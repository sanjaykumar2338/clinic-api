<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaignstatistics extends Model
{
    use HasFactory;
    protected $table = 'mcl_campaign_statistics';

    public function campaign(){
        return $this->hasOne(Campaign::class, 'id', 'campaign_id');
    }
}
