<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Consultation;

class PatientTestRequest extends Model
{
    protected $table = 'patient_test_requests';

    protected $fillable = [
        'soap_note_id',
        'soap_note_id',
        'patient_id',
        'doctor_id',
        'content',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
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