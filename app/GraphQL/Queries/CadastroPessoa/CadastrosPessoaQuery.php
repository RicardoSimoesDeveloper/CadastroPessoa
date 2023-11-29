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

class CadastrosPessoaQuery extends Query
{
    protected $attributes = [
        'name' => 'cadastrosPessoa',
    ];

    public function type(): Type
    {
        return GraphQL::paginate('CadastroPessoa');
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
        $query = CadastroPessoa::query();
    
        if (!empty($args['query'])) {
            $searchTerm = $args['query'];
    
            $query->where(function ($q) use ($searchTerm, $args) {
                $fillableColumns = array_keys(CadastroPessoa::first()->getAttributes());
    
                if (!empty($args['coluna'])) {
                    if (in_array($args['coluna'], $fillableColumns))
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
