<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use App\Category;
use App\Http\Resources\Category as CategoryResource;
use Illuminate\Support\Facades\Storage;
use DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return $categories;
    }
    public function getCategoryByCityId($city_id)
    {
        // $cities = DB::select('SELECT * FROM cities');
        $categories = Category::where('city_id', $city_id)->get();
        return $categories;
    }

    public function store(Request $request, $id)
    {
        $validData = $request->validate([
            'category_name' => 'required|string',
            'details' => 'string',
            'category_image' => 'image|nullable|max:1999999'
        ]);

        // Handle File Upload
        if ($request->hasFile('category_image')) {
            // Get filename with the extension
            $filenameWithExt = $request->file('category_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $fileName = str_replace(' ', '_', $filename);
            // Get just ext
            $extension = $request->file('category_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $fileName.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('category_image')->storeAs('public/category_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'category.jpg';
        }

        if ($validData) {
            $category = new Category;
            $category->category_name = $request->input('category_name');
            $category->details = $request->input('details');
            $category->category_image = $fileNameToStore;
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
        $category = Category::findOrFail($id);

        $this->validate($request, [
            'category_name' => 'required|string',
            'details' => 'string',
            'category_image' => 'nullable|max:1999999'
        ]);

        // Handle File Upload
        if ($request->hasFile('category_image')) {
            // Get filename with the extension
            $filenameWithExt = $request->file('category_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $fileName = str_replace(' ', '_', $filename);
            // Get just ext
            $extension = $request->file('category_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $fileName.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('category_image')->storeAs('public/category_images', $fileNameToStore);
            //Delete existing files
            Storage::delete('public/category_images/'.$category->category_image);
        } else {
            $prevImage = $category->category_image;
        }

        $category->category_name = $request->input('category_name');
        if ($request->hasFile('category_image')) {
            $category->category_image = $fileNameToStore;
        } else {
            $category->category_image = $prevImage;
        }
        $category->details = $request->input('details');
        $category->save();

        return response()->json(['message' => 'Category with an ID of '.$id.' was Updated Successfully']);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category with an ID of '.$id.' was Deleted Successfully']);
    }
}
