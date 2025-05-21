<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function errorResponse($e)
    {
        Log::critical($e);
        return response()->json([
            'status' => false,
            'message' => 'There was an error while processing your request: ' .
                $e->getMessage()
        ], 500);
    }

    public function validationResponse($e)
    {
        return response()->json(['status' => false, 'message' => $e->errors()->first()], 200);
    }
}