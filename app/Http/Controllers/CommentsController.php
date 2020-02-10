<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use App\Comment;
use App\City;
use DB;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::all();
        return $comments;
    }

    public function getCommentByCityId($city_id)
    {
        $comments = Comment::where('city_id', $city_id)->orderBy('created_at', 'desc')->get();
        return $comments;
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
            'comment.required' => 'Empty comments are not allowed.'
        ];

        $validData = $request->validate([
            'comment' => 'required',
        ], $messages);

        if ($validData) {
            $comment = new Comment;
            $comment->comment = $request->input('comment');
            $comment->city_id = $city_id;
            $comment->user_id = $user_id;
            $comment->save();

            $city = City::findOrFail($city_id);
            $update = DB::table('cities')->where('id', $city_id)->update(['comment_count' => ($city->comment_count + 1)]);

            return response()->json(['success' => true, 'message' => 'Comment Successful.']);
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
            'comment.required' => 'Empty comments are not allowed.'
        ];

        $validData = $request->validate([
            'comment' => 'required',
        ], $messages);

        $comment = Comment::findOrFail($id);

        if ($validData && $comment->user_id == $user_id) {
            $comment->comment = $request->input('comment');
            $comment->save();

            return response()->json(['success' => true, 'message' => 'Comment Updated Successfully.']);
        } else {
            return response()->json(['success' => false, 'error' => 'Comment update unsuccessful. You are not authorized to edit this comment.']);
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
        $comment = Comment::findOrFail($id);
        if ($comment->user_id == $user_id) {
            $comment->delete();

            return response()->json(['message' => 'Your comment was deleted successfully.']);
        } else {
            return response()->json(['error' => 'You are unauthorized to delete this comment.']);
        }
    }

    public function adminDestroy($city_id)
    {
        $comment = Comment::where('city_id', $city_id);
        $comment->delete();

        return response()->json(['message' => 'Comment with an city id of '.$city_id.' was Deleted Successfully']);
    }
}
