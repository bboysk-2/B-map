<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use App\Models\Spot;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

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

        try {
                Review::create([
                'spot_id' => $request->spot_id,
                'user_id' => $request->user()->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
    
            return redirect()->route('spots.show',  $request->spot_id)->with('successReviewMessage', 'レビューを投稿しました');
        } catch (Exception $e) {
            log::error($e->getMessage());
            return back()->with('error', 'レビューの投稿に失敗しました');
        }
    }


    public function edit(Request $request, Review $review) {
        if ($review->user_id !== $request->user()->id) {
            return back()->with('error', '他のユーザーのレビューは編集できません');
        }

        $review->load('spot');

        if (!$review) {
            return back()->with('error', 'レビューが見つかりません');
        }

        return Inertia::render('Reviews/Edit', [
            'review' => $review,
            'error' => session('error'),
        ]);
    }


    public function update(UpdateReviewRequest $request, Review $review) {
        try {
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            return redirect()->route('spots.show', $review->spot_id)->with('successReviewMessage', 'レビューを更新しました');
        } catch (Exception $e) {
            log::error($e->getMessage());
            return back()->with('error', 'レビューの更新に失敗しました');
        }
    }


    public function destroy(Request $request, Review $review) {
        try {
            if ($review->user_id !== $request->user()->id) {
                return back()->with('error', '他のユーザーのレビューは削除できません');
            }

            $review->delete();

            return redirect()->route('mypage')->with('deleteId', $review->id);
        } catch (Exception $e) {
            log::error($e->getMessage());
            return back()->with('error', 'レビューの削除に失敗しました');
        }
    }
}
