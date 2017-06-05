<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Contains site method used queries
 * 
 * @author     Ndot Team
 * @copyright  (c) 2008-2011 Ndot Team
 * @license    http://ndot.in/license
 */
 
class Model_Site extends Model 
{
	public function __construct()
	{
		$this->session = Session::instance();
		$this->currentdate=Commonfunction::getCurrentTimeStamp();
	}
    
	public function site_info_fetch()
	{
		$query=DB::select('app_description','email_id','phone_number','app_name','currency_symbol','currency_format')->from(SITEINFO)->limit(1)->execute()->as_array();
		return $query;
	}
	
	
  /*
	public function getallfooter()
	{
        $sql = 'SELECT * FROM '.CMS.' where status=1 and type=0 order by id'; //footer_menu
        $footer_result= Db::query(Database::SELECT, $sql)
                            ->execute()
                            ->as_array();  	
        return $footer_result;	
	}
  */
	
	
	
	
 /*

     //Users block/active/delete 
     //=================================
      */
      
	public function block_users_request($blockids)
	{
		//check whether id is exist in checkbox or single block request
		//==================================================================
		$arr_chk = " id in ('" . implode("','",$blockids) . "') ";
		$query = " UPDATE ". PEOPLE ." SET  status = 'D' WHERE $arr_chk ";
		$result = Db::query(Database::UPDATE, $query)
			->execute();
		return count($result);
	}
	public function active_users_request($activeids)
	{
		//check whether id is exist in checkbox or single active request
		//==================================================================

		$arr_chk = " id in ('" . implode("','",$activeids) . "') ";
		$query = " UPDATE ". PEOPLE ." SET  status = 'A' WHERE $arr_chk ";
		$result = Db::query(Database::UPDATE, $query)
		->execute();
		return count($result);
	}

	public function trash_users_request($trashids)
	{
		//check whether id is exist in checkbox or single trash request
		//==================================================================
		$arr_chk = " id in ('" . implode("','",$trashids) . "') ";
		$query = " UPDATE ". PEOPLE ." SET  status = 'T' WHERE $arr_chk ";
		$result = Db::query(Database::UPDATE, $query)
				->execute();
		return count($result);
	}

	public function delete_users_request($deleteids)
	{
		// People delete 
		//==================
		$arr_chk = " id in ('" . implode("','",$deleteids) . "') ";	
		$query = " DELETE FROM ". PEOPLE ."  WHERE $arr_chk ";		     
		$result = Db::query(Database::DELETE, $query)
				->execute();
		return $result;
	}
	
	public function block_passenger_request($blockids)
	{
		
		//check whether id is exist in checkbox or single block request
		//==================================================================       
		$arr_chk = " id in ('" . implode("','",$blockids) . "') ";
		$query = " UPDATE ". PASSENGERS ." SET  user_status = 'D' WHERE $arr_chk ";
		$result = Db::query(Database::UPDATE, $query)->execute();
		$passEmails = array();
		if($result) {
			$selQuery = "Select group_concat(`email` separator ',') as `passEmails`, group_concat(concat(`country_code`,`phone`) separator ',') as `passMobiles` from ".PASSENGERS." WHERE $arr_chk";
			$passEmails = Db::query(Database::SELECT, $selQuery)->execute()->as_array();
		}
		return $passEmails;
	}
	public function active_passenger_request($activeids)
	{
		//check whether id is exist in checkbox or single active request
		//==================================================================
		$arr_chk = " id in ('" . implode("','",$activeids) . "') ";
		$query = " UPDATE ". PASSENGERS ." SET  user_status = 'A' WHERE $arr_chk "; 
		$result = Db::query(Database::UPDATE, $query)->execute();
		$passEmails = array();
		if($result) {
			$selQuery = "Select group_concat(`email` separator ',') as `passEmails`, group_concat(concat(`country_code`,`phone`) separator ',') as `passMobiles` from ".PASSENGERS." WHERE $arr_chk";
			$passEmails = Db::query(Database::SELECT, $selQuery)->execute()->as_array();
		}
		return $passEmails;
	}

	public function trash_passenger_request($trashids)
	{
		//check whether id is exist in checkbox or single trash request
		//==================================================================
		$arr_chk = " id in ('" . implode("','",$trashids) . "') ";
		$query = " UPDATE ". PASSENGERS ." SET  user_status = 'T' WHERE $arr_chk ";     
		$result = Db::query(Database::UPDATE, $query)->execute();
		$passEmails = array();
		if($result) {
			$selQuery = "Select group_concat(`email` separator ',') as `passEmails`, group_concat(concat(`country_code`,`phone`) separator ',') as `passMobiles` from ".PASSENGERS." WHERE $arr_chk";
			$passEmails = Db::query(Database::SELECT, $selQuery)->execute()->as_array();
		}
		return $passEmails;
	}

	public function delete_passenger_request($deleteids)
	{
		// People delete 
		//==================

		$arr_chk = " id in ('" . implode("','",$deleteids) . "') ";
			$query = " DELETE FROM ". PASSENGERS ."  WHERE $arr_chk ";
				$result = Db::query(Database::DELETE, $query)
						->execute();
				return 1;

	}
//========================================================================================
/*
   
*/

//========================================================================================

   

}       
