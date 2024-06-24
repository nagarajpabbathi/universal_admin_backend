<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Luckydraw extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'logo_image',
        'cover_image',
        'text_area',
        'category',
        'prize_amount',
        'entry_amount',
        'max_tickets_per_submission',
        'lucky_number_digits',
        'allow_upload_receipt',
        'expired_date',
        'draw_date',
    ];
}
