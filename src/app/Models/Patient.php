<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'nutritionist_id',
        'user_id',
        'full_name',
        'email',
        'birth_date',
        'biological_sex',
        'phone',
        'profession',
        'work_routine',
        'main_goal',
        'referral_source',
        'code',
        'weight',
        'height',
        'body_fat_percentage',
        'clinical_history',
        'calorie_target',
        'medical_notes',
    ];

    /**
     * Get the nutritionist that owns the patient.
     */
    public function nutritionist()
    {
        return $this->belongsTo(Nutritionist::class);
    }

    /**
     * Get the user associated with the patient.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
