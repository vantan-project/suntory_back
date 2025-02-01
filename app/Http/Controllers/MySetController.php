<?php

namespace App\Http\Controllers;

use App\Http\Requests\MySetStoreRequest;
use App\Models\Drink;
use App\Models\MySet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class MySetController extends Controller
{
    public function store(MySetStoreRequest $request) {
        $user = Auth::user();
        $mySet = $request->validated()["mySet"];

        if (collect($mySet["items"])->sum('bottleCount') !== $user->masterPlan->quantity) {
            return response()->json([
                "success" => false,
                "messages" => ["{$user->masterPlan->quantity}本選択してください"],
            ]);
        }
        
        try {
            DB::transaction(function () use ($user, $mySet) {
                $createdMySet = MySet::create([
                    "user_id" => $user->id,
                    "name" => $mySet["name"],
                ]);

                $items = $mySet["items"];
                $createdMySet->mySetItems()->createMany(
                    collect($items)->map(function ($item) {
                        return [
                            "drink_id" => $item["drinkId"],
                            "bottle_count" => $item["bottleCount"],
                        ];
                    })
                );
            });
        } catch (Exception $e) {
            Log::warning($e);
            return response()->json([
                "success" => false,
                "messages" => ["登録に失敗しました"],
            ]);
        }

        return response()->json([
            "success" => true,
            "messages" => ["登録が完了しました"],
        ]);
    }
}
