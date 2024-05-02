<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Categorias;

use DB;

class categoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    // Começa a construir a consulta ao banco de dados
    $query = DB::table('categorias')
        ->select('categorias.*')
        ->orderBy('nome', 'asc');

    // Executa a consulta
    $categorias = $query->get();

    // Processamento dos dados para personalizar a resposta
    $dadosPersonalizados = [];
    foreach ($categorias as $categoria) {
        $dadosPersonalizados[] = [
            'id' => $categoria->id,
            'nome' => $categoria->nome,
            'descricao' => $categoria->descricao,
            'foto_categoria' => $categoria->foto_categoria ? env('URL_BASE_SERVIDOR') . '/' . $categoria->foto_categoria : null,
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
        $categorias = new Categorias;

        $categorias->nome = $request->nome;
        $categorias->descricao = $request->descricao;

        $categorias->save();

        return $categorias;
    }

    public function uploadFotoCategorias(Request $request, $id)
    {
        // Encontra a categoria pelo ID
        $categorias = Categorias::find($id);

        // Verifica se a categoria existe
        if (!$categorias) {
            return response(['message' => 'Categoria não encontrada'], 404);
        }

        if ($request->hasfile('foto_categoria')) {
            $file = $request->file('foto_categoria');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            // Move o arquivo para o diretório de destino
            $file->move('uploads/categorias/', $filename);
            $categorias->foto_categoria = 'uploads/categorias/' . $filename;
            $categorias->save();
        }

        // Retorna a categoria sem fazer alterações se nenhum arquivo foi enviado
        return response()->json(['message' => 'Foto da categoria enviada com sucesso'], 200);
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
        $categorias = Categorias::find($id);
        if (!$categorias) {
            return response(['message' => 'Categoria não encontrada'], 404);
        }
        $dadosPersonalizados = [
            'id' => $categorias->id,
            'nome' => $categorias->nome,
            'descricao' => $categorias->descricao,
            'foto_categoria' => $categorias->foto_categoria ? env('URL_BASE_SERVIDOR') . '/' . $categorias->foto_categoria : null,
            'created_at' => $categorias->created_at,

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
        $categorias = Categorias::find($id);
        if (!$categorias) {
            return response(['message' => 'Categoria não encontrado'], 404);
        }
        $categorias->nome = $request->nome;
        $categorias->descricao = $request->descricao;

        $categorias->save();

        return $categorias;
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
        Categorias::destroy($id);
        return response(['message' => 'Categoria eliminada com sucesso'], 200);
    }
}
