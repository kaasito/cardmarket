<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;

class ParticularValido
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
                return response("Api key no vale", 401);
            }else{
                $rol = $usuario->rol;
                    if($rol == "Particular"){
                        return $next($req);
                    }else{
                        return response("No tienes permisos para hacer eso, tu puesto de trabajo es ".$rol, 401);
                    }       
            }
        }else{
            return response("No api token", 401);
        } 
    }
}
