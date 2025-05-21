<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cpf extends Model
{
    protected $fillable = ['cpf'];

    public $timestamps = false;

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }

    public function detalhesModalidades()
    {
        return $this->hasMany(DetalheModalidade::class);
    }
}
