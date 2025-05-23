<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetalheModalidade extends Model
{
    protected $fillable = ['modalidade_id', 'cpf_id', 'qnt_parcela_min', 'qnt_parcela_max', 'valor_min', 'valor_max', 'juros_mes'];

    public function modalidade()
    {
        return $this->belongsTo(Modalidade::class);
    }

    public function cpf()
    {
        return $this->belongsTo(Cpf::class);
    }
}
