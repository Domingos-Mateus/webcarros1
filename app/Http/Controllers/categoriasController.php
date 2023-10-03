<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Categorias;

class categoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categorias = Categorias::all();


        return $categorias;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categorias = new Categorias;

        $categorias->nome = $request->nome;
        $categorias->sobrenome = $request->descricao;

        $categorias->save();

        return $categorias;
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
        $categorias = new Categorias;

        $categorias->nome = $request->nome;
        $categorias->descricao = $request->descricao;
/*
        $categorias = [
            'nome' => 'teste1',
            'descricao' => 'Testando',
        ];
    
        return new JsonResponse($categorias);
*/
        $categorias->save();

        return $categorias;
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
        $categorias = Categorias::find($id);
        if(!$categorias){
            return response(['message'=>'Categoria não encontrado'], 404);
        }
        return $categorias;
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
        $categorias = Categorias::find($id);
        if(!$categorias){
            return response(['message'=>'Categoria não encontrado'], 404);
        }
        $categorias->nome = $request->nome;
        $categorias->descricao = $request->descricao;

        $categorias->save();

        return $categorias;
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
        Categorias::destroy($id);
    }
}
