<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            "name"=> ["required","string","max:75"],
            "last_name"=> ["required","string","max:150"],
            "email"=> ["required","string","email","unique:employees,email"],
            "deparment_id" => ["required","numeric","exists:deparments,id"],
        ];
    }
}