<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use App\Page;
use App\Http\Resources\Page as PageResource;
use DB;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::all();
        return $pages;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validData = $request->validate([
            'title' => 'alpha',
            'body' => 'string'
        ]);

        if ($validData) {
            $page = new Page;
            $page->title = $request->input('title');
            $page->body = $request->input('body');
            $page->save();

            return response()->json(['message' => 'Page Inserted Successfully.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);
        return $page;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'alpha',
            'body' => 'string'
        ]);

        $page = Page::findOrFail($id);
        $page->title = $request->input('title');
        $page->body = $request->input('body');
        $page->save();

        return response()->json(['message' => 'Page with an ID of '.$id.' was Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return response()->json(['message' => 'Page with an ID of '.$id.' was Deleted Successfully']);
    }
}
