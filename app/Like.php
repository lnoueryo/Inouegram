<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'likes';
    protected $primaryKey = 'id';
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

}
