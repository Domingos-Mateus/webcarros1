<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PlanosAnunciantes;
use App\Models\Anunciantes;
use App\Models\Planos;
use File;
use DB;
use Carbon\Carbon;

use App\Models\HistoricoAnunciantes;



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
                'limite_anuncio' => $planosAnunciante->limite_anuncio,
                'quantidade_anuncio' => $planosAnunciante->quantidade_anuncio,
                'anuncio_restante' => $planosAnunciante->anuncio_restante,
                'data_vencimento' => $planosAnunciante->data_vencimento,
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
    $anunciante = Anunciantes::find($request->anunciante_id);
    $plano = Planos::find($request->plano_id);

    if(!$anunciante){
        return response(['message'=> 'O Anunciante selecionado não existe'], 404);
    }

    if(!$plano){
        return response(['message'=> 'O Plano selecionado não existe'], 404);
    }

    $data_hoje = Carbon::now();
    $data_vecimento = $data_hoje->addDays($plano->dias_publicacao);

    //return $plano->quantidade_anuncios;

    $planosAnunciantes = new PlanosAnunciantes;
    $planosAnunciantes->plano_id = $request->plano_id;
    $planosAnunciantes->anunciante_id = $request->anunciante_id;
    $planosAnunciantes->status = $request->status;
    $planosAnunciantes->limite_anuncio = $plano->quantidade_anuncios;
    $planosAnunciantes->anuncio_restante = $planosAnunciantes->limite_anuncio;



    $planosAnunciantes->data_vencimento = $data_vecimento->format('Y-m-d');
    $planosAnunciantes->save();

    $historico = new HistoricoAnunciantes();
    $historico->plano_anunciante_id = $planosAnunciantes->id;
    $historico->save();

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
        $planosAnunciantes->data_vencimento = $request->data_vencimento;
        $planosAnunciantes->save();

        return $planosAnunciantes;
    }

    public function destroy($id)
    {
        $plano = PlanosAnunciantes::with('anuncios')->find($id);

        if (!$plano) {
            return response(['message' => 'Plano do Anunciante não encontrado.'], 404);
        }

        // Verifica se existem anúncios vinculados ao plano
        if ($plano->anuncios->isNotEmpty()) {
            return response(['message' => 'Não é possível eliminar o plano, pois ele está vinculado a um ou mais anúncios.'], 400);
        }

        $plano->delete();
        return response(['message' => 'Plano do Anunciante eliminado com sucesso!'], 200);
    }


}
