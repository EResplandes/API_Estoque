<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            'name' => 'string|required|unique:stock',
            'description' => 'string|required',
            'amount' => 'integer|required',
            'dt_validity' => 'date',
            'fk_companie' => 'integer|required'
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'O campo :attribute é obrigatório!',
            '*.integer' => 'O campo :attribute deve ser um inteiro!',
            '*.string' => 'O campo ::attribute deve ser um texto!',
            'name.unique' => 'Produto já existe no sistema!',
            'dt_validity.date' => 'O campo Data de Validade deve ser data valida!'
        ];
    }
}
