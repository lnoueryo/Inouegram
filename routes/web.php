<?php
use App\Post;
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
    $users = User::all();
    $this_user_posts = Post::where('user_id', 1)->orderBy('updated_at', 'desc');
    $index = (object) [
        'this_user_posts'   => $this_user_posts->get(),
        'users' => $users,
    ];
    return view('index',['index' => $index]);
    // return view('index',['this_user_posts' => $this_user_posts->get()]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/post', 'PostController@index');
