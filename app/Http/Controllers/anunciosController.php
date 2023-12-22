<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Anuncios;
use App\Models\Marcas;
use App\Models\Modelos;
use App\Models\Anunciantes;
use File;
use DB;

class anunciosController extends Controller
{

    public function index()
    {
        // Começa a construir a consulta ao banco de dados
        $query = DB::table('anuncios')
            ->join('marcas','marcas.id','anuncios.marca_id')
            ->join('modelos','modelos.id','anuncios.modelo_id')
            ->join('categorias','categorias.id','anuncios.categoria_id')
            ->join('anunciantes','anunciantes.id','anuncios.anunciante_id')
            ->select('anuncios.*', 'marcas.nome_marca', 'marcas.id as id_marca', 'modelos.nome_modelo', 'modelos.id as id_modelo','categorias.nome as nome_categoria', 'categorias.id as id_categoria', 'anunciantes.nome as nome_anunciante', 'anunciantes.id as id_anunciante');

        // Adiciona os filtros conforme os parâmetros passados
        if (request('nome_marca')) {
            $query->where('marcas.nome_marca', 'LIKE', '%' . request('nome_marca') . '%');
        }

        if (request('nome_modelo')) {
            $query->where('modelos.nome_modelo', 'LIKE', '%' . request('nome_modelo') . '%');
        }

        if (request('nome_anunciante')) {
            $query->where('anunciantes.nome', 'LIKE', '%' . request('nome_anunciante') . '%');
        }

        if (request('estado_id')) {
            $query->where('anuncios.estado_id', request('estado_id'));
        }
        if (request('tipo_veiculo_id')) {
            $query->where('anuncios.tipo_veiculo_id', request('tipo_veiculo_id'));
        }
        if (request('tecnologia_id')) {
            $query->where('anuncios.tecnologia_id', request('tecnologia_id'));
        }
        if (request('cor_id')) {
            $query->where('anuncios.cor_id', request('cor_id'));
        }
        if (request('transmissao_id')) {
            $query->where('anuncios.transmissao_id', request('transmissao_id'));
        }
        if (request('combustivel_id')) {
            $query->where('anuncios.combustivel_id', request('combustivel_id'));
        }

        // Executa a consulta
        $anuncios = $query->get();

        // Processamento dos dados para personalizar a resposta
        $dadosPersonalizados = [];

        foreach ($anuncios as $anuncio) {
            $dadosPersonalizados[] = [
                'id' => $anuncio->id,
                'tipo_veiculo_id' => $anuncio->tipo_veiculo_id,
                'tecnologia_id' => $anuncio->tecnologia_id,
                'nome_marca' => $anuncio->nome_marca,
                'id_marca' => $anuncio->id_marca,
                'nome_modelo' => $anuncio->nome_modelo,
                'id_modelo' => $anuncio->id_modelo,
                'numero_cliques' => $anuncio->numero_cliques,
                'situacao_veiculo' => $anuncio->situacao_veiculo,
                'nome_anunciante' => $anuncio->nome_anunciante,
                'id_anunciante' => $anuncio->id_anunciante,
                'nome_categoria' => $anuncio->nome_categoria,
                'id_categoria' => $anuncio->id_categoria,
                'data_inicio' => $anuncio->data_inicio,
                'data_fim' => $anuncio->data_fim,
                'ordenacao' => $anuncio->ordenacao,
                'status_publicacao' => $anuncio->status_publicacao,
                'status_pagamento' => $anuncio->status_pagamento,
                'tipo' => $anuncio->tipo,
                'vendido' => $anuncio->vendido,
                'vitrine' => $anuncio->vitrine,
                'destaque_busca' => $anuncio->destaque_busca,
                'cep' => $anuncio->cep,
                'estado_id' => $anuncio->estado_id,
                'cidade_id' => $anuncio->cidade_id,
                'empresa' => $anuncio->empresa,
                'tipo_preco' => $anuncio->tipo_preco,
                'valor_preco' => $anuncio->valor_preco,
                'fabricante_id' => $anuncio->fabricante_id,
                'ano_fabricacao' => $anuncio->ano_fabricacao,
                'ano_modelo' => $anuncio->ano_modelo,
                'carroceria' => $anuncio->carroceria,
                'estilo' => $anuncio->estilo,
                'portas' => $anuncio->portas,
                'cilindros' => $anuncio->cilindros,
                'motor' => $anuncio->motor,
                'cor' => $anuncio->cor_id,
                'transmissao' => $anuncio->transmissao_id,
                'combustivel' => $anuncio->combustivel_id,
                'placa' => $anuncio->placa,
                'km' => $anuncio->km,
                'sinistrado' => $anuncio->sinistrado,
                'conforto_id' => $anuncio->conforto_id,
                'seguranca_id' => $anuncio->seguranca_id,
                'descricao' => $anuncio->descricao,
                'foto1' => $anuncio->foto1 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto1 : null,
                'foto2' => $anuncio->foto2 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto2 : null,
                'foto3' => $anuncio->foto3 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto3 : null,
                'foto4' => $anuncio->foto4 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto4 : null,
                'foto5' => $anuncio->foto5 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto5 : null,
                'foto6' => $anuncio->foto6 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto6 : null,
                'foto7' => $anuncio->foto7 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto7 : null,
                'foto8' => $anuncio->foto8 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto8 : null,
                'foto9' => $anuncio->foto9 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto9 : null,
                'foto10' => $anuncio->foto10 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto10 : null,
                // Adicione mais campos personalizados conforme necessário
            ];
        }



        // Retorna a resposta JSON com os dados personalizados
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
        $anuncios = new Anuncios;
        $anuncios->tipo_veiculo_id = $request->tipo_veiculo;
        $anuncios->tecnologia_id = $request->tecnologia;
        $anuncios->marca_id = $request->marca_id;
        $anuncios->modelo_id = $request->modelo_id;
        $anuncios->numero_cliques = $request->numero_cliques;
        $anuncios->situacao_veiculo = $request->situacao_veiculo;
        $anuncios->anunciante_id = $request->anunciante_id;
        $anuncios->categoria_id = $request->categoria_id;
        $anuncios->data_inicio = $request->data_inicio;
        $anuncios->data_fim = $request->data_fim;
        $anuncios->ordenacao = $request->ordenacao;
        $anuncios->status_publicacao = $request->status_publicacao;
        $anuncios->status_pagamento = $request->status_pagamento;
        $anuncios->tipo = $request->tipo;
        $anuncios->vendido = $request->vendido;
        $anuncios->vitrine = $request->vitrine;
        $anuncios->destaque_busca = $request->destaque_busca;
        $anuncios->cep = $request->cep;
        $anuncios->estado_id = $request->estado_id;
        $anuncios->cidade_id = $request->cidade_id;
        $anuncios->empresa = $request->empresa;
        $anuncios->tipo_preco = $request->tipo_preco;
        $anuncios->valor_preco = $request->valor_preco;
        $anuncios->fabricante_id = $request->fabricante_id;
        $anuncios->ano_fabricacao = $request->ano_fabricacao;
        $anuncios->ano_modelo = $request->ano_modelo;
        $anuncios->carroceria = $request->carroceria;
        $anuncios->estilo = $request->estilo;
        $anuncios->portas = $request->portas;
        $anuncios->cilindros = $request->cilindros;
        $anuncios->motor = $request->motor;
        $anuncios->cor_id = $request->cor;
        $anuncios->transmissao_id = $request->transmissao;
        $anuncios->combustivel_id = $request->combustivel;
        $anuncios->placa = $request->placa;
        $anuncios->km = $request->km;
        $anuncios->conforto_id = $request->conforto_id;
        $anuncios->seguranca_id = $request->seguranca_id;
        $anuncios->sinistrado = $request->sinistrado;
        $anuncios->descricao = $request->descricao;

        $anuncios->save();
        return $anuncios;
    }

