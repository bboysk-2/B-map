<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;

class SpotSearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $spots = Spot::with('spotImages', 'reviews')
            ->where('name', 'LIKE', "%{$keyword}%")
            ->orWhere('address', 'LIKE', "%{$keyword}%")
            ->orWhere('category', 'LIKE', "%{$keyword}%")
            ->orWhere('space', 'LIKE', "%{$keyword}%")
            ->orWhere('floor_material', 'LIKE', "%{$keyword}%")
            ->orWhere('slipping', 'LIKE', "%{$keyword}%")
            ->orWhere('usage_fee', 'LIKE', "%{$keyword}%")
            ->orWhere('available_times', 'LIKE', "%{$keyword}%")
            ->orWhere('volume', 'LIKE', "%{$keyword}%")
            ->orWhere('reservation', 'LIKE', "%{$keyword}%")
            ->orWhere('remarks', 'LIKE', "%{$keyword}%")
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['id', 'name', 'address']); 
    
            $spots->withPath("/api/search/?keyword={$keyword}");
        return response()->json($spots);
    }
}
