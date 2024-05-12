<?php

namespace App\VenueModule\Models;

use App\UserModule\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'user_id',
        'booked'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
