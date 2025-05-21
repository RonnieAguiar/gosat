<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instituicao extends Model
{
    protected $fillable = ['id', 'nome'];

    public function modalidades()
    {
        return $this->hasMany(Modalidade::class);
    }
}
