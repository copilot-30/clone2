<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'string',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'amount' => 'decimal:2'
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
}