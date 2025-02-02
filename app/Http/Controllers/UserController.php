<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function mySet() {
        $user = Auth::user();

        $mySet = $user->mySet()->with('mySetItems.drink')->first();
        if (!$mySet) {
            return response()->json([
                "success" => true,
                "mySet" => null,
            ]);
        }

        $maxBottleCountItem = $mySet->mySetItems->sortByDesc('bottle_count')->first();
        return response()->json([
            "success" => true,
            "mySet" => [
                "name" => $mySet->name,
                "isLacking" => !!$mySet->is_lacking,
                "imageUrl" => $maxBottleCountItem ? $maxBottleCountItem->drink->image_url : null,
                "items" => $mySet->mySetItems->map(function ($mySetItem) {
                    return [
                        "drinkName" => $mySetItem->drink->name,
                        "bottleCount" => $mySetItem->bottle_count,
                    ];
                }),
            ],
        ]);
    }
}
