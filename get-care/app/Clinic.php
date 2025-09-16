<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for UUID generation

class Clinic extends Model
{
    protected $table = 'clinics'; // Explicitly define the table name
    protected $keyType = 'string';
    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'postal_code',
        'phone',
        'email',
        'operating_hours',
        'facilities',
        'is_active',
        'is_hospital',
    ];

    protected $casts = [
        'id' => 'string',
        'operating_hours' => 'array', // Cast jsonb to array
        'facilities' => 'array', // Cast ARRAY to array
        'is_active' => 'boolean',
        'is_hospital' => 'boolean',
    ];

    public $incrementing = false; // Disable auto-incrementing for UUID

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid(); // Generate UUID for new models
        });
    }

    // Relationships
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'clinic_id');
    }

    public function doctorAvailability()
    {
        return $this->hasMany(DoctorAvailability::class, 'clinic_id');
    }

    public function doctorClinics()
    {
        return $this->hasMany(DoctorClinic::class, 'clinic_id');
    }
}