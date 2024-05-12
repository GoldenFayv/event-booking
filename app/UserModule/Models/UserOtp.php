<?php

namespace App\UserModule\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'otp', 'type', 'expires_at'];
    protected $hidden = ['user_id', 'otp'];
    protected $casts = ['expires_at' => 'datetime'];

}
