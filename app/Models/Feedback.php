<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedbacks'; 
    protected $fillable = [
        'positivity', 
        'comment_description', 
        'image', 'amount_purchased', 
        'served_by', 
        'related_entity_id', 
        'related_entity_type',
        'rating_count',
        'user_id'
    ];

    public function relatedEntity()
    {
        return $this->morphTo();
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
