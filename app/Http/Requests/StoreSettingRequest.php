<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingRequest extends FormRequest
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
            'store_name' => 'required|string',
            'store_address' => 'required|string',
            'store_city' => 'required|string',
            'store_state' => 'required|string',
            'store_pin' => 'required|string',
            'store_country' => 'required|string',
            'store_phone' => 'required|string',
            'gst_number' => 'required|string',
            'tax_type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'store_name.required' => 'Store name is required',
            'store_name.string' => 'Store name must be a string',
            'store_address.required' => 'Store address is required',
            'store_address.string' => 'Store address must be a string',
            'store_city.required' => 'Store city is required',
            'store_city.string' => 'Store city must be a string',
            'store_state.required' => 'Store state is required',
            'store_state.string' => 'Store state must be a string',
            'store_pin.required' => 'Store pin is required',
            'store_pin.string' => 'Store pin must be a string',
            'store_country.required' => 'Store country is required',
            'store_country.string' => 'Store country must be a string',
            'store_phone.required' => 'Store phone is required',
            'store_phone.string' => 'Store phone must be a string',
            'gst_number.required' => 'GST number is required',
            'gst_number.string' => 'GST number must be a string',
            'tax_type.required' => 'Tax type is required',
            
        ];
    }
}
