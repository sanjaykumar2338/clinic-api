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

    public function paymentpurpose(){
        return $this->hasMany(Paymentpurpose::class, 'id', 'payment_purpose');
    }

    public function paymentmethod(){
        return $this->hasMany(Paymentmethod::class, 'id', 'payment_method');
    }

    public function inventory(){
        return $this->hasMany(InventoryItem::class, 'id', 'inventory');
    }

    public function patients(){
        return $this->hasMany(RevenuePatient::class, 'revenue', 'id');
    }

    public function doctor(){
        return $this->hasOne(Doctor::class, 'id', 'doctor');
    }
}
