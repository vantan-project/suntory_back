<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MySetItem extends Model
{
    protected $fillable = ["my_set_id", "drink_id", "bottle_count"];
}
