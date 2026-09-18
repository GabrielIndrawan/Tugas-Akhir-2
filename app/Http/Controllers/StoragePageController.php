<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoragePageController extends Controller
{
    //
    public function index(Request $request){
        return view("app.user.storage");
    }

    public function destroy(Request $request){
        return redirect()->intended(route("/"));
    }
}
