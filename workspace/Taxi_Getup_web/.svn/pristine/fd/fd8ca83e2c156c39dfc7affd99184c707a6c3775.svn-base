<?php  defined('SYSPATH') or die("No direct script access.");
//--------------------------------------------------
/*$path = explode('.',$_SERVER['SCRIPT_NAME']);
		$url = explode('/',$path[0]);
		$cnt = count($url)-1;
		$SEGMENT = "";
		for($i=0; $i<$cnt; $i++){
			$SEGMENT .= $url[$i].'/';
		} */
		
$urlSegments = parse_url($_SERVER["SERVER_NAME"]);
	$urlHostSegments = explode('.', $urlSegments['path']);

	$uploads = "uploads";
	$expiry = 0;
	/*if(count($urlHostSegments) > 2) {
		$uploads = str_replace("-","_",$urlHostSegments[0]);
		if(($uploads == "www")||($uploads == "live")){
			$uploads = "uploads";
			$expiry = 0;
		}
	} */
		
$result = DB::select(SITEINFO.'.admin_commision_setting',SITEINFO.'.company_commision_setting',SITEINFO.'.driver_commision_setting',SITEINFO.'.referral_settings',SITEINFO.'.driver_referral_setting',SITEINFO.'.date_time_format',SITEINFO.'.user_time_zone',SITEINFO.'.default_miles',SITEINFO.'.pagination_settings',SITEINFO.'.price_settings',SITEINFO.'.notification_settings',SITEINFO.'.facebook_key',SITEINFO.'.facebook_secretkey',SITEINFO.'.ios_google_map_key',SITEINFO.'.ios_google_geo_key',SITEINFO.'.web_google_map_key',SITEINFO.'.web_google_geo_key',SITEINFO.'.android_google_key',SITEINFO.'.site_country',SITEINFO.'.site_state',SITEINFO.'.site_city',SITEINFO.'.admin_commission',SITEINFO.'.app_name',SITEINFO.'.site_copyrights',SITEINFO.'.facebook_share',SITEINFO.'.twitter_share',SITEINFO.'.google_share',SITEINFO.'.linkedin_share',SITEINFO.'.sms_enable',SITEINFO.'.driver_tell_to_friend_message',SITEINFO.'.tell_to_friend_message',SITEINFO.'.default_unit',SITEINFO.'.skip_credit_card',SITEINFO.'.cancellation_fare_setting',SITEINFO.'.referral_discount',SITEINFO.'.email_id',SITEINFO.'.show_map',SITEINFO.'.taxi_charge',SITEINFO.'.fare_calculation_type', SITEINFO.'.app_description',SITEINFO.'.continuous_request_time',SITEINFO.'.tax',SITEINFO.'.pre_authorized_amount',SITEINFO.'.google_business_key',SITEINFO.'.customer_android_key',SITEINFO.'.expiry_date',SITEINFO.'.site_currency',SITEINFO.'.site_logo')->from(SITEINFO)
		->where('id', '=', '1')
		->execute()
		->as_array();

$default_currency = DB::select('currency_code','currency_symbol')->from(PAYMENT_GATEWAYS)
		->where('default_payment_gateway', '=', '1')
		->where('company_id', '=', '0')
		->execute()
		->as_array();

define('APPLICATION_NAME','Getuptaxi');

if($_SERVER['SERVER_PORT'] == "443") {
	DEFINE('PROTOCOL','https');
} else {
	DEFINE('PROTOCOL','http');
}

//DEFINE BASE URL(URL_BASE)
DEFINE('URL_BASE',url::base(PROTOCOL,TRUE));	
DEFINE('UPLOADS',$uploads);

DEFINE('TEST_MODE',"T");
DEFINE('LIVE_MODE',"L");
DEFINE('IN_REVIEW',"R");

