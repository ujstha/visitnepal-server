<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    public function user() {
        return $this->belongsTo('App\User');
    }
}
