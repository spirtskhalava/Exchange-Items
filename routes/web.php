<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProductVerificationController;
use App\Http\Controllers\SavedSearchController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CashPaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

/* ══════════════════════════════════════════════════════════════
 |  PUBLIC ROUTES  (no login required)
 |  Visible to everyone: Home, Browse, Product detail,
 |  Trade Protection, Terms, Google OAuth
 ══════════════════════════════════════════════════════════════ */

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Google OAuth
Route::get('/auth/google',          [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Static info pages
Route::get('/trade-protection', fn() => view('pages.trade-protection'))->name('trade.protection');
Route::get('/terms',            fn() => view('pages.terms'))->name('terms');

// Product browsing (read-only) — public
// NOTE: products/create MUST be declared before products/{product} to avoid wildcard collision.
// The create & store routes themselves live inside the auth group below, but the URI
// ordering is guaranteed because explicit segments always beat route-model-binding in Laravel.
Route::get('products',           [ProductController::class, 'index'])->name('products.index');
Route::get('products/create',    [ProductController::class, 'create'])->name('products.create')->middleware('auth');
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

// Seller profile — public browsing
Route::get('/seller/{id}/items', [ProductController::class, 'showSellerItems'])->name('seller.items');

// Dev utility (protect in production behind admin middleware if desired)
Route::get('/migrate-now', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migrations executed!';
});


/* ══════════════════════════════════════════════════════════════
 |  AUTHENTICATED ROUTES  (login required)
 |  Everything below redirects to /login if not authenticated.
 ══════════════════════════════════════════════════════════════ */

Route::middleware(['auth'])->group(function () {

    /* ── Products: create / edit / delete ─────────────────── */
    // products.create is declared above (before the {product} wildcard) with ->middleware('auth')
    Route::post('products',            [ProductController::class, 'store'])->name('products.store');
    Route::put('products/{product}',   [ListingController::class, 'update'])->name('products.update');
    Route::post('products/{id}/remove-image', [ProductController::class, 'removeImage'])->name('products.removeImage');
    Route::post('/products/{product}/verify', [ProductVerificationController::class, 'verify'])->name('products.verify');

    /* ── Listings (My items dashboard) ────────────────────── */
    Route::resource('listings', ListingController::class)->except(['show']);
    Route::get('listing/{id}/edit', [ListingController::class, 'edit']);

    /* ── Exchanges ─────────────────────────────────────────── */
    Route::get('products/{product}/offer',  [ExchangeController::class, 'create'])->name('exchanges.create');
    Route::post('products/{product}/offer', [ExchangeController::class, 'store'])->name('exchanges.store');
    Route::get('exchanges',                 [ExchangeController::class, 'index'])->name('exchanges.index');
    Route::patch('exchanges/{exchange}',    [ExchangeController::class, 'updateStatus'])->name('exchanges.updateStatus');
    Route::delete('exchanges/{exchange}/cancel', [ExchangeController::class, 'cancel'])->name('exchanges.cancel');

    /* ── Offers ────────────────────────────────────────────── */
    Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');

    /* ── Wishlist ──────────────────────────────────────────── */
    Route::get('/wishlist',              [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}',   [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{id}',      [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    /* ── Cash top-up payment ───────────────────────────────── */
    Route::get( '/exchanges/{exchange}/pay-cash',              [CashPaymentController::class, 'create'])->name('cash-payment.create');
    Route::get( '/exchanges/{exchange}/pay-cash/success',      [CashPaymentController::class, 'success'])->name('cash-payment.success');
    Route::post('/exchanges/{exchange}/pay-cash/choose-cash',  [CashPaymentController::class, 'chooseCash'])->name('cash-payment.choose-cash');
    Route::post('/exchanges/{exchange}/pay-cash/confirm-cash', [CashPaymentController::class, 'confirmCash'])->name('cash-payment.confirm-cash');

    /* ── Profile ───────────────────────────────────────────── */
    Route::get('/profile',            [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile',            [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar',  [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');

    /* ── Trade history ─────────────────────────────────────── */
    Route::get('/trades', [TradeController::class, 'index'])->name('trades.index');

    /* ── Notifications ─────────────────────────────────────── */
    Route::post('/notifications/{id}/read', function ($id) {
        Auth::user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    })->name('notifications.read');
    Route::post('/notifications/read-all', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');

    /* ── Messages ──────────────────────────────────────────── */
    Route::get('/messages',                     [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/store',              [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/fetch',               [MessageController::class, 'fetchMessages'])->name('messages.fetch');
    Route::get('/messages/open/{sellerId}',     [MessageController::class, 'openChatWithSeller'])->name('messages.openChatWithSeller');
    Route::post('/messages/mark-read',          [MessageController::class, 'markAsRead'])->name('messages.markRead');
    Route::get('/messages/search-users',        [MessageController::class, 'searchUsers'])->name('messages.searchUsers');

    /* ── Reviews ───────────────────────────────────────────── */
    Route::post('/products/{id}/review', [ProductController::class, 'storeReview'])->name('products.review.store');
    Route::post('/users/{id}/review',    [UserReviewController::class, 'store'])->name('user.reviews.store');

    /* ── Saved searches ────────────────────────────────────── */
    Route::post('/saved-searches',                 [SavedSearchController::class, 'store'])->name('saved-searches.store');
    Route::delete('/saved-searches/{savedSearch}', [SavedSearchController::class, 'destroy'])->name('saved-searches.destroy');
    Route::get('/saved-searches',                  [SavedSearchController::class, 'index'])->name('saved-searches.index');

    /* ── Insurance ─────────────────────────────────────────── */
    Route::post('exchanges/{exchange}/insurance/opt-in',    [InsuranceController::class, 'optIn'])->name('insurance.optIn');
    Route::post('exchanges/{exchange}/insurance/valuation', [InsuranceController::class, 'submitValuation'])->name('insurance.submitValuation');
    Route::post('exchanges/{exchange}/insurance/respond',   [InsuranceController::class, 'respondValuation'])->name('insurance.respondValuation');
    Route::get('exchanges/{exchange}/insurance/pay',        [InsuranceController::class, 'createPayment'])->name('insurance.pay');
    Route::get('exchanges/{exchange}/insurance/success',    [InsuranceController::class, 'paymentSuccess'])->name('insurance.paymentSuccess');
    Route::post('exchanges/{exchange}/insurance/received',  [InsuranceController::class, 'markReceived'])->name('insurance.markReceived');

    /* ── Disputes ──────────────────────────────────────────── */
    Route::get('exchanges/{exchange}/dispute',  [DisputeController::class, 'create'])->name('disputes.create');
    Route::post('exchanges/{exchange}/dispute', [DisputeController::class, 'store'])->name('disputes.store');
    Route::get('admin/disputes',               fn() => redirect()->route('admin.disputes.index'))->name('admin.disputes.index');
    Route::post('admin/disputes/{dispute}/resolve', [DisputeController::class, 'adminResolve'])->name('admin.disputes.resolve');
});


/* ══════════════════════════════════════════════════════════════
 |  ADMIN PANEL  (auth + admin role required)
 ══════════════════════════════════════════════════════════════ */

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                                    [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',                               [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle-status',         [AdminController::class, 'toggleUserStatus'])->name('users.toggleStatus');
    Route::post('/users/{user}/toggle-admin',          [AdminController::class, 'toggleAdminRole'])->name('users.toggleAdmin');
    Route::put('/users/{user}',                        [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}',                     [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/products',                            [AdminController::class, 'products'])->name('products');
    Route::post('/products/{product}/toggle',          [AdminController::class, 'toggleProduct'])->name('products.toggle');
    Route::put('/products/{product}',                  [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}',               [AdminController::class, 'deleteProduct'])->name('products.delete');
    Route::get('/finances',                            [AdminController::class, 'finances'])->name('finances');
    Route::get('/disputes',                            [DisputeController::class, 'adminIndex'])->name('disputes.index');
    Route::post('/disputes/{dispute}/resolve',         [DisputeController::class, 'adminResolve'])->name('disputes.resolve');
});
