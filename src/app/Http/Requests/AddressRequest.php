<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment_method'        => ['nullable', 'string'],
            'shipping_postcode'      => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'],
            'shipping_address'       => ['required', 'string', 'max:255'],
            'shipping_building'      => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'shipping_postcode.required' => '郵便番号を入力してください',
            'shipping_postcode.regex'     => '郵便番号はハイフンを含めた8文字で入力してください',
            'shipping_address.required' => '住所を入力してください',
            'shipping_address.max' => '住所は255文字以内で入力してください',
            'shipping_building.max' => '建物名は255文字以内で入力してください',
        ];
    }
}
