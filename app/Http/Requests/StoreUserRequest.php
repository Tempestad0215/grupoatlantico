<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
            'name' => ['required','string','max:75'],
            'last_name' => ['required','string','max:75'],
            'email'=> ['required','string','email','unique:users,email'],
            'password'=> ['required','string','max:70', Password::min(8)->mixedCase()->numbers()->symbols()],
            'password_confirmation' => ['required','string','max:70',Password::min(8)->mixedCase()->numbers()->symbols()],
            'access' => ['array','required']
        ];
    }



    protected function failedValidation(Validator $validator)
    {
        // Crear la respuesta
        $response = response()->json([
            "status" => "error",
            "message" => "La validación contiene errores",
            "errors" => $validator->errors()
        ],422);

        // Devolver la respuiesta con el error
        throw new HttpResponseException($response);
    }
}
