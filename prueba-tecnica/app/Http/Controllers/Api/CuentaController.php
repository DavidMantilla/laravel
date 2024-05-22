<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CuentaController extends Controller
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
            'cuenta' => ['required'],
            'saldo' =>'required',
            'tipo_cuenta' => 'required',
            'cliente' => 'required',
        ]);
         
         $cuenta= cuenta::where('cuenta',$request->cuenta)->first();
         if($cuenta== null){
            $cuenta = new cuenta();
            $cuenta->cuenta = $request->cuenta;
            $cuenta->saldo = $request->saldo;
            $cuenta->tipo_cuenta = $request->tipo_cuenta;
            $cuenta->asesor = auth()->user()->id;
            $cuenta->cliente=$request->cliente;
            $cuenta->save();
            return  response($cuenta, Response::HTTP_CREATED);
         }
         return response(["mensaje"=> "La cuenta ya existe"], Response::HTTP_NOT_MODIFIED);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $cuenta = new cuenta();
        $consulta= $cuenta::join('clientes', 'clientes.cedula', '=', 'cuentas.cliente')->whereRaw('clientes.cedula = ? or clientes.nombre LIKE ?', [$request->cedula, $request->nombre])->get();
        return  response()->json( $consulta);
    }



    public function consultaSaldo($_cuenta)
    {  
        $cuenta=cuenta::where("cuenta",'like',$_cuenta)->first();
       return response()->json(["cuenta" => $cuenta]);
    }

    /**
     * Update the specified resource in storage.
     */
  
}
