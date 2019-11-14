<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use App\UserImages;

class UserImagesController extends Controller
{
    public function index($user_id)
    {
        $userImages = UserImages::orderBy('created_at', 'desc')->where('user_id', $user_id)->get();
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
            // Get just ext
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
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
}
