<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymentpurpose extends Model
{
    use HasFactory;
    protected $table = 'mcl_payment_purpose';
    protected $fillable = [
        'name',
        'is_deleted'
    ];
}
