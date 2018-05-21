<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = '_Category';

    public $timestamps = false;

    protected $fillable = [
        '_Owner', 'Name'
    ];
}
