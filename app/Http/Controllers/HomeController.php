<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Photo;
use App\Like;
use App\Follower;
use App\Comment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $main_user = Auth::user();
        $following_users = Follower::where('following_id', Auth::id());
        $following_users_info = User::where('id', Auth::id())->orWhereIn('id', $following_users->get('followed_id'))->get();
        $posts = Post::whereIn('user_id', User::where('id', Auth::id())->orWhereIn('id', $following_users->get('followed_id'))->get('id'));
        $main_user_likes = Like::where('user_id', Auth::id())->get();
        $likes = Like::whereIn('post_id', $posts->get('id'));
        $liked_users = User::whereIn('id', $likes->get('user_id'))->get();
        $comments = Comment::whereIn('post_id', $posts->orderBy('updated_at', 'desc')->take(20)->get('id'))->get();
        return view('index', ['main_user' => $main_user, 'posts' => $posts->orderBy('updated_at', 'desc')->take(20)->get(), 'main_user_likes' => $main_user_likes, 'following_users_info' => $following_users_info, 'likes' => $likes->get(), 'liked_users' => $liked_users, 'comments' => $comments]);
    }

    // public function search(Request $request){
    //     $followers = Follower::where('following_id', $request->id);
    //     $my_users = User::where('id',$request->id)->orWhereIn('id', $followers->get('followed_id'))->get();
    //     return $my_users;
    // }
    public function json(){
        $posts = Post::whereBetween('user_id', [51, 101])->get();
        return view('json', compact('posts'));
    }

}
