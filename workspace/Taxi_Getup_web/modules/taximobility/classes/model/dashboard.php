<?php defined('SYSPATH') or die('No direct script access.');

/************************************************

* Contains Dashboard(Site Statistics - Count) details


* @Package: Taximobility

* @Author: Taximobility Team

* @URL : http://ndot.in/

************************************************/

class Model_Dashboard extends Model
{

	/**
	 * ****__construct()****
	 *
	 * setting up session variables
	 */
	public function __construct()
	{	
		$this->session = Session::instance();	
		$this->username = $this->session->get("username");
		$this->admin_session_id = $this->session->get("id");
	}



	public function getUsers($month, $year)
	{
		$query = "select count(*) as count from " . PEOPLE . " where status='" . ACTIVE . "' and user_type ='M' and month(created_date) ='" . $month . "' and year(created_date)='" . $year . "' group by month(created_date)";

		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();	

		if ($result)
		{
			return $result[0]['count'];
		}
		return '0';
	}

	public function getUsersbydate($startdate, $enddate)
	{
			$date_where = "  (created_date between '$startdate' and '$enddate') ";
			
		$query = "select count(*) as count from " . PEOPLE . " where status='" . ACTIVE . "' and user_type ='M' and 	$date_where group by month(created_date)";

		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();	

		if ($result)
		{
			return $result[0]['count'];
		}
		return '0';
	}

	public function get_company_trip_count($month, $year)
	{
		$query = "SELECT count(c.cid) as count
				FROM ".COMPANY." as c
				Join ".PASSENGERS_LOG." as p ON p.company_id=c.cid
				Join ".TRANS." as T on T.passengers_log_id=p.passengers_log_id
				where month(T.current_date) ='" . $month . "'
				and year(T.current_date)='" . $year . "'
				and p.travel_status = 1
				group by month(T.current_date)";
				
		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();	

		if ($result)
		{
			return $result[0]['count'];
		}
		return '0';
	}
	/** to get the trip counts in both mobile app and webapp, total trip counts and total trip revenues **/
	public function appwise_trips($month, $year)
	{
		$query = "SELECT count(c.cid) as total_trips,sum(fare) as revenues,sum(T.admin_amount) as admincommission, sum(case when p.bookby = '2' then 1 else 0 end) as webtrips, sum(case when p.bookby = '1' then 1 else 0 end) as mobiletrips FROM ".COMPANY." as c Join ".PASSENGERS_LOG." as p ON p.company_id=c.cid Join ".TRANS." as T on T.passengers_log_id=p.passengers_log_id where month(T.current_date) ='" .$month. "' and year(T.current_date)='" . $year . "' and p.travel_status = 1 group by month(T.current_date)";
		
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}

	public function get_company_trip_count_date($month, $year,$startdate, $enddate)
	{
		$date_where="";
		if($startdate !="" && $enddate!="")
		{
			//$date_where = "and (T.current_date between '$startdate' and '$enddate') ";
			$date_where = " and T.current_date >=  '$startdate' and T.current_date <=  '$enddate' ";
		}

		$query = "SELECT count(c.cid) as count
				FROM ".COMPANY." as c
				Join ".PASSENGERS_LOG." as p ON p.company_id=c.cid
				Join ".TRANS." as T on T.passengers_log_id=p.passengers_log_id
				where month(T.current_date) ='" . $month . "'
				and year(T.current_date)='" . $year . "'
				and p.travel_status = 1
				$date_where
				group by month(T.current_date)";

		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();	

		if ($result){
			return $result[0]['count'];
		}
		return '0';
	}

	public function get_company_trip_revenues($month, $year)
	{
		/*
		$query = " SELECT * ,pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl
		join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id
		Join `".COMPANY."` as c ON pl.company_id=c.cid
		Join `".PEOPLE."` as pe ON pe.id=pl.driver_id
		Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id
		$condition $trans_condition
		order by pl.passengers_log_id desc
		limit $val offset $offset";
		
		$query = " SELECT sum(fare) as revenues FROM `".PASSENGERS_LOG."` as pl
		join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id
		Join `".COMPANY."` as c ON pl.company_id=c.cid
		Join `".PEOPLE."` as pe ON pe.id=pl.driver_id
		Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id
		where month(current_date) ='" . $month . "'
		and year(current_date)='" . $year . "'
		group by month(current_date)";
		*/
		$query = "SELECT sum(fare) as revenues
				FROM ".COMPANY." as c
				Join ".PASSENGERS_LOG." as p ON p.company_id=c.cid
				Join ".TRANS." as T on T.passengers_log_id=p.passengers_log_id
				where month(T.current_date) ='" . $month . "'
				and year(T.current_date)='" . $year . "'
				and p.travel_status = 1
				group by month(T.current_date)";
				
		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();	

		if (count($result)>0)
		{
			return $result[0]['revenues'];
		}
		return '0';
	}

