<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\CadastroPessoa;

use App\Models\CadastroPessoa;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class DeleteCadastroPessoaMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteCadastroPessoa',
        'description' => 'Exclui um cadastro de pessoa'
    ];

    public function type(): Type
    {
        return Type::boolean();
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
            ]
        ];
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'id.exists' => 'Pessoa não encontrada',
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $pessoa = CadastroPessoa::findOrFail($args['id']);
        $pessoa->delete();

        return true;
    }
}
