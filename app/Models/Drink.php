<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Drink extends Model
{
    protected $fillable = ["master_category_id", "name", "image_url", "buy_count"];

    public function masterCategory()
    {
        return $this->belongsTo(MasterCategory::class);
    }

    public function mysetItems(): HasMany
    {
        return $this->hasMany(MySetItem::class);
    }
}
