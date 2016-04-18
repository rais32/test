<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use DB;
use App\UserAppModel;
use App\couponsModel;
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
                        $rowData[]["coupon_number"] = $sheet->getCellByColumnAndRow(1 ,$row)->getValue();
                    }
                    $dataReturn = couponsModel::insert_coupon($rowData);     

                    if($dataReturn != "Success"){
                        $dataJson["error_messages"][] = "Duplikasi nomor coupon";
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
