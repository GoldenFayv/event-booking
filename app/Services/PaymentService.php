<?php

namespace App\Services;

use App\Models\Payment;

class PaymentService
{
    public function makePayment($ticketId, $amount, $paymentDate, $paymentMethod, $payeeEmail=null, $userId=null)
    {
        Payment::create([
            'user_id' => $userId,
            'ticket_id' => $ticketId,
            'amount' => $amount,
            'payment_date' => $paymentDate,
            'payment_method' => $paymentMethod,
            'reference' => generateReference(),
            'payee_email' => $payeeEmail,
        ]);
    }
}

