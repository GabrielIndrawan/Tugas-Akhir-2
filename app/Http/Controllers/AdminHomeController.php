<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\NextcloudUserProvider;

class AdminHomeController extends Controller
{
    //
    public function __construct(
        private NextcloudUserProvider $nextcloudUserProvider
    ) {}

    public function index(Request $request){

        $results = $this->nextcloudUserProvider->getAllUserwithProfileData();

        return view("app.admin.home",["users" => $results]);
    }

}
