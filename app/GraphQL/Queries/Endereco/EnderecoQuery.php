<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Endereco;

use App\Models\Endereco;
use Closure;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;

class EnderecoQuery extends Query
{
    protected $attributes = [
        'name' => 'endereco',
    ];

    public function type(): Type
    {
        return GraphQL::type('Endereco');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' =>
                [
                    'required',
                    'exists:enderecos,id,excluido_em,NULL'
                ]
            ],
        ];
    }

   
    public function validationErrorMessages(array $args = []): array
    {
        return [
            'id.exists' => 'Endereço não encontrado.',
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $endereco = Endereco::findOrFail($args['id']);
        return $endereco;
    }
}
