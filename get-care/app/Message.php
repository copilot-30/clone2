<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for UUID generation

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message_type',
        'content',
        'file_url',
        'file_name',
        'file_size',
        'is_read',
    ];

    protected $casts = [
        'id' => 'string',
        'is_read' => 'boolean',
        'file_size' => 'integer',
    ];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}