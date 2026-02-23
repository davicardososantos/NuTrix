<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'weight',
        'height',
        'body_fat_percentage',
        'clinical_history',
        'calorie_target',
        'medical_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
