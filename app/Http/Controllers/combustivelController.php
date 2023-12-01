<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Combustivel;

class combustivelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $combustivel = Combustivel::all();
        return $combustivel;
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
        $combustivel = new Combustivel;

        $combustivel->combustivel = $request->combustivel;
        $combustivel->descricao = $request->descricao;

        $combustivel->save();

        return $combustivel;
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
        $combustivel = Combustivel::find($id);
        if(!$combustivel){
            return response(['message'=>'combustivel não encontrado'], 404);
        }
        return $combustivel;
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
        $combustivel = Combustivel::find($id);
        if(!$combustivel){
            return response(['message'=>'combustivel não encontrado'], 404);
        }
        $combustivel->combustivel = $request->combustivel;
        $combustivel->descricao = $request->descricao;

        $combustivel->save();

        return $combustivel;
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
        Combustivel::destroy($id);
    }
}
