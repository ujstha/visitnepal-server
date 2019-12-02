<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\CitiesImage;

class CitiesImagesController extends Controller
{
    public function index()
    {
        $citiesImages = CitiesImage::all();
        return $citiesImages;
    }
    public function getImagesByCityId($city_id)
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
            $fileName = str_replace(' ', '_', $filename);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $fileName.'_'.time().'.'.$extension;
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

    public function update(Request $request, $id, $city_id)
    {
        $citiesImages = CitiesImage::findOrFail($id);

        $validData = $request->validate([
            'cover_image' => 'nullable|max:1999999'
        ]);

        $prevImage = $citiesImages->cover_image;

        if ($validData && $citiesImages->city_id == $city_id) {
            if ($request->hasFile('cover_image')) {
                // Get filename with the extension
                $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
                // Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $fileName = str_replace(' ', '_', $filename);
                // Get just ext
                $extension = $request->file('cover_image')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore= $fileName.'_'.time().'.'.$extension;
                // Upload Image
                $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
                //Delete existing files
                Storage::delete('public/cover_images/'.$citiesImages->cover_image);
                
                $citiesImages->cover_image = $fileNameToStore;
            } else {
                $citiesImages->cover_image = $prevImage;
            }
            $citiesImages->save();
        
            return response()->json(['message' => "Cover Image Updated Successfully"]);
        } else {
            return response()->json(['error' => "Cover Image update unsuccessful. Wrong city_id was provided."]);
        }
    }
}
