<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Product;

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

    public function plan()
    {
        $user = Auth::user();
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // ユーザーのメールで顧客を検索
        $customers = Customer::all([
            'limit' => 100,
            'email' => $user->email,
        ]);

        // 顧客をcreated日時でソートし、最新の顧客を取得
        usort($customers->data, function ($a, $b) {
            return $b->created - $a->created;  // 降順でソート
        });

        // 最新の顧客情報を取得
        if (count($customers->data) === 0) {
            return response()->json([
                "success" => true,
                "plan" => null,
            ]);
        }
        $latestCustomer = $customers->data[0];

        // 顧客のサブスクリプション情報を取得
        $subscriptions = Subscription::all([
            'customer' => $latestCustomer->id,
        ]);

        // サブスクリプションが存在しない場合はplanをnullに設定
        $plan = null;

        // サブスクリプションが存在する場合、プランIDを取得
        if (count($subscriptions->data) > 0) {
            // 最初のサブスクリプションのプランIDを取得
            $plan = $subscriptions->data[0]->items->data[0]->plan;
        }

        if ($plan === null) {
            // planがnullの場合は、"plan" => nullを返す
            return response()->json([
                "success" => true,
                "plan" => null,
            ]);
        }

        // planが存在する場合の処理
        $quantity = Product::retrieve($plan->product)->metadata["quantity"] ?? null;
        $amount = $plan->amount;

        return response()->json([
            "success" => true,
            "plan" => [
                "customerId" => $latestCustomer->id,
                "quantity" => intval($quantity),
                'amount' => $amount,
            ]
        ]);
    }
}
