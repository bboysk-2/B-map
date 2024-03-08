<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'spot_id' => ['required','exists:spots,id'] , //spots テーブルの id カラムに存在しているか
            'rating' => ['required','integer','min:1','max:5'],
            'comment' => ['nullable','string'],
        ]);

        Review::create([
            'spot_id' => $request->spot_id,
            'user_id' => $request->user()->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('spots.show',  $request->spot_id)->with('success', 'レビューを投稿しました');
    }
}
