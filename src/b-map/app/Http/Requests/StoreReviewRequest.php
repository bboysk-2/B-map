<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'spot_id' => ['required','exists:spots,id'] , //spots テーブルの id カラムに存在しているか
            'rating' => ['required','integer','min:1','max:5'],
            'comment' => ['nullable','string'],
        ];
    }

    public function messages()
    {
        return [
            'rating.required' =>'評価を選択してください。',
            'rating.integer' =>'評価を選択してください。',
            'rating.min' =>'評価を選択してください。',
            'rating.max' =>'評価を選択してください。',
            'spot_id.required' =>'不正な操作です。',
            'spot_id.exists:spots,id' =>'不正な操作です。',
            'comment.string' =>'コメントは文字列で入力してください。',
        ];
    }
}
