<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Planos;
use File;
use DB;

class planosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $planos = Planos::all();

        return $planos;
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
        $planos = new Planos;
        $planos->nome = $request->nome;
        $planos->activo = $request->activo;
        $planos->quantidade_anuncios = $request->quantidade_anuncios;
        $planos->valor = $request->valor;
        $planos->tipo = $request->tipo;
        $planos->anuncio_destaque = $request->anuncio_destaque;
        $planos->quantidade_anuncio_destaque = $request->quantidade_anuncio_destaque;
        $planos->quantidade_anuncio_vitrine = $request->quantidade_anuncio_vitrine;
        $planos->dias_publicacao = $request->dias_publicacao;
        $planos->texto_plano = $request->texto_plano;
        $planos->quantidade_fotos = $request->quantidade_fotos;
        $planos->link_pagamento = $request->link_pagamento;
       

        $planos->save();
        return $planos;
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
        $planos = Planos::find($id);
        return $planos;
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
        $planos = Planos::find($id);
        $planos->nome = $request->nome;
        $planos->activo = $request->activo;
        $planos->quantidade_anuncios = $request->quantidade_anuncios;
        $planos->valor = $request->valor;
        $planos->tipo = $request->tipo;
        $planos->anuncio_destaque = $request->anuncio_destaque;
        $planos->quantidade_anuncio_destaque = $request->quantidade_anuncio_destaque;
        $planos->quantidade_anuncio_vitrine = $request->quantidade_anuncio_vitrine;
        $planos->dias_publicacao = $request->dias_publicacao;
        $planos->texto_plano = $request->texto_plano;
        $planos->quantidade_fotos = $request->quantidade_fotos;
        $planos->link_pagamento = $request->link_pagamento;
       
        $planos->save();
        return $planos;
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
        Planos::destroy($id);
        return "Plano eliminado com sucesso!";
    }
}
