<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClienteController extends Controller
{
    public function store(Request $request)
    {
       $request->validate([
            'cedula' => 'required',
            'nombre' =>'required',
            'email' => 'required| email',
        ]);
               
        
        $cliente = new Cliente();
        $cliente->cedula = $request->cedula;
        $cliente->nombre = $request->nombre;
        $cliente->email = $request->email;
        $cliente->save();
        return  response($cliente, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function search($cedula )
    {

        $cliente = Cliente::whereRaw('clientes.cedula = ? or clientes.nombre LIKE ?', [$cedula, $cedula])->first();

         if($cliente!=""){

             return response($cliente,Response::HTTP_OK);
         }else{
            return response(["message"=>""],Response::HTTP_NOT_FOUND);  
         }
    }





}
