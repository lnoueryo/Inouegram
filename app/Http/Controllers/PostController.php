<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Storage;
class PostController extends Controller
{
    public function index() {

        return view('post');
    }

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
            $array[] = $imageName;
            $src_array += $array;
        }
        $post->image = json_encode($src_array);
        $post->save();
    }
}
