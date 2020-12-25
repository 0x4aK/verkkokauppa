<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function buildFailedValidationResponse(Request $request, array $errors) {
        return response()->json(["message" => "Validator error!" , "errors" => $errors], 400);
    }
}
