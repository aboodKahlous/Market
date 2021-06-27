<?php

namespace App\Traits;

trait ResponseTrait {

    public function returnWithSuccess($data = null, $message = '',$code = 200) {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => $message,
            'code' => $code
        ], $code);
    }

    public function returnWithFailed($data = null, $message = '', $code = 500) {
        return response()->json([
            'status' => 'failed',
            'data' => $data,
            'message' => $message,
            'code' => $code
        ], $code);
    }

    public function returnWithNotFound($data = null, $nameSearch = '', $code = 404) {
        return response()->json([
            'status' => 'Not Found',
            'data' => $data,
            'message' => $nameSearch.' Not Found',
            'code' => $code
        ], $code);
    }

}
