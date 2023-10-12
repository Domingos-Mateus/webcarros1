<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propostas;
use File;
use DB;

class propostasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $propostas = Propostas::all();

        $propostas = DB::table('propostas')
        ->join('anuncios','anuncios.id','propostas.anuncio_id')
        ->select('propostas.*', 'anuncios.titulo as anuncio_titulo')
        ->get();

$dadosPersonalizados = [];

foreach ($propostas as $proposta) {
    // Personalize os campos conforme necessário
    $dadosPersonalizados[] = [
        'id' => $proposta->id,
        'titulo' => $proposta->titulo,
        'anuncio_titulo' => $proposta->anuncio_titulo,
        'nome' => $proposta->nome,
        'email' => $proposta->email,
        'ddd' => $proposta->ddd,
        'telefone' => $proposta->telefone,
        'mensagem' => $proposta->mensagem,
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
        $proposta = new Propostas;
        $proposta->titulo = $request->titulo;
        $proposta->anuncio_id = $request->anuncio_id;
        $proposta->nome = $request->nome;
        $proposta->email = $request->email;
        $proposta->ddd = $request->ddd;
        $proposta->telefone = $request->telefone;
        $proposta->mensagem = $request->mensagem;
        $proposta->save();

        return $proposta;
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
        $proposta = Propostas::find($id);
        if(!$proposta){
            return response(['message'=>'Proposta não encontrada'], 404);
        }
        return $proposta;
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
        $proposta = Propostas::find($id);
        if(!$proposta){
            return response(['message'=>'Proposta não encontrada'], 404);
        }
        $proposta->titulo = $request->titulo;
        $proposta->anuncio_id = $request->anuncio_id;
        $proposta->nome = $request->nome;
        $proposta->email = $request->email;
        $proposta->ddd = $request->ddd;
        $proposta->telefone = $request->telefone;
        $proposta->mensagem = $request->mensagem;

        $proposta->save();
        return $proposta;
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
        Propostas::destroy($id);
        return "Anúncio eliminado com sucesso!";
    }
}
