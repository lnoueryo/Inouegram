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

Route::get('/user','ApiController@search');

Route::post('/logout', 'ProfileController@logout');

Route::post('/delete_post','ProfileController@deletePost');

Route::post('/create', 'PostController@create')->name('create');

Route::post('/like', 'ApiController@like');

Route::post('/delete_like', 'ApiController@deleteLike');
Route::post('/upload', 'ProfileController@uploadBg');
Route::post('/upload2', 'ProfileController@uploadAvatar');

Route::get('/follow', 'ApiController@follow');
Route::post('/likeUsers', 'ApiController@likeUsers');
Route::post('/commentUsers', 'ApiController@commentUsers');
Route::post('/followingUsers', 'ApiController@followingUsers');

// Route::get('/users', 'HomeController@search');

Route::post('/comment', 'ApiController@comment');

Route::post('/delete_comment', 'ApiController@deleteComment');
Route::get('/likedPostUsers', 'ApiController@likedPostUsers');
Route::post('/jsonjson', function(Request $request){

    for ($i=0; $i < count($request->json); $i++) {
        $post = Post::find($request->id[$i]);
        $post->image = $request->json[$i];
        $post->save();
    }
    // for($i=0; $i<count($request->image); $i++){
    //     $photo = new Photo;
    //     $photo->post_id = $post->id;
    //     $photo->src = $request->image[$i];
    //     $files = $request->file('image');
    //     $path = $files[$i]->store('public/image');
    //     $photo->src = basename($path);
    //     $photo->save();
    // }
    return 'hello';
});

// Route::post('/auth/google', function(){
//     return Socialite::driver('google')->redirect();
// });