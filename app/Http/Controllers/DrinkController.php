<?php

namespace App\Http\Controllers;

use App\Http\Requests\DrinkStoreRequest;
use App\Models\Drink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class DrinkController extends Controller
{
    public function index(Request $request) {
        $search = $request["search"];

        $drinks = Drink::orderBy("buy_count", "desc")
            ->orderBy("created_at", "desc")
            ->with('masterCategory');
        if ($search["name"]) {
            $drinks = $drinks->where("name", "like", "%" . $search["name"] . "%");
        }
        if ($search["categoryId"]) {
            $drinks = $driniks->where("master_category_id", $search["categoryId"]);
        }

        return response()->json([
            "success" => true,
            "drinks" => $drinks->get()->map(function ($drink) {
                return [
                    "id" => $drink->id,
                    "name" => $drink->name,
                    "imageUrl" => $drink->image_url,
                    "categoryId" => $drink->master_category_id,
                    "categoryName" => $drink->masterCategory->name,
                ];
            }),
        ]);
    }

    public function store(DrinkStoreRequest $request) {
        $drink = $request->validated()["drink"];

        try {
            DB::transaction(function () use ($drink) {
                Drink::create([
                    "name" => $drink["name"],
                    "image_url" => config('filesystems.disks.s3.url') . '/' . Storage::disk('s3')->putFile('suntory-app', $drink["imageData"]),
                    "master_category_id" => $drink["categoryId"],
                ]);
            });
        } catch (Exception $e) {
            Log::warning($e);
            return response()->json([
                "success" => false,
                "messages" => ["登録に失敗しました"],
            ], 500);
        }

        return response()->json([
            "success" => true,
            "messages" => ["登録が完了しました"],
        ]);
    }

    public function destroy($id) {
        $drink = Drink::find($id);

        foreach ($drink->mySetItems as $mySetItem) {
            $mySetItem->mySet()->update([
                'is_lacking' => true,
            ]);
        }
        $drink->delete();

        return response()->json([
            "success" => true,
            "messages" => ["削除が完了しました"],
        ]);
    }

    public function new()
    {
        return response()->json([
            "success" => true,
            "imageUrls" => Drink::orderBy('created_at', 'desc')
                ->take(5)
                ->pluck('image_url'),
        ]);
    }
}
