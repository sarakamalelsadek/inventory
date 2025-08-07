<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function hasPermission(string|array $permission, $user = null) :bool
    {
        // check if user
        if(!$user) $user = Auth::user();

        $check = false;
        if($user){
            // check array
            if(is_array($permission)){
                $check = $user->hasAnyPermission($permission);
            }
            // check string
            else{
                $check = $user->hasPermissionTo($permission);
            }
        }
        return $check;
    }
}
