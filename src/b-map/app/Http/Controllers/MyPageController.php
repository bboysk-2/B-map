<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Spot;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $avatarPath = $request->user()->avatar ?: asset('/images/no_avatar.png');

        // ログインユーザーが投稿したスポット
        $posts = Spot::with('spotImages', 'reviews')
        ->where('user_id', $request->user()->id)
        ->orderBy('created_at', 'desc')
        ->get(['id', 'name', 'address']);

        // ログインユーザーがお気に入り登録したスポット
        $favorites = Spot::with('spotImages', 'reviews')
        ->whereHas('favorites', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->get(['id', 'name', 'address']);

        // ログインユーザーが投稿したレビュー
        $reviews = $request->user()->reviews()
        ->with('spot')
        ->orderBy('created_at', 'desc')
        ->get();

        // 投稿したスポットの中でユーザーがお気に入りにしているかどうかを判定するプロパティを追加
        $posts->map(function ($post) use ($request) {
            $post->isFavorite = $post->favorites->contains(function ($favorite) use ($request) {
                return $favorite->user_id === $request->user()->id;
            });
            return $post;
        });

        $favorites->map(function ($favorite) {
            $favorite->isFavorite = true;
            return $favorite;
        });

        return Inertia::render('MyPage', [
            'avatarPath' => $avatarPath,
            'posts' => $posts,
            'favorites' => $favorites,
            'reviews' => $reviews,
        ]);
    }
}