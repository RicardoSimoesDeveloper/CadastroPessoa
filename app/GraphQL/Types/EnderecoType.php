<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Endereco;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class EnderecoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Endereco',
        'description' => 'Endereço',
        'model' => Endereco::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'O ID do endereço isenta dentro do banco de dados'
            ],
            'pais' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'O pais do endereço',
            ],
            'estado' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'O estado do endereço',
            ],
            'cidade' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'A cidade do endereço',
            ],
            'bairro' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'O bairro do endereço',
            ],
             'logradouro' => [
                'type' => Type::string(),
                'description' => 'O logradouro do endereço',
            ],
            'numero' => [
                'type' => Type::string(),
                'description' => 'O número do endereço',
            ],
            'complemento' => [
                'type' => Type::string(),
                'description' => 'O complemento do endereço',
            ],
            'cep' => [
                'type' => Type::string(),
                'description' => 'O CEP do endereço',
            ],
        ];
    }
}
