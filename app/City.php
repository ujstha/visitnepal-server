<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function categories() 
    {
        return $this->hasMany('App\Category');
    }

    public function citiesImages() 
    {
        return $this->hasOne('App\CitiesImage');
    }

    public function comments() 
    {
        return $this->hasMany('App\Comment');
    }
}
