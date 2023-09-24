<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PlanosAnunciantes;
use File;
use DB;

class planosAnuciantesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $planosAnunciantes = PlanosAnunciantes::all();

        return $planosAnunciantes;
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
        $planosAnunciantes = new PlanosAnunciantes;
        $planosAnunciantes->plano_id = $request->plano_id;
        $planosAnunciantes->anunciante_id = $request->anunciante_id;

        $planosAnunciantes->save();

        return $planosAnunciantes;
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
        $planosAnunciantes = PlanosAnunciantes::find($id);
        return $planosAnunciantes;
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
        $planosAnunciantes = PlanosAnunciantes::find($id);
        
        $planosAnunciantes->plano_id = $request->plano_id;
        $planosAnunciantes->anunciante_id = $request->anunciante_id;

        $planosAnunciantes->save();

        return $planosAnunciantes;
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
        PlanosAnunciantes::destroy($id);
        return "Anúncio eliminado com sucesso!";
    }
}
