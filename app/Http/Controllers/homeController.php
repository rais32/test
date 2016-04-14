<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
class homeController extends Controller
{
    //

    public function index(){

    	$this->data["title"] = "Dashboard";
    	if (Auth::check()){
    		return view('pages.home', $this->data);
    	}
    	else{
    		//die();
    		return view('pages.login', $this->data);
    	}
    }
}
