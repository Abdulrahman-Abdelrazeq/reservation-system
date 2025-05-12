<?php

namespace App\Traits;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

trait Response
{

    // Send a standardized JSON API response.
    public function sendRes($status = true, $message, $data = null, $errors = null, $code = 200)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if($data != null) {
            $response['data'] = $data;
        }

        // Append error details if present
        if($errors != null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}