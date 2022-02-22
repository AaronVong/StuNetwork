<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SettingUser extends Pivot
{
    use HasFactory;
    protected $table = "setting_user";
    protected $casts =[
        "value" => "boolean",
    ];
}
