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

class EnderecosQuery extends Query
{
    protected $attributes = [
        'name' => 'enderecos',
    ];

    public function type(): Type
    {
        return GraphQL::paginate('Endereco');
    }

    public function args(): array
    {
        return [
            'page' => [
                'type' => Type::int(),
                'defaultValue' => 1
            ],
            'limit' => [
                'type' => Type::int(),
                'defaultValue' => 10
            ],
            'coluna' => [
                'name' => 'coluna',
                'description' => 'Coluna a ser pesquisada',
                'type' => Type::string(),
                'defaultValue' => '',
            ],
            'query' => [
                'name' => 'query',
                'description' => 'Pesquisar em todas as colunas do modelo ou na coluna mencionada',
                'type' => Type::string(),
                'defaultValue' => '',
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $with = $fields->getRelations();

        $query = Endereco::with($with);

        if (!empty($args['query'])) {
            $searchTerm = $args['query'];

            $query->where(function ($q) use ($searchTerm, $args) {
                $fillableColumns = array_keys(Endereco::first()->getAttributes());

                if(!empty($args['coluna'])){
                    if(in_array($args['coluna'], $fillableColumns))
                        $fillableColumns = array($args['coluna']);
                }

                foreach ($fillableColumns as $column) {
                    $q->orWhere($column, 'like', "%$searchTerm%");
                }
            });
        }

        return $query->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
