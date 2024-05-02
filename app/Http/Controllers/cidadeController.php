<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cidades;
use File;
use DB;

class cidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //
        $query = DB::table('cidades')
            ->join('regioes', 'regioes.id', 'cidades.regiao_id')
            ->join('estados', 'estados.id', 'regioes.estado_id')
            ->select('cidades.*', 'regioes.regiao',
            'regioes.id as id_regiao', 'regioes.regiao',
            'estados.id as id_estado', 'estados.estado');
        // Adiciona os filtros conforme os parâmetros passados
        if (request('regiao')) {
            $query->where('regioes.regiao', 'LIKE', '%' . request('regiao') . '%');
        }
        if (request('cidade')) {
            $query->where('cidades.cidade', 'LIKE', '%' . request('cidade') . '%');
        }
        if (request('estado')) {
            $query->where('estados.estado', 'LIKE', '%' . request('estado') . '%');
        }

        $cidades = $query->get();
        $dadosPersonalizados = [];

        foreach ($cidades as $cidade) {
            // Personalize os campos conforme necessário
            $dadosPersonalizados[] = [
                'id' => $cidade->id,
                'regiao' => $cidade->regiao,
                'cidade' => $cidade->cidade,
                'id_regiao' => $cidade->id_regiao,
                'nome_regiao' => $cidade->regiao,
                'id_estado' => $cidade->id_estado,
                'nome_estado' => $cidade->estado,
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
        $cidade = new Cidades;

        $cidade->regiao_id = $request->regiao_id;
        $cidade->cidade = $request->cidade;

        $cidade->save();
        return "Cidade Cadastrada";
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
        $cidade = Cidades::find($id);
        if (!$cidade) {
            return response(['message' => 'Cidades não encontrada!'], 404);
        }
        return $cidade;
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
        $cidade = Cidades::find($id);
        if (!$cidade) {
            return response(['message' => 'Cidades não encontrada!'], 404);
        }
        $cidade->regiao_id = $request->regiao_id;
        $cidade->cidade = $request->cidade;

        $cidade->save();
        return "Cidade Actualizada";
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
        Cidades::destroy($id);
        return "Cidade Eliminada";
    }
}
