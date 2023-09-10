<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;
    protected $table = 'mcl_clinic';

    public function administrators(){
        return $this->hasMany(Clinicadministrator::class, 'clinic_id', 'id');
    }

    public function doctors(){
        return $this->hasOne(Clinicdoctor::class, 'clinic_id', 'id');
    }
}
