<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopService extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'title',
        'description',
        'cost',
        'image',
        'rating',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
