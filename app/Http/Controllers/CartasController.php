<?php

namespace App\Http\Controllers;

use App\Models\Carta;
use App\Models\Coleccion;
use App\Models\Pertenece;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;

class CartasController extends Controller
{
     public function crear(Request $req){
         try{
            $validator = Validator::make(json_decode($req->getContent(), true), [
                'nombre' => 'required',
                'descripcion' => 'required',
                'id_coleccion' => 'required',
            ]);
           
            $respuesta = ["status" => 1, "msg" => ""];
            $datos = $req->getContent();
            $datos = json_decode($datos);
           
            if(Coleccion::where("id", $datos->id_coleccion)->first()){
                $carta = new Carta();
                $carta->nombre = $datos->nombre;
                $carta->descripcion = $datos->descripcion;
                $carta->save();
                $pertenece = new Pertenece();
                $pertenece->id_coleccion = $datos->id_coleccion;
                $pertenece->id_carta = $carta->id;
                $pertenece->save();
                $respuesta["status"] = 1;
                $respuesta["msg"] = "Carta creada con éxito";
            }else{
                $respuesta["status"] = 0;
                $respuesta["msg"] = "La coleccion no existe";
            }
    
           
          
         }catch(\Exception $e){
            $respuesta["status"] = 0;
            $respuesta["msg"] = $e ->getMessage();
         }
         return response()->json($respuesta);
    }

}
