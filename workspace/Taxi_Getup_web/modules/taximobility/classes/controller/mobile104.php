<?php defined('SYSPATH') or die('No direct script access.');

/****************************************************************

* website controller - Contains abstract class of API (Version 6.0.0)

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************************************/

abstract class Controller_Mobile104 extends Controller
{	
	//Default variables
	public $template="themes/template";
	public $alllanguage;
	public $success_msg;		
	public $failure_msg;
	public $script;
	public $style;
	public $curr_lang;	
	public $session_instance;
	public $userid;
	public $user_name;
	public $user_email;	
	public $user_type;
	public $user_paypal_account;
	public $all_countries;
	public $user_shipping;
	public $other_shipping;
	public $gig_alt_name;
	public $replace_variables;
	public $site_settings; 
	public $job_settings; 
	public $selected_theme;
	public $page_title;
	public $miles;
	


	/**
	****__construct()****
	*/
	public function __construct(Request $request, Response $response)
	{

		$controller = $request->controller();
		$action = $request->action();
 
		// Assign the request to the controller
		$this->request = $request;

		// Assign a response to the controller
		$this->response = $response;	       
		//Session instance
		$this->session = Session::instance();
		
		$this->urlredirect=Request::current();		

		$this->lang =$this->session->get('lang');
		if($this->lang !=""){
			$lang=$this->lang;
		}else{
			$lang="en";
		}


        $this->userid='';

		//Css & Script include for admin
		/**To Define path for selected theme**/
		

	
		$id =$this->session->get('id');
		$usertype =$this->session->get('usertype');
		//$userid = $this->session->get('userid');
		$usrid =$id ;// isset($userid)?$userid:$id;
		
		DEFINE("SITENAME",$this->app_name);
		

		//View::bind_global('currency_symbol',$this->currency_symbol);
		View::bind_global('success_msg', $this->success_msg);
		View::bind_global('failure_msg', $this->failure_msg );

		View::bind_global('app_name',$this->app_name);
		//View::bind_global('currencysymbol',$this->currencysymbol);
		View::bind_global('siteemail',$this->siteemail);
		
	
		View::bind_global('action', $action );
		View::bind_global('controller', $controller);
        	
		View::bind_global('data', $_POST);
	    $ip=$_SERVER['REMOTE_ADDR'];	
			$ip =IPADDRESS; 
			
	}



	/**
	 * ***action_array_keys_exists()****
	 * ** User Defined Function **
	 * @return check array exist otr not
	 */

	
	/**
	*****action_network_activity()****
	*@purpose of linkdin curl function
	*/

		/** SEND GRID FUNCTION **/
		
		public function sendgrid($host = array(), $from = "", $receiver = array(), $subject = "", $message = "")
		{
			include MODPATH."/email/swift/lib/swift_required.php";
			include_once MODPATH."/email/swift/SmtpApiHeader.php";
			$hdr = new SmtpApiHeader();
			$times = array();
			$names = array();
			 
			$hdr->addFilterSetting('subscriptiontrack', 'enable', 1);
			$hdr->addFilterSetting('twitter', 'enable', 1);

			$hdr->addTo($receiver);

			 
			$hdr->addSubVal('-time-', $times);
			$hdr->addSubVal('-name-', $names);
			$hdr->setUniqueArgs(array());

			$sitename = "Sayboard";
			if(!$sitename){
				$sitename = $_SERVER['HTTP_HOST'];
			}
			$fromEmail = $from;
			if(!$fromEmail){
				$fromEmail = "noreply@".$_SERVER['HTTP_HOST'];
			}
				 
			$from = array($fromEmail => $sitename );

			$to = array('defaultdestination@example.com' => 'Personal Name Of Recipient');
			$text = "test text..";
			$html = $message;

			
			$transport = Swift_SmtpTransport::newInstance($host['host'], $host['port']);

			$transport ->setUsername($host['uname']);

			$transport ->setPassword($host['password']);
			$swift = Swift_Mailer::newInstance($transport);
			 
			$message = new Swift_Message($subject);
			$headers = $message->getHeaders();

			$headers->addTextHeader('X-SMTPAPI', $hdr->asJSON());
			
			$message->setFrom($from);
			$message->setBody($html, 'text/html');
			$message->setTo($to);
			$message->addPart($text, 'text/plain');
			
			if ($recipients = $swift->send($message, $failures)){
			  //common::message(1, "Message sent out to ".$recipients." users");
			}
			else{
			  //common::message(-1, "Something went wrong - Try Later");
			}
			return;
		}
			
	 
	 
	/**
	 * ****action_currenttimestamp()****
	 * @return time format
	 */
	public function currenttimestamp()
	{
		return date("Y:m:d H:i:s",time());		
	}

	//Search validation
	function search_validation($search_array)
	{
			return Validation::factory($search_array)
				->rule('latitude','not_empty')
				->rule('longitude','not_empty')				
				->rule('pickup_time','not_empty');	
	}


	//Passenger Login Validation
	function passenger_login_validation($array)
	{
		 return Validation::factory($array)
				->rule('phone','not_empty')			
				//->rule('email','email')				
				->rule('phone_number','numeric')				
				->rule('password','not_empty');
				//->rule('devicetoken','not_empty');
	}
		
	
	//Passenger Edit Profile Validation
	function passenger_profile_validation($array)
	{
		 return Validation::factory($array)
				->rule('salutation','not_empty')
				->rule('firstname','not_empty')
				->rule('lastname','not_empty');
				//->rule('otp','not_empty');				
				//->rule('phone','not_empty')				
				//->rule('phone','numeric')				
				//->rule('email','email')
				//->rule('email','not_empty')
				//->rule('address','not_empty');
	}
	//Passenger Edit Profile Validation
	function edit_passenger_profile_validation($array)
	{
		 return Validation::factory($array)
				->rule('firstname','not_empty')
				->rule('lastname','not_empty')
				->rule('email','not_empty')		
				//->rule('email','email')		
				->rule('phone','not_empty');
				//->rule('phone','numeric');
				//->rule('password','min_length', array(':value', '6'));
	}
	//Payment Validation
	//index/?type=tripfare_updateby_passenger&trip_id=1&distance=2.6&actual_distance=&actual_amount=120&total_fare=120&remarks=test driviing&nightfare_applicable=1&nightfare=12.50	
	function payment_validation($array)
	{
		 return Validation::factory($array)
				->rule('trip_id','not_empty')
				->rule('distance','not_empty')
				->rule('fare','not_empty');	
	}
	// For card paymement Validation
	//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=payment_updateby_driver&trip_id=165&distance=25&actual_distance=&actual_amount=120&total_fare=120&remarks=test%20driviing&nightfare_applicable=1&nightfare=12.50&creditcard_no=&creditcard_cvv=&expmonth=&expyear=&pay_mod_id=1
	function payment_validationwith_card($array)
	{
		 return Validation::factory($array)
				->rule('trip_id','not_empty')
				->rule('distance','not_empty')
				->rule('fare','not_empty')	
				->rule('creditcard_no','not_empty')
				->rule('creditcard_no','min_length', array(':value', '9'))	
				->rule('creditcard_no','max_length', array(':value', '16'))	
				//->rule('creditcard_cvv','not_empty')
				->rule('expmonth','not_empty')
				->rule('expyear','not_empty');
	}
	function payment_validationwith_account($array)
	{
		 return Validation::factory($array)
				->rule('trip_id','not_empty')
				->rule('distance','not_empty')
				->rule('fare','not_empty')	
				->rule('creditcard_no','not_empty')
				->rule('creditcard_no','min_length', array(':value', '9'))	
				->rule('creditcard_no','max_length', array(':value', '16'))	
				//->rule('creditcard_cvv','not_empty')
				->rule('expmonth','not_empty')
				->rule('expyear','not_empty')
				->rule('account_id','not_empty')
				->rule('group_id','not_empty');		
	}
	//Passenger Card Validation

	function passenger_card_validation($array)
	{
		 return Validation::factory($array)
				->rule('email','not_empty')
				->rule('email','email')
				->rule('creditcard_no','not_empty')
				->rule('creditcard_no','min_length', array(':value', '9'))	
				->rule('creditcard_no','max_length', array(':value', '16'))	
				//->rule('creditcard_cvv','not_empty')
				//->rule('creditcard_cvv','numeric')
				->rule('expdatemonth','not_empty')
				->rule('expdateyear','not_empty');		
				//->rule('phone','not_empty')				
				//->rule('phone','numeric')				
				//
				//
				//->rule('address','not_empty');
	}
	//Edit Card Validation
	function edit_passenger_card_validation($array)
	{
		 return Validation::factory($array)
				->rule('creditcard_no','not_empty')
				->rule('creditcard_no','min_length', array(':value', '9'))	
				->rule('creditcard_no','max_length', array(':value', '16'))	
				//->rule('creditcard_cvv','not_empty')
				//->rule('creditcard_cvv','numeric')
				->rule('expdatemonth','not_empty')
				->rule('expdateyear','not_empty');		
				//->rule('phone','not_empty')				
				//->rule('phone','numeric')				
				//
				//
				//->rule('address','not_empty');
	}		
	//Change Password
	function chg_password_passenger_validation($array)
	{
		return Validation::factory($array)
				->rule('old_password','not_empty')				
				->rule('new_password','not_empty')	
				//->rule('new_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))		
				->rule('confirm_password','not_empty');				
				
	}
	// Feed back validation
	function feedback_validation($array)
	{
		return Validation::factory($array)
				->rule('to','not_empty')				
				->rule('passenger_id','not_empty')
				->rule('subject','not_empty')
				->rule('message','not_empty');			
	}

	function check_dynamic_array($array)
	{
		return Validation::factory($array)
				->rule('pagename','not_empty')				
				->rule('device_type','not_empty')
				->rule('device_type','numeric');			
	}
	
	//nearest validation
	function nearestdriver_validation($search_array)
	{
			return Validation::factory($search_array)
				->rule('latitude','not_empty')
				->rule('longitude','not_empty');	
	}
			
	//Update Ratings and Comments
	function update_ratings_comments_validation($array)
	{
		return Validation::factory($array)
				->rule('ratings','not_empty');				
				//->rule('comments','not_empty');
							
	}
	
	

	function driver_login_validation($array)
	{
		 return Validation::factory($array)
				->rule('phone','not_empty')				
				->rule('password','not_empty');
				//->rule('device_token','not_empty');
	}
	
	//driver login status update
	public function driver_login_status_update($data)
	{
		$api = Model::factory(MOBILEAPI_107);
		$driver_id = $data['driver_id'];
		$company_id = $data['company_id'];
		$driver_status = $data['driver_status'];
		$driver_id_status = $data['driver_id_status'];
		$taxi_id = $data['taxi_id'];
		
	}
	
	public function pasenger_signup_validation($array) 
	{
		$password = isset($array['password']) ? $array['password'] : '';
		return Validation::factory($array)       
			
			->rule('first_name','not_empty')	
			->rule('last_name','not_empty')	
			->rule('email','not_empty')	
			->rule('email','email')			
			->rule('phone', 'not_empty')
			->rule('phone', 'min_length', array(':value', '7'))			
			->rule('password', 'not_empty')
			->rule('confirm_password', 'not_empty')
			->rule('confirm_password', 'Model_Find::checkConfirmPassword', array(':value',$password));									
	}
	
	public function account_validation($array) 
	{
		return Validation::factory($array)       
			
			->rule('email','not_empty')	
			->rule('email','email')			
			->rule('phone', 'not_empty')
			->rule('phone', 'min_length', array(':value', '7'))			
			->rule('password', 'not_empty');									
	}	
	
	public function getpassenger_update_validation($array)
	{
		return Validation::factory($array)       
			
			//->rule('trip_id','not_empty')	
			->rule('passenger_id','not_empty')	
			->rule('request_type','not_empty');		
	}
//add_expense&expense_driver_id=16&expense_amount=100&expense_type_id=test&payment_type=&expense_date=expense_validation	
	public function expense_validation($array) 
	{
		return Validation::factory($array)       
			
			->rule('expense_driver_id','not_empty')	
			->rule('expense_amount','not_empty')			
			->rule('expense_type_id', 'not_empty')
			->rule('expense_date', 'not_empty');									
	}
		
