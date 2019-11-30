<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use App\Rating;
use App\City;
use DB;

class RatingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ratings = Rating::all();
        return $ratings;
    }

    public function getRatingByCityId($city_id)
    {
        $ratings = Rating::where('city_id', $city_id)->orderBy('created_at', 'desc')->get();
        return $ratings;
    }

    public function getAvgRatingByCityId($city_id)
    {
        $avgRating = Rating::where('city_id', $city_id)->avg('rating');
        return $avgRating;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $city_id, $user_id)
    {
        $messages = [
            'rating.required' => 'Empty Ratings are not allowed.'
        ];

        $validData = $request->validate([
            'rating' => 'required',
        ], $messages);

        if ($validData) {
            $rating = new Rating;
            $rating->rating = $request->input('rating');
            $rating->city_id = $city_id;
            $rating->user_id = $user_id;
            $rating->save();

            $city = City::findOrFail($city_id);
            $update = DB::table('cities')->where('id', $city_id)->update(['rating_count' => ($city->rating_count + 1)]);

            return response()->json(['success' => true, 'message' => 'Rating Successful.']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $user_id)
    {
        $messages = [
            'rating.required' => 'Empty Ratings are not allowed.'
        ];

        $validData = $request->validate([
            'rating' => 'required',
        ], $messages);

        $rating = Rating::findOrFail($id);

        if ($validData && $Rating->user_id == $user_id) {
            $rating->rating = $request->input('rating');
            $rating->save();

            return response()->json(['success' => true, 'message' => 'Rating Updated Successfully.']);
        } else {
            return response()->json(['success' => false, 'error' => 'Rating update unsuccessful. You are not authorized to edit this Rating.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $user_id)
    {
        $rating = Rating::findOrFail($id);
        if ($rating->user_id == $user_id) {
            $rating->delete();

            return response()->json(['message' => 'Your Rating was deleted successfully.']);
        } else {
            return response()->json(['error' => 'You are unauthorized to delete this Rating.']);
        }
    }
}
