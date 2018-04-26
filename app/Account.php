<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'Account';

    public $timestamps = false;

    protected $fillable = [
        '_Owner', '_Role', 'Email', 'Fullname', 'Password', 'Phone', 'Status', 'LastLogin', 'Uuid', 'CreatedDt', 'UpdatedDt', 'UpdatedBy', 'isForgot', 'isForgotToken', 'isForgotExpired'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $primaryKey = "Id";

}
