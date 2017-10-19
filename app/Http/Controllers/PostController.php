<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Post;
use App\Category;
use App\Tag;
use Image;
use Session;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->paginate(10);
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('posts.index')->withPosts($posts)->withCategories($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.create')->withCategories($categories)->withTags($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate the data
        $this->validate($request, array(
                'title' => 'required|max:255',
                'slug'   => 'required|alpha_dash|min:5|max:255|unique:posts,slug',
                /*'category_id' => 'required|integer',*/
                'body' => 'required'
            ));

        //store in the database
        $post = new Post;

        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->category_id = $request->category_id;
        $post->body = $request->body;

        //find image

        if ($request->hasFile('featured_img')) {
          $image = $request->file('featured_img');
          $filename = time() . '.' . $image->getClientOriginalExtension();
          $location = public_path('images/' . $filename);
          Image::make($image)->resize(800, 400)->save($location);

          $post->image = $filename;
        }

        //save post
        
        $post->save();

        $post->tags()->sync($request->tags, false);

        Session::flash('success', 'The blog post was successfully saved!');

        //redirect to another page
        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->withPost($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //find post in database and return as view
            $post = Post::find($id);

            $categories = Category::all();
            $cats = array();
            foreach ($categories as $category) {
                $cats[$category->id] = $category->name;
            }

            $tags = Tag::all();
            $tags2 = array();
            foreach ($tags as $tag) {
                $tags2[$tag->id] = $tag->name;
            }

        //return view and pass in the var created
            return view('posts.edit')->withPost($post)->withCategories($cats)->withTags($tags2);
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
        //Validate the data
        $post = Post::find($id);

        if ($request->input('slug') == $post->slug) {
            $this->validate($request, array(
                'title' => 'required|max:255',
                'body' => 'required'
            ));
        } else {
                $this->validate($request, array(
                    'title' => 'required|max:255',
                    //'category_id' => 'required|integer',
                    'slug'  => 'required|alpha_dash|min:4|max:255|unique:posts,slug',
                    'body' => 'required'
                ));
        }

        //Save data to database
        $post = Post::find($id);

        $post->title = $request->input('title');
        //$post->category_id = $request->input('category_id');
        $post->slug = $request->input('slug');
        $post->body = $request->input('body');

        $post->save();

        $post->tags()->sync($request->tags);

        //set flash data with success message
        Session::flash('success', 'This post was successfully saved.');

        //redirect with flash data to posts.show
        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Find post to be deleted
        $post = Post::find($id);
        //delete post
        $post->delete();
        //confirm result with flash success message
        Session::flash('success', 'deleted successfully!');
        //redirect with flash data to posts.show
        return redirect()->route('posts.index');
    }
}
