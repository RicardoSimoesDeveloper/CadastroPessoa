<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\CadastroPessoa;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CadastroPessoaType extends GraphQLType
{
    protected $attributes = [
        'name' => 'CadastroPessoa',
        'description' => 'Cadastroa uma pessoa',
        'model' => CadastroPessoa::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'O ID do cadastro de pessoas isenta dentro do banco de dados'
            ],
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'O nome da pessoa'
            ],
            'documento_cpf' => [
                'type' => Type::string(),
                'description' => 'O documento cpf da pessoa'
            ],
            'data_nascimento' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'A data de nascimento da pessoa',
                'resolve' => function ($pessoa) {
                    return !is_null($pessoa->data_nascimento) ? date("d/m/Y", strtotime($pessoa->data_nascimento)) : null;
                }
            ],
            'endereco' => [
                'type' => Type::nonNull(GraphQL::type('Endereco')),
                'description' => 'O endereco da pessoa',
                'resolve' => function ($pessoa) {
                    return $pessoa->endereco_id;
                }
            ],
            'sexo' => [
                'type' => Type::string(),
                'description' => 'O sexo da pessoa',
            ],
            'telefone' => [
                'type' => Type::string(),
                'description' => 'O telefone da pessoa',
                'resolve' => function ($pessoa) {
                    $telefone = preg_replace("/[^0-9]/", "", $pessoa->telefone);
                    if (strlen($telefone) != 11) {
                        return preg_replace("/^(..)(....)(....)$/", "($1) $2-$3", $telefone);
                    } else {
                        return preg_replace("/^(..)(.....)(....)$/", "($1) $2-$3", $telefone);
                    }
                },
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'O email da pessoa'
            ],
        ];
    }
}
