<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expedition extends Model
{
    protected $table = 'expeditions';
    protected $primaryKey = 'order_no';
    public $timestamps = false;
}
