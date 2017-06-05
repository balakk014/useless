<?php defined('SYSPATH') OR die('No Direct Script Access');

/******************************************

* Contains Users module details

* @Package: ConnectTaxi

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************/

Class Model_Edit extends Model
{
	public function __construct()
	{	
		$this->session = Session::instance();	
		$this->username = $this->session->get("username");
		$this->admin_username = $this->session->get("username");
		$this->admin_userid = $this->session->get("id");
		$this->admin_email = $this->session->get("email");
		$this->user_admin_type = $this->session->get("user_type");
		$this->currentdate=Commonfunction::getCurrentTimeStamp();

	}
	
	/**Validating for Add company**/
	public function validate_editcompany($arr,$uid) 
	{ 
		$validation = Validation::factory($arr)   

			
			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('email', 'Model_Edit::checkemail', array(':value',$uid))
			
           		->rule('firstname', 'not_empty')
           		//->rule('firstname', 'alpha_dash')
			->rule('firstname', 'min_length', array(':value', '4'))
			->rule('firstname', 'max_length', array(':value', '30'))
			->rule('firstname','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
            		->rule('lastname', 'not_empty')
			->rule('lastname','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
            		//->rule('lastname', 'alpha_dash')
			//->rule('lastname', 'min_length', array(':value', '4'))
			//->rule('lastname', 'max_length', array(':value', '30'))

			->rule('phone', 'not_empty')
			//->rule('phone', 'numeric')
			->rule('phone', 'min_length', array(':value', '7'))
			->rule('phone', 'max_length', array(':value', '20'))
			//->rule('phone', 'phone', array(':value'))
			->rule('phone', 'contact_phone', array(':value'))
			->rule('phone', 'Model_Edit::checkphone', array(':value',$uid))

			->rule('company_name', 'not_empty')
			->rule('company_name', 'min_length', array(':value', '4'))
			->rule('company_name', 'max_length', array(':value', '30'))
			->rule('company_name','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
			//->rule('company_name', 'Model_Edit::checkcompany', array(':value',$arr['country'],$arr['state'],$arr['city'],$uid))

			//->rule('paypal_api_username','not_empty')
			//->rule('paypal_api_password','not_empty')
			//->rule('paypal_api_signature','not_empty')
			//->rule('payment_method','not_empty')
									
			->rule('address', 'not_empty')
			->rule('country', 'not_empty')
			->rule('state', 'not_empty')
			->rule('city', 'not_empty')
			
			//->rule('company_address', 'not_empty')
			->rule('currency_code', 'not_empty')
			->rule('currency_symbol', 'not_empty')
			->rule('currency_symbol', 'Model_Edit::checksite_currency', array(':value',$arr['currency_code']))
			->rule('time_zone', 'not_empty');
		/*	if($this->user_admin_type=='A' || $this->user_admin_type=='DA')
			{
						$validation->rule('paymodstatus', 'not_empty');

			}*/

		return $validation;
	}

	/**Validating for Add Motor**/
	public function validate_editmotor($arr,$uid) 
	{

		return Validation::factory($arr)       
		
			->rule('companyname', 'not_empty')
			//->rule('companyname', 'alpha_dash')
			->rule('companyname', 'min_length', array(':value', '2'))
			->rule('companyname', 'max_length', array(':value', '30'))
			->rule('companyname', 'Model_Edit::checkmotor', array(':value',$uid));

	}

	/**Validating for Add company**/
	public function validate_editdriver($arr,$uid) 
	{
		$validate = Validation::factory($arr)       
		
			->rule('firstname', 'not_empty')
			//->rule('username', 'alpha_dash')
			->rule('firstname', 'min_length', array(':value', '4'))
			->rule('firstname', 'max_length', array(':value', '30'))
			->rule('firstname','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
			->rule('lastname', 'not_empty')
			->rule('lastname','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
			//->rule('username', 'alpha_dash')
			//->rule('lastname', 'min_length', array(':value', '4'))
			//->rule('lastname', 'max_length', array(':value', '30'))

			->rule('dob', 'not_empty')
			
			->rule('phone', 'not_empty')
			//->rule('phone','Model_Add::check_valid_phone_number',array(':value','/^[0-9()-+]*$/u'))
			//->rule('phone', 'alpha_numeric')			
			->rule('phone', 'min_length', array(':value', '7'))
			->rule('phone', 'max_length', array(':value', '20'))
			//->rule('phone', 'phone', array(':value'))
			->rule('phone', 'contact_phone', array(':value'))
			->rule('phone', 'Model_Edit::checkphone', array(':value',$uid))

			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('email', 'Model_Edit::checkemail', array(':value',$uid))
		
			->rule('password', 'not_empty')
			->rule('password', 'min_length', array(':value', '6'))
			->rule('password', 'max_length', array(':value', '20'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))

			->rule('repassword', 'not_empty')
			->rule('repassword', 'min_length', array(':value', '6'))
			->rule('repassword', 'max_length', array(':value', '20'))
			->rule('repassword','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('repassword',  'matches', array(':validation', 'password', 'repassword'))
			
			->rule('driver_license_id', 'not_empty')
			->rule('driver_license_id', 'max_length', array(':value', '30'))
			->rule('driver_license_id', 'Model_Edit::checklicenceId', array(':value',$uid))
			->rule('driver_license_expire_date', 'not_empty')
			/*->rule('driver_pco_license_number', 'not_empty')
			->rule('driver_pco_license_number', 'max_length', array(':value', '30'))
			->rule('driver_pco_license_number', 'Model_Edit::checkpcolicenceNo', array(':value',$uid))
			->rule('driver_pco_license_expire_date', 'not_empty')
			->rule('driver_insurance_number', 'not_empty')*/
			->rule('driver_insurance_number', 'max_length', array(':value', '30'))
			->rule('driver_insurance_number', 'Model_Edit::checkinsuranceNo', array(':value',$uid))
			/*->rule('driver_insurance_expire_date', 'not_empty')
			>rule('driver_national_insurance_number', 'not_empty')
			->rule('driver_national_insurance_number', 'max_length', array(':value', '30'))
			->rule('driver_national_insurance_number', 'Model_Edit::checkNationalinsuranceNo', array(':value',$uid))
			->rule('driver_national_insurance_expire_date', 'not_empty')*/
			->rule('address', 'not_empty')
			->rule('country', 'not_empty')
			->rule('state', 'not_empty');
			if($arr['brand_type'] == "M") {
				$validate->rule('company_name', 'not_empty');
			}
			
			/*$validate->rule('booking_limit', 'not_empty')*/
			
			$validate->rule('booking_limit', 'numeric')
			
			->rule('booking_limit', 'Model_Add::check_booking_limit', array(':value',$arr['booking_limit']))
			
			->rule('city', 'not_empty')
			
			->rule('profile_picture', 'Upload::type', array(':value', array('jpg', 'png', 'gif')));
			
			return $validate;

			//->rule('company_name', 'Model_Edit::checkassigneddriver',array($arr['city'],$arr['country'],$arr['state'],$arr['company_name'],$uid));

			//->rule('photo', 'Upload::type', array(':value', array('jpeg','jpg','png','gif')));
	}
	
	public static function checkassigneddriver($city,$country,$state,$company_name,$uid)
	{
		
			$date_where = " AND ( mapping_startdate >= now() or mapping_enddate >= now() ) ";	
				
			$query = " select * from ".TAXIMAPPING." where (".TAXIMAPPING.".mapping_companyid != '$company_name' or ".TAXIMAPPING.".mapping_countryid != '$country'  or ".TAXIMAPPING.".mapping_stateid != '$state' or ".TAXIMAPPING.".mapping_cityid !='$city') and ".TAXIMAPPING.".mapping_driverid =$uid $date_where ";

 		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();
			 
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}

	}
	
	
	public function validate_editmodel($arr,$uid) 
	{

		$validation = Validation::factory($arr)       
		
			->rule('model_name', 'not_empty')
			//->rule('model_name', 'alpha_dash')
			->rule('model_name', 'min_length', array(':value', '2'))
			->rule('model_name', 'max_length', array(':value', '30'))
			->rule('model_name','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
			->rule('model_name', 'Model_Edit::checkmodelname', array(':value',$arr['companyname'],$uid))
			
			->rule('model_size', 'not_empty')
			->rule('model_size', 'Model_Edit::check_fare_zero', array(':value',$arr['model_size']))
			
			->rule('companyname', 'not_empty')
			->rule('companyname','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
			->rule('waiting_time', 'not_empty')
			->rule('waiting_time', 'Model_Edit::check_waiting_time', array(':value',$arr['waiting_time']))
			
			->rule('base_fare', 'not_empty')
			->rule('base_fare', 'Model_Edit::check_base_fare', array(':value',$arr['base_fare']))
			
			->rule('min_km', 'not_empty')
			->rule('min_km', 'Model_Add::check_min_km', array(':value',$arr['min_km']))
			
			->rule('min_fare', 'not_empty')
			->rule('min_fare', 'Model_Edit::check_min_fare', array(':value',$arr['min_fare']))
						
			->rule('cancellation_fare', 'not_empty')
			->rule('cancellation_fare', 'Model_Edit::check_cancellation_fare', array(':value',$arr['cancellation_fare']))
			
			->rule('below_and_above_km', 'not_empty')
			->rule('below_and_above_km', 'Model_Add::check_below_and_above_km', array(':value',$arr['min_km']))
						
			->rule('below_km', 'not_empty')
			->rule('below_km', 'Model_Edit::check_below_km', array(':value',$arr['below_km']))
						
			->rule('above_km', 'not_empty')	
			->rule('above_km', 'Model_Edit::check_above_km', array(':value',$arr['above_km']))		
			
			->rule('night_charge', 'not_empty')			
			
			->rule('minutes_fare', 'not_empty')
			->rule('minutes_fare', 'Model_Edit::check_minute_fare', array(':value',$arr['minutes_fare']))
			->rule('model_thumb_image', 'Upload::type', array(':value', array('jpeg','jpg','png')))
			->rule('model_thumb_act_image', 'Upload::type', array(':value', array('jpeg','jpg','png')))
			->rule('model_image', 'Upload::type', array(':value', array('jpeg','jpg','png')));
			
			if(Arr::get($arr,'night_charge')==1)
			{
				//echo "dsf";exit;
			   $validation->rule('night_timing_from', 'not_empty')
						 ->rule('night_timing_to', 'not_empty')
						 ->rule('night_fare', 'not_empty')
						 ->rule('night_fare', 'Model_Edit::check_night_fare', array(':value',$arr['night_fare']))
						 ->rule('night_fare', 'Model_Edit::check_fare_zero', array(':value',$arr['night_fare']))
						 ->rule('night_fare', 'Model_Admin::check_percentage', array(':value'));
		    } 
		    
		    if(Arr::get($arr,'evening_charge')==1)
			{
				//echo "dsf";exit;
			   $validation->rule('evening_timing_from', 'not_empty')
						 ->rule('evening_timing_to', 'not_empty')
						 ->rule('evening_fare', 'not_empty')
						->rule('evening_fare', 'Model_Add::check_evening_fare', array(':value',$arr['evening_fare']))
						->rule('evening_fare', 'Model_Edit::check_fare_zero', array(':value',$arr['evening_fare']))
						 ->rule('evening_fare', 'Model_Admin::check_percentage', array(':value'));
		    } 
		    return $validation;

	}
	
	public function validate_editfare($arr) 
	{

		$validation = Validation::factory($arr)       
			
			->rule('base_fare', 'not_empty')
			->rule('base_fare', 'Model_Edit::check_base_fare', array(':value',$arr['base_fare']))
			->rule('base_fare', 'Model_Edit::check_fare_zero', array(':value',$arr['base_fare']))
			
			->rule('model_name', 'not_empty')
			->rule('model_name','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
			
			->rule('model_size', 'not_empty')
			->rule('model_size', 'Model_Edit::check_fare_zero', array(':value',$arr['model_size']))
			
			->rule('min_km', 'not_empty')
			->rule('min_km', 'Model_Add::check_min_km', array(':value',$arr['min_km']))
			
			->rule('min_fare', 'not_empty')
			->rule('min_fare', 'Model_Edit::check_min_fare', array(':value',$arr['min_fare']))
						
			->rule('cancellation_fare', 'not_empty')
			->rule('cancellation_fare', 'Model_Edit::check_cancellation_fare', array(':value',$arr['cancellation_fare']))
			
			->rule('below_and_above_km', 'not_empty')
			->rule('below_and_above_km', 'Model_Add::check_below_and_above_km', array(':value',$arr['min_km']))
						
			->rule('below_km', 'not_empty')
			->rule('below_km', 'Model_Edit::check_below_km', array(':value',$arr['below_km']))
			->rule('below_km', 'Model_Edit::check_fare_zero', array(':value',$arr['below_km']))
						
			->rule('above_km', 'not_empty')	
			->rule('above_km', 'Model_Edit::check_above_km', array(':value',$arr['above_km']))
			->rule('above_km', 'Model_Edit::check_fare_zero', array(':value',$arr['above_km']))
			
			->rule('minutes_fare', 'not_empty')
			->rule('minutes_fare', 'Model_Edit::check_minute_fare', array(':value',$arr['minutes_fare']))
			
			->rule('night_charge', 'not_empty')
			->rule('evening_charge', 'not_empty');
			
			if(Arr::get($arr,'night_charge')==1)
			{
				//echo "dsf";exit;
			   $validation->rule('night_timing_from', 'not_empty')
						 ->rule('night_timing_to', 'not_empty')
						 ->rule('night_fare', 'not_empty')
						 ->rule('night_fare', 'Model_Edit::check_night_fare', array(':value',$arr['night_fare']))
						 ->rule('night_fare', 'Model_Edit::check_fare_zero', array(':value',$arr['night_fare']))
						 ->rule('night_fare', 'Model_Admin::check_percentage', array(':value'));
		    } 
		     if(Arr::get($arr,'evening_charge')==1)
			{
				//echo "dsf";exit;
			   $validation->rule('evening_timing_from', 'not_empty')
						 ->rule('evening_timing_to', 'not_empty')
						 ->rule('evening_fare', 'not_empty')
						->rule('evening_fare', 'Model_Add::check_evening_fare', array(':value',$arr['evening_fare']))
						->rule('evening_fare', 'Model_Edit::check_fare_zero', array(':value',$arr['evening_fare']))
						 ->rule('evening_fare', 'Model_Admin::check_percentage', array(':value'));
		    } 
		    return $validation;

	}
	
	/**Validating for Add Taxi**/
	public function validate_editfield($arr,$uid) 
	{

		$rule = Validation::factory($arr)       
		
		->rule('field_labelname', 'not_empty')
		->rule('field_labelname', 'min_length', array(':value', '2'))
		->rule('field_labelname', 'max_length', array(':value', '20'))


		->rule('field_name', 'not_empty')
		->rule('field_name', 'min_length', array(':value', '2'))
		->rule('field_name', 'max_length', array(':value', '20'))
		->rule('field_name', 'small_letters', array(':value'))
		->rule('field_name', 'Model_Edit::checkfieldname', array(':value',$uid))

		->rule('field_type', 'not_empty');
		
		if($arr['field_type'] !='Textbox')
		{
		//$rule = $rule->rule('field_value', 'not_empty');
		}
		
		return $rule;

	}

	public static function checkfieldname($name,$uid)
	{	
		// Check if the username already exists in the database

		$result = DB::select('field_name')->from(MANAGEFIELD)->where('field_name','=',$name)->where('field_id','!=',$uid)
			->execute()
			->as_array();
						
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}

	/**Validating for Add Taxi**/
	public function validate_edittaxi($arr,$form_values,$uid) 
	{ 

		$rule = Validation::factory($arr)       


		->rule('taxi_no', 'not_empty')
		->rule('taxi_no', 'min_length', array(':value', '4'))
		->rule('taxi_no', 'max_length', array(':value', '30'))
		//->rule('taxi_no', 'alpha_numeric', array(':value','/^[0-9]{1,}/'))
		->rule('taxi_no', 'regex', array(':value','/^[a-z0-9A-Z -]++$/iD'))
		->rule('taxi_no', 'Model_Edit::check_taxino', array(':value',$uid))
		
		//->rule('taxi_type', 'not_empty')
		->rule('taxi_model', 'not_empty')
		//->rule('taxi_min_speed', 'not_empty')
		->rule('taxi_owner_name', 'not_empty')
		->rule('taxi_owner_name','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
		->rule('taxi_manufacturer', 'not_empty')

		->rule('taxi_colour', 'not_empty')
		->rule('taxi_color','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
		->rule('taxi_motor_expire_date', 'not_empty')
		->rule('taxi_insurance_number', 'not_empty')
		->rule('taxi_insurance_number', 'Model_Edit::check_taxinsurance_number', array(':value',$uid))
		->rule('taxi_insurance_expire_date', 'not_empty')
		//->rule('taxi_pco_licence_number', 'not_empty')
		//->rule('taxi_pco_licence_number', 'Model_Edit::check_taxipco_number', array(':value',$uid))
		//->rule('taxi_pco_licence_expire_date', 'not_empty')
		->rule('country', 'not_empty')
		->rule('state', 'not_empty')
		->rule('city', 'not_empty')
		->rule('company_name', 'not_empty')
		
	/*	->rule('taxi_capacity', 'not_empty')
		->rule('taxi_capacity', 'min_length', array(':value', '1'))
		->rule('taxi_capacity', 'max_length', array(':value', '20'))
		->rule('taxi_capacity', 'digit', array(':value','/^[0-9]{1,}/'))*/

		/*->rule('taxi_fare_km', 'not_empty')
		->rule('taxi_fare_km', 'min_length', array(':value', '1'))
		->rule('taxi_fare_km', 'max_length', array(':value', '20'))
		->rule('taxi_fare_km', 'digit', array(':value','/^[0-9]{1,}/'))*/

		->rule('company_name', 'Model_Edit::checkassignedtaxi',array($arr['city'],$arr['country'],$arr['state'],$arr['company_name'],$uid));			

		foreach($arr as $key => $value)
		{	if(in_array($value,$form_values)) 
			{	
				$rule = $rule->rule($value, 'not_empty');
			}
		
		}
				
		return $rule;

	}


	public static function checkassignedtaxi($city,$country,$state,$company_name,$uid)
	{
		
			$date_where = " AND ( mapping_startdate >= now() or mapping_enddate >= now() ) ";	
				
			$query = " select * from ".TAXIMAPPING." where (".TAXIMAPPING.".mapping_companyid != '$company_name' or ".TAXIMAPPING.".mapping_countryid != '$country'  or ".TAXIMAPPING.".mapping_stateid != '$state' or ".TAXIMAPPING.".mapping_cityid !='$city') and ".TAXIMAPPING.".mapping_taxiid =$uid $date_where ";

 		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();
			 
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}

	}
	
	/**Validating for Add Motor**/
	public function validate_editpackage($arr,$uid) 
	{
		return Validation::factory($arr)       
		
			->rule('package_name', 'not_empty')
			->rule('package_name', 'min_length', array(':value', '4'))
			->rule('package_name', 'max_length', array(':value', '100'))
			->rule('package_name', 'Model_Edit::checkpackagename', array(':value',$uid))
			
			->rule('package_description', 'not_empty')
			->rule('package_description', 'min_length', array(':value', '20'))

			->rule('no_of_taxi', 'not_empty')
			->rule('no_of_taxi', 'Model_Edit::check_fare_zero',array(':value'))
			->rule('no_of_taxi', 'digit')

			->rule('no_of_driver', 'not_empty')
			->rule('no_of_driver', 'Model_Edit::check_fare_zero',array(':value'))
			->rule('no_of_driver', 'digit')
			
			->rule('days_expire', 'not_empty')
			->rule('days_expire', 'Model_Edit::check_fare_zero',array(':value'))
			->rule('days_expire', 'digit')
						
			->rule('package_price', 'not_empty')
			->rule('package_price', 'numeric')
			->rule('days_expire', 'Model_Add::checkpackageExists', array($arr['package_price'],$arr['no_of_taxi'],$arr['no_of_driver'],$arr['days_expire'],$uid));

			

	}

	public function validate_editcompanypayment($arr,$uid) 
	{
		if(isset($arr["payment_method"]) && $arr["payment_method"] == "T") {
			return Validation::factory($arr)     
                        ->rule('description', 'not_empty')
                        ->rule('currency_code','not_empty')
                        ->rule('currency_code', 'max_length', array(':value', '3'))
                        ->rule('currency_symbol','not_empty')
                        ->rule('currency_symbol', 'Model_Admin::checksite_currency', array(':value',$arr['currency_code']))
                        ->rule('payment_method','not_empty')
                        ->rule('paypal_api_username','not_empty')
                        ->rule('paypal_api_password','not_empty')
                        ->rule('paypal_api_signature','not_empty');
		} else {
			return Validation::factory($arr)     
                        ->rule('description', 'not_empty')
                        ->rule('currency_code','not_empty')
                        ->rule('currency_code', 'max_length', array(':value', '3'))
                        ->rule('currency_symbol','not_empty')
                        ->rule('currency_symbol', 'Model_Admin::checksite_currency', array(':value',$arr['currency_code']))
                        ->rule('payment_method','not_empty')
                        ->rule('live_paypal_api_username','not_empty')
                        ->rule('live_paypal_api_password','not_empty')
                        ->rule('live_paypal_api_signature','not_empty');
		}
	}

	//To update company Functionalities 
	public static function editcompany($uid,$post,$files)
	{
		//print_r($post);exit;
		$result='';$result1='';$result2='';$check='';
		$company_id = $_SESSION['company_id'];
		if(isset($files['taxi_image']['name']) && $files['taxi_image']['name'] != "")
		{
			$check = "";
			$image_name = $uid; 
			$filename = Upload::save($files['taxi_image'],$image_name,DOCROOT.COMPANY_IMG_IMGPATH);
			$logo_image = Image::factory($filename);
			$path1 = DOCROOT.COMPANY_IMG_IMGPATH;
			$path = $image_name;
			Commonfunction::multipleimageresize($logo_image,COMPANY_IMG_WIDTH, COMPANY_IMG_HEIGHT,$path1,$image_name,90);
			$check = 1;
		}
		
		$check = 1;
			
		$result=DB::update(PEOPLE)->set(array('name'=>$post['firstname'],'lastname'=>$post['lastname'],'country_code'=>$post['telephone_code'],'phone'=>$post['phone'],'address'=>$post['address'],'email'=>$post['email'],'login_country'=>$post['country'],'login_state'=>$post['state'],'login_city'=>$post['city']))
				->where('id','=',$uid)->where('user_type','=','C')
				->execute();
		//'paypal_account'=>$post['paypal_api_username'],			
		$result1=DB::update(COMPANY)->set(array('company_name'=>$post['company_name'],'company_address'=>$post['company_address'],'company_country'=>$post['country'],'company_state'=>$post['state'],'company_city'=>$post['city'],'time_zone'=>$post['time_zone']))
				->where('userid','=',$uid)
				->where('cid','=',$post['company_id'])
				->execute();	
		$company_cid = $post['company_id'];
		$result2=DB::update(COMPANYINFO)->set(array('company_currency'=>$post['currency_symbol'],'company_currency_format'=>$post['currency_code']))
				->where('company_cid','=',$company_cid)
				->execute();
		//'company_paypal_username'=>$post['paypal_api_username'],'company_paypal_password'=>$post['paypal_api_password'],'company_paypal_signature'=>$post['paypal_api_signature'],'payment_method'=>$post['payment_method'])
		
		/** Company package expiry date upgrade functionality if it is changed **
		if(!empty($post['prev_expiry_date']) && $post['prev_expiry_date'] != $post['expire_date']) { 
			$packageupdate = DB::update(PACKAGE_REPORT)->set(array('upgrade_expirydate'=>$post['expire_date']))->where('upgrade_companyid','=',$company_cid)
				->execute();
			//taxi driver mapping date extended for that particular company
			$taximappingupdate = DB::update(TAXIMAPPING)->set(array('mapping_enddate'=>$post['expire_date']))->where('mapping_companyid','=',$company_cid)
				->execute();
			//status update in people table
			$stsupdate = DB::update(PEOPLE)->set(array('status'=>'A'))->where('company_id','=',$company_cid)->execute();
		} */
		
		
			/***Company payment settings Update***/
			/*if(isset($post['payid'])){
				foreach($post['payid'] as $k=>$id)
				{
					//print_r($id);exit;
					if($id == $post['default'][0]){
						$default = '1';
					}else{
						$default = '0';
					}

					if(in_array($id,$post['paymodstatus'])){ 
						$paystatus = "1";
					}else{
						$paystatus = '';
					}

					$result3=DB::update(COMPANY_PAYMENT_MODULES)->set(array('pay_active'=>$paystatus,'pay_mod_default'=>$default))
						->where('compay_payment_id','=',$id)
						->execute();
				}
			}
			
			if($uid!=0){
				/***Company payment settings Insert***
				if(isset($post['payid_add'])){
					foreach($post['payid_add'] as $k=>$id)
					{  
				
						if($id == $post['default'][0]){
							$default = '1';
						}else{
							$default = '0';
						}

						if(in_array($id,$post['paymodstatus'])){ 
							$paystatus = "1";
						}else{
							$paystatus = '';
						}
						
						$pay_result =  DB::insert('company_payment_module',array('company_id','company_user_id','pay_mod_id','pay_mod_name','pay_mod_image','pay_active','pay_mod_default'))
							->values(array($post['company_id'],$uid,$post['payid_add'][$k],$post['paymodname'][$k],$post['paymodimage'][$k],$paystatus,$default))
							->execute();
					}
				}
			} */
		
		
		//echo $uid."--".$result."--".$result1."--".$result2."--".$check;exit;
		if($result || $result1 || $result2 || $check == 1)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}




	//To update company Functionalities 
	public static function editmotor($uid,$post)
	{
		
		$result=DB::update(MOTORCOMPANY)->set(array('motor_name'=>$post['companyname']))
				->where('motor_id','=',$uid)
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

	public static function editcompanypayment($uid,$post)
	{
		$company_id = $_SESSION['company_id']; $payment = array();
		if($post['payment_method'] == "T") {
			$paypal_api_username_field = "paypal_api_username";
			$paypal_api_password_field = "paypal_api_password";
			$paypal_api_signature_field = "paypal_api_signature";
			$paypal_api_username = $post['paypal_api_username'];
			$paypal_api_password = $post['paypal_api_password'];
			$paypal_api_signature = $post['paypal_api_signature'];
		} else {
			$paypal_api_username_field = "live_paypal_api_username";
			$paypal_api_password_field = "live_paypal_api_password";
			$paypal_api_signature_field = "live_paypal_api_signature";
			$paypal_api_username = $post['live_paypal_api_username'];
			$paypal_api_password = $post['live_paypal_api_password'];
			$paypal_api_signature = $post['live_paypal_api_signature'];
		}
		$query = array('description' => $post['description'],
				'currency_code' => $post['currency_code'],
				'currency_symbol' => $post['currency_symbol'],
				'payment_method' => $post['payment_method'],
				$paypal_api_username_field => $paypal_api_username,
				$paypal_api_password_field => $paypal_api_password,
				$paypal_api_signature_field => $paypal_api_signature);

		$result =  DB::update(PAYMENT_GATEWAYS)->set($query)
					->where('id', '=' ,$uid)
					->where('company_id', '=' ,$company_id)	
					->execute();
		if($result && $post['check_default'] == 1) {
			DB::update(SITEINFO)->set(array('currency_format' => $post['currency_code'],'site_currency' => $post['currency_symbol']))->where('id', '=' ,'1')->execute();
		}

		return $result;

	}

	
	
	//To update company Functionalities 
	public static function editmodel($uid,$post)
	{
		
		$result=DB::update(MOTORMODEL)->set(array('model_name'=>$post['model_name'],'model_size'=>$post['model_size'],'motor_mid'=>$post['companyname'],'base_fare'=>$post['base_fare'],'min_fare'=>$post['min_fare'],'cancellation_fare'=>$post['cancellation_fare'],'below_km'=>$post['below_km'],'above_km'=>$post['above_km'],'night_charge'=>$post['night_charge'],'night_timing_from'=>$post['night_timing_from'],'night_timing_to'=>$post['night_timing_to'],'night_fare'=>$post['night_fare'],'evening_charge'=>$post['evening_charge'],'evening_timing_from'=>$post['evening_timing_from'],'evening_timing_to'=>$post['evening_timing_to'],'evening_fare'=>$post['evening_fare'],'waiting_time'=>$post['waiting_time'],'min_km'=>$post['min_km'],'below_above_km' =>$post['below_and_above_km'],'minutes_fare'=>$post['minutes_fare'],'taxi_speed'=>$post['taxi_speed'],'taxi_min_speed'=>$post['taxi_min_speed']))
	
				->where('model_id','=',$uid)
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
	
	//To update company Functionalities 
	public static function editfare($post)
	{
		
		$result=DB::update(COMPANY_MODEL_FARE)->set(array('model_name'=>$post['model_name'],'model_size'=>$post['model_size'],'base_fare'=>$post['base_fare'],'min_fare'=>$post['min_fare'],'cancellation_fare'=>$post['cancellation_fare'],'below_km'=>$post['below_km'],'above_km'=>$post['above_km'],'night_charge'=>$post['night_charge'],'night_timing_from'=>$post['night_timing_from'],'night_timing_to'=>$post['night_timing_to'],'night_fare'=>$post['night_fare'],'evening_charge'=>$post['evening_charge'],'evening_timing_from'=>$post['evening_timing_from'],'evening_timing_to'=>$post['evening_timing_to'],'evening_fare'=>$post['evening_fare'],'min_km'=>$post['min_km'],'waiting_time'=>$post['waiting_time'],'below_above_km' =>$post['below_and_above_km'],'minutes_fare'=>$post['minutes_fare']))
	
				->where('company_model_fare_id','=',$post['company_model_fare_id'])
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

	public static function get_payment_details($uid)
	{
		$company_id = $_SESSION['company_id'];

		$result = DB::select('payment_gatway','description','currency_code','currency_symbol','payment_method','paypal_api_username','paypal_api_password','paypal_api_signature','live_paypal_api_username','live_paypal_api_password','live_paypal_api_signature')->from(PAYMENT_GATEWAYS)->where('company_id','=',$company_id)
			->where('id','=',$uid)
			->execute()
			->as_array();
		return $result;
	}
	
	//To update Edit field Functionalities 
	public static function editfield($uid,$post)
	{
		$result = DB::select('field_name')->from(MANAGEFIELD)->where('field_id','=',$uid)
			->execute()
			->as_array();
			$get_field_name = $result[0]['field_name'];
			$posted_field_name = $post['field_name'];
			if($posted_field_name != $get_field_name)
			{
				DB::query(5,"ALTER TABLE taxi_additional_field CHANGE ".$get_field_name." ".$posted_field_name." varchar(250)  NOT NULL")
				->execute();
			}
	
		$result = DB::update(MANAGEFIELD)->set(array('field_labelname'=>$post['field_labelname'],'field_name'=>$post['field_name'],'field_type'=>$post['field_type'],'field_value'=>$post['field_value']))
			->where('field_id','=',$uid)
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
		
	
	// To Check Company Name is Already Available or Not
	public static function checkcompany($companyname,$country,$state,$city,$uid)
	{
		// Check if the username already exists in the database
		$result = DB::select('company_name')->from(COMPANY)->where('company_name','=',$companyname)->where('company_country','=',$country)->where('company_state','=',$state)->where('company_city','=',$city)->where('userid','!=',$uid)
			->execute()
			->as_array();
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}	
	
		// To Check Company Name is Already Available or Not
	public static function checkmotor($companyname,$uid)
	{
		// Check if the username already exists in the database
		$result = DB::select('motor_name')->from(MOTORCOMPANY)->where('motor_name','=',$companyname)->where('motor_id','!=',$uid)
		->execute()
		->as_array();

			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}	

	public static function checkmodel($companyname,$modelname,$motorid,$uid)
	{
		// Check if the username already exists in the database
		$result = DB::select('model_name')->from(MOTORMODEL)->where('model_name','=',$modelname)->where('model_id','!=',$uid)->where('motor_mid','=',$companyname)
		->execute()
		->as_array();

			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}
		
	// Check Whether Email is Already Exist or Not
	public static function checkemail($email="",$uid)
	{
		$result = DB::select('email')->from(PEOPLE)->where('email','=',$email)->where('id','!=',$uid)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return false;
			}
			else
			{
				return true;		
			}
	}
	
		// Check Whether Email is Already Exist or Not
	public static function checkphone($phone="",$uid)
	{


		$result = DB::select('phone')->from(PEOPLE)->where('phone','=',$phone)->where('id','!=',$uid)
			->execute()
			->as_array();
			
			if(count($result) > 0)
			{ 
				return false;
			}
			else
			{
				return true;		
			}
	}	
	// Check Whether Email is Already Exist or Not
	public static function check_passengeremail($email="",$uid)
	{

		$result = DB::select('email')->from(PASSENGERS)->where('email','=',$email)->where('id','!=',$uid)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return false;
			}
			else
			{
				return true;		
			}
	}
	
	// Check Whether Company details is Already Exist or Not
	public static function company_details($uid)
	{

		$result = DB::select()->from(COMPANY)->join(PEOPLE, 'LEFT')->on(PEOPLE.'.id', '=', COMPANY.'.userid')->join(COMPANYINFO,'LEFT')->on(COMPANY.'.cid', '=', COMPANYINFO.'.company_cid')->where(PEOPLE.'.user_type', '=', 'C')->where(COMPANY.'.userid','=',$uid)
			->execute()
			->as_array();

		  return $result;
	}
	
	public static function company_details_new($uid)
	{
		$result = DB::select()->from(COMPANY)->join(PEOPLE, 'LEFT')->on(PEOPLE.'.id', '=', COMPANY.'.userid')->join(COMPANYINFO,'LEFT')->on(COMPANY.'.cid', '=', COMPANYINFO.'.company_cid')->join(PACKAGE_REPORT,'LEFT')->on(COMPANY.'.cid', '=', PACKAGE_REPORT.'.upgrade_companyid')->where(PEOPLE.'.user_type', '=', 'C')->where(PEOPLE.'.id','=',$uid)
			->execute()
			->as_array();

		  return $result;
	}
	
	// Check Whether Motor details is Already Exist or Not
	public static function motor_details($uid)
	{
			$result = DB::select()->from(MOTORCOMPANY)->where('motor_id','=',$uid)
			->execute()
			->as_array();
		  return $result;
	}

	public function model_details($offset, $val)
	{
		$result =DB::select()->from(MOTORMODEL)->join(MOTORCOMPANY, 'LEFT')->on(MOTORMODEL.'.motor_mid', '=', MOTORCOMPANY.'.motor_id')->order_by('model_name','ASC')->limit($offset)->limit($val)
			->execute()
			->as_array();
		  return $result;		   
	}
		
	public function model_motordetails($uid)
	{
			$result =DB::select('motor_id','model_name','base_fare','min_km','min_fare','cancellation_fare','below_above_km','below_km','above_km','waiting_time','minutes_fare','taxi_min_speed','night_charge','night_timing_from','night_timing_to','night_fare','evening_charge','evening_timing_from','evening_timing_to','evening_fare','model_size','taxi_speed','model_id','motor_name')->from(MOTORMODEL)->join(MOTORCOMPANY, 'LEFT')->on(MOTORMODEL.'.motor_mid', '=', MOTORCOMPANY.'.motor_id')
			->where(MOTORMODEL.'.model_id','=',$uid)
			->execute()
			->as_array();

		  return $result;
		   
	}
	
	public function model_faredetails($uid)
	{
		$company_id = $_SESSION['company_id'];	
		$query = "select * from ".COMPANY_MODEL_FARE." where ".COMPANY_MODEL_FARE.".company_cid = '$company_id' and ".COMPANY_MODEL_FARE.".company_model_fare_id= '$uid' ORDER BY `company_model_fare_id` ASC";
		
		$result = Db::query(Database::SELECT, $query)
	 	->execute()			
		->as_array();

		  return $result;
		   
	}
	
	// Check Whether Motor details is Already Exist or Not
	public static function motordetails()
	{

		$result = DB::select('motor_id','motor_name')->from(MOTORCOMPANY)->where('motor_status','=','A')->order_by('motor_name','asc')
			->execute()
			->as_array();

		  return $result;
	}
	
	// Check Whether Manage Field details is Already Exist or Not
	public static function managefield_details($uid)
	{

		$result = DB::select()->from(MANAGEFIELD)->where(MANAGEFIELD.'.field_id','=',$uid)
			->execute()
			->as_array();
			
		  return $result;
	}
	
		// Check Whether Manage Field details is Already Exist or Not
	public static function managetaxi_details($uid)
	{
		$result =DB::select('taxi_no','taxi_company','taxi_model','taxi_owner_name','taxi_manufacturer','taxi_colour','taxi_motor_expire_date','taxi_insurance_number','taxi_insurance_expire_date_time','taxi_pco_licence_number','taxi_pco_licence_expire_date','taxi_speed','taxi_min_speed','max_luggage','taxi_fare_km','taxi_country','taxi_state','taxi_city','taxi_image',TAXI.'.taxi_id','taxi_sliderimage','taxi_serializeimage')->from(TAXI)->join(ADDFIELD, 'LEFT')->on(TAXI.'.taxi_id', '=', ADDFIELD.'.taxi_id')
			->where(TAXI.'.taxi_id','=',$uid)
			->execute()
			->as_array();
			
		  return $result;
	}
	
	/** for updating taxi image **/
	public static function edittaxi_image($image,$uid)
	{
		$sql="select taxi_image from ".TAXI." where taxi_id='$uid'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results)){
				$id1 = $results[0]['taxi_image'];
				if(file_exists($id1)){
					unlink($id1);
				}
			}
			
			$result = DB::update(TAXI)->set(array('taxi_image' => $image))
					->where('taxi_id','=',$uid)
					->execute();
			return $result;
			
	}
	//To Edit Taxi Functionalities 
	public static function edittaxi($post,$form_array,$uid,$files)
	{ 
		//print_r($post); exit;
		$field ='';
		$field_value ='';
		$array_fieldvalue = '';
		$merge_field ='';
		$merge_value = '';
		$nexti = '';
		$add_count = $post['add_count'];
		
		if(isset($files['updateimage']['name']) && $files['updateimage']['type'] !='')
		{  	
			
			foreach($files['updateimage']['name'] as $key => $value)
			{
				$nexti = $key;
				$file_array = array();

				if(isset($files['updateimage']['name'][$key]) && $files['updateimage']['name'][$key]!='')
				{
					$file_array['name'] =  $files['updateimage']['name'][$key];
					$file_array['type'] = $files['updateimage']['type'][$key];
					$file_array['tmp_name'] = $files['updateimage']['tmp_name'][$key];
					$file_array['error'] = $files['updateimage']['error'][$key];
					$image_name = $uid.'_'.$key; 
					$filename = Upload::save($file_array,$image_name,DOCROOT.TAXI_IMG_IMGPATH);
					$logo_image = Image::factory($filename);
					$path1=DOCROOT.TAXI_IMG_IMGPATH;
					$path=$image_name;
					Commonfunction::multipleimageresize($logo_image,TAXI_IMG_WIDTH, TAXI_IMG_HEIGHT,$path1,$image_name,90);
					
				}

			}
		
		/*	for($i=0;$i<$count;$i++)
			{   $nexti = $i;
				$file_array = array();
				if(isset($files['updateimage']['name'][$i]) && $files['updateimage']['name'][$i]!='')
				{
					$file_array['name'] =  $files['updateimage']['name'][$i];
					$file_array['type'] = $files['updateimage']['type'][$i];
					$file_array['tmp_name'] = $files['updateimage']['tmp_name'][$i];
					$file_array['error'] = $files['updateimage']['error'][$i];
					$image_name = $uid.'_'.$i; 
					$filename = Upload::save($file_array,$image_name,DOCROOT.TAXI_IMG_IMGPATH);
					$logo_image = Image::factory($filename);
					$path1=DOCROOT.TAXI_IMG_IMGPATH;
					$path=$image_name;
					Commonfunction::multipleimageresize($logo_image,TAXI_IMG_WIDTH, TAXI_IMG_HEIGHT,$path1,$image_name,90);
					
				}
				
			}
		*/	
			
					
		}

		$taxi_arrcount = array();
		
		if(isset($files['size']['name']) && $files['size']['type'] !='')
		{  
			$count = count($files['size']['name']);
			
			$z = 0;

			for($j=0;$j<$count;$j++)
			{
				$file_array = array();
				
				if($files['size']['name'][$j]!='')
				{
					$z++;
					if($nexti =='')
					{
						$nexti = $add_count;
					}
					else
					{
						$nexti++;
					}
					$file_array['name'] =  $files['size']['name'][$j];
					$file_array['type'] = $files['size']['type'][$j];
					$file_array['tmp_name'] = $files['size']['tmp_name'][$j];
					$file_array['error'] = $files['size']['error'][$j];
					$image_name = $uid.'_'.$nexti; 
					$taxi_arrcount[] = $nexti;
					$filename = Upload::save($file_array,$image_name,DOCROOT.TAXI_IMG_IMGPATH);
					$logo_image = Image::factory($filename);
					$path1=DOCROOT.TAXI_IMG_IMGPATH;
					$path=$image_name;
					Commonfunction::multipleimageresize($logo_image,TAXI_IMG_WIDTH, TAXI_IMG_HEIGHT,$path1,$image_name,90);
				}
				
			}
			
			$image_serialize = array();
				
			$updatequery = " UPDATE ". TAXI ." SET taxi_sliderimage=taxi_sliderimage+$z wHERE taxi_id = '$uid'";						
			$updateresult = Db::query(Database::UPDATE, $updatequery)
   			 ->execute();


			$array_query = " select taxi_serializeimage from " . TAXI .  " where taxi_id=$uid";
	 		$array_result = Db::query(Database::SELECT, $array_query)
				   			 ->execute()
							 ->as_array();

			$image_serialize = unserialize($array_result[0]['taxi_serializeimage']);

			if(is_array($image_serialize) > 0)
			{
			$update_array = array_merge($image_serialize, $taxi_arrcount);	
			}
			else
			{
			$update_array = $taxi_arrcount;	
			}

			$update_arrimage = serialize($update_array);

			$updatequery = " UPDATE ". TAXI ." SET taxi_serializeimage='$update_arrimage' wHERE taxi_id = '$uid' ";	

			$updateresult = Db::query(Database::UPDATE, $updatequery)
			->execute();
   			    			 
   			 
		}

			

			
		foreach($form_array as $value)
		{	
			$field .= $value.",";
		}

		$field = substr($field, 0, -1);		

		foreach($form_array as $key => $value)
		{	
			$field_value .= "'".$post[$value]."',";
			$array_fieldvalue .=$post[$value].",";
		}


		$field_value = substr($field_value, 0, -1);
		$post['taxi_type'] = 1;
		
			$result = DB::update(TAXI)->set(array('taxi_no'=>$post['taxi_no'],'taxi_type'=>$post['taxi_type'],'taxi_model'=>$post['taxi_model'],'taxi_country'=>$post['country'],'taxi_state'=>$post['state'],'taxi_city'=>$post['city'],'taxi_capacity'=>'','taxi_speed'=>$post['taxi_speed'],'max_luggage'=>$post['minimum_luggage'],'taxi_fare_km'=>$post['taxi_fare_km'],'taxi_company'=>$post['company_name'],'taxi_owner_name'=>$post['taxi_owner_name'],'taxi_manufacturer'=>$post['taxi_manufacturer'],'taxi_colour'=>$post['taxi_colour'],'taxi_motor_expire_date'=>$post['taxi_motor_expire_date']/*,'taxi_pco_licence_number'=>$post['taxi_pco_licence_number'],'taxi_pco_licence_expire_date'=>$post['taxi_pco_licence_expire_date']*/,'taxi_insurance_number'=>$post['taxi_insurance_number'],'taxi_insurance_expire_date_time'=>$post['taxi_insurance_expire_date'],'taxi_min_speed'=>$post['taxi_min_speed']))
			->where('taxi_id','=',$uid)
			->execute();
			//$post['taxi_capacity']

		$array_fieldvalue = substr($array_fieldvalue, 0, -1);
		if($field !='')
		{
			$array_field = explode(',',$field);
		}
		else
		{
			$array_field = array();
		}
		if($array_fieldvalue !='')
		{
			$array_value = explode(',',$array_fieldvalue);
		}
		else
		{
			$array_value = array();
		}
		if(count($array_field) > 0 )
		{
			$update_array = array_combine($array_field,$array_value);
			
			$result = DB::update(ADDFIELD)->set($update_array)
			->where('taxi_id','=',$uid)
			->execute(); 

		}
					

		if($result)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	public static function check_taxino($name,$uid)
	{
		// Check if the username already exists in the database

		$result = DB::select('taxi_no')->from(TAXI)->where('taxi_no','=',$name)->where('taxi_id','!=',$uid)
			->execute()
			->as_array();	
		
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}

	// To Check Motorname is Already Available or Not
	public static function checkmodelname($name,$motorid,$uid)
	{

		// Check if the username already exists in the database

		$result = DB::select('model_name')->from(MOTORMODEL)->where('model_name','=',$name)->where('motor_mid','=',$motorid)->where('model_id','!=',$uid)
		
			->execute()
			->as_array();
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}
	
	public static function driver_details($uid)
	{
			$result = DB::select('id','name','lastname','email','org_password','org_password','gender','dob','driver_license_id','phone','login_country','login_state','login_city','company_id','booking_limit','address','profile_picture','user_type')->from(PEOPLE)->where('id','=',$uid)->where('user_type', '=', 'D')
			->execute()
			->as_array();

			return $result;	
	}
	
	public static function manager_details($uid)
	{
			$result = DB::select('name','lastname','email','phone','address','company_id','login_country','login_state','login_city','password','org_password')->from(PEOPLE)->where('id','=',$uid)->where('user_type', '=', 'M')
			->execute()
			->as_array();

			return $result;	
	}
	
	public static function moderator_details($uid)
	{
			$result = DB::select('name','lastname','email','phone','address','login_country','user_type','login_state','login_city')->from(PEOPLE)->where('id','=',$uid)->where_open()->where('user_type', '=', 'S')->or_where('user_type', '=', 'DA')->where_close()->execute()->as_array();
			return $result;
	}
	
	//to get driver's licence and insurance details from driver info table
	public static function driver_info_details($driver_id)
	{
		$result = DB::select('driver_license_expire_date','driver_pco_license_number','driver_pco_license_expire_date','driver_insurance_number','driver_insurance_expire_date','driver_national_insurance_number','driver_national_insurance_expire_date')->from(DRIVER_INFO)->where('driver_id','=',$driver_id)->execute()->as_array();
		return $result;	
	}
	
		// To Check User Name is Already Available or Not
	public static function checkusername($name,$uid)
	{
		// Check if the username already exists in the database
			
		$result = DB::select('username')->from(PEOPLE)->where('username','=',$name)->where('id','!=',$uid)
			->execute()
			->as_array();			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}	

	//To update Edit Driver Functionalities 
	public static function edit_driver($post,$uid)
	{

		//$username = Html::chars($post['username']);
		$password = Html::chars(md5($post['password']));
		$dob = Commonfunction::ensureDatabaseFormat($post['dob'],3);
		$driver_license_expire_date = isset($post['driver_license_expire_date'])?Commonfunction::ensureDatabaseFormat($post['driver_license_expire_date'],3):'';
		/*$driver_pco_license_expire_date = Commonfunction::ensureDatabaseFormat($post['driver_pco_license_expire_date'],3);*/
		$driver_insurance_expire_date = isset($post['driver_insurance_expire_date'])?Commonfunction::ensureDatabaseFormat($post['driver_insurance_expire_date'],3):'';
		/*$driver_national_insurance_expire_date = Commonfunction::ensureDatabaseFormat($post['driver_national_insurance_expire_date'],3);*/
		$driver_twilio_number = $post['driver_twilio_number'];

				$result=DB::update(PEOPLE)->set(array('name'=>$post['firstname'],'address'=>$post['address'],'login_country'=>$post['country'],'login_state'=>$post['state'],'login_city'=>$post['city'],'lastname'=>$post['lastname'],'gender'=>$post['gender'],'dob'=>$dob,'email'=>$post['email'],'password'=>$password,'org_password'=>$post['password'],'driver_license_id'=>$post['driver_license_id'],'country_code'=>$post['telephone_code'],'phone'=>$post['phone'],'company_id'=>$post['company_name'],'booking_limit'=>$post['booking_limit']))
				->where('id','=',$uid)
				->execute();
			
			$cityresult = DB::select()->from(CITY)->where('city_id','=',$post['city'])->where('city_status','=','A')->order_by('city_name','ASC')
					->execute()
					->as_array();

			/*
			$address = $cityresult[0]['city_name'];
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
				$latitude = $json->results[0]->geometry->location->lat;
				$longitude = $json->results[0]->geometry->location->lng;
			}
			else
			{
				$latitude = LOCATION_LATI;
				$longitude = LOCATION_LONG;
			}
			*/

			$latitude = LOCATION_LATI;
			$longitude = LOCATION_LONG;

			$result1=DB::update(DRIVER)->set(array('latitude'=>$latitude,'longitude'=>$longitude))
				->where('driver_id','=',$uid)
				->execute();

			$driver_info_exists = DB::select("driver_id")->from(DRIVER_INFO)->where('driver_id','=',$uid)->execute()->as_array();
			if(count($driver_info_exists) > 0){
				//to update driver info details like licence number and insurance details etc..
				/*
				$driver_info = DB::update(DRIVER_INFO)->set(array('driver_license_expire_date'=>$driver_license_expire_date,'driver_pco_license_number'=>$post['driver_pco_license_number'],'driver_pco_license_expire_date'=>$driver_pco_license_expire_date,'driver_insurance_number'=>$post['driver_insurance_number'],'driver_insurance_expire_date'=>$driver_insurance_expire_date,'driver_national_insurance_number'=>$post['driver_national_insurance_number'],'driver_national_insurance_expire_date'=>$driver_national_insurance_expire_date,'driver_twilio_number'=>$driver_twilio_number))->where('driver_id','=',$uid)->execute();	
				*/

				$driver_info = DB::update(DRIVER_INFO)->set(array('driver_license_expire_date'=>$driver_license_expire_date,'driver_insurance_number'=>$post['driver_insurance_number'],'driver_insurance_expire_date'=>$driver_insurance_expire_date,'driver_twilio_number'=>$driver_twilio_number))->where('driver_id','=',$uid)->execute();	

			}else{
				//driver additional information insert
				/*
				$driver_info = DB::insert(DRIVER_INFO, array('driver_id','driver_license_expire_date','driver_pco_license_number','driver_pco_license_expire_date','driver_insurance_number', 'driver_insurance_expire_date','driver_national_insurance_number','driver_national_insurance_expire_date','driver_twilio_number'=>))->values(array($uid,$driver_license_expire_date,$post['driver_pco_license_number'],$driver_pco_license_expire_date,$post['driver_insurance_number'],$driver_insurance_expire_date,$post['driver_national_insurance_number'],$driver_national_insurance_expire_date,$driver_twilio_number))->execute();
				*/


				$driver_info = DB::insert(DRIVER_INFO, array('driver_id','driver_license_expire_date','driver_insurance_number','driver_insurance_expire_date','driver_twilio_number'))->values(array($uid,$driver_license_expire_date,$post['driver_insurance_number'],$driver_insurance_expire_date,$driver_twilio_number))->execute();
			}	


		//the condition to get the response 1 if driver or driver info detials changed
		if($result || $driver_info)
		{
			if(isset($post['brand_type']) && $post['brand_type'] == "S"){
				$driver_info = DB::update(COMPANY)->set(array('company_name'=>$post['firstname'],'company_address'=>$post['address'],'company_country'=>$post['country'],'company_state'=>$post['state'],'company_city'=>$post['city'],'time_zone'=>$_SESSION['timezone']))->where('userid','=',$uid)->execute();
			}
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public static function package_details($uid)
	{
			$result = DB::select()->from(PACKAGE)->where('package_id','=',$uid)
			->execute()
			->as_array();

			return $result;	
	}

	// To Check Company Name is Already Available or Not
	public static function checkpackagename($packagename,$uid)
	{
		// Check if the username already exists in the database

		$result = DB::select('package_name')->from(PACKAGE)->where('package_name','=',$packagename)->where('package_id','!=',$uid)
			->execute()
			->as_array();	
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}

	public static function edit_package($post,$uid)
	{
		//print_r($post);exit;
		
		if(isset($post['driver_tracking']))
		{
			$driver_tracking = 'S';
		}
		else
		{
			$driver_tracking = 'N';
		}

			$result=DB::update(PACKAGE)->set(array('package_name'=>$post['package_name'],'package_description'=>$post['package_description'],'no_of_taxi'=>$post['no_of_taxi'],'no_of_driver'=>$post['no_of_driver'],'package_price'=>$post['package_price'],'days_expire'=>$post['days_expire'],'package_type'=>$post['package_type'],'driver_tracking'=>$driver_tracking))
				->where('package_id','=',$uid)
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

	// Check Whether Country details is Already Exist or Not
	public static function country_details($uid)
	{
		$result = DB::select('country_id','country_name','iso_country_code','telephone_code','currency_code','currency_symbol')->from(COUNTRY)->where('country_id','=',$uid)->execute()->as_array();
		return $result;
	}

	public function validate_editcountry($arr,$uid) 
	{

		return Validation::factory($arr)       
		
			->rule('country_name', 'not_empty')
			//->rule('country_name', 'alpha_dash')
			->rule('country_name', 'Model_Edit::check_reg_countryname', array(':value'))
			->rule('country_name', 'min_length', array(':value', '2'))
			->rule('country_name', 'max_length', array(':value', '30'))
			->rule('country_name', 'Model_Edit::checkcountryname', array(':value',$uid))
			
			->rule('iso_country_code', 'not_empty')
			->rule('iso_country_code', 'min_length', array(':value', '2'))
			->rule('iso_country_code', 'max_length', array(':value', '5'))
			->rule('iso_country_code', 'Model_Edit::checkisocountrycode', array(':value',$uid))
			
			->rule('telephone_code', 'not_empty')
			->rule('telephone_code', 'min_length', array(':value', '2'))
			->rule('telephone_code', 'max_length', array(':value', '5'))
			
			->rule('currency_code', 'not_empty')
			->rule('currency_code', 'min_length', array(':value', '2'))
			->rule('currency_code', 'max_length', array(':value', '5'))
			
			->rule('currency_symbol', 'not_empty');
	}

	public function validate_edit_template($arr,$uid) 
	{

		return Validation::factory($arr)       
		
			->rule('sms_description', 'not_empty');
			
	}

	public static function checkcountryname($name,$uid)
	{
		$result = DB::select('country_name')->from(COUNTRY)->where('country_name','=',$name)->where('country_id','!=',$uid)
			->execute()
			->as_array();
				
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}

	public static function checkfaqtitle($faq,$fid)
	{
		$result = DB::select('faq_title')->from(PASSENGERS_FAQ)->where('faq_title','=',$faq)->where('faq_id','!=',$fid)
			->execute()
			->as_array();
				
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}

	public static function checkisocountrycode($iso_country_code,$uid)
	{
		$result = DB::select('iso_country_code')->from(COUNTRY)->where('iso_country_code','=',$iso_country_code)->where('country_id','!=',$uid)
			->execute()
			->as_array();
				
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}

	public static function editcountry($uid,$post)
	{
		
		$result=DB::update(COUNTRY)->set(array('country_name'=>$post['country_name'],'iso_country_code'=>$post['iso_country_code'],'telephone_code'=>$post['telephone_code'],'currency_code'=>$post['currency_code'],'currency_symbol'=>$post['currency_symbol']))
				->where('country_id','=',$uid)
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

	public static function edittemplate($uid,$post)
	{
		$result=DB::update(SMS_TEMPLATE)->set(array('sms_description'=>$post['sms_description'],'status'=>$post['template_status']))
				->where('sms_id','=',$uid)
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

	public static function sms_template($uid)
	{
		return $result = DB::select('sms_id','sms_title','sms_info','sms_description','status')->from(SMS_TEMPLATE)->where('sms_id','=',$uid)
				->execute()
				->as_array();
	}

	public static function countrydetails()
	{

		$result = DB::select('country_id','country_name')->from(COUNTRY)->where('country_status','=','A')->order_by('country_name','asc')
			->execute()
			->as_array();

		  return $result;
	}

	public static function country_details_new()
	{
		$result = DB::select('country_id','country_name')->from(COUNTRY)
				->join(STATE)->on(STATE.'.state_countryid', '=', COUNTRY.'.country_id')
				->where('country_status','=','A')
				->where('state_status','=','A')
				->order_by('country_name','asc')
				->group_by(COUNTRY.'.country_id')
				->execute()
				->as_array();

		return $result;
	}
	
	public function city_countrydetails($uid)
	{
			$result =DB::select(COUNTRY.'.country_id',CITY.'.city_name',CITY.'.city_countryid',CITY.'.city_stateid',CITY.'.zipcode',CITY.'.city_model_fare')->from(CITY)
			->join(STATE, 'LEFT')->on(CITY.'.city_stateid', '=', STATE.'.state_id')
			->join(COUNTRY, 'LEFT')->on(CITY.'.city_countryid', '=', COUNTRY.'.country_id')
			->where(CITY.'.city_id','=',$uid)
			->execute()
			->as_array();

		  return $result;
		   
	}
	
	public static function state_details()
	{
		
		$result = DB::select('state_id','state_name')->from(STATE)->where('state_status','=','A')->order_by('state_name','asc')
			->execute()
			->as_array();

		  return $result;
	}
	
	
	public function validate_editcity($arr,$uid) 
	{

		return Validation::factory($arr)       
		
			->rule('city_name', 'not_empty')
			//->rule('city_name', 'alpha_dash')
			->rule('city_name', 'Model_Edit::check_reg_city_name', array(':value'))
			->rule('city_name', 'min_length', array(':value', '2'))
			->rule('city_name', 'max_length', array(':value', '30'))
			->rule('city_name', 'Model_Edit::checkcityname', array(':value',$arr['state_name'],$arr['country_name'],$uid))

			->rule('zipcode', 'not_empty')
			
			->rule('state_name', 'not_empty')
			->rule('country_name', 'not_empty')

			->rule('city_model_fare', 'not_empty')
			->rule('city_model_fare', 'numeric')
			->rule('city_model_fare', 'decimal', array(':value', '2'));


	}
	// To Check Motorname is Already Available or Not
	public static function checkcityname($name,$stateid,$countryid,$uid)
	{

		// Check if the username already exists in the database

		$result = DB::select('city_name')->from(CITY)->where('city_name','=',$name)->where('city_stateid','=',$stateid)->where('city_countryid','=',$countryid)->where('city_id','!=',$uid)


		
			->execute()
			->as_array();
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}
	public static function editcity($uid,$post)
	{
		$result=DB::update(CITY)->set(array('city_name'=>$post['city_name'],'zipcode'=>$post['zipcode'],'city_stateid' =>$post['state_name'],'city_countryid' =>$post['country_name'],'city_model_fare' =>$post['city_model_fare']))
				->where('city_id','=',$uid)
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
	public function state_countrydetails($uid)
	{
		$result =DB::select(COUNTRY.'.country_name',STATE.'.state_countryid',STATE.'.state_name')->from(STATE)->join(COUNTRY, 'LEFT')->on(STATE.'.state_countryid', '=', COUNTRY.'.country_id')
			->where(STATE.'.state_id','=',$uid)
			->execute()
			->as_array();
		return $result;
	}
	public function validate_editstate($arr,$uid) 
	{
		return Validation::factory($arr)
			->rule('state_name', 'not_empty')
			//->rule('state_name', 'alpha_dash')
			->rule('state_name', 'Model_Edit::check_reg_state_name', array(':value'))
			->rule('state_name', 'min_length', array(':value', '2'))
			->rule('state_name', 'max_length', array(':value', '30'))
			->rule('state_name', 'Model_Edit::checkstatename', array(':value',$arr['country_name'],$uid))
			->rule('country_name', 'not_empty');
	}
	

	public static function checkstatename($name,$countryid,$uid)
	{
	

		// Check if the state_name already exists in the database
		$result = DB::select('state_name')->from(STATE)->where('state_name','=',$name)->where('state_countryid','=',$countryid)->where('state_id','!=',$uid)
			->execute()
			->as_array();
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;
			}
	}
	
	public static function editstate($uid,$post)
	{
		$result=DB::update(STATE)->set(array('state_name'=>$post['state_name'],'state_countryid' =>$post['country_name']))
				->where('state_id','=',$uid)
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
	
	public function validate_editmanager($arr,$uid) 
	{
		$validate = Validation::factory($arr)       
		
           		->rule('firstname', 'not_empty')
           		//->rule('firstname', 'alpha_dash') 
			->rule('firstname', 'min_length', array(':value', '4'))
			->rule('firstname', 'max_length', array(':value', '30'))
			->rule('firstname','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
            		->rule('lastname', 'not_empty')
			->rule('lastname','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
            		//->rule('lastname', 'alpha_dash') 
			//->rule('lastname', 'min_length', array(':value', '4'))
			//->rule('lastname', 'max_length', array(':value', '30'))

			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('email', 'Model_Edit::checkemail', array(':value',$uid))
			
			->rule('phone', 'not_empty')
			//->rule('phone', 'numeric')
			->rule('phone', 'min_length', array(':value', '7'))
			->rule('phone', 'max_length', array(':value', '20'))
			//->rule('phone', 'phone', array(':value'))
			->rule('phone', 'contact_phone', array(':value'))
			->rule('phone', 'Model_Edit::checkphone', array(':value',$uid))
            
			->rule('company_name', 'not_empty')
			->rule('company_name','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'));
			//->rule('company_name', 'alpha_dash') 
			//->rule('company_name', 'Model_Edit::checkmanagercompany', array(':value',$arr['city'],$arr['state'],$arr['country'],$uid))

			if($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'S') {			

			$validate->rule('password', 'not_empty')
			->rule('password', 'min_length', array(':value', '6'))
			->rule('password', 'max_length', array(':value', '20'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'));

			}

			$validate->rule('address', 'not_empty')
			
			->rule('country', 'not_empty')
			
			->rule('state', 'not_empty')
			
			->rule('city', 'not_empty');

			return $validate;
			

	}

	public function validate_editadmin($arr,$uid) 
	{
		return Validation::factory($arr)       
		
           		->rule('firstname', 'not_empty')
			->rule('firstname', 'min_length', array(':value', '4'))
			->rule('firstname', 'max_length', array(':value', '30'))
			->rule('firstname','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))

            		->rule('lastname', 'not_empty')
			->rule('lastname','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))

			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('email', 'Model_Edit::checkemail', array(':value',$uid))
			
			->rule('phone', 'not_empty')
			//->rule('phone', 'numeric')
			->rule('phone', 'min_length', array(':value', '7'))
			->rule('phone', 'max_length', array(':value', '20'))
			//->rule('phone', 'phone', array(':value'))
			->rule('phone', 'contact_phone', array(':value'))
			->rule('phone', 'Model_Edit::checkphone', array(':value',$uid))
        		
			->rule('address', 'not_empty')
			
			->rule('country', 'not_empty');
			

	}	
	public static function checkmanagercompany($companyname,$cityid,$stateid,$countryid,$uid)
	{



		$result = DB::select()->from(PEOPLE)->where('company_id','=',$companyname)->where('login_country','=',$countryid)->where('login_state','=',$stateid)->where('login_city','=',$cityid)->where('user_type','=','M')->where('id','!=',$uid)
			->execute()
			->as_array();
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}
		
	//To update Edit Manager Functionalities 
	public static function edit_manager($post,$uid)
	{

		//$username = Html::chars($post['username']);

		if(isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'S') )
		{
			$password = Html::chars(md5($post['password']));

			$result=DB::update(PEOPLE)->set(array('name'=>$post['firstname'],'address'=>$post['address'],'login_country'=>$post['country'],'login_state'=>$post['state'],'login_city'=>$post['city'],'lastname'=>$post['lastname'],'email'=>$post['email'],'country_code'=>$post['telephone_code'],'phone'=>$post['phone'],'company_id'=>$post['company_name'],'password'=>$password,'org_password'=>$post['password']))
				->where('id','=',$uid)
				->execute();	
		}
		else
		{
			$result=DB::update(PEOPLE)->set(array('name'=>$post['firstname'],'address'=>$post['address'],'login_country'=>$post['country'],'login_state'=>$post['state'],'login_city'=>$post['city'],'lastname'=>$post['lastname'],'email'=>$post['email'],'country_code'=>$post['telephone_code'],'phone'=>$post['phone'],'company_id'=>$post['company_name']))
				->where('id','=',$uid)
				->execute();	
		}

					
				
	
		if($result)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public static function edit_admin($post,$uid)
	{
		
	$result=DB::update(PEOPLE)->set(array('name'=>$post['firstname'],'address'=>$post['address'],'login_country'=>$post['country'],'lastname'=>$post['lastname'],'email'=>$post['email'],'country_code'=>$post['telephone_code'],'phone'=>$post['phone'],'user_type'=>$post['user_type']))
				->where('id','=',$uid)
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
		
	public function validate_editassigntaxi($arr,$uid) 
	{
		return Validation::factory($arr)       
		
			->rule('company_name', 'not_empty')
			->rule('country', 'not_empty')
			->rule('state', 'not_empty')
			->rule('city', 'not_empty')
			->rule('driver', 'not_empty')
			->rule('startdate', 'not_empty')
			->rule('enddate', 'not_empty')
			->rule('enddate', 'Model_Edit::checkassigntaxi', array(':value',$arr,$uid))
			->rule('taxi', 'not_empty');
			

	}
	
	public static function checkassigntaxi($enddate,$post,$uid)
	{
		$country_id = $post['country'];
		$state_id = $post['state'];
		$city_id = $post['city'];
		$company_name = $post['company_name'];
		$driver_id = $post['driver'];
		$taxi_id = $post['taxi'];
		$startdate = $post['startdate']; 
		$enddate = $post['enddate']; 
		/*$country_where = ($country_id) ? " AND mapping_countryid = '$country_id'" : "";
		$city_where = ($city_id) ? " AND mapping_cityid = '$city_id'" : "";
		$company_where = ($company_name) ? " AND mapping_companyid = '$company_name'" : "";
		*/
		$driver_where ='';
		$taxi_where = '';
		$cond_where = '';
		$startdate_where = '';
		$date_where = '';
		$enddate_where = '';
		
		if($driver_id && $taxi_id)
		{
			$cond_where = "AND (mapping_driverid ='$driver_id' or mapping_taxiid ='$taxi_id')";
		}
		else
		{
			if($driver_id)
			{
				$driver_where = " AND mapping_driverid = '$driver_id'";	
			}
			if($taxi_id)
			{
				$taxi_where = " AND mapping_taxiid = '$taxi_id'";	
			}
			$cond_where = $driver_where.$taxi_where;
		}
		
		if($startdate && $enddate)
		{
				$date_where = " AND ( ( '$startdate' between mapping_startdate and  mapping_enddate ) or ( '$enddate' between mapping_startdate and  mapping_enddate) )";	
		}
		else
		{
			if($startdate)
			{
				$startdate_where = " AND '$startdate'  between mapping_startdate and  mapping_enddate ";	
			}
			if($enddate)
			{
				$enddate_where = " AND '$enddate'  between mapping_startdate  and  mapping_enddate ";	
			}
			
			$date_where = $startdate_where.$enddate_where;
		}

		

		
		$query = " select * from " . TAXIMAPPING . " left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id  left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id where 1=1  and mapping_status='A' $cond_where  $date_where and mapping_id != '$uid' order by mapping_startdate DESC ";
		

 		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();
			 
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}
	
	public static function assigntaxi_details($uid)
	{
	
			$result = DB::select()->from(TAXIMAPPING)
				->join(COMPANY, 'LEFT')->on(TAXIMAPPING.'.mapping_companyid', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(TAXIMAPPING.'.mapping_countryid', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXIMAPPING.'.mapping_stateid', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXIMAPPING.'.mapping_cityid', '=', CITY.'.city_id')
				->join(PEOPLE, 'LEFT')->on(TAXIMAPPING.'.mapping_driverid', '=', PEOPLE.'.id')
				->where(TAXIMAPPING.'.mapping_id','=',$uid)				
				->order_by('mapping_startdate','desc')
				->execute()
				->as_array();

		  return $result;
		  
	}	
	//To update Edit Manager Functionalities 
	public static function edit_assigntaxi($post,$uid)
	{

		$edit_company = Model::factory('edit');					
		
			$result=DB::update(TAXIMAPPING)->set(array('mapping_driverid'=>$post['driver'],'mapping_taxiid'=>$post['taxi'],'mapping_companyid'=>$post['company_name'],'mapping_countryid'=>$post['country'],'mapping_stateid'=>$post['state'],'mapping_cityid'=>$post['city'],'mapping_startdate'=>$post['startdate'],'mapping_enddate'=>$post['enddate']))
				->where('mapping_id','=',$uid)
				->execute();	
				
	
		if($result)
		{
			$resultquery=DB::select(PEOPLE.'.email',PEOPLE.'.name',TAXI.'.taxi_no',TAXI.'.max_luggage',TAXI.'.taxi_speed',MOTORMODEL.'.model_name')->from(PEOPLE)->join(TAXIMAPPING,'LEFT')->on(TAXIMAPPING.'.mapping_driverid','=',PEOPLE.'.id')->join(TAXI,'LEFT')->on(TAXIMAPPING.'.mapping_taxiid','=',TAXI.'.taxi_id')->join(MOTORMODEL,'LEFT')->on(TAXI.'.taxi_model','=',MOTORMODEL.'.model_id')
				->where('id','=',$post['driver'])
				->execute()->as_array();
			return $resultquery;
		}
		else
		{
			return 0;
		}
	}

	public function get_Taxino($taxi_id)
	{
		$result = DB::select()->from(TAXI)->where('taxi_id','=',$taxi_id)
			->execute()
			->as_array();
		
		 	return $result[0]['taxi_no'];
	
	}
	
	public function unavailability_details($id='')
	{
		$query = "SELECT * FROM `".UNAVAILABILITY."` as unable Join `".PEOPLE."` as p ON unable.u_driverid=p.id  Join `".COMPANY."` as c ON unable.u_companyid=c.cid WHERE unable.u_id=$id";
			
		$rs = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return $rs;		
	}
		
	public function validate_unavailabledriver($arr,$uid) 
	{
		return Validation::factory($arr)       

			->rule('reason', 'not_empty')
			->rule('startdate', 'not_empty')
			->rule('enddate', 'not_empty')
			->rule('enddate',  'Model_Manage::date_diff', array('value', $arr['startdate']))
			->rule('enddate', 'Model_Edit::checkunavailable', array(':value',$arr,$uid));
	}
	
	public static function date_diff($enddate,$startdate)
	{
		if($startdate > $enddate)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	
	}
	
	public static function checkunavailable($enddate,$post,$uid)
	{

		$driver_id = $post['driver_id'];
		$taxi_id = $post['taxi_id'];
		$reason = $post['reason'];
		$startdate = $post['startdate']; 
		$enddate = $post['enddate']; 
		$driver_where ='';
		$startdate_where = '';
		$date_where = '';
		$enddate_where = '';
		
		if($startdate && $enddate)
		{
				$date_where = " AND ( ( '$startdate' between u_startdate and  u_enddate ) or ( '$enddate' between u_startdate and  u_enddate) )";		
		}
		else
		{
			if($startdate)
			{
				$startdate_where = " AND '$startdate'  between u_startdate and  u_enddate ";	
			}
			if($enddate)
			{
				$enddate_where = " AND '$enddate'  between u_startdate  and  u_enddate ";	
			}
			
			$date_where = $startdate_where.$enddate_where;
		}

		

		
		$query = " select * from " . UNAVAILABILITY . " where 1=1  and u_driverid='$driver_id' and taxi_id='$taxi_id' $date_where and u_id!='$uid' ";

 		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();
			 
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}	

	public function edit_unavailabledriver($post,$uid)
	{
	
			$result=DB::update(UNAVAILABILITY)->set(array('u_reason'=>$post['reason'],'u_startdate'=>$post['startdate'],'u_enddate'=>$post['enddate']))
				->where('u_id','=',$uid)
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

	public function getunavailabledriverlist($driver_id='',$taxi_id='')
	{
		$query = "SELECT * FROM `".UNAVAILABILITY."` as unable Join `".PEOPLE."` as p ON unable.u_driverid=p.id  Join `".COMPANY."` as c ON unable.u_companyid=c.cid WHERE unable.u_driverid='$driver_id' and unable.u_taxiid='$taxi_id'";
			
		$rs = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return count($rs);		
	}

	public function get_unavailabledriverlist($driver_id='',$taxi_id='',$offset='',$val='')
	{
		$query = "SELECT * FROM `".UNAVAILABILITY."` as unable Join `".PEOPLE."` as p ON unable.u_driverid=p.id  Join `".COMPANY."` as c ON unable.u_companyid=c.cid WHERE unable.u_driverid='$driver_id' and unable.u_taxiid='$taxi_id' limit $val offset $offset";
			
		$rs = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return $rs;		
	}
	
	//Validate the edit menu
	public function validate_editmenu($arr,$mid) 
	{

		return Validation::factory($arr)       
		
			->rule('menu_name', 'not_empty')
			->rule('menu_name', 'min_length', array(':value', '2'))
			->rule('menu_name', 'max_length', array(':value', '30'))
			
			->rule('slug','not_empty');
	}
	
	//selected manu 
	public function get_menu($mid)
	{
		$result =DB::select()->from(MENU)
		->where(MENU.'.menu_id','=',$mid)
		->execute()
		->as_array();

	  return $result;
		   
	}
	
	public function update_menu($mid,$post)
	{
		$status = $post['status_posts'];
		if($status=='Publish'){
			$status='P';
		}else if($status=='Unpublish'){
			$status='U';
		}		
		
		$result=DB::update(MENU)->set(array('menu_name'=>$post['menu_name'],'menu_link'=>$post['slug'],'status_post' =>$status))
				->where('menu_id','=',$mid)
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
	
	//Check the menu already exists
	public function menu_name_exits($mid,$post)
	{
		  $result =DB::select()->from(MENU)
			->where(MENU.'.menu_id','!=',$mid)
			->where(MENU.'.menu_name','=',$post['menu_name'])
			->execute()
			->as_array();
			
		  if($result)
		  {
		  	return 1;
		  }

	}
	
	//String Convert the URL format
	function string_convertoUrl($str_val) 
	{
		// small fonts
		$strurl = strtolower($str_val);
		// change spaces to -
		$strurl = str_replace(' ', '-', $strurl);
		// delete all other characters to -
		$strurl = preg_replace('|[^0-9a-z\-\/+]|', '', $strurl);
		// delete too much - if near
		$strurl = preg_replace('/[\-]+/', '-', $strurl);

		// trim -
		$strurl = trim($strurl, '-');

		return $strurl;
	}
	
	//Validate the edit menu
	public function validate_editmile($arr,$mid) 
	{

		return Validation::factory($arr)       
		
			->rule('mile', 'not_empty')
			->rule('mile', 'digit')
			->rule('mile', 'max_length', array(':value', '30'));
	}
	
	//selected mile 
	public function get_mile($mid)
	{
		$result =DB::select()->from(MILES)
		->where(MILES.'.id','=',$mid)
		->execute()
		->as_array();

	  return $result;
		   
	}
	
	public function update_mile($mid,$post)
	{
		
		$result=DB::update(MILES)->set(array('mile_name'=>$post['mile']))
				->where('id','=',$mid)
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
	
	//Check the mile already exists
	public function mile_name_exits($mid,$post)
	{
		  $result =DB::select()->from(MILES)
			->where(MILES.'.id','!=',$mid)
			->where(MILES.'.mile_name','=',$post['mile'])
			->execute()
			->as_array();
			
		  if($result)
		  {
		  	return 1;
		  }

	}
	
	public static function check_reg_countryname($name)
	{
		if(preg_match('/^[A-Za-z ]+$/',$name))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function check_reg_state_name($name)
	{
		if(preg_match('/^[A-Za-z ]+$/',$name))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function check_reg_city_name($name)
	{
		if(preg_match('/^[A-Za-z ]+$/',$name))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function check_base_fare($base_fare)
	{
		if(preg_match('/^\d+(\.\d+)*$/',$base_fare))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function check_fare_zero($base_fare)
	{
		if($base_fare=="0")
		{		
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public static function check_min_km($min_km)
	{
		if(preg_match('/^\d+(\.\d+)*$/',$min_km))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function check_below_and_above_km($below_and_above_km,$min_km)
	{
		if(preg_match('/^\d+(\.\d+)*$/',$below_and_above_km) && $below_and_above_km > $min_km)
		{		
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function check_min_fare($min_fare)
	{
		if(preg_match('/^\d+(\.\d+)*$/',$min_fare))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function check_cancellation_fare($cancellation_fare)
	{
		if(preg_match('/^\d+(\.\d+)*$/',$cancellation_fare))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}
	public static function check_minute_fare($minute_fare)
	{
		if(preg_match('/^\d+(\.\d+)*$/',$minute_fare))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function check_below_km($below_km)
	{
		if(preg_match('/^\d+(\.\d+)*$/',$below_km))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function check_above_km($above_km)
	{
		if(preg_match('/^\d+(\.\d+)*$/',$above_km))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function check_waiting_time($waiting_time)
	{
		if(preg_match('/^\d+(\.\d+)*$/',$waiting_time))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function check_night_fare($night_fare)
	{
		if(preg_match('/^\d+(\.\d+)*$/',$night_fare))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function check_booking_limit($night_fare)
	{
		if(preg_match('/^\d+(\\d+)*$/',$night_fare))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function bannerdetails($uid)
	{
		$company_id = $_SESSION['company_id'];	
		$query = "select * from ".COMPANY_CMS." where company_id = $company_id and id= $uid ORDER BY `id` ASC";
		
		$result = Db::query(Database::SELECT, $query)
	 	->execute()			
		->as_array();

		  return $result;
	}
	
	public function update_banner_image($image,$id)
        {
		
		$sql="select banner_image from ".COMPANY_CMS." where id='$id'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['banner_image'])){
				$id1 = DOCROOT.BANNER_IMGPATH.$results[0]['banner_image'];
				if(file_exists($id1)){
					$id1 = BANNER_IMGPATH.$results[0]['banner_image'];
					unlink($id1);
				}
			}
			
		if($id > 0){
		if(isset($image))
		{
			$query = array('banner_image' => $image,'type' =>'2');
		}

		$result =  DB::update(COMPANY_CMS)->set($query)
					->where('id', '=' ,$id)
					->execute();
		}else{
			$result   = DB::insert(CMS)
					->columns(array('banner_image','type','status'))
					->values(array($image,'2','1'))
					->execute();
		}
		if($result)
		{ 
			return 1;
		}
		else
		{
			return 0;		
		}
        }
        
    public function update_banner_details($tag,$image_tag,$id)
    {
		$company_id = $_SESSION['company_id'];
		$query = array('alt_tags' =>$tag,'image_tag' => $image_tag);
		$rs   = DB::update(COMPANY_CMS)->set($query)
					->where('company_id','=',$company_id)
					->where('id','=',$id)
					->execute();
		if($rs)
		{ 
			return 1;
		}
		else
		{
			return 0;		
		}
		
    }
      
      	public function get_faqdetails($fid)
		{
			$query = "select faq_title,faq_details from ".PASSENGERS_FAQ." where faq_id = $fid";
			$result = Db::query(Database::SELECT, $query)->execute()->as_array();
			return $result;
		}
		
	public function validate_editfaq($arr,$fid) 
	{

		return Validation::factory($arr)       
		
			->rule('faq_title', 'not_empty')
			->rule('faq_title', 'Model_Edit::checkfaqtitle', array(':value',$fid))
			->rule('faq_details', 'not_empty');

	}

	public function editfaq($post,$fid)
	{	

		$result=DB::update(PASSENGERS_FAQ)->set(array('faq_title'=>$post['faq_title'],'faq_details'=>$post['faq_details'],'status' =>'A'))
				->where('faq_id','=',$fid)
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
		// To Check Currency code is equal to Currency symbol
		public static function checksite_currency($currencysymbol,$currencycode)
		{
			// To Check Currency code is equal to Currency symbol
			$result = DB::select('country_id')->from(COUNTRY)->where('currency_code','=',$currencycode)->where('currency_symbol','=',$currencysymbol)
			->execute()
			->as_array();

			if(count($result) > 0)
			{
				return true;
			}
			else
			{
				return false;		
			}
		}

	public function get_company_payment_settings($company_user_id)
	{	
		/*$query2 = "SELECT * FROM ".COMPANY_PAYMENT_MODULES." order by pay_mod_id asc";

		$result =DB::select()->from(COMPANY_PAYMENT_MODULES)
				->where('company_user_id','=',$company_user_id)
				->execute()
				->as_array();

		return $result;*/
	}

	function findcompany_timezone($company_cid)
	{
			$rs = DB::select('time_zone')->from(COMPANY)
				->where('cid','=',$company_cid)
				->execute()
				->as_array();	
			if(count($rs)>0)
			return $rs[0]['time_zone'];
			else
			return 0;		
	}

	public function get_promocodedetails($id)
	{
		$query="SELECT distinct passenger_promoid,promocode,promo_discount,start_date,expire_date,promo_limit,promocode_type FROM ".PASSENGER_PROMO." group by promocode  HAVING `passenger_promoid` = $id";
		$result =  Db::query(Database::SELECT, $query)
						->execute()
						->as_array();
		return $result;	
	}

	public function validate_editpromocode($arr,$id) 
	{

		$validation=Validation::factory($arr)       

			->rule('promo_limit', 'not_empty')
			//->rule('model_name', 'alpha_dash')
			->rule('promo_limit', 'numeric');
			
		    return $validation;

	}

	 public function get_promocode_users($promocode)
	 {
		/* $result = DB::select()->from(PASSENGER_PROMO)->where(PASSENGER_PROMO.'.promocode', '=', $promocode)
			->join(PASSENGERS)->on(PASSENGERS.'.id','=',PASSENGER_PROMO.'.passenger_id')
			->where(PASSENGERS.'.user_status','=','A')
			->execute();
			->as_array(); */

		$query = "select name,email from ".PASSENGER_PROMO." join ".PASSENGERS." where ".PASSENGER_PROMO.".promocode = '$promocode' and ".PASSENGERS.".user_status = 'A' and FIND_IN_SET(".PASSENGERS.".id, ".PASSENGER_PROMO.".passenger_id)";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	 }

	 public function editpromocode($post,$promocode)
	 {
		$result = DB::update(PASSENGER_PROMO)->set(array('start_date'=>$post['start_date'],'expire_date'=>$post['expire_date'],'promo_limit'=>$post['promo_limit']))
			 ->where('promocode','=',$promocode)
			 ->execute();
		 return 1;
	 }

	// Check driver licence Id is Already Exist or Not
		public static function checklicenceId($value,$uid)
		{
			$result = DB::select(array( DB::expr('COUNT(id)' ), 'total'))->from(PEOPLE)->where('driver_license_id','=',$value)->where('id','!=',$uid)->execute()->get('total');
			if($result > 0)
			{ 
				return false;
			}
			else
			{
				return true;		
			}
		}	
		//pco licence number already exist
		public static function checkpcolicenceNo($value,$uid)
		{
			$result = DB::select(array( DB::expr('COUNT('.PEOPLE.'.id)' ), 'total'))->from(PEOPLE)->join(DRIVER_INFO,'LEFT')->on(DRIVER_INFO.'.driver_id','=',PEOPLE.'.id')->where(DRIVER_INFO.'.driver_pco_license_number','=',$value)->where(PEOPLE.'.id','!=',$uid)->execute()->get('total');
			if($result > 0)
			{ 
				return false;
			}
			else
			{
				return true;		
			}
		}
		//insurance number already exist
		public static function checkinsuranceNo($value,$uid)
		{
			$result = DB::select(array( DB::expr('COUNT('.PEOPLE.'.id)' ), 'total'))->from(PEOPLE)->join(DRIVER_INFO,'LEFT')->on(DRIVER_INFO.'.driver_id','=',PEOPLE.'.id')->where(DRIVER_INFO.'.driver_insurance_number','=',$value)->where(PEOPLE.'.id','!=',$uid)->execute()->get('total');
			if($result > 0)
			{ 
				return false;
			}
			else
			{
				return true;		
			}
		}
		//national insurance number already exist
		public static function checkNationalinsuranceNo($value,$uid)
		{
			$result = DB::select(array( DB::expr('COUNT('.PEOPLE.'.id)' ), 'total'))->from(PEOPLE)->join(DRIVER_INFO,'LEFT')->on(DRIVER_INFO.'.driver_id','=',PEOPLE.'.id')->where(DRIVER_INFO.'.driver_national_insurance_number','=',$value)->where(PEOPLE.'.id','!=',$uid)->execute()->get('total');
			if($result > 0)
			{ 
				return false;
			}
			else
			{
				return true;		
			}
		}
		// To Check taxi insurance number is Already Available or Not
		public static function check_taxinsurance_number($number,$uid)
		{	
			$result = DB::select(array(DB::expr('COUNT(taxi_id)' ), 'total'))->from(TAXI)->where('taxi_insurance_number','=',$number)->where('taxi_id','!=',$uid)->execute()->get('total');			
				if($result > 0)
				{
					return false;
				}
				else
				{
					return true;		
				}
		}
		// To Check taxi pco licence number is Already Available or Not
		public static function check_taxipco_number($number,$uid)
		{	
			$result = DB::select(array(DB::expr('COUNT(taxi_id)' ), 'total'))->from(TAXI)->where('taxi_pco_licence_number','=',$number)->where('taxi_id','!=',$uid)->execute()->get('total');			
				if($result > 0)
				{
					return false;
				}
				else
				{
					return true;		
				}
		}
		
	public static function driver_details_edit($uid)
	{
		$result = DB::select(PEOPLE.'.id','name','lastname','email','org_password','org_password','gender','dob','driver_license_id','phone','login_country', 'login_state','login_city','company_id','booking_limit','address','profile_picture','user_type','driver_license_expire_date', 'driver_pco_license_number','driver_pco_license_expire_date','driver_insurance_number','driver_insurance_expire_date', 'driver_national_insurance_number','driver_national_insurance_expire_date',COMPANYINFO.'.company_brand_type', 'driver_twilio_number')
				->from(PEOPLE)
				->join(DRIVER_INFO,'LEFT')->on(DRIVER_INFO.'.driver_id','=',PEOPLE.'.id')
				->join(COMPANYINFO,'LEFT')->on(COMPANYINFO.'.company_cid','=',PEOPLE.'.company_id')
				->where(PEOPLE.'.id','=',$uid)->where(PEOPLE.'.user_type', '=', 'D')
				->execute()
				->as_array();
		return $result;	
	}
	
	//Get driver coupon details by couponid
	public function get_couponcodedetails($id)
	{
		$query = "SELECT distinct * FROM ".DRIVERS_COUPON." group by coupon_code  HAVING `coupon_id` = $id";
		$result =  Db::query(Database::SELECT, $query)
						->execute()
						->as_array();						 
		return $result;	
	}
	
	//Validate edit coupon form values
	public function edit_coupon_validate( $arr ) 
	{
		return Validation::factory( $arr )
						->rule( 'amount','not_empty')
						->rule( 'coupon_name','not_empty')
						->rule( 'amount', 'regex', array( ':value', "/^[0-9]+$/" ) )
						//->rule( 'amount', 'max_length', array( ':value',5 ) )
						->rule( 'start_date','not_empty')
						->rule( 'expire_date','not_empty');
	}
	
	//Edit driver coupon details 
	public function edit_driver_coupon( $values , $coupon_id)
	{
		$result = DB::update(DRIVERS_COUPON)->set(array('start_date' => $values['start_date'], 'expiry_date' => $values['expire_date'],'amount' => $values['amount'],'coupon_name' => $values['coupon_name']))
			 ->where('coupon_id','=',$coupon_id)
			 ->execute();
		 return 1;						
	}
	
	
	//Get Passenger coupon details by couponid
	public function passenger_get_couponcodedetails($id)
	{
		$query = "SELECT distinct * FROM ".PASSENGERS_COUPON." group by coupon_code  HAVING `coupon_id` = $id";
		$result =  Db::query(Database::SELECT, $query)
						->execute()
						->as_array();						 
		return $result;	
	}
	
	//Validate Passenger edit coupon form values
	public function passenger_edit_coupon_validate( $arr ) 
	{
		return Validation::factory( $arr )
						->rule( 'amount','not_empty')
						->rule( 'coupon_name','not_empty')
						->rule( 'amount', 'regex', array( ':value', "/^[0-9]+$/" ) )
						//->rule( 'amount', 'max_length', array( ':value',5 ) )
						->rule( 'start_date','not_empty')
						->rule( 'expire_date','not_empty');
	}
	
	//Edit Passenger coupon details 
	public function edit_passenger_coupon( $values , $coupon_id)
	{
		$result = DB::update(PASSENGERS_COUPON)->set(array('start_date' => $values['start_date'], 'expiry_date' => $values['expire_date'],'amount' => $values['amount'],'coupon_name' => $values['coupon_name']))
			 ->where('coupon_id','=',$coupon_id)
			 ->execute();
		 return 1;						
	}
		
}
