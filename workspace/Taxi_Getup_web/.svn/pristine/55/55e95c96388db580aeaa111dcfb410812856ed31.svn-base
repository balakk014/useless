<?php defined('SYSPATH') or die('No direct script access.');
/****************************************************************

* Contains Finding By IP details


* @Package: ConnectTaxi

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************************************/
class Controller_TaximobilityFind extends Controller_Website {

	/**
	****__construct()****
	*/	
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
		$this->taxidispatchMdl = Model::factory('taxidispatch');
		$this->domain_name = (COMPANY_CID != 0) ? SUBDOMAIN : 'site';
	}
	public function action_index()
	{  		
		$view=View::factory(USERVIEW.'find');				
		$this->template->content=$view;
		$this->template->title=$this->title;	
	}
	
	public function action_search()
	{
		
		$find_model = Model::factory('find');
		
		$this->session = Session::instance();
		$data_address = $this->session->get('data_address');
		$current_location = $this->session->get("current_location");
		$drop_location = $this->session->get("drop_location");
		$no_passenger = $this->session->get("no_passengers");
		//$pickup_time = $this->session->get("pickup_time");
		$pickup_time = '';
		$userid =$this->session->get('id');
		$content = $this->commonmodel->getcontents('search');
		
		// get cancel_rejected_driver_id for current user
		if(isset($userid))
		{
			$cancel_reject_trips = $find_model->get_cancel_reject_trips($userid);
			//print_r($cancel_reject_trips);
			$driver_ids ="";
			foreach($cancel_reject_trips as $values)
			{
				$driver_ids .= $values['driver_id'].',';
			}
			$cancel_rejected_driver_id = substr($driver_ids,0,strlen($driver_ids)-1);
		}
		else
		{
			$cancel_rejected_driver_id = "";
		}
		////////////
		if($data_address == null)
		{
			//$ip=$_SERVER['SERVER_ADDR'];
			$ip=IPADDRESS;		
			$api_link = 'http://api.ipinfodb.com/v3/ip-city/?key='.IPINFOAPI_KEY.'&ip='.$ip.'&format=json';
			$data_address = @file_get_contents($api_link);			
			$this->session->set('data_address',$data_address);
		}					
		
		$json_data = json_decode($data_address);	
		
		$miles = $this->session->get('miles');
		$no_passengers = '';
		$bookingtime = '';
		//Sending the latitude and lonitude to the file
		$driver_details = $find_model->get_driver_location($json_data->latitude,$json_data->longitude,$miles,$no_passengers,$bookingtime);	
		
		$getMiles = $find_model->getMiles();
		//print_r($getMiles);
		$view=View::factory(USERVIEW.'find')
				->bind('data_address',$data_address)			
				->bind('current_location',$current_location)
				->bind('drop_location',$drop_location)
				->bind('no_passengers',$no_passenger)
				->bind('pickup_time',$pickup_time)
				->bind('lastdriver_id',$lastdriver_id)
				->bind('getMiles',$getMiles)
				->bind('cancel_rejected_driver_id',$cancel_rejected_driver_id)
				->bind('content',$content)						
				->bind('driver_details',$driver_details);			
		
		$this->template->content=$view;
		$this->template->title=$this->title;	
	}

	//Function for Ajax ; getting the Driver Location
	public function action_get_driver_location()
	{
		if(isset($_POST["latitude"]))
		{
			$find_model = Model::factory('find');
			$latitude = $_POST["latitude"];
			$longitude = $_POST["longitude"];
			$no_passengers = $_POST["no_passengers"];
			$miles = $_POST["miles"];
			$bookingtime = $_POST["bookingtime"];
			/** Unset passenger search sessions **/
			$this->session->set("current_location",'');
			$this->session->set("drop_location",'');
			$this->session->set("no_passengers",'');
			$this->session->set("pickup_time",'');
			/*************/
			$driver_details = $find_model->get_driver_location($latitude,$longitude,$miles,$no_passengers,$bookingtime);

			echo json_encode($driver_details);
			
			exit;
		}
	}
	
	public function action_displayname()
	{
		echo 'demo testing';
		exit;
	}
	
	public function action_advancesearch()
	{

		$find_model = Model::factory('find');
		$this->session = Session::instance();
		$data_address = $this->session->get('data_address');
		$add_model = Model::factory('add');					
		$userid = $this->session->get('id');
		$usertype = $this->session->get('usertype');	

		if($userid !='' && $usertype =='driver')
		{
			Message::error(__('you_dont_access'));
			$this->request->redirect("driver/dashboard");
		}	

		$current_location = $this->session->get("current_location");
		$drop_location = $this->session->get("drop_location");
		$no_passenger = $this->session->get("no_passengers");
		$pass_log_id = $this->session->get("passengerlog_id");

		$this->session->set("current_location",'');
		$this->session->set("drop_location",'');
		$this->session->set("no_passengers",'');	
		$this->session->set("passengerlog_id",'');

		if(isset($_POST['search_country']))
		{	
			$_SESSION['search_country'] = $_POST['search_country'];
			$_SESSION['search_city'] = $_POST['search_city'];

		}

		$content = $this->commonmodel->getcontents('advancesearch');



		// get cancel_rejected_driver_id for current user
		if(isset($userid))
		{
			$cancel_reject_trips = $find_model->get_cancel_reject_trips($userid);
			$driver_ids ="";
			foreach($cancel_reject_trips as $values)
			{
				$driver_ids .= $values['driver_id'].',';
			}
			$cancel_rejected_driver_id = substr($driver_ids,0,strlen($driver_ids)-1);
		}
		else
		{
			$cancel_rejected_driver_id = "";
		}
		////////////
		//From Normal Search Values
		if(isset($_POST) && count($_POST) > 0){
			$post_values = $_POST;
		}
		else{
			$post_values = array(
						"current_location"=>"",
						"drop_location"=>"",
						"no_passengers"=>"",
						"pick_up_time"=>"",
						"miles"=>""
						);
			}
						
		
		if($data_address == null)
		{
			$ip=$_SERVER['SERVER_ADDR'];
			//$ip=IPADDRESS;		
			$api_link = 'http://api.ipinfodb.com/v3/ip-city/?key='.IPINFOAPI_KEY.'&ip='.$ip.'&format=json';
			$data_address = @file_get_contents($api_link);			
			if($data_address!='')
			{
				$this->session->set('data_address',$data_address);
				$json_data = json_decode($data_address);	
				$cityName = $json_data->cityName; 
				$countryName = $json_data->countryName; 
				$this->session->set('search_city',$cityName);
				$this->session->set('search_country',$countryName);
			}
			else
			{
				$data_address = $find_model->getcityname(DEFAULT_CITY);
				$data_address = $data_address[0]['city_name'];
				$this->session->set('search_city',$data_address[0]['city_name']);
			}
			/*
				{
					"statusCode" : "OK",
					"statusMessage" : "",
					"ipAddress" : "209.21.92.148",
					"countryCode" : "US",
					"countryName" : "UNITED STATES",
					"regionName" : "CALIFORNIA",
					"cityName" : "SAN FRANCISCO",
					"zipCode" : "94104",
					"latitude" : "37.7913",
					"longitude" : "-122.401",
					"timeZone" : "-07:00"
				}
				*/
		}	
		
		$json_data = json_decode($data_address);	
		
		$miles = $this->session->get('miles');
		$no_passengers = '';
		$taxi_fare_km = '';
		$taxi_min_fare = '';
		$taxi_model = '';
		$taxi_type = '';
		$maximum_luggage = "";

		if(isset($_SESSION['search_city']))
		{
			$cityname = $_SESSION['search_city'];			
		}
		else
		{
			$cityname = '';			
		}
		
		//Sending the latitude and lonitude to the file
		//$driver_details = $find_model->search_driver_location($json_data->latitude,$json_data->longitude,$miles,$no_passengers,$_REQUEST,$taxi_fare_km,$taxi_min_fare,$taxi_model,$taxi_type,$maximum_luggage,$cityname,$pass_log_id);	

	$driver_details = $find_model->search_driver_location(LOCATION_LATI,LOCATION_LONG,$miles,$no_passengers,$_REQUEST,$taxi_fare_km,$taxi_model,$taxi_type,$maximum_luggage,$cityname,$pass_log_id);	

		$motor_details = $add_model->motor_details();
		$motor_id = 1;
		$model_details = $find_model->getmodel_details($motor_id );
					
		$additional_fields = $add_model->taxi_additionalfields();
		$getMiles = $find_model->getMiles();
		
		$view=View::factory(USERVIEW.'advancesearch')
				->bind('data_address',$data_address)
				->bind('current_location',$current_location)
				->bind('drop_location',$drop_location)
				->bind('pass_log_id',$pass_log_id)
				->bind('no_passengers',$no_passenger)
				->bind('motor_details',$motor_details)
				->bind('model_details',$model_details)
				->bind('post_values',$post_values)							
				->bind('additional_fields',$additional_fields)
				->bind('getMiles',$getMiles)
				->bind('cancel_rejected_driver_id',$cancel_rejected_driver_id)	
				->bind('content',$content)						
				->bind('driver_details',$driver_details);			
		$cms = Model::factory('cms');
		$content_cms = $cms->getcmscontent('advance-search');
		$this->meta_title=isset($content_cms[0]['meta_title'])?$content_cms[0]['meta_title']:"";
		$this->meta_keywords=isset($content_cms[0]['meta_keyword'])?$content_cms[0]['meta_keyword']:"";
		$this->meta_description=isset($content_cms[0]['meta_description'])?$content_cms[0]['meta_description']:"";
		$this->template->content=$view;
		$this->template->title=$this->title;	
	}

	public function action_getmodellist()
	{
		$find_model = Model::factory('find');
		$output ='';
		$motorid =arr::get($_REQUEST,'motor_id'); 
		$modelid =arr::get($_REQUEST,'model_id'); 

		$getmodel_details = $find_model->getmodel_details($motorid);

		if(isset($motorid))
		{
				
			$count=count($getmodel_details);
			if($count>0)
			{
				$output .='<select name="taxi_model" id="taxi_model">
					   <option value="">--Select--</option>';

					foreach($getmodel_details as $modellist) { 
					$output .='<option value="'.$modellist["model_id"].'"';
					if($modelid == $modellist["model_id"])
					{
						$output .='selected=selected';
					}
					$output .='>'.$modellist["model_name"].'</option>';
					}
	
				$output .='</select>';
				
			}
			else
			{
				$output .='<select name="taxi_model" id="taxi_model">
				<option value="">--Select--</option></select>';
			}	   

		}
			echo $output;exit;
			
	}

	public function action_search_driver_location()
	{
		
		if(isset($_REQUEST["latitude"]))
		{
			$find_model = Model::factory('find');
			$common_model = Model::factory('commonmodel');
			$latitude = $_POST["latitude"];
			$longitude = $_POST["longitude"];
			$miles = "1500";
			$no_passengers = $_POST["no_passengers"];
			$taxi_fare_km = $_POST["taxi_min_fare"];
			//$taxi_min_fare = $_POST["taxi_min_fare"]; 
			$taxi_model = $_POST["taxi_model"];
			$taxi_type = $_POST["taxi_type"]; 
			$maximum_luggage = $_POST["maximum_luggage"];
			$pass_logid = $_POST["pass_logid"];
			$passenger_id = $_POST["passenger_id"];

			if(isset($_SESSION['search_city']))
			{
				$cityname = $_SESSION['search_city'];			
			}
			else
			{
				$cityname = '';			
			}

			$driver_details = $find_model->search_driver_location($latitude,$longitude,$miles,$no_passengers,$_REQUEST,$taxi_fare_km,$taxi_model,$taxi_type,$maximum_luggage,$cityname,$pass_logid);
			//print_r($driver_details);exit;
			$nearest_driver='';
			$a=1;
			$temp='10000';
			$driver_list="";
			$prev_min_distance='10000~0~0~0';
			$taxi_id='';
			$temp_driver=0;
			$nearest_key=0;
			$prev_key=0;
			$driverdetails=array();
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
						$driver_list .= $value['driver_id'].',';
						$available_drivers = substr_replace($driver_list ,"",-1);
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
							$driverdetails[0]['driver_id']=$driver_details[$nearest_key]['driver_id'];
							$driverdetails[0]['taxi_id']=$driver_details[$nearest_key]['taxi_id'];
							$driverdetails[0]['below_km']=$driver_details[$nearest_key]['below_km'];
							$driverdetails[0]['above_km']=$driver_details[$nearest_key]['above_km'];
							$driverdetails[0]['min_fare']=$driver_details[$nearest_key]['min_fare'];
							$driverdetails[0]['company_id']=$driver_details[$nearest_key]['get_companyid'];
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
			}
			
			//print_r($driverdetails);exit;
			// Passenger Settings
			if($passenger_id !='' && count($driverdetails) > 0)
			{

				$company_id = $driver_details[0]['get_companyid'];

				//$passenger_setting = $find_model->get_passenger_company($passenger_id);
				//$passenger_setting = $find_model->get_company_setting($company_id);
				$passenger_setting =1;
				if($passenger_setting == 1)
				{
					
					$result = $find_model->auto_savebooking($driverdetails,$_REQUEST,$passenger_id,$company_id);
					
					$company_all_currenttimestamp = $common_model->getcompany_all_currenttimestamp($company_id);
											$insert_array = array(
																	"trip_id" => $result['pass_logid'],
																	"available_drivers" 			=> $available_drivers,
																	"status" 	=> '0',
																	"rejected_timeout_drivers"		=> "",
																	"createdate"		=> $company_all_currenttimestamp,
																);								
					//Inserting to Transaction Table 
					$transaction = $common_model->insert(DRIVER_REQUEST_DETAILS,$insert_array);	
							/*if($result['result'] == 1)
							{

								$passenger_logid = $result['pass_logid'];
								//$passenger_logid = $passengerid[0]['id'];
								$api = Model::factory('api');
								$config_array = $api->select_site_settings(array('notification_settings'),SITEINFO);
								if(count($config_array) > 0)
								{
									$notification_time = $config_array[0]['notification_settings'];
								}
								else
								{
									$notification_time = 60;
								}

								$driver_model = Model::factory('driver');
								$driver_info = $driver_model->get_driver_profile_details($driver_details[0]['driver_id']);
								$name = $driver_info[0]['name'];
								$phone = $driver_info[0]['phone'];
								$device_id = $driver_info[0]['device_id'];
								$device_token = $driver_info[0]['device_token'];								
								$device_type = $driver_info[0]['device_type'];

								$booking_details = array(
								"pickup"=>$_REQUEST['cur_loc'],
								"drop"=>$_REQUEST['drop_loc'],
								"no_of_passengers"=>$_REQUEST['no_passengers'],
								"pickup_time"=>$_REQUEST['bookingtime'],
								"driver_id"=>$driver_details[0]['driver_id'],
								"passenger_id"=>'',
								"roundtrip"=>'',
								"passenger_phone"=>'',
								"bookedby"=>BOOK_BY_PASSENGER
								);
								$msg = array("message" => 'You have new trip request.Kindly response the request',"status" => 1,"Passenger_logid"=>$passenger_logid,"booking_details" =>$booking_details,"notification_time"=>$notification_time);

								Message::success(__('save_booking_success'));
								//echo __('save_booking_success');
								//$this->request->redirect('/passengers/dashboard');AIzaSyAlFdMOAPiPDdcGdJtrxPmdRNiyWPeAvdQ
								//---------------------------------- ANDROID ----------------------------------//
								//  $apikey = "AIzaSyBaXJNLQajj74IS1A1tvUk5i-rm4ekvBzM"; //for client.taximobility.com
								//$apiKey = "AIzaSyAkKhg71cHRWWzYxfvlLUGm0FEeKPlZ1Z0"; //for client.taximobility.com
								$apikey = "AIzaSyAlFdMOAPiPDdcGdJtrxPmdRNiyWPeAvdQ"; //first for taximobility
								//$apiKey = "AIzaSyBQzVb-gm8BqqmSXaD53Zw-Hnk0VKn1i90";//second
								//$apiKey = "AIzaSyCxqKQ8eqgIJFF3W8GUjT0x3n3IBPf3kCw";//third
								$registrationIDs = array($device_token);
								// Message to be sent
                                    
								if(!empty($registrationIDs))
								{
								// Set POST variables
								$url = 'https://android.googleapis.com/gcm/send';
								//print_r($registrationIDs);exit;
								$fields = array(
								'registration_ids'  => $registrationIDs,
								'data'              => array( "message" => $msg,"title" => SITE_NAME ),
								);

								$headers = array( 
								'Authorization: key=' . $apikey,
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

								}

								$message = array('status' => 1); 
								echo json_encode($message); exit;

							}
							else
							{
								echo json_encode($driver_details);exit;
							}*/

				}
				elseif($passenger_setting == '3')
				{
					echo "here1";exit;
					echo json_encode($driver_details[0]);exit;
				}
			

			}
			
			
				echo json_encode($driver_details);
				exit;

		}
	}
	
	function action_get_driver_details()
	{ ?>
		<script>
			jQuery(document).ready(function($) {

			  $('#banner-fade').bjqs({
			    height      : 260,
			    width       : 340,
		    	    left	: 40,	
			    responsive  : true
			  });

			});
		</script>
		<?php
		if(isset($_POST["driver_id"]))
		{
			$common_model = Model::factory('Commonmodel');
			$result = $common_model->get_driver_details($_POST["driver_id"]);
			//echo "<pre>";print_r($result);echo "</pre>";exit;
			$html = "";
			$chk = 0;
			if(count($result) > 0)
			{
				if(isset($result[0]) && count($result[0]) > 0)
				{
					$output ='<ul class="bjqs">';
					
					$taxi_image = $_SERVER['DOCUMENT_ROOT'].'/'.TAXI_IMG_IMGPATH.$result[0]['taxi_image'];
					if(file_exists($taxi_image) && $result[0]['taxi_image'] !='')
					{
					$taxi_image = URL_BASE.TAXI_IMG_IMGPATH.$result[0]['taxi_image'];
					}else{
					$taxi_image = URL_BASE."/public/".UPLOADS."/taxi_image/no-image.jpg";
					}
					$output .='<li><img src="'.$taxi_image.'" ></li>';	
		
					$count = $result[0]['taxi_sliderimage'];
					$serialize_count = unserialize($result[0]['taxi_serializeimage']);
					$taxi_id = $result[0]['taxi_id'];
					$j = 0;
					if(is_array($serialize_count))
					{
						foreach($serialize_count as $value)
						{
							if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/taxi_image/'.$taxi_id.'_'.$value.'.png'))
							{ 
							$image_path = URL_BASE.'/public/'.UPLOADS.'/taxi_image/'.$taxi_id.'_'.$value.'.png';
							$output .='<li><img src="'.$image_path.'" ></li>';	
							}						
						
						}

					}
					$output .='</ul>';

					
					$html ="<div id='banner-fade'>".$output."</div>";	
					$html .= "<div class='taxi_driver'>
								<dl class='dl-horizontal'>
								  <dt>".__('taxicompany')."</dt>
								  <dd>: ".ucfirst($result[0]['company_name'])."</dd>
								  <dt>".__('taxi_number')."</dt>
								  <dd>: ".$result[0]['taxi_no']."</dd>
								  <dt>".__('taxi_model')."</dt>
								  <dd>: ".ucfirst($result[0]['model_name'])."</dd>
								  <dt>".__('taxi_capacity')."</dt>
								  <dd>: ".$result[0]['taxi_capacity']."</dd>	
								  <dt>".__('maximum_luggage')."</dt>
								  <dd>: ".$result[0]['max_luggage']."</dd>								  							 
							</dl>";
						/*echo " <dt>".str_replace('%currency%',CURRENCY,__('taxi_fare_km'))."</dt>
								  <dd>: ".CURRENCY." ".$result[0]['taxi_fare_km']."</dd>";	*/
				
				
					if($result[0]['cancellation_nfree'] == 1)
					{
						$cancellation_fare = CURRENCY." ".$result[0]['cancellation_fare'];
					}
					else
					{
						$cancellation_fare = 'No';
					}
				$html .="<div class='other_features'>
					<label class='title_h3'>".__('fare_det')."</label>
					<dl class='dl-horizontal'>
						  <dt>".__('base_fare')."</dt>
						  <dd>: ".CURRENCY." ".ucfirst($result[0]['base_fare'])."</dd>
						   <dt>".__('min_fare')."</dt>
						  <dd>: ".CURRENCY." ".$result[0]['min_fare']."</dd>
						  <dt>".__('cancel_fare')."</dt>
						  <dd>: ".$cancellation_fare."</dd>
						  <dt>".sprintf(__('below_km'),$result[0]['below_above_km'])."</dt>
						  <dd>: ".CURRENCY." ".ucfirst($result[0]['below_km'])."</dd>
						  <dt>".sprintf(__('above_km'),$result[0]['below_above_km'])."</dt>
						  <dd>: ".CURRENCY." ".$result[0]['above_km']."</dd>
						  <dt>".__('wait_charge')."</dt>
						  <dd>: ".CURRENCY." ".$result[0]['waiting_time']."</dd>
					</dl>
					</div>";
					
				}	


				if(isset($result[1]) && count($result[1]) > 0 && count($result['label_name']) > 0)
				{
					$html .="<div class='other_features'>
					<label class='title_h3'>".__('Other Features')."</label>
					<dl class='dl-horizontal'>";

					foreach($result['label_name'] as $key => $value)
					{
						if($result[1][$result['label_name'][$key]['field_name']] !='') 
						{ 
							$html .="<dt>".$result['label_name'][$key]['field_labelname']."</dt><dd>: ".$result[1][$result['label_name'][$key]['field_name']]."</dd>";
							
						}
						else
						{
							$html .="<dt>".$result['label_name'][$key]['field_labelname']."</dt><dd>: ".__('not_specified')."</dd>";
						}
						

					}
					
					$html .="</dl>
					</div>";
				}	
				
				//Rating of the Driver			

				if(isset($result['comments']) && count($result['comments']) > 0)
				{
					$overall_rating = 0; $i=0;
					$html .="<div class='rating_driver'>";
								
						foreach($result['comments'] as $comments)
						{
							
							switch($comments['rating']){
								case 1: $star = "one";
										break;
								case 2: $star = "two";
										break;
								case 3: $star = "three";
										break;
								case 4: $star = "four";
										break;
								case 5: $star = "five";
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
							$passenger_image = $_SERVER['DOCUMENT_ROOT'].'/'.PASS_IMG_IMGPATH.$passenger_details[0]['profile_image'];
							if(file_exists($passenger_image) && $passenger_details[0]['profile_image']!='')
							{
								$img = URL_BASE.'public/'.UPLOADS.'/passenger/thumb_'.$passenger_details[0]['profile_image'];
							}else{
								$img = URL_BASE."/public/images/noimages.jpg";
							}
							
							if(($star != "") ||($comment != ""))
							{

								if($chk == 0)
								{	
									$html .="<label class='title_h3'>".__('recent_comments')."</label>";
									$chk = 1;
								}

								$html .="<div class='driver_comment comments_area'>
										<div class='comment_area_img'><img src=".$img." class='img-polaroid'/></div>
										<div class='comment_area_right'>
											<p class='pass_name'><a href='#'>".ucfirst($passenger_details[0]['name'])."</a></p>
											<p class='ratings ".$star."'></p>											
											<p class='comments'>".$comment."</p>
										</div>
									</div>";
							}
							$overall_rating += $comments['rating'];
							$i++;
						}
								
					$html .="</div>";
				}
				else
				{

				}
						
				$html .="</div>";
						
				
			}			
			else
			{
				$html = "";
			}
			echo $html;
			exit;
		}
	}
	
	public function action_get_motor_model()
	{
		if(isset($_POST['motor_type']))
		{
			$common_model = Model::factory('Commonmodel');
			$motor_model= $common_model->get_motor_model($_POST['motor_type']);
			$html = "";
			$html .='<label>'.__("taxi_model").'</label>
					<select name="taxi_model" id="taxi_model">
						<option value="">'.__("select_label").'</option>';
			foreach($motor_model as $list) { 
				$html .='<option value="'.$list["model_id"].'" >'.$list["model_name"].'</option>';
			} 
						 
			$html .='</select>';
			
			echo $html;
			exit;
		}
	}
	/** function for web booking **/
	public function action_webBooking()
	{
		 /**To check Whether the user is logged in or not**/
		if(!isset($this->session) ||  !$this->session->get('id') )	 
		{
			Message::error(__('login_access'));
			$this->request->redirect("/users/login/");
		}
		$motorid = 1;
		/**function to get the selected model fare details**/
		$getModelDetails = $this->findMdl->getmodel_details($motorid);
		$passengerId = $this->session->get('id');
		$passengerName = $this->session->get('passenger_name');
		$passengerEmail = $this->session->get('passenger_email');
		$passengerPhone = $this->session->get('passenger_phone');
		$passengerPhCode = $this->session->get('passenger_phone_code');
		$val = array();
		$val['driver_status'] = 'F';
		$driverList = $this->taxidispatchMdl->driver_status_details($val);
		$this->template->content = View::factory(USERVIEW.'web_booking')->bind('getmodel_details',$getModelDetails)->bind('driverList',$driverList)->bind('passengerId',$passengerId)->bind('passengerName',$passengerName)->bind('passengerEmail',$passengerEmail)->bind('passengerPhone',$passengerPhone)->bind('passengerPhCode',$passengerPhCode);
	}
	/** Function to get model fare details **/
	public function action_getModelFareDets()
	{
		if(isset($_POST['modelID'])) {
			$modelFares = $this->findMdl->modelDets($_POST['modelID']);
			$markers = array();
			$pickupTimezone = $this->findMdl->getpickupTimezone($_POST['passlat'],$_POST['passlong']);
			$currentTime = convert_timezone('now',$pickupTimezone);
			$driverList = $this->findMdl->getFreeDriverList($_POST['modelID'],$_POST['passlat'],$_POST['passlong'],$currentTime);
			$a=0;
			if(count($driverList) > 0)
			{
				 foreach($driverList as $v)
				{
					//print_r($v);
					for($b=0;$b<4;$b++)
					{
						if($b==0)
						{  
							$markers[$a][$b]= $v['latitude'];
						}
						if($b==1)
						{
							$markers[$a][$b]=$v['longitude'];
						}
						if($b==2)
						{ 
							//$markers[$a][$b]= '<div class="marker-info-win">'.'<div class="marker-inner-win"><span class="info-content"><b>'.__('driver_name').'</b> : '.$v['name'];
							$markers[$a][$b]= '<div class="info_content"><b>'.__('driver_name').'</b> : '.$v['driver_name'].'</div>';
						}
						if($b==3)
						{
							$markers[$a][$b]= PUBLIC_IMGPATH.'/taxi_location.png';
						}
						
					}
					$a++;
				 }  
			}
			$modelFares['driver_list'][] = $markers;
			$modelFares['drivers_count'] = count($driverList);
			echo json_encode($modelFares);
		}
		exit;
	}
	
	/** Booking save function **/
	public function action_bookingSubmit()
	{
		$post = Arr::map('trim', $this->request->post());
		$response = array();
		if($post) {
			$pickupTimezone = $this->findMdl->getpickupTimezone($post['pass_latitude'],$post['pass_longitude']);
			$currentTime = convert_timezone('now',$pickupTimezone);
			$post['currentTime'] = $currentTime;
			if(!empty($post['pickup_time'])) {
				$post['pickup_time'] = date("Y-m-d H:i:s",strtotime($post['pickup_time']));
			}
			$validator = $this->findMdl->validate_bookingForm($post);
			if($validator->check()){
				$this->commonmodel = Model::factory('commonmodel');
				/*$passengerCompany = $this->findMdl->get_passenger_company_id($post['passengerId']);
				$companyId = (!empty($passengerCompany)) ? $passengerCompany : 0;
				$currentTime = $this->commonmodel->getcompany_all_currenttimestamp($companyId);*/
				$promoValid = ($post['promocode'] != '') ? $this->findMdl->checkPromocode($post['passengerId'],$post['promocode'],$currentTime) : 1;
				if($promoValid == 1) {
					$passengerInTrip = $this->findMdl->check_passenger_in_trip($post['passengerId'],$currentTime);
					if($passengerInTrip > 0) {
						Message::error(__('passenger_in_journey'));
						$response['redirect'] = URL_BASE."booking.html";
					} else {
						list($result,$driverCnt,$tripId) = $this->findMdl->saveBooking($post);
						if($result != 1) {
							if($result == 2) {
								Message::success(__('api_request_disapatcher'));
							} else if($result == 3){
								Message::error(__('no_drivers_found'));
							}
							$response['redirect'] = URL_BASE."booking.html";
						} else {
							/*Message::success(__('api_request_confirmed_passenger'));
							$response['redirect'] = URL_BASE."booking.html"; */
							//1000 is multiplied to change the seconds to ms(millisecond)
							if($driverCnt < 4) {
								$timeout = ((ADMIN_NOTIFICATION_TIME * $driverCnt) + 25) * 1000;
							} else {
								$timeout = CONTINOUS_REQUEST_TIME;
							}
							//$response['driverCnt'] = $totalTime; */
							$notification_time = ADMIN_NOTIFICATION_TIME;									
							if($notification_time != 0 ){ $timeoutseconds = $notification_time;}else{$timeoutseconds = 15;}
							$microseconds = $timeout*1000000; //Seconds to microseconds 1 second = 1000000 
							$now = time();
							$i = 0;	
							if(!empty($tripId)) {
								while((time() - $now) < $timeout)
								{	
									$driver_status = $this->findMdl->get_request_status($tripId);	
									//print_r($driver_status);
									$driver_status_count=count($driver_status);
									if($driver_status_count >0)
									{
										$req_count=$driver_status_count*$timeoutseconds;
										$driver_reply = $driver_status[0]['status'];
										$trip_type = $driver_status[0]['trip_type'];//get booking type 1-Favourite booking, 0-Normal Booking
										$selected_driver_id = $driver_status[0]['selected_driver'];
										$available_drivers = explode(',',$driver_status[0]['total_drivers']);
										$rejected_timeout_drivers = explode(',',$driver_status[0]['rejected_timeout_drivers']);	
										$comp_result = array_diff($available_drivers, $rejected_timeout_drivers);
										
										$timeout=count($available_drivers)*25+20;
										if($timeout < CONTINOUS_REQUEST_TIME)
										{
											$timeout=CONTINOUS_REQUEST_TIME;
										}
										$microseconds=$timeout*1000000;
										//to get drivers company timestamp
										$company_det = $this->findMdl->get_company_id($selected_driver_id);
										if(count($company_det)>0){
											
											$company_all_currenttimestamp = $this->commonmodel->getcompany_all_currenttimestamp($company_det[0]['company_id']);
										}
										//condition to check driver not updated for above 30seconds if it is means we should change the request to next driver
										$driver_not_updated = $this->taxidispatchMdl->check_driver_not_updated($selected_driver_id,$company_all_currenttimestamp);
										$time_difference = strtotime($company_all_currenttimestamp) - strtotime($driver_not_updated);
										if($time_difference > 25 && count($comp_result) != 0 && $driver_reply != '4') {
											$get_request_dets=$this->taxidispatchMdl->check_new_request_tripid("","",$tripId,$selected_driver_id,$company_all_currenttimestamp,"");
										}
										//echo count($comp_result);
										if(count($comp_result) == 0)
										{
											$driver_reply  = 5;
										}

										if(!empty($driver_reply))
										{
											if($driver_reply == '3') 
											{
												Message::success(__('request_confirmed_passenger'));
												$response['redirect'] = URL_BASE."booking.html";
												break;
											}
											elseif($driver_reply == '4')
											{
												Message::success(__('driver_busy'));
												$response['redirect'] = URL_BASE."booking.html";
												break;
											}
											elseif($driver_reply == '5')
											{
												Message::success(__('driver_busy'));
												$response['redirect'] = URL_BASE."booking.html";
												break;
											}
										}
										usleep(5000000);
										$i = $i+5000000;
										if($i == $microseconds)
										{
												$update_trip_array  = array("status"=>'4');
												$result = $this->commonmodel->update(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$tripId);
												Message::success(__('driver_busy'));
												$response['redirect'] = URL_BASE."booking.html";
												break;											
										}							
									}
									else
									{
										Message::success(__('try_again'));
										$response['redirect'] = URL_BASE."booking.html";
										break;
									}										
								}
							}
						}
					}
				} else {
					$promoInvalidMsg = array();
					if($promoValid == 0){
						$promoInvalidMsg['promocode'] = __('invalid_promocode');
					} else if($promoValid == 2) {
						$promoInvalidMsg['promocode'] = __('promo_code_limit_exceed');
					} else if($promoValid == 3) {
						$promoInvalidMsg['promocode'] = __('promo_code_startdate');
					} else if($promoValid == 4) {
						$promoInvalidMsg['promocode'] = __('promo_code_expired');
					}
					$response['error'] = $promoInvalidMsg;
				}
			}
			else
			{
				$response['error'] = $validator->errors('errors');
			}
		}
		echo json_encode($response); exit;
	}
	
	/** Passenger Signup **/
	public function action_signup()
	{
		$post = Arr::map('trim', $this->request->post());
		$response = array(); $returncode = 1;
		if($post) {
			if(DEFAULT_SKIP_CREDIT_CARD) {
				$post['credit_card_number'] = preg_replace('/\s+/', '', $post['credit_card_number']);
			}
			$validator = $this->findMdl->validate_signupForm($post);
			if($validator->check()){
				if(DEFAULT_SKIP_CREDIT_CARD) {
					$preAuthorizeAmount = PRE_AUTHORIZATION_REG_AMOUNT;
					list($returncode,$paymentResult,$fcardtype) = $this->findMdl->creditcardPreAuthorization($post['first_name'],$post['credit_card_number'],$post['cvv'],$post['month'],$post['year'],$preAuthorizeAmount);
					if($returncode == 0)
					{
						//preauthorization with amount "1"
						$preAuthorizeAmount = PRE_AUTHORIZATION_RETRY_REG_AMOUNT;
						list($returncode,$paymentResult,$fcardtype)= $this->findMdl->creditcardPreAuthorization($post['first_name'],$post['credit_card_number'],$post['cvv'],$post['month'],$post['year'],$preAuthorizeAmount);
					}
				}
				if($returncode != 0) {
					if(DEFAULT_SKIP_CREDIT_CARD) {
						$post['paymentResult'] = $paymentResult;
						$post['preAuthorizeAmount'] = $preAuthorizeAmount;
						$post['fcardtype'] = $fcardtype;
					} else {
						$post['credit_card_number'] = "";
						$post['month'] = "";
						$post['year'] = "";
						$post['cvv'] = "";
						$post['paymentResult'] = "";
						$post['preAuthorizeAmount'] = 0;
						$post['fcardtype'] = "";
					}
					$result = $this->findMdl->passengerSignup($post);
					if($result == 1){
						$mobileNo = $post['country_code'].$post['mobile_number'];
						$p_password =$post['password'];
						$passName =$post['first_name'];
						//sending sms to passenger
						if(SMS == 1)
						{
							$commonmodel = Model::factory('commonmodel');
							$message_details = $commonmodel->sms_message_by_title('account_create_sms');
							if(count($message_details) > 0) {
								$to = $mobileNo;
								$message = $message_details[0]['sms_description'];
								$message = str_replace("##USERNAME##",$to,$message);
								$message = str_replace("##PASSWORD##",$p_password,$message);
								$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
								$commonmodel->send_sms($to,$message);
							}
						}
						//sending mail to passenger
						$replace_variables=array(REPLACE_LOGO=>URL_BASE.SITE_LOGO_IMGPATH.$this->domain_name.'_email_logo.png',REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$passName,REPLACE_MOBILE=>$mobileNo,REPLACE_PASSWORD=>$p_password,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT);
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
						$to = $post['email'];
						$from = $this->siteemail;
						$subject = __('pass_account_details')." - ".$this->app_name;	
						$redirect = "no";	
						$smtp_result = $commonmodel->smtp_settings();					
						if(!empty($smtp_result) && ($smtp_result[0]['smtp'] == 1))
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
						Message::success(__('signup_success'));
					} else {
						Message::error(__('try_again'));
					}
					$response['redirect'] = URL_BASE."dashboard.html";
				} else {
					if(DEFAULT_SKIP_CREDIT_CARD) {
						$errors['credit_card_number'] = $paymentResult;
					}
					$response['error'] = $errors;
				}
			}
			else
			{
				$errors = $validator->errors('errors');
				if(isset($errors['country_code'])) {
					unset($errors['mobile_number']);
				}
				$response['error'] = $errors;
			}
		}
		echo json_encode($response); exit;
	}
	/** Cancel trip **/
	public function action_cancelTrip()
	{
		$api_model = Model::factory('find');
		$this->commonmodel=Model::factory('commonmodel');
		$cancel_trip_array = $_POST;		
		$passenger_log_id = $cancel_trip_array['passenger_log_id'];
		$remarks = urldecode($cancel_trip_array['remarks']);
		$check_travelstatus = $api_model->check_travelstatus($passenger_log_id);
		if($check_travelstatus == -1)
		{
			$message = array("message" => __('invalid_trip'),"status"=>3);
			echo json_encode($message);exit;			
		}
		if($check_travelstatus == 4)
		{
			$message = array("message" => __('trip_already_canceled'), "status"=>-1);
			echo json_encode($message);exit;
		}			
		if($check_travelstatus == 2)
		{
			$message = array("message" => __('passenger_in_journey'), "status"=>-1);
			echo json_encode($message);exit;
		}
		
		$flag = 1;
		$trans_result = $api_model->check_tranc($passenger_log_id,$flag);
		if($trans_result == 1)
		{
			$message = array("message" => __('trip_fare_already_updated'), "status"=>-1);
			echo json_encode($message);exit;
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
						$phone_no=$api_model->get_passenger_phone_by_id($passenger_id);
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
					echo json_encode($message);exit;
				}
				else
				{
					$total = $api_model->get_passenger_cancel_faredetail($passenger_log_id);
					$passengerReferrDet = $api_model->check_passenger_referral_amount($passenger_id);
					$referralAmt = (isset($passengerReferrDet[0]['referral_amount'])) ? $passengerReferrDet[0]['referral_amount'] : 0;
					$reducAmt = ($referralAmt != 0) ? ($wallet_amount - $referralAmt) : $wallet_amount;
					//echo $passenger_id.">".$passenger_wallet[0]['wallet_amount'].">". $total;exit;
					if($cancel_trip_array['pay_mod_id'] == 3 || ($wallet_amount > 0 && $reducAmt >= $total)) // By cash
					{
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
							$result_sts_update = $this->commonmodel->update(PASSENGERS_LOG,$update_travel_status_array,'passengers_log_id',$passenger_log_id);
							/*$status_array  = array("status" => '4'); // Passenger Cancelled
							$result_sts_update = $api->update_table(DRIVER_REQUEST_DETAILS,$status_array,'trip_id',$passenger_log_id);*/
							$cancel_from = __('Cash');
							//to reduce the wallet amount while cancelling the trip
							if($wallet_amount >= $total){
								$balance_wallet_amount = $wallet_amount - $total;
								//update wallet amount in passenger table
								$update_wallet_array = array("wallet_amount" => $balance_wallet_amount);
								$wallet_update = $this->commonmodel->update(PASSENGERS,$update_wallet_array,'id',$passenger_id);
								$cancel_from = __('Wallet');
							}
							
							if(SMS == 1 && !empty($passenger_id))
							{
								$phone_no=$api_model->get_passenger_phone_by_id($passenger_id);
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
						echo json_encode($message);exit;
					}
					else
					{
						$card_type = '';
						$default = 'yes';
						$carddetails = $api_model->get_creadit_card_details($passenger_id,$card_type,$default);
						$no_default_card = $api_model->get_creadit_card_details($passenger_id,$card_type,"");
						//echo count($no_default_card);exit;
						 if(count($carddetails)>0)
						 {
							$payment_status = $this->cancel_trippayment($cancel_trip_array,$cancellation_nfree);
							//echo $payment_status;exit;
							$cancelArr = ($payment_status != 0) ? explode("#",$payment_status):'';
							$payment_status = isset($cancelArr[0]) ? $cancelArr[0] : 0;
							$cancelAmount = isset($cancelArr[1]) ? $cancelArr[1] : 0;
							if($payment_status == 0)
							{
								$gateway_response = isset($_SESSION['paymentresponse']['L_LONGMESSAGE0'])?$_SESSION['paymentresponse']['L_LONGMESSAGE0']:'Payment Failed';
								$message = array("message" => __('cancel_payment_failed'), "gateway_response" =>$gateway_response,"status"=>0);		
								echo json_encode($message);exit;
							}
							else if($payment_status == 1)
							{
								if(SMS == 1 && !empty($passenger_id))
								{
									$phone_no=$api_model->get_passenger_phone_by_id($passenger_id);
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
								echo json_encode($message);exit;				
							}
							else if($payment_status == -1)
							{
								$message = array("message" => __('invalid_trip'),"status"=>3);	
								echo json_encode($message);exit;
							}
						} else if (count($carddetails) == 0 && count($no_default_card) > 0) {
							$message = array("message" => __('passenger_has_no_default_creditcard'),"status"=>5);	
							echo json_encode($message);exit;
								
						} else {
								$message = array("message" => __('cancel_no_creditcard'),"status"=>4);	
								echo json_encode($message);exit;			
						}
					}
				}
			}
			else
			{
				$message = array("message" => __('invalid_trip'),"status"=>3);	
				echo json_encode($message);exit;
			}	
	}
	
	public function cancel_trippayment($values,$cancellation_nfree)
	{   
		$api_model = Model::factory('find');
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
						//$this->phone=$this->commonmodel->get_passengers_details($email,1);
						$message_details = $this->commonmodel->sms_message_by_title('payment_cancel');
						$phone = $passenger_log_details[0]['passenger_phone'];
						$to = $phone;
						$message = $message_details[0]['sms_description'];
						//$message = str_replace("##booking_key##",$passenger_log_id,$message);
						$message = str_replace("##SITE_NAME##",SITE_NAME,$message);								
						//$this->commonmodel->send_sms($to,$message);
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
	
	public function send_cancel_fare_mail_passenger($cancelFare=0, $passenger_name="", $pickup_location="", $to="")
    {
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
			REPLACE_COMPANYDOMAIN=>$this->app_name,
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
	
} // End Controller_Find class
