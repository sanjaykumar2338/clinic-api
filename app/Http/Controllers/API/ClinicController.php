<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Auth;
use Illuminate\Support\Str;

class ClinicController extends Controller
{
    public function add(Request $request){
        $response = [
            'success'=>true,
            'data'=>$request->user(),
            'message'=>'User register successfully!'
        ];

        return response()->json($response,200);
    }
}