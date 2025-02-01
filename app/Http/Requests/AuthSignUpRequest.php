<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthSignUpRequest extends FormRequest
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
            'user.name' => ['required', 'string', 'max:255'],
            'user.email' => ['required', 'email', 'unique:users,email'],
            'user.password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'user.email.required' => 'メールアドレスは必須です',
            'user.email.email' => '正しいメールアドレス形式で入力してください',
            'user.email.unique' => 'このメールアドレスは既に登録されています',
            'user.password.required' => 'パスワードは必須です',
            'user.password.string' => 'パスワードは文字列で入力してください',
            'user.name.required' => '名前は必須です',
            'user.name.string' => '名前は文字列で入力してください',
            'user.name.max' => '名前は255文字以内で入力してください',
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
