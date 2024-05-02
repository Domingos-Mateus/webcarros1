<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TiposVeiculos;
use DB;

class tipoVeiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('tipos_veiculos')
       ->select('tipos_veiculos.*')
       ->orderBy('tipos_veiculos.tipo_veiculo', 'asc');

   // Adiciona os filtros conforme os parâmetros passados
   if (request('tipo_veiculo')) {
       $query->where('tipos_veiculos.tipo_veiculo', 'LIKE', '%' . request('tipo_veiculo') . '%');
   }

   // Executa a consulta aleatóriamente
   $tipos_veiculos = $query->get();
   //return $tipos_veiculos;
   // Processamento dos dados para personalizar a resposta
   $dadosPersonalizados = [];

   foreach ($tipos_veiculos as $tipo_veiculo) {
       $dadosPersonalizados[] = [
           'id' => $tipo_veiculo->id,
           'tipo_veiculo' => $tipo_veiculo->tipo_veiculo,
           'descricao' => $tipo_veiculo->descricao,
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
        $tipos_veiculos = new TiposVeiculos;

        $tipos_veiculos->tipo_veiculo = $request->tipo_veiculo;
        $tipos_veiculos->descricao = $request->descricao;

        $tipos_veiculos->save();

        return $tipos_veiculos;
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
        $tipos_veiculos = TiposVeiculos::find($id);
        if(!$tipos_veiculos){
            return response(['message'=>'Tipo de veiculo não encontrado'], 404);
        }
        return $tipos_veiculos;
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

        $tipos_veiculos = TiposVeiculos::find($id);
        if(!$tipos_veiculos){
            return response(['message'=>'Tipo de veiculo não encontrado'], 404);
        }
        $tipos_veiculos->tipo_veiculo = $request->tipo_veiculo;
        $tipos_veiculos->descricao = $request->descricao;

        $tipos_veiculos->save();

        return $tipos_veiculos;
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
        TiposVeiculos::destroy($id);
        return response(['message'=>'Anunciante Eliminado com sucesso'], 200);

    }
}
