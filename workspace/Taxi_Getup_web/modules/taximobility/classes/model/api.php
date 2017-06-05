	<?php defined('SYSPATH') OR die('No Direct Script Access');

/******************************************

* Contains API model details

* @Package: API

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************/

Class Model_Api extends Model
{
	public function __construct()
	{	
		/*$this->session = Session::instance();	
		$this->name = $this->session->get("name");
		$this->admin_userid = $this->session->get("passenger_id");
		$this->admin_email = $this->session->get("email");
		$this->user_admin_type = $this->session->get("user_type");*/
		$this->currentdate=Commonfunction::getCurrentTimeStamp();
	}
	public function search($search)
	{
		echo "API Model";
	}
	//Get Common config
	public function select_site_settings($company_id)
	{

		//$result=DB::select_array($field)->from($table)->limit(1)->execute()->as_array();
		
		if($company_id != '')	
		{ 
			$query= "SELECT company_app_name as app_name,company_currency_format as currency_format,company_currency as site_currency,company_tagline site_tagline,company_logo as company_logo,company_copyrights as site_copyrights,company_facebook_key as facebook_key,
company_facebook_secretkey as facebook_secretkey,company_facebook_share as facebook_share,company_twitter_share as twitter_share,company_notification_settings as notification_settings FROM ".COMPANYINFO." where company_cid = '$company_id' limit 0,1 ";

		}
		else
		{ 
			$query= "SELECT app_name,currency_format,site_country,site_currency,admin_commission,site_tagline,site_copyrights,facebook_key,
facebook_secretkey,twitter_key,twitter_secretkey,facebook_share,twitter_share,notification_settings,site_logo FROM ".SITEINFO." limit 0,1 ";
		}	
//echo $query;
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();

		if(count($result)>0)
		{
			return $result;//$result[0][$field];
		}
		else
		{
			return;
		}
	}
	//Passenger Login
	public function passenger_login($phone,$pwd,$devicetoken="",$deviceid="",$devicetype="",$company_id="") 
	{
		//$password = Html::chars(md5($pwd));
		$query= "SELECT 
		id,name,email,profile_image,phone,address,referral_code,creditcard_no,creditcard_cvv,CONCAT(REPEAT('X', CHAR_LENGTH(creditcard_cvv))) AS masked_cvv,expdatemonth,expdateyear,fb_user_id,fb_access_token,user_status,login_from FROM ".PASSENGERS." WHERE phone = '$phone' AND password='$pwd'";
		if($company_id == '')
		{
			$query .= " and passenger_cid = 0";
		}				
		else
		{
			$query .= " and passenger_cid ='$company_id'";
		}
		
		//echo $query;//exit;
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		//echo count($result);exit;		
		if(count($result)>0)
		{			
			//echo 'as';exit;
			$creditcard_no = encrypt_decrypt('decrypt',$result[0]['creditcard_no']);
			$masked_card = repeatx($creditcard_no,'X',4); 
			$result['masked_card'] = $masked_card;
			if($devicetoken != "")
			{
				//echo $company_id;
				$update_array = array("device_token" => $devicetoken,"device_id" => $deviceid,"device_type" => $devicetype );
				if($company_id == '')
				{
					$update_device_token_result = DB::update(PASSENGERS)
							->set($update_array)
							->where('phone', '=', $phone)
							->where('passenger_cid', '=', '0')
							->execute();				
				}
				else
				{
					$update_device_token_result = DB::update(PASSENGERS)
							->set($update_array)
							->where('phone', '=', $phone)
							->where('passenger_cid', '=', $company_id)
							->execute();				
				}
			}
			return $result;	
		}
		else
		{
			return array();
		}
		
	}
	// Check Whether Passenger Email is Already Exist or Not
	public function check_email_passengers($email="",$company_id="")
	{
			
		if($company_id !='')
		{
			$sql = "SELECT email FROM ".PASSENGERS." WHERE email='$email' and passenger_cid='$company_id'";   
		}
		else
		{
			$sql = "SELECT email FROM ".PASSENGERS." WHERE email='$email' and passenger_cid='0'";   
		}	

		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}	

	// Check Whether Passenger phone is Already Exist or Not
	public function check_phone_passengers($phone="",$company_id="")
	{
		if($company_id != '')
		{
			$sql = "SELECT phone FROM ".PASSENGERS." WHERE phone='$phone' and passenger_cid='$company_id'";   
		}
		else
		{
			$sql = "SELECT phone FROM ".PASSENGERS." WHERE phone='$phone' and passenger_cid='0'";   
		}
		//echo $sql;
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}

	// Check Whether Passenger phone is Already Exist or Not
	public function check_phone_bypassengers($phone="",$email='',$company_id='')
	{
		
		if($company_id != '')
		{
			$sql = "SELECT phone FROM ".PASSENGERS." WHERE ( phone='$phone' ) and passenger_cid = '$company_id'";   
		}
		else
		{
			$sql = "SELECT phone FROM ".PASSENGERS." WHERE ( phone='$phone' ) and passenger_cid = '0'";  
		}

//echo $sql;
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}

	// Check Whether People phone is Already Exist or Not
	// Check with all placed before going to edit this function
	public function check_phone_people($phone="",$user_type="",$company_id)
	{
		if($company_id != '')	
		{
			$sql = "SELECT phone FROM ".PEOPLE." WHERE phone='$phone' and user_type='$user_type' and company_id='$company_id'";   
		}
		else
		{
			$sql = "SELECT phone FROM ".PEOPLE." WHERE phone='$phone' and user_type='$user_type'";   
		}
		

		//$sql = "SELECT phone FROM ".PEOPLE." WHERE phone='$phone' and user_type='$user_type'";   

		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}

	/**User Signup**/
	public function signup($val,$password='',$random_key="",$devicetoken="",$deviceid="",$devicetype="",$company_id="") 
	{

		if($company_id != '')
		{
			$cid = $company_id;
		}
		else
		{
			$cid = 0;
		}	

		$username = Html::chars($val['name']);
		//$activation_key = Commonfunction::admin_random_user_password_generator();


		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
		}	
		else
		{
			$model_base_query = "select time_zone from  company where cid='$company_id' "; 
			$model_fetch = Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array();			

			if($model_fetch[0]['time_zone'] != '')
			{
				$current_time = convert_timezone('now',$model_fetch[0]['time_zone']);
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

		
		/*$fieldname_array = array('name','email','password','org_password','phone','address','creditcard_no','creditcard_cvv','expdatemonth','expdateyear','activation_key','activation_status','user_status','created_date','passenger_cid');
		$values_array = array($val['name'],$val['email'],md5($val['password1']),$val['password1'],$val['mobileno'],$val['address'],$val['creditcard_no'],$val['creditcard_cvv'],$val['expdatemonth'],$val['expdateyear'],$activation_key,'1','A',$current_time,$cid);
		$result = DB::insert(PASSENGERS, $fieldname_array)
					->values($values_array)
					->execute(); */
					
		$name = $val['name'];
		$email = $val['email'];
		$mdpassword = md5($password);
		$phone = $val['mobileno'];
		$address = $val['address'];
		$creditcard_no =  encrypt_decrypt('encrypt',$val['creditcard_no']);
		$creditcard_cvv = $val['creditcard_cvv'];
		$expdatemonth = $val['expdatemonth'];
		$expdateyear = $val['expdateyear'];		
		
		
		$query = "insert into ".PASSENGERS."(name,email,password,org_password,phone,address,creditcard_no,creditcard_cvv,expdatemonth,expdateyear,activation_key,activation_status,user_status,created_date,passenger_cid)values('".$name."','".$email."','".$mdpassword."','".$password."','".$phone."','".$address."','".$creditcard_no."','".$creditcard_cvv."','".$expdatemonth."','".$expdateyear."','".$random_key."','1','A','".$current_time."','".$cid."')";
		
		$result = Db::query(Database::INSERT, $query)
		->execute();
					
		if($result){										
				$email = DB::select()->from(PASSENGERS)
					->where('email', '=', $val['email'])
					->execute()
					->as_array(); 		         
		if($devicetoken != "")
		{
			$update_array = array("device_token" => $devicetoken,"device_id" => $deviceid,"device_type" => $devicetype );
			
			/*if($company_id != '')
			{
				$update_device_token_result = DB::update(PASSENGERS)
						->set($update_array)
						->where('email', '=', $val['email'])
						->where('passenger_cid', '=', '0')
						->execute();				
			}
			else
			{
				$update_device_token_result = DB::update(PASSENGERS)
						->set($update_array)
						->where('email', '=', $val['email'])
						->where('passenger_cid', '=', $company_id)
						->execute();				
			}*/

				$update_device_token_result = DB::update(PASSENGERS)
						->set($update_array)
						->where('email', '=', $val['email'])
						->execute();				

		}
		return 1;
			
		}else{
				return 0;
		}
	}
   /** REGISTER FACEBOOK USERS **/
   /** REGISTER FACEBOOK USERS **/
    public function register_facebook_user($accessToken = "",$uid="",$otp="",$referral_code="",$fname="",$lname="",$email="",$image_name = "",$devicetoken="",$device_id="",$devicetype="",$company_id = "")
    {


				/** Referrral key generator **/
				$referralcode_query = "select concat(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1)) as referral_code from passengers Having NOT EXISTS (select referral_code from passengers having referral_code=referral_code) limit 1";

			 	$referralcode_result = Db::query(Database::SELECT, $referralcode_query)
			 	->execute()			
				->as_array();

				if(count($referralcode_result) > 0)
				{
					$referral_code = $referralcode_result[0]['referral_code'];
				}
				else
				{
					$referralcode_query = "select concat(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1)) as referral_code";

				 	$referralcode_result = Db::query(Database::SELECT, $referralcode_query)
				 	->execute()			
					->as_array();

					$referral_code = $referralcode_result[0]['referral_code'];
				}

				/** Referral key generator **/		   




        if($company_id !='')
		{	
			$res = DB::select()->from(PASSENGERS)->where('email', '=', $email)->where('passenger_cid', '=', $company_id)->execute()->as_array(); 
		}
		else
		{
			$res = DB::select()->from(PASSENGERS)->where('email', '=', $email)->where('passenger_cid', '=', 0)->execute()->as_array();
		}
		 //echo count($res);exit;
		 //print_r($res);exit;
         if(count($res) == 0)
         {                                      					
			$password = text::random($type = 'alnum', $length = 6);		
			$activation_key = Commonfunction::admin_random_user_password_generator(); //
			$common_model = Model::factory('commonmodel');
			if($company_id !='')
			{
				$current_time = $common_model->getcompany_all_currenttimestamp($company_id);
			}
			else
			{
				$current_time =	date('Y-m-d H:i:s');
			}
				$current_time =	date('Y-m-d H:i:s');			
			$fieldname_array = array('name','lastname','email','profile_image','password','org_password','otp','phone','address','referral_code','fb_user_id','fb_access_token','activation_key','activation_status','login_from','user_status','created_date','updated_date','passenger_cid');
			$values_array = array($fname,$lname,$email,$image_name,md5($password),$password,$otp,$uid,'',$referral_code,$uid,$accessToken,$activation_key,'1','3','A',$current_time,$current_time,$company_id);
			$result = DB::insert(PASSENGERS, $fieldname_array)
						->values($values_array)
						->execute();
			if($devicetoken != "")
			{
				$update_array = array("device_token" => $devicetoken,"device_id" => $device_id,"device_type" => $devicetype );
				$update_device_token_result = DB::update(PASSENGERS)
						->set($update_array)
						->where('email', '=', $email)
						->execute();				
			}						
			return 2;
		}
		else if(count($res) == 1)
		{

			if($company_id != '')
			{
				$ress = DB::select(PASSENGERS.'.id')->from(PASSENGERS)->where('email', '=', $email)->where('fb_user_id', '!=', '')->where('fb_access_token', '!=', '')->where('passenger_cid','=',$company_id)->execute()->as_array();
			}
			else
			{
				$ress = DB::select(PASSENGERS.'.id')->from(PASSENGERS)->where('email', '=', $email)->where('fb_user_id', '!=', '')->where('fb_access_token', '!=', '')->where('passenger_cid','=',0)->execute()->as_array();
			}

			$counts = count($ress);
			//echo '<br>';	
			if($counts != 0)
			{
				$passenger_id = $ress[0]['id'];
				/************* Check whether Mobile Details Filled ******************/
				if($company_id !='')
				{
					$mobile_datasql = "SELECT id FROM ".PASSENGERS." WHERE email='$email' and phone = '$uid' and passenger_cid='$company_id'";   	
				}
				else
				{
					$mobile_datasql = "SELECT id FROM ".PASSENGERS." WHERE email='$email' and phone = '$uid'";   	
				}
	
				$mobileresult=Db::query(Database::SELECT, $mobile_datasql)
					->execute()
					->as_array(); 
				$mobile_count = count($mobileresult);	
				/************* Check whether Personal Details Filled ******************/	
				if($company_id !='')
				{												
					$personal_datasql = "SELECT id FROM ".PASSENGERS." WHERE email='$email' and (salutation = '' or name = '' or lastname = '') and passenger_cid='$company_id'";   	
				}
				else
				{
					$personal_datasql = "SELECT id FROM ".PASSENGERS." WHERE email='$email' and (salutation = '' or name = '' or lastname = '')";   	
				}

				$result=Db::query(Database::SELECT, $personal_datasql)
					->execute()
					->as_array(); 
				//echo '<br>';
				$personal_count = count($result);	
				
				$cardsql = "SELECT passenger_cardid FROM ".PASSENGERS_CARD_DETAILS." WHERE passenger_id = '$passenger_id'";   	

				$cardresult=Db::query(Database::SELECT, $cardsql)
					->execute()
					->as_array(); 
				//echo '<br>';
				if(count($cardresult) >0)
				{					
					$card_count = 1;
				}
				else
				{
					$card_count = 0;
				}	
				//echo $card_count;			
				if($devicetoken != "")
				{
					$update_array = array("device_token" => $devicetoken,"device_id" => $device_id,"device_type" => $devicetype,"login_from" => 3 );
					if($company_id !='')
					{
						$update_device_token_result = DB::update(PASSENGERS)->set($update_array)->where('email', '=', $email)->where('passenger_cid', '=', $company_id)->execute();				
					}
					else
					{
						$update_device_token_result = DB::update(PASSENGERS)->set($update_array)->where('email', '=', $email)->where('passenger_cid', '=', 0)->execute();				
					}				
				}	
											
					if(($counts==1) &&($personal_count == 0) &&($mobile_count == 0) && ($card_count == 1))
					{
						return 1;        
					}
					else if(($counts==1) &&($mobile_count == 1) && ($personal_count == 1) && ($card_count == 0))
					{
						return 2;										
					} 
					else if(($counts==1) &&($mobile_count == 0) &&($personal_count == 1) && ($card_count == 0))
					{
						return 3;										
					} 
					else if(($counts==1) &&($mobile_count == 0) &&($personal_count == 0) && ($card_count == 0))
					{
						return 4;										
					}   
					else if(($counts==0) &&($personal_count == 0) &&($mobile_count == 0))
					{
						return -2;
					}   
			}
			else
			{
				return -2;
			}     
		}
		else
		{
			  return -2;
		}
    }
    // Passenger Mobile Number 
    public function update_passenger_mobile($email="",$mobile="",$creditcard_no="",$creditcard_cvv="",$expdatemonth="",$expdateyear="",$company_id="")
    {

       try {

		$update_array = array("phone" => $mobile);

		if($company_id != '')
		{

			$result = DB::update(PASSENGERS)
					->set($update_array)
					->where('email', '=', $email)
					->where('passenger_cid', '=', $company_id)
					->execute();

		}
		else
		{
			$result = DB::update(PASSENGERS)
					->set($update_array)
					->where('email', '=', $email)
					->where('passenger_cid', '=', '0')
					->execute();


		}

		return 1;

		} 
		catch (Kohana_Exception $e) {
			return -1;			
		}
	}
	//Passenger Profile
	public function passenger_profile($userid) 
	{		

			$query= "SELECT ".PASSENGERS.".name,".PASSENGERS.".lastname,".PASSENGERS.".salutation,".PASSENGERS.".email,".PASSENGERS.".password,".PASSENGERS.".org_password,".PASSENGERS.".referral_code,".PASSENGERS.".profile_image,".PASSENGERS.".phone,".PASSENGERS.".address,".PASSENGERS.".discount,
".PASSENGERS.".creditcard_no,".PASSENGERS.".creditcard_cvv,".PASSENGERS.".expdatemonth,".PASSENGERS.".expdateyear,".PASSENGERS.".device_token,
".PASSENGERS.".device_type,".PASSENGERS.".user_status FROM ".PASSENGERS." WHERE id = '$userid'";

		//echo $query;
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $result;	
	}

	/** Save Customer Booking **/
	public function savebooking($val,$company_id)
	{
		$pickup_time = mysql_real_escape_string($val['pickup_time']);
		$roundtrip = mysql_real_escape_string($val['roundtrip']);
		$cityname = mysql_real_escape_string($val['cityname']);
		
		$approx_distance = "";//mysql_real_escape_string($val['approx_distance']);
		$approx_fare = "";//mysql_real_escape_string($val['approx_fare']);		
		$pickup_latitude = mysql_real_escape_string($val['pickup_latitude']);
		$pickup_longitude = mysql_real_escape_string($val['pickup_longitude']);
		$drop_latitude = mysql_real_escape_string($val['drop_latitude']);
		$drop_longitude = mysql_real_escape_string($val['drop_longitude']);
		$notes_driver = "";//mysql_real_escape_string($val['notes']);
		
		$sub_logid = $this->get_sublogid($val['sub_logid']);

		// Get Pickup & Drop location Lat & Long using Google API 
		// Which is used when we calculating approximat fare and distance from api side
		/*$pickup = $this->getLatLongwithcity(mysql_real_escape_string($val['pickup']));
		$pickup_latitude = $pickup[0];
		$pickup_longitude = $pickup[1];
		$cityname = $pickup[2];*/
		$model_query = "select city_id from ".CITY." where city_name like '%".$cityname."%' limit 0,1";	
		$model_fetch = Db::query(Database::SELECT, $model_query)
				->execute()
				->as_array();
		
		if(count($model_fetch) > 0)
		{
			$city_id = $model_fetch[0]['city_id'];
		}
		else
		{
			$model_query = "select city_id from ".CITY." where city.default='1' limit 0,1";	

			$model_fetch = Db::query(Database::SELECT, $model_query)
					->execute()
					->as_array();
			$city_id = $model_fetch[0]['city_id'];
		}

			$get_taxi_fare_based_model = $this->get_current_taxi_details($val["driver_id"],$val["taxi_id"],$city_id);
			$base_fare = $get_taxi_fare_based_model[0]['base_fare'];
			$min_fare = $get_taxi_fare_based_model[0]['min_fare'];
			$cancellation_fare = $get_taxi_fare_based_model[0]['cancellation_fare'];
			$below_km = $get_taxi_fare_based_model[0]['below_km'];
			$above_km = $get_taxi_fare_based_model[0]['above_km'];
			$night_charge = $get_taxi_fare_based_model[0]['night_charge'];
			$night_timing_to = $get_taxi_fare_based_model[0]['night_timing_to'];
			$night_fare = $get_taxi_fare_based_model[0]['night_fare'];	
			$companyid = $get_taxi_fare_based_model[0]['cid'];	
			$company_tax = $get_taxi_fare_based_model[0]['company_tax'];			
			


		if($roundtrip == 'true'){ $pickupdrop = 1;}else{$pickupdrop = 0;}
		$waitingtime = '';
		//$company_id = $this->get_company_id(mysql_real_escape_string($val['driver_id']));
		//if(count($company_id)>0){ $d_company_id = $company_id[0]['company_id'];}else{$d_company_id=0;}

		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
					$time = date ('H:i:s',strtotime($pickup_time));					
					$update_time = $current_date[0].' '.$time;					
				}
				else
				{
					$time = date ('H:i:s',strtotime($pickup_time));
					$update_time = date('Y-m-d').' '.$time;
				}
		}	
		else
		{
			$model_base_query = "select time_zone from  company where cid='$company_id' "; 
			$model_fetch = Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array();			

			if($model_fetch[0]['time_zone'] != '')
			{
				$time = date ('H:i:s',strtotime($pickup_time));
				$current_datetime = convert_timezone('now',$model_fetch[0]['time_zone']); 
				$curretnt_datetime_split = explode(' ',$current_datetime);
				$update_time = $curretnt_datetime_split[0].' '.$time;
			}
			else
			{
				$time = date ('H:i:s',strtotime($pickup_time));
				$update_time = date('Y-m-d').' '.$time;
			}
		}



//'pickup_latitude','pickup_longitude','drop_latitude','drop_longitude'
		
		$fieldname_array = array('passengers_id','driver_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude','no_passengers','approx_distance','approx_fare','pickup_time','pickupdrop','waitingtime','createdate','taxi_id','booking_from','search_city','sub_logid','notes_driver','booking_from_cid','company_tax','bookingtype','bookby');
		//'pickup','drop','no_of_passengers','pickup_time','driver_id','roundtrip','taxi_id','passenger_id'
		$values_array = array(mysql_real_escape_string($val['passenger_id']),mysql_real_escape_string($val['driver_id']),$companyid,mysql_real_escape_string($val['pickupplace']),$pickup_latitude,$pickup_longitude,mysql_real_escape_string($val['dropplace']),$drop_latitude,$drop_longitude,'',$approx_distance,$approx_fare,$update_time,$pickupdrop,$waitingtime,$this->currentdate,mysql_real_escape_string($val['taxi_id']),'1',$city_id,$sub_logid,$notes_driver,$company_id,$company_tax,'1','1');
		$driver_availability_result = $this->get_driver_availability(mysql_real_escape_string($val['driver_id']),$update_time);
		
		
		if(count($driver_availability_result) == 0)
		{
			$result = DB::insert(PASSENGERS_LOG, $fieldname_array)
						->values($values_array)
						->execute();

			if($sub_logid =='' || $sub_logid =='0' )
			{
				$update_pass_logid= DB::update(PASSENGERS_LOG)->set(array('sub_logid' =>$result[0] ))
				->where('passengers_log_id','=',$result[0])
				->execute();	
			}

			if($result)
			{
				return $result[0];
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return 'F'; //Driver already booed for this time
		}
			
	}

	/** Get Company id for the Driver **/
	public function get_company_id($driver_id)
	{
		$sql = "SELECT * FROM ".PEOPLE." WHERE `id` = '".$driver_id."'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	
	/** Mark as favourite Trip**/
	public function set_markfav_tripdetails($pass_log_id,$mark_status)
	{
		 $update_result =  DB::update(PASSENGERS_LOG)->set(array('favourite_trip'=>$mark_status))					->where('passengers_log_id', '=' ,$pass_log_id)->execute();
		return $update_result;
	}
	/** Driver availability **/
	public function get_driver_availability($driver_id,$pickup_time)
	{
		$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `pickup_time` = '".$pickup_time."' and `driver_id` = '".$driver_id."' and `driver_reply` = 'A' and 'travel_status' = 9";
		//echo $sql;exit;
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}
				
	//Passenger Profile Edit
	public function edit_passenger_profile($array,$company_id)
	{

		try {
			if($company_id != '')
			{		
				/*$result = DB::update(PASSENGERS)
				->set($array)
				->where('id', '=', $array['id'])
				->where('passenger_cid', '=', $company_id)
				->execute();*/
				$name = $array['name'];
				/*$profile_picture = $array['profile_picture'];*/
				$address = $array['address'];
				$creditcard_no = encrypt_decrypt('encrypt',$array['creditcard_no']);
				$creditcard_cvv = $array['creditcard_cvv'];
				$expdatemonth = $array['expdatemonth'];
				$expdateyear = $array['expdateyear'];
				$id = $array['id'];
				
				//$sql = "UPDATE `passengers` SET `name` = '$name', `address` = '$address', `creditcard_no` = '$creditcard_no', `creditcard_cvv` = '$creditcard_cvv', `expdatemonth` = '$expdatemonth', `expdateyear` = '$expdateyear' WHERE `id` = '$id' AND `passenger_cid` = '$company_id'";
				
				$sql = "UPDATE `passengers` SET `name` = '$name', `address` = '$address', `creditcard_no` = '$creditcard_no', `creditcard_cvv` = '$creditcard_cvv', `expdatemonth` = '$expdatemonth', `expdateyear` = '$expdateyear' WHERE `id` = '$id' ";
				$result =  Db::query(Database::UPDATE, $sql)
				->execute();
			}
			else
			{
				/*$result = DB::update(PASSENGERS)
						->set($array)
						->where('id', '=', $array['id'])
						->where('passenger_cid', '=', '0')
						->execute();*/
				$name = $array['name'];
				/*$profile_picture = $array['profile_picture'];*/
				$address = $array['address'];
				$creditcard_no = encrypt_decrypt('encrypt',$array['creditcard_no']);
				$creditcard_cvv = $array['creditcard_cvv'];
				$expdatemonth = $array['expdatemonth'];
				$expdateyear = $array['expdateyear'];
				$id = $array['id'];
				
				$sql = "UPDATE `passengers` SET `name` = '$name', `address` = '$address', `creditcard_no` = '$creditcard_no', `creditcard_cvv` = '$creditcard_cvv', `expdatemonth` = '$expdatemonth', `expdateyear` = '$expdateyear' WHERE `id` = '$id'";
				
				$result =  Db::query(Database::UPDATE, $sql)
				->execute();

			}		
			
			return 0;
		} 
		catch (Kohana_Exception $e) {
			return -1;			
		}
		
	}
	
	//Change Password for Both Driver and Passenger
	public function chg_password_passenger($array,$table,$company_id='',$type='')
	{
		
		//Checking the Confirmation of Password
		if($array['new_password'] == $array['confirm_password'])
		{
			//echo 'as'.$type;
			if($type == 'D')
				$profile = $this->driver_profile($array['id']);		
			else
				$profile = $this->passenger_profile($array['id']);
		//print_r($profile);
			if(count($profile) > 0)
			{
				//Checking the Old Password
				if($array['old_password'] == $array['new_password'])
				{
					return -4;
				}
				else if($profile[0]['password'] == $array['old_password'])
				{
				   if($type == 'D')	
				   {
					   /*if($company_id != '')	
					   {
					   $result = DB::update($table)
									->set(array('password' => $array['new_password'],'org_password'=>$array['org_new_password']))
									->where('id', '=', $array['id'])
									->where('company_id', '=', $company_id)
									->execute();
					  }
					  else
					  {
						$result = DB::update($table)
						->set(array('password' => $array['new_password'],'org_password'=>$array['org_new_password']))
						->where('id', '=', $array['id'])
						->execute();
					 }	
					 */

						$result = DB::update($table)
						->set(array('password' => $array['new_password'],'org_password'=>$array['org_new_password']))
						->where('id', '=', $array['id'])
						->execute();

	
				   }	
				   else
				   {	
						if($company_id != '')
						{	
							$result = DB::update($table)
							->set(array('password' => $array['new_password'],'org_password'=>$array['org_new_password']))
							->where('id', '=', $array['id'])
							->where('passenger_cid', '=', $company_id)
							->execute();
						}
						else
						{
							$result = DB::update($table)
							->set(array('password' => $array['new_password'],'org_password'=>$array['org_new_password']))
							->where('id', '=', $array['id'])
							->where('passenger_cid', '=', '0')
							->execute();
						}

						//$result = DB::update($table)->set(array('password' => $array['new_password'],'org_password'=>$array['org_new_password']))->where('id', '=', $array['id'])->execute();


				   }	
					return 1;		
				}
				else
					return -2;
			}
			else
				return -3;
		}
		else
		{
			return -1;
		}
		
	}
	
	//Save Comments and Ratings from Passenger
	public function savecomments($log_id="",$ratings="",$comments="")
	{
		 $update_result =  DB::update(PASSENGERS_LOG)->set(array('comments'=>$comments,'rating'=>$ratings))					->where('passengers_log_id', '=' ,$log_id)
							 ->execute();
		return $update_result;
	}
	
	//Common Function for updation
	public function update_table($table,$arr,$cond1,$cond2)
	{
			$result=DB::update($table)->set($arr)->where($cond1,"=",$cond2)->execute();
			return $result;
	}
	
	//Common Function for Select
	public function select_table($table,$cond1,$cond2)
	{
		$result=DB::select('*')->from($table)->where($cond1,"=",$cond2)->execute()->as_array();
		return $result;
	}
	
	public function select_driverloc($driverid,$company_id)
	{
		$result=DB::select('*')->from(DRIVER)->where('driver_id',"=",$driverid)->execute()->as_array();		
		return $result;
	}
	
	//Driver Profile
	public function driver_profile($userid) 
	{		
		$query= "SELECT salutation,name,".PEOPLE.".id as userid,lastname,email,phone,address,password,org_password,otp,photo,device_type,device_token,login_status,user_type,driver_referral_code,notification_setting,company_id,driver_license_id,profile_picture,".COMPANY.".bankname,".COMPANY.".bankaccount_no,".COMPANY.".userid as company_ownerid FROM ".PEOPLE." JOIN  ".COMPANY." ON (  ".PEOPLE.".`company_id` =  ".COMPANY.".`cid` )  WHERE id = '$userid' AND user_type = 'D' ";		
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $result;	
	}


		
	//Driver Login
	public function driver_login($phone,$pwd,$company_id) 
	{
		//$password = Html::chars(md5($pwd));
		if($company_id != '')
		{	
			$query= "SELECT * FROM ".PEOPLE." WHERE phone = '$phone' AND password='$pwd' AND user_type = 'D' and company_id='$company_id'";
		}	
		else
		{
			$query= "SELECT * FROM ".PEOPLE." WHERE phone = '$phone' AND password='$pwd' AND user_type = 'D' ";
		}	
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $result;	
	}

	public function check_driver_companydetails($driver_id,$company_id) 
	{
		//$password = Html::chars(md5($pwd));
		if($company_id != '')
		{	
			$query= "SELECT * FROM ".PEOPLE." WHERE id = '$driver_id' AND user_type = 'D' and company_id='$company_id'";
		}	
		else
		{
			$query= "SELECT * FROM ".PEOPLE." WHERE id = '$driver_id' AND user_type = 'D'";
		}	
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return count($result);	
	}


	public function check_passenger_companydetails($id,$company_id) 
	{
		//$password = Html::chars(md5($pwd));
		if($company_id != '')
		{	
			$query= "SELECT * FROM ".PASSENGERS." WHERE id = '$id' and passenger_cid='$company_id'";
		}	
		else
		{
			$query= "SELECT * FROM ".PASSENGERS." WHERE id = '$id'";
		}
		
		//echo $query;
		//$query= "SELECT * FROM ".PASSENGERS." WHERE id = '$id'";

		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return count($result);	
	}


	// Driver Login Status
	public function logged_user_status($driver_id,$company_id)
	{
		//echo 'as'.$company_id;
		if($company_id !='')
		{
			 $query="SELECT login_status FROM ".PEOPLE." where id = '".$driver_id."' and company_id='$company_id'";
		}
		else
		{
			$query="SELECT login_status FROM ".PEOPLE." where id = '".$driver_id."'";
			
		}
		//echo $query;exit;
		$result =  Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
			
		//print_r($query);exit;
			if(count($result) == 1 && $result[0]['login_status'] == 'S')
			{		
			   return 1;
				
			}
			else
			{
				return 0;
			}
	}

	//Update Driver Status
	public function update_driverreply_status($id,$driver_id,$taxi_id,$company_id,$status,$travel_status,$field,$flag)
	{

		$driver_reply = '';

		if($company_id != '')
		{
			$data = DB::select('*')->from(PASSENGERS_LOG)
						->where(PASSENGERS_LOG.'.passengers_log_id','=',$id)
						->where(PASSENGERS_LOG.'.company_id','=',$company_id)
						->as_object()		
						->execute();		
		}
		else
		{
			$data = DB::select('*')->from(PASSENGERS_LOG)
						->where(PASSENGERS_LOG.'.passengers_log_id','=',$id)
						->as_object()		
						->execute();		
		}	

		foreach($data as $values)
		{
			$driver_reply = $values->driver_reply;
		}
		//Acceptred Status
		if($status == 'A')
		{						
			$sql_query = array(
					'travel_status' => $travel_status,
					'driver_reply' => $status,
					'driver_id'=> $driver_id,
					'taxi_id'=> $taxi_id,
					'company_id'=> $company_id,					
					'time_to_reach_passen' => $field,
					'msg_status' =>'R'
				);					
		}
		//Rejected Status and Adding the Driver Comments 
		else
		{
			$sql_query = array(
					'travel_status' => $travel_status,
					'driver_reply' => $status,
					'driver_id'=> $driver_id,
					'taxi_id'=> $taxi_id,
					'company_id'=> $company_id,						
					'driver_comments' => $field,
					'msg_status' =>'R'
				);			
		}
			
		if($driver_reply == '')
		{
			if($company_id != '')
			{
				$update_result = DB::update(PASSENGERS_LOG)->set($sql_query)
					->where('passengers_log_id', '=' ,$id)
					->where('company_id','=',$company_id)
					->execute();
			}
			else
			{
				$update_result = DB::update(PASSENGERS_LOG)->set($sql_query)
					->where('passengers_log_id', '=' ,$id)
					->execute();
			}

			
			if($update_result > 0 )	
			{
				if($status == 'A')
					return 1;
				else if($status == 'R')
					return 2;
				else if($status == 'C')		
					return 3;
			}
			else
			{
				return 4;	
			}
		}
		else
		{	
			// Driver cancel the drip when pick up
			if($flag == 1)
			{
					$sql_query = array(
					'travel_status' => '0',
					'driver_reply' => $status,
					'driver_comments' => $field,
					'msg_status' =>'R'
				);	
			
			if($company_id != '')
			{
				$update_result = DB::update(PASSENGERS_LOG)->set($sql_query)
				->where('passengers_log_id', '=' ,$id)
				->where('company_id','=',$company_id)
				->execute();		

			}
			else
			{
				$update_result = DB::update(PASSENGERS_LOG)->set($sql_query)
				->where('passengers_log_id', '=' ,$id)
				->execute();		
			}
			
				if($update_result > 0)		
				{
					if($status == 'R')
					return 2;
					else if($status == 'C')		
					return 3;
				}
				else
				{
					return 4; //
				}	
			}
			else
			{
				if($driver_reply == 'A')
				{
					return 5; // driver already confirmed
				}
				else if($driver_reply == 'R')
				{
					return 6; // driver already rejected
				}
				else if($driver_reply == 'C')
				{
					return 7; // driver already cancelled
				}
				else
				{
					return 0; // Time out or some technical issues
				}
			}
		}
			
	}
	
	//Driver Profile Edit
	public function edit_driver_profile($array,$default_companyid)
	{				
		//print_r($array);
		//exit;
		try {
			$chk_driver = $this->driver_profile($array['id']);	
			//$chk_phone = $this->check_driver_phone_update($array['phone'],$array['id']);		

				if(count($chk_driver) > 0)
				{					
						//if($chk_phone > 0)			
						//{	
						//	return 3;
						//}
						//else
						//{							
							$result = DB::update(PEOPLE)
							->set($array)
							->where('id', '=', $array['id'])
							->where('user_type', '=', 'D')
							->execute();					
							return 0;
						//}
				}
				else
				{
					return -2;
				}				
			
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 1;			
		}
	}
	
	//Company Profile Edit
	public function edit_company_profile($array)
	{				
		//print_r($array);
		//exit;
		try {
			$chk_driver = $this->driver_profile($array['id']);	
			//print_r($chk_driver);
			
			//$chk_phone = $this->check_driver_phone_update($array['phone'],$array['id']);		

				if(count($chk_driver) > 0)
				{					
						//if($chk_phone > 0)			
						//{	
						//	return 3;
						//}
						//else
						//{		
							$company_id = $chk_driver[0]['company_id'];					
							array_shift($array);
							$result = DB::update(COMPANY)
							->set($array)
							->where('cid', '=', $company_id)						
							->execute();					
							//print_r($result);
							return 0;
						//}
				}
				else
				{
					return -2;
				}				
			
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 1;			
		}
	}
	
	//Function used to check Phone number exist or not
	public function check_driver_phone_update($email="",$id="")
	{
		$sql = "SELECT phone FROM ".PEOPLE." WHERE phone='$email' AND id !='$id' ";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();
			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;	
			}
	}	
	// Function used to get Drivers comments Today and Totally
	public function get_driver_comments($id,$today = null,$company_id='')
	{
		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
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
				$date = $current_date[0].' %';
			}
			else
			{
				$current_time =	date('Y-m-d H:i:s');
				$start_time = date('Y-m-d').' 00:00:01';
				$end_time = date('Y-m-d').' 23:59:59';
				$date = date('Y-m-d %');
			}
		}


		if($today == 1)	
			// It will take the today transaction too
			$result = DB::select('rating','fare')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->join(TRANS)->on(PASSENGERS_LOG.'.passengers_log_id','=',TRANS.'.passengers_log_id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					//->where(PASSENGERS_LOG.'.comments','!=','')
					->where(PASSENGERS_LOG.'.travel_status','=',1)
					->where(PASSENGERS_LOG.'.pickup_time','LIKE',$date)
					->order_by(PASSENGERS.'.id', 'desc')					
					->execute()
					->as_array();
			
		else	
			$result = DB::select('name','rating','comments')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					//->where(PASSENGERS_LOG.'.comments','!=','')
					->where(PASSENGERS_LOG.'.travel_status','=',1)
					->order_by('id', 'desc')					
					->execute()
					->as_array();								
		      // print_r($result);        
		return $result;	
	}
	
	
	//Function used to get the get_driver_logs
	public function get_driver_logs($id,$msg_status,$driver_reply=null,$travel_status=null,$company_id='',$start=null,$limit=null) //
	{


		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
		}	
		else
		{
			$timezone_base_query = "select time_zone from  company where cid='$company_id'"; 
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


		if($company_id	!= '')
		{
			$result = DB::select(PASSENGERS.'.name',PASSENGERS.'.phone',PASSENGERS_LOG.'.passengers_log_id',PASSENGERS_LOG.'.pickup_time',array(PASSENGERS_LOG.'.current_location','pickup_location'),PASSENGERS_LOG.'.drop_location')->from(PASSENGERS_LOG)
			->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
			->where(PASSENGERS_LOG.'.driver_id','=',$id)
			->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
			->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
			->where(PASSENGERS_LOG.'.company_id','=',$company_id)
			->limit($start)->offset($limit)
			->order_by(PASSENGERS.'.id', 'ASC')					
			->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
			->where(PASSENGERS_LOG.'.pickup_time','>=',$start_time)
			->where(PASSENGERS_LOG.'.company_id','=',$company_id)
			->as_object()		
			->execute();									
		}
		else
		{
		$result = DB::select(PASSENGERS.'.name',PASSENGERS.'.phone',PASSENGERS_LOG.'.passengers_log_id',PASSENGERS_LOG.'.pickup_time',array(PASSENGERS_LOG.'.current_location','pickup_location'),PASSENGERS_LOG.'.drop_location')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
					->limit($start)->offset($limit)
					->order_by(PASSENGERS.'.id', 'ASC')					
					->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
					->where(PASSENGERS_LOG.'.pickup_time','>=',$start_time)
					->as_object()		
					->execute();									
		}
		    //print_r($result);           exit;
		return $result;				
	}

	//Function used to get the get_driver_logs
	public function get_driver_current_trip($id,$msg_status,$driver_reply=null,$travel_status=null,$company_id,$start=null,$limit=null) //
	{

		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
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
			$result = DB::select(PASSENGERS.'.name',PASSENGERS_LOG.'.passengers_log_id',array(PASSENGERS_LOG.'.current_location','pickup_location'),PASSENGERS_LOG.'.drop_location',PASSENGERS_LOG.'.pickup_longitude',
	PASSENGERS_LOG.'.pickup_latitude',PASSENGERS_LOG.'.drop_latitude',PASSENGERS_LOG.'.drop_longitude',PASSENGERS_LOG.'.travel_status')->from(PASSENGERS_LOG)
						->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
						->where(PASSENGERS_LOG.'.driver_id','=',$id)
						->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
						->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
						->where(PASSENGERS_LOG.'.company_id','=',$company_id) ///$this->currentdate
						->limit(1)->offset(0)
						->order_by(PASSENGERS_LOG.'.pickup_time', 'ASC')					
						//->where(PASSENGERS_LOG.'.pickup_time','=',$this->currentdate)
						->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
						->where(PASSENGERS_LOG.'.pickup_time','>=',$start_time)
						->where(PASSENGERS_LOG.'.company_id','=',$company_id)
						->as_object()		
						->execute();									
		}
		else
		{
			$result = DB::select(PASSENGERS.'.name',PASSENGERS_LOG.'.passengers_log_id',array(PASSENGERS_LOG.'.current_location','pickup_location'),PASSENGERS_LOG.'.drop_location',PASSENGERS_LOG.'.pickup_longitude',
	PASSENGERS_LOG.'.pickup_latitude',PASSENGERS_LOG.'.drop_latitude',PASSENGERS_LOG.'.drop_longitude',PASSENGERS_LOG.'.travel_status')->from(PASSENGERS_LOG)
						->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
						->where(PASSENGERS_LOG.'.driver_id','=',$id)
						->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
						->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
						->limit(1)->offset(0)
						->order_by(PASSENGERS_LOG.'.pickup_time', 'ASC')		
						->where(PASSENGERS_LOG.'.pickup_time','>=',$start_time)			
						->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
						->as_object()		
						->execute();									
		}
