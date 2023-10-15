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
 // Personalização dos campos da base de dados
 $dadosPersonalizados = [];

 foreach ($anunciantes as $anunciantes) {
     // Personalize os campos conforme necessário
     $dadosPersonalizados[] = [
         'id' => $anunciantes->id,
         'nome' => $anunciantes->nome,
         'tipo' => $anunciantes->tipo,
         'responsavel' => $anunciantes->responsavel,
         'email' => $anunciantes->email,
         'telefone' => $anunciantes->telefone,
         'cpf' => $anunciantes->cpf,
         'foto' => $anunciantes->foto ? env('URL_BASE_SERVIDOR') . $anunciantes->foto : null,
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
        if(!$anunciantes){
            return response(['message'=>'Anunciante não encontrado'], 404);
        }
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
        $anunciante = Anunciantes::find($id);
        $dadosPersonalizados = [];
        if(!$anunciante){
            return response(['message'=>'Anunciante não encontrado'], 404);
        }
            // Personalize os campos conforme necessário
            $dadosPersonalizados[] = [
                'id' => $anunciante->id,
                'nome' => $anunciante->nome,
                'tipo' => $anunciante->tipo,
                'responsavel' => $anunciante->responsavel,
                'email' => $anunciante->email,
                'telefone' => $anunciante->telefone,
                'cpf' => $anunciante->cpf,
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
