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
        ->select('noticias.*', 'categorias.nome as nome_categoria','categorias.id as id_categorias')
        ->get();

        if (request('nome_categoria')) {
            $query->where('anuncios.nome_categoria', request('nome_categoria'));
        }
//$noticias = Noticias::all();

// Personalização dos campos da base de dados
$dadosPersonalizados = [];

foreach ($noticias as $noticias) {
    // Personalize os campos conforme necessário
    $dadosPersonalizados[] = [
        'id' => $noticias->id,
        'titulo' => $noticias->titulo,
        'subtitulo' => $noticias->subtitulo,
        'foto_capa' => $noticias->foto_capa ? env('URL_BASE_SERVIDOR') . $noticias->foto_capa : null,
        'foto_artigo' => $noticias->foto_artigo ? env('URL_BASE_SERVIDOR') . $noticias->foto_artigo : null,
        'resumo' => $noticias->resumo,
        'status' => $noticias->status,
        'nome_categoria' => $noticias->nome_categoria,
        'id_categorias' => $noticias->id,
        'descricao' => $noticias->descricao,
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

        return "Foto de Capa carregada com sucesso";
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
        $noticia = Noticias::find($id);
        $dadosPersonalizados = [];
        if(!$noticia){
            return response(['message'=>'Notícias não encontrada'], 404);
        }
        // Personalização dos campos da base de dados
    // Personalize os campos conforme necessário
    $dadosPersonalizados[] = [
        'id' => $noticia->id,
        'titulo' => $noticia->titulo,
        'subtitulo' => $noticia->subtitulo,
        'foto_capa' => $noticia->foto_capa ? env('URL_BASE_SERVIDOR') . $noticia->foto_capa : null,
        'foto_artigo' => $noticia->foto_artigo ? env('URL_BASE_SERVIDOR') . $noticia->foto_artigo : null,
        'status' => $noticia->status,
        'categoria_id' => $noticia->categoria_id,
        'descricao' => $noticia->descricao,
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
        $noticias = Noticias::find($id);
        if(!$noticias){
            return response(['message'=>'Notícias não encontrada'], 404);
        }
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



    
    public function destroyFotoArtigo($id)
{
    $noticia = Noticias::find($id);

    if (!$noticia) {
        return response()->json(['message' => 'Notícia não encontrada'], 404);
    }

    // Para achar o caminho da foto
    $pathFoto = public_path() .  $noticia->foto_artigo;
    //return $pathFoto;
    // Verificar se o arquivo existe
    if (File::exists($pathFoto)) {
        // Deletar o arquivo
        File::delete($pathFoto);

        // Atualizar ou zerar a coluna da foto no banco de dados
        $noticia->foto_artigo = '';
        $noticia->save();

        return response()->json(['message' => 'Foto do artigo eliminada com sucesso'], 200);
        
    } 
    
    else {
        return response()->json(['message' => 'Foto não encontrada'], 404);
    }
}


public function destroyFotoCapa($id)
{
    $noticia = Noticias::find($id);

    if (!$noticia) {
        return response()->json(['message' => 'Notícia não encontrada'], 404);
    }

    // Para achar o caminho da foto
    $pathFoto = public_path() .  $noticia->foto_capa;
    //return $pathFoto;
    // Verificar se o arquivo existe
    if (File::exists($pathFoto)) {
        // Deletar o arquivo
        File::delete($pathFoto);

        // Atualizar ou zerar a coluna da foto no banco de dados
        $noticia->foto_capa = '';
        $noticia->save();

        return response()->json(['message' => 'Foto de capa eliminada com sucesso'], 200);
        
    } 
    
    else {
        return response()->json(['message' => 'Foto não encontrada'], 404);
    }
}
}
