<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use DateTime;


class AuthService
{

    public function login($request)
    {

        $credenciais = $request->all(['email', 'password']);
        $email = $request->input('email');

        $token = auth('api')->attempt($credenciais); // Verificando se o usuário existe

        if ($token == false) {
            $token = 'Usuário ou senha inválidos!';
            return $token;
        } else {

            $information = DB::table('users')
                ->join('companies', 'users.fk_companie', '=', 'companies.id')
                ->select(
                    'users.id',
                    'users.name AS user_name',  // Alias para o campo 'name' da tabela 'users'
                    'users.email',
                    'users.cpf',
                    'users.status',
                    'companies.name AS company_name'  // Alias para o campo 'name' da tabela 'companies'
                )
                ->where('email', $email)
                ->get();

            return ['Token' => $token, 'User' => $information];
        }
    }

    public function verificaToken($request)
    {

        // Pegando token
        $tokenString = $request->input('token');

        // Pegando data e hora atual
        date_default_timezone_set('America/Sao_Paulo');
        $dataAtual = date('Y-m-d H:i:s');

        $payload = JWTAuth::getPayload($tokenString)->toArray();

        // Pegando datas 
        $dataExpira = $payload['exp'];

        // Convertendo para objetos DateTime
        $expDate = new DateTime("@$dataExpira");

        // Verifica token
        if ($expDate < $dataAtual) {
            return 'O token não é mais valído!';
        } else {
            return 'O token está valído!';
        }
    }

    public function logout($request)
    {

        $token = $request->input('token'); // Armazenando token

        $query = auth('api')->logout($token); // Colocando token na blacklist
        
        return $query;

    }
}