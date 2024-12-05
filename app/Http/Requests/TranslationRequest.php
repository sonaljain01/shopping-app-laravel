<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TranslationRequest extends FormRequest
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
            'key' => 'required|string',
            'value' => 'required|string',
            'language' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'key.required' => 'Key is required',
            'key.string' => 'Key must be a string',
            'value.required' => 'Value is required',
            'value.string' => 'Value must be a string',
            'language.required' => 'Language is required',
            'language.string' => 'Language must be a string',
        ];
    }
}
