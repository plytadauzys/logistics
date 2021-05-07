<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expedition extends Model
{
    protected $table = 'expeditions';
    protected $primaryKey = 'order_no';
    public $timestamps = false;

    function klientas() {
        return $this->belongsTo('App\Models\Client', 'client');
    }
    function tiekejas() {
        return $this->belongsTo('App\Models\Supplier', 'supplier');
    }
}
