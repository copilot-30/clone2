<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Payment extends Model
{
    protected $guarded = [];
    protected $keyType = 'string';
    protected $casts = [
        'id' => 'string',
        'payment_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        // Define polymorphic map for 'payable' relationships
        Relation::morphMap([
            'MEMBERSHIP' => 'App\Plan', // Map 'MEMBERSHIP' string to App\Plan model
            // Add other polymorphic mappings if needed, e.g., 'APPOINTMENT' => 'App\Appointment'
        ]);

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payable()
    {
        return $this->morphTo();
    }

 
}