<?php defined('SYSPATH') OR die('No Direct Script Access');

/******************************************

* Contains Users Module details

* @Created on July, 2013

* @Updated on July, 2013

* @Package: taxi

* @Author: taxi Team

* @URL : taxiapp.com

********************************************/

Class Model_Manager extends Model
{
	public function __construct()
	{	
		$this->session = Session::instance();	
		//$this->username = $this->session->get("user_name");
		$this->currentdate=Commonfunction::getCurrentTimeStamp();

	}
	
	public function get_availabletaxi_list()
	{
	
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
		
		$cuurentdate = date('Y-m-d H:i:s');
		$enddate = date('Y-m-d').' 23:59:59';
			
		$query_where = " AND ( ( '$cuurentdate' between taximapping.mapping_startdate and  taximapping.mapping_enddate ) or ( '$enddate' between taximapping.mapping_startdate and  taximapping.mapping_enddate) )";	
						
				
		$sql ="SELECT *
			FROM ".TAXI." as taxi
			JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid
			JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid
			JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid
			JOIN ".DRIVER." as driver ON driver.driver_id = taximapping.mapping_driverid
			WHERE people.status = 'A' and driver.status='F' and company.cid='$company_id'
			AND taximapping.mapping_status = 'A' 
			AND taxi.taxi_country='$country_id' 
			AND taxi.taxi_state='$state_id' 
			AND taxi.taxi_city='$city_id' 
			AND people.login_country='$country_id' 
			AND people.login_state='$state_id' 
			AND people.login_city='$city_id'			
			AND taximapping.mapping_cityid='$city_id'  $query_where limit 0,10";
			
		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}

	public function free_driver_list()
	{
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
		$booked_driver = $this->free_availabletaxi_list();
	
		$driver_list = "";
		if(count($booked_driver) > 0)
		{
			foreach($booked_driver as $key => $value)
			{
				$driver_list .= "'".$value['id']."',";
			}
			$driver_list = rtrim($driver_list,',');
		}
		else
		{
			$driver_list = "''";
		}


		
		$sql = "select id,name,userid,company_name from ".PEOPLE." JOIN ".COMPANY." ON ".PEOPLE.".company_id = company.cid where ".PEOPLE.".user_type='D' and  ".PEOPLE.".company_id='$company_id' and ".PEOPLE.".login_country='$country_id' and ".PEOPLE.".login_state='$state_id' and ".PEOPLE.".login_city='$city_id' and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and ".PEOPLE.".id NOT IN ($driver_list) order by ".PEOPLE.".id asc";

		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return $result;
			

	}

	public function free_taxi_list()
	{
		
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
		
		$booked_driver = $this->free_availabletaxi_list();

		$taxi_list = "";
		
		if(count($booked_driver) > 0)
		{
			foreach($booked_driver as $key => $value)
			{
				$taxi_list .= "'".$value['taxi_id']."',";
			}
			$taxi_list = rtrim($taxi_list,',');
		}
		else
		{
			$taxi_list = "''";
		}

		$sql = "select taxi_id,taxi_no,userid,company_name from ".TAXI." JOIN ".COMPANY." ON ".TAXI.".taxi_company = company.cid where ".TAXI.".taxi_status='A' and  ".TAXI.".taxi_availability='A' and ".TAXI.".taxi_company='$company_id' and ".TAXI.".taxi_country='$country_id' and ".TAXI.".taxi_state='$state_id' and ".TAXI.".taxi_city='$city_id' and ".TAXI.".taxi_id NOT IN ($taxi_list) order by ".TAXI.".taxi_id asc";

		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return $result;
		
	}
	
	public function free_availabletaxi_list()
	{
	
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
		$cuurentdate = date('Y-m-d H:i:s');
		$enddate = date('Y-m-d').' 23:59:59';
			
		$query_where = " AND ( ( '$cuurentdate' between taximapping.mapping_startdate and  taximapping.mapping_enddate ) or ( '$enddate' between taximapping.mapping_startdate and  taximapping.mapping_enddate) )";	
						
				
		$sql ="SELECT people.id,taxi.taxi_id  
			FROM ".TAXI." as taxi
			JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid
			JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid
			JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid
			WHERE people.status = 'A' and company.cid='$company_id'
			AND taximapping.mapping_status = 'A' 
			AND taxi.taxi_country='$country_id' 
			AND taxi.taxi_state='$state_id' 
			AND taxi.taxi_city='$city_id' 
			AND people.login_country='$country_id' 
			AND people.login_state='$state_id' 
			AND people.login_city='$city_id'			
			AND taximapping.mapping_cityid='$city_id'  $query_where limit 0,10";
//JOIN ".DRIVER." as driver ON driver.driver_id = taximapping.mapping_driverid and driver.status='F'
		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}
	
	public function getUserbyCompany()
	{
		$query = "SELECT count(`id`) as co_nt,c.company_name FROM `people` as p Join `company` as c ON p.company_id=c.cid WHERE p.user_type='D' group by p.company_id";
		$queryval = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		$result = "";
		foreach($queryval as $res)
		{
			$result .= "['".$res["company_name"]."', ".$res["co_nt"].""."],";
		}
		$result = rtrim($result,",");

		return $result;
	}
	
	public function gettransaction($id)
	{
		$query = "SELECT count(`passengers_log_id`) as co_nt,p.name as driver_name FROM ".PEOPLE." as p Join ".PASSENGERS_LOG." as pl ON p.id=pl.driver_id WHERE pl.driver_id IN(".$id.") and travel_status='1' group by pl.driver_id";

		$queryval = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		
		$result = $queryval;

		return $result;
	}
	
	public function changegettransaction($id = '',$startdate = '',$enddate = '')
	{
		$date_where = "  and (pl.createdate between '$startdate' and '$enddate') ";	
		$query = "SELECT count(`passengers_log_id`) as co_nt,p.name as driver_name FROM ".PEOPLE." as p Join ".PASSENGERS_LOG." as pl ON p.id=pl.driver_id WHERE pl.driver_id IN(".$id.") $date_where and travel_status='1' group by pl.driver_id";
		//echo $query;
		$queryval = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		
		$result = $queryval;

		return $result;
	}
	public function get_admin_dashboard_data($company_id)
	{	
	
		//$result_query = "SELECT * FROM ".PASSENGERS." where 1=1 and (user_status = 'A' OR user_status = 'D' )";
		$result_query = "SELECT count(id) as count FROM ".PASSENGERS." where user_status = 'A' and passenger_cid = $company_id ";
		$results = Db::query(Database::SELECT, $result_query)->execute()->as_array();
		$result["general_users"] = $results[0]["count"];

		//$result_query = "SELECT * FROM ".PEOPLE." where user_type='D' and (status = 'A' OR status = 'D' ) and company_id=$company_id ";
		$result_query = "SELECT count(id) as count FROM ".PEOPLE." where user_type='D' and status = 'A'  and company_id=$company_id ";
		$results = Db::query(Database::SELECT, $result_query)->execute()->as_array();
		$result["driver"] = $results[0]["count"];
		
		/*$result_query = "SELECT * FROM  ".COMPANY." LEFT JOIN  ".PEOPLE." ON ".PEOPLE.".id = ".COMPANY.".userid WHERE user_type ='C' and (status = 'A' OR  status = 'D' ) ";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["company"]=count($results);*/
		
		/*$result_query = "SELECT * FROM ".PEOPLE." where user_type='M' and (status = 'A' OR status = 'D' )";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["manager"]=count($results);*/
		
		//$result_query = "SELECT * FROM ".COUNTRY." where country_status = 'A' OR country_status = 'D' ";
		$result_query = "SELECT count(country_id) as count FROM ".COUNTRY." where country_status = 'A' ";
		$results = Db::query(Database::SELECT, $result_query)->execute()->as_array();
		$result["country"] = $results[0]["count"];
		
		//$result_query = "SELECT * FROM ".STATE." where state_status = 'A' OR state_status = 'D'";
		$result_query = "SELECT count(state_id) as count FROM ".STATE." where state_status = 'A'";
		$results = Db::query(Database::SELECT, $result_query)->execute()->as_array();
		$result["state"] = $results[0]["count"];
		
		//$result_query = "SELECT * FROM ".CITY." where city_status = 'A' OR city_status = 'D'";
		$result_query = "SELECT count(city_id) as count FROM ".CITY." where city_status = 'A' ";
		$results = Db::query(Database::SELECT, $result_query)->execute()->as_array();
		$result["city"] = $results[0]["count"];

		//$result_query = "SELECT * FROM ".TAXI." where taxi_status = 'A' OR taxi_status = 'D' AND taxi_createdby =$company_id";
		$result_query = "SELECT count(taxi_id) as count FROM ".TAXI." where taxi_company =".$company_id." AND taxi_status = 'A'";
		$results = Db::query(Database::SELECT, $result_query)->execute()->as_array();
		$result["taxi"] = $results[0]["count"];
		return $result;
	}
	
	public function get_activeusers_list($company_id)
	{
		$query = "SELECT count(id) as count FROM ".PASSENGERS." where login_status = 'A' and passenger_cid = '" . $company_id . "' limit 0,10";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
	 	return isset($result[0]['count']) ? $result[0]['count'] : 0;
	}
		//dashboard active users count
	public function get_activeusers_list_count($company_id)
	{
		$query = "SELECT count(id) as count FROM ".PASSENGERS." where login_status = 'A' and passenger_cid = '" . $company_id . "'";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
	 	return isset($result[0]['count']) ? $result[0]['count'] : 0;
	}

	/***********Dashboard Trip details chart************/
	public function get_company_trip_count($month, $year)
	{
		$user_type=$_SESSION['user_type'];
		$dispatcher_id=$_SESSION['userid'];
		$company_id=$_SESSION['company_id'];
		if($user_type == 'M'){
			$query = "SELECT count(c.cid) as count
				FROM ".COMPANY." as c
				Join ".PASSENGERS_LOG." as p ON p.company_id=c.cid
				Join ".TRANS." as T on T.passengers_log_id=p.passengers_log_id
				Join ".PEOPLE." as PP on PP.user_createdby=c.userid
				where month(T.current_date) ='" . $month . "'
				and year(T.current_date)='" . $year . "'
				and p.travel_status = 1
				and p.driver_reply = 'A'
				and c.cid=".$company_id."
				and PP.id=".$dispatcher_id."
				group by month(T.current_date)";
			$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();	

			if ($result)
			{
				return $result[0]['count'];
			}
		}
		return '0';
	}

	public function get_company_trip_revenues($month, $year)
	{
		$user_type=$_SESSION['user_type'];
		$dispatcher_id=$_SESSION['userid'];
		$company_id=$_SESSION['company_id'];
		if($user_type == 'M'){
			$query = "SELECT sum(fare) as revenues
				FROM ".COMPANY." as c
				Join ".PASSENGERS_LOG." as p ON p.company_id=c.cid
				Join ".TRANS." as T on T.passengers_log_id=p.passengers_log_id
				Join ".PEOPLE." as PP on PP.user_createdby=c.userid
				where month(T.current_date) ='" . $month . "'
				and year(T.current_date)='" . $year . "'
				and p.travel_status = 1
				and c.cid=".$company_id."
				and PP.id=".$dispatcher_id."
				group by month(T.current_date)";
				
			$result = Db::query(Database::SELECT, $query)
					->execute()
					->as_array();	

			if (count($result)>0)
			{
				return $result[0]['revenues'];
			}
		}
		
		return '0';
	}

	public function total_trip_details($start='',$end='')
	{
		$user_type=$_SESSION['user_type'];
		$dispatcher_id=$_SESSION['userid'];
		$company_id=$_SESSION['company_id'];
		if($start!=''&& $end!=''){
			$date_where="AND p.`pickup_time` >= '".$start." 00:00:01' AND  p.`pickup_time` <= '".$end." 23:59:59'";
		}
		if($user_type == 'M'){
			$query = "SELECT round(sum(T.`fare`)) as fare,count(fare) as trips,DATE_FORMAT(p.`pickup_time`,'%d') as date,DATE_FORMAT(p.`pickup_time`,'%M') as month
					FROM ".COMPANY." as c
					Join ".PASSENGERS_LOG." as p ON p.company_id=c.cid
					Join ".TRANS." as T on T.passengers_log_id=p.passengers_log_id
					Join ".PEOPLE." as PP on PP.user_createdby=c.userid
					WHERE p.`travel_status` = 1
					$date_where
					and p.company_id=".$company_id."
					and PP.id=".$dispatcher_id."
					group by DATE(p.`pickup_time`)";
			 
			//echo $query;exit;
			$result = Db::query(Database::SELECT, $query)
				 ->execute()
				 ->as_array();		

			return $result;
		}
		return '0';
	}
	/***********Dashboard Trip details chart************/
	
	public function get_company_timezone($company_id)
	{
		$result = DB::select('user_time_zone')->from(COMPANY)->execute()->as_array();
		(isset($result[0]['user_time_zone']) && $result[0]['user_time_zone'] != '') ? $this->session->set("timezone",$result[0]['user_time_zone']) : "";
	}
}
