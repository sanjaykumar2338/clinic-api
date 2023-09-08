<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $table = 'v3_doctors';

    public function User(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
