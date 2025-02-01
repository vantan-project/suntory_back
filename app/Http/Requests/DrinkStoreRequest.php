<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DrinkStoreRequest extends FormRequest
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
            'drink.name' => 'required|string|max:255',
            'drink.imageData' => 'required|image|mimes:jpeg,png,jpg,gif',
            'drink.categoryId' => 'required|integer|exists:master_categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'drink.name.required' => '飲み物の名前は必須です。',
            'drink.name.string' => '飲み物の名前は文字列でなければなりません。',
            'drink.name.max' => '飲み物の名前は255文字以内でなければなりません。',
            
            'drink.imageData.required' => '飲み物の画像は必須です。',
            'drink.imageData.image' => '画像ファイルをアップロードしてください。',
            'drink.imageData.mimes' => '画像はJPEG、PNG、JPG、またはGIF形式でなければなりません。',
            
            'drink.categoryId.required' => 'カテゴリーは必須です。',
            'drink.categoryId.integer' => 'カテゴリーIDは整数でなければなりません。',
            'drink.categoryId.exists' => '指定されたカテゴリーIDは存在しません。',
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
