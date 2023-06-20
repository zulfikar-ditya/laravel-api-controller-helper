<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

trait ControllerHelpers
{
    /**
     * Response json
     */
    public function responseJson(mixed $arr, int $code = 200): JsonResponse
    {
        return response()->json($arr, $code);
    }

    /**
     * Response json validation error
     */
    public function responseJsonValidate(\Illuminate\Support\MessageBag $error, int $code = 422): JsonResponse
    {
        return response()->json(compact('error'), $code);
    }

    /**
     * Response json message
     */
    public function responseJsonMessage(string $message, int $code = 200): JsonResponse
    {
        return response()->json(compact('message'), $code);
    }

    /**
     * Response json data
     */
    public function responseJsonData(mixed $data, string $message = 'success get data', int $code = 200): JsonResponse
    {
        return response()->json(compact('data', 'message'), $code);
    }

    /**
     * Response json message crud
     */
    public function responseJsonMessageCrud(bool $success = true, string $method = 'create', string $message = null, string $exception_message = null, int $code = 200, mixed $data = null): JsonResponse
    {
        if ($success) {
            $final_message = 'Success ';
        } else {
            $final_message = 'Failed ';
        }

        $methodBind = [
            'create' => 'insert new data. ',
            'edit' => 'update data. ',
            'delete' => 'delete data. ',
            'restore' => 'restore data. ',
            'forceDelete' => 'force delete data. ',
        ];

        if (array_key_exists($method, $methodBind)) {
            $final_message .= $methodBind[$method];
        }

        if ($message != null) {
            $final_message .= $message . ' ';
        }

        if ($exception_message != null) {
            $final_message .= $exception_message;
        }

        if ($data == null) {
            return response()->json(['message' => $final_message], $code);
        } else {
            return response()->json(['message' => $final_message, "result" => $data], $code);
        }
    }

    /**
     * Response message crud
     */
    public function responseMessageCrud(bool $success = true, string $method = 'create', string $message = null, string $exception_message = null): array
    {
        if ($success) {
            $final_message = 'Success ';
        } else {
            $final_message = 'Failed ';
        }

        $methodBind = [
            'create' => 'insert new data. ',
            'edit' => 'update data. ',
            'delete' => 'delete data. ',
            'restore' => 'restore data. ',
            'forceDelete' => 'force delete data. ',
        ];

        if (array_key_exists($method, $methodBind)) {
            $final_message .= $methodBind[$method];
        }
        if ($message != null) {
            $final_message .= $message . ' ';
        }

        if ($exception_message != null) {
            $final_message .= $exception_message;
        }

        return [
            'success' => $success,
            'message' => $final_message
        ];
    }

    /**
     * Response file
     */
    public function responseFile(string $file_name): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return response()->file(storage_path('/app/public/' . $file_name));
    }

    /**
     * Response download  from storage
     */
    public function responseDownloadStorage(string $file): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return response()->download(storage_path('/app/public/' . $file));
    }

    /**
     * Response download
     */
    public function responseDownload(string $file): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return response()->download($file);
    }

    /**
     * Upload file to storage
     */
    public function uploadFile(\Illuminate\Http\UploadedFile $file, string $folder = 'unknown'): string|bool
    {
        return Storage::disk('public')->put($folder, $file);
    }

    /**
     * Delete file  from storage
     */
    public function deleteFile(string $file_path): bool
    {
        return Storage::disk('public')->delete($file_path);
    }

    /**
     * Validate api
     */
    public function validateApi($request, $rules): bool|JsonResponse
    {
        $validate = Validator::make($request, $rules);

        if ($validate->fails()) {
            return $this->responseJsonValidate($validate->errors());
        }

        return true;
    }
}
