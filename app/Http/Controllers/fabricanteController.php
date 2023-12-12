<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Fabricantes;
use File;
use DB;

class fabricanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $fabricante = Fabricantes::all();
        return $fabricante;
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
        $fabricante = new Fabricantes;
        $fabricante->fabricante = $request->fabricante;
        $fabricante->descricao = $request->descricao;

        $fabricante->save();

        return "Fabricante cadastrado com sucesso";
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
        $fabricante = Fabricantes::find($id);
        if(!$fabricante){
            return response(['message'=>'fabricante não encontrada'], 404);
        }
        
        return $fabricante;
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
        $fabricante = Fabricantes::find($id);
        if(!$fabricante){
            return response(['message'=>'fabricante não encontrada'], 404);
        }
        
        $fabricante->fabricante = $request->fabricante;
        $fabricante->descricao = $request->descricao;

        $fabricante->save();

        return "Fabricante Actualizado com sucesso";
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
        Fabricantes::destroy($id);
        return "Fabricantes Eliminada com sucesso!";
    }
}
