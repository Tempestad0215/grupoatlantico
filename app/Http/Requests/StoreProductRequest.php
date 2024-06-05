<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use function App\Global\errorHttp;

class StoreProductRequest extends FormRequest
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
            "name"=> ["required","string","min:4","max:75"],
            "description" => ["required","string","min:4","max:150"],
            "branch_id" => ["required","numeric","exists:branches,id"],
            "stock" => ["required","numeric"],
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        // Crear la respuesta
        $response = errorHttp(config("msj.validation"),422, $validator->errors());

        // Devolver la respuiesta con el error
        throw new HttpResponseException($response);
    }
}
