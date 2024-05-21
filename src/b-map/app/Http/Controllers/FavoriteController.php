<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Spot;

class FavoriteController extends Controller
{
    public function addFavorite(Request $request, Spot $spot)
    {
        Favorite::create([
            'user_id' => $request->user()->id,
            'spot_id' => $spot->id,
        ]);
    }

    public function removeFavorite(Request $request, Spot $spot)
    {
        Favorite::where('spot_id', $spot->id)
        ->where('user_id', $request->user()->id)
        ->delete();
    }
}
