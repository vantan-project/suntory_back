<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MySetItem extends Model
{
    protected $fillable = ["my_set_id", "drink_id", "bottle_count"];

    protected static function booted()
    {
        static::deleted(function ($mySetItem) {
            $mySetItem->mySet()->update(['isLacking' => true]);
        });
    }

    public function drink(): BelongsTo
    {
        return $this->belongsTo(Drink::class);
    }

    public function mySet(): BelongsTo
    {
        return $this->belongsTo(MySet::class);
    }
}
