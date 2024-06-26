<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Estados;
use File;
use DB;

class estadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('estados')
       ->select('estados.*')
       ->orderBy('estados.estado', 'asc');

   // Adiciona os filtros conforme os parâmetros passados
   /*
   if (request('estado')) {
       $query->where('estados.estado', 'LIKE', '%' . request('estado') . '%');
   }*/

   $estados = $query->get();
   // Processamento dos dados para personalizar a resposta
   $dadosPersonalizados = [];

   foreach ($estados as $estado) {
       $dadosPersonalizados[] = [
           'id' => $estado->id,
           'uf' => $estado->uf,
           'estado' => $estado->estado,
       ];
    }
    return $dadosPersonalizados;
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $estados = new Estados;

        $estados->uf = $request->uf;
        $estados->estado = $request->estado;

        $estados->save();
        return response(['message'=> 'Estado cadastrado com sucesso!'], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $estados = Estados::find($id);
        if(!$estados){
            return response(['message'=>'Estado não encontrado'], 404);
        }
        return $estados;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $estados = Estados::find($id);
        if(!$estados){
            return response(['message'=>'Estado não encontrado'], 404);
        }
        $estados->uf = $request->uf;
        $estados->estado = $request->estado;

        $estados->save();
        return "Estado Actualizado";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Estados::destroy($id);
        return "Estado Eliminado";
    }
}
