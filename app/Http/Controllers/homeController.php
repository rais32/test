<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use cURL;
use App\Http\Requests;
use Auth;
use DB;
use App\UserAppModel;
use App\couponsModel;
use App\OptionsModel;
use Validator;
use PHPExcel; 
use PHPExcel_IOFactory;

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
    		return view('pages.login', $this->data);
    	}
    }

    public function showAddCoupon(){
        $this->data["title"] = "Dashboard";
        return view('pages.add_more_coupon', $this->data);
    }
    public function showListCoupon(Request $request){
        $dataCoupons  = DB::table('coupons')
                      ->select('coupons.coupon_number', 'coupons.created_at', 'coupons.status')
                      ->orderBy('coupons.created_at', 'desc');
        
        $this->data['page'] = 'list_coupons';

        if($request->input('search')){
            $this->data['queryString']['search'] = $request->input('search');
            $dataCoupons->where('coupons.coupon_number', 'like', '%'.$request->input('search').'%');
        }

        $this->data['dataCoupons'] = $dataCoupons->paginate(20);
        $this->data['dataCoupons']->setPath('list_coupons');
        return view('pages.list_coupons', $this->data);
    }
    public function uploadCoupon(Request $request){
        if($request->ajax()){

            $dataJson['t'] = 1;
            $rules = array(
                            'file' => 'required|max:1000:|mimes:xlsx,xls, csv'
                        );            
            $messages = array(
                'required'  => 'Input :attribute harus diisi',
                'max'       => 'File terlalu besar',
                'mimes'   => 'Format file harus .xls atau .xlsx',
            );

            $validator = Validator::make($request->all(), $rules, $messages); 
            
            if(!$validator->fails()){  
                $path_files = public_path() . $this->path_files;
                if (!file_exists($path_files)) {
                    mkdir($path_files, 0775, true);
                }
                $fileExcel  = $request->file('file');
                $fileName   = "list_coupons";
                
                if($fileExcel->move($path_files, "/" . $fileName. ".". $fileExcel->getClientOriginalExtension())){
                    
                    $path_excel = $path_files."/". $fileName. "." . $fileExcel->getClientOriginalExtension();
                    //die($path_excel);
                    try {

                        $inputFileType = PHPExcel_IOFactory::identify($path_excel);
                        
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                        
                        $objPHPExcel = $objReader->load($path_excel);

                    } catch(\Exception $e) {
                        $dataJson["error_messages"][] = $e->getMessage();
                    }

                    $sheet = $objPHPExcel->getSheet(0); 
                    $highestRow = $sheet->getHighestRow(); 
                    $highestColumn = $sheet->getHighestColumn();

                    for ($row = 2; $row <= $highestRow; $row++){ 
                        $rowData[] = array("coupon_number" => $sheet->getCellByColumnAndRow(1 ,$row)->getValue(),
                                            "created_at" => DB::raw("NOW()"),
                                            "updated_at" => DB::raw("NOW()")
                                            );

                    }
                    $dataReturn = couponsModel::insert_coupon($rowData);     

                    if($dataReturn != "Success"){
                        $dataJson["error_messages"][] = $dataReturn;
                        $dataJson["t"] = 0;
                    }
                }
                else{
                    $dataJson["error_messages"][] = "Failed to upload file";
                    $dataJson["t"] = 0;
                }
                
            }
            else{
                $dataJson["error_messages"] = $validator->messages();
                $dataJson["t"] = 0;
            }
            return response()->json($dataJson);
        }
        else{
            exit("Access Forbidden");
        }
        
    }

    public function showSendFormAll(){
        $this->data["title"] = "Dashboard";
        return view('pages.send_to_all', $this->data);    
    }

    public function postSendAll(Request $request){
        
        if($request->ajax()){
            $dataJson['t'] = 1;
            $rules = array(
                            'message' => 'required|max:100'
                        );            
            $messages = array(
                'required'  => 'Input :attribute harus diisi',
                'max'       => 'Input :attribute maximal 100 karakter'
            );

            $validator = Validator::make($request->all(), $rules, $messages); 
            
            if(!$validator->fails()){  
                $curl = curl_init();

                $dataUsers = DB::table('users_app')
                                ->select('id_phone')
                                ->get();

                for($x=0; $x<count($dataUsers); $x++){
                    $dataUsersSend[$x] = $dataUsers[$x]->id_phone; 
                }
                $dataJsonSend = array(
                    "registration_ids" => $dataUsersSend,
                    "data" => array(
                                    "title" => "Alpenliebe",
                                    "text" => $request->input('message')
                                )
                );
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://gcm-http.googleapis.com/gcm/send",
                  CURLOPT_SSL_VERIFYPEER=> FALSE,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => json_encode($dataJsonSend),
                  CURLOPT_HTTPHEADER => array(
                    "authorization: key=AIzaSyA3IQIokKmUN4fyru3HK9pRNss7F6OL1Lc",
                    "cache-control: no-cache",
                    "content-type: application/json"
                  ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                  $dataJson["error_messages"] = $err;
                } else {
                    $dataResponse = json_decode($response);
                    if($dataResponse->success < count($dataUsers)){
                        $dataJson["error_messages"][] = $dataResponse;
                        $dataJson["t"] = 0;
                    }
                }
            }
            else{
                $dataJson["error_messages"] = $validator->messages();
                $dataJson["t"] = 0;
            }
            return response()->json($dataJson);
        }
        else{
            exit("Access Forbidden");
        }
    }

    public function postSendUser(Request $request){
        
        if($request->ajax()){
            $dataJson['t'] = 1;
            $rules = array(
                            'message' => 'required|max:100',
                            'id'      => 'required'
                        );            
            $messages = array(
                'required'  => 'Input :attribute harus diisi',
                'max'       => 'Input :attribute maximal 100 karakter'
            );

            $validator = Validator::make($request->all(), $rules, $messages); 
            
            if(!$validator->fails()){ 

                $dataUser  = DB::table('users_app')
                              ->where('id','=', $request->input('id'))
                              ->get();

                if(count($dataUser) > 0){
                    $dataUsersSend[0] = $dataUser[0]->id_phone;
                    $curl = curl_init();

                    
                    $dataJsonSend = array(
                        "registration_ids" => $dataUsersSend,
                        "data" => array(
                                        "title" => "Alpenliebe",
                                        "text" => $request->input('message')
                                    )
                    );
                    
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://gcm-http.googleapis.com/gcm/send",
                      CURLOPT_SSL_VERIFYPEER=> FALSE,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_POSTFIELDS => json_encode($dataJsonSend),
                      CURLOPT_HTTPHEADER => array(
                        "authorization: key=AIzaSyA3IQIokKmUN4fyru3HK9pRNss7F6OL1Lc",
                        "cache-control: no-cache",
                        "content-type: application/json"
                      ),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                        $dataJson["error_messages"] = $err;
                    }   
                    else {
                        $dataResponse = json_decode($response);
                        if($dataResponse->success != '1'){
                            $dataJson["error_messages"][] = $dataResponse->results[0]->error;
                            $dataJson["t"] = 0;
                        }
                    }
                }
            }
            else{
                $dataJson["error_messages"] = $validator->messages();
                $dataJson["t"] = 0;
            }
            return response()->json($dataJson);
        }
        else{
            exit("Access Forbidden");
        }
    }

    public function showUsers(Request $request){
        $dataUsers  = DB::table('users_app')
                      ->select('users_app.id', 'users_app.name','users_app.phone_number', 'users_app.created_at')
                      ->orderBy('users_app.created_at', 'desc');
        
        $this->data['page'] = 'list_users';

        if($request->input('search')){
            $this->data['queryString']['search'] = $request->input('search');
            $dataUsers->where('users_app.name', 'LIKE', '%'.$request->input('search').'%')
                        ->orWhere('users_app.phone_number', 'LIKE', '%'.$request->input('search').'%');
        }

        $this->data['dataUsers'] = $dataUsers->paginate(20);
        $this->data['dataUsers']->setPath('list_users');
        return view('pages.list_users', $this->data);
    }

    public function showSendFormNotif($id){
        $this->data["title"]    = "Dashboard";        
        $this->data["id"]       = $id;

        $dataUser  = DB::table('users_app')
                      ->where('id','=', $id)
                      ->get();

        if(count($dataUser) > 0){
            $this->data["dataUser"] = $dataUser;
            return view('pages.send_to_user', $this->data);
        }
        else{
            return redirect()->route('');
        }
        
    }

    public function showWinner(Request $request){
        $dataWinners  = DB::table('users_app')
                      ->join('winners', 'users_app.id', '=', 'winners.id_user')
                      ->join('coupons', 'coupons.id', '=', 'winners.id_coupon')
                      ->select('users_app.name', 'coupons.coupon_number', 'winners.created_at')
                      ->orderBy('winners.created_at', 'desc');
        
        $this->data['page'] = 'list_winners';

        if($request->input('search')){
            $this->data['queryString']['search'] = $request->input('search');
            $dataWinners->where('users_app.name', 'like', '%'.$request->input('search').'%');
        }

        $this->data['dataWinners'] = $dataWinners->paginate(20);
        $this->data['dataWinners']->setPath('list_winners');
        return view('pages.list_winners', $this->data);
    }

    public function showOptions(){
        $this->data["title"]        = "Dashboard";

        $this->data["probMinOptions"]   = DB::table('options')
                                        ->where('key','=','min_prob')
                                        ->get();
        $this->data["probMaxOptions"]   = DB::table('options')
                                        ->where('key','=','max_prob')
                                        ->get();
        $this->data["maxWinnerOptions"]   = DB::table('options')
                                        ->where('key','=','max_winner')
                                        ->get();
        return view('pages.options_form', $this->data);
    }

    public function updateTotalWinners(Request $request){
        if($request->ajax()){
            $dataJson['t'] = 1;
            $rules = array(
                            'max_winner' => 'required|min:0|numeric'
                        );            
            $messages = array(
                'required'  => 'Input :attribute harus diisi',
                'min'       => 'Input :attribute minimal 0',
                'numeric'   => 'Input :attribute harus berupa angka',
            );

            $validator = Validator::make($request->all(), $rules, $messages); 
            
            if(!$validator->fails()){  
                $dataUpdates = array(
                                 "value" => $request->input('max_winner'),
                                 "updated_at" => DB::raw("NOW()")
                                );
                DB::table('options')
                    ->where('key', '=','max_winner')
                    ->update($dataUpdates);
                
            }
            else{
                $dataJson["error_messages"] = $validator->messages();
                $dataJson["t"] = 0;
            }
            return response()->json($dataJson);
        }
        else{
            exit("Access Forbidden");
        }
    }
    public function updateProbability(Request $request){
        if($request->ajax()){
            //die("asd");
            $dataJson['t'] = 1;
            $rules = array(
                            'min_prob' => 'required|min:0|numeric',
                            'max_prob' => 'required|min:0|numeric'
                        );            
            $messages = array(
                'required'  => 'Input :attribute harus diisi',
                'min'       => 'Input :attribute minimal 0',
                'numeric'   => 'Input :attribute harus berupa angka',
            );

            $validator = Validator::make($request->all(), $rules, $messages); 
            
            if(!$validator->fails()){  
                $dataUpdates[] = array(
                                 "value"        => $request->input('max_prob'),
                                 "key"          => "max_prob",
                                 "updated_at"   => DB::raw("NOW()")
                                );
                $dataUpdates[] = array(
                                 "value"        => $request->input('min_prob'),
                                 "key"          => "min_prob",
                                 "updated_at"   => DB::raw("NOW()")
                                );

                OptionsModel::update_options($dataUpdates);
            }
            else{
                $dataJson["error_messages"] = $validator->messages();
                $dataJson["t"] = 0;
            }
            return response()->json($dataJson);
        }
        else{
            exit("Access Forbidden");
        }
    }

    public function test(){

        $path_files = public_path() . $this->path_files;
        $path_excel = $path_files . "";
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();

        //  Loop through each row of the worksheet in turn

        for ($row = 1; $row <= $highestRow; $row++){ 
            //  Read a row of data into an array
            $rowData[] = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                            NULL,
                                            TRUE,
                                            FALSE);

            
            //  Insert row data array into your database of choice here
        }

        die(var_dump($rowData));
    }
}
