<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\CadastroPessoa;

use App\Models\CadastroPessoa;
use Closure;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CadastroPessoaQuery extends Query
{
    protected $attributes = [
        'name' => 'cadastroPessoa',
    ];

    public function type(): Type
    {
        return GraphQL::type('CadastroPessoa');
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
                    'exists:cadastro_pessoas,id,excluido_em,NULL'
                ]
            ],
        ];
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'id.exists' => 'Pessoa não encontrado.',
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $pessoa = CadastroPessoa::findOrFail($args['id']);
        return $pessoa;
    }
}
