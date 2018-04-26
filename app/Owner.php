<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $table = 'Owner';

    public $timestamps = false;

    protected $primaryKey = "Id";
}
