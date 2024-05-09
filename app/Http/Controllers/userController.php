<?php

namespace App\Http\Controllers;

use App\Models\Anunciantes;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Auth;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = DB::table('users')
            ->leftJoin('anunciantes', 'anunciantes.usuario_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.activo',
                'users.perfil',
                'users.remember_token',
                'users.created_at',
                'users.updated_at',
                'anunciantes.id as anunciante_id'  // Adiciona o ID do anunciante associado se existir
            );

        $users = $query->get();
        $dadosPersonalizados = [];
        foreach ($users as $user) {
            $dadosPersonalizados[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'activo' => $user->activo,
                'perfil' => $user->perfil,
                'remember_token' => $user->remember_token,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'anunciante_id' => $user->anunciante_id  // Adiciona o ID do anunciante ao array
            ];
        }

        return response()->json($dadosPersonalizados);
    }




    public function usuarioLogado()
    {
        //
        $user = Auth::user();
        return $user;
    }

    public function anuncianteLogado()
    {
        $user = Auth::user();
        $anunciante = Anunciantes::where('usuario_id', $user->id)->first(); // Usando o relacionamento definido no modelo User
        // Preparar os dados a serem retornados
        $data = [
            'anunciante_id' => $anunciante ? $anunciante->id : null,
            'name' => $user->name,
            'email' => $user->email,
            'activo' => $user->activo,
            'remember_token' => $user->remember_token,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'id_usuario' => $user->id
        ];

        // Retorna os dados em formato JSON
        return response()->json($data);
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
    }
}
