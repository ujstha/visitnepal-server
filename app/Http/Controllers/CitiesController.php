<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use App\City;
use App\CitiesImage;
// use App\Category;
use App\Rating;
use App\Comment;
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
        $cities = City::all();
        $getCities = CityResource::collection($cities);
        $getCityImage = CitiesImage::all();
        // $getCityCategory = Category::all();

        // return array('city' => $getCities, 'city_image' => $getCityImage, 'category' => $getCityCategory );
        return array('city' => $getCities, 'city_image' => $getCityImage);
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'place' => 'required|string',
            'category' => 'nullable',
            'city_name' => 'required|string',
            'country' => 'nullable|string',
            'description' => 'required'
        ]);

        if ($validData) {
            $city = new City;
            $city->place = $request->input('place');
            $city->category = implode(", ", $request->input('category'));
            $city->city_name = $request->input('city_name');
            $city->country = $request->input('country');
            $city->description = $request->input('description');
            $city->save();
    
            return response()->json(['message' => "City's Data Inserted Successfully", 'city_id' => $city->id]);
        }
    }

    public function show($id)
    {
        $city = City::findOrFail($id);
        $update = DB::table('cities')->where('id', $id)->update(['visit_count' => ($city->visit_count + 1)]);
        $cityImage = CitiesImage::where('city_id', $id)->get();
        $cityAvgRating = Rating::where('city_id', $id)->avg('rating');
        $cityRatingList = Rating::where('city_id', $id);
        $cityRatingCount = Rating::where('city_id', $id)->count();
        // $categoryByCityId = Category::where('city_id', $id)->get();
        $commentByCityId = Comment::where('city_id', $id)->orderBy('created_at', 'desc')->get();
        
        return array('cityById' => $city,
        'cityImageByCityId' => $cityImage,
        'commentByCityId' => $commentByCityId,
        'rating_count' => $cityRatingCount,
        'avg_rating' => $cityAvgRating,
        'rating_list' => $cityRatingList );
    }

    public function uidShow($id, $uid)
    {
        $city = City::findOrFail($id);
        $cityRatingByUID = Rating::where('city_id', $id)->where('user_id', $uid)->get();
       
        return array('rate_uid'  => $cityRatingByUID);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'place' => 'required|string',
            'category' => 'nullable',
            'city_name' => 'required|string',
            'country' => 'nullable|string',
            'description' => 'required'
        ]);
    
        $city = City::findOrFail($id);
        $city->place = $request->input('place');
        $city->category = implode(", ", $request->input('category'));
        $city->city_name = $request->input('city_name');
        $city->country = $request->input('country');
        $city->description = $request->input('description');
        
        $city->save();

        return response()->json(['message' => 'City with an ID of '.$id.' was Updated Successfully']);
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return response()->json(['message' => 'City and Image with an ID of '.$id.' was Deleted Successfully']);
    }
}
