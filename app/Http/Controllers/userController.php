<?php

namespace App\Http\Controllers;

use App\Models\Anunciantes;
use Illuminate\Http\Request;
use App\Models\User;
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
        $user = Auth::user();
    $anunciante = Anunciantes::where('usuario_id',$user->id)->first(); // Usando o relacionamento definido no modelo User

    // Preparar os dados a serem retornados
    $data = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'activo' => $user->activo,
        'remember_token' => $user->remember_token,
        'created_at' => $user->created_at,
        'updated_at' => $user->updated_at,
        'anunciante_id' => $anunciante ? $anunciante->id : null
    ];

    // Retorna os dados em formato JSON
    return response()->json($data);
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
    $anunciante = Anunciantes::where('usuario_id',$user->id)->first(); // Usando o relacionamento definido no modelo User


    // Preparar os dados a serem retornados
    $data = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'activo' => $user->activo,
        'remember_token' => $user->remember_token,
        'created_at' => $user->created_at,
        'updated_at' => $user->updated_at,
        'anunciante_id' => $anunciante ? $anunciante->id : null
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
