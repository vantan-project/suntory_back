<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class SettingController extends Controller
{
    public function plan(Request $request)
    {
        $user = Auth::user();
        $planId = $request["planId"];
        try {
            DB::transaction(function () use ($user, $planId) {
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

    public function mySet(Request $request)
    {
        $user = Auth::user();
        $mySetId = $request["mySetId"];
        try {
            DB::transaction(function () use ($user, $mySetId) {
                $user->update([
                    "my_set_id" => $mySetId,
                ]);
            });
        } catch (Exception $e) {
            Log::warning($e);
            return response()->json([
                "success" => false,
                "messages" => ["マイセットの変更に失敗しました"],
            ]);
        }

        return response()->json([
            "success" => true,
            "messages" => ["マイセットの変更に成功しました"],
        ]);
    }
}
