<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tecnologia;
use Illuminate\Support\Facades\DB;

class tecnologiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('tecnologias')
       ->select('tecnologias.*')
       ->orderBy('tecnologias.tecnologia', 'asc');

   // Adiciona os filtros conforme os parâmetros passados
   /*
   if (request('tecnologia')) {
       $query->where('tecnologias.tecnologia', 'LIKE', '%' . request('tecnologia') . '%');
   }*/

   $tecnologias = $query->get();
   // Processamento dos dados para personalizar a resposta
   $dadosPersonalizados = [];

   foreach ($tecnologias as $tecnologia) {
       $dadosPersonalizados[] = [
           'id' => $tecnologia->id,
           'tecnologia' => $tecnologia->tecnologia,
           'descricao' => $tecnologia->descricao,
       ];
    }
    return $dadosPersonalizados;
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
        $tecnologia = new Tecnologia;

        $tecnologia->tecnologia = $request->tecnologia;
        $tecnologia->descricao = $request->descricao;

        $tecnologia->save();

        return response(['message'=> 'Tecnologia cadastrada com sucesso!'], 200);

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
        $tecnologia = Tecnologia::find($id);
        if(!$tecnologia){
            return response(['message'=>'Tecnologia não encontrado'], 404);
        }
        return $tecnologia;
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
        $tecnologia = Tecnologia::find($id);
        if(!$tecnologia){
            return response(['message'=>'Tecnologia não encontrado'], 404);
        }
        $tecnologia->tecnologia = $request->tecnologia;
        $tecnologia->descricao = $request->descricao;

        $tecnologia->save();
        return response(['message'=> 'Tecnologia Actualizado com sucesso!'], 200);

        //return $tecnologia;
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
        Tecnologia::destroy($id);
    }
}
