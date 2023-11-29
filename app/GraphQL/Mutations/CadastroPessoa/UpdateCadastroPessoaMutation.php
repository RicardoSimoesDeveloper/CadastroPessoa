<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\CadastroPessoa;

use Closure;
use App\Models\CadastroPessoa;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Validation\ValidationException;
use App\GraphQL\Validations\CadastroPessoaValidation;

class UpdateCadastroPessoaMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateCadastroPessoa',
        'description' => 'Edita um registro de cadastro de pessoa'
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
                'type' =>  Type::int(),
                'rules' => ['required', 'exists:cadastro_pessoas,id,excluido_em,NULL']
            ],
            'nome' => [
                'type' => Type::string(),
                'description' => 'O nome da pessoa'
            ],
            'documento_cpf' => [
                'type' => Type::string(),
                'description' => 'O documento cpf da pessoa'
            ],
            'data_nascimento' => [
                'type' => Type::string(),
                'description' => 'A data de nascimento da pessoa'
            ],
            'endereco' => [
                'type' => Type::int(),
                'description' => 'O endereco da pessoa'
            ],
            'sexo' => [
                'type' => Type::string(),
                'description' => 'O sexo da pessoa'
            ],
            'telefone' => [
                'type' => Type::string(),
                'description' => 'O telefone da pessoa'
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'O email da pessoa'
            ],
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

        $validator = CadastroPessoaValidation::make($args);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();

            throw ValidationException::withMessages($errors);
        }
   
        $pessoa->update($args);
        $pessoa = $pessoa->fresh();

        return $pessoa;
    }
}
