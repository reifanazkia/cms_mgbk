<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function Store(Request $request){
        $validator = Validator::make($request->all(),[
            'Username'      => 'required',
            'Email'         => 'required|email',
            'Password'      => 'required'
        ]);

        if ($validator->fails()){
            return response()->json([
                'message'   => 'Masukan Semua Field',
            ]);
        }

        $data = [
            'name'      => $request->Username,
            'email'     => $request->Email,
            'password'  => Hash::make($request->Password)
        ];

        User::insert($data);

        return response()->json(['message' => 'sukses'] , 200);

    }

    public function Login(Request $request){
        // dd($request->all());
        $data = [
            'email' => $request->Email,
            'password' => $request->Password,
        ];

        if(!$token = auth()->guard('api')->attempt($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Anda salah'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api')->user(),
            'token'   => $token
        ], 200);
    }

    public function logout(Request $request){

        $removetoken = JWTAuth::invalidate(JWTAuth::getToken());

        if($removetoken){
            return response()->json([
                'succes' => true,
                'message' => 'logout succes'
            ]);
        }

    }
}
