<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use App\UserImages;

class UserImagesController extends Controller
{
    public function index()
    {
        $userImages = UserImages::all();
        return $userImages;
    }
    public function getImagesByUserId($user_id)
    {
        $userImages = UserImages::orderBy('created_at', 'asc')->where('user_id', $user_id)->get();
        return $userImages;
    }

    public function store(Request $request, $user_id)
    {
        $validData = $request->validate([
            'profile_image' => 'image|nullable|max:1999999'
        ]);

        // Handle File Upload
        if ($request->hasFile('profile_image')) {
            // Get filename with the extension
            $filenameWithExt = $request->file('profile_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $fileName = str_replace(' ', '_', $filename);
            // Get just ext
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $fileName.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('profile_image')->storeAs('public/profile_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'profile.jpg';
        }

        if ($validData) {
            $userImages = new UserImages;
            $userImages->profile_image = $fileNameToStore;
            $userImages->user_id = $user_id;
            $userImages->save();
    
            return response()->json(['message' => "Profile Image Inserted Successfully"]);
        }
    }

    public function update(Request $request, $id, $user_id)
    {
        $userImages = UserImages::findOrFail($id);

        $validData = $request->validate([
            'profile_image' => 'nullable|max:1999999'
        ]);

        $prevImage = $userImages->profile_image;

        if ($validData && $userImages->user_id == $user_id) {
            if ($request->hasFile('profile_image')) {
                // Get filename with the extension
                $filenameWithExt = $request->file('profile_image')->getClientOriginalName();
                // Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $fileName = str_replace(' ', '_', $filename);
                // Get just ext
                $extension = $request->file('profile_image')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore= $fileName.'_'.time().'.'.$extension;
                // Upload Image
                $path = $request->file('profile_image')->storeAs('public/profile_images', $fileNameToStore);
                //Delete existing files
                Storage::delete('public/profile_images/'.$userImages->profile_image);

                $userImages->profile_image = $fileNameToStore;
            } else {
                $userImages->profile_image = $prevImage;
            }
            $userImages->save();
        
            return response()->json(['message' => "profile Image Updated Successfully"]);
        } else {
            return response()->json(['error' => "Profile Image update unsuccessful.  You are not authorized to edit this image."]);
        }
    }
}
