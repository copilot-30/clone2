<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $table = 'prescriptions';

    protected $fillable = [
        'soap_note_id',
        'patient_id',
        'doctor_id',
        'medication_name',
        'dosage',
        'frequency',
        'duration',
        'quantity',
        'instructions',
        'is_sent_to_patient',
    ];

    protected $casts = [
        'id' => 'string',
        'is_sent_to_patient' => 'boolean',
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
}