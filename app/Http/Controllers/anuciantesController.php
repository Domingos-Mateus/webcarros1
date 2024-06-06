<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Anunciantes;
use App\Models\Anuncios;
use App\Models\Estados;
use App\Models\Regioes;
use App\Models\Cidades;
use App\Models\User;
use File;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        $query = DB::table('anunciantes')
            ->join('estados', 'estados.id', '=', 'anunciantes.estado_id')
            ->join('regioes', 'regioes.id', '=', 'anunciantes.regiao_id')
            ->join('cidades as cidade_principal', 'cidade_principal.id', '=', 'anunciantes.cidade_id')
            ->join('users', 'users.id', '=', 'anunciantes.usuario_id')
            ->join('cidades as cidade_comercial', 'cidade_comercial.id', '=', 'anunciantes.cidade_comercial_id')
            ->select(
                'anunciantes.*',
                'estados.estado as estado',
                'estados.id as estado_id',
                'regioes.regiao as regiao',
                'regioes.id as regiao_id',
                'cidade_principal.cidade as cidade',
                'cidade_principal.id as cidade_id',
                'cidade_comercial.cidade as cidade_Comercial',
                'cidade_comercial.id as cidade_comercial_id',
                'users.id as user_id'
            );

        if (request('nome_empresa')) {
            $query->where('anunciantes.nome_empresa', 'LIKE', '%' . request('nome_empresa') . '%');
        }
        if (request('tipo_anunciante')) {
            $query->where('anunciantes.tipo_anunciante', 'LIKE', '%' . request('tipo_anunciante') . '%');
        }
        if (request('estado')) {
            $query->where('estados.estado', 'LIKE', '%' . request('estado') . '%');
        }
        if (request('regiao')) {
            $query->where('regioes.regiao', 'LIKE', '%' . request('regiao') . '%');
        }
        if (request('cidade')) {
            $query->where('cidade_comercial.cidade', 'LIKE', '%' . request('cidade') . '%');
        }
        if (request('status')) {
            $query->where('anunciantes.status', request('status'));
        }

        $anunciantes = $query->get();
        $dadosPersonalizados = [];
        foreach ($anunciantes as $anunciante) {
            $dadosPersonalizados[] = [
                'id' => $anunciante->id,
                'nome_empresa' => $anunciante->nome_empresa,
                'Pessoal_responsavel' => $anunciante->pessoal_responsavel,
                'tipo_anunciante' => $anunciante->tipo_anunciante,
                'cnpj' => $anunciante->cnpj,
                'telefone' => $anunciante->telefone,
                'celular' => $anunciante->celular,
                'whatsapp' => $anunciante->whatsapp,
                'site' => $anunciante->site,
                'email' => $anunciante->email,
                'password' => $anunciante->password,
                'cep' => $anunciante->cep,
                'endereco' => $anunciante->endereco,
                'numero' => $anunciante->numero,
                'complemento' => $anunciante->complemento,
                'bairro' => $anunciante->bairro,
                'cep_comercial' => $anunciante->cep_comercial,
                'endereco_comercial' => $anunciante->endereco_comercial,
                'numero_comercial' => $anunciante->numero_comercial,
                'complemento_comercial' => $anunciante->complemento_comercial,
                'bairro_comercial' => $anunciante->bairro_comercial,


                'regiao_id' => $anunciante->regiao_id,
                'regiao' => $anunciante->regiao,
                'estado_id' => $anunciante->estado_id,
                'estado' => $anunciante->estado,
                'cidade_id' => $anunciante->cidade_id,
                'cidade' => $anunciante->cidade,
                'cidade_comercial_id' => $anunciante->cidade_comercial_id,
                'cidade_comericial' => $anunciante->cidade_Comercial,
                'status' => $anunciante->status,
                'observacao' => $anunciante->observacao,
                'foto' => $anunciante->foto ? env('URL_BASE_SERVIDOR') . '/' . $anunciante->foto : null,
                'banner_loja' => $anunciante->banner_loja ? env('URL_BASE_SERVIDOR') . '/' . $anunciante->banner_loja : null,
                'banner_loja_movel' => $anunciante->banner_loja_movel ? env('URL_BASE_SERVIDOR') . '/' . $anunciante->banner_loja_movel : null,
                'user_id' => $anunciante->user_id,
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

        $estado = Estados::find($request->estado_id);
        $regiao = Regioes::find($request->regiao_id);
        $cidade = Cidades::find($request->cidade_id);


        if (!$estado) {
            return response(['message' => 'O Estado selecionado não existe'], 404);
        }
        if (!$regiao) {
            return response(['message' => 'A Regiao selecionada não existe'], 404);
        }
        if (!$cidade) {
            return response(['message' => 'A Cidade selecionada não existe'], 404);
        }

        // Verifica se o email já existe na tabela de anunciantes
        $emailExistente = Anunciantes::where('email', $request->email)->exists();

        // Se o email já existe, retorna uma mensagem em JSON
        if ($emailExistente) {
            return response()->json(['mensagem' => 'Este email já existe no banco de dados'], 409); // 409 é o código de status para conflito
        }

        $anunciantes = new Anunciantes;
        $anunciantes->nome_empresa = $request->nome_empresa;
        $anunciantes->pessoal_responsavel = $request->pessoal_responsavel;
        $anunciantes->tipo_anunciante = $request->tipo_anunciante;
        $anunciantes->cnpj = $request->cnpj;
        $anunciantes->telefone = $request->telefone;
        $anunciantes->celular = $request->celular;
        $anunciantes->whatsapp = $request->whatsapp;
        $anunciantes->email = $request->email;
        $anunciantes->password = $request->password;
        $anunciantes->status = $request->status;
        $anunciantes->site = $request->site;
        $anunciantes->cep = $request->cep;
        $anunciantes->endereco = $request->endereco;
        $anunciantes->numero = $request->numero;
        $anunciantes->complemento = $request->complemento;
        $anunciantes->bairro = $request->bairro;
        $anunciantes->endereco_comercial = $request->endereco_comercial;
        $anunciantes->numero_comercial = $request->numero_comercial;
        $anunciantes->complemento_comercial = $request->complemento_comercial;
        $anunciantes->bairro_comercial = $request->bairro_comercial;
        $anunciantes->cep_comercial = $request->cep_comercial;
        $anunciantes->estado_id = $request->estado_id;
        $anunciantes->cidade_id = $request->cidade_id;
        $anunciantes->cidade_comercial_id = $request->cidade_comercial_id;
        $anunciantes->regiao_id = $request->regiao_id;
        $anunciantes->observacao = $request->observacao;

        $anunciantes->save();


        $usuario = new User();
        $usuario->name = $anunciantes->nome_empresa;
        $usuario->email = $anunciantes->email;
        $usuario->password = Hash::make($anunciantes->password);
        $usuario->save();

        $anunciantes->usuario_id = $usuario->id;
        $anunciantes->save();


        return response()->json(['mensagem' => 'Anunciante cadastrado com sucesso'], 201);
        //return $anunciantes;
    }


    public function uploadFotoAnunciantes(Request $request, $id)
    {
        $anunciante = Anunciantes::find($id);
        if (!$anunciante) {
            return response(['message' => 'Anunciante não encontrado'], 404);
        }

        if ($request->hasFile('foto')) {
            // Excluir o arquivo antigo, se existir
            if ($anunciante->foto && file_exists(public_path($anunciante->foto))) {
                unlink(public_path($anunciante->foto));
            }
            // Processar o novo arquivo
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/anunciantes/perfil/', $filename);
            $anunciante->foto = 'uploads/anunciantes/perfil/' . $filename;
            $anunciante->save();
        }

        return response()->json(['message' => 'Foto do anunciante enviada com sucesso'], 200);
    }


    public function uploadBannerLoja(Request $request, $id)
    {
        $anunciante = Anunciantes::find($id);
        if (!$anunciante) {
            return response(['message' => 'Anunciante não encontrado'], 404);
        }

        if ($request->hasFile('banner_loja')) {
            // Excluir o arquivo antigo, se existir
            if ($anunciante->banner_loja && file_exists(public_path($anunciante->banner_loja))) {
                unlink(public_path($anunciante->banner_loja));
            }
            // Processar o novo arquivo
            $file = $request->file('banner_loja');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/anunciantes/loja/', $filename);
            $anunciante->banner_loja = 'uploads/anunciantes/loja/' . $filename;
            $anunciante->save();
        }

        return response()->json(['message' => 'Foto da loja do anunciante enviada com sucesso'], 200);
    }


    public function uploadBannerLojaMovel(Request $request, $id)
    {
        $anunciante = Anunciantes::find($id);
        if (!$anunciante) {
            return response(['message' => 'Anunciante não encontrado'], 404);
        }

        if ($request->hasFile('banner_loja_movel')) {
            // Excluir o arquivo antigo, se existir
            if ($anunciante->banner_loja_movel && file_exists(public_path($anunciante->banner_loja_movel))) {
                unlink(public_path($anunciante->banner_loja_movel));
            }
            // Processar o novo arquivo
            $file = $request->file('banner_loja_movel');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/anunciantes/loja_movel/', $filename);
            $anunciante->banner_loja_movel = 'uploads/anunciantes/loja_movel/' . $filename;
            $anunciante->save();
        }

        return response()->json(['message' => 'Foto da loja movel do anunciante enviada com sucesso'], 200);
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
        if (!$anunciante) {
            return response(['message' => 'Anunciante não encontrado'], 404);
        }


        $estado = Estados::find($anunciante->estado_id);
        $regiao = Regioes::find($anunciante->regiao_id);
        $cidade = Cidades::find($anunciante->cidade_id);
        // Personalize os campos conforme necessário
        $dadosPersonalizados[] = [
            'id' => $anunciante->id,
            'nome_empresa' => $anunciante->nome_empresa,
            'pessoal_anunciante' => $anunciante->pessoal_anunciante,
            'tipo_anunciante' => $anunciante->tipo_anunciante,
            'cnpj' => $anunciante->cnpj,
            'telefone' => $anunciante->telefone,
            'celular' => $anunciante->celular,
            'whatsapp' => $anunciante->whatsapp,
            'email' => $anunciante->email,
            'site' => $anunciante->site,
            'cep' => $anunciante->cep,
            'endereco' => $anunciante->endereco,
            'numero' => $anunciante->numero,
            'complemento' => $anunciante->complemento,
            'bairro' => $anunciante->bairro,
            'cep_comercial' => $anunciante->cep_comercial,
            'endereco_comercial' => $anunciante->endereco_comercial,
            'numero_comercial' => $anunciante->numero_comercial,
            'complemento_comercial' => $anunciante->complemento_comercial,
            'bairro_comercial' => $anunciante->bairro_comercial,
            'regiao' => $regiao->regiao,
            'estado' => $estado->estado,
            'cidade' => $cidade->cidade,
            'status' => $anunciante->status,
            'observacao' => $anunciante->observacao,
            'foto' => $anunciante->foto ? env('URL_BASE_SERVIDOR') . '/' . $anunciante->foto : null,
            'banner_loja' => $anunciante->banner_loja ? env('URL_BASE_SERVIDOR') . '/' . $anunciante->banner_loja : null,
            'banner_loja_movel' => $anunciante->banner_loja_movel ? env('URL_BASE_SERVIDOR') . '/' . $anunciante->banner_loja_movel : null,
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
        if (!$anunciantes) {
            return response(['message' => 'Anunciante não encontrado'], 404);
        }
        $anunciantes->nome_empresa = $request->nome_empresa;
        $anunciantes->pessoal_responsavel = $request->pessoal_responsavel;
        $anunciantes->tipo_anunciante = $request->tipo_anunciante;
        $anunciantes->cnpj = $request->cnpj;
        $anunciantes->telefone = $request->telefone;
        $anunciantes->celular = $request->celular;
        $anunciantes->whatsapp = $request->whatsapp;
        $anunciantes->email = $request->email;
        $anunciantes->status = $request->status;
        $anunciantes->site = $request->site;
        $anunciantes->cep = $request->cep;
        $anunciantes->endereco = $request->endereco;
        $anunciantes->numero = $request->numero;
        $anunciantes->complemento = $request->complemento;
        $anunciantes->bairro = $request->bairro;
        $anunciantes->endereco_comercial = $request->endereco_comercial;
        $anunciantes->numero_comercial = $request->numero_comercial;
        $anunciantes->complemento_comercial = $request->complemento_comercial;
        $anunciantes->bairro_comercial = $request->bairro_comercial;
        $anunciantes->cep_comercial = $request->cep_comercial;
        $anunciantes->estado_id = $request->estado_id;
        $anunciantes->cidade_id = $request->cidade_id;
        $anunciantes->cidade_comercial_id = $request->cidade_comercial_id;
        $anunciantes->regiao_id = $request->regiao_id;
        $anunciantes->observacao = $request->observacao;

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
        $anuncios = Anuncios::where('anunciante_id', $id)->delete();
        return $anuncios;


        return response(['message' => 'Anunciante e usuário relacionado eliminados com sucesso'], 200);
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
        } else {
            return response()->json(['message' => 'Foto não encontrada'], 404);
        }
    }
}
