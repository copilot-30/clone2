<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';
    protected $primaryKey = 'email'; // Set 'email' as primary key
    public $incrementing = false; // Disable auto-incrementing for primary key
    protected $fillable = ['email', 'token', 'created_at'];
    public $timestamps = false;
    
}
