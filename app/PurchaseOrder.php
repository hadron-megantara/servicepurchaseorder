<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table = 'PurchaseOrder';

    public $timestamps = false;

    protected $primaryKey = "OrderCode";
}