	/********* Total Trip and Revenue details *********************/
	public function total_trip_details($company_id,$start='',$end='')
	{
		$company_where="";
		if($company_id !="")
		{
			$company_where="AND log.`company_id` = ".$company_id."";
		}
		
		 $query = "SELECT round(sum(t.`fare`)) as fare,count(fare) as trips,DATE_FORMAT(log.`pickup_time`,'%d') as date,DATE_FORMAT(log.`pickup_time`,'%M') as month, c.company_name as company,round(sum(t.`admin_amount`)) as admincommission, sum(case when log.bookby = '2' then 1 else 0 end) as webtrips, sum(case when log.bookby = '1' then 1 else 0 end) as mobiletrips
		 FROM ".PASSENGERS_LOG." as log
		 LEFT JOIN ".TRANS." as t on log.`passengers_log_id`=t.`passengers_log_id` LEFT JOIN ".COMPANY." as c on c.cid=log.company_id
		 WHERE log.`travel_status` = 1
		 AND t.`current_date` BETWEEN '".$start." 00:00:01' AND '".$end." 23:59:59'
		 $company_where group by DATE(t.`current_date`)";//
		 
		//echo $query;exit;
		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();		

		return $result;
	}
	
	/*************************Dashboard Driver status ***********************************/
	public function driver_status_details($driver_status)
	{
		$where_cond="";
		if($driver_status=='A' || $driver_status=='F'){
			$where_cond.="AND D.status='$driver_status' AND D.shift_status='IN'";
		}elseif($driver_status=='OUT'){
			$where_cond.="AND D.status='F' AND D.shift_status='$driver_status'";
		} 
		
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		$company_id = $_SESSION['company_id'];	
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
		if($usertype  == 'C')
		{
			$query = "SELECT PP.name,D.latitude,D.longitude,D.shift_status,D.status AS driver_status FROM ".PEOPLE." AS PP
					 JOIN ".DRIVER." AS D ON PP.`id` = D.`driver_id` 
					 WHERE PP.user_type =  'D'
					 AND PP.company_id =  $company_id
					 $where_cond";

			//echo $query;//exit;
			$result = Db::query(Database::SELECT, $query)
				 ->execute()
				 ->as_array();
			return $result;
		}
		else if($usertype  == 'M')
		{
			/*$result = DB::select("*",array(DRIVER.'.status','driver_status'))->from(PEOPLE)
					->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
					->where('user_type','=','D')
					->where(PEOPLE.'.status','=','A')
					//->where(PEOPLE.'.login_status','=','S')
					->where('company_id','=',$company_id)
					//->where(PEOPLE.'.user_createdby','=',$user_createdby)
					//->order_by('created_date','desc')->limit($val)->offset($offset)
					->execute()
					->as_array();
			return $result;*/
			$query = "SELECT PP.name,D.latitude,D.longitude,D.shift_status,D.status AS driver_status FROM ".PEOPLE." AS PP
					 JOIN ".DRIVER." AS D ON PP.`id` = D.`driver_id` 
					 WHERE PP.user_type =  'D'
					 AND PP.company_id =  $company_id
					 $where_cond";

			//echo $query;exit;
			$result = Db::query(Database::SELECT, $query)
				 ->execute()
				 ->as_array();
			return $result;
		}
		else 
		{
			$query = "SELECT PP.name,D.latitude,D.longitude,D.shift_status,D.status AS driver_status FROM ".PEOPLE." AS PP
					 JOIN ".DRIVER." AS D ON PP.`id` = D.`driver_id` 
					 WHERE PP.user_type =  'D'
					 $where_cond";

			//echo $query;exit;
			$result = Db::query(Database::SELECT, $query)
				 ->execute()
				 ->as_array();
			return $result;
		}
	}

	public function driver_status_details_company($driver_status,$company)
	{
		$where_cond="";
		if($driver_status=='A' || $driver_status=='F'){
			$where_cond.="AND D.status='$driver_status' AND D.shift_status='IN'";
		}elseif($driver_status=='OUT'){
			$where_cond.="AND D.status='F' AND D.shift_status='$driver_status'";
		}

		$company_cond="";
		if($company!=""){
			$company_cond.=" AND PP.company_id =  $company";
		}
		
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		$company_id = $_SESSION['company_id'];	
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
		if($usertype  == 'C')
		{
			$query = "SELECT PP.name,D.latitude,D.longitude,D.shift_status,D.status AS driver_status FROM ".PEOPLE." AS PP
					 JOIN ".DRIVER." AS D ON PP.`id` = D.`driver_id` 
					 WHERE PP.user_type =  'D'
					 AND PP.company_id =  $company_id
					 $where_cond";

			//echo $query;exit;
			$result = Db::query(Database::SELECT, $query)
				 ->execute()
				 ->as_array();
			return $result;
		}
		else if($usertype  == 'M')
		{
			/*$result = DB::select("*",array(DRIVER.'.status','driver_status'))->from(PEOPLE)
					->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
					->where('user_type','=','D')
					->where(PEOPLE.'.status','=','A')
					//->where(PEOPLE.'.login_status','=','S')
					->where('company_id','=',$company_id)
					//->where(PEOPLE.'.user_createdby','=',$user_createdby)
					//->order_by('created_date','desc')->limit($val)->offset($offset)
					->execute()
					->as_array();
			return $result;*/
			$query = "SELECT PP.name,D.latitude,D.longitude,D.shift_status,D.status AS driver_status FROM ".PEOPLE." AS PP
					 JOIN ".DRIVER." AS D ON PP.`id` = D.`driver_id` 
					 WHERE PP.user_type =  'D'
					 AND PP.company_id =  $company_id
					 $where_cond";

			//echo $query;exit;
			$result = Db::query(Database::SELECT, $query)
				 ->execute()
				 ->as_array();
			return $result;
		}
		else 
		{
			$query = "SELECT PP.name,D.latitude,D.longitude,D.shift_status,D.status AS driver_status FROM ".PEOPLE." AS PP
					 JOIN ".DRIVER." AS D ON PP.`id` = D.`driver_id` 
					 WHERE PP.user_type =  'D'
					 $company_cond
					 $where_cond";

			//echo $query;exit;
			$result = Db::query(Database::SELECT, $query)
				 ->execute()
				 ->as_array();
			return $result;
		}
	}
	
