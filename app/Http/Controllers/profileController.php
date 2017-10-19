<?php

namespace App\Http\Controllers;

use App\User;
use App\Profile;
use App\Avatar;
use Auth;
use Session;
use Illuminate\Http\Request;

class profileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        //Grab slug for authenticated user
        $user = User::where('slug', $slug)->first();

        // Grab all avatars
        $avatars = Avatar::all();
        // Return avatar for link
        foreach ($avatars as $avatar) {
            $avatar;
        }

        return view('profiles.profile')
        ->withUser($user)
        ->withAvatar($avatars);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                'location' => 'required',
                'about' => 'required|max:255'
            ));

        //store in the database
        $p = new Profile;

        $p->title = $request->title;
        $p->body = $request->body;

        //add avatar

        //save post
        
        $p->save();

        Session::flash('success', 'The profile was successfully saved!');

        //redirect to another page
        return redirect()->route('profile', ['slug' => Auth::user()->slug]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('profiles.edit')->with('info', Auth::user()->profile);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($r->hasFile('avatar')) {
            Auth::user()->update([
                    'avatar' =>$r->avatar->store('public/avatars')
                ]);
        }


        Session::flash('success', 'avatar updated successfully');

        //redirect with flash data to User's profile
        return redirect()->route('profile', ['slug' => Auth::user()->slug]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
