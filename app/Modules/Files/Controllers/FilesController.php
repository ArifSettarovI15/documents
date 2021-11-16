<?php

namespace App\Modules\Files\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Files\Repositories\FilesRepository;
use App\Repositories\Functions\Strings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Validator;

class FilesController extends Controller
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new FilesRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function add_image(Request $request): JsonResponse
    {
        if (!$request->files) {
            return response()->json(['Вы не выбрали файл']);
        }
        $validator = Validator::make($request->all(), ['photo' => 'required|image']);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if (!isset($request->folder)) {
            $request->folder = 'core';
        }
        $file_id = $this->repo->upload_image($request->file('photo'), $request->folder);


        if (isset($request->name)) {
            return response()->json(['file_id' => $file_id, 'name' => $request->name]);
        }

        return response()->json(['file_id' => $file_id]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function add_file(Request $request): JsonResponse
    {
        if (!$request->files) {
            return response()->json(['Вы не выбрали файл']);
        }
        $validator = Validator::make($request->all(), ['file' => 'required|file']);

        if ($validator->fails()) {
            return response()->json(
                ['status' => false, 'message' => 'Файл не выбран или не является office-документом']
            );
        }

        $file_id = $this->repo->upload_file($request->file('file'));

        if (!$file_id) {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Ошибка загрузки файла',
                ]
            );
        }
        if (isset($request->name)) {
            return response()->json(
                [
                    'status'  => true,
                    'message' => 'Файл загружен успешно',
                    'file_id' => $file_id,
                    'name'    => $request->name,
                ]
            );
        }

        return response()->json(
            [
                'status'  => true,
                'message'  => 'Файл загружен успешно',
                'file_id' => $file_id,
            ]
        );
    }

    /**
     * @param $folder
     * @param $file
     * @return StreamedResponse
     */
    public function get_file($folder, $file): StreamedResponse
    {
        $array = explode('.', $file);
        $extension = end($array);
        if ($extension == 'html') {
            $data = file_get_contents(Storage::disk('public')->path($folder . '/' . $file));
            echo $data;
        }
        $filename = Strings::translit($file);
        return Storage::disk('public')->download($folder . '/' . $file, $filename);
    }

}
