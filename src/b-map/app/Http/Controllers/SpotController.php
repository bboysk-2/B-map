<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpotRequest;
use App\Http\Requests\UpdateSpotRequest;
use App\Models\Spot;
use App\Models\User;
use App\Models\SpotImage;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginateSpots = Spot::with('spotImages', 'reviews')->orderBy('created_at', 'desc')
        ->paginate(10, ['id', 'name', 'address', 'latitude', 'longitude']);

        if (auth()->user()) {
            $paginateSpots->map(function ($spot) {
                $spot->isFavorite = $spot->favorites->contains(function ($favorite) {
                    return $favorite->user_id === auth()->user()->id;
                });
                return $spot;
            });
        }

        return Inertia::render('Spots/Index', [
            'allSpots' => Spot::with('spotImages')->get(['id', 'name', 'latitude', 'longitude']), //マップのマーカー描画用
            'spots' => $paginateSpots,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Spots/Create', [
            'error' => session('error'),
        ]);  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSpotRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSpotRequest $request)
    {
        // 同じ住所が既に登録されているかチェック
        $exists = Spot::where('address', $request->address
        )->exists();

        if ($exists) {
            return back()->with('error', '同じ住所が既に登録されています。');
        }

        DB::beginTransaction(); 

        try {
            $spot = Spot::create([
                'user_id' => $request->user()->id,
                'name' => $request->spot_name,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'category' => $request->category,
                'space' => $request->space,
                'floor_material' => $request->floor_material,
                'slipping' => $request->slipping,
                'usage_fee' => $request->usage_fee,
                'available_times' => $request->available_times,
                'volume' => $request->volume,
                'reservation' => $request->reservation,
                'remarks' => $request->remarks,
            ]);
    
            if ($request->hasFile('spot_images')) {
                $disk = Storage::disk('s3');
    
                foreach ($request->file('spot_images') as $image) {
                    $fileName = $disk->putFile('spot_image', $image);
                    $url = $disk->url($fileName);
                    SpotImage::create([
                        'spot_id' => $spot->id, // SpotとSpotImageモデルインスタンスを関連付ける
                        'image' => $url,
                    ]);
                }
            }
    
            DB::commit(); 
    
            return redirect()->route('spots.index')->with('success', 'スポットを投稿しました。');
        } catch (\Exception $e) {
            DB::rollBack(); 
            log::error($e->getMessage());
            return back()->with('error', 'スポットの投稿に失敗しました。');
        }       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function show(Spot $spot)
    {
        $spot->load('spotImages', 'reviews.user');

        if (auth()->user()) {
            $spot->isFavorite = $spot->favorites->contains(function ($favorite) {
                return $favorite->user_id === auth()->user()->id;
            });
        }

        return Inertia::render('Spots/Show', [
            'spot' => $spot,
            'success' => session('success'),
            'error' => session('error'),
            'successReview' => session('successReview'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function edit(Spot $spot, Request $request)
    {
        if ($spot->user_id !== $request->user()->id) {
            return redirect()->route('spots.show', $spot->id)->with('error', '他のユーザーの投稿は編集できません');
        }

        $spot->load('spotImages');

        return Inertia::render('Spots/Edit', [
            'spot' => $spot,
            'error' => session('error'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSpotRequest  $request
     * @param  \App\Models\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSpotRequest $request, Spot $spot)
    {
        if ($spot->address !== $request->address) {
            $exists = Spot::where('address', $request->address
        )->exists();

        if ($exists) {
            return back()->with('error', '同じ住所が既に登録されています。');
        }
        }

        DB::beginTransaction(); 
        
        try {
            if ($request->deleted_images) {
                $disk = Storage::disk('s3');
    
                foreach ($request->deleted_images as $deletedImage) {
                    SpotImage::where('id', $deletedImage["id"])->delete();
                    $currentFileName = basename($deletedImage["image"]);
                    $disk->delete('spot_image/' . $currentFileName);
                }
            }
    
            if ($request->hasFile('spot_images')) {
                if (!$request->deleted_images) {
                    $disk = Storage::disk('s3');
                }
    
                foreach ($request->file('spot_images') as $image) {
                    $fileName = $disk->putFile('spot_image', $image);
                    $url = $disk->url($fileName);
                    SpotImage::create([
                        'spot_id' => $spot->id, 
                        'image' => $url,
                    ]);
                }
            }
    
            $spot->update([
                'name' => $request->spot_name,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'category' => $request->category,
                'space' => $request->space,
                'floor_material' => $request->floor_material,
                'slipping' => $request->slipping,
                'usage_fee' => $request->usage_fee,
                'available_times' => $request->available_times,
                'volume' => $request->volume,
                'reservation' => $request->reservation,
                'remarks' => $request->remarks,
            ]);
    
            DB::commit(); 
    
            return redirect()->route('spots.show', $spot->id)->with('success', 'スポットを更新しました。');
        } catch (\Exception $e) {
            DB::rollBack(); 
            log::error($e->getMessage());
            return back()->with('error', 'スポットの更新に失敗しました。');
        }       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spot $spot)
    {
        DB::beginTransaction(); 

        try {
            if ($spot->spotImages) {
                $disk = Storage::disk('s3');
    
                foreach ($spot->spotImages as $spotImage) {
                    $currentFileName = basename($spotImage->image);
                    $disk->delete('spot_image/' . $currentFileName);
                }
            }

            $spot->delete();
    
            DB::commit(); 
    
            return redirect()->route('mypage')->with('deleteId', $spot->id);
        } catch (\Exception $e) {
            DB::rollBack(); 
            log::error($e->getMessage());
            return back()->with('error', 'スポットの削除に失敗しました。');
        }
    }
    
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
