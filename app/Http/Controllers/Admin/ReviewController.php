<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['customer.user', 'item']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('item', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('comment', 'like', "%{$search}%");
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'oldest') {
                $query->oldest('id');
            } else {
                $query->latest('id');
            }
        } else {
            $query->latest('id');
        }

        $reviews = $query->paginate(15)->withQueryString();

        if ($request->ajax()) {
            return view('admin.reviews.partials.table', compact('reviews'))->render();
        }

        return view('admin.reviews.index', compact('reviews'));
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:visible,hidden'
        ]);

        $review->update(['status' => $request->status]);

        return back()->with('success', 'Review status updated.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted successfully.');
    }
}
