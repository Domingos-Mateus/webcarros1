<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanosAnunciantes;

use DB;

class historicoAnunciantesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         //
         $query = DB::table('historico_anunciantes')
         ->join('planos_anunciantes', 'planos_anunciantes.id', '=', 'historico_anunciantes.plano_anunciante_id')
         ->select(
             'historico_anunciantes.*',
             'planos_anunciantes.plano_id',
             'planos_anunciantes.anunciante_id',
             'planos_anunciantes.status',
             'planos_anunciantes.created_at'

         );

     $historicos = $query->get();

     $dadosPersonalizados = [];

     foreach ($historicos as $historico) {
         $dadosPersonalizados[] = [
             'id' => $historico->id,
             'plano_anunciante_id' => $historico->plano_id,
             'anunciante_id' => $historico->anunciante_id,
             'status' => $historico->status,
             'created_at' => $historico->created_at,
         ];
     }

     return response()->json($dadosPersonalizados);
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
    }
}
