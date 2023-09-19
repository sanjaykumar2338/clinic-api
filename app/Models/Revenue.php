<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    protected $table = 'mcl_revenue';

    public function payment_purpose(){
        return $this->hasOne(Paymentpurpose::class, 'id', 'payment_purpose');
    }

    public function payment_method(){
        return $this->hasOne(Paymentmethod::class, 'id', 'payment_method');
    }

    public function inventory(){
        return $this->hasOne(InventoryItem::class, 'id', 'inventory');
    }

    public function patientsingle(){
        return $this->hasOne(Patient::class, 'id', 'patient');
    }

    public function doctor(){
        return $this->hasOne(Doctor::class, 'id', 'doctor');
    }

    public function doctorsingle(){
        return $this->hasOne(Doctor::class, 'id', 'doctor');
    }
}
