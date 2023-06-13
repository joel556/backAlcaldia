<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registrar(Request $request)
    {
        // validar
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required",
            "c_password" => "required|same:password"
        ]);
        // guardar
        $usuario = new User;
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->save();
        
        // respuesta
        return response()->json(["mensaje" => "Usuario Regitrado"], 201);
    }

    public function ingresar(Request $request)
    {
        // validar
        $credenciales = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        // verificar
        if(!Auth::attempt($credenciales)){
            return response()->json(["mensaje" => "No Autorizado"], 401);
        }

        // generar token
        $usuario = $request->user();
        $tokenResult = $usuario->createToken("login personal");
        $token = $tokenResult->plainTextToken;
        // responder
        return response()->json([
            "accessToken" => $token,
            "token_type" => "Bearer",
            "usuario" => $usuario
        ]);
    }


    public function getPerfil()
    {
        $usuario = Auth::user();

        return response()->json($usuario, 200);
        
    }

    public function salir()
    {
        $usuario = Auth::user();  
        $usuario->tokens()->delete();

        return response()->json(["mensaje" => "Salió"], 200);
    }
    
   
}