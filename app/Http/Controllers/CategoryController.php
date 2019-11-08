<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use App\Category;
use App\City;
use App\Http\Resources\Category as CategoryResource;
use DB;

class CategoryController extends Controller
{
    public function store(Request $request, $id)
    {
        $category = new Category;
        $category->category_name = $request->input('category_name');
        $category->city_id = $id;
        $category->save();

        return response()->json(['message' => 'Category Inserted Successfully with city_id '.$id.'']);
    }    
}
