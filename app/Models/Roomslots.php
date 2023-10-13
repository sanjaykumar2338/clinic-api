<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roomslots extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'mcl_appointment_available_space';
}
