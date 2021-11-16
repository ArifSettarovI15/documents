<?php


namespace App\Modules\Files\Repositories;

use App\Modules\Files\Models\FileModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FilesRepository
{
    /**
     * @param $id
     * @return string
     */
    public static function getFile($id): string
    {

        $file = FileModel::find($id);
        if ($file){
            if ($file->file_sizes == 'none') {
                return Storage::disk('public')->url($file->file_folder . '/' . $file->file_name);
            }

            return Storage::disk('public')->url($file->file_folder . '/medium/' . $file->file_name);
        }
        return Storage::url('core/'.'no-photo.png');
    }

    /**
     * @param $id
     * @return string
     */
    public static function getFilePath($id): string
    {

        $file = FileModel::find($id);
        if ($file){
            if ($file->file_sizes == 'none') {
                return Storage::disk('public')->path($file->file_folder . '/' . $file->file_name);
            }

            return Storage::disk('public')->path($file->file_folder . '/medium/' . $file->file_name);
        }
        return Storage::path('core/'.'no-photo.png');
    }

    /**
     * @param $id
     * @return string
     */
    public static function getFilename($id): string
    {
        return FileModel::find($id)->file_name;
    }

    /**
     * @param $file_data
     * @param string $folder
     * @return mixed
     */
    public function upload_image($file_data, $folder='core'){

        $saved_sizes = [];
        $filename=Str::random(10).'.'.$file_data->extension();

        $img = Image::make($file_data);
        $sizes = $this->GetImageSizes();

        foreach ($sizes as $size_folder=>$size){
            $file_data->storeAs($folder.'/'.$size_folder, $filename);

            $source = storage_path().'/app/'.$folder.'/'.$size_folder.'/'.$filename;

            $image = Image::make($source);

            if (($img->width() / $size['width']) > ($img->height() / $size['height'])) {
                if ($img->width()  < $size['width']) {
                    $size['width'] = $img->width() ;
                }
                $image->widen($size['width']);
            } else {
                if ($img->height() < $size['height']) {
                    $size['height'] = $img->width();
                }
                $image->heighten($size['height']);
            }
            $image->save($source);
            $saved_sizes[] = $size_folder;

        }
        $file = new FileModel;
        $file->file_name = $filename;
        $file->file_module = $folder;
        $file->file_folder = $folder;
        $file->file_sizes = serialize($saved_sizes);
        $file->file_type = $file_data->getMimeType();
        $file->file_time = time();
        if (Auth::id()) {
            $file->file_user_id = Auth::id();
        }
        $file->save();

        return $file->file_id;
    }

    /**
     * @return int[][]
     */
    public function GetImageSizes (): array
    {
        return array(
            'medium' => array(
                'width' => 600,
                'height' => 600
            ),
            'normal' => array(
                'width' => 800,
                'height' => 800
            ),
            'small' => array(
                'width' => 300,
                'height' => 300
            ),
            'large'=>array(
                'width' => 1200,
                'height' => 900
            ),
            'original'=>array(
                'width' => 1920,
                'height' => 1920
            )
        );
    }

    public function upload_file($file_data, string $folder='core'){

        if ($file_data->extension() == 'html') {
            $filename = Str::random(10) . '.' . 'blade.php';
        }
        else {
            $filename = Str::random(10) . '.' . $file_data->extension();
        }

        $file_data->storeAs('public/templates',$filename);
        if ($folder == 'core') {
            $folder = 'templates';
        }
        $file = new FileModel;
        $file->file_name = $filename;
        $file->file_module = $folder;
        $file->file_folder = $folder;
        $file->file_sizes = 'none';
        $file->file_type = $file_data->extension();
        $file->file_time = time();
        if (Auth::id()) {
            $file->file_user_id = Auth::id();
        }
        $file->save();

        return $file->file_id;
    }


    /**
     * @param string $file_content
     * @param string $folder
     * @param string|null $filename
     * @param string $extension
     * @return int
     */
    public static function save_file(string $file_content, string $folder='core', string $filename=null, string $extension='txt'):int
    {
        if (!$filename || $filename === '') {
            $filename = time();
        }

        $filename = str_replace(['&quot;', '"', '.', ' ', ','], ['', '', '_', '_', '_'], $filename);

        $extension = str_replace(['.', ' ', ','], '', $extension);
        $path = $folder.'/'.$filename.'.'.$extension;

        Storage::disk('public')->put($path, $file_content);

        return self::add_row_to_db($filename, $extension, $folder)->file_id;
    }


    public static function add_row_to_db($filename, $extension=null, $folder='core'): FileModel
    {
        $filename = str_replace(['&quot;', '"', '.', ' ', ','], ['', '', '_', '_', '_'], $filename);

        $file = new FileModel;
        $file->file_name = $filename.'.'.$extension;
        $file->file_module = $folder;
        $file->file_folder = $folder;
        $file->file_sizes = 'none';
        $file->file_type = $extension ?? 'txt';
        $file->file_time = time();
        if (Auth::id()) {
            $file->file_user_id = Auth::id();
        }
        $file->save();
        return $file;
    }
}
