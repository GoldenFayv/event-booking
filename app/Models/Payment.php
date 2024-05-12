<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference',
        'payee_email'
    ];

    // public function user(){
    //     return $this->belongsTo(User::class);
    // }
    // public function ticket(){
    //     return $this->belongsTo(Ticket::class);
    // }
}
