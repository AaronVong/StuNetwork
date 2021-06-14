<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToastFiles extends Model
{
    use HasFactory;
    protected $table = "toast_files";
    protected $fillable=[
        "id",
        "url",
        "type",
        "name"
    ];
    protected $keyType = 'string';
    public $incrementing = false;
    public function toast(){
        return $this->belongsTo(Toast::class, "toast_id", "id");
    }
}
