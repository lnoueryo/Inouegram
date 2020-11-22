<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $post->save();
        $decoded_images = json_decode($request->cropped_image);
        for ($i=0; $i<count($decoded_images); $i++) {
            $photo = new Photo;
            $photo->post_id = $post->id;
            $base64_image = $decoded_images[$i];
            @list($type, $file_data[$i]) = explode(';', $base64_image);
            @list(, $file_data[$i]) = explode(',', $file_data[$i]);
            $imageName = str_random(10).'.'.'png';
            Storage::disk('local')->put($imageName, base64_decode($file_data[$i]));
            $photo->src = $imageName;
            $photo->save();
        }
        $photos = Photo::where('post_id', $post->id)->get('src');
        $post->image = $photos;
        $post->save();
        return 'hello';
    }
}
