<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'featured_image',
        'text_area',
        'category',
        'question',
        'answer1',
        'answer2',
        'correct_answer',
        'youtube_link',
        'upload_video',
        'shop_id'
    ];

    /**
     * Get the shop that owns the news.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
