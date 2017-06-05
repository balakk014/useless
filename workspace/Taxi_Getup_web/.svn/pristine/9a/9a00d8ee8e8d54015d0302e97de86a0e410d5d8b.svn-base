<?php defined('SYSPATH') OR die('No Direct Script Access');

/******************************************

* Contains Users module details

* @Package: ConnectTaxi

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************/

Class Model_Add extends Model
{
	public function __construct()
	{	
		$this->session = Session::instance();	
		$this->username = $this->session->get("username");
		$this->admin_username = $this->session->get("username");
		$this->admin_userid = $this->session->get("userid");
		$this->admin_email = $this->session->get("email");
		$this->user_admin_type = $this->session->get("user_type");
		$this->currentdate=Commonfunction::getCurrentTimeStamp();

	}
	
	/**Validating for Add company**/
	public function validate_addcompany($arr,$files_value_array) 
	{
		return Validation::factory($arr)       
		
           		->rule('firstname', 'not_empty')
           		//->rule('firstname', 'alpha_dash')
			->rule('firstname', 'min_length', array(':value', '4'))
			->rule('firstname', 'max_length', array(':value', '30'))
			->rule('firstname','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
            		->rule('lastname', 'not_empty')
			
            		//->rule('lastname', 'alpha_dash')
			->rule('lastname', 'min_length', array(':value', '1'))
			->rule('lastname','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
			//->rule('lastname', 'max_length', array(':value', '30'))

			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('email', 'Model_Add::checkemail', array(':value'))

			->rule('password', 'not_empty')
			->rule('password', 'min_length', array(':value', '4'))
			->rule('password', 'max_length', array(':value', '20'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))

			->rule('repassword', 'not_empty')
			->rule('repassword', 'min_length', array(':value', '4'))
			->rule('repassword', 'max_length', array(':value', '20'))
			->rule('repassword','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('repassword',  'matches', array(':validation', 'password', 'repassword'))
									
			->rule('phone', 'not_empty')
			//->rule('phone', 'numeric')
			->rule('phone', 'min_length', array(':value', '7'))
			->rule('phone', 'max_length', array(':value', '20'))
			//->rule('phone', 'phone', array(':value'))
			->rule('phone', 'contact_phone', array(':value'))
			->rule('phone', 'Model_Add::checkphone', array(':value'))
			
			->rule('company_name', 'not_empty')
			->rule('company_name', 'min_length', array(':value', '4'))
			->rule('company_name', 'max_length', array(':value', '30'))
			->rule('company_name','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
			->rule('company_name', 'Model_Add::checkcompany', array(':value',$arr['country'],$arr['state'],$arr['city']))

			//->rule('domain_name', 'not_empty')
			//->rule('domain_name', 'min_length', array(':value', '4'))
			//->rule('domain_name', 'max_length', array(':value', '60'))
			//->rule('domain_name', 'alpha_numeric', array(':value','/^[0-9]{1,}/'))
			//->rule('domain_name', 'Model_Add::checkdomain', array(':value'))
			
			/* ->rule('paypal_api_username','not_empty')
			    ->rule('paypal_api_password','not_empty')
			    ->rule('paypal_api_signature','not_empty')
			    ->rule('payment_method','not_empty') */
			
			->rule('address', 'not_empty')
			
			->rule('country', 'not_empty')
			
			->rule('state', 'not_empty')
			
			->rule('city', 'not_empty')
						
			//->rule('company_address', 'not_empty')
			->rule('currency_code', 'not_empty')
			->rule('currency_symbol', 'not_empty')
			->rule('taxi_image', 'Upload::not_empty',array($files_value_array['taxi_image']))
			->rule('taxi_image', 'Upload::valid',array($files_value_array['taxi_image']))
			->rule('time_zone', 'not_empty');

	}

	/**Validating for Add company**/
	public function validate_addmoderator($arr) 
	{
		return Validation::factory($arr)       
		
           		->rule('name', 'not_empty')
			->rule('name', 'min_length', array(':value', '4'))
			->rule('name', 'max_length', array(':value', '30'))
			->rule('name','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))

			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('email', 'Model_Add::checkemail', array(':value'))
			
			->rule('sales_person_email', 'not_empty')
			->rule('sales_person_email', 'email')     
			->rule('sales_person_email', 'max_length', array(':value', '50'))
			
			->rule('phone', 'not_empty')
			//->rule('phone', 'numeric')
			->rule('phone', 'min_length', array(':value', '7'))
			->rule('phone', 'max_length', array(':value', '20'))
			//->rule('phone', 'phone', array(':value'))
			->rule('phone', 'contact_phone', array(':value'))
			->rule('phone', 'Model_Add::checkphone', array(':value'))

			->rule('company_name', 'not_empty')
			->rule('company_name', 'min_length', array(':value', '4'))
			->rule('company_name', 'max_length', array(':value', '30'))
			->rule('company_name','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))

			->rule('domain_name', 'not_empty')
			->rule('domain_name', 'min_length', array(':value', '4'))
			->rule('domain_name', 'max_length', array(':value', '60'))
			->rule('domain_name', 'alpha_numeric', array(':value','/^[0-9]{1,}/'))
			->rule('domain_name', 'Model_Add::checkdomain', array(':value'))

			->rule('no_of_taxi', 'not_empty')
			->rule('no_of_taxi', 'numeric')
			->rule('message', 'not_empty')
			->rule('time_zone', 'not_empty');

	}

	/**Validating for Add Taxi**/
	public function validate_addtaxi($arr,$form_values,$files_value_array="") 
	{
		$rule = Validation::factory($arr)
		->rule('taxi_no', 'not_empty')
		->rule('taxi_no', 'min_length', array(':value', '4'))
		->rule('taxi_no', 'max_length', array(':value', '30'))
		//->rule('taxi_no', 'alpha_numeric', array(':value','/^[0-9]{1,}/'))
		->rule('taxi_no', 'regex', array(':value','/^[a-z0-9A-Z -]++$/iD'))
		->rule('taxi_no', 'Model_Add::check_taxino', array(':value'))
		
		->rule('taxi_type', 'not_empty')
		->rule('taxi_model', 'not_empty')
		->rule('taxi_owner_name', 'not_empty')
		->rule('taxi_owner_name','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
		->rule('taxi_manufacturer', 'not_empty')

		->rule('taxi_colour', 'not_empty')
		->rule('taxi_color','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
		->rule('taxi_motor_expire_date', 'not_empty')
		->rule('taxi_insurance_number', 'not_empty')
		->rule('taxi_insurance_number', 'Model_Add::check_taxinsurance_number', array(':value'))
		->rule('taxi_insurance_expire_date', 'not_empty')
		/*->rule('taxi_pco_licence_number', 'not_empty')
		->rule('taxi_pco_licence_number', 'Model_Add::check_taxipco_number', array(':value'))
		->rule('taxi_pco_licence_expire_date', 'not_empty')*/
		->rule('country', 'not_empty')		
		->rule('state', 'not_empty')
		->rule('city', 'not_empty')
		->rule('company_name', 'not_empty')
		/*->rule('taxi_min_speed', 'not_empty')
		->rule('taxi_capacity', 'not_empty')
		->rule('taxi_capacity', 'min_length', array(':value', '1'))
		->rule('taxi_capacity', 'max_length', array(':value', '20'))
		->rule('taxi_capacity', 'digit', array(':value','/^[0-9]{1,}/'))*/

		->rule('taxi_fare_km', 'not_empty')
		->rule('taxi_fare_km', 'min_length', array(':value', '1'))
		->rule('taxi_fare_km', 'max_length', array(':value', '20'))
		->rule('taxi_fare_km', 'digit', array(':value','/^[0-9]{1,}/'));
		
		/*->rule('file', 'Upload::not_empty',array($files_value_array['taxi_image']))
		->rule('file', 'Upload::type', array($files_value_array['taxi_image'], array('jpg','jpeg', 'png', 'gif')))
		->rule('file', 'Upload::size', array($files_value_array['taxi_image'],'2M'));*/
		
		foreach($arr as $key => $value)
		{	if(in_array($value,$form_values)) 
			{
				$rule = $rule->rule($value, 'not_empty');
			}
		}
				
		return $rule;

	}

	/**Validating for Add Taxi**/
	public function validate_addfield($arr) 
	{
		
		$rule = Validation::factory($arr)       
		
		->rule('field_labelname', 'not_empty')
		->rule('field_labelname', 'min_length', array(':value', '2'))
		->rule('field_labelname', 'max_length', array(':value', '20'))


		->rule('field_name', 'not_empty')
		->rule('field_name', 'min_length', array(':value', '2'))
		->rule('field_name', 'max_length', array(':value', '20'))
		->rule('field_name', 'small_letters', array(':value'))
		->rule('field_name', 'Model_Add::checkfieldname', array(':value'))

		->rule('field_type', 'not_empty');
		
		if($arr['field_type'] !='Textbox')
		{
		//$rule = $rule->rule('field_value', 'not_empty');
		}
		
		return $rule;

	}
	

	public static function checkfieldname($name)
	{	
		// Check if the username already exists in the database

		$result = DB::select('field_name')->from(MANAGEFIELD)->where('field_name','=',$name)
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
	public function validate_addmotor($arr) 
	{
		return Validation::factory($arr)       
		
			->rule('companyname', 'not_empty')
			//->rule('country_name', 'alpha_dash')
			->rule('companyname', 'min_length', array(':value', '2'))
			->rule('companyname', 'max_length', array(':value', '30'))
			->rule('companyname', 'Model_Add::checkmotorname', array(':value'));

	}

	/**Validating for Add Motor**/
	public function validate_addmodel($arr) 
	{

		$validation=Validation::factory($arr)       

			->rule('model_name', 'not_empty')
			//->rule('model_name', 'alpha_dash')
			->rule('model_name', 'min_length', array(':value', '2'))
			->rule('model_name', 'max_length', array(':value', '30'))
			->rule('model_name', 'Model_Add::checkmodelname', array(':value',$arr['companyname']))
			->rule('model_name','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))

			->rule('model_size', 'not_empty')
			->rule('model_size', 'Model_Edit::check_fare_zero', array(':value',$arr['model_size']))
			
			->rule('companyname', 'not_empty')
			->rule('companyname','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))

			->rule('waiting_time', 'not_empty')			
			->rule('waiting_time', 'Model_Add::check_waiting_time', array(':value',$arr['waiting_time']))
						
			->rule('base_fare', 'not_empty')
			->rule('base_fare', 'Model_Add::check_base_fare', array(':value',$arr['base_fare']))
			
			->rule('min_km', 'not_empty')
			->rule('min_km', 'Model_Add::check_min_km', array(':value',$arr['min_km']))
			
			->rule('min_fare', 'not_empty')
			->rule('min_fare', 'Model_Add::check_min_fare', array(':value',$arr['min_fare']))
						
			->rule('cancellation_fare', 'not_empty')
			->rule('cancellation_fare', 'Model_Add::check_cancellation_fare', array(':value',$arr['cancellation_fare']))
			
			->rule('minutes_fare', 'not_empty')
			->rule('minutes_fare', 'Model_Edit::check_minute_fare', array(':value',$arr['minutes_fare']))
			
			->rule('below_and_above_km', 'not_empty')
			->rule('below_and_above_km', 'Model_Add::check_below_and_above_km', array(':value',$arr['min_km']))
						
			->rule('below_km', 'not_empty')
			->rule('below_km', 'Model_Add::check_below_km', array(':value',$arr['below_km']))
						
			->rule('above_km', 'not_empty')	
			->rule('above_km', 'Model_Add::check_above_km', array(':value',$arr['above_km']))		
			
			->rule('night_charge', 'not_empty');

			if(Arr::get($arr,'night_charge')==1)
			{
				//echo "dsf";exit;
			   $validation->rule('night_timing_from', 'not_empty')
						 ->rule('night_timing_to', 'not_empty')
						 ->rule('night_fare', 'not_empty')
						 ->rule('night_fare', 'Model_Add::check_night_fare', array(':value',$arr['night_fare']))
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
	
	/**Validating for Add Motor**/
	public function validate_addfare($arr) 
	{

		$validation=Validation::factory($arr)       

			->rule('model_name', 'not_empty')
			->rule('model_name','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
			->rule('model_size', 'not_empty')
			->rule('model_size', 'Model_Edit::check_fare_zero', array(':value',$arr['model_size']))
					
			->rule('waiting_time', 'not_empty')			
			->rule('waiting_time', 'Model_Add::check_waiting_time', array(':value',$arr['waiting_time']))
						
			->rule('base_fare', 'not_empty')
			->rule('base_fare', 'Model_Add::check_base_fare', array(':value',$arr['base_fare']))
			
			->rule('min_km', 'not_empty')
			->rule('min_km', 'Model_Add::check_min_km', array(':value',$arr['min_km']))
			
			->rule('min_fare', 'not_empty')
			->rule('min_fare', 'Model_Add::check_min_fare', array(':value',$arr['min_fare']))
						
			->rule('cancellation_fare', 'not_empty')
			->rule('cancellation_fare', 'Model_Add::check_cancellation_fare', array(':value',$arr['cancellation_fare']))
			
			->rule('below_and_above_km', 'not_empty')
			->rule('below_and_above_km', 'Model_Add::check_below_and_above_km', array(':value',$arr['min_km']))
			->rule('below_and_above_km', 'Model_Add::check_value_zero', array(':value',$arr['below_and_above_km']))
						
			->rule('below_km', 'not_empty')
			->rule('below_km', 'Model_Add::check_below_km', array(':value',$arr['below_km']))
						
			->rule('above_km', 'not_empty')	
			->rule('above_km', 'Model_Add::check_above_km', array(':value',$arr['above_km']))		
			
			->rule('minutes_fare', 'not_empty')
			->rule('minutes_fare', 'Model_Add::check_minute_fare', array(':value',$arr['minutes_fare']))
			
			->rule('night_charge', 'not_empty')
			->rule('evening_charge', 'not_empty');

			if(Arr::get($arr,'night_charge')==1)
			{
				//echo "dsf";exit;
			   $validation->rule('night_timing_from', 'not_empty')
						 ->rule('night_timing_to', 'not_empty')
						 ->rule('night_fare', 'not_empty')
						 ->rule('night_fare', 'Model_Add::check_night_fare', array(':value',$arr['night_fare']))
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
		
	/**Validating for Add company**/
	public function validate_adddriver($arr) 
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
			//->rule('phone', 'max_length', array(':value', '10'))
			//->rule('phone', 'phone', array(':value'))
			->rule('phone', 'contact_phone', array(':value'))
			->rule('phone', 'Model_Add::checkphone', array(':value'))
			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('email', 'Model_Add::checkemail', array(':value'))
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
			->rule('driver_license_id', 'Model_Add::checklicenceId', array(':value'))
			->rule('driver_license_expire_date', 'not_empty')
			/*->rule('driver_pco_license_number', 'not_empty')
			->rule('driver_pco_license_number', 'max_length', array(':value', '30'))
			->rule('driver_pco_license_number', 'Model_Add::checkpcolicenceNo', array(':value'))
			->rule('driver_pco_license_expire_date', 'not_empty')
			->rule('driver_insurance_number', 'not_empty')*/
			->rule('driver_insurance_number', 'max_length', array(':value', '30'))
			->rule('driver_insurance_number', 'Model_Add::checkinsuranceNo', array(':value'))
			/*->rule('driver_insurance_expire_date', 'not_empty')
			->rule('driver_national_insurance_number', 'not_empty')
			->rule('driver_national_insurance_number', 'max_length', array(':value', '30'))
			->rule('driver_national_insurance_number', 'Model_Add::checkNationalinsuranceNo', array(':value'))
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
			->rule('photo', 'Upload::not_empty',array($arr['photo']))
			->rule('photo', 'Upload::type', array(':value', array('jpeg','jpg','png','gif')));
		return $validate;
	}


	//To Add company Functionalities 
	public function addcompany($post,$path)
	{
		//print_r($post);exit;
		$image_data= getimagesize($path['taxi_image']['tmp_name']);
		if(isset($image_data['mime']))
		{
			
			$password = Html::chars(md5($post['password']));
			
			$upgrade_package = $post['upgrade_package'];
			$upgrade_packid = $post['pack'];

			$add_company = Model::factory('add');

			$current_date = convert_timezone('now',$post['time_zone']);

			$ses_userId = $this->admin_userid;
			$result = DB::insert(PEOPLE, array('name','address','lastname','email','paypal_account','country_code','phone','password','org_password','login_country','login_state','login_city','created_date','user_type','status','user_createdby'))
						->values(array($post['firstname'],$post['address'],$post['lastname'],$post['email'],'',$post['telephone_code'],$post['phone'],$password,$post['password'],$post['country'],$post['state'],$post['city'],$current_date,'C',ACTIVE,$ses_userId))
						->execute();

			$company_userid = $result[0];

			$in_company = DB::insert(COMPANY, array('company_name','company_address','company_country','company_state','company_city','userid','time_zone','header_bgcolor','menu_color','mouseover_color'))
						->values(array($post['company_name'],$post['company_address'],$post['country'],$post['state'],$post['city'],$company_userid,$post['time_zone'],'#FFFFFF','#000000','#FFD800'))
						->execute();
				
			$reg_companyid = $in_company[0];

			/** ADD for Payment Models for comapny **/
			/*foreach($post['payid'] as $k=>$id)
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
					->values(array($reg_companyid,$company_userid,$post['payid'][$k],$post['paymodname'][$k],$post['paymodimage'][$k],$paystatus,$default))
					->execute();
			}*/

			/** ADD for Default Payment gateway **/
			$payment =   DB::select()->from('payment_gateway_types')
				->where('payment_gateway_status', '=' ,1)
				->execute()
				->as_array();
				$i=1;
				foreach($payment as $gateway){
					($i==1)?$default=1:$default=0;
					($i==1)?$payment_user='nandhu_1337947987_biz_api1.gmail.com':$payment_user='';
					($i==1)?$payment_pass='1337948040':$payment_pass='';
					($i==1)?$payment_sign='A0YqGlJEML24al4qg2LnV2U.g2ThAfXD37NEiWIVcgjl1pxlygg-XaVs':$payment_sign='';
					$pay_result =  DB::insert(PAYMENT_GATEWAYS,array('company_id','payment_gateway_id','payment_gatway','paypal_api_username','paypal_api_password','paypal_api_signature','payment_status','default_payment_gateway','currency_code','currency_symbol','payment_method'))
					->values(array($reg_companyid,$gateway['payment_gateway_id'],$gateway['payment_gateway_name'],$payment_user,$payment_pass,$payment_sign,'A',$default,'USD','$','T')) 
					->execute();
					$i++;
				} 
			/** ADD for Default Payment gateway **/
			/** ADD for Payment Models for comapny **/

			/** ADD for subdomain Data Base Creation **

				$config = Kohana::$config->load('database');
				$a = $config->get('default');
			        $DatabaseName = $post['domain_name']."_taxi_service";
				$DatabaseUsername = $a['connection']['username'];
				$DatabasePassword = $a['connection']['password'];
				$DB_Host = $a['connection']['hostname'];

				$createDB = Database::instance()->query(NULL, "CREATE DATABASE ".$DatabaseName);

				if(count($createDB) == 1){

				$config['company'] = array
					(
						'type'       => 'mysql',
						'connection' => array(
							
							'hostname'   => $DB_Host,
							'database'   => $DatabaseName,
							'username'   => $DatabaseUsername,
							'password'   => $DatabasePassword,
							'persistent' => FALSE,
						),
						'table_prefix' => '',
						'charset'      => 'utf8',
						'caching'      => FALSE,
						'profiling'    => TRUE,
					);

					$db = Database::instance('custom', $config['company']);
					include APPPATH."vendor/company_query.php";
					/** Update for company payment  module **
					foreach($post['payid'] as $k=>$id){  
		
					if($id == $post['default'][0]){
							$default =1;
					}
					else
					{
						$default =0;
					}

					if(in_array($id,$post['paymodstatus']))
					{
						$paystatus =1;
					}
					else
					{
						$paystatus =0;
					}
	
				$db->query(NULL,"UPDATE payment_modules SET pay_mod_active='$paystatus', pay_mod_default='$default' WHERE pay_mod_id='$id';" );
					}
					unset($db);

	
                            }

			/** ADD for subdomain Data Base Creation **/

			$insert_tdispatchalogrithm = DB::insert(TBLALGORITHM, array('labelname','alg_created_by','alg_company_id','active','hide_customer','hide_droplocation','hide_fare'))->values(array(1,$company_userid,$reg_companyid,1,0,0,0))->execute();

						   $key="";
						   $charset = "abcdefghijklmnopqrstuvwxyz"; 
						   $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
						   $charset .= "0123456789"; 
						   $length = mt_rand (30, 35); 
						   for ($i=0; $i<$length; $i++) 
						   $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];					
						
			 DB::insert(COMPANYINFO, array('company_cid','company_domain','company_app_name','company_currency','company_currency_format','company_paypal_username','company_paypal_password','company_paypal_signature','payment_method','company_notification_settings','company_api_key'))
						->values(array($reg_companyid,$post['domain_name'],$post['company_name'],$post['currency_symbol'],$post['currency_code'],'','','','','60',$key))
						->execute();	
//$post['paypal_api_username'],$post['paypal_api_password'],$post['paypal_api_signature'],$post['payment_method']

					/* $cms=DB::insert(COMPANY_CMS, array('company_id','menu_name','title','content','page_url','type'));
					//$banner_image=DB::insert(COMPANY_CMS, array('company_id','image_tag','alt_tags','banner_image','type'));
					//$pages=array('About us','Features','Terms and Conditions','Tutorials','Contact us');
					//$page_url=array('about-us','features','termsconditions','tutorials','contact-us');
					$pages=array('About us','Privacy policy','Servicing for Excellence','Terms and Conditions','Help');
					$page_url=array('aboutus','privacypolicy','service-area','termsconditions','help');
					 for($i=0;$i<5;$i++)
					 {
						$cms->values(array($reg_companyid,$pages[$i],$pages[$i],$pages[$i],$page_url[$i],1));
						if($i==0)
						{
							$srcfile=DOCROOT.PUBLIC_IMAGES_FOLDER.'header_banner_bg.jpg';
							$dstfile=DOCROOT.PUBLIC_UPLOAD_BANNER_FOLDER.$reg_companyid.'_header_banner_bg.jpg';
							copy($srcfile, $dstfile);
							$image_name=$reg_companyid.'_header_banner_bg.jpg';
							$banner_image=DB::insert(COMPANY_CMS, array('company_id','image_tag','alt_tags','banner_image','type'));
							$banner_image->values(array($reg_companyid,"image1","image1",$image_name,2));
							$banner_image->execute();
						}
					 }
					 $cms->execute();	*/
					 //$banner_image->execute();
												
					// Company Image	
					
					
					/* image */
				
					$image_name = $company_userid; 
					$filename = Upload::save($path['taxi_image'],$image_name,DOCROOT.COMPANY_IMG_IMGPATH);
					$logo_image = Image::factory($filename);
					$path1=DOCROOT.COMPANY_IMG_IMGPATH;
					$path=$image_name;
					Commonfunction::multipleimageresize($logo_image,COMPANY_IMG_WIDTH, COMPANY_IMG_HEIGHT,$path1,$image_name,90);

			
					
					// End Company Image
			
				
			$update_people = DB::update(PEOPLE)->set(array('company_id' => $reg_companyid))->where('id', '=', $company_userid)
				->execute();
			if($upgrade_package =='D')
			{
			$get_packagedetails = $add_company->payment_packagedetails($upgrade_packid);
			$package_name = $get_packagedetails[0]['package_name']; 
			$no_of_taxi = $get_packagedetails[0]['no_of_taxi']; 
			$no_of_driver = $get_packagedetails[0]['no_of_driver']; 
			$days = $get_packagedetails[0]['days_expire'];   
			$amount = $get_packagedetails[0]['package_price'];   
			$package_type = $get_packagedetails[0]['package_type'];   

			$userid = $_SESSION['userid'];	
			
			// Convert Time
					
			$current_time = convert_timezone('now',$post['time_zone']);

			
				// Convert Time

				if($days > 0)
				{
					$expirydate = Commonfunction::getExpiryTimeStamp($current_time,$days);
				}
				else
				{
					$expirydate = $current_time;
				}
			
					$result = DB::insert(PACKAGE_REPORT, array('upgrade_companyid','upgrade_packageid','upgrade_packagename','upgrade_no_taxi','upgrade_no_driver','upgrade_expirydate','upgrade_ack','upgrade_capture','upgrade_amount','upgrade_type','upgrade_by','check_expirydate','check_package_type'))->values(array($in_company[0],$upgrade_packid,$package_name,$no_of_taxi,$no_of_driver,$expirydate,'Success','1',$amount,'D',$userid,$expirydate,$package_type))->execute();							
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
		else
		{
			return 2;
		}
	}


	public function check_array($value="")
	{	

			if(!empty($value))
			{ 
				return true;
			}
			else
			{	
				return false;		
			}
	}



	//To Add Taxi Functionalities 

	public static function addtaxi($post,$form_array,$image,$files)
	{
		$field ='';
		$field_value ='';
		$array_fieldvalue = '';
		$merge_field ='';
		$merge_value = '';
		$taxi_createdby = $_SESSION['userid'];
		foreach($form_array as $value)
		{	
			$field .= $value.",";
		}

		$field = substr($field, 0, -1);		

		foreach($form_array as $value)
		{	
			$field_value .= "'".$post[$value]."',";
			$array_fieldvalue .=$post[$value].",";
		}

		$field_value = substr($field_value, 0, -1);
		$cid = $post['company_name'];
		/*
		$fetch_result = DB::select()->from(COUNT_HISTORY)->where('history_company_id','=',$cid)
			->execute()
			->as_array();
	
		if(count($fetch_result) == 0)
		{
			
			$insertresult = DB::insert(COUNT_HISTORY, array('history_company_id','history_taxi_count','history_updatedate'))
					->values(array($cid,'1',now()))
					->execute();
		}
		else
		{
			 $updatequery = " UPDATE ". COUNT_HISTORY ." SET history_taxi_count=history_taxi_count+1 wHERE history_company_id = '$did' ";	
			 
	 		$updateresult = Db::query(Database::UPDATE, $updatequery)
   			 ->execute();

		}
		*/		
		
		if(isset($files['size']['name']))
		{
		
			$count = count($files['size']['name']);
		}
		else
		{
			$count = 0;
		}				
		$result = DB::insert(TAXI, array('taxi_no','taxi_type','taxi_model','taxi_company','taxi_owner_name','taxi_manufacturer','taxi_colour','taxi_motor_expire_date', 'taxi_insurance_number','taxi_insurance_expire_date_time'/*,'taxi_pco_licence_number','taxi_pco_licence_expire_date'*/,'taxi_country', 'taxi_state','taxi_city','taxi_capacity','taxi_speed','taxi_min_speed','max_luggage','taxi_fare_km','taxi_createdby','taxi_status', 'taxi_image','taxi_sliderimage'))
			->values(array($post['taxi_no'],$post['taxi_type'],$post['taxi_model'],$post['company_name'],$post['taxi_owner_name'],$post['taxi_manufacturer'],$post['taxi_colour'],$post['taxi_motor_expire_date'],$post['taxi_insurance_number'],$post['taxi_insurance_expire_date']/*,$post['taxi_pco_licence_number'],$post['taxi_pco_licence_expire_date']*/,$post['country'],$post['state'],$post['city'],'',$post['taxi_speed'],$post['taxi_min_speed'],$post['minimum_luggage'],$post['taxi_fare_km'],$taxi_createdby,ACTIVE,$image,$count))
			->execute();//$post['taxi_capacity']

			
		$array_fieldvalue = substr($array_fieldvalue, 0, -1);
		$taxi_id = $result[0];
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

		$merge_field = array('taxi_id');
		$merge_value = array($result[0]);
		$taxi_arrcount = array();

		for($i=0;$i<$count;$i++)
		{
			
			$file_array = array();
			$file_array['name'] =  $files['size']['name'][$i];
			$file_array['type'] = $files['size']['type'][$i];
			$file_array['tmp_name'] = $files['size']['tmp_name'][$i];
			$file_array['error'] = $files['size']['error'][$i];
			$image_name = $taxi_id.'_'.$i; 
			$taxi_arrcount[] = $i;
			$filename = Upload::save($file_array,$image_name,DOCROOT.TAXI_IMG_IMGPATH);
			$logo_image = Image::factory($filename);
			$path1=DOCROOT.TAXI_IMG_IMGPATH;
			$path=$image_name;
			Commonfunction::multipleimageresize($logo_image,TAXI_IMG_WIDTH, TAXI_IMG_HEIGHT,$path1,$image_name,90);
			
		}

		$update_arrimage = serialize($taxi_arrcount);
		
     	        $updatequery = " UPDATE ". TAXI ." SET taxi_serializeimage='$update_arrimage' wHERE taxi_id = '$taxi_id' ";	
		 
 		$updateresult = Db::query(Database::UPDATE, $updatequery)

		 ->execute();
   			 		
		$array_field =Arr::merge($array_field,$merge_field);
		$array_value =Arr::merge($array_value,$merge_value);

		 $result = DB::insert(ADDFIELD, $array_field)

					->values($array_value)
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
	
	//To check the fare exist for the model
	public static function check_fare_exist($comny_id,$modelid)
	{		
		$result =DB::select(array(DB::expr("COUNT('company_model_fare_id')"), 'count'))->from(COMPANY_MODEL_FARE)
			->where(COMPANY_MODEL_FARE.'.company_cid','=',$comny_id)
			->where(COMPANY_MODEL_FARE.'.model_id','=',$modelid)
			->execute()
			->as_array();
		return !empty($result) ? $result[0]['count'] : 0;
	}
	
	//To Add Motor company Functionalities 
	public static function addmotor($post)
	{

		$result = DB::insert(MOTORCOMPANY, array('motor_name','motor_status'))
					->values(array($post['companyname'],ACTIVE))
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

	//To Add Motor company Functionalities 
	public static function addfield($post)
	{

		$result = DB::insert(MANAGEFIELD, array('field_labelname','field_name','field_type','field_value','field_status'))
					->values(array($post['field_labelname'],$post['field_name'],$post['field_type'],$post['field_value'],ACTIVE))
					->execute();
		
		$fetch_result = DB::select()->from(MANAGEFIELD)
			->execute()
			->as_array();
	
		$order_id = count($fetch_result);
		
		$result = DB::update(MANAGEFIELD)->set(array('field_order' => $order_id))->where('field_id', '=', $result[0])
			->execute();
	        	  
	        	  			
		DB::query(5,"ALTER TABLE taxi_additional_field ADD ".$post['field_name']." varchar(250)  NOT NULL")
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
		
	//To Add Model Functionalities 
	public static function addmodel($post)
	{

		$result = DB::insert(MOTORMODEL, array('model_name','model_size','model_status','motor_mid','base_fare','min_fare','cancellation_fare','below_km','above_km','night_charge',
'night_timing_from','night_timing_to','night_fare','evening_charge','evening_timing_from','evening_timing_to','evening_fare','waiting_time','min_km','below_above_km','minutes_fare'))
					->values(array($post['model_name'],$post['model_size'],ACTIVE,$post['companyname'],$post['base_fare'],$post['min_fare'],$post['cancellation_fare'],$post['below_km'],$post['above_km'],$post['night_charge'],$post['night_timing_from'],$post['night_timing_to'],$post['night_fare'],$post['evening_charge'],$post['evening_timing_from'],$post['evening_timing_to'],$post['evening_fare'],$post['waiting_time'],$post['min_km'],$post['below_and_above_km'],$post['minutes_fare']))
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
	
	//To Add Fare Functionalities 
	public static function addfare($post)
	{
		$company_id = $_SESSION['company_id'];
		$model_name = DB::select("model_name")->from(MOTORMODEL)->where('model_status','=','A')->where('model_id','=',$post['model_name'])
				->execute()
				->as_array();
		if($model_name){
		$result = DB::insert(COMPANY_MODEL_FARE, array('model_id','model_name','company_cid','motor_mid','base_fare','min_fare','cancellation_fare','below_km','above_km','night_charge',
'night_timing_from','night_timing_to','night_fare','evening_charge','evening_timing_from','evening_timing_to','evening_fare','min_km','below_above_km','minutes_fare','model_size','waiting_time'))
					->values(array($post['model_name'],$model_name['0']['model_name'],$company_id,$post['companyname'],$post['base_fare'],$post['min_fare'],$post['cancellation_fare'],$post['below_km'],$post['above_km'],$post['night_charge'],$post['night_timing_from'],$post['night_timing_to'],$post['night_fare'],$post['evening_charge'],$post['evening_timing_from'],$post['evening_timing_to'],$post['evening_fare'],$post['min_km'],$post['below_and_above_km'],$post['minutes_fare'],$post['model_size'],$post['waiting_time']))
					->execute();
		
		if($result)
		{
			return 1;
		}
		else
		{
			return 0;
		}

                } else{return 0; }
	}
	
	//To Add Fare Functionalities 
	public static function exist_models($model_id)
	{
		$company_id = $_SESSION['company_id'];
		$result =DB::select()->from(COMPANY_MODEL_FARE)
			->where(COMPANY_MODEL_FARE.'.company_cid','=',$company_id)
			->where(COMPANY_MODEL_FARE.'.model_id','=',$model_id)
			->execute()
			->as_array();
		  	return $result;
	}
	
	//To Add company Functionalities 
	public static function add_driver($post,$filename)
	{
		
		$password = Html::chars(md5($post['password']));

		$user_createdby = $_SESSION['userid']; 

		$cid = $post['company_name'];
		/*
		$fetch_result = DB::select()->from(COUNT_HISTORY)->where('history_company_id','=',$cid)
			->execute()
			->as_array();
	
		if(count($fetch_result) == 0)
		{
			
			$insertresult = DB::insert(COUNT_HISTORY, array('history_company_id','history_driver_count','history_updatedate'))
					->values(array($cid,'1',now()))
					->execute();
		}
		else
		{
			 $updatequery = " UPDATE ". COUNT_HISTORY ." SET history_taxi_count=history_driver_count+1 wHERE history_company_id = '$did' ";	
			 
	 		$updateresult = Db::query(Database::UPDATE, $updatequery)
   			 ->execute();

		}
		*/
		
		$companyId = isset($post['company_name']) ? $post['company_name'] : 0;
		
			
		$dob = Commonfunction::ensureDatabaseFormat($post['dob'],3);
		$current_date = date('Y-m-d H:i:s', time());
		$result = DB::insert(PEOPLE, array('name','address','lastname','gender','dob','email','country_code','phone','password','org_password','created_date','user_type','status','user_createdby','login_country','login_state','login_city','company_id','driver_license_id','booking_limit','profile_picture'))
					->values(array($post['firstname'],$post['address'],$post['lastname'],$post['gender'],$dob,$post['email'],$post['telephone_code'],$post['phone'],$password,$post['password'],$current_date,'D',ACTIVE,$user_createdby,$post['country'],$post['state'],$post['city'],$companyId,$post['driver_license_id'],$post['booking_limit'],$filename))
					->execute();
		$driver_id = $result[0];

		$find_driver = DB::select(array(DB::expr("COUNT('driver_id')"),'count'))->from(DRIVER)
			->where('driver_id','=',$driver_id)
			->execute()
			->as_array();
		$count_finddriver = !empty($find_driver) ? $find_driver[0]['count']	:0;
		if($count_finddriver == 0)
		{
			$cityresult = DB::select('city_name')->from(CITY)->where('city_id','=',$post['city'])
							->where('city_status','=','A')
							->order_by('city_name','ASC')
							->execute()
							->as_array();
			$address = $cityresult[0]['city_name'];
			$address = str_replace(' ', '+', $address);
			$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&sensor=false&key='.GOOGLE_GEO_API_KEY;
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$geoloc = curl_exec($ch);
			
			//print_r($geoloc);
			$json = json_decode($geoloc);	
			if(isset($json->status) == 'OK' && $json !='')
			{
				$latitude = $json->results[0]->geometry->location->lat;
				$longitude = $json->results[0]->geometry->location->lng;
			}
			else
			{
				$latitude = LOCATION_LATI;
				$longitude = LOCATION_LONG;
			}
		
			$result = DB::insert(DRIVER, array('driver_id','latitude','longitude','status','shift_status'))
					->values(array($driver_id,$latitude,$longitude,'F','OUT'))
					->execute();
		}					
		if($result)
		{
			$driver_license_expire_date = Commonfunction::ensureDatabaseFormat($post['driver_license_expire_date'],3);
			/*$driver_pco_license_expire_date = Commonfunction::ensureDatabaseFormat($post['driver_pco_license_expire_date'],3);*/
			$driver_insurance_expire_date = Commonfunction::ensureDatabaseFormat($post['driver_insurance_expire_date'],3);
			/*$driver_national_insurance_expire_date = Commonfunction::ensureDatabaseFormat($post['driver_national_insurance_expire_date'],3);*/
			//driver additional information insert
			$driver_info = DB::insert(DRIVER_INFO, array('driver_id','driver_license_expire_date'/*,'driver_pco_license_number','driver_pco_license_expire_date'*/,'driver_insurance_number','driver_insurance_expire_date'/*,'driver_national_insurance_number','driver_national_insurance_expire_date'*/))->values(array($driver_id,$driver_license_expire_date/*,$post['driver_pco_license_number'],$driver_pco_license_expire_date*/,$post['driver_insurance_number'],$driver_insurance_expire_date/*,$post['driver_national_insurance_number'],$driver_national_insurance_expire_date*/))->execute();
			//if(DRIVER_REF_SETTINGS == 1) {
				//driver referral insert
				$driverReferralCode = strtoupper(text::random($type = 'alnum', $length = 6));
				$driverRef = DB::insert(DRIVER_REF_DETAILS, array('registered_driver_id','registered_driver_code','registered_driver_code_amount'))->values(array($driver_id,$driverReferralCode,DRIVER_REF_AMOUNT))->execute();
			//}
			
			/** For Single brand type drivers, add their details to company and company_info tables ( only for Single brand type ) **/
			if($post['brand_type'] == "S") {
				$in_company = DB::insert(COMPANY, array('company_name','company_address','company_country','company_state','company_city','userid','time_zone','header_bgcolor','menu_color','mouseover_color'))
							->values(array($post['firstname'],$post['address'],$post['country'],$post['state'],$post['city'],$driver_id,$_SESSION['timezone'],'#FFFFFF','#000000','#FFD800'))
							->execute();
					
				$companyId = $in_company[0];
				
				$key="";
			   $charset = "abcdefghijklmnopqrstuvwxyz"; 
			   $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
			   $charset .= "0123456789"; 
			   $length = mt_rand (30, 35); 
			   for ($i=0; $i<$length; $i++) 
			   $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];	
				//company info table
				DB::insert(COMPANYINFO, array('company_cid','company_brand_type','company_api_key'))->values(array($companyId,$post['brand_type'],$key))->execute();
				//update company id in people table
				$update_people = DB::update(PEOPLE)->set(array('company_id' => $companyId))->where('id', '=', $driver_id)
				->execute();
					$add_company = Model::factory('add');
					$upgrade_packid = 1;
					$get_packagedetails = $add_company->payment_packagedetails($upgrade_packid);
					$package_name = $get_packagedetails[0]['package_name']; 
					$no_of_taxi = $get_packagedetails[0]['no_of_taxi']; 
					$no_of_driver = $get_packagedetails[0]['no_of_driver']; 
					$days = $get_packagedetails[0]['days_expire'];   
					$amount = $get_packagedetails[0]['package_price'];   
					$package_type = $get_packagedetails[0]['package_type'];   

					$userid = $_SESSION['userid'];	
					
					// Convert Time
							
					$current_time = convert_timezone('now',$_SESSION['timezone']);

				
					// Convert Time

					if($days > 0)
					{
						$expirydate = Commonfunction::getExpiryTimeStamp($current_time,$days);
					}
					else
					{
						$expirydate = $current_time;
					}
			
					$result = DB::insert(PACKAGE_REPORT, array('upgrade_companyid','upgrade_packageid','upgrade_packagename','upgrade_no_taxi','upgrade_no_driver','upgrade_expirydate','upgrade_ack','upgrade_capture','upgrade_amount','upgrade_type','upgrade_by','check_expirydate','check_package_type'))->values(array($in_company[0],$upgrade_packid,$package_name,$no_of_taxi,$no_of_driver,$expirydate,'Success','1',$amount,'D',$userid,$expirydate,$package_type))->execute();	
			}
			return $driver_id;
		}
		else
		{
			return 0;
		}
	}
	
	// To Check User Name is Already Available or Not
	public static function checkusername($name)
	{
		// Check if the username already exists in the database
			
		$result = DB::select('username')->from(PEOPLE)->where('username','=',$name)
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

	// To Check User Name is Already Available or Not
	public static function check_taxino($name)
	{
		// Check if the username already exists in the database
			
		$result = DB::select('taxi_no')->from(TAXI)->where('taxi_no','=',$name)
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
	public static function checkmotorname($name)
	{
		// Check if the username already exists in the database

		$result = DB::select('motor_name')->from(MOTORCOMPANY)->where('motor_name','=',$name)
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
	public static function checkmodelname($name,$motorid)
	{

		// Check if the username already exists in the database

		$result = DB::select('model_name')->from(MOTORMODEL)->where('model_name','=',$name)->where('motor_mid','=',$motorid)
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
	// To Check Taxi Name is Already Available or Not
	public static function checktaxiname($name)
	{
		// Check if the username already exists in the database

		$result = DB::select('taxi_name')->from(TAXI)->where('taxi_name','=',$name)
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
	public static function checkcompany($companyname,$country,$state,$city)
	{
		// Check if the username already exists in the database
		
		$result = DB::select('company_name')->from(COMPANY)->where('company_name','=',$companyname)->where('company_country','=',$country)->where('company_state','=',$state)->where('company_city','=',$city)




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
	public static function checkdomain($domainname)
	{
		// Check if the username already exists in the database
		
		$result = DB::select('company_domain')->from(COMPANYINFO)->where('company_domain','=',$domainname)
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
	public static function checkcompanydomain($domainname)
	{
		// Check if the username already exists in the database
		
		$result = DB::select('company_domain')->from(COMPANYINFO)->where('company_domain','=',$domainname)
				->execute()
				->as_array();	
			
			if(count($result) > 0)
			{
				return 1;
			}
			else
			{
				return  0;
			}
	}	
	// Check Whether Email is Already Exist or Not
	public static function checkemail($email="")
	{


		$result = DB::select('email')->from(PEOPLE)->where('email','=',$email)
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
	public static function checkphone($phone="")
	{


		$result = DB::select('phone')->from(PEOPLE)->where('phone','=',$phone)
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
	
	public static function checkphone_autocreate($phone="")
	{
		$phone = $phone.'1';

		$result = DB::select('phone')->from(PEOPLE)->where('phone','=',$phone)
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
	
		// Check Whether Motor details is Already Exist or Not
	public static function motor_details()
	{
		
		$result = DB::select()->from(MOTORCOMPANY)->where('motor_status','=','A')->order_by('motor_name','asc')
			->execute()
			->as_array();

		  return $result;
	}
	
	// Get the Additional Field for Taxi
	public static function taxi_additionalfields()
	{

		$result = DB::select('field_name','field_labelname','field_type','field_value')->from(MANAGEFIELD)->where('field_status','=','A')->order_by('field_order','asc')
			->execute()
			->as_array();
		//	print_r($result);
		  return $result;
	}

	/**Validating for Add Motor**/
	public function validate_addpackage($arr) 
	{
		return Validation::factory($arr)       
		
			->rule('package_name', 'not_empty')
			->rule('package_name', 'min_length', array(':value', '4'))
			->rule('package_name', 'max_length', array(':value', '100'))
			->rule('package_name', 'Model_Add::checkpackagename', array(':value',$arr['package_name']))
			
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
			->rule('days_expire', 'Model_Add::checkpackageExists', array($arr['package_price'],$arr['no_of_taxi'],$arr['no_of_driver'],$arr['days_expire'],''));

			

	}

	// To Check Company Name is Already Available or Not
	public static function checkpackagename($packagename)
	{
		// Check if the username already exists in the database
		
		$result = DB::select('package_name')->from(PACKAGE)->where('package_name','=',$packagename)
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
	
	//To Add company Functionalities 
	public static function add_package($post)
	{

		if(isset($post['driver_tracking']))
		{
			$driver_tracking = 'S';
		}
		else
		{
			$driver_tracking = 'N';
		}

		$result = DB::insert(PACKAGE, array('package_name','package_description','no_of_taxi','no_of_driver','package_price','days_expire','package_status','package_type','driver_tracking'))
					->values(array($post['package_name'],$post['package_description'],$post['no_of_taxi'],$post['no_of_driver'],$post['package_price'],$post['days_expire'],ACTIVE,$post['package_type'],$driver_tracking))
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

	/**Validating for Add Country**/
	public function validate_addcountry($arr) 
	{
		
		//print_r($arr);
		//exit;
		return Validation::factory($arr)       
		
			->rule('country_name', 'not_empty')
			//->rule('country_name', 'alpha_dash')
			->rule('country_name', 'Model_Add::check_reg_countryname', array(':value'))
			->rule('country_name', 'min_length', array(':value', '2'))
			->rule('country_name', 'max_length', array(':value', '30'))
			->rule('country_name', 'Model_Add::checkcountryname', array(':value'))

			->rule('iso_country_code', 'not_empty')
			->rule('iso_country_code', 'min_length', array(':value', '2'))
			->rule('iso_country_code', 'max_length', array(':value', '5'))
			->rule('iso_country_code', 'Model_Add::checkisocountrycode', array(':value'))
			
			->rule('telephone_code', 'not_empty')
			->rule('telephone_code', 'min_length', array(':value', '2'))
			->rule('telephone_code', 'max_length', array(':value', '5'))
			
			->rule('currency_code', 'not_empty')
			->rule('currency_code', 'min_length', array(':value', '2'))
			->rule('currency_code', 'max_length', array(':value', '5'))
			
			->rule('currency_symbol', 'not_empty')
			->rule('currency_symbol', 'max_length', array(':value', '5'));

	}
		
	// To Check Countryname is Already Available or Not
	public static function checkcountryname($name)
	{
		$result = DB::select('country_name')->from(COUNTRY)->where('country_name','=',$name)
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

	// To Check Countryname is Already Available or Not
	public static function checkfaqtitle($faq)
	{
		$result = DB::select('faq_title')->from(PASSENGERS_FAQ)->where('faq_title','=',$faq)
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

		// To Check Countryname is Already Available or Not
	public static function checkisocountrycode($iso_country_code)
	{
		$result = DB::select('iso_country_code')->from(COUNTRY)->where('iso_country_code','=',$iso_country_code)
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

	//To Add Country Functionalities 
	public static function addcountry($post)
	{
		$result = DB::insert(COUNTRY, array('country_name','iso_country_code','telephone_code','currency_code','currency_symbol','country_status'))
					->values(array($post['country_name'],$post['iso_country_code'],$post['telephone_code'],$post['currency_code'],$post['currency_symbol'],ACTIVE))
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
	
	public static function country_details()
	{
		$result = DB::select('country_id','country_name')->from(COUNTRY)->where('country_status','=','A')->order_by('country_name','asc')->execute()->as_array();
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

	public static function city_details()
	{
		
		$result = DB::select('city_id','city_name')->from(CITY)->where('city_status','=','A')->order_by('city_name','asc')
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
	
	public static function get_city_state_details($countryid)
	{
		if($countryid)
		{
			$state_countryid = $countryid;
		}
		else
		{
			$state_countryid = DEFAULT_COUNTRY;
		}
		$result = DB::select('state_id','state_name')->from(STATE)->where('state_status','=','A')
		->where('state_countryid','=',$state_countryid)
		->order_by('state_name','asc')
			->execute()
			->as_array();

		  return $result;
	}
		
	public function validate_addcity($arr) 
	{

		return Validation::factory($arr)       

			->rule('city_name', 'not_empty')
			//->rule('city_name', 'alpha_dash')
			->rule('city_name', 'Model_Add::check_reg_city_name', array(':value'))
			->rule('city_name', 'min_length', array(':value', '2'))
			->rule('city_name', 'max_length', array(':value', '30'))
			->rule('city_name', 'Model_Add::checkcityname', array(':value',$arr['state_name'],$arr['country_name']))

			->rule('state_name', 'not_empty')
			->rule('country_name', 'not_empty')

			->rule('zipcode', 'not_empty')
			
			->rule('city_model_fare', 'not_empty')
			->rule('city_model_fare', 'numeric');
			//->rule('city_model_fare', 'decimal', array(':value', '2'));
			//->rule('city_model_fare', 'Model_Add::check_base_fare', array(':value',$arr['city_model_fare']));

	}
	
	public function validate_addstate($arr) 
	{
		return Validation::factory($arr)
			->rule('state_name', 'not_empty')
			//->rule('state_name', 'alpha_dash')
			->rule('state_name', 'Model_Add::check_reg_state_name', array(':value'))
			->rule('state_name', 'min_length', array(':value', '2'))
			->rule('state_name', 'max_length', array(':value', '30'))
			->rule('state_name', 'Model_Add::checkstatename', array(':value',$arr['country_name']))
			
			->rule('country_name', 'not_empty');

	}



	public static function checkcityname($name,$stateid,$motorid)
	{

		$result = DB::select('city_name')->from(CITY)->where('city_name','=',$name)->where('city_stateid','=',$stateid)->where('city_countryid','=',$motorid)
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
	
	public static function checkstatename($name,$motorid)
	{
		$result = DB::select('state_name')->from(STATE)->where('state_name','=',$name)->where('state_countryid','=',$motorid)
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
	public static function addcity($post)
	{
		$result = DB::insert(CITY, array('city_name','zipcode','city_status','city_countryid','city_stateid','city_model_fare'))
					->values(array($post['city_name'],$post['zipcode'],ACTIVE,$post['country_name'],$post['state_name'],$post['city_model_fare']))
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
	public static function addstate($post)
	{
		$result = DB::insert(STATE, array('state_name','state_status','state_countryid'))
					->values(array($post['state_name'],ACTIVE,$post['country_name']))
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

	public static function model_details()
	{


			$result = DB::select()->from(MOTORMODEL)->where('model_status','=','A')->order_by('model_name','ASC')
				->execute()
				->as_array();
			

			return $result;
	}

	public static function model_details_new()
	{
		$company_id = $_SESSION['company_id'];
		if(FARE_SETTINGS == 2 && $company_id !=0) //company_id = 0 as Admin
		{
			 $model_base_query = "select distinct ".MOTORMODEL.".model_id,".COMPANY_MODEL_FARE.".model_name from ".COMPANY_MODEL_FARE." left join ".MOTORMODEL." on ".MOTORMODEL.".model_id=".COMPANY_MODEL_FARE.".model_id where ".COMPANY_MODEL_FARE.".company_cid='$company_id'"; 

			$result = Db::query(Database::SELECT, $model_base_query)
				->execute()
				->as_array();	
			return $result;
		}
		else
		{
			$result = DB::select('model_id','model_name')->from(MOTORMODEL)->where('model_status','=','A')
				->where('motor_mid','=','1')
				->order_by('model_name','ASC')
				->execute()
				->as_array();
			return $result;
		}	
	}
	

	public static function getmodel_details($motorid)
	{


			$result = DB::select('model_id','model_name')->from(MOTORMODEL)->join(MOTORCOMPANY, 'LEFT')->on(MOTORMODEL.'.motor_mid', '=', MOTORCOMPANY.'.motor_id')->where('motor_mid','=',$motorid)->order_by('model_name','ASC')
				->execute()
				->as_array();
			
			return $result;
	}

	public static function getcity_details($country_id,$state_id)
	{


			$result = DB::select('city_id','city_name')->from(CITY)->join(STATE, 'LEFT')->on(CITY.'.city_stateid', '=', STATE.'.state_id')->join(COUNTRY, 'LEFT')->on(CITY.'.city_countryid', '=', COUNTRY.'.country_id')->where('city_countryid','=',$country_id)->where('city_stateid','=',$state_id)->where('state_status','=','A')->where('city_status','=','A')->order_by('city_name','ASC')
				->execute()
				->as_array();
	
			return $result;
	}
	
	public static function getstate_details($country_id)
	{

			$result = DB::select('state_id','state_name')->from(STATE)->join(COUNTRY, 'LEFT')->on(STATE.'.state_countryid', '=', COUNTRY.'.country_id')->where('state_countryid','=',$country_id)->where('state_status','=','A')->order_by('state_name','ASC')
				->execute()
				->as_array();
			
			return $result;
	}	

	public static function taxicompany_details()
	{
		
		$result = DB::select(COMPANY.'.cid',COMPANY.'.company_name',COMPANYINFO.'.company_brand_type')->from(COMPANY)->join(COMPANYINFO, 'LEFT')->on(COMPANYINFO.'.company_cid', '=', COMPANY.'.cid')->where(COMPANY.'.company_status','=','A')->order_by(COMPANY.'.company_name','asc')
			->execute()
			->as_array();
			
			return $result;

	}
	
	public static function getcompany_details($country_id,$state_id,$city_id)
	{
		
		$result = DB::select()->from(COMPANY)->where('company_status','=','A')->where('company_country','=',$country_id)->where('company_state','=',$state_id)->where('company_city','=',$city_id)->order_by('company_name','asc')
			->execute()
			->as_array();
			
			return $result;

	}

	public static function getcompanydetails($company_id)
	{
		
		$result = DB::select()->from(COMPANY)->where('company_status','=','A')->where('cid','=',$company_id)->order_by('company_name','asc')
			->execute()
			->as_array();
			
			return $result;

	}
	
	public function validate_addmanager($arr) 
	{
		return Validation::factory($arr)       
		
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
			->rule('email', 'Model_Add::checkemail', array(':value'))
			
			->rule('phone', 'not_empty')
			//->rule('phone', 'alpha_numeric')
			->rule('phone', 'min_length', array(':value', '7'))
			->rule('phone', 'max_length', array(':value', '20'))
			//->rule('phone', 'phone', array(':value'))
			->rule('phone', 'Model_Add::checkphone', array(':value'))
            
			->rule('password', 'not_empty')
			->rule('password', 'min_length', array(':value', '4'))
			->rule('password', 'max_length', array(':value', '20'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))

			->rule('repassword', 'not_empty')
			->rule('repassword', 'min_length', array(':value', '4'))
			->rule('repassword', 'max_length', array(':value', '20'))
			->rule('repassword','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('repassword',  'matches', array(':validation', 'password', 'repassword'))
			
			->rule('company_name', 'not_empty')
			->rule('company_name','valid_strings',array(':value','/^[A-Za-z0-9-_. ]*$/u'))
			//->rule('company_name', 'alpha_dash')
			//->rule('company_name', 'Model_Add::checkmanagercompany', array(':value',$arr['city'],$arr['state'],$arr['country']))
			
			->rule('address', 'not_empty')
		
			->rule('country', 'not_empty')
			
			->rule('state', 'not_empty')
			
			->rule('city', 'not_empty');
			

	}

	public function validate_addadmin($arr) 
	{ 
		return Validation::factory($arr)       
		
           		->rule('firstname', 'not_empty')
			->rule('firstname', 'min_length', array(':value', '4'))
			->rule('firstname', 'max_length', array(':value', '30'))
			
            		->rule('lastname', 'not_empty')

			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('email', 'Model_Add::checkemail', array(':value'))
			
			->rule('phone', 'not_empty')
			//->rule('phone', 'alpha_numeric')
			->rule('phone', 'min_length', array(':value', '7'))
			->rule('phone', 'max_length', array(':value', '20'))
			->rule('phone', 'contact_phone', array(':value'))
			->rule('phone', 'Model_Add::checkphone', array(':value'))
            
			->rule('password', 'not_empty')
			->rule('password', 'min_length', array(':value', '4'))
			->rule('password', 'max_length', array(':value', '20'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))

			->rule('repassword', 'not_empty')
			->rule('repassword', 'min_length', array(':value', '4'))
			->rule('repassword', 'max_length', array(':value', '20'))
			->rule('repassword','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('repassword',  'matches', array(':validation', 'password', 'repassword'))
			
			->rule('address', 'not_empty')
		
			->rule('country', 'not_empty');
			
			

	}

	public static function checkmanagercompany($companyname,$cityid,$stateid,$countryid)
	{


		
		$result = DB::select()->from(PEOPLE)->where('company_id','=',$companyname)->where('login_country','=',$countryid)->where('login_state','=',$stateid)->where('login_city','=',$cityid)->where('user_type','=','M')
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
	
	public static function addmanager($post)
	{
		$password = Html::chars(md5($post['password']));
		$manager_createdby = $_SESSION['userid'];
		
		$current_date = date('Y-m-d H:i:s', time());
		$result = DB::insert(PEOPLE, array('name','address','lastname','email','country_code','phone','password','org_password','created_date','user_type','status','login_country','login_state','login_city','company_id','user_createdby'))
					->values(array($post['firstname'],$post['address'],$post['lastname'],$post['email'],$post['telephone_code'],$post['phone'],$password,$post['password'],$current_date,'M',ACTIVE,$post['country'],$post['state'],$post['city'],$post['company_name'],$manager_createdby))
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

	public static function addadmin($post)
	{

		$password = Html::chars(md5($post['password']));
		$manager_createdby = $_SESSION['userid'];
		
		$current_date = date('Y-m-d H:i:s', time());
		$result = DB::insert(PEOPLE, array('name','address','lastname','email','country_code','phone','password','org_password','created_date','user_type','account_type','status','login_country','user_createdby'))
					->values(array($post['firstname'],$post['address'],$post['lastname'],$post['email'],$post['telephone_code'],$post['phone'],$password,$post['password'],$current_date,$post['user_type'],'0',ACTIVE,$post['country'],$manager_createdby))
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
	
	
	public function driver_details()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];

	   	
	   	if($usertype =='M')
	   	{
		$rs = DB::select('id','name')->from(PEOPLE)
				->where('user_type','=','D')
				->where('status','=','A')
				->where('availability_status','=','A')
				->where('login_country','=',$country_id)
				->where('login_state','=',$state_id)
				->where('login_city','=',$city_id)
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')
				->execute()
				->as_array();
				

				return $rs;
		}
		else if($usertype =='C')
		{
		
		$rs = DB::select()->from(PEOPLE)
				->where('user_type','=','D')
				->where('status','=','A')
				->where('availability_status','=','A')
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else
		{
			$rs = DB::select()->from(PEOPLE)
				->where('user_type','=','D')
				->where('status','=','A')
				->where('availability_status','=','A')
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
		
		}	
	}															
	
	public function taxi_details()
	{
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
		$rs = DB::select('taxi_id','taxi_no')->from(TAXI)
				->where('taxi_country','=',$country_id)
				->where('taxi_state','=',$state_id)
				->where('taxi_city','=',$city_id)
				->where('taxi_company','=',$company_id)
				->where('taxi_status','=','A')
				->where('taxi_availability','=','A')
				->order_by('taxi_id','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else if($usertype =='C')
		{
		
		$rs = DB::select()->from(TAXI)
				->where('taxi_company','=',$company_id)
				->where('taxi_status','=','A')
				->where('taxi_availability','=','A')				
				->order_by('taxi_id','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else
		{
			$rs = DB::select()->from(TAXI)
				->where('taxi_status','=','A')
				->where('taxi_availability','=','A')			
				->order_by('taxi_id','desc')
				->execute()
				->as_array();
				
				return $rs;
		
		}
	}															

	public static function getassignedlist($country_id='',$state_id='',$city_id='',$company_name='',$driver_id='',$taxi_id='',$startdate='',$enddate='',$offset='',$val='')
	{
		
				
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];

		$driver_where ='';
		$taxi_where = '';
		$cond_where = '';
		$startdate_where = '';
		$date_where = '';
		$enddate_where = '';
		$company_where = '';
		$manager_where = '';
		
		if($usertype =='C')
	   	{
	   		$company_where = " AND mapping_companyid ='$company_id' ";
	   		
	   	}	
	   	
		if($usertype =='M')
	   	{
	   		$manager_where = " AND mapping_companyid ='$company_id'  AND mapping_countryid='$country_id' AND mapping_stateid='$state_id'  AND mapping_cityid='$city_id'";
	   		
	   	}
	   					
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

		
		$query = " select * from " . TAXIMAPPING . " left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id where 1=1  $company_where $manager_where $cond_where  $date_where order by mapping_startdate ASC limit $val  offset $offset ";


	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			return $results;
	}

	public static function getassignedcount($country_id='',$state_id='',$city_id='',$company_name='',$driver_id='',$taxi_id='',$startdate='',$enddate='')
	{
		
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
		
		$driver_where ='';
		$taxi_where = '';
		$cond_where = '';
		$startdate_where = '';
		$date_where = '';
		$enddate_where = '';
		$company_where = '';
		$manager_where = '';
		if($usertype =='C')
	   	{
	   		$company_where = " AND mapping_companyid ='$company_id' ";
	   		
	   	}	
	   	
		if($usertype =='M')
	   	{
	   		$manager_where = " AND mapping_companyid ='$company_id'  AND mapping_countryid='$country_id' AND mapping_stateid='$state_id'  AND mapping_cityid='$city_id'";
	   		
	   	}		   	
	   		
		if($driver_id && $taxi_id)
		{
			$cond_where = " AND (mapping_driverid ='$driver_id' or mapping_taxiid ='$taxi_id')";
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
		
		$query = " select * from " . TAXIMAPPING .  " left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid = ".TAXI.".taxi_id left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id where 1=1  $company_where $manager_where $cond_where  $date_where order by mapping_startdate ASC ";


	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			return count($results);
	}
		
	public function validate_addassigntaxi($arr) 
	{
		return Validation::factory($arr)       
		

			->rule('company_name', 'not_empty')
			->rule('country', 'not_empty')
			->rule('state', 'not_empty')
			->rule('city', 'not_empty')
			->rule('driver', 'not_empty')
			->rule('startdate', 'not_empty')
			->rule('enddate', 'not_empty')
			->rule('enddate', 'Model_Add::checkassigntaxi', array(':value',$arr))
			->rule('taxi', 'not_empty');

	}

	public static function checkassigntaxi($enddate,$post)
	{
		$country_id = $post['country'];
		$state_id = $post['state'];
		$city_id = $post['city'];
		$company_name = $post['company_name'];
		$driver_id = $post['driver'];
		$taxi_id = $post['taxi'];
		$startdate = $post['startdate']; 
		$enddate = $post['enddate']; 
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

		

		
		$query = " select * from " . TAXIMAPPING . " left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id where 1=1 and mapping_status='A' $cond_where  $date_where order by mapping_startdate DESC ";


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


	public static function checkactiveassigntaxi($enddate,$post)
	{
		$country_id = $post['country'];
		$state_id = $post['state'];
		$city_id = $post['city'];
		$company_name = $post['company_name'];
		$driver_id = $post['driver'];
		$taxi_id = $post['taxi'];
		$startdate = $post['startdate']; 
		$enddate = $post['enddate']; 
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

		

		
		$query = " select * from " . TAXIMAPPING . " left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id where 1=1 and mapping_status='A' $cond_where  $date_where order by mapping_startdate DESC ";


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

	public function addassigntaxi($post)
	{

		$mapping_createdby = $_SESSION['userid'];
		$result = DB::insert(TAXIMAPPING, array('mapping_driverid','mapping_taxiid','mapping_companyid','mapping_countryid','mapping_stateid','mapping_cityid','mapping_startdate','mapping_enddate','mapping_status','mapping_createdby'))
					->values(array($post['driver'],$post['taxi'],$post['company_name'],$post['country'],$post['state'],$post['city'],$post['startdate'],$post['enddate'],ACTIVE,$mapping_createdby))
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

	public function get_Taxino($id)
	{
				$result = DB::select('taxi_no')->from(TAXI)
				->where('taxi_id','=',$id)
				->execute()
				->as_array();
				if(count($result) > 0)
				{
					return $result[0]['taxi_no'];
				}
				else
				{
					return ''; 
				}	
		
	}
	
	public static function getdriverdetails($company_id,$country_id,$state_id,$city_id,$usertype)
	{

		$user_createdby = $_SESSION['userid'];
		$commMdl = Model::factory('commonmodel');
		$date = $commMdl->getcompany_all_currenttimestamp($_SESSION["company_id"]);
		if($usertype =='M')
		{
			/* $rs = DB::select()->from(PEOPLE)
				->where('user_type','=','D')
				->where('status','=','A')
				->where('availability_status','=','A')
				->where('login_country','=',$country_id)
				->where('login_state','=',$state_id)
				->where('login_city','=',$city_id)
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')
				->execute()
				->as_array();
				return $rs; */
			$query = "select id,name,mapping_id,mapping_startdate,mapping_enddate from (select * from " . PEOPLE . " left join " .TAXIMAPPING. " on " .TAXIMAPPING.".mapping_driverid = " .PEOPLE.".id where user_type = 'D' and status = 'A' and (availability_status = 'A' or availability_status = 'T') and login_country = '$country_id' and login_state = '$state_id' and login_city = '$city_id' and company_id = '$company_id' order by mapping_id desc) as p1 where (case when mapping_id !='NULL' then '$date' > mapping_startdate or '$date' < mapping_enddate ELSE 1=1 end) group by id";
			$result = Db::query(Database::SELECT, $query)
						->execute()
						->as_array();
			return $result;
		}
		else if($usertype =='C')
		{
			/* $rs = DB::select()->from(PEOPLE)
				->where('user_type','=','D')
				->where('status','=','A')
				->where('availability_status','=','A')
				->where('login_country','=',$country_id)
				->where('login_state','=',$state_id)
				->where('login_city','=',$city_id)
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')
				->execute()
				->as_array();
			return $rs; */
			$query = "select id,name,mapping_id,mapping_startdate,mapping_enddate from (select * from " . PEOPLE . " left join " .TAXIMAPPING. " on " .TAXIMAPPING.".mapping_driverid = " .PEOPLE.".id where user_type = 'D' and status = 'A' and (availability_status = 'A' or availability_status = 'T') and login_country = '$country_id' and login_state = '$state_id' and login_city = '$city_id' and company_id = '$company_id' order by mapping_id desc) as p1 where (case when mapping_id !='NULL' then '$date' > mapping_startdate or '$date' < mapping_enddate ELSE 1=1 end) group by id";
			$result = Db::query(Database::SELECT, $query)
						->execute()
						->as_array();
			return $result;
		}
		else
		{
			/*$result = DB::select()->from(PEOPLE)
				->where('user_type','=','D')
				->where('status','=','A')
				->where('availability_status','=','A')
				->where('login_country','=',$country_id)
				->where('login_state','=',$state_id)
				->where('login_city','=',$city_id)
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')
				->execute()
				->as_array(); */
			$query = "select id,name,mapping_id,mapping_startdate,mapping_enddate from (select * from " . PEOPLE . " left join " .TAXIMAPPING. " on " .TAXIMAPPING.".mapping_driverid = " .PEOPLE.".id where user_type = 'D' and status = 'A' and (availability_status = 'A' or availability_status = 'T') and login_country = '$country_id' and login_state = '$state_id' and login_city = '$city_id' and company_id = '$company_id' order by mapping_id desc) as p1 where (case when mapping_id !='NULL' then '$date' > mapping_startdate or '$date' < mapping_enddate ELSE 1=1 end) group by id";
			$result = Db::query(Database::SELECT, $query)
						->execute()
						->as_array();
			return $result;
		}
	}

	public function gettaxidetails($company_id,$country_id,$state_id,$city_id,$usertype)
	{
		$taxi_createdby = $_SESSION['userid'];
		$commMdl = Model::factory('commonmodel');
		$date = $commMdl->getcompany_all_currenttimestamp($_SESSION["company_id"]);
		
		if($usertype =='M')
		{
			/* $rs = DB::select()->from(TAXI)
				->where('taxi_status','=','A')
				->where('taxi_availability','=','A')
				->where('taxi_country','=',$country_id)
				->where('taxi_state','=',$state_id)
				->where('taxi_city','=',$city_id)
				->where('taxi_company','=',$company_id)
				->order_by('taxi_id','desc')
				->execute()
				->as_array();
			return $rs; */
			$query = "select taxi_id,taxi_model,taxi_no,mapping_id,mapping_startdate,mapping_enddate from (select * from " . TAXI . " left join " .TAXIMAPPING. " on " .TAXIMAPPING.".mapping_taxiid = " .TAXI.".taxi_id where taxi_status = 'A' and taxi_availability = 'A' and taxi_country = '$country_id' and taxi_state = '$state_id' and taxi_city = '$city_id' and taxi_company = '$company_id' order by mapping_id desc) as p1 where (case when mapping_id !='NULL' then '$date' > mapping_startdate or '$date' < mapping_enddate ELSE 1=1 end) group by taxi_id";
			$result = Db::query(Database::SELECT, $query)
						->execute()
						->as_array();
			return $result;
		}
		else if($usertype =='C')
		{
			/* $rs = DB::select()->from(TAXI)
				->where('taxi_status','=','A')
				->where('taxi_availability','=','A')
				->where('taxi_country','=',$country_id)
				->where('taxi_state','=',$state_id)
				->where('taxi_city','=',$city_id)
				->where('taxi_company','=',$company_id)
				->order_by('taxi_id','desc')
				->execute()
				->as_array();
			return $rs; */
			$query = "select taxi_id,taxi_model,taxi_no,mapping_id,mapping_startdate,mapping_enddate from (select * from " . TAXI . " left join " .TAXIMAPPING. " on " .TAXIMAPPING.".mapping_taxiid = " .TAXI.".taxi_id where taxi_status = 'A' and taxi_availability = 'A' and taxi_country = '$country_id' and taxi_state = '$state_id' and taxi_city = '$city_id' and taxi_company = '$company_id' order by mapping_id desc) as p1 where (case when mapping_id !='NULL' then '$date' > mapping_startdate or '$date' < mapping_enddate ELSE 1=1 end) group by taxi_id";
			$result = Db::query(Database::SELECT, $query)
						->execute()
						->as_array();
			return $result;
		}
		else
		{
			/* $rs = DB::select()->from(TAXI)
					->where('taxi_status','=','A')
					->where('taxi_availability','=','A')
					->where('taxi_country','=',$country_id)
					->where('taxi_state','=',$state_id)
					->where('taxi_city','=',$city_id)
					->where('taxi_company','=',$company_id)
					->order_by('taxi_id','desc')
					->execute()
					->as_array();
			return $rs; */
			$query = "select taxi_id,taxi_model,taxi_no,mapping_id,mapping_startdate,mapping_enddate from (select * from " . TAXI . " left join " .TAXIMAPPING. " on " .TAXIMAPPING.".mapping_taxiid = " .TAXI.".taxi_id where taxi_status = 'A' and taxi_availability = 'A' and taxi_country = '$country_id' and taxi_state = '$state_id' and taxi_city = '$city_id' and taxi_company = '$company_id' order by mapping_id desc) as p1 where (case when mapping_id !='NULL' then '$date' > mapping_startdate or '$date' < mapping_enddate ELSE 1=1 end) group by taxi_id";
			$result = Db::query(Database::SELECT, $query)
						->execute()
						->as_array();
			return $result;
		}
	}

	public static function package_details()
	{
		//$result = DB::select()->from(PACKAGE)->where('package_status','=','A')->order_by('package_name','asc')
		$result = DB::select()->from(PACKAGE)->where('package_status','=','A','package_type','=','N')->order_by('package_name','asc')
			->execute()
			->as_array();

		  return $result;
	}

	public function payment_packagedetails($packid=0)
	{

		$result = DB::select('package_name','no_of_taxi','no_of_driver','days_expire','package_price','package_type')->from(PACKAGE)
			->where('package_status','=','A')
			//->where('package_type','=','N')
			->where('package_id','=',$packid)
			->order_by('package_name','asc')
			->execute()
			->as_array();

		  return $result;
	}

	public function company_timezone($cid)
	{
		$result = DB::select('timezone')->from(PEOPLE)->where('company_id', '=', $cid)->where('user_type', '=', 'C')
		->execute()
		->as_array();

		if($result[0]['timezone'] !='')
		{
			return $result[0]['timezone'];	
		}
		else
		{
			return 0;		
		}
	}

	public function packageupgrade($post,$company_id)
	{
		$upgrade_type = $post['upgrade_type'];

		if(isset($post['upgrade_userid']))
		{
			$upgrade_userid = $post['upgrade_userid'];
		}
		else
		{
			$upgrade_userid = $_SESSION['userid'];
		}

		$upgrade_packid = $post['pack'];			
		$get_packagedetails = $this->payment_packagedetails($upgrade_packid);
		$package_name = $get_packagedetails[0]['package_name']; 
		$no_of_taxi = $get_packagedetails[0]['no_of_taxi']; 
		$no_of_driver = $get_packagedetails[0]['no_of_driver']; 
		$days = $get_packagedetails[0]['days_expire'];   
		$amount = $get_packagedetails[0]['package_price'];  
 		$package_type = $get_packagedetails[0]['package_type'];  
		$userid = $_SESSION['userid'];	

		$time_zone = $this->company_timezone($company_id);

		if($time_zone == 0)
		{
			$time_zone = TIMEZONE;	
		}

		$upgrade_packagelist = " select * from " . PACKAGE_REPORT . "  where ".PACKAGE_REPORT.".upgrade_companyid = '$company_id' order by upgrade_expirydate  DESC";

		$upgrade_result = Db::query(Database::SELECT, $upgrade_packagelist)
				->execute()
				->as_array();

		if(count($upgrade_result) > 0)

		{
			$last_expirydate = $upgrade_result[0]['upgrade_expirydate'];
		}
		else
		{
			$last_expirydate =  date('Y-m-d H:i:s');
		}

		$last_expirydate = convert_timezone('now',$time_zone);


		if($upgrade_type == 1)
		{
			if($days > 0)
			{
				$expirydate = Commonfunction::getExpiryTimeStamp($last_expirydate,$days);
				$now =  $expirydate;
			}
			else
			{
				$now =  $expirydate = $last_expirydate;
			}
			

			$result = DB::insert(PACKAGE_REPORT, array('upgrade_companyid','upgrade_packageid','upgrade_packagename','upgrade_no_taxi','upgrade_no_driver','upgrade_startdate','upgrade_expirydate','upgrade_ack','upgrade_capture','upgrade_amount','upgrade_type','upgrade_by','check_package_type'))->values(array($company_id,$upgrade_packid,$package_name,$no_of_taxi,$no_of_driver,$now,$expirydate,'Success','1',$amount,'D',$userid,$package_type))->execute();			
		}
		else
		{

			if($days > 0)
			{
				$expirydate = Commonfunction::getExpiredTimeStamp($last_expirydate,$days);
				$now =  $expirydate;
			}
			else
			{
				$now =  $expirydate = $last_expirydate;
			}

			$result = DB::insert(PACKAGE_REPORT, array('upgrade_companyid','upgrade_packageid','upgrade_packagename','upgrade_no_taxi','upgrade_no_driver','upgrade_startdate','upgrade_expirydate','upgrade_ack','upgrade_capture','upgrade_amount','upgrade_type','upgrade_by','check_package_type'))->values(array($company_id,$upgrade_packid,$package_name,$no_of_taxi,$no_of_driver,$last_expirydate,$expirydate,'Success','1',$amount,'D',$userid,$package_type))->execute();
							
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

	public function validate_package_assigntaxi($cid)
	{

			$current_time = convert_timezone('now',TIMEZONE);

			$total_query ="SELECT people.id ,(select upgrade_no_taxi from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as no_taxi,(select check_package_type from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM people WHERE user_type='C' and company_id ='$cid' group by people.id Having ( check_package_type = 'T' or upgrade_expirydate >='$current_time' )";

 		$total_result = Db::query(Database::SELECT, $total_query)
	   			 ->execute()
				 ->as_array();

		if(count($total_result) > 0)
		{
			return 1;
		}
		return 0;

	}

	public function validate_packagetaxi($cid)
	{			
		$current_time = convert_timezone('now',TIMEZONE);

		$total_query ="SELECT people.id ,(select upgrade_no_taxi from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as no_taxi,(select check_package_type from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM people WHERE user_type='C' and company_id ='$cid' group by people.id Having ( check_package_type = 'T' or upgrade_expirydate >='$current_time' )";

		//$total_query = " select (upgrade_no_taxi) as no_taxi,check_package_type.upgrade_expirydate from " . PACKAGE_REPORT . "  where ".PACKAGE_REPORT.".upgrade_companyid = '$cid'  and (upgrade_expirydate >= now() or check_package_type ='T') order by upgrade_id desc limit 0,1";
		//	$total_query = " select (upgrade_no_taxi) as no_taxi,upgrade_expirydate from " . PACKAGE_REPORT . "  where ".PACKAGE_REPORT.".upgrade_companyid = '$cid'  and (upgrade_expirydate >= now() or check_package_type ='T') order by upgrade_id desc limit 0,1";

		$total_result = Db::query(Database::SELECT, $total_query)
					 ->execute()
					 ->as_array();


		$added_query = " select count(taxi_id) as taxi_count from " . TAXI . "  where taxi_company = '$cid' and taxi_availability = 'A'";
		$added_result = Db::query(Database::SELECT, $added_query)
					 ->execute()
					 ->as_array();

		if(count($total_result) > 0)
		{	
			if($total_result[0]['check_package_type'] == 'T')	
			{
				$taxi_add = 1;
			}
			else
			{
				$taxi_add = $total_result[0]['no_taxi']-$added_result[0]['taxi_count'];
			}
		}
		else
		{
			$taxi_add = 0;
		}	

		return $taxi_add;			 

	}
	
	public function validate_packagedriver($cid)
	{
		$current_time = convert_timezone('now',TIMEZONE);

		$total_query ="SELECT people.id ,(select upgrade_no_driver from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as no_driver,(select check_package_type from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM people WHERE user_type='C' and company_id ='$cid' group by people.id Having ( upgrade_expirydate >='$current_time' )";//check_package_type = 'T' or 
			
		//$total_query = " select (upgrade_no_driver) as no_driver,check_package_type from " . PACKAGE_REPORT . "  where ".PACKAGE_REPORT.".upgrade_companyid = '$cid'  and (upgrade_expirydate >= now() or check_package_type ='T') order by upgrade_id desc limit 0,1";
		$total_result = Db::query(Database::SELECT, $total_query)
					 ->execute()
					 ->as_array();
				
		$added_query = " select count(id) as driver_count from " . PEOPLE . "  where company_id = '$cid' and user_type='D' and availability_status='A'";
		$added_result = Db::query(Database::SELECT, $added_query)
					 ->execute()
					 ->as_array();

		if(count($total_result) > 0)
		{
			if($total_result[0]['check_package_type'] == 'T')	
			{
				$driver_add = 1;
			}		
			else
			{
				$driver_add = $total_result[0]['no_driver']-$added_result[0]['driver_count'];
			}
		}
		else
		{
			$driver_add = 0;
		}			 
		return $driver_add;		 
	}
	
	public function check_package_expiry($cid)
	{

		$current_time = convert_timezone('now',TIMEZONE);
		$total_query ="SELECT people.id ,(select upgrade_no_driver from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as no_driver,(select check_package_type from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM people WHERE user_type='C' and company_id ='$cid' group by people.id Having ( upgrade_expirydate >='$current_time' )";//check_package_type = 'T' or 
		$total_result = Db::query(Database::SELECT, $total_query)->execute()->as_array();
	 
		return count($total_result);			 

	}
		
	/** validate add contents **/
	public function validate_addcontents($arr)
	{
		return Validation::factory($arr)		
		->rule('meta_title', 'not_empty')
		->rule('meta_keyword', 'not_empty')
		->rule('meta_description', 'not_empty')
		->rule('menu_name', 'not_empty');
	}
	
	/** inserting the contents **/
	public function addcontents($post)
	{ 
		//$string = preg_replace('/\s+/', '', $post['menu']);
		//$link = 'users/'.strtolower($string);		
		$result =DB::select()->from(MENU)
					->where(MENU.'.menu_id','=',$post['menu_name'])
					->execute()
					->as_array();		
		if(count($result)>0)
		{
			$menu_name = $result[0]['menu_name'];
		}	
		else
		{
			$menu_name = "";
		}

		$result = DB::insert(CMS, array('menu_id','menu','meta_title','meta_keyword','meta_description','content','type','status'))
				->values(array($post['menu_name'],$menu_name,$post['meta_title'],$post['meta_keyword'],$post['meta_description'],$post['content'],'1','1'))
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
	
	//Check the menu name already exists while adding a content
	public function menu_content_exits($post)
	{
		  $result =DB::select()->from(CMS)
			->where(CMS.'.menu_id','=',$post['menu_name'])
			->execute()
			->as_array();
			
		  if($result)
		  {
		  	return 1;
		  }

	}

	//selected manus 
	public function get_menus()
	{
		$result =DB::select()->from(MENU)
		->order_by('order_status','ASC')
		->execute()
		->as_array();

	  return $result;
		   
	}

	public static function check_companyid($cid)
	{
		
		$result = DB::select()->from(COMPANY)->where('cid','=',$cid)->where('company_status','=','A')
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

	public function current_package($company_id)
	{
			
		$query = " select upgrade_packageid from " . PACKAGE_REPORT . "  where ".PACKAGE_REPORT.".upgrade_companyid = '$company_id'  order by upgrade_id desc limit 0,1";
			
			
	 			$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
				if(count($result) > 0)		 
				{
					return $result[0]['upgrade_packageid'];
				}
				else
				{
					return 0;		
				}
	
	}

	public static function countdriverassignedlist($driver_id='',$startdate='',$enddate='')
	{
		
		$driver_where ='';
		$cond_where = '';
		$startdate_where = '';
		$date_where = '';
		$enddate_where = '';
		$company_where = '';
		$manager_where = '';

		if($driver_id)
		{
			$driver_where = " AND mapping_driverid = '$driver_id'";	
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
		
		$query = " select * from " . TAXIMAPPING .  " left join " .PEOPLE. " on ".TAXIMAPPING.".mapping_driverid = ".PEOPLE.".id left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid = ".TAXI.".taxi_id  left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id where 1=1 $driver_where  $date_where order by mapping_startdate ASC ";


	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			return count($results);
	}

	public static function getdriverassignedlist($driver_id='',$startdate='',$enddate='',$offset='',$val='')
	{
		
		$driver_where ='';
		$cond_where = '';
		$startdate_where = '';
		$date_where = '';
		$enddate_where = '';
		$company_where = '';
		$manager_where = '';

		if($driver_id)
		{
			$driver_where = " AND mapping_driverid = '$driver_id'";	
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
		
		$query = " select * from " . TAXIMAPPING .  "  left join " .PEOPLE. " on ".TAXIMAPPING.".mapping_driverid = ".PEOPLE.".id left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid = ".TAXI.".taxi_id left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id where 1=1 $driver_where  $date_where order by mapping_startdate ASC limit $val offset $offset";


	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			return $results;
	}
	
	public function change_imagecount($taxi_id='',$image_id='')
	{
	
		$get_query = " select taxi_sliderimage from " . TAXI .  " where taxi_id=$taxi_id";
 		$get_result = Db::query(Database::SELECT, $get_query)
			   			 ->execute()
						 ->as_array();

		$get_slidercount = $get_result[0]['taxi_sliderimage'];
		
		if($get_slidercount > 0)
		{						 
			$updatequery = " UPDATE ". TAXI ." SET taxi_sliderimage=taxi_sliderimage-1 wHERE taxi_id = '$taxi_id' ";	
	 		$updateresult = Db::query(Database::UPDATE, $updatequery)
	   			 ->execute();
		}
		
		$array_query = " select taxi_serializeimage from " . TAXI .  " where taxi_id=$taxi_id";
 		$array_result = Db::query(Database::SELECT, $array_query)
			   			 ->execute()
						 ->as_array();

		$image_serialize = unserialize($array_result[0]['taxi_serializeimage']);	

		$remove_array = array();
		$remove_array[] = $image_id;  

		$update_array = array_diff($image_serialize, $remove_array);	


		$update_serialize = serialize($update_array);				 
			 
		$updatequery = " UPDATE ". TAXI ." SET taxi_serializeimage='$update_serialize' wHERE taxi_id = '$taxi_id' ";	
 		$updateresult = Db::query(Database::UPDATE, $updatequery)
   			 ->execute();
   			 
   			 
		$query = " select taxi_serializeimage from " . TAXI .  " where taxi_id=$taxi_id";
 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
		

		$count_image = unserialize($results[0]['taxi_serializeimage']);
		
		return  $count_image;
	}
	
	/**Validating for Add Menu**/
	public function validate_addmenu($arr) 
	{		
		//print_r($arr);
		//exit;
		return Validation::factory($arr)       
		
			->rule('menu_name', 'not_empty')
			->rule('menu_name', 'min_length', array(':value', '2'))
			->rule('menu_name', 'max_length', array(':value', '30'))
			
			->rule('slug','not_empty');
	}
	
	//To Add Menu Functionalities 
	public static function addmenu($post)
	{
		$status = $post['status_posts'];
		if($status=='Publish'){
			$status='P';
		}else if($status=='Unpublish'){
			$status='U';
		}
		
		$sql="select menu_id from ".MENU." order by menu_id DESC";
		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
		if(!empty($results[0]['menu_id'])){
			$id = $results[0]['menu_id'];
		}else{
			$id = 0;
		}
		
		if($id > 0){
			$result = DB::insert(MENU, array('menu_name','menu_link','status_post','order_status'))
				->values(array($post['menu_name'],$post['slug'],$status,$id+1))
				->execute();
		}
		else
		{
			$result = DB::insert(MENU, array('menu_name','menu_link','status_post','order_status'))
				->values(array($post['menu_name'],$post['slug'],$status,'1'))
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
	
	//Check the menu already exists
	public function menu_name_exits($post)
	{
		  $result =DB::select()->from(MENU)
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

	public function packagecount($cid)
	{
		$result = DB::select()->from(PACKAGE_REPORT)->where(PACKAGE_REPORT.'.upgrade_companyid', '=', $cid)
			->execute()
			->as_array();
			
			return count($result);
	}
	
	/**Validating for Add Mile**/
	public function validate_addmile($arr) 
	{		
		//print_r($arr);
		//exit;
		return Validation::factory($arr)       
		
			->rule('mile', 'not_empty')
			->rule('mile', 'digit')
			->rule('mile', 'max_length', array(':value', '30'));
	}
	
	//To Add Menu Functionalities 
	public static function addmile($post)
	{		
		$result = DB::insert(MILES, array('mile_name'))
			->values(array($post['mile']))
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
	public function mile_name_exits($post)
	{
		  $result =DB::select()->from(MILES)
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
	
	public static function check_evening_fare($night_fare)
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
	
	public static function check_valid_phone_number($phone,$regex)
	{
		if(preg_match($regex,$phone))
		{		
			return false;
		}
		else
		{
			return true;
		}
	}
	
	    /** validating the banners images **/
        public function validate_addbanner($arr="",$files_value_array="")
        {
            return Validation::factory($arr)     
			->rule('tags', 'not_empty')
			->rule('image_tag', 'not_empty')
			->rule('file', 'Upload::not_empty',array($files_value_array['banner_image']))
			->rule('file', 'Upload::type', array($files_value_array['banner_image'], array('jpg','jpeg', 'png', 'gif')))
			->rule('file', 'Upload::size', array($files_value_array['banner_image'],'2M'));

        }
        
        /** Updating the banner images **/
        public function update_banner($tag,$image_tag,$path1,$id)
        {

		$banner_image=DB::insert(COMPANY_CMS, array('company_id','image_tag','alt_tags','banner_image','type'))
		->values(array($id,"$image_tag","$tag","$path1",2))
		->execute();
		return $banner_image;
        }
        
        /** Validating Faq **/
        public function validate_addfaq($arr) 
		{
			$validation=Validation::factory($arr)       

			->rule('faq_title', 'not_empty')
			->rule('faq_title', 'Model_Add::checkfaqtitle', array(':value'))
			->rule('faq_details', 'not_empty');
		    return $validation;
		}
		
		public static function addfaq($post)
		{
			$result = DB::insert(PASSENGERS_FAQ, array('faq_title','faq_details','status'))
						->values(array($post['faq_title'],$post['faq_details'],'A'))
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

		public static function check_value_zero($value)
		{
			if($value=="0")
			{		
				return false;
			}
			else
			{
				return true;
			}
		}
		
		
		/**Validating for Create Login**/
		
	public function validate_createlogin($arr) 
	{
		return Validation::factory($arr)       
		
			->rule('firstname', 'not_empty')
			->rule('firstname', 'Model_Add::checkname', array(':value'))
			->rule('firstname', 'min_length', array(':value', '4'))
			->rule('firstname', 'max_length', array(':value', '30'))
			->rule('lastname', 'not_empty')
			->rule('no_of_login', 'not_empty')
			->rule('company_id', 'not_empty')
			->rule('phone', 'numeric')
			->rule('phone', 'not_empty')
			->rule('phone', 'contact_phone', array(':value'))
			->rule('phone', 'min_length', array(':value', '8'))
			->rule('phone', 'Model_Add::checkphone_autocreate', array(':value'));

	}
	
		public static function checkname($firstname="")
	{


		$result = DB::select('name')->from(PEOPLE)->where('name','=',$firstname."1")
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
	
		public static function create_login($post)
		{
			///print_r($post);
			 $no_of_login = $post['no_of_login'];
			 $commMdl = Model::factory('commonmodel');
			 $companyDets = $commMdl->get_country_details($post['company_id']);
			 if(count($companyDets) > 0) {
				 $post['country'] = $companyDets[0]['login_country'];
				 $post['state'] = $companyDets[0]['login_state'];
				 $post['city'] = $companyDets[0]['login_city'];
			}
			for($i=1;$i<=$no_of_login;$i++)
			{
			
				$password = Html::chars(md5($post['password']));
				$user_createdby = $_SESSION['userid']; 
				$mapping_createdby = $_SESSION['userid'];
				$cid = $post['company_name'];
				$email = $post['firstname'].$i."@getuptaxi.com";
				$name = $post['firstname'].$i;
				$phone = ($no_of_login > 1) ? $post['phone'].$i : $post['phone'];
				$taxi_no = "TX".$post['phone'].$i;
			
			
			$current_date = date('Y-m-d H:i:s', time());
			$result = DB::insert(PEOPLE, array('salutation','name','address','lastname','gender','dob','email','phone','password','org_password','created_date','user_type','status','user_createdby','login_country','login_state','login_city','company_id','driver_license_id','booking_limit',))
					->values(array($post['salutation'],$name,'USA',$post['lastname'],'Male','1990-05-05',$email,$phone,$password,$post['password'],$current_date,'D',ACTIVE,$user_createdby,$post['country'],$post['state'],$post['city'],
					$post['company_id'],$phone,$post['booking_limit']))
					
				->execute();
		 $driver_id = $result[0];
		
		$latitude="34.0500";
		$longitude="-118.2500";
		
			$result = DB::insert(DRIVER, array('driver_id','latitude','longitude','status','shift_status'))
					->values(array($driver_id,$latitude,$longitude,'F','OUT'))
					->execute();
												/** Taxi added**/
				
			$result = DB::insert(TAXI, array('taxi_no','taxi_type','taxi_model','taxi_company','taxi_owner_name','taxi_manufacturer','taxi_colour','taxi_motor_expire_date', 'taxi_insurance_number','taxi_insurance_expire_date_time','taxi_pco_licence_number','taxi_pco_licence_expire_date','taxi_country', 'taxi_state','taxi_city','taxi_capacity','taxi_speed','max_luggage','taxi_fare_km','taxi_createdby','taxi_status','taxi_image'))
					->values(array($taxi_no,'1',$post['taxi_model'],$post['company_id'],'Ndot','Ndot','red','2018-05-05',$phone,'2018-05-05 00:00:00','2018-05-05','2018-05-05',$post['country'],$post['state'],$post['city'],'5','80','8','80',$current_date,ACTIVE,''))
					->execute();
			$taxi_id = $result[0];
			
													/** Taxi Mapping**/
			
			$result = DB::insert(TAXIMAPPING, array('mapping_driverid','mapping_taxiid','mapping_companyid','mapping_countryid','mapping_stateid','mapping_cityid','mapping_startdate','mapping_enddate','mapping_status','mapping_createdby'))
					->values(array($driver_id,$taxi_id,$post['company_id'],$post['country'],$post['state'],$post['city'],$post['start_booking_date'],$post['end_booking_date'],ACTIVE,$mapping_createdby))
					->execute();
					
			$driverReferralCode = text::random($type = 'alnum', $length = 6);
				$driverRef = DB::insert(DRIVER_REF_DETAILS, array('registered_driver_id','registered_driver_code','registered_driver_code_amount'))->values(array($driver_id,$driverReferralCode,DRIVER_REF_AMOUNT))->execute();
					
					
														/** Add Passenger**/	
				
			$result = DB::insert(PASSENGERS, array('salutation','name','lastname','email','password','org_password','phone','creditcard_no','creditcard_cvv','expdatemonth','expdateyear','activation_status','login_status','user_status','passenger_cid'))
					->values(array($post['salutation'],$name,$post['lastname'],$email,$password,$post['password'],$phone,'bmRvdGVuY3JpcHRfNDU1NjM2ODM3MDg4NDQ3MQ==','567','01','2020','1','N','A',$post['company_id']))
					->execute();
			$passenger_id = $result[0];
					
													/** Passenger Credit card **/	
					
			$result = DB::insert(PASSENGERS_CARD_DETAILS, array('passenger_id','passenger_email','card_type','creditcard_no','creditcard_cvv','expdatemonth','expdateyear','default_card','createdate'))
					->values(array($passenger_id,$email,'P','bmRvdGVuY3JpcHRfNDExMTExMTExMTExMTExMQ==','567','5','2020','1',$current_date))
					->execute();
				
				
		}	
				
		
		
			
		}
		public static function view_login($post)
		{
				$no_of_login = $post['no_of_login'];
			 
				  //$driver_id = $post['phone'].$i;
		        $query = "select name,phone,org_password from ".PEOPLE." ORDER BY id DESC LIMIT $no_of_login ";
		        $driver_details = Db::query(Database::SELECT, $query)
				->execute()			
				->as_array();
				   
				return $driver_details;
						
		}
		public static function view_passengerlogin($post)
		{
				$no_of_login = $post['no_of_login'];
			 
				  //$driver_id = $post['phone'].$i;
		        $query = "select name,phone,org_password from ".PASSENGERS." ORDER BY id DESC LIMIT $no_of_login ";
		        $passenger_details = Db::query(Database::SELECT, $query)
				->execute()			
				->as_array();
				   
				return $passenger_details;
						
		}
		
			// Check driver licence Id is Already Exist or Not
		public static function checklicenceId($value)
		{
			$result = DB::select(array( DB::expr('COUNT(id)' ), 'total'))->from(PEOPLE)->where('driver_license_id','=',$value)->execute()->get('total');
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
		public static function checkpcolicenceNo($value)
		{
			$result = DB::select(array( DB::expr('COUNT('.PEOPLE.'.id)' ), 'total'))->from(PEOPLE)->join(DRIVER_INFO,'LEFT')->on(DRIVER_INFO.'.driver_id','=',PEOPLE.'.id')->where(DRIVER_INFO.'.driver_pco_license_number','=',$value)->execute()->get('total');
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
		public static function checkinsuranceNo($value)
		{
			$result = DB::select(array( DB::expr('COUNT('.PEOPLE.'.id)' ), 'total'))->from(PEOPLE)->join(DRIVER_INFO,'LEFT')->on(DRIVER_INFO.'.driver_id','=',PEOPLE.'.id')->where(DRIVER_INFO.'.driver_insurance_number','=',$value)->execute()->get('total');
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
		public static function checkNationalinsuranceNo($value)
		{
			$result = DB::select(array( DB::expr('COUNT('.PEOPLE.'.id)' ), 'total'))->from(PEOPLE)->join(DRIVER_INFO,'LEFT')->on(DRIVER_INFO.'.driver_id','=',PEOPLE.'.id')->where(DRIVER_INFO.'.driver_national_insurance_number','=',$value)->execute()->get('total');
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
		public static function check_taxinsurance_number($number)
		{	
			$result = DB::select(array(DB::expr('COUNT(taxi_id)' ), 'total'))->from(TAXI)->where('taxi_insurance_number','=',$number)->execute()->get('total');			
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
		public static function check_taxipco_number($number)
		{	
			$result = DB::select(array(DB::expr('COUNT(taxi_id)' ), 'total'))->from(TAXI)->where('taxi_pco_licence_number','=',$number)->execute()->get('total');			
				if($result > 0)
				{
					return false;
				}
				else
				{
					return true;		
				}
		}
		// To Check package setup already exist ( that means same no of taxi, no of driver, price and days to expire )
		public static function checkpackageExists($packageprice,$nooftaxi,$noofdriver,$days,$uid)
		{
			// Check if the username already exists in the database
			$result = DB::select(array(DB::expr('COUNT(package_id)' ), 'total'))->from(PACKAGE)->where('package_price','=',$packageprice)->where('no_of_taxi','=',$nooftaxi)->where('no_of_driver','=',$noofdriver)->where('days_expire','=',$days);
			if($uid != '') {
				$result->where('package_id','!=',$uid);
			}
			$out = $result->execute()->get('total');	
				
				if($out > 0)
				{
					return false;
				}
				else
				{
					return true;		
				}
		}
	
		public function smtp_settings(){
			
			$smtp_result = DB::select('smtp')
							->from(SMTP_SETTINGS)
							->where('id', '=', '1')
							->execute()
							->as_array();
			return !empty($smtp_result) ? $smtp_result : array();
		}
		//Company Brand Type
		public function getCompanyBrand($companyId)
		{

			$companyBrand = DB::select('company_brand_type')->from(COMPANYINFO)->where('company_cid', '=', $companyId)->execute()->get('company_brand_type');
			return $companyBrand;
		}
		
		//Coupon management --> add
		public function getcouponcode()
		{
			$coupon_code = "";
			
			//$result = DB::select()->from( DRIVERS_COUPON )->execute()->as_array();
			//if( !empty( $result ) )
			//{
				//$coupon_code_query = 'SELECT FLOOR( RAND() * 9999999999 ) AS coupon_code FROM drivers_coupon WHERE "coupon_code" NOT IN (SELECT coupon_code FROM drivers_coupon)';
				$coupon_code_query = "select concat(substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1)) as coupon_code1 from drivers_coupon Having NOT EXISTS (select coupon_code from drivers_coupon having coupon_code1=coupon_code) limit 1";
				
				$coupon_code_result = Db::query(Database::SELECT, $coupon_code_query)->execute()->as_array();
				if(count($coupon_code_result) > 0)
					$coupon_code = $coupon_code_result[0]['coupon_code1'];
				else
				{
					$coupon_code_query = "select concat(substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1)) as coupon_code";
					$coupon_code_result = Db::query(Database::SELECT, $coupon_code_query)->execute()->as_array();
					if(count($coupon_code_result) > 0)
						$coupon_code = $coupon_code_result[0]['coupon_code'];
				}
			//}
			/*else
			{
				//$coupon_code_query = 'SELECT FLOOR( RAND() * 9999999999 ) AS coupon_code';
				$coupon_code_query = "SELECT concat(substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1)) AS coupon_code";
				$coupon_code_result = Db::query(Database::SELECT, $coupon_code_query)->execute()->as_array();
				if(count($coupon_code_result) > 0)
					$coupon_code = $coupon_code_result[0]['coupon_code'];
			}*/
			return $coupon_code;
	}
	
	//Validate generate coupon form values
	public function generate_coupon_validate( $arr ) 
	{
		return Validation::factory( $arr )
						->rule( 'coupon_count','not_empty')
						->rule( 'coupon_count', 'numeric')
						->rule( 'coupon_count', 'regex', array( ':value', "/^[0-9]+$/" ) )
						->rule( 'coupon_count', 'max_length', array( ':value', 3 ) )
						->rule( 'start_date','not_empty')
						->rule( 'expire_date','not_empty');
	}
	
	//Add driver coupon details 
	public function add_driver_coupons( $values )
	{
		$coupon_count = $values['coupon_count'];
		$coupon_list = array();
		$current_date = date('Y-m-d H:i:s');
		$serial_number = null;
		for( $cnt = 0;$cnt < $coupon_count;$cnt++ )
		{
			$coupon_exists = '';
			
			/*while($coupon_exists == '')
			{
				$coupon_code = mt_rand(1000000000, 9999999999);
				$coupon_cnt = DB::select(array(DB::expr('COUNT(coupon_code)' ), 'coupon_count'))->from(DRIVERS_COUPON)->where('coupon_code','=',$coupon_code)->execute()->get('coupon_count');
				if($coupon_cnt == 0)
				{
					$coupon_exists = 1;
				}
			}*/
			while($coupon_exists == '')
			{
				//$coupon_code = mt_rand(1000000000, 9999999999);
				//$coupon_code = bin2hex(openssl_random_pseudo_bytes(5));
				$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
				$coupon_code_arr = array(); //remember to declare $pass as an array
				$coupon_code_str = '';
				$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
				for ($i = 0; $i < 10; $i++) {
					$n = rand(0, $alphaLength);
					$coupon_code_str .= substr($alphabet, $n, 1);
				}
				$coupon_code = $coupon_code_str; 
				
				$coupon_cnt = DB::select(array(DB::expr('COUNT(coupon_code)' ), 'coupon_count'))->from(DRIVERS_COUPON)->where('coupon_code','=',$coupon_code)->execute()->get('coupon_count');
				if($coupon_cnt == 0)
				{
					$coupon_exists = 1;
				}
			}

			$site_info = DB::select('site_currency', 'email_id', 'phone_number')->from(SITEINFO)->limit(1)->execute()->as_array();

			$last_insert_id = DB::select(array(DB::expr('coupon_id' ), 'last_insert_id'))
			                    ->from(DRIVERS_COUPON)
			                    ->order_by('coupon_id', 'DESC')
			                    ->limit(1)
			                    ->execute()
			                    ->get('last_insert_id');

			$serial_number = $last_insert_id + 1;			                    

			$result = DB::insert( DRIVERS_COUPON, array('generate_id', 'coupon_code','coupon_name','amount','coupon_used', 'start_date', 'expiry_date', 'coupon_status', 'coupon_count', 'serial_number', 'created_date' ))
							->values( array( $values['generate_id'], $coupon_code, $values['coupon_name'], $values['coupon_amt'], 0, $values['start_date'], $values['expire_date'], 'A', $values['coupon_count'], $serial_number, $current_date) )
							->execute();
							
				
			$serial_number = str_pad($serial_number, 9, "0", STR_PAD_LEFT);	
			$coupon_list[] = array("coupon_code" => $coupon_code,
								   "coupon_amt" => $values['coupon_amt'],
								   "serial_number" => $serial_number,
								   "site_currency" => $site_info[0]['site_currency'],
								   "email_id" => $site_info[0]['email_id'],
								   "phone_number" => $site_info[0]['phone_number'],
							 );
		}
		//return 1;							
		return $coupon_list;		
	}
	
	
	
	public function passsenger_getcouponcode()
		{
			$coupon_code = "";
			
			//$result = DB::select()->from( DRIVERS_COUPON )->execute()->as_array();
			//if( !empty( $result ) )
			//{
				//$coupon_code_query = 'SELECT FLOOR( RAND() * 9999999999 ) AS coupon_code FROM drivers_coupon WHERE "coupon_code" NOT IN (SELECT coupon_code FROM drivers_coupon)';
				$coupon_code_query = "select concat(substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1)) as coupon_code1 from passengers_coupon Having NOT EXISTS (select coupon_code from passengers_coupon having coupon_code1=coupon_code) limit 1";
				
				$coupon_code_result = Db::query(Database::SELECT, $coupon_code_query)->execute()->as_array();
				if(count($coupon_code_result) > 0)
					$coupon_code = $coupon_code_result[0]['coupon_code1'];
				else
				{
					$coupon_code_query = "select concat(substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1)) as coupon_code";
					$coupon_code_result = Db::query(Database::SELECT, $coupon_code_query)->execute()->as_array();
					if(count($coupon_code_result) > 0)
						$coupon_code = $coupon_code_result[0]['coupon_code'];
				}
			//}
			/*else
			{
				//$coupon_code_query = 'SELECT FLOOR( RAND() * 9999999999 ) AS coupon_code';
				$coupon_code_query = "SELECT concat(substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1),substring('0123456789', rand()*10+1, 1)) AS coupon_code";
				$coupon_code_result = Db::query(Database::SELECT, $coupon_code_query)->execute()->as_array();
				if(count($coupon_code_result) > 0)
					$coupon_code = $coupon_code_result[0]['coupon_code'];
			}*/
			return $coupon_code;
	}
	
	//Validate generate coupon form values
	public function passenger_generate_coupon_validate( $arr ) 
	{
		return Validation::factory( $arr )
						->rule( 'coupon_count','not_empty')
						->rule( 'coupon_count', 'numeric')
						->rule( 'coupon_count', 'regex', array( ':value', "/^[0-9]+$/" ) )
						->rule( 'coupon_count', 'max_length', array( ':value', 3 ) )
						->rule( 'start_date','not_empty')
						->rule( 'expire_date','not_empty');
	}
	
	//Add driver coupon details 
	public function passenger_add_driver_coupons( $values )
	{
		$coupon_count = $values['coupon_count'];
		$coupon_list = array();
		$current_date = date('Y-m-d H:i:s');
		$serial_number = null;
		for( $cnt = 0;$cnt < $coupon_count;$cnt++ )
		{
			$coupon_exists = '';
			
			/*while($coupon_exists == '')
			{
				$coupon_code = mt_rand(1000000000, 9999999999);
				$coupon_cnt = DB::select(array(DB::expr('COUNT(coupon_code)' ), 'coupon_count'))->from(DRIVERS_COUPON)->where('coupon_code','=',$coupon_code)->execute()->get('coupon_count');
				if($coupon_cnt == 0)
				{
					$coupon_exists = 1;
				}
			}*/
			while($coupon_exists == '')
			{
				//$coupon_code = mt_rand(1000000000, 9999999999);
				//$coupon_code = bin2hex(openssl_random_pseudo_bytes(5));
				$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
				$coupon_code_arr = array(); //remember to declare $pass as an array
				$coupon_code_str = '';
				$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
				for ($i = 0; $i < 10; $i++) {
					$n = rand(0, $alphaLength);
					$coupon_code_str .= substr($alphabet, $n, 1);
				}
				$coupon_code = $coupon_code_str; 
				
				$coupon_cnt = DB::select(array(DB::expr('COUNT(coupon_code)' ), 'coupon_count'))->from(PASSENGERS_COUPON)->where('coupon_code','=',$coupon_code)->execute()->get('coupon_count');
				if($coupon_cnt == 0)
				{
					$coupon_exists = 1;
				}
			}

			$site_info = DB::select('site_currency', 'email_id', 'phone_number')->from(SITEINFO)->limit(1)->execute()->as_array();

			$last_insert_id = DB::select(array(DB::expr('coupon_id' ), 'last_insert_id'))
			                    ->from(PASSENGERS_COUPON)
			                    ->order_by('coupon_id', 'DESC')
			                    ->limit(1)
			                    ->execute()
			                    ->get('last_insert_id');

			$serial_number = $last_insert_id + 1;			                    

			$result = DB::insert( PASSENGERS_COUPON, array('generate_id', 'coupon_code','coupon_name','amount','coupon_used', 'start_date', 'expiry_date', 'coupon_status', 'coupon_count', 'serial_number', 'created_date' ))
							->values( array( $values['generate_id'], $coupon_code, $values['coupon_name'], $values['coupon_amt'], 0, $values['start_date'], $values['expire_date'], 'A', $values['coupon_count'], $serial_number, $current_date) )
							->execute();
							
				
			$serial_number = str_pad($serial_number, 9, "0", STR_PAD_LEFT);	
			$coupon_list[] = array("coupon_code" => $coupon_code,
								   "coupon_amt" => $values['coupon_amt'],
								   "serial_number" => $serial_number,
								   "site_currency" => $site_info[0]['site_currency'],
								   "email_id" => $site_info[0]['email_id'],
								   "phone_number" => $site_info[0]['phone_number'],
							 );
		}
		//return 1;							
		return $coupon_list;		
	}
		
}
