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
  
        $planosAnunciantes = DB::table('planos_anunciantes')
                ->join('planos','planos.id','planos_anunciantes.plano_id')
                ->join('anunciantes','anunciantes.id','planos_anunciantes.anunciante_id')
                ->select('planos_anunciantes.*', 'planos.nome as nome_plano', 'anunciantes.nome as nome_anunciantes')
                ->get();
         $dadosPersonalizados = [];

        foreach ($planosAnunciantes as $planosAnunciante) {
            // Personalize os campos conforme necessário
            $dadosPersonalizados[] = [
                'id' => $planosAnunciante->id,
                'nome_plano' => $planosAnunciante->nome_plano,
                'nome_anunciantes' => $planosAnunciante->nome_anunciantes,
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

        if(!$planosAnunciantes){
            return response(['message'=>'Plano de anunciantes não encontrado'], 404);
        }
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

        if(!$planosAnunciantes){
            return response(['message'=>'Plano de anunciantes não encontrado'], 404);
        }
        
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
