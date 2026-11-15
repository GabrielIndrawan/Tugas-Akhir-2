<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\NextCloud\NextCloudWebDavClient;
use Illuminate\Http\Request;
use App\Services\Auth\NextcloudUserProvider;

class StoragePageController extends Controller
{
    public function __construct(
        private NextCloudUserProvider $nextcloudUserProvider,
     ){}
    //
    public function index(Request $request){

        $files = $this->nextcloudUserProvider->getUserFiles(auth()->user()->id, session('nextcloud_app_password'));

        if(auth()->user()->id === "admin"){
            return view("app.admin.storage", ["user" => auth()->user(), "files" => $files]);
        }

        return view("app.user.storage", ["user" => auth()->user(), "files" => $files]);
    }

    public function destroy(Request $request){
        return redirect()->intended(route("/"));
    }
}
