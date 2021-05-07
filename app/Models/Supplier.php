<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    public $timestamps = false;

    function ekspedicija() {
        return $this->hasMany('App\Models\Expedition','supplier');
    }
}
