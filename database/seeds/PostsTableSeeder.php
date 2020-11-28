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
            'title' => 'Chinese',
            'image' => '[{"src":"chinese.jpg"}]'
        ]);

        //必要ならfaker利用
        $faker = Faker::create('en_US');
        // $faker = Faker::create('ja_JP');
        // $json = my_func::SelectJson($faker->numberBetween(1,10));
        //必要ならループ（ここをFactory使う）
        
        for($i = 0; $i < 30; $i++){
            $src_array = Storage::files();
            $json = my_func::SelectJson($faker->numberBetween(1,9), $src_array);
            
            Post::create([
                'user_id' => $faker->numberBetween(1,51),
                'text' => $faker->text(),
                'title' => $faker->title(),
                'image' => $json[0],
            ]);
            for ($i=0; $i < count($json[1]); $i++) { 
                $index = array_search($json[1][$i], $src_array);
                array_splice($src_array, $index);
            }

        }

        //factoryを利用（結果は上記と同じ）
        // factory(Photo::class, 5)->create();
    }
    // public static function SelectJson($number)
    // {
    //     $json = [];
    //     $src_array = Storage::allFiles();
    //     for($j=1; $j<11; $j++){
    //         if($number == $j){
    //             for($i=0; $i<$j; $i++){
    //                 $array[] = ['src' => $src_array[My_func::RandomStorageNumber()]];
    //                 $json += $array;
    //                 unset($src_array[My_func::RandomStorageNumber()]);
    //             }
    //         }
    //     }
    //     return json_encode($json);
    // }
}
