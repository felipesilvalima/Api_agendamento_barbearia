<?php declare(strict_types=1); 

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->isMethod('patch') ? [

            'telefone' => ['sometimes','required','numeric','digits:11','unique:clientes,telefone'],   
        ] :
        [
            'nome' => 'required|string|max:40',
            'telefone' => 'required|numeric|digits:11|unique:clientes,telefone',

            'email' => [
                'required',
                'string',
                'max:40',
                'unique:users,email',
                'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/'
            ],

            'password' => 'required|size:10|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'mensagem' => 'Dados inválidos',
                'campos' => $validator->errors()
            ], 422)
        );
    }

    public function messages(): array
    {
        return [
            'required' => 'O :attribute é obrigatório',
            'regex' => 'O :atrtribute inválido',
            'string' => 'O :attribute precisar ser do tipo texto',
            'numeric' => 'O :attribute precisar ser do tipo númerico',
            'size' => 'O :attribute deve ter :size caracteres',
            'max' => 'O :attribute deve ter no máximo :max caracteres',
            'unique' => 'Esse :attribute já foi cadastrado',
            'digits' => 'O :attribute deve ter :digits digitos',
        ];
    }
}
