<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Post;
use App\Category;
use App\Tag;

class BlogController extends Controller
{
	public function getIndex()
	{
		$posts = Post::orderBy('id', 'desc')->paginate(10);

            $categories = DB::table('categories')
            				->crossjoin('posts')
            				->select('name')
            				->whereColumn('category_id', 'posts.id')
            				->get();

            $tags = DB::table('tags')
            				->crossjoin('post_tag')
            				->select('tags.name')
            				->whereColumn('tags.id', 'post_tag.tag_id')
            				->get();           				

		return view('blog.index')->withPosts($posts)->withCategory($categories)->withTag($tags);
	}

	public function getSingle($slug)
	{
		//fetch from DB based on slug
		$post = Post::where('slug', '=', $slug)->first();

		//Return view and pass in post object
		return view('blog.single')->withPost($post);
	}
}
