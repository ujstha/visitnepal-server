<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use App\City;
use App\User;

class CountController extends Controller
{
    public function count()
    {
        $users = User::where('isAdmin', 0)->count();
        $cities = City::all()->count();
        $cityViews = City::all()->sum('visit_count');
        $commentCounts = City::all()->sum('comment_count');
        $count = array('user_count' => $users, 'city_count' => $cities, 'comment_count' => $commentCounts, 'view_count' => $cityViews);
        return $count;
    }
}
