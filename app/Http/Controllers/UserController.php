<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
    public function plan(Request $request)
    {
        $user = Auth::user();
        $planId = $request["planId"];
        try {
            DB::transaction(function ($connection, $transaction) use ($user, $planId) {
                $user->update([
                    "master_plan_id" => $planId,
                ]);
            });
        } catch (Exception $e) {
            Log::warning($e);
            return response()->json([
                "success" => false,
                "messages" => ["プランの変更に失敗しました"],
            ]);
        }

        return response()->json([
            "success" => true,
            "messages" => ["プランの変更に成功しました"],
        ]);
    }
}