//print_r($result);
		return $result;				
	}
	//Function used to get all driver logs with transactions
	public function get_driver_logs_completed_transaction($id,$msg_status,$driver_reply=null,$travel_status=null,$company_id='',$start=null,$limit=null)
	{


		if($company_id != '')	
		{
			$result = DB::select(PASSENGERS.'.name',PASSENGERS_LOG.'.passengers_log_id',array(PASSENGERS_LOG.'.current_location','pickup_location'),PASSENGERS_LOG.'.drop_location',PASSENGERS_LOG.'.rating')->from(PASSENGERS_LOG)
						->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
						->join(TRANS,'LEFT')->on(PASSENGERS_LOG.'.passengers_log_id','=',TRANS.'.passengers_log_id')
						->where(PASSENGERS_LOG.'.driver_id','=',$id)
						->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
						->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
						->where(PASSENGERS_LOG.'.company_id','=',$company_id)
						->limit($start)->offset($limit)
						->order_by(PASSENGERS_LOG.'.passengers_log_id', 'desc')					
						->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
						->as_object()		
						->execute();									
		}
		else
		{
			$result = DB::select(PASSENGERS.'.name',PASSENGERS_LOG.'.passengers_log_id',array(PASSENGERS_LOG.'.current_location','pickup_location'),PASSENGERS_LOG.'.drop_location',PASSENGERS_LOG.'.rating')->from(PASSENGERS_LOG)
						->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
						->join(TRANS,'LEFT')->on(PASSENGERS_LOG.'.passengers_log_id','=',TRANS.'.passengers_log_id')
						->where(PASSENGERS_LOG.'.driver_id','=',$id)
						->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
						->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
						->limit($start)->offset($limit)
						->order_by(PASSENGERS_LOG.'.passengers_log_id', 'desc')					
						->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
						->as_object()		
						->execute();									
		}
		   //print_r($result);           exit;
		return $result;	
	}

	/*** Get Passenger Profile details with tranaction using passenger log id ***/
	public function get_passenger_log_tranaction_details($passengerlog_id="",$company_id="") 
	{
			$company_condition = '';
			if($company_id != '')
			{
				$company_condition = " and ".PASSENGERS_LOG.".`company_id` =  '$company_id' ";
			}
				$sql = "SELECT ".PASSENGERS_LOG.".current_location,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".no_passengers,".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".rating,  ".PEOPLE.".name AS driver_name,".PEOPLE.".phone AS driver_phone,  ".PASSENGERS.".name AS passenger_name,".PASSENGERS.".email AS passenger_email,".PASSENGERS.".phone AS passenger_phone,".TRANS.".distance,".TRANS.".actual_distance,".TRANS.".fare,".TRANS.".waiting_time,".TRANS.".waiting_cost,".TRANS.".remarks FROM  ".PASSENGERS_LOG." JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) JOIN  ".TRANS." ON (  ".TRANS.".`passengers_log_id` =  ".PASSENGERS_LOG.".`passengers_log_id` ) 
WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$passengerlog_id' $company_condition";


				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}

	public function get_assignedtaxi_alllist($driver_id='',$company_id='')
	{


		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
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



			$company_condition = '';
			if($company_id != '')
			{
				$company_condition = " and ".TAXIMAPPING.".`mapping_companyid` =  '$company_id' ";
			}

		$query = " select 
		".TAXI.".taxi_no,".TAXI.".taxi_capacity,".TAXI.".taxi_speed,".TAXI.".max_luggage from " . TAXIMAPPING . " left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id where mapping_driverid='$driver_id' $company_condition and mapping_enddate >= '$current_time'  order by mapping_startdate ASC"; 
		
		//and mapping_enddate >= '$current_time'

 		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();		

		return $result;

	}	


	//notification reminder for driver
	public function cron_push()
	{
		$current_date = date('Y-m-d');
		
		$query= "SELECT *,NOW() FROM ".DRIVER." AS driver JOIN ".PASSENGERS_LOG." AS pass ON `driver`.`driver_id`= `pass`.`driver_id` WHERE `status` = 'F' AND `pass`.`travel_status`=0 AND `pass`.`driver_reply`='A' AND  `pass`.`pickup_time` >= (NOW() - INTERVAL ".REMINDER_TIME." MINUTE) AND `pass`.`pickup_time` LIKE '$current_date %' GROUP BY `driver`.`driver_id` ";
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $result;	
		
	}
		
	//Update driver break status
	public function update_driver_break_status($driver_id,$breakstatus,$interval_type)
	{
		if($breakstatus == 'IN')
		{
			$break_status = $interval_type;
		}
		else
		{
			$break_status = 'F';
		}
		$sql_query = array(
					'status' => $break_status,
				);
		//print_r($driver_id);
		DB::update(DRIVER)->set($sql_query)
				->where('driver_id', '=' ,$driver_id)
				->execute();
	}
	/** Get Driver Current Journey **/
	public function get_driver_current_journey($driver_id,$company_id,$createdate)
	{
		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
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
		/*$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `pickup_time` < '".$pickup_time."'  and `driver_id` = '".$driver_id."' and `driver_reply` = 'A' and `travel_status` != 1 order by passengers_log_id desc limit 1 "; */					
		$condition="";
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'"; }
		
		if($company_id != '')	
		{	
			$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `driver_id` = '".$driver_id."' and company_id='$company_id' and `driver_reply` = 'A' and `travel_status` = 2 $condition order by passengers_log_id desc limit 1 ";
		}
		else
		{
			$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `driver_id` = '".$driver_id."' and `driver_reply` = 'A' and `travel_status` = 2 $condition order by passengers_log_id desc limit 1 ";
		}	

		$availablity = Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
			
		return $availablity	;
	}
	/** Get Motor Company **/
	public static function motor_details()
	{
			$result = DB::select()->from(MOTORCOMPANY)->where('motor_status','=','A')->order_by('motor_name','ASC')
				->execute()
				->as_array();		
			return $result;
	}
	
	public function getLatLong($address) 
	{		
		$address = str_replace(' ', '+', $address);
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&sensor=false&key='.GOOGLE_GEO_API_KEY;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$geoloc = curl_exec($ch);
		
		//print_r($geoloc);
		$json = json_decode($geoloc);	
		if($json->status == 'OK')
		{		
		return array($json->results[0]->geometry->location->lat, $json->results[0]->geometry->location->lng);
		}
		else
		{
			return array(11.621354, 76.14253698);
		}		
	}
	
	//Getting the latitude and Longitude with City
	public function getLatLongwithcity($address) 
	{		
		$address = str_replace(' ', '+', $address);
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&sensor=false&key='.GOOGLE_GEO_API_KEY;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$geoloc = curl_exec($ch);
		
		//print_r($geoloc);
		$json = json_decode($geoloc);	
		if($json->status == 'OK')
		{		
		$address_arr = $json->results[0]->address_components;
 
		$address = "";
		 
		foreach ($address_arr as $arr1){
		 
		 
		if(strcmp($arr1->types[0],"locality") == 0){
		 
		$city = $arr1->long_name;
		 
		continue;
		 
		}
		 
		if(strcmp($arr1->types[0],"administrative_area_level_1") == 0){
		 
		$state = $arr1->long_name;
		 
		continue;
		 
		}
		 
		if(strcmp($arr1->types[0],"administrative_area_level_2") == 0){
		 
		$state2 = $arr1->long_name;
		 
		continue;
		 
		}
		
		 
		}
		 

		 
		$response = array($json->results[0]->geometry->location->lat, $json->results[0]->geometry->location->lng,$city); //level_1 administrative data not exist
		}
		else
		{
			$cityresult = DB::select()->from(CITY)->where('city_id','=',DEFAULT_CITY)->where('city_status','=','A')->order_by('city_name','ASC')
					->execute()
					->as_array();
			$city = $cityresult[0]['city_name'];
			
			$response = array(LOCATION_LATI,LOCATION_LONG,$city); //level_1 administrative data not exist
		 }
		return $response;
		
		//}		
	}
	//Applying the Haversine Function to get the Distance
	
	function Haversine($start, $finish) 
	{	
		$theta = $start[1] - $finish[1]; 
		$distance = (sin(deg2rad($start[0])) * sin(deg2rad($finish[0]))) + (cos(deg2rad($start[0])) * cos(deg2rad($finish[0])) * cos(deg2rad($theta))); 
		$distance = acos($distance); 
		$distance = rad2deg($distance); 
		$distance = $distance * 60 * 1.1515; 
		
		return round($distance, 2);
	}	
	
	function Tripdistance_Haversine($start, $finish) 
	{	
		if((count($start) > 0)&&(count($finish) > 0))
		{
			$theta = $start[1] - $finish[1]; 
			// modifiedon 04-05-2014
			/*$distance = (sin(deg2rad($start[0])) * sin(deg2rad($finish[0]))) + (cos(deg2rad($start[0])) * cos(deg2rad($finish[0])) * cos(deg2rad($theta))); 
			$distance = acos($distance); 
			$distance = rad2deg($distance); 
			$distance = $distance * 60 * 1.1515; 
			
			return round($distance, 4);*/
			//if($theta > 0 )
			//{
				//echo '<br>';
				//echo 'theta'.$theta;
				//echo '<br>';
				$distance = (sin(deg2rad($start[0])) * sin(deg2rad($finish[0]))) + (cos(deg2rad($start[0])) * cos(deg2rad($finish[0])) * cos(deg2rad($theta))); 
				//echo '<br>';
				$cal_distance = acos($distance); 
				//var_dump($cal_distance);
				if(is_nan($cal_distance))
				{
					$distance = '0';
					return $distance;				
				}
				else
				{
					//echo 'cos_distance'.$cal_distance;
					$red_deg_distance = rad2deg($cal_distance); 
					//echo '<br>';
					//echo 'distance'.$red_deg_distance;
					$final_distance = $red_deg_distance * 60 * 1.1515; 
					//echo '<br>';
					//echo 'miles'.$final_distance;
					$after_round = round($final_distance, 4);
					//echo '<br>'.'After Round'.$after_round;
					return $after_round;
					
				}
			/*}
			else

			{
					$distance = '0';
					return $distance;				
			}	*/		
		}
		else
		{
			$distance = '0';
			return $distance;
		}
	}
	/*** Get Taxi fare per KM & Waiting charge of the company ***/
	public function get_taxi_fare_waiting_charge($taxi_id="")
	{
				$sql = "SELECT * FROM  ".TAXI." JOIN  ".COMPANY." ON (  ".COMPANY.".`cid` =  ".TAXI.".`taxi_company` ) WHERE  ".TAXI.".`taxi_id` =  '$taxi_id'";
				//echo $sql;
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}	
	/** Get Driver Location */
	public function get_driver_location($driver_id)
	{
			$result = DB::select()->from(DRIVER)->where('driver_id','=',$driver_id)
				->execute()
				->as_array();		
			return $result;
	}
	
	/** Get Passenger Details */
	public function passenger_details($id,$company_id)
	{
		$query = "SELECT *, creditcard_no ,creditcard_cvv,CONCAT(REPEAT('X', CHAR_LENGTH(creditcard_cvv))) AS masked_cvv FROM ".PASSENGERS." WHERE id = '$id' ";
		
		//$query= "SELECT *, CONCAT(REPEAT('X', CHAR_LENGTH(creditcard_no) - 4),SUBSTRING(creditcard_no, -4)) AS masked_card,creditcard_cvv,CONCAT(REPEAT('X', CHAR_LENGTH(creditcard_cvv))) AS masked_cvv FROM ".PASSENGERS." WHERE id = '$id' ";

		/*if($company_id != '')
		{
			$query .= " and passenger_cid = '$company_id'";
		}
		else
		{
			$query .= " and passenger_cid = '0'";
		}
		*/
		//echo $query;exit;
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		
		if($result[0]['creditcard_no'] !='')
		{
		$cardno = encrypt_decrypt('decrypt',$result[0]['creditcard_no']);
		//echo $cardno;
		$split = explode('_',$cardno);
		
		$creditcard_no = trim($split[1]);
		if(strlen($creditcard_no) > 9)
		  {
			$masked_card = str_repeat("x", (strlen($creditcard_no) - 4)) . substr($creditcard_no,-4,4);  }
		  else
		  {
			$masked_card = $creditcard_no;	
		  }
		  $result['masked_card'] = $masked_card;
		}
		else
		{
		$result['masked_card'] = '';
		}

		return $result;
	}

	public function passenger_detailsbyemail($email,$company_id)
	{

		$query= "SELECT * FROM ".PASSENGERS." WHERE email = '$email' ";

		if($company_id != '')
		{
			$query .= " and passenger_cid = '$company_id'";
		}
		else
		{
			$query .= " and passenger_cid = 0";
		}
		

		//echo $query;exit;
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
        //echo 'as'.$result[0]['creditcard_no'];exit;	
       /* if($result[0]['creditcard_no'] != "")
        {				
		$creditcard_no = encrypt_decrypt('decrypt',$result[0]['creditcard_no']);
		if(strlen($creditcard_no) > 9)
		  {
			$masked_card = str_repeat("x", (strlen($creditcard_no) - 4)) . substr($creditcard_no,-4,4);  }
		  else
		  {
			$masked_card = $creditcard_no;	
		  }
		 //$result['masked_card'] = $masked_card;
		}
		else
		{
			//$result['masked_card'] = "";
		}*/
		  
		return $result;
	}

	/** Get Passenger Details Using Email */
	public function getpassengerdetails($email)
	{
			$result = DB::select()->from(PASSENGERS)->where('email','=',$email)
				->execute()
				->as_array();		
			return $result;
	}
	
	//Forgot Password for Both Driver and Passenger
	public function forgot_password($array,$password)
	{
		
		if($array['user_type'] == 'P')
		{
			$result = DB::update(PASSENGERS)
			->set(array('password' => Html::chars(md5($password)),'org_password'=>$password))
			->where('phone', '=', $array['phone_no'])
			->execute();

			$result = DB::select()->from(PASSENGERS)->where('phone','=',$array['phone_no'])
			->execute()
			->as_array();

			return $result;	

		}
		else
		{
			$result = DB::update(PEOPLE)
			->set(array('password' => Html::chars(md5($password)),'org_password'=>$password))
			->where('phone', '=', $array['phone_no'])
			->execute();

			$result = DB::select()->from(PEOPLE)->where('phone','=',$array['phone_no'])
			->execute()
			->as_array();

			return $result;	

		}

		
	}

	/*** Get Passenger Profile details using passenger log id ***/
	public function get_driverupcoming_log_details($passengerlog_id="",$company_id='')
	{
		$company_condition = '';
		if($company_id != '')
		{
			$company_condition = "  AND ".PASSENGERS_LOG.".company_id = '$company_id' ";
		}
				$sql = "SELECT * ,  ".PEOPLE.".name AS driver_name,".PEOPLE.".phone AS driver_phone, ".PASSENGERS.".discount AS passenger_discount, ".PASSENGERS.".name AS passenger_name,".PASSENGERS.".email AS passenger_email,".PASSENGERS.".phone AS passenger_phone FROM  ".PASSENGERS_LOG." JOIN  ".COMPANY." ON (  ".PASSENGERS_LOG.".`company_id` =  ".COMPANY.".`cid` ) JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$passengerlog_id' $company_condition";
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}

		/*** Get  Passenger Completed Trip Log **/
	public function get_passenger_log_details($userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null,$company_id)
	{

		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
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


		$condition="";
		if($createdate == 0){ $condition = "AND pg.pickup_time >='".$start_time."'"; }
		/*$sql = "SELECT *,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername  FROM ".PASSENGERS_LOG." as pg LEFT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition  order by pg.passengers_log_id desc LIMIT $start,$limit";     */

/*if($start == 0 && $limit ==0)
{*/

		$company_condition="";
		if($company_id != ""){
			$company_condition = " AND pg.company_id = '$company_id'";
		}
		
		$sql = "SELECT *,pg.passengers_log_id,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername,(select pay_mod_name from ".PAYMENT_MODULES." where pay_mod_id=t.payment_type) as payment_name,pg.passengers_log_id as pass_log_id,t.company_tax as tax_amount,pg.company_tax as tax_percentage,(select count(*) from  ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' and pg.company_id='$company_id' $condition) as total_count  FROM ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition $company_condition order by pg.passengers_log_id desc LIMIT $start,$limit";

//echo $sql;
/*}
else
{
		$sql = "SELECT *,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername,pg.passengers_log_id as pass_log_id   FROM ".PASSENGERS_LOG." as pg LEFT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition  order by pg.passengers_log_id desc LIMIT $start,$limit";
}
*/		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}

	public function get_favourite_trip_details($userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null,$company_id)
	{

		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
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

		$condition="";
		if($createdate == 0){ $condition = "AND pg.pickup_time >='".$start_time."'"; }

		$company_condition="";
		if($company_id != ""){
			$company_condition = " AND pg.company_id = '$company_id'";
		}

		$sql = "SELECT *,pg.passengers_log_id,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername,(select pay_mod_name from ".PAYMENT_MODULES." where pay_mod_id=t.payment_type) as payment_name,pg.passengers_log_id as pass_log_id,(select count(*) from  ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' AND favourite_trip='S' $condition $company_condition ) as total_count  FROM ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' AND favourite_trip='S' $condition  $company_condition order by pg.passengers_log_id desc LIMIT $start,$limit";

		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}

	/*** Get Taxi fare per KM & Waiting charge of the company based Company***/
	public function get_taxi_fare_details($company_id="")
	{
				$sql = "SELECT * FROM  ".COMPANY."  WHERE  `cid` =  '$company_id'";
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}


	/*** Get Taxi Model***/
	public function get_taxi_model_details($taxi_id="")
	{
				$sql = "SELECT * FROM  ".TAXI."  WHERE  `taxi_id` =  '$taxi_id'";
				$result = Db::query(Database::SELECT, $sql)					
					->execute()			
					->as_array(); 								               
				return $result;		
	}

	/*** Get Taxi fare per KM & Waiting charge of the company based Company***/
	public function get_model_fare_details($company_id,$model_id="",$search_city="")
	{

		if($search_city!='')
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".city_id ='".$search_city."'"; 
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
			$sql = "SELECT (SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km,company_model_fare.night_charge,company_model_fare.night_timing_from,company_model_fare.night_timing_to,(SUM(company_model_fare.night_fare)*($city_model_fare)/100) + company_model_fare.night_fare as night_fare,
company_model_fare.waiting_time,company_model_fare.min_km,company_model_fare.below_above_km FROM  ".COMPANY_MODEL_FARE." as company_model_fare WHERE  ".COMPANY_MODEL_FARE.".`model_id` = '$model_id' and ".COMPANY_MODEL_FARE.".`company_cid`= '$company_id'";
		}
		else
		{
			$sql = "SELECT (SUM(model.base_fare)*($city_model_fare)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*($city_model_fare)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*($city_model_fare)/100) + model.below_km as below_km,(SUM(model.above_km)*($city_model_fare)/100) + model.above_km as above_km,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*($city_model_fare)/100) + model.night_fare as night_fare,model.waiting_time,model.min_km,model.below_above_km FROM  ".MOTORMODEL." as model WHERE  model.`model_id` = '$model_id'";			
		}
