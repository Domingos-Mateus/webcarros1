<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoAnunciantes extends Model
{
    use HasFactory;

    public function planos()
    {
        return $this->belongsTo(Planos::class);
    }
}