DEFINE('PRE_AUTHORIZATION_AMOUNT',$result[0]['pre_authorized_amount']);
DEFINE('PRE_AUTHORIZATION_REG_AMOUNT',0);
DEFINE('PRE_AUTHORIZATION_RETRY_REG_AMOUNT',1);
//DEFINE('GOOGLE_MAP_API_KEY',"AIzaSyByFlLjgMjTCIoGEy_Et02p5aMV_XGl5jM");
DEFINE('GOOGLE_MAP_API_KEY',$result[0]['web_google_map_key']);
//DEFINE('GOOGLE_GEO_API_KEY',"AIzaSyCO-f2RoRqVdpYSuMwG6a9wuYwv_wcDQug");
DEFINE('GOOGLE_GEO_API_KEY',$result[0]['web_google_geo_key']);
DEFINE('GOOGLE_TIMEZONE_API_KEY',"AIzaSyDH2zt8Nrwsogdj2r3xS42l6pK9a3rMcCs");
DEFINE('IOS_GOOGLE_MAP_API_KEY',$result[0]['ios_google_map_key']);
DEFINE('IOS_GOOGLE_GEO_API_KEY',$result[0]['ios_google_geo_key']);
DEFINE('ANDROID_GOOGLE_GEO_API_KEY',$result[0]['android_google_key']);
DEFINE('GOOGLE_BUSINESS_KEY_USED_STATUS',$result[0]['google_business_key']);
define('DEFAULT_DATE_TIME_FORMAT',$result[0]['date_time_format']);
define('REFERRAL_SETTINGS',$result[0]['referral_settings']);
define('DRIVER_REFERRAL_SETTINGS',$result[0]['driver_referral_setting']);
define('CUSTOMER_ANDROID_KEY',$result[0]['customer_android_key']);
define('EXPIRY_DATE',$result[0]['expiry_date']);
define('SITE_CURRENCY',$result[0]['site_currency']);
define('SITE_LOGO',$result[0]['site_logo']);

DEFINE('IPINFOAPI_KEY',"3aec9d045fb56ca9da8994707354ed3a85f6ea8ed850aafb284524eb6a5b3bbe");

DEFINE('ENCRYPT_KEY',"ndotencript_");

//set the headers here
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

//View path
define("ADMINVIEW","admin/");
define("COMPANYVIEW","company/");
define("MANAGERVIEW","manager/");
define("VIEW_PATH","pages/");


define("SCRIPTPATH",URL_BASE."public/js/");

//Status
define("ACTIVE","A");
define("INACTIVE","I");
define("DELETED","D");
define("SUCCESS","S");
define("PENDING","P");
define("SUCCESSWITHWARNING","SW");
define("FAILURE","F");
define("FAILUREWITHWARNING","FW");

define("DELIVERED","D");
define("UNDELIVERED","U");

define("ADMIN","A");
define("STOREADMIN","S");
define("NORMALUSER","N");

//define("REC_PER_PAGE",2);
define("PAGE_NO",1);
define("SUCESS",1);
define("FAIL",0);

define("PAID_PACKAGE",6);
/* define("COMMON_ANDROID_PASSENGER_APP",'https://play.google.com/store/apps/details?id=com.taximobility');
define("COMMON_IOS_PASSENGER_APP",'https://itunes.apple.com/'); */
define("COMMON_ANDROID_PASSENGER_APP",'https://play.google.com/store/apps/details?id=com.Taximobility&hl=en');
define("COMMON_IOS_PASSENGER_APP",'https://itunes.apple.com/us/app/taximobility-passenger/id981530483?mt=8');
define("COMMON_ANDROID_DRIVER_APP",'https://play.google.com/store/apps/details?id=com.taximobility.driver');

//Map default location declaration
//define("LOCATION_LATI","37.7913");
//define("LOCATION_LONG","-122.401");
define("LOCATION_LATI","31.9454");
define("LOCATION_LONG","35.9284");
define("LOCATION_ADDR","Amman");
define("IPADDRESS","182.72.62.190");
define("RANDOM_KEY_LENGTH","15");

