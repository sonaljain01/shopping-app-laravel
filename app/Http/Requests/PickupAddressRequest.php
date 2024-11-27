<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PickupAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === '0';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'address_1' => 'required|string',
            'address_2' => 'nullable|string',
            'phone' => 'required|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|numeric',
            'country' => 'required|string',
            'is_default' => 'nullable|boolean',
            'tags' => 'nullable|string|unique:pickup_addresses,tags',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'address_1.required' => 'Address 1 is required.',
            'address_1.string' => 'Address 1 must be a string.',
            'address_2.string' => 'Address 2 must be a string.',
            'phone.required' => 'Phone number is required.',
            'phone.numeric' => 'Phone number must be numeric.',
            'city.required' => 'City is required.',
            'city.string' => 'City must be a string.',
            'state.string' => 'State must be a string.',
            'state.required' => 'State is required.',
            'zip.required' => 'Zip code is required.',
            'zip.numeric' => 'Zip code must be numeric.',
            'country.required' => 'Country is required.',
            'country.string' => 'Country must be a string.',
            'is_default.boolean' => 'Is default must be a boolean.',
            'tags.string' => 'Tags must be a string.',
            'tags.unique' => 'Tags must be unique.',
        ];
    }
}
