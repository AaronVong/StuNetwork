<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SettingUser;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SettingUserController extends Controller
{
    function changeSettings(Request $request){
        if(!$request->ajax()){
            return throw new HttpException(404);
        }
        
        if($request->user()->username != auth()->user()->username){
            return throw new HttpException(403);
        }
        
        foreach($request->newSettings as $setting){
            $request->user()->settings()->updateExistingPivot($setting["id"], ["value" => $setting["pivot"]["value"]]);
        }
        return response(["settings" => $request->user()->settings], 200);
    }

    function userSettings(Request $request, $username){
        if(!$request->ajax()){
            return throw new HttpException(404);
        }

        // Xác minh danh tính người request
        if($request->user()->username != $username){
            return throw new HttpException(403);
        }

        $settings = auth()->user()->settings;
        return response()->json(["settings" => $settings],200);
    }

    function settings(Request $request){
        if(!$request->ajax()){
            return throw new HttpException(404);
        }
        $settings = Setting::all();
        return response(["settings"=> $settings],200);       
    }
}
