<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Anunciantes;
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

        return $anunciantes;
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
        $anunciantes = new Anunciantes;
        $anunciantes->nome = $request->nome;
        $anunciantes->tipo = $request->tipo;
        $anunciantes->responsavel = $request->responsavel;
        $anunciantes->email = $request->email;
        $anunciantes->telefone = $request->telefone;
        $anunciantes->cpf = $request->cpf;
        $anunciantes->plano_id = $request->plano_id;
        $anunciantes->estado_id = $request->estado_id;
        $anunciantes->cidade_id = $request->cidade_id;

        $anunciantes->save();
        return $anunciantes;
    }

    public function uploadFoto(Request $request, $id)
    {
        //
        $anunciantes = Anunciantes::find($id);
        if($request->foto){
            $foto = $request->foto;
            $extensaoimg = $foto->getClientOriginalExtension();
            if($extensaoimg !='jpg' && $extensaoimg != 'jpg' && $extensaoimg != 'png'){
                return back()->with('Erro', 'imagem com formato inválido');
            }
        }

        $anunciantes->save();

        if ($request->foto) {
            File::move($foto, public_path().'/imagens_anunciantes/imagens'.$anunciantes->id.'.'.$extensaoimg);
            $anunciantes->foto = '/imagens_anunciantes/imagens'.$anunciantes->id.'.'.$extensaoimg;

            $anunciantes->save();
        }
        $anunciantes->save();
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
        $anunciantes = Anunciantes::find($id);
        return $anunciantes;
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
        $anunciantes->nome = $request->nome;
        $anunciantes->tipo = $request->tipo;
        $anunciantes->responsavel = $request->responsavel;
        $anunciantes->email = $request->email;
        $anunciantes->telefone = $request->telefone;
        $anunciantes->cpf = $request->cpf;
        $anunciantes->plano_id = $request->plano_id;
        $anunciantes->estado_id = $request->estado_id;
        $anunciantes->cidade_id = $request->cidade_id;

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
    }
}
