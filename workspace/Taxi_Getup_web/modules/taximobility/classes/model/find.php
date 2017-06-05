<?php defined('SYSPATH') OR die('No Direct Script Access');

/******************************************

* Contains Finding the Locations details

* @Package: ConnectTaxi

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************/

class Model_Find extends Model
{
	
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
		$result = DB::select(MOTORMODEL.'.model_id',MOTORMODEL.'.model_name')->from(MOTORMODEL)->join(MOTORCOMPANY, 'LEFT')->on(MOTORMODEL.'.motor_mid', '=', MOTORCOMPANY.'.motor_id')->where('motor_mid','=',$motorid)->where('motor_status','=','A')->where('model_status','=','A')->order_by('model_id','ASC')->execute()->as_array();
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

		//$this->currentdate = Commonfunction::getCurrentTimeStamp();
		$additional_fields = $this->taxi_additionalfields();
		$add_field = "";		
		
		if($request){
			//$add = array();
			foreach($additional_fields as $res){
				//$add_field[] = $_POST[$res['field_name']];
				$fi_n = $res['field_name'];
				if(isset($request[$fi_n])){
					//$add_field[$fi_n] = $_POST[$fi_n];
					if($request[$fi_n]!=""){
						$add_field .= " AND adds.`".$fi_n."`='".$request[$fi_n]."'";
					}
				}
				
			}
				///echo $add_field;
		}
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
		$unit=0;
		if($unit == '0')
		{
			$unit_conversion = '*1.609344';
		}



		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $current_date[0].' 00:00:01';
		$end_time = $current_date[0].' 23:59:59';