define("REPLACE_LOGO","##LOGO##");
define("REPLACE_NAME","##NAME##");
define("REPLACE_USERNAME","##USERNAME##");
define("REPLACE_SHAREDUSER","##SHAREDUSER##");
define("REPLACE_CONTROLLERNAME","##CONTROLLERNAME##");
define("REPLACE_PASSWORD","##PASSWORD##");
define("REPLACE_SUBJECT","##SUBJECT##");
define("RESET_LINK","##RESET_LINK##");
define("REPLACE_PHONE","##PHONE##");
define("REPLACE_MESSAGE","##MESSAGE##");
define("REPLACE_PROMOCODE","##PROMOCODE##");
define("REPLACE_OTP","##OTP##");
define("REPLACE_EMAIL","##EMAIL##");
define("REPLACE_SALES_PERSON_EMAIL","##SALES_PERSONEMAIL##");
define("REPLACE_MOBILE","##MOBILE##");
define("REPLACE_SITENAME","##SITENAME##");
define("REPLACE_DOMAINNAME","##DOMAINNAME##");
define("REPLACE_EMAIL_LOGO","##EMAILLOGO##");
define("REPLACE_SITELINK","##SITELINK##");
define("REPLACE_SITEURL","##SITEURL##");//
define("REPLACE_ACTLINK","##ACTLINK##");//
define("REPLACE_REQMESSAGE","##REQMESSAGE##");
define("REPLACE_ORDERLIST","##ORDERS_LIST##");
define("REPLACE_DELIVERYADDRESS","##DELIVERY_ADDRESS##");
define("REPLACE_ORDERID","##ORDERID##");
define("REPLACE_SITEEMAIL","##SITEEMAIL##");
define("REPLACE_NOOFTAXI","##NOOFTAXI##");
define("REPLACE_COMPANY","##COMPANY##");
define("REPLACE_CLIENT_NAME","##CLIENT_NAME##");
define("REPLACE_COUNTRY","##COUNTRY##");
define("REPLACE_CITY","##CITY##");
define("CONTACT_NO","##CONTACT_NO##");
define("REPLACE_PICKUP","##PICKUP##");
define("REPLACE_MAPURl","##MAPURL##");
define("REPLACE_DROP","##DROP##");
define("REPLACE_CURRENCY","##CURRENCY##");
define("REPLACE_PACKAGE","##PACKAGE_NAME##");
define("REPLACE_TAXIS","##NO_OF_TAXIS##");
define("REPLACE_CHARGE","##CHARGE_PER_TAXI##");
define("REPLACE_AMOUNT","##TOTAL_AMOUNT##");
define("REPLACE_COPYRIGHTS","##COPYRIGHTS##");
define("REPLACE_COPYRIGHTYEAR","##COPYYEAR##");

define("REPLACE_ANDROID_PASSENGER_APP","##ANDROID_PASSENGER_APP##");
define("REPLACE_IOS_PASSENGER_APP","##IOS_PASSENGER_APP##");
define("REPLACE_ANDROID_DRIVER_APP","##ANDROID_DRIVER_APP##");
					
define('MESSAGE','##MESSAGE##');
define('TEMPLATE_CONTENT','##TEMPLATE_CONTENT##');
define('SITE_DESCRIPTION','##SITE_DESCRIPTION##');
define("REPLACE_VERIFYLINK","##VERIFYLINK##");

define("REPLACE_STARTDATE","##STARTDATE##");
define("REPLACE_EXPIREDATE","##EXPIREDATE##");
define("REPLACE_USAGELIMIT","##USAGELIMIT##");

define("IMGPATH",URL_BASE."public/admin/images/");
define('PUBLIC_FOLDER',"public/admin");
define('PUBLIC_UPLOADS_FOLDER',"public/".UPLOADS);

define('PUBLIC_IMAGES_FOLDER',"public/images/");
define('PUBLIC_UPLOAD_BANNER_FOLDER',"public/".UPLOADS."/banners/");


define('UPLOADED_FILES',"uploadfiles");
define('USERS_IMGFOLDER',"users/");
define('USER_IMGPATH',PUBLIC_FOLDER.'/'.UPLOADED_FILES.'/'.USERS_IMGFOLDER);
define('USER_IMGPATH_THUMB',USER_IMGPATH.'thumb/');
define('USER_IMGPATH_PROFILE',USER_IMGPATH.'profile/');

