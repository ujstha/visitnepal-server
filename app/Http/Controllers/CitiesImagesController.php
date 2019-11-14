<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\CitiesImage;

class CitiesImagesController extends Controller
{
    public function index($city_id)
    {
        $citiesImages = CitiesImage::orderBy('created_at', 'desc')->where('city_id', $city_id)->get();
        return $citiesImages;
    }

    public function store(Request $request, $city_id)
    {
        $validData = $request->validate([
            'cover_image' => 'image|nullable|max:1999999'
        ]);

        // Handle File Upload
        if ($request->hasFile('cover_image')) {
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
            $fileNameToStore = 'cover.jpg';
        }

        if ($validData) {
            $citiesImages = new CitiesImage;
            $citiesImages->cover_image = $fileNameToStore;
            $citiesImages->city_id = $city_id;
            $citiesImages->save();
    
            return response()->json(['message' => "Cover Image Inserted Successfully"]);
        }
    }
}
