<?php defined('SYSPATH') or die('No direct script access.');

/****************************************************************

* website controller - Contains abstract class of front end 

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************************************/

abstract class Controller_Website extends Controller_Template
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

		//Include defined Constants files
		//require Kohana::find_file('classes','table_config');
		//require Kohana::find_file('classes','common_config');
		
		//4sq file include
		//require Kohana::find_file('classes','4sq_config.php');
		
		//Models declaration
		$commonmodel=Model::factory('commonmodel');
		if(file_exists(DOCROOT.'application/classes/common_config.php')){
			require Kohana::find_file('classes','common_config');
		}
		$siteusers = Model::factory('siteusers');
		//$driver = Model::factory('driver');
		//$passengers = Model::factory('passengers');
		$this->findMdl = Model::factory('find');
		$this->authorize=Model::factory('authorize');
		//$this->commonmodel=Model::factory('commonmodel');
		$this->managemodel=Model::factory('managemodel');
		$this->site=Model::factory('site');
		$this->emailtemplate=Model::factory('emailtemplate');
		
		$this->lang =$this->session->get('lang');
		if($this->lang !=""){
			$lang=$this->lang;
		}else{
			$lang="en";
		}
		$this->currlang=I18n::lang($lang);
		$this->javascript_language=json_encode(I18n::load($this->lang));
        
		$this->usertype=$this->session->get('user_type');
		$this->username=$this->session->get('username');
		$this->name=$this->session->get('name');
		$this->user_name=$this->session->get('user_name');
		$this->firstname=$this->session->get('first_name');
		//$this->userid=$this->session->get('userid');

		View::bind_global('usertype',$this->usertype);
		View::bind_global('username',$this->username);
		View::bind_global('name',$this->name);
		View::bind_global('user_name',$this->user_name);
		View::bind_global('first_name',$this->firstname);
		View::bind_global('js_language',$this->javascript_language);
		View::bind_global('language',$this->lang);
		//View::bind_global('userid',$this->userid);
		
		$langArr = array("en"=>"English","ar"=>"Arabic","fr"=>"French","tr"=>"Turkish","de"=>"Germen","ru"=>"Russian","es"=>"Spanish");
		View::bind_global('langArr',$langArr);
		//passengers model
        $id =$this->session->get('id');  
        //$passengermodel = Model::factory('passengers');
        //exit;
		
		$field_array = array('banner_image','banner_content','app_content','passenger_app_android_store_link','passenger_app_ios_store_link', 'app_android_store_link', 'app_ios_store_link', 'app_bg_color', 'about_us_content', 'about_bg_color', 'footer_bg_color','contact_us_content', 'facebook_follow_link', 'google_follow_link', 'twitter_follow_link');
		$home_array = array();
		if(count($this->siteinfo) > 0){
			$siteinfo = $this->siteinfo[0];
			foreach($field_array as $field){
				$home_array[$field] = (isset($siteinfo[$field]) && $siteinfo[$field]!="")?$siteinfo[$field]:"";
			}
		}
		View::bind_global('home_array', $home_array);

        if($id != ''){
							
			$upcomming_trips = $siteusers->get_upcomming_trips(false,$id);
			$data = json_decode($upcomming_trips);
			define('ALERT',$upcomming_trips);
			
			$details = Commonfunction::get_user_wallet_amount();
			define('PASSENGER_WALLET',$details['wallet_amount']);
			define('PASSENGER_REFERRALCODE',$details['referral_code']);
			
			/* $upcomming_trips_alert = $siteusers->get_upcoming_trips_alert($session->get("id"));
			($upcomming_trips_alert <= 30) ? define('UPCOMING_ALERT',$upcomming_trips_alert) : define('UPCOMING_ALERT',0); */			
			
			//~ $user_det=$passengermodel->select_current_user($id);			
			//~ View::bind_global('user_det', $user_det);
			//~ $this->session->set('user_det',$user_det);
		}
		
		//store admin authentication
		/*if($this->usertype==STOREADMIN)
		{
			$this->authenticate_storeid=$this->commonmodel->get_store_id($this->adminid);
			View::bind_global('storeadmin_auth',$this->authenticate_storeid);
		}*/
		
		//Get cookie values if cookie is set and apply to session variables
		Cookie::$salt='userid';
		$cookie=Cookie::get('userid');
		if($cookie)
		{
			$this->session->set("userid",$cookie);
			$user_details=$this->authorize->select_user_details_by_id($cookie);
			if(count($user_details)>0){
				$this->session->set("user_type",$user_details[0]['user_type']);
				$this->session->set("email",$user_details[0]['email']);
			}
		}
		$this->userid='';
		//Css & Script include for admin
		/**To Define path for selected theme**/
		define("ADMINIMGPATH",URL_BASE.'public/admin/images/');
		define("CSSADMIN",URL_BASE.'public/admin/');
		define("ADMINCSSPATH",CSSADMIN.'css/');		
		

		//Users Themes
		define("THEME","default/");
		define("USERVIEW","themes/".THEME);
		define("CSSPATH","public/css/");

		$this->template = USERVIEW.'template';

	
		$id =$this->session->get('id');
		$usertype =$this->session->get('usertype');
		//$userid = $this->session->get('userid');
		$usrid =$id ;// isset($userid)?$userid:$id;
		/*if($id != ''){			
			if($usertype == 'passengers'){				
				$usr_details = $passengers->get_passenger_profile_details($usrid);
			}
			else{				
				$usr_details = $driver->get_my_profile_details($usrid);
			}			
		}*/
		/** for header menu info **/
		//$menuorder = $siteusers->menu_listingorder();
		$menuorder = array();
		View::bind_global('menuorder',$menuorder);
		/** for footer info **/
		//$footer_contents = $siteusers->footer_contents();
		$footer_contents[0]['site_favicon'] = SITE_FAVICON;
		View::bind_global('footer_contents',$footer_contents);
		
		View::bind_global('usrid',$usrid);
		//View::bind_global('usr_details',$usr_details);
		View::bind_global('usertype',$usertype);
		View::bind_global('global_session',$this->session );
		$companyId = $this->session->get('company_id');
		if($companyId > 0)
		{
			$company_content= $commonmodel->getcompanycontent($companyId);
			View::bind_global('company_content',$company_content);
			foreach($company_content as $cc)
			{
				Route::set($cc['page_url'], $cc['page_url'].'.html')
					->defaults(array(
						'controller' => 'page',
						'action'     => 'companycms'
					));	
			}
		}
		if(!isset($usrid) && $action != 'signup' && $action != 'forgotpassword' && $action!='foursquare_connect' && $action!='twittersignin' && $action!='linkdin_signin'){
			$userstyles = array(CSSPATH.'layout_home.css' =>'screen',
							CSSPATH.'mobile_slider/skin.css' =>'screen');
		}else{
			$userstyles = array(CSSPATH.'layout.css' =>'screen',
							CSSPATH.'mobile_slider/skin.css' =>'screen');		
		
		}
							
		$userscripts = array(SCRIPTPATH.'jquery-1.4.2.min.js',
							SCRIPTPATH.'lightbox-form.js',
							SCRIPTPATH.'rating.min.js',
							SCRIPTPATH.'text_sahdow.js');	
							
		//$this->app_name=$this->commonmodel->select_site_settings('app_name',SITEINFO);		
		//$this->app_description=$this->commonmodel->select_site_settings('app_description',SITEINFO);		
		//$this->siteemail=$this->commonmodel->select_site_settings('email_id',SITEINFO);
		//$this->notification_time = $this->commonmodel->select_site_settings('notification_settings',SITEINFO);

		//DEFINE("SITENAME",$this->app_name);
		$this->app_name = SITENAME;
		$this->siteemail = SITE_EMAILID;
		$this->app_description = SITE_APP_DESCRIPTION;
		$this->notification_time = SITE_NOTIFICATION_SETTING;
		//print_r($action);exit;
		if($action) {
			$meta_data = $commonmodel->get_meta_settings('meta_keyword,meta_description,meta_title',$action);
			//echo '<pre>';print_r($meta_data);exit;
			$this->meta_title = $meta_data[0]["meta_title"];
			$this->meta_description = $meta_data[0]["meta_description"];
			$this->meta_keyword = $meta_data[0]["meta_keyword"];
		}
		($this->meta_keyword == '') ? $this->meta_keyword = SITE_METAKEYWORD : "";
		($this->meta_description == '') ? $this->meta_description = SITE_METADESCRIPTION : "";
		($this->meta_title == '') ? $this->meta_title = $this->app_name : "";

		/*$getMiles = $this->findMdl->getMiles();
		foreach($getMiles as $mile){
			//$m .= '"'.$mile['mile_name'].'" => "'.$mile['mile_name'].'"'.',';
			$miles[$mile['mile_name']] = $mile['mile_name'];
		}*/
	
		/*$miles = array(
					"5" => "5 miles",
					"10" => "10 miles",
					"20" => "20 miles",
					"25" => "25 miles"
				 );*/

        $waitingtime = array(
					"15" => "15 Mins",
					"30" => "30 Mins",
					"45" => "45 Mins",
					"60" => "60 Mins"
				 );
				 
        	$this->session->set("miles",5);
        
        	if($action)
        	{ 
        		
			//$this->meta_keyword=$this->commonmodel->get_meta_settings('meta_keyword',$action);		
			//$this->meta_description=$this->commonmodel->get_meta_settings('meta_description',$action);
			//$this->meta_title = $this->commonmodel->get_meta_settings('meta_title',$action);
	    	}
	    	
	    	if($this->meta_keyword == '')
	    	{
			//$this->meta_keyword = $this->commonmodel->select_site_settings('meta_keyword',SITEINFO);			    	
	    	}

	    	if($this->meta_description == '')
	    	{
			//$this->meta_description = $this->commonmodel->select_site_settings('meta_description',SITEINFO);			    	
	    	}
	    	
	    	if($this->meta_title == '')
	    	{
	    		//$this->meta_title = $this->app_name;			    	
	    	}
	        if($companyId==0)
	        {
				$this->title=$this->app_name; //
				$this->meta_keywords=$this->meta_keyword; //
				$this->meta_description=$this->meta_description; //
            }
            else
            {
				$this->title=COMPANY_APP_NAME; //
				$this->meta_title = COMPANY_META_TITLE;
				$this->meta_keywords=COMPANY_META_KEYWORD; //
				$this->meta_description=COMPANY_META_DESCRIPTION; //				
			}
		
		
		View::bind_global('meta_description',$this->meta_description);
		View::bind_global('page_title',$this->title);
		View::bind_global('meta_keywords',$this->meta_keywords);
		View::bind_global('meta_title',$this->meta_title);
		
		
		View::bind_global('miles', $miles);
		View::bind_global('waitingtime', $waitingtime);//
		View::bind_global('notification_time', $notification_time);		

        
		View::bind_global('currency_code',$this->all_currency_code);
		//View::bind_global('currency_symbol',$this->currency_symbol);
		View::bind_global('success_msg', $this->success_msg);
		View::bind_global('failure_msg', $this->failure_msg );

		View::bind_global('app_name',$this->app_name);
		//View::bind_global('currencysymbol',$this->currencysymbol);
		View::bind_global('siteemail',$this->siteemail);
		
		View::bind_global('styles', $userstyles);
		View::bind_global('scripts', $userscripts);		
		
		View::bind_global('action', $action );
		View::bind_global('controller', $controller);
        
		$this->currenttimestamp=$this->currenttimestamp();
 
 
		  /* 
			$footermenu=$this->site->getallfooter();
			View::bind_global('footermenu', $footermenu);
			$footersocial=$this->site->getallsocialfooter();
			View::bind_global('footersocial',$footersocial);        
			
			$socialvideo=$this->site->getvideo();
			View::bind_global('socialvideo',$socialvideo);  
		    */    
        
		/*             
				//functionality for contact us
				//================================
				$contact_submit =arr::get($_REQUEST,'contact');
				
		    if ($contact_submit && Validation::factory($_POST) )
		    {
			$validator1 = $siteusers->validate_contact_form(arr::extract($_POST,array('name','email1','message')));
		       
			    if ($validator1->check())
			    {
				   
				     $result = $siteusers->add_contact_details($_POST);
				 
				    if($result)
				    {
					    $mail="";
				       
					    $replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_EMAIL=>$_POST['email1'],MESSAGE=>$_POST['message'],REPLACE_SITEURL=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITELINK => URL_BASE);
					    $message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'Contact.html',$replace_variables);
					    $mail=array("to" => $_POST['email1'],"from"=>$this->siteemail,"subject"=>"Contact Us","message"=>$message);
					    $emailstatus=$this->email_send($mail,'smtp');                       

		   
					    Message::success(__('sucessful_contact_add'));

					    $request->redirect("/");
				    }  
			    }
			   
			    else
			    {
			    //validation failed, get errors
			    $errors1 = $validator1->errors('errors');
			    //
			    }

		    }
				//api key generation ends here
				View::bind_global('errors1', $errors1);		
				View::bind_global('validator1', $validator1);         
			*/
	
		
			
		
			/*	
				$get_ip = Request::$client_ip;
				try{
					//default NDOT IP
					$details = file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=6fc8481b8fb162f8d6802111c55518aa1bcfaa13d16b87b05288a11d818f4bd4&ip=182.72.62.190");
					//echo $details;exit;
					$det = array(';');
					$detail = str_replace($det,",",$details);
					$val = explode(",",$detail);
					$this->get_location = $val;
				
				
				}
				catch  (Exception $error){
				
					Message::error(__('pbm_connect_network'));
				
				}	
				   
				$this->get_location =array();	
				View::bind_global('location_latlong',$this->get_location);
		
		*/


		
		/*	
			$add_tags =arr::get($_REQUEST,'smarttag');
			$added_tags = arr::get($_REQUEST,'tagval');
			
			if (isset($add_tags)) 
			{					
  					$details = $siteusers->add_smart_tags($usrid,$added_tags);	
					if($details > 0){											
						Message::success(__('sucessful_add_smarttag'));
						$request->redirect("/");
					}	
			} 
			
			if(isset($_GET['id']) && isset($_GET['text']))
			{
				$index=$_GET['id'];
				$text=$_GET['text'];
				$update=$this->authorize->get_smart_tags($usrid,$index,$text);
				if($update)
				{				                                                
					Message::success(__('sucessful_update_smarttag'));$request->redirect("/"); 
				}
			}
			
			$tag_len = $siteusers->get_tag_count($usrid);
			
			View::bind_global('tag_len', $tag_len);		
			View::bind_global('tag_len_count', $tag_len[0]['smart_tags']);		
			View::bind_global('added_tags',$added_tags);	
			
		*/
			
		    //$ct_list=$siteusers->count_care_details($usrid);
		    // View::bind_global('ct_list',$ct_list);
		    // $care_message=$siteusers->get_care_message_count($usrid);
		    // View::bind_global('care_message',$care_message);
			
			View::bind_global('data', $_POST);				
		
		/*		
			//check s/n account whether activate/deactivate
		    $Socialnetwork = Model::factory('Socialnetwork');
		    $site_socialnetwork = $Socialnetwork->get_site_socialnetwork_account(FACEBOOK,$usrid);
		    $site_twitter_socialnetwork = $Socialnetwork->get_twitter_account(TWITTER,$usrid);
		    $site_linkedin_socialnetwork = $Socialnetwork->get_linkedin_account(LINKDIN,$usrid);
		    
			View::bind_global('site_socialnetwork', $site_socialnetwork);		
			View::bind_global('site_twitter_socialnetwork', $site_twitter_socialnetwork);	
			View::bind_global('site_linkedin_socialnetwork', $site_linkedin_socialnetwork);	
			
		*/		

		    $ip=$_SERVER['REMOTE_ADDR'];	
			$ip =IPADDRESS; 

			
	}


	/**
	 * ****action_email_send()****
	 * @E-Mail function calls from here
	 */		
	public function email_send(array $mail,$type='smtp',$htmlneed=true)
	{
		if(is_array($mail))
		{
			if($this->array_keys_exists($mail,array('to','from','subject','message')))
			{
				$to=$mail['to'];
				$from=$mail['from'];
				$subject=$mail['subject'];
				$message=$mail['message'];
				$headers = 'MIME-Version: 1.0'. "\r\n";
				$headers .= "Content-language: he" . "\r\n";
				$headers .= 'Content-type:text/html;charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '. $from . "\r\n";
				switch($type)
				{		
					case "smtp":
					 //mail send thru smtp
					
					 $this->siteusers=Model::factory('siteusers');
					 $smtp_detail=$this->siteusers->get_smtpdetails();
					 $smtp_config="";
					 if(isset($smtp_detail[0]))
					 {
						$host= $smtp_detail[0]['smtp_host'];
						$username=$smtp_detail[0]['smtp_username'];
						$password=$smtp_detail[0]['smtp_password'];
						$port=$smtp_detail[0]['smtp_port'];
						$smtp_config = array('driver' => 'smtp','options' => array('hostname'=>$host,
									'username'=>$username,'password' =>$password,
									'port' => $port,'encryption' => 'ssl')); 	
					 }		
					 $smtp_config1 = array('driver' => 'smtp','options' => array('hostname'=>'smtp-mail.outlook.com',
								  'username'=>'info@getuptaxi.com','password' =>'ndotadmin',
									'port' => '587','encryption' => 'tls'));

					
						//mail sending option here
						$connect_result = Email::connect($smtp_config1);
						
						try{
							if(Email::connect($smtp_config1))
							{                         	                      
								if(Email::send($to,$from,$subject,$message,$html = $htmlneed)==0)
								{                                  		                
									return 1;
								}
								return 0;
							}
						}
						catch(Exception $e)
						{
							try{
								if(mail($to, $subject,$message,$headers))
								{                               
									return 1;
								}
							}
							catch(Exception $e)
							{
								return 0;
							}
						}	

						break;
					default:
						if(mail($to, $subject,$message,$headers))
						{                               
							return 1;
						}
						break;
				}
			}
			else
			{
				return 2;
			}
		}
	}
	
	/**
	 * ***action_array_keys_exists()****
	 * ** User Defined Function **
	 * @return check array exist otr not
	 */
	public function array_keys_exists($array,$keys) 
	{
		foreach($keys as $k) 
		{
			if(!isset($array[$k])) 
			{
				return false;
			}
		}
		return true;
	}
	
	public function action_index()
	{				
		$view=View::factory(USERVIEW.'home');		
		$this->template->content=$view;	
	}
	
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
			
	 public function  network_activity( $oauth_token, $oauth_token_secret, $endpoint)
	 {
	
		$req_token = new OAuthConsumer($oauth_token, $oauth_token_secret, 1);
 
		$profile_req = OAuthRequest::from_consumer_and_token($this->test_consumer,$req_token, "GET", $endpoint, array());
	
		$profile_req->sign_request($this->sig_method, $this->test_consumer, $req_token);
			
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER,array (
				$profile_req->to_header()
		));
		curl_setopt($ch, CURLOPT_URL, $endpoint);
		$output = curl_exec($ch);
		
		if(curl_errno($ch)){
			echo 'Curl error 2: ' . curl_error($ch);
		}
		curl_close($ch);
		return $output;

	 }		 
	 
	/**
	 * ****action_currenttimestamp()****
	 * @return time format
	 */
	public function currenttimestamp()
	{
		return date("Y:m:d H:i:s",time());		
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
	
	/*
	 
	// POST STATUS UPDATE 
	public function facebook_status_update($Status_Message ="",$image)
	{
		$id =$this->session->get('id');
		$userid = $this->session->get('userid');
		$usrid = isset($userid)?$userid:$id;
		$siteusers = Model::factory('siteusers');		
	
		//get the facebook userid and token id
		$fb_user_check = $siteusers->check_fb_account_exist($usrid);	
		
		
		//$count_facebook_info = $siteusers->select_fb_account(1,TRUE);
		$fb_user_id = '';
		$fb_access_token = '';
		if(count($fb_user_check) > 0)
		{
				$fb_user_id = $fb_user_check[0]["fb_user_id"];
				$fb_access_token = $fb_user_check[0]["access_key"];
		}

		
		if($fb_access_token && $fb_user_id)
		{

			$Post_url = "https://graph.facebook.com/feed";	//exit;
			$post_arg = array("access_token" => $fb_access_token, "message" => $Status_Message,"id" => $fb_user_id,"method" => "post","scope" => "publish_stream,manage_pages" );
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $Post_url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_TIMEOUT, 100);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$type = "POST";
			if($type == "POST"){
		
				curl_setopt($ch, CURLOPT_POST, 1);			
				curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post_arg));
			
			}
		
			$result = curl_exec($ch);  print_r($result); exit;
		
			curl_close ($ch);
			
			return $result; 
		}
		return;
	}
	
	public function twitter_status_update($Status_Message = "",$image)
	{

		//require Kohana::find_file('classes/controller','oauth');
		require Kohana::find_file('classes/twitter','OAuth');
		require Kohana::find_file('classes/twitter','twitterOAuth');
	
		$share_link ="http://scriptfiverrclone.com";//exit;
		
		// get the short url 
		$short_url = $this->get_bitly_short_url($share_link,'karthikavel','R_ff285853e5765e8f7879184f0d4107b8');			
	
		//$tweet_message = $short_url;	

		$consumer_key = "mO3lcBCttO1hMzf1Wam4A";
		$consumer_secret ="f6l9mIgM8NHEoBwZKseLgHt0z91W8fykhUaBG06y2g";		

		
		$id =$this->session->get('id');
		$userid = $this->session->get('userid');
		$usrid = isset($userid)?$userid:$id;
		$socialnetwork = Model::factory('socialnetwork');
		
		$get_twitter_details = $socialnetwork->check_twitter_account($usrid);
		
		
		if(count($get_twitter_details) > 0){

		
			$oauth_access_token = $get_twitter_details[0]['oauth_token'];
			$oauth_access_token_secret = $get_twitter_details[0]['oauth_token_secret'];

		}
		
		// Create TwitterOAuth with app key/secret and user access key/secret
		$to = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret);

		$tweet_message = $Status_Message;

		$content = $to->OAuthRequest('https://twitter.com/statuses/update.xml', array('status' => $tweet_message), 'POST');
	
		return $content;	

	}	

	
	 // POST STATUS UPDATE 	 
	 public function post_updates($oauth_token, $oauth_token_secret , $status = "", $endpoint = "" )
	 {	
		require Kohana::find_file('classes/controller','oauth');
		//require_once('oauth.php');
		//require_once('feed.php');
		
		$this->domain = "https://api.linkedin.com/uas/oauth";
		$this->sig_method = new OAuthSignatureMethod_HMAC_SHA1();

		define(LINKDIN_API_KEY,"qseccnfij011");
		define(LINKDIN_SECRET_KEY,"HRYuAPFAGdiGfRC7");

		$this->test_consumer = new OAuthConsumer(LINKDIN_API_KEY, LINKDIN_SECRET_KEY, NULL);
		
		$this->callback = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?action=getaccesstoken";	
		$status ="test";

		$xml = '<?xml version="1.0" encoding="UTF-8"?>
		<current-status>'.$status.'</current-status>';
		
		$req_token = new OAuthConsumer($oauth_token, $oauth_token_secret, 1);
		
		$profile_req = OAuthRequest::from_consumer_and_token($this->test_consumer,$req_token, "PUT", $endpoint, array());
		
		$profile_req->sign_request($this->sig_method, $this->test_consumer, $req_token);
		
		$headers[] = $profile_req->to_header();
		$headers[] = 'Content-Type: text/xml';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		$output = curl_exec($ch);
	
		if(curl_errno($ch)){		
			echo 'Curl error 2: ' . curl_error($ch);
		}
		curl_close($ch);
		
		return $output;
	 }
	 
	*/

	
} // End Website
