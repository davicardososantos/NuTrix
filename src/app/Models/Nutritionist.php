<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nutritionist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'cpf',
        'crn',
        'phone',
        'specialization',
        'clinic_address',
        'bio',
        'photo_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all patients for the nutritionist.
     */
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
