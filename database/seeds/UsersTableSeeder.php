<?php

use Illuminate\Database\Seeder;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //一括削除
        User::truncate();

        //特定のデータを追加
        User::create([
            'name' => '井上領',
            'screen_name' => 'lnoueryo',
            'profile_image' => 'panda.png',
            'email' => 'popo62520908@gmail.com',
            'password' => Hash::make('123456789')
        ]);

        //必要ならfaker利用
        $faker = Faker::create('en_US');
        // $faker = Faker::create('ja_JP');

        //必要ならループ（ここをFactory使う）
        for($i = 0; $i < 50; $i++){
            User::create([
                'name' => $faker->name,
                'screen_name' => $faker->name,
                'profile_image' => $faker->image('/var/www/html/my_project/public/storage',640,480, null, false),
                'email' => $faker->email,
                'password' => Hash::make('123456789')
            ]);
        }

        //factoryを利用（結果は上記と同じ）
        factory(User::class, 20)->create();
    }
}
