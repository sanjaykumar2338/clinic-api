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

    public function paymentpurpose(){
        return $this->hasMany(Paymentpurpose::class, 'id', 'payment_purpose');
    }

    public function paymentmethod(){
        return $this->hasMany(Paymentmethod::class, 'id', 'payment_method');
    }

    public function category(){
        return $this->hasOne(Expensescategory::class, 'id', 'category');
    }
}
