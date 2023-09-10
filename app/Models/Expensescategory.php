<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expensescategory extends Model
{
    use HasFactory;
    protected $table = 'mcl_expense_category';
    protected $fillable = [
        'name',
        'is_deleted'
    ];
}
