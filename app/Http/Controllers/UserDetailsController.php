<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use App\UserDetails;
use App\Http\Resources\UserDetails as UserDetailsResource;
use DB;

class UserDetailsController extends Controller
{
    public function index()
    {
        $details = UserDetails::all();
        return $details;
    }
    public function getDetailsByUserId($user_id)
    {
        $details = UserDetails::where('user_id', $user_id)->get();
        return $details;
    }

    public function store(Request $request, $id)
    {
        $validData = $request->validate([
            'firstname' => 'alpha|nullable',
            'lastname' => 'alpha|nullable',
            'country' => 'alpha|nullable',
            'city' => 'alpha|nullable'
        ]);

        if ($validData) {
            $details = new UserDetails;
            $details->firstname = $request->input('firstname');
            $details->lastname = $request->input('lastname');
            $details->country = $request->input('country');
            $details->city = $request->input('city');
            $details->user_id = $id;
            $details->save();

            return response()->json(['message' => 'Your details were added Successfully.']);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'firstname' => 'alpha|nullable',
            'lastname' => 'alpha|nullable',
            'country' => 'alpha|nullable',
            'city' => 'alpha|nullable'
        ]);
        
        $detail = UserDetails::findOrFail($id);
        $detail->firstname = $request->input('firstname');
        $detail->lastname = $request->input('lastname');
        $detail->country = $request->input('country');
        $detail->city = $request->input('city');
        $detail->save();

        return response()->json(['message' => 'Your details were Updated Successfully.']);
    }
}
