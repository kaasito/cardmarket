<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;

class AdministradorValido
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $req, Closure $next)
    {
        $jdata = $req->getContent();
        $datos = json_decode($jdata);
        
        if($datos->Api_token){
            $token = $datos->Api_token;
            $usuario = Usuario::where('Api_token', $token)->first(); //devuelve objeto
            if(!$usuario){
                $respuesta["status"] = 0;
                $respuesta["msg"] = "Api key no vale";
                return response()->json($respuesta);
            }else{
                $rol = $usuario->rol;
                    if($rol == "Administrador"){
                        return $next($req);
                    }else{
                        $respuesta["status"] = 0;
                        $respuesta["msg"] = "No tienes permisos para hacer eso, tu puesto de trabajo es ".$rol;
                        return response()->json($respuesta);
                    }       
            }
        }else{
            $respuesta["status"] = 0;
            $respuesta["msg"] = "No apitoken";
            return response()->json($respuesta);
           
        }    
    }
}
