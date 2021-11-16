<?php


namespace App\Modules;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use URL;

class ModuleClass
{

    public static function asset($data){
        preg_match('~(.*?):~s', $data, $matched);
        $data = str_replace($matched[0], '',$data);
        $module = $matched[1];
        $path_base =$module.'/Views/assets/'.$data;


        $data = Storage::disk('modules_assets')->get($path_base);

        return $data;

    }
//    static function PrepareData($data){
//
//        return ['path'=>$path, 'path_base'=>$path_base];
//    }
}