define('PUBLIC_FOLDER_IMGPATH',PUBLIC_FOLDER.'/'.'images');
define('PUBLIC_IMGPATH',URL_BASE.'public/'.'images');
define("TEMPLATEPATH","public/emailtemplate/");
define("REPLACE_TAXINO","##TAXINO##");
define('SITE_LOGOIMG',"site_logo");
define('BANNER_IMG',"banners/");
define('LOGOPATH',PUBLIC_FOLDER.'/images/');
define('SITE_LOGO_IMGPATH',PUBLIC_UPLOADS_FOLDER.'/'.SITE_LOGOIMG);
define('BANNER_IMGPATH',PUBLIC_UPLOADS_FOLDER.'/'.BANNER_IMG);
define('TAXI_IMG',"taxi_image/");
define('COMPANY_IMG',"company/");
define('TAXI_IMG_IMGPATH',PUBLIC_UPLOADS_FOLDER.'/'.TAXI_IMG);
define('COMPANY_IMG_IMGPATH',PUBLIC_UPLOADS_FOLDER.'/'.COMPANY_IMG);
define('PASS_IMG',"passenger/");
define('PASS_IMG_IMGPATH',PUBLIC_UPLOADS_FOLDER.'/'.PASS_IMG);
define('TAXI_IMG_WIDTH',340);
define('TAXI_IMG_HEIGHT',260);//
define('TAXI_APP_THMB32_IMG_WIDTH',32);
define('TAXI_APP_THMB32_IMG_HEIGHT',32);
define('TAXI_APP_THMB100_IMG_WIDTH',100);
define('TAXI_APP_THMB100_IMG_HEIGHT',100);
define('COMPANY_IMG_WIDTH',32);
define('COMPANY_IMG_HEIGHT',32);
define('PASS_IMG_WIDTH',140);
define('PASS_IMG_HEIGHT',140);
define('DRIVER_DOC_IMG_WIDTH',500);
define('DRIVER_DOC_IMG_HEIGHT',500);
define('PASS_THUMBIMG_WIDTH',80);
define('PASS_THUMBIMG_HEIGHT',80);
define('PASS_THUMBIMG_WIDTH1',100);
define('PASS_THUMBIMG_HEIGHT1',100);
define('SITE_LOGO_WIDTH',155);
define('SITE_LOGO_HEIGHT',35);
define('BANNER_SLIDER_WIDTH',1600);
define('BANNER_SLIDER_HEIGHT',557);
define('FAVICON_IMG',"favicon/");
define('SITE_FAVICON_IMGPATH',PUBLIC_UPLOADS_FOLDER.'/'.FAVICON_IMG);
define('FAVICON_WIDTH',16);
define('FAVICON_HEIGHT',16);
define('DRIVER_IMG',"driver_image/");
define('SITE_DRIVER_IMGPATH',PUBLIC_UPLOADS_FOLDER.'/'.DRIVER_IMG);
define('WITHDRAW_IMG_PATH',PUBLIC_UPLOADS_FOLDER.'/withdraw_request_attachements/');
define('MOBILE_COMPLETE_TRIP_MAP_IMG_PATH',PUBLIC_UPLOADS_FOLDER.'/complete_trip_map/');
define('MOBILE_TRIP_DETAIL_MAP_IMG_PATH',PUBLIC_UPLOADS_FOLDER.'/trip_detail_map/');
define('MOBILE_PENDING_TRIP_MAP_IMG_PATH',PUBLIC_UPLOADS_FOLDER.'/pending_trip_map/');
define('MOBILE_FAV_LOC_MAP_IMG_PATH',PUBLIC_UPLOADS_FOLDER.'/favourite_location_map/');
define('MOBILE_iOS_IMAGES_FILES',PUBLIC_UPLOADS_FOLDER.'/iOS/');
define('MOBILE_ANDROID_IMAGES_FILES',PUBLIC_UPLOADS_FOLDER.'/android/');

//Taxi Dispatch
	define('BOOTSTRAP_IMGPATH',URL_BASE.'public/bootstrap-3.2.0/vendor/bootstrap/images');
	define('TAXI_DISPATCH',"admin/taxi_dispatch/");
	define('DATATABLE_CSSPATH',URL_BASE."public/bootstrap-3.2.0/vendor/Datatable/css/");
	define('DATATABLE_JSPATH',URL_BASE."public/bootstrap-3.2.0/vendor/Datatable/js/");
	define('TDISPATCH_VIEW',1); //1-Show , 0-Hide
	define('MAP_COUNTRY','JO');


