<?php

namespace App\GraphQL\Validations;

use App\Models\CadastroPessoa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CadastroPessoaValidation
{
    public static function make(array $data)
    {
        Validator::extend('telefone_com_ddd', function ($attribute, $value, $parameters, $validator) {
            $length = strlen((string) $value);
            return ($length == 10 || $length == 11);
        });

        Validator::extend('cpf', function ($attribute, $value, $parameters, $validator) {
            $cpf = preg_replace('/[^0-9]/', '', $value);

            if (strlen($cpf) != 11) {
                return false;
            }

            if (preg_match('/(\d)\1{10}/', $cpf)) {
                return false;
            }

            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }
            return true;
        });

        $id = isset($data['id']) ? $data['id'] : null;
        
        $rules = [
            'nome'            => ['required', 'string', 'max:45'],
            'documento_cpf'   => ['nullable', 'cpf', 'numeric', 'digits:11'],
            'data_nascimento' => ['required', 'date'],
            'endereco'        => ['required', 'integer', 'exists:enderecos,id'],
            'sexo'            => ['nullable', 'string', 'max:10'],
			'telefone'        => ['nullable', 'numeric', 'telefone_com_ddd'],
			'email'           => ['nullable', 'string', 'email', 'max:100'],
        ];

        if(isset($id)) {
            $adaptativeRules = [];
            foreach ($rules as $property => $propertyRules) {
                foreach ($propertyRules as $rule) {
                    if ($rule !== 'required')
                        $adaptativeRules[$property][] = $rule;
                }
            }
            $rules = $adaptativeRules;
        }

        $messages = [
            'telefone.telefone_com_ddd' => "O campo telefone é inválido.",
            'documento_cpf.cpf' => 'O CPF informado é inválido.'
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return $validator;
        }

        return $validator;
    }
}
