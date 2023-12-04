<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Regioes;
use File;
use DB;

class regiaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('regioes')
        ->join('estados','estados.id','regioes.estado_id')
        ->select('regioes.*', 'estados.estado','estados.id as id_estado');
  // Adiciona os filtros conforme os parâmetros passados
  if (request('estado')) {
    $query->where('marcas.estado', 'LIKE', '%' . request('estado') . '%');
}

$regiaos = $query->get();
$dadosPersonalizados = [];

foreach ($regiaos as $regiao) {
    // Personalize os campos conforme necessário
    $dadosPersonalizados[] = [
        'id' => $regiao->id,
        'estado' => $regiao->estado,
        'regiao' => $regiao->regiao,
        'id_estado' => $regiao->id_estado,
    ];
}
return response()->json($dadosPersonalizados);
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
        $regiao = new Regioes;

        $regiao->estado_id = $request->estado_id;
        $regiao->regiao = $request->regiao;
        
        $regiao->save();
        return "Regiao Cadastrada";
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
        $regiao = Regioes::find($id);
        if(!$regiao){
            return response(['message'=>'Região não encontrado!'], 404);
        }
        return $regiao;
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
        $regiao = Regioes::find($id);
        if(!$regiao){
            return response(['message'=>'Região não encontrado!'], 404);
        }
        $regiao->estado_id = $request->estado_id;
        $regiao->regiao = $request->regiao;
        
        $regiao->save();
        return "Região Actualizada";
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
        Regioes::destroy($id);
        return "Região Eliminada";
    }
}
