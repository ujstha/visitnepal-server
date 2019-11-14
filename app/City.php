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
        return $this->hasMany('App\CitiesImage');
    }
}
