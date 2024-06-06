<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anuncios;
use App\Models\Tecnologia;
use App\Models\TiposVeiculos;
use App\Models\Marcas;
use App\Models\Modelos;
use App\Models\Anunciantes;
use App\Models\Categorias;
use App\Models\Estados;
use App\Models\Cidades;
use App\Models\Combustivel;
use App\Models\Cor;
use App\Models\Transmissao;
use App\Models\Fabricantes;
use App\Models\Opcionais;
use App\Models\PlanosAnunciantes;
use DateTime;
use File;
use DB;
use Illuminate\Support\Facades\Auth;
//use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManagerStatic as Image;

class anunciosController extends Controller
{

    public function index()
{
    // Começa a construir a consulta ao banco de dados
    $query = DB::table('anuncios')
        ->join('marcas', 'marcas.id', 'anuncios.marca_id')
        ->join('modelos', 'modelos.id', 'anuncios.modelo_id')
        ->join('categorias', 'categorias.id', 'anuncios.categoria_id')
        ->join('anunciantes', 'anunciantes.id', 'anuncios.anunciante_id')
        ->join('tipos_veiculos', 'tipos_veiculos.id', 'anuncios.tipo_veiculo_id')
        ->join('tecnologias', 'tecnologias.id', 'anuncios.tecnologia_id')
        ->join('cors', 'cors.id', 'anuncios.cor_id')
        ->join('fabricantes', 'fabricantes.id', 'anuncios.fabricante_id')
        ->join('transmissaos', 'transmissaos.id', 'anuncios.transmissao_id')
        ->join('combustivels', 'combustivels.id', 'anuncios.combustivel_id')
        ->join('estados', 'estados.id', 'anunciantes.estado_id')
        ->join('cidades', 'cidades.id', 'anunciantes.cidade_id')
        ->select(
            'anuncios.*',
            'marcas.nome_marca',
            'marcas.id as id_marcas',
            'modelos.nome_modelo',
            'modelos.id as id_m',
            'categorias.nome as nome_categoria',
            'categorias.id as id_categoria',
            'anunciantes.nome_empresa',
            'anunciantes.cnpj',
            'anunciantes.telefone',
            'anunciantes.celular',
            'anunciantes.whatsapp',
            'anunciantes.endereco',
            'anunciantes.estado_id',
            'estados.estado',
            'cidades.cidade',
            'anunciantes.cidade_id',
            'anunciantes.cep',
            'anunciantes.cep_comercial',
            'anunciantes.foto as foto_anunciante',
            'anunciantes.banner_loja',
            'anunciantes.banner_loja_movel',
            'anunciantes.endereco_comercial',
            'anunciantes.status as status_anuncinte',
            'anunciantes.id as id_anunciantes',
            'tipos_veiculos.tipo_veiculo',
            'fabricantes.fabricante',
            'tipos_veiculos.id as id_tipos_veiculo',
            'tipos_veiculos.tipo_veiculo',
            'cors.cor',
            'cors.id as id_cor',
            'tecnologias.tecnologia',
            'tecnologias.id as idtecnologia',
            'combustivels.combustivel',
            'combustivels.id as id_combustivel',
            'transmissaos.transmissao',
            'transmissaos.id as id_transmissao',
        )
        ->where('anunciantes.status', '=', 1);

    // Filtragem adicional
    if ($opcionais = request('opcionais_id')) {
        $opcionais = json_decode($opcionais);
        if (is_array($opcionais)) {
            $query->where(function($query) use ($opcionais) {
                foreach ($opcionais as $opcional) {
                    $query->orWhere('anuncios.opcionais_id', 'LIKE', "%{$opcional}%");
                }
            });
        }
    }


    // Obtém as datas de início e fim da requisição, se fornecidas
    $dataInicio = request('data_inicio');
    $dataFim = request('data_fim');

    if (!empty($dataInicio) && !empty($dataFim)) {
        $dataInicioFormatada = DateTime::createFromFormat('d/m/Y', $dataInicio);
        $dataFimFormatada = DateTime::createFromFormat('d/m/Y', $dataFim);

        if ($dataInicioFormatada && $dataFimFormatada) {
            $dataInicioFormatada = $dataInicioFormatada->format('Y-m-d');
            $dataFimFormatada = $dataFimFormatada->format('Y-m-d');

            if ($dataInicioFormatada > $dataFimFormatada) {
                return response()->json(['error' => 'A data de fim não pode ser anterior à data de início.'], 400);
            }

            $query->whereBetween('anuncios.created_at', [$dataInicioFormatada, $dataFimFormatada]);
        } else {
            return response()->json(['error' => 'Formato de data inválido.'], 400);
        }
    }

    // Filtro de preço
    $precoMin = request('preco_min');
    $precoMax = request('preco_max');
    if (!empty($precoMin) && !empty($precoMax)) {
        $query->whereBetween('anuncios.valor_preco', [$precoMin, $precoMax]);
    }

    // Filtro de ano de modelo
    if (request()->has(['ano_modelo_min', 'ano_modelo_max'])) {
        $query->whereBetween('anuncios.ano_modelo', [request('ano_modelo_min'), request('ano_modelo_max')]);
    }

    // Filtro de quilometragem
    if (request()->has(['quilometragem_min', 'quilometragem_max'])) {
        $query->whereBetween('anuncios.km', [request('quilometragem_min'), request('quilometragem_max')]);
    }

    if (request('tipo_veiculo')) {
        $query->where('tipos_veiculos.tipo_veiculo', 'LIKE', '%' . request('tipo_veiculo') . '%');
    }
    if (request('situacao_veiculo')) {
        $query->where('anuncios.situacao_veiculo', 'LIKE', '%' . request('situacao_veiculo') . '%');
    }
    if (request('nome_empresa')) {
        $query->where('anunciantes.nome_empresa', 'LIKE', '%' . request('nome_empresa') . '%');
    }

    if (request('valor_preco')) {
        $query->where('anuncios.valor_preco', request('valor_preco'));
    }
    if (request('ano_modelo')) {
        $query->where('anuncios.ano_modelo', request('ano_modelo'));
    }

    if (request('nome_marca')) {
        $query->where('marcas.nome_marca', request('nome_marca'));
    }

    if (request('nome_modelo')) {
        $query->where('modelos.nome_modelo', 'LIKE', '%' . request('nome_modelo') . '%');
    }
    if (request('nome_anunciante')) {
        $query->where('anunciantes.nome', 'LIKE', '%' . request('nome_anunciante') . '%');
    }
    if (request('estado')) {
        $query->where('estados.estado', request('estado'));
    }
    if (request('tecnologia')) {
        $query->where('tecnologias.tecnologia', request('tecnologia'));
    }
    if (request('cor')) {
        $query->where('cors.cor', request('cor'));
    }
    if (request('transmissao_id')) {
        $query->where('anuncios.transmissao_id', request('transmissao_id'));
    }
    if (request('combustivel_id')) {
        $query->where('anuncios.combustivel_id', request('combustivel_id'));
    }
    if (request('estado')) {
        $query->where('estados.estado', request('estado'));
    }
    if (request('cidade')) {
        $query->where('cidades.cidade', request('cidade'));
    }
    if (request('status_publicacao')) {
        $query->where('anuncios.status_publicacao', request('status_publicacao'));
    }
    if (request('vitrine')) {
        $query->where('anuncios.vitrine', request('vitrine'));
    }
    if (request('destaque_busca')) {
        $query->where('anuncios.destaque_busca', request('destaque_busca'));
    }

    $query->orderByRaw('CASE WHEN anuncios.destaque_busca = 1 THEN 0 ELSE 1 END, RAND()');

    // Executa a consulta aleatoriamente
    $anuncios = $query->inRandomOrder()->get();

    // Processamento dos dados para personalizar a resposta
    $dadosPersonalizados = [];

    foreach ($anuncios as $anuncio) {
        $dataPublicacao = new DateTime($anuncio->created_at);
        $dataFormatada = $dataPublicacao->format('d/m/Y');

        $dadosPersonalizados[] = [
            'id' => $anuncio->id,
            'tipo_veiculo_id' => $anuncio->tipo_veiculo_id,
            'tipo_veiculo' => $anuncio->tipo_veiculo,
            'tecnologia_id' => $anuncio->tecnologia_id,
            'tecnologia' => $anuncio->tecnologia,
            'id_marca' => $anuncio->id_marcas,
            'nome_marca' => $anuncio->nome_marca,
            'id_modelo' => $anuncio->id_m,
            'nome_modelo' => $anuncio->nome_modelo,
            'numero_cliques' => $anuncio->numero_cliques,
            'situacao_veiculo' => $anuncio->situacao_veiculo,
            'nome_empresa' => $anuncio->nome_empresa,
            'cnpj' => $anuncio->cnpj,
            'telefone' => $anuncio->telefone,
            'celular' => $anuncio->celular,
            'whatsapp' => $anuncio->whatsapp,
            'endereco' => $anuncio->endereco,
            'status_anuncinte' => $anuncio->status_anuncinte,
            'endereco_comercial' => $anuncio->endereco_comercial,
            'foto_anunciante' => $anuncio->foto_anunciante ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto_anunciante : null,
            'banner_loja' => $anuncio->banner_loja ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->banner_loja : null,
            'banner_loja_movel' => $anuncio->banner_loja_movel ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->banner_loja_movel : null,
            'id_anunciante' => $anuncio->id_anunciantes,
            'nome_categoria' => $anuncio->nome_categoria,
            'id_categoria' => $anuncio->id_categoria,
            'data_inicio' => $anuncio->data_inicio,
            'data_fim' => $anuncio->data_fim,
            'ordenacao' => $anuncio->ordenacao,
            'status_publicacao' => $anuncio->status_publicacao,
            'status_pagamento' => $anuncio->status_pagamento,
            'tipo' => $anuncio->tipo,
            'vendido' => $anuncio->vendido,
            'vitrine' => $anuncio->vitrine,
            'destaque_busca' => $anuncio->destaque_busca,
            'estado' => $anuncio->estado,
            'cidade' => $anuncio->cidade,
            'cep' => $anuncio->cep,
            'cep_comercial' => $anuncio->cep_comercial,
            'tipo_preco' => $anuncio->tipo_preco,
            'valor_preco' => $anuncio->valor_preco,
            'mostrar_preco' => $anuncio->mostrar_preco,
            'fabricante_id' => $anuncio->fabricante_id,
            'fabricante' => $anuncio->fabricante,
            'ano_fabricacao' => $anuncio->ano_fabricacao,
            'ano_modelo' => $anuncio->ano_modelo,
            'carroceria' => $anuncio->carroceria,
            'estilo' => $anuncio->estilo,
            'portas' => $anuncio->portas,
            'cilindros' => $anuncio->cilindros,
            'motor' => $anuncio->motor,
            'cor_id' => $anuncio->cor_id,
            'cor' => $anuncio->cor,
            'transmissao_id' => $anuncio->transmissao_id,
            'transmissao' => $anuncio->transmissao,
            'combustivel_id' => $anuncio->combustivel_id,
            'combustivel' => $anuncio->combustivel,
            'placa' => $anuncio->placa,
            'km' => $anuncio->km,
            'sinistrado' => $anuncio->sinistrado,
            'opcionais_id' => $anuncio->opcionais_id,
            'descricao' => $anuncio->descricao,
            'data_publicacao' => $dataFormatada,
            'foto1' => $anuncio->foto1 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto1 : null,
            'foto2' => $anuncio->foto2 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto2 : null,
            'foto3' => $anuncio->foto3 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto3 : null,
            'foto4' => $anuncio->foto4 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto4 : null,
            'foto5' => $anuncio->foto5 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto5 : null,
            'foto6' => $anuncio->foto6 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto6 : null,
            'foto7' => $anuncio->foto7 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto7 : null,
            'foto8' => $anuncio->foto8 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto8 : null,
            'foto9' => $anuncio->foto9 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto9 : null,
            'foto10' => $anuncio->foto10 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto10 : null,
        ];
    }

    return response()->json($dadosPersonalizados);
}