	/** to get company details and Driver, Taxi and passengers details for that particular company **/
	public function getCompanyUsersTaxi($company_id)
	{
		$query = "SELECT count(p.id) as totaldrivers, (select count(t.taxi_id) from ".TAXI." as t where t.taxi_company = '$company_id') as totaltaxis, (select count(pass.id) from ".PASSENGERS." as pass where pass.passenger_cid = '$company_id') as totalpassengers, c.company_name FROM ".PEOPLE." as p LEFT JOIN ".COMPANY." as c ON c.cid = p.company_id where p.user_type = 'D' and p.status = 'A' and p.company_id = '$company_id'";
		
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	
	/**
	 * Get Dashboard Data's for version 5.1
	 **/
	public function getDashboardData($company_id = 0,$start_date,$end_date)
	{
		$condition = "";
		if($company_id > 0) {
			$condition .= "and company_id = '$company_id'";
		}

		/** Total Amount **/
		$total_amount_sql = "SELECT sum(fare) + sum(used_wallet_amount) as total_amount FROM ".TRANS." left join ".PASSENGERS_LOG." on ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id where ".TRANS.".current_date BETWEEN '".$start_date."' and '".$end_date."' $condition";
		$total_amount_result = Db::query(Database::SELECT, $total_amount_sql)->execute()->as_array();
		$result["total_amount"] = round($total_amount_result[0]["total_amount"],2);
		
		/** Commision Amount **/
		if($company_id > 0) {
			$commision_sql = "SELECT sum(company_amount) as commision_amount,sum(driver_amount) as driver_amount FROM ".TRANS." left join ".PASSENGERS_LOG." on passengers_log.passengers_log_id = transacation.passengers_log_id where ".TRANS.".current_date BETWEEN '".$start_date."' and '".$end_date."' and passengers_log.company_id = ".$company_id."";
		} else {
			$commision_sql = "SELECT sum(admin_amount) as commision_amount FROM ".TRANS." where ".TRANS.".current_date BETWEEN '".$start_date."' and '".$end_date."'";
			$driver_commission = 0;
		}
		
		$commision_result = Db::query(Database::SELECT, $commision_sql)->execute()->as_array();
		if($company_id > 0) {
			$driver_commission = $commision_result[0]["driver_amount"];
		}
		$result["commision_amount"] = round($commision_result[0]["commision_amount"],2);
		$result["driver_commision"] = round($driver_commission,2);

		/** Company Amount **/
		$result["company_amount"] = $result["total_amount"]-$result["commision_amount"];

		/** Cash Payment **/
		$cash_payment_sql = "SELECT sum(fare) + sum(used_wallet_amount) as cash_payment, round(sum(company_amount),2) as company_cash_amt FROM ".TRANS." left join ".PASSENGERS_LOG." on ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id where ".TRANS.".current_date BETWEEN '".$start_date."' and '".$end_date."' and payment_type = '1' $condition";
		$cash_payment_result = Db::query(Database::SELECT, $cash_payment_sql)->execute()->as_array();
		$result["cash_payment"] = round($cash_payment_result[0]["cash_payment"],2);
		$result["company_cash_payment"] = round($cash_payment_result[0]["company_cash_amt"],2);
		

		/** Card Payment **/
		$card_payment_sql = "SELECT sum(fare) + sum(used_wallet_amount) as card_payment, round(sum(company_amount),2) as company_card_amt FROM ".TRANS." left join ".PASSENGERS_LOG." on ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id where ".TRANS.".current_date BETWEEN '".$start_date."' and '".$end_date."' and (payment_type = '2' or payment_type = '3') $condition";
		$card_payment_result = Db::query(Database::SELECT, $card_payment_sql)->execute()->as_array();
		$result["card_payment"] = round($card_payment_result[0]["card_payment"],2);
		$result["company_card_payment"] = round($card_payment_result[0]["company_card_amt"],2);

		/** New Users Count **/
		if($company_id > 0) {
			$passenger_sql = "SELECT count(id) as passenger_count FROM ".PASSENGERS." where ".PASSENGERS.".created_date BETWEEN '".$start_date."' and '".$end_date."' and passenger_cid = '$company_id'";
		} else {
			$passenger_sql = "SELECT count(id) as passenger_count FROM ".PASSENGERS." where ".PASSENGERS.".created_date BETWEEN '".$start_date."' and '".$end_date."'";
		}
		$passenger_result = Db::query(Database::SELECT, $passenger_sql)->execute()->get('passenger_count');
		$result["passenger_count"] = $passenger_result;

		/** Total Trips Count **/
		$trips_sql = "SELECT count(passengers_log_id) as trips_count FROM ".PASSENGERS_LOG." where ".PASSENGERS_LOG.".actual_pickup_time BETWEEN '".$start_date."' and '".$end_date."' and travel_status = '1' and driver_reply = 'A' $condition";
		$trips_result = Db::query(Database::SELECT, $trips_sql)->execute()->get('trips_count');
		$result["trips_count"] = $trips_result;

		/**
		 *Edited By Logeswaran 02-12-2016
		 *Ride Later Passenger count also added here
		 *Removed Group By Passengers _id
		 *Active Passenger Count
		 **/
		//$active_passenger_sql = "SELECT count(passengers_log_id) as active_passenger_count FROM ".PASSENGERS_LOG." left join ".PASSENGERS." on ".PASSENGERS.".id = ".PASSENGERS_LOG.".passengers_id where ".PASSENGERS_LOG.".actual_pickup_time BETWEEN '".$start_date."' and '".$end_date."' and ((travel_status = '1' and driver_reply = 'A') or (travel_status = '9' and driver_reply = 'C' or travel_status = '4' or travel_status = '8')) and ".PASSENGERS.".user_status = 'A' $condition group by passengers_id";
		$active_passenger_sql = "SELECT count(distinct(passengers_id)) as active_passenger_count FROM ".PASSENGERS_LOG." left join ".PASSENGERS." on ".PASSENGERS.".id = ".PASSENGERS_LOG.".passengers_id where ".PASSENGERS_LOG.".actual_pickup_time BETWEEN '".$start_date."' and '".$end_date."' and ((travel_status= '0' AND now_after='1' AND ".PASSENGERS_LOG.".pickup_time BETWEEN '".$start_date."' and '".$end_date."') OR (travel_status = '1' and driver_reply = 'A') or (travel_status = '9' and driver_reply = 'C' or travel_status = '4' or travel_status = '8')) and ".PASSENGERS.".user_status = 'A' $condition";	
		$active_passenger_result = Db::query(Database::SELECT, $active_passenger_sql)->execute()->get('active_passenger_count');
		$result["active_passenger_count"] = !empty($active_passenger_result) ? $active_passenger_result : 0;

		/** Cancel Trips Count **/
		$cancel_trips_sql = "SELECT count(passengers_log_id) as cancel_trips_count FROM ".PASSENGERS_LOG." where 
		".PASSENGERS_LOG.".actual_pickup_time BETWEEN '".$start_date."' and '".$end_date."' and (travel_status = '9' and driver_reply = 'C' or travel_status = '4' or travel_status = '8') $condition";
		$cancel_trips_result = Db::query(Database::SELECT, $cancel_trips_sql)->execute()->get('cancel_trips_count');
		$result["cancel_trips_count"] = $cancel_trips_result;

		/** Mobile App Trips **/
		$cancel_trips_sql = "SELECT count(passengers_log_id) as app_trips_count FROM ".PASSENGERS_LOG." where 
		".PASSENGERS_LOG.".createdate BETWEEN '".$start_date."' and '".$end_date."' and ( booking_from != 0 ) $condition";
		$cancel_trips_result = Db::query(Database::SELECT, $cancel_trips_sql)->execute()->get('app_trips_count');
		$result["mobile_app_trips"] = $cancel_trips_result;


		/** Web App Trips **/
		$cancel_trips_sql = "SELECT count(passengers_log_id) as web_trips_count FROM ".PASSENGERS_LOG." where 
		".PASSENGERS_LOG.".createdate BETWEEN '".$start_date."' and '".$end_date."' and (booking_from = 0 ) $condition";
		$cancel_trips_result = Db::query(Database::SELECT, $cancel_trips_sql)->execute()->get('web_trips_count');
		$result["web_app_trips"] = $cancel_trips_result;


		return $result;
	}

	/**
	 * Get Dashboard Assigned vs Unassigned data's for version 5.1
	 * return array
	 **/

	public function getDashboardassignUnassignData($company_id = 0,$start_date,$end_date)
	{
		$condition = $mapping_condition = $people_condition = "";
		if($company_id > 0) {
			$condition .= "and taxi_company = '$company_id'";
			$mapping_condition .= "and mapping_companyid = '$company_id'";
			$people_condition .= "and company_id = '$company_id'";
			/** Assigned driver count **/
			/*$driver_sql = "select mapping_companyid as company_id,count(mapping_driverid) as assigned_driver_count from ".TAXIMAPPING." where (('$start_date' between mapping_startdate and  mapping_enddate) or ('$end_date' between mapping_startdate and mapping_enddate)) $mapping_condition group by mapping_companyid";
			$driver_result = Db::query(Database::SELECT, $driver_sql)->execute()->as_array();*/
			
			/** Assigned taxi count **/
			$taxi_sql = "select mapping_companyid as company_id,count(mapping_taxiid) as assigned_taxi_count,count(mapping_driverid) as assigned_driver_count from ".TAXIMAPPING." where (('$start_date' between mapping_startdate and  mapping_enddate) or ('$end_date' between mapping_startdate and mapping_enddate)) $mapping_condition group by mapping_companyid";
			$taxi_result = Db::query(Database::SELECT, $taxi_sql)->execute()->as_array();
			
			/** Total driver count **/
			$total_driver_sql = "SELECT company_id,count(id) as total_driver_count from ".PEOPLE." left join ".COMPANY." on ".COMPANY.".cid = ".PEOPLE.".company_id where ".PEOPLE.".user_type='D' $people_condition group by ".PEOPLE.".company_id";
			$total_driver_result = Db::query(Database::SELECT, $total_driver_sql)->execute()->as_array();

			
			$sql = "SELECT cid, company_name, count(".TAXI.".taxi_id) as taxi_count FROM ".COMPANY." LEFT JOIN ".TAXI." ON ".TAXI.".taxi_company = ".COMPANY.".cid where 1=1 $condition group by ".COMPANY.".cid";
			$result = Db::query(Database::SELECT, $sql)->execute()->as_array(); 

			if(count($result) > 0) {
				foreach($result as $k => $v) {
					$result[$k]["assigned_driver_count"] = 0;
					$result[$k]["assigned_taxi_count"] = 0;
					$result[$k]["total_driver_count"] = 0;
					if(count($taxi_result) > 0) {
						foreach($taxi_result as $t) {
							if($v["cid"] == $t["company_id"]) {
								$result[$k]["assigned_taxi_count"] = $t["assigned_taxi_count"];
								$result[$k]["assigned_driver_count"] = $t["assigned_driver_count"];
							}
						}
					}
					/*if(count($driver_result) > 0) {
						foreach($driver_result as $d) {
							if($v["cid"] == $d["company_id"]) {
								$result[$k]["assigned_driver_count"] = $d["assigned_driver_count"];
							}
						}
					}
					if(count($taxi_result) > 0) {
						foreach($taxi_result as $t) {
							if($v["cid"] == $t["company_id"]) {
								$result[$k]["assigned_taxi_count"] = $t["assigned_taxi_count"];
							}
						}
					}*/
					if(count($total_driver_result) > 0) {
						foreach($total_driver_result as $d) {
							if($v["cid"] == $d["company_id"]) {
								$result[$k]["total_driver_count"] = $d["total_driver_count"];
							}
						}
					}
				}
			}
		} else {
			$result = array();
		}
		return $result;
	}
	
	/**
	 * Get Dashboard Comapny Wise Trip data's for version 5.1
	 * return array
	 **/

	public function getCompanyWiseTrip($company_id = 0,$start_date,$end_date)
	{
		$condition = "";
		if($company_id > 0) {
			$condition .= "and company_id = '$company_id'";
		}
		$sql = "select company_name,count(if(travel_status = '1' and driver_reply = 'A',passengers_log_id,NULL)) as trip_completed,count(if(travel_status = '2' and driver_reply = 'A',passengers_log_id,NULL)) as trip_inprogress,count(if((travel_status = '4' and driver_reply = 'A') or (travel_status = '8') or (travel_status = 9 and driver_reply = 'C'),passengers_log_id,NULL)) as trip_cancelled  from ".COMPANY." left join ".PASSENGERS_LOG." on ".PASSENGERS_LOG.".company_id = ".COMPANY.".cid where 1=1 and pickup_time between '$start_date' and '$end_date' $condition group by company_id";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}

	/**
	 * Get Dashboard Payment By Comapny data's for version 5.1
	 * return array
	 **/

	public function getPaymentByCompany($company_id = 0,$start_date,$end_date)
	{
		$condition = ""; $promotional_amount = $promotional_amount_count = 0;
		if($company_id > 0) {
			$condition .= "and company_id = '$company_id'";
		}
		$sql = "SELECT sum(company_amount) as commision_amount,sum(driver_amount) as driver_commision,sum(fare+used_wallet_amount) as total_amount, count(id) as total_amount_count,sum(case when (payment_type = '1' and promocode = '') then fare+used_wallet_amount else 0 end) as cash_payment,sum(case when (payment_type = '1') then company_amount else 0 end) as company_cash_amt,sum(case when (payment_type = '1') then 1 else 0 end) as cash_payment_count, sum(case when (promocode = '' and (payment_type = '2' or payment_type = '3')) then fare+used_wallet_amount else 0 end) as card_payment, sum(case when (promocode = '' and (payment_type = '2' or payment_type = '3')) then company_amount else 0 end) as company_card_payment,sum(case when (promocode = '' and (payment_type = '2' or payment_type = '3')) then 1 else 0 end) as card_payment_count, sum(case when (payment_type = '5' and promocode = '') then used_wallet_amount else 0 end) as referral_payment, sum(case when (payment_type = '5' and promocode = '') then 1 else 0 end) as referral_payment_count FROM ".TRANS." left join ".PASSENGERS_LOG." on ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id where ".TRANS.".current_date BETWEEN '".$start_date."' and '".$end_date."' $condition";
		$all_result = Db::query(Database::SELECT, $sql)->execute()->as_array();

		$query = "SELECT promocode,fare FROM ".PASSENGERS_LOG." left join ".TRANS." on ".TRANS.".passengers_log_id = ".PASSENGERS_LOG.".passengers_log_id where promocode != '' and ".TRANS.".current_date BETWEEN '".$start_date."' and '".$end_date."' $condition";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		
		if(count($result) > 0) {
			foreach($result as $p) {
				if($p["promocode"] != "") {
					$promo_code = $p["promocode"];
					$fare = $p["fare"];
					$promo_query = "SELECT promo_discount FROM ".PASSENGER_PROMO." where promocode = '$promo_code'";
					$promo_result = Db::query(Database::SELECT, $promo_query)->execute()->as_array();
					if(count($promo_result) > 0) {
						$promotional_amount += ($promo_result[0]["promo_discount"]/100)*$fare;
						$promotional_amount_count = $promotional_amount_count+1;
					}
				}
			}
		}
		$all_result[0]["promotional_amount"] = $promotional_amount;
		$all_result[0]["company_cash_amt"] = $all_result[0]["company_cash_amt"]-$promotional_amount;
		$all_result[0]["promotional_amount_count"] = $promotional_amount_count;
		//echo '<pre>';print_r($all_result);exit;
		return $all_result;
	}

	/**
	 * Get Dashboard Comapny Count data's for version 5.1
	 * return array
	 **/

	public function getCompanyCountChart($company_id = 0,$start_date,$end_date)
	{
		$condition = "";
		if($company_id > 0) {
			$condition .= "and ".PASSENGERS_LOG.".company_id = '$company_id'";
		}
		$total_trip_sql = "SELECT count(passengers_log_id) as total_trip FROM ".PASSENGERS_LOG." where pickup_time BETWEEN '".$start_date."' and '".$end_date."' $condition";
		$total_trip_result = Db::query(Database::SELECT, $total_trip_sql)->execute()->as_array();

		$sql = "SELECT count(case when (payment_type = '1' and promocode = '') then id else NULL end) as cash_payment_count, count(case when (promocode = '' and (payment_type = '2' or payment_type = '3')) then id else NULL end) as card_payment_count, count(case when (payment_type = '5' and promocode = '') then id else NULL end) as referral_payment_count, count(case when (payment_type != '') then ".PASSENGERS_LOG.".passengers_log_id else NULL end) as promotional_used_count FROM ".TRANS." left join ".PASSENGERS_LOG." on ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id where ".TRANS.".current_date BETWEEN '".$start_date."' and '".$end_date."' $condition";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		$result[0]["total_trip_count"] = $total_trip_result[0]["total_trip"];
		return $result;
	}
	
	/**
	 * Get Dashboard Trip Request Top Cities data's for version 5.1
	 * return array
	 **/

	public function getTripReqTopCitiesChart($company_id = 0,$start_date,$end_date)
	{
		$condition = "";
		if($company_id > 0) {
			$condition .= "and ".PASSENGERS_LOG.".company_id = '$company_id'";
		}
		$sql = "SELECT city_name,count(passengers_log_id) as trip_top_cities_count FROM ".PASSENGERS_LOG." where city_name != '' and ".PASSENGERS_LOG.".pickup_time BETWEEN '".$start_date."' and '".$end_date."' $condition group by city_name";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	/**
	 * Get Dashboard City By Count data's for version 5.1
	 * return array
	 **/

	public function getCityByCountChart($company_id = 0,$start_date,$end_date)
	{
		$condition = "";
		if($company_id > 0) {
			$condition .= "and ".PASSENGERS_LOG.".company_id = '$company_id'";
		}
		$sql = "SELECT city_name,count(passengers_log_id) as trip_top_cities_count FROM ".PASSENGERS_LOG." where city_name != '' and ".PASSENGERS_LOG.".pickup_time BETWEEN '".$start_date."' and '".$end_date."' $condition group by city_name";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		//echo '<pre>';print_r($result);exit;
		return $result;
	}
	
	/**
	 * Get Dashboard Company Revenue Graph data's for version 5.1
	 * return array
	 **/

	public function getCompanyRevenueChart($company_id = 0,$start_date,$end_date)
	{
		$condition = ""; $new_array = array();
		if($company_id > 0) {
			$condition .= " and pl.company_id =  '$company_id'";
		}

		$query = "SELECT round(sum(t.company_amount),2) as amount,pl.createdate,count(t.fare) as trips FROM ".PASSENGERS_LOG." as pl join ".TRANS." as t ON pl.passengers_log_id = t.passengers_log_id Join ".COMPANY." as c ON pl.company_id = c.cid Join ".PEOPLE." as pe ON pe.id = pl.driver_id Join ".PASSENGERS." as pa ON pl.passengers_id = pa.id where pl.createdate >= '$start_date' and pl.createdate <= '$end_date' $condition group by DATE(pl.createdate)";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		
		$user_query = "SELECT created_date,count(id) as user_count FROM ".PASSENGERS." left join ".PASSENGERS_LOG." as pl on pl.passengers_id = ".PASSENGERS.".id where created_date >= '$start_date' and created_date <= '$end_date' $condition group by DATE(created_date)";
		$user_result = Db::query(Database::SELECT, $user_query)->execute()->as_array();

		$user_date_array = $trans_array = $final_array = $stored_arr = $data1 = $data2 = $full_arr = array();
		if(count($result) > 0) {
			foreach($result as $res) {
				$full_arr[] = $date = date("Y-m-d",strtotime($res["createdate"]));
				$trans_array[$date] = $res;
			}
		}
		
		if(count($user_result) > 0) {
			foreach($user_result as $d) {
				$full_arr[] = $date = date("Y-m-d",strtotime($d["created_date"]));
				$user_date_array[$date] = $d;
			}
		}
		function cmp($a, $b)
		{
			if ($a == $b) {
				return 0;
			}
			return ($a < $b) ? -1 : 1;
		}
		$full_arr = array_values(array_unique($full_arr));
		usort($full_arr, "cmp");
		foreach($full_arr as $val){
			$data1['createdate'] = $val;
			$data1['amount'] = 0;
			$data1['trips'] = 0;
			$data2['user_count'] = 0;
			$data2['created_date'] = "";
			if(isset($trans_array[$val])) { $data1 = $trans_array[$val]; }
			if(isset($user_date_array[$val])) { $data2 = $user_date_array[$val]; }
			$final_array[] = array_merge($data1,$data2);
		}
		return $final_array;
	}
	
	/**
	 * Get Dashboard Driver Revenue Graph data's for version 5.1
	 * return array
	 **/

	public function getDriverRevenueChart($driver_id = 0,$start_date,$end_date)
	{
		$condition = "";
		if($driver_id > 0) {
			$condition .= "and pl.driver_id = '$driver_id'";
		}
		$sql = "SELECT round(sum(t.driver_amount),2) as amount,t.current_date,count(pl.passengers_log_id) as trip_count FROM ".PASSENGERS_LOG." as pl join ".TRANS." as t ON pl.passengers_log_id = t.passengers_log_id where pl.travel_status = '1' and pl.driver_reply = 'A' and t.current_date >= '$start_date' and t.current_date <= '$end_date' $condition group by DATE(t.current_date)";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		//echo '<pre>';print_r($result);exit;
		return $result;
	}
	
	/**
	 * Live Dispatch
	 **/

	public function get_driver_list($array = array())
	{
		$company_id = 0;
		$commonmodel = Model::factory('commonmodel');
		$company_current_time = $commonmodel->getcompany_all_currenttimestamp($company_id);
		$query  = "SELECT people.id as driver_id,people.name, list.status AS driver_status, list.latitude, list.longitude,list.shift_status as shift_status, list.updatetime_difference AS updatetime_difference FROM (SELECT * , (TIME_TO_SEC( TIMEDIFF(  '" . $company_current_time . "', driver.update_date ) )) AS updatetime_difference FROM driver) AS list JOIN people ON people.id = list.driver_id WHERE people.user_type = 'D' AND people.status = 'A'";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}

	/*************************Dashboard Driver status ***********************************/
	
	
	// driver dashboard class --- stats
	
	/**
	 * Get Dashboard Data's for version 5.1
	 **/
	public function driver_getDashboardData($driver_id = 0,$start_date,$end_date)
	{
		$condition = "";
		if($driver_id > 0) {
			$condition .= "and driver_id = '$driver_id'";
		}
		/** Total Driver Amount **/
		$total_amount_sql = "SELECT sum(account_balance) as total_amount FROM ".PEOPLE." where user_type='D' $condition";
		$total_amount_result = Db::query(Database::SELECT, $total_amount_sql)->execute()->as_array();
		$result["total_amount"] = round($total_amount_result[0]["total_amount"],2);
		
		/** Total Driver Count **/
		$total_amount_sql = "SELECT id FROM ".PEOPLE." where user_type='D' $condition";
		$total_amount_result = Db::query(Database::SELECT, $total_amount_sql)->execute()->as_array();
		$result["total_driver_count"] = count($total_amount_result);
		
		/** Total Trips Count **/
		$trips_sql = "SELECT count(passengers_log_id) as trips_count FROM ".PASSENGERS_LOG." where ".PASSENGERS_LOG.".actual_pickup_time BETWEEN '".$start_date."' and '".$end_date."' and travel_status = '1' and driver_reply = 'A' $condition";
		$trips_result = Db::query(Database::SELECT, $trips_sql)->execute()->get('trips_count');
		$result["trips_count"] = $trips_result;
		
		/** Cancel Trips Count **/
		$cancel_trips_sql = "SELECT count(passengers_log_id) as cancel_trips_count FROM ".PASSENGERS_LOG." where 
		".PASSENGERS_LOG.".actual_pickup_time BETWEEN '".$start_date."' and '".$end_date."' and (travel_status = '9' and driver_reply = 'C' or travel_status = '4' or travel_status = '8') $condition";
		$cancel_trips_result = Db::query(Database::SELECT, $cancel_trips_sql)->execute()->get('cancel_trips_count');
		$result["cancel_trips_count"] = $cancel_trips_result;
		
		
		$coupon_recharge_amount_sql = "SELECT sum(amount) AS amount FROM ".DRIVERS_COUPON." where added_by=0 and coupon_used=1 and ".DRIVERS_COUPON.".used_date BETWEEN '".$start_date."' and '".$end_date."' $condition";
		$coupon_recharge_amount = Db::query(Database::SELECT, $coupon_recharge_amount_sql)->execute()->get('amount');
		$result["coupon_recharge_amount"] = $coupon_recharge_amount;
		
		$admin_recharge_amount_sql = "SELECT sum(amount) AS amount FROM ".DRIVERS_COUPON." where added_by=1 and coupon_used=1 and ".DRIVERS_COUPON.".used_date BETWEEN '".$start_date."' and '".$end_date."' $condition";
		
		$admin_recharge_amount = Db::query(Database::SELECT, $admin_recharge_amount_sql)->execute()->get('amount');
		$result["admin_recharge_amount"] = $admin_recharge_amount;
		
		
		/** Total Active Driver **/
		$total_active_driver_sql = "SELECT id FROM ".PEOPLE." where user_type='D' AND status='A' $condition";
		$total_active_driver = Db::query(Database::SELECT, $total_active_driver_sql)->execute()->as_array();
		$result["total_active_driver"] = count($total_active_driver);
		
		/** Total InActive Driver **/
		$total_inactive_driver_sql = "SELECT id FROM ".PEOPLE." where user_type='D' AND status='D' $condition";
		$total_inactive_driver = Db::query(Database::SELECT, $total_inactive_driver_sql)->execute()->as_array();
		$result["total_inactive_driver"] = count($total_inactive_driver);
		
		/** Total online Driver **/
		$total_online_driver_sql = "SELECT id FROM ".PEOPLE." where user_type='D' AND login_status='S' $condition";
		$total_online_driver = Db::query(Database::SELECT, $total_online_driver_sql)->execute()->as_array();
		$result["total_online_driver"] = count($total_online_driver);
		
		/** Total offline Driver **/
		$total_offline_driver_sql = "SELECT id FROM ".PEOPLE." where user_type='D' AND login_status='N' $condition";
		$total_offline_driver = Db::query(Database::SELECT, $total_offline_driver_sql)->execute()->as_array();
		$result["total_offline_driver"] = count($total_offline_driver);
		
		
		
		//print_r($result); exit;

		return $result;
	}
	
	public function driver_getDriverBalance($driver_id = 0,$start_date,$end_date)
	{
		$condition = "";
		if($driver_id > 0) {
			$condition .= "and id = '$driver_id'";
		}
		$res = "SELECT count(if(account_balance > '0',account_balance,NULL)) as balance_driver, count(if(account_balance <= '0',account_balance,NULL)) as no_balance_driver FROM ".PEOPLE." where user_type='D' $condition";
		
		$result = Db::query(Database::SELECT, $res)->execute()->current();
		
		return $result;
		
	//count(if(travel_status = '1' and driver_reply = 'A',passengers_log_id,NULL)) as trip_completed,count(if(travel_status = '2' and driver_reply = 'A',passengers_log_id,NULL)) as trip_inprogress	
	}
	
	public function getDriverTrip($driver_id = 0,$start_date,$end_date)
	{
		$condition = "";
		if($driver_id > 0) {
			$condition .= "and id = '$driver_id'";
		}
		$sql = "select name,count(if(travel_status = '1' and driver_reply = 'A',passengers_log_id,NULL)) as trip_completed,count(if(travel_status = '2' and driver_reply = 'A',passengers_log_id,NULL)) as trip_inprogress,count(if((travel_status = '4' and driver_reply = 'A') or (travel_status = '8') or (travel_status = 9 and driver_reply = 'C'),passengers_log_id,NULL)) as trip_cancelled  from ".PEOPLE." left join ".PASSENGERS_LOG." on ".PASSENGERS_LOG.".driver_id = ".PEOPLE.".id where 1=1 and pickup_time between '$start_date' and '$end_date' $condition group by driver_id";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();

		return $result;
	}

	public function drivertopup($driver_id = 0,$start_date,$end_date)
	{
		$condition = "";
		if($driver_id > 0) {
			$condition .= "and driver_id = '$driver_id'";
		}

		$coupon_recharge_amount_sql = "SELECT if(added_by = '0' ,sum(amount),0) as coupon_amount,if(added_by = '1' ,sum(amount),NULL) as admin_amount,count(if(added_by = '0' ,added_by,NULL)) as in_coupon, count(if(added_by = '1' ,added_by,NULL)) as in_admin FROM ".DRIVERS_COUPON." where coupon_used=1 and pay_type!=2 and ".DRIVERS_COUPON.".used_date BETWEEN '".$start_date."' and '".$end_date."' $condition";
		return Db::query(Database::SELECT, $coupon_recharge_amount_sql)->execute()->as_array();

	}
	
	public function getOnlineOfflineData($driver_id = 0,$start_date,$end_date)
	{
		$condition = "";
		if($driver_id > 0) {
			$condition .= "and id = '$driver_id'";
		}
		$res = "SELECT count(if(login_status ='S',login_status,NULL)) as online_driver, count(if(login_status ='N',login_status,NULL)) as offline_driver FROM ".PEOPLE." where user_type='D' $condition";
		
		$result = Db::query(Database::SELECT, $res)->execute()->current();
		
		return $result;
	}
	
	
	// Driver dashboard class --- Ends
	
}
?>
