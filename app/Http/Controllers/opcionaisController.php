<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Opcionais;
use App\Models\CategoriaOpcionais;
use File;
use DB;

class opcionaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('opcionais')
        ->join('categoria_opcionais','categoria_opcionais.id','opcionais.categoria_opcional_id')
        ->select('opcionais.*', 'categoria_opcionais.nome as nome_categoria','categoria_opcionais.id as id_categorias');
  // Adiciona os filtros conforme os parâmetros passados
  /*
  if (request('nome_categoria')) {
    $query->where('categoria_opcionais.nome_categoria', 'LIKE', '%' . request('nome_categoria') . '%');
}
*/

$opcionais = $query->get();
$dadosPersonalizados = [];

foreach ($opcionais as $opcional) {
    // Personalize os campos conforme necessário
    $dadosPersonalizados[] = [
        'id' => $opcional->id,
        'categoria_opcional' => $opcional->nome_categoria,
        'nome' => $opcional->nome,
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
        $catgoriasOpcionais = CategoriaOpcionais::find($request->categoria_opcional_id);

        if(!$catgoriasOpcionais){
            return response(['message'=> 'A Categoria selecionada não existe'], 404);
        }
        $opcionais = new Opcionais;
        $opcionais->categoria_opcional_id = $request->categoria_opcional_id;
        $opcionais->nome = $request->nome;

        $opcionais->save();

        return $opcionais;
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
        $opcionais = Opcionais::find($id);
        if(!$opcionais){
            return response(['message'=>'Opcionais não encontrada'], 404);
        }
        $categoria_opcional = CategoriaOpcionais::find($opcionais->categoria_opcional_id);

        $dadosPersonalizados[] = [
            'id' => $opcionais->id,
            'categoria_opcional' => $categoria_opcional->nome,
            'nome' => $opcionais->nome,
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
        $opcionais = Opcionais::find($id);
        if(!$opcionais){
            return response(['message'=>'Opcionais não encontrada'], 404);
        }

        $opcionais->categoria_opcional_id = $request->categoria_opcional_id;
        $opcionais->nome = $request->nome;

        $opcionais->save();
        return $opcionais;
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
        Opcionais::destroy($id);
        return "Opcional Eliminado com sucesso!";
    }
}
