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
        // return auth()->check() && auth()->user()->role === '0';
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
            'user_id' => 'nullable|numeric',
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'phone' => 'required|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'pincode' => 'required|string',
            'country' => 'required|string',
            'is_default' => 'nullable|boolean',
            'tag' => 'nullable|string|unique:pickup_addresses,tag',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'address.required' => 'Address 1 is required.',
            'address.string' => 'Address 1 must be a string.',            
            'phone.required' => 'Phone number is required.',
            'phone.numeric' => 'Phone number must be numeric.',
            'city.required' => 'City is required.',
            'city.string' => 'City must be a string.',
            'state.string' => 'State must be a string.',
            'state.required' => 'State is required.',
            'pincode.required' => 'Zip code is required.',
            'pincode.numeric' => 'Zip code must be numeric.',
            'country.required' => 'Country is required.',
            'country.string' => 'Country must be a string.',
            'is_default.boolean' => 'Is default must be a boolean.',
            'tag.string' => 'Tags must be a string.',
            'tag.unique' => 'Tags must be unique.',
        ];
    }
}
