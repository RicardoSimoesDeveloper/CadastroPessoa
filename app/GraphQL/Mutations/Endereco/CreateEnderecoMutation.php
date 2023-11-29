<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Endereco;

use Closure;
use App\Models\Endereco;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Validation\ValidationException;
use App\GraphQL\Validations\EnderecoValidation;

class CreateEnderecoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createEndereco',
        'description' => 'Cria um novo endereço'
    ];

    public function type(): Type
    {
        return GraphQL::type('Endereco');
    }

    public function args(): array
    {
        return [
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

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $validator = EnderecoValidation::make($args);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();

            throw ValidationException::withMessages($errors);
        }

        $endereco = new Endereco();
        $endereco->fill($args);
        $endereco->save();

        return $endereco;
    }
}
