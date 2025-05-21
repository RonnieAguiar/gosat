<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidade extends Model
{
    protected $fillable = ['nome', 'instituicao_id', 'codigo'];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    }

    public function detalhes()
    {
        return $this->hasMany(DetalheModalidade::class);
    }
}
