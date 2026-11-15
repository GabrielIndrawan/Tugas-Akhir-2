<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscribtion;

class HistoryController extends Controller
{
    public function index(){
        $currentUser = Auth()->user()->id;
        $userTransactions = Subscribtion::where("user_id",$currentUser)->get();
        return view("app.user.history",["transactions" => $userTransactions]);
    }
}
