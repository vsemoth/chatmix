<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Profile;
use App\Avatar;
use Auth;
use Session;
use Image;

class updateController extends Controller
{
    public function update(Request $r)
    {
        $this->validate($r, [
                'location' => 'required',
                'about' => 'required|max:255',
            ]);

        Auth::user()->profile()->update([
                'location' => $r->location,
                'about' => $r->about
            ]);


        Session::flash('success', 'profile updated successfully');

        //redirect with flash data to User's profile
        return redirect()->route('profile', ['slug' => Auth::user()->slug]);
    }

    public function show($slug)
    {
        $user = User::where('slug', $slug)->first();

        return view('profiles.image');
    }

    public function avatar(Request $r, $slug)
    {
        if($r->hasFile('avatar')) {
                    $avatar = $r->file('avatar');
                    $filename = time() . '.' . $avatar->getClientOriginalExtension();
                    Image::make($avatar)->resize(450, 450)->save(public_path('/storage/avatar/' . $filename));

                    $user = Auth::user();
                    Profile::create(['user_id' => $user->id]);
                    $user->avatar = $filename;
                    $path = '/storage/avatar/' . $filename;
                    Avatar::create(['user_id' => $user->id, 'profile_id' => $user->id, 'avatar' => $path]);
                    $user->save();


            Session::flash('success', 'avatar updated successfully');

            //redirect with flash data to User's profile
            return redirect()->back();
        }
    }
}
