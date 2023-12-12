<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Confortos;
use File;
use DB;

class confortoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $conforto = Confortos::all();
        return $conforto;
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
        $conforto = new Confortos;
        $conforto->conforto = $request->conforto;
        $conforto->descricao = $request->descricao;

        $conforto->save();

        return "conforto cadastrado com sucesso";
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
        $conforto = Confortos::find($id);
        if(!$conforto){
            return response(['message'=>'conforto não encontrada'], 404);
        }
        
        return $conforto;
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
        $conforto = Confortos::find($id);
        if(!$conforto){
            return response(['message'=>'conforto não encontrada'], 404);
        }
        
        $conforto->conforto = $request->conforto;
        $conforto->descricao = $request->descricao;

        $conforto->save();

        return "conforto Editado com sucesso";
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
        Confortos::destroy($id);
        return "Conforto Eliminado com sucesso!";
    }
}
