<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendSuccess($message, $data = [])
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, 200);
    }

    public function sendFailure($message)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        return response()->json($response, 400);
    }

}