define('CURRENCY',$default_currency[0]['currency_symbol']);
define('CURRENCY_FORMAT',$default_currency[0]['currency_code']);
define('REC_PER_PAGE',$result[0]['pagination_settings']);
define('FARE_SETTINGS',$result[0]['price_settings']);
define('ADMIN_NOTIFICATION_TIME',$result[0]['notification_settings']);
define('CONTINOUS_REQUEST_TIME',$result[0]['continuous_request_time']);
define('FB_KEY',$result[0]['facebook_key']);
define('FB_SECRET_KEY',$result[0]['facebook_secretkey']);
define('DEFAULT_COUNTRY',$result[0]['site_country']);
define('DEFAULT_STATE',$result[0]['site_state']);
define('DEFAULT_CITY',$result[0]['site_city']);
define('APP_DESCRIPTION',$result[0]['app_description']);
define('TOTAL_RATING',5);
define('REMINDER_TIME',1);
define('ADMIN_COMMISSON',$result[0]['admin_commission']);
define('TAX',$result[0]['tax']);
define('MIN_FUND',"");
define('MAX_FUND',"");
define('SITE_NAME',$result[0]['app_name']);
define('SITE_COPYRIGHT',$result[0]['site_copyrights']);
define('FB_SHARE',$result[0]['facebook_share']);
define('TW_SHARE',$result[0]['twitter_share']);
define('GOOGLE_SHARE',$result[0]['google_share']);
define('LINKEDIN_SHARE',$result[0]['linkedin_share']);
define('SMS',$result[0]['sms_enable']);

define('DRIVER_TELL_TO_FRIEND_MESSAGE',$result[0]['driver_tell_to_friend_message']);
define('REFERRAL_DISCOUNT',$result[0]['referral_discount']);
define('SITE_EMAIL_CONTACT',$result[0]['email_id']);
define('SHOW_MAP',$result[0]['show_map']);
define('TAXI_CHARGE',$result[0]['taxi_charge']);
define('SITE_FARE_CALCULATION_TYPE',$result[0]['fare_calculation_type']);

define('BOOK_BY_PASSENGER',1);
define('BOOK_BY_CONTROLLER',2);

define('CURRENCY_SYMB',$default_currency[0]['currency_symbol']);
define('REPLACE_COMPANYNAME','##COMPANYNAME##');
define('REPLACE_COMPANYDOMAIN','##COMPANYDOMAIN##');
define('LOCATIONUPDATESECONDS',15);
define('DEFAULTMILE',$result[0]['default_miles']);
define('DEFAULT_DRIVER_MILE',3);
define('TRAILEXPIRY',10);
define('DEFAULT_CONNECTION','default');
define('ADMIN_COMMISION_SETTING',$result[0]['admin_commision_setting']);
define('COMPANY_COMMISION_SETTING',$result[0]['company_commision_setting']);
define('DRIVER_COMMISION_SETTING',$result[0]['driver_commision_setting']);




//DEFINE("EMAILTEMPLATELOGO",URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png');

//print_r($smtp_result[0]);

define("SMTP",1); 		
define("NIGHT_FROM","20:00:00"); 
define("NIGHT_TO","05:59:59"); 
define("EVENING_FROM","16:00:00"); 
define("EVENING_TO","19:59:59"); 
define("SAR_EQUAL_USD","0.27");
define('REPLACE_TRIPDETAILS','##TRIP_DETAILS##');



$subdomainname = '.'.$_SERVER["HTTP_HOST"];
define("SUB_DOMAIN_NAME",$subdomainname); 
define("DOMAIN_NAME",'getuptaxi.com'); 
define("DOMAIN_URL_NAME",'www.getuptaxi.com'); 

//$url=URL_BASE;
//$url="http://demo.taximobility.com"; //odeeluxury11@gmail.com
//$url="http://512rides.taximobility.com";
//$subdomain=$this->getUrlSubdomain($url);
//define("SUBDOMAIN",$subdomain);
//define("LIVECHATSTATUS","0");
//define('MONGODBPATH',APPPATH.'vendor/mongp-common.php');

/* TO get the livechat status */ 
//View::bind_global('subdomain',$this->subdomain);
$dynamicTimeZone = isset($result[0]["user_time_zone"]) ? $result[0]["user_time_zone"] : 'Asia/Amman';
define("COMPANY_CID",0);
define("TIMEZONE",$dynamicTimeZone);
define("USER_SELECTED_TIMEZONE",$dynamicTimeZone);	

define('CHECK_EXPAIRY',$expiry);
$currentTime = convert_timezone('now',$dynamicTimeZone);
$cTsatmp = strtotime($currentTime);
$expiryTime = isset($result[0]["expiry_date"]) ? $result[0]["expiry_date"] : '';
if(!empty($expiryTime) && $expiryTime < $cTsatmp && CHECK_EXPAIRY!=0){ 
	$message = array("message" => "Your host has been expired","status" => 2);
	echo json_encode($message);exit;
}
	
