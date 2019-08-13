<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Response;
use DB;
use Validator;
use Redirect;
use Session;
use Illuminate\Validation\Rule;
class AssigmentController extends Controller
{







public function get_api(Request $req){



	$result=$this->call_json_data_get_api("https://swapi.co/api/people/");
	$http_result=$result['http_result'];
	$error=$result['error'];
	$obj = json_decode($http_result);
	return   $obj->results;


}




public function call_json_data_get_api($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$http_result = curl_exec($ch);
	$error = curl_error($ch);
	$http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
	curl_close($ch);
	$result=array('http_result' =>$http_result ,'error'=>$error );
	return $result;

}


public function assigment_add(Request $req){


 
try{

	if(isset($req->films)){

	$val=Validator::make($req->All(), [
		'name' => [
		'required',
		Rule::unique('ass_tbl'),
		],
		]);

	if ($val->fails()){
		 return response()->json(array('messages' =>$val->messages(),'status'=>1 ), 200);

	}else{
		$id = DB::table('ass_tbl') ->insertGetId( ['name' =>$req->name]);

		// $a=str_replace('[', ' ', $req->arrdata); 
		// $b= str_replace(']', ' ', $a); 
		// foreach (explode(",",$b) as $key => $value) {DB::table('films_tbl') ->insert( ['films_name' =>$value,'ass_tbl_id'=>$id]);}

		foreach ($req->films as $key => $value) {DB::table('films_tbl') ->insert( ['films_name' =>$value,'ass_tbl_id'=>$id]);}
         
      $query=DB::table('ass_tbl')
    ->leftJoin('films_tbl', 'ass_tbl.id', '=', 'films_tbl.ass_tbl_id')
    ->get();
       $flg=0;
         } 
         return response()->json(array('messages' =>$query,'status'=>0 ), 200);
}

}catch(Exception $e){
 return response()->json(array('messages' =>$e,'status'=>2 ), 404);
}


}


}
