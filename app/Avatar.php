<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    //Make fields fillable

    protected $fillable = ['avatar', 'user_id', 'profile_id'];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function profile()
    {
    	return $this->belongsTo('App\Profile');
    }
}