/*if(is_null($subdomain))
{

  define("COMPANY_CID",0);
  define("TIMEZONE",'America/New_York');	
 
}
else
{
   $company_cid=findcompanyid($subdomain);
   /** Added for redirection to site when subdomain not present *
   if($company_cid==0){
		header('Location: http://www.taximobility.com/');
		exit;
	}
   /** Added for redirection to site when subdomain not present **/ 
   /* define("COMPANY_CID",$company_cid);
   $company_timezone=$this->findcompany_timezone($company_cid);

   if(($company_timezone !='') || ($company_timezone !='0') )
   {
		define("TIMEZONE",$company_timezone);	
   }
   else
   {
		define("TIMEZONE",'America/New_York');
   } */
   
	/** Added for redirection to site when subdomain not present **
	define("COMPANY_CID",$company_cid);
	$company_timezone = findcompany_timezone($company_cid);
	if($company_cid == 0) {
		$user_selected_company_timezone = $result[0]["user_time_zone"];
	} else {
		$user_selected_company_timezone = findcompany_user_selected_timezone($company_cid);
	}

	if($user_selected_company_timezone != "" || $user_selected_company_timezone != 0) {
		define("USER_SELECTED_TIMEZONE",$user_selected_company_timezone);
	} else {
		define("USER_SELECTED_TIMEZONE","America/New_York");
	}

	if(($company_timezone != '') || ($company_timezone != 0)) {
		define("TIMEZONE",$company_timezone);
	} else {
		define("TIMEZONE",'America/New_York');
	}
}*/
//echo SUBDOMAIN;
//echo COMPANY_CID;exit;
//define("COMPANY_CID",0);
/* if(COMPANY_CID != 0) {
       $email_logo = URL_BASE.SITE_LOGO_IMGPATH.'/'.SUBDOMAIN.'_email_logo.png';
} else { */
       $email_logo = URL_BASE.SITE_LOGO_IMGPATH.'/site_email_logo.png';
