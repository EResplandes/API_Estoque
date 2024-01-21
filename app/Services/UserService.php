<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
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
        $email_user = $request->input('email'); // Definindo email 
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

        DB::table('users')->insert($informations); // Cadastrando novo usuário

        $permissions = $request->input('permissionsUser'); // Pegando as permissões

        $latestUser = DB::table('users')->latest('id')->select('id')->first(); // Pegando id do usuário

        $queryRegister = $this->registerPermissions($permissions, $latestUser); // Registrando permissões

        if ($queryRegister) {
            SendRegistrationEmail::dispatch($email, $email_user, $password); // Chamando job de envio de e-mail
            return 'Usuário cadastrado com sucesso!';
        } else {
            return 'Ocorreu algum problema, entre em contato como o Administrador do sistema!';
        }

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

    public function registerPermissions($arrayPermissions, $latestUser)
    {

        // Inseriondo permissões do usuário
        foreach ($arrayPermissions as $permissions) {
            DB::table('users_permissions')->insert([
                'fk_permission' => (int) $permissions,
                'fk_user' => (int) $latestUser->id,
            ]);
        }

        return true;
    }
}
