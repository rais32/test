<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;
use DB;
use App\UserAppModel;
use App\couponsModel;

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
                                "updated_at" => DB::raw("NOW()"),
                                "created_at" => DB::raw("NOW()")
                            );
              
                DB::table('users_app')->insert($dataAdd);
            }       
            else{
                $messageError = json_decode($validator->messages(), true);
                //die(var_dump($messageError["username"][0]));

                if(isset($messageError["username"][0]) && isset($messageError["id_phone"][0])){
                    $dataJson["error_messages"][] = "Tolong masukkan nama dan id_phone";
                }
                else if(isset($messageError["id_phone"][0])){
                    $dataJson["error_messages"][] = "Tolong masukkan id_phone";
                }
                else{
                    $dataJson["error_messages"][] = "Tolong masukkan username";
                }
                
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
                        'username'      => 'required',
                        'phone_number'  => 'required|numeric',
                        'type'          => 'required'
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
                                "updated_at" => DB::raw("NOW()")
                            );
                try{
                    DB::table('users_app')
                    ->where('name', $request->input('username'))
                    ->update($dataUpdate);
                }
                catch(\Exception $e){
                    $dataJson["error_messages"][]   = $e->getMessage();
                    $dataJson["status"]             = "Failed";
                }

                if($dataJson["status"] != "Failed"){
                    $dataUserKey = DB::table('options')
                                    ->where('key','=','sms_userkey')
                                    ->get();
                    $dataPassKey = DB::table('options')
                                    ->where('key','=','sms_passkey')
                                    ->get();

                    $dataCoupon = DB::table('winners')
                                    ->join('coupons', 'winners.id_coupon', '=', 'coupons.id')
                                    ->join('users_app', 'users_app.id', '=', 'winners.id_user')
                                    ->where('users_app.name','=',$request->input('username'))
                                    ->where(DB::raw("date(winners.created_at)"),'=',DB::raw("date(NOW())"))
                                    ->where('type','=', $request->input('type'))
                                    ->select('coupons.coupon_number')
                                    ->get();

                    $pesanSms = "";

                    if($request->input('type') == '1'){
                        //$pesanSms = "Selamat anda menang \nini no kupon Anda : ". $dataCoupon[0]->coupon_number . "barbie";    
                        $pesanSms = "Selamat!\nMini Barbie gratis buat kamu! Tebus di Alfamart tertentu dengan\npembelian 10 pcs Alpenliebe Lollipop dan tunjukkan kode promo: ". $dataCoupon[0]->coupon_number . ". Info: 021-7822867";
                    }
                    else{
                        //$pesanSms = "Selamat anda menang \nini no kupon Anda : ". $dataCoupon[0]->coupon_number . "hotwheel";       
                        $pesanSms = "Selamat!\nHot Wheels gratis buat kamu! Tebus di Alfamart tertentu dengan\npembelian 10 pcs Alpenliebe Lollipop dan tunjukkan kode promo: ". $dataCoupon[0]->coupon_number . ". Info: 021-7822867";
                    }
                    
                    
                    
                    if(count($dataCoupon) > 0){
                        $dataArraySend = "userkey=".$dataUserKey[0]->value."&passkey=".$dataPassKey[0]->value."&nohp=".$request->input('phone_number')."&pesan=". $pesanSms;
                    
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => "https://alpha.zenziva.net/apps/smsapi.php",
                            CURLOPT_SSL_VERIFYPEER=> FALSE,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $dataArraySend,
                            CURLOPT_HTTPHEADER => array(
                                                    "cache-control: no-cache"
                                                    )
                        ));

                        $response = curl_exec($curl);
                        $err = curl_error($curl);

                        $xmlResponse=simplexml_load_string($response);
                        
                        
                        if ($err) {
                            $dataJson["error_messages"] = $err;
                            $dataJson["status"] = "Failed";
                            $dataJson["coupon_number"] = "FALSE";
                        }
                        else if($xmlResponse->message->text != "Success"){
                            $dataJson["error_messages"] = $xmlResponse->message->text;
                            $dataJson["status"] = "Failed";
                            $dataJson["coupon_number"] = "FALSE";
                        }
                    }
                    else{
                        $dataJson["error_messages"][] = "Tidak terdapat no kupon";
                        $dataJson["status"] = "Failed";    
                    }

                }

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
                    'score'     => 'Input :attribute harus berupa angka'
                    ];
        $validator = Validator::make($request->all(), $rules, $messages); 
        
        if(!$validator->fails()){  
            $dataUser = DB::table('users_app')->where('name', '=', $request->input('username'))->get();
            if(count($dataUser)  > 0 ){

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

    public function getCouponWinner(Request $request){
        $dataJson["status_code"]     = 200;
        $dataJson["status"]          = "Success";
        //
        $rules = array(
                        'username' => 'required',
                        'type' => 'required'
                    );

        $messages = array('required'  => 'Input :attribute harus diisi');
        $validator = Validator::make($request->all(), $rules, $messages); 
        
        if(!$validator->fails()){  

            $dataMinProb = DB::table('options')
                            ->where('key','=','min_prob')
                            ->get();
            $dataMaxProb = DB::table('options')
                            ->where('key','=','max_prob')
                            ->get();

            $dataMaxWinner = DB::table('options')
                            ->where('key','=','max_winner')
                            ->get();
            
            $rand = rand(1,$dataMaxProb[0]->value);

            $sql = "SELECT winners.id 
                    FROM winners 
                    JOIN users_app ON winners.id_user = users_app.id
                    WHERE 
                        users_app.name = :username
                    AND date(winners.created_at) = CURDATE()";

            $dataUser = DB::select(DB::raw($sql), array('username' => $request->input('username')));

            $sql_winner = "SELECT id 
                    FROM winners 
                    WHERE
                        date(winners.created_at) = CURDATE()";

            $dataAllWinner  = DB::select(DB::raw($sql_winner));
            $dataUserSend   = DB::table('users_app')
                              ->where('name','=',$request->input('username'))
                              ->get();
            
            //die(count($dataAllWinner) . " " . $dataMaxWinner[0]->value);

            /*if(count($dataUserSend) > 0 && $rand <= $dataMinProb[0]->value && count($dataUser) < 1 && count($dataAllWinner) < $dataMaxWinner[0]->value){
                die("a");
            }
            else{
                die("b");
            }*/
            if(count($dataUserSend) > 0 && $rand <= $dataMinProb[0]->value && count($dataUser) < 1 && count($dataAllWinner) < intval($dataMaxWinner[0]->value)){
                
                $dataCoupon = DB::table('coupons')
                                ->where('status', '=', '0')
                                ->where('type', '=', $request->input('type'))
                                ->take(1)
                                ->get();

                if(count($dataCoupon) > 0){
                    $returnValue = couponsModel::insert_winner($request->input('username'),$dataCoupon[0]->id, $dataCoupon[0]->coupon_number);

                    if($returnValue){
                        $dataJson["coupon_number"] = $returnValue;
                    }
                    else{
                        $dataJson["error_messages"][] = "Database error";
                        $dataJson["status"] = "Failed";
                        $dataJson["coupon_number"] = "FALSE";
                    }
                }
                else{
                    $dataJson["error_messages"][] = "Kupon tidak tersedia";
                    $tersi["status"] = "Failed";
                    $dataJson["coupon_number"] = "FALSE";
                }

            }
            else{
                $dataJson["error_messages"][] = "Anda belum berhasil";
                $dataJson["status"] = "Failed";
                $dataJson["coupon_number"] = "FALSE";
            }
        }       
        else{
            $dataJson["error_messages"] = $validator->messages();
            $dataJson["status"] = "Failed";
            $dataJson["coupon_number"] = "FALSE";
        }
        
        return response()->json($dataJson);
        
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

    public function getLeaderBoardHotwheel(Request $request){
        
        $dataJson["status_code"]     = 200;
        $dataJson["status"]          = "Success";
        //
        $rules = array(
                        'username' => 'required'
                    );

        $messages = array('required'  => 'Input :attribute harus diisi');
        $validator = Validator::make($request->all(), $rules, $messages); 
        
        if(!$validator->fails()){
            $dataUser = DB::table('users_app')->where('name', '=', $request->input('username'))->get();

            if(count($dataUser) > 0){
                $sql = "SELECT number, name, score FROM (
                            (SELECT @s:=@s+1 AS number, name, hotwheel_score AS score, id FROM users_app ,(SELECT @s:= 0) AS s ORDER BY `score` DESC LIMIT 10)
                            UNION
                            (SELECT
                                (
                                    SELECT COUNT(id) FROM users_app 
                                    WHERE hotwheel_score >= (
                                        SELECT hotwheel_score FROM users_app WHERE name = ?
                                    )

                                )AS number, name, hotwheel_score, id FROM users_app WHERE name = ?)
                        ) a GROUP BY id ORDER BY `number` ASC";
                //die($sql);
                $dataLeaderBoard = DB::select(DB::raw($sql), 
                                        array($request->input('username'), $request->input('username'))
                                    );

                if(count($dataLeaderBoard) > 0){
                    $dataJson["leader_board"]   = $dataLeaderBoard;
                    $dataJson["total_data"]   = count($dataLeaderBoard);
                }
                else{
                    $dataJson["error_messages"] = "Tidak ada data";
                    $dataJson["status"] = "Failed";
                    $dataJson["coupon_number"] = "FALSE";
                }
            }
            else{
                $dataJson["error_messages"] = "Username tidak terdaftar";
                $dataJson["status"] = "Failed";
                $dataJson["coupon_number"] = "FALSE";
            }
        }
        else{
            $dataJson["error_messages"] = $validator->messages();
            $dataJson["status"] = "Failed";
            $dataJson["coupon_number"] = "FALSE";
        }

        return response()->json($dataJson);
    }

    public function getLeaderBoardBarbie(Request $request){
        
        $dataJson["status_code"]     = 200;
        $dataJson["status"]          = "Success";
        //
        $rules = array(
                        'username' => 'required'
                    );

        $messages = array('required'  => 'Input :attribute harus diisi');
        $validator = Validator::make($request->all(), $rules, $messages); 
        
        if(!$validator->fails()){
            $dataUser = DB::table('users_app')->where('name', '=', $request->input('username'))->get();

            if(count($dataUser) > 0){
                $sql = "SELECT number, name, score FROM (
                            (SELECT @s:=@s+1 AS number, name, barbie_score AS score, id FROM users_app ,(SELECT @s:= 0) AS s ORDER BY `score` DESC LIMIT 10)
                            UNION
                            (SELECT
                                (
                                    SELECT COUNT(id) FROM users_app 
                                    WHERE barbie_score >= (
                                        SELECT barbie_score FROM users_app WHERE name = ?
                                    )

                                )AS number, name, barbie_score, id FROM users_app WHERE name = ?)
                        ) a GROUP BY id ORDER BY `number` ASC";

                $dataLeaderBoard = DB::select(DB::raw($sql), 
                                        array($request->input('username'), $request->input('username'))
                                    );

                if(count($dataLeaderBoard) > 0){
                    $dataJson["total_data"]   = count($dataLeaderBoard);
                    $dataJson["leader_board"] = $dataLeaderBoard;
                    
                }
                else{
                    $dataJson["error_messages"] = "Tidak ada data";
                    $dataJson["status"] = "Failed";
                    $dataJson["coupon_number"] = "FALSE";
                }
            }
            else{
                $dataJson["error_messages"] = "Username tidak terdaftar";
                $dataJson["status"] = "Failed";
                $dataJson["coupon_number"] = "FALSE";
            }
        }
        else{
            $dataJson["error_messages"] = $validator->messages();
            $dataJson["status"] = "Failed";
            $dataJson["coupon_number"] = "FALSE";
        }

        return response()->json($dataJson);
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
        return couponsModel::coba();
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
