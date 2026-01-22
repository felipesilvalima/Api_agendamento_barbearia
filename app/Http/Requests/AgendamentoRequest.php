<?php declare(strict_types=1); 

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AgendamentoRequest extends FormRequest
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
        return [
            'id_barbeiro' => 'required|integer',
            'data' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'hora' => 'required|date_format:H:i:s',
            'servicos' => 'required|array|min:1'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Dados inválidos',
                'fields' => $validator->errors()
            ], 422)
        );
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'integer' => 'O campo :attribute precisar ser do tipo inteiro',
            'date' => 'O campo :attribute precisar ser do tipo data',
            'date_format' => 'O campo :attribute precisar ter um formato válido',
            'after_or_equal' => 'Data inválida. Escolha uma data mais atual',
            'array' => 'O campo :attribute precisar ser do tipo array inteiro',
            'min' => 'O campo :attribute precisar ter no minimo :min :attribute'
        ];
    }
}
