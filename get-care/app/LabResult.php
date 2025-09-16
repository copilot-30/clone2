<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for UUID generation

class LabResult extends Model
{
    protected $table = 'lab_results';

    protected $fillable = [ 
        'patient_id',
        'result_data',
        'result_file_url',
        'result_date',
        'notes',
    ];

    protected $casts = [
        'id' => 'string',
        'result_data' => 'string', // Cast to string
        'result_date' => 'date',
    ];

    public $incrementing = false;

    public function getResultDataParsedAttribute()
    {
        return json_decode($this->attributes['result_data'], true);
    }

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