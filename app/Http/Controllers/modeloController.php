<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Modelos;
use App\Models\Marcas;
use File;
use DB;

class modeloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$modelos = Modelos::all();
        $query = DB::table('modelos')
                ->join('marcas','marcas.id','modelos.marca_id')
                ->select('modelos.*', 'marcas.nome_marca');
          // Adiciona os filtros conforme os parâmetros passados
          if (request('nome_marca')) {
            $query->where('marcas.nome_marca', 'LIKE', '%' . request('nome_marca') . '%');
        }

        $modelos = $query->get();
        $dadosPersonalizados = [];

        foreach ($modelos as $modelo) {
            // Personalize os campos conforme necessário
            $dadosPersonalizados[] = [
                'id' => $modelo->id,
                'nome_modelo' => $modelo->nome_modelo,
                'nome_marca' => $modelo->nome_marca,
                'descricaoS' => $modelo->descricao,
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
        $modelos = new Modelos;
        $modelos->nome_modelo = $request->nome_modelo;
        $modelos->marca_id = $request->marca_id;
        $modelos->descricao = $request->descricao;

        $modelos->save();

        return "Marca cadastrada";
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
        $modelo = Modelos::find($id);
        if(!$modelo){
            return response(['message'=>'Modelo não encontrado'], 404);
        }
        
        return $modelo;
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
        $modelo = Modelos::find($id);
        $marcas = Marcas::find($request->marca_id);
        if(!$modelo){
            return response(['message'=>'Marca não encontrado'], 404);
        }

        if(!$marcas){
            return response(['message'=>'Marca não encontrada'], 404);
        }
        $modelo->nome_modelo = $request->nome_modelo;
        $modelo->marca_id = $request->marca_id;
        $modelo->descricao = $request->descricao;

        $modelo->save();
        return "Dados Atualizado";
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
        Modelos::destroy($id);
        return "Modelo Eliminado com sucesso!";
    }
}
