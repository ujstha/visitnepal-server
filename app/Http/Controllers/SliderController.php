<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use App\Slider;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('status', 'active')->get();
        return $sliders;
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'caption' => 'string',
            'link' => 'string',
            'slides' => 'image|nullable|max:1999999',
            'status' => 'string'
        ]);

        // Handle File Upload
        if ($request->hasFile('slides')) {
            // Get filename with the extension
            $filenameWithExt = $request->file('slides')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('slides')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('slides')->storeAs('public/slider_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'slides.jpg';
        }

        if ($validData) {
            $sliders = new Slider;
            $sliders->caption = $request->input('caption');
            $sliders->link = $request->input('link');
            $sliders->slides = $fileNameToStore;
            $sliders->status = $request->input('status');
            $sliders->save();
    
            return response()->json(['message' => "Slider's Data inserted successfully.", 'sliders' => $sliders]);
        }
    }

    public function update(Request $request, $id)
    {
        $sliders = Slider::findOrFail($id);
        
        $request->validate([
            'caption' => 'string',
            'link' => 'string',
            'slides' => 'image|nullable|max:1999999',
            'status' => 'string'
        ]);

        // Handle File Upload
        if ($request->hasFile('slides')) {
            // Get filename with the extension
            $filenameWithExt = $request->file('slides')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('slides')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('slides')->storeAs('public/slider_images', $fileNameToStore);
            //Delete existing files
            Storage::delete('public/slider_images/'.$sliders->slides);
        }

        $sliders->caption = $request->input('caption');
        $sliders->link = $request->input('link');
        if ($request->hasFile('slides')) {
            $sliders->slides = $fileNameToStore;
        }
        $sliders->status = $request->input('status');
        $sliders->save();

        return response()->json(['message' => 'Slider with an ID of '.$id.' was Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->delete();

        return response()->json(['message' => 'Slide with an ID of '.$id.' was Deleted Successfully']);
    }
}
