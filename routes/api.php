<?php

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user',function(Request $request){
    $cond_name = $request->name;
    if ($cond_name !='') {
        $users = User::where('screen_name', 'like', '%'.$cond_name. '%')->take(5)->get();
    } else {
        $users = User::inRandomOrder()->take(5)->get();
    }
    return $users;
});

Route::post('/canvas',function(Request $request){
    $base64_image = $request->canvasInfo;
    @list($type, $file_data) = explode(';', $base64_image);
    @list(, $file_data) = explode(',', $file_data);
    $imageName = str_random(10).'.'.'png';
    Storage::disk('local')->put($imageName, base64_decode($file_data));
    return $request->canvasInfo;
});

Route::post('/logout',function(){
    // dd(request()->all());
	return Auth::logout();
});

Route::post('/delete_post','ProfileController@deletePost');

Route::post('/create', 'HomeController@create')->name('create');
Route::post('/store', 'HomeController@store')->name('store');
Route::post('/upload', 'ProfileController@uploadBg');
