<?php

declare(strict_types = 1);

return [
    'route' => [
        'prefix' => 'graphql',
        'controller' => \Rebing\GraphQL\GraphQLController::class . '@query',
        'middleware' => [],
        'group_attributes' => [],
    ],

    'default_schema' => 'default',

    'batching' => [
        'enable' => true,
    ],

    'schemas' => [
        'default' => [

            'query' => [
                'cadastrosPessoa' => \App\GraphQL\Queries\CadastroPessoa\CadastrosPessoaQuery::class,
                'cadastroPessoa'  => \App\GraphQL\Queries\CadastroPessoa\CadastroPessoaQuery::class,

               'enderecos' => \App\GraphQL\Queries\Endereco\EnderecosQuery::class,
               'endereco'  => \App\GraphQL\Queries\Endereco\EnderecoQuery::class,
                
            ],
            'mutation' => [
                'createCadastroPessoa'  =>  \App\GraphQL\Mutations\CadastroPessoa\CreateCadastroPessoaMutation::class,
                'updateCadastroPessoa'  =>  \App\GraphQL\Mutations\CadastroPessoa\UpdateCadastroPessoaMutation::class,
                'deleteCadastroPessoa'  =>  \App\GraphQL\Mutations\CadastroPessoa\DeleteCadastroPessoaMutation::class,
                'restoreCadastroPessoa' =>  \App\GraphQL\Mutations\CadastroPessoa\RestoreCadastroPessoaMutation::class,

                'createEndereco'  =>  \App\GraphQL\Mutations\Endereco\CreateEnderecoMutation::class,
                'updateEndereco'  =>  \App\GraphQL\Mutations\Endereco\UpdateEnderecoMutation::class,
                'deleteEndereco'  =>  \App\GraphQL\Mutations\Endereco\DeleteEnderecoMutation::class,
                'restoreEndereco' =>  \App\GraphQL\Mutations\Endereco\RestoreEnderecoMutation::class,
            ],
            'types' => [
                'CadastroPessoa' => App\GraphQL\Types\CadastroPessoaType::class,
                'Endereco' => App\GraphQL\Types\EnderecoType::class,

            ],
            'middleware' => null,
            'method' => ['GET', 'POST'],
            'execution_middleware' => null,
        ],
    ],

    'types' => [
     
    ],
    'error_formatter' => [\Rebing\GraphQL\GraphQL::class, 'formatError'],
    'errors_handler' => [\Rebing\GraphQL\GraphQL::class, 'handleErrors'],
    'security' => [
        'query_max_complexity' => null,
        'query_max_depth' => null,
        'disable_introspection' => false,
    ],
    'pagination_type' => \Rebing\GraphQL\Support\PaginationType::class,
    'simple_pagination_type' => \Rebing\GraphQL\Support\SimplePaginationType::class,
    'defaultFieldResolver' => null,
    'headers' => [],
    'json_encoding_options' => 0,
    'apq' => [
        'enable' => env('GRAPHQL_APQ_ENABLE', false),
        'cache_driver' => env('GRAPHQL_APQ_CACHE_DRIVER', config('cache.default')),
        'cache_prefix' => config('cache.prefix') . ':graphql.apq',
        'cache_ttl' => 300,
    ],

    'execution_middleware' => [
        \Rebing\GraphQL\Support\ExecutionMiddleware\ValidateOperationParamsMiddleware::class,
        // AutomaticPersistedQueriesMiddleware listed even if APQ is disabled, see the docs for the `'apq'` configuration
        \Rebing\GraphQL\Support\ExecutionMiddleware\AutomaticPersistedQueriesMiddleware::class,
        \Rebing\GraphQL\Support\ExecutionMiddleware\AddAuthUserContextValueMiddleware::class,
        // \Rebing\GraphQL\Support\ExecutionMiddleware\UnusedVariablesMiddleware::class,
    ],
];
