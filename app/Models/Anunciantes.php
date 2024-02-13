<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

class Anunciantes extends Model
{
    use HasFactory;
    protected $fillable = [
        // seus campos aqui
    ];

    protected static function booted()
    {
        static::created(function ($anunciante) {
            // Crie uma conta de usuário
            $usuario = new User();
            $usuario->name = $anunciante->nome_empresa;
            $usuario->email = $anunciante->email;
            $usuario->password = Hash::make($anunciante->password); // Use o CNPJ como senha
            $usuario->save();
        });
    }

}
