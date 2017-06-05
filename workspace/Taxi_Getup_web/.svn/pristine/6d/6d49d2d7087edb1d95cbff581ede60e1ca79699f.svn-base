<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Contains commonly used queries

 * @author     Ndot Team

 * @license    http://ndot.in/license

 */
 
class Model_Commonmodel extends Model 
{
	public function __construct()
    {
		$this->session = Session::instance();
		$this->currentdate=Commonfunction::getCurrentTimeStamp();
    }
    
	public function insert($table,$arr)
	{	
		$result =  DB::insert($table,array_keys($arr))->values(array_values($arr))
			     ->execute();	
		/*echo $response = Debug::vars((string) $result);	exit;	     */

		return $result;
	}
	
	public function update($table,$arr,$cond1,$cond2)
	{
		$result=DB::update($table)->set($arr)->where($cond1,"=",$cond2)->execute();
		//print_r($result);
		return $result;
	}
	
	public function delete($table,$cond1,$cond2)
	{
		$result=DB::delete($table)->where($cond1,'=',$cond2)->execute();
		return $result;
	}
	
	public function select($table,$cond1,$cond2)
	{
		$result=DB::select('*')->from($table)->where($cond1,'=',$cond2)->execute()->as_array();
		return $result;
	}
	
	public function select_site_settings($field="",$table)
	{

		$result=DB::select($field)->from($table)->limit(1)->execute()->as_array();
		if(count($result)>0)
		{
			return $result[0][$field];
		}
		else
		{
			return;
		}
	}

	public function get_meta_settings($field="",$action)
	{

		$sql = "SELECT ".CMS.".$field FROM ".CMS." join ".MENU." on ".CMS.".menu_id = ".MENU.".menu_id WHERE menu_link = '$action' and ".MENU.".status_post='P' "; 

		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array(); 	
		//echo '<pre>';print_r($sql);exit;
		if(count($result)>0)
		{
			$response = $allfields = array();
			$field = explode(',',$field);
			foreach($field as $f){
				$allfields[$f] = $result[0][$f];
			}
			$response[] = $allfields;
			//echo '<pre>';print_r($response);exit;
			return $response;
		}
		else
		{
			return;
		}
	}
		
	/*Function Used to get the 
	 * Driver and Taxi Details 
	 * based upon the ID 
	*/
	public function get_driver_details($id)
	{
		$taxi_id = DB::select('mapping_taxiid')->from(TAXIMAPPING)
					->where('mapping_driverid','=',$id)				
					->where('mapping_status','=','A')
					->where('mapping_startdate','<=',$this->currentdate)
					->where('mapping_enddate','>=',$this->currentdate)					
					->execute()
					->get('mapping_taxiid');
					
					$mapping_companyid = DB::select('mapping_companyid')->from(TAXIMAPPING)
					->where('mapping_driverid','=',$id)				
					->where('mapping_status','=','A')
					->where('mapping_startdate','<=',$this->currentdate)
					->where('mapping_enddate','>=',$this->currentdate)					
					->execute()
					->get('mapping_companyid');
		
		//Driver Rating Need to bind with the result
		$ratings['comments'] = DB::select()->from(PASSENGERS_LOG)	
					->where('driver_id','=',$id)		
					->where('travel_status','=',1)		
					->order_by('createdate','DESC')	
					->limit(5)->offset(0)
					->execute()
					->as_array();


		if(isset($_SESSION['search_city']))
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".city_name like '%".$_SESSION['search_city']."%'  limit 0,1"; 
		}		
		else
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1"; 
		}

		$model_fetch = Db::query(Database::SELECT, $model_base_query)
				->execute()
				->as_array();

		if(count($model_fetch) > 0)
		{
			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}
		else
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1"; 

			$model_fetch = Db::query(Database::SELECT, $model_base_query)
				->execute()
				->as_array();

			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}

		if(FARE_SETTINGS == 2)
		{
			$fare_details_query = "select company.company_name,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".id=company.cid ) as cancellation_nfree,(SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km,(SUM(company_model_fare.waiting_time)*($city_model_fare)/100) + motor_model.waiting_time as waiting_time,company_model_fare.below_above_km as below_above_km,motor_model.model_name,taxi.* from taxi 
			join company on taxi.taxi_company=company.cid
			JOIN ".COMPANY_MODEL_FARE." as company_model_fare ON company_model_fare.`model_id`=taxi.`taxi_model` 
			join motor_model on taxi.taxi_model = motor_model.model_id 
			where company_cid=".$mapping_companyid." and taxi_id=".$taxi_id;
		}
		else
		{
			$fare_details_query = "select company.company_name,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".id=company.cid ) as cancellation_nfree,(SUM(motor_model.base_fare)*($city_model_fare)/100) + motor_model.base_fare as base_fare,(SUM(motor_model.min_fare)*($city_model_fare)/100) + motor_model.min_fare as min_fare,(SUM(motor_model.cancellation_fare)*($city_model_fare)/100) + motor_model.cancellation_fare as cancellation_fare,(SUM(motor_model.below_km)*($city_model_fare)/100) + motor_model.below_km as below_km,(SUM(motor_model.above_km)*($city_model_fare)/100) + motor_model.above_km as above_km,(SUM(motor_model.waiting_time)*($city_model_fare)/100) + motor_model.waiting_time as waiting_time,motor_model.below_above_km as below_above_km,motor_model.model_name,taxi.* from taxi 
			join company on taxi.taxi_company=company.cid 
			join motor_model on taxi.taxi_model = motor_model.model_id 
			where taxi_id=".$taxi_id;
		}
