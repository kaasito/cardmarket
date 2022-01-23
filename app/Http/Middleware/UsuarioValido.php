<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;

class UsuarioValido
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
            //$table->enum("rol", ["Particular","Profesional","Administrador"]);
            $rol = $usuario->rol;
            switch ($rol) {
                case 'Particular':
                    $permiso = 1;
                    break;
                case 'Profesional':
                    $permiso = 1;
                    break;
                case 'Administrador':
                    $permiso = 3;
                    break;
                default:
                    return response("No tiene puesto", 401); 
                    break;
                }

                if($permiso == 1){
                    return $next($req); //Pasar a la siguiente condicion o en su defecto Controller
                }else{
                    return response("No tienes permisos para hacer eso, tu puesto de trabajo es ".$rol, 401);
                }       
        }
    }else{
        return response("No api key", 401);
    }    
    }
}
