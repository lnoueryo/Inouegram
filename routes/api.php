<?php

use Illuminate\Http\Request;
use App\User;
use App\Like;
use App\Post;
use App\Follower;
use App\Comment;
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
    $search_like = Like::where('post_id', $request->postId)->where('user_id', $request->userId);

    if($search_like->exists()){
        $like = $search_like->first();
        $like->reaction = $request->reaction;
        $like->save();
    } else {
        $like = new Like;
        $like->user_id = $request->userId;
        $like->post_id = $request->postId;
        $like->reaction = $request->reaction;
        $like->save();
    }
    // $users_id = Like::where('post_id', $request->postId)->get('user_id');
    // $like_users = User::whereIn('id', $users_id)->get();
    $requested_user_posts = Post::where('user_id', $request->requestedUserId)->orderBy('updated_at', 'desc');
    $main_user_likes = Like::where('user_id', $request->userId)->get();
    $requested_user_likes = Like::whereIn('post_id', $requested_user_posts->get('id'))->get();
    $users_id = Like::where('post_id', $request->postId)->get('user_id');
    $like_users = User::whereIn('id', $users_id)->get();
    return $like;
    // return [$main_user_likes,$requested_user_likes,$like_users];
});

Route::post('/delete_like', function(Request $request){
    $like = Like::where('post_id', $request->postId)->where('user_id', $request->userId)->first();
    $like->delete();
    // $requested_user_posts = Post::where('user_id', $request->requestedUserId)->orderBy('updated_at', 'desc');
    // $main_user_likes = Like::where('user_id', $request->userId)->get();
    // $requested_user_likes = Like::whereIn('post_id', $requested_user_posts->get('id'))->get();
    // $users_id = Like::where('post_id', $request->postId)->get('user_id');
    // $like_users = User::whereIn('id', $users_id)->get();
    return $like;
    // return [$main_user_likes,$requested_user_likes,$like_users];
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
    if($request->isFollowed == 'true') {
        $follower = Follower::where('following_id', $request->myId)->where('followed_id', $request->id);
        $follower->delete();
        $requested_user_followed = Follower::where('followed_id', $request->id)->get();
    } else {
        $follower = new Follower;
        $follower->following_id = $request->myId;
        $follower->followed_id = $request->id;
        $follower->save();
        $requested_user_followed = Follower::where('followed_id', $request->id)->get();
    }
    return  $requested_user_followed;
});
Route::post('/likeUsers', function(Request $request){
    $users_id = Like::where('post_id', $request->postId)->get('user_id');
    $like_users = User::whereIn('id', $users_id)->get();
    return $like_users;
});
Route::post('/commentUsers', function(Request $request){
    $users_id = Comment::where('post_id', $request->postId)->get('user_id');
    $comment_users = User::whereIn('id', $users_id)->get();
    return $comment_users;
});

Route::get('/users', 'HomeController@search');

Route::post('/comment', function(Request $request){
    $comment = new Comment;
    $comment->user_id = $request->userId;
    $comment->post_id = $request->postId;
    $comment->text = $request->text;
    $comment->save();
    return $comment;
});

Route::post('/delete_comment', function(Request $request){
    $comment = Comment::find($request->id);
    $comment->delete();
    return $comment;
});