<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Segurancas;
use File;
use DB;

class segurancaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $seguranca = Segurancas::all();
        return $seguranca;
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
        $seguranca = new Segurancas;
        $seguranca->seguranca = $request->seguranca;
        $seguranca->descricao = $request->descricao;

        $seguranca->save();

        return "seguranca cadastrado com sucesso";
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
        $seguranca = Segurancas::find($id);
        if(!$seguranca){
            return response(['message'=>'seguranca não encontrada'], 404);
        }
        
        return $seguranca;
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
        $seguranca = Segurancas::find($id);
        if(!$seguranca){
            return response(['message'=>'seguranca não encontrada'], 404);
        }
        
        
        $seguranca->seguranca = $request->seguranca;
        $seguranca->descricao = $request->descricao;

        $seguranca->save();

        return "segurança Actualizado com sucesso";
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
        Segurancas::destroy($id);
        return "Segurança Eliminada com sucesso!";
    }
}
