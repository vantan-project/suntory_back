<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MySetItem extends Model
{
    protected $fillable = ["my_set_id", "drink_id", "bottle_count"];

    public function drink(): BelongsTo
    {
        return $this->belongsTo(Drink::class);
    }
}
