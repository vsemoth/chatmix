<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Category;
use Session;

class BlogCategoriesController extends Controller
{

    public function __construct() {
        $this->middleware('auth', ['except' => ['show', 'getIndex']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $categories = Category::all();
        return view('blog.categories.index')->withCategories($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array('name' => 'required|max:255'));
        $tag = new Tag;
        $tag->name = $request->name;
        $tag->save();

        Session::flash('success', 'New Tag was successfully created!');

        return redirect()->route('blog.tags.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categories = Category::find($id);
        return view('blog.categories.show')->withCategory($categories);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Category::find($id);
        return view('blog.tags.edit')->withCategory($tag);
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
        $tag = Tag::find($id);

        $this->validate($request, ['name' => 'required|max:255']);

        $tag->name = $request->name;
        $tag->save();

        Session::flash('success', 'Successfully saved your new tag!');

        return redirect()->route('blog.tags.show', $tag->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        $tag->posts()->detach();

        $tag->delete();

        Session::flash('success', 'Tag was deleted successfully');

        return redirect()->route('blog.tags.index');
    }
}
