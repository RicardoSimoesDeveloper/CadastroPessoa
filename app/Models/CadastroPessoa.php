<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CadastroPessoa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cadastro_pessoas';

    protected $primaryKey = 'id';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
    const DELETED_AT = 'excluido_em';

    protected $fillable = [
        'nome',
        'documento_cpf',
        'data_nascimento',
        'endereco',
        'sexo',
        'telefone',
        'email'
    ];

    protected $hidden = [
        'criado_em',
        'atualizado_em',
        'excluido_em',
    ];

    protected $with = ['endereco_id'];

    public function endereco_id()
    {
       return $this->belongsTo(Endereco::class, 'endereco');
    }

}
