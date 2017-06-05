<?php defined('SYSPATH') or die('No direct script access.');

/****************************************************************

* Contains API details - Version 6.0.0

* @Package: Taximobility

* @Author:  NDOT Team

* @URL : http://www.ndot.in

****************************************************************/
Class Controller_TaximobilityMobileapi119 extends Controller_Mobile104
{

	public function __construct()
	{	
		ob_start();
		try {
		//require Kohana::find_file('classes','table_config');
		require Kohana::find_file('classes','mobile_common_config');		
		$this->commonmodel=Model::factory('commonmodel');
		DEFINE("MOBILEAPI_107","mobileapi119");
		DEFINE("FIND","find114");

		if((COMPANY_CID !='0'))
		{
			$this->app_name = COMPANY_SITENAME;
			$this->siteemail= COMPANY_CONTACT_EMAIL;
			$this->domain_name = SUBDOMAIN;
		}
		else
		{
			 $this->siteemail=SITE_EMAIL_CONTACT;//$this->commonmodel->select_site_settings('email_id',SITEINFO);
			$this->app_name = SITE_NAME;//$this->commonmodel->select_site_settings('app_name',SITEINFO);
			$this->app_name = preg_replace("/#?[a-z0-9]+;/i","",$this->app_name); // Remove &amp; tag from site name
			 $this->domain_name='site';
		}
		$this->lang = I18n::lang(LANG);
		$this->app_description=APP_DESCRIPTION;	
		$this->emailtemplate=Model::factory('emailtemplate');
		$this->notification_time = ADMIN_NOTIFICATION_TIME;
		$this->customer_google_api = CUSTOMER_ANDROID_KEY;//$this->commonmodel->select_site_settings('customer_android_key',SITEINFO); // For GCM
		//$this->driver_android_api = $this->commonmodel->select_site_settings('driver_android_key',SITEINFO);	// For GCM
		//$this->customer_app_url = $this->commonmodel->select_site_settings('customer_app_url',SITEINFO);	
		//$this->driver_app_url = $this->commonmodel->select_site_settings('driver_app_url',SITEINFO);		
		//$this->google_geocode_api = $this->commonmodel->select_site_settings('google_geocode_api',SITEINFO);		
		$this->continuous_request_time = CONTINOUS_REQUEST_TIME;
		$this->currentdate=Commonfunction::getCurrentTimeStamp();
		}
		catch (Database_Exception $e)
		{
			 // Insert failed. Rolling back changes...
			// print_r($e);
			$message = array("message" => __('Database Connection Failed'),"status" => 2);			
			echo json_encode($message);
			exit;
		}
	}
	
	function action_encrypt_decrypt($action, $string) {
		
          $output = false;

          $key = 'Taxi Application Project';

          // initialization vector 
          $iv = md5(md5($key));

          if( $action == 'encrypt' ) {
              $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
              $output = base64_encode($string);
          }
          else if( $action == 'decrypt' ){
              $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
              //$output = rtrim($output, "");
              $output = base64_decode($string);
          }
          
          return $output;
    }
       
       
	public function action_index()
	{
	
		$find_url = explode('/',urldecode($_SERVER['REQUEST_URI']));  	
		//print_r($find_url);
		$split = explode('?',$find_url[3]);
		$company_api_encrypt = trim($split[0]);

		/*$company_api_key = "ntaxi"."_"."tagmytaxi";
		$encrypted_txt = $this->encrypt_decrypt('encrypt', $company_api_key);
		echo "Encrypted Text = $encrypted_txt\n";
		exit;*/


		/*$company_api_key = "ntaxi"."_"."eoqITaphgQ7f6ZcBTF85VSSlRwPeAdIZQT";
		$encrypted_txt = $this->encrypt_decrypt('encrypt', $company_api_key);
		echo "Encrypted Text = $encrypted_txt\n";
		exit;
		//*/
		//bnRheGlfUkg3UFZzS0UxOHFHZTZZNllPQzhrZFJudE9FeURuRDB1Vw==
		//exit;*/

		$company_api_decrypt = $this->encrypt_decrypt('decrypt', $company_api_encrypt);		
		$company_split = explode('_',$company_api_decrypt);
		$company_api_key = trim($company_split[1]);
		//print_r($company_api_key);		
		$api = Model::factory(MOBILEAPI_107);	
		/// We are getting the date from mobile as urlencoded format in POST method
		$mobile_encodeddata = $beforeApi= file_get_contents('php://input');
		$mobile_encodeddata = $this->encrypt_decrypt('decrypt', $mobile_encodeddata);	
		// Here we are decode the url encoded values and conver the values in to array
		$mobiledata =  (array)json_decode($mobile_encodeddata,true);
		//echo "<pre>"; print_r(json_encode($mobiledata)); exit;
		$errors = array();		
		if(isset($_REQUEST["type"])){
		        $method = $_REQUEST["type"];
		}
		$apikey_result =array();
		$host  = $_SERVER['HTTP_HOST'];
		$dateStamp = $_SERVER['REQUEST_TIME'];
		
		if(!isset($_REQUEST["type"])){
		        $message = array("message" => "Invalid Request ","status" => 2);
			echo json_encode($message);
			exit;
		
		}
		
		if((string)$method!='driver_location_history' && (string)$method!='getpassenger_update')
        {
        	if (!file_exists(DOCROOT."api.txt")){
        		$newFile= fopen(DOCROOT."api.txt", 'w+');	
        		fclose($newFile);
        		chmod(DOCROOT."api.txt", 0777);
        	}
        	 
		    
        @file_put_contents(DOCROOT."api.txt","Method <b style='color:red;'>".$method."</b><br/>".'Raw Data'."<br/>"."<br/>".json_encode($mobiledata)."<br/><br/>".'PostMan Request'."<br/>"."<br />".$beforeApi."<br/><br/>"."Time is ".date('Y-m-d H:i:s')."<br/>"."<br/>" . PHP_EOL, FILE_APPEND);
        }
		
		//CHECK KEY CHECK
		if(($method != "check_companydomain") && ($method != "get_authentication") ){
			if($_REQUEST['encode']){
				$mobileresponcr = $_REQUEST['encode'];
				$mobile_encodeddata = $this->encrypt_decrypt('decrypt', $mobileresponcr);

				$mobiledatestamp = substr($mobile_encodeddata,-10);
				$mobilehost  = substr($mobile_encodeddata,0,-11);
				
				
				$add = $mobiledatestamp+1800;
				$min = $mobiledatestamp-1800;
				if($mobilehost){
				//echo $mobilehost.'---'.$host;exit;
					if($mobilehost == $host){							
						if(($dateStamp > $add) || ($dateStamp < $add) && ($dateStamp > $min) || ($dateStamp < $min) ){
							//$encode = array("key"=>base64_encode($key));
							//$message = array("message" =>__('success'),"key" => $encode,"status" => 1);
						} else {
							$message = array("messagekey" => "Time Stamp does not match","status" => "-16");
							echo json_encode($message);
							exit;
						}
							
					} else {
						$message = array("messagekey" => "Host does not match","status" => "-16");
						echo json_encode($message);
						exit;
					}
					
				} else {
					$message = array("messagekey" => "Host not valid ","status" => "-16");
					echo json_encode($message);
					exit;
				}
			}
		}
		
		//dGF4aV9hbGw= => All
		//CHECK FOR VALID API KEY

		if($company_api_key != 'getuptaxi')
		{
		$apikey_query = "select company_cid,company_currency,company_app_description from ".COMPANYINFO." left join ".COMPANY." on ".COMPANY.".cid=".COMPANYINFO.".company_cid where company_api_key='".$company_api_key."' and company_status='A'";

		$apikey_result =  Db::query(Database::SELECT, $apikey_query)
			->execute()
			->as_array();
		}
		
		if((count($apikey_result) > 0) || ($company_api_key == 'getuptaxi'))
		{ 
			if($company_api_key == 'getuptaxi')
			{
				$default_companyid = '';
				$this->site_currency = CURRENCY;
			}
			else
			{
				$default_companyid = $apikey_result[0]['company_cid'];
				$this->site_currency = COMPANY_CURRENCY;
				$this->app_description = $apikey_result[0]['company_app_description'];
			}
		$company_all_currenttimestamp = convert_timezone('now',TIMEZONE);//$this->commonmodel->getcompany_all_currenttimestamp($default_companyid);
		//echo 'as'.$default_companyid;
		switch($method)
		{
		
			case 'get_authentication':
				$host  = $_SERVER['HTTP_HOST'];
				$dateStamp = $_SERVER['REQUEST_TIME'];
				$mobilehost = strtolower($mobiledata['mobilehost']); 
				if(isset($mobilehost)){
					if($mobilehost == $host){
						$value = $host."-".$dateStamp;
						$encode = $this->encrypt_decrypt('encrypt', $value);
						$message = array("message" =>__('success'),"encode" => $encode,"status" => 1);
					} else {
						$message = array("message" => "Host does not match","status" => 2);
						echo json_encode($message);
						break;
					}
				} else {
					$message = array("message" => " Invalid Host Request ","status" => 2);
					echo json_encode($message);
					break; 
				}
				echo json_encode($message);
			break;
			
			//Company URL : http://192.168.1.88:1000/api/index/dGF4aV9YRlJJb1p0NjdxYTU5ZmlIRFl1OGJPQ0J2elRHQVYxZmY=?type=getcoreconfig
			//All Company URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=getcoreconfig
			case 'getcoreconfig':
			//$deviceToken = 'APA91bF7ZGBYW1ORNiW_coPnyd9q1li8fUv-Ssp0tdlq0GnFPkAKp4tWU6FgqIAJO3GR6w8gLfb3hrinfXBwnpzKSEXRqBbDR5hYuK9hRZq3BNscTVh1Ue_zNXjFKxWYHlc5VeAAmnf1';
			//$pushMessage = array('message'=>'Hi Nags');
			//$api->send_pushnotification($deviceToken,'1',$pushMessage,$this->driver_android_api);
			$config_array = $api->select_site_settings($default_companyid);
			if(count($config_array) > 0)
			{
				if($default_companyid == '')
				{
					$config_array[0]['noimage_base'] = URL_BASE.'/public/images/noimages109.png';
					$config_array[0]['api_base'] = URL_BASE;
					$config_array[0]['logo_base'] = URL_BASE.'/public/admin/images/';					
					$config_array[0]['aboutpage_description'] = $this->app_description;
					$config_array[0]['admin_email'] = $this->siteemail;
					$config_array[0]['tell_to_friend_subject'] = __('telltofrien_subject');
					$config_array[0]['skip_credit'] = SKIP_CREDIT_CARD;
					$config_array[0]['metric'] = UNIT_NAME;
				}
				else
				{					
					$config_array[0]['noimage_base'] = URL_BASE.'/public/images/noimages109.png';
					$config_array[0]['api_base'] = URL_BASE;
					$config_array[0]['site_country'] = "";
					$config_array[0]['logo_base'] = URL_BASE.'/public/'.UPLOADS.'/site_logo/';	
					$config_array[0]['aboutpage_description'] = $this->app_description;		
					$config_array[0]['admin_email'] = $this->siteemail;	
					$config_array[0]['tell_to_friend_subject'] = __('telltofrien_subject');
					$config_array[0]['skip_credit'] = SKIP_CREDIT_CARD;
					$config_array[0]['metric'] = UNIT_NAME;
				}

				if(strlen($config_array[0]['passenger_book_notify']) > 0)
				{
					$config_array[0]['passenger_notify'] = 1;	
					$config_array[0]['passenger_book_notify'] = $config_array[0]['passenger_book_notify'];
				}
				else
				{
					$config_array[0]['passenger_notify'] = 0;	
					$config_array[0]['passenger_book_notify'] = '';
				}	

				$config_array[0]['share_content'] = __('telltofriend_content');
				$config_array[0]['referral_code_info'] = __('referral_code_info_details');
				$config_array[0]['cancellation_setting'] = CANCELLATION_FARE;
				$config_array[0]['ios_google_map_key'] = IOS_GOOGLE_MAP_API_KEY;
				$config_array[0]['ios_google_geo_key'] = IOS_GOOGLE_GEO_API_KEY;
				$config_array[0]['android_google_api_key'] = ANDROID_GOOGLE_GEO_API_KEY;
				$config_array[0]['google_business_key'] = GOOGLE_BUSINESS_KEY_USED_STATUS;
				$expiry_date = "";
				$get_company_expiry_date = $api->get_company_expiry_date();
				if(count($get_company_expiry_date) > 0){
					$expiry_date = "";
					if(isset($get_company_expiry_date[0]['expiry_date']) && $get_company_expiry_date[0]['expiry_date']!="")
					{
						$date = date('Y-m-d H:i:s',$get_company_expiry_date[0]['expiry_date']);
						$expiry_date = Commonfunction::getDateTimeFormat($date,1);
					}
				}
				$config_array[0]['domain_expiry_date'] = $expiry_date;
					
				$referral_settings = 0;
				$referral_settings_message = __("referral_settings_message");
				if(REFERRAL_SETTINGS == 1) {
					$referral_settings = 1;
					$referral_settings_message = "";
				}
				$config_array[0]['referral_settings'] = $referral_settings;
				$config_array[0]['referral_settings_message'] = $referral_settings_message;
				
				$driverReferralSettings = 0;
				$driverRefSettingsMsg = __("referral_settings_message");
				if(DRIVER_REFERRAL_SETTINGS == 1) {
					$driverReferralSettings = 1;
					$driverRefSettingsMsg = "";
				}
				$config_array[0]['driver_referral_settings'] = $driverReferralSettings;
				$config_array[0]['driver_referral_settings_message'] = $driverRefSettingsMsg;

				/***Get Company car model details start***/
				$company_model_details = $api->company_model_details($default_companyid);
				if(count($company_model_details)>0) {
					foreach($company_model_details as $key => $val) {
						//$focus_image = $unfocus_image = $focus_image_ios = $unfocus_image_ios = "";
						if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/model_image/android/'.$val["model_id"].'_focus.png')) {
							$focus_image = URL_BASE.'public/'.UPLOADS.'/model_image/android/'.$val['model_id'].'_focus.png';
							$company_model_details[$key]["focus_image"] = $focus_image;
						}
						if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/model_image/android/'.$val["model_id"].'_unfocus.png')) {
							$unfocus_image = URL_BASE.'public/'.UPLOADS.'/model_image/android/'.$val['model_id'].'_unfocus.png';
							$company_model_details[$key]['unfocus_image'] = $unfocus_image;
						}
						if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/model_image/ios/'.$val["model_id"].'_focus.png')) {
							$focus_image_ios = URL_BASE.'public/'.UPLOADS.'/model_image/ios/'.$val['model_id'].'_focus.png';
							$company_model_details[$key]["focus_image_ios"] = $focus_image_ios;
						}
						if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/model_image/ios/'.$val["model_id"].'_unfocus.png')) {
							$unfocus_image_ios = URL_BASE.'public/'.UPLOADS.'/model_image/ios/'.$val['model_id'].'_unfocus.png';
							$company_model_details[$key]["unfocus_image_ios"] = $unfocus_image_ios;
						}
						if($val["model_id"] == 10 && file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/model_image/android/10_focus.svg')) {
							$svg_focus_image = URL_BASE.'public/'.UPLOADS.'/model_image/android/10_focus.svg';
							$company_model_details[$key]["focus_image_svg"] = $svg_focus_image;
						}
					}
					$config_array[0]['model_details'] = $company_model_details;
				}else{ 
					$config_array[0]['model_details']=__('model_details_not_found');
				}
				$gateway_details = $this->commonmodel->gateway_details($default_companyid);

				$gateway_array = array(); $passenger_payment_option = array();
				foreach($gateway_details as $valArr) {
					$gateway_array[] = $valArr;
					if($valArr["pay_mod_id"] != 3) {
						$passenger_payment_option[] = $valArr;
						if(SKIP_CREDIT_CARD == 0)
							break;
					}
				}
				$config_array[0]['app_name'] = preg_replace("/#?[a-z0-9]+;/i","",$config_array[0]['app_name']);
				$config_array[0]['gateway_array'] = $gateway_array;
				$config_array[0]['passenger_payment_option'] = $passenger_payment_option;

				$config_array[0]['cancellation_array'] = array(1 => "Car Trouble", 2 =>"Accepted by Mistake", 3 => "Customer No Show" );

				/***Get Company car model details end***/
				$message = array("message" =>__('success'),"detail" => $config_array,"status" => 1);
			}
			else
			{
				$message = array("message" => __('failed'),"status" => 2);
			}
			echo json_encode($message);
			break;
		




			case 'check_companydomain':
				$result = $api->check_company_domain($mobiledata);
				if(!empty($mobiledata['company_domain']) && count($result) == 0) {
					$message = array("message" => __('sub_domain_notexists'),"status" => 2);
					echo json_encode($message);
					break;
				}
				$company_domain = strtolower(trim($mobiledata['company_domain']));
					//dGF4aV9hbGw
				if(count($result) > 0){
					$mobiledata['domain_id'] = $result[0]['domain_id'];
					$status = $api->update_used_status($mobiledata);
					$baseurl = PROTOCOL."://".$company_domain.".".$mobiledata['company_main_domain']."/mobileapi119/index/";
					$folderPath = ($mobiledata['device_type'] == 2) ? "public/".$company_domain."/iOS/" : "public/".$company_domain."/android/";
					$iOSImage = URL_BASE."public/".$company_domain."/iOS/static_image/";
				} else {
					$baseurl = URL_BASE."mobileapi119/index/";
					$folderPath = ($mobiledata['device_type'] == 2) ? MOBILE_iOS_IMAGES_FILES : MOBILE_ANDROID_IMAGES_FILES;
					$iOSImage = URL_BASE.MOBILE_iOS_IMAGES_FILES."static_image/";
				}
					//$baseurl = PROTOCOL."://".$mobiledata['company_domain'].".taximobility.com/mobileapi117/index/";
					//$baseurl = URL_BASE."mobileapi117/index/";
					$encrypted_txt = $this->encrypt_decrypt('encrypt', $company_domain);
					
					$message = array("message" =>__('success'),"baseurl" => $baseurl,"apikey" => "bnRheGlfZ2V0dXB0YXhp=","status" => 1);
				//clearstatcache();
//functionality to get imgaes, language and color code files for iOS App
				$dateStamp = $_SERVER['REQUEST_TIME'];
				$iOSPathArr = array();
				
				$iOSPassengerLanguageDOC = DOCROOT.$folderPath."language/passenger/";
				$iOSPassengerLanguageVIEW = URL_BASE.$folderPath."language/passenger/";
				$iOSDriverLanguageDOC = DOCROOT.$folderPath."language/driver/";
				$iOSDriverLanguageVIEW = URL_BASE.$folderPath."language/driver/";
				if($mobiledata['device_type'] == 2){
					$iOSColorCode = URL_BASE.$folderPath."colorcode/PassengerAppColor.xml?timeCache=".$dateStamp;
					$iOSDriverColorCode = URL_BASE.$folderPath."colorcode/DriverAppColor.xml?timeCache=".$dateStamp;
				} else {
					$iOSColorCode = URL_BASE.$folderPath."colorcode/";
					$iOSDriverColorCode = "";
				}
				$staticLanguArr = array("english"=>"en","turkish"=>"tr","arabic"=>"ar","german"=>"de","russian"=>"ru","spanish"=>"es","indonesian"=>"id","french"=>"fr");

/*				
				//iOS Passenger Language Files
				$passLangFiles  = opendir($iOSPassengerLanguageDOC);
				$passLangs = array();
				while (false !== ($filename = readdir($passLangFiles))) {
					if($filename != '.' && $filename != '..'){
						$langArr = explode('_',$filename);
						if(isset($langArr[1]))
						$langName =  ($mobiledata['device_type'] == 2) ? str_replace('.strings','',$langArr[1]) : str_replace('.xml','',$langArr[1]);
						$designType = 'LTR';
						$checkRTL = strtolower($langName);
						if($checkRTL == "arabic" || $checkRTL == "urdu") {
							$designType = 'RTL';
						}
						$langType = isset($staticLanguArr[$checkRTL]) ? $staticLanguArr[$checkRTL]:'';
						$fileNam = $filename."?timeCache=".$dateStamp;
						$langFilesArr = array("language"=>$langName,"design_type"=>$designType,"language_code"=>$langType,"url"=>$iOSPassengerLanguageVIEW.$fileNam);
						$passLangs[] = $langFilesArr;
					}
				}
				
				//iOS Driver Language Files
				$driverLangFiles  = opendir($iOSDriverLanguageDOC);
				$driverLangs = array();
				while (false !== ($driverFilename = readdir($driverLangFiles))) {
					if($driverFilename != '.' && $driverFilename != '..'){
						$driverLangArr = explode('_',$driverFilename);
						if(isset($driverLangArr[1]))
						$driverLangName =  ($mobiledata['device_type'] == 2) ? str_replace('.strings','',$driverLangArr[1]) : str_replace('.xml','',$driverLangArr[1]);
						$designType = 'LTR';
						$checkRTL = strtolower($driverLangName);
						if($checkRTL == "arabic" || $checkRTL == "urdu") {
							$designType = 'RTL';
						}
						$langType = isset($staticLanguArr[$checkRTL]) ? $staticLanguArr[$checkRTL]:'';
						$driverFileName = $driverFilename."?timeCache=".$dateStamp;
						$driverLangFilesArr = array("language"=>$driverLangName,"design_type"=>$designType,"language_code"=>$langType,"url"=>$iOSDriverLanguageVIEW.$driverFileName);
						$driverLangs[] = $driverLangFilesArr;
					}
				}
				

*/


				if($mobiledata['device_type'] == 1)
				{
					$passLangs[0] = array(
					    'language' => 'Arabic',
					    'design_type' => 'RTL',
					    'language_code' => 'ar',
					    'url' => $iOSPassengerLanguageVIEW.'strings_Arabic.xml?timeCache='.$dateStamp,
					);

					$passLangs[1] = array(
					    'language' => 'English',
					    'design_type' => 'LTR',
					    'language_code' => 'en',
					    'url' => $iOSPassengerLanguageVIEW.'strings_English.xml?timeCache='.$dateStamp,
					);

					$driverLangs[0] = array(
						'language' => 'Arabic',
						'design_type' => 'RTL',
						'language_code' => 'ar',
						'url' => $iOSDriverLanguageVIEW.'strings_Arabic.xml?timeCache='.$dateStamp,
					);

					$driverLangs[1] = array(
						'language' => 'English',
						'design_type' => 'LTR',
						'language_code' => 'en',
						'url' => $iOSDriverLanguageVIEW.'strings_English.xml?timeCache='.$dateStamp,
					);

				}
				else
				{

					$passLangs[0] = array(
					    'language' => 'Arabic',
					    'design_type' => 'RTL',
					    'language_code' => 'ar',
					    'url' => $iOSPassengerLanguageVIEW.'Localizable_Arabic.strings?timeCache='.$dateStamp,
					);

					$passLangs[1] = array(
					    'language' => 'English',
					    'design_type' => 'LTR',
					    'language_code' => 'en',
					    'url' => $iOSPassengerLanguageVIEW.'Localizable_English.strings?timeCache='.$dateStamp,
					);

					$driverLangs[0] = array(
						'language' => 'Arabic',
						'design_type' => 'RTL',
						'language_code' => 'ar',
						'url' => $iOSDriverLanguageVIEW.'Localizable_Arabic.strings?timeCache='.$dateStamp,
					);

					$driverLangs[1] = array(
						'language' => 'English',

						'design_type' => 'LTR',
						'language_code' => 'en',
						'url' => $iOSDriverLanguageVIEW.'Localizable_English.strings?timeCache='.$dateStamp,
					);
				}



				$host  = (!empty($mobiledata['company_domain'])) ? $mobiledata['company_domain'].".".$mobiledata['company_main_domain'] : $_SERVER['HTTP_HOST'];
				$dateStamp = $_SERVER['REQUEST_TIME'];
				$key = $host."-".$dateStamp;
				$encode = $this->encrypt_decrypt('encrypt', $key);
				$iOSPathArr = array("static_image"=>$iOSImage,"driver_language"=>$driverLangs,"passenger_language"=>$passLangs,"colorcode"=>$iOSColorCode,"driverColorCode"=>$iOSDriverColorCode);
				if($mobiledata['device_type'] == 2) {
					$message['iOSPaths'] = $iOSPathArr;
				} else {
					$message['androidPaths'] = $iOSPathArr;
				}
				$message['encode'] = $encode;
				
				echo json_encode($message);
			break;

			case 'getmodel_fare_details':
				$company_model_details = $api->company_model_details($default_companyid);
				if(count($company_model_details) > 0) {
					foreach($company_model_details as $k => $t) {
						//$focus_image = $unfocus_image = $focus_image_ios = $unfocus_image_ios = "";
						if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/model_image/android/'.$t["model_id"].'_focus.png')) {
							$focus_image = URL_BASE.'public/'.UPLOADS.'/model_image/android/'.$t['model_id'].'_focus.png';
							$company_model_details[$k]['focus_image'] = $focus_image;
						}
						if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/model_image/android/'.$t["model_id"].'_unfocus.png')) {
							$unfocus_image = URL_BASE.'public/'.UPLOADS.'/model_image/android/'.$t['model_id'].'_unfocus.png';
							$company_model_details[$k]['unfocus_image'] = $unfocus_image;
						}
						if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/model_image/ios/'.$t["model_id"].'_focus.png')) {
							$focus_image_ios = URL_BASE.'public/'.UPLOADS.'/model_image/ios/'.$t['model_id'].'_focus.png';
							$company_model_details[$k]['focus_image_ios'] = $focus_image_ios;
						}
						if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/model_image/ios/'.$t["model_id"].'_unfocus.png')) {
							$unfocus_image_ios = URL_BASE.'public/'.UPLOADS.'/model_image/ios/'.$t['model_id'].'_unfocus.png';
							$company_model_details[$k]['unfocus_image_ios'] = $unfocus_image_ios;
						}
						
					}
					$details = array("model_details"=>$company_model_details);
					$message = array("message" =>__('success'),"detail" => $details,"status" => 1);
				} else {
					$message = array("message" => __('model_detail_not_found'),"status" => 2);
				}
				echo json_encode($message);
			break;

			//URL :http://192.168.1.88:1020/api/?type=driver_location_history&driver_id=8&trip_id=268&locations=11.017194,76.964758&status=A&device_token=
			case 'driver_location_history':
					$location_array = $mobiledata;
					//print_r($location_array);exit;
					$msg = array();
					$api = Model::factory(MOBILEAPI_107);
					//$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($default_companyid);
					
					$check_driver_status = $api->check_driver_login_status($location_array['driver_id']);
					if($check_driver_status != "A")
					{
						$msg = array("message" => __('account_blocked'),"status"=>108);	
						echo json_encode($msg);
						exit;
					}
					
					if(!empty($location_array))
					{
						$company_id = $default_companyid;
						$company_det =$api->get_company_id($location_array['driver_id']);
						if(count($company_det)>0){
							$company_id = $company_det[0]['company_id'];
							$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($company_det[0]['company_id']);
						}

						   $history_validator = $this->history_validation($location_array);
						   if($history_validator->check())
						   {
								$driver_status = $location_array['status'];
								$device_token = "";//$location_array['device_token'];
								$fcm_token = "";//$location_array['fcm_token'];
								$driver_id = $location_array['driver_id'];
								$trip_id = $location_array['trip_id'];
								
								$coordinates = explode('|',$location_array['locations']);
								//print_r($coordinates);exit;
								if(count($coordinates)>1){
									$last_1=array_slice($coordinates, -2, 2, true);
									$coordinates = explode(',',$last_1[count($coordinates)-2]);
									//print_r($last_1);
								}else{
									$coordinates = explode(',',$coordinates[0]);
								}
								
								$latitude = empty($coordinates['0'])?'0.0':$coordinates['0'];		
								$longitude = empty($coordinates['1'])?'0.0':$coordinates['1'];
								//echo $latitude."--".$longitude;exit;
								if(!empty($trip_id)){
									//Passenger or Dispatcher cancel alert to driver
									$tripCancelAlert = $api->getTripCancelAlert($driver_id, $trip_id, $company_all_currenttimestamp);
									if(count($tripCancelAlert) > 0){
										$canMsg = ($tripCancelAlert[0]['trip_status'] == 4) ? __('passenger_trip_cancelled') : __('dispatcher_trip_cancelled'); 
										$msg = array("message" => $canMsg,"status"=>10);
										$update_driver_request_array  = array("notification_status" => '5'); 
										$result = $api->update_table(PASSENGERS_LOG,$update_driver_request_array,'passengers_log_id',$tripCancelAlert[0]['trip_id']);
										echo json_encode($msg); break;
									}
									
									/** driver update from dispatcher **/
									$trip_update_status = $api->get_trip_update_status($trip_id);
									if(count($trip_update_status) > 0)
									{
										$drop_location=isset($trip_update_status[0]['drop_location']) ? urldecode($trip_update_status[0]['drop_location']) : "";
										$drop_latitude=isset($trip_update_status[0]['drop_latitude'])?$trip_update_status[0]['drop_latitude']:"";
										$drop_longitude=isset($trip_update_status[0]['drop_longitude'])?$trip_update_status[0]['drop_longitude']:"";
										$pickup_location=isset($trip_update_status[0]['current_location']) ? urldecode($trip_update_status[0]['current_location']) : "";
										$pickup_latitude=isset($trip_update_status[0]['pickup_latitude'])?$trip_update_status[0]['pickup_latitude']:"";
										$pickup_longitude=isset($trip_update_status[0]['pickup_longitude'])?$trip_update_status[0]['pickup_longitude']:"";
										$driver_notes=isset($trip_update_status[0]['notes_driver'])?$trip_update_status[0]['notes_driver']:"";
										$notification_status=isset($trip_update_status[0]['notification_status'])?$trip_update_status[0]['notification_status']:"";
										$tripUpdateMSg = ($notification_status == 6) ? __('disptcher_updated') : __('passenger_update_drop_location');
										$msg = array("message" => $tripUpdateMSg,"drop_location"=>$drop_location,"drop_latitude"=>$drop_latitude,"drop_longitude"=>$drop_longitude,"pickup_location"=>$pickup_location,"pickup_latitude"=>$pickup_latitude,"pickup_longitude"=>$pickup_longitude,"driver_notes"=>$driver_notes,"status"=>11);
										$update_driver_array  = array("notification_status" => '7');
										$update_current_result = $api->update_table(PASSENGERS_LOG,$update_driver_array,'passengers_log_id',$trip_id);
										echo json_encode($msg); break;                                 
									}
								}
								/** driver update from dispatcher **/
								if($driver_status == 'F')
								{
									/***** Update Driver Current Location *********************/								
									if(count($coordinates)>0)
									{		
										if(($latitude != 0) && ($longitude != 0))
										{													
											if(($location_array['trip_id'] == 0) || ($location_array['trip_id'] == ""))
											{
												$update_driver_array  = array(		
																	"latitude" => $latitude,
																	"longitude" => $longitude,
																	"status" => 'F',
																	"update_date"=> $company_all_currenttimestamp);										
											}
											else
											{
												$update_driver_array  = array(
																	"latitude" => $latitude,
																	"longitude" => $longitude,
																	"status" => strtoupper($driver_status),
																	"update_date"=> $company_all_currenttimestamp);
											}
											
											$update_current_result = $api->update_table(DRIVER,$update_driver_array,'driver_id',$driver_id);
											//$driver_location_history_free = $api->save_driver_location_history_free($location_array,$default_companyid);
											$check_new_request = $api->check_new_request($driver_id,$company_all_currenttimestamp);
											//added on mar 17 2017
											$check_driver_req = $api->check_assigned_driver_id($location_array['trip_id']);
													
											if($check_driver_req != $location_array['driver_id'])
											{
														$msg = array("message" => __('Trip assigned to other drivers'),"status" => -109);
											}
											if($check_new_request > 0)
											{	
												$passenger_name = "";
												$get_passenger_log_details = $api->get_passenger_log_detail($check_new_request);
												if(count($get_passenger_log_details)>0)
												{
													foreach($get_passenger_log_details as $values)
													{														
														$p_device_type = $values->passenger_device_type;
														$p_device_token  = $values->passenger_device_token;	
														/** get minimum speed **/
														$taxi_id=$values->taxi_id;
														$dr_company_id=$values->company_id;
														$get_min_speed=$api->get_minimum_speed($taxi_id,$default_companyid);
														$belowspeed_mins= isset($get_min_speed[0]['taxi_min_speed']) ? $get_min_speed[0]['taxi_min_speed'] : 0;  
														/** get minimum speed **/
														$pickupplace  = urldecode($values->current_location);	
														$dropplace = urldecode($values->drop_location);	
														$passenger_id = $values->passengers_id;
														$passenger_phone = $values->passenger_phone;
														$time_to_reach_passen = $values->time_to_reach_passen;
														$sub_logid = $values->sub_logid;
														$pickup_latitude = $values->pickup_latitude;
														$pickup_longitude = $values->pickup_longitude;
														$drop_latitude = $values->drop_latitude;
														$drop_longitude = $values->drop_longitude;
														$passenger_salutation = $values->passenger_salutation;
														$p_name = $values->passenger_name;
														$pickup_time = $values->pickup_time;
														$bookby = $values->bookby;
														$notes_driver = $values->notes_driver;
														$driver_notes = $values->driver_notes;	
													}	
													$passenger_name = $passenger_salutation.' '.ucfirst($p_name);
													$notification_time = $this->notification_time;
													if($notification_time != 0 ){ $timeoutseconds = $notification_time;}else{$timeoutseconds = 15;}
													//if timeout seconds greater than 60 seconds we have to convert to mins and secs
													if($timeoutseconds > 60) {
														$notification_minutes = floor($timeoutseconds / 60);
														$notification_seconds = $timeoutseconds % 60;
														$notification_minutes = ($notification_minutes < 10) ? '0'.$notification_minutes : $notification_minutes;
													} else {
														$notification_minutes = "00";
														$notification_seconds = $timeoutseconds;
													}
													$notification_seconds = ($notification_seconds < 10) ? '0'.$notification_seconds : $notification_seconds;
													$total_timeout = $notification_minutes." : ".$notification_seconds;
													$trip_details = array("message" => __('api_request_confirmed_passenger'),"status" => "1","passengers_log_id" => $check_new_request,"booking_details" => array ( "pickupplace" => $pickupplace, "dropplace" => $dropplace, "pickup_time" => $pickup_time,"driver_id" => $driver_id,"passenger_id" => $passenger_id,"roundtrip" => "","passenger_phone" => $passenger_phone,"cityname" => "", "distance_away" => "","sub_logid" => $sub_logid,"drop_latitude" => $drop_latitude,"drop_longitude" => $drop_longitude, "taxi_id" => $taxi_id, "company_id" => $dr_company_id,"pickup_latitude" => $pickup_latitude, "pickup_longitude" => $pickup_longitude,"bookedby" => $bookby, "passenger_name" => $passenger_name,"profile_image" => "","drop" => $dropplace),"estimated_time" => $time_to_reach_passen ,"notification_time" => $timeoutseconds,"notification_minutes" => $notification_minutes,"notification_seconds" => $notification_seconds,"notes" =>$notes_driver,"driver_notes" =>$driver_notes,"belowspeed_mins"=>$belowspeed_mins);	
													$msg = array("message" => __('driver_history_updated'),"trip_details"=>$trip_details,"status" => 5);	
													
													$check_another_request = $api->check_new_request_bydriver($driver_id,$company_all_currenttimestamp,$check_new_request);
													if(count($check_another_request) > 0){
														foreach($check_another_request as $cns){
															$api->change_driver_reqflow($cns['trip_id'],$cns['available_drivers'],$cns['rejected_timeout_drivers']);
														}
													}
													$update_trip_array  = array("status"=>'1');
													$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$check_new_request);	
													
													$update_driver_array  = array("status"=>'B');
													$result = $api->update_table(DRIVER,$update_driver_array,'driver_id',$driver_id);
												}	
												else
												{
													$msg = array("message" => __('driver_history_updated'),"status" => 1);
												}
											}
											else
											{
												$msg = array("message" => __('driver_history_updated'),"status" => 1);
											}
										}
										else
										{
											$msg = array("message" => __('invalid_request'),"status"=>-4);
										}
										/********* Update driver device token every specified seconds *************
										if($device_token != "")
										{
										$update_array  = array("device_token" => $device_token);							
										$login_status_update = $api->update_driver_phone($update_array,$driver_id,$default_companyid);
										}
										/***************************************************************************/									
									}
								}
								else if($driver_status == 'A')
								{
									/***** Update Driver Current Location ******************************************/
									$update_driver_array  = array(
																	"latitude" => $latitude,
																	"longitude" => $longitude,
																	"status" => strtoupper($driver_status),
																	"update_date"=> $company_all_currenttimestamp);
									$update_current_result = $api->update_table(DRIVER,$update_driver_array,'driver_id',$driver_id);
									/*******************************************************************************/
									$result = $api->save_driver_location_history($location_array,$default_companyid);
									$distance = isset($result[1]) ? $result[1] :'0';
									$trip_fare = 0;
									/** to get the trip fare based on the distance and minutes travelled for ongoing trip **/
									if(!empty($location_array['trip_id'])){
										$tripDets = $api->getTripCompanyModelDets($location_array['trip_id']);
										if($tripDets['booking_from'] == 2){
											$taxi_fare_details = $api->get_model_fare_details($tripDets['company_id'],$tripDets['taxi_modelid'],$tripDets['search_city'],$tripDets['brand_type']);
											$base_fare = '0';
											$min_km_range = '0';
											$min_fare = '0';
											$below_above_km_range = '0';
											$below_km = '0';
											$above_km = '0';
											$minutes_cost= '0';
											if(count($taxi_fare_details) > 0)
											{
												$base_fare = $taxi_fare_details[0]['base_fare'];
												$min_km_range = $taxi_fare_details[0]['min_km'];
												$min_fare = $taxi_fare_details[0]['min_fare'];
												$below_above_km_range = $taxi_fare_details[0]['below_above_km'];
												$below_km = $taxi_fare_details[0]['below_km'];
												$above_km = $taxi_fare_details[0]['above_km'];
												$minutes_fare = $taxi_fare_details[0]['minutes_fare'];
											}
											/********Minutes fare calculation *******/ 
										   $interval  = abs(strtotime($company_all_currenttimestamp) - strtotime($tripDets['actual_pickup_time']));
										   $minutes   = round($interval / 60);
										   /********Minutes fare calculation *******/
											$baseFare = $base_fare;
											$total_fare = $base_fare;
											if(FARE_CALCULATION_TYPE==1 || FARE_CALCULATION_TYPE==3)
											{
												if($distance < $min_km_range)
												{
													//min fare has set as base fare if trip distance 
													$baseFare = $min_fare;
													$total_fare = $min_fare;
												}
												else if($distance <= $below_above_km_range)
												{
													$fare = $distance * $below_km;
													$total_fare  = 	$fare + $base_fare ;
												}
												else if($distance > $below_above_km_range)
												{
													$fare = $distance * $above_km;
													$total_fare  = 	$fare + $base_fare ;
												}
											}
											
											if(FARE_CALCULATION_TYPE==2 || FARE_CALCULATION_TYPE==3)
											{
												/********** Minutes fare calculation ************/
												if($minutes_fare > 0)
												{
													$minutes_cost = $minutes * $minutes_fare;
													$total_fare  = $total_fare + $minutes_cost;
												}
												/************************************************/
											}
											$trip_fare = $total_fare;
										}
									}
									/*****/
									if(isset($result[0]) && $result[0] == 1)
									{
										$msg = array("message" => __('driver_history_updated'),"status" => 1,"distance"=>$distance,"trip_fare"=>$trip_fare);	
									}
									else if($result == -1)
									{
										$msg = array("message" => __('driver_history_already'),"status" => -1);	
									}
									else if($result == 2)
									{
										$msg = array("message" => __('invalid_user'),"status" => 2);	
									}
									else if($result == 3)
									{
										$msg = array("message" => __('no_access'),"status" => 3);	
									}
									else if($result == 5)
									{
										$msg = array("message" => __('driver_history_updated'),"status" => 1,"distance"=>$distance,"trip_fare"=>$trip_fare);	
									}
									else
									{
										$msg = array("message" => __('invalid_user'),"status"=>-1);	
									}
								}
								elseif($driver_status == 'B')
								{
									/***** Update Driver Current Location *********************************************************/
									if(($latitude != 0) &&($longitude != 0))
									{
											$update_driver_array  = array(
																	"latitude" => $latitude,
																	"longitude" => $longitude,
																	"status" => strtoupper($driver_status),
																	"update_date"=> $company_all_currenttimestamp);
											$update_current_result = $api->update_table(DRIVER,$update_driver_array,'driver_id',$driver_id);
									}
									/**********************************************************************************************/
									$get_passenger_log_details = $api->get_passenger_log_detail($trip_id);
									if(count($get_passenger_log_details)>0)
									{
										$driver_reply = $get_passenger_log_details[0]->driver_reply;
										$travel_status = $get_passenger_log_details[0]->travel_status;
										$location_array = array("drop_location" => $get_passenger_log_details[0]->drop_location,"drop_latitude" => $get_passenger_log_details[0]->drop_latitude,"drop_longitude" => $get_passenger_log_details[0]->drop_longitude);
										$msg = array("message" => __('driver_history_updated'),"drop_location_details" => $location_array,"status" => 1);
										if(($driver_reply == 'A') && ($travel_status == 4))
										{
											$msg = array("message" => __("trip_cancelled_passenger"),"detail"=>"","status"=>7);
										}
									}
									else
									{
										$msg = array("message" => __('driver_history_updated'),"status" => 1);	
									}
									
									$check_new_request_trip = $api->check_new_request_bydriver($driver_id,$company_all_currenttimestamp,$trip_id);
									$check_driver_status_free=$api->check_driver_status_free($driver_id);
									if($check_driver_status_free=="B" && count($check_new_request_trip) > 0){
										foreach($check_new_request_trip as $cns){
											//$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($default_companyid);
											//$get_request_dets=$api->check_new_request_tripid("","",$cns['trip_id'],$driver_id,$company_all_currenttimestamp,"");
											$api->change_driver_reqflow($cns['trip_id'],$cns['available_drivers'],$cns['rejected_timeout_drivers']);
										}
									} 
								}
								else
								{
									$msg = array("message" => __('validation_error'),"detail"=>"","status"=>-3);
								}
							}
							else
							{
								$errors = $history_validator->errors('errors');	
								$msg = array("message" => __('validation_error'),"detail"=>$errors,"status"=>-3);	
								//echo json_encode($msg);
							}
					}
					else
					{
						$msg = array("message" => __('invalid_request'),"status"=>-4);
					}
					
					echo json_encode($msg);
					unset(Database::$instances['default']);
					break;	
					
			//Passenger Signup with Referral code concept
			/*Url : http://192.168.1.116:1013/mobileapi114/index/dGF4aV9hbGw=/?type=passenger_signup_single
			 * Params : {"first_name":"Test","last_name":"user","email":"test@gmail.com","phone":"28828282","password":"qwerty","confirm_password":"qwerty","deviceid":"","devicetoken":"sdsd333","devicetype":"1","referral_code":""}
			 * Method: POST
			 * */
			case 'passenger_signup_single':
			   $p_first_name = (isset($mobiledata['first_name'])) ? urldecode($mobiledata['first_name']) : '';
			   $p_last_name = (isset($mobiledata['last_name'])) ? urldecode($mobiledata['last_name']) : '';
			   $p_email = (isset($mobiledata['email'])) ? urldecode($mobiledata['email']) : '';
			   $p_phone = (isset($mobiledata['phone'])) ? urldecode($mobiledata['phone']) : '';
			   $country_code = (isset($mobiledata['country_code'])) ? $mobiledata['country_code'] : '';
			   $p_password = (isset($mobiledata['password'])) ? urldecode($mobiledata['password']) : '';
			   $p_confirm_password = (isset($mobiledata['confirm_password'])) ? urldecode($mobiledata['confirm_password']) : '';
			   $devicetoken = (isset($mobiledata['devicetoken'])) ? urldecode($mobiledata['devicetoken']) : '';
			   $device_id = (isset($mobiledata['deviceid'])) ? urldecode($mobiledata['deviceid']) : '';
			   $devicetype = (isset($mobiledata['devicetype'])) ? urldecode($mobiledata['devicetype']) : '';	
			   $accessToken = (isset($mobiledata['accesstoken'])) ? urldecode($mobiledata['accesstoken']) : '';
			   $uid = (isset($mobiledata['userid'])) ? urldecode($mobiledata['userid']) : '';				   
			   $referral_code = (isset($mobiledata['referral_code'])) ? urldecode($mobiledata['referral_code']) : '';
			   $p_acc_validator = $this->pasenger_signup_validation($mobiledata);
			   $config_array = $api->select_site_settings($default_companyid);
			   if($p_acc_validator->check())
			   {
				   $email_exist = $api->check_email_passengers($p_email,$default_companyid);
				   $phone_exist = $api->check_phone_passengers($p_phone,$default_companyid,$country_code);
				   $referralcode_exist = $api->check_referral_code_exist($referral_code,$default_companyid);
				   
				   if($email_exist > 0)
					{
						$message = array("message" => __('email_exists'),"status"=> 2);
						echo json_encode($message);
					}
					else if($phone_exist > 0)
					{
						$message = array("message" => __('phone_exists'),"status"=> 3);
						echo json_encode($message);
					}
					else if(!empty($referral_code) && $referralcode_exist == 0)
					{
						$message = array("message" => __('referral_code_not_exists'),"status"=> 5);
						echo json_encode($message);
					}
					else
					{
						$image_name = '';
						if($uid != '') {
							//to get profile image from facebook and store it passenger
							$thumb_image = file_get_contents("http://graph.facebook.com/".$uid."/picture?width=".PASS_THUMBIMG_WIDTH1."&height=".PASS_THUMBIMG_HEIGHT1."");
							$thumb_image_name =  'thumb_'.$uid.'.jpg';
							$thumb_image_path = DOCROOT.PASS_IMG_IMGPATH.$thumb_image_name; 
							@chmod(DOCROOT.PASS_IMG_IMGPATH,0777);
							@chmod($thumb_image_path,0777);
							file_put_contents($thumb_image_path, $thumb_image);

							$edit_image = file_get_contents("http://graph.facebook.com/".$uid."/picture?width=".PASS_THUMBIMG_WIDTH1."&height=".PASS_THUMBIMG_HEIGHT1."");
							$edit_image_name =  'edit_'.$uid.'.jpg';
							$edit_image_path = DOCROOT.PASS_IMG_IMGPATH.$edit_image_name; 
							@chmod(DOCROOT.PASS_IMG_IMGPATH,0777);
							@chmod($edit_image_path,0777);
							file_put_contents($edit_image_path, $edit_image);

							/** Big Image **/
							$big_image = file_get_contents("http://graph.facebook.com/".$uid."/picture?width=".PASS_IMG_WIDTH."&height=".PASS_IMG_HEIGHT."");
							$image_name =  $uid.'.jpg';
							$big_image_path = DOCROOT.PASS_IMG_IMGPATH.$image_name; 
							@chmod(DOCROOT.PASS_IMG_IMGPATH,0777);
							@chmod($big_image_path,0777);
							file_put_contents($big_image_path, $big_image);


							$base_image = imagecreatefromjpeg($edit_image_path);
							$width = 100;
							$height = 19;
							$top_image = imagecreatefrompng(URL_BASE."public/images/edit.png");
							$merged_image = DOCROOT.PASS_IMG_IMGPATH.'edit_'.$uid.'.jpg';
							imagesavealpha($top_image, true);
							imagealphablending($top_image, true);
							imagecopy($base_image, $top_image, 0, 83, 0, 0, $width, $height);
							imagejpeg($base_image, $merged_image);
						}
						/******/						
						$otp = text::random($type = 'numeric', $length = 4);
						//$otp = '';
						$acc_details_result=$api->passenger_signup_with_referral($p_first_name, $p_last_name, $p_email, $p_phone, $country_code, $p_password, $p_confirm_password,$otp,$referral_code,$devicetoken,$device_id,$devicetype,$default_companyid,$accessToken,$uid,$image_name);							
						if($acc_details_result == 1) 
						{ 
							$mail="";
							/*
							$replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>'',REPLACE_OTP=>$otp,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COMPANYDOMAIN=>$this->domain_name,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
							//$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'otp.html',$replace_variables);
							if($this->lang!='en'){
								if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/otp-'.$this->lang.'.html')){
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/otp-'.$this->lang.'.html',$replace_variables);
								}else{
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'otp.html',$replace_variables);
								}
							}
							else
							{
								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'otp.html',$replace_variables);
							}
							*/

							$replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$p_first_name,REPLACE_MOBILE=>$p_phone,REPLACE_PASSWORD=>$p_password,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>COMPANY_COPYRIGHT);

							if($this->lang!='en'){
								if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/passenger-register.html')){
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/passenger-register.html',$replace_variables);
								}else{
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'passenger-register.html',$replace_variables);
								}
							}
							else
							{
								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'passenger-register.html',$replace_variables);
							}

							$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'passenger-register.html',$replace_variables);

							$to = $p_email;
							$from = $this->siteemail;
							$subject = __('passenger_registration_confirmation')." - ".$this->app_name;
							//$subject = __('otp_subject')." - ".$this->app_name;
							$redirect = "no";
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

							//free sms url with the arguments
							if(SMS == 1)
							{
								/*$message_details = $this->commonmodel->sms_message_by_title('passenger_signup_success');
								$to = $p_phone;
								$message = $message_details[0]['sms_description'];
								//$message = str_replace("##OTP##",$otp,$message);
								$message = str_replace("##PHONE##",$p_phone,$message);
								$message = str_replace("##PASSWORD##",$p_password,$message);
								$message = str_replace("##SITE_NAME##",SITE_NAME,$message);*/
								//$api->send_sms($to,$message);
								/*$message_details = $this->commonmodel->sms_message_by_title('otp');
								if(count($message_details) > 0) {
									$to = $country_code.$p_phone;
									$message = $message_details[0]['sms_description'];
									$message = str_replace("##OTP##",$otp,$message);
									$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
									$this->commonmodel->send_sms($to,$message);
								}*/
							}
							//$detail = array("email"=>$p_email,"phone"=>$p_phone,"skip_credit"=>SKIP_CREDIT_CARD);
							/*$message = array("message" =>__('account_save_otp').' Your OTP is '.$otp,"OTP" => $otp,"detail"=>$detail,"status"=> 1);*/
							
							$update_cred_sts  = array("skip_credit_card" => '1');
							$update_current_result = $api->update_table(PASSENGERS,$update_cred_sts,'email',$p_email);
							$passenger_details = $api->passenger_detailsbyemail($p_email,$default_companyid);
							$total_array = array();
							if(count($passenger_details) > 0)
							{
								if((!empty($passenger_details[0]['profile_image'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image'])){ 
								$profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image']; 
								}
								else{ 
								$profile_image = URL_BASE."public/images/no_image109.png";
								} 								
								$passenger_id = $passenger_details[0]['id'];
								$total_array['id'] = $passenger_details[0]['id'];
								$total_array['salutation'] = $passenger_details[0]['salutation'];
								$total_array['name'] = $passenger_details[0]['name'];
								$total_array['lastname'] = $passenger_details[0]['lastname'];
								$total_array['email'] = $passenger_details[0]['email'];
								$total_array['profile_image'] = $profile_image;
								$total_array['phone'] = $passenger_details[0]['phone'];
								$total_array['country_code'] = $passenger_details[0]['country_code'];
								$total_array['address'] = $passenger_details[0]['address'];
								$total_array['split_fare'] = $passenger_details[0]['split_fare'];
								$referral_code = $passenger_details[0]['referral_code'];
								$total_array['referral_code'] = $referral_code;
								$total_array['referral_code_amount'] = $passenger_details[0]['referral_code_amount'];
								$ref_message = TELL_TO_FRIEND_MESSAGE.''.$referral_code;
								$ref_discount = REFERRAL_DISCOUNT;
								$telltofriend_message = TELL_TO_FRIEND_MESSAGE;//str_replace("#REFDIS#",$ref_discount,$ref_message); 
								//Newly Added-13.11.2014
								$total_array['site_currency'] = $config_array[0]['site_currency'];
								$total_array['facebook_share'] = $config_array[0]['facebook_share'];
								$total_array['twitter_share'] = $config_array[0]['twitter_share'];
								$total_array['aboutpage_description'] = $this->app_description;
								$total_array['tell_to_friend_subject'] = __('telltofrien_subject');
								$total_array['skip_credit'] = SKIP_CREDIT_CARD;
								$total_array['metric'] = UNIT_NAME;
								$total_array['credit_card_status'] = 0;
								/***Get Company car model details start***/
									$company_model_details = $api->company_model_details($default_companyid);
									if(count($company_model_details)>0){
										$total_array['model_details']=$company_model_details;
									}else{
										$total_array['model_details']="model details not found";
									}
								/***Get Company car model details end***/										
								$total_array['telltofriend_message'] = $telltofriend_message;	
							}						
						//if(isset($passenger_details[0]['login_from']) && $passenger_details[0]['login_from'] != '3') {
							$p_password = isset($passenger_details[0]['org_password'])?$passenger_details[0]['org_password']:'';
							if(SMS == 1)
							{
								$message_details = $this->commonmodel->sms_message_by_title('account_create_sms');
								if(count($message_details) > 0) {
									$to = isset($total_array['phone'])? $total_array['country_code'].$total_array['phone']:'';
									//$p_password = isset($passenger_details[0]['org_password'])?$passenger_details[0]['org_password']:'';
									//$p_password ="";
									$message = $message_details[0]['sms_description'];
									$message = str_replace("##USERNAME##",$p_email,$message);
									$message = str_replace("##PASSWORD##",$p_password,$message);
									$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
									$this->commonmodel->send_sms($to,$message);
								}
								//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
								
							}
							
							$mobile_no = isset( $passenger_details[0]['phone'])? $passenger_details[0]['country_code'].$passenger_details[0]['phone']:'';
							$username = isset( $passenger_details[0]['name'])? $passenger_details[0]['name']:'';
							$replace_variables=array(REPLACE_LOGO=>URL_BASE.SITE_LOGO_IMGPATH.$this->domain_name.'_email_logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$username,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_MOBILE=>$mobile_no,REPLACE_PASSWORD=>$p_password,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COMPANYDOMAIN=>$this->domain_name,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
								
							//$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver-register.html',$replace_variables);
							/* Added for language email template */
							if($this->lang!='en')
							{				
								if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/driver-register-'.$this->lang.'.html'))
								{
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/driver-register-'.$this->lang.'.html',$replace_variables);
								}else
								{
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver-register.html',$replace_variables);
								}
							}
							else
							{
								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver-register.html',$replace_variables);
							}
							$to = $p_email;
							$from = $this->siteemail;
								$subject = __('pass_account_details')." - ".$this->app_name;	
							$redirect = "no";	
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
						//}
						/*** Update Pssenger password as empty ************/
						$update_passenger_array  = array("org_password" => ""); // 
						//$result = $api->update_table(PASSENGERS,$update_passenger_array,'id',$passenger_id);
						/***************************************************/							
						$message = array("message" => __('signup_success'),"detail"=>$total_array,"status"=>1);
							
							
							//$message = array("message" =>__('signup_success'),"detail"=>$detail,"status"=> 1);
						}
						else
						{
							$message = array("message" => __('try_again'),"status"=> 4);
						}
					 echo json_encode($message);
					}
			   }
			   else
			   {
					$errors = $p_acc_validator->errors('errors');
					$result = array("message"=>$errors,"status"=>-1);
					echo json_encode($result);
					exit;
				}											
			break;
			/** URL : http://192.168.1.116:1013/mobileapi114/index/dGF4aV9hbGw=/?type=otp_verify
			 * Params :- {"otp":"169105","email":"test@gmail.com"}
			 *  **/	
			case 'otp_verify':
				$otp = isset($mobiledata['otp']) ? $mobiledata['otp'] : '';
				$email = isset($mobiledata['email']) ? $mobiledata['email'] : '';
				if(!empty($otp)) {
					$otp_verification = $api->otp_verification($otp,$email);
					if($otp_verification > 0) {
						$update_passenger_array  = array("user_status" => "A"); // activate user if the otp is valid
						$result = $api->update_table(PASSENGERS,$update_passenger_array,'email',$email);
						$detail = array("email" => $email,"skip_credit" => SKIP_CREDIT_CARD);
						$msg = array("message" =>__('signup_success'),"detail" => $detail,"status" => 1);
					} else {
						$msg = array("message" => __('invalid_otp'),"status"=>-2);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status"=>-1);
				}
				echo json_encode($msg);
				exit;
			break;
			/** Url : http://192.168.1.116:1013/mobileapi114/index/dGF4aV9hbGw=/?type=passenger_wallet
			 * Params : {"passenger_id":"1638"}
			 *  **/	
			case 'passenger_wallet':
				$passenger_id = isset($mobiledata['passenger_id']) ? $mobiledata['passenger_id'] : '';
				if(!empty($passenger_id)) {
					$passenger_wallet = $api->get_passenger_wallet_amount($passenger_id);
					$siteInfo = $api->siteinfo_details();
					$amount_details = array("wallet_amount1"=>$siteInfo[0]['wallet_amount1'],"wallet_amount2"=>$siteInfo[0]['wallet_amount2'],"wallet_amount3"=>$siteInfo[0]['wallet_amount3'],"wallet_amount_range"=>$siteInfo[0]['wallet_amount_range']);
					if(count($passenger_wallet) > 0) {
						$msg = array("wallet_amount" => $passenger_wallet[0]['wallet_amount'],"amount_details"=>$amount_details,"status"=>1);
					} else {
						$msg = array("message" => __('invalid_user'),"status"=>-2);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status"=>-1);
				}
				echo json_encode($msg);
				exit;
			break;
			/** URL : http://192.168.1.116:1013/mobileapi114/index/dGF4aV9hbGw=/?type=wallet_addmoney
			 *  Params : {"passenger_id":"1633","creditcard_no":"5555555555554444","creditcard_cvv":"222","expmonth":"11","expyear":"2021","money":"150","cardholder_name":"Test","payment_type":"2","savecard":"0"}
			 *  **/
			case 'wallet_addmoney':
				$passenger_id = isset($mobiledata['passenger_id']) ? $mobiledata['passenger_id'] : '';
				$money = isset($mobiledata['money']) ? $mobiledata['money'] : '';
				$promo_code = isset($mobiledata['promo_code']) ? urldecode($mobiledata['promo_code']) : '';
				$p_validator = $this->wallet_addmoney_validation($mobiledata);
				$promocodeAmount = 0;
			   if($p_validator->check())
			   {
				   if($promo_code != "")
					{
						$promodiscount = $api->getpromodetails($promo_code,$passenger_id);
						$promocodeAmount = ($promodiscount/100) * $money;
						$mobiledata['money'] = $money + $promocodeAmount;
					}
					
					$passenger_wallet = $this->wallet_addmoney($mobiledata,$default_companyid,$promo_code,$promocodeAmount);
					$cancelFare = $api->get_passenger_cancel_farebyid($passenger_id);
					$wallAmount = 0;
					if($passenger_wallet != 0) {
						$passwallArr = explode("#",$passenger_wallet);
						$wallAmount = isset($passwallArr[1]) ? $passwallArr[1] : 0;
						$passenger_wallet = $passwallArr[0];
					}
					
					$credit_card_sts = ($wallAmount >= $cancelFare) ? 0 : SKIP_CREDIT_CARD;
					
					if($passenger_wallet == 1) {
						$msg = array("message" => __('amount_added_wallet'), "credit_card_status" => $credit_card_sts,"status"=>1);
					} 
					else if($passenger_wallet == 0)
					{
						$gateway_response = isset($_SESSION['paymentresponse']['L_LONGMESSAGE0'])?$_SESSION['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
						$msg = array("message" => $gateway_response, "gateway_response" =>$gateway_response,"status"=>0);		
					} else {
						$msg = array("message" => __('no_payment_gateway'),"status"=>-1);
					}
				} else {
					//$msg = array("message" => __('invalid_request'),"status"=>-1);
					$errors = $p_validator->errors('errors');	
					$msg = array("message" => __('validation_error'),"detail"=>$errors,"status"=>-1);
				}
				echo json_encode($msg);
				exit;
			break;
			
			case 'invite_with_referral':
				$passenger_id = isset($mobiledata['passenger_id']) ? $mobiledata['passenger_id'] : '';
				if(!empty($passenger_id)) {
					$passengerReferral = $api->get_passenger_wallet_amount($passenger_id);
					if(count($passengerReferral) > 0) {
						if(REFERRAL_SETTINGS == 1) {
							$referral_settings = 1;
							$referral_settings_message = "";
						} else {
							$referral_settings = 0;
							$referral_settings_message = __("referral_settings_message");
						}
						$detail = array("referral_code" => $passengerReferral[0]['referral_code'],"referral_amount" => $passengerReferral[0]['referral_code_amount'],"referral_settings" => $referral_settings,"referral_settings_message" => $referral_settings_message);
						$msg = array("message" => __('referral_amount'),"detail" => $detail,"status"=>1);
					} else {
						$msg = array("message" => __('invalid_user'),"status"=>-2);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status"=>-1);
				}
				echo json_encode($msg);
			break;
			
			//api to check valid promocode
			case 'check_valid_promocode':
				$passenger_id = isset($mobiledata['passenger_id']) ? $mobiledata['passenger_id'] : '';
				$promo_code = isset($mobiledata['promo_code']) ? urldecode($mobiledata['promo_code']) : '';
				if(!empty($passenger_id) && !empty($promo_code)) {
					$check_promo = $api->checkwalletpromocode($promo_code,$passenger_id,$default_companyid);
					if($check_promo == 0)
					{
						$msg = array("message" => __('invalid_promocode_wallet'),"status" => 3);
					}
					else if($check_promo == 3)
					{
						$msg = array("message" => __('promo_code_startdate'),"status" => 3);
					}
					else if($check_promo == 4)
					{
						$msg = array("message" => __('promo_code_expired'),"status" => 3);
					}
					else if($check_promo == 2)
					{
						$msg = array("message" => __('promo_code_limit_exceed'),"status" => 3);
					}
					else
					{
						$msg = array("message" => __('promocode_valid'),"status" => 1);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status"=>-1);
				}
				echo json_encode($msg);
				exit;
			break;
													
			//URL : http://192.168.1.88:1009/api/index/?type=passenger_account_details&email=prabhu.r@ndot.in&phone=8888888885&password=123456&deviceid=&devicetoken=&devicetype=
			case 'passenger_account_details':
				  //$datas = file_get_contents('php://input');
				  //$data =  (array)json_decode($datas,true);
				   //print_r($mobiledata);
				   //exit;
				   $p_email = $mobiledata['email'];
				   $p_phone = $mobiledata['phone'];
				   $p_password = $mobiledata['password'];
				   $devicetoken = $mobiledata['devicetoken'];
  				   $fcm_token = isset($mobiledata['fcm_token'])?$mobiledata['fcm_token']:'';
				   $device_id = "";//$mobiledata['deviceid'];
				   $devicetype = $mobiledata['devicetype'];					   
				   
				   $p_acc_validator = $this->account_validation($mobiledata);
				   if($p_acc_validator->check())
				   {
					   $email_exist = $api->check_email_passengers($p_email,$default_companyid);
					   $phone_exist = $api->check_phone_passengers($p_phone,$default_companyid);
					   if($email_exist > 0)
						{
							$message = array("message" => __('email_exists'),"status"=> 2);
							echo json_encode($message);
						}
						else if($phone_exist > 0)
						{
							$message = array("message" => __('phone_exists'),"status"=> 3);
							echo json_encode($message);
						}
						else
						{							
							$otp = text::random($type = 'alnum', $length = 5);
							$referral_code = text::random($type = 'alnum', $length = 6);
							$acc_details_result=$api->add_p_account_details($mobiledata,$otp,$referral_code,$devicetoken,$device_id,$devicetype,$default_companyid,$fcm_token);							
							if($acc_details_result == 1) 
							{ 
							/*	$mail="";						
								$replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>'',REPLACE_OTP=>$otp,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE);
								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'otp.html',$replace_variables);

							$to = $p_email;
							$from = $this->siteemail;
							$subject = __('otp_subject')." - ".$this->app_name;	
							$redirect = "no";	
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
							}*/

							//free sms url with the arguments
							if(SMS == 1)
							{
							$message_details = $this->commonmodel->sms_message_by_title('otp');
							if(count($message_details) > 0) {
								$to = $p_phone;
								$message = $message_details[0]['sms_description'];
								$message = str_replace("##OTP##",$otp,$message);
								$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
								$this->commonmodel->send_sms($to,$message);
							}
							//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
							} 
							$detail = array("email"=>$p_email,"skip_credit"=>SKIP_CREDIT_CARD);
							$message = array("message" =>__('account_saved'),"detail"=>$detail,"status"=> 1);
							}
							else
							{
								$message = array("message" => __('try_again'),"status"=> 4);
							}	
						 echo json_encode($message);        	
						}							    					    
				   }
				   else
				   {
						$errors = $p_acc_validator->errors('errors');	
						$result = array("message"=>$errors,"status"=>-1);
						echo json_encode($result);
						exit;					
					}											
					break;			
				   //URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=resend_otp&email=senthilcse2008@gmail.com&user_type=P
				case 'resend_otp':
					$otp_array = $mobiledata;
					$email = $mobiledata['email'];
					$user_type = $otp_array['user_type'];
					if(isset($email))
					{
						$otp = text::random($type = 'numeric', $length = 4);
						$otp_result = $api->update_otp($otp_array,$otp,$default_companyid);
						if($otp_result == 1) 
						{
							$mail="";
							$replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>'',REPLACE_OTP=>$otp,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COMPANYDOMAIN=>$this->domain_name,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);

							/* Added for language email template */
							if($this->lang!='en')
							{
								if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/otp-'.$this->lang.'.html'))
								{
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/otp-'.$this->lang.'.html',$replace_variables);
								}
								else
								{
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'otp.html',$replace_variables);
								}
							}
							else
							{
								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'otp.html',$replace_variables);
							}

							/* Added for language email template */
							$to = $email;
							//$to = "durairaj.s@ndot.in";
							$from = $this->siteemail;
							if($user_type == 'D')
							{
								$subject = __('otp_driver_subject')." - ".$this->app_name;
							}
							else
							{
								$subject = __('otp_subject')." - ".$this->app_name;	
							}
							$redirect = "no";
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

							//free sms url with the arguments
							if(SMS == 1)
							{
								if($otp_array['user_type']=='P')
								{
									$this->phone = $this->commonmodel->getUserDetailByEmail('phone',PASSENGERS,$email);
								}
								else
								{
									$this->phone = $this->commonmodel->getUserDetailByEmail('phone',PEOPLE,$email);
								}
								$message_details = $this->commonmodel->sms_message_by_title('otp');
								if(count($message_details) > 0) {
									$to = $this->phone;
									//$to = "+919489443922";
									$message = $message_details[0]['sms_description'];
									$message = str_replace("##OTP##",$otp,$message);
									$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
									$this->commonmodel->send_sms($to,$message);
								}
								//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
							}
							$detail = array("email"=>$email);
							$message = array("message" => __('resend_otp'),"detail"=>$detail,"status"=> 1);
						}
						else
						{
							$message = array("message" => __('try_again'),"status"=> 4);
						}
						echo json_encode($message);   
						exit;
					}
					else
					{
						$result = array("message"=>__('invalid_email'),"status"=>-1);
						echo json_encode($result);
						exit;
					}
					break;
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=passenger_personal_details&email=sakthivel.s.m@ndot.in&otp=mwS0Q&salutation=Mr&referral_code=jNs7q&firstname=senthil&lastname=kumar&profile_image=
			case 'passenger_personal_details':
					$p_personal_array= $mobiledata;										
					$referred_passenger_id="";
					//print_r($p_personal_array);
					//exit;
					if(isset($p_personal_array['email']))
					{
						$validator = $this->passenger_profile_validation($p_personal_array);						
						if($validator->check())
						{
							$referral_code = $p_personal_array['referral_code'];
							/*$validate_otp = $api->check_otp($p_personal_array['otp'],$p_personal_array['email'],'P',$default_companyid);
							//check_otp_expire
							if($validate_otp == 1)
							{
								$check_otp_expiry = $api->check_otp_expire($p_personal_array['otp'],$p_personal_array['email'],'P',$default_companyid);
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
								//print_r($check_otp_expiry);
								//$current_time =	date('Y-m-d H:i:s');
								$updated_date = $check_otp_expiry[0]['updated_date'];
								$otp_expiry = $check_otp_expiry[0]['otp_expiry'];
								if($current_time <= $otp_expiry)
								{		*/				
									if($referral_code != "")
									{					
										$validate_referral_code = $api->check_referral_code($referral_code);						
										//echo count($validate_referral_code);
										if(is_array($validate_referral_code))
										{
											$referred_passenger_id = $validate_referral_code[0]['id'];
										}		
										else
										{
											//$referred_passenger_id = '';
											$message = array("message" => __('invalid_referral_code'),"status"=> 3);
											echo json_encode($message);
											exit;
										}		
									}																			
								if($p_personal_array['profile_image'] != NULL)
								{
								/* Profile Update */
								$imgdata = base64_decode($p_personal_array['profile_image']);
								$f = finfo_open();
								$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
								$mime_type = explode('/',$mime_type);
								$mime_type = $mime_type[1];
								//echo $imgdata;exit;
								$img = imagecreatefromstring($imgdata); 

								if($img != false)
								{                   
									$image_name = uniqid().'.'.$mime_type;
									$thumb_image_name = 'thumb_'.$image_name;
									$image_url = DOCROOT.PASS_IMG_IMGPATH.'/'.$image_name;                    								
									//header('Content-Type: image/jpeg');					
									$image_path = DOCROOT.PASS_IMG_IMGPATH.$image_name;  
									imagejpeg($img,$image_url);
									imagedestroy($img);
									chmod($image_path,0777);
									$d_image = Image::factory($image_path);
									$path11=DOCROOT.PASS_IMG_IMGPATH;
									//Commonfunction::imageresize($d_image,PASS_IMG_WIDTH, PASS_IMG_HEIGHT,$path11,$image_name,90);								
									Commonfunction::imageoriginalsize($d_image,$path11,$image_name,90);
									$path12=$thumb_image_name;
									//Commonfunction::imageresize($d_image,PASS_THUMBIMG_WIDTH, PASS_THUMBIMG_HEIGHT,$path11,$thumb_image_name,90);
									Commonfunction::imageoriginalsize($d_image,$path11,$thumb_image_name,90);
									$update_array = array(								
									"salutation"=>$p_personal_array['salutation'],
									"name" => $p_personal_array['firstname'],
									"lastname" => $p_personal_array['lastname'],
									"email" => $p_personal_array['email'],
									"profile_image" => $image_name,
									"user_status"=>'A',
									"activation_status"=>1);
									$message = $api->save_passenger_personaldata($update_array,$referred_passenger_id,$default_companyid);
								//chmod($image_path, 0777);                    
								}
								else
								{
									$message = array("message" => __('image_not_upload'),"status"=>4);								
								}
								
							}
							else
							{
									$update_array = array(
									"salutation"=>$p_personal_array['salutation'],
									"name" => $p_personal_array['firstname'],
									"lastname" => $p_personal_array['lastname'],
									"email" => $p_personal_array['email'],
									"user_status"=>'A',
									"activation_status"=>1);
									$message = $api->save_passenger_personaldata($update_array,$referred_passenger_id,$default_companyid);
							}
								/*****************************************/												
								if($message == 0)
								{
									$passenger_details = $api->passenger_detailsbyemail($p_personal_array['email'],$default_companyid);
											$id="";
											if(count($passenger_details) >0)
											{
												$id = $passenger_details[0]['id'];
												$email =  $passenger_details[0]['email'];
											}
											$detail = array("passenger_id"=>$id,"skip_credit"=>SKIP_CREDIT_CARD);
											$message = array("message" => __('personal_updated'),"detail"=>$detail,"status"=>1);	
								}	
								if($message == -1)
								{
									$message = array("message" => __('try_again'),"status"=>-1);	
								}	
						/*	}
							else
							{
								$message = array("message" => __('otp_expire'),"status"=>-7);
							}
							}
							else
							{
								$message = array("message" => __('invalid_otp'),"status"=>-5);
							}*/
						}
						else
						{							
							$validation_error = $validator->errors('errors');	
							$message = array("message" => $validation_error,"status"=>-3);		
						}
					}
					else
					{
						$message = array("message" => __('invalid_email'),"status"=>-4);	
					}
					
					echo json_encode($message);
					break;			
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=passenger_card_details&email=sakthivel.s.m@ndot.in&creditcard_no=4111111111111111&expdatemonth=08&expdateyear=2014&creditcard_cvv=567&savecard=1&default=
			case 'passenger_card_details':
			$p_card_array= $mobiledata;			
			$savecard = $p_card_array['savecard'];
			$email = $p_card_array['email'];
			$config_array = $api->select_site_settings($default_companyid);
			if($savecard == 1)
			{
				$card_validation = $this->passenger_card_validation($p_card_array);			
				if($card_validation->check())
				{
					$creditcard_no = $p_card_array['creditcard_no'];
					$card_holder_name = (isset($p_card_array['card_holder_name'])) ? urldecode($p_card_array['card_holder_name']) : '';
					$creditcard_cvv = (isset($p_card_array['creditcard_cvv'])) ? urldecode($p_card_array['creditcard_cvv']) : '';
					$expdatemonth = (isset($p_card_array['expdatemonth'])) ? urldecode($p_card_array['expdatemonth']) : '';
					$expdateyear = (isset($p_card_array['expdateyear'])) ? urldecode($p_card_array['expdateyear']) : '';
					//isVAlidCreditCard($ccnum,"",true);
					$authorize_status =$api->isVAlidCreditCard($creditcard_no,"",true);
					//print_r($authorize_status);
					//exit;
					if($authorize_status == 0)
					{
						$message = array("message" => __('invalid_card'),"status"=> 2);
						echo json_encode($message);
						exit;
					}
					
					$passenger_details = $api->passenger_detailsbyemail($email,$default_companyid);
					$passenger_id = (count($passenger_details) > 0) ? $passenger_details[0]['id'] : 0;
					//Credit Card Pre authorization section goes here
					//preauthorization with amount "0"(Zero)
					$preAuthorizeAmount = PRE_AUTHORIZATION_REG_AMOUNT;
					list($returncode,$paymentResult,$fcardtype) = $api->creditcardPreAuthorization($passenger_id,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$preAuthorizeAmount);
					
					if($returncode==0)
					{
						//preauthorization with amount "1"
						$preAuthorizeAmount = PRE_AUTHORIZATION_RETRY_REG_AMOUNT;					
						list($returncode,$paymentResult,$fcardtype)= $api->creditcardPreAuthorization($passenger_id,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$preAuthorizeAmount);
						
					}
					
					if($returncode != 0)
					{
						$result = $api->save_passenger_carddata($p_card_array,$default_companyid,$paymentResult,$preAuthorizeAmount,$fcardtype);
						if($result) {
							$void_transaction=$api->voidTransactionAfterPreAuthorize($result,$paymentResult);
						}
					}
					else
					{
						$message=array("message"=>__('insufficient_fund'),"status"=>3);
						echo json_encode($message);
						exit;
					}
							
					if($result > 0)
					{
						$total_array = array();
						if(count($passenger_details) > 0)
						{
							if((!empty($passenger_details[0]['profile_image'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image'])){ 
								$profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image']; 
							}
							else{ 
								$profile_image = URL_BASE."public/images/no_image109.png";
							} 										
							$passenger_id= $passenger_details[0]['id'];
							$total_array['id'] = $passenger_details[0]['id'];
							$total_array['salutation'] = $passenger_details[0]['salutation'];
							$total_array['name'] = $passenger_details[0]['name'];
							$total_array['lastname'] = $passenger_details[0]['lastname'];
							$total_array['email'] = $passenger_details[0]['email'];
							$total_array['profile_image'] = $profile_image;
							$total_array['country_code'] = $passenger_details[0]['country_code'];
							$total_array['phone'] = $passenger_details[0]['phone'];
							$total_array['address'] = $passenger_details[0]['address'];
							$total_array['split_fare'] = $passenger_details[0]['split_fare'];
							$referral_code = $passenger_details[0]['referral_code'];
							$total_array['referral_code'] = $referral_code;
							$total_array['referral_code_amount'] = $passenger_details[0]['referral_code_amount'];
							$ref_message = TELL_TO_FRIEND_MESSAGE.''.$referral_code;
							$ref_discount = REFERRAL_DISCOUNT;
							$telltofriend_message = TELL_TO_FRIEND_MESSAGE;
							//Newly Added-13.11.2014
							$total_array['site_currency'] = $config_array[0]['site_currency'];
							$total_array['aboutpage_description'] = $this->app_description;
							$total_array['tell_to_friend_subject'] = __('telltofrien_subject');
							$total_array['skip_credit'] = SKIP_CREDIT_CARD;
							$total_array['metric'] = UNIT_NAME;
							//variable to know whether the passenger have credit card details
							$total_array['credit_card_status'] = 1;
							//str_replace("#REFDIS#",$ref_discount,$ref_message); 
							/***Get Company car model details start***/
							$company_model_details = $api->company_model_details($default_companyid);
							if(count($company_model_details)>0){
								$total_array['model_details']=$company_model_details;
							}else{
								$total_array['model_details']="model details not found";
							}
							/***Get Company car model details end***/										
							$total_array['telltofriend_message'] = $telltofriend_message;	
						}				
						//if(isset($passenger_details[0]['login_from']) && $passenger_details[0]['login_from'] != '3') {//this condition for not sending email or sms for facebook sms users
						$p_password = isset($passenger_details[0]['org_password'])?$passenger_details[0]['org_password']:'';
						//free sms url with the arguments
						if(SMS == 1)
						{
							$message_details = $this->commonmodel->sms_message_by_title('account_create_sms');
							if(count($message_details) > 0) {
								$to = isset($total_array['phone'])? $total_array['country_code'].$total_array['phone']:'';
								//$p_password = isset($passenger_details[0]['org_password'])?$passenger_details[0]['org_password']:'';
								//$p_password ="";
								$message = $message_details[0]['sms_description'];
								$message = str_replace("##USERNAME##",$email,$message);
								$message = str_replace("##PASSWORD##",$p_password,$message);
								$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
								$this->commonmodel->send_sms($to,$message);
							}
							//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
							
						}
							
						$mobile_no = isset( $passenger_details[0]['phone'])? $passenger_details[0]['country_code'].$passenger_details[0]['phone']:'';
						$username = isset( $passenger_details[0]['name'])? $passenger_details[0]['name']:'';
						$replace_variables=array(REPLACE_LOGO=>URL_BASE.SITE_LOGO_IMGPATH.$this->domain_name.'_email_logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$username,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_MOBILE=>$mobile_no,REPLACE_PASSWORD=>$p_password,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COMPANYDOMAIN=>$this->domain_name,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
										
						//$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver-register.html',$replace_variables);
						/* Added for language email template */
						if($this->lang!='en')
						{				
							if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/driver-register-'.$this->lang.'.html'))
							{
								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/driver-register-'.$this->lang.'.html',$replace_variables);
							}else
							{
								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver-register.html',$replace_variables);
							}
						}
						else
						{
							$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver-register.html',$replace_variables);
						}
						/* Added for language email template */
						$to = $email;
						$from = $this->siteemail;
							$subject = __('pass_account_details')." - ".$this->app_name;	
						$redirect = "no";	
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
						//}
						/*** Update Pssenger password as empty ************/
						//$update_passenger_array  = array("org_password" => ""); // 
						$update_passenger_array  = array("login_status" => "S"); // 
						$result = $api->update_table(PASSENGERS,$update_passenger_array,'id',$passenger_id);
						/***************************************************/
						$message = array("message" => __('signup_success'),"detail"=>$total_array,"status"=>1);		
					}
					elseif($result == -1)
					{
						$message = array("message" => __('you_have_detail'),"status"=>3);
					}
					else
					{
						$message = array("message" => __('try_again'),"status"=>1);	
					}
				
				}
				else
				{							
					$validation_error = $card_validation->errors('errors');	
					$message = array("message" => __('validation_error'),"detail"=>$validation_error,"status"=>-3);		
				}
			}
			else
			{
					$update_cred_sts  = array("skip_credit_card" => '1');
					$update_current_result = $api->update_table(PASSENGERS,$update_cred_sts,'email',$email);
					$passenger_details = $api->passenger_detailsbyemail($email,$default_companyid);
									$total_array = array();
									if(count($passenger_details) > 0)
									{
										if((!empty($passenger_details[0]['profile_image'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image'])){ 
										$profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image']; 
										}
										else{ 
										$profile_image = URL_BASE."public/images/no_image109.png";
										} 								
										$passenger_id = $passenger_details[0]['id'];
										$total_array['id'] = $passenger_details[0]['id'];
										$total_array['salutation'] = $passenger_details[0]['salutation'];
										$total_array['name'] = $passenger_details[0]['name'];
										$total_array['lastname'] = $passenger_details[0]['lastname'];
										$total_array['email'] = $passenger_details[0]['email'];
										$total_array['profile_image'] = $profile_image;
										$total_array['phone'] = $passenger_details[0]['phone'];
										$total_array['country_code'] = $passenger_details[0]['country_code'];
										$total_array['address'] = $passenger_details[0]['address'];
										$total_array['split_fare'] = $passenger_details[0]['split_fare'];
										$referral_code = $passenger_details[0]['referral_code'];
										$total_array['referral_code'] = $referral_code;
										$total_array['referral_code_amount'] = $passenger_details[0]['referral_code_amount'];
										$ref_message = TELL_TO_FRIEND_MESSAGE.''.$referral_code;
										$ref_discount = REFERRAL_DISCOUNT;
										$telltofriend_message = TELL_TO_FRIEND_MESSAGE;//str_replace("#REFDIS#",$ref_discount,$ref_message); 
										//Newly Added-13.11.2014
										$total_array['site_currency'] = $config_array[0]['site_currency'];
										$total_array['facebook_share'] = $config_array[0]['facebook_share'];
										$total_array['twitter_share'] = $config_array[0]['twitter_share'];
										$total_array['aboutpage_description'] = $this->app_description;
										$total_array['tell_to_friend_subject'] = __('telltofrien_subject');
										$total_array['skip_credit'] = SKIP_CREDIT_CARD;
										$total_array['metric'] = UNIT_NAME;
										$total_array['credit_card_status'] = 0;
										/***Get Company car model details start***/
											$company_model_details = $api->company_model_details($default_companyid);
											if(count($company_model_details)>0){
												$total_array['model_details']=$company_model_details;
											}else{
												$total_array['model_details']="model details not found";
											}
										/***Get Company car model details end***/										
										$total_array['telltofriend_message'] = $telltofriend_message;	
									}						
						//if(isset($passenger_details[0]['login_from']) && $passenger_details[0]['login_from'] != '3') {
							$p_password = isset($passenger_details[0]['org_password'])?$passenger_details[0]['org_password']:'';
										if(SMS == 1)
										{
											$message_details = $this->commonmodel->sms_message_by_title('account_create_sms');
											if(count($message_details) > 0) {
												$to = isset($total_array['phone'])? $total_array['country_code'].$total_array['phone']:'';
												//$p_password = isset($passenger_details[0]['org_password'])?$passenger_details[0]['org_password']:'';
												//$p_password ="";
												$message = $message_details[0]['sms_description'];
												$message = str_replace("##USERNAME##",$email,$message);
												$message = str_replace("##PASSWORD##",$p_password,$message);
												$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
												$this->commonmodel->send_sms($to,$message);
											}
											//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
											
										}
							
							$mobile_no = isset( $passenger_details[0]['phone'])? $passenger_details[0]['country_code'].$passenger_details[0]['phone']:'';
							$username = isset( $passenger_details[0]['name'])? $passenger_details[0]['name']:'';
							$replace_variables=array(REPLACE_LOGO=>URL_BASE.SITE_LOGO_IMGPATH.$this->domain_name.'_email_logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$username,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_MOBILE=>$mobile_no,REPLACE_PASSWORD=>$p_password,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COMPANYDOMAIN=>$this->domain_name,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
								
							//$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver-register.html',$replace_variables);
							/* Added for language email template */
							if($this->lang!='en')
							{				
								if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/driver-register-'.$this->lang.'.html'))
								{
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/driver-register-'.$this->lang.'.html',$replace_variables);
								}else
								{
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver-register.html',$replace_variables);
								}
							}
							else
							{
								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver-register.html',$replace_variables);
							}
							$to = $email;
							$from = $this->siteemail;
								$subject = __('pass_account_details')." - ".$this->app_name;	
							$redirect = "no";	
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
						//}
					/*** Update Pssenger password as empty ************/
					$update_passenger_array  = array("org_password" => ""); // 
					//$result = $api->update_table(PASSENGERS,$update_passenger_array,'id',$passenger_id);
					/***************************************************/							
				$message = array("message" => __('signup_success'),"detail"=>$total_array,"status"=>1);	
			}
			echo json_encode($message);
			break;
			/** URL : http://192.168.1.116:1013/mobileapi114/index/dGF4aV9hbGw=/?type=passenger_referral_code
			 * Params : {"email":"test@gmail.com","referral_code":"EMJIOL"}
			 *  **/
			case 'passenger_referral_code':
				$referral_code = (isset($mobiledata['referral_code'])) ? urldecode($mobiledata['referral_code']) : '';
				$email = (isset($mobiledata['email'])) ? urldecode($mobiledata['email']) : '';
				if(!empty($referral_code)) {
					$referralcode_exist = $api->check_referral_code_exist($referral_code,$default_companyid);
					if($referralcode_exist > 0) {
						$passenger_details = $api->passenger_detailsbyemail($email,$default_companyid);
						if(count($passenger_details) > 0) {
							$referral_used = $api->check_referral_code_used($passenger_details[0]['id']);
							if($referral_used == 0) {
								$save_referral = $api->save_referral_code($passenger_details[0]['id'],$referral_code,$default_companyid,$passenger_details[0]['device_id'],$passenger_details[0]['device_token']);
								if($save_referral == 1) {
									$message = array("message" => __('referral_code_save_successful'),"status"=> 1);
								} else {
									$message = array("message" => __('try_again'),"status"=>-1);	
								}
							} else {
								$message = array("message" => __('referral_code_already_used'),"status"=> 4);
							}
						} else {
							$message = array("message" => __('invalid_user'),"status"=>-1);
						}
					} else {
						$message = array("message" => __('referral_code_not_exists'),"status"=> -1);
					}
				} else {
					$message = array("message" => __('referral_code_not_empty'),"status"=> -1);
				}
				echo json_encode($message);
			break;
			
			//URL : http://192.168.1.104:1003/api/index/dGF4aV9hbGw=/?type=passenger_fb_connect&accesstoken=sdfdssdsfdsfasdfassfasfsdfsdf&userid=100000222346359&fname=senthil&lname=kumar&fbemail=janani.senthilcse@gmail.com&devicetoken=e10adc3949ba59abbe56e057f20f883e&deviceid=SDfsdf454&devicetype=1
			case 'passenger_fb_connect':
					$array = $mobiledata;
					$accessToken = $array['accesstoken'];
					$uid = $array['userid'];
					$fname = $array['fname'];
					$lname = $array['lname'];
					$email = $array['fbemail'];
					$devicetoken = $array['devicetoken'];
					$device_id = $array['deviceid'];
					$devicetype = $array['devicetype'];
					$fcm_token = isset($array['fcm_token'])?$array['fcm_token']:'';
										
								/*$profile_data_url = "https://graph.facebook.com/me?access_token=".$accessToken;
								$Profile_data = json_decode($this->curl_function($profile_data_url));
								if(isset($Profile_data->error))
								{
									$message = array("message" => 'Problem on Facebook Connect.Please Try Again',"status"=>-1);
								}
								else{*/
								/** Thumb Image ****/
//echo "http://graph.facebook.com/".$uid."/picture?width=".PASS_THUMBIMG_WIDTH1."&height=".PASS_THUMBIMG_HEIGHT1."";
								$thumb_image = @file_get_contents("https://graph.facebook.com/".$uid."/picture?width=".PASS_THUMBIMG_WIDTH1."&height=".PASS_THUMBIMG_HEIGHT1."");
								$thumb_image_name =  'thumb_'.$uid.'.jpg';
								$thumb_image_path = DOCROOT.PASS_IMG_IMGPATH.$thumb_image_name; 
								/*if(file_exists($thumb_image_path))
									chmod($thumb_image_path,0777);
								file_put_contents($thumb_image_path, $thumb_image); */
								$thumb_image_file = fopen($thumb_image_path, "w") or die("Unable to open file!");
								fwrite($thumb_image_file, $thumb_image);
								fclose($thumb_image_file);

								$edit_image = @file_get_contents("https://graph.facebook.com/".$uid."/picture?width=".PASS_THUMBIMG_WIDTH1."&height=".PASS_THUMBIMG_HEIGHT1."");
								$edit_image_name =  'edit_'.$uid.'.jpg';
								$edit_image_path = DOCROOT.PASS_IMG_IMGPATH.$edit_image_name;
								/*if(file_exists($edit_image_path))
									chmod($edit_image_path,0777);
								file_put_contents($edit_image_path, $edit_image); */
								$image_file = fopen($edit_image_path, "w") or die("Unable to open file!");
                               fwrite($image_file, $edit_image);
                               fclose($image_file);

								/** Big Image **/
								$big_image = @file_get_contents("https://graph.facebook.com/".$uid."/picture?width=".PASS_IMG_WIDTH."&height=".PASS_IMG_HEIGHT."");
								$image_name =  $uid.'.jpg';
								$big_image_path = DOCROOT.PASS_IMG_IMGPATH.$image_name;
								/*if(file_exists($big_image_path))
									chmod($big_image_path,0777);
								file_put_contents($big_image_path, $big_image);*/
								$big_image_file = fopen($big_image_path, "w") or die("Unable to open file!");
                               fwrite($big_image_file, $big_image);
                               fclose($big_image_file);


								$base_image = imagecreatefromjpeg($edit_image_path);
								$width = 100;
								$height = 19;
								$top_image = imagecreatefrompng(URL_BASE."public/images/edit.png");
								$merged_image = DOCROOT.PASS_IMG_IMGPATH.'edit_'.$uid.'.jpg';
								imagesavealpha($top_image, true);
								imagealphablending($top_image, true);
								imagecopy($base_image, $top_image, 0, 83, 0, 0, $width, $height);
								imagejpeg($base_image, $merged_image);

								/*************************/	
								//print_r($Profile_data); exit;
								$otp = text::random($type = 'alnum', $length = 5);
								$referral_code = strtoupper(text::random($type = 'alnum', $length = 6));
								$status = $api->register_facebook_user($accessToken,$uid,$otp,$referral_code,$fname,$lname,$email,$image_name,$devicetoken,$device_id,$devicetype,$default_companyid);
								//echo $status;exit;
								$passenger_array = $passenger_details = $api->passenger_detailsbyemail($email,$default_companyid);	
								
								if((!empty($passenger_details[0]['profile_image'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image'])){ 
									$profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image']; 
								 }
								else{ 
									$profile_image = URL_BASE."public/images/no_image109.png";
								 } 

								$passenger_details[0]['profile_image'] = $profile_image;
								$config_array = $api->select_site_settings($default_companyid);

									//print_r($config_array);exit;
								//echo 'as'.$status;exit;								
									$total_array = array();
									$result = $passenger_details;
									$fbemail = '';
									$skip_credit_card = 2;
									if(count($result) > 0 && count($passenger_array) > 0)
									{
										$total_array['id'] = $result[0]['id'];
										$total_array['name'] = $result[0]['name'];
										$total_array['email'] = $result[0]['email'];
										$fbemail = $total_array['email'];
										$total_array['profile_image'] = $profile_image;
										$total_array['country_code'] = $result[0]['country_code'];
										$total_array['phone'] = $result[0]['phone'];
										$total_array['address'] = $result[0]['address'];
										$total_array['user_status'] = $result[0]['user_status'];
										$total_array['login_from'] = $result[0]['login_from'];
										$total_array['referral_code'] = $result[0]['referral_code'];
										$total_array['referral_code_amount'] = $result[0]['referral_code_amount'];
										$total_array['split_fare'] = $result[0]['split_fare'];
										//to check whether the passenger gave
										$skip_credit_card = $result[0]['skip_credit_card'];
										$telltofriend_message = TELL_TO_FRIEND_MESSAGE;//str_replace("#REFDIS#",$ref_discount,$ref_message); 
										$total_array['telltofriend_message'] = $telltofriend_message;
										
										//Newly Added-13.11.2014
										$total_array['site_currency'] = $config_array[0]['site_currency'];
										$total_array['aboutpage_description'] = $this->app_description;
										$total_array['tell_to_friend_subject'] = __('telltofrien_subject');
										$total_array['skip_credit'] = SKIP_CREDIT_CARD;
										$total_array['metric'] = UNIT_NAME;
										//variable to know whether the passenger have credit card
										$check_card_data = $api->check_passenger_card_data($result[0]['id']);
										$credit_card_sts = ($check_card_data == 0) ? 0 : SKIP_CREDIT_CARD;
										$total_array['credit_card_status'] = $credit_card_sts;
									}
									//print_r($total_array);exit;
									//echo $status;exit('sdfdsf');
							    if($status==1)
								{
									//echo $passenger_details[0]['id'];	
									/***Get Company car model details start***/
											$company_model_details = $api->company_model_details($default_companyid);
											if(count($company_model_details)>0){
												$total_array['model_details']=$company_model_details;
											}else{
												$total_array['model_details']="model details not found";
											}
										/***Get Company car model details end***/
									$message = array("message" => __('succesful_login_flash'),"detail"=>$total_array,"status"=> 1); //url::redirect(PATH);																
								}
								else if($status==2)
								{	
									$detail = array("email"=>$fbemail);														
									$message = array("message"=>__('account_saved_withoutmobile'),"detail"=>$detail,"status"=>2);					 
									//$message = array("message"=>__('account_saved_withoutmobile'),"status"=>2);					 
								}
								/*else if($status==3)
								{									
									$message = array("message"=>__('p_personal_data_not_filled'),"detail"=>$total_array,"status"=>3);													 
								} */
								else if($status==4 || $status==3)
								{
									/*if(SKIP_CREDIT_CARD !=1 || $skip_credit_card != 1)
									{
										$message = array("message"=>__('p_card_data_not_filled'),"detail"=>$total_array,"status"=>4);	
									}
									else
									{ */
										/***Get Company car model details start***/
											$company_model_details = $api->company_model_details($default_companyid);
											if(count($company_model_details)>0){
												$total_array['model_details']=$company_model_details;
											}else{
												$total_array['model_details']="model details not found";
											}
										/***Get Company car model details end***/
										$message = array("message" => __('succesful_login_flash'),"detail"=>$total_array,"status"=> 1);
									//} 
								}
								else if($status==-2)
								{
									//$message = array("message"=>__('email_exists'),"status"=>-2);		
									$detail = array("email"=>$email);							 
									$message = array("message"=>__('account_not_activated'),"detail"=>$detail,"status"=>-2);													 
								}
								else if($status==10)
								{
									$message = array("message" => __('facebook_email_empty'),"status"=>10);
								}
								else
								{
									$message = array("message" => __('facebook_error'),"status"=>-1);
								}

					echo json_encode($message);
					break;
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=passenger_mobile_otp&fbemail=test@gmail.com&mobile=9789648588&fname=senthil&lname=kumar&otp=HH6tC
			case 'passenger_mobile_otp':

					$array = $mobiledata;
					$email = $array['fbemail'];
					$mobile = $array['mobile'];
					$country_code = isset($array['country_code']) ? $array['country_code'] : '';

					$phone_exist = $api->check_phone_bypassengers($mobile,$email,$default_companyid,$country_code);
					
					if($phone_exist != 0)
					{
						$message = array("message" => __('phone_exists'),"status"=>4);
					}
					else 
					{
						if($email != null && $mobile != null)
						{
							$status = $api->update_passenger_mobile($email,$mobile,$default_companyid,$country_code);

							if($status == 1)
							{
								$passenger_details = $api->passenger_detailsbyemail($email,$default_companyid);
								$otp = $passenger_details[0]['otp'];
								$id = $passenger_details[0]['id'];
								$mail="";						
								$replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>'',REPLACE_OTP=>$otp,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE);
								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'otp.html',$replace_variables);

								$to = $email;
								$from = $this->siteemail;
								$subject = __('otp_subject')." - ".$this->app_name;	
								$redirect = "no";	
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
								if(SMS == 1)
								{
									$message_details = $this->commonmodel->sms_message_by_title('otp');
									if(count($message_details) > 0) {
										$to = $passenger_details[0]['phone'];
										$message = $message_details[0]['sms_description'];
										$message = str_replace("##OTP##",$otp,$message);
										$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
										$this->commonmodel->send_sms($to,$message);
									}
								}
								
								$total_array = array();
								if(count($passenger_details) > 0)
								{
									$total_array['id'] = $passenger_details[0]['id'];
									$total_array['name'] = $passenger_details[0]['name'];
									$total_array['email'] = $passenger_details[0]['email'];
									$total_array['phone'] = $passenger_details[0]['phone'];
									$total_array['address'] = $passenger_details[0]['address'];
								}
								$detail = array("passenger_id"=>$id);
								
								
								
								//add
								
							$update_cred_sts  = array("skip_credit_card" => '1');
							$update_current_result = $api->update_table(PASSENGERS,$update_cred_sts,'email',$email);
							$passenger_details = $api->passenger_detailsbyemail($email,$default_companyid);
							$config_array = $api->select_site_settings($default_companyid);
							$total_array = array();
							if(count($passenger_details) > 0)
							{
								if((!empty($passenger_details[0]['profile_image'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image'])){ 
								$profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image']; 
								}
								else{ 
								$profile_image = URL_BASE."public/images/no_image109.png";
								} 								
								$passenger_id = $passenger_details[0]['id'];
								$total_array['id'] = $passenger_details[0]['id'];
								$total_array['salutation'] = $passenger_details[0]['salutation'];
								$total_array['name'] = $passenger_details[0]['name'];
								$total_array['lastname'] = $passenger_details[0]['lastname'];
								$total_array['email'] = $passenger_details[0]['email'];
								$total_array['profile_image'] = $profile_image;
								$total_array['phone'] = $passenger_details[0]['phone'];
								$total_array['country_code'] = $passenger_details[0]['country_code'];
								$total_array['address'] = $passenger_details[0]['address'];
								$total_array['split_fare'] = $passenger_details[0]['split_fare'];
								$referral_code = $passenger_details[0]['referral_code'];
								$total_array['referral_code'] = $referral_code;
								$total_array['referral_code_amount'] = $passenger_details[0]['referral_code_amount'];
								$ref_message = TELL_TO_FRIEND_MESSAGE.''.$referral_code;
								$ref_discount = REFERRAL_DISCOUNT;
								$telltofriend_message = TELL_TO_FRIEND_MESSAGE;//str_replace("#REFDIS#",$ref_discount,$ref_message); 
								//Newly Added-13.11.2014
								$total_array['site_currency'] = $config_array[0]['site_currency'];
								$total_array['facebook_share'] = $config_array[0]['facebook_share'];
								$total_array['twitter_share'] = $config_array[0]['twitter_share'];
								$total_array['aboutpage_description'] = $this->app_description;
								$total_array['tell_to_friend_subject'] = __('telltofrien_subject');
								$total_array['skip_credit'] = SKIP_CREDIT_CARD;
								$total_array['metric'] = UNIT_NAME;
								$total_array['credit_card_status'] = 0;
								/***Get Company car model details start***/
									$company_model_details = $api->company_model_details($default_companyid);
									if(count($company_model_details)>0){
										$total_array['model_details']=$company_model_details;
									}else{
										$total_array['model_details']="model details not found";
									}
								/***Get Company car model details end***/										
								$total_array['telltofriend_message'] = $telltofriend_message;	
							}						
						//if(isset($passenger_details[0]['login_from']) && $passenger_details[0]['login_from'] != '3') {
							$p_password = isset($passenger_details[0]['org_password'])?$passenger_details[0]['org_password']:'';
							if(SMS == 1)
							{
								$message_details = $this->commonmodel->sms_message_by_title('account_create_sms');
								if(count($message_details) > 0) {
									$to = isset($total_array['phone'])? $total_array['country_code'].$total_array['phone']:'';
									//$p_password = isset($passenger_details[0]['org_password'])?$passenger_details[0]['org_password']:'';
									//$p_password ="";
									$message = $message_details[0]['sms_description'];
									$message = str_replace("##USERNAME##",$email,$message);
									$message = str_replace("##PASSWORD##",$p_password,$message);
									$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
									$this->commonmodel->send_sms($to,$message);
								}
								//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
								
							}
							
							$mobile_no = isset( $passenger_details[0]['phone'])? $passenger_details[0]['country_code'].$passenger_details[0]['phone']:'';
							$username = isset( $passenger_details[0]['name'])? $passenger_details[0]['name']:'';
							$replace_variables=array(REPLACE_LOGO=>URL_BASE.SITE_LOGO_IMGPATH.$this->domain_name.'_email_logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$username,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_MOBILE=>$mobile_no,REPLACE_PASSWORD=>$p_password,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COMPANYDOMAIN=>$this->domain_name,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
								
							//$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver-register.html',$replace_variables);
							/* Added for language email template */
							if($this->lang!='en')
							{				
								if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/driver-register-'.$this->lang.'.html'))
								{
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/driver-register-'.$this->lang.'.html',$replace_variables);
								}else
								{
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver-register.html',$replace_variables);
								}
							}
							else
							{
								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver-register.html',$replace_variables);
							}
							$to = $email;
							$from = $this->siteemail;
								$subject = __('pass_account_details')." - ".$this->app_name;	
							$redirect = "no";	
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
						//}
				
						/***************************************************/							
								
								
								
								//end
								
								
								$message = array("message" => __('signup_success'),"detail"=>$total_array,"status"=>1);
							}
							else
							{
								$message = array("message" => __('try_again'),"status"=>2);
							}
						}
						else
						{
							$message = array("message" => __('invalid_user'),"status"=>3);
						}	

					}
					echo json_encode($message);
					break;

			//URL:	//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=passenger_login&phone=9999999999&password=123456&deviceid=4&devicetoken=geerge&devicetype=1
			case 'passenger_login':
				$p_login_array = $mobiledata;
				$validator = $this->passenger_login_validation($p_login_array);
				$fcm_token=isset($p_login_array['fcm_token'])?$p_login_array['fcm_token']:'';

					//print_r($p_login_array);exit;
					if($validator->check())
					{ 
					   $phone_exist = $api->check_phone_passengers($p_login_array['phone'],$default_companyid,$p_login_array['country_code']);
					   if($phone_exist == 0)
						{
							$message = array("message" => __('phone_not_exists'),"status"=> 2);
							echo json_encode($message);
							break;
						} //
						else
						{
							$result = $api->passenger_login($p_login_array['phone'],md5(urldecode($p_login_array['password'])),$p_login_array['devicetoken'],$p_login_array['deviceid'],$p_login_array['devicetype'],$default_companyid,$p_login_array['country_code'],$fcm_token);

							if(count($result) > 0)
							{
								//Checking the User Status
								$user_status = $result[0]['user_status'];
								$passenger_email = $result[0]['email'];
								$passenger_id = $result[0]['id'];
								$device_id = $result[0]['device_token'];
								$login_status = $result[0]['login_status'];
								$otp = $result[0]['otp'];
								if($user_status == 'D' || $user_status == 'T' )
								{
									$message = array("message" => __('user_blocked'),"status"=> 3);
								}
								else if($user_status == 'I')
								{
									$detail = array("email"=>$passenger_email,"phone"=>$p_login_array['phone'],"passenger_id"=>$passenger_id);
									$message = array("message" => __('account_not_activated').' Your OTP is '.$otp,"OTP" => $otp,"detail"=>$detail,"status"=> -2);
								}
								else
								{
									$device_token=isset($p_login_array['devicetoken'])?$p_login_array['devicetoken']:'';
									$device_id;
									$update_id = $result[0]['id'];
									
									//$check_personal_date = $api->check_passenger_personal_data($update_id);
									$check_card_data = $api->check_passenger_card_data($update_id);
									//variable to know whether the passenger have credit card
									$credit_card_sts = ($check_card_data == 0) ? 0:SKIP_CREDIT_CARD;
									if(isset($result[0]['name']) && $result[0]['name'] == '')
									{ 
										$detail = array("email"=>$passenger_email,"phone"=>$p_login_array['phone'],"passenger_id"=>$passenger_id);
										$message = array("message" => __('p_personal_data_not_filled'),"status"=> -2,"detail"=>$detail);
									}
									//else if(SKIP_CREDIT_CARD !=1 && $check_card_data == 0)
									else if($result[0]['skip_credit_card'] !=1 && $check_card_data == 0)
									{
										$detail = array("email"=>$passenger_email,"phone"=>$p_login_array['phone'],"passenger_id"=>$passenger_id);
										$message = array("message" => __('p_card_data_not_filled'),"status"=> -3,"detail"=>$detail);
									}
									/*else if(($login_status == 'S')  && ($device_id != $device_token))
									{
										$message = array("message" => __('already_login'),"status"=> 0);								
									}*/					
									else//  && ($device_id == $device_token)
									{ 
										if((!empty($result[0]['profile_image'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'edit_'.$result[0]['profile_image'])){ 
											$edit_image = URL_BASE.PASS_IMG_IMGPATH.'edit_'.$result[0]['profile_image']; 
										}
										else{ 
											$edit_image = URL_BASE."public/images/edit_image.png";
										} 

										$result[0]['edit_image'] = $edit_image;


										if((!empty($result[0]['profile_image'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$result[0]['profile_image'])){ 
											$profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$result[0]['profile_image']; 
										}
										else{ 
											$profile_image = URL_BASE."public/images/no_image109.png";
										} 
										$total_array = array();
										if(count($result) > 0)
										{
											$total_array['id'] = $result[0]['id'];
											$total_array['name'] = $result[0]['name'];
											$total_array['email'] = $result[0]['email'];
											$total_array['profile_image'] = $profile_image;
											$total_array['country_code'] = $result[0]['country_code'];
											$total_array['phone'] = $result[0]['phone'];
											$total_array['login_from'] = $result[0]['login_from'];
											$total_array['referral_code'] = $result[0]['referral_code'];
											$total_array['referral_code_amount'] = $result[0]['referral_code_amount'];
											//this field is used to check whether the user logged in after forgot the password 0 - not forgot, 1- forgot
											$total_array['forgot_password'] = $result[0]['forgot_password'];
											$total_array['split_fare'] = $result[0]['split_fare'];
											$telltofriend_message = TELL_TO_FRIEND_MESSAGE;//str_replace("#REFDIS#",$ref_discount,$ref_message); 
											$total_array['telltofriend_message'] = $telltofriend_message;
											//Newly Added-13.11.2014
											$total_array['site_currency'] = $this->site_currency;
											$total_array['aboutpage_description'] = $this->app_description;
											$total_array['tell_to_friend_subject'] = __('telltofrien_subject');
											
											$total_array['credit_card_status'] = $credit_card_sts;
											/** function to update forgot_password status as 0 **/
											if($total_array['forgot_password'] == 1) {
												$update_pass_array  = array("forgot_password" => '0'); // Start to Pickup
												$result = $api->update_table(PASSENGERS,$update_pass_array,'id',$passenger_id);	
											}
											/***Get Company car model details end***/
											$message = array("message" => __('succesful_login_flash'),"detail"=>$total_array,"status"=> 1);
										}
									}											
								}
								echo json_encode($message);
								break;												
							}							
							else
							{
								$message = array("message" => __('password_failed'),"status"=> 4);
								echo json_encode($message);
								break;										
							}						
						}					
					}
					else
					{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"detail"=>$validation_error,"status"=>-5);
						echo json_encode($message);
						break;				
					}										
				break;	
			/**
			 * Api to check given mobile number is exist in taximobility [ While Fare split from primary passenger ]
			 * http://192.168.1.116:1027/mobileapi116/index/dGF4aV9hbGw=/?type=passenger_mobile_check
			 * {"phone":"9876543210"}
			 * */		
			case 'passenger_mobile_check':
					if(!empty($mobiledata['phone'])) {
						$getArr = 2;//param to get array of passenger details
						$passDetails = $api->checkpassengerPhonewithConcat($mobiledata['phone'],$default_companyid,$getArr);
						if(count($passDetails) > 0) {
							if($passDetails[0]['login_status'] == 'S') {
								$checkPassTrip = $api->checkSecondPassengerinTrip($passDetails[0]['id']);
								if($checkPassTrip > 0){
									$message = array("message" =>__('friend_in_trip'),"detail"=>$passDetails,"status"=> 3);
								} else {
									$message = array("message" =>__('friend_contact_online'),"detail"=>$passDetails,"status"=> 1);
								}
								
							} else {
								$message = array("message" =>__('friend_contact_notin_online'),"detail"=>$passDetails,"status"=> 0);
							}
						} else {
							$message = array("message" =>__('friend_contact_not_found'),"detail"=>$passDetails,"status"=> 2);
							//** SMS Section Starts **//
							if(SMS == 1)
							{
								$message_details = $this->commonmodel->sms_message_by_title('invite_friend_sms');
								if(count($message_details) > 0) {
									$to = $mobiledata['phone'];
									$msg = (count($message_details) > 0) ? $message_details[0]['sms_description'] : '';
									$msg = str_replace("##SITE_NAME##",SITE_NAME,$msg);
									$result = $this->commonmodel->send_sms($to,$msg);
								}
							}
						}
					} else {
						$message = array("message" => __('invalid_request'),"status"=>-1);	
					}
					echo json_encode($message);
			break;
			/**
			 * Api to check secondary passenger approve the Split fare or not
			 * http://192.168.1.116:1027/mobileapi116/index/dGF4aV9hbGw=/?type=splitfare_approval
			 * {"friend_id":"","approve_status":"A","trip_id":""}
			 * */
			case 'splitfare_approval':
					$splitfare_approval = $mobiledata;
					$validator = $this->checkApprovalValidation($mobiledata);	
					if($validator->check())
					{					
						if(!empty($mobiledata['friend_id']))
						{
							//function to check a trip is completed while accept the split trip
							$checkTripStatus = $api->checkTripStatus($mobiledata['trip_id']);
							if($checkTripStatus == 1 || $checkTripStatus == 2 || $checkTripStatus == 3 || $checkTripStatus == 5) {
								$msg = ($checkTripStatus == 1) ? __('trip_already_completed') : __('passenger_in_journey');
								$message = array("message" => $msg,"status"=>2);
							} else {
								$result = $api->setSplitfareApproval($mobiledata['trip_id'],$mobiledata['friend_id'],$mobiledata['approve_status']);
								if($result == 1)
								{	
									$message = array("message" =>__('approve_status_updated'),"trip_id" => $mobiledata['trip_id'],"status"=> 1);
								}
								else if($result == 2)
								{
									$message = array("message" =>__('approve_status_declined'),"trip_id" => $mobiledata['trip_id'],"status"=> 3);
								}
								else
								{
									$message = array("message" => __('invalid_approve'),"status"=>2);
								}
							}
						}
						else
						{
							$message = array("message" => __('invalid_user'),"status"=>-1);	
						}
					}
					else
					{
						$message = array("message" => __('validation_error'),"status"=>0);							
					}
					echo json_encode($message);
			break;
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=passenger_profile&userid=2
			case 'passenger_profile':			
					if($mobiledata['userid'] != null)
					{
						$result = $api->passenger_profile($mobiledata['userid'],'A');
						if(count($result) >0)
						{
							$passenger_image = $result[0]['profile_image'];							
							/*************************** Passenger Image ************************************/
							if((!empty($passenger_image)) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_image))
							{ 
								$profile_image = URL_BASE.PASS_IMG_IMGPATH.$passenger_image; 
							}
							else
							{ 
								$profile_image = URL_BASE."public/images/no_image109.png";
							}
							$result[0]['profile_image'] = 	$profile_image;
							$message = array("message" => __('success'),"detail"=>$result,"status"=>1);	
						}
						else
						{
							$message = array("message" => __('invalid_user'),"status"=>0);	
						}
						
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;			
			//new
			
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=edit_passenger_profile&passenger_id=17&email=prabhu.r@ndot.in&phone=34234234323&salutation=Mr&firstname=Sidhes&lastname=kumar&password=789456&profile_image=
			
			case 'edit_passenger_profile':
					$p_personal_array = $mobiledata;
					//print_r($p_personal_array);
					//echo 'profiletest';
					//exit;
					if(count($p_personal_array)>0)
					{
							if($p_personal_array['email'] != null)
							{
								$p_email = urldecode($p_personal_array['email']);
								$country_code = $p_personal_array['country_code'];
								$p_phone = urldecode($p_personal_array['phone']);
								$passenger_id = $p_personal_array['passenger_id'];
								$password = urldecode($p_personal_array['password']);
								$validator = $this->edit_passenger_profile_validation($p_personal_array);						
								if($validator->check())
								{															
								   $email_exist = $api->edit_check_email_passengers($p_email,$passenger_id,$default_companyid);
								   $phone_exist = $api->edit_check_phone_passengers($p_phone,$passenger_id,$default_companyid,$country_code);
									if($email_exist > 0)
									{
										$message = array("message" => __('email_exists'),"status"=> 1);
										//echo json_encode($message);
									}
									else if($phone_exist > 0)
									{
										$message = array("message" => __('phone_exists'),"status"=> 2);
										//echo json_encode($message);
									}
									else
									{	
										if($p_personal_array['profile_image'] != "")
										{							
										/* Profile Update */
										$imgdata = base64_decode($p_personal_array['profile_image']);
										$f = finfo_open();
										$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
										//echo '<img src="data:image/jpg;base64,'.$p_personal_array['profile_image'].'" />';
										//print_r($mime_type);
										//exit;
										$mime_type = explode('/',$mime_type);
										$mime_type = $mime_type[1];
										//print_r($mime_type);exit;
										//ini_set('gd.jpeg_ignore_warning', true);
										$img = imagecreatefromstring($imgdata); 

										if($img != false)
										{                   
											// get prev image
											$result = $api->passenger_profile($p_personal_array['passenger_id'],'A');
											if(count($result) >0)
											{
												$profile_picture = $result[0]['profile_image'];
												if($profile_picture != "")
												{
													$main_image_path = $_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.$profile_picture;
													$thumb_image_path = $_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$profile_picture;
													if(file_exists($main_image_path) &&($profile_picture != ""))
													{
													unlink($main_image_path);
													}
													if(file_exists($thumb_image_path) &&($profile_picture != ""))
													{
													unlink($thumb_image_path);
													}
												}
											}										
											$image_name = uniqid().'.'.$mime_type;
											$thumb_image_name = 'thumb_'.$image_name;
											$image_url = DOCROOT.PASS_IMG_IMGPATH.'/'.$image_name;                    								
											//header('Content-Type: image/jpeg');					
											$image_path = DOCROOT.PASS_IMG_IMGPATH.$image_name;  
											imagejpeg($img,$image_url);
											imagedestroy($img);
											chmod($image_path,0777);
											$d_image = Image::factory($image_path);
											$path11=DOCROOT.PASS_IMG_IMGPATH;
											//Commonfunction::imageresize($d_image,PASS_IMG_WIDTH, PASS_IMG_HEIGHT,$path11,$image_name,90);
											Commonfunction::imageoriginalsize($d_image,$path11,$image_name,90);
											
											$path12=$thumb_image_name;
											//Commonfunction::imageresize($d_image,PASS_THUMBIMG_WIDTH, PASS_THUMBIMG_HEIGHT,$path11,$thumb_image_name,90);
											Commonfunction::imageoriginalsize($d_image,$path11,$thumb_image_name,90);
											if($password != "")
											{
												$update_array = array(								
												"salutation"=>urldecode($p_personal_array['salutation']),
												"name" => urldecode($p_personal_array['firstname']),
												"lastname" => urldecode($p_personal_array['lastname']),
												"email" => $p_email,
												"country_code" => $country_code,
												"phone" => $p_phone,
												"password" => md5($password),
												//"org_password" => $password,
												"profile_image" => $image_name);
											}
											else
											{
												$update_array = array(								
												"salutation"=>urldecode($p_personal_array['salutation']),
												"name" => urldecode($p_personal_array['firstname']),
												"lastname" => urldecode($p_personal_array['lastname']),
												"email" => $p_email,
												"country_code" => $country_code,
												"phone" => $p_phone,
												"profile_image" => $image_name);										
											}
											
											$message = $api->edit_passenger_personaldata($update_array,$passenger_id,$default_companyid);
										//chmod($image_path, 0777);                    
										}
										else
										{
											$message = array("message" => __('image_not_upload'),"status"=>4);								
										}
										
									}
									else
									{
											if($password != "")
											{
												$update_array = array(								
												"salutation"=>urldecode($p_personal_array['salutation']),
												"name" => urldecode($p_personal_array['firstname']),
												"lastname" => urldecode($p_personal_array['lastname']),
												"email" => $p_email,
												"country_code" => $country_code,
												"phone" => $p_phone,
												"password" => md5($password));
												//"org_password" => $password);
												
											}
											else
											{
												$update_array = array(								
												"salutation"=>urldecode($p_personal_array['salutation']),
												"name" => urldecode($p_personal_array['firstname']),
												"lastname" => urldecode($p_personal_array['lastname']),
												"email" => $p_email,
												"country_code" => $country_code,
												"phone" => $p_phone);										
											}
											//print_r($update_array);
											//exit;
											$message = $api->edit_passenger_personaldata($update_array,$passenger_id,$default_companyid);
									}
										/*****************************************/												
										if($message == 0)
										{
											$passenger_details = $api->passenger_profile($p_personal_array['passenger_id'],'A');
											if((!empty($passenger_details[0]['profile_image'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image'])){ 
												$profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image']; 
											} else { 
												$profile_image = URL_BASE."public/images/no_image109.png";
											}
											$message = array("message" => __('personal_updated'),"profile_image"=>$profile_image,"status"=>1);	
										}	
										if($message == -1)
										{
											$message = array("message" => __('try_again'),"status"=>-1);	
										}											
								}
							}
								else
								{							
									$validation_error = $validator->errors('errors');	
									$message = array("message" => __('validation_error'),"detail"=>$validation_error,"status"=>-3);	
								}
							}
							else
							{
								$message = array("message" => __('invalid_email'),"status"=>-4);	
							}
					}
					else
					{
						$message = array("message" => __('try_again'),"status"=>-5);	
					}
					echo json_encode($message);
					break;

			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=add_card_details&passenger_id=58&email=sakthivel.s.m@ndot.in&creditcard_no=4111111111111111&expdatemonth=08&expdateyear=2014&creditcard_cvv=567&card_type=P&default=1
			case 'add_card_details':
			$p_card_array= $mobiledata;
			$creditcard_no = $p_card_array['creditcard_no'];
			$creditcard_cvv = $p_card_array['creditcard_cvv'];
			$expdatemonth = $p_card_array['expdatemonth'];
			$expdateyear = $p_card_array['expdateyear'];			
			$passenger_id = $p_card_array['passenger_id'];
			$default = $p_card_array['default'];
			$card_validation = $this->passenger_card_validation($p_card_array);
			//print_r($card_validation); exit;
			if($card_validation->check())
			{				
				$authorize_status =$api->isVAlidCreditCard($creditcard_no,"",true);
				//print_r($authorize_status);
				//exit;
				if($authorize_status == 0)
				{
					$message = array("message" => __('invalid_card'),"status"=> 2);
					echo json_encode($message);
					exit;
				}
				$card_exist = $api->check_card_exist($creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$passenger_id);
				if($card_exist > 0)
				{
					$message = array("message" => __('card_exist'),"status"=> 3);
					echo json_encode($message);
					exit;
				}
				//Credit Card Pre authorization section goes here
				//preauthorization with amount "0"(Zero)
				$preAuthorizeAmount = PRE_AUTHORIZATION_REG_AMOUNT;
				list($returncode,$paymentResult,$fcardtype) = $api->creditcardPreAuthorization($passenger_id,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$preAuthorizeAmount);
				if($returncode==0)
				{
					//preauthorization with amount "1"
					$preAuthorizeAmount = PRE_AUTHORIZATION_RETRY_REG_AMOUNT;
					list($returncode,$paymentResult,$fcardtype)= $api->creditcardPreAuthorization($passenger_id,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$preAuthorizeAmount);
				}

				if($returncode != 0)
				{
					$result = $api->add_passenger_carddata($p_card_array,'',$paymentResult,$preAuthorizeAmount,$fcardtype);
					if($result) {
						$void_transaction=$api->voidTransactionAfterPreAuthorize($result,$paymentResult);
					}
				}
				else
				{
					//$message=array("message"=>__('insufficient_fund'),"status"=>3);
					$message=array("message"=> $paymentResult,"status"=>3);
					echo json_encode($message);
					exit;
				}
				//$result = $api->add_passenger_carddata($p_card_array);	
				//echo $result;	
				if($result > 0)
				{
					$message = array("message" => __('card_success'),"status"=>1);		
				}
				else
				{
					$message = array("message" => __('try_again'),"status"=>-1);	
				}
			
			}
			else
			{							
				$validation_error = $card_validation->errors('errors');	
				$message = array("message" => __('validation_error'),"detail"=>$validation_error,"status"=>-3);		
			}
			echo json_encode($message);
			break;

			//URL : http://192.168.1.104:1003/api/index/dGF4aV9hbGw=/?type=edit_card_details&passenger_cardid=58&passenger_id=2&creditcard_no=3530111333300000&expdatemonth=08&expdateyear=2014&creditcard_cvv=567&card_type=P&default=0
			case 'edit_card_details':
			$p_card_array= $mobiledata;
			$passenger_cardid = $p_card_array['passenger_cardid'];
			$passenger_id = $p_card_array['passenger_id'];
			if($passenger_cardid != null)
			{
				$creditcard_no = $p_card_array['creditcard_no'];
				$creditcard_cvv = $p_card_array['creditcard_cvv'];
				$expdatemonth = $p_card_array['expdatemonth'];
				$expdateyear = $p_card_array['expdateyear'];
				$default = $p_card_array['default'];
				$card_validation = $this->edit_passenger_card_validation($p_card_array);
				if($card_validation->check())
				{
					$authorize_status =$api->isVAlidCreditCard($creditcard_no,"",true);
					if($authorize_status == 0)
					{
						$message = array("message" => __('invalid_card'),"status"=> 2);
						echo json_encode($message);
						exit;
					}
					$card_exist = $api->edit_check_card_exist($passenger_cardid,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$passenger_id,$default);
					if($card_exist == 1)
					{
						$message = array("message" => __('card_exist'),"status"=> 3);
						//echo json_encode($message);
					}
					else if($card_exist == 2)
					{
						$message = array("message" => __('one_card_exist'),"status"=> 2);
						//echo json_encode($message);
					}
					else
					{
						//Credit Card Pre authorization section goes here
						//preauthorization with amount "0"(Zero)
						$preAuthorizeAmount = PRE_AUTHORIZATION_REG_AMOUNT;
						list($returncode,$paymentResult,$fcardtype) = $api->creditcardPreAuthorization($passenger_id,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$preAuthorizeAmount);
						if($returncode==0)
						{
							//preauthorization with amount "1"
							$preAuthorizeAmount = PRE_AUTHORIZATION_RETRY_REG_AMOUNT;
							list($returncode,$paymentResult,$fcardtype)= $api->creditcardPreAuthorization($passenger_id,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$preAuthorizeAmount);
						}
						if($returncode != 0)
						{
							$result = $api->edit_passenger_carddata($p_card_array,$paymentResult,$preAuthorizeAmount,$fcardtype);
							if($result == 0) {
								$void_transaction=$api->voidTransactionAfterPreAuthorize($passenger_cardid,$paymentResult);
							}
						}
						else
						{
							//$message=array("message"=>__('insufficient_fund'),"status"=>3);
							$message=array("message"=> $paymentResult,"status"=>3);
							echo json_encode($message);
							exit;
						}
						if($result == 0)
						{
						$message = array("message" => __('edit_card_success'),"status"=>1);		
						}
						else
						{
						$message = array("message" => __('try_again'),"status"=>-1);	
						}
					}
				
				}
				else
				{							
					$validation_error = $card_validation->errors('errors');	
					$message = array("message" => __('validation_error'),"detail"=>$validation_error,"status"=>-3);		
				}
			}
			else
			{
				$message = array("message" => __('try_again'),"status"=>1);
			}
			echo json_encode($message);
			break;
						
						
			/*URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=chg_password_passenger&id=1&old_password=61be360905cecd0e96e31c9d575283b1&new_password=e10adc3949ba59abbe56e057f20f883e&confirm_password=e10adc3949ba59abbe56e057f20f883e&org_new_password=123456
			*/
			case 'chg_password_passenger':
			$p_chg_pass_array = $mobiledata;			
			if(!empty($p_chg_pass_array))
			{
					if($p_chg_pass_array['id'] != null)
					{
						$validator = $this->chg_password_passenger_validation($p_chg_pass_array);						
						if($validator->check())
						{
							//array_shift($p_chg_pass_array);
							//array_shift($p_chg_pass_array);
							$message = $api->chg_password_passenger($p_chg_pass_array,$default_companyid,'P');								
							//{-1 : confirm password must be the same as new password , -2 : Old Password is In Correct: -3: Invalid User,1:Password Changed Successfully	}
							
							switch($message){
								case -1 :
									$message = array("message" => __('confirm_new_same'),"status"=>-1);	
									break;
								case -2 :
									$message = array("message" => __('old_pass_incorrect'),"status"=>-2);
									break;
								case -3 :
									$message = array("message" => __('invalid_user'),"status"=>-3);
									break;
								case 1 :
									$message = array("message" => __('password_changed'),"status"=>1);	
									break;
								case -4 :
									$message = array("message" => __('old_new_pass_same'),"status"=>-4);	
									break;
								}
						}
						else
						{							
							$validation_error = $validator->errors('errors');	
							$message = array("message" => $validation_error,"status"=>-3);							
						}
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>0);	
					}
			}
			else
			{
					$message = array("message" => __('invalid_request'),"status"=>-6);	
				//	echo json_encode($message);	
			}
					echo json_encode($message);
					break;
							
			//URL : api/?type=mark_favourite&pass_log_id=1
			//http://192.168.1.49:1003/mobileapi108/index/dGF4aV9hbGw=/?type=add_favourite&passenger_id=78&p_favourite_place=Vadavalli,coimbatore&p_fav_latitude=11.1425367&p_fav_longtitute=76.1253648&d_favourite_place=Gandhipuram,coimbatore&d_fav_latitude=11.1425367&d_fav_longtitute=76.1253648&fav_comments=test&p_fav_locationtype=home
	case 'add_favourite':
				$add_fav_array = $mobiledata;
				//print_r();
				$validator = $this->favourite_validation($add_fav_array);				
				if($validator->check())
				{
					$passenger_id= $add_fav_array['passenger_id'];					
					$fav_comments = urldecode($add_fav_array['fav_comments']);
					$p_favourite_place = urldecode($add_fav_array['p_favourite_place']);
					$p_fav_latitude = $add_fav_array['p_fav_latitude'];
					$p_fav_longtitute = $add_fav_array['p_fav_longtitute'];
					$d_favourite_place = (isset($add_fav_array['d_favourite_place'])) ? urldecode($add_fav_array['d_favourite_place']) : '';
					$d_fav_latitude = (isset($add_fav_array['d_fav_latitude'])) ? $add_fav_array['d_fav_latitude'] : '';
					$d_fav_longtitute = (isset($add_fav_array['d_fav_longtitute'])) ? $add_fav_array['d_fav_longtitute'] : '';
					$p_fav_locationtype = urldecode($add_fav_array['p_fav_locationtype']);
					$notes = isset($add_fav_array['notes']) ? urldecode($add_fav_array['notes']) :"";
					
					$check_fav_place = $api->check_fav_place($passenger_id,$p_favourite_place,$d_favourite_place,$p_fav_locationtype);
					if($check_fav_place==0)
					{
						//Set the Favourite Trips
						$status = $api->save_favourite($passenger_id,$p_favourite_place,$p_fav_latitude,$p_fav_longtitute,$d_favourite_place,$d_fav_latitude,$d_fav_longtitute,$fav_comments,$notes,$p_fav_locationtype);
						if($status)					
						{
							// Create directory if it does not exist
							if(!is_dir(DOCROOT.MOBILE_FAV_LOC_MAP_IMG_PATH."passenger_". $passenger_id ."/")) {
								mkdir(DOCROOT.MOBILE_FAV_LOC_MAP_IMG_PATH."passenger_". $passenger_id ."/",0777);
							}
							//Map image creation
							include_once MODPATH."/email/vendor/polyline_encoder/encoder.php";
							$polylineEncoder = new PolylineEncoder();
							$polylineEncoder->addPoint($p_fav_latitude,$p_fav_longtitute);
							
							$marker_end = 0;
							if($d_fav_latitude != 0 && $d_fav_longtitute != 0){
								$polylineEncoder->addPoint($d_fav_latitude,$d_fav_longtitute);
								$marker_end = $d_fav_latitude.','.$d_fav_longtitute;
							}
							$encodedString = $polylineEncoder->encodedString();
							$marker_start = $p_fav_latitude.','.$p_fav_longtitute;
							$startMarker = URL_BASE.PUBLIC_IMAGES_FOLDER.'startMarker.png';
							$endMarker = URL_BASE.PUBLIC_IMAGES_FOLDER.'endMarker.png';
							//$startMarker = 'http://testtaxi.know3.com/public/images/startMarker.png';
							//$endMarker = 'http://testtaxi.know3.com/public/images/endMarker.png';
							if($marker_end != 0) {
								$mapurl = "https://maps.googleapis.com/maps/api/staticmap?size=640x270&zoom=13&maptype=roadmap&markers=icon:$startMarker%7C$marker_start&markers=icon:$endMarker%7C$marker_end&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
							} else {
								$mapurl = "https://maps.googleapis.com/maps/api/staticmap?size=640x270&zoom=13&maptype=roadmap&markers=icon:$startMarker%7C$marker_start&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
							}
							
							//echo $mapurl;exit;
							if(isset($mapurl) && $mapurl != "") {
								$file_path = DOCROOT.MOBILE_FAV_LOC_MAP_IMG_PATH."passenger_". $passenger_id ."/fav_".$status.".png";
								file_put_contents($file_path,@file_get_contents($mapurl));
								//$mapurl = URL_BASE.MOBILE_FAV_LOC_MAP_IMG_PATH."passenger_". $passenger_id ."/fav_".$status.".png";
							}
							
							$message = array("message" => __('mark_fav'),"detail"=>"","status"=>1);
						}
						else
						{
							$p_favourite_id = $check_fav_place['0']['p_favourite_id'];
							$message = array("message" => __('try_again'),"status"=>0);	
						}	
					}else if($check_fav_place==-1)
					{
						$message = array("message" => __('fav_already_exist_type'),"status"=>3);
					}
					else
					{
						$message = array("message" => __('fav_already_exist'),"status"=>2);
					}					
				}
				else
				{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"status"=>-3);								
				}
				echo json_encode($message);
				break;
			//URL
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=get_favourite_list&passenger_id=2
			case 'get_favourite_list':		
				if(count($mobiledata) > 0)
				{
					$passenger_id = $mobiledata['passenger_id'];
					$favourite_list = $api->get_favourite_list($passenger_id);
					if(count($favourite_list)>0)
					{
						foreach($favourite_list as $key=>$val){
							$mapurl = '';
							if(file_exists(DOCROOT.MOBILE_FAV_LOC_MAP_IMG_PATH."passenger_". $passenger_id ."/fav_".$val['p_favourite_id'].".png")) {
								$mapurl = URL_BASE.MOBILE_FAV_LOC_MAP_IMG_PATH."passenger_". $passenger_id ."/fav_".$val['p_favourite_id'].".png";
							}
							$favourite_list[$key]['map_image'] = $mapurl;
						}
						
						$message = array("message" => __('success'),"detail"=>$favourite_list,"status"=>1);	
					}
					else
					{
						$message = array("message" => __('no_favourite_trips'),"status"=>0);	
					}				
				}
				else
				{
					$message = array("message" => __('no_favourite_trips'),"status"=>-1);								
				}
				echo json_encode($message);
				break;		
			//URL		
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=get_favourite_details&p_favourite_id=2
			case 'get_favourite_details':
				$p_fav_array = $mobiledata;
				if($p_fav_array['p_favourite_id'] != null)
				{
					$favourite_details = $api->get_favourite_details($p_fav_array['p_favourite_id']);
					$message = array("message" => $favourite_details,"status"=>1);	
				}
				else
				{
					$message = array("message" => __('no_favourite'),"status"=>-1);								
				}
				echo json_encode($message);
				break;
				
			/*Favourite Delete
			 * URL :
			 * 
			 * */
			case 'delete_favourite':
				$p_fav_array = $mobiledata;
				if($p_fav_array['p_favourite_id'] != null && $p_fav_array['passenger_id'] != null)
				{
					$favourite_details = $api->delete_favourite($p_fav_array['p_favourite_id'],$p_fav_array['passenger_id']);
					if($favourite_details) {
						if(file_exists(DOCROOT.MOBILE_FAV_LOC_MAP_IMG_PATH."passenger_". $p_fav_array['passenger_id'] ."/fav_".$p_fav_array['p_favourite_id'].".png")) {
							unlink(DOCROOT.MOBILE_FAV_LOC_MAP_IMG_PATH."passenger_". $p_fav_array['passenger_id'] ."/fav_".$p_fav_array['p_favourite_id'].".png");
						}
						$message = array("message" => __('favourite_deleted'),"status"=>1);	
					} else {
						$message = array("message" => __('no_favourite'),"status"=>-1);
					}
				}
				else
				{
					$message = array("message" => __('no_favourite'),"status"=>-1);								
				}
				echo json_encode($message);
			break;	
			//http://192.168.1.49:1003/mobileapi108/index/dGF4aV9hbGw=/?type=edit_favourite&p_favourite_id=53&passenger_id=78&p_favourite_place=Vadavalli,coimbatore-41&p_fav_latitude=11.1425367&p_fav_longtitute=76.1253648&d_favourite_place=Gandhipuram,coimbatore&d_fav_latitude=11.1425367&d_fav_longtitute=76.1253648&fav_comments=test=78&p_favourite_place=Vadavalli,Gandhipuram,west&p_fav_latitude=11.1425367&p_fav_longtitute=76.1253648&d_favourite_place=Gandhipuram,coimbatore&d_fav_latitude=11.1425367&d_fav_longtitute=76.1253648&fav_comments=&p_fav_locationtype=office
	case 'edit_favourite':
				$edit_fav_array = $mobiledata;
				$validator = $this->edit_favourite_validation($edit_fav_array);	
				if($validator->check())
				{
					$favourite_id= $edit_fav_array['p_favourite_id'];
					$fav_comments = $edit_fav_array['fav_comments'];
					$passenger_id  = $edit_fav_array['passenger_id'];
					$p_favourite_place = urldecode($edit_fav_array['p_favourite_place']);
					$p_fav_latitude = $edit_fav_array['p_fav_latitude'];
					$p_fav_longtitute = $edit_fav_array['p_fav_longtitute'];
					$d_favourite_place = (isset($edit_fav_array['d_favourite_place'])) ? urldecode($edit_fav_array['d_favourite_place']) : '';
					$d_fav_latitude = (isset($edit_fav_array['d_fav_latitude'])) ? $edit_fav_array['d_fav_latitude'] : '';
					$d_fav_longtitute = (isset($edit_fav_array['d_fav_longtitute'])) ? $edit_fav_array['d_fav_longtitute'] : '';
					
					$p_fav_locationtype = urldecode($edit_fav_array['p_fav_locationtype']);
					$notes = isset($edit_fav_array['notes']) ? urldecode($edit_fav_array['notes']):"";
					//Set the Favourite Trips
					$check_fav_place = $api->check_fav_editplace($passenger_id,$p_favourite_place,$d_favourite_place,$favourite_id,$p_fav_locationtype);

					if($check_fav_place==0)
					{ 
						$check_fav_place_exist = $api->check_fav_editplacecheck($passenger_id,$p_favourite_place,$d_favourite_place,$favourite_id,$p_fav_locationtype);
						if($check_fav_place_exist==0)
						{
							$status = $api->edit_favourite($favourite_id,$p_favourite_place,$p_fav_latitude,$p_fav_longtitute,$d_favourite_place,$d_fav_latitude,$d_fav_longtitute,$fav_comments,$notes,$p_fav_locationtype);
							if($status)					
							{
								$message = array("message" => __('edit_mark_fav'),"detail"=>"","status"=>1);
							}
							else
							{
								$message = array("message" => __('no_chage_made'),"status"=>0);	
							}

						 }else{
							$message = array("message" => __('fav_already_exist'),"status"=>2);

						}	
					}else if($check_fav_place==-1){
						$message = array("message" => __('no_data'),"status"=>-3);

					}
					else
					{
						$message = array("message" => __('fav_already_exist_type'),"status"=>3);
					}										
				}
				else
				{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"status"=>-3);	
				}
				echo json_encode($message);
				break;
			case 'check_passenger_trip':
				$passenger_id = (isset($mobiledata['passenger_id'])) ? $mobiledata['passenger_id'] : '';
				if(!empty($passenger_id)) {
					$passengerCompany = $api->get_passenger_company_id($passenger_id);
					$company_id = ($passengerCompany != 0) ? $passengerCompany : $default_companyid;
					$passengerInTrip = $api->check_passenger_in_trip($passenger_id,$company_id);
					if($passengerInTrip > 0) {
						$message = array("message" => __('passenger_in_journey'),"status" => 1);
					} else {
						$message = array("message" => __('invalid_trip'),"status" => 2);
					}
				} else {
					$message = array("message" => __('invalid_request'),"status"=>0);
				}
				echo json_encode($message);
			break;
			//URL : http://192.168.1.49:1015/mobileapi108/index/dGF4aV9hbGw/?type=savebooking&latitude=11.0213687&longitude=76.916638&pickupplace=&dropplace=&drop_latitude=&drop_longitude=&pickup_time=03:00:00&motor_model=1&cityname=Coimbatore&sub_logid=&distance_away=&passenger_id=1&request_type=0&now_after=1&notes=test
                       //Append the Additional Fields while sending...
			case 'savebooking':
					$search_array = $mobiledata;
					$validator = $this->search_validation($search_array);
					$passenger_id = $search_array['passenger_id'];

					$check_driver_status = $api->check_passenger_login_status($passenger_id);
					if($check_driver_status != "A")
					{
						$msg = array("message" => __('account_blocked'),"status"=>109);	
						echo json_encode($msg);
						exit;
					}

					$device_type = (isset($search_array['device_type']))?$search_array['device_type']:'';
					$app_version = isset($search_array['passenger_app_version']) ? $search_array['passenger_app_version'] : '';
					$os_version = (isset($search_array['os_version'])) ? $search_array['os_version'] : '';
					$brand_name = (isset($search_array['brand_name'])) ? $search_array['brand_name'] : '';

					$passenger_app_version_details=json_encode(array('device_type'=>$device_type,'brand_name'=>$brand_name,'os_version'=>$os_version,'app_version'=>$app_version));

					
					$promo_code = isset($search_array['promo_code'])?$search_array['promo_code']:'';
					$referral_code = isset($search_array['referral_code'])?$search_array['referral_code']:'';
					$passenger_payment_option = isset($search_array['passenger_payment_option'])?$search_array['passenger_payment_option']:0;
					if($validator->check())
					{
					if($promo_code != "")
					{
						$check_promo = $api->checkpromocode($promo_code,$passenger_id,$default_companyid);
						if($check_promo == 0)
						{
							$msg = array("message" => __('invalid_promocode'),"status" => 3);
							echo json_encode($msg);
							break;
						}
						else if($check_promo == 3)
						{
							$msg = array("message" => __('promo_code_startdate'),"status" => 3);
							echo json_encode($msg);
							break;
						}
						else if($check_promo == 4)
						{
							$msg = array("message" => __('promo_code_expired'),"status" => 3);
							echo json_encode($msg);
							break;
						}
						else if($check_promo == 2)
						{
							$msg = array("message" => __('promo_code_limit_exceed'),"status" => 3);
							echo json_encode($msg);
							break;
						}
						else if($check_promo == -1)
						{
							$msg = array("message" => __('promo_code_first_transaction'),"status" => 3);
							echo json_encode($msg);
							break;
						}
						else
						{
							$formvalues['promo_code'] = $promo_code;
						}
					}
						
						if($search_array['latitude'] !='0' && $search_array['longitude'] !='0')
						{
							$add_model = Model::factory('add');
							$find_model = Model::factory(FIND);
							$latitude = $search_array['latitude'];
							$longitude = $search_array['longitude'];
							$miles = DEFAULTMILE;//$search_array['no_of_miles'];
							$no_passengers = "";//$search_array['no_of_passengers'];
							$pickup_time = $search_array['pickup_time'];
							
							$pickupplace = urldecode($search_array['pickupplace']);
							$dropplace = urldecode($search_array['dropplace']);
							$drop_latitude = $search_array['drop_latitude'];
							$drop_longitude = $search_array['drop_longitude'];
							
							//$taxi_fare_km = $search_array['taxi_fare_km'];
							$taxi_fare_km = '';
							$motor_company = '1';//$search_array['motor_company'];
							$motor_model = $search_array['motor_model'];
							$maximum_luggage = "";//$search_array['maximum_luggage'];
							$cityname = $search_array['cityname'];
							$sub_logid = $search_array['sub_logid'];
							$now_after = $search_array['now_after'];
							$passenger_id = $search_array['passenger_id'];
							$notes = isset($search_array['notes']) ? $search_array['notes'] : '';
							$passenger_app_version = isset($search_array['passenger_app_version']) ? $search_array['passenger_app_version'] : '';
							$fav_driver_booking_type = isset($search_array['fav_driver_booking_type']) ? $search_array['fav_driver_booking_type'] : 0;
							$approx_trip_fare = isset($search_array['approx_trip_fare']) ? $search_array['approx_trip_fare'] : 0;
							$unit = UNIT; // 0 - KM, 1 - Miles
							$service_type="";
							//print_r($_REQUEST);
							$city_id  = $api->get_city_id($cityname);	
							$passengerCompany = (!empty($passenger_id)) ? $api->get_passenger_company_id($passenger_id) : 0;
							$company_id = ($passengerCompany != 0) ? $passengerCompany : $default_companyid;
							$passengerInTrip = $api->check_passenger_in_trip($passenger_id,$company_id);
							if($passengerInTrip > 0 && $now_after != 1) {
								$errorMessage = ($passengerInTrip == 1) ? __('passenger_in_journey') : __('your_last_payment_pending');
								$msg = array("message" => $errorMessage,"status" => 3);
								echo json_encode($msg);
								break;
							}

							//** Function to check passenger's credit card has expired before booking **//
							$gateway_details = $this->commonmodel->gateway_details();//function to get list of payments used ( cash, creditcard, New card )
							$payoptArr = array();
							foreach($gateway_details as $key=>$valArr){
								$payoptArr[] = $valArr['pay_mod_id'];
							}
							//function to check passenger has credit card
							$card_type = '';
							$result_braintree = '';
							$default = 'yes';
							$returncode = 1;
							$pre_authorize_amount = 0;
							$passCardDetails = $api->get_creadit_card_details($passenger_id, $card_type, $default);
							$approx_trip_fare = round($approx_trip_fare,2);
							if($approx_trip_fare != 0) {
								$check_wallet_amount = $api->checkWalletAmount($passenger_id,$approx_trip_fare);
								if($check_wallet_amount > 0) {
									$pre_authorize_amount = 0;
								} else {
									if(count($passCardDetails) > 0 && $approx_trip_fare != 0) {
										$creditcard_no = encrypt_decrypt('decrypt',$passCardDetails[0]['creditcard_no']);
										$creditcard_cvv = $passCardDetails[0]['creditcard_cvv'];
										$expdatemonth = $passCardDetails[0]['expdatemonth'];
										$expdateyear = $passCardDetails[0]['expdateyear'];
										$pre_authorize_amount = $approx_trip_fare;
										// Verify wether the card is valid or not
										list($returncode,$result_braintree,$fcardtype) = $api->creditcardPreAuthorization($passenger_id,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$pre_authorize_amount);
									}
								}
							}

							//$passvalidcard = $api->check_credit_card_expire($passenger_id, $company_id);
							//if admin payment settings have credit card option and passenger credit card is valid
							/*if(in_array('2',$payoptArr) && (count($passCardDetails) > 0)) {
								$msg = array("message" => __('credit_card_has_expired'),"status" => 3);
								echo json_encode($msg);
								break;
							} */
							if($returncode != 0) {
								$favdriversArr = array();
								if($fav_driver_booking_type != 0) {
									$favDrivers = $api->getFavDrivers($passenger_id);
									$favdriversArr = (!empty($favDrivers)) ? explode(",",$favDrivers) : array();
								}
								
								//$driver_details = $find_model->search_driver_mobileapp($latitude,$longitude,$miles,$passenger_id,$taxi_fare_km,$motor_company,$motor_model,$maximum_luggage,$cityname,$sub_logid,$company_id,$unit,$service_type);	
								/*$pickupTimezone = $api->getpickupTimezone($latitude,$longitude);
								$currentTime = convert_timezone('now',$pickupTimezone);*/
								$pickupTimezone = 'Asia/Amman';
								$currentTime = $this->commonmodel->getcompany_all_currenttimestamp($default_companyid);
								$driver_details = $find_model->getNearestDrivers($motor_model,$latitude,$longitude,$currentTime,$default_companyid,$miles,$unit);
								//print_r($driver_details);exit;
								$nearest_driver='';
								$a=1;
								$temp='10000';
								$prev_min_distance='10000~0';
								$taxi_id='';
								$temp_driver=0;
								$nearest_key=0;
								$prev_key=0;
								$driver_list="";
								$available_drivers ="";
								$avail_nearest_driver = array();
								$fav_driver_list = array();
								$total_count = count($driver_details);
								//echo COMPANY_CONTACT_PHONE_NUMBER;
								$company_contact_no='';
								if(COMPANY_CID != 0)
								{
									$company_contact_no=COMPANY_CONTACT_PHONE_NUMBER;
								}
								$no_vehicle_msg=__('no_vehicle_msg').$company_contact_no;
								$notification_time = $this->notification_time;	
								//print_r($driver_details);
								//exit;
								if($notification_time != 0 ){ $timeoutseconds = $notification_time;}else{$timeoutseconds = 15;}
								//Form Values//
										$formvalues = Arr::extract($mobiledata, array('pickupplace','dropplace','pickup_time','driver_id','passenger_id','roundtrip','passenger_phone','cityname','distance_away','sub_logid','drop_latitude','drop_longitude','promo_code','now_after','motor_model','friend_id1','friend_percentage1','friend_id2','friend_percentage2','friend_id3','friend_percentage3','friend_id4','friend_percentage4','friend_percentage_amt1','friend_percentage_amt2','friend_percentage_amt3','friend_percentage_amt4','approx_trip_fare','passenger_payment_option'));
								$credit_card_sts = SKIP_CREDIT_CARD; 
								if($total_count > 0)
								{
									
										$driver_id = isset($driver_details[0]['driver_id'])?$driver_details[0]['driver_id']:"";
										$taxi_id = isset($driver_details[0]['taxi_id'])?$driver_details[0]['taxi_id']:"";
										//$company_tax = isset($driver_details[0]['company_tax'])?$driver_details[0]['company_tax']:"";
										$totalrating = 0;
										//echo '<pre>';
										//print_r($driver_details);exit;
										foreach($driver_details as $key => $value)
										{
											$updatetime_difference = $value['updatetime_difference'];
											//Exclude the drivers who has not logged in and not update the status last specified seconds
											if($updatetime_difference <= LOCATIONUPDATESECONDS)
											{
												if(count($favdriversArr) > 0 && in_array($value['driver_id'], $favdriversArr)) {
													$fav_driver_list[] = $value['driver_id'];
												} else {
													//$driver_list .= $value['driver_id'].',';
													$avail_nearest_driver[] = $value['driver_id'];
													//$available_drivers = substr_replace($driver_list ,"",-1);
												}
											}
											/* else
											{
												$shiftout_array  = array("shift_status" => 'OUT');
												//$transaction = $this->commonmodel->update(DRIVER,$shiftout_array,'driver_id',$value['driver_id']);
											} */
										}
										
										if($fav_driver_booking_type == 1 && count($fav_driver_list) == 0) {
											$msg = array("message" => __('fav_driver_not_available'),"status" => 4);
											echo json_encode($msg);exit;
										}

										if(count($fav_driver_list) > 0) {
											$avail_nearest_driver = $fav_driver_list;
										}
	
										/*********************************************Save booking ***************************************/

										$formvalues['taxi_id']=$taxi_id;
										$formvalues['pickup_latitude']=$search_array['latitude'];
										$formvalues['pickup_longitude']=$search_array['longitude'];
										$formvalues['approx_distance']=isset($search_array['approx_distance']) ? $search_array['approx_distance'] : '';
										$formvalues['approx_duration']=isset($search_array['approx_duration']) ? $search_array['approx_duration'] : '';
										$formvalues['pickup_longitude']=$search_array['longitude'];
										$formvalues['driver_id'] =$driver_id;
										$formvalues['notes'] = $notes;
										$formvalues['approx_fare'] =$approx_trip_fare;
										//$formvalues['passenger_app_version'] = $passenger_app_version;
										$formvalues['passenger_app_version'] = $passenger_app_version_details;
										$formvalues['pre_transaction_id']=$result_braintree;
										$formvalues['pre_transaction_amount']=$pre_authorize_amount;
										$formvalues['passenger_payment_option'] = $passenger_payment_option;
										$formvalues['currentTime'] = $currentTime;
										$formvalues['pickupTimezone'] = $pickupTimezone;
										//print_r($formvalues);exit;
										$result = $api->savebooking($formvalues,$company_id);
										//to get nearest driver
										//$avail_nearest_driver=explode(',',$available_drivers);
										//print_r($avail_nearest_driver);exit;
										if(count($avail_nearest_driver)>0){
											$nearest_driver=$avail_nearest_driver[0];
										}
										
										$totalNoofDrivers = (count($avail_nearest_driver) < 5) ? count($avail_nearest_driver) : 5;
										$total_request_time = ($totalNoofDrivers * $notification_time) + 20;
										//function to check whether the passenger have wallet amount by this we can give credit card status
										$total_cancelfare = $api->get_passenger_cancel_faredetail($result);
										$passenger_wallet = $api->get_passenger_wallet_amount($passenger_id);
										
										if(count($passenger_wallet) > 0 && $passenger_wallet[0]['wallet_amount'] >= $total_cancelfare) {
											$credit_card_sts = 0;
										}
										
										if(($result > 0) && ($formvalues['now_after'] == 0))
										{
										//$driver_details['city_id'] = $city_id;
												/***** Insert the druiver details to driver request table ************/
												//echo $nearest_driver;exit;
												if(!empty($nearest_driver)) {
													
													if(count($avail_nearest_driver)>0) {
														$available_drivers_Arr = array();
														foreach($avail_nearest_driver as $key=>$driveridVal){
															$driver_has_request = $api->check_driver_has_trip_request($driveridVal,$company_all_currenttimestamp);
															if($driver_has_request == 0 ){
																$available_drivers_Arr[] = $driveridVal;
															}
														}
														$available_drivers =  implode(",",$available_drivers_Arr);
														$nearest_driver = (count($available_drivers_Arr) > 0) ? $available_drivers_Arr[0]: '';
													}
												}
												//echo 'as'.$available_drivers;
												//echo '<br>';
												//echo $nearest_driver;
												//exit;
												//$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($default_companyid);
												$company_det =$api->get_company_id($nearest_driver);
												if(count($company_det)>0){
													$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($company_det[0]['company_id']);
												}
													$insert_array = array(
																			"trip_id" => $result,
																			"available_drivers" 			=> $available_drivers,
																			"total_drivers" 			=> $available_drivers,
																			"selected_driver"		=> $nearest_driver,
																			"status" 	=> '0',
																			"trip_type" 	=> $fav_driver_booking_type,
																			"rejected_timeout_drivers"		=> "",
																			"createdate"		=> $company_all_currenttimestamp,
																		);								
													//Inserting to Transaction Table 
													$transaction = $this->commonmodel->insert(DRIVER_REQUEST_DETAILS,$insert_array);
														
													$detail = array("passenger_tripid"=>$result,"notification_time"=>$notification_time,"total_request_time"=>$total_request_time,"credit_card_status"=>$credit_card_sts);
												
												$msg = array("message" => __('api_request_confirmed_passenger'),"status" => 1,"detail"=>$detail);
												echo json_encode($msg);
												exit;	
										}
										else if(($result > 0) && ($formvalues['now_after'] == 1))
										{
											/** Later Booking E-mail & SMS Send Start **/
											$datas = $this->commonmodel->getPassengerDetails($result);
											$passenger_logid = $datas[0]["passengers_log_id"];
											$pickup_location = $datas[0]["current_location"];
											$drop_location = $datas[0]["drop_location"];
											$pickup_time = $datas[0]["pickup_time"];
											$name = $datas[0]["name"];
											$email = $datas[0]["email"];
											$phone = $datas[0]["country_code"].$datas[0]["phone"];
											$message = "";
											$message .= "Thanks for booking with us, your booking was confirmed. your booking id ".$passenger_logid.". We will contact shortly.<br>";
											$message .= "Pickup Date : ".$pickup_time."<br>";
											$message .= "Pickup Location : ".$pickup_location."<br>";
											$message .= "Drop Location : ".$drop_location;
											$replace_variables=array(
												REPLACE_LOGO => EMAILTEMPLATELOGO,
												REPLACE_SITENAME => $this->app_name,
												REPLACE_USERNAME => $name,
												REPLACE_MESSAGE => $message,
												REPLACE_SITEURL => URL_BASE,
												REPLACE_COPYRIGHTS => SITE_COPYRIGHT,
												REPLACE_COPYRIGHTYEAR => COPYRIGHT_YEAR
											);
											$message = $this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'laterbooking_cofirm_message.html',$replace_variables);

											$to = $email;
											//$to = "durairaj.s@ndot.in";
											$from = $this->siteemail;
											$subject = __('later_booking_confirm_mail')." - ".$this->app_name;
											$redirect = "no";
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

											if(SMS == 1)
											{
												$message_details = $this->commonmodel->sms_message_by_title('booking_confirmed_sms');
												if(count($message_details) > 0) {
													$to = $phone;
													//$to = "+919489443922";
													$message = (count($message_details)) ? $message_details[0]['sms_description'] : '';
													$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
													$message = str_replace("##booking_key##",$passenger_logid,$message);
													$this->commonmodel->send_sms($to,$message);
												}
											}

											/** Later Booking E-mail & SMS Send End **/
											
											//$driver_details['city_id'] = $city_id;
											/***** Insert the druiver details to driver request table ************/
											$detail = array("passenger_tripid"=>$result,"notification_time"=>$notification_time,"total_request_time"=>$total_request_time,"credit_card_status"=>$credit_card_sts);
											$msg = array("message" => __('api_request_disapatcher'),"status" => 1,"detail"=>$detail);
											echo json_encode($msg);
											exit;
										}
										else
										{
											$message = array("message" => __('try_again'),"status"=>2);	
										}
							  }
							  else
							  {
								  if($formvalues['now_after'] == 1) {	
										$formvalues['taxi_id'] = 0;
										$formvalues['pickup_latitude']=$search_array['latitude'];
										$formvalues['pickup_longitude']=$search_array['longitude'];
										$formvalues['approx_distance']=isset($search_array['approx_distance']) ? $search_array['approx_distance'] : '';
										$formvalues['approx_duration']=isset($search_array['approx_duration']) ? $search_array['approx_duration'] : '';
										$formvalues['driver_id'] = 0;
										$formvalues['notes'] =$notes;
										$formvalues['approx_fare'] =$approx_trip_fare;
										$formvalues['pre_transaction_id']=$result_braintree;
										//$formvalues['passenger_app_version'] = $passenger_app_version;
										$formvalues['passenger_app_version'] = $passenger_app_version_details;
										$formvalues['pre_transaction_amount']=$pre_authorize_amount;
										$formvalues['currentTime'] = $currentTime;
										$formvalues['pickupTimezone'] = $pickupTimezone;
									  $result= $api->savebooking($formvalues,$company_id);
									  $detail = array("passenger_tripid"=>$result,"notification_time"=>$notification_time,"total_request_time"=>$notification_time,"credit_card_status"=>$credit_card_sts);
									  $msg = array("message" => __('api_request_disapatcher'),"status" => 1,"detail"=>$detail);
									  echo json_encode($msg);
									  exit;
								  } else {
									   if($fav_driver_booking_type == 1) {
										   $msg = array("message" => __('fav_driver_not_available'),"status" => 4);
									   } else {
										   $msg = array("message" => $no_vehicle_msg,"status" => 3);
									   }
									  echo json_encode($msg);
									  exit;
								  }							  
							  }
						  } else {
							  //$message=array("message"=>__('insufficient_fund'),"status"=>3);
							  $message=array("message"=>$result_braintree,"status"=>3);
							  echo json_encode($message);
							  exit;
						  }
					  }
					  else
					  {
							$message = array("message" => __('lat_not_zero'),"status"=>-4);
							echo json_encode($message);
							exit;	
					  }
					}
					else
					{
						$errors = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"detail"=>$errors,"status"=>-5);
						echo json_encode($message);
						exit;					
					}
					
					break;
			//http://192.168.1.88:1020/api/index/bnRheGlfYlVtUzZGMUJMVDY4VTZtWkdYaDNnRFV2WE5BRGo0=/?type=nearestdriver_list&latitude=10.978942571323032&longitude=76.761474609375&motor_model=&passenger_id=
			case 'nearestdriver_list':
					$search_array = $mobiledata;
					$validator = $this->nearestdriver_validation($search_array);			
					if($validator->check())
					{
						if($search_array['latitude'] !='0' && $search_array['longitude'] !='0')
						{
							$passengerStatus = 3;$passengerStatusMessage = "";
							$passenger_id = $search_array['passenger_id'];
							$passenger_status_result = $api->get_passenger_status($passenger_id);
							if(count($passenger_status_result) > 0) {
								if(isset($passenger_status_result[0]["user_status"]) && $passenger_status_result[0]["user_status"] != 'A') {
									$passengerStatus = 5;
									$passengerStatusMessage = ($passenger_status_result[0]["user_status"] == "D") ? __("passenger_status_blocked_msg") : __("passenger_status_deleted_msg");
								}
							}
							$find_model = Model::factory(FIND);	
							//$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($default_companyid);						
							$latitude = $search_array['latitude'];
							$longitude = $search_array['longitude'];
							$miles = DEFAULTMILE;//$search_array['no_of_miles'];
							$unit = UNIT; // 0 - KM, 1 - Miles
							$taxi_model = $search_array['motor_model'];
							$service_type="";
							$company_id = $default_companyid;
							//$passengerCompany = (!empty($passenger_id)) ? $api->get_passenger_company_id($passenger_id) : 0;
							//$company_id = ($passengerCompany != 0) ? $passengerCompany : $default_companyid;
							//$currentTime = $this->commonmodel->getcompany_all_currenttimestamp($company_id);
							/*
							$pickupTimezone = $api->getpickupTimezone($latitude,$longitude);
							$currentTime = convert_timezone('now',$pickupTimezone);
							*/
							$pickupTimezone = 'Asia/Amman';	
							$currentTime = $this->commonmodel->getcompany_all_currenttimestamp($default_companyid);
							$driver_details = $find_model->getNearestDrivers($taxi_model,$latitude,$longitude,$currentTime,$company_id,$miles,$unit);
							//$driver_details = $find_model->nearestdrivers($latitude,$longitude,$taxi_model,$passenger_id,$miles,$company_id,$unit,$service_type);
							$get_modelfare_details=$api->get_modelfare_details($default_companyid,$taxi_model);
							$getFavDrivers = '';
							if(!empty($passenger_id)) { //to update passengers lat long
								$update_pass_array  = array("latitude" => $latitude, "longitude" => $longitude); // Start to Pickup
								$uplatlong = $api->update_table(PASSENGERS,$update_pass_array,'id',$passenger_id);
								//to get favourite drivers for a passenger
								$favDrivers = $api->getFavDrivers($passenger_id);
								$getFavDrivers = (!empty($favDrivers)) ? explode(",",$favDrivers) : '';
							}
							//print_r($getFavDrivers );exit;
							$nearest_driver='';
							$a=1;
							$temp='10000';
							$prev_min_distance='10000~0~0~0';
							$taxi_id='';
							$temp_driver=0;
							$nearest_key=0;
							$prev_key=0;
							$total_count = count($driver_details);		
							//echo COMPANY_CONTACT_PHONE_NUMBER;					
							$company_contact_no='';
							if(COMPANY_CID != 0)
							{
								$company_contact_no=COMPANY_CONTACT_PHONE_NUMBER;
							}
							$no_vehicle_msg=__('no_vehicle_msg').$company_contact_no;
							
							//Get Fare details of the Taxi model_id Start
							$fare_details=__('no_fare_details_found');
							if($get_modelfare_details!=0){
								$fare_details=$get_modelfare_details[0];
							}
							//print_r($fare_details); 
							//Get Fare details of the Taxi model_id End
							$fare_details['metric'] = UNIT_NAME;
							$fare_details['fare_calculation_type']=FARE_CALCULATION_TYPE;
							$fare_details['passenger_book_notify'] = "Please pay additional to driver ".$fare_details['min_fare'] .CURRENCY;
							$fav_drivers_exist = 0;
							if($total_count > 0)
							{
								$driver_id = isset($driver_details[0]['driver_id'])?$driver_details[0]['driver_id']:"";
								$totalrating = 0;
								foreach($driver_details as $key => $value)
								{
									 //Set nearest driver equal to 1											
										if($driver_id == $value['driver_id'])
										{
											$driver_details[$nearest_key]['nearest_driver'] ='1';
										}
										else
										{
											$driver_details[$key]['nearest_driver'] ='0';
										}
										
										if(!empty($getFavDrivers) && in_array($value['driver_id'],$getFavDrivers)) {
											$fav_drivers_exist++;
										}
									// Get last 20 coordinates of the driver Start
										$get_driver_coordinates= '0';//$api->get_driver_coordinates($value['driver_id']);
										$driver_details[$key]['driver_coordinates'] = $get_driver_coordinates;
									// Get last 20 coordinates of the driver End

									//Get Nearest driver Taxi speed Start										
											//FARE_CALCULATION_TYPE : 1 => Distance, 2 => Time, 3=> Distance / Time
											
									//Get Nearest driver Taxi speed Start

									$driver_details[$key]['distance_km'] = round($value['distance_km'],5);
								}
								
								//$driver_details['city_id'] = $city_id;								
									if(count($driver_details) > 0)
										$message = array("detail" => $driver_details,"fav_drivers"=>$fav_drivers_exist,"fav_driver_message"=>__('fav_driver_book_message'),"fare_details"=>$fare_details,"driver_around_miles"=>DEFAULTMILE,"status" => 1,"message" => 'success','metric'=>UNIT_NAME);
									else
										$message = array("message" => $no_vehicle_msg,"fav_drivers"=>$fav_drivers_exist,"fav_driver_message"=>__('fav_driver_book_message'),"fare_details"=>$fare_details,"driver_around_miles"=>DEFAULTMILE,"status" => 0);	
											
									echo json_encode($message);
									break;
						  }
						  else
						  {
							  $msg = array("message" => $no_vehicle_msg,"fav_drivers"=>$fav_drivers_exist,"fav_driver_message"=>__('fav_driver_book_message'),"fare_details"=>$fare_details,"status" => $passengerStatus,"passenger_status_message" => $passengerStatusMessage);
							  echo json_encode($msg);
							  exit;							  
						  }
					  }
					  else
					  {
							$message = array("message" => __('lat_not_zero'),"status"=>-4);
							echo json_encode($message);
							exit;	
					  }
					}
					else
					{
						$errors = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"detail"=>$errors,"status"=>-5);
						echo json_encode($message);
						exit;					
					}							
					
					break;					
					
            //url : 									
            //http://192.168.1.88:1000/api/?type=driver_arrived&trip_id=205
			case 'driver_arrived':
			$array = $mobiledata;
			$trip_id = $array['trip_id'];
			if($array['trip_id'] != null)
			{
			$check_travelstatus = $api->check_travelstatus($trip_id);

				if($check_travelstatus == -1)
				{
					$message = array("message" => __('invalid_trip'),"status"=>2);
					echo json_encode($message);
					break;
				}				
				if($check_travelstatus == 4)
				{
					$message = array("message" => __('trip_cancelled_passenger'), "status"=>-1);
					echo json_encode($message);
					break;
				}
				if($check_travelstatus != 9)
				{
					$message = array("message" => __('passenger_in_journey'), "status"=>-1);
					echo json_encode($message);
					break;
				}						
				$get_passenger_log_details = $api->get_passenger_log_detail($trip_id);		
				//print_r($get_passenger_log_details);
				//exit;			
				$driver_id = $get_passenger_log_details[0]->driver_id;
				$driver_current_location = $api->get_driver_current_status($driver_id);
				//print_r($driver_current_location);
				//exit;						
				$driver_latitute = $driver_longtitute="";
				if(count($driver_current_location)>0)
				{
					$driver_latitute = $driver_current_location[0]->latitude;
					$driver_longtitute  = $driver_current_location[0]->longitude;
					$driver_status  = $driver_current_location[0]->status;					
				}
		
				//if($driver_status == 'A' || $driver_status == 'B')
				if($driver_status == 'A')
				{
					$message = array("message" => __('already_trip'), "status"=>-1);
					echo json_encode($message);
					break;					
				}
				/********** Update Driver Status after complete Payments *****************/
				$update_pass_array  = array("travel_status" => '3'); // Start to Pickup
				$result = $api->update_table(PASSENGERS_LOG,$update_pass_array,'passengers_log_id',$trip_id);	
				/*************** Update arrival in driver request table ******************/
				$update_trip_array  = array("status"=>'5');
				$driver_request_result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$trip_id);		
				/**************************** Update status in driver table *********/
				$update_driver_arrary  = array("status"=>'B');
				$driver_result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_id);		
				/*************************************************************************/				
				/** Send Trip fare details to Passenger ***/
				$p_device_token = $get_passenger_log_details[0]->passenger_device_token;
				$device_type = $get_passenger_log_details[0]->passenger_device_type;
				$passenger_id = $get_passenger_log_details[0]->passengers_id;
				$pushmessage = array("message"=>__('passenger_on_board'),"trip_id"=>$trip_id,"driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"status"=>2);
				//print_r($pushmessage);
				//exit;
				if(SMS == 1)
				{
					//$this->phone=$this->commonmodel->get_passengers_details($email,1);
					$message_details = $this->commonmodel->sms_message_by_title('driver_arrived');
					if(count($message_details) > 0) {
						$to = $api->get_passenger_phone_by_id($passenger_id);
						//$to = isset($this->phone[0]['phone'])?$this->phone[0]['phone']:'';
						$message = $message_details[0]['sms_description'];
						$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
						$this->commonmodel->send_sms($to,$message);
					}

				}
				//$p_send_notification = $api->send_passenger_mobile_pushnotification($p_device_token,$device_type,$pushmessage,$this->customer_google_api);
				$message = array("message" => __('driver_arrival_send'),"status"=>1);					
			}
			else
			{
				$message = array("message" => __('invalid_trip'),"status"=>-1);	
			}
			echo json_encode($message);	
			break;
												
			//URL : http://192.168.1.104:1003/api/index/dGF4aV9hbGw=/?type=user_logout&driver_id=99&shiftupdate_id=
			case 'user_logout':		
			$driver_logout_array = $mobiledata;
			$driver_id = $mobiledata['driver_id'];			
					if($driver_id != null)
					{
						$shiftupdate_id = $driver_logout_array['shiftupdate_id'];
						$driver_model = Model::factory('driver');
						$update_id = $driver_id;							
						$check_result = $api->check_driver_companydetails($driver_id,$default_companyid);
						if($check_result == 0)	
						{
							$message = array("message" => __('invalid_user'),"status"=>-1);
							echo json_encode($message);
							exit;
						}
					
						$driver_current_status = $api->get_driver_current_status($update_id);
						//print_r($driver_current_status);exit;
						if(count($driver_current_status) > 0)
						{
								$get_driver_log_details = $api->get_driver_log_details($update_id,$default_companyid);
								//print_r($get_driver_log_details);
								 $driver_trip_count = count($get_driver_log_details);//exit;
								if($driver_trip_count == 0)
								{
									$update_array  = array("login_from"=>"","login_status"=>"N","device_id" => "","device_token" => "","device_type" => "","notification_setting"=>"0","notification_status"=>"0","fcm_token"=>"");
									$login_status_update = $this->commonmodel->update(PEOPLE,$update_array,'id',$update_id);
									/*** Update in Driver table **/
									$driver_reply = $driver_model->update_driver_shift_status($update_id,'0');
									/** Update in driver shift history table **/
									$shiftupdate_arrary  = array("shift_end" => $this->currentdate);
									$shiftupdateid = $shiftupdate_id;		
									if($shiftupdateid)
									{
										$transaction = $this->commonmodel->update(DRIVERSHIFTSERVICE,$shiftupdate_arrary,'driver_shift_id',$shiftupdateid);
									}
									$message = array("message" => __('logout_success'),"status"=>1);
								}
								else
								{
									$message = array("message" => __('trip_in_future'),"status"=>-4);
								}
						}
						else
						{
							$drivers_tripids = $api->get_driver_tripsdetails($driver_id);
							$message = array("message" => __('driver_in_trip').' TripIds '.$drivers_tripids,"status"=>0);
						}
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
				echo json_encode($message);	
				break;		
			
			// http://192.168.1.104:1003/mobileapi/index/dGF4aV9hbGw=/?type=get_trip_detail&trip_id=1064
			case 'get_trip_detail':
					$array = $mobiledata;
					$trip_id = $array['trip_id'];
					//passenger_id params come from ios passenger app only
					$passenger_id = isset($array['passenger_id']) ? $array['passenger_id'] : '';
					if($trip_id != null)
					{
						$trip_id = $trip_id;						
						$api_model = Model::factory(MOBILEAPI_107);			
						$get_passenger_log_details = $api_model->get_trip_detail($trip_id,$passenger_id);
						if(count($get_passenger_log_details)>0)
						{
							foreach($get_passenger_log_details as $journey)
							{
								//print_r($journey);
									$driver_id = $journey->driver_id;
									$taxi_id = $journey->taxi_id;
									$company_id = $journey->company_id;
									$driver_image_name = $journey->driver_image;
									$passenger_image = $journey->passenger_image;
									$trip_details['taxi_min_speed']=$journey->taxi_min_speed;
									$trip_details['trip_id'] = $journey->passengers_log_id;
									$trip_details['current_location'] = $journey->pickup_location;
									$trip_details['pickup_latitude'] = $journey->pickup_latitude;
									$trip_details['pickup_longitude'] = $journey->pickup_longitude;
									$trip_details['drop_location'] = $journey->drop_location;
									$trip_details['drop_latitude'] = $journey->drop_latitude;
									$trip_details['drop_longitude'] = $journey->drop_longitude;
									$trip_details['drop_time'] = ($journey->drop_time != "0000-00-00 00:00:00") ? Commonfunction::getDateTimeFormat($journey->drop_time,1) : "";
									$trip_details['pickup_date_time'] = ($journey->actual_pickup_time != "0000-00-00 00:00:00") ? Commonfunction::getDateTimeFormat($journey->actual_pickup_time,1) : Commonfunction::getDateTimeFormat($journey->pickup_time,1);
									$trip_details['pickup_time'] = ($journey->actual_pickup_time != "0000-00-00 00:00:00") ? date("H:i:s",strtotime($journey->actual_pickup_time)) : "";
									$trip_details['booking_time'] = Commonfunction::getDateTimeFormat($journey->pickup_time,1);
									$trip_details['time_to_reach_passen'] = str_replace('Min','',$journey->time_to_reach_passen);		
									$trip_details['no_passengers']= $journey->no_passengers;	
									$trip_details['rating'] = $journey->rating;
									$trip_details['notes']= $journey->notes_driver;																
									$trip_details['driver_name'] = $journey->driver_name;								
									$trip_details['driver_id'] = $journey->driver_id;							
									$trip_details['taxi_id'] = $journey->taxi_id;
									$trip_details['taxi_number'] = $journey->taxi_no;
									//$trip_details['driver_phone'] = $journey->driver_phone_code.$journey->driver_phone;
									$trip_details['driver_phone'] = !empty($journey->driver_twilio_number) ? trim($journey->driver_twilio_number) : trim($journey->driver_phone);
									$trip_details['passenger_phone'] = !empty($journey->passenger_phone) ? $journey->passenger_phone : "";
									$trip_details['passenger_name'] = $journey->passenger_name;									
									$passengerWallAmt = $journey->wallet_amount;									
									$trip_details['travel_status'] = $journey->travel_status;	
									$trip_details['bookedby'] =  $journey->bookby;//1-passenger, 2-Dispatcher, 3-Driver
									$trip_details['street_pickup_trip'] = ($journey->bookby == 3) ? 1 : 0;
									//$trip_details['waiting_time'] =  str_replace(" Mins","",$journey->waiting_time);
									$trip_details['waiting_time'] = $journey->waiting_time;
									$trip_details['waiting_fare'] =  ($journey->waiting_cost != "" && $journey->waiting_cost != null) ? $journey->waiting_cost : 0;
									$trip_details['distance'] =  $journey->distance;
									$trip_details['actual_distance'] =  $journey->actual_distance;
									$trip_details['metric'] =  !empty($journey->metric) ? $journey->metric : UNIT_NAME;
									$trip_details['amt'] = round($journey->amt,2);		
									$trip_details['actual_paid_amount'] = ($journey->is_split_trip == 1 && isset($journey->split_paid_amount)) ? round($journey->split_paid_amount,2) : round($journey->actual_paid_amount,2);		
									//$trip_details['used_wallet_amount'] = ($journey->is_split_trip == 1 && isset($journey->split_wallet)) ? round($journey->split_wallet,2) : round($journey->used_wallet_amount,2);
									$trip_details['used_wallet_amount'] = ($journey->payment_type == 5 ) ? round($journey->actual_paid_amount,2) : 0;		
									$trip_details['job_ref'] = $journey->job_ref;		
									$trip_details['payment_type'] = $journey->payment_type;	
									$trip_details['fare_calculation_type'] = $journey->fare_calculation_type;	
									$trip_details['payment_type_label'] = ($journey->payment_type == 1) ? __('cash'):(($journey->payment_type == 5) ? __('wallet') : __('card'));		
									$trip_details['taxi_speed'] = $journey->taxi_speed;
									$trip_details['waiting_fare_hour'] = $journey->waiting_fare_hour;
									$trip_details['fare_per_minute'] = $journey->fare_per_minute;
									$subtotal = $journey->tripfare + $trip_details['waiting_fare'] + $journey->eveningfare + $journey->nightfare;
									$trip_details['subtotal'] = round($subtotal,2);
									$trip_details['minutes_fare'] = $journey->minutes_fare;
									$trip_details['distance_fare'] = round((($journey->tripfare) - ($journey->minutes_fare)),2);
									$distance_fare_metric =  ($journey->distance > 1) ? ($trip_details['distance_fare'] / $journey->distance) : $trip_details['distance_fare']; 
									$trip_details['distance_fare_metric'] = round($distance_fare_metric,2);
									$trip_details['trip_minutes'] = $journey->trip_minutes;
									$trip_details['promocode_fare'] = $journey->promocode_fare;
									$trip_details['eveningfare'] = $journey->eveningfare;
									$trip_details['nightfare'] = $journey->nightfare;
									$trip_details['tax_percentage'] = $journey->tax_percentage;
									$trip_details['tax_fare'] = $journey->tax_fare;
									$trip_details['isSplit_fare'] = (int)$journey->is_split_trip;//0-Normal trip, 1-Split Trip
									//variable to know whether the passenger have credit card
									$check_card_data = $api->check_passenger_card_data($journey->passengers_id);
									$totalCancelFare = $api->get_passenger_cancel_faredetail($trip_id);
									$passengerReferrDet = $api->check_passenger_referral_amount($passenger_id);

									$fare_setting = $api->get_cancel_setting($company_id); 
									//check unused referral amount with existing wallet amount
									$referralAmt = (isset($passengerReferrDet[0]['referral_amount'])) ? $passengerReferrDet[0]['referral_amount'] : 0;
									$reducAmt = ($referralAmt != 0) ? ($passengerWallAmt - $referralAmt) : $passengerWallAmt;

									$credit_card_sts = (($check_card_data == 0) || ($passengerWallAmt > 0 && $reducAmt >= $totalCancelFare) || ($fare_setting == 0)) ? 0 : SKIP_CREDIT_CARD;
									
									$trip_details['credit_card_status'] = $credit_card_sts;
									//condition to check the passenger is primary or not
									$trip_details['is_primary'] = (!empty($passenger_id) && $passenger_id != $journey->passengers_id) ? false : true;
									$trip_details['trip_duration'] = "0";
									if($trip_details['drop_time'] != "") {
										//total trip duration
										$trip_seconds = strtotime($trip_details['drop_time']) - strtotime($trip_details['pickup_time']);
										$trip_days    = floor($trip_seconds / 86400);
										$trip_hours   = floor(($trip_seconds - ($trip_days * 86400)) / 3600);
										$trip_minutes = floor(($trip_seconds - ($trip_days * 86400) - ($trip_hours * 3600))/60);
										$trip_seconds = floor(($trip_seconds - ($trip_days * 86400) - ($trip_hours * 3600) - ($trip_minutes*60)));
										$trip_hours = ($trip_hours < 10) ? '0'.$trip_hours : $trip_hours;
										$trip_minutes = ($trip_minutes < 10) ? '0'.$trip_minutes : $trip_minutes;
										$trip_seconds = ($trip_seconds < 10) ? '0'.$trip_seconds : $trip_seconds;
										$trip_details['trip_duration'] = $trip_hours.":".$trip_minutes.":".$trip_seconds;
									}
									$mapurl = '';
									//map image for completed trips in trip detail page
									if($journey->travel_status == 1) {
										if(file_exists(DOCROOT.MOBILE_TRIP_DETAIL_MAP_IMG_PATH.$trip_id.".png")) {
											$mapurl = URL_BASE.MOBILE_TRIP_DETAIL_MAP_IMG_PATH.$trip_id.".png";
										} else {
											$path = $journey->active_record;
											$path = str_replace('],[', '|', $path);
											$path = str_replace(']', '', $path);
											$path = str_replace('[', '', $path);
											$path = explode('|',$path);$path = array_unique($path);
											include_once MODPATH."/email/vendor/polyline_encoder/encoder.php";
											$polylineEncoder = new PolylineEncoder();
											if($journey->active_record != 0 && count(array_filter($path)) > 0)
											{
												foreach($path as $values)
												{
												$values = explode(',',$values);
												$polylineEncoder->addPoint($values[0],$values[1]);
												$polylineEncoder->encodedString();
												}
											}
											$encodedString = $polylineEncoder->encodedString();
											
											$marker_end = $journey->drop_latitude.','.$journey->drop_longitude;
											$marker_start = $journey->pickup_latitude.','.$journey->pickup_longitude;
											$startMarker = URL_BASE.PUBLIC_IMAGES_FOLDER.'startMarker.png';
											$endMarker = URL_BASE.PUBLIC_IMAGES_FOLDER.'endMarker.png';
											//$startMarker = 'http://testtaxi.know3.com/public/images/startMarker.png';
											//$endMarker = 'http://testtaxi.know3.com/public/images/endMarker.png';
											if($marker_end != 0) {
												$mapurl = "https://maps.googleapis.com/maps/api/staticmap?size=640x640&zoom=13&maptype=roadmap&markers=icon:$startMarker%7C$marker_start&markers=icon:$endMarker%7C$marker_end&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
											} else {
												$mapurl = "https://maps.googleapis.com/maps/api/staticmap?size=640x640&zoom=13&maptype=roadmap&markers=icon:$startMarker%7C$marker_start&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
											}
											if(isset($mapurl) && $mapurl != "") {
												$file_path = DOCROOT.MOBILE_TRIP_DETAIL_MAP_IMG_PATH.$trip_id."ss.png";
												file_put_contents($file_path,@file_get_contents($mapurl));
												$mapurl = URL_BASE.MOBILE_TRIP_DETAIL_MAP_IMG_PATH.$trip_id."ss.png";
											}
										} 
									}
									$trip_details['map_image'] = $mapurl;
							}
							//exit;
							
							/*$paymentname="";
							if($trip_details['payment_type'] != '5') {
								$paymentname_sql = $api->get_payment_name($trip_details['payment_type']);
								if(count($paymentname_sql) > 0)
								{
									$paymentname = $paymentname_sql[0]['pay_mod_name'];
								}
							} else {
								$paymentname = "Wallet";
							}
							$trip_details['payment_type'] = $paymentname;*/
							/************************************Driver Image *******************************/					
								$driver_image = $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DRIVER_IMGPATH.'thumb_'.$driver_image_name;
								if(file_exists($driver_image) && ($driver_image_name !=''))
								{
								$driver_image = URL_BASE.SITE_DRIVER_IMGPATH.'thumb_'.$driver_image_name;
								}else{
								$driver_image = URL_BASE."/public/images/noimages109.png";
								}		
								$trip_details['driver_image'] = $driver_image;
								
							/*************************** Passenger Image ************************************/
							if((!empty($passenger_image)) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_image))
							{ 
								 $profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_image; 
							}
							else
							{ 
								$profile_image = (isset($trip_details['bookedby']) && $trip_details['bookedby'] == 3) ? URL_BASE."public/".UPLOADS."/iOS/static_image/streetPickupFocus.png" : URL_BASE."public/images/no_image109.png";
							}	
							$trip_details['passenger_image'] = $profile_image;
							$trip_details['driver_latitute'] = $trip_details['driver_longtitute'] = '0.0';
							$current_driver_status = $api_model->get_driver_current_status($driver_id);
							if(count($current_driver_status)>0)
							{
								foreach($current_driver_status as $driver_details)
								{
									$trip_status = $driver_details->status;
									$trip_details['driver_latitute'] = $driver_details->latitude;
									$trip_details['driver_longtitute'] = $driver_details->longitude;									
								}
							}
							
							$trip_details['driver_status'] =  (isset($trip_status) && $trip_status != 'B') ?  $trip_status : 'F';

							$dresult = $api->driver_ratings($driver_id);
							$totalrating=0;
							if(count($dresult) > 0)
							{
								$overall_rating = 5; $i=0; $trip_total_with_rate=1;
								
								foreach($dresult as $comments)
								{
									if($comments['rating'] != 0)
									$trip_total_with_rate++;
									$rating = $comments['rating'];
									//print_r($comments);
									$overall_rating += $comments['rating'];
									$i++;	
								}
																		
								if($trip_total_with_rate!=0 && $overall_rating!=0){
									$totalrating = $overall_rating/$trip_total_with_rate;
								}else{
									$totalrating = 5;
								}		
																
								$totalrating = round($totalrating);	
									 //echo 'as'.$totalrating;												
							}
							else
							{
								$totalrating = 5;
							}							
							$trip_details['driver_rating'] = $totalrating;
							/** Split Fare Details **/
							$splitApproveArr = array();
							if($trip_details['isSplit_fare'] == 1){
								$splitApproveArr = $api->getSplitFareStatus($trip_id);
								if(count($splitApproveArr) > 1){
									foreach($splitApproveArr as $splkey=>$splits){
										if((!empty($splits['profile_image'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.$splits['profile_image'])){ 
											$profile_image = URL_BASE.PASS_IMG_IMGPATH.$splits['profile_image']; 
										}
										else{ 
											$profile_image = URL_BASE."public/images/no_image109.png";
										}
										$splitApproveArr[$splkey]['profile_image'] = $profile_image;
										$splittedFare = ($trip_details['amt'] * $splits['fare_percentage']) / 100;
										$splitApproveArr[$splkey]['splitted_fare'] = round($splittedFare, 2);
									}
								}
							}
							$trip_details['splitFareDetails'] = $splitApproveArr;
							
							//print_r($upcoming_journey);
							if(count($get_passenger_log_details) == 0)
							{
								$message = array("message" => __('try_again'),"status"=>0,"site_currency"=>$this->site_currency);	
							}
							else
							{
								$mes = __('success');
								if($trip_details['travel_status'] == 5) {
									$mes = __('trip_waiting_payment');
								} else if($trip_details['travel_status'] == 4) {
									$mes = __('cancel_by_passenger');
								}
								$message = array("message" => $mes,"detail"=>$trip_details,"status" => 1,"site_currency"=>$this->site_currency);
							}	
					}
					else
					{
						$message = array("message" => __('invalid_trip'),"status"=>-1,"site_currency"=>$this->site_currency);	
					}									
				}
				else
				{
					$message = array("message" => __('invalid_trip'),"status"=>-1,"site_currency"=>$this->site_currency);	
				}			
				echo json_encode($message);	
				break;
			//URL : api/?type=passenger_logout&id=7
			case 'passenger_logout':	
			$passenger_log_array = $mobiledata;		
					if($passenger_log_array['id'] != null)
					{
						$api_model = Model::factory(MOBILEAPI_107);	
						//$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($default_companyid);						
						$update_id = $passenger_log_array['id'];
						$check_result = $api->check_passenger_companydetails($passenger_log_array['id'],$default_companyid);
						if($check_result == 0)	
						{
							$message = array("message" => __('invalid_user'),"status"=>-1);
							echo json_encode($message);
							exit;
						}

						$update_array  = array("login_from"=>"","login_status"=>"N","device_id" => "","device_token" => "","device_type" => "","fcm_token"=>"");
						$logout_status_update = $api_model->update_passengers($update_array,$update_id,$default_companyid);
						$delete_rejected_trips = $api_model->delete_rejected_trips($update_id,$company_all_currenttimestamp);
						$message = array("message" => __('logout_success'),"status"=>1);					
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>0);	
					}
				echo json_encode($message);	
				break;		
				
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=tell_to_friend_message&id=58&usertype=P
			case 'tell_to_friend_message':			
			$array = $mobiledata;
			$id = $array['id'];
			$type = $array['usertype'];
			$device_type = $array['device_type'];
			//$referral_code = $telltofriend_message = "";
				$subject = __('telltofrien_subject');
				$name="";
				$message = "";
				if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/site_logo/'.$this->domain_name.'_email_logo.png')){  $email_logo=$this->domain_name; }else{ $email_logo="demo"; }
				if($device_type == 1)
				{
					$replace_variables=array(
						REPLACE_LOGO=>EMAILTEMPLATELOGO,
						REPLACE_SITENAME=>$this->app_name,
						REPLACE_NAME=>$name,
						REPLACE_MESSAGE=>TELL_TO_FRIEND_MESSAGE,
						REPLACE_SITEEMAIL=>$this->siteemail,
						REPLACE_SITEURL=>URL_BASE,
						REPLACE_EMAIL_LOGO=>$email_logo,
						REPLACE_ANDROID_PASSENGER_APP=>ANDROID_PASSENGER_APP,
						REPLACE_IOS_PASSENGER_APP=>IOS_PASSENGER_APP,
						REPLACE_ANDROID_DRIVER_APP=>ANDROID_DRIVER_APP,
					);
				}
				else
				{
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,
						REPLACE_SITENAME=>$this->app_name,
						REPLACE_NAME=>$name,
						REPLACE_SITEEMAIL=>$this->siteemail,
						REPLACE_SITEURL=>URL_BASE,
						REPLACE_MESSAGE=>TELL_TO_FRIEND_MESSAGE,
						REPLACE_ANDROID_PASSENGER_APP=>ANDROID_PASSENGER_APP,
						REPLACE_EMAIL_LOGO=>$email_logo,
						REPLACE_IOS_PASSENGER_APP=>IOS_PASSENGER_APP,
						REPLACE_ANDROID_DRIVER_APP=>ANDROID_DRIVER_APP,
						REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,
						REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR
					);					
				}
				
				/* Added for language email template */
			if($this->lang!='en'){
			if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/telltofriend-'.$this->lang.'.html')){
			$message_temp=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/telltofriend-'.$this->lang.'.html',$replace_variables);
			}else{
			$message_temp=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'telltofriend.html',$replace_variables);
			}
			}else{
			$message_temp=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'telltofriend.html',$replace_variables);
			}
			/* Added for language email template */
				//echo $message_temp;exit;
				//print_r($message_temp);
				$message_template = htmlspecialchars($message_temp);
							
			if(($id != null) && ($type != null))
			{
				if($type == 'D')
				{
					$driver_profile = $api->driver_profile($id);	
					if(count($driver_profile)>0)
					{
						$referral_code = $driver_profile[0]['driver_referral_code'];		
						$name = $passenger_profile[0]['name'];				
						$telltofriend_message = DRIVER_TELL_TO_FRIEND_MESSAGE; 
						$detail = array("tell_message" => $telltofriend_message,"message_template"=>$message_template,"subject"=>$subject);
						$message = array("detail"=>$detail,"status"=>1,"message"=>__('success'));
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>0);
					}
					
				}				
				else
				{
					$passenger_profile = $api->passenger_profile($id,'A');
					if(count($passenger_profile)>0)
					{
						$referral_code = $passenger_profile[0]['referral_code'];
						$name = $passenger_profile[0]['name'];
						$ref_message = TELL_TO_FRIEND_MESSAGE.''.$referral_code;
						$ref_discount = REFERRAL_DISCOUNT;
						$telltofriend_message = TELL_TO_FRIEND_MESSAGE;//str_replace("#REFDIS#",$ref_discount,$ref_message); 						
						$detail = array("tell_message" => $telltofriend_message,"message_template"=>$message_template,"subject"=>$subject);
						$message = array("detail"=>$detail,"status"=>1,"message"=>__('success'));
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>0,"message"=>__('failed'));
					}
				}		
				//$message = array("message" => $telltofriend_message,"status"=>1,"message"=>__('success'));								
			}
			else
			{
				$message = array("message" => __('validation_error'),"status"=>-1,"message"=>__('failed'));	
			}			
									
            if($device_type == 1)
            {
				$search = array('"');
				$replace = array("'");			
				echo $str = str_ireplace($search,$replace,$message_temp);							
			}
			else
			{
				echo json_encode($message);
			}
				
			break;			
						//URL
			// http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=tell_to_friend&to=ndottagmytaxi2014@gmail.com&message=Flagger left you hail a taxi. Download it for free to get 5% off your first ride&passenger_id=58
			case 'tell_to_friend':
			$tell_array = $mobiledata;
			if(!empty($tell_array))
			{
				$to = $tell_array['to'];
				$message = $tell_array['message'];
				$passenger_id = $tell_array['passenger_id'];
				$name = $email = $referral_code = "";
				$check_validation = $this->check_tell_to_friend($tell_array);
				if($check_validation->check()) 
				{
					$passenger_details = $api->passenger_profile($passenger_id,'A');
					$referral_code = "";
					if(count($passenger_details)>0)
					{
						$name = $passenger_details[0]['name'];
						$email = $passenger_details[0]['email'];
						$referral_code = $passenger_details[0]['referral_code'];
					}
					
							$friends_email = explode(',',$to);
							$rejectedemails="";
							$successemails = "";
							$mail="";
							foreach($friends_email as $femail)					
							{	
								$check_list = $api->check_email_passengers($femail);
								if($check_list > 0)
								{
									$rejectedemails .= $femail.',';
									//$message = array("message" => $rejectedemails.' '.__('already_reg'),"status"=> -1);
									//echo json_encode($message);
								}
								else
								{ 
				
								if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/site_logo/'.$this->domain_name.'_email_logo.png')){  $email_logo=$this->domain_name; }else{ $email_logo="demo"; }
								
									$subject = __('telltofrien_subject');
									$replace_variables=array(
										REPLACE_LOGO=>EMAILTEMPLATELOGO,
										REPLACE_SITENAME=>$this->app_name,
										REPLACE_NAME=>$name,
										REPLACE_EMAIL=>$email,
										REPLACE_SUBJECT=>$subject,
										REPLACE_MESSAGE=>$message,
										REPLACE_SITEEMAIL=>$this->siteemail,
										REPLACE_SITEURL=>URL_BASE,
										REPLACE_COMPANYDOMAIN=>$this->domain_name,
										REPLACE_ANDROID_PASSENGER_APP=>ANDROID_PASSENGER_APP,
										REPLACE_IOS_PASSENGER_APP=>IOS_PASSENGER_APP,
										REPLACE_EMAIL_LOGO=>$email_logo,
										REPLACE_ANDROID_DRIVER_APP=>ANDROID_DRIVER_APP,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
									//print_r($replace_variables);exit;
									
			/* Added for language email template */
			if($this->lang!='en'){
			if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/telltofriend-'.$this->lang.'.html')){
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/telltofriend-'.$this->lang.'.html',$replace_variables);
			}else{
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'telltofriend.html',$replace_variables);
			}
			}else{
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'telltofriend.html',$replace_variables);
			}
			/* Added for language email template */
									
									//print_r(htmlspecialchars($message));exit;
									$friend_to = $femail;
									$from = $this->siteemail;
									$successemails .= $femail.',';	
									$redirect = "no";	
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
								//$rejectedemails.' '.__('already_reg')
								if(empty($successemails))
								{
									$detail = __('already_reg');
								}
								else
								{
									$detail =  __('invitation_send');						
								}
								$message = array("detail"=>$detail,"status"=>1,"message"=>__('success'));
							}	
					}
					else
					{
						$detail = $check_validation->errors('errors');
						$message = array("detail"=>$detail,"status"=>2,"message"=>__('validation_error'));
					}
			}
			else
			{
				$message = array("message" => __('invalid_request'),"status"=> 5);
			}	
            echo json_encode($message);								
			break;		
			//URL
			// http://192.168.1.88:1003/api/index/dGF4aV9hbGw=/?type=dynamic_page&pagename=termsconditions&device_type=1
			case 'dynamic_page':			
			$page_array = $_GET;
			$check_validation = $this->check_dynamic_array($page_array);
			if($check_validation->check()) 
			{
					$pagename = $page_array['pagename'];
					$device_type = $page_array['device_type'];
					$pagecontent=$content="";
					if($pagename != null)
					{	
						$content_cms = $api->getcmscontent($pagename,$default_companyid);
						if(count($content_cms)>0)
						{
							foreach($content_cms as $value)
							{								
								$pagecontent = $value['content'];
								//$content = stripcslashes($pagecontent);
								$content = htmlentities($pagecontent);								
								$menu = $value['menu'];
							}
						}
						else
						{
							if($device_type == 1)
							{
								echo __('page_not_found');
								break;	
							}
							else if($device_type == 2)
							{
								$message = array("message" => __('page_not_found'),"status"=>2);
								echo $json_decode = json_encode($message);	
								break;	
							}			
							else
							{
								$message = array("message" => __('page_not_found'),"status"=>2);
								echo $json_decode = json_encode($message);	
								break;
							}	
	
						}							
					}
					else
					{
						$message = array("message" => __('invalid_page'),"status"=>-1);	
						echo $json_decode = json_encode($message);
						break;	
					}
				if($device_type == 1)
				{
					echo $pagecontent;
					break;	
				}
				else if($device_type == 2)
				{
					$result = array("content"=>$content,"title"=>$menu);
					$message = array("message"=>__('success'),"detail" => $result,"status"=>1);
					echo $json_decode = json_encode($message);	
					break;	
				}			
				else
				{
					$message = array("message" => __('invalid_page'),"status"=>-1);	
					echo $json_decode = json_encode($message);
					break;	
				}	 
			}
			else
			{
					$detail = $check_validation->errors('errors');
					$message = array("detail"=>$detail,"status"=>-3,"message"=>__('validation_error'));		
					echo json_encode($message);			
			}			
				//echo $pagecontent;
				//*/
			break;								

			//URL : http://192.168.1.88:1000/api/index/?type=completed_journey_datewise&passenger_id=1&start=0&limit=5&date=2012-12-26&device_type=1			
			case 'completed_journey_datewise':
				$array = $mobiledata;				
				if($array['passenger_id'] != null)
				{
					$validator = $this->trip_history_date_wise($array);					
					if($validator->check()) 
					{					
						$userid= $array['passenger_id'];
						$start = $array['start'];
						$limit = $array['limit'];	
						$date = $array['date'];		
						$device_type = $array['device_type']; // 1 Android , 2 - IOS
						//Getting from Passenger Model Directly
						$passengers = Model::factory('passengers');
						$booktype="2";
						$fromdate = $date.' 00:00:01';
						$todate = $date.' 23:59:59';
						$arraydetails = array();
						$alldetails = array();
						if($device_type == 1)
						$pagination = 1;
						else
						$pagination = 0;
						$total_array = array();
						for($i = strtotime($fromdate); $i <= strtotime($todate); $i = strtotime('+1 Day', $i))
						{
							$cdate = date("Y-m-d",$i);						
							$passengers_all_compl = $api->get_passenger_trips_bydate($pagination,$booktype,$userid,1,'A','1',$start,$limit,$cdate);
							if(count($passengers_all_compl) > 0)
							{
								foreach($passengers_all_compl as $result)
								{
									$arraydetails['trip_id'] = $result['trip_id'];
									$arraydetails['place'] = $result['place'];
									$arraydetails['booking_time'] = $result['pickup_time'];
									$arraydetails['pickup_time'] = ($result['actual_pickup_time'] != "0000-00-00 00:00:00") ? $result['actual_pickup_time'] : $result['pickup_time'];
									$arraydetails['fare'] = $result['fare'];
									$arraydetails['pickup_latitude'] = $result['pickup_latitude'];
									$arraydetails['pickup_longitude'] = $result['pickup_longitude'];
									$arraydetails['drop_latitude'] = $result['drop_latitude'];
									$arraydetails['drop_longitude'] = $result['drop_longitude'];
									$arraydetails['notes_driver'] = $result['notes_driver'];
									$arraydetails['drivername'] = $result['drivername'];
									$arraydetails['drop_location'] = $result['drop_location'];
									$date =$result['pickup_time'];
									$alldetails[] = $arraydetails;
								}
								$total_array[] = array("trip_Date" => $cdate,"trip_details" => $alldetails);
							}							
						}						
						if(count($total_array) > 0)
						{
							$message = array("message" => __('success'),"detail"=>$total_array,"status"=>1,"site_currency"=>$this->site_currency);	
							//$message = $passengers_all_compl;
						}
						else
						{
							$message = array("message" => __('no_completed_data_date'),"status"=>0,"site_currency"=>$this->site_currency);	
						}	
					}
					else
					{
						$errors = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"detail"=>$errors,"status"=>2,"site_currency"=>$this->site_currency);
					}											
				}
				else
				{
					$message = array("message" => __('invalid_user'),"status"=>-1,"site_currency"=>$this->site_currency);	
				}
				echo json_encode($message);
				break;
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=completed_journey_monthwise&passenger_id=1&start=1&limit=5&month=12&year=2013			
			case 'completed_journey_monthwise':
				$array = $mobiledata;				
				if($array['passenger_id'] != null)
				{
					$validator = $this->trip_history_month_wise($array);
					if($validator->check()) 
					{
						$userid= $array['passenger_id'];
						$start = $array['start'];
						$limit = $array['limit'];	
						$month = $array['month'];	
						$year = $array['year'];	
						$device_type = $array['device_type']; // 1 Android , 2 - IOS
						//Getting from Passenger Model Directly
						$passengers = Model::factory('passengers');
						// Booktype 0 -> Flagger Ride, 1-> Strret Ride, 2-> All
						$booktype="2";
						// all records from 1 month	//
						$fromdate = $year.'-'.$month.'-'.'01';
						$todate = date('Y-m-t', strtotime($fromdate));
						$cmonth = $year.'-'.$month;
						/*if($device_type == 1)
						{
							$fromdate = $year.'-'.$month.'-'.$start;
							$todate = $year.'-'.$month.'-'.$limit;
						}
						else
						{
							$fromdate = $year.'-'.$month.'-'.'01';
							$todate = date('Y-m-t', strtotime($fromdate));				
						} */
						$arraydetails = array();
						$alldetails = array();
						//$perdayarray = array();
						$pagination = 1;
						//for($i = strtotime($fromdate); $i <= strtotime($todate); $i = strtotime('+1 Day', $i))
						/*for($i = strtotime($todate); $i >= strtotime($fromdate); $i = strtotime('-1 Day', $i))
						{ */
							
							/*$cdate = date("Y-m-d",$i);
							$req_date = date("Y-m-d",$i);*/
							//$passengers_all_compl = $api->get_passengertrips_byfrmdate($pagination,$booktype,$userid,1,'A','1',$start,$limit,$cmonth);
							$passengers_all_compl = $api->get_passengertrips_byfrmdate($pagination,$booktype,$userid,'1',$start,$limit,$cmonth);
							if(count($passengers_all_compl) > 0)
							{
								foreach($passengers_all_compl as $result)
								{
									//$perdayarray['trip_Date'] = date("Y-m-d",strtotime($result['createdate']));
									$arraydetails['trip_id'] = $result['trip_id'];
									$arraydetails['place'] = $result['place'];
									$arraydetails['booking_time'] = Commonfunction::getDateTimeFormat($result['pickup_time'],1);
									$arraydetails['pickup_time'] = ($result['actual_pickup_time'] != "0000-00-00 00:00:00") ? Commonfunction::getDateTimeFormat($result['actual_pickup_time'],1) : Commonfunction::getDateTimeFormat($result['pickup_time'],1);
									$arraydetails['fare'] = $result['fare'];
									if($result['is_split_trip'] == 1) {
										$arraydetails['fare'] = $result['paid_amount'] + $result['used_wallet_amount'];
									}									
									$arraydetails['pickup_latitude'] = $result['pickup_latitude'];
									$arraydetails['pickup_longitude'] = $result['pickup_longitude'];
									$arraydetails['drop_latitude'] = $result['drop_latitude'];
									$arraydetails['drop_longitude'] = $result['drop_longitude'];
									$arraydetails['notes_driver'] = $result['notes_driver'];
									$arraydetails['drivername'] = $result['drivername'];
									$arraydetails['drop_location'] = $result['drop_location'];
									$arraydetails['createdate'] = Commonfunction::getDateTimeFormat($result['createdate'],1);
									$arraydetails['profile_image'] = URL_BASE.'public/images/no_image109.png';
									if(!empty($result['profile_image']) && file_exists(DOCROOT.SITE_DRIVER_IMGPATH.$result['profile_image'])) {
										$arraydetails['profile_image'] = URL_BASE.SITE_DRIVER_IMGPATH.$result['profile_image'];
									}
									$arraydetails['taxi_no'] = $result['taxi_no'];
									$arraydetails['travel_status'] = $result['travel_status'];
									$arraydetails['payment_type'] = $result['payment_type'];
									$arraydetails['model_name'] = $result['model_name'];
									$arraydetails['driver_confirm'] = 1;
									$date = $result['pickup_time'];
									
									
									if(file_exists(DOCROOT.MOBILE_COMPLETE_TRIP_MAP_IMG_PATH.$result['trip_id'].".png")) {
										$mapurl = URL_BASE.MOBILE_COMPLETE_TRIP_MAP_IMG_PATH.$result['trip_id'].".png";
									} else {
										$path = $result['active_record'];
										$path = str_replace('],[', '|', $path);
										$path = str_replace(']', '', $path);
										$path = str_replace('[', '', $path);
										$path = explode('|',$path);$path = array_unique($path);
										include_once MODPATH."/email/vendor/polyline_encoder/encoder.php";
										$polylineEncoder = new PolylineEncoder();
										if(count(array_filter($path)) > 0)
										{
											foreach ($path as $values)
											{
											$values = explode(',',$values);
											$polylineEncoder->addPoint($values[0],$values[1]);
											$polylineEncoder->encodedString();
											}
										}
										$encodedString = $polylineEncoder->encodedString();
										
										$marker_end = $result['drop_latitude'].','.$result['drop_longitude'];
										$marker_start = $result['pickup_latitude'].','.$result['pickup_longitude'];
										$startMarker = URL_BASE.PUBLIC_IMAGES_FOLDER.'startMarker.png';
										$endMarker = URL_BASE.PUBLIC_IMAGES_FOLDER.'endMarker.png';
										//$startMarker = 'http://testtaxi.know3.com/public/images/startMarker.png';
										//$endMarker = 'http://testtaxi.know3.com/public/images/endMarker.png';
										if($marker_end != 0) {
											$mapurl = "https://maps.googleapis.com/maps/api/staticmap?size=640x270&zoom=13&maptype=roadmap&markers=icon:$startMarker%7C$marker_start&markers=icon:$endMarker%7C$marker_end&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
										} else {
											$mapurl = "https://maps.googleapis.com/maps/api/staticmap?size=640x270&zoom=13&maptype=roadmap&markers=icon:$startMarker%7C$marker_start&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
										}
										if(isset($mapurl) && $mapurl != "") {
											$file_path = DOCROOT.MOBILE_COMPLETE_TRIP_MAP_IMG_PATH.$result['trip_id'].".png";
											file_put_contents($file_path,@file_get_contents($mapurl));
											$mapurl = URL_BASE.MOBILE_COMPLETE_TRIP_MAP_IMG_PATH.$result['trip_id'].".png";
										}
									} 
									$arraydetails['map_image'] = $mapurl;
									$alldetails[] = $arraydetails;
								}								
							}
						//}
						
						if(count($alldetails) > 0)
						{
							$message = array("message" =>__('success'),"trip_details" => $alldetails,"status"=>1,"site_currency"=> $this->site_currency);
						}
						else
						{
							$message = array("message" => __('no_completed_data_month'),"status"=>0,"site_currency"=>$this->site_currency);	
						}						
					}
					else
					{
						$errors = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"detail"=>$errors,"status"=>2,"site_currency"=>$this->site_currency);
					}							
				}
				else
				{
					$message = array("message" => __('invalid_user'),"status"=>-1,"site_currency"=>$this->site_currency);	
				}
				echo json_encode($message);
				break;	
				
			//URL : http://192.168.1.88:1234/api/index/dGF4aV9YRlJJb1p0NjdxYTU5ZmlIRFl1OGJPQ0J2elRHQVYxZmY=?type=completed_journey&id=1&start=0&limit=5
			//Company URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=completed_journey&id=1&start=0&limit=5
			case 'completed_journey':
				$array = $mobiledata;
				if($array['id'] != '')
				{
					$userid= $array['id'];
					$start = $array['start'];
					$limit = $array['limit'];

					$check_result = $api->check_passenger_companydetails($array['id'],$default_companyid);
					if($check_result == 0)	
					{
						$message = array("message" => __('invalid_user'),"status"=>-1,"site_currency"=>$this->site_currency);
						echo json_encode($message);
						exit;;
					}
					
					//Getting from Passenger Model Directly
					$passengers = Model::factory('passengers');
					$passengers_all_compl = $api->get_passenger_log_details($userid,1,'A','1',$start,$limit,$default_companyid);
					
					//print_r($passengers_all_compl);
					
					if(count($passengers_all_compl) > 0)
					{
						$message = array("message" =>__('success'),"detail"=>$passengers_all_compl,"currency"=>$this->site_currency);
					}
					else
					{
						$message = array("message" => __('no_completed_data'),"status"=>0,"site_currency"=>$this->site_currency);	
					}						
						
				}
				else
				{
					$message = array("message" => __('invalid_user'),"status"=>-1,"site_currency"=>$this->site_currency);	
				}
				echo json_encode($message);
				break;	

			 // Cancelled Trip by Passenger
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=?type=cancelled_journey&id=1&start=0&limit=5&device_type=1
			case 'cancelled_journey':
			$cancel_array = $mobiledata;
				if($cancel_array['id'] != null)
				{
					$validator = $this->coming_cancel($array);					
					if($validator->check()) 
					{
						$userid= $cancel_array['id'];
						$start = $cancel_array['start'];
						$limit = $cancel_array['limit'];
						$device_type = $cancel_array['device_type'];

						$check_result = $api->check_passenger_companydetails($userid,$default_companyid);
						if($check_result == 0)	
						{
							$message = array("message" => __('invalid_user'),"status"=>-1);
							echo json_encode($message);
							exit;
						}
						if($device_type == 1)
						$pagination = 1;
						else
						$pagination = 0;
						$passengers_cancel = $api->get_passenger_cancelled_trip_details($default_companyid,$pagination,$userid,'','A','',$start,$limit);
								//print_r($passengers_cancel);	
						$trip_details = array();
						$i = 0;
						$alldetails = array();
						if(count($passengers_cancel) > 0)
						{
							foreach($passengers_cancel as $journey)
							{
									$driver_id = $journey['driver_id'];
									$payment_type = $journey['payment_type'];
									$driver_image = $journey['driver_image'];
									$trip_details['trip_id'] = $journey['passengers_log_id'];
									$trip_details['pickup_location'] = $journey['pickup_location'];
									$trip_details['pickup_latitude'] = $journey['pickup_latitude'];
									$trip_details['pickup_longitude'] = $journey['pickup_longitude'];
									$trip_details['drop_location'] = $journey['drop_location'];
									$trip_details['drop_latitude'] = $journey['drop_latitude'];
									$trip_details['drop_longitude'] = $journey['drop_longitude'];
									$trip_details['pickup_time'] = $journey['pickup_time'];
									$trip_details['time_to_reach_passen'] = $journey['time_to_reach_passen'];
									$trip_details['driver_name'] = $journey['driver_name'];								
									$trip_details['driver_id'] = $journey['driver_id'];							
									$trip_details['taxi_id'] = $journey['taxi_id'];
									$trip_details['taxi_number'] = $journey['taxi_no'];
									$trip_details['driver_phone'] = $journey['driver_phone'];
									$trip_details['passenger_name'] = $journey['passenger_name'];
									$trip_details['travel_status'] = $journey['travel_status'];
									$trip_details['amt'] = $journey['amt'];		
									$trip_details['job_ref'] = $journey['job_ref'];		
							$paymentname="";
							$paymentname_sql = $api->get_payment_name($journey['payment_type']);
							if(count($paymentname_sql) > 0)
							{
								$paymentname = $paymentname_sql[0]['pay_mod_name'];
							}
							$trip_details['payment_type'] = $paymentname;		
							/************************************Driver Image *******************************/					
								$driver_image = URL_BASE.SITE_DRIVER_IMGPATH.$driver_image;
								if(file_exists($driver_image) && $driver_image !='')
								{
								$driver_image = URL_BASE.SITE_DRIVER_IMGPATH.$driver_image;
								}else{
								$driver_image = URL_BASE."/public/images/noimages109.png";
								}		
								$trip_details['driver_image'] = $driver_image;			
							$alldetails[] = $trip_details;
							$i = $i+1;			
							}
								if(SMS == 1)
							{
								//$this->phone=$this->commonmodel->get_drivers_details($email,1);
								
								$message_details = $this->commonmodel->sms_message_by_title('trip_cancel');
								if(count($message_details) > 0) {
									//$to = isset($this->phone[0]['phone'])?$this->phone[0]['phone']:'';
									$to = $api->get_driver_phone_by_id($driver_id);
									$message = $message_details[0]['sms_description'];
									$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
									$this->commonmodel->send_sms($to,$message);
								}
								//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");								
							}																		
							//$message = $passengers_cancel;
							$message = array("message" => __('success'),"detail"=>$alldetails,"status"=>1);
						}
						else
						{
							$message = array("message" => __('no_data'),"status"=>0);	
						}	
					}
					else
					{
							$errors = $validator->errors('errors');	
							$message = array("message" => __('validation_error'),"detail"=>$errors,"status"=>2);							
					}					
						
				}
				else
				{
					$message = array("message" => __('invalid_user'),"status"=>-1);	
				}
				echo json_encode($message);
				break;					
				
				//URL : http://192.168.1.88:100/api/index/dGF4aV9hbGw=?type=coming_trips&id=1&start=0&limit=10&device_type=1
				case 'coming_trips':
				$passenger_list_array = $mobiledata;
				//Current Journey after driver confirmation //TN1013619352
				
					if($passenger_list_array['id'] != null)
					{
						$validator = $this->coming_cancel($passenger_list_array);					
						if($validator->check()) 
						{						
							$userid= $passenger_list_array['id'];
							$start = $passenger_list_array['start'];
							$limit = $passenger_list_array['limit'];	
							$device_type = $passenger_list_array['device_type'];	
							$check_result = $api->check_passenger_companydetails($passenger_list_array['id'],$default_companyid);
							if($check_result == 0)	
							{
								$message = array("message" => __('invalid_user'),"status"=>-1);
								echo json_encode($message);
								exit;
							}
							if($device_type == 1)
							$pagination = 1;
							else
							$pagination = 0;
							$passengers_current = $api->get_passenger_current_log_details($default_companyid,$pagination,$userid,'','A','0',$start,$limit);
							
							if(count($passengers_current) > 0)
							{
								//$message = $passengers_current;
								$message = array("message" => __('success'),"detail"=>$passengers_current,"status"=>1);
							}					
							else
							{
								$message = array("message" => __('no_data'),"status"=>0);	
							}	
						}
						else
						{
							$errors = $validator->errors('errors');	
							$message = array("message" => __('validation_error'),"detail"=>$errors,"status"=>2);							
						}												
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
					
				case 'booking_list':
					//Current Journey after driver confirmation //TN1013619352
					$array = $mobiledata;
					if($array['id'] != null)
					{
						$validator = $this->coming_cancel($array);
						if($validator->check()) 
						{
							$userid= $array['id'];
							$start = $array['start'];
							$limit = $array['limit'];
							$device_type = $array['device_type'];
							$check_result = $api->check_passenger_companydetails($array['id'],$default_companyid);
							if($check_result == 0)
							{
								$message = array("message" => __('invalid_user'),"status"=>-1);
								echo json_encode($message);
								exit;
							}
							if($device_type == 1)
							$pagination = 1;
							else
							$pagination = 0;
							$passengers_trips = array();
							$pending_bookings = $api->get_pending_bookings($default_companyid,$pagination,$userid,'','A','0',$start,$limit);
							foreach($pending_bookings as $key => $val)
							{
								$pending_bookings[$key]['driver_confirm']=true;
								switch($val['travel_status'])
								{
									case 1:
										$pending_bookings[$key]['travel_msg']="Fare Updated";
									break;
									case 2:
										$pending_bookings[$key]['travel_msg']="Inprogress";
									break;
									case 3:
										$pending_bookings[$key]['travel_msg']="Arrived";
									break;
									case 5:
										$pending_bookings[$key]['travel_msg']="Completed";
									break;
									case 9:
										$pending_bookings[$key]['travel_msg']="Confirmed";
									break;
									case 0:
										$pending_bookings[$key]['travel_msg']="Not Confirmed";
										$pending_bookings[$key]['driver_confirm']=false;
									break;
									default:
										$pending_bookings[$key]['travel_msg']="Cancelled";
									break;
								}
								//to get the pickup time with required date format
								$pending_bookings[$key]['pickup_time'] = Commonfunction::getDateTimeFormat($val['pickuptime'],3);
								$pending_bookings[$key]['profile_image'] = URL_BASE.'public/images/no_image109.png';
								if(!empty($val['profile_image']) && file_exists(DOCROOT.SITE_DRIVER_IMGPATH.$val['profile_image'])) {
									$pending_bookings[$key]['profile_image'] = URL_BASE.SITE_DRIVER_IMGPATH.$val['profile_image'];
								}
								
								$pending_bookings[$key]['waiting_fare'] = "0";
								
								if(file_exists(DOCROOT.MOBILE_PENDING_TRIP_MAP_IMG_PATH.$val['passengers_log_id'].".png")) {
									$mapurl = URL_BASE.MOBILE_PENDING_TRIP_MAP_IMG_PATH.$val['passengers_log_id'].".png";
								} else {									
									include_once MODPATH."/email/vendor/polyline_encoder/encoder.php";
									$polylineEncoder = new PolylineEncoder();
									$polylineEncoder->addPoint($val['pickup_latitude'],$val['pickup_longitude']);
									
									$marker_end = 0;
									if($val['drop_latitude'] != 0 && $val['drop_longitude'] != 0){
										$polylineEncoder->addPoint($val['drop_latitude'],$val['drop_longitude']);
										$marker_end = $val['drop_latitude'].','.$val['drop_longitude'];
									}
									$encodedString = $polylineEncoder->encodedString();
									$marker_start = $val['pickup_latitude'].','.$val['pickup_longitude'];
									$startMarker = URL_BASE.PUBLIC_IMAGES_FOLDER.'startMarker.png';
									$endMarker = URL_BASE.PUBLIC_IMAGES_FOLDER.'endMarker.png';
									//$startMarker = 'http://testtaxi.know3.com/public/images/startMarker.png';
									//$endMarker = 'http://testtaxi.know3.com/public/images/endMarker.png';
									if($marker_end != 0) {
										$mapurl = "https://maps.googleapis.com/maps/api/staticmap?size=640x270&zoom=13&maptype=roadmap&markers=icon:$startMarker%7C$marker_start&markers=icon:$endMarker%7C$marker_end&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
									} else {
										$mapurl = "https://maps.googleapis.com/maps/api/staticmap?size=640x270&zoom=13&maptype=roadmap&markers=icon:$startMarker%7C$marker_start&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
									}
									if(isset($mapurl) && $mapurl != "") {
										$file_path = DOCROOT.MOBILE_PENDING_TRIP_MAP_IMG_PATH.$val['passengers_log_id'].".png";
										file_put_contents($file_path,@file_get_contents($mapurl));
										$mapurl = URL_BASE.MOBILE_PENDING_TRIP_MAP_IMG_PATH.$val['passengers_log_id'].".png";
									}
								}
								$pending_bookings[$key]['map_image'] = $mapurl;
								$pending_bookings[$key]['trip_id']=(isset($val['passengers_log_id']))?$val['passengers_log_id']:"";
							}
							/*$past_bookings = $api->get_past_bookings($userid,1,'A','1',$start,$limit,$default_companyid);
							foreach($past_bookings as $pastkey=>$pastVal)
							{
								$past_bookings[$pastkey]['passenger_name'] = (!empty($pastVal['passenger_name'])) ? ucfirst($pastVal['passenger_name']) : '';
								//to get the pickup time with required date format
								$past_bookings[$pastkey]['pickuptime'] = Commonfunction::getDateTimeFormat($pastVal['pickuptime'],1);
								$past_bookings[$pastkey]['drop_time'] = Commonfunction::getDateTimeFormat($pastVal['drop_time'],1);
							} */
							$passengers_trips['pending_bookings']=$pending_bookings;
							//$passengers_trips['past_bookings']=$past_bookings;
							if(count($passengers_trips) > 0)
							{
								//$message = $passengers_current;
								$message = array("message" => __('success'),"detail"=>$passengers_trips,"status"=>1);
							}					
							else
							{
								$message = array("message" => __('no_data'),"status"=>0);
							}	
						}
						else
						{
							$errors = $validator->errors('errors');
							$message = array("message" => __('validation_error'),"detail"=>$errors,"status"=>2);							
						}												
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
					
				case 'driver_booking_list':
				//Current Journey after driver confirmation //TN1013619352
				$driver_list_array = $mobiledata;
					if($driver_list_array['driver_id'] != null)
					{
						$validator = $this->driver_coming_cancel($driver_list_array);
						if($validator->check())
						{
							$driver_id= $driver_list_array['driver_id'];
							$start = $driver_list_array['start'];
							$limit = $driver_list_array['limit'];	
							$device_type = $driver_list_array['device_type'];
							$request_type = $driver_list_array['request_type'];

							/*
							if($device_type == 1)
							$pagination = 1;
							else
							$pagination = 0;
							*/
							$pagination = 0;
							//$ongoing_journey = array();
							$driver_pending_bookings = array();
							$past_booking = array();
							if($request_type == 1) {
								/***********************Driver Upcoming******************************/
								$driver_pending_bookings = $api->driver_pending_bookings($driver_id,'R','A','2',$default_companyid);
								if(count($driver_pending_bookings) > 0)
								{
									foreach($driver_pending_bookings as $key => $journey)
									{
										$passenger_photo = isset($journey['passenger_profile_image'])?$journey['passenger_profile_image']:'';
										if((!empty($passenger_photo)) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_photo))
										{ 
											 $profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_photo; 
										}
										else
										{ 
											$profile_image = URL_BASE."public/images/no_image109.png";
										} 
										$driver_pending_bookings[$key]['profile_image']=$profile_image;
										$payment_type=isset($journey['payment_type'])?$journey['payment_type']:'';	
										switch($payment_type)
										{
											case 1:
											$driver_pending_bookings[$key]['payment_type']="Cash";
											break;
											case 2:
											$driver_pending_bookings[$key]['payment_type']="Credit Card";
											break;
											case 3:
											$driver_pending_bookings[$key]['payment_type']="New Card";
											break;
											case 5:
											$driver_pending_bookings[$key]['payment_type']="Wallet";
											break;
											default:
											$driver_pending_bookings[$key]['payment_type']="Uncard";
											break;
										}
										//to get the pickup time with required date format
										$driver_pending_bookings[$key]['pickup_time'] = Commonfunction::getDateTimeFormat($journey['pickup_time'],1);
										//map image for upcoming trips in driver app
										if(file_exists(DOCROOT.MOBILE_PENDING_TRIP_MAP_IMG_PATH.$journey['passengers_log_id'].".png")) {
											$mapurl = URL_BASE.MOBILE_PENDING_TRIP_MAP_IMG_PATH.$journey['passengers_log_id'].".png";
										} else {									
											include_once MODPATH."/email/vendor/polyline_encoder/encoder.php";
											$polylineEncoder = new PolylineEncoder();
											$polylineEncoder->addPoint($journey['pickup_latitude'],$journey['pickup_longitude']);
											$marker_end = 0;
											if($journey['drop_latitude'] != 0 && $journey['drop_longitude'] != 0){
												$polylineEncoder->addPoint($journey['drop_latitude'],$journey['drop_longitude']);
												$marker_end = $journey['drop_latitude'].','.$journey['drop_longitude'];
											}
											$marker_start = $journey['pickup_latitude'].','.$journey['pickup_longitude'];
											$encodedString = $polylineEncoder->encodedString();
											$startMarker = URL_BASE.PUBLIC_IMAGES_FOLDER.'startMarker.png';
											$endMarker = URL_BASE.PUBLIC_IMAGES_FOLDER.'endMarker.png';
											//$startMarker = 'http://testtaxi.know3.com/public/images/startMarker.png';
											//$endMarker = 'http://testtaxi.know3.com/public/images/endMarker.png';
											if($marker_end != 0) {
												$mapurl = "https://maps.googleapis.com/maps/api/staticmap?size=640x270&zoom=13&maptype=roadmap&markers=icon:$startMarker%7C$marker_start&markers=icon:$endMarker%7C$marker_end&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
											} else {
												$mapurl = "https://maps.googleapis.com/maps/api/staticmap?size=640x270&zoom=13&maptype=roadmap&markers=icon:$startMarker%7C$marker_start&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
											}
											
											if(isset($mapurl) && $mapurl != "") {
												$file_path = DOCROOT.MOBILE_PENDING_TRIP_MAP_IMG_PATH.$journey['passengers_log_id'].".png";
												file_put_contents($file_path,@file_get_contents($mapurl));
												$mapurl = URL_BASE.MOBILE_PENDING_TRIP_MAP_IMG_PATH.$journey['passengers_log_id'].".png";
											}
										}
										$driver_pending_bookings[$key]['map_image'] = $mapurl;
									}
								}
							} else {
								$booktype = 1;
								$past_booking = $api->driver_past_bookings($pagination,$booktype,$driver_id,'R','A','1',$start,$limit,$default_companyid);
								foreach($past_booking as $key => $journey)
								{
									$passenger_photo = isset($journey['profile_image'])?$journey['profile_image']:'';
									if((!empty($passenger_photo)) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_photo))
									{ 
										$profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_photo;
									}
									else
									{ 
										$profile_image = ($journey['bookby'] == 3) ? URL_BASE."public/".UPLOADS."/iOS/static_image/streetPickupFocus.png" : URL_BASE."public/images/no_image109.png";
									} 
									$past_booking[$key]['profile_image']=$profile_image;
									$payment_type=isset($journey['payment_type'])?$journey['payment_type']:'';
									switch($payment_type)
									{
										case 1:
										$past_booking[$key]['payment_type']="Cash";
										break;
										case 2:
										$past_booking[$key]['payment_type']="Credit Card";
										break;
										case 3:
										$past_booking[$key]['payment_type']="Uncard";
										break;
										case 5:
										$past_booking[$key]['payment_type']="Wallet";
										break;
										default:
										$past_booking[$key]['payment_type']="Uncard";
										break;
									}
									//to get passenger Name
									$past_booking[$key]['passenger_name'] = (!empty($journey['passenger_name'])) ? ucfirst($journey['passenger_name']) : '';
									//to get the pickup time with required date format
									$past_booking[$key]['pickup_time'] = Commonfunction::getDateTimeFormat($journey['pickup_time'],1);
									$past_booking[$key]['drop_time'] = Commonfunction::getDateTimeFormat($journey['drop_time'],1);
									//Map image
									if(file_exists(DOCROOT.MOBILE_COMPLETE_TRIP_MAP_IMG_PATH.$journey['passengers_log_id'].".png")) {
										$mapurl = URL_BASE.MOBILE_COMPLETE_TRIP_MAP_IMG_PATH.$journey['passengers_log_id'].".png";
									} else {
										$path = $journey['active_record'];
										$path = str_replace('],[', '|', $path);
										$path = str_replace(']', '', $path);
										$path = str_replace('[', '', $path);
										$path = explode('|',$path);$path = array_unique($path);
										include_once MODPATH."/email/vendor/polyline_encoder/encoder.php";
										$polylineEncoder = new PolylineEncoder();
										if(count(array_filter($path)) > 0)
										{
											foreach ($path as $values)
											{
											$values = explode(',',$values);
											$polylineEncoder->addPoint($values[0],$values[1]);
											$polylineEncoder->encodedString();
											}
										}
										$encodedString = $polylineEncoder->encodedString();
										
										$marker_end = $journey['drop_latitude'].','.$journey['drop_longitude'];
										$marker_start = $journey['pickup_latitude'].','.$journey['pickup_longitude'];
										$startMarker = URL_BASE.PUBLIC_IMAGES_FOLDER.'startMarker.png';
										$endMarker = URL_BASE.PUBLIC_IMAGES_FOLDER.'endMarker.png';
										//$startMarker = 'http://testtaxi.know3.com/public/images/startMarker.png';
										//$endMarker = 'http://testtaxi.know3.com/public/images/endMarker.png';
										if($marker_end != 0) {
											$mapurl = "https://maps.googleapis.com/maps/api/staticmap?size=640x270&zoom=13&maptype=roadmap&markers=icon:$startMarker%7C$marker_start&markers=icon:$endMarker%7C$marker_end&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
										} else {
											$mapurl = "https://maps.googleapis.com/maps/api/staticmap?size=640x270&zoom=13&maptype=roadmap&markers=icon:$startMarker%7C$marker_start&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
										}
										if(isset($mapurl) && $mapurl != "") {
											$file_path = DOCROOT.MOBILE_COMPLETE_TRIP_MAP_IMG_PATH.$journey['passengers_log_id'].".png";
											file_put_contents($file_path,@file_get_contents($mapurl));
											$mapurl = URL_BASE.MOBILE_COMPLETE_TRIP_MAP_IMG_PATH.$journey['passengers_log_id'].".png";
										}
									}
									$past_booking[$key]['map_image'] = $mapurl;
									$past_booking[$key]['distance_fare_km'] = ($journey["distance"] > 1) ? round($journey["amt"]/$journey["distance"],2) : $journey["amt"];
									$past_booking[$key]['vehicle_distance_fare'] = ($journey["distance"] > 1) ? round($past_booking[$key]['distance_fare_km']*$journey["distance"],2) : $journey["amt"];
									$time = explode(':', str_replace("Mins","",$journey["twaiting_hour"]));
									$secs = isset($time[0]) ? $time[0] : 0;
									$mins = isset($time[1]) ? $time[1] : 0;
									$waiting_time_per_hours = ($secs*3600) + ($mins*60)/60;
									//echo $waiting_time_per_hours;exit;
									$waiting_fare_per_hour = 0;
									if($journey["waiting_fare"] > 0 && $waiting_time_per_hours > 0) {
										$waiting_fare_per_hour = ($journey["waiting_fare"]*60)/$waiting_time_per_hours;
									}
									$past_booking[$key]['waiting_fare_per_hour'] = $waiting_fare_per_hour;
									$past_booking[$key]['final_amt'] = $journey["total"] - $journey["wallet"];
									unset($past_booking[$key]['active_record']);
								}
							}
							$detail = array("pending_booking"=>$driver_pending_bookings,"past_booking"=>$past_booking);
							$message = array("message" => __('success'),"detail"=>$detail,"status"=>1);						
						}
						else
						{
							$errors = $validator->errors('errors');	
							$message = array("message" => __('validation_error'),"detail"=>$errors,"status"=>2);							
						}												
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
					
			/************** Driver upcoming and ongoing details **************************/
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=?type=driver_coming_trips&driver_id=1&start=0&limit=2&device_type=1
			case 'driver_coming_trips':
				//Current Journey after driver confirmation //TN1013619352
				$driver_coming_list_array = $mobiledata;
					if($driver_coming_list_array['driver_id'] != null)
					{
						$validator = $this->driver_coming_cancel($driver_coming_list_array);					
						if($validator->check()) 
						{						
							$driver_id= $driver_coming_list_array['driver_id'];
							$start = $driver_coming_list_array['start'];
							$limit = $driver_coming_list_array['limit'];	
							$device_type = $driver_coming_list_array['device_type'];
							/*
							if($device_type == 1)
							$pagination = 1;
							else
							$pagination = 0;
							*/
							$pagination = 0;
							//$ongoing_journey = array();
							$driver_logs_prog = array();
							
							/*************** Driver Ongoing Journey ***************************/
							$driver_logs_progress = $api->get_driver_current_ongoigtrips($driver_id,'R','A','2',$default_companyid);
							//print_r($driver_logs_progress);
							//exit;
							$driver_logs = array();
							if(count($driver_logs_progress)>0)
							{ $i=0;
								$alldetails = array();
								//for($i=0;$i<count($driver_logs_progress);$i++)
								foreach($driver_logs_progress as $v)
								{
									if($v['bookby']==2 && ($v['travel_status']==9 || $v['travel_status']==3))
									{
										//echo "restrict";
										//$alldetails = __('no_ongoing_data');
										//$ongoing = 0;
									}
									else
									{
										$driver_logs['passenger_name'] = $v['passenger_name'];
										$driver_logs['passenger_phone'] = $v['passenger_phone'];
										if($v['profile_image']!="" && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.$v['profile_image'])){ 
											$profile_image = URL_BASE.PASS_IMG_IMGPATH.$v['profile_image']; 
										}else{ 
											$profile_image = URL_BASE."public/images/no_image109.png";
										}
										$driver_logs['profile_image'] = $profile_image;
										$driver_logs['passengers_log_id'] = $v['passengers_log_id'];
										$driver_logs['pickup_location'] = $v['pickup_location'];
										$driver_logs['drop_location'] = $v['drop_location'];
										$driver_logs['pickup_longitude'] = $v['pickup_longitude'];
										$driver_logs['pickup_latitude'] = $v['pickup_latitude'];
										$driver_logs['drop_latitude'] = $v['drop_latitude'];
										$driver_logs['drop_longitude'] = $v['drop_longitude'];
										$driver_logs['travel_status'] = $v['travel_status'];
										$driver_logs['notes'] = $v['notes'];
										$driver_logs['distance'] = $v['distance'];
										$driver_logs['waiting_hour'] = $v['waiting_hour'];
										$driver_logs['bookby'] = $v['bookby'];

										//$alldetails[] = $v;
										$alldetails[] = $driver_logs;
										//$ongoing = 1;
									}
									$i++;
									
								}
								$ongoing = 1; 
								if(empty($alldetails))
								{
									$alldetails = __('no_ongoing_data');
									$ongoing = 0;
								}
								$ongoing_journey = 	$alldetails;
								$ongoing_status = $ongoing;
								
								foreach($driver_logs_progress as $key => $journey)
								{
									$passenger_photo = $journey['profile_image'];			
									if((!empty($passenger_photo)) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_photo))
									{ 
										 $profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_photo; 
									}
									else
									{ 
										$profile_image = URL_BASE."public/images/no_image109.png";
									} 
									$driver_logs_progress[$key]['profile_image']=$profile_image;
								}

							}		
							else
							{
								$ongoing_journey = __('no_ongoing_data');
								$ongoing_status = 0;
							}							
							/***********************Driver Upcoming******************************/					
							$driver_upcoming = $api->get_driver_current_log_details($default_companyid,$pagination,$driver_id,'','A','0',$start,$limit);
							//print_r($driver_upcoming);
							
							if(count($driver_upcoming) > 0)
							{
								$upcoming_journey = $driver_upcoming;
								$upgoing_status = 1;
							}	
							else
							{
								$upcoming_journey = __('no_upcoming_data');
								$upgoing_status = 0;
							}				
							$detail = array("ongoing_journey"=>$ongoing_journey,"upcoming_journey"=>$upcoming_journey);
							$message = array("message" => __('success'),"detail"=>$detail,"ongoing_status"=>$ongoing_status,"upcoming_status"=>$upgoing_status);						
						}
						else
						{
							$errors = $validator->errors('errors');	
							$message = array("message" => __('validation_error'),"detail"=>$errors,"status"=>2);							
						}												
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
							
			//URL : api/?type=update_ratings_comments&pass_id=1&ratings=&comments=	
			case 'update_ratings_comments':
			$rating_array = $mobiledata;
					if(!empty($rating_array['pass_id']))
					{
						$validator = $this->update_ratings_comments_validation($rating_array);
						
						if($validator->check()) 
						{
							$pass_id= $rating_array['pass_id'];
							$ratings = $rating_array['ratings'];
							$comments = $rating_array['comments'];						
							$fav_driver_id = $api->savecomments($pass_id,$ratings,$comments);
							$setFavDriver = ($ratings < 4) ? 2 : 1;
							$msg = ($ratings < 4) ? __('rate_comment_updated') : __('rate_msg_with_set_favorite');
							$message = array("message" => $msg,"set_fav_driver" => $setFavDriver,"fav_driver_id" => $fav_driver_id,"status"=>1);
						}	
						else
						{							
							
							$errors = $validator->errors('errors');		
							$message = array("message" => __('validation_error'),"detail"=>$errors,"status"=>-2);	
						}																						
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
				/**
				 * Title : Favourite driver save functionality for a passenger
				 * Url : http://192.168.1.116:1027/mobileapi116/index/dGF4aV9hbGw=/?type=set_favourite_driver
				 * input : {"driver_id":"1474","passenger_id":"1941"}
				 * */
				case 'set_favourite_driver':
					if(!empty($mobiledata['passenger_id']) && !empty($mobiledata['driver_id'])) {
						$chkdriveradded = $api->chkDriverAdded($mobiledata['passenger_id'],$mobiledata['driver_id']);
						if($chkdriveradded == 0) {
							$api->saveFavouriteDriver($mobiledata['passenger_id'],$mobiledata['driver_id']);
							$message = array("message" => __('fav_driver_saved'),"status"=>1);
						} else {
							$message = array("message" => __('fav_driver_added_already'),"status"=>-1);	
						}
					}
					else
					{
						$message = array("message" => __('invalid_request'),"status"=>-1);	
					}
					echo json_encode($message);
				break;
					//URL : http://192.168.1.88:1009/api/index/?type=get_credit_card_details&passenger_id=58&card_type=P&default=yes
				case 'get_credit_card_details':
					$array = $mobiledata;
					$passenger_id = $array['passenger_id'];
					$default = $array['default'];
					$card_type = strtoupper($array['card_type']);
					if($array['passenger_id'] != null)
					{												
							$result = $api->get_creadit_card_details($passenger_id,$card_type,$default);
							if(count($result)>0)
							{
								$carddetails = array();
								if($default == 'yes')
								{									
									$plain_cardno = encrypt_decrypt('decrypt',$result[0]['creditcard_no']);
									$carddetails['creditcard_no'] = $plain_cardno;
									$carddetails['masked_creditcard_no'] = repeatx($plain_cardno,'X',4);
									$carddetails['expdatemonth'] = $result[0]['expdatemonth'];
									$carddetails['expdateyear'] = $result[0]['expdateyear'];
									$carddetails['creditcard_cvv'] = "";//$result[0]['creditcard_cvv'];
									$carddetails['masked_creditcard_cvv'] = "";//repeatx($result[0]['creditcard_cvv'],'X','All');		
									$carddetails['passenger_cardid'] = $result[0]['passenger_cardid'];
									$carddetails['card_type'] = $result[0]['card_type'];									
									$message = array("message" =>__('success'),"detail"=>$carddetails,"status"=>1);					
								}
								else
								{
									$i = 0;
									$alldetails = array();
									foreach($result as $value)
									{
										$plain_cardno = encrypt_decrypt('decrypt',$value['creditcard_no']);
										$carddetails['creditcard_no'] = $plain_cardno;
										$carddetails['masked_creditcard_no'] = repeatx($plain_cardno,'X',4);
										$carddetails['expdatemonth'] = $value['expdatemonth'];
										$carddetails['expdateyear'] = $value['expdateyear'];
										$carddetails['creditcard_cvv'] = "";//$value['creditcard_cvv'];
										$carddetails['masked_creditcard_cvv'] = "";//repeatx($value['creditcard_cvv'],'X','All');		
										$carddetails['default_card'] = $value['default_card'];
										$carddetails['passenger_cardid'] = $value['passenger_cardid'];										
										$carddetails['card_type'] = $value['card_type'];
										$alldetails[] = $carddetails;
										$i = $i+1;										
									}
									$message = array("message" =>__('success'),"detail"=>$alldetails,"status"=>1);	
								}								
							}
							else
							{
								$message = array("message" =>__('no_card'),"status"=>2);
							}
					}	
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}			
			echo json_encode($message);
			break;
			
			/** Credit Card delete option//
			 * URI : http://192.168.1.116:1013/mobileapi114/index/dGF4aV9hbGw=/?type=credit_card_delete
			 * Params : {"passenger_cardid":"1125","passenger_id":"123"}
			 * **/
			case 'credit_card_delete':
				if(!empty($mobiledata['passenger_cardid']) && !empty($mobiledata['passenger_id'])) {
					$favourite_details = $api->delete_credit_card($mobiledata['passenger_cardid'], $mobiledata['passenger_id']);
					if($favourite_details) {
						$message = array("message" => __('credit_card_deleted'),"status"=>1);
					} else {
						$message = array("message" =>__('invalid_card_id'),"status"=>2);
					}
				} else {
					$message = array("message" =>__('invalid_card_id'),"status"=>2);
				}
				echo json_encode($message);
			break;
			
			//URL : api/?type=update_driver_reply&pass_id=1&driver_reply=C
			
			case 'update_driver_reply':
			$update_driver_array = $mobiledata;
					if($update_driver_array['pass_id'] != null)
					{
						$pass_id= $update_driver_array['pass_id'];
						$driver_reply= $update_driver_array['driver_reply'];
						
						$update_array = array("driver_reply"=>$driver_reply);
						
						$api->update_table(PASSENGERS_LOG,$update_array,"passengers_log_id",$pass_id);
						$message = array("message" => __('get_another_taxi'),"status"=>1);
					}
					else
					{
						$message = array("message" =>  __('invalid_user'),"status"=>-1);
					}
					echo json_encode($message);
					break;
			
								
			/*END OF PASSENGER DETAILS*/
			
			/*START OF DRIVER DETAILS*/
			/*http://192.168.1.88:1000/api/index/wiOBAfPKdmAr544Tl1ayDEsUQN9Og3/?type=driver_profile&userid=6
			//URL : api/?type=driver_profile&userid=1*/
			case 'driver_profile':
				$driver_array = $mobiledata;
				$siteinfo_details = $api->siteinfo_details();
				if($driver_array['userid'] != null)
				{
					$check_driver_login_status = $this->is_login_status($driver_array['userid'],$default_companyid);
					if($check_driver_login_status == 1)
					{ 
						$result = $api->driver_profile($driver_array['userid']);
						if(count($result) >0)
						{
							$driver_pending_amount = 0; $driver_referral_wallet_pending_amount = 0;
							if(isset($result[0]['company_id'])) {
								$referral_pending_result = $api->driver_referral_pending_amount($driver_array['userid']);
								if(count($referral_pending_result) > 0) {
									$driver_referral_wallet_pending_amount = ($referral_pending_result[0]["driver_referral_wallet_pending_amount"]) ? $referral_pending_result[0]["driver_referral_wallet_pending_amount"] : 0;
								}
								$pending_result = $api->driver_withdraw_pending_amount($result[0]['company_id'],$driver_array['userid']);
								if(count($pending_result) > 0) {
									$driver_pending_amount = ($pending_result[0]["pending_amount"]) ? $pending_result[0]["pending_amount"] : 0;
								}
							}
							$country_code = (!empty($result[0]['country_code'])) ? trim($result[0]['country_code']).'-':'';
							$name = $result[0]['name'];
							$salutation = $result[0]['salutation'];
							$email = $result[0]['email'];
							$phone = $country_code.$result[0]['phone'];
							$profile_picture = $result[0]['profile_picture'];
							$address = $result[0]['address'];
							$driver_license_id = $result[0]['driver_license_id'];
							$lastname = $result[0]['lastname'];
							$bankname = $result[0]['bankname'];
							$bankaccount_no = $result[0]['bankaccount_no'];
							$taxi_no = $result[0]['taxi_no'];
							$mapping_startdate = Commonfunction::getDateTimeFormat($result[0]['mapping_startdate'],1);
							$mapping_enddate = Commonfunction::getDateTimeFormat($result[0]['mapping_enddate'],1);
							$model_name = $result[0]['model_name'];
							$driverWalletAmount = $result[0]['driver_wallet_amount'];
							$driverAvailableAmount = $result[0]['account_balance'];
							/************************************Driver Image *******************************/
							$main_image_path = $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DRIVER_IMGPATH.$profile_picture;
							$thumb_image_path = $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DRIVER_IMGPATH.'thumb_'.$profile_picture;
							if(file_exists($main_image_path) && ($profile_picture !='')) {
								$driver_main_image = URL_BASE.SITE_DRIVER_IMGPATH.$profile_picture;
							} else {
								$driver_main_image = URL_BASE."/public/images/noimages.png";
							}

							if(file_exists($thumb_image_path) && ($profile_picture !='')) {
								$driver_thumb_image = URL_BASE.SITE_DRIVER_IMGPATH.'thumb_'.$profile_picture;
							} else {
								$driver_thumb_image = URL_BASE."/public/images/noimages.png";
							}

							$dresult = $api->driver_ratings($driver_array['userid']);
							$totalrating = 5;
							if(count($dresult) > 0)
							{
								$overall_rating = 5; $i = 0; $trip_total_with_rate = 1;
								foreach($dresult as $comments)
								{
									if($comments['rating'] != 0)
									$trip_total_with_rate++;
									$rating = $comments['rating'];
									$overall_rating += $comments['rating'];
									$i++;
								}
								if($trip_total_with_rate!=0 && $overall_rating!=0){
									$totalrating = $overall_rating/$trip_total_with_rate;
								}
								$totalrating = round($totalrating);
							}
							$get_bal = Commonfunction::getDriverTripWallet($driver_array['userid']);
							$get_bal = (isset($get_bal['tot_amount']))?$get_bal['tot_amount']:0;
 							$company_det = $api->get_company_id($driver_array['userid']);
							$comp_id = ($company_det > 0) ? $company_det[0]['company_id'] : $default_companyid;
							$pending_trip_referral_wallet = $api->driver_withdraw_pending_amount($comp_id,$driver_array['userid']);
							$pending_trip_referral_wallet = (isset($pending_trip_referral_wallet[0]['pending_amount']))?$pending_trip_referral_wallet[0]['pending_amount']:0;
							$result = array(
								"salutation" => $salutation,
								"name" => $name,
								"lastname"=>$lastname,
								"bankname"=>$bankname,
								"bankaccount_no"=>$bankaccount_no,
								"email"=> $email,
								"phone"=>$phone,
								"main_image_path" => $driver_main_image,
								"thumb_image_path" => $driver_thumb_image,
								"address" => $address,
								"taxi_no" => $taxi_no,
								"taxi_map_from" => $mapping_startdate,
								"taxi_map_to" => $mapping_enddate,
								"taxi_model" => $model_name,
								"driver_license_id" => $driver_license_id,
								"driver_rating" => $totalrating,
								"driver_wallet_amount"=> (float)$driverWalletAmount,
								"driver_wallet_pending_amount"=> $driver_referral_wallet_pending_amount,
								"trip_amount"=> round($driverAvailableAmount,2),
								"trip_pending_amount"=> round($driver_pending_amount,2),
								"total_amount"=> round($driverAvailableAmount - $driver_pending_amount,2),
								"account_balance"=>round($driverAvailableAmount,2),
								"minimum_withdraw_amount"=>(float)$siteinfo_details[0]['driver_minimum_wallet_request'],
								"maximum_withdraw_amount"=>(float)$siteinfo_details[0]['driver_maximum_wallet_request'],
								"trip_referral_wallet"=>$get_bal,
								"pending_trip_referral_wallet"=>$pending_trip_referral_wallet,
							);
							$message = array("message" =>__('success'),"detail"=>$result,"status"=>1);
						}
						else
						{
							$message = array("message" => __('invalid_user_driver'),"status"=>0);
						}
					}
					else
					{
						$message = array("message" => __('driver_not_login'),"status"=>-1);
					}
				}
				else
				{
					$message = array("message" => __('invalid_user_driver'),"status"=>-1);
				}
				echo json_encode($message);
				break;

			//URL : http://192.168.1.88:1003/api/index/dGF4aV9hbGw=/?type=driver_login&phone=9999988888&password=a4321f23a0119191688621018a0b2b0b&device_id=dfff&device_token=4534534&device_type=2
			
			
			case 'driver_withdraw_request_list':
					$array = $mobiledata;
					$data = $api->driver_withdraw_request_list($array['driver_id']);				
					if(count($data)>0)
					{
						$new_array=array();
						foreach($data as $val)
						{
							$val['name']=(isset($val['name']) && ($val['name']) !="")?$val['name']:"";
							$val['driver_phone']=(isset($val['driver_phone']) && ($val['driver_phone']) !="")?$val['driver_phone']:"";
							$new_array[] = $val;
						}
						$message = array("message" => __('Transaction Details'),'detail'=>$new_array,"status"=>1);
					}
					else
					{
						$message = array("message" => __('no_data'),"status"=>0);
					}												
					echo json_encode($message);
					break;
			
			case 'driver_login':
					$array = $mobiledata;
					//print_r($array);
					if(!empty($array))
					{
						$array['company_id'] = $default_companyid;
						$array['user_type'] = 'D';
						$validator = $this->driver_login_validation($array);
						$org_fcm_token=isset($array['fcm_token'])?$array['fcm_token']:'';
						
						if($validator->check())
						{
							$phone_exist = $api->check_phone_people(urldecode($array['phone']),'D',$default_companyid);
							if($phone_exist == 0)
							{
								$message = array("message" =>  __('phone_not_exists'),"status"=> 2);
								echo json_encode($message);
								exit;
							}		
							else
							{
								$result = $api->driver_login(urldecode($array['phone']),urldecode($array['password']),$default_companyid);
								if(count($result) > 0)
								{
									//print_r($result); exit;
									//Checking the User Status
									$user_status = $result[0]['status'];
									$login_status = $result[0]['login_status'];
									$login_from = $result[0]['login_from'];
									$device_token = $result[0]['device_token'];
									$fcm_token = $result[0]['fcm_token'];
									$device_id = $result[0]['device_id'];
									$company_id = $result[0]['company_id'];
									$company_phone = '0780500111'; /*$result[0]['company_phone'];*/
									$driver_id = $result[0]['id'];
									$driver_first_login = $result[0]['driver_first_login'];
									$driver_details = $api->driver_profile($driver_id);
									//print_r($driver_details);exit;
									if($user_status == 'D' || $user_status == 'T')
									{
										$message = array("message" => __('account_deactivte'),"status"=> 0);
									}
									else if(($login_status == 'S') && ($login_from == 'D') && ($device_id != $array['device_id']))
									{
										$message = array("message" => __('already_login'),"status"=> 0);
									}					
									/*else if(($login_status == 'S') && ($login_from == 'D') && ($device_id == $array['device_id']))
									{
											//Else code
									}		
									else if(($login_status == 'S') && ($login_from == 'W'))
									{
									$message = array("message" => __('alteady_login_website'),"status"=> 0);
									}*/								
									else
									{
										$driver_status = 'F';
										$taxi_id = "";
										$getTaxiforDriver = $api->getTaxiforDriver($driver_id,$company_id);
										if(count($getTaxiforDriver) > 0 )
										{
											if(($login_status != 'S') && ($login_from != 'D') && ($device_id != $array['device_id']))
											{
												$update_array  = array("notification_setting"=>"1","login_from"=>"D","login_status"=>"S","device_id" => $array['device_id'],"device_token" => $array['device_token'],"device_type" => $array['device_type'],"notification_status"=>"1","fcm_token"=>$org_fcm_token);							
												// Need for update labong settings automatically
												$login_status_update = $api->update_driver_phone($update_array,$driver_id,$default_companyid);
											}
											//Enable Driver Shift status
											$driver_reply = $api->update_driver_shift_status($driver_id,'IN');
											$taxi_id = $getTaxiforDriver[0]['mapping_taxiid'];
											$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($company_id);
											$insert_array = array("driver_id" => $driver_id,
																	"taxi_id" 			=> $taxi_id,												
																	"shift_start" 	=> $company_all_currenttimestamp,
																	"shift_end"		=> "",
																	"reason"		=> "",
																	"createdate"		=> $this->currentdate,
																);
											//Inserting to Transaction Table 
											$transaction = $this->commonmodel->insert(DRIVERSHIFTSERVICE,$insert_array);
											//print_r($transaction);		
											$shiftupdate_id = $transaction[0];
											/***** Check whether new trips or payment waiting trips is availavble for the driver ********/
											$trip_id = $travel_status = "";
											$get_driver_trip_details = $api->get_driver_log_details($driver_id,$company_id);
											//print_r($get_driver_log_details);
											$driver_trip_count = count($get_driver_trip_details);//exit;
											if($driver_trip_count > 0)
											{												
												foreach($get_driver_trip_details as $details)
												{
													$trip_id = $details->passengers_log_id;
													$travel_status = $details->travel_status;
													$driver_status =  ($travel_status != '9') ?  'A' : $driver_status;
												}												
											}	
											/*************************************************************************************/
											$driver_details[0]["shiftupdate_id"]=$shiftupdate_id;
											$driver_details[0]["taxi_id"]=$taxi_id;
											$driver_details[0]["trip_id"]=$trip_id;
											$driver_details[0]["travel_status"]=$travel_status;
											$driver_details[0]["driver_status"]=$driver_status;
											$driver_details[0]["shift_status"]='IN';
											// Driver Statistics ********************/
											$driver_cancelled_trips = $api->get_driver_cancelled_trips($driver_id,$company_id);
											$driver_logs_rejected = $api->get_rejected_drivers($driver_id,$company_id);
											$rejected_trips = count($driver_logs_rejected);
											$driver_earnings = $api->get_driver_earnings_with_rating($driver_id,$company_id);
											$driver_tot_earnings = $api->get_driver_total_earnings($driver_id);
											$statistics = array();
											$total_trip = $trip_total_with_rate = $total_ratings = $today_earnings = $total_amount=0;
											foreach($driver_earnings as $stat){
											$total_trip++;
											$total_ratings += $stat['rating'];
											$total_amount += $stat['total_amount'];
											}
											$overall_trip = $total_trip + $rejected_trips+	$driver_cancelled_trips;
											$time_driven = $api->get_time_driven($driver_id,'R','A','1');
											$statistics = array( 
												"total_trip" => $overall_trip,
												"completed_trip" => $total_trip,
												"total_earnings" => round($driver_tot_earnings,2),
												"overall_rejected_trips" => $rejected_trips,
												"cancelled_trips" => $driver_cancelled_trips,
												"today_earnings"=>round($total_amount,2),											
												"shift_status"=>'IN',
												"time_driven"=>$time_driven,
												"status"=> 1
											  );
											$driver_details[0]["driver_first_login"]=$driver_first_login;
											if($driver_first_login == 1) {
												$updateDrArray  = array("driver_first_login"=>"2");
												$login_status_update = $this->commonmodel->update(PEOPLE,$updateDrArray,'id',$driver_id);
											}
											//$statistics = array();
											$driver_details[0]["company_phone"]=$company_phone;	
											$driver_details[0]["driver_statistics"]=$statistics;
											$driver_balance = (isset($result[0]['account_balance']) && $result[0]['account_balance'] !="")?$result[0]['account_balance']:0;
											$driver_details[0]["account_balance"]=round($driver_balance,2);	
											$alert_message =($driver_balance <=0)? "Your credits are too low. You will not receive trip request. Please recharge now!":"";
											$details = array("driver_details"=>$driver_details);
											/**************************************************/
											
											
											$details['account_balance']=round($driver_balance,2);
										    $details['alert_message']=$alert_message;

											/** Last Recent 3 trip start **/
											$trip_list = array(); $trip_array["driver_id"] = $driver_id;
											$trip_list = $api->get_recent_driver_trip_list($trip_array);
											if(count($trip_list) > 0){
												foreach($trip_list as $key => $val){
													$trip_list[$key]['drop_time'] = Commonfunction::getDateTimeFormat($val['drop_time'],1);
												}
											}
											/** Last Recent 3 trip end **/
											$message = array("message" => __('login_success'),"status"=> 1,"detail"=>$details,"recent_trip_list"=>$trip_list);		
										}
										else
										{
											$message = array("message" => __('taxi_not_assigned'),"status"=>-3);
										}												
									}
								}
								else
								{
									$message = array("message" => __('password_failed'),"status"=> -1);							
								}						
								echo json_encode($message);
							}
						}
						else
						{
							$errors = $validator->errors('errors');
							$message = array("message" => __('validation_error'),"status"=>-5,"detail"=>$errors);
							echo json_encode($message);
							exit;
						}
					}
					else
					{
							$message = array("message" => __('invalid_request'),"status"=>-6);	
							echo json_encode($message);						
					}						
										
					break;
					
					case 'driver_earnings':
						if(!empty($mobiledata['driver_id'])){
							$company_det =$api->get_company_id($mobiledata['driver_id']);
							if(count($company_det) > 0) {
								$default_companyid = $company_det[0]['company_id'];
								$check_driver_login_status = $this->is_login_status($mobiledata['driver_id'],$default_companyid);
								if($check_driver_login_status == 1)
								{
									$get_company_time_details = $api->get_company_time_details($default_companyid);
									$start_time = $get_company_time_details['start_time']; //Start time
									$end_time = $get_company_time_details['end_time']; //end time
									$current_time = $get_company_time_details['current_time'];
									$getTodayEarnings = $api->getTodayDriverEarnings($mobiledata['driver_id'],$start_time,$end_time);
									if(isset($getTodayEarnings[0]["total_amount"]) && $getTodayEarnings[0]["total_amount"] == null) {
										unset($getTodayEarnings[0]["total_amount"]);
										$getTodayEarnings[0]["total_amount"] = 0;
									}
									$getTodayEarnings[0]["total_amount"] = !empty($getTodayEarnings[0]["total_amount"]) ? round($getTodayEarnings[0]["total_amount"],2) : 0;
									$getWeeklyReport = $api->getWeeklyWiseEarnings($mobiledata['driver_id'],$current_time);
									$message = array("message" => __('success'),"today_earnings"=>$getTodayEarnings,"weekly_earnings"=>$getWeeklyReport,"status"=>1);
								} else {
									$message = array("message" => __('driver_not_login'),"status"=>-1);
								}
							} else {
								$message = array("message" => __('invalid_user'),"status"=>-1);
							}
						} else {
							$message = array("message" => __('invalid_request'),"status"=>-1);
						}
						echo json_encode($message);
					break;
		//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=edit_driver_profile&driver_id=3&email=senthilkumar.a@ndot.in&phone=9999988888&salutation=Mr&firstname=senthil&lastname=kumar&password=e10adc3949ba59abbe56e057f20f883e&org_password=123456&profile_picture=&bankname=&bankaccount_no=
		
					case 'edit_driver_profile':					
					$d_personal_array = $mobiledata;
					//print_r($d_personal_array);
					//exit;
					if(!empty($d_personal_array))
					{
							$driver_id = $d_personal_array['driver_id'];
							//print_r($array);
							//exit;
							if($d_personal_array["driver_id"] != null)
							{
								$validator = $this->edit_passenger_profile_validation($d_personal_array);						
								if($validator->check())
								{
									$d_email = urldecode($d_personal_array['email']);
									$d_phone = urldecode($d_personal_array['phone']);
									$password = urldecode($d_personal_array['password']);					
									$bankname = urldecode($d_personal_array['bankname']);
									$bankaccount_no = urldecode($d_personal_array['bankaccount_no']);
									$email_exist = $api->edit_check_email_people($d_email,$driver_id);
								    $phone_exist = $api->edit_check_phone_people($d_phone,$driver_id);
									if($email_exist > 0)
									{
										$message = array("message" => __('email_exists'),"status"=> 0);
										//echo json_encode($message);
									}
									else if($phone_exist > 0)
									{
										$message = array("message" => __('phone_exists'),"status"=> 2);
										//echo json_encode($message);
									}
									else
									{			
											if($d_personal_array['profile_picture'] != NULL)
											{
												/* Profile Update */
												$imgdata = base64_decode($d_personal_array['profile_picture']);
												
												$f = finfo_open();
												$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
												//echo '<img src="data:image/jpg;base64,' . $d_personal_array['profile_picture'] . '" />';
												//print_r($mime_type);exit;
												$mime_type = explode('/',$mime_type);
												
												$mime_type = $mime_type[1];
													
												$img = imagecreatefromstring($imgdata); 
												//print_r($img);
												//exit;
												if($img != false)
												{                   
													$result = $api->driver_profile($d_personal_array['driver_id'],$default_companyid);
													if(count($result) >0)
													{
														$profile_picture = $result[0]['profile_picture'];
														$thumb_image = 'thumb_'.$profile_picture;
														$main_image_path = $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DRIVER_IMGPATH.$profile_picture;
														$thumb_image_path = $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DRIVER_IMGPATH.'thumb_'.$profile_picture;
														if(file_exists($main_image_path) &&($profile_picture != ""))
														{
															unlink($main_image_path);
														}
														if(file_exists($thumb_image_path) && ($thumb_image != ""))
														{
															unlink($thumb_image_path);
														}
													}											//unlink($filename);
													$image_name = uniqid().'.'.$mime_type;
													$thumb_image_name = 'thumb_'.$image_name;
													$image_url = DOCROOT.SITE_DRIVER_IMGPATH.'/'.$image_name;                    								
													//header('Content-Type: image/jpeg');					
													$image_path = DOCROOT.SITE_DRIVER_IMGPATH.$image_name;  
													imagejpeg($img,$image_url);
													imagedestroy($img);
													chmod($image_path,0777);
													$d_image = Image::factory($image_path);
													$path11=DOCROOT.SITE_DRIVER_IMGPATH;
													//Commonfunction::imageresize($d_image,PASS_IMG_WIDTH, PASS_IMG_HEIGHT,$path11,$image_name,90);
													Commonfunction::imageoriginalsize($d_image,$path11,$image_name,90);
													
													$path12=$thumb_image_name;
													Commonfunction::imageresize($d_image,PASS_THUMBIMG_WIDTH, PASS_THUMBIMG_HEIGHT,$path11,$thumb_image_name,90);
													//Commonfunction::imageoriginalsize($d_image,$path11,$thumb_image_name,90);
													if($password != "")
													{
													$update_array = array(	
													"id"=>$d_personal_array['driver_id'],						
													"salutation"=> urldecode($d_personal_array['salutation']),
													"name" => urldecode($d_personal_array['firstname']),
													"lastname" => urldecode($d_personal_array['lastname']),
													"email" => $d_email,
													//"phone" => $d_phone,
													"password" => md5($password),
													"org_password" => $password,
													"profile_picture" => $image_name);
													}
													else
													{
													$update_array = array(	
													"id"=> urldecode($d_personal_array['driver_id']),						
													"salutation"=> urldecode($d_personal_array['salutation']),
													"name" => urldecode($d_personal_array['firstname']),
													"lastname" => urldecode($d_personal_array['lastname']),
													"email" => $d_email,
													//"phone" => $d_phone,
													"profile_picture" => $image_name);
													}
													$bank_update_array = array(
													"id"=>$d_personal_array['driver_id'],
													"bankname" => $bankname,
													"bankaccount_no" => $bankaccount_no);
													$message = $api->edit_driver_profile($update_array,$default_companyid);
													$update_bank = $api->edit_company_profile($bank_update_array);
													//$message = $api->edit_company_profile($update_array);
													
												//chmod($image_path, 0777);                    
												}
												else
												{
													$message = array("message" => __('image_not_upload'),"status"=>4);								
												}
												
											}
											else
											{
													if($password != "")
													{
													$update_array = array(	
													"id"=> $d_personal_array['driver_id'],						
													"salutation"=> urldecode($d_personal_array['salutation']),
													"name" => urldecode($d_personal_array['firstname']),
													"lastname" => urldecode($d_personal_array['lastname']),
													"email" => $d_email,
													//"phone" => $d_phone,
													"password" => md5($password),
													"org_password" => $password);
													}
													else
													{
													$update_array = array(	
													"id"=>$d_personal_array['driver_id'],						
													"salutation"=>urldecode($d_personal_array['salutation']),
													"name" => urldecode($d_personal_array['firstname']),
													"lastname" => urldecode($d_personal_array['lastname']),
													"email" => $d_email);//,"phone" => $d_phone
													}
													$bank_update_array = array(
													"id"=>$d_personal_array['driver_id'],
													"bankname" => $bankname,
													"bankaccount_no" => $bankaccount_no);
													$message = $api->edit_driver_profile($update_array,$default_companyid);
													$update_bank = $api->edit_company_profile($bank_update_array);
											}
												/*****************************************/
																				
												if($message == 0)
												{
													$message = array("message" => __('profile_updated'),"status"=>1);	
												}	
												else
												{
													$message = array("message" => __('try_again'),"status"=>1);	
												}																				
											}				
									}
								else
								{							
									$errors = $validator->errors('errors');	
									$message = array("message" => __('validation_error'),"status"=>-5,"detail"=>$errors);		
								}
							}
							else
							{
								$message = array("message" => __('invalid_user_driver'),"status"=>-1);	
							}
					}
					else
					{
						$message = array("message" => __('invalid_request'),"status"=>-1);	
					}
					
					echo json_encode($message);
					break;
					
			/*URL : api?type=chg_password_driver&id=7&old_password=a4541576840a7a08f5331f84491d915d&new_password=e10adc3949ba59abbe56e057f20f883e&confirm_password=e10adc3949ba59abbe56e057f20f883e&org_new_password=123456
			*/
			case 'chg_password_driver':
			$driver_chg_password = $mobiledata;
					if($driver_chg_password['id'] != null)
					{
						$validator = $this->chg_password_passenger_validation($driver_chg_password);
						
						if($validator->check())
						{
							//array_shift($array);
							//array_shift($array);
							$message = $api->chg_password_passenger($driver_chg_password,$default_companyid,'D');	
							
							//{-1 : confirm password must be the same as new password , -2 : Old Password is In Correct: -3: Invalid User,1:Password Changed Successfully	}
							
							switch($message){
								case -1 :
									$message = array("message" => __('confirm_new_same'),"status"=>-1);	
									break;
								case -2 :
									$message = array("message" => __('old_pass_incorrect'),"status"=>-2);
									break;
								case -3 :
									$message = array("message" => __('invalid_user'),"status"=>-3);
									break;
								case 1 :
									$message = array("message" => __('password_changed'),"status"=>1);	
									break;
								case -4 :
									$message = array("message" => __('old_new_pass_same'),"status"=>-4);	
									break;
								}
							
						}
						else
						{							
							$message = $validator->errors('errors');			
						}
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>0);	
					}
					echo json_encode($message);
					break;
			//URL: http://192.168.1.88:1020/api/index/dGF4aV9hbGw=/?type=getdriver_update&passenger_tripid=703
			case 'getdriver_update':
						ignore_user_abort(true);
						$array = $mobiledata;
						$message = array();
						$trip_id = $array["passenger_tripid"];						
						$notification_time = $this->notification_time;			
						//exit;									
						if($notification_time != 0 ){ $timeoutseconds = $notification_time;}else{$timeoutseconds = 15;}
						$timeout = $this->continuous_request_time;//$timeoutseconds; // timeout in seconds
						$microseconds = $timeout*1000000; //Seconds to microseconds 1 second = 1000000 
						$flag = 0;
						$now = time();
						//exit;
						$search_flag=0;						
						if((int)$trip_id != "") 
						{					
							//echo 'as';								
								$i = 0;		
								while((time() - $now) < $timeout)
								{	
									$driver_status = $api->get_request_status($trip_id);	
									//print_r($driver_status);
									$driver_status_count=count($driver_status);
									
									if($driver_status_count >0)
									{
										$req_count=$driver_status_count*$timeoutseconds;
										//$microseconds = $req_count*1000000; //Seconds to microseconds 1 second = 1000000
										//echo $req_count;exit;
										$driver_reply = $driver_status[0]['status'];
										$trip_type = $driver_status[0]['trip_type'];//get booking type 1-Favourite booking, 0-Normal Booking
										$selected_driver_id = $driver_status[0]['selected_driver'];
										$available_drivers = explode(',',$driver_status[0]['total_drivers']);
										$rejected_timeout_drivers = explode(',',$driver_status[0]['rejected_timeout_drivers']);	
										$comp_result = array_diff($available_drivers, $rejected_timeout_drivers);
										
										$timeout=count($available_drivers)*25+20;
										if($timeout < $this->continuous_request_time)
										{
											$timeout=$this->continuous_request_time;
										}
										$microseconds=$timeout*1000000;
										//to get drivers company timestamp
										$company_det =$api->get_company_id($selected_driver_id);
										if(count($company_det)>0){
											
											$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($company_det[0]['company_id']);
										}
										//condition to check driver not updated for above 30seconds if it is means we should change the request to next driver
										$driver_not_updated = $api->check_driver_not_updated($selected_driver_id,$company_all_currenttimestamp);
										//$time_difference=time()-strtotime($driver_not_updated);
										$time_difference = strtotime($company_all_currenttimestamp) - strtotime($driver_not_updated);
										if($time_difference > 25 && count($comp_result) != 0 && $driver_reply != '4') {
											$get_request_dets=$api->check_new_request_tripid("","",$trip_id,$selected_driver_id,$company_all_currenttimestamp,"");
										}
										//echo count($comp_result);
										if(count($comp_result) == 0)
										{
											$driver_reply  = 5;
										}

										//exit;
										if(!empty($driver_reply))
										{
											if($driver_reply == '3') 
											{
												$message = array("message" => __("request_confirmed_passenger"),"trip_id"=>$trip_id,"status"=>1);
												echo json_encode($message);
												exit;
											}
											elseif($driver_reply == '4')
											{
												if($trip_type == 1) {
													$message = array("message" => __("fav_driver_not_available"),"status"=>4);
												} else {
													$message = array("message" => __("driver_busy"),"status"=>2);
												}
												echo json_encode($message);
												exit;
											}
											elseif($driver_reply == '5')
											{
												if($trip_type == 1) {
													$message = array("message" => __("fav_driver_not_available"),"status"=>4);
												} else {
													$message = array("message" => __("driver_busy"),"status"=>2);
												}
												echo json_encode($message);
												exit;
											}
											else 
											{				
												$message = array("message" => __('try_again'),"status"=>0);
											}
											//echo json_encode($message);
											//exit;
										}
										usleep(5000000);	
												
										$i = $i+5000000;
										// echo $i;
										if($i == $microseconds)
										{
												$update_trip_array  = array("status"=>'4');
												$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$trip_id);
												if($trip_type == 1) {
													$message = array("message" => __("fav_driver_not_available"),"status"=>4);
												} else {
													$message = array("message" => __("driver_busy"),"status"=>2);
												}
												echo json_encode($message);
												exit;											
										}							
									}
									else
									{
										$message = array("message" => __('try_again'),"status"=>0);	
										exit;
									}										
								}
																													
						}
						else
						{
							$message = array("message" => __('validation_error'),"status"=>0);	
						}
				echo json_encode($message);
				break;
            //URL : http://192.168.1.88:1020/api/index/dGF4aV9hbGw=/?type=getdriver_reply&passenger_tripid=346					
			case 'getdriver_reply':
				$array = $mobiledata;
				if($array['passenger_tripid'] != null)
				{
					$passenger_tripid = $array["passenger_tripid"];
					$get_passenger_log_det = $api->get_trip_detail_only($passenger_tripid);
					if(count($get_passenger_log_det) > 0)															    		
					{
						//print_r($get_passenger_log_det);
						$driver_reply = $get_passenger_log_det[0]->driver_reply;
						if($driver_reply == 'A')
						{
							$detail = array("trip_id"=>$passenger_tripid,"driverdetails"=>"");
							$message = array("message" => __("request_confirmed_passenger"),"detail"=>$detail,"status"=>1);
						}
						else
						{
							$change_driver_status = $api->change_driver_status($passenger_tripid,'C');
							$update_trip_array  = array("status"=>'4');
							$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$passenger_tripid);		
							$message = array("message" => __("request_canceled_passenger"),"status"=>3);
						}
					}
					else
					{
						$message = array("message" => __('invalid_trip'),"status"=>-1);	
					}
				}
				else
				{
					$message = array("message" => __('try_again'),"status"=>0);	
				}
				echo json_encode($message);
				break;

            //URL : http://192.168.1.88:1020/api/index/dGF4aV9hbGw=/?type=getpassenger_update&trip_id=627&request_type=0					
			case 'getpassenger_update':
				$array = $mobiledata;
				$check_driver_status = $api->check_passenger_login_status($array['passenger_id']);
				if($check_driver_status != "A")
				{
					$msg = array("message" => __('account_blocked'),"status"=>109);	
					echo json_encode($msg);
					exit;
				}				
				$validator = $this->getpassenger_update_validation($array);						
				if($validator->check())
				{
					$trip_id = isset($array["trip_id"])? $array["trip_id"]:'';
					$passenger_id = $array["passenger_id"];
					$request_type = $array['request_type'];
					$new_drop_lat = isset($array['drop_lat']) ? $array['drop_lat'] : 0;
					$new_drop_long = isset($array['drop_long']) ? $array['drop_long'] : 0;
					$drop_location_name = isset($array['drop_location_name']) ? urldecode($array['drop_location_name']) : "";

					if($request_type == 0)
					{
						$secondaryPassNotify = 0;
						$arrived_display = $tripstart_display = $trip_complete_display = $tripfare_update_display = $driver_cancel_display = 0;
						
						//** update the drop latitude and longitude when the passenger chenged ahile in trip **//
						if(!empty($trip_id) && !empty($new_drop_lat) && !empty($new_drop_long)) {
							$update_drop_array  = array("notification_status"=>'22',"drop_latitude" => $new_drop_lat,"drop_longitude" => $new_drop_long,"drop_location" => $drop_location_name);
							$updateDrop = $api->update_table(PASSENGERS_LOG,$update_drop_array,'passengers_log_id',$trip_id);
						}
						
						$amt="";$pickup="";
						$get_passenger_log_det = $api->get_request_detail($passenger_id,$trip_id);
						if(count($get_passenger_log_det) > 0)
						{
							//print_r($get_passenger_log_det); exit;
							$driver_reply = $get_passenger_log_det[0]->driver_reply;
							$travel_status = $get_passenger_log_det[0]->travel_status;
							$driver_id = $get_passenger_log_det[0]->driver_id;
							$transId = $get_passenger_log_det[0]->job_ref;
							$farePercent = $get_passenger_log_det[0]->fare_percentage;
							$amt = round($get_passenger_log_det[0]->amt,2);
							$splittedAmt = ($amt * $farePercent)/100;
							$pickup_location = $get_passenger_log_det[0]->pickup_location;
							$actual_pickup_time = $get_passenger_log_det[0]->actual_pickup_time;
							$drop_time = $get_passenger_log_det[0]->drop_time;
							$notification_status = $get_passenger_log_det[0]->notification_status;
							$primary_passenger = $get_passenger_log_det[0]->primary_passenger;
							$tripfare = round($get_passenger_log_det[0]->tripfare,2);
							$base_fare = round($get_passenger_log_det[0]->base_fare,2);
							$minutes_fare = round($get_passenger_log_det[0]->minutes_fare,2);
							$waiting_fare = round($get_passenger_log_det[0]->waiting_fare,2);
							$nightfare = round($get_passenger_log_det[0]->nightfare,2);
							$eveningfare = round($get_passenger_log_det[0]->eveningfare,2);
							$company_tax = round($get_passenger_log_det[0]->company_tax,2);
							$passenger_discount = $get_passenger_log_det[0]->passenger_discount;
							$used_wallet_amount = round($get_passenger_log_det[0]->used_wallet_amount,2);
							$cancel_datetime = $get_passenger_log_det[0]->cancel_datetime;
							$cancel_status = $get_passenger_log_det[0]->cancel_status;
							$payment_type = ($get_passenger_log_det[0]->payment_type == 1) ? __('cash'):(($get_passenger_log_det[0]->payment_type == 5) ? __('wallet') : __('card'));
							$isSplit_fare = (int)$get_passenger_log_det[0]->splitTrip;//0->Normal Trip, 1->Split Trip
							/** secondary passengers approval status **/
							$splitApproveArr = array();
							if($primary_passenger == $passenger_id && $isSplit_fare == 1){
								$splitApproveArr = $api->getSplitFareStatus($trip_id);
								if(count($splitApproveArr) > 1){
									foreach($splitApproveArr as $splkey=>$splits){
										if((!empty($splits['profile_image'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.$splits['profile_image'])){ 
											$profile_image = URL_BASE.PASS_IMG_IMGPATH.$splits['profile_image']; 
										}
										else{ 
											$profile_image = URL_BASE."public/images/no_image109.png";
										}
										$splitApproveArr[$splkey]['profile_image'] = $profile_image;
									}
								}
							}
							/************** Driver Location ***************************/
							$driver_latitute = $driver_longtitute = '0.0';
							$current_driver_status = $api->get_driver_current_status($driver_id);
							if(count($current_driver_status)>0)
							{
								$trip_status = $current_driver_status[0]->status;
								$driver_latitute = $current_driver_status[0]->latitude;
								$driver_longtitute = $current_driver_status[0]->longitude;
							}				
							/**********************************************************/
							
							if(($driver_reply == 'A') && ($travel_status == 9))
							{
								$detail = array("trip_id"=>$trip_id,"driverdetails"=>"");
								$message = array("message" => __("request_confirmed_passenger"),"detail"=>$detail,"isSplit_fare"=>$isSplit_fare,"splitfaredetail"=>$splitApproveArr,"driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"status"=>1);
							}
							elseif(($driver_reply == 'A') && ($travel_status == 8))
							{
								$dispatcher_cancel_display = ($notification_status != 8) ?  1 : 0;
								$message = array("message" => __("dispatcher_trip_cancelled"),"detail"=>"","driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"status"=>10,"display"=>$dispatcher_cancel_display);
								$update_trip_array  = array("notification_status"=>'8');
								//$result = $api->update_table(PASSENGERS_LOG,$update_trip_array,'passengers_log_id',$trip_id);
								$result = $api->update_split_log_table(P_SPLIT_FARE,$update_trip_array,'friends_p_id',$passenger_id,'trip_id',$trip_id);
							}
							elseif(($driver_reply == 'C') && ($travel_status == 6))
							{
								$message = array("message" => __("trip_cancel"),"detail"=>"","driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"status"=>7);
							}
							elseif(($driver_reply == 'C') && ($travel_status == 9))
							{

								if(strtotime($cancel_datetime) > strtotime("now") && $cancel_status == 1)
								{
									$driver_cancel_display = ($notification_status != 9) ?  1 : 0;
									$message = array("message" => __("driver_cancel_new_driver"),"detail"=>"","driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"status"=>99,"display"=>$driver_cancel_display);
									$update_trip_array  = array("notification_status"=>'9');
									//$logresult = $api->update_table(PASSENGERS_LOG,$update_trip_array,'passengers_log_id',$trip_id);
									$result = $api->update_table(P_SPLIT_FARE,$update_trip_array,'friends_p_id',$passenger_id);

									$cronbooking_model = Model::factory('cronbooking');
									$next_driver = $cronbooking_model->cron_autodispatch($trip_id);
									
								}
								else
								{
									$driver_cancel_display = ($notification_status != 5) ?  1 : 0;
									$message = array("message" => __("driver_cancel_after_confirm"),"detail"=>"","driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"status"=>8,"display"=>$driver_cancel_display);
									$update_trip_array  = array("notification_status"=>'5');
									//$logresult = $api->update_table(PASSENGERS_LOG,$update_trip_array,'passengers_log_id',$trip_id);
									$result = $api->update_table(P_SPLIT_FARE,$update_trip_array,'friends_p_id',$passenger_id);

								}

								
							}
							elseif($travel_status == 7)
							{

								if(strtotime($cancel_datetime) > strtotime("now") && $cancel_status == 1)
								{
									$driver_cancel_display = ($notification_status != 9) ?  1 : 0;
									$message = array("message" => __("driver_cancel_new_driver"),"detail"=>"","driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"status"=>99,"display"=>$driver_cancel_display);
									$update_trip_array  = array("notification_status"=>'9');
									//$logresult = $api->update_table(PASSENGERS_LOG,$update_trip_array,'passengers_log_id',$trip_id);
									$result = $api->update_table(P_SPLIT_FARE,$update_trip_array,'friends_p_id',$passenger_id);

									$cronbooking_model = Model::factory('cronbooking');
									$next_driver = $cronbooking_model->cron_autodispatch($trip_id);
									
								}
								elseif( $cancel_status == 1)
								{
									$driver_cancel_display = ($notification_status != 5) ?  1 : 0;
									$message = array("message" => __("driver_cancel_after_confirm"),"detail"=>"","driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"status"=>8,"display"=>$driver_cancel_display);
									$update_trip_array  = array("notification_status"=>'5');
									//$logresult = $api->update_table(PASSENGERS_LOG,$update_trip_array,'passengers_log_id',$trip_id);
									$result = $api->update_table(P_SPLIT_FARE,$update_trip_array,'friends_p_id',$passenger_id);

								}

								
							}
							elseif(($driver_reply == 'A') && ($travel_status == 3))
							{
								$arrived_display = ($notification_status != 1) ?  1 : 0;
								$message = array("message"=>__('passenger_on_board'),"isSplit_fare"=>$isSplit_fare,"splitfaredetail"=>$splitApproveArr,"trip_id"=>$trip_id,"driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"status"=>2,"display"=>$arrived_display);
								//$update_trip_array  = array("notification_status"=>'1');
								//$logresult = $api->update_table(PASSENGERS_LOG,$update_trip_array,'passengers_log_id',$trip_id);
								$update_trip_array_split  = array("notification_status"=>'1');
								$result = $api->update_split_log_table(P_SPLIT_FARE,$update_trip_array_split,'friends_p_id',$passenger_id,'trip_id',$trip_id);
							}
							elseif(($driver_reply == 'A') && ($travel_status == 2))
							{
								$tripstart_display = ($notification_status != 2) ?  1 : 0;
								$actual_pickup_time = $this->commonmodel->getcompany_all_currenttimestamp($default_companyid);
								//$change_driver_status = $passengers->change_driver_status($passenger_tripid,'C');
								//$update_trip_array  = array("status"=>'4');
								//$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$passenger_tripid);		
								$message = array("message" =>__('journey_started'),"isSplit_fare"=>$isSplit_fare,"splitfaredetail"=>$splitApproveArr,"pickup_time"=>$actual_pickup_time,"trip_id"=>$trip_id,"driver_status"=>$trip_status,"driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"status"=>3,"display"=>$tripstart_display);
								$update_trip_array_split  = array("notification_status"=>'2');
								$result = $api->update_table(P_SPLIT_FARE,$update_trip_array_split,'friends_p_id',$passenger_id);	
							}
							elseif(($driver_reply == 'A') && ($travel_status == 5))
							{
								$trip_complete_display = ($notification_status != 3) ?  1 : 0;
								$message = array("message"=>__('trip_completed'),"driver_status"=>$trip_status,"driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"status"=>4,"display"=>$trip_complete_display);
								$update_trip_array  = array("notification_status"=>'3');
								//$logresult = $api->update_table(PASSENGERS_LOG,$update_trip_array,'passengers_log_id',$trip_id);
								$update_trip_array_split  = array("notification_status"=>'3');
								$result = $api->update_split_log_table(P_SPLIT_FARE,$update_trip_array_split,'friends_p_id',$passenger_id,'trip_id',$trip_id);
							}
							elseif(($driver_reply == 'A') && ($travel_status == 1) && $transId != 0)
							{
									//$subtot = $tripfare+$waiting_fare+$nightfare+$eveningfare;
									$promotion = ($splittedAmt * $passenger_discount) / 100;
									$promotion = round($promotion,2);
									
									$interval  = abs(strtotime($drop_time) - strtotime($actual_pickup_time));
									$minutes   = round($interval / 60);
									
								$tripfare_update_display = ($notification_status != 4) ?  1 : 0;
								$message = array("message" => __('trip_fare_updated'),"fare" => $splittedAmt,"trip_id"=>$trip_id,"pickup" => $pickup_location, "status"=>5,"display"=>$tripfare_update_display,"driver_status"=>$trip_status,"driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"payment_type"=>$payment_type,"base_fare"=>$base_fare,"waiting_fare"=>$waiting_fare,"nightfare"=>$nightfare,"eveningfare"=>$eveningfare,"paid_amount"=>$splittedAmt,"promotion"=>$promotion,"tax"=>$company_tax,"used_wallet_amount"=>$used_wallet_amount,"minutes_traveled"=>$minutes,"minutes_fare"=>$minutes_fare);
								$update_trip_array  = array("notification_status"=>'4');
								//$logresult = $api->update_table(PASSENGERS_LOG,$update_trip_array,'passengers_log_id',$trip_id);
								$result = $api->update_split_log_table(P_SPLIT_FARE,$update_trip_array,'friends_p_id',$passenger_id,'trip_id',$trip_id);
							}
							elseif(($driver_reply == 'A') && ($travel_status == 4) && $primary_passenger != $passenger_id)
							{
								$tripCancel_display = ($notification_status != 9) ?  1 : 0;
								//$message = array("message" => __('trip_cancelled_passenger'),"driver_status"=>$trip_status,"driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute, "status"=>9);
								$update_trip_array  = array("notification_status"=>'9');	
								$message = array("message" => __('trip_cancel_by_primary'),"driver_status"=>$trip_status,"driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute, "status"=>9,"display"=>$tripCancel_display);
								$result = $api->update_split_log_table(P_SPLIT_FARE,$update_trip_array,'friends_p_id',$passenger_id,'trip_id',$trip_id);
							}									
							else
							{
								$message = array("message"=>__('trip_not_started'),"driver_status"=>$trip_status,"driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"status"=>6);
							}
							
						}
						else
						{
							$message = array("message" => __('invalid_trip'),"status"=>-1);	
						}
					}
					elseif($request_type == 1)
					{
						$get_driver_request = $api->get_driver_request($trip_id);
						if(count($get_driver_request) >0)
						{
							//print_r($get_passenger_log_det);
							$driver_reply = $get_driver_request[0]['status'];
							$trip_type = $get_driver_request[0]['trip_type'];//get booking type 1-Favourite booking, 0-Normal Booking
							$available_drivers = explode(',',$get_driver_request[0]['total_drivers']);
							$rejected_timeout_drivers = explode(',',$get_driver_request[0]['rejected_timeout_drivers']);	
							$comp_result = array_diff($available_drivers, $rejected_timeout_drivers);	

							//echo count($comp_result);
							if(count($comp_result) == 0)
							{
								$driver_reply  = 5;
							}
							if($driver_reply == '3')
							{
								$detail = array("trip_id"=>$trip_id,"driverdetails"=>"");
								$message = array("message" => __("request_confirmed_passenger"),"detail"=>$detail,"status"=>1);
							}
							elseif($driver_reply == '4')
							{
								$message = array("message" => __("trip_cancel"),"detail"=>"","status"=>7);
							}
							elseif($driver_reply == '5')
							{
								if($trip_type == 1) {
									$message = array("message" => __("fav_driver_not_available"),"status"=>4);
								} else {
									$message = array("message" => __("driver_busy"),"status"=>2);
								}
								echo json_encode($message);
								exit;
							}
							else
							{
								$message = array("message"=>__('trip_not_started'),"status"=>6);
							}
						}
						else
						{
							$message = array("message" => __('invalid_trip'),"status"=>-1);	
						}
					}
					else
					{
						$message = array("message" => __('No Trips '),"status"=>-1);
					}
				}
				else
				{
						$errors = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"status"=>-5,"detail"=>$errors);
				}
				echo json_encode($message);
				break;
			//URL : http://192.168.1.88:1020/api/index/dGF4aV9hbGw=/?type=gettriprequest_status&trip_id=627
			case 'gettriprequest_status':
				$array = $mobiledata;
				
				if($array['trip_id'] != null)
				{
					$trip_id = $array["trip_id"];
					$amount="";$pickup="";
					
					$get_driver_request = $api->get_driver_request($trip_id);
					if($get_driver_request != 0)
					{
						//print_r($get_passenger_log_det);
						$driver_reply = $get_driver_request[0]->status;

						if($driver_reply == '3')
						{
							$detail = array("trip_id"=>$trip_id,"driverdetails"=>"");
							$message = array("message" => __("request_confirmed_passenger"),"detail"=>$detail,"status"=>1);
						}
						elseif($driver_reply == '4')
						{
							$message = array("message" => __("trip_cancel"),"detail"=>"","status"=>7);
						}					
						else
						{
							$message = array("message"=>__('trip_not_started'),"status"=>6);
						}
					}
					else
					{
						$message = array("message" => __('invalid_trip'),"status"=>-1);
					}
				}
				else
				{
					$message = array("message" => __('trip_id_req'),"status"=>0);	
				}
				echo json_encode($message);
				break;								
								
			//For Driver Cancell the trip
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=driver_status_update&driver_id=35&latitude=11&longitude=76.7997&status=A&trip_id=1216
			//Push Notifications in Driver Table
			case 'driver_status_update':
			$driver_status_array = $mobiledata;
			$act_pickup_location = isset($driver_status_array['actual_pickup_location']) ? 	urldecode($driver_status_array['actual_pickup_location']) : '';
					if($driver_status_array['driver_id'] != null)
					{
						$check_driver_login_status = $this->is_login_status($driver_status_array['driver_id'],$default_companyid);
						if($check_driver_login_status == 1)
						{ 
							$driver_model = Model::factory('driver');
							$current_driver_status = $driver_model->get_driver_current_status($driver_status_array['driver_id']);
							if(count($current_driver_status) > 0)
							{
										//print_r($current_driver_status);
										//array_shift($driver_status_array);
										//array_shift($driver_status_array);
										$trip_details = array();
										$passengers_log_id = $driver_status_array['trip_id'];
										$update_driver_arrary  = array(
										"latitude" => $driver_status_array['latitude'],
										"longitude" => $driver_status_array['longitude'],
										"status" => strtoupper($driver_status_array['status']));						
										if($current_driver_status[0]->status != 'A')
										{								
											if(($driver_status_array['status'] == 'A') && ($passengers_log_id != null))
											{
											$get_passenger_log_details = $api->get_passenger_log_detail($passengers_log_id);
											//print_r($get_passenger_log_details);exit;
											foreach($get_passenger_log_details as $values)
											{
													$current_location = $values->current_location;	
													$pickup_latitude = $values->pickup_latitude;
													$pickup_longitude = $values->pickup_longitude;			
													$drop_location = $values->drop_location;	
													$drop_latitude= $values->drop_latitude;
													$drop_longitude = $values->drop_longitude;
													$driver_name = $values->driver_name;															
													$p_device_type = $values->passenger_device_type;
													$p_device_token  = $values->passenger_device_token;	
													$actual_pickup_time  = $values->actual_pickup_time;
													$travel_status = $values->travel_status;
													$driver_reply = $values->driver_reply;
													$notes = $values->notes_driver;
											}
											/********** Check whther the Trip is alreadt cancelled by the passenger **********/
											if(($driver_reply == 'A') && ($travel_status == 4))
											{
												$msg = array("message" => __("trip_cancelled_passenger"),"detail"=>"","status"=>7);
												echo json_encode($msg);
												exit;
											}
											/*********************************************************************************/
											
												/** update journey inprogress in Passenger log table when driver start the journey**/
												$company_det =$api->get_company_id($driver_status_array['driver_id']);
												$compId = (count($company_det) > 0) ? $company_det[0]['company_id'] : $default_companyid;
												$actual_pickup_time = $this->commonmodel->getcompany_all_currenttimestamp($compId);
												$travel_status = 2;
												$act_pickup_location=$api->getaddress($driver_status_array['latitude'],$driver_status_array['longitude']);
												   if($act_pickup_location == false)
												   //if(empty($act_pickup_location))
												   {
														$act_pickup_location = $current_location;
												   }
												$act_pic_lat = ($driver_status_array['latitude'] != 0) ? $driver_status_array['latitude'] : $pickup_latitude;
												$act_pic_long = ($driver_status_array['longitude'] != 0) ? $driver_status_array['longitude'] : $pickup_longitude;

												$update_passenger_log_array = array('travel_status' => $travel_status,'actual_pickup_time'=>$actual_pickup_time,'current_location'=>$act_pickup_location,'pickup_latitude'=>$act_pic_lat,'pickup_longitude'=>$act_pic_long);

												$result = $api->update_table(PASSENGERS_LOG,$update_passenger_log_array,'passengers_log_id',$passengers_log_id);
												/** Passenger log table update end **/
												/*************** Update arrival in driver request table ******************/
												$update_trip_array  = array("status"=>'6');
												$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$passengers_log_id);
												/*************************************************************************/	
												if(($driver_status_array['latitude'] != 0) &&($driver_status_array['longitude'] != 0))
												{
													$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_status_array['driver_id']);
												}
												$trip_details = array("pickup_latitude"=>$driver_status_array['latitude'],"pickup_longitude"=>$driver_status_array['longitude'],"pickup_location"=>$act_pickup_location,"drop_latitude"=>$drop_latitude,"drop_longitude"=>$drop_longitude,"drop_location"=>$drop_location,"notes"=>$notes);
												$message = array("message" => __('driver_location_update'),"status"=>1,"detail"=>$trip_details);
												$push_message = array("message" =>__('journey_started'),"pickup_time"=>$actual_pickup_time,"trip_id"=>$passengers_log_id,"status"=>3);
												//print_r($push_message);
												//exit;
												//$p_send_notification = $api->send_passenger_mobile_pushnotification($p_device_token,$p_device_type,$push_message,$this->customer_google_api);
											}	
											elseif(($driver_status_array['status'] == 'A') && ($passengers_log_id == null))
											{
												$message = array("message" => __('invalid_trip_id'),"status"=>-1,"detail"=>$trip_details);
											}
											else
											{
												if(($driver_status_array['latitude'] != 0) &&($driver_status_array['longitude'] != 0))
												{
													$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_status_array['driver_id']);	
												}
												$message = array("message" => __('driver_location_update'),"status"=>1);
											}
										}
										else
										{
											$update_driver_arrary  = array(
											"latitude" => $driver_status_array['latitude'],
											"longitude" => $driver_status_array['longitude'],
											"status" => strtoupper($driver_status_array['status']));	
											//print_r($update_driver_arrary);
											if(($driver_status_array['latitude'] != 0 ) &&($driver_status_array['longitude'] != 0))
											{
												$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_status_array['driver_id']);	
											}
											$message = array("message" => __('already_trip'),"status"=>-1);
										}
								}
								else
								{									
									$insert_array = array(
									"driver_id" => $driver_status_array['driver_id'],
									"latitude"		=> $driver_status_array['latitude'],
									"longitude"		=> $driver_status_array['longitude'],
									"status"			=> 'F',
									"shift_status" => 'OUT');									
									if(($driver_status_array['latitude'] != 0) &&($driver_status_array['longitude'] != 0))
									{
										$transaction = $this->commonmodel->insert(DRIVER,$insert_array);
									}
									$message = array("message" => __('driver_location_update'),"status"=>1);
								}
						}
						else
						{
							$message = array("message" => __('driver_not_login'),"status"=>-1);	
						}													
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);
					}
					echo json_encode($message);
					break;
					
			//URL : http://192.168.1.88:1020/api/index/dGF4aV9KcUJRWjgwVjRJajc1RXhrSXFwaXpSUHA5Umd3eGI=?type=driver_reply&pass_logid=1138&driver_id=37&taxi_id=3&company_id=1&driver_reply=R&field=Sample Comments for rejection&flag=1
			case 'driver_reply':
			$driver_reply_array = $mobiledata;
			//print_r($driver_reply_array);exit;
					if($driver_reply_array['pass_logid'] != null)
					{
						$api_model = Model::factory(MOBILEAPI_107);
						$pass_logid = $driver_reply_array['pass_logid'];
						$driver_reply = $driver_reply_array['driver_reply'];
						$driver_id = $driver_reply_array['driver_id'];
						$taxi_id = $driver_reply_array['taxi_id'];
						$company_id = $driver_reply_array['company_id'];
						$field = $driver_reply_array['field'];
						$flag = $driver_reply_array['flag'];

						if($driver_reply == 'A'){$travel_status = 9;}elseif($driver_reply == 'R'){$travel_status=10;}else{$travel_status=9;}
						$driver_statistics=array();
						$result = $api_model->update_driverreply_status($pass_logid,$driver_id,$taxi_id,$company_id,$driver_reply,$travel_status,$field,$flag,$default_companyid);
						
						if($result == 1)
						{
							if($driver_reply == 'A')
							{
								/********* Update the status in driver request table **************/								
								$update_trip_array  = array("status"=>'3');
								$update_result = $api_model->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$pass_logid);	
								/********** Update the Driver table he goes Busy status ****************/
								$update_driver_array  = array("status"=>'B');
								$update_driver_result = $api_model->update_table(DRIVER,$update_driver_array,'driver_id',$driver_id);
								//** Split fare push notification section **//
								$spltPassDetails = $api->getSplitPassengersDetails($pass_logid);
								if(count($spltPassDetails) > 0) {
									$primary_passenger_name = '';
									$primaryPassImage = '';
									foreach($spltPassDetails as $passengerDets){
										if($primary_passenger_name == '')
											$primary_passenger_name = $passengerDets['name'];
											
										if($primaryPassImage == '') {
											if(!empty($passengerDets['profile_image']) && file_exists(URL_BASE.PASS_IMG_IMGPATH.$passengerDets['profile_image'])) {
												$primaryPassImage = URL_BASE.PASS_IMG_IMGPATH.$passengerDets['profile_image'];
											} else {
												$primaryPassImage = URL_BASE."public/images/no_image109.png";
											}
										}
										
										$pushMessage  = array("message"=>"You have a split fare request","trip_id"=>$pass_logid, "pickup_location"=>$passengerDets['current_location'], "drop_location"=>$passengerDets['drop_location'], "passenger_name"=>$primary_passenger_name, "primary_passenger_profile"=>$primaryPassImage, "total_fare"=>$passengerDets['total_fare'], "split_fare"=>$passengerDets['split_fare']);
										 if($passengerDets['primary_pass_id'] != $passengerDets['passenger_id']) {
											$api->send_pushnotification($passengerDets['device_token'],$passengerDets['device_type'],$pushMessage,$this->customer_google_api);
										 }
									}
								}
								/***************** Function to send the sms ***************************/	
								$laterBookings = $api_model->get_booking_details($pass_logid);
								if(count($laterBookings) > 0) {
									//** Email Section Starts **//
									
									$subject = __('later_booking_confirm_subjest');
									$name = $laterBookings[0]['name'];
									$message = __('later_booking_confirm_message');
									$message = str_replace("##booking_key##",$pass_logid,$message);
									$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$name,REPLACE_SUBJECT=>$subject,REPLACE_MESSAGE=>$message,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_COMPANYDOMAIN=>$this->domain_name,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
									//$message = $this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'laterbooking_confirm_message.html',$replace_variables);
									if($this->lang!='en'){
										if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/laterbooking_confirm_message-'.$this->lang.'.html')){
											$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/laterbooking_confirm_message-'.$this->lang.'.html',$replace_variables);
										}else{
											$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'laterbooking_confirm_message.html',$replace_variables);
										}
									}else{
										$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'laterbooking_confirm_message.html',$replace_variables);
									}
									//echo $message;exit;
									$to = $laterBookings[0]['email'];
									$from = $this->siteemail;
									$redirect = "no";	
									if($to != '') {
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
									//** Email Section Ends **//
									//** SMS Section Starts **//
									if(SMS == 1)
									{
										$message_details = $this->commonmodel->sms_message_by_title('booking_confirmed_sms');
										if(count($message_details) > 0) {
											$to = $laterBookings[0]['passenger_phone'];
											$message = (count($message_details)) ? $message_details[0]['sms_description'] : '';
											$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
											$message = str_replace("##booking_key##",$pass_logid,$message);
											$result = $this->commonmodel->send_sms($to,$message);
										}
									}
									//** SMS Section Ends **//
									//** Driver's and passenger's orginal phone number inserted for call masking  **//
									$twiliocall=$api->mobileNumberUpdateForMasking($pass_logid,$laterBookings[0]['driver_id'],$laterBookings[0]['passengers_id'],$laterBookings[0]['driver_phone'],$laterBookings[0]['passenger_phone']);
								}
								
								
							}
							$message = __('request_confirmed');	
							$push_msg = __('driver_confirm_push');
							$push_status = 1;
							$response_status = 1;							
							//$delete_pass_log_temp =$api->delete_passengers_log_temp($pass_logid);
						}
						else if($result == 2)		
						{	
							/********** Update the Driver table he goes Busy status ****************/
								$update_driver_array  = array("status"=>'F');
								$update_driver_result = $api_model->update_table(DRIVER,$update_driver_array,'driver_id',$driver_id);		
							/**************************************************************************/							
							$message = __('request_rejected');
							$push_msg = __('request_rejected_passenger');
							$push_status = 6;
							$response_status = 2;
						}else if($result == 3)		
						{
							//Driver Statistics Functionality Start
							/*
							$driver_model = Model::factory('driver');
							$driver_logs_rejected = $api->get_rejected_drivers($driver_id,$default_companyid);	
							$rejected_trips = count($driver_logs_rejected);	
							$driver_earnings = $driver_model->get_driver_earnings($driver_id);
							$driver_comments = $api->get_driver_comments($driver_id,'',$default_companyid);	
							$today_goal = $amount_left = $today_earnings = 0;
							$goal_detail = $api->get_goal_details($driver_id,'R','A','1');
							if(count($goal_detail)>0)
							{
								$today_earnings = $goal_detail[0]['acheive_amt'];
							}
							$statistics = array();
							$total_trip = $trip_total_with_rate = $total_ratings = 0;
							foreach($driver_comments as $stat){
								$total_trip++;
								$total_ratings += $stat['rating'];
								if($stat['rating'] != 0)
									$trip_total_with_rate++;
							}
							$time_driven = $api->get_time_driven($driver_id,'R','A','1');
							

								$drivername = "";
								$notification_setting = "";
								$driver_statistics = array( 
									"drivername"=>$drivername,
									"total_trip" => $total_trip,
									"total_earnings" => round($driver_earnings[0]['total_amount'],2),
									"overall_rejected_trips" => $rejected_trips,
									"today_earnings"=>round($today_earnings,2),											
									"shift_status"=>'IN',
									"time_driven"=>$time_driven,
								);  */
								
								/***************** Function to send the sms ***************************/	
								$laterBookings = $api_model->get_booking_details($pass_logid);
								if(count($laterBookings) > 0) {
									//** Email Section Starts **//
									
									$subject = __('booking_cancel_subject');
									$name = $laterBookings[0]['name'];
									$message = __('booking_cancel_message');
									$message = str_replace("##booking_key##",$pass_logid,$message);
									$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$name,REPLACE_SUBJECT=>$subject,REPLACE_MESSAGE=>$message,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_COMPANYDOMAIN=>$this->domain_name,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
									//$message = $this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'laterbooking_confirm_message.html',$replace_variables);
									if($this->lang!='en'){
									if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/laterbooking_confirm_message-'.$this->lang.'.html')){
										$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/laterbooking_confirm_message-'.$this->lang.'.html',$replace_variables);
									}else{
										$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'laterbooking_confirm_message.html',$replace_variables);
									}
									}else{
										$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'laterbooking_confirm_message.html',$replace_variables);
									}
									//echo $message;exit;
									$to = $laterBookings[0]['email'];
									$from = $this->siteemail;
									$redirect = "no";
									if($to != '') {
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
									//** Email Section Ends **//
									//** SMS Section Starts **//
									if(SMS == 1)
									{
										$message_details = $this->commonmodel->sms_message_by_title('booking_cancelled_sms');
										if(count($message_details) > 0) {
											$to = $laterBookings[0]['passenger_phone'];
											$message = (count($message_details)) ? $message_details[0]['sms_description'] : '';
											$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
											$message = str_replace("##booking_key##",$pass_logid,$message);
											$result = $this->commonmodel->send_sms($to,$message);
										}
									}
									//** SMS Section Ends **//
								}
								
								// Driver Statistics ********************/
								$driver_cancelled_trips = $api->get_driver_cancelled_trips($driver_id,$company_id);
								$driver_logs_rejected = $api->get_rejected_drivers($driver_id,$company_id);
								$rejected_trips = count($driver_logs_rejected);
								$driver_earnings = $api->get_driver_earnings_with_rating($driver_id,$company_id);
								$driver_tot_earnings = $api->get_driver_total_earnings($driver_id);
								$statistics = array();
								$total_trip = $today_earnings = $total_amount=0;
																
								foreach($driver_earnings as $stat){
								$total_trip++;
								$total_amount += $stat['total_amount'];
								}
								$overall_trip = $total_trip + $rejected_trips + $driver_cancelled_trips;
								$time_driven = $api->get_time_driven($driver_id,'R','A','1');
								$driver_statistics = array(
									"total_trip" => $overall_trip,
									"completed_trip" => $total_trip,
									"total_earnings" => round($driver_tot_earnings,2),
									"overall_rejected_trips" => $rejected_trips,
									"cancelled_trips" => $driver_cancelled_trips,
									"today_earnings"=>round($total_amount,2),
									"shift_status"=>'IN',
									"time_driven"=>$time_driven,
									"status"=> 1
								  );
							
							//print_r($driver_statistics);exit;
							//Driver Statistics Functionality End
							/********** Update the Driver table he goes Busy status ****************/
							$update_driver_array  = array("status"=>'F');
							$update_driver_result = $api_model->update_table(DRIVER,$update_driver_array,'driver_id',$driver_id);		
							/*************** Update in driver request table ******************/
							$update_trip_array  = array("status"=>'9');
							$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$pass_logid);		
							/*************************************************************************/
							$message = __('trip_cancelled_driver');
							$push_msg = __('driver_cancel_after_confirm');
							$push_status = 7;
							$response_status = 3;
							/*$message = array("message" => __('trip_fare_updated'),"detail"=>$detail,"driver_statistics"=>$driver_statistics,"status"=>1);*/
						}
						else if($result == 4)
						{
						$push_msg = $message = __('trip_already_cancel_rejected');
						$push_status = 8;
						$response_status = 4;
						}
						else if($result == 5){
							$push_msg = $message = __('trip_already_confirm');
							$push_status = 9;
							$response_status = 5;
						}
						else if($result == 6){
							$push_msg = $message = __('trip_already_rejected');
							$push_status = 10;
							$response_status =6;
						}
						else if($result == 7){
							$push_msg = $message = __('trip_cancel');
							$push_status = 11;
							$response_status = 7;
						} else if($result == 10){
							$push_msg = $message = __('trip already confirm to other driver');
							$push_status = 12;
							$response_status = 8;
						}
						else {
							$message = __('trip_cancel_timeout');
							$push_msg = __('trip_cancel_timeout');
							$push_status = 12;
							$response_status = 8;
						}
						//echo $push_status;

							$phone_no = '';
							$device_token = '';
							$driver_name = $p_device_token = $phone_no = $driver_phone = $p_device_type="";

						    $latitude = $longitude="";
						    $taxi_details = "";

							//free sms url with the arguments
							if((SMS == 1) && ($driver_phone !=''))
							{
							$message_details = $this->commonmodel->sms_message('3');
							$to = $driver_phone;
							$message_temp = $message_details[0]['sms_description'];
							$sms_message = str_replace("##booking_key##",$pass_logid,$message_temp);
					
							//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($sms_message)."&senderid=NAJIK&recipient=$to");
							}
										
								$totalrating = "";																
								$driverdetails = array();
								$trip_detail = array();
								$driverdetails=$api->get_passenger_log_detail_reply($pass_logid);
								foreach($driverdetails as $values)
								{
									if($values->profile_image)
											{
												$img = URL_BASE.'public/'.UPLOADS.'/passenger/thumb_'.$values->profile_image;
											}else{
												$img = URL_BASE."/public/images/noimages109.png";
											} 
											$values->profile_image=$img;
									//$driverdetails=$values;
								}

								if($result == 10){
									$pass_logid = "";
								}
								$detail = array("trip_id"=>$pass_logid,"driverdetails"=>$driverdetails,"driver_statistics"=>$driver_statistics);
								if($response_status == 1)
								{
									$msg = array("message" => $message,"status" => $response_status,"detail"=>$detail);	
								}
								else
								{
									$msg = array("message" => $message,"status" => $response_status,"driver_statistics"=>$driver_statistics);	
								}
								if($push_status == 1 || $push_status == 6 || $push_status == 7)
								{														
									if($push_status == 1)
									{															
										$push_message = array("message"=>$push_msg,"trip_id"=>$pass_logid,"driverdetails"=>$driverdetails,"status"=>$push_status);
									}
									else
									{
										$push_message = array("message"=>$push_msg,"trip_id"=>$pass_logid,"trip_detail"=>$trip_detail,"status"=>$push_status);
									}
									//$p_send_notification = $api->send_passenger_mobile_pushnotification($p_device_token,$p_device_type,$push_message,$this->customer_google_api);
									//print_r($push_message);
									//exit;
								}
																																								
					}
					else
					{
						$msg = array("message" => __('invalid_trip'),"status"=>-1);	
					}
					echo json_encode($msg);
					break;
			
			
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9YRlJJb1p0NjdxYTU5ZmlIRFl1OGJPQ0J2elRHQVYxZmY=?type=driver_status_select&driver_id=60	
			case 'driver_status_select':
			$driver_status_array = $mobiledata;
					$check_result = $api->check_driver_companydetails($driver_status_array['driver_id'],$default_companyid);
					if($check_result == 0)	
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);
						echo json_encode($message);
						exit;;
					}

					if($driver_status_array['driver_id'] != null)
					{																						
						//$result = $api->select_table(DRIVER,'driver_id',$array['driver_id']);	
						$result = $api->select_driverloc($driver_status_array['driver_id'],$default_companyid);	
						$driver_details=array();
						$latitude = $longitude = '0.0';
						$status = 'F';
						
						//print_r($result);
						if(count($result)>0)
						{
							foreach($result as $details)
							{
								$driver_status = $details['status'];	
								$id = $details['id'];			//				
								$shift_status = $details['shift_status'];
								$driver_id = $details['driver_id'];
								$latitude = $details['latitude'];
								$longitude = $details['longitude'];		
								$update_date = $details['update_date'];	
							}
							//$result[0]['status'] =  ($driver_status != 'B') ?  $driver_status : 'F';
							$driver_details = array(
									"id"=>$id,
									"driver_id"=>$driver_id,
									"latitude"=>$latitude,
									"longitude"=>$longitude,
									"status"=>$status,
									"shift_status"=>$shift_status,
									"update_date"=>$update_date);
						}
						
						$driver_current_journey = $api->get_driver_current_journey($driver_status_array['driver_id'],$default_companyid,'0');

						$trip_details=array();
						if(count($driver_current_journey)> 0)
						{
							foreach($driver_current_journey as $values)
								{
									$current_location = $values['current_location'];				
									$drop_location = $values['drop_location'];
									$current_latitude = $values['pickup_latitude'];
									$current_longitude = $values['pickup_longitude'];
									$drop_latitude = $values['drop_latitude'];
									$drop_longitude = $values['drop_longitude'];
									//$no_passengers = $value->no_passengers;									
								}

							$trip_details = array(
									"pickup_location"=>$current_location,
									"drop_location"=>$drop_location,
									"current_latitude"=>$current_latitude,
									"current_longitude"=>$current_longitude,
									"drop_latitude"=>$drop_latitude,
									"drop_longitude"=>$drop_longitude
									);
						}
						else
						{
							$trip_details = array('No Trip Found.');
						}
						if(count($result) > 0)				
							$message = array("current_location" => $result,"current_trip"=>$trip_details,"status"=>1);		
						else
							$message = array("message" => 'Driver Not Found or Kindly update your status',"status"=>-1);
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
					
			//URL : api/?type=driver_journey_status&pass_logid=7	
			case 'driver_journey_status':
			$driver_journey_array = $mobiledata;
					if($driver_journey_array['pass_logid'] != null)
					{													
						$result = $api->select_table(PASSENGERS_LOG,'passengers_log_id',$driver_journey_array['pass_logid']);		
						if(count($result) > 0)				
							$message = $result;		
						else
							$message = array("message" => __('invalid_trip'),"status"=>0);
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
				
			//PASSENGER LOG TABLE UPDATE		
			//URL : api/?type=driver_journey_status_update&pass_logid=7&time_to_reach_passen=&drop_time=&pickupdrop=&waitingtime=&rating=&comments=&travel_status=&driver_reply=&driver_comments=&msg_status
			case 'driver_journey_status_update':
			$journey_status_update = $mobiledata;
					if($journey_status_update['pass_logid'] != null)
					{	
						//Removing the URL Other parameters
						array_shift($journey_status_update);
						array_shift($journey_status_update);						
						//print_r($array);
						$passengers_log_id = $journey_status_update['pass_logid'];
						
						//Removing the $array['pass_logid'] from array	for the $org_array
						array_shift($journey_status_update);	
												
						foreach($journey_status_update as $key=>$arr)
						{
							if($arr != null)
								$org_array[$key] = $arr;
						}
												
						if(count($org_array) > 0)		{						
							$result = $api->update_table(PASSENGERS_LOG,$org_array,'passengers_log_id',$passengers_log_id);
							$message = array("message" => 'Data Updated Successfully',"status"=>1);	
						}
						else{
							$message = array("message" => 'Atleast Provide Single Field Data',"status"=>0);
						}
					
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
											
			//URL : http://192.168.1.88:1235/api/index/dGF4aV9KcUJRWjgwVjRJajc1RXhrSXFwaXpSUHA5Umd3eGI=?type=driver_upcoming_journey&driver_id=3
			case 'driver_upcoming_journey':
			$driver_upcoming_journey = $mobiledata;
					$check_result = $api->check_driver_companydetails($driver_upcoming_journey['driver_id'],$default_companyid);
					if($check_result == 0)	
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);
						echo json_encode($message);
						exit;
					}

					if($array['driver_id'] != null)
					{
						$driver_id = $driver_upcoming_journey['driver_id'];		
						$driver_logs_upcoming = $api->get_driver_logs($driver_id,'R','A','9',$default_companyid);		
						$array_inc = 0;
						foreach($driver_logs_upcoming as $journey)
						{
							$upcoming_journey[] = (array) $journey;		
							$pickuptime = date('H:i:s',strtotime($journey->pickup_time));	

							 $currenttime = date('H:i:s',strtotime("+10 min"));	
	 
							//$currenttime ="<script>document.write(currenttime);</script>";
							if($pickuptime <= $currenttime)
							{	
								//$this->array_put_to_position($upcoming_journey, 'P', 1, 'pickstatus');
								$upcoming_journey = $this->array_push_assoc($upcoming_journey,$array_inc, 'pickstatus', 'P');									
							}
							else
							{
								$upcoming_journey = $this->array_push_assoc($upcoming_journey,$array_inc, 'pickstatus', 'w');	
							}  
						$array_inc++;	
						}

						if(count($driver_logs_upcoming) == 0)
						{
							$message = array("message" => __('no_data'),"status"=>0);	
						}
						else
						{
							$message = array("message" => $upcoming_journey,"status" => 1);										
							//$message = Arr::merge($msg,$upcoming_journey);
						}				
						
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
			/**
			 * API : Driver invites with referral code
			 * Url : http://192.168.1.116:1027/mobileapi116/index/dGF4aV9hbGw=/?type=driver_invite_with_referral
			 * Params : {"driver_id":"1529"}
			 **/							
			case 'driver_invite_with_referral':
				$driverId = isset($mobiledata['driver_id']) ? $mobiledata['driver_id'] : '';
				if(!empty($driverId)) {
					$check_driver_login_status = $this->is_login_status($driverId,$default_companyid);
					if($check_driver_login_status == 1)
					{ 
						if(DRIVER_REFERRAL_SETTINGS == 1) {
							$driverReferral = $api->getDriverReferralDetails($driverId);
							if(count($driverReferral) > 0) {
								$driverImage = $api->getDriverProfileImage($driverId);
								$drProfileImg = URL_BASE.'public/images/no_image109.png';
								if(!empty($driverImage) && file_exists(DOCROOT.SITE_DRIVER_IMGPATH.$driverImage)) {
									$drProfileImg = URL_BASE.SITE_DRIVER_IMGPATH.$driverImage;
								}
								$detail = array("referral_code" => $driverReferral[0]['registered_driver_code'],"referral_amount" => $driverReferral[0]['registered_driver_code_amount'],"profile_image"=>$drProfileImg);
								$msg = array("message" => __('referral_amount'),"detail" => $detail,"status"=>1);
							} else {
								$msg = array("message" => __('invalid_user'),"status"=>-2);
							}
						} else {
							$msg = array("message" => __('referral_settings_message'),"status"=>-1);
						}
					} else {
						$msg = array("message" => __('driver_not_login'),"status"=>-1);
					}
					
				} else {
					$msg = array("message" => __('invalid_request'),"status"=>-1);
				}
				echo json_encode($msg);
			break;
			/**
			 * API : Check the Referral code exist
			 * Url : http://192.168.1.116:1027/mobileapi116/index/dGF4aV9hbGw=/?type=check_driver_referral_code
			 * Params : {"driver_id":"1529","referral_code":"hO03hv"}
			 **/
			case 'check_driver_referral_code':
				$driverId = isset($mobiledata['driver_id']) ? $mobiledata['driver_id'] : '';
				$driverReferralCode = isset($mobiledata['referral_code']) ? $mobiledata['referral_code'] : '';
				if(!empty($driverReferralCode) && !empty($driverId)) {
					$driverReferral = $api->checkDriverReferralExists($driverReferralCode);
					if(count($driverReferral) > 0) {
						$driverUsedReferral = $api->checkDriverUsedReferral($driverId);
						if($driverUsedReferral > 0) {
							//updates the referred driver's id and referral status in registered users row who is using the referral code
							$referralArr = array("referred_driver_id" => $driverReferral[0]['registered_driver_id'],"registered_driver_wallet"=>$driverReferral[0]['registered_driver_code_amount']);
							$referUpdate = $this->commonmodel->update(DRIVER_REF_DETAILS,$referralArr,'registered_driver_id',$driverId);
							if($referUpdate) {
								/* Adding amout to driver wallet for pay by wallet N0592 12-5-2017 */
								$driver_profiles = $api->driver_profile($driverId);
								$trip_wallet = isset($driver_profiles[0]['trip_wallet'])?$driver_profiles[0]['trip_wallet']:'0';
								
								$trip_wallet = $trip_wallet + $driverReferral[0]['registered_driver_code_amount'];

								$trip_wallet_arrary = array("trip_wallet" => $trip_wallet);
								$trip_wallet_result = $api->update_table(PEOPLE,$trip_wallet_arrary,'id',$driverId);
								$current_time =	date('Y-m-d H:i:s');
								if($trip_wallet_result){
									$driver_wallet_trip_array = array("driver_id" => $driverId,"trip_id" => $passenger_log_id,'amount'=>$fare,'type'=>1,'date'=>$current_time);
									$this->commonmodel->insert(DRIVER_WALLET_TRANS,$driver_wallet_trip_array);
									
								}
								/* Adding amout to driver wallet for pay by wallet N0592 12-5-2017 */

								$msg = array("message" => __('referral_code_save_successful'),"status"=>1);
							} else {
								$msg = array("message" => __('try_again'),"status"=>-1);
							}
						} else {
							$msg = array("message" => __('referral_code_already_used'),"status"=>-1);
						}
					} else {
						$msg = array("message" => __('driver_referral_code_not_exists'),"status"=>-2);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status"=>-1);
				}
				echo json_encode($msg);
			break;
			/**
			 * API : Driver Requests the wallet amount
			 * Url : http://192.168.1.116:1027/mobileapi116/index/dGF4aV9hbGw=/?type=driver_wallet_request
			 * Params : {"driver_id":"1529","driver_wallet_amount":"150"}
			 **/
			case 'driver_wallet_request':
				$driverId = isset($mobiledata['driver_id']) ? $mobiledata['driver_id'] : '';
				$requestedAmount = isset($mobiledata['driver_wallet_amount']) ? $mobiledata['driver_wallet_amount'] : '';
				if(!empty($requestedAmount) && !empty($driverId)) {
					$company_det =$api->get_company_id($driverId);
					$companyId = (count($company_det) > 0) ? $company_det[0]['company_id'] : $default_companyid;
					$currentTime = $this->commonmodel->getcompany_all_currenttimestamp($companyId);
					$driverWalletDets = $api->getDriverReferralDetails($driverId);
					$existWalletAmt = (count($driverWalletDets) > 0) ? $driverWalletDets[0]['registered_driver_wallet'] : 0;
					if($existWalletAmt >= $requestedAmount) {
						$result = $api->saveDriverWalletRequest($driverId,$requestedAmount,$currentTime);
						if($result) {
							$existWalletAmt =  $existWalletAmt - $requestedAmount;
							$wallArr = array("registered_driver_wallet" => $existWalletAmt);
							$walletUpdate = $this->commonmodel->update(DRIVER_REF_DETAILS,$wallArr,'registered_driver_id',$driverWalletDets[0]['registered_driver_id']);
							$msg = array("message" => __('driver_wallet_request_send'),"driver_wallet_amount"=>$existWalletAmt,"status"=>1);
						} else {
							$msg = array("message" => __('try_again'),"status"=>-1);
						}
					} else {
						$msg = array("message" => __('dont_have_sufficient_wallet_amount'),"status"=>-1);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status"=>-1);
				}
				echo json_encode($msg);
			break;
			/**
			 * API : Driver Wallet Amount and Request List
			 * Url : http://192.168.1.116:1027/mobileapi116/index/dGF4aV9hbGw=/?type=driver_wallet
			 * Params : {"driver_id":"1529"}
			 **/
			case 'driver_wallet':
				$driverId = isset($mobiledata['driver_id']) ? $mobiledata['driver_id'] : '';
				if(!empty($driverId)) {
					$check_driver_login_status = $this->is_login_status($driverId,$default_companyid);
					if($check_driver_login_status == 1)
					{
						$driverWallets = $api->getDriverReferralDetails($driverId);
						$walletAmt = isset($driverWallets[0]['registered_driver_wallet']) ? $driverWallets[0]['registered_driver_wallet'] : 0;
						$requestLists = $api->getWalletAmtRequests($driverId);
						$msg = array("message" => __('wallet_details'),"wallet_amount"=>$walletAmt,"request_lists"=>$requestLists,"status"=>1);
					} else {
						$msg = array("message" => __('driver_not_login'),"status"=>-1);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status"=>-1);
				}
				echo json_encode($msg);
			break;
		// http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=tell_to_friend_by_sms&driver_id=16&phone=
			case 'tell_to_friend_by_sms':
			$tell_sms_array = $mobiledata;
				//print_r();
				$validator = $this->tellfri_sms_validation($tell_sms_array);				
				if($validator->check())
				{
					//Set the Favourite Trips
					$driver_details = $api->driver_profile($tell_sms_array['driver_id'],$default_companyid);
					$driver_referral_code="";
					if(count($driver_details)>0)
					{
						$driver_referral_code = $driver_details[0]['driver_referral_code'];
					}
					$message_details = $this->commonmodel->sms_message('7');
					$to = $tell_sms_array['phone'];
					$message = $message_details[0]['sms_description'];
					$message = str_replace("##SITENAME##",ucfirst(COMPANY_SITENAME),$message);
					$message = str_replace("##REFERRAL_CODE##",$driver_referral_code,$message);
					$message = str_replace("##ANDROID_PASSENGER_APP##",ANDROID_PASSENGER_APP,$message);
					$message = str_replace("##IOS_PASSENGER_APP##",IOS_PASSENGER_APP,$message);
					$message = str_replace("##ANDROID_DRIVER_APP##",ANDROID_DRIVER_APP,$message);
					//echo $message;exit;
					//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
					$result = true;
					if($result)
					{
						$message = array("message" => __('sms_invite_send'),"status"=>1);	
					}
					else
					{
						$message = array("message" => __('try_again'),"status"=>0);	
					}
					
				}
				else
				{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"status"=>-3,"detail"=>$validation_error);										
				}
				echo json_encode($message);
				break;				
			break;	
			//URL
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=tell_to_friend_by_email&driver_id=16&email=
			case 'tell_to_friend_by_email':
			$tell_mail_array = $mobiledata;
			$driver_id = $tell_mail_array['driver_id'];
			$email = $tell_mail_array['email'];					
			$validator = $this->tellfri_email_validation($tell_mail_array);
			if($validator->check())
			{			
					$name = $driver_referral_code = "";
					$driver_details = $api->driver_profile($tell_mail_array['driver_id'],$default_companyid);
					$driver_referral_code="";
					if(count($driver_details)>0)
					{
						$driver_referral_code = $driver_details[0]['driver_referral_code'];
						$name = $driver_details[0]['name'];
					}
					$message = DRIVER_TELL_TO_FRIEND_MESSAGE;
					$mail="";
					$subject = __('driver_telltofriend_subject').' '.$this->app_name;
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_NAME=>$name,REPLACE_SUBJECT=>$subject,REPLACE_MESSAGE=>$message,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COMPANYDOMAIN=>$this->domain_name,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					
			/* Added for language email template */
			if($this->lang!='en'){
			if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/driver_telltofriend-'.$this->lang.'.html')){
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/driver_telltofriend-'.$this->lang.'.html',$replace_variables);
			}else{
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver_telltofriend.html',$replace_variables);
			}
			}else{
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver_telltofriend.html',$replace_variables);
			}
			/* Added for language email template */
					$to = $email;
					$from = $this->siteemail;
					$redirect = "no";	
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
						//$rejectedemails.' '.__('already_reg')
						$message = array("message" => __('driver_tellfri_email_success'),"status"=> 1);	
			}
			else
			{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"status"=>-3,"detail"=>$validation_error);				
			}					
            echo json_encode($message);								
			break;

		
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=driver_statistics&driver_id=7
			case 'driver_statistics':
			//$driver_model = Model::factory('driver');
					if($mobiledata['driver_id'] != null)
					{
						$company_det =$api->get_company_id($mobiledata['driver_id']);
						$default_companyid = ($company_det > 0) ? $company_det[0]['company_id'] : $default_companyid;
						$check_driver_login_status = $this->is_login_status($mobiledata['driver_id'],$default_companyid);
						if($check_driver_login_status == 1)
						{
							$driver_id = $mobiledata['driver_id'];																		
							$driver_details = $api->driver_profile($driver_id);
							$driver_logs_rejected = $api->get_rejected_drivers($driver_id,$default_companyid);	
							$rejected_trips = count($driver_logs_rejected);	
							$driver_cancelled_trips = $api->get_driver_cancelled_trips($driver_id,$default_companyid);
							//$driver_earnings = $driver_model->get_driver_earnings($driver_id);
							$driver_tot_earnings = $api->get_driver_total_earnings($driver_id);
							//$driver_comments = $api->get_driver_comments($driver_id,'',$default_companyid);	
							$driver_comments = $api->get_driver_earnings_with_rating($driver_id,$default_companyid);
							$today_goal = $amount_left = $today_earnings = 0;
							$goal_detail = $api->get_goal_details($driver_id,'R','A','1');
							$driver_shift_status = $api->get_driver_shift($driver_id);
							if(count($goal_detail)>0)
							{
								$today_earnings = $goal_detail[0]['acheive_amt'];
							}
								$statistics = array();
								$total_trip = $trip_total_with_rate = $total_ratings = $total_amount = 0;
								
								foreach($driver_comments as $stat){
									$total_trip++;
									$total_ratings += $stat['rating'];
									$total_amount += $stat['total_amount'];
									if($stat['rating'] != 0)
										$trip_total_with_rate++;
								}
								
								$time_driven = $api->get_time_driven($driver_id,'R','A','1');
								if(count($driver_details) > 0)
								{
									$drivername = ucfirst($driver_details[0]['name']).' '.ucfirst($driver_details[0]['lastname']);
									$notification_setting = $driver_details[0]['notification_setting'];
									$overall_trip = $total_trip + $rejected_trips + $driver_cancelled_trips;
									$statistics = array( 
												"drivername"=>$drivername,
												"total_trip" => $overall_trip,
												"completed_trip" => $total_trip,
												//"total_earnings" => round($driver_earnings[0]['total_amount'],2),
												"total_earnings" => round($driver_tot_earnings,2),
												"overall_rejected_trips" => $rejected_trips,
												"cancelled_trips" => $driver_cancelled_trips,
												//"today_earnings"=>round($today_earnings,2),											
												"today_earnings"=>round($total_amount,2),											
												"shift_status"=> $driver_shift_status,
												"time_driven"=>$time_driven,
												"status"=> 1
											  ); 
									$message = array("message" => __('success'),"detail"=>$statistics,"status"=>1);
								}
								else
								{
									$message = array("message" => __('invalid_driver'),"status"=>2);
								}
							//	$message = $statistics;		
							//}	
						}
						else
						{
							$message = array("message" => __('driver_not_login'),"status"=>-1);
						}
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;					

			//URL : http://192.168.1.88:1020/api/index/dGF4aV9hbGw=/?type=driver_shift_status&driver_id=8&shiftstatus=IN&reason=&update_id=
			case 'driver_shift_status':
			$array = $mobiledata;
			$validator = $this->shift_status_validation($array);
			if($validator->check())
			{
					$driver_id = $array['driver_id'];
					$company_status = $api->api_companystatus($array['driver_id']);
					if(($company_status == 'D') || ($company_status == 'T')){
						$message = array("message" => __('user_blocked'),"status"=>-7);
						echo json_encode($message);
						//return;
						exit;
					}
					if($array['driver_id'] != null)
					{
					$check_result = $api->check_driver_companydetails($array['driver_id'],$default_companyid);
					if($check_result == 0)
					{
						$message = array("message" => __('company_deactivaed_driver'),"status"=>'-1');
						echo json_encode($message);
						exit;
					}
$company_det =$api->get_company_id($driver_id);
$default_companyid = (count($company_det) > 0) ? $company_det[0]['company_id'] : $default_companyid;
						$getTaxiassignedforDriver = $api->get_assignedtaxi_list($driver_id,$default_companyid);
						//echo count($getTaxiassignedforDriver);exit;
						$current_driver_status = $api->get_driver_current_status($array['driver_id'],$default_companyid);
							$shiftstatus = $array['shiftstatus'];
								if($array['shiftstatus'] == 'IN')
								{
									if(count($getTaxiassignedforDriver)>0)
									{	
										$taxi_id = "";
										/*$getTaxiforDriver = $api->getTaxiforDriver($driver_id,$default_companyid);	
										if(count($getTaxiforDriver) > 0 )
										{*/
										$taxi_id = $getTaxiassignedforDriver[0]['taxi_id'];
										//$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($default_companyid);
										$driver_reply = $api->update_driver_shift_status($driver_id,$array['shiftstatus']);
										$insert_array = array(
																"driver_id" => $driver_id,
																"taxi_id" 			=> $taxi_id,												
																"shift_start" 	=> $company_all_currenttimestamp,
																"shift_end"		=> "",
																"reason"		=> $array['reason'],
																"createdate"		=> $this->currentdate,
															);
										//Inserting to Transaction Table 
										$transaction = $this->commonmodel->insert(DRIVERSHIFTSERVICE,$insert_array);	
										//print_r($transaction);		
										$insert_id = $transaction[0];
										if($transaction)
										{
												$detail = array("update_id"=>$insert_id);
												$message = array("message" => __('driver_shift'),"status"=>1,"detail"=>$detail);
										}
										else
										{
											$message = array("message" => __('try_again'),"status"=>-2);
										}
									/*   }	
									   else
									   {
											$message = array("message" => __('taxi_not_assigned'),"status"=>-3);
											//exit;
									   } */	
									}
									else
									{
										$message = array("message" => __('taxi_not_assigned'),"status"=>-3);
									}						
								}
								else
								{
									if($current_driver_status[0]->status != 'A')
									{
										$get_driver_log_details = $api->get_driver_log_details($driver_id,$default_companyid);
										$driver_trip_count = count($get_driver_log_details);//exit;
										if($driver_trip_count == 0)
										{
											$update_id = $array['update_id'];
											//$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($default_companyid);
											$update_arrary  = array("shift_end" => $company_all_currenttimestamp);
											if($update_id != "")
											{
												$transaction = $this->commonmodel->update(DRIVERSHIFTSERVICE,$update_arrary,'driver_shift_id',$update_id);
												$driver_reply = $api->update_driver_shift_status($driver_id,'OUT');
												if($transaction)
												{
													$message = array("message" => __('driver_shift_out'),"status"=>1);
												}
												else
												{
													$message = array("message" => __('try_again'),"status"=>-2);
												}
											}
											else
											{
												$message = array("message" => __('update_id_missing'),"status"=>-5);
											}
										}
										else
										{
											$message = array("message" => __('trip_in_future'),"status"=>-4);
										}
									}
									else
									{
										$drivers_tripids = $api->get_driver_tripsdetails($driver_id);
										$message = array("message" => __('driver_in_trip').' TripIds '.$drivers_tripids,"status"=>-1);
									}
								}
					}
					else
					{
						$message = array("message" => __('invalid_user_driver'),"status"=>-1);
					}
				}
				else
				{
					$validation_error = $validator->errors('errors');	
					$message = array("message" => __('validation_error'),"status"=>-3,"detail"=>$validation_error);				
				}
			echo json_encode($message);
			break;
			case 'driver_shift':
			$array = $mobiledata;
			$validator = $this->shift_status_validation($array);
			if($validator->check())
			{			
				$check_driver_login_status = $this->is_login_status($array['driver_id'],$default_companyid);
				if($check_driver_login_status == 1)
				{
					$driver_id = $array['driver_id'];
					$company_status = $api->api_companystatus($array['driver_id']);	
					if(($company_status == 'D') || ($company_status == 'T')){
						$message = array("message" => __('user_blocked'),"status"=>-7);
						echo json_encode($message);
						//return;
						exit;
					}
					if($array['driver_id'] != null)
					{						
						$getTaxiassignedforDriver = $api->get_assignedtaxi_list($driver_id,$default_companyid);			
						//print_r($getTaxiassignedforDriver);	
						$current_driver_status = $api->get_driver_current_status($array['driver_id'],$default_companyid);				

							$shiftstatus = $array['shiftstatus'];
								if($array['shiftstatus'] == 'IN')
								{ 
									if(count($getTaxiassignedforDriver)>0)
									{	
											$taxi_id = "";
											$taxi_id = $getTaxiassignedforDriver[0]['taxi_id'];														
											//$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($default_companyid);
											$update_shift_status = $api->update_driver_shift_status($driver_id,$array['shiftstatus']);
											
												if($update_shift_status != 0)
												{
													$message = array("message" => __('driver_shift'),"status"=>1);
												}
												else
												{
													$message = array("message" => __('try_again'),"status"=>-2);
												}	
									 }
									 else
									 {
										 $message = array("message" => __('taxi_not_assigned'),"status"=>-3);
									 }						
								}
								else
								{
									if($current_driver_status[0]->status != 'A')
									{
										$get_driver_log_details = $api->get_driver_log_details($driver_id,$default_companyid);
										$driver_trip_count = count($get_driver_log_details);//exit;
										if($driver_trip_count == 0)
										{
											//$update_id = $array['update_id'];
											//$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($default_companyid);
											//$update_arrary  = array("shift_end" => $company_all_currenttimestamp);
											//if($update_id != "")
											//{
												//$transaction = $this->commonmodel->update(DRIVERSHIFTSERVICE,$update_arrary,'driver_shift_id',$update_id);
												$driver_reply = $api->update_driver_shift_status($driver_id,'OUT');
												if($driver_reply)
												{
													$message = array("message" => __('driver_shift_out'),"status"=>2);
												}
												else
												{
													$message = array("message" => __('try_again'),"status"=>-2);
												}
											/*}
											else
											{
												$message = array("message" => __('update_id_missing'),"status"=>-5);
											}*/
										}
										else
										{
											$message = array("message" => __('trip_in_future'),"status"=>-4);
										}
									}
									else
									{
										$drivers_tripids = $api->get_driver_tripsdetails($driver_id);
										$message = array("message" => __('driver_in_trip').' TripIds '.$drivers_tripids,"status"=>-1);
									}
								}
					}
					else
					{
						$message = array("message" => __('invalid_user_driver'),"status"=>-1);
					}
					
					}
				else
				{
					$message = array("message" => __('driver_not_login'),"status"=>-1);
				}
			}
			else
			{
				$validation_error = $validator->errors('errors');	
				$message = array("message" => __('validation_error'),"status"=>-3,"detail"=>$validation_error);				
			}
			echo json_encode($message);
			break;
						
		//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=complete_trip&trip_id=574&drop_latitude=11.7625134&drop_longitude=76.1235648&drop_location=vadavalli,coimbatoe&distance=6&actual_distance=&waiting_time=1.5
		case 'complete_trip':
		$array = $mobiledata;
		/* array(
		"passenger_log_id" => 457,
		"drop_latitude" => 11.0214907,
		"drop_longitude" => 76.9166205,
		"drop_location" => "gandhipuram,coimbatore");*/
		if(!empty($array))
		{    
			$drop_latitude = $array['drop_latitude'];
			$drop_longitude = $array['drop_longitude'];
			$drop_location = urldecode($array['drop_location']);
			$trip_id = $array['trip_id'];
			$distance = $array['distance'];
			$actual_distance = $array['actual_distance'];
			$waiting_hours = $array['waiting_hour'];
			$driver_app_version = (isset($array['driver_app_version'])) ? $array['driver_app_version'] : '';

			$device_type = (isset($array['device_type']))?$array['device_type']:'';
			$app_version = isset($array['driver_app_version']) ? $array['driver_app_version'] : '';
			$os_version = (isset($array['os_version'])) ? $array['os_version'] : '';
			$brand_name = (isset($array['brand_name'])) ? $array['brand_name'] : '';
			
			$driver_app_version_details=json_encode(array('device_type'=>$device_type,'brand_name'=>$brand_name,'os_version'=>$os_version,'app_version'=>$app_version));
			/*if($actual_distance == "")
				$total_distance = $distance;
			else
				$total_distance = $actual_distance;
			//*/
			if(!empty($trip_id))
			{
				//echo $default_companyid;exit;
				$gateway_details = $this->commonmodel->gateway_details($default_companyid);
				$get_passenger_log_details = $api->get_passenger_log_detail($trip_id);
				//print_r($get_passenger_log_details);exit;
				$p_referral_discount = 0;
				$pickupdrop = $taxi_id = $company_id = 0;
				$fare_per_hour = $waiting_per_hour = $total_fare = $nightfare = 0;
				if(count($get_passenger_log_details) > 0)
				{
						/******* Check whether the trip is completed if so we change the driver status and trip travel status and give response **********/
						//$flag = 0;
						//$trans_result = $api->check_tranc($trip_id,$flag);
						if($get_passenger_log_details[0]->transaction_id != 0)
						{
							$travel_status = $get_passenger_log_details[0]->travel_status;
							//exit;
							$driver_id = $get_passenger_log_details[0]->driver_id;
							if(($travel_status == 1 || $travel_status == 5 || $travel_status == 2))
							{									
								/********** Update Driver Status after complete Payments *****************/									
								$update_driver_arrary = array("status" => 'F');
								$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_id);	
								/************Update Driver Status ***************************************/				
								$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
								$journey = $api->update_journey_status($trip_id,$msg_status,$driver_reply,$journey_status);
								/*************** Update arrival in driver request table ******************/
								$update_trip_array  = array("status"=>'7');
								$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$trip_id);		
								/*************************************************************************/	
								$resMessage = ($travel_status == 1) ?  __('trip_fare_already_updated') : __('trip_fare_and_status_updated');
								$message = array("message" => $resMessage, "status"=>-1);
								echo json_encode($message);
								break;
							}								

						}
						else
						{ 
							$passenger_discount = $get_passenger_log_details[0]->passenger_discount; // 
							//print_r($get_passenger_log_details);exit;
							$passengers_id = $get_passenger_log_details[0]->passengers_id;
							$referred_by = $get_passenger_log_details[0]->referred_by;									
							///$referral_earned_amount = $get_passenger_log_details[0]->referral_earned_amount;
							$referrer_earned = $get_passenger_log_details[0]->referrer_earned;
							$company_tax = $get_passenger_log_details[0]->company_tax;
							$tax = (FARE_SETTINGS != 2) ? TAX : $company_tax;
							$travel_status = $get_passenger_log_details[0]->travel_status;
							$splitTrip = $get_passenger_log_details[0]->is_split_trip;//0 - Normal trip, 1 - Split trip
							$total_distance = $get_passenger_log_details[0]->distance;
							$used_wallet_amount = $get_passenger_log_details[0]->used_wallet_amount;
							$promocode = $get_passenger_log_details[0]->promocode;
							$p_referral_discount = 0;
							$pickupdrop = $taxi_id = $company_id = 0;
							$fare_per_hour = $waiting_per_hour = $total_fare = $nightfare = 0;
							
							if(($travel_status == 2) || ($travel_status == 5))
							{
								$pickup = $get_passenger_log_details[0]->current_location;
								$drop = $get_passenger_log_details[0]->drop_location;
								$pickupdrop = $get_passenger_log_details[0]->pickupdrop;
								$taxi_id = $get_passenger_log_details[0]->taxi_id;
								$pickuptime = date('H:i:s', strtotime($get_passenger_log_details[0]->pickup_time));
								$actualPickupTime = date('H:i:s', strtotime($get_passenger_log_details[0]->actual_pickup_time));
								$company_id = $get_passenger_log_details[0]->company_id;
								$driver_id = $get_passenger_log_details[0]->driver_id;
								$approx_distance = $get_passenger_log_details[0]->approx_distance;
								$approx_fare = $get_passenger_log_details[0]->approx_fare;
								$fixedprice = $get_passenger_log_details[0]->fixedprice;
								$passengers_id = $get_passenger_log_details[0]->passengers_id;
								$referred_by = $get_passenger_log_details[0]->referred_by;			
								$actual_pickup_time = $get_passenger_log_details[0]->actual_pickup_time;
								$brand_type = $get_passenger_log_details[0]->brand_type;
								$passengerMobile = $get_passenger_log_details[0]->passenger_country_code.$get_passenger_log_details[0]->passenger_phone;
								
								$taxi_model_id = $get_passenger_log_details[0]->taxi_modelid;
								//$taxi_details = $api->get_taxi_model_details($taxi_id);
								//$taxi_model_id = $taxi_details[0]['taxi_model'];	
											
								$taxi_fare_details = $api->get_model_fare_details($company_id,$taxi_model_id,$get_passenger_log_details[0]->search_city,$brand_type);
								//print_r($taxi_fare_details);exit;
								if($travel_status != 5) {
									$drop_time = $this->commonmodel->getcompany_all_currenttimestamp($company_id);
									//echo $drop_time."--".$actual_pickup_time;exit;
								} else {
									$drop_time = $get_passenger_log_details[0]->drop_time;
								}
								
								/*************** Update arrival in driver request table ******************/
								$update_trip_array  = array("status"=>'7');
								$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$trip_id);		
								/*************************************************************************/	
								
								/** Update Driver Status **/
								$update_driver_arrary  = array(
								"latitude" => $array['drop_latitude'],
								"longitude" => $array['drop_longitude'],
								"status" => 'A');	
								if(($array['drop_latitude'] > 0 ) && ($array['drop_longitude'] > 0))
								{
									$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_id);	
								}
								else
								{
									$update_driver_arrary  = array("status" => 'A');
									$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_id);	
								}
								/*********************/
								$base_fare = '0';
								$min_km_range = '0';
								$min_fare = '0';
								$cancellation_fare = '0';
								$below_above_km_range = '0';
								$below_km = '0';
								$above_km = '0';
								$night_charge = '0';
								$night_timing_from = '0';
								$night_timing_to ='0';
								$night_fare = '0';
								$evening_charge = '0';
								$evening_timing_from = '0';
								$evening_timing_to ='0';
								$evening_fare = '0';
								$waiting_per_hour = '0';
								$minutes_cost= '0';
								if(count($taxi_fare_details) > 0)
								{
									$base_fare = $taxi_fare_details[0]['base_fare'];
									$min_km_range = $taxi_fare_details[0]['min_km'];
									$min_fare = $taxi_fare_details[0]['min_fare'];
									$cancellation_fare = $taxi_fare_details[0]['cancellation_fare'];
									$below_above_km_range = $taxi_fare_details[0]['below_above_km'];
									$below_km = $taxi_fare_details[0]['below_km'];
									$above_km = $taxi_fare_details[0]['above_km'];
									$night_charge = $taxi_fare_details[0]['night_charge'];
									$night_timing_from = $taxi_fare_details[0]['night_timing_from'];
									$night_timing_to = $taxi_fare_details[0]['night_timing_to'];
									$night_fare = $taxi_fare_details[0]['night_fare'];
									$evening_charge = $taxi_fare_details[0]['evening_charge'];
									$evening_timing_from = $taxi_fare_details[0]['evening_timing_from'];
									$evening_timing_to = $taxi_fare_details[0]['evening_timing_to'];
									$evening_fare = $taxi_fare_details[0]['evening_fare'];
									$waiting_per_hour = $taxi_fare_details[0]['waiting_time'];
									$minutes_fare = $taxi_fare_details[0]['minutes_fare'];
								}
								
								// Which is used when the driver send waiting time as minutes
								/*$split_time = explode(":",$waiting_time);
								
								if(count($split_time) == 3)
								{
									//Converting to Hours
									$waiting_hours = $split_time[0]/60;
								}
								else
								{
									//Converting to Hours
									$waiting_hours = $split_time[0];
									$waiting_hours += $split_time[1]/60;
								}
								print_r($split_time);echo '<br>';
								echo count($split_time);echo '<br>';
								echo $waiting_hours;exit;*/
								$roundtrip="No";
								if($pickupdrop == 1)
								{
									$roundtrip = "Yes";
									//$total_distance = $total_distance * 2;
								}
								// Minutes travelled functionlity starts here
								/*$trip_seconds = strtotime($drop_time) - strtotime($actual_pickup_time);
								$trip_days    = floor($trip_seconds / 86400);
								$trip_hours   = floor(($trip_seconds - ($trip_days * 86400)) / 3600);
								$trip_minutes = floor(($trip_seconds - ($trip_days * 86400) - ($trip_hours * 3600))/60);
								$trip_seconds = floor(($trip_seconds - ($trip_days * 86400) - ($trip_hours * 3600) - ($trip_minutes*60)));*/
								/********Minutes fare calculation *******/ 
							   $interval  = abs(strtotime($drop_time) - strtotime($actual_pickup_time));
							   $minutes   = round($interval / 60);       
							   /********Minutes fare calculation *******/
								//$minutes=$trip_minutes;
								// Minutes travelled functionlity ends here
								$baseFare = $base_fare;
								$total_fare = $base_fare;
								if(FARE_CALCULATION_TYPE==1 || FARE_CALCULATION_TYPE==3)
								{
									$baseFare = $base_fare;
									if($total_distance < $min_km_range)
									{
										//min fare has set as base fare if trip distance 
										$baseFare = $min_fare;
										$total_fare = $min_fare;
									}
									else if($total_distance <= $below_above_km_range)
									{
										$fare = $total_distance * $below_km;
										$total_fare  = 	$fare + $base_fare ;
									}
									else if($total_distance > $below_above_km_range)
									{
										$fare = $total_distance * $above_km;
										$total_fare  = 	$fare + $base_fare ;
									}
								}
								
								if(FARE_CALCULATION_TYPE==2 || FARE_CALCULATION_TYPE==3)
								{
									/********** Minutes fare calculation ************/
									//$date_difference = abs($drop_time - $actual_pickup_time);
									//$minutes = floor(((($date_difference % 604800) % 86400) % 3600) / 60);
									if($minutes_fare > 0)
									{
										$minutes_cost = $minutes * $minutes_fare;
										$total_fare  = $total_fare + $minutes_cost;
									}
									/************************************************/
								}
								$trip_fare = $total_fare;

								// Waiting Time calculation
								$waiting_cost = ($waiting_per_hour * $waiting_hours)/60;
								$total_fare = $waiting_cost + $total_fare;

								$parsed = date_parse($actualPickupTime);
								$pickup_seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];

								//Night Fare Calculation
								$parsed = date_parse($night_timing_from);
								$night_from_seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];

								$parsed = date_parse($night_timing_to);
								$night_to_seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];

								$nightfare_applicable = $date_difference=0;
								if ($night_charge != 0) 
								{
									if( ($pickup_seconds >= $night_from_seconds && $pickup_seconds <= 86399) || ($pickup_seconds >= 0 && $pickup_seconds <= $night_to_seconds) )
									{
										$nightfare_applicable = 1;
										$nightfare = ($night_fare/100)*$total_fare;//night_charge%100;                                        
										$total_fare  = $nightfare + $total_fare;
									}
								}
								

								//Evening Fare Calculation
								$parsed_eve = date_parse($evening_timing_from);
								$evening_from_seconds = $parsed_eve['hour'] * 3600 + $parsed_eve['minute'] * 60 + $parsed_eve['second'];

								$parsed_eve = date_parse($evening_timing_to);
								$evening_to_seconds = $parsed_eve['hour'] * 3600 + $parsed_eve['minute'] * 60 + $parsed_eve['second'];

								$eveningfare = 0; $evefare_applicable=$date_difference=0;
								if ($evening_charge != 0) 
								{
									if( $pickup_seconds >= $evening_from_seconds && $pickup_seconds <= $evening_to_seconds)
									{
										$evefare_applicable = 1;
										$eveningfare = ($evening_fare/100)*$total_fare;//night_charge%100;
										$total_fare  = $eveningfare + $total_fare;
									}
								}
								
								// Passenger individual Discount Calculation
								$discount_fare="0.00";
								if($passenger_discount!='0')
								{
									$discount_fare = ($passenger_discount/100)*$total_fare;
									$total_fare = $total_fare - $discount_fare;
								}	
									
								// Referral Discount Claculation
								$siteinfo_details = $api->siteinfo_details();
								$promo_discount = $promodiscount_amount = 0;
								if($promocode != "")
								{
									$promodetails = $api->getpromodetails($promocode,$passengers_id);
									//print_r($promodetails);
									if($promodetails > 0)
									{
										$promo_discount = $promodetails;
										$calculate_amt = ($promo_discount/100)*$total_fare;
										$promodiscount_amount = round($calculate_amt,2);
										$total_fare = $total_fare-$promodiscount_amount;
									}
									else 
									{
										$promodiscount = 0;
										$promodiscount_amount = 0;
									}
								}
								$referral_discount = $siteinfo_details[0]['referral_discount'];
								$referdiscount = 0;
								// Company Tax amount Calculation
								$tax_amount = "";
								if($tax > 0)
								{
										$tax_amount = ($tax/100)*$total_fare;//night_charge%100;
										$total_fare =  $total_fare+$tax_amount;
								}
								$total_fare = ($fixedprice != 0) ? $fixedprice : $total_fare;
								$trip_fare = round($trip_fare,2);
								$total_fare = round($total_fare,2);
								$subtotal_fare = $total_fare;//to display the actual total trip fare in complete trip page
								$usedWalAmount = 0;
								$totalFareAmt = 0;
								$passenger_payment_option = 0;
								$total_wallet_amount = $api->passenger_profile($passengers_id);
								$total_wallet_amount = (isset($total_wallet_amount[0]['wallet_amount']) && $total_wallet_amount[0]['wallet_amount'] !="" )?$total_wallet_amount[0]['wallet_amount']:0;
								if($travel_status != 5) {
									//condition checked to avoid amount detection while trip is in waiting for payment status
									/** Referral amount detection if the passenger have amount in their wallet **/
									/*
									$show_credit_payment = 1;
									$totalPendingPercentage = $api->getpendingFarePercentage($trip_id);
									$splitPassDets = $api->getSplitPassengersDetails($trip_id);
									if(count($splitPassDets) > 0) {
										foreach($splitPassDets as $splitPass) {
											$farePercentage = ($splitPass['primary_pass_id'] == $splitPass['passenger_id']) ? ($splitPass['fare_percentage'] + $totalPendingPercentage) : $splitPass['fare_percentage'];
											$splittedFare = ($total_fare * $farePercentage) / 100;
											$splTotfare = round($splittedFare, 2);
											if($splitPass["approve_status"] == "A") {
												list($actusedWalAmount,$actTotalFare) = $this->deductWalletAmount($splitPass['passenger_id'],$splitPass['wallet_amount'],$siteinfo_details[0]['referral_settings'],$splTotfare,$usedWalAmount);
											} else {
												// Secondary passenger percent & amount to Zero //
												if($splitPass['primary_pass_id'] != $splitPass['passenger_id']) {
													$api->updateSecondaryPercentAmtPrimary($trip_id,$splitPass['passenger_id'],$splitPass['primary_pass_id']);
													$api->updatePassengerPercentAmt($trip_id,$splitPass['primary_pass_id'],1);
												}
												$actusedWalAmount = $actTotalFare = 0;
											}
											//update used wallet amount in passenger split details
											$updateUsedWalAmountArr = array("used_wallet_amount" => $actusedWalAmount);
											$api->updateUsedWalletAmount($updateUsedWalAmountArr,$splitPass['passenger_id'],$trip_id);
											$usedWalAmount = $usedWalAmount + $actusedWalAmount;
											$totalFareAmt = $totalFareAmt + $actTotalFare;
											//get payment option for passenger [ both normal and split trip ]
											$passenger_payment_option = ($splitPass['primary_pass_id'] == $splitPass['passenger_id']) ? $splitPass['passenger_payment_option'] : 0;
										}
									}
									$usedAmount = $usedWalAmount;
									$total_fare = $totalFareAmt;
									//to update the used wallet amount and  for a trip in passenger log table
									$msg_status = 'R';$driver_reply='A';$journey_status=5; // Waiting for Payment
									$journey = $api->update_journey_statuswith_drop($trip_id,$msg_status,$driver_reply,$journey_status,$drop_latitude,$drop_longitude,$drop_location,$drop_time,$total_distance,$waiting_hours,$tax,$driver_app_version,$usedAmount);
									*/
									/** Referral amount detection if the passenger have amount in their wallet **/
									

									$usedAmount = $usedWalAmount;
									/*$total_fare = $totalFareAmt;*/
									//to update the used wallet amount and  for a trip in passenger log table
									$msg_status = 'R';$driver_reply='A';$journey_status=5; // Waiting for Payment
									$journey = $api->update_journey_statuswith_drop($trip_id,$msg_status,$driver_reply,$journey_status,$drop_latitude,$drop_longitude,$drop_location,$drop_time,$total_distance,$waiting_hours,$tax,$driver_app_version_details,$usedAmount);

									//update the wallet amount in referred driver's row
									//get referred driver id
									$referredDriver = $api->getReferredDriver($driver_id);
									if($referredDriver > 0) {
										$driverReferral = $api->getDriverReferralDetails($referredDriver);
										if(count($driverReferral) > 0){
											$wallAmount = $driverReferral[0]['registered_driver_wallet'] + $driverReferral[0]['registered_driver_code_amount'];
											$wallArr = array("registered_driver_wallet" => $wallAmount);
											$walletUpdate = $this->commonmodel->update(DRIVER_REF_DETAILS,$wallArr,'registered_driver_id',$driverReferral[0]['registered_driver_id']);
											//update referrer earned status in registered driver's row while he completing his first trip
											$referrerEarnedArr = array("referral_status"=>"1");
											$refEarnedUpdate = $this->commonmodel->update(DRIVER_REF_DETAILS,$referrerEarnedArr,'registered_driver_id',$driver_id);
										}
									}
								} else {
									$usedAmount = $used_wallet_amount;
									$total_fare = $total_fare - $used_wallet_amount;
								}
								//echo $usedAmount.'----'.$total_fare; exit;
								$referdiscount = 0;//round($referdiscount,2);
								$discount_fare = round($discount_fare,2);
								$tax_amount = round($tax_amount,2);
								$nightfare = round($nightfare,2);
								
								//$total_fare = ($usedAmount >= $total_fare) ? 0 : $total_fare;

								//For testing purpose only
								/*if($total_fare==0){
									$total_fare =5;
								}*/
								//For testing purpose only
								
								if(SMS ==1)
								{
									//$passenger_phone_no=$api->get_passenger_phone_by_id($passengers_id);
									$message_details = $this->commonmodel->sms_message_by_title('complete_trip');
									if(count($message_details) > 0) {
										$to = $passengerMobile;
										$message = $message_details[0]['sms_description'];
										$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
										$this->commonmodel->send_sms($to,$message);
									}
								}
								/** Update Driver Status End**/
								//$passenger_payment_option = $api->get_passenger_payment_option($trip_id);

								//variable to know whether the passenger have credit card
								$check_card_data = $api->check_passenger_card_data($passengers_id);
								$credit_card_sts = ($check_card_data == 0) ? 0:SKIP_CREDIT_CARD;
								//condition checked to remove creditcard key value from array
			
								if($credit_card_sts == 0) {
									//condition checked to remove credit card if the passenger dont have credit card details
									$smpleArr = array();
									foreach($gateway_details as $key=>$valArr) {
										if($valArr['pay_mod_id'] != 2) {
											/*
											if(SKIP_CREDIT_CARD == 0)
												break;
											*/
											$smpleArr[] = $valArr;
											/*if(SKIP_CREDIT_CARD == 0)
											{
												$smpleArr[] = $valArr;
											} */

										}
									}
									$gateway_details = $smpleArr;
								}
								$passenger_payment_option_array = array();
								//if the completed trip is split fare type means dont show the new card option
								if($splitTrip == 1){
									//condition checked to remove credit card if the passenger dont have credit card details
									$smpleArr = array();
									foreach($gateway_details as $key=>$valArr){
										if($valArr['pay_mod_id'] != 1 && $valArr['pay_mod_id'] != 3) {
											$smpleArr[] = $valArr;
										}
									}
									$gateway_details = $smpleArr;
								} else {
									if(count($gateway_details) > 0 && $passenger_payment_option > 0) {
										foreach($gateway_details as $valArr) {
											/*(if($passenger_payment_option == $valArr["pay_mod_id"]) {
												$passenger_payment_option_array[] = $valArr;
											}*/
											if($credit_card_sts == 0 && $valArr["pay_mod_id"] !="2") {
												$smpleArr[] = $valArr;
											}
										}
									}
								}
								$gateway_details = (count($passenger_payment_option_array) > 0) ? $passenger_payment_option_array : $gateway_details;

								//to change the payment mode detail if trip fare is zero
								if($total_wallet_amount > $total_fare) {
									$gateway_details[] = array("pay_mod_id"=>"5","pay_mod_name"=>"Wallet","pay_mod_default"=>"1");
								}
								
								//the hours value has been changed to seconds
								$convertSeconds = $waiting_hours * 3600;
								$converthours = floor($convertSeconds / 3600);
								$convertmins = floor(($convertSeconds - ($converthours*3600)) / 60);
								$convertsecs = floor($convertSeconds % 60);
								$waitH = ($converthours < 10) ? '0'.$converthours : $converthours;
								$waitM = ($convertmins < 10) ? '0'.$convertmins : $convertmins;
								$waitS = ($convertsecs < 10) ? '0'.$convertsecs : $convertsecs;
								//$waitingTime = ($waitH != "00") ? $waitH.':'.$waitM.':'.$waitS.' Hours' :  $waitM.':'.$waitS.' Mins';
								//echo $waitH.' '.$waitM.' '.$waitS; exit; 
								if($waitH != "00"){
									$waitingTime = $waitH.':'.$waitM.':'.$waitS.' Hours';
								}else if($waitM != "00"){
									$waitingTime = $waitM.':'.$waitS.' Mins';
								}else if($waitM == "00" && $waitS != "00"){
									$waitingTime = $waitM.':'.$waitS.' Sec';
								}else{
									$waitingTime = '00:00 Sec';
								}
								$detail = array("trip_id" => $trip_id,"pass_id"=>$passengers_id,"distance"=>$total_distance,"trip_fare"=>$trip_fare,"referdiscount"=>$referdiscount,"promo_discount_per"=>$promo_discount,"promodiscount_amount"=>$promodiscount_amount,"passenger_discount"=>$discount_fare,"nightfare_applicable"=>$nightfare_applicable,"nightfare"=>$nightfare,"eveningfare_applicable"=>$evefare_applicable,"eveningfare"=>$eveningfare,"waiting_time"=>$waitingTime,"waiting_cost"=>$waiting_cost,"tax_amount"=>$tax_amount,"subtotal_fare"=>$subtotal_fare,"total_fare"=>$total_fare,"gateway_details"=>$gateway_details,"pickup"=>$pickup,"drop"=>$drop_location,"company_tax"=>$tax,"waiting_per_hour" => $waiting_per_hour, "roundtrip"=> $roundtrip,"minutes_traveled"=>$minutes,"minutes_fare"=>$minutes_cost,"metric"=>UNIT_NAME,"credit_card_status"=>$credit_card_sts,"wallet_amount_used"=>$usedAmount,"base_fare"=>$baseFare,"street_pickup"=>0,"fare_calculation_type"=>FARE_CALCULATION_TYPE);
											
								$message = array("message"=>__('trip_completed_driver'),"detail"=>$detail,"status"=>4);
								//print_r($message);
								//exit;
								/** Send Trip fare details to Driver ***/
								$d_device_token = $get_passenger_log_details[0]->driver_device_token;
								$d_device_type = $get_passenger_log_details[0]->driver_device_type;
								//$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);	
								/** Send Trip fare details to Passenger ***/
								$pushmessage = array("message"=>__('trip_completed'),"status"=>4);
								$p_device_token = $get_passenger_log_details[0]->passenger_device_token;
								$p_device_type = $get_passenger_log_details[0]->passenger_device_type;

								//$p_send_notification = $api->send_passenger_mobile_pushnotification($p_device_token,$p_device_type,$pushmessage,$this->customer_google_api);						
								//$message = $pushmessage;
							}
							else if($travel_status == 1)
							{
								$message = array("message" => __('trip_already_completed'),"status"=>-1);	
							}		
							else
							{
								$message = array("message" => __('trip_not_started'),"status"=>-1);
							}
						}
				}
				else
				{
					$message = array("message" => __('invalid_trip'),"status"=>-1);	
				}
			}
			else
			{
				$message = array("message" => __('invalid_trip'),"status"=>-1);	
			}
		}
		else
		{
			$message = array("message" => __('invalid_request'),"status"=>-1);	
		}
		echo json_encode($message);
		break;
			//http://192.168.1.88:1003/api/index/dGF4aV9hbGw=?type=tripfare_update&trip_id=7&distance=25&actual_distance=&actual_amount=85&trip_fare=&fare=90&tips=0.50&passenger_discount=remarks=test%20driviing&nightfare_applicable=&remarks=&tax_amount=100&waiting_time=1.6&waiting_cost=0.333&minutes_traveled=10&minutes_fare=150&nightfare=&creditcard_no=4024007155409633&creditcard_cvv=567&expmonth=12&expyear=2031&pay_mod_id=1

			//Cash :
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=tripfare_update&trip_id=1252&distance=25&actual_distance=&actual_amount=&trip_fare=120&fare=180.50&tips=10.50&passenger_discount=10&tax_amount=20&remarks=test%20driviing&nightfare_applicable=1&nightfare=20&waiting_time=1.6&waiting_cost=0.50&minutes_traveled=10&minutes_fare=150&creditcard_no=&creditcard_cvv=&expmonth=&expyear=&pay_mod_id=1

			//Card :
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=tripfare_update&trip_id=709&distance=25&actual_distance=&actual_amount=&trip_fare=&fare=90&fare=90&tips=0.50&passenger_discount=&tax_amount=&remarks=test%20driviing&nightfare_applicable=&nightfare=&waiting_time=1.6&waiting_cost=0.333&creditcard_no=4024007155409633&creditcard_cvv=567&expmonth=12&expyear=2031&pay_mod_id=2

			//Uncard :
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=tripfare_update&trip_id=990&distance=25&actual_distance=&actual_amount=&trip_fare=&fare=90&fare=90&tips=0.50&passenger_discount=&tax_amount=&remarks=test%20driviing&nightfare_applicable=&nightfare=&waiting_time=1.6&waiting_cost=0.333&minutes_traveled=10&minutes_fare=150&creditcard_no=4024007155409633&creditcard_cvv=567&expmonth=12&expyear=2031&pay_mod_id=3
			
			//Account :
			//http://192.168.1.73:1013/api/index/bnRheGlfYlVtUzZGMUJMVDY4VTZtWkdYaDNnRFV2WE5BRGo0==/?type=tripfare_update&trip_id=58&distance=25&actual_distance=&actual_amount=&trip_fare=&fare=90&fare=90&tips=0.50&passenger_discount=&tax_amount=&remarks=test%20driviing&nightfare_applicable=&nightfare=&waiting_time=1.6&waiting_cost=0.333&minutes_traveled=10&minutes_fare=150&creditcard_no=4024007155409633&creditcard_cvv=567&expmonth=12&expyear=2031&pay_mod_id=4&group_id=&account_id=
			case 'tripfare_update':
			//$array = $_GET;
			$array = $mobiledata;
			//$driver_model = Model::factory('driver');
			$api_model = Model::factory(MOBILEAPI_107);
			$pay_mod_id = $array['pay_mod_id'];
			if($pay_mod_id == '1' ||  $pay_mod_id == '2' ||  $pay_mod_id == '4' ||  $pay_mod_id == '5')
			{
				$validator = $this->payment_validation($array);
			}
			else
			{
				$validator = $this->payment_validationwith_card($array);
			}
			$driver_statistics=array();
			if($validator->check())
			{
				$passenger_log_id = $array['trip_id'];
				if($array['actual_distance'] == "")
					$distance = $array['distance'];
				else
				$distance = $array['actual_distance'];
				$actual_amount = $array['actual_amount'];
				$remarks = $array['remarks'];
				$minutes_traveled=$array['minutes_traveled'];
				$minutes_fare=$array['minutes_fare'];
				$base_fare=$array['base_fare'];
				$trip_fare = $array['trip_fare']; // Trip Fare without Tax,Tips and Discounts
				$fare = round($array['fare'],2); // Total Fare with Tax,Tips and Discounts can editable by driver
				$tips = round($array['tips'],2); // Tips Optional
				$nightfare_applicable = $array['nightfare_applicable'];
				$nightfare = $array['nightfare'];
				$eveningfare_applicable = $array['eveningfare_applicable'];
				$eveningfare = $array['eveningfare'];
				$tax_amount = $array['tax_amount'];
				$tax_percentage = $array['company_tax'];
				$promodiscount_amount = $array['promodiscount_amount'];
$fare_calculation_type = isset($array['fare_calculation_type']) ? $array['fare_calculation_type'] : FARE_CALCULATION_TYPE;
				// Actual amount means if any deviations in trip fare driver will update it manualy but now this is not required.
				/*if($actual_amount != null)
				{
					$trip_fare = $actual_amount;
				}
				else
				{	
					$trip_fare = $fare;	
				}*/
				$trip_fare = round($trip_fare,2);
				$total_fare = $fare;// + $tips; // Total fare with Tips if exist
				$amount = round($total_fare,2); // Total amount which is used for pass to payment gateways
				$get_passenger_log_details = $api->get_passenger_log_detail($passenger_log_id);
				$pre_transaction_id = "";
				//echo count($get_passenger_log_details);exit;
				if(count($get_passenger_log_details) > 0)
				{
					$promocode = $get_passenger_log_details[0]->promocode;
					$pre_transaction_id = isset($get_passenger_log_details[0]->pre_transaction_id) ? $get_passenger_log_details[0]->pre_transaction_id : "";
					$promodiscount_amount = isset($array['passenger_promo_discount'])?$array['passenger_promo_discount']:"";
					$flag = 1;
					$trans_result = $api_model->check_tranc($passenger_log_id,$flag);
					if($trans_result == 1)
					{
						/********** Update Driver Status after complete Payments *****************/
						$drivers_id = $get_passenger_log_details[0]->driver_id;
						$update_driver_arrary = array("status" => 'F');
						$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$drivers_id);
						/************Update Driver Status ***************************************/
						$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
						$journey = $api->update_journey_status($passenger_log_id,$msg_status,$driver_reply,$journey_status);
						/*************** Update in driver request table ******************/
						$update_trip_array  = array("status"=>'8');
						$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$passenger_log_id);
						/*************************************************************************/	
						if(count($get_passenger_log_details) > 0)
						{
							$default_companyid = isset($get_passenger_log_details[0]->company_id) ? $get_passenger_log_details[0]->company_id : $default_companyid;
							// Driver Statistics ********************/
							$driver_logs_rejected = $api->get_rejected_drivers($drivers_id,$default_companyid);	
							$rejected_trips = count($driver_logs_rejected);	
							$driver_cancelled_trips = $api->get_driver_cancelled_trips($drivers_id,$default_companyid);
							$driver_earnings = $api->get_driver_earnings_with_rating($drivers_id,$default_companyid);
							$driver_tot_earnings = $api->get_driver_total_earnings($drivers_id);
							$driver_statistics = array();
							$total_trip = $trip_total_with_rate = $total_ratings = $today_earnings = $total_amount=0;

							foreach($driver_earnings as $stat){
								$total_trip++;
								$total_ratings += $stat['rating'];
								$total_amount += $stat['total_amount'];
							}
							$overall_trip = $total_trip + $rejected_trips + $driver_cancelled_trips;
							$time_driven = $api->get_time_driven($drivers_id,'R','A','1');
							$driver_statistics = array( 
								"total_trip" => $overall_trip,
								"completed_trip" => $total_trip,
								"total_earnings" => round($driver_tot_earnings,2),
								"overall_rejected_trips" => $rejected_trips,
								"cancelled_trips" => $driver_cancelled_trips,
								"today_earnings"=>round($total_amount,2),
								"shift_status"=>'IN',
								"time_driven"=>$time_driven,
								"status"=> 1
							);
							//$driver_details[0]["driver_statistics"]=$statistics;
							/**************************************************/
						}
						else
						{
							$driver_statistics=array();
						}
						//Driver Statistics Functionality End
						$message = array("message" => __('trip_fare_already_updated'), "status"=>-1);
						$message['driver_statistics']=$driver_statistics;
						echo json_encode($message);
						break;
					}
					//$update_commission = $this->commonmodel->update_commission($passenger_log_id,$total_fare,ADMIN_COMMISSON);
					
					//get_driver_balance
					$trip_array=array();
					$drriver_array['driver_id'] = (isset($get_passenger_log_details[0]->driver_id) && $get_passenger_log_details[0]->driver_id !="")?$get_passenger_log_details[0]->driver_id:0;
					$get_balance = $api->get_driver_balance($drriver_array);
					$account_balance = (isset($get_balance[0]['account_balance']) && $get_balance[0]['account_balance'] !="")?round($get_balance[0]['account_balance'],2):0;
					$alert_message =($account_balance <=0)? "Your credits are too low. You will not receive trip request. Please recharge now!":"";

					if($array['pay_mod_id'] == 1 || $array['pay_mod_id'] == 5)//5 for wallet payment
					{

							/** Referral amount detection if the passenger have amount in their wallet **/
							if($array['pay_mod_id'] == 5) {

							$usedWalAmount = 0;
							$totalFareAmt = 0;
							$passenger_payment_option = 0;
							$show_credit_payment = 1;
							$trip_id = $passenger_log_id;
							$totalPendingPercentage = $api->getpendingFarePercentage($trip_id);
							$splitPassDets = $api->getSplitPassengersDetails($trip_id);
							$siteinfo_details = $api->siteinfo_details();

							if(count($splitPassDets) > 0) {
								foreach($splitPassDets as $splitPass) {
									$farePercentage = ($splitPass['primary_pass_id'] == $splitPass['passenger_id']) ? ($splitPass['fare_percentage'] + $totalPendingPercentage) : $splitPass['fare_percentage'];
									$splittedFare = ($total_fare * $farePercentage) / 100;
									$splTotfare = round($splittedFare, 2);
									if($splitPass["approve_status"] == "A") {
										list($actusedWalAmount,$actTotalFare) = $this->deductWalletAmount($splitPass['passenger_id'],$splitPass['wallet_amount'],$siteinfo_details[0]['referral_settings'],$splTotfare,$usedWalAmount,$trip_id);
									} else {
										// Secondary passenger percent & amount to Zero //
										if($splitPass['primary_pass_id'] != $splitPass['passenger_id']) {
											$api->updateSecondaryPercentAmtPrimary($trip_id,$splitPass['passenger_id'],$splitPass['primary_pass_id']);
											$api->updatePassengerPercentAmt($trip_id,$splitPass['primary_pass_id'],1);
										}
										$actusedWalAmount = $actTotalFare = 0;
									}
									//update used wallet amount in passenger split details
									$updateUsedWalAmountArr = array("used_wallet_amount" => $actusedWalAmount);
									$api->updateUsedWalletAmount($updateUsedWalAmountArr,$splitPass['passenger_id'],$trip_id);

								}
							}


							}
							/** Referral amount detection if the passenger have amount in their wallet **/

						/** Return payment using payment gateway **/
						if($array['pay_mod_id'] == 5 && $pre_transaction_id != "") {
							$paypal_details = $api->payment_gateway_details();
							$payment_gateway_username = isset($paypal_details[0]['payment_gateway_username'])?$paypal_details[0]['payment_gateway_username']:"";
							$payment_gateway_password = isset($paypal_details[0]['payment_gateway_password'])?$paypal_details[0]['payment_gateway_password']:"";
							$payment_gateway_key = isset($paypal_details[0]['payment_gateway_key'])?$paypal_details[0]['payment_gateway_key']:"";
							$currency_format = isset($paypal_details[0]['gateway_currency_format'])?$paypal_details[0]['gateway_currency_format']:"";
							$payment_method = ($paypal_details[0]['payment_method'] == "L") ? "live" : "sandbox";
							$payment_type = (isset($paypal_details[0]['payment_type']) && $paypal_details[0]['payment_type'] == 1) ? 1 : 2;
							/** Paypal **/
							if($payment_type == 1) {
								$this->voidTransaction($pre_transaction_id,$payment_method,$payment_gateway_username,$payment_gateway_password,$payment_gateway_key);
							}
							/** Brain Tree **/
							if($payment_type == 2) {
								$product_title = Html::chars('Complete Trip');
								$payment_action='sale';
								//require_once DOCROOT.'braintree-payment/lib/Braintree.php';
								require_once(APPPATH.'vendor/braintree-payment/lib/Braintree.php');
								if ($payment_method=="live") {
									Braintree_Configuration::environment('production');
								} else {
									Braintree_Configuration::environment('sandbox');
								}
								//config access
								Braintree_Configuration::merchantId($payment_gateway_username);//your_merchant_id
								Braintree_Configuration::publicKey($payment_gateway_password);//your_public_key
								Braintree_Configuration::privateKey($payment_gateway_key);//your_private_key
								$result = Braintree_Transaction::void($pre_transaction_id);
							}
						}
							//Inserting to Transaction Table 
							try {
								//$passenger_log_id = $passenger_log_id;
								//$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
								//$journey = $driver_model->update_journey_status($passenger_log_id,$msg_status,$driver_reply,$journey_status);
								
								$update_commission = $this->commonmodel->update_commission($passenger_log_id,$total_fare,ADMIN_COMMISSON);
								
								$insert_array = array(
									"passengers_log_id" => $passenger_log_id,
									"distance" 			=> urldecode($array['distance']),
									"actual_distance" 	=> urldecode($array['actual_distance']),
									"distance_unit" 	=> UNIT_NAME,
									"tripfare"			=> $trip_fare,
									"fare" 				=> $fare,
									"tips" 				=> $tips,
									"waiting_cost"		=> $array['waiting_cost'],
									"passenger_discount"=> $array['passenger_discount'],
									"promo_discount_fare"=> $promodiscount_amount,
									"tax_percentage"=> $tax_percentage,
									"company_tax"		=> $tax_amount,
									"waiting_time"		=> urldecode($array['waiting_time']),
									"trip_minutes"		=> $minutes_traveled,
									"minutes_fare"		=> $minutes_fare,
									"base_fare"		=> $base_fare,
									"remarks"			=> $remarks,
									"payment_type"		=> $array['pay_mod_id'],
									"amt"				=> $amount,
									"nightfare_applicable" => $nightfare_applicable,
									"nightfare" 		=> $nightfare,
									"eveningfare_applicable" => $eveningfare_applicable,
									"eveningfare" 		=> $eveningfare,
									"admin_amount"		=> $update_commission['admin_commission'],
									"company_amount"	=> $update_commission['company_commission'],
									"driver_amount"	=> $update_commission['driver_commission'],
									"trans_packtype"	=> $update_commission['trans_packtype'],
									"fare_calculation_type"	=> $fare_calculation_type
								);
								$check_trans_already_exist = $api->checktrans_details($passenger_log_id);
								if(count($check_trans_already_exist)>0)
								{
									$tranaction_id = $check_trans_already_exist[0]['id'];
									$update_transaction = $api->update_table(TRANS,$insert_array,'id',$tranaction_id);
									$jobreferral = $tranaction_id;
								}
								else
								{
									$transaction = $this->commonmodel->insert(TRANS,$insert_array);
									$jobreferral = $transaction[0];
								}
								/********** Update Driver Status after complete Payments *****************/
								$drivers_id = $get_passenger_log_details[0]->driver_id;

								$update_driver_arrary = array("status" => 'F');
								$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$drivers_id);
								/************Update Driver Status ***************************************/
								/*************** Update in driver request table ******************/
								$update_trip_array  = array("status"=>'8');
								$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$passenger_log_id);
								/*************************************************************************/
								$pickup = $get_passenger_log_details[0]->current_location;
								
								if(SMS == 1)
								{
									$passenger_phone_no = $get_passenger_log_details[0]->passenger_country_code.$get_passenger_log_details[0]->passenger_phone;
									$message_details = $this->commonmodel->sms_message_by_title('payment_confirmed_sms');
									if(count($message_details) > 0) {
										$to = $passenger_phone_no;
										$message = $message_details[0]['sms_description'];
										$message = str_replace("##booking_key##",$passenger_log_id,$message);
										$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
										$this->commonmodel->send_sms($to,$message);
									}
								}
								$driver_profiles = $api->driver_profile($drivers_id);
								$account_balance=isset($driver_profiles[0]['account_balance'])?$driver_profiles[0]['account_balance']:'0';
								$detail = array("fare" => $amount,"pickup" => $pickup,"jobreferral"=>$jobreferral,"trip_id"=>$passenger_log_id);
								$message = array("message" => __('trip_fare_updated'),"detail"=>$detail,"status"=>1,'account_balance'=>$account_balance,'alert_message'=>$alert_message);
								$pushmessage = array("message" => __('trip_fare_updated'),"fare" => $amount,"trip_id"=>$passenger_log_id,"pickup" => $pickup, "status"=>5);


								/* Adding amout to driver wallet for pay by wallet N0592 12-5-2017 */
								if(isset($array['pay_mod_id']) && $array['pay_mod_id'] == 5)
								{ 
									$trip_wallet = isset($driver_profiles[0]['trip_wallet'])?$driver_profiles[0]['trip_wallet']:'0';
									
									$trip_wallet = $trip_wallet + $fare;
									$trip_wallet_arrary = array("trip_wallet" => $trip_wallet);
									$trip_wallet_result = $api->update_table(PEOPLE,$trip_wallet_arrary,'id',$drivers_id);
									$current_time =	date('Y-m-d H:i:s');
									if($trip_wallet_result){
										$driver_wallet_trip_array = array("driver_id" => $drivers_id,"trip_id" => $passenger_log_id,'amount'=>$fare,'type'=>2,'date'=>$current_time);
										$this->commonmodel->insert(DRIVER_WALLET_TRANS,$driver_wallet_trip_array);
										
									}
								}

								/* Adding amout to driver wallet for pay by wallet N0592 12-5-2017 */

								//print_r($pushmessage);
								//exit;
								//$message = $pushmessage;
								$send_mail_status = $this->send_mail_passenger($passenger_log_id,1);
							}
							catch (Kohana_Exception $e) {
								$message = array("message" => __('trip_fare_already_updated'), "status"=>-1);
							}
					}
					else if($array['pay_mod_id'] == 2)
					{
						//$passenger_cardid = $array['passenger_cardid'];
						//$carddetails = $api->getcard_details($passenger_cardid);
						$passengers_id = $get_passenger_log_details[0]->passengers_id;
						$card_type = '';
						$default = 'yes';
						$carddetails = $api->get_creadit_card_details($passengers_id,$card_type,$default);
						 if(count($carddetails)>0)
						 {
							$creditcard_no = encrypt_decrypt('decrypt',$carddetails[0]['creditcard_no']);
							//$creditcard_cvv = $array['creditcard_cvv'];
							$creditcard_cvv = 456;
							$expmonth = $carddetails[0]['expdatemonth'];
							$expyear = $carddetails[0]['expdateyear'];
							
							if($creditcard_no != "")
							{

								$payment_status = $this->trippayment($array,$default_companyid);//$account_id
								if($payment_status == 0)
								{
									$gateway_response = isset($_SESSION['paymentresponse']['L_LONGMESSAGE0'])?$_SESSION['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
									$message = array("message" => $gateway_response, "gateway_response" =>$gateway_response,"status"=>0);		
								}				
								else if($payment_status == 3)
								{
									$message = array("message" => __('gve_credit_card_details'), "status"=>-2);		
								}
								else if($payment_status == 1)
								{
									$tranaction_id = "";
									$check_trans_already_exist = $api->checktrans_details($passenger_log_id);
									if(count($check_trans_already_exist)>0)
									{
										$tranaction_id = $check_trans_already_exist[0]['id'];
									}
										$jobreferral = $tranaction_id;
										$pickup = $get_passenger_log_details[0]->current_location;
										$detail = array("fare" => $amount,"pickup" => $pickup,"jobreferral"=>$jobreferral,"trip_id"=>$passenger_log_id);
										$message = array("message" => __('trip_fare_updated'), "detail" => $detail,"status"=>1,'account_balance'=>$account_balance,'alert_message'=>$alert_message);	
										$pushmessage = array("message" => __('trip_fare_updated'),"fare" => $amount,"trip_id"=>$passenger_log_id,"pickup" => $pickup, "status"=>5);
										/*************** Update in driver request table ******************/
										$update_trip_array  = array("status"=>'8');
										$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$passenger_log_id);		
										/*************************************************************************/										
										//$send_mail_status = $this->send_mail_passenger($passenger_log_id,1);
								}
								else if($payment_status == -1)
								{
									$message = array("message" => __('invalid_trip'),"status"=>-1);	
								}
								else if($payment_status == 7)
								{
									$message = array("message" => __('no_payment_gateway'),"status"=>-1);	
								}
							}
							else
							{
								$message = array("message" => __('no_creditcard'),"status"=>-9);
							} 
						 }		
						 else
						 {			 								
							 $message = array("message" => __('no_card'),"status"=>-9);
						 }
					}
					else if($array['pay_mod_id'] == 3)
					{
						$creditcard_no = $array['creditcard_no'];
						$creditcard_cvv = $array['creditcard_cvv'];
						$expmonth = $array['expmonth'];
						$expyear = $array['expyear'];
						$authorize_status =$api->isVAlidCreditCard($creditcard_no,"",true);
						
						if($authorize_status == 1)
						{
							$payment_status = $this->trippayment($array,$default_companyid);//$account_id
							if($payment_status == 0)
							{
								$gateway_response = isset($_SESSION['paymentresponse']['L_LONGMESSAGE0'])?$_SESSION['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
								$message = array("message" => $gateway_response, "gateway_response" =>$gateway_response,"status"=>0);		
							}				
							else if($payment_status == 3)
							{
								$message = array("message" => __('gve_credit_card_details'), "status"=>-2);		
							}
							else if($payment_status == 1)
							{
								$tranaction_id = "";
								$check_trans_already_exist = $api->checktrans_details($passenger_log_id);
								if(count($check_trans_already_exist)>0)
								{
									$tranaction_id = $check_trans_already_exist[0]['id'];
								}
								
								$jobreferral = $tranaction_id;
								$pickup = $get_passenger_log_details[0]->current_location;
								$detail = array("fare" => $amount,"pickup" => $pickup,"jobreferral"=>$jobreferral,"trip_id"=>$passenger_log_id);
								$message = array("message" =>  __('trip_fare_updated'), "detail" => $detail,"status"=>1,'account_balance'=>$account_balance,'alert_message'=>$alert_message);	
								$pushmessage = array("message" => __('trip_fare_updated'),"fare" => $amount,"trip_id"=>$passenger_log_id,"pickup" => $pickup, "status"=>5);
								/*************** Update in driver request table ******************/
								$update_trip_array  = array("status"=>'8');
								$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$passenger_log_id);		
								/*************************************************************************/								
								/** Send Trip fare details to Driver ***/
								$d_device_token = $get_passenger_log_details[0]->driver_device_token;
								$d_device_type = $get_passenger_log_details[0]->driver_device_type;
								//$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);	
								/** Send Trip fare details to Passenger ***/
								$p_device_token = $get_passenger_log_details[0]->passenger_device_token;
								$p_device_type = $get_passenger_log_details[0]->passenger_device_type;
								//$p_send_notification = $api->send_passenger_mobile_pushnotification($p_device_token,$p_device_type,$pushmessage,$this->customer_google_api);	
								//$send_mail_status = $this->send_mail_passenger($passenger_log_id,1);
							}
							else if($payment_status == -1)
							{
								$message = array("message" => __('invalid_trip'),"status"=>-1);	
							}
						}
						else
						{
							$message = array("message" => __('invalid_card'),"status"=>-9);
						}
					}
					else if($array['pay_mod_id'] == 4)
					{
							//$account_id = $get_passenger_log_details[0]->account_id;
							//$accgroup_id = $get_passenger_log_details[0]->accgroup_id;
							
							//Account of Selected Passenger
							$account_id = $array['account_id'];
							$accgroup_id = $array['group_id'];
							

							if($account_id != 0)
							{
								$account_details = $api->get_account_discount($account_id);
								//print_r($get_passenger_profile);
								//print_r($account_discount);
								if(count($account_details)>0)
								{
									$account_holder_id = $account_details[0]['passid'];
									$get_passenger_profile = $api_model->passenger_profile($account_holder_id,'A');							
									$account_holder_status = $get_passenger_profile[0]['user_status'];
									if($account_holder_status == 'A')
									{
										$card_type = '';
										$default = 'yes';
										$carddetails = $api->get_creadit_card_details($account_holder_id,$card_type,$default);
										//echo $creditcard_no;exit;
										if(count($carddetails) > 0)
										{									
											$account_limit=$grp_limit="";//exit;
											$total_used_limit=$bal_limit=0;
											$account_limit = $account_details[0]['limit'];															
											$grouplimit = $api->get_groupdetails($accgroup_id);									
											if(count($grouplimit) > 0)
											{
												$grp_limit = $grouplimit[0]['limit'];
												if($grp_limit > 0)
												{
													$passenger_id_array = explode(',',$grouplimit[0]['passenger_id']);
													
													foreach($passenger_id_array as $passenger_id)
													{
														$bal_account_limit = $api->check_used_limit($passenger_id);
														if(count($bal_account_limit)>0)
														{
															if($bal_account_limit[0]['total_used_limit'] >0)
															{															
																$total_used_limit += $bal_account_limit[0]['total_used_limit'];
															}
														}	
													}	
													$bal_limit = $grp_limit - $total_used_limit;

													if($bal_limit > $amount)
													{
														//Inserting to Transaction Table 
															try 
															{
																$array['account_holder_id']=$account_holder_id;
																$payment_status = $this->trippayment($array,$default_companyid);//$account_holder_id			
																if($payment_status == 0)
																{
																	$gateway_response = isset($_SESSION['paymentresponse']['L_LONGMESSAGE0'])?$_SESSION['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
																	$message = array("message" => $gateway_response, "gateway_response" =>$gateway_response,"status"=>0);		
																}				
																else if($payment_status == 3)
																{
																	$message = array("message" => __('gve_credit_card_details'), "status"=>-2);		
																}
																else if($payment_status == 1)
																{
																	$tranaction_id = "";
																	$check_trans_already_exist = $api->checktrans_details($passenger_log_id);
																	if(count($check_trans_already_exist)>0)
																	{
																		$tranaction_id = $check_trans_already_exist[0]['id'];
																	}
																	$pickup = $get_passenger_log_details[0]->current_location;
																	$jobreferral = $tranaction_id;
																	$detail = array("fare" => $amount,"pickup" => $pickup,"jobreferral"=>$jobreferral,"trip_id"=>$passenger_log_id);
																	$message = array("message" =>  __('trip_fare_updated'), "detail" => $detail,"driver_statistics"=>$driver_statistics,"status"=>1,'account_balance'=>$account_balance,'alert_message'=>$alert_message);	
																	$pushmessage = array("message" => __('trip_fare_updated'),"trip_id"=>$passenger_log_id,"fare" => $amount,"pickup" => $pickup, "status"=>5);
																	/*************** Update in driver request table ******************/
																	$update_trip_array  = array("status"=>'8');
																	$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$passenger_log_id);		
																	/*************************************************************************/																	
																	/** Send Trip fare details to Driver ***/
																	$d_device_token = $get_passenger_log_details[0]->driver_device_token;
																	$d_device_type = $get_passenger_log_details[0]->driver_device_type;
																	//$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);	
																	/** Send Trip fare details to Passenger ***/
																	$p_device_token = $get_passenger_log_details[0]->passenger_device_token;
																	$p_device_type = $get_passenger_log_details[0]->passenger_device_type;
																	//$p_send_notification = $api->send_passenger_mobile_pushnotification($p_device_token,$p_device_type,$pushmessage,$this->customer_google_api);	
																	$send_mail_status = $this->send_mail_passenger($passenger_log_id,1);
																}
																else if($payment_status == -1)
																{
																	$message = array("message" => __('invalid_trip'),"status"=>-1);	
																}											
															}
															catch (Kohana_Exception $e) 
															{
																$message = array("message" =>__('trip_fare_already_updated'), "status"=>-1);			
															}
													}
													else
													{
														$message = array("message" => __('no_sufficient_credits'),"status"=>-12);
													}
												}
												else
												{
													$message = array("message" => __('no_credits_in_account'),"status"=>-11);
												}
											}
											else
											{
												$message = array("message" => __('group_account_deactive'),"status"=>-10);
											}
										}
										else
										{
											$message = array("message" => __('no_creditcard'),"status"=>-9);
										}
									}
									else
									{
										$message = array("message" => __('account_holder_deactive'),"status"=>-8);
									}
								}
								else
								{
									$message = array("message" => __('account_deactive'),"status"=>-7);
								}
							}
							else
							{
								$message = array("message" => __('no_account'),"status"=>-6);								
							}
							
					}
					
					//Driver Statistics Functionality Start
					$driver_id = $get_passenger_log_details[0]->driver_id;
					$default_companyid = isset($get_passenger_log_details[0]->company_id) ? $get_passenger_log_details[0]->company_id : $default_companyid;
					// Driver Statistics ********************/
					$driver_logs_rejected = $api->get_rejected_drivers($driver_id,$default_companyid);	
					$rejected_trips = count($driver_logs_rejected);	
					$driver_cancelled_trips = $api->get_driver_cancelled_trips($driver_id,$default_companyid);
					$driver_earnings = $api->get_driver_earnings_with_rating($driver_id,$default_companyid);
					$statistics = array();
					$total_trip = $trip_total_with_rate = $total_ratings = $today_earnings = $total_amount=0;
					foreach($driver_earnings as $stat){
							$total_trip++;
							$total_ratings += $stat['rating'];
							$total_amount += $stat['total_amount'];											
					}
					
					$overall_trip = $total_trip + $rejected_trips + $driver_cancelled_trips;													
					$time_driven = $api->get_time_driven($driver_id,'R','A','1');	
					$driver_statistics = array( 
									"total_trip" => $overall_trip,
									"completed_trip" => $total_trip,
									"total_earnings" => round($total_amount,2),
									"overall_rejected_trips" => $rejected_trips,
									"cancelled_trips" => $driver_cancelled_trips,
									"today_earnings"=>round($total_amount,2),											
									"shift_status"=>'IN',
									"time_driven"=>$time_driven,
									"status"=> 1
											  );
					/**************************************************/
					
				}
				else
				{
					$message = array("message" => __('invalid_trip'),"status"=>-1);
				}
			}
			else
			{
					$validation_error = $validator->errors('errors');	
					$message = array("message" => $validation_error,"status"=>-3);						
			}
						
					//Driver Statistics Functionality End
			$message['driver_statistics']=$driver_statistics;
			echo json_encode($message);
			break;			
			/*END OF DRIVER DETAILS*/

			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=?type=cancel_trip&passenger_log_id=48&travel_status=4&remarks=test driviing&pay_mod_id=2&creditcard_cvv=

			case 'cancel_trip':			
			$driver_model = Model::factory('driver');
			$api_model = Model::factory(MOBILEAPI_107);	
			$cancel_trip_array = ($mobiledata) ? $mobiledata : $_POST;		
			$passenger_log_id = $cancel_trip_array['passenger_log_id'];
			$remarks = urldecode($cancel_trip_array['remarks']);

			$check_travelstatus = $api_model->check_travelstatus($passenger_log_id);
			if($check_travelstatus == -1)
			{
				$message = array("message" => __('invalid_trip'),"status"=>3);
				echo json_encode($message);
				break;
			}
			if($check_travelstatus == 4)
			{
				$message = array("message" => __('trip_already_canceled'), "status"=>-1);
				echo json_encode($message);
				break;
			}			
			if($check_travelstatus == 2)
			{
				$message = array("message" => __('passenger_in_journey'), "status"=>-1);
				echo json_encode($message);
				break;
			}
			
			$flag = 1;
			$trans_result = $api_model->check_tranc($passenger_log_id,$flag);
			if($trans_result == 1)
			{
				$message = array("message" => __('trip_fare_already_updated'), "status"=>-1);
				echo json_encode($message);
				break;
			}

				if($cancel_trip_array['passenger_log_id'] != null)
				{
					$get_passenger_log_det = $api_model->get_passenger_log_detail($passenger_log_id);
					$driver_id = $get_passenger_log_det[0]->driver_id;
					$passenger_id = $get_passenger_log_det[0]->passengers_id;
					$passenger_name = $get_passenger_log_det[0]->passenger_name;
					$passenger_email = $get_passenger_log_det[0]->passenger_email;
					$pickup_location = $get_passenger_log_det[0]->current_location;
					$is_split_trip = $get_passenger_log_det[0]->is_split_trip;
					$wallet_amount = $get_passenger_log_det[0]->wallet_amount;
					$cancel_trip_array['company_id'] = $get_passenger_log_det[0]->company_id;
					
					$cancellation_nfree = (FARE_SETTINGS == 2) ? $get_passenger_log_det[0]->cancellation_nfree : CANCELLATION_FARE;
					
					$status = "F";
					if(!empty($driver_id))
						$result = $api_model->update_driver_status($status,$driver_id);
						
					if($cancellation_nfree == 0 || empty($driver_id))
					{
						 if(SMS == 1 && !empty($passenger_id))
						{
							$phone_no=$api->get_passenger_phone_by_id($passenger_id);
							$message_details = $this->commonmodel->sms_message_by_title('trip_cancel');
							if(count($message_details) > 0) {
								$to = $phone_no;
								$message = $message_details[0]['sms_description'];
								//$message = str_replace("##OTP##",$otp,$message);
								$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
								$this->commonmodel->send_sms($to,$message);
							}
						}
						$payment_types=0;
						$transaction_detail=$api_model->cancel_triptransact_details($cancel_trip_array,$cancellation_nfree,$payment_types,$driver_id);
							$pushmessage = array("message"=>__('trip_cancelled_passenger'), "status"=>2);
							$d_device_token = $get_passenger_log_det[0]->driver_device_token;
							$d_device_type = $get_passenger_log_det[0]->driver_device_type;
							//$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);
						$message = array("message" => __('trip_cancel_passenger'),"cancellation_from"=> __('Free'),"cancellation_amount"=> 0, "status"=>2);	//with out cancellation fee
						echo json_encode($message);
					}
					else
					{
						$total = $api_model->get_passenger_cancel_faredetail($passenger_log_id);
						$passengerReferrDet = $api->check_passenger_referral_amount($passenger_id);
						$referralAmt = (isset($passengerReferrDet[0]['referral_amount'])) ? $passengerReferrDet[0]['referral_amount'] : 0;
						$reducAmt = ($referralAmt != 0) ? ($wallet_amount - $referralAmt) : $wallet_amount;
						//echo $passenger_id.">".$passenger_wallet[0]['wallet_amount'].">". $total;exit;
						if($cancel_trip_array['pay_mod_id'] == 3 || ($wallet_amount > 0 && $reducAmt >= $total)) // By cash
						{
							/*$get_passenger_log_details = $driver_model->get_passenger_log_details($passenger_log_id);
							if(count($get_passenger_log_details) > 0)
							{*/
								//Inserting to Transaction Table
								try {
									$siteinfo_details = $api_model->siteinfo_details();
									$update_commission = $this->commonmodel->update_commission($passenger_log_id,$total,$siteinfo_details[0]['admin_commission']);
									$total = (empty($total)) ? 0 : $total;
									$insert_array = array(
										"passengers_log_id" => $passenger_log_id,
										"remarks"		=> $remarks,
										"payment_type"		=> $cancel_trip_array['pay_mod_id'],
										"amt"			=> $total,
										"fare"			=> $total,
										"admin_amount"		=> $update_commission['admin_commission'],
										"company_amount"	=> $update_commission['company_commission'],
										"trans_packtype"	=> $update_commission['trans_packtype']
									);

									$transaction = $this->commonmodel->insert(TRANS,$insert_array);
									
									$update_travel_status_array  = array("travel_status" => '4'); // Passenger Cancelled
									$result_sts_update = $api->update_table(PASSENGERS_LOG,$update_travel_status_array,'passengers_log_id',$passenger_log_id);
									/*$status_array  = array("status" => '4'); // Passenger Cancelled
									$result_sts_update = $api->update_table(DRIVER_REQUEST_DETAILS,$status_array,'trip_id',$passenger_log_id);*/
									$cancel_from = __('Cash');
									//to reduce the wallet amount while cancelling the trip
									if($wallet_amount >= $total){
										$balance_wallet_amount = $wallet_amount - $total;
										//update wallet amount in passenger table
										$update_wallet_array = array("wallet_amount" => $balance_wallet_amount);
										$wallet_update = $api->update_table(PASSENGERS,$update_wallet_array,'id',$passenger_id);
										$cancel_from = __('Wallet');
									}
									
									if(SMS == 1 && !empty($passenger_id))
									{
										$phone_no=$api->get_passenger_phone_by_id($passenger_id);
										$message_details = $this->commonmodel->sms_message_by_title('trip_cancel');
										if(count($message_details) > 0) {
											$to = $phone_no;
											$message = $message_details[0]['sms_description'];
											//$message = str_replace("##OTP##",$otp,$message);
											$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
											$this->commonmodel->send_sms($to,$message);
										}
									}
									$pushmessage = array("message"=>__('trip_cancelled_passenger'), "status"=>2);
									$d_device_token = $get_passenger_log_det[0]->driver_device_token;
									$d_device_type = $get_passenger_log_det[0]->driver_device_type;
									//$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);
									$message = array("message" => __('trip_cancel_passenger'),"cancellation_from"=> $cancel_from,"cancellation_amount"=> $total, "status"=>1);
									//$send_mail_status = $this->send_mail_passenger($passenger_log_id,4);
								}
									catch (Kohana_Exception $e) {
										//print_r($e);exit;
									$message = array("message" => __('try_again'), "status"=>3);
								}
							/*}
							else
							{
									$message = array("message" => __('invalid_trip'),"status"=>3);	
							} */
							echo json_encode($message);
						}
						else
						{
							$card_type = '';
							$default = 'yes';
							$carddetails = $api->get_creadit_card_details($passenger_id,$card_type,$default);
							$no_default_card = $api->get_creadit_card_details($passenger_id,$card_type,"");
							//echo count($no_default_card);exit;
							 if(count($carddetails)>0)
							 {
								$payment_status = $this->cancel_trippayment($cancel_trip_array,$cancellation_nfree,$default_companyid);
								//echo $payment_status;exit;
								$cancelArr = ($payment_status != 0) ? explode("#",$payment_status):'';
								$payment_status = isset($cancelArr[0]) ? $cancelArr[0] : 0;
								$cancelAmount = isset($cancelArr[1]) ? $cancelArr[1] : 0;
								if($payment_status == 0)
								{
									$gateway_response = isset($_SESSION['paymentresponse']['L_LONGMESSAGE0'])?$_SESSION['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
									$message = array("message" => __('cancel_payment_failed'), "gateway_response" =>$gateway_response,"status"=>0);		
									echo json_encode($message);
									break;
								}
								else if($payment_status == 1)
								{
									if(SMS == 1 && !empty($passenger_id))
									{
										$phone_no=$api->get_passenger_phone_by_id($passenger_id);
										$message_details = $this->commonmodel->sms_message_by_title('trip_cancel');
										if(count($message_details) > 0) {
											$to = $phone_no;
											$message = $message_details[0]['sms_description'];
											//$message = str_replace("##OTP##",$otp,$message);
											$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
											$this->commonmodel->send_sms($to,$message);
										}
									}
									$message = array("message" => __('trip_cancel_passenger'),"cancellation_from"=> __('credit_card'),"cancellation_amount"=> $cancelAmount, "status"=>1);
									$pushmessage = array("message"=>__('trip_cancelled_passenger'), "status"=>2);
									$d_device_token = $get_passenger_log_det[0]->driver_device_token;
									$d_device_type = $get_passenger_log_det[0]->driver_device_type;
									//$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);
									$send_mail_status = $this->send_cancel_fare_mail_passenger($cancelAmount, $passenger_name, $pickup_location, $passenger_email);
									echo json_encode($message);				
								}
								else if($payment_status == -1)
								{
									$message = array("message" => __('invalid_trip'),"status"=>3);	
									echo json_encode($message);
									break;
								}
							} else if (count($carddetails) == 0 && count($no_default_card) > 0) {
								$message = array("message" => __('passenger_has_no_default_creditcard'),"status"=>5);	
								echo json_encode($message);		
								break;	
									
							} else {
									$message = array("message" => __('cancel_no_creditcard'),"status"=>4);	
									echo json_encode($message);		
									break;				
							}
						}
					}
				}
				else
				{
					$message = array("message" => __('invalid_trip'),"status"=>3);	
					echo json_encode($message);
					break;
				}				
				//echo json_encode($message);
			break;												
				/** driver document upload api ***/
			//http://192.168.1.82:1023/mobileapi109/index/dGF4aV9hbGw=/?type=driver_document_upload, method = post, driver_id,driver_document,device_type
			case 'driver_document_upload':
				$p_personal_array = $mobiledata;				
					//for ios
					if($p_personal_array['driver_document'] != NULL)
					{						
						$dirname = $p_personal_array['driver_id'];						
						$imgdata = base64_decode($p_personal_array['driver_document']);
						$f = finfo_open();
						$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
						//echo '<img src="data:image/jpg;base64,' . $p_personal_array['driver_document'].'" />';
						//print_r($mime_type);
						//exit;
						$mime_type = explode('/',$mime_type);
						$mime_type = $mime_type[1];
						$img = imagecreatefromstring($imgdata); 							
							if($img != false)
							{                   
								$image_name = uniqid().'.'.$mime_type;
								$thumb_image_name = 'thumb_'.$image_name;			
								$image_path = DOCROOT.PUBLIC_UPLOADS_FOLDER.'/'.$image_name; 					
								$image_url = DOCROOT.PUBLIC_UPLOADS_FOLDER.'/'.$image_name;                    								
								//header('Content-Type: image/jpeg');					
								//$image_path = DOCROOT.PUBLIC_UPLOADS_FOLDER.'/'.$image_name; 
								//echo  $image_path;exit;
								imagejpeg($img,$image_url);
								imagedestroy($img);
								chmod($image_path,0777);
								$d_image = Image::factory($image_path);
								$foldername = DOCROOT.PUBLIC_UPLOADS_FOLDER."/driver_documents/".$dirname."/";

								if (!file_exists($foldername)) {
									mkdir(DOCROOT.PUBLIC_UPLOADS_FOLDER."/driver_documents/".$dirname, 0777);
								}
								//function called to unlink previous files from the folder
								$api->previous_files_unlink($foldername);
								Commonfunction::imageresize($d_image,DRIVER_DOC_IMG_WIDTH, DRIVER_DOC_IMG_HEIGHT,$foldername,$image_name,90);
								chmod($foldername,0777);
								unlink($image_path);
								$message = array("message" => __('file_upload_success'),"status"=>1);
							} else {
								$message = array("message" => __('image_not_upload'),"status"=>-1);
							}
					} else {
						$message = array("message" => __('image_not_upload'),"status"=>-1);
					} 				
				echo json_encode($message);
				exit;
		

			/* start of Passenger Forgot Password */		
		//URL : api/?type=forgot_password&phone_no=9999999999&user_type=P
		case 'forgot_password':												
		$array_values = $mobiledata;
		$message="";
						if($array_values['user_type'] == 'P')
						{
							$check_fb_user = $api->check_fb_user($array_values['phone_no'],$default_companyid,$array_values['country_code']);
							if($check_fb_user > 0){
								$message = array("message" => __('fb_user'),'status' => 3);
								echo json_encode($message);
								break;
							}
							$phone_exist = $api->check_phone_passengers($array_values['phone_no'],$default_companyid,$array_values['country_code']);
							$phone_no = $array_values['country_code'].'-'.$array_values['phone_no'];
						}
						else
						{
							$phone_exist = $api->check_phone_people($array_values['phone_no'],'D',$default_companyid);
							$phone_no = $array_values['phone_no'];
						}
						if($phone_exist > 0)
						{
							$forgot_result = $api->get_passenger_details_phone($array_values,$default_companyid);
							if(count($forgot_result) > 0) 
							{ 	
				                /*$mail="";
								if($array_values['user_type'] == 'P') {
									$replace_variables= array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$forgot_result[0]['name'],REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,SITE_DESCRIPTION=>$this->app_description,RESET_LINK=>URL_BASE.'passengers/resetpassword/?phone_no='.$array_values['phone_no'].'&activation_key='.$forgot_result[0]['activation_key'],REPLACE_COMPANYDOMAIN=>$this->domain_name,REPLACE_COPYRIGHTS=>COMPANY_COPYRIGHT);
								}
								else
								{
									$replace_variables= array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$forgot_result[0]['name'],REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,SITE_DESCRIPTION=>$this->app_description,RESET_LINK=>URL_BASE.'driver/resetpassword/?phone_no='.$array_values['phone_no'],REPLACE_COMPANYDOMAIN=>$this->domain_name,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
								}								
								/*Added for language email template *
								if($this->lang!='en'){
									if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/reset-forgotpassword-'.$this->lang.'.html')){
										$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/reset-forgotpassword-'.$this->lang.'.html',$replace_variables);
									}else{
										$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'reset-forgotpassword.html',$replace_variables);
									}
								}else{
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'reset-forgotpassword.html',$replace_variables);
								}
								/* Added for language email template */
								/**To generate random key if user enter email at forgot password**/
								$random_key = text::random($type = 'alnum', $length = 7);
								//function to update new password
								$newPassUpdate = $api->new_password_update($array_values,$random_key,$default_companyid);				
								$email = $forgot_result[0]['email'];
								$replace_variables=array(REPLACE_LOGO=>URL_BASE.SITE_LOGO_IMGPATH.$this->domain_name.'_email_logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>ucfirst($forgot_result[0]['name']),REPLACE_MOBILE=>$phone_no,REPLACE_PASSWORD=>$random_key,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE,SITE_DESCRIPTION=>$this->app_description,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'user-forgotpassword.html',$replace_variables);
								if($this->lang!='en'){
									if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/user-forgotpassword-'.$this->lang.'.html')){
										$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/user-forgotpassword-'.$this->lang.'.html',$replace_variables);
									}else{
										$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'user-forgotpassword.html',$replace_variables);
									}
								}else{
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'user-forgotpassword.html',$replace_variables);
								}

								//$to = $email.',mahes@taximobility.com';//for cc to maheswaran if client goes to forgot password only in live
								$to = $email;
								$from = $this->siteemail;
								$subject = __('forgot_password_subject')." - ".$this->app_name;	
								$redirect = "no";
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
								$message = array("message" => __('forgot_pass_success').' Your password is '.$random_key,'status' => 1);
							}
							else
							{
								$message = array("message" => __('invalid_user'),'status' => 2);
							}
	
						}
						else
						{
							$message = array("message" => __('invalid_user'),"status"=> 2);							
						}					
								
					echo json_encode($message);
					break;
					
					
				case 'call_response':
					require_once(DOCROOT.'application/vendor/smsgateway/Services/Twilio.php');
					$from_number=$mobiledata['From'];
					$to_passenger_number=$api->getPassengerNumber($from_number);
					$to_driver_number=$api->getDriverNumber($from_number);
					if(count($to_passenger_number)>0){
						$to=$to_passenger_number[0]["passenger_phone"];
						$driverid=$to_passenger_number[0]["driver_id"];
						$twiliocall=$api->getDriverInfo($driverid);
						$twilio_no=$twiliocall[0]["twilio_number"];
					}else if(count($to_driver_number)>0){
						$to=$to_driver_number[0]["driver_phone"];
						$driverid=$to_driver_number[0]["driver_id"];
						$twiliocall=$api->getDriverInfo($driverid);
						$twilio_no=$twiliocall[0]["twilio_number"];
					}
					else{
						$message="No records found";
						echo json_encode($message);
						exit;
					}
					header('Content-type: text/xml');					
					echo '<Response>
					<Dial callerId="'.$twilio_no.'">'.$to.'</Dial>
					</Response>';
				break;
				//http://192.168.1.88:1020/api/index/bnRheGlfYlVtUzZGMUJMVDY4VTZtWkdYaDNnRFV2WE5BRGo0==/?type=reject_trip&trip_id=12&driver_id=&reason=&reject_type=1
				case 'reject_trip':
				$array = $mobiledata;
				//print_r($array);
				$trip_id = $array['trip_id'];
				$reject_type = $array['reject_type'];
				$driver_id = $array['driver_id'];
				$taxi_id=$array['taxi_id'];
				$company_id= $array['company_id'];
				if($trip_id != "")
				{			
					//$passenger_log_details = $api->get_passenger_log_detail($trip_id);
					$passenger_log_details = $api->get_trip_detail_only($trip_id);
					//print_r($passenger_log_details);exit;					
					if(count($passenger_log_details) >0)
					{		

						$cancel_datetime = $passenger_log_details[0]->cancel_datetime;
						$cancel_status = $passenger_log_details[0]->cancel_status;
						$post=array();
						$post['driver_id']=$driver_id;
						$post['passengers_id']=$passenger_log_details[0]->passengers_id;
						$post['passengers_log_id']=$trip_id;
						$post['reason']=$array['reason'];	
						$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($company_id);
						$post['createdate']= $company_all_currenttimestamp;
						$operator_id = $passenger_log_details[0]->operator_id;					
						if($reject_type == 1)
						{	
							if($passenger_log_details[0]->driver_reply == 'R')
							{
								$message=__('trip_cancel_timeout');
								$msg = array("message" => $message,"status" => '8');	
								echo json_encode($msg);//exit;
							} 
							else if ($passenger_log_details[0]->travel_status == 6) 
							{
								$message = array("message" => __('trip_already_canceled'), "status"=>4);
								echo json_encode($message);
								break;
							}
							else
							{
								//push message for rejected driver
								$rejected_driver=$passenger_log_details[0]->driver_id;
								$passengers_log_id=$trip_id;
								$push_msg = __('request_rejected');
								$message = array("message"=>$push_msg,"trip_id"=>$passengers_log_id,"trip_detail"=>"","status"=>6);
								
								/********** Update Trip Status *****************/
								$driver_reply = "";
								//$update_trip_array  = array("driver_reply" => 'R');
								//$result = $api->update_table(PASSENGERS_LOG,$update_trip_array,'passengers_log_id',$passengers_log_id);	
								$get_driver_request = $api->get_driver_request($trip_id);
								//print_r($get_driver_request);exit;
								if($get_driver_request != 0)
								{
									/******* Update the driver id in */
									$rejection_type = 1;
									$prev_rejected_timeout_drivers = $get_driver_request[0]['rejected_timeout_drivers'];
									$status = $get_driver_request[0]['status'];

									$get_request_dets=$api->check_new_request_tripid($taxi_id,$company_id,$trip_id,$driver_id,$company_all_currenttimestamp,"",$operator_id);
									
									if($prev_rejected_timeout_drivers != "")
									{
										$rejected_timeout_drivers = $prev_rejected_timeout_drivers.','.$driver_id;
									}
									else
									{
										$rejected_timeout_drivers = $driver_id;
									}
									
									if($status != '4')
									{										
										$update_trip_array  = array("status"=>'0',"rejected_timeout_drivers" => $rejected_timeout_drivers);
										//$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$trip_id);							
									}
									$add_rejected_list = $api->add_rejected_list($post,$rejection_type);
									// Driver Statistics ********************/
									$driver_logs_rejected = $api->get_rejected_drivers($driver_id,$company_id);	
									$rejected_trips = count($driver_logs_rejected);	
									//to get cancelled trip counts from drivers
									$driver_cancelled_trips = $api->get_driver_cancelled_trips($driver_id,$company_id);
									$driver_earnings = $api->get_driver_earnings_with_rating($driver_id,$company_id);
									$driver_tot_earnings = $api->get_driver_total_earnings($driver_id);
									$statistics = array();
									$total_trip = $trip_total_with_rate = $total_ratings = $today_earnings = $total_amount=0;
																	
									foreach($driver_earnings as $stat){
									$total_trip++;
									$total_ratings += $stat['rating'];
									$total_amount += $stat['total_amount'];											
									}
									$overall_trip = $total_trip + $rejected_trips + $driver_cancelled_trips;							
									$time_driven = $api->get_time_driven($driver_id,'R','A','1');
									$statistics = array(
										"total_trip" => $overall_trip,
										"completed_trip" => $total_trip,
										"total_earnings" => round($driver_tot_earnings,2),
										"overall_rejected_trips" => $rejected_trips,
										"cancelled_trips" => $driver_cancelled_trips,
										"today_earnings"=>round($total_amount,2),											
										"shift_status"=>'IN',
										"time_driven"=>$time_driven,
										"status"=> 1
									  );

								if(strtotime($cancel_datetime) > strtotime("now") && $cancel_status == 1)
								{
									$api->update_driver_reply($trip_id);	

								}

									
									$message = array("message" => __('request_rejected'),"driver_statistics"=>$statistics,"status" => 6);
								}								
								/***********************************************************************************/							
							}
						}
						else
						{
							
								$get_driver_request = $api->get_driver_request($trip_id);
//print_r($get_driver_request);exit;
								$rejection_type = 0;
								if($get_driver_request != 0)
								{
									/******* Update the driver id in */
									$prev_rejected_timeout_drivers = $get_driver_request[0]['rejected_timeout_drivers'];
									$status = $get_driver_request[0]['status'];
									$reject_driversArr = explode(",",$prev_rejected_timeout_drivers);
									if(!in_array($driver_id, $reject_driversArr)) 
									{
											if($prev_rejected_timeout_drivers != "")
											{
												$rejected_timeout_drivers = $prev_rejected_timeout_drivers.','.$driver_id;
											}
											else
											{
												$rejected_timeout_drivers = $driver_id;
											}
										$get_request_dets=$api->check_new_request_tripid($taxi_id,$company_id,$trip_id,$driver_id,$company_all_currenttimestamp,"",$operator_id);
										if($status != '4')
										{
											$update_trip_array  = array("status"=>'0',"rejected_timeout_drivers" => $rejected_timeout_drivers);
											$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$trip_id);
																		
										}
									}
									$add_rejected_list = $api->add_rejected_list($post,$rejection_type);
									// Driver Statistics ********************/
									$driver_logs_rejected = $api->get_rejected_drivers($driver_id,$company_id);	
									$rejected_trips = count($driver_logs_rejected);	
									//to get cancelled trip counts from drivers
									$driver_cancelled_trips = $api->get_driver_cancelled_trips($driver_id,$company_id);
									$driver_earnings = $api->get_driver_earnings_with_rating($driver_id,$company_id);
									$driver_tot_earnings = $api->get_driver_total_earnings($driver_id);
									$statistics = array();
									$total_trip = $trip_total_with_rate = $total_ratings = $today_earnings = $total_amount=0;
																	
									foreach($driver_earnings as $stat){
									$total_trip++;
									$total_ratings += $stat['rating'];
									$total_amount += $stat['total_amount'];											
									}
									$overall_trip = $total_trip + $rejected_trips + $driver_cancelled_trips;							
									$time_driven = $api->get_time_driven($driver_id,'R','A','1');	
									$statistics = array( 
										"total_trip" => $overall_trip,
										"completed_trip" => $total_trip,
										"total_earnings" => round($driver_tot_earnings,2),
										"overall_rejected_trips" => $rejected_trips,
										"cancelled_trips" => $driver_cancelled_trips,
										"today_earnings"=>round($total_amount,2),											
										"shift_status"=>'IN',
										"time_driven"=>$time_driven,
										"status"=> 1
									  ); 
									
									$message = array("message" => __('driver_reply_timeout'),"driver_statistics"=>$statistics,"status" => 7);


								if(strtotime($cancel_datetime) > strtotime("now") && $cancel_status == 1)
								{
									$api->update_driver_reply($trip_id);	

								}

								
								}		
						}	
					}
					else
					{
						$message = array("message" => __('invalid_trip'),"status"=>2);
					}						
			}
			else
			{
				$message =__('trip_id_req');
				$message = array("message" => $message,"status" => '-1');
			}
			echo json_encode($message);
			exit;

			/** Favourite Driver List **/

			case 'favourite_driver_list':
				$passenger_id = isset($mobiledata['passenger_id']) ? $mobiledata['passenger_id'] : '';
				if(!empty($passenger_id)) {
					$result = $api->favourite_driver_list($passenger_id);
					if(count($result) > 0) {
						$favourite_driver = array();
						foreach($result as $f) {
							$image = URL_BASE."public/images/noimages.jpg";
							if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/driver_image/'.$f['profile_picture']) && ($f['profile_picture'] != "")) {
								$image = URL_BASE.SITE_DRIVER_IMGPATH.$f['profile_picture'];
							}
							$favourite_driver[] = array (
								"driver_id" => $f["id"],
								"name" => $f["name"],
								"email" => $f["email"],
								"phone" => $f["phone"],
								"profile_image" => $image,
								"taxi_no" => $f["taxi_no"]
							);
						}
						$msg = array("message" => __('favourite_driver_list'),"details" => $favourite_driver, "status" => 1);
					}
					else
					{
						$msg = array("message" => __('no_data'),"status" => -1);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status" => -1);
				}
				echo json_encode($msg);
				break;

			/** Unfavourite Drivers **/

			case 'unfavourite_driver':
				$passenger_id = isset($mobiledata['passenger_id']) ? $mobiledata['passenger_id'] : '';
				$driver_id = isset($mobiledata['driver_id']) ? $mobiledata['driver_id'] : '';
				if(!empty($passenger_id) && !empty($driver_id)) {
					$result = $api->unfavourite_driver($passenger_id, $driver_id);
					if($result == 1) {
						$msg = array("message" => __('unfavourite_success'),"status" => 1);
					} else if($result == -1) {
						$msg = array("message" => __('no_data'),"status" => -1);
					} else {
						$msg = array("message" => __('invalid_user_driver'),"status" => -2);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status" => -1);
				}
				echo json_encode($msg);
				break;
			
			/** Set Split Fare Option **/

			case 'set_split_fare':
				$passenger_id = isset($mobiledata['passenger_id']) ? $mobiledata['passenger_id'] : '';
				$type = isset($mobiledata['type']) ? $mobiledata['type'] : '';
				if(!empty($passenger_id)) {
					$result = $api->set_split_fare($passenger_id,$type);
					if($result > 0) {
						if($type) {
							$msg = array("message" => __('splifare_on_success_label'), "status" => 1);
						} else {
							$msg = array("message" => __('splifare_off_success_label'), "status" => 0);
						}
					} else {
						$msg = array("message" => __('not_eligible_for_splifare_on_label'), "status" => -1);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status" => -1);
				}
				echo json_encode($msg);
				break;
				
			/** Driver Withdraw List **/
			
			case 'driver_wallet_balance':
				$array = $mobiledata;
				$driver_id= $array['driver_id'];
				$company_det = $api->get_company_id($driver_id);
				$company_id = ($company_det > 0) ? $company_det[0]['company_id'] : $default_companyid;
				$trip_referral_wallet = $api->driver_profile($driver_id);
				$trip_referral_wallet =(isset($trip_referral_wallet[0]['trip_wallet']) && $trip_referral_wallet[0]['trip_wallet'] !="")?$trip_referral_wallet[0]['trip_wallet']:0;
				$pending_trip_referral_wallet = $api->driver_withdraw_pending_amount($company_id,$driver_id);
				$pending_trip_referral_wallet = (isset($pending_trip_referral_wallet[0]['pending_amount']))?$pending_trip_referral_wallet[0]['pending_amount']:0;
				$msg = array("message" => __('Wallet Balance'), "status" => 1,"trip_referral_wallet"=>$trip_referral_wallet,"pending_trip_referral_wallet"=>$pending_trip_referral_wallet);
				echo json_encode($msg);
				break;

			case 'driver_withdraw_list':
				$driver_id = isset($mobiledata['driver_id']) ? $mobiledata['driver_id'] : '';
				if(!empty($driver_id)) {
					$company_det = $api->get_company_id($driver_id);
					$company_id = ($company_det > 0) ? $company_det[0]['company_id'] : $default_companyid;
					$result = $api->get_withdraw_request($company_id,$driver_id);
					if(count($result) > 0) {
						$data = array();
						foreach($result as $f) {
							$status_label = __("pending");
							$status_id = 0;
							if($f["request_status"] == 1) {
								$status_label = __("approved");
								$status_id = 1;
							} else if($f["request_status"] == 2) {
								$status_label = __("Cancelled by admin");
								$status_id = 2;
							} else if($f["request_status"] == 3) {
								$status_label = __("Cancelled by you");
								$status_id = 3;
							}
							$data[] = array (
								"withdraw_request_id" => $f["withdraw_request_id"],
								"request_id" => "#".$f["request_id"],
								"withdraw_amount" => $this->site_currency.$f["withdraw_amount"],
								"request_date" => Commonfunction::getDateTimeFormat($f["request_date"],1),
								"brand_type" => ($f["brand_type"] == 1) ? __("multy") : __("single"),
								"request_status" => $status_label,
								"request_status_id" => $status_id
							);
						}
						$msg = array("message" => __('withdraw_req_list'),"details" => $data, "status" => 1);
					}
					else
					{
						$msg = array("message" => __('no_data'),"status" => -1);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status" => -1);
				}
				echo json_encode($msg);
				break;
				
			/** Driver Send Withdraw Request **/

			case 'driver_send_withdraw_request':
				$driver_id = isset($mobiledata['driver_id']) ? $mobiledata['driver_id'] : '';
				$company_det = $api->get_company_id($driver_id);
				$company_id = ($company_det > 0) ? $company_det[0]['company_id'] : $default_companyid;
				$request_amount = isset($mobiledata['request_amount']) ? $mobiledata['request_amount'] : '';
				$available_amount = isset($mobiledata['available_amount']) ? $mobiledata['available_amount'] : '';
				if(!empty($driver_id) && !empty($request_amount) && !empty($available_amount)) {
					if($request_amount == "0.00" || $request_amount == "0") {
						$msg = array("message" => __('invalid_amount'),"status" => -1);
					} else if($request_amount > $available_amount) {
						$msg = array("message" => __('withdraw_amount_error'),"status" => -1);
					} else {
						$company_det = $api->get_company_id($driver_id);
						$company_id = ($company_det > 0) ? $company_det[0]['company_id'] : $default_companyid;
						$result = $api->insert_withdraw_request($company_id,$driver_id,$request_amount);
						$trip_referral_wallet = $api->driver_profile($driver_id);
						$trip_referral_wallet =(isset($trip_referral_wallet[0]['trip_wallet']) && $trip_referral_wallet[0]['trip_wallet'] !="")?$trip_referral_wallet[0]['trip_wallet']:0;
						$pending_trip_referral_wallet = $api->driver_withdraw_pending_amount($company_id,$driver_id);
						$pending_trip_referral_wallet = (isset($pending_trip_referral_wallet[0]['pending_amount']))?$pending_trip_referral_wallet[0]['pending_amount']:0;
						if(count($result) > 0) {
							$msg = array("message" => __('withdraw_req_sent_success'), "status" => 1,"trip_referral_wallet"=>$trip_referral_wallet,"pending_trip_referral_wallet"=>$pending_trip_referral_wallet);
						}
						else
						{
							$msg = array("message" => __('no_data'),"status" => -1);
						}
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status" => -1);
				}
				echo json_encode($msg);
				break;
				
				
			case 'cancel_withdraw_request':
				$array = $mobiledata;
				$api->cancel_withdraw_request($array);
				
				$company_det = $api->get_company_id($array['driver_id']);
				$company_id = ($company_det > 0) ? $company_det[0]['company_id'] : $default_companyid;
				$trip_referral_wallet = $api->driver_profile($array['driver_id']);
				$trip_referral_wallet =(isset($trip_referral_wallet[0]['trip_wallet']) && $trip_referral_wallet[0]['trip_wallet'] !="")?$trip_referral_wallet[0]['trip_wallet']:0;
				$pending_trip_referral_wallet = $api->driver_withdraw_pending_amount($company_id,$array['driver_id']);
				$pending_trip_referral_wallet = (isset($pending_trip_referral_wallet[0]['pending_amount']))?$pending_trip_referral_wallet[0]['pending_amount']:0;

				$msg = array("message" => __('Withdrawn request hase been cancelled successfully '), "status" => 1,"trip_referral_wallet"=>$trip_referral_wallet,"pending_trip_referral_wallet"=>$pending_trip_referral_wallet);
				echo json_encode($msg);
				break;
				
			/** Help content list in passenger app **/
			case 'help_content':
				$helpList = $api->getHelpContents();
				$msg = array();
				if(count($helpList) > 0){
					$msg = array("message" => __('success'),"details" => $helpList, "status" => 1);
				} else {
					$msg = array("message" => __('no_data'),"status" => -1);
				}
				echo json_encode($msg);
			break;
			
			/** Help Comment update for a trip from passenger App **/
			case 'help_comment_update':
				$trip_id = isset($mobiledata['trip_id']) ? $mobiledata['trip_id'] : '';
				$help_id = isset($mobiledata['help_id']) ? $mobiledata['help_id'] : '';
				$help_comment = isset($mobiledata['help_comment']) ? $mobiledata['help_comment'] : '';
				if(!empty($help_id) && !empty($help_comment) && !empty($trip_id)) {
					$updateTripComment = $api->updateTripComment($trip_id,$help_id,$help_comment);
					if($updateTripComment){
						$msg = array("message" => __('comment_post_success'), "status" => 1);
					} else {
						$msg = array("message" => __('problem_post_comment'),"status" => -1);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status" => -1);
				}
				echo json_encode($msg);
			break;
			/** Driver Withdraw List Detail Page **/
			case 'driver_withdraw_list_detail':
				$driver_id = isset($mobiledata['driver_id']) ? $mobiledata['driver_id'] : '';
				$withdraw_request_id = isset($mobiledata['withdraw_request_id']) ? $mobiledata['withdraw_request_id'] : '';
				if(!empty($driver_id) && !empty($withdraw_request_id)) {
					$result = $api->get_withdraw_deatil($driver_id,$withdraw_request_id);
					$log_result = $api->get_withdraw_log($withdraw_request_id);
					$activity_log = $data = array();
					if(count($result) > 0) {
						foreach($result as $f) {
							$status_label = __("not_yet_approved");
							if($f["request_status"] == 1) {
								$status_label = __("approved");
							} else if($f["request_status"] == 2) {
								$status_label = __("rejected");
							}
							$data[] = array (
								"withdraw_request_id" => $f["withdraw_request_id"],
								"request_id" => "#".$f["request_id"],
								"company_name" => $f["company_name"],
								"withdraw_amount" => $this->site_currency.$f["withdraw_amount"],
								"request_date" => date("D,dM-Y h:i:s A",strtotime($f["request_date"])),
								"brand_type" => ($f["brand_type"] == 1) ? __("multy") : __("single"),
								"request_status" => $status_label
							);
						}
						if(count($log_result) > 0) {
							foreach($log_result as $l) {
								$attachment = "";
								$status_label = __("not_yet_approved");
								if($l["status"] == 1) {
									$status_label = __("approved");
								} else if($l["status"] == 2) {
									$status_label = __("rejected");
								}
								$status_txt = "Status changed to ".$status_label.".";
								if($l["status"] == 1) {
									$payment_mode_name = $l["payment_mode_name"];
									$transaction_id = $l["transaction_id"];
									$comments = $l["comments"];
								} else if($l["status"] == 2) {
									$payment_mode_name = $l["payment_mode_name"];
									$transaction_id = $l["transaction_id"];
									$comments = $l["comments"];
								}
								if($l['file_name'] != "" && file_exists(DOCROOT.WITHDRAW_IMG_PATH.$l['file_name'])) {
									$attachment = URL_BASE.WITHDRAW_IMG_PATH.$l['file_name'];
								}
								$activity_log[] = array (
									"created_date" => date("D,dM-Y h:i:s A",strtotime($l["created_date"])),
									"status" => $l["status"],
									"status_txt" => $status_txt,
									"payment_mode_name" => ($payment_mode_name != null) ? $payment_mode_name : "",
									"transaction_id" => $transaction_id,
									"comments" => $comments,
									"attachment" => $attachment,
								);
							}
						}
						$msg = array("message" => __('withdraw_request_details'),"details" => $data,"activity_log" => $activity_log, "status" => 1);
					}
					else
					{
						$msg = array("message" => __('no_data'),"status" => -1);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status" => -1);
				}
				echo json_encode($msg);
			break;
			/** Street Pickup End Trip **/
			case 'street_pickup_end_trip':
			$array = $mobiledata;
			if(!empty($array))
			{    
				$drop_latitude = $array['drop_latitude'];
				$drop_longitude = $array['drop_longitude'];
				$drop_location = urldecode($array['drop_location']);
				$trip_id = $array['trip_id'];
				$distance = $array['distance'];
				$actual_distance = $array['actual_distance'];
				$distance_time = $array['distance_time'];
				$waiting_hours = $array['waiting_hour'];
				$driver_app_version = (isset($array['driver_app_version'])) ? $array['driver_app_version'] : '';

				$device_type = (isset($array['device_type']))?$array['device_type']:'';
				$app_version = isset($array['driver_app_version']) ? $array['driver_app_version'] : '';
				$os_version = (isset($array['os_version'])) ? $array['os_version'] : '';
				$brand_name = (isset($array['brand_name'])) ? $array['brand_name'] : '';
				
				$driver_app_version_details=json_encode(array('device_type'=>$device_type,'brand_name'=>$brand_name,'os_version'=>$os_version,'app_version'=>$app_version));


				if(!empty($trip_id))
				{
					$gateway_details = $this->commonmodel->gateway_details($default_companyid);
					$get_passenger_log_details = $api->getPassengerLogDetail($trip_id);
					$pickupdrop = $taxi_id = $company_id = $fare_per_hour = $waiting_per_hour = $total_fare = $nightfare = 0;
					if(count($get_passenger_log_details) > 0)
					{
						$company_tax = $get_passenger_log_details[0]->company_tax;
						$tax = (FARE_SETTINGS != 2) ? TAX : $company_tax;
						$travel_status = $get_passenger_log_details[0]->travel_status;
						$splitTrip = $get_passenger_log_details[0]->is_split_trip; //0 - Normal trip, 1 - Split trip
						$total_distance = $get_passenger_log_details[0]->distance;
						$pickupdrop = $taxi_id = $company_id = $fare_per_hour = $waiting_per_hour = $total_fare = $nightfare = 0;
						if(($travel_status == 2) || ($travel_status == 5))
						{
							$pickup = $get_passenger_log_details[0]->current_location;
							$drop = $get_passenger_log_details[0]->drop_location;
							$pickupdrop = $get_passenger_log_details[0]->pickupdrop;
							$taxi_id = $get_passenger_log_details[0]->taxi_id;
							$pickuptime = date('H:i:s', strtotime($get_passenger_log_details[0]->pickup_time));
							$actualPickupTime = date('H:i:s', strtotime($get_passenger_log_details[0]->actual_pickup_time));
							$company_id = $get_passenger_log_details[0]->company_id;
							$driver_id = $get_passenger_log_details[0]->driver_id;
							$approx_distance = $get_passenger_log_details[0]->approx_distance;
							$approx_fare = $get_passenger_log_details[0]->approx_fare;
							$fixedprice = $get_passenger_log_details[0]->fixedprice;
							$actual_pickup_time = $get_passenger_log_details[0]->actual_pickup_time;

							$taxi_model_id = $get_passenger_log_details[0]->taxi_modelid;
							$brand_type = $get_passenger_log_details[0]->brand_type;

							$taxi_fare_details = $api->get_model_fare_details($company_id,$taxi_model_id,$get_passenger_log_details[0]->search_city,$brand_type);
							if($travel_status != 5) {
								$drop_time = $this->commonmodel->getcompany_all_currenttimestamp($company_id);
							} else {
								$drop_time = $get_passenger_log_details[0]->drop_time;
							}
							
							/*************** Update arrival in driver request table ******************/
							$update_trip_array  = array("status" => '7');
							$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$trip_id);
							/*************************************************************************/
							
							/** Update Driver Status **/
							$update_driver_arrary  = array(
								"latitude" => $array['drop_latitude'],
								"longitude" => $array['drop_longitude'],
								"status" => 'A'
							);
							if(($array['drop_latitude'] > 0 ) && ($array['drop_longitude'] > 0))
							{
								$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_id);
							}
							else
							{
								$update_driver_arrary  = array("status" => 'A');
								$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_id);
							}
							/*********************/
							$base_fare = $min_km_range = $min_fare = $cancellation_fare = $below_above_km_range = $below_km = $above_km = $night_charge = $night_timing_from = $night_timing_to = $night_fare = $evening_charge = $evening_timing_from = $evening_timing_to = $evening_fare = $waiting_per_hour = $minutes_cost= 0;
							if(count($taxi_fare_details) > 0)
							{
								$base_fare = $taxi_fare_details[0]['base_fare'];
								$min_km_range = $taxi_fare_details[0]['min_km'];
								$min_fare = $taxi_fare_details[0]['min_fare'];
								$cancellation_fare = $taxi_fare_details[0]['cancellation_fare'];
								$below_above_km_range = $taxi_fare_details[0]['below_above_km'];
								$below_km = $taxi_fare_details[0]['below_km'];
								$above_km = $taxi_fare_details[0]['above_km'];
								$night_charge = $taxi_fare_details[0]['night_charge'];
								$night_timing_from = $taxi_fare_details[0]['night_timing_from'];
								$night_timing_to = $taxi_fare_details[0]['night_timing_to'];
								$night_fare = $taxi_fare_details[0]['night_fare'];
								$evening_charge = $taxi_fare_details[0]['evening_charge'];
								$evening_timing_from = $taxi_fare_details[0]['evening_timing_from'];
								$evening_timing_to = $taxi_fare_details[0]['evening_timing_to'];
								$evening_fare = $taxi_fare_details[0]['evening_fare'];
								$waiting_per_hour = $taxi_fare_details[0]['waiting_time'];
								$minutes_fare = $taxi_fare_details[0]['minutes_fare'];
							}

							// Which is used when the driver send waiting time as minutes
							$roundtrip = "No";
							if($pickupdrop == 1)
							{
								$roundtrip = "Yes";
							}
							// Minutes travelled functionlity starts here

							/********Minutes fare calculation *******/ 
							$interval  = abs(strtotime($drop_time) - strtotime($actual_pickup_time));
							$minutes   = round($interval / 60);       
							/********Minutes fare calculation *******/
							$baseFare = $base_fare;
							$total_fare = $base_fare;
							// Minutes travelled functionlity ends here
							if(FARE_CALCULATION_TYPE == 1 || FARE_CALCULATION_TYPE == 3)
							{
								if($total_distance < $min_km_range)
								{
									//min fare has set as base fare if trip distance 
									$baseFare = $min_fare;
									$total_fare = $min_fare;
								}
								else if($total_distance <= $below_above_km_range)
								{
									$fare = $total_distance * $below_km;
									$total_fare  = 	$fare + $base_fare ;
								}
								else if($total_distance > $below_above_km_range)
								{
									$fare = $total_distance * $above_km;
									$total_fare  = 	$fare + $base_fare ;
								}
							}
							
							if(FARE_CALCULATION_TYPE == 2 || FARE_CALCULATION_TYPE == 3)
							{
								/********** Minutes fare calculation ************/
								if($minutes_fare > 0)
								{
									$minutes_cost = $minutes * $minutes_fare;
									$total_fare  = $total_fare + $minutes_cost;
								}
								/************************************************/
							}
							$trip_fare = $total_fare;

							// Waiting Time calculation
							$waiting_cost = $waiting_per_hour * $waiting_hours;
							$total_fare = $waiting_cost + $total_fare;

							$parsed = date_parse($actualPickupTime);
							$pickup_seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];

							//Night Fare Calculation
							$parsed = date_parse($night_timing_from);
							$night_from_seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];

							$parsed = date_parse($night_timing_to);
							$night_to_seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];

							$nightfare_applicable = $date_difference=0;
							if ($night_charge != 0) 
							{
								if( ($pickup_seconds >= $night_from_seconds && $pickup_seconds <= 86399) || ($pickup_seconds >= 0 && $pickup_seconds <= $night_to_seconds) )
								{
									$nightfare_applicable = 1;
									$nightfare = ($night_fare/100)*$total_fare;//night_charge%100;                                        
									$total_fare  = $nightfare + $total_fare;
								}
							}

							//Evening Fare Calculation
							$parsed_eve = date_parse($evening_timing_from);
							$evening_from_seconds = $parsed_eve['hour'] * 3600 + $parsed_eve['minute'] * 60 + $parsed_eve['second'];

							$parsed_eve = date_parse($evening_timing_to);
							$evening_to_seconds = $parsed_eve['hour'] * 3600 + $parsed_eve['minute'] * 60 + $parsed_eve['second'];

							$eveningfare = $evefare_applicable=$date_difference=0;
							if ($evening_charge != 0) 
							{
								if( $pickup_seconds >= $evening_from_seconds && $pickup_seconds <= $evening_to_seconds)
								{
									$evefare_applicable = 1;
									$eveningfare = ($evening_fare/100)*$total_fare;//night_charge%100;
									$total_fare  = $eveningfare + $total_fare;
								}
							}

							// Company Tax amount Calculation
							$tax_amount = "";
							if($tax > 0)
							{
								$tax_amount = ($tax/100)*$total_fare;//night_charge%100;
								$total_fare =  $total_fare+$tax_amount;
							}
							
							$total_fare = ($fixedprice != 0) ? $fixedprice : $total_fare;
							$trip_fare = round($trip_fare,2);
							$total_fare = round($total_fare,2);
							if($travel_status != 5) {
								//to update the used wallet amount and  for a trip in passenger log table
								$msg_status = 'R';$driver_reply='A';$journey_status=5; // Waiting for Payment
								$journey = $api->update_journey_statuswith_drop($trip_id,$msg_status,$driver_reply,$journey_status,$drop_latitude,$drop_longitude,$drop_location,$drop_time,$total_distance,$waiting_hours,$tax,$driver_app_version_details,0);

								//update the wallet amount in referred driver's row
								$referredDriver = $api->getReferredDriver($driver_id);
								if($referredDriver > 0) {
									$driverReferral = $api->getDriverReferralDetails($referredDriver);
									if(count($driverReferral) > 0){
										$wallAmount = $driverReferral[0]['registered_driver_wallet'] + $driverReferral[0]['registered_driver_code_amount'];
										$wallArr = array("registered_driver_wallet" => $wallAmount);
										$walletUpdate = $this->commonmodel->update(DRIVER_REF_DETAILS,$wallArr,'registered_driver_id',$driverReferral[0]['registered_driver_id']);
										//update referrer earned status in registered driver's row while he completing his first trip
										$referrerEarnedArr = array("referral_status"=>"1");
										$refEarnedUpdate = $this->commonmodel->update(DRIVER_REF_DETAILS,$referrerEarnedArr,'registered_driver_id',$driver_id);
									}
								}
							}
							$tax_amount = round($tax_amount,2);
							$nightfare = round($nightfare,2);
							$smpleArr = array();
							foreach($gateway_details as $key=>$valArr) {
								if($valArr['pay_mod_id'] == 1) {
									$smpleArr[] = $valArr;
								}
							}
							$gateway_details = $smpleArr;

							//the hours value has been changed to seconds
							$convertSeconds = $waiting_hours * 3600;
							$converthours = floor($convertSeconds / 3600);
							$convertmins = floor(($convertSeconds - ($converthours*3600)) / 60);
							$convertsecs = floor($convertSeconds % 60);
							$waitH = ($converthours < 10) ? '0'.$converthours : $converthours;
							$waitM = ($convertmins < 10) ? '0'.$convertmins : $convertmins;
							$waitS = ($convertsecs < 10) ? '0'.$convertsecs : $convertsecs;
							$waitingTime = ($waitH != "00") ? $waitH.':'.$waitM.':'.$waitS.' Hours' :  $waitM.':'.$waitS.' Mins';
														
							$detail = array("trip_id" => $trip_id,"distance" => $total_distance,"trip_fare"=>$trip_fare,"nightfare_applicable"=>$nightfare_applicable,"nightfare"=>$nightfare,"eveningfare_applicable"=>$evefare_applicable,"eveningfare"=>$eveningfare,"waiting_time"=>$waitingTime,"waiting_cost"=>$waiting_cost,"tax_amount"=>$tax_amount,"subtotal_fare"=>$total_fare,"total_fare"=>$total_fare,"gateway_details"=>$gateway_details,"pickup"=>$pickup,"drop"=>$drop_location,"company_tax"=>$tax,"waiting_per_hour" => $waiting_per_hour, "roundtrip"=> $roundtrip,"minutes_traveled"=>$minutes,"minutes_fare"=>$minutes_cost,"metric"=>UNIT_NAME,"base_fare"=>$baseFare,"wallet_amount_used"=>0,"promo_discount_per"=>0,"pass_id"=>"","referdiscount"=>"","promo_discount_per"=>"","promodiscount_amount"=>0,"passenger_discount"=>"","credit_card_status"=>0,"street_pickup"=>1,"fare_calculation_type"=>FARE_CALCULATION_TYPE);
							$message = array("message" => __('trip_completed_driver'),"detail" => $detail,"status" => 4);
						}
						else if($travel_status == 1)
						{
							$message = array("message" => __('trip_already_completed'),"status"=>-1);
						}
						else
						{
							$message = array("message" => __('trip_not_started'),"status"=>-1);
						}
					}
					else
					{
						$message = array("message" => __('invalid_trip'),"status"=>-1);
					}
				}
				else
				{
					$message = array("message" => __('invalid_trip'),"status"=>-1);
				}
			}
			else
			{
				$message = array("message" => __('invalid_request'),"status"=>-1);
			}
			echo json_encode($message);
			break;
			
			/** Street Pickup - Driver Start Trip Process **/
			case 'driver_start_trip':
				$trip_array = $mobiledata;
				if(!empty($trip_array)) {
					$trip_array['company_id'] = $default_companyid;
					$login_status = $api->driver_logged_status($trip_array);
					if($login_status == 1){
						$trip_array['company_id'] = $api->get_driver_companyid($trip_array);
						$trip_status = $api->driver_current_trip_status($trip_array);
						//echo count($trip_status);exit;
						if(count($trip_status) > 0){
							$trip_array['approx_trip_fare'] = isset($trip_array['approx_trip_fare'])?round($trip_array['approx_trip_fare'],2):0;
							$trip_array['taxi_id'] = isset($trip_status[0]['mapping_taxiid'])?$trip_status[0]['mapping_taxiid']:"";
							$brand_type = isset($trip_status[0]['brand_type'])?$trip_status[0]['brand_type']:"";
							$fare_details = $api->get_model_fare_details($trip_array['company_id'],$trip_status[0]['taxi_model'],"",$brand_type);
							if(count($fare_details) > 0){
								$trip_array['motor_model'] = $trip_status[0]['taxi_model'];
								$details['driver_tripid'] = $api->save_street_trip($trip_array);
								$update_driver_arrary  = array(
									"latitude" => $trip_array['pickup_latitude'],
									"longitude" => $trip_array['pickup_longitude'],
									"status" => 'A'
								);
								$api->update_table(DRIVER,$update_driver_arrary,'driver_id',$trip_array['driver_id']);
								foreach($fare_details as $val){
									$details['base_fare'] = $val['base_fare'];
									$details['min_fare'] = $val['min_fare'];
									$details['below_km'] = $val['below_km'];
									$details['above_km'] = $val['above_km'];
									$details['below_above_km'] = $val['below_above_km'];
								}
								$message = array("message" => __('trip_confirmed'),"status" => 1,"detail"=>$details);
							}else{
								$message = array("message" => __('invalid_motor_model'),"status" => -1);
							}
						}else{
							$message = array("message" => __('already_trip'),"status"=>-1);
						}
					}
					else
					{
						$message = array("message" => __('driver_not_login'),"status"=>-1);
					}
				} else {
					$message = array("message" => __('invalid_request'),"status" => -1);
				}
				echo json_encode($message);
			break;
		
			/** Search Driver Withdraw List **/
			case 'search_driver_withdraw_list':
				$search_array = $mobiledata;
				//print_r($search_array); exit;
				if(!empty($search_array['driver_id'])) {
					$company_det = $api->get_company_id($search_array['driver_id']);
					$search_array['company_id'] = ($company_det > 0) ? $company_det[0]['company_id'] : $default_companyid;
					$result = $api->search_withdraw_request($search_array);
					if(count($result) > 0) {
						$data = array();
						foreach($result as $f) {
							$status_label = __("pending");
							$status_id = 0;
							if($f["request_status"] == 1) {
								$status_label = __("approved");
								$status_id = 1;
							} else if($f["request_status"] == 2) {
								$status_label = __("Cancelled by admin");
								$status_id = 2;
							} else if($f["request_status"] == 3) {
								$status_label = __("Cancelled by driver");
								$status_id = 3;
							}
							
							$data[] = array (
								"withdraw_request_id" => $f["withdraw_request_id"],
								"request_id" => "#".$f["request_id"],
								"withdraw_amount" => $this->site_currency.$f["withdraw_amount"],
								"request_date" => Commonfunction::getDateTimeFormat($f["request_date"],1),
								"request_status" => $status_label,
								"request_status_id" => $status_id
							);
						}
						$msg = array("message" => __('withdraw_req_list'),"details" => $data, "status" => 1);
					}
					else
					{
						$msg = array("message" => __('no_data'),"status" => -1);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status" => -1);
				}
				echo json_encode($msg);
				break;
				
		    /** Search Driver Withdraw List **/
			case 'search_driver_trip_request_list':
				$search_array = $mobiledata;
				//print_r($search_array); exit;
				if(!empty($search_array['driver_id'])) {
					$company_det = $api->get_company_id($search_array['driver_id']);
					//$search_array['company_id'] = ($company_det > 0) ? $company_det[0]['company_id'] : $default_companyid;
					$result = $api->search_withdraw_request_list($search_array);
					if(count($result) > 0) {
						$data = array();
						foreach($result as $f) {
							$status_label = __("pending");
							$status_id = 0;
							if($f["request_status"] == 1) {
								$status_label = __("paid");
								$status_id = 1;
							} else if($f["request_status"] == 2) {
								$status_label = __("Cancelled by admin");
								$status_id = 2;
							} else if($f["request_status"] == 3) {
								$status_label = __("Cancelled by driver");
								$status_id = 3;
							}
							
							$data[] = array (
								"withdraw_request_id" => $f["withdraw_request_id"],
								"withdraw_amount" => $this->site_currency.$f["withdraw_amount"],
								"request_date" => $f["request_date"],
								"request_status" => $status_label,
								"request_status_id" => $status_id,
								"trip_id" =>$f['trip_id'],
							);
						}
						$msg = array("message" => __('withdraw_req_list'),"details" => $data, "status" => 1);
					}
					else
					{
						$msg = array("message" => __('no_data'),"status" => -1);
					}
				} else {
					$msg = array("message" => __('invalid_request'),"status" => -1);
				}
				echo json_encode($msg);
				break;
		
			/** Driver Recent Trip List **/
			case 'driver_recent_trip_list':
				$trip_array = $mobiledata;
				if(!empty($trip_array)) {
					$login_status = $api->driver_logged_status($trip_array);
					$get_balance = $api->get_driver_balance($trip_array);
					if($login_status == 1){
						$trip_list = $api->get_recent_driver_trip_list($trip_array);
						$driver_balance = (isset($get_balance[0]['account_balance']) && $get_balance[0]['account_balance'] !="")?$get_balance[0]['account_balance']:0;
							$alert_message =($driver_balance <=0)? "Your credits are too low. You will not receive trip request. Please recharge now!":"";
						if(count($trip_list) > 0){
							foreach($trip_list as $key => $val){
								$trip_list[$key]['drop_time'] = Commonfunction::getDateTimeFormat($val['drop_time'],1);
							}					
							$message = array("message" => __('drive_recent_trip_list'),"status" => 1,"alert_message"=>$alert_message,"account_balance"=>round($driver_balance,2),"trip_list"=>$trip_list);
						}else{
							$array[]= array("drop_time"=>"","fare"=>0,"model_name"=>"");
							$message = array("message" => __('drive_recent_trip_list'),"status" => 1,"alert_message"=>$alert_message,"account_balance"=>round($driver_balance,2),"trip_list"=>$array);
						}	
					}
					else
					{
						$message = array("message" => __('driver_not_login'),"status"=>-1);	
					}
				} else {
					$message = array("message" => __('invalid_request'),"status" => -1);
				}
				echo json_encode($message);
			break;
			
			case 'driver_balance_update':
				$array = $mobiledata;
				$data= $api->get_driver_acc_details($array['driver_id']);
				$account_balance = (isset($data[0]['account_balance']))?round($data[0]['account_balance'],2):0;
				$alert_message =($account_balance <=0)? "Your credits are too low. You will not receive trip request. Please recharge now!":"";
				$message = array("message" => __('Driver balance update'),"status" => 1,'alert_message'=> $alert_message,'account_balance'=> $account_balance);
				echo json_encode($message);
			break;
			
			case 'passenger_recharge_api':
				$array = $mobiledata;
				$validator = $api->passenger_recharge_api_validation($array);
				if($validator->check()) 
				{
					$check_recharge_code = $api->check_passenger_recharge_code($array);
					$recharge_id=isset($check_recharge_code[0]['coupon_id'])?$check_recharge_code[0]['coupon_id']:'';
					$recharge_amount=isset($check_recharge_code[0]['amount'])?$check_recharge_code[0]['amount']:'0';
					if(count($check_recharge_code)>0 && !empty($recharge_id)){
						$update_recharge_status = $api->update_passenger_recharge_status($array,$recharge_id,$recharge_amount);
						$passenger_recharge_history = $api->get_passenger_recharge_history_api($array['passenger_id']);
						$passenger_profile = $api->passenger_profile($array['passenger_id']);
						$account_balance=isset($passenger_profile[0]['wallet_amount'])?$passenger_profile[0]['wallet_amount']:'0';
						$message = array("message" => __('coupon_code_success'),"total_balance_amt"=>round($account_balance,2),"past_history"=>$passenger_recharge_history,"voucher_amount"=>$recharge_amount,"currency"=>$this->site_currency,"status"=>1);
					}else if($check_recharge_code=='-1')
					{
							
						$message = array("message" => __('invalid_coupon_code'),"status"=>2);	
					}
					else if($check_recharge_code=='-2')
					{
						$blockedvoucherdetails = $api->update_passenger_blockedvoucherdetails($array);
						$message = array("message" => __('couponcode_blocked'),"status"=>2);	
					}
					else if($check_recharge_code=='-3')
					{
						$message = array("message" => __('already_used_couponcode'),"status"=>2);	
					}
					else
					{
						$message = array("message" => __('time_coupon_code'),"status"=>2);	
					}
				}else
				{
					$errors = $validator->errors('errors');	
					$message = array("message" => __('validation_error'),"status"=>3,"detail"=>$errors);	
				}
				echo json_encode($message);
			break;
			
			case 'passenger_recharge_history':
				$array = $mobiledata;
				$passenger_id=isset($array['passenger_id'])?$array['passenger_id']:'';
				if(!empty($passenger_id)){
					$passenger_recharge_history = $api->get_passenger_recharge_history($passenger_id,$array['data_count'],$array['limit']);
					$new_array = array();
					foreach($passenger_recharge_history as $history)
					{
						$history['amount']= round($history['amount'],2);
						$history['coupon_code']= $history['coupon_code']."(".$history['payment_type'].")";
						$new_array[] = $history;
					}
					
					$passenger_profile = $api->passenger_profile($passenger_id);
					if(count($passenger_profile)>0){
						if(count($passenger_recharge_history)>0){
							$account_balance=isset($passenger_profile[0]['wallet_amount'])?$passenger_profile[0]['wallet_amount']:'0';
							$message = array("message" => __('success'),"total_balance_amt"=>round($account_balance,2),"past_history"=>$new_array,"currency"=>$this->site_currency,"status"=>1);
						}else{
							$message = array("message" => __('no_past_history'),"total_balance_amt"=>"0","status"=>2);
						}
					}else{
						$message = array("message" => __('invalid_user_passenger'),"status"=>3);	
					}
				}else{
					$message = array("message" => __('invalid_user_passenger'),"status"=>3);	
				}
			echo json_encode($message);
			break;
			
			case 'driver_recharge_api':
				$array = $mobiledata;
				$validator = $api->driver_recharge_api_validation($array);
				if($validator->check()) 
				{
					$check_recharge_code = $api->check_recharge_code($array);
					$recharge_id=isset($check_recharge_code[0]['coupon_id'])?$check_recharge_code[0]['coupon_id']:'';
					$recharge_amount=isset($check_recharge_code[0]['amount'])?$check_recharge_code[0]['amount']:'0';
					if(count($check_recharge_code)>0 && !empty($recharge_id)){
						$update_recharge_status = $api->update_recharge_status($array,$recharge_id,$recharge_amount);
						$driver_recharge_history = $api->get_driver_recharge_history_api($array['driver_id']);
						$driver_profile = $api->driver_profile($array['driver_id']);					
						$account_balance=isset($driver_profile[0]['account_balance'])?$driver_profile[0]['account_balance']:'0';
						$alert_message =($account_balance <=0)? "Your credits are too low. You will not receive trip request. Please recharge now!":"";
						$message = array("message" => __('coupon_code_success'),"total_balance_amt"=>$account_balance,"past_history"=>$driver_recharge_history,"voucher_amount"=>$recharge_amount,"currency"=>$this->site_currency,"status"=>1,"alert_message"=>$alert_message,"account_balance"=>round($account_balance,2));
					}else if($check_recharge_code=='-1')
					{
							
						$message = array("message" => __('invalid_coupon_code'),"status"=>2);	
					}
					else if($check_recharge_code=='-2')
					{
						$blockedvoucherdetails = $api->update_blockedvoucherdetails($array);
						$message = array("message" => __('couponcode_blocked'),"status"=>2);	
					}
					else if($check_recharge_code=='-3')
					{
						$message = array("message" => __('already_used_couponcode'),"status"=>2);	
					}
					else
					{
						$message = array("message" => __('time_coupon_code'),"status"=>2);	
					}
				}else
				{
					$errors = $validator->errors('errors');	
					$message = array("message" => __('validation_error'),"status"=>3,"detail"=>$errors);	
				}
				echo json_encode($message);
			break;
			
			case 'driver_recharge_history':
				$array = $mobiledata;
				$driver_id=isset($array['driver_id'])?$array['driver_id']:'';
				if(!empty($driver_id)){
					$driver_recharge_history = $api->get_driver_recharge_history($driver_id,$array['data_count'],$array['limit']);
					$new_array = array();
					foreach($driver_recharge_history as $history)
					{
						$history['amount']= round($history['amount'],2);
						$history['coupon_code']= $history['coupon_code']."(".$history['payment_type'].")";
						$new_array[] = $history;
					}
					$driver_profile = $api->driver_profile($driver_id);
					if(count($driver_profile)>0){
						if(count($driver_recharge_history)>0){
							$account_balance=isset($driver_profile[0]['account_balance'])?$driver_profile[0]['account_balance']:'0';
							$message = array("message" => __('success'),"total_balance_amt"=>round($account_balance,2),"past_history"=>$new_array,"currency"=>$this->site_currency,"status"=>1);
						}else{
							$message = array("message" => __('no_past_history'),"total_balance_amt"=>"0","status"=>2);
						}
					}else{
						$message = array("message" => __('invalid_user_driver'),"status"=>3);	
					}
				}else{
					$message = array("message" => __('invalid_user_driver'),"status"=>3);	
				}
			echo json_encode($message);
			break;
			
			case 'street_pickup_tripfare_update':
				$array = $mobiledata;
				$api_model = Model::factory(MOBILEAPI_107);
				$pay_mod_id = $array['pay_mod_id'];
				$validator = $this->payment_validation($array);
				$driver_statistics = array();
				if($validator->check())
				{
					$passenger_log_id = $array['trip_id'];
					if($array['actual_distance'] == "")
						$distance = $array['distance'];
					else
					$distance = $array['actual_distance'];
					$actual_amount = $array['actual_amount'];
					$remarks = $array['remarks'];
					$minutes_traveled=$array['minutes_traveled'];
					$minutes_fare=$array['minutes_fare'];
					$base_fare=$array['base_fare'];
					$trip_fare = $array['trip_fare']; //Trip Fare without Tax,Tips and Discounts
					$fare = round($array['fare'],2); //Total Fare with Tax,Tips and Discounts can editable by driver
					$tips = round($array['tips'],2); //Tips Optional
					$nightfare_applicable = $array['nightfare_applicable'];
					$nightfare = $array['nightfare'];
					$eveningfare_applicable = $array['eveningfare_applicable'];
					$eveningfare = $array['eveningfare'];
					$tax_amount = $array['tax_amount'];
					$tax_percentage = $array['company_tax'];
					$fare_calculation_type = isset($array['fare_calculation_type']) ? $array['fare_calculation_type'] : FARE_CALCULATION_TYPE;
					$trip_fare = round($trip_fare,2);
					$total_fare = $fare;
					$amount = round($total_fare,2); // Total amount which is used for pass to payment gateways
					$get_passenger_log_details = $api->getPassengerLogDetail($passenger_log_id);
					if(count($get_passenger_log_details) > 0)
					{
						if($array['pay_mod_id'] == 1)
						{
							try {
								$update_commission = $this->commonmodel->update_commission($passenger_log_id,$total_fare,ADMIN_COMMISSON);
								$insert_array = array(
									"passengers_log_id" => $passenger_log_id,
									"distance" 			=> urldecode($array['distance']),
									"actual_distance" 	=> urldecode($array['actual_distance']),
									"distance_unit" 	=> UNIT_NAME,
									"tripfare"			=> $trip_fare,
									"fare" 				=> $fare,
									"tips" 				=> $tips,
									"waiting_cost"		=> $array['waiting_cost'],
									"passenger_discount"=> 0,
									"promo_discount_fare"=> 0,
									"tax_percentage"	=> $tax_percentage,
									"company_tax"		=> $tax_amount,
									"waiting_time"		=> urldecode($array['waiting_time']),
									"trip_minutes"		=> $minutes_traveled,
									"minutes_fare"		=> $minutes_fare,
									"base_fare"		=> $base_fare,
									"remarks"			=> $remarks,
									"payment_type"		=> $array['pay_mod_id'],
									"amt"				=> $amount,
									"nightfare_applicable" => $nightfare_applicable,
									"nightfare" 		=> $nightfare,
									"eveningfare_applicable" => $eveningfare_applicable,
									"eveningfare" 		=> $eveningfare,
									"admin_amount"		=> $update_commission['admin_commission'],
									"company_amount"	=> $update_commission['company_commission'],
									"driver_amount"		=> $update_commission['driver_commission'],
									"trans_packtype"	=> $update_commission['trans_packtype'],
									"fare_calculation_type"	=> $fare_calculation_type
								);
								$check_trans_already_exist = $api->checktrans_details($passenger_log_id);
								if(count($check_trans_already_exist)>0)
								{
									$tranaction_id = $check_trans_already_exist[0]['id'];
									$update_transaction = $api->update_table(TRANS,$insert_array,'id',$tranaction_id);
								}
								else
								{
									$transaction = $this->commonmodel->insert(TRANS,$insert_array);
								}
								//update travel status in passengers log table
								$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
								$journey = $api->update_journey_status($passenger_log_id,$msg_status,$driver_reply,$journey_status);
								$pickup = $get_passenger_log_details[0]->current_location;
								$detail = array("fare" => $amount,"pickup" => $pickup,"trip_id"=>$passenger_log_id);
								$message = array("message" => __('trip_fare_updated'),"detail"=>$detail,"status"=>1);
							}
							catch (Kohana_Exception $e) {
								$message = array("message" => __('trip_fare_already_updated'), "status"=>-1);
							}
						}
						else
						{
							$message = array("message" => __('invalid_payment_mode'),"status"=>-1);
						}
					}
					else
					{
						$message = array("message" => __('invalid_trip'),"status"=>-1);
					}
				}
				else
				{
					$validation_error = $validator->errors('errors');
					$message = array("message" => $validation_error,"status" => -3);
				}
				echo json_encode($message);
			break;
		}
		/** Switch Case End **/
		exit;
	}
	else
	{
		$message = array("message" => __('invalid_company'),"status"=>-8);
		//"url_explode"=>$find_url,"count"=>count($apikey_result),"encrypt valu"=>$company_api_encrypt,"decrypt valu"=>$company_api_decrypt,"descrypt_split"=>$company_split,"Company APK"=>$company_api_key);
		echo json_encode($message);
		exit;
	}
}	




}
?>