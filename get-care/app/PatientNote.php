<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for UUID generation

class PatientNote extends Model
{
    protected $table = 'patient_notes';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'subject',
        'content',
        'note_type',
        'visibility',
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

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}