<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spot;

class SpotImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'spot_id', 'image'
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
