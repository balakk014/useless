<?php defined('SYSPATH') OR die('No Direct Script Access');

/******************************************

* Contains Transaction model details

* @Package: ConnectTaxi

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************/

Class Model_Transaction extends Model
{
	/**
	****__construct()**
	*** Common Function in this model
	*/
	public function __construct()
	{	
		//** Session variables initialization goes here **//
		$this->session = Session::instance();
		$this->username = $this->session->get("username");
		$this->admin_username = $this->session->get("username");
		$this->admin_userid = $this->session->get("id");
		$this->admin_email = $this->session->get("email");
		$this->user_admin_type = $this->session->get("user_type");
		$this->currentdate=Commonfunction::getCurrentTimeStamp();
	}
	/**
	****Company list function**
	*** Returns total list of the company
	*/
	public function company_list()
	{
	
		$result = DB::select()->from(COMPANY)
			->join(PEOPLE, 'LEFT')->on(PEOPLE.'.id', '=', COMPANY.'.userid')
			->where(PEOPLE.'.user_type', '=', 'C')
			->order_by('created_date','desc')
			->execute()
			->as_array();
	 	return $result;
	}
	/**
	****Company count function**
	*** Returns total count of the company
	*/
	public function count_company_list()
	{
		$result = DB::select()->from(COMPANY)
			->join(PEOPLE, 'LEFT')->on(PEOPLE.'.id', '=', COMPANY.'.userid')
			->where(PEOPLE.'.user_type', '=', 'C')
			->order_by('created_date','desc')
			->execute()
			->as_array();
		return count($result);
	}
	/**
	****Company list function**
	*** Returns overall list of the company with limit, offset for pagination
	*/
	public function all_company_list($offset, $val)
	{
		
		$result = DB::select()->from(COMPANY)->join(PEOPLE, 'LEFT')->on(PEOPLE.'.id', '=', COMPANY.'.userid')->where(PEOPLE.'.user_type', '=', 'C')
		->order_by('created_date','desc')->limit($val)->offset($offset)
			->execute()
			->as_array();
		
		$details = array();
		foreach($result as $key => $res)		
		{
			$details[$key]['no_of_taxi'] = $this->taxicount($res['cid']);
			$details[$key]['no_of_driver'] = $this->drivercount($res['cid']);
			$details[$key]['no_of_manager'] = $this->managercount($res['cid']);
			$details[$key]['name'] = $res['name'];
			$details[$key]['username'] = $res['username'];
			$details[$key]['email'] = $res['email'];
			$details[$key]['cid'] = $res['cid'];
			$details[$key]['company_name'] = $res['company_name'];
			$details[$key]['company_address'] = $res['company_address'];
			$details[$key]['company_status'] = $res['company_status'];
			$details[$key]['userid'] = $res['userid'];	
			$details[$key]['id'] = $res['id'];	
				
		}
			
		  return $details;
	}
	/**
	****Company list function**
	*** Returns overall list of the company
	*/
	public static function get_allcompany()
	{
		
		$result = DB::select()->from(COMPANY)->order_by('company_name','asc')
			->execute()
			->as_array();

		  return $result;
	}
	/**
	****Company list function**
	*** Returns overall list of the company based on status
	*/
	public static function get_allcompany_tranaction($status = "")
	{
		$result = DB::select(COMPANY.'.cid',COMPANY.'.company_name',COMPANYINFO.'.company_brand_type')->from(COMPANY)->join(COMPANYINFO, 'LEFT')->on(COMPANYINFO.'.company_cid', '=', COMPANY.'.cid');
		if($status != "") {
			$result->where(COMPANY.'.company_status','=',$status);
		}
		return $result->order_by(COMPANY.'.company_name','asc')->execute()->as_array();
	}
	/**
	****Transaction count function**
	*** Returns overall count of transaction list
	*/
	public function count_admintransaction_list($list,$company,$manager_id,$taxiid,$driverid,$passengerid,$startdate,$enddate,$transaction_id,$payment_type, $payment_mode='', $passenger_ids='', $taxi_ids = "",$driver_ids = "",$trip_id = "")
	{
		$left_join = "";
		$usertype = $_SESSION['user_type'];
		//** Condition to search based on taxi and driver for user type "Managers" **//
		if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
		{
			//** Function to get taxi details **//
			/* $taxilist = $this->gettaxidetails($company,$manager_id,$manager_details_array);
			$taxi_id ="";
			if(count($taxilist)>0)
			{
				foreach($taxilist as $taxis)
				{
					$taxi_id .= $taxis["taxi_id"].',';
				}
				$taxi_ids = substr($taxi_id,0,strlen($taxi_id)-1);
			}
			else
			{
				$taxi_ids = "";
			} */
			//** Function to get driver details **//
			/* $driverlist = $this->getdriverdetails($company,$manager_id,$manager_details_array);

			$driver_id ="";
			if(count($driverlist)>0)
			{
				foreach($driverlist as $drivers)
				{
					$driver_id .= $drivers["id"].',';
				}
				$driver_ids = substr($driver_id,0,strlen($driver_id)-1);
			}
			else
			{
				$driver_ids = "";
			} */
		}
		//** Function to get passenger details **//
		/*$passengerlist = $this->getpassengerdetails($company,$manager_id);
		$cpassenger_id ="";		
		if(count($passengerlist)>0)
		{
			foreach($passengerlist as $passengers)
			{
				$cpassenger_id .= $passengers["id"].',';
			}
			$passenger_ids = substr($cpassenger_id,0,strlen($cpassenger_id)-1);
		}
		else
		{
			$passenger_ids = "";
		}*/
		//** Condition to search based on transaction id **//
		if($trip_id !='')
		{
			$trip_condition = " and t.passengers_log_id like '%".$trip_id."%' ";
		}
		else
		{
			$trip_condition ='';
		}
		
		if($transaction_id !='')
		{
			$trans_condition = " and t.transaction_id like '%".$transaction_id."%' ";
		}
		else
		{
			$trans_condition ='';
		}	
		//** Condition to search based on transaction status **//
		if($list =='all')
		{
			$left_join = "left";
			//$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";//pl.driver_reply = 'A' ";
			$condition = "WHERE 1=1";
		}
		else if($list =='success')
		{
			$left_join = "left";
			$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";
		}
		else if($list =='cancelled')
		{
			$condition = "WHERE ((pl.travel_status = '4' and pl.driver_reply = 'A') or (pl.travel_status = '8') or (pl.travel_status = 9 and pl.driver_reply = 'C'))";
		}
		else if($list =='rejected')
		{
			$condition = "WHERE pl.travel_status = '0'";
		}
		else if($list =='pendingpayment')
		{
			$condition = "WHERE pl.travel_status = '5' and pl.driver_reply = 'A' ";
		}
		//** Condition to search based on payment type **//
		if($payment_type !='All' && $payment_type !='')
		{
			if($list != 'rejected')
			{
				$condition .= " and payment_type = '$payment_type' ";
			}
		}
		//** filter based payment mode ( Ex: Live or Test )
		if($payment_mode !='All' && $payment_mode !='')
		{
			$condition .= " and payment_method = '$payment_mode' and (t.payment_type = 2 or t.payment_type = 3) ";
		}
		//** Condition to search based on company **//
		if(($company !="") && ($company !="All")) { 
			/* $condition .= " and pl.company_id =  '$company'"; before sureshkumar.m modified code in 6.0 pack */ 
			//$condition .= " and pl.company_id =  '$company' and pa.passenger_cid = '$company' "; 
			$condition .= " and pl.company_id =  '$company'";
			/* sureshkumar.m modified code for 6.0 pack here i have checked condition for passenger company id */  
		}
		//** Condition to search based on taxi id **//
		if(($taxiid !="All")) 
		{ 
			$condition .= " and pl.taxi_id =  '$taxiid'"; 
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{
				//if(count($taxilist)>0)
				if($taxi_ids != "")
				{
					$condition .= "AND pl.taxi_id IN ( $taxi_ids )";
				}
			}
			else
			{
				$condition .= "";
			}
		}
		//** Condition to search based on driver id **//
		if(($driverid !="All")) 
		{ 			
			if($list !='rejected') {
				$condition .= " and pl.driver_id  =  '$driverid'"; 
			} else {
				$condition .= " and dr.driver_id  =  '$driverid'";
			}
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{	
				//if(count($driverlist)>0)
				if($driver_ids != "")
				{
					$condition .= "AND pl.driver_id IN ( $driver_ids )";
				}
			}
			else
			{
				$condition .= "";
			}
		}
		//** Condition to search based on passenger id **//
		if(($passengerid !="") && ($passengerid !="All")) 
		{ 
			$condition .= " and pl.passengers_id =  '$passengerid'"; 
		}
		if($startdate !="") { $condition .= " and pl.pickup_time >=  '$startdate' and pl.pickup_time <=  '$enddate' "; }
		if($list =='rejected')
		{
			//** Condition to search based on date **//
			$query = " SELECT count(*) as count FROM `".PASSENGERS_LOG."` as pl left join `".COMPANY."` as c ON pl.company_id=c.cid left join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id left join ".DRIVER_REJECTION." as dr ON dr.passengers_log_id = pl.passengers_log_id left join `".PEOPLE."` as pe ON pe.id=dr.driver_id $condition and dr.rejection_type = '1' group by dr.passengers_log_id order by pl.passengers_log_id desc";
			//echo $query;exit;
			$exeQuery = Db::query(Database::SELECT, $query)->execute()->as_array();
			$results = count($exeQuery);
		}
		else
		{
			//echo $condition; exit;
			//** Condition to search based on date **//
			$query = " SELECT count(*) as count FROM `".PASSENGERS_LOG."` as pl left join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id $left_join Join `".COMPANY."` as c ON pl.company_id=c.cid $left_join Join `".PEOPLE."` as pe ON pe.id=pl.driver_id $left_join Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id left Join ".PAYMENT_GATEWAYS_TYPES." as pg ON t.payment_gateway_id = pg.payment_gateway_id $condition $trans_condition $trip_condition order by pl.passengers_log_id desc";
			$results = Db::query(Database::SELECT, $query)->execute()->get('count');
		}		
		return $results;
	   
	}
   	/**
	****Transaction list function**
	*** Returns overall transaction list
	*/
   	public function transaction_details($list,$company,$manager_id,$taxiid,$driverid,$passengerid,$startdate,$enddate,$offset='',$val='',$transaction_id,$payment_type, $payment_mode="", $passenger_ids='',$taxi_ids = "",$driver_ids = "",$trip_id="")
   	{
		$left_join = "";
		$usertype = $_SESSION['user_type'];
		//** Condition to search based on transaction id **//
		if($trip_id !='')
		{
			$trip_condition = " and t.passengers_log_id like '%".$trip_id."%' ";
		}
		else
		{
			$trip_condition ='';
		}
		if($transaction_id !='')
		{
			$trans_condition = " and t.transaction_id like '%".$transaction_id."%'";
		}
		else
		{
			$trans_condition ='';
		}
		//** Condition to search based on status **//
		if($list =='all')
		{
			$left_join = "left";
			$condition = "WHERE 1 = 1 ";//pl.driver_reply = 'A' ";
			//$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";
		}
		else if($list =='success')
		{
			$left_join = "left";
			$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";
		}
		else if($list =='cancelled')
		{
			$condition = "WHERE ((pl.travel_status = '4') or (pl.travel_status = '8') or (pl.travel_status = 9 and pl.driver_reply = 'C'))";
		}
		else if($list =='rejected')
		{
			$condition = "WHERE pl.travel_status = '0'";
		}
		else if($list =='pendingpayment')
		{
			$condition = "WHERE pl.travel_status = '5' and pl.driver_reply = 'A' ";
		}
		//** Condition to search based on payment type **//
		if($payment_type !='All' && $payment_type !='' )
		{
			if($list !='rejected')
			{
				$condition .= " and t.payment_type = '$payment_type'";
			}
		}
		//** filter based payment mode ( Ex: Live or Test )
		if($payment_mode !='All' && $payment_mode !='' && $list !='rejected')
		{
			$condition .= " and t.payment_method = '$payment_mode' and (t.payment_type = 2 or t.payment_type = 3) ";
		}
		//** Condition to search based on company **//
		if(($company !="") && ($company !="All")) { 
			/* $condition .= " and pl.company_id =  '$company'"; before sureshkumar.m modified code in 6.0 pack */ 
			//$condition .= " and pl.company_id =  '$company' and pa.passenger_cid = '$company' "; 
			$condition .= " and pl.company_id =  '$company'";
			/* sureshkumar.m modified code for 6.0 pack here i have checked condition for passenger company id */  
		}
		//** Condition to search based on taxi id **//
	    if(($taxiid !="All")) 
	    { 
			$condition .= " and pl.taxi_id =  '$taxiid'"; 
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{
				//if(count($taxilist)>0)
				if($taxi_ids != "")
				{
					$condition .= " AND pl.taxi_id IN ( $taxi_ids )";
				}
			}
			else
			{
				$condition .= "";
			}
		}
		//** Condition to search based on driver id **//
	    if(($driverid !="All")) 
	    { 
			if($list !='rejected') {
				$condition .= " and pl.driver_id  =  '$driverid'"; 
			} else {
				$condition .= " and dr.driver_id  =  '$driverid'";
			}
		}
		else
		{ 
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{
				//if(count($driverlist)>0)
				if($driver_ids != "")
				{
					$condition .= " AND pl.driver_id IN ( $driver_ids )";
				}
			}
			else
			{
				$condition .= "";
			}
		}
		//** Condition to search based on passenger id **//
		if(($passengerid !="") && ($passengerid !="All")) 
		{ 
			$condition .= " and pl.passengers_id =  '$passengerid'"; 
		}
		//** Condition to search based on date **//
		if($startdate !="") { $condition .= " and pl.pickup_time >=  '$startdate' and pl.pickup_time <=  '$enddate' "; }
		if($list =='rejected')
		{
			$query = " SELECT pl.no_passengers,pl.driver_reply,pl.passengers_log_id,pl.actual_pickup_time,pl.pickup_time,pl.current_location,pl.drop_location,pl.driver_reply,pl.driver_id,c.userid,c.company_name,group_concat(pe.name) AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone,pl.createdate as journey_date FROM `".PASSENGERS_LOG."` as pl left join `".COMPANY."` as c ON pl.company_id=c.cid left join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id left join ".DRIVER_REJECTION." as dr ON dr.passengers_log_id = pl.passengers_log_id left join `".PEOPLE."` as pe ON pe.id=dr.driver_id $condition and dr.rejection_type = '1' group by dr.passengers_log_id order by pl.passengers_log_id desc  limit $val offset $offset";
			//echo $query;exit;
		}
		else
		{
			$query = " SELECT pl.no_passengers,pl.promocode,t.passenger_discount,pl.driver_reply,pg.payment_gateway_name,c.userid,t.payment_method,t.transaction_id,t.payment_type,c.company_name,pl.driver_id,pl.passengers_log_id as passengers_log_id,pe.name AS driver_name,pe.phone AS driver_phone, pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone,pl.actual_pickup_time,pl.pickup_time,pl.current_location,pl.drop_location,t.admin_amount,t.company_amount,t.distance,t.nightfare,t.eveningfare,pl.used_wallet_amount,t.fare,pl.travel_status,pl.rating,pl.comments,t.distance_unit,pl.createdate as journey_date,t.waiting_time,t.distance FROM `".PASSENGERS_LOG."` as pl left join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id $left_join Join `".COMPANY."` as c ON pl.company_id=c.cid $left_join Join `".PEOPLE."` as pe ON pe.id=pl.driver_id $left_join Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id left Join ".PAYMENT_GATEWAYS_TYPES." as pg ON t.payment_gateway_id = pg.payment_gateway_id $condition $trans_condition $trip_condition order by pl.passengers_log_id desc limit $val offset $offset";
		}
		//echo $query; exit;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		//echo "<pre>"; print_r($results); exit;
		return $results;   	     
   	}
   	public function export_transaction_details($list,$company,$manager_id,$taxiid,$driverid,$passengerid,$startdate,$enddate,$offset='',$val='',$transaction_id,$payment_type, $payment_mode="", $passenger_ids='',$taxi_ids = "",$driver_ids = "",$trip_id="")
   	{
		$left_join = "";
		$usertype = $_SESSION['user_type'];
		//** Condition to search based on transaction id **//
		if($trip_id !='')
		{
			$trip_condition = " and t.passengers_log_id like '%".$trip_id."%' ";
		}
		else
		{
			$trip_condition ='';
		}
		if($transaction_id !='')
		{
			$trans_condition = " and t.transaction_id like '%".$transaction_id."%'";
		}
		else
		{
			$trans_condition ='';
		}
		//** Condition to search based on status **//
		if($list =='all')
		{
			$left_join = "left";
			//$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";//pl.driver_reply = 'A' ";
			$condition = "WHERE 1 = 1";
		}
		else if($list =='success')
		{
			$left_join = "left";
			$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";
		}
		else if($list =='cancelled')
		{
			$condition = "WHERE ((pl.travel_status = '4' and pl.driver_reply = 'A') or (pl.travel_status = '8') or (pl.travel_status = 9 and pl.driver_reply = 'C'))";
		}
		else if($list =='rejected')
		{
			$condition = "WHERE pl.travel_status = '0'";
		}
		else if($list =='pendingpayment')
		{
			$condition = "WHERE pl.travel_status = '5' and pl.driver_reply = 'A' ";
		}
		//** Condition to search based on payment type **//
		if($payment_type !='All' && $payment_type !='' )
		{
			if($list !='rejected')
			{
				$condition .= " and t.payment_type = '$payment_type'";
			}
		}
		//** filter based payment mode ( Ex: Live or Test )
		if($payment_mode !='All' && $payment_mode !='' && $list !='rejected')
		{
			$condition .= " and t.payment_method = '$payment_mode' and (t.payment_type = 2 or t.payment_type = 3) ";
		}
		//** Condition to search based on company **//
		if(($company !="") && ($company !="All")) { $condition .= " and pl.company_id =  '$company'"; }
		//** Condition to search based on taxi id **//
	    if(($taxiid !="All")) 
	    { 
			$condition .= " and pl.taxi_id =  '$taxiid'"; 
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{
				//if(count($taxilist)>0)
				if($taxi_ids != "")
				{
					$condition .= " AND pl.taxi_id IN ( $taxi_ids )";
				}
			}
			else
			{
				$condition .= "";
			}
		}
		//** Condition to search based on driver id **//
	    if(($driverid !="All")) 
	    { 
			if($list !='rejected') {
				$condition .= " and pl.driver_id  =  '$driverid'"; 
			} else {
				$condition .= " and dr.driver_id  =  '$driverid'";
			}
		}
		else
		{ 
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{
				//if(count($driverlist)>0)
				if($driver_ids != "")
				{
					$condition .= " AND pl.driver_id IN ( $driver_ids )";
				}
			}
			else
			{
				$condition .= "";
			}
		}
		//** Condition to search based on passenger id **//
		if(($passengerid !="") && ($passengerid !="All")) 
		{ 
			$condition .= " and pl.passengers_id =  '$passengerid'"; 
		}   
		
		//** Condition to search based on date **//
		if($startdate !="") { $condition .= " and pl.pickup_time >=  '$startdate' and pl.pickup_time <=  '$enddate' "; }
		if($list =='rejected')
		{
			$query = " SELECT pl.no_passengers,pl.driver_reply,pl.passengers_log_id,pl.actual_pickup_time,pl.pickup_time,pl.current_location,pl.drop_location,pl.driver_reply,pl.driver_id,c.userid,c.company_name,group_concat(pe.name) AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone,pl.createdate as journey_date FROM `".PASSENGERS_LOG."` as pl left join `".COMPANY."` as c ON pl.company_id=c.cid left join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id left join ".DRIVER_REJECTION." as dr ON dr.passengers_log_id = pl.passengers_log_id left join `".PEOPLE."` as pe ON pe.id=dr.driver_id $condition and dr.rejection_type = '1' group by dr.passengers_log_id order by pl.passengers_log_id desc";
		}
		else
		{			
			$query = " SELECT pl.no_passengers,pl.driver_reply,pg.payment_gateway_name,c.userid,t.payment_method,t.transaction_id,t.payment_type,c.company_name,pl.driver_id,pl.passengers_log_id as passengers_log_id,pe.name AS driver_name,pe.phone AS driver_phone, pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone,pl.actual_pickup_time,pl.pickup_time,pl.current_location,pl.drop_location,t.admin_amount,t.company_amount,t.distance,t.nightfare,t.eveningfare,pl.used_wallet_amount,t.fare,pl.travel_status,pl.rating,pl.comments,t.distance_unit,pl.createdate as journey_date,t.waiting_time,t.distance FROM `".PASSENGERS_LOG."` as pl left join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id $left_join Join `".COMPANY."` as c ON pl.company_id=c.cid $left_join Join `".PEOPLE."` as pe ON pe.id=pl.driver_id $left_join Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id left Join ".PAYMENT_GATEWAYS_TYPES." as pg ON t.payment_gateway_id = pg.payment_gateway_id $condition $trans_condition $trip_condition order by pl.passengers_log_id desc";
		}
		//echo $query;
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $results;   	     
   	}
   	/**
	****Reject/Cancel Trips Count function**
	*** Returns overall count of Reject/Cancel Trips
	*/
	public function count_rejcancel_list($company,$manager_id,$taxiid,$driver_id,$passengerid,$startdate,$enddate)
	{				
		$usertype = $_SESSION['user_type'];
		//** Condition to search based on taxi and driver id in manager login **//
		if((($manager_id !='') && ($manager_id !='All')) || ($usertype = 'M'))
		{
			//** function to get taxi details **//
			$taxilist = $this->gettaxidetails($company,$manager_id);
			$taxi_id ="";
			if(count($taxilist)>0)
			{
				foreach($taxilist as $taxis)
				{
					$taxi_id .= $taxis["taxi_id"].',';
				}
				$taxi_ids = substr($taxi_id,0,strlen($taxi_id)-1);
			}
			else
			{
				$taxi_ids = "";
			}
			//** function to get driver details **//
			$driverlist = $this->getdriverdetails($company,$manager_id);
			$driver_id ="";
			if(count($driverlist)>0)
			{
				foreach($driverlist as $drivers)
				{
					$driver_id .= $drivers["id"].',';
				}
				$driver_ids = substr($driver_id,0,strlen($driver_id)-1);
			}
			else
			{
				$driver_ids = "";
			}
		}
		//** Condition to search based on travel status **//
		$condition = "WHERE pl.travel_status = '0' and pl.driver_reply != 'A' ";
		//** Condition to search based on company **//
		if(($company !="") && ($company !="All")) { $condition .= " and pl.company_id =  '$company'"; }
		//** Condition to search based on taxi id **//
		if(($taxiid !="All")) 
		{ 
			$condition .= " and pl.taxi_id =  '$taxiid'"; 
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype = 'M'))
			{	
				if(count($taxilist)>0)
				{	
					$condition .= "AND pl.taxi_id IN ( $taxi_ids )";
				}	
			}
			else
			{
				$condition .= "";
			}
		}
		//** Condition to search based on driver id **//
		if(($driver_id !="All")) 
		{ 
		
			$condition .= " and pl.driver_id =  '$driver_id'"; 
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype = 'M'))
			{	
				if(count($driverlist)>0)
				{						
					$condition .= "AND pl.driver_id IN ( $driver_ids )";
				}	
			}
			else
			{
				$condition .= "";
			}
		}
		//** Condition to search based on passenger id **//
		if(($passengerid !="") && ($passengerid !="All")) { $condition .= " and pl.passengers_id =  '$passengerid'"; }
		//** Condition to search based on date **//
		if($startdate !="") { $condition .= " and pl.createdate >=  '$startdate' and pl.createdate <=  '$enddate' "; }
		$query = " SELECT * , pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition order by pl.passengers_log_id desc";
		//echo $query;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return count($results);
	   
	}	
   	/**
	****Reject/Cancel Trip List function**
	*** Returns overall list of Reject/Cancel Trips
	*/
   	public function rejcancel_details($company,$manager_id,$taxiid,$driver_id,$passengerid,$startdate,$enddate,$offset='',$val='')
   	{
		$usertype = $_SESSION['user_type'];
		//** Condition to search based on taxi and driver id in manager login **//
		if((($manager_id !='') && ($manager_id !='All')) || ($usertype = 'M'))
	   	{
			//** function to get taxi details **//
			$taxilist = $this->gettaxidetails($company,$manager_id);
			$taxi_id ="";
			if(count($taxilist)>0)
			{
				foreach($taxilist as $taxis)
				{
					$taxi_id .= $taxis["taxi_id"].',';
				}
				$taxi_ids = substr($taxi_id,0,strlen($taxi_id)-1);
			}
			else
			{
				$taxi_ids = "";
			}
			//** function to get driver details **//
			$driverlist = $this->getdriverdetails($company,$manager_id);
			$cdriver_id ="";
			if(count($driverlist)>0)
			{
				foreach($driverlist as $drivers)
				{
					$cdriver_id .= $drivers["id"].',';
				}
				$driver_ids = substr($cdriver_id,0,strlen($cdriver_id)-1);
			}
			else
			{
				$driver_ids = "";
			}
		}
		//** Condition to search based on travel status **//
		$condition = "WHERE pl.travel_status != '1' and pl.driver_reply != 'A' ";
		//** Condition to search based on company **//
		if(($company !="") && ($company !="All")) { $condition .= " and pl.company_id =  '$company'"; }
		//** Condition to search based on taxi id **//
	    if(($taxiid !="All")) 
	    { 
			$condition .= " and pl.taxi_id =  '$taxiid'"; 
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype = 'M'))
			{
				if(count($taxilist)>0)
				{						
					$condition .= " AND pl.taxi_id IN ( $taxi_ids )";
				}
			}
			else
			{
				$condition .= "";
			}
		}
		//** Condition to search based on driver id **//
	    if(($driver_id !="All")) 
	    { 
			$condition .= " and pl.driver_id  =  '$driver_id'"; 
		}
		else
		{ 
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype = 'M'))
			{	
				if(count($driverlist)>0)
				{						
					$condition .= " AND pl.driver_id IN ( $driver_ids )";
				}	
			}
			else
			{
				$condition .= "";
			}
		}
		//** Condition to search based on passenger id **//
		if(($passengerid !="") && ($passengerid !="All")) { $condition .= " and pl.passengers_id =  '$passengerid'"; }
		//** Condition to search based on date **//
		if($startdate !="") { $condition .= " and pl.createdate >=  '$startdate' and pl.createdate <=  '$enddate' "; }		    
		$query = " SELECT * ,pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition order by pl.passengers_log_id desc limit $val offset $offset";
		//echo $query;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;   	     
   	}

   	
   	/** Function Used for Driver Dashboard Transaction log ****/
   	public function front_driver_transaction_details($company,$taxiid,$driver_id,$startdate,$enddate,$offset='',$val='')
   	{
		$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";
		//** Condition to search based on company id **//
		if(($company !="") && ($company !="All")) { $condition .= " and pl.company_id =  '$company'"; }
		//** Condition to search based on taxi id **//
	    if(($taxiid !="") && ($taxiid !="All")) { $condition .= " and pl.taxi_id =  '$taxiid'"; }
	    //** Condition to search based on driver id **//
	    if(($driver_id !="") && ($driver_id !="All"))  { $condition .= " and pl.driver_id =  '$driver_id'"; }
	    //** Condition to search based on date **//
		if($startdate !="") { $condition .= "and pl.createdate >=  '$startdate' and pl.createdate <=  '$enddate' "; }		    
		$query = " SELECT * ,pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition order by pl.passengers_log_id desc limit $val offset $offset";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;   	     
   	}
   	/************* Get Taxi List ******************/
   	public function gettaxidetails($company_id,$manager_id,$manager_details_array)
	{
		$usertype = $_SESSION['user_type'];	
		if(($manager_id !="") && ($manager_id !="All") || ($usertype == 'M')) { 
			if($usertype =='M')
			{
				$manager_id = $_SESSION['userid'];
			}
			//** function to get manager details ( city, state, country and company ) **//
			/* $manager_details = $this->manager_details($manager_id);
			$country_id = $manager_details[0]['login_country'];
			$state_id = $manager_details[0]['login_state'];
			$city_id = $manager_details[0]['login_city'];
			$company_id = $manager_details[0]['company_id']; */
			if(count($manager_details_array) > 0) {
				foreach($manager_details_array as $s) {
					$country_id = $s['login_country'];
					$state_id = $s['login_state'];
					$city_id = $s['login_city'];
					$company_id = $s['company_id'];
				}
			}
		}
		else
		{
			$country_id = $_SESSION['country_id'];
		   	$state_id = $_SESSION['state_id'];
		   	$city_id = $_SESSION['city_id'];		
		}
		
		$joins="";
		$condition = "";
		if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
	   	{
			//** Condition to search based on state, city, company id in manager login **//
			$joins="LEFT JOIN `country` ON (`taxi`.`taxi_country` = `country`.`country_id`) LEFT JOIN `state` ON (`taxi`.`taxi_state` = `state`.`state_id`) LEFT JOIN `city` ON (`taxi`.`taxi_city` = `city`.`city_id`) ";
			$condition .= "where `taxi_state` = '".$state_id."' AND `taxi_city` = '".$city_id."' AND `state`.`state_status` = 'A' and `city`.`city_status` = 'A'";
			if(($company_id !="") && ($company_id !="All")) { $condition .= "AND `taxi_company` = '".$company_id."'"; }
		}
		else
		{
			//** Condition to search based on company id **//
			if(($company_id !="") && ($company_id !="All")) { $condition .= "Where `taxi_company` = '".$company_id."'"; }
		}

		$query = "SELECT ".COMPANY.".time_zone, ".TAXI.".taxi_id,".TAXI.".taxi_no FROM ".TAXI." LEFT JOIN ".COMPANY." on ".COMPANY.".cid = ".TAXI.".taxi_company $joins $condition ORDER BY `taxi_id` DESC";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();			
		return $results;
	}	
   	/************* Get Driver List ******************/
   	public function getdriverdetails($company_id,$manager_id,$manager_details_array)
	{
		$usertype = $_SESSION['user_type'];
		if(($manager_id !="") && ($manager_id !="All")) {
			//** function to get manager details ( city, state, country and company ) **//
			/* $manager_details = $this->manager_details($manager_id);
			$country_id = $manager_details[0]['login_country'];
			$state_id = $manager_details[0]['login_state'];
			$city_id = $manager_details[0]['login_city'];
			$company_id = $manager_details[0]['company_id']; */
			if(count($manager_details_array) > 0) {
				foreach($manager_details_array as $s) {
					$country_id = $s['login_country'];
					$state_id = $s['login_state'];
					$city_id = $s['login_city'];
					$company_id = $s['company_id'];
				}
			}
		}
		else
		{
			$country_id = $_SESSION['country_id'];
		   	$state_id = $_SESSION['state_id'];
		   	$city_id = $_SESSION['city_id'];
		}
		$joins="";
		$condition = "WHERE `user_type` = 'D'";
		if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
	   	{
			//** Condition to search based on state, city, company id in manager login **//
			$joins="LEFT JOIN ".COUNTRY." ON (".PEOPLE.".login_country = ".COUNTRY.".country_id) LEFT JOIN ".STATE." ON (".PEOPLE.".login_state = ".STATE.".state_id) LEFT JOIN ".CITY." ON (".PEOPLE.".login_city = ".CITY.".city_id) ";
			
			$condition .= "and login_state = '".$state_id."' AND login_city = '".$city_id."' AND ".STATE.".state_status = 'A' and ".CITY.".city_status = 'A'";
		}
		//** Condition to search based on company id **//
		if(($company_id !="") && ($company_id !="All")) 
		{ 	
			$condition .= "AND company_id = '".$company_id."'";
		}

		$query = "SELECT ".PEOPLE.".id,".PEOPLE.".name,".PEOPLE.".lastname FROM ".PEOPLE." $joins $condition  ORDER BY `id` DESC";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;
		
	}

	/************* Function to get Manager Details ******************/

	public static function manager_details($manager_id)
	{
		$query = "SELECT login_country,login_state,login_city,company_id FROM ".PEOPLE." where user_type='M' and id='$manager_id' ORDER BY `id` DESC";
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $results;
	}

	/************* Get Taxi List ******************/
	public function getmanager_taxidetails($company_id,$manager_id)
	{
		//** function to get manager details ( city, state, country and company ) **//
		$manager_details = $this->manager_details($manager_id);
		$country_id = $manager_details[0]['login_country'];
	   	$state_id = $manager_details[0]['login_state'];
	   	$city_id = $manager_details[0]['login_city'];
	   	$manager_cmid = $manager_details[0]['company_id'];
		//** Condition to search based on state, city, company id **//
		$joins="LEFT JOIN `country` ON (`taxi`.`taxi_country` = `country`.`country_id`) LEFT JOIN `state` ON (`taxi`.`taxi_state` = `state`.`state_id`) LEFT JOIN `city` ON (`taxi`.`taxi_city` = `city`.`city_id`) ";
		$condition = "where `taxi_country` = '".$country_id."' AND `taxi_state` = '".$state_id."' AND `taxi_city` = '".$city_id."' AND `state`.`state_status` = 'A' and `city`.`city_status` = 'A'";
		//** Condition to search based on company id **//
		if(($company_id !="") && ($company_id !="All")) 
		{ 
			$condition .= "AND `taxi_company` = '".$company_id."'"; 
		} 
		else
		{
			$condition .= "AND `taxi_company` = '".$manager_cmid."'"; 
		}

		$query = "SELECT * FROM ".TAXI." $joins $condition ORDER BY `taxi_id` DESC";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;
	}	
   	/************* Get Driver List ******************/
   	public function getmanager_driverdetails($company_id,$manager_id)
	{
		//** function to get manager details ( city, state, country and company ) **//
		$manager_details = $this->manager_details($manager_id);	
		$country_id = $manager_details[0]['login_country'];
	   	$state_id = $manager_details[0]['login_state'];
	   	$city_id = $manager_details[0]['login_city'];
	   	$manager_cmid = $manager_details[0]['company_id'];
	   	//** Condition to search based on state, city, company id and usertype **//
		$condition = "WHERE `user_type` = 'D'";
		$joins="LEFT JOIN `country` ON (`".PEOPLE."`.`login_country` = `country`.`country_id`) LEFT JOIN `state` ON (`".PEOPLE."`.`login_state` = `state`.`state_id`) LEFT JOIN `city` ON (`".PEOPLE."`.`login_city` = `city`.`city_id`) ";
		$condition .= " and `login_country` = '".$country_id."' and `login_state` = '".$state_id."' AND `login_city` = '".$city_id."' AND `state`.`state_status` = 'A' and `city`.`city_status` = 'A'";
		//** Condition to search based on company id **//
		 if(($company_id !="") && ($company_id !="All"))
		 { 
		 	$condition .= "AND `company_id` = '".$company_id."'"; 
		 }
		 else
		 {
		 	$condition .= "AND `company_id` = '".$manager_cmid."'"; 		 
		 }
		 
		$query = "SELECT * FROM ".PEOPLE." $joins $condition  ORDER BY `id` DESC";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;
		
	}
		
   	/************* Get Manager List ******************/
   	public function getmanagerdetails($company_id)
	{
		$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
		$usertype = $_SESSION['user_type'];	
		//** Condition to search based on state, city, company id and usertype **//
		$joins="";
		$condition = "WHERE `user_type` = 'M'";
		if($usertype =='M')
	   	{
			$joins="LEFT JOIN ".STATE." ON (".PEOPLE.".login_state = ".STATE.".state_id) LEFT JOIN ".CITY." ON (".PEOPLE.".login_city = ".CITY.".city_id) ";
			$condition .= "and login_state = '".$state_id."' AND login_city = '".$city_id."' AND ".STATE.".state_status = 'A' and ".CITY.".city_status = 'A'";
		}
		//** Condition to search based on company id **//
		 if(($company_id !="") && ($company_id !="All")) { $condition .= "AND company_id = '".$company_id."'"; }
		$query = "SELECT ".PEOPLE.".id,".PEOPLE.".name,".PEOPLE.".lastname FROM ".PEOPLE." $joins $condition  ORDER BY `id` DESC";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;
		
	}
		
		
	/** Get Passengers List **************/
	public function getpassengerdetails($company_id,$manager_id,$manager_details_array)
	{
		$usertype = $_SESSION['user_type'];	
		
		if(($manager_id !="") && ($manager_id !="All")) {  
			//** function to get manager details ( city, state, country and company ) **//
			/* $manager_details = $this->manager_details($manager_id);
			$country_id = $manager_details[0]['login_country'];
			$state_id = $manager_details[0]['login_state'];
			$city_id = $manager_details[0]['login_city'];
			$company_id = $manager_details[0]['company_id']; */
			if(count($manager_details_array) > 0) {
				foreach($manager_details_array as $s) {
					$country_id = $s['login_country'];
					$state_id = $s['login_state'];
					$city_id = $s['login_city'];
					$company_id = $s['company_id'];
				}
			}
		}
		else
		{ 
			$country_id = $_SESSION['country_id'];
		   	$state_id = $_SESSION['state_id'];
		   	$city_id = $_SESSION['city_id'];		
		}
	   	//** Condition to search based on company id **//
		$joins="";
		$condition = " ";
		 if(($company_id !="") && ($company_id !="All")) 
		 { 	 
		 	$condition .= "WHERE ".PASSENGERS.".passenger_cid = '".$company_id."'"; 
		 }
         else
         {
			$joins=" LEFT "; 
		 }
		$query = "SELECT ".PASSENGERS.".id,".PASSENGERS.".name,".COMPANY.".company_name FROM ".PASSENGERS." {$joins}JOIN  ".COMPANY." ON (  ".PASSENGERS.".`passenger_cid` =  ".COMPANY.".`cid` ) $condition  ORDER BY `name` ASC";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return $results;	
	}
	/**
	 * ****export_data()****
	 *@export user listings
	 */
	public function export_data($list,$company,$manager_id,$taxiid,$driver_id,$passengerid,$startdate,$enddate,$transaction_id,$payment_type,$payment_mode='',$manager_details_array = "")
	{
		$left_join = "";
		$usertype = $_SESSION['user_type'];
		$condition = '';
		$driver_reply = '';
		$driver_comments = '';	
		if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
	   	{
			//** function to get taxi details **//
			$taxilist = $this->gettaxidetails($company,$manager_id,$manager_details_array);
			$taxi_id ="";
			if(count($taxilist)>0)
			{
				foreach($taxilist as $taxis)
				{
					$taxi_id .= $taxis["taxi_id"].',';
				}
				$taxi_ids = substr($taxi_id,0,strlen($taxi_id)-1);
			}
			else
			{
				$taxi_ids = "";
			}
			//** function to get driver details **//
			$driverlist = $this->getdriverdetails($company,$manager_id,$manager_details_array);
			$cdriver_id ="";
			if(count($driverlist)>0)
			{
				foreach($driverlist as $drivers)
				{
					$cdriver_id .= $drivers["id"].',';
				}
				$driver_ids = substr($cdriver_id,0,strlen($cdriver_id)-1);
			}
			else
			{
				$driver_ids = "";
			}
		}
		//** function to get passenger details **//
		$passengerlist = $this->getpassengerdetails($company,$manager_id,$manager_details_array);
		$cpassenger_id ="";
		if(count($passengerlist)>0)
		{
			foreach($passengerlist as $passengers)
			{
				$cpassenger_id .= $passengers["id"].',';
			}
			$passenger_ids = substr($cpassenger_id,0,strlen($cpassenger_id)-1);
		}
		else
		{
			$passenger_ids = "";
		}
		$xls_output = "<table border='1' cellspacing='0' cellpadding='5'>";
		if($list != 'rejected') {
			$xls_output .= "<th>".__('sno')."</th>";
			$xls_output .= "<th>".__('cctransaction_id')."</th>";
			$xls_output .= "<th>".__('payment_type')."</th>";
		} 
		$xls_output .= "<th>".__('trip_id')."</th>";
		$xls_output .= "<th>".__('passenger_name')."</th>";
		$xls_output .= "<th>".ucfirst(__('driver_name'))."</th>";
		$xls_output .= "<th>".__('journey_date')."</th>";
		$xls_output .= "<th>".__('passenger_email')."</th>";
		$xls_output .= "<th>".__('Current_Location')."</th>";
		$xls_output .= "<th>".__('Drop_Location')."</th>";
		$xls_output .= "<th>".__('companyname')."</th>";
		if($list != 'rejected') { 
			$xls_output .= "<th>".__('admin_commision')."</th>";
			$xls_output .= "<th>".__('company_commision')."</th>";
		} 

		if($list != 'rejected' && $list != 'cancelled' ) { 
			$xls_output .= "<th>".__('waiting_time_with_format')."</th>";
			$xls_output .= "<th>".__('distance_km')."</th>";
			$xls_output .= "<th>".__('trip_total_fare')."</th>";
			//$xls_output .= "<th>".__('equivalent_to_usd').CURRENCY_FORMAT."</th>";
			$xls_output .= "<th>".__('nightfare')."</th>";
			$xls_output .= "<th>".__('eveningfare')."</th>";
		} 
		elseif($list == 'cancelled') { 
			$xls_output .= "<th>".__('cancel_fare').'('.CURRENCY.')'."</th>";
		} 
		else {	
			$xls_output .= "<th>".__('travel_status')."</th>";
			//$xls_output .= "<th>".__('reason')."</th>";
		} 
		$file = 'Export';

		if($transaction_id !='')
		{
			$trans_condition = " and t.transaction_id like '%".$transaction_id."%'";
		}
		else
		{
			$trans_condition = '';
		}
		if($list =='all')
		{
			$left_join = "left";
			$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";
		}
		else if($list =='success')
		{
			$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";
		}
		else if($list =='cancelled')
		{
			$condition = "WHERE ((pl.travel_status = '4' and pl.driver_reply = 'A') or (pl.travel_status = '8') or (pl.travel_status = 9 and pl.driver_reply = 'C'))";
		}
		else if($list =='rejected')
		{
			$condition = "WHERE pl.travel_status = '0'";
		}
		else if($list =='pendingpayment')
		{
			$condition = "WHERE pl.travel_status = '5' and pl.driver_reply = 'A' ";
		}
		
		if($payment_type !='All' && $payment_type !='' )
		{
			if($list !='rejected')
			{
				$condition .= " and payment_type = '$payment_type'";
			}
		}
		
		if($payment_mode !='All' && $payment_mode !='')
		{
			$condition .= " and payment_method = '$payment_mode' and (t.payment_type = 2 or t.payment_type = 3) ";
		}

		if(($company !="") && ($company !="All")) { $condition .= " and pl.company_id =  '$company'"; }

	    if(($taxiid !="All") && ($taxiid !="")) 
	    { 
			$condition .= " and pl.taxi_id =  '$taxiid'"; 
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{
				$condition .= " AND pl.taxi_id IN ( $taxi_ids )";
			}
			else
			{
				$condition .= "";
			}
		}
	    if(($driver_id !="All") && ($driver_id !="")) 
	    { 
			//$condition .= " and pl.driver_id =  '$driver_id'"; 
			if($list !='rejected') {
				$condition .= " and pl.driver_id  =  '$driver_id'"; 
			} else {
				$condition .= " and dr.driver_id  =  '$driver_id'";
			}
		}
		else if(($driver_id !="All") && ($driver_id =="")) {
			$condition .= "";
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{
				$condition .= " AND pl.driver_id IN ( $driver_ids )";
			}
			else
			{
				$condition .= "";
			}
		}
		$passengerlist = $this->getpassengerdetails($company,$manager_id,$manager_details_array);
		$cpassenger_id ="";
		if(count($passengerlist)>0)
		{
			foreach($passengerlist as $passengers)
			{
				$cpassenger_id .= $passengers["id"].',';
			}
			$passenger_ids = substr($cpassenger_id,0,strlen($cpassenger_id)-1);
		}
		else
		{
			$passenger_ids = "";
		}
		if($startdate !="") { $condition .= " and pl.pickup_time >=  '$startdate' and pl.pickup_time <=  '$enddate' "; }
		if(($passengerid !="") && ($passengerid !="All")) { $condition .= " and pl.passengers_id =  '$passengerid'"; }	

		if($list =='rejected')
		{
				//$query = " SELECT * , pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl left join `".COMPANY."` as c ON pl.company_id=c.cid left join `".PEOPLE."` as pe ON pe.id=pl.driver_id left Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition order by pl.passengers_log_id desc";
				$query = " SELECT * , group_concat(pe.name) AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl left join `".COMPANY."` as c ON pl.company_id=c.cid left join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id left join ".DRIVER_REJECTION." as dr ON dr.passengers_log_id = pl.passengers_log_id left join `".PEOPLE."` as pe ON pe.id=dr.driver_id $condition and dr.rejection_type = '1' group by pl.passengers_log_id order by pl.passengers_log_id desc";
		}
		else
		{		    
			$query = " SELECT * ,pl.passengers_log_id as passengers_log_id,pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl left join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id $left_join Join `".COMPANY."` as c ON pl.company_id=c.cid $left_join Join `".PEOPLE."` as pe ON pe.id=pl.driver_id $left_join Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id Left Join ".PAYMENT_GATEWAYS_TYPES." as pg ON t.payment_gateway_id = pg.payment_gateway_id $condition $trans_condition order by pl.passengers_log_id desc";
		}
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		$sno=0;
		$total_fare = 0;
		foreach($results as $result)
		{
			if($list != 'rejected') { 
				$paymentMod = ($result['payment_method'] == 'L') ? 'Live' : 'Test';
				if($result['distance'] != 0) { $distance = round($result['distance'],2); } else { $distance =  '--';  }
				if($result['fare'] != 0) { $fare =  round($result['fare'],2); } else { $fare =  '--'; }
				if($result['comments'] != '') { $comments =  $result['comments']; } else { $comments =  'No Comments'; }

			}
			else
			{
				if($result['driver_reply'] == 'C') { $driver_reply =  __('cancelled_by_driver'); } else { $driver_reply = __('rejected_by_driver'); }
				if($result['driver_comments'] == '') { $driver_comments = ''; } else { $driver_comments = $result['driver_comments']; }
			}	
			if(isset($result['company_amount'])){if($result['company_amount'] <= 0) { $company_amount =  '0'; } else { $company_amount = round($result['distance'],2);  } }
			if($result['rating'] == 0) { $ratings =  '-'; } else { $ratings =  $result['rating']; }
			
			$xls_output .= "<tr>"; 
			if($list != 'rejected') { 
				$xls_output .= "<td>".++$sno."</td>"; 
				$xls_output .= "<td>".ucfirst($result['transaction_id'])."</td>"; 
				 
				if($result['payment_type'] == 2 )
				{
					$xls_output .= "<td>Credit Card Using ".$result['payment_gateway_name']." ( ".$paymentMod." )</td>";
				}
				else if($result['payment_type'] == 3 )
				{
					$xls_output .= "<td> ".__('new_credit_card')." ( ".$paymentMod." )</td>";
				}
				else if($result['payment_type'] == 4) 
				{
					$xls_output .= "<td> ".__('account')." </td>";
				}
				else
				{
					$xls_output .= "<td>Cash</td>"; 	
				}
				
			}
			$xls_output .= "<td>".$result['passengers_log_id']."</td>";
			$journeyDate = ($result['actual_pickup_time'] != '0000-00-00 00:00:00') ? Commonfunction::getDateTimeFormat($result['actual_pickup_time'],1) : Commonfunction::getDateTimeFormat($result['pickup_time'],1);
			$xls_output .= "<td>".ucfirst($result['passenger_name'])."</td>"; 
			$xls_output .= "<td>".wordwrap(ucfirst($result['driver_name']),30,'<br/>',1)."</td>"; 
			$xls_output .= "<td>".$journeyDate."</td>"; 
			$xls_output .= "<td>".wordwrap($result['email'],50,'<br />',1)."</td>"; 
			$xls_output .= "<td>".strip_tags(htmlentities($result['current_location']))."</td>"; 
			$xls_output .= "<td>".strip_tags(htmlentities($result['drop_location']))."</td>"; 
			$xls_output .= "<td>".wordwrap($result['company_name'],25,'<br />',1)."</td>"; 
			if($list != 'rejected') { 
			$xls_output .= "<td>".$result['admin_amount']."</td>"; 
			$xls_output .= "<td>".$result['company_amount']."</td>"; 
			} 
			if($list != 'rejected' && $list != 'cancelled') { 
			//$waitingTime = (!empty($result['waitingtime'])) ? $result['waitingtime'].' Mins': '--';
			$waitingTime = '--';
			if(!empty($result['waiting_time'])) {
				$waitingTimeArr = explode(" ",$result['waiting_time']);
				$waitingTimeFormat = explode(":",$waitingTimeArr[0]);
				$waitingTime = (!isset($waitingTimeFormat[2])) ? '00:'.$waitingTimeArr[0] : $waitingTimeArr[0];
			}
			$xls_output .= "<td>".$waitingTime."</td>"; 
			$xls_output .= "<td>".$distance."</td>"; 
			$company_currency = $result['company_id'];
			//function to get company currency
			//$ccur = findcompany_currencyformat($company_currency);
			$ccur = CURRENCY_FORMAT;
			$xls_output .= "<td>".$ccur.' '.$fare."</td>";
			//function to convert currency
			$convet_amt = currency_conversion($ccur,$fare);
			$con_amt = round($convet_amt,2);
			//$xls_output .= "<td>".$con_amt."</td>";
			$nightfare = (!empty($result['nightfare'])) ? $ccur.' '.$result['nightfare']: '--';
			$eveningfare = (!empty($result['eveningfare'])) ? $ccur.' '.$result['eveningfare']: '--';
			$xls_output .= "<td>".$nightfare."</td>";
			$xls_output .= "<td>".$eveningfare."</td>";
			}
			elseif($list == 'cancelled') { 
				$company_currency = $result['company_id'];
				//function to get company currency
				$ccur = findcompany_currencyformat($company_currency);
				$xls_output .= "<td>".$ccur.' '.$fare."</td>"; 
				//function to convert currency
				$convet_amt = currency_conversion($ccur,$fare);
			}
			else
			{
			$xls_output .= "<td>".$driver_reply."</td>"; 
			//$xls_output .= "<td>".$driver_comments."</td>"; 	
			} 
			$xls_output .= "</tr>"; 
			if($list != 'rejected') {
				$total_fare +=$convet_amt;
			}
		}
		
		if($list != 'rejected' && count($results) > 0) {
			$colspan = ($list == 'cancelled') ? '12' : '15';
			$xls_output .= "<tr><td colspan='$colspan' align='right'>".__('trip_total_fare')."</td><td>".CURRENCY_FORMAT." $total_fare</td></tr>";
		}
		$xls_output .= "</table>";

		$filename = $file."_".date("Y-m-d_H-i",time());
		header("Content-Disposition: attachment; filename=".$filename.".xls");
		echo $xls_output; 
		exit;
	}

	/**
	 * ****export_data()****
	 *@export user listings as pdf
	 */
	public function export_data_pdf($list,$company,$manager_id,$taxiid,$driver_id,$passengerid,$startdate,$enddate,$transaction_id,$payment_type,$payment_mode='',$manager_details_array = "")
	{
		$left_join = "";
		//$company;
		//$company,$taxiid,$driver_id,$startdate,$enddate,
		$usertype = $_SESSION['user_type'];
		$condition = '';
		$driver_reply = '';
		$driver_comments = '';	
		if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
	   	{
			
			$taxilist = $this->gettaxidetails($company,$manager_id,$manager_details_array);
			$taxi_id ="";
			if(count($taxilist)>0)
			{
				foreach($taxilist as $taxis)
				{
					$taxi_id .= $taxis["taxi_id"].',';
				}
				$taxi_ids = substr($taxi_id,0,strlen($taxi_id)-1);
			}
			else
			{
				$taxi_ids = "";
			}
			$driverlist = $this->getdriverdetails($company,$manager_id,$manager_details_array);
			$cdriver_id ="";
			if(count($driverlist)>0)
			{
				foreach($driverlist as $drivers)
				{
					$cdriver_id .= $drivers["id"].',';
				}
				$driver_ids = substr($cdriver_id,0,strlen($cdriver_id)-1);
			}
			else
			{
				$driver_ids = "";
			}
		}

		$passengerlist = $this->getpassengerdetails($company,$manager_id,$manager_details_array);
		$cpassenger_id ="";
			if(count($passengerlist)>0)
			{
				foreach($passengerlist as $passengers)
				{
					$cpassenger_id .= $passengers["id"].',';
				}
				$passenger_ids = substr($cpassenger_id,0,strlen($cpassenger_id)-1);
			}
			else
			{
				$passenger_ids = "";
			}
		//echo $passenger_ids;
		

		if($transaction_id !='')
		{
			$trans_condition = " and t.transaction_id like '%".$transaction_id."%'";
		}
		else
		{
			$trans_condition = '';
		}
			if($list =='all')
			{
				$left_join = "left";
				//$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";//"WHERE pl.driver_reply = 'A' ";
				$condition = "WHERE 1 = 1";
			}
			else if($list =='success')
			{
				$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";
			}
			else if($list =='cancelled')
			{
				$condition = "WHERE ((pl.travel_status = '4' and pl.driver_reply = 'A') or (pl.travel_status = '8') or (pl.travel_status = 9 and pl.driver_reply = 'C'))";
			}
			else if($list =='rejected')
			{
				$condition = "WHERE pl.travel_status = '0'";
			}
			else if($list =='pendingpayment')
			{
				$condition = "WHERE pl.travel_status = '5' and pl.driver_reply = 'A' ";
			}
			
			if($payment_type !='All' && $payment_type !='' )
			{
				if($list !='rejected')
				{
					$condition .= " and payment_type = '$payment_type'";
				}
			}
			
			if($payment_mode !='All' && $payment_mode !='')
			{
				$condition .= " and payment_method = '$payment_mode'  and (t.payment_type = 2 or t.payment_type = 3)  ";
			}

			if(($company !="") && ($company !="All")) { $condition .= " and pl.company_id =  '$company'"; }

	    if(($taxiid !="All") && ($taxiid !="")) 
	    { 
			$condition .= " and pl.taxi_id =  '$taxiid'"; 
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{			
				$condition .= " AND pl.taxi_id IN ( $taxi_ids )";
			}
			else
			{
				$condition .= "";
			}
		}
	    if(($driver_id !="All") && ($driver_id !="")) 
	    { 
			//$condition .= " and pl.driver_id =  '$driver_id'"; 
			if($list !='rejected') {
				$condition .= " and pl.driver_id  =  '$driver_id'"; 
			} else {
				$condition .= " and dr.driver_id  =  '$driver_id'";
			}
		}
		else if(($driver_id !="All") && ($driver_id =="")) {
			$condition .= "";
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{			
				$condition .= " AND pl.driver_id IN ( $driver_ids )";
			}
			else
			{
				$condition .= "";
			}
		}
		  $passengerlist = $this->getpassengerdetails($company,$manager_id,$manager_details_array);
		  $cpassenger_id ="";
			if(count($passengerlist)>0)
			{
				foreach($passengerlist as $passengers)
				{
					$cpassenger_id .= $passengers["id"].',';
				}
				$passenger_ids = substr($cpassenger_id,0,strlen($cpassenger_id)-1);
			}
			else
			{
				$passenger_ids = "";
			}
		//echo $passenger_ids;
		if($startdate !="") { $condition .= " and pl.pickup_time >=  '$startdate' and pl.pickup_time <=  '$enddate' "; }
		if(($passengerid !="") && ($passengerid !="All")) { $condition .= " and pl.passengers_id =  '$passengerid'"; }	

		if($list =='rejected')
		{
			//$query = " SELECT * , pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl left join `".COMPANY."` as c ON pl.company_id=c.cid left join `".PEOPLE."` as pe ON pe.id=pl.driver_id left join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition order by pl.passengers_log_id desc";
			$query = " SELECT * , group_concat(pe.name) AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl left join `".COMPANY."` as c ON pl.company_id=c.cid left join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id left join ".DRIVER_REJECTION." as dr ON dr.passengers_log_id = pl.passengers_log_id left join `".PEOPLE."` as pe ON pe.id=dr.driver_id $condition and dr.rejection_type = '1' group by pl.passengers_log_id order by pl.passengers_log_id desc";
		}
		else
		{ 
			$query = " SELECT * ,pl.passengers_log_id as passengers_log_id,pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl left join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id $left_join Join `".COMPANY."` as c ON pl.company_id=c.cid $left_join Join `".PEOPLE."` as pe ON pe.id=pl.driver_id $left_join Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id Left Join ".PAYMENT_GATEWAYS_TYPES." as pg ON t.payment_gateway_id = pg.payment_gateway_id $condition $trans_condition order by pl.passengers_log_id desc";
		}
//echo $query; exit;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;
	}

	public function accountexport_data($list,$company,$manager_id,$taxiid,$driver_id,$passengerid,$startdate,$enddate,$transaction_id)
	{


		$usertype = $_SESSION['user_type'];
		$condition = '';
		$driver_reply = '';
		$driver_comments = '';	
		if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
	   	{
			
			$taxilist = $this->gettaxidetails($company,$manager_id);
			$taxi_id ="";
			if(count($taxilist)>0)
			{
				foreach($taxilist as $taxis)
				{
					$taxi_id .= $taxis["taxi_id"].',';
				}
				$taxi_ids = substr($taxi_id,0,strlen($taxi_id)-1);
			}
			else

			{
				$taxi_ids = "";
			}
			$driverlist = $this->getdriverdetails($company,$manager_id);
			$cdriver_id ="";
			if(count($driverlist)>0)
			{
				foreach($driverlist as $drivers)
				{
					$cdriver_id .= $drivers["id"].',';
				}
				$driver_ids = substr($cdriver_id,0,strlen($cdriver_id)-1);
			}
			else
			{
				$driver_ids = "";
			}
		}
			$xls_output = "<table border='1' cellspacing='0' cellpadding='5'>";
			$xls_output .= "<th>".__('cctransaction_id')."</th>";
			$xls_output .= "<th>".__('payment_type')."</th>";
			$xls_output .= "<th>".__('trip_id')."</th>";
			/*$xls_output .= "<th>".__('package_type')."</th>";*/
			$xls_output .= "<th>".__('companyname')."</th>";
			$xls_output .= "<th>".__('admin_commision')."</th>";
			$xls_output .= "<th>".__('company_commision')."</th>";
			$xls_output .= "<th>".__('journey_date')."</th>";
			$xls_output .= "<th>".__('trip_total_fare').'('.CURRENCY.')'."</th>";

			$file = 'Export';

		if($transaction_id !='')
		{
			$trans_condition = " and t.transaction_id like '%".$transaction_id."%'";
		}
		else
		{
			$trans_condition = '';
		}

			$condition = " WHERE ( pl.travel_status = '1' or pl.travel_status = '4' ) and pl.driver_reply = 'A' ";

			if(($company !="") && ($company !="All")) { $condition .= " and pl.company_id =  '$company'"; }
	
	    if(($taxiid !="All") && ($taxiid !="")) 
	    { 
			$condition .= " and pl.taxi_id =  '$taxiid'"; 
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{	
		
				$condition .= " AND pl.taxi_id IN ( $taxi_ids )";
			}
			else
			{
				$condition .= "";
			}
		}
	    if(($driver_id !="All") && ($driver_id !="")) 
	    { 
			$condition .= " and pl.driver_id =  '$driver_id'"; 
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{			
				$condition .= " AND pl.driver_id IN ( $driver_ids )";
			}
			else
			{
				$condition .= "";
			}
		}

		if($startdate !="") { $condition .= " and pl.createdate >=  '$startdate' and pl.createdate <=  '$enddate' "; }
		if(($passengerid !="") && ($passengerid !="All")) { $condition .= " and pl.passengers_id =  '$passengerid'"; }	

		    
				$query = " SELECT * ,pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition $trans_condition order by pl.passengers_log_id desc";


		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();


		foreach($results as $result)
		{

			if($result['fare'] == 0) { $fare =  '-'; } else { $fare =  round($result['fare'],2);}
			$xls_output .= "<tr>"; 
			$xls_output .= "<td>".ucfirst($result['transaction_id'])."</td>"; 
			 
			if($result['payment_type'] == 2 )
			{
				$xls_output .= "<td>Credit Card Using Paypal</td>"; 
			}	
			else
			{
				$xls_output .= "<td>Cash</td>"; 	
			}
			$xls_output .= "<td>".$result['passengers_log_id']."</td>"; 
			$xls_output .= "<td>".wordwrap($result['company_name'],25,'<br />',1)."</td>"; 
			$xls_output .= "<td>".$result['admin_amount']."</td>"; 
			$xls_output .= "<td>".$result['company_amount']."</td>"; 
			$xls_output .= "<td>".$result['createdate']."</td>"; 
			$xls_output .= "<td>".$fare."</td>"; 
			$xls_output .= "</tr>"; 
		}

		$xls_output .= "</table>";

		$filename = $file."_".date("Y-m-d_H-i",time());
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Length: " . strlen($xls_output));
		header("Content-type: application/vnd.ms-excel");
		header("Content-type: application/octet-stream, charset=UTF-8; encoding=UTF-8");
		header("Content-Disposition: attachment; filename=".$filename.".xls");
		echo $xls_output; 
		exit;
	}


	/********* Graph ****************/
	public function getgraphvalues($list,$company,$manager_id,$taxiid,$driverid,$passengerid,$startdate,$enddate,$offset='',$val='',$transaction_id,$payment_type, $payment_mode="", $passenger_ids='',$taxi_ids = "",$driver_ids = "",$trip_id="")
	{
		$left_join = "";
		$usertype = $_SESSION['user_type'];
		//** Condition to search based on transaction id **//
		if($trip_id !='')
		{
			$trip_condition = " and t.passengers_log_id like '%".$trip_id."%' ";
		}
		else
		{
			$trip_condition ='';
		}
		if($transaction_id !='')
		{
			$trans_condition = " and t.transaction_id like '%".$transaction_id."%'";
		}
		else
		{
			$trans_condition ='';
		}
		//** Condition to search based on status **//
		if($list =='all')
		{
			$left_join = "left";
			$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";//pl.driver_reply = 'A' ";
		}
		else if($list =='success')
		{
			$left_join = "left";
			$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";
		}
		else if($list =='cancelled')
		{
			$condition = "WHERE ((pl.travel_status = '4' and pl.driver_reply = 'A') or (pl.travel_status = '8') or (pl.travel_status = 9 and pl.driver_reply = 'C'))";
		}
		else if($list =='rejected')
		{
			$condition = "WHERE pl.travel_status = '0'";
		}
		else if($list =='pendingpayment')
		{
			$condition = "WHERE pl.travel_status = '5' and pl.driver_reply = 'A' ";
		}
		//** Condition to search based on payment type **//
		if($payment_type !='All' && $payment_type !='' )
		{
			if($list !='rejected')
			{
				$condition .= " and t.payment_type = '$payment_type'";
			}
		}
		//** filter based payment mode ( Ex: Live or Test )
		if($payment_mode !='All' && $payment_mode !='' && $list !='rejected')
		{
			$condition .= " and t.payment_method = '$payment_mode' and (t.payment_type = 2 or t.payment_type = 3) ";
		}
		//** Condition to search based on company **//
		if(($company !="") && ($company !="All")) { 
			/* $condition .= " and pl.company_id =  '$company'"; before sureshkumar.m modified code in 6.0 pack */ 
			//$condition .= " and pl.company_id =  '$company' and pa.passenger_cid = '$company' "; 
			$condition .= " and pl.company_id =  '$company'";
			/* sureshkumar.m modified code for 6.0 pack here i have checked condition for passenger company id */  
		}
		//** Condition to search based on taxi id **//
	    if(($taxiid !="All")) 
	    { 
			$condition .= " and pl.taxi_id =  '$taxiid'"; 
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{
				//if(count($taxilist)>0)
				if($taxi_ids != "")
				{
					$condition .= " AND pl.taxi_id IN ( $taxi_ids )";
				}
			}
			else
			{
				$condition .= "";
			}
		}
		//** Condition to search based on driver id **//
	    if(($driverid !="All")) 
	    { 
			if($list !='rejected') {
				$condition .= " and pl.driver_id  =  '$driverid'"; 
			} else {
				$condition .= " and dr.driver_id  =  '$driverid'";
			}
		}
		else
		{ 
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{
				//if(count($driverlist)>0)
				if($driver_ids != "")
				{
					$condition .= " AND pl.driver_id IN ( $driver_ids )";
				}
			}
			else
			{
				$condition .= "";
			}
		}
		//** Condition to search based on passenger id **//
		if(($passengerid !="") && ($passengerid !="All")) 
		{ 
			$condition .= " and pl.passengers_id =  '$passengerid'"; 
		}
		if($list !='rejected') {
			if($startdate !="") { $condition .= " and pl.pickup_time >=  '$startdate' and pl.pickup_time <=  '$enddate' "; }
			$query = " SELECT pl.createdate,pl.pickup_time,sum(t.fare) as amount,SUM(IF(pl.travel_status = 4, t.fare, 0)) as cancelled_amount,SUM(IF(pl.travel_status =1, t.fare, 0)) as completed_amount,SUM(IF(pl.travel_status = 5, t.fare, 0)) as pending_amount FROM `".PASSENGERS_LOG."` as pl left join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id $left_join Join `".COMPANY."` as c ON pl.company_id=c.cid $left_join Join `".PEOPLE."` as pe ON pe.id=pl.driver_id $left_join Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id left Join ".PAYMENT_GATEWAYS_TYPES." as pg ON t.payment_gateway_id = pg.payment_gateway_id $condition $trans_condition $trip_condition group by DATE(pl.createdate) order by pl.passengers_log_id desc";

			/* if($startdate !="") { $condition .= "and pl.pickup_time >=  '$startdate' and pl.pickup_time <=  '$enddate' "; }
			$query = " SELECT pl.createdate,pl.pickup_time,sum(t.fare) as amount,SUM(IF(pl.travel_status = 4, t.fare, 0)) as cancelled_amount,SUM(IF(pl.travel_status =1, t.fare, 0)) as completed_amount,SUM(IF(pl.travel_status = 5, t.fare, 0)) as pending_amount FROM ".PASSENGERS_LOG." as pl left join ".TRANS." as t ON pl.passengers_log_id = t.passengers_log_id Join ".COMPANY." as c ON pl.company_id=c.cid Join ".PEOPLE." as pe ON pe.id=pl.driver_id Join ".PASSENGERS." as pa ON pl.passengers_id = pa.id $condition $trans_condition group by DATE(pl.createdate)"; */
			$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		} else {
			$results = array();
		}
		return $results;
	}

	public function viewtransaction_details($log_id)
	{
		$query = " SELECT pl.drop_latitude,pl.arrived_time,pe.device_type,pl.promocode,pl.booking_from,t.passenger_discount,pl.drop_longitude,dl.active_record,t.payment_type,c.company_name,pl.actual_pickup_time,pl.drop_time,pl.current_location,pl.drop_location,t.distance,t.tripfare,t.minutes_fare,t.distance_unit,t.nightfare,t.trip_minutes,t.nightfare_applicable,t.eveningfare_applicable,t.company_tax,t.fare as amt,t.fare,pl.used_wallet_amount,pl.travel_status,pl.driver_comments,pl.passenger_app_version,pl.driver_app_version,pe.name AS driver_name,pe.phone AS driver_phone,pa.name AS passenger_name,pl.company_tax AS org_tax,pa.email AS passenger_email,pa.phone AS passenger_phone,t.waiting_time as taxi_waiting_time,t.waiting_cost as taxi_waiting_cost,t.payment_status,t.eveningfare,pl.comments FROM `".PASSENGERS_LOG."` as pl left join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid left Join `".PEOPLE."` as pe ON pe.id=pl.driver_id left Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id left join ".DRIVER_LOCATION_HISTORY." as dl ON dl.trip_id = pl.passengers_log_id where pl.passengers_log_id='$log_id'";
		//echo $query; exit;
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $results;
	}

	public function count_accountreport_list($list="",$company="",$startdate="",$enddate="",$payment_type="")
	{		
		$usertype = $_SESSION['user_type'];
		$condition="";
		$trans_condition="";
		if($payment_type !='All' && $payment_type !='' )
		{
			$condition .= " and payment_type = '$payment_type' ";
		}
		if(($company !="") && ($company !="All")) { $condition .= " and pl.company_id =  '$company'"; }
		if($startdate !="") { $condition .= " and pl.createdate >=  '$startdate' and pl.createdate <=  '$enddate' "; }
	   	$query = " SELECT * , pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition $trans_condition order by pl.passengers_log_id desc";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return count($results);
	   
	}
	
	public function accountreport_details($list,$company,$startdate,$enddate,$payment_type)
   	{

		//$totalfare = "select sum(fare) from `transacation`";
		$usertype = $_SESSION['user_type'];
		$condition="";
		$trans_condition="";
		if($payment_type !='All' && $payment_type !='' )
		{
			$condition .= " and payment_type = '$payment_type' ";
		}

		if(($company !="") && ($company !="All")) { $condition .= " and pl.company_id =  '$company'"; }	    
		if($startdate !="") { $condition .= " and pl.createdate >=  '$startdate' and pl.createdate <=  '$enddate' "; }
		$query = " SELECT t.fare,pg.currency_code,pg.currency_symbol,c.cid,pe.name AS driver_name,pe.phone AS driver_phone, pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PAYMENT_GATEWAYS."` as pg ON c.cid = pg.company_id Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition $trans_condition order by pl.passengers_log_id desc";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return $results;   	     
   	}	//

	//public function getaccountreportvalues($list,$company,$manager_id,$taxiid,$driver_id,$passengerid,$startdate,$enddate,$transaction_id,$payment_type)
	public function getaccountreportvalues($list,$company,$startdate,$enddate,$payment_type)
	{ 
		//echo $startdate;
		$usertype = $_SESSION['user_type'];
		$condition="";$trans_condition="";
		/*if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
	   	{
			$taxilist = $this->gettaxidetails($company,$manager_id);
			$taxi_id ="";
			if(count($taxilist)>0)
			{
				foreach($taxilist as $taxis)
				{
					$taxi_id .= $taxis["taxi_id"].',';
				}
				$taxi_ids = substr($taxi_id,0,strlen($taxi_id)-1);
			}
			else
			{
				$taxi_ids = "";
			}
			$driverlist = $this->getdriverdetails($company,$manager_id);
			$cdriver_id ="";
			if(count($driverlist)>0)
			{
				foreach($driverlist as $drivers)
				{
					$cdriver_id .= $drivers["id"].',';
				}
				$driver_ids = substr($cdriver_id,0,strlen($cdriver_id)-1);
			}
			else
			{
				$driver_ids = "";
			}
		}

		$passengerlist = $this->getpassengerdetails($company,$manager_id);
		$cpassenger_id ="";
			if(count($passengerlist)>0)
			{
				foreach($passengerlist as $passengers)
				{
					$cpassenger_id .= $passengers["id"].',';
				}
				$passenger_ids = substr($cpassenger_id,0,strlen($cpassenger_id)-1);
			}
			else
			{
				$passenger_ids = "";
			}
		//echo $passenger_ids;	
		
		if($transaction_id !='')
		{
			$trans_condition = " and t.transaction_id like '%".$transaction_id."%'";
		}
		else
		{
			$trans_condition ='';
		}

		$condition = "WHERE ( pl.travel_status = '1' or pl.travel_status = '4' ) and pl.driver_reply = 'A' ";
		
			
	    if(($taxiid !="All")) 
	    { 
			$condition .= " and pl.taxi_id =  '$taxiid'"; 
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{	
				if(count($taxilist)>0)
				{					
					$condition .= "AND pl.taxi_id IN ( $taxi_ids )";
				}	
			}
			else
			{
				$condition .= "";
			}
		}
	    if(($driver_id !="All")) 
	    { 
			$condition .= " and pl.driver_id =  '$driver_id'"; 
		}
		else
		{
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{	
				if(count($driverlist)>0)
				{					
					$condition .= "AND pl.driver_id IN ( $driver_ids )";
				}	
			}
			else
			{
				$condition .= "";
			}
		}
	   	
	   	if(($passengerid !="") && ($passengerid !="All")) 
		{ 
			$condition .= " and pl.passengers_id =  '$passengerid'"; 
		}	
		else
		{
			//echo $usertype;
			if(($usertype == 'M') || ($usertype == 'C'))
			{
				if(count($passengerlist)>0)
				{
					//$condition .= " AND pl.passengers_id IN ( $passenger_ids )";
				}
			}
			else
			{
				//$condition .= " ";
			}
		} */

		if($payment_type !='All' && $payment_type !='' )

			{
				$condition .= " and payment_type = '$payment_type' ";
			}


		if(($company !="") && ($company !="All")) { $condition .= " and pl.company_id =  '$company' "; } 	   
		if($startdate !="") { $condition .= "and pl.createdate >=  '$startdate' and pl.createdate <=  '$enddate' "; }		    
		$query = " SELECT pl.createdate,round(sum(t.fare)) as amount,count(t.fare) as trips FROM `".PASSENGERS_LOG."` as pl join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition $trans_condition group by DATE(pl.`createdate`)";
		
		//echo '<br/><br/><br/>'.$query;

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();	
		//print_r($results);exit;
		return $results;
	}

	public function viewdriver_tracking($trip_id)
	{ 
		$sql = "SELECT active_record FROM driver_location_history WHERE trip_id = '$trip_id'";
		$trip_check =  Db::query(Database::SELECT, $sql)->execute()->as_array();
		if(count($trip_check) > 0){ return $trip_check;  }else{ return 0;} 
	}

	public function close_mysql_connection($instance)
	{
		$db = Database::$instances[$instance];
		//print_r($db);
		$db->disconnect();
	}	
	
	// PHP strtotime compatible strings
  public function dateDiff($time1, $time2, $precision = 6) {
		// If not numeric then convert texts to unix timestamps
		if (!is_int($time1)) {
		  $time1 = strtotime($time1);
		}
		if (!is_int($time2)) {
		  $time2 = strtotime($time2);
		}
	 
		// If time1 is bigger than time2
		// Then swap time1 and time2
		if ($time1 > $time2) {
		  $ttime = $time1;
		  $time1 = $time2;
		  $time2 = $ttime;
		}
	 
		// Set up intervals and diffs arrays
		$intervals = array('year','month','day','hour','minute','second');
		$diffs = array();
	 
		// Loop thru all intervals
		foreach ($intervals as $interval) {
		  // Create temp time from time1 and interval
		  $ttime = strtotime('+1 ' . $interval, $time1);
		  // Set initial values
		  $add = 1;
		  $looped = 0;
		  // Loop until temp time is smaller than time2
		  while ($time2 >= $ttime) {
			// Create new temp time from time1 and interval
			$add++;
			$ttime = strtotime("+" . $add . " " . $interval, $time1);
			$looped++;
		  }
	 
		  $time1 = strtotime("+" . $looped . " " . $interval, $time1);
		  $diffs[$interval] = $looped;
		}
		
		$count = 0;
		$times = array();
		// Loop thru all diffs
		foreach ($diffs as $interval => $value) {
		  // Break if we have needed precission
		  if ($count >= $precision) {
	 break;
		  }
		  // Add value and interval 
		  // if value is bigger than 0
		  if ($value > 0) {
	 // Add s if value is not 1
	 if ($value != 1) {
	   $interval .= "s";
	 }
	 // Add value and interval to times array
	 $times[] = $value . " " . $interval;
	 $count++;
		  }
		}
	 
		// Return string with times
		return implode(", ", $times);
	  }

	  public function braintree_transaction_details($keyword="",$start_date="",$end_date="",$company_id="",$filter_company="",$val="",$offset="")
	  {
		  $limit_condition="";
			//if($val != '' && $offset != '')
			//{
			$limit_condition="limit $val offset $offset ";
			//}
			$condition="";
			if($keyword != '')
			{
				$condition.="AND (t.passengers_log_id = '$keyword'";
				$condition.="OR  t.transaction_id = '$keyword')";
			}
			if($start_date !='')
			{
				$condition.="AND  pl.createdate >= '$start_date'";
			}
			if($end_date !='')
			{
				$condition.="AND  pl.createdate <= '$end_date'";
			}
			if($company_id !='')
			{
				$condition.="AND  pl.company_id = '$company_id'";
			}
			if($filter_company !='' && $filter_company !='All')
			{
				$condition.="AND  pl.company_id = '$filter_company'";
			}
		$query = " SELECT c.company_name,t.transaction_id,t.payment_status,t.amt,t.passengers_log_id as trip_id,pl.createdate ,pl.company_id FROM `".PASSENGERS_LOG."` as pl join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id where t.payment_type in(2,3) and t.payment_gateway_id='2' $condition order by pl.passengers_log_id desc $limit_condition ";
		//echo $query;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();	
		return $results;
	  }
	  public function total_braintree_transaction_details($keyword="",$start_date="",$end_date="",$company_id="",$filter_company="")
	  {
		$limit_condition="";
		$condition="";
		if($keyword != '')
		{
			$condition.="AND (t.passengers_log_id = '$keyword'";
			$condition.="OR  t.transaction_id = '$keyword')";
		}
		if($start_date !='')
		{
			$condition.="AND  pl.createdate >= '$start_date'";
		}
		if($end_date !='')
		{
			$condition.="AND  pl.createdate <= '$end_date'";
		}
		if($company_id !='')
		{
			$condition.="AND  pl.company_id = '$company_id'";
		}
		if($filter_company !='' && $filter_company !='All')
		{
			$condition.="AND  pl.company_id = '$filter_company'";
		}
		$query = " SELECT count(*) as count FROM `".PASSENGERS_LOG."` as pl join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id where t.payment_type in(2,3) and t.payment_gateway_id='2' $condition order by pl.passengers_log_id desc $limit_condition ";
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $results[0]["count"];
	}

	  public function update_settlement_status($transaction_array=array(),$company_id="")
	  {
			$api_model = Model::factory('commonmodel');
			/*if($company_id > 0)
			{ 
				//$paypal_details = $api_model->company_paypal_details($values['company_id']);		
				//$company_addr_details = $api_model->company_addr_details($values['company_id']);
				//$city=$api_model->company_get_city_name($company_addr_details[0]['login_city']);
				$paypal_details = $api_model->company_payment_details($company_id);
				$payment_gateway_username = isset($paypal_details[0]['payment_gateway_username'])?$paypal_details[0]['payment_gateway_username']:"";
				$payment_gateway_password = isset($paypal_details[0]['payment_gateway_password'])?$paypal_details[0]['payment_gateway_password']:"";
				$payment_gateway_key = isset($paypal_details[0]['payment_gateway_key'])?$paypal_details[0]['payment_gateway_key']:"";
				$currency_format = isset($paypal_details[0]['gateway_currency_format'])?$paypal_details[0]['gateway_currency_format']:"";
				$payment_method = isset($paypal_details[0]['payment_method'])?$paypal_details[0]['payment_method']:"";
				$payment_types=isset($paypal_details[0]['payment_type'])?$paypal_details[0]['payment_type']:"";
				$street=COMPANY_STREET_ADDR;
				$city=COMPANY_LOGIN_CITY_NAME;
				$state=COMPANY_LOGIN_STATE_NAME;
				$country_code=COMPANY_LOGIN_ISO_COUNTRYCODE;
				//$country_dets=$api_model->company_get_country_code($company_addr_details[0]['login_country']);
			//	print_r($country_dets);exit;
				$currency_code=COMPANY_CURRENCY_FORMAT;
				//$country_code=isset($country_dets[0]['iso_country_code'])?$country_dets[0]['iso_country_code']:"";
				$zipcode=COMPANY_ZIPCODE;
			}
			else
			{ */
				//$paypal_details = $api_model->paypal_details(); 
				$paypal_details = $api_model->payment_gateway_details();	
				$payment_gateway_username = isset($paypal_details[0]['payment_gateway_username'])?$paypal_details[0]['payment_gateway_username']:"";
				$payment_gateway_password = isset($paypal_details[0]['payment_gateway_password'])?$paypal_details[0]['payment_gateway_password']:"";
				$payment_gateway_key = isset($paypal_details[0]['payment_gateway_key'])?$paypal_details[0]['payment_gateway_key']:"";
				$currency_format = isset($paypal_details[0]['gateway_currency_format'])?$paypal_details[0]['gateway_currency_format']:"";
				$payment_method = isset($paypal_details[0]['payment_method'])?$paypal_details[0]['payment_method']:"";
				$payment_types=isset($paypal_details[0]['payment_type'])?$paypal_details[0]['payment_type']:"";
			//}
			/** Brain Tree payment gateway **/
			$product_title = Html::chars('Complete Trip');
			$payment_action='sale';

			require_once(APPPATH.'vendor/braintree-payment/lib/Braintree.php');
			$pay_type=($payment_method =="L")?"live":"sandbox";
			if ($pay_type=="live") {
			Braintree_Configuration::environment('production');
			} else {
			Braintree_Configuration::environment('sandbox');			
			}

			Braintree_Configuration::merchantId($payment_gateway_username);//your_merchant_id
			Braintree_Configuration::publicKey($payment_gateway_password);//your_public_key
			Braintree_Configuration::privateKey($payment_gateway_key);//your_private_key
			foreach($transaction_array as $key => $val)
			{
				$trans=explode(":",$val);
				$transaction_id=$trans[0];
				$trip_id=$trans[1];
				$transaction = Braintree_Transaction::find($transaction_id);
				//echo json_encode($transaction);echo "<br/>";
				if(isset($transaction->_attributes))
				{
					$result=$transaction->_attributes;
					$api_model->update(TRANS,array('payment_status'=>str_replace('_',' ',$result['status'])),'passengers_log_id',$trip_id);
				}
			}
			//exit;
		}
		
		public function getaddress($lat,$lng)
		{ 
			$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false&key='.GOOGLE_GEO_API_KEY;
				
			$json = @file_get_contents($url);
			//echo "<pre>"; print_r($json); exit;
			$data=json_decode($json);

			if(!empty($data)){
				$status = $data->status;
				if($status=="OK")
					return $data->results[0]->formatted_address;
				else
					return false;
			} else {
				return false;
			}
			
		}
		
	public function update_table($table,$arr,$cond1,$cond2)
	{
		$result=DB::update($table)->set($arr)->where($cond1,"=",$cond2)->execute();
		return $result;
	}
	
	/************* Get Driver & Manager List ******************/
	public function getdrivermanagerlist($company_id,$manager_id)
	{
		$usertype = $_SESSION['user_type'];
		$country_id = $_SESSION['country_id'];
		$state_id = $_SESSION['state_id'];
		$city_id = $_SESSION['city_id'];
		if(($manager_id !="") && ($manager_id !="All")) {
			$manager_details = $this->manager_details($manager_id);
			$country_id = $manager_details[0]['login_country'];
			$state_id = $manager_details[0]['login_state'];
			$city_id = $manager_details[0]['login_city'];
			$company_id = $manager_details[0]['company_id'];
		}
		$joins = "";
		$condition = "WHERE user_type IN('D','M') ";
		if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
		{
			$joins="LEFT JOIN country ON (".PEOPLE.".login_country = country.country_id) LEFT JOIN state ON (".PEOPLE.".login_state = state.state_id) LEFT JOIN `city` ON (".PEOPLE.".login_city = city.city_id) ";
			$condition .= "and login_state = '".$state_id."' AND login_city = '".$city_id."' AND state.state_status = 'A' and city.city_status = 'A'";
		}
		if(($company_id !="") && ($company_id !="All")) 
		{
			$condition .= " AND company_id = '".$company_id."'";
		}

		$query = "SELECT ".PEOPLE.".id,".PEOPLE.".name,".PEOPLE.".lastname,".PEOPLE.".user_type FROM ".PEOPLE." $joins $condition ORDER BY id DESC";
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		$all_array = $driver_list = $manager_list = array();
		if(count($results) > 0) {
			foreach($results as $key => $p) {
				if($p["user_type"] == "D") {
					$driver_list[$key] = array("id" => $p['id'],"name" => $p["name"],"lastname" => $p['lastname'],"user_type" => $p["user_type"]);
				}
				if($p["user_type"] == "M") {
					$manager_list[$key] = array("id" => $p['id'],"name" => $p["name"],"lastname" => $p['lastname'],"user_type" => $p["user_type"]);
				}
			}
		}
		$all_array['driver_list'] = $driver_list;
		$all_array['manager_list'] = $manager_list;
		return $all_array;
	}

	public function gettransactionPassenger($company_id = "")
	{
		$joins = $condition = "";
		$usertype = $_SESSION['user_type'];
		$userid = $_SESSION['userid'];
		if($company_id != "") {
			$condition .= " and pl.company_id = '".$company_id."'"; 
		}
		$query = "SELECT p.id,p.name,c.company_name FROM ".PASSENGERS_LOG." as pl join ".PASSENGERS." as p ON pl.passengers_id = p.id join ".COMPANY." as c ON c.cid = pl.company_id where 1=1 $condition group by pl.passengers_id order by p.name asc";
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $results;
	}

}
