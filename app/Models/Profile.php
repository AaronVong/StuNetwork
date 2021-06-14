<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable= [
        "fullname",
        "phone",
        "birthday",
        "address",
        "gender"
    ];

    function user(){
        return $this->belongsTo(User::class,"user_id","id");
    }

    function followers(){
        return $this->belongsToMany(User::class, "user_follows", "following_id", "follower_id");
    }
}
