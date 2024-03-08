<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\SpotImage;

class Spot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name', 
        'address', 
        'latitude', 
        'longitude', 
        'category', 
        'floor_material', 
        'slipping', 
        'space', 
        'usage_fee', 
        'available_times', 
        'volume', 
        'reservation', 
        'remarks'
    ];

    public function spotImages() {
        return $this->hasMany(SpotImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
