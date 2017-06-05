<?php defined('SYSPATH') or die('No direct script access.');
/****************************************************************

* Contains API details

* @Package: ConnectTaxi
driver_arrived
* @Author:  senthilkumar.a@ndot.in Team

* @URL : http://www.ndot.in

********************************************************************/
Class Controller_Api extends Controller_Website
{

	public function __construct()
	{	
		try {
		DEFINE('UPLOADS','uploads');
		//require Kohana::find_file('classes','table_config');
		//require Kohana::find_file('classes','common_config');
		$lang = I18n::lang('en');
		$this->commonmodel=Model::factory('commonmodel');
		$this->siteemail=$this->commonmodel->select_site_settings('email_id',SITEINFO);
		$this->app_name = $this->commonmodel->select_site_settings('app_name',SITEINFO);	
		$this->app_description=$this->commonmodel->select_site_settings('app_description',SITEINFO);	
		$this->emailtemplate=Model::factory('emailtemplate');
		$this->notification_time = $this->commonmodel->select_site_settings('notification_settings',SITEINFO);		
		$this->customer_google_api = $this->commonmodel->select_site_settings('customer_android_key',SITEINFO);		
		$this->driver_android_api = $this->commonmodel->select_site_settings('driver_android_key',SITEINFO);	
		$this->customer_app_url = $this->commonmodel->select_site_settings('customer_app_url',SITEINFO);	
		$this->driver_app_url = $this->commonmodel->select_site_settings('driver_app_url',SITEINFO);		
		$this->google_geocode_api = $this->commonmodel->select_site_settings('google_geocode_api',SITEINFO);		
		$this->app_description = $this->commonmodel->select_site_settings('app_description',SITEINFO);
		$this->continuous_request_time = $this->commonmodel->select_site_settings('continuous_request_time',SITEINFO);

		$this->currentdate=Commonfunction::getCurrentTimeStamp();
		}
		catch (Database_Exception $e)
		{
			 // Insert failed. Rolling back changes...
			 //print_r($e);
		}
	}
	public function action_index()
	{	

		$find_url = explode('/',urldecode($_SERVER['REQUEST_URI']));  	
		//print_r($find_url);
		$split = explode('?',$find_url[3]);  	
		$company_api_encrypt = trim($split[0]);

		/*$company_api_key = "ntaxi"."_"."bUmS6F1BLT68U6mZGXh3gDUvXNADj4";

		$encrypted_txt = $this->encrypt_decrypt('encrypt', $company_api_key);
		echo "Encrypted Text = $encrypted_txt\n";
		echo "<br />";
		
		//exit;*/
		$company_api_decrypt = $this->encrypt_decrypt('decrypt', $company_api_encrypt);
		
		//echo $company_api_decrypt;


		$company_split = explode('_',$company_api_decrypt);

		$company_api_key = trim($company_split[1]);
		//print_r($company_api_key);
		$api = Model::factory('api');	
		$array = $_GET;
		$errors = array();
		
		$method = $_REQUEST["type"];

		$apikey_result =array();

		//CHECK FOR VALID API KEY
		if($company_api_key != 'all')
		{
		$apikey_query = "select company_cid,company_currency from companyinfo left join company on company.cid=companyinfo.company_cid where company_api_key='".$company_api_key."' and company_status='A'";

		$apikey_result =  Db::query(Database::SELECT, $apikey_query)
			->execute()
			->as_array(); 
		}
		
		if((count($apikey_result) > 0) || ($company_api_key == 'all') )
		{ 
			if($company_api_key == 'all')
			{
				$default_companyid = '';
				$this->site_currency='';
			}
			else
			{
				$default_companyid = $apikey_result[0]['company_cid'];
				$this->site_currency = $apikey_result[0]['company_currency'];
			}
//echo $default_companyid;
		switch($method)
		{
			//Company URL : http://192.168.1.88:1000/api/index/dGF4aV9YRlJJb1p0NjdxYTU5ZmlIRFl1OGJPQ0J2elRHQVYxZmY=?type=getcoreconfig
			//All Company URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=getcoreconfig
			case 'getcoreconfig':
			$config_array = $api->select_site_settings($default_companyid);
			if(count($config_array) > 0)
			{				
				if($default_companyid == '')
				{
					$config_array[0]['api_url'] = URL_BASE.'api';
					$config_array[0]['passenger_image_base'] = URL_BASE.'public/'.UPLOADS.'/passenger/';
					$config_array[0]['taxi_image_base'] = URL_BASE.'public/'.UPLOADS.'/taxi_image/';
					$config_array[0]['driver_image_base'] = URL_BASE.'public/'.UPLOADS.'/driver_image/';
					$config_array[0]['noimage_base'] = URL_BASE.'/public/images/noimages.jpg';
					$config_array[0]['api_base'] = URL_BASE;
					$config_array[0]['item_perpage'] = 10;
					//$config_array[0]['facebook_key'] = FB_KEY;
					$config_array[0]['below_above_km'] = 10;
					$config_array[0]['company_logo'] = " ";
					$config_array[0]['logo_base'] = URL_BASE.'/public/admin/images/';
					$config_array[0]['driver_tracking'] = 'S';
					$config_array[0]['google_geocode_api'] = $this->google_geocode_api;
					$config_array[0]['aboutpage_description'] = $this->app_description;
					$config_array[0]['admin_email'] = $this->siteemail;
					$config_array[0]['tell_to_friend_subject'] = __('telltofrien_subject');
				}
				else
				{
					$package_details = $api->current_package_details($default_companyid);
					$driver_tracking = 'N';
					if(count($package_details)>0)
					{
						$driver_tracking = $package_details[0]['driver_tracking'];
					}
					$config_array[0]['api_url'] = URL_BASE.'api';
					$config_array[0]['passenger_image_base'] = URL_BASE.'public/'.UPLOADS.'/passenger/';
					$config_array[0]['taxi_image_base'] = URL_BASE.'public/'.UPLOADS.'/taxi_image/';
					$config_array[0]['driver_image_base'] = URL_BASE.'public/'.UPLOADS.'/driver_image/';
					$config_array[0]['noimage_base'] = URL_BASE.'/public/images/noimages.jpg';
					$config_array[0]['api_base'] = URL_BASE;
					$config_array[0]['item_perpage'] = 10;
					$config_array[0]['site_country'] = "";
					$config_array[0]['admin_commission'] = "";
					$config_array[0]['site_logo'] = "";
					$config_array[0]['twitter_key'] = "";
					$config_array[0]['twitter_secretkey'] = "";						
					$config_array[0]['below_above_km'] = 10;
					$config_array[0]['site_logo'] = " ";
					$config_array[0]['logo_base'] = URL_BASE.'/public/'.UPLOADS.'/site_logo/';	
					$config_array[0]['driver_tracking'] = $driver_tracking;	
					$config_array[0]['google_geocode_api'] = $this->google_geocode_api;	
					$config_array[0]['aboutpage_description'] = $this->app_description;		
					$config_array[0]['admin_email'] = $this->siteemail;	
					$config_array[0]['tell_to_friend_subject'] = __('telltofrien_subject');
				}
				$message = array("message" =>__('success'),"detail" => $config_array,"status" => 1);
			}
			else
			{
				$message = array("message" => __('failed'),"status" => 2);
			}
			echo json_encode($message);
			break;
			
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=all_model_miles_details		
			case 'all_model_miles_details' :
			
					$search_array = $_GET;	
					$add_model = Model::factory('add');		
					$find_model = Model::factory('find');
					$motor_details = $api->motor_details();
					$miles_details = $find_model->getMiles();
					$motor_id = 1;
					$model_details = $find_model->getmodel_details($motor_id );
					//print_r($miles_details);
					if(count($motor_details) > 0)
					{
							$motor_company = $motor_details;
							$model_status = array("model_status"=>1);
					}
					else
					{
							$motor_company = array("message" => __('no_motor_company'));	
							$model_status = array("model_status"=>0);
					}
					if(count($model_details) > 0)
					{																		
							$sub_model = $model_details;
							$sub_model_status = array("sub_model_status"=>1);
					}
					else
					{
							$sub_model = array("message" => __('no_model'));	
							$sub_model_status = array("sub_model_status"=>0);
					}
					$allmile = array();
					$miles_value = array();
					if(count($miles_details) > 0)
					{
							$i=0;
							foreach($miles_details as $value)
							{
								$miles_value['id'] = $value['id']; 
								$miles_value['mile_name'] = $value['mile_name'].' '.__('miles'); 			
								$allmile[] = $miles_value;	
								$i++;			
							}
							$miles = $allmile;
							$miles_status = array("miles_status"=>1);
					}
					else
					{
							$miles = array("message" => __('no_miles'));	
							$miles_status = array("miles_status"=>0);
					}		
					$motorresult = array("motorcompany"=>$motor_company,"motor_status"=>$model_status);
					$milesresult = array("miles"=>$miles,"miles_status"=>$miles_status);
					$detail = array("motorcompany"=>$motorresult,"miles"=>$milesresult);
					$message = array("message" =>__('success'),"detail"=> $detail,"status" => 1);				
					echo json_encode($message);
					break;
					
			//URL : api/?type=sub_model_details&motor_id=2	
			case 'sub_model_details' :
			
					if($array['motor_id'] != null)
					{
						$search_array = $_GET;	
						$motor_id = $array['motor_id'];
						$find_model = Model::factory('find');
						$model_details = $find_model->getmodel_details($motor_id );
						
						if(count($model_details) > 0)
							$message = array("message" => __('success'),"detail"=> $model_details,"status" => 1);	
						else
							$message = array("message" => __('no_submodel'),"status" => 0);		
					}
					else
					{
						$message = array("message" => __('invalid_motor_id'),"status"=>-1);	
					}
					echo json_encode($message);
					break;				
				
					
			//URL : api/?type=all_additional_fields		
			case 'all_additional_fields' :
			
					$search_array = $_GET;	
					$add_model = Model::factory('add');		
					$additional_fields = $add_model->taxi_additionalfields();		
					if(count($additional_fields) > 0)
							$message = array("message" => __('success'),"detail" => $additional_fields,"status" => 1);							
						else
							$message = array("message" => __('no_additional'),"status" => 0);	
					echo json_encode($message);
					break;
			
			//URL :http://192.168.1.88:1020/api/?type=driver_location_history&driver_id=8&trip_id=268&locations=11.017194,76.964758&status=A&device_token=
			case 'driver_location_history':

					$location_array = $_POST;
					//print_r($location_array);exit;
					$api = Model::factory('api');	
					$commonmodel=Model::factory('commonmodel');	
					$company_all_currenttimestamp = $commonmodel->getcompany_all_currenttimestamp($default_companyid);
					
					if(!empty($location_array))
					{
					   $history_validator = $this->history_validation($location_array);
					   if($history_validator->check())
					   {
						   	$driver_status = $location_array['status'];
							$device_token = $location_array['device_token'];
							$driver_id = $location_array['driver_id'];
							$coordinates = explode(',',$location_array['locations']);
							$latitude = empty($coordinates['0'])?'0.0':$coordinates['0'];		
							$longitude = empty($coordinates['1'])?'0.0':$coordinates['1'];							   	
							if($driver_status == 'F')
							{														
								/***** Update Driver Current Location *********************/								
								if(count($coordinates)>0)
								{		
									if(($latitude != 0) &&($longitude != 0))
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
										$check_new_request = $api->check_new_request($driver_id,$company_all_currenttimestamp);
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
													$pickupplace  = $values->current_location;	
													$dropplace = $values->drop_location;	
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
												}	
												$passenger_name = $passenger_salutation.' '.ucfirst($p_name);
												$notification_time = $this->notification_time;
												if($notification_time != 0 ){ $timeoutseconds = $notification_time;}else{$timeoutseconds = 15;}														
												$trip_details = array("message" => __('api_request_confirmed_passenger'),"status" => "1","Passenger_logid" => $check_new_request,"booking_details" => array ( "pickupplace" => $pickupplace, "dropplace" => $dropplace, "pickup_time" => $pickup_time,"driver_id" => $driver_id,"passenger_id" => $passenger_id,"roundtrip" => "","passenger_phone" => $passenger_phone,"cityname" => "", "distance_away" => $time_to_reach_passen,"sub_logid" => $sub_logid,"drop_latitude" => $drop_latitude,"drop_longitude" => $drop_longitude, "taxi_id" => "","pickup_latitude" => $pickup_latitude, "pickup_longitude" => $pickup_longitude,"bookedby" => BOOK_BY_PASSENGER, "passenger_name" => $passenger_name,"profile_image" => "","drop" => $dropplace),"estimated_time" => $time_to_reach_passen ,"notification_time" => $timeoutseconds,"notes" =>"");	
												$msg = array("message" => __('driver_history_updated'),"trip_details"=>$trip_details,"status" => 5);	
												$update_trip_array  = array("status"=>'1');
												$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$check_new_request);													
												
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
									/********* Update driver device token every specified seconds **************/
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
								if($result == 1)
								{
									$msg = array("message" => __('driver_history_updated'),"status" => 1);	
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
									$msg = array("message" => __('driver_history_updated'),"status" => 1);	
								}
								else
								{
									$msg = array("message" => __('invalid_user'),"status"=>-1);	
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
					break;									
			//URL : http://192.168.1.88:1009/api/index/?type=passenger_account_details&email=prabhu.r@ndot.in&phone=8888888885&password=123456&deviceid=&devicetoken=&devicetype=
			case 'passenger_account_details':
				   $p_acc_detail_array = $_GET;
				  // print_r($p_acc_detail_array);
				   //exit;
				   $p_email = $p_acc_detail_array['email'];
				   $p_phone = $p_acc_detail_array['phone'];
				   $p_password = $p_acc_detail_array['password'];
				   $devicetoken = $p_acc_detail_array['devicetoken'];
				   $device_id = $p_acc_detail_array['deviceid'];
				   $devicetype = $p_acc_detail_array['devicetype'];					   
				   
				   $p_acc_validator = $this->account_validation($p_acc_detail_array);
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
							$acc_details_result=$api->add_p_account_details($p_acc_detail_array,$otp,$referral_code,$devicetoken,$device_id,$devicetype,$default_companyid);							
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
							$common_model = Model::factory('commonmodel');	
							$message_details = $common_model->sms_message_by_title('otp');
							$to = $p_phone;
							$message = $message_details[0]['sms_description'];
							$message = str_replace("##OTP##",$otp,$message);
							$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
							$api->send_sms($to,$message);
							//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
							} 
							$detail = array("email"=>$p_email);
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
				   $otp_array = $_GET;
				   $email = $otp_array['email'];	
				   $user_type = $otp_array['user_type'];
				   if(isset($email))
				   {				
							$otp = text::random($type = 'alnum', $length = 5);
							$otp_result=$api->update_otp($otp_array,$otp,$default_companyid);							
							if($otp_result == 1) 
							{ 
								$mail="";						
								$replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>'',REPLACE_OTP=>$otp,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE);
								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'otp.html',$replace_variables);

							$to = $email;
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
							$common_model = Model::factory('commonmodel');	
							
							if($otp_array['user_type']=='P')
							{
								$this->phone=$this->commonmodel->select_site_settings('phone',PASSENGERS);
							}
							else
							{
								$this->phone=$this->commonmodel->select_site_settings('phone',PEOPLE);
							}
							$message_details = $common_model->sms_message_by_title('otp');
							$to = $this->phone;
							$message = $message_details[0]['sms_description'];
							$message = str_replace("##OTP##",$otp,$message);
							$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
							$api->send_sms($to,$message);
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
					$p_personal_array= $_POST;										
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
									Commonfunction::imageresize($d_image,PASS_IMG_WIDTH, PASS_IMG_HEIGHT,$path11,$image_name,90);								
									
									$path12=$thumb_image_name;
									Commonfunction::imageresize($d_image,PASS_THUMBIMG_WIDTH, PASS_THUMBIMG_HEIGHT,$path11,$thumb_image_name,90);
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
											$detail = array("passenger_id"=>$id);
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
			$p_card_array= $_GET;			
			$savecard = $p_card_array['savecard'];
			$email = $p_card_array['email'];
			if($savecard == 1)
			{
				$card_validation = $this->passenger_card_validation($p_card_array);			
				if($card_validation->check())
				{
					$creditcard_no = $p_card_array['creditcard_no'];
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
					$result = $api->save_passenger_carddata($p_card_array,$default_companyid);	
					//echo $result;	
					if($result == 0)
					{
					$passenger_details = $api->passenger_detailsbyemail($email,$default_companyid);


									$total_array = array();
									if(count($result) > 0)
									{
										if((!empty($passenger_details[0]['profile_image'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image'])){ 
										$profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image']; 
										}
										else{ 
										$profile_image = URL_BASE."public/images/no_image.png";
										} 										
										$total_array['id'] = $passenger_details[0]['id'];
										$total_array['salutation'] = $passenger_details[0]['salutation'];
										$total_array['name'] = $passenger_details[0]['name'];
										$total_array['lastname'] = $passenger_details[0]['lastname'];
										$total_array['email'] = $passenger_details[0]['email'];
										$total_array['profile_image'] = $profile_image;
										$total_array['phone'] = $passenger_details[0]['phone'];
										$total_array['address'] = $passenger_details[0]['address'];
										$referral_code = $passenger_details[0]['referral_code'];
										$ref_message = TELL_TO_FRIEND_MESSAGE.''.$referral_code;
										//$ref_discount = REFERRAL_DISCOUNT;
										$telltofriend_message = TELL_TO_FRIEND_MESSAGE;//str_replace("#REFDIS#",$ref_discount,$ref_message); 
										$total_array['telltofriend_message'] = $telltofriend_message;	
										}				
										
									//free sms url with the arguments
										if(SMS == 1)
										{
											$common_model = Model::factory('commonmodel');	
											$message_details = $common_model->sms_message_by_title('account_create_sms');
											$to = isset($total_array['phone'])?$total_array['phone']:'';
											$p_password = isset($passenger_details[0]['org_password'])?$passenger_details[0]['org_password']:'';
											$message = $message_details[0]['sms_description'];
											$message = str_replace("##USERNAME##",$email,$message);
											$message = str_replace("##PASSWORD##",$p_password,$message);
											$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
											$api->send_sms($to,$message);
											//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
											
										}

					$message = array("message" => __('signup_success'),"detail"=>$total_array,"status"=>1);		
					}
					elseif($result == 2)
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
					$passenger_details = $api->passenger_detailsbyemail($email,$default_companyid);
					$message = array("message" => __('signup_success'),"detail"=>$passenger_details,"status"=>1);						
			}
			echo json_encode($message);
			break;
			
			//URL : http://192.168.1.104:1003/api/index/dGF4aV9hbGw=/?type=passenger_fb_connect&accesstoken=sdfdssdsfdsfasdfassfasfsdfsdf&userid=100000222346359&fname=senthil&lname=kumar&fbemail=janani.senthilcse@gmail.com&devicetoken=e10adc3949ba59abbe56e057f20f883e&deviceid=SDfsdf454&devicetype=1
			case 'passenger_fb_connect':
					$array = $_GET;
					$accessToken = $array['accesstoken'];
					$uid = $array['userid'];
					$fname = $array['fname'];
					$lname = $array['lname'];
					$email = $array['fbemail'];
					$devicetoken = $array['devicetoken'];
					$device_id = $array['deviceid'];
					$devicetype = $array['devicetype'];
										
					if($array['devicetoken'] != null)
					{
								/*$profile_data_url = "https://graph.facebook.com/me?access_token=".$accessToken;
								$Profile_data = json_decode($this->curl_function($profile_data_url));
								if(isset($Profile_data->error))
								{
									$message = array("message" => 'Problem on Facebook Connect.Please Try Again',"status"=>-1);
								}
								else{*/
								/** Thumb Image ****/
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

								/*************************/	
								//print_r($Profile_data); exit;
								$otp = text::random($type = 'alnum', $length = 5);
								$referral_code = text::random($type = 'alnum', $length = 6);
								$status = $api->register_facebook_user($accessToken,$uid,$otp,$referral_code,$fname,$lname,$email,$image_name,$devicetoken,$device_id,$devicetype,$default_companyid);
								$passenger_details = $api->passenger_detailsbyemail($email,$default_companyid);									

								if((!empty($passenger_details[0]['profile_image'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'edit_'.$passenger_details[0]['profile_image'])){ 
									$edit_image = URL_BASE.PASS_IMG_IMGPATH.'edit_'.$passenger_details[0]['profile_image']; 
								 }
								else{ 
									$edit_image = URL_BASE."publis/uploads/edit_image.png";
								 } 
								$passenger_details[0]['edit_image'] = $edit_image;


								if((!empty($passenger_details[0]['profile_image'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image'])){ 
									$profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_details[0]['profile_image']; 
								 }
								else{ 
									$profile_image = URL_BASE."public/".UPLOADS."/no_image.png";
								 } 

								$passenger_details[0]['profile_image'] = $profile_image;


									//print_r($miles_details);
								//echo 'as'.$status;exit;								
									$total_array = array();
									$result = $passenger_details;
									if(count($result) > 0)
									{
										$total_array['id'] = $result[0]['id'];
										$total_array['name'] = $result[0]['name'];
										$total_array['email'] = $result[0]['email'];
										$total_array['profile_image'] = $profile_image;
										$total_array['phone'] = $result[0]['phone'];
										$total_array['address'] = $result[0]['address'];
										$total_array['fb_user_id'] = $result[0]['fb_user_id'];
										$total_array['fb_access_token'] = $result[0]['fb_access_token'];
										$total_array['user_status'] = $result[0]['user_status'];
										$total_array['login_from'] = $result[0]['login_from'];
										$total_array['edit_image'] = $edit_image;
										$referral_code = $result[0]['referral_code'];
										$ref_message = TELL_TO_FRIEND_MESSAGE.''.$referral_code;
										//$ref_discount = REFERRAL_DISCOUNT;
										$telltofriend_message = TELL_TO_FRIEND_MESSAGE;//str_replace("#REFDIS#",$ref_discount,$ref_message); 
										$total_array['telltofriend_message'] = $telltofriend_message;
										
									}								
							    if($status==1)
								{
									//echo $passenger_details[0]['id'];	
									$message = array("message" => __('succesful_login_flash'),"detail"=>$total_array,"status"=> 1); //url::redirect(PATH);																
								}
								else if($status==2)
								{															
									$message = array("message"=>__('account_saved_withoutmobile'),"detail"=>$total_array,"status"=>2);					 
								}
								else if($status==3)
								{									
									$message = array("message"=>__('p_personal_data_not_filled'),"detail"=>$total_array,"status"=>3);													 
								}
								else if($status==4)
								{									
									$message = array("message"=>__('p_card_data_not_filled'),"detail"=>$total_array,"status"=>4);													 
								}
								else if($status==-2)
								{
									$message = array("message"=>__('email_exists'),"status"=>-2);													 
								}
								else
								{
									$message = array("message" => __('facebook_error'),"status"=>-1);
								}
							//}
					}
					else
					{
						$message = array("message" => __('facebook_error'),"status"=>-1);
					}
					echo json_encode($message);
					break;
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=passenger_mobile_otp&fbemail=janani.senthilcse@gmail.com&mobile=9789648588&fname=senthil&lname=kumar&otp=HH6tC
			case 'passenger_mobile_otp':

					$array = $_GET;
					$email = $array['fbemail'];
					$mobile = $array['mobile'];

					$phone_exist = $api->check_phone_bypassengers($mobile,$email,$default_companyid);
					
					if($phone_exist != 0)
					{
						$message = array("message" => __('phone_exists'),"status"=>4);
					}
					else 
					{
						if($email != null && $mobile != null)
						{
							$status = $api->update_passenger_mobile($email,$mobile,'','','','',$default_companyid);

							if($status == 1)
							{
								$passenger_details = $api->passenger_detailsbyemail($email,$default_companyid);
								$otp = $passenger_details[0]['otp'];
								$id = $passenger_details[0]['id'];
								/*$mail="";						
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
									}*/
									if(SMS == 1)
									{
										$common_model = Model::factory('commonmodel');	
										
		
										$message_details = $common_model->sms_message_by_title('otp');
										$to = $passenger_details[0]['phone'];
										$message = $message_details[0]['sms_description'];
										$message = str_replace("##OTP##",$otp,$message);
										$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
										
										$api->send_sms($to,$message);
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
									$message = array("message" => __('account_saved'),"detail"=>$total_array,"status"=>1);
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
				$p_login_array = $_POST;
			    $Commonmodel = Model::factory('Commonmodel');                        
				$validator = $this->passenger_login_validation($p_login_array);
					//print_r($validator);exit;
					if($validator->check())
					{
					   $phone_exist = $api->check_phone_passengers($p_login_array['phone'],$default_companyid);
					   if($phone_exist == 0)
						{
							$message = array("message" => __('phone_not_exists'),"status"=> 2);
							echo json_encode($message);
							break;
						}
						else
						{
						$result = $api->passenger_login(urldecode($p_login_array['phone']),md5($p_login_array['password']),$p_login_array['devicetoken'],$p_login_array['deviceid'],$p_login_array['devicetype'],$default_companyid);
						//print_r($result);
						if(count($result) > 0)
						{	
							//Checking the User Status						
							$user_status = $result[0]['user_status'];
							$passenger_email = $result[0]['email'];
							$passenger_id = $result[0]['id'];
							if($user_status == 'D' || $user_status == 'T' )
							{
								$message = array("message" => __('user_blocked'),"status"=> 3);								
							}
							else if($user_status == 'I')
							{
								$detail = array("email"=>$passenger_email,"passenger_id"=>$passenger_id);
								$message = array("message" => __('p_personal_data_not_filled'),"detail"=>$detail,"status"=> -2);								
							}
							else
							{
								$update_id = $result[0]['id'];
								
								$check_personal_date = $api->check_passenger_personal_data($update_id);
								$check_card_data = $api->check_passenger_card_data($update_id);
								if($check_personal_date == 1)
								{
									$detail = array("email"=>$passenger_email,"passenger_id"=>$passenger_id);
									$message = array("message" => __('p_personal_data_not_filled'),"status"=> -2,"detail"=>$detail);
								}
								else if($check_card_data == 0)
								{
									$detail = array("email"=>$passenger_email,"passenger_id"=>$passenger_id);
									$message = array("message" => __('p_card_data_not_filled'),"status"=> -3,"detail"=>$detail);
								}
								else
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
									$profile_image = URL_BASE."public/images/no_image.png";
									} 

									$total_array = array();
									if(count($result) > 0)
									{
										$total_array['id'] = $result[0]['id'];
										$total_array['name'] = $result[0]['name'];
										$total_array['email'] = $result[0]['email'];
										$total_array['profile_image'] = $profile_image;
										$total_array['phone'] = $result[0]['phone'];
										$total_array['address'] = $result[0]['address'];
										$total_array['fb_user_id'] = $result[0]['fb_user_id'];
										$total_array['fb_access_token'] = $result[0]['fb_access_token'];
										$total_array['user_status'] = $result[0]['user_status'];
										$total_array['login_from'] = $result[0]['login_from'];
										$total_array['edit_image'] = $edit_image;
										$referral_code = $result[0]['referral_code'];
										$ref_message = TELL_TO_FRIEND_MESSAGE.''.$referral_code;
										//$ref_discount = REFERRAL_DISCOUNT;
										$telltofriend_message = TELL_TO_FRIEND_MESSAGE;//str_replace("#REFDIS#",$ref_discount,$ref_message); 
										$total_array['telltofriend_message'] = $telltofriend_message;
										$message = array("message" => __('succesful_login_flash'),"detail"=>$total_array,"status"=> 1);
									}
								}												
                            }												
						}							
						else
						{
							$message = array("message" => __('password_failed'),"status"=> 4);							
						}						
						echo json_encode($message);	
						}					
					}
					else
					{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"detail"=>$validation_error,"status"=>-5);
						echo json_encode($message);
						exit;				
					}										
					break;					
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=passenger_profile&userid=2
			case 'passenger_profile':
			
					if($array['userid'] != null)
					{
						$result = $api->passenger_profile($array['userid']);
						if(count($result) >0)
						{
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
			/*$p_personal_array=array(
					"passenger_id"=>17,
					"salutation"=>"Mr",
					"email"=>"prabhu.rs@ndot.in",
					"phone"=>"34234234323",
					"firstname"=>"Sidhes",
					"lastname"=>"kumar",
					"password"=>"789456",
					"profile_image"=>"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",					
					);	*/		
					$p_personal_array = $_POST;
					//print_r($p_personal_array);
					//echo 'profiletest';
					//exit;
					if(count($p_personal_array)>0)
					{
							if($p_personal_array['email'] != null)
							{
								$p_email = $p_personal_array['email'];
								$p_phone = $p_personal_array['phone'];
								$passenger_id = $p_personal_array['passenger_id'];
								$password = $p_personal_array['password'];
								$validator = $this->edit_passenger_profile_validation($p_personal_array);						
								if($validator->check())
								{															
								   $email_exist = $api->edit_check_email_passengers($p_email,$passenger_id,$default_companyid);
								   $phone_exist = $api->edit_check_phone_passengers($p_phone,$passenger_id,$default_companyid);
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
										$mime_type = explode('/',$mime_type);
										$mime_type = $mime_type[1];
										//print_r($mime_type);exit;
										$img = imagecreatefromstring($imgdata); 

										if($img != false)
										{                   
											// get prev image
											$result = $api->passenger_profile($p_personal_array['passenger_id']);
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
											Commonfunction::imageresize($d_image,PASS_IMG_WIDTH, PASS_IMG_HEIGHT,$path11,$image_name,90);								
											
											$path12=$thumb_image_name;
											Commonfunction::imageresize($d_image,PASS_THUMBIMG_WIDTH, PASS_THUMBIMG_HEIGHT,$path11,$thumb_image_name,90);
											if($password != "")
											{
												$update_array = array(								
												"salutation"=>$p_personal_array['salutation'],
												"name" => $p_personal_array['firstname'],
												"lastname" => $p_personal_array['lastname'],
												"email" => $p_email,
												"phone" => $p_phone,
												"password" => md5($password),
												"org_password" => $password,
												"profile_image" => $image_name);
											}
											else
											{
												$update_array = array(								
												"salutation"=>$p_personal_array['salutation'],
												"name" => $p_personal_array['firstname'],
												"lastname" => $p_personal_array['lastname'],
												"email" => $p_email,
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
												"salutation"=>$p_personal_array['salutation'],
												"name" => $p_personal_array['firstname'],
												"lastname" => $p_personal_array['lastname'],
												"email" => $p_email,
												"phone" => $p_phone,
												"password" => md5($password),
												"org_password" => $password);
											}
											else
											{
												$update_array = array(								
												"salutation"=>$p_personal_array['salutation'],
												"name" => $p_personal_array['firstname'],
												"lastname" => $p_personal_array['lastname'],
												"email" => $p_email,
												"phone" => $p_phone);										
											}
											$message = $api->edit_passenger_personaldata($update_array,$passenger_id,$default_companyid);
									}
										/*****************************************/												
										if($message == 0)
										{
											$message = array("message" => __('personal_updated'),"status"=>1);	
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
									$message = array("message" => $validation_error,"status"=>-3);		
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
			$p_card_array= $_GET;
			$creditcard_no = $p_card_array['creditcard_no'];
			$creditcard_cvv = $p_card_array['creditcard_cvv'];
			$expdatemonth = $p_card_array['expdatemonth'];
			$expdateyear = $p_card_array['expdateyear'];			
			$passenger_id = $p_card_array['passenger_id'];
			$default = $p_card_array['default'];
			$card_validation = $this->passenger_card_validation($p_card_array);
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
				$result = $api->add_passenger_carddata($p_card_array);	
				//echo $result;	
				if($result == 0)
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
			$p_card_array= $_GET;
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
					//print_r($authorize_status);
					//exit;
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
						$result = $api->edit_passenger_carddata($p_card_array);	
						//echo $result;	
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
			$p_chg_pass_array = $_POST;			
			if(!empty($p_chg_pass_array))
			{
					if($p_chg_pass_array['id'] != null)
					{
						$validator = $this->chg_password_passenger_validation($p_chg_pass_array);
						
						if($validator->check())
						{
							//array_shift($p_chg_pass_array);
							//array_shift($p_chg_pass_array);
							$message = $api->chg_password_passenger($p_chg_pass_array,PASSENGERS,$default_companyid,'P');	
							
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
			//http://192.168.1.104:1003/api/index/dGF4aV9hbGw=/?type=add_favourite&passenger_id=78&p_favourite_place=Vadavalli,coimbatore&p_fav_latitude=11.1425367&p_fav_longtitute=76.1253648&d_favourite_place=Gandhipuram,coimbatore&d_fav_latitude=11.1425367&d_fav_longtitute=76.1253648&fav_comments=test
			case 'add_favourite':
				$add_fav_array = $_GET;
				//print_r();
				$validator = $this->favourite_validation($add_fav_array);				
				if($validator->check())
				{
					$passenger_id= $add_fav_array['passenger_id'];					
					$fav_comments = $add_fav_array['fav_comments'];
					$p_favourite_place = $add_fav_array['p_favourite_place'];
					$p_fav_latitude = $add_fav_array['p_fav_latitude'];
					$p_fav_longtitute = $add_fav_array['p_fav_longtitute'];
					$d_favourite_place = $add_fav_array['d_favourite_place'];
					$d_fav_latitude = $add_fav_array['d_fav_latitude'];
					$d_fav_longtitute = $add_fav_array['d_fav_longtitute'];
					$check_fav_place = $api->check_fav_place($passenger_id,$p_favourite_place,$d_favourite_place);
					if(count($check_fav_place)==0)
					{
						//Set the Favourite Trips
						$status = $api->save_favourite($passenger_id,$p_favourite_place,$p_fav_latitude,$p_fav_longtitute,$d_favourite_place,$d_fav_latitude,$d_fav_longtitute,$fav_comments);
						if($status)					
						{
							$message = array("message" => __('mark_fav'),"status"=>1);
						}
						else
						{
							$p_favourite_id = $check_fav_place['0']['p_favourite_id'];
							$message = array("message" => __('try_again'),"status"=>0);	
						}	
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
				$p_fav_array = $_GET;
				if($p_fav_array['passenger_id'] != null)
				{
					$favourite_list = $api->get_favourite_list($p_fav_array['passenger_id']);
					if(count($favourite_list)>0)
					{
					$message = array("message" => __('success'),"detail"=>$favourite_list,"status"=>1);	
					}
					else
					{
					$message = array("message" => __('no_data'),"status"=>0);	
					}				
				}
				else
				{
					$message = array("message" => __('no_user'),"status"=>-1);								
				}
				echo json_encode($message);
				break;			
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=get_favourite_details&p_favourite_id=2
			case 'get_favourite_details':
				$p_fav_array = $_GET;
				if($p_fav_array['p_favourite_id'] != null)
				{
					$favourite_details = $api->get_favourite_details($p_fav_array['p_favourite_id']);
					$message = array("message" => $favourite_details,"status"=>1);	
				}
				else
				{
					$message = array("message" => __('no_user'),"status"=>-1);								
				}
				echo json_encode($message);
				break;			
			//http://192.168.1.104:1003/api/index/dGF4aV9hbGw=/?type=edit_favourite&p_favourite_id=1&passenger_id=78&p_favourite_place=Vadavalli,coimbatore-41&p_fav_latitude=11.1425367&p_fav_longtitute=76.1253648&d_favourite_place=Gandhipuram,coimbatore&d_fav_latitude=11.1425367&d_fav_longtitute=76.1253648&fav_comments=test=78&p_favourite_place=Vadavalli,Gandhipuram,west&p_fav_latitude=11.1425367&p_fav_longtitute=76.1253648&d_favourite_place=Gandhipuram,coimbatore&d_fav_latitude=11.1425367&d_fav_longtitute=76.1253648&fav_comments=
			case 'edit_favourite':
				$edit_fav_array = $_GET;
				$validator = $this->edit_favourite_validation($edit_fav_array);	
				if($validator->check())
				{
					$favourite_id= $edit_fav_array['p_favourite_id'];
					$fav_comments = $edit_fav_array['fav_comments'];
					$passenger_id  = $edit_fav_array['passenger_id'];
					$p_favourite_place = $edit_fav_array['p_favourite_place'];
					$p_fav_latitude = $edit_fav_array['p_fav_latitude'];
					$p_fav_longtitute = $edit_fav_array['p_fav_longtitute'];
					$d_favourite_place = $edit_fav_array['d_favourite_place'];
					$d_fav_latitude = $edit_fav_array['d_fav_latitude'];
					$d_fav_longtitute = $edit_fav_array['d_fav_longtitute'];				
					//Set the Favourite Trips
					$check_fav_place = $api->check_fav_place($passenger_id,$p_favourite_place,$d_favourite_place);
					if(count($check_fav_place)==0)
					{
						$status = $api->edit_favourite($favourite_id,$p_favourite_place,$p_fav_latitude,$p_fav_longtitute,$d_favourite_place,$d_fav_latitude,$d_fav_longtitute,$fav_comments);
						if($status)					
						{
							$message = array("message" => __('edit_mark_fav'),"status"=>1);
						}
						else
						{
							$message = array("message" => __('try_again'),"status"=>0);	
						}	
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
			//URL : http://192.168.1.88:1020/api/index/bnRheGlfYlVtUzZGMUJMVDY4VTZtWkdYaDNnRFV2WE5BRGo0=/?type=savebooking&latitude=11.0213687&longitude=76.916638&pickupplace=&dropplace=&drop_latitude=&drop_longitude=&pickup_time=03:00:00&motor_model=1&cityname=Coimbatore&sub_logid=&distance_away=&passenger_id=1&request_type=0			 
			//Append the Additional Fields while sending ...
			case 'savebooking':
					$search_array = $_GET;					
										
					$validator = $this->search_validation($search_array);
									
					if($validator->check())
					{
						if($search_array['latitude'] !='0' && $search_array['longitude'] !='0')
						{							
							$add_model = Model::factory('add');			
							$find_model = Model::factory('find');	
							$common_model = Model::factory('commonmodel');												
							$latitude = $search_array['latitude'];
							$longitude = $search_array['longitude'];
							$miles = "";//$search_array['no_of_miles'];
							$no_passengers = "";//$search_array['no_of_passengers'];
							$pickup_time = $search_array['pickup_time'];	
							
							$pickupplace = $search_array['pickupplace'];
							$dropplace = $search_array['dropplace'];
							$drop_latitude = $search_array['drop_latitude'];
							$drop_longitude = $search_array['drop_longitude'];
							
							//$taxi_fare_km = $search_array['taxi_fare_km'];
							$taxi_fare_km = '';
							$motor_company = '1';//$search_array['motor_company'];
							$motor_model = $search_array['motor_model'];		
							$maximum_luggage = "";//$search_array['maximum_luggage'];	
							$cityname = $search_array['cityname'];		
							$sub_logid = $search_array['sub_logid'];	
							$passenger_id = $search_array['passenger_id'];	
							$unit = '0'; // 0 - KM, 1 - Miles	
							
							//print_r($_REQUEST);
							$city_id  = $api->get_city_id($cityname);
							$driver_details = $find_model->search_driver_mobileapp($latitude,$longitude,$miles,$passenger_id,$taxi_fare_km,$motor_company,$motor_model,$maximum_luggage,$cityname,$sub_logid,$default_companyid,$unit);		
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
							if($total_count > 0)
							{
										foreach($driver_details as $key => $value)
										{
												$taxi_image = URL_BASE."public/images/no_taximg.png";
											//Request Values
												$driver_details[$key]['taxi_image'] = $taxi_image;
												$driver_details[$key]['city_id'] = $city_id;
												$driver_details[$key]['pickupplace'] = $pickupplace;
												$driver_details[$key]['dropplace'] = $dropplace;
												$driver_details[$key]['drop_latitude'] = $drop_latitude;
												$driver_details[$key]['drop_longitude'] = $drop_longitude;	
												$driver_details[$key]['pickup_time'] = $pickup_time;	
												$driver_details[$key]['no_of_passengers'] = '';//$search_array['no_of_passengers'];
												$updatetime_difference = $value['updatetime_difference'];
												$driver_details[$key]['taxi_model'] = $value['model_name'];
												//Exclude the drivers who has not logged in and not update the status last specified seconds
												if($updatetime_difference <= LOCATIONUPDATESECONDS)
												{
													$driver_list .= $value['driver_id'].',';
													$available_drivers = substr_replace($driver_list ,"",-1);
												}
										       /*else
												{
													$shiftout_array  = array("shift_status" => 'OUT');
													//$transaction = $common_model->update(DRIVER,$shiftout_array,'driver_id',$value['driver_id']);
												}*/

												/*Nearest driver calculation */
												$prev_min_distance = explode('~',$prev_min_distance);
												$prev_key=$prev_min_distance[1];
												$prev_min_distance = $prev_min_distance[0];
												//checking with previous minimum 
												if($value['distance'] < $prev_min_distance)
												{	
													//new minimum distance
													$nearest_key=$key;
													$prev_min_distance = $value['distance'].'~'.$key;
												}
												else
												{
													//previous minimum
													$nearest_key=$prev_key;
													$prev_min_distance = $prev_min_distance.'~'.$prev_key;
												}

												$totalrating = 0;
												$driver_details[$key]['driver_rating'] = $totalrating;
											//Driver images
											if((!empty($value['d_photo'])) && file_exists($_SERVER['DOCUMENT_ROOT'].'/public/'.UPLOADS.'/driver_image/'.$value['d_photo'])){ 
												$driver_image = URL_BASE.'public/'.UPLOADS.'/driver_image/'.$value['d_photo']; 
											 }
											else{ 
												$driver_image = URL_BASE."public/images/no_image.png";
											 } 
											 //Set nearest driver equal to 1
												$driver_details[$key]['d_photo'] = $driver_image;	
												
												if($a == $total_count)
												{
													$driver_details[$nearest_key]['nearest_driver'] ='1';
													$taxi_id=$value['taxi_id'];
													$nearest_driver=$driver_details[$nearest_key]['driver_id'];
													$taxi_id=$driver_details[$nearest_key]['taxi_id'];
													if($nearest_key != $key)
													{
													$driver_details[$key]['nearest_driver'] ='0';
													}
												}
												else
												{
													$driver_details[$key]['nearest_driver'] ='0';
												}

												$a++;
										}
									/*********************************************Save booking ***************************************/
									$formvalues = Arr::extract($_GET, array('pickupplace','dropplace','pickup_time','driver_id','passenger_id','roundtrip','passenger_phone','cityname','distance_away','sub_logid','drop_latitude','drop_longitude'));	
									$formvalues['taxi_id']=$taxi_id;
									$formvalues['pickup_latitude']=$search_array['latitude'];
									$formvalues['pickup_longitude']=$search_array['longitude'];
									$formvalues['driver_id'] =$nearest_driver;
									$commonmodel=Model::factory('commonmodel');				
									$result=$api->savebooking($formvalues,$default_companyid);			
									if($result > 0 )
									{
									//$driver_details['city_id'] = $city_id;
											/***** Insert the druiver details to driver request table ************/
											$company_all_currenttimestamp = $common_model->getcompany_all_currenttimestamp($default_companyid);
											$insert_array = array(
																	"trip_id" => $result,
																	"available_drivers" 			=> $available_drivers,								"status" 	=> '0',
																	"rejected_timeout_drivers"		=> "",
																	"createdate"		=> $company_all_currenttimestamp,
																);								
											//Inserting to Transaction Table 
											$transaction = $common_model->insert(DRIVER_REQUEST_DETAILS,$insert_array);	
											$detail = array("passenger_tripid"=>$result,"notification_time"=>$notification_time);
											$msg = array("message" => __('api_request_confirmed_passenger'),"status" => 1,"detail"=>$detail);
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
							  $msg = array("message" => $no_vehicle_msg,"status" => 3);
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
			//http://192.168.1.88:1020/api/index/bnRheGlfYlVtUzZGMUJMVDY4VTZtWkdYaDNnRFV2WE5BRGo0=/?type=nearestdriver_list&latitude=10.978942571323032&longitude=76.761474609375&motor_model=&passenger_id=
			case 'nearestdriver_list':
					$search_array = $_GET;					
										
					$validator = $this->nearestdriver_validation($search_array);
									
					if($validator->check())
					{
						if($search_array['latitude'] !='0' && $search_array['longitude'] !='0')
						{
							$passenger_id = $search_array['passenger_id'];
							$add_model = Model::factory('add');			
							$find_model = Model::factory('find');	
							$commonmodel=Model::factory('commonmodel');	
							$company_all_currenttimestamp = $commonmodel->getcompany_all_currenttimestamp($default_companyid);							$latitude = $search_array['latitude'];
							$longitude = $search_array['longitude'];
							$miles = "15";//$search_array['no_of_miles'];
							$unit = '0'; // 0 - KM, 1 - Miles
							$taxi_model = $search_array['motor_model'];
							$driver_details = $find_model->nearestdrivers($latitude,$longitude,$taxi_model,$passenger_id,$miles,$default_companyid,$unit);		
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
							if($total_count > 0)
							{
										foreach($driver_details as $key => $value)
										{										
											    /*Nearest driver calculation */
												$prev_min_distance = explode('~',$prev_min_distance);
												$prev_key=$prev_min_distance[1];
												$prev_min_distance = $prev_min_distance[0];
												$driver_distance = $value['distance_km'];
												//$location_update_date = $value['location_update_date'];
												//SELECT DATEDIFF($company_all_currenttimestamp,$location_update_date)
												$distance = round($driver_distance,4);
												//checking with previous minimum 
												if($distance < $prev_min_distance)
												{	
													//new minimum distance
													$nearest_key=$key;
													$prev_min_distance = $distance.'~'.$key;
												}
												else
												{
													//previous minimum
													$nearest_key=$prev_key;
													$prev_min_distance = $prev_min_distance.'~'.$prev_key;
												}
												
												$totalrating = 0;
												
											 //Set nearest driver equal to 1											
												if($a == $total_count)
												{
													$driver_details[$nearest_key]['nearest_driver'] ='1';
													$taxi_id=$value['taxi_id'];
													$nearest_driver=$driver_details[$nearest_key]['driver_id'];
													$taxi_id=$driver_details[$nearest_key]['taxi_id'];
													if($nearest_key != $key)
													{
													$driver_details[$key]['nearest_driver'] ='0';
													}
												}
												else
												{
													$driver_details[$key]['nearest_driver'] ='0';
												}
												$driver_details[$key]['distance_km'] =$distance;
												$a++;
										}
								//$driver_details['city_id'] = $city_id;								
									if(count($driver_details) > 0)
										$message = array("detail" => $driver_details,"status" => 1,"message" => 'success');
									else
										$message = array("message" => $no_vehicle_msg,"status" => 0);	
											
									echo json_encode($message);
									break;
						  }
						  else
						  {
							  $msg = array("message" => $no_vehicle_msg,"status" => 3);
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
			case 'driver_arrival_send':
			$array = $_GET;
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
				}

				/********** Update Driver Status after complete Payments *****************/
				$update_pass_array  = array("travel_status" => '3'); // Start to Pickup
				$result = $api->update_table(PASSENGERS_LOG,$update_pass_array,'passengers_log_id',$trip_id);	
				
				/** Send Trip fare details to Passenger ***/
				$p_device_token = $get_passenger_log_details[0]->passenger_device_token;
				$device_type = $get_passenger_log_details[0]->passenger_device_type;
				$passenger_id = $get_passenger_log_details[0]->passengers_id;
				$pushmessage = array("message"=>__('passenger_on_board'),"trip_id"=>$trip_id,"driver_latitute"=>$driver_latitute,"driver_longtitute"=>$driver_longtitute,"status"=>2);
				//print_r($pushmessage);
				//exit;
				if(SMS == 1)
				{
					$common_model = Model::factory('commonmodel');	
					//$this->phone=$this->commonmodel->get_passengers_details($email,1);
					$message_details = $common_model->sms_message_by_title('driver_arrived');
					$to = $api->get_passenger_phone_by_id($passenger_id);
					//$to = isset($this->phone[0]['phone'])?$this->phone[0]['phone']:'';
					$message = $message_details[0]['sms_description'];
					$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
					
					$api->send_sms($to,$message);

				}
				$p_send_notification = $api->send_passenger_mobile_pushnotification($p_device_token,$device_type,$pushmessage,$this->customer_google_api);
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
					if($array['driver_id'] != null)
					{
						$Commonmodel = Model::factory('Commonmodel');	
						$driver_model = Model::factory('driver');
						$update_id = $array['driver_id'];	

						$check_result = $api->check_driver_companydetails($array['driver_id'],$default_companyid);
						if($check_result == 0)	
						{
							$message = array("message" => __('invalid_user'),"status"=>-1);
							echo json_encode($message);
							exit;;
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
									$update_array  = array("login_from"=>"","login_status"=>"N","device_id" => "","device_token" => "","device_type" => "","notification_setting"=>"0");
									$login_status_update = $Commonmodel->update(PEOPLE,$update_array,'id',$update_id);
									/*** Update in Driver table **/
									$driver_reply = $driver_model->update_driver_shift_status($update_id,'0');
									/** Update in driver shift history table **/
									$shiftupdate_arrary  = array("shift_end" => $this->currentdate);
									$shiftupdateid = $array['shiftupdate_id'];		
									if($shiftupdateid)
									{
										$transaction = $Commonmodel->update(DRIVERSHIFTSERVICE,$shiftupdate_arrary,'driver_shift_id',$shiftupdateid);
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
							$message = array("message" => __('driver_in_trip'),"status"=>0);
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
					$array = $_GET;
					$trip_id = $array['trip_id'];
					if($trip_id != null)
					{
						$trip_id = $trip_id;
						
						$api_model = Model::factory('api');			
						$get_passenger_log_details = $api_model->get_trip_detail($trip_id);		
						if(count($get_passenger_log_details)>0)
						{
							foreach($get_passenger_log_details as $journey)
							{
									$driver_id = $journey->driver_id;
									$taxi_id = $journey->taxi_id;
									$driver_image_name = $journey->driver_image;
									$passenger_image = $journey->passenger_image;
									$trip_details['trip_id'] = $journey->passengers_log_id;
									$trip_details['current_location'] = $journey->pickup_location;
									$trip_details['pickup_latitude'] = $journey->pickup_latitude;
									$trip_details['pickup_longitude'] = $journey->pickup_longitude;
									$trip_details['drop_location'] = $journey->drop_location;
									$trip_details['drop_latitude'] = $journey->drop_latitude;
									$trip_details['drop_longitude'] = $journey->drop_longitude;
									$trip_details['pickup_time'] = $journey->pickup_time;
									$trip_details['time_to_reach_passen'] = $journey->time_to_reach_passen;	
									$trip_details['no_passengers']= $journey->no_passengers;	
									$trip_details['rating'] = $journey->rating;
									$trip_details['notes']= $journey->notes_driver;																
									$trip_details['driver_name'] = $journey->driver_name;								
									$trip_details['driver_id'] = $journey->driver_id;							
									$trip_details['taxi_id'] = $journey->taxi_id;
									$trip_details['taxi_number'] = $journey->taxi_no;
									$trip_details['driver_phone'] = $journey->driver_phone;
									$trip_details['passenger_phone'] = $journey->passenger_phone;
									$trip_details['passenger_name'] = $journey->passenger_name;									
									$trip_details['travel_status'] = $journey->travel_status;									
									$trip_details['amt'] = $journey->amt;		
									$trip_details['job_ref'] = $journey->job_ref;		
									$trip_details['payment_type'] = $journey->payment_type;	
																		
							}
							$paymentname="";
							$paymentname_sql = $api->get_payment_name($trip_details['payment_type']);
							if(count($paymentname_sql) > 0)
							{
								$paymentname = $paymentname_sql[0]['pay_mod_name'];
							}
							$trip_details['payment_type'] = $paymentname;
							/************************************Driver Image *******************************/					
								$driver_image = $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DRIVER_IMGPATH.$driver_image_name;
								if(file_exists($driver_image) && ($driver_image_name !=''))
								{
								$driver_image = URL_BASE.SITE_DRIVER_IMGPATH.$driver_image_name;
								}else{
								$driver_image = URL_BASE."/public/images/noimages.jpg";
								}		
								$trip_details['driver_image'] = $driver_image;
								
							/*************************** Passenger Image ************************************/
							if((!empty($passenger_image)) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_image))
							{ 
								 $profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_image; 
							}
							else
							{ 
								$profile_image = URL_BASE."public/images/no_image.png";
							}	
							$trip_details['passenger_image'] = $profile_image;
							$trip_details['driver_latitute'] = $trip_details['driver_longtitute'] = '0.0';
							$current_driver_status = $api_model->get_driver_current_status($driver_id);
							if(count($current_driver_status)>0)
							{
								foreach($current_driver_status as $driver_details)
								{
									$trip_details['driver_latitute'] = $driver_details->latitude;
									$trip_details['driver_longtitute'] = $driver_details->longitude;
									$trip_details['driver_status'] = $driver_details->status;
								}
							}
							
							$dresult = $api->driver_ratings($driver_id);
							$totalrating=0;
							if(count($dresult) > 0)
							{
								$overall_rating = 0; $i=0; $trip_total_with_rate=0;
								
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
										$totalrating = 0;
									}		
																	
									$totalrating = round($totalrating);	
										 //echo 'as'.$totalrating;												
								}
								else
								{
									$totalrating = 0;
								}							
							$trip_details['driver_rating'] = $totalrating;
							//print_r($upcoming_journey);
							if(count($get_passenger_log_details) == 0)
							{
								$message = array("message" => __('try_again'),"status"=>0,"site_currency"=>$this->site_currency);	
							}
							else
							{
								$message = array("message" => __('success'),"detail"=>$trip_details,"status" => 1,"site_currency"=>$this->site_currency);										
								//$message = Arr::merge($msg,$upcoming_journey);
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
					if($array['id'] != null)
					{
						$api_model = Model::factory('api');	
						$Commonmodel = Model::factory('Commonmodel');
						$company_all_currenttimestamp = $Commonmodel->getcompany_all_currenttimestamp($default_companyid);
						
						$update_id = $array['id'];

						$check_result = $api->check_passenger_companydetails($array['id'],$default_companyid);
						if($check_result == 0)	
						{
							$message = array("message" => __('invalid_user'),"status"=>-1);
							echo json_encode($message);
							exit;
						}

						$update_array  = array("login_from"=>"","login_status"=>"N","device_id" => "","device_token" => "","device_type" => "");
						$logout_status_update = $api_model->update_passengers($update_array,$update_id,$default_companyid);
						$delete_rejected_trips = $api_model->delete_rejected_trips($update_id,$company_all_currenttimestamp);
						if($logout_status_update > 0)
						{
							$message = array("message" => __('logout_success'),"status"=>1);
						}
						else
						{
							$message = array("message" => __('invalid_user'),"status"=>-1);
						}						
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>0);	
					}
				echo json_encode($message);	
				break;		
				
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=tell_to_friend_message&id=58&usertype=P
			case 'tell_to_friend_message':			
			$array = $_GET;

			$id = $array['id'];
			$type = $array['usertype'];
			$device_type = $array['device_type'];
			//$referral_code = $telltofriend_message = "";
				$subject = __('telltofrien_subject');
				$name="";
				$message = "";
				if($device_type == 1)
				{
				$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_NAME=>$name,REPLACE_MESSAGE=>TELL_TO_FRIEND_MESSAGE,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE);
				}
				else
				{
				$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_NAME=>$name,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE);					
				}
				
				$message_temp=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'telltofriend.html',$replace_variables);
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
					$passenger_profile = $api->passenger_profile($id);
					if(count($passenger_profile)>0)
					{
						$referral_code = $passenger_profile[0]['referral_code'];
						$name = $passenger_profile[0]['name'];
						$ref_message = TELL_TO_FRIEND_MESSAGE.''.$referral_code;
						//$ref_discount = REFERRAL_DISCOUNT;
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
			$tell_array = $_POST;
			if(!empty($tell_array))
			{
				$to = $tell_array['to'];
				$message = $tell_array['message'];
				$passenger_id = $tell_array['passenger_id'];
				$name = $email = $referral_code = "";
				$check_validation = $this->check_tell_to_friend($tell_array);
				if($check_validation->check()) 
				{
					$passenger_details = $api->passenger_profile($passenger_id);
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
									$subject = __('telltofrien_subject');
									$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_NAME=>$name,REPLACE_EMAIL=>$email,REPLACE_SUBJECT=>$subject,REPLACE_MESSAGE=>$message,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE);
									//print_r($replace_variables);exit;
									$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'telltofriend.html',$replace_variables);
									//print_r(htmlspecialchars($message));exit;
									$friend_to = $femail;
									$from = $this->siteemail;
									$successemails .= $femail.',';	
									$redirect = "/";	
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
						$cms = Model::factory('cms');
						$content_cms = $cms->getcmscontent($pagename,$default_companyid);
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
				
			//URL
			// http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=send_feedback&to=senthilcse2008@gmail.com&cc_bcc=sivakumar.mr@ndot.in&subject=&message=Testing cc and bcc email address for flagger&passenger_id=1
			case 'send_feedback':			

			$feed_array = $_POST;
			if(!empty($feed_array))
			{
				$to = $feed_array['to'];
				$cc_bcc = $feed_array['cc_bcc'];
				$subject = $feed_array['subject'];
				$message = $feed_array['message'];			
				$passenger_id = $feed_array['passenger_id'];			
				$validator = $this->feedback_validation($feed_array);
				if($validator->check())
				{			
						$name = $email = $referral_code = "";
						$passenger_details = $api->passenger_profile($passenger_id);
						$referral_code = "";
						if(count($passenger_details)>0)
						{
							$name = $passenger_details[0]['name'];
							$email = $passenger_details[0]['email'];
						}
						$rejectedemails="";
						$successemails = "";
						$mail="";
						$subject = $subject;
						$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_NAME=>$name,REPLACE_SUBJECT=>$subject,REPLACE_MESSAGE=>$message,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE);
						$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'feedback.html',$replace_variables);
						$to = $to;
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
							$headers .= 'Bcc: '.$cc_bcc.'' . "\r\n";
							mail($to,$subject,$message,$headers);	
						}
							//$rejectedemails.' '.__('already_reg')
							$message = array("message" => __('feedback_received'),"status"=> 1);	
				}
				else
				{
						$detail = $validator->errors('errors');
						$message = array("detail"=>$detail,"status"=>-3,"message"=>__('validation_error'));	
				}	
			}
			else
			{
				$message = array("message" => __('invalid_request'),"status"=> 5);
			}				
            echo json_encode($message);								
			break; 				

			// Get FAQ List
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=get_faqs
			case 'get_faqs':
					$array = $_GET;
					//$passenger_id = $array['passenger_id'];
												
							$result = $api->get_faq_list();
							if(count($result)>0)
							{
								$detail = array("list"=>$result);
								$message = array("detail" =>$detail,"message"=>__('success'),"status"=>1);
							}
							else
							{
								$message = array("message" =>__('no_faq'),"status"=>2);
							}
			
			echo json_encode($message);
			break;
			// Get FAQ Details
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=get_faqs_detail&faq_id=1
			case 'get_faqs_detail':
					$array = $_GET;
					$faq_id = $array['faq_id'];
					if($array['faq_id'] != null)
					{												
							$result = $api->get_faq_details($faq_id);
							if(count($result)>0)
							{
								$message = array("message" =>$result,"status"=>1);
							}
							else
							{
								$message = array("message" =>__('no_faq'),"status"=>2);
							}
					}	
					else
					{
						$message = array("message" => __('invalid_faq'),"status"=>-1);	
					}			
			echo json_encode($message);
			break;			

			//URL : http://192.168.1.88:1000/api/index/?type=completed_journey_datewise&passenger_id=1&start=0&limit=5&date=2012-12-26&device_type=1			
			case 'completed_journey_datewise':
				$array = $_GET;				
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
									$arraydetails['pickup_time'] = $result['pickup_time'];
									$arraydetails['fare'] = $result['fare'];
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
							$message = array("message" => __('no_completed_data'),"status"=>0,"site_currency"=>$this->site_currency);	
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
				$array = $_GET;				
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
						//$fromdate = $year.'-'.$month.'-'.'01';
						//$todate = date('Y-m-t', strtotime($fromdate));
						if($device_type == 1)
						{
							$fromdate = $year.'-'.$month.'-'.$start;
							$todate = $year.'-'.$month.'-'.$limit;
						}
						else
						{
							$fromdate = $year.'-'.$month.'-'.'01';
							$todate = date('Y-m-t', strtotime($fromdate));						
						}
						$arraydetails = array();
						$alldetails = array();
						$perdayarray = array();
						$pagination = 0;
						for($i = strtotime($fromdate); $i <= strtotime($todate); $i = strtotime('+1 Day', $i))
						{
							
							$cdate = date("Y-m-d",$i);
							$req_date = date("Y-m-d",$i);
							$passengers_all_compl = $api->get_passengertrips_byfrmdate($pagination,$booktype,$userid,1,'A','1',$start,$limit,$cdate);
							
							if(count($passengers_all_compl) > 0)
							{
								foreach($passengers_all_compl as $result)
								{
									$arraydetails['trip_id'] = $result['trip_id'];
									$arraydetails['place'] = $result['place'];
									$arraydetails['pickup_time'] = $result['pickup_time'];
									$arraydetails['fare'] = $result['fare'];
									$date =$result['pickup_time'];
									$alldetails[] = $arraydetails;
								}
								$perdayarray[] = array("trip_Date"=>$req_date,"trip_details" => $alldetails);

							}
						}
						
						if(count($perdayarray) > 0)
						{
							$message = array("message" =>__('success'),"detail"=>$perdayarray,"status"=>1,"site_currency"=> $this->site_currency);
						}
						else
						{
							$message = array("message" => __('no_completed_data'),"status"=>0,"site_currency"=>$this->site_currency);	
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
				$array = $_GET;
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
								$driver_image = URL_BASE."/public/images/noimages.jpg";
								}		
								$trip_details['driver_image'] = $driver_image;			
							$alldetails[] = $trip_details;
							$i = $i+1;			
							}
								if(SMS == 1)
							{
								$common_model = Model::factory('commonmodel');	
								//$this->phone=$this->commonmodel->get_drivers_details($email,1);
								
								$message_details = $common_model->sms_message_by_title('trip_cancel');
								//$to = isset($this->phone[0]['phone'])?$this->phone[0]['phone']:'';
								$to = $api->get_driver_phone_by_id($driver_id);
								$message = $message_details[0]['sms_description'];
								$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
								$api->send_sms($to,$message);
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
			//URL : http://192.168.1.88:1009/api/?type=transactions_driver_detail&passengers_log_id=10
			case 'transactions_driver_detail':
			
					if($array['trip_id'] != null)
					{
						$passengers_log_id = $array['trip_id'];
						
						$driver_model = Model::factory('driver');			
						$this->commonmodel=Model::factory('commonmodel');		
						$get_passenger_log_details = $api->get_transaction_passenger_log_tranaction_details($passengers_log_id);		
						
						if(count($get_passenger_log_details) > 0)
						{
							foreach($get_passenger_log_details as $journey)
							{
								$upcoming_journey['current_location'] = $journey->current_location;
								$upcoming_journey['drop_location'] = $journey->drop_location;
								$upcoming_journey['no_passengers'] = $journey->no_passengers;
								$upcoming_journey['pickup_time'] = $journey->pickup_time;
								$upcoming_journey['taxi_id'] = $journey->taxi_id;																
								$upcoming_journey['rating'] = $journey->rating;
								$upcoming_journey['driver_name'] = $journey->driver_name;
								$driver_image = $journey->driver_image;
								$upcoming_journey['driver_phone'] = $journey->driver_phone;
								$upcoming_journey['passenger_name'] = $journey->passenger_name;
								$upcoming_journey['amt'] = $journey->amt;		
								$upcoming_journey['job_ref'] = $journey->transaction_id;		
								$upcoming_journey['payment_type'] = $journey->payment_type;						   
							}							
							/************************************Driver Image *******************************/					
								$driver_image = URL_BASE.SITE_DRIVER_IMGPATH.$driver_image;
								if(file_exists($driver_image) && $driver_image !='')
								{
								$driver_image = URL_BASE.SITE_DRIVER_IMGPATH.$driver_image;
								}else{
								$driver_image = URL_BASE."/public/images/noimages.jpg";
								}		
								$upcoming_journey['driver_image'] = $driver_image;
								//print_r($upcoming_journey);
							$message = array("message" => $upcoming_journey,"status" => 1);														
						}
						else
						{
							$message = array("message" => __('no_data'),"status"=>0);										
							//$message = Arr::merge($msg,$upcoming_journey);
						}				
						
					}//http://192.168.1.104:1003/api/index/dGF4aV9hbGw=/?type=get_trip_detail&trip_id=1445
					else
					{
						$message = array("message" => __('invalid_trip'),"status"=>-1);	
					}
					echo json_encode($message);
					break;			
				
				//URL : http://192.168.1.88:100/api/index/dGF4aV9hbGw=?type=coming_trips&id=1&start=0&limit=10&device_type=1
				case 'coming_trips':
				//Current Journey after driver confirmation //TN1013619352
				
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
					
			/************** Driver upcoming and ongoing details **************************/
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=?type=driver_coming_trips&driver_id=1&start=0&limit=2&device_type=1
			case 'driver_coming_trips':
				//Current Journey after driver confirmation //TN1013619352
				
					if($array['driver_id'] != null)
					{
						$validator = $this->driver_coming_cancel($array);					
						if($validator->check()) 
						{						
							$driver_id= $array['driver_id'];
							$start = $array['start'];
							$limit = $array['limit'];	
							$device_type = $array['device_type'];	
							if($device_type == 1)
							$pagination = 1;
							else
							$pagination = 0;
							$ongoing_journey = array();
							/*************** Driver Ongoing Journey ***************************/
							$driver_logs_progress = $api->get_driver_current_ongoigtrips($driver_id,'R','A','2',$default_companyid);
							//echo print_r($driver_logs_progress);
							//exit;
							if(count($driver_logs_progress)>0)
							{
								foreach($driver_logs_progress as $key => $journey)
								{
									$passenger_photo = $journey['profile_image'];			
									if((!empty($passenger_photo)) && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.'thumb_'.$passenger_photo))
									{ 
										 $profile_image = URL_BASE.PASS_IMG_IMGPATH.'thumb_'.$passenger_photo; 
									}
									else
									{ 
										$profile_image = URL_BASE."public/images/no_image.png";
									} 
									$driver_logs_progress[$key]['profile_image']=$profile_image;
								}

								//$driver_details[$key]['taxi_image'] = $taxi_image;
								$ongoing_journey = 	$driver_logs_progress;
								$ongoing_status = 1;
							}		
							else
							{
								$ongoing_journey = __('no_ongoing_data');
								$ongoing_status = 0;
							}							
							/***********************Driver Upcoming******************************/					
							$driver_upcoming = $api->get_driver_current_log_details($default_companyid,$pagination,$driver_id,'','A','0',$start,$limit);
							
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
							$message = array("message" => __('success'),"detail"=>$detail,"ongoing_status"=>$ongoing_status,"upgoing_status"=>$upgoing_status);						
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
			
					if($array['pass_id'] != null)
					{
						$validator = $this->update_ratings_comments_validation($array);
						
						if($validator->check()) 
						{
							$pass_id= $array['pass_id'];
							$ratings = $array['ratings'];
							$comments = $array['comments'];						
							$api->savecomments($pass_id,$ratings,$comments);
							$message = array("message" => __('rate_comment_updated'),"status"=>1);
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
					
					//URL : http://192.168.1.88:1009/api/index/?type=get_credit_card_details&passenger_id=58&card_type=P&default=yes
				case 'get_credit_card_details':
					$array = $_GET;
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
									$carddetails['creditcard_cvv'] = $result[0]['creditcard_cvv'];
									$carddetails['masked_creditcard_cvv'] = repeatx($result[0]['creditcard_cvv'],'X','All');		
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
										$carddetails['creditcard_cvv'] = $value['creditcard_cvv'];
										$carddetails['masked_creditcard_cvv'] = repeatx($value['creditcard_cvv'],'X','All');		
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
			
			//URL : api/?type=update_driver_reply&pass_id=1&driver_reply=C
			
			case 'update_driver_reply':
			
					if($array['pass_id'] != null)
					{
						$pass_id= $array['pass_id'];
						$driver_reply= $array['driver_reply'];
						
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
			//http://192.168.1.88:1000/api/index/wiOBAfPKdmAr544Tl1ayDEsUQN9Og3/?type=driver_profile&userid=6
			//URL : api/?type=driver_profile&userid=1
			case 'driver_profile':
			
					if($array['userid'] != null)
					{
						$check_driver_login_status = $this->is_login_status($array['userid'],$default_companyid);
						if($check_driver_login_status == 1)
						{ 
								$result = $api->driver_profile($array['userid']);
								if(count($result) >0)
								{
									foreach($result as $key => $values) //
									{							
									  $name = $values['name'];
									  $salutation = $values['salutation'];
									  $email = $values['email'];
									  $phone = $values['phone'];
									  $profile_picture = $values['profile_picture'];
									  $address = $values['address'];
									  $driver_license_id = $values['driver_license_id'];
									  $lastname = $values['lastname'];
									  $bankname = $values['bankname'];
									  $bankaccount_no = $values['bankaccount_no'];
									}
									/************************************Driver Image *******************************/					
									$main_image_path = $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DRIVER_IMGPATH.$profile_picture;
									$thumb_image_path = $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DRIVER_IMGPATH.'thumb_'.$profile_picture;
									if(file_exists($main_image_path) && ($profile_picture !=''))
									{
										//echo 'as';
									$driver_main_image = URL_BASE.SITE_DRIVER_IMGPATH.$profile_picture;
									}else{
										//echo 'as';
									$driver_main_image = URL_BASE."/public/images/noimages.jpg";
									}		

									if(file_exists($thumb_image_path) && ($profile_picture !=''))
									{
									$driver_thumb_image = URL_BASE.SITE_DRIVER_IMGPATH.'thumb_'.$profile_picture;
									}else{
									$driver_thumb_image = URL_BASE."/public/images/noimages.jpg";
									}	
									
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
									"driver_license_id" => $driver_license_id
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
			case 'driver_login':
					$array = $_POST;
					if(!empty($array))
					{
						//print_r($array);exit;
						$validator = $this->driver_login_validation($array);
						$Commonmodel = Model::factory('Commonmodel');	
						if($validator->check())
						{
								$phone_exist = $api->check_phone_people($array['phone'],'D',$default_companyid);
								if($phone_exist == 0)
								{
									$message = array("message" =>  __('phone_not_exists'),"status"=> 2);
									echo json_encode($message);
									exit;									
								}		
								else
								{						
									$result = $api->driver_login($array['phone'],$array['password'],$default_companyid);								
									if(count($result) > 0)
									{	
										//Checking the User Status						
										$user_status = $result[0]['status'];
										$login_status = $result[0]['login_status'];
										$login_from = $result[0]['login_from'];
										$device_token = $result[0]['device_token'];
										$driver_id = $result[0]['id'];
										$driver_details = $api->driver_profile($driver_id);
										//print_r($driver_details);
										if($user_status == 'D')
										{
										$message = array("message" => __('account_deactivte'),"status"=> 0);								
										}
										else if($user_status == 'T')
										{
										$message = array("message" => __('account_deactivte'),"status"=> 0);	
										}
										else if(($login_status == 'S') && ($login_from == 'D') && ($device_token != $array['device_token']))
										{
										$message = array("message" => __('already_login'),"status"=> 0);								
										}					
										else if(($login_status == 'S') && ($login_from == 'D') && ($device_token == $array['device_token']))
										{
											$driver_reply = $api->update_driver_shift_status($driver_id,'IN');
											$taxi_id = "";
											$getTaxiforDriver = $api->getTaxiforDriver($driver_id);	

											if(count($getTaxiforDriver) > 0 )
											{
											$taxi_id = $getTaxiforDriver[0]['mapping_taxiid'];														
											$company_all_currenttimestamp = $Commonmodel->getcompany_all_currenttimestamp($default_companyid);
											$insert_array = array(
																	"driver_id" => $driver_id,
																	"taxi_id" 			=> $taxi_id,												
																	"shift_start" 	=> $company_all_currenttimestamp,
																	"shift_end"		=> "",
																	"reason"		=> "",
																	"createdate"		=> $this->currentdate,
																);								
											//Inserting to Transaction Table 
											$transaction = $Commonmodel->insert(DRIVERSHIFTSERVICE,$insert_array);	
											//print_r($transaction);		
											$shiftupdate_id = mysql_insert_id();
											$driver_details[0]["shiftupdate_id"]=$shiftupdate_id;
											$driver_details[0]["taxi_id"]=$taxi_id;											
											$details = array("driver_details"=>$driver_details);
											$message = array("message" => __('login_success'),"status"=> 1,"detail"=>$details);		
											}
											else
											{
												$message = array("message" => __('taxi_not_assigned'),"status"=>-3);
											}	
										}		
										/*else if(($login_status == 'S') && ($login_from == 'W'))
										{
										$message = array("message" => __('alteady_login_website'),"status"=> 0);
										}*/								
										else
										{
											$update_id = $result[0]['id'];

											$update_array  = array("notification_setting"=>"1","login_from"=>"D","login_status"=>"S","device_id" => $array['device_id'],"device_token" => $array['device_token'],"device_type" => $array['device_type']);							
											// Need for update labong settings automatically
											$login_status_update = $api->update_driver_phone($update_array,$update_id,$default_companyid);
											//print_r($login_status_update);
											//Enable Driver Shift status
											$driver_reply = $api->update_driver_shift_status($driver_id,'IN');
											$taxi_id = "";
											$getTaxiforDriver = $api->getTaxiforDriver($driver_id);	

													if(count($getTaxiforDriver) > 0 )
													{
													$taxi_id = $getTaxiforDriver[0]['mapping_taxiid'];														
													$company_all_currenttimestamp = $Commonmodel->getcompany_all_currenttimestamp($default_companyid);
													
													$insert_array = array(
																			"driver_id" => $driver_id,
																			"taxi_id" 			=> $taxi_id,												
																			"shift_start" 	=> $company_all_currenttimestamp,
																			"shift_end"		=> "",
																			"reason"		=> "",
																			"createdate"		=> $this->currentdate,
																		);								
													//Inserting to Transaction Table 
													$transaction = $Commonmodel->insert(DRIVERSHIFTSERVICE,$insert_array);	
													//print_r($transaction);		
													$shiftupdate_id = mysql_insert_id();
													$driver_details[0]["shiftupdate_id"]=$shiftupdate_id;
													$driver_details[0]["taxi_id"]=$taxi_id;								
													$details = array("driver_details"=>$driver_details);
													$message = array("message" => __('login_success'),"status"=> 1,"detail"=>$details);		
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
            //URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=labang_settings&laban_status=1&driver_id=						
			case 'labang_settings':						
					$array = $_GET;
					if($array['driver_id'] != null)
					{					
							$driver_id= $array['driver_id'];
							$laban_status = $array['laban_status'];
							$update_array  = array("notification_setting"=>$laban_status);							
							$login_status_update = $api->update_table(PEOPLE,$update_array,'id',$driver_id);
							$message = array("message" => __('labang_settings'),"status"=>1);	
					}
					else
					{
						$message = array("message" => __('invalid_user_driver'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
				//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=nearest_driver_list&driver_id=						
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=edit_driver_profile&driver_id=3&email=senthilkumar.a@ndot.in&phone=9999988888&salutation=Mr&firstname=senthil&lastname=kumar&password=e10adc3949ba59abbe56e057f20f883e&org_password=123456&profile_picture=&bankname=&bankaccount_no=
		
					case 'edit_driver_profile':					
					/*$d_personal_array=array(
					"driver_id"=>103,
					"email"=>"janani.senthilcse@gmail.com",
					"phone"=>"45698745586",
					"firstname"=>"Sidhes",
					"lastname"=>"kumar",
					"password"=>"",
					"bankname"=>"SBIs",
					"bankaccount_no"=>"10684589761",
					"profile_picture"=>"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",
					);*/				
					$d_personal_array = $_POST;
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
									$d_email = $d_personal_array['email'];
									$d_phone = $d_personal_array['phone'];
									$password = $d_personal_array['password'];					
									$bankname = $d_personal_array['bankname'];
									$bankaccount_no = $d_personal_array['bankaccount_no'];
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
												//print_r($mime_type);
												//exit;
												$mime_type = explode('/',$mime_type);
												$mime_type = $mime_type[1];
												
												$img = imagecreatefromstring($imgdata); 
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
													Commonfunction::imageresize($d_image,PASS_IMG_WIDTH, PASS_IMG_HEIGHT,$path11,$image_name,90);								
													
													$path12=$thumb_image_name;
													Commonfunction::imageresize($d_image,PASS_THUMBIMG_WIDTH, PASS_THUMBIMG_HEIGHT,$path11,$thumb_image_name,90);		
													if($password != "")
													{
													$update_array = array(	
													"id"=>$d_personal_array['driver_id'],						
													"salutation"=>$d_personal_array['salutation'],
													"name" => $d_personal_array['firstname'],
													"lastname" => $d_personal_array['lastname'],
													"email" => $d_email,
													"phone" => $d_phone,
													"password" => md5($password),
													"org_password" => $password,
													"profile_picture" => $image_name);
													}
													else
													{
													$update_array = array(	
													"id"=>$d_personal_array['driver_id'],						
													"salutation"=>$d_personal_array['salutation'],
													"name" => $d_personal_array['firstname'],
													"lastname" => $d_personal_array['lastname'],
													"email" => $d_email,
													"phone" => $d_phone,
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
													"id"=>$d_personal_array['driver_id'],						
													"salutation"=>$d_personal_array['salutation'],
													"name" => $d_personal_array['firstname'],
													"lastname" => $d_personal_array['lastname'],
													"email" => $d_email,
													"phone" => $d_phone,
													"password" => md5($password),
													"org_password" => $password);
													}
													else
													{
													$update_array = array(	
													"id"=>$d_personal_array['driver_id'],						
													"salutation"=>$d_personal_array['salutation'],
													"name" => $d_personal_array['firstname'],
													"lastname" => $d_personal_array['lastname'],
													"email" => $d_email,
													"phone" => $d_phone);
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
			
					if($array['id'] != null)
					{
						$validator = $this->chg_password_passenger_validation($array);
						
						if($validator->check())
						{
							//array_shift($array);
							//array_shift($array);
							$message = $api->chg_password_passenger($array,PEOPLE,$default_companyid,'D');	
							
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
						$array = $_GET;
						$trip_id = $array["passenger_tripid"];						
						$notification_time = $this->notification_time;			
						//exit;									
						if($notification_time != 0 ){ $timeoutseconds = $notification_time;}else{$timeoutseconds = 15;}
						$timeout = '60';//$timeoutseconds; // timeout in seconds
						$microseconds = 15*1000000; //Seconds to microseconds 1 second = 1000000 
						$flag = 0;
						$now = time();
						//echo 'as'.time()+20;
						//exit;
						$search_flag=0;
						
						if((int)$trip_id != "") 
						{												
							$i = 0;		
								while((time() - $now) < $timeout)
								{	
									$driver_status = $api->get_request_status($trip_id);	
									//print_r($driver_status);
									//exit;
									if(count($driver_status) >0)
									{								
										$driver_reply = $driver_status[0]['driver_reply'];
										$time_to_reach = $driver_status[0]['time_to_reach_passen'];		
										if(!empty($driver_reply))
										{
											if($driver_reply == 'A')
											{
												$message = array("message" => __("request_confirmed_passenger"),"trip_id"=>$trip_id,"status"=>1);
												echo json_encode($message);
												exit;
											}
											elseif($driver_reply == 'C')
											{
												exit;
											}
											else 
											{				
												$message = array("message" => __('try_again'),"status"=>0);
											}
											echo json_encode($message);
											exit;
										}
										usleep(50000);											
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
				$array = $_GET;
				if($array['passenger_tripid'] != null)
				{
					$passenger_tripid = $array["passenger_tripid"];
					$passengers = Model::factory('passengers');
					$get_passenger_log_det = $api->get_passenger_log_detail($passenger_tripid);
					if(count($get_passenger_log_det) > 0)															    		
					{
						//print_r($get_passenger_log_det);
						$driver_reply = $get_passenger_log_det[0]->driver_reply;
						if($driver_reply == 'A')
						{
							$detail = array("trip_id"=>$passenger_tripid,"driverdetails"=>"");
							$message = array("message" => __("request_confirmed_passenger"),"detail"=>$detail,"status"=>3);
						}
						else
						{
							$change_driver_status = $passengers->change_driver_status($passenger_tripid,'C');
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
								
			//For Driver Cancell the trip
		
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=driver_status_update&driver_id=4&latitude=11&longitude=76.7997&status=F&trip_id=574
			//Push Notifications in Driver Table
			case 'driver_status_update':			
					if($array['driver_id'] != null)
					{						
						$check_driver_login_status = $this->is_login_status($array['driver_id'],$default_companyid);

						$Commonmodel = Model::factory('Commonmodel');
						if($check_driver_login_status == 1)
						{ 
							$driver_model = Model::factory('driver');
							$current_driver_status = $driver_model->get_driver_current_status($array['driver_id']);
							if(count($current_driver_status) > 0)
							{
												//print_r($current_driver_status);
												array_shift($array);
												array_shift($array);							
												$trip_details = array();
												$passengers_log_id = $array['trip_id'];
												$update_driver_arrary  = array(
												"latitude" => $array['latitude'],
												"longitude" => $array['longitude'],
												"status" => strtoupper($array['status']));						
												if($current_driver_status[0]->status != 'A')
												{								
													if(($array['status'] == 'A') && ($passengers_log_id != null))
													{
													$get_passenger_log_details = $api->get_passenger_log_detail($passengers_log_id);
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
													}
													$trip_details = array(
													"pickup_location"=>$current_location,
													"drop_location"=>$drop_location);
														/** update journey inprogress in Passenger log table when driver start the journey**/
														$actual_pickup_time = $Commonmodel->getcompany_all_currenttimestamp($default_companyid);
														$travel_status = 2;
														$update_passenger_log_array = array('travel_status' => $travel_status,'actual_pickup_time'=>$actual_pickup_time);									
														$result = $api->update_table(PASSENGERS_LOG,$update_passenger_log_array,'passengers_log_id',$passengers_log_id);
														/** Passenger log table update end **/
														if(($array['latitude'] != 0) &&($array['longitude'] != 0))
														{
															$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$array['driver_id']);	
														}
														$message = array("message" => __('driver_location_update'),"status"=>1,"detail"=>$trip_details);		
														$push_message = array("message" =>__('journey_started'),"pickup_time"=>$actual_pickup_time,"trip_id"=>$passengers_log_id,"status"=>3);
														//print_r($push_message);
														//exit;
														$p_send_notification = $api->send_passenger_mobile_pushnotification($p_device_token,$p_device_type,$push_message,$this->customer_google_api);															
													}	
													elseif(($array['status'] == 'A') && ($passengers_log_id == null))
													{
														$message = array("message" => __('invalid_trip_id'),"status"=>-1,"detail"=>$trip_details);
													}
													else
													{
														if(($array['latitude'] != 0) &&($array['longitude'] != 0))
														{
															if(($array['status'] == 'B')||($array['status'] == 'S'))
															{
																$notifify_update_array  = array("notification_setting"=>"0");
																$login_status_update = $Commonmodel->update(PEOPLE,$notifify_update_array,'id',$array['driver_id']);
															}
															$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$array['driver_id']);	
														}
														$message = array("message" => __('driver_location_update'),"status"=>1);
													}
												}
												else
												{
													$update_driver_arrary  = array(
													"latitude" => $array['latitude'],
													"longitude" => $array['longitude'],
													"status" => strtoupper($array['status']));	
													//print_r($update_driver_arrary);
													if(($array['latitude'] != 0 ) &&($array['longitude'] != 0))
													{
														$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$array['driver_id']);	
													}
													$message = array("message" => __('already_trip'),"status"=>-1);
												}
								}
								else
								{									
									$insert_array = array(
									"driver_id" => $array['driver_id'],
									"latitude"		=> $array['latitude'],
									"longitude"		=> $array['longitude'],
									"status"			=> 'F',
									"shift_status" => 'OUT');									
									if(($array['latitude'] != 0) &&($array['longitude'] != 0))
									{
										$transaction = $Commonmodel->insert(DRIVER,$insert_array);
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
					
			//URL : http://192.168.1.88:1020/api/index/dGF4aV9KcUJRWjgwVjRJajc1RXhrSXFwaXpSUHA5Umd3eGI=?type=driver_reply&pass_logid=341&driver_id=&taxi_id=&company_id=&driver_reply=R&field=Sample Comments for rejection&flag=0
			case 'driver_reply':
			
					if($array['pass_logid'] != null)
					{
						$api_model = Model::factory('api');			
						$common_model = Model::factory('commonmodel');			

						array_shift($array);
						array_shift($array);
							
						$pass_logid = $array['pass_logid'];
						$driver_reply = $array['driver_reply'];
						$driver_id = $array['driver_id'];
						$taxi_id = $array['taxi_id'];
						$company_id = $array['company_id'];
						$field = $array['field'];
						$flag = $array['flag'];							
						if($driver_reply == 'A'){$travel_status = 9;}else{$travel_status=10;}
						
						//print_r($get_passenger_log_det);

						$result = $api_model->update_driverreply_status($pass_logid,$driver_id,$taxi_id,$company_id,$driver_reply,$travel_status,$field,$flag,$default_companyid);	
						//echo $result;exit;
							
						if($result == 1)
						{
							if($driver_reply == 'A')
							{
								$update_trip_array  = array("status"=>'3');
								$update_result = $api_model->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$pass_logid);	
							}
							$message = __('request_confirmed');	
							$push_msg = __('driver_confirm_push');
							$push_status = 1;
							$response_status = 1;							
							//$delete_pass_log_temp =$api->delete_passengers_log_temp($pass_logid);
						}
						else if($result == 2)		
						{	
							$message = __('request_rejected');
							$push_msg = __('request_rejected_passenger');
							$push_status = 6;
							$response_status = 2;
						}else if($result == 3)		
						{	
							$message = __('trip_cancelled_driver');
							$push_msg = __('driver_cancel_after_confirm');
							$push_status = 7;
							$response_status = 3;
						}else if($result == 4)		
						{	
						$message = __('trip_already_cancel_rejected');
						$push_msg = __('trip_already_cancel_rejected');
						$push_status = 8;
						$response_status = 4;
						}
						else if($result == 5){
							$message = __('trip_already_confirm');	
							$push_msg = __('trip_already_confirm');	
							$push_status = 9;
							$response_status = 5;
						}
						else if($result == 6){
							$message = __('trip_already_rejected');	
							$push_msg = __('trip_already_rejected');	
							$push_status = 10;
							$response_status =6;
						}							
						else if($result == 7){
							$message = __('trip_cancel');
							$push_msg = __('trip_cancel');
							$push_status = 11;
							$response_status = 7;
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
							$common_model = Model::factory('commonmodel');	
							$message_details = $common_model->sms_message('3');
							$to = $driver_phone;
							$message_temp = $message_details[0]['sms_description'];
							$sms_message = str_replace("##booking_key##",$pass_logid,$message_temp);
					
							//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($sms_message)."&senderid=NAJIK&recipient=$to");
							}
								
								//print_r($taxi_details);									
								//echo $additional;
								//Rating of the Driver			
								$totalrating = "";																
								$driverdetails = array();
								$trip_detail = array();
										
													$detail = array("trip_id"=>$pass_logid,"driverdetails"=>$driverdetails);
													if($response_status == 1)
													{
														$msg = array("message" => $message,"status" => $response_status,"detail"=>$detail);	
													}
													else
													{
														$msg = array("message" => $message,"status" => $response_status);	
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
						$message = array("message" => __('invalid_trip'),"status"=>-1);	
					}
					echo json_encode($msg);
					break;
			
			
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9YRlJJb1p0NjdxYTU5ZmlIRFl1OGJPQ0J2elRHQVYxZmY=?type=driver_status_select&driver_id=60	
			case 'driver_status_select':
			
					$check_result = $api->check_driver_companydetails($array['driver_id'],$default_companyid);
					if($check_result == 0)	
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);
						echo json_encode($message);
						exit;;
					}

					if($array['driver_id'] != null)
												{																						
						//$result = $api->select_table(DRIVER,'driver_id',$array['driver_id']);	
						$result = $api->select_driverloc($array['driver_id'],$default_companyid);	

						$driver_current_journey = $api->get_driver_current_journey($array['driver_id'],$default_companyid,'0');

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
			
					if($array['pass_logid'] != null)
					{													
						$result = $api->select_table(PASSENGERS_LOG,'passengers_log_id',$array['pass_logid']);		
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
			
					if($array['pass_logid'] != null)
					{	
						//Removing the URL Other parameters
						array_shift($array);
						array_shift($array);						
						//print_r($array);
						$passengers_log_id = $array['pass_logid'];
						
						//Removing the $array['pass_logid'] from array	for the $org_array
						array_shift($array);	
												
						foreach($array as $key=>$arr)
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
			
					$check_result = $api->check_driver_companydetails($array['driver_id'],$default_companyid);
					if($check_result == 0)	
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);
						echo json_encode($message);
						exit;
					}

					if($array['driver_id'] != null)
					{
						$driver_id = $array['driver_id'];
						
						$driver_model = Model::factory('driver');			
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
			//URL : api/?type=driver_upcoming_journey_detail&passengers_log_id=3
			case 'driver_upcoming_journey_detail':			
					if($array['passengers_log_id'] != null)
					{
						$passengers_log_id = $array['passengers_log_id'];
						
						$api_model = Model::factory('api');			
						$get_passenger_log_details = $api_model->get_driverupcoming_log_details($passengers_log_id,$default_companyid);		
						
						foreach($get_passenger_log_details as $journey)
						{
							$upcoming_journey[] = (array) $journey;						   
						}
						//print_r($upcoming_journey);
						if(count($get_passenger_log_details) == 0)
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
						$message = array("message" => 'Invalid Trip',"status"=>-1);	
					}
					echo json_encode($message);
					break;					
			//Company URL : api/?type=completed_journey_driver&driver_id=7
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=driver_sales_datewise_summary&driver_id=3&date=2014-01-05
			case 'driver_sales_datewise_summary':			
				$array = $_GET;		
				//print_r($array);		
				if($array['driver_id'] != null)
				{
					$start = "";
					$limit = "";	
					$date = $array['date'];							
					$driver_id = $array['driver_id'];							
					// Summary 1 -> Summary only 0 -> Detail with rows
					// second arg is 1 - pagination 0 = no pagination
					// Booktype(booking from) 0 and 1 -> Flagger Ride, 2-> Strret Ride, 3-> All					
					$summary = 1;
					$pagination="0";
					$booktype="0";									
					$flagger_driver_sales = $api->get_driver_sales_by_date($summary,$pagination,$booktype,$driver_id,'R','A','1',$start,$limit,$date);			
                    $booktype="2";
                    $streetride_driver_sales = $api->get_driver_sales_by_date($summary,$pagination,$booktype,$driver_id,'R','A','1',$start,$limit,$date);	
					//print_r($flagger_driver_sales);exit;
					
						if(count($flagger_driver_sales)>0)
						{
							$driver_flagger_sales = $flagger_driver_sales[0]->total;						   
						}
						
						if(count($streetride_driver_sales)>0)
						{
							$driver_street_sales =  $streetride_driver_sales[0]->total;		
						}					   						
											
						if($driver_flagger_sales != null)
						{
							$flagger_ride = round($driver_flagger_sales,2);	
						}
						else
						{
							$flagger_ride = 0;
						}

						if($driver_street_sales != null)
						{
							$street_ride = round($driver_street_sales,2);	
						}
						else
						{
							$street_ride = 0;
						}
				//****************** Get Expense for the Driver ************************************/
				    // First arg is 0 - give all details, 1 - Summary(total value). second arg is 1 - pagination 0 = no pagination
                    $driver_expense = $api->get_driver_expense_by_date('0','0','',$driver_id,$start,$limit,$date);
           			//print_r($driver_expense);exit;
					$petrol = $food = $rent = $misc = $expense_amount  = 0;
					foreach($driver_expense as $values)
					{
						$expense_type_id = $values->expense_type_id;
						$expense_amount = round($values->expense_amount,2);	
						if($expense_type_id == 1)
						{
							//echo $expense_amount;
							$petrol += $expense_amount;
							//echo $petrol;
						}					
						if($expense_type_id == 2)
						{
							$food += $expense_amount;
						}
						if($expense_type_id == 3)
						{
							$rent += $expense_amount;
						}
						if($expense_type_id == 4)
						{
							$misc += $expense_amount;
						}
					}
					$total_revenue = $flagger_ride + $street_ride;
					$total_expense = $petrol + $food + $rent + $misc;
					$profit_loss = $total_revenue - $total_expense;
					//echo $petrol;
					
					$flagger_ride = number_format($flagger_ride, 2, '.', '');
					$street_ride = number_format($street_ride, 2, '.', '');
					$petrol = number_format($petrol, 2, '.', '');
					$food = number_format($food, 2, '.', '');
					$rent = number_format($rent, 2, '.', '');
					$misc = number_format($misc, 2, '.', '');
					$profit_loss = number_format($profit_loss, 2, '.', '');
					
					$salesbyday = array("flagger_ride"=>$flagger_ride,"street_ride"=>$street_ride,"petrol"=>$petrol,"food"=>$food,"rent"=>$rent,"misc"=>$misc,"profit_loss"=>$profit_loss);
						$message = array("message" =>__('success'),"detail"=>$salesbyday,"status" => 1);		
						
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
			// All Company URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=driver_sales_weekwise_summary&driver_id=3&startdate=2014-02-03&enddate=2014-02-07
			case 'driver_sales_weekwise_summary':			
				$array = $_GET;		
				//print_r($array);		
				if($array['driver_id'] != null)
				{
					$start = ""; // For pagination like pull to refresh - optional
					$limit = ""; // For pagination like pull to refresh - optional
					$startdate = $array['startdate'];							
					$enddate = $array['enddate'];
					$driver_id = $array['driver_id'];		
					// Summary 1 -> Summary only, 0 -> Detail with rows
					// Second arg is 1 - pagination 0 = no pagination
					// Booktype(booking from) 0 and 1 -> Flagger Ride, 2-> Strret Ride, 3-> All						
					$summary = 1;	
					$pagination="0";
					
					$booktype="0";																		
					$flagger_driver_sales = $api->get_driver_sales_by_week($summary,$pagination,$booktype,$driver_id,'R','A','1',$start,$limit,$startdate,$enddate);			
                    $booktype="2";
                    $streetride_driver_sales = $api->get_driver_sales_by_week($summary,$pagination,$booktype,$driver_id,'R','A','1',$start,$limit,$startdate,$enddate);	
                    
					//print_r($flagger_driver_sales);exit;
 					
						if(count($flagger_driver_sales)>0)
						{
							$driver_flagger_sales = $flagger_driver_sales[0]->total;						   
						}
						
						if(count($streetride_driver_sales)>0)
						{
							$driver_street_sales =  $streetride_driver_sales[0]->total;		
						}					   						
											
						if($flagger_driver_sales != null)
						{
							$flagger_ride = round($driver_flagger_sales,2);	
						}
						else
						{
							$flagger_ride = 0;
						}

						if($driver_street_sales != null)
						{
							$street_ride = round($driver_street_sales,2);	
						}
						else
						{
							$street_ride = 0;
						}					
				   //****************** Get Expense for the Driver ************************************/
				   // First arg is 0 - give all details, 1 - Summary(total value). second arg is 1 - pagination 0 = no pagination
                    $driver_expense = $api->get_driver_expense_by_week('0','0','',$driver_id,$start,$limit,$startdate,$enddate);
					//print_r($driver_expense);exit;
					$petrol = $food = $rent = $misc = 0;
					foreach($driver_expense as $values)
					{
						$expense_type_id = $values->expense_type_id;
						$expense_amount = round($values->expense_amount,2);	
						if($expense_type_id == 1)
						{
							$petrol += $expense_amount;
						}					
						if($expense_type_id == 2)
						{
							$food += $expense_amount;
						}
						if($expense_type_id == 3)
						{
							$rent += $expense_amount;
						}
						if($expense_type_id == 4)
						{
							$misc += $expense_amount;
						}
					}
					$total_revenue = $flagger_ride + $street_ride;
					$total_expense = $petrol + $food + $rent + $misc;
					$profit_loss = $total_revenue - $total_expense;
					
					$flagger_ride = number_format($flagger_ride, 2, '.', '');
					$street_ride = number_format($street_ride, 2, '.', '');
					$petrol = number_format($petrol, 2, '.', '');
					$food = number_format($food, 2, '.', '');
					$rent = number_format($rent, 2, '.', '');
					$misc = number_format($misc, 2, '.', '');	
					$profit_loss = number_format($profit_loss, 2, '.', '');				
					
					$salesbyday = array("flagger_ride"=>$flagger_ride,"street_ride"=>$street_ride,"petrol"=>$petrol,"food"=>$food,"rent"=>$rent,"misc"=>$misc,"profit_loss"=>$profit_loss);
						$message = array("message" =>__('success'),"detail"=>$salesbyday,"status" => 1);		
						
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;

			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=driver_sales_monthwise_summary&driver_id=3&month=02&year=2014
			case 'driver_sales_monthwise_summary':
			
				$array = $_GET;		
				//print_r($array);		
				if($array['driver_id'] != null)
				{
					$start = "";
					$limit = "";	
					$month = $array['month'];	
					$year = $array['year'];							
					$driver_id = $array['driver_id'];						
					$summary = 1;	
					// Booktype(booking from) 0 and 1 -> Flagger Ride, 2-> Strret Ride, 3-> All	
					$booktype="0";									
					$flagger_driver_sales = $api->get_driver_sales_by_month($summary,$booktype,$driver_id,'R','A','1',$start,$limit,$month,$year);			
                    $booktype="2";
                    $streetride_driver_sales = $api->get_driver_sales_by_month($summary,$booktype,$driver_id,'R','A','1',$start,$limit,$month,$year);
                   // print_r($streetride_driver_sales);//exit;
					
						if(count($flagger_driver_sales)>0)
						{
							$driver_flagger_sales = $flagger_driver_sales[0]->total;						   
						}
						
						if(count($streetride_driver_sales)>0)
						{
							$driver_street_sales =  $streetride_driver_sales[0]->total;		
						}					   						
											
						if($driver_flagger_sales != null)
						{
							$flagger_ride = round($driver_flagger_sales,2);	
						}
						else
						{
							$flagger_ride = 0;
						}

						if($driver_street_sales != null)
						{
							$street_ride = round($driver_street_sales,2);	
						}
						else
						{
							$street_ride = 0;
						}
				//****************** Get Expense for the Driver ************************************/				    
                    // First arg is summary - 0. here we get all expense details with pagination
					$driver_expense = $api->get_driver_expense_by_month('1','',$driver_id,$start,$limit,$month,$year);
					//print_r($driver_expense);exit;
					$petrol = $food = $rent = $misc = 0;
					foreach($driver_expense as $values)
					{
						$expense_type_id = $values->expense_type_id;
						$expense_amount = round($values->expense_amount,2);	
						if($expense_type_id == 1)
						{
							$petrol += $expense_amount;
						}					
						if($expense_type_id == 2)
						{
							$food += $expense_amount;
						}
						if($expense_type_id == 3)
						{
							$rent += $expense_amount;
						}
						if($expense_type_id == 4)
						{
							$misc += $expense_amount;
						}
					}
					$total_revenue = $flagger_ride + $street_ride;
					$total_expense = $petrol + $food + $rent + $misc;
					$profit_loss = $total_revenue - $total_expense;
					
					$flagger_ride = number_format($flagger_ride, 2, '.', '');
					$street_ride = number_format($street_ride, 2, '.', '');
					$petrol = number_format($petrol, 2, '.', '');
					$food = number_format($food, 2, '.', '');
					$rent = number_format($rent, 2, '.', '');
					$misc = number_format($misc, 2, '.', '');		
					$profit_loss = number_format($profit_loss, 2, '.', '');			
					
					$salesbyday = array("flagger_ride"=>$flagger_ride,"street_ride"=>$street_ride,"petrol"=>$petrol,"food"=>$food,"rent"=>$rent,"misc"=>$misc,"profit_loss"=>$profit_loss);
						$message = array("message" =>__('success'),"detail"=>$salesbyday,"status" => 1);								
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
			// All Company URL : 
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=driver_sales_datewise_details&driver_id=109&date=2014-01-05&start=0&limit=5&booktype=0
			case 'driver_sales_datewise_details':
			
				$array = $_GET;		
				//print_r($array);		
				if($array['driver_id'] != null)
				{
					$start = $array['start'];
					$limit = $array['limit'];	
					$date = $array['date'];							
					$driver_id = $array['driver_id'];	
					$booktype = $array['booktype'];
					// Summary 1 -> Summary only 0 -> Detail with rows
					// second arg is 1 - pagination 0 = no pagination
					// Booktype 0 -> Flagger Ride, 1-> Strret Ride, 2-> All					
					$summary = 0;
					$pagination=1;
					if($booktype == 0)
					{	
					$driver_sales = $api->get_driver_sales_by_date($summary,$pagination,$booktype,$driver_id,'R','A','1',$start,$limit,$date);	
					}
					else
					{
                    $driver_sales = $api->get_driver_sales_by_date($summary,$pagination,$booktype,$driver_id,'R','A','1',$start,$limit,$date);	
					}
					//print_r($driver_sales);exit;										   						
						foreach($driver_sales as $journey)
						{
							$driver_sales_result[] = (array) $journey;						   
						}
												
						if(count($driver_sales) == 0)
						{
							$message = array("message" => __('no_sales_data'),"status"=>0);	
						}
						else
						{
							$message = array("message" =>__('success'),"detail"=>$driver_sales_result,"status" => 1);																	
						}
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=driver_expense_datewise_details&driver_id=3&date=2014-01-05&expense_type_id=1&start=0&limit=5
			case 'driver_expense_datewise_details':
			
				$array = $_GET;		
				//print_r($array);		
				if($array['driver_id'] != null)
				{
					$start = $array['start'];
					$limit = $array['limit'];
					$date = $array['date'];							
					$driver_id = $array['driver_id'];	
					$expense_type_id = $array['expense_type_id'];
					// Summary 1 -> Summary only, 0 -> Detail with rows
					// Booktype 0 -> Flagger Ride, 1-> Strret Ride, 2-> All
				/****************** Get Expense for the Driver ************************************/
				     // First arg is summary - 1. here we get all expense details with pagination
                    $driver_expense = $api->get_driver_expense_by_date('0','1',$expense_type_id,$driver_id,$start,$limit,$date);
					//print_r($driver_expense);exit;
					
						foreach($driver_expense as $expense)
						{
							$driver_expense_result[] = (array) $expense;						   
						}
												
						if(count($driver_expense) == 0)
						{
							$message = array("message" => __('no_expense_data'),"status"=>0);	
						}
						else
						{
							$message = array("message" =>__('success'),"detail"=>$driver_expense_result,"status" => 1);																	
						}
				}
				else
				{
					$message = array("message" => __('invalid_user'),"status"=>-1);	
				}
					echo json_encode($message);
					break;

			// All Company URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=driver_sales_weekwise_details&driver_id=109&startdate=2014-02-03&enddate=2014-02-07&start=0&limit=5&booktype=1
			case 'driver_sales_weekwise_details':
			
				$array = $_GET;		
				//print_r($array);		
				if($array['driver_id'] != null)
				{
					$start = "";//$array['start'];
					$limit = "";//$array['limit'];	
					$startdate = $array['startdate'];							
					$enddate = $array['enddate'];
					$driver_id = $array['driver_id'];	
					$booktype = $array['booktype'];
					/*$summary = 0;	
					// Summary 1 -> Summary only, 0 -> Detail with rows
					// Booktype 0 -> Flagger Ride, 1-> Strret Ride, 2-> All
					if($booktype == 0)
					{								
					$driver_sales = $api->get_driver_sales_by_week($summary,$booktype,$driver_id,'R','A','1',$start,$limit,$startdate,$enddate);			
					}
					else
					{
                    $driver_sales = $api->get_driver_sales_by_week($summary,$booktype,$driver_id,'R','A','1',$start,$limit,$startdate,$enddate);	
					}*/
                   $perdayresult = array();
                   $alldetails = array();
                   for($i = strtotime($startdate); $i <= strtotime($enddate); $i = strtotime('+1 Day', $i))
                   {
						$date = date('Y-m-d',$i);
						$day = date('l',$i);
						// Summary 1 -> Summary only 0 -> Detail with rows
						// second arg is 1 - pagination 0 = no pagination
						// Booktype 0 -> Flagger Ride, 1-> Strret Ride, 2-> All					
						$summary = 1;
						$pagination=0;
						if($booktype == 0)
						{	
						$driver_sales = $api->get_driver_sales_by_date($summary,$pagination,$booktype,$driver_id,'R','A','1',$start,$limit,$date);	
						}
						else
						{
						$driver_sales = $api->get_driver_sales_by_date($summary,$pagination,$booktype,$driver_id,'R','A','1',$start,$limit,$date);	
						}					
						//print_r($driver_sales);exit;										   						
						foreach($driver_sales as $value)
						{
							if($value->total != null)
								{
									$perdayresult['total'] = $value->total;
								}
								else
								{
									$perdayresult['total'] = '0.00';
								}

								$perdayresult['sales_day'] = $day;	
								$alldetails[] = $perdayresult;							   
						}
												
						if(count($alldetails) == 0)
						{
							$message = array("message" => __('no_sales_data'),"status"=>0);	
						}
						else
						{
							$message = array("message" =>__('success'),"detail"=>$alldetails,"status" => 1);																	
						}
					}
				}
				else
				{
					$message = array("message" => __('invalid_user'),"status"=>-1);	
				}
				echo json_encode($message);
				break;

			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=driver_expense_weekwise_details&driver_id=3&startdate=2014-02-03&enddate=2014-02-07&expense_type_id=1&start=0&limit=5
			case 'driver_expense_weekwise_details':
			
				$array = $_GET;		
				//print_r($array);		
				if($array['driver_id'] != null)
				{
					$start = "";//$array['start']; // For pagination like pull to refresh
					$limit = "";//$array['limit'];	// For pagination like pull to refresh
					$startdate = $array['startdate'];							
					$enddate = $array['enddate'];
					$driver_id = $array['driver_id'];	
					$expense_type_id = $array['expense_type_id'];	
					// Summary 1 -> Summary only, 0 -> Detail with rows
					/****************** Get Expense for the Driver ************************************/
				    // First arg is summary - 1. here we get all expense details with pagination
                   // $driver_expense = $api->get_driver_expense_by_week('1',$expense_type_id,$driver_id,$start,$limit,$startdate,$enddate);
                   $perdayresult = array();
                   $alldetails = array();
                   for($i = strtotime($startdate); $i <= strtotime($enddate); $i = strtotime('+1 Day', $i))
                   {
						$date = date('Y-m-d',$i);
						$day = date('l',$i);
						// First arg is 0 - give all details, 1 - Summary(total value). second arg is 1 - pagination 0 = no pagination
						$driver_expense = $api->get_driver_expense_by_date('1','0',$expense_type_id,$driver_id,$start,$limit,$date);
							foreach($driver_expense as $value)
							{
								if($value->total_amt != null)
								{
									$perdayresult['total_amt'] = $value->total_amt;
								}
								else
								{
									$perdayresult['total_amt'] = "0.00";
								}
								if($value->expense_day != null)
								{
									$perdayresult['expense_day'] = $day;	
								}
								else
								{
									$perdayresult['expense_day'] = $day;
								}
								$alldetails[] = $perdayresult;						
							}

						//print_r($driver_expense);
				   }
					
						if(count($alldetails) == 0)
						{
							$message = array("message" => __('no_expense_data'),"status"=>0);	
						}
						else
						{
							$message = array("message" =>__('success'),"detail"=>$alldetails,"status" => 1);																	
						}	
						
				}
				else
				{
					$message = array("message" => __('invalid_user'),"status"=>-1);	
				}
				echo json_encode($message);
				break;

			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=driver_sales_monthwise_details&driver_id=3&month=02&year=2014&booktype=0
			case 'driver_sales_monthwise_details':
			
				$array = $_GET;		
				//print_r($array);		
				if($array['driver_id'] != null)
				{
					$start = "";//$array['start'];
					$limit = "";//$array['limit'];	
					$month = $array['month'];	
					$year = $array['year'];							
					$booktype = $array['booktype'];	
					$driver_id = $array['driver_id'];						
					
                    $fromdate = $year.'-'.$month.'-'.'01';
					$todate = date('Y-m-t', strtotime($fromdate));
					$num = date("w",mktime(0,0,0,$month,1,$year));
                    $first_sunday_of_week =  date("d",mktime(0,0,0,$month,1,$year)+(86400*(7-$num)));
					$pending_days = $first_sunday_of_week - 1;
					
					$total_weeks = '';
					$ach_amt = '';
					$target_amount = '';
					$j=1;
					$perweekresult = array();
					$alldetails = array();
					for($i = strtotime($fromdate); $i <= strtotime($todate); $i = strtotime('+7 day', $i))
					{
						$from = date("Y-m-d", strtotime('+0 day', $i));
						$m = date("m", strtotime('+6 day', $i));
						if($m != $month)
						{
							$to = date('Y-m-t', strtotime($fromdate));
						}
						else
						{
							$to = date("Y-m-d", strtotime('+6 day', $i));
						}
							
							//Get week values
							$total_weeks = 'week'.$j.', ';
							/*$week =  date('W', $i);
							$year =  date('Y', $i);
							$from = date("Y-m-d", strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week
							$to = date("Y-m-d", strtotime("{$year}-W{$week}-7"));   //Returns the date of sunday in week 
							* 
							*Get first to 31 as per week
							* -------------------------- *
							$suff = array('st','nd','rd','th','th','th'); 		//week suffixes
							$end = date('t',mktime(0,0,0,$month,1,$year)); 		//last date day of month: 28 - 31
							$start = date('w',mktime(0,0,0,$month,1,$year)); 	//1st day of month: 0 - 6 (Sun - Sat)
							$last = 7 - $start; 					//get last day date (Sat) of first week
							$noweeks = ceil((($end - ($last + 1))/7) + 1);		//total no. weeks in month
							$output = "";						//initialize string		
							$monthlabel = str_pad($month, 2, '0', STR_PAD_LEFT);
							for($x=1;$x<$noweeks+1;$x++){	
								if($x == 1){
									$startdate = "$year-$monthlabel-01";
									$day = $last - 6;
								}else{
									$day = $last + 1 + (($x-2)*7);
									$day = str_pad($day, 2, '0', STR_PAD_LEFT);
									$startdate = "$year-$monthlabel-$day";
								}
								if($x == $noweeks){
									$enddate = "$year-$monthlabel-$end";
								}else{
									$dayend = $day + 6;
									$dayend = str_pad($dayend, 2, '0', STR_PAD_LEFT);
									$enddate = "$year-$monthlabel-$dayend";
								}
								$output .= "{$x}{$suff[$x-1]} week -> Start date=$startdate End date=$enddate <br />";	
							}
							echo $output;
							* */
							
							//Get the taget amount values
							$month = date('m', $i);
							$year = date('Y', $i);
							$summary = 1;
							$pagination=0;
							$driver_sales = $api->get_driver_sales_by_week($summary,$pagination,$booktype,$driver_id,'R','A','1',$start,$limit,$from,$to);
							foreach($driver_sales as $value)
							{
								if($value->total != null)
								{
									$perweekresult['total_amt'] = $value->total;
								}
								else
								{
									$perweekresult['total_amt'] = "0.00";
								}
								
								$perweekresult['week'] = $total_weeks;
								$alldetails[] = $perweekresult;
							} 
							$j++;
						} 
						if(count($alldetails) == 0)
						{
							$message = array("message" => __('no_sales_data'),"status"=>0);	
						}
						else
						{
							$detail = array("saleslist"=>$alldetails,"pending_days" => $pending_days);
							$message = array("message" =>__('success'),"detail"=>$detail,"status" => 1);
						}
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
	

			//http://192.168.1.88:1009/api/index/?type=driver_expense_monthwise_details&driver_id=109&expense_type_id=1&month=02&year=2014
		
			case 'driver_expense_monthwise_details':
			
				$array = $_GET;		
				//print_r($array);		
				if($array['driver_id'] != null)
				{
					$start = "";//$array['start']; // For pagination like pull to refresh
					$limit = "";//$array['limit'];	// For pagination like pull to refresh	
					$month = $array['month'];	
					$year = $array['year'];							
					$driver_id = $array['driver_id'];						
					$expense_type_id = $array['expense_type_id'];
					$summary = 0;	
					// Summary 1 -> Summary only, 0 -> Detail with rows	
					/****************** Get Expense for the Driver ***********************************/
				    // First arg is summary - 1. here we get all expense details with pagination
                    //$driver_expense = $api->get_driver_expense_by_month('1',$expense_type_id,$driver_id,$start,$limit,$month,$year);
					//print_r($driver_expense);exit;
					$fromdate = $year.'-'.$month.'-'.'01';
					$todate = date('Y-m-t', strtotime($fromdate));
					$num = date("w",mktime(0,0,0,$month,1,$year));
                    $first_sunday_of_week =  date("d",mktime(0,0,0,$month,1,$year)+(86400*(7-$num)));
					$pending_days = $first_sunday_of_week - 1;
					
						$total_weeks = '';
						$ach_amt = '';
						$target_amount = '';
						$j=1;
						$perweekresult = array();
						$alldetails = array();
						for($i = strtotime($fromdate); $i <= strtotime($todate); $i = strtotime('+7 day', $i))
						{
							//Get week values
							$total_weeks = 'week'.$j.',';
							/*$week =  date('W', $i);
							$year =  date('Y', $i);
							$from = date("Y-m-d", strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week
							$to = date("Y-m-d", strtotime("{$year}-W{$week}-7"));   //Returns the date of sunday in week*/
							
							$from = date("Y-m-d", strtotime('+0 day', $i));
							$m = date("m", strtotime('+6 day', $i));
							if($m != $month)
							{
								$to = date('Y-m-t', strtotime($fromdate));
							}
							else
							{
								$to = date("Y-m-d", strtotime('+6 day', $i));
							}
							
							//Get the taget amount values
							$month = date('m', $i);
							$year = date('Y', $i);
							$driver_expense = $api->get_driver_expense_by_week('1','0',$expense_type_id,$driver_id,$start,$limit,$from,$to);
							//$driver_logs_target = $api->get_driver_target_by_month($driver_id,'R','A','1',$from,$to);
							foreach($driver_expense as $value)
							{
								if($value->total_amt != null)
								{
									$perweekresult['total_amt'] = $value->total_amt;
								}
								else
								{
									$perweekresult['total_amt'] = "0.00";
								}
								
								$perweekresult['week'] = $total_weeks;							
								$alldetails[] = $perweekresult;						
							}
							$j++;
						} 
						if(count($alldetails) == 0)
						{
							$message = array("message" => __('no_expense_data'),"status"=>0);	
						}
						else
						{
							$detail = array("saleslist"=>$alldetails,"pending_days" => $pending_days);
							$message = array("message" => __('success'),"detail" => $detail,"status" => 1);																	
						}	
						
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;

			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=get_expensetype_list
			case 'get_expensetype_list':
				$exp_type_array = $_GET;
				$exp_type_list = $api->get_expensetype_list();
				$message = array("message" => __('success'),"detail"=>$exp_type_list,"status"=>1);	
				echo json_encode($message);
				break;	

			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=add_street_pickup&driver_id=16&pickup_location=gandhipuram,coimbatore&drop_location=marudhamalai,coimbatore&distance=10&fare=100.32&tips=10.00&travel_date=2014-02-11 15:16:00
			case 'add_street_pickup':
				$street_array = $_GET;
				//print_r();
				$validator = $this->street_validation($street_array);				
				if($validator->check())
				{
					//Set the Favourite Trips
					$status = $api->save_streetpickup($street_array);
					if($status == 1)					
					{
						$message = array("message" => __('street_saved'),"status"=>1);
					}
					else
					{
						$message = array("message" => __('try_again'),"status"=>0);	
					}						
				}
				else
				{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"detail"=>$validation_error,"status"=>-3);								
				}
				echo json_encode($message);
				break;			
	
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=add_expense&expense_driver_id=99&expense_amount=100&expense_type_id=2&expense_date=2012-12-26 &notes=this is the expense entry&recurring=1&fromdate=2014-02-01&todate=2014-02-20
			case 'add_expense':
				$add_exp_array = $_GET;
				//print_r();
				$validator = $this->expense_validation($add_exp_array);				
				if($validator->check())
				{
					//Set the Favourite Trips
					$status = $api->save_expense($add_exp_array);
					if($status == 1)					
					{
						$message = array("message" => __('exp_saved'),"status"=>1);
					}
					else
					{
						$message = array("message" => __('try_again'),"status"=>0);	
					}						
				}
				else
				{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"detail"=>$validation_error,"status"=>-3);								
				}
				echo json_encode($message);
				break;
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=edit_expense&expense_id=365&expense_driver_id=99&expense_amount=150&expense_type_id=2&expense_date=2012-12-26 &notes=this is the expense entry&recurring=1&fromdate=2014-02-05&todate=2014-02-25
			case 'edit_expense':
				$edit_exp_array = $_GET;
				//print_r();
				$validator = $this->expense_validation($edit_exp_array);				
				if($validator->check())
				{
					//Set the Favourite Trips
					$status = $api->edit_expense($edit_exp_array);
					if($status == 1)					
					{
						$message = array("message" => __('exp_edited'),"status"=>1);
					}
					else
					{
						$message = array("message" => __('try_again'),"status"=>0);	
					}						
				}
				else
				{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"detail"=>$validation_error,"status"=>-3);								
				}
				echo json_encode($message);
				break;							
			
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=delete_expense&expense_id=33
			case 'delete_expense':
				$delete_exp_array = $_GET;
				$validator = $this->delete_expense_validation($delete_exp_array);				
				if($validator->check())
				{					
					$status = $api->delete_expense($delete_exp_array);
					if($status == 1)					
					{
						$message = array("message" => __('expense_delete_success'),"status"=>1);
					}
					else
					{
						$message = array("message" => __('try_again'),"status"=>0);	
					}						
				}
				else
				{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"detail"=>$validation_error,"status"=>-3);								
				}
				echo json_encode($message);
				break;
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=delete_passenger_card&passenger_cardid=33
			case 'delete_passenger_card':
				$delete_card_array = $_GET;
				$validator = $this->delete_card_validation($delete_card_array);				
				if($validator->check())
				{					
					$status = $api->delete_card($delete_card_array);
					if($status == 1)					
					{
						$message = array("message" => __('card_delete_success'),"status"=>1);
					}
					else
					{
						$message = array("message" => __('try_again'),"status"=>0);	
					}						
				}
				else
				{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"detail"=>$validation_error,"status"=>-3);								
				}
				echo json_encode($message);
				break;				
			//http://192.168.1.88:1000/api/index/?type=get_target&target_date=2014-02-12&driver_id=16
			case 'get_target':
				$get_tar_array = $_GET;
				$target_date = date('Y-m-d',strtotime($get_tar_array['target_date']));
				$target_driver_id = $get_tar_array['driver_id'];
				$get_tar_array = $api->get_target($target_date,$target_driver_id);
				if(count($get_tar_array)>0)
				{
					$message = array("message" =>__('success'),"detail"=>$get_tar_array,"status"=>1);	
				}
				else
				{
					$message = array("message" => __('no_target'),"status"=>0);	
				}
				echo json_encode($message);
				break;					
			//http://192.168.1.88:1009/api/index/?type=save_target&target_driver_id=16&target_id=&target_amount=100&recurring=0&fromdate=2014-02-05&todate=2014-02-25&target_date=2014-02-12
			case 'save_target':
				$add_target_array = $_GET;
				$target_date = date('Y-m-d',strtotime($add_target_array['target_date']));
				$target_driver_id = $add_target_array['target_driver_id'];
				//print_r();
				$validator = $this->target_validation($add_target_array);				
				if($validator->check())
				{
					$check_target = $api->check_today_target($target_date,$target_driver_id);
					if($check_target == 0)
					{
						$status = $api->save_target($add_target_array);
					}
					else
					{
						$status = $api->update_target($add_target_array);
					}
					if($status)					
					{
						$message = array("message" => __('target_saved'),"status"=>1);
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
			// http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=tell_to_friend_by_sms&driver_id=16&phone=
			case 'tell_to_friend_by_sms':
			$tell_sms_array = $_GET;
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
					$common_model = Model::factory('commonmodel');
					$message_details = $common_model->sms_message('7');
					$to = $tell_sms_array['phone'];
					$message = $message_details[0]['sms_description'];
					$message = str_replace("##DRIVERAPPURL##",$this->driver_app_url,$message);
					$message = DRIVER_TELL_TO_FRIEND_MESSAGE;//str_replace("##REFERRAL_CODE##",$driver_referral_code,$message);
							
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
			$tell_mail_array = $_GET;
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
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_NAME=>$name,REPLACE_SUBJECT=>$subject,REPLACE_MESSAGE=>$message,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver_telltofriend.html',$replace_variables);
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
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=get_target_by_week&driver_id=16&fromdate=2013-12-01&todate=2013-12-30
			case 'get_target_by_week':
				$array = $_GET;		
				//print_r($array);		
				if($array['driver_id'] != null)
				{
					$fromdate = $array['fromdate'];		
					$todate = $array['todate'];							
					$driver_id = $array['driver_id'];
					$validator = $this->get_target_validation($array);
					if($validator->check())
					{	
						$total_dates ='';
						$selected_day = '' ;
						$ach_amt = '';
						$target_amount = '';
						//echo strtotime($fromdate);
						//echo strtotime($todate);
						for($i = strtotime($fromdate); $i <= strtotime($todate); $i = strtotime('+1 Day', $i))
						{
							//Get the date values
							$get_date = date('Y-m-d', $i);
							//echo '<br>';
							$total_dates .= $get_date.',';
							//Get the day values
							$array_dates = getdate($i);
							$selected_day .= mb_substr($array_dates['weekday'], 0, 3, 'utf-8').',';
							//Get the taget amount values
							$target_date = date('Y-m-d', $i);
							$driver_logs_target = $api->get_driver_target_by_week($driver_id,'R','A','1',$target_date);
							//echo count($driver_logs_target);echo '<br>';
							if(count($driver_logs_target) > 0)
							{
								foreach($driver_logs_target as $result)
								{
									//number_format($flagger_ride, 2, '.', '')
									//echo $result['ach_amt'];
									if($result['ach_amt'] != null)
									{
										$ach_amt .= number_format($result['ach_amt'], 2, '.', '').',';
									}
									else
									{
										$ach_amt .=  '0.00,';
									}
									if($result['target_amount']!= null)
									{
										$target_amount .= number_format($result['target_amount'], 2, '.', '').',';
									}
									else
									{
										$target_amount .= '0.00,';
									}
								}
							}
							else
							{
								$ach_amt .=  '0.00,';
								$target_amount .= '0.00,';
							}							
						}
						//echo $total_dates;
						$total_dates = substr($total_dates,0,strlen($total_dates)-1);
						$selected_day = substr($selected_day,0,strlen($selected_day)-1);
						$ach_amt = substr($ach_amt,0,strlen($ach_amt)-1);
						$target_amount = substr($target_amount,0,strlen($target_amount)-1);
						$total_array = array("dates" => $total_dates,"days" =>$selected_day,"ach_amt" =>  $ach_amt ,"target_amount" => $target_amount);
						if(count($total_array) == 0)
									$message = array("message" => __('no_data'),"status"=>0);	
								else
									$message = array("message" =>__('success'),"detail"=>$total_array,"status" => 1);
					}
					else
					{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"status"=>-3,"detail"=>$validation_error);				
					}	
			}
			else
			{
					$message = array("message" => __('invalid_user'),"status"=>-1);	
			}
			echo json_encode($message);
			break;
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=get_target_by_month&driver_id=16&month=12&year=2013
			case 'get_target_by_month':
				$array = $_GET;		
				//print_r($array);		
				if($array['driver_id'] != null)
				{
					$month = $array['month'];
					$year = $array['year'];
					
					$fromdate = $year.'-'.$month.'-'.'01';
					$todate = date('Y-m-t', strtotime($fromdate));
					$driver_id = $array['driver_id'];
					$validator = $this->get_target_validationby_month($array);
					if($validator->check())
					{	
						$total_weeks = '';
						$ach_amt = '';
						$target_amount = '';
						$j=1;
						for($i = strtotime($fromdate); $i <= strtotime($todate); $i = strtotime('+1 Week', $i))
						{
							//Get week values
							$total_weeks .= 'W'.$j.',';
							/*$week =  date('W', $i);
							$year =  date('Y', $i);
							$from = date("Y-m-d", strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week
							$to = date("Y-m-d", strtotime("{$year}-W{$week}-7"));   //Returns the date of sunday in week */
							
							$from = date("Y-m-d", strtotime('+0 day', $i));
							$m = date("m", strtotime('+6 day', $i));
							if($m != $month)
							{
								$to = date('Y-m-t', strtotime($fromdate));
							}
							else
							{
								$to = date("Y-m-d", strtotime('+6 day', $i));
							}
							
							//Get the taget amount values
							$month = date('m', $i);
							$year = date('Y', $i);
							$driver_logs_target = $api->get_driver_target_by_month($driver_id,'R','A','1',$from,$to);
							/*if(count($driver_logs_target) > 0)
							{
								foreach($driver_logs_target as $result)
								{
									if($result['ach_amt'] != null)
									{
										$ach_amt .= $result['ach_amt'].',';
									}
									else
									{
										$ach_amt .=  '0,';
									}
									if($result['target_amount']!= null)
									{
										$target_amount .= $result['target_amount'].',';
									}
									else
									{
										$target_amount .= '0,';
									}
								}
							}
							else
							{
								$ach_amt .=  '0,';
								$target_amount .= '0,';
							} */
							$ach_amt .= number_format($driver_logs_target['ach_amt'], 2, '.', '').',';
							$target_amount .= number_format($driver_logs_target['target_amount'], 2, '.', '').',';
							$j++;
						} 
						$total_weeks = substr($total_weeks,0,strlen($total_weeks)-1);
						$ach_amt = substr($ach_amt,0,strlen($ach_amt)-1);
						$target_amount = substr($target_amount,0,strlen($target_amount)-1);
						$total_array = array("Weeks" => $total_weeks,"ach_amt" =>  $ach_amt ,"target_amount" => $target_amount);
						if(count($total_array) == 0)
									$message = array("message" => __('no_data'),"status"=>0);	
								else
									$message = array("message" =>__('success'),"detail"=>$total_array,"status" => 1);
					}
					else
					{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"status"=>-3,"detail"=>$validation_error);				
					}	
			}
			else
			{
					$message = array("message" => __('invalid_user'),"status"=>-1);	
			}
			echo json_encode($message);
			break;
			/* Driver Target by year */
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=get_target_by_year&driver_id=16&year=2013
			case 'get_target_by_year':
				$array = $_GET;		
				//print_r($array);		
				if($array['driver_id'] != null)
				{
					$year = $array['year'];
					$fromdate = $year.'-01-'.'01';
					$todate = $year.'-12-'.'31';
					$driver_id = $array['driver_id'];
					$validator = $this->get_target_validationby_year($array);
					if($validator->check())
					{	
						$ach_amt = '';
						$target_amount = '';
						for($i = strtotime($fromdate); $i <= strtotime($todate); $i = strtotime('+1 Month', $i))
						{
							$from = date('Y-m-01',$i); 
							$to  = date('Y-m-t',$i);
							//Get the taget amount values
							$driver_logs_target = $api->get_driver_target_by_month($driver_id,'R','A','1',$from,$to);
							/*if(count($driver_logs_target) > 0)
							{
								foreach($driver_logs_target as $result)
								{
									if($result['ach_amt'] != null)
									{
										$ach_amt .= $result['ach_amt'].',';
									}
									else
									{
										$ach_amt .=  '0,';
									}
									if($result['target_amount']!= null)
									{
										$target_amount .= $result['target_amount'].',';
									}
									else
									{
										$target_amount .= '0,';
									}
								}
							}
							else
							{
								$ach_amt .=  '0,';
								$target_amount .= '0,';
							} */
							$ach_amt .= number_format($driver_logs_target['ach_amt'], 2, '.', '').',';
							$target_amount .= number_format($driver_logs_target['target_amount'], 2, '.', '').',';
						}
						$total_months = "J,F,M,A,M,J,J,A,S,O,N,D";
						$ach_amt = substr($ach_amt,0,strlen($ach_amt)-1);
						$target_amount = substr($target_amount,0,strlen($target_amount)-1);
						//$ach_amt = "0,135.95999622345,0,0,0,0,0,0,0,0,0,0";
						//$target_amount = "0,0,0,0,0,0,0,0,0,0,0,0";
						$total_array = array("Months" => $total_months,"ach_amt" =>  $ach_amt ,"target_amount" => $target_amount);
						if(count($total_array) == 0)
									$message = array("message" => __('no_data'),"status"=>0);	
								else
									$message = array("message" =>__('success'),"detail"=>$total_array,"status" => 1);
					}
					else
					{
						$validation_error = $validator->errors('errors');	
						$message = array("message" => __('validation_error'),"status"=>-3,"detail"=>$validation_error);				
					}	
			}
			else
			{
					$message = array("message" => __('invalid_user'),"status"=>-1);	
			}
			echo json_encode($message);
			break;
			/* Driver Target by year */
			case 'ongoing_journey_driver':
			$array = $_GET;			
					if($array['driver_id'] != null)
					{
						$check_result = $api->check_driver_companydetails($array['driver_id'],$default_companyid);
						if($check_result == 0)	
						{
							$message = array("message" => __('invalid_user'),"status"=>-1);
							echo json_encode($message);
							exit;
						}
						$driver_id = $array['driver_id'];						
						$driver_model = Model::factory('driver');			
						$current_driver_status = $api->get_driver_current_status($array['driver_id']);
						//print_r($current_driver_status);
						if($current_driver_status[0]->status != 'A')
						{
							//Getting In Progress Data
							$driver_logs_progress = $api->get_driver_current_trip($driver_id,'R','A','9',$default_companyid);																						
							foreach($driver_logs_progress as $journey)
							{
								$inprogress_journey[] = (array) $journey;						   
							}							
							
							if(count($driver_logs_progress) == 0)
								$message = array("message" => __('no_data'),"status"=>0);	
							else
								$message = array("message" => __('success'),"detail"=>$inprogress_journey,"status"=>1);	
						}
						else
						{
							$driver_logs_progress = $api->get_driver_current_trip($driver_id,'R','A','2',$default_companyid);
							//echo count($driver_logs_progress);
							//exit;
							foreach($driver_logs_progress as $journey)
							{
							$inprogress_journey[] = (array)$journey;						   
							}				
							if(count($driver_logs_progress) != 0)
							$message = array("message" => __('driver_in_trip'),"detail"=>$inprogress_journey,"status"=>-1);
							else
							$message = array("message" => __('driver_in_trip'),"status"=>-1);
						}						
					}
					else
					{
						$message = array("message" => __('invalid_user_driver'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
						
					
			//URL : api/?type=assignedtaxi_list&driver_id=7
			case 'assignedtaxi_list':

					$check_result = $api->check_driver_companydetails($array['driver_id'],$default_companyid);
					if($check_result == 0)	
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);
						echo json_encode($message);
						exit;;
					}
			
					if($array['driver_id'] != null)
					{
						$driver_id = $array['driver_id'];
						
						$api_model = Model::factory('api');								
						$assignedtaxi_list = $api_model->get_assignedtaxi_alllist($driver_id,$default_companyid);	
						
						
						if(count($assignedtaxi_list) == 0)
							$message = array("message" => __('no_data'),"status"=>0);	
						else
							$message = array("message" =>__('success'),"detail"=>$assignedtaxi_list,"status"=>1);								
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
					
			//URL : api/?type=get_driver_comments&driver_id=7
			case 'get_driver_comments':
			
					if($array['driver_id'] != null)
					{
						$driver_id = $array['driver_id'];																		
						$driver_comments = $api->get_driver_comments($driver_id,'',$default_companyid);						
																
						if(count($driver_comments) == 0)
							$message = array("message" => __('no_data'),"status"=>0);	
						else
							$message = $driver_comments;			
						
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);	
					}
					echo json_encode($message);
					break;
			//Profiles Image Upload
			
			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=driver_statistics&driver_id=7
			case 'driver_statistics':
			$driver_model = Model::factory('driver');
					if($array['driver_id'] != null)
					{
						$check_driver_login_status = $this->is_login_status($array['driver_id'],$default_companyid);
						if($check_driver_login_status == 1)
						{
							$driver_id = $array['driver_id'];																		
							$driver_details = $api->driver_profile($driver_id);
							$driver_logs_new = $api->get_driver_logs($driver_id,'U','','9',$default_companyid);
							//$driver_logs_rejected = $api->get_driver_logs_completed($driver_id,'R','R','10',$default_companyid);	
							$driver_logs_rejected = $api->get_rejected_drivers($driver_id,$default_companyid);	
							//$driver_logs_completed = $driver_model->get_driver_logs_completed($driver_id,'R','A','1');	
							$driver_comments = $api->get_driver_comments($driver_id,$default_companyid);	
							$driver_today = $api->get_driver_comments($driver_id,1,$default_companyid);						
							$today_new_trips = count($driver_logs_new);	
							$rejected_trips = count($driver_logs_rejected);	
							$driver_earnings = $driver_model->get_driver_earnings($driver_id);		
							$goal_detail = $api->get_goal_details($driver_id,'R','A','1');
							$today_goal = $amount_left = $today_earnings = 0;

							//exit;

								$statistics = array();
								$total_trip = $trip_total_with_rate = $total_ratings = 0;
								foreach($driver_comments as $stat){
									$total_trip++;
									$total_ratings += $stat['rating'];
									if($stat['rating'] != 0)
										$trip_total_with_rate++;
								}
								
								/*$today_transaction = $trip_today_with_rate = $today_trip = $today_rating = 0;
								foreach($driver_today as $stat){
									$today_trip++;
									if($stat['rating']!= 0){$today_rating += $stat['rating'];}
									if($stat['rating'] != 0)
										$trip_today_with_rate++;
									$today_transaction	+= $stat['fare'];
								}
								if($trip_today_with_rate != 0)
								$today_rating /= $trip_today_with_rate;
								
								if($trip_total_with_rate!=0 && $total_ratings!=0){
									$totalrating = $total_ratings/$trip_total_with_rate;
								}else{
									$totalrating = 0;
								}*/
								$time_driven = $api->get_time_driven($driver_id,'R','A','1');
								if(count($driver_details) > 0)
								{
									$drivername = ucfirst($driver_details[0]['name']).' '.ucfirst($driver_details[0]['lastname']);
									$notification_setting = $driver_details[0]['notification_setting'];
									$statistics = array( 
												"drivername"=>$drivername,
												"total_trip" => $total_trip,
												//"total_ratings" => $totalrating,
												"total_ratings" => '',
												"total_earnings" => $driver_earnings[0]['total_amount'],
												//"today_completed_trip" => $today_trip,
												"today_completed_trip" => '',
												//"today_transaction" => $today_transaction,
												"today_transaction" => '',
												//"today_rating" => $today_rating,
												"today_rating" => '',
												//"today_new_trip_request" => $today_new_trips,
												"today_new_trip_request" => '',
												"overall_rejected_trips" => $rejected_trips,
												//"today_goal"=>$today_goal,
												"today_goal"=>'',
												"today_earnings"=>round($today_earnings,2),											
												"amount_left"=>round($amount_left,2),
												"shift_status"=>'IN',
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
			//URL : http://192.168.1.88:1234/api/index/dGF4aV9YRlJJb1p0NjdxYTU5ZmlIRFl1OGJPQ0J2elRHQVYxZmY=?type=driver_break_status&driver_id=60&interval_type=B&reason=Go for lunch&breakstatus=IN&update_id=
			case 'driver_break_status':
			$array = $_POST;
			$driver_model = Model::factory('driver');
			$Commonmodel = Model::factory('Commonmodel');			

			$check_result = $api->check_driver_companydetails($array['driver_id'],$default_companyid);
			if($check_result == 0)	
			{
				$message = array("message" => __('invalid_user'),"status"=>-1);
				echo json_encode($message);
				exit;;
			}


			$driver_id = $array['driver_id'];
			$interval_type = strtoupper($array['interval_type']);
			if($array['driver_id'] != null)
			{			
			$current_driver_status = $api->get_driver_current_status($array['driver_id'],$default_companyid);
			//print_r($current_driver_status);
				if($current_driver_status[0]->status != 'A')
				{
					$breakstatus = $array['breakstatus'];
					if($array['breakstatus'] == 'IN')
					{
						$taxi_id = "";
						$getTaxiforDriver = $api->getTaxiforDriver($driver_id,$default_companyid);	

						if(count($getTaxiforDriver) > 0 )
						{
							$taxi_id = $getTaxiforDriver[0]['mapping_taxiid'];
						}			
				
						$driver_reply = $api->update_driver_break_status($driver_id,$breakstatus,$interval_type);
						
						$company_all_currenttimestamp = $Commonmodel->getcompany_all_currenttimestamp($default_companyid);
						//echo $company_all_currenttimestamp;
						$insert_array = array(
												"driver_id" => $driver_id,
												"taxi_id" 			=> $taxi_id,
												"interval_type" 	=> $interval_type,
												"interval_start" 	=> $company_all_currenttimestamp,
												"interval_end"		=> "",
												"reason"		=> $array['reason'],
												"createdate"		=> $this->currentdate,
											);								
						//Inserting to Transaction Table 
						$transaction = $Commonmodel->insert(DRIVERBREAKSERVICE,$insert_array);	
						//print_r($transaction);		
						$insert_id = mysql_insert_id();
						if($transaction)
						{
							if($interval_type == 'B')
							{
								$message = array("message" => __('driver_in_break'),"status"=>1,"update_id"=>$insert_id);
							}
							else
							{
								$message = array("message" => __('driver_in_service'),"status"=>1,"update_id"=>$insert_id);
							}
						}
						else
						{
							$message = array("message" => __('try_again'),"status"=>-1);
						}
					}
					else
					{
						$update_id = $array['update_id'];
						$company_all_currenttimestamp = $Commonmodel->getcompany_all_currenttimestamp($default_companyid);
						$update_arrary  = array("interval_end" => $company_all_currenttimestamp);
						//print_r($update_arrary);
						
						$driver_reply = $api->update_driver_break_status($driver_id,$breakstatus,$interval_type);
						$transaction = $Commonmodel->update(DRIVERBREAKSERVICE,$update_arrary,'driver_break_service_id',$update_id);
						if($transaction)
						{
								$message = array("message" => __('driver_back_drive'),"status"=>1);
						}
						else
						{
							$message = array("message" => __('try_again'),"status"=>-1);
						}
					}
				}
				else
				{
					$message = array("message" => __('driver_in_trip'),"status"=>-1);
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
			$array = $_GET;
			$driver_model = Model::factory('driver');
			$Commonmodel = Model::factory('Commonmodel');	
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
						$getTaxiassignedforDriver = $api->get_assignedtaxi_list($driver_id,$default_companyid);			
						//echo count($getTaxiassignedforDriver);	
						$current_driver_status = $api->get_driver_current_status($array['driver_id'],$default_companyid);				

							$shiftstatus = $array['shiftstatus'];
								if($array['shiftstatus'] == 'IN')
								{
									if(count($getTaxiassignedforDriver)>0)
									{	
											$taxi_id = "";
											$getTaxiforDriver = $api->getTaxiforDriver($driver_id,$default_companyid);	
											if(count($getTaxiforDriver) > 0 )
											{
											$taxi_id = $getTaxiforDriver[0]['mapping_taxiid'];														
											$company_all_currenttimestamp = $Commonmodel->getcompany_all_currenttimestamp($default_companyid);
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
											$transaction = $Commonmodel->insert(DRIVERSHIFTSERVICE,$insert_array);	
											//print_r($transaction);		
											$insert_id = mysql_insert_id();
											if($transaction)
											{
													$detail = array("update_id"=>$insert_id);
													$message = array("message" => __('driver_shift'),"status"=>1,"detail"=>$detail);
											}
											else
											{
												$message = array("message" => __('try_again'),"status"=>-2);
											}
										   }	
										   else
										   {
												$message = array("message" => __('taxi_not_assigned'),"status"=>-3);
												//exit;				   
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
											$update_id = $array['update_id'];
											$company_all_currenttimestamp = $Commonmodel->getcompany_all_currenttimestamp($default_companyid);
											$update_arrary  = array("shift_end" => $company_all_currenttimestamp);
											if($update_id != "")
											{
												$transaction = $Commonmodel->update(DRIVERSHIFTSERVICE,$update_arrary,'driver_shift_id',$update_id);
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
										$message = array("message" => __('driver_in_trip'),"status"=>-1);
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
						
		//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=complete_trip&trip_id=574&drop_latitude=11.7625134&drop_longitude=76.1235648&drop_location=vadavalli,coimbatoe&distance=6&actual_distance=&waiting_time=1.5
		case 'complete_trip':
		$array = $_POST;
		//print_r($array);exit;
		/* array(
		"passenger_log_id" => 457,
		"drop_latitude" => 11.0214907,
		"drop_longitude" => 76.9166205,
		"drop_location" => "gandhipuram,coimbatore");*/
		if(!empty($array))
		{    
			$driver_model = Model::factory('driver');
			$Commonmodel = Model::factory('Commonmodel');	
			$drop_latitude = $array['drop_latitude'];
			$drop_longitude = $array['drop_longitude'];
			$drop_location = $array['drop_location'];
			$trip_id = $array['trip_id'];
			$distance = $array['distance'];
			$actual_distance = $array['actual_distance'];
			$waiting_hours = $array['waiting_hour'];
			/*if($actual_distance == "")
				$total_distance = $distance;
			else
				$total_distance = $actual_distance;						
			//*/
			if($trip_id != null )
			{   
				$gateway_details = $Commonmodel->gateway_details();	
				$get_passenger_log_details = $api->get_passenger_log_detail($trip_id);		
				//print_r($get_passenger_log_details);exit;
				$p_referral_discount = 0;
				$pickupdrop = $taxi_id = $company_id = 0;
				$fare_per_hour = $waiting_per_hour = $total_fare = $nightfare = 0;
				if(count($get_passenger_log_details) > 0)
				{
							/******* Check whether the trip is completed if so we change the driver status and trip travel status and give response **********/
						$flag = 0;
						$trans_result = $api->check_tranc($trip_id,$flag);
						if(count($trans_result)>0)
						{       
								$travel_status=$trans_result[0]['travel_status'];
								//exit;
								$transid=$trans_result[0]['id'];
								$driver_id=$trans_result[0]['driver_id'];
								if($travel_status == 1 && $transid != "")
								{									
									/********** Update Driver Status after complete Payments *****************/									
									$update_driver_arrary = array("status" => 'F');
									$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_id);	
									/************Update Driver Status ***************************************/				
									$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
									$journey = $api->update_journey_status($trip_id,$msg_status,$driver_reply,$journey_status);
									/**************************************************************************/
									$message = array("message" => __('trip_fare_already_updated'), "status"=>-1);
									echo json_encode($message);
									break;
								}
								if($travel_status == 5 && $transid != "")
								{
									/********** Update Driver Status after complete Payments *****************/									
									$update_driver_arrary = array("status" => 'F');
									$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_id);	
									/************Update Driver Status ***************************************/				
									$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
									$journey = $api->update_journey_status($trip_id,$msg_status,$driver_reply,$journey_status);
									/**************************************************************************/	
									$message = array("message" => __('trip_fare_and_status_updated'), "status"=>-1);
									echo json_encode($message);
									break;																	
								}

						}
						else
						{
							$gateway_details = $Commonmodel->gateway_details();	
							$get_passenger_log_details = $api->get_passenger_log_detail($trip_id);		
							$passenger_discount = $get_passenger_log_details[0]->passenger_discount; // 
							//print_r($get_passenger_log_details);exit;
							$passengers_id = $get_passenger_log_details[0]->passengers_id;
							$referred_by = $get_passenger_log_details[0]->referred_by;									
							///$referral_earned_amount = $get_passenger_log_details[0]->referral_earned_amount;
							$referrer_earned = $get_passenger_log_details[0]->referrer_earned;
							$company_tax = $get_passenger_log_details[0]->company_tax;
							$travel_status = $get_passenger_log_details[0]->travel_status;
							$total_distance = $get_passenger_log_details[0]->distance;
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
								$company_id = $get_passenger_log_details[0]->company_id;
								$driver_id = $get_passenger_log_details[0]->driver_id;
								$approx_distance = $get_passenger_log_details[0]->approx_distance;
								$approx_fare = $get_passenger_log_details[0]->approx_fare;
								$passengers_id = $get_passenger_log_details[0]->passengers_id;
								$referred_by = $get_passenger_log_details[0]->referred_by;			
								
								$company_id = $get_passenger_log_details[0]->company_id;
								$taxi_details = $api->get_taxi_model_details($taxi_id);
								$taxi_model_id = $taxi_details[0]['taxi_model'];	
											
								$taxi_fare_details = $api->get_model_fare_details($company_id,$taxi_model_id,$get_passenger_log_details[0]->search_city);
								//print_r($taxi_fare_details);exit;
								$drop_time = $Commonmodel->getcompany_all_currenttimestamp($default_companyid);
								$msg_status = 'R';$driver_reply='A';$journey_status=5; // Waiting for Payment
								
								$journey = $api->update_journey_statuswith_drop($trip_id,$msg_status,$driver_reply,$journey_status,$drop_latitude,$drop_longitude,$drop_location,$drop_time,$total_distance,$waiting_hours);
								
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
								$waiting_per_hour = '0';
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
									$waiting_per_hour = $taxi_fare_details[0]['waiting_time'];
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
					
								if($total_distance < $min_km_range)
								{
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
								// Waiting Time calculation
								$waiting_cost = $waiting_per_hour * $waiting_hours;
								$total_fare = $waiting_cost + $total_fare;		
												
								$trip_fare = $total_fare;
								
								//Night Fare Calculation
								$nightfare_applicable=0;
								if ($night_charge != 0) 
								{
									if( $pickuptime >= $night_timing_from || $pickuptime <= $night_timing_to)
									{									
										$nightfare_applicable = 1;
										$nightfare = ($night_fare/100)*$total_fare;//night_charge%100;					
										$total_fare  = $nightfare + $total_fare;
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
								$referral_discount = $siteinfo_details[0]['referral_discount'];
								$referdiscount = 0;
								if($referred_by != "")
								{
									$check_reg_passenger_first_trip = $api->reg_passenger_first_trip($passengers_id);
									if($check_reg_passenger_first_trip ==1)
									{
										//Update referral_earned_amount in Passenger Table when registere complete first trip
										//$update_ref_discount = $api->update_ref_discount($referred_by,$referral_discount);
										// Calculate discount for registered user first trip
										$referdiscount = ($referral_discount/100)*$total_fare;
										//Update registered user get discount and it detail to  passengers_referral_list when he complete first trip
										//$update_registerer_discount = $api->update_registerer_discount($passengers_id,$trip_id,$referral_discount);
										//$total_fare = $total_fare-$referdiscount;									
									}						
								}
								//if(($referral_earned_amount >=$referral_discount) && ($referrer_earned != 1))
								if($referrer_earned != 1)
								{
									// Calculate discount for referred user if he have earned discount and not used previously
									//$referdiscount = ($referral_discount/100)*$total_fare;
									//$update_referer_earn_status = $api->update_referer_earn_status($passengers_id);
								}
								// Company Tax amount Calculation							
								$tax_amount = "";
								if($company_tax > 0)
								{
										$tax_amount = ($company_tax/100)*$total_fare;//night_charge%100;							
										$total_fare =  $total_fare+$tax_amount;
								}														
								$trip_fare = round($trip_fare,2);
								$total_fare = round($total_fare,2);
								$referdiscount = 0;//round($referdiscount,2);
								$discount_fare = round($discount_fare,2);
								$tax_amount = round($tax_amount,2);
								$nightfare = round($nightfare,2);
								
								if(SMS ==1)
								{
									$passenger_phone_no=$api->get_passenger_phone_by_id($passengers_id);
									$common_model = Model::factory('commonmodel');	
									$message_details = $common_model->sms_message_by_title('complete_trip');
									$to = $passenger_phone_no;
									$message = $message_details[0]['sms_description'];
									$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
									//$api->send_sms($to,$message);
								}
								/** Update Driver Status End**/		
								//
								$detail = array("trip_id" => $trip_id,"pass_id"=>$passengers_id,"distance"=>$total_distance,"trip_fare"=>$trip_fare,"referdiscount"=>$referdiscount,"passenger_discount"=>$discount_fare,"nightfare_applicable"=>$nightfare_applicable,"nightfare"=>$nightfare,"waiting_time"=>$waiting_hours,"waiting_cost"=>$waiting_cost,"tax_amount"=>$tax_amount,"total_fare"=>$total_fare,"gateway_details"=>$gateway_details,"pickup"=>$pickup,"drop"=>$drop,"company_tax"=>$company_tax,"waiting_per_hour" => $waiting_per_hour, "roundtrip"=> $roundtrip,"min_km_range"=>$min_km_range,"below_above_km_range"=>$below_above_km_range,"base_fare"=>$base_fare,"min_fare"=>$min_fare,"below_km"=>$below_km,"above_km"=>$above_km);
											
								$message = array("message"=>__('trip_completed_driver'),"detail"=>$detail,"status"=>4);
								//print_r($pushmessage);
								//exit;
								/** Send Trip fare details to Driver ***/
								$d_device_token = $get_passenger_log_details[0]->driver_device_token;
								$d_device_type = $get_passenger_log_details[0]->driver_device_type;
								//$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);	
								/** Send Trip fare details to Passenger ***/
								$pushmessage = array("message"=>__('trip_completed'),"status"=>4);
								$p_device_token = $get_passenger_log_details[0]->passenger_device_token;
								$p_device_type = $get_passenger_log_details[0]->passenger_device_type;

								$p_send_notification = $api->send_passenger_mobile_pushnotification($p_device_token,$p_device_type,$pushmessage,$this->customer_google_api);						
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
			//http://192.168.1.88:1003/api/index/dGF4aV9hbGw=?type=tripfare_update&trip_id=7&distance=25&actual_distance=&actual_amount=85&trip_fare=&fare=90&tips=0.50&passenger_discount=remarks=test%20driviing&nightfare_applicable=&remarks=&tax_amount=100&waiting_time=1.6&waiting_cost=0.333&nightfare=&creditcard_no=4024007155409633&creditcard_cvv=567&expmonth=12&expyear=2031&pay_mod_id=1

			//Cash :
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=tripfare_update&trip_id=806&distance=25&actual_distance=&actual_amount=&trip_fare=120&fare=180.50&tips=10.50&passenger_discount=10&tax_amount=20&remarks=test%20driviing&nightfare_applicable=1&nightfare=20&waiting_time=1.6&waiting_cost=0.50&creditcard_no=&creditcard_cvv=&expmonth=&expyear=&pay_mod_id=1

			//Card :
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=tripfare_update&trip_id=709&distance=25&actual_distance=&actual_amount=&trip_fare=&fare=90&fare=90&tips=0.50&passenger_discount=&tax_amount=&remarks=test%20driviing&nightfare_applicable=&nightfare=&waiting_time=1.6&waiting_cost=0.333&creditcard_no=4024007155409633&creditcard_cvv=567&expmonth=12&expyear=2031&pay_mod_id=2

			//Uncard :
			//http://192.168.1.88:1000/api/index/dGF4aV9hbGw=/?type=tripfare_update&trip_id=990&distance=25&actual_distance=&actual_amount=&trip_fare=&fare=90&fare=90&tips=0.50&passenger_discount=&tax_amount=&remarks=test%20driviing&nightfare_applicable=&nightfare=&waiting_time=1.6&waiting_cost=0.333&creditcard_no=4024007155409633&creditcard_cvv=567&expmonth=12&expyear=2031&pay_mod_id=3
			
			//Account :
			//http://192.168.1.73:1013/api/index/bnRheGlfYlVtUzZGMUJMVDY4VTZtWkdYaDNnRFV2WE5BRGo0==/?type=tripfare_update&trip_id=58&distance=25&actual_distance=&actual_amount=&trip_fare=&fare=90&fare=90&tips=0.50&passenger_discount=&tax_amount=&remarks=test%20driviing&nightfare_applicable=&nightfare=&waiting_time=1.6&waiting_cost=0.333&creditcard_no=4024007155409633&creditcard_cvv=567&expmonth=12&expyear=2031&pay_mod_id=4&group_id=&account_id=
			case 'tripfare_update':
			$array = $_GET;						
			$driver_model = Model::factory('driver');
			$Commonmodel = Model::factory('Commonmodel');	
			$api_model = Model::factory('api');	
			$pay_mod_id = $array['pay_mod_id'];
			if($pay_mod_id == '1' ||  $pay_mod_id == '2' ||  $pay_mod_id == '4')
			{
				$validator = $this->payment_validation($array);
			}
			else
			{
				$validator = $this->payment_validationwith_card($array);	
			}
		
			if($validator->check())
			{					
				$passenger_log_id = $array['trip_id'];
				if($array['actual_distance'] == "")
					$distance = $array['distance'];
				else
					$distance = $array['actual_distance'];				
				$actual_amount = $array['actual_amount'];
				$remarks = $array['remarks'];
				$trip_fare = $array['trip_fare']; // Trip Fare without Tax,Tips and Discounts
				$fare = round($array['fare'],2); // Total Fare with Tax,Tips and Discounts can editable by driver
				$tips = round($array['tips'],2); // Tips Optional
				$nightfare_applicable = $array['nightfare_applicable'];
				$nightfare = $array['nightfare'];	
				$tax_amount = $array['tax_amount'];
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
				if(count($get_passenger_log_details) > 0)
				{
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
						/**************************************************************************/
						$message = array("message" => __('trip_fare_already_updated'), "status"=>-1);
						echo json_encode($message);
						break;

					}	
					if($array['pay_mod_id'] == 1)
					{																				
							//Inserting to Transaction Table 
							try {
								//$passenger_log_id = $passenger_log_id;
								//$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
								//$journey = $driver_model->update_journey_status($passenger_log_id,$msg_status,$driver_reply,$journey_status);
								$passenger_log_id = $passenger_log_id;
								$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
								$journey = $driver_model->update_journey_status($passenger_log_id,$msg_status,$driver_reply,$journey_status);
								$siteinfo_details = $api_model->siteinfo_details(); 
								$update_commission = $Commonmodel->update_commission($passenger_log_id,$total_fare,$siteinfo_details[0]['admin_commission']);								
								$siteinfo_details = $api_model->siteinfo_details(); 
								
								$insert_array = array(
									"passengers_log_id" => $passenger_log_id,
									"distance" 			=> $array['distance'],
									"actual_distance" 	=> $array['actual_distance'],
									"tripfare"			=> $trip_fare,
									"fare" 				=> $fare,
									"tips" 				=> $tips,
									"waiting_cost"		=> $array['waiting_cost'],
									"passenger_discount"=> $array['passenger_discount'],
									"company_tax"		=> $tax_amount,
									"waiting_time"		=> $array['waiting_time'],
									"remarks"		=> $remarks,
									"payment_type"		=> $array['pay_mod_id'],
									"amt"			=> $amount,
									"nightfare_applicable" => $nightfare_applicable,
									"nightfare" 		=> $nightfare,
									"admin_amount"		=> $update_commission['admin_commission'],
									"company_amount"	=> $update_commission['company_commission'],
									"trans_packtype"	=> $update_commission['trans_packtype']
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
									$transaction = $Commonmodel->insert(TRANS,$insert_array);		
									$jobreferral = mysql_insert_id();									
								}
		
								$update_commission = $Commonmodel->update_commission($passenger_log_id,$total_fare,$siteinfo_details[0]['admin_commission']);
								/********** Update Driver Status after complete Payments *****************/
								$drivers_id = $get_passenger_log_details[0]->driver_id;
								$update_driver_arrary = array("status" => 'F');
								$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$drivers_id);	
								/************Update Driver Status ***************************************/				
								$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
								$journey = $api->update_journey_status($passenger_log_id,$msg_status,$driver_reply,$journey_status);
								/**************************************************************************/
								$pickup = $get_passenger_log_details[0]->current_location;			
								
								if(SMS == 1)
								{
									$passenger_phone_no =$get_passenger_log_details[0]->phone;
									$common_model = Model::factory('commonmodel');	
									$message_details = $common_model->sms_message_by_title('payment_confirmed_sms');
									$to = $passenger_phone_no;
									$message = $message_details[0]['sms_description'];
									//$message = str_replace("##booking_key##",SITE_NAME,$message);
									$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
									$api->send_sms($to,$message);
								}
								
								$detail = array("fare" => $amount,"pickup" => $pickup,"jobreferral"=>$jobreferral);			
								$message = array("message" => __('trip_fare_updated'),"detail"=>$detail,"status"=>1);		
								$pushmessage = array("message" => __('trip_fare_updated'),"fare" => $amount,"trip_id"=>$passenger_log_id,"pickup" => $pickup, "status"=>5);
								//print_r($pushmessage);
								//exit;
								
								/** Send Trip fare details to Driver ***/
								$d_device_token = $get_passenger_log_details[0]->driver_device_token;
								$d_device_type = $get_passenger_log_details[0]->driver_device_type;
								//$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);	
								/** Send Trip fare details to Passenger ***/
								$p_device_token = $get_passenger_log_details[0]->passenger_device_token;
								$p_device_type = $get_passenger_log_details[0]->passenger_device_type;
								$p_send_notification = $api->send_passenger_mobile_pushnotification($p_device_token,$p_device_type,$pushmessage,$this->customer_google_api);						
								//$message = $pushmessage;	
								$send_mail_status = $this->send_mail_passenger($passenger_log_id,1);								
							}
								catch (Kohana_Exception $e) {
								//	print_r($e);
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
						//echo count($carddetails);exit;
						 if(count($carddetails)>0)
						 {
							$creditcard_no = encrypt_decrypt('decrypt',$carddetails[0]['creditcard_no']);										
							$creditcard_cvv = $carddetails[0]['creditcard_cvv'];
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
										$detail = array("fare" => $amount,"pickup" => $pickup,"jobreferral"=>$jobreferral);
										$message = array("message" => __('trip_fare_updated'), "detail" => $detail,"status"=>1);	
										$pushmessage = array("message" => __('trip_fare_updated'),"fare" => $amount,"trip_id"=>$passenger_log_id,"pickup" => $pickup, "status"=>5);
										/** Send Trip fare details to Driver ***/
										$d_device_token = $get_passenger_log_details[0]->driver_device_token;
										$d_device_type = $get_passenger_log_details[0]->driver_device_type;
										//$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);	
										/** Send Trip fare details to Passenger ***/
										$p_device_token = $get_passenger_log_details[0]->passenger_device_token;
										$p_device_type = $get_passenger_log_details[0]->passenger_device_type;
										$p_send_notification = $api->send_passenger_mobile_pushnotification($p_device_token,$p_device_type,$pushmessage,$this->customer_google_api);
										$send_mail_status = $this->send_mail_passenger($passenger_log_id,1);
								}
								else if($payment_status == -1)
								{
									$message = array("message" => __('invalid_trip'),"status"=>-1);	
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
								$detail = array("fare" => $amount,"pickup" => $pickup,"jobreferral"=>$jobreferral);
								$message = array("message" =>  __('trip_fare_updated'), "detail" => $detail,"status"=>1);	
								$pushmessage = array("message" => __('trip_fare_updated'),"fare" => $amount,"trip_id"=>$passenger_log_id,"pickup" => $pickup, "status"=>5);
								/** Send Trip fare details to Driver ***/
								$d_device_token = $get_passenger_log_details[0]->driver_device_token;
								$d_device_type = $get_passenger_log_details[0]->driver_device_type;
								//$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);	
								/** Send Trip fare details to Passenger ***/
								$p_device_token = $get_passenger_log_details[0]->passenger_device_token;
								$p_device_type = $get_passenger_log_details[0]->passenger_device_type;
								$p_send_notification = $api->send_passenger_mobile_pushnotification($p_device_token,$p_device_type,$pushmessage,$this->customer_google_api);	
								$send_mail_status = $this->send_mail_passenger($passenger_log_id,1);
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
									$get_passenger_profile = $api_model->passenger_profile($account_holder_id,$default_companyid);							
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
																	//$jobreferral = mysql_insert_id();
																	$jobreferral = $tranaction_id;
																	$detail = array("fare" => $amount,"pickup" => $pickup,"jobreferral"=>$jobreferral);
																	$message = array("message" =>  __('trip_fare_updated'), "detail" => $detail,"status"=>1);	
																	$pushmessage = array("message" => __('trip_fare_updated'),"trip_id"=>$passenger_log_id,"fare" => $amount,"pickup" => $pickup, "status"=>5);
																	/** Send Trip fare details to Driver ***/
																	$d_device_token = $get_passenger_log_details[0]->driver_device_token;
																	$d_device_type = $get_passenger_log_details[0]->driver_device_type;
																	//$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);	
																	/** Send Trip fare details to Passenger ***/
																	$p_device_token = $get_passenger_log_details[0]->passenger_device_token;
																	$p_device_type = $get_passenger_log_details[0]->passenger_device_type;
																	$p_send_notification = $api->send_passenger_mobile_pushnotification($p_device_token,$p_device_type,$pushmessage,$this->customer_google_api);	
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
			echo json_encode($message);
			break;			
			/*END OF DRIVER DETAILS*/

			//URL : http://192.168.1.88:1000/api/index/dGF4aV9hbGw=?type=cancel_trip&passenger_log_id=48&travel_status=4&remarks=test driviing&pay_mod_id=2

			case 'cancel_trip':
			
			$driver_model = Model::factory('driver');
			$Commonmodel = Model::factory('Commonmodel');			
			$api_model = Model::factory('api');			

			$passenger_log_id = $array['passenger_log_id'];
			$remarks = $array['remarks'];

			$check_travelstatus = $api_model->check_travelstatus($passenger_log_id);
			//echo $check_travelstatus;
			if($check_travelstatus == -1)
			{
				$message = array("message" => __('invalid_trip'),"status"=>2);
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

				if($array['passenger_log_id'] != null)
				{
				$get_passenger_log_det = $api_model->get_passenger_log_detail($passenger_log_id);
				//print_r($get_passenger_log_det);exit;
				$driver_id = $get_passenger_log_det[0]->driver_id;
				$passenger_id = $get_passenger_log_det[0]->passengers_id;
				$array['company_id'] = $get_passenger_log_det[0]->company_id;
				$cancellation_nfree = $get_passenger_log_det[0]->cancellation_nfree;
				
				$status = "F";
				$result = $api_model->update_driver_status($status,$driver_id);
				
				if($cancellation_nfree == 0)
				{
					 if(SMS == 1)
					{
						$phone_no=$api->get_driver_phone_by_id($driver_id);
						$common_model = Model::factory('commonmodel');								
						$message_details = $common_model->sms_message_by_title('trip_cancel');
						$to = $phone_no;
						$message = $message_details[0]['sms_description'];
						//$message = str_replace("##OTP##",$otp,$message);
						$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
						
						$api->send_sms($to,$message);
					}
					$transaction_detail=$api_model->cancel_triptransact_details($array,$cancellation_nfree);
						$pushmessage = array("message"=>__('trip_cancelled_passenger'), "status"=>2);
						$d_device_token = $get_passenger_log_det[0]->driver_device_token;
						$d_device_type = $get_passenger_log_det[0]->driver_device_type;
						$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);					
					$message = array("message" => __('trip_cancel_passenger'), "status"=>1);	//with out cancellation fee
				}
				else
				{


					if($array['pay_mod_id'] == 3) // By cash
					{
						$get_passenger_log_details = $driver_model->get_passenger_log_details($passenger_log_id);

						if(count($get_passenger_log_details) > 0)
						{						
							
							//Inserting to Transaction Table 
							try {
			
								$siteinfo_details = $api_model->siteinfo_details(); 

								$total = $api_model->get_passenger_cancel_faredetail($passenger_log_id); 
								$update_commission = $Commonmodel->update_commission($passenger_log_id,$total,$siteinfo_details[0]['admin_commission']);

							$insert_array = array(
									"passengers_log_id" => $passenger_log_id,
									"remarks"		=> $remarks,
									"payment_type"		=> $array['pay_mod_id'],
									"amt"			=> $total,
									"admin_amount"		=> $update_commission['admin_commission'],
									"company_amount"	=> $update_commission['company_commission'],
									"trans_packtype"	=> $update_commission['trans_packtype']
								);

								$transaction = $Commonmodel->insert(TRANS,$insert_array);
								
								if(SMS == 1)
					            {
								$phone_no=$api->get_driver_phone_by_id($driver_id);
								$common_model = Model::factory('commonmodel');								
								$message_details = $common_model->sms_message_by_title('trip_cancel');
								$to = $phone_no;
								$message = $message_details[0]['sms_description'];
								//$message = str_replace("##OTP##",$otp,$message);
								$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
								
								$api->send_sms($to,$message);
							    }
								
								$message = array("message" => __('trip_fare_updated'), "status"=>1);		
								
								$pushmessage = array("message"=>__('trip_cancelled_passenger'), "status"=>2);
								$d_device_token = $get_passenger_log_det[0]->driver_device_token;
								$d_device_type = $get_passenger_log_det[0]->driver_device_type;
								$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);
								$message = array("message" => $pushmessage,"status"=>1);									
								$send_mail_status = $this->send_mail_passenger($passenger_log_id,4);
							}
								catch (Kohana_Exception $e) {
								//	print_r($e);
								$message = array("message" => __('try_again'), "status"=>2);			
							}
						}
						else
						{
								$message = array("message" => __('invalid_trip'),"status"=>2);	
						}
					}
					else
					{
						$card_type = '';
						$default = 'yes';
						$carddetails = $api->get_creadit_card_details($passenger_id,$card_type,$default);									
						//echo count($carddetails);exit;
						 if(count($carddetails)>0)
						 {
							$payment_status = $this->cancel_trippayment($array,$cancellation_nfree);
							if($payment_status == 0)
							{
								$gateway_response = isset($_SESSION['paymentresponse']['L_LONGMESSAGE0'])?$_SESSION['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
								$message = array("message" => __('cancel_payment_failed'), "gateway_response" =>$gateway_response,"status"=>0);		
								echo json_encode($message);
								break;
							}				
							else if($payment_status == 1)
							{
								
							    if(SMS == 1)
					            {
								$phone_no=$api->get_driver_phone_by_id($driver_id);
								$common_model = Model::factory('commonmodel');								
								$message_details = $common_model->sms_message_by_title('trip_cancel');
								$to = $phone_no;
								$message = $message_details[0]['sms_description'];
								//$message = str_replace("##OTP##",$otp,$message);
								$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
								$api->send_sms($to,$message);
						     	}
						     	
								$message = array("message" => __('cancel_fare_update'),"status"=>1);
								echo json_encode($message);
								$pushmessage = array("message"=>__('trip_cancelled_passenger'), "status"=>2);
								$d_device_token = $get_passenger_log_det[0]->driver_device_token;
								$d_device_type = $get_passenger_log_det[0]->driver_device_type;
								$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);
								$message = array("message" => __('cancel_fare_update'),"status"=>1);									
								$send_mail_status = $this->send_mail_passenger($passenger_log_id,4);	
								break;						
							}
							else if($payment_status == -1)
							{
								$message = array("message" => __('invalid_trip'),"status"=>2);	
								echo json_encode($message);
								break;
							}
						}
						else
						{
								/*$status = "F";
								$result = $api_model->update_driver_status($status,$driver_id);
								$transaction_detail=$api_model->cancel_triptransact_details($array,$cancellation_nfree);
								$pushmessage = array("message"=>__('trip_cancelled_passenger'), "status"=>2);
								$d_device_token = $get_passenger_log_det[0]->driver_device_token;
								$d_device_type = $get_passenger_log_det[0]->driver_device_type;
								$d_send_notification = $api->send_driver_mobile_pushnotification($d_device_token,$d_device_type,$pushmessage,$this->driver_android_api);
								$message = array("message" => __('cancel_fare_update'),"status"=>1);									
								$send_mail_status = $this->send_mail_passenger($passenger_log_id,4);	*/
								$message = array("message" => __('cancel_no_creditcard'),"status"=>3);	
								echo json_encode($message);		
								break;				
						}
					}
				}
				}
				else
				{
					$message = array("message" => __('invalid_trip'),"status"=>2);	
					echo json_encode($message);
					break;
				}

				
				//echo json_encode($message);
			break;			

			/* start of Passenger Forgot Password */		
		//URL : api/?type=forgot_password&phone_no=9999999999&user_type=P
		case 'forgot_password':												

						if($_GET['user_type'] == 'P')
						{
							$phone_exist = $api->check_phone_passengers($_GET['phone_no'],$default_companyid);
						}
						else
						{
							$phone_exist = $api->check_phone_people($_GET['phone_no'],'D',$default_companyid);
						}	
						$forgotpassword_array = $_GET;

						if($phone_exist > 0)
						{

							//$random_key = text::random($type = 'alnum', $length = 6);
							//$result = $api->forgot_password($forgotpassword_array,$random_key);
							$result = $api->get_passenger_details_phone($forgotpassword_array,$default_companyid);
							$email = $result[0]['email'];
							if($result) 
							{ 					
				                $mail="";
								if($_GET['user_type'] == 'P') {


												$replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$result[0]['name'],REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,SITE_DESCRIPTION=>$this->app_description,RESET_LINK=>URL_BASE.'passengers/resetpassword/?phone_no='.$_GET['phone_no'].'&activation_key='.$result[0]['activation_key']);
								}
								else
								{
												$replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$result[0]['name'],REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,SITE_DESCRIPTION=>$this->app_description,RESET_LINK=>URL_BASE.'driver/resetpassword/?phone_no='.$_GET['phone_no']);
								}

								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'reset-forgotpassword.html',$replace_variables);

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

					//free sms url with the arguments
				/*	if(SMS == 1)
					{
					$common_model = Model::factory('commonmodel');	
					$passenger_details = $common_model->get_passengers_details();
					$message_details = $common_model->sms_message('2');
					$to = $result[0]['phone'];
					$message = $message_details[0]['sms_description'];
				        $message = str_replace("##PASSWORD##",$random_key,$message);
			
					$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
					}

				*/
					//  $mail=array($result[0]['email'],"from"=>$this->siteemail,"subject"=>__('forgot_password_subject')." - ".$this->app_name,"message"=>$message);
					//$emailstatus=$this->email_send($mail,'smtp');								
						

								$message = array("message" => __('forgot_pass_success'),'status' => 1);
							}
	
						 echo json_encode($message);   

						}
						else
						{
							$message = array("message" => __('invalid_user'),"status"=> 2);
							echo json_encode($message);     	
						}					
								

					break;
			
					//URL api/?type=taxi_features&taxi_id=3&driver_id=6&cityid=coimbatore
					case 'taxi_features':
					$array = $_GET;
						if(isset($array["driver_id"]))
						{							
							$common_model = Model::factory('Commonmodel');
							$result = $api->get_driver_details($array["driver_id"],$array["taxi_id"],$array["cityid"]);
							$driver_current_location = $api->get_driver_location($array["driver_id"]);
							//echo "<pre>";print_r($result);echo "</pre>";exit;
							$html = "";
							if(count($result) > 0)
							{
								if(isset($result[0]) && count($result[0]) > 0)
								{																		
									$taxi_image = $_SERVER['DOCUMENT_ROOT'].'/'.TAXI_IMG_IMGPATH.$result[0]['taxi_image'];
									if(file_exists($taxi_image) && $result[0]['taxi_image'] !='')
									{
									$taxi_image = URL_BASE.TAXI_IMG_IMGPATH.'tmb100_'.$result[0]['taxi_image'];
									}else{
									$taxi_image = URL_BASE."/public/images/noimages.jpg";
									}
									$taxicompany = ucfirst($result[0]['company_name']);
									$taxi_number = $result[0]['taxi_no'];
									$taxi_model = ucfirst($result[0]['model_name']);
									$taxi_capacity = $result[0]['taxi_capacity'];
									
									
									if($result[0]['cancellation_nfree'] == 0)
									{
										$cancellation_fare = $result[0]['cancellation_fare'];
									}
									else
									{
										$cancellation_fare = 'No';
									}

									$base_fare = $result[0]['base_fare'];
									$min_km_range = $result[0]['min_km'];
									$min_fare = $result[0]['min_fare'];
									$below_above_km_range = $result[0]['below_above_km'];
									$below_km = $result[0]['below_km'];
									$above_km = $result[0]['above_km'];
									$night_charge = $result[0]['night_charge'];
									$night_timing_to = $result[0]['night_timing_to'];
									$night_fare = $result[0]['night_fare'];
									$taxi_speed = $result[0]['taxi_speed'];

									
									//$taxi_speed = $taxi_speed==0?'0':$taxi_speed;
										$taxi_details = array(
										"taxi_image"=>$taxi_image,
										"taxicompany"=>$taxicompany,
										"taxi_number"=>$taxi_number,
										"taxi_model"=>$taxi_model,
										"taxi_capacity"=>$taxi_capacity,
										"base_fare"=>$base_fare,
										"min_fare"=>$min_fare,
										"min_km_range"=>$min_km_range,
										"cancellation_fare"=>$cancellation_fare,
										"below_above_km_range"=>$below_above_km_range,
										"below_km"=>$below_km,
										"above_km"=>$above_km,
										"night_charge"=>$night_charge,
										"night_timing_to"=>$night_timing_to,
										"night_fare"=>$night_fare,
										"taxi_speed"=>$taxi_speed
																						
									);	
									
									//print_r($taxi_details);															
								}	
								else
								{
									$taxi_details = "";
								}
								
								//print_r($taxi_details);									
								//echo $additional;
								//Rating of the Driver			

								if(isset($result['comments']) && count($result['comments']) > 0)
								{
									$overall_rating = 0; $i=0; $trip_total_with_rate=0;
									
										foreach($result['comments'] as $comments)
										{
											if($comments['rating'] != 0)
											$trip_total_with_rate++;
											$rating = $comments['rating'];
											//print_r($comments);
											switch($comments['rating']){
												case 1: $star = 1;
														break;
												case 2: $star = 2;
														break;
												case 3: $star = 3;
														break;
												case 4: $star = 4;
														break;
												case 5: $star = 5;
														break;
												default: $star = "";
														break;
											}
											
											if($comments['comments'])
											{ 
												$comment = $comments['comments']; 
											}else{
												$comment = "";//__('no_data');
											}
											
											$passenger_details = $common_model->get_passenger_details($comments["passengers_id"]);
											//print_r($passenger_details);
											/*if($passenger_details[0]['profile_image'])
											{
												$img = URL_BASE.'public/uploads/passenger/thumb_'.$passenger_details[0]['profile_image'];
											}else{
												$img = URL_BASE."/public/images/noimages.jpg";
											} 
											$p_name = ucfirst($passenger_details[0]['name']);
											$p_rating = $star;
											$p_comment = $comment;
											
											$passenger_rate_comment = $p_name.'-'.$p_rating.'-'.$p_comment;
											//echo '<br>';*/
											$overall_rating += $comments['rating'];
											$i++;
											if($trip_total_with_rate!=0 && $overall_rating!=0){
												$totalrating = $overall_rating/$trip_total_with_rate;
											}else{
												$totalrating = 0;
											}
											// Driver details
											$totalrating = round($totalrating);
											
										}
												
											 //echo 'as'.$totalrating;												
									}
									else
									{
										$totalrating = 0;
									}	
											$driver_details = $api->get_driver_profile_details($array["driver_id"],$default_companyid);
											//print_r($driver_details);
											$driver_name = "";
											$driver_image = URL_BASE."/public/images/noimages.jpg";
											if(count($driver_details)>0)
											{
												$driver_name = $driver_details[0]['salutation'].' '.$driver_details[0]['name'];
												$driver_photo = $driver_details[0]['photo'];
												$driver_image = $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DRIVER_IMGPATH.$driver_photo;
												if(file_exists($driver_image) && $driver_photo !='')
												{
												$driver_image = URL_BASE.SITE_DRIVER_IMGPATH.$driver_photo;
												}else{
												$driver_image = URL_BASE."/public/images/noimages.jpg";
												}
											}
									$driver_details = array(
									"driver_name"=>$driver_name,
									"driver_photo"=>$driver_image,
									);
									$driver_details['totalrating'] = round($totalrating);					
									$result = array("taxidetails" => $taxi_details,"driver_details"=>$driver_details);			
									$message = array("detail"=>$result,"status"=>1,"message"=>__('success'));								
							}			
							else
							{
								$message = array("message" => __('no_data'),"status"=>-1);
							}								
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);
					}
					echo json_encode($message);
					exit;
					break;					
					//URL api/?type=current_taxi_features&taxi_id=2&driver_id=2&cityid=1
					case 'current_taxi_features':
					$array = $_GET;
						if(isset($array["driver_id"]))
						{							
							$common_model = Model::factory('Commonmodel');
							$result = $api->get_current_taxi_details($array["driver_id"],$array["taxi_id"],$array["cityid"]);
							$driver_current_location = $api->get_driver_location($array["driver_id"]);
							//echo "<pre>";print_r($result);echo "</pre>";exit;
							$html = "";
							if(count($result) > 0)
							{
								if(isset($result[0]) && count($result[0]) > 0)
								{																		
									$taxi_image = $_SERVER['DOCUMENT_ROOT'].'/'.TAXI_IMG_IMGPATH.$result[0]['taxi_image'];
									if(file_exists($taxi_image) && $result[0]['taxi_image'] !='')
									{
									$taxi_image = URL_BASE.TAXI_IMG_IMGPATH.'tmb100_'.$result[0]['taxi_image'];
									}else{
									$taxi_image = URL_BASE."/public/".UPLOADS."/taxi_image/noimages.jpg";
									}
									$taxicompany = ucfirst($result[0]['company_name']);
									$taxi_number = $result[0]['taxi_no'];
									$taxi_model = ucfirst($result[0]['model_name']);
									$taxi_capacity = $result[0]['taxi_capacity'];
									$taxi_fare_km = $result[0]['taxi_fare_km'];	
									$base_fare = $result[0]['base_fare'];
									$min_fare = $result[0]['min_fare'];
									$cancellation_fare = $result[0]['cancellation_fare'];
									$below_km = $result[0]['below_km'];
									$above_km = $result[0]['above_km'];
									$night_charge = $result[0]['night_charge'];
									$night_timing_to = $result[0]['night_timing_to'];
									$night_fare = $result[0]['night_fare'];
									$taxi_speed = $result[0]['taxi_speed'];
									//$taxi_speed = $taxi_speed==0?'0':$taxi_speed;
										$taxi_details = array(
										"taxi_image"=>$taxi_image,
										"taxicompany"=>$taxicompany,
										"taxi_number"=>$taxi_number,
										"taxi_model"=>$taxi_model,
										"taxi_capacity"=>$taxi_capacity,
										"taxi_fare_km"=>$taxi_fare_km,
										"base_fare"=>$base_fare,
										"min_fare"=>$min_fare,
										"cancellation_fare"=>$cancellation_fare,
										"below_km"=>$below_km,
										"above_km"=>$above_km,
										"night_charge"=>$night_charge,
										"night_timing_to"=>$night_timing_to,
										"night_fare"=>$night_fare,
										"taxi_speed"=>$taxi_speed
																						
									);	
									//print_r($taxi_details);															
								}	
								else
								{
									$taxi_details = "";
								}
								
								//print_r($taxi_details);	

								if(isset($result[1]) && count($result[1]) > 0)
								{
									$additional="";
									foreach($result['label_name'] as $key => $value)
									{
										if($result[1][$result['label_name'][$key]['field_name']] !='') 
										{ 
											$additional .=$result['label_name'][$key]['field_labelname'].",".$result[1][$result['label_name'][$key]['field_name']].',';											
										}
										else
										{
											//$html .="<dt>".$result['label_name'][$key]['field_labelname']."</dt><dd>: ".__('not_specified')."</dd>";
										}										
									}
								}	
								else
								{
									$additional = "";
								}

								$additional = explode(',',$additional);
								//echo $additional;
								//Rating of the Driver			

								if(isset($result['comments']) && count($result['comments']) > 0)
								{
									$overall_rating = 0; $i=0; $trip_total_with_rate=0;
									
										foreach($result['comments'] as $comments)
										{
											if($comments['rating'] != 0)
											$trip_total_with_rate++;
											$rating = $comments['rating'];
											//print_r($comments);
											switch($comments['rating']){
												case 1: $star = 1;
														break;
												case 2: $star = 2;
														break;
												case 3: $star = 3;
														break;
												case 4: $star = 4;
														break;
												case 5: $star = 5;
														break;
												default: $star = "";
														break;
											}
											
											if($comments['comments'])
											{ 
												$comment = $comments['comments']; 
											}else{
												$comment = "";//__('no_data');
											}
											
											$passenger_details = $common_model->get_passenger_details($comments["passengers_id"]);
											//print_r($passenger_details);
											/*if($passenger_details[0]['profile_image'])
											{
												$img = URL_BASE.'public/uploads/passenger/thumb_'.$passenger_details[0]['profile_image'];
											}else{
												$img = URL_BASE."/public/images/noimages.jpg";
											} 
											$p_name = ucfirst($passenger_details[0]['name']);
											$p_rating = $star;
											$p_comment = $comment;
											
											$passenger_rate_comment = $p_name.'-'.$p_rating.'-'.$p_comment;
											//echo '<br>';*/
											$overall_rating += $comments['rating'];
											$i++;
											if($trip_total_with_rate!=0 && $overall_rating!=0){
												$totalrating = $overall_rating/$trip_total_with_rate;
											}else{
												$totalrating = 0;
											}
											
										}
											 $totalrating = round($totalrating);	
											 //echo 'as'.$totalrating;												
									}
									else
									{
										$totalrating = 0;
									}						
									$message = array("taxidetails" => $taxi_details,"driver_current_location"=>$driver_current_location,"addtional_fields"=>$additional,"totalrating"=>$totalrating,"status"=>1);							
							}			
							else
							{
								$message = array("message" => __('no_data'),"status"=>-1);
							}								
					}
					else
					{
						$message = array("message" => __('invalid_user'),"status"=>-1);
					}
					echo json_encode($message);
					exit;
					break;										
				/* End of Passenger Forgot Password */		
				
				/* Get Passenger Account Credits */
				
				//URL http://192.168.1.73:1013/api/index/bnRheGlfYlVtUzZGMUJMVDY4VTZtWkdYaDNnRFV2WE5BRGo0==/?type=get_passenger_account_credits&pass_id=12
				case 'get_passenger_account_credits':
				$array = $_GET;
				if($array['pass_id'] != null)
				{
					$pass_id = $array['pass_id'];
					$passenger_acc_details=$api->get_passenger_account_credits($pass_id);
					if(count($passenger_acc_details)>0)
					{
						$message = array("message" => __('passenger_credits'),"account_credit_details"=>$passenger_acc_details,"status"=>1);
					}
					else
					{
						$message = array("message" => __('no_account'),"status"=>-1);
					}
				}
				else
				{
					$message = array("message" => __('validation_error'),"status"=>-1);
				}
				echo json_encode($message);
				exit;
				
				//http://192.168.1.88:1020/api/index/bnRheGlfYlVtUzZGMUJMVDY4VTZtWkdYaDNnRFV2WE5BRGo0==/?type=reject_trip&trip_id=12&driver_id=&reason=&reject_type=1
				
				case 'reject_trip':
				$array = $_GET;
				//print_r($array);
				$trip_id = $_GET['trip_id'];
				$reject_type = $_GET['reject_type'];
				$driver_id = $_GET['driver_id'];
				if($trip_id != "")
				{					
					if($reject_type == 1)
					{
						$Commonmodel = Model::factory('Commonmodel');
						$passenger_log_details = $api->get_passenger_log_detail($trip_id);
						//print_r($passenger_log_details);exit;
						if(count($passenger_log_details) >0)
						{
							$post=array();
							$post['driver_id']=$driver_id;
							$post['passengers_id']=$passenger_log_details[0]->passengers_id;
							$post['passengers_log_id']=$trip_id;
							$post['reason']=$array['reason'];
							$company_all_currenttimestamp = $Commonmodel->getcompany_all_currenttimestamp($default_companyid);
							$post['createdate']= $company_all_currenttimestamp;
							
								if($passenger_log_details[0]->driver_reply == 'R')
								{
									$message=__('trip_cancel_timeout');
									$msg = array("message" => $message,"status" => '8');	
									echo json_encode($msg);//exit;
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
									if($get_driver_request != 0)
									{
										/******* Update the driver id in */
										$rejection_type = 1;
										$prev_rejected_timeout_drivers = $get_driver_request[0]['rejected_timeout_drivers'];
										if($prev_rejected_timeout_drivers != "")
										{
											$rejected_timeout_drivers = $prev_rejected_timeout_drivers.','.$driver_id;
										}
										else
										{
											$rejected_timeout_drivers = $driver_id;
										}
										$update_trip_array  = array("status"=>'0',"rejected_timeout_drivers" => $rejected_timeout_drivers);
										$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$trip_id);								
										$message = array("message" => __('request_rejected'),"status" => 6);
										$add_rejected_list = $api->add_rejected_list($post,$rejection_type);
									}								
									/***********************************************************************************/							
								}
							}
							else
							{
								$message = array("message" => __('invalid_trip'),"status"=>2);
							}
					}
					else
					{
							$get_driver_request = $api->get_driver_request($trip_id);
							if($get_driver_request != 0)
							{
								/******* Update the driver id in */
								$prev_rejected_timeout_drivers = $get_driver_request[0]['rejected_timeout_drivers'];
								if($prev_rejected_timeout_drivers != "")
								{
									$rejected_timeout_drivers = $prev_rejected_timeout_drivers.','.$driver_id;
								}
								else
								{
									$rejected_timeout_drivers = $driver_id;
								}
								$update_trip_array  = array("status"=>'0',"rejected_timeout_drivers" => $rejected_timeout_drivers);
								$result = $api->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$trip_id);								
								$message = array("message" => __('driver_reply_timeout'),"status" => 7);
							}		
					}	
			}
			else
			{
				$message =__('trip_id_req');
				$message = array("message" => $message,"status" => '-1');
			}
			echo json_encode($message);
			exit;
		}
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
	
	//Search validation
	function search_validation($search_array)
	{
			return Validation::factory($search_array)
				->rule('latitude','not_empty')
				->rule('longitude','not_empty')				
				->rule('pickup_time','not_empty');	
	}

	//nearest validation
	function nearestdriver_validation($search_array)
	{
			return Validation::factory($search_array)
				->rule('latitude','not_empty')
				->rule('longitude','not_empty');	
	}
		
	//Passenger Login Validation
	function passenger_login_validation($array)
	{
		 return Validation::factory($array)
				->rule('phone','not_empty')			
				//->rule('email','email')				
				->rule('phone_number','numeric')				
				->rule('password','not_empty')
				->rule('devicetoken','not_empty');
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
				->rule('phone','not_empty')				
				->rule('phone','numeric');
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
				->rule('creditcard_cvv','not_empty')
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
				->rule('creditcard_cvv','not_empty')
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
				->rule('creditcard_cvv','not_empty')
				->rule('creditcard_cvv','numeric')
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
				->rule('creditcard_cvv','not_empty')
				->rule('creditcard_cvv','numeric')
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
				->rule('new_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))		
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
			
	function encrypt_decrypt($action, $string) {
	   $output = false;

	   $key = 'Taxi Application';

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

	
	//Update Ratings and Comments
	function update_ratings_comments_validation($array)
	{
		return Validation::factory($array)
				->rule('ratings','not_empty');				
				//->rule('comments','not_empty');
							
	}
	
	//Driver Login Validation
	function driver_login_validation($array)
	{
		 return Validation::factory($array)
				->rule('phone','not_empty')				
				->rule('password','not_empty')
				->rule('device_token','not_empty');
	}
	
	public static function action_cron_push()
	{
		$api = Model::factory('api');	
		$result = $api->cron_push();
		echo json_encode($result);		
		exit;
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
//add_expense&expense_driver_id=16&expense_amount=100&expense_type_id=test&payment_type=&expense_date=expense_validation	
	public function expense_validation($array) 
	{
		return Validation::factory($array)       
			
			->rule('expense_driver_id','not_empty')	
			->rule('expense_amount','not_empty')			
			->rule('expense_type_id', 'not_empty')
			->rule('expense_date', 'not_empty');									
	}
	public function delete_expense_validation($array) 
	{
		return Validation::factory($array)       
			
			->rule('expense_id','not_empty');									
	}

	public function delete_card_validation($array) 
	{
		return Validation::factory($array)       
			
			->rule('passenger_cardid','not_empty');									
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
	//
	public function get_target_validation($array) 
	{
		return Validation::factory($array)       
			
			->rule('driver_id','not_empty')	
			->rule('fromdate','not_empty')			
			->rule('todate','not_empty');									
	}	
	public function get_target_validationby_month($array) 
	{
		return Validation::factory($array)       
			
			->rule('driver_id','not_empty')	
			->rule('month','not_empty')			
			->rule('year','not_empty');									
	}
	public function get_target_validationby_year($array) 
	{
		return Validation::factory($array)       
			
			->rule('driver_id','not_empty')	
			->rule('year','not_empty');									
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
			->rule('p_favourite_place', 'not_empty');									
	}
	//p_favourite_id=2&favourite_place=Vadavalli,Gandhipuram,west&fav_comments=
	public function edit_favourite_validation($array) 
	{
		return Validation::factory($array)       
			
			->rule('p_favourite_id','not_empty')				
			->rule('p_favourite_place', 'not_empty');									
	}

	public function is_login_status($driver_id,$company_id='')
	{ 	
			$api = Model::factory('api');
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

    public function send_mail_passenger($log_id='',$travel_status='')
    {

	/* *************************** Mail send to Passenger ************** */   
	$api_model = Model::factory('api');
	$passenger_log_details = $api_model->passenger_transdetails($log_id);
	if(count($passenger_log_details)>0)
	{
		
		$to = $passenger_log_details[0]['passenger_email'];
		$name = $passenger_log_details[0]['passenger_name'];
		$job_referral = $passenger_log_details[0]['job_referral'];
		$api_model=Model::factory('api');	
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
		$orderlist='';   
		$orderlist='<table cellspacing="8" cellpadding="5">';   
		if($travel_status == 4)
		{
			//$orderlist.='<tr style="color:#808080"><td colspan="2" style="color: #161616; font-size: 15px; font-weight: bold"><b>'.__('cancel_trip').'</b></td><td></td></tr>';
		}
		else
		{
			//$orderlist.='<tr style="color:#808080"><td colspan="2" style="color: #161616; font-size: 15px; font-weight: bold"><b>'.__('complete_from').'</b></td><td></td></tr>';
		}	
		$orderlist.='<tr style="color:#808080"><td style="padding-left:5px; width="80px"">'.__('complete_from2').'</td></tr><tr><td colspan="2">'.$passenger_log_details[0]['current_location'].' </td><td></td></tr>'.
		'<tr style="color:#808080"><td style="padding-left:5px; width="80px""> To </td></tr><tr><td colspan="2"> '.$passenger_log_details[0]['drop_location'].'</td><td></td></tr>'.
		'<tr style="color:#808080"><td width="80px">'.__('total_fare').'</td><td>'. CURRENCY.' '.$passenger_log_details[0]['amt'].'</td></tr>'.                                                                                                                                                  
		'<tr style="color:#808080"><td>'.__('track_id').'</td><td>'.$job_referral.'</td></tr></table>';


			$mail="";								
					$replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$name,REPLACE_EMAIL=>$to,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ORDERID=>$log_id,REPLACE_ORDERLIST=>$orderlist,REPLACE_MAPURl=>$mapurl,REPLACE_PICKUP=>$pickup,REPLACE_DROP=>$drop);

					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcomplete-mail.html',$replace_variables);


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
                 /* *************************** Mail send to Passenger ************** */		
    }				


    public function authorize_creditcard($values)
    {   

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

		$api_model = Model::factory('api');
		$driver_model = Model::factory('driver');
		$Commonmodel = Model::factory('commonmodel');
		//echo $values['nightfare_applicable'];
		//print_r($values);exit;
		//$save_card_detail = $values['save_card_detail'];
		//$faretype = $values['faretype'];
		//$new_card = $values['new_card'];
		//echo $account_id;exit;
		$passenger_log_details = $api_model->passengerlogid_details($values['trip_id']);
		if($values['pay_mod_id'] == 4)
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
				$creditcard_cvv = $carddetails[0]['creditcard_cvv'];
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
		}
		
		//echo $creditcard_no;
		//exit;
		$driver_userid = $passenger_log_details[0]['driver_id'];
		$company_id = $passenger_log_details[0]['company_id'];
		$values['company_id'] = $passenger_log_details[0]['company_id'];
		$paypal_details = $api_model->paypal_details(); 	
		$company_paypal_details = $api_model->company_paypal_details($values['company_id']);
		$siteinfo_details = $api_model->siteinfo_details(); 
		//echo 'as'.$company_paypal_details[0]['company_paypal_signature'];
			$passenger_log_id = $values['trip_id'];
			if($values['actual_distance'] == "")
				$distance = $values['distance'];
			else
				$distance = $values['actual_distance'];				
			$actual_amount = $values['actual_amount'];
			$waiting_cost = $values['waiting_cost'];
			$waiting_hours = $values['waiting_time'];
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
			/*if($actual_amount != null)
			{
				$total_fare = $actual_amount;
			}
			else
			{	
				$total_fare = $total;	
			}*/						
		//echo '<br>';
		//echo $paypal_details[0]['paypal_api_username'];
		//echo '<br>';
		//echo $paypal_details[0]['paypal_api_password'];
		//echo '<br>';
		//echo $paypal_details[0]['paypal_api_signature'];
		//exit;
		$trip_fare = round($trip_fare,2);	
		$total_fare = $fare;// + $tips; // Total fare with Tips if exist
		$amount = round($total_fare,2); // Total amount which is used for pass to payment gateways
		$amt = round($total_fare,2);
		$credits_used = 0;
		/******** SAR Equal USD ******************/
		//$amount = round(currency_conversion_usd('SAR',$amount),2); //exit;
		$product_title = Html::chars('Complete Trip');
		//$payment_type = 'Authorization';
		$payment_action='sale';

		$request  = 'METHOD=DoDirectPayment';
		$request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
		//$request .= '&USER=' . urlencode($company_paypal_details[0]['paypal_api_username']);
		$request .= '&USER=' . urlencode($company_paypal_details[0]['company_paypal_username']);
		//$request .= '&PWD=' . urlencode($company_paypal_details[0]['paypal_api_password']);
		$request .= '&PWD=' . urlencode($company_paypal_details[0]['company_paypal_password']);
		$request .= '&SIGNATURE=' . urlencode($company_paypal_details[0]['company_paypal_signature']);

		$request .= '&CUSTREF=' . (int)$values['trip_id'];
		$request .= '&PAYMENTACTION=' . $payment_action; //type
		$request .= '&AMT=' . urlencode($amount); //   $amount = urlencode($data['amount']);

		//$request .= '&CREDITCARDTYPE=' . $_POST['cc_type'];
		$request .= '&ACCT=' . urlencode(str_replace(' ', '',$creditcard_no));
		// $request .= '&CARDSTART=' . urlencode($_POST['cc_start_date_month'] . $_POST['cc_start_date_year']);
		$request .= '&EXPDATE=' . urlencode($expdatemonth.$expdateyear);
		$request .= '&CVV2=' . urlencode($creditcard_cvv);

		/*$request .= '&FIRSTNAME=' . urlencode($shipping_first_name);
		$request .= '&LASTNAME=' . urlencode($shipping_last_name);
		$request .= '&EMAIL=' . urlencode($shipping_email);

		$request .= '&IPADDRESS=' . urlencode($_SERVER['REMOTE_ADDR']);
		$request .= '&STREET=' . urlencode($this->session->get('addr'));

		$request .= '&CITY=' . urlencode($city);
		$request .= '&STATE=' . urlencode($state);
		$request .= '&ZIP=' . urlencode($zipcode);

		$request .= '&COUNTRYCODE=' . urlencode($country_code);
		*/

		$request .= '&CURRENCYCODE=' .$company_paypal_details[0]['company_currency_format'];

		$paypal_type=($company_paypal_details[0]['payment_method'] =="L")?"live":"sandbox";
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
		 
		$_SESSION["paymentresponse"]=array();
		$_SESSION["paymentresponse"]=$nvpArray;  
	//	print_r($_SESSION["paymentresponse"]);exit;

        if(isset($_SESSION["paymentresponse"]) && !empty($_SESSION["paymentresponse"]) )
        {
		    $paymentresponse=array();
		    $ack = isset($_SESSION['paymentresponse']["ACK"])?strtoupper($_SESSION['paymentresponse']["ACK"]):'';		                
			if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING" )
			{                           
				/*if($save_card_detail == 1)
				{
					$card_detail_array = array("creditcard_no" => $creditcard_no,"creditcard_cvv" => $creditcard_cvv,"expdatemonth" => $expdatemonth,"expdateyear" => $expdateyear);
					$transaction = $api_model->passenger_card_details_update($card_detail_array,$passenger_userid);
				} */								
				$invoceno = commonfunction::randomkey_generator();
				$paymentresponse=$_SESSION['paymentresponse'];							
				$payment_type = "";
				//if($new_card == 1){$payment_type = 4;}else{$payment_type =;}
				$insert_array = array(
									"passengers_log_id" => $passenger_log_id,
									"distance" 			=> $values['distance'],
									"actual_distance" 	=> $values['actual_distance'],
									"tripfare"			=> $trip_fare,
									"fare" 				=> $fare,
									"tips" 				=> $tips,
									"waiting_cost"		=> $waiting_cost,
									"company_tax"		=> $company_tax,
									"passenger_discount"=> $passenger_discount_amt,
									"account_discount" => $account_discount_amt,
									"credits_used"		=>$credits_used,
									"waiting_time"		=> $waiting_hours,
									"remarks"		=> $remarks,
									"payment_type"		=>  $values['pay_mod_id'],
									"amt"			=> $amt,
									"company_id" => $company_id,//nightfare_applicable
									"nightfare_applicable" => $values['nightfare_applicable'],
									"nightfare" 		=> $values['nightfare']

								);
																
				$transactionfield = $insert_array + $paymentresponse + $siteinfo_details;  // Data Store 				
				//print_r($transactionfield);exit;
				/********** Update Driver Status after complete Payments *****************/
				$update_driver_arrary  = array("status" => 'F');
				$result = $api_model->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_userid);	
				/***********************************************************************************/				
				//Update Travel Status after payment done
				$passenger_log_id = $values['trip_id'];
				$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
				$journey = $driver_model->update_journey_status($passenger_log_id,$msg_status,$driver_reply,$journey_status);
                
				//insert transaction status
				//===================================
				$transaction_detail=$api_model->triptransact_details($transactionfield);
				/* *************************** Mail send to Passenger ************** */   
				$to = $passenger_log_details[0]['passenger_email'];
				$name = $passenger_log_details[0]['passenger_name'];
				$phone = $passenger_log_details[0]['passenger_phone'];				

                /*$orderlist='';   
				$orderlist='<p><b>'.__('complete_from').'</b><br />'.__('complete_from1').''.$passenger_log_details[0]['current_location'].' <br /> To : '.$passenger_log_details[0]['drop_location'].'</p>'.
				'<p>'.__('complete_date').$this->currentdate.'</p>'.
				'<p>'.__('total_cost').':'. CURRENCY.' '.$values['total_fare'].'</p>'.                                                                         
				'<p>'.__('transactionid_label').': '.$paymentresponse['TRANSACTIONID'].'</p>';*/
				
				$orderlist='';   
				$orderlist='<p><b>'.__('complete_from').'</b></p><p style="padding-left:5px;">'.__('complete_from1').''.$passenger_log_details[0]['current_location'].' </p><p style="padding-left:5px;"> To : '.$passenger_log_details[0]['drop_location'].'</p>'.
				/*'<p>'.__('complete_date').$this->currentdate.'</p>'.*/
				'<p>'.__('total_cost').':'. CURRENCY.' '.$amt.'</p>'.                                                                         
				'<p>'.__('transactionid_label').': '.$paymentresponse['TRANSACTIONID'].'</p>';

      	                    	$mail="";								
					        $replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$name,REPLACE_EMAIL=>$to,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ORDERID=>$paymentresponse['TRANSACTIONID'],REPLACE_ORDERLIST=>$orderlist);

					        $message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcomplete-mail.html',$replace_variables);


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

						if(SMS == 1)
						{
						$common_model = Model::factory('commonmodel');	
						$api = Model::factory('api');	
						//$this->phone=$this->commonmodel->get_passengers_details($email,1);
						$message_details = $common_model->sms_message_by_title('payment_confirmed_sms');
						$to = $phone;
						$message = $message_details[0]['sms_description'];
						//$message = str_replace("##booking_key##",$passenger_log_id,$message);
						$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
							
						$api->send_sms($to,$message);

						//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");

						}
		
					/* *************************** Mail send to Manager ************** */   
					/*
					$to = $passenger_log_details[0]['passenger_email'];
					$name = $passenger_log_details[0]['passenger_name'];

		                    	$orderlist='';   
		                    	$orderlist='<p> Complete Trip : From : '.$passenger_log_details[0]['current_location'].'  To : '.$passenger_log_details[0]['drop_location'].'</p>'.
		                                   '<p> Complete date : '.$this->currentdate.'</p>'.
						   '<p> Total Cost : '. CURRENCY.' '.$values['total_fare'].'</p>'.                                      	   '<p> Transaction ID : '.$paymentresponse['TRANSACTIONID'].'</p>';

	      	                    	$mail="";								
							$replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$name,REPLACE_EMAIL=>$to,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ORDERID=>$paymentresponse['TRANSACTIONID'],REPLACE_ORDERLIST=>$orderlist);

							$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcomplete-mail.html',$replace_variables);


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
						*/
		               /* *************************** Mail send to Manager ************** */
				        //Message::success("Payment has been completed succcessfully");
					  return 1; 	
                      }   
				 else
				 {
						  $message = isset($_SESSION['paymentresponse']['L_LONGMESSAGE0'])?$_SESSION['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
						  return 0; 
				 }	
  

                    } 
                    else
                    {
                    		//set cancel message
                            Message::success(__("payment_cancelled"));
                    } 


	}

	    public function cancel_trippayment($values,$cancellation_nfree)
	    {   

			$api_model = Model::factory('api');

			$passenger_log_details = $api_model->passengerlogid_details($values['passenger_log_id']);
	
			$passenger_userid = $passenger_log_details[0]['passengers_id'];
			$driver_userid = $passenger_log_details[0]['driver_id'];
			$driver_userid = $passenger_log_details[0]['driver_id'];
			$company_id = $passenger_log_details[0]['company_id'];
			//$array['company_id'] = $passenger_log_details[0]['company_id'];
			$values['company_id'] = $passenger_log_details[0]['company_id'];
			
			$card_type = '';
			$default = 'yes';
			$carddetails = $api_model->get_creadit_card_details($passenger_userid,$card_type,$default);
		
			if(count($carddetails)>0)
			{
				$creditcard_no = encrypt_decrypt('decrypt',$carddetails[0]['creditcard_no']);
				$creditcard_cvv = $carddetails[0]['creditcard_cvv'];
				$expdatemonth = $carddetails[0]['expdatemonth'];
				$expdateyear = $carddetails[0]['expdateyear'];
			}		
			
			//echo $creditcard_no;exit;
			$city_id = $passenger_log_details[0]['search_city'];
			$taxi_id = $passenger_log_details[0]['taxi_id'];							

			$taxi_model_details = $api_model->get_taxi_model_details($taxi_id);

			$taxi_model = $taxi_model_details[0]['taxi_model'];

			$paypal_details = $api_model->paypal_details(); 
			
			$company_paypal_details = $api_model->company_paypal_details($values['company_id']);	

			$siteinfo_details = $api_model->siteinfo_details(); 	

			$company_details = $api_model->company_details($company_id);
			
			$fare_details = $api_model->get_model_fare_details($company_id,$taxi_model,$city_id);

			//$amount = $values['total_fare'] = $company_details[0]['cancellation_fare'];
			$values['total_fare'] = $fare_details[0]['cancellation_fare'];
			$amount = $fare_details[0]['cancellation_fare'];
			/******** SAR Equal USD ******************/
			//$c_amount = round(currency_conversion_usd('SAR',$amount),2); //exit;			
			

			$product_title = Html::chars('Complete Trip');
			//$payment_type = 'Authorization';
			$payment_action='sale';

			$request  = 'METHOD=DoDirectPayment';
			$request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
			$request .= '&USER=' . urlencode($company_paypal_details[0]['company_paypal_username']);
			$request .= '&PWD=' . urlencode($company_paypal_details[0]['company_paypal_password']);
			$request .= '&SIGNATURE=' . urlencode($company_paypal_details[0]['company_paypal_signature']);

			$request .= '&CUSTREF=' . (int)$values['passenger_log_id'];
			$request .= '&PAYMENTACTION=' . $payment_action; //type
			$request .= '&AMT=' . urlencode($amount); //   $amount = urlencode($data['amount']);

			//$request .= '&CREDITCARDTYPE=' . $_POST['cc_type'];
			$request .= '&ACCT=' . urlencode(str_replace(' ', '', $creditcard_no));
			// $request .= '&CARDSTART=' . urlencode($_POST['cc_start_date_month'] . $_POST['cc_start_date_year']);
			$request .= '&EXPDATE=' . urlencode($expdatemonth . $expdateyear);
			$request .= '&CVV2=' . urlencode($creditcard_cvv);

			/*$request .= '&FIRSTNAME=' . urlencode($shipping_first_name);
			$request .= '&LASTNAME=' . urlencode($shipping_last_name);
			$request .= '&EMAIL=' . urlencode($shipping_email);

			$request .= '&IPADDRESS=' . urlencode($_SERVER['REMOTE_ADDR']);
			$request .= '&STREET=' . urlencode($this->session->get('addr'));

			$request .= '&CITY=' . urlencode($city);
			$request .= '&STATE=' . urlencode($state);
			$request .= '&ZIP=' . urlencode($zipcode);

			$request .= '&COUNTRYCODE=' . urlencode($country_code);
			*/

			$request .= '&CURRENCYCODE=' . $company_paypal_details[0]['company_currency_format'];

			$paypal_type=($company_paypal_details[0]['payment_method'] =="L")?"live":"sandbox";
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

			$_SESSION["paymentresponse"]=array();
			$_SESSION["paymentresponse"]=$nvpArray;  

		        if(isset($_SESSION["paymentresponse"]) && !empty($_SESSION["paymentresponse"]) )
		        {
				$paymentresponse=array();

				$ack = isset($_SESSION['paymentresponse']["ACK"])?strtoupper($_SESSION['paymentresponse']["ACK"]):'';		                
				if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING" )
				{                           
					$invoceno = commonfunction::randomkey_generator();

					$paymentresponse=$_SESSION['paymentresponse'];

					$transactionfield = $values + $paymentresponse + $siteinfo_details;  // Data Store 

		        
					//insert transaction status
					//===================================
					$transaction_detail=$api_model->cancel_triptransact_details($transactionfield,$cancellation_nfree);

					$phone = $passenger_log_details[0]['passenger_phone'];
					$passenger_log_id = $values['passenger_log_id'];

					//free sms url with the arguments
					if(SMS == 1)
					{
					$common_model = Model::factory('commonmodel');	
					$api = Model::factory('api');	
					//$this->phone=$this->commonmodel->get_passengers_details($email,1);
					$message_details = $common_model->sms_message_by_title('payment_cancel');
					$phone = $passenger_log_details[0]['passenger_phone'];
					$to = $phone;
					$message = $message_details[0]['sms_description'];
					//$message = str_replace("##booking_key##",$passenger_log_id,$message);
					$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
								
					$api->send_sms($to,$message);

					//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");

					}

					/* *************************** Mail send to Passenger **************    

					$to = $passenger_log_details[0]['passenger_email'];

					$name = $passenger_log_details[0]['passenger_name'];

		                    	$orderlist='';   
					$orderlist='<p><b>'.__('complete_from').'</b><br />'.__('complete_from1').''.$passenger_log_details[0]['current_location'].' <br /> To : '.$passenger_log_details[0]['drop_location'].'</p>'.
					'<p>'.__('cancel_date').$this->currentdate.'</p>'.
					'<p>'.__('total_cost').':'. CURRENCY.' '.$values['total_fare'].'</p>'.                                                                         
					'<p>'.__('transactionid_label').': '.$paymentresponse['TRANSACTIONID'].'</p>';

	      	                    	$mail="";								
							$replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$name,REPLACE_EMAIL=>$to,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ORDERID=>$paymentresponse['TRANSACTIONID'],REPLACE_ORDERLIST=>$orderlist);

							$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcomplete-mail.html',$replace_variables);


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

					 //---------------------------------- ANDROID PUSH NOTIFICATION ----------------------------------//


		                                 /* *************************** Mail send to Passenger ************** */

						/* *************************** Mail send to Manager ************** */   
						/*
						$to = $passenger_log_details[0]['passenger_email'];
						$name = $passenger_log_details[0]['passenger_name'];

				            	$orderlist='';   
				            	$orderlist='<p> Complete Trip : From : '.$passenger_log_details[0]['current_location'].'  To : '.$passenger_log_details[0]['drop_location'].'</p>'.
				                           '<p> Complete date : '.$this->currentdate.'</p>'.
							   '<p> Total Cost : '. CURRENCY.' '.$values['total_fare'].'</p>'.                                      	   '<p> Transaction ID : '.$paymentresponse['TRANSACTIONID'].'</p>';

		      	                    	$mail="";								
								$replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$name,REPLACE_EMAIL=>$to,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ORDERID=>$paymentresponse['TRANSACTIONID'],REPLACE_ORDERLIST=>$orderlist);

								$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcomplete-mail.html',$replace_variables);


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
							*/
				                         /* *************************** Mail send to Manager ************** */


						  //Message::success("Payment has been completed succcessfully");
						  return 1; 	

		                 }   
				 else
				 {
				 		  $message = isset($_SESSION['paymentresponse']['L_LONGMESSAGE0'])?$_SESSION['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
						  return 0; 
				 }	
	  

		            } 
		            else
		            {
		            		//set cancel message
		                    Message::success(__("payment_cancelled"));
		            } 

		}
		function history_validation($search_array)
		{
				return Validation::factory($search_array)
					->rule('driver_id','not_empty')
					->rule('locations','not_empty')				
					->rule('status','not_empty');	
		}
		
		public function auto_search_send_request($trip_id,$driver_reply,$default_companyid)
		{				
				try
				{					
								$add_model = Model::factory('add');			
								$Commonmodel = Model::factory('Commonmodel');								
								$find_model = Model::factory('find');
								$api = Model::factory('api');									
								$passenger_log_details=$api->passenger_log_det($trip_id);																	
								$latitude = $passenger_log_details[0]['pickup_latitude'];
								$longitude = $passenger_log_details[0]['pickup_longitude'];
								$miles = '15';
								$unit = 0; //0 - Km, 1- Mile
								$no_passengers = $passenger_log_details[0]['no_passengers'];
								$pickup_time = $passenger_log_details[0]['pickup_time'];	
								
								$pickupplace = $passenger_log_details[0]['current_location'];
								$dropplace = $passenger_log_details[0]['drop_location'];								
								$drop_latitude = $passenger_log_details[0]['drop_latitude'];
								$drop_longitude = $passenger_log_details[0]['drop_longitude'];
								$taxi_fare_km = '';
								$motor_company = $passenger_log_details[0]['taxi_company'];
								$motor_model = $passenger_log_details[0]['taxi_model'];	
								$passengers_id = $passenger_log_details[0]['passengers_id'];
								$maximum_luggage = '0';	
								$time_to_reach_passen = $passenger_log_details[0]['time_to_reach_passen'];	
								$cityname = $passenger_log_details[0]['city_name'];	
								$passenger_id = $passenger_log_details[0]['passengers_id'];	
								$sub_logid = $trip_id;		
								$rejection_type = "";

								/************ Add the rejection/Timeout list ************/
								$post=array();
								$post['reason']="";

								$post['driver_id']=$passenger_log_details[0]['driver_id'];
								$post['passengers_id']=$passenger_log_details[0]['passengers_id'];
								$post['passengers_log_id']=$trip_id;								
								$company_all_currenttimestamp = $Commonmodel->getcompany_all_currenttimestamp($default_companyid);
								$post['createdate']= $company_all_currenttimestamp;																	
								//$add_passengers_log_temp = $api->add_passengers_log_temp($post);
								if($driver_reply == 'R')
								{
									$post['reason']=$passenger_log_details[0]['driver_comments'];//"Driver rejected the request";
									$rejection_type = '1';		
									$add_rejected_list = $api->add_rejected_list($post,$rejection_type);							
									$add_passengers_log_temp = $api->add_passengers_log_temp($post);
								}
								//elseif($driver_reply == 'T')
								else
								{
									$post['reason']="Trip cancelled due to timeout";
									$rejection_type = '0';
									$add_rejected_list = $api->add_rejected_list($post,$rejection_type);
									$add_passengers_log_temp = $api->add_passengers_log_temp($post);
								}		

														
								/*********************************************************/							
								$city_id  = $api->get_city_id($cityname);
								$driver_details = $find_model->search_driver_mobileapp($latitude,$longitude,$miles,$passenger_id,$taxi_fare_km,$motor_company,$motor_model,$maximum_luggage,$cityname,$sub_logid,$default_companyid,$unit);		
								//print_r($driver_details);
								//exit;
							if(count($driver_details)>0)
							{								
								/** Nearest distance calculation **/
								$prev_min_distance='10000~0~0~0';
								$taxi_id='';
								$temp_driver=0;
								$nearest_key=0;
								$prev_key=0;
								$a=1;
								$total_count=count($driver_details);
									foreach($driver_details as $key => $value)
									{
											$taxi_image = URL_BASE."public/images/no_taximg.png";
											//Request Values
											$driver_details[$key]['taxi_image'] = $taxi_image;
											$driver_details[$key]['city_id'] = $city_id;
											$driver_details[$key]['pickupplace'] = $pickupplace;
											$driver_details[$key]['dropplace'] = $dropplace;
											$driver_details[$key]['drop_latitude'] = $drop_latitude;
											$driver_details[$key]['drop_longitude'] = $drop_longitude;	
											$driver_details[$key]['pickup_time'] = $pickup_time;	
											$driver_details[$key]['no_of_passengers'] = '';//$search_array['no_of_passengers'];
											$driver_details[$key]['taxi_model'] = $value['model_name'];
											/*Nearest driver calculation */
											$prev_min_distance = explode('~',$prev_min_distance);
											$prev_key=$prev_min_distance[1];
											$prev_min_distance = $prev_min_distance[0];
											//checking with previous minimum 
											if($value['distance'] < $prev_min_distance)
											{	
												//new minimum distance
												$nearest_key=$key;
												$prev_min_distance = $value['distance'].'~'.$key;
											}
											else
											{
												//previous minimum
												$nearest_key=$prev_key;
												$prev_min_distance = $prev_min_distance.'~'.$prev_key;
											}
											
											if($a == $total_count)
											{
												$driver_details[$nearest_key]['nearest_driver'] ='1';
												$taxi_id=$value['taxi_id'];
												$nearest_driver=$driver_details[$nearest_key]['driver_id'];
												$taxi_id=$driver_details[$nearest_key]['taxi_id'];
												if($nearest_key != $key)
												{
												$driver_details[$key]['nearest_driver'] ='0';
												}
											}
											else
											{
												$driver_details[$key]['nearest_driver'] ='0';
											}

											$a++;
									}
								//print_r($distance_array);

										
								/* update booking */
								
								$formvalues = Arr::extract($_GET, array('pickup','drop','no_of_passengers','pickup_time','driver_id','passenger_id','roundtrip','passenger_phone','cityname','distance_away','sub_logid','approx_distance','approx_fare','drop_latitude','drop_longitude','notes'));	
								$formvalues['taxi_id']=$taxi_id;
								$formvalues['pickup_latitude']=$passenger_log_details[0]['pickup_latitude'];
								$formvalues['pickup_longitude']=$passenger_log_details[0]['pickup_longitude'];
								$formvalues['pickupplace']= $pickupplace;	
								$formvalues['drop']= $dropplace;							
								$formvalues['distance_away']= $time_to_reach_passen;
								//$formvalues['pickup']=$passenger_log_details[0]['pickup_location'];
								//$formvalues['drop']=$passenger_log_details[0]['drop_location'];
								$formvalues['driver_id'] =$nearest_driver;
								$commonmodel=Model::factory('commonmodel');		
								//echo $trip_id;
								//echo '<br>';		
								$result=$api->update_booking($nearest_driver,$trip_id);
								//print_r($result);
								//$result =1;
								if($result > 0 )
								{
									$passenger_logid = $trip_id;
									$notification_time = $this->notification_time;
									//print_r($config_array);echo 'as';exit;
									if($notification_time != 0)
									{
										$notification_time = $notification_time;
									}
									else
									{
										$notification_time = 15;
									}
									//echo $notification_time;exit;
									// Passenger Details 
									$passenger_details = $api->passenger_profile($passengers_id);
									$passenger_name = $profile_image = "";
									if(count($passenger_details)>0)
									{
										$passenger_name = ucfirst($passenger_details[0]['salutation']).' '.ucfirst($passenger_details[0]['name']);
										$profile_image = $passenger_details[0]['profile_image'];
									}
									$formvalues['bookedby']=BOOK_BY_PASSENGER;			
									$formvalues['passenger_name']=$passenger_name;
									$formvalues['profile_image'] = "";
									//if(file_exists($driver_image) && $driver_image !='')	
									$notes = $formvalues['notes'];
									$distance_away = round($formvalues['distance_away'],2);
																										
									$pushmessage = array("message" => __('new_trip_request'),"status" => 1,"Passenger_logid"=>$passenger_logid,"booking_details" =>$formvalues,"estimated_time"=>$distance_away,"notification_time"=>$notification_time,"notes"=>$notes);		
									//echo $formvalues['driver_id'];
									//print_r($pushmessage);
									//exit;

									$driver_details = $api->get_driver_profile_details($formvalues['driver_id'],$default_companyid);
									if(count($driver_details)>0)
									{
										$device_id = $driver_details[0]['device_id'];
										$device_token = $driver_details[0]['device_token'];								
										$device_type = $driver_details[0]['device_type'];
										$send_notification = $api->send_driver_mobile_pushnotification($device_token,$device_type,$pushmessage,$this->driver_android_api);	
										$result = 1;
										//echo $result;
										//$detail = array("passenger_tripid"=>$passenger_logid,"notification_time"=>$notification_time);
										//$msg = array("message" => __('api_request_confirmed_passenger'),"status" => 1,"detail"=>$detail);	
									}
									else
									{
										$result = 2;
									}
								}
								else
								{
									$result = 3;									
								}																															
						}
						else
						{
							$result = 4;							
						}
						return $result;
					}
					catch(Throwable $e)	
					{
						//print_r($e);
						return 5;
					}
				}	
							
  	}


?>
