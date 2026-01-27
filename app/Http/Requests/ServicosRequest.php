<?php declare(strict_types=1); 

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ServicosRequest extends FormRequest
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
        return $this->isMethod('patch') ?  [
            'descricao' => 'string|max:100',
            'preco' => 'sometimes|required|numeric',
        ]:
        [
            'nome' => 'required|string|max:40|unique:servicos,nome',
            'descricao' => 'string|max:100',
            'duracao_minutos' => 'sometimes|integer',
            'preco' => 'required|numeric',
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
            'string' => 'O :attribute precisar ser do tipo texto',
            'numeric' => 'O :attribute precisar ser do tipo númerico',
            'max' => 'O :attribute deve ter no máximo :max caracteres',
            'integer' => 'O :attribute precisar ser do tipo númerico inteiro',
            'unique' => 'Esse Servico já foi cadastrado',

            
        ];
    }
}
