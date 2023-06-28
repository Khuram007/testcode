<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait ApiResponse
{
    /**
     * @Description set response
     * @param  string  $message
     * @param  int  $statusCode
     * @param  array  $data
     * @return \Illuminate\Http\JsonResponse
     * @Author Khuram Qadeer.
     */
    public function setResponse(
        array $data = [],
        string $message = 'success',
        int $statusCode = ResponseAlias::HTTP_OK
    ): \Illuminate\Http\JsonResponse {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
