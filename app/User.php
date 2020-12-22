<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'screen_name','profile_image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($user) {
            // $posts = $user->posts;
            // $likes = $user->likes;
            // $comments = $user->comments;
            // $followees = $user->followees;
            // $followers = $user->followers;
            // foreach($posts as $post) {
            //     $post->delete();
            // }
            // foreach($likes as $like) {
            //     $like->delete();
            // }
            // foreach($comments as $comment) {
            //     $comment->delete();
            // }
            // foreach($followees as $followee) {
            //     $followee->delete();
            // }
            // foreach($followers as $follower) {
            //     $follower->delete();
            // }
            foreach(['posts', 'likes', 'comments', 'followees', 'followers'] as $relation) {
                $relation->delete();
            }
        });
    }

    public function posts()
    {
        return $this->hasMany('App\Post', 'user_id');
    }

    public function likes()
    {
        return $this->hasMany('App\Like', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'user_id');
    }

    public function followees()
    {
        return $this->hasMany('App\Follower', 'followed_id');
    }

    public function followers()
    {
        return $this->hasMany('App\Follower', 'following_id');
    }

}
