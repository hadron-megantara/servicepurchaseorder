<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'Stock';

    public $timestamps = false;

    protected $primaryKey = "Id";
}
