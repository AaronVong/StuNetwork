<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelLike\Traits\Likeable;

class Toast extends Model
{
    use HasFactory, Likeable;
    protected $fillable=[
        "content"
    ];
    public function user(){
        return $this->belongsTo(User::class, "user_id", "id");
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
}
