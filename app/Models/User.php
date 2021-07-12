<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Laravelista\Comments\Commenter;
use Overtrue\LaravelLike\Traits\Liker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasFactory, Notifiable, Liker, Commenter, HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];
    protected $connection = 'mysql';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 
     * Relationships
     * 
     */
    function profile(){
        return $this->hasOne(Profile::class,"user_id","id");
    }

    // function roles(){
    //     return $this->belongsToMany(Role::class);
    // }

    public function toasts(){
        return $this->hasMany(Toast::class,"user_id", "id");
    }

    public function followings(){
        return $this->belongsToMany(Profile::class, "user_follows", "follower_id", "following_id")->with("user:id,username");
    }

    public function followedCount(){
        return DB::table("user_follows")->where("following_id" ,"=",$this->id)->count();
    }

    public function messages(){
        return $this->hasMany(Message::class,"sender_id", "id");
    }

    public function rolesWithPermissions(){
            return $this->morphToMany(
            config('permission.models.role'),
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            'role_id'
        )->with("permissions");
    }
    /**
     * 
     * Helper
     * 
     */
    function isStudent(){
	    $domain = substr($this->email, strpos($this->email,"@")+1);
        return $domain !== false ? strpos($domain,"student"): false;
    }
}
