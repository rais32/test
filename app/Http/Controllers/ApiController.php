<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;
use DB;
use App\UserAppModel;

class ApiController extends Controller
{
    
    public function addUser(Request $request){
        
        $dataJson["status_code"]     = 200;
        $dataJson["status"]          = "Success";

        $dataUser = DB::table('users_app')->where('name', '=', $request->input('username'))->get();
        
        if(count($dataUser)  == 0 ){
            $rules = array(
                            'username' => 'required|alpha_num',
                            'id_phone' => 'required'
                        );

            $messages = [
                'required'  => 'Input :attribute harus diisi',
                'alpha_num' => 'Input :attribute harus berupa angka atau huruf',
                'numeric'   => 'Input :attribute harus berupa angka',
            ];
            $validator = Validator::make($request->all(), $rules, $messages); 
            
            if(!$validator->fails()){  

                $dataAdd = array(
                                "name" => $request->input('username'),
                                "id_phone" => $request->input('id_phone'),
                                "updated_at" => date('Y-m-d G:i:s'),
                                "created_at" => date('Y-m-d G:i:s')
                            );
              
                DB::table('users_app')->insert($dataAdd);
            }       
            else{
                $dataJson["error_messages"] = $validator->messages();
                $dataJson["status"] = "Failed";
            }   
        } 
        else{
            $dataJson["error_messages"][] = "Nama sudah terpakai";
            $dataJson["suggetion_name"][] = $this->randomName($request->input('username'));
            $dataJson["suggetion_name"][] = $this->randomName($request->input('username'));
            $dataJson["status"] = "Failed";
        }  
        return response()->json($dataJson);   
	}

    public function insertPhoneNumber(Request $request){
        $dataJson["status_code"]     = 200;
        $dataJson["status"]          = "Success";
        
        $rules = array(
                        'username' => 'required',
                        'phone_number' => 'required|numeric'
                    );

        $messages = [
            'required'  => 'Input :attribute harus diisi',
            'numeric'   => 'Input :attribute harus berupa angka',
        ];
        $validator = Validator::make($request->all(), $rules, $messages); 
        
        if(!$validator->fails()){  
            $dataUser = DB::table('users_app')->where('name', '=', $request->input('username'))->get();
            if(count($dataUser)  > 0 ){
                $dataUpdate = array(
                                "phone_number" => $request->input('phone_number'),
                                "updated_at" => date('Y-m-d G:i:s')
                            );
              
                DB::table('users_app')
                ->where('name', $request->input('username'))
                ->update($dataUpdate);
            }
            else{
                $dataJson["error_messages"][] = "Username tidak terdaftar";
                $dataJson["status"] = "Failed";
            }
        }       
        else{
            $dataJson["error_messages"] = $validator->messages();
            $dataJson["status"] = "Failed";
        }   
         
        
        return response()->json($dataJson);
    }


    public function updateBarbieScore(Request $request){
        $dataJson["status_code"]     = 200;
        $dataJson["status"]          = "Success";
        
        $rules = array(
                        'username' => 'required',
                        'score' => 'required|numeric'
                    );

        $messages = [
            'required'  => 'Input :attribute harus diisi',
            'score'   => 'Input :attribute harus berupa angka',
        ];
        $validator = Validator::make($request->all(), $rules, $messages); 
        
        if(!$validator->fails()){  
            $dataUser = DB::table('users_app')->where('name', '=', $request->input('username'))->get();
            if(count($dataUser)  > 0 ){
                /*$dataUpdate = array(
                                "barbie_score" => $request->input('score'),
                                "updated_at" => date('Y-m-d G:i:s')
                            );
              
                DB::table('users_app')
                ->where('name', $request->input('username'))
                ->update($dataUpdate);*/

                UserAppModel::update_score_barbie($request->input('username'), $request->input('score'));
                
            }
            else{
                $dataJson["error_messages"][] = "Username tidak terdaftar";
                $dataJson["status"] = "Failed";
            }
        }       
        else{
            $dataJson["error_messages"] = $validator->messages();
            $dataJson["status"] = "Failed";
        }   
         
        
        return response()->json($dataJson);
    }


    public function updateHotwheelScore(Request $request){
        $dataJson["status_code"]     = 200;
        $dataJson["status"]          = "Success";
        
        $rules = array(
                        'username' => 'required',
                        'score' => 'required|numeric'
                    );

        $messages = [
            'required'  => 'Input :attribute harus diisi',
            'score'   => 'Input :attribute harus berupa angka',
        ];
        $validator = Validator::make($request->all(), $rules, $messages); 
        
        if(!$validator->fails()){  
            $dataUser = DB::table('users_app')->where('name', '=', $request->input('username'))->get();
            if(count($dataUser)  > 0 ){
                /*$dataUpdate = array(
                                "hotwheel_score" => $request->input('score'),
                                "updated_at" => date('Y-m-d G:i:s')
                            );
              
                DB::table('users_app')
                ->where('name', $request->input('username'))
                ->update($dataUpdate);*/
                UserAppModel::update_score_hotwheel($request->input('username'), $request->input('score'));
            }
            else{
                $dataJson["error_messages"][] = "Username tidak terdaftar";
                $dataJson["status"] = "Failed";
            }
        }       
        else{
            $dataJson["error_messages"] = $validator->messages();
            $dataJson["status"] = "Failed";
        }
        
        return response()->json($dataJson);
    }

    public function getCouponWinner(){
        
    }
    public function getToken(){
        $dataJson["token"] = csrf_token();
        return json_encode($dataJson, JSON_PRETTY_PRINT);
        
    }
    private function randomName($username){
        $randomName = $this->generateRandomString(4,"2");

        $dataUser = DB::table('users_app')->where('name', '=', $username.$randomName)->get();

        $suggestUsername = array();
        $flag = 0;

        if(count($dataUser) > 0){
            $this->randomName($username);
        }
        else{
            return $username.$randomName;
        }

    }

    private function generateRandomString($length, $type) {
        if($type == '1'){
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        else{
            $characters = '0123456789';
        }
        
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function coba(){
        return UserAppModel::coba();
    }
    private function tryCatch(){
        /*try{
            $statusCode = 200;
            $response = [
              'user'  => []
            ];

            $photos = DB::table('users_app')->get();
            if(!$photos){
                throw new \Exception("asdf");

            }
            foreach($photos as $photo){

                $response['photos'][] = [
                    'id' => $photo->id
                ];
            }
        }catch (\Exception $e){
            $statusCode = 400;
        }

        finally{
            //return Response::json($response, $statusCode);
            $dataJson['response'] = $response;
            $dataJson['status_code'] = $statusCode;
            return response()->json($dataJson);
        }*/
    }
}
