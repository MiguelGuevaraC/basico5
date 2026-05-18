<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreProductoFotosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fotos' => ['required', 'array', 'min:1'],
            'fotos.*' => ['required', 'file', 'image', 'max:10240'],
        ];
    }
}

