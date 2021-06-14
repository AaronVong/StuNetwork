<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFollow extends Model
{
    use HasFactory;

    protected $increment = false;
    protected $table = "user_follows";
    protected $fillable = ["following_id", "follower_id"]
}
