<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Cuenta;
use App\Models\Transacciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

use function Laravel\Prompts\select;

class transaccionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return "hola";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "tipo" => ["required",Rule::in(['ingreso', 'salida'])],
            "valor" => ['required', 'numeric'],
            "id_cuenta" => ['required'],
        ]);

         
        $cuenta=Cuenta::where('id',$request->id_cuenta)->first();

        if($cuenta!= null){

            if(($cuenta->saldo>=$request->valor && $request->tipo=="salida") || $request->tipo=="ingreso" ){
              
            if($request->tipo=="salida"){
                $cuenta-> saldo=$cuenta-> saldo-$request->valor; 
            }else{
                $cuenta-> saldo=$request->valor+ $cuenta-> saldo; 
            }
            $cuenta->save();

            $transacciones= new Transacciones();

            $transacciones->tipo = $request->tipo;
            $transacciones->valor= $request->valor;
            $transacciones->id_cuenta= $cuenta->id;
            $transacciones->save();
            return  response(["transacciones"=>$transacciones,"cuenta"=>$cuenta], Response::HTTP_CREATED);
            }else{
                return response (["mensaje"=>"el valor no se puede retirar"],Response::HTTP_NOT_MODIFIED);
            }
        }
        return  response(["mensaje"=>"el numero de cuenta no existe"], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    { 
         $transacciones= Transacciones::where("id_cuenta",$id)->get();

         return  response($transacciones, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, transacciones $transacciones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(transacciones $transacciones)
    {
        //
    }
}
