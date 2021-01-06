<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users  = User::withCount('posts')->get();
        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return User::create([
            'name' => Str::random(7),
            'screen_name' => Str::random(7),
            'email' => Str::random(7).'@gmail.com',
            'password' => Hash::make('123456789'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $requested_user = User::with(['followees', 'posts' => function($post_query){
            $post_query->with(['likes' => function($like_query){
                $like_query->with(['user'])->orderBy('updated_at', 'desc');
            }, 'comments' => function($comment_query){
                $comment_query->with(['user'])->orderBy('updated_at', 'desc');
            }])->orderBy('updated_at', 'desc');
        }])->find($id);
        return $requested_user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update(['name' => $request->name, 'screen_name' => $request->screen_name, 'email' => $request->email]);
        $user->name = $request->name;
        $user->screen_name = $request->screen_name;
        $user->email = $request->email;
        if(isset($request->profile_image)){
            Storage::disk('avatar')->delete($user->profile_image);
            $base64_image = $request->profile_image;
            @list($type, $file_data) = explode(';', $base64_image);
            @list(, $file_data) = explode(',', $file_data);
            $image_name4avatar = str_random(10).'.'.'png';
            Storage::disk('avatar')->put($image_name4avatar, base64_decode($file_data));
            $user->update(['profile_image' => $image_name4avatar]);
        }
        if(isset($request->bg_image)){
            Storage::disk('background')->delete($user->bg_image);
            $base64_image = $request->bg_image;
            @list($type, $file_data) = explode(';', $base64_image);
            @list(, $file_data) = explode(',', $file_data);
            $image_name4bg = str_random(10).'.'.'png';
            Storage::disk('background')->put($image_name4bg, base64_decode($file_data));
            $user->update(['bg_image' => $image_name4bg]);
            // $user->bg_image= $image_name4bg;
        }
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if(isset($user->profile_image)){
            Storage::disk('avatar')->delete($user->profile_image);
        }
        if(isset($user->bg_image)){
            Storage::disk('background')->delete($user->bg_image);
        }
        $user->delete();
        return response("OK", 200);
    }
}