    //Listar apenas os anuncios aprovados
    public function anunciosAprovados()
    {
        // Começa a construir a consulta ao banco de dados
        $query = DB::table('anuncios')
            ->join('marcas', 'marcas.id', 'anuncios.marca_id')
            ->join('modelos', 'modelos.id', 'anuncios.modelo_id')
            ->join('categorias', 'categorias.id', 'anuncios.categoria_id')
            ->join('anunciantes', 'anunciantes.id', 'anuncios.anunciante_id')
            ->join('tipos_veiculos', 'tipos_veiculos.id', 'anuncios.tipo_veiculo_id')
            ->join('tecnologias', 'tecnologias.id', 'anuncios.tecnologia_id')
            ->join('cors', 'cors.id', 'anuncios.cor_id')
            ->join('fabricantes', 'fabricantes.id', 'anuncios.fabricante_id')
            ->join('transmissaos', 'transmissaos.id', 'anuncios.transmissao_id')
            ->join('combustivels', 'combustivels.id', 'anuncios.combustivel_id')
            ->join('estados', 'estados.id', 'anunciantes.estado_id') // Junção com a tabela de estados
            ->join('cidades', 'cidades.id', 'anunciantes.cidade_id') // Junção com a tabela de estados
            //  ->join('planos_anunciantes', 'planos_anunciantes.anunciante_id', 'anunciantes.id')
            ->select(
                'anuncios.*',
                'marcas.nome_marca',
                'marcas.id as id_marcas',
                'modelos.nome_modelo',
                'modelos.id as id_m',
                'categorias.nome as nome_categoria',
                'categorias.id as id_categoria',
                'anunciantes.nome_empresa',
                'anunciantes.cnpj',
                'anunciantes.telefone',
                'anunciantes.celular',
                'anunciantes.whatsapp',
                'anunciantes.endereco',
                'anunciantes.estado_id',
                'estados.estado', // Selecionando o nome do estado
                'cidades.cidade', // Selecionando o nome do Cidade
                'anunciantes.cidade_id',
                'anunciantes.cep',
                'anunciantes.cep_comercial',
                'anunciantes.foto as foto_anunciante',
                'anunciantes.banner_loja',
                'anunciantes.banner_loja_movel',
                'anunciantes.endereco_comercial',
                'anunciantes.status as status_anuncinte',
                'anunciantes.id as id_anunciantes',
                'tipos_veiculos.tipo_veiculo',
                'fabricantes.fabricante',
                'tipos_veiculos.id as id_tipos_veiculo',
                'tipos_veiculos.tipo_veiculo',
                'cors.cor',
                'cors.id as id_cor',
                'tecnologias.tecnologia',
                'tecnologias.id as idtecnologia',
                'combustivels.combustivel',
                'combustivels.id as id_combustivel',
                'transmissaos.transmissao',
                'transmissaos.id as id_transmissao',
            )
            ->where('anuncios.status_publicacao', '=', 2)
            ->where('anunciantes.status', '=', 1);

        // Obtém as datas de início e fim da requisição, se fornecidas
        $dataInicio = request('data_inicio'); // Exemplo: '12/06/2023'
        $dataFim = request('data_fim'); // Exemplo: '11/09/2023'

        // Verifica se ambas as datas foram fornecidas
        if (!empty($dataInicio) && !empty($dataFim)) {
            $dataInicioFormatada = DateTime::createFromFormat('d/m/Y', $dataInicio);
            $dataFimFormatada = DateTime::createFromFormat('d/m/Y', $dataFim);

            // Verifica se as datas convertidas são válidas
            if ($dataInicioFormatada && $dataFimFormatada) {
                $dataInicioFormatada = $dataInicioFormatada->format('Y-m-d');
                $dataFimFormatada = $dataFimFormatada->format('Y-m-d');

                // Certifica-se de que a data de início não é posterior à data de fim
                if ($dataInicioFormatada > $dataFimFormatada) {
                    return response()->json(['error' => 'A data de fim não pode ser anterior à data de início.'], 400);
                }

                // Aplica o filtro de intervalo de datas
                $query->whereBetween('anuncios.created_at', [$dataInicioFormatada, $dataFimFormatada]);
            } else {
                return response()->json(['error' => 'Formato de data inválido.'], 400);
            }
        }




        // Filtro de preço
        if ($precoMin = request('preco_min') && $precoMax = request('preco_max')) {
            $query->whereBetween('anuncios.valor_preco', [$precoMin, $precoMax]);
        }

        // Filtro de ano de modelo
        if (request()->has(['ano_modelo_min', 'ano_modelo_max'])) {
            $query->whereBetween('anuncios.ano_modelo', [request('ano_modelo_min'), request('ano_modelo_max')]);
        }

        // Filtro de quilometragem
        if (request()->has(['quilometragem_min', 'quilometragem_max'])) {
            $query->whereBetween('anuncios.km', [request('quilometragem_min'), request('quilometragem_max')]);
        }

        if (request('tipo_veiculo')) {
            $query->where('tipos_veiculos.tipo_veiculo', 'LIKE', '%' . request('tipo_veiculo') . '%');
        }
        if (request('situacao_veiculo')) {
            $query->where('anuncios.situacao_veiculo', 'LIKE', '%' . request('situacao_veiculo') . '%');
        }
        if (request('nome_empresa')) {
            $query->where('anunciantes.nome_empresa', 'LIKE', '%' . request('nome_empresa') . '%');
        }

        if (request('valor_preco')) {
            $query->where('anuncios.valor_preco', request('valor_preco'));
        }
        if (request('ano_modelo')) {
            $query->where('anuncios.ano_modelo', request('ano_modelo'));
        }

        if (request('nome_marca')) {
            $query->where('marcas.nome_marca', request('nome_marca'));
        }

        if (request('nome_modelo')) {
            $query->where('modelos.nome_modelo', 'LIKE', '%' . request('nome_modelo') . '%');
        }
        if (request('nome_anunciante')) {
            $query->where('anunciantes.nome', 'LIKE', '%' . request('nome_anunciante') . '%');
        }
        if (request('estado')) {
            $query->where('estados.estado', request('estado'));
        }
        if (request('tecnologia')) {
            $query->where('tecnologias.tecnologia', request('tecnologia'));
        }
        if (request('cor')) {
            $query->where('cors.cor', request('cor'));
        }
        if (request('transmissao_id')) {
            $query->where('anuncios.transmissao_id', request('transmissao_id'));
        }
        if (request('combustivel_id')) {
            $query->where('anuncios.combustivel_id', request('combustivel_id'));
        }
        if (request('estado')) {
            $query->where('estados.estado', request('estado'));
        }
        if (request('cidade')) {
            $query->where('cidades.cidade', request('cidade'));
        }
        if (request('status_publicacao')) {
            $query->where('anuncios.status_publicacao', request('status_publicacao'));
        }
        if (request('vitrine')) {
            $query->where('anuncios.vitrine', request('vitrine'));
        }

        $query->orderByRaw('CASE WHEN anuncios.destaque_busca = 1 THEN 0 ELSE 1 END, RAND()');

        // Executa a consulta aleatóriamente
        $anuncios = $query->inRandomOrder()->get();
        // Processamento dos dados para personalizar a resposta
        $dadosPersonalizados = [];

        foreach ($anuncios as $anuncio) {
            $dataPublicacao = new DateTime($anuncio->created_at);
            $dataFormatada = $dataPublicacao->format('d/m/Y');

            $dadosPersonalizados[] = [
                'id' => $anuncio->id,
                'tipo_veiculo_id' => $anuncio->tipo_veiculo_id,
                'tipo_veiculo' => $anuncio->tipo_veiculo,
                'tecnologia_id' => $anuncio->tecnologia_id,
                'tecnologia' => $anuncio->tecnologia,
                'id_marca' => $anuncio->id_marcas,
                'nome_marca' => $anuncio->nome_marca,
                'id_modelo' => $anuncio->id_m,
                'nome_modelo' => $anuncio->nome_modelo,
                'numero_cliques' => $anuncio->numero_cliques,
                'situacao_veiculo' => $anuncio->situacao_veiculo,
                'nome_empresa' => $anuncio->nome_empresa,
                'cnpj' => $anuncio->cnpj,
                'telefone' => $anuncio->telefone,
                'celular' => $anuncio->celular,
                'whatsapp' => $anuncio->whatsapp,
                'endereco' => $anuncio->endereco,
                'status_anuncinte' => $anuncio->status_anuncinte,
                'endereco_comercial' => $anuncio->endereco_comercial,
                'foto_anunciante' => $anuncio->foto_anunciante ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto_anunciante : null,
                'banner_loja' => $anuncio->banner_loja ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->banner_loja : null,
                'banner_loja_movel' => $anuncio->banner_loja_movel ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->banner_loja_movel : null,
                'id_anunciante' => $anuncio->id_anunciantes,
                'nome_categoria' => $anuncio->nome_categoria,
                'id_categoria' => $anuncio->id_categoria,
                'data_inicio' => $anuncio->data_inicio,
                'data_fim' => $anuncio->data_fim,
                'ordenacao' => $anuncio->ordenacao,
                'status_publicacao' => $anuncio->status_publicacao,
                'status_pagamento' => $anuncio->status_pagamento,
                'tipo' => $anuncio->tipo,
                'vendido' => $anuncio->vendido,
                'vitrine' => $anuncio->vitrine,
                'destaque_busca' => $anuncio->destaque_busca,
                //'estado_id' => $anuncio->estado_id,
                'estado' => $anuncio->estado,
                //'id_cidade' => $anuncio->cidade_id,
                'cidade' => $anuncio->cidade,
                'cep' => $anuncio->cep,
                'cep_comercial' => $anuncio->cep_comercial,
                'tipo_preco' => $anuncio->tipo_preco,
                'valor_preco' => $anuncio->valor_preco,
                'mostrar_preco' => $anuncio->mostrar_preco,
                'fabricante_id' => $anuncio->fabricante_id,
                'fabricante' => $anuncio->fabricante,
                'ano_fabricacao' => $anuncio->ano_fabricacao,
                'ano_modelo' => $anuncio->ano_modelo,
                'carroceria' => $anuncio->carroceria,
                'estilo' => $anuncio->estilo,
                'portas' => $anuncio->portas,
                'cilindros' => $anuncio->cilindros,
                'motor' => $anuncio->motor,
                'cor_id' => $anuncio->cor_id,
                'cor' => $anuncio->cor,
                'transmissao_id' => $anuncio->transmissao_id,
                'transmissao' => $anuncio->transmissao,
                'combustivel_id' => $anuncio->combustivel_id,
                'combustivel' => $anuncio->combustivel,
                'placa' => $anuncio->placa,
                'km' => $anuncio->km,
                'sinistrado' => $anuncio->sinistrado,
                //'conforto_id' => $anuncio->conforto_id,
                //'seguranca_id' => $anuncio->seguranca_id,
                'opcionais_id' => $anuncio->opcionais_id,
                'descricao' => $anuncio->descricao,
                'data_publicacao' => $dataFormatada,
                'foto1' => $anuncio->foto1 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto1 : null,
                'foto2' => $anuncio->foto2 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto2 : null,
                'foto3' => $anuncio->foto3 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto3 : null,
                'foto4' => $anuncio->foto4 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto4 : null,
                'foto5' => $anuncio->foto5 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto5 : null,
                'foto6' => $anuncio->foto6 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto6 : null,
                'foto7' => $anuncio->foto7 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto7 : null,
                'foto8' => $anuncio->foto8 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto8 : null,
                'foto9' => $anuncio->foto9 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto9 : null,
                'foto10' => $anuncio->foto10 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto10 : null,
                // Adicione mais campos personalizados conforme necessário
            ];
        }
        // Retorna a resposta JSON com os dados personalizados
        return response()->json($dadosPersonalizados);
    }

