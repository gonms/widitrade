<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request) {
        $credentials = request(['email', 'password']);
    
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => ['required'],
            'password' => ['required']
         ]);
      if($validator->fails()){
          return response()->json($validator->messages(), 200);
       }

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    //     $data = $request->all();

    //     $totUser = \App\Models\User::where('email', $data['email'])->count();

    //      return $totUser == 0 ? 
    //         response()->json(["status" => "KO", "mensaje" => "El usuario no existe"], 204)
    //     :
    //     response()->json(["status" => "OK", "mensaje" => "JWT"], 200);
        
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
