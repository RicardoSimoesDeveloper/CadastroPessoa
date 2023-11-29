<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Endereco;

use App\Models\Endereco;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class DeleteEnderecoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteEndereco',
        'description' => 'Exclui um endereço'
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
                    'exists:enderecos,id,excluido_em,NULL'
                ]
            ]
        ];
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'id.exists' => 'Endereço não encontrado',
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $endereco = Endereco::findOrFail($args['id']);
        $endereco->delete();

        return true;
    }
}
