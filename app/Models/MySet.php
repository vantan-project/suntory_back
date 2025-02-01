<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MySet extends Model
{
    protected $fillable = ["user_id", "name"];

    public function mySetItems(): HasMany
    {
        return $this->hasMany(MySetItem::class);
    }
}
