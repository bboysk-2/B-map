<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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
            'rating' => ['required','integer','min:1','max:5'],
            'comment' => ['nullable','string','max:255'],
        ];
    }

    public function messages()
    {
        return [
            'rating.required' =>'評価を選択してください。',
            'rating.integer' =>'評価を選択してください。',
            'rating.min' =>'評価を選択してください。',
            'rating.max' =>'評価を選択してください。',
            'comment.string' =>'コメントは文字列で入力してください。',
            'comment.max' =>'コメントは255文字以内で入力してください。',
        ];
    }
}
