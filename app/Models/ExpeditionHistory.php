<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpeditionHistory extends Model
{
    protected $table = 'expeditionhistory';
    protected $primaryKey = 'order_no';
    public $timestamps = false;

    public function clients() {

    }
    public function suppliers() {

    }
}
