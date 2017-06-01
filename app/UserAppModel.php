<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserAppModel extends Model
{
    //
    protected $table = 'users';
    //protected $timestamps = true;

    public static function update_score_barbie($username, $score){

    	DB::update('UPDATE users_app 
                    SET 
                        barbie_score = barbie_score + :score,
                        updated_at = NOW()
                    WHERE name = :name', ['name' => $username, 'score' => $score]
                    );
    }

    public static function update_score_hotwheel($username, $score){

    	DB::update('UPDATE users_app 
                    SET 
                        hotwheel_score = hotwheel_score + :score,
                        updated_at = NOW()
                    WHERE name = :name', ['name' => $username, 'score' => $score]
                    );
    }
}
