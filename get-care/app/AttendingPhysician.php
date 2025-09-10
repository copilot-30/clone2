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

    public $incrementing = false; // Primary key is not auto-incrementing

    protected $casts = [
        'id' => 'string', // Cast id to string
        'start_date' => 'date',
        'end_date' => 'date',
    ];

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
