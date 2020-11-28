<?php

namespace App\Lib;
use Illuminate\Support\Facades\Storage;

class My_func
{
    public static function RandomStorageNumber()
    {
        $file_total_numbers = count(Storage::allFiles())-1;
        $random_numbers = random_int (0, $file_total_numbers);
        return $random_numbers;
    }

    public static function SelectJson($number_between, $src_array)
    {
        $json = [];
        $abc = [];
        for($j=1; $j<11; $j++){
            if($number_between == $j){
                for($i=0; $i<$j; $i++){
                    $random_numbers = random_int (0, count($src_array)-1);
                    $array[] = ['src' => $src_array[$random_numbers]];
                    $json += $array;
                    $def[] = $src_array[$random_numbers];
                    $abc += $def;
                }
            }
        }
        return [json_encode($json), $abc];
    }


}
