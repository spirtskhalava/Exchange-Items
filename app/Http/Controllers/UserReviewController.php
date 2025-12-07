<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReview;
use Illuminate\Support\Facades\Auth;

class UserReviewController extends Controller
{
    public function store(Request $request, $userId)
{
    $reviewerId = Auth::id();

    // 1. Prevent self-review
    if ($reviewerId == $userId) {
        return back()->with('error', 'You cannot review yourself.');
    }

    // 2. CHECK: Has this user already reviewed this seller?
    $existingReview = UserReview::where('reviewer_id', $reviewerId)
                                ->where('user_id', $userId)
                                ->exists();

    if ($existingReview) {
        return back()->with('error', 'You have already submitted a review for this user.');
    }

    // 3. Validate input
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    // 4. Create Review
    UserReview::create([
        'reviewer_id' => $reviewerId,
        'user_id'     => $userId,
        'rating'      => $request->rating,
        'comment'     => $request->comment,
    ]);

    return back()->with('success', 'User review submitted!');
}
}