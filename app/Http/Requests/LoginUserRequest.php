<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

use function App\Global\errorHttp;

class LoginUserRequest extends FormRequest
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
            'email' => ['required','email','max:150'],
            'password' => ['required','max:60',Password::min(8)->mixedCase()->numbers()->symbols()]
        ];
    }

    // Mensaje en caso de que no sea correcta
    public function failedValidation(Validator $validator)
    {
        // Crear la respuesta
        $response = errorHttp(config('msj.validation'),422, $validator->errors());

        // Enviar el error
        throw new HttpResponseException( $response);
    }


}
