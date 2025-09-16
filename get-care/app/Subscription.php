<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = ['plan_id', 'status']; // Allowing mass assignment for plan_id and status for convenience in creation

    protected $casts = [
        'id' => 'string',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'plan_id' => 'string',
        'status' => 'string'
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

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}