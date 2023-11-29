<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\CadastroPessoa;

use Closure;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Validation\ValidationException;
use App\GraphQL\Validations\CadastroPessoaValidation;
use App\Models\CadastroPessoa;

class CreateCadastroPessoaMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createCadastroPessoa',
        'description' => 'Cria um novo registro de pessoa'
    ];

    public function type(): Type
    {
        return GraphQL::type('CadastroPessoa');
    }

    public function args(): array
    {
        return [
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
                'description' => 'A data de nascimento da pessoa'
            ],
            'endereco' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'O endereco da pessoa'
            ],
            'sexo' => [
                'type' => Type::string(),
                'description' => 'O sexo da pessoa',
            ],
            'telefone' => [
                'type' => Type::string(),
                'description' => 'O telefone da pessoa'
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'O email da pessoa',
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $validator = CadastroPessoaValidation::make($args);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();

            throw ValidationException::withMessages($errors);
        }

        $pessoa = new CadastroPessoa();
        $pessoa->fill($args);
        $pessoa->save();

        return $pessoa;
    }
}
