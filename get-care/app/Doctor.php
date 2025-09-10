<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctor_profiles';

    protected $fillable = [
        'user_id',
        'specialization',
        'years_of_experience',
        'certifications',
        'first_name',
        'middle_name',
        'last_name',
        'sex',
        'phone_number',
        'email',
        'prc_license_number',
        'ptr_license_number',
        'affiliated_hospital',
        'training',
        'online_availability_enabled',
    ];

    protected $casts = [
        'id' => 'string',
        'years_of_experience' => 'integer',
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

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function doctorAvailability()
    {
        return $this->hasMany(DoctorAvailability::class, 'doctor_id');
    }

    public function doctorClinics()
    {
        return $this->hasMany(DoctorClinic::class, 'doctor_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'doctor_id');
    }

    public function labTestRequests()
    {
        return $this->hasMany(LabRequest::class, 'doctor_id');
    }

    public function patientNotes()
    {
        return $this->hasMany(PatientNote::class, 'doctor_id');
    }

    public function patientVisits()
    {
        return $this->hasMany(PatientVisit::class, 'doctor_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'doctor_id');
    }

    public function sharedCasesAsSharer()
    {
        return $this->hasMany(SharedCase::class, 'sharing_doctor_id');
    }

    public function sharedCasesAsReceiver()
    {
        return $this->hasMany(SharedCase::class, 'receiving_doctor_id');
    }

    public function soapNotes()
    {
        return $this->hasMany(Consultation::class, 'doctor_id');
    }
}