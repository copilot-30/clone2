<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for UUID generation

class LabResult extends Model
{
    protected $table = 'lab_results';

    protected $fillable = [
        'test_request_id',
        'patient_id',
        'result_data',
        'result_file_url',
        'result_date',
        'notes',
    ];

    protected $casts = [
        'id' => 'string',
        'result_data' => 'array', // Cast jsonb to array
        'result_date' => 'date',
    ];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public function testRequest()
    {
        return $this->belongsTo(LabRequest::class, 'test_request_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}