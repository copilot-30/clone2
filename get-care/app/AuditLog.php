<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'url',
        'action',
        'data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'id' => 'string',
        'data' => 'array',
        'created_at' => 'datetime',
    ];

    public $incrementing = false;
    public $timestamps = true; // Only 'created_at' exists, no 'updated_at'

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}