    //Listar apenas os de Vitrine
    public function indexDestaque()
    {
        // Começa a construir a consulta ao banco de dados
        $query = DB::table('anuncios')
            ->join('marcas', 'marcas.id', 'anuncios.marca_id')
            ->join('modelos', 'modelos.id', 'anuncios.modelo_id')
            ->join('categorias', 'categorias.id', 'anuncios.categoria_id')
            ->join('anunciantes', 'anunciantes.id', 'anuncios.anunciante_id')
            ->join('tipos_veiculos', 'tipos_veiculos.id', 'anuncios.tipo_veiculo_id')
            ->join('tecnologias', 'tecnologias.id', 'anuncios.tecnologia_id')
            ->join('cors', 'cors.id', 'anuncios.cor_id')
            ->join('fabricantes', 'fabricantes.id', 'anuncios.fabricante_id')
            ->join('transmissaos', 'transmissaos.id', 'anuncios.transmissao_id')
            ->join('combustivels', 'combustivels.id', 'anuncios.combustivel_id')
            ->join('estados', 'estados.id', 'anunciantes.estado_id') // Junção com a tabela de estados
            ->join('cidades', 'cidades.id', 'anunciantes.cidade_id') // Junção com a tabela de estados
            //  ->join('planos_anunciantes', 'planos_anunciantes.anunciante_id', 'anunciantes.id')
            ->select(
                'anuncios.*',
                'marcas.nome_marca',
                'marcas.id as id_marcas',
                'modelos.nome_modelo',
                'modelos.id as id_m',
                'categorias.nome as nome_categoria',
                'categorias.id as id_categoria',
                'anunciantes.nome_empresa',
                'anunciantes.cnpj',
                'anunciantes.telefone',
                'anunciantes.celular',
                'anunciantes.whatsapp',
                'anunciantes.endereco',
                'anunciantes.estado_id',
                'estados.estado', // Selecionando o nome do estado
                'cidades.cidade', // Selecionando o nome do Cidade
                'anunciantes.cidade_id',
                'anunciantes.cep',
                'anunciantes.cep_comercial',
                'anunciantes.foto as foto_anunciante',
                'anunciantes.endereco_comercial',
                'anunciantes.id as id_anunciantes',
                'tipos_veiculos.tipo_veiculo',
                'fabricantes.fabricante',
                'tipos_veiculos.id as id_tipos_veiculo',
                'tipos_veiculos.tipo_veiculo',
                'cors.cor',
                'cors.id as id_cor',
                'tecnologias.tecnologia',
                'tecnologias.id as idtecnologia',
                'combustivels.combustivel',
                'combustivels.id as id_combustivel',
                'transmissaos.transmissao',
                'transmissaos.id as id_transmissao',
            )
            ->where('anuncios.destaque_busca', '=', 1)
            ->where('anuncios.status_publicacao', '=', 2)
            ->where('anunciantes.status', '=', 1) // Filtra apenas anúncios na vitrine
            ->inRandomOrder() // Ordenação aleatória dos resultados
            ->limit(12);


        // Obtém as datas de início e fim da requisição, se fornecidas
        $dataInicio = request('data_inicio'); // Exemplo: '12/06/2023'
        $dataFim = request('data_fim'); // Exemplo: '11/09/2023'

        // Verifica se ambas as datas foram fornecidas
        if (!empty($dataInicio) && !empty($dataFim)) {
            $dataInicioFormatada = DateTime::createFromFormat('d/m/Y', $dataInicio);
            $dataFimFormatada = DateTime::createFromFormat('d/m/Y', $dataFim);

            // Verifica se as datas convertidas são válidas
            if ($dataInicioFormatada && $dataFimFormatada) {
                $dataInicioFormatada = $dataInicioFormatada->format('Y-m-d');
                $dataFimFormatada = $dataFimFormatada->format('Y-m-d');

                // Certifica-se de que a data de início não é posterior à data de fim
                if ($dataInicioFormatada > $dataFimFormatada) {
                    return response()->json(['error' => 'A data de fim não pode ser anterior à data de início.'], 400);
                }

                // Aplica o filtro de intervalo de datas
                $query->whereBetween('anuncios.created_at', [$dataInicioFormatada, $dataFimFormatada]);
            } else {
                return response()->json(['error' => 'Formato de data inválido.'], 400);
            }
        }

        // Filtro de preço
        if (request()->has(['preco_min', 'preco_max'])) {
            $query->whereBetween('anuncios.valor_preco', [request('preco_min'), request('preco_max')]);
        }

        // Filtro de ano de modelo
        if (request()->has(['ano_modelo_min', 'ano_modelo_max'])) {
            $query->whereBetween('anuncios.ano_modelo', [request('ano_modelo_min'), request('ano_modelo_max')]);
        }

        // Filtro de quilometragem
        if (request()->has(['quilometragem_min', 'quilometragem_max'])) {
            $query->whereBetween('anuncios.km', [request('quilometragem_min'), request('quilometragem_max')]);
        }

        if (request('tipo_veiculo')) {
            $query->where('tipos_veiculos.tipo_veiculo', 'LIKE', '%' . request('tipo_veiculo') . '%');
        }
        if (request('situacao_veiculo')) {
            $query->where('anuncios.situacao_veiculo', 'LIKE', '%' . request('situacao_veiculo') . '%');
        }
        if (request('nome_empresa')) {
            $query->where('anunciantes.nome_empresa', 'LIKE', '%' . request('nome_empresa') . '%');
        }

        if (request('valor_preco')) {
            $query->where('anuncios.valor_preco', request('valor_preco'));
        }
        if (request('ano_modelo')) {
            $query->where('anuncios.ano_modelo', request('ano_modelo'));
        }

        if (request('nome_marca')) {
            $query->where('marcas.nome_marca', request('nome_marca'));
        }

        if (request('nome_modelo')) {
            $query->where('modelos.nome_modelo', 'LIKE', '%' . request('nome_modelo') . '%');
        }
        if (request('nome_anunciante')) {
            $query->where('anunciantes.nome', 'LIKE', '%' . request('nome_anunciante') . '%');
        }
        if (request('estado')) {
            $query->where('estados.estado', request('estado'));
        }
        if (request('tecnologia')) {
            $query->where('tecnologias.tecnologia', request('tecnologia'));
        }
        if (request('cor')) {
            $query->where('cors.cor', request('cor'));
        }
        if (request('transmissao_id')) {
            $query->where('anuncios.transmissao_id', request('transmissao_id'));
        }
        if (request('combustivel_id')) {
            $query->where('anuncios.combustivel_id', request('combustivel_id'));
        }
        if (request('estado')) {
            $query->where('estados.estado', request('estado'));
        }
        if (request('cidade')) {
            $query->where('cidades.cidade', request('cidade'));
        }
        if (request('status_publicacao')) {
            $query->where('anuncios.status_publicacao', request('status_publicacao'));
        }
        if (request('vitrine')) {
            $query->where('anuncios.vitrine', request('vitrine'));
        }

        // Executa a consulta aleatóriamente
        $anuncios = $query->inRandomOrder()->get();
        // Processamento dos dados para personalizar a resposta
        $dadosPersonalizados = [];

        foreach ($anuncios as $anuncio) {

            //Para listar a data formatada
            $dataPublicacao = new DateTime($anuncio->created_at);
            $dataFormatada = $dataPublicacao->format('d/m/Y');

            $dadosPersonalizados[] = [
                'id' => $anuncio->id,
                'tipo_veiculo_id' => $anuncio->tipo_veiculo_id,
                'tipo_veiculo' => $anuncio->tipo_veiculo,
                'tecnologia_id' => $anuncio->tecnologia_id,
                'tecnologia' => $anuncio->tecnologia,
                'id_marca' => $anuncio->id_marcas,
                'nome_marca' => $anuncio->nome_marca,
                'id_modelo' => $anuncio->id_m,
                'nome_modelo' => $anuncio->nome_modelo,
                'numero_cliques' => $anuncio->numero_cliques,
                'situacao_veiculo' => $anuncio->situacao_veiculo,
                'nome_empresa' => $anuncio->nome_empresa,
                'cnpj' => $anuncio->cnpj,
                'telefone' => $anuncio->telefone,
                'celular' => $anuncio->celular,
                'whatsapp' => $anuncio->whatsapp,
                'endereco' => $anuncio->endereco,
                'endereco_comercial' => $anuncio->endereco_comercial,
                'foto_anunciante' => $anuncio->foto_anunciante ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto_anunciante : null,
                'id_anunciante' => $anuncio->id_anunciantes,
                'nome_categoria' => $anuncio->nome_categoria,
                'id_categoria' => $anuncio->id_categoria,
                'data_inicio' => $anuncio->data_inicio,
                'data_fim' => $anuncio->data_fim,
                'ordenacao' => $anuncio->ordenacao,
                'status_publicacao' => $anuncio->status_publicacao,
                'status_pagamento' => $anuncio->status_pagamento,
                'tipo' => $anuncio->tipo,
                'vendido' => $anuncio->vendido,
                'vitrine' => $anuncio->vitrine,
                'destaque_busca' => $anuncio->destaque_busca,
                'estado' => $anuncio->estado,
                'cidade' => $anuncio->cidade,
                'cep' => $anuncio->cep,
                'cep_comercial' => $anuncio->cep_comercial,
                'tipo_preco' => $anuncio->tipo_preco,
                'valor_preco' => $anuncio->valor_preco,
                'mostrar_preco' => $anuncio->mostrar_preco,
                'fabricante_id' => $anuncio->fabricante_id,
                'fabricante' => $anuncio->fabricante,
                'ano_fabricacao' => $anuncio->ano_fabricacao,
                'ano_modelo' => $anuncio->ano_modelo,
                'carroceria' => $anuncio->carroceria,
                'estilo' => $anuncio->estilo,
                'portas' => $anuncio->portas,
                'cilindros' => $anuncio->cilindros,
                'motor' => $anuncio->motor,
                'cor_id' => $anuncio->cor_id,
                'cor' => $anuncio->cor,
                'transmissao_id' => $anuncio->transmissao_id,
                'transmissao' => $anuncio->transmissao,
                'combustivel_id' => $anuncio->combustivel_id,
                'combustivel' => $anuncio->combustivel,
                'placa' => $anuncio->placa,
                'km' => $anuncio->km,
                'sinistrado' => $anuncio->sinistrado,
                'opcionais_id' => $anuncio->opcionais_id,
                'descricao' => $anuncio->descricao,
                'data_publicacao' => $dataFormatada,
                'foto1' => $anuncio->foto1 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto1 : null,
                'foto2' => $anuncio->foto2 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto2 : null,
                'foto3' => $anuncio->foto3 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto3 : null,
                'foto4' => $anuncio->foto4 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto4 : null,
                'foto5' => $anuncio->foto5 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto5 : null,
                'foto6' => $anuncio->foto6 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto6 : null,
                'foto7' => $anuncio->foto7 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto7 : null,
                'foto8' => $anuncio->foto8 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto8 : null,
                'foto9' => $anuncio->foto9 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto9 : null,
                'foto10' => $anuncio->foto10 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto10 : null,
                // Adicione mais campos personalizados conforme necessário
            ];
        }
        // Retorna a resposta JSON com os dados personalizados
        return response()->json($dadosPersonalizados);
    }
    public function indexVetrine()
    {
        // Começa a construir a consulta ao banco de dados
        $query = DB::table('anuncios')
            ->join('marcas', 'marcas.id', 'anuncios.marca_id')
            ->join('modelos', 'modelos.id', 'anuncios.modelo_id')
            ->join('categorias', 'categorias.id', 'anuncios.categoria_id')
            ->join('anunciantes', 'anunciantes.id', 'anuncios.anunciante_id')
            ->join('tipos_veiculos', 'tipos_veiculos.id', 'anuncios.tipo_veiculo_id')
            ->join('tecnologias', 'tecnologias.id', 'anuncios.tecnologia_id')
            ->join('cors', 'cors.id', 'anuncios.cor_id')
            ->join('fabricantes', 'fabricantes.id', 'anuncios.fabricante_id')
            ->join('transmissaos', 'transmissaos.id', 'anuncios.transmissao_id')
            ->join('combustivels', 'combustivels.id', 'anuncios.combustivel_id')
            ->join('estados', 'estados.id', 'anunciantes.estado_id') // Junção com a tabela de estados
            ->join('cidades', 'cidades.id', 'anunciantes.cidade_id') // Junção com a tabela de estados
            //  ->join('planos_anunciantes', 'planos_anunciantes.anunciante_id', 'anunciantes.id')
            ->select(
                'anuncios.*',
                'marcas.nome_marca',
                'marcas.id as id_marcas',
                'modelos.nome_modelo',
                'modelos.id as id_m',
                'categorias.nome as nome_categoria',
                'categorias.id as id_categoria',
                'anunciantes.nome_empresa',
                'anunciantes.cnpj',
                'anunciantes.telefone',
                'anunciantes.celular',
                'anunciantes.whatsapp',
                'anunciantes.endereco',
                'anunciantes.estado_id',
                'estados.estado', // Selecionando o nome do estado
                'cidades.cidade', // Selecionando o nome do Cidade
                'anunciantes.cidade_id',
                'anunciantes.cep',
                'anunciantes.cep_comercial',
                'anunciantes.foto as foto_anunciante',
                'anunciantes.endereco_comercial',
                'anunciantes.id as id_anunciantes',
                'tipos_veiculos.tipo_veiculo',
                'fabricantes.fabricante',
                'tipos_veiculos.id as id_tipos_veiculo',
                'tipos_veiculos.tipo_veiculo',
                'cors.cor',
                'cors.id as id_cor',
                'tecnologias.tecnologia',
                'tecnologias.id as idtecnologia',
                'combustivels.combustivel',
                'combustivels.id as id_combustivel',
                'transmissaos.transmissao',
                'transmissaos.id as id_transmissao',
            )
            ->where('anuncios.vitrine', '=', 1)
            ->where('anuncios.status_publicacao', '=', 2)
            ->where('anunciantes.status', '=', 1) // Filtra apenas anúncios na vitrine
            ->inRandomOrder() // Ordenação aleatória dos resultados
            ->limit(12);


        // Obtém as datas de início e fim da requisição, se fornecidas
        $dataInicio = request('data_inicio'); // Exemplo: '12/06/2023'
        $dataFim = request('data_fim'); // Exemplo: '11/09/2023'

        // Verifica se ambas as datas foram fornecidas
        if (!empty($dataInicio) && !empty($dataFim)) {
            $dataInicioFormatada = DateTime::createFromFormat('d/m/Y', $dataInicio);
            $dataFimFormatada = DateTime::createFromFormat('d/m/Y', $dataFim);

            // Verifica se as datas convertidas são válidas
            if ($dataInicioFormatada && $dataFimFormatada) {
                $dataInicioFormatada = $dataInicioFormatada->format('Y-m-d');
                $dataFimFormatada = $dataFimFormatada->format('Y-m-d');

                // Certifica-se de que a data de início não é posterior à data de fim
                if ($dataInicioFormatada > $dataFimFormatada) {
                    return response()->json(['error' => 'A data de fim não pode ser anterior à data de início.'], 400);
                }

                // Aplica o filtro de intervalo de datas
                $query->whereBetween('anuncios.created_at', [$dataInicioFormatada, $dataFimFormatada]);
            } else {
                return response()->json(['error' => 'Formato de data inválido.'], 400);
            }
        }

        // Filtro de preço
        if (request()->has(['preco_min', 'preco_max'])) {
            $query->whereBetween('anuncios.valor_preco', [request('preco_min'), request('preco_max')]);
        }

        // Filtro de ano de modelo
        if (request()->has(['ano_modelo_min', 'ano_modelo_max'])) {
            $query->whereBetween('anuncios.ano_modelo', [request('ano_modelo_min'), request('ano_modelo_max')]);
        }

        // Filtro de quilometragem
        if (request()->has(['quilometragem_min', 'quilometragem_max'])) {
            $query->whereBetween('anuncios.km', [request('quilometragem_min'), request('quilometragem_max')]);
        }

        if (request('tipo_veiculo')) {
            $query->where('tipos_veiculos.tipo_veiculo', 'LIKE', '%' . request('tipo_veiculo') . '%');
        }
        if (request('situacao_veiculo')) {
            $query->where('anuncios.situacao_veiculo', 'LIKE', '%' . request('situacao_veiculo') . '%');
        }
        if (request('nome_empresa')) {
            $query->where('anunciantes.nome_empresa', 'LIKE', '%' . request('nome_empresa') . '%');
        }

        if (request('valor_preco')) {
            $query->where('anuncios.valor_preco', request('valor_preco'));
        }
        if (request('ano_modelo')) {
            $query->where('anuncios.ano_modelo', request('ano_modelo'));
        }

        if (request('nome_marca')) {
            $query->where('marcas.nome_marca', request('nome_marca'));
        }

        if (request('nome_modelo')) {
            $query->where('modelos.nome_modelo', 'LIKE', '%' . request('nome_modelo') . '%');
        }
        if (request('nome_anunciante')) {
            $query->where('anunciantes.nome', 'LIKE', '%' . request('nome_anunciante') . '%');
        }
        if (request('estado')) {
            $query->where('estados.estado', request('estado'));
        }
        if (request('tecnologia')) {
            $query->where('tecnologias.tecnologia', request('tecnologia'));
        }
        if (request('cor')) {
            $query->where('cors.cor', request('cor'));
        }
        if (request('transmissao_id')) {
            $query->where('anuncios.transmissao_id', request('transmissao_id'));
        }
        if (request('combustivel_id')) {
            $query->where('anuncios.combustivel_id', request('combustivel_id'));
        }
        if (request('estado')) {
            $query->where('estados.estado', request('estado'));
        }
        if (request('cidade')) {
            $query->where('cidades.cidade', request('cidade'));
        }
        if (request('status_publicacao')) {
            $query->where('anuncios.status_publicacao', request('status_publicacao'));
        }
        if (request('vitrine')) {
            $query->where('anuncios.vitrine', request('vitrine'));
        }

        // Executa a consulta aleatóriamente
        $anuncios = $query->inRandomOrder()->get();
        // Processamento dos dados para personalizar a resposta
        $dadosPersonalizados = [];

        foreach ($anuncios as $anuncio) {

            //Para listar a data formatada
            $dataPublicacao = new DateTime($anuncio->created_at);
            $dataFormatada = $dataPublicacao->format('d/m/Y');

            $dadosPersonalizados[] = [
                'id' => $anuncio->id,
                'tipo_veiculo_id' => $anuncio->tipo_veiculo_id,
                'tipo_veiculo' => $anuncio->tipo_veiculo,
                'tecnologia_id' => $anuncio->tecnologia_id,
                'tecnologia' => $anuncio->tecnologia,
                'id_marca' => $anuncio->id_marcas,
                'nome_marca' => $anuncio->nome_marca,
                'id_modelo' => $anuncio->id_m,
                'nome_modelo' => $anuncio->nome_modelo,
                'numero_cliques' => $anuncio->numero_cliques,
                'situacao_veiculo' => $anuncio->situacao_veiculo,
                'nome_empresa' => $anuncio->nome_empresa,
                'cnpj' => $anuncio->cnpj,
                'telefone' => $anuncio->telefone,
                'celular' => $anuncio->celular,
                'whatsapp' => $anuncio->whatsapp,
                'endereco' => $anuncio->endereco,
                'endereco_comercial' => $anuncio->endereco_comercial,
                'foto_anunciante' => $anuncio->foto_anunciante ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto_anunciante : null,
                'id_anunciante' => $anuncio->id_anunciantes,
                'nome_categoria' => $anuncio->nome_categoria,
                'id_categoria' => $anuncio->id_categoria,
                'data_inicio' => $anuncio->data_inicio,
                'data_fim' => $anuncio->data_fim,
                'ordenacao' => $anuncio->ordenacao,
                'status_publicacao' => $anuncio->status_publicacao,
                'status_pagamento' => $anuncio->status_pagamento,
                'tipo' => $anuncio->tipo,
                'vendido' => $anuncio->vendido,
                'vitrine' => $anuncio->vitrine,
                'destaque_busca' => $anuncio->destaque_busca,
                'estado' => $anuncio->estado,
                'cidade' => $anuncio->cidade,
                'cep' => $anuncio->cep,
                'cep_comercial' => $anuncio->cep_comercial,
                'tipo_preco' => $anuncio->tipo_preco,
                'valor_preco' => $anuncio->valor_preco,
                'mostrar_preco' => $anuncio->mostrar_preco,
                'fabricante_id' => $anuncio->fabricante_id,
                'fabricante' => $anuncio->fabricante,
                'ano_fabricacao' => $anuncio->ano_fabricacao,
                'ano_modelo' => $anuncio->ano_modelo,
                'carroceria' => $anuncio->carroceria,
                'estilo' => $anuncio->estilo,
                'portas' => $anuncio->portas,
                'cilindros' => $anuncio->cilindros,
                'motor' => $anuncio->motor,
                'cor_id' => $anuncio->cor_id,
                'cor' => $anuncio->cor,
                'transmissao_id' => $anuncio->transmissao_id,
                'transmissao' => $anuncio->transmissao,
                'combustivel_id' => $anuncio->combustivel_id,
                'combustivel' => $anuncio->combustivel,
                'placa' => $anuncio->placa,
                'km' => $anuncio->km,
                'sinistrado' => $anuncio->sinistrado,
                'opcionais_id' => $anuncio->opcionais_id,
                'descricao' => $anuncio->descricao,
                'data_publicacao' => $dataFormatada,
                'foto1' => $anuncio->foto1 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto1 : null,
                'foto2' => $anuncio->foto2 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto2 : null,
                'foto3' => $anuncio->foto3 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto3 : null,
                'foto4' => $anuncio->foto4 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto4 : null,
                'foto5' => $anuncio->foto5 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto5 : null,
                'foto6' => $anuncio->foto6 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto6 : null,
                'foto7' => $anuncio->foto7 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto7 : null,
                'foto8' => $anuncio->foto8 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto8 : null,
                'foto9' => $anuncio->foto9 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto9 : null,
                'foto10' => $anuncio->foto10 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto10 : null,
                // Adicione mais campos personalizados conforme necessário
            ];
        }
        // Retorna a resposta JSON com os dados personalizados
        return response()->json($dadosPersonalizados);
    }

    //Método para o super usuário
    public function anuncioAdmin()
    {
        // Obter o ID do usuário logado
        $userId = Auth::id();  // Assume que você está usando o guard padrão do Laravel

        // Começa a construir a consulta ao banco de dados
        $query = DB::table('anuncios')
            ->join('marcas', 'marcas.id', 'anuncios.marca_id')
            ->join('modelos', 'modelos.id', 'anuncios.modelo_id')
            ->join('categorias', 'categorias.id', 'anuncios.categoria_id')
            ->join('anunciantes', 'anunciantes.id', 'anuncios.anunciante_id')
            ->join('tipos_veiculos', 'tipos_veiculos.id', 'anuncios.tipo_veiculo_id')
            ->join('tecnologias', 'tecnologias.id', 'anuncios.tecnologia_id')
            ->join('cors', 'cors.id', 'anuncios.cor_id')
            ->join('fabricantes', 'fabricantes.id', 'anuncios.fabricante_id')
            ->join('transmissaos', 'transmissaos.id', 'anuncios.transmissao_id')
            ->join('combustivels', 'combustivels.id', 'anuncios.combustivel_id')
            ->join('estados', 'estados.id', 'anunciantes.estado_id')
            ->join('cidades', 'cidades.id', 'anunciantes.cidade_id')
            ->where('anunciantes.usuario_id', $userId)  // Filtra os anúncios pelo ID do usuário logado vinculado ao anunciante
            ->select(
                'anuncios.*',
                'marcas.nome_marca',
                'marcas.id as id_marcas',
                'modelos.nome_modelo',
                'modelos.id as id_m',
                'categorias.nome as nome_categoria',
                'categorias.id as id_categoria',
                'anunciantes.nome_empresa',
                'anunciantes.cnpj',
                'anunciantes.telefone',
                'anunciantes.celular',
                'anunciantes.whatsapp',
                'anunciantes.endereco',
                'anunciantes.estado_id',
                'estados.estado', // Selecionando o nome do estado
                'cidades.cidade', // Selecionando o nome do Cidade
                'anunciantes.cidade_id',
                'anunciantes.cep',
                'anunciantes.cep_comercial',
                'anunciantes.foto as foto_anunciante',
                'anunciantes.endereco_comercial',
                'anunciantes.id as id_anunciantes',
                'tipos_veiculos.tipo_veiculo',
                'fabricantes.fabricante',
                'tipos_veiculos.id as id_tipos_veiculo',
                'tipos_veiculos.tipo_veiculo',
                'cors.cor',
                'cors.id as id_cor',
                'tecnologias.tecnologia',
                'tecnologias.id as idtecnologia',
                'combustivels.combustivel',
                'combustivels.id as id_combustivel',
                'transmissaos.transmissao',
                'transmissaos.id as id_transmissao',
            );



        // Obtém as datas de início e fim da requisição, se fornecidas
        $dataInicio = request('data_inicio'); // Exemplo: '12/06/2023'
        $dataFim = request('data_fim'); // Exemplo: '11/09/2023'

        // Verifica se ambas as datas foram fornecidas
        if (!empty($dataInicio) && !empty($dataFim)) {
            $dataInicioFormatada = DateTime::createFromFormat('d/m/Y', $dataInicio);
            $dataFimFormatada = DateTime::createFromFormat('d/m/Y', $dataFim);

            // Verifica se as datas convertidas são válidas
            if ($dataInicioFormatada && $dataFimFormatada) {
                $dataInicioFormatada = $dataInicioFormatada->format('Y-m-d');
                $dataFimFormatada = $dataFimFormatada->format('Y-m-d');

                // Certifica-se de que a data de início não é posterior à data de fim
                if ($dataInicioFormatada > $dataFimFormatada) {
                    return response()->json(['error' => 'A data de fim não pode ser anterior à data de início.'], 400);
                }

                // Aplica o filtro de intervalo de datas
                $query->whereBetween('anuncios.created_at', [$dataInicioFormatada, $dataFimFormatada]);
            } else {
                return response()->json(['error' => 'Formato de data inválido.'], 400);
            }
        }

        // Filtro de preço
        if (request()->has(['preco_min', 'preco_max'])) {
            $query->whereBetween('anuncios.valor_preco', [request('preco_min'), request('preco_max')]);
        }

        // Filtro de ano de modelo
        if (request()->has(['ano_modelo_min', 'ano_modelo_max'])) {
            $query->whereBetween('anuncios.ano_modelo', [request('ano_modelo_min'), request('ano_modelo_max')]);
        }

        // Filtro de quilometragem
        if (request()->has(['quilometragem_min', 'quilometragem_max'])) {
            $query->whereBetween('anuncios.km', [request('quilometragem_min'), request('quilometragem_max')]);
        }

        if (request('tipo_veiculo')) {
            $query->where('tipos_veiculos.tipo_veiculo', 'LIKE', '%' . request('tipo_veiculo') . '%');
        }
        if (request('situacao_veiculo')) {
            $query->where('anuncios.situacao_veiculo', 'LIKE', '%' . request('situacao_veiculo') . '%');
        }
        if (request('nome_empresa')) {
            $query->where('anunciantes.nome_empresa', 'LIKE', '%' . request('nome_empresa') . '%');
        }

        if (request('valor_preco')) {
            $query->where('anuncios.valor_preco', request('valor_preco'));
        }
        if (request('ano_modelo')) {
            $query->where('anuncios.ano_modelo', request('ano_modelo'));
        }

        if (request('nome_marca')) {
            $query->where('marcas.nome_marca', request('nome_marca'));
        }

        if (request('nome_modelo')) {
            $query->where('modelos.nome_modelo', 'LIKE', '%' . request('nome_modelo') . '%');
        }
        if (request('nome_anunciante')) {
            $query->where('anunciantes.nome', 'LIKE', '%' . request('nome_anunciante') . '%');
        }
        if (request('estado')) {
            $query->where('estados.estado', request('estado'));
        }
        if (request('tecnologia')) {
            $query->where('tecnologias.tecnologia', request('tecnologia'));
        }
        if (request('cor')) {
            $query->where('cors.cor', request('cor'));
        }
        if (request('transmissao_id')) {
            $query->where('anuncios.transmissao_id', request('transmissao_id'));
        }
        if (request('combustivel_id')) {
            $query->where('anuncios.combustivel_id', request('combustivel_id'));
        }
        if (request('estado')) {
            $query->where('estados.estado', request('estado'));
        }
        if (request('cidade')) {
            $query->where('cidades.cidade', request('cidade'));
        }
        if (request('status_publicacao')) {
            $query->where('anuncios.status_publicacao', request('status_publicacao'));
        }
        if (request('vitrine')) {
            $query->where('anuncios.vitrine', request('vitrine'));
        }
        // Executa a consulta
        $anuncios = $query->get();

        // Processamento dos dados para personalizar a resposta
        $dadosPersonalizados = $anuncios->map(function ($anuncio) {
            return [
                'id' => $anuncio->id,
                'tipo_veiculo_id' => $anuncio->tipo_veiculo_id,
                'tipo_veiculo' => $anuncio->tipo_veiculo,
                'tecnologia_id' => $anuncio->tecnologia_id,
                'tecnologia' => $anuncio->tecnologia,
                'id_marca' => $anuncio->id_marcas,
                'nome_marca' => $anuncio->nome_marca,
                'id_modelo' => $anuncio->id_m,
                'nome_modelo' => $anuncio->nome_modelo,
                'numero_cliques' => $anuncio->numero_cliques,
                'situacao_veiculo' => $anuncio->situacao_veiculo,
                'nome_empresa' => $anuncio->nome_empresa,
                'cnpj' => $anuncio->cnpj,
                'telefone' => $anuncio->telefone,
                'celular' => $anuncio->celular,
                'whatsapp' => $anuncio->whatsapp,
                'endereco' => $anuncio->endereco,
                'endereco_comercial' => $anuncio->endereco_comercial,
                'foto_anunciante' => $anuncio->foto_anunciante ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto_anunciante : null,
                'id_anunciante' => $anuncio->id_anunciantes,
                'nome_categoria' => $anuncio->nome_categoria,
                'id_categoria' => $anuncio->id_categoria,
                'data_inicio' => $anuncio->data_inicio,
                'data_fim' => $anuncio->data_fim,
                'ordenacao' => $anuncio->ordenacao,
                'status_publicacao' => $anuncio->status_publicacao,
                'status_pagamento' => $anuncio->status_pagamento,
                'tipo' => $anuncio->tipo,
                'vendido' => $anuncio->vendido,
                'vitrine' => $anuncio->vitrine,
                'destaque_busca' => $anuncio->destaque_busca,
                //'estado_id' => $anuncio->estado_id,
                'estado' => $anuncio->estado,
                //'id_cidade' => $anuncio->cidade_id,
                'cidade' => $anuncio->cidade,
                'cep' => $anuncio->cep,
                'cep_comercial' => $anuncio->cep_comercial,
                'tipo_preco' => $anuncio->tipo_preco,
                'valor_preco' => $anuncio->valor_preco,
                'mostrar_preco' => $anuncio->mostrar_preco,
                'fabricante_id' => $anuncio->fabricante_id,
                'fabricante' => $anuncio->fabricante,
                'ano_fabricacao' => $anuncio->ano_fabricacao,
                'ano_modelo' => $anuncio->ano_modelo,
                'carroceria' => $anuncio->carroceria,
                'estilo' => $anuncio->estilo,
                'portas' => $anuncio->portas,
                'cilindros' => $anuncio->cilindros,
                'motor' => $anuncio->motor,
                'cor_id' => $anuncio->cor_id,
                'cor' => $anuncio->cor,
                'transmissao_id' => $anuncio->transmissao_id,
                'transmissao' => $anuncio->transmissao,
                'combustivel_id' => $anuncio->combustivel_id,
                'combustivel' => $anuncio->combustivel,
                'placa' => $anuncio->placa,
                'km' => $anuncio->km,
                'sinistrado' => $anuncio->sinistrado,
                //'conforto_id' => $anuncio->conforto_id,
                //'seguranca_id' => $anuncio->seguranca_id,
                'opcionais_id' => $anuncio->opcionais_id,
                'descricao' => $anuncio->descricao,
                'foto1' => $anuncio->foto1 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto1 : null,
                'foto2' => $anuncio->foto2 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto2 : null,
                'foto3' => $anuncio->foto3 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto3 : null,
                'foto4' => $anuncio->foto4 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto4 : null,
                'foto5' => $anuncio->foto5 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto5 : null,
                'foto6' => $anuncio->foto6 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto6 : null,
                'foto7' => $anuncio->foto7 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto7 : null,
                'foto8' => $anuncio->foto8 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto8 : null,
                'foto9' => $anuncio->foto9 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto9 : null,
                'foto10' => $anuncio->foto10 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto10 : null,
            ];
        });

        // Retorna a resposta JSON com os dados personalizados
        return response()->json($dadosPersonalizados);
    }

