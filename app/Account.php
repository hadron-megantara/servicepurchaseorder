<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'Account';

    public $timestamps = false;

    protected $fillable = [
        '_Owner', '_Role', 'Email', 'Fullname', 'Password', 'Phone', 'Status', 'LastLogin', 'Uuid', 'CreatedDt', 'UpdatedDt', 'UpdatedBy', 'isForgot', 'isForgotToken', 'isForgotExpired'
    ];
}
