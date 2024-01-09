<?php

namespace App\Services;

use App\Mail\RegistrationUserMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Jobs\SendRegistrationEmail;


class UserService
{

    public function getAll()
    {

        $informations = DB::table('users')
            ->join('companies', 'users.fk_companie', '=', 'companies.id')
            ->select(
                'users.id',
                'users.name AS user_name',  // Alias para o campo 'name' da tabela 'users'
                'users.email',
                'users.cpf',
                'users.status',
                'companies.name AS company_name'  // Alias para o campo 'name' da tabela 'companies'
            )
            ->get();

        return $informations;
    }

    public function registrationUser($request)
    {

        $cpf_formatado = $this->replaceCPF($request->input('cpf')); // Formatando CPF
        $sizePassword = 10; // Defina o comprimento da cadeia desejado
        $email = $request->input('email'); // Definindo email 
        $cpf = $cpf_formatado; // Definindo cpf
        $password = Str::random($sizePassword); // Gerando senha

        $informations = [
            'name' => strtoupper($request->input('name')),
            'cpf' => $cpf_formatado,
            'email' => $request->input('email'),
            'password' => bcrypt($password),
            'status' => 'ATIVO',
            'fk_companie' => $request->input('fk_companie'),
            'fk_office' => $request->input('fk_office'),
        ];

        SendRegistrationEmail::dispatch($email, $cpf, $password);

        return DB::table('users')->insert($informations); // Cadastrando novo usuário

        // return Mail::to($email)->send(new RegistrationUserMail($cpf, $passowrd)); // Enviando e-mail de confirmação com credenciais

    }

    public function deactivationUser($id)
    {

        return DB::table('users')->where('id', $id)->update(['status' => 'DESATIVADO']); // Desativando usuário

    }

    public function activateUser($id)
    {

        return DB::table('users')->where('id', $id)->update(['status' => 'ATIVO']); // Ativando usuário

    }


    public function replaceCPF($cpf)
    {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Adiciona zeros à esquerda para garantir 11 dígitos
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        // Formata o CPF
        $cpfFormatado = substr_replace($cpf, '.', 3, 0);
        $cpfFormatado = substr_replace($cpfFormatado, '.', 7, 0);
        $cpfFormatado = substr_replace($cpfFormatado, '-', 11, 0);

        return $cpfFormatado;
    }
}
