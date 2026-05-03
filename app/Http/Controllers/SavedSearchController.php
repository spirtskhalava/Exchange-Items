<?php
namespace App\Http\Controllers;

use App\Models\SavedSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedSearchController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'query'     => 'nullable|string|max:100',
            'category'  => 'nullable|string|max:60',
            'condition' => 'nullable|string|max:30',
        ]);

        $q         = $request->input('query')     ?: null;
        $category  = $request->input('category')  ?: null;
        $condition = $request->input('condition') ?: null;

        // Prevent exact duplicates for this user
        $exists = SavedSearch::where('user_id', Auth::id())
            ->where('query',     $q)
            ->where('category',  $category)
            ->where('condition', $condition)
            ->exists();

        if ($exists) {
            return back()->with('info', 'You already have this search saved.');
        }

        // Cap at 10 saved searches per user
        if (SavedSearch::where('user_id', Auth::id())->count() >= 10) {
            return back()->with('error', 'Maximum 10 saved searches allowed. Remove one first.');
        }

        SavedSearch::create([
            'user_id'   => Auth::id(),
            'query'     => $q,
            'category'  => $category,
            'condition' => $condition,
        ]);

        return back()->with('success', 'Search saved! We\'ll notify you when matching items are listed.');
    }

    public function destroy(SavedSearch $savedSearch)
    {
        abort_unless($savedSearch->user_id === Auth::id(), 403);
        $savedSearch->delete();
        return back()->with('success', 'Saved search removed.');
    }

    public function index()
    {
        $searches = SavedSearch::where('user_id', Auth::id())
            ->latest()->get();
        return view('saved_searches.index', compact('searches'));
    }
}
