<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
        protected $keyType = 'string';
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'clinic_id',
        'appointment_datetime',
        'duration_minutes',
        'type',
        'subtype',
        'soap_note_id', // Add this line
        'status',
        'is_online',
        'meet_link',
        'chief_complaint',
        'notes',
        'admin_notes',
        'cancellation_reason',
    ];

    protected $casts = [
        'id' => 'string',
        'appointment_datetime' => 'datetime',
        'is_online' => 'boolean',
        'clinic_id' => 'string',
    ];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}