<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Like;
use App\Comment;
use App\Post;
use App\Follower;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index() {

        // $id = $request->id;
        // $this_user = User::find(1);
        // $my_info = User::find(Auth::id());
        $this_user_posts = Post::where('user_id', Auth::id())->orderBy('updated_at', 'desc');
        // $this_user_likes = Like::whereIn('post_id', $this_user_posts->get('id'))->get();
        // $this_user_comments = Comment::whereIn('post_id', $this_user_posts->get('id'))->latest();
        // $comment_users = User::whereIn('id', $this_user_comments->get('user_id'))->get();
        // $followed_numbers = Follower::where('followed_id', $id);
        // $followed_count = $followed_numbers->count();
        // $following_count = Follower::where('following_id', $id)->count();
        // if($followed_numbers->where('following_id', Auth::id())->count() == 0){
        //     $followed = 0;
        // } else {
        //     $followed = 1;
        // }
        $user = User::all();

        return view('profile',['this_user_posts' => $this_user_posts->get(), 'user' => $user]);
    }

    // public function index(Request $request) {

    //     $id = $request->id;
    //     $this_user = User::find(1);
    //     $my_info = User::find(Auth::id());
    //     $this_user_posts = Post::where('user_id', $id)->orderBy('updated_at', 'desc');
    //     $this_user_likes = Like::whereIn('post_id', $this_user_posts->get('id'))->get();
    //     $this_user_comments = Comment::whereIn('post_id', $this_user_posts->get('id'))->latest();
    //     $comment_users = User::whereIn('id', $this_user_comments->get('user_id'))->get();
    //     $followed_numbers = Follower::where('followed_id', $id);
    //     $followed_count = $followed_numbers->count();
    //     $following_count = Follower::where('following_id', $id)->count();
    //     if($followed_numbers->where('following_id', Auth::id())->count() == 0){
    //         $followed = 0;
    //     } else {
    //         $followed = 1;
    //     }

    //     return view('profile',['comment_users' => $comment_users, 'this_user_comments' => $this_user_comments->get(), 'this_user_likes' => $this_user_likes, 'followed_count' => $followed_count, 'following_count' => $following_count, 'this_user' => $this_user, 'my_info' => $my_info, 'this_user_posts' => $this_user_posts->get(), 'id' => $id, 'followed' => $followed]);
    // }

    public function create(Request $request) {
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
    }

    public function deletePost(Request $request) {
        $request = json_decode($request->post);
        $post = Post::find($request->id);
        $image =$request->image;
        $exists = [];
        for($i=0; $i<count($image); $i++){
            Storage::delete($request->image[$i]->src);
        };
        $post->delete();
        $posts = Post::where('user_id', $request->user_id)->orderBy('updated_at', 'desc')->get();
        return $posts;
    }

    public function delete(Request $request) {
        $follower = Follower::where('following_id', Auth::id())->where('followed_id', $request->id);
        $follower->delete();
        return  redirect()->route('profile', ['id' => $request->id]);
    }

    public function uploadBg(Request $request) {
        $user = User::find($request->userId);
        Storage::disk('background')->delete($user->bg_image);
        $image = $request->bgData;
        if(strpos($image,'data:image/png;') !== false){
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = str_random(10).'.'.'png';
        } else {
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = str_random(10).'.'.'jpg';
        }
        Storage::disk('background')->put($imageName, base64_decode($image));
        $user->bg_image = $imageName;
        $user->save();
        return $user;
    }
    public function uploadAvatar(Request $request) {
        $user = User::find($request->userId);
        Storage::disk('avatar')->delete($user->profile_image);
        $image = $request->avatarData;
        if(strpos($image,'data:image/png;') !== false){
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = str_random(10).'.'.'png';
        } else {
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = str_random(10).'.'.'jpg';
        }
        Storage::disk('avatar')->put($imageName, base64_decode($image));
        $user->profile_image = $imageName;
        $user->save();
        return $user;
    }
}
