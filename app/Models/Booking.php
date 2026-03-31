<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        // BASIC INFO
        'full_name',
        'contact_number',
        'email',
        'event_name',
        'event_date',
        'start_time',
        'end_time',
        'venue',
        'notes',

        // STATUS
        'status',

        // PAYMENT INFO 
        'payment_method',      // Cash / GCash
        'event_price',
        'downpayment_amount',
        'gcash_name',
        'gcash_number',
        'gcash_receipt',       // screenshot / resibo
    ];
}
