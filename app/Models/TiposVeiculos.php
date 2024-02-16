<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposVeiculos extends Model
{
    use HasFactory;

    public function marcas()
    {
        return $this->hasMany(Marcas::class, 'tipo_veiculo_id');
    }
}
