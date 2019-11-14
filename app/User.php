<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    public function userDetails() 
    {
        return $this->hasOne('App\UserDetails');
    }

    public function userImages() 
    {
        return $this->hasMany('App\UserImages');
    }

    protected $fillable = [
        'username', 'email', 'password', 'password_confirmation',
    ];

    /*
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password', 'password_confirmation',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
