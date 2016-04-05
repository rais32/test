<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use App\UserAppModel;

class ApiController extends Controller
{
    //

    public function addUser(){
        try{
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
        }

	}

}
