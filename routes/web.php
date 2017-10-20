<?php
use App\Events\MessagePosted;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('logout', function () {
	return view('logout');
});

Route::get('/chat', function () {
    return view('chat');
})->middleware('auth');

Route::get('/messages', function () {
	return App\Message::with('user')->get();
})->middleware('auth');;

Route::post('/messages', function () {
	//store the new message
	$user = Auth::user();

	$user->messages()->create([
		'message' => request()->get('message')
	]);

	//A new message has been posted
	event(new MessagePosted($message, $user));

	return ['status' => 'OK!'];
})->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index');




Route::group(['middleware' => 'auth'], function()
{
	Route::get('/profile/{slug}', [
			'uses' => 'ProfileController@index',
			'as' => 'profile'
		]);

	Route::get('/profile/{slug}/update', [
			'uses' => 'updateController@update',
			'as' => 'profile.update'
		]);

	Route::get('/profile/{slug}/avatar', [
			'uses' => 'updateController@show',
			'as' => 'profile.avatar'
		]);

	Route::post('upload', 'UploadController@upload');

	Route::resource('profile', 'profileController');
});


Route::get('/{vue?}', function () { return view('welcome'); })->where('vue', '[\/\w\.-]*');

Route::get('/check_relationship_status/{id}', function ($id) {
    return \App\User::find($id);
});

Auth::routes();

Route::get('/home', 'HomeController@index');
