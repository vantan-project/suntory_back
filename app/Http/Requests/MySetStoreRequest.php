<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MySetStoreRequest extends FormRequest
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
            'mySet' => 'required|array',
            'mySet.name' => 'required|string|max:255',
            'mySet.items' => 'required|array',
            'mySet.items.*.drinkId' => 'required|integer',
            'mySet.items.*.bottleCount' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'mySet.required' => 'MySetは必須です。',
            'mySet.name.required' => '名前は必須です。',
            'mySet.name.string' => '名前は文字列でなければなりません。',
            'mySet.items.required' => 'アイテムは必須です。',
            'mySet.items.array' => 'アイテムは配列でなければなりません。',
            'mySet.items.*.drinkId.required' => '飲み物IDは必須です。',
            'mySet.items.*.drinkId.integer' => '飲み物IDは整数でなければなりません。',
            'mySet.items.*.bottleCount.required' => 'ボトル数は必須です。',
            'mySet.items.*.bottleCount.integer' => 'ボトル数は整数でなければなりません。',
            'mySet.items.*.bottleCount.min' => 'ボトル数は1以上でなければなりません。',
            'quantity.required' => '数量は必須です。',
            'quantity.integer' => '数量は整数でなければなりません。',
            'quantity.min' => '数量は1以上でなければなりません。',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'messages' => collect($validator->errors()->messages())
                    ->flatten()
                    ->toArray()
            ], 422)
        );
    }
}
