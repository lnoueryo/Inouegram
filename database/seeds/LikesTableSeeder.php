<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Post;
use App\Like;
use Faker\Factory as Faker;

class LikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Like::truncate();

        //必要ならfaker利用
        $faker = Faker::create('en_US');
        // $faker = Faker::create('ja_JP');

        //必要ならループ（ここをFactory使う）
        for($i = 0; $i < 4000; $i++){
            Like::create([
                'user_id' => $faker->numberBetween(1,count(User::all())),
                'post_id' => $faker->numberBetween(1,count(Post::all())),
                'reaction' => $faker->numberBetween(1,4),
            ]);
        }
    }
}
