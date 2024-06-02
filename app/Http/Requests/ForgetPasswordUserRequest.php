<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use function App\Global\errorHttp;

class ForgetPasswordUserRequest extends FormRequest
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
            'email' => ['required','email','string','max:150','exists:users,email'],
        ];
    }

    // si la validacion falla
    public function failedValidation(Validator $validator)
    {
        // Crear la respusta
        $response = errorHttp(config("mjs.validation"),400, $validator->errors());

        // Devolver la respuesta
        throw new HttpResponseException($response);
    }
}
