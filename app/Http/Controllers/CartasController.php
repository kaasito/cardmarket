<?php

namespace App\Http\Controllers;

use App\Models\Carta;
use App\Models\Venta;
use App\Models\Coleccion;
use App\Models\Pertenece;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
           
            if($validator->fails()){
                $respuesta = ['status'=>0, 'msg'=>$validator->errors()->first()]; //si los datos introducidos son erroneos salta un error
            }else{
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
           
            if($validator->fails()){
                $respuesta = ['status'=>0, 'msg'=>$validator->errors()->first()]; //si los datos introducidos son erroneos salta un error
            }else{
            $respuesta = ["status" => 1, "msg" => ""];
            $datos = $req->getContent();
            $datos = json_decode($datos);
            $usuario = Usuario::where('Api_token', $datos->Api_token)->first();
            $carta = Carta::find($datos->id_carta);
            $carta->alta = '1';
            $carta->save();
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
    public function buscarparavender(Request $req){
        $respuesta = ["status" => 1, "msg" => ""];
        $datos = $req->input('filtro', '');
       
        try{
            $peticion = DB::table('cartas');

            if($datos != '') {
                $peticion->where('nombre', 'like', '%'.$datos.'%')->where('alta','0');
            }else{
                $respuesta["status"] = 0;
                $respuesta["msg"] = "Introduce un filtro";
                return response()->json($respuesta);
            }
            
            $respuesta["status"] = 1;
            $respuesta["msg"] = "Mostrando todass las cartas";
            $respuesta["datos"] = $peticion->distinct()->get();

        }catch(\Exception $e){
            $respuesta["msg"] = $e ->getMessage();
            $respuesta["status"] = 0;
        }

        return response()->json($respuesta);
    }
    public function buscaralaventa(Request $req){
        $respuesta = ["status" => 1, "msg" => ""];
        $datos = $req->input('filtro', '');

        try{
            if($datos != '') {
                // $ventas = Venta::where('nombre_carta', 'like', '%'.$datos.'%')->distinct()->get()->toArray();
                $ventas = DB::table('ventas')
                ->leftJoin('cartas', 'ventas.id_carta', '=', 'cartas.id')
                ->where('cartas.nombre', 'like', '%'.$datos.'%')
                ->get()
                ->toArray();
            }

            usort($ventas, function($object1, $object2){
                return $object1->precio > $object2->precio;
            });

            if(count($ventas) == 0){
                $respuesta["status"] = 0;
                $respuesta["msg"] = "no se han encontrado Coincidencias";
            }else{
                $respuesta["status"] = 1;
                $respuesta["msg"] = "Mostrando todos los ventas";
                $respuesta["datos"] = $ventas;
            }
            

        }catch(\Exception $e){
            $respuesta["msg"] = $e ->getMessage();
            $respuesta["status"] = 0;
        }



        return response()->json($respuesta);
    }
    

    

}
