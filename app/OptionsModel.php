<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class OptionsModel extends Model
{
    //
	protected $table = 'options';
    public static function update_options($dataUpdateArray){
    	DB::beginTransaction();
        try {
        	foreach ($dataUpdateArray as $dataUpdate) {
        		$updating = array(
                                 "value" => $dataUpdate['value'],
                                 "updated_at" => $dataUpdate['updated_at']
                                );
        		DB::table('options')
                    ->where('key', '=',$dataUpdate['key'])
                    ->update($updating);
        	}
            DB::commit();
            return "Success";
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
