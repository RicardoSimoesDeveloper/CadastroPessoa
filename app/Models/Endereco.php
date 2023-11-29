<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Endereco extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'enderecos';

    protected $primaryKey = 'id';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
    const DELETED_AT = 'excluido_em';

    protected $fillable = [
        'pais',
        'estado',
        'cidade',
        'bairro',
        'logradouro',
        'numero',
        'complemento',
        'cep'
    ];

    protected $hidden = [
        'criado_em',
        'atualizado_em',
        'excluido_em',
    ];

    public function getCepAttribute($value)
    {
        return preg_replace("/^(..)(...)(...)(.*)$/",  "$1.$2-$3", $value);
    }

    public function pessoa()
    {
        return $this->hasMany(CadastroPessoa::class, 'endereco');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($endereco) {
            if ($endereco->pessoa()->count() > 0) {
                throw ValidationException::withMessages(['Não é possível excluir o endereço, pois o mesmo está relacionada a um cadastro de pessoa.']);
            }
        });
    }
}
