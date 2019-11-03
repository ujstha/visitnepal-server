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
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required'
        ]);

        try {
            $city = new City;
            $city->name = $request->input('name');
            $city->description = $request->input('description');
            $city->save();

            return response()->json(['message' => 'Data Inserted Successfully']);
        } catch (QueryException $e) { // It's actually a QueryException but this works too
            abort(500, 'Unsuccessful to Insert the Data.');
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

       return response()->json('Data with an ID of '.$id.' Updated Successfully');
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return response()->json('Data with an ID of '.$id.' Deleted Successfully');
    }
}
