<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'media_url',
        'media_type',
        'section',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function section()
    {
        return $this->belongsTo(ShopSection::class);
    }
}
