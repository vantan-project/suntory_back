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
            "messages" => ["登録に成功しました"],
        ]);
    }
}
