<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory;
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
}
