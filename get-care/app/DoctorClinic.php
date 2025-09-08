<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for UUID generation

class DoctorClinic extends Model
{
    protected $table = 'doctor_clinics';

    protected $fillable = [
        'doctor_id',
        'clinic_id',
        'is_primary',
        'available_days',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'id' => 'string',
        'is_primary' => 'boolean',
        'available_days' => 'array',
    ];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }
}