//echo $fare_details_query; exit;
			$result = Db::query(Database::SELECT, $fare_details_query)
				->execute()
				->as_array();
										
					
		$taxi_additional_field = DB::select('*')->from(ADDFIELD)->where('taxi_id','=',$taxi_id)->execute()->as_array();

		$additional_field['label_name'] = DB::select('*')->from(MANAGEFIELD)->where('field_status','=','A')->execute()->as_array();
															
			
		return array_merge($result,$taxi_additional_field,$ratings,$additional_field);
	}

	/** Driver availability **/
	public function get_driver_availability($driver_id,$company_id,$pickup_time)
	{
		/*$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `pickup_time` < '".$pickup_time."'  and `driver_id` = '".$driver_id."' and `driver_reply` = 'A' and `travel_status` != 1 order by passengers_log_id desc limit 1 "; */
		///echo $company_id;
		if($company_id == '')
		{
				$current_time =	date('Y-m-d H:i:s');
				$start_time = date('Y-m-d').' 00:00:01';
				$end_time = date('Y-m-d').' 23:59:59';
		}	
		else
		{
			$timezone_base_query = "select time_zone from  company where cid='$company_id' "; 
			$timezone_fetch = Db::query(Database::SELECT, $timezone_base_query)
					->execute()
					->as_array();			

			if($timezone_fetch[0]['time_zone'] != '')
			{
				$current_time = convert_timezone('now',$timezone_fetch[0]['time_zone']);
				$current_date = explode(' ',$current_time);
				$start_time = $current_date[0].' 00:00:01';
				$end_time = $current_date[0].' 23:59:59';
			}
			else
			{
				$current_time =	date('Y-m-d H:i:s');
				$start_time = date('Y-m-d').' 00:00:01';
				$end_time = date('Y-m-d').' 23:59:59';
			}
		}		
		if($company_id != '')
		{
			$condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'";
			$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `pickup_time` < '".$pickup_time."'  and `driver_id` = '".$driver_id."' and `driver_reply` = 'A' and `travel_status` != 1 and `travel_status` != 4  $condition order by passengers_log_id desc limit 1 ";
			//echo $sql;
		}
		else
		{
			$condition = "AND ".PASSENGERS_LOG.".pickup_time >='".date('Y-m-d 00:00:01')."'";
			$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `pickup_time` < '".$pickup_time."'  and `driver_id` = '".$driver_id."' and `driver_reply` = 'A' and `travel_status` != 1 and `travel_status` != 4  $condition order by passengers_log_id desc limit 1 ";
			//echo $sql;
		}
		//echo $sql;
		$availablity = Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
			
		return $availablity	;
	}
	
	//Set Driver Status in Active in DRIVER Table
	public function set_status_driver($lat, $long,$max,$id,$status)	
	{		
		
		$data = $this->get_randorm_values($lat,$long,$max);		
		$dat_arr = explode("$",$data);	
		
		$sql_query = array(
						'latitude' => $dat_arr[0],
						'longitude' =>$dat_arr[1],
						'status' =>$status
					);
					
		$result =  DB::update(DRIVER)->set($sql_query)
					->where('driver_id', '=' ,$id)
					->execute();	
			
		return $data;
	}
	
	function get_randorm_values($lat,$long,$max)
	{
		
		$longitude = (float) $long;
		$latitude = (float) $lat;
		$radius = rand(1,$max); // in miles

		$lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 69);
		$lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 69);
		$lat_min = $latitude - ($radius / 69);
		$lat_max = $latitude + ($radius / 69);
	
		return $lat_max ."$" .$lng_max;

	}
	
	public function get_passenger_details($id)
	{ 
		$sql = "SELECT * FROM ".PASSENGERS." WHERE id = '$id' "; 
		//echo $sql;exit;
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}
	//Get driver current status from driver break status 
	public function get_driver_current_break_status($id)
	{ //echo $id;
		$sql = "SELECT * FROM ".DRIVERBREAKSERVICE." WHERE driver_break_service_id = '$id' "; 
		//echo $sql;
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}
	//Get driver current Shift status from driver shift
	public function get_driver_current_shift_status($driver_id)
	{ 
		$result  = DB::select('*')->from(DRIVER)
					->join(DRIVERSHIFTSERVICE)->on(DRIVERSHIFTSERVICE.'.driver_id','=',DRIVER.'.driver_id')				
					->where(DRIVER.'.driver_id','=',$driver_id)	
					->where('shift_start','!=','0000-00-00 00-00-00')
					->where('shift_end','=','0000-00-00 00-00-00')		
					->execute()
					->as_array();
		if(count($result)>0){
								 $shift_status = $result[0]['shift_status'];
								}else{
									$shift_status= "";
									}
		
		//$sql = "SELECT * FROM ".DRIVERSHIFTSERVICE." WHERE driver_shift_id = '$id' "; 
		//echo $sql;
		return $shift_status; 	
	}	
	function get_motor_model($motor_type)
	{
		$sql = "SELECT * FROM ".MOTORMODEL." WHERE motor_mid = '$motor_type' "; 
		
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}

	function getcontents($action)
	{
		$sql = "SELECT * FROM ".CMS." join ".MENU." on ".CMS.".menu_id = ".MENU.".menu_id WHERE menu_link = '$action' and ".MENU.".status_post='P' "; 
		
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
			
	}

	public static function header_citylist($country_id)
	{

			$result = DB::select()->from(CITY)->join(STATE, 'LEFT')->on(CITY.'.city_stateid', '=', STATE.'.state_id')->join(COUNTRY, 'LEFT')->on(CITY.'.city_countryid', '=', COUNTRY.'.country_id')->where('city_countryid','=',$country_id)->where('state_status','=','A')->where('city_status','=','A')->order_by('city_name','ASC')
				->execute()
				->as_array();
	
			return $result;

	}

	/**get country details**/
	public function country_details()
	{
		$result = DB::select()->from(COUNTRY)->where('country_status','=','A')->order_by('country_name','asc')
			->execute()
			->as_array();
		  return $result;
	}
	/**get city details**/
	public function city_details()
	{
		$result = DB::select()->from(CITY)->where('city_status','=','A')->order_by('city_name','asc')
			->execute()
			->as_array();
		  return $result;
	}
	/**get state details**/
	public function state_details()
	{
		$result = DB::select()->from(STATE)->where('state_status','=','A')->order_by('state_name','asc')
			->execute()
			->as_array();
		  return $result;
	}

	/** get location **/
	public function company_location($cid)
	{
		if($cid!="0"){ 
			$result = DB::select('login_country','login_state','login_city')->from(PEOPLE)
			->where('company_id','=',$cid)
			->where('user_type','=','C')
			->limit(1)
			->execute()
			->as_array();
		}else{
			$result = DB::select('login_country','login_state','login_city')->from(PEOPLE)
			->where('user_type','=','A')
			->limit(1)
			->execute()			
			->as_array();
		}
			return $result;
	}
	

	/**get state details**/
	public function gateway_details($company_id=null,$booktype=null)
	{
		$get_company = array();
		if($company_id != null && $company_id != ''){
			$get_company = DB::select('company_id')->from(COMPANY_PAYMENT_MODULES)->where('company_id','=',$company_id)
				->execute()
				->as_array();
		}
		
		if(count($get_company)>0)
		{
			if($booktype == 1)
			{
				/// Query which is used for getiing payment modules without cash payments for dispatch
				$result = DB::select('pay_mod_id','pay_mod_name','pay_mod_default')->from(COMPANY_PAYMENT_MODULES)
						->where('pay_active','=','1')
						->where('pay_mod_id','!=','1')
						->where('company_id','=',$company_id)
						->order_by('pay_mod_id','asc')
				->execute()
				->as_array();
			}
			else
			{
				$result = DB::select('pay_mod_id','pay_mod_name','pay_mod_default')->from(COMPANY_PAYMENT_MODULES)
						->where('pay_active','=','1')
						->where('company_id','=',$company_id)
						->order_by('pay_mod_id','asc')
				->execute()
				->as_array();
			}
		}
		else
		{
			if($booktype == 1)
			{
				/// Query which is used for getiing payment modules without cash payments for dispatch
				$result = DB::select('pay_mod_id','pay_mod_name','pay_mod_default')->from(PAYMENT_MODULES)->where('pay_mod_active','=','1')->where('pay_mod_id','!=','1')->order_by('pay_mod_id','asc')
				->execute()
				->as_array();
			}
			else
			{
				$result = DB::select('pay_mod_id','pay_mod_name','pay_mod_default')->from(PAYMENT_MODULES)->where('pay_mod_active','=','1')->order_by('pay_mod_id','asc')
				->execute()
				->as_array();
			}
		}
		return $result;
	}

	public function sms_message($sms_id='')
	{
		$result = DB::select('sms_title','sms_description')->from(SMS_TEMPLATE)->where('sms_id','=',$sms_id)->order_by('sms_id','asc')
			->execute()
			->as_array();
		  return $result;
	}

	public function get_passengers_details($value='',$check ='')
	{ 
		if($check == 1)
		{
			$sql = "SELECT * FROM ".PASSENGERS." WHERE email = '$value' "; 
		}
		else
		{
			$sql = "SELECT * FROM ".PASSENGERS." WHERE phone = '$value' "; 
		}	
		//echo $sql;exit;
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}

	public function get_drivers_details($value='',$check ='')
	{ 
		if($check == 1)
		{
			$sql = "SELECT * FROM ".PEOPLE." WHERE email = '$value' and user_type='D'"; 
		}
		else
		{
			$sql = "SELECT * FROM ".PEOPLE." WHERE phone = '$value' and user_type='D'"; 
		}	
		//echo $sql;exit;
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}
	//Get driver current Shift status from driver shift
	public function get_driver_currentstatus($driver_id)
	{ 
		$result  = DB::select('shift_status')->from(DRIVER)
					->where(DRIVER.'.driver_id','=',$driver_id)	
					->execute()
					->as_array();
		if(count($result)>0)
		{
			$shift_status = $result[0]['shift_status'];
		}
		else
		{
			$shift_status= "";
		}
		
		//$sql = "SELECT * FROM ".DRIVERSHIFTSERVICE." WHERE driver_shift_id = '$id' "; 
		//echo $sql;
		return $shift_status; 	
	}	

	public function package_details()
	{
		$result = DB::select('package_name')->from(PACKAGE)->order_by('package_name','asc')
			->execute()
			->as_array();
		  return $result;
	}


	public function update_commission($pass_logid,$total_amount,$admin_commission)
	{
		$company_query = "select company_id,driver_id from passengers_log where passengers_log_id =".$pass_logid;
		$company_results = Db::query(Database::SELECT, $company_query)->execute()->as_array();
		$company_id = $company_results[0]['company_id'];
		$driver_id = $company_results[0]['driver_id'];
		$first_query = "select check_package_type from " . PACKAGE_REPORT . " join ".PACKAGE." on ".PACKAGE.".package_id =".PACKAGE_REPORT.".upgrade_packageid  where ".PACKAGE_REPORT.".upgrade_companyid = ".$company_id."  order by upgrade_id desc limit 0,1";
		$first_results = Db::query(Database::SELECT, $first_query)->execute()->as_array();
		if(count($first_results) > 0)
		{
			$check_package_type = $first_results[0]['check_package_type'];
		}
		else
		{
			$check_package_type = 'T';
		}

		if($check_package_type != 'N')
		{
			$admin_amt = ($total_amount * $admin_commission)/100; //payable to admin
			$admin_amt = round($admin_amt, 2);
			$total_balance = round($total_amount,2);

			//Set Commission to Admin
			if(ADMIN_COMMISION_SETTING) {
				$updatequery = " UPDATE ". PEOPLE ." SET account_balance = account_balance+$admin_amt WHERE user_type = 'A'";
				$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
			}
		}
		else
		{
			$admin_amt = 0;
		}

		$company_amt = $total_amount - $admin_amt;
		$company_amt = round($company_amt, 2);

		//Set Commission to Company
		if(COMPANY_COMMISION_SETTING) {
			$updatequery = " UPDATE ". PEOPLE ." SET account_balance = account_balance+$company_amt WHERE user_type = 'C' and company_id=".$company_id;
			$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
			/* if($company_id > 0) {
				$company_query = " UPDATE ". COMPANYINFO ." SET company_available_amount = company_available_amount+$company_amt WHERE company_cid = ".$company_id;
				Db::query(Database::UPDATE, $company_query)->execute();
			} */
		}
			$driver_commission_amt = 0;
		
			$sit_info = $this->common_site_info();

			//$driver_trip_amount = (isset($sit_info[0]['driver_trip_amount']))?($sit_info[0]['driver_trip_amount']*$total_amount/100):1;
			$driver_trip_amount= (isset($sit_info[0]['driver_trip_amount']))?$sit_info[0]['driver_trip_amount']:0;
			//added balance
			$people_data = $this->get_people_detail($driver_id);
			$datas['receiver_id']=$driver_id;
			$datas['receiver_pervious_balance']=(isset($people_data['account_balance']) && $people_data['account_balance'])?$people_data['account_balance']:0;
			$datas['receiver_after_balance']=$datas['receiver_pervious_balance']-$driver_trip_amount;
			$datas['transfer_amount']=$driver_trip_amount;
			$datas['transfer_type']=2;
			$datas['comments']="Trip Amount";
			$datas['trip_id']=$pass_logid;
					   
			$add_logs = $this->set_driver_cash_transfer($datas); 
		
		
		//Set Commission to Driver
		if(DRIVER_COMMISION_SETTING) {
			$driver_com_query = "select driver_commission from ".COMPANY." where cid =".$company_id;
			$driver_com_result = Db::query(Database::SELECT, $driver_com_query)->execute()->as_array();
			$driver_commission = isset($driver_com_result[0]['driver_commission']) ? $driver_com_result[0]['driver_commission'] : 0;
			$driver_commission_amt = round(($company_amt*$driver_commission/100),2);
			$company_bal_amt = $company_amt-$driver_commission_amt;

			
			$updatequery = " UPDATE ". PEOPLE ." SET account_balance = account_balance-$driver_trip_amount WHERE user_type = 'D' and id = '$driver_id'";
			$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
		}
		$result_array = array();
		$result_array['admin_commission'] = $admin_amt;
		$result_array['company_commission'] = $company_amt;
		$result_array['driver_commission'] = $driver_commission_amt;
		$result_array['trans_packtype'] = $check_package_type;
		return $result_array;
	}
	//Getting Current Time of the company Time
	public function getcompany_all_currenttimestamp($company_id)
	{
		if(empty($company_id))
		{
			$current_time = convert_timezone('now',TIMEZONE);
			$current_date = explode(' ',$current_time);
			$start_time = $current_date[0].' 00:00:01';
			$end_time = $current_date[0].' 23:59:59';
			$date = $current_date[0].' %';
		}	
		else
		{
			$timezone_base_query = "select time_zone from  company where cid='$company_id' limit 1"; 
			
			$timezone_fetch = Db::query(Database::SELECT, $timezone_base_query)
					->execute()
					->as_array();			
			$timezonefetch=isset($timezone_fetch[0]['time_zone'])?$timezone_fetch[0]['time_zone']:"";
			if($timezonefetch != '')
			{
				$current_time = convert_timezone('now',$timezone_fetch[0]['time_zone']);
				$current_date = explode(' ',$current_time);
			}
			else
			{
				$current_time = convert_timezone('now',TIMEZONE);
				$current_date = explode(' ',$current_time);
				$start_time = $current_date[0].' 00:00:01';
				$end_time = $current_date[0].' 23:59:59';
				$date = $current_date[0].' %';
			}
		}
		return $current_time;
	}
	
		//Getting Current Time of the company Time
	public function getdriver_currenttimestamp($driver_id)
	{

			$timezone_base_query = "select time_zone from ".PEOPLE." join ".COMPANY." on ".COMPANY.".cid=".PEOPLE.".company_id where id=".$driver_id.""; 
			
			$timezone_fetch = Db::query(Database::SELECT, $timezone_base_query)
					->execute()
					->as_array();			

			if($timezone_fetch[0]['time_zone'] != '')
			{
				$current_time = convert_timezone('now',$timezone_fetch[0]['time_zone']);
				$current_date = explode(' ',$current_time);
			}
			else
			{
				$current_time = convert_timezone('now',TIMEZONE);
				$current_date = explode(' ',$current_time);
				$start_time = $current_date[0].' 00:00:01';
				$end_time = $current_date[0].' 23:59:59';
				$date = $current_date[0].' %';
			}
		return $current_time;
	}

	public function company_tax($company_id='')
	{
			$tax_query = "select company_tax from  companyinfo where company_cid='$company_id' "; 
			$tax_fetch = Db::query(Database::SELECT, $tax_query)
					->execute()
					->as_array();
			if(count($tax_fetch) > 0)
			{
				return  $tax_fetch[0]['company_tax'];
			}
			else
			{
				return  0;
			}	
	}

	public function company_timezone($company_id='')
	{


			$timezone_base_query = "select time_zone from  company where cid='$company_id' "; 
			$timezone_fetch = Db::query(Database::SELECT, $timezone_base_query)
					->execute()
					->as_array();						
			if(count($timezone_fetch)>0)
			{
				return  $timezone_fetch[0]['time_zone'];
			}
			else
			{
				return TIMEZONE;
			}
	}
	
	public function getcountry_currecny_details()
	{
		//~ $getcurrencycode = DB::select('currency_code','currency_symbol')->from(COUNTRY)
				//~ ->order_by('country_id','ASC')
				//~ ->execute();
		//~ return $getcurrencycode;
	}
	
	/*public function getcurrencycode()
	{
		$getcurrencycode = DB::select('currency_code')->from(COUNTRY)
				->order_by('country_id','ASC')
				->execute();
		return $getcurrencycode;
	}
	public function getcurrencysymbol()
	{
		$getcurrencysymbol = DB::select('currency_symbol')->from(COUNTRY)
				->order_by('country_id','ASC')
				->execute();
		return $getcurrencysymbol;
	}*/

	public function get_passengerlogdetails($log_id)
	{
			$driverid_query = "select driver_id from  passengers_log where passengers_log_id='$log_id' "; 
			$driverid_fetch = Db::query(Database::SELECT, $driverid_query)
					->execute()
					->as_array();

			$driver_id = $driverid_fetch[0]['driver_id'];


			if($driver_id !='')
			{
				$drivername_query = "select name from  people where id='$driver_id' "; 
				$drivername_fetch = Db::query(Database::SELECT, $drivername_query)
						->execute()
						->as_array();
				$driver_name = $drivername_fetch[0]['name'];
			}
			else
			{
				$driver_name = '';	
			}

			return $driver_name;

	}
	
	public function sms_message_by_title($sms_title='')
	{
		$result = DB::select('sms_id','sms_title','sms_info','sms_description','status')->from(SMS_TEMPLATE)->where('sms_title','=',$sms_title)->where('status','=',0)->order_by('sms_id','asc')
			->execute()
			->as_array();
		  return $result;
	}
	
	//**** Booking key generator *******/
	
	public function get_randonkey()
	{
		// Booking key generator //
			$bookingkey_query = "select concat(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1)) as random_key from passengers_log Having NOT EXISTS (select booking_key from passengers_log having booking_key=random_key) limit 1";
		 	$bookingkey_result = Db::query(Database::SELECT, $bookingkey_query)
				 	->execute()			
					->as_array();

			if(count($bookingkey_result) > 0)
			{
					$booking_key = $bookingkey_result[0]['random_key'];
			}
			else
			{
					$bookingkey_query = "select concat(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1)) as random_key";
				 	$bookingkey_result = Db::query(Database::SELECT, $bookingkey_query)
				 	->execute()			
					->as_array();
					$booking_key = $bookingkey_result[0]['random_key'];
			}
			return $booking_key;
	}
	
	/** get location **/
	public function get_country_details($cid)
	{
		$result = DB::select(PEOPLE.'.login_country',PEOPLE.'.login_state',PEOPLE.'.login_city',COUNTRY.'.country_name')->from(PEOPLE)->join(COUNTRY,'LEFT')->on(COUNTRY.'.country_id','=',PEOPLE.'.login_country')->where(PEOPLE.'.company_id','=',$cid)->execute()->as_array();//->where(PEOPLE.'.user_type','=','C')
		return $result;
	}
	/** Function to send sms using twilio sms gateway **/
	public function send_sms($to,$message)
	{
		/*
		require_once(DOCROOT.'application/vendor/smsgateway/Services/Twilio.php');
		$sid = "ACea0fb39945c1b57c0f948ce6be14f2f0"; // Your Account SID from www.twilio.com/user/account (live Acc - ACea0fb39945c1b57c0f948ce6be14f2f0 ) Test - ACb79cfb562f7936e28ea106c0e3100a6b
		$token = "334ca1214e28671839f7be3a4d3eca36"; // Your Auth Token from www.twilio.com/user/account (live Acc - 334ca1214e28671839f7be3a4d3eca36 ) Test - c81ac0e14e32a43ed6e00e844831ef7f
		
		try
		{
			$client = new Services_Twilio($sid, $token);
			$res = $client->account->messages->sendMessage(
			"+14245437407", // From a valid Twilio number  Test Number (+15005550006)
			 $to, // Text this number
			 $message
			); 
		}
		catch(Exception $e)
		{
			//echo $e;exit;
		}

		*/


             $Url = 'http://sms.theblunet.com/send-sms/?username=GetupTaxi&password=get123&body='.$message.'&numbers='.$to.'&language=en';

	    // OK cool - then let's create a new cURL resource handle
	    $ch = curl_init();
	 
	    // Now set some options (most are optional)
	 
	    // Set URL to download
	    curl_setopt($ch, CURLOPT_URL, $Url);
	 
	    // Include header in result? (0 = yes, 1 = no)
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	 
	    // Should cURL return or print out the data? (true = return, false = print)
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 
	    // Timeout in seconds
	    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	 
	    // Download the given URL, and return output
	    $output = curl_exec($ch);
	 
	    // Close the cURL resource, and free system resources
	    curl_close($ch);


	    return 1;	


	}
	/**get country details**/
	public function getCountryTblFlds($field,$countryId)
	{
		$result = DB::select($field)->from(COUNTRY)->where('country_id','=',$countryId)->execute()->get($field);
		return $result;
	}
	/******************** Get default payment gateway of Specific company *********************/
	public function payment_gateway_details()
	{
		$sql = "SELECT payment_gateway_id as payment_type,paypal_api_username as payment_gateway_username,paypal_api_password as payment_gateway_password,paypal_api_signature as payment_gateway_key,currency_code as gateway_currency_format,payment_method as payment_method FROM ".PAYMENT_GATEWAYS."  WHERE company_id = '0' and default_payment_gateway=1";

		$result =  Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

		return $result;

	}
	
	public function getTaxiSpeed($modelId)
	{
		$sql = "SELECT taxi_speed,taxi_min_speed FROM ".MOTORMODEL." WHERE model_id = :modelId ";
		return Db::query(Database::SELECT, $sql)->parameters(array(':modelId'=>$modelId))->execute()->as_array(); 
	}
	/** Common Function to display datetime format **/
	public function setDateDisplayFormat($dateval)
	{
		//DEFAULT_DATE_TIME_FORMAT
		$timestamp = strtotime($dateval);
		return date('D,dM-Y h:i:s A', $timestamp);
	}
	
	public function get_passenger_name($id)
	{
		$sql = "SELECT name FROM ".PASSENGERS." WHERE id = '$id' ";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();
	}
	
	public function promocode_used_update($promo_code,$passenger_id)
	{
		$promo_query = "SELECT passenger_promoid,promo_used_details FROM ".PASSENGER_PROMO." WHERE promocode = '$promo_code' and FIND_IN_SET('$passenger_id',passenger_id)";
		$promo_fetch = Db::query(Database::SELECT, $promo_query)
					->execute()
					->as_array();
		if(count($promo_fetch) > 0)
		{
			if(isset($promo_fetch[0]['promo_used_details']) && $promo_fetch[0]['promo_used_details'] != "") {
				$passenger_promoid = $promo_fetch[0]['passenger_promoid'];
				$promo_used_details = unserialize($promo_fetch[0]['promo_used_details']);
				if(array_key_exists($passenger_id, $promo_used_details)) {
					$promo_used_details[$passenger_id]++;
				}
				$data = serialize($promo_used_details);
				DB::update(PASSENGER_PROMO)
					->set(array("promo_used_details" => $data))
					->where('passenger_promoid', '=', $passenger_promoid)
					->execute();
			}
		}
	}
	
	/** get location **/
	public function companyLocationDetails($cid)
	{
		if($cid!="0"){ 
			$result = DB::select(COUNTRY.'.country_name',STATE.'.state_name',CITY.'.city_name')->from(PEOPLE)->join(COUNTRY,'LEFT')->on(COUNTRY.'.country_id','=',PEOPLE.'.login_country')->join(STATE,'LEFT')->on(STATE.'.state_id','=',PEOPLE.'.login_state')->join(CITY,'LEFT')->on(CITY.'.city_id','=',PEOPLE.'.login_city')->where(PEOPLE.'.company_id','=',$cid)->where(PEOPLE.'.user_type','=','C')->execute()->as_array();
		}else{
			$result = DB::select('login_country','login_state','login_city')->from(PEOPLE)
			->where('user_type','=','A')
			->execute()
			->as_array();
			$result = DB::select(COUNTRY.'.country_name',STATE.'.state_name',CITY.'.city_name')->from(PEOPLE)->join(COUNTRY,'LEFT')->on(COUNTRY.'.country_id','=',PEOPLE.'.login_country')->join(STATE,'LEFT')->on(STATE.'.state_id','=',PEOPLE.'.login_state')->join(CITY,'LEFT')->on(CITY.'.city_id','=',PEOPLE.'.login_city')->where(PEOPLE.'.user_type','=','A')->execute()->as_array();

		}
			return $result;
	}
	
	/**
	 * Get User mobile number
	 * return mobile number
	 **/
	
	public function getUserDetailByEmail($field = "",$table,$email)
	{
		$result = DB::select($field)->from($table)->limit(1)->where('email','=',$email)->execute()->as_array();
		if(count($result)>0)
		{
			return $result[0][$field];
		}
		else
		{
			return;
		}
	}
	
	/** 
	 * Get Passenger Details
	 * return selected fields
	 **/

	public function getPassengerDetails($passenger_logid)
	{
		$query = "select ".PASSENGERS_LOG.".passengers_log_id,".PASSENGERS_LOG.".current_location,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".pickup_time,".PASSENGERS.".name,".PASSENGERS.".email,".PASSENGERS.".country_code,".PASSENGERS.".phone from ".PASSENGERS_LOG." left join ".PASSENGERS." on ".PASSENGERS.".id = ".PASSENGERS_LOG.".passengers_id where ".PASSENGERS_LOG.".passengers_log_id = '$passenger_logid'";
		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $result;
	}
	
	public function smtp_settings(){
		
		$smtp_result = DB::select('smtp')
						->from(SMTP_SETTINGS)
						->where('id', '=', '1')
						->execute()
						->as_array(); 
		return !empty($smtp_result) ? $smtp_result : array();
	}
	
	public function common_site_info(){
		
		$result = DB::select('app_name','email_id','app_description','notification_settings','meta_keyword','meta_description','site_favicon','pre_authorized_amount','web_google_map_key','web_google_geo_key','google_timezone_api_key','pagination_settings','price_settings','continuous_request_time','facebook_key','facebook_secretkey','site_country','site_state','site_city','admin_commission','tax','site_copyrights','facebook_share','twitter_share','google_share','linkedin_share','sms_enable','driver_tell_to_friend_message','referral_discount','show_map','taxi_charge','fare_calculation_type','driver_referral_setting','driver_referral_amount','referral_settings','default_miles','wallet_amount1','wallet_amount2','wallet_amount3','wallet_amount_range','admin_commision_setting','company_commision_setting','driver_commision_setting','user_time_zone','date_time_format','date_time_format_script','tell_to_friend_message','default_unit','skip_credit_card','cancellation_fare_setting','site_tagline','phone_number','referral_amount','ios_google_map_key','ios_google_geo_key','android_google_key','site_logo','email_site_logo','currency_format','banner_image','banner_content','app_content','app_android_store_link','app_ios_store_link','app_bg_color','about_us_content','about_bg_color','footer_bg_color','contact_us_content','facebook_follow_link','google_follow_link','twitter_follow_link','passenger_app_ios_store_link','passenger_app_android_store_link','mobile_header_logo','flash_screen_logo','expiry_date','passenger_book_notify','driver_trip_amount','driver_minimum_wallet_request','driver_maximum_wallet_request','wallet_request_per_day','admin_secret_key')
		->from(SITEINFO)
		->where('id', '=', '1')->limit(1)->execute()->as_array();
		return !empty($result) ? $result : array();
	}
	
	public function common_findcompanyid($subdomain){
	
		$rs = DB::select('C.cid','C.time_zone','C.user_time_zone','C.date_time_format','C.date_time_format_script')->from(array(COMPANY,'C'))
				->join(array(COMPANYINFO,'CI'))
				->on('C.cid','=','CI.company_cid')
				->where('CI.company_domain','=',$subdomain)
				->limit(1)
				->execute()
				->as_array();
		return !empty($rs) ? $rs : array();
	}
	
	public function common_currency_details($company_id){
	
		$result = DB::select('currency_code','currency_symbol','payment_gateway_id')->from(PAYMENT_GATEWAYS)
							->where('default_payment_gateway', '=', '1')
							->where('company_id', '=', $company_id)
							->limit(1)
							->execute()
							->as_array();		
		return !empty($result) ? $result : array();
	}
	
	public function common_company_details($company_id){
	
		$result = DB::select('company_logo','company_favicon','company_app_name','company_facebook_share','company_twitter_share','company_google_share','company_linkedin_share','customer_app_url','driver_app_url','company_api_key','company_facebook_key','company_facebook_secretkey','company_notification_settings','company_name','header_bgcolor','menu_color','mouseover_color','company_phone_number','company_meta_title','company_meta_keyword','company_meta_description','company_copyrights','default_unit','skip_credit_card','fare_calculation_type','company_app_description','cancellation_fare','name','lastname','email','address','driver_commission','account_balance')->from(COMPANYINFO)
				->join(PEOPLE, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANYINFO.'.company_cid')
				->join(COMPANY, 'LEFT')->on(COMPANY.'.cid', '=', COMPANYINFO.'.company_cid')
				->where(COMPANYINFO.'.company_cid','=',$company_id)
				->where(PEOPLE.'.user_type','=','C')
				->limit(1)
				->execute()
				->as_array();	
		return !empty($result) ? $result : array();
	}
	
	function findcompany_currency($company_cid)
	{
		$currency_arr[] = array('currency_code' =>CURRENCY_FORMAT, 'currency_symbol' =>CURRENCY);
		$rs = DB::select('currency_code','currency_symbol')->from(PAYMENT_GATEWAYS)
			->where('default_payment_gateway', '=', '1')
			->where('company_id', '=',$company_cid)
			->execute()
			->as_array();
		
		if(count($rs)>0)
			return $rs;
		else
			return $currency_arr;
	}
	
	function findcompany_currencyformat($company_cid)
	{
		$rs = DB::select('currency_code','currency_symbol')->from(PAYMENT_GATEWAYS)
		->where('default_payment_gateway', '=', '1')
		->where('company_id', '=',$company_cid)
		->execute()
		->as_array();
		if(count($rs)>0)
			return $rs[0]['currency_code'];
		else
			return CURRENCY_FORMAT;
	}
	
	function getcompanycontent($cid)
	{
			$rs = DB::select('id','company_id','menu_name','page_url')->from(COMPANY_CMS)
				->where('company_id','=',$cid)
				->where('type','=',1)
				->where('status','=',1)
				->order_by('id','ASC')
				->execute()
				->as_array();	
			return $rs;			
	}
	/** Function to send push notification to passenger **/
    public function send_pushnotification($d_device_token="",$device_type="",$pushmessage=null,$android_api="")
    {
	   if($device_type == 1)
	   {
			//---------------------------------- ANDROID ----------------------------------//
			$apiKey = $android_api;
			$registrationIDs = array($d_device_token);
			// Message to be sent
			if(!empty($registrationIDs))
			{
				// Set POST variables
				$url = 'https://android.googleapis.com/gcm/send';
				$pushmessage = json_encode($pushmessage);
				$fields = array('registration_ids' => $registrationIDs,'data' => array( "message" => $pushmessage));
				$headers = array('Authorization: key=' . $apiKey, 'Content-Type: application/json');
				// Open connection
				$ch = curl_init();
				// Set the url, number of POST vars, POST data
				curl_setopt( $ch, CURLOPT_URL, $url );
				curl_setopt( $ch, CURLOPT_POST, true );
				curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
				// Execute post
				$result = curl_exec($ch);
				// Close connection
				curl_close($ch);
				//echo $result;                            
			}
		}
		elseif($device_type == 2)
		{                          
			//---------------------------------- IPHONE ----------------------------------// 
			$deviceToken = trim($d_device_token);                                                                                      
			if(!empty($deviceToken))
			{
				// Put your private key's passphrase here:
				$passphrase = '1234';
				// Put your alert message here:
				//$message = $message = "A new business ".$business_name." is added in Yiper";
				//$message = $deal_id.".".ucfirst($merchant_name)." has a new deal for you. View now...";                                    
				$badge = 0;
				////////////////////////////////////////////////////////////////////////////////
				$root = $_SERVER['DOCUMENT_ROOT'].'/application/classes/controller/driver_deploy.pem' ;
				$ctx = stream_context_create();
				stream_context_set_option($ctx, 'ssl', 'local_cert',$root );
				stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

				// Open a connection to the APNS server
				$fp = stream_socket_client(
					'ssl://gateway.push.apple.com:2195', $err,
					$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
				if (!$fp)
					exit("Failed to connect: $err $errstr" . PHP_EOL);
				//echo 'Connected to APNS' . PHP_EOL; 
				// Create the payload body
				$message = $pushmessage['message'];
				//$message = "Success";
				$body['aps'] = array(
					'alert' => $message,
					'trip_details' => $pushmessage,
					'sound' => 'default'                                       
					);	
				// Encode the payload as JSON
				$payload = json_encode($body);
				// Build the binary notification
				$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
				// Send it to the server
				$result = fwrite($fp, $msg, strlen($msg));				
				fclose($fp);  
			} 
		}		
	}
	public function get_company_home_data($cid){
		$rs = DB::select('banner_image','banner_content','app_content', 'app_android_store_link', 'app_ios_store_link', 'app_bg_color', 'about_us_content', 'about_bg_color', 'footer_bg_color','contact_us_content', 'facebook_follow_link', 'google_follow_link', 'twitter_follow_link')->from(COMPANY)->where('cid','=',$cid)->execute()->as_array();
		return (isset($rs[0]) && count($rs[0]))?$rs[0]:array();
	}
	
	public function set_driver_cash_transfer($data)
	{
		$currentdate = date('Y-m-d H:i:s');
		$result = DB::insert(DRIVER_CASH_TRANSFER, array('receiver_id','receiver_previous_balance','receiver_after_balance','transfer_amount','transfer_type','transfer_date','comments','trip_id'))
						->values(array($data['receiver_id'],$data['receiver_pervious_balance'],$data['receiver_after_balance'],$data['transfer_amount'],$data['transfer_type'],$currentdate,$data['comments'],$data['trip_id']))
						->execute();
						
		return $result;
	}
	
	public function set_passenger_cash_transfer($data)
	{
		$currentdate = date('Y-m-d H:i:s');
		$result = DB::insert(PASSENGER_CASH_TRANSFER, array('receiver_id','receiver_previous_balance','receiver_after_balance','transfer_amount','transfer_type','transfer_date','comments','trip_id'))
						->values(array($data['receiver_id'],$data['receiver_pervious_balance'],$data['receiver_after_balance'],$data['transfer_amount'],$data['transfer_type'],$currentdate,$data['comments'],$data['trip_id']))
						->execute();
						
		return $result;
	}
	
	public function get_people_detail($id)
	{
		$result = DB::select('account_balance')->from(PEOPLE)
			->where('id','=',$id)
			->execute()			
			->current();
		return $result;
	}
}       
?>
