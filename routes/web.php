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
use App\User;
Route::get('/public', function () {
    // $users = User::all();
    // $this_user_posts = Post::where('user_id', 1)->orderBy('updated_at', 'desc');
    // $index = (object) [
    //     'this_user_posts'   => $this_user_posts->get(),
    //     'users' => $users,
    // ];
    // return view('index',['index' => $index]);
    // return view('index',['this_user_posts' => $this_user_posts->get()]);
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
