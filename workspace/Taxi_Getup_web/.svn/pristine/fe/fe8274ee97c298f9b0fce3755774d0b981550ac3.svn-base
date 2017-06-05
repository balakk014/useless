<?php defined('SYSPATH') OR die('No Direct Script Access');

class Model_Cronbooking extends Model
{


	public function cron_recurrentbooking()
	{
		// Get all recurrent booking details //
		
		// Next 24 hours //
		$date_query = "SELECT now()+interval 24 hour as check_datetime";
		$date_result = Db::query(Database::SELECT, $date_query)->execute();
		$next_datetime = $date_result[0]['check_datetime'];
		$next_datetime_split = explode(' ',$next_datetime);
		$next_date = $next_datetime_split[0];
		$next_time = $next_datetime_split[1];

		//$next_datetime = '2014-02-10 03:51:00';
		//$next_datetime_split = explode(' ',$next_datetime);
		//$next_date = $next_datetime_split[0];
		//$next_time = $next_datetime_split[1];
		// Next 24 hours //

		// Next Days //
		$day_query = "select DATE_FORMAT(NOW()+interval 24 hour,'%a') as days";
		$day_result = Db::query(Database::SELECT, $day_query)->execute();
		$next_day = strtoupper($day_result[0]['days']);
		//$next_days = 'TUE';
		// Next Days //

		
		function toDate($x){return date('Y-m-d', $x);}

		$start_datetime = $next_date.' 00:00:00';
		$end_datetime = $next_date.' 23:59:59';	
		
		// Get all recurrent booking details query //

		$sql = "SELECT  ".RECURR_BOOKING.".reid,".RECURR_BOOKING.".passengers_log_id,".RECURR_BOOKING.".frmdate,".RECURR_BOOKING.".todate,".RECURR_BOOKING.".days,
".RECURR_BOOKING.".excludedates,".RECURR_BOOKING.".specific_dates  FROM ".RECURR_BOOKING." left join ".COMPANY." ON ( ".RECURR_BOOKING.".companyid = ".COMPANY.".cid ) left join  ".PEOPLE." ON ( ".PEOPLE.".company_id = ".RECURR_BOOKING.".companyid ) WHERE ".PEOPLE.".status = 'A' and ".PEOPLE.".user_type='C' and ".COMPANY.".company_status='A' and ".RECURR_BOOKING.".frmdate <= now() and ".RECURR_BOOKING.".todate >= now() and ".RECURR_BOOKING.".reid NOT IN (select ".PASSENGERS_LOG.".recurrent_id from ".PASSENGERS_LOG." where (".PASSENGERS_LOG.".pickup_time between '$start_datetime' and '$end_datetime')) ";

		$recurrent_result = Db::query(Database::SELECT, $sql)->execute()->as_array();

		// Get all recurrent booking details query //

	
		if(count($recurrent_result) > 0)
		{
			foreach($recurrent_result as $recurrent_details)
			{
				
				$recurrent_id = $recurrent_details['reid'];
				$startDate = $recurrent_details['frmdate'];
				$endDate = $recurrent_details['todate'];

				$insert_booking = 'N';
	
				// Table entry in Booking Log Table //
				if($recurrent_details['days'] !='')	
				{
					$days =  unserialize($recurrent_details['days']);
				}
				else
				{	
					$days = '';
				}	
				
				if($recurrent_details['specific_dates'] != '')
				{
					$all_dates =  unserialize($recurrent_details['specific_dates']);		
				}
				else
				{
					$all_dates =  '';		
				}	


				if($all_dates =='' && $days == '')
				{

					/** Check Today is between fromdate and todate **/
					$checknext_query ="select '$next_date'  between '$startDate' and '$endDate' as checkdate";
					$checknext_result = Db::query(Database::SELECT, $checknext_query)->execute()->as_array();
					
					/** Check Today is between fromdate and todate **/

					/** Insert Today Booking **/
					if($checknext_result[0]['checkdate'] == 1)
					{

						// Booking key generator //
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

					       // Booking key generator //	

						// To get Recurrent Log details from Passenger Log Table //
						$recurrent_query = "SELECT * from ".RECURR_BOOKING." where ".RECURR_BOOKING.".reid ='$recurrent_id'"; 				

						$booking_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();			
						// To get Recurrent Log details from Passenger Log Table //

						// To get Passenger Log details from Passenger Log Table //
						$companytax_query = "SELECT company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid =".$booking_details[0]['companyid']; 				

			$companytax_details = Db::query(Database::SELECT, $companytax_query)->execute()->as_array();			

			$company_tax = $companytax_details[0]['company_tax'];
						
						// To get Passenger Log details from Passenger Log Table //

						// Insert into Passenger Log Table //	

						$pickup_datetime = $next_date.' '.$booking_details[0]['recurrent_pickuptime'];

						$insert_booking = 'S';

						$today_result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude',
						'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','search_city','notes_driver','faretype','fixedprice','luggage','bookby',
						'operator_id','additional_fields','travel_status','taxi_modelid','recurrent_type','recurrent_id','company_tax','account_id','accgroup_id'))->values(array($booking_key,$booking_details[0]['recurrent_passengerid'],$booking_details[0]['companyid'],$booking_details[0]['recurrent_pickuplocation'],$booking_details[0]['recurrent_pickuplatitude'],$booking_details[0]['recurrent_pickuplongitude'],$booking_details[0]['recurrent_droplocation'],$booking_details[0]['recurrent_droplatitude'],$booking_details[0]['recurrent_droplongitude'],$pickup_datetime,$booking_details[0]['recurrent_noofpassengers'],$booking_details[0]['recurrent_approxdistance'],$booking_details[0]['recurrent_approxduration'],$booking_details[0]['recurrent_approxfare'],$booking_details[0]['recurrent_city'],$booking_details[0]['recurrent_notes_driver'],$booking_details[0]['recurrent_faretype'],$booking_details[0]['recurrent_fixedprice'],$booking_details[0]['recurrent_luggage'],'2',$booking_details[0]['recurrent_operatorid'],$booking_details[0]['recurrent_additionalfields'],'0',$booking_details[0]['recurrent_modelid'],'2',$recurrent_id,$company_tax,$booking_details[0]['recurrent_accountid'],$booking_details[0]['recurrent_groupid']))->execute();	


						$ins_logid = $today_result[0];

						/* Create Log */		
						$company_id = $_SESSION['company_id'];			
						$user_createdby = $_SESSION['userid'];
						$log_message = __('log_message_added');
						$log_message = str_replace("PASS_LOG_ID",$ins_logid,$log_message); 
						$log_booking = __('log_booking_added');
						$log_booking = str_replace("PASS_LOG_ID",$ins_logid,$log_booking); 
						$log_status = $this->create_logs($ins_logid,$company_id,$user_createdby,$log_message,$log_booking);
						/* Create Log */


						// Insert into Passenger Log Table //
						
					}
					/** Insert Today Booking **/

				/** Insert booking based on fromdate and todate **/
					
				}
				/** daysofweek not empty **/
				else if($all_dates =='' && $days !='')
				{
					
					/** Check Today is between fromdate and todate **/
					$checknext_query ="select '$next_date'  between '$startDate' and '$endDate' as checkdate";
					$checknext_result = Db::query(Database::SELECT, $checknext_query)->execute()->as_array();
//					echo 'as'.$checknext_result[0]['checkdate'];
					/** Check Today is between fromdate and todate **/


					if($checknext_result[0]['checkdate'] == 1)
					{
						/** Insert Booking Today **/
						if(in_array($next_day,$days))
						{
							// Booking key generator //
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

						       // Booking key generator //	

							// To get Recurrent Log details from Passenger Log Table //
							$recurrent_query = "SELECT * from ".RECURR_BOOKING." where ".RECURR_BOOKING.".reid ='$recurrent_id'"; 				

							$booking_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();			
							// To get Recurrent Log details from Passenger Log Table //

							// To get Passenger Log details from Passenger Log Table //
							$companytax_query = "SELECT company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid =".$booking_details[0]['companyid'];
							$companytax_details = Db::query(Database::SELECT, $companytax_query)->execute()->as_array();			
							$company_tax = $companytax_details[0]['company_tax'];
							// To get Passenger Log details from Passenger Log Table //


							// Insert into Passenger Log Table //	

							$pickup_datetime = $next_date.' '.$booking_details[0]['recurrent_pickuptime'];

							$insert_booking = 'S';

							$today_result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude',
							'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','search_city','faretype','fixedprice','luggage','bookby',
							'operator_id','additional_fields','travel_status','taxi_modelid','recurrent_type','recurrent_id','company_tax','account_id','accgroup_id'))->values(array($booking_key,$booking_details[0]['recurrent_passengerid'],$booking_details[0]['companyid'],$booking_details[0]['recurrent_pickuplocation'],$booking_details[0]['recurrent_pickuplatitude'],$booking_details[0]['recurrent_pickuplongitude'],$booking_details[0]['recurrent_droplocation'],$booking_details[0]['recurrent_droplatitude'],$booking_details[0]['recurrent_droplongitude'],$pickup_datetime,$booking_details[0]['recurrent_noofpassengers'],$booking_details[0]['recurrent_approxdistance'],$booking_details[0]['recurrent_approxduration'],$booking_details[0]['recurrent_approxfare'],$booking_details[0]['recurrent_city'],$booking_details[0]['recurrent_faretype'],$booking_details[0]['recurrent_fixedprice'],$booking_details[0]['recurrent_luggage'],'2',$booking_details[0]['recurrent_operatorid'],$booking_details[0]['recurrent_additionalfields'],'0',$booking_details[0]['recurrent_modelid'],'2',$recurrent_id,$company_tax,$booking_details[0]['recurrent_accountid'],$booking_details[0]['recurrent_groupid']))->execute();	


							$ins_logid = $today_result[0];

							/* Create Log */		
							$company_id = $_SESSION['company_id'];			
							$user_createdby = $_SESSION['userid'];
							$log_message = __('log_message_added');
							$log_message = str_replace("PASS_LOG_ID",$ins_logid,$log_message); 
							$log_booking = __('log_booking_added');
							$log_booking = str_replace("PASS_LOG_ID",$ins_logid,$log_booking); 
							$log_status = $this->create_logs($ins_logid,$company_id,$user_createdby,$log_message,$log_booking);
							/* Create Log */


							// Insert into Passenger Log Table //

						}
						/** Insert Booking Today **/
					}
				}
				/** daysofweek not empty **/
				/** all_dates not empty **/
				else
				{
					/** Check Tommorrow is between fromdate and todate **/
					$checknext_query ="select '$next_date'  between '$startDate' and '$endDate' as checkdate"; echo '<br>';
					$checknext_result = Db::query(Database::SELECT, $checknext_query)->execute()->as_array();
					/** Check Tommorrow is between fromdate and todate **/


					$specific_dates = implode(',',$all_dates);


					/** Check Tommorrow is exist in specific dates **/
					$checknext_query2 ="select FIND_IN_SET('$next_date', '$specific_dates' ) as finddate";
					$checknext_result2 = Db::query(Database::SELECT, $checknext_query2)->execute()->as_array();
				//	echo 'as1'.$checknext_result2[0]['finddate'];echo '<br>';
				//	echo 'as2'.$checknext_result[0]['checkdate'];

					/** Check Tommorrow is exist in specific dates **/

					/** Insert Today Booking **/
					if($checknext_result[0]['checkdate'] == 1 && $checknext_result2[0]['finddate'] >= 1)
					{

						//echo 'Ok';// Booking key generator //
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

					       // Booking key generator //	

						// To get Recurrent Log details from Passenger Log Table //
						$recurrent_query = "SELECT * from ".RECURR_BOOKING." where ".RECURR_BOOKING.".reid ='$recurrent_id'"; 				

						$booking_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();			
						// To get Recurrent Log details from Passenger Log Table //


						// To get Passenger Log details from Passenger Log Table //
						$companytax_query = "SELECT company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid =".$booking_details[0]['companyid'];
						$companytax_details = Db::query(Database::SELECT, $companytax_query)->execute()->as_array();			
						$company_tax = $companytax_details[0]['company_tax'];
						// To get Passenger Log details from Passenger Log Table //

						// Insert into Passenger Log Table //	

						$pickup_datetime = $next_date.' '.$booking_details[0]['recurrent_pickuptime'];

						$insert_booking = 'S';

						$today_result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude',
						'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','search_city','faretype','fixedprice','luggage','bookby',
						'operator_id','additional_fields','travel_status','taxi_modelid','recurrent_type','recurrent_id','company_tax','account_id','accgroup_id'))->values(array($booking_key,$booking_details[0]['recurrent_passengerid'],$booking_details[0]['companyid'],$booking_details[0]['recurrent_pickuplocation'],$booking_details[0]['recurrent_pickuplatitude'],$booking_details[0]['recurrent_pickuplongitude'],$booking_details[0]['recurrent_droplocation'],$booking_details[0]['recurrent_droplatitude'],$booking_details[0]['recurrent_droplongitude'],$pickup_datetime,$booking_details[0]['recurrent_noofpassengers'],$booking_details[0]['recurrent_approxdistance'],$booking_details[0]['recurrent_approxduration'],$booking_details[0]['recurrent_approxfare'],$booking_details[0]['recurrent_city'],$booking_details[0]['recurrent_faretype'],$booking_details[0]['recurrent_fixedprice'],$booking_details[0]['recurrent_luggage'],'2',$booking_details[0]['recurrent_operatorid'],$booking_details[0]['recurrent_additionalfields'],'0',$booking_details[0]['recurrent_modelid'],'2',$recurrent_id,$company_tax,$booking_details[0]['recurrent_accountid'],$booking_details[0]['recurrent_groupid']))->execute();	


						$ins_logid = $today_result[0];

						/* Create Log */		
						$company_id = $_SESSION['company_id'];			
						$user_createdby = $_SESSION['userid'];
						$log_message = __('log_message_added');
						$log_message = str_replace("PASS_LOG_ID",$ins_logid,$log_message); 
						$log_booking = __('log_booking_added');
						$log_booking = str_replace("PASS_LOG_ID",$ins_logid,$log_booking); 
						$log_status = $this->create_logs($ins_logid,$company_id,$user_createdby,$log_message,$log_booking);
						/* Create Log */


						// Insert into Passenger Log Table //
						
					}
					/** Insert Today Booking **/


				}
				/** all_dates not empty **/


				// Table entry in Booking Log Table //
				}

			}

			echo 'Recurrent Booking will be created successfully.';exit;
		}


		public function cron_autodispatch($trip_id='')
		{
			
			// Auto Dispatch for booking before 2 hrs of pickup time //
			/*
			$startdate_query = "SELECT now()+interval 2 hour as startdate,now()+interval 4 hour as enddate";
			$startdate_result = Db::query(Database::SELECT, $startdate_query)->execute()->as_array();			

			$start_datetime = $startdate_result[0]['startdate']; 
			$end_datetime = $startdate_result[0]['enddate']; 
			*/
			/* 24 hours pending Task */	
			$current_time = date("Y-m-d H:i:s", strtotime('-2 hours', strtotime(date('Y-m-d H:i:s'))));

			$now = strtotime(convert_timezone('now',TIMEZONE));
			//$start_datetime = date("Y-m-d H:i:s", strtotime('+1 hours', $now));
			$start_datetime = $current_time;
			$end_datetime = date("Y-m-d H:i:s", strtotime('+2 hours', $now));

			$api = Model::factory('api');
			
			//Get the Passenger Log details to assign the driver //
			if($trip_id == '')
			{
				 $booking_sql = "SELECT * from ".PASSENGERS_LOG." left join ".TBLALGORITHM."  on  ".TBLALGORITHM.".alg_company_id = ".PASSENGERS_LOG.".company_id  where ".PASSENGERS_LOG.".pickup_time >= '$start_datetime'  and driver_reply='C'  group by ".PASSENGERS_LOG.".passengers_log_id ";
			}
			else
			{
				 $booking_sql = "SELECT * from ".PASSENGERS_LOG." left join ".TBLALGORITHM."  on  ".TBLALGORITHM.".alg_company_id = ".PASSENGERS_LOG.".company_id  where ".PASSENGERS_LOG.".passengers_log_id = '$trip_id'  and (travel_status='9' and driver_reply='C') ";
			}


				$booking_results = Db::query(Database::SELECT, $booking_sql)->execute()->as_array();			
				
				if(count($booking_results) > 0)
				{
				
					foreach($booking_results as $booking_details1)
					{

						$pass_logid = $booking_details1['passengers_log_id'];
						$company_id = $booking_details1['company_id'];
						$operator_id = $booking_details1['operator_id'];
						$cancelled_driverid = $booking_details1['driver_id'];

						$booking_details = $this->get_bookingdetails($pass_logid,$company_id);

						$latitude = $booking_details[0]["pickup_latitude"];
						$longitude = $booking_details[0]["pickup_longitude"];
						$miles = '';
						$no_passengers = $booking_details[0]["no_passengers"];
						$taxi_fare_km = $booking_details[0]["min_fare"];
						$taxi_model = $booking_details[0]["taxi_modelid"];
						$taxi_type = ''; 
						$maximum_luggage = $booking_details[0]["luggage"];
						$company_id = $booking_details[0]["company_id"];
						$cityname = '';			
						$search_driver = '';		

						$driver_details = $this->search_driver_location($latitude,$longitude,$miles,$no_passengers,$_REQUEST,$taxi_fare_km,$taxi_model,$taxi_type,$maximum_luggage,$cityname,$pass_logid,$company_id,$search_driver,$cancelled_driverid);

						if(count($driver_details) > 0)
						{
							$a=1;
							foreach($driver_details as $key => $value)
							{
								$prev_min_distance='10000~0~0~0';
								$taxi_id='';
								$temp_driver=0;
								$nearest_key=0;
								$prev_key=0;

								$total_count=count($driver_details);
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
									$taxi_id=$value['taxi_id'];
									$nearest_driver=$driver_details[$nearest_key]['driver_id'];
									$taxi_id=$driver_details[$nearest_key]['taxi_id'];
									$distance_miles=$driver_details[$nearest_key]['distance_miles'];
								}
								$a++;
							}
											
							$driver_id = $driver_details[0]['driver_id'];
							$taxi_id = $driver_details[0]['id'];
							//$miles_to_km = round(($driver_details[0]['distance_miles'] * 1.609344),2);
							$miles_to_km = round(($distance_miles * 1.609344),2);
							$driver_away_in_km = (ceil($miles_to_km*100)/100);

							$common_model = Model::factory('commonmodel');

							$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);	
							//$current_datetime =	date('Y-m-d H:i:s');	
							$duration ='+1 minutes';
							$current_datetime = date('Y-m-d H:i:s', strtotime($duration, strtotime($current_datetime)));

							$updatequery = " UPDATE ".PASSENGERS_LOG." SET driver_id='".$driver_id."',taxi_id='".$taxi_id."',travel_status='7',driver_reply='',msg_status='U',dispatch_time='$current_datetime' wHERE passengers_log_id ='". $pass_logid."'";						
							$updateresult = Db::query(Database::UPDATE, $updatequery)
				   			 ->execute();

							

						$passenger_logid = $pass_logid;

						$passenger_details = $this->get_bookingdetails($pass_logid,$company_id);
						
						$bookinglog_details = $api->get_passengerlog_notify($passenger_logid);

						$company_dispatch = DB::select()->from(TBLALGORITHM)
									->where('alg_company_id','=',$company_id)
									->order_by('aid','desc')
									->limit(1)
									->execute()
									->as_array();


						$tdispatch_type = $company_dispatch[0]['labelname'];
						//$match_vehicletype = $company_dispatch[0]['match_vehicletype'];
						$hide_customer = $company_dispatch[0]['hide_customer'];
						$hide_droplocation = $company_dispatch[0]['hide_droplocation'];
						//$hide_fare = $company_dispatch[0]['hide_fare'];


						/* Create Log */		
						$userid = $operator_id;			
						$log_message = __('log_message_dispatched');
						$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
						$log_booking = __('log_booking_dispatched');
						$log_booking = str_replace("DRIVERNAME",$driver_details[0]['name'],$log_booking); 
						$log_status = $this->create_logs($passenger_logid,$company_id,$userid,$log_message,$log_booking);
						?>

						<?php 
						/* Create Log */
						$time = date ('H:i:s',strtotime($passenger_details[0]['pickup_time']));
	
									
								$cityname = "";
								$driver_away_in_km = round($driver_away_in_km,2);
								
								$taxi_speed=$api->get_taxi_speed($bookinglog_details[0]['taxi_id']);
								$estimated_time = $api->estimated_time($driver_away_in_km,$taxi_speed);	
															
								$notes = $bookinglog_details[0]['notes_driver'];

						$exist_request = $this->exist_request($pass_logid);
						if ($exist_request == 1) {
						$delete_exist_request = $common_model->delete(DRIVER_REQUEST_DETAILS, 'trip_id', $pass_logid);
						}

						/***** Insert the druiver details to driver request table ************/
						$insert_array = array(
							"trip_id" => $pass_logid,
							"available_drivers" => $driver_id,
							"total_drivers" => $driver_id,
							"selected_driver" => $driver_id,
							"status"  => '0',
							"rejected_timeout_drivers" => "",
							"createdate" => $current_datetime, 	);								
						//Inserting to Transaction Table 
						$transaction = $common_model->insert(DRIVER_REQUEST_DETAILS,$insert_array);	
						/*$detail = array("passenger_tripid"=>$pass_logid,"notification_time"=>"");
						$msg = array("message" => __('api_request_confirmed_passenger'),"status" => 1,"detail"=>$detail);
						*/
						//Message::success(__('save_booking_success'));

	
					}

					}

				}

			if($trip_id == '')
			{
				echo 'Recurrent booking request has been dispatched to driver.';exit;
			}
			else
			{
				return 1;	
			}
				
			//Get the Passenger Log details to assign the driver //



			// Auto Dispatch for booking before 3 hrs of pickup time //

		}



		public function cron_autodispatch_trips()
		{
			

			//$this->passenger_notify_laterbooking('755',1);
			// Auto Dispatch for booking before 2 hrs of pickup time //
			//echo 'Test';exit;
			$startdate_query = "SELECT now() as startdate,now()+interval 30 minute as enddate";
			$startdate_result = Db::query(Database::SELECT, $startdate_query)->execute()->as_array();			

			$start_datetime = $startdate_result[0]['startdate']; 
			$end_datetime = $startdate_result[0]['enddate']; 
			$last_daytime = date('Y-m-d h:i:s', strtotime("-1 day"));

			$api = Model::factory('api');
			
			//Get the Passenger Log details to assign the driver //

				$booking_sql = "SELECT * from ".PASSENGERS_LOG." left join ".TBLALGORITHM."  on  ".TBLALGORITHM.".alg_company_id = ".PASSENGERS_LOG.".company_id  where (".PASSENGERS_LOG.".pickup_time between '$start_datetime' and '$end_datetime') and driver_id=0 and labelname='1' ";

				$booking_sql = "SELECT * from passengers_log where (pickup_time between '$start_datetime' and '$end_datetime') and driver_id=0 and bookingtype=2 and travel_status=0 and passengers_id!=0 and passengers_id = (select passengers_id from passengers_log where travel_status NOT IN ('2','3','5','9') and (pickup_time between '$last_daytime' and '$end_datetime') limit 1 )";

				/*$booking_sql = "SELECT * from passengers_log where passengers_log_id='781'";*/

				$booking_results = Db::query(Database::SELECT, $booking_sql)->execute()->as_array();			
				
				if(count($booking_results) > 0)
				{
				
					foreach($booking_results as $booking_details1)
					{

						$pass_logid = $booking_details1['passengers_log_id'];
						$company_id = $booking_details1['company_id'];
						$operator_id = $booking_details1['operator_id'];

						$booking_details = $this->get_bookingdetails($pass_logid,$company_id);

						$latitude = $booking_details[0]["pickup_latitude"];
						$longitude = $booking_details[0]["pickup_longitude"];
						$miles = '';
						$no_passengers = $booking_details[0]["no_passengers"];
						$taxi_fare_km = $booking_details[0]["min_fare"];
						$taxi_model = $booking_details[0]["taxi_modelid"];
						$taxi_type = ''; 
						$maximum_luggage = $booking_details[0]["luggage"];
						$company_id = $booking_details[0]["company_id"];
						$cityname = '';			
						$search_driver = '';		

						$driver_details = $this->search_driver_location($latitude,$longitude,$miles,$no_passengers,$_REQUEST,$taxi_fare_km,$taxi_model,$taxi_type,$maximum_luggage,$cityname,$pass_logid,$company_id,$search_driver,'');

						if(count($driver_details) > 0)
						{	$a=1;
							foreach($driver_details as $key => $value)
							{
								$prev_min_distance='10000~0~0~0';
								$taxi_id='';
								$temp_driver=0;
								$nearest_key=0;
								$prev_key=0;
								
								$total_count=count($driver_details);
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
									$taxi_id=$value['taxi_id'];
									$nearest_driver=$driver_details[$nearest_key]['driver_id'];
									$taxi_id=$driver_details[$nearest_key]['taxi_id'];
									$distance_miles=$driver_details[$nearest_key]['distance_miles'];
								}
								$a++;
							}
											
							$driver_id = $driver_details[0]['driver_id'];
							//$taxi_id = $driver_details[0]['id'];
							//$miles_to_km = round(($driver_details[0]['distance_miles'] * 1.609344),2);
							$miles_to_km = round(($distance_miles * 1.609344),2);
							$driver_away_in_km = (ceil($miles_to_km*100)/100);

							$common_model = Model::factory('commonmodel');

							$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);	
							//$current_datetime =	date('Y-m-d H:i:s');	
							$duration ='+1 minutes';
							$current_datetime = date('Y-m-d H:i:s', strtotime($duration, strtotime($current_datetime)));

							$updatequery = " UPDATE ".PASSENGERS_LOG." SET driver_id='".$driver_id."',taxi_id='".$taxi_id."',travel_status='7',driver_reply='',msg_status='U',dispatch_time='$current_datetime' wHERE passengers_log_id ='". $pass_logid."'";						
							$updateresult = Db::query(Database::UPDATE, $updatequery)
				   			 ->execute();

							

						$passenger_logid = $pass_logid;

						$passenger_details = $this->get_bookingdetails($pass_logid,$company_id);
						
						$bookinglog_details = $api->get_passengerlog_notify($passenger_logid);

						$company_dispatch = DB::select()->from(TBLALGORITHM)
									->where('alg_company_id','=',$company_id)
									->order_by('aid','desc')
									->limit(1)
									->execute()
									->as_array();


						$tdispatch_type = $company_dispatch[0]['labelname'];
						//$match_vehicletype = $company_dispatch[0]['match_vehicletype'];
						$hide_customer = $company_dispatch[0]['hide_customer'];
						$hide_droplocation = $company_dispatch[0]['hide_droplocation'];
						//$hide_fare = $company_dispatch[0]['hide_fare'];


						/* Create Log */		
						$userid = $operator_id;			
						$log_message = __('log_message_dispatched');
						$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
						$log_booking = __('log_booking_dispatched');
						$log_booking = str_replace("DRIVERNAME",$driver_details[0]['name'],$log_booking); 
						$log_status = $this->create_logs($passenger_logid,$company_id,$userid,$log_message,$log_booking);
						?>

						<?php 
						/* Create Log */
						$time = date ('H:i:s',strtotime($passenger_details[0]['pickup_time']));
	
									
								$cityname = "";
								$driver_away_in_km = round($driver_away_in_km,2);
								
								$taxi_speed=$api->get_taxi_speed($bookinglog_details[0]['taxi_id']);
								$estimated_time = $api->estimated_time($driver_away_in_km,$taxi_speed);	
															
								$notes = $bookinglog_details[0]['notes_driver'];
						/***** Insert the druiver details to driver request table ************/
							$insert_array = array(
								"trip_id" => $pass_logid,
								"available_drivers" => $driver_id,
								"total_drivers" => $driver_id,
								"selected_driver" => $driver_id,
								"status" => '0',
								"rejected_timeout_drivers" => "",
								"createdate"		=> $current_datetime,
							);								
						//Inserting to Transaction Table 
						$transaction = $common_model->insert(DRIVER_REQUEST_DETAILS,$insert_array);	
						$detail = array("passenger_tripid"=>$pass_logid,"notification_time"=>"");
						$msg = array("message" => __('api_request_confirmed_passenger'),"status" => 1,"detail"=>$detail);

						if($driver_id !='' && $driver_id!=0)
						{
							$this->passenger_notify_laterbooking($pass_logid,1);
						}
						//Message::success(__('save_booking_success'));

	
					}

					}

				}

			echo 'Recurrent booking request has been dispatched to driver.';exit;
				
			//Get the Passenger Log details to assign the driver //



			// Auto Dispatch for booking before 3 hrs of pickup time //

		}


		public function get_driver_profile_details($id="")
		{
			$sql = "SELECT * FROM ".PEOPLE." WHERE id = '$id' ";                      
			return Db::query(Database::SELECT, $sql)
				->execute()
				->as_array(); 	
		}

		public function search_driver_location($lat,$long,$distance = NULL,$no_passengers,$request,$taxi_fare_km,$taxi_model,$taxi_type,$maximum_luggage,$city_name,$sub_log_id,$company_id,$search_driver,$cancelled_driverid)
		{
			$unit = 1;
			$distance = "";
			$unit_conversion = "";
			$remove_driver_list = array();
			/*if($sub_log_id !='')
			{
				$get_passenger_driverid = $this->unset_driver_list($sub_log_id);

				if(count($get_passenger_driverid) > 0)
				{
					foreach($get_passenger_driverid as $key => $value)
					{
						$remove_driver_list[] = $value['driver_id'];
					}
				}	
				else
				{
					$remove_driver_list = array();
				}

			}*/

			$assigned_driver = $this->free_availabletaxisearch_list_web($no_passengers,$request,$company_id);

			$add_field = "";		
			/*
			if($request){
				//$add = array();
				foreach($additional_fields as $res){
					//$add_field[] = $post_value_array[$res['field_name']];
					$fi_n = $res['field_name'];
					if(isset($request[$fi_n])){
						//$add_field[$fi_n] = $post_value_array[$fi_n];
						if($request[$fi_n]!=""){
							$add_field .= " AND adds.`".$fi_n."`='".$request[$fi_n]."'";
						}
					}
			
				}
					///echo $add_field;
			}*/
			$where = ' ';

			if($taxi_model){
				$where.= " AND taxi.`taxi_model`='".$taxi_model."' ";
			}
			if($taxi_type){
				$where.= " AND taxi.`taxi_type`='".$taxi_type."' ";
			}
			if($maximum_luggage){
				$where.= " AND taxi.`max_luggage`>='".$maximum_luggage."' ";
			}
			
			$driver_list = '';
			$driver_count = '';
			$driver_list_array = array();

			foreach($assigned_driver as $key => $value)
			{
				$driver_list_array[] = $value['id'];
			}	

			if($sub_log_id !='')
			{
				$driver_arraylist = array_diff($driver_list_array,$remove_driver_list); 	

				foreach($driver_arraylist as $key => $value)
				{
					$driver_count = 1;
					$driver_list .= "'".$value."',";
				}	
			}		
			else
			{		
				foreach($assigned_driver as $key => $value)
				{
					$driver_count = 1;
					$driver_list .= "'".$value['id']."',";
				}	
			}


			if($driver_count > 0)
			{
				$driver_list = substr_replace($driver_list ,"",-1);
			}
			else
			{
				$driver_list = "''";
			}


			$additional_field_join = "";
			/*if($add_field != "")
			{
				$additional_field_join = "JOIN ".ADDFIELD." as adds ON tmap.`mapping_taxiid`=adds.`taxi_id`";
			}*/

			$driver_like = '';
			if($search_driver)
			{
				$driver_like = "  and name LIKE  '%$search_driver%' ";
			}

			$current_time = convert_timezone('now',TIMEZONE);
			$current_date = explode(' ',$current_time);
			$start_time = $current_date[0].' 00:00:00';
			$end_time = $current_date[0].' 23:59:59';

			if($unit == '0')
			{
				$unit_conversion = '*1.609344';
			}

			if($distance)
			{
				$distance_query = "HAVING distance <='$distance'";
			}
			else
			{
				$distance_query = "HAVING distance <='".DEFAULTMILE."'";
			}	

			$cancelled_query = "";
			if($cancelled_driverid !='')
			{
				$cancelled_query = "  people.id!='$cancelled_driverid' and ";
			}

			$query =" select list.name as name,list.driver_id as driver_id,list.phone as phone,list.profile_picture as d_photo,list.id as id,list.latitude as latitude,list.longitude as longitude,list.status as status,list.distance as distance,list.distance as distance_miles,comp.company_name as company_name,taxi.taxi_no as taxi_no,taxi.taxi_image as taxi_image,taxi.taxi_capacity as taxi_capacity,taxi.taxi_id as taxi_id from ( SELECT people.name,people.profile_picture,people.phone,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515) AS distance,(TIME_TO_SEC(TIMEDIFF('$current_time',driver.update_date))) AS updatetime_difference FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where $cancelled_query people.login_status='S' $distance_query  AND driver.status='F' AND driver.shift_status='IN' and driver_id IN ($driver_list) order by distance ) as list JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model` JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid` $additional_field_join where tmap.mapping_startdate <='$current_time' AND updatetime_difference  <= '".LOCATIONUPDATESECONDS."' AND  tmap.mapping_enddate >='$current_time'  AND tmap.`mapping_status`='A' ".$where.$add_field.$driver_like." group by list.driver_id";


			$result = Db::query(Database::SELECT, $query)
				   			 ->execute()
							 ->as_array();

		
			return $result;
	
		}	


		public function unset_driver_list($log_id)
		{

			$result = DB::select('driver_id')->from(PASSENGERS_LOG)->where('sub_logid','=',$log_id)->order_by('passengers_log_id','asc')
				->execute()
				->as_array();
			  return $result;
		}

		public function free_availabletaxisearch_list_web($no_passengers = '',$request = '',$company_id ='')
		{
			$Commonmodel = Model::factory('Commonmodel');			

			//print_r($request);
			$where_cond = '';

		    /*if($field_count > 0)
		    {
			    for($i=0; $i<$field_count; $i++)
			    { 	
			    		$field_name = $additional_fields[$i]['field_name'];
			    		
					foreach($request as $key => $value)
					{	
						if(($key == $field_name) && ($value !=''))
						{
							$where_cond .= " and ".$key." = '".$value."'";
						}
					}
			     }
		    }	*/

		
			if(($no_passengers != null) && ($no_passengers != 0))
			{
				$capacity_where = " AND taxi_capacity >= $no_passengers";
			}
			else
			{
				$capacity_where = '';
			}
		

			if(isset($request['taxi_fare_km']) && $request['taxi_fare_km'] !='')
			{
				//$taxifare_where = " AND taxi_fare_km <=".$request['taxi_fare_km'];
			}
			else
			{
				//$taxifare_where = '';
			}
		
			if(isset($request['motor_company']) && $request['motor_company'] !='')
			{
				//$taxitype_where = " AND taxi_type ='".$request['motor_company']."'";
				$taxitype_where = " AND taxi_type ='1'";
			}
			else
			{
				$taxitype_where = '';
			}
		
			if(isset($request['motor_model'])  && ($request['motor_model'] !=''))
			{
				$taximodel_where = " AND taxi_model ='".$request['motor_model']."'";
			}
			else
			{
				$taximodel_where = '';
			}
				
			$current_time = $Commonmodel->getcompany_all_currenttimestamp($company_id);
			$current_date = explode(' ',$current_time);
			$start_time = $current_date[0].' 00:00:01';
			$end_time = $current_date[0].' 23:59:59';	
		
			//$cuurentdate = date('Y-m-d H:i:s');
			//$enddate = date('Y-m-d').' 23:59:59';
			
			$company_condition="";
			if($company_id){
				$company_condition = "AND taximapping.mapping_companyid = '".$company_id."' AND people.company_id = '".$company_id."' AND taxi.taxi_company = '".$company_id."'";
			}

	
			 $sql ="SELECT people.id,taxi.taxi_id  ,(select check_package_type from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company  order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM ".TAXI." as taxi JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid WHERE people.status = 'A' 	AND taxi.taxi_status = 'A' AND taxi.taxi_availability = 'A' AND people.availability_status = 'A'  AND people.booking_limit > (SELECT COUNT( passengers_log_id ) FROM  ".PASSENGERS_LOG." WHERE driver_id = people.id AND  `createdate` >='".$start_time."' AND  `travel_status` =  '1') AND taximapping.mapping_status = 'A' $company_condition AND company.company_status='A' $capacity_where AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time'  group by taxi_id Having ( check_package_type = 'T' or upgrade_expirydate >='$current_time' )";


			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
				return $results;
		}

		public static function taxi_additionalfields()
		{

			$result = DB::select()->from(MANAGEFIELD)->where('field_status','=','A')->order_by('field_order','asc')
				->execute()
				->as_array();
			  return $result;
		}

		public function get_bookingdetails($pass_logid,$company_id)
		{

			$query = "SELECT *,(select company_name from ".COMPANY." where cid=".PASSENGERS_LOG.".company_id) as company_name,".PASSENGERS_LOG.".passengers_log_id as pass_logid,(select name from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_name,(select email from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_email,(select phone from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_phone,(select min_fare from ".MOTORMODEL." where ".MOTORMODEL.".model_id=".PASSENGERS_LOG.".taxi_modelid) as min_fare FROM ".PASSENGERS_LOG."  where ".PASSENGERS_LOG.".passengers_log_id ='$pass_logid' AND ".PASSENGERS_LOG.".company_id='$company_id'";


			$result = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();			

			return $result;
		}	

		public static function create_logs($booking_logid='',$company_id='',$log_userid='',$log_message='',$log_booking='')	
		{
			$Commonmodel = Model::factory('Commonmodel');			
			//$user_createdby = $_SESSION['userid'];
			$current_time = $Commonmodel->getcompany_all_currenttimestamp($company_id);


			$result = DB::insert(LOGS, array('booking_logid','log_userid','log_message','log_booking','log_createdate'))
				->values(array($booking_logid,$log_userid,$log_message,$log_booking,$current_time))
				->execute();

			return $result;
		}
		
	public function get_timeout_cancel_trips()
	{
		$query = "DELETE FROM ".PASSENGERS_LOG." where travel_status='6' and driver_reply='C' order by passengers_log_id";

		$result = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return $result;
	}
	
	public function delete_driver_request_details()
	{ 
		// Get filter unwanted trip_id from DRIVER_REQUEST_DETAILS table
		$get_unwanted_trip_ids=$this->get_unwanted_trip_ids();
		// Get filter unwanted passengers_log_id from PASSENGERS_LOG table
		$get_unwanted_log_ids=$this->get_unwanted_log_ids($get_unwanted_trip_ids);

		if($get_unwanted_trip_ids){
			$delete_trip_id = "DELETE FROM ".DRIVER_REQUEST_DETAILS." WHERE `trip_id` IN ($get_unwanted_trip_ids)";
			//$result = Db::query(Database::DELETE, $delete_trip_id)->execute();
		}
		if($get_unwanted_log_ids){
			$delete_log_id = "DELETE FROM ".PASSENGERS_LOG." WHERE `passengers_log_id` IN ($get_unwanted_log_ids)";
			//$result = Db::query(Database::DELETE, $delete_log_id)->execute();
		}
		echo 'Records deleted in Driver request table';
		exit;
	}
	
	public function get_unwanted_trip_ids()
	{
		$query="SELECT trip_id FROM ".DRIVER_REQUEST_DETAILS." WHERE `status` NOT IN ('0','1','3','6')";
		//echo $query;exit;
		$result = Db::query(Database::SELECT, $query)
				->execute();
 
		$r=array();
		$trip_id="";
		if(count($result)>0){
			foreach($result as $p){
				$r[]="'".$p['trip_id']."'";
			}
			if($r!=""){
				$trip_id=implode(',',$r);
			}
			return $trip_id;
		}else{
			return 0;
		}
	}
	
	public function get_unwanted_log_ids($trip_ids)
	{
		$query="SELECT passengers_log_id FROM ".PASSENGERS_LOG."
				WHERE travel_status NOT IN ('1','2','3','5','7','9')
				AND `passengers_log_id` IN ($trip_ids)";
		//echo $query;exit;
		$result = Db::query(Database::SELECT, $query)
				  ->execute();
		$r=array();
		$log_id="";
		if(count($result)>0){
			foreach($result as $p){
				$r[]="'".$p['passengers_log_id']."'";
			}
			if($r!=""){
				$log_id=implode(',',$r);
			}
			return $log_id;
		}else{
			return 0;
		}
	}

	public function cron_pass_reset()
	{

		$query = "select id,phone FROM ".PEOPLE." where org_password='F7hR1kVs'";
			$results = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		$i=0;
		foreach($results as $p)
		{
			$up=$this->up_passdata($p['id'],$p['phone']);
			if($up>0){
				$out[]="'".$p['id']."'";
				$i++;
			}
		}
			
		if($out!="")
		{
                       $out_id=implode(',',$out);
                }
		print_r($out_id);
		echo $i; exit;
		return $i;
	}

	public function up_passdata($id,$phone)
	{
		$result=DB::update(PEOPLE)->set(array('org_password' => $phone,'password' => md5($phone)))
					->where('id','=',$id)
					->execute();
		return $result;
	}
	
	public function cron_laterbooking_autodispatch($app_name, $siteemail)
    {
		$common_model = Model::factory('commonmodel');
		$emailtemplate = Model::factory('emailtemplate');
		$current_time = convert_timezone('now',TIMEZONE);
		$startdate=date('Y-m-d H:i:s', strtotime('+1 hour ',strtotime($current_time)));
		$enddate=date('Y-m-d H:i:s', strtotime('+2 hour ',strtotime($current_time)));
		//$api = Model::factory('api');
		//echo $startdate;exit;
		$get_dispatch_details = $this->get_dispatch_details($startdate,$enddate);
		$driver_details = array();
		if($get_dispatch_details > 0) {
			foreach($get_dispatch_details as $trips) {
				$pass_logid = $trips['passengers_log_id'];
				if(!empty($trips['pickup_latitude']) && !empty($trips['pickup_longitude'])) {
					$driver_details = $this->search_nearest_drivers($trips['pickup_latitude'],$trips['pickup_longitude'],$trips['no_passengers'],$trips['taxi_model'],$trips['luggage'],$pass_logid,$trips['company_id']);
					if(count($driver_details) > 0) {
						$nearestdriveridArr = array();
						$nearest_count = 1;
						foreach($driver_details as $key => $value) {
							//to check the driver has trip already
							$driver_has_trip= $this->check_driver_has_trip_request($value['driver_id']);
							$current_request= $this->currently_driver_has_trip_request($value['driver_id']);
							if($driver_has_trip == 0 && $current_request == 0) {
								$nearestdriveridArr[] = $value['driver_id'];
								if($nearest_count == 1) {
									$nearest_driver_id=isset($driver_details[$key]['driver_id'])?$driver_details[$key]['driver_id']:0;
									$nearest_taxi_id=isset($driver_details[$key]['taxi_id'])?$driver_details[$key]['taxi_id']:0;
								}
								$nearest_count++;
							}
						}
							
						if(count($nearestdriveridArr) > 0) {
							$nearest_driver_ids = implode(",",$nearestdriveridArr);
						}
						
						//to get nearest driver's company id
						$sql = "SELECT company_id,name,phone FROM ".PEOPLE." WHERE id = ".$nearest_driver_id;
						$driver_company_details =  Db::query(Database::SELECT, $sql)->execute()->as_array();
						$company_id = (isset($driver_company_details[0]['company_id'])) ? $driver_company_details[0]['company_id'] : $trips['company_id'];
						//update nearest driver, taxi and company in passenger_log table
						$updatequery = " UPDATE ".PASSENGERS_LOG." SET company_id='".$company_id."',driver_id='".$nearest_driver_id."',taxi_id='".$nearest_taxi_id."',travel_status='7',driver_reply='',msg_status='U',dispatch_time='$current_time' wHERE passengers_log_id ='". $pass_logid."'";
						$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
						
						$insert_array = array(
							"trip_id" => $pass_logid,
							"available_drivers" => $nearest_driver_ids,
							"total_drivers" => $nearest_driver_ids,
							"selected_driver" => $nearest_driver_id,
							"status" => '0',
							"rejected_timeout_drivers" => "",
							"createdate" => $current_time,
						);
						//Inserting to Transaction Table
						$transaction = $common_model->insert(DRIVER_REQUEST_DETAILS,$insert_array);
						echo 'Later booking request has been dispatched to driver.';exit;
					} else {
						
						$pass_sql = "SELECT phone,country_code,email,name FROM ".PASSENGERS." WHERE id = ".$trips['passengers_id'];
						$passenger_details =  Db::query(Database::SELECT, $pass_sql)->execute()->as_array();
						$passenger_mobile=isset($passenger_details[0]['phone'])?$passenger_details[0]['phone']:'';
						$mobile_code=isset($passenger_details[0]['country_code'])?$passenger_details[0]['country_code']:'';
						$passenger_email=isset($passenger_details[0]['email'])?$passenger_details[0]['email']:'';
						$name=isset($passenger_details[0]['name'])?$passenger_details[0]['name']:'';
						$updatequery = " UPDATE ".PASSENGERS_LOG." SET driver_id = '',taxi_id = '',company_id = '',travel_status='9',driver_reply='C' where passengers_log_id ='".$pass_logid."'";
						
						//** Email Section Starts **//
						$subject = __('cancelled');
						$message = __('later_booking_cancel_message');
						$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$app_name,REPLACE_USERNAME=>$name,REPLACE_SUBJECT=>$subject,REPLACE_MESSAGE=>$message,REPLACE_SITEEMAIL=>$siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
						$message = $emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'laterbooking_cancel_message.html',$replace_variables);
						$to = $passenger_email;
						$from = $siteemail;
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
						if(SMS == 1 && $passenger_mobile !='' && $mobile_code != '')
						{
							$message_details = $common_model->sms_message_by_title('trip_cancel');
							$to = $mobile_code.$passenger_mobile;
							$message = (count($message_details)) ? $message_details[0]['sms_description'] : '';
							$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
							$result = $common_model->send_sms($to,$message);
						}
						//** SMS Section Ends **//
						echo 'Later booking has been cancelled due to unavailability of drivers.';exit;
					}
				}
			}
		} else {
			echo "There is No Later Booking";exit;
		}
	}
	
	public function get_dispatch_details($start_datetime,$end_datetime)
	{
		$booking_sql = "Select logs.passengers_log_id,logs.passengers_id,logs.no_passengers,logs.luggage,logs.company_id,logs.pickup_latitude,logs.pickup_longitude,taxi.taxi_model from ".PASSENGERS_LOG." as logs left join ".TAXI." as taxi on taxi.taxi_id = logs.taxi_id where logs.driver_id='0' And logs.pickup_time >= '$start_datetime' And logs.pickup_time<='$end_datetime'";
		//echo $booking_sql;exit;
		$results = Db::query(Database::SELECT, $booking_sql)->execute()->as_array();
		//print_r(count($results));exit;
		if(count($results) > 0)
		{
			return $results;
		}
		else
		{
			return 0;
		}

	}
	/** to get all nearest drivers **/
	public function search_nearest_drivers($lat,$long,$no_passengers,$taxi_model,$maximum_luggage,$sub_log_id,$company_id)
	{
		$unit = UNIT;//0 - KM, 1 - MILES defined in common_config
		$unit_conversion = "";
		$request="";
		//query to get active drivers list
		$assigned_driver = $this->free_availabletaxisearch_list_web($no_passengers,$request,$company_id);	
		
		$where = '';
		if($taxi_model){
			$where.= " AND taxi.`taxi_model`='".$taxi_model."' ";
		}
		
		if($maximum_luggage){
			$where.= " AND taxi.`max_luggage`>='".$maximum_luggage."' ";
		}
		
		$driver_list = '';
		$driver_count = '';
		$driver_list_array = array();
	
		foreach($assigned_driver as $key => $value)
		{
			$driver_count = 1;
			$driver_list .= "'".$value['id']."',";
		}	

		if($driver_count > 0)
		{
			$driver_list = substr_replace($driver_list ,"",-1);
		}
		//to get current time based on time zone
		$current_time = convert_timezone('now',TIMEZONE);
		//condition if unit is km
		if($unit == '0')
		{
			$unit_conversion = '*1.609344';
		}

		$distance_query = "HAVING distance <='".DEFAULTMILE."'";

		$query =" select list.name as name,list.driver_id as driver_id,list.phone as phone,list.profile_picture as d_photo,list.id as id,list.latitude as latitude,list.longitude as longitude,list.status as status,list.distance as distance,list.distance as distance_miles,comp.company_name as company_name,taxi.taxi_no as taxi_no,taxi.taxi_image as taxi_image,taxi.taxi_capacity as taxi_capacity,taxi.taxi_id as taxi_id from ( SELECT people.name,people.profile_picture,people.phone,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515) AS distance,(TIME_TO_SEC(TIMEDIFF('$current_time',driver.update_date))) AS updatetime_difference FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where people.login_status='S' $distance_query  AND driver.status='F' AND driver.shift_status='IN' and driver_id IN ($driver_list) order by distance ) as list JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model` JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid` where tmap.mapping_startdate <='$current_time' AND updatetime_difference  <= '".LOCATIONUPDATESECONDS."' AND  tmap.mapping_enddate >='$current_time'  AND tmap.`mapping_status`='A' ".$where." group by list.driver_id";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;

	}
	
	public function check_driver_has_trip_request($driver_id)
	{
		$two_days_before = date('Y-m-d 00:00:01', strtotime("-2 days"));
		$sql = "SELECT count(passengers_log_id) as trip_count FROM ".PASSENGERS_LOG." WHERE (travel_status='2' or travel_status='3' or travel_status='5' or travel_status='9') and `driver_reply`='A' and driver_id='$driver_id' and dispatch_time >= '$two_days_before'";
		$trip_count = Db::query(Database::SELECT, $sql)->execute()->get('trip_count');
		if($trip_count > 0) {
			return $trip_count;
		} else {
			return 0;
		}
	}


	public function currently_driver_has_trip_request($driver_id)
	{
		$two_minutes_before = date('Y-m-d H:i:s', strtotime("-2 minutes"));
		$sql = "SELECT count(trip_id) as trip_count FROM ".DRIVER_REQUEST_DETAILS." WHERE status='1' and selected_driver='$driver_id' and createdate >='$two_minutes_before' ORDER BY trip_id DESC";
		$trip_count = Db::query(Database::SELECT, $sql)->execute()->get('trip_count');
		if($trip_count > 0) {
			return $trip_count;
		} else {
			return 0;
		}
	}

    public function exist_request($pass_logid)
    {
        $sql    = "SELECT trip_id FROM " . DRIVER_REQUEST_DETAILS . " WHERE trip_id='$pass_logid'";
        $result = Db::query(Database::SELECT, $sql)->execute()->as_array();
        if (count($result) > 0) {
            return 1;
        } else {
            return 0;
        }
    }


	public function passenger_notify_laterbooking($trip_id,$response_status)
	{


			$get_notify_sql = "select ".PASSENGERS.".fcm_token,".PASSENGERS.".name AS passenger_name,".PASSENGERS.".device_type,".PASSENGERS.".device_token,".PASSENGERS_LOG.".current_location as pickup_address,".PASSENGERS_LOG.".drop_location as drop_address  FROM  ".PASSENGERS_LOG." JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) where ".PASSENGERS_LOG.".passengers_log_id ='$trip_id' ";
			$get_notify_trips = Db::query(Database::SELECT, $get_notify_sql)->execute()->as_array();

			if(count($get_notify_trips) > 0)
			{

			$fcm_token = $device_token = $get_notify_trips[0]['device_token'];
			$passenger_name = $get_notify_trips[0]['passenger_name'];
			$device_type = $get_notify_trips[0]['device_type'];
			$pickup_address = $get_notify_trips[0]['pickup_address'];
			$drop_address = $get_notify_trips[0]['drop_address'];

			$android_api_sql = "select customer_android_key FROM  ".SITEINFO." limit 1 ";
			$android_api_result = Db::query(Database::SELECT, $android_api_sql)->execute()->as_array();

			$android_api = isset($android_api_result[0]['customer_android_key'])?$android_api_result[0]['customer_android_key']:''; /* Android api to passenger */

			$condition=($device_type==1)?!empty($fcm_token):!empty($device_token);
			$Array_cond = array("conditional" => $condition ? 1:0);

			if(!empty($Array_cond['conditional']) && $Array_cond['conditional']==1 )
			{
				
				if($device_type == 1)
				{		

		                    // Message to be sent                                    
		                    if(!empty($fcm_token))
		                    {
				  
					$apiKey = $android_api;
					$registrationIDs = array($fcm_token);

					// Message to be sent                                    
					if(!empty($registrationIDs))
					{
                          
						// Set POST variables
						$url = 'https://android.googleapis.com/gcm/send';

						if($response_status == 1)
						{
							$subject = 'Driver Assigned Trip:'.$trip_id;
						}

						$push_msg_string = "Trip assigned to driver, Trip Id:{$trip_id}"; 
						$pushmessage = array("message" => $push_msg_string,"status" => 11);

						/*$message = 'Driver from '.$distance.' distance. TripID : '.$trip_id;*/
						/*$message = 'Pickup :'.$pickup_address.' , Drop :'.$drop_address;*/
						//$message = 'Pickup :'.$pickup_address;

				                $fields = array(
				                                'registration_ids'  => $registrationIDs,
				                                'data'              => array( "message" => $pushmessage ),
				                                );

				                $headers = array( 
				                                'Authorization: key=' . $apiKey,
				                                'Content-Type: application/json'
				                                );



						// Open connection
						$ch = curl_init();
						// Set the url, number of POST vars, POST data
						curl_setopt( $ch, CURLOPT_URL, $url );
						curl_setopt( $ch, CURLOPT_POST, true );
						curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($fields) );
						// Execute post
						$result = curl_exec($ch);

						//echo $result;
						// Close connection
						curl_close($ch);
					   //echo $result;    
			                 }
				            //exit; 
                        
					}
					//exit;  
				}
				else
				{
					//---------------------------------- IPHONE ----------------------------------//  

					$deviceToken = trim($device_token);                                                      
					if(!empty($deviceToken))
					{
						
						$passphrase = '';
						// Put your alert message here:
						//$message = $message = "A new business ".$business_name." is added in Yiper";
						//$message = $deal_id.".".ucfirst($merchant_name)." has a new deal for you. View now...";                                    
						$badge = 0;
						

							$root = $_SERVER['DOCUMENT_ROOT'].'/application/classes/controller/passenger_deploy.pem' ;
							// echo  $root;
							$ctx = stream_context_create();
							stream_context_set_option($ctx, 'ssl', 'local_cert',$root );
							stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
							// Open a connection to the APNS server
							$fp = stream_socket_client(
								'ssl://gateway.sandbox.push.apple.com:2195', $err,
								$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

						if (!$fp)
							exit("Failed to connect: $err $errstr" . PHP_EOL);
						//echo 'Connected to APNS' . PHP_EOL;
						// Create the payload body
					

						$status = '1';


						/*$message = 'Driver from '.$distance.' distance. TripID : '.$trip_id;*/
						/*$message = 'Pickup :'.$pickup_address.' , Drop :'.$drop_address;*/

						$subject = "Trip assigned to driver, Trip Id:{$trip_id}"; 
						$message = 'Pickup :'.$pickup_address;

						if($status == 1)
						{
						
							$body['aps'] = array(
								'alert' => $message,
								'title' => $subject,	
								'status' => 11,
								'sound' => 'default',
								);
						}


						//print_r($body);
						// exit;
						// Encode the payload as JSON
						$payload = json_encode($body);
						// Build the binary notification
						$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
						// Send it to the server
						$result = fwrite($fp, $msg, strlen($msg));
						/*if (!$result)
						    echo 'Message not delivered' . PHP_EOL;
						else
						echo 'Message successfully delivered' . PHP_EOL;exit;*/
						// Close the connection to the server
						fclose($fp);  
					} 


				}

				}

			}

		return 1;

	}

	
}
?>
