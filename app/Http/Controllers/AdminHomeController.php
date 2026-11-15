<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\NextcloudUserProvider;
use Illuminate\Support\Facades\Cache;

class AdminHomeController extends Controller
{
    //
    public function __construct(
        private NextcloudUserProvider $nextcloudUserProvider
    ) {}

    public function index(Request $request){

        $results = Cache::remember(
            "all_users_data",
            now()->addMinutes(5),
            fn() => $this->nextcloudUserProvider->getAllUserwithProfileData()
        );

        return view("app.admin.home",["users" => $results]);
        
    }

}