    public function uploadFoto(Request $request, $id)
    {
        //
        $anuncios = Anuncios::find($id);

        // $anuncios->foto1 = $anuncios->foto1;
        // $anuncios->foto2 = $anuncios->foto2;
        // $anuncios->foto3 = $anuncios->foto3;
        // $anuncios->foto4 = $anuncios->foto4;
        // $anuncios->foto5 = $anuncios->foto5;
        // $anuncios->foto6 = $anuncios->foto6;
        // $anuncios->foto7 = $anuncios->foto7;
        // $anuncios->foto8 = $anuncios->foto8;
        // $anuncios->foto9 = $anuncios->foto9;
        // $anuncios->foto10 = $anuncios->foto10;


        if($request->hasfile('foto1'))
        {
            $file = $request->file('foto1');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/anuncios/imagens/', $filename);
            $anuncios->foto1 = 'uploads/anuncios/imagens/'.$filename;
            $anuncios->save();
        }

        // if($request->hasfile('imagem'))
        // {
        //     $file = $request->file('imagem');
        //     $extenstion = $file->getClientOriginalExtension();
        //     $filename = time().'.'.$extenstion;
        //     $file->move('uploads/modelos/', $filename);
        //     $modelos->imagem = 'uploads/modelos/'.$filename;
        //     $modelos->save();
        // }

         //Foto1
        // if($request->foto1){
        //     $foto1 = $request->foto1;
        //     $extensaoimg = $foto1->getClientOriginalExtension();
        //     if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
        //         return back()->with('Erro', 'imagem com formato inválido');
        //     }
        // }
        // $anuncios->save();

        // if ($request->foto1) {
        //     File::move($foto1, public_path().'/imagens_anuncios/imagem1/imagens'.$anuncios->id.'.'.$extensaoimg);
        //     $anuncios->foto1 = '/imagens_anuncios/imagem1/imagens'.$anuncios->id.'.'.$extensaoimg;
        //     $anuncios->save();
        // }

        if($request->hasfile('foto2'))
        {
            $file = $request->file('foto2');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/anuncios/imagens/', $filename);
            $anuncios->foto2 = 'uploads/anuncios/imagens/'.$filename;
            $anuncios->save();
        }

        if($request->hasfile('foto3'))
        {
            $file = $request->file('foto3');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/anuncios/imagens/', $filename);
            $anuncios->foto3 = 'uploads/anuncios/imagens/'.$filename;
            $anuncios->save();
        }

        if($request->hasfile('foto4'))
        {
            $file = $request->file('foto4');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/anuncios/imagens/', $filename);
            $anuncios->foto4 = 'uploads/anuncios/imagens/'.$filename;
            $anuncios->save();
        }

        if($request->hasfile('foto5'))
        {
            $file = $request->file('foto5');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/anuncios/imagens/', $filename);
            $anuncios->foto5 = 'uploads/anuncios/imagens/'.$filename;
            $anuncios->save();
        }


        if($request->hasfile('foto6'))
        {
            $file = $request->file('foto6');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/anuncios/imagens/', $filename);
            $anuncios->foto6 = 'uploads/anuncios/imagens/'.$filename;
            $anuncios->save();
        }


        if($request->hasfile('foto7'))
        {
            $file = $request->file('foto7');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/anuncios/imagens/', $filename);
            $anuncios->foto7 = 'uploads/anuncios/imagens/'.$filename;
            $anuncios->save();
        }

        if($request->hasfile('foto8'))
        {
            $file = $request->file('foto8');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/anuncios/imagens/', $filename);
            $anuncios->foto8 = 'uploads/anuncios/imagens/'.$filename;
            $anuncios->save();
        }


        if($request->hasfile('foto9'))
        {
            $file = $request->file('foto9');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/anuncios/imagens/', $filename);
            $anuncios->foto9 = 'uploads/anuncios/imagens/'.$filename;
            $anuncios->save();
        }

        if($request->hasfile('foto10'))
        {
            $file = $request->file('foto10');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/anuncios/imagens/', $filename);
            $anuncios->foto10 = 'uploads/anuncios/imagens/'.$filename;
            $anuncios->save();
        }


        $anuncios->save();
        return $anuncios;


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
        $anuncio = Anuncios::find($id);
        $dadosPersonalizados = [];
        if(!$anuncio){
            return response(['message'=>'Anúncio não encontrado'], 404);
        }

        //Para incrementar o número de cliques
        $anuncio->increment('numero_cliques');

        // Personalização dos campos da base de dados


            // Personalize os campos conforme necessário
            $dadosPersonalizados[] = [
                'id' => $anuncio->id,
                'tipo_veiculo' => $anuncio->tipo_veiculo,
                'marca' => $anuncio->marca_id,
                'modelo' => $anuncio->modelo_id,
                'numero_cliques' => $anuncio->numero_cliques,
                'situacao_veiculo' => $anuncio->situacao_veiculo,
                'anunciantes_id' => $anuncio->anunciantes_id,
                'categoria_id' => $anuncio->categoria_id,
                'data_inicio' => $anuncio->data_inicio,
                'data_fim' => $anuncio->data_fim,
                'ordenacao' => $anuncio->ordenacao,
                'status_publicacao' => $anuncio->status_publicacao,
                'status_pagamento' => $anuncio->status_pagamento,
                'tipo' => $anuncio->tipo,
                'vendido' => $anuncio->vendido,
                'vitrine' => $anuncio->vitrine,
                'destaque_busca' => $anuncio->destaque_busca,
                'estado_id' => $anuncio->estado_id,
                'cidade_id' => $anuncio->cidade_id,
                'empresa' => $anuncio->empresa,
                'tipo_preco' => $anuncio->tipo_preco,
                'valor_preco' => $anuncio->valor_preco,
                'fabricante_id' => $anuncio->fabricante_id,
                'ano_fabricacao' => $anuncio->ano_fabricacao,
                'ano_modelo' => $anuncio->ano_modelo,
                'carroceria' => $anuncio->carroceria,
                'estilo' => $anuncio->estilo,
                'portas' => $anuncio->portas,
                'cilindros' => $anuncio->cilindros,
                'motor' => $anuncio->motor,
                'cor' => $anuncio->cor,
                'transmissao' => $anuncio->transmissao,
                'combustivel' => $anuncio->combustivel,
                'placa' => $anuncio->placa,
                'km' => $anuncio->km,
                'sinistrado' => $anuncio->sinistrado,
                'conforto_id' => $anuncio->conforto_id,
                'seguranca_id' => $anuncio->seguranca_id,
                'som' => $anuncio->som,
                'descricao' => $anuncio->descricao,
                'foto1' => $anuncio->foto1 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto1 : null,
                'foto2' => $anuncio->foto2 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto2 : null,
                'foto3' => $anuncio->foto3 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto3 : null,
                'foto4' => $anuncio->foto4 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto4 : null,
                'foto5' => $anuncio->foto5 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto5 : null,
                'foto6' => $anuncio->foto6 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto6 : null,
                'foto7' => $anuncio->foto7 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto7 : null,
                'foto8' => $anuncio->foto8 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto8 : null,
                'foto9' => $anuncio->foto9 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto9 : null,
                'foto10' => $anuncio->foto10 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto10 : null,
                // Adicione mais campos personalizados conforme necessário
            ];

        // Retorna a resposta JSON com os dados personalizados
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
        $anuncios = Anuncios::find($id);
        $marcas = Marcas::find($request->marca_id);
        $modelos = Modelos::find($request->modelo_id);
        $anunciantes = Anunciantes::find($request->anunciante_id);
        if(!$anuncios){
            return response(['message'=>'Anúncio não encontrado'], 404);
        }

        if(!$marcas){
            return response(['message'=>'Marca não encontrada'], 404);
        }

        if(!$modelos){
            return response(['message'=>'Modelo não encontrado'], 404);
        }

        if(!$anunciantes){
            return response(['message'=>'Anunciante não encontrado'], 404);
        }

        $anuncios->tipo_veiculo_id = $request->tipo_veiculo;
        $anuncios->tecnologia_id = $request->tecnologia;
        $anuncios->marca_id = $request->marca_id;
        $anuncios->modelo_id = $request->modelo_id;
        $anuncios->numero_cliques = $request->numero_cliques;
        $anuncios->situacao_veiculo = $request->situacao_veiculo;
        $anuncios->anunciante_id = $request->anunciante_id;
        $anuncios->categoria_id = $request->categoria_id;
        $anuncios->data_inicio = $request->data_inicio;
        $anuncios->data_fim = $request->data_fim;
        $anuncios->ordenacao = $request->ordenacao;
        $anuncios->status_publicacao = $request->status_publicacao;
        $anuncios->status_pagamento = $request->status_pagamento;
        $anuncios->tipo = $request->tipo;
        $anuncios->vendido = $request->vendido;
        $anuncios->vitrine = $request->vitrine;
        $anuncios->destaque_busca = $request->destaque_busca;
        $anuncios->cep = $request->cep;
        $anuncios->estado_id = $request->estado_id;
        $anuncios->cidade_id = $request->cidade_id;
        $anuncios->empresa = $request->empresa;
        $anuncios->tipo_preco = $request->tipo_preco;
        $anuncios->valor_preco = $request->valor_preco;
        $anuncios->fabricante_id = $request->fabricante_id;
        $anuncios->ano_fabricacao = $request->ano_fabricacao;
        $anuncios->ano_modelo = $request->ano_modelo;
        $anuncios->carroceria = $request->carroceria;
        $anuncios->estilo = $request->estilo;
        $anuncios->portas = $request->portas;
        $anuncios->cilindros = $request->cilindros;
        $anuncios->motor = $request->motor;
        $anuncios->cor_id = $request->cor;
        $anuncios->transmissao_id = $request->transmissao;
        $anuncios->combustivel_id = $request->combustivel;
        $anuncios->placa = $request->placa;
        $anuncios->km = $request->km;
        $anuncios->conforto_id = $request->conforto_id;
        $anuncios->seguranca_id = $request->seguranca_id;
        $anuncios->sinistrado = $request->sinistrado;
        $anuncios->descricao = $request->descricao;
        $anuncios->save();
        return $anuncios;
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
        Anuncios::destroy($id);
        return "Anúncio eliminado com sucesso!";
    }



