<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request){
        $user = auth()->user();

        return view("app.user.home",["name"=>$user->id]);
    }

    public function destroy(Request $request){
        return redirect()->intended(route("/"));
    }
}
