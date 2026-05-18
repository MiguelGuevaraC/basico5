<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMarcaRequest extends FormRequest
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
        $marcaId = $this->route('marca')?->id ?? $this->route('marca');

        return [
            'nombre' => [
                'required',
                'string',
                'max:120',
                Rule::unique('marcas', 'nombre')
                    ->ignore($marcaId)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
