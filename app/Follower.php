<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    protected $table = 'followers';
    protected $primaryKey = 'id';
    public function follower()
    {
        return $this->hasOne('App\User', 'id', 'followed_id');
    }
    public function followee()
    {
        return $this->hasOne('App\User', 'id', 'following_id');
    }
}
