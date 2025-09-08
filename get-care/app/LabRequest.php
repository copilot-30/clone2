<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabRequest extends Model
{
    protected $table = 'lab_test_requests';

    protected $fillable = [
        'soap_note_id',
        'patient_id',
        'doctor_id',
        'test_name',
        'test_type',
        'instructions',
        'urgency',
        'status',
        'is_sent_to_patient',
        'requested_date',
    ];

    protected $casts = [
        'id' => 'string',
        'is_sent_to_patient' => 'boolean',
        'requested_date' => 'date',
    ];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function soapNote()
    {
        return $this->belongsTo(Consultation::class, 'soap_note_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function labResults()
    {
        return $this->hasMany(LabResult::class, 'test_request_id');
    }
}