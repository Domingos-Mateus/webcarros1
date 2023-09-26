<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Anuncios;
use File;
use DB;

class anunciosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $anuncios = Anuncios::all();

        return $anuncios;
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
        return "Foto carregada com sucesso!";

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
        $anuncios = Anuncios::find($id);
        if(!$anuncios){
            return "Anúncio não encontrado";
        }
        return $anuncios;
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
            return "Anúncio não encontrado";
        }
        $anuncios->titulo = $request->titulo;
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
