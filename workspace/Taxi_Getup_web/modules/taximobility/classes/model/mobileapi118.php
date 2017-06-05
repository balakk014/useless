<?php defined('SYSPATH') OR die('No Direct Script Access');

/******************************************

* Contains API model details - Version 6.0.0

* @Package: Taximobility

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************/

Class Model_Mobileapi118 extends Model
{
	public function __construct()
	{
		$this->currentdate=Commonfunction::getCurrentTimeStamp();
	}
	public function search($search)
	{
		echo "API Model";
	}
	//Get Common config
	public function select_site_settings($company_id)
	{

		if($company_id != '')	
		{ 
			$query= "SELECT company_currency as site_currency,company_facebook_key as facebook_key,
company_facebook_secretkey as facebook_secretkey,company_facebook_share as facebook_share,company_twitter_share as twitter_share,cancellation_fare,company_logo as site_logo  FROM ".COMPANYINFO." where company_cid = '$company_id' limit 0,1 ";

		}
		else
		{ 
			$query= "SELECT app_name,site_country,site_currency,facebook_key,
facebook_secretkey,facebook_share,twitter_share,site_logo,passenger_book_notify FROM ".SITEINFO." limit 0,1 ";
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
	public function passenger_login($phone,$pwd,$devicetoken="",$deviceid="",$devicetype="",$company_id="",$country_code="",$fcm_token="") 
	{
		//$password = Html::chars(md5($pwd));
		/*$query= "SELECT 
		id,name,email,profile_image,phone,address,referral_code,creditcard_no,creditcard_cvv,CONCAT(REPEAT('X', CHAR_LENGTH(creditcard_cvv))) AS masked_cvv,expdatemonth,expdateyear,fb_user_id,fb_access_token,user_status,login_from FROM ".PASSENGERS." WHERE phone = '$phone' AND password='$pwd'";*/
		/*$query= "SELECT ".PASSENGERS.".id,".PASSENGERS.".name,".PASSENGERS.".lastname,".PASSENGERS.".salutation,".PASSENGERS.".org_password,".PASSENGERS.".email,".PASSENGERS.".profile_image,".PASSENGERS.".phone,address,".PASSENGERS.".referral_code,".PASSENGERS_CARD_DETAILS.".creditcard_no,".PASSENGERS_CARD_DETAILS.".creditcard_cvv,CONCAT(REPEAT('X', CHAR_LENGTH(".PASSENGERS_CARD_DETAILS.".creditcard_cvv))) AS masked_cvv,".PASSENGERS_CARD_DETAILS.".expdatemonth,".PASSENGERS_CARD_DETAILS.".expdateyear,".PASSENGERS.".fb_user_id,".PASSENGERS.".fb_access_token,".PASSENGERS.".user_status FROM ".PASSENGERS." join ".PASSENGERS_CARD_DETAILS." on  ".PASSENGERS_CARD_DETAILS." .passenger_id = ".PASSENGERS.".id WHERE ".PASSENGERS.".phone = '$phone' AND ".PASSENGERS.".password='$pwd'";*/
		$query= "SELECT   otp,id,name,email,profile_image,phone,country_code,address,referral_code,referral_code_amount,fb_user_id,fb_access_token,user_status,login_from,device_id,device_token,login_status,skip_credit_card,forgot_password,split_fare,fcm_token FROM ".PASSENGERS." WHERE phone = '$phone' AND (country_code = '$country_code' OR country_code = '') AND password='$pwd'";//
		if($company_id == '')
		{
			$query .= "";//" and passenger_cid = 0";
		}				
		else
		{
			$query .= " and passenger_cid ='$company_id'";
		}
		
		//echo $query;//exit;
		$result =  Db::query(Database::SELECT, $query) ->execute()->as_array();
		//echo count($result);exit;		
		if(count($result)>0)
		{
			if($deviceid != "")
			{
				//echo $company_id;
				$update_array = array("device_token" => $devicetoken,"device_id" => $deviceid,"device_type" => $devicetype,"login_status"=>"S","fcm_token"=>$fcm_token );
				$update_device_token_result = DB::update(PASSENGERS)->set($update_array)->where('phone', '=', $phone);
				if(!empty($company_id)){
					$update_device_token_result->where('passenger_cid', '=', $company_id);
				}
				$update_device_token_result->execute();
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
		$condition="";
		if($company_id !=''){
			$condition = "and passenger_cid='$company_id'";   
		}
		$sql = "SELECT count(id) as total FROM ".PASSENGERS." WHERE email='$email' $condition";
		$result=Db::query(Database::SELECT, $sql)->execute()->get('total');
		return $result;
	}	

	// Check Whether Passenger phone is Already Exist or Not
	public function check_phone_passengers($phone="",$company_id="",$country_code="")
	{
		$condition="";
		if($company_id !=''){
			$condition = "and passenger_cid='$company_id'";
		}
		$sql = "SELECT count(id) as total FROM ".PASSENGERS." WHERE phone='$phone' and (country_code = '$country_code' OR country_code = '') $condition";
		$result = Db::query(Database::SELECT, $sql)->execute()->get('total');
		return $result;	
	}

	// Check Whether Passenger phone is Already Exist or Not
	public function check_phone_bypassengers($phone="",$email='',$company_id='',$country_code='')
	{
		$condition="";
		if($company_id !=''){
			$condition = "and passenger_cid='$company_id'";
		}
		$sql = "SELECT count(id) as total FROM ".PASSENGERS." WHERE phone='$phone' and (country_code = '$country_code' OR country_code = '') $condition";
		$result=Db::query(Database::SELECT, $sql)->execute()->get('total');
		return $result;
	}

	// Check Whether People phone is Already Exist or Not
	// Check with all placed before going to edit this function
	public function check_phone_people($phone="",$user_type="",$company_id)
	{
		$condition="";
		if($company_id !=''){
			$condition = "and company_id='$company_id'";
		}
		$sql = "SELECT count(id) as total FROM ".PEOPLE." WHERE phone='$phone' and user_type='$user_type' $condition";
		$result=Db::query(Database::SELECT, $sql)->execute()->get('total');
		return $result;
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


		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time

		
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
		$creditcard_cvv = "";//$val['creditcard_cvv'];
		$expdatemonth = $val['expdatemonth'];
		$expdateyear = $val['expdateyear'];		
		
		
		$query = "insert into ".PASSENGERS."(name,email,password,phone,address,activation_key,activation_status,user_status,created_date,passenger_cid)values('".$name."','".$email."','".$mdpassword."','".$phone."','".$address."','".$random_key."','1','A','".$current_time."','".$cid."')";
		
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
    public function register_facebook_user($accessToken = "",$uid="",$otp="",$referral_code="",$fname="",$lname="",$email="",$image_name = "",$devicetoken="",$device_id="",$devicetype="",$company_id = "",$fcm_token="")
    {


				/** Referrral key generator *
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
		//condition to check whether the facebook user restrict or dont have email id	   
		if($email == '') {
			$fbUserExist = DB::select('email')->from(PASSENGERS)->where('fb_user_id', '=', $uid)->execute()->as_array();
			if(count($fbUserExist) == 0) {
				return 10;
			} else {
				$email = (isset($fbUserExist[0]['email']))  ?  $fbUserExist[0]['email'] : '';
			}
		}
		
        if($company_id !='')
		{	
			$res = DB::select('id')->from(PASSENGERS)->where('email', '=', $email)->where('passenger_cid', '=', $company_id)->execute()->as_array(); 
		}
		else
		{
			$res = DB::select('id')->from(PASSENGERS)->where('email', '=', $email)->execute()->as_array();
		}
		if(count($res) == 0)
		{
			/* below script hided becos in new version During signUp there is OTP verification and referral code So the user has been redirected to register page
			 * //type='distinct' used to get values Upper case alphabets and numbers without zero
			 *
			*/
			$password = text::random($type = 'distinct', $length = 6);
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
			$fieldname_array = array('name','lastname','email','profile_image','password','org_password','otp','phone','address','referral_code','fb_user_id','fb_access_token','activation_key','activation_status','login_from','user_status','login_status','created_date','updated_date','passenger_cid');
			$values_array = array($fname,$lname,$email,$image_name,md5($password),$password,$otp,$uid,'',$referral_code,$uid,$accessToken,$activation_key,'1','3','A','S',$current_time,$current_time,$company_id);
			$result = DB::insert(PASSENGERS, $fieldname_array)
						->values($values_array)
						->execute();
			if($devicetoken != "")
			{
				$update_array = array("device_token" => $devicetoken,"device_id" => $device_id,"device_type" => $devicetype,"fcm_token"=>$fcm_token );
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
				$ress = DB::select(PASSENGERS.'.id')->from(PASSENGERS)->where('email', '=', $email)->where('fb_user_id', '!=', '')->where('fb_access_token', '!=', '')->execute()->as_array(); //->where('passenger_cid','=',0)
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
					$personal_datasql = "SELECT id FROM ".PASSENGERS." WHERE email='$email' and (name = '' or lastname = '') and passenger_cid='$company_id'";   	
				}
				else
				{
					$personal_datasql = "SELECT id FROM ".PASSENGERS." WHERE email='$email' and (name = '' or lastname = '')";   	
				}

				$result=Db::query(Database::SELECT, $personal_datasql)
					->execute()
					->as_array(); 
				//echo '<br>';
				$personal_count = count($result);	
				
				$cardsql = "SELECT count(passenger_cardid) as total FROM ".PASSENGERS_CARD_DETAILS." WHERE passenger_id = '$passenger_id'";
				$cardresult=Db::query(Database::SELECT, $cardsql)->execute()->get('total');
				if($cardresult > 0)
				{					
					$card_count = 1;
				}
				else
				{
					$card_count = 0;
				}	
				//echo $card_count;			
				$update_array = array("login_from" => 3, "login_status" => 'S',"device_token" => $devicetoken,"device_id" => $device_id,"device_type" => $devicetype);
				if($company_id !='')
				{
					$update_device_token_result = DB::update(PASSENGERS)->set($update_array)->where('email', '=', $email)->where('passenger_cid', '=', $company_id)->execute();
				}
				else
				{
					$update_device_token_result = DB::update(PASSENGERS)->set($update_array)->where('email', '=', $email)->execute();	//->where('passenger_cid', '=', 0)
				}
					//echo $counts."-->".$personal_count."-->".$mobile_count."-->".$card_count;exit;
					if(($counts==1) &&($personal_count == 0) &&($mobile_count == 0) && ($card_count == 1))
					{
						return 1;
					}
					else if((($counts==1) &&($mobile_count == 1) && ($personal_count == 1) && ($card_count == 0)) || (($counts==1) &&($mobile_count == 1) && ($personal_count == 0) && ($card_count == 0)) )
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
    public function update_passenger_mobile($email="",$mobile="",$company_id="",$country_code="")
    {
		try {
			$update_array = array("phone" => $mobile,"country_code" => $country_code);
			if($company_id != '')
			{
				$result = DB::update(PASSENGERS)->set($update_array)->where('email', '=', $email)->where('passenger_cid', '=', $company_id)->execute();
			}
			else
			{
				$result = DB::update(PASSENGERS)->set($update_array)->where('email', '=', $email)->execute();
			}

			return 1;

		} 
		catch (Kohana_Exception $e) {
			return -1;			
		}
	}
	//Passenger Profile
	public function passenger_profile($userid,$status='') 
	{		
		$stsCond = (!empty($status)) ? "AND user_status=:status" : "";
		$sql= "SELECT ".PASSENGERS.".wallet_amount,".PASSENGERS.".name,".PASSENGERS.".lastname,".PASSENGERS.".org_password,".PASSENGERS.".password,".PASSENGERS.".salutation,".PASSENGERS.".email,".PASSENGERS.".referral_code,".PASSENGERS.".profile_image,".PASSENGERS.".country_code,".PASSENGERS.".phone,".PASSENGERS.".discount,".PASSENGERS.".user_status,".PASSENGERS.".login_from FROM ".PASSENGERS." WHERE id =:id $stsCond";
		
		//echo $query;
		$query =  Db::query(Database::SELECT, $sql);
		$query->parameters(array(':id' => $userid,':status' => $status));
		$result=$query->execute()
				->as_array();
				
		return $result;	
	}

	/** Save Customer Booking **/
	public function savebooking($val,$company_id)
	{
		$pickup_time = urldecode($val['pickup_time']);
		$roundtrip = $val['roundtrip'];
		$cityname = $val['cityname'];
		
		$approx_distance = $val['approx_distance'];
		$approx_duration = $val['approx_duration'];
		$approx_fare = "";//commonfunction::real_escape_string($val['approx_fare']);
		$pickup_latitude = $val['pickup_latitude'];
		$pickup_longitude = $val['pickup_longitude'];
		$drop_latitude = $val['drop_latitude'];
		$drop_longitude = $val['drop_longitude'];
		$notes_driver = $val['notes'];
		$passenger_app_version = $val['passenger_app_version'];
		$distance_away = (isset($val['distance_away'])) ? $val['distance_away'] : '';
		$promo_code = $val['promo_code'];
		$now_after = $val['now_after'];
		$pre_transaction_id = $val['pre_transaction_id'];
		$pre_transaction_amount = $val['pre_transaction_amount'];
		$update_time = $val['currentTime'];
		$pickupTimezone = $val['pickupTimezone'];

		$sub_logid = $this->get_sublogid($val['sub_logid']);
		$city_id="";

		$pass_company = (!empty($company_id)) ? $company_id : 0;
		
		$city_name = Commonfunction::getCityName($pickup_latitude,$pickup_longitude);
		
		// Get Pickup & Drop location Lat & Long using Google API 
		// Which is used when we calculating approximat fare and distance from api side

		/*$model_query = "select city_id from ".CITY." where city_name like '%".$cityname."%' limit 0,1";	
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
		}*/

		//$get_taxi_fare_based_model = $this->get_current_taxi_details($val["driver_id"],$val["taxi_id"],$city_id);
		$company_tax = "";//$get_taxi_fare_based_model[0]['company_tax'];

		if($roundtrip == 'true'){ $pickupdrop = 1;}else{$pickupdrop = 0;}
		$waitingtime = '';

		$company_id =$this->get_company_id($val['driver_id']);
		if(count($company_id)>0){ $company_id = $company_id[0]['company_id'];}else{$company_id=0;}
		
		//split fare values
		$spliFareArr = array();
		$friend_id1 = (isset($val['friend_id1'])) ? $val['friend_id1'] : '';
		$friend_percentage1 = (isset($val['friend_percentage1'])) ? $val['friend_percentage1'] : '';
		$friend_percentage_amt1 = (isset($val['friend_percentage_amt1'])) ? $val['friend_percentage_amt1'] : '';
		if(!empty($friend_id1))
			$spliFareArr[$friend_id1] = $friend_percentage1."_" .$friend_percentage_amt1;
		
		$friend_id2 = (isset($val['friend_id2'])) ? $val['friend_id2'] : '';
		$friend_percentage2 = (isset($val['friend_percentage2'])) ? $val['friend_percentage2'] : '';
		$friend_percentage_amt2 = (isset($val['friend_percentage_amt2'])) ? $val['friend_percentage_amt2'] : '';
		if(!empty($friend_id2))
			$spliFareArr[$friend_id2] = $friend_percentage2."_" .$friend_percentage_amt2;
			
		$friend_id3 = (isset($val['friend_id3'])) ? $val['friend_id3'] : '';
		$friend_percentage3 = (isset($val['friend_percentage3'])) ? $val['friend_percentage3'] : '';
		$friend_percentage_amt3 = (isset($val['friend_percentage_amt3'])) ? $val['friend_percentage_amt3'] : '';
		if(!empty($friend_id3))
			$spliFareArr[$friend_id3] = $friend_percentage3."_" .$friend_percentage_amt3;
		
		$friend_id4 = (isset($val['friend_id4'])) ? $val['friend_id4'] : '';
		$friend_percentage4 = (isset($val['friend_percentage4'])) ? $val['friend_percentage4'] : '';
		$friend_percentage_amt4 = (isset($val['friend_percentage_amt4'])) ? $val['friend_percentage_amt4'] : '';
		if(!empty($friend_id4))
			$spliFareArr[$friend_id4] = $friend_percentage4."_" .$friend_percentage_amt4;
		 
		
		//echo $company_id;exit;
		/*if($company_id == '')
		{
			//echo TIMEZONE;exit;
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
			$company_time_zone = isset($model_fetch[0]['time_zone'])?$model_fetch[0]['time_zone']:0;
			if($company_time_zone != 0)
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
		} */
		
		//echo $update_time;exit;
		$booking_key = text::random($type = 'alnum', $length = 10);
		$isSplit = (count($spliFareArr) > 1) ? 1:0;

		$fieldname_array = array('passengers_id','driver_id','company_id','current_location','pickup_latitude','pickup_longitude','city_name','drop_location','drop_latitude','drop_longitude','no_passengers','approx_distance','approx_fare','time_to_reach_passen','pickup_time','pickupdrop','waitingtime','createdate','taxi_id','booking_from','search_city','sub_logid','notes_driver','booking_from_cid','company_tax','bookingtype','bookby','promocode','now_after','passenger_app_version','pre_transaction_id','pre_transaction_amount','is_split_trip','trip_timezone','taxi_modelid');
		//'pickup','drop','no_of_passengers','pickup_time','driver_id','roundtrip','taxi_id','passenger_id'
		$values_array = array($val['passenger_id'],$val['driver_id'],$company_id,urldecode($val['pickupplace']),$pickup_latitude,$pickup_longitude,$city_name,urldecode($val['dropplace']),$drop_latitude,$drop_longitude,'',$approx_distance,$approx_fare,$distance_away,$update_time,$pickupdrop,$waitingtime,$this->currentdate,$val['taxi_id'],'1',$city_id,$sub_logid,$notes_driver,$company_id,$company_tax,'1','1',$promo_code,'0',$passenger_app_version,$pre_transaction_id,$pre_transaction_amount,$isSplit,$pickupTimezone,$val['motor_model']);
		//}


//'pickup_latitude','pickup_longitude','drop_latitude','drop_longitude'
		$driver_availability_result = $this->get_driver_availability($val['driver_id'],$update_time);


		if(count($driver_availability_result) == 0)
		{
			if($now_after == 0)
			{
				$result = DB::insert(PASSENGERS_LOG, $fieldname_array)
							->values($values_array)
							->execute();
			}
			else
			{
				$pickup_time = date("Y-m-d H:i:s",strtotime($pickup_time));
				$result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','city_name','drop_location','drop_latitude','drop_longitude',
	'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','search_city','notes_driver','faretype','fixedprice','bookingtype','luggage','bookby','operator_id'
	,'travel_status','taxi_modelid','recurrent_type','company_tax','promocode','now_after','passenger_app_version','pre_transaction_id','pre_transaction_amount','is_split_trip','trip_timezone'))
				->values(array($booking_key,$val['passenger_id'],$pass_company,urldecode($val['pickupplace']),$pickup_latitude,$pickup_longitude,$city_name,urldecode($val['dropplace']),$drop_latitude,$drop_longitude,$pickup_time,'',$approx_distance,$approx_duration,$approx_fare,$city_id,$notes_driver,'','','2','0','1','0','0',$val['motor_model'],'0',$company_tax,$promo_code,'1',$passenger_app_version,$pre_transaction_id,$pre_transaction_amount,$isSplit,$pickupTimezone))
				->execute();//$company_id
			}

			if($sub_logid =='' || $sub_logid =='0' )
			{
				$update_pass_logid= DB::update(PASSENGERS_LOG)->set(array('sub_logid' =>$result[0] ))
				->where('passengers_log_id','=',$result[0])
				->execute();	
			}

			if($result)
			{
				/** Update Promocode used count individual user **/
				if($promo_code != "") 
				{
					$cmModel = Model::factory('commonmodel');
					$cmModel->promocode_used_update($promo_code,$val['passenger_id']);
				}
$passengerPaymentOption = empty($val["passenger_payment_option"]) ? 0 : $val["passenger_payment_option"];
				if(count($spliFareArr) > 0) {
					foreach($spliFareArr as $key => $values)
					{
						$approve_status = ($val['passenger_id']==$key) ? 'A' : 'I';
						$perctvalue=explode('_',$values);
						$friend_percent=isset($perctvalue[0])?$perctvalue[0]:"";
						$friend_amnt=isset($perctvalue[1])?$perctvalue[1]:"";
						$fieldname_array = array('trip_id','friends_p_id','fare_percentage','createdate','approve_status','appx_amount','passenger_payment_option');
						$values_array = array($result[0],$key,$friend_percent,$update_time,$approve_status,round($friend_amnt,2),$passengerPaymentOption);
						if($key != 0)
						{
							$split_insert_result = DB::insert(P_SPLIT_FARE, $fieldname_array)->values($values_array)->execute();
						}
					}
				} else {
					//for entry in split fare table while book later from iOS device
					if($now_after != 0) {
						$fieldname_array = array('trip_id','friends_p_id','fare_percentage','createdate','approve_status','appx_amount',"passenger_payment_option");
						$values_array = array($result[0],$val['passenger_id'],'100',$update_time,'A',round($approx_fare,2),$passengerPaymentOption);
						$split_insert_result = DB::insert(P_SPLIT_FARE, $fieldname_array)->values($values_array)->execute();
					}
				}
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
		$sql = "SELECT company_id FROM ".PEOPLE." WHERE `id` = '".$driver_id."'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();
	}
	
	/** Mark as favourite Trip**/
	public function set_markfav_tripdetails($pass_log_id,$mark_status)
	{
		 $update_result =  DB::update(PASSENGERS_LOG)->set(array('favourite_trip'=>$mark_status))->where('passengers_log_id', '=' ,$pass_log_id)->execute();
		return $update_result;
	}
	/** Driver availability **/
	public function get_driver_availability($driver_id,$pickup_time)
	{
		$sql = "SELECT passengers_log_id FROM ".PASSENGERS_LOG." WHERE `pickup_time` = '".$pickup_time."' and `driver_id` = '".$driver_id."' and `driver_reply` = 'A' and 'travel_status' = 9";
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
				$creditcard_cvv = "";//$array['creditcard_cvv'];
				$expdatemonth = $array['expdatemonth'];
				$expdateyear = $array['expdateyear'];
				$id = $array['id'];
				
				//$sql = "UPDATE `passengers` SET `name` = '$name', `address` = '$address', `creditcard_no` = '$creditcard_no', `creditcard_cvv` = '$creditcard_cvv', `expdatemonth` = '$expdatemonth', `expdateyear` = '$expdateyear' WHERE `id` = '$id' AND `passenger_cid` = '$company_id'";
				
				$sql = "UPDATE `passengers` SET `name` = '$name', `address` = '$address' WHERE `id` = '$id' ";
				$result =  Db::query(Database::UPDATE, $sql)
				->execute();
				$sql = "UPDATE ".PASSENGERS_CARD_DETAILS." SET `creditcard_no` = '$creditcard_no', `expdatemonth` = '$expdatemonth', `expdateyear` = '$expdateyear' WHERE `passenger_id` = '$id' ";
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
				$creditcard_cvv = "";//$array['creditcard_cvv'];
				$expdatemonth = $array['expdatemonth'];
				$expdateyear = $array['expdateyear'];
				$id = $array['id'];
				
				$sql = "UPDATE `passengers` SET `name` = '$name', `address` = '$address' WHERE `id` = '$id'";
				
				$result =  Db::query(Database::UPDATE, $sql)
				->execute();
				
				$sql = "UPDATE ".PASSENGERS_CARD_DETAILS." SET `creditcard_no` = '$creditcard_no', `expdatemonth` = '$expdatemonth', `expdateyear` = '$expdateyear' WHERE `passenger_id` = '$id'";
				
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
	public function chg_password_passenger($array,$company_id='',$type='')
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

						$result = DB::update(PEOPLE)
						->set(array('password' => md5($array['new_password']),'org_password'=>$array['new_password']))
						->where('id', '=', $array['id'])
						->execute();

	
				   }	
				   else
				   {	
							$result = DB::update(PASSENGERS)
							->set(array('password' => md5($array['new_password']),'org_password'=>$array['new_password']))
							->where('id', '=', $array['id']);
							if($company_id != ''){
								$result->where('passenger_cid', '=', $company_id);
							}
							$result->execute();
						
						//$result = DB::update($table)->set(array('password' => $array['new_password'])->where('id', '=', $array['id'])->execute();


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
		 $update_result =  DB::update(PASSENGERS_LOG)->set(array('comments'=>$comments,'rating'=>$ratings))->where('passengers_log_id', '=' ,$log_id)->execute();
		//to get the driver id of particular trip
		$driver_id=DB::select('driver_id')->from(PASSENGERS_LOG)->where('passengers_log_id', '=' ,$log_id)->execute()->get('driver_id');
		return $driver_id;
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
		$result=DB::select('status','id','shift_status','driver_id','latitude','longitude','update_date')->from(DRIVER)->where('driver_id',"=",$driverid)->execute()->as_array();		
		return $result;
	}
	
	//Driver Profile
	public function driver_profile($userid) 
	{		
		//$query= "SELECT salutation,name,".PEOPLE.".id as userid,lastname,email,phone,address,password,otp,photo,device_type,device_token,login_status,user_type,driver_referral_code,notification_setting,company_id,driver_license_id,profile_picture,".COMPANY.".bankname,".COMPANY.".bankaccount_no,".COMPANY.".userid as company_ownerid FROM ".PEOPLE." JOIN  ".COMPANY." ON (  ".PEOPLE.".`company_id` =  ".COMPANY.".`cid` )  WHERE id = '$userid' AND user_type = 'D' ";	
		$query = "SELECT salutation,name,".PEOPLE.".id as userid,lastname,email,country_code,phone,address,password,otp,photo,device_type,device_token,login_status,user_type,driver_referral_code,notification_setting,company_id,driver_license_id,account_balance,profile_picture,".COMPANY.".bankname,".COMPANY.".bankaccount_no,".COMPANY.".userid as company_ownerid,".TAXI.".taxi_no,".TAXIMAPPING.".mapping_startdate,".TAXIMAPPING.".mapping_enddate,".MOTORMODEL.".model_name,IF(".DRIVER_REF_DETAILS.".registered_driver_wallet,".DRIVER_REF_DETAILS.".registered_driver_wallet,0) as driver_wallet_amount FROM ".PEOPLE." LEFT JOIN  ".COMPANY." ON (  ".PEOPLE.".`company_id` =  ".COMPANY.".`cid` ) LEFT JOIN ".TAXIMAPPING." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id LEFT JOIN " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id LEFT JOIN ".MOTORMODEL." on ".TAXI.".taxi_model = ".MOTORMODEL.".model_id LEFT JOIN ".DRIVER_REF_DETAILS." on ".DRIVER_REF_DETAILS.".registered_driver_id = ".PEOPLE.".id  WHERE id = '$userid' AND user_type = 'D' AND ".TAXIMAPPING.".mapping_status = 'A'";
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
			$query= "SELECT account_balance,status,login_status,login_from,device_token,device_id,id,company_id,driver_first_login,fcm_token,(select CONCAT(pl.country_code,pl.phone) as company_phone from people as pl where pl.company_id= pls.company_id and user_type='C' ) as company_phone FROM ".PEOPLE." as pls WHERE pls.phone = '$phone' AND pls.password='$pwd' AND pls.user_type = 'D' and pls.company_id='$company_id'";
		}	
		else
		{
			$query= "SELECT account_balance,status,login_status,login_from,device_token,device_id,id,company_id,driver_first_login,fcm_token,(select CONCAT(pl.country_code,pl.phone) as company_phone from people as pl where pl.company_id= pls.company_id and user_type='C' ) as company_phone FROM ".PEOPLE."  as pls WHERE pls.phone = '$phone' AND pls.password='$pwd' AND pls.user_type = 'D' ";
		}	
		//echo $query;
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
			$query= "SELECT id FROM ".PEOPLE." WHERE id = '$driver_id' AND user_type = 'D' and company_id='$company_id'";
		}	
		else
		{
			$query= "SELECT id FROM ".PEOPLE." WHERE id = '$driver_id' AND user_type = 'D'";
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
			$query= "SELECT COUNT(id) as count FROM ".PASSENGERS." WHERE id = '$id' and passenger_cid='$company_id'";
		}	
		else
		{
			$query= "SELECT COUNT(id) as count FROM ".PASSENGERS." WHERE id = '$id'";
		}
		//$query= "SELECT * FROM ".PASSENGERS." WHERE id = '$id'";
		$result =  Db::query(Database::SELECT, $query)->execute()->get('count');
		//print_r($result); exit;
		return $result;
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
			
		//print_r($result);exit;
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
	public function update_driverreply_status($id,$driver_id,$taxi_id,$company_id,$status,$travel_status,$field,$flag,$default_companyid)
	{

		$driver_reply = '';
		$driver_db_id = '';

		$cancel_datetime = date('Y-m-d H:i:s', strtotime("+2 minutes"));

		$data = DB::select('driver_reply','driver_id','cancel_status')->from(PASSENGERS_LOG)
						->where(PASSENGERS_LOG.'.passengers_log_id','=',$id)
						->as_object()
						->execute();

		foreach($data as $values)
		{
			$driver_reply = $values->driver_reply;
			$driver_db_id = $values->driver_id;
			$cancel_status = $values->cancel_status;
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
					//'time_to_reach_passen' => $field,
					'msg_status' =>'R'
				);
		}
		//Rejected Status and Adding the Driver Comments 
		else
		{	
			if($cancel_status == 0 && $status == 'C' )
			{
				$sql_query = array(
					'travel_status' => $travel_status,
					'driver_reply' => $status,
					'driver_id'=> $driver_id,
					'taxi_id'=> $taxi_id,
					'company_id'=> $company_id,
					'driver_comments' => $field,
					'msg_status' =>'R',
					'cancel_datetime' => $cancel_datetime,
					'cancel_status' => '1'
				);

			}
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

		}

		if($driver_reply == '')
		{
			$update_result = DB::update(PASSENGERS_LOG)->set($sql_query)
					->where('passengers_log_id', '=' ,$id)
					->execute();

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

				if($cancel_status == 0 && $status == 'C' )
				{
					$sql_query = array(
						'travel_status' => '9',
						'driver_reply' => $status,
						'driver_comments' => $field,
						'msg_status' =>'R',
						'cancel_datetime' => $cancel_datetime,
						'cancel_status' => '1'
					);
				}
				else
				{
					$sql_query = array(
						'travel_status' => '9',
						'driver_reply' => $status,
						'driver_comments' => $field,
						'msg_status' =>'R'

					);

				}		
				$update_result = DB::update(PASSENGERS_LOG)->set($sql_query)
					->where('passengers_log_id', '=' ,$id)
					->execute();

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
					if($driver_db_id == $driver_id) {
						return 5; // driver already confirmed
					} else {
						return 10;
					}
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
		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time


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


		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time


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

		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time

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
		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time

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
		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time
		
		/*$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `pickup_time` < '".$pickup_time."'  and `driver_id` = '".$driver_id."' and `driver_reply` = 'A' and `travel_status` != 1 order by passengers_log_id desc limit 1 "; */					
		$condition="";
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'"; }
		
		if($company_id != '')	
		{	
			$sql = "SELECT drop_location,pickup_latitude,pickup_longitude,drop_latitude,drop_longitude FROM ".PASSENGERS_LOG." WHERE `driver_id` = '".$driver_id."' and company_id='$company_id' and `driver_reply` = 'A' and `travel_status` = 2 $condition order by passengers_log_id desc limit 1 ";
		}
		else
		{
			$sql = "SELECT drop_location,pickup_latitude,pickup_longitude,drop_latitude,drop_longitude FROM ".PASSENGERS_LOG." WHERE `driver_id` = '".$driver_id."' and `driver_reply` = 'A' and `travel_status` = 2 $condition order by passengers_log_id desc limit 1 ";
		}	

		$availablity = Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
			
		return $availablity	;
	}
	/** Get Motor Company **/
	public static function motor_details()
	{
			$result = DB::select('motor_id','motor_name','motor_status')->from(MOTORCOMPANY)->where('motor_status','=','A')->order_by('motor_name','ASC')
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
			$result = DB::select('driver_id','latitude','longitude','status')->from(DRIVER)->where('driver_id','=',$driver_id)
				->execute()
				->as_array();		
			return $result;
	}
	
	/** Get Passenger Details
	public function passenger_details($id,$company_id)
	{
		$query = "SELECT * FROM ".PASSENGERS." WHERE id = '$id' ";
		
		//$query= "SELECT *, CONCAT(REPEAT('X', CHAR_LENGTH(creditcard_no) - 4),SUBSTRING(creditcard_no, -4)) AS masked_card,creditcard_cvv,CONCAT(REPEAT('X', CHAR_LENGTH(creditcard_cvv))) AS masked_cvv FROM ".PASSENGERS." WHERE id = '$id' ";

		/*if($company_id != '')
		{
			$query .= " and passenger_cid = '$company_id'";
		}
		else
		{
			$query .= " and passenger_cid = '0'";
		}
		*
		//echo $query;exit;
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		
		return $result;
	} */

	public function passenger_detailsbyemail($email,$company_id)
	{

		$query= "SELECT id,email,profile_image,salutation,name,lastname,country_code,phone,org_password,address,referral_code,referral_code_amount,otp,fb_user_id,fb_access_token,user_status,login_from,device_id,device_token,skip_credit_card,split_fare FROM ".PASSENGERS." WHERE email = '$email' ";

		if($company_id != '')
		{
			$query .= " and passenger_cid = '$company_id'";
		}
		/*else
		{
			$query .= " and passenger_cid = 0";
		} */
		

		//echo $query;exit;
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
        //echo 'as'.$result[0]['creditcard_no'];exit;
		/*if($result[0]['creditcard_no'] != "")
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
		$result = DB::select()->from(PASSENGERS)->where('email','=',$email)->execute()->as_array();
		return $result;
	}
	
	//Forgot Password for Both Driver and Passenger
	public function forgot_password($array,$password)
	{
		
		if($array['user_type'] == 'P')
		{
			$result = DB::update(PASSENGERS)
			->set(array('password' => Html::chars(md5($password))))
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
			->set(array('password' => Html::chars(md5($password))))
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
				$sql = "SELECT * ,  ".PEOPLE.".name AS driver_name,".PEOPLE.".phone AS driver_phone, ".PASSENGERS.".discount AS passenger_discount, ".PASSENGERS.".name AS passenger_name,".PASSENGERS.".email AS passenger_email,".PASSENGERS.".phone AS passenger_phone FROM  ".PASSENGERS_LOG." 
				JOIN  ".COMPANY." ON (  ".PASSENGERS_LOG.".`company_id` =  ".COMPANY.".`cid` ) 
				JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
				JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) 
				
				WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$passengerlog_id' $company_condition";
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}

		/*** Get  Passenger Completed Trip Log **/
	public function get_passenger_log_details($userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null,$company_id)
	{

		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time


		$condition="";
		if($createdate == 0){ $condition = "AND pg.pickup_time >='".$start_time."'"; }
		/*$sql = "SELECT *,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername  FROM ".PASSENGERS_LOG." as pg LEFT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition  order by pg.passengers_log_id desc LIMIT $start,$limit";     */

/*if($start == 0 && $limit ==0)
{*/

		$company_condition="";
		if($company_id != ""){
			$company_condition = " AND pg.company_id = '$company_id'";
		}
		
		$sql = "SELECT *,pg.passengers_log_id,
		(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername,
		(select pay_mod_name from ".PAYMENT_MODULES." where pay_mod_id=t.payment_type) as payment_name,
		pg.passengers_log_id as pass_log_id,t.company_tax as tax_amount,pg.company_tax as tax_percentage,		
		FROM ".PASSENGERS_LOG." as pg 
		RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  
		WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition $company_condition order by pg.passengers_log_id desc LIMIT $start,$limit";

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

		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time

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
				$sql = "SELECT taxi_model FROM  ".TAXI."  WHERE  `taxi_id` =  '$taxi_id'";
				$result = Db::query(Database::SELECT, $sql)					
					->execute()			
					->as_array(); 								               
				return $result;		
	}

	/*** Get Taxi fare per KM & Waiting charge of the company based Company***/
	public function get_model_fare_details($company_id,$model_id="",$search_city="",$brand_type="")
	{

		if($search_city!='')
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".city_id ='".$search_city."'"; 
		}		
		else
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1"; 
		}

		$model_fetch = Db::query(Database::SELECT, $model_base_query)->execute()->as_array();
		$city_model_fare = (count($model_fetch) > 0) ? $model_fetch[0]['city_model_fare'] : 0;

		if(FARE_SETTINGS == 2 )
		{
			$sql = "SELECT (SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km,(SUM(company_model_fare.minutes_fare)*($city_model_fare)/100) + company_model_fare.minutes_fare as minutes_fare,company_model_fare.night_charge,company_model_fare.night_timing_from,company_model_fare.night_timing_to,(SUM(company_model_fare.night_fare)*($city_model_fare)/100) + company_model_fare.night_fare as night_fare,company_model_fare.evening_charge,company_model_fare.evening_timing_from,company_model_fare.evening_timing_to,(SUM(company_model_fare.evening_fare)*($city_model_fare)/100) + company_model_fare.evening_fare as evening_fare,
company_model_fare.waiting_time,company_model_fare.min_km,company_model_fare.below_above_km 
FROM  ".COMPANY_MODEL_FARE." as company_model_fare WHERE  ".COMPANY_MODEL_FARE.".`model_id` = '$model_id' and ".COMPANY_MODEL_FARE.".`company_cid`= '$company_id'";
		}
		else
		{
			$sql = "SELECT (SUM(model.base_fare)*($city_model_fare)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*($city_model_fare)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*($city_model_fare)/100) + model.below_km as below_km,(SUM(model.above_km)*($city_model_fare)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*($city_model_fare)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*($city_model_fare)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*($city_model_fare)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km 
			FROM  ".MOTORMODEL." as model WHERE  model.`model_id` = '$model_id'";			
		}
//echo $sql;
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();								               
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
		$ratings['comments'] = DB::select('rating','comments')->from(PASSENGERS_LOG)	
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
		$result_query = "select company.company_name,company.cid,(SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as cancellation_nfree,(select company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as company_tax,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km,company_model_fare.night_charge,company_model_fare.night_timing_to,company_model_fare.night_fare,company_model_fare.min_km,company_model_fare.below_above_km,motor_model.model_name,taxi.taxi_id,taxi.taxi_no,taxi.taxi_type,taxi.taxi_model,taxi.taxi_company,taxi.taxi_capacity,taxi.taxi_speed,taxi.max_luggage,taxi.taxi_image,taxi.taxi_serializeimage  from ". TAXI." 
		join ".COMPANY."  ON taxi.taxi_company=company.cid 
		JOIN ".MOTORMODEL."  ON taxi.`taxi_model`=motor_model.model_id 
		JOIN ".COMPANY_MODEL_FARE." as company_model_fare ON taxi.taxi_model = company_model_fare.model_id
		where company_model_fare.company_cid=company.cid  and taxi_id=$taxi_id";
		}
		else
		{
			$result_query = "select company.company_name,company.cid,(SUM(motor_model.base_fare)*($city_model_fare)/100) + motor_model.base_fare as base_fare,(SUM(motor_model.min_fare)*($city_model_fare)/100) + motor_model.min_fare as min_fare,(SUM(motor_model.cancellation_fare)*($city_model_fare)/100) + motor_model.cancellation_fare as cancellation_fare,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as cancellation_nfree,(select company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as company_tax,(SUM(motor_model.below_km)*($city_model_fare)/100) + motor_model.below_km as below_km,(SUM(motor_model.above_km)*($city_model_fare)/100) + motor_model.above_km as above_km,motor_model.night_charge,motor_model.night_timing_to,motor_model.night_fare,motor_model.min_km,motor_model.below_above_km,motor_model.model_name,taxi.* from ". TAXI." 
			join ".COMPANY."  ON taxi.taxi_company=company.cid 
			JOIN ".MOTORMODEL."  ON taxi.taxi_model=motor_model.model_id where taxi_id=$taxi_id";
		}		
		
		//echo $result_query;					

		$result = Db::query(Database::SELECT, $result_query)
				->execute()
				->as_array();

		
		return array_merge($result,$ratings);
	}

	public function passengerlogid_details($log_id)	
	{
		$sql = "SELECT pg.passengers_id, pg.driver_id, pg.taxi_modelid, pg.company_id, pg.passengers_id, pg.pickup_time, pg.current_location as pickupLocation, pg.drop_location as dropLocation, pg.pickup_latitude, pg.pickup_longitude, pg.drop_latitude, pg.drop_longitude, pg.search_city, pg.taxi_id, pg.pre_transaction_id, pg.pre_transaction_amount, pg.used_wallet_amount,dloc.active_record,pe.name as driver_name,pe.email as driver_email,pe.phone as driver_phone,pe.device_token as driver_devicetoken,pg.search_city,pg.taxi_id,CONCAT(p.country_code,p.phone) as passenger_phone, p.name as passenger_name, p.lastname as passenger_lastname, p.email as passenger_email FROM ".PASSENGERS_LOG." as pg 
		left join ".DRIVER_LOCATION_HISTORY." as dloc on dloc.trip_id=pg.passengers_log_id  
		left join ".PEOPLE." as pe on pg.driver_id=pe.id left join ".PASSENGERS." as p on p.id=pg.passengers_id WHERE passengers_log_id = '$log_id'";

		return Db::query(Database::SELECT, $sql)->execute()->as_array(); 

	}

	public function passenger_transdetails($log_id)	
	{
	
		$sql = "SELECT pl.passengers_log_id,pl.booking_key,pl.passengers_id,pl.driver_id,pl.taxi_id,pl.company_id,pl.current_location,pl.pickup_latitude,pl.pickup_longitude,pl.drop_location,pl.drop_latitude,pl.drop_longitude,pl.no_passengers,pl.approx_distance,pl.approx_duration,pl.approx_fare,pl.time_to_reach_passen,pl.pickup_time,pl.dispatch_time,pl.pickupdrop,pl.rating,pl.comments,pl.travel_status,pl.driver_reply,pl.createdate,pl.booking_from,pl.company_tax,pl.faretype,pl.bookingtype,pl.driver_comments,(pl.drop_time - pl.actual_pickup_time) as travel_time,
				
				t.id as job_referral,t.distance,t.actual_distance,t.tripfare,t.fare,t.tips,t.waiting_time,t.waiting_cost,t.company_tax as tax_amount,t.amt,t.passenger_discount,t.account_discount,t.credits_used,t.transaction_id,t.payment_type,t.payment_status,t.admin_amount,t.company_amount,t.nightfare_applicable,t.nightfare,t.eveningfare_applicable,t.eveningfare,t.trans_packtype,t.waiting_time,t.minutes_fare,t.trip_minutes,	pl.used_wallet_amount, p.name as passenger_name,p.email as 
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
		$sql = "SELECT admin_commission,referral_discount,currency_format,referral_amount,referral_settings,wallet_amount1,wallet_amount2,wallet_amount3,wallet_amount_range  FROM ".SITEINFO;
		return Db::query(Database::SELECT, $sql)->execute()->as_array();
	}


	public function triptransact_details($details,$payment_types,$driver_id = 0)
	{	

			$first_query = "select ".PACKAGE_REPORT.".check_package_type from " . PACKAGE_REPORT . " join ".PACKAGE." on ".PACKAGE.".package_id =".PACKAGE_REPORT.".upgrade_packageid  where ".PACKAGE_REPORT.".upgrade_companyid = ".$details['company_id']."  order by upgrade_id desc limit 0,1";

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
				if(ADMIN_COMMISION_SETTING) {
					$updatequery = " UPDATE ". PEOPLE ." SET account_balance=account_balance+$admin_amt WHERE user_type = 'A'";
					$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
				}
			}
			else
			{
				$admin_amt = 0;
			}

			$company_amt = $details['fare'] - $admin_amt; 	
			$company_amt = round($company_amt, 2);	

			//Set Commission to Company
			if(COMPANY_COMMISION_SETTING) {
				$updatequery = " UPDATE ". PEOPLE ." SET account_balance=account_balance+$company_amt WHERE user_type = 'C' and company_id=".$details['company_id'];
				$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
			}
			$driver_commission_amt = 0;
			//Set Commission to Driver
			if(DRIVER_COMMISION_SETTING && $driver_id > 0) {
				$driver_com_query = "select driver_commission from ".COMPANY." where cid =".$details['company_id'];
				$driver_com_result = Db::query(Database::SELECT, $driver_com_query)->execute()->as_array();
				$driver_commission = isset($driver_com_result[0]['driver_commission']) ? $driver_com_result[0]['driver_commission'] : 0;
				$driver_commission_amt = round(($company_amt*$driver_commission/100),2);
				$company_bal_amt = $company_amt-$driver_commission_amt;
				$updatequery = " UPDATE ". PEOPLE ." SET account_balance = account_balance-$driver_commission_amt WHERE user_type = 'D' and id = '$driver_id'";
				$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
			}

			$current_time = date('Y-m-d H:i:s');
			$details['CORRELATIONID']=isset($details['CORRELATIONID'])?$details['CORRELATIONID']:'';
			$details['ACK']=isset($details['ACK'])?$details['ACK']:'1';
			$details['CURRENCYCODE']=isset($details['CURRENCYCODE'])?$details['CURRENCYCODE']:'';
			
			$result = DB::insert(TRANS, array('passengers_log_id','distance','actual_distance','distance_unit','tripfare','fare','base_fare','tips','waiting_cost','waiting_time','company_tax','trip_minutes','minutes_fare','passenger_discount','account_discount','credits_used','remarks','correlation_id','ack','transaction_id','payment_type','payment_method','order_time','amt','currency_code','payment_status','captured','admin_amount','company_amount','driver_amount','trans_packtype','nightfare_applicable','nightfare','payment_gateway_id','fare_calculation_type','tax_percentage','eveningfare','eveningfare_applicable'))
			->values(array($details['passengers_log_id'],$details['distance'],$details['actual_distance'],$details['distance_unit'],$details['tripfare'],$details['fare'],$details['base_fare'],$details['tips'],$details['waiting_cost'],$details['waiting_time'],$details['company_tax'],$details['trip_minutes'],$details['minutes_fare'],$details['passenger_discount'],$details['account_discount'],$details['credits_used'],$details['remarks'],$details['CORRELATIONID'],$details['ACK'],$details['TRANSACTIONID'],$details['payment_type'],$details['payment_method'],$current_time,$details['amt'],$details['CURRENCYCODE'],$details['ACK'],'1',$admin_amt,$company_amt,$driver_commission_amt,$check_package_type,$details['nightfare_applicable'],$details['nightfare'],$payment_types,$details['fare_calculation_type'],$details['tax_percentage'],$details['eveningfare'],$details['eveningfare_applicable']))
			->execute();
			

			return $result; 
	}




	public function cancel_triptransact_details($details,$cancellation_nfree,$payment_types,$driver_id = 0)
	{
		if($cancellation_nfree != 0 && !empty($driver_id))
		{
			$first_query = "select ".PACKAGE_REPORT.".check_package_type from " . PACKAGE_REPORT . " join ".PACKAGE." on ".PACKAGE.".package_id =".PACKAGE_REPORT.".upgrade_packageid  where ".PACKAGE_REPORT.".upgrade_companyid = ".$details['company_id']."  order by upgrade_id desc limit 0,1";
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
				if(ADMIN_COMMISION_SETTING) {
					$updatequery = " UPDATE ". PEOPLE ." SET account_balance = account_balance+$admin_amt wHERE user_type = 'A'";	
					$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
				}
			}
			else
			{
				$admin_amt = 0;
			}			

			//$company_amt = $details['total_fare'] - $admin_amt; 	
			$company_amt = $details['total_fare'];
			$company_amt = round($company_amt, 2);	

			//Set Commission to Company
			if(COMPANY_COMMISION_SETTING) {
				$updatequery = " UPDATE ". PEOPLE ." SET account_balance = account_balance+$company_amt wHERE user_type = 'C' and company_id=".$details['company_id'];
				$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
			}
			
			//Set Commission to Driver
			if(DRIVER_COMMISION_SETTING && $driver_id > 0) {
				$driver_com_query = "select driver_commission from ".COMPANY." where cid =".$details['company_id'];
				$driver_com_result = Db::query(Database::SELECT, $driver_com_query)->execute()->as_array();
				$driver_commission = isset($driver_com_result[0]['driver_commission']) ? $driver_com_result[0]['driver_commission'] : 0;
				$driver_commission_amt = round(($company_amt*$driver_commission/100),2);
				$company_bal_amt = $company_amt-$driver_commission_amt;
				$updatequery = " UPDATE ". PEOPLE ." SET account_balance = account_balance-$driver_commission_amt WHERE user_type = 'D' and id = '$driver_id'";
				$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
			}

			if($details['travel_status'] == 4)
			{
			$updatequery = " UPDATE ". PASSENGERS_LOG ." SET comments='".$details['remarks']."', travel_status='".$details['travel_status']."' WHERE passengers_log_id=".$details['passenger_log_id'];	

			$updateresult = Db::query(Database::UPDATE, $updatequery)
			->execute();

			}
			$current_time = date('Y-m-d H:i:s');
			$details['CORRELATIONID']=isset($details['CORRELATIONID'])?$details['CORRELATIONID']:'';
			$details['ACK']=isset($details['ACK'])?$details['ACK']:'1';
			$details['CURRENCYCODE']=isset($details['CURRENCYCODE'])?$details['CURRENCYCODE']:'';
			
			$result = DB::insert(TRANS, array('passengers_log_id','fare','remarks','correlation_id','ack','transaction_id',
'payment_type','order_time','amt','currency_code','payment_status','captured','admin_amount','company_amount','trans_packtype','payment_gateway_id'))
			->values(array($details['passenger_log_id'],$details['total_fare'],urldecode($details['remarks']),$details['CORRELATIONID'],$details['ACK'],$details['TRANSACTIONID'],$details['pay_mod_id'],$current_time,$details['total_fare'],$details['CURRENCYCODE'],$details['ACK'],'1',$admin_amt,$company_amt,$check_package_type,$payment_types))
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
		$sql = "SELECT pl.travel_status,pl.driver_id,t.id,pl.passengers_id FROM ".PASSENGERS_LOG." as pl join ".TRANS." as t on 
				t.passengers_log_id=pl.passengers_log_id  WHERE t.passengers_log_id = '$log_id' $condition limit 1";
		$result =  Db::query(Database::SELECT, $sql)->execute()->as_array(); 
		$responseRes = ($flag == 1) ? count($result) : $result;
		return $responseRes;
	}

	public function check_travelstatus($log_id)
	{
		$sql = "SELECT travel_status,driver_reply FROM ".PASSENGERS_LOG." WHERE passengers_log_id = '$log_id' ";

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
			$sql= "SELECT salutation,name,lastname,email,address,password,otp,photo,device_type,device_token,device_id,phone,login_status,user_type,driver_referral_code,notification_setting,company_id,driver_license_id,profile_picture,".COMPANY.".bankname,".COMPANY.".userid,".COMPANY.".bankaccount_no FROM ".PEOPLE." JOIN  ".COMPANY." ON (  ".PEOPLE.".`company_id` =  ".COMPANY.".`cid` )  WHERE id = '$id' and company_id='$company_id' AND user_type = 'D' ";
		}
		else
		{
			$sql= "SELECT salutation,name,lastname,email,address,password,otp,photo,device_type,device_token,device_id,phone,login_status,user_type,driver_referral_code,notification_setting,company_id,driver_license_id,profile_picture,".COMPANY.".bankname,".COMPANY.".userid,".COMPANY.".bankaccount_no FROM ".PEOPLE." JOIN  ".COMPANY." ON (  ".PEOPLE.".`company_id` =  ".COMPANY.".`cid` )  WHERE id = '$id' AND user_type = 'D' ";
		}
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}

	public function save_driver_location_history($location_array,$default_companyid)
	{	
		$get_company_time_details = $this->get_company_time_details($default_companyid);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time
		
		$driver_id = $location_array['driver_id'];
		$trip_id = $location_array['trip_id'];
		$status = $location_array['status'];

		$location_record_array = explode('|',$location_array['locations']);
		
		//print_r($location_record_array);

		$location_record = '';	
		foreach($location_record_array as $key=>$value)
		{
			if($value !=""){
				$location_record .='['.$value.']'.',';
				//echo $location_record;
			}
		}//exit;
		//$location_record = '['.$location_array['locations'].']';
		if($driver_id!="")
		{
			if($trip_id != '')
			{					
				$sql = "SELECT active_record,distance FROM driver_location_history WHERE trip_id = '$trip_id'";
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
							$result[0]=1;
							$result[1]=0;
							return $result;
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
							// find last lat and lon
							$pickup_location = str_replace(']','',end($active_record));
							$pickup_location = str_replace('[','',$pickup_location);
							// Get the pickup lat,lon from existing record( Last lat lon)
							$pickup_location = explode(',',$pickup_location);
							// Get drop lat lon from location array from driver

						//Recursive lat,lon calculation Start
							$coordinates=array();
							$c=0;
							$explode_location=explode('|',$location_array['locations']);
							//print_r($explode_location);exit;
							for($count=0;$count<count($explode_location);$count++){
								if($explode_location[$count] !=""){
									if($count!=0){
										$pickup_location=explode(',',$explode_location[$c]);
										if($c < count($explode_location)-1){
											$drop_location=explode(',',$explode_location[$c+1]);
										}
										$c++;
									}else{
										//$drop_location = explode(',',$location_array['locations']);
										$drop_location = explode(',',$explode_location[$count]);
									}
									$coordinates[] = '['.$explode_location[$count].']';

									// Pass pickup lat,lon and drop lat,lon to Haversine formula
									$distance=$this->Tripdistance_Haversine($pickup_location, $drop_location);
									$current_distance = 0;
									if($distance > 0)
									{
										if(UNIT_NAME != "KM") { //to get distance in miles
											$current_distance = round($distance,4);
										} else { //to get distance in km
											$current_distance = round($distance * 1.609344,4);
										}
									}

									if(isset($total_distance)){
										$prev_distance = $total_distance;
										$total_distance = $prev_distance+$current_distance;
									}else{
										$prev_distance = $trip_check[0]['distance'];
										$total_distance = $prev_distance+$current_distance;
									}
									/*
									print_r($pickup_location);
									echo "</br>";
									print_r($drop_location);
									echo "</br>";
									echo $prev_distance."+".$current_distance."=".$total_distance;
									echo "</br></br>";
									*/
								}
								
							}
							if($coordinates != NULL){
								$location_record = implode(",",$coordinates);
								//$location_record = $trip_check[0]['active_record'].','.$location_record;
							}//print_r($location_record);exit;
							//Recursive lat,lon calculation End							
							if($trip_id != 0)
							{
								$location_record = ','.$location_record;
								//$updatequery = " UPDATE driver_location_history SET driver_id='$driver_id',status='$status',active_record='$location_record',distance='$total_distance' where trip_id = '$trip_id'";	
								$updatequery = " UPDATE driver_location_history SET driver_id='$driver_id',status='$status',`active_record` = concat(`active_record`,'$location_record'),distance='$total_distance'  where trip_id = '$trip_id'";
								$updateresult = Db::query(Database::UPDATE, $updatequery)
								->execute();
								
								$distance_updatequery = " UPDATE passengers_log SET distance='$total_distance' where passengers_log_id = '$trip_id'";	

								$distance_updateresult = Db::query(Database::UPDATE, $distance_updatequery)
								->execute();
								if($updateresult)
								{
									//return 1;
									$result[0]=1;
									$result[1]=$total_distance;
									return $result;
								}
								else
								{
									//return 1;
									$result[0]=1;
									$result[1]=$total_distance;
									return $result;
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

		}
		else
		{
			return 2;
		}

	}

	public function get_current_distance($location_array,$default_companyid)
	{
		$driver_id = $location_array['driver_id'];
		$trip_id = $location_array['trip_id'];

		$distance_query = "SELECT distance FROM ".PASSENGERS_LOG." WHERE driver_id = '$driver_id' and passengers_log_id='$trip_id' ";
		$distance_result =  Db::query(Database::SELECT, $distance_query)
			->execute()
			->as_array();
		return $distance_result;

	}
	
	
	public function save_driver_location_history_free($location_array,$default_companyid)
	{
				

		$get_company_time_details = $this->get_company_time_details($default_companyid);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time
		//echo $start_time;exit;
		$driver_id = $location_array['driver_id'];
		$trip_id = $location_array['trip_id'];
		$status = $location_array['status'];
		$check_package_type="";

		$company_query = "SELECT company_id FROM people WHERE id = '$driver_id' and user_type='D' ";

		$company_result =  Db::query(Database::SELECT, $company_query)
			->execute()
			->as_array();

		if(count($company_result) > 0)
		{
			$first_query = "select ".PACKAGE.".driver_tracking from " . PACKAGE_REPORT . " join ".PACKAGE." on ".PACKAGE.".package_id =".PACKAGE_REPORT.".upgrade_packageid  where ".PACKAGE_REPORT.".upgrade_companyid = ".$company_result[0]['company_id']."  order by upgrade_id desc limit 0,1";

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
		$location_record = '';	
		/*foreach($location_record_array as $key=>$value)
		{
			$location_record .='['.$value.']'.',';
		}*/
		$location_record = '['.$location_array['locations'].']';
		$string = str_replace(array('[',']'),'',$location_record);
		$exp=explode('|',$string);
		
		$coordinates=array();
		foreach($exp as $v){
			if($v!=""){
				$coordinates[] ="[".$v."]";
			}
		}
		if($coordinates != NULL){
			$location_record = implode(",",$coordinates);
		}

		if($driver_id!="")
		{
			if(($trip_id == '') || (($trip_id == 0)))
			{
				$driver_track_db = Database::instance(DRIVER_TRACK_DB);
				$find_query = "SELECT location_hid FROM driver_location_history WHERE status='F' and driver_id='$driver_id' and createdate >= '$start_time'";
				
				$find_result =  Db::query(Database::SELECT, $find_query)
					  ->execute(DRIVER_TRACK_DB)
					   ->as_array(); 
					   
				if(count($find_result) == 0)
				{
					//$location_record = substr($location_record, 0, -1);
					$fieldname_array = array('driver_id','free_record','status','createdate');
					$values_array = array($driver_id,$location_record,$status,$current_time);
					$result = DB::insert('driver_location_history', $fieldname_array)
						->values($values_array)
						->execute(DRIVER_TRACK_DB);

					if($result){
						return 1;
					}else{
						return 0;
					}
				}
				else
				{
					//$location_record = substr($location_record, 0, -1);
					//$location_record = $find_result[0]['free_record'].','.$location_record;

					$location_hid = $find_result[0]['location_hid'];	

					// $updatequery = " UPDATE driver_location_history SET driver_id='$driver_id',status='$status',free_record='$location_record',createdate='$current_time' where location_hid = '$location_hid'";
					$updatequery = " UPDATE driver_location_history SET free_record = concat(free_record,'".$location_record."') where location_hid = '$location_hid'";	

					$updateresult = Db::query(Database::UPDATE, $updatequery)
					->execute(DRIVER_TRACK_DB);

					if($updateresult){
						return 1;
					}else{
						return 0;
					}
				}
				unset($driver_track_db);
			}
			/*else
			{
				
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
	public function update_journey_statuswith_drop($id,$msg_status,$driver_reply,$travel_status,$drop_latitude,$drop_longitude,$drop_location,$drop_time,$total_distance,$waiting_hours,$tax,$driver_app_version="",$usedAmount)
	{
		$sql_query = array(
					'msg_status' =>$msg_status,
					'driver_reply' => $driver_reply,
					'travel_status' => $travel_status,
					'drop_latitude'=>$drop_latitude,
					'drop_longitude'=>$drop_longitude,
					'drop_location'=>$drop_location,
					'drop_time'=>$drop_time,
					'waitingtime'=>$waiting_hours,
					'driver_app_version'=>$driver_app_version,
					"used_wallet_amount" => $usedAmount,
					'company_tax'=>$tax						
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
	public function get_trip_detail($passengerlog_id = "",$passenger_id = "") 
	{
		$passenger_cond = "";
		$passenger_join = "";
		$passenger_sel = "";
		if(!empty($passenger_id)) {
			//passenger_id params come from ios passenger app only
			//$passenger_cond = " AND ".PASSENGERS_LOG.".`passengers_id` = '$passenger_id'";
			$passenger_sel = ", ".P_SPLIT_FARE.".paid_amount as split_paid_amount, ".P_SPLIT_FARE.".used_wallet_amount as split_wallet";
			$passenger_cond = " AND ".P_SPLIT_FARE.".`friends_p_id` = '$passenger_id'";//for primary and secondary passengers in split fare
			//join
			$passenger_join = " LEFT JOIN  ".P_SPLIT_FARE." ON ( ".P_SPLIT_FARE.".`trip_id` =  ".PASSENGERS_LOG.".`passengers_log_id` )";
		}
				$sql = "SELECT 
				".PASSENGERS_LOG.".passengers_log_id,".PASSENGERS_LOG.".company_id,".PASSENGERS_LOG.".passengers_id,".PASSENGERS_LOG.".current_location,IFNULL(".PASSENGERS_LOG.".drop_location,0) as drop_location ,".PASSENGERS_LOG.".no_passengers,".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".actual_pickup_time,".PASSENGERS_LOG.".drop_time,".PASSENGERS_LOG.".rating,".PASSENGERS_LOG.".no_passengers,".PASSENGERS_LOG.".notes_driver,".PASSENGERS_LOG.".is_split_trip, ".PEOPLE.".name AS driver_name,".PEOPLE.".profile_picture AS driver_image,".PEOPLE.".id AS driver_id,".TAXI.".taxi_no AS taxi_no,".MOTORMODEL.".taxi_speed AS taxi_speed,".MOTORMODEL.".taxi_min_speed AS taxi_min_speed,".MOTORMODEL.".waiting_time AS waiting_fare_hour,".MOTORMODEL.".minutes_fare AS fare_per_minute,".TAXI.".taxi_id AS taxi_id,".PASSENGERS_LOG.".travel_status AS travel_status,".PASSENGERS_LOG.".driver_reply AS driver_reply,".PASSENGERS_LOG.".search_city AS city_id ,".PASSENGERS_LOG.".current_location AS pickup_location,".PASSENGERS_LOG.".pickup_latitude,".PASSENGERS_LOG.".pickup_longitude,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".drop_latitude,".PASSENGERS_LOG.".drop_longitude,".PASSENGERS_LOG.".time_to_reach_passen,".PASSENGERS_LOG.".notification_status,".PASSENGERS_LOG.".used_wallet_amount,".PASSENGERS_LOG.".bookby,IFNULL(".DRIVER_INFO.".driver_twilio_number,'') AS driver_twilio_number,CONCAT(".PEOPLE.".country_code,".PEOPLE.".phone) as driver_phone, IFNULL(".PASSENGERS.".name,'') AS passenger_name, CONCAT(".PASSENGERS.".country_code,".PASSENGERS.".phone) AS passenger_phone,".PASSENGERS.".profile_image AS passenger_image,".PASSENGERS.".wallet_amount,IFNULL(".TRANS.".id,0) as job_ref,IFNULL(".TRANS.".payment_type,0) as payment_type, IFNULL((".TRANS.".amt+".PASSENGERS_LOG.".used_wallet_amount),0) as amt, IFNULL(".TRANS.".amt,0) as actual_paid_amount, ".TRANS.".waiting_time as waiting_time, ".PASSENGERS_LOG.".distance as distance, IFNULL(".TRANS.".distance,0) as actual_distance, IFNULL(".TRANS.".distance_unit,'".UNIT_NAME."') as metric,IFNULL(".TRANS.".waiting_cost,0) as waiting_cost,IFNULL(".TRANS.".tripfare,0) as tripfare,IFNULL(".TRANS.".minutes_fare,0) as minutes_fare,IFNULL(".TRANS.".trip_minutes,0) as trip_minutes,IFNULL(".TRANS.".promo_discount_fare,0) as promocode_fare,IFNULL(".TRANS.".tax_percentage,0) as tax_percentage,IFNULL(".TRANS.".company_tax,0) as tax_fare,IFNULL(".TRANS.".eveningfare,0) as eveningfare,IFNULL(".TRANS.".nightfare,0) as nightfare,IFNULL(".TRANS.".fare_calculation_type,".FARE_CALCULATION_TYPE.") as fare_calculation_type,IFNULL(dloc.active_record,0) as active_record $passenger_sel FROM  ".PASSENGERS_LOG." 
				LEFT JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
				LEFT JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) 
				LEFT JOIN  ".DRIVER_INFO." ON (  ".DRIVER_INFO.".`driver_id` =  ".PASSENGERS_LOG.".`driver_id` ) 
				LEFT JOIN  ".TAXI." ON (  ".TAXI.".`taxi_id` =  ".PASSENGERS_LOG.".`Taxi_id` )  
				LEFT JOIN  ".MOTORMODEL." ON (  ".MOTORMODEL.".`model_id` =  ".TAXI.".`taxi_model` )  
				LEFT JOIN ".DRIVER_LOCATION_HISTORY." as dloc ON dloc.trip_id = ".PASSENGERS_LOG.".passengers_log_id
				LEFT JOIN  ".TRANS." ON ( ".PASSENGERS_LOG.".`passengers_log_id` =  ".TRANS.".`passengers_log_id` )  $passenger_join
				WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$passengerlog_id' $passenger_cond limit 1";
//echo $sql;".PEOPLE.".phone AS driver_phone,".PEOPLE.".country_code AS driver_phone_code
//echo $sql;exit;
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}

	public function get_minimum_speed($taxi_id,$default_companyid)
	{
		$company_id = $default_companyid;
		if(FARE_SETTINGS == 2 && $company_id !="")
		{
			$model_base_query = "select ".COMPANY_MODEL_FARE.".taxi_min_speed
								from ".COMPANY_MODEL_FARE."
								left join ".TAXI." on ".TAXI.".taxi_model=".COMPANY_MODEL_FARE.".model_id
								where ".COMPANY_MODEL_FARE.".company_cid='$company_id'
								and ".COMPANY_MODEL_FARE.".fare_status='A' and ".TAXI.".taxi_id='$taxi_id'"; 

			$result = Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array();
			return $result;		
		}
		else
		{
			$model_base_query = "select ".MOTORMODEL.".taxi_min_speed
								from ".MOTORMODEL."
								left join ".TAXI." on ".TAXI.".taxi_model=".MOTORMODEL.".model_id
								where ".TAXI.".taxi_id='$taxi_id'"; 
			$result = Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array();
			return $result;
		}

	}
	/*** Get Passenger get_trip_detail passenger log id ***/ 
	public function get_request_detail($passenger_id="",$trip_id="") 
	{
		$date=date('Y-m-d');
				$sql = "SELECT  ".PASSENGERS_LOG.".cancel_status,".PASSENGERS_LOG.".cancel_datetime,".PASSENGERS_LOG.".passengers_log_id as trip_id,".PASSENGERS_LOG.".current_location,".PASSENGERS_LOG.".is_split_trip as splitTrip,".PASSENGERS_LOG.".passengers_id as primary_passenger,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".no_passengers,".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".actual_pickup_time,".PASSENGERS_LOG.".drop_time,".PASSENGERS_LOG.".rating,".PASSENGERS_LOG.".no_passengers,".PASSENGERS_LOG.".notes_driver, ".PEOPLE.".name AS driver_name,".PEOPLE.".profile_picture AS driver_image,".PEOPLE.".id AS driver_id,".TAXI.".taxi_no AS taxi_no,".TAXI.".taxi_id AS taxi_id,".PASSENGERS_LOG.".travel_status AS travel_status,".PASSENGERS_LOG.".driver_reply AS driver_reply,".PASSENGERS_LOG.".search_city AS city_id ,".PASSENGERS_LOG.".current_location AS pickup_location,".PASSENGERS_LOG.".pickup_latitude,".PASSENGERS_LOG.".pickup_longitude,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".drop_latitude,".PASSENGERS_LOG.".drop_longitude,".PASSENGERS_LOG.".time_to_reach_passen,".P_SPLIT_FARE.".notification_status,".P_SPLIT_FARE.".fare_percentage,".PASSENGERS_LOG.".bookby,".PASSENGERS_LOG.".used_wallet_amount,".PEOPLE.".phone AS driver_phone,  ".PASSENGERS.".name AS passenger_name, ".PASSENGERS.".phone AS passenger_phone,".PASSENGERS.".profile_image AS passenger_image,IFNULL(".TRANS.".id,0) as job_ref,IFNULL(".TRANS.".payment_type,0) as payment_type, IFNULL(".TRANS.".amt,0) as amt, IFNULL(".TRANS.".tripfare,0) as tripfare, IFNULL(".TRANS.".base_fare,0) as base_fare, IFNULL(".TRANS.".minutes_fare,0) as minutes_fare, IFNULL(".TRANS.".waiting_cost,0) as waiting_fare, IFNULL(".TRANS.".nightfare,0) as nightfare, IFNULL(".TRANS.".eveningfare,0) as eveningfare, IFNULL(".TRANS.".passenger_discount,0) as passenger_discount, IFNULL(".TRANS.".company_tax,0) as company_tax FROM  ".PASSENGERS_LOG." JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` )  JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) JOIN  ".TAXI." ON (  ".TAXI.".`taxi_id` =  ".PASSENGERS_LOG.".`Taxi_id` )  LEFT JOIN  ".TRANS." ON ( ".PASSENGERS_LOG.".`passengers_log_id` =  ".TRANS.".`passengers_log_id` ) LEFT JOIN  ".P_SPLIT_FARE." ON ( ".P_SPLIT_FARE.".`trip_id` =  ".PASSENGERS_LOG.".`passengers_log_id` ) WHERE  ".P_SPLIT_FARE.".`friends_p_id` =  '$passenger_id' AND ".PASSENGERS_LOG.".passengers_log_id='$trip_id' AND ".P_SPLIT_FARE.".`notification_status` !='4' AND DATE(".PASSENGERS_LOG.".createdate)='$date' AND (".PASSENGERS_LOG.".travel_status = '2' OR ".PASSENGERS_LOG.".travel_status='3' OR  ".PASSENGERS_LOG.".travel_status='5' OR ".PASSENGERS_LOG.".travel_status='6' OR ".PASSENGERS_LOG.".travel_status='9' OR ".PASSENGERS_LOG.".travel_status='8' OR ".PASSENGERS_LOG.".travel_status='4' OR ".PASSENGERS_LOG.".travel_status='1' OR ".PASSENGERS_LOG.".travel_status='7' ) order by trip_id desc limit 0,1";
//echo $sql;exit;
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
JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) JOIN  ".TAXI." ON (  ".TAXI.".`taxi_id` =  ".PASSENGERS_LOG.".`Taxi_id` )  LEFT JOIN  ".P_SPLIT_FARE." ON ( ".P_SPLIT_FARE.".`trip_id` =  ".PASSENGERS_LOG.".`passengers_log_id` ) WHERE ".P_SPLIT_FARE.".`friends_p_id` = '$userid' AND (".PASSENGERS_LOG.".travel_status = '9' or ".PASSENGERS_LOG.".travel_status = '2' or ".PASSENGERS_LOG.".travel_status = '3') AND ".PASSENGERS_LOG.".driver_reply = '".$driver_reply."'  $condition  $orderby";
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
					$start_time = $current_date[0].' 00:00:00';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:00';
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
				$start_time = $current_date[0].' 00:00:00';
				$end_time = $current_date[0].' 23:59:59';
			}
			else
			{
				$current_time =	date('Y-m-d H:i:s');
				$start_time = date('Y-m-d').' 00:00:00';
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
		//passengers_log_id,passengers_id,driver_id,taxi_id,current_location,pickup_latitude,pickup_longitude,drop_location,drop_latitude,drop_longitude,pickup_time,travel_status,time(".PASSENGERS_LOG.".pickup_time) as pickuptime
		$sql = "SELECT ".PASSENGERS_LOG.".passengers_log_id,".PASSENGERS_LOG.".current_location as pickup_location,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".no_passengers,IF(".PASSENGERS_LOG.".actual_pickup_time = '0000-00-00 00:00:00',".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".actual_pickup_time) as pickuptime,".PASSENGERS_LOG.".rating, ".PEOPLE.".name AS driver_name,".PEOPLE.".lastname AS driver_lastname,".PEOPLE.".photo AS driver_image,".PEOPLE.".id AS driver_id,".TAXI.".taxi_no AS taxi_no,".TAXI.".taxi_id AS taxi_id,".PASSENGERS_LOG.".travel_status AS travel_status,".PASSENGERS_LOG.".search_city AS city_id ,".PASSENGERS_LOG.".current_location AS pickup_location,".PASSENGERS_LOG.".pickup_latitude,".PASSENGERS_LOG.".pickup_longitude,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".drop_latitude,".PASSENGERS_LOG.".drop_longitude,".PASSENGERS_LOG.".time_to_reach_passen,".PEOPLE.".phone AS driver_phone,  ".PASSENGERS.".name AS passenger_name,CONCAT(".PASSENGERS.".`country_code`,".PASSENGERS.".`phone`) AS `passenger_phone`,".PASSENGERS.".lastname AS passenger_lastname FROM  ".PASSENGERS_LOG." JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) JOIN  ".TAXI." ON (  ".TAXI.".`taxi_id` =  ".PASSENGERS_LOG.".`Taxi_id` )  WHERE driver_id = '$userid' AND ".PASSENGERS_LOG.".bookby = '".BOOK_BY_CONTROLLER."' AND (travel_status = '9' or travel_status = '3') AND driver_reply = '".$driver_reply."'  $condition  $orderby";
//echo $sql;echo '<br>';
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();		
	}
			
	/*** Get Passenger Profile details using passenger log id  ***/
	public function get_passenger_log_detail($passengerlog_id="")
	{
				 $sql = "SELECT ".PASSENGERS_LOG.".driver_notes,".PEOPLE.".phone,".PASSENGERS_LOG.".promocode,".PASSENGERS_LOG.".is_split_trip,".PASSENGERS_LOG.".booking_key,".PASSENGERS_LOG.".passengers_id,".PASSENGERS_LOG.".driver_id,".PASSENGERS_LOG.".taxi_id,".PASSENGERS_LOG.".taxi_modelid, ".PASSENGERS_LOG.".company_id,".PASSENGERS_LOG.".current_location,".PASSENGERS_LOG.".pickup_latitude,".PASSENGERS_LOG.".pickup_longitude,
				IFNULL(".PASSENGERS_LOG.".drop_location,0) as drop_location,".PASSENGERS_LOG.".drop_latitude,".PASSENGERS_LOG.".drop_longitude,".PASSENGERS_LOG.".no_passengers, ".PASSENGERS_LOG.".approx_distance,".PASSENGERS_LOG.".approx_duration,".PASSENGERS_LOG.".approx_fare,".PASSENGERS_LOG.".fixedprice,".PASSENGERS_LOG.".time_to_reach_passen, ".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".actual_pickup_time,".PASSENGERS_LOG.".drop_time,".PASSENGERS_LOG.".account_id,".PASSENGERS_LOG.".accgroup_id,".PASSENGERS_LOG.".pickupdrop,
				".PASSENGERS_LOG.".rating,".PASSENGERS_LOG.".comments,".PASSENGERS_LOG.".travel_status,".PASSENGERS_LOG.".driver_reply, ".PASSENGERS_LOG.".msg_status,".PASSENGERS_LOG.".createdate,".PASSENGERS_LOG.".booking_from,".PASSENGERS_LOG.".search_city, ".PASSENGERS_LOG.".sub_logid,".PASSENGERS_LOG.".bookby,".PASSENGERS_LOG.".booking_from_cid,".PASSENGERS_LOG.".distance,".PASSENGERS_LOG.".notes_driver,".PASSENGERS_LOG.".used_wallet_amount, ".PEOPLE.".name AS driver_name,".PASSENGERS.".discount AS passenger_discount,".PEOPLE.".phone AS driver_phone,".PEOPLE.".profile_picture AS driver_photo,".PEOPLE.".device_id AS driver_device_id,".PEOPLE.".device_token AS driver_device_token,".PEOPLE.".device_type AS driver_device_type,".PASSENGERS.".discount AS passenger_discount,".PASSENGERS.".device_id AS passenger_device_id,".PASSENGERS.".device_token AS passenger_device_token,".PASSENGERS.".referred_by AS referred_by,".PASSENGERS.".referrer_earned AS referrer_earned,".PASSENGERS.".wallet_amount,".PASSENGERS.".device_type AS passenger_device_type, ".PASSENGERS.".salutation AS passenger_salutation,".PASSENGERS.".name AS passenger_name,".PASSENGERS.".lastname AS passenger_lastname,".PASSENGERS.".email AS passenger_email,".PASSENGERS.".phone AS passenger_phone,".PASSENGERS.".country_code AS passenger_country_code,".COMPANYINFO.".cancellation_fare as cancellation_nfree,".COMPANYINFO.".company_tax as company_tax,".COMPANYINFO.".company_brand_type as brand_type,IFNULL(".TRANS.".id,0) as transaction_id FROM  ".PASSENGERS_LOG." 
				JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
				LEFT JOIN  ".TRANS." ON (  ".PASSENGERS_LOG.".`passengers_log_id` =  ".TRANS.".`passengers_log_id` ) 
				LEFT JOIN  ".COMPANYINFO." ON (  ".PASSENGERS_LOG.".`company_id` =  ".COMPANYINFO.".`company_cid` ) 
				LEFT JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) 
				WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$passengerlog_id'";
				$result = Db::query(Database::SELECT, $sql)->as_object()->execute();											               
				return $result;		
	}
	public function get_passenger_log_detail_reply($passengerlog_id="")
	{
				 $sql = "SELECT ".PEOPLE.".phone,".PASSENGERS_LOG.".booking_key,".PASSENGERS_LOG.".passengers_id,".PASSENGERS_LOG.".driver_id,".PASSENGERS_LOG.".taxi_id,
				".PASSENGERS_LOG.".company_id,".PASSENGERS_LOG.".current_location as pickup_location,".PASSENGERS_LOG.".pickup_latitude,".PASSENGERS_LOG.".pickup_longitude,
				".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".drop_latitude,".PASSENGERS_LOG.".drop_longitude,".PASSENGERS_LOG.".no_passengers,
				".PASSENGERS_LOG.".approx_distance,".PASSENGERS_LOG.".approx_duration,".PASSENGERS_LOG.".approx_fare,".PASSENGERS_LOG.".time_to_reach_passen,
				".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".actual_pickup_time,".PASSENGERS_LOG.".drop_time,".PASSENGERS_LOG.".account_id,".PASSENGERS_LOG.".accgroup_id,".PASSENGERS_LOG.".pickupdrop,
				".PASSENGERS_LOG.".rating,".PASSENGERS_LOG.".comments,".PASSENGERS_LOG.".travel_status,".PASSENGERS_LOG.".driver_reply,
				".PASSENGERS_LOG.".msg_status,".PASSENGERS_LOG.".createdate,".PASSENGERS_LOG.".booking_from,".PASSENGERS_LOG.".search_city,
				".PASSENGERS_LOG.".sub_logid,".PASSENGERS_LOG.".passengers_log_id,".PASSENGERS_LOG.".bookby,".PASSENGERS_LOG.".booking_from_cid,".PASSENGERS_LOG.".company_tax,".PASSENGERS_LOG.".distance,
				".PEOPLE.".name AS driver_name,".PASSENGERS.".discount AS passenger_discount,".PASSENGERS.".profile_image,".PASSENGERS_LOG.".notes_driver as notes,".PEOPLE.".phone AS driver_phone,".PEOPLE.".profile_picture AS driver_photo,".PEOPLE.".device_id AS driver_device_id,".PEOPLE.".device_token AS driver_device_token,".PEOPLE.".device_type AS driver_device_type,".PASSENGERS.".discount AS passenger_discount,".PASSENGERS.".device_id AS passenger_device_id,".PASSENGERS.".device_token AS passenger_device_token,".PASSENGERS.".referred_by AS referred_by,".PASSENGERS.".referrer_earned AS referrer_earned,".PASSENGERS.".device_type AS passenger_device_type, ".PASSENGERS.".salutation AS passenger_salutation,".PASSENGERS.".name AS passenger_name,".PASSENGERS.".lastname AS passenger_lastname,".PASSENGERS.".email AS passenger_email,".PASSENGERS.".phone AS passenger_phone,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".id=company.cid ) as cancellation_nfree
				FROM  ".PASSENGERS_LOG."
				JOIN  ".COMPANY." ON (  ".PASSENGERS_LOG.".`company_id` =  ".COMPANY.".`cid` )
				JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` )
				JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` )
				WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$passengerlog_id'";
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();
					//->as_array();
				return $result;
	}

	/*** Get Passenger Profile details using passenger log id ***/
	public function get_passenger_cancel_faredetail($passengerlog_id="")
	{
		$model_base_query = "select search_city from ".PASSENGERS_LOG." where passengers_log_id=".$passengerlog_id;
		$model_fetch = Db::query(Database::SELECT, $model_base_query)->execute()->as_array();
		$model_fetch1 = array();
		if(count($model_fetch) > 0)
		{
			$city_id = $model_fetch[0]['search_city'];
			$model_base1_query = "select city_model_fare from ".CITY." where ".CITY.".city_id=".$city_id;
			$model_fetch1 = Db::query(Database::SELECT, $model_base1_query)->execute()->as_array();
		}
		if(count($model_fetch1) > 0)
		{
			$city_model_fare = $model_fetch1[0]['city_model_fare'];
		}
		else
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1";
			$model_fetch = Db::query(Database::SELECT, $model_base_query)->execute()->as_array();
			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}
		$sql = "select (SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare FROM ".PASSENGERS_LOG." AS pg JOIN ".TAXI." as taxi ON pg.`taxi_id`=taxi.`taxi_id` JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model` where pg.passengers_log_id='$passengerlog_id'";
				$result = Db::query(Database::SELECT, $sql)					
				->execute()
				->get('cancellation_fare');												               
				return $result;		
	}
	
	/*** Get Passenger Profile details using passenger log id ***/
	public function get_passenger_cancel_farebyid($passenger_id="")
	{

		$model_base_query = "select search_city from ".PASSENGERS_LOG." where passengers_id=".$passenger_id." AND (travel_status = 2 or travel_status = 3) order by passengers_log_id desc limit 0,1";
		$model_fetch = Db::query(Database::SELECT, $model_base_query)->execute()->as_array();

		if(count($model_fetch) > 0)
		{
			$city_id = $model_fetch[0]['search_city'];

			$model_base1_query = "select city_model_fare from ".CITY." where ".CITY.".city_id=".$city_id; 
			$model_fetch1 = Db::query(Database::SELECT, $model_base1_query)->execute()->as_array();

			if(count($model_fetch1) > 0)
			{	
				$city_model_fare = $model_fetch1[0]['city_model_fare'];
			}
			else
			{
				$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1"; 
				$model_fetch = Db::query(Database::SELECT, $model_base_query)->execute()->as_array();
				$city_model_fare = $model_fetch[0]['city_model_fare'];
			}	

		}
		else
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1"; 
			$model_fetch = Db::query(Database::SELECT, $model_base_query)->execute()->as_array();
			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}
			

		$sql = "select (SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare FROM ".PASSENGERS_LOG." AS pg JOIN ".TAXI." as taxi ON pg.`taxi_id`=taxi.`taxi_id` JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model` where pg.passengers_id='$passenger_id' AND (travel_status = 2 or travel_status = 3) order by passengers_log_id desc limit 0,1";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();												               
		return (count($result) > 0) ? $result[0]['cancellation_fare'] : 0;		
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

				 $sql = "SELECT ".PASSENGERS_LOG.".passengers_log_id,".PASSENGERS_LOG.".travel_status FROM  ".PASSENGERS_LOG." WHERE  ".PASSENGERS_LOG.".`driver_id` =  '$driver_id' and ".PASSENGERS_LOG.".pickup_time >='".$start_time."' $company_condition and (travel_status = '9' OR travel_status = '5' OR travel_status='3' OR travel_status='2') and driver_reply = 'A' ORDER BY ".PASSENGERS_LOG.".passengers_log_id DESC LIMIT 0 , 1 ";
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
					'status'=>'F'
				);
				//print_r($sql_query);
			$updatequery = " UPDATE ".DRIVER." SET shift_status='$shift_status',status='F' where driver_id = '$id'";	

			 $updateresult = Db::query(Database::UPDATE, $updatequery)
								->execute();				
			// $update = mysql_affected_rows();
		return $updateresult;
		 
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
				$sql = "SELECT email,name,activation_key,phone FROM ".PASSENGERS." WHERE phone = '".$array['phone_no']."' and passenger_cid='".$company_id."' and (country_code = '".$array['country_code']."' or country_code = '')"; //
			}
			else
			{
				$sql = "SELECT email,name,activation_key,phone FROM ".PASSENGERS." WHERE phone = '".$array['phone_no']."' and (country_code = '".$array['country_code']."' or country_code = '')"; //
			}
			
			//$sql = "SELECT * FROM ".PASSENGERS." WHERE phone = '".$array['phone_no']."' "; 
			//echo $sql;
			return Db::query(Database::SELECT, $sql)
				->execute()
				->as_array(); 	
		}
		else
		{
			$sql = "SELECT email,name FROM ".PEOPLE." WHERE phone = '".$array['phone_no']."'  and user_type='D'"; 
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
			$result=DB::update(PASSENGERS)->set($update_array)->where('id',"=",$update_id)->execute();//->where('passenger_cid','=','0')
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

		$result = DB::select('status','latitude','longitude')->from(DRIVER)
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
		
		$query = " select taxi_id from " . TAXIMAPPING . " 
		left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id 
		left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid 
		left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id 
		left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id 
		left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  
		left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id 
		where mapping_driverid='$driver_id' $company_condition 
		AND mapping_startdate <=  '".$current_time."' 
		AND mapping_enddate >= '".$current_time."'  order by mapping_startdate ASC";
		
		//AND mapping_startdate <=  '".$end_time."' AND mapping_enddate >= '".$start_time."' 
//echo $query;exit;
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
		$start_time = $current_date[0].' 00:00:01';
		$end_time = $current_date[0].' 23:59:59';
		
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
		$query= "SELECT company_id FROM ".PEOPLE." WHERE id='$user_id'";
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
		$sql = "SELECT company_paypal_username,company_paypal_password,company_paypal_signature,company_currency_format,payment_method FROM ".COMPANYINFO." WHERE company_cid = '$cid'";

		$result =  Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

		return $result;

	}
	/******************** Get default payment gateway of Specific company *********************/
	public function company_payment_details($cid)
	{
		$sql = "SELECT pg.payment_gateway_id as payment_type,pg.paypal_api_username as payment_gateway_username,pg.paypal_api_password as payment_gateway_password,pg.paypal_api_signature as payment_gateway_key,ci.company_currency_format as gateway_currency_format,pg.payment_method as payment_method FROM ".PAYMENT_GATEWAYS." as pg join ".COMPANYINFO." as ci on pg.company_id=ci.company_cid WHERE pg.company_id = '$cid' and pg.default_payment_gateway=1";
		$result =  Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

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
	
	/** Get Payment gateway details by payment type **/
	public function payment_gateway_bytype($paymentType = "")
	{
		$sql = "SELECT payment_gateway_id as payment_type,paypal_api_username as payment_gateway_username,paypal_api_password as payment_gateway_password,paypal_api_signature as payment_gateway_key,currency_code as gateway_currency_format,payment_method as payment_method FROM ".PAYMENT_GATEWAYS."  WHERE company_id = '0' and payment_gateway_id = '$paymentType'";

		$result =  Db::query(Database::SELECT, $sql)->execute()->as_array(); 
		return $result;
	}
	
	/**************************** Customer enhancement - Edited Senthil *************************/
	/**Passenger Signup**/
	public function add_p_account_details($val,$otp=null,$referral_code="",$devicetoken="",$deviceid="",$devicetype="",$company_id="",$fcm_token="") 
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


		
		$fieldname_array = array('name','email','password','org_password','otp','phone','address','referral_code','activation_key','activation_status','user_status','created_date','updated_date','passenger_cid','fcm_token');
		$values_array = array('',$val['email'],md5($val['password']),$val['password'],$otp,$val['phone'],'',$referral_code,'','0','I',$current_time,$current_time,$company_id,$fcm_token);
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

				$update_otp= DB::update(PASSENGERS)->set(array('otp' =>$otp ,'updated_date'=>$current_datetime))->where('email','=',$otp_array['email'])->execute();	//->where('passenger_cid','=',0)

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
			 		$sql = "SELECT id FROM ".PASSENGERS." WHERE email='$email' and otp='$otp' ";  //and passenger_cid='0' 
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
					 $sql = "SELECT id,email, otp, created_date,updated_date,DATE_ADD(  `updated_date` , INTERVAL 15 MINUTE ) AS otp_expiry FROM ".PASSENGERS." WHERE email='$email' and otp='$otp'";   // and passenger_cid='0'
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
				$sql = "SELECT id FROM ".PASSENGERS." WHERE referral_code='$referral_code' ";   // and passenger_cid='0'
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
				$data = DB::select('id')->from(PASSENGERS)->where(PASSENGERS.'.email','=',$p_email)->where(PASSENGERS.'.passenger_cid','=',$company_id)->as_object()->execute();
			}
			else
			{
				$data = DB::select('id')->from(PASSENGERS)->where(PASSENGERS.'.email','=',$p_email)->as_object()->execute(); //->where(PASSENGERS.'.passenger_cid','=',0)
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
							->where('id', '=', $passenger_id)							
							->execute();	
                /********************************************************								
				$referer_data_count = DB::select('*')->from(PASSENGERS_REF_DETAILS)
										->where(PASSENGERS_REF_DETAILS.'.registered_passenger_id','=',$passenger_id)
										->as_object()		
										->execute();
				if(count($referer_data_count)==0)
				{
				$ref_result = DB::insert(PASSENGERS_REF_DETAILS, array('referred_passenger_id','registered_passenger_id','referral_status','referrer_earned','earned_amount'))
								->values(array($referred_passenger_id,$passenger_id,'1','0',''))
								->execute();	
				/***************************************
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
	public function save_passenger_carddata($array, $default_companyid, $preTransactId = '', $preTransactAmount = '', $cardTypeDesc = '')
	{				
		//print_r($array);
		//echo $referred_passenger_id;
		//exit;
		try {							
			$p_email = $array['email'];
			if($default_companyid != "")
			{
				$data = DB::select('id')->from(PASSENGERS)
											->where(PASSENGERS.'.email','=',$p_email)
											->where('passenger_cid', '=', $default_companyid)
											->as_object()		
											->execute();
			}
			else
			{
				$data = DB::select('id')->from(PASSENGERS)
											->where(PASSENGERS.'.email','=',$p_email)
											//->where('passenger_cid', '=', '0')
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
				$card_holder_name = isset($array['card_holder_name']) ? urldecode($array['card_holder_name']) : '';
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
					return -1;
				}
				else
				{
					$card_result = DB::insert(PASSENGERS_CARD_DETAILS, array('passenger_id','passenger_email','card_type','creditcard_no','creditcard_cvv','card_holder_name','expdatemonth','expdateyear','default_card','pre_transaction_id','pre_transaction_amount','card_type_description'))
								->values(array($passenger_id,$p_email,'P',$creditcard_no,$creditcard_cvv,$card_holder_name,$expdatemonth,$expdateyear,'1',$preTransactId,$preTransactAmount,$cardTypeDesc))
								->execute();											
					return $card_result[0];	
				}
			}
			else
			{
				return 0;
			}						
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 0;			
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
	$sql = "SELECT id FROM ".PASSENGERS." WHERE id='$userid' and (name = '')";   	

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
		$sql = "SELECT count(passenger_cardid) as total FROM ".PASSENGERS_CARD_DETAILS." WHERE passenger_id = '$userid'";   	
		$result=Db::query(Database::SELECT, $sql)->execute()->get('total');
		return $result;
			
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
			$sql = "SELECT email FROM ".PASSENGERS." WHERE email='$email' and id!='$passenger_id' ";   //and passenger_cid='0'
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
	public function edit_check_phone_passengers($phone="",$passenger_id='',$company_id='',$country_code='')
	{
		if($company_id !='')
		{		
			$sql = "SELECT email FROM ".PASSENGERS." WHERE phone='$phone' and (country_code='$country_code' or country_code = '') and id!='$passenger_id' and passenger_cid='$company_id'";   //
		}
		else
		{
			$sql = "SELECT email FROM ".PASSENGERS." WHERE phone='$phone' and (country_code='$country_code' or country_code = '') and id!='$passenger_id' ";   //
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
					$result = DB::update(PASSENGERS)->set($array)->where('id', '=', $passenger_id)->execute();
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
	public function check_fav_place($passenger_id="",$favourite_place="",$d_favourite_place="",$p_fav_locationtype="")
	{
		$fav_sql = "SELECT count(p_favourite_id) as totalcnt FROM ".PASSENGERS_FAV." WHERE passenger_id='$passenger_id' and fav_loction_type IN ('$p_fav_locationtype')";
		
		$fav_result=Db::query(Database::SELECT,$fav_sql)->execute()->get('totalcnt');
			
		if($fav_result == 0){
			$favourite_place = $favourite_place;
			$d_favourite_place = $d_favourite_place;
			if($d_favourite_place !="")
			{
				$sql = "SELECT count(p_favourite_id) as favCnt FROM ".PASSENGERS_FAV." WHERE passenger_id='$passenger_id' and p_favourite_place = '$favourite_place' and d_favourite_place = '$d_favourite_place'";  
			}
			else
			{
				$sql = "SELECT count(p_favourite_id) as favCnt FROM ".PASSENGERS_FAV." WHERE passenger_id='$passenger_id' and p_favourite_place = '$favourite_place'";
			}
			$result=Db::query(Database::SELECT, $sql)
				->execute()
				->get('favCnt');
				return $result;	
		}else{
			return -1;
		}
	}


	public function check_fav_editplace($passenger_id="",$favourite_place="",$d_favourite_place="",$favourite_id="",$p_fav_locationtype="")
	{
			$fav_sql = "SELECT fav_loction_type FROM ".PASSENGERS_FAV." WHERE passenger_id='$passenger_id' and p_favourite_id='$favourite_id'";
			$fav_result=Db::query(Database::SELECT,$fav_sql)
			->execute()
			->as_array();
			if(count($fav_result)>0){
				if($fav_result[0]['fav_loction_type']==$p_fav_locationtype){
					return 0;
				}else{
					
					if($d_favourite_place !="")
					{
						$sql = "SELECT p_favourite_id FROM ".PASSENGERS_FAV." WHERE passenger_id='$passenger_id' and fav_loction_type='$p_fav_locationtype' and p_favourite_id!=$favourite_id";  
					}
					else
					{
						$sql = "SELECT p_favourite_id FROM ".PASSENGERS_FAV." WHERE passenger_id='$passenger_id' and fav_loction_type='$p_fav_locationtype' and p_favourite_id!=$favourite_id";
					}
					$result=Db::query(Database::SELECT, $sql)
						->execute()
						->as_array();
						return count($result);	
					}
			}else{
				return -1;
			}
	}

	public function check_fav_editplacecheck($passenger_id="",$favourite_place="",$d_favourite_place="",$favourite_id="",$p_fav_locationtype="")
	{
		$d_favourite_place = $d_favourite_place;
		$favourite_place = $favourite_place;
		if($d_favourite_place !="")
			{
				$sql = "SELECT p_favourite_id FROM ".PASSENGERS_FAV." WHERE passenger_id='$passenger_id' and p_favourite_place = '$favourite_place' and d_favourite_place = '$d_favourite_place' and p_favourite_id!=$favourite_id";  
			}
			else
			{
				$sql = "SELECT p_favourite_id FROM ".PASSENGERS_FAV." WHERE passenger_id='$passenger_id' and p_favourite_place = '$favourite_place' and p_favourite_id!=$favourite_id";
			}
			$result=Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
				return count($result);	

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
	public function add_passenger_carddata($array,$referred_passenger_id=null,$preTransactId,$preTransactAmount,$cardTypeDesc)
	{				
		//print_r($array);
		//echo $referred_passenger_id;
		//exit;
		try {							
			$p_email = urldecode($array['email']);			
			$passenger_id = $array['passenger_id'];
			$creditcard_no = $array['creditcard_no'];
			$creditcard_no = encrypt_decrypt('encrypt',$creditcard_no);			
			$creditcard_cvv = $array['creditcard_cvv'];
			$expdatemonth = $array['expdatemonth'];
			$expdateyear = $array['expdateyear'];
			$card_type = $array['card_type'];
			$default = $array['default'];
			$isDefault = "0";
			if($default == 1)
			{
				$array = array("default_card" => '0');//update previous cards are not default for a particular passenger
				$result = DB::update(PASSENGERS_CARD_DETAILS)->set($array)->where('passenger_id', '=', $passenger_id)->execute(); 
				$isDefault = "1";
			}
			$card_result = DB::insert(PASSENGERS_CARD_DETAILS, array('passenger_id','passenger_email','card_type','creditcard_no','creditcard_cvv','expdatemonth','expdateyear','default_card','pre_transaction_id','pre_transaction_amount','card_type_description'))->values(array($passenger_id,$p_email,$card_type,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$isDefault,$preTransactId,$preTransactAmount,$cardTypeDesc))->execute();
			$last_insert_id = $card_result[0];		
			//print_r($card_result);														
			return $last_insert_id;							
		} 
		catch (Kohana_Exception $e) {
			//echo $e->getMessage();
			return 1;			
		}
	}	
	//edit Passenger Card Data
	public function edit_passenger_carddata($array, $preTransactId = '', $preTransactAmount = '', $cardTypeDesc = '')
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
				"default_card" => '1',
				"pre_transaction_id"=>$preTransactId,
				"pre_transaction_amount"=>$preTransactAmount,
				"card_type_description"=>$cardTypeDesc);
				//print_r($update_array);exit;
				$array = array("default_card" => '0');
				$result = DB::update(PASSENGERS_CARD_DETAILS)->set($array)->where('passenger_id', '=', $passenger_id)->execute(); 						    								
			}
			else
			{
				$update_array = array("card_type" => $card_type,
				"creditcard_no"=>$creditcard_no,
				"expdatemonth"=>$expdatemonth,
				"expdateyear"=>$expdateyear,
				"pre_transaction_id"=>$preTransactId,
				"pre_transaction_amount"=>$preTransactAmount,
				"card_type_description"=>$cardTypeDesc);				
			}	
			$udate_result = DB::update(PASSENGERS_CARD_DETAILS)->set($update_array)->where('passenger_cardid', '=', $passenger_cardid)->execute();		
			//print_r($ref_result);														
			return 0;							
		} 
		catch (Kohana_Exception $e) {
			echo $e->getMessage();exit;
			return 1;			
		}
	}
	/** Save favourite Trip**/
	public function save_favourite($passenger_id=null,$p_favourite_place=null,$p_fav_latitude=null,$p_fav_longtitute=null,$d_favourite_place=null,$d_fav_latitude=null,$d_fav_longtitute=null,$fav_comments=null,$notes=null,$p_fav_locationtype=null)
	{
	
		$fav_result = DB::insert(PASSENGERS_FAV, array('passenger_id','p_favourite_place','p_fav_latitude','p_fav_longtitute','d_favourite_place','d_fav_latitude','d_fav_longtitute','fav_comments','status','notes','fav_loction_type'))
							->values(array($passenger_id,$p_favourite_place,$p_fav_latitude,$p_fav_longtitute,$d_favourite_place,$d_fav_latitude,$d_fav_longtitute,$fav_comments,'A',$notes,$p_fav_locationtype))
							->execute();
		return isset($fav_result[0]) ? $fav_result[0] : 0;
	}
	/** Get Favourite list **/
	public function get_favourite_list($passenger_id)
	{
		$sql = "SELECT passenger_id,p_favourite_id,p_favourite_place,p_fav_latitude,p_fav_longtitute,d_favourite_place,d_fav_latitude,d_fav_longtitute,fav_comments,notes,fav_loction_type FROM ".PASSENGERS_FAV." WHERE `passenger_id` = '".$passenger_id."' and status='A' order by p_favourite_id DESC";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	/** Get Favourite Details **/
	public function get_favourite_details($p_favourite_id)
	{
		$sql = "SELECT p_favourite_id,p_favourite_place,p_fav_latitude,p_fav_longtitute,d_favourite_place,d_fav_latitude,d_fav_longtitute,fav_comments,notes,fav_loction_type FROM ".PASSENGERS_FAV." WHERE `p_favourite_id` = '".$p_favourite_id."'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}	
	
	//$favourite_id,$p_favourite_place,$p_fav_latitude,$p_fav_longtitute,$d_favourite_place,$d_fav_latitude,$d_fav_longtitute,$fav_comments
	public function edit_favourite($favourite_id=null,$p_favourite_place=null,$p_fav_latitude=null,$p_fav_longtitute=null,$d_favourite_place=null,$d_fav_latitude=null,$d_fav_longtitute=null,$fav_comments=null,$notes=null,$p_fav_locationtype=null)
	{
		           $update_result =  DB::update(PASSENGERS_FAV)->set(array(
				'p_favourite_place'=>$p_favourite_place,
				'p_fav_latitude'=>$p_fav_latitude,
				'p_fav_longtitute'=>$p_fav_longtitute,
				'd_favourite_place'=>$d_favourite_place,
				'd_fav_latitude'=>$d_fav_latitude,
				'd_fav_longtitute'=>$d_fav_longtitute,
				'fav_comments'=>$fav_comments,
				'notes'=>$notes,
				'fav_loction_type'=>$p_fav_locationtype
			))->where('p_favourite_id', '=' ,$favourite_id)->execute();
		return $update_result;
	}
	/** Delete Favourite **/
	public function delete_favourite($favourite_id,$passenger_id)
	{
		$result=DB::delete(PASSENGERS_FAV)->where('p_favourite_id','=',$favourite_id)->where('passenger_id','=',$passenger_id)->execute();
		return $result;
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
		$sql = "SELECT pg.current_location as place,pg.pickup_time,pg.actual_pickup_time,pg.drop_location,
		pg.pickup_latitude,pg.pickup_longitude,pg.drop_latitude,pg.drop_longitude,pg.notes_driver,
		t.fare,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername,(select photo from ".PEOPLE." where id=pg.driver_id) as driverimage,(select taxi_no from ".TAXI." where taxi_id=pg.taxi_id) as taxi_no,(select pay_mod_name from ".PAYMENT_MODULES." where pay_mod_id=t.payment_type) as payment_name,pg.passengers_log_id as trip_id,t.id as jobreferral,pg.createdate,(select count(*) from  ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition) as total_count  FROM ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition $orderby ";
//echo $sql.'<br>';
	return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	// Get Passenger trips by Fromdate anda to date
	//public function get_passengertrips_byfrmdate($pagination,$booktype,$userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null,$date)
	public function get_passengertrips_byfrmdate($pagination,$booktype,$userid="",$createdate="",$start=null,$limit=null,$date)
	{
		$condition="";
		//$condition = "AND DATE_FORMAT(pg.createdate,'%Y-%m') ='".$date."'"; //it is commented for new design because in that they dont want the result monthwise

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
		$sql = "SELECT pg.current_location as place,pg.pickup_time,pg.actual_pickup_time,pg.drop_location,pg.pickup_latitude,pg.pickup_longitude
		,pg.drop_latitude,pg.drop_longitude,pg.notes_driver,pg.is_split_trip,t.fare,split.paid_amount,split.used_wallet_amount,CONCAT(ppl.name,' ',ppl.lastname) as drivername, ppl.profile_picture as profile_image,taxi.taxi_no, mmdl.model_name,pg.passengers_log_id as trip_id,t.id as jobreferral,pg.createdate,pg.travel_status,IFNULL(t.payment_type,0) as payment_type,IFNULL(dloc.active_record,0) as active_record FROM ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id RIGHT JOIN ".P_SPLIT_FARE." as split ON split.trip_id = pg.passengers_log_id RIGHT JOIN ".TAXI." as taxi ON taxi.taxi_id = pg.taxi_id RIGHT JOIN ".MOTORMODEL." as mmdl ON mmdl.model_id = taxi.taxi_model RIGHT JOIN ".PEOPLE." as ppl ON ppl.id = pg.driver_id LEFT JOIN ".DRIVER_LOCATION_HISTORY." as dloc ON dloc.trip_id = pg.passengers_log_id WHERE split.friends_p_id = '$userid' AND ((pg.travel_status = '1' AND pg.driver_reply = 'A') OR (pg.travel_status = '4') OR (pg.travel_status = '8') OR (pg.travel_status = '9' AND pg.driver_reply = 'C')) $condition group by split.trip_id $orderby ";//  
//echo $sql.'<br>';exit;

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
		array('name','email','password','otp','phone','driver_referral_code','user_type','status','created_date','updated_date','company_id','booking_limit');
		$values_array = 
		array('',$val['email'],md5($val['password']),$otp,$val['phone'],$referral_code,'D','D',$current_time,$current_time,'','100');
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
	
	public function delete_rejected_trips($passenger_id,$company_all_currenttimestamp)
	{
		$datetime = explode(' ',$company_all_currenttimestamp);
		$currentdate = $datetime[0].' 00:00:01';
		$delete_query = "DELETE FROM ".DRIVER_REJECTION." WHERE ".DRIVER_REJECTION.".passengers_id = '$passenger_id' and createdate >= '$currentdate'";
		$result = Db::query(Database::DELETE, $delete_query)->execute();
		return $result;
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
                         else if($device_type == 2)
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
                        else
                        {
							
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
                         elseif($device_type == 2)
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
                        else
                        {
							
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
		 $sql = "select passenger_cardid,passenger_id,card_type,expdatemonth,default_card,expdateyear,creditcard_no,creditcard_cvv from ".PASSENGERS_CARD_DETAILS." where passenger_id='$passenger_id' $condition";
		 $result = Db::query(Database::SELECT, $sql)->execute()->as_array();					             
		 return $result;				
		}
		
		/** Credit card delete function **/
		public function delete_credit_card($passenger_cardid,$passenger_id)
		{
			$result=DB::delete(PASSENGERS_CARD_DETAILS)->where('passenger_cardid','=',$passenger_cardid)->where('passenger_id','=',$passenger_id)->execute();
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
		$result = DB::select('package_name','no_of_taxi','no_of_driver','days_expire','package_price','package_type')->from(PACKAGE)
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
					$start_time = $current_date[0].' 00:00:00';
					$end_time = $current_date[0].' 23:59:59';
					$date = $current_date[0].' %';
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
					$start_time = date('Y-m-d').' 00:00:00';
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
				$start_time = $current_date[0].' 00:00:00';
				$end_time = $current_date[0].' 23:59:59';
			}
			else
			{
				$current_time =	date('Y-m-d H:i:s');
				$start_time = date('Y-m-d').' 00:00:00';
				$end_time = date('Y-m-d').' 23:59:59';
			}
		}
//drop_location

			$query = "SELECT ".PASSENGERS.".`name` as passenger_name, CONCAT(".PASSENGERS.".`country_code`,".PASSENGERS.".`phone`) AS `passenger_phone`,".PASSENGERS.".profile_image,".PASSENGERS_LOG.".`passengers_log_id`, ".PASSENGERS_LOG.".`current_location` AS `pickup_location`,".PASSENGERS_LOG.".`drop_location`, ".PASSENGERS_LOG.".`drop_location`, ".PASSENGERS_LOG.".`pickup_longitude`, ".PASSENGERS_LOG.".`pickup_latitude`, ".PASSENGERS_LOG.".`drop_latitude`, ".PASSENGERS_LOG.".`drop_longitude`, ".PASSENGERS_LOG.".`travel_status`, ".PASSENGERS_LOG.".`notes_driver` AS `notes`, ".PASSENGERS_LOG.".`distance`, ".PASSENGERS_LOG.".`waitingtime` AS `waiting_hour`, ".PASSENGERS_LOG.".`bookby` AS `bookby` FROM ".PASSENGERS_LOG." JOIN `passengers` ON (".PASSENGERS_LOG.".`passengers_id` = `passengers`.`id`) left join ".TRANS."  on 
				".TRANS.".passengers_log_id=".PASSENGERS_LOG.".passengers_log_id  WHERE ".PASSENGERS_LOG.".`driver_id` = '$id' AND ".PASSENGERS_LOG.".`msg_status` = '$msg_status' AND ".PASSENGERS_LOG.".`driver_reply` = '$driver_reply'  AND ".PASSENGERS_LOG.".`pickup_time` >= '$start_time'  AND (".PASSENGERS_LOG.".`travel_status` = '2' OR ".PASSENGERS_LOG.".`travel_status` = '5' OR ".PASSENGERS_LOG.".`travel_status` = '3' OR ".PASSENGERS_LOG.".`travel_status` = '9') ORDER BY `passengers_log`.`pickup_time` ASC";
			// echo $query;exit;
			 $result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();	
			 //AND ".PASSENGERS_LOG.".bookby = '".BOOK_BY_CONTROLLER."' 
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
						->values(array($post['driver_id'],$post['passengers_log_id'],$post['passengers_id'],$post['reason'],(string)$rejection_type,$post['createdate']))
						->execute();
		return $result;
	}
	
	public function passenger_log_det($trip_id)
	{
		
		$result = $result = DB::select(PASSENGERS_LOG.'.pickup_latitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.no_passengers',
PASSENGERS_LOG.'.pickup_time',PASSENGERS_LOG.'.current_location',PASSENGERS_LOG.'.drop_location',PASSENGERS_LOG.'.drop_latitude',
PASSENGERS_LOG.'.drop_longitude',PASSENGERS_LOG.'.passengers_id',PASSENGERS_LOG.'.time_to_reach_passen',PASSENGERS_LOG.'.driver_id',
PASSENGERS_LOG.'.driver_comments',TAXI.'.taxi_company',TAXI.'.taxi_model',CITY.'.city_name')->from(PASSENGERS_LOG)
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
		$country_res = DB::select('telephone_code')->from(COUNTRY)->where('default', '=', '1')->execute()->as_array();
		$to=str_replace('+','',$country_res[0]['telephone_code'].$number);
		require_once(DOCROOT.'application/vendor/mobility_sms/includeSettings.php');
		$userAccount='';
		$passAccount='';
		$timeSend=time();
		$dateSend=0;
		$deleteKey=0;
		$viewResult=1;
		$sender="Taximobility";
		$sms_send=sendSMS($userAccount, $passAccount, $to, $sender, $msg, $timeSend, $dateSend, $deleteKey, $viewResult);
		return $sms_send;
	}
	/** Get driver job status **/
	public function get_request_status($passenger_log_id="")
	{
		//$sql = "SELECT driver_reply,time_to_reach_passen FROM ".PASSENGERS_LOG." WHERE `passengers_log_id` = '".$passenger_log_id."'";
		$sql = "SELECT available_drivers,rejected_timeout_drivers,total_drivers,selected_driver,status,trip_type FROM ".DRIVER_REQUEST_DETAILS." WHERE `trip_id` = '".$passenger_log_id."'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 			
	}

	
	public function get_location_details($trip_id)
	{
		$result =DB::select('current_location','drop_location','active_record','drop_latitude','drop_longitude','pickup_latitude',
'pickup_longitude')->from(PASSENGERS_LOG)
		                     ->join(DRIVER_LOCATION_HISTORY)->on(PASSENGERS_LOG.'.passengers_log_id','=',DRIVER_LOCATION_HISTORY.'.trip_id')
		                     ->where(PASSENGERS_LOG.'.passengers_log_id','=',$trip_id)
		                     ->execute()
		                     ->as_array();
		return $result;
		                   
	}
	
	public function get_rejected_drivers($driver_id,$company_id)
	{
		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		
		$result =DB::select(DRIVER_REJECTION.'.driver_id')->from(DRIVER_REJECTION)
							//->join(PEOPLE)->on(DRIVER_REJECTION.'.driver_id','=',PEOPLE.'.id')
		                     ->where(DRIVER_REJECTION.'.driver_id','=',$driver_id)
		                     ->where(DRIVER_REJECTION.'.createdate','>=',$start_time)
		                     ->where(DRIVER_REJECTION.'.createdate','<=',$end_time)
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
		$passenger_log = DB::select('tpassenger_log_id','tdriver_ids')->from(PASSENGER_LOG_TEMP)
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
		$sql = "SELECT trip_id,available_drivers FROM ".DRIVER_REQUEST_DETAILS." WHERE status = '0' and selected_driver='$driver_id'
 and createdate >= '$currentdate' ORDER BY trip_id DESC LIMIT 0, 1";
	
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array(); 
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
				$driver_request = DB::select('available_drivers','total_drivers','rejected_timeout_drivers','status','trip_type')->from(DRIVER_REQUEST_DETAILS)
					->where('trip_id','=',$trip_id)		
		
					->execute()

					->as_array(); 
//print_r($driver_request);
				if(count($driver_request) >0)
				{
					return $driver_request;
				}
				else
				{
					return 0;
				}
				
	}

	public function company_addr_details($company_id)
	{
		$addr_details = DB::select('login_city','login_state','login_country','address')->from(PEOPLE)
			->where('company_id','=',$company_id)
			->limit(1)
			->execute()
			->as_array(); 
		if(count($addr_details) >0)
		{
			return $addr_details;
		}
		else
		{
			return 0;
		}
	}

	public function company_get_city_name($city)
	{
		$addr_details = DB::select('city_name')->from(CITY)
			->where('city_id','=',$city)
			->where('city_status','=','A')
			->limit(1)
			->execute()
			->as_array();
		if(count($addr_details) >0)
		{
			return $addr_details[0]['city_name'];
		}
		else
		{
			return 0;
		}
	}

	public function company_get_state_name($state)
	{
		$addr_details = DB::select('state_name')->from(STATE)
			->where('state_id','=',$state)
			->where('state_status','=','A')
			->limit(1)
			->execute()
			->as_array();
		if(count($addr_details) >0)
		{
			return $addr_details[0]['state_name'];
		}
		else
		{
			return 0;
		}
	}

	public function company_get_country_code($country)
	{
		$addr_details = DB::select()->from(COUNTRY)
			->where('country_id','=',$country)
			->where('country_status','=','A')
			->limit(1)
			->execute()
			->as_array();
		if(count($addr_details) >0)
		{
			return $addr_details;
		}
		else
		{
			return 0;
		}
	}
	
	public function get_driver_coordinates($driver_id)
	{
		$result=DB::select('free_record')->from(DRIVER_LOCATION_HISTORY)
			->where('driver_id','=',$driver_id)
			->where('trip_id','=','0')
			->order_by('location_hid', 'desc')
			->limit(1)
			->execute()
			->as_array();
		if(count($result) >0)
		{
			$exp=explode('],[',$result[0]['free_record']);
			$last_20=array_slice($exp, -20, 20, true);
			//print_r($last_20);exit;
			$coordinates=array();
			foreach($last_20 as $k => $v){
				$string = str_replace(array('[',']'),'',$v);
				$coordinates[] =$string;
			}
			if($coordinates != NULL){
				$coordinates = implode("#",$coordinates);
			}
			return $coordinates;
		}
		else
		{
			return 0;
		}
	}

	public static function company_model_details($default_companyid)
	{
		$company_id = $default_companyid;

		if(FARE_SETTINGS == 2 && $company_id !="")
		{
			$model_base_query = "select distinct ".MOTORMODEL.".model_id,".COMPANY_MODEL_FARE.".model_name,".COMPANY_MODEL_FARE.".model_size,
								".COMPANY_MODEL_FARE.".base_fare,
								".COMPANY_MODEL_FARE.".waiting_time as waiting_fare,
								".COMPANY_MODEL_FARE.".min_fare,
								".COMPANY_MODEL_FARE.".min_km,
								".COMPANY_MODEL_FARE.".below_above_km,
								".COMPANY_MODEL_FARE.".below_km,
								".COMPANY_MODEL_FARE.".above_km,
								".COMPANY_MODEL_FARE.".cancellation_fare,
								".COMPANY_MODEL_FARE.".night_charge,
								DATE_FORMAT(".COMPANY_MODEL_FARE.".night_timing_from,'%h:%i %p') as night_timing_from,
								DATE_FORMAT(".COMPANY_MODEL_FARE.".night_timing_to,'%h:%i %p') as night_timing_to,
								".COMPANY_MODEL_FARE.".night_fare,
								".COMPANY_MODEL_FARE.".evening_charge,
								DATE_FORMAT(".COMPANY_MODEL_FARE.".evening_timing_from,'%h:%i %p') as evening_timing_from,
								DATE_FORMAT(".COMPANY_MODEL_FARE.".evening_timing_to,'%h:%i %p') as evening_timing_to,
								".COMPANY_MODEL_FARE.".evening_fare
								from ".COMPANY_MODEL_FARE."
								left join ".MOTORMODEL." on ".MOTORMODEL.".model_id=".COMPANY_MODEL_FARE.".model_id
								where ".COMPANY_MODEL_FARE.".company_cid='$company_id'
								and ".COMPANY_MODEL_FARE.".fare_status='A'
								order by model_id ASC"; 

/* DATE_FORMAT(".COMPANY_MODEL_FARE.".night_timing_from,'%h:%i %p'),
 DATE_FORMAT(".COMPANY_MODEL_FARE.".night_timing_to,'%h:%i %p'), */

			$result = Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array();

			return $result;
		}
		else
		{
			/*$result = DB::select('model_id','model_name','base_fare','min_fare','min_km','below_above_km','below_km','above_km','night_charge','night_timing_from','night_timing_to','night_fare')->from(MOTORMODEL)
					->where('model_status','=','A')
					->order_by('model_id','ASC')
					->execute()
					->as_array();*/
			$model_base_query="SELECT `model_id`, `model_name`, `model_size`, `base_fare`, `min_fare`, `cancellation_fare`, `min_km`, `below_above_km`, `below_km`, `above_km`, `night_charge`, DATE_FORMAT(`night_timing_from`,'%h:%i %p') as night_timing_from, DATE_FORMAT(`night_timing_to`,'%h:%i %p') as night_timing_to, `night_fare`, `evening_charge`, DATE_FORMAT(`evening_timing_from`,'%h:%i %p') as evening_timing_from, DATE_FORMAT(`evening_timing_to`,'%h:%i %p') as evening_timing_to, `evening_fare`,waiting_time as waiting_fare FROM ".MOTORMODEL." WHERE `model_status` = 'A' ORDER BY `model_id` ASC";
			$result = Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array();

			return $result;
		}	
	}

	public function get_modelfare_details($default_companyid,$taxi_model)
	{
		if($default_companyid !=""){
			$result = DB::select('model_id','model_name','model_size','motor_mid','base_fare','min_km','min_fare','cancellation_fare','below_above_km','below_km','above_km','night_fare','waiting_time','minutes_fare')->from(COMPANY_MODEL_FARE)
			//->join(TAXI)->on(TAXI.'.taxi_company','=',COMPANY_MODEL_FARE.'.company_cid')
			->where('company_cid','=',$default_companyid)
			->where('model_id','=',$taxi_model)
			->where('fare_status','=','A')
			->order_by('company_model_fare_id','DESC')
			->limit(1)
			->execute()
			->as_array();
		}else{
			$result = DB::select('model_id','model_name','model_size','motor_mid','base_fare','min_km','min_fare','cancellation_fare','below_above_km','below_km','above_km','night_fare','waiting_time','minutes_fare')->from(MOTORMODEL)
			->where('model_id','=',$taxi_model)
			->order_by('model_id','DESC')
			->limit(1)
			->execute()
			->as_array();
		}
		
		if(count($result) >0)
		{
			return $result;
		}
		else
		{
			return 0;
		}
	}


	public function get_driver_taxi_speed($taxi_id)
	{
		$result = DB::select('taxi_speed')->from(TAXI)
			->where('taxi_id','=',$taxi_id)
			->where('taxi_status','=','A')
			->order_by('taxi_id','DESC')
			->limit(1)
			->execute()
			->as_array();
		if(count($result) >0)
		{
			return $result[0]['taxi_speed'];
		}
		else
		{
			return 0;
		}
	}
	  public function getpromodetails($promo_code="",$passenger_id="")
   {
		$promo_query = "SELECT promocode,promo_discount,promo_used,promo_limit FROM  ".PASSENGER_PROMO." WHERE  promocode = '$promo_code' and  `passenger_id` ='$passenger_id'"; 
		$promo_fetch = Db::query(Database::SELECT, $promo_query)
					->execute()
					->as_array();	
		 if(count($promo_fetch)>0)
		 {
			 $promocode = $promo_fetch[0]['promocode'];
			 $promo_discount = $promo_fetch[0]['promo_discount'];
			 $promo_used = $promo_fetch[0]['promo_used'];
			 $promo_limit = $promo_fetch[0]['promo_limit'];
			 
			$promo_use_query = "SELECT COUNT(passengers_log_id) as promo_count  FROM  ".PASSENGERS_LOG." WHERE  promocode = '$promo_code' and  `passengers_id` ='$passenger_id' and  travel_status='1' and driver_reply='A'"; 
			$promo_user_count = Db::query(Database::SELECT, $promo_use_query)
						->execute()
						->as_array();					 
				 
			 if(count($promo_user_count)>0&&$promo_user_count[0]['promo_count']>=$promo_limit)
			 {
				return -1; 
			 }			 
			 else
			 {				 
				 return $promo_discount;
			 }
		 }
		 else
		 {
			 return 0;
		 }		 		
   }  
   		
		/*** Get Passenger Profile details using passenger log id  ***/
	public function get_driver_request_detail($trip_id="",$passenger_id="")
	{
		$date = date('Y-m-d');
		if($trip_id!=""){
			$sql="SELECT ".DRIVER_REQUEST_DETAILS.".trip_id,
						".PASSENGERS_LOG.".driver_id,
						".PASSENGERS_LOG.".passengers_id,
						".DRIVER_REQUEST_DETAILS.".status,
						".PASSENGERS_LOG.".notification_status,
						".PASSENGERS_LOG.".current_location AS pickup_location,
						".PASSENGERS_LOG.".now_after,
						".PASSENGERS_LOG.".company_id,
						IFNULL(".TRANS.".amt,0) as amt
					FROM ".DRIVER_REQUEST_DETAILS."
					LEFT JOIN ".PASSENGERS_LOG." ON ( ".DRIVER_REQUEST_DETAILS.".trip_id =  ".PASSENGERS_LOG.".passengers_log_id)
					LEFT JOIN ".TRANS." ON ( ".DRIVER_REQUEST_DETAILS.".trip_id =  ".TRANS.".passengers_log_id )
					WHERE trip_id = '$trip_id' AND DATE(".DRIVER_REQUEST_DETAILS.".createdate)='$date'";
		}else{
			$sql="SELECT ".DRIVER_REQUEST_DETAILS.".trip_id,
						".PASSENGERS_LOG.".driver_id,
						".PASSENGERS_LOG.".passengers_id,
						".DRIVER_REQUEST_DETAILS.".status,
						".PASSENGERS_LOG.".now_after,
						".PASSENGERS_LOG.".notification_status,
						".PASSENGERS_LOG.".current_location AS pickup_location,
						".PASSENGERS_LOG.".company_id,
						IFNULL(".TRANS.".amt,0) as amt
					FROM ".DRIVER_REQUEST_DETAILS."
					LEFT JOIN ".PASSENGERS_LOG." ON ( ".DRIVER_REQUEST_DETAILS.".trip_id =  ".PASSENGERS_LOG.".passengers_log_id )
					LEFT JOIN ".TRANS." ON ( ".DRIVER_REQUEST_DETAILS.".trip_id =  ".TRANS.".passengers_log_id )
					WHERE ".PASSENGERS_LOG.".passengers_id = '$passenger_id' AND (status = '2' OR status='3' OR status='5' OR status='6' OR status='7' OR status='8' OR status='9') AND DATE(".DRIVER_REQUEST_DETAILS.".createdate)='$date'";
		}
		$result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
		return $result;
	}
		public function get_notification_status($trip_id,$current_status)
	{
		$result_value=1;
		if(!empty($trip_id)){
			$result=DB::select('notification_status')->from(PASSENGERS_LOG)->where('passengers_log_id', 'IN', $trip_id)->execute()->as_array();
			foreach($result as $r){
				if($r['notification_status']==$current_status){
					$result_value=0;
				}else{
					$result_value=1;
					break;
				}
			}
		}
		return $result_value;
	}
			public function get_passengerlog_details($trip_id)
		{
			$sql="SELECT * FROM  `passengers_log` WHERE `passengers_log_id` = '$trip_id'";
			$result = DB::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			return $result;
		}
   /********************** Check Promo Code ***************/
   public function checkpromocode($promo_code="",$passenger_id="",$company_id="")
   {
	 	//$promo_query = "SELECT promocode,promo_discount,promo_used,start_date,expire_date,promo_limit FROM  ".PASSENGER_PROMO." WHERE  promocode = '$promo_code' and  `passenger_id` ='$passenger_id'  "; 
	 	$promo_query = "SELECT promocode,promo_discount,promo_used,start_date,expire_date,promo_limit FROM  ".PASSENGER_PROMO." WHERE  promocode = '$promo_code' and  FIND_IN_SET('$passenger_id',`passenger_id`) ";
		$promo_fetch = Db::query(Database::SELECT, $promo_query)
					->execute()
					->as_array();
		 if(count($promo_fetch)>0)
		 {
			 $promocode = $promo_fetch[0]['promocode'];
			 $promo_discount = $promo_fetch[0]['promo_discount'];
			 $promo_used = $promo_fetch[0]['promo_used'];
			 
			 $promo_start = $promo_fetch[0]['start_date'];
			 $promo_expire = $promo_fetch[0]['expire_date'];
			 $promo_limit = $promo_fetch[0]['promo_limit'];
			 
				if($company_id == '')
				{
						if(TIMEZONE)
						{
							$current_time = convert_timezone('now',TIMEZONE);				
						}
						else
						{
							$current_time = date('Y-m-d H:i:s');
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
					}
					else
					{
						$current_time = date('Y-m-d H:i:s');
					}
				}
             // echo "start"."       ".$promo_start;
             // echo "end"."       ".$current_time;
			 if(strtotime($promo_start) > strtotime($current_time))
			 {
				 return 3;
			 }
			 else if(strtotime($promo_expire) < strtotime($current_time))
			 {
				 return 4;
			 }
			 else
			 {

				$promo_use_query = "SELECT COUNT(passengers_log_id) as promo_count  FROM  ".PASSENGERS_LOG." WHERE  promocode = '$promo_code' and  `passengers_id` ='$passenger_id' and  travel_status='1' and driver_reply='A'"; 
				$promo_user_count = Db::query(Database::SELECT, $promo_use_query)
							->execute()
							->as_array();					 
				 
				 if(count($promo_user_count)>0&&$promo_user_count[0]['promo_count']>=$promo_limit)
				 {
					return 2; 
				 }
				 else
				 {
					return 1;
				 }
			 }
		 }
		 else
		 {
			 return 0;
		 }		 		
   }
   	//To get the passenger cancel request data
	public function get_passenger_cancel_request_data($driver_id="",$current_time)
	{
		//$date = date('Y-m-d');
		$sql="SELECT ".PASSENGERS_LOG.".passengers_log_id as trip_id,
					".PASSENGERS_LOG.".travel_status as status,
					".PASSENGERS_LOG.".notification_status
				FROM ".PASSENGERS_LOG."
				WHERE ".PASSENGERS_LOG.".driver_id = '$driver_id' AND ".PASSENGERS_LOG.".travel_status = '4' AND ".PASSENGERS_LOG.".notification_status !='4' AND ".PASSENGERS_LOG.".notification_status !='5' AND ".PASSENGERS_LOG.".createdate = '$current_time' ";//DATE(".PASSENGERS_LOG.".createdate)='$date'
		$result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
		return $result;
		//				LEFT JOIN ".DRIVER." ON ( ".DRIVER_REQUEST_DETAILS.".driver_id = ".DRIVER.".id)
	}

   /**************** Get Promo details ***************/	
   
   	public function get_past_bookings($userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null,$company_id)
	{

		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time


		$condition="";
		if($createdate == 0){ $condition = "AND pg.pickup_time >='".$start_time."'"; }
		/*$sql = "SELECT *,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername  FROM ".PASSENGERS_LOG." as pg LEFT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition  order by pg.passengers_log_id desc LIMIT $start,$limit";     */

/*if($start == 0 && $limit ==0)
{*/

		$company_condition="";
		if($company_id != ""){
			$company_condition = " AND pg.company_id = '$company_id'";
		}
		
		$sql = "SELECT pg.passengers_log_id,pg.current_location as pickup_location,pg.drop_location,pg.pickup_longitude,pg.pickup_latitude,pg.drop_longitude,pg.drop_latitude,IF(pg.actual_pickup_time = '0000-00-00 00:00:00',pg.pickup_time,pg.actual_pickup_time) as pickuptime,pg.travel_status AS travel_status,pg.current_location AS pickup_location,pg.drop_location, ps.name AS passenger_name,pg.passengers_log_id,pe.id AS driver_id,pg.notes_driver AS notes_driver,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername,pg.drop_time, TIMEDIFF(pg.drop_time,IF(pg.actual_pickup_time = '0000-00-00 00:00:00',pg.pickup_time,pg.actual_pickup_time)) as trip_duration, tx.taxi_no, mmdl.model_name, IF(pe.profile_picture = '', '".URL_BASE."public/images/no_image109.png',CONCAT('".URL_BASE.SITE_DRIVER_IMGPATH."', pe.profile_picture)) as profile_image,t.waiting_cost as waiting_fare
		FROM ".PASSENGERS_LOG." as pg
		RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id
		RIGHT JOIN ".PASSENGERS." as ps ON pg.passengers_id = ps.id
		RIGHT JOIN ".TAXI." as tx ON tx.taxi_id = pg.taxi_id
		RIGHT JOIN ".PEOPLE." as pe ON  pg.driver_id = pe.id
		RIGHT JOIN ".MOTORMODEL." as mmdl ON  mmdl.model_id = tx.taxi_model
		RIGHT JOIN ".P_SPLIT_FARE." as split ON split.trip_id = pg.passengers_log_id
		WHERE split.`friends_p_id` = '$userid'
		AND pg.travel_status = '$status'
		AND pg.driver_reply = '$driver_reply'
		$condition $company_condition
		order by pg.passengers_log_id desc
		LIMIT $start,$limit"; //pg.passengers_id = '$userid'
		//DATE_FORMAT(pg.pickup_time,'%h:%i %p,%d %b %Y') as pickuptime
		//$sql = "SELECT pg.passengers_log_id,pg.current_location as pickup_location,pg.drop_location,pg.no_passengers,time(pg.pickup_time) as pickuptime,pg.rating,tx.taxi_no AS taxi_no,tx.taxi_id AS taxi_id,pg.travel_status AS travel_status,pg.search_city AS city_id ,pg.current_location AS pickup_location,pg.pickup_latitude,pg.pickup_longitude,pg.drop_location,pg.drop_latitude,pg.drop_longitude,pg.time_to_reach_passen, ps.name AS passenger_name,ps.lastname AS passenger_lastname,pg.passengers_log_id,pe.phone AS driver_phone,pe.photo AS driver_image,pe.id AS driver_id,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername,(select pay_mod_name from ".PAYMENT_MODULES." where pay_mod_id=t.payment_type) as payment_name,pg.passengers_log_id as pass_log_id,t.company_tax as tax_amount,pg.company_tax as tax_percentage,(select count(*) from  ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' and pg.company_id='$company_id' $condition) as total_count  FROM ".PASSENGERS_LOG." as pg RIGHT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id RIGHT JOIN ".PASSENGERS." as ps ON pg.passengers_id = ps.id RIGHT JOIN ".TAXI." as tx ON tx.taxi_id = pg.taxi_id RIGHT JOIN ".PEOPLE." as pe ON  pg.driver_id = pe.id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition $company_condition order by pg.passengers_log_id desc LIMIT $start,$limit";

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
	
	public function get_pending_bookings($company_id,$pagination,$userid="",$travelstatus="",$driver_reply="",$createdate="",$start=null,$limit=null)
	{
		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time

		$current_time = $get_company_time_details['current_time']; // Current Time
		$datetime    = explode(' ', $current_time);
		$current_time = $datetime[0] . ' 00:00:01';

		$condition="";
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$current_time."'"; }
		
		if($pagination == 1)
		{
			$orderby = "order by ".PASSENGERS_LOG.".pickup_time asc LIMIT $start,$limit";
		}
		else
		{
			$orderby = "order by ".PASSENGERS_LOG.".pickup_time asc";
		}		
		//passengers_log_id,passengers_id,driver_id,taxi_id,current_location,pickup_latitude,pickup_longitude,drop_location,drop_latitude,drop_longitude,pickup_time,travel_status
		$sql = "SELECT ".PASSENGERS_LOG.".passengers_log_id, ".PASSENGERS_LOG.".current_location as pickup_location, ".PASSENGERS_LOG.".drop_location, ".PASSENGERS_LOG.".pickup_latitude, ".PASSENGERS_LOG.".pickup_longitude, ".PASSENGERS_LOG.".drop_latitude, ".PASSENGERS_LOG.".drop_longitude, IF(".PASSENGERS_LOG.".actual_pickup_time = '0000-00-00 00:00:00',".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".actual_pickup_time) as pickuptime, IFNULL(".PEOPLE.".name,'') AS drivername,IFNULL(".PEOPLE.".id,'') AS driver_id, ".PASSENGERS_LOG.".travel_status AS travel_status, ".PASSENGERS_LOG.".current_location AS pickup_location, ".PASSENGERS_LOG.".drop_location, ".PASSENGERS_LOG.".notes_driver, ".PASSENGERS_LOG.".waitingtime, ".PASSENGERS_LOG.".distance, ".PASSENGERS.".name AS passenger_name, IFNULL(".TAXI.".taxi_no,'') as taxi_no, IFNULL(".MOTORMODEL.".model_name,'') as model_name, ".PEOPLE.".profile_picture as profile_image
			FROM  ".PASSENGERS_LOG." 
			JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
			LEFT JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) 
			LEFT JOIN  ".TAXI." ON (  ".TAXI.".`taxi_id` =  ".PASSENGERS_LOG.".`Taxi_id` )  LEFT JOIN  ".P_SPLIT_FARE." ON ( ".P_SPLIT_FARE.".`trip_id` =  ".PASSENGERS_LOG.".`passengers_log_id` ) LEFT JOIN ".MOTORMODEL." ON ( ".PASSENGERS_LOG.".`taxi_modelid` = ".MOTORMODEL.".`model_id` )
			WHERE  ".P_SPLIT_FARE.".`friends_p_id` = '$userid' 
			AND ((travel_status = '0' and bookingtype = '2') or (travel_status = '9') or (travel_status = '2') or (travel_status = '3') or (travel_status = '5')) 
			AND (driver_reply = '".$driver_reply."' or driver_reply = '' ) $condition  $orderby"; //".PASSENGERS_LOG.".passengers_id = '$userid' 
		//echo $sql;exit;	

		/*$sql = "SELECT *,(select concat(name,' ',lastname) from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as drivername  FROM ".PASSENGERS_LOG."   order by passengers_log_id desc LIMIT $start";     */
		//echo $sql;
		return Db::query(Database::SELECT, $sql)->execute()->as_array();
	}
	public function driver_past_bookings($pagination,$booktype,$id,$msg_status,$driver_reply=null,$travel_status=null,$start=null,$limit=null,$default_companyid=null)
	{
		$get_company_time_details = $this->get_company_time_details($default_companyid);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time
		//$current_time = convert_timezone('now',TIMEZONE);
		//$current_date = explode(' ',$current_time);
		//$start_time = $date.' 00:00:01';
		//$end_time = $date.' 23:59:59';
		$condition ="";
		//$condition = " AND passengers_log.createdate >='".$start_time."' and passengers_log.createdate <= '".$end_time."'"; 									
		if($booktype == 2)
		{
			$condition.= " AND passengers_log.booking_from = '$booktype'";
		}
		else
		{
			//$condition.= " AND passengers_log.booking_from != 2 ";
		}
		$selection="";

		$selection = "IF(".PASSENGERS_LOG.".`actual_pickup_time` = '0000-00-00 00:00:00',".PASSENGERS_LOG.".`pickup_time`,".PASSENGERS_LOG.".`actual_pickup_time`) as pickup_time , 
			".PASSENGERS_LOG.".`passengers_log_id`, 
			IFNULL(".TRANS.".`company_tax`,0) as tax, 
			IFNULL(".TRANS.".`tripfare`,0) as sub_total,
			IFNULL(".TRANS.".`amt`,0) as total,
			IFNULL(".TRANS.".`promo_discount_fare`,0) as promocode,
			".PASSENGERS_LOG.".`current_location` AS 
			`pickup_location`,
			".PASSENGERS_LOG.".`company_tax` AS 
			`tax_percentage`,
			".PASSENGERS.".`name`as 
			passenger_name,".PASSENGERS.".`profile_image`as profile_image, 
			IFNULL(".PASSENGERS_LOG.".drop_location,0) as 
			drop_location,IFNULL(ROUND((".TRANS.".`tripfare`-".TRANS.".`minutes_fare`),2),0) as 
			distance_fare,IFNULL(ROUND((".TRANS.".`amt`+".PASSENGERS_LOG.".`used_wallet_amount`),2),0) as 
			amt,".PASSENGERS_LOG.".`travel_status`,".PASSENGERS_LOG.".`used_wallet_amount` as wallet,".TRANS.".`payment_type`,".PASSENGERS_LOG.".`bookby`,".PASSENGERS_LOG.".`pickup_longitude`, ".PASSENGERS_LOG.".`pickup_latitude`, ".PASSENGERS_LOG.".`drop_latitude`, ".PASSENGERS_LOG.".`drop_longitude`, ".PASSENGERS_LOG.".`travel_status`, ".PASSENGERS_LOG.".`notes_driver` AS `notes`, ".PASSENGERS_LOG.".`distance`,".PASSENGERS_LOG.".drop_time, TIMEDIFF(".PASSENGERS_LOG.".drop_time,IF(".PASSENGERS_LOG.".`actual_pickup_time` = '0000-00-00 00:00:00',".PASSENGERS_LOG.".`pickup_time`,".PASSENGERS_LOG.".`actual_pickup_time`)) as trip_duration,IFNULL(".TRANS.".`distance_unit`,0) as metric,IFNULL(".TRANS.".waiting_cost,0) as waiting_fare, ".PASSENGERS_LOG.".`waitingtime`, IFNULL(".TRANS.".`waiting_time`,0) AS `twaiting_hour`,IFNULL(dloc.active_record,0) as active_record";

		if($pagination == 1)
		{
			$orderby = "ORDER BY ".PASSENGERS_LOG.".`pickup_time` DESC LIMIT $start,$limit";
		}
		else
		{
			$orderby = "ORDER BY ".PASSENGERS_LOG.".`pickup_time` DESC";
		}
			$sql = "SELECT $selection FROM ".PASSENGERS_LOG." LEFT JOIN ".TRANS." ON (".PASSENGERS_LOG.".`passengers_log_id` = ".TRANS.".`passengers_log_id`) LEFT JOIN ".PASSENGERS." ON (".PASSENGERS_LOG.".`passengers_id` = ".PASSENGERS.".`id`) LEFT JOIN ".DRIVER_LOCATION_HISTORY." as dloc ON dloc.trip_id = ".PASSENGERS_LOG.".passengers_log_id  WHERE ".PASSENGERS_LOG.".`driver_id` = '$id' AND ".PASSENGERS_LOG.".`msg_status` = '$msg_status' AND ".PASSENGERS_LOG.".`driver_reply` = '$driver_reply' AND ".PASSENGERS_LOG.".`travel_status` = '$travel_status' $condition $orderby";	
			//echo $sql;echo '<br>';echo '<br>';
			$result = Db::query(Database::SELECT, $sql)						
					->execute()
					->as_array();		
		
		
		 // print_r($result);           exit;
		return $result;	
	}
	
		//Function used to get the get_driver ongoign trips
	public function driver_pending_bookings($id,$msg_status,$driver_reply=null,$travel_status=null,$company_id,$start=null,$limit=null) //
	{
		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time
//drop_location

			$query = "SELECT ".PASSENGERS_LOG.".`passengers_log_id`,".PASSENGERS_LOG.".`pickup_time` as pickup_time,".PASSENGERS_LOG.".`pickup_longitude`, ".PASSENGERS_LOG.".`pickup_latitude`, ".PASSENGERS_LOG.".`drop_latitude`, ".PASSENGERS_LOG.".`drop_longitude`, ".PASSENGERS_LOG.".`travel_status`, ".PASSENGERS_LOG.".`notes_driver` AS `notes`, ".PASSENGERS_LOG.".`distance`, ".PASSENGERS_LOG.".`waitingtime` AS `waiting_hour`,".PASSENGERS_LOG.".`bookby`,".PEOPLE.".`name` as drivername,".PASSENGERS.".`name` as passenger_name,".PASSENGERS.".`profile_image` as passenger_profile_image,".PASSENGERS_LOG.".`passengers_log_id`, ".PASSENGERS_LOG.".`current_location` AS `pickup_location`, IFNULL(".PASSENGERS_LOG.".drop_location,0) as drop_location, ".PASSENGERS_LOG.".`travel_status`,".TRANS.".waiting_cost FROM ".PASSENGERS_LOG." LEFT JOIN `passengers` ON (".PASSENGERS_LOG.".`passengers_id` = `passengers`.`id`) left join ".TRANS."  on ".TRANS.".passengers_log_id=".PASSENGERS_LOG.".passengers_log_id left join ".PEOPLE."  on ".PASSENGERS_LOG.".driver_id=".PEOPLE.".id  WHERE ".PASSENGERS_LOG.".`driver_id` = '$id' AND ".PASSENGERS_LOG.".`msg_status` = '$msg_status' AND ".PASSENGERS_LOG.".`driver_reply` = '$driver_reply'  AND ".PASSENGERS_LOG.".`pickup_time` >= '$start_time'  AND (".PASSENGERS_LOG.".`travel_status` = '2' OR ".PASSENGERS_LOG.".`travel_status` = '5' OR ".PASSENGERS_LOG.".`travel_status` = '3' OR ".PASSENGERS_LOG.".`travel_status` = '9') ORDER BY `passengers_log`.`travel_status` DESC";
			 
			 $result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();
			 
			//DATE_FORMAT(".PASSENGERS_LOG.".`pickup_time`,'%h:%i %p,%d %b %Y') as pickup_time	
			 //echo '<br>';
			 //AND ".PASSENGERS_LOG.".bookby = '".BOOK_BY_CONTROLLER."' 
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
	
	public function check_new_request_bydriver($driver_id,$company_all_currenttimestamp,$trip_id)
	{
		$datetime = explode(' ',$company_all_currenttimestamp);
		$currentdate = $datetime[0].' 00:00:01';
		//$sql = "SELECT trip_id,available_drivers FROM ".DRIVER_REQUEST_DETAILS." WHERE status = '0' and FIND_IN_SET('$driver_id',available_drivers)  and NOT FIND_IN_SET('$driver_id', rejected_timeout_drivers) and createdate >= '$currentdate' ORDER BY trip_id DESC LIMIT 0 , 1";
		/*$sql = "SELECT trip_id,available_drivers FROM ".DRIVER_REQUEST_DETAILS."
			WHERE status = '0'
			and selected_driver='$driver_id'
			and NOT FIND_IN_SET('$driver_id', rejected_timeout_drivers)
			and createdate >= '$currentdate'
			ORDER BY trip_id DESC
			"; */
		$sql = "SELECT trip_id,available_drivers,rejected_timeout_drivers FROM ".DRIVER_REQUEST_DETAILS." WHERE status = '0' and selected_driver='$driver_id' and trip_id != '$trip_id' and createdate >= '$currentdate' ORDER BY trip_id DESC ";
		$result = Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
		
		Database::$instances = array();
		return $result;
	}
	
	/********************* Check any new job request for the driver ***********************/
	public function check_new_request_tripid($taxi_id=null,$company_id=null,$trip_id,$driver_id,$company_all_currenttimestamp,$driver_reply,$operator_id = 0)
	{		
		$datetime = explode(' ',$company_all_currenttimestamp);
		$current_date = $datetime[0].' 00:00:01';
		$createdate = isset($current_date)?$current_date:$datetime;		
		//$sql = "SELECT trip_id,available_drivers FROM ".DRIVER_REQUEST_DETAILS." WHERE status = '0' and FIND_IN_SET('$driver_id',available_drivers)  and NOT FIND_IN_SET('$driver_id', rejected_timeout_drivers) and createdate >= '$currentdate' ORDER BY trip_id DESC LIMIT 0 , 1";
		/*$sql = "SELECT trip_id,available_drivers,total_drivers,rejected_timeout_drivers FROM ".DRIVER_REQUEST_DETAILS."
		WHERE trip_id='$trip_id'
		and NOT FIND_IN_SET('$driver_id', rejected_timeout_drivers)
		and createdate >= '$currentdate'		
		ORDER BY trip_id DESC
		LIMIT 0 , 1";*/
$sql = "SELECT trip_id,available_drivers,total_drivers,rejected_timeout_drivers,status FROM ".DRIVER_REQUEST_DETAILS." WHERE trip_id='$trip_id' and selected_driver='$driver_id' and status !='4' and createdate >= '$createdate' ORDER BY trip_id DESC LIMIT 0 , 1";
		$result = Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();
		//print_r($result);exit;
		//return $result;
		if(count($result)>0)
		{
			if($driver_reply != 'C')
			{
				$available_drivers = $result[0]['available_drivers'];
				$exp_drivers=explode(',',$available_drivers);
				//print_r($exp_drivers);exit;
				$s_array=array();
				$first_driver=isset($exp_drivers[0])?$exp_drivers[0]:0;
				for($i=1;$i<count($exp_drivers);$i++){
					$s_array[]=$exp_drivers[$i];
					$temp_driver=isset($exp_drivers[1])?$exp_drivers[1]:$exp_drivers[0];
				}
				if($s_array!=""){
					$s_driver=implode(',',$s_array);
				}
				
				$prev_rejected_timeout_drivers = isset($result[0]['rejected_timeout_drivers'])?$result[0]['rejected_timeout_drivers']:"";
				if($prev_rejected_timeout_drivers != "")
				{
					$rejected_timeout_drivers = $prev_rejected_timeout_drivers.','.$driver_id;
				}
				else
				{
					$rejected_timeout_drivers = $driver_id;
				}
				//to get the usertypes
				if($operator_id != 0) {
					$sql_query = "SELECT user_type FROM ".PEOPLE." WHERE id = ".$operator_id;                      
					$user_type_dets =  Db::query(Database::SELECT, $sql_query)->execute()->as_array();
				}
				
				$temp_driver=isset($temp_driver)?$temp_driver:"";
				$update_trip_array  = array(
					"available_drivers"=>$s_driver,
					"selected_driver"=>$temp_driver,
					"status"=>"0",
					"rejected_timeout_drivers"=>$rejected_timeout_drivers
				);
				$update_result = $this->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$trip_id);
				//to update driver request and passenger log if selected driver is empty
				if($temp_driver == ''){
					$update_trip_array_one  = array("status"=>"4");
					$update_result = $this->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array_one,'trip_id',$trip_id);
					if($operator_id != 0 && $user_type_dets[0]['user_type'] == 'A') {
						$update_log_array_driver=array("driver_id"=>"0","taxi_id"=>"0","company_id"=>"0");
					} else {
						$update_log_array_driver=array("driver_id"=>"0","taxi_id"=>"0");
					}
					$results = $this->update_table(PASSENGERS_LOG,$update_log_array_driver,'passengers_log_id',$trip_id);
				}
				
				
				$driver_details=$this->get_driver_taxi($temp_driver);
				//print_r($driver_details);exit;
				$drivertaxi=isset($driver_details[0]['mapping_taxiid'])?$driver_details[0]['mapping_taxiid']:$taxi_id;
				$drivercompany=isset($driver_details[0]['mapping_companyid'])?$driver_details[0]['mapping_companyid']:$company_id;
				if($operator_id != 0 && $user_type_dets[0]['user_type'] == 'A') {
					$update_log_array=array("driver_id"=>$temp_driver,"taxi_id"=>$drivertaxi,"company_id"=>$drivercompany);
				} else {
					$update_log_array=array("driver_id"=>$temp_driver,"taxi_id"=>$drivertaxi);
				}
					
				$pass_log_update = $this->update_table(PASSENGERS_LOG,$update_log_array,'passengers_log_id',$trip_id);

				$update_driver_array  = array("status"=>'B');
				$driver_tbl_update = $this->update_table(DRIVER,$update_driver_array,'driver_id',$driver_id);
				
				//$driver_status = $this->get_request_status($trip_id);
				$available_drivers = explode(',',$result[0]['total_drivers']);
				$rejected_timeout_drivers = explode(',',$rejected_timeout_drivers);
				$comp_result = array_diff($available_drivers, $rejected_timeout_drivers);
				//echo count($comp_result);exit;
				if(count($comp_result) == 0){
					$update_trip_array_one  = array("status"=>"4");
					$update_result = $this->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array_one,'trip_id',$trip_id);
					if($operator_id != 0 && $user_type_dets[0]['user_type'] == 'A') {
						$update_log_array_driver=array("driver_id"=>"0","taxi_id"=>"0","company_id"=>"0");
					} else {
						$update_log_array_driver=array("driver_id"=>"0","taxi_id"=>"0");
					}
					$result = $this->update_table(PASSENGERS_LOG,$update_log_array_driver,'passengers_log_id',$trip_id);
				}
			}
			else
			{
				$drivertaxi=$taxi_id;//isset($driver_details[0]['mapping_taxiid'])?$driver_details[0]['mapping_taxiid']:"";
				$drivercompany=$company_id;//isset($driver_details[0]['mapping_companyid'])?$driver_details[0]['mapping_companyid']:"";
				if($driver_reply=="C"){
					$update_log_array=array("driver_id"=>$temp_driver,"taxi_id"=>$drivertaxi,"driver_reply"=>"C");
				}else{
					$update_log_array=array("driver_id"=>$temp_driver,"taxi_id"=>$drivertaxi);
				}
			}
		}		
		else
		{
			$trip_id = 0;
		}
		return "";
	}

	
	public function get_driver_taxi($driver_id="")
	{
		   //$sql = "SELECT driver_reply,time_to_reach_passen FROM ".PASSENGERS_LOG." WHERE `passengers_log_id` = '".$passenger_log_id."'";
		   $sql = "SELECT `mapping_taxiid`,`mapping_companyid`  FROM ".TAXIMAPPING." WHERE `mapping_driverid` = '".$driver_id."' and `mapping_status`='A'";
		   $result= Db::query(Database::SELECT, $sql)
				   ->execute()
				   ->as_array();                         
		   return isset($result)?$result:'0';
	}
	
	public function check_driver_status_free($driver_id="")
   {
		   $sql = "SELECT status FROM ".DRIVER." WHERE driver_id='$driver_id'";           

		   $result=Db::query(Database::SELECT, $sql)
				   ->execute()
				   ->as_array();

		   return $result[0]['status'];
   }
   
   public function previous_files_unlink($dir)
    {
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				$images = array();
				while (($file = readdir($dh)) !== false) {
					if (!is_dir($dir.$file)) {
						//$images[$listings['id']] = $file;
						unlink($dir.$file);
					}
				}
				closedir($dh);
			}
		}
	}
	public function check_driver_has_trip_request($driver_id,$company_all_currenttimestamp)
	{
		$datetime = explode(' ',$company_all_currenttimestamp);
		$current_date = $datetime[0].' 00:00:01';
		$createdate = isset($current_date)?$current_date:$datetime;

		$sql = "SELECT count(trip_id) as trip_count FROM ".DRIVER_REQUEST_DETAILS." WHERE status='1' and selected_driver='$driver_id' and createdate >= '$createdate' ORDER BY trip_id DESC";
		$trip_count = Db::query(Database::SELECT, $sql)->execute()->get('trip_count');
		if($trip_count > 0) {
			return $trip_count;
		} else {
			return 0;
		}
	}
	/*** Get Passenger Profile details using passenger log id  ***/
    public function get_trip_detail_only($passengerlog_id="")
    {
                 $sql = "SELECT 
		 ".PASSENGERS_LOG.".cancel_datetime,
		 ".PASSENGERS_LOG.".cancel_status,
                 ".PASSENGERS_LOG.".passengers_id,
                 ".PASSENGERS_LOG.".driver_id,
                 ".PASSENGERS_LOG.".taxi_id,
                 ".PASSENGERS_LOG.".operator_id,
                 ".PASSENGERS_LOG.".travel_status,
                ".PASSENGERS_LOG.".driver_reply                            
                FROM  ".PASSENGERS_LOG." 
                WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$passengerlog_id'";
                $result = Db::query(Database::SELECT, $sql)                    
                    ->as_object()        
                    ->execute();                                                           
                return $result;        
    }
	/** Change Driver Status **/
	public function change_driver_status($passenger_log_id="",$status="")
	{
		if($status == 'A')
		{
			$changearr = array("driver_reply"=>$status,"msg_status"=>'R',"travel_status"=>'9',"driver_comments"=>__('confirmed'));
		}
		elseif($status == 'R')
		{
			$changearr = array("driver_reply"=>$status,"msg_status"=>'R',"travel_status"=>'10',"driver_comments"=>__('missed'));
		}
		else
		{
			$changearr = array("driver_reply"=>$status,"msg_status"=>'R',"travel_status"=>'6',"driver_comments"=>"");
		}
		
		return DB::update(PASSENGERS_LOG)->set($changearr)
						->where('passengers_log_id','=',$passenger_log_id)
						->execute();	
	}       
	/*** Notification to driver for Dispatcher cancelled the trip *********************/
	public function get_dispatcher_cancel_data($driver_id="",$company_id)
    {
        $date = date('Y-m-d');        
        $get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time
				
        $sql="SELECT ".PASSENGERS_LOG.".passengers_log_id as trip_id,
                    ".PASSENGERS_LOG.".travel_status as status FROM ".PASSENGERS_LOG."
                WHERE ".PASSENGERS_LOG.".driver_id = '$driver_id'
                AND ".PASSENGERS_LOG.".travel_status = '8'
                AND ".PASSENGERS_LOG.".notification_status != '5'
                AND ".PASSENGERS_LOG.".bookby ='2'
                AND DATE(".PASSENGERS_LOG.".createdate)='$date' ";
        $result = Db::query(Database::SELECT, $sql)
                    ->execute()
                    ->as_array();
        //print_r( $result );exit;
        return $result;
    }
    /************************************************************************************/    
	public function get_driver_earnings_with_rating($driver_id,$company_id)
	{
        $get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		$current_time = $get_company_time_details['current_time']; // Current Time	
			
		$query = "select ".PASSENGERS_LOG.".rating, fare as total_amount from ".PASSENGERS_LOG." join 
		".TRANS." on ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id 
		where ".PASSENGERS_LOG.".driver_id='$driver_id' and ".PASSENGERS_LOG.".travel_status='1' and createdate >= '$start_time' and createdate <= '$end_time'";
			$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();
		return $result;
	}  
	
	public function get_driver_total_earnings($driver_id)
	{	
		$query = "select SUM(".TRANS.".fare) as total_amount from ".PASSENGERS_LOG." join ".TRANS." on ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id where ".PASSENGERS_LOG.".driver_id='$driver_id' and ".PASSENGERS_LOG.".travel_status='1'";
		$result = Db::query(Database::SELECT, $query)->execute()->get('total_amount');
		return $result;
	}  
    /************************************************************************************/    	
	public function get_company_time_details($companyid)
	{
		$timezone_details = array();
		/*** Start ***/
		if($companyid == '')
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
			$timezone_base_query = "select time_zone from  company where cid='$companyid' "; 
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
		$timezone_details['current_time'] = $current_time;
		$timezone_details['start_time'] = $start_time;
		$timezone_details['end_time'] = $end_time;
		$timezone_details['date'] = $date;
		return $timezone_details;
		/*** End ***/
	}
	/** to check driver not updated for a particular period **/
	public function check_driver_not_updated($driver_id,$company_timestamp)
	{
		$sql = "SELECT update_date  FROM ".DRIVER." WHERE driver_id='$driver_id'";
		$total = Db::query(Database::SELECT, $sql)->execute()->as_array();
		return isset($total[0]['update_date'])?$total[0]['update_date']:'0';
	}

	/** Change driver request flow **/		
	public function change_driver_reqflow($trip_id,$available_drivers,$rejected_timeout_drivers)
	{
		$availDriversArr = explode(",",$available_drivers);
		if(count($availDriversArr) > 1) {
			/*$shiftedDriver = array_shift($availDriversArr);
			array_push($availDriversArr,$shiftedDriver); */
			$temp = $availDriversArr[0];
			$availDriversArr[0] = $availDriversArr[1];
			$availDriversArr[1] = $temp;
			$driver_avail = implode(",",$availDriversArr);
			$temp_driver = isset($availDriversArr[0]) ? $availDriversArr[0] : 0;
			$update_trip_array  = array(
				"available_drivers"=>$driver_avail,
				"selected_driver"=>$temp_driver,
				"status"=>"0"
			);
			$update_result = $this->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$trip_id);
			
			
			$driver_details=$this->get_driver_taxi($temp_driver);
			//print_r($driver_details);exit;
			$drivertaxi=isset($driver_details[0]['mapping_taxiid'])?$driver_details[0]['mapping_taxiid']:0;
			$drivercompany=isset($driver_details[0]['mapping_companyid'])?$driver_details[0]['mapping_companyid']:0;
			$update_log_array=array("driver_id"=>$temp_driver,"taxi_id"=>$drivertaxi,"company_id"=>$drivercompany);
			
			$pass_log_update = $this->update_table(PASSENGERS_LOG,$update_log_array,'passengers_log_id',$trip_id);

			$update_driver_array  = array("status"=>'B');
			$driver_tbl_update = $this->update_table(DRIVER,$update_driver_array,'driver_id',$temp_driver);
		} else {
			$reject_drivers = ($rejected_timeout_drivers != '') ? $rejected_timeout_drivers.','.$available_drivers : $available_drivers;
			$update_trip_array  = array(
				"available_drivers"=>"",
				"selected_driver"=>"",
				"rejected_timeout_drivers"=>$reject_drivers,
				"status"=>"4"
			);
			$update_result = $this->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$trip_id);
			
			$update_log_array=array("driver_id"=>"0","taxi_id"=>"0","company_id"=>"0");
			$pass_log_update = $this->update_table(PASSENGERS_LOG,$update_log_array,'passengers_log_id',$trip_id);
		}
		
	}
	/** to get the updated trip details ( updated from dispatcher ) **/
	public function get_trip_update_status($trip_id="")
	{
		$sql = "SELECT drop_location, current_location, pickup_latitude, pickup_longitude, drop_latitude, drop_longitude,notes_driver,notification_status FROM ".PASSENGERS_LOG." WHERE passengers_log_id='$trip_id' AND (notification_status='6' OR notification_status='22')";           
		$result=Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
		return $result;
	}
	
	public function passenger_signup_with_referral($p_first_name, $p_last_name, $p_email, $p_phone, $country_code, $p_password, $p_confirm_password,$otp=null,$referral_code="",$devicetoken="",$deviceid="",$devicetype="",$company_id="",$accessToken="",$uid="",$image_name="") 
	{
		$common_model = Model::factory('commonmodel');
		if($company_id !='')
		{
			$current_time = $common_model->getcompany_all_currenttimestamp($company_id);
		}
		else
		{
			$current_time =	date('Y-m-d H:i:s');
		}
		
		/** Referrral key generator **/
		//$referralcode_query = "select concat(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1)) as referral_code";
		
		$referralcode_query = "select concat(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1)) as referral_code";

		$referralcode_result = Db::query(Database::SELECT, $referralcode_query)->execute()->as_array();
		//this referral code generated automatically. Passenger can refer this
		$auto_referral_code = $referralcode_result[0]['referral_code'];
		/** Referrral key generator **/
		/** to get referral setting and amount from siteinfo table **/
		$siteInfo = $this->siteinfo_details();
		$referralAmount = $siteInfo[0]['referral_amount'];
		/** Insert in passenger table **/
		$fieldname_array = array('name','lastname','email','password','org_password','otp','country_code','phone','address','referral_code','referral_code_amount','referral_code_limit','activation_key','activation_status','user_status','created_date','updated_date','passenger_cid','device_token','device_id','device_type','fb_user_id','fb_access_token','profile_image');
		$values_array = array($p_first_name,$p_last_name,$p_email,md5($p_password),$p_password,$otp,$country_code,$p_phone,'',$auto_referral_code,$referralAmount,'1','','1','A',$current_time,$current_time,$company_id,$devicetoken,$deviceid,$devicetype,$accessToken,$uid,$image_name);
		$passresult = DB::insert(PASSENGERS, $fieldname_array)->values($values_array)->execute();
		if($passresult){
			if(!empty($referral_code)) {
				//to get the referral amount and referral limit from the referral code
				$referral_sql = "SELECT id,referral_code_amount,referral_code_limit FROM ".PASSENGERS." WHERE referral_code='$referral_code'";
				$refer_dets = Db::query(Database::SELECT, $referral_sql)->execute()->as_array();
				if(count($refer_dets) > 0) {
					$ref_fieldArr = array('passenger_id','referral_code','referral_amount','referral_limit','device_id','device_token','referred_by','createdate');
					$ref_valueArr = array($passresult[0],$referral_code,$refer_dets[0]['referral_code_amount'],$refer_dets[0]['referral_code_limit'],$deviceid,$devicetoken,$refer_dets[0]['id'],$current_time);
					$passRef = DB::insert(PASSENGER_REFERRAL, $ref_fieldArr)->values($ref_valueArr)->execute();
					//to update the referral amount into the wallet column in passenger table
					$update_array = array('wallet_amount'=>$refer_dets[0]['referral_code_amount']);
					$update_wallet_amount = DB::update(PASSENGERS)->set($update_array)->where('id', '=', $passresult[0])->execute();
				}
			}
			return 1;
		} else {
			return 0;
		}
	}
	
	public function save_referral_code($passenger_id="",$referral_code="",$company_id="",$deviceid="",$devicetoken="")
	{
		$common_model = Model::factory('commonmodel');
		if($company_id !='')
		{
			$current_time = $common_model->getcompany_all_currenttimestamp($company_id);
		}
		else
		{
			$current_time =	date('Y-m-d H:i:s');
		}
		//to get the referral amount and referral limit from the referral code
		$referral_sql = "SELECT id,referral_code_amount,referral_code_limit FROM ".PASSENGERS." WHERE referral_code='$referral_code'";
		$refer_dets = Db::query(Database::SELECT, $referral_sql)->execute()->as_array();
		if(count($refer_dets) > 0) {
			$ref_fieldArr = array('passenger_id','referral_code','referral_amount','referral_limit','device_id','device_token','referred_by','createdate');
			$ref_valueArr = array($passenger_id,$referral_code,$refer_dets[0]['referral_code_amount'],$refer_dets[0]['referral_code_limit'],$deviceid,$devicetoken,$refer_dets[0]['id'],$current_time);
			$passRef = DB::insert(PASSENGER_REFERRAL, $ref_fieldArr)->values($ref_valueArr)->execute();
			//to update the referral amount into the wallet column in passenger table
			$update_array = array('wallet_amount'=>$refer_dets[0]['referral_code_amount']);
			$update_wallet_amount = DB::update(PASSENGERS)->set($update_array)->where('id', '=', $passenger_id)->execute();
			return 1;
		} else {
			return 0;
		}
	}
	//check passenger already used referral code
	public function check_referral_code_used($passenger_id)
	{
		$sql = "SELECT count(passenger_referralid) as total FROM ".PASSENGER_REFERRAL." WHERE passenger_id='$passenger_id'";
		$result = Db::query(Database::SELECT, $sql)->execute()->get('total');
		return $result;
	}
	
	//check otp exist for a passenger
	public function otp_verification($otp = "",$email = "")
	{
		$sql = "SELECT count(id) as total FROM ".PASSENGERS." WHERE email='$email' and otp='$otp'";
		$result = Db::query(Database::SELECT, $sql)->execute()->get('total');
		return $result;
	}
		
	// Check Whether Passenger phone is Already Exist or Not
	public function check_referral_code_exist($referral_code="",$company_id="")
	{
		$condition = "";
		if($company_id != ''){
			$condition = " and passenger_cid='$company_id'";
		}
		$sql = "SELECT count(id) as total FROM ".PASSENGERS." WHERE referral_code='$referral_code' $condition";
		$result = Db::query(Database::SELECT, $sql)->execute()->get('total');
		return $result;
	}
	
	//to check the passenger have wallet amount to use
	public function get_passenger_wallet_amount($passenger_id)
	{
		$sql = "SELECT wallet_amount,name,lastname,email,phone,referral_code_amount,referral_code FROM ".PASSENGERS." WHERE id='$passenger_id'";
		$result=Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	//function to get passenger details by referral code
	public function passenger_detailsbyreferralcode($referral_code)
	{
		$sql = "SELECT id,wallet_amount FROM ".PASSENGERS." WHERE referral_code='$referral_code'";
		$result=Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	//to check the passenger have referral amount to use
	public function check_passenger_referral_amount($passenger_id)
	{
		$sql = "SELECT referral_amount,referral_code FROM ".PASSENGER_REFERRAL." WHERE passenger_id='$passenger_id' and referral_amount_used='0'";
		$result=Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	//insert into wallet log table
	public function add_wallet_log($fieldname_array, $values_array)
	{
		return DB::insert(PASSENGER_WALLET_LOG, $fieldname_array)->values($values_array)->execute();
	}
	//insert credit card details if savecard is 1
	public function add_credit_card_details($fieldname_array, $values_array)
	{
		return DB::insert(PASSENGERS_CARD_DETAILS, $fieldname_array)->values($values_array)->execute();
	}
	/** check promocode used limit for wallet **/
	public function checkwalletpromocode($promo_code="",$passenger_id="",$company_id="")
	{
		$promo_query = "SELECT promocode,promo_discount,promo_used,start_date,expire_date,promo_limit FROM  ".PASSENGER_PROMO." WHERE  promocode = '$promo_code' and FIND_IN_SET('$passenger_id',passenger_id)";
		$promo_fetch = Db::query(Database::SELECT, $promo_query)
					->execute()
					->as_array();
		 if(count($promo_fetch)>0)
		 {
			 $promocode = $promo_fetch[0]['promocode'];
			 $promo_discount = $promo_fetch[0]['promo_discount'];
			 $promo_used = $promo_fetch[0]['promo_used'];
			 
			 $promo_start = $promo_fetch[0]['start_date'];
			 $promo_expire = $promo_fetch[0]['expire_date'];
			 $promo_limit = $promo_fetch[0]['promo_limit'];
			 
				if($company_id == '')
				{
						if(TIMEZONE)
						{
							$current_time = convert_timezone('now',TIMEZONE);				
						}
						else
						{
							$current_time = date('Y-m-d H:i:s');
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
					}
					else
					{
						$current_time = date('Y-m-d H:i:s');
					}
				}
			 // echo "start"."       ".$promo_start;
			 // echo "end"."       ".$current_time;
			 if(strtotime($promo_start) > strtotime($current_time))
			 {
				 return 3;
			 }
			 else if(strtotime($promo_expire) < strtotime($current_time))
			 {
				 return 4;
			 }
			 else
			 {
				$promo_use_query = "SELECT COUNT(passenger_wallet_logid) as promo_count  FROM  ".PASSENGER_WALLET_LOG." WHERE  promocode = '$promo_code' and  `passenger_id` ='$passenger_id'"; 
				$promo_user_count = Db::query(Database::SELECT, $promo_use_query)
							->execute()
							->as_array();
				 if(count($promo_user_count)>0&&$promo_user_count[0]['promo_count']>=$promo_limit)
				 {
					return 2; 
				 }
				 else
				 {
					return 1;
				 }
			 }
		 }
		 else
		 {
			return 0;
		 }
	}
	/** to get location from latittude and longitude **/
	public function getaddress($lat,$lng)
	{
		try{
		  $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false&key='.GOOGLE_GEO_API_KEY;        
		  $json = @file_get_contents($url);
		  $data=json_decode($json);
		  $status = ($data) ? $data->status : 0;
		  if($status=="OK")
		  return $data->results[0]->formatted_address;
		  else
		  return false;
		} catch (Kohana_Exception $e) {
			return false;
		} 
	  
	}
	/*Get the CMS Content*/
	public function getcmscontent($content,$default_companyid="")
	{
		$default_companyid=COMPANY_CID;
		if($default_companyid != 0)
		{			
			$sql = "select ".COMPANY_CMS.".content,".COMPANY_CMS.".menu_name as menu from ".COMPANY_CMS."  where ".COMPANY_CMS.".type='1' and status='1' AND ".COMPANY_CMS.".page_url= '$content' AND company_id = '".$default_companyid."'";
		}
		else
		{
			$sql = "select ".CMS.".content,".CMS.".meta_keyword,".CMS.".meta_title,".CMS.".meta_description,".CMS.".menu from ".CMS." JOIN  ".MENU." ON ( ".MENU.".`menu_id` =  ".CMS.".`menu_id` ) where ".CMS.".type='1' and status='1' and ".MENU.".menu_id='".$content."'";
		}
		//echo $sql;
		$cms_result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $cms_result;
	}
	
	public function get_driver_cancelled_trips($driver_id,$company_id)
	{
		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time']; //Start time
		$end_time = $get_company_time_details['end_time']; //end time
		
		$result =DB::select(array( DB::expr('COUNT(passengers_log_id)' ), 'total'))->from(PASSENGERS_LOG)->where('driver_id','=',$driver_id)->where('travel_status','=','9')->where('driver_reply','=','C')->where('createdate','>=',$start_time)->where('createdate','<=',$end_time)->execute()->get('total');
		return $result;
	}
	
	public function logged_user_status_web($driver_id,$current_time,$company_id)
	{
		$query="SELECT login_status,notification_status,status FROM ".PEOPLE." Join ".TAXIMAPPING." on ".TAXIMAPPING.".`mapping_driverid` = ".PEOPLE.".`id` where id = '".$driver_id."' and ".TAXIMAPPING.".`mapping_enddate` >= '$current_time'";
		//echo $query;exit;
		$result =  Db::query(Database::SELECT, $query)->execute()->as_array();
		
		if(count($result) == 0 || (count($result) == 0 && $result[0]['status'] != 'A'))
		{
			$result[0]['login_status'] = 'N';
			$result[0]['notification_status'] = '0';
			$result[0]['admin_logout'] = '1';
			
			$company_condition = "";
			if($company_id != ""){
				$company_condition = " AND ".PASSENGERS_LOG.".company_id = '$company_id'";
			}
			//to get current date
			$current_date = explode(' ',$current_time);
			$start_time = $current_date[0].' 00:00:01';

			$driverlogsql = "SELECT ".PASSENGERS_LOG.".passengers_log_id,".PASSENGERS_LOG.".travel_status FROM  ".PASSENGERS_LOG." WHERE  ".PASSENGERS_LOG.".`driver_id` =  '$driver_id' and ".PASSENGERS_LOG.".pickup_time >='".$start_time."' $company_condition and (travel_status = '9' OR travel_status = '5' OR travel_status='3' OR travel_status='2') and driver_reply = 'A' ORDER BY ".PASSENGERS_LOG.".passengers_log_id DESC LIMIT 0 , 1 ";
			$get_driver_log_details = Db::query(Database::SELECT, $driverlogsql)->as_object()->execute();
			
			if(count($get_driver_log_details) == 0)
			{
			  $update_array  = array("login_from"=>"","login_status"=>"N","device_id" => "","device_token" => "","device_type" => "","notification_setting"=>"0");
			  $login_status_update = $this->update_table(PEOPLE,$update_array,'id',$driver_id);
				if($login_status_update)
				{
					$result[0]['login_status'] = 'N';
					$result[0]['notification_status'] = '1';
					$result[0]['admin_logout'] = ($result[0]['status'] != 'A') ? '2':'1';
					$update_driverArr = array("shift_status"=>"OUT");
					$dr_status_update = $this->update_table(DRIVER,$update_driverArr,'driver_id',$driver_id);
				}
				/** GET Shift ID **/
				$driver_shift = $this->get_driver_shift_log($driver_id); 
				if(count($driver_shift)>0){
					$shiftupdate_arrary = array("shift_end" => $current_time);
					$driver_shift_id = isset($driver_shift[0]['driver_shift_id'])?$driver_shift[0]['driver_shift_id']:'';
					$transaction = $this->update_table(DRIVERSHIFTSERVICE,$shiftupdate_arrary,'driver_shift_id',$driver_shift_id);
				}
			}
			return $result;
		}
		else
		{
			if($result[0]['login_status'] == 'S' && $result[0]['notification_status'] == 1)
			{		
			   return 1;
			}
			if($result[0]['login_status'] == 'N'){
				return $result;
			}else{
				return 1;
			}
		}
	}
	
	public function get_driver_shift_log($id)
	{
		$result = DB::select('driver_shift_id')->from(DRIVERSHIFTSERVICE)->where(DRIVERSHIFTSERVICE.'.driver_id','=',$id)->execute()->as_array();
		return $result;
	}
		
	public function get_passenger_company_id($id) 
	{
		$query= "SELECT passenger_cid FROM ".PASSENGERS." WHERE id = '$id'";
		$result =  Db::query(Database::SELECT, $query)->execute()->as_array();
		return ($result[0]['passenger_cid'])?$result[0]['passenger_cid']:0;
	}
	//** to check the passenger in trip or not **//
	public function check_passenger_in_trip($passengerId, $company_id)
	{
		$get_company_time_details = $this->get_company_time_details($company_id);
		$start_time = $get_company_time_details['start_time'];
		//$sql = "SELECT count(passengers_log_id) as total FROM ".PASSENGERS_LOG." WHERE `pickup_time` >= '".$start_time."' and `passengers_id` = '".$passengerId."' and `driver_reply` = 'A' and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '5')";
		//return Db::query(Database::SELECT, $sql)->execute()->get('total'); 
		$sql = "SELECT total,payment_fail FROM (SELECT count(passengers_log_id) as total FROM ".PASSENGERS_LOG." as pl Left join ".P_SPLIT_FARE." as psplit ON psplit.trip_id = pl.passengers_log_id WHERE pl.`pickup_time` >= '".$start_time."' and psplit.`friends_p_id` = '".$passengerId."' and psplit.approve_status = 'A' and pl.`driver_reply` = 'A' and (pl.`travel_status` = '9' or pl.`travel_status` = '2' or pl.`travel_status` = '3')) as total, (SELECT count(passengers_log_id) as payment_fail FROM ".PASSENGERS_LOG." WHERE `pickup_time` >= '".$start_time."' and `passengers_id` = '".$passengerId."' and `driver_reply` = 'A' and `travel_status` = '5') as payment_fail";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array(); 
		if(count($result) > 0) {
			if(isset($result[0]["total"]) && $result[0]["total"] == 1) {
				return 1;
			}
			
			if(isset($result[0]["payment_fail"]) && $result[0]["payment_fail"] == 1) {
				return 2;
			}
		}
		return 0;
	}
	// Check Whether Passenger is fb user or normal
	public function check_fb_user($phone="",$company_id="",$country_code="")
	{
		if($company_id != '')
		{
			$sql = "SELECT count(id) as total FROM ".PASSENGERS." WHERE phone='$phone' and (country_code='$country_code' or country_code = '') and passenger_cid='$company_id' and fb_user_id != '' and fb_access_token != ''";   //
		}
		else
		{
			$sql = "SELECT count(id) as total FROM ".PASSENGERS." WHERE phone='$phone' and (country_code='$country_code' or country_code = '') and fb_user_id != '' and fb_access_token != ''";   //
		}
		//echo $sql;
		$result=Db::query(Database::SELECT, $sql)->execute()->get('total');
		return $result;
	}
	//** function to check whether the booking is later or not **//
	public function get_booking_details($tripID)
	{
		$sql = "SELECT CONCAT(".PASSENGERS.".country_code,".PASSENGERS.".phone) as passenger_phone,".PASSENGERS.".name,".PASSENGERS.".email,CONCAT(".PEOPLE.".country_code,".PEOPLE.".phone) as driver_phone, ".PASSENGERS_LOG.".driver_id, ".PASSENGERS_LOG.".passengers_id FROM ".PASSENGERS_LOG." LEFT JOIN ".PASSENGERS." ON ".PASSENGERS.".id = ".PASSENGERS_LOG.".passengers_id LEFT JOIN ".PEOPLE." ON ".PEOPLE.".id = ".PASSENGERS_LOG.".driver_id WHERE ".PASSENGERS_LOG.".`passengers_log_id` = '".$tripID."' ";
		return Db::query(Database::SELECT, $sql)->execute()->as_array(); 
	}
	//** Function to check the passenger's credit card has expired  **//
	public function check_credit_card_expire($passenger_id, $company_id)
	{
		$get_company_time_details = $this->get_company_time_details($company_id);
		$current_time = $get_company_time_details['current_time'];
		$currentmonth = date("m", strtotime($current_time));
		$currentyear = date("Y", strtotime($current_time));
		$cardsql = "SELECT count(passenger_cardid) as total FROM ".PASSENGERS_CARD_DETAILS." WHERE passenger_id = '$passenger_id' and default_card = '1' and ((expdatemonth < $currentmonth and expdateyear = $currentyear) or (expdateyear < $currentyear))";
		//echo $cardsql;exit;
		$cardresult = Db::query(Database::SELECT, $cardsql)->execute()->get('total');
		return $cardresult;
	}


	public function get_driver_user_status($id)
	{

		$result = DB::select('status')->from(PEOPLE)
			->where('id','=',$id)				
			->where('status','=','A')				
			->execute()
			->as_array();
		if(count($result)==0){
			$result_new = DB::update(PEOPLE)->set(array('login_status' => 'N','notification_setting' => '0'))
				->where('id', '=', $id)
				->execute();
		}
		             
		return count($result);	
			
	}
	//function to update new geneerated password
	public function new_password_update($array,$random_key,$company_id)
	{
		$password = md5($random_key);
		$update_array = array('password'=>$password,'org_password'=>$random_key,'forgot_password'=>'1');
		if($array['user_type'] == 'P')
		{
			if($company_id != '')
			{
				$result=DB::update(PASSENGERS)->set($update_array)->where('phone',"=",$array['phone_no'])->where('passenger_cid','=',$company_id)->execute();
			}
			else
			{
				$result=DB::update(PASSENGERS)->set($update_array)->where('phone',"=",$array['phone_no'])->execute();
			}
		}
		else
		{
			$result=DB::update(PEOPLE)->set($update_array)->where('phone',"=",$array['phone_no'])->execute();
		}
		return $result;
	}
	/**  **/
	/** to get current time of a location(country) from latittude and longitude **/
	public function getpickupTimezone($lat,$lng)
	{
		
		try{
		  $url = 'https://maps.googleapis.com/maps/api/timezone/json?location='.trim($lat).','.trim($lng).'&timestamp=1&key='.GOOGLE_TIMEZONE_API_KEY;  
		  //echo $url;exit;    
		  $json = @file_get_contents($url);
		  $data=json_decode($json);
		  $status = ($data) ? $data->status : 0;
		  if($status=="OK")
			return ($data) ? $data->timeZoneId : TIMEZONE;
		  else
			return TIMEZONE;
		} catch (Kohana_Exception $e) {
			return TIMEZONE;
		} 
	  
	}
	/** function to update favourite driver id **/
	public function saveFavouriteDriver($passengerId, $driverId)
	{
		$query = "update ".PASSENGERS." set favourite_drivers = IF(favourite_drivers='',$driverId,concat(favourite_drivers,',$driverId')) where id = '$passengerId'";
		
		$result = Db::query(Database::UPDATE, $query)->execute();
		return $result;
	}
	/** function to update favourite driver id **/
	public function chkDriverAdded($passengerId, $driverId)
	{
		$query = "SELECT count(id) as total FROM ".PASSENGERS." WHERE id = '$passengerId' and FIND_IN_SET('$driverId', favourite_drivers) > 0";
		$result = Db::query(Database::SELECT, $query)->execute()->get('total');
		return $result;
	}
	/** function to get favourite driver for a passenger **/
	public function getFavDrivers($passengerId)
	{
		$query = "SELECT favourite_drivers FROM ".PASSENGERS." WHERE id = '$passengerId' limit 1";
		$result = Db::query(Database::SELECT, $query)->execute()->get('favourite_drivers');
		return $result;
	}
	/**  **/
	public function checkpassengerPhonewithConcat($phone="",$company_id="")
	{
		$company_cond = "";
		if($company_id != '')
		{
			$company_cond = " and passenger_cid='$company_id'";  
		}
		$sql = "SELECT phone,id,name,login_status FROM ".PASSENGERS." WHERE (CONCAT_WS('', country_code, phone) = '$phone' OR phone = '$phone') $company_cond";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;	
	}
	//credit card validation for braintree
	public function creditcardPreAuthorization($passenger_id,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$preauthorize_amount)
	{
		$passenger_profile=$this->passenger_profile($passenger_id,'A');
		if(count($passenger_profile)>0)
		{
			$paypal_details = $this->payment_gateway_details();
			$payment_gateway_username = isset($paypal_details[0]['payment_gateway_username'])?$paypal_details[0]['payment_gateway_username']:"";
			$payment_gateway_password = isset($paypal_details[0]['payment_gateway_password'])?$paypal_details[0]['payment_gateway_password']:"";
			$payment_gateway_key = isset($paypal_details[0]['payment_gateway_key'])?$paypal_details[0]['payment_gateway_key']:"";
			$currency_format = isset($paypal_details[0]['gateway_currency_format'])?$paypal_details[0]['gateway_currency_format']:"";
			$payment_method = isset($paypal_details[0]['payment_method'])?$paypal_details[0]['payment_method']:"";
			$payment_types=isset($paypal_details[0]['payment_type'])?$paypal_details[0]['payment_type']:"";
			
			$firstName=$passenger_profile[0]['name'];
			$lastName='';
			$phone='';
			$email='';
			$street='';
			$state='';
			$city='';
			$country_code='';
			$zipcode='';
			$code=0;
			$fcardtype="";
			$fresult = '';
			if($payment_types == 1) {
				$payment_action='sale';
				$request  = 'METHOD=DoDirectPayment';
				$request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
				$request .= '&USER=' . urlencode($payment_gateway_username);
				$request .= '&PWD=' . urlencode($payment_gateway_password);
				$request .= '&SIGNATURE=' . urlencode($payment_gateway_key);

				$request .= '&CUSTREF=';
				$request .= '&PAYMENTACTION=' . $payment_action; //type
				$request .= '&AMT=' . urlencode($preauthorize_amount); //   $amount = urlencode($data['amount']);

				//$request .= '&CREDITCARDTYPE=' . $_POST['cc_type'];
				$request .= '&ACCT=' . urlencode(str_replace(' ', '',$creditcard_no));
				// $request .= '&CARDSTART=' . urlencode($_POST['cc_start_date_month'] . $_POST['cc_start_date_year']);
				$request .= '&EXPDATE=' . urlencode($expdatemonth.$expdateyear);
				$request .= '&CVV2=' . urlencode($creditcard_cvv);
			
				$request .= '&CURRENCYCODE=' .$currency_format;
				//exit;
				$paypal_type=($payment_method =="L")?"live":"sandbox";
				//echo $paypal_type; exit;
				if ($paypal_type=="live") {
					$curl = curl_init('https://api-3t.paypal.com/nvp');
					/*** Billing Address ************/
					$request .= '&FIRSTNAME=' . urlencode($firstName);
					$request .= '&LASTNAME=' . urlencode($lastName);
					$request .= '&EMAIL=' . urlencode($email);
					$request .= '&IPADDRESS=' . urlencode($_SERVER['REMOTE_ADDR']);
					$request .= '&STREET=' . urlencode($street);
					$request .= '&CITY=' . urlencode($city);
					$request .= '&STATE=' . urlencode($state);
					$request .= '&ZIP=' . urlencode($zipcode);
					$request .= '&COUNTRYCODE=' . urlencode($country_code);
					/***************************************/
				} else {
					$curl = curl_init('https://api-3t.sandbox.paypal.com/nvp');			
				}
					//print_r($request);
				curl_setopt($curl, CURLOPT_PORT, 443);
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
				curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

				$response = curl_exec($curl); 
				$nvpstr=$response; 		
				curl_close($curl); 
				//print_r($nvpstr); exit;
				$intial=0;
				$nvpArray = array();

				while(strlen($nvpstr))
				{
					//postion of Key
					$keypos= strpos($nvpstr,'=');
					//position of value
					$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

					/*getting the Key and Value values and storing in a Associative Array*/
					$keyval=substr($nvpstr,$intial,$keypos);
					$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
					//decoding the respose
					$nvpArray[urldecode($keyval)] =urldecode( $valval);
					$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
				}
				//print_r($nvpArray); exit;
				if(isset($nvpArray["ACK"]) && strtoupper($nvpArray["ACK"]) == "SUCCESS") {
					$fresult= isset ($nvpArray['TRANSACTIONID']) ? $nvpArray['TRANSACTIONID'] : '';
					$code = 1;
				} else {
					$code = 0;
					$fresult= isset ($nvpArray['L_LONGMESSAGE0']) ? $nvpArray['L_LONGMESSAGE0'] : '';
				}
			} else {
				require_once(APPPATH.'vendor/braintree-payment/lib/Braintree.php');
				$pay_type=($payment_method =="L")?"live":"sandbox";
				try
				{
					if ($pay_type=="live") 
					{
						Braintree_Configuration::environment('production');
					}
					else
					{
						Braintree_Configuration::environment('sandbox');			
					}
					Braintree_Configuration::merchantId($payment_gateway_username);//your_merchant_id
					Braintree_Configuration::publicKey($payment_gateway_password);//your_public_key
					Braintree_Configuration::privateKey($payment_gateway_key);
						
					$result=Braintree_Transaction::sale(array(
							'amount' => $preauthorize_amount,				    
							'creditCard' => array(
					
							'cardholderName' => $firstName,
							'number' => $creditcard_no,//$_POST['creditCard']
							'expirationMonth' =>$expdatemonth,//$_POST['month']
							'expirationYear' => $expdateyear,//$_POST['year']
							'cvv' => $creditcard_cvv,
							),
							
							'customer' => array(
							'firstName'=>$firstName,
							'lastName'=>$lastName,
							'company' => '',
							'phone' => $phone,
							'fax' => '',
							'website' => '',						
							 'email'=>$email
						  ),
							
							'shipping' => array(
							'firstName' => $firstName,
							'lastName' => $lastName,
							'company' => '',
							'streetAddress' => $street,
							'extendedAddress' => '',
							'locality' => $state,
							'region' => $country_code,
							'postalCode' => $zipcode,
							'countryCodeAlpha2' =>$country_code,
						  ),						
						 'options' => array(
											  'submitForSettlement'=> false,
											 )
						 ));
				}
				catch(Braintree_Exception $message)
				{
					$fresult = '';
					$code = 0;
					return array($code,$fresult);
				}
				if($result->success)
				{
					$fresult = $result->transaction->id;
					$fcardtype = $result->transaction->creditCardDetails->cardType;
					$code = 1;
				} else {
					$code = 0;
					$fresult = "";
					foreach (($result->errors->deepAll()) as $error) {
						$fresult .= $error->message;
					}
				}
			}
			return array($code,$fresult,$fcardtype);
		}
		else
		{
			$fresult = 'Passenger Data Not Available';
			$code = 0;
			$fcardtype = "";
			return array($code,$fresult,$fcardtype);
		}
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
	/** Function to get splitted Passenger details to send push notification to them **/
	public function getSplitPassengersDetails($trip_id)
	{
		$sql = "SELECT p.phone,p.wallet_amount,p.id as passenger_id,p.name as name,p.profile_image as profile_image,p.device_token,p.device_id,p.device_type,plog.current_location,plog.drop_location,plog.approx_fare as total_fare,plog.passengers_id as primary_pass_id, split.appx_amount as split_fare,split.fare_percentage,split.approve_status,split.passenger_payment_option FROM ".P_SPLIT_FARE." as split LEFT JOIN ".PASSENGERS." as p ON p.id = split.friends_p_id LEFT JOIN ".PASSENGERS_LOG." as plog ON plog.passengers_log_id = split.trip_id WHERE plog.passengers_log_id = '$trip_id'";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	/** Function to get total pending fare percentage for a particular trip **/
	public function getpendingFarePercentage($trip_id)
	{
		//$sql = "SELECT sum(psplit.fare_percentage) as total FROM ".P_SPLIT_FARE." as psplit LEFT JOIN ".PASSENGERS_CARD_DETAILS." as pcard ON psplit.friends_p_id = pcard.passenger_id WHERE (pcard.passenger_id IS NULL or psplit.approve_status != 'A') and psplit.trip_id = '$trip_id'";
		$sql = "SELECT sum(psplit.fare_percentage) as total FROM ".P_SPLIT_FARE." as psplit LEFT JOIN ".PASSENGERS_CARD_DETAILS." as pcard ON psplit.friends_p_id = pcard.passenger_id WHERE (pcard.passenger_id IS NULL or pcard.default_card = '1') and psplit.approve_status != 'A' and psplit.trip_id = '$trip_id'";
		//print_r($sql); exit;
		return Db::query(Database::SELECT, $sql)->execute()->get('total'); 
	}
	/** Function to get split fare details for a particular trip **/
	public function getTripSplitFareDets($tripid,$approve_status = '')
	{
		$stsCnd = "";
		if($approve_status != '') {
			$stsCnd = " and approve_status = '$approve_status' ";
		}
		$sql = "SELECT psplit.split_id,psplit.friends_p_id,psplit.used_wallet_amount,psplit.fare_percentage, pcard.card_type, pcard.creditcard_no, pcard.creditcard_cvv, pcard.expdatemonth, pcard.expdateyear, pcard.card_holder_name, p.name as firstname, p.lastname, p.email, CONCAT(p.country_code,p.phone) as phone FROM ".P_SPLIT_FARE." as psplit LEFT JOIN ".PASSENGERS_CARD_DETAILS." as pcard ON psplit.friends_p_id = pcard.passenger_id LEFT JOIN ".PASSENGERS." as p ON p.id = psplit.friends_p_id WHERE `trip_id` = '".$tripid."' $stsCnd ORDER BY psplit.split_id DESC";
		return Db::query(Database::SELECT, $sql)->execute()->as_array(); 
	}
	//**Function to set approval status from secondary passenger for split fare**//
	public function setSplitfareApproval($tripId, $friendId, $approvalStatus)
	{
		$update_array = array("approve_status" => $approvalStatus);
		$update_transaction = DB::update(P_SPLIT_FARE)->set($update_array)->where('trip_id', '=', $tripId)->where('friends_p_id', '=', $friendId)->where('approve_status', '=', 'I')->execute();
		return ($update_transaction) ? (($approvalStatus == 'A') ? 1 : 2) : 0; 
	}
	/** function to update split fare payment status **/
	public function updateSplitTransaction($transactionId, $amount, $paymentSts, $gatewaySts, $settlementSts, $tripId, $friendId)
	{
		$update_array = array("transaction_id" => $transactionId, "paid_amount" => $amount, "payment_status" => $paymentSts, "braintree_payment_status" => $gatewaySts, "settlement_status" => $settlementSts);
		return $update_transaction = DB::update(P_SPLIT_FARE)->set($update_array)->where('trip_id', '=', $tripId)->where('friends_p_id', '=', $friendId)->execute();
	}
	/** function to insert failure transaction **/
	public function insertFailureTransact($tripId, $passengerId, $failureAmount, $failureDate)
	{
		$fieldname_array = array('trip_id','passenger_id','failure_amount','failure_date');
		$values_array = array($tripId,$passengerId,$failureAmount,$failureDate);
		$result = DB::insert(P_FAILURE_TRANSACTION, $fieldname_array)->values($values_array)->execute();
		return $result;
	}
	/** Function to get driver's referral code and amount to invite **/
	public function getDriverReferralDetails($driverId)
	{
		$query = "SELECT registered_driver_id,registered_driver_code,registered_driver_code_amount,registered_driver_wallet FROM ".DRIVER_REF_DETAILS." WHERE registered_driver_id = :driverId";
		$result = Db::query(Database::SELECT, $query)->parameters(array(':driverId'=>$driverId))->execute()->as_array();
		return $result; 
		//->order_by('createdate','desc')
	}
	/** Function to get driver's Profile image **/
	public function getDriverProfileImage($driverId)
	{
		$query = "SELECT profile_picture FROM ".PEOPLE." WHERE id = :driverId AND user_type = :userType";
		$result = Db::query(Database::SELECT, $query)->parameters(array(':driverId'=>$driverId,':userType'=>'D'))->execute()->get('profile_picture');
		return $result; 
	}
	/** Function to check the entered referral code of a particular driver is exist or not **/
	public function checkDriverReferralExists($driverReferralCode)
	{
		$query = "SELECT registered_driver_id,registered_driver_code_amount,registered_driver_wallet FROM ".DRIVER_REF_DETAILS." WHERE registered_driver_code = :driverReferralCode";
		$result = Db::query(Database::SELECT, $query)->parameters(array(':driverReferralCode'=>$driverReferralCode))->execute()->as_array();
		return $result; 
	}
	/** Function to check the driver has already used the referral code or not **/
	public function checkDriverUsedReferral($driverId)
	{
		$query = "SELECT count(d_referral_id) as total FROM ".DRIVER_REF_DETAILS." WHERE registered_driver_id = :driverId AND referred_driver_id = :referralDriver";
		$result = Db::query(Database::SELECT, $query)->parameters(array(':driverId'=>$driverId,':referralDriver'=>'0'))->execute()->get('total');
		return $result; 
	}
	/** Function to get the referred driver id **/
	public function getReferredDriver($driverId)
	{
		$query = "SELECT referred_driver_id FROM ".DRIVER_REF_DETAILS." WHERE registered_driver_id = :driverId AND referral_status = :referralStatus";
		$result = Db::query(Database::SELECT, $query)->parameters(array(':driverId'=>$driverId,':referralStatus'=>'0'))->execute()->get('referred_driver_id');
		return $result; 
	}
	//credit card validation for braintree
	public function voidTransactionAfterPreAuthorize($cardId,$preTransactId)
	{
		$paypal_details = $this->payment_gateway_details();	
		$payment_gateway_username = isset($paypal_details[0]['payment_gateway_username'])?$paypal_details[0]['payment_gateway_username']:"";
		$payment_gateway_password = isset($paypal_details[0]['payment_gateway_password'])?$paypal_details[0]['payment_gateway_password']:"";
		$payment_gateway_key = isset($paypal_details[0]['payment_gateway_key'])?$paypal_details[0]['payment_gateway_key']:"";
		$currency_format = isset($paypal_details[0]['gateway_currency_format'])?$paypal_details[0]['gateway_currency_format']:"";
		$payment_method = isset($paypal_details[0]['payment_method'])?$paypal_details[0]['payment_method']:"";
		$payment_types = isset($paypal_details[0]['payment_type'])?$paypal_details[0]['payment_type']:"";
		if($payment_types == 1) {
			$request  = 'METHOD=RefundTransaction';
			$request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
			$request .= '&USER=' . urlencode($payment_gateway_username);
			$request .= '&PWD=' . urlencode($payment_gateway_password);
			$request .= '&SIGNATURE=' . urlencode($payment_gateway_key);
			$request .= '&TRANSACTIONID=' . urlencode($preTransactId);
			$paypal_type=($payment_method =="L")?"live":"sandbox";
			if ($paypal_type=="live") {
				$curl = curl_init('https://api-3t.paypal.com/nvp');
			} else {
				$curl = curl_init('https://api-3t.sandbox.paypal.com/nvp');			
			}
			//	print_r($request);
			curl_setopt($curl, CURLOPT_PORT, 443);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			$nvpstr = curl_exec($curl);
			curl_close($curl);
			$nvpArray = array();
			$intial=0;
			while(strlen($nvpstr))
			{
				//postion of Key
				$keypos= strpos($nvpstr,'=');
				//position of value
				$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
				/*getting the Key and Value values and storing in a Associative Array*/
				$keyval=substr($nvpstr,$intial,$keypos);
				$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
				//decoding the respose
				$nvpArray[urldecode($keyval)] =urldecode( $valval);
				$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
			}
			
			$ack = isset($nvpArray["ACK"]) ? strtoupper($nvpArray["ACK"]):'';
			$code = 0;
			if($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {
				$update_card_passenger_array = array("void_status"=>1);
				$result = $this->update_table(PASSENGERS_CARD_DETAILS,$update_card_passenger_array,'passenger_cardid',$cardId);
				$code = 1;
			}
		} else {
			require_once(APPPATH.'vendor/braintree-payment/lib/Braintree.php');
			$pay_type=($payment_method =="L")?"live":"sandbox";
			try {
				if ($pay_type=="live") 
				{
					Braintree_Configuration::environment('production');
				} else {
					Braintree_Configuration::environment('sandbox');			
				}
				Braintree_Configuration::merchantId($payment_gateway_username);//your_merchant_id
				Braintree_Configuration::publicKey($payment_gateway_password);//your_public_key
				Braintree_Configuration::privateKey($payment_gateway_key);
				$result = Braintree_Transaction::void($preTransactId);
			} catch(Braintree_Exception $message) {
				return 0; 
			}
				
			$code=0;
			if($result->success)
			{
				$update_card_passenger_array = array("void_status"=>1);
				$code = 1;
				$result = $this->update_table(PASSENGERS_CARD_DETAILS,$update_card_passenger_array,'passenger_cardid',$cardId);
			}
			/*else
			{
				$code=0;					
				/*foreach($result->errors->deepAll() AS $error) 
				{
					//print_r($error->code . ": " . $error->message . "\n");
					$code=0;						
				}*
			} */
		}
		return $code;
	}
	/** Save driver wallet requests **/
	public function saveDriverWalletRequest($driverId,$driverWalletAmount,$currentTime)
	{
		$requestStatus = '1';
		$sql="INSERT INTO ".DRIVER_WALLET_REQUESTS."(wallet_request_driver,wallet_request_amount,wallet_request_date,wallet_request_status) VALUES (:driver_id,:driverWalletAmount,:currentTime,:requestStatus)";				
		$result = DB::query(Database::INSERT, $sql)->bind(':driver_id', $driverId)->bind(':driverWalletAmount', $driverWalletAmount)->bind(':currentTime', $currentTime)->bind(':requestStatus', $requestStatus)->execute();
		return $result;
	}
	/** get wallet requested list **/
	public function getWalletAmtRequests($driverId)
	{
		$query = "SELECT wallet_request_id,wallet_request_amount,(CASE WHEN wallet_request_status = '2' THEN 'Approved' WHEN wallet_request_status = '3' THEN 'Reject' ELSE 'Pending' END) as status,wallet_request_date FROM ".DRIVER_WALLET_REQUESTS." WHERE wallet_request_driver = :driverId order by wallet_request_date desc";
		$result = Db::query(Database::SELECT, $query)->parameters(array(':driverId'=>$driverId))->execute()->as_array();
		return $result;
	}
	/** Driver's and passenger's orginal phone number inserted for call masking for a particular trip **/
	public function mobileNumberUpdateForMasking($tripid,$driverid,$passengerid,$driverphone,$passengerphone)
	{
		$fieldname_array = array('trip_id','driver_id','passenger_id','driver_phone','passenger_phone');
		$values_array = array($tripid,$driverid,$passengerid,$driverphone,$passengerphone);
		$result = DB::insert(TWILIO_CALL, $fieldname_array)->values($values_array)->execute();
		return $result;
	}
	/** public function to check whether the trip completed **/
	public function checkTripStatus($tripId)
	{
		$query = "SELECT travel_status FROM ".PASSENGERS_LOG." WHERE passengers_log_id = :tripId";
		$result = Db::query(Database::SELECT, $query)->parameters(array(':tripId'=>$tripId))->execute()->get('travel_status');
		return $result;
	}
	/** Function to get split fare details for a particular trip **/
	public function getSplitFareStatus($tripId)
	{
		$sql = "SELECT p.name as firstname,	p.profile_image,psplit.approve_status,psplit.fare_percentage,psplit.used_wallet_amount FROM ".P_SPLIT_FARE." as psplit LEFT JOIN ".PASSENGERS." as p ON p.id = psplit.friends_p_id WHERE psplit.`trip_id` = :tripId ORDER BY psplit.split_id DESC";
		return Db::query(Database::SELECT, $sql)->parameters(array(':tripId'=>$tripId))->execute()->as_array(); 
	}
	public function updateUsedWalletAmount($updateArr,$passengerId,$tripId)
	{
		return $update_transaction = DB::update(P_SPLIT_FARE)->set($updateArr)->where('trip_id', '=', $tripId)->where('friends_p_id', '=', $passengerId)->execute();
	}
	
	//Common Function for updation in split log table
	public function update_split_log_table($table,$arr,$cond1,$cond2,$cond3,$cond4)
	{
			$result=DB::update($table)->set($arr)->where($cond1,"=",$cond2)->where($cond3,"=",$cond4)->execute();
			return $result;
	}

	/** 
	 * Unfavourite Driver
	 * return int true or false
	 **/

	public function unfavourite_driver($passenger_id, $driver_id)
	{
		$passenger_result = DB::select('favourite_drivers')->from(PASSENGERS)->where(PASSENGERS.'.id','=',$passenger_id)->execute()->as_array();
		if(count($passenger_result) > 0) {
			if(isset($passenger_result[0]["favourite_drivers"]) && $passenger_result[0]["favourite_drivers"] != "") {
				$driver_array = explode(",",$passenger_result[0]["favourite_drivers"]);
				if(count($driver_array) > 0) {
					if(in_array($driver_id,$driver_array)) {
						$list = array_diff(str_getcsv($passenger_result[0]["favourite_drivers"]), array($driver_id));
						$driver_list = implode(",",$list);
						$update_array = ($driver_list != "") ? array("favourite_drivers" => $driver_list) : array("favourite_drivers" => "");
						DB::update(PASSENGERS)->set($update_array)->where(PASSENGERS.'.id','=',$passenger_id)->execute();
						return 1;
					} else {
						return -1;
					}
				} else {
					return -1;
				}
			} else {
				return -1;
			}
		} else {
			return 0;
		}
	}

	/** 
	 * Favourite Driver Lists
	 * return array
	 **/

	public function favourite_driver_list($passenger_id)
	{
		$query = "select * from (select ".TAXI.".taxi_no,".PEOPLE.".id,".PEOPLE.".name,".PEOPLE.".email,".PEOPLE.".phone,".PEOPLE.".profile_picture from ".PASSENGERS." join ".PEOPLE." on FIND_IN_SET(".PEOPLE.".id, ".PASSENGERS.".favourite_drivers) left join ".TAXIMAPPING." on ".TAXIMAPPING.".mapping_driverid = ".PEOPLE.".id left join ".TAXI." on ".TAXI.".taxi_id = ".TAXIMAPPING.".mapping_taxiid where ".PASSENGERS.".id = '$passenger_id' order by ".TAXIMAPPING.".mapping_id desc) as d group by d.id";
		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $result;
	}
	
	public function get_passenger_status($passenger_id)
	{
		$query = "select ".PASSENGERS.".user_status from ".PASSENGERS." where ".PASSENGERS.".id = '$passenger_id'";
		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $result;
	}
	
	/**
	 * Get passenger transaction details
	 * return array()
	 **/

	public function viewtransaction_details($trip_id = "",$userid = "")
	{
		$query = " SELECT pl.actual_pickup_time,t.distance,pl.drop_time,t.tripfare,t.minutes_fare,t.company_tax,ps.used_wallet_amount,t.fare,pl.company_tax AS org_tax,t.waiting_time as taxi_waiting_time,t.waiting_cost as taxi_waiting_cost,t.actual_distance,t.trip_minutes,t.waiting_time,t.distance_unit,t.current_date,t.nightfare_applicable,t.nightfare,t.eveningfare_applicable,t.eveningfare,t.passenger_discount FROM ".PASSENGERS_LOG." as pl left join ".TRANS." as t ON pl.passengers_log_id=t.passengers_log_id left join ".P_SPLIT_FARE." as ps ON ps.trip_id = pl.passengers_log_id Join ".COMPANY." as c ON pl.company_id=c.cid Join ".PEOPLE." as pe ON pe.id=pl.driver_id left join ".TAXI." as ta on ta.taxi_id = pl.taxi_id left join ".MOTORMODEL." as model on model.model_id = ta.taxi_model Join ".PASSENGERS." as pa ON pl.passengers_id=pa.id where ps.friends_p_id = '$userid' and pl.passengers_log_id = '$trip_id'";
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $results;
	}
	
	public function get_passenger_payment_option($trip_id)
	{
		$query = "select ".P_SPLIT_FARE.".passenger_payment_option from ".P_SPLIT_FARE." where ".P_SPLIT_FARE.".trip_id = '$trip_id'";
		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return (count($result) > 0) ? $result[0]["passenger_payment_option"] : 0;
	}
	
	public function checkWalletAmount($passenger_id,$approx_trip_fare)
	{
		$query = "select if(wallet_amount < $approx_trip_fare,'0','1') as count from ".PASSENGERS." where ".PASSENGERS.".id = '$passenger_id'";
		//echo $query; exit;
		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $result[0]["count"];
	}
	
	/** 
	 * Detect wallet amount
	 * return
	 **/
	
	public function minusWalletAmount($passenger_id,$amount)
	{
		$query = "update ".PASSENGERS." set wallet_amount = wallet_amount - $amount where id = '$passenger_id'";
		Db::query(Database::UPDATE, $query)->execute();
	}
	
	public function updatePassengerPercentAmt($passenger_log_id,$passengers_id,$type)
	{
		$update_array = array("fare_percentage" => 0,"appx_amount" => 0);
		if($type == 1) {
			DB::update(P_SPLIT_FARE)
				->set($update_array)
				->where('trip_id', '=', $passenger_log_id)
				->where('friends_p_id', '!=', $passengers_id)
				->where('approve_status', '=', "I")
				->execute();
		} else {
			DB::update(P_SPLIT_FARE)
				->set($update_array)
				->where('trip_id', '=', $passenger_log_id)
				->where('friends_p_id', '!=', $passengers_id)
				->where('approve_status', '=', "A")
				->execute();
		}
	}
	
	/** 
	 * Update Secondary percent and amount to Primary passenger
	 * return
	 **/

	public function updateSecondaryPercentAmtPrimary($trip_id,$secondary_passenger_id,$primary_passenger_id)
	{
		$query = "select fare_percentage,appx_amount from ".P_SPLIT_FARE." where trip_id = '$trip_id' and friends_p_id = '$secondary_passenger_id'";
		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		if(count($result) > 0) {
			$percent = $result[0]["fare_percentage"];
			$appx_amount = $result[0]["appx_amount"];
			$update_query = "update ".P_SPLIT_FARE." set fare_percentage = fare_percentage + $percent, appx_amount = appx_amount + $appx_amount where friends_p_id = '$primary_passenger_id'";
			Db::query(Database::UPDATE, $update_query)->execute();
		}
	}
	
	/** 
	 * Set Split fare
	 * return int true or false
	 **/

	public function set_split_fare($passenger_id, $type)
	{
		$query = "select ".PASSENGERS_CARD_DETAILS.".passenger_cardid from ".PASSENGERS_CARD_DETAILS." where ".PASSENGERS_CARD_DETAILS.".passenger_id = '$passenger_id'";
		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		if(count($result) > 0) {
			DB::update(PASSENGERS)->set(array("split_fare" => $type))->where(PASSENGERS.'.id','=',$passenger_id)->execute();
			return 1;
		} else {
			return 0;
		}
	}
	
	/** 
	 * Get cancel settings
	 * return
	 **/
	
	public function get_cancel_setting($company_id)
	{
		if(FARE_SETTINGS == 2) {
			$query = "select cancellation_fare,skip_credit_card from ".COMPANYINFO." where id = '$company_id'";
			$result = Db::query(Database::SELECT, $query)
					->execute()
					->as_array();
			if(count($result) > 0) {
				if($result[0]["cancellation_fare"] == 1) {
					if($result[0]["skip_credit_card"] == 1) {
						return 0;
					} else {
						return 1;
					}
				} else {
					return 0;
				}
			}
		} else {
			$query = "select cancellation_fare_setting,skip_credit_card from ".SITEINFO."";
			$result = Db::query(Database::SELECT, $query)
					->execute()
					->as_array();
			if(count($result) > 0) {
				if($result[0]["cancellation_fare_setting"] == 1) {
					if($result[0]["skip_credit_card"] == 0) {
						return 0;
					} else {
						return 1;
					}
				} else {
					return 0;
				}
			}
		}
	}
	
	/** 
	 * Get Driver Withdraw Request
	 * return array
	 **/

	public function get_withdraw_request($company_id,$driver_id)
	{
		$query = "select withdraw_request_id,request_id,withdraw_amount,request_date,request_status,brand_type from ".WITHDRAW_REQUEST." where company_id = '$company_id' and requester_id = '$driver_id' and type = '1'";
		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $result;
	}
	
	/** 
	 * Get Driver Withdraw Pending Amount
	 * return array
	 **/

	public function driver_withdraw_pending_amount($company_id,$driver_id)
	{
		$query = "SELECT sum(case when (request_status = '0') then withdraw_amount else NULL end) as pending_amount from ".WITHDRAW_REQUEST." where company_id = '$company_id' and requester_id = '$driver_id' and type = '1'";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	
	/** 
	 * Get Driver Referral Pending Amount
	 * return array
	 **/

	public function driver_referral_pending_amount($driver_id)
	{
		$query = "SELECT sum(case when (wallet_request_status = '1') then wallet_request_amount else NULL end) as driver_referral_wallet_pending_amount from ".DRIVER_WALLET_REQUESTS." where wallet_request_driver = '$driver_id' and wallet_request_status = '1'";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	
	/** 
	 * Insert New Withdraw Request From Driver
	 * return last insert ID
	 **/
	
	public function insert_withdraw_request($company_id,$driver_id,$request_amount)
	{
		$uniqueId = commonfunction::checkRequestID();
		$this->commonmodel=Model::factory('commonmodel');
		$date = $this->commonmodel->getcompany_all_currenttimestamp($company_id);
		$result = DB::insert(WITHDRAW_REQUEST, array('company_id','request_id','brand_type','requester_id','withdraw_amount','request_date','type'))->values(array($company_id,$uniqueId,1,$driver_id,$request_amount,$date,1))->execute();
		/* if($result[0] > 0) {
			$update_query = "update ".PEOPLE." set account_balance = account_balance - '$request_amount' where id = '$driver_id' and user_type = 'D'";
			Db::query(Database::UPDATE, $update_query)->execute();
		} */
		return $result[0];
	}
	
	/** 
	 * Get Withdraw Requested for Detail Page 
	 * return array
	 **/

	public function get_withdraw_deatil($driver_id,$withdraw_request_id)
	{
		$query = "SELECT withdraw_request_id,request_id,withdraw_amount,request_date,request_status,brand_type,company_name from ".WITHDRAW_REQUEST." left join ".COMPANY." as c on c.cid = ".WITHDRAW_REQUEST.".company_id where withdraw_request_id = '$withdraw_request_id' and requester_id = '$driver_id'";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	
	/** 
	 * Get Withdraw Requested Log
	 * return array
	 **/

	public function get_withdraw_log($withdraw_request_id)
	{
		$query = "SELECT log_id,payment_mode,transaction_id,comments,status,file_name,created_date,payment_mode_name from ".WITHDRAW_REQUEST_LOG." left join ".WITHDRAW_PAYMENT_MODE." as p on p.withdraw_payment_mode_id = ".WITHDRAW_REQUEST_LOG.".payment_mode where withdraw_request_id = '$withdraw_request_id' order by log_id desc";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	/** function to get trip cancel alert to driver ( either from passenger or dispatcher ) **/
	public function getTripCancelAlert($driver_id="", $trip_id, $current_time)
	{
		//to get current date
		$current_date = explode(' ',$current_time);
		$date = $current_date[0];
		$sql="SELECT ".PASSENGERS_LOG.".passengers_log_id as trip_id, ".PASSENGERS_LOG.".travel_status as trip_status, ".PASSENGERS_LOG.".notification_status FROM ".PASSENGERS_LOG." WHERE ".PASSENGERS_LOG.".driver_id = '$driver_id' AND ".PASSENGERS_LOG.".passengers_log_id = '$trip_id' AND (".PASSENGERS_LOG.".travel_status = '4' or ".PASSENGERS_LOG.".travel_status = '8') AND ".PASSENGERS_LOG.".notification_status !='5' AND DATE(".PASSENGERS_LOG.".createdate)='$date' ";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	public function get_driver_shift($driver_id)
	{
		$query = "SELECT shift_status from ".DRIVER." where driver_id = '$driver_id'";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result[0]["shift_status"];
	}
	
	/** get Help contents to show in passnger app **/
	public function getHelpContents()
	{
		$query = "SELECT help_id,help_content from ".HELP_CONTENTS;
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}	
	/** To update help comment in transaction table **/
	public function updateTripComment($trip_id,$help_id,$help_comment)
	{
		$commentedDate = date('Y-m-d H:i:s');
		$update_array = array("help_id" => $help_id,"help_comment" => $help_comment,"help_comment_date" => $commentedDate);
		return	DB::update(TRANS)->set($update_array)->where('passengers_log_id', '=', $trip_id)->execute();
	}
	
	//get Driver Earnings for a particular day
	public function getTodayDriverEarnings($driver_id,$start_time,$end_time)
	{
		//$query = "select SUM(".TRANS.".fare) as total_amount, COUNT(".PASSENGERS_LOG.".passengers_log_id) as total_trips from ".PASSENGERS_LOG." join ".TRANS." on ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id where ".PASSENGERS_LOG.".driver_id='$driver_id' and ".PASSENGERS_LOG.".travel_status='1' and ".PASSENGERS_LOG.".createdate >= '$start_time' and ".PASSENGERS_LOG.".createdate <= '$end_time'";
		$today = date('Y-m-d',strtotime($start_time));
		$query = "select SUM(".TRANS.".fare) as total_amount, COUNT(".PASSENGERS_LOG.".passengers_log_id) as total_trips from ".PASSENGERS_LOG." join ".TRANS." on ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id where ".PASSENGERS_LOG.".driver_id='$driver_id' and ".PASSENGERS_LOG.".travel_status='1' and DATE(".PASSENGERS_LOG.".createdate) = '$today'";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	
	//get Driver Earnings for a particular week
	/*public function getWeeklyWiseEarnings($driver_id,$current_time)
	{
		$query = "select SUM(".TRANS.".fare) as total_amount,COUNT(".PASSENGERS_LOG.".passengers_log_id) as total_trips,DATE(passengers_log.createdate) as created_date from ".PASSENGERS_LOG." join ".TRANS." on ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id where ".PASSENGERS_LOG.".driver_id='$driver_id' and ".PASSENGERS_LOG.".travel_status='1' and YEARWEEK(".PASSENGERS_LOG.".createdate) = YEARWEEK('$current_time') GROUP BY DATE( passengers_log.createdate )"; 
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	} */
	
	//get Driver Earnings for a particular week
	public function getWeeklyWiseEarnings($driver_id,$current_time)
	{
		$query = "select SUM(IF(".TRANS.".fare > 0,".TRANS.".fare,0)) total_amount,
					DATE(".PASSENGERS_LOG.".createdate) as created_date
					from ".PASSENGERS_LOG."
					join ".TRANS." on ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id
					where ".PASSENGERS_LOG.".driver_id='$driver_id'
						and ".PASSENGERS_LOG.".travel_status='1'
						and ".PASSENGERS_LOG.".createdate BETWEEN DATE_FORMAT('$current_time' - INTERVAL 1 MONTH, '%Y-%m-01 00:00:00') AND DATE_FORMAT('$current_time', '%Y-%m-%d 23:59:59')
						GROUP BY DATE( ".PASSENGERS_LOG.".createdate )
						ORDER BY DATE( ".PASSENGERS_LOG.".createdate ) DESC";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		$data = $date_array = $final_array = array();
		$current_week_amount = 0;
		if(count($result) > 0){
			foreach($result as $val){
				$array['total_amount'] = $val['total_amount'];
				$array['day'] = date('d', strtotime($val['created_date']));
				$data[$val['created_date']] = $array;
			}
		}
			$days_count_via_month = cal_days_in_month(CAL_GREGORIAN, date('m',strtotime("-30 days")),date('Y'));
			$month_array = array(date('m', strtotime('last month'))=>$days_count_via_month,date('m')=>date('j'));
			$current_date = date('d');
			foreach($month_array as $month => $day_count){
				for($i=1;$i<=$day_count;$i++){
					$day = (strlen($i) > 1)?$i:'0'.$i;
					$date = date('Y').'-'.$month.'-'.$day;
					$month_text = date('M', strtotime($date));
					if (array_key_exists($date, $data)) {
						$arr['trip_amount'][]=$data[$date]['total_amount'];
						$arr['day_list'][]=$data[$date]['day'];
					}else{
						$arr['trip_amount'][]=0;
						$arr['day_list'][]=date('d', strtotime($date));
					}
					if($i<=7){
						$date_array[]= $day;
						if($i==7 || ($i==$current_date && $month == date('m'))){
							$arr['date_text'] = $month_text." 01-07";
							$arr['this_week_earnings'] = array_sum($arr['trip_amount']);
							$final_array[] = $arr;
							$arr=$date_array=array();
						}
					}
					elseif($i>=8 && $i<=14){
						$date_array[]= $day;
						if($i==14 || ($i==$current_date && $month == date('m'))){
							$arr['date_text'] = $month_text." 08-14";
							$arr['this_week_earnings'] = array_sum($arr['trip_amount']);
							$final_array[] = $arr;
							$arr=$date_array=array();
						}
					}
					elseif($i>=15 && $i<=21){
						$date_array[]= $day;
						if($i==21 || ($i==$current_date && $month == date('m'))){
							$arr['date_text'] = $month_text." 15-21";
							$arr['this_week_earnings'] = array_sum($arr['trip_amount']);
							$final_array[] = $arr;
							$arr=$date_array=array();
						}
					}
					elseif($i>=22 && $i<=28){
						$date_array[]= $day;
						if($i==28 || ($i==$current_date && $month == date('m'))){
							$arr['date_text'] = $month_text." 22-28";
							$arr['this_week_earnings'] = array_sum($arr['trip_amount']);
							$final_array[] = $arr;
							$arr=$date_array=array();
						}
					}
					elseif($i>=29 && $i<=$days_count_via_month){
						$date_array[]= $day;
						if($i==$days_count_via_month || ($i==$current_date && $month == date('m'))){
							$arr['date_text'] = $month_text." 29-".$days_count_via_month;
							$arr['this_week_earnings'] = array_sum($arr['trip_amount']);
							$final_array[] = $arr;
							$arr=$date_array=array();
						}
					}	
				}
			}
		return array_reverse($final_array);
	}

	// Driver Login Status
	public function driver_logged_status($data)
	{
		if(isset($data['driver_id']) && !empty($data['driver_id'])){
			$condition = "id = '".$data['driver_id']."'";
			if(isset($data['company_id']) && !empty($data['company_id'])){
				$condition .= " and company_id='".$data['company_id']."'";
			}
			$query="SELECT login_status FROM ".PEOPLE." where $condition";
			$result =  Db::query(Database::SELECT, $query)->execute()->current();
			return (count($result) > 0 && $result['login_status'] == 'S')?1:0;
		}else{
			return 0;
		}
	}
	
	/** Get Company id for the Driver **/
	public function get_driver_companyid($data)
	{
		$company = (isset($data['company_id']))?$data['company_id']:'';
		if(isset($data['driver_id']) && !empty($data['driver_id'])){
			$sql = "SELECT company_id FROM ".PEOPLE." WHERE `id` = '".$data['driver_id']."'";
			$result = Db::query(Database::SELECT, $sql)->execute()->current();
			$company = (count($result) > 0 && $result['company_id']!="")?$result['company_id']:$company;
		}
		return $company;
	}
	
	//Get Driver Current Status if he is break,Avtive,Free
	public function driver_current_trip_status($data)
	{
		$company = (isset($data['company_id']))?$data['company_id']:'';
		$get_company_time_details = $this->get_company_time_details($company);
		$current_time = $get_company_time_details['current_time'];
		$query="SELECT ".TAXIMAPPING.".mapping_taxiid,".DRIVER.".status, ".TAXI.".taxi_model, ".COMPANYINFO.".company_brand_type as brand_type FROM ".DRIVER."
				Left Join ".TAXIMAPPING." on ".TAXIMAPPING.".mapping_driverid = ".DRIVER.".driver_id Left Join ".TAXI." on ".TAXI.".taxi_id = ".TAXIMAPPING.".mapping_taxiid Left Join ".COMPANYINFO." on ".COMPANYINFO.".company_cid = ".TAXIMAPPING.".mapping_companyid where ".DRIVER.".driver_id = '".$data['driver_id']."' and ".DRIVER.".status = 'F' and ".DRIVER.".shift_status = 'IN' and ".TAXIMAPPING.".`mapping_enddate` >= '$current_time'";
			//	echo $query;exit;
		$result =  Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	
	/** Save Driver street trip **/
	public function save_street_trip($data)
	{
		$approx_fare = "";//commonfunction::real_escape_string($val['approx_fare']);
		$city_name = Commonfunction::getCityName($data['pickup_latitude'],$data['pickup_longitude']);
		/*$pickupTimezone = $this->getpickupTimezone($data['pickup_latitude'],$data['pickup_longitude']);*/
		$pickupTimezone = 'Asia/Amman';
		/*$update_time = convert_timezone('now',$pickupTimezone);*/
		$update_time = date('Y-m-d H:i:s');
		$fieldname_array = array('driver_id','taxi_id','company_id','current_location','pickup_latitude','pickup_longitude','city_name','drop_location','drop_latitude', 'drop_longitude','approx_distance','approx_fare','time_to_reach_passen','pickup_time','actual_pickup_time','dispatch_time','travel_status', 'driver_reply','msg_status','notification_status','createdate','booking_from','bookingtype','bookby','taxi_modelid','is_split_trip','driver_app_version');
		$values_array = array($data['driver_id'],$data['taxi_id'],$data['company_id'],urldecode($data['pickup_location']),$data['pickup_latitude'],$data['pickup_longitude'],$city_name,urldecode($data['drop_location']),$data['drop_latitude'],$data['drop_longitude'],$data['approx_distance'],$data['approx_fare'],$data['time_to_reach_passen'],$update_time,$update_time,'0000-00-00 00:00:00',2,'A','R',2,$update_time,2,1,3,$data['motor_model'],0,$data['driver_app_version']);
		$result = DB::insert(PASSENGERS_LOG, $fieldname_array)->values($values_array)->execute();
		return $result[0];
	}

	/*** Street Pickup Passenger Details ***/

	public function getPassengerLogDetail($trip_id = "")
	{
		$sql = "SELECT ".PEOPLE.".phone,".PASSENGERS_LOG.".promocode,".PASSENGERS_LOG.".is_split_trip,".PASSENGERS_LOG.".booking_key,".PASSENGERS_LOG.".passengers_id,".PASSENGERS_LOG.".driver_id,".PASSENGERS_LOG.".taxi_id,".PASSENGERS_LOG.".taxi_modelid, ".PASSENGERS_LOG.".company_id,".PASSENGERS_LOG.".current_location,".PASSENGERS_LOG.".pickup_latitude,".PASSENGERS_LOG.".pickup_longitude, IFNULL(".PASSENGERS_LOG.".drop_location,0) as drop_location,".PASSENGERS_LOG.".drop_latitude,".PASSENGERS_LOG.".drop_longitude,".PASSENGERS_LOG.".no_passengers, ".PASSENGERS_LOG.".approx_distance,".PASSENGERS_LOG.".approx_duration,".PASSENGERS_LOG.".approx_fare,".PASSENGERS_LOG.".fixedprice,".PASSENGERS_LOG.".time_to_reach_passen, ".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".actual_pickup_time,".PASSENGERS_LOG.".drop_time,".PASSENGERS_LOG.".account_id,".PASSENGERS_LOG.".accgroup_id,".PASSENGERS_LOG.".pickupdrop,".PASSENGERS_LOG.".rating,".PASSENGERS_LOG.".comments,".PASSENGERS_LOG.".travel_status,".PASSENGERS_LOG.".driver_reply, ".PASSENGERS_LOG.".msg_status,".PASSENGERS_LOG.".createdate,".PASSENGERS_LOG.".booking_from,".PASSENGERS_LOG.".search_city, ".PASSENGERS_LOG.".sub_logid,".PASSENGERS_LOG.".bookby,".PASSENGERS_LOG.".booking_from_cid,".PASSENGERS_LOG.".distance,".PASSENGERS_LOG.".notes_driver,".PASSENGERS_LOG.".used_wallet_amount, ".PEOPLE.".name AS driver_name,".PASSENGERS.".discount AS passenger_discount,".PEOPLE.".phone AS driver_phone,".PEOPLE.".profile_picture AS driver_photo,".PEOPLE.".device_id AS driver_device_id,".PEOPLE.".device_token AS driver_device_token,".PEOPLE.".device_type AS driver_device_type,".PASSENGERS.".discount AS passenger_discount,".PASSENGERS.".device_id AS passenger_device_id,".PASSENGERS.".device_token AS passenger_device_token,".PASSENGERS.".referred_by AS referred_by,".PASSENGERS.".referrer_earned AS referrer_earned,".PASSENGERS.".wallet_amount,".PASSENGERS.".device_type AS passenger_device_type, ".PASSENGERS.".salutation AS passenger_salutation,".PASSENGERS.".name AS passenger_name,".PASSENGERS.".lastname AS passenger_lastname,".PASSENGERS.".email AS passenger_email,".PASSENGERS.".phone AS passenger_phone,".PASSENGERS.".country_code AS passenger_country_code,".COMPANYINFO.".cancellation_fare as cancellation_nfree,".COMPANYINFO.".company_tax as company_tax,".COMPANYINFO.".company_brand_type as brand_type,IFNULL(".TRANS.".id,0) as transaction_id FROM  ".PASSENGERS_LOG."
		LEFT JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
		LEFT JOIN  ".TRANS." ON (  ".PASSENGERS_LOG.".`passengers_log_id` =  ".TRANS.".`passengers_log_id` ) 
		LEFT JOIN  ".COMPANYINFO." ON (  ".PASSENGERS_LOG.".`company_id` =  ".COMPANYINFO.".`company_cid` ) 
		LEFT JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) 
		WHERE  ".PASSENGERS_LOG.".`passengers_log_id` = '$trip_id'";
		$result = Db::query(Database::SELECT, $sql)->as_object()->execute();
		return $result;
	}
	
	/** get company, model and city details for a trip **/
	public function getTripCompanyModelDets($tripId)
	{
		$sql = "SELECT company_id, search_city, taxi_modelid, booking_from, actual_pickup_time, ".COMPANYINFO.".company_brand_type as brand_type FROM  ".PASSENGERS_LOG." LEFT JOIN  ".COMPANYINFO." ON (".PASSENGERS_LOG.".`company_id` =  ".COMPANYINFO.".`company_cid`) WHERE ".PASSENGERS_LOG.".passengers_log_id = '$tripId' Limit 1";
		return Db::query(Database::SELECT, $sql)->execute()->current();
	}
	
	/** check secondary passenger in trip **/
	public function checkSecondPassengerinTrip($passengerId)
	{
		$currentDate = date('Y-m-d');
		$sql = "SELECT count(passengers_log_id) as total FROM  ".PASSENGERS_LOG." JOIN ".P_SPLIT_FARE." ON ".P_SPLIT_FARE.".trip_id = ".PASSENGERS_LOG.".passengers_log_id WHERE DATE(".PASSENGERS_LOG.".pickup_time) = '$currentDate' and ".P_SPLIT_FARE.".friends_p_id = '$passengerId' and ".P_SPLIT_FARE.".approve_status = 'A' and ((".PASSENGERS_LOG.".travel_status = '9' and ".PASSENGERS_LOG.".driver_reply = 'A') or ".PASSENGERS_LOG.".travel_status = '3' or ".PASSENGERS_LOG.".travel_status = '2' or ".PASSENGERS_LOG.".travel_status = '5')";
		return Db::query(Database::SELECT, $sql)->execute()->get('total');
	}
	
	//Get Driver Recent Trip List
	public function get_recent_driver_trip_list($data)
	{
		$sql = "SELECT ".PASSENGERS_LOG.".drop_time,".TRANS.".fare,".MOTORMODEL.".model_name FROM  ".PASSENGERS_LOG."
				JOIN ".MOTORMODEL." on ".PASSENGERS_LOG.".taxi_modelid = ".MOTORMODEL.".model_id
				JOIN  ".TRANS." ON ( ".PASSENGERS_LOG.".passengers_log_id =  ".TRANS.".passengers_log_id )
				WHERE ".PASSENGERS_LOG.".driver_id = '".$data['driver_id']."'
					and ".PASSENGERS_LOG.".travel_status = 1
					and ".PASSENGERS_LOG.".drop_time != '0000-00-00 00:00:00'
				order by ".PASSENGERS_LOG.".drop_time DESC Limit 3";
		return Db::query(Database::SELECT, $sql)->execute()->as_array();
	}
	
	/** 
	 * Get Driver Withdraw Request
	 * return array
	 **/

	public function search_withdraw_request($data)
	{
		$condition = "company_id = '".$data['company_id']."' and requester_id = '".$data['driver_id']."' and type = '1'";
		if(isset($data['status'])){
			$condition .= " AND request_status ='".$data['status']."'";
		}
		if(isset($data['from']) && $data['from']!=""){
			$condition .= " AND request_date >='".urldecode($data['from'])."'";
		}
		if(isset($data['to']) && $data['to']!=""){
			$condition .= " AND request_date <='".urldecode($data['to'])."'";
		}
		
		$query = "select withdraw_request_id,request_id,withdraw_amount,request_date,request_status from ".WITHDRAW_REQUEST." where $condition";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	
	public function check_company_domain($data)
	{	
		$company_domain = (isset($data['company_domain']))?strtolower(trim($data['company_domain'])):"";
		$sql = "SELECT domain_id,mobileauthcode FROM ".COMPANY_DOMAIN." WHERE company_domain='$company_domain' ";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	public function update_used_status($data){
		$update_array = array("used_status" => 1,'used_date' => strtotime($this->currentdate));
		$result = DB::update(COMPANY_DOMAIN)->set($update_array)->where('domain_id', '=', $data['domain_id'])->execute();
		return $result;
	}
	
	public function get_company_expiry_date()
    	{
		   $sql = "SELECT expiry_date FROM ".SITEINFO." WHERE id=1 ";
		   $result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		   return $result;
	}
	
	//function to get passenger details by referral code
	public function passenger_walletamount_byuserid($user_id)
	{
		$sql = "SELECT id,wallet_amount FROM ".PASSENGERS." WHERE id='$user_id'";
		$result=Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	public function update_driver_reply($trip_id)
	{
		$sql_query = array('travel_status' => '9','driver_reply' => 'C','msg_status' =>'R');
		$update_result = DB::update(PASSENGERS_LOG)->set($sql_query)->where('passengers_log_id', '=' ,$trip_id)->execute();
		return 1;
	}

	public function get_driver_tripsdetails($driver_id)
	{
		$sql = "SELECT passengers_log_id FROM  ".PASSENGERS_LOG." WHERE travel_status in('9','2','3','5') and driver_reply='A' and driver_id='$driver_id' limit 1";

		$result=Db::query(Database::SELECT, $sql)->execute()->as_array();
		return (count($result) > 0)?$result[0]['passengers_log_id']:'';
		
	}
	
	public function check_driver_login_status($id)
	{
		$query= "SELECT status FROM ".PEOPLE." WHERE id = '$id'";

		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return (isset($result[0]['status']))?$result[0]['status']:'';
	}
	
	public function check_passenger_login_status($id)
	{
		$query= "SELECT user_status FROM ".PASSENGERS." WHERE id = '$id'";

		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return (isset($result[0]['user_status']))?$result[0]['user_status']:'';
	}
	
	public function check_assigned_driver_id($trip_id)
	{
		$sql = "SELECT driver_id FROM  ".PASSENGERS_LOG." WHERE passengers_log_id='$trip_id'";

		$result=Db::query(Database::SELECT, $sql)->execute()->as_array();
		return isset($result[0]['driver_id'])?$result[0]['driver_id']:0;
	}
	
	// Passsenger recharge coupon -- Start
	
	public function passenger_recharge_api_validation($array)
	{
		return Validation::factory($array)->rule('passenger_id','not_empty')->rule('recharge_code','not_empty');
	}
	
	
	
	public function check_passenger_recharge_code($array)
	{
		if(TIMEZONE) { 
			$current_time = convert_timezone('now',TIMEZONE);
			$current_date = explode(' ',$current_time);
			$start_time = $current_date[0].' 00:00:01';
			$end_time = $current_date[0].' 23:59:59';
		}else{
			$current_time =	date('Y-m-d H:i:s');
			$start_time = date('Y-m-d').' 00:00:01';
			$end_time = date('Y-m-d').' 23:59:59';
		}
		$driver_id=isset($array['passenger_id'])?$array['passenger_id']:'';
		$coupon_code=isset($array['recharge_code'])?$array['recharge_code']:'';
		
		$sql = "SELECT coupon_id FROM ".PASSENGERS_COUPON." where BINARY coupon_code='$coupon_code' ";  
		$result=Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
		if(count($result)>0)
		{
			 $sql2 = "SELECT coupon_id FROM ".PASSENGERS_COUPON." where BINARY coupon_code='$coupon_code' and coupon_status ='A'  ";  
				$result2=Db::query(Database::SELECT, $sql2)
				->execute() ->as_array();
			//echo count($result2);exit;
			if(count($result2)>0)
			{
					
					$sql3 = "SELECT coupon_id FROM ".PASSENGERS_COUPON." where BINARY coupon_code='$coupon_code' and coupon_used ='0'  ";  
					
					$result3=Db::query(Database::SELECT, $sql3)
					->execute()
					->as_array();
	
				if(count($result3)>0){
 
					$sql4 = "SELECT * FROM ".PASSENGERS_COUPON." where BINARY coupon_code='$coupon_code' and coupon_used ='0' and start_date <=  '$end_time' and expiry_date >= '$current_time' ";  
					$result4=Db::query(Database::SELECT, $sql4)
					->execute()
					->as_array();
					return $result4;
				}
				
				return -3;//Invalid already used  
			}
			return -2;//Blocked recharge code
		}
		return -1;//Invalid recharge code 
		
	}
	
	/***Update a recharge **/
	public function update_passenger_recharge_status($array,$recharge_id,$recharge_amount)
	{
		$passenger_id=isset($array['passenger_id'])?$array['passenger_id']:'';
		$recharge_code=isset($array['recharge_code'])?$array['recharge_code']:'';
		$passenger_balance=$this->get_passenger_acc_details($passenger_id);
		$account_balance=isset($passenger_balance[0]['wallet_amount'])?$passenger_balance[0]['wallet_amount']:'0';
		$driver_company_id=0;
		$total_balance=$account_balance+$recharge_amount;
		
		if(TIMEZONE)
		{
			
			$current_time = convert_timezone('now',TIMEZONE);
		}
		else
		{
			$current_time =	date('Y-m-d H:i:s');
		}
		
		$update_array  = array("coupon_used"=>'1',"passenger_id"=>$passenger_id,"used_date"=>$current_time);
		$recharge_status_update = $this->update_table(PASSENGERS_COUPON,$update_array,'coupon_id',$recharge_id);
			
			/***Udpate total account balance to corresponding driver account***/
		$passenger_update_array  = array("wallet_amount"=>round($total_balance,2));
		$update_driver_amount = $this->update_table(PASSENGERS,$passenger_update_array,'id',$passenger_id);
			
		
		
	}
	
	public function get_passenger_recharge_history_api($passenger_id){
		
		if(TIMEZONE)
		{
			$current_time = convert_timezone('now',TIMEZONE);
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
		$previous_date=date('Y-m-d H:i:s', strtotime($current_time. ' -30 days'));
		//and used_date >= '$previous_date'
		$sql = "select * from ((SELECT coupon_name,".PASSENGERS_COUPON." .amount,'Voucher code' AS recharage_by,CONCAT(SUBSTR(coupon_code, 1,2),REPEAT('X', CHAR_LENGTH(coupon_code) - 6),RIGHT(coupon_code,2)) as coupon_code,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date FROM ".PASSENGERS_COUPON."  where ".PASSENGERS_COUPON." .passenger_id='$passenger_id'  AND used_date <='$current_time' and coupon_status='A' and coupon_used='1' and added_by=0 order by used_date desc ) 
                       UNION (SELECT '' AS coupon_name,passenger_prepaid_amt_details.amount,'CashU' AS recharage_by,'' AS coupon_code,IFNULL(DATE_FORMAT(".PASSENGER_PREPAID_AMT.".updated_date,'%e-%b-%Y %r'), '') AS used_date FROM ".PASSENGER_PREPAID_AMT." where passenger_id='$passenger_id' and updated_date >= '$previous_date' AND updated_date <='$current_time' ORDER BY updated_date DESC )) as temp_table ORDER BY used_date desc limit 0,2";
/*
		$sql = "SELECT coupon_name,amount,coupon_status,CONCAT(SUBSTR(coupon_code, 1,3),REPEAT('X', CHAR_LENGTH(coupon_code) - 2)) AS coupon_code,created_date,start_date,expiry_date AS end_date,coupon_used AS used_status,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date  FROM ".DRIVERS_COUPON." 
    	where ".DRIVERS_COUPON.".driver_id='$driver_id' and used_date >= '$previous_date' AND used_date <='$current_time' and coupon_status='A' and coupon_used='1'  order by used_date desc limit 0,30 ";  
*/		
		$result=Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
		return $result;
		
	}
	
	public function get_passenger_recharge_history($passenger_id,$start=null,$limit=null){
		
		if(TIMEZONE)
		{
			$current_time = convert_timezone('now',TIMEZONE);
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
		$previous_date=date('Y-m-d H:i:s', strtotime($current_time. ' -30 days'));
		//and used_date >= '$previous_date'
		$sql = "select * from ((SELECT coupon_name,".PASSENGERS_COUPON." .amount,'Voucher code' AS recharage_by,CONCAT(SUBSTR(coupon_code, 1,2),REPEAT('X', CHAR_LENGTH(coupon_code) - 6),RIGHT(coupon_code,2)) AS coupon_code,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date FROM ".PASSENGERS_COUPON."  where ".PASSENGERS_COUPON." .passenger_id='$passenger_id'  AND used_date <='$current_time' and coupon_status='A' and coupon_used='1' and added_by=0 order by used_date desc ) 
                       UNION (SELECT '' AS coupon_name,passenger_prepaid_amt_details.amount,'CashU' AS recharage_by,'' AS coupon_code,IFNULL(DATE_FORMAT(".PASSENGER_PREPAID_AMT.".updated_date,'%e-%b-%Y %r'), '') AS used_date FROM ".PASSENGER_PREPAID_AMT." where passenger_id='$passenger_id' and updated_date >= '$previous_date' AND updated_date <='$current_time' ORDER BY updated_date DESC )) as temp_table ORDER BY used_date desc limit $start,$limit";
/*
		$sql = "SELECT coupon_name,amount,coupon_status,CONCAT(SUBSTR(coupon_code, 1,3),REPEAT('X', CHAR_LENGTH(coupon_code) - 2)) AS coupon_code,created_date,start_date,expiry_date AS end_date,coupon_used AS used_status,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date  FROM ".DRIVERS_COUPON." 
    	where ".DRIVERS_COUPON.".driver_id='$driver_id' and used_date >= '$previous_date' AND used_date <='$current_time' and coupon_status='A' and coupon_used='1'  order by used_date desc limit 0,30 ";  
*/		
		$result=Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
		return $result;
		
	}
	
	
	
	/***Update  Blocked voucher card used driver details **/
	public function update_passenger_blockedvoucherdetails($array)
	{
		$driver_id=isset($array['passenger_id'])?$array['passenger_id']:'';
		$recharge_code=isset($array['recharge_code'])?$array['recharge_code']:'';
		if(TIMEZONE){
			$current_time = convert_timezone('now',TIMEZONE);
		}else{
			$current_time =	date('Y-m-d H:i:s');
		}
		/*****Check if already blocked or not *******/
		$check_already_blocked=$this->check_passenger_already_blocked($driver_id,$recharge_code);
		if(count($check_already_blocked)>0)
		{
			return 1;
			
		}else{
		
		/***Blocked voucher card details ***/
			$history_result = DB::insert(PASSENGERS_RECHARGE_HISTORY, array('passenger_id','coupon_code','used_date'))
							->values(array($passenger_id,$recharge_code,$current_time))
							->execute();
			return $history_result;
		/***End Blocked voucher card details ***/
		}
			
			
	}
	
	public function check_passenger_already_blocked($passenger_id,$recharge_code)
	{
		$sql = "SELECT  passenger_id FROM ".PASSENGERS_RECHARGE_HISTORY." where passenger_id ='$passenger_id' AND coupon_code ='$recharge_code'  ";   
		$result=Db::query(Database::SELECT, $sql)
		->execute()->as_array();return $result;
	}
	
	
	// Passsenger recharge coupon -- End
	
	public function get_driver_balance($data)
	{
		$result=DB::select('account_balance')->from(PEOPLE)->where('id',"=",$data['driver_id'])->execute()->as_array();			
		return $result;
	}
	
	public function driver_recharge_api_validation($array)
	{
		return Validation::factory($array)->rule('driver_id','not_empty')->rule('recharge_code','not_empty');
	}
	
	public function check_recharge_code($array)
	{
		if(TIMEZONE) { 
			$current_time = convert_timezone('now',TIMEZONE);
			$current_date = explode(' ',$current_time);
			$start_time = $current_date[0].' 00:00:01';
			$end_time = $current_date[0].' 23:59:59';
		}else{
			$current_time =	date('Y-m-d H:i:s');
			$start_time = date('Y-m-d').' 00:00:01';
			$end_time = date('Y-m-d').' 23:59:59';
		}
		$driver_id=isset($array['driver_id'])?$array['driver_id']:'';
		$coupon_code=isset($array['recharge_code'])?$array['recharge_code']:'';
		
		$sql = "SELECT coupon_id FROM ".DRIVERS_COUPON." where BINARY coupon_code='$coupon_code' ";  
		$result=Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
		if(count($result)>0)
		{
			 $sql2 = "SELECT coupon_id FROM ".DRIVERS_COUPON." where BINARY coupon_code='$coupon_code' and coupon_status ='A'  ";  
				$result2=Db::query(Database::SELECT, $sql2)
				->execute() ->as_array();
			//echo count($result2);exit;
			if(count($result2)>0)
			{
					
					$sql3 = "SELECT coupon_id FROM ".DRIVERS_COUPON." where BINARY coupon_code='$coupon_code' and coupon_used ='0'  ";  
					
					$result3=Db::query(Database::SELECT, $sql3)
					->execute()
					->as_array();
	
				if(count($result3)>0){
 
					$sql4 = "SELECT * FROM ".DRIVERS_COUPON." where BINARY coupon_code='$coupon_code' and coupon_used ='0' and start_date <=  '$end_time' and expiry_date >= '$current_time' ";  
					$result4=Db::query(Database::SELECT, $sql4)
					->execute()
					->as_array();
					return $result4;
				}
				
				return -3;//Invalid already used  
			}
			return -2;//Blocked recharge code
		}
		return -1;//Invalid recharge code 
		
	}
	
	/***Update a recharge **/
	public function update_recharge_status($array,$recharge_id,$recharge_amount)
	{
		$driver_id=isset($array['driver_id'])?$array['driver_id']:'';
		$recharge_code=isset($array['recharge_code'])?$array['recharge_code']:'';
		$driver_balance=$this->get_driver_acc_details($driver_id);
		$account_balance=isset($driver_balance[0]['account_balance'])?$driver_balance[0]['account_balance']:'0';
		$driver_company_id=isset($driver_balance[0]['company_id'])?$driver_balance[0]['company_id']:'0';
		$total_balance=$account_balance+$recharge_amount;
		
		if(TIMEZONE)
		{
			
			$current_time = convert_timezone('now',TIMEZONE);
		}
		else
		{
			$current_time =	date('Y-m-d H:i:s');
		}
		
		$update_array  = array("coupon_used"=>'1',"driver_id"=>$driver_id,"company_id"=>$driver_company_id,"used_date"=>$current_time);
		$recharge_status_update = $this->update_table(DRIVERS_COUPON,$update_array,'coupon_id',$recharge_id);
			
			/***Udpate total account balance to corresponding driver account***/
			$driver_update_array  = array("account_balance"=>round($total_balance,2));
			$update_driver_amount = $this->update_table(PEOPLE,$driver_update_array,'id',$driver_id);
			
			/***Insert Driver recharge history details ***/
			/*$history_result = DB::insert(DRIVER_RECHARGE_HISTORY, array('driver_id','couponid','coupon_code','amount','used_date'))
							->values(array($driver_id,$recharge_id,$recharge_code,$recharge_amount,$current_time))
							->execute();
			return $history_result;*/
			/***End Driver recharge history details ***/
			
			/***End driver balance update****/
		
		
	}
	
	public function get_driver_recharge_history_api($driver_id){
		/*$sql = "SELECT coupon_code,coupon_name,amount,coupon_status,created_date,start_date,end_date,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date  FROM ".DRIVER_RECHARGE_HISTORY." where ".DRIVER_RECHARGE_HISTORY.".driver_id='$driver_id' and coupon_status='A' and used_status='1' order by used_date desc  ";    */
		$cond ="";
	
		if(TIMEZONE)
		{
			$current_time = convert_timezone('now',TIMEZONE);
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
		$previous_date=date('Y-m-d H:i:s', strtotime($current_time. ' -30 days'));
		//and used_date >= '$previous_date'
		$sql = "select * from ((SELECT coupon_name,".DRIVERS_COUPON." .amount,'Voucher code' AS recharage_by,CONCAT(SUBSTR(coupon_code, 1,2),REPEAT('X', CHAR_LENGTH(coupon_code) - 6),RIGHT(coupon_code,2)) as coupon_code,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date FROM ".DRIVERS_COUPON."  where ".DRIVERS_COUPON." .driver_id='$driver_id'  AND used_date <='$current_time' and coupon_status='A' and coupon_used='1' and added_by=0 order by used_date desc ) 
                       UNION (SELECT '' AS coupon_name,driver_prepaid_amt_details.amount,'CashU' AS recharage_by,'' AS coupon_code,IFNULL(DATE_FORMAT(".DRIVER_PREPAID_AMT.".updated_date,'%e-%b-%Y %r'), '') AS used_date FROM ".DRIVER_PREPAID_AMT." where driver_id='$driver_id' and updated_date >= '$previous_date' AND updated_date <='$current_time' ORDER BY updated_date DESC )) as temp_table ORDER BY used_date desc limit 0,2 ";
/*
		$sql = "SELECT coupon_name,amount,coupon_status,CONCAT(SUBSTR(coupon_code, 1,3),REPEAT('X', CHAR_LENGTH(coupon_code) - 2)) AS coupon_code,created_date,start_date,expiry_date AS end_date,coupon_used AS used_status,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date  FROM ".DRIVERS_COUPON." 
    	where ".DRIVERS_COUPON.".driver_id='$driver_id' and used_date >= '$previous_date' AND used_date <='$current_time' and coupon_status='A' and coupon_used='1'  order by used_date desc limit 0,30 ";  
*/		
		$result=Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
		
		return $result;
		
	}
	
	public function get_driver_recharge_history($driver_id,$start=null,$limit=null){
		/*$sql = "SELECT coupon_code,coupon_name,amount,coupon_status,created_date,start_date,end_date,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date  FROM ".DRIVER_RECHARGE_HISTORY." where ".DRIVER_RECHARGE_HISTORY.".driver_id='$driver_id' and coupon_status='A' and used_status='1' order by used_date desc  ";    */
		$cond ="";
	
		if(TIMEZONE)
		{
			$current_time = convert_timezone('now',TIMEZONE);
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
		$previous_date=date('Y-m-d H:i:s', strtotime($current_time. ' -30 days'));
		//and used_date >= '$previous_date'
		$sql = "select * from ((SELECT coupon_name,".DRIVERS_COUPON." .amount,'Voucher code' AS recharage_by,CONCAT(SUBSTR(coupon_code, 1,2),REPEAT('X', CHAR_LENGTH(coupon_code) - 6),RIGHT(coupon_code,2)) as coupon_code,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date FROM ".DRIVERS_COUPON."  where ".DRIVERS_COUPON." .driver_id='$driver_id'  AND used_date <='$current_time' and coupon_status='A' and coupon_used='1' and added_by=0 order by used_date desc ) 
                       UNION (SELECT '' AS coupon_name,driver_prepaid_amt_details.amount,'CashU' AS recharage_by,'' AS coupon_code,IFNULL(DATE_FORMAT(".DRIVER_PREPAID_AMT.".updated_date,'%e-%b-%Y %r'), '') AS used_date FROM ".DRIVER_PREPAID_AMT." where driver_id='$driver_id' and updated_date >= '$previous_date' AND updated_date <='$current_time' ORDER BY updated_date DESC )) as temp_table ORDER BY used_date desc limit $start,$limit ";
/*
		$sql = "SELECT coupon_name,amount,coupon_status,CONCAT(SUBSTR(coupon_code, 1,3),REPEAT('X', CHAR_LENGTH(coupon_code) - 2)) AS coupon_code,created_date,start_date,expiry_date AS end_date,coupon_used AS used_status,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date  FROM ".DRIVERS_COUPON." 
    	where ".DRIVERS_COUPON.".driver_id='$driver_id' and used_date >= '$previous_date' AND used_date <='$current_time' and coupon_status='A' and coupon_used='1'  order by used_date desc limit 0,30 ";  
*/		
		$result=Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
		
		return $result;
		
	}
	
	//Driver Profile
	/*public function driver_profile($userid) 
	{		
		//$query= "SELECT salutation,name,".PEOPLE.".id as userid,lastname,email,phone,address,password,otp,photo,device_type,device_token,login_status,user_type,driver_referral_code,notification_setting,company_id,driver_license_id,profile_picture,".COMPANY.".bankname,".COMPANY.".bankaccount_no,".COMPANY.".userid as company_ownerid FROM ".PEOPLE." JOIN  ".COMPANY." ON (  ".PEOPLE.".`company_id` =  ".COMPANY.".`cid` )  WHERE id = '$userid' AND user_type = 'D' ";	
		$query= "SELECT salutation,name,".PEOPLE.".id as userid,lastname,email,phone,address,password,otp,photo,device_type,device_token,login_status,user_type,driver_referral_code,notification_setting,account_balance,company_id,driver_license_id,profile_picture,".COMPANY.".bankname,".COMPANY.".bankaccount_no,".COMPANY.".userid as company_ownerid,".TAXI.".taxi_no,".TAXIMAPPING.".mapping_startdate,".TAXIMAPPING.".mapping_enddate,".MOTORMODEL.".model_name,".MOTORMODEL.".admin_slab_commission,".MOTORMODEL.".driver_commission_limit FROM ".PEOPLE." LEFT JOIN  ".COMPANY." ON (  ".PEOPLE.".`company_id` =  ".COMPANY.".`cid` ) LEFT JOIN ".TAXIMAPPING." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id LEFT JOIN " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id LEFT JOIN ".MOTORMODEL." on ".TAXI.".taxi_model = ".MOTORMODEL.".model_id WHERE id = '$userid' AND user_type = 'D' AND ".TAXIMAPPING.".mapping_status = 'A' ";	
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $result;	
	} */
	
	/***Update  Blocked voucher card used driver details **/
	public function update_blockedvoucherdetails($array)
	{
		$driver_id=isset($array['driver_id'])?$array['driver_id']:'';
		$recharge_code=isset($array['recharge_code'])?$array['recharge_code']:'';
		if(TIMEZONE){
			$current_time = convert_timezone('now',TIMEZONE);
		}else{
			$current_time =	date('Y-m-d H:i:s');
		}
		/*****Check if already blocked or not *******/
		$check_already_blocked=$this->check_already_blocked($driver_id,$recharge_code);
		if(count($check_already_blocked)>0)
		{
			return 1;
			
		}else{
		
		/***Blocked voucher card details ***/
			$history_result = DB::insert(DRIVER_RECHARGE_HISTORY, array('driver_id','coupon_code','used_date'))
							->values(array($driver_id,$recharge_code,$current_time))
							->execute();
			return $history_result;
		/***End Blocked voucher card details ***/
		}
			
			
	}
	
	public function get_driver_acc_details($userid) 
	{		
		$query= "SELECT account_balance,referral_amount,driver_referral_code,company_id FROM ".PEOPLE."  WHERE id = '$userid' AND user_type = 'D'  ";	
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
			return $result;
	}
	
	
	public function get_passenger_acc_details($userid) 
	{		
		$query= "SELECT wallet_amount FROM ".PASSENGERS."  WHERE id = '$userid'";	
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
			return $result;
	}
	
	public function check_already_blocked($driver_id,$recharge_code)
	{
		$sql = "SELECT  driver_id FROM ".DRIVER_RECHARGE_HISTORY." where driver_id ='$driver_id' AND coupon_code ='$recharge_code'  ";   
		$result=Db::query(Database::SELECT, $sql)
		->execute()->as_array();return $result;
	}
	
	
}
?>
