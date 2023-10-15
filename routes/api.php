<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

//Categorias
Route::get('/categorias/listar_categorias', 'App\Http\Controllers\categoriasController@index');
Route::post('/categorias/registar_categorias', 'App\Http\Controllers\categoriasController@store');
Route::put('/editar_categorias/update/{id}', 'App\Http\Controllers\categoriasController@update');
Route::get('/categorias/visualizar_categorias/{id}', 'App\Http\Controllers\categoriasController@show');
Route::get('/eliminar_categorias/{id}', 'App\Http\Controllers\categoriasController@destroy');

//Notícias
Route::get('/noticias/listar_noticias', 'App\Http\Controllers\noticiasController@index');
Route::post('/noticias/registar_noticias', 'App\Http\Controllers\noticiasController@store');
Route::put('/editar_noticias/update/{id}', 'App\Http\Controllers\noticiasController@update');
Route::get('/noticias/visualizar_noticias/{id}', 'App\Http\Controllers\noticiasController@show');
Route::get('/eliminar_noticias/{id}', 'App\Http\Controllers\noticiasController@destroy');
Route::post('/noticias/upload_foto_capa/{id}', 'App\Http\Controllers\noticiasController@foto_capa');
Route::post('/noticias/upload_foto_artigo/{id}', 'App\Http\Controllers\noticiasController@foto_artigo');
Route::delete('/noticias/eliminar_foto_artigo/{id}', 'App\Http\Controllers\noticiasController@destroyFotoArtigo');
Route::delete('/noticias/eliminar_foto_capa/{id}', 'App\Http\Controllers\noticiasController@destroyFotoCapa');


//Anuciantes
Route::get('/anunciantes/listar_anuciantes', 'App\Http\Controllers\anuciantesController@index');
Route::post('/anunciantes/registar_anuciantes', 'App\Http\Controllers\anuciantesController@store');
Route::post('/anunciantes/uploadFoto_anuciantes/{id}', 'App\Http\Controllers\anuciantesController@uploadFoto');
Route::get('/anunciantes/visualizar_anuciantes/{id}', 'App\Http\Controllers\anuciantesController@show');
Route::put('/editar_anuciantes/update/{id}', 'App\Http\Controllers\anuciantesController@update');
Route::get('/eliminar_anuciantes/{id}', 'App\Http\Controllers\anuciantesController@destroy');
Route::delete('/anunciantes/eliminar_foto/{id}', 'App\Http\Controllers\anuciantesController@destroyFoto');


//Anuncios
Route::get('/anuncios/listar_anuncios', 'App\Http\Controllers\anunciosController@index');
Route::post('/anuncios/registar_anuncios', 'App\Http\Controllers\anunciosController@store');
Route::post('/anuncios/uploadFoto1_anuncios/{id}', 'App\Http\Controllers\anunciosController@uploadFoto');
Route::put('/editar_anuncios/update/{id}', 'App\Http\Controllers\anunciosController@update');
Route::get('/anuncios/visualizar_anuncios/{id}', 'App\Http\Controllers\anunciosController@show');
Route::get('/eliminar_anuncios/{id}', 'App\Http\Controllers\anunciosController@destroy');

Route::delete('/anuncios/eliminar_foto1/{id}', 'App\Http\Controllers\anunciosController@destroyFoto1');
Route::delete('/anuncios/eliminar_foto2/{id}', 'App\Http\Controllers\anunciosController@destroyFoto2');
Route::delete('/anuncios/eliminar_foto3/{id}', 'App\Http\Controllers\anunciosController@destroyFoto3');
Route::delete('/anuncios/eliminar_foto4/{id}', 'App\Http\Controllers\anunciosController@destroyFoto4');
Route::delete('/anuncios/eliminar_foto5/{id}', 'App\Http\Controllers\anunciosController@destroyFoto5');
Route::delete('/anuncios/eliminar_foto6/{id}', 'App\Http\Controllers\anunciosController@destroyFoto6');
Route::delete('/anuncios/eliminar_foto7/{id}', 'App\Http\Controllers\anunciosController@destroyFoto7');
Route::delete('/anuncios/eliminar_foto8/{id}', 'App\Http\Controllers\anunciosController@destroyFoto8');
Route::delete('/anuncios/eliminar_foto9/{id}', 'App\Http\Controllers\anunciosController@destroyFoto9');
Route::delete('/anuncios/eliminar_foto10/{id}', 'App\Http\Controllers\anunciosController@destroyFoto10');


//Planos
Route::get('/planos/listar_planos', 'App\Http\Controllers\planosController@index');
Route::post('/planos/registar_planos', 'App\Http\Controllers\planosController@store');
Route::put('/editar_planos/update/{id}', 'App\Http\Controllers\planosController@update');
Route::get('/planos/visualizar_planos/{id}', 'App\Http\Controllers\planosController@show');
Route::get('/eliminar_planos/{id}', 'App\Http\Controllers\planosController@destroy');

//Planos Anuciantes
Route::get('/planos_anuciantes/listar_planos_anuciantes', 'App\Http\Controllers\planosAnuciantesController@index');
Route::post('/planos_anuciantes/registar_planos_anuciantes', 'App\Http\Controllers\planosAnuciantesController@store');
Route::put('/editar_planos_anuciantes/update/{id}', 'App\Http\Controllers\planosAnuciantesController@update');
Route::get('/planos_anuciantes/visualizar_planos_anuciantes/{id}', 'App\Http\Controllers\planosAnuciantesController@show');
Route::get('/eliminar_planos_anuciantes/{id}', 'App\Http\Controllers\planosAnuciantesController@destroy');

//Proposta
Route::get('/propostas/listar_propostas', 'App\Http\Controllers\propostasController@index');
Route::post('/propostas/registar_propostas', 'App\Http\Controllers\propostasController@store');
Route::get('/propostas/visualizar_proposta/{id}', 'App\Http\Controllers\propostasController@show');
Route::put('/editar_proposta/update/{id}', 'App\Http\Controllers\propostasController@update');
Route::get('/eliminar_proposta/{id}', 'App\Http\Controllers\propostasController@destroy');

//Marcas
Route::get('/marcas/listar_marcas', 'App\Http\Controllers\marcasController@index');
Route::post('/marcas/registar_marcas', 'App\Http\Controllers\marcasController@store');
Route::get('/marcas/visualizar_marca/{id}', 'App\Http\Controllers\marcasController@show');
Route::put('/editar_marca/update/{id}', 'App\Http\Controllers\marcasController@update');
Route::get('/eliminar_marca/{id}', 'App\Http\Controllers\marcasController@destroy');

//Modelo
Route::get('/modelos/listar_modelos', 'App\Http\Controllers\modeloController@index');
Route::post('/modelos/registar_modelos', 'App\Http\Controllers\modeloController@store');
Route::get('/modelos/visualizar_modelo/{id}', 'App\Http\Controllers\modeloController@show');
Route::put('/editar_modelo/update/{id}', 'App\Http\Controllers\modeloController@update');
Route::get('/eliminar_modelo/{id}', 'App\Http\Controllers\modeloController@destroy');

//login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum'])->group(
	function () {
    Route::get('/teste', [AuthController::class,'teste']);
    
    Route::post('logout', [AuthController::class, 'logout']);
}); // Fim da verificação de autenticação

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});