<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

use function App\Global\errorHttp;

class UpdateEmployeeRequest extends FormRequest
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

        // Sacar e id
        $id = $this->route("employee");

        // Verificar los datos
        return [
            "name"=> ["required","string","max:75"],
            "last_name"=> ["required","string","max:150"],
            "email"=> ["required","string","email",Rule::unique("employees")->ignore($id)],
            "deparment_id" => ["required","numeric","exists:deparments,id"],
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
