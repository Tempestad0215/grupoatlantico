<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use function App\Global\errorHttp;

class StoreEntranceRequest extends FormRequest
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
            'order' => ['required','array'],
            'order.*.name' => ['required','string','min:4','max:70'],
            'order.*.id' => ['required','numeric','exists:products,id'],
            'info' => ['required','string','min:4'],
        ];
    }


    // Validacion error

    protected function failedValidation(Validator $validator)
    {
        // Crear la respuesta
        $response = errorHttp(config('msj.validation'),422, $validator->errors());

        // Devolver la respuiesta con el error
        throw new HttpResponseException($response);
    }
}
