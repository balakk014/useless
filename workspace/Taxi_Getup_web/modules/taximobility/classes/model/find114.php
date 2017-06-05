<?php defined('SYSPATH') OR die('No Direct Script Access');

/******************************************

* Contains Finding the Locations details

* @Package: ConnectTaxi

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************/

class Model_Find114 extends Model
{
	/** get nearest driver list - optimized query 22oct16 **/
	public function getNearestDrivers($modelId,$lat,$long,$current_time,$companyId,$distance,$unit)
	{
		$company_condition=$where="";
		if(!empty($companyId)){
			$company_condition = "AND tmap.mapping_companyid = '$companyId' AND taxi.taxi_company = '$companyId'";
		}
		//model Condition
		if(($modelId != 'All') && !empty($modelId)){
			$where.= " AND taxi.`taxi_model`='".$modelId."' ";
		}
		//distance check
		$distance_query="";
		if($distance)
		{
			$distance_query = "HAVING d_distance <='$distance'";
		}
		//unit conversion
		$unit_conversion = "";
		if($unit == '0')
		{
			$unit_conversion = '*1.609344';
		}
		$query =" select list.driver_id as driver_id,list.latitude as latitude,list.longitude as longitude,list.d_distance as distance_km,list.updatetime_difference as updatetime_difference,taxi.taxi_speed as taxi_speed,(select model_name from ".MOTORMODEL." where ".MOTORMODEL.".model_id = taxi.taxi_model limit 0,1 ) as model_name,(select check_package_type from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = taxi.taxi_company  order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = taxi.taxi_company order by upgrade_id desc limit 0,1 ) as upgrade_expirydate from ( SELECT people.name,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) * cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515$unit_conversion) AS d_distance,(TIME_TO_SEC(TIMEDIFF('$current_time',driver.update_date))) AS updatetime_difference FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where people.login_status='S' AND people.account_balance > 0  AND people.booking_limit > (SELECT COUNT( passengers_log_id ) FROM  ".PASSENGERS_LOG." as pl WHERE pl.driver_id = people.id AND  pl.`createdate` >='$current_time' AND  pl.`travel_status` =  '1' AND pl.booking_from != '2') $distance_query AND driver.status='F' AND driver.shift_status='IN' order by d_distance ) as list JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid` where updatetime_difference  <= '".LOCATIONUPDATESECONDS."' and  tmap.mapping_startdate <= '$current_time' AND  tmap.mapping_enddate >= '$current_time' AND tmap.`mapping_status`='A' ".$where." $company_condition group by list.driver_id Having ( check_package_type = 'T' or upgrade_expirydate >='$current_time') order by distance_km asc limit 0,5";
		//echo $query;exit;
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		//print_r($result); exit;
		return $result;
	}
	/*Function used to get the current location of the Driver*/
	public function get_driver_location($lat,$long,$distance = NULL,$no_passengers,$bookingtime)
	{
		
		$assigned_driver = $this->free_availabletaxi_list($no_passengers,$bookingtime);
		//$this->currentdate = Commonfunction::getCurrentTimeStamp();

		$driver_list = '';
		$driver_count = '';
		foreach($assigned_driver as $key => $value)
		{
			$driver_count = 1;
			$driver_list .= "'".$value['id']."',";
		}	
		if($driver_count > 0)
		{
			$driver_list = substr_replace($driver_list ,"",-1);
		}
		else
		{
			$driver_list = "''";
		}

		/*$query = " DROP FUNCTION IF EXISTS CONV_MI_KM";

		$find_result = Database::instance()->query(NULL, $query);

		$query = "CREATE FUNCTION CONV_MI_KM (measurement INT,  base_type ENUM('m','k')) RETURNS FLOAT(65,4) DETERMINISTIC RETURN IF(base_type = 'm', measurement * 1.609344, IF(base_type = 'k', measurement * 0.62137, NULL))";

		Database::instance()->query(NULL, $query);
		*/
		if(isset($_SESSION['search_city']))
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".city_name like '%".$_SESSION['search_city']."%'  limit 0,1"; 
		}		
		else
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1"; 
		}

		$model_fetch = Db::query(Database::SELECT, $model_base_query)
				->execute()
				->as_array();

		$city_model_fare = $model_fetch[0]['city_model_fare'];

		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $current_date[0].' 00:00:01';
		$end_time = $current_date[0].' 23:59:59';

		 $query = "select list.name as name,list.driver_id as driver_id,list.phone as phone,list.id as id,list.latitude as latitude,list.longitude as longitude,list.status as status,list.distance as distance,list.distance as distance_km,comp.company_name as company_name,taxi.taxi_no as taxi_no,taxi.taxi_fare_km as taxi_fare,taxi.taxi_image as taxi_image,taxi.taxi_capacity as taxi_capacity,taxi.taxi_id as taxi_id from ( SELECT people.name,people.phone,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515) AS distance FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where driver.status='F' and driver_id IN ($driver_list) order by distance ) as list JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid` WHERE tmap.mapping_startdate <='$current_time' AND  tmap.mapping_enddate >='$current_time' AND tmap.`mapping_status`='A'  group by list.driver_id";
		 //tmap.mapping_startdate <='$current_time' AND  tmap.mapping_enddate >='$current_time' AND 

		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();			
		return $result;
		
	}
	
	public static function getmodel_details($motorid)
	{
			$result = DB::select(MOTORMODEL.'.model_id',MOTORMODEL.'.model_name')->from(MOTORMODEL)->join(MOTORCOMPANY, 'LEFT')->on(MOTORMODEL.'.motor_mid', '=', MOTORCOMPANY.'.motor_id')->where('motor_mid','=',$motorid)->where('motor_status','=','A')->where('model_status','=','A')->order_by('model_name','ASC')
				->execute()
				->as_array();

			return $result;
	}
	

	public function search_driver_location($lat,$long,$distance = NULL,$no_passengers,$request,$taxi_fare_km,$taxi_model,$taxi_type,$maximum_luggage,$city_name,$sub_log_id)
	{
		if($sub_log_id !='')
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

		}

		$assigned_driver = $this->free_availabletaxisearch_list_web($no_passengers,$request);
		$add_field = "";
		//$this->currentdate = Commonfunction::getCurrentTimeStamp();

		$where = ' ';
		/*if($taxi_fare_km){
			$where.= " AND taxi.`taxi_fare_km`<='".$taxi_fare_km."' ";
		}*/
		/*if($taxi_fare_km){
			$where.= " AND taxi.`min_fare`<='".$taxi_fare_km."' ";
		}
		*/
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

	
		/*$query = " DROP FUNCTION IF EXISTS CONV_MI_KM";

		$find_result = Database::instance()->query(NULL, $query);

		$query = "CREATE FUNCTION CONV_MI_KM (measurement INT,  base_type ENUM('m','k')) RETURNS FLOAT(65,4) DETERMINISTIC RETURN IF(base_type = 'm', measurement * 1.609344, IF(base_type = 'k', measurement * 0.62137, NULL))";

		Database::instance()->query(NULL, $query);*/
		
		if($city_name !='')
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".city_name like '%".$city_name."%'  limit 0,1"; 
		}		
		else
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1"; 
		}

		$model_fetch = Db::query(Database::SELECT, $model_base_query)
				->execute()
				->as_array();

		if(count($model_fetch) > 0)
		{
			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}
		else
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1"; 

			$model_fetch = Db::query(Database::SELECT, $model_base_query)
				->execute()
				->as_array();

			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}
		if($taxi_fare_km) {  /*$where.=" HAVING min_fare <= $taxi_fare_km";*/ }
		$additional_field_join = "";
		if($add_field != "")
		{
			$additional_field_join = "JOIN ".ADDFIELD." as adds ON tmap.`mapping_taxiid`=adds.`taxi_id`";
		}


		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $current_date[0].' 00:00:01';
		$end_time = $current_date[0].' 23:59:59';

		if(FARE_SETTINGS == 2)
		{
			$query =" select list.name as name,list.driver_id as driver_id,list.phone as phone,list.profile_picture as d_photo,list.id as id,list.latitude as latitude,list.longitude as longitude,list.status as status,list.distance as distance,list.distance as distance_miles,comp.company_name as company_name,comp.cid as get_companyid,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".id=comp.cid ) as cancellation_nfree,(SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km, taxi.taxi_no as taxi_no,taxi.taxi_image as taxi_image,taxi.taxi_capacity as taxi_capacity,taxi.taxi_id as taxi_id,taxi.taxi_speed as taxi_speed from ( SELECT people.name,people.profile_picture,people.phone,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515) AS distance FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where people.login_status='S' HAVING distance <= " .$distance." AND driver.status='F' AND driver.shift_status='IN' and driver_id IN ($driver_list) order by distance ) as list JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` 
			JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model` 
			JOIN ".COMPANY_MODEL_FARE." as company_model_fare ON company_model_fare.`model_id`=taxi.`taxi_model` 
			JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid` $additional_field_join where  company_model_fare.company_cid=comp.cid AND tmap.mapping_startdate <='$current_time' AND  tmap.mapping_enddate >='$current_time' and tmap.`mapping_status`='A' ".$where.$add_field." group by list.driver_id";
			
			//
		}
		else
		{
			$query =" select list.name as name,list.driver_id as driver_id,list.phone as phone,list.profile_picture as d_photo,list.id as id,list.latitude as latitude,list.longitude as longitude,list.status as status,list.distance as distance,list.distance as distance_miles,comp.company_name as company_name,comp.cid as get_companyid,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".id=comp.cid ) as cancellation_nfree,(SUM(model.base_fare)*($city_model_fare)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*($city_model_fare)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*($city_model_fare)/100) + model.below_km as below_km,(SUM(model.above_km)*($city_model_fare)/100) + model.above_km as above_km, taxi.taxi_no as taxi_no,taxi.taxi_image as taxi_image,taxi.taxi_capacity as taxi_capacity,taxi.taxi_id as taxi_id,taxi.taxi_speed as taxi_speed from ( SELECT people.name,people.profile_picture,people.phone,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515) AS distance FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where people.login_status='S' HAVING distance <= " .$distance." AND driver.status='F' AND driver.shift_status='IN' and driver_id IN ($driver_list) order by distance ) as list JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model` JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid` $additional_field_join where  tmap.mapping_startdate <='$current_time' AND  tmap.mapping_enddate >='$current_time'  AND tmap.`mapping_status`='A' ".$where.$add_field." group by list.driver_id";
			//
		}
		//echo $query;
		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();

			
		return $result;
		
	}	
	
	

	
	
	
	public function free_availabletaxi_list($no_passengers = '',$bookingtime = '')
	{
	

		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $current_date[0].' 00:00:01';
		$end_time = $current_date[0].' 23:59:59';
		$bookingdatetime = $current_date[0].' '.$bookingtime;

		//$cuurentdate = date('Y-m-d H:i:s');
		//$enddate = date('Y-m-d').' 23:59:59';
		
		//$bookingdatetime = date('Y-m-d').' '.$bookingtime;
			
		$capacity_where= ($no_passengers) ? " AND taxi_capacity >= $no_passengers" : "";
		
		$booked_where = '';
			
		
		if($bookingtime)
		{
			$booked_where = " AND ( ( '$bookingdatetime' NOT between passengerlog.pickup_time and  passengerlog.drop_time ) )";	
		}

		/**			
		SELECT people.id,taxi.taxi_id,company_id,(select check_package_type from package_report where package_report.upgrade_companyid = taxi.taxi_company order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from package_report where package_report.upgrade_companyid = taxi.taxi_company order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM taxi as taxi JOIN company as company ON taxi.taxi_company = company.cid JOIN taxi_driver_mapping as taximapping ON taxi.taxi_id = taximapping.mapping_taxiid JOIN people as people ON people.id = taximapping.mapping_driverid JOIN taxi_additional_field as addfield ON addfield.taxi_id = taxi.taxi_id WHERE people.status = 'A' AND taxi.taxi_status = 'A' AND taxi.taxi_availability = 'A' AND people.availability_status = 'A' AND people.booking_limit > (SELECT COUNT( passengers_log_id ) FROM passengers_log WHERE driver_id = people.id AND `createdate` >='2013-11-22 00:00:01' AND `travel_status` = '1') AND taximapping.mapping_status = 'A' AND company.company_status='A' AND ( ( '2013-11-22 20:23:59' between taximapping.mapping_startdate and taximapping.mapping_enddate ) or ( '2013-11-22 23:59:59' between taximapping.mapping_startdate and taximapping.mapping_enddate) ) group by taxi_id

		**/
		
		$company_condition="";
		 if(COMPANY_CID!=0){
			$company_condition = "AND taximapping.mapping_companyid = '".COMPANY_CID."' AND people.company_id = '".COMPANY_CID."' AND taxi.taxi_company = '".COMPANY_CID."'";
		} 		
		
			
		/*$sql ="SELECT people.id,taxi.taxi_id  ,(select check_package_type from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company order by upgrade_id desc limit 0,1 ) as upgrade_expirydate	FROM ".TAXI." as taxi JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid WHERE people.status = 'A' 	AND taxi.taxi_status = 'A' AND taxi.taxi_availability = 'A' AND people.availability_status = 'A' AND people.booking_limit > (SELECT COUNT( passengers_log_id ) FROM  ".PASSENGERS_LOG." WHERE driver_id = people.id AND  `createdate` >='".$start_time."' AND  `travel_status` =  '1' AND booking_from != '2') AND taximapping.mapping_status = 'A' $company_condition AND company.company_status='A' $capacity_where AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time'   group by taxi_id Having ( check_package_type = 'T' or upgrade_expirydate >='$current_time' )";*/
		$sql ="SELECT people.id,taxi.taxi_id FROM ".TAXI." as taxi JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid WHERE people.status = 'A' 	AND taxi.taxi_status = 'A' AND taxi.taxi_availability = 'A' AND people.availability_status = 'A' AND people.booking_limit > (SELECT COUNT( passengers_log_id ) FROM  ".PASSENGERS_LOG." WHERE driver_id = people.id AND  `createdate` >='".$start_time."' AND  `travel_status` =  '1' AND booking_from != '2') AND taximapping.mapping_status = 'A' $company_condition AND company.company_status='A' $capacity_where AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time'   group by taxi_id";
		//AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time'

//echo $sql;
		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}	



	public function free_availabletaxisearch_list_web($no_passengers = '',$request = '',$company_id ='')
	{
		//print_r($request);
	
		$where_cond = '';

 

		//$capacity_where= ($no_passengers) ? " AND taxi_capacity >= $no_passengers" : "";
		
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
				

		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $current_date[0].' 00:00:01';
		$end_time = $current_date[0].' 23:59:59';	
		
		$cuurentdate = date('Y-m-d H:i:s');
		//$enddate = date('Y-m-d').' 23:59:59';
			
		$company_condition="";
		if(COMPANY_CID!=0){
			$company_condition = "AND taximapping.mapping_companyid = '".COMPANY_CID."' AND people.company_id = '".COMPANY_CID."' AND taxi.taxi_company = '".COMPANY_CID."'";
		}

	
		 $sql ="SELECT people.id,taxi.taxi_id  ,(select check_package_type from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company  order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM ".TAXI." as taxi JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid WHERE people.status = 'A' 	AND taxi.taxi_status = 'A' AND taxi.taxi_availability = 'A' AND people.availability_status = 'A' AND people.notification_setting = '1' AND people.booking_limit > (SELECT COUNT( passengers_log_id ) FROM  ".PASSENGERS_LOG." WHERE driver_id = people.id AND  `createdate` >='".$start_time."' AND  `travel_status` =  '1' AND booking_from != '2') AND taximapping.mapping_status = 'A' $company_condition AND company.company_status='A' $capacity_where AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time'  group by taxi_id Having ( check_package_type = 'T' or upgrade_expirydate >='$cuurentdate' )";
		 //AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time' 
		//echo $sql;
		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}

	public function free_availabletaxisearch_list($motor_company = '',$motor_model = '',$company_id ='')
	{
		//print_r($request);
		$additional_fields = "";
		
		
		$field_count = count($additional_fields);
		$where_cond = '';

		//$capacity_where= ($no_passengers) ? " AND taxi_capacity >= $no_passengers" : "";
		
		if(isset($motor_company) && $motor_company !='')
		{
			$taxitype_where = " AND taxi_type ='1'";
		}
		else
		{
			$taxitype_where = '';
		}
		
		if(isset($motor_model)  && ($motor_model !=''))
		{
			$taximodel_where = " AND taxi_model ='".$motor_model."'";
		}
		else
		{
			$taximodel_where = '';
		}
				

		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $current_date[0].' 00:00:01';
		$end_time = $current_date[0].' 23:59:59';
		
		//$cuurentdate = date('Y-m-d H:i:s');
		//$enddate = date('Y-m-d').' 23:59:59';
			

		$company_condition="";
		if($company_id != ""){
			$company_condition = "AND taximapping.mapping_companyid = '$company_id' AND people.company_id = '$company_id' AND taxi.taxi_company = '$company_id'";
		}

		 $sql ="SELECT people.id,taxi.taxi_id  ,(select check_package_type from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company  order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM ".TAXI." as taxi JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid WHERE people.status = 'A'         AND taxi.taxi_status = 'A' AND taxi.taxi_availability = 'A' AND people.availability_status = 'A'   and people.booking_limit > (SELECT COUNT( passengers_log_id ) FROM  ".PASSENGERS_LOG." WHERE driver_id = people.id AND  `createdate` >='".$start_time."' AND  `travel_status` =  '1' AND booking_from != '2')  
               AND taximapping.mapping_status = 'A' $company_condition AND company.company_status='A' AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time'  group by taxi_id Having ( check_package_type = 'T' or upgrade_expirydate >='$current_time')";
		 //AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time'
		 //AND people.notification_setting = '1'
//echo $sql;
		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}
	/** Get get_cancel_reject_trips **/
	public function get_cancel_reject_trips($id)
	{


		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $current_date[0].' 00:00:01';
		$end_time = $current_date[0].' 23:59:59';

		$sql = "SELECT * FROM  `".PASSENGERS_LOG."` WHERE  `".PASSENGERS_LOG."`.`passengers_id` =  '".$id."' AND (`".PASSENGERS_LOG."`.`driver_reply` =  'R' OR  `".PASSENGERS_LOG."`.`driver_reply` =  'C' ) AND  `".PASSENGERS_LOG."`.`travel_status` =  '0' AND  `".PASSENGERS_LOG."`.`createdate` >=  '".$start_time."' ORDER BY  `".PASSENGERS_LOG."`.`passengers_log_id` DESC ";
								
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
	
	public function getMiles()
	{
		$result = DB::select()->from(MILES)->where('mile_status','=','A')->order_by('mile_name','asc')
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

	/*public function unset_driverlist_app($log_id,$flag,$company_id)
	{

		//$log_id = $this->get_sublogid($log_id);

		//$result = DB::select('driver_id')->from(PASSENGERS_LOG)->where('sub_logid','=',$log_id)->order_by('passengers_log_id','asc')
		if($flag == '0')
		{
		//based on passenger log id
		$result = DB::select('driver_id')->from(DRIVER_REJECTION)
						->where('passengers_log_id','=',$log_id)
						->order_by('passengers_log_id','asc')
						->execute()
						->as_array();
		}
		else
		{
			$Commonmodel = Model::factory('Commonmodel');
			$company_all_currenttimestamp = $Commonmodel->getcompany_all_currenttimestamp($company_id);
			//based on passenger id and date
			$query ="SELECT DISTINCT driver_id FROM  `driver_rejection_list` WHERE  `passengers_id` =  '$log_id' AND DATE(createdate) !=  '$company_all_currenttimestamp' order by 'passengers_log_id' ASC";
			$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		}

		return $result;
	}*/
	public function unset_driverlist_app($log_id,$flag,$company_id)
	{ 
		//$log_id = $this->get_sublogid($log_id);

		//$result = DB::select('driver_id')->from(PASSENGERS_LOG)->where('sub_logid','=',$log_id)->order_by('passengers_log_id','asc')
		if($flag == '0')
		{ 
		//based on passenger log id
			$result = DB::select('tdriver_ids')->from(PASSENGER_LOG_TEMP)
						->where('tpassenger_log_id','=',$log_id)
						//->order_by('tpassengers_log_id','asc')
						->execute()
						->as_array();
		}
		else
		{
			$Commonmodel = Model::factory('Commonmodel');
			$company_all_currenttimestamp = $Commonmodel->getcompany_all_currenttimestamp($company_id);
			//based on passenger id and date
			$query ="SELECT tdriver_ids FROM  ".PASSENGER_LOG_TEMP." WHERE  `tpassenger_id` =  '$log_id' AND DATE(createdate) !=  '$company_all_currenttimestamp' order by 'tpassengers_log_id' ASC";
			$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		}
		if(count($result)>0)
		{
			$output = $result[0]['tdriver_ids'];
		}
		else
		{
			$output="";
		}
		return $output;
	}

	
	public function search_driver_mobileapp($lat,$long,$distance = NULL,$passenger_id,$taxi_fare_km,$taxi_type,$taxi_model,$maximum_luggage,$city_name,$sub_log_id,$company_id,$unit,$service_type)
		
	{ 
		$flag='';
		$unit_conversion="";
		if(($sub_log_id !='0') && ($sub_log_id !=''))
		{
			//based on passenger log id
			$flag=0;
			$get_passenger_driverid = $this->unset_driverlist_app($sub_log_id,$flag,$company_id);
			if($get_passenger_driverid != "")
			{
				$remove_driver_list=explode(',',$get_passenger_driverid);
				/*foreach($get_passenger_driverid as $key => $value)
				{
					$remove_driver_list[] = $value['driver_id'];
				}*/
			}	
			else
			{
				$remove_driver_list = array();
			}

		}
		else
		{
			//based on passenger id and date
			$flag=1;
			$get_passenger_driverid = $this->unset_driverlist_app($passenger_id,$flag,$company_id);
			if($get_passenger_driverid != "")
			{
				$remove_driver_list=explode(',',$get_passenger_driverid);		
			}
			else
			{
				$remove_driver_list = array();
			}
		}
		
		$assigned_driver = $this->free_availabletaxisearch_list($taxi_type,$taxi_model,$company_id);
		//$this->currentdate = Commonfunction::getCurrentTimeStamp();
		$add_field = "";		
		
		$where = ' ';

		if($taxi_type){
			$where.= " AND taxi.`taxi_type`='".$taxi_type."' ";
		}
		if(($taxi_model != 0) && ($taxi_model != null)){
			$where.= " AND taxi.`taxi_model`='".$taxi_model."' ";
		}
		/*if($maximum_luggage){
			$where.= " AND taxi.`max_luggage`>='".$maximum_luggage."' ";
		}*/
				
		$driver_list = '';
		$driver_count = '';
		$driver_list_array = array();

		foreach($assigned_driver as $key => $value)
		{
			$driver_list_array[] = $value['id'];
		}	

		if(($sub_log_id !='0') && ($sub_log_id !=''))
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
			$driver_arraylist = array_diff($driver_list_array,$remove_driver_list); 	

			foreach($driver_arraylist as $key => $value)
			{
				$driver_count = 1;
				$driver_list .= "'".$value."',";
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
	

		if($city_name !='')
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".city_name like '%".$city_name."%'  limit 0,1"; 
		}		
		else
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1"; 
		}

		$model_fetch = Db::query(Database::SELECT, $model_base_query)
				->execute()
				->as_array();

		if(count($model_fetch) > 0)
		{
			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}
		else
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1"; 

			$model_fetch = Db::query(Database::SELECT, $model_base_query)
				->execute()
				->as_array();

			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}


		$company_condition="";
		if($company_id != ""){
			$company_condition = "AND tmap.mapping_companyid = '$company_id' AND taxi.taxi_company = '$company_id'";
		}


		if($company_id == '')
		{
			$current_time = convert_timezone('now',TIMEZONE);
			$current_date = explode(' ',$current_time);
			$start_time = $current_date[0].' 00:00:01';
			$end_time = $current_date[0].' 23:59:59';
		}	
		else
		{
			$model_base_query = "select time_zone from  company where cid='$company_id' "; 
			$model_fetch = Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array();			

			if($model_fetch[0]['time_zone'] != '')
			{
				$current_time = convert_timezone('now',$model_fetch[0]['time_zone']);
				$current_date = explode(' ',$current_time);
				$start_time = $current_date[0].' 00:00:01';
				$end_time = $current_date[0].' 23:59:59';
			}
			else
			{
				$current_time =	date('Y-m-d H:i:s');
				$start_time = date('Y-m-d').' 00:00:01';
				$end_time = date('Y-m-d').' 23:59:59';
			}
		}

		if($distance)
		{
			$distance_query = "HAVING distance <='$distance'";
		}
		else
		{
			$distance_query = "HAVING distance <='".DEFAULTMILE."'";
		}

		$additional_field_join = "";
		if($add_field != "")
		{
			$additional_field_join = "JOIN ".ADDFIELD." as adds ON tmap.`mapping_taxiid`=adds.`taxi_id`";
		}
		if($unit == '0')
		{
			$unit_conversion = '*1.609344';
		}

			 $query =" select 
			 list.name as name,
			 model.model_name,
			 list.driver_id as driver_id,
			 list.phone as phone,list.
			 profile_picture as d_photo,
			 list.id as id,
			 list.latitude as latitude,
			 list.longitude as longitude,
			 list.status as status,
			 list.distance as distance,
			 list.distance as distance_miles,
			 list.latitude as driver_latitude,
			 list.longitude as driver_longitude,
			 list.updatetime_difference as updatetime_difference,
			 comp.company_name as company_name,
			 comp.cid as get_companyid,
			 
			 (select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=comp.cid ) as cancellation_nfree,
			 
			 (select company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=comp.cid ) as company_tax,			 
			 
			 taxi.taxi_no as taxi_no,taxi.taxi_image as taxi_image,taxi.taxi_capacity as taxi_capacity,taxi.taxi_id as taxi_id,taxi.taxi_speed as taxi_speed from 
			 
			 ( SELECT people.name,people.profile_picture,people.phone,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515$unit_conversion) AS distance,(TIME_TO_SEC(TIMEDIFF('$current_time',driver.update_date))) AS updatetime_difference  
			 FROM ".DRIVER." AS driver 
			 JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where 
			 people.login_status='S'  
			 $distance_query 
			 AND driver.status='F' 
			 AND driver.shift_status='IN' 
			 and driver_id IN ($driver_list) order by distance ) as list 
			 
			JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` 
			JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` 
			JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model`
			JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid`  $additional_field_join where  
			
			tmap.mapping_startdate <='$current_time' 
			AND  tmap.mapping_enddate >='$current_time' 
			AND tmap.`mapping_status`='A' AND  p.account_balance > 0 ".$where.$add_field." $company_condition group by list.driver_id order by distance asc";
		
	//echo $query.'<br/><br/>';

		if($taxi_fare_km) {  $query .=" HAVING min_fare <= $taxi_fare_km"; }


		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();

			
		return $result;
		
	}	


	public function get_sublogid($log_id)
	{
		 $sql = "select sub_logid from ".PASSENGERS_LOG." where passengers_log_id='$log_id'";
		 $result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
		if(count($result) > 0)
		{
			return $result[0]['sub_logid'];
		}
		else
		{
			return '0';
		}
	}	
	
	public function get_passenger_company($passenger_id)
	{
		 $sql = "select passenger_cid from ".PASSENGERS." where id='$passenger_id'";
		 $result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();

		if($result[0]['passenger_cid'] == 0)	
		{

			 $sql = "select passenger_setting from ".SITEINFO." limit 0,1";
			 $result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();

			return $result[0]['passenger_setting'];
		}
		else
		{
			 $company_id = $result[0]['passenger_cid'];
			 $sql = "select passenger_setting from ".COMPANYINFO." where company_cid='$company_id'";
			 $result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();

			return $result[0]['passenger_setting'];

		}

	}			


	public function get_company_setting($company_id)
	{

		 $sql = "select passenger_setting from ".COMPANYINFO." where company_cid='$company_id'";
		 $result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();

		return $result[0]['passenger_setting'];

	}

	public function auto_savebooking($driver_details,$request,$passenger_id,$company_id)
	{ 
	
		$pickup_time = commonfunction::real_escape_string($request['bookingtime']);
		$pickupdrop = '0';
		$waitingtime = '0';
		$travel_status = '0';
		$pass_logid = $request['pass_logid'];

		// Get Pickup & Drop location Lat & Long using Google API 
		$pickup = $this->getlatitudelong(commonfunction::real_escape_string($request['cur_loc']));
		$pickup_latitude = $pickup[0];
		$pickup_longitude = $pickup[1];

		$drop_latitude = $drop_longitude="";
		if(isset($_SESSION['search_city']))
		{
			$model_base_query = "select city_id from ".CITY." where ".CITY.".city_name like '%".$_SESSION['search_city']."%' limit 0,1"; 
		}		
		else
		{
			$model_base_query = "select city_id from ".CITY." where ".CITY.".default=1"; 
		}

		$model_base_result =  Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array(); 

		if(count($model_base_result) > 0)
		{	
			$city_id = $model_base_result[0]['city_id'];
		}
		else
		{
			$model_base_query = "select city_id from ".CITY." where ".CITY.".default=1"; 
			$model_base_result =  Db::query(Database::SELECT, $model_base_query)
						->execute()
						->as_array(); 

			$city_id = $model_base_result[0]['city_id'];
		}

		/******** Fare Details *******************/
				
		if($request['drop_loc'] != '')
		{
		$drop = $this->getlatitudelong(commonfunction::real_escape_string($request['drop_loc']));
		$drop_latitude = $drop[0];
		$drop_longitude = $drop[1];	
			$distance = $this->find_Haversine($pickup, $drop);
			//Converting to Km

			$approx_distance = round($distance * 1.609344,2);						
			if($approx_distance < 1)
			{
				$approx_fare = $driver_details[0]['min_fare'];
			}
			else if($approx_distance <= 10)
			{
				$approx_fare = $approx_distance * $driver_details[0]['below_km'];
			}
			else if($approx_distance > 10)
			{
				$approx_fare = $approx_distance * $driver_details[0]['above_km'];
			}	
		}
		else
		{	
			$drop = '';
			$drop_latitude = '';
			$drop_longitude = '';
			
			//Converting to Km
			$approx_distance = "";						
			$approx_fare = "";			
		}
		/************** End *****************/		

		$d_company_id = 
		$companytax_query = "SELECT * FROM ".COMPANYINFO." WHERE `company_cid` = '".$company_id."'";
		$companytax_result = Db::query(Database::SELECT, $companytax_query)->execute()->as_array();

		$company_tax = $companytax_result[0]['company_tax'];	

		$fieldname_array = array('passengers_id','driver_id','company_id','current_location','pickup_latitude',
'pickup_longitude','drop_location','drop_latitude','drop_longitude','no_passengers','approx_distance','approx_fare','pickup_time',
'pickupdrop','waitingtime','travel_status','createdate','taxi_id','search_city','sub_logid','booking_from_cid','company_tax',
'bookingtype','bookby');	

			$time = date ('H:i:s',strtotime($pickup_time));
			$current_datetime = convert_timezone('now',TIMEZONE); 
			$curretnt_datetime_split = explode(' ',$current_datetime);
			$update_time = $curretnt_datetime_split[0].' '.$time;

			$Commonmodel = Model::factory('Commonmodel');
			$this->currentdate = $Commonmodel->getcompany_all_currenttimestamp(COMPANY_CID);

		
		$values_array = array($passenger_id,commonfunction::real_escape_string($driver_details[0]['driver_id']),$company_id,commonfunction::real_escape_string($request['cur_loc']),$pickup_latitude,$pickup_longitude,commonfunction::real_escape_string($request['drop_loc']),$drop_latitude,$drop_longitude,commonfunction::real_escape_string($request['no_passengers']),$approx_distance,$approx_fare,$update_time,$pickupdrop,$waitingtime,$travel_status,$this->currentdate,commonfunction::real_escape_string($driver_details[0]['taxi_id']),$city_id,$pass_logid,$company_id,$company_tax,'1','1');



			$result = DB::insert(PASSENGERS_LOG, $fieldname_array)
						->values($values_array)
						->execute();

			if($pass_logid =='')
			{
				$update_pass_logid= DB::update(PASSENGERS_LOG)->set(array('sub_logid' =>$result[0] ))
				->where('passengers_log_id','=',$result[0])
				->execute();	
			}
			if($result)
			{
				$array = array();
				//return array(1,$result);
				$array['result'] = 1 ;
				$array['pass_logid'] = $result[0];
				return $array;
			}
			else
			{
				return 0;
			}

			
	}

	public function get_driver_companyid($driver_id)
	{
		$sql = "SELECT * FROM ".PEOPLE." WHERE `id` = '".$driver_id."'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}

	public function getlatitudelong($address) 
	{		
		$address = str_replace(' ', '+', $address);
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&sensor=false&key='.GOOGLE_GEO_API_KEY;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$geoloc = curl_exec($ch);
		
		//print_r($geoloc);
		$json = json_decode($geoloc);	
		//print_r($json);exit;
		if($json->status == 'OK')
		{				
		return array($json->results[0]->geometry->location->lat, $json->results[0]->geometry->location->lng);
		}
		else
		{
			return array(11.621354, 76.14253698);
		}
		
	}

	
	public function find_Haversine($start, $finish) 
	{	
		$theta = $start[1] - $finish[1]; 
		$distance = (sin(deg2rad($start[0])) * sin(deg2rad($finish[0]))) + (cos(deg2rad($start[0])) * cos(deg2rad($finish[0])) * cos(deg2rad($theta))); 
		$distance = acos($distance); 
		$distance = rad2deg($distance); 
		$distance = $distance * 60 * 1.1515; 
		
		return round($distance, 2);
	}
	
	public function getcityname($cityid)	
	{
		$cityquery = "select city_name from ".CITY." where city_id =".$cityid; 
		$cityresult =  Db::query(Database::SELECT, $cityquery)
					->execute()
					->as_array();
	}
	
	// search nearest drivers around passengers pickup location
	
	public function nearestdrivers($lat,$long,$taxi_model,$passenger_id,$distance = NULL,$company_id,$unit,$service_type)
	{
		//echo $company_id;exit;
		//print_r($remove_driver_list);
		$free_driver = $this->availabledrivers($passenger_id,$company_id);
		//$this->currentdate = Commonfunction::getCurrentTimeStamp();	
		$where = '';
		$driver_list ='';
		$driver_count = '';
		$unit_conversion = "";
		$driver_list_array = array();

		if($free_driver > 0)
		{
			foreach($free_driver as $key => $value)
			{
				$driver_list_array[] = $value['id'];
			}
		}
		else
		{			
			$driver_list_array = array();
		}		

		// Find already rejected and timeout drivers in the current trip
		$flag=1;
		$get_passenger_driverid = $this->unset_driverlist_app($passenger_id,$flag,$company_id);
		if($get_passenger_driverid != "")
		{
			$remove_driver_list=explode(',',$get_passenger_driverid);			
		}
		else
		{
			$remove_driver_list = array();
		}
		// Exclude already rejected and timeout drivers in the current trip
		$driver_arraylist = array_diff($driver_list_array,$remove_driver_list); 	

		foreach($driver_arraylist as $key => $value)
		{
			$driver_count = 1;
			$driver_list .= "'".$value."',";
		}		
		
		if($driver_count > 0)
		{
			$driver_list = substr_replace($driver_list ,"",-1);
		}
		else
		{
			$driver_list = "''";
		}		
		$company_condition="";
		if($company_id != ""){
			$company_condition = "AND tmap.mapping_companyid = '$company_id' AND taxi.taxi_company = '$company_id'";
		}
		
		if(($taxi_model != 'All') && ($taxi_model != null)){
			$where.= " AND taxi.`taxi_model`='".$taxi_model."' ";
		}

		if($company_id == '')
		{
			$current_time = convert_timezone('now',TIMEZONE);
			$current_date = explode(' ',$current_time);
			$start_time = $current_date[0].' 00:00:01';
			$end_time = $current_date[0].' 23:59:59';
		}	
		else
		{
			$model_base_query = "select time_zone from  company where cid='$company_id' "; 
			$model_fetch = Db::query(Database::SELECT, $model_base_query)
					->execute()
					->as_array();			

			if($model_fetch[0]['time_zone'] != '')
			{
				$current_time = convert_timezone('now',$model_fetch[0]['time_zone']);
				$current_date = explode(' ',$current_time);
				$start_time = $current_date[0].' 00:00:01';
				$end_time = $current_date[0].' 23:59:59';
			}
			else
			{
				$current_time =	date('Y-m-d H:i:s');
				$start_time = date('Y-m-d').' 00:00:01';
				$end_time = date('Y-m-d').' 23:59:59';
			}
		}
		$distance_query="";
		if($distance)
		{
			$distance_query = "HAVING d_distance <='$distance'";
		}

		$service_types="";
		if($service_type){
		$service_types = " AND driver.taxi_service_type in ($service_type) ";
		}
		
		
		if($unit == '0')
		{
			$unit_conversion = '*1.609344';
		}

			$query =" select list.driver_id as driver_id,list.latitude as latitude,list.longitude as longitude,list.d_distance as distance_km,taxi.taxi_speed as taxi_speed from ( SELECT people.name,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515$unit_conversion) AS d_distance,(TIME_TO_SEC(TIMEDIFF('$current_time',driver.update_date))) AS updatetime_difference 
			FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where people.login_status='S'  $distance_query  AND driver.status='F' AND driver.shift_status='IN' $service_types AND  driver_id IN ($driver_list) order by d_distance ) as list 
			JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` 
			JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` 						
			JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid`  where updatetime_difference  <= '".LOCATIONUPDATESECONDS."' and  tmap.mapping_startdate <= '$current_time' AND  tmap.mapping_enddate >= '$current_time' AND tmap.`mapping_status`='A' AND p.account_balance > 0 ".$where." $company_condition  group by list.driver_id order by distance_km asc limit 0,50";

			//updatetime_difference  <= '51' and
			//"select list.name as name,list.driver_id as driver_id,list.latitude as latitude,list.longitude as longitude,list.d_distance as distance_km,list.update_date as location_update_date,list.updatetime_difference as updatetime_difference,taxi.taxi_id as taxi_id,taxi.taxi_speed as taxi_speed from ( SELECT people.name,driver.*,(((acos(sin((11.021366*pi()/180)) * sin((driver.latitude*pi()/180))+cos((11.021366*pi()/180)) * cos((driver.latitude*pi()/180)) * cos(((76.9166495- driver.longitude)* pi()/180))))*180/pi())*60*1.1515*1.609344) AS d_distance,(TIME_TO_SEC(TIMEDIFF('2014-07-14 09:45:50','2014-07-14 09:45:00'))) AS updatetime_difference  FROM driver AS driver JOIN people AS people ON driver.driver_id=people.id where people.login_status='S'   HAVING d_distance <='15' AND driver.status='F'  and driver.shift_status='IN' and driver_id IN ('35','36','37','48','50','51','52') order by d_distance ) as list JOIN taxi_driver_mapping as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN taxi as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` JOIN company AS comp ON tmap.`mapping_companyid`=comp.`cid` where updatetime_difference  <= '51' and tmap.mapping_startdate <= '2014-07-14 12:08:05' AND tmap.mapping_enddate >= '2014-07-14 12:08:05' AND tmap.`mapping_status`='A' AND tmap.mapping_companyid = '1' AND taxi.taxi_company = '1' group by list.driver_id"
			//echo $query.'<br/><br/>';

		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();

			
		return $result;
		
	}	


	public function availabledrivers($availabledrivers,$company_id ='')
	{
		//print_r($request);
				

		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $current_date[0].' 00:00:01';
		$end_time = $current_date[0].' 23:59:59';
		
		//$cuurentdate = date('Y-m-d H:i:s');
		//$enddate = date('Y-m-d').' 23:59:59';
			

		$company_condition="";
		if($company_id != ""){
			$company_condition = "AND taximapping.mapping_companyid = '$company_id' AND people.company_id = '$company_id' AND taxi.taxi_company = '$company_id'";
		}

		 $sql ="SELECT people.id,taxi.taxi_capacity ,taxi.taxi_id  ,(select check_package_type from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company  order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM ".TAXI." as taxi 
		 JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid 
		 JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid 
		 JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid WHERE people.status = 'A' AND taxi.taxi_status = 'A' AND taxi.taxi_availability = 'A' AND people.availability_status = 'A'   and people.booking_limit > (SELECT COUNT( passengers_log_id ) FROM  ".PASSENGERS_LOG." WHERE driver_id = people.id AND  `createdate` >='".$start_time."' AND  `travel_status` =  '1' AND booking_from != '2')  
               AND taximapping.mapping_status = 'A' $company_condition AND company.company_status='A' AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time'  group by taxi_id Having ( check_package_type = 'T' or upgrade_expirydate >='$current_time')";
		 //AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time'
		 //AND people.notification_setting = '1'
//echo $sql;
		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}	
}
