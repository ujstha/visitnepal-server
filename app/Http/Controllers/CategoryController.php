<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use App\Category;
use App\Http\Resources\Category as CategoryResource;
use DB;

class CategoryController extends Controller
{
    public function index($city_id)
    {
        // $cities = DB::select('SELECT * FROM cities');
        $categories = Category::where('city_id', $city_id)->get();
        return $categories;
    }

    public function store(Request $request, $id)
    {
        $validData = $request->validate([
            'category_name' => 'required|alpha',
        ]);

        if ($validData) {
            $category = new Category;
            $category->category_name = $request->input('category_name');
            $category->city_id = $id;
            $category->save();

            return response()->json(['message' => 'Category Inserted Successfully with city_id '.$id.'']);
        }
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return $category;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category_name' => 'required|alpha',
        ]);

        $category = Category::findOrFail($id);
        $category->category_name = $request->input('category_name');
        $category->save();

        return response()->json(['message' => 'Data with an ID of '.$id.' was Updated Successfully']);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Data with an ID of '.$id.' was Deleted Successfully']);
    }
}