<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpeditionHistory extends Model
{
    protected $table = 'expeditionhistory';
    protected $primaryKey = 'order_no';
    public $timestamps = false;

    function clients() {
        return $this->belongsTo('App\Models\Client', 'client');
    }
    function suppliers() {
        return $this->belongsTo('App\Models\Supplier', 'supplier');
    }
}
