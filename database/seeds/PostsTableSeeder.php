<?php

use Illuminate\Database\Seeder;
use App\Post;
use Faker\Factory as Faker;
use App\Lib\my_func;
class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //一括削除
        Post::truncate();

        //特定のデータを追加
        Post::create([
            'user_id' => '1',
            'text' => 'I am learning Chinese!!',
            'image' => '[{"src":"chinese.jpg"}]'
        ]);

        //必要ならfaker利用
        $faker = Faker::create('en_US');
        // $faker = Faker::create('ja_JP');
        // $json = my_func::SelectJson($faker->numberBetween(1,10));
        //必要ならループ（ここをFactory使う）
        for($i = 0; $i < 200; $i++){
            Post::create([
                'user_id' => $faker->numberBetween(1,21),
                'text' => $faker->text(),
                'image' => my_func::SelectJson($faker->numberBetween(1,9)),
            ]);
        }

        //factoryを利用（結果は上記と同じ）
        // factory(Photo::class, 5)->create();
    }
}
