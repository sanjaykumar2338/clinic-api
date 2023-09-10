<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenuePatient extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'mcl_revenue_patient';
}
