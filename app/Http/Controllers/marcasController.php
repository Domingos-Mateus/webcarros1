<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Marcas;
use File;
use DB;

class marcasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $marcas = Marcas::all();
        return $marcas;
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
        $marcas = new Marcas;
        $marcas->nome_marca = $request->nome_marca;
        $marcas->descricao = $request->descricao;

        $marcas->save();

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
        $marca->descricao = $request->descricao;

        $marca->save();
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
        Marcas::destroy($id);
        return "Marca Eliminada com sucesso!";
    }
}
