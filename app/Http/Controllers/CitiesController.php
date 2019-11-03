<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use App\City;
use DB;

class CitiesController extends Controller
{
    public function index()
    {
        $cities = DB::select('SELECT * FROM cities');
        // $cities = City::all();
        return $cities;
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'name' => 'required|unique:cities',
            'description' => 'required'
        ]);

        if ($validData) {
            $city = new City;
            $city->name = $request->input('name');
            $city->description = $request->input('description');
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
}
