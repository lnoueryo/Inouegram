<?php

use Illuminate\Database\Seeder;
use App\Comment;
use App\Post;
use Faker\Factory as Faker;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //一括削除
        Comment::truncate();

        //特定のデータを追加
        Comment::create([
            'user_id' => '1',
            'post_id' => '1',
            'text' => '加油',
        ]);

        //必要ならfaker利用
        $faker = Faker::create('en_US');
            $posts_length = Post::all()->count();
        for($i = 0; $i < 600; $i++){
            Comment::create([
                'user_id' => $faker->numberBetween(1,21),
                'post_id' => $faker->numberBetween(1,$posts_length-1),
                'text' => $faker->text(),
            ]);
        }

        //factoryを利用（結果は上記と同じ）
        // factory(Photo::class, 5)->create();
    }
}
