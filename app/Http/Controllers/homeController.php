<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use DB;
use App\UserAppModel;
class homeController extends Controller
{
    //

    public function index(){

    	$this->data["title"] = "Dashboard";
    	if (Auth::check()){
            $this->data['topTenBarbie'] = DB::table('users_app')
                    ->select('name', 'barbie_score')
                    ->orderBy('barbie_score', 'desc')
                    ->take(10)
                    ->get();
            $this->data['topTenHotwheel'] = DB::table('users_app')
                    ->select('name', 'hotwheel_score')
                    ->orderBy('hotwheel_score', 'desc')
                    ->take(10)
                    ->get();
    		return view('pages.home', $this->data);
    	}
    	else{
    		//die();
    		return view('pages.login', $this->data);
    	}
    }
}
