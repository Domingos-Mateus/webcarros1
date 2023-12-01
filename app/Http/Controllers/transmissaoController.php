<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transmissao;

class transmissaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $transmissao = Transmissao::all();
        return $transmissao;
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
        $transmissao = new Transmissao;

        $transmissao->transmissao = $request->transmissao;
        $transmissao->descricao = $request->descricao;

        $transmissao->save();

        return $transmissao;
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
        $transmissao = Transmissao::find($id);
        if(!$transmissao){
            return response(['message'=>'transmissao não encontrado'], 404);
        }
        return $transmissao;
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
        $transmissao = Transmissao::find($id);
        if(!$transmissao){
            return response(['message'=>'transmissao não encontrado'], 404);
        }
        $transmissao->transmissao = $request->transmissao;
        $transmissao->descricao = $request->descricao;

        $transmissao->save();

        return $transmissao;
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
        Transmissao::destroy($id);
    }
}
