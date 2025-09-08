<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for UUID generation

class Conversation extends Model
{
    protected $table = 'conversations';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'id' => 'string',
        'last_message_at' => 'datetime',
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

    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }
}