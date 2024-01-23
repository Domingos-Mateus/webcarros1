<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PlanosAnunciantes;
use App\Models\Anunciantes;
use App\Models\Planos;
use File;
use DB;

class planosAnuciantesController extends Controller
{

    public function index()
    {
        //
        $planosAnunciantes = PlanosAnunciantes::all();

        $planosAnunciantes = DB::table('planos_anunciantes')
                ->join('planos','planos.id','planos_anunciantes.plano_id')
                ->join('anunciantes','anunciantes.id','planos_anunciantes.anunciante_id')
                ->select('planos_anunciantes.*', 'planos.nome as nome_plano','planos.id as id_planos', 'anunciantes.nome_empresa','anunciantes.pessoal_responsavel','anunciantes.id as id_anunciante')
                ->get();
         $dadosPersonalizados = [];
        foreach ($planosAnunciantes as $planosAnunciante) {
            // Personalize os campos conforme necessário
            $dadosPersonalizados[] = [
                'id' => $planosAnunciante->id,
                'nome_plano' => $planosAnunciante->nome_plano,
                'id_planos' => $planosAnunciante->id_planos,
                'nome_plano' => $planosAnunciante->nome_plano,
                'nome_anunciantes' => $planosAnunciante->nome_empresa,
                'anunciante' => $planosAnunciante->pessoal_responsavel,
                'id_anunciantes' => $planosAnunciante->id_anunciante,
                'status' => $planosAnunciante->status,
                'created_at' => $planosAnunciante->created_at,
            ];
        }
        return response()->json($dadosPersonalizados);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
        $anunciante = Anunciantes::find($request->anunciante_id);
        $plano = Planos::find($request->plano_id);

        if(!$anunciante){
            return response(['message'=> 'O Anunciante selecionado não existe'], 404);
        }

        if(!$plano){
            return response(['message'=> 'O Plano selecionado não existe'], 404);
        }

        $planosAnunciantes = new PlanosAnunciantes;
        $planosAnunciantes->plano_id = $request->plano_id;
        $planosAnunciantes->anunciante_id = $request->anunciante_id;
        $planosAnunciantes->status = $request->status;

        $planosAnunciantes->save();

        return $planosAnunciantes;
    }

    public function show($id)
    {
        //
        $planosAnunciantes = PlanosAnunciantes::find($id);

        if(!$planosAnunciantes){
            return response(['message'=>'Plano de anunciantes não encontrado'], 404);
        }
        return $planosAnunciantes;
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
        $planosAnunciantes = PlanosAnunciantes::find($id);

        if(!$planosAnunciantes){
            return response(['message'=>'Plano de anunciantes não encontrado'], 404);
        }

        $planosAnunciantes->plano_id = $request->plano_id;
        $planosAnunciantes->anunciante_id = $request->anunciante_id;
        $planosAnunciantes->status = $request->status;
        $planosAnunciantes->save();

        return $planosAnunciantes;
    }

    public function destroy($id)
    {
        //
        PlanosAnunciantes::destroy($id);
        return "Anúncio eliminado com sucesso!";
    }
}
