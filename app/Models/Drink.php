<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    protected $fillable = ["master_category_id", "name", "image_url", "buy_count"];
}