    public function destroyFoto1($id)
{
    $anuncios = Anuncios::find($id);

    if (!$anuncios) {
        return response()->json(['message' => 'Anunciantes não encontrado'], 404);
    }

    $pathFoto = public_path() .  $anuncios->foto1;
    if (File::exists($pathFoto)) {
        File::delete($pathFoto);
        $anuncios->foto1 = '';
        $anuncios->save();
        return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
    }

    else {
        return response()->json(['message' => 'Foto não encontrada'], 404);
    }
}

public function destroyFoto2($id)
{
    $anuncios = Anuncios::find($id);

    if (!$anuncios) {
        return response()->json(['message' => 'Anunciantes não encontrado'], 404);
    }

    $pathFoto = public_path() .  $anuncios->foto2;
    if (File::exists($pathFoto)) {
        File::delete($pathFoto);
        $anuncios->foto2 = '';
        $anuncios->save();
        return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
    }

    else {
        return response()->json(['message' => 'Foto não encontrada'], 404);
    }
}

public function destroyFoto3($id)
{
    $anuncios = Anuncios::find($id);

    if (!$anuncios) {
        return response()->json(['message' => 'Anunciantes não encontrado'], 404);
    }

    $pathFoto = public_path() .  $anuncios->foto1;
    if (File::exists($pathFoto)) {
        File::delete($pathFoto);
        $anuncios->foto3 = '';
        $anuncios->save();
        return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
    }

    else {
        return response()->json(['message' => 'Foto não encontrada'], 404);
    }
}

public function destroyFoto4($id)
{
    $anuncios = Anuncios::find($id);

    if (!$anuncios) {
        return response()->json(['message' => 'Anunciantes não encontrado'], 404);
    }

    $pathFoto = public_path() .  $anuncios->foto1;
    if (File::exists($pathFoto)) {
        File::delete($pathFoto);
        $anuncios->foto4 = '';
        $anuncios->save();
        return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
    }

    else {
        return response()->json(['message' => 'Foto não encontrada'], 404);
    }
}

public function destroyFoto5($id)
{
    $anuncios = Anuncios::find($id);

    if (!$anuncios) {
        return response()->json(['message' => 'Anunciantes não encontrado'], 404);
    }

    $pathFoto = public_path() .  $anuncios->foto5;
    if (File::exists($pathFoto)) {
        File::delete($pathFoto);
        $anuncios->foto5 = '';
        $anuncios->save();
        return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
    }

    else {
        return response()->json(['message' => 'Foto não encontrada'], 404);
    }
}

public function destroyFoto6($id)
{
    $anuncios = Anuncios::find($id);

    if (!$anuncios) {
        return response()->json(['message' => 'Anunciantes não encontrado'], 404);
    }

    $pathFoto = public_path() .  $anuncios->foto6;
    if (File::exists($pathFoto)) {
        File::delete($pathFoto);
        $anuncios->foto6 = '';
        $anuncios->save();
        return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
    }

    else {
        return response()->json(['message' => 'Foto não encontrada'], 404);
    }
}

public function destroyFoto7($id)
{
    $anuncios = Anuncios::find($id);

    if (!$anuncios) {
        return response()->json(['message' => 'Anunciantes não encontrado'], 404);
    }

    $pathFoto = public_path() .  $anuncios->foto7;
    if (File::exists($pathFoto)) {
        File::delete($pathFoto);
        $anuncios->foto7 = '';
        $anuncios->save();
        return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
    }

    else {
        return response()->json(['message' => 'Foto não encontrada'], 404);
    }
}


public function destroyFoto8($id)
{
    $anuncios = Anuncios::find($id);

    if (!$anuncios) {
        return response()->json(['message' => 'Anunciantes não encontrado'], 404);
    }

    $pathFoto = public_path() .  $anuncios->foto8;
    if (File::exists($pathFoto)) {
        File::delete($pathFoto);
        $anuncios->foto8 = '';
        $anuncios->save();
        return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
    }

    else {
        return response()->json(['message' => 'Foto não encontrada'], 404);
    }
}


public function destroyFoto9($id)
{
    $anuncios = Anuncios::find($id);

    if (!$anuncios) {
        return response()->json(['message' => 'Anunciantes não encontrado'], 404);
    }

    $pathFoto = public_path() .  $anuncios->foto9;
    if (File::exists($pathFoto)) {
        File::delete($pathFoto);
        $anuncios->foto9 = '';
        $anuncios->save();
        return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
    }

    else {
        return response()->json(['message' => 'Foto não encontrada'], 404);
    }
}


public function destroyFoto10($id)
{
    $anuncios = Anuncios::find($id);

    if (!$anuncios) {
        return response()->json(['message' => 'Anunciantes não encontrado'], 404);
    }

    $pathFoto = public_path() .  $anuncios->foto10;
    if (File::exists($pathFoto)) {
        File::delete($pathFoto);
        $anuncios->foto10 = '';
        $anuncios->save();
        return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
    }

    else {
        return response()->json(['message' => 'Foto não encontrada'], 404);
    }
}
}