		if(FARE_SETTINGS == 2)
		{
			$query =" select list.name as name,list.driver_id as driver_id,list.phone as phone,list.profile_picture as d_photo,list.id as id,list.latitude as latitude,list.d_distance as distance_km,list.longitude as longitude,list.status as status,list.distance as distance,list.distance as distance_miles,comp.company_name as company_name,comp.cid as get_companyid,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".id=comp.cid ) as cancellation_nfree,(SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km, taxi.taxi_no as taxi_no,taxi.taxi_image as taxi_image,taxi.taxi_capacity as taxi_capacity,taxi.taxi_id as taxi_id,taxi.taxi_speed as taxi_speed from ( SELECT people.name,people.profile_picture,people.phone,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515) AS distance,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515$unit_conversion) AS d_distance FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where people.login_status='S' HAVING distance <= " .$distance." AND driver.status='F' AND driver.shift_status='IN' and driver_id IN ($driver_list) order by distance ) as list JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` 
			JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model` 
			JOIN ".COMPANY_MODEL_FARE." as company_model_fare ON company_model_fare.`model_id`=taxi.`taxi_model` 
			JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid` $additional_field_join where  company_model_fare.company_cid=comp.cid AND tmap.mapping_startdate <='$current_time' AND  tmap.mapping_enddate >='$current_time' and tmap.`mapping_status`='A' ".$where.$add_field." group by list.driver_id";
			
			//
		}
		else
		{
		 $query =" select list.name as name,list.driver_id as driver_id,list.phone as phone,list.profile_picture as d_photo,list.id as id,list.latitude as latitude,list.longitude as longitude,list.d_distance as distance_km,list.status as status,list.distance as distance,list.distance as distance_miles,comp.company_name as company_name,comp.cid as get_companyid,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".id=comp.cid ) as cancellation_nfree,(SUM(model.base_fare)*($city_model_fare)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*($city_model_fare)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*($city_model_fare)/100) + model.below_km as below_km,(SUM(model.above_km)*($city_model_fare)/100) + model.above_km as above_km, taxi.taxi_no as taxi_no,taxi.taxi_image as taxi_image,taxi.taxi_capacity as taxi_capacity,taxi.taxi_id as taxi_id,taxi.taxi_speed as taxi_speed from ( SELECT people.name,people.profile_picture,people.phone,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515) AS distance,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515$unit_conversion) AS d_distance FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where people.login_status='S' HAVING distance <= " .$distance." AND driver.status='F' AND driver.shift_status='IN' and driver_id IN ($driver_list) order by distance ) as list JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model` JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid` $additional_field_join where  tmap.mapping_startdate <='$current_time' AND  tmap.mapping_enddate >='$current_time'  AND tmap.`mapping_status`='A' ".$where.$add_field." group by list.driver_id";
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
		
			
		$sql ="SELECT people.id,taxi.taxi_id  ,(select check_package_type from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company order by upgrade_id desc limit 0,1 ) as upgrade_expirydate	FROM ".TAXI." as taxi JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid WHERE people.status = 'A' 	AND taxi.taxi_status = 'A' AND taxi.taxi_availability = 'A' AND people.availability_status = 'A' AND people.booking_limit > (SELECT COUNT( passengers_log_id ) FROM  ".PASSENGERS_LOG." WHERE driver_id = people.id AND  `createdate` >='".$start_time."' AND  `travel_status` =  '1' AND booking_from != '2') AND taximapping.mapping_status = 'A' $company_condition AND company.company_status='A' $capacity_where AND taximapping.mapping_startdate <='$current_time' AND  taximapping.mapping_enddate >='$current_time'   group by taxi_id Having ( check_package_type = 'T' or upgrade_expirydate >='$current_time' )";
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
		$additional_fields = $this->taxi_additionalfields();
		
		$field_count = count($additional_fields);
		$where_cond = '';

            if($field_count > 0)
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
	    }	

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
		$additional_fields = $this->taxi_additionalfields();
		
		
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

	
	public function search_driver_mobileapp($lat,$long,$distance = NULL,$passenger_id,$taxi_fare_km,$taxi_type,$taxi_model,$maximum_luggage,$city_name,$sub_log_id,$company_id,$unit)
		
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
		//$additional_fields = $this->taxi_additionalfields();
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

		if(FARE_SETTINGS == 2)
		{
			 $query =" select list.name as name,model.model_name,list.driver_id as driver_id,list.phone as phone,list.profile_picture as d_photo,list.id as id,list.latitude as latitude,list.longitude as longitude,list.status as status,list.distance as distance,list.distance as distance_miles,list.latitude as driver_latitude,list.longitude as driver_longitude,list.updatetime_difference as updatetime_difference,comp.company_name as company_name,comp.cid as get_companyid,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=comp.cid ) as cancellation_nfree,(select company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=comp.cid ) as company_tax,(SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km, company_model_fare.min_km,company_model_fare.below_above_km,taxi.taxi_no as taxi_no,taxi.taxi_image as taxi_image,taxi.taxi_capacity as taxi_capacity,taxi.taxi_id as taxi_id,taxi.taxi_speed as taxi_speed from ( SELECT people.name,people.profile_picture,people.phone,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515$unit_conversion) AS distance,(TIME_TO_SEC(TIMEDIFF('$current_time',driver.update_date))) AS updatetime_difference  FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where people.login_status='S'  $distance_query AND driver.status='F' AND driver.shift_status='IN' and driver_id IN ($driver_list) order by distance ) as list JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` 
			JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model` 
			JOIN ".COMPANY_MODEL_FARE." as company_model_fare ON company_model_fare.`model_id`=taxi.`taxi_model` 
			JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid`  $additional_field_join where  tmap.mapping_startdate <='$current_time' AND  tmap.mapping_enddate >='$current_time' and company_model_fare.company_cid=comp.cid AND tmap.`mapping_status`='A' ".$where.$add_field." $company_condition group by list.driver_id";
			//
		}
		else
		{
	
			$query =" select list.name as name,model.model_name,list.driver_id as driver_id,list.phone as phone,list.profile_picture as d_photo,list.id as id,list.latitude as latitude,list.longitude as longitude,list.status as status,list.distance as distance,list.distance as distance_miles,list.updatetime_difference as updatetime_difference,comp.company_name as company_name,comp.cid as get_companyid,(select cancellation_fare from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=comp.cid ) as cancellation_nfree,(select company_tax from ".COMPANYINFO." where ".COMPANYINFO.".company_cid=comp.cid ) as company_tax,(SUM(model.base_fare)*($city_model_fare)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*($city_model_fare)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*($city_model_fare)/100) + model.below_km as below_km,(SUM(model.above_km)*($city_model_fare)/100) + model.above_km as above_km,model.min_km,model.below_above_km, taxi.taxi_no as taxi_no,taxi.taxi_image as taxi_image,taxi.taxi_capacity as taxi_capacity,taxi.taxi_id as taxi_id,taxi.taxi_speed as taxi_speed from ( SELECT people.name,people.profile_picture,people.phone,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515) AS distance,(TIME_TO_SEC(TIMEDIFF('$current_time',driver.update_date))) AS updatetime_difference FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where  people.login_status='S' $distance_query AND driver.status='F' AND driver.shift_status='IN' and driver_id IN ($driver_list) order by distance ) as list JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model` JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid` $additional_field_join where tmap.mapping_startdate <='$current_time' AND  tmap.mapping_enddate >='$current_time' AND tmap.`mapping_status`='A' ".$where.$add_field." $company_condition group by list.driver_id";
		
	}
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
	
		$pickup_time = mysql_real_escape_string($request['bookingtime']);
		$pickupdrop = '0';
		$waitingtime = '0';
		$travel_status = '0';
		$pass_logid = $request['pass_logid'];

		// Get Pickup & Drop location Lat & Long using Google API 
		$pickup = $this->getlatitudelong(mysql_real_escape_string($request['cur_loc']));
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
		$drop = $this->getlatitudelong(mysql_real_escape_string($request['drop_loc']));
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

		
		$values_array = array($passenger_id,mysql_real_escape_string($driver_details[0]['driver_id']),$company_id,mysql_real_escape_string($request['cur_loc']),$pickup_latitude,$pickup_longitude,mysql_real_escape_string($request['drop_loc']),$drop_latitude,$drop_longitude,mysql_real_escape_string($request['no_passengers']),$approx_distance,$approx_fare,$update_time,$pickupdrop,$waitingtime,$travel_status,$this->currentdate,mysql_real_escape_string($driver_details[0]['taxi_id']),$city_id,$pass_logid,$company_id,$company_tax,'1','1');



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
	
	public function nearestdrivers($lat,$long,$taxi_model,$passenger_id,$distance = NULL,$company_id,$unit)
	{
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
		
		if($unit == '0')
		{
			$unit_conversion = '*1.609344';
		}

			$query =" select list.name as name,list.driver_id as driver_id,list.latitude as latitude,list.longitude as longitude,list.d_distance as distance_km,taxi.taxi_id as taxi_id,taxi.taxi_speed as taxi_speed,list.updatetime_difference as updatetime_difference from ( SELECT people.name,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515$unit_conversion) AS d_distance,(TIME_TO_SEC(TIMEDIFF('$current_time',driver.update_date))) AS updatetime_difference 
			FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where people.login_status='S' $distance_query AND driver.status='F' AND driver.shift_status='IN' and driver_id IN ($driver_list) order by d_distance ) as list 
			JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` 
			JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` 						
			JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid`  where updatetime_difference  <= '".LOCATIONUPDATESECONDS."' and tmap.mapping_startdate <= '$current_time' AND  tmap.mapping_enddate >= '$current_time' AND tmap.`mapping_status`='A' ".$where." $company_condition group by list.driver_id";

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

		 $sql ="SELECT people.id,taxi.taxi_id  ,(select check_package_type from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company  order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from ".PACKAGE_REPORT." where ".PACKAGE_REPORT.".upgrade_companyid = ".TAXI.".taxi_company order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM ".TAXI." as taxi 
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
	//to get model fare details
	public function modelDets($modelId)
	{
		$companyId = COMPANY_CID;
		if(FARE_SETTINGS == 2 && !empty($companyId))
		{
			$model_base_query = "select distinct ".MOTORMODEL.".model_id,".COMPANY_MODEL_FARE.".model_name,".COMPANY_MODEL_FARE.".model_size,CONCAT('".CURRENCY."',".COMPANY_MODEL_FARE.".base_fare) as base_fare, CONCAT('".CURRENCY."',".COMPANY_MODEL_FARE.".min_fare) as min_fare, CONCAT('( ',".COMPANY_MODEL_FARE.".min_km,' ','".UNIT_NAME."',' )') as min_km, CONCAT('( ',".COMPANY_MODEL_FARE.".below_above_km,' ','".UNIT_NAME."',' )') as below_above_km, CONCAT('".CURRENCY."',".COMPANY_MODEL_FARE.".below_km) as below_km, CONCAT('".CURRENCY."',".COMPANY_MODEL_FARE.".above_km) as above_km, CONCAT('".CURRENCY."',".COMPANY_MODEL_FARE.".cancellation_fare) as cancellation_fare, ".COMPANY_MODEL_FARE.".night_charge, DATE_FORMAT(".COMPANY_MODEL_FARE.".night_timing_from,'%h:%i %p') as night_timing_from, DATE_FORMAT(".COMPANY_MODEL_FARE.".night_timing_to,'%h:%i %p') as night_timing_to, ".COMPANY_MODEL_FARE.".night_fare, ".COMPANY_MODEL_FARE.".evening_charge, DATE_FORMAT(".COMPANY_MODEL_FARE.".evening_timing_from,'%h:%i %p') as evening_timing_from, DATE_FORMAT(".COMPANY_MODEL_FARE.".evening_timing_to,'%h:%i %p') as evening_timing_to, ".COMPANY_MODEL_FARE.".evening_fare from ".COMPANY_MODEL_FARE." left join ".MOTORMODEL." on ".MOTORMODEL.".model_id=".COMPANY_MODEL_FARE.".model_id where ".COMPANY_MODEL_FARE.".company_cid='$companyId' and ".COMPANY_MODEL_FARE.".fare_status='A' and ".COMPANY_MODEL_FARE." .model_id = '$modelId' order by model_id ASC"; 
		}
		else
		{
			$model_base_query="SELECT `model_id`, `model_name`, `model_size`, CONCAT('".CURRENCY."',`base_fare`) as base_fare, CONCAT('".CURRENCY."',`min_fare`) as min_fare, CONCAT('".CURRENCY."',`cancellation_fare`) as cancellation_fare, CONCAT('( ',`min_km`,' ','".UNIT_NAME."',' )') as min_km, CONCAT('( ',`below_above_km`,' ','".UNIT_NAME."',' )') as `below_above_km`, CONCAT('".CURRENCY."',`below_km`) as below_km, CONCAT('".CURRENCY."',`above_km`) as above_km, night_charge, DATE_FORMAT(`night_timing_from`,'%h:%i %p') as night_timing_from, DATE_FORMAT(`night_timing_to`,'%h:%i %p') as night_timing_to, `night_fare`, evening_charge, DATE_FORMAT(`evening_timing_from`,'%h:%i %p') as evening_timing_from, DATE_FORMAT(`evening_timing_to`,'%h:%i %p') as evening_timing_to, `evening_fare` FROM ".MOTORMODEL." WHERE `model_status` = 'A' and `model_id` = '$modelId' ORDER BY `model_id` ASC";
		}
		$result = Db::query(Database::SELECT, $model_base_query)->execute()->as_array();
		return $result;
	}
	/** Function to get active free driver list **/
	public function getFreeDriverList($modelId,$lat,$long,$current_time)
    {
        $unit_conversion = (UNIT == 0) ? '*1.609344' : '' ;// 0 - KM, 1 - MILES
        
        $query =" select list.driver_id as driver_id,list.latitude as latitude,list.driver_name,list.longitude as longitude,list.d_distance as distance_km,taxi.taxi_speed as taxi_speed, taxi.taxi_id, comp.cid from ( SELECT people.name as driver_name,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515$unit_conversion) AS d_distance,(TIME_TO_SEC(TIMEDIFF('$current_time',driver.update_date))) AS updatetime_difference 
			FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where people.login_status='S' AND people.booking_limit > (SELECT COUNT( passengers_log_id ) FROM  ".PASSENGERS_LOG." WHERE driver_id = people.id AND  `createdate` >='$current_time' AND  `travel_status` =  '1' AND booking_from != '2') HAVING d_distance <='".DEFAULTMILE."'  AND driver.status='F' AND driver.shift_status='IN' order by d_distance ) as list  JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid`  where updatetime_difference  <= '".LOCATIONUPDATESECONDS."' and  tmap.mapping_startdate <= '$current_time' AND  tmap.mapping_enddate >= '$current_time' AND tmap.`mapping_status`='A' AND taxi.taxi_model = '$modelId' group by list.driver_id order by distance_km asc limit 0,50";
		//echo $query;exit;
        $result = Db::query(Database::SELECT, $query)->execute()->as_array();
        return $result;
    }
    /** to get current time of a location(country) from latittude and longitude **/
	public function getpickupTimezone($lat,$lng)
	{
		
		try{
		  $url = 'https://maps.googleapis.com/maps/api/timezone/json?location='.trim($lat).','.trim($lng).'&timestamp=1&key='.GOOGLE_TIMEZONE_API_KEY;  
		  //echo $url;exit;    
		  $json = @file_get_contents($url);
		  $data=json_decode($json);
		  $status = ($data) ? $data->status : 0;
		  if($status=="OK")
			return ($data) ? $data->timeZoneId : TIMEZONE;
		  else
			return TIMEZONE;
		} catch (Kohana_Exception $e) {
			return TIMEZONE;
		} 
	  
	}
    /** function for booking form validation **/
    public function validate_bookingForm($arr)
    {
		$validation = Validation::factory($arr)
           		->rule('name', 'not_empty')
           		->rule('country_code', 'not_empty')
           		->rule('mobile_number', 'not_empty')
           		->rule('email', 'not_empty')
           		->rule('pickup_location', 'not_empty')
           		->rule('promocode', 'min_length', array(':value', '6')) 
				->rule('promocode', 'max_length', array(':value', '6'));
           if(Arr::get($arr,'bookType')==1)
			{
				$validation->rule('pickup_time', 'not_empty');
				$validation->rule('pickup_time', 'Model_Find::checklaterTime', array(':value',$arr['currentTime']));
			}
		return $validation;
           		
	}
	/** function to check later pickuptime is greater than 1 hour **/
	public static function checklaterTime($pickupTime, $currentTime)
	{
		$diffSecs = strtotime($pickupTime) - strtotime($currentTime);
		$diffHrs = round($diffSecs/3600);
		if($diffHrs < 1) {
			return false;
		} else {
			return true;
		}
	}
	/** function for save booking **/
	public function saveBooking($post)
	{
		//function to get nearest driver and his company id
		$freeDrivers = $this->getFreeDriverList($post['model'],$post['pass_latitude'],$post['pass_longitude'],$post['currentTime']);
		$drArr = array();
		$tripId = 0;
		$passengerId = $post['passengerId'];//
		$driverCnt = count($freeDrivers);
		$pickupTime = ($post['bookType'] == 0) ? $post['currentTime'] : $post['pickup_time'];
		$approx_distance = (isset($post['appx_distance'])) ? $post['appx_distance'] : 0;
		$approx_fare = (isset($post['appx_amount'])) ? $post['appx_amount'] : 0;
		if($driverCnt > 0){
			$nearestDriver = $freeDrivers[0]['driver_id'];
			$companyId = $freeDrivers[0]['cid'];
			$taxiId = $freeDrivers[0]['taxi_id'];
			foreach($freeDrivers as $drivers) {
				$drArr[] = $drivers['driver_id'];
			}
			$driverStr = implode(",",$drArr);
			$commonmodel = Model::factory('commonmodel');
			$createdTime = $commonmodel->getcompany_all_currenttimestamp($companyId);
			$bookBy = ($post['bookType'] == 0) ?  1 : 2;//1-passenger, 2-dispatcher
			//for later booking don not save driver, taxi, company
			if($post['bookType'] != 0){
				$nearestDriver = 0;
				$taxiId = 0;
				$companyId = 0;
			}
			$fieldname_array = array('passengers_id','driver_id','taxi_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude','pickup_time','bookby','now_after','promocode','taxi_modelid','approx_distance','approx_fare');
			$values_array = array($passengerId,$nearestDriver,$taxiId,$companyId,$post['pickup_location'],$post['pickup_latitude'],$post['pickup_longitude'],$post['drop_location'],$post['drop_latitude'],$post['drop_longitude'],$pickupTime,$bookBy,$post['bookType'],$post['promocode'],$post['model'],$approx_distance,$approx_fare);
			$trip = DB::insert(PASSENGERS_LOG, $fieldname_array)->values($values_array)->execute();
			$tripId = ($trip) ? $trip[0] : 0;
			
			if($tripId != 0) {
				//to insert in split fare table
				$fieldSplArr = array('trip_id','friends_p_id','fare_percentage','createdate','approve_status','appx_amount');
				$valueSplArr = array($tripId,$passengerId,'100',$createdTime,'A',0);
				$split_insert_result = DB::insert(P_SPLIT_FARE, $fieldSplArr)->values($valueSplArr)->execute();
				if($post['bookType'] == 0) {
					$fields = array('trip_id','available_drivers','total_drivers','selected_driver','createdate');
					$vals = array($tripId,$driverStr,$driverStr,$nearestDriver,$createdTime);
					DB::insert(DRIVER_REQUEST_DETAILS, $fields)->values($vals)->execute();
				}
			}
			$responseCode = ($post['bookType'] == 0) ?  1 : 2;
		} else {
			if($post['bookType'] == 1) {
				$fieldname_array = array('passengers_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude','pickup_time','bookby','now_after','taxi_modelid','promocode','approx_distance','approx_fare');
				$values_array = array($passengerId,$post['pickup_location'],$post['pickup_latitude'],$post['pickup_longitude'],$post['drop_location'],$post['drop_latitude'],$post['drop_longitude'],$pickupTime,'2',$post['bookType'],$post['model'],$post['promocode'],$approx_distance,$approx_fare);
				$trip = DB::insert(PASSENGERS_LOG, $fieldname_array)->values($values_array)->execute();
				if($trip) {
					$tripId = $trip[0];
					//to insert in split fare table
					$fieldSplArr = array('trip_id','friends_p_id','fare_percentage','createdate','approve_status','appx_amount');
					$valueSplArr = array($tripId,$passengerId,'100',$pickupTime,'A',0);
					$split_insert_result = DB::insert(P_SPLIT_FARE, $fieldSplArr)->values($valueSplArr)->execute();
				}
				$responseCode = 2;
			} else {
				$responseCode = 3;
			}
		}
		return array($responseCode,$driverCnt,$tripId);
	}
	/** function for signup form validation **/
    public function validate_signupForm($arr)
    {
		$validation = Validation::factory($arr)
				->rule('email', 'not_empty')
				->rule('email', 'email')
				->rule('email', 'min_length', array(':value', '8')) 
				->rule('email', 'max_length', array(':value', '50'))
				->rule('email', 'Model_Find::signupEmailExist', array(':value'))
           		->rule('password', 'not_empty')
           		->rule('password', 'min_length', array(':value', '6')) 
				->rule('password', 'max_length', array(':value', '24'))
           		->rule('confirm_password', 'not_empty')
           		->rule('confirm_password', 'min_length', array(':value', '6')) 
				->rule('confirm_password', 'max_length', array(':value', '24'))
           		->rule('confirm_password', 'Model_Find::checkConfirmPassword', array(':value', $arr['password']))
           		->rule('country_code', 'not_empty')
           		->rule('country_code', 'min_length', array(':value', '2'))
				->rule('country_code', 'max_length', array(':value', '5'))
           		->rule('mobile_number', 'not_empty')
           		->rule('mobile_number', 'phone', array(':value'))
           		->rule('mobile_number', 'min_length', array(':value', '7'))
				->rule('mobile_number', 'max_length', array(':value', '20'))
				->rule('mobile_number', 'Model_Find::signupPhoneExist', array(':value', $arr['country_code']));
			if(REFERRAL_SETTINGS == 1){
				$validation->rule('referral_code', 'Model_Find::checkRefCodeExist', array(':value'));
			}
           		$validation->rule('salutation', 'not_empty')
           		->rule('first_name', 'not_empty')
           		->rule('first_name', 'min_length', array(':value', '4')) 
				->rule('first_name', 'max_length', array(':value', '35'))
           		->rule('last_name', 'not_empty')
           		->rule('last_name', 'min_length', array(':value', '4')) 
				->rule('last_name', 'max_length', array(':value', '35'));
				if(DEFAULT_SKIP_CREDIT_CARD) {
					$validation->rule('credit_card_number', 'not_empty');
					$validation->rule('credit_card_number', 'Model_Find::checkValidCard', array(':value'));
					$validation->rule('month', 'not_empty');
					$validation->rule('year', 'not_empty');
					$validation->rule('year', 'Model_Find::checkMonthYear', array(':value', $arr['month']));
					$validation->rule('cvv', 'not_empty');
					$validation->rule('cvv', 'min_length', array(':value', '3'));
					$validation->rule('cvv', 'max_length', array(':value', '4'));
				}
		return $validation;
	}
	/** function to check email id exist **/
	public static function signupEmailExist($email)
	{
		$result = DB::select(array( DB::expr('COUNT(id)' ), 'total'))->from(PASSENGERS)->where('email','=',':email')->parameters(array(':email'=>$email))->execute()->get('total');
		if($result > 0) {
			return false;
		} else {
			return true;
		}
	}
	/** Function to check given referral code exist or not **/
	public static function checkRefCodeExist($referralCode)
	{
		$result = DB::select(array( DB::expr('COUNT(id)' ), 'total'))->from(PASSENGERS)->where('referral_code','=',':referralCode')->parameters(array(':referralCode'=>$referralCode))->execute()->get('total');
		if($result == 0) {
			return false;
		} else {
			return true;
		}
	}
	/** function to check email id exist **/
	public static function signupPhoneExist($mobileno,$mobilecode)
	{
		$result = DB::select(array( DB::expr('COUNT(id)' ), 'total'))->from(PASSENGERS)->where('country_code','=',':mobileCode')->where('phone','=',':mobileNo')->parameters(array(':mobileCode'=>$mobilecode,':mobileNo'=>$mobileno))->execute()->get('total');
		if($result > 0) {
			return false;
		} else {
			return true;
		}
	}
	/** function to check password and confirm password are same **/
	public static function checkConfirmPassword($confPass, $pass)
	{
		if($confPass != $pass) {
			return false;
		} else {
			return true;
		}
	}
	/** function to check the credit card number is valid or not **/
	public static function checkValidCard($creditcard)
	{
		$siteusers = Model::factory('siteusers');
		$checkSts = $siteusers->isVAlidCreditCard($creditcard,"",true);
		if($checkSts == 0){
			return false;
		} else {
			return true;
		}
	}
	/** function to check the expiration month and year is valid or not **/
	public static function checkMonthYear($year,$month)
	{
		if(($year < date('Y')) || ($year == date('Y') && $month < date('m')) || ($month < 1 || $month > 12)) {
			return false;
		} else {
			return true;
		}
	}
	/** function for passenger signup **/
	public function passengerSignup($post)
	{
		$this->session = Session::instance();
		$referralcode_query = "select concat(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1)) as referral_code";

		$referralcode_result = Db::query(Database::SELECT, $referralcode_query)->execute()->as_array();
		//this referral code generated automatically. Passenger can refer this

		$auto_referral_code = $referralcode_result[0]['referral_code'];
		/** Referrral key generator **/
		/** to get referral setting and amount from siteinfo table **/
		$siteInfo = $this->siteinfo_details();
		$referralAmount = $siteInfo[0]['referral_amount'];
		$currentTime = date("Y-m-d H:i:s");
		/** Insert in passenger table **/
		
		$fieldname_array = array('name','lastname','email','password','org_password','country_code','phone','referral_code','referral_code_amount','referral_code_limit','activation_status','user_status','created_date','updated_date','device_type','login_status','login_from','fb_user_id','fb_access_token');
		$values_array = array($post['first_name'],$post['last_name'],$post['email'],md5($post['password']),$post['password'],$post['country_code'],$post['mobile_number'],$auto_referral_code,$referralAmount,'1','1','A',$currentTime,$currentTime,'3','S','4',$post['fb_id'],$post['fb_access_token']);
		$passresult = DB::insert(PASSENGERS, $fieldname_array)->values($values_array)->execute();
		if($passresult){
			$referral_code = (!empty($post['referral_code'])) ? $post['referral_code'] : '';
			
			if(!empty($referral_code)) {
				//to get the referral amount and referral limit from the referral code
				$referral_sql = "SELECT id,referral_code_amount,referral_code_limit FROM ".PASSENGERS." WHERE referral_code='$referral_code'";
				$refer_dets = Db::query(Database::SELECT, $referral_sql)->execute()->as_array();
				if(count($refer_dets) > 0) {
					$ref_fieldArr = array('passenger_id','referral_code','referral_amount','referral_limit','referred_by','createdate');
					$ref_valueArr = array($passresult[0],$referral_code,$refer_dets[0]['referral_code_amount'],$refer_dets[0]['referral_code_limit'],$refer_dets[0]['id'],$currentTime);
					$passRef = DB::insert(PASSENGER_REFERRAL, $ref_fieldArr)->values($ref_valueArr)->execute();
					//to update the referral amount into the wallet column in passenger table
					$update_array = array('wallet_amount'=>$refer_dets[0]['referral_code_amount']);
					$update_wallet_amount = DB::update(PASSENGERS)->set($update_array)->where('id', '=', $passresult[0])->execute();
				}
			}
			//save credit card details
			if(DEFAULT_SKIP_CREDIT_CARD) {
				$this->saveCreditCard($passresult[0],$post['first_name'],$post['email'],$post['credit_card_number'],$post['month'],$post['year'],$post['cvv'],$post['paymentResult'],$post['preAuthorizeAmount'],$post['fcardtype']);
			}
			
			$this->session->set("passenger_name",$post['first_name']);
			$this->session->set("id",$passresult[0]);
			$this->session->set("passenger_email",$post['email']);
			$this->session->set("passenger_phone",$post['mobile_number']);
			$this->session->set("passenger_phone_code",$post['country_code']);

			$this->session->delete("fb_name");
			$this->session->delete("fb_email");
			$this->session->delete("fb_id");
			$this->session->delete("fb_access_token");

			return 1;
		} else {
			return 0;
		}
	}
	/** function to save credit card details after pre authorization **/
	public function saveCreditCard($passId,$passName,$passEmail,$cardNo,$expMnt,$expYear,$cvv,$paymentResult,$preAuthorizeAmount,$fcardtype)
	{
		$cardNo = encrypt_decrypt('encrypt',$cardNo);	
		$card_result = DB::insert(PASSENGERS_CARD_DETAILS, array('passenger_id','passenger_email','card_type','creditcard_no','card_holder_name','expdatemonth','expdateyear','default_card','pre_transaction_id','pre_transaction_amount','card_type_description'))->values(array($passId,$passEmail,'P',$cardNo,$passName,$expMnt,$expYear,'1',$paymentResult,$preAuthorizeAmount,$fcardtype))->execute();
		if($card_result){
			$void_transaction=$this->voidTransactionAfterPreAuthorize($card_result[0],$paymentResult);
		}
	}
	//credit card validation for braintree
	public function creditcardPreAuthorization($passengerName,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$preauthorize_amount)
	{	
		$paypal_details = $this->payment_gateway_details();	
		$payment_gateway_username = isset($paypal_details[0]['payment_gateway_username'])?$paypal_details[0]['payment_gateway_username']:"";
		$payment_gateway_password = isset($paypal_details[0]['payment_gateway_password'])?$paypal_details[0]['payment_gateway_password']:"";
		$payment_gateway_key = isset($paypal_details[0]['payment_gateway_key'])?$paypal_details[0]['payment_gateway_key']:"";
		$currency_format = isset($paypal_details[0]['gateway_currency_format'])?$paypal_details[0]['gateway_currency_format']:"";
		$payment_method = isset($paypal_details[0]['payment_method'])?$paypal_details[0]['payment_method']:"";
		$payment_types=isset($paypal_details[0]['payment_type'])?$paypal_details[0]['payment_type']:"";
		
		$firstName=$passengerName;
		$lastName='';
		$phone='';
		$email='';
		$street='';
		$state='';
		$city='';
		$country_code='';
		$zipcode='';
		$code=0;
		$fcardtype="";
		$fresult = '';
		if($payment_types == 1) {
			$payment_action='sale';
			$request  = 'METHOD=DoDirectPayment';
			$request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
			$request .= '&USER=' . urlencode($payment_gateway_username);
			$request .= '&PWD=' . urlencode($payment_gateway_password);
			$request .= '&SIGNATURE=' . urlencode($payment_gateway_key);

			$request .= '&CUSTREF=';
			$request .= '&PAYMENTACTION=' . $payment_action; //type
			$request .= '&AMT=' . urlencode($preauthorize_amount); //   $amount = urlencode($data['amount']);

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
			
			if(isset($nvpArray["ACK"]) && strtoupper($nvpArray["ACK"]) == "SUCCESS") {
				$fresult= isset ($nvpArray['TRANSACTIONID']) ? $nvpArray['TRANSACTIONID'] : '';
				$code = 1;
			} else {
				$code = 0;
				$fresult= isset ($nvpArray['L_LONGMESSAGE0']) ? $nvpArray['L_LONGMESSAGE0'] : '';
			}
		} else {
			require_once(APPPATH.'vendor/braintree-payment/lib/Braintree.php');
			$pay_type=($payment_method =="L")?"live":"sandbox";
			try
			{
				if ($pay_type=="live") 
				{
					Braintree_Configuration::environment('production');
				}
				else
				{
					Braintree_Configuration::environment('sandbox');			
				}
				Braintree_Configuration::merchantId($payment_gateway_username);//your_merchant_id
				Braintree_Configuration::publicKey($payment_gateway_password);//your_public_key
				Braintree_Configuration::privateKey($payment_gateway_key);
					
				$result=Braintree_Transaction::sale(array(
						'amount' => $preauthorize_amount,				    
						'creditCard' => array(
				
						'cardholderName' => $firstName,
						'number' => $creditcard_no,//$_POST['creditCard']
						'expirationMonth' =>$expdatemonth,//$_POST['month']
						'expirationYear' => $expdateyear,//$_POST['year']
						'cvv' => $creditcard_cvv,
						),
						
						'customer' => array(
						'firstName'=>$firstName,
						'lastName'=>$lastName,
						'company' => '',
						'phone' => $phone,
						'fax' => '',
						'website' => '',						
						 'email'=>$email
					  ),
						
						'shipping' => array(
						'firstName' => $firstName,
						'lastName' => $lastName,
						'company' => '',
						'streetAddress' => $street,
						'extendedAddress' => '',
						'locality' => $state,
						'region' => $country_code,
						'postalCode' => $zipcode,
						'countryCodeAlpha2' =>$country_code,
					  ),						
					 'options' => array(
										  'submitForSettlement'=> false,
										 )
					 ));
			}
			catch(Braintree_Exception $message)
			{
				//$fresult='Payment Gateway Exception';
				$fresult='';
				$code=0;
				return array($code,$fresult);
			}
			
			if($result->success)
			{
				$fresult=$result->transaction->id;
				$fcardtype =$result->transaction->creditCardDetails->cardType;						
				$code=1;
			} else {
				$code = 0;
				$fresult = "";
				foreach (($result->errors->deepAll()) as $error) {
					$fresult .= $error->message;
				}
			}
		}			
		return array($code,$fresult,$fcardtype);
	}
	/******************** Get default payment gateway of Specific company *********************/
	public function payment_gateway_details()
	{
		$sql = "SELECT payment_gateway_id as payment_type,paypal_api_username as payment_gateway_username,paypal_api_password as payment_gateway_password,paypal_api_signature as payment_gateway_key,currency_code as gateway_currency_format,payment_method as payment_method FROM ".PAYMENT_GATEWAYS."  WHERE company_id = '0' and default_payment_gateway=1";
		$result =  Db::query(Database::SELECT, $sql)->execute()->as_array(); 
		return $result;

	}
	//Refun the amount after the preauthorization completed
	public function voidTransactionAfterPreAuthorize($cardId,$preTransactId)
	{
		$commonmodel = Model::factory('commonmodel');
		$paypal_details = $this->payment_gateway_details();	
		$payment_gateway_username = isset($paypal_details[0]['payment_gateway_username'])?$paypal_details[0]['payment_gateway_username']:"";
		$payment_gateway_password = isset($paypal_details[0]['payment_gateway_password'])?$paypal_details[0]['payment_gateway_password']:"";
		$payment_gateway_key = isset($paypal_details[0]['payment_gateway_key'])?$paypal_details[0]['payment_gateway_key']:"";
		$currency_format = isset($paypal_details[0]['gateway_currency_format'])?$paypal_details[0]['gateway_currency_format']:"";
		$payment_method = isset($paypal_details[0]['payment_method'])?$paypal_details[0]['payment_method']:"";
		$payment_types = isset($paypal_details[0]['payment_type'])?$paypal_details[0]['payment_type']:"";
		if($payment_types == 1) {
			$request  = 'METHOD=RefundTransaction';
			$request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
			$request .= '&USER=' . urlencode($payment_gateway_username);
			$request .= '&PWD=' . urlencode($payment_gateway_password);
			$request .= '&SIGNATURE=' . urlencode($payment_gateway_key);
			$request .= '&TRANSACTIONID=' . urlencode($preTransactId);
			$paypal_type=($payment_method =="L")?"live":"sandbox";
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
			
			$ack = isset($nvpArray["ACK"]) ? strtoupper($nvpArray["ACK"]):'';
			$code = 0;
			if($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {
				$update_card_passenger_array = array("void_status"=>1);
				$result = $commonmodel->update(PASSENGERS_CARD_DETAILS,$update_card_passenger_array,'passenger_cardid',$cardId);
				$code = 1;
			}
		} else {
			require_once(APPPATH.'vendor/braintree-payment/lib/Braintree.php');
			$pay_type=($payment_method =="L")?"live":"sandbox";
			try {
				if ($pay_type=="live") 
				{
					Braintree_Configuration::environment('production');
				} else {
					Braintree_Configuration::environment('sandbox');			
				}
				Braintree_Configuration::merchantId($payment_gateway_username);//your_merchant_id
				Braintree_Configuration::publicKey($payment_gateway_password);//your_public_key
				Braintree_Configuration::privateKey($payment_gateway_key);
				$result = Braintree_Transaction::void($preTransactId);
			} catch(Braintree_Exception $message) {
				return 0; 
			}
				
			$code=0;
			if($result->success)
			{
				$update_card_passenger_array = array("void_status"=>1);
				$code = 1;
				$result = $commonmodel->update(PASSENGERS_CARD_DETAILS,$update_card_passenger_array,'passenger_cardid',$cardId);
			}
		}
		return $code;
	}
	/** get common info from siteinfo table **/
	public function siteinfo_details()	
	{
		$sql = "SELECT admin_commission,referral_amount FROM ".SITEINFO;
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

	}
	/** Get driver job status **/
	public function get_request_status($passenger_log_id="")
	{
		//$sql = "SELECT driver_reply,time_to_reach_passen FROM ".PASSENGERS_LOG." WHERE `passengers_log_id` = '".$passenger_log_id."'";
		$sql = "SELECT available_drivers,rejected_timeout_drivers,total_drivers,selected_driver,status,trip_type FROM ".DRIVER_REQUEST_DETAILS." WHERE `trip_id` = '".$passenger_log_id."'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 			
	}
	/** Get Company id for the Driver **/
	public function get_company_id($driver_id)
	{
		$sql = "SELECT company_id FROM ".PEOPLE." WHERE `id` = '".$driver_id."'";
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
	}
	/** Get passenger's company id **/
	public function get_passenger_company_id($id) 
	{

		$query= "SELECT passenger_cid FROM ".PASSENGERS." WHERE id = '$id'";

		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return ($result[0]['passenger_cid'])?$result[0]['passenger_cid']:0;	
	}
	//** to check the passenger in trip or not **//
	public function check_passenger_in_trip($passengerId, $currentTime)
	{
		$getDate = explode(" ",$currentTime);
		$currentTime = $getDate[0]." 00:00:01";
		$sql = "SELECT count(passengers_log_id) as total FROM ".PASSENGERS_LOG." WHERE `pickup_time` >= '".$currentTime."' and `passengers_id` = '".$passengerId."' and `driver_reply` = 'A' and (`travel_status` = '9' or `travel_status` = '2' or `travel_status` = '3' or `travel_status` = '5')";
		//echo $sql;exit;
		return Db::query(Database::SELECT, $sql)->execute()->get('total'); 
	}
	 /********************** Check Promo Code ***************/
   public function checkPromocode($passenger_id,$promo_code,$current_time)
   {
	 	$promo_query = "SELECT promocode,promo_discount,promo_used,start_date,expire_date,promo_limit FROM  ".PASSENGER_PROMO." WHERE  promocode = '$promo_code' and  `passenger_id` ='$passenger_id'  "; 
		$promo_fetch = Db::query(Database::SELECT, $promo_query)->execute()->as_array();	
		 if(count($promo_fetch)>0)
		 {
			 $promocode = $promo_fetch[0]['promocode'];
			 $promo_discount = $promo_fetch[0]['promo_discount'];
			 $promo_used = $promo_fetch[0]['promo_used'];
			 
			 $promo_start = $promo_fetch[0]['start_date'];
			 $promo_expire = $promo_fetch[0]['expire_date'];
			 $promo_limit = $promo_fetch[0]['promo_limit'];
			 
			 if(strtotime($promo_start) > strtotime($current_time))
			 {
				 return 3;
			 }
			 else if(strtotime($promo_expire) < strtotime($current_time))
			 {
				 return 4;
			 }
			 else
			 {

				$promo_use_query = "SELECT COUNT(passengers_log_id) as promo_count  FROM  ".PASSENGERS_LOG." WHERE  promocode = '$promo_code' and  `passengers_id` ='$passenger_id' and  travel_status='1' and driver_reply='A'"; 
				$promo_user_count = Db::query(Database::SELECT, $promo_use_query)->execute()->as_array();					 
				 
				 if(count($promo_user_count)>0&&$promo_user_count[0]['promo_count']>=$promo_limit)
				 {
					return 2; 
				 }
				 else
				 {
					return 1;
				 }
			 }
		 }
		 else
		 {
			 return 0;
		 }		 		
   }
   
   public function check_travelstatus($log_id)
	{
		$sql = "SELECT travel_status,driver_reply FROM ".PASSENGERS_LOG." WHERE passengers_log_id = '$log_id' ";

		$result =  Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
		if(count($result) > 0)
		{	
			return $result[0]['travel_status'];
		}
		else
		{
			return -1;
		}

	}
	
	public function check_tranc($log_id,$flag)
	{
		$condition="";
		if($flag == 1)
		{
			$condition = "and pl.travel_status = '1'";
		}
		$sql = "SELECT pl.travel_status,pl.driver_id,t.id,pl.passengers_id FROM ".PASSENGERS_LOG." as pl join ".TRANS." as t on 
				t.passengers_log_id=pl.passengers_log_id  WHERE t.passengers_log_id = '$log_id' $condition limit 1";
		$result =  Db::query(Database::SELECT, $sql)->execute()->as_array(); 
		$responseRes = ($flag == 1) ? count($result) : $result;
		return $responseRes;
	}
	
	/*** Get Passenger Profile details using passenger log id  ***/
	public function get_passenger_log_detail($passengerlog_id="")
	{
				 $sql = "SELECT ".PEOPLE.".phone,".PASSENGERS_LOG.".promocode,".PASSENGERS_LOG.".is_split_trip,".PASSENGERS_LOG.".booking_key,".PASSENGERS_LOG.".passengers_id,".PASSENGERS_LOG.".driver_id,".PASSENGERS_LOG.".taxi_id,".PASSENGERS_LOG.".taxi_modelid, ".PASSENGERS_LOG.".company_id,".PASSENGERS_LOG.".current_location,".PASSENGERS_LOG.".pickup_latitude,".PASSENGERS_LOG.".pickup_longitude,
				IFNULL(".PASSENGERS_LOG.".drop_location,0) as drop_location,".PASSENGERS_LOG.".drop_latitude,".PASSENGERS_LOG.".drop_longitude,".PASSENGERS_LOG.".no_passengers, ".PASSENGERS_LOG.".approx_distance,".PASSENGERS_LOG.".approx_duration,".PASSENGERS_LOG.".approx_fare,".PASSENGERS_LOG.".fixedprice,".PASSENGERS_LOG.".time_to_reach_passen, ".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".actual_pickup_time,".PASSENGERS_LOG.".drop_time,".PASSENGERS_LOG.".account_id,".PASSENGERS_LOG.".accgroup_id,".PASSENGERS_LOG.".pickupdrop,
				".PASSENGERS_LOG.".rating,".PASSENGERS_LOG.".comments,".PASSENGERS_LOG.".travel_status,".PASSENGERS_LOG.".driver_reply, ".PASSENGERS_LOG.".msg_status,".PASSENGERS_LOG.".createdate,".PASSENGERS_LOG.".booking_from,".PASSENGERS_LOG.".search_city, ".PASSENGERS_LOG.".sub_logid,".PASSENGERS_LOG.".bookby,".PASSENGERS_LOG.".booking_from_cid,".PASSENGERS_LOG.".distance,".PASSENGERS_LOG.".notes_driver,".PASSENGERS_LOG.".used_wallet_amount, ".PEOPLE.".name AS driver_name,".PASSENGERS.".discount AS passenger_discount,".PEOPLE.".phone AS driver_phone,".PEOPLE.".profile_picture AS driver_photo,".PEOPLE.".device_id AS driver_device_id,".PEOPLE.".device_token AS driver_device_token,".PEOPLE.".device_type AS driver_device_type,".PASSENGERS.".discount AS passenger_discount,".PASSENGERS.".device_id AS passenger_device_id,".PASSENGERS.".device_token AS passenger_device_token,".PASSENGERS.".referred_by AS referred_by,".PASSENGERS.".referrer_earned AS referrer_earned,".PASSENGERS.".wallet_amount,".PASSENGERS.".device_type AS passenger_device_type, ".PASSENGERS.".salutation AS passenger_salutation,".PASSENGERS.".name AS passenger_name,".PASSENGERS.".lastname AS passenger_lastname,".PASSENGERS.".email AS passenger_email,".PASSENGERS.".phone AS passenger_phone,".PASSENGERS.".country_code AS passenger_country_code,".COMPANYINFO.".cancellation_fare as cancellation_nfree,".COMPANYINFO.".company_tax as company_tax,".COMPANYINFO.".company_brand_type as brand_type,IFNULL(".TRANS.".id,0) as transaction_id FROM  ".PASSENGERS_LOG." 
				JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
				LEFT JOIN  ".TRANS." ON (  ".PASSENGERS_LOG.".`passengers_log_id` =  ".TRANS.".`passengers_log_id` ) 
				LEFT JOIN  ".COMPANYINFO." ON (  ".PASSENGERS_LOG.".`company_id` =  ".COMPANYINFO.".`company_cid` ) 
				LEFT JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) 
				WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$passengerlog_id'";
				$result = Db::query(Database::SELECT, $sql)->as_object()->execute();											               
				return $result;		
	}
	
	public function update_driver_status($status,$driverid)
	{
		
		$update_array = array("status" => $status);
		return $result = DB::update(DRIVER)
					->set($update_array)
					->where('driver_id', '=', $driverid)
					->execute();						
	}
	
	public function get_passenger_phone_by_id($id) 
	{

		$query= "SELECT phone FROM ".PASSENGERS." WHERE id = '$id'";

		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return ($result[0]['phone'])?$result[0]['phone']:'';	
	}	
	
	public function cancel_triptransact_details($details,$cancellation_nfree,$payment_types,$driver_id = 0)
	{
		if($cancellation_nfree != 0 && !empty($driver_id))
		{
			$first_query = "select ".PACKAGE_REPORT.".check_package_type from " . PACKAGE_REPORT . " join ".PACKAGE." on ".PACKAGE.".package_id =".PACKAGE_REPORT.".upgrade_packageid  where ".PACKAGE_REPORT.".upgrade_companyid = ".$details['company_id']."  order by upgrade_id desc limit 0,1";
		$first_results = Db::query(Database::SELECT, $first_query)
				->execute()
				->as_array();
			if(count($first_results) > 0)
			{
				$check_package_type = $first_results[0]['check_package_type'];
			}
			else
			{
				$check_package_type = 'T';
			}

			if($check_package_type != 'N')
			{
				$admin_amt = ($details['total_fare'] * $details[0]['admin_commission'])/100; //payable to admin
				$admin_amt = round($admin_amt, 2);
				$total_balance = round($details['total_fare'],2);
		
				//Set Commission to Admin
				if(ADMIN_COMMISION_SETTING) {
					$updatequery = " UPDATE ". PEOPLE ." SET account_balance = account_balance+$admin_amt wHERE user_type = 'A'";	
					$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
				}
			}
			else
			{
				$admin_amt = 0;
			}			

			//$company_amt = $details['total_fare'] - $admin_amt; 	
			$company_amt = $details['total_fare'];
			$company_amt = round($company_amt, 2);	

			//Set Commission to Company
			if(COMPANY_COMMISION_SETTING) {
				$updatequery = " UPDATE ". PEOPLE ." SET account_balance = account_balance+$company_amt wHERE user_type = 'C' and company_id=".$details['company_id'];
				$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
			}
			
			//Set Commission to Driver
			if(DRIVER_COMMISION_SETTING && $driver_id > 0) {
				$driver_com_query = "select driver_commission from ".COMPANY." where cid =".$details['company_id'];
				$driver_com_result = Db::query(Database::SELECT, $driver_com_query)->execute()->as_array();
				$driver_commission = isset($driver_com_result[0]['driver_commission']) ? $driver_com_result[0]['driver_commission'] : 0;
				$driver_commission_amt = round(($company_amt*$driver_commission/100),2);
				$company_bal_amt = $company_amt-$driver_commission_amt;
				$updatequery = " UPDATE ". PEOPLE ." SET account_balance = account_balance+$driver_commission_amt WHERE user_type = 'D' and id = '$driver_id'";
				$updateresult = Db::query(Database::UPDATE, $updatequery)->execute();
			}

			if($details['travel_status'] == 4)
			{
			$updatequery = " UPDATE ". PASSENGERS_LOG ." SET comments='".$details['remarks']."', travel_status='".$details['travel_status']."' WHERE passengers_log_id=".$details['passenger_log_id'];	

			$updateresult = Db::query(Database::UPDATE, $updatequery)
			->execute();

			}
			$current_time = date('Y-m-d H:i:s');
			$details['CORRELATIONID']=isset($details['CORRELATIONID'])?$details['CORRELATIONID']:'';
			$details['ACK']=isset($details['ACK'])?$details['ACK']:'1';
			$details['CURRENCYCODE']=isset($details['CURRENCYCODE'])?$details['CURRENCYCODE']:'';
			
			$result = DB::insert(TRANS, array('passengers_log_id','fare','remarks','correlation_id','ack','transaction_id',
'payment_type','order_time','amt','currency_code','payment_status','captured','admin_amount','company_amount','trans_packtype','payment_gateway_id'))
			->values(array($details['passenger_log_id'],$details['total_fare'],urldecode($details['remarks']),$details['CORRELATIONID'],$details['ACK'],$details['TRANSACTIONID'],$details['pay_mod_id'],$current_time,$details['total_fare'],$details['CURRENCYCODE'],$details['ACK'],'1',$admin_amt,$company_amt,$check_package_type,$payment_types))
			->execute();


			return $result; 
		}
		else
		{
			$updatequery = " UPDATE ". PASSENGERS_LOG ." SET comments='".$details['remarks']."', travel_status='4' WHERE passengers_log_id=".$details['passenger_log_id'];	

			$updateresult = Db::query(Database::UPDATE, $updatequery)
			->execute();			
		}
	}
	/*** Get Passenger Profile details using passenger log id ***/
	public function get_passenger_cancel_faredetail($passengerlog_id="")
	{
		$model_base_query = "select search_city from ".PASSENGERS_LOG." where passengers_log_id=".$passengerlog_id;
		$model_fetch = Db::query(Database::SELECT, $model_base_query)->execute()->as_array();
		$model_fetch1 = array();
		if(count($model_fetch) > 0)
		{
			$city_id = $model_fetch[0]['search_city'];
			$model_base1_query = "select city_model_fare from ".CITY." where ".CITY.".city_id=".$city_id;
			$model_fetch1 = Db::query(Database::SELECT, $model_base1_query)->execute()->as_array();
		}
		if(count($model_fetch1) > 0)
		{
			$city_model_fare = $model_fetch1[0]['city_model_fare'];
		}
		else
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1";
			$model_fetch = Db::query(Database::SELECT, $model_base_query)->execute()->as_array();
			$city_model_fare = $model_fetch[0]['city_model_fare'];
		}
		$sql = "select (SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare FROM ".PASSENGERS_LOG." AS pg JOIN ".TAXI." as taxi ON pg.`taxi_id`=taxi.`taxi_id` JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model` where pg.passengers_log_id='$passengerlog_id'";
				$result = Db::query(Database::SELECT, $sql)					
				->execute()
				->get('cancellation_fare');												               
				return $result;		
	}
	//to check the passenger have referral amount to use
	public function check_passenger_referral_amount($passenger_id)
	{
		$sql = "SELECT referral_amount,referral_code FROM ".PASSENGER_REFERRAL." WHERE passenger_id='$passenger_id' and referral_amount_used='0'";
		$result=Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	public function get_creadit_card_details($passenger_id="",$card_type="",$default="")
	{
		$condition="";
		if(($card_type == "P") || ($card_type == "B"))
		{
			$condition = " and card_type = '$card_type'";
		}	
		if($default == 'yes')
		{
			$condition .= " and default_card = '1'";
		}	 
		 $sql = "select passenger_cardid,passenger_id,card_type,expdatemonth,default_card,expdateyear,creditcard_no,creditcard_cvv from ".PASSENGERS_CARD_DETAILS." where passenger_id='$passenger_id' $condition";
		 $result = Db::query(Database::SELECT, $sql)->execute()->as_array();					             
		 return $result;				
	}
	
	public function passengerlogid_details($log_id)	
	{
		$sql = "SELECT pg.passengers_id, pg.driver_id, pg.taxi_modelid, pg.company_id, pg.passengers_id, pg.pickup_time, pg.current_location as pickupLocation, pg.drop_location as dropLocation, pg.pickup_latitude, pg.pickup_longitude, pg.drop_latitude, pg.drop_longitude, pg.search_city, pg.taxi_id, pg.pre_transaction_id, pg.pre_transaction_amount, pg.used_wallet_amount,dloc.active_record,pe.name as driver_name,pe.email as driver_email,pe.phone as driver_phone,pe.device_token as driver_devicetoken,pg.search_city,pg.taxi_id,CONCAT(p.country_code,p.phone) as passenger_phone, p.name as passenger_name, p.lastname as passenger_lastname, p.email as passenger_email FROM ".PASSENGERS_LOG." as pg 
		left join ".DRIVER_LOCATION_HISTORY." as dloc on dloc.trip_id=pg.passengers_log_id  
		left join ".PEOPLE." as pe on pg.driver_id=pe.id left join ".PASSENGERS." as p on p.id=pg.passengers_id WHERE passengers_log_id = '$log_id'";

		return Db::query(Database::SELECT, $sql)->execute()->as_array(); 

	}
	
	/*** Get Taxi fare per KM & Waiting charge of the company based Company***/
	public function get_model_fare_details($company_id,$model_id="",$search_city="",$brand_type="")
	{

		if($search_city!='')
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".city_id ='".$search_city."'"; 
		}		
		else
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".default=1"; 
		}

		$model_fetch = Db::query(Database::SELECT, $model_base_query)->execute()->as_array();
		$city_model_fare = (count($model_fetch) > 0) ? $model_fetch[0]['city_model_fare'] : 0;

		if(FARE_SETTINGS == 2 && $brand_type == "M")
		{
			$sql = "SELECT (SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km,(SUM(company_model_fare.minutes_fare)*($city_model_fare)/100) + company_model_fare.minutes_fare as minutes_fare,company_model_fare.night_charge,company_model_fare.night_timing_from,company_model_fare.night_timing_to,(SUM(company_model_fare.night_fare)*($city_model_fare)/100) + company_model_fare.night_fare as night_fare,company_model_fare.evening_charge,company_model_fare.evening_timing_from,company_model_fare.evening_timing_to,(SUM(company_model_fare.evening_fare)*($city_model_fare)/100) + company_model_fare.evening_fare as evening_fare,
company_model_fare.waiting_time,company_model_fare.min_km,company_model_fare.below_above_km 
FROM  ".COMPANY_MODEL_FARE." as company_model_fare WHERE  ".COMPANY_MODEL_FARE.".`model_id` = '$model_id' and ".COMPANY_MODEL_FARE.".`company_cid`= '$company_id'";
		}
		else
		{
			$sql = "SELECT (SUM(model.base_fare)*($city_model_fare)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*($city_model_fare)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*($city_model_fare)/100) + model.below_km as below_km,(SUM(model.above_km)*($city_model_fare)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*($city_model_fare)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*($city_model_fare)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*($city_model_fare)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km 
			FROM  ".MOTORMODEL." as model WHERE  model.`model_id` = '$model_id'";			
		}
//echo $sql;
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();								               
		return $result;		
	}
}
