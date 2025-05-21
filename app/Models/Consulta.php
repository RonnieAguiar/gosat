<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $fillable = ['consultado_em'];

    public function cpf()
    {
        return $this->belongsTo(Cpf::class);
    }
}
