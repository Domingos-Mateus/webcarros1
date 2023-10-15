<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Anuncios;
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
            ->select('anuncios.*', 'marcas.nome_marca', 'modelos.nome_modelo','categorias.nome as nome_categoria', 'anunciantes.nome as nome_anunciante');

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

        // Executa a consulta
        $anuncios = $query->get();

        // Processamento dos dados para personalizar a resposta
        $dadosPersonalizados = [];

        foreach ($anuncios as $anuncio) {
            $dadosPersonalizados[] = [
                'id' => $anuncio->id,
                'titulo' => $anuncio->titulo,
                'nome_marca' => $anuncio->nome_marca,
                'nome_modelo' => $anuncio->nome_modelo,
                'numero_cliques' => $anuncio->numero_cliques,
                'nome_anunciante' => $anuncio->nome_anunciante,
                'nome_categoria' => $anuncio->nome_categoria,
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
                'descricao' => $anuncio->descricao,
                'foto1' => $anuncio->foto1 ? env('URL_BASE_SERVIDOR') . $anuncio->foto1 : null,
                'foto2' => $anuncio->foto2 ? env('URL_BASE_SERVIDOR') . $anuncio->foto2 : null,
                'foto3' => $anuncio->foto3 ? env('URL_BASE_SERVIDOR') . $anuncio->foto3 : null,
                'foto4' => $anuncio->foto4 ? env('URL_BASE_SERVIDOR') . $anuncio->foto4 : null,
                'foto5' => $anuncio->foto5 ? env('URL_BASE_SERVIDOR') . $anuncio->foto5 : null,
                'foto6' => $anuncio->foto6 ? env('URL_BASE_SERVIDOR') . $anuncio->foto6 : null,
                'foto7' => $anuncio->foto7 ? env('URL_BASE_SERVIDOR') . $anuncio->foto7 : null,
                'foto8' => $anuncio->foto8 ? env('URL_BASE_SERVIDOR') . $anuncio->foto8 : null,
                'foto9' => $anuncio->foto9 ? env('URL_BASE_SERVIDOR') . $anuncio->foto9 : null,
                'foto10' => $anuncio->foto10 ? env('URL_BASE_SERVIDOR') . $anuncio->foto10 : null,
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
        $anuncios->titulo = $request->titulo;
        $anuncios->marca_id = $request->marca_id;
        $anuncios->modelo_id = $request->modelo_id;
        $anuncios->numero_cliques = $request->numero_cliques;
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
        $anuncios->estado_id = $request->estado_id;
        $anuncios->cidade_id = $request->cidade_id;
        $anuncios->empresa = $request->empresa;
        $anuncios->tipo_preco = $request->tipo_preco;
        $anuncios->valor_preco = $request->valor_preco;
        $anuncios->descricao = $request->descricao;

        $anuncios->save();
        return $anuncios;
    }

    public function uploadFoto(Request $request, $id)
    {
        //
        $anuncios = Anuncios::find($id);

        $anuncios->foto1 = $request->foto1;
        $anuncios->foto2 = $request->foto2;
        $anuncios->foto3 = $request->foto3;
        $anuncios->foto4 = $request->foto4;
        $anuncios->foto5 = $request->foto5;
        $anuncios->foto6 = $request->foto6;
        $anuncios->foto7 = $request->foto7;
        $anuncios->foto8 = $request->foto8;
        $anuncios->foto9 = $request->foto9;
        $anuncios->foto10 = $request->foto10;

         //Foto1
        if($request->foto1){
            $foto1 = $request->foto1;
            $extensaoimg = $foto1->getClientOriginalExtension();
            if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
                return back()->with('Erro', 'imagem com formato inválido');
            }
        }
        $anuncios->save();

        if ($request->foto1) {
            File::move($foto1, public_path().'/imagens_anuncios/imagem1/imagens'.$anuncios->id.'.'.$extensaoimg);
            $anuncios->foto1 = '/imagens_anuncios/imagem1/imagens'.$anuncios->id.'.'.$extensaoimg;
            $anuncios->save();
        }
        //Foto2
        if($request->foto2){
            $foto2 = $request->foto2;
            $extensaoimg = $foto2->getClientOriginalExtension();
            if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
                return back()->with('Erro', 'imagem com formato inválido');
            }
        }
        $anuncios->save();

        if ($request->foto2) {
            File::move($foto2, public_path().'/imagens_anuncios/imagem2/imagens'.$anuncios->id.'.'.$extensaoimg);
            $anuncios->foto2 = '/imagens_anuncios/imagem2/imagens'.$anuncios->id.'.'.$extensaoimg;
            $anuncios->save();
        }

             //Foto3
             if($request->foto3){
                $foto3 = $request->foto3;
                $extensaoimg = $foto3->getClientOriginalExtension();
                if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
                    return back()->with('Erro', 'imagem com formato inválido');
                }
            }
            $anuncios->save();

            if ($request->foto3) {
                File::move($foto3, public_path().'/imagens_anuncios/imagem3/imagens'.$anuncios->id.'.'.$extensaoimg);
                $anuncios->foto3 = '/imagens_anuncios/imagem3/imagens'.$anuncios->id.'.'.$extensaoimg;
                $anuncios->save();
            }

             //Foto4
             if($request->foto4){
                $foto4 = $request->foto4;
                $extensaoimg = $foto4->getClientOriginalExtension();
                if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
                    return back()->with('Erro', 'imagem com formato inválido');
                }
            }
            $anuncios->save();

            if ($request->foto4) {
                File::move($foto4, public_path().'/imagens_anuncios/imagem4/imagens'.$anuncios->id.'.'.$extensaoimg);
                $anuncios->foto4 = '/imagens_anuncios/imagem3/imagens'.$anuncios->id.'.'.$extensaoimg;
                $anuncios->save();
            }

            //Foto5
            if($request->foto5){
                $foto5 = $request->foto5;
                $extensaoimg = $foto5->getClientOriginalExtension();
                if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
                    return back()->with('Erro', 'imagem com formato inválido');
                }
            }
            $anuncios->save();

            if ($request->foto5) {
                File::move($foto5, public_path().'/imagens_anuncios/imagem5/imagens'.$anuncios->id.'.'.$extensaoimg);
                $anuncios->foto5 = '/imagens_anuncios/imagem5/imagens'.$anuncios->id.'.'.$extensaoimg;
                $anuncios->save();
            }

              //Foto6
              if($request->foto6){
                $foto6 = $request->foto6;
                $extensaoimg = $foto6->getClientOriginalExtension();
                if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
                    return back()->with('Erro', 'imagem com formato inválido');
                }
            }
            $anuncios->save();

            if ($request->foto6) {
                File::move($foto6, public_path().'/imagens_anuncios/imagem6/imagens'.$anuncios->id.'.'.$extensaoimg);
                $anuncios->foto6 = '/imagens_anuncios/imagem6/imagens'.$anuncios->id.'.'.$extensaoimg;
                $anuncios->save();
            }

                  //Foto7
                  if($request->foto7){
                    $foto7 = $request->foto7;
                    $extensaoimg = $foto7->getClientOriginalExtension();
                    if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
                        return back()->with('Erro', 'imagem com formato inválido');
                    }
                }
                $anuncios->save();

                if ($request->foto7) {
                    File::move($foto7, public_path().'/imagens_anuncios/imagem7/imagens'.$anuncios->id.'.'.$extensaoimg);
                    $anuncios->foto7 = '/imagens_anuncios/imagem7/imagens'.$anuncios->id.'.'.$extensaoimg;
                    $anuncios->save();
                }

                    //Foto8
                    if($request->foto8){
                        $foto8 = $request->foto8;
                        $extensaoimg = $foto8->getClientOriginalExtension();
                        if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
                            return back()->with('Erro', 'imagem com formato inválido');
                        }
                    }
                    $anuncios->save();

                    if ($request->foto8) {
                        File::move($foto8, public_path().'/imagens_anuncios/imagem8/imagens'.$anuncios->id.'.'.$extensaoimg);
                        $anuncios->foto8 = '/imagens_anuncios/imagem8/imagens'.$anuncios->id.'.'.$extensaoimg;
                        $anuncios->save();
                    }



                    //Foto9
                    if($request->foto9){
                        $foto9 = $request->foto9;
                        $extensaoimg = $foto9->getClientOriginalExtension();
                        if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
                            return back()->with('Erro', 'imagem com formato inválido');
                        }
                    }
                    $anuncios->save();

                    if ($request->foto9) {
                        File::move($foto9, public_path().'/imagens_anuncios/imagem9/imagens'.$anuncios->id.'.'.$extensaoimg);
                        $anuncios->foto9 = '/imagens_anuncios/imagem9/imagens'.$anuncios->id.'.'.$extensaoimg;
                        $anuncios->save();
                    }


                     //Foto10
                     if($request->foto10){
                        $foto10 = $request->foto10;
                        $extensaoimg = $foto10->getClientOriginalExtension();
                        if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
                            return back()->with('Erro', 'imagem com formato inválido');
                        }
                    }
                    $anuncios->save();

                    if ($request->foto10) {
                        File::move($foto10, public_path().'/imagens_anuncios/imagem10/imagens'.$anuncios->id.'.'.$extensaoimg);
                        $anuncios->foto10 = '/imagens_anuncios/imagem10/imagens'.$anuncios->id.'.'.$extensaoimg;
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
        // Personalização dos campos da base de dados


            // Personalize os campos conforme necessário
            $dadosPersonalizados[] = [
                'id' => $anuncio->id,
                'marca' => $anuncio->titulo,
                'modelo' => $anuncio->modelo,
                'numero_cliques' => $anuncio->numero_cliques,
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
                'descricao' => $anuncio->descricao,
                'foto1' => $anuncio->foto1 ? env('URL_BASE_SERVIDOR') . $anuncio->foto1 : null,
                'foto2' => $anuncio->foto2 ? env('URL_BASE_SERVIDOR') . $anuncio->foto2 : null,
                'foto3' => $anuncio->foto3 ? env('URL_BASE_SERVIDOR') . $anuncio->foto3 : null,
                'foto4' => $anuncio->foto4 ? env('URL_BASE_SERVIDOR') . $anuncio->foto4 : null,
                'foto5' => $anuncio->foto5 ? env('URL_BASE_SERVIDOR') . $anuncio->foto5 : null,
                'foto6' => $anuncio->foto6 ? env('URL_BASE_SERVIDOR') . $anuncio->foto6 : null,
                'foto7' => $anuncio->foto7 ? env('URL_BASE_SERVIDOR') . $anuncio->foto7 : null,
                'foto8' => $anuncio->foto8 ? env('URL_BASE_SERVIDOR') . $anuncio->foto8 : null,
                'foto9' => $anuncio->foto9 ? env('URL_BASE_SERVIDOR') . $anuncio->foto9 : null,
                'foto10' => $anuncio->foto10 ? env('URL_BASE_SERVIDOR') . $anuncio->foto10 : null,
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
        if(!$anuncios){
            return response(['message'=>'Anúncio não encontrado'], 404);
        }
        $anuncios->titulo = $request->titulo;
        $anuncios->marca_id = $request->marca_id;
        $anuncios->modelo_id = $request->modelo_id;
        $anuncios->numero_cliques = $request->numero_cliques;
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
        $anuncios->estado_id = $request->estado_id;
        $anuncios->cidade_id = $request->cidade_id;
        $anuncios->empresa = $request->empresa;
        $anuncios->tipo_preco = $request->tipo_preco;
        $anuncios->valor_preco = $request->valor_preco;
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
}
