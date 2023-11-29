<?php

namespace App\GraphQL\Validations;

use App\Models\Endereco;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class EnderecoValidation
{
    public static function make(array $data)
    {
        Validator::extend('cep', function ($attribute, $value, $parameters, $validator) {

            $url = "https://viacep.com.br/ws/$value/json/";
            $client = new \GuzzleHttp\Client();
            try {
                $response = $client->request('GET', $url);

                if ($response->getStatusCode() == 200) {
                    return true;
                }else{
                    return false;
                }

            } catch (\Exception $e) {
                return false;
            }
        });

        $id = isset($data['id']) ? $data['id'] : null;

        $rules = [
            'pais'        => ['required', 'string', 'max:30'],
            'estado'      => ['required', 'string', 'max:2'],
            'cidade'      => ['required', 'string', 'max:40'],
            'bairro'      => ['required', 'string', 'max:90'],
			'logradouro'  => ['nullable', 'string', 'max:45'],
			'numero'      => ['nullable', 'string', 'max:10'],
			'complemento' => ['nullable', 'string', 'max:30'],
			'cep'         => ['nullable', 'numeric', 'cep'],
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
            'cep.cep' => "O campo cep é inválido.",
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return $validator;
        }

        $validator->after(function ($validator) use ($data) {
            $id = isset($data['id']) ? $data['id'] : null;

            $endereco = Endereco::find($id);

            $logradouro = isset($data['logradouro']) ? $data['logradouro'] : ($endereco ? $endereco->logradouro : null);
            $numero = isset($data['numero']) ? $data['numero'] : ($endereco ? $endereco->numero : null);

            // se logradouro estiver preenchido o numero nao pode ser nulo.
            if(!is_null($logradouro)){
                if(is_null($numero)){
                    $validator->errors()->add('numero', 'O número é obrigatório.');
                }
            }

        });

        return $validator;
    }
}
