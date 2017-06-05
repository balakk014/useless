	<?php defined('SYSPATH') or die('No direct access allowed.');
	/**
	 * Modulename — My own custom module.
	 *
	 * @package    Commonfunction
	 * @category   Base
	 * @author     Myself Team
	 * @copyright  (c) 2012 Myself Team
	 * @license    http://kohanaphp.com/license.html
	 */
	class Kohana_Commonfunction {
	
	
	 /**
	 * @var array configuration settings
	 */
	 protected $_config = array();
	
	 /**
	  * Class Main Constructor Method
	  * This method is executed every time your module class is instantiated.
	  */
	 public function __construct() {	 

	 }


	/**
	* ****Image resize ****
	* @return Image listings  */

	static function logoresize($image_factory,$width,$height,$path,$image_name,$quality=90)
	{
	        $image_name = $image_name.'.png';
		     	
		$image_factory->resize($width, $height, Image::NONE);
		$image_factory->crop($width, $height);                    
		$image= $image_factory->save($path.$image_name,90);
		return  $image;

	}
	
	static function imageresize($image_factory,$width,$height,$path,$image_name,$quality=90)
	{
		    if ($image_factory->height < $height || $image_factory->width < $width )
		    {
		          $image = $image_factory->save($path.$image_name,90);
		          return  $image; 
		    }
		    else
		    {
		            $image_factory->resize($width, $height, Image::NONE);
		            $image_factory->crop($width, $height);                    
		            $image= $image_factory->save($path.$image_name,90);
		     return  $image;
		    }

	}

	static function taxiimageresize($image_factory,$width,$height,$path,$image_name,$quality=90)
	{
		            $image_factory->resize($width, $height, Image::NONE);
		            $image_factory->crop($width, $height);                    
		            $image= $image_factory->save($path.$image_name,90);
		     return  $image;

	}
	
	static function multipleimageresize($image_factory,$width,$height,$path,$image_name,$quality=90)
	{
		    $image_name = $image_name.'.png';
		     	
		    /*if ($image_factory->height < $height || $image_factory->width < $width )
		    {
		          $image = $image_factory->save($path.$image_name,90);
		          return  $image; 
		    }
		    else
		    {*/
		            $image_factory->resize($width, $height, Image::NONE);
		            $image_factory->crop($width, $height);                    
		            $image= $image_factory->save($path.$image_name,90);
		     return  $image;
		    /*}*/

	}
	
	
	
	/**To Get Current TimeStamp**/
	static function getCurrentTimeStamp()
	{
		return date('Y-m-d H:i:s', time());
	}
	
	/** TO get the After days TImestamp **/
	
	static function getExpiryTimeStamp($current_time='',$days='')
	{
	

		$query = " SELECT DATE_ADD('$current_time', INTERVAL $days DAY) as date";

 		$results = Db::query(Database::SELECT, $query)
		   			 ->execute()
					 ->as_array();
						 		
          
		return $results[0]['date'];
	}
	
	static function getExpiredTimeStamp($last_expirydate='',$days='')
	{
	

		$query = " SELECT DATE_ADD('$last_expirydate', INTERVAL $days DAY) as date";

 		$results = Db::query(Database::SELECT, $query)
		   			 ->execute()
					 ->as_array();
						 		
          
		return $results[0]['date'];
	}	
	   //function for generating random key
		//=================================	 
	 static function admin_random_user_password_generator()
	 {
		 $string = Text::random('hexdec', RANDOM_KEY_LENGTH);
	
	    return $string;
	 	}

         //function for generating random key
		//=================================	 
	 static function randomkey_generator($length=0)
	 {
	        $length = ($length ==0)?RANDOM_KEY_LENGTH:$length;
		 $string = Text::random('hexdec',$length);
	
	    return $string;
	 	}

/*
	static public function select_slider_settings()
	{		
		$result=DB::select()->from('banner_management')
		            ->where('banner_status','=',1)
			    ->execute()	
			    ->as_array();
                return  $result;
	}	    
*/
	
	  static function change_time($time="") 
	  {
	         $time = strtotime($time).'<br />';

		//echo date("Y-m-d h:i:s", time()).'<br/>';
	          $c_time = time() - $time;
	          //echo $c_time;
	          if ($c_time < 60) {
	                  return '0 minute ago';
	          } else if ($c_time < 120) {
	                  return '1 minute ago';
	          } else if ($c_time < (45 * 60)) {
	                  return floor($c_time / 60) . ' minutes ago';
	          } else if ($c_time < (90 * 60)) {
	                  return '1 hour ago.';
	          } else if ($c_time < (24 * 60 * 60)) {
	                  return floor($c_time / 3600) . ' hours ago';
	          } else if ($c_time < (48 * 60 * 60)) {
	                  return '1 day ago.';
	          } else {
	                  return floor($c_time / 86400) . ' days ago';
	          }
	  }
	  
	static public function remove_comma_default_string($string = ""){
   
   		$string = trim($string);
   		
   		//$string_output ="";
		//remove default comma from return addr string
		if(substr($string,0,1) == ",")
		{
			$string = substr($string,1,strlen($string));		
		}  		 
		if(substr($string,-1) == ",")
		{		
			$string = substr($string,0,strlen($string)-1);
		
		}  	
   		return $string;   
   }
 
	//for calculating distance based on lat and lon
	//=============================================
	
	static public function get_distance($lat1, $lon1, $lat2, $lon2, $unit){
		
		  $lat1 = ($lat1)?(double)$lat1:(double)"0.00";
		  $lon1 = ($lon1)?(double)$lon1:(double)"0.00";
		  $lat2 = ($lat2)?(double)$lat2:(double)"0.00";
		  $lon2 = ($lon2)?(double)$lon2:(double)"0.00";
		  

		  $theta = $lon1 - $lon2; 
		  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
		  $dist = acos($dist); 
		  $dist = rad2deg($dist); 
		  $miles = $dist * 60 * 1.1515;
		  $unit = strtoupper($unit);

		  if ($unit == "K") {
			return ($miles * 1.609344); 
		  } else if ($unit == "N") {
			  return ($miles * 0.8684);
		  } else {
		  	
				return $miles;
		  }	
	}
	
    
    static public function currency_convertor($from_Currency,$to_Currency,$amount)
	{
		
		$from_Currency = explode('-',$from_Currency);
		$from_Currency = $from_Currency[1];
		$url = "http://www.google.com/ig/calculator?hl=en&q=$amount$from_Currency=?$to_Currency";
		$ch = curl_init();
		$timeout = 0;
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$rawdata = curl_exec($ch);
		curl_close($ch);
		$data = explode('"', $rawdata);
		$data = explode(' ', $data['3']);
		$var = $data['0'];  //round($var,2);			

		return sprintf ("%.2f", $var);//number_format($var, 2, '.', '');
	}

	//Save original image size
	static function imageoriginalsize($image_factory,$path,$image_name,$quality=90)
	{
		$image= $image_factory->save($path.$image_name,$quality);
		 return  $image;
	}
	
	//function for php 5.5 mysqli_real_escape_string function expects paramerter to be database connection link. So here this function will solve the issue with old mysql_real_escape_string
   static function real_escape_string($data)
   {
	   $result = Database::instance()->escape($data);
	   return $result;
   }
   
	static public function get_user_wallet_amount()
	{
		$session = Session::instance();
		$result["wallet_amount"] = 0; $result["referral_code"] = "";
		$query = DB::select('wallet_amount','referral_code')->from(PASSENGERS)->where('id','=',$session->get("id"))->limit(1)->execute()->as_array();
		if(isset($query[0]["wallet_amount"])) {
			$result["wallet_amount"] = $query[0]["wallet_amount"];
		}
		if(isset($query[0]["referral_code"])) {
			$result["referral_code"] = $query[0]["referral_code"];
		}
		return $result;
	}
	
	static function getDateTimeFormat($value,$type)
	{
		if($value != "0000-00-00 00:00:00" && $value != "0000-00-00") {
			if(($type == 1) || ($type == 2)) {
				$date = new DateTime($value, new DateTimeZone(TIMEZONE));
				$date->setTimezone(new DateTimeZone(USER_SELECTED_TIMEZONE));
				$value = $date->format('Y-m-d H:i:s');
				if($type == 1) {
					$value = str_replace('/', '-', $value);
					$timestamp = strtotime($value);
					return date(DEFAULT_DATE_TIME_FORMAT, $timestamp);
				} else if($type == 2) {
					$timestamp = strtotime($value);
					$format = explode(" ",DEFAULT_DATE_TIME_FORMAT);
					$format = isset($format[0]) ? $format[0] : DEFAULT_DATE_TIME_FORMAT;
					return date($format, $timestamp);
				} 
			} else { //$type == 3
				$timestamp = strtotime($value);
				return date(DEFAULT_DATE_TIME_FORMAT, $timestamp);
			}
		} else {
			return $value;
		}
	}
	
	static function ensureDatabaseFormat($date,$type)
	{
		//echo TIMEZONE.'----'.USER_SELECTED_TIMEZONE; exit;
		$date = new DateTime($date, new DateTimeZone(USER_SELECTED_TIMEZONE));
		$date->setTimezone(new DateTimeZone(TIMEZONE));
		$date = $date->format('Y-m-d H:i:s');
		$date = str_replace('/', '-', $date);
		$date = explode(" ",$date);
		//print_r($date);
		if(isset($date[1])) {
			$start_con = $end_con = $date[1];
		} else {
			$start_con = "00:00:00";
			$end_con = "23:59:59";
		}
		if($type == 1) {
			$date = $date[0]." ".$start_con;
		} else if($type == 2) {
			$date = $date[0]." ".$end_con;
		} else if($type == 3) {
			return date("Y-m-d", strtotime($date[0]));
		}
		$timestamp = strtotime($date);
		return date("Y-m-d H:i:s", $timestamp);
	}
	
	static function getCityName($latitude,$longitude)
	{
		$city = "";
		if($latitude != "" && $longitude != "") {
			$geocode = @file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&sensor=false');
			$output = json_decode($geocode);
			if(isset($output->results[0]->address_components) && !empty($output->results[0]->address_components)){
				for($j=0;$j<count($output->results[0]->address_components);$j++) {
					$cn = array($output->results[0]->address_components[$j]->types[0]);
					if(in_array("locality", $cn))
					{
						$city = strtolower($output->results[0]->address_components[$j]->long_name);
					}
				}
			}
		}
		return $city;
	}
	
	static function checkRequestID()
	{
		$string = Text::random('hexdec', 10);
		$query = "SELECT withdraw_request_id from ".WITHDRAW_REQUEST." where request_id = '$string'";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		if(count($result) == 0) {
			return $string;
		} else {
			commonfunction::checkRequestID();
		}
	}
	
	static function getDriverTripWallet($id)
	{
		$sql = "SELECT sum(amount) as tot_amount FROM ".DRIVER_WALLET_TRANS." where status =0 and driver_id=$id";
		return Db::query(Database::SELECT, $sql)->execute()->current();
	}
}
