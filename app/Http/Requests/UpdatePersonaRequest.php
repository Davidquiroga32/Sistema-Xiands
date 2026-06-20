<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonaRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre_completo' => ['required', 'string', 'max:150'],
            'cedula' => ['required', 'string', 'max:20', Rule::unique('personas')->ignore($this->route('persona'))],
            'correo_electronico' => ['nullable', 'email', 'max:150'],
            'numero_telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string'],
            'nombre_codeudor' => ['nullable', 'string', 'max:150'],
        ];
    }
}
