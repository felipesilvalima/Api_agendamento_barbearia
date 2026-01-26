<?php declare(strict_types=1); 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthRequest extends FormRequest
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

            'password' => ['required','size:10','string','confirmed']
           
        ] :[
             'email' => [
                'required',
                'string',
                'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/'
            ],

            'password' => 'required|string',
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
            'confirmed' => 'A senha de confirmação está incorreta',
            'size' => 'A senha dever conter :size caracteres'
        ];
    }
}