//}
DEFINE("EMAIL_TEMPLATE_LOGO",$email_logo);
DEFINE("EMAILTEMPLATELOGO",$email_logo);

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
	
	function findcompany_user_selected_timezone($company_cid)
	{
		$rs = DB::select()->from(COMPANY)
				->where('cid','=',$company_cid)
				->execute()
				->as_array();
		return (count($rs) > 0 && $rs[0]['user_time_zone'] != "") ? $rs[0]['user_time_zone'] : $rs[0]['time_zone'];
	}

	function findcompanyid($subdomain)
	{
			$rs = DB::select(COMPANYINFO.'.company_cid')->from(COMPANYINFO)
				->where('company_domain','=',$subdomain)
				->execute()
				->as_array();	
			if(count($rs)>0)
			return $rs[0]['company_cid'];
			else
			return 0;		
	}


	define("COMPANY_SITENAME",SITE_NAME);
	define("COMPANY_NOTIFICATION_TIME",60);
	define("COMPANY_CUSTOMER_APP_URL",'https://play.google.com/store/apps/details?id=com.taximobility');
	define("COMPANY_DRIVER_APP_URL",'https://play.google.com/store/apps/details?id=com.taximobility.driver');
	define('TELL_TO_FRIEND_MESSAGE',$result[0]['tell_to_friend_message']);
	define("DEFAULT_UNIT",$result[0]['default_unit']);
	define("DEFAULT_SKIP_CREDIT_CARD",$result[0]['skip_credit_card']);
	define("COMPANY_CURRENCY",CURRENCY);
	//define("CANCELLATION_FARE",0);
	define("CANCELLATION_FARE",$result[0]['cancellation_fare_setting']);
	define("COMPANY_COPYRIGHT",SITE_COPYRIGHT);
	define("CONTACT_EMAIL",SITE_EMAIL_CONTACT);
	define("COPYRIGHT_YEAR","2016");
	/**************** Convert the Timezone *********/
	function convert_timezone($time,$timezone='')
	{	
		//echo $timezone;
		//$timezone = 'Europe/London';  
		//$timezone = 'America/Chicago';
		//$timezone = 'Asia/Kolkata';
		$date = new DateTime($time, new DateTimeZone($timezone));
		$localtime = $date->format('Y-m-d H:i:s');
		return $localtime;
	}

	
	
	//ENCRIPTION AND DECRIPTION FUNCTION
	function encrypt_decrypt($action, $string) {
	   $output = false;

	   $key = 'Taxi Application';

	   // initialization vector 
	   $iv = md5(md5($key));

	   if( $action == 'encrypt' ) {
	       //$output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
	       $output = base64_encode(ENCRYPT_KEY.$string);
	   }
	   else if( $action == 'decrypt' ){
	       //$output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
	       //$output = rtrim($output, "");
	       $decrypt_val = base64_decode($string);
	       $split = explode('_',$decrypt_val);	
			
	       if(count($split)>1)	
	       {
				$output = trim($split[1]);
		   }
		   else
		   {
				$output = "";
		   }
	   }
	   return $output;
	}	
	// Repeat X function
	function repeatx($data,$repeatstring,$repeatcount)
	{
		if($data != "")
		{
			if($repeatcount == 'All')
			{
				return str_repeat($repeatstring, (strlen($data)));
				
			}
			else
			{				
				return str_repeat($repeatstring, (strlen($data) - $repeatcount)) . substr($data,-$repeatcount,$repeatcount);
			}
		}
		else
		{
			return 0;
		}
	}
	

	/* define("SENDGRID_HOST",'smtp.sendgrid.net');
	define("SENDGRID_PORT",'25');
	define("SENDGRID_USERNAME",'Taxindot9');
	define("SENDGRID_PASSWORD",'taxisendto&6!'); */

	/*** Language setting for mobile application*****/	
	$lang = (isset($_GET))?(Arr::get($_GET,'lang')):'en';
	if($lang == ''){
		$lang='en';
	}
	define("LANG",$lang);
	/***********************************************/

	//define("SUPERADMIN_EMAIL","superadmin@taximobility.com");

	
	/*if(COMPANY_CID!=0){
		define('UNIT',DEFAULT_UNIT); 
	}else{
		define('UNIT',0); // 0 as Kilometer , 1 as Miles
	} */
	define('UNIT',DEFAULT_UNIT);

	if(UNIT==1){
		define('UNIT_NAME','MILES');
	}else{
		define('UNIT_NAME','KM');
	}

	/* if(COMPANY_CID!=0){
		
		define('FARE_CALCULATION_TYPE',COMPANY_FARE_CALCULATION_TYPE);  //1 => Distance, 2 => Time, 3=> Distance / Time
	}else{ */
		//define('SKIP_CREDIT_CARD',1); // 1 as Skip , 0 as No-Skip
		define('FARE_CALCULATION_TYPE',SITE_FARE_CALCULATION_TYPE); //1 => Distance, 2 => Time, 3=> Distance / Time
	//}
	define('SKIP_CREDIT_CARD',DEFAULT_SKIP_CREDIT_CARD); // 1 as Skip , 0 as No-Skip
	/****Tell to friend-App URL Display by PAID VERSION Start****/
/*	if(COMPANY_CID!=0)
	{
		$pp = DB::select('upgrade_packageid')->from(PACKAGE_REPORT)
					->where('upgrade_companyid','=',COMPANY_CID)
					->order_by('upgrade_id', 'DESC')
					->limit(1)
					->execute()
					->as_array();
		if(count($pp)>0){
			if($pp[0]['upgrade_packageid']==PAID_PACKAGE){ //6 as PAID PACKAGE
				$app=explode('|',COMPANY_CUSTOMER_APP_URL);
				$android_passenger=isset($app[0])?$app[0]:"";
				$ios_passenger=isset($app[1])?$app[1]:"";
				$android_driver=COMPANY_DRIVER_APP_URL;
				define("ANDROID_PASSENGER_APP",$android_passenger);
				define("IOS_PASSENGER_APP",$ios_passenger);
				define("ANDROID_DRIVER_APP",$android_driver);
			}else{
				define("ANDROID_PASSENGER_APP",COMMON_ANDROID_PASSENGER_APP);
				define("IOS_PASSENGER_APP",COMMON_IOS_PASSENGER_APP);
				define("ANDROID_DRIVER_APP",COMMON_ANDROID_DRIVER_APP);
			}
		}
	}
	else
	{ */
		define("ANDROID_PASSENGER_APP",COMMON_ANDROID_PASSENGER_APP);
		define("IOS_PASSENGER_APP",COMMON_IOS_PASSENGER_APP);
		define("ANDROID_DRIVER_APP",COMMON_ANDROID_DRIVER_APP);
	//}	
	/**** Driver tracking DB initializing****/
	define('DRIVER_TRACK_DB','driver_tracking'); //Access Database 2

?>

