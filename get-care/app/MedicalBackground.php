<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for UUID generation

class MedicalBackground extends Model
{
    protected $table = 'medical_backgrounds';

    protected $fillable = [
        'patient_id',
        'known_conditions',
        'allergies',
        'previous_surgeries',
        'family_history',
        'current_medications',
        'supplements',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}