    //Método do Super Usuário
    public function anuncioAdminSuper()
    {
        // Começa a construir a consulta ao banco de dados
        $query = DB::table('anuncios')
            ->join('marcas', 'marcas.id', 'anuncios.marca_id')
            ->join('modelos', 'modelos.id', 'anuncios.modelo_id')
            ->join('categorias', 'categorias.id', 'anuncios.categoria_id')
            ->join('anunciantes', 'anunciantes.id', 'anuncios.anunciante_id')
            ->join('tipos_veiculos', 'tipos_veiculos.id', 'anuncios.tipo_veiculo_id')
            ->join('tecnologias', 'tecnologias.id', 'anuncios.tecnologia_id')
            ->join('cors', 'cors.id', 'anuncios.cor_id')
            ->join('fabricantes', 'fabricantes.id', 'anuncios.fabricante_id')
            ->join('transmissaos', 'transmissaos.id', 'anuncios.transmissao_id')
            ->join('combustivels', 'combustivels.id', 'anuncios.combustivel_id')
            ->join('estados', 'estados.id', 'anunciantes.estado_id') // Junção com a tabela de estados
            ->join('cidades', 'cidades.id', 'anunciantes.cidade_id') // Junção com a tabela de estados
            //->join('planos_anunciantes', 'planos_anunciantes.anunciante_id', 'anunciantes.id')
            ->select(
                'anuncios.*',
                'marcas.nome_marca',
                'marcas.id as id_marcas',
                'modelos.nome_modelo',
                'modelos.id as id_m',
                'categorias.nome as nome_categoria',
                'categorias.id as id_categoria',
                'anunciantes.nome_empresa',
                'anunciantes.cnpj',
                'anunciantes.telefone',
                'anunciantes.celular',
                'anunciantes.whatsapp',
                'anunciantes.endereco',
                'anunciantes.estado_id',
                'estados.estado', // Selecionando o nome do estado
                'cidades.cidade', // Selecionando o nome do Cidade
                'anunciantes.cidade_id',
                'anunciantes.cep',
                'anunciantes.cep_comercial',
                'anunciantes.foto as foto_anunciante',
                'anunciantes.endereco_comercial',
                'anunciantes.id as id_anunciantes',
                'tipos_veiculos.tipo_veiculo',
                'fabricantes.fabricante',
                'tipos_veiculos.id as id_tipos_veiculo',
                'tipos_veiculos.tipo_veiculo',
                'cors.cor',
                'cors.id as id_cor',
                'tecnologias.tecnologia',
                'tecnologias.id as idtecnologia',
                'combustivels.combustivel',
                'combustivels.id as id_combustivel',
                'transmissaos.transmissao',
                'transmissaos.id as id_transmissao',
                //'planos_anunciantes.*'
            );

        // Adiciona os filtros conforme os parâmetros passados


        // Obtém as datas de início e fim da requisição, se fornecidas
        $dataInicio = request('data_inicio'); // Exemplo: '12/06/2023'
        $dataFim = request('data_fim'); // Exemplo: '11/09/2023'

        // Verifica se ambas as datas foram fornecidas
        if (!empty($dataInicio) && !empty($dataFim)) {
            $dataInicioFormatada = DateTime::createFromFormat('d/m/Y', $dataInicio);
            $dataFimFormatada = DateTime::createFromFormat('d/m/Y', $dataFim);

            // Verifica se as datas convertidas são válidas
            if ($dataInicioFormatada && $dataFimFormatada) {
                $dataInicioFormatada = $dataInicioFormatada->format('Y-m-d');
                $dataFimFormatada = $dataFimFormatada->format('Y-m-d');

                // Certifica-se de que a data de início não é posterior à data de fim
                if ($dataInicioFormatada > $dataFimFormatada) {
                    return response()->json(['error' => 'A data de fim não pode ser anterior à data de início.'], 400);
                }

                // Aplica o filtro de intervalo de datas
                $query->whereBetween('anuncios.created_at', [$dataInicioFormatada, $dataFimFormatada]);
            } else {
                return response()->json(['error' => 'Formato de data inválido.'], 400);
            }
        }

        // Filtro de preço
        if (request()->has(['preco_min', 'preco_max'])) {
            $query->whereBetween('anuncios.valor_preco', [request('preco_min'), request('preco_max')]);
        }

        // Filtro de ano de modelo
        if (request()->has(['ano_modelo_min', 'ano_modelo_max'])) {
            $query->whereBetween('anuncios.ano_modelo', [request('ano_modelo_min'), request('ano_modelo_max')]);
        }

        // Filtro de quilometragem
        if (request()->has(['quilometragem_min', 'quilometragem_max'])) {
            $query->whereBetween('anuncios.km', [request('quilometragem_min'), request('quilometragem_max')]);
        }

        if (request('tipo_veiculo')) {
            $query->where('tipos_veiculos.tipo_veiculo', 'LIKE', '%' . request('tipo_veiculo') . '%');
        }
        if (request('situacao_veiculo')) {
            $query->where('anuncios.situacao_veiculo', 'LIKE', '%' . request('situacao_veiculo') . '%');
        }
        if (request('nome_empresa')) {
            $query->where('anunciantes.nome_empresa', 'LIKE', '%' . request('nome_empresa') . '%');
        }

        if (request('valor_preco')) {
            $query->where('anuncios.valor_preco', request('valor_preco'));
        }
        if (request('ano_modelo')) {
            $query->where('anuncios.ano_modelo', request('ano_modelo'));
        }

        if (request('nome_marca')) {
            $query->where('marcas.nome_marca', request('nome_marca'));
        }

        if (request('nome_modelo')) {
            $query->where('modelos.nome_modelo', 'LIKE', '%' . request('nome_modelo') . '%');
        }
        if (request('nome_anunciante')) {
            $query->where('anunciantes.nome', 'LIKE', '%' . request('nome_anunciante') . '%');
        }
        if (request('estado')) {
            $query->where('estados.estado', request('estado'));
        }
        if (request('tecnologia')) {
            $query->where('tecnologias.tecnologia', request('tecnologia'));
        }
        if (request('cor')) {
            $query->where('cors.cor', request('cor'));
        }
        if (request('transmissao_id')) {
            $query->where('anuncios.transmissao_id', request('transmissao_id'));
        }
        if (request('combustivel_id')) {
            $query->where('anuncios.combustivel_id', request('combustivel_id'));
        }
        if (request('estado')) {
            $query->where('estados.estado', request('estado'));
        }
        if (request('cidade')) {
            $query->where('cidades.cidade', request('cidade'));
        }
        if (request('status_publicacao')) {
            $query->where('anuncios.status_publicacao', request('status_publicacao'));
        }
        if (request('vitrine')) {
            $query->where('anuncios.vitrine', request('vitrine'));
        }

        // Executa a consulta aleatóriamente
        $anuncios = $query->get();
        // Processamento dos dados para personalizar a resposta
        $dadosPersonalizados = [];
        foreach ($anuncios as $anuncio) {
            //Para listar a data formatada
            $dataPublicacao = new DateTime($anuncio->created_at);
            $dataFormatada = $dataPublicacao->format('d/m/Y');
            $dadosPersonalizados[] = [
                'id' => $anuncio->id,
                'tipo_veiculo_id' => $anuncio->tipo_veiculo_id,
                'tipo_veiculo' => $anuncio->tipo_veiculo,
                'tecnologia_id' => $anuncio->tecnologia_id,
                'tecnologia' => $anuncio->tecnologia,
                'id_marca' => $anuncio->id_marcas,
                'nome_marca' => $anuncio->nome_marca,
                'id_modelo' => $anuncio->id_m,
                'nome_modelo' => $anuncio->nome_modelo,
                'numero_cliques' => $anuncio->numero_cliques,
                'situacao_veiculo' => $anuncio->situacao_veiculo,
                'nome_empresa' => $anuncio->nome_empresa,
                'cnpj' => $anuncio->cnpj,
                'telefone' => $anuncio->telefone,
                'celular' => $anuncio->celular,
                'whatsapp' => $anuncio->whatsapp,
                'endereco' => $anuncio->endereco,
                'endereco_comercial' => $anuncio->endereco_comercial,
                'foto_anunciante' => $anuncio->foto_anunciante ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto_anunciante : null,
                'id_anunciante' => $anuncio->id_anunciantes,
                'nome_categoria' => $anuncio->nome_categoria,
                'id_categoria' => $anuncio->id_categoria,
                'data_inicio' => $anuncio->data_inicio,
                'data_fim' => $anuncio->data_fim,
                'ordenacao' => $anuncio->ordenacao,
                'status_publicacao' => $anuncio->status_publicacao,
                'status_pagamento' => $anuncio->status_pagamento,
                'tipo' => $anuncio->tipo,
                'vendido' => $anuncio->vendido,
                'vitrine' => $anuncio->vitrine,
                'destaque_busca' => $anuncio->destaque_busca,
                //'estado_id' => $anuncio->estado_id,
                'estado' => $anuncio->estado,
                //'id_cidade' => $anuncio->cidade_id,
                'cidade' => $anuncio->cidade,
                'cep' => $anuncio->cep,
                'cep_comercial' => $anuncio->cep_comercial,
                'tipo_preco' => $anuncio->tipo_preco,
                'valor_preco' => $anuncio->valor_preco,
                'mostrar_preco' => $anuncio->mostrar_preco,
                'fabricante_id' => $anuncio->fabricante_id,
                'fabricante' => $anuncio->fabricante,
                'ano_fabricacao' => $anuncio->ano_fabricacao,
                'ano_modelo' => $anuncio->ano_modelo,
                'carroceria' => $anuncio->carroceria,
                'estilo' => $anuncio->estilo,
                'portas' => $anuncio->portas,
                'cilindros' => $anuncio->cilindros,
                'motor' => $anuncio->motor,
                'cor_id' => $anuncio->cor_id,
                'cor' => $anuncio->cor,
                'transmissao_id' => $anuncio->transmissao_id,
                'transmissao' => $anuncio->transmissao,
                'combustivel_id' => $anuncio->combustivel_id,
                'combustivel' => $anuncio->combustivel,
                'placa' => $anuncio->placa,
                'km' => $anuncio->km,
                'sinistrado' => $anuncio->sinistrado,
                //'conforto_id' => $anuncio->conforto_id,
                //'seguranca_id' => $anuncio->seguranca_id,
                'opcionais_id' => $anuncio->opcionais_id,
                'descricao' => $anuncio->descricao,
                'data_publicacao' => $dataFormatada,
                'foto1' => $anuncio->foto1 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto1 : null,
                'foto2' => $anuncio->foto2 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto2 : null,
                'foto3' => $anuncio->foto3 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto3 : null,
                'foto4' => $anuncio->foto4 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto4 : null,
                'foto5' => $anuncio->foto5 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto5 : null,
                'foto6' => $anuncio->foto6 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto6 : null,
                'foto7' => $anuncio->foto7 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto7 : null,
                'foto8' => $anuncio->foto8 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto8 : null,
                'foto9' => $anuncio->foto9 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto9 : null,
                'foto10' => $anuncio->foto10 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto10 : null,
                // Adicione mais campos personalizados conforme necessário
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
        $tipo_veiculo = TiposVeiculos::find($request->tipo_veiculo);
        $tecnologia = Tecnologia::find($request->tecnologia);
        $modelo = Modelos::find($request->modelo_id);
        $marca = Marcas::find($request->marca_id);
        $anunciante = Anunciantes::find($request->anunciante_id);
        $categoria = Categorias::find($request->categoria_id);
        $fabricante = Fabricantes::find($request->fabricante_id);
        $cor = Cor::find($request->cor);
        $transmissao = Transmissao::find($request->transmissao);
        $combustivel = Combustivel::find($request->combustivel);

        $plano_anunciante = PlanosAnunciantes::where('anunciante_id', '=', $request->anunciante_id)->first();

        if (!$plano_anunciante) {
            return response(['message' => 'O Anunciante selecionado não possui um plano'], 404);
        }

        //return $plano_anunciante->anuncio_restante;

        if ($plano_anunciante->anuncio_restante == 0) {
            return response(['message' => 'O teu plano não possui anuncios restantes'], 404);
        }

        if (!$tipo_veiculo) {
            return response(['message' => 'O tipo de veiculo selecionado não existe'], 404);
        }
        if (!$tecnologia) {
            return response(['message' => 'A tecnologia selecionada não existe'], 404);
        }
        if (!$marca) {
            return response(['message' => 'A marca selecionada não existe'], 404);
        }

        if (!$modelo) {
            return response(['message' => 'O modelo selecionado não existe'], 404);
        }
        if (!$anunciante) {
            return response(['message' => 'O Anunciante selecionado não existe'], 404);
        }

        if (!$categoria) {
            return response(['message' => 'A Categoria selecionada não existe'], 404);
        }

        if (!$fabricante) {
            return response(['message' => 'O Fabricante selecionado não existe'], 404);
        }
        if (!$cor) {
            return response(['message' => 'A Cor selecionada não existe'], 404);
        }
        if (!$transmissao) {
            return response(['message' => 'A Transmissão selecionada não existe'], 404);
        }
        if (!$combustivel) {
            return response(['message' => 'O Combustível selecionado não existe'], 404);
        }

        $anuncios = new Anuncios;
        $anuncios->titulo = $request->titulo;
        $anuncios->tipo_veiculo_id = $request->tipo_veiculo;
        $anuncios->tecnologia_id = $request->tecnologia;
        $anuncios->marca_id = $request->marca_id;
        $anuncios->modelo_id = $request->modelo_id;
        $anuncios->numero_cliques = 0; // Definindo o valor padrão como zero
        $anuncios->situacao_veiculo = $request->situacao_veiculo;
        $anuncios->anunciante_id = $request->anunciante_id;
        $anuncios->categoria_id = $request->categoria_id;
        $anuncios->data_inicio = $request->data_inicio;
        $anuncios->data_fim = $request->data_fim;
        $anuncios->ordenacao = $request->ordenacao;
        $anuncios->status_publicacao = $request->status_publicacao;
        $anuncios->status_pagamento = $request->status_pagamento;
        $anuncios->tipo = $request->tipo;
        $anuncios->vendido = $request->vendido;
        $anuncios->vitrine = $request->vitrine;
        $anuncios->destaque_busca = $request->destaque_busca;
        $anuncios->tipo_preco = $request->tipo_preco;
        $anuncios->valor_preco = $request->valor_preco;
        $anuncios->mostrar_preco = $request->mostrar_preco;
        $anuncios->fabricante_id = $request->fabricante_id;
        $anuncios->ano_fabricacao = $request->ano_fabricacao;
        $anuncios->ano_modelo = $request->ano_modelo;
        $anuncios->carroceria = $request->carroceria;
        $anuncios->estilo = $request->estilo;
        $anuncios->portas = $request->portas;
        $anuncios->cilindros = $request->cilindros;
        $anuncios->motor = $request->motor;
        $anuncios->cor_id = $request->cor;
        $anuncios->transmissao_id = $request->transmissao;
        //$anuncios->conforto_id = $request->conforto_id;
        //$anuncios->seguranca_id = $request->seguranca_id;
        $anuncios->opcionais_id = $request->opcionais_id;
        $anuncios->combustivel_id = $request->combustivel;
        $anuncios->placa = $request->placa;
        $anuncios->km = $request->km;

        $anuncios->sinistrado = $request->sinistrado;
        $anuncios->descricao = $request->descricao;

        $anuncios->save();

        // Verifica se a quantidade de anúncios já atingiu o limite
        /* if ($plano_anunciante->quantidade_anuncio >= $request->limite_anuncio) {
            return response(['message'=> 'Limite de anúncios atingido'], 400);
        }
 */
        // Atualiza a quantidade total de anúncios feitos pelo anunciante
        $plano_anunciante->quantidade_anuncio += 1;

        // Atualiza a quantidade restante de anúncios no plano do anunciante
        $plano_anunciante->anuncio_restante -= 1;

        $plano_anunciante->save();


        return $anuncios;
    }

    public function uploadFoto(Request $request, $id)
    {
        $anuncios = Anuncios::find($id);

        if (!$anuncios) {
            return response()->json(['mensagem' => 'Não existe um anúncio com este ID!']);
        }

        if ($request->hasFile('foto1')) {

            // Código para upload e redimensionamento de cada foto
            $file = $request->file('foto1');
            $extension = $file->getClientOriginalExtension();
            $detalheFilename = 'detalhe.' . $extension;
            $detaleMiniFilename = 'detalhe_mini.' . $extension;
            $destaqueFilename = 'destaque.' . $extension;
            $destaqueMiniFilename = 'destaque_mini.' . $extension;
            $principalFilename = 'principal.' . $extension;
            $principalMiniFilename = 'principal_mini.' . $extension;

            // Diretório para armazenar o arquivo, criado com base no ID
            $directory = 'uploads/anuncios/foto1/' . $id . '/';
            // Move a primeira foto
            $file->move($directory, $detalheFilename);
            // Cria cópias redimensionadas para as outras fotos
            $copyDestaque = Image::make($directory . $detalheFilename)
                ->fit(480, 360) // Tamanho desejado para destaque
                ->save($directory . $destaqueFilename);

            $copyDestaqueMini = Image::make($directory . $detalheFilename)
                ->fit(240, 180) // Tamanho desejado para miniatura do destaque
                ->save($directory . $destaqueMiniFilename);

            $copyPrincipal = Image::make($directory . $detalheFilename)
                ->fit(249, 186) // Tamanho desejado para detalhe
                ->save($directory . $principalFilename);

            $copyPrincipalMini = Image::make($directory . $detalheFilename)
                ->fit(122, 93) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $principalMiniFilename);

            $copyDetalharMini = Image::make($directory . $detalheFilename)
                ->fit(800, 600) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $detaleMiniFilename);

            $anuncios->foto1 = $directory;
            $anuncios->save();
        }


        if ($request->hasFile('foto2')) {

            // Código para upload e redimensionamento de cada foto
            $file = $request->file('foto2');
            $extension = $file->getClientOriginalExtension();
            $detalheFilename = 'detalhe.' . $extension;
            $detaleMiniFilename = 'detalhe_mini.' . $extension;
            $destaqueFilename = 'destaque.' . $extension;
            $destaqueMiniFilename = 'destaque_mini.' . $extension;
            $principalFilename = 'principal.' . $extension;
            $principalMiniFilename = 'principal_mini.' . $extension;

            // Diretório para armazenar o arquivo, criado com base no ID
            $directory = 'uploads/anuncios/foto2/' . $id . '/';
            // Move a primeira foto
            $file->move($directory, $detalheFilename);
            // Cria cópias redimensionadas para as outras fotos
            $copyDestaque = Image::make($directory . $detalheFilename)
                ->fit(480, 360) // Tamanho desejado para destaque
                ->save($directory . $destaqueFilename);

            $copyDestaqueMini = Image::make($directory . $detalheFilename)
                ->fit(240, 180) // Tamanho desejado para miniatura do destaque
                ->save($directory . $destaqueMiniFilename);

            $copyPrincipal = Image::make($directory . $detalheFilename)
                ->fit(249, 186) // Tamanho desejado para detalhe
                ->save($directory . $principalFilename);

            $copyPrincipalMini = Image::make($directory . $detalheFilename)
                ->fit(122, 93) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $principalMiniFilename);

            $copyDetalharMini = Image::make($directory . $detalheFilename)
                ->fit(800, 600) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $detaleMiniFilename);

