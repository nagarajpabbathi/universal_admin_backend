<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'facebook',
        'twitter',
        'instagram'
    ];

    public function media()
    {
        return $this->hasMany(ShopMedia::class);
    }

    public function services()
    {
        return $this->hasMany(ShopService::class);
    }

    public function sections()
    {
        return $this->hasMany(ShopSection::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
