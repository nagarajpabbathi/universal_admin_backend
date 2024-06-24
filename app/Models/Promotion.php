<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Promotion extends Model
{
    protected $fillable = [
        'title',
        'featured_image',
        'bonus_amount',
        'is_free',
        'description',
        'question',
        'answer1',
        'answer2',
        'correct_answer',
        'start_date',
        'end_date',
        'is_button_allowed',
        'button_label',
        'button_link',

        'related_entity_id',
        'related_entity_type',

        'user_id'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function relatedEntity()
    {
        return $this->morphTo();
    }

    //  Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for active promotions
    // public function scopeActive(Builder $query)
    // {
    //     return $query->where('start_date', '<=', now())
    //         ->where('end_date', '>=', now());
    // }

    // // Scope for free promotions
    // public function scopeFree(Builder $query)
    // {
    //     return $query->where('is_free', true);
    // }
   
}
