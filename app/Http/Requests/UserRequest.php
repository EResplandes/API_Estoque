<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'string|required',
            'cpf' => 'string|required|unique:users|digits_between:11,11',
            'email' => 'email|required|unique:users',
            'status' => 'string|required',
            'fk_companie' => 'integer|required',
            'fk_office' => 'integer|required'
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'O campo :attribute é obrigatório!',
            '*.string' => 'O campo :attribute deve ser um texto!',
            '*.integer' => 'O campo :attribute deve ser um inteiro!',
            '*.unique' => 'O campo :attribute já está cadastrado!',
            'cpf.digits_between' => 'O campo CPF deve conter 11 caracteres!',
        ];
    }

}
