<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;
use DB;
use Validator;
class homeController extends Controller
{
    
    public function index(){

    	$this->data["title"] = "HOME";
    	$this->data["uuid"]  = uniqid();
        $dataUsers  = DB::table('users')                          
                            ->orderBy('created_at', 'desc');
        $this->data['dataUsers'] = $dataUsers->get();
    	return view('pages.home', $this->data);
    	
    }

    public function AjaxAddUser(Request $request){
        
        if($request->ajax()){
            $dataJson['t'] = 1;
            $rules = array(
                    'name' => 'required|max:100',
                    'uuid' => 'required|max:100',                    
                    'address' => 'required|max:100'
                );

            $validator = Validator::make($request->all(), $rules); 
                        
            if(!$validator->fails()){ 
                $dataUsers     = DB::table('users')->where('nama', $request->input('name'))->get();
                if(count($dataUsers) > 0){
                    $dataJson["errors"]['name'][0]   = 'Name Already Exists';
                    $dataJson["t"]          = 0;
                      
                } 
                else{
                    $dataUser = array(
                        'nama' => $request->input('name'),
                        'uuid' => $request->input('uuid'),
                        'alamat' => $request->input('address'),
                        "created_at"    => DB::raw("NOW()"),
                        "updated_at"    => DB::raw("NOW()")
                    );
                    try{
                        DB::table('users')->insert($dataUser);
                        $dataJson['uuid'] = uniqid();
                    }
                    catch(\Exception $e){
                        $dataJson["errors"][]   = $e->getMessage();
                        $dataJson["t"]          = 0;
                    }  
                }    
                
            }
            else{
                $dataJson["errors"] = $validator->messages();
                $dataJson["t"] = 0;
            }       
            
            return response()->json($dataJson);  
        }

    }

    

}
