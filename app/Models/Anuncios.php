<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anuncios extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'titulo',
        'tipo_veiculo',
        'marca_id',
        'modelo_id',
        'numero_cliques',
        'anunciante_id',
        'categoria_id',
        'data_inicio',
        'data_fim',
        'ordenacao',
        'status_publicacao',
        'status_pagamento',
        'tipo',
        'vendido',
        'vitrine',
        'destaque_busca',
        'estado_id',
        'cidade_id',
        'empresa',
        'tipo_preco',
        'valor_preco',
        'descricao',
        'foto1',
        'foto2',
        'foto3',
        'foto4',
        'foto5',
        'foto6',
        'foto7',
        'foto8',
        'foto9',
        'foto10'
    ];

}
