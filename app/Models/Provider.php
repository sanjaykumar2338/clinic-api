<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    protected $table = 'mcl_provider';
    protected $fillable = [
        'name',
        'image',
        'is_deleted'
    ];
}