	//&driver_id=16&pickup_location=gandhipuram,coimbatore&drop_location=marudhamalai,coimbatore&distance=10&fare=100.32&tips=10.00&travel_date=2012-12-26 15:16:00
	public function street_validation($array) 
	{
		return Validation::factory($array)       
			
			->rule('driver_id','not_empty')	
			->rule('pickup_location','not_empty')			
			->rule('drop_location', 'not_empty')
			->rule('distance', 'not_empty')			
			->rule('fare', 'not_empty')
			->rule('tips', 'not_empty')
			->rule('travel_date', 'not_empty');									
	}	//&target_driver_id=16&target_amount=100&target_date=2012-12-26 15:16:00
	public function target_validation($array) 
	{
		return Validation::factory($array)       
			
			->rule('target_driver_id','not_empty')	
			->rule('target_amount','not_empty')			
			->rule('target_date', 'not_empty');									
	}

//&driver_id=16&phone=
	public function tellfri_sms_validation($array) 
	{
		return Validation::factory($array)       
			
			->rule('driver_id','not_empty')	
			->rule('phone','not_empty')			
			->rule('phone', 'numeric');									
	}
//tellfri_email_validation
	public function tellfri_email_validation($array) 
	{
		return Validation::factory($array)       
			
			->rule('driver_id','not_empty')	
			->rule('email','not_empty')			
			->rule('email', 'email');									
	}
		
	//&driver_id=5&shiftstatus=IN&reason=&update_id=
	public function shift_status_validation($array) 
	{
		return Validation::factory($array)       			
			->rule('driver_id','not_empty')	
			->rule('shiftstatus','not_empty');
	}
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=driver_taxi_details&email=senthilkumar.a@ndot.in&company_email=&tvdlno=&taxi_company=&taxi_model=&referral_code=&bank_name=&bank_account_no=
//
	public function check_tell_to_friend($array)
	{
		return Validation::factory($array)
			->rule('to','not_empty')
			->rule('to','email')
			->rule('passenger_id', 'not_empty');
	}
//&=dfff&=4534534&=2
	public function taxi_validation($array) 
	{
		return Validation::factory($array)       			
			->rule('email','not_empty')	
			->rule('email','email')		
			->rule('tvdlno', 'not_empty')
			->rule('taxi_company', 'not_empty')
			->rule('taxi_model', 'not_empty')			
			->rule('bank_name', 'not_empty')
			->rule('bank_account_no', 'not_empty')
			->rule('device_id', 'not_empty')
			->rule('device_token', 'not_empty')
			->rule('device_type', 'not_empty');								
	}	
	// Validation for Coming List
	public function coming_cancel($array) 
	{
		return Validation::factory($array)       			
			->rule('id','not_empty')	
			->rule('start', 'not_empty')
			->rule('limit', 'not_empty')
			->rule('device_type', 'not_empty');
	}
	// Validation for Driver Coming List
	public function driver_coming_cancel($array) 
	{
		return Validation::factory($array)       			
			->rule('driver_id','not_empty')	
			->rule('start', 'not_empty')
			->rule('limit', 'not_empty')
			->rule('device_type', 'not_empty');
	}		
	public function trip_history_month_wise($array) 
	{
		return Validation::factory($array)       			
			->rule('passenger_id','not_empty')	
			->rule('start', 'not_empty')
			->rule('limit', 'not_empty')
			->rule('month', 'not_empty')			
			->rule('device_type', 'not_empty');
	}
	
	public function trip_history_date_wise($array) 
	{
		return Validation::factory($array)       			
			->rule('passenger_id','not_empty')	
			->rule('start', 'not_empty')
			->rule('limit', 'not_empty')
			->rule('date', 'not_empty')
			->rule('device_type', 'not_empty');
	}	
	//&passenger_id=&favourite_place=Vadavalli,Gandhipuram&fav_comments=test
	public function favourite_validation($array) 
	{
		return Validation::factory($array)       
			
			->rule('passenger_id','not_empty')				
			->rule('p_favourite_place', 'not_empty')
			->rule('p_fav_locationtype', 'not_empty');									
	}
	//p_favourite_id=2&favourite_place=Vadavalli,Gandhipuram,west&fav_comments=
	public function edit_favourite_validation($array) 
	{
		return Validation::factory($array)       
			
			->rule('p_favourite_id','not_empty')				
			->rule('p_favourite_place', 'not_empty')
			->rule('p_fav_locationtype', 'not_empty');									
	}
	/**
	 * ****DisplayDateTimeFormat()****
	 * @param $input_date_time string
	 * @return  time format
	 */
	public function DisplayDateTimeFormat($input_date_time)
	{
		//getting input data from last login db field
		//===========================================
		$input_date_split = explode("-",$input_date_time);

		//splitting year and time in two arrays
		//=====================================
		$input_date_explode = explode(' ',$input_date_split[2]);
		$input_date_explode1 = explode(':',$input_date_explode[1]);

		//getting to display datetime format
		//==================================
		$display_datetime_format = date('j M Y h:i:s A',mktime($input_date_explode1[0], $input_date_explode1[1], $input_date_explode1[2], $input_date_split[1], $input_date_explode[0], $input_date_split[0]));

		return $display_datetime_format;
	}
	
	
	/**
	 * ****get_bitly_short_url()****
	 *
	 * @param url format
	 * @param 
	 * @return  the shortened bitly url
	 */
	public function get_bitly_short_url($url,$login,$appkey,$format='txt') {
		
		  $connectURL = 'http://api.bit.ly/v3/shorten?login='.$login.'&apiKey='.$appkey.'&uri='.urlencode($url).'&format='.$format;
		  return $this->curl_get_result($connectURL);
	}
	
	public function curl_get_result($url) {
		
		  $ch = curl_init();
		  curl_setopt($ch,CURLOPT_URL,$url);
		  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		  $data = curl_exec($ch);
		  curl_close($ch);
		  return $data;
	}
	
	function history_validation($search_array)
	{
				return Validation::factory($search_array)
					->rule('driver_id','not_empty')
					->rule('locations','not_empty')	
					->rule('above_min_km','not_empty')			
					->rule('status','not_empty');	
	}
		
	// Used for encrypt and decrypt the keys		
	function encrypt_decrypt($action, $string) {
	   $output = false;

	   $key = 'Taxi Application';

	   // initialization vector 
	   $iv = md5(md5($key));

	   if( $action == 'encrypt' ) {
	       //$output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
	       $output = base64_encode($string);
	   }
	   else if( $action == 'decrypt' ){
	     //  $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
	       //$output = rtrim($output, "");
	       $output = base64_decode($string);
	   }
	   return $output;
	}

	function findcompany_timezone($company_cid)
	{
			$rs = DB::select()->from(COMPANY)
				->where('cid','=',$company_cid)
				->execute()
				->as_array();	
			if(count($rs)>0)
			return $rs[0]['time_zone'];
			else
			return 0;		
	}
	/********* Get Subdomain details from URL *************/
	function getUrlSubdomain($url)
	{
		$urlSegments = parse_url($url);
		$urlHostSegments = explode('.', $urlSegments['host']);
		if(count($urlHostSegments) > 2) 
		{
			return $urlHostSegments[0];
		}
		else
		{
			return null;
		}
	}
	
	public static function action_cron_push()
	{
		/*$api = Model::factory(MOBILEAPI_107);	
		$result = $api->cron_push();
		echo json_encode($result);	*/	
		exit;
	}
	
	///Check whther the user logged in the system or not
	public function is_login_status($driver_id,$company_id='')
	{ 	
			$api = Model::factory(MOBILEAPI_107);
			/**Check user is Logged in or not  **/
			$result=$api->logged_user_status($driver_id,$company_id);			
			/* If user Logged IN*/
			if($result == 1)
			{
				return 1;	
			}
			/* If user Logged OUT*/			
			else
			{	
				return 0;				
			}			
	 }	
	 
	// Used for push the records into array for mobile display
	public function array_put_to_position(&$array, $object, $position, $name = null)
	{	
			//$this->array_put_to_position($upcoming_journey, 'P', 1, 'pickstatus');
			//echo $object;
			//echo $position;
			//echo $name; 
			$count = 0;
			$return = array();
			foreach ($array as $k => $v) 
			{   
					//echo $k.'=>'.$v;	
					// insert new object
					if ($count == $position)
					{   
							if (!$name) $name = $count;
							$return[$name] = $object;
							$inserted = true;
					}   
					// insert old object
					$return[$k] = $v; 
					$count++;
			}   
			if (!$name) $name = $count;
			//if (!$inserted) $return[$name];
			$array = $return;
			return $array;
	}

	public function array_push_assoc($array, $position, $key, $value){
		//print_r($array);
		$array[$position][$key] = $value;
		return $array;
	}
    /** CURL GET AND POST**/

    private function curl_function($req_url = "" , $type = "", $arguments =  array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $req_url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        if($type == "POST"){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arguments);
        }

