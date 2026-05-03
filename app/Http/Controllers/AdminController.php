<?php
namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\ExchangeInsurance;
use App\Models\InsuranceDispute;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    /* ─── Dashboard ──────────────────────────────────────────── */
    public function dashboard()
    {
        // Cache aggregate stats for 5 minutes — they don't need to be real-time
        $stats = Cache::remember('admin.dashboard.stats', 300, function () {
            return [
                'users'             => User::count(),
                'products'          => Product::where('hide', 0)->count(),
                'trades'            => Exchange::where('status', 'accepted')->count(),
                'pending_offers'    => Exchange::where('status', 'pending')->count(),
                'disputes'          => InsuranceDispute::where('status', 'pending')->count(),
                'escrow_total'      => ExchangeInsurance::whereIn('escrow_status', ['locked', 'disputed'])
                                            ->get()
                                            ->sum(fn($i) => $i->requesterLockedAmount() + $i->responderLockedAmount()),
                'new_users_week'    => User::where('created_at', '>=', now()->subDays(7))->count(),
                'new_listings_week' => Product::where('created_at', '>=', now()->subDays(7))->count(),
            ];
        });

        // Recent lists are cheap — no cache needed, always fresh
        $recentUsers    = User::latest()->limit(5)->get();
        $recentProducts = Product::with('user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentProducts'));
    }

    private function bustDashboardCache(): void
    {
        Cache::forget('admin.dashboard.stats');
    }

    /* ─── Users ───────────────────────────────────────────────── */
    public function users(Request $request)
    {
        $query = User::withCount(['products', 'reviewsReceived']);

        if ($q = $request->get('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($request->get('role') === 'admin') {
            $query->whereHas('roles', fn($r) => $r->where('name', 'admin'));
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Cannot change your own status.');
        }
        $user->status = $user->status === 'active' ? 'banned' : 'active';
        $user->save();
        $this->bustDashboardCache();
        return back()->with('success', "User {$user->name} is now {$user->status}.");
    }

    public function toggleAdminRole(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Cannot change your own role.');
        }
        if ($user->hasRole('admin')) {
            $user->removeRole('admin');
            $msg = "{$user->name} admin role removed.";
        } else {
            $user->assignRole('admin');
            $msg = "{$user->name} is now an admin.";
        }
        return back()->with('success', $msg);
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'email'  => 'required|email|max:150|unique:users,email,' . $user->id,
            'phone'  => 'nullable|string|max:30',
            'status' => 'required|in:active,banned',
        ]);

        $user->fill($request->only('name', 'email', 'phone', 'status'))->save();
        $this->bustDashboardCache();

        return back()->with('success', "User <strong>{$user->name}</strong> updated.");
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Cannot delete yourself.');
        }
        $user->delete();
        $this->bustDashboardCache();
        return back()->with('success', "User deleted.");
    }

    /* ─── Content / Products ──────────────────────────────────── */
    public function products(Request $request)
    {
        $query = Product::with('user');

        if ($q = $request->get('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->get('status') === 'hidden') {
            $query->where('hide', 1);
        } elseif ($request->get('status') === 'active') {
            $query->where('hide', 0);
        }

        if ($cat = $request->get('category')) {
            $query->where('category', $cat);
        }

        $products = $query->latest()->paginate(20)->withQueryString();

        return view('admin.products', compact('products'));
    }

    public function toggleProduct(Product $product)
    {
        $product->hide = $product->hide ? 0 : 1;
        $product->save();
        $this->bustDashboardCache();
        return back()->with('success', 'Product ' . ($product->hide ? 'hidden' : 'made visible') . '.');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'description' => 'nullable|string|max:5000',
            'category'    => 'required|string|max:60',
            'condition'   => 'required|in:New,Like New,Good,Fair,Poor',
            'hide'        => 'required|in:0,1',
        ]);

        $product->fill($request->only('name', 'description', 'category', 'condition'))->save();
        $product->hide = (int) $request->hide;
        $product->save();
        $this->bustDashboardCache();

        return back()->with('success', "Listing <strong>{$product->name}</strong> updated.");
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        $this->bustDashboardCache();
        return back()->with('success', 'Product deleted.');
    }

    /* ─── Finances ────────────────────────────────────────────── */
    public function finances(Request $request)
    {
        $insurances = ExchangeInsurance::with([
            'exchange.requester',
            'exchange.responder',
            'exchange.requestedProduct',
            'exchange.offeredProduct',
            'dispute',
        ])->whereNot('escrow_status', 'none')
          ->latest()
          ->paginate(15);

        $totals = [
            'locked'   => ExchangeInsurance::where('escrow_status', 'locked')->count(),
            'disputed' => ExchangeInsurance::where('escrow_status', 'disputed')->count(),
            'released' => ExchangeInsurance::where('escrow_status', 'released')->count(),
            'pending'  => ExchangeInsurance::where('escrow_status', 'pending_payment')->count(),
            'amount_locked' => ExchangeInsurance::whereIn('escrow_status', ['locked','disputed'])
                ->get()->sum(fn($i) => $i->requesterLockedAmount() + $i->responderLockedAmount()),
            'amount_released' => ExchangeInsurance::where('escrow_status', 'released')
                ->get()->sum(fn($i) => $i->requesterLockedAmount() + $i->responderLockedAmount()),
            'fees_collected' => ExchangeInsurance::where('escrow_status', 'released')->count() * 10, // $5 x2
        ];

        return view('admin.finances', compact('insurances', 'totals'));
    }
}
