<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;

trait ControllerHelper {
    /**
     * response json 
     * 
     * @param array $arr
     * @param int $code
     * @return \Illuminate\Response\JsonResponse
     */
    public function ResponseJson(array $arr, int $code = 200)
    {
        return response()->json($arr, $code);
    }

    /**
     * response json while error validation
     * 
     * @param $error
     * @param int $code
     * @return \Illuminate\Response\JsonResponse
     */
    public function ResponseJsonValidate($error, int $code = 422) {
        return response()->json(compact('error'), $code);
    }

    public function ResponseJsonDataTable($data, $count, $message = 'success get data', $code = 200) 
    {
        return response()->json(compact('data', 'count', 'message'), $code);
    }

    /**
     * response json message only
     * 
     * @param string $message
     * @param int $code
     * @return \Illuminate\Response\JsonResponse
     */
    public function ResponseJsonMessage(string $message, int $code = 200) {
        return $this->ResponseJson(compact('message'), $code);
    }

    /**
     * response from mixed value
     * 
     * @param mixed $var
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function ResponseJsonMixed($var, $message = 'success get data', $code = 200)
    {
        return response()->json([
            'data'=> $var,
            'message' => $message
        ], $code);
    }

    public function upload_file(\Illuminate\Http\UploadedFile $file, string $folder = 'uknown') 
    {
        return Storage::disk('public')->put($folder, $file);
    }

    public function delete_file(string $file_path) 
    {
        return Storage::disk('public')->delete($file_path);
    }

    /**
     * get or set message redirect
     * 
     * @param bool $succes
     * @param string $method
     * @param string $message
     * @param string $exception_message
     * @param int $code
     * @return \Illuminate\Response\JsonResponse
     */
    public function ResponseJsonMessageCRUD(bool $success = true, $method = 'create', $message = null, $exception_message = null, $code = 200)
    {
        if ($success) {
            $final_message = 'Success ';
        } else {
            $final_message = 'Failed ';
        }

        if ($method == 'create') {
            $final_message .= 'insert new data. ';
        } else if ($method == 'edit') {
            $final_message .= 'update data. ';
        } else if ($method == 'delete') {
            $final_message .= 'delete data, ';
        }

        if ($message != null) {
            $final_message .= $message.' ';
        }

        if ($exception_message != null) {
            $final_message .= $exception_message;
        }
        return response()->json(['message' => $final_message], $code);
    }

    /**
     * response 404 not found
     * 
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function ResponseJsonNotFound($message = 'Data or Page Not Found.', $code = 404)
    {
        return response()->json(compact('message'), $code);
    }

    /**
     * response json data
     * 
     * @param $data
     * @param string $message
     * @param int $code 
     * @return \Illuminate\Http\JsonResponse
     */
    public function ResponseJsonData($data, $message = 'success get data', $code = 200)
    {
        return response()->json(compact('data', 'message'), $code);
    }
}
