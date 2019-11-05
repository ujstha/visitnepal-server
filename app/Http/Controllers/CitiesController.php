<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use App\City;
use App\Http\Resources\City as CityResource;
use DB;

class CitiesController extends Controller
{
    public function index()
    {
        /* to show all at once
        // $cities = DB::select('SELECT * FROM cities');
        // // $cities = City::all();
        // return $cities;
        */
        $cities = City::paginate(5);
        return CityResource::collection($cities);
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'name' => 'required|unique:cities',
            'description' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

         // Handle File Upload
         if($request->hasFile('cover_image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        if ($validData) {
            $city = new City;
            $city->name = $request->input('name');
            $city->description = $request->input('description');
            $city->cover_image = $fileNameToStore;
            $city->save();
    
            return response()->json(['message' => 'Data Inserted Successfully']);
        } 
    }

    public function show($id)
    {
        $city = City::findOrFail($id);
        return $city;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required'
        ]);

        $city = City::findOrFail($id);
        $city->name = $request->input('name');
        $city->description = $request->input('description');
        $city->save();

        return response()->json(['message' => 'Data with an ID of '.$id.' was Updated Successfully']);
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return response()->json(['message' => 'Data with an ID of '.$id.' was Deleted Successfully']);
    }

    /* Working function for getting specific data with id

    // public function showImage($id)
    // {
    //     $cities = DB::select('SELECT * FROM cities WHERE id='.$id.'');
    //     return $cities;
       
    // }

    */

    public function cityCoverImage()
    {
        echo url("/storage/cover_images");
    }
}