//echo $sql;
				$result = Db::query(Database::SELECT, $sql)					
				->execute()
				->as_array();											               
				return $result;		
	}

	/*Function Used to get the 
	 * Driver and Taxi Details 
	 * based upon the ID 
	*/
	public function get_driver_details($driver_id,$taxi_id,$cityid)
	{
		/*$taxi_id = DB::select('mapping_taxiid')->from(TAXIMAPPING)
					->where('mapping_driverid','=',$id)				
					->where('mapping_status','=','A')
					->where('mapping_startdate','<=',$this->currentdate)
					->where('mapping_enddate','>=',$this->currentdate)					
					->execute()
					->get('mapping_taxiid');*/
			$taxi_id = $taxi_id;			
		
		//Driver Rating Need to bind with the result
		$ratings['comments'] = DB::select(PASSENGERS_LOG.'.passengers_log_id',PASSENGERS_LOG.'.passengers_id',PASSENGERS_LOG.'.driver_id',PASSENGERS_LOG.'.taxi_id',PASSENGERS_LOG.'.company_id',PASSENGERS_LOG.'.current_location',PASSENGERS_LOG.'.pickup_latitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.drop_location',PASSENGERS_LOG.'.drop_latitude',PASSENGERS_LOG.'.drop_longitude',PASSENGERS_LOG.'.rating',PASSENGERS_LOG.'.comments',PASSENGERS_LOG.'.driver_comments',PEOPLE.'.salutation',PEOPLE.'.name',PEOPLE.'.lastname',PEOPLE.'.email',PEOPLE.'.photo',PEOPLE.'.device_token',PEOPLE.'.device_type')->from(PASSENGERS_LOG)	
					->where('driver_id','=',$driver_id)		
					->join(PEOPLE)->on(PASSENGERS_LOG.'.driver_id','=',PEOPLE.'.id')
					->where('travel_status','=',1)
					->where('driver_reply','=','A')		
					->order_by('createdate','DESC')	
					->limit(5)->offset(0)
					->execute()
					->as_array();



		$model_query = "select city_model_fare from ".CITY." where city_id = '".$cityid."' limit 0,1";	

		$model_fetch = Db::query(Database::SELECT, $model_query)
				->execute()
				->as_array();
		
		if(count($model_fetch)>0)
		{
			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}
		else
		{
			$model_query = "select city_model_fare from ".CITY." where city.default='1' limit 0,1";	

			$model_fetch = Db::query(Database::SELECT, $model_query)
					->execute()
					->as_array();
			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}
		//echo FARE_SETTINGS;
		if(FARE_SETTINGS == 2)
		{
		$result_query = "select company.company_name,(SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as cancellation_nfree,(select company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as company_tax,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km,company_model_fare.night_charge,company_model_fare.night_timing_to,company_model_fare.night_fare,company_model_fare.min_km,company_model_fare.below_above_km,motor_model.model_name,taxi.taxi_id,taxi.taxi_no,taxi.taxi_type,taxi.taxi_model,taxi.taxi_company,taxi.taxi_capacity,taxi.taxi_speed,taxi.max_luggage,taxi.taxi_image,taxi.taxi_serializeimage  from ". TAXI." join ".COMPANY."  ON taxi.taxi_company=company.cid 
		JOIN ".MOTORMODEL."  ON taxi.`taxi_model`=motor_model.model_id 
		JOIN ".COMPANY_MODEL_FARE." as company_model_fare ON taxi.taxi_model = company_model_fare.model_id
		where company_model_fare.company_cid=company.cid  and taxi_id=$taxi_id";
	}
	else
	{
		$result_query = "select company.company_name,(SUM(motor_model.base_fare)*($city_model_fare)/100) + motor_model.base_fare as base_fare,(SUM(motor_model.min_fare)*($city_model_fare)/100) + motor_model.min_fare as min_fare,(SUM(motor_model.cancellation_fare)*($city_model_fare)/100) + motor_model.cancellation_fare as cancellation_fare,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as cancellation_nfree,(select company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as company_tax,(SUM(motor_model.below_km)*($city_model_fare)/100) + motor_model.below_km as below_km,(SUM(motor_model.above_km)*($city_model_fare)/100) + motor_model.above_km as above_km,motor_model.night_charge,motor_model.night_timing_to,motor_model.night_fare,motor_model.min_km,motor_model.below_above_km,motor_model.model_name,taxi.* from ". TAXI." join ".COMPANY."  ON taxi.taxi_company=company.cid JOIN ".MOTORMODEL."  ON taxi.taxi_model=motor_model.model_id where taxi_id=$taxi_id";
	}								
//echo $result_query;
		$result = Db::query(Database::SELECT, $result_query)
				->execute()
				->as_array();

					
		$taxi_additional_field = DB::select('*')->from(ADDFIELD)->where('taxi_id','=',$taxi_id)->execute()->as_array();

		//$additional_field['label_name'] = DB::select('*')->from(MANAGEFIELD)->where('field_status','=','A')->execute()->as_array();
															
			
		return array_merge($result,$taxi_additional_field,$ratings);
	}
	
	/************* Get Current Taxi Details *******************/
	public function get_current_taxi_details($driver_id,$taxi_id,$cityid)
	{
		/*$taxi_id = DB::select('mapping_taxiid')->from(TAXIMAPPING)
					->where('mapping_driverid','=',$id)				
					->where('mapping_status','=','A')
					->where('mapping_startdate','<=',$this->currentdate)
					->where('mapping_enddate','>=',$this->currentdate)					
					->execute()
					->get('mapping_taxiid');*/
			$taxi_id = $taxi_id;			
		
		//Driver Rating Need to bind with the result
		$ratings['comments'] = DB::select()->from(PASSENGERS_LOG)	
					->where('driver_id','=',$driver_id)		
					->where('travel_status','=',1)		
					->order_by('createdate','DESC')	
					->limit(5)->offset(0)
					->execute()
					->as_array();
					
		

		$model_query = "select city_model_fare from ".CITY." where city_id='$cityid' limit 0,1";	

		$model_fetch = Db::query(Database::SELECT, $model_query)
				->execute()
				->as_array();

		if(count($model_fetch) > 0)
		{
			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}
		else
		{
			$model_query = "select city_model_fare from ".CITY." where city.default='1' limit 0,1";	

			$model_fetch = Db::query(Database::SELECT, $model_query)
					->execute()
					->as_array();

			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}
		//echo FARE_SETTINGS;
		if(FARE_SETTINGS == 2)
		{
		$result_query = "select company.company_name,company.cid,(SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as cancellation_nfree,(select company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as company_tax,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km,company_model_fare.night_charge,company_model_fare.night_timing_to,company_model_fare.night_fare,company_model_fare.min_km,company_model_fare.below_above_km,motor_model.model_name,taxi.taxi_id,taxi.taxi_no,taxi.taxi_type,taxi.taxi_model,taxi.taxi_company,taxi.taxi_capacity,taxi.taxi_speed,taxi.max_luggage,taxi.taxi_image,taxi.taxi_serializeimage  from ". TAXI." join ".COMPANY."  ON taxi.taxi_company=company.cid 
		JOIN ".MOTORMODEL."  ON taxi.`taxi_model`=motor_model.model_id 
		JOIN ".COMPANY_MODEL_FARE." as company_model_fare ON taxi.taxi_model = company_model_fare.model_id
		where company_model_fare.company_cid=company.cid  and taxi_id=$taxi_id";
		}
		else
		{
			$result_query = "select company.company_name,company.cid,(SUM(motor_model.base_fare)*($city_model_fare)/100) + motor_model.base_fare as base_fare,(SUM(motor_model.min_fare)*($city_model_fare)/100) + motor_model.min_fare as min_fare,(SUM(motor_model.cancellation_fare)*($city_model_fare)/100) + motor_model.cancellation_fare as cancellation_fare,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as cancellation_nfree,(select company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as company_tax,(SUM(motor_model.below_km)*($city_model_fare)/100) + motor_model.below_km as below_km,(SUM(motor_model.above_km)*($city_model_fare)/100) + motor_model.above_km as above_km,motor_model.night_charge,motor_model.night_timing_to,motor_model.night_fare,motor_model.min_km,motor_model.below_above_km,motor_model.model_name,taxi.* from ". TAXI." join ".COMPANY."  ON taxi.taxi_company=company.cid JOIN ".MOTORMODEL."  ON taxi.taxi_model=motor_model.model_id where taxi_id=$taxi_id";
		}							

		$result = Db::query(Database::SELECT, $result_query)
				->execute()
				->as_array();

		
		return array_merge($result,$ratings);
	}

	public function passengerlogid_details($log_id)	
	{
	
		//$sql = "SELECT *, p.name as passenger_name,AES_DECRYPT(p.creditcard_no,'".ENCRYPT_KEY."') as creditcard_no,p.email as passenger_email,p.phone as passenger_phone,pe.name as driver_name,pe.email as driver_email,pe.phone as driver_phone,p.device_token as passenger_devicetoken,pe.device_token as driver_devicetoken,pg.search_city,pg.taxi_id FROM ".PASSENGERS_LOG." as pg left join ".PASSENGERS." as p on p.id=pg.passengers_id  left join ".PEOPLE." as pe on pg.driver_id=pe.id WHERE passengers_log_id = '$log_id'";
		$sql = "SELECT *, p.name as passenger_name,p.creditcard_no,p.email as passenger_email,p.phone as passenger_phone,pe.name as driver_name,pe.email as driver_email,pe.phone as driver_phone,p.device_token as passenger_devicetoken,pe.device_token as driver_devicetoken,pg.search_city,pg.taxi_id FROM ".PASSENGERS_LOG." as pg left join ".PASSENGERS." as p on p.id=pg.passengers_id  left join ".PEOPLE." as pe on pg.driver_id=pe.id WHERE passengers_log_id = '$log_id'";

		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

	}

	public function passenger_transdetails($log_id)	
	{
	
		$sql = "SELECT pl.passengers_log_id,pl.booking_key,pl.passengers_id,pl.driver_id,pl.taxi_id,pl.company_id,pl.current_location,pl.pickup_latitude,pl.pickup_longitude,pl.drop_location,pl.drop_latitude,pl.drop_longitude,pl.no_passengers,pl.approx_distance,pl.approx_duration,pl.approx_fare,pl.time_to_reach_passen,pl.pickup_time,pl.dispatch_time,pl.pickupdrop,pl.rating,pl.comments,pl.travel_status,pl.driver_reply,pl.createdate,pl.booking_from,pl.company_tax,pl.faretype,pl.bookingtype,pl.driver_comments,
				
				t.id as job_referral,t.distance,t.actual_distance,t.tripfare,t.fare,t.tips,t.waiting_time,t.waiting_cost,t.company_tax as tax_amount,t.amt,t.passenger_discount,t.account_discount,t.credits_used,t.transaction_id,t.payment_type,t.payment_status,t.admin_amount,t.company_amount,t.nightfare_applicable,t.nightfare,t.trans_packtype,t.waiting_time,
				
				pl.passengers_log_id, p.name as passenger_name,p.email as 
				passenger_email,pe.name as driver_name,pe.email as driver_email 
				FROM ".PASSENGERS_LOG." as pl left join ".PASSENGERS." as p on 
				p.id=pl.passengers_id left join ".TRANS." as t on 
				t.passengers_log_id=pl.passengers_log_id left join ".PEOPLE." as pe on pl.driver_id=pe.id WHERE t.passengers_log_id = '$log_id'";

		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

	}

	public function paypal_details()	
	{
	
		$sql = "SELECT *  FROM ".PAYMENT_GATEWAYS;

		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

	}

	public function siteinfo_details()	
	{
	
		$sql = "SELECT admin_commission,referral_discount  FROM ".SITEINFO;

		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

	}


	public function triptransact_details($details)
	{	

			$first_query = "select * from " . PACKAGE_REPORT . " join ".PACKAGE." on ".PACKAGE.".package_id =".PACKAGE_REPORT.".upgrade_packageid  where ".PACKAGE_REPORT.".upgrade_companyid = ".$details['company_id']."  order by upgrade_id desc limit 0,1";

		$first_results = Db::query(Database::SELECT, $first_query)
				->execute()
				->as_array();

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
				$admin_amt = ($details['fare'] * $details[0]['admin_commission'])/100; //payable to admin
				$admin_amt = round($admin_amt, 2);
				$total_balance = round($details['fare'],2);				
		
				//Set Commission to Admin	
				$updatequery = " UPDATE ". PEOPLE ." SET account_balance=account_balance+$total_balance WHERE user_type = 'A'";	

				$updateresult = Db::query(Database::UPDATE, $updatequery)
						->execute();
			}
			else
			{
				$admin_amt = 0;
			}

			$company_amt = $details['fare'] - $admin_amt; 	
			$company_amt = round($company_amt, 2);	

		     	//Set Commission to Admin	
			$updatequery = " UPDATE ". PEOPLE ." SET account_balance=account_balance+$company_amt WHERE user_type = 'C' and company_id=".$details['company_id'];	

			$updateresult = Db::query(Database::UPDATE, $updatequery)
			->execute();

			
			$current_time = date('Y-m-d H:i:s');

			$result = DB::insert(TRANS, array('passengers_log_id','distance','actual_distance','tripfare','fare','tips','waiting_cost','waiting_time','company_tax','passenger_discount','account_discount','credits_used','remarks','correlation_id','ack','transaction_id',
'payment_type','order_time','amt','currency_code','payment_status','captured','admin_amount','company_amount','trans_packtype','nightfare_applicable','nightfare'))
			->values(array($details['passengers_log_id'],$details['distance'],$details['actual_distance'],$details['tripfare'],$details['fare'],$details['tips'],$details['waiting_cost'],$details['waiting_time'],$details['company_tax'],$details['passenger_discount'],$details['account_discount'],$details['credits_used'],$details['remarks'],$details['CORRELATIONID'],$details['ACK'],$details['TRANSACTIONID'],$details['payment_type'],$current_time,$details['amt'],$details['CURRENCYCODE'],$details['ACK'],'1',$admin_amt,$company_amt,$check_package_type,$details['nightfare_applicable'],$details['nightfare']))
			->execute();

			return $result; 
	}

	public function cancel_triptransact_details($details,$cancellation_nfree)
	{	
		//print_r($details);exit;
		if($cancellation_nfree != 0)
		{
			$first_query = "select * from " . PACKAGE_REPORT . " join ".PACKAGE." on ".PACKAGE.".package_id =".PACKAGE_REPORT.".upgrade_packageid  where ".PACKAGE_REPORT.".upgrade_companyid = ".$details['company_id']."  order by upgrade_id desc limit 0,1";

		$first_results = Db::query(Database::SELECT, $first_query)
				->execute()
				->as_array();

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
				$admin_amt = ($details['total_fare'] * $details[0]['admin_commission'])/100; //payable to admin
				$admin_amt = round($admin_amt, 2);
				$total_balance = round($details['total_fare'],2);				
		
				//Set Commission to Admin	
				$updatequery = " UPDATE ". PEOPLE ." SET account_balance=account_balance+$total_balance wHERE user_type = 'A'";	

				$updateresult = Db::query(Database::UPDATE, $updatequery)
						->execute();
			}
			else
			{
				$admin_amt = 0;
			}			

			//$company_amt = $details['total_fare'] - $admin_amt; 	
			$company_amt = $details['total_fare'];
			$company_amt = round($company_amt, 2);	

		     	//Set Commission to Admin	
			$updatequery = " UPDATE ". PEOPLE ." SET account_balance=account_balance+$company_amt wHERE user_type = 'C' and company_id=".$details['company_id'];	

			$updateresult = Db::query(Database::UPDATE, $updatequery)
			->execute();

			if($details['travel_status'] == 4)
			{
			$updatequery = " UPDATE ". PASSENGERS_LOG ." SET comments='".$details['remarks']."', travel_status='".$details['travel_status']."' WHERE passengers_log_id=".$details['passenger_log_id'];	

			$updateresult = Db::query(Database::UPDATE, $updatequery)
			->execute();

			}
			$current_time = date('Y-m-d H:i:s');

			$result = DB::insert(TRANS, array('passengers_log_id','fare','remarks','correlation_id','ack','transaction_id',
'payment_type','order_time','amt','currency_code','payment_status','captured','admin_amount','company_amount','trans_packtype'))
			->values(array($details['passenger_log_id'],$details['total_fare'],$details['remarks'],$details['CORRELATIONID'],$details['ACK'],$details['TRANSACTIONID'],$details['pay_mod_id'],$current_time,$details['total_fare'],$details['CURRENCYCODE'],$details['ACK'],'1',$admin_amt,$company_amt,$check_package_type))
			->execute();

			return $result; 
		}
		else
		{
			$updatequery = " UPDATE ". PASSENGERS_LOG ." SET comments='".$details['remarks']."', travel_status='4' WHERE passengers_log_id=".$details['passenger_log_id'];	

			$updateresult = Db::query(Database::UPDATE, $updatequery)
			->execute();			
		}
	}


	public function check_tranc($log_id,$flag)
	{
		$condition="";
		if($flag == 1)
		{
			$condition = "and pl.travel_status = '1'";
		}
		$sql = "SELECT pl.travel_status,pl.driver_id,t.id FROM ".PASSENGERS_LOG." as pl join ".TRANS." as t on 
				t.passengers_log_id=pl.passengers_log_id  WHERE t.passengers_log_id = '$log_id' $condition";

		$result =  Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
		if($flag == 1)
		{
			return count($result);
		}
		else
		{
			return $result;
		}

	}

	public function check_travelstatus($log_id)
	{
		$sql = "SELECT travel_status FROM ".PASSENGERS_LOG." WHERE passengers_log_id = '$log_id' ";

		$result =  Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
		if(count($result) > 0)
		{	
			return $result[0]['travel_status'];
		}
		else
		{
			return -1;
		}

	}

	public function company_details($cid)
	{
		$sql = "SELECT * FROM ".COMPANY." WHERE cid = '$cid'";

		$result =  Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

		return $result;

	}
	
	public function get_driver_profile_details($id="",$company_id="")
	{
		if($company_id != "")
		{
			$sql= "SELECT salutation,name,lastname,email,address,password,org_password,otp,photo,device_type,device_token,device_id,phone,login_status,user_type,driver_referral_code,notification_setting,company_id,driver_license_id,profile_picture,".COMPANY.".bankname,".COMPANY.".userid,".COMPANY.".bankaccount_no FROM ".PEOPLE." JOIN  ".COMPANY." ON (  ".PEOPLE.".`company_id` =  ".COMPANY.".`cid` )  WHERE id = '$id' and company_id='$company_id' AND user_type = 'D' ";
		}
		else
		{
			$sql= "SELECT salutation,name,lastname,email,address,password,org_password,otp,photo,device_type,device_token,device_id,phone,login_status,user_type,driver_referral_code,notification_setting,company_id,driver_license_id,profile_picture,".COMPANY.".bankname,".COMPANY.".userid,".COMPANY.".bankaccount_no FROM ".PEOPLE." JOIN  ".COMPANY." ON (  ".PEOPLE.".`company_id` =  ".COMPANY.".`cid` )  WHERE id = '$id' AND user_type = 'D' ";
		}
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}

	public function save_driver_location_history($location_array,$default_companyid)
	{	

		if($default_companyid == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
		}	
		else
		{
			$timezone_base_query = "select time_zone from  company where cid='$default_companyid' "; 
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
		
		$driver_id = $location_array['driver_id'];
		$trip_id = $location_array['trip_id'];
		$status = $location_array['status'];

		$company_query = "SELECT * FROM people WHERE id = '$driver_id' and user_type='D' ";

		$company_result =  Db::query(Database::SELECT, $company_query)
			->execute()
			->as_array();

		if(count($company_result) > 0)
		{	
			$first_query = "select * from " . PACKAGE_REPORT . " join ".PACKAGE." on ".PACKAGE.".package_id =".PACKAGE_REPORT.".upgrade_packageid  where ".PACKAGE_REPORT.".upgrade_companyid = ".$company_result[0]['company_id']."  order by upgrade_id desc limit 0,1";

			$first_results = Db::query(Database::SELECT, $first_query)
					->execute()
					->as_array();

			if(count($first_results) > 0)
			{
				$check_package_type = $first_results[0]['driver_tracking'];
			}

			if($check_package_type != 'S')
			{	
				return 3;
			}
		}
		else
		{
			return 3;
		}



		$location_record_array = explode(',',$location_array['locations']);
		
		//print_r($location_record_array);

		$sql = "SELECT * FROM driver_location_history WHERE trip_id = '$trip_id'";

		$trip_check =  Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

		$location_record = '';	
		/*foreach($location_record_array as $key=>$value)
		{
			$location_record .='['.$value.']'.',';
		}*/
		$location_record = '['.$location_array['locations'].']';
		if($driver_id!="")
		{
			if($trip_id != '')
			{	
				
				$sql = "SELECT * FROM driver_location_history WHERE trip_id = '$trip_id'";

				$trip_check =  Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
				//echo count($trip_check);
				if(count($trip_check)==0)
				{
					//$location_record = substr($location_record, 0, -1);		
					$fieldname_array = array('driver_id','trip_id','active_record','status','createdate');
					$values_array = array($driver_id,$trip_id,$location_record,$status,$current_time);
					if($trip_id != 0)
					{
						$result = DB::insert('driver_location_history', $fieldname_array)
							->values($values_array)
							->execute();					
						if($result)
						{
							return 1;
						}
						else
						{
							return 0;
						}
					}
					else
					{
						return 5; // If there is no trip id means update only driver current location. This is done at controller it self
					}
				}
				else
				{
					// We updated driver location waypoits one by one(lat,lon)
						$pickup=array();
						$drop=array();
						//$location_record = substr($location_record, 0, -1);
						//print_r($trip_check[0]['distance']);echo '<br>';//exit;
						if(($trip_check[0]['active_record'] != "") && ($trip_check[0]['active_record'] != '[]'))
						{							
							$active_record = explode('],[',$trip_check[0]['active_record']);
							//print_r($active_record);echo '<br>';
							$pickup_location = str_replace(']','',end($active_record));
							$pickup_location = str_replace('[','',$pickup_location);
							$pickup_location = explode(',',$pickup_location);
							$drop_location = explode(',',$location_array['locations']);
							$distance=$this->Tripdistance_Haversine($pickup_location, $drop_location);
							$current_distance = 0;
							if($distance > 0)
							{
							$current_distance = round($distance * 1.609344,4);
							}							
							$prev_distance = $trip_check[0]['distance'];
							$total_distance = $prev_distance+$current_distance;
							$location_record = $trip_check[0]['active_record'].','.$location_record;
							
							if($trip_id != 0)
							{
								$updatequery = " UPDATE driver_location_history SET driver_id='$driver_id',status='$status',active_record='$location_record',distance='$total_distance' where trip_id = '$trip_id'";	

								$updateresult = Db::query(Database::UPDATE, $updatequery)
								->execute();
								
								$distance_updatequery = " UPDATE passengers_log SET distance='$total_distance' where passengers_log_id = '$trip_id'";	

								$distance_updateresult = Db::query(Database::UPDATE, $distance_updatequery)
								->execute();
								if($updateresult)
								{
									return 1;
								}
								else
								{
									return 0;
								}
							}
							else
							{ //echo 'fsf';
								return 5;
							}										
						}
						else
						{
							return 3;
						}

				}

			}
			else
			{
				return 5;
			}
			/*else // When the driver in free status
			{
				$location_record = substr($location_record, 0, -1);

				//$startdate = date('Y-m-j').' 00:00:00';
				//$enddate = date('Y-m-j').' 23:59:59';
				
				$find_query = "SELECT * FROM driver_location_history WHERE trip_id = '0' and status='F' and driver_id='$driver_id' and createdate between '$start_time' and '$end_time'";

				$find_result =  Db::query(Database::SELECT, $find_query)
					   ->execute()
					   ->as_array(); 

				if(count($find_result) == 0)
				{

					$fieldname_array = array('driver_id','free_record','status','createdate');
					$values_array = array($driver_id,$location_record,$status,$current_time);
					$result = DB::insert('driver_location_history', $fieldname_array)
						->values($values_array)
						->execute();

					if($result)
					{
						return 1;
					}
					else
					{
						return 0;
					}

				}
				else
				{

					$location_record = substr($location_record, 0, -1);
					$location_record = $find_result[0]['free_record'].','.$location_record;

					$location_hid = $find_result[0]['location_hid'];	

					$updatequery = " UPDATE driver_location_history SET driver_id='$driver_id',status='$status',free_record='$location_record' where location_hid = '$location_hid'";	

					$updateresult = Db::query(Database::UPDATE, $updatequery)
					->execute();

					if($updateresult)
					{
						return 1;
					}
					else
					{
						return 0;
					}
				}
					
				
			} */


		}
		else
		{
			return 2;
		}

	}
	
	public function trip_id_check($trip_id="")
	{
		
		$sql = "SELECT * FROM driver_location_history WHERE trip_id = '$trip_id'";

		$result =  Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

		return count($result);
					
	}

	//Update the Journey Status with drop location
	public function update_journey_statuswith_drop($id,$msg_status,$driver_reply,$travel_status,$drop_latitude,$drop_longitude,$drop_location,$drop_time)
	{
		$sql_query = array(
					'msg_status' =>$msg_status,
					'driver_reply' => $driver_reply,
					'travel_status' => $travel_status,
					'drop_latitude'=>$drop_latitude,
					'drop_longitude'=>$drop_longitude,
					'drop_location'=>$drop_location,
					'drop_time'=>$drop_time					
				);			
		DB::update(PASSENGERS_LOG)->set($sql_query)
				->where('passengers_log_id', '=' ,$id)
				->execute();
				
	}	
	
		//Update the Journey Status with out drop location
	public function update_journey_status($id,$msg_status,$driver_reply,$travel_status)
	{
		$sql_query = array(
					'msg_status' =>$msg_status,
					'driver_reply' => $driver_reply,
					'travel_status' => $travel_status					
				);			
		DB::update(PASSENGERS_LOG)->set($sql_query)
				->where('passengers_log_id', '=' ,$id)
				->execute();
				
	}	
	//Function used to Transactions for driver
	public function get_transaction_driver_details($company_id,$driver_id,$status="",$driver_reply="",$createdate="",$start=null,$limit=null)
	{

		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
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

		$condition="";
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'"; }
		
		
		$sql = "SELECT ".PASSENGERS.".name as name,".PASSENGERS_LOG.".current_location as pickup_location,".PASSENGERS_LOG.".passengers_log_id,".PASSENGERS_LOG.".pickup_latitude as pickup_latitude,".PASSENGERS_LOG.".pickup_longitude as pickup_longitude,
		".PASSENGERS_LOG.".drop_location AS drop_location,".PASSENGERS_LOG.".drop_latitude as drop_latitude,
		".PASSENGERS_LOG.".drop_longitude as drop_longitude,".PASSENGERS_LOG.".rating as rating,".TRANS.".fare as fare,	
		".TRANS.".payment_status as payment_status from ".PASSENGERS_LOG." left join ".TRANS." 
		on  ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id left join ".PASSENGERS." 
		on  ".PASSENGERS_LOG.".passengers_id = ".PASSENGERS.".id		
		WHERE ".PASSENGERS_LOG.".driver_id = '$driver_id' AND ".PASSENGERS_LOG.".travel_status = '$status' AND ".PASSENGERS_LOG.".driver_reply = '$driver_reply' $condition 
		  order by ".PASSENGERS_LOG.".passengers_log_id desc LIMIT $start,$limit";
		//print_r($sql);
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	
	/*** Get Passenger Profile details with tranaction using passenger log id ***/
	public function get_transaction_passenger_log_tranaction_details($passengerlog_id="") 
	{
				$sql = "SELECT ".PASSENGERS_LOG.".current_location,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".no_passengers,".PASSENGERS_LOG.".taxi_id,".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".rating, ".PEOPLE.".name AS driver_name,".PEOPLE.".photo AS driver_image,".PEOPLE.".phone AS driver_phone,  ".PASSENGERS.".name AS passenger_name,".TRANS.".transaction_id,".TRANS.".payment_type,".TRANS.".amt,".TRANS.".payment_status,".TRANS.".pending_reason,".TRANS.".distance,".TRANS.".actual_distance,".TRANS.".tripfare,".TRANS.".fare,".TRANS.".company_tax,".TRANS.".passenger_discount,".TRANS.".account_discount,".TRANS.".waiting_time,".TRANS.".waiting_cost,".TRANS.".remarks,".TRANS.".passenger_discount,".TRANS.".amt FROM  ".PASSENGERS_LOG." JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) JOIN  ".TRANS." ON (  ".TRANS.".`passengers_log_id` =  ".PASSENGERS_LOG.".`passengers_log_id` ) 
WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$passengerlog_id'";
//echo $sql;
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}	

	/*** Get Passenger get_trip_detail passenger log id ***/
	public function get_trip_detail($passengerlog_id="") 
	{
				$sql = "SELECT 
				".PASSENGERS_LOG.".passengers_log_id,".PASSENGERS_LOG.".current_location,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".no_passengers,".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".rating,".PASSENGERS_LOG.".no_passengers,".PASSENGERS_LOG.".notes_driver, ".PEOPLE.".name AS driver_name,".PEOPLE.".profile_picture AS driver_image,".PEOPLE.".id AS driver_id,".TAXI.".taxi_no AS taxi_no,".TAXI.".taxi_id AS taxi_id,".PASSENGERS_LOG.".travel_status AS travel_status,".PASSENGERS_LOG.".search_city AS city_id ,".PASSENGERS_LOG.".current_location AS pickup_location,".PASSENGERS_LOG.".pickup_latitude,".PASSENGERS_LOG.".pickup_longitude,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".drop_latitude,".PASSENGERS_LOG.".drop_longitude,".PASSENGERS_LOG.".time_to_reach_passen,".PEOPLE.".phone AS driver_phone,  ".PASSENGERS.".name AS passenger_name, ".PASSENGERS.".phone AS passenger_phone,".PASSENGERS.".profile_image AS passenger_image,IFNULL(".TRANS.".id,0) as job_ref,IFNULL(".TRANS.".payment_type,0) as payment_type, IFNULL(".TRANS.".amt,0) as amt FROM  ".PASSENGERS_LOG." JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
				JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) JOIN  ".TAXI." ON (  ".TAXI.".`taxi_id` =  ".PASSENGERS_LOG.".`Taxi_id` )  LEFT JOIN  ".TRANS." ON ( ".PASSENGERS_LOG.".`passengers_log_id` =  ".TRANS.".`passengers_log_id` ) 
				WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$passengerlog_id'";
//echo $sql;
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}
	
	/** Get passengers Current booked details for passenger with Upcoming and ongoing trip details**/	
	public function get_passenger_current_log_details($company_id,$pagination,$userid="",$travelstatus="",$driver_reply="",$createdate="",$start=null,$limit=null)
	{

		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
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


		$condition="";
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'"; }
		
		if($pagination == 1)
		{
			$orderby = "order by ".PASSENGERS_LOG.".passengers_log_id desc LIMIT $start,$limit";
		}
		else
		{
			$orderby = "order by ".PASSENGERS_LOG.".passengers_log_id desc";
		}		
		//passengers_log_id,passengers_id,driver_id,taxi_id,current_location,pickup_latitude,pickup_longitude,drop_location,drop_latitude,drop_longitude,pickup_time,travel_status
		$sql = "SELECT ".PASSENGERS_LOG.".passengers_log_id,".PASSENGERS_LOG.".current_location as pickup_location,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".no_passengers,time(".PASSENGERS_LOG.".pickup_time) as pickuptime,".PASSENGERS_LOG.".rating, ".PEOPLE.".name AS driver_name,".PEOPLE.".lastname AS driver_lastname,".PEOPLE.".photo AS driver_image,".PEOPLE.".id AS driver_id,".TAXI.".taxi_no AS taxi_no,".TAXI.".taxi_id AS taxi_id,".PASSENGERS_LOG.".travel_status AS travel_status,".PASSENGERS_LOG.".search_city AS city_id ,".PASSENGERS_LOG.".current_location AS pickup_location,".PASSENGERS_LOG.".pickup_latitude,".PASSENGERS_LOG.".pickup_longitude,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".drop_latitude,".PASSENGERS_LOG.".drop_longitude,".PASSENGERS_LOG.".time_to_reach_passen,".PEOPLE.".phone AS driver_phone,  ".PASSENGERS.".name AS passenger_name,".PASSENGERS.".lastname AS passenger_lastname FROM  ".PASSENGERS_LOG." JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) JOIN  ".TAXI." ON (  ".TAXI.".`taxi_id` =  ".PASSENGERS_LOG.".`Taxi_id` )  WHERE passengers_id = '$userid' AND (travel_status = '9' or travel_status = '2' or travel_status = '3') AND driver_reply = '".$driver_reply."'  $condition  $orderby";
//echo $sql;

		/*$sql = "SELECT *,(select concat(name,' ',lastname) from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as drivername  FROM ".PASSENGERS_LOG."   order by passengers_log_id desc LIMIT $start";     */
		//echo $sql;
		

		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();		
	}

	/** Get passengers Cancelled trip details**/

	public function get_passenger_cancelled_trip_details($company_id,$pagination,$userid="",$travelstatus="",$driver_reply="",$createdate="",$start=null,$limit=null)
	{
//echo $createdate;
		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
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


		$condition="";
		//if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'"; }
		
		if($pagination == 1)
		{
			$orderby = "order by ".PASSENGERS_LOG.".passengers_log_id desc LIMIT $start,$limit";
		}
		else
		{
			$orderby = "order by ".PASSENGERS_LOG.".passengers_log_id desc";
		}		
		//passengers_log_id,passengers_id,driver_id,taxi_id,current_location,pickup_latitude,pickup_longitude,drop_location,drop_latitude,drop_longitude,pickup_time,travel_status
$sql = "SELECT ".PASSENGERS_LOG.".passengers_log_id,".PASSENGERS_LOG.".current_location,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".no_passengers,".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".rating, ".PEOPLE.".name AS driver_name,".PEOPLE.".photo AS driver_image,".PEOPLE.".id AS driver_id,".TAXI.".taxi_no AS taxi_no,".TAXI.".taxi_id AS taxi_id,".PASSENGERS_LOG.".travel_status AS travel_status,".PASSENGERS_LOG.".search_city AS city_id ,".PASSENGERS_LOG.".current_location AS pickup_location,".PASSENGERS_LOG.".pickup_latitude,".PASSENGERS_LOG.".pickup_longitude,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".drop_latitude,".PASSENGERS_LOG.".drop_longitude,".PASSENGERS_LOG.".time_to_reach_passen,".PEOPLE.".phone AS driver_phone,  ".PASSENGERS.".name AS passenger_name,IFNULL(".TRANS.".id,0) as job_ref,IFNULL(".TRANS.".payment_type,0) as payment_type, IFNULL(".TRANS.".amt,0) as amt FROM  ".PASSENGERS_LOG." JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
				JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) JOIN  ".TAXI." ON (  ".TAXI.".`taxi_id` =  ".PASSENGERS_LOG.".`Taxi_id` )  LEFT JOIN  ".TRANS." ON ( ".PASSENGERS_LOG.".`passengers_log_id` =  ".TRANS.".`passengers_log_id` ) 
				WHERE   passengers_id = '$userid' AND (travel_status = '4') AND driver_reply = '".$driver_reply."'  $condition  $orderby";
										
		//echo $sql;
		/*$sql = "SELECT *,(select concat(name,' ',lastname) from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as drivername  FROM ".PASSENGERS_LOG."   order by passengers_log_id desc LIMIT $start";     */
		//echo $sql;
		

		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();		
	}

	/** Get passengers Current booked details for passenger with Upcoming and ongoing trip details**/	
	public function get_driver_current_log_details($company_id,$pagination,$userid="",$travelstatus="",$driver_reply="",$createdate="",$start=null,$limit=null)
	{

		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
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


		$condition="";
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'"; }
		
		if($pagination == 1)
		{
			$orderby = "order by ".PASSENGERS_LOG.".pickup_time asc LIMIT $start,$limit";
		}
		else
		{
			$orderby = "order by ".PASSENGERS_LOG.".pickup_time desc";
		}		
		//passengers_log_id,passengers_id,driver_id,taxi_id,current_location,pickup_latitude,pickup_longitude,drop_location,drop_latitude,drop_longitude,pickup_time,travel_status
		$sql = "SELECT ".PASSENGERS_LOG.".passengers_log_id,".PASSENGERS_LOG.".current_location as pickup_location,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".no_passengers,time(".PASSENGERS_LOG.".pickup_time) as pickuptime,".PASSENGERS_LOG.".rating, ".PEOPLE.".name AS driver_name,".PEOPLE.".lastname AS driver_lastname,".PEOPLE.".photo AS driver_image,".PEOPLE.".id AS driver_id,".TAXI.".taxi_no AS taxi_no,".TAXI.".taxi_id AS taxi_id,".PASSENGERS_LOG.".travel_status AS travel_status,".PASSENGERS_LOG.".search_city AS city_id ,".PASSENGERS_LOG.".current_location AS pickup_location,".PASSENGERS_LOG.".pickup_latitude,".PASSENGERS_LOG.".pickup_longitude,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".drop_latitude,".PASSENGERS_LOG.".drop_longitude,".PASSENGERS_LOG.".time_to_reach_passen,".PEOPLE.".phone AS driver_phone,  ".PASSENGERS.".name AS passenger_name,".PASSENGERS.".lastname AS passenger_lastname FROM  ".PASSENGERS_LOG." JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) JOIN  ".TAXI." ON (  ".TAXI.".`taxi_id` =  ".PASSENGERS_LOG.".`Taxi_id` )  WHERE driver_id = '$userid' AND (travel_status = '9' or travel_status = '3') AND driver_reply = '".$driver_reply."'  $condition  $orderby";
//echo $sql;echo '<br>';
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();		
	}
			
	/*** Get Passenger Profile details using passenger log id  ***/
	public function get_passenger_log_detail($passengerlog_id="")
	{
				$sql = "SELECT ".PEOPLE.".phone,".PASSENGERS_LOG.".booking_key,".PASSENGERS_LOG.".passengers_id,".PASSENGERS_LOG.".driver_id,".PASSENGERS_LOG.".taxi_id,
				".PASSENGERS_LOG.".company_id,".PASSENGERS_LOG.".current_location,".PASSENGERS_LOG.".pickup_latitude,".PASSENGERS_LOG.".pickup_longitude,
				".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".drop_latitude,".PASSENGERS_LOG.".drop_longitude,".PASSENGERS_LOG.".no_passengers,
				".PASSENGERS_LOG.".approx_distance,".PASSENGERS_LOG.".approx_duration,".PASSENGERS_LOG.".approx_fare,".PASSENGERS_LOG.".time_to_reach_passen,
				".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".actual_pickup_time,".PASSENGERS_LOG.".drop_time,".PASSENGERS_LOG.".account_id,".PASSENGERS_LOG.".accgroup_id,".PASSENGERS_LOG.".pickupdrop,
				".PASSENGERS_LOG.".rating,".PASSENGERS_LOG.".comments,".PASSENGERS_LOG.".travel_status,".PASSENGERS_LOG.".driver_reply,
				".PASSENGERS_LOG.".msg_status,".PASSENGERS_LOG.".createdate,".PASSENGERS_LOG.".booking_from,".PASSENGERS_LOG.".search_city,
				".PASSENGERS_LOG.".sub_logid,".PASSENGERS_LOG.".booking_from_cid,".PASSENGERS_LOG.".company_tax,".PASSENGERS_LOG.".distance,
				".PEOPLE.".name AS driver_name,".PASSENGERS.".discount AS passenger_discount,".PEOPLE.".phone AS driver_phone,".PEOPLE.".profile_picture AS driver_photo,".PEOPLE.".device_id AS driver_device_id,".PEOPLE.".device_token AS driver_device_token,".PEOPLE.".device_type AS driver_device_type,".PASSENGERS.".discount AS passenger_discount,".PASSENGERS.".device_id AS passenger_device_id,".PASSENGERS.".device_token AS passenger_device_token,".PASSENGERS.".referred_by AS referred_by,".PASSENGERS.".referrer_earned AS referrer_earned,".PASSENGERS.".device_type AS passenger_device_type, ".PASSENGERS.".salutation AS passenger_salutation,".PASSENGERS.".name AS passenger_name,".PASSENGERS.".lastname AS passenger_lastname,".PASSENGERS.".email AS passenger_email,".PASSENGERS.".phone AS passenger_phone,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".id=company.cid ) as cancellation_nfree FROM  ".PASSENGERS_LOG." JOIN  ".COMPANY." ON (  ".PASSENGERS_LOG.".`company_id` =  ".COMPANY.".`cid` ) JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$passengerlog_id'";
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}

	/*** Get Passenger Profile details using passenger log id ***/
	public function get_passenger_cancel_faredetail($passengerlog_id="")
	{

		$model_base_query = "select search_city from ".PASSENGERS_LOG." where passengers_log_id=".$passengerlog_id; 
		

		$model_fetch = Db::query(Database::SELECT, $model_base_query)
				->execute()
				->as_array();

		if(count($model_fetch) > 0)
		{
			$city_id = $model_fetch[0]['search_city'];

			$model_base1_query = "select city_model_fare from ".CITY." where ".CITY.".city_id=".$city_id; 

			$model_fetch1 = Db::query(Database::SELECT, $model_base1_query)
				->execute()
				->as_array();

			if(count($model_fetch1) > 0)
			{	
				$city_model_fare = $model_fetch1[0]['city_model_fare'];
			}
			else
			{
				$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1"; 

				$model_fetch = Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array();

				$city_model_fare = $model_fetch[0]['city_model_fare'];
			}	

		}
		else
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1"; 

			$model_fetch = Db::query(Database::SELECT, $model_base_query)
				->execute()
				->as_array();

			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}
			

		$sql = "select (SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare FROM ".PASSENGERS_LOG." AS pg JOIN ".TAXI." as taxi ON pg.`taxi_id`=taxi.`taxi_id` JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model` ";
				$result = Db::query(Database::SELECT, $sql)					
				->execute()
				->as_array();												               
				return $result[0]['cancellation_fare'];		
	}

		/*** Get Passenger Profile details using Driver id ***/
	public function get_driver_log_details($driver_id="",$company_id)
	{

		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
		}	
		else
		{
			$model_base_query = "select time_zone from  company where cid='$company_id' "; 
			$model_fetch = Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array();			

			if($model_fetch[0]['time_zone'] != '')
			{
				$current_time = convert_timezone('now',$model_fetch[0]['time_zone']);
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
		$company_condition = "";
		if($company_id != ""){
			$company_condition = " AND ".PASSENGERS_LOG.".company_id = '$company_id'";
		}

				 $sql = "SELECT ".PASSENGERS_LOG.".passengers_log_id FROM  ".PASSENGERS_LOG." WHERE  ".PASSENGERS_LOG.".`driver_id` =  '$driver_id' and ".PASSENGERS_LOG.".pickup_time >='".$current_time."' $company_condition and (travel_status = '9' OR travel_status = '5' OR travel_status='2') and driver_reply = 'A'";
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}
	public function update_driver_status($status,$driverid)
	{
		
		$update_array = array("status" => $status);
		return $result = DB::update(DRIVER)
					->set($update_array)
					->where('driver_id', '=', $driverid)
					->execute();						
	}
	// Update Driver Shift Status
	public function update_driver_shift_status($id,$shift_status,$stat = null)
	{
		$sql_query = array(
					'shift_status' => $shift_status,
				);
		DB::update(DRIVER)->set($sql_query)
				->where('driver_id', '=' ,$id)
				->execute();
	}
	// Update Driver Sattus
		//Set Driver Status in Active in DRIVER Table
	// Get City Name
	public function get_city_id($cityname)
	{
		$city_id ="";
		$model_query = "select city_id from ".CITY." where city_name like '%".$cityname."%' limit 0,1";	

		$model_fetch = Db::query(Database::SELECT, $model_query)
				->execute()
				->as_array();
		
		if(count($model_fetch) > 0)
		{
			$city_id = $model_fetch[0]['city_id'];
		}
		else
		{
			$model_query = "select city_id from ".CITY." where city.default='1' limit 0,1";	

			$model_fetch = Db::query(Database::SELECT, $model_query)
					->execute()
					->as_array();
			$city_id = $model_fetch[0]['city_id'];
		}
		return $city_id;
	}	

	public function get_passenger_details_phone($array,$company_id)
	{ 

		if($array['user_type'] == 'P')
		{
			if($company_id != '')
			{
				$sql = "SELECT * FROM ".PASSENGERS." WHERE phone = '".$array['phone_no']."' and passenger_cid='".$company_id."'"; 
			}
			else
			{
				$sql = "SELECT * FROM ".PASSENGERS." WHERE phone = '".$array['phone_no']."' and passenger_cid='0'"; 
			}
			
			//$sql = "SELECT * FROM ".PASSENGERS." WHERE phone = '".$array['phone_no']."' "; 
			//echo $sql;
			return Db::query(Database::SELECT, $sql)
				->execute()
				->as_array(); 	
		}
		else
		{
			$sql = "SELECT * FROM ".PEOPLE." WHERE phone = '".$array['phone_no']."'  and user_type='D' "; 
			return Db::query(Database::SELECT, $sql)
				->execute()
				->as_array(); 	
		}
	}

	public function get_sublogid($log_id)
	{
		 $sql = "select sub_logid from ".PASSENGERS_LOG." where passengers_log_id='$log_id'";
		 $result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
		if(count($result) > 0)
		{
			return $result[0]['sub_logid'];
		}
		else
		{
			return '0';
		}
	}	

	public function update_passengers($update_array,$update_id,$default_companyid)
	{

		if($default_companyid != '')		
		{
			$result=DB::update(PASSENGERS)->set($update_array)->where('id',"=",$update_id)->where('passenger_cid','=',$default_companyid)->execute();
		}
		else
		{
			$result=DB::update(PASSENGERS)->set($update_array)->where('id',"=",$update_id)->where('passenger_cid','=','0')->execute();
		}
		
		//$result=DB::update(PASSENGERS)->set($update_array)->where('id',"=",$update_id)->execute();

		return $result;
	}

	public function update_passengers_phone($update_array,$phone,$default_companyid)
	{
		//$result=DB::update(PASSENGERS)->set($update_array)->where('phone',"=",$phone)->where('passenger_cid','=',$default_companyid)->execute();

		$result=DB::update(PASSENGERS)->set($update_array)->where('phone',"=",$phone)->execute();
		return $result;
	}

	public function update_driver_phone($update_array,$id,$default_companyid)
	{
		if($default_companyid != '')
		{
			$result=DB::update(PEOPLE)->set($update_array)->where('id',"=",$id)->where('company_id','=',$default_companyid)->execute();
		}
		else
		{
			$result=DB::update(PEOPLE)->set($update_array)->where('id',"=",$id)->execute();
		}

		return $result;
	}
		//Get Driver Current Status if he is break,Avtive,Free
	public function get_driver_current_status($id,$company_id='')
	{

		$result = DB::select('*')->from(DRIVER)
			->where(DRIVER.'.driver_id','=',$id)				
			->order_by('id', 'ASC')					
			->as_object()		
			->execute();									

		//print_r($result);              
		return $result;	
			
	}

	public function get_driver_list($id)
	{
									
		$result = 
		DB::select(PEOPLE.'.id',PEOPLE.'.salutation',PEOPLE.'.name',DRIVER.'.latitude',DRIVER.'.longitude')->from(PEOPLE)
						->join(DRIVER)->on(PEOPLE.'.id','=',DRIVER.'.driver_id')
						->where(PEOPLE.'.user_type','=','D')
						->where(PEOPLE.'.login_status','=','S') //
						->where(PEOPLE.'.status','=','A')
						->where(DRIVER.'.status','!=','A')
						->where(DRIVER.'.shift_status','=','IN')
						->order_by(DRIVER.'.driver_id', 'desc')					
						->as_object()		
						->execute();									
		//print_r($result);              
		return $result;	
			
	}
	
	public function getTaxiforDriver($id,$company_id='')
	{

		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
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


		$company_condition = '';

		if($company_id !='')
		{
			$company_condition = " and mapping_companyid='$company_id'";
		}
		$query = "select mapping_taxiid from ".TAXIMAPPING." where mapping_status='A' and mapping_driverid='".$id."' $company_condition AND mapping_startdate <='$current_time' and mapping_enddate >= '$current_time' order by mapping_startdate DESC"; 
		
		//AND mapping_startdate <='$current_time' and mapping_enddate >= '$current_time'

 		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();		

		return $result;
	}

	public function get_assignedtaxi_list($driver_id='',$company_id='')
	{

		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
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


		$company_condition = '';

		if($company_id !='')
		{
			$company_condition = " and mapping_companyid='$company_id'";
		}
		
		$query = " select * from " . TAXIMAPPING . " left join " .TAXI. " on 
		".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id where mapping_driverid='$driver_id' $company_condition AND mapping_startdate <=  '".$end_time."' AND mapping_enddate >= '".$start_time."'  order by mapping_startdate ASC"; 
		
		//AND mapping_startdate <=  '".$end_time."' AND mapping_enddate >= '".$start_time."' 

 		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();		

		return $result;
	}
	
	//Get Company Current Package Details
	
	public function current_package_details($cid)
	{
		$array = array();

		$query = "SELECT people.id ,(select upgrade_packageid from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as upgrade_packageid,
		(select check_package_type from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as check_package_type,
		(select upgrade_expirydate from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM people WHERE user_type='C' and company_id ='$cid' group by people.id Having ( check_package_type = 'T' or upgrade_expirydate >=now() )";


		$result = Db::query(Database::SELECT, $query)
			 ->execute()
			 ->as_array();		

		if(count($result) > 0)
		{
			$package_query = "select driver_tracking from package where package_id = ".$result[0]['upgrade_packageid'];

			$package_result = Db::query(Database::SELECT, $package_query)
				 ->execute()
				 ->as_array();		
			return $package_result;

		}	
		else
		{
			return $array;
		}		

	}	


	public function get_driver_bylocation($lat,$long,$distance = NULL,$no_passengers,$bookingtime,$company_id='')
	{
		
		$assigned_driver = $this->free_availabletaxi_list($no_passengers,$bookingtime);
		//$this->currentdate = Commonfunction::getCurrentTimeStamp();

		$driver_list = '';
		$driver_count = '';
		foreach($assigned_driver as $key => $value)
		{
			$driver_count = 1;
			$driver_list .= "'".$value['id']."',";
		}	
		if($driver_count > 0)
		{
			$driver_list = substr_replace($driver_list ,"",-1);
		}
		else
		{
			$driver_list = "''";
		}

		$query = " DROP FUNCTION IF EXISTS CONV_MI_KM";

		$find_result = Database::instance()->query(NULL, $query);

		$query = "CREATE FUNCTION CONV_MI_KM (measurement INT,  base_type ENUM('m','k')) RETURNS FLOAT(65,4) DETERMINISTIC RETURN IF(base_type = 'm', measurement * 1.609344, IF(base_type = 'k', measurement * 0.62137, NULL))";

		Database::instance()->query(NULL, $query);

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

		$city_model_fare = $model_fetch[0]['city_model_fare'];

		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
		}	
		else
		{
			$model_base_query = "select time_zone from  company where cid='$company_id' "; 
			$model_fetch = Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array();			

			if($model_fetch[0]['time_zone'] != '')
			{
				$current_time = convert_timezone('now',$model_fetch[0]['time_zone']);
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



		 $query = "select list.name as name,list.driver_id as driver_id,list.phone as phone,list.id as id,list.latitude as latitude,list.longitude as longitude,list.status as status,list.distance as distance,(SELECT CONV_MI_KM(list.distance,'m')) as distance_km,comp.company_name as company_name,taxi.taxi_no as taxi_no,taxi.taxi_fare_km as taxi_fare,taxi.taxi_image as taxi_image,taxi.taxi_capacity as taxi_capacity,taxi.taxi_id as taxi_id from ( SELECT people.name,people.phone,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515) AS distance FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where driver.status='F' and driver_id IN ($driver_list) order by distance ) as list JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid` WHERE tmap.mapping_startdate <='$current_time' AND  tmap.mapping_enddate >='$current_time' AND tmap.`mapping_status`='A'  group by list.driver_id";

		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();			
		return $result;
		
	}

	//Driver logs for rejected trips used in Statistics
		//Function used to find the completed logs
	public function get_driver_logs_completed($id,$msg_status,$driver_reply=null,$travel_status=null,$company_id)
	{
		if($company_id != '')	
		{
			$result = DB::select('*')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					//->join(PEOPLE)->on(PASSENGERS_LOG.'.driver_id','=',PEOPLE.'.id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)					
					->order_by(PASSENGERS_LOG.'.passengers_log_id', 'desc')					
					->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
					->where(PASSENGERS_LOG.'.company_id','=',$company_id)
					->as_object()		
					->execute();									
		    //print_r($result);           
		return $result;	
		}
		else
		{
			$result = DB::select('*')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					//->join(PEOPLE)->on(PASSENGERS_LOG.'.driver_id','=',PEOPLE.'.id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
					->order_by(PASSENGERS_LOG.'.passengers_log_id', 'desc')					
					->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)					
					->as_object()		
					->execute();									
		    //print_r($result);           
			return $result;	
		}
	
	}
	
	//Function used to get total driven details
	public function get_time_driven($id,$msg_status,$driver_reply=null,$travel_status=null)
	{
		$date = date("Y-m-d");
		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $date.' 00:00:01';
		$end_time = $date.' 23:59:59';
		
		//$start_time = '2014-01-09 00:00:01';
		//$end_time = '2014-01-09 23:59:59';
		
		$condition="";
		$condition = "AND passengers_log.createdate >='".$start_time."' and passengers_log.createdate <= '".$end_time."'"; 
		
		$query = "SELECT actual_pickup_time,drop_time FROM `passengers_log` JOIN `passengers` ON (`passengers_log`.`passengers_id` = `passengers`.`id`) WHERE `passengers_log`.`driver_id` = '$id' AND `passengers_log`.`msg_status` = '$msg_status' AND `passengers_log`.`driver_reply` = '$driver_reply' AND `passengers_log`.`travel_status` = '$travel_status' $condition ORDER BY `passengers_log`.`passengers_log_id` DESC";
		//echo $query;// exit;
		$result =  Db::query(Database::SELECT, $query)
			->execute()
			->as_array();			
		if(count($result) > 0)
		{	$actual_pickup_time = '';
			$drop_time = '';
			$hours = '';
			$minutes = '';
			$seconds = '';
			$date_difference = "";
			$total_differnce = "";
			foreach($result as $get_details)
			{
				$actual_pickup_time = strtotime($get_details['actual_pickup_time']);
				$drop_time = strtotime($get_details['drop_time']);
				//echo $actual_pickup_time;
				//echo '-';
				//echo $drop_time;
				//echo '<br>';
				 $date_difference = abs($drop_time - $actual_pickup_time);
				 $total_differnce += $date_difference;
			}
				
				//$date_difference = $drop_time - $actual_pickup_time;
				$hours += floor((($total_differnce % 604800) % 86400) / 3600); 
				$minutes += floor(((($total_differnce % 604800) % 86400) % 3600) / 60); 
				$seconds += floor((((($total_differnce % 604800) % 86400) % 3600) % 60));
				$time_result = $minutes.':'.$seconds;
		}
		else
		{
			$time_result = "00:00";
		}
		return $time_result;
	}
	
	public function api_companystatus($user_id)
	{		
		$query= "SELECT * FROM ".PEOPLE." WHERE id='$user_id'";
		$result =  Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		if(count($result) > 0)
		{
			$company_id = $result[0]['company_id'];

			$query= "SELECT company_status FROM ".COMPANY." WHERE cid='$company_id'";
			$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
			if(count($result) > 0)
			{
				return $result[0]['company_status'];	
			}
			else
			{
				$result[0]['company_status'] = 'A';
				return $result[0]['company_status'];
			}

		}
		else
		{
			$result[0]['company_status'] = 'A';

			return $result[0]['company_status'];
		}	
	}
	/************************ TDispatch **************************/
	public function get_account_discount($aid)
	{
		//$user_createdby = $_SESSION['userid'];
		//select * from " . PASSENGERS . " where FIND_IN_SET (id,'$passenger_id') order by name asc
		$sql = " SELECT ".TBLGROUPACCOUNT.".limit,".TBLGROUPACCOUNT.".discount,".TBLGROUPACCOUNT.".passid FROM ".TBLGROUPACCOUNT." LEFT JOIN ".PASSENGERS." ON (".TBLGROUPACCOUNT.".passid = ".PASSENGERS.".id) WHERE `aid` = '$aid' and ".TBLGROUPACCOUNT.".status=1";
		
		$result =  Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
		//print_r($result);
		return $result;
	}
	public function get_groupdetails($groupid)
	{
			///$user_query = " select * from " . TBLGROUP . " where FIND_IN_SET ('$check_passid',passenger_id) and gid='$groupid'";
			$user_query = " select ".TBLGROUP.".limit,passenger_id from ".TBLGROUP." where gid='$groupid' and status = '1'";
			$user_result = Db::query(Database::SELECT, $user_query)
					->execute()
					->as_array();
			return $user_result;
	}
	public function check_used_limit($passengers_id)
	{
			$sql = "SELECT sum(".TRANS.".credits_used) as total_used_limit FROM  ".PASSENGERS_LOG." JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
			JOIN  ".TRANS." ON (  ".TRANS.".`passengers_log_id` =  ".PASSENGERS_LOG.".`passengers_log_id` ) 
			WHERE  ".PASSENGERS_LOG.".`passengers_id` =  '$passengers_id'";
			$user_result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			return $user_result;		
	}
	public function company_paypal_details($cid)
	{
		$sql = "SELECT * FROM ".COMPANYINFO." WHERE company_cid = '$cid'";

		$result =  Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

		return $result;

	}
	
	public function passenger_card_details_update($array_data,$passenger_userid)
	{
		
		$creditcard_no = encrypt_decrypt('encrypt',$array_data['creditcard_no']);
		//echo $cardno;
		//$split = explode('_',$cardno);
		//echo ENCRYPT_KEY;
		//print_r($split);
		//exit;
		//$creditcard_no = trim($split[1]);
		$creditcard_cvv = $array_data['creditcard_cvv'];
		$expdatemonth = $array_data['expdatemonth'];
		$expdateyear = $array_data['expdateyear'];
		
		$sql = "UPDATE `passengers` SET `creditcard_no` = '$creditcard_no', `creditcard_cvv` = '$creditcard_cvv', `expdatemonth` = '$expdatemonth', `expdateyear` = '$expdateyear' WHERE `id` = '$passenger_userid'";
		
		$result =  Db::query(Database::UPDATE, $sql)
		->execute();

		return $result;
	}	
	/**************************** Customer enhancement - Edited Senthil *************************/
	/**Passenger Signup**/
	public function add_p_account_details($val,$otp=null,$referral_code="",$devicetoken="",$deviceid="",$devicetype="",$company_id="") 
	{
		//$username = Html::chars($val['name']);
		$password = text::random($type = 'alnum', $length = 6);		
		$common_model = Model::factory('commonmodel');
		$activation_key = Commonfunction::admin_random_user_password_generator();
		if($company_id !='')
		{
			$current_time = $common_model->getcompany_all_currenttimestamp($company_id);
		}
		else
		{
			$current_time =	date('Y-m-d H:i:s');
		}
		/*$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $current_date[0].' 00:00:01';
		$end_time = $current_date[0].' 23:59:59';*/

		//$referral_code = text::random($type = 'alnum', $length = 6);

				/** Referrral key generator **/
				$referralcode_query = "select concat(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1)) as referral_code from passengers Having NOT EXISTS (select referral_code from passengers having referral_code=referral_code) limit 1";

			 	$referralcode_result = Db::query(Database::SELECT, $referralcode_query)
			 	->execute()			
				->as_array();

				if(count($referralcode_result) > 0)
				{
					$referral_code = $referralcode_result[0]['referral_code'];
				}
				else
				{
					$referralcode_query = "select concat(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1)) as referral_code";

				 	$referralcode_result = Db::query(Database::SELECT, $referralcode_query)
				 	->execute()			
					->as_array();
				

					$referral_code = $referralcode_result[0]['referral_code'];
				}
					/** Referrral key generator **/


		
		$fieldname_array = array('name','email','password','org_password','otp','phone','address','referral_code','creditcard_no','creditcard_cvv','expdatemonth','expdateyear','activation_key','activation_status','user_status','created_date','updated_date','passenger_cid');
		$values_array = array('',$val['email'],md5($val['password']),$val['password'],$otp,$val['phone'],'',$referral_code,'','','','','','0','I',$current_time,$current_time,$company_id);
		$result = DB::insert(PASSENGERS, $fieldname_array)
					->values($values_array)
					->execute();
		if($result){										
				$email = DB::select()->from(PASSENGERS)
					->where('email', '=', $val['email'])
					->execute()
					->as_array(); 		         
		if($devicetoken != "")
		{
			$update_array = array("device_token" => $devicetoken,"device_id" => $deviceid,"device_type" => $devicetype );
			
				$update_device_token_result = DB::update(PASSENGERS)
						->set($update_array)
						->where('email', '=', $val['email'])						
						->execute();				
		}
		return 1;
			
		}else{
				return 0;
		}
	}	
	/** Resend OTP **/
	public function update_otp($otp_array,$otp,$company_id='')
	{


		if($company_id !='')
		{
			$common_model = Model::factory('commonmodel');
			$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);
		}
		else
		{
			$current_datetime =	date('Y-m-d H:i:s');
		}


		if($otp_array['user_type']== 'P')
		{
			if($company_id !='')
			{
				$update_otp= DB::update(PASSENGERS)->set(array('otp' =>$otp ,'updated_date'=>$current_datetime))->where('email','=',$otp_array['email'])->where('passenger_cid','=',$company_id)->execute();	
			}
			else
			{

				$update_otp= DB::update(PASSENGERS)->set(array('otp' =>$otp ,'updated_date'=>$current_datetime))->where('email','=',$otp_array['email'])->where('passenger_cid','=',0)->execute();	

			}
		}
		else if($otp_array['user_type']== 'D')
		{
			$update_otp= DB::update(PEOPLE)->set(array('otp' =>$otp,'updated_date'=>$current_datetime))
			->where('email','=',$otp_array['email'])
			->execute();	
		}
		else
		{
			$update_otp = false;
		}

		if($update_otp)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}		
	//Check OTP 
	public function check_otp($otp=null,$email=null,$user_type=null,$company_id='')
	{
		try {		
			if($user_type == 'P')					
			{
				if($company_id !='')
				{
			 		$sql = "SELECT id FROM ".PASSENGERS." WHERE email='$email' and otp='$otp' and passenger_cid='$company_id'";   
				}
				else	
				{
			 		$sql = "SELECT id FROM ".PASSENGERS." WHERE email='$email' and otp='$otp' and passenger_cid='0'";   
				}
			}
			else
			{
			$sql = "SELECT id FROM ".PEOPLE." WHERE email='$email' and otp='$otp'";   
			}
			$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();
			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;	
			}										
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 2;			
		}		
	}
	public function check_otp_expire($otp=null,$email=null,$user_type=null,$company_id='')
	{
		try {		
			if($user_type == 'P')					
			{

				if($company_id !='')
				{
					 $sql = "SELECT id,email, otp, created_date,updated_date,DATE_ADD(  `updated_date` , INTERVAL 15 MINUTE ) AS otp_expiry FROM ".PASSENGERS." WHERE email='$email' and otp='$otp' and passenger_cid='$company_id'";   
				}
				else
				{
					 $sql = "SELECT id,email, otp, created_date,updated_date,DATE_ADD(  `updated_date` , INTERVAL 15 MINUTE ) AS otp_expiry FROM ".PASSENGERS." WHERE email='$email' and otp='$otp' and passenger_cid='0'";   
				}
			}
			else
			{
			$sql = "SELECT id,email, otp, created_date,updated_date,DATE_ADD(  `created_date` , INTERVAL 15 MINUTE ) AS otp_expiry FROM ".PEOPLE." WHERE email='$email' and otp='$otp' and user_type='D'";   
			}
			$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();
			if(count($result) > 0)
			{ 
				return $result;
			}
			else
			{
				return 0;	
			}										
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 2;			
		}		
	}
	//Check Referral code
	public function check_referral_code($referral_code=null,$company_id='')
	{
		try {		

			if($company_id !='')	
			{
				$sql = "SELECT id FROM ".PASSENGERS." WHERE referral_code='$referral_code' and passenger_cid='$company_id' ";   
			}
			else
			{
				$sql = "SELECT id FROM ".PASSENGERS." WHERE referral_code='$referral_code' and passenger_cid='0' ";   
			}
			$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return $result;				
			}
			else
			{
				return 0;	
			}										
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 1;			
		}		
	}
	//Check Referral code
	public function check_driver_referral_code($referral_code=null)
	{
		try {		

			$sql = "SELECT id FROM ".PEOPLE." WHERE driver_referral_code='$referral_code'";   
			$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return $result;				
			}
			else
			{
				return 0;	
			}										
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 1;			
		}		
	}
	//Save Passenger Personal Profile Data
	public function save_passenger_personaldata($array,$referred_passenger_id,$company_id='')
	{				
		//print_r($array);
		//exit;
		try {							
			$p_email = $array['email'];
			if($company_id !='')
			{
				$data = DB::select('*')->from(PASSENGERS)->where(PASSENGERS.'.email','=',$p_email)->where(PASSENGERS.'.passenger_cid','=',$company_id)->as_object()->execute();
			}
			else
			{
				$data = DB::select('*')->from(PASSENGERS)->where(PASSENGERS.'.email','=',$p_email)->where(PASSENGERS.'.passenger_cid','=',0)->as_object()->execute();
			}


			if(count($data)>0)
			{
				$passenger_id = $data[0]->id;
			}
			else
			{
				$passenger_id = '';
			}
			if($passenger_id != '')
			{
				$result = DB::update(PASSENGERS)
							->set($array)
							->where('email', '=', $array['email'])							
							->execute();	
                /*********************************************************/									
				$referer_data_count = DB::select('*')->from(PASSENGERS_REF_DETAILS)
										->where(PASSENGERS_REF_DETAILS.'.registered_passenger_id','=',$passenger_id)
										->as_object()		
										->execute();
				if(count($referer_data_count)==0)
				{
				$ref_result = DB::insert(PASSENGERS_REF_DETAILS, array('referred_passenger_id','registered_passenger_id','referral_status','referrer_earned','earned_amount'))
								->values(array($referred_passenger_id,$passenger_id,'1','0',''))
								->execute();	
				/****************************************/
				$refby_array = array("referred_by"=>$referred_passenger_id);
				$refby_result = DB::update(PASSENGERS)
							->set($refby_array)
							->where('email', '=', $p_email)							
							->execute();
				}				
				//print_r($ref_result);			
				/*****************************************/												
							return 0;	
			}
			else
			{
				return 1;
			}						
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 1;			
		}
	}
	//Save Passenger Card Data
	public function save_passenger_carddata($array,$default_companyid)
	{				
		//print_r($array);
		//echo $referred_passenger_id;
		//exit;
		try {							
			$p_email = $array['email'];
			if($default_companyid != "")
			{
				$data = DB::select('*')->from(PASSENGERS)
											->where(PASSENGERS.'.email','=',$p_email)
											->where('passenger_cid', '=', $default_companyid)
											->as_object()		
											->execute();
			}
			else
			{
				$data = DB::select('*')->from(PASSENGERS)
											->where(PASSENGERS.'.email','=',$p_email)
											->where('passenger_cid', '=', '0')
											->as_object()		
											->execute();				
			}


			if(count($data)>0)
			{
				$passenger_id = $data[0]->id;
			}
			else
			{
				$passenger_id = '';
			}
			
			if($passenger_id != '')
			{				
				$creditcard_no = $array['creditcard_no'];
				$creditcard_no = encrypt_decrypt('encrypt',$creditcard_no);
				$creditcard_cvv = $array['creditcard_cvv'];
				$expdatemonth = $array['expdatemonth'];
				$expdateyear = $array['expdateyear'];
				
				$sql = "SELECT passenger_cardid FROM ".PASSENGERS_CARD_DETAILS." WHERE passenger_id='$passenger_id' and creditcard_no = '$creditcard_no'";   
				$result=Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
				if(count($result) > 0)
				{ 
					return 2;
				}
				else
				{
					$card_result = DB::insert(PASSENGERS_CARD_DETAILS, array('passenger_id','passenger_email','card_type','creditcard_no','creditcard_cvv','expdatemonth','expdateyear','default_card'))
								->values(array($passenger_id,$p_email,'P',$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,'1'))
								->execute();											
					return 0;	
				}
			}
			else
			{
				return 1;
			}						
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 1;			
		}
	}
	
	// Check Whether Driver People Email is Already Exist or Not
	public function check_people_personal_data($userid="",$user_type="")
	{
		$sql = "SELECT id FROM ".PEOPLE." WHERE id='$userid' and user_type = '$user_type' and (name = '' or lastname = '')";   	

		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}	
	//check_people_taxi_data
	public function check_people_taxi_data($userid="")
	{
		$sql = "SELECT id FROM ".PEOPLE." WHERE id = '$userid' and company_id =0";   	
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}
	
	// Check Whether Passenger Personal Data is Already Exist or Not //check_passenger_card_data
	public function check_passenger_personal_data($userid="")
	{
		$sql = "SELECT id FROM ".PASSENGERS." WHERE id='$userid' and (name = '' or lastname = '')";   	

		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}	
	//Check Passenger Card Details
	public function check_passenger_card_data($userid="")
	{
		$sql = "SELECT passenger_cardid FROM ".PASSENGERS_CARD_DETAILS." WHERE passenger_id = '$userid'";   	
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return $result;
			}
			else
			{
				return 0;		
			}
	}
	// edit_check_email_passengers
	public function edit_check_email_passengers($email="",$passenger_id="",$company_id='')
	{
		if($company_id !='')
		{		
			$sql = "SELECT email FROM ".PASSENGERS." WHERE email='$email' and id!='$passenger_id' and passenger_cid='$company_id'";   
		}
		else
		{
			$sql = "SELECT email FROM ".PASSENGERS." WHERE email='$email' and id!='$passenger_id' and passenger_cid='0'";   
		}
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}

	// edit_check_email_passengers
	public function edit_check_email_people($email="",$driver_id="")
	{
		$sql = "SELECT email FROM ".PEOPLE." WHERE email='$email' and id!='$driver_id' and user_type='D'";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}

	//edit_check_phone_passengers
	public function edit_check_phone_passengers($phone="",$passenger_id='',$company_id='')
	{
		if($company_id !='')
		{		
			$sql = "SELECT email FROM ".PASSENGERS." WHERE phone='$phone' and id!='$passenger_id' and passenger_cid='$company_id'";   
		}
		else
		{
			$sql = "SELECT email FROM ".PASSENGERS." WHERE phone='$phone' and id!='$passenger_id' and passenger_cid='0'";   
		}  
		//echo $sql;
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}
	//edit_check_phone_passengers
	public function edit_check_phone_people($phone="",$driver_id='')
	{
		$sql = "SELECT phone FROM ".PEOPLE." WHERE phone='$phone' and id != '$driver_id' and user_type='D'";  
		//echo $sql;
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}	

	// Check Whether People phone is Already Exist or Not //
	public function check_email_people($email="",$user_type="")
	{
		$sql = "SELECT email FROM ".PEOPLE." WHERE email='$email' and user_type='$user_type'";   

		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}	
	//Edit Passenger Personal Profile Data
	public function edit_passenger_personaldata($array,$passenger_id,$company_id='')
	{				
		//print_r($array);
		//exit;
		try {							
				if($company_id !='')					
				{
					$result = DB::update(PASSENGERS)->set($array)->where('id', '=', $passenger_id)->where('passenger_cid', '=', $company_id)->execute();
					//return $result;

				}
				else
				{
					$result = DB::update(PASSENGERS)->set($array)->where('id', '=', $passenger_id)->where('passenger_cid', '=', 0)->execute();
					//return $result;
				}				
				return 0;							
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 1;			
		}
	}
	//Check card exist for passenger
	public function check_card_exist($creditcard_no="",$creditcard_cvv,$expdatemonth,$expdateyear,$passenger_id="")
	{
		$creditcard_no = encrypt_decrypt('encrypt',$creditcard_no);		
		$sql = "SELECT passenger_cardid FROM ".PASSENGERS_CARD_DETAILS." WHERE passenger_id='$passenger_id' and creditcard_no = '$creditcard_no'";     
		//   and creditcard_cvv = '$creditcard_cvv' and expdatemonth = '$expdatemonth' and expdateyear = '$expdateyear' and default_card = '$default'
		$result=Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}		
	}

	//Check card exist for passenger
	public function edit_check_card_exist($passenger_cardid,$creditcard_no="",$creditcard_cvv,$expdatemonth,$expdateyear,$passenger_id="",$default)
	{
		$creditcard_no = encrypt_decrypt('encrypt',$creditcard_no);		
		$sql = "SELECT passenger_cardid,default_card FROM ".PASSENGERS_CARD_DETAILS." WHERE passenger_id='$passenger_id' and creditcard_no = '$creditcard_no' and passenger_cardid != '$passenger_cardid' ";     
		//and creditcard_cvv = '$creditcard_cvv' and expdatemonth = '$expdatemonth' and expdateyear = '$expdateyear' and default_card = '$default'
		$result=Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			if(count($result) > 0)
			{ 
				$default_card = $result[0]['default_card'];
				if($default_card == $default)
				{
					return 2;
				}
				else
				{
					return 1;
				}				
			}
			else
			{
				return 0;		
			}		
	}
	
	//Check Favourite Place exist for passenger
	public function check_fav_place($passenger_id="",$favourite_place="",$d_favourite_place="")
	{
		if($d_favourite_place !="")
		{
			$sql = "SELECT p_favourite_id FROM ".PASSENGERS_FAV." WHERE passenger_id='$passenger_id' and p_favourite_place = '$favourite_place' and d_favourite_place = '$d_favourite_place'";  
		}
		else
		{
			$sql = "SELECT p_favourite_id FROM ".PASSENGERS_FAV." WHERE passenger_id='$passenger_id' and p_favourite_place = '$favourite_place'";
		}
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			return $result;	
	}
	//Check Trip exist for passenger
	public function check_trip_data($passenger_id="")
	{
		$sql = "SELECT passengers_log_id FROM ".PASSENGERS_LOG." WHERE passengers_log_id='$passenger_id'";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}		
	}		
	//Add Passenger Card Data
	public function add_passenger_carddata($array,$referred_passenger_id=null)
	{				
		//print_r($array);
		//echo $referred_passenger_id;
		//exit;
		try {							
			$p_email = $array['email'];			
			$passenger_id = $array['passenger_id'];
			$creditcard_no = $array['creditcard_no'];
			$creditcard_no = encrypt_decrypt('encrypt',$creditcard_no);			
			$creditcard_cvv = $array['creditcard_cvv'];
			$expdatemonth = $array['expdatemonth'];
			$expdateyear = $array['expdateyear'];
			$card_type = $array['card_type'];
			$default = $array['default'];
			if($default == 1)
			{
			$array = array("default_card" => '0');
			$result = DB::update(PASSENGERS_CARD_DETAILS)
					->set($array)
					->where('passenger_id', '=', $passenger_id)							
					->execute(); 
			$card_result = DB::insert(PASSENGERS_CARD_DETAILS, array('passenger_id','passenger_email','card_type','creditcard_no','creditcard_cvv','expdatemonth','expdateyear','default_card'))
							->values(array($passenger_id,$p_email,$card_type,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,"1"))
							->execute();
            $last_insert_id = mysql_insert_id();
            /*$array = array("default_card" => '0');
			$result = DB::update(PASSENGERS_CARD_DETAILS)
					->set($array)
					->where('passenger_cardid', '!=', $last_insert_id)							
					->execute();  */          								
			}
			else
			{
 
			$card_result = DB::insert(PASSENGERS_CARD_DETAILS, array('passenger_id','passenger_email','card_type','creditcard_no','creditcard_cvv','expdatemonth','expdateyear','default_card'))
							->values(array($passenger_id,$p_email,$card_type,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,"0"))
							->execute();				
			}			
			//print_r($card_result);														
			return 0;							
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 1;			
		}
	}	
	//edit Passenger Card Data
	public function edit_passenger_carddata($array)
	{				
		//print_r($array);
		//echo $referred_passenger_id;
		//exit;
		try {							
			//$p_email = $array['email'];			
			$passenger_cardid = $array['passenger_cardid'];
			$passenger_id = $array['passenger_id'];
			$creditcard_no = $array['creditcard_no'];
			$creditcard_no = encrypt_decrypt('encrypt',$creditcard_no);
			$creditcard_cvv = $array['creditcard_cvv'];
			$expdatemonth = $array['expdatemonth'];
			$expdateyear = $array['expdateyear'];
			$card_type = $array['card_type'];
			$default = $array['default'];
			if($default == 1)
			{
			$update_array = array("card_type" => $card_type,
			"creditcard_no"=>$creditcard_no,
			"creditcard_cvv"=>$creditcard_cvv,
			"expdatemonth"=>$expdatemonth,
			"expdateyear"=>$expdateyear,
			"default_card" => '1');
			//print_r($update_array);exit;
            $array = array("default_card" => '0');
			$result = DB::update(PASSENGERS_CARD_DETAILS)
					->set($array)
					->where('passenger_id', '=', $passenger_id)							
					->execute(); 
			$udate_result = DB::update(PASSENGERS_CARD_DETAILS)
					->set($update_array)
					->where('passenger_cardid', '=', $passenger_cardid)							
					->execute();							    								
			}
			else
			{
			$update_array = array("card_type" => $card_type,
			"creditcard_no"=>$creditcard_no,
			"creditcard_cvv"=>$creditcard_cvv,
			"expdatemonth"=>$expdatemonth,
			"expdateyear"=>$expdateyear);
			$udate_result = DB::update(PASSENGERS_CARD_DETAILS)
					->set($update_array)
					->where('passenger_cardid', '=', $passenger_cardid)							
					->execute();				
			}			
			//print_r($ref_result);														
			return 0;							
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 1;			
		}
	}
	/** Save favourite Trip**/
	public function save_favourite($passenger_id=null,$p_favourite_place=null,$p_fav_latitude=null,$p_fav_longtitute=null,$d_favourite_place=null,$d_fav_latitude=null,$d_fav_longtitute=null,$fav_comments=null)
	{
		$fav_result = DB::insert(PASSENGERS_FAV, array('passenger_id','p_favourite_place','p_fav_latitude','p_fav_longtitute','d_favourite_place','d_fav_latitude','d_fav_longtitute','fav_comments','status'))
							->values(array($passenger_id,$p_favourite_place,$p_fav_latitude,$p_fav_longtitute,$d_favourite_place,$d_fav_latitude,$d_fav_longtitute,$fav_comments,'A'))
							->execute();
		return $fav_result;
	}
	/** Get Favourite list **/
	public function get_favourite_list($passenger_id)
	{
		$sql = "SELECT * FROM ".PASSENGERS_FAV." WHERE `passenger_id` = '".$passenger_id."' and status='A'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	/** Get Favourite Details **/
	public function get_favourite_details($p_favourite_id)
	{
		$sql = "SELECT p_favourite_id,p_favourite_place,p_fav_latitude,p_fav_longtitute,d_favourite_place,d_fav_latitude,d_fav_longtitute,fav_comments FROM ".PASSENGERS_FAV." WHERE `p_favourite_id` = '".$p_favourite_id."'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}	
	
	//$favourite_id,$p_favourite_place,$p_fav_latitude,$p_fav_longtitute,$d_favourite_place,$d_fav_latitude,$d_fav_longtitute,$fav_comments
	public function edit_favourite($favourite_id=null,$p_favourite_place=null,$p_fav_latitude=null,$p_fav_longtitute=null,$d_favourite_place=null,$d_fav_latitude=null,$d_fav_longtitute=null,$fav_comments=null)
	{
		 $update_result =  DB::update(PASSENGERS_FAV)->set(array('p_favourite_place'=>$p_favourite_place,'p_fav_latitude'=>$p_fav_latitude,'p_fav_longtitute'=>$p_fav_longtitute,'d_favourite_place'=>$d_favourite_place,'d_fav_latitude'=>$d_fav_latitude,'d_fav_longtitute'=>$d_fav_longtitute,'fav_comments'=>$fav_comments))->where('p_favourite_id', '=' ,$favourite_id)->execute();
		return $update_result;
	}	
	//Passenger Completed Trips by Date wise
	public function get_passenger_trips_bydate($pagination,$booktype,$userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null,$date)
	{
		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $date.' 00:00:01';
		$end_time = $date.' 23:59:59';
		//echo $history_type;
		$condition="";

		$condition = "AND pg.createdate >='".$start_time."' and pg.createdate <= '".$end_time."'"; 

		if($booktype != 2)
		{
			$condition.= " AND passengers_log.bookingtype = '$booktype'";
		}		
		/*$company_condition="";
		if($company_id != ""){
			$company_condition = " AND pg.company_id = '$company_id'";
		}*/
		if($pagination == 1)
		{
			$orderby = "order by pg.passengers_log_id desc LIMIT $start,$limit";
		}
		else
		{
			$orderby = "order by pg.passengers_log_id desc";
		}		
		$sql = "SELECT pg.current_location as place,pg.pickup_time,t.fare,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername,(select photo from ".PEOPLE." where id=pg.driver_id) as driverimage,(select taxi_no from ".TAXI." where taxi_id=pg.taxi_id) as taxi_no,(select pay_mod_name from ".PAYMENT_MODULES." where pay_mod_id=t.payment_type) as payment_name,pg.passengers_log_id as trip_id,t.id as jobreferral,pg.createdate,(select count(*) from  ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition) as total_count  FROM ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition $orderby ";
//echo $sql.'<br>';
	return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	// Get Passenger trips by Fromdate anda to date
	public function get_passengertrips_byfrmdate($pagination,$booktype,$userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null,$date)
	{
		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $date.' 00:00:01';
		$end_time = $date.' 23:59:59';
		//echo $history_type;
		$condition="";

		$condition = "AND pg.createdate >='".$start_time."' and pg.createdate <= '".$end_time."'"; 

		if($booktype != 2)
		{
			$condition.= " AND passengers_log.bookingtype = '$booktype'";
		}		

		if($pagination == 1)
		{
			$orderby = "order by pg.passengers_log_id desc LIMIT $start,$limit";
		}
		else
		{
			$orderby = "order by pg.passengers_log_id desc";
		}		
		$sql = "SELECT pg.current_location as place,pg.pickup_time,t.fare,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername,(select photo from ".PEOPLE." where id=pg.driver_id) as driverimage,(select taxi_no from ".TAXI." where taxi_id=pg.taxi_id) as taxi_no,(select pay_mod_name from ".PAYMENT_MODULES." where pay_mod_id=t.payment_type) as payment_name,pg.passengers_log_id as trip_id,t.id as jobreferral,pg.createdate,(select count(*) from  ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition) as total_count  FROM ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition $orderby ";
//echo $sql.'<br>';

	return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	//Passenger Completed Trips by Month wise
	public function get_passenger_trips_bymonth($booktype,$userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null,$month,$year)
	{
		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		//$start_time = $date.' 00:00:01';
		//$end_time = $date.' 23:59:59';
		//echo $history_type;
		$condition="";
		
		$condition = $condition = "and MONTH( pg.createdate ) = $month AND YEAR( pg.createdate) = $year";
		
		if($booktype != 2)
		{
			$condition.= "AND pg.bookingtype = '$booktype'";
		}
		/*$company_condition="";
		if($company_id != ""){
			$company_condition = " AND pg.company_id = '$company_id'";
		}*/
		//select * from thetable where monthname(date_field) = 'February'
		
		$sql = "SELECT pg.current_location as place,pg.pickup_time,t.fare,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername,(select photo from ".PEOPLE." where id=pg.driver_id) as driverimage,(select taxi_no from ".TAXI." where taxi_id=pg.taxi_id) as taxi_no,(select pay_mod_name from ".PAYMENT_MODULES." where pay_mod_id=t.payment_type) as payment_name,pg.passengers_log_id as trip_id,t.id as jobreferral,pg.createdate FROM ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status'  AND pg.driver_reply = '$driver_reply' $condition  group by pg.createdate ";
		//echo $sql;
		//LIMIT $start,$limit
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}	
	/*********************************************************************************************/
	/****************************  Driver enhancements - Edited by sakthivel ********************/
	/*************************** Driver Registration ********************************/
	/**Driver Account Signup**/
	public function add_d_account_details($val,$otp=null,$referral_code,$devicetoken="",$deviceid="",$devicetype="") 
	{
		//$username = Html::chars($val['name']);
		$password = text::random($type = 'alnum', $length = 6);		
		$activation_key = Commonfunction::admin_random_user_password_generator();

		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $current_date[0].' 00:00:01';
		$end_time = $current_date[0].' 23:59:59';
		
		$fieldname_array = 
		array('name','email','password','org_password','otp','phone','driver_referral_code','user_type','status','created_date','updated_date','company_id','booking_limit');
		$values_array = 
		array('',$val['email'],md5($val['password']),$val['password'],$otp,$val['phone'],$referral_code,'D','D',$current_time,$current_time,'','100');
		$result = DB::insert(PEOPLE, $fieldname_array)
					->values($values_array)
					->execute();
		if($result){										
				$email = DB::select()->from(PEOPLE)
					->where('email', '=', $val['email'])
					->execute()
					->as_array(); 		         
		if($devicetoken != "")
		{
			$update_array = array("device_token" => $devicetoken,"device_id" => $deviceid,"device_type" => $devicetype );
			
				$update_device_token_result = DB::update(PEOPLE)
						->set($update_array)
						->where('email', '=', $val['email'])						
						->execute();				
		}
		return 1;
			
		}else{
				return 0;
		}
	}
	// ** Add Taxi Details **//
	//&email=senthilkumar.a@ndot.in&company_email=&tvdlno=&taxi_company=&taxi_model=&referral_code=&bank_name=&bank_account_no=
	public function add_taxi_details($array,$referred_driver_id=null)
	{
			try {							

					if(TIMEZONE)
					{
						$current_time = convert_timezone('now',TIMEZONE);
						$current_date = explode(' ',$current_time);
						$start_time = $current_time;//$current_date[0].' 00:00:01';
						
						$end_time = $current_date[0];
						$edate = strtotime($end_time); 
						$new_date = strtotime('+ 1 year', $edate); 
						$end_time = date('Y-m-d', $new_date).' 23:59:59'; 
						$date = $current_date[0].' %';
						//$end = date('Y-m-d', strtotime('+5 years'));
					}
					else
					{
						$current_time =	date('Y-m-d H:i:s');
						$start_time = $current_time;//date('Y-m-d').' 00:00:01';
						$end_time = date('Y-m-d', strtotime('+1 years')).' 23:59:59';
						$date = date('Y-m-d %');
					}
					//echo $start_time;
					//echo $end_time;
					//echo $end_time = date('Y-m-d', strtotime('+1 years')).' 23:59:59';
					//exit;
			$d_email = $array['email'];
			$data = DB::select('*')->from(PEOPLE)
										->where(PEOPLE.'.email','=',$d_email)
										->where(PEOPLE.'.user_type','=','D')
										->as_object()		
										->execute();
			if(count($data)>0)
			{
				$driver_id = $data[0]->id;
				$cname = $data[0]->name;				
			}
			else
			{
				$driver_id = '';
				$cname = '';
			}
			//echo $driver_id;exit;
			$company_email = $array['email'];
			$tvdlno = $array['tvdlno'];
			$plate_no = $array['plate_no'];
			$company_name = $array['taxi_company'];
			$taxi_model = $array['taxi_model'];
			$bank_name = $array['bank_name'];
			$bank_account_no = $array['bank_account_no'];
			$company_owner_name = $array['taxi_company'];
			$comp_password = text::random($type = 'alnum', $length = 5);			
			/** Insert Owner Details *******/
			$owner_result = DB::insert(PEOPLE, array('name','email','password','org_password','user_type','login_country','login_state','login_city','status'))
							->values(array($company_owner_name,$company_email,md5($comp_password),$comp_password,'C',DEFAULT_COUNTRY,DEFAULT_STATE,DEFAULT_CITY,'A'))
							->execute();
            $user_id = mysql_insert_id();								
			/** Insert Company Details *******/
			$comp_result = DB::insert(COMPANY, array('company_name','bankname','bankaccount_no','time_zone','userid','company_country','company_state','company_city','company_status'))
							->values(array($company_name,$bank_name,$bank_account_no,TIMEZONE,$user_id,DEFAULT_COUNTRY,DEFAULT_STATE,DEFAULT_CITY,'A'))
							->execute();	
            $company_id = mysql_insert_id();	
            
            if($company_id != "")
            {
				/** Insert Company Package *************/
				$package_name = 'Unlimited';$no_of_taxi = $no_of_driver=1;
				$days = 30;$amount=0; $package_typ='T';				
				$get_packagedetails = $this->payment_packagedetails(1);
				if(count($get_packagedetails)>0)
				{
					$package_name = $get_packagedetails[0]['package_name']; 
					$no_of_taxi = $get_packagedetails[0]['no_of_taxi']; 
					$no_of_driver = $get_packagedetails[0]['no_of_driver']; 
					$days = $get_packagedetails[0]['days_expire'];   
					$amount = $get_packagedetails[0]['package_price'];   
					$package_type = $get_packagedetails[0]['package_type'];   
				}
				$userid = $user_id ;			
				// Convert Time				
				$expirydate = $current_time;
				$result = DB::insert(PACKAGE_REPORT, array('upgrade_companyid','upgrade_packageid','upgrade_packagename','upgrade_no_taxi','upgrade_no_driver','upgrade_expirydate','upgrade_ack','upgrade_capture','upgrade_amount','upgrade_type','upgrade_by','check_expirydate','check_package_type'))->values(array($company_id,'1',$package_name,$no_of_taxi,$no_of_driver,$expirydate,'Success','1',$amount,'D',$userid,$expirydate,$package_type))->execute();								
				/** Insert Company Info Details *******/
				$comp_info_result = DB::insert(COMPANYINFO, 
				array('company_cid','cancellation_fare','company_currency','company_currency_format','company_time_zone'))
								->values(array($company_id,'0',CURRENCY,CURRENCY_FORMAT,TIMEZONE))
								->execute();	
				$company_info_id = mysql_insert_id();
				/** Insert Taxi Details *******/
				$taxi_type = '1';
				$taxi_result = DB::insert(TAXI, array('taxi_no','taxi_type','taxi_model','taxi_company','taxi_country','taxi_state','taxi_city'))
								->values(array($plate_no,$taxi_type,$taxi_model,$company_id,DEFAULT_COUNTRY,DEFAULT_STATE,DEFAULT_CITY))
								->execute();
				$taxi_id = mysql_insert_id();
				/*** Insert Model fare for the company ***********/
				$adminmodeldata = DB::select('*')->from(MOTORMODEL)
						->where(MOTORMODEL.'.model_id','=',$taxi_model)
						->execute()
						->as_array();
				if(count($adminmodeldata)>0)
				{
					foreach($adminmodeldata as $values)
					{
						$model_id = $values['model_id'];
						$motor_mid = $values['motor_mid'];
						$base_fare = $values['base_fare'];
						$min_km = $values['min_km'];
						$min_fare = $values['min_fare'];
						$cancellation_fare = $values['cancellation_fare'];
						$below_above_km = $values['below_above_km'];
						$below_km = $values['below_km'];
						$above_km = $values['above_km'];
						$night_charge = $values['night_charge'];
						$night_timing_from = $values['night_timing_from'];
						$night_timing_to = $values['night_timing_to'];
						$night_fare = $values['night_fare'];
						$waiting_time = $values['waiting_time'];
					}
					$result = DB::insert(COMPANY_MODEL_FARE, array('model_id','company_cid','motor_mid','base_fare','min_fare','cancellation_fare','below_km','above_km','night_charge',
	'night_timing_from','night_timing_to','night_fare','min_km','below_above_km','waiting_time'))
						->values(array($model_id,$company_id,$motor_mid,$base_fare,$min_fare,$cancellation_fare,$below_km,$above_km,$night_charge,$night_timing_from,$night_timing_to,$night_fare,$min_km,$below_above_km,$waiting_time))
						->execute();
				}
				/** Insert Taxi Mappning Details *******/
				$taxi_mapping_result = DB::insert(TAXIMAPPING, array('mapping_driverid','mapping_taxiid','mapping_companyid','mapping_countryid','mapping_stateid','mapping_cityid','mapping_startdate','mapping_enddate','mapping_createdby'))
								->values(array($driver_id,$taxi_id,$company_id,DEFAULT_COUNTRY,DEFAULT_STATE,DEFAULT_CITY,$start_time,$end_time,1))
								->execute();
				/** Insert Taxi Additional Details *******/
				$taxi_additional_result = DB::insert(ADDFIELD, array('taxi_id'))
								->values(array($taxi_id))
								->execute();										
				/**********  Insert Driver status in DRIVER table************/
				$driver_status_result = DB::insert(DRIVER, array('driver_id','status','shift_status'))
								->values(array($driver_id,'F','OUT'))
								->execute();			
				/**************************************************************/
				$d_update_array = array("company_id"=>$company_id,"driver_license_id"=>$tvdlno);			
				$update_driver_company_result = DB::update(PEOPLE)
							->set($d_update_array)
							->where('email', '=', $d_email)	
							->where(PEOPLE.'.user_type','=','D')					
							->execute();
				 $update_owner_company_result = DB::update(PEOPLE)
							->set($d_update_array)
							->where('email', '=', $company_email)	
							->where('user_type', '=' ,'C')					
							->execute();							
				//print_r($ref_result);														
				return 1;	
			}
			else
			{
				return 2;
			}	
		} 
		catch (Kohana_Exception $e) {
			//print_r($e);
			return -1;			
		}
	}	
	//Save Driver Personal Profile Data
	public function save_driver_personaldata($array)
	{				
		//print_r($array);
		//exit;
		try {							
			$d_email = $array['email'];
			$data = DB::select('*')->from(PEOPLE)
										->where(PEOPLE.'.email','=',$d_email)
										->where(PEOPLE.'.user_type','=','D')
										->as_object()		
										->execute();
			if(count($data)>0)
			{
				$driver_id = $data[0]->id;
			}
			else
			{
				$driver_id = '';
			}
			$result = DB::update(PEOPLE)
							->set($array)
							->where('email', '=', $array['email'])							
							->execute();	
       		/** Insert Referral Details ******
			if($referred_driver_id != "")
			{
			$ref_result = DB::insert(DRIVER_REF_DETAILS, array('referred_driver_id','registered_driver_id','referral_status'))
							->values(array($referred_driver_id,$driver_id,'1'))
							->execute();
			}	
			*/												
							return 0;							
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 1;			
		}
	}
	public function check_tvdlno_people($tvdlno="")
	{
		$sql = "SELECT driver_license_id FROM ".PEOPLE." WHERE driver_license_id='$tvdlno'";   		

		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}
			public function check_plateno_people($tvdlno="")
	{
		$sql = "SELECT taxi_no FROM ".TAXI." WHERE taxi_no='$tvdlno'";   

		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}
	// //Check tvdl no already exist 
	public function check_owner_list($email="",$user_type="")
	{
		$sql = "SELECT id FROM ".PEOPLE." JOIN  ".COMPANY." ON (".COMPANY.".cid = ".PEOPLE.".company_id) WHERE ".PEOPLE.".email='$email' and user_type='$user_type'";   

		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}	
	//Driver Profile
	public function driver_profile_by_email($email) 
	{		
		$query= "SELECT id,salutation,name,lastname,email,address,password,org_password,otp,photo,device_type,device_token,login_status,user_type,driver_referral_code,notification_setting,company_id,driver_license_id,profile_picture FROM ".PEOPLE." WHERE email = '$email' AND user_type = 'D' ";		
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		if(count($result)>0)
		{
		return $result;	
		}
		else
		{
			return 1;
		}
	
	}		 
	// Function for get driver sales by week
	public function get_driver_sales_by_week($summary,$pagination,$booktype,$id,$msg_status,$driver_reply=null,$travel_status=null,$start=null,$limit=null,$startdate,$enddate)
	{
		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $startdate.' 00:00:01';
		$end_time = $enddate.' 23:59:59';
		$condition ="";
		$condition = " AND passengers_log.createdate >='".$start_time."' and passengers_log.createdate <= '".$end_time."'"; 									
		if($booktype == 2)
		{
			$condition.= " AND passengers_log.booking_from = '$booktype'";
		}
		else
		{
			$condition.= " AND passengers_log.booking_from != 2 ";
		}
		$selection="";
		if($summary == 1)
		{
			$selection = "ROUND(sum(`transacation`.`amt`),2) as total";			
		}
		else
		{
			$selection = "`passengers_log`.`pickup_time`, `passengers_log`.`passengers_log_id`, `passengers_log`.`current_location` AS `pickup_location`, `passengers_log`.`drop_location`, `passengers_log`.`rating`, IFNULL(ROUND(`transacation`.`amt`,2),0) as amt";			
		}
		if($pagination == 1)
		{
			$orderby = "ORDER BY `passengers_log`.`passengers_log_id` DESC LIMIT $start,$limit";
		}
		else
		{
			$orderby = "ORDER BY `passengers_log`.`passengers_log_id` DESC";
		}
			$sql = "SELECT $selection FROM `passengers_log` LEFT JOIN `transacation` ON (`passengers_log`.`passengers_log_id` = `transacation`.`passengers_log_id`) WHERE `passengers_log`.`driver_id` = '$id' AND `passengers_log`.`msg_status` = '$msg_status' AND `passengers_log`.`driver_reply` = '$driver_reply' AND `passengers_log`.`travel_status` = '$travel_status' $condition ORDER BY `passengers_log`.`passengers_log_id` DESC";	
					//echo $sql;echo '<br>';echo '<br>';
			$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();		
		
		
		 // print_r($result);           exit;
		return $result;	
	}	
	
	//******* Get Driver Expense by Week *********************************/
	public function get_driver_expense_by_week($summary,$pagination,$expense_type_id="",$driver_id,$start,$limit,$startdate,$enddate)
	{
		try {
			$current_time = convert_timezone('now',TIMEZONE);
			$current_date = explode(' ',$current_time);
			$start_time = $startdate.' 00:00:01';
			$end_time = $enddate.' 23:59:59';
			$condition ="";
			$condition = " AND ".DRIVER_EXP.".expense_date >='".$start_time."' and ".DRIVER_EXP.".expense_date <= '".$end_time."'";								

			if($expense_type_id != "")
			{
				$condition .= " AND ".DRIVER_EXP.".expense_type_id ='".$expense_type_id."'";	
			}
			
			$selection="";
			if($summary == 1)
			{
				$selection = "ROUND(sum(".DRIVER_EXP.".`expense_amount`),2) as total_amt,".DRIVER_EXP.".`expense_date`,".DRIVER_EXP.".expense_type_id";
			}
			else
			{
				$selection = "".DRIVER_EXP.".expense_id,".DRIVER_EXP.".fromdate,".DRIVER_EXP.".todate,".DRIVER_EXP.".`expense_date`, ".DRIVER_EXP.".`expense_type_id`, ".DRIVER_EXP.".`expense_amount`,".DRIVER_EXP.".`recurrent`,".DRIVER_EXP.".`notes`";
			}
			if($pagination == 1)
			{
				$orderby = "ORDER BY ".DRIVER_EXP.".`expense_id` DESC LIMIT $start,$limit";
			}
			else
			{
				$orderby = "ORDER BY ".DRIVER_EXP.".`expense_id` DESC";
			}
				$sql = "SELECT $selection FROM ".DRIVER_EXP." LEFT JOIN ".EXP_TYPE." ON (".EXP_TYPE.".`expense_type_id` = ".DRIVER_EXP.".`expense_type_id`) WHERE ".DRIVER_EXP.".`expense_driver_id` = '$driver_id' $condition $orderby";	
				//echo $sql;echo '<br>';echo '<br>';
				$result = Db::query(Database::SELECT, $sql)					
						->as_object()		
						->execute();	
			return $result;	
		}
		catch (Kohana_Exception $e) {
			//print_r($e);
			return -1;		
		}	
	}
	
	//Function used to get all driver logs with transactions
	public function get_driver_sales_by_month($summary,$booktype,$id,$msg_status,$driver_reply=null,$travel_status=null,$start=null,$limit=null,$month,$year)
	{
		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		//$start_time = $date.' 00:00:01';
		//$end_time = $date.' 23:59:59';
		$condition ="";
		$condition = " and MONTH( passengers_log.createdate ) = '$month' AND YEAR( passengers_log.createdate) = '$year'";
		if($booktype == 2)
		{
			$condition.= " AND passengers_log.booking_from = '$booktype'";
		}
		else
		{
			$condition.= " AND passengers_log.booking_from != 2 ";
		}
		$selection="";
		if($summary == 1)
		{
			$selection = "ROUND(sum(`transacation`.`amt`),2) as total";
			$orderby = "ORDER BY `passengers_log`.`passengers_log_id` DESC";
		}
		else
		{
			$selection = "`passengers_log`.`pickup_time`, `passengers_log`.`passengers_log_id`, `passengers_log`.`current_location` AS `pickup_location`, `passengers_log`.`drop_location`, `passengers_log`.`rating`, IFNULL(ROUND(`transacation`.`amt`,2),0) as amt";
			$orderby = "ORDER BY `passengers_log`.`passengers_log_id` DESC LIMIT $start,$limit";
		}
		//LEFT JOIN `passengers` ON (`passengers_log`.`passengers_id` = `passengers`.`id`)
		$sql = "SELECT $selection FROM `passengers_log`  LEFT JOIN `transacation` ON (`passengers_log`.`passengers_log_id` = `transacation`.`passengers_log_id`) WHERE `passengers_log`.`driver_id` = '$id' AND `passengers_log`.`msg_status` = '$msg_status' AND `passengers_log`.`driver_reply` = '$driver_reply' AND `passengers_log`.`travel_status` = '$travel_status' $condition $orderby";


			$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;	
						//$condition = "AND pg.createdate >='".$start_time."' and pg.createdate <= '".$end_time."'"; 									
		   //print_r($result);           exit;
		return $result;	
	}	
	
	//******* Get Driver Expense by Month *********************************/
	public function get_driver_expense_by_month($summary,$expense_type_id,$driver_id,$start,$limit,$month,$year)
	{
		try {
			$current_time = convert_timezone('now',TIMEZONE);
			$current_date = explode(' ',$current_time);
			//$start_time = $date.' 00:00:01';
			//$end_time = $date.' 23:59:59';
			$condition ="";
			$condition = " and MONTH( ".DRIVER_EXP.".expense_date ) = $month AND YEAR( ".DRIVER_EXP.".expense_date) = $year";			

			if($expense_type_id != "")
			{
				$condition .= " AND ".DRIVER_EXP.".expense_type_id ='".$expense_type_id."'";	
			}
			
			$selection="";
			$selection = "".DRIVER_EXP.".expense_id,".DRIVER_EXP.".fromdate,".DRIVER_EXP.".todate,".DRIVER_EXP.".`expense_date`, ".DRIVER_EXP.".`expense_type_id`, ".DRIVER_EXP.".`expense_amount`,".DRIVER_EXP.".`recurrent`,".DRIVER_EXP.".`notes`";
			if($summary == 1)
			{
				$orderby = "ORDER BY ".DRIVER_EXP.".`expense_id`";
			}
			else
			{
				$orderby = "ORDER BY ".DRIVER_EXP.".`expense_id` DESC LIMIT $start,$limit";
			}
				$sql = "SELECT $selection FROM ".DRIVER_EXP." LEFT JOIN ".EXP_TYPE." ON (".EXP_TYPE.".`expense_type_id` = ".DRIVER_EXP.".`expense_type_id`) WHERE ".DRIVER_EXP.".`expense_driver_id` = '$driver_id' $condition $orderby";
						//echo $sql;
				$result = Db::query(Database::SELECT, $sql)					
						->as_object()		
						->execute();	
			return $result;	
		}
		catch (Kohana_Exception $e) {
			//print_r($e);
			return -1;		
		}	
	}
	//Function used to get all driver logs with transactions
	public function get_driver_sales_by_date($summary,$pagination,$booktype,$id,$msg_status,$driver_reply=null,$travel_status=null,$start=null,$limit=null,$date)
	{
		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $date.' 00:00:01';
		$end_time = $date.' 23:59:59';
		$condition ="";
		$condition = " AND passengers_log.createdate >='".$start_time."' and passengers_log.createdate <= '".$end_time."'"; 									
		if($booktype == 2)
		{
			$condition.= " AND passengers_log.booking_from = '$booktype'";
		}
		else
		{
			$condition.= " AND passengers_log.booking_from != 2 ";
		}
		$selection="";
		if($summary == 1)
		{
			$selection = "ROUND(sum(`transacation`.`amt`),2) as total";
			
		}
		else
		{
			$selection = "`passengers_log`.`pickup_time`, `passengers_log`.`passengers_log_id`, `passengers_log`.`current_location` AS `pickup_location`, `passengers_log`.`drop_location`, `passengers_log`.`rating`, IFNULL(ROUND(`transacation`.`amt`,2),0) as amt";
			
		}
		if($pagination == 1)
		{
			$orderby = "ORDER BY `passengers_log`.`passengers_log_id` DESC LIMIT $start,$limit";
		}
		else
		{
			$orderby = "ORDER BY `passengers_log`.`passengers_log_id` DESC";
		}
			$sql = "SELECT $selection FROM `passengers_log` LEFT JOIN `transacation` ON (`passengers_log`.`passengers_log_id` = `transacation`.`passengers_log_id`) WHERE `passengers_log`.`driver_id` = '$id' AND `passengers_log`.`msg_status` = '$msg_status' AND `passengers_log`.`driver_reply` = '$driver_reply' AND `passengers_log`.`travel_status` = '$travel_status' $condition $orderby";	
			//echo $sql;echo '<br>';echo '<br>';
			$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();		
		
		
		 // print_r($result);           exit;
		return $result;	
	}
	
	//******* Get Driver Expense by Date *********************************/
	public function get_driver_expense_by_date($summary,$pagination,$expense_type_id="",$driver_id,$start,$limit,$date)
	{
		try {
			$current_time = convert_timezone('now',TIMEZONE);
			$current_date = explode(' ',$current_time);
			$start_time = $date.' 00:00:01';
			$end_time = $date.' 23:59:59';
			$condition ="";
			$condition = " AND ".DRIVER_EXP.".expense_date >='".$start_time."' and ".DRIVER_EXP.".expense_date <= '".$end_time."'";
			if($expense_type_id != "")
			{
				$condition .= " AND ".DRIVER_EXP.".expense_type_id ='".$expense_type_id."'";	
			}									

			$selection="";
			if($summary == 1)
			{
				$selection = "ROUND(sum(".DRIVER_EXP.".`expense_amount`),2) as total_amt,DAYNAME(".DRIVER_EXP.".`expense_date`) as expense_day,".DRIVER_EXP.".`expense_date`";
			}
			else
			{
				$selection = "".DRIVER_EXP.".expense_id,".DRIVER_EXP.".fromdate,".DRIVER_EXP.".todate,".DRIVER_EXP.".`expense_date`, ".DRIVER_EXP.".`expense_type_id`, ROUND(".DRIVER_EXP.".`expense_amount`,2) as expense_amount ,".DRIVER_EXP.".`recurrent`,".DRIVER_EXP.".`notes`";
			}
			if($pagination == 1)
			{
				$orderby = "ORDER BY ".DRIVER_EXP.".`expense_id` DESC LIMIT $start,$limit";
			}
			else
			{
				$orderby = "ORDER BY ".DRIVER_EXP.".`expense_id` DESC";
			}
				$sql = "SELECT $selection FROM ".DRIVER_EXP." LEFT JOIN ".EXP_TYPE." ON (".EXP_TYPE.".`expense_type_id` = ".DRIVER_EXP.".`expense_type_id`) WHERE ".DRIVER_EXP.".`expense_driver_id` = '$driver_id' $condition $orderby";	
						//echo $sql;echo '<br>';
				$result = Db::query(Database::SELECT, $sql)					
						->as_object()		
						->execute();	
			return $result;	
		}
		catch (Kohana_Exception $e) {
			//print_r($e);
			return -1;		
		}	
	}
	public function get_expensetype_list()
		{	 
		 $sql = "select expense_type_id,expense_name from ".EXP_TYPE."";
		 $result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();					             
			return $result;				
		}
		//******* Get Driver Expense by Date *********************************/
	//driver_id=16&pickup_location=gandhipuram,coimbatore&drop_location=marudhamalai,coimbatore&distance=10&fare=100.32&tips=10.00&travel_date=2012-12-26 15:16:00
	public function save_streetpickup($street_array)
	{
		try {	
					$driver_id= $street_array['driver_id'];
					$pickup_location = $street_array['pickup_location'];
					$drop_location = $street_array['drop_location'];
					$distance = $street_array['distance'];
					$fare = round($street_array['fare'],2);
					$tips = round($street_array['tips'],2);
					$travel_date = $street_array['travel_date'];
					$taxi_id = $company_id = "";
					$gettaxidetails = $this->get_assignedtaxi_list($driver_id);
					if(count($gettaxidetails)>0)
					{
						$taxi_id = $gettaxidetails[0]['taxi_id'];
						$company_id = $gettaxidetails[0]['mapping_companyid'];
					}
					/************** Insert into pass log table ***************/
					$log_result = DB::insert(PASSENGERS_LOG, array('driver_id','taxi_id','company_id','current_location','drop_location','pickup_time','bookingtype','travel_status','driver_reply','msg_status','createdate','booking_from'))
							->values(array($driver_id,$taxi_id,$company_id,$pickup_location,$drop_location,$travel_date,'1','1','A','R',$travel_date,'2'))
							->execute();
							//print_r($log_result);
					$passengers_log_id = mysql_insert_id();
					$amount  = $fare + $tips;
					/*********** Insert into Transaction table *****************/
					$siteinfo_details = $this->siteinfo_details(); 
					$Commonmodel = Model::factory('Commonmodel');	
					$update_commission = $Commonmodel->update_commission($passengers_log_id,$amount,$siteinfo_details[0]['admin_commission']);
					
					$trans_result = DB::insert(TRANS, array('passengers_log_id','distance','fare','tips','payment_type','amt','trans_packtype','admin_amount','company_amount'))
							->values(array($passengers_log_id,$distance,$fare,$tips,'1',$amount,$update_commission['trans_packtype'],$update_commission['admin_commission'],$update_commission['company_commission']))
							->execute();
					/******************************************************************/		
		return 1;
		}
			catch (Kohana_Exception $e) {
			//print_r($e);
			return -1;			
		}
	}
	
	//save_expense 
	public function save_expense($add_exp_array)
	{
		try {
					$expense_driver_id= $add_exp_array['expense_driver_id'];
					$expense_amount = $add_exp_array['expense_amount'];
					$expense_type_id = $add_exp_array['expense_type_id'];
					
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);					
					
					$expense_date = $add_exp_array['expense_date'].' '.$current_date[1];
					//echo $expense_date;exit;
					$recurring = $add_exp_array['recurring'];
					$notes = $add_exp_array['notes'];
					$fromdate = $add_exp_array['fromdate'];
					$todate = $add_exp_array['todate'];			
					$payment_type = "Cash";							
					if($recurring == 0)
					{					
						$exp_result = DB::insert(DRIVER_EXP, array('expense_driver_id','expense_amount','expense_type_id','payment_type','recurrent','notes','expense_date'))
							->values(array($expense_driver_id,$expense_amount,$expense_type_id,$payment_type,$recurring,$notes,$expense_date))
							->execute();
					}
					else
					{
						for($i = strtotime($fromdate); $i <= strtotime($todate); $i = strtotime('+1 Day', $i))
						{
						    //echo date('l Y-m-d', $i);
						    
						    $expense_date = date('Y-m-d', $i);
						    $check_date = date('d', $i);	
						    $check_month = date('m', $i);	
						    $check_year = date('Y', $i);		
						    $fromdate = date('Y-m-d',strtotime($fromdate)).' '.$current_date[1];
						    $todate = date('Y-m-d',strtotime($todate)).' '.$current_date[1];
						    /** check valid date or not and check exclus date **/	

						    //if(checkdate($check_month,$check_date,$check_year) && !in_array($booking_date,$remove_date))		
						    if(checkdate($check_month,$check_date,$check_year))		
						    {									
								$expense_date = $expense_date.' '.$current_date[1];
								//echo '<br>';
								$exp_result = DB::insert(DRIVER_EXP, array('expense_driver_id','expense_amount','expense_type_id','payment_type','recurrent','notes','expense_date','fromdate','todate'))
								->values(array($expense_driver_id,$expense_amount,$expense_type_id,$payment_type,$recurring,$notes,$expense_date,$fromdate,$todate))
								->execute();
							}
						}
					}
					return 1;
			}
				catch (Kohana_Exception $e) {
				//print_r($e);
				return -1;			
			}			
	}
	
	public function edit_expense($add_exp_array)
	{
		try {
					$expense_id = $add_exp_array['expense_id'];
					$expense_driver_id = $add_exp_array['expense_driver_id'];
					$expense_amount = $add_exp_array['expense_amount'];
					$expense_type_id = $add_exp_array['expense_type_id'];
					
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);					
					
					$expense_date = $add_exp_array['expense_date'].' '.$current_date[1];
					$recurring = $add_exp_array['recurring'];
					$notes = $add_exp_array['notes'];
					$fromdate = $add_exp_array['fromdate'];
					$todate = $add_exp_array['todate'];			
					$payment_type = "Cash";							
					if($recurring == 0)
					{					
						$update_array = array(
						"expense_amount"=>$expense_amount,
						"expense_type_id"=>$expense_type_id,
						"recurrent"=>$recurring,
						"notes"=>$notes,
						"expense_date"=>$expense_date,			
						"fromdate"=>'0000:00:00 00:00:00',
						"todate"=>'0000:00:00 00:00:00'
						);
						$update_result = DB::update(DRIVER_EXP)
							->set($update_array)
							->where('expense_id', '=', $expense_id)
							->where('expense_driver_id', '=', $expense_driver_id)
							->execute();
					}
					else
					{
						//echo strtotime($fromdate);
						//echo '<br>';
						//echo strtotime($todate);
						//echo '<br>';
						for($i = strtotime($fromdate); $i <= strtotime($todate); $i = strtotime('+1 Day', $i))
						{
						    //echo date('l Y-m-d', $i);						    
						    $expense_date = date('Y-m-d', $i);
						    //echo '<br>';
						    $check_date = date('d', $i);	
						    $check_month = date('m', $i);	
						    $check_year = date('Y', $i);		
						    $fromdate = date('Y-m-d',strtotime($fromdate)).' '.$current_date[1];
						    $todate = date('Y-m-d',strtotime($todate)).' '.$current_date[1];
						    /** check valid date or not and check exclus date **/	

						    //if(checkdate($check_month,$check_date,$check_year) && !in_array($booking_date,$remove_date))		
						    if(checkdate($check_month,$check_date,$check_year))		
						    {									
								$expense_datetime = $expense_date.' '.$current_date[1];								
								//if(strtotime($expense_date) > strtotime($current_time) )
								if(strtotime($expense_date) >= strtotime($current_time) )
								{									
									//echo $expense_date;
									//echo '<br>';
									$delete_query = "DELETE FROM ".DRIVER_EXP." WHERE ".DRIVER_EXP.".expense_driver_id = $expense_driver_id and ".DRIVER_EXP.".`expense_date` > '$current_time'";
									$result = Db::query(Database::DELETE, $delete_query)
											   ->execute();			
								}			   
							}
						}
						for($i = strtotime($fromdate); $i <= strtotime($todate); $i = strtotime('+1 Day', $i))
						{
						    //echo date('l Y-m-d', $i);						    
						    $expense_date = date('Y-m-d', $i);
						    //echo '<br>';
						    $check_date = date('d', $i);	
						    $check_month = date('m', $i);	
						    $check_year = date('Y', $i);		
						    $fromdate = date('Y-m-d',strtotime($fromdate)).' '.$current_date[1];
						    $todate = date('Y-m-d',strtotime($todate)).' '.$current_date[1];
						    /** check valid date or not and check exclus date **/	

						    //if(checkdate($check_month,$check_date,$check_year) && !in_array($booking_date,$remove_date))		
						    if(checkdate($check_month,$check_date,$check_year))		
						    {									
								$expense_datetime = $expense_date.' '.$current_date[1];	
								//print_r($delete_query);
									//exit;
									/*$update_array = array(
									"expense_amount"=>$expense_amount,
									"expense_type_id"=>$expense_type_id,
									"recurring"=>$recurring,
									"notes"=>$notes,
									"expense_date"=>$expense_datetime,						
									);*/
									//echo $current_time;
									//echo '<br>';
								if(strtotime($expense_date) > strtotime($current_time) )
								{
									$exp_result = DB::insert(DRIVER_EXP, array('expense_driver_id','expense_amount','expense_type_id','payment_type','recurrent','notes','expense_date','fromdate','todate'))
								->values(array($expense_driver_id,$expense_amount,$expense_type_id,$payment_type,$recurring,$notes,$expense_datetime,$fromdate,$todate))
								->execute();
								//print_r($exp_result);
								}
							}
						}
					}
					return 1;
			}
				catch (Kohana_Exception $e) {
				//print_r($e);
				return -1;			
			}			
	}
	public function delete_expense($add_exp_array)
	{
		$expense_id = $add_exp_array['expense_id'];
		$delete_query = "DELETE FROM ".DRIVER_EXP." WHERE ".DRIVER_EXP.".expense_id = $expense_id";
		$result = Db::query(Database::DELETE, $delete_query)
		->execute();
		return 1;
	}

	public function delete_rejected_trips($passenger_id,$company_all_currenttimestamp)
	{
		$datetime = explode(' ',$company_all_currenttimestamp);
		$currentdate = $datetime[0].' 00:00:01';
		$passenger_id = $passenger_id;
		$delete_query = "DELETE FROM ".DRIVER_REJECTION." WHERE ".DRIVER_REJECTION.".passengers_id = '$passenger_id' and createdate >= '$currentdate'";
		$result = Db::query(Database::DELETE, $delete_query)
		->execute();
		return 1;
	}
	
	public function delete_card($delete_card_array)
	{
		$card_id = $delete_card_array['passenger_cardid'];
		$delete_query = "DELETE FROM ".PASSENGERS_CARD_DETAILS." WHERE ".PASSENGERS_CARD_DETAILS.".passenger_cardid = $card_id";
		$result = Db::query(Database::DELETE, $delete_query)
		->execute();
		return 1;
	}
	// Get target by current date 
	public function get_target($target_date,$target_driver_id)
	{
		//DATE_FORMAT(fromdate,'%Y-%m-%d') as fromdate,DATE_FORMAT(todate,'%Y-%m-%d') as todate
		 $sql = "select target_id,target_amount,recurrent,fromdate,todate from ".DRIVER_TARGET." where target_driver_id = '$target_driver_id' and target_date = '$target_date'";
		 $result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();					             
			return $result;	
	}
	public function check_today_target($target_date,$target_driver_id)
	{
		 $sql = "select target_id from ".DRIVER_TARGET." where target_driver_id = '$target_driver_id' and target_date = '$target_date'";
		 $result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();					             
		if(count($result)>0)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	//save_target &target_driver_id=16&target_amount=100&target_date=2012-12-26 15:16:00
	public function save_target($add_target_array)
	{
					$target_driver_id= $add_target_array['target_driver_id'];
					$target_amount = $add_target_array['target_amount'];
					//$target_date = $add_target_array['target_date'];
					
					$target_date = convert_timezone('now',TIMEZONE);
					$createdate = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$target_date);		
					
					//$expense_date = $add_exp_array['expense_date']. $current_date[1];
					$recurring = $add_target_array['recurring'];
					$fromdate = $add_target_array['fromdate'];
					$todate = $add_target_array['todate'];	
					if($recurring == 0)
					{					
						$target_date = date('Y-m-d',strtotime($add_target_array['target_date']));
						$exp_result = DB::insert(DRIVER_TARGET, array('target_driver_id','target_amount','recurrent','target_date','createdate'))
							->values(array($target_driver_id,$target_amount,$recurring,$target_date,$createdate))
							->execute();
					}
					else
					{
						for($i = strtotime($fromdate); $i <= strtotime($todate); $i = strtotime('+1 Day', $i))
						{
						    //echo date('l Y-m-d', $i);
						    $target_date = date('Y-m-d', $i);
						    $check_date = date('d', $i);	
						    $check_month = date('m', $i);	
						    $check_year = date('Y', $i);		
						    /** check valid date or not and check exclus date **/	

						    //if(checkdate($check_month,$check_date,$check_year) && !in_array($booking_date,$remove_date))		
						    if(checkdate($check_month,$check_date,$check_year))		
						    {									
								//if(strtotime($target_date) > strtotime($current_date[0]) )
								//{
									$target_date = $target_date.' '.$current_date[1];
									//echo '<br>';
									$target_date = date('Y-m-d',strtotime($target_date));
									$fromdate = date('Y-m-d',strtotime($fromdate)).' '.$current_date[1];
									$todate = date('Y-m-d',strtotime($todate)).' '.$current_date[1];								
									$exp_result = DB::insert(DRIVER_TARGET, array('target_driver_id','target_amount','recurrent','target_date','fromdate','todate','createdate'))
									->values(array($target_driver_id,$target_amount,$recurring,$target_date,$fromdate,$todate,$createdate))
									->execute();
								//}
							}
						}
					}
							
		return $exp_result;
	}
	//save_target &target_driver_id=16&target_amount=100&target_date=2012-12-26 15:16:00
	public function update_target($add_target_array)
	{
					$target_driver_id= $add_target_array['target_driver_id'];
					$target_amount = $add_target_array['target_amount'];
					//$target_date = $add_target_array['target_date'];
					
					$target_date = convert_timezone('now',TIMEZONE);
					$createdate = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$target_date);		
					
					//$expense_date = $add_exp_array['expense_date']. $current_date[1];
					$recurring = $add_target_array['recurring'];
					$fromdate = $add_target_array['fromdate'];
					$todate = $add_target_array['todate'];	
					$target_id = $add_target_array['target_id'];
					if($recurring == 0)
					{					
						$target_date = date('Y-m-d',strtotime($add_target_array['target_date']));
						//$exp_result = DB::insert(DRIVER_TARGET, array('target_driver_id','target_amount','recurrent','target_date','createdate'))
						//	->values(array($target_driver_id,$target_amount,$recurring,$target_date,$createdate))
						//	->execute();
						$update_array = array(
						"target_amount"=>$target_amount,
						"recurrent"=>$recurring,
						"target_date"=>$target_date,
						);
						//echo 'as'.$target_id;
						$update_result = DB::update(DRIVER_TARGET)
							->set($update_array)
							->where('target_id', '=', $target_id)
							->where('target_driver_id', '=', $target_driver_id)
							->execute();
							//print_r($update_array);
						$exp_result	= 1;
					}
					else
					{
						for($i = strtotime($fromdate); $i <= strtotime($todate); $i = strtotime('+1 Day', $i))
						{
						    //echo date('l Y-m-d', $i);
						    $target_date = date('Y-m-d', $i);
						    $check_date = date('d', $i);	
						    $check_month = date('m', $i);	
						    $check_year = date('Y', $i);		
						    /** check valid date or not and check exclus date **/	

						    //if(checkdate($check_month,$check_date,$check_year) && !in_array($booking_date,$remove_date))		
						    if(checkdate($check_month,$check_date,$check_year))		
						    {																	
								
								if(strtotime($target_date) >= strtotime($current_date[0]) )
								{									
									//echo $expense_date;
									//echo '<br>';
									/******** Delete the record after current date if recurrent booking is enabled *****/
									$delete_query = "DELETE FROM ".DRIVER_TARGET." WHERE ".DRIVER_TARGET.".target_driver_id = $target_driver_id and ".DRIVER_TARGET.".`target_date` >= '$target_date'";
									$result = Db::query(Database::DELETE, $delete_query)
											   ->execute();			
									/************ Insert new records after current date ***************/		   
									$target_date = date('Y-m-d',strtotime($target_date));
									$fromdate = date('Y-m-d',strtotime($fromdate)).' '.$current_date[1];
									$todate = date('Y-m-d',strtotime($todate)).' '.$current_date[1];									
									$exp_result = DB::insert(DRIVER_TARGET, array('target_driver_id','target_amount','recurrent','target_date','fromdate','todate','createdate'))
									->values(array($target_driver_id,$target_amount,$recurring,$target_date,$fromdate,$todate,$createdate))
									->execute();															
								}
							}
						}
					}
							
		return $exp_result;
	}
	// Get today Goal Details //
	public function get_goal_details($id,$msg_status,$driver_reply=null,$travel_status=null)
	{
		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $current_date[0].' 00:00:01';
		$end_time = $current_date[0].' 23:59:59';
		$condition = "AND passengers_log.createdate >='".$start_time."' and passengers_log.createdate <= '".$end_time."'"; 
		/*$sql = " SELECT sum(`transacation`.`amt`) as acheive_amt,  DATE(passengers_log.createdate) datewise,`driver_target`.`target_amount` FROM `passengers_log`  LEFT JOIN `transacation` ON (`passengers_log`.`passengers_log_id` = `transacation`.`passengers_log_id`)  left JOIN `driver_target` ON (`passengers_log`.`driver_id` = `driver_target`.`target_driver_id`) WHERE `passengers_log`.`driver_id` = '$id' AND `passengers_log`.`msg_status` = '$msg_status' AND `passengers_log`.`driver_reply` = '$driver_reply' AND `passengers_log`.`travel_status` = '1' AND ( passengers_log.createdate between '$start_time' and '$end_time' ) group by datewise";	*/
		$sql = "select sum(transacation.amt) as acheive_amt,(select target_amount from driver_target where target_date = '".$current_date[0]."' and target_driver_id='$id') as target_amount from transacation left join  passengers_log on  passengers_log.passengers_log_id=transacation.passengers_log_id WHERE `passengers_log`.`driver_id` = '$id' AND `passengers_log`.`msg_status` = 'R' AND `passengers_log`.`driver_reply` = '$driver_reply' AND `passengers_log`.`travel_status` = '1' AND passengers_log.createdate >='$start_time' and passengers_log.createdate <= '$end_time'";							
		//print_r($sql);           exit;
	$result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
		return $result;	
	}
		// Check Whether People phone is Already Exist or Not //
	/*********************************************************************************************/
	/*********** Function Used for validate credit cards ***************/
		function _checkSum($ccnum)
		{
			$checksum = 0;
			for ($i=(2-(strlen($ccnum) % 2)); $i<=strlen($ccnum); $i+=2)
			{
				$checksum += (int)($ccnum{$i-1});
			}
			// Analyze odd digits in even length strings or even digits in odd length strings.
		    for ($i=(strlen($ccnum)% 2) + 1; $i<strlen($ccnum); $i+=2) 
			{
			  $digit = (int)($ccnum{$i-1}) * 2;
			  if ($digit < 10) 
				  { $checksum += $digit; } 
			  else 
				  { $checksum += ($digit-9); }
		    }
			if (($checksum % 10) == 0) 
				return true; 
			else 
				return false;

		}

		function isVAlidCreditCard($ccnum,$type="",$returnobj=false)
		{
			$creditcard=array(  "visa"=>"/^4\d{3}-?\d{4}-?\d{4}-?\d{4}$/",
								"mastercard"=>"/^5[1-5]\d{2}-?\d{4}-?\d{4}-?\d{4}$/",
								"discover"=>"/^6011-?\d{4}-?\d{4}-?\d{4}$/",
								"amex"=>"/^3[4,7]\d{13}$/",
								"diners"=>"/^3[0,6,8]\d{12}$/",
								"bankcard"=>"/^5610-?\d{4}-?\d{4}-?\d{4}$/",
								"jcb"=>"/^[3088|3096|3112|3158|3337|3528|3530]\d{12}$/",
								"enroute"=>"/^[2014|2149]\d{11}$/",
								"switch"=>"/^[4903|4911|4936|5641|6333|6759|6334|6767]\d{12}$/");
			if(empty($type))
			{
				
				$match=false;
				foreach($creditcard as $type=>$pattern)
					if(preg_match($pattern,$ccnum)==1)
					{
						$match=true;
						break;
					}
				
				if(!$match)
					return 0;
				else
				{
					
					if($returnobj)
					{
						$return=new stdclass;
						$return->valid=$this->_checkSum($ccnum);
						$return->ccnum=$ccnum;
						$return->type=$type;
						return 1;
					}
					else
						return 0;
				}
			
			}
			else
			{
				if(@preg_match($creditcard[strtolower(trim($type))],$ccnum)==0)
				{
					return false;
				}
				else
				{
					if($returnobj)
					{
						//print_r($returnobj);
						$return=new stdclass;						
						$return->valid=$this->_checkSum($ccnum);
						$return->ccnum=$ccnum;
						$return->type=$type;
						return 1;
					}
					else
						return 1;
				}
			}
		}
/*************************************************************************************/		
	//Mobile Driver Push Notification Sending
    public function send_driver_mobile_pushnotification($d_device_token="",$device_type="",$pushmessage=null,$android_api="",$book_request="")
    {                          
				//---------------------------------- ANDROID ----------------------------------//                            
                           if($device_type == 1)
                           {						  
                            $apiKey = $android_api;
                            //echo $d_device_token;
                            $registrationIDs = array($d_device_token);
                            // Message to be sent                                    
                            if(!empty($registrationIDs))
                            {
                                // Set POST variables
                                $url = 'https://android.googleapis.com/gcm/send';
                                //print_r($registrationIDs);exit;
                                $fields = array(
                                                'registration_ids'  => $registrationIDs,
                                                'data'              => array( "message" => $pushmessage ),
                                                );

                                $headers = array( 
                                                'Authorization: key=' . $apiKey,
                                                'Content-Type: application/json'
                                                );
                               // print_r($pushmessage);exit;
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
                                //echo $result;
                                // Close connection
                                curl_close($ch);
                               //echo $result;                            
                            }
                            //exit;  
                         }
                         else
                         {                          
                            //---------------------------------- IPHONE ----------------------------------//  
                            //print_r($contact_iphone);exit;
                                $deviceToken = $d_device_token;
                                $deviceToken = trim($deviceToken);                                                                                         
                                if(!empty($deviceToken))
                                {
                                    //print_r($deviceToken);exit;
                                    // Put your private key's passphrase here:
                                    $passphrase = '1234';
                                    // Put your alert message here:
                                    //$message = $message = "A new business ".$business_name." is added in Yiper";
                                    //$message = $deal_id.".".ucfirst($merchant_name)." has a new deal for you. View now...";                                    
                                    $badge = 0;
                                    ////////////////////////////////////////////////////////////////////////////////
                                    $root = $_SERVER['DOCUMENT_ROOT'].'/application/classes/controller/ck.pem' ;
                                   // echo  $root;
                                    $ctx = stream_context_create();
                                    stream_context_set_option($ctx, 'ssl', 'local_cert',$root );
                                    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

                                    // Open a connection to the APNS server
                                    $fp = stream_socket_client(
                                        'ssl://gateway.sandbox.push.apple.com:2195', $err,
                                        $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
                                    if (!$fp)
                                        exit("Failed to connect: $err $errstr" . PHP_EOL);
                                    //echo 'Connected to APNS' . PHP_EOL;
                                    // Create the payload body
                                    $status = $pushmessage['status'];
                                    if($status == 1)
                                    {
										$pickup = $pushmessage['pickup'];
										$passenger_id = $pushmessage['passenger_id'];
										$taxi_id = $pushmessage['taxi_id'];
										$company_id = $pushmessage['company_id'];
										$distance = $pushmessage['distance'];
										$trip_details = $passenger_id.'-'.$taxi_id.'-'.$company_id.'-'.$distance;
										$body['aps'] = array(
											'alert' => 'You have new booking request',
											'trip_details'	=> $trip_details,
											'sound' => 'default',                                                                       
											);
									}
									else if($status == 3)
									{
										$pickup = $pushmessage['pickup'];
										$fare = $pushmessage['fare'];
										$referral_discount = $pushmessage['referral_discount'];
										$message = $pushmessage['result'];
										$fare_details = $pickup.'-'.$fare.'-'.$referral_discount;
										$body['aps'] = array(
											'alert' => $message,
											'fare_details'	=> $fare_details,
											'sound' => 'default',                                                                       
											);

											$body['aps'] = array(
											'alert' => $pushmessage,
											'badge' => $badge,
											'sound' => 'default',                                                                       
											);
									}
                                       // print_r($body);
                                       // exit;
                                    // Encode the payload as JSON
                                    $payload = json_encode($body);
                                    // Build the binary notification
                                    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
                                    // Send it to the server
                                    $result = fwrite($fp, $msg, strlen($msg));
                                    //if (!$result)
                                      //  echo 'Message not delivered' . PHP_EOL;
                                    //else
                                        //echo 'Message successfully delivered' . PHP_EOL;
                                    // Close the connection to the server
                                    fclose($fp);  
                                } 
                        }					
		}
		/** Get Driver Target by Date ********/
	public function get_driver_target_by_week($id,$msg_status,$driver_reply=null,$travel_status=null,$target_date)
	{
		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $target_date.' 00:00:00';
		$end_time = $target_date.' 23:59:59';
		 /*
		$sql = "SELECT sum(`transacation`.`amt`) as ach_amt,  DATE(passengers_log.createdate) datewise,(select `driver_target`.`target_amount`from driver_target where Date(driver_target.target_date) = Date(passengers_log.createdate) and target_driver_id = '$id' limit 1) as target_amount  FROM `passengers_log`  LEFT JOIN `transacation` ON (`passengers_log`.`passengers_log_id` = `transacation`.`passengers_log_id`)  LEFT JOIN `driver_target` ON ( driver_target.target_driver_id = passengers_log.driver_id  ) WHERE `passengers_log`.`driver_id` = '$id' AND `passengers_log`.`msg_status` = '$msg_status' AND `passengers_log`.`driver_reply` = '$driver_reply' AND `passengers_log`.`travel_status` = '1' AND passengers_log.createdate >='$start_time' and passengers_log.createdate <= '$end_time' group by datewise";
*/

		$sql = "select target_amount, (select sum(transacation.amt) from transacation left join  passengers_log on  passengers_log.passengers_log_id=transacation.passengers_log_id WHERE `passengers_log`.`driver_id` = '$id' AND `passengers_log`.`msg_status` = 'R' AND `passengers_log`.`driver_reply` = '$driver_reply' AND `passengers_log`.`travel_status` = '1' AND passengers_log.createdate >='$start_time' and passengers_log.createdate <= '$end_time' limit 1 ) as ach_amt from driver_target where target_date = '$target_date' and target_driver_id='$id'";

//echo $sql;echo '<br>';
		$result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
		   //print_r($result);exit;
		return $result;	
	}
	/** Get Driver Target by Month ********/
	public function get_driver_target_by_month($id,$msg_status,$driver_reply=null,$travel_status=null,$from,$to)
	{
		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		
		$start_time = $from.' 00:00:00';
		$end_time = $to.' 23:59:59';
		 
		/*$sql = "SELECT sum(`transacation`.`amt`) as ach_amt,  DATE(passengers_log.createdate) datewise,(select `driver_target`.`target_amount`from driver_target where Date(driver_target.target_date) = Date(passengers_log.createdate) and target_driver_id = '$id' limit 1) as target_amount  FROM `passengers_log`  LEFT JOIN `transacation` ON (`passengers_log`.`passengers_log_id` = `transacation`.`passengers_log_id`)  LEFT JOIN `driver_target` ON ( driver_target.target_driver_id = passengers_log.driver_id  ) WHERE `passengers_log`.`driver_id` = '$id' AND `passengers_log`.`msg_status` = '$msg_status' AND `passengers_log`.`driver_reply` = '$driver_reply' AND `passengers_log`.`travel_status` = '1' AND passengers_log.createdate >='$start_time' and passengers_log.createdate <= '$end_time' group by datewise";*/

		$sql = "select target_amount, (select sum(transacation.amt) from transacation left join  passengers_log on  passengers_log.passengers_log_id=transacation.passengers_log_id WHERE `passengers_log`.`driver_id` = '$id' AND `passengers_log`.`msg_status` = 'R' AND `passengers_log`.`driver_reply` = '$driver_reply' AND `passengers_log`.`travel_status` = '1' AND ( passengers_log.createdate >='$start_time' and passengers_log.createdate <= '$end_time' ) limit 1 ) as ach_amt from driver_target where ( target_date >='$from' and target_date <='$to' ) and  target_driver_id='$id'";

		//echo $sql;
		//echo '<br/<br/><br/>',
		$result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
		   //print_r($result);exit;
			$achve_amt = '';
			$tar_amt = '';
		   if(count($result) > 0)
		   {
			  foreach($result as $amount)
				{
					$achve_amt += $amount['ach_amt'];
					$tar_amt += $amount['target_amount'];
				}
		   }
		   else
		   {
				$achve_amt += 0;
				$tar_amt += 0;
		   }
        $achve_amt = number_format($achve_amt, 2, '.', '');
        $tar_amt = number_format($tar_amt, 2, '.', '');		   
		$total_amounts = array("ach_amt" =>  $achve_amt ,"target_amount" => $tar_amt);
		return $total_amounts;		
	}
	/** Get Driver Target by Year ********/
	public function get_driver_target_by_year($id,$msg_status,$driver_reply=null,$travel_status=null,$year)
	{
		$condition = "AND YEAR( passengers_log.createdate) = $year";
		$sql = "SELECT sum(`transacation`.`amt`) as ach_amt,  DATE(passengers_log.createdate) datewise,(select `driver_target`.`target_amount`from driver_target where Date(driver_target.target_date) = Date(passengers_log.createdate) and target_driver_id = '$id' limit 1) as target_amount FROM `passengers_log`  LEFT JOIN `transacation` ON (`passengers_log`.`passengers_log_id` = `transacation`.`passengers_log_id`)  LEFT JOIN `driver_target` ON ( driver_target.target_driver_id = passengers_log.driver_id  ) WHERE `passengers_log`.`driver_id` = '$id' AND `passengers_log`.`msg_status` = '$msg_status' AND `passengers_log`.`driver_reply` = '$driver_reply' AND `passengers_log`.`travel_status` = '1' $condition group by datewise";
		//echo $sql;
		$result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
		return $result;	
	}
	//Mobile Passenger Push Notification Sending
    public function send_passenger_mobile_pushnotification($d_device_token="",$device_type="",$pushmessage=null,$android_api="")
    {                          
                               
//echo 'asd';
                               // print_r($pushmessage);exit;
		//echo 'as'.$device_type;
				//---------------------------------- ANDROID ----------------------------------//                            
                           if($device_type == 1)
                           {						  
                            $apiKey = $android_api;
                            $registrationIDs = array($d_device_token);                            
                            //echo $d_device_token;
                            // Message to be sent                                    
                            if(!empty($registrationIDs))
                            {
                                // Set POST variables
                                $url = 'https://android.googleapis.com/gcm/send';
                                //print_r($registrationIDs);exit;
                                
                                $pushmessage = json_encode($pushmessage);
                                //print_r($pushmessage);exit;
                                $fields = array(
                                                'registration_ids'  => $registrationIDs,
                                                'data'              => array( "message" => $pushmessage),
                                                );

                                $headers = array( 
                                                'Authorization: key=' . $apiKey,
                                                'Content-Type: application/json'
                                                );


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
                                //echo $result;
                                // Close connection
                                curl_close($ch);
                                //echo $result;                            
                            }
                           //exit;  
                         }
                         else
                         {                          
                            //---------------------------------- IPHONE ----------------------------------//  
                            //print_r($contact_iphone);exit;
                           // print_r($pushmessage);
                                $deviceToken = $d_device_token;
                                $deviceToken = trim($deviceToken);   
                               // echo $deviceToken;exit;                                                                                      
                                if(!empty($deviceToken))
                                {
                                    //print_r($deviceToken);exit;
                                    // Put your private key's passphrase here:
                                    $passphrase = '1234';
                                    // Put your alert message here:
                                    //$message = $message = "A new business ".$business_name." is added in Yiper";
                                    //$message = $deal_id.".".ucfirst($merchant_name)." has a new deal for you. View now...";                                    
                                    $badge = 0;
                                    ////////////////////////////////////////////////////////////////////////////////
                                    $root = $_SERVER['DOCUMENT_ROOT'].'/application/classes/controller/ck.pem' ;
                                    $ctx = stream_context_create();
                                    stream_context_set_option($ctx, 'ssl', 'local_cert',$root );
                                    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

                                    // Open a connection to the APNS server
                                    $fp = stream_socket_client(
                                        'ssl://gateway.sandbox.push.apple.com:2195', $err,
                                        $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
                                    if (!$fp)
                                        exit("Failed to connect: $err $errstr" . PHP_EOL);
                                    //echo 'Connected to APNS' . PHP_EOL; 
                                    // Create the payload body
                                    $message=$pushmessage['message'];
                                    $status=$pushmessage['status'];
                                    if($status == 1)
                                    {
                                    $trip_id=$pushmessage['trip_id'];
                                    $body['aps'] = array(
                                        'alert' => $message,
                                        'trip_id' =>$trip_id,
                                        'status'=>$status,
                                        'sound' => 'default'                                       
                                        );
                                     }
                                     else if($status == 2){	
									$trip_id=$pushmessage['trip_id'];									 
                                    $body['aps'] = array(
                                        'alert' => $message,
                                        'trip_id' =>$trip_id,
                                        'status'=>$status,
                                        'sound' => 'default'                                       
                                        );
									}
									else if($status == 3){										 
                                    $trip_id=$pushmessage['trip_id'];
                                    $body['aps'] = array(
                                        'alert' => $message,
                                        'trip_id' =>$trip_id,
                                        'status'=>$status,
                                        'sound' => 'default'                                       
                                        );
									}
									else if($status == 4)
									{									
                                    $body['aps'] = array(
                                        'alert' => $message,
                                        'status'=>$status,
                                        'sound' => 'default'                                       
                                        );										
									}
									else if($status == 5)
									{
                                    $fare=$pushmessage['fare'];		
                                    $pickup=$pushmessage['pickup'];										
                                    $body['aps'] = array(
                                        'alert' => $message,
                                        'fare' => $fare,
                                        'pickup' => $pickup,
                                        'status'=>$status,
                                        'sound' => 'default'                                       
                                        );										
									}
									else if($status == 6)
                                    {
                                    $trip_id=$pushmessage['trip_id'];
                                    $body['aps'] = array(
                                        'alert' => $message,
                                        'trip_id' =>$trip_id,
                                        'status'=>$status,
                                        'sound' => 'default'                                       
                                        );
                                     }
									else if($status == 7)
                                    {
                                    $trip_id=$pushmessage['trip_id'];
                                    $body['aps'] = array(
                                        'alert' => $message,
                                        'trip_id' =>$trip_id,
                                        'status'=>$status,
                                        'sound' => 'default'                                       
                                        );
                                     }                                     
									else
									{
                                    $body['aps'] = array(
                                        'alert' => $message,
                                        'status'=>$status,
                                        'sound' => 'default'                                       
                                        );										
									}							
									//print_r($body);		
                                    // Encode the payload as JSON
                                    $payload = json_encode($body);
                                    //print_r($payload);//exit;
                                    //
                                    // Build the binary notification
                                    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
                                    // Send it to the server
                                    $result = fwrite($fp, $msg, strlen($msg));
                                    /*if (!$result)
                                        echo 'Message not delivered' . PHP_EOL;
                                    else
                                        echo 'Message successfully 
                                        * delivered' . PHP_EOL;*/
                                    // Close the connection to the server
                                    fclose($fp);  
                                } 
                        }					
		}
		
		/******** reg_passenger_first_trip ***********/
		public function reg_passenger_first_trip($passengers_id)
		{
			$sql = "select ".PASSENGERS_LOG.".passengers_log_id from ".PASSENGERS_LOG." RIGHT JOIN ".TRANS." as t ON ".PASSENGERS_LOG.".passengers_log_id = t.passengers_log_id where passengers_id = '$passengers_id' and travel_status = '1' and  driver_reply = 'A' and msg_status='R'";
			 $result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();					             
			if(count($result) == 0)
			{
				return 1;
			}
			else
			{
				return 0;
			}				
		}
		
		/************* Update Referral Discount ***********************/
		public function update_ref_discount($referred_by,$referral_discount)
		{
					/****************************************/
					$refby_array = array("referral_earned_amount"=>$referral_discount);
					$refby_result = DB::update(PASSENGERS)
								->set($refby_array)
								->where('id', '=', $referred_by)
								->where('referrer_earned', '=', '0')							
								->execute();				
					//print_r($ref_result);			
					/*****************************************/			
		}
		
		/************* Update Registerer Discount ***********************/
		public function update_registerer_discount($passengers_id,$trip_id,$referral_discount)
		{
					/****************************************/
					$refby_array = array("registerer_earned"=>'1',"registerer_tripid"=>$trip_id,"earned_amount"=>$referral_discount);
					$refby_result = DB::update(PASSENGERS_REF_DETAILS)
								->set($refby_array)
								->where('registered_passenger_id', '=', $passengers_id)
								->execute();				
					//print_r($ref_result);			
					/*****************************************/			
		}
		/*********************** update_referer_earn_status **********/
		public function update_referer_earn_status($passengers_id)
		{
					/****************************************/
					$refby_array = array("referrer_earned"=>'1');
					$refby_result = DB::update(PASSENGERS)
								->set($refby_array)
								->where('id', '=', $passengers_id)
								->execute();				
					//print_r($ref_result);			
					/*****************************************/			
		}
		// Get Faq list
		
		public function get_faq_list()
		{	 
		 $sql = "select faq_id,faq_title,faq_details from ".PASSENGERS_FAQ." where status = 'A'";
		 $result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();					             
			return $result;				
		}
		
		// Get Passenger credit card details
		
		public function get_creadit_card_details($passenger_id="",$card_type="",$default="")
		{
		$condition="";
		if(($card_type == "P") || ($card_type == "B"))
		{
			$condition = " and card_type = '$card_type'";
		}	
		if($default == 'yes')
		{
			$condition .= " and default_card = '1'";
		}	 
		 $sql = "select passenger_cardid,passenger_id,card_type,creditcard_cvv,expdatemonth,default_card,expdateyear,creditcard_no from ".PASSENGERS_CARD_DETAILS." where passenger_id='$passenger_id' $condition";
		 $result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();					             
		 return $result;				
		}
		
		public function driver_ratings($driver_id)
		{
					$result = DB::select(PASSENGERS_LOG.'.passengers_log_id',PASSENGERS_LOG.'.passengers_id',PASSENGERS_LOG.'.driver_id',PASSENGERS_LOG.'.taxi_id',PASSENGERS_LOG.'.company_id',PASSENGERS_LOG.'.current_location',PASSENGERS_LOG.'.pickup_latitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.drop_location',PASSENGERS_LOG.'.drop_latitude',PASSENGERS_LOG.'.drop_longitude',PASSENGERS_LOG.'.rating',PASSENGERS_LOG.'.comments',PASSENGERS_LOG.'.driver_comments',PEOPLE.'.salutation',PEOPLE.'.name',PEOPLE.'.lastname',PEOPLE.'.email',PEOPLE.'.photo',PEOPLE.'.device_token',PEOPLE.'.device_type')->from(PASSENGERS_LOG)	
					->where('driver_id','=',$driver_id)		
					->join(PEOPLE)->on(PASSENGERS_LOG.'.driver_id','=',PEOPLE.'.id')
					->where('travel_status','=',1)
					->where('driver_reply','=','A')		
					->order_by('createdate','DESC')	
					->limit(5)->offset(0)
					->execute()
					->as_array();
					
					return $result;
		}	

	public function get_passengerlog_notify($log_id)
	{

			$log_query = "select approx_fare,drop_latitude,pickup_longitude,notes_driver,approx_distance,sub_logid,passengers_id,drop_longitude,pickup_latitude,search_city,taxi_id,(select name from passengers where passengers.id=passengers_log.passengers_id) as passenger_name, (select profile_image from passengers where passengers.id=passengers_log.passengers_id) as profile_image from  passengers_log where passengers_log_id='$log_id' "; 
			$log_fetch = Db::query(Database::SELECT, $log_query)
					->execute()
					->as_array();

			$passenger_photo = $log_fetch[0]['profile_image'];


				if((!empty($passenger_photo)) && file_exists($_SERVER['DOCUMENT_ROOT'].PASS_IMG_IMGPATH.$passenger_photo)){ 
					$passenger_image = $passenger_photo; 
				 }
				else{ 
					$passenger_image = 0;
				 } 

	
			$log_fetch[0]['passenger_image'] = $passenger_image;

			return $log_fetch;
	}

	// Get Payment type name
	public function get_payment_name($payment_id)
	{
		$log_query = "SELECT pay_mod_name,pay_mod_image FROM  ".PAYMENT_MODULES." WHERE  `pay_mod_id` ='$payment_id' "; 
			$log_fetch = Db::query(Database::SELECT, $log_query)
					->execute()
					->as_array();	
					return $log_fetch;
	}
	public function payment_packagedetails($packid=0)
	{

		$result = DB::select()->from(PACKAGE)
			->where('package_status','=','A')
			->where('package_id','=',$packid)
			->order_by('package_name','asc')
			->execute()
			->as_array();

		  return $result;
	}
		public function checktrans_details($log_id)
	{
		$sql = "SELECT id FROM ".TRANS." WHERE passengers_log_id = '$log_id'";

		$result =  Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

		return $result;

	}
	//Function used to get the get_driver ongoign trips
	public function get_driver_current_ongoigtrips($id,$msg_status,$driver_reply=null,$travel_status=null,$company_id,$start=null,$limit=null) //
	{

		if($company_id == '')
		{
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
					$current_date = explode(' ',$current_time);
					$start_time = $current_date[0].' 00:00:01';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:01';
					$end_time = date('Y-m-d').' 23:59:59';
					$date = date('Y-m-d %');
				}
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
//drop_location

			 $query = "SELECT ".PASSENGERS.".`name`, ".PASSENGERS.".`phone` AS `passenger_phone`,".PASSENGERS.".profile_image,".PASSENGERS_LOG.".`passengers_log_id`, ".PASSENGERS_LOG.".`current_location` AS `pickup_location`,".PASSENGERS_LOG.".`drop_location`, ".PASSENGERS_LOG.".`drop_location`, ".PASSENGERS_LOG.".`pickup_longitude`, ".PASSENGERS_LOG.".`pickup_latitude`, ".PASSENGERS_LOG.".`drop_latitude`, ".PASSENGERS_LOG.".`drop_longitude`, ".PASSENGERS_LOG.".`travel_status`, ".PASSENGERS_LOG.".`notes_driver` AS `notes`, ".PASSENGERS_LOG.".`distance`, ".PASSENGERS_LOG.".`waitingtime` AS `waiting_hour` FROM ".PASSENGERS_LOG." JOIN `passengers` ON (".PASSENGERS_LOG.".`passengers_id` = `passengers`.`id`) left join ".TRANS."  on 
				".TRANS.".passengers_log_id=".PASSENGERS_LOG.".passengers_log_id  WHERE ".PASSENGERS_LOG.".`driver_id` = '$id' AND ".PASSENGERS_LOG.".`msg_status` = '$msg_status' AND ".PASSENGERS_LOG.".`driver_reply` = '$driver_reply' AND ".PASSENGERS_LOG.".`pickup_time` >= '$start_time' AND (".PASSENGERS_LOG.".`travel_status` = '2' OR ".PASSENGERS_LOG.".`travel_status` = '5') ORDER BY `passengers_log`.`pickup_time` ASC";
			 
			 $result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();	
			 /*DB::select(PASSENGERS.'.name',array(PASSENGERS.'.phone','passenger_phone'),PASSENGERS_LOG.'.passengers_log_id',array(PASSENGERS_LOG.'.current_location','pickup_location'),PASSENGERS_LOG.'.drop_location',PASSENGERS_LOG.'.pickup_longitude',
	PASSENGERS_LOG.'.pickup_latitude',PASSENGERS_LOG.'.drop_latitude',PASSENGERS_LOG.'.drop_longitude',PASSENGERS_LOG.'.travel_status',array(PASSENGERS_LOG.'.notes_driver','notes'),PASSENGERS_LOG.'.distance',array(PASSENGERS_LOG.'.waitingtime','waiting_hour'))->from(PASSENGERS_LOG)
						->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
						->where(PASSENGERS_LOG.'.driver_id','=',$id)
						->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
						->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
						->order_by(PASSENGERS_LOG.'.pickup_time', 'ASC')		
						->where(PASSENGERS_LOG.'.pickup_time','>=',$start_time)			
						->where(PASSENGERS_LOG.'.travel_status','=','2')
						->or_where(PASSENGERS_LOG.'.travel_status','=','5')
						->execute();
						//->as_array();	*/								

//print_r($result);
		return $result;				
	}
	/******** Get Taxi Speed *****************/
	public function get_taxi_speed($taxi_id="")
	{
			$taxi_query = "SELECT taxi_id,taxi_speed FROM  ".TAXI." WHERE  `taxi_id` ='$taxi_id' "; 
			$taxi_speed = Db::query(Database::SELECT, $taxi_query)
					->execute()
					->as_array();	
			if(count($taxi_speed)>0)
			{
				return $taxi_speed[0]['taxi_speed'];
			}
			else
			{
				return 0;
			}
					
	}	
	/***************** Calculate ETA Time *********************/
	public function estimated_time($distance,$taxi_speed)
	{						   
				 $ttime="";  
				 if($distance != 0 && $taxi_speed != 0)
				{
						   $time = $distance/$taxi_speed;    		
						   //Titanium.API.info("Response ETA" + distance + "-" + taxi_speed);					                                                                          
						   $time = $time*3600; // time duration in seconds
						   $days = floor($time / (60 * 60 * 24));
						   $time -= $days * (60 * 60 * 24);
						   $hours = floor($time / (60 * 60));
						   $time -= $hours * (60 * 60);
						   $minutes = floor($time / 60);
						   $time -= $minutes * 60;
						   $seconds = floor($time);
						   $time -= $seconds;
						  
						   if($minutes>0)
							{
								   $ttime .= $minutes.__('Min')+":";
						   }
						   if($seconds>0)
						   {
								   $ttime .= $seconds.__('Sec');
						   }
						   
				  }
				  else
				  {
					 $ttime =1;  
				  }
				  return $ttime;
	}			
	
	public function get_passenger_account_credits($pass_id)
	{
		//$query = "select * from ".TBLGROUP." left JOIN ".TBLGROUPACCOUNT." on  ".TBLGROUP.".aid = ".TBLGROUPACCOUNT.".aid where FIND_IN_SET($pass_id, passenger_id)";
		$query = "select gid,aid,gcompany_id,".TBLGROUP.".limit,department from ".TBLGROUP." where FIND_IN_SET($pass_id, passenger_id)";
		
		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		
		return $result;
	}
	
	public function add_rejected_list($post,$rejection_type)
	{
		$result=DB::insert(DRIVER_REJECTION, array('driver_id','passengers_log_id','passengers_id','reason','rejection_type','createdate'))
						->values(array($post['driver_id'],$post['passengers_log_id'],$post['passengers_id'],$post['reason'],$rejection_type,$post['createdate']))
						->execute();
		return $result;
	}
	
	public function passenger_log_det($trip_id)
	{
		
		$result = DB::select('*',TAXI.'.taxi_model',CITY.'.city_name')->from(PASSENGERS_LOG)
				->join(TAXI)->on(TAXI.'.taxi_id','=',PASSENGERS_LOG.'.taxi_id')
				//->join(MOTORCOMPANY)->on(TAXI.'.taxi_company','=',MOTORCOMPANY.'.motor_id')
				->join(MOTORMODEL)->on(TAXI.'.taxi_model','=',MOTORMODEL.'.model_id')
				->join(CITY)->on(CITY.'.city_id','=',PASSENGERS_LOG.'.search_city')
				->where(PASSENGERS_LOG.'.passengers_log_id','=',$trip_id)
				->execute()
				->as_array();
		
		return $result;
	}
	
	public function update_booking($driver_id,$pass_id)
	{
	$result= DB::update(PASSENGERS_LOG)->set(array('driver_id' => $driver_id,'travel_status' => '9'))
				->where('passengers_log_id','=',$pass_id)
				->execute();	
	return $result;
	}
	
		public function get_passenger_phone_by_id($id) 
	{

		$query= "SELECT phone FROM ".PASSENGERS." WHERE id = '$id'";

		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return ($result[0]['phone'])?$result[0]['phone']:'';	
	}	
	
	public function get_driver_phone_by_id($id) 
	{

		$query= "SELECT phone FROM ".PEOPLE." WHERE id = '$id'";

		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return ($result[0]['phone'])?$result[0]['phone']:'';	
	}
	
	/*	public function send_sms($to='',$message='')
	{
			//Kohana::find_file('vendor/smsgateway/Services', 'Twilio');
			require_once(DOCROOT.'application/vendor/smsgateway/Services/Twilio.php');
			//$sid = SMS_ACCOUNT_ID; // Your Account SID from www.twilio.com/user/account
			$sid ='ACd23c9836bf8708b3ece3e926c9dda4bc'; // Your Account SID from www.twilio.com/user/account
			//$token = SMS_AUTH_TOKEN; // Your Auth Token from www.twilio.com/user/account
			$token = 'fe4d28dfb94ec8eadb5dad2ae34064e9'; // Your Auth Token from www.twilio.com/user/account
			try
			{
				$country_res = DB::select()->from(COUNTRY)->where('default', '=', '1')->execute()->as_array();
				$to=$country_res[0]['telephone_code'].$to;
				$client = new Services_Twilio($sid, $token);
				$res = $client->account->messages->sendMessage(
				 //SMS_FROM_NUMBER, // From a valid Twilio number
				 '+14432254992', // From a valid Twilio number
				  $to, // Text this number
				  $message
				);
			}
			catch(Exception $e)
			{
				
			}
		//echo $res->sid;exit;
	}*/

	public function send_sms($number,$msg)
	{
		$country_res = DB::select()->from(COUNTRY)->where('default', '=', '1')->execute()->as_array();
		$to=str_replace('+','',$country_res[0]['telephone_code'].$number);
		require_once(DOCROOT.'application/vendor/mobility_sms/includeSettings.php');
		$userAccount='';
		$passAccount='';
		$timeSend=time();
		$dateSend=0;
		$deleteKey=0;
		$viewResult=1;
		$sender="Getuptaxi";
		$sms_send=sendSMS($userAccount, $passAccount, $to, $sender, $msg, $timeSend, $dateSend, $deleteKey, $viewResult);
		return $sms_send;
	}
	/** Get driver job status **/
	public function get_request_status($passenger_log_id="")
	{
		$sql = "SELECT driver_reply,time_to_reach_passen FROM ".PASSENGERS_LOG." WHERE `passengers_log_id` = '".$passenger_log_id."'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}

	
	public function get_location_details($trip_id)
	{
		$result =DB::select()->from(PASSENGERS_LOG)
		                     ->join(DRIVER_LOCATION_HISTORY)->on(PASSENGERS_LOG.'.passengers_log_id','=',DRIVER_LOCATION_HISTORY.'.trip_id')
		                     ->where(PASSENGERS_LOG.'.passengers_log_id','=',$trip_id)
		                     ->execute()
		                     ->as_array();
		return $result;
		                   
	}
	
	public function get_rejected_drivers($driver_id,$company_id)
	{
		$result =DB::select()->from(DRIVER_REJECTION)
							//->join(PEOPLE)->on(DRIVER_REJECTION.'.driver_id','=',PEOPLE.'.id')
		                     ->where(DRIVER_REJECTION.'.driver_id','=',$driver_id)
		                     //->where(PEOPLE.'.company_id','=',$company_id)
		                     ->execute()
		                     ->as_array();
		return $result;
	}
	public function delete($table,$cond1,$cond2)
	{
		$result=DB::delete($table)->where($cond1,'=',$cond2)->execute();
		return $result;
	}
	/************ Used to add the temp passenger log details ************/
	public function  add_passengers_log_temp($post)
	{ 
		$passenger_log = DB::select()->from(PASSENGER_LOG_TEMP)
			->where('tpassenger_log_id','=',$post['passengers_log_id'])
			->execute()
			->as_array();
		if(count($passenger_log)>0)
		{ 
				$trip_id=$post['passengers_log_id'];
				$drivers = explode(",",$passenger_log[0]['tdriver_ids']);
				if(end($drivers) == $post['driver_id'])
				{
					$driver_ids=$passenger_log[0]['tdriver_ids'];
				}else
				{
					$driver_ids = $passenger_log[0]['tdriver_ids'].','.$post['driver_id'];
				}
				 $updatequery = " UPDATE ".PASSENGER_LOG_TEMP." SET tdriver_ids='$driver_ids' where tpassenger_log_id = '$trip_id'";	

				$result = Db::query(Database::UPDATE, $updatequery)
				->execute();
				
		}else
		{ 
			$result = DB::insert(PASSENGER_LOG_TEMP,array('tpassenger_log_id','tdriver_ids','tpassenger_id','createdate'))
					->values(array($post['passengers_log_id'],$post['driver_id'],$post['passengers_id'],$post['createdate']))
					->execute();
		}
		return $result;
	}
	/********************* Check any new job request for the driver ***********************/
	public function check_new_request($driver_id,$company_all_currenttimestamp)
	{
		$datetime = explode(' ',$company_all_currenttimestamp);
		$currentdate = $datetime[0].' 00:00:01';
		$sql = "SELECT trip_id FROM ".DRIVER_REQUEST_DETAILS." WHERE status = '0' and FIND_IN_SET('$driver_id',available_drivers)  and NOT FIND_IN_SET('$driver_id', rejected_timeout_drivers) and createdate >= '$currentdate' ORDER BY trip_id DESC LIMIT 0 , 1";
		$result = Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
		if(count($result)>0)
		{
			$trip_id = $result[0]['trip_id'];
			//$updatequery = " UPDATE ".DRIVER_REQUEST_DETAILS." SET status='1' where trip_id = '$trip_id'";	
			//$result = Db::query(Database::UPDATE, $updatequery)
				//->execute();			
		}		
		else
		{
			$trip_id = 0;
		}
		return $trip_id;
	}
	/****************** Select driver request ******************/
	public function get_driver_request($trip_id)
	{
				$driver_request = DB::select('available_drivers','rejected_timeout_drivers')->from(DRIVER_REQUEST_DETAILS)
					->where('trip_id','=',$trip_id)				
					->execute()
					->as_array(); 
				if(count($driver_request) >0)
				{
					return $driver_request;
				}
				else
				{
					return 0;
				}
				
	}
	
}
?>
