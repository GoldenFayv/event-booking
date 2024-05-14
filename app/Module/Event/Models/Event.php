<?php

namespace App\Module\Event\Models;

use App\Module\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_name',
        'description',
        'date',
        'time',
        'venue_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    // public function venue(){
    //     return $this->belongsTo(Venue::class);
    // }
}
