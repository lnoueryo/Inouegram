<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Like;
use App\Follower;
use App\Comment;
class ApiController extends Controller
{
    public function search(Request $request){
        $cond_name = $request->name;
        if ($cond_name !='') {
            $users = User::where('screen_name', 'like', '%'.$cond_name. '%')->take(5)->get();
        } else {
            $users = User::inRandomOrder()->take(5)->get();
        }
        return $users;
    }

    public function like(Request $request){
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
        return $like;
    }
    public function deleteLike(Request $request){
        $like = Like::where('post_id', $request->postId)->where('user_id', $request->userId)->first();
        $like->delete();
        return $like;
    }
    
    public function likeUsers(Request $request){
        $users_id = Like::where('post_id', $request->postId)->get('user_id');
        $like_users = User::whereIn('id', $users_id)->get();
        return $like_users;
    }

    public function follow(Request $request){
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
    }

    public function comment(Request $request){
        $comment = new Comment;
        $comment->user_id = $request->userId;
        $comment->post_id = $request->postId;
        $comment->text = $request->text;
        $comment->save();
        return $comment;
    }

    public function deleteComment(Request $request){
        $comment = Comment::find($request->id);
        $comment->delete();
        return $comment;
    }

    public function commentUsers(Request $request){
        $users_id = Comment::where('post_id', $request->postId)->get('user_id');
        $comment_users = User::whereIn('id', $users_id)->get();
        return $comment_users;
    }

    public function followingUsers(Request $request){
        $following_users = User::whereIn('id', $request->usersId)->get();
        return $following_users;
    }

    public function likedPostUsers(Request $request){
        $main_user_likes = Like::where('user_id', $request->id);
        $liked_posts = Post::whereIn('id', $main_user_likes->get('post_id'));
        $post_likes = Like::whereIn('post_id', $liked_posts->get('id'));
        $post_likes_users = User::whereIn('id', $post_likes->get('user_id'));
        $post_comments = Comment::whereIn('post_id', $liked_posts->get('id'));
        $post_comments_users = User::whereIn('id', $post_comments->get('user_id'));
        $res = ['userLikes' => $main_user_likes->get(), 'posts' => $liked_posts->get(), 'likes' => $post_likes->get(), 'likeUsers' => $post_likes_users->get(), 'comments' => $post_comments->get(), 'commentUsers' => $post_comments_users->get()];
        return $res;
    }

}
