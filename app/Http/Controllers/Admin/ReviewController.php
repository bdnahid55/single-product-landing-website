<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of customer reviews.
     */
    public function index()
    {
        $reviews = Review::latest()->get();
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_location' => 'nullable|string|max:255',
            'rating'            => 'required|integer|min:1|max:5',
            'review_text'       => 'required|string|max:1000',
        ]);

        Review::create([
            'customer_name'     => $validated['customer_name'],
            'customer_location' => $validated['customer_location'],
            'rating'            => $validated['rating'],
            'review_text'       => $validated['review_text'],
            'is_approved'       => $request->boolean('is_approved', true),
        ]);

        return back()->with('success', 'Review added successfully!');
    }

    /**
     * Toggle approval status of a review.
     */
    public function toggleApproval(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        return back()->with('success', 'Review visibility status updated!');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted successfully!');
    }
}
