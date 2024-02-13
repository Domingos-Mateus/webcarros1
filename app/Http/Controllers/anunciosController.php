<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Anuncios;
use App\Models\Tecnologia;
use App\Models\TiposVeiculos;
use App\Models\Marcas;
use App\Models\Modelos;
use App\Models\Anunciantes;
use App\Models\Categorias;
use App\Models\Estados;
use App\Models\Cidades;
use App\Models\Combustivel;
use App\Models\Cor;
use App\Models\Transmissao;
use App\Models\Confortos;
use App\Models\Fabricantes;
use App\Models\Opcionais;
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
            ->join('tipos_veiculos','tipos_veiculos.id','anuncios.tipo_veiculo_id')
            ->join('tecnologias','tecnologias.id','anuncios.tecnologia_id')
            ->join('cors','cors.id','anuncios.cor_id')
            ->join('estados','estados.id','anuncios.estado_id')
            ->join('cidades','cidades.id','anuncios.cidade_id')
            ->join('fabricantes','fabricantes.id','anuncios.fabricante_id')
            ->join('transmissaos','transmissaos.id','anuncios.transmissao_id')
            ->join('combustivels','combustivels.id','anuncios.combustivel_id')
            ->select('anuncios.*', 'marcas.nome_marca', 'marcas.id as id_marcas',
             'modelos.nome_modelo', 'modelos.id as id_m','categorias.nome as nome_categoria',
             'categorias.id as id_categoria', 'anunciantes.nome_empresa',
             'anunciantes.id as id_anunciantes','tipos_veiculos.tipo_veiculo',
             'estados.id as id_estado','estados.estado',
             'cidades.id as id_cidade','cidades.cidade',
             'fabricantes.fabricante',
             'tipos_veiculos.id as id_tipos_veiculo','tipos_veiculos.tipo_veiculo','cors.cor','cors.id as id_cor','tecnologias.tecnologia',
             'tecnologias.id as idtecnologia','combustivels.combustivel','combustivels.id as id_combustivel',
             'transmissaos.transmissao','transmissaos.id as id_transmissao');

             // Adiciona os filtros conforme os parâmetros passados
             if (request('tipo_veiculo')) {
                 $query->where('tipos_veiculos.tipo_veiculo', request('tipo_veiculo'));
             }
             if (request('situacao_veiculo')) {
                $query->where('anuncios.situacao_veiculo', request('situacao_veiculo'));
            }
            if (request('nome_empresa')) {
                $query->where('anunciantes.nome_empresa', request('nome_empresa'));
            }
            if (request('nome_marca')) {
                $query->where('marcas.nome_marca', request('nome_marca'));
            }
            if (request('valor_preco')) {
                $query->where('anuncios.valor_preco', request('valor_preco'));
            }
            if (request('ano_modelo')) {
                $query->where('anuncios.ano_modelo', request('ano_modelo'));
            }
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

        // Executa a consulta aleatóriamente
        $anuncios = $query->inRandomOrder()->get();
        // Processamento dos dados para personalizar a resposta
        $dadosPersonalizados = [];





        foreach ($anuncios as $anuncio) {
            $dadosPersonalizados[] = [
                'id' => $anuncio->id,
                'tipo_veiculo_id' => $anuncio->tipo_veiculo_id,
                'tipo_veiculo' => $anuncio->tipo_veiculo,
                'tecnologia_id' => $anuncio->tecnologia_id,
                'tecnologia' => $anuncio->tecnologia,
                'nome_marca' => $anuncio->nome_marca,
                'id_marca' => $anuncio->id_marcas,
                'nome_modelo' => $anuncio->nome_modelo,
                'id_modelo' => $anuncio->id_m,
                'numero_cliques' => $anuncio->numero_cliques,
                'situacao_veiculo' => $anuncio->situacao_veiculo,
                'nome_empresa' => $anuncio->nome_empresa,
                'id_anunciante' => $anuncio->id_anunciantes,
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
                'estado' => $anuncio->estado,
                'id_cidade' => $anuncio->cidade_id,
                'cidade' => $anuncio->cidade,
                'empresa' => $anuncio->empresa,
                'tipo_preco' => $anuncio->tipo_preco,
                'valor_preco' => $anuncio->valor_preco,
                'mostrar_preco' => $anuncio->mostrar_preco,
                'fabricante_id' => $anuncio->fabricante_id,
                'fabricante' => $anuncio->fabricante,
                'ano_fabricacao' => $anuncio->ano_fabricacao,
                'ano_modelo' => $anuncio->ano_modelo,
                'carroceria' => $anuncio->carroceria,
                'estilo' => $anuncio->estilo,
                'portas' => $anuncio->portas,
                'cilindros' => $anuncio->cilindros,
                'motor' => $anuncio->motor,
                'cor' => $anuncio->cor_id,
                'cor' => $anuncio->cor,
                'transmissao_id' => $anuncio->transmissao_id,
                'transmissao' => $anuncio->transmissao,
                'combustivel_id' => $anuncio->combustivel_id,
                'combustivel' => $anuncio->combustivel,
                'placa' => $anuncio->placa,
                'km' => $anuncio->km,
                'sinistrado' => $anuncio->sinistrado,
                //'conforto_id' => $anuncio->conforto_id,
                //'seguranca_id' => $anuncio->seguranca_id,
                'opcionais_id' => $anuncio->opcionais_id,
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
        $tipo_veiculo = TiposVeiculos::find($request->tipo_veiculo);
        $tecnologia = Tecnologia::find($request->tecnologia);
        $modelo = Modelos::find($request->modelo_id);
        $marca = Marcas::find($request->marca_id);
        $anunciante = Anunciantes::find($request->anunciante_id);
        $categoria = Categorias::find($request->categoria_id);
        $estado = Estados::find($request->estado_id);
        $cidade = Cidades::find($request->cidade_id);
        $fabricante = Fabricantes::find($request->fabricante_id);
        $cor = Cor::find($request->cor);
        $transmissao = Transmissao::find($request->transmissao);
        $combustivel = Combustivel::find($request->combustivel);

        if(!$tipo_veiculo){
            return response(['message'=> 'O tipo de veiculo selecionado não existe'], 404);
        }
        if(!$tecnologia){
            return response(['message'=> 'A tecnologia selecionada não existe'], 404);
        }
        if(!$marca){
            return response(['message'=> 'A marca selecionada não existe'], 404);
        }

        if(!$modelo){
            return response(['message'=> 'O modelo selecionado não existe'], 404);
        }
        if(!$anunciante){
            return response(['message'=> 'O Anunciante selecionado não existe'], 404);
        }

        if(!$categoria){
            return response(['message'=> 'A Categoria selecionada não existe'], 404);
        }
        if(!$estado){
            return response(['message'=> 'O Estado selecionado não existe'], 404);
        }
        if(!$cidade){
            return response(['message'=> 'A Cidade selecionada não existe'], 404);
        }
        if(!$fabricante){
            return response(['message'=> 'O Fabricante selecionado não existe'], 404);
        }
        if(!$cor){
            return response(['message'=> 'A Cor selecionada não existe'], 404);
        }
        if(!$transmissao){
            return response(['message'=> 'A Transmissão selecionada não existe'], 404);
        }
        if(!$combustivel){
            return response(['message'=> 'O Combustível selecionado não existe'], 404);
        }

        $anuncios = new Anuncios;
        $anuncios->titulo = $request->titulo;
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
        $anuncios->mostrar_preco = $request->mostrar_preco;
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
        //$anuncios->conforto_id = $request->conforto_id;
        //$anuncios->seguranca_id = $request->seguranca_id;
        $anuncios->opcionais_id = $request->opcionais_id;
        $anuncios->combustivel_id = $request->combustivel;
        $anuncios->placa = $request->placa;
        $anuncios->km = $request->km;

        $anuncios->sinistrado = $request->sinistrado;
        $anuncios->descricao = $request->descricao;

        $anuncios->save();
        return $anuncios;
    }

    public function uploadFoto(Request $request, $id)
    {
        //
        $anuncios = Anuncios::find($id);


        if($request->hasfile('foto1'))
        {
            $file = $request->file('foto1');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/anuncios/imagens/', $filename);
            $anuncios->foto1 = 'uploads/anuncios/imagens/'.$filename;
            $anuncios->save();
        }

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

        //Para Mostrar os nomes dos relacionamentos
        $tipo_veiculo = TiposVeiculos::find($anuncio->tipo_veiculo_id);
        $marca = Marcas::find($anuncio->marca_id);
        $modelo = Modelos::find($anuncio->modelo_id);
        $anunciante = Anunciantes::find($anuncio->anunciante_id);
        $categoria = Categorias::find($anuncio->categoria_id);
        $estado = Estados::find($anuncio->estado_id);
        $cidade = Cidades::find($anuncio->cidade_id);
        $fabricante = Fabricantes::find($anuncio->fabricante_id);
        $cor = Cor::find($anuncio->cor_id);
        $transmissao = Transmissao::find($anuncio->transmissao_id);
        $combustivel = Combustivel::find($anuncio->combustivel_id);
        // Personalização dos campos da base de dados

        $opcionais = Opcionais::all();
$lista_opcionais = collect([]);

$opcionais_anuncio = $anuncio->opcionais_id;

// Converta a string JSON para um array usando json_decode
$opcionais_array = json_decode($opcionais_anuncio, true);

foreach ($opcionais_array as $opcional_anuncio) {
    // Inicialize o array $array_opcional dentro do loop foreach
    $array_opcional = [];

    foreach ($opcionais as $opcional_banco) {
        if ($opcional_anuncio == $opcional_banco->id) {
            $array_opcional = array(
                'id_opcional' => $opcional_banco->id,
                'categoria_opcional_id' => $opcional_banco->categoria_opcional_id,
                'nome' => $opcional_banco->nome,
            );
        }

    }

    // Movido para fora do loop interno para evitar repetições
    $lista_opcionais->push($array_opcional);


                }





//return $tipo_veiculo;
            // Personalize os campos conforme necessário
            $dadosPersonalizados[] = [
                'id' => $anuncio->id,
                'tipo_veiculo' => $tipo_veiculo->tipo_veiculo,
                'marca' => $marca->nome_marca,
                'modelo' => $modelo->nome_modelo,
                'numero_cliques' => $anuncio->numero_cliques,
                'situacao_veiculo' => $anuncio->situacao_veiculo,
                'anunciantes' => $anunciante->nome,
                'categoria_id' => $categoria->nome,
                'data_inicio' => $anuncio->data_inicio,
                'data_fim' => $anuncio->data_fim,
                'ordenacao' => $anuncio->ordenacao,
                'status_publicacao' => $anuncio->status_publicacao,
                'status_pagamento' => $anuncio->status_pagamento,
                'tipo' => $anuncio->tipo,
                'vendido' => $anuncio->vendido,
                'vitrine' => $anuncio->vitrine,
                'destaque_busca' => $anuncio->destaque_busca,
                'estado' => $estado->estado,
                'cidade' => $cidade->cidade,
                'empresa' => $anuncio->empresa,
                'tipo_preco' => $anuncio->tipo_preco,
                'valor_preco' => $anuncio->valor_preco,
                'mostrar_preco' => $anuncio->mostrar_preco,
                'fabricante' => $fabricante->fabricante,
                'ano_fabricacao' => $anuncio->ano_fabricacao,
                'ano_modelo' => $anuncio->ano_modelo,
                'carroceria' => $anuncio->carroceria,
                'estilo' => $anuncio->estilo,
                'portas' => $anuncio->portas,
                'cilindros' => $anuncio->cilindros,
                'motor' => $anuncio->motor,
                'cor' => $cor->cor,
                'transmissao' => $transmissao->transmissao,
                'combustivel' => $combustivel->combustivel,
                'placa' => $anuncio->placa,
                'km' => $anuncio->km,
                'sinistrado' => $anuncio->sinistrado,
                'opcionais_id' => $lista_opcionais,

                //'conforto_id' => $anuncio->conforto_id,
                //'seguranca_id' => $anuncio->seguranca_id,
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
        $dadosPersonalizados = [];
        if(!$anuncios){
            return response(['message'=>'Anúncio não encontrado'], 404);
        }

        $tipo_veiculo = TiposVeiculos::find($request->tipo_veiculo);
        $tecnologia = Tecnologia::find($request->tecnologia);
        $modelo = Modelos::find($request->modelo_id);
        $marca = Marcas::find($request->marca_id);
        $anunciante = Anunciantes::find($request->anunciante_id);
        $categoria = Categorias::find($request->categoria_id);
        $estado = Estados::find($request->estado_id);
        $cidade = Cidades::find($request->cidade_id);
        $fabricante = Fabricantes::find($request->fabricante_id);
        $cor = Cor::find($request->cor);
        $transmissao = Transmissao::find($request->transmissao);
        $combustivel = Combustivel::find($request->combustivel);



        if(!$tipo_veiculo){
            return response(['message'=> 'O tipo de veiculo selecionado não existe'], 404);
        }
        if(!$tecnologia){
            return response(['message'=> 'A tecnologia selecionada não existe'], 404);
        }
        if(!$marca){
            return response(['message'=> 'A marca selecionada não existe'], 404);
        }

        if(!$modelo){
            return response(['message'=> 'O modelo selecionado não existe'], 404);
        }
        if(!$anunciante){
            return response(['message'=> 'O Anunciante selecionado não existe'], 404);
        }

        if(!$categoria){
            return response(['message'=> 'A Categoria selecionada não existe'], 404);
        }
        if(!$estado){
            return response(['message'=> 'O Estado selecionado não existe'], 404);
        }
        if(!$cidade){
            return response(['message'=> 'A Cidade selecionada não existe'], 404);
        }
        if(!$fabricante){
            return response(['message'=> 'O Fabricante selecionado não existe'], 404);
        }
        if(!$cor){
            return response(['message'=> 'A Cor selecionada não existe'], 404);
        }
        if(!$transmissao){
            return response(['message'=> 'A Transmissão selecionada não existe'], 404);
        }
        if(!$combustivel){
            return response(['message'=> 'O Combustível selecionado não existe'], 404);
        }



        $anuncios->titulo = $request->titulo;
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
        $anuncios->mostrar_preco = $request->mostrar_preco;
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
        //$anuncios->conforto_id = $request->conforto_id;
        $anuncios->opcionais_id = $request->opcionais_id;
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
