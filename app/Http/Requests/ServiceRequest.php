<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
        $isUpdate = $this->method() === 'PUT' || $this->method() === 'PATCH';

        return [
            'name' => ($isUpdate ? 'sometimes|' : '') . 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => ($isUpdate ? 'sometimes|' : '') . 'required|numeric|min:0',
            'available' => 'nullable|boolean',
        ];
    }
}
