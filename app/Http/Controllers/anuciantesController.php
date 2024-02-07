<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Anunciantes;
use App\Models\Estados;
use App\Models\Regioes;
use App\Models\Cidades;

use File;
use DB;


class anuciantesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $anunciantes = Anunciantes::all();
 // Personalização dos campos da base de dados
 $query = DB::table('anunciantes')
    ->join('estados', 'estados.id', '=', 'anunciantes.estado_id')
    ->join('regioes', 'regioes.id', '=', 'anunciantes.regiao_id')
    ->join('cidades as cidade_principal', 'cidade_principal.id', '=', 'anunciantes.cidade_id')
    ->join('cidades as cidade_comercial', 'cidade_comercial.id', '=', 'anunciantes.cidade_comercial_id')
    ->select(
        'anunciantes.*',
        'estados.estado as estado',
        'estados.id as estado_id',
        'regioes.regiao as regiao',
        'regioes.id as regiao_id',
        'cidade_principal.cidade as cidade',
        'cidade_principal.id as cidade_id',
        'cidade_comercial.cidade as cidade_Comercial',
        'cidade_comercial.id as cidade_comercial_id'
    );

$anunciantes = $query->get();

$dadosPersonalizados = [];

foreach ($anunciantes as $anunciante) {
    $dadosPersonalizados[] = [
        'id' => $anunciante->id,
        'nome_empresa' => $anunciante->nome_empresa,
        'Pessoal_responsavel' => $anunciante->pessoal_responsavel,
        'tipo_anunciante' => $anunciante->tipo_anunciante,
        'cnpj' => $anunciante->cnpj,
        'telefone' => $anunciante->telefone,
        'celular' => $anunciante->celular,
        'whatsapp' => $anunciante->whatsapp,
        'site' => $anunciante->site,
        'email' => $anunciante->email,
        'cep' => $anunciante->cep,
        'endereco' => $anunciante->endereco,
        'numero' => $anunciante->numero,
        'complemento' => $anunciante->complemento,
        'bairro' => $anunciante->bairro,
        'cep_comercial' => $anunciante->cep_comercial,
        'endereco_comercial' => $anunciante->endereco_comercial,
        'numero_comercial' => $anunciante->numero_comercial,
        'complemento_comercial' => $anunciante->complemento_comercial,
        'bairro_comercial' => $anunciante->bairro_comercial,



        'regiao_id' => $anunciante->regiao_id,
        'regiao' => $anunciante->regiao,
        'estado_id' => $anunciante->estado_id,
        'estado' => $anunciante->estado,
        'cidade_id' => $anunciante->cidade_id,
        'cidade' => $anunciante->cidade,
        'cidade_comercial_id' => $anunciante->cidade_comercial_id,
        'cidade_comericial' => $anunciante->cidade_Comercial,
        'status' => $anunciante->status,
        'observacao' => $anunciante->observacao,
        'foto' => $anunciante->foto ? env('URL_BASE_SERVIDOR') . $anunciante->foto : null,
        // Adicione mais campos personalizados conforme necessário
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

        $estado = Estados::find($request->estado_id);
        $regiao = Regioes::find($request->regiao_id);
        $cidade = Cidades::find($request->cidade_id);


        if(!$estado){
            return response(['message'=> 'O Estado selecionado não existe'], 404);
        }
        if(!$regiao){
            return response(['message'=> 'A Regiao selecionada não existe'], 404);
        }
        if(!$cidade){
            return response(['message'=> 'A Cidade selecionada não existe'], 404);
        }


        $anunciantes = new Anunciantes;
        $anunciantes->nome_empresa = $request->nome_empresa;
        $anunciantes->pessoal_responsavel = $request->pessoal_responsavel;
        $anunciantes->tipo_anunciante = $request->tipo_anunciante;
        $anunciantes->cnpj = $request->cnpj;
        $anunciantes->telefone = $request->telefone;
        $anunciantes->celular = $request->celular;
        $anunciantes->whatsapp = $request->whatsapp;
        $anunciantes->email = $request->email;
        $anunciantes->status = $request->status;
        $anunciantes->site = $request->site;
        $anunciantes->cep = $request->cep;
        $anunciantes->endereco = $request->endereco;
        $anunciantes->numero = $request->numero;
        $anunciantes->complemento = $request->complemento;
        $anunciantes->bairro = $request->bairro;
        $anunciantes->endereco_comercial = $request->endereco_comercial;
        $anunciantes->numero_comercial = $request->numero_comercial;
        $anunciantes->complemento_comercial = $request->complemento_comercial;
        $anunciantes->bairro_comercial = $request->bairro_comercial;
        $anunciantes->cep_comercial = $request->cep_comercial;
        $anunciantes->estado_id = $request->estado_id;
        $anunciantes->cidade_id = $request->cidade_id;
        $anunciantes->cidade_comercial_id = $request->cidade_comercial_id;
        $anunciantes->regiao_id = $request->regiao_id;
        $anunciantes->observacao = $request->observacao;

        $anunciantes->save();


        return $anunciantes;
    }

    public function uploadFoto(Request $request, $id)
    {
        //
        $anunciantes = Anunciantes::find($id);
        if(!$anunciantes){
            return response(['message'=>'Anunciante não encontrado'], 404);
        }

        if($request->hasfile('foto'))
        {
            $file = $request->file('foto');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/anunciantes/imagens/', $filename);
            $anunciantes->foto = 'uploads/anunciantes/imagens/'.$filename;
            $anunciantes->save();
        }
        $anunciantes->save();
        return $anunciantes;
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
        $anunciante = Anunciantes::find($id);
        $dadosPersonalizados = [];
        if(!$anunciante){
            return response(['message'=>'Anunciante não encontrado'], 404);
        }


        $estado = Estados::find($anunciante->estado_id);
        $regiao = Regioes::find($anunciante->regiao_id);
        $cidade = Cidades::find($anunciante->cidade_id);
            // Personalize os campos conforme necessário
            $dadosPersonalizados[] = [
                'id' => $anunciante->id,
                'nome_empresa' => $anunciante->nome_empresa,
                'pessoal_anunciante' => $anunciante->pessoal_anunciante,
                'tipo_anunciante' => $anunciante->tipo_anunciante,
                'cnpj' => $anunciante->cnpj,
                'telefone' => $anunciante->telefone,
                'celular' => $anunciante->celular,
                'whatsapp' => $anunciante->whatsapp,
                'email' => $anunciante->email,
                'site' => $anunciante->site,
                'cep' => $anunciante->cep,
                'endereco' => $anunciante->endereco,
                'numero' => $anunciante->numero,
                'complemento' => $anunciante->complemento,
                'bairro' => $anunciante->bairro,
                'cep_comercial' => $anunciante->cep_comercial,
                'endereco_comercial' => $anunciante->endereco_comercial,
                'numero_comercial' => $anunciante->numero_comercial,
                'complemento_comercial' => $anunciante->complemento_comercial,
                'bairro_comercial' => $anunciante->bairro_comercial,
                'regiao' => $regiao->regiao,
                'estado' => $estado->estado,
                'cidade' => $cidade->cidade,
                'cidade' => $cidade->cidade,
                'status' => $anunciante->status,
                'observacao' => $anunciante->observacao,
                'foto' => $anunciante->foto ? env('URL_BASE_SERVIDOR') . $anunciante->foto : null,
                // Adicione mais campos personalizados conforme necessário
            ];
            return response()->json($dadosPersonalizados);
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
        $anunciantes = Anunciantes::find($id);
        if(!$anunciantes){
            return response(['message'=>'Anunciante não encontrado'], 404);
        }
        $anunciantes->nome_empresa = $request->nome_empresa;
        $anunciantes->pessoal_responsavel = $request->pessoal_responsavel;
        $anunciantes->tipo_anunciante = $request->tipo_anunciante;
        $anunciantes->cnpj = $request->cnpj;
        $anunciantes->telefone = $request->telefone;
        $anunciantes->celular = $request->celular;
        $anunciantes->whatsapp = $request->whatsapp;
        $anunciantes->email = $request->email;
        $anunciantes->status = $request->status;
        $anunciantes->site = $request->site;
        $anunciantes->cep = $request->cep;
        $anunciantes->endereco = $request->endereco;
        $anunciantes->numero = $request->numero;
        $anunciantes->complemento = $request->complemento;
        $anunciantes->bairro = $request->bairro;
        $anunciantes->endereco_comercial = $request->endereco_comercial;
        $anunciantes->numero_comercial = $request->numero_comercial;
        $anunciantes->complemento_comercial = $request->complemento_comercial;
        $anunciantes->bairro_comercial = $request->bairro_comercial;
        $anunciantes->cep_comercial = $request->cep_comercial;
        $anunciantes->estado_id = $request->estado_id;
        $anunciantes->cidade_id = $request->cidade_id;
        $anunciantes->cidade_comercial_id = $request->cidade_comercial_id;
        $anunciantes->regiao_id = $request->regiao_id;
        $anunciantes->observacao = $request->observacao;

        $anunciantes->save();
        return $anunciantes;
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
        Anunciantes::destroy($id);
        return response(['message'=>'Anunciante Eliminado com sucesso'], 200);
    }

    public function destroyFoto($id)
{
    $anunciantes = Anunciantes::find($id);

    if (!$anunciantes) {
        return response()->json(['message' => 'Anunciantes não encontrado'], 404);
    }

    // Para achar o caminho da foto
    $pathFoto = public_path() .  $anunciantes->foto;
    // Verificar se o arquivo existe
    if (File::exists($pathFoto)) {
        // Deletar o arquivo
        File::delete($pathFoto);

        // Atualizar ou zerar a coluna da foto no banco de dados
        $anunciantes->foto = '';
        $anunciantes->save();

        return response()->json(['message' => 'Foto eliminada com sucesso'], 200);

    }

    else {
        return response()->json(['message' => 'Foto não encontrada'], 404);
    }


}


}
