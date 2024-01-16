<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestsRequest extends FormRequest
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
            // 'fk_user' => 'integer|required',
            // 'observations' => 'string',
            // 'application' => 'string|required',
            // 'cart' => 'required',
        ];
    }

    public function messages()
    {
        return [
            // 'cart.required' => 'O carrinho está vazio, selecione ao menos 1 item!',
            // '*.string' => 'O campo ::attribute deve ser um texto!',
            // 'fk_user.integer' => 'O id do usuário deve ser um inteiro!',
            // '*.required' => "O campo :attribute é obrigatório!"
        ];
    }
}
