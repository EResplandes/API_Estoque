<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'O campo E-MAIL é obrigatório!',
            'email.email' => 'O campo E-MAIL não é um e-mail valído!',
            'email.unique' => 'O E-MAIL informado já está cadastrado!',
            'password.required' => 'O campo PASSWORD é obrigatório!'
        ];
    }
}
