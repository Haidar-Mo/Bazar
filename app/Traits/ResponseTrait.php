<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

trait ResponseTrait
{
    /**
     * Show a success response with model data.
     *
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    public function showResponse($data, string $message = 'Operation successful', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Show an error response with exception message.
     *
     * @param \Exception $exception
     * @param int $status
     * @return JsonResponse
     */
    public function showError(\Exception $exception, string $message = 'Operation failed', int $status = 500): JsonResponse
    {
        Log::error('Error occurred: ' . $exception->getMessage());

        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => $exception->getMessage(),
        ], $status);
    }

    /**
     * Show a general message response.
     *
     * @param string $message
     * @param int $status
     * @param bool $success
     * @return JsonResponse
     */
    public function showMessage(string $message, int $status = 200, bool $success = true): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
        ], $status);
    }
}
