<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpotRequest;
use App\Http\Requests\UpdateSpotRequest;
use App\Models\Spot;
use App\Models\SpotImage;
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
        // Spotモデルのデータをcreated_atの降順（新しいデータが先）で取得
        $spots = Spot::with('spotImages')->orderBy('created_at', 'desc')->get(['id', 'name', 'address', 'latitude', 'longitude']);
        return Inertia::render('Spots/Index', [
            'spots' => $spots,
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
        // 同じスポット名もしくは住所が既に登録されているかチェック
        $exists = Spot::where(function ($query) use ($request) {
            $query->where('name', $request->spot_name)
                  ->orWhere('address', $request->address);
        })
        ->where('user_id', $request->user()->id)
        ->exists();

        if ($exists) {
            return redirect()->route('spots.create')->with('error', '同じスポット名もしくは住所が既に登録されています。');
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
    
            $paths = []; // 保存した画像のパスを格納する配列（使う機会なかったら消す）
    
            if ($request->hasFile('spot_images')) {
                $disk = Storage::disk('s3');
    
                foreach ($request->file('spot_images') as $image) {
                    $fileName = $disk->putFile('spot_image', $image);
                    $url = $disk->url($fileName);
                    SpotImage::create([
                        'spot_id' => $spot->id, // SpotとSpotImageモデルインスタンスを関連付ける
                        'image' => $url,
                    ]);
    
                    $paths[] = $url; // 保存した画像のパスを配列に追加（使う機会なかったら消す） 
                }
            }
    
            DB::commit(); 
    
            return redirect()->route('spots.index')->with('success', 'スポットを投稿しました。');
        } catch (\Exception $e) {
            DB::rollBack(); 
            log::error($e->getMessage());
            return redirect()->route('spots.create')->with('error', 'スポットの投稿に失敗しました。');
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
        $spot->load('spotImages');
        return Inertia::render('Spots/Show', [
            'spot' => $spot,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function edit(Spot $spot)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spot $spot)
    {
        //
    }
}
