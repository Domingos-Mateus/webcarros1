<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cor;


class corController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $cor = Cor::orderBy('cor', 'asc')->get();
        return $cor;
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
        $cor = new Cor;

        $cor->cor = $request->cor;
        $cor->descricao = $request->descricao;

        $cor->save();

        return $cor;
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
        $cor = Cor::find($id);
        if(!$cor){
            return response(['message'=>'Tipo de veiculo não encontrado'], 404);
        }
        return $cor;
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
        $cor = Cor::find($id);
        if(!$cor){
            return response(['message'=>'Tipo de veiculo não encontrado'], 404);
        }
        $cor->cor = $request->cor;
        $cor->descricao = $request->descricao;

        $cor->save();

        return $cor;
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
        Cor::destroy($id);

    }
}
