<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Noticias;
use App\Models\Categorias;
use File;
use DB;

class noticiasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $noticias = DB::table('noticias')
        ->join('categorias','categorias.id','noticias.categoria_id')
        ->select('noticias.*', 'categorias.nome as nome_categoria')
        ->get();


$noticias = Noticias::all();

return $noticias;
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
        $noticias = new Noticias;
        $noticias->titulo = $request->titulo;
        $noticias->subtitulo = $request->subtitulo;
        $noticias->foto_capa = "";
        $noticias->foto_artigo = "";
        $noticias->resumo = $request->resumo;
        $noticias->status = $request->status;
        $noticias->categoria_id = $request->categoria_id;
        $noticias->descricao = $request->descricao;

       $noticias->save();

       


        return $noticias;

    }

    public function foto_artigo(Request $request, $id)
    {
        $noticias = Noticias::find($id);
        if($request->foto_artigo){
            $foto_artigo = $request->foto_artigo;
            $extensaoimg = $foto_artigo->getClientOriginalExtension();
            if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
                return back()->with('Erro', 'imagem com formato inválido');
            }
        }

        $noticias->save();

        if ($request->foto_artigo) {
            File::move($foto_artigo, public_path().'/imagens_de_artigo/imagens'.$noticias->id.'.'.$extensaoimg);
            $noticias->foto_artigo = '/imagens_de_artigo/imagens'.$noticias->id.'.'.$extensaoimg;

            $noticias->save();
        }

        return "Foto de artigo carregada com sucesso";
    }

    public function foto_capa(Request $request, $id)
    {
        $noticias = Noticias::find($id);
        if($request->foto_capa){
            $foto_capa = $request->foto_capa;
            $extensaoimg = $foto_capa->getClientOriginalExtension();
            if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
                return back()->with('Erro', 'imagem com formato inválido');
            }
        }

        $noticias->save();

        if ($request->foto_capa) {
            File::move($foto_capa, public_path().'/imagens_de_capa/imagens'.$noticias->id.'.'.$extensaoimg);
            $noticias->foto_capa = '/imagens_de_capa/imagens'.$noticias->id.'.'.$extensaoimg;

            $noticias->save();
        }

        return "Foto de artigo carregada com sucesso";
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
        $noticias = Noticias::find($id);
        return $noticias;
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
        $noticias = Noticias::find($id);
        $noticias->titulo = $request->titulo;
        $noticias->subtitulo = $request->subtitulo;
        $noticias->resumo = $request->resumo;
        $noticias->status = $request->status;
        $noticias->categoria_id = $request->categoria_id;
        $noticias->descricao = $request->descricao;

            $noticias->save();
        return $noticias;
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
        Noticias::destroy($id);

        return "Anunciante eliminado com sucesso!";
    }
}
