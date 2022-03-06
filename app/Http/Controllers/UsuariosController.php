<?php

namespace App\Http\Controllers;


use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UsuariosController extends Controller
{
    public function registrar(Request $req){

        
        $validator = Validator::make(json_decode($req->getContent(), true), [
            'nickname' => 'required',
            'rol' => 'required',
            'password' => 'required|regex:/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[A-Za-z0-9]).{6,}/',
            'email' => 'required|unique:usuarios',
        ]);
        
        if($validator->fails()){
            $respuesta["msg"] = $validator->errors()->first();
            $respuesta["status"] = 0;
        }else{
            $respuesta = ["status" => 1, "msg" => ""];
            $datos = $req->getContent();
            $datos = json_decode($datos);
            $usuario = new Usuario();
            $usuario->nickname = $datos->nickname;
            $usuario->rol = $datos->rol;
            $usuario->password = Hash::make($datos->password);
            $usuario->email = $datos->email;
            try{
                $respuesta["status"] = 1;
                $respuesta["msg"] = "Usuario creado con éxito";
                $usuario->save();
            }catch(\Exception $e){
                $respuesta["msg"] = $e ->getMessage();
                $respuesta["status"] = 0;
            }
        }

            return response()->json($respuesta);
    }
    public function login(Request $req){
          
        $jdatos = $req->getContent();
        $datos = json_decode($jdatos);

        $usuario = Usuario::where('nickname', $datos->nickname)->first();
        if($usuario && Hash::check($datos->password, $usuario->password)){
            $token = Hash::make(now().$usuario->email); //creación del token
            $usuario->api_token = $token;
            $usuario->save();
            $respuesta["status"] = 1;
            $respuesta["msg"] = "Sesión Iniciada";
            $respuesta["token"] = $token;
        }else{
            $respuesta["status"] = 0;
        }
        return response()->json($respuesta);
    }
    public function recuperarPass(Request $req){
        $jdatos = $req->getContent();
        $datos = json_decode($jdatos);

        $usuario = Usuario::where('email', $datos->email)->first();
        if($usuario){
           $password = generarPass("abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ",8);
           $usuario->password = Hash::make($password);
           $usuario->save();
           $respuesta["password"] = "Nueva contraseña: ".$password;
        }else{
            $respuesta["msg"] = 401;
            $respuesta["error"] = "no se ha encontrado un usuario con ese email";
        }
        return response()->json($respuesta);
    }
    
}

function generarPass($opciones, $lengt = 5){
    
    $charactersLenght = strlen($opciones);
    $randomString = '';
    for ($i=0; $i < $lengt; $i++) {
        $randomString .= $opciones[rand(0, $charactersLenght - 1)];
    }
    return $randomString;
}