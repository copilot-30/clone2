<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patients';

    protected $keyType = 'string';

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
        'middle_name',
        'suffix',
        'date_of_birth',
        'age',
        'sex',
        'primary_mobile',
        'address',
    ];

    protected $casts = [
        'id' => 'string',
        'date_of_birth' => 'date',
        'age' => 'integer',
    ];

    public $incrementing = false;

    public function getFullNameAttribute()
    {
        $n= $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name . ' ' . $this->suffix;
        return ucwords ($n);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
        });

        static::saving(function ($model) {
            if ($model->date_of_birth) {
                $model->age = $model->date_of_birth->diffInYears(\Carbon\Carbon::now());
            }
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
        return $this->hasOne(Subscription::class);
    }

    // public function medicalBackground()
    // {
    //     return $this->hasOne(MedicalBackground::class, 'patient_id');
    // }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'patient_id');
    }

    public function labResults()
    {
        return $this->hasMany(LabResult::class, 'patient_id');
    }

    // public function labTestRequests()
    // {
    //     return $this->hasMany(LabRequest::class, 'patient_id');
    // }

    public function patientNotes()
    {
        return $this->hasMany(PatientNote::class, 'patient_id');
    }

    // public function patientVisits()
    // {
    //     return $this->hasMany(PatientVisit::class, 'patient_id');
    // }

    // public function prescriptions()
    // {
    //     return $this->hasMany(Prescription::class, 'patient_id');
    // }

    public function sharedCases()
    {
        return $this->hasMany(SharedCase::class, 'patient_id');
    }

    public function soapNotes()
    {
        return $this->hasMany(Consultation::class, 'patient_id');
    }

    public function attendingPhysician()
    {
        return $this->hasOne(AttendingPhysician::class, 'patient_id');
    }

    // Accessor to calculate age based on date_of_birth
    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return $this->date_of_birth->diffInYears(\Carbon\Carbon::now());
        }
        return null;
    }


    
    public function patientPrescriptions()
    {
        return $this->hasMany(PatientPrescription::class, 'patient_id');
    }

    public function patientTestRequests()
    {
        return $this->hasMany(PatientTestRequest::class, 'patient_id');
    }
}