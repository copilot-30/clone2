<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'admin_activities';

    protected $fillable = [
        'admin_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'details',
        'ip_address',
        'user_agent',
        // 'created_at' will be handled by Laravel if timestamps are enabled, but we manually cast it below.
    ];

    protected $casts = [
        'id' => 'string',
        'details' => 'array',
        'created_at' => 'datetime',
    ];

    public $incrementing = false;
    public $timestamps = false; // Only 'created_at' exists, no 'updated_at'

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}