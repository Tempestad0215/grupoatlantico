<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

use function App\Global\errorHttp;

class ChangePasswordRequest extends FormRequest
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
            'password'=> ['required','string','max:70', Password::min(8)->mixedCase()->numbers()->symbols()],
            'password_confirmation' => ['required','string','max:70',Password::min(8)->mixedCase()->numbers()->symbols()],
        ];
    }


    // Si la validacion falla

    protected function failedValidation(Validator $validator)
    {
        // Crear la respuesta
        $response = errorHttp(config("msj.validation"),422, $validator->errors());

        // Devolver la respuiesta con el error
        throw new HttpResponseException($response);
    }
}
