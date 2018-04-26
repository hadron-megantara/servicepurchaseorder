<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'Address';

    public $timestamps = false;

    protected $primaryKey = "Id";
}
