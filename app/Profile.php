<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //make fillable

    protected $fillable = ['location', 'about','user_id'];

    //user - profile relationship
    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    //Avatar - profile relationship
    public function avatar()
    {
    	return $this->hasMany('App\Avatar');
    }
}
