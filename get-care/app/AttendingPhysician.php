<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendingPhysician extends Model
{
    protected $table = 'attending_physicians';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'id' => 'string',
        'start_date' => 'date',
        'end_date' => 'date',
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
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
