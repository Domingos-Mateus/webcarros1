<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CategoriaOpcionais;

class categoriaOpcionaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$catgoriasOpcionais = CategoriaOpcionais::all();
        $catgoriasOpcionais = CategoriaOpcionais::orderBy('nome', 'asc')->get();
        return $catgoriasOpcionais;
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
        $catgoriasOpcionais = new CategoriaOpcionais;

        $catgoriasOpcionais->nome = $request->nome;
        $catgoriasOpcionais->save();

        return $catgoriasOpcionais;
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
        $catgoriasOpcionais = CategoriaOpcionais::find($id);
        if(!$catgoriasOpcionais){
            return response(['message'=>'Categoria Opcionais não encontrada'], 404);
        }
        return $catgoriasOpcionais;
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
        $catgoriasOpcionais = CategoriaOpcionais::find($id);
        if(!$catgoriasOpcionais){
            return response(['message'=>'Categoria Opcionais não encontrada'], 404);
        }
        $catgoriasOpcionais->nome = $request->nome;
        $catgoriasOpcionais->save();

        return $catgoriasOpcionais;
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
        CategoriaOpcionais::destroy($id);
        return response(['message'=>'Categoria Opcionais Eliminado com sucesso'], 200);
    }
}
