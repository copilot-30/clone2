<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for UUID generation

class PatientVisit extends Model
{
    protected $table = 'patient_visits';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_id',
        'visit_date',
        'visit_type',
        'chief_complaint',
        'diagnosis',
    ];

    protected $casts = [
        'id' => 'string',
        'visit_date' => 'date',
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

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function soapNotes()
    {
        return $this->hasMany(Consultation::class, 'visit_id');
    }
}