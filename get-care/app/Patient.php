<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patients';

    protected $fillable = [
        'user_id',
        'blood_type',
        'civil_status',
        'philhealth_no',
        'medical_conditions',
        'allergies',
        'surgeries',
        'family_history',
        'medications',
        'supplements',
        'tag',
        'first_name',
        'last_name',
        'suffix',
        'nickname',
        'date_of_birth',
        'age',
        'sex',
        'primary_mobile',
        'email',
    ];

    protected $casts = [
        'id' => 'string',
        'date_of_birth' => 'date',
        'age' => 'integer',
    ];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function medicalBackground()
    {
        return $this->hasOne(MedicalBackground::class, 'patient_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'patient_id');
    }

    public function labResults()
    {
        return $this->hasMany(LabResult::class, 'patient_id');
    }

    public function labTestRequests()
    {
        return $this->hasMany(LabRequest::class, 'patient_id');
    }

    public function patientNotes()
    {
        return $this->hasMany(PatientNote::class, 'patient_id');
    }

    public function patientVisits()
    {
        return $this->hasMany(PatientVisit::class, 'patient_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    public function sharedCases()
    {
        return $this->hasMany(SharedCase::class, 'patient_id');
    }

    public function soapNotes()
    {
        return $this->hasMany(Consultation::class, 'patient_id');
    }
}