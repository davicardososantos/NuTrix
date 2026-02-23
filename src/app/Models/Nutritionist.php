<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nutritionist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'crn',
        'specialization',
        'bio',
        'photo_path',
        'clinic_address',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
