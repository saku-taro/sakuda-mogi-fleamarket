<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment_method' => ['required', 'string'],
            'shipping_postcode'      => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'],
            'shipping_address'       => ['required', 'string', 'max:255'],
            'shipping_building'      => ['nullable', 'string', 'max:255'],
            'total_price'       => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'shipping_postcode.required' => '配送先を入力してください',
            'shipping_address.required' => '配送先を入力してください',
        ];
    }
}
