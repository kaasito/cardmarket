<?php

namespace App\Http\Controllers;

use App\Models\Carta;
use App\Models\Venta;
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
    public function venta(Request $req){
        try{
            $validator = Validator::make(json_decode($req->getContent(), true), [
                'id_carta' => 'required',
                'cantidad' => 'required',
                'precio_total' => 'required'
            ]);
           
          
            $respuesta = ["status" => 1, "msg" => ""];
            $datos = $req->getContent();
            $datos = json_decode($datos);
            $usuario = Usuario::where('Api_token', $datos->Api_token)->first();
            if(Venta::where("id_carta", $datos->id_carta)->first()){
                $respuesta["status"] = 0;
                $respuesta["msg"] = "La carta ya esta a la venta";
            }else{
                $venta = new Venta();
                $venta->id_carta = $datos->id_carta;
                $venta->cantidad = $datos->cantidad;
                $venta->precio = $datos->precio_total;
                $venta->id_usuario = $usuario->id;
                $venta->save();
                $respuesta["status"] = 1;
                $respuesta["msg"] = "Carta en venta";
            }
    
           
          
         }catch(\Exception $e){
            $respuesta["status"] = 0;
            $respuesta["msg"] = $e ->getMessage();
         }
         return response()->json($respuesta);
    }
    public function alta(Request $req){
        //UNICA Y EXCLUSIVAMENTE administrador 
        $validator = Validator::make(json_decode($req->getContent(), true), [
            'id_carta' => 'required',
            'alta' => 'required',
        ]);
       
        $respuesta = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        $datos = json_decode($datos);
        $carta = Carta::where('id', $datos->id_carta)->first();
        $carta->alta = $datos->alta;
        $respuesta["msg"] = "Carta dada de alta";
    }
    

    

}
