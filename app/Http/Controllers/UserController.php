<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Str;

class UserController extends Controller
{
    public function store(Request $request){
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        User::create($input);

        return response()->json([
            'res' => true,
            'message' => 'Usuario creado'
        ]);
    }

    public function login(Request $request){
        $user = User::whereEmail($request->email)->first();
        if(!is_null($user) && Hash::check($request->password, $user->password)){
            $user->api_token = Str::random(100);
            $user->save();

            return response()->json([
                'res' => true,
                'token' => $user->api_token,
                'message' => 'Usuario ha iniciado sesion'
            ]);
        }else{
            return response()->json([
                'res' => false,
                'message' => 'Usuario o contraseña incorrecta'
            ]);
        }
    }

    public function logout(){
        $user = auth()->user();
        $user->api_token = null;

        return response()->json([
            'res' => true,
            'message' => 'Ha cerrado sesión'
        ]);        
    }

    public function auth(){
        return response()->json([
            'res' => false,
            'message' => 'No autenticado'
        ]);
    }
}
