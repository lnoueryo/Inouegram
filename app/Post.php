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
        static::deleted(function($post) {
            foreach(['likes', 'comments'] as $relation) {
                foreach($post->$relation()->get() as $child){
                    $child->delete();
                }
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
