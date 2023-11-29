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

class UpdateEnderecoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateEndereco',
        'description' => 'Edita um registro de endereço'
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
                'type' =>  Type::int(),
                'rules' => ['required', 'exists:enderecos,id,excluido_em,NULL']
            ],
            'pais' => [
                'type' => Type::string(),
                'description' => 'O pais do endereço',
            ],
            'estado' => [
                'type' => Type::string(),
                'description' => 'O estado do endereço',
            ],
            'cidade' => [
                'type' => Type::string(),
                'description' => 'A cidade do endereço',
            ],
            'bairro' => [
                'type' => Type::string(),
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

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'id.exists' => 'Endereço não encontrado',
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $endereco = Endereco::findOrFail($args['id']);

        $validator = EnderecoValidation::make($args);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();

            throw ValidationException::withMessages($errors);
        }
   
        $endereco->update($args);
        $endereco = $endereco->fresh();

        return $endereco;
    }
}
