<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for UUID generation

class SharedCase extends Model
{
    protected $table = 'shared_cases';

    protected $fillable = [
        'patient_id',
        'sharing_doctor_id',
        'receiving_doctor_id',
        'case_description',
        'shared_data',
        'permissions',
        'status',
        'expires_at',
        'urgency',
    ];

    protected $casts = [
        'id' => 'string',
        'shared_data' => 'array', // Cast jsonb to array
        'permissions' => 'array', // Cast jsonb to array
        'expires_at' => 'datetime',
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

    public function sharingDoctor()
    {
        return $this->belongsTo(Doctor::class, 'sharing_doctor_id');
    }

    public function receivingDoctor()
    {
        return $this->belongsTo(Doctor::class, 'receiving_doctor_id');
    }
}