        $result = curl_exec($ch);
        curl_close ($ch);
        return $result;
    }

	public function close_mysql_connection($instance)
	{
		$db = Database::$instances[$instance];
		//print_r($db);
		$db->disconnect();
	}
    public function send_mail_passenger($log_id='',$travel_status='')
    {
		/**************************** Mail send to Passenger ***************/   
		$api_model = Model::factory(MOBILEAPI_107);
		$passenger_log_details = $api_model->passenger_transdetails($log_id);
		//print_r($passenger_log_details);exit;
		if(count($passenger_log_details)>0)
		{
			$to = $passenger_log_details[0]['passenger_email'];
			$name = $passenger_log_details[0]['passenger_name'];
			$job_referral = $passenger_log_details[0]['job_referral'];
			$api_model=Model::factory(MOBILEAPI_107);	
			$location_data = $api_model->get_location_details($log_id);
			if($location_data)
			{
				$pickup = $location_data[0]['current_location'];
				$drop = $location_data[0]['drop_location'];
				$path = $location_data[0]['active_record'];
				$path=str_replace('],[', '|', $path);
				$path=str_replace(']', '', $path);
				$path=str_replace('[', '', $path);
				$path =explode('|',$path);$path=array_unique($path);
				include_once MODPATH."/email/vendor/polyline_encoder/encoder.php";
				$polylineEncoder = new PolylineEncoder();
				if(count($path) > 0)
				{
					foreach ($path as $values)
					{
						$values = explode(',',$values);
						$polylineEncoder->addPoint($values[0],$values[1]);
						$polylineEncoder->encodedString();
					}
				}
				$encodedString = $polylineEncoder->encodedString();
				$marker_end=$location_data[0]['drop_latitude'].','.$location_data[0]['drop_longitude'];
				$marker_start=$location_data[0]['pickup_latitude'].','.$location_data[0]['pickup_longitude'];
				//$url= "http://maps.google.com/maps/api/staticmap?center=$center&zoom=11&markers=$markers&size=500x300&sensor=TRUE_OR_FALSE&path=color:0x0000ff|weight:5|$path";echo $url;exit;
				$mapurl = "http://maps.googleapis.com/maps/api/staticmap?size=250x250&markers=color:red%7C$marker_start&markers=color:green%7C$marker_end&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
			}
			else
			{
				$mapurl ="";
				$pickup ="";
				$drop="";
			}
			$subtotal='';
			$orderlist='';   
			//$orderlist='<table cellspacing="8" cellpadding="5">';   
			if($travel_status == 4)
			{
				//$orderlist.='<tr style="color:#808080"><td colspan="2" style="color: #161616; font-size: 15px; font-weight: bold"><b>'.__('cancel_trip').'</b></td><td></td></tr>';
			}
			else
			{
				//$orderlist.='<tr style="color:#808080"><td colspan="2" style="color: #161616; font-size: 15px; font-weight: bold"><b>'.__('complete_from').'</b></td><td></td></tr>';
			}
			$used_wallet_amount = (isset($passenger_log_details[0]['used_wallet_amount'])) ? $passenger_log_details[0]['used_wallet_amount'] : 0;	
			$distance_fare = $passenger_log_details[0]['tripfare'] - $passenger_log_details[0]['minutes_fare'];
			//$subtotal= $passenger_log_details[0]['fare'];
			$subtotal= $passenger_log_details[0]['fare']+$used_wallet_amount;
			$payment_mode_value=$passenger_log_details[0]['payment_type'];
			switch($payment_mode_value)
			{
				case 1:
				$payment_mode = __('cash');
				break;
				case 2:
				$payment_mode =__('credit_card');
				break;
				case 3:
				$payment_mode =__('uncard');
				break;
				default:
				$payment_mode =__('account');
			}
			$distance_km=($passenger_log_details[0]['distance']!='')?$passenger_log_details[0]['distance']:'0';
			$trip_minutes=($passenger_log_details[0]['trip_minutes']!='')?$passenger_log_details[0]['trip_minutes']:'0';
			
			$distance_fare_row = '';
			if($distance_fare != 0) {
				$distance_fare_row = '<tr><td width="50%"> <p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('distance_fare').'</p></td><td width="50%"><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($distance_fare,2).'</p></td></tr>';
			}
			$minutes_fare_row = '';
			if($passenger_log_details[0]['minutes_fare'] != 0) {
				$minutes_fare_row = '<tr><td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('minutes_fare').'</td><td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($passenger_log_details[0]['minutes_fare'],2).'</p></td></tr>';
			}
			$wallet_row = '';
			if($used_wallet_amount != 0) {
				$wallet_row = '<tr><td><p  style="font:bold 15px/18px arial;margin:0 ;color:#333">'.__('wallet_amount_paid').'</td><td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($used_wallet_amount,2).'</p></td></tr>';
			}
			$evening_fare = '';
			if($passenger_log_details[0]['eveningfare'] != 0) {
				$evening_fare = '<tr><td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('eveningfare').'</td><td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($passenger_log_details[0]['eveningfare'],2).'</p></td></tr>';
			}
			$night_fare = '';
			if($passenger_log_details[0]['nightfare'] != 0) {
				$night_fare = '<tr><td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('nightfare').'</td><td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($passenger_log_details[0]['nightfare'],2).'</p></td></tr>';
			}
			
			$orderlist = '<tr>
		<td  align="left" colspan="2" width="100%" style="padding: 20px 15px 0px 15px;">
		    <table width="100%" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #ddd;">
			<tr>
			    <td valign="top" style="padding: 0;" width="305">
				<img src="##MAPURL##" align="top" style="float: left;" />
			    </td>
			    <td align="center" valign="middle">
				 <table width="100%" align="center" cellpadding="0" cellspacing="0">
				     <tr>
					 <td colspan="2" valign="middle" align="center" height="180">
					     <p style="font:normal 14px/18px arial;margin:0 ;color:#333;text-transform: uppercase;">'.__('total_fare').'</p>
				    <p style="font:normal 50px/58px arial;margin:0 ;color:#333;text-transform: uppercase;">'.COMPANY_CURRENCY.' '.round($passenger_log_details[0]['fare'],2).'</p>
				     <p  style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.__('payment_mode').': '.$payment_mode.'</p>
					 </td>
				     </tr>
				     <tr>
					 <td valign="middle" align="center">
					     <p style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.__('distance').'</p>
				    <p style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.$distance_km.'	'.__('km').'</p></td>
					 <td valign="middle" align="center">
					       <p style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.__('trip_minutes').'</p>
				    <p style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.$trip_minutes.'</p>
					 </td>
				     </tr>
				 </table>
				
			    </td>
			</tr>
		    </table>
		</td>
	    </tr>
	    <tr>
		<td align="left" colspan="2" style="padding: 10px 15px;">
		    <p style="font:normal 18px arial;margin:0;color:#333;border-bottom:1px solid #ddd;padding: 10px 0;text-transform: uppercase">'.__('fare_details').'</p>
		</td>
	    </tr>
	    <tr>
		<td colspan="2" style="padding: 0px 5px;">
		    <table width="100%" align="center" cellpadding="8" cellspacing="0">
			'.$distance_fare_row.$minutes_fare_row.'
			<tr>
			    <td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('waiting_time_cost').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($passenger_log_details[0]['waiting_cost'],2).'</p></td>
			</tr>
			<tr>
			    <td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('waiting_time_hours').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.$passenger_log_details[0]['waiting_time'].'</p></td>
			</tr>'.$evening_fare.$night_fare.'
			<tr>
			    <td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('tax').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($passenger_log_details[0]['tax_amount'],2).'</p></td>
			</tr>
			<tr>
			    <td><p  style="font:bold 15px/18px arial;margin:0 ;color:#333">'.__('sub_total').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($subtotal,2).'</p></td>
			</tr>'.$wallet_row.'
		    </table>
		</td>
	    </tr>
	    <tr>
		<td align="left" colspan="2" style="padding: 10px 15px;">
		    <p style="font:normal 18px arial;margin:0;color:#333;border-bottom:1px solid #ddd;padding: 10px 0;text-transform: uppercase">'.__('booking_details').'</p>
		</td>
	    </tr>
	    <tr>
		<td style="background: #fff;padding: 10px 15px;" valign="top">
		    <b  style="font:bold 14px/20px arial;margin:0;color:#000">'.__('Current_Location').'</b>
		    <p  style="font:normal 13px/18px arial;margin:3px 0 0 0 ;color:#000">'.$passenger_log_details[0]['current_location'].'</p>
		</td>
		<td style="background: #fff;padding:15px;" valign="top">
		    <b  style="font:bold 14px/20px arial;margin:0;color:#000">'.__('Drop_Location').'</b>
		    <p  style="font:normal 13px/18px arial;margin:3px 0 0 0 ;color:#000">'.$passenger_log_details[0]['drop_location'].'</p>
		</td>
	    </tr>';

			$mail="";								
			$replace_variables=array(
				REPLACE_LOGO=>EMAILTEMPLATELOGO,
				REPLACE_SITENAME=>$this->app_name,
				REPLACE_USERNAME=>$name,
				REPLACE_EMAIL=>$to,
				REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',
				REPLACE_SITEEMAIL=>$this->siteemail,
				REPLACE_SITEURL=>URL_BASE,
				REPLACE_ORDERID=>$log_id,
				REPLACE_ORDERLIST=>$orderlist,
				REPLACE_MAPURl=>$mapurl,
				REPLACE_COMPANYDOMAIN=>$this->domain_name,
				REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,
				REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR
			);

			/* Added for language email template */
			if($this->lang!='en'){
			if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/tripcomplete-mail-'.$this->lang.'.html')){
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/tripcomplete-mail-'.$this->lang.'.html',$replace_variables);
			}else{
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcomplete-mail.html',$replace_variables);
			}
			}else{
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcomplete-mail.html',$replace_variables);
			}
			/* Added for language email template */

			//echo $message;exit;

			$from = $this->siteemail;
			$subject = __('payment_made_successfully');	
			$redirect = 'no';
			if(SMTP == 1)
			{
				include($_SERVER['DOCUMENT_ROOT']."/modules/SMTP/smtp.php");
			}
			else
			{
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				// Additional headers
				$headers .= 'From: '.$from.'' . "\r\n";
				$headers .= 'Bcc: '.$to.'' . "\r\n";
				mail($to,$subject,$message,$headers);	
			}
			
			$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
			$journey = $api_model->update_journey_status($log_id,$msg_status,$driver_reply,$journey_status);
		}
		/**************************** Mail send to Passenger ***************/
    }	
    
    public function send_cancel_fare_mail_passenger($cancelFare=0, $passenger_name="", $pickup_location="", $to="")
    {
		/*$orderlist = '<table cellspacing="8" cellpadding="5">';
		$orderlist.="
			<tr style='color:#808080'><td>".__('cancel_fare')."</td><td style='color:#000'>".COMPANY_CURRENCY."	".$cancelFare."</td></tr>
			<tr style='color:#808080'><td>".__('Current_Location')."</td><td style='word-break:break-word;border-collapse:collapse!important;text-align:left;display:table-cell;line-height:16px;padding:0px;color:#000'>".$pickup_location."</td></tr>";*/
		$orderlist = '<p style="font:bold 14px/22px arial;margin:0px 0 0 0;color:#333;padding: 0px 0">'.__('cancel_fare').':'.COMPANY_CURRENCY.' '.$cancelFare.'</p>';
		$orderlist = '<p style="font:bold 14px/22px arial;margin:0px 0 0 0;color:#333;padding: 5px 0">'.__('Current_Location').':'.$pickup_location.'</p>';

		$mail="";								
		$replace_variables=array(
			REPLACE_LOGO=>EMAILTEMPLATELOGO,
			REPLACE_SITENAME=>$this->app_name,
			REPLACE_USERNAME=>$passenger_name,
			REPLACE_SITEEMAIL=>$this->siteemail,
			REPLACE_SITEURL=>URL_BASE,
			REPLACE_ORDERLIST=>$orderlist,
			REPLACE_COMPANYDOMAIN=>$this->domain_name,
			REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,
			REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR
		);

		/* Added for language email template */
		if($this->lang!='en'){
		if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/tripcancel-'.$this->lang.'.html')){
		$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/tripcancel-'.$this->lang.'.html',$replace_variables);
		}else{
		$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcancel.html',$replace_variables);
		}
		}else{
		$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcancel.html',$replace_variables);
		}
		/* Added for language email template */

		//echo $message;exit;

		$from = $this->siteemail;
		$subject = __('payment_made_successfully');	
		$redirect = 'no';
		if(SMTP == 1)
		{
			include($_SERVER['DOCUMENT_ROOT']."/modules/SMTP/smtp.php");
		}
		else
		{
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			// Additional headers
			$headers .= 'From: '.$from.'' . "\r\n";
			$headers .= 'Bcc: '.$to.'' . "\r\n";
			mail($to,$subject,$message,$headers);	
		}
	}


    public function authorize_creditcard($values)
    {   

		$api_model = Model::factory(MOBILEAPI_107);
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

		    /*getting the Key and Value values and storing in a Associative Array*/
		    $keyval=substr($nvpstr,$intial,$keypos);
		    $valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
		    //decoding the respose
		    $nvpArray[urldecode($keyval)] =urldecode( $valval);
		    $nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
		}

		$ack = isset($nvpArray['ACK'])?strtoupper($nvpArray['ACK']):'';

		if(($ack =='SUCCESSWITHWARNING') ||($ack =='SUCCESS'))
		{
			return 1;
		}
		else
		{
			return 0;
		}			                

	}
	
	public  function weeks_in_month($month, $year) 
	{
	 // Start of month
	 $start = mktime(0, 0, 0, $month, 1, $year);
	 // End of month
	// echo '<br>';
	 $end = mktime(0, 0, 0, $month, date('t', $start), $year);
	 // Start week
	 $start_week = date('W', $start);
	 // End week
	 
	 $end_week = date('W', $end);
	 
	 if ($end_week < $start_week) { // Month wraps
	   return ((52 + $end_week) - $start_week) + 1;
	 }
 
	return ($end_week - $start_week) + 1;
}

	public function trippayment($values,$default_companyid)
	{

		$api_model = Model::factory(MOBILEAPI_107);
		$passenger_log_details = $api_model->passengerlogid_details($values['trip_id']);
		$passengers_id = $passenger_log_details[0]['passengers_id'];
		$pickupDate = $passenger_log_details[0]['pickup_time'];
		$pickupLocation = $passenger_log_details[0]['pickupLocation'];
		$dropLocation = $passenger_log_details[0]['dropLocation'];
		$pickup_latitude = $passenger_log_details[0]['pickup_latitude'];
		$pickup_longitude = $passenger_log_details[0]['pickup_longitude'];
		$drop_latitude = $passenger_log_details[0]['drop_latitude'];
		$drop_longitude = $passenger_log_details[0]['drop_longitude'];
		$active_record = $passenger_log_details[0]['active_record'];
		$used_wallet_amount = (isset($passenger_log_details[0]['used_wallet_amount'])) ? $passenger_log_details[0]['used_wallet_amount'] : 0;
		$pre_transaction_id = isset($passenger_log_details[0]['pre_transaction_id'])?$passenger_log_details[0]['pre_transaction_id']:"";
		$pre_transaction_amount = isset($passenger_log_details[0]['pre_transaction_amount'])?$passenger_log_details[0]['pre_transaction_amount']:0;
		$passenger_first_name=isset($passenger_log_details[0]['passenger_name'])?$passenger_log_details[0]['passenger_name']:"";
		$passenger_last_name=isset($passenger_log_details[0]['passenger_lastname'])?$passenger_log_details[0]['passenger_lastname']:"";
		$passenger_email=isset($passenger_log_details[0]['passenger_email'])?$passenger_log_details[0]['passenger_email']:"";
		$passenger_phone=isset($passenger_log_details[0]['passenger_phone'])?$passenger_log_details[0]['passenger_phone']:"";
		$fare_calculation_type = isset($array['fare_calculation_type']) ? $array['fare_calculation_type'] : FARE_CALCULATION_TYPE;
		/*if($values['pay_mod_id'] == 4)
		{
			$passenger_userid = $values['account_holder_id'];
		}
		else
		{
			$passenger_userid = $passenger_log_details[0]['passengers_id'];
		}
		
		if(($values['pay_mod_id'] == 2) || ($values['pay_mod_id'] == 4))
		{
			$card_type = '';
			$default = 'yes';
			$carddetails = $api_model->get_creadit_card_details($passenger_userid,$card_type,$default);
			if(count($carddetails)>0)
			{
				$creditcard_no = encrypt_decrypt('decrypt',$carddetails[0]['creditcard_no']);
				$creditcard_cvv = $values['creditcard_cvv'];
				$expdatemonth = $carddetails[0]['expdatemonth'];
				$expdateyear = $carddetails[0]['expdateyear'];
			}
		}
		else
		{
			$creditcard_no = $values['creditcard_no'];
			$creditcard_cvv = $values['creditcard_cvv'];
			$expdatemonth = $values['expmonth'];
			$expdateyear = $values['expyear'];
		} */

		//echo $creditcard_no;
		//exit;
		$driver_userid = $passenger_log_details[0]['driver_id'];
		$company_id = $passenger_log_details[0]['company_id'];
		$values['company_id'] = $company_id;
		$street = $city = $state = $country_code = $currency_code = $country_code = $zipcode = $paypal_api_username = $paypal_api_password = $paypal_api_signature = $currency_format = "";
		$siteinfo_details = $api_model->siteinfo_details();

		$passenger_log_id = $values['trip_id'];
		if($values['actual_distance'] == "")
			$distance = urldecode($values['distance']);
		else
		$distance = urldecode($values['actual_distance']);
		$actual_amount = $values['actual_amount'];
		$waiting_cost = $values['waiting_cost'];
		$waiting_hours = urldecode($values['waiting_time']);
		$remarks = $values['remarks'];
		$trip_fare = $values['trip_fare']; // Trip Fare without Tax,Tips and Discounts
		$fare = round($values['fare'],2); // Total Fare with Tax,Tips and Discounts can editable by driver
		$tips = round($values['tips'],2); // Tips Optional
		$passenger_discount = $values['passenger_discount'];
		$account_discount = ""; //$values['account_discount'];
		$company_tax = $values['tax_amount'];

		$passenger_discount_amt = $passenger_discount;
		$account_discount_amt = 0;
		//echo $creditcard_no;

		$trip_fare = round($trip_fare,2);
		$total_fare = $fare;// + $tips; // Total fare with Tips if exist
		$amount = round($total_fare,2); // Total amount which is used for pass to payment gateways
		$credits_used = 0;
		/******** SAR Equal USD ******************/
		//$amount = round(currency_conversion_usd('SAR',$amount),2); //exit;

	/*	if($default_companyid > 0)
		{
			//$paypal_details = $api_model->company_paypal_details($values['company_id']);
			//$company_addr_details = $api_model->company_addr_details($values['company_id']);
			//$city=$api_model->company_get_city_name($company_addr_details[0]['login_city']);
			$paypal_details = $api_model->company_payment_details($values['company_id']);
			$payment_gateway_username = isset($paypal_details[0]['payment_gateway_username'])?$paypal_details[0]['payment_gateway_username']:"";
			$payment_gateway_password = isset($paypal_details[0]['payment_gateway_password'])?$paypal_details[0]['payment_gateway_password']:"";
			$payment_gateway_key = isset($paypal_details[0]['payment_gateway_key'])?$paypal_details[0]['payment_gateway_key']:"";
			$currency_format = isset($paypal_details[0]['gateway_currency_format'])?$paypal_details[0]['gateway_currency_format']:"";
			$payment_method = isset($paypal_details[0]['payment_method'])?$paypal_details[0]['payment_method']:"";
			$payment_types=isset($paypal_details[0]['payment_type'])?$paypal_details[0]['payment_type']:"";
			$street=COMPANY_STREET_ADDR;
			$city=COMPANY_LOGIN_CITY_NAME;
			$state=COMPANY_LOGIN_STATE_NAME;
			$country_code=COMPANY_LOGIN_ISO_COUNTRYCODE;
			//$country_dets=$api_model->company_get_country_code($company_addr_details[0]['login_country']);
		//	print_r($country_dets);exit;
			$currency_code=COMPANY_CURRENCY_FORMAT;
			//$country_code=isset($country_dets[0]['iso_country_code'])?$country_dets[0]['iso_country_code']:"";
			$zipcode=COMPANY_ZIPCODE;
		}
		else
		{ */
			//$paypal_details = $api_model->paypal_details(); 
			$paypal_details = $api_model->payment_gateway_details();
			$payment_gateway_username = isset($paypal_details[0]['payment_gateway_username'])?$paypal_details[0]['payment_gateway_username']:"";
			$payment_gateway_password = isset($paypal_details[0]['payment_gateway_password'])?$paypal_details[0]['payment_gateway_password']:"";
			$payment_gateway_key = isset($paypal_details[0]['payment_gateway_key'])?$paypal_details[0]['payment_gateway_key']:"";
			$currency_format = isset($paypal_details[0]['gateway_currency_format'])?$paypal_details[0]['gateway_currency_format']:"";
			$payment_method = isset($paypal_details[0]['payment_method'])?$paypal_details[0]['payment_method']:"";
			$payment_types = isset($paypal_details[0]['payment_type'])?$paypal_details[0]['payment_type']:"";
		//}

		if($values['pay_mod_id'] == 2) {
			/** Split fare payment section **/
			//get the total non approved percentage
			$totalPendingPercentage = $api_model->getpendingFarePercentage($passenger_log_id);
			$approvedSplitFares = $api_model->getTripSplitFareDets($passenger_log_id,'A');
			if(count($approvedSplitFares) > 0) {
				$failurePercent = 0;
				//print_r($approvedSplitFares); exit;
				foreach($approvedSplitFares as $sfares) {
					if($sfares['friends_p_id'] == $passengers_id) {
						$passPercent = $sfares['fare_percentage'] + $totalPendingPercentage + $failurePercent;
						$amountToPay = $amount * ($passPercent / 100);
						$amountToPay = round($amountToPay,2);
						$primaryPassenger = 1;

						/** Update primary passenger percent & amount **/
						DB::update(P_SPLIT_FARE)->set(array('fare_percentage' => $passPercent,"appx_amount" => $amountToPay))
												->where('trip_id', '=', $passenger_log_id)
												->where('friends_p_id', '=', $passengers_id)
												->execute();

						/** Secondary passenger percent & amount to Zero **/
						$api_model->updatePassengerPercentAmt($passenger_log_id,$passengers_id,1);
					} else {
						$passPercent = $sfares['fare_percentage'];
						$amountToPay = $amount * ($passPercent / 100);
						$amountToPay = round($amountToPay,2);
						$primaryPassenger = 2;
					}
					//reduce the used wallet amount from amountToPay
					if($sfares['used_wallet_amount'] >= $amountToPay){
						$actAmountToPay = $sfares['used_wallet_amount'] - $amountToPay;
					} else {
						$actAmountToPay = $amountToPay - $sfares['used_wallet_amount'];
					}
					
					$creditcard_no = encrypt_decrypt('decrypt',$sfares['creditcard_no']);
					//payment process
					list($paymentResult,$result) = $this->paymentProcess($payment_method, $payment_gateway_username, $payment_gateway_password, $payment_gateway_key, $creditcard_no, $sfares['creditcard_cvv'], $sfares['expdatemonth'], $sfares['expdateyear'], $sfares['card_holder_name'], $actAmountToPay, $sfares['firstname'], $sfares['lastname'], $sfares['phone'], $sfares['email'], $passenger_log_id, $sfares['friends_p_id'], $primaryPassenger, $pre_transaction_amount, $pre_transaction_id, $pickupDate, $payment_types, $currency_format,$values['pay_mod_id']);
					if($paymentResult == 2){ //if payment failure for secondary passenger that fare percentage will be added to primary passenger
						$failurePercent = $failurePercent + $sfares['fare_percentage'];
						/** Secondary passenger percent & amount to Zero **/
						$api_model->updatePassengerPercentAmt($passenger_log_id,$passengers_id,2);

						//function to send Message to passenger
						$this->sendMessageToSplittedPassenger($passenger_log_id,$sfares['phone'],'failure');
					} else {
						//function to send Mail to passenger
						$this->sendMailToSplittedPassenger($passenger_log_id,$sfares['email'],$sfares['firstname'],$pickupLocation,$pickup_latitude,$pickup_longitude,$dropLocation,$drop_latitude,$drop_longitude,$active_record,$used_wallet_amount,$trip_fare,$values['minutes_fare'],$fare,$values['pay_mod_id'],$values['distance'],$values['minutes_traveled'],$values['eveningfare'],$values['nightfare'],$waiting_hours,$waiting_cost,$company_tax,$passPercent,$amountToPay);
						//function to send Message to passenger
						$this->sendMessageToSplittedPassenger($passenger_log_id,$sfares['phone'],'success');
					}
				}
			}
		} else {
			$creditcard_no = $values['creditcard_no'];
			$creditcard_cvv = $values['creditcard_cvv'];
			$expdatemonth = $values['expmonth'];
			$expdateyear = $values['expyear'];
			//payment process
			list($paymentResult,$result) = $this->paymentProcess($payment_method, $payment_gateway_username, $payment_gateway_password, $payment_gateway_key, $creditcard_no, $creditcard_cvv, $expdatemonth, $expdateyear, '', $amount, $passenger_first_name, $passenger_last_name, $passenger_phone, $passenger_email, $passenger_log_id, $passengers_id, 1, 0, '', $pickupDate, $payment_types, $currency_format,$values['pay_mod_id']);
			if($paymentResult == 1) {
				//function to send Mail to passenger
				$this->sendMailToSplittedPassenger($passenger_log_id,$passenger_email,$passenger_first_name,$pickupLocation,$pickup_latitude,$pickup_longitude,$dropLocation,$drop_latitude,$drop_longitude,$active_record,$used_wallet_amount,$trip_fare,$values['minutes_fare'],$fare,$values['pay_mod_id'],$values['distance'],$values['minutes_traveled'],$values['eveningfare'],$values['nightfare'],$waiting_hours,$waiting_cost,$company_tax,'100',$amount);
				//function to send Message to passenger
				$this->sendMessageToSplittedPassenger($passenger_log_id,$passenger_phone,'success');
			}
		}
			
		/******* Process the next step once we get the response from payment gateway ****************************/
        if(( is_array($result) && isset($result["paymentresponse"]) && !empty($result["paymentresponse"]) ) || isset($result->success))
        {
		    $paymentresponse=array();
		    $ack = ($payment_types == 1 && isset($result['paymentresponse']["ACK"]))?strtoupper($result['paymentresponse']["ACK"]):'';		                
				if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING" || isset($result->success))
				{                  											
					$invoceno = commonfunction::randomkey_generator();
					if($payment_types==2){
						$paymentresponse['TRANSACTIONID'] = $result->transaction->id;
					}else{
						$paymentresponse = $result['paymentresponse'];
					}					
					
					//if($new_card == 1){$payment_type = 4;}else{$payment_type =;}
					$insert_array = array(
										"passengers_log_id" => $passenger_log_id,
										"distance" 			=> $values['distance'],
										"actual_distance" 	=> $values['actual_distance'],
										"distance_unit" 	=> UNIT_NAME,
										"tripfare"			=> $trip_fare,
										"fare" 				=> $fare,
										"tips" 				=> $tips,
										"waiting_cost"		=> $waiting_cost,
										"company_tax"		=> $company_tax,
										"passenger_discount"=> $passenger_discount_amt,
										"promo_discount_fare"=> $values['promodiscount_amount'],
										"tax_percentage"=> $values['company_tax'],
										"account_discount" => $account_discount_amt,
										"credits_used"		=>$credits_used,
										"waiting_time"		=> $waiting_hours,
										"trip_minutes"		=> $values['minutes_traveled'],
										"minutes_fare"		=> $values['minutes_fare'],
										"base_fare"			=> $values['base_fare'],
										"remarks"			=> $remarks,
										"payment_type"		=>  $values['pay_mod_id'],
										"payment_method"		=>  $payment_method,
										"amt"			=> $amount,
										"company_id" => $company_id,//nightfare_applicable
										"nightfare_applicable" => $values['nightfare_applicable'],
										"nightfare" 		=> $values['nightfare'],
										"eveningfare_applicable" => $values['eveningfare_applicable'],
										"eveningfare" 		=> $values['eveningfare'],
										"fare_calculation_type"	=> $fare_calculation_type

									);
												
					$transactionfield = $insert_array + $paymentresponse + $siteinfo_details;  // Data Store 				
					//print_r($transactionfield);exit;
					/********** Update Driver Status after complete Payments *****************/
					$update_driver_arrary  = array("status" => 'F');
					$result = $api_model->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_userid);
					$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
					$journey = $api_model->update_journey_status($passenger_log_id,$msg_status,$driver_reply,$journey_status);	
					/***********************************************************************************/
					//insert transaction status
					//===================================
					$transaction_detail=$api_model->triptransact_details($transactionfield,$payment_types,$driver_userid);				
					return 1; 	
				}   
				else
				{ 
					$message = isset($result['paymentresponse']['L_LONGMESSAGE0'])?$result['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
					return 0; 
				}
          } 
           else
           {           
                    $message = isset($result['paymentresponse']['L_LONGMESSAGE0'])?$result['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
                    return 0; 
           }

	}
	
	public function paymentProcess($payment_method, $payment_gateway_username, $payment_gateway_password, $payment_gateway_key, $creditcard_no, $creditcard_cvv, $expdatemonth, $expdateyear, $cardHolderName, $amount, $firstName, $lastName, $phone, $email, $tripId, $passengerId, $primaryPassenger, $preTransactAmount, $preTransactId, $pickupDate, $paymentTypes, $currency_format, $payModId)
	{
		$api_model = Model::factory(MOBILEAPI_107);
		if($paymentTypes == 1) {
			$product_title = Html::chars('Complete Trip');
			//$payment_type = 'Authorization';
			$payment_action='sale';

			$request  = 'METHOD=DoDirectPayment';
			$request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
			$request .= '&USER=' . urlencode($payment_gateway_username);
			$request .= '&PWD=' . urlencode($payment_gateway_password);
			$request .= '&SIGNATURE=' . urlencode($payment_gateway_key);
			$request .= '&CUSTREF=' . (int)$tripId;
			$request .= '&PAYMENTACTION=' . $payment_action; //type
			$request .= '&AMT=' . urlencode($amount); // 
			$request .= '&ACCT=' . urlencode(str_replace(' ', '',$creditcard_no));
			$request .= '&EXPDATE=' . urlencode($expdatemonth.$expdateyear);
			$request .= '&CVV2=' . urlencode($creditcard_cvv);
		
			$request .= '&CURRENCYCODE=' .$currency_format;
			$street = $city = $state = $zipcode = $country_code = '';
			//exit;
			$paypal_type=($payment_method =="L")?"live":"sandbox";
			//echo $paypal_type;
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
			$nvpstr = curl_exec($curl);
			curl_close($curl);	
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
			if(isset($nvpArray["ACK"]) && strtoupper($nvpArray["ACK"]) == "SUCCESS") {
				if($payModId == 2) { //payModId - 2 = credit card, payModId - 3 = New Card
					$splTransact = $api_model->updateSplitTransaction($nvpArray["TRANSACTIONID"], $amount, 1, 1, 1, $tripId, $passengerId);
					if($splTransact) {
						if($primaryPassenger == 1 && !empty($preTransactId)) {
							$paypalVoid = $this->voidTransaction($preTransactId,$paypal_type,$payment_gateway_username,$payment_gateway_password,$payment_gateway_key);
							$upSpltArr=array("split_transaction_status"=>"2","void_status"=>"1");
						} else {
							$upSpltArr=array("split_transaction_status"=>"2");
						}
						$resultSplt = $api_model->update_table(PASSENGERS_LOG,$upSpltArr,'passengers_log_id',$tripId);
					}
				}
				$response = 1;
			} else {
				if($primaryPassenger == 1 && $payModId == 2) {
					if($amount > $preTransactAmount) {
						$pendingAmount = $amount-$preTransactAmount;
						$failureTransact = $api_model->insertFailureTransact($tripId, $passengerId, $pendingAmount, $pickupDate);
					}
					$splTransact = $api_model->updateSplitTransaction($preTransactId, $amount, 1, 0, 0, $tripId, $passengerId);
					if($splTransact) {
						$upSpltArr=array("split_transaction_status"=>"2","void_status"=>"2");
						$resultSplt = $api_model->update_table(PASSENGERS_LOG,$upSpltArr,'passengers_log_id',$tripId);
					}
					$response = 1;
				} else {
					$response = 2;
				}
			}
			return array($response,$nvpstr);
			
		} else {
			try{
				/** Brain Tree payment gateway **/
				$product_title = Html::chars('Complete Trip');
				$payment_action='sale';
				//require_once DOCROOT.'braintree-payment/lib/Braintree.php';
				require_once(APPPATH.'vendor/braintree-payment/lib/Braintree.php');
				$pay_type=($payment_method =="L")?"live":"sandbox";
				if ($pay_type=="live") 
				{
					Braintree_Configuration::environment('production');
				} else {
					Braintree_Configuration::environment('sandbox');			
				}
				//config access
				Braintree_Configuration::merchantId($payment_gateway_username);//your_merchant_id
				Braintree_Configuration::publicKey($payment_gateway_password);//your_public_key
				Braintree_Configuration::privateKey($payment_gateway_key);//your_private_key
				$cnumber=str_replace(' ', '',$creditcard_no);
				$ccv=str_replace(' ', '',$creditcard_cvv);
				//payModId - 2 = credit card, payModId - 3 = New Card
				if(($primaryPassenger == 1 && $preTransactAmount < $amount) || ($primaryPassenger == 2) || ($payModId == 3)) {
						$result = Braintree_Transaction::sale(array(

						'amount' => $amount,				    
						'creditCard' => array(
				
						'cardholderName' => $cardHolderName,
						'number' => $cnumber,//$_POST['creditCard']
						'expirationMonth' =>$expdatemonth,//$_POST['month']
						'expirationYear' => $expdateyear,//$_POST['year']
						'cvv' => $ccv,
						),
						
						'customer' => array(
						'firstName' => $firstName,
						'lastName' => $lastName,
						'company' => '',
						'phone' => $phone,
						'fax' => '',
						'website' => '',
						 'email' => $email
					  ),
						
						'shipping' => array(
						'firstName' => $firstName,
						'lastName' => $lastName,
						'company' => '',
						'streetAddress' => '',
						'extendedAddress' => '',
						'locality' => '',
						'region' => '',
						'postalCode' => '',
						'countryCodeAlpha2' => '',
					  ) ));
					if($result->success) { 
						if($payModId == 2) { //payModId - 2 = credit card, payModId - 3 = New Card
							$splTransact = $api_model->updateSplitTransaction($result->transaction->id, $amount, 1, 1, 1, $tripId, $passengerId);
							if($splTransact) { 
								if($primaryPassenger == 1 && !empty($preTransactId)) {
									$result_void = Braintree_Transaction::void($preTransactId);
									$upSpltArr=array("split_transaction_status"=>"2","void_status"=>"1");
								} else {
									$upSpltArr=array("split_transaction_status"=>"2");
								}
								$resultSplt = $api_model->update_table(PASSENGERS_LOG,$upSpltArr,'passengers_log_id',$tripId);
							}
						}
						$response = 1;
					} else {
						if($primaryPassenger == 1 && $payModId == 2 && !empty($preTransactId)) {
							$result = Braintree_Transaction::submitForSettlement($preTransactId);
							$braintree_status = str_replace('_',' ',$result->transaction->status);
							$pendingAmount = $amount-$preTransactAmount;
							$failureTransact = $api_model->insertFailureTransact($tripId, $passengerId, $pendingAmount, $pickupDate);
							$splTransact = $api_model->updateSplitTransaction($result->transaction->id, $amount, 1, $braintree_status, 0, $tripId, $passengerId);
							if($splTransact) {
								$upSpltArr=array("split_transaction_status"=>"2","void_status"=>"2");
								$resultSplt = $api_model->update_table(PASSENGERS_LOG,$upSpltArr,'passengers_log_id',$tripId);
							}
							$response = 1;
						} else {
							$response = 2;
						}
					}
				} else {
					$result = Braintree_Transaction::submitForSettlement($preTransactId, $amount);
					$braintree_status = str_replace('_',' ',$result->transaction->status);
					$splTransact = $api_model->updateSplitTransaction($result->transaction->id, $amount, 1, $braintree_status, 0, $tripId, $passengerId);
					if($splTransact) {
						$upSpltArr=array("split_transaction_status"=>"2","void_status"=>"2");
						$resultSplt = $api_model->update_table(PASSENGERS_LOG,$upSpltArr,'passengers_log_id',$tripId);
					}
					$response = 1;
				}
			}
			catch(Braintree_Exception $message) {
				return 0; 
			}
			return array($response,$result);
		}
	}
	/** Function to void the pre transaction amount using paypal **/
	public function voidTransaction($preTransactId,$paypal_type,$gatewayUsername,$gatewayPassword,$gatewayKey)
	{
		$request  = 'METHOD=RefundTransaction';
		$request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
		$request .= '&USER=' . urlencode($gatewayUsername);
		$request .= '&PWD=' . urlencode($gatewayPassword);
		$request .= '&SIGNATURE=' . urlencode($gatewayKey);
		$request .= '&TRANSACTIONID=' . urlencode($preTransactId);
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
		return 1;
	}
	
	public function sendMailToSplittedPassenger($tripId,$passengerEmail, $passengerName, $pickupLocation, $pickupLatitude, $pickupLongitude, $dropLocation, $dropLatitude, $dropLongitude, $activeRecord,$usedWalletAmount,$tripFare,$minutesFare,$totalFare,$paymentType,$distance,$tripMins,$eveningFare,$nightFare,$waitingTime,$waitingCost,$taxAmount,$passPercent,$amountToPay)
	{
		/**************************** Mail send to Passenger ***************/ 
			$to = $passengerEmail;
			$name = $passengerName;
			$pickup = $pickupLocation;
			$drop = $dropLocation;
			$path=str_replace('],[', '|', $activeRecord);
			$path=str_replace(']', '', $path);
			$path=str_replace('[', '', $path);
			$path =explode('|',$path);$path=array_unique($path);
			include_once MODPATH."/email/vendor/polyline_encoder/encoder.php";
			$polylineEncoder = new PolylineEncoder();
			if(!empty($activeRecord) && count($path) > 0)
			{
				foreach ($path as $values)
				{
					$values = explode(',',$values);
					$polylineEncoder->addPoint($values[0],$values[1]);
					$polylineEncoder->encodedString();
				}
			}
			$encodedString = $polylineEncoder->encodedString();
			$marker_end = $dropLatitude.','.$dropLongitude;
			$marker_start = $pickupLatitude.','.$pickupLongitude;
			$mapurl = "http://maps.googleapis.com/maps/api/staticmap?size=250x250&markers=color:red%7C$marker_start&markers=color:green%7C$marker_end&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
			$subtotal='';
			$orderlist='';
			$distance_fare = $tripFare - $minutesFare;
			$subtotal = $totalFare + $usedWalletAmount;
			$payment_mode_value = $paymentType;
			switch($payment_mode_value)
			{
				case 1:
				$payment_mode = __('cash');
				break;
				case 2:
				$payment_mode =__('credit_card');
				break;
				case 3:
				$payment_mode =__('uncard');
				break;
				default:
				$payment_mode =__('account');
			}
			$distance_km=($distance!='') ? $distance : '0';
			$trip_minutes=($tripMins!='') ? $tripMins : '0';
			
			$distance_fare_row = '';
			if($distance_fare != 0) {
				$distance_fare_row = '<tr><td width="50%"> <p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('distance_fare').'</p></td><td width="50%"><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($distance_fare,2).'</p></td></tr>';
			}
			$minutes_fare_row = '';
			if($minutesFare != 0) {
				$minutes_fare_row = '<tr><td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('minutes_fare').'</td><td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($minutesFare,2).'</p></td></tr>';
			}
			$wallet_row = '';
			if($usedWalletAmount != 0) {
				$wallet_row = '<tr><td><p  style="font:bold 15px/18px arial;margin:0 ;color:#333">'.__('wallet_amount_paid').'</td><td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($usedWalletAmount,2).'</p></td></tr>';
			}
			$evening_fare = '';
			if($eveningFare != 0) {
				$evening_fare = '<tr><td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('eveningfare').'</td><td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($eveningFare,2).'</p></td></tr>';
			}
			$night_fare = '';
			if($nightFare != 0) {
				$night_fare = '<tr><td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('nightfare').'</td><td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($nightFare,2).'</p></td></tr>';
			}
			
			$orderlist = '<tr>
		<td  align="left" colspan="2" width="100%" style="padding: 20px 15px 0px 15px;">
		    <table width="100%" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #ddd;">
			<tr>
			    <td valign="top" style="padding: 0;" width="305">
				<img src="##MAPURL##" align="top" style="float: left;" />
			    </td>
			    <td align="center" valign="middle">
				 <table width="100%" align="center" cellpadding="0" cellspacing="0">
				     <tr>
					 <td colspan="2" valign="middle" align="center" height="180">
					     <p style="font:normal 14px/18px arial;margin:0 ;color:#333;text-transform: uppercase;">'.__('paid_fare').'</p>
				    <p style="font:normal 50px/58px arial;margin:0 ;color:#333;text-transform: uppercase;">'.COMPANY_CURRENCY.' '.round($amountToPay,2).'</p>
				     <p  style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.__('payment_mode').': '.$payment_mode.'</p>
				     <p  style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.__('fare_percentage').' (%) : '.$passPercent.'</p>
					 </td>
				     </tr>
				     <tr>
					 <td valign="middle" align="center">
					     <p style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.__('distance').'</p>
				    <p style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.$distance_km.'	'.__('km').'</p></td>
					 <td valign="middle" align="center">
					       <p style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.__('trip_minutes').'</p>
				    <p style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.$trip_minutes.'</p>
					 </td>
				     </tr>
				 </table>
				
			    </td>
			</tr>
		    </table>
		</td>
	    </tr>
	    <tr>
		<td align="left" colspan="2" style="padding: 10px 15px;">
		    <p style="font:normal 18px arial;margin:0;color:#333;border-bottom:1px solid #ddd;padding: 10px 0;text-transform: uppercase">'.__('fare_details').'</p>
		</td>
	    </tr>
	    <tr>
		<td colspan="2" style="padding: 0px 5px;">
		    <table width="100%" align="center" cellpadding="8" cellspacing="0">
			'.$distance_fare_row.$minutes_fare_row.'
			<tr>
			    <td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('waiting_time_cost').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($waitingCost,2).'</p></td>
			</tr>
			<tr>
			    <td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('waiting_time_hours').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.$waitingTime.'</p></td>
			</tr>'.$evening_fare.$night_fare.'
			<tr>
			    <td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('tax').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($taxAmount,2).'</p></td>
			</tr>
			<tr>
			    <td><p  style="font:bold 15px/18px arial;margin:0 ;color:#333">'.__('sub_total').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($subtotal,2).'</p></td>
			</tr>'.$wallet_row.'
			<tr>
			    <td><p  style="font:bold 15px/18px arial;margin:0 ;color:#333">'.__('total_fare').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($totalFare,2).'</p></td>
			</tr>
		    </table>
		</td>
	    </tr>
	    <tr>
		<td align="left" colspan="2" style="padding: 10px 15px;">
		    <p style="font:normal 18px arial;margin:0;color:#333;border-bottom:1px solid #ddd;padding: 10px 0;text-transform: uppercase">'.__('booking_details').'</p>
		</td>
	    </tr>
	    <tr>
		<td style="background: #fff;padding: 10px 15px;" valign="top">
		    <b  style="font:bold 14px/20px arial;margin:0;color:#000">'.__('Current_Location').'</b>
		    <p  style="font:normal 13px/18px arial;margin:3px 0 0 0 ;color:#000">'.$pickupLocation.'</p>
		</td>
		<td style="background: #fff;padding:15px;" valign="top">
		    <b  style="font:bold 14px/20px arial;margin:0;color:#000">'.__('Drop_Location').'</b>
		    <p  style="font:normal 13px/18px arial;margin:3px 0 0 0 ;color:#000">'.$dropLocation.'</p>
		</td>
	    </tr>';

			$mail="";								
			$replace_variables=array(
				REPLACE_LOGO=>EMAILTEMPLATELOGO,
				REPLACE_SITENAME=>$this->app_name,
				REPLACE_USERNAME=>$name,
				REPLACE_EMAIL=>$to,
				REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',
				REPLACE_SITEEMAIL=>$this->siteemail,
				REPLACE_SITEURL=>URL_BASE,
				REPLACE_ORDERID=>$tripId,
				REPLACE_ORDERLIST=>$orderlist,
				REPLACE_MAPURl=>$mapurl,
				REPLACE_COMPANYDOMAIN=>$this->domain_name,
				REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,
				REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR
			);

			/* Added for language email template */
			if($this->lang!='en'){
			if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/tripcomplete-mail-'.$this->lang.'.html')){
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/tripcomplete-mail-'.$this->lang.'.html',$replace_variables);
			}else{
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcomplete-mail.html',$replace_variables);
			}
			}else{
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcomplete-mail.html',$replace_variables);
			}
			/* Added for language email template */

			//echo $message;exit;

			$from = $this->siteemail;
			$subject = __('payment_made_successfully');	
			$redirect = 'no';
			if(SMTP == 1)
			{
				include($_SERVER['DOCUMENT_ROOT']."/modules/SMTP/smtp.php");
			}
			else
			{
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				// Additional headers
				$headers .= 'From: '.$from.'' . "\r\n";
				$headers .= 'Bcc: '.$to.'' . "\r\n";
				mail($to,$subject,$message,$headers);	
			}
		/**************************** Mail send to Passenger ***************/
	}
	/** Send Message **/
	public function sendMessageToSplittedPassenger($tripId, $phone, $payResult)
	{
		if($payResult == "success") {
			$message_details = $this->commonmodel->sms_message_by_title('payment_confirmed_sms');
		} else {
			$message_details = $this->commonmodel->sms_message_by_title('payment_failed_sms');
		}
		
		$message = $message_details[0]['sms_description'];
		$message = str_replace("##booking_key##",$tripId,$message);
		$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
		$this->commonmodel->send_sms($phone,$message);
	}
	
	

	public function cancel_trippayment($values,$cancellation_nfree,$default_companyid)
	{   
		

			$api_model = Model::factory(MOBILEAPI_107);

			$passenger_log_details = $api_model->passengerlogid_details($values['passenger_log_id']);
	
			$passenger_userid = $passenger_log_details[0]['passengers_id'];
			$driver_userid = $passenger_log_details[0]['driver_id'];
			$company_id = $passenger_log_details[0]['company_id'];
			$values['company_id'] = $company_id;
			$shipping_first_name=isset($passenger_log_details[0]['passenger_name'])?$passenger_log_details[0]['passenger_name']:"";
			$shipping_last_name=isset($passenger_log_details[0]['passenger_lastname'])?$passenger_log_details[0]['passenger_lastname']:"";
			$shipping_email=isset($passenger_log_details[0]['passenger_email'])?$passenger_log_details[0]['passenger_email']:"";
			$shipping_phone=isset($passenger_log_details[0]['passenger_phone'])?$passenger_log_details[0]['passenger_phone']:"";
			$street = $city = $state = $country_code = $currency_code = $country_code = $zipcode = $paypal_api_username = $paypal_api_password = $trip_id=$paypal_api_signature = $currency_format = "";

			$card_type = '';
			$default = 'yes';
			$carddetails = $api_model->get_creadit_card_details($passenger_userid,$card_type,$default);
		
			if(count($carddetails)>0)
			{
				$creditcard_no = encrypt_decrypt('decrypt',$carddetails[0]['creditcard_no']);
				$creditcard_cvv = $values['creditcard_cvv'];
				$expdatemonth = $carddetails[0]['expdatemonth'];
				$expdateyear = $carddetails[0]['expdateyear'];
			}		
			
			//echo $creditcard_no."--".$creditcard_cvv."--".$expdatemonth."--".$expdateyear;exit;
			$city_id = $passenger_log_details[0]['search_city'];
			$taxi_id = $passenger_log_details[0]['taxi_id'];							

			//$taxi_model_details = $api_model->get_taxi_model_details($taxi_id);

			$taxi_model = $passenger_log_details[0]['taxi_modelid'];
					
			$siteinfo_details = $api_model->siteinfo_details(); 	
			
			$fare_details = $api_model->get_model_fare_details($company_id,$taxi_model,$city_id);

			//$amount = $values['total_fare'] = $company_details[0]['cancellation_fare'];
			$values['total_fare'] = $fare_details[0]['cancellation_fare'];
			$amount = $fare_details[0]['cancellation_fare'];
			/******** SAR Equal USD ******************/
			//$c_amount = round(currency_conversion_usd('SAR',$amount),2); //exit;			
			

			/*if($default_companyid > 0)
			{
				//$paypal_details = $api_model->company_paypal_details($values['company_id']);		
				//$company_addr_details = $api_model->company_addr_details($values['company_id']);
				//$city=$api_model->company_get_city_name($company_addr_details[0]['login_city']);
				$paypal_details = $api_model->company_payment_details($values['company_id']);
				$payment_gateway_username = isset($paypal_details[0]['payment_gateway_username'])?$paypal_details[0]['payment_gateway_username']:"";
				$payment_gateway_password = isset($paypal_details[0]['payment_gateway_password'])?$paypal_details[0]['payment_gateway_password']:"";
				$payment_gateway_key = isset($paypal_details[0]['payment_gateway_key'])?$paypal_details[0]['payment_gateway_key']:"";
				$currency_format = isset($paypal_details[0]['gateway_currency_format'])?$paypal_details[0]['gateway_currency_format']:"";
				$payment_method = isset($paypal_details[0]['payment_method'])?$paypal_details[0]['payment_method']:"";
				$payment_types=isset($paypal_details[0]['payment_type'])?$paypal_details[0]['payment_type']:"";
				$street=COMPANY_STREET_ADDR;
				$city=COMPANY_LOGIN_CITY_NAME;
				$state=COMPANY_LOGIN_STATE_NAME;
				$country_code=COMPANY_LOGIN_ISO_COUNTRYCODE;
				//$country_dets=$api_model->company_get_country_code($company_addr_details[0]['login_country']);
			//	print_r($country_dets);exit;
				$currency_code=COMPANY_CURRENCY_FORMAT;
				//$country_code=isset($country_dets[0]['iso_country_code'])?$country_dets[0]['iso_country_code']:"";
				$zipcode=COMPANY_ZIPCODE;
			}
			else
			{ */
				//$paypal_details = $api_model->paypal_details(); 
				$paypal_details = $api_model->payment_gateway_details();	
				$payment_gateway_username = isset($paypal_details[0]['payment_gateway_username'])?$paypal_details[0]['payment_gateway_username']:"";
				$payment_gateway_password = isset($paypal_details[0]['payment_gateway_password'])?$paypal_details[0]['payment_gateway_password']:"";
				$payment_gateway_key = isset($paypal_details[0]['payment_gateway_key'])?$paypal_details[0]['payment_gateway_key']:"";
				$currency_format = isset($paypal_details[0]['gateway_currency_format'])?$paypal_details[0]['gateway_currency_format']:"";
				$payment_method = isset($paypal_details[0]['payment_method'])?$paypal_details[0]['payment_method']:"";
				$payment_types=isset($paypal_details[0]['payment_type'])?$paypal_details[0]['payment_type']:"";
			//}
			/******* For Paypal Payment Pro**********************/
			if($payment_types==1)
			{ 
					//echo $creditcard_no;exit;
					$product_title = Html::chars('Complete Trip');
					//$payment_type = 'Authorization';
					$payment_action='sale';

					$request  = 'METHOD=DoDirectPayment';
					$request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
					$request .= '&USER=' . urlencode($payment_gateway_username);
					$request .= '&PWD=' . urlencode($payment_gateway_password);
					$request .= '&SIGNATURE=' . urlencode($payment_gateway_key);

					$request .= '&CUSTREF=' . (int)$values['passenger_log_id'];
					$request .= '&PAYMENTACTION=' . $payment_action; //type
					$request .= '&AMT=' . urlencode($amount); //   $amount = urlencode($data['amount']);

					//$request .= '&CREDITCARDTYPE=' . $_POST['cc_type'];
					$request .= '&ACCT=' . urlencode(str_replace(' ', '',$creditcard_no));
					// $request .= '&CARDSTART=' . urlencode($_POST['cc_start_date_month'] . $_POST['cc_start_date_year']);
					$request .= '&EXPDATE=' . urlencode($expdatemonth.$expdateyear);
					$request .= '&CVV2=' . urlencode($creditcard_cvv);
				
					$request .= '&CURRENCYCODE=' .$currency_format;
					//exit;
					$paypal_type=($payment_method =="L")?"live":"sandbox";
					//echo $paypal_type;
					if ($paypal_type=="live") {
						$curl = curl_init('https://api-3t.paypal.com/nvp');
					/*** Billing Address ************/
					$request .= '&FIRSTNAME=' . urlencode($shipping_first_name);
					$request .= '&LASTNAME=' . urlencode($shipping_last_name);
					$request .= '&EMAIL=' . urlencode($shipping_email);
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
					//	print_r($request);
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
					//print_r($nvpstr);	
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
				 
				$_SESSION["paymentresponse"]=$nvpArray;  
				//print_r($_SESSION["paymentresponse"]);exit;
             }
             /******* For Braintree Payment**********************/
             else if($payment_types==2)
             {
				try{
					/** Brain Tree payment gateway **/
					$product_title = Html::chars('Complete Trip');
					$payment_action='sale';
					//require_once DOCROOT.'braintree-payment/lib/Braintree.php';
					require_once(APPPATH.'vendor/braintree-payment/lib/Braintree.php');
					$pay_type=($payment_method =="L")?"live":"sandbox";
					if ($pay_type=="live") 
					{
						Braintree_Configuration::environment('production');
					} else {
						Braintree_Configuration::environment('sandbox');			
					}
					
					Braintree_Configuration::merchantId($payment_gateway_username);//your_merchant_id
					Braintree_Configuration::publicKey($payment_gateway_password);//your_public_key
					Braintree_Configuration::privateKey($payment_gateway_key);//your_private_key
					 
					 $cnumber=str_replace(' ', '',$creditcard_no);
					 $ccv=str_replace(' ', '',$creditcard_cvv);
					 $expirationMonth=$expdatemonth;
					 $expirationYear=$expdateyear;

					$result = Braintree_Transaction::sale(array(

						'amount' => $amount,				    
						'creditCard' => array(
				
						'cardholderName' => $shipping_first_name,
						'number' => $cnumber,//$_POST['creditCard']
						'expirationMonth' =>$expirationMonth,//$_POST['month']
						'expirationYear' => $expirationYear,//$_POST['year']
						'cvv' => $ccv,
						),
						
						'customer' => array(
						'firstName' => $shipping_first_name,
						'lastName' => $shipping_last_name,
						'company' => '',
						'phone' => $shipping_phone,
						'fax' => '',
						'website' => '',
						 'email' => $shipping_email
					  ),
						
						'shipping' => array(
						'firstName' => $shipping_first_name,
						'lastName' => $shipping_last_name,
						'company' => '',
						'streetAddress' => $street,
						'extendedAddress' => '',
						'locality' => $state,
						'region' => $country_code,
						'postalCode' => $zipcode,
						'countryCodeAlpha2' =>$country_code,
					  ) ));
				}
				catch(Braintree_Exception $message) {
					return 0; 
		  	 	}
				
					$braintree_trans_id=array();

					if($result->success){
						$braintree_trans_id['TRANSACTIONID']=$result->transaction->id;
					}else if ($result->transaction) {
							$message = isset($result->transaction->processorResponseText)?$result->transaction->processorResponseText:"Payment Failed";
						$_SESSION['paymentresponse']['L_LONGMESSAGE0']=$message;
						return 0; 	
					}else if($result->message){
					$message = isset($result->message)?$result->message:'Payment Failed';
					$_SESSION['paymentresponse']['L_LONGMESSAGE0']=$message;
					return 0; 
					}
			/******* No Payment Gateway selected **********************/
			}
			else
			{				
				 return 7; 
			}

		if(isset($_SESSION["paymentresponse"]) && !empty($_SESSION["paymentresponse"]) || isset($result->success))
        {
		    $paymentresponse=array();
		    $ack = isset($_SESSION['paymentresponse']["ACK"])?strtoupper($_SESSION['paymentresponse']["ACK"]):'';		                
			if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING" || isset($result->success))
			{                           								
				$invoceno = commonfunction::randomkey_generator();
				if($payment_types==2){
				$paymentresponse['TRANSACTIONID']=$braintree_trans_id['TRANSACTIONID'];
				}else{
				$paymentresponse=$_SESSION['paymentresponse'];
				}
				$transactionfield = $values + $paymentresponse + $siteinfo_details; 
			
				$transaction_detail=$api_model->cancel_triptransact_details($transactionfield,$cancellation_nfree,$payment_types,$driver_userid);

					$phone = $passenger_log_details[0]['passenger_phone'];
					$passenger_log_id = $values['passenger_log_id'];
					//free sms url with the arguments
					if(SMS == 1)
					{
						$api = Model::factory(MOBILEAPI_107);	
						//$this->phone=$this->commonmodel->get_passengers_details($email,1);
						$message_details = $this->commonmodel->sms_message_by_title('payment_cancel');
						$phone = $passenger_log_details[0]['passenger_phone'];
						$to = $phone;
						$message = $message_details[0]['sms_description'];
						//$message = str_replace("##booking_key##",$passenger_log_id,$message);
						$message = str_replace("##SITE_NAME##",SITE_NAME,$message);								
						//$api->send_sms($to,$message);
					}	
						  //Message::success("Payment has been completed succcessfully");
						  $resVal = '1#'.$amount;
						  return $resVal; 
			}
			else
			{
				 $message = isset($_SESSION['paymentresponse']['L_LONGMESSAGE0'])?$_SESSION['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
				 return 0; 
			}		 
		} 
		else
		{		
		         return 0; 
		} 
	}
	/** Function to add money in passenger wallet from paypal or Brain Tree payment gateways **/
	public function wallet_addmoney($values,$default_companyid,$promo_code,$promocodeAmount)
    {   			
		$api_model = Model::factory(MOBILEAPI_107);			
		$passenger_details = $api_model->get_passenger_wallet_amount($values['passenger_id']);
		$shipping_first_name=isset($passenger_details[0]['name'])?$passenger_details[0]['name']:"";
		$shipping_last_name=isset($passenger_details[0]['lastname'])?$passenger_details[0]['lastname']:"";
		$shipping_email=isset($passenger_details[0]['email'])?$passenger_details[0]['email']:"";
		$wallet_amount=isset($passenger_details[0]['wallet_amount'])?$passenger_details[0]['wallet_amount']:"";
		$street = $city = $state = $country_code = $currency_code = $country_code = $zipcode = $paypal_api_username = $paypal_api_password =$paypal_api_signature = $currency_format = "";			
		$creditcard_no = $values['creditcard_no'];
		$creditcard_cvv = $values['creditcard_cvv'];
		$expdatemonth = $values['expmonth'];
		$expdateyear = $values['expyear'];
		$amount = $values['money'];
		$cardholder_name = urldecode($values['cardholder_name']);
		$payment_types = $values['payment_type'];
		$savecard = $values['savecard'];
		
		$paypal_details = $api_model->payment_gateway_bytype($payment_types);	
		$payment_gateway_username = isset($paypal_details[0]['payment_gateway_username'])?$paypal_details[0]['payment_gateway_username']:"";
		$payment_gateway_password = isset($paypal_details[0]['payment_gateway_password'])?$paypal_details[0]['payment_gateway_password']:"";
		$payment_gateway_key = isset($paypal_details[0]['payment_gateway_key'])?$paypal_details[0]['payment_gateway_key']:"";
		$currency_format = isset($paypal_details[0]['gateway_currency_format'])?$paypal_details[0]['gateway_currency_format']:"";
		$payment_method = isset($paypal_details[0]['payment_method'])?$paypal_details[0]['payment_method']:"";
		/******* For Paypal Payment Pro**********************/
		if($payment_types==1)
		{ 
				//echo $creditcard_no;exit;
				$product_title = Html::chars('Add Money');
				//$payment_type = 'Authorization';
				$payment_action='sale';

				$request  = 'METHOD=DoDirectPayment';
				$request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
				$request .= '&USER=' . urlencode($payment_gateway_username);
				$request .= '&PWD=' . urlencode($payment_gateway_password);
				$request .= '&SIGNATURE=' . urlencode($payment_gateway_key);

				$request .= '&CUSTREF=' . (int)$values['passenger_id'];
				$request .= '&PAYMENTACTION=' . $payment_action; //type
				$request .= '&AMT=' . urlencode($amount); //   $amount = urlencode($data['amount']);

				//$request .= '&CREDITCARDTYPE=' . $_POST['cc_type'];
				$request .= '&ACCT=' . urlencode(str_replace(' ', '',$creditcard_no));
				// $request .= '&CARDSTART=' . urlencode($_POST['cc_start_date_month'] . $_POST['cc_start_date_year']);
				$request .= '&EXPDATE=' . urlencode($expdatemonth.$expdateyear);
				$request .= '&CVV2=' . urlencode($creditcard_cvv);
			
				$request .= '&CURRENCYCODE=' ."USD";
				//exit;
				$paypal_type=($payment_method =="L")?"live":"sandbox";
				//echo $paypal_type;
				if ($paypal_type=="live") {
					$curl = curl_init('https://api-3t.paypal.com/nvp');
				/*** Billing Address ************/
				$request .= '&FIRSTNAME=' . urlencode($shipping_first_name);
				$request .= '&LASTNAME=' . urlencode($shipping_last_name);
				$request .= '&EMAIL=' . urlencode($shipping_email);
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
			//print_r($request); exit;
				curl_setopt($curl, CURLOPT_PORT, 443);
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
				curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

				$response = curl_exec($curl); 
				//print_r($response); exit;
				$nvpstr=$response; 		
				curl_close($curl); 
				//print_r($nvpstr);	
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
			 
			$_SESSION["paymentresponse"]=array();
			$_SESSION["paymentresponse"]=$nvpArray;  
			//print_r($_SESSION["paymentresponse"]);exit;
		 }
		 /******* For Braintree Payment**********************/
		 else if($payment_types==2)
		 {
			try{
				/** Brain Tree payment gateway **/
				$product_title = Html::chars('Add Money');
				$payment_action='sale';
				//require_once DOCROOT.'braintree-payment/lib/Braintree.php';
				require_once(APPPATH.'vendor/braintree-payment/lib/Braintree.php');
				$pay_type=($payment_method =="L")?"live":"sandbox";
				if ($pay_type=="live") 
				{
					Braintree_Configuration::environment('production');
				} else {
					Braintree_Configuration::environment('sandbox');			
				}
				Braintree_Configuration::merchantId($payment_gateway_username);//your_merchant_id
				Braintree_Configuration::publicKey($payment_gateway_password);//your_public_key
				Braintree_Configuration::privateKey($payment_gateway_key);//your_private_key
				 
				 $amount=$amount;
				 $firstName=$shipping_first_name;
				 $lastName=$shipping_last_name;
				 $company="";
				 $phone="";
				 $cnumber=str_replace(' ', '',$creditcard_no);
				 $ccv=str_replace(' ', '',$creditcard_cvv);
				 $expirationMonth=$expdatemonth;
				 $expirationYear=$expdateyear;
				 $email=$shipping_email;

				$result = Braintree_Transaction::sale(array(

					'amount' => $amount,				    
					'creditCard' => array(
			
					'cardholderName' => $firstName,
					'number' => $cnumber,//$_POST['creditCard']
					'expirationMonth' =>$expirationMonth,//$_POST['month']
					'expirationYear' => $expirationYear,//$_POST['year']
					'cvv' => $ccv,
					),
					
					'customer' => array(
					'firstName' => isset($passenger_details[0]['name'])?$passenger_details[0]['name']:"",
					'lastName' => isset($passenger_details[0]['lastname'])?$passenger_details[0]['lastname']:"",
					'company' => '',
					'phone' => isset($passenger_details[0]['phone'])?$passenger_details[0]['phone']:"",
					'fax' => '',
					'website' => '',
					 'email' => isset($passenger_details[0]['email'])?$passenger_details[0]['email']:""
				  ),
					
					'shipping' => array(
					'firstName' => $shipping_first_name,
					'lastName' => $shipping_last_name,
					'company' => '',
					'streetAddress' => $street,
					'extendedAddress' => '',
					'locality' => $state,
					'region' => $country_code,
					'postalCode' => $zipcode,
					'countryCodeAlpha2' =>$country_code,
				  ) ));
			}
			catch(Braintree_Exception $message) {
				//print_r($message);exit;
				return 0; 
			}
			
				$braintree_trans_id=array();

				if($result->success){
					$braintree_trans_id['TRANSACTIONID']=$result->transaction->id;
				}else if ($result->transaction) {
						$message = isset($result->transaction->processorResponseText)?$result->transaction->processorResponseText:"Payment Failed";
					$_SESSION['paymentresponse']['L_LONGMESSAGE0']=$message;
					return 0; 	
				}else if($result->message){
				$message = isset($result->message)?$result->message:'Payment Failed';
				$_SESSION['paymentresponse']['L_LONGMESSAGE0']=$message;
				return 0; 
				}
		/******* No Payment Gateway selected **********************/
		}
		else
		{				
			 return 7; 
		}


	/******* Process the next step once we get the response from payment gateway ****************************/
	if(isset($_SESSION["paymentresponse"]) && !empty($_SESSION["paymentresponse"]) || isset($result->success))
	{
		$paymentresponse=array();
		$ack = isset($_SESSION['paymentresponse']["ACK"])?strtoupper($_SESSION['paymentresponse']["ACK"]):'';		                
			if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING" || isset($result->success))
			{                           											
				$invoceno = commonfunction::randomkey_generator();
				if($payment_types==2){
				$paymentresponse['TRANSACTIONID']=$braintree_trans_id['TRANSACTIONID'];
				}else{
				$paymentresponse=$_SESSION['paymentresponse'];
				}							
				/********** Update Wallet Money and Payment Status Status after complete Payments *****************/
				$totalWalletAmount = $wallet_amount + $amount;
				$update_wallet_array  = array("wallet_amount" => $totalWalletAmount);
				$result = $api_model->update_table(PASSENGERS,$update_wallet_array,'id',$values['passenger_id']);
				
				/** Update Promocode used count individual user **/
				if($promo_code != "") 
				{
					$cmModel = Model::factory('commonmodel');
					$cmModel->promocode_used_update($promo_code,$passenger_id);
				}
				
				$correlation_id=isset($paymentresponse['CORRELATIONID'])?$paymentresponse['CORRELATIONID']:'';
				$ack=isset($paymentresponse['ACK'])?$paymentresponse['ACK']:'1';
				$currecncy_code=isset($paymentresponse['CURRENCYCODE'])?$paymentresponse['CURRENCYCODE']:'';
				
				$creditcard_no = encrypt_decrypt('encrypt',$creditcard_no);
				$wallet_fieldArr = array("passenger_id","creditcard_no","card_holder_name","expdatemonth","expdateyear","amount","currency_code","payment_status","payment_type","correlation_id","transaction_id","promocode","promocode_amount");
				$wallet_valueArr = array($values['passenger_id'], $creditcard_no, $cardholder_name, $expdatemonth, $expdateyear, $amount, $currecncy_code, $ack, $payment_types, $correlation_id, $paymentresponse['TRANSACTIONID'],$promo_code,$promocodeAmount);
				$wallet_log = $api_model->add_wallet_log($wallet_fieldArr, $wallet_valueArr);
				//save the card details if savecard param is one
				if($savecard == 1) {
					$card_fieldArr = array("passenger_id","passenger_email","creditcard_no","card_holder_name","expdatemonth","expdateyear");
					$card_valueArr = array($values['passenger_id'], $shipping_email, $creditcard_no, $cardholder_name, $expdatemonth, $expdateyear);
					$api_model->add_credit_card_details($card_fieldArr, $card_valueArr);
				}
				/***********************************************************************************/							
				return '1#'.$totalWalletAmount; 	
			}   
			else
			{ 
				$message = isset($_SESSION['paymentresponse']['L_LONGMESSAGE0'])?$_SESSION['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
				return 0; 
			}	
	   } 
	   else
	   {           
				$message = isset($_SESSION['paymentresponse']['L_LONGMESSAGE0'])?$_SESSION['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
				return 0; 
	   }

	}
	
	/** function to check password and confirm password are same **/
	public static function checkwithpassword($confirmPass, $password)
	{
		if($confirmPass != $password){
			return false;
		} else {
			return true;
		}
	}
	/* wallet add money form validation */
	function wallet_addmoney_validation($array)
	{
		 return Validation::factory($array)
				->rule('passenger_id','not_empty')
				->rule('creditcard_no','not_empty')
				->rule('creditcard_no','min_length', array(':value', '9'))	
				->rule('creditcard_no','max_length', array(':value', '16'))	
				->rule('expmonth','not_empty')
				->rule('expyear','not_empty')
				->rule('money','not_empty');
				//->rule('cardholder_name','not_empty');		
	}
	/** Split fare approval status validation **/
	public function checkApprovalValidation($array)
	{
		return Validation::factory($array)
				->rule('friend_id','not_empty')
				->rule('approve_status','not_empty')
				->rule('trip_id','not_empty');	
	}
	/** Deduct wallet amount from primary and secondary passengers **/
	public function deductWalletAmount($passengers_id,$walletAmount,$referralSettings,$total_fare,$usedAmount,$trip_id="")
	{
		$totalfare = $total_fare;
		$datas = array();
		//print_R($trip_id); echo "-"; print_r($walletAmount); echo "-"; print_r($total_fare); 
		$api = Model::factory(MOBILEAPI_107);
		$passenger_referral = $api->check_passenger_referral_amount($passengers_id);
		//$passenger_wallet = $api->get_passenger_wallet_amount($passengers_id);
		if($referralSettings == 1 && count($passenger_referral) > 0 ) {
			if($total_fare > $passenger_referral[0]['referral_amount']) {
				$total_fare = $total_fare - $passenger_referral[0]['referral_amount'];
			} else {
				$total_fare = 0;
			}
			$balance_wallet_amount = $walletAmount - $passenger_referral[0]['referral_amount'];
			//update wallet amount in passenger table
			$update_wallet_array = array("wallet_amount" => $balance_wallet_amount);
			// Adding credit/debit  data to DB
			$datas['receiver_id']=isset($passengers_id)?$passengers_id:"";
			$datas['receiver_pervious_balance']=isset($walletAmount)?$walletAmount:"";
			$datas['receiver_after_balance']=isset($balance_wallet_amount)?$balance_wallet_amount:"";
			$datas['transfer_amount']=isset($total_fare)?$total_fare:"";
			$datas['transfer_type']=2;
			$datas['comments']="Referral Amount";
			$datas['trip_id']="";
			
			$common_model = Model::factory('commonmodel');			
		   
			$add_logs = $common_model->set_passenger_cash_transfer($datas);
			// Adding credit/debit  data to DB -- END
			
			$wallet_update = $api->update_table(PASSENGERS,$update_wallet_array,'id',$passengers_id);
			//to add the referral amount in referred passengers( the person whose referral code is used ) wallet 
			$referredPass = $api->passenger_detailsbyreferralcode($passenger_referral[0]['referral_code']);
			$refeWallAmount = isset($referredPass[0]['wallet_amount']) ? $referredPass[0]['wallet_amount'] : 0;
			$tot_wallAmount = $refeWallAmount + $passenger_referral[0]['referral_amount'];
			//update wallet amount in passenger table
			$update_wallet_referred = array("wallet_amount" => $tot_wallAmount);
			$api->update_table(PASSENGERS,$update_wallet_referred,'id',$referredPass[0]['id']);
			//update referral amount used status in passenger referral table
			$update_referral_used = array("referral_amount_used" => "1");
			$referral_used_update = $api->update_table(PASSENGER_REFERRAL,$update_referral_used,'passenger_id',$passengers_id);
			//wallet amount used
			$usedAmount = $passenger_referral[0]['referral_amount'];
		} else {
			//if($referralSettings == 1 && $walletAmount != 0) {
			if($walletAmount != 0) {
				if($total_fare > $walletAmount) {
					$total_fare = $total_fare - $walletAmount;
					$balance_wallet_amount = 0;
					//wallet amount used
					$usedAmount = $walletAmount;
				} else {
					//wallet amount used
					$usedAmount = $total_fare;
					$balance_wallet_amount = $walletAmount - $total_fare;
					$total_fare = 0;
				}
				// Adding credit/debit  data to DB
				$datas['receiver_id']=$passengers_id;
				$datas['receiver_pervious_balance']=$walletAmount;
				$datas['receiver_after_balance']=$balance_wallet_amount;
				$datas['transfer_amount']=$totalfare;
				$datas['transfer_type']=2;
				$datas['comments']="Trip Amount";
				$datas['trip_id']=$trip_id;
				
				$common_model = Model::factory('commonmodel');			
			   
				$add_logs = $common_model->set_passenger_cash_transfer($datas);
				// Adding credit/debit  data to DB -- END
				$update_wallet_array = array("wallet_amount" => $balance_wallet_amount);
				$wallet_update = $api->update_table(PASSENGERS,$update_wallet_array,'id',$passengers_id);
			} else {
				$usedAmount = $walletAmount;
			}
		}
		return array($usedAmount,$total_fare);
	}
	
} // End Website
