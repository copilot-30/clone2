<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for UUID generation

class FileAttachment extends Model
{
    protected $table = 'file_attachments';

    protected $fillable = [
        'entity_type',
        'entity_id',
        'file_name',
        'file_url',
        'file_size',
        'file_type',
        'uploaded_by_id',
    ];

    protected $casts = [
        'id' => 'string',
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

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }
}