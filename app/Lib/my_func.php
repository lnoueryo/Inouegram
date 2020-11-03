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

    public static function Hello()
    {
        $abc = My_func::RandomStorageNumber();
        $def = My_func::RandomStorageNumber();
        $g = [$abc,$def];
        return json_encode($g);
    }

    public static function SelectJson($number)
    {
        $json = [];
        for($j=1; $j<11; $j++){
            if($number == $j){
                for($i=0; $i<$j; $i++){
                    $array[] = ['src' => Storage::allFiles()[My_func::RandomStorageNumber()]];
                    $json += $array;
                }
            }
        }
        return json_encode($json);
    }

}
