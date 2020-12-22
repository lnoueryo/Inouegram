<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'text','image',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($post) {
            // $likes = $post->likes;
            // $comments = $post->comments;
            // foreach($likes as $like) {
            //     $like->delete();
            // }
            // foreach($comments as $comment) {
            //     $comment->delete();
            // }
            foreach(['likes', 'comments'] as $relation) {
                $comment->delete();
            }
        });
    }

    public function users()
    {
        return $this->hasMany('App\User', 'user_id');
    }

    public function likes()
    {
        return $this->hasMany('App\Like', 'post_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'post_id');
    }
}
