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
            'city_name' => 'required|string|unique:cities',
            'country' => 'nullable|string',
            'description' => 'required'
        ]);

        if ($validData) {
            $city = new City;
            $city->city_name = $request->input('city_name');
            $city->country = $request->input('country');
            $city->description = $request->input('description');
            $city->save();
    
            return response()->json(['message' => "City's Data Inserted Successfully"]);
        }
    }

    public function show($id)
    {
        $city = City::findOrFail($id);
        $update = DB::table('cities')->where('id', $id)->update(['visit_count' => ($city->visit_count + 1)]);
        return $city;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'city_name' => 'required|string',
            'country' => 'nullable|string',
            'description' => 'required'
        ]);
    
        $city = City::findOrFail($id);
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

        return response()->json(['message' => 'City with an ID of '.$id.' was Deleted Successfully']);
    }

    /* Working function for getting specific data with id

    // public function showImage($id)
    // {
    //     $cities = DB::select('SELECT * FROM cities WHERE id='.$id.'');
    //     return $cities;

    // }

    */
}
