<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $table = 'soap_notes';

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'date',
        'subjective',
        'chief_complaint',
        'history_of_illness',
        'objective',
        'vital_signs',
        'assessment',
        'diagnosis',
        'plan',
        'prescription',
        'test_request',
        'remarks',
        'vital_remarks',
        'file_remarks', 
    ];

    protected $casts = [
        'id' => 'string',
        'date' => 'date',
        'vital_signs' => 'array',
        'follow_up_date' => 'date',
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
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
 
    

    public function patientTestRequests()
    {
        return $this->hasMany(PatientTestRequest::class, 'soap_note_id');
    }
    
    public function labResults()
    {
        return $this->hasMany(LabResult::class, 'soap_note_id');
    }

    
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}