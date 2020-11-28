<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Photo;
use App\Like;
use App\Follower;
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
        $my_info = Auth::user();
        $followers = Follower::where('following_id', $my_info->id);
        $my_users = User::where('id', $my_info->id)->orWhereIn('id', $followers->get('followed_id'));
        $posts = Post::whereIn('user_id', $my_users->get('id'));
        $my_likes = Like::where('user_id', Auth::id())->get();
        $likes = Like::whereIn('post_id', $posts->get('id'))->get();
        return view('index', ['my_info' => $my_info, 'posts' => $posts->orderBy('updated_at', 'desc')->take(20)->get(), 'my_likes' => $my_likes, 'my_users' => $my_users->get(), 'likes' => $likes]);
    }
    // public function index()
    // {
    //     $latest_posts = Post::latest()->take(20);
    //     $latest_posted_users = User::whereIn('id', $latest_posts->get('user_id'));
    //     return view('index',['latest_posts' => $latest_posts->get('user_id'), 'latest_posted_users' => $latest_posted_users->get()]);
    // }

    public function create(Request $request)
    {
        $post = new Post;
        $post->user_id = $request->userId;
        $post->text = $request->message;
        $post->title = $request->title;
        $post->save();
        $decoded_images = json_decode($request->cropped_image);
        $src_array = [];
        for ($i=0; $i<count($decoded_images); $i++) {
            $base64_image = $decoded_images[$i];
            @list($type, $file_data[$i]) = explode(';', $base64_image);
            @list(, $file_data[$i]) = explode(',', $file_data[$i]);
            $imageName = str_random(10).'.'.'png';
            Storage::disk('local')->put($imageName, base64_decode($file_data[$i]));
            $array[] = ['src' => $imageName];
            $src_array += $array;
        }
        $post->image = json_encode($src_array);
        $post->save();
    }

    public function createdes(Request $request)
    {
        // $post = new Post;
        // $post->user_id = 1;
        // $post->text = $request->message;
        // $post->save();
        $decoded_images = json_decode($request->cropped_image);
        // $base64_image = $decoded_images[0];
        // @list($type, $file_data[0]) = explode(';', $base64_image);
        // @list(, $file_data[0]) = explode(',', $file_data[0]);
        // $imageName = str_random(10).'.'.'png';
        // Storage::disk('local')->put($imageName, base64_decode($file_data[0]));
        for ($i=0; $i<count($decoded_images); $i++) {
            // $photo = new Photo;
            // $photo->post_id = $post->id;
            $base64_image = $decoded_images[$i];
            @list($type, $file_data[$i]) = explode(';', $base64_image);
            @list(, $file_data[$i]) = explode(',', $file_data[$i]);
            $imageName = str_random(10).'.'.'png';
            Storage::disk('local')->put($imageName, base64_decode($file_data[$i]));
            // $photo->src = $imageName;
            // $photo->save();
        }
        // $photos = Photo::where('post_id', $post->id)->get('src');
        // $post->image = $photos;
        // $post->save();
        return 'hello';
    }

    // public function store(Request $request)
    // {
    //     $filename = $request->photo->name;
    //     $path = $request->photo->storeAs('images', $filename);
    // }

    public function search(Request $request){
        $followers = Follower::where('following_id', $request->id);
        $my_users = User::where('id',$request->id)->orWhereIn('id', $followers->get('followed_id'))->get();
        return $my_users;
    }

}
