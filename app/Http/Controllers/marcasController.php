<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Marcas;
use App\Models\TiposVeiculos;
use File;
use DB;

class marcasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    // Começa a construir a consulta ao banco de dados
    $query = DB::table('marcas')
        ->join('tipos_veiculos', 'tipos_veiculos.id', '=', 'marcas.tipo_veiculo_id')
        ->select('marcas.*', 'tipos_veiculos.id as tipo_veiculo_id', 'tipos_veiculos.tipo_veiculo')
        ->orderBy('marcas.nome_marca', 'asc');

    // Adiciona os filtros conforme os parâmetros passados
    if ($request->has('nome_marca')) {
        $query->where('marcas.nome_marca', 'LIKE', '%' . $request->nome_marca . '%');
    }

    if ($request->has('tipo_veiculo_id')) {
        $query->where('marcas.tipo_veiculo_id', $request->tipo_veiculo_id);
    }

    if ($request->has('tipo_veiculo')) {
        $query->where('tipos_veiculos.tipo_veiculo', 'LIKE', '%' . $request->tipo_veiculo . '%');
    }

    // Executa a consulta
    $marcas = $query->get();

    // Processamento dos dados para personalizar a resposta
    $dadosPersonalizados = $marcas->map(function ($marca) {
        return [
            'id' => $marca->id,
            'tipo_veiculo_id' => $marca->tipo_veiculo_id,
            'tipo_veiculo' => $marca->tipo_veiculo,
            'nome_marca' => $marca->nome_marca,
            'descricao' => $marca->descricao ?? 'N/A', // Assume descrição opcional
        ];
    });

    // Retorna a resposta JSON com os dados personalizados
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
        $tipo_veiculo = TiposVeiculos::find($request->tipo_veiculo);
        if(!$tipo_veiculo){
            return response(['message'=> 'O tipo de veiculo selecionado não existe'], 404);
        }

        $marcas = new Marcas;
        $marcas->nome_marca = $request->nome_marca;
        $marcas->tipo_veiculo_id = $request->tipo_veiculo;
        $marcas->descricao = $request->descricao;

        $marcas->save();

        //return "Marca cadastrada";
        return response(['message'=> 'Marca cadastrada'], 200);
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
        $marca = Marcas::find($id);
        if(!$marca){
            return response(['message'=>'Marca não encontrada'], 404);
        }

        return $marca;
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
        $marca = Marcas::find($id);
        if(!$marca){
            return response(['message'=>'Marca não encontrado'], 404);
        }
        $marca->nome_marca = $request->nome_marca;
        $marca->tipo_veiculo_id = $request->tipo_veiculo;
        $marca->descricao = $request->descricao;

        $marca->save();
        return response(['message'=> 'Dados Atualizado'], 200);
        //return "Dados Atualizado";
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
        Marcas::destroy($id);
        return response(['message'=> 'Marca Eliminada com sucesso!'], 200);
    }
}
