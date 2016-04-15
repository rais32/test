<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class couponsModel extends Model
{
    //
    protected $table = 'coupons';
    public $timestamps = true;

    public static function insert_winner($username,$id_coupon, $coupon_number){
    	
        DB::beginTransaction();

		try {
		    $sql = "INSERT INTO winners (id_user,id_coupon, created_at, updated_at)
                            SELECT id, :id_coupon, NOW(), NOW() 
                            FROM users_app
                            WHERE 
                                name = :username";
        	DB::insert($sql, 
        			array("id_coupon" => $id_coupon, "username" => $username)
        		);

        	$sql_update = "UPDATE coupons SET `status` = '1', updated_at = NOW() WHERE id = :id_coupon";

        	DB::update($sql_update, 
        			array("id_coupon" => $id_coupon)
        		);
		    DB::commit();
		    return "TRUE_" . $coupon_number;
		} catch (\Exception $e) {
		    DB::rollback();
		    return false;
		}
    }
    public static function coba(){
    	return "sadf";
    }
}
