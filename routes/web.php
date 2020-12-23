<?php
use App\Post;
use App\Comments;
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
use App\User;
Route::get('/public', function () {
    $my_info = User::find(1);
    $my_posts = Post::where('user_id', $my_info->id)->get();
    return view('index', ['my_info' => $my_info, 'my_posts' => $my_posts]);
})->middleware('auth');

Auth::routes();

Route::get('/', 'HomeController@index')->middleware('auth');
Route::get('/home', 'HomeController@index')->middleware('auth');
// Route::get('/', 'HomeController@index')->name('home');
Route::get('/post', 'PostController@index')->middleware('auth');
Route::get('/profile', 'ProfileController@index')->middleware('auth');
Route::get('/user', function(){
    if(Auth::id()==1){
        $users = User::withCount('posts')->get();
        // $users = User::with(['comments', 'posts' => function($query){
        //     $query->with(['likes', 'comments']);
        // }])->get();
        return view('dashboard');
    } else {
        return redirect('/');
    }
});

Route::get('auth/google', 'Auth\LoginController@redirectToGoogle');/*このアカウントはありますかとgoogleに問い合わせ*/
Route::get('auth/google/callback', 'Auth\LoginController@handleGoogleCallback');/*その問い合わせに対する応答とその応答に対する処理*/
Route::get('auth/github', 'Auth\LoginController@redirectToGithub');/*このアカウントはありますかとgoogleに問い合わせ*/
Route::get('auth/github/callback', 'Auth\LoginController@handleGithubCallback');/*その問い合わせに対する応答とその応答に対する処理*/
