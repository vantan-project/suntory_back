<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthSignUpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(AuthLoginRequest $request) 
    {
        $user = $request->validated()['user'];

        if (Auth::attempt(['email' => $user["email"], 'password' => $user["password"]])) {
            // 認証成功時の処理
            $authUser = Auth::user();
            return response()->json([
                'success' => true,
                'authToken' => $authUser->createToken('authToken')->plainTextToken,
                'isAdmin' => !!$authUser->is_admin,
            ]);
        } else {
            // 認証失敗時の処理
            return response()->json([
                'success' => false,
                'messages' => [
                    'メールアドレスまたはパスワードが正しくありません。',
                ]
            ], 401);
        }
    }

    public function signUp(AuthSignUpRequest $request)
    {
        $user = $request->validated()["user"];

        $createdUser = User::create([
            'name' => $user["name"],
            'email' => $user["email"],
            'password' => $user["password"],
        ]);

        Auth::login($createdUser);
        $token = $createdUser->createToken('authToken')->plainTextToken;
        return response()->json([
            'success' => true,
            'authToken' => $token,
        ]);
    }
}
