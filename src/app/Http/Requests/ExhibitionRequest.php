<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name'          => ['required', 'string', 'max:255'],
            'brand_name'    => ['nullable', 'string', 'max:255'],
            'description'   => ['required', 'string', 'max:255'],
            'item_image'         => ['required', 'mimes:jpeg,png', 'image'],
            'category_ids'  => ['required', 'array'],
            'status'        => ['required', 'in:0,1,2,3'],
            'price'         => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'name.required'         => '商品名を入力してください',
            'name.max'         => '商品名は255文字以内で入力してください',
            'brand_name.max'         => 'ブランド名は255文字以内で入力してください',
            'description.required'  => '商品の説明を入力してください',
            'description.max'  => '商品の説明は255文字以内で入力してください',
            'item_image.required'        => '商品画像は必須です',
            'item_image.mimes'           => '画像の形式はJPEGまたはPNGのみ対応しています',
            'item_image.image'           => '指定されたファイルが画像ではありません',
            'category_ids.required' => 'カテゴリーを1つ以上選択してください',
            'status.required'       => '商品の状態を選択してください',
            'price.required'        => '販売価格を入力してください',
            'price.integer'         => '販売価格は半角数字で入力してください',
            'price.min'             => '0円以上の金額を入力してください',
        ];
    }
}
