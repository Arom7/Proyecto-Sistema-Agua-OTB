<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocioRequest extends FormRequest
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
            'nombre' => ['required', 'string','regex:/^(?! )[a-zA-Z]+( [a-zA-Z]+)*$/', 'max:85'],
            'primer_apellido' => ['required', 'string', 'regex:/^(?! )[a-zA-Z]+( [a-zA-Z]+)*$/', 'max:85'],
            'segundo_apellido' => ['string','regex:/^(?! )[a-zA-Z]+( [a-zA-Z]+)*$/', 'max:85'],
            'ci' => ['required', 'string', 'regex:/^[a-zA-Z0-9]+$/', 'max:40','unique:socios,ci_socio'],
            'image' => ['nullable' , 'image' , 'mimes:jpeg,png,jpg,gif,svg' , 'max:2048']
        ];
    }

    public function messages():array {
        return [
            'nombre.required' => 'El nombre es requerido',
            'nombre.string' => 'El nombre debe ser un texto',
            'primer_apellido.required' => 'El primer apellido es requerido',
            'primer_apellido.string' => 'El primer apellido debe ser un texto',
            'segundo_apellido.string' => 'El segundo apellido debe ser un texto',
            'nombre_socio.regex' => 'Tu nombre solo puede contener letras y espacios.',
            'primer_apellido_socio.regex' => 'Tu primer apellido solo puede contener letras.',
            'segundo_apellido_socio.regex' => 'Tu segundo apellido solo puede contener letras',
            'ci_socio.regex' => 'El CI solo puede contener letras y números.',
            'ci.required' => 'La cedula de identidad es requerida',
            'ci.string' => 'La cedula de identidad debe ser un texto',
            'image.required' => 'La imagen es requerida',
            'image.mines' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg, gif, svg',
        ];
    }
}
