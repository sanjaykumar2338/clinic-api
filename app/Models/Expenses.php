<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    protected $table = 'mcl_expenses';

    public function payment_method(){
        return $this->hasOne(Paymentmethod::class, 'id', 'payment_method');
    }

    public function category(){
        return $this->hasOne(Expensescategory::class, 'id', 'category');
    }

    public function provider(){
        return $this->hasOne(Provider::class, 'id', 'provider');
    }
}
