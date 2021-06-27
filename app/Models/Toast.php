<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Laravelista\Comments\Commentable;
use Overtrue\LaravelLike\Traits\Likeable;

class Toast extends Model
{
    use HasFactory, Likeable, Commentable;
    protected $fillable=[
        "content"
    ];

    public function user(){
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function ownerProfile(){
        return $this->hasOne(Profile::class, "user_id", "user_id");
    }

    public function files(){
        return $this->hasMany(ToastFiles::class,"toast_id", "id");
    }

    public function likes(){
        return $this->morphMany(Like::class,"likeable");
    }

    public function likedBy(User $user){
        return $this->likes->contains("user_id", $user->id);
    }

    public function toastComments(){
        return $this->morphMany(Config::get("comments.model"), "comments","commentable_type","commentable_id","id");
    }
}
