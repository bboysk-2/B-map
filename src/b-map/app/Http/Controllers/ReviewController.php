<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use App\Models\Spot;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function create(Request $request) {
        $spot = [
            'spot_id' => $request->spot_id,
            'spot_name' => $request->spot_name,
        ];

        return Inertia::render('Reviews/Create', [
            'spot' => $spot,
            'error' => session('error'),
        ]);
    }

    public function store(StoreReviewRequest $request) {
        $exists = Review::where('spot_id', $request->spot_id)
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($exists) {
            return back()->with('error', '既にこのスポットにレビューを投稿しています');
        }

        Review::create([
            'spot_id' => $request->spot_id,
            'user_id' => $request->user()->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('spots.show',  $request->spot_id)->with('success', 'レビューを投稿しました');
    }

    public function edit(Request $request, $id) {
        $review = Review::with('spot')
        ->where('id', $id)
        ->where('user_id', $request->user()->id)
        ->first();

        if (!$review) {
            return back()->with('error', 'レビューが見つかりません');
        }

        dd($review);

        return Inertia::render('Reviews/Edit', [
            'review' => $review,
            'error' => session('error'),
        ]);
    }
}
