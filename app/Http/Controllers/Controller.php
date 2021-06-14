<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    # Kiểm tra trạng thái của DB
    public function DBConnection(){
        try{
            $pdo = DB::connection('mysql')->getPdo();
        }catch(\Exception $e){
            return back()->with("register_status", "Hệ thống không khả dụng, vui lòng thử lại sau");
        }
    }

    # Lấy id của file từ đường dẫn
    public function getGoogleDriveFileId($url){
        if(strlen($url)<=0)return false;
        $idPart = substr($url, strpos($url, "id="));
        if(strlen($idPart)<=0)return false;
        $matches=[];
        preg_match('/id=(.+)(\&.+)$/', $idPart, $matches);
        return array_key_exists(1, $matches) ? $matches[1] : false;        
    }

    # Upload file lên drive
    public function uploadFile($file,$folderID){
        try{
            $path = $file->storePubliclyAs($folderID,$file->getClientOriginalName(),"google");
            $url = Storage::disk("google")->url($path);
            $fileID = $this->getGoogleDriveFileId($url);
            $extension = $file->extension();
            return ["url" => $url, "fileID" => $fileID, "extension" => $extension];
        }catch(Exception $err){
            return false;
        }
        return false;
    }
}
