<?php

namespace App\Http\Controllers;

use App\Models\Carta;
use App\Models\Coleccion;
use App\Models\Pertenece;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;

class ColeccionsController extends Controller
{
    public function crear(Request $req){
       
        $respuesta = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        $datos = json_decode($datos);


        if(isset($datos->id_carta)){
            
            

            try{

                $validator = Validator::make(json_decode($req->getContent(), true), [
                    'nombre' => 'required',
                    'imagen' => 'required',
                    'id_carta' => 'required',
                ]);
    
                if($validator->fails()){
                    $respuesta = ['status'=>0, 'msg'=>$validator->errors()->first()]; //si los datos introducidos son erroneos salta un error
                }else{
                if(Carta::where("id", $datos->id_carta)->first()){ 
                    $coleccion = new Coleccion();
                    $coleccion->nombre = $datos->nombre;
                    $coleccion->imagen = $datos->imagen;
                    $coleccion->save();
                    $pertenece = new Pertenece();
                    $pertenece->id_coleccion = $coleccion->id;
                    $pertenece->id_carta = $datos->id_carta; 
                    $pertenece->save();
                    $respuesta["status"] = 1;
                    $respuesta["msg"] = "Coleccion creada con éxito";
                }else{
                    $respuesta["status"] = 0;
                    $respuesta["msg"] = "La carta no existe";
                }
            }
            }catch(\Exception $e){
                $respuesta["status"] = 0;
                $respuesta["msg"] = $e ->getMessage();
            }
            
        }else{
            try{
                $validator = Validator::make(json_decode($req->getContent(), true), [
                    'nombre' => 'required',
                    'imagen' => 'required',
                    'carta_nombre' => 'required',
                    'carta_descripcion' => 'required',
                ]);
                
            if($validator->fails()){
                $respuesta = ['status'=>0, 'msg'=>$validator->errors()->first()]; //si los datos introducidos son erroneos salta un error
            }else{
                $coleccion = new Coleccion();
                $coleccion->nombre = $datos->nombre;
                $coleccion->imagen = $datos->imagen;
                $coleccion->save();
                $carta = new Carta();
                $carta->nombre = $datos->carta_nombre;
                $carta->descripcion = $datos->carta_descripcion; 
                $carta->save();
                $pertenece = new Pertenece();
                $pertenece->id_coleccion = $coleccion->id;
                $pertenece->id_carta = $carta->id; 
                $pertenece->save();
                $respuesta["status"] = 1;
                $respuesta["msg"] = "Coleccion creada con éxito";
            }
                
            }catch(\Exception $e){
                $respuesta["status"] = 0;
                $respuesta["msg"] = $e ->getMessage();
            }
           
        }
       
        return response()->json($respuesta);
    }

    public function asociar(Request $req){
       
        $respuesta = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        $datos = json_decode($datos);

        if(isset($datos->id_carta) && isset($datos->id_coleccion)){
            try{
                $validator = Validator::make(json_decode($req->getContent(), true), [
                    'id_carta' => 'required',
                    'id_coleccion' => 'required'
                ]);
    
                if($validator->fails()){
                    $respuesta = ['status'=>0, 'msg'=>$validator->errors()->first()]; //si los datos introducidos son erroneos salta un error
                }else{
                    $pertenece = new Pertenece();
                    $pertenece->id_carta = $datos->id_carta;
                    $pertenece->id_coleccion = $datos->id_coleccion;
                    $pertenece->save();
                    $respuesta["msg"] = "Asociación exitosa";
                }
            }catch(\Exception $e){
                $respuesta["status"] = 0;
                $respuesta["msg"] = $e ->getMessage();
            }
           
        }else{
            $respuesta["status"] = 0;
            $respuesta["msg"] = "debe uste de poner el id de la carta y el id de la coleccion a la que la quiere asociar";
        }
       
        return response()->json($respuesta);
    } 
   
}
