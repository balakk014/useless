<?php defined('SYSPATH') OR die('No Direct Script Access');

/******************************************

* Contains Users module details

* @Package: Passengers

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************/

Class Model_Passengers extends Model
{
	public function __construct()
	{	
		$this->session = Session::instance();	
		$this->name = $this->session->get("name");
		$this->admin_userid = $this->session->get("passenger_id");
		$this->admin_email = $this->session->get("email");
		$this->user_admin_type = $this->session->get("user_type");
		$Commonmodel = Model::factory('Commonmodel');
		$this->currentdate = $Commonmodel->getcompany_all_currenttimestamp(COMPANY_CID);


        $this->lat='';
        $this->lon='';
        if( isset($_SESSION['id']) && ($_SESSION['id']!='') )
        { 
                $this->lat=isset($_SESSION['ip_lati'])?$_SESSION['ip_lati']:LOCATION_LATI;  
                $this->lon=isset($_SESSION['ip_lng'])?$_SESSION['ip_lng']:LOCATION_LONG;                 
        }
        else{ 
                $this->lat=isset($_COOKIE['c_lati'])?$_COOKIE['c_lati']:LOCATION_LATI;    
                $this->lon=isset($_COOKIE['c_lng'])?$_COOKIE['c_lng']:LOCATION_LONG;    
         }
	}
	/**Validating User SignUP details**/
	
	/**Validating User SignUP details**/
	public function validate_signup($arr) 
	{
		return Validation::factory($arr)       
		
			->rule('name', 'not_empty')
			->rule('name', 'min_length', array(':value', '4'))
			->rule('name', 'max_length', array(':value', '32'))

			->rule('lastname', 'not_empty')
			->rule('lastname', 'min_length', array(':value', '1'))
			->rule('lastname', 'max_length', array(':value', '32'))

			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('password', 'not_empty')
			->rule('password', 'min_length', array(':value', '5'))
			->rule('password', 'max_length', array(':value', '50'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))

			/*->rule('repassword', 'not_empty')
			->rule('repassword', 'min_length', array(':value', '5'))
			->rule('repassword', 'max_length', array(':value', '50'))
			->rule('repassword',  'matches', array(':validation', 'password', 'repassword'))*/;
	}
	
	public function validate_twittersignup($arr) 
	{
		return Validation::factory($arr)       
		
			->rule('name', 'not_empty')
			->rule('name', 'min_length', array(':value', '4'))
			->rule('name', 'max_length', array(':value', '32'))

			->rule('lastname', 'not_empty')
			->rule('lastname', 'min_length', array(':value', '1'))
			->rule('lastname', 'max_length', array(':value', '32'))

			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('password', 'not_empty')
			->rule('password', 'min_length', array(':value', '5'))
			->rule('password', 'max_length', array(':value', '50'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('account_type', 'not_empty')
			;
	}
	
	
    
	/**User Signup**/
	/**User Signup**/
	public function signup($val,$otp,$referred_passenger_id,$random_key=null) 
	{
		$username = Html::chars($val['name']);
		$password = text::random($type = 'alnum', $length = 6);		
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

				/** Referral key generator **/				
		/*$fieldname_array = array('name','email','password','org_password','phone','address','creditcard_no','creditcard_cvv','expdatemonth','expdateyear','activation_key','activation_status','user_status','created_date','passenger_cid');
		//$values_array = array($val['name'],$val['email'],md5($password),$password,$val['phone'],$val['address'],$val['creditcard_no'],$val['creditcard_cvv'],$padDateMonth,$val['expdateyear'],$random_key,'0','A',$this->currentdate);
		$values_array = array($val['name'],$val['email'],md5($password),$password,$val['phone'],$val['address'],$val['creditcard_no'],$val['creditcard_cvv'],$val['expdatemonth'],$val['expdateyear'],$random_key,'0','A',$this->currentdate,COMPANY_CID);
		$result = DB::insert(PASSENGERS, $fieldname_array)
					->values($values_array)
					->execute();*/
		$name = $val['name'];
		$lastname = $val['lastname'];
		$salutation = $val['salutation'];
		$email = $val['email'];
		$mdpassword = md5($password);
		$phone = $val['phone'];
		$address = $val['address'];
		$creditcard_no = encrypt_decrypt('encrypt',$val['creditcard_no']);
		$creditcard_cvv = $val['creditcard_cvv'];
		$expdatemonth = $val['expdatemonth'];
		$expdateyear = $val['expdateyear'];
		$cdate = $this->currentdate;
		
		
		/*$query = "insert into ".PASSENGERS."(name,email,password,org_password,phone,address,creditcard_no,creditcard_cvv,expdatemonth,expdateyear,activation_key,
activation_status,user_status,created_date,passenger_cid)values('".$name."','".$email."','".$mdpassword."','".$password."','".$phone."','".$address."','".$creditcard_no."','".$creditcard_cvv."','".$expdatemonth."','".$expdateyear."','".$random_key."','0','A','".$cdate."','".COMPANY_CID."')";*/

		$query = "insert into ".PASSENGERS."(name,lastname,salutation,email,password,org_password,otp,phone,address,referral_code,creditcard_no,creditcard_cvv,expdatemonth,expdateyear,activation_key,
activation_status,user_status,created_date,updated_date,passenger_cid)values('".$name."','".$lastname."','".$salutation."','".$email."','".$mdpassword."','".$password."','".$otp."','".$phone."','".$address."','".$referral_code."','".$creditcard_no."','".$creditcard_cvv."','".$expdatemonth."','".$expdateyear."','".$random_key."','0','A','".$cdate."','".$cdate."','".COMPANY_CID."')";
		
		$result = Db::query(Database::INSERT, $query)
		->execute();

		if($result){								
		
				$email = DB::select()->from(PASSENGERS)
						       ->where('email', '=', $val['email'])
						 		->execute()
					           ->as_array(); 
		         if(count($email)>0)
				{
					$passenger_id = $email[0]['id'];
				}
				else
				{
					$passenger_id = '';
				}		          
				if($referred_passenger_id != "")
				{
				$ref_result = DB::insert(PASSENGERS_REF_DETAILS, array('referred_passenger_id','registered_passenger_id','referral_status','referrer_earned','earned_amount'))
				->values(array($referred_passenger_id,$passenger_id,'S','N',''))
				->execute();					
				}
				
				return 1;
			
		}else{
				return 0;
		}
	}
	/** Save Customer Booking **/
	public function savebooking($val,$usrid,$travel_status)
	{ 
		$pickup_time = commonfunction::real_escape_string($val['timepicker1']);
		$pickupdrop = commonfunction::real_escape_string($val['pickupdrop']);
		$waitingtime = commonfunction::real_escape_string($val['waitingtime']);
		$notes_driver = commonfunction::real_escape_string($val['notes']);
		$pass_logid = $val['pass_logid'];
		$company_id = $this->get_company_id(commonfunction::real_escape_string($val['driver_id']));
		// Get Pickup & Drop location Lat & Long using Google API 
		$pickup = $this->getLatLong(commonfunction::real_escape_string($val['c_location']));
		$pickup_latitude = $pickup[0];
		$pickup_longitude = $pickup[1];
		$drop_latitude = $drop_longitude="";
		if(isset($_SESSION['search_city']))
		{
			$model_base_query = "select city_id from ".CITY." where ".CITY.".city_name like '%".$_SESSION['search_city']."%' limit 0,1"; 
		}		
		else
		{
			$model_base_query = "select city_id from ".CITY." where ".CITY.".default=1"; 
		}

		$model_base_result =  Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array(); 

		if(count($model_base_result) > 0)
		{	
			$city_id = $model_base_result[0]['city_id'];
		}
		else
		{
			$model_base_query = "select city_id from ".CITY." where ".CITY.".default=1"; 
			$model_base_result =  Db::query(Database::SELECT, $model_base_query)
						->execute()
						->as_array(); 

			$city_id = $model_base_result[0]['city_id'];
		}
		/******** Fare Details *******************/
		$get_taxi_fare_based_model = $this->get_current_taxi_details($val["driver_id"],$val["taxi_id"],$city_id);		
		//$get_taxi_fare_waiting_charge = $this->get_taxi_fare_waiting_charge(commonfunction::real_escape_string($val['taxi_id']));	
		//$fare_per_hour = $get_taxi_fare_waiting_charge[0]->taxi_fare_km;			
			//$taxi_fare_km = $get_taxi_fare_based_model[0]['taxi_fare_km'];	
			$base_fare = $get_taxi_fare_based_model[0]['base_fare'];
			$min_km_range = $get_taxi_fare_based_model[0]['min_km'];
			$min_fare = $get_taxi_fare_based_model[0]['min_fare'];
			$cancellation_fare = $get_taxi_fare_based_model[0]['cancellation_fare'];
			$below_above_km_range = $get_taxi_fare_based_model[0]['below_above_km'];
			$below_km = $get_taxi_fare_based_model[0]['below_km'];
			$above_km = $get_taxi_fare_based_model[0]['above_km'];
			$night_charge = $get_taxi_fare_based_model[0]['night_charge'];
			$night_timing_to = $get_taxi_fare_based_model[0]['night_timing_to'];
			$night_fare = $get_taxi_fare_based_model[0]['night_fare'];	
			$company_tax = $get_taxi_fare_based_model[0]['company_tax'];	
		$approx_fare="";		
		if($val['d_location'] != '')
		{
		$drop = $this->getLatLong(commonfunction::real_escape_string($val['d_location']));
		$drop_latitude = $drop[0];
		$drop_longitude = $drop[1];	
			$distance = $this->Haversine($pickup, $drop);
			//Converting to Km

							$approx_distance = round($distance * 1.609344,2);						
							if($approx_distance < $min_km_range)
							{
								$approx_fare = $min_fare;
							}
							else if($approx_distance <= $below_above_km_range)
							{
								$fare = $approx_distance * $below_km;
								$approx_fare  = 	$fare + $base_fare ;
							}
							else if($approx_distance > $below_above_km_range)
							{
								$fare = $approx_distance * $above_km;
								$approx_fare  = 	$fare + $base_fare ;
							}	
							$tax_amount = "";
							if($company_tax > 0)
							{
									$tax_amount = ($company_tax/100)*$approx_fare;//night_charge%100;							
									$approx_fare =  $approx_fare+$tax_amount;
							}	
		}
		else
		{	
			$drop = '';
			$drop_latitude = '';
			$drop_longitude = '';
			
			//Converting to Km
			$approx_distance = "";						
			$approx_fare = "";			
		}
		/************** End *****************/		
		$company_tax = $get_taxi_fare_based_model[0]['company_tax'];	
		if(count($company_id)>0){ $d_company_id = $company_id[0]['company_id'];}else{$d_company_id=0;}


				/** Booking key generator **/
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

				/** Booking key generator **/


		$fieldname_array = array('booking_key','passengers_id','driver_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude','no_passengers','approx_distance','approx_fare','pickup_time','pickupdrop','waitingtime','travel_status','createdate','taxi_id','search_city','sub_logid','booking_from_cid','company_tax','bookingtype','bookby','notes_driver');	

			$time = date ('H:i:s',strtotime($pickup_time));
			$current_datetime = convert_timezone('now',TIMEZONE); 
			$curretnt_datetime_split = explode(' ',$current_datetime);
			$update_time = $curretnt_datetime_split[0].' '.$time;


		
		$values_array = array($booking_key,$usrid,commonfunction::real_escape_string($val['driver_id']),$d_company_id,commonfunction::real_escape_string($val['c_location']),$pickup_latitude,$pickup_longitude,commonfunction::real_escape_string($val['d_location']),$drop_latitude,$drop_longitude,commonfunction::real_escape_string($val['n_passengers']),$approx_distance,$approx_fare,$update_time,$pickupdrop,$waitingtime,$travel_status,$this->currentdate,commonfunction::real_escape_string($val['taxi_id']),$city_id,$pass_logid,$d_company_id,$company_tax,'1','1',$notes_driver);


		$driver_availability_result = $this->get_driver_availability(commonfunction::real_escape_string($val['driver_id']),$update_time);
		//echo count($driver_availability_result);
		//exit;
		if(count($driver_availability_result) == 0)
		{
			$result = DB::insert(PASSENGERS_LOG, $fieldname_array)
						->values($values_array)
						->execute();

			if($pass_logid =='')
			{
				$update_pass_logid= DB::update(PASSENGERS_LOG)->set(array('sub_logid' =>$result[0] ))
				->where('passengers_log_id','=',$result[0])
				->execute();	
			}
			if($result)
			{
				$array = array();
				//return array(1,$result);
				$array['result'] = 1 ;
				$array['pass_logid'] = $result[0];
				return $array;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return 2;
		}
			
	}
	/** Save Customer Booking **/
	public function updatebooking($val,$usrid,$passengerlog_id)
	{
		$pickup_time = commonfunction::real_escape_string($val['timepicker1']);
		$pickupdrop = commonfunction::real_escape_string($val['pickupdrop']);
		$waitingtime = commonfunction::real_escape_string($val['waitingtime']);
		$company_id = $this->get_company_id(commonfunction::real_escape_string($val['driver_id']));
		$notes_driver = commonfunction::real_escape_string($val['notes']);
		// Get Pickup & Drop location Lat & Long using Google API 
		$pickup = $this->getLatLong(commonfunction::real_escape_string($val['c_location']));
		$pickup_latitude = $pickup[0];
		$pickup_longitude = $pickup[1];
		$pass_logid = $val['pass_logid'];

		if(isset($_SESSION['search_city']))
		{
			$model_base_query = "select city_id from ".CITY." where ".CITY.".city_name like '%".$_SESSION['search_city']."%' limit 0,1"; 
		}		
		else
		{
			$model_base_query = "select city_id from ".CITY." where ".CITY.".default=1"; 
		}

		$model_base_result =  Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array(); 

		if(count($model_base_result) > 0)
		{	
			$city_id = $model_base_result[0]['city_id'];
		}
		else
		{
			$model_base_query = "select city_id from ".CITY." where ".CITY.".default=1"; 
			$model_base_result =  Db::query(Database::SELECT, $model_base_query)
						->execute()
						->as_array(); 

			$city_id = $model_base_result[0]['city_id'];
		}
		/******** Fare Details *******************/
		$get_taxi_fare_based_model = $this->get_current_taxi_details($val["driver_id"],$val["taxi_id"],$city_id);		
		//$get_taxi_fare_waiting_charge = $this->get_taxi_fare_waiting_charge(commonfunction::real_escape_string($val['taxi_id']));	
		//$fare_per_hour = $get_taxi_fare_waiting_charge[0]->taxi_fare_km;			
			$taxi_fare_km = $get_taxi_fare_based_model[0]['taxi_fare_km'];	
			$base_fare = $get_taxi_fare_based_model[0]['base_fare'];
			$min_fare = $get_taxi_fare_based_model[0]['min_fare'];
			$cancellation_fare = $get_taxi_fare_based_model[0]['cancellation_fare'];
			$below_km = $get_taxi_fare_based_model[0]['below_km'];
			$above_km = $get_taxi_fare_based_model[0]['above_km'];
			$night_charge = $get_taxi_fare_based_model[0]['night_charge'];
			$night_timing_to = $get_taxi_fare_based_model[0]['night_timing_to'];
			$night_fare = $get_taxi_fare_based_model[0]['night_fare'];	
				
		if($val['d_location'] != '')
		{
		$drop = $this->getLatLong(commonfunction::real_escape_string($val['d_location']));
		$drop_latitude = $drop[0];
		$drop_longitude = $drop[1];	
			$distance = $this->Haversine($pickup, $drop);
			//Converting to Km

			$approx_distance = round($distance * 1.609344,2);						
			if($approx_distance < 1)
			{
				$approx_fare = $min_fare;
			}
			else if($approx_distance <= 10)
			{
				$approx_fare = $approx_distance * $below_km;
			}
			else if($approx_distance > 10)
			{
				$approx_fare = $approx_distance * $above_km;
			}	
		}
		else
		{	
			$drop = '';
			$drop_latitude = '';
			$drop_longitude = '';
			
			//Converting to Km
			$approx_distance = "";						
			$approx_fare = "";			
		}
		
		/************** End *****************/			
		if(count($company_id)>0){ $d_company_id = $company_id[0]['company_id'];}else{$d_company_id=0;}

		$time = date ('H:i:s',strtotime($pickup_time));
		$current_datetime = convert_timezone('now',TIMEZONE); 
		$curretnt_datetime_split = explode(' ',$current_datetime);
		$update_time = $curretnt_datetime_split[0].' '.$time;
		$company_tax = $get_taxi_fare_based_model[0]['company_tax'];


		
		if($pass_log_id !='')
		{
			$fieldname_array = array('passengers_id','driver_id','company_id','notes_driver','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude','no_passengers','approx_distance','approx_fare','pickup_time','pickupdrop','waitingtime','createdate','taxi_id','search_city','sub_logid','booking_from_cid','company_tax','bookingtype','bookby');
			$values_array = array($usrid,commonfunction::real_escape_string($val['driver_id']),$d_company_id,commonfunction::real_escape_string($val['c_location']),$pickup_latitude,$pickup_longitude,commonfunction::real_escape_string($val['d_location']),$drop_latitude,$drop_longitude,commonfunction::real_escape_string($val['n_passengers']),$approx_distance,$approx_fare,$update_time,$pickupdrop,$waitingtime,$this->currentdate,commonfunction::real_escape_string($val['taxi_id'],$city_id,$pass_logid,$d_company_id,$company_tax,'1','1'));
		}
		else
		{
			$fieldname_array = array('passengers_id','driver_id','company_id','current_location','notes_driver','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude','no_passengers','approx_distance','approx_fare','pickup_time','pickupdrop','waitingtime','createdate','taxi_id','search_city','booking_from_cid','company_tax','bookingtype','bookby');
			$values_array = array($usrid,commonfunction::real_escape_string($val['driver_id']),$d_company_id,commonfunction::real_escape_string($val['c_location']),$pickup_latitude,$pickup_longitude,commonfunction::real_escape_string($val['d_location']),$drop_latitude,$drop_longitude,commonfunction::real_escape_string($val['n_passengers']),$approx_distance,$approx_fare,$update_time,$pickupdrop,$waitingtime,$this->currentdate,commonfunction::real_escape_string($val['taxi_id'],$city_id,$d_company_id,$company_tax,'1','1'));

		}
		$driver_availability_result = $this->get_driver_availability(commonfunction::real_escape_string($val['driver_id']),$update_time);
		//echo count($driver_availability_result);
		//exit;
		if(count($driver_availability_result) == 0)
		{

		if($pass_log_id !='')
		{
			$changearr = array('current_location'=>commonfunction::real_escape_string($val['c_location']),'drop_location'=>commonfunction::real_escape_string($val['d_location']),'no_passengers'=>commonfunction::real_escape_string($val['n_passengers']),'pickup_time'=>$update_time,'driver_reply'=>'','driver_id'=>commonfunction::real_escape_string($val['driver_id']),'sub_logid' => $passengerlog_id);
		}
		else
		{
			$changearr = array('current_location'=>commonfunction::real_escape_string($val['c_location']),'drop_location'=>commonfunction::real_escape_string($val['d_location']),'no_passengers'=>commonfunction::real_escape_string($val['n_passengers']),'pickup_time'=>$update_time,'driver_reply'=>'','driver_id'=>commonfunction::real_escape_string($val['driver_id']));
		}	
			$result = DB::update(PASSENGERS_LOG)->set($changearr)
						->where('passengers_log_id','=',$passengerlog_id)
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
			return 2;
		}
			
	}
	/** Get driver status **/
	public function get_driver_request($passenger_log_id="")
	{
		$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `passengers_log_id` = '".$passenger_log_id."'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}
	
		/** Get passenger log id **/
	public function get_passenger_id()
	{
		$sql = "SELECT MAX(passengers_log_id) as id FROM ".PASSENGERS_LOG."";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
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

	/** Driver availability **/
	public function get_driver_availability($driver_id,$pickup_time)
	{
		//$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `pickup_time` < '".$pickup_time."' and `driver_id` = '".$driver_id."' and `driver_reply` = ''";
		$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `pickup_time` = '".$pickup_time."' and `driver_id` = '".$driver_id."' and `driver_reply` = 'A' and 'travel_status' = 0";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}
	/** Get Company id for the Driver **/
	public function get_company_id($driver_id)
	{
		$sql = "SELECT * FROM ".PEOPLE." WHERE `id` = '".$driver_id."'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	/** Account Validation **/
	public function account_confirmation($key="")
	{

		$checkkey = DB::select()->from(PASSENGERS)
						       ->where('activation_key', '=', $key)
						 		->execute()
					           ->as_array(); 
		if(count($checkkey) > 0)
		{
				//print_r($checkkey);
				$actarr = array('activation_status' => '1');
				$accountactivation_update=DB::update(PASSENGERS)->set($actarr)
						->where('id','=',$checkkey['0']['id'])
						->and_where('activation_key', '=', $key)
						->execute();	
				if($accountactivation_update > 0)
				{					
					return 1;
				}
				else
				{
					return 2;
				}				
		}
		else
		{
			return 3;
		}			
	}
	
	// Check Email Exist or Not while Updating User Details
	public function check_passenger_email_update($email="",$id="")
	{
		$sql = "SELECT email FROM ".PASSENGERS." WHERE email='$email' AND id !='$id' ";   
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

	public function check_passenger_phone_update($email="",$id="")
	{
		//$sql = "SELECT phone FROM ".PASSENGERS." WHERE phone='$email' AND id !='$id' AND passenger_cid=".COMPANY_CID;   
		$sql = "SELECT phone FROM ".PASSENGERS." WHERE phone='$email' AND id !='$id'";   
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
	
	/**Validating Login Datas**/
	public function validate_login($arr) 
	{
		
		if($arr['password'] == 'Password'){ $arr['password'] = "";}

		return Validation::factory($arr)       
            ->rule('email','not_empty')
			->rule('email','email')
			->rule('password', 'not_empty')
			->rule('password','min_length',array(':value','5'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'));

	}

	/**User Login**/
	public function login($phone,$pwd,$remember) 
	{
		$password = Html::chars(md5($pwd));
		$query= "SELECT * FROM ".PASSENGERS." WHERE phone = '$phone' AND password='$password'"; // and passenger_cid=".COMPANY_CID;// AND activation_status = '1'
		$result =  Db::query(Database::SELECT, $query)
			->execute()
			->as_array();

			if(count($result) == 1 && $result[0]['activation_status'] == '1')
			{
				if(count($result) == 1 && $result[0]['user_status'] == 'A')
					{
						//Whenever user logged into the application, Add their IP and other details..
						$login_time = $this->currentdate;			
						$sql_query = array('last_login'=>$login_time,'login_status'=>'A'); 		                
						$result_login = DB::update(PASSENGERS)
										->set($sql_query)
										->where('phone', '=', $phone)
										//->where('passenger_cid', '=',COMPANY_CID)
										->execute();								
									$this->session->set("id",$result["0"]["id"]);
									$this->session->set("email",$result["0"]["email"]);
									$this->session->set("phone",$result["0"]["phone"]);
									$this->session->set("name",$result["0"]["name"]);
									$this->session->set("usertype","passengers");
									if($remember == 'yes')
									 {
										 setcookie("passenger_phone",$phone, time()+3600*24,'/');
										 setcookie("passenger_password",$pwd, time()+3600*24,'/');
									 }

									return 1;
					}
					elseif(count($result) == 1 && $result[0]['user_status'] == 'D')
					{
						return -1;
					}
					else
					{
						return 0;
					}
			}
			elseif(count($result) == 1 && $result[0]['activation_status'] == '0')
			{
				return -2;
			}
			else
			{
				return 0;
			}
	}
	
/** Update phone number **/
	public function update_phonenumber($val,$usrid)
	{
		
		//$fieldname_array = array('phone','creditcard_no','creditcard_cvv','expdatemonth','expdateyear');
		//$values_array = array($val['phone'],$val['creditcard_no'],$val['creditcard_cvv'],$val['expdatemonth'],$val['expdateyear']);
	//print_r($val); exit;
			$creditcard_no = encrypt_decrypt('encrypt',$val['popup_creditcard_no']);
			
		    $update_result =  DB::update(PASSENGERS)->set(array('phone'=>$val['lphone'],'creditcard_no'=>$creditcard_no,'creditcard_cvv'=>$val['popup_creditcard_cvv'],'expdatemonth'=>$val['expdatemonth'],'expdateyear'=>$val['expdateyear']))
					    ->where('id', '=' ,$usrid)					   
					    ->execute();
			
			if($update_result == 1)
			{
				
				return 1;
			}
			else
			{
				
				return 0;
			}
	}
	
/** Update Driver Rating **/
	public function update_rating($ratevalue="",$userid="",$driver_id="",$log_id="")
	{
		    
		   // echo 'user_id'.$userid.' '.'ratevalue'.' '.$ratevalue.' driver_id'.$driver_id.' log_id'.$log_id;
		    $update_result =  DB::update(PASSENGERS_LOG)->set(array('rating'=>$ratevalue))
					    ->where('passengers_id', '=' ,$userid)
					    ->where('driver_id', '=' ,$driver_id)
					    ->where('passengers_log_id', '=' ,$log_id)
					    ->execute();
					   //print_r($update_result);
			
			if($update_result == 1)
			{
				$get_result = $this->get_rating($userid,$driver_id,$log_id);
				return $get_result;
			}
			else
			{
				return 0;
			}
	}
/** Get Driver Rating **/
	public function get_rating($userid="",$driver_id="",$log_id="")
	{
		$query= "SELECT * FROM ".PASSENGERS_LOG." WHERE passengers_log_id= '$log_id' and passengers_id = '$userid' AND driver_id='$driver_id'";
		$result =  Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return $result;
	}
	/*** Save Comments ***/
	public function savecomments($userid="",$driver_id="",$log_id="",$comments="")
	{
		 $update_result =  DB::update(PASSENGERS_LOG)->set(array('comments'=>$comments))
					    ->where('passengers_id', '=' ,$userid)
					    ->where('driver_id', '=' ,$driver_id)
					    ->where('passengers_log_id', '=' ,$log_id)
					    ->execute();
		return $update_result;
	}
	public function savefavourite($log_id="",$mark_status="")
	{
		 $update_result =  DB::update(PASSENGERS_LOG)->set(array('favourite_trip'=>$mark_status))
					    ->where('passengers_log_id', '=' ,$log_id)
					    ->execute();
		return $update_result;
	}
	public function get_passenger_profile_details($id="")
	{
		$sql = "SELECT * FROM ".PASSENGERS." LEFT JOIN ".PASSENGERS_CARD_DETAILS." on ".PASSENGERS.".id = ".PASSENGERS_CARD_DETAILS.".passenger_id WHERE id = '$id' ";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}
	/*** Passenger Log **/
	public function get_passenger_log_details($userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null,$company_id)
	{
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
		$company_condition="";
		if($company_id != ""){
			$company_condition = " AND pg.company_id = '$company_id'";
		}
		$condition="";
		if($createdate == 0){ $condition = "AND pg.pickup_time >='".$start_time."'"; }
		/*$sql = "SELECT *,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername  FROM ".PASSENGERS_LOG." as pg LEFT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition  order by pg.passengers_log_id desc LIMIT $start,$limit";     */

		$sql = "SELECT *,pg.passengers_log_id,(select concat(name,' ',lastname) from ".PEOPLE." where id=pg.driver_id) as drivername,pg.passengers_log_id as pass_log_id   FROM ".PASSENGERS_LOG." as pg LEFT JOIN ".TRANS." as t ON pg.passengers_log_id = t.passengers_log_id  WHERE pg.passengers_id = '$userid' AND pg.travel_status = '$status' AND pg.driver_reply = '$driver_reply' $condition $company_condition  order by pg.passengers_log_id desc limit $start,$limit";

		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	/*** Passenger Log **/
	public function get_total_passenger_log_details($userid="",$status="",$driver_reply="",$createdate="",$company_id,$start=null,$limit=null)
	{
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

		$company_condition="";
		if($company_id != ""){
			$company_condition = " AND ".PASSENGERS_LOG.".company_id = '$company_id'";
		}

		$condition="";
		//if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".createdate >='".$start_time."'"; }
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'"; }

		$sql = "SELECT  count(*) as co_nt FROM ".PASSENGERS_LOG." WHERE passengers_id = '$userid' AND travel_status = '$status' AND driver_reply = '$driver_reply' $condition $company_condition  order by passengers_log_id desc";     
		//echo $sql;
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	/** Get Passengers booked and cancelled logs **/
	public function get_passenger_booked_log_details($userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null,$company_id)
	{
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
		$company_condition="";
		if($company_id != ""){
			$company_condition = " AND ".PASSENGERS_LOG.".company_id = '$company_id'";
		}
				
		$condition="";
		//if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".createdate >='".$start_time."'"; }
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'"; }

		$sql = "SELECT *,(select concat(name,' ',lastname) from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as drivername,(select phone from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as driverphone,(select count(*) from ".PASSENGERS_LOG." WHERE passengers_id = '$userid' AND travel_status = '$status' AND (driver_reply = '' or driver_reply = 'C' or driver_reply = 'R')  $condition) as total_count  FROM ".PASSENGERS_LOG." WHERE passengers_id = '$userid' AND travel_status = '$status' AND (driver_reply = '' or driver_reply = 'C' or driver_reply = 'R')  $condition $company_condition  and ".PASSENGERS_LOG.".driver_id!='' order by passengers_log_id desc LIMIT $start,$limit";     
		//echo $sql;
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();		
	}

	public function get_passenger_booked_cancel_details($company_id,$userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null)
	{
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

		$company_condition="";
		if($company_id != ""){
			$company_condition = " AND ".PASSENGERS_LOG.".company_id = '$company_id'";
		}

		$condition="";
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'"; }
		//if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".createdate >='".date('Y-m-d 00:00:01')."'"; }
		//$sql = "SELECT *,".PASSENGERS_LOG.".passengers_log_id,(select concat(name,' ',lastname) from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as drivername,(select pay_mod_name from ".PAYMENT_MODULES." where pay_mod_id=".TRANS.".payment_type) as payment_name,".PASSENGERS_LOG.".passengers_log_id as pass_log_id,(select phone from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as driverphone,(select count(*) from ".PASSENGERS_LOG." WHERE passengers_id = '$userid' AND travel_status IN('0','4')  $condition) as total_count  FROM ".PASSENGERS_LOG." left join ".TRANS." on ".TRANS.".passengers_log_id=".PASSENGERS_LOG.".passengers_log_id WHERE passengers_id = '$userid' AND if((driver_reply = 'A'),travel_status IN('4'),travel_status IN('0','4'))  $condition  order by ".PASSENGERS_LOG.".passengers_log_id desc LIMIT $start,$limit"; 
		$sql = "SELECT *,".PASSENGERS_LOG.".passengers_log_id,(select concat(name,' ',lastname) from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as drivername,(select pay_mod_name from ".PAYMENT_MODULES." where pay_mod_id=".TRANS.".payment_type) as payment_name,".PASSENGERS_LOG.".passengers_log_id as pass_log_id,(select phone from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as driverphone,(select count(*) from ".PASSENGERS_LOG." WHERE passengers_id = '$userid' AND travel_status IN('$status') AND (driver_reply = '' or driver_reply = 'C' or driver_reply = 'R') $condition) as total_count  FROM ".PASSENGERS_LOG." left join ".TRANS." on ".TRANS.".passengers_log_id=".PASSENGERS_LOG.".passengers_log_id WHERE passengers_id = '$userid' AND travel_status = '$status' AND (driver_reply = '' or driver_reply = 'C' or driver_reply = 'R') AND sub_logid !=0 $condition  $company_condition group by sub_logid order by ".PASSENGERS_LOG.".passengers_log_id desc LIMIT $start,$limit";     

		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();		
	}

	/** Get passengers Currewnt booked details **/
	
	public function get_passenger_current_log_details($userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null,$company_id)
	{
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

		$company_condition="";
		if($company_id != ""){
			$company_condition = " AND ".PASSENGERS_LOG.".company_id = '$company_id'";
		}

		$condition="";
		//if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".createdate >='".$start_time."'"; }
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'"; }

		$sql = "SELECT *,(select concat(name,' ',lastname) from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as drivername,(select phone from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as driverphone  FROM ".PASSENGERS_LOG." WHERE passengers_id = '$userid' AND (travel_status = '9' or travel_status = '2') AND driver_reply = '".$driver_reply."'  $condition  order by passengers_log_id desc";     
		//echo $sql;
		/*$sql = "SELECT *,(select concat(name,' ',lastname) from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as drivername  FROM ".PASSENGERS_LOG."   order by passengers_log_id desc LIMIT $start";     */

		

		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();		
	}
	/*** Passenger Log with transactions **/
	public function get_passenger_log_transactions($userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null,$company_id)
	{ //echo $limit;
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
		$company_condition="";
		if($company_id != ""){
			$company_condition = " AND ".PASSENGERS_LOG.".company_id = '$company_id'";
		}
				
		$condition="";
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'"; }
		//if($start!="" && $limit !=""){$condition = " limit $start,$limit"; }
		$sql = "SELECT *,".PASSENGERS_LOG.".passengers_log_id,(select concat(name,' ',lastname) from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as drivername  FROM ".PASSENGERS_LOG."  LEFT JOIN ".TRANS." ON ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id WHERE passengers_id = '$userid' AND travel_status = '$status' AND driver_reply = '$driver_reply' $condition $company_condition  order by ".PASSENGERS_LOG.".passengers_log_id desc limit $start,$limit";
		// LIMIT $start";     
		//echo $sql;
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}

	public function get_passenger_favourite_trip($userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null)
	{ //echo $limit;
		$condition="";
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".date('Y-m-d 00:00:01')."'"; }

		$sql = "SELECT *,".PASSENGERS_LOG.".passengers_log_id,(select concat(name,' ',lastname) from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as drivername  FROM ".PASSENGERS_LOG."  LEFT JOIN ".TRANS." ON ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id WHERE passengers_id = '$userid' AND travel_status = '$status' AND driver_reply = '$driver_reply' $condition  AND favourite_trip='S' order by ".PASSENGERS_LOG.".passengers_log_id desc limit $start,$limit";
		// LIMIT $start";     
		//echo $sql;
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	

	/*** Passenger Log with transactions **/
	public function get_total_passenger_favourite_trip($userid="",$status="",$driver_reply="",$createdate="",$company_id,$start=null,$limit=null)
	{ 
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

		$company_condition="";
		if($company_id != ""){
			$company_condition = " AND ".PASSENGERS_LOG.".company_id = '$company_id'";
		}

		$condition="";
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'"; }
		$sql = "SELECT count(*) as co_nt FROM ".PASSENGERS_LOG."  LEFT JOIN ".TRANS." ON ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id WHERE passengers_id = '$userid' AND travel_status = '$status' AND driver_reply = '$driver_reply' $condition $company_condition AND favourite_trip='S'  order by ".PASSENGERS_LOG.".passengers_log_id desc";
		// LIMIT $start";     
//echo $sql;
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	
	/*** Passenger Log with transactions **/
	public function get_total_passenger_log_transactions($userid="",$status="",$driver_reply="",$createdate="",$company_id,$start=null,$limit=null)
	{
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

		$company_condition="";
		if($company_id != ""){
			$company_condition = " AND ".PASSENGERS_LOG.".company_id = '$company_id'";
		}

		$condition="";
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".pickup_time >='".$start_time."'"; }
		
		$sql = "SELECT count(*) as co_nt FROM ".PASSENGERS_LOG."  LEFT JOIN ".TRANS." ON ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id WHERE passengers_id = '$userid' AND travel_status = '$status' AND driver_reply = '$driver_reply' $condition $company_condition order by ".PASSENGERS_LOG.".passengers_log_id desc";
		// LIMIT $start";     

		//echo $sql;
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	
	// Validating User Details while Updating User Details

	public function validate_user_settings($arr,$files_value_array) 
	{
		return Validation::factory($arr,$files_value_array)       
			->rule('file', 'Upload::type', array($files_value_array['photo'], array('jpg','jpeg', 'png', 'gif')))
			->rule('file', 'Upload::size', array($files_value_array['photo'],'2M'));						
	}	

	public function validate_passengers_profilesettings($arr) 
	{
		return Validation::factory($arr)       
			
			->rule('name', 'not_empty')
			//->rule('name','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
			->rule('name', 'min_length', array(':value', '4'))
			->rule('name', 'max_length', array(':value', '32'))
			->rule('phone', 'not_empty')
			//->rule('phone','numeric') //num
			->rule('address', 'not_empty');
			/*->rule('creditcard_no', 'not_empty')	
			->rule('creditcard_no', 'numeric')
			->rule('creditcard_no','min_length',array(':value','9'))
			->rule('creditcard_no','max_length',array(':value','16'))
			//->rule('creditcard_no', 'Model_Passengers::validatecreditcard')
			->rule('creditcard_cvv', 'not_empty')			
			->rule('creditcard_cvv', 'numeric')
			->rule('creditcard_cvv', 'min_length', array(':value','3'))
			->rule('creditcard_cvv', 'max_length', array(':value','4'))
			->rule('expdatemonth', 'not_empty')
			//->rule('expdatemonth', 'Model_Passengers::validatemonth', array(':value',$arr['expdateyear']))
			->rule('expdateyear', 'not_empty')*/
			//->rule($arr, 'Model_Passengers::authorize_creditcard');
			

			//->rule('description','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'));					
	}


	public static function validatemonth ($month,$year){
		$current_year  = date("Y");
		$current_month = date("m");
		if(($current_year == $year) && ($month < $current_month) )
		{
			return false;
		}
		else
		{
			return true;
		}	
	}


	public static function validatecreditcard ($creditcard_no){

	       $all_cardtype = '/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/';

		if(preg_match($all_cardtype,$creditcard_no))
		{ 
			return true;
		}
		else
		{	
			return false;
		}	
	}	

	// Updating User Details
	public function update_passengerimage($image,$userid)
	{
		$sql="select profile_image from ".PASSENGERS." where id='$userid'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['profile_image'])){
				$id1 = PASS_IMG_IMGPATH.$results[0]['profile_image'];
				$id2 = PASS_IMG_IMGPATH.'thumb_'.$results[0]['profile_image'];
				$id3 = PASS_IMG_IMGPATH.'edit_'.$results[0]['profile_image'];	
				
				if(file_exists($id1))
				{
					unlink($id1);
				}
				if(file_exists($id2))
				{
					unlink($id2);
				}
				if(file_exists($id3))
				{
					unlink($id3);
				}
			}
			
		$mdate = $this->currentdate;
		
		$sql_query = (array('updated_date' => $mdate));
	
		if(isset($image)){
			$sql_query[ 'profile_image' ]=$image ;
		}
		
		$result =  DB::update(PASSENGERS)->set($sql_query)
					->where('id', '=' ,$userid)
					->execute();
	}
	
	public function validate_user_profilesettings($arr) 
	{
		return Validation::factory($arr)       
			
		    ->rule('name', 'not_empty')
            ->rule('name','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
			->rule('name', 'min_length', array(':value', '4'))
			->rule('name', 'max_length', array(':value', '32'))

            ->rule('lastname', 'not_empty')
            ->rule('lastname','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
			->rule('lastname', 'min_length', array(':value', '1'))
			->rule('lastname', 'max_length', array(':value', '32'))


                    ->rule('email','not_empty')
					->rule('email','max_length',array(':value','50'))
					
					->rule('email','email_domain')
					    

	        ->rule('description', 'not_empty')		
            ->rule('description','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
			->rule('description', 'min_length', array(':value', '5'))


                    ->rule('school', 'not_empty')   
                    ->rule('education','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))

                    ->rule('education', 'not_empty') 
                    ->rule('education','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
            ;						
	}
	public function validate_user_profilesettings_optional($arr)
	{
		return Validation::factory($arr)       
			
                    ->rule('phone', 'phone')
                    ->rule('dob', 'date')

                   
                    ->rule('organisation', 'alpha_space')  
                    ->rule('organisation','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
                    ->rule('organisation', 'not_numeric')  


                    ->rule('work', 'alpha_space')  
                    ->rule('work','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
                    ->rule('work', 'not_numeric')  


                    ->rule('website', 'url')

					        ->rule('user_paypal_account','max_length',array(':value','60'))					     
    					    ->rule('user_paypal_account','email')
					        ->rule('user_paypal_account','Model_Authorize::unique_email')

                   ->rule('account_balance_amt', 'numeric')  


                    ->rule('group', 'alpha_space')  
                    ->rule('group','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
                    ->rule('group', 'not_numeric')  
            ;								
	}	

     public function update_user_settings_optional($array_data,$id)
     {
			if(isset($array_data['time_zone']) && $array_data['time_zone'] !=""){
						$name = $array_data['time_zone'];
						$sql_query[ 'timezone' ]=$name ;
					}
			  if(isset($array_data['phone']) && $array_data['phone'] !=""){
						$name = $array_data['phone'];
						$sql_query[ 'phone' ]=$name ;
					}
			  if(isset($array_data['gender']) && $array_data['gender'] !=""){
						$name = $array_data['gender'];
						$sql_query[ 'gender' ]=$name ;
					}
			  if(isset($array_data['dob']) && $array_data['dob'] !=""){
						$name = $array_data['dob'];
						$sql_query[ 'dob' ]=$name ;
					}
				
			  if(isset($array_data['known_language']) && $array_data['known_language'] !=""){
						$name = $array_data['known_language'];
						
						$sql_query[ 'known_language' ]=implode(',',$array_data['known_language']) ;
					}
			if(isset($array_data['group']) && $array_data['group'] !=""){
						$name = $array_data['group'];
						$sql_query[ 'group' ]=$name ;
					}					
			  if(isset($array_data['work']) && $array_data['work'] !=""){
						$name = $array_data['work'];
						$sql_query[ 'work' ]=$name ;
					}
			  if(isset($array_data['website']) && $array_data['website'] !=""){
						$name = $array_data['website'];
						$sql_query[ 'website' ]=$name ;
					}
			 
            $optional_result =  DB::update(PEOPLE)->set($sql_query)
					    ->where('id', '=' ,$id)
					    ->execute();
	 }
// Updating User Details

	public function update_user_settings($array_data,$post_value_array,$userid)
	{
		//print_r("test"); exit;
		$mdate = $this->currentdate; 
		
		$name = $array_data['name'];
		$phone = $array_data['phone'];
		$address = $array_data['address'];

		
		$sql = "UPDATE `passengers` SET `updated_date` = '$mdate', `name` = '$name', `phone` = '$phone', `address` = '$address' WHERE `id` = '$userid'";
		
		$optional_result =  Db::query(Database::UPDATE, $sql)
		->execute();

		return ($optional_result)?1:0; 
	}

	/**Get User Details at User Profile Page**/
	public function get_user_details($userid=null,$location_details=null) //,$offset=0,$rec=0
	{    
        $lat=$this->lat; 
        $lon=$this->lon;

        $rad='';

        $result=array();
        if($userid!=null)
        {
	        if($rad == ""){	

                if( ($lat!=null) && ($lon!=null) )
                {
		            $query="SELECT *,(((acos(sin((".$lat."*pi()/180)) * 
                        sin((`latitude`*pi()/180))+cos((".$lat."*pi()/180)) * 
                        cos((`latitude`*pi()/180)) * cos(((".$lon."- `Langitude`)*
                        pi()/180))))*180/pi())*60*1.1515) as distance FROM ".PEOPLE."  where id = '".$userid."' 
                         ORDER BY `distance` ASC ";	  		 //limit $offset,$rec	
                }else 
                {
                    $query="SELECT * FROM ".PEOPLE."  where id = '".$userid."' ";
                }
	        }
            $result=Db::query(Database::SELECT, $query)
			            ->execute()
			            ->as_array();

            return $result;

        }else { return $result; }
	}
	
	
	// Validating Forgot Password Details
	public function validate_forgotpwd($arr) 
	{
		return Validation::factory($arr)       
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '100'))
			->rule('email', 'not_empty');
	}

	// Check Whether Passenger Email is Already Exist or Not
	public function check_email_passengers($email="",$company_id="")
	{
		//$sql = "SELECT email FROM ".PASSENGERS." WHERE email='$email' and passenger_cid=".COMPANY_CID;
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
	
	// Check Whether Passenger Email is Already Exist or Not
	public function check_passengers_det($email="")
	{

		//$sql = "SELECT * FROM ".PASSENGERS." WHERE email='$email' and passenger_cid=".COMPANY_CID;   
		$sql = "SELECT * FROM ".PASSENGERS." WHERE email='$email'";   
		return $result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();
	}
	
	// Check Whether Passenger phone is Already Exist or Not
	public function check_phone_passengers($phone="",$company_id="")
	{

		//$sql = "SELECT phone FROM ".PASSENGERS." WHERE phone='$phone' and passenger_cid=".COMPANY_CID;
		if($company_id != '')
		{
			$sql = "SELECT phone FROM ".PASSENGERS." WHERE phone='$phone' and passenger_cid='$company_id'";   
		}
		else
		{
			$sql = "SELECT phone FROM ".PASSENGERS." WHERE phone='$phone' and passenger_cid='0'";   
		}     
		//echo $sql;
		//exit;
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

	// Reset User Password if User Forgot Password 
	public function forgot_password($array_data,$post_value_array,$random_key)
	{
		$mdate = $this->currentdate;
		$pass= md5($random_key);
		// Create a new user record in the database
		$result = DB::update(PASSENGERS)->set(array('password' => $pass,'org_password'=>$random_key,'updated_date' => $mdate ))->where('email', '=', $array_data['email'])
			->execute();
		if($result){
			 $rs = DB::select('name','email','password','phone')->from(PASSENGERS)
			 			->where('email','=', $post_value_array['email'])
			 			->where('activation_status','=','1')
						->execute()
						->as_array();
			 return $rs;
		}else{
			return 0;
			}
	}	
	
	// Reset User Password if User Forgot Password 
	public function forgot_password_activation($email,$random_key)
	{
		$mdate = $this->currentdate;
		$pass= md5($random_key);
		// Create a new user record in the database
		$result = DB::update(PASSENGERS)->set(array('password' => $pass,'org_password'=>$random_key,'updated_date' => $mdate ))->where('email', '=', $email)
		//->where('passenger_cid','=',COMPANY_CID)
		->where('activation_status','=','1')
			->execute();
		
		if($result){
			 $rs = DB::select('name','email','password','phone')->from(PASSENGERS)
			 			->where('email','=', $email)
			 			->where('activation_status','=','1')
			 			//->where('passenger_cid','=',COMPANY_CID)
						->execute()
						->as_array();
						//print_r($rs);exit;	
			 return $rs;
		}else{
			return 0;
			}
	}	

	// Reset User Password if User Forgot Password 
	public function forgot_password_phone($array_data,$value,$random_key,$company_id=null)
	{
		$mdate = $this->currentdate;
		$pass= md5($random_key);
		// Create a new user record in the database
		if($company_id != 0)
		{
			$result = DB::update(PASSENGERS)->set(array('password' => $pass,'org_password'=>$random_key,'updated_date' => $mdate))
			->where('phone', '=', $value['phone_no'])
			->where('passenger_cid', '=', $company_id)			
			->execute();
		}
		else
		{
			$result = DB::update(PASSENGERS)->set(array('password' => $pass,'org_password'=>$random_key,'updated_date' => $mdate))
			->where('phone', '=', $value['phone_no'])
			->where('passenger_cid', '=', '0')
			->execute();			
		}
		if($result){
		 if($company_id != 0)
		 {
			$rs = DB::select('name','email','password','phone')->from(PASSENGERS)
			 			->where('phone','=', $value['phone_no'])
			 			->where('activation_status','=','1')
			 			->where('passenger_cid', '=', $company_id)
						->execute()
						->as_array();
		 }
		 else
		 {
			$rs = DB::select('name','email','password','phone')->from(PASSENGERS)
			 			->where('phone','=', $value['phone_no'])
			 			->where('activation_status','=','1')
			 			->where('passenger_cid', '=', '0')
						->execute()
						->as_array();			 
		 }
			 return $rs;
		}else{
			return 0;
			}
	}	

	// User Change Password
	public function change_password($array_data,$post_value_array,$userid="")
	{
		$userid = (isset($_SESSION['id'])?$_SESSION['id']:"");	
		$mdate = $this->currentdate;
		$pass=md5($array_data['confirm_password']);
		// Create a new user record in the database
		$result = DB::update(PASSENGERS)->set(array('password' => $pass ,'org_password'=>$array_data['confirm_password'],'updated_date' => $mdate ))->where('id', '=', $userid)
			->execute();
			
		if(count($result) == SUCESS)
		{
			$rs = DB::select('name','password','email','phone')->from(PASSENGERS)
				->where('id', '=', $userid)
				->execute()
				->as_array();
				
			return $rs;
		}
	}		

	// Validating Change Password Details
	public function validate_changepwd($arr) 
	{ 
		return Validation::factory($arr)       
			->rule('old_password', 'not_empty')
			->rule('old_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('old_password', 'max_length', array(':value', '16'))
			->rule('new_password', 'not_empty')
			->rule('new_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('new_password', 'min_length', array(':value', '5'))
			->rule('new_password', 'max_length', array(':value', '16'))
			->rule('confirm_password', 'not_empty')
			->rule('confirm_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('confirm_password',  'matches', array(':validation', 'new_password', 'confirm_password'))
			->rule('confirm_password', 'min_length', array(':value', '5'))
			->rule('confirm_password', 'max_length', array(':value', '16'));
	}

	/**Validating Reset Password Details **/

	public function validate_resetpwd($arr) 
	{
		return Validation::factory($arr)       
			->rule('new_password', 'not_empty')
			//->rule('new_password','alpha_dash')
			->rule('new_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('new_password', 'max_length', array(':value', '16'))
			->rule('conf_password', 'not_empty')
			//->rule('conf_password','alpha_dash')
			//->rule('conf_password', array(':equals','new_password'))
			->rule('conf_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('conf_password', 'max_length', array(':value', '16'));
	}

	// Check Whether the Eneterd Password is Correct While User Change Password
	public function check_pass($pass="",$userid="")
	{
		$userid = (isset($_SESSION['id'])?$_SESSION['id']:"");	
		$result = DB::select()->from(PASSENGERS)
			->where('id', '=', $userid)
			->execute()
			->as_array();
		$pass=md5($pass);
		$password=$result["0"]["password"];
			if($password == $pass)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}


	public function check_userdetails_exist($id,$key)
	{
		$result = DB::select('id','activation_code_status')->from(USERS)
			->where('id', '=', $id)
			->where('activation_code','=',$key)
			->execute()
			->as_array();		
			
			return $result;	

	}
	
public function get_email($uid="")
	{

		$sql = "SELECT email FROM ".PEOPLE." WHERE id='$uid' ";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return $result["0"]["email"];
			}
			else
			{
				return "";		
			}
	}
    public function get_uname($uid="")
	{

		$sql = "SELECT name,lastname FROM ".PASSENGERS." WHERE id='$uid' ";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return $result["0"]["name"]."".$result["0"]["lastname"];
			}
			else
			{
				return "";		
			}
	}

	public function get_passenger_details($key){
	
		$result=DB::select()->from(PASSENGERS)	
				->where('activation_key','=',$key)
				->execute()
				->as_array();				
					
		return $result;		
	}	
	
	/**
	* Validation rule for fields in email
	*/
	public function validate_email($arr)
	{
		return Validation::factory($arr)
					->rule('email','not_empty')
					->rule('email','max_length',array(':value','50'))
					->rule('email', 'Model_Authorize::check_label_not_empty',array(":value",__('enter_email')))
					->rule('email','email_domain')

					->rule('email','Model_Authorize::unique_email');
	}
/**For Facebook user signup insertion**/

	public function register_facebook_user($profile_data = array(),$fb_access_token,$arr,$location_details,$userurl=null,$random_key=null,$pwd='', $verify_code)
	{

        $lat=$this->lat;
        $lng=$this->lon;


        $accounttype=($this->session->get('account_type')=="job")?2:1;  // job means : careseeker login


		$result=DB::select()->from(PASSENGERS)
			->where('email', '=', $profile_data->email)
			->execute()
			->as_array();

	    if(empty($result))
		{  
			$username = Html::chars($profile_data->first_name);	
			$password = md5($pwd); //  md5(Commonfunction::randomkey_generator());

			$insert_result = DB::insert(PASSENGERS, array('username','name', 'email', 'password','user_url','user_uniq_urlid','photo','login_type','user_type','status',
										'created_date','access_key','secret_key','facebook_session_key','last_login',
										'account_type', 'verify_link'))
							->values(array(strtolower($username),$profile_data->name,$profile_data->email,
										$password,$userurl,$random_key,$arr['picture'],FACEBOOK,NORMALUSER,ACTIVE,
										$this->currentdate,$fb_access_token,FB_APP_SECRET,'',$this->currentdate,$accounttype, $verify_code))
							->execute();

                //'latitude','langitude','login_city','login_state','login_country','login_country_code','login_ip',
                //,$lat,$lng,$city,$state,$country,$country_code,$ip

			$this->session->set("id" ,$insert_result[0]);
			$this->session->set("username", $profile_data->first_name);
			$this->session->set("email", $profile_data->email);
			$this->session->set("fb_access_token" ,$fb_access_token);

            unset($_SESSION["account_type"]);


            $rslt=array(); 
            $rslt=array("name"=>$profile_data->first_name,"email"=>$profile_data->email,"password"=>$password);
            return $rslt;
		}
		else
		{
				$login_time = $this->currentdate;                
				$result_login = DB::update(PASSENGERS)
								->set(array('last_login'=> $login_time))
								->execute();
				
				if(($result["0"]["user_type"] == ADMIN)){
				        
						$this->session->set("user_email",$result[0]["email"]);
						$this->session->set("name",$result[0]["name"]);
						$this->session->set("username",$result[0]["username"]);
						$this->session->set("userid", $result[0]["id"]);
						$this->session->set("user_type", $result[0]["user_type"]);
									
					}				
					$this->session->set("user_name",$result["0"]["username"]);

				    $this->session->set("name",$result[0]["name"]);
					$this->session->set("id",$result["0"]["id"]);
					$this->session->set("usertype",$result["0"]["user_type"]);
					$this->session->set("email",$result["0"]["email"]);

            return 1;		
		}
		
	}
	


	/**For Facebook user signup insertion**/
	public function register_facebook_user1($profile_data = array(),$fb_access_token,$arr,$location_details,$userurl=null,$random_key=null, $verify_code)
	{
	
        $lat=$this->lat;
        $lng=$this->lon;


        $accounttype=($this->session->get('account_type')=="job")?2:1;  // job means : careseeker login


		$result=DB::select()->from(PASSENGERS)
			->where('email', '=', $profile_data->email)
			->execute()
			->as_array();

	    if(empty($result))
		{  
			$username = Html::chars($profile_data->first_name);	
			$password = md5(Commonfunction::randomkey_generator());

			$insert_result = DB::insert(PASSENGERS, array('username','name', 'email', 'password','user_url','user_uniq_urlid','photo','login_type','user_type','status',
										'created_date','access_key','secret_key','facebook_session_key','last_login',
										'account_type', 'verify_link'))
							->values(array(strtolower($username),$profile_data->name,$profile_data->email,
										$password,$userurl,$random_key,$arr['picture'],FACEBOOK,NORMALUSER,INACTIVE,
										$this->currentdate,$fb_access_token,FB_APP_SECRET,'',$this->currentdate,$accounttype, $verify_code))
							->execute();

                //'latitude','langitude','login_city','login_state','login_country','login_country_code','login_ip',
                //,$lat,$lng,$city,$state,$country,$country_code,$ip

			$this->session->set("fb_id" ,$insert_result[0]);
            unset($_SESSION["account_type"]);


            $rslt=array(); 
            $rslt=array("name"=>$profile_data->first_name,"email"=>$profile_data->email,"password"=>$password);
            return $rslt;
		}
		else
		{
				$login_time = $this->currentdate;                
				$result_login = DB::update(PASSENGERS)
								->set(array('last_login'=> $login_time))
								->execute();
				
				if(($result["0"]["user_type"] == ADMIN)){
				        
						$this->session->set("user_email",$result[0]["email"]);
						$this->session->set("name",$result[0]["name"]);
						$this->session->set("username",$result[0]["username"]);
						$this->session->set("userid", $result[0]["id"]);
						$this->session->set("user_type", $result[0]["user_type"]);
									
					}				
					$this->session->set("user_name",$result["0"]["username"]);
				    $this->session->set("name",$result[0]["name"]);
					$this->session->set("id",$result["0"]["id"]);

					$this->session->set("usertype",$result["0"]["user_type"]);
					$this->session->set("email",$result["0"]["email"]);


            return 1;		
		}
		
	}
	
		public function logged_user_status()
	{
		$query="SELECT activation_status FROM ".PASSENGERS."  where id = '".$this->session->get("id")."' ";
		$result =  Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
			

			if(count($result) == 1 && $result[0]['activation_status'] == '1')
			{		
			   return 1;
				
			}
			else if(count($result) == 1 && $result[0]['activation_status'] == '0')
			{
				return -1;
			}
			else
			{
				return 0;
			}
	}


	public function get_user_email()
	{      	
        $query="SELECT email FROM ".PASSENGERS."  where id != '".$this->session->get("id")."' ";
           
        $result=Db::query(Database::SELECT, $query)
		            ->execute()
		            ->as_array();

        return $result;
    }

	public function get_usernames($userid)
	{
         $result =  DB::select('name')->from(PASSENGERS)
                                     
                                     ->where('id', '=',$userid)
									 ->execute()
									 ->as_array();		 
		  return $result;			
	}
		public function get_smtpdetails()
	{
          $result =  DB::select()->from(SMTP_SETTINGS)
                                     ->limit(1)
									 ->execute()
									 ->as_array();		 
		  return $result;		
	}
	public function get_email_settinguser($user_id,$cols="")
	{
          $result =  DB::select($cols)->from(USER_EMAIL_SETTINGS)
                                     ->where('userid', '=', $user_id)
                                     ->limit(1)
									 ->execute()
									 ->as_array();		 
		  return $result;		
		
	}
	 public function get_currentuser($userid)
	 {
		 $query="select * from ".PASSENGERS." where id=$userid and status='A' " ;
                $result=Db::query(Database::SELECT, $query)

				->execute()

				->as_array();
	     return $result;			 
	 }

	 
	 public function get_user_email_setting($userid)
	 {
          $result =  DB::select()->from(USER_EMAIL_SETTINGS)
									 ->where('userid', '=', $userid)
									 ->execute()
									 ->as_array();		 
		  return $result;
	 }
	 

   public function get_logged_user_details($userid){
   
   
   		$result=DB::select()->from(PASSENGERS)	
					->where("id","=",$userid)
					->and_where("user_type","=","N") //NORMAL

					->execute()
					->as_array(); // print_r($result);exit;   				
		return $result;   
   }
   
   public function check_password_exist($userid){
   
			$query=DB::select('email','name','password')->from(PASSENGERS)
								->where('id','=',$userid)
								->execute()->as_array();
								
			if(count($query) > 0){
			return 1;
			}else{
			return 0;
			}	
			
	}
	public function get_my_profile_details($id)
	{
	
		$sql = "SELECT * FROM ".PASSENGERS." WHERE id = '$id' ";                      
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}
	//Getting the latitude and Longitude	
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
		//print_r($json);exit;
		if($json->status == 'OK')
		{				
		return array($json->results[0]->geometry->location->lat, $json->results[0]->geometry->location->lng);
		}
		else
		{
			return array(11.621354, 76.14253698);
		}
		
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
	/*** Get Taxi fare per KM & Waiting charge of the company ***/
	public function get_taxi_fare_waiting_charge($taxi_id="")
	{
				$sql = "SELECT *,".COMPANYINFO.".company_tax FROM  ".TAXI." JOIN  ".COMPANY." ON (  ".COMPANY.".`cid` =  ".TAXI.".`taxi_company` ) join ".COMPANYINFO."  ON ".COMPANY.".cid=".COMPANYINFO.".company_cid WHERE  ".TAXI.".`taxi_id` =  '$taxi_id'";
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}	
	
	//public function register_facebook_user($fb_profile = array(),$fb_access_token = "",$ar, $location, $user_referral_id = "")
	public function register_facebook_user_taxi($fb_profile , $fb_access_token = "",$image_name)
    {
		$result=DB::select()->from(PASSENGERS)
			->where('email', '=', $fb_profile->email)
			//->where('passenger_cid', '=', COMPANY_CID)
			->execute()
			->as_array();
			
		   
		if(empty($result))
		{ 	
	
		/*
		$query = DB::insert("passengers", array('name','email','fb_user_id','fb_access_token','login_from','activation_status','user_status','phone','profile_image','passenger_cid'))
			   ->values(array($fb_profile->first_name,$fb_profile->email,$fb_profile->id,$fb_access_token,'3','1','A',$fb_profile->id,$image_name,COMPANY_CID))	
			   ->execute();
		*/	

		$query = DB::insert("passengers", array('name','email','fb_user_id','fb_access_token','login_from','activation_status','user_status','phone','profile_image'))
			   ->values(array($fb_profile->first_name,$fb_profile->email,$fb_profile->id,$fb_access_token,'3','1','A',$fb_profile->id,$image_name))	
			   ->execute();

			   
	   if(empty($query))
	   { 
		   
		   return -1;
	   }
	   else
	   {	
		   $this->session->set("id",$query[0]);	   
		   $this->session->set("name", $fb_profile->first_name);
		   $this->session->set("email",$fb_profile->email);
		   $this->session->set("fb_access_token" ,$fb_access_token);
		   $this->session->set("usertype","passengers");
		   return 1;	
	   }
	   }
	 else
	   {
			$email = $fb_profile->email;			
			$result_email=DB::select()->from(PASSENGERS)
			->where('email', '=', $fb_profile->email)
			->where('login_from', '=', '3')
			//->where('passenger_cid', '=', COMPANY_CID)
			->execute()
			->as_array();
			$count = count($result_email); 			
		   if($count==1)
		   {
		   $this->session->set("id",$result_email[0]['id']);		   	  
		   $this->session->set("name", $fb_profile->first_name);
		   $this->session->set("email",$fb_profile->email);
		   $this->session->set("fb_access_token" ,$fb_access_token);
		   $this->session->set("usertype","passengers");
		   return 1;
	    }	
	      else
	      {
			  return -1;
		  }
	   }
	
}


	/** Get driver status **/
	public function select_current_user($id="")
	{
		$sql = "SELECT * FROM ".PASSENGERS." WHERE `id` = '".$id."'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}
	
	public function passenger_cancel_transaction_details($id="",$company_id="",$val="",$offset="")
	{
	/*	$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `passengers_id` = '".$id."' and `travel_status` = '4'";*/
	$company_condition="";
	if($company_id != ""){
		$company_condition = " AND pl.company_id = '$company_id'";
	}	
	$condition = "WHERE pl.passengers_id = '$id' and `travel_status` = '4' "; 
	$query = " SELECT pl.passengers_log_id,pl.passengers_id,pl.current_location,pl.drop_location,pl.no_passengers,pl.approx_distance,pl.distance,pl.approx_fare,pl.pickup_time,pe.name AS driver_name,trans.fare,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id  LEFT JOIN ".TRANS." as trans ON pl.passengers_log_id = trans.passengers_log_id  $condition $company_condition order by pl.passengers_log_id desc limit $val,$offset";
	//echo $query;
		return Db::query(Database::SELECT, $query)
			->execute()
			->as_array(); 
	}
	
	public function passenger_tot_cancel_transaction_details($id="",$company_id)
	{
	$company_condition="";
	if($company_id != ""){
		$company_condition = " AND pl.company_id = '$company_id'";
	}	
	/*	$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `passengers_id` = '".$id."' and `travel_status` = '4'";*/
	$condition = "WHERE pl.passengers_id = '$id' and `travel_status` = '4' "; 
	$query = " SELECT count(*) as tot,pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition $company_condition order by pl.passengers_log_id desc";


		return Db::query(Database::SELECT, $query)
			->execute()
			->as_array(); 
	}
	
	
	public static function authorize_creditcard($values)
    {   
		//echo "values:";print_r($values); exit;
		$api_model = Model::factory('api');
		$paypal_details = $api_model->paypal_details(); 
		$amount = '0';

		$product_title = Html::chars('Authorize Creditcard');
		$payment_action = 'Authorization';
		//$payment_action='sale';

		$request  = 'METHOD=DoDirectPayment';
		$request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
		$request .= '&USER=' . urlencode($paypal_details[0]['paypal_api_username']);
		$request .= '&PWD=' . urlencode($paypal_details[0]['paypal_api_password']);
		$request .= '&SIGNATURE=' . urlencode($paypal_details[0]['paypal_api_signature']);

		$request .= '&PAYMENTACTION=' . $payment_action; //type
		$request .= '&AMT=' . urlencode($amount); //   
		$request .= '&ACCT=' . urlencode(str_replace(' ', '', $values['creditcard_no']));
		$request .= '&EXPDATE=' . urlencode($values['expdatemonth'] . $values['expdateyear']);
		$request .= '&CVV2=' . urlencode($values['creditcard_cvv']);

		$request .= '&CURRENCYCODE=' . $paypal_details[0]['currency_code'];

		$paypal_type=($paypal_details[0]['payment_method'] =="L")?"live":"sandbox";
		if ($paypal_type=="live") {
			$curl = curl_init('https://api-3t.paypal.com/nvp');
		} else {
			$curl = curl_init('https://api-3t.sandbox.paypal.com/nvp');
		}

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

		$intial=0;
		$nvpArray = array();


		while(strlen($nvpstr)){
		    //postion of Key
		    $keypos= strpos($nvpstr,'=');
		    //position of value
		    $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

		    //getting the Key and Value values and storing in a Associative Array
		    $keyval=substr($nvpstr,$intial,$keypos);
		    $valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
		    //decoding the respose
		    $nvpArray[urldecode($keyval)] =urldecode( $valval);
		    $nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
		}

		$ack = isset($nvpArray['ACK'])?strtoupper($nvpArray['ACK']):'';

		if($ack =='SUCCESSWITHWARNING' || $ack =='SUCCESS')
		{
			return 0;
		}
		else
		{
			return -1;
		}		                

	}
	
	public function get_passenger_phone($id)
	{
		$sql = "SELECT * FROM ".PASSENGERS." WHERE id = '$id' ";  
                   
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}

	public function get_driver_location($driverid)
	{
		 $sql = "select * from ".DRIVER." where driver_id='$driverid'";
		//$sql = "select * from ".DRIVER." where driver_id=$driverid";
		return Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
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

	public function check_companystatus($company_id)
	{		
		$query= "SELECT * FROM ".COMPANY." WHERE cid='$company_id'";
		$result =  Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return $result;
	}
	/************* Get Current Taxi Details *******************/
	public function get_current_taxi_details($driver_id,$taxi_id,$cityid)
	{
		
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
		$result_query = "select company.company_name,(SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as cancellation_nfree,(select company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as company_tax,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km,company_model_fare.night_charge,company_model_fare.night_timing_to,company_model_fare.night_fare,company_model_fare.min_km,company_model_fare.below_above_km,motor_model.model_name,taxi.taxi_id,taxi.taxi_no,taxi.taxi_type,taxi.taxi_model,taxi.taxi_company,taxi.taxi_capacity,taxi.taxi_speed,taxi.max_luggage,taxi.taxi_image,taxi.taxi_serializeimage  from ". TAXI." join ".COMPANY."  ON taxi.taxi_company=company.cid 
		JOIN ".MOTORMODEL."  ON taxi.`taxi_model`=motor_model.model_id 
		JOIN ".COMPANY_MODEL_FARE." as company_model_fare ON taxi.taxi_model = company_model_fare.model_id
		where company_model_fare.company_cid=company.cid  and taxi_id=$taxi_id";
		}
		else
		{
			$result_query = "select company.company_name,(SUM(motor_model.base_fare)*($city_model_fare)/100) + motor_model.base_fare as base_fare,(SUM(motor_model.min_fare)*($city_model_fare)/100) + motor_model.min_fare as min_fare,(SUM(motor_model.cancellation_fare)*($city_model_fare)/100) + motor_model.cancellation_fare as cancellation_fare,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as cancellation_nfree,(select company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=company.cid ) as company_tax,(SUM(motor_model.below_km)*($city_model_fare)/100) + motor_model.below_km as below_km,(SUM(motor_model.above_km)*($city_model_fare)/100) + motor_model.above_km as above_km,motor_model.night_charge,motor_model.night_timing_to,motor_model.night_fare,motor_model.min_km,motor_model.below_above_km,motor_model.model_name,taxi.* from ". TAXI." join ".COMPANY."  ON taxi.taxi_company=company.cid JOIN ".MOTORMODEL."  ON taxi.taxi_model=motor_model.model_id where taxi_id=$taxi_id";
		}								

		$result = Db::query(Database::SELECT, $result_query)
				->execute()
				->as_array();

		
		return array_merge($result,$ratings);
	}

	public function get_company_ownerid($company_id)
	{
			$query= "SELECT id FROM ".PEOPLE." WHERE company_id='$company_id' and user_type='C'";
			$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();

			return $result;
	}
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
		
		//Check Referral code
	public function check_referral_code($referral_code=null)
	{
		try {		

			$sql = "SELECT id FROM ".PASSENGERS." WHERE referral_code='$referral_code'";   
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
			return 1;			
		}		
	}
	
	public function passenger_detailsbyactivationkey($key)
	{

		$query= "SELECT id,email,name,lastname,phone,profile_image,CONCAT(REPEAT('X', CHAR_LENGTH(creditcard_no) - 4),SUBSTRING(creditcard_no, -4)) AS masked_card,creditcard_cvv,CONCAT(REPEAT('X', CHAR_LENGTH(creditcard_cvv))) AS masked_cvv FROM ".PASSENGERS." WHERE activation_key = '$key' ";

		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();

			return $result;
	}
	
	/** Account Validation **/
	public function account_confirmation1($email="",$key="",$company_id="")
	{
		if($company_id != "")
		{
				$checkkey = DB::select()->from(PASSENGERS)
						       ->where('email','=',$email)
						       ->where('user_status', '=', 'A')
						       ->where('activation_status', '=', '1')
						       ->where('passenger_cid', '=', $company_id)
						 		->execute()
					           ->as_array();
		}
		else
		{			
				$checkkey = DB::select()->from(PASSENGERS)
						       ->where('email','=',$email)
						       ->where('user_status', '=', 'A')
						       ->where('activation_status', '=', '1')
						 	   ->execute()
					           ->as_array();			
		}
				if(count($checkkey) == 0)
				{
					//print_r($checkkey);
					$actarr = array('activation_status' => '1','user_status'=>'A');
					$accountactivation_update=DB::update(PASSENGERS)->set($actarr)
							->where('email','=',$email)
							->and_where('activation_key', '=', $key)
							->execute();	
					if($accountactivation_update > 0)
					{					
						return 1;
					}
					else
					{
						return 2;
					}
				}
				else
				{
					return 3;
				}						
	}
	
	/** Resend OTP **/

	public function update_otp($email,$random_key,$otp,$company_id)
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
								//$current_time =	date('Y-m-d H:i:s');

				if($company_id != "")
				{
						$checkkey = DB::select()->from(PASSENGERS)
									   ->where('email','=',$email)
									   ->where('user_status', '=', 'A')
									   ->where('activation_status', '=', '1')
									   ->where('passenger_cid', '=', $company_id)
										->execute()
									   ->as_array();
				}
				else
				{			
						$checkkey = DB::select()->from(PASSENGERS)
									   ->where('email','=',$email)
									   ->where('user_status', '=', 'A')
									   ->where('activation_status', '=', '1')
									   ->execute()
									   ->as_array();			
				}
				//print_r($checkkey);
				//echo count($checkkey);exit;
				if(count($checkkey) == 0)
				{
					$update_otp= DB::update(PASSENGERS)->set(array('otp' =>$otp,'activation_key'=>$random_key,'updated_date'=>$current_time))
					->where('email','=',$email)
					->execute();	

					if($update_otp)
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
					return 3;
				}
	}
	
	/*** get_all_creadit_card **/
	public function get_all_creadit_card($userid="")
	{
    
		$sql = "select count(*) as co_nt from  ".PASSENGERS_CARD_DETAILS." where passenger_id='$userid'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	
	public function get_creadit_card_details($passenger_id="",$card_type=null,$default="")
	{
	$condition="";
	/*if(($card_type == "P") || ($card_type == "B"))
	{
		$condition = " and card_type = '$card_type'";
	}*/	
	if($default == 'yes')
	{
		$condition .= " and default_card = '1'";
	}	 
	 $sql = "select passenger_cardid,passenger_id,card_type,expdatemonth,default_card,expdateyear,creditcard_no,creditcard_cvv,CONCAT(REPEAT('X', CHAR_LENGTH(creditcard_cvv))) AS masked_cvv from ".PASSENGERS_CARD_DETAILS." where passenger_id='$passenger_id' $condition";
	 $result = Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();		
					 
		return $result;				
	}
	
	public function update_default_card($card_id)
	{
			$default_card = '1';
			$update_array = array("default_card" => $default_card);
			$udate_result = DB::update(PASSENGERS_CARD_DETAILS)
					->set($update_array)
					->where('passenger_cardid', '=', $card_id)
					->execute();					
			if(count($udate_result) > 0)
			return 1;
	}
	
	/**Validating Card Details **/
	public function validate_card_details($arr) 
	{
		return Validation::factory($arr)       
			->rule('card_type', 'not_empty')
			->rule('org_creditcard_no','max_length', array(':value', '16'))
			->rule('org_creditcard_cvv','not_empty')
			->rule('org_creditcard_cvv','numeric')
			->rule('expdatemonth','numeric')
			->rule('expdateyear','numeric');
	}
	
		//Add Passenger Card Data
	public function add_carddata($array, $userid, $email)
	{				
		//print_r($array);
		//echo $userid;
		//echo $email;
		//exit;
		try {							
			$p_email = $email;			
			$passenger_id = $userid;
			$creditcard_no = encrypt_decrypt('encrypt',$array['org_creditcard_no']);
			$creditcard_cvv = $array['org_creditcard_cvv'];
			$expdatemonth = $array['expdatemonth'];
			$expdateyear = $array['expdateyear'];
			$card_type = $array['card_type'];
			$default = '0';
			
			$card_result = DB::insert(PASSENGERS_CARD_DETAILS, array('passenger_id','passenger_email','card_type','creditcard_no','creditcard_cvv','expdatemonth','expdateyear','default_card'))
							->values(array($passenger_id,$p_email,'P',$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$default))
							->execute();				
			return 0;							
		} 
		catch (Kohana_Exception $e) {
			echo $e->getMessage();
			return 1;			
		}
	}
	public function check_card_exist($creditcard_no="",$passenger_id="")
	{
		$creditcard_no = encrypt_decrypt('encrypt',$creditcard_no);
		$sql = "SELECT passenger_cardid FROM ".PASSENGERS_CARD_DETAILS." WHERE passenger_id='$passenger_id' and creditcard_no = '$creditcard_no'";   
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
		
		// Get the card details
	public function get_card_details($id="")
	{
		$sql = "SELECT * FROM ".PASSENGERS_CARD_DETAILS." WHERE passenger_cardid = '$id' ";                      
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}
	
	public function edit_carddata($array, $userid, $card_id, $email)
	{				
		//print_r($array);
		//echo $referred_passenger_id;
		//exit;
		try {										
			$passenger_cardid = $card_id;
			$passenger_id = $userid;
			$creditcard_no = encrypt_decrypt('encrypt',$array['org_creditcard_no']);
			$creditcard_cvv = $array['org_creditcard_cvv'];
			$expdatemonth = $array['expdatemonth'];
			$expdateyear = $array['expdateyear'];
			$card_type = $array['card_type'];
			$update_array = array("card_type" => $card_type,
			"creditcard_no"=>$creditcard_no,
			"creditcard_cvv"=>$creditcard_cvv,
			"expdatemonth"=>$expdatemonth,
			"expdateyear"=>$expdateyear);
			$udate_result = DB::update(PASSENGERS_CARD_DETAILS)
					->set($update_array)
					->where('passenger_cardid', '=', $passenger_cardid)							
					->execute();
			return 0;							
		} 
		catch (Kohana_Exception $e) {
			echo $e->getMessage();
			return 1;			
		}
	}

	public function get_passenger_ongoing_transaction($id,$current_date = 1,$fromdate = "",$todate = "")
	{
		if($current_date == 1)
		{
			$start_time = date('Y-m-d').' 00:00:01';
			$end_time = date('Y-m-d').' 23:59:59';
		}
		else
		{
			$start_time = $fromdate;
			$end_time = $todate;
		}
		$result = DB::select(PASSENGERS_LOG.'.passengers_log_id',PASSENGERS_LOG.'.passengers_id',PASSENGERS_LOG.'.driver_id',PASSENGERS_LOG.'.taxi_id', PASSENGERS_LOG.'.company_id',PASSENGERS_LOG.'.current_location',PASSENGERS_LOG.'.pickup_latitude',PASSENGERS_LOG.'.pickup_longitude', PASSENGERS_LOG.'.drop_location',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.pickup_longitude', PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.drop_latitude',PASSENGERS_LOG.'.drop_longitude',PASSENGERS_LOG.'.distance', PASSENGERS_LOG.'.no_passengers',PASSENGERS_LOG.'.approx_duration',PASSENGERS_LOG.'.approx_fare',PASSENGERS_LOG.'.pickup_time', PASSENGERS_LOG.'.travel_status',PASSENGERS_LOG.'.driver_reply',PASSENGERS_LOG.'.comments',PASSENGERS_LOG.'.driver_comments', PASSENGERS_LOG.'.fixedprice',PASSENGERS_LOG.'.company_tax',PASSENGERS_LOG.'.faretype',PASSENGERS_LOG.'.bookingtype', PASSENGERS_LOG.'.luggage',PASSENGERS_LOG.'.bookby',PASSENGERS_LOG.'.operator_id',PASSENGERS_LOG.'.controller_rating', PASSENGERS_LOG.'.controller_comments',PEOPLE.'.name')->from(PASSENGERS_LOG)
					->join(PEOPLE)->on(PASSENGERS_LOG.'.driver_id','=',PEOPLE.'.id')
					->where(PASSENGERS_LOG.'.passengers_id','=',$id)
					->where(PASSENGERS_LOG.'.driver_reply','=','A')
					->and_where_open()
						->where(PASSENGERS_LOG.'.travel_status','=','9')
						->or_where(PASSENGERS_LOG.'.travel_status','=','2')
						->or_where(PASSENGERS_LOG.'.travel_status','=','3')
						->or_where(PASSENGERS_LOG.'.travel_status','=','5')
					->and_where_close()
					->where(PASSENGERS_LOG.'.pickup_time','>=',$start_time)
					->where(PASSENGERS_LOG.'.pickup_time','<=',$end_time)
					->order_by(PASSENGERS_LOG.'.passengers_log_id', 'desc')
					->as_object()
					->execute();
					
		return $result;
	}


	
}

?>