            $anuncios->foto2 = $directory;
            $anuncios->save();
        }


        if ($request->hasFile('foto3')) {

            // Código para upload e redimensionamento de cada foto
            $file = $request->file('foto3');
            $extension = $file->getClientOriginalExtension();
            $detalheFilename = 'detalhe.' . $extension;
            $detaleMiniFilename = 'detalhe_mini.' . $extension;
            $destaqueFilename = 'destaque.' . $extension;
            $destaqueMiniFilename = 'destaque_mini.' . $extension;
            $principalFilename = 'principal.' . $extension;
            $principalMiniFilename = 'principal_mini.' . $extension;

            // Diretório para armazenar o arquivo, criado com base no ID
            $directory = 'uploads/anuncios/foto3/' . $id . '/';
            // Move a primeira foto
            $file->move($directory, $detalheFilename);
            // Cria cópias redimensionadas para as outras fotos
            $copyDestaque = Image::make($directory . $detalheFilename)
                ->fit(480, 360) // Tamanho desejado para destaque
                ->save($directory . $destaqueFilename);

            $copyDestaqueMini = Image::make($directory . $detalheFilename)
                ->fit(240, 180) // Tamanho desejado para miniatura do destaque
                ->save($directory . $destaqueMiniFilename);

            $copyPrincipal = Image::make($directory . $detalheFilename)
                ->fit(249, 186) // Tamanho desejado para detalhe
                ->save($directory . $principalFilename);

            $copyPrincipalMini = Image::make($directory . $detalheFilename)
                ->fit(122, 93) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $principalMiniFilename);

            $copyDetalharMini = Image::make($directory . $detalheFilename)
                ->fit(800, 600) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $detaleMiniFilename);

            $anuncios->foto3 = $directory;
            $anuncios->save();
        }

        if ($request->hasFile('foto4')) {

            // Código para upload e redimensionamento de cada foto
            $file = $request->file('foto4');
            $extension = $file->getClientOriginalExtension();
            $detalheFilename = 'detalhe.' . $extension;
            $detaleMiniFilename = 'detalhe_mini.' . $extension;
            $destaqueFilename = 'destaque.' . $extension;
            $destaqueMiniFilename = 'destaque_mini.' . $extension;
            $principalFilename = 'principal.' . $extension;
            $principalMiniFilename = 'principal_mini.' . $extension;

            // Diretório para armazenar o arquivo, criado com base no ID
            $directory = 'uploads/anuncios/foto4/' . $id . '/';
            // Move a primeira foto
            $file->move($directory, $detalheFilename);
            // Cria cópias redimensionadas para as outras fotos
            $copyDestaque = Image::make($directory . $detalheFilename)
                ->fit(480, 360) // Tamanho desejado para destaque
                ->save($directory . $destaqueFilename);

            $copyDestaqueMini = Image::make($directory . $detalheFilename)
                ->fit(240, 180) // Tamanho desejado para miniatura do destaque
                ->save($directory . $destaqueMiniFilename);

            $copyPrincipal = Image::make($directory . $detalheFilename)
                ->fit(249, 186) // Tamanho desejado para detalhe
                ->save($directory . $principalFilename);

            $copyPrincipalMini = Image::make($directory . $detalheFilename)
                ->fit(122, 93) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $principalMiniFilename);

            $copyDetalharMini = Image::make($directory . $detalheFilename)
                ->fit(800, 600) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $detaleMiniFilename);

            $anuncios->foto4 = $directory;
            $anuncios->save();
        }

        if ($request->hasFile('foto5')) {

            // Código para upload e redimensionamento de cada foto
            $file = $request->file('foto5');
            $extension = $file->getClientOriginalExtension();
            $detalheFilename = 'detalhe.' . $extension;
            $detaleMiniFilename = 'detalhe_mini.' . $extension;
            $destaqueFilename = 'destaque.' . $extension;
            $destaqueMiniFilename = 'destaque_mini.' . $extension;
            $principalFilename = 'principal.' . $extension;
            $principalMiniFilename = 'principal_mini.' . $extension;

            // Diretório para armazenar o arquivo, criado com base no ID
            $directory = 'uploads/anuncios/foto5/' . $id . '/';
            // Move a primeira foto
            $file->move($directory, $detalheFilename);
            // Cria cópias redimensionadas para as outras fotos
            $copyDestaque = Image::make($directory . $detalheFilename)
                ->fit(480, 360) // Tamanho desejado para destaque
                ->save($directory . $destaqueFilename);

            $copyDestaqueMini = Image::make($directory . $detalheFilename)
                ->fit(240, 180) // Tamanho desejado para miniatura do destaque
                ->save($directory . $destaqueMiniFilename);

            $copyPrincipal = Image::make($directory . $detalheFilename)
                ->fit(249, 186) // Tamanho desejado para detalhe
                ->save($directory . $principalFilename);

            $copyPrincipalMini = Image::make($directory . $detalheFilename)
                ->fit(122, 93) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $principalMiniFilename);

            $copyDetalharMini = Image::make($directory . $detalheFilename)
                ->fit(800, 600) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $detaleMiniFilename);

            $anuncios->foto5 = $directory;
            $anuncios->save();
        }

        if ($request->hasFile('foto6')) {

            // Código para upload e redimensionamento de cada foto
            $file = $request->file('foto6');
            $extension = $file->getClientOriginalExtension();
            $detalheFilename = 'detalhe.' . $extension;
            $detaleMiniFilename = 'detalhe_mini.' . $extension;
            $destaqueFilename = 'destaque.' . $extension;
            $destaqueMiniFilename = 'destaque_mini.' . $extension;
            $principalFilename = 'principal.' . $extension;
            $principalMiniFilename = 'principal_mini.' . $extension;

            // Diretório para armazenar o arquivo, criado com base no ID
            $directory = 'uploads/anuncios/foto6/' . $id . '/';
            // Move a primeira foto
            $file->move($directory, $detalheFilename);
            // Cria cópias redimensionadas para as outras fotos
            $copyDestaque = Image::make($directory . $detalheFilename)
                ->fit(480, 360) // Tamanho desejado para destaque
                ->save($directory . $destaqueFilename);

            $copyDestaqueMini = Image::make($directory . $detalheFilename)
                ->fit(240, 180) // Tamanho desejado para miniatura do destaque
                ->save($directory . $destaqueMiniFilename);

            $copyPrincipal = Image::make($directory . $detalheFilename)
                ->fit(249, 186) // Tamanho desejado para detalhe
                ->save($directory . $principalFilename);

            $copyPrincipalMini = Image::make($directory . $detalheFilename)
                ->fit(122, 93) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $principalMiniFilename);

            $copyDetalharMini = Image::make($directory . $detalheFilename)
                ->fit(800, 600) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $detaleMiniFilename);

            $anuncios->foto6 = $directory;
            $anuncios->save();
        }

        if ($request->hasFile('foto7')) {

            // Código para upload e redimensionamento de cada foto
            $file = $request->file('foto7');
            $extension = $file->getClientOriginalExtension();
            $detalheFilename = 'detalhe.' . $extension;
            $detaleMiniFilename = 'detalhe_mini.' . $extension;
            $destaqueFilename = 'destaque.' . $extension;
            $destaqueMiniFilename = 'destaque_mini.' . $extension;
            $principalFilename = 'principal.' . $extension;
            $principalMiniFilename = 'principal_mini.' . $extension;

            // Diretório para armazenar o arquivo, criado com base no ID
            $directory = 'uploads/anuncios/foto7/' . $id . '/';
            // Move a primeira foto
            $file->move($directory, $detalheFilename);
            // Cria cópias redimensionadas para as outras fotos
            $copyDestaque = Image::make($directory . $detalheFilename)
                ->fit(480, 360) // Tamanho desejado para destaque
                ->save($directory . $destaqueFilename);

            $copyDestaqueMini = Image::make($directory . $detalheFilename)
                ->fit(240, 180) // Tamanho desejado para miniatura do destaque
                ->save($directory . $destaqueMiniFilename);

            $copyPrincipal = Image::make($directory . $detalheFilename)
                ->fit(249, 186) // Tamanho desejado para detalhe
                ->save($directory . $principalFilename);

            $copyPrincipalMini = Image::make($directory . $detalheFilename)
                ->fit(122, 93) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $principalMiniFilename);

            $copyDetalharMini = Image::make($directory . $detalheFilename)
                ->fit(800, 600) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $detaleMiniFilename);

            $anuncios->foto7 = $directory;
            $anuncios->save();
        }

        if ($request->hasFile('foto8')) {

            // Código para upload e redimensionamento de cada foto
            $file = $request->file('foto8');
            $extension = $file->getClientOriginalExtension();
            $detalheFilename = 'detalhe.' . $extension;
            $detaleMiniFilename = 'detalhe_mini.' . $extension;
            $destaqueFilename = 'destaque.' . $extension;
            $destaqueMiniFilename = 'destaque_mini.' . $extension;
            $principalFilename = 'principal.' . $extension;
            $principalMiniFilename = 'principal_mini.' . $extension;

            // Diretório para armazenar o arquivo, criado com base no ID
            $directory = 'uploads/anuncios/foto8/' . $id . '/';
            // Move a primeira foto
            $file->move($directory, $detalheFilename);
            // Cria cópias redimensionadas para as outras fotos
            $copyDestaque = Image::make($directory . $detalheFilename)
                ->fit(480, 360) // Tamanho desejado para destaque
                ->save($directory . $destaqueFilename);

            $copyDestaqueMini = Image::make($directory . $detalheFilename)
                ->fit(240, 180) // Tamanho desejado para miniatura do destaque
                ->save($directory . $destaqueMiniFilename);

            $copyPrincipal = Image::make($directory . $detalheFilename)
                ->fit(249, 186) // Tamanho desejado para detalhe
                ->save($directory . $principalFilename);

            $copyPrincipalMini = Image::make($directory . $detalheFilename)
                ->fit(122, 93) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $principalMiniFilename);

            $copyDetalharMini = Image::make($directory . $detalheFilename)
                ->fit(800, 600) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $detaleMiniFilename);

            $anuncios->foto8 = $directory;
            $anuncios->save();
        }

        if ($request->hasFile('foto9')) {

            // Código para upload e redimensionamento de cada foto
            $file = $request->file('foto9');
            $extension = $file->getClientOriginalExtension();
            $detalheFilename = 'detalhe.' . $extension;
            $detaleMiniFilename = 'detalhe_mini.' . $extension;
            $destaqueFilename = 'destaque.' . $extension;
            $destaqueMiniFilename = 'destaque_mini.' . $extension;
            $principalFilename = 'principal.' . $extension;
            $principalMiniFilename = 'principal_mini.' . $extension;

            // Diretório para armazenar o arquivo, criado com base no ID
            $directory = 'uploads/anuncios/foto9/' . $id . '/';
            // Move a primeira foto
            $file->move($directory, $detalheFilename);
            // Cria cópias redimensionadas para as outras fotos
            $copyDestaque = Image::make($directory . $detalheFilename)
                ->fit(480, 360) // Tamanho desejado para destaque
                ->save($directory . $destaqueFilename);

            $copyDestaqueMini = Image::make($directory . $detalheFilename)
                ->fit(240, 180) // Tamanho desejado para miniatura do destaque
                ->save($directory . $destaqueMiniFilename);

            $copyPrincipal = Image::make($directory . $detalheFilename)
                ->fit(249, 186) // Tamanho desejado para detalhe
                ->save($directory . $principalFilename);

            $copyPrincipalMini = Image::make($directory . $detalheFilename)
                ->fit(122, 93) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $principalMiniFilename);

            $copyDetalharMini = Image::make($directory . $detalheFilename)
                ->fit(800, 600) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $detaleMiniFilename);

            $anuncios->foto9 = $directory;
            $anuncios->save();
        }

        if ($request->hasFile('foto10')) {

            // Código para upload e redimensionamento de cada foto
            $file = $request->file('foto10');
            $extension = $file->getClientOriginalExtension();
            $detalheFilename = 'detalhe.' . $extension;
            $detaleMiniFilename = 'detalhe_mini.' . $extension;
            $destaqueFilename = 'destaque.' . $extension;
            $destaqueMiniFilename = 'destaque_mini.' . $extension;
            $principalFilename = 'principal.' . $extension;
            $principalMiniFilename = 'principal_mini.' . $extension;

            // Diretório para armazenar o arquivo, criado com base no ID
            $directory = 'uploads/anuncios/foto10/' . $id . '/';
            // Move a primeira foto
            $file->move($directory, $detalheFilename);
            // Cria cópias redimensionadas para as outras fotos
            $copyDestaque = Image::make($directory . $detalheFilename)
                ->fit(480, 360) // Tamanho desejado para destaque
                ->save($directory . $destaqueFilename);

            $copyDestaqueMini = Image::make($directory . $detalheFilename)
                ->fit(240, 180) // Tamanho desejado para miniatura do destaque
                ->save($directory . $destaqueMiniFilename);

            $copyPrincipal = Image::make($directory . $detalheFilename)
                ->fit(249, 186) // Tamanho desejado para detalhe
                ->save($directory . $principalFilename);

            $copyPrincipalMini = Image::make($directory . $detalheFilename)
                ->fit(122, 93) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $principalMiniFilename);

            $copyDetalharMini = Image::make($directory . $detalheFilename)
                ->fit(800, 600) // Tamanho desejado para miniatura do detalhe
                ->save($directory . $detaleMiniFilename);

            $anuncios->foto10 = $directory;
            $anuncios->save();
        }

        return response()->json(['message' => 'Foto enviada com sucesso'], 200);
    }

    // Adicione mais verificações e ações para cada foto que você deseja processar, como foto2, foto3, etc.

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $anuncio = Anuncios::find($id);
        $dadosPersonalizados = [];
        if (!$anuncio) {
            return response(['message' => 'Anúncio não encontrado'], 404);
        }

        //Para incrementar o número de cliques
        $anuncio->increment('numero_cliques');

        //Para Mostrar os nomes dos relacionamentos
        $tipo_veiculo = TiposVeiculos::find($anuncio->tipo_veiculo_id);
        $marca = Marcas::find($anuncio->marca_id);
        $modelo = Modelos::find($anuncio->modelo_id);
        $anunciante = Anunciantes::find($anuncio->anunciante_id);
        $categoria = Categorias::find($anuncio->categoria_id);
        $estado = Estados::find($anuncio->estado_id);
        $cidade = Cidades::find($anuncio->cidade_id);
        $fabricante = Fabricantes::find($anuncio->fabricante_id);
        $cor = Cor::find($anuncio->cor_id);
        $transmissao = Transmissao::find($anuncio->transmissao_id);
        $combustivel = Combustivel::find($anuncio->combustivel_id);


        // Acessar o estado e cidade através do relacionamento com o anunciante
        $estado = Estados::find($anunciante->estado_id);
        $cidade = Cidades::find($anunciante->cidade_id);

        $opcionais = Opcionais::all();
        $lista_opcionais = collect([]);

        $opcionais_anuncio = $anuncio->opcionais_id;

        // Converta a string JSON para um array usando json_decode
        $opcionais_array = json_decode($opcionais_anuncio, true);

        foreach ($opcionais_array as $opcional_anuncio) {
            // Inicialize o array $array_opcional dentro do loop foreach
            $array_opcional = [];

            foreach ($opcionais as $opcional_banco) {
                if ($opcional_anuncio == $opcional_banco->id) {
                    $array_opcional = array(
                        'id_opcional' => $opcional_banco->id,
                        'categoria_opcional_id' => $opcional_banco->categoria_opcional_id,
                        'nome' => $opcional_banco->nome,
                    );
                }
            }

            // Movido para fora do loop interno para evitar repetições
            $lista_opcionais->push($array_opcional);
        }
        //return $tipo_veiculo;
        // Personalize os campos conforme necessário
        $dadosPersonalizados[] = [
            'id' => $anuncio->id,
            'tipo_veiculo' => $tipo_veiculo->tipo_veiculo,
            'id_marca' => $marca->id,
            'marca' => $marca->nome_marca,
            'id_modelo' => $modelo->id,
            'modelo' => $modelo->nome_modelo,
            'numero_cliques' => $anuncio->numero_cliques,
            'numero_cliques_contato' => $anuncio->numero_cliques_contato,
            'numero_cliques_mensagem' => $anuncio->numero_cliques_mensagem,
            'situacao_veiculo' => $anuncio->situacao_veiculo,
            'id_anunciante' => $anunciante->id,
            'anunciantes' => $anunciante->pessoal_responsavel,
            'telefone' => $anunciante->telefone,
            'celular' => $anunciante->celular,
            'whatsapp' => $anunciante->whatsapp,
            'email' => $anunciante->email,
            'foto' => $anunciante->foto ? env('URL_BASE_SERVIDOR') . '/' . $anunciante->foto : null,
            'banner_loja' => $anunciante->banner_loja ? env('URL_BASE_SERVIDOR') . '/' . $anunciante->banner_loja : null,
            'banner_loja_movel' => $anunciante->banner_loja_movel ? env('URL_BASE_SERVIDOR') . '/' . $anunciante->banner_loja_movel : null,
            'categoria_id' => $categoria->id,
            'categoria' => $categoria->nome,
            'data_inicio' => $anuncio->data_inicio,
            'data_fim' => $anuncio->data_fim,
            'ordenacao' => $anuncio->ordenacao,
            'status_publicacao' => $anuncio->status_publicacao,
            'status_pagamento' => $anuncio->status_pagamento,
            'tipo' => $anuncio->tipo,
            'vendido' => $anuncio->vendido,
            'vitrine' => $anuncio->vitrine,
            'destaque_busca' => $anuncio->destaque_busca,
            'estado' => $estado->estado,
            'uf' => $estado->uf,
            'cidade_id' => $cidade->id,
            'cidade' => $cidade->cidade,
            'empresa' => $anunciante->nome_empresa,
            'tipo_preco' => $anuncio->tipo_preco,
            'valor_preco' => $anuncio->valor_preco,
            'mostrar_preco' => $anuncio->mostrar_preco,
            'fabricante_id' => $fabricante->id,
            'fabricante' => $fabricante->fabricante,
            'ano_fabricacao' => $anuncio->ano_fabricacao,
            'ano_modelo' => $anuncio->ano_modelo,
            'carroceria' => $anuncio->carroceria,
            'estilo' => $anuncio->estilo,
            'portas' => $anuncio->portas,
            'cilindros' => $anuncio->cilindros,
            'motor' => $anuncio->motor,
            'cor_id' => $cor->id,
            'cor' => $cor->cor,
            'transmissao_id' => $transmissao->id,
            'transmissao' => $transmissao->transmissao,
            'combustivel_id' => $combustivel->id,
            'combustivel' => $combustivel->combustivel,
            'placa' => $anuncio->placa,
            'km' => $anuncio->km,
            'sinistrado' => $anuncio->sinistrado,
            'opcionais_id' => $lista_opcionais,

            'som' => $anuncio->som,
            'descricao' => $anuncio->descricao,
            'foto1' => $anuncio->foto1 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto1 : null,
            'foto2' => $anuncio->foto2 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto2 : null,
            'foto3' => $anuncio->foto3 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto3 : null,
            'foto4' => $anuncio->foto4 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto4 : null,
            'foto5' => $anuncio->foto5 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto5 : null,
            'foto6' => $anuncio->foto6 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto6 : null,
            'foto7' => $anuncio->foto7 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto7 : null,
            'foto8' => $anuncio->foto8 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto8 : null,
            'foto9' => $anuncio->foto9 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto9 : null,
            'foto10' => $anuncio->foto10 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto10 : null,
            // Adicione mais campos personalizados conforme necessário
        ];

        // Retorna a resposta JSON com os dados personalizados
        return response()->json($dadosPersonalizados);
    }
    public function detalhar($id)
    {
        //
        $anuncio = Anuncios::find($id);
        $dadosPersonalizados = [];
        if (!$anuncio) {
            return response(['message' => 'Anúncio não encontrado'], 404);
        }

        //Para incrementar o número de cliques
        //$anuncio->increment('numero_cliques');

        //Para Mostrar os nomes dos relacionamentos
        $tipo_veiculo = TiposVeiculos::find($anuncio->tipo_veiculo_id);
        $marca = Marcas::find($anuncio->marca_id);
        $modelo = Modelos::find($anuncio->modelo_id);
        $anunciante = Anunciantes::find($anuncio->anunciante_id);
        $categoria = Categorias::find($anuncio->categoria_id);
        $estado = Estados::find($anuncio->estado_id);
        $cidade = Cidades::find($anuncio->cidade_id);
        $fabricante = Fabricantes::find($anuncio->fabricante_id);
        $cor = Cor::find($anuncio->cor_id);
        $transmissao = Transmissao::find($anuncio->transmissao_id);
        $combustivel = Combustivel::find($anuncio->combustivel_id);


        // Acessar o estado e cidade através do relacionamento com o anunciante
        $estado = Estados::find($anunciante->estado_id);
        $cidade = Cidades::find($anunciante->cidade_id);

        $opcionais = Opcionais::all();
        $lista_opcionais = collect([]);

        $opcionais_anuncio = $anuncio->opcionais_id;

        // Converta a string JSON para um array usando json_decode
        $opcionais_array = json_decode($opcionais_anuncio, true);

        foreach ($opcionais_array as $opcional_anuncio) {
            // Inicialize o array $array_opcional dentro do loop foreach
            $array_opcional = [];

            foreach ($opcionais as $opcional_banco) {
                if ($opcional_anuncio == $opcional_banco->id) {
                    $array_opcional = array(
                        'id_opcional' => $opcional_banco->id,
                        'categoria_opcional_id' => $opcional_banco->categoria_opcional_id,
                        'nome' => $opcional_banco->nome,
                    );
                }
            }

            // Movido para fora do loop interno para evitar repetições
            $lista_opcionais->push($array_opcional);
        }
        //return $tipo_veiculo;
        // Personalize os campos conforme necessário
        $dadosPersonalizados[] = [
            'id' => $anuncio->id,
            'tipo_veiculo' => $tipo_veiculo->tipo_veiculo,
            'marca_id' => $marca->id,
            'marca' => $marca->nome_marca,
            'modelo_id' => $modelo->id,
            'modelo' => $modelo->nome_modelo,
            'numero_cliques' => $anuncio->numero_cliques,
            'numero_cliques_contato' => $anuncio->numero_cliques_contato,
            'numero_cliques_mensagem' => $anuncio->numero_cliques_mensagem,
            'situacao_veiculo' => $anuncio->situacao_veiculo,
            'anunciante_id' => $anunciante->id,
            'anunciantes' => $anunciante->pessoal_responsavel,
            'telefone' => $anunciante->telefone,
            'celular' => $anunciante->celular,
            'whatsapp' => $anunciante->whatsapp,
            'email' => $anunciante->email,
            'foto' => $anunciante->foto ? env('URL_BASE_SERVIDOR') . '/' . $anunciante->foto : null,
            'banner_loja' => $anunciante->banner_loja ? env('URL_BASE_SERVIDOR') . '/' . $anunciante->banner_loja : null,
            'banner_loja_movel' => $anunciante->banner_loja_movel ? env('URL_BASE_SERVIDOR') . '/' . $anunciante->banner_loja_movel : null,
            'categoria_id' => $categoria->id,
            'categoria' => $categoria->nome,
            'data_inicio' => $anuncio->data_inicio,
            'data_fim' => $anuncio->data_fim,
            'ordenacao' => $anuncio->ordenacao,
            'status_publicacao' => $anuncio->status_publicacao,
            'status_pagamento' => $anuncio->status_pagamento,
            'tipo' => $anuncio->tipo,
            'vendido' => $anuncio->vendido,
            'vitrine' => $anuncio->vitrine,
            'destaque_busca' => $anuncio->destaque_busca,
            'estado_id' => $estado->id,
            'estado' => $estado->estado,
            'uf' => $estado->uf,
            'cidade_id' => $cidade->id,
            'cidade' => $cidade->cidade,
            'empresa' => $anunciante->nome_empresa,
            'tipo_preco' => $anuncio->tipo_preco,
            'valor_preco' => $anuncio->valor_preco,
            'mostrar_preco' => $anuncio->mostrar_preco,
            'fabricante_id' => $fabricante->id,
            'fabricante' => $fabricante->fabricante,
            'ano_fabricacao' => $anuncio->ano_fabricacao,
            'ano_modelo' => $anuncio->ano_modelo,
            'carroceria' => $anuncio->carroceria,
            'estilo' => $anuncio->estilo,
            'portas' => $anuncio->portas,
            'cilindros' => $anuncio->cilindros,
            'motor' => $anuncio->motor,
            'cor_id' => $cor->id,
            'cor' => $cor->cor,
            'transmissao_id' => $transmissao->id,
            'transmissao' => $transmissao->transmissao,
            'combustivel_id' => $combustivel->id,
            'combustivel' => $combustivel->combustivel,
            'placa' => $anuncio->placa,
            'km' => $anuncio->km,
            'sinistrado' => $anuncio->sinistrado,
            'opcionais_id' => $lista_opcionais,

            'som' => $anuncio->som,
            'descricao' => $anuncio->descricao,
            'foto1' => $anuncio->foto1 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto1 : null,
            'foto2' => $anuncio->foto2 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto2 : null,
            'foto3' => $anuncio->foto3 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto3 : null,
            'foto4' => $anuncio->foto4 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto4 : null,
            'foto5' => $anuncio->foto5 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto5 : null,
            'foto6' => $anuncio->foto6 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto6 : null,
            'foto7' => $anuncio->foto7 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto7 : null,
            'foto8' => $anuncio->foto8 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto8 : null,
            'foto9' => $anuncio->foto9 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto9 : null,
            'foto10' => $anuncio->foto10 ? env('URL_BASE_SERVIDOR') . '/' . $anuncio->foto10 : null,
            // Adicione mais campos personalizados conforme necessário
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

    public function contadorContato($id)
    {
        $contato = Anuncios::findOrFail($id);
        $contato->increment('numero_cliques_contato');

        return $contato;
    }


    public function contadorMensagem($id)
    {
        $mensagem = Anuncios::findOrFail($id);
        $mensagem->increment('numero_cliques_mensagem');

        return $mensagem;
    }



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

        $anuncios = Anuncios::find($id);
        $dadosPersonalizados = [];
        if (!$anuncios) {
            return response(['message' => 'Anúncio não encontrado'], 404);
        }

        $tipo_veiculo = TiposVeiculos::find($request->tipo_veiculo);
        $tecnologia = Tecnologia::find($request->tecnologia);
        $modelo = Modelos::find($request->modelo_id);
        $marca = Marcas::find($request->marca_id);
        $anunciante = Anunciantes::find($request->anunciante_id);
        $categoria = Categorias::find($request->categoria_id);
        $fabricante = Fabricantes::find($request->fabricante_id);
        $cor = Cor::find($request->cor);
        $transmissao = Transmissao::find($request->transmissao);
        $combustivel = Combustivel::find($request->combustivel);



        if (!$tipo_veiculo) {
            return response(['message' => 'O tipo de veiculo selecionado não existe'], 404);
        }
        if (!$tecnologia) {
            return response(['message' => 'A tecnologia selecionada não existe'], 404);
        }
        if (!$marca) {
            return response(['message' => 'A marca selecionada não existe'], 404);
        }

        if (!$modelo) {
            return response(['message' => 'O modelo selecionado não existe'], 404);
        }
        if (!$anunciante) {
            return response(['message' => 'O Anunciante selecionado não existe'], 404);
        }

        if (!$categoria) {
            return response(['message' => 'A Categoria selecionada não existe'], 404);
        }
        if (!$fabricante) {
            return response(['message' => 'O Fabricante selecionado não existe'], 404);
        }
        if (!$cor) {
            return response(['message' => 'A Cor selecionada não existe'], 404);
        }
        if (!$transmissao) {
            return response(['message' => 'A Transmissão selecionada não existe'], 404);
        }
        if (!$combustivel) {
            return response(['message' => 'O Combustível selecionado não existe'], 404);
        }



        $anuncios->titulo = $request->titulo;
        $anuncios->tipo_veiculo_id = $request->tipo_veiculo;
        $anuncios->tecnologia_id = $request->tecnologia;
        $anuncios->marca_id = $request->marca_id;
        $anuncios->modelo_id = $request->modelo_id;
        $anuncios->numero_cliques = $request->numero_cliques;
        $anuncios->situacao_veiculo = $request->situacao_veiculo;
        $anuncios->anunciante_id = $request->anunciante_id;
        $anuncios->categoria_id = $request->categoria_id;
        $anuncios->data_inicio = $request->data_inicio;
        $anuncios->data_fim = $request->data_fim;
        $anuncios->ordenacao = $request->ordenacao;
        $anuncios->status_publicacao = $request->status_publicacao;
        $anuncios->status_pagamento = $request->status_pagamento;
        $anuncios->tipo = $request->tipo;
        $anuncios->vendido = $request->vendido;
        $anuncios->vitrine = $request->vitrine;
        $anuncios->destaque_busca = $request->destaque_busca;
        $anuncios->tipo_preco = $request->tipo_preco;
        $anuncios->valor_preco = $request->valor_preco;
        $anuncios->mostrar_preco = $request->mostrar_preco;
        $anuncios->fabricante_id = $request->fabricante_id;
        $anuncios->ano_fabricacao = $request->ano_fabricacao;
        $anuncios->ano_modelo = $request->ano_modelo;
        $anuncios->carroceria = $request->carroceria;
        $anuncios->estilo = $request->estilo;
        $anuncios->portas = $request->portas;
        $anuncios->cilindros = $request->cilindros;
        $anuncios->motor = $request->motor;
        $anuncios->cor_id = $request->cor;
        $anuncios->transmissao_id = $request->transmissao;
        $anuncios->combustivel_id = $request->combustivel;
        $anuncios->placa = $request->placa;
        $anuncios->km = $request->km;
        //$anuncios->conforto_id = $request->conforto_id;
        $anuncios->opcionais_id = $request->opcionais_id;
        $anuncios->sinistrado = $request->sinistrado;
        $anuncios->descricao = $request->descricao;
        $anuncios->save();
        return $anuncios;
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
        Anuncios::destroy($id);
        return "Anúncio eliminado com sucesso!";
    }



    public function destroyFoto1($id)
    {
        $anuncios = Anuncios::find($id);

        if (!$anuncios) {
            return response()->json(['message' => 'Anunciantes não encontrado'], 404);
        }

        $pathFoto = public_path() .  $anuncios->foto1;
        if (File::exists($pathFoto)) {
            File::delete($pathFoto);
            $anuncios->foto1 = '';
            $anuncios->save();
            return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Foto não encontrada'], 404);
        }
    }

    public function destroyFoto2($id)
    {
        $anuncios = Anuncios::find($id);

        if (!$anuncios) {
            return response()->json(['message' => 'Anunciantes não encontrado'], 404);
        }

        $pathFoto = public_path() .  $anuncios->foto2;
        if (File::exists($pathFoto)) {
            File::delete($pathFoto);
            $anuncios->foto2 = '';
            $anuncios->save();
            return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Foto não encontrada'], 404);
        }
    }

    public function destroyFoto3($id)
    {
        $anuncios = Anuncios::find($id);

        if (!$anuncios) {
            return response()->json(['message' => 'Anunciantes não encontrado'], 404);
        }

        $pathFoto = public_path() .  $anuncios->foto1;
        if (File::exists($pathFoto)) {
            File::delete($pathFoto);
            $anuncios->foto3 = '';
            $anuncios->save();
            return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Foto não encontrada'], 404);
        }
    }

    public function destroyFoto4($id)
    {
        $anuncios = Anuncios::find($id);

        if (!$anuncios) {
            return response()->json(['message' => 'Anunciantes não encontrado'], 404);
        }

        $pathFoto = public_path() .  $anuncios->foto1;
        if (File::exists($pathFoto)) {
            File::delete($pathFoto);
            $anuncios->foto4 = '';
            $anuncios->save();
            return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Foto não encontrada'], 404);
        }
    }

    public function destroyFoto5($id)
    {
        $anuncios = Anuncios::find($id);

        if (!$anuncios) {
            return response()->json(['message' => 'Anunciantes não encontrado'], 404);
        }

        $pathFoto = public_path() .  $anuncios->foto5;
        if (File::exists($pathFoto)) {
            File::delete($pathFoto);
            $anuncios->foto5 = '';
            $anuncios->save();
            return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Foto não encontrada'], 404);
        }
    }

    public function destroyFoto6($id)
    {
        $anuncios = Anuncios::find($id);

        if (!$anuncios) {
            return response()->json(['message' => 'Anunciantes não encontrado'], 404);
        }

        $pathFoto = public_path() .  $anuncios->foto6;
        if (File::exists($pathFoto)) {
            File::delete($pathFoto);
            $anuncios->foto6 = '';
            $anuncios->save();
            return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Foto não encontrada'], 404);
        }
    }

    public function destroyFoto7($id)
    {
        $anuncios = Anuncios::find($id);

        if (!$anuncios) {
            return response()->json(['message' => 'Anunciantes não encontrado'], 404);
        }

        $pathFoto = public_path() .  $anuncios->foto7;
        if (File::exists($pathFoto)) {
            File::delete($pathFoto);
            $anuncios->foto7 = '';
            $anuncios->save();
            return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Foto não encontrada'], 404);
        }
    }


    public function destroyFoto8($id)
    {
        $anuncios = Anuncios::find($id);

        if (!$anuncios) {
            return response()->json(['message' => 'Anunciantes não encontrado'], 404);
        }

        $pathFoto = public_path() .  $anuncios->foto8;
        if (File::exists($pathFoto)) {
            File::delete($pathFoto);
            $anuncios->foto8 = '';
            $anuncios->save();
            return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Foto não encontrada'], 404);
        }
    }


    public function destroyFoto9($id)
    {
        $anuncios = Anuncios::find($id);

        if (!$anuncios) {
            return response()->json(['message' => 'Anunciantes não encontrado'], 404);
        }

        $pathFoto = public_path() .  $anuncios->foto9;
        if (File::exists($pathFoto)) {
            File::delete($pathFoto);
            $anuncios->foto9 = '';
            $anuncios->save();
            return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Foto não encontrada'], 404);
        }
    }


    public function destroyFoto10($id)
    {
        $anuncios = Anuncios::find($id);

        if (!$anuncios) {
            return response()->json(['message' => 'Anunciantes não encontrado'], 404);
        }

        $pathFoto = public_path() .  $anuncios->foto10;
        if (File::exists($pathFoto)) {
            File::delete($pathFoto);
            $anuncios->foto10 = '';
            $anuncios->save();
            return response()->json(['message' => 'Foto eliminada com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Foto não encontrada'], 404);
        }
    }
}
