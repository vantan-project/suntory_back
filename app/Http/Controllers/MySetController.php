<?php

namespace App\Http\Controllers;

use App\Http\Requests\MySetStoreRequest;
use App\Models\MySet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class MySetController extends Controller
{
    public function index() {
        $user = Auth::user();
        $mySets = $user->mySets()
            ->with('mySetItems.drink')
            ->get()
            ->map(function ($mySet) {
                return [
                    "id" => $mySet->id,
                    "name" => $mySet->name,
                    "isLacking" => !!$mySet->is_lacking,
                    "imageUrl" => $mySet->mySetItems->sortByDesc('bottle_count')->first(),
                    "items" => $mySet->mySetItems->map(function ($mySetItem) {
                        return [
                            "drinkId" => $mySetItem->drink_id,
                            "drinkName" => $mySetItem->drink->name,
                            "imageUrl" => $mySetItem->drink->image_url,
                            "bottleCount" => $mySetItem->bottle_count,
                        ];
                    }),
                ];
            })
            ->toArray();

        return response()->json([
            "success" => true,
            "mySets" => $mySets,
        ]);
    }

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
                $createdMySet = $user->mySets()->create([
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

    public function destroy($id) {
        MySet::find($id)->delete();

        return response()->json([
            "success" => true,
            "messages" => ["削除しました"],
        ]);
    }
}
