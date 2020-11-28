<?php

use Illuminate\Http\Request;
use App\User;
use App\Like;
use App\Follower;
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

Route::post('/like', function(Request $request){

    $search_like = Like::where('post_id', $request->postId)->where('user_id', $request->postingUserId);
    if($search_like->exists()){
        $like = $search_like->first();
        $like->reaction = $request->reaction;
        $like->save();
    } else {
        $like = new Like;
        $like->user_id = $request->postingUserId;
        $like->post_id = $request->postId;
        $like->reaction = $request->reaction;
        $like->save();
    }
    $user_posts = json_decode($request->userPosts);
    for($i=0; $i<count($user_posts); $i++){
        $array[] = $user_posts[$i]->id;
    }
    $this_user_likes = Like::whereIn('post_id', $array)->get();
    return $this_user_likes;
});

Route::post('/delete_like', function(Request $request){
    $like = Like::where('post_id', $request->postId)->where('user_id', $request->postingUserId)->first();
    $like->delete();
    $user_posts = json_decode($request->userPosts);
    for($i=0; $i<count($user_posts); $i++){
        $array[] = $user_posts[$i]->id;
    }
    $this_user_likes = Like::whereIn('post_id', $array)->get();
    return $this_user_likes;
});
Route::post('/upload', 'ProfileController@uploadBg');
Route::post('/upload2', 'ProfileController@uploadAvatar');
Route::get('/user', function(Request $request){
    $cond_name = $request->name;
    if ($cond_name !='') {
        $users = User::where('screen_name', 'like', '%'.$cond_name. '%')->take(5)->get();
    } else {
        $users = User::inRandomOrder()->take(5)->get();
    }
    return $users;
});

Route::get('/follow', function(Request $request){
    if($request->followed == 0) {
        $follower = new Follower;
        $follower->following_id = $request->myId;
        $follower->followed_id = $request->id;
        $follower->save();
        return  1;
    } else {
        $follower = Follower::where('following_id', $request->myId)->where('followed_id', $request->id);
        $follower->delete();
        return  0;
    }
});
Route::get('/likeUsers', function(Request $request){
    $users_id = Like::where('post_id', $request->postId)->get('user_id');
    $like_users = User::whereIn('id', $users_id)->get();
    return $like_users;
});

Route::get('/users', 'HomeController@search');