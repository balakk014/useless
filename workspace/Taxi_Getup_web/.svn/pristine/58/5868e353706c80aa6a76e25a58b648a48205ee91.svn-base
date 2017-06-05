<?php defined('SYSPATH') OR die('No Direct Script Access');

class Model_Tdispatch extends Model
{
	public function __construct()
	{	
		$this->session = Session::instance();	
		$this->username = $this->session->get("username");
		$this->admin_username = $this->session->get("username");
		$this->admin_userid = $this->session->get("id");
		$this->admin_email = $this->session->get("email");
		$this->user_admin_type = $this->session->get("user_type");
		$this->currentdate=Commonfunction::getCurrentTimeStamp();

	}


	public function count_bookings_list($list,$company,$manager_id,$taxiid,$driverid,$passengerid,$startdate,$enddate,$transaction_id,$payment_type)
	{		
			//echo $driverid;exit;
			$usertype = $_SESSION['user_type'];

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
			//echo 'as'.$driver_ids;exit;
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
			$trans_condition = " and t.transaction_id like '%".$transaction_id."%' ";
		}
		else
		{
			$trans_condition ='';
		}	

			if($list =='all')
			{
				$condition = " ";//pl.driver_reply = 'A' ";
			}
			else if($list =='success')
			{
				$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";
			}
			else if($list =='cancelled')
			{
				$condition = "WHERE ((pl.travel_status = '4' and pl.driver_reply = 'A') or (pl.travel_status = '0' and pl.driver_reply = 'C'))";
			}
			else if($list =='rejected')
			{
				$condition = "WHERE pl.driver_reply = 'R'";
			}

			if($payment_type !='All' && $payment_type !='')
			{
				$condition .= " and payment_type = '$payment_type' ";
			}

		    if(($company !="") && ($company !="All")) { $condition .= " and pl.company_id =  '$company'"; }
	
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
			if(($driverid !="All")) 
			{ 			
				$condition .= " and pl.driver_id =  '$driverid'"; 
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
		}    			
		  
		    if($startdate !="") { $condition .= " and pl.createdate >=  '$startdate' and pl.createdate <=  '$enddate' "; }
		
			if($list =='rejected')
			{
		   		$query = " SELECT * , pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".RECURR_BOOKING."` as rb left join`".PASSENGERS_LOG."` as pl ON rb.passengers_log_id=pl.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition order by pl.passengers_log_id desc";
			}
			else
			{		   		
		   		$query = " SELECT * , pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".RECURR_BOOKING."` as rb left join`".PASSENGERS_LOG."` as pl ON rb.passengers_log_id=pl.passengers_log_id join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition $trans_condition order by pl.passengers_log_id desc";
			}

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return count($results);
	   
	}	
   	
   	public function bookings_details($list,$company,$manager_id,$taxiid,$driverid,$passengerid,$startdate,$enddate,$offset='',$val='',$transaction_id,$payment_type)
   	{

		//$totalfare = "select sum(fare) from `transacation`";
		$usertype = $_SESSION['user_type'];
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

			
			
			if($list =='all')
			{
				$condition = " ";//pl.driver_reply = 'A' ";
			}
			else if($list =='success')
			{
				$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";
			}
			else if($list =='cancelled')
			{
				$condition = "WHERE ((pl.travel_status = '4' and pl.driver_reply = 'A') or (pl.travel_status = '0' and pl.driver_reply = 'C'))";
			}
			else if($list =='rejected')
			{
				$condition = "WHERE pl.driver_reply = 'R'";
			}

			if($payment_type !='All' && $payment_type !='' )
			{
				$condition .= " and payment_type = '$payment_type'";
			}

		if(($company !="") && ($company !="All")) { $condition .= " and pl.company_id =  '$company'"; }
		
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
					$condition .= " AND pl.taxi_id IN ( $taxi_ids )";
				}
			}
			else
			{
				$condition .= "";
			}
		}
	    if(($driverid !="All")) 
	    { 
			$condition .= " and pl.driver_id  =  '$driverid'"; 
		}
		else
		{ 
			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
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
		}    
		if($startdate !="") { $condition .= " and pl.createdate >=  '$startdate' and pl.createdate <=  '$enddate' "; }	

			if($list =='rejected')
			{
			   		$query = " SELECT * , pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".RECURR_BOOKING."` as rb left join`".PASSENGERS_LOG."` as pl ON rb.passengers_log_id=pl.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition order by pl.passengers_log_id desc  limit $val offset $offset";
			   		//echo 'as';
			}
			else
			{		    
				$query = " SELECT * ,pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".RECURR_BOOKING."` as rb left join`".PASSENGERS_LOG."` as pl ON rb.passengers_log_id=pl.passengers_log_id join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition $trans_condition order by pl.passengers_log_id desc limit $val offset $offset";
			}

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;   	     
   	}	


	public function getpassengerdetails($company_id,$manager_id)
	{
		$usertype = $_SESSION['user_type'];	
		
		if(($manager_id !="") && ($manager_id !="All")) { 
			$manager_details = $this->manager_details($manager_id);
			$country_id = $manager_details[0]['login_country'];
			$state_id = $manager_details[0]['login_state'];
			$city_id = $manager_details[0]['login_city'];
			$company_id = $manager_details[0]['company_id'];
		}
		else
		{
			$country_id = $_SESSION['country_id'];
		   	$state_id = $_SESSION['state_id'];
		   	$city_id = $_SESSION['city_id'];		
		}

	   	
		$joins="";
		$condition = " ";
		if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
	   	{
			//$joins="LEFT JOIN `country` ON (`".PEOPLE."`.`login_country` = `country`.`country_id`) LEFT JOIN `state` ON (`".PEOPLE."`.`login_state` = `state`.`state_id`) LEFT JOIN `city` ON (`".PEOPLE."`.`login_city` = `city`.`city_id`) ";
			//$condition .= "and `login_state` = '".$state_id."' AND `login_city` = '".$city_id."' AND `state`.`state_status` = 'A' and `city`.`city_status` = 'A'";
		}
		 if(($company_id !="") && ($company_id !="All")) 
		 { 	
		 	$condition .= "WHERE ".PASSENGERS.".passenger_cid = '".$company_id."'"; 
		 }

		$query = "SELECT ".PASSENGERS.".id,".PASSENGERS.".name,".COMPANY.".company_name FROM ".PASSENGERS." JOIN  ".COMPANY." ON (  ".PASSENGERS.".`passenger_cid` =  ".COMPANY.".`cid` ) $condition  ORDER BY `name` DESC";
		
		//$query = "select * from ".PASSENGERS." ORDER BY  `passengers`.`name` ASC ";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;	

		}

   	/************* Get Driver List ******************/
   	public function getdriverdetails($company_id,$manager_id)
	{

		$usertype = $_SESSION['user_type'];	
		
		if(($manager_id !="") && ($manager_id !="All")) { 
			$manager_details = $this->manager_details($manager_id);
			$country_id = $manager_details[0]['login_country'];
			$state_id = $manager_details[0]['login_state'];
			$city_id = $manager_details[0]['login_city'];
			$company_id = $manager_details[0]['company_id'];
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
			$joins="LEFT JOIN `country` ON (`".PEOPLE."`.`login_country` = `country`.`country_id`) LEFT JOIN `state` ON (`".PEOPLE."`.`login_state` = `state`.`state_id`) LEFT JOIN `city` ON (`".PEOPLE."`.`login_city` = `city`.`city_id`) ";
			$condition .= "and `login_state` = '".$state_id."' AND `login_city` = '".$city_id."' AND `state`.`state_status` = 'A' and `city`.`city_status` = 'A'";
		}
		 if(($company_id !="") && ($company_id !="All")) 
		 { 	
		 	$condition .= "AND `company_id` = '".$company_id."'"; 
		 }

		$query = "SELECT * FROM ".PEOPLE." $joins $condition  ORDER BY `id` DESC";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;
		
	}

   	/************* Get Taxi List ******************/
   	public function gettaxidetails($company_id,$manager_id)
	{
		$usertype = $_SESSION['user_type'];	
	
		if(($manager_id !="") && ($manager_id !="All") || ($usertype == 'M')) { 
			if($usertype =='M')
			{
				$manager_id = $_SESSION['userid'];
			}
			$manager_details = $this->manager_details($manager_id);
			$country_id = $manager_details[0]['login_country'];
			$state_id = $manager_details[0]['login_state'];
			$city_id = $manager_details[0]['login_city'];
			$company_id = $manager_details[0]['company_id'];
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
			$joins="LEFT JOIN `country` ON (`taxi`.`taxi_country` = `country`.`country_id`) LEFT JOIN `state` ON (`taxi`.`taxi_state` = `state`.`state_id`) LEFT JOIN `city` ON (`taxi`.`taxi_city` = `city`.`city_id`) ";
			$condition .= "where `taxi_state` = '".$state_id."' AND `taxi_city` = '".$city_id."' AND `state`.`state_status` = 'A' and `city`.`city_status` = 'A'";
			if(($company_id !="") && ($company_id !="All")) { $condition .= "AND `taxi_company` = '".$company_id."'"; }
		}
		else
		{
			if(($company_id !="") && ($company_id !="All")) { $condition .= "Where `taxi_company` = '".$company_id."'"; }
		}

		$query = "SELECT * FROM ".TAXI." $joins $condition ORDER BY `taxi_id` DESC";
		//echo $query;exit;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;
	}
	
	public static function getgroup_details($like,$accountid)	
	{
		$query = "SELECT * FROM ".TBLGROUP." where department LIKE '%$like%' and aid='$accountid' ORDER BY `department` ASC";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return $results;
	}	

	public static function getuser_details($like,$type)	
	{
		$company_id = $_SESSION['company_id'];
		if($type == 1)
		{
			$split_value = explode('-',urldecode($like));	
			if(count($split_value) > 1)
			{
				$phone_split = substr(trim($split_value[1]),0,-1);
				$phone_no = substr(trim($phone_split),1);
				$phone_no = trim($phone_no);	
				$query = "SELECT * FROM ".PASSENGERS." where phone LIKE '%$phone_no%' and passenger_cid='$company_id' and user_status ='A'  ORDER BY `name` ASC";
			}
			else
			{
				$query = "SELECT * FROM ".PASSENGERS." where name LIKE '%$like%' and passenger_cid='$company_id' and user_status ='A'  ORDER BY `name` ASC";
			}	

		}
		elseif($type == 2)
		{
			$query = "SELECT * FROM ".PASSENGERS." where email LIKE '%$like%' and passenger_cid='$company_id' and user_status ='A'  ORDER BY `name` ASC";
		}	
		elseif($type == 3)
		{
			$query = "SELECT * FROM ".PASSENGERS." where phone LIKE '%$like%' and passenger_cid='$company_id' and user_status ='A'  ORDER BY `name` ASC";
		}	

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;
	}



	public static function getpassenger_Detailinfo($data)
	{
		$company_id = $_SESSION['company_id'];
		if($data['field_name'] == 'firstname')
		{
			
			$split_value = explode('-',$data['field_value']);	

			$phone_split = substr(trim($split_value[1]),0,-1);
			$phone_no = substr(trim($phone_split),1);
			$phone_no = trim($phone_no);	

			$query = "SELECT * FROM ".PASSENGERS." where phone ='".$phone_no."' and passenger_cid='$company_id' and user_status='A' limit 0,1";
		}
		elseif($data['field_name'] == 'email')
		{
			$query = "SELECT * FROM ".PASSENGERS." where email ='".$data['field_value']."' and passenger_cid='$company_id'  and user_status='A' limit 0,1";
		}
		elseif($data['field_name'] == 'phone')
		{
			$query = "SELECT * FROM ".PASSENGERS." where phone ='".$data['field_value']."' and passenger_cid='$company_id'  and user_status='A' limit 0,1";
		}
		
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;
	}

	public static function getpassenger_Detailinfo_new($data)
	{
		$company_id = $_SESSION['company_id'];
		//print_r($data);exit;
		if($data['field_value'] == 'firstname')
		{
			$split_value = explode('-',$data['field_name']);	

			$phone_split = substr(trim($split_value[1]),0,-1);
			$phone_no = substr(trim($phone_split),1);
			$phone_no = trim($phone_no);	

			$query = "SELECT * FROM ".PASSENGERS." where phone ='".$phone_no."' and passenger_cid='$company_id' and user_status='A' limit 0,1";
		}
		/*elseif($data['field_name'] == 'email')
		{
			$query = "SELECT * FROM ".PASSENGERS." where email ='".$data['field_value']."' and passenger_cid='$company_id'  and user_status='A' limit 0,1";
		}
		elseif($data['field_name'] == 'phone')
		{
			$query = "SELECT * FROM ".PASSENGERS." where phone ='".$data['field_value']."' and passenger_cid='$company_id'  and user_status='A' limit 0,1";
		}
		*/
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;
	}

	public static function get_modelminfare($model_id){

		if(FARE_SETTINGS == 2)
		{
			$query = "SELECT * FROM ".COMPANY_MODEL_FARE." where model_id ='$model_id' limit 0,1";
			$results = Db::query(Database::SELECT, $query)->execute()->as_array();				
		}
		else
		{
			$query = "SELECT * FROM ".MOTORMODEL." where model_id ='$model_id' limit 0,1";
			$results = Db::query(Database::SELECT, $query)->execute()->as_array();				
		}


		return $results;

	}

	public function validate_dispatchbooking($arr) 
	{

		return Validation::factory($arr)       
		
			->rule('firstname', 'not_empty')
			->rule('firstname', 'min_length', array(':value', '3'))
			//->rule('firstname', 'max_length', array(':value', '32'))

			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))

			->rule('phone', 'not_empty')

			->rule('current_location', 'not_empty')
			->rule('pickup_lat', 'not_empty')
			->rule('pickup_lng', 'not_empty')

			/*->rule('drop_location', 'not_empty')
			->rule('drop_lat', 'not_empty')
			->rule('drop_lng', 'not_empty')*/

			//->rule('luggage', 'numeric')
			//->rule('no_passengers', 'numeric')

			->rule('pickup_date', 'not_empty')
			->rule('pickup_time', 'not_empty');
	}

	public function addbooking($post,$random_key,$password,$company_tax)
	{	

			//print_r($post);
			$firstname = Html::chars($post['firstname']);
			$pass_logid = '';
			$recurrent_id = '';

			$send_mail = 'N';	
			$insert_booking = 'N';

			$passenger_id = $post['passenger_id'];
			$company_id = $_SESSION['company_id'];
			$common_model = Model::factory('commonmodel');
			$api = Model::factory('api');
			$search_city = $post['cityname'];
			$booking_key = $common_model->get_randonkey();
			if($search_city!='')
			{
				$cityid_query = "select city_id from ".CITY." where ".CITY.".city_name like '%".$search_city."%' limit 0,1"; 
			}		
			else
			{
				$cityid_query = "select city_id from ".CITY." where ".CITY.".default=1"; 
			}

			$cityid_fetch = Db::query(Database::SELECT, $cityid_query)
					->execute()
					->as_array();

			if(count($cityid_fetch) == 0)
			{
				$cityid_query = "select city_id from ".CITY." where ".CITY.".default=1"; 
				$cityid_fetch = Db::query(Database::SELECT, $cityid_query)
						->execute()
						->as_array();
			}

			$city_id = $cityid_fetch[0]['city_id'];

			$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);
			$current_datesplit = explode(' ',$current_datetime);
			$pickup_date = $post['pickup_date'];
			$pickup_time = $post['pickup_time'];			
			if(!empty($post['addition_fields']))
			{
				$additional_fields = serialize($post['addition_fields']);
			}
			else
			{
				$post['addition_fields'] = '';
				$additional_fields = '';
			}
			if($pickup_date =='' || $pickup_date =='Date')
			{
				$pickup_date = $current_datesplit[0];
			}

			if($pickup_time =='' || $pickup_time =='Now')
			{
				$pickup_time = $current_datesplit[1];
			}
			
			$pickup_datetime = $pickup_date.' '.$pickup_time;
			

			$userid = $_SESSION['userid'];

			if(isset($post['dispatch']))
			{
				$booktype = '1';
			}
			else
			{
				$booktype = '2';
			}

			if(isset($post['group_id']))
			{
				$group_id = $post['group_id'];
				$company_dispatch = DB::select()->from(TBLGROUP)
					->where('gid','=',$group_id)
					->execute()
					->as_array();

				if(count($company_dispatch) > 0)
				{
					$account_id = $company_dispatch[0]['aid'];
				}
				else
				{
					$account_id = '0';
				}
			}

			if($post['passenger_id'] =='')
			{

			$insert_passenger = DB::insert(PASSENGERS, array('name','email','phone','password','org_password','created_date','activation_key','user_status','passenger_cid','activation_status'))
			->values(array($firstname,$post['email'],$post['phone'],md5($password),$password,$current_datetime,$random_key,ACTIVE,$company_id,1))
			->execute();

			$send_mail = 'S';			
		 	$passenger_id = $insert_passenger[0];

			$user_createdby = $_SESSION['userid'];
			$company_id = $_SESSION['company_id'];

				/*if(!isset($post['group_id'])) {

				$check_accountexist = DB::select('account_name')->from(TBLGROUPACCOUNT)->where('account_name','=',$firstname)->where('acc_created_by','=',$user_createdby)->execute()->as_array();	
			
				if(count($check_accountexist) == 0)
				{
					/** Insert Account **
					$insert_account = DB::insert(TBLGROUPACCOUNT, array('acc_created_by','acc_company_id','account_name','passid'))->values(array($user_createdby,$company_id,$firstname,$passenger_id))->execute();
					$account_id = $insert_account['0'];
					/** Insert Account **
				}
				else
				{
					/** Insert Account **
					$insert_account = DB::insert(TBLGROUPACCOUNT, array('acc_created_by','acc_company_id','account_name','passid'))->values(array($user_createdby,$company_id,$firstname.'_'.$passenger_id,$passenger_id))->execute();
					$account_id = $insert_account['0'];
					/** Insert Account **

				}	


				$check_groupexist = DB::select('department')->from(TBLGROUP)->where('department','=',$firstname)->where('gcompany_id','=',$company_id)->execute()->as_array();	
			
				if(count($check_groupexist) == 0)
				{
					/** Insert Group **
					$insert_group = DB::insert(TBLGROUP, array('aid','gcompany_id','passenger_id','department'))->values(array($insert_account[0],$company_id,$passenger_id,$firstname))->execute();
					$group_id = $insert_group['0'];
					/** Insert Group **
				}
				else
				{
					/** Insert Group **
					$insert_group = DB::insert(TBLGROUP, array('aid','gcompany_id','passenger_id','department'))->values(array($insert_account[0],$company_id,$passenger_id,$firstname.'_'.$passenger_id))->execute();
					$group_id = $insert_group['0'];
					/** Insert Group **

				}
	
				} */

			}
			/** if single booking **/
			if($post['recurrent'] == 1)
			{
				$account_id = isset($account_id)?$account_id:'0';
				$group_id = isset($group_id)?$group_id:'0';


			$today_result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude',
'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','search_city','notes_driver','faretype','fixedprice','bookingtype','luggage','bookby','operator_id'
,'additional_fields','travel_status','taxi_modelid','recurrent_type','company_tax','account_id','accgroup_id'))
			->values(array($booking_key,$passenger_id,$company_id,$post['current_location'],$post['pickup_lat'],$post['pickup_lng'],$post['drop_location'],$post['drop_lat'],$post['drop_lng'],$pickup_datetime,$post['no_passengers'],$post['distance_km'],$post['total_duration'],$post['total_fare'],$city_id,$post['notes'],$post['payment_type'],$post['fixedprice'],$booktype,$post['luggage'],'2',$userid,$additional_fields,'0',$post['taxi_model'],$post['recurrent'],$post['company_tax'],$account_id,$group_id))
			->execute();

				$pass_logid = $today_result[0];
				
				$trip_id = $pass_logid;

				$insert_booking = 'S';

			}
							
			/** if single booking end**/

			/** if recurrent booking **/
			if($post['recurrent'] == 2)
			{
				
				// Next 24 hours //
				$date_query = "SELECT '$current_datetime'+interval 24 hour as next_datetime,'$current_datetime' as today_datetime";
				$date_result = Db::query(Database::SELECT, $date_query)->execute();
				$next_datetime = $date_result[0]['next_datetime'];
				$today_datetime = $date_result[0]['today_datetime'];
				$next_datetime_split = explode(' ',$next_datetime);
				$next_date = $next_datetime_split[0];
				$next_time = $next_datetime_split[1];
				$today_datetime_split = explode(' ',$today_datetime);
				$today_date = $today_datetime_split[0];
				$today_time = $today_datetime_split[1];
				// Next 24 hours //

				
				// Next Days //
				$day_query = "select DATE_FORMAT('$current_datetime'+interval 24 hour,'%a') as next_day, DATE_FORMAT('$current_datetime','%a') as today_day";
				$day_result = Db::query(Database::SELECT, $day_query)->execute();
				$next_day = strtoupper($day_result[0]['next_day']);
				$today_day = strtoupper($day_result[0]['today_day']);
				// Next Days //

				$insert_booking = 'N';
				// Insert Recurrent Table //

				if(isset($post['daysofweek'])) { 
					$days = serialize($post['daysofweek']);
				}
				else
				{
					$days = '';
				}


				if(isset($post['all_dates']) && ($post['all_dates'] !='')) {
 
					$specific_dates = explode(',',$post['all_dates']);
					$specific_dates = !empty($specific_dates)?serialize($specific_dates):'';
				}
				else
				{
					$specific_dates = '';
				}


				$exclude_dates = '';

				$account_id = isset($account_id)?$account_id:'0';
				$group_id = isset($group_id)?$group_id:'0';

				$insert_recurr = DB::insert(RECURR_BOOKING, array('labelname','frmdate','todate','days','excludedates','specific_dates','companyid','recurrent_passengerid','recurrent_pickuplocation',
'recurrent_pickuplatitude','recurrent_pickuplongitude','recurrent_droplocation','recurrent_droplatitude','recurrent_droplongitude',
'recurrent_noofpassengers','recurrent_approxdistance','recurrent_approxduration','recurrent_approxfare','recurrent_pickuptime','recurrent_city',
'recurrent_fixedprice','recurrent_faretype','recurrent_luggage','recurrent_operatorid','recurrent_additionalfields','recurrent_modelid',
'recurrent_accountid','recurrent_groupid','recurrent_notes_driver'))->values(array($post['labelname'],$post['frmdate'],$post['todate'],$days,$exclude_dates,$specific_dates,$company_id,$passenger_id,$post['current_location'],$post['pickup_lat'],$post['pickup_lng'],$post['drop_location'],
$post['drop_lat'],$post['drop_lng'],$post['no_passengers'],$post['distance_km'],$post['total_duration'],$post['total_fare'],$pickup_time,$city_id,$post['fixedprice'],$post['payment_type'],$post['luggage'],$userid,$additional_fields,$post['taxi_model'],$account_id,$group_id,$post['notes']))->execute();


		$recurrent_id = $insert_recurr[0];
		// Insert Recurrent Table //


		function toDate($x){return date('Y-m-d', $x);}


		// Get all recurrent booking details query //
		
		$sql = "SELECT  ".RECURR_BOOKING.".reid,".RECURR_BOOKING.".passengers_log_id,".RECURR_BOOKING.".frmdate,".RECURR_BOOKING.".todate,".RECURR_BOOKING.".days,
".RECURR_BOOKING.".excludedates,".RECURR_BOOKING.".specific_dates  FROM ".RECURR_BOOKING." left join ".COMPANY." ON ( ".RECURR_BOOKING.".companyid = ".COMPANY.".cid ) left join  ".PEOPLE." ON ( ".PEOPLE.".company_id = ".RECURR_BOOKING.".companyid ) WHERE ".PEOPLE.".status = 'A' and ".PEOPLE.".user_type='C' and ".COMPANY.".company_status='A' and ".RECURR_BOOKING.".frmdate <= '$current_datetime' and ".RECURR_BOOKING.".todate >= '$current_datetime' and ".RECURR_BOOKING.".reid='$recurrent_id' ";


		$recurrent_result = Db::query(Database::SELECT, $sql)->execute()->as_array();

		// Get all recurrent booking details query //


		if(count($recurrent_result) > 0)
		{
			foreach($recurrent_result as $recurrent_details1)
			{
				
				$recurrent_id = $recurrent_details1['reid'];
				$startDate = $recurrent_details1['frmdate'];
				$endDate = $recurrent_details1['todate'];

				$insert_booking = 'N';

				// To get Passenger Log details from Passenger Log Table //
				$recurrent_query = "SELECT * from ".RECURR_BOOKING." where ".RECURR_BOOKING.".reid ='$recurrent_id'"; 
				$recurrent_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();			
				// To get Passenger Log details from Passenger Log Table //

				$all_dates = $post['all_dates'];
				if(isset($post['daysofweek'])) { 
					$days = serialize($post['daysofweek']);
				}
				else
				{
					$days = '';
				}
				/** Insert booking based on fromdate and todate **/

				//echo $all_dates.'all dates and days'.$days;
				//exit;
				if($all_dates =='' && $days == '')
				{

					/** Check Today is between fromdate and todate **/
					$checktoday_query ="select '$today_date'  between '$startDate' and '$endDate' as checkdate";
					$checktoday_result = Db::query(Database::SELECT, $checktoday_query)->execute()->as_array();
					/** Check Today is between fromdate and todate **/

					/** Check Tommorrow is between fromdate and todate **/
					$checktomorrow_query ="select '$next_date'  between '$startDate' and '$endDate' as checkdate";
					$checktomorrow_result = Db::query(Database::SELECT, $checktomorrow_query)->execute()->as_array();
					/** Check Tommorrow is between fromdate and todate **/


					/** Insert Today Booking **/
					if($checktoday_result[0]['checkdate'] == 1)
					{	
						// To get Recurrent Log details from Passenger Log Table //
						$recurrent_query = "SELECT * from ".RECURR_BOOKING." where ".RECURR_BOOKING.".reid ='$recurrent_id'"; 				

						$recurrent_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();			
						// To get Recurrent Log details from Passenger Log Table //

						// Insert into Passenger Log Table //	

						$pickup_datetime = $today_date.' '.$recurrent_details[0]['recurrent_pickuptime'];

						$insert_booking = 'S';

						$today_result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude',
						'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','search_city','faretype','fixedprice','luggage','bookby',
						'operator_id','additional_fields','travel_status','taxi_modelid','recurrent_type','recurrent_id','company_tax','account_id','accgroup_id'))->values(array($booking_key,$recurrent_details[0]['recurrent_passengerid'],$recurrent_details[0]['companyid'],$recurrent_details[0]['recurrent_pickuplocation'],$recurrent_details[0]['recurrent_pickuplatitude'],$recurrent_details[0]['recurrent_pickuplongitude'],$recurrent_details[0]['recurrent_droplocation'],$recurrent_details[0]['recurrent_droplatitude'],$recurrent_details[0]['recurrent_droplongitude'],$pickup_datetime,$recurrent_details[0]['recurrent_noofpassengers'],$recurrent_details[0]['recurrent_approxdistance'],$recurrent_details[0]['recurrent_approxduration'],$recurrent_details[0]['recurrent_approxfare'],$recurrent_details[0]['recurrent_city'],$recurrent_details[0]['recurrent_faretype'],$recurrent_details[0]['recurrent_fixedprice'],$recurrent_details[0]['recurrent_luggage'],'2',$recurrent_details[0]['recurrent_operatorid'],$recurrent_details[0]['recurrent_additionalfields'],'0',$recurrent_details[0]['recurrent_modelid'],'2',$recurrent_id,$company_tax,$recurrent_details[0]['recurrent_accountid'],$recurrent_details[0]['recurrent_groupid']))->execute();	


						$ins_logid = $today_result[0];
						
						$trip_id = $ins_logid;

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


					/** Insert Tomorrow Booking **/
					if($checktomorrow_result[0]['checkdate'] == 1)
					{
						// To get Recurrent Log details from Passenger Log Table //
						$recurrent_query = "SELECT * from ".RECURR_BOOKING." where ".RECURR_BOOKING.".reid ='$recurrent_id'"; 				

						$recurrent_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();			
						// To get Recurrent Log details from Passenger Log Table //

						// Insert into Passenger Log Table //	

						$pickup_datetime = $next_date.' '.$recurrent_details[0]['recurrent_pickuptime'];

						$insert_booking = 'S';

						$nextday_result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude',
						'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','search_city','faretype','fixedprice','luggage','bookby',
						'operator_id','additional_fields','travel_status','taxi_modelid','recurrent_type','recurrent_id','company_tax','account_id','accgroup_id'))->values(array($booking_key,$recurrent_details[0]['recurrent_passengerid'],$recurrent_details[0]['companyid'],$recurrent_details[0]['recurrent_pickuplocation'],$recurrent_details[0]['recurrent_pickuplatitude'],$recurrent_details[0]['recurrent_pickuplongitude'],$recurrent_details[0]['recurrent_droplocation'],$recurrent_details[0]['recurrent_droplatitude'],$recurrent_details[0]['recurrent_droplongitude'],$pickup_datetime,$recurrent_details[0]['recurrent_noofpassengers'],$recurrent_details[0]['recurrent_approxdistance'],$recurrent_details[0]['recurrent_approxduration'],$recurrent_details[0]['recurrent_approxfare'],$recurrent_details[0]['recurrent_city'],$recurrent_details[0]['recurrent_faretype'],$recurrent_details[0]['recurrent_fixedprice'],$recurrent_details[0]['recurrent_luggage'],'2',$recurrent_details[0]['recurrent_operatorid'],$recurrent_details[0]['recurrent_additionalfields'],'0',$recurrent_details[0]['recurrent_modelid'],'2',$recurrent_id,$company_tax,$recurrent_details[0]['recurrent_accountid'],$recurrent_details[0]['recurrent_groupid']))->execute();	


						$ins_logid = $nextday_result[0];
						
						$trip_id = $ins_logid;

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
					/** Insert Tomorrow Booking **/


				/** Insert booking based on fromdate and todate **/
					
				}
				/** daysofweek not empty **/
				else if($all_dates =='' && $days !='')
				{
					
					/** Check Today is between fromdate and todate **/
					$checktoday_query ="select '$today_date'  between '$startDate' and '$endDate' as checkdate";
					$checktoday_result = Db::query(Database::SELECT, $checktoday_query)->execute()->as_array();
					/** Check Today is between fromdate and todate **/

					/** Check Tommorrow is between fromdate and todate **/
					$checktomorrow_query ="select '$next_date'  between '$startDate' and '$endDate' as checkdate";
					$checktomorrow_result = Db::query(Database::SELECT, $checktomorrow_query)->execute()->as_array();
					/** Check Tommorrow is between fromdate and todate **/


					$daysofweek =  unserialize($recurrent_details[0]['days']);		

					if($checktoday_result[0]['checkdate'] == 1)
					{
						/** Insert Booking Today **/
						if(in_array($today_day,$daysofweek))
						{							

							// To get Recurrent Log details from Passenger Log Table //
							$recurrent_query = "SELECT * from ".RECURR_BOOKING." where ".RECURR_BOOKING.".reid ='$recurrent_id'"; 				

							$recurrent_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();			
							// To get Recurrent Log details from Passenger Log Table //

							// Insert into Passenger Log Table //	

							$pickup_datetime = $today_date.' '.$recurrent_details[0]['recurrent_pickuptime'];

							$insert_booking = 'S';

							$today_result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude',
							'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','search_city','faretype','fixedprice','luggage','bookby',
							'operator_id','additional_fields','travel_status','taxi_modelid','recurrent_type','recurrent_id','company_tax','account_id','accgroup_id'))->values(array($booking_key,$recurrent_details[0]['recurrent_passengerid'],$recurrent_details[0]['companyid'],$recurrent_details[0]['recurrent_pickuplocation'],$recurrent_details[0]['recurrent_pickuplatitude'],$recurrent_details[0]['recurrent_pickuplongitude'],$recurrent_details[0]['recurrent_droplocation'],$recurrent_details[0]['recurrent_droplatitude'],$recurrent_details[0]['recurrent_droplongitude'],$pickup_datetime,$recurrent_details[0]['recurrent_noofpassengers'],$recurrent_details[0]['recurrent_approxdistance'],$recurrent_details[0]['recurrent_approxduration'],$recurrent_details[0]['recurrent_approxfare'],$recurrent_details[0]['recurrent_city'],$recurrent_details[0]['recurrent_faretype'],$recurrent_details[0]['recurrent_fixedprice'],$recurrent_details[0]['recurrent_luggage'],'2',$recurrent_details[0]['recurrent_operatorid'],$recurrent_details[0]['recurrent_additionalfields'],'0',$recurrent_details[0]['recurrent_modelid'],'2',$recurrent_id,$company_tax,$recurrent_details[0]['recurrent_accountid'],$recurrent_details[0]['recurrent_groupid']))->execute();	


							$ins_logid = $today_result[0];
							
							$trip_id = $ins_logid;

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
					

					
					/** Insert Booking Tomorrow **/

					if($checktomorrow_result[0]['checkdate'] == 1)
					{
						/** Insert Booking Today **/
						if(in_array($next_day,$daysofweek))
						{

							// To get Recurrent Log details from Passenger Log Table //
							$recurrent_query = "SELECT * from ".RECURR_BOOKING." where ".RECURR_BOOKING.".reid ='$recurrent_id'"; 				

							$recurrent_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();			
							// To get Recurrent Log details from Passenger Log Table //

							// Insert into Passenger Log Table //	

							$pickup_datetime = $next_date.' '.$recurrent_details[0]['recurrent_pickuptime'];

							$insert_booking = 'S';

							$today_result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude',
							'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','search_city','faretype','fixedprice','luggage','bookby',
							'operator_id','additional_fields','travel_status','taxi_modelid','recurrent_type','recurrent_id','company_tax','account_id','accgroup_id'))->values(array($booking_key,$recurrent_details[0]['recurrent_passengerid'],$recurrent_details[0]['companyid'],$recurrent_details[0]['recurrent_pickuplocation'],$recurrent_details[0]['recurrent_pickuplatitude'],$recurrent_details[0]['recurrent_pickuplongitude'],$recurrent_details[0]['recurrent_droplocation'],$recurrent_details[0]['recurrent_droplatitude'],$recurrent_details[0]['recurrent_droplongitude'],$pickup_datetime,$recurrent_details[0]['recurrent_noofpassengers'],$recurrent_details[0]['recurrent_approxdistance'],$recurrent_details[0]['recurrent_approxduration'],$recurrent_details[0]['recurrent_approxfare'],$recurrent_details[0]['recurrent_city'],$recurrent_details[0]['recurrent_faretype'],$recurrent_details[0]['recurrent_fixedprice'],$recurrent_details[0]['recurrent_luggage'],'2',$recurrent_details[0]['recurrent_operatorid'],$recurrent_details[0]['recurrent_additionalfields'],'0',$recurrent_details[0]['recurrent_modelid'],'2',$recurrent_id,$company_tax,$recurrent_details[0]['recurrent_accountid'],$recurrent_details[0]['recurrent_groupid']))->execute();	


							$ins_logid = $today_result[0];
							
							$trip_id = $ins_logid;

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

					/** Insert Booking Tomorrow **/

				}
				/** daysofweek not empty **/
				/** all_dates not empty **/
				else
				{
					/** Check Today is between fromdate and todate **/
					$checktoday_query ="select '$today_date'  between '$startDate' and '$endDate' as checkdate";
					$checktoday_result = Db::query(Database::SELECT, $checktoday_query)->execute()->as_array();
					/** Check Today is between fromdate and todate **/

					/** Check Tommorrow is between fromdate and todate **/
					$checktomorrow_query ="select '$next_date'  between '$startDate' and '$endDate' as checkdate";
					$checktomorrow_result = Db::query(Database::SELECT, $checktomorrow_query)->execute()->as_array();
					/** Check Tommorrow is between fromdate and todate **/

					$specific_dates =  unserialize($recurrent_details[0]['specific_dates']);		
					$specific_dates = implode(',',$specific_dates);

					/** Check Today is exist in specific dates **/
					$checktoday_query2 ="select FIND_IN_SET('$today_date', '$specific_dates' ) as finddate";
					$checktoday_result2 = Db::query(Database::SELECT, $checktoday_query2)->execute()->as_array();
					/** Check Today is exist in specific dates **/

					/** Check Tommorrow is exist in specific dates **/
					$checktomorrow_query2 ="select FIND_IN_SET('$next_date', '$specific_dates' ) as finddate";
					$checktomorrow_result2 = Db::query(Database::SELECT, $checktomorrow_query2)->execute()->as_array();
					/** Check Tommorrow is exist in specific dates **/

					/** Insert Today Booking **/
					if($checktoday_result[0]['checkdate'] == 1 && $checktoday_result2[0]['finddate'] == 1)
					{


						// To get Recurrent Log details from Passenger Log Table //
						$recurrent_query = "SELECT * from ".RECURR_BOOKING." where ".RECURR_BOOKING.".reid ='$recurrent_id'"; 				

						$recurrent_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();			
						// To get Recurrent Log details from Passenger Log Table //

						// Insert into Passenger Log Table //	

						$pickup_datetime = $today_date.' '.$recurrent_details[0]['recurrent_pickuptime'];

						$insert_booking = 'S';

						$today_result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude',
						'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','search_city','faretype','fixedprice','luggage','bookby',
						'operator_id','additional_fields','travel_status','taxi_modelid','recurrent_type','recurrent_id','company_tax','account_id','accgroup_id'))->values(array($booking_key,$recurrent_details[0]['recurrent_passengerid'],$recurrent_details[0]['companyid'],$recurrent_details[0]['recurrent_pickuplocation'],$recurrent_details[0]['recurrent_pickuplatitude'],$recurrent_details[0]['recurrent_pickuplongitude'],$recurrent_details[0]['recurrent_droplocation'],$recurrent_details[0]['recurrent_droplatitude'],$recurrent_details[0]['recurrent_droplongitude'],$pickup_datetime,$recurrent_details[0]['recurrent_noofpassengers'],$recurrent_details[0]['recurrent_approxdistance'],$recurrent_details[0]['recurrent_approxduration'],$recurrent_details[0]['recurrent_approxfare'],$recurrent_details[0]['recurrent_city'],$recurrent_details[0]['recurrent_faretype'],$recurrent_details[0]['recurrent_fixedprice'],$recurrent_details[0]['recurrent_luggage'],'2',$recurrent_details[0]['recurrent_operatorid'],$recurrent_details[0]['recurrent_additionalfields'],'0',$recurrent_details[0]['recurrent_modelid'],'2',$recurrent_id,$company_tax,$recurrent_details[0]['recurrent_accountid'],$recurrent_details[0]['recurrent_groupid']))->execute();	


						$ins_logid = $today_result[0];
						
						$trip_id = $ins_logid;

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

					/** Insert Tomorrow Booking **/
					if($checktomorrow_result[0]['checkdate'] == 1 && $checktomorrow_result2[0]['finddate'] == 1)
					{

						// To get Recurrent Log details from Passenger Log Table //
						$recurrent_query = "SELECT * from ".RECURR_BOOKING." where ".RECURR_BOOKING.".reid ='$recurrent_id'"; 				

						$recurrent_details = Db::query(Database::SELECT, $recurrent_query)->execute()->as_array();			
						// To get Recurrent Log details from Passenger Log Table //

						// Insert into Passenger Log Table //	

						$pickup_datetime = $next_date.' '.$recurrent_details[0]['recurrent_pickuptime'];

						$insert_booking = 'S';

						$nextday_result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude',
						'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','search_city','faretype','fixedprice','luggage','bookby',
						'operator_id','additional_fields','travel_status','taxi_modelid','recurrent_type','recurrent_id','company_tax','account_id','accgroup_id'))->values(array($booking_key,$recurrent_details[0]['recurrent_passengerid'],$recurrent_details[0]['companyid'],$recurrent_details[0]['recurrent_pickuplocation'],$recurrent_details[0]['recurrent_pickuplatitude'],$recurrent_details[0]['recurrent_pickuplongitude'],$recurrent_details[0]['recurrent_droplocation'],$recurrent_details[0]['recurrent_droplatitude'],$recurrent_details[0]['recurrent_droplongitude'],$pickup_datetime,$recurrent_details[0]['recurrent_noofpassengers'],$recurrent_details[0]['recurrent_approxdistance'],$recurrent_details[0]['recurrent_approxduration'],$recurrent_details[0]['recurrent_approxfare'],$recurrent_details[0]['recurrent_city'],$recurrent_details[0]['recurrent_faretype'],$recurrent_details[0]['recurrent_fixedprice'],$recurrent_details[0]['recurrent_luggage'],'2',$recurrent_details[0]['recurrent_operatorid'],$recurrent_details[0]['recurrent_additionalfields'],'0',$recurrent_details[0]['recurrent_modelid'],'2',$recurrent_id,$company_tax,$recurrent_details[0]['recurrent_accountid'],$recurrent_details[0]['recurrent_groupid']))->execute();	


						$ins_logid = $nextday_result[0];
						
						$trip_id = $ins_logid;

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
					/** Insert Tomorrow Booking **/
				}
				/** all_dates not empty **/


			}

		}



			}
			// if recurrent booking //


			if(isset($post['dispatch']))
			{
				$company_id = $_SESSION['company_id'];
				$company_dispatch = DB::select()->from(TBLALGORITHM)
									->where('alg_company_id','=',$company_id)
									->order_by('aid','desc')
									->limit(1)
									->execute()
									->as_array();

				if(count($company_dispatch) > 0)	
				{
					$tdispatch_type = $company_dispatch[0]['labelname'];
					//$match_vehicletype = $company_dispatch[0]['match_vehicletype'];
					$hide_customer = $company_dispatch[0]['hide_customer'];
					$hide_droplocation = $company_dispatch[0]['hide_droplocation'];
					//$hide_fare = $company_dispatch[0]['hide_fare'];
					/** Auto Dispatch **/
					//echo $today_result[0];
					if($trip_id != "")					
					{
						$pass_logid = $this->get_autodispatch($trip_id);	
					}
					else
					{
						//$pass_logid = $this->get_bookingrecurrentdetails($insert_recurr[0]);
						$pass_logid = '';
					}

					//echo $pass_logid;
					//echo '<br>';
					//echo $tdispatch_type;
					//exit;
					if($tdispatch_type == 1 && $pass_logid !='')
					{

						$booking_details = $this->get_bookingdetails($pass_logid);

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

						$driver_details = $this->search_driver_location($latitude,$longitude,$miles,$no_passengers,$_REQUEST,$taxi_fare_km,$taxi_model,$taxi_type,$maximum_luggage,$cityname,$pass_logid,$company_id,$search_driver);
						//print_r($driver_details);exit;
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
						$nearest_driver_id = $nearest_taxi_id = "";
						$total_count = count($driver_details);
						//exit;

						if(count($driver_details) > 0)
						{
							/*Nearest driver calculation */
							foreach($driver_details as $key => $value)
							{								
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
									$nearest_driver_id=$driver_details[$nearest_key]['driver_id'];
									$nearest_taxi_id=$driver_details[$nearest_key]['taxi_id'];
								}
								else
								{
									$nearest_driver=0;
									$nearest_taxi_id=0;
								}

							}
						//	echo $nearest_driver_id;
						//	echo '<br>';
						//	echo $nearest_taxi_id;
							//exit;
							/*Nearest driver calculation End*/
							
							$miles_or_km = round(($prev_min_distance),2);
							$driver_away_in_km = (ceil($miles_or_km*100)/100);
							
							$company_id = $_SESSION['company_id'];
							$common_model = Model::factory('commonmodel');

							//$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);	
							$current_datetime =	date('Y-m-d H:i:s');	
							$duration ='+1 minutes';
							$current_datetime = date('Y-m-d H:i:s', strtotime($duration, strtotime($current_datetime)));
							
							/****** Estimated Arival *************/
							$taxi_speed=$api->get_taxi_speed($nearest_taxi_id);
							$estimated_time = $api->estimated_time($driver_away_in_km,$taxi_speed);
							/**************************************/

							$updatequery = " UPDATE ".PASSENGERS_LOG." SET driver_id='".$nearest_driver_id."',taxi_id='".$nearest_taxi_id."',travel_status='7',driver_reply='',msg_status='U',dispatch_time='$current_datetime' wHERE passengers_log_id ='". $pass_logid."'";					
							//exit;	
							$updateresult = Db::query(Database::UPDATE, $updatequery)
				   			 ->execute();

							

						/* Create Log */		
						$company_id = $_SESSION['company_id'];			
						$userid = $_SESSION['userid'];			
						$log_message = __('log_message_dispatched');
						$log_message = str_replace("PASS_LOG_ID",$pass_logid,$log_message); 
						$log_booking = __('log_booking_dispatched');
						$log_booking = str_replace("DRIVERNAME",$driver_details[0]['name'],$log_booking); 
						$log_status = $this->create_logs($pass_logid,$company_id,$userid,$log_message,$log_booking);
						?>
						<script type="text/javascript">load_logcontent();</script>
						<?php 
						/* Create Log */											

													
						/***** Insert the druiver details to driver request table ************/
						$insert_array = array(
												"trip_id" => $pass_logid,
												"available_drivers" 			=> $nearest_driver_id,
												"status" 	=> '0',
												"rejected_timeout_drivers"		=> "",
												"createdate"		=> $current_datetime,
											);								
						//Inserting to Transaction Table 
						$transaction = $common_model->insert(DRIVER_REQUEST_DETAILS,$insert_array);	
						$detail = array("passenger_tripid"=>$pass_logid,"notification_time"=>"");
						$msg = array("message" => __('api_request_confirmed_passenger'),"status" => 1,"detail"=>$detail);
							
					}
	
					
					}
					/** Auto Dispatch **/

				}
				
			}
			//echo $recurrent_id;
			//echo '<br>';
			//echo $pass_logid;
			//exit;
			$req_result['send_mail'] = $send_mail;	
			$req_result['pass_logid'] = $pass_logid;
			$req_result['recurrent_id'] = $recurrent_id;
			$req_result['insert_booking'] = $insert_booking;

			return $req_result;
	}

	public function updatebooking($post,$random_key,$password)
	{
			$firstname = Html::chars($post['firstname']);
			$send_mail = 'N';	
			

			$city_id = $post['city_id'];
			$search_city = $post['cityname'];

			if($search_city!='')
			{
				$cityid_query = "select city_id from ".CITY." where ".CITY.".city_name like '%".$search_city."%' limit 0,1"; 
				$cityid_fetch = Db::query(Database::SELECT, $cityid_query)
						->execute()
						->as_array();
				
			}		
			else
			{
				$cityid_query = "select city_id from ".CITY." where ".CITY.".default=1"; 
				$cityid_fetch = Db::query(Database::SELECT, $cityid_query)
						->execute()
						->as_array();
			}


			if(count($cityid_fetch) == 0)
			{
				$cityid_query = "select city_id from ".CITY." where ".CITY.".default=1"; 
				$cityid_fetch = Db::query(Database::SELECT, $cityid_query)
						->execute()
						->as_array();
			}

			$city_id = $cityid_fetch[0]['city_id'];

			$passenger_id = $post['passenger_id'];
			$company_id = $_SESSION['company_id'];
			$common_model = Model::factory('commonmodel');

			$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);
			$current_datesplit = explode(' ',$current_datetime);
			$pickup_date = $post['pickup_date'];
			$pickup_time = $post['pickup_time'];			
			if(!empty($post['addition_fields']))
			{
				$additional_fields = serialize($post['addition_fields']);
			}
			else
			{
				$additional_fields = '';
			}
			if($pickup_date =='' || $pickup_date =='Today')
			{
				$pickup_date = $current_datesplit[0];
			}

			if($pickup_time =='' || $pickup_time =='Now')
			{
				$pickup_time = $current_datesplit[1];
			}
			
			$pickup_datetime = $pickup_date.' '.$pickup_time;
			
			$user_createdby = $userid = $_SESSION['userid'];

			if(isset($post['dispatch']))
			{
				$booktype = '1';
			}
			else
			{
				$booktype = '2';
			}

			if(isset($post['group_id']))
			{
				$group_id = $post['group_id'];
				$company_dispatch = DB::select()->from(TBLGROUP)
					->where('gid','=',$group_id)
					->execute()
					->as_array();

				if(count($company_dispatch) > 0)
				{
					$account_id = $company_dispatch[0]['aid'];
				}
				else
				{
					$account_id = '0';
				}
			}

			if($post['passenger_id'] =='')
			{
			$insert_passenger = DB::insert(PASSENGERS, array('name','email','phone','password','org_password','created_date','activation_key','user_status','passenger_cid','activation_status'))
			->values(array($firstname,$post['email'],$post['phone'],md5($password),$password,$current_datetime,$random_key,ACTIVE,$company_id,1))
			->execute();

			$send_mail = 'S';		
		 	$passenger_id = $insert_passenger[0];

				/*if(!isset($post['group_id'])) {


				$check_accountexist = DB::select('account_name')->from(TBLGROUPACCOUNT)->where('account_name','=',$firstname)->where('acc_created_by','=',$user_createdby)->execute()->as_array();	
			
				if(count($check_accountexist) == 0)
				{
					/** Insert Account **
					$insert_account = DB::insert(TBLGROUPACCOUNT, array('acc_created_by','acc_company_id','account_name','passid'))->values(array($user_createdby,$company_id,$firstname,$passenger_id))->execute();
					$account_id = $insert_account['0'];
					/** Insert Account **
				}
				else
				{
					/** Insert Account **
					$insert_account = DB::insert(TBLGROUPACCOUNT, array('acc_created_by','acc_company_id','account_name','passid'))->values(array($user_createdby,$company_id,$firstname.'_'.$passenger_id,$passenger_id))->execute();
					$account_id = $insert_account['0'];
					/** Insert Account **

				}	


				$check_groupexist = DB::select('department')->from(TBLGROUP)->where('department','=',$firstname)->where('gcompany_id','=',$company_id)->execute()->as_array();	
			
				if(count($check_groupexist) == 0)
				{
					/** Insert Group **
					$insert_group = DB::insert(TBLGROUP, array('aid','gcompany_id','passenger_id','department'))->values(array($insert_account[0],$company_id,$passenger_id,$firstname))->execute();
					$group_id = $insert_group['0'];
					/** Insert Group **
				}
				else
				{
					/** Insert Group **
					$insert_group = DB::insert(TBLGROUP, array('aid','gcompany_id','passenger_id','department'))->values(array($insert_account[0],$company_id,$passenger_id,$firstname.'_'.$passenger_id))->execute();
					$group_id = $insert_group['0'];
					/** Insert Group **

				}


				} */

			}

			$account_id = isset($account_id)?$account_id:'0';
			$group_id = isset($group_id)?$group_id:'0';

		 $updatequery = " UPDATE ".PASSENGERS_LOG." SET passengers_id = '".$passenger_id."',current_location = '".$post['current_location']."',pickup_latitude = '".$post['pickup_lat']."',pickup_longitude = '".$post['pickup_lng']."',drop_location = '".$post['drop_location']."',drop_latitude = '".$post['drop_lat']."',drop_longitude = '".$post['drop_lng']."',pickup_time = '".$pickup_datetime."',no_passengers = '".$post['no_passengers']."',approx_distance = '".$post['distance_km']."',approx_duration = '".$post['total_duration']."',approx_fare = '".$post['total_fare']."',search_city='".$city_id."',notes_driver = '".$post['notes']."',faretype = '".$post['payment_type']."',fixedprice = '".$post['fixedprice']."',bookingtype = '".$booktype."',luggage = '".$post['luggage']."',bookby = '2',operator_id = '".$userid."',additional_fields = '".$additional_fields."',taxi_modelid = '".$post['taxi_model']."',company_tax ='".$post['company_tax']."',account_id ='".$account_id."',accgroup_id ='".$group_id."'  wHERE passengers_log_id = '".$post['pass_logid']."' ";	
			 
	 		$updateresult = Db::query(Database::UPDATE, $updatequery)
   			 ->execute();


			$req_result['send_mail'] = $send_mail;
			$req_result['pass_logid'] = $post['pass_logid'];
			return $req_result;			

	}


	public function check_emailuserdetails($post)
	{
		$passenger_id = isset($post['passenger_id'])?$post['passenger_id']:'';
		$company_id = $_SESSION['company_id'];
		$email = isset($post['email'])?$post['email']:'';
		$phone = isset($post['phone'])?$post['phone']:'';

		if($email != "")
		{ 
			$query = "select id from passengers where email ='".$email."' and passenger_cid='".$company_id."' ";
			//$query = "select id from passengers where email ='".$email."' ";
			if($passenger_id !='')
			{
				$query .= " and id !='".$passenger_id."'";
			}

			$results = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();	
		  
			if(count($results)>0)
			{
				return  1;//__('email_exists');
			}
			else
			{
				return 0;
			}

		}
		else
		{
			return 0;
		}
	}


	public function check_phoneuserdetails($post)
	{
		$passenger_id = isset($post['passenger_id'])?$post['passenger_id']:'';
		$company_id = $_SESSION['company_id'];
		$email = isset($post['email'])?$post['email']:'';
		$phone = isset($post['phone'])?$post['phone']:'';
		//echo $email.'-'.$phone;
		if($phone!="")
		{ 
			$query = "select id from passengers where phone ='".$phone."'  and passenger_cid='".$company_id."' ";
			//$query = "select id from passengers where phone ='".$phone."' ";
			if($passenger_id !='')
			{
				$query .= " and id !='".$passenger_id."'";
			}
			//echo $query;
			$results = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();	
		  
			if(count($results)>0)
			{
				return 1;
			}
			else
			{
				return 0;
			}

		}
		else
		{
			return 0;
		}
	}	

	public function count_booking_list()
	{
		
		$currentdate = date('Y-m-d').' 00:00:00';
		$enddate = date('Y-m-d').' 23:59:59';
		$company_id = $_SESSION['company_id'];


		$query = "SELECT * FROM ".PASSENGERS_LOG." where bookby='2'  AND company_id='$company_id' AND ( pickup_time between '$currentdate' and  '$enddate' )";


		$result = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();			

		return count($result);
	}
	
	public function all_booking_list($offset, $val)
	{
		
	$currentdate = date('Y-m-d').' 00:00:00';
	$enddate = date('Y-m-d').' 23:59:59';
	$company_id = $_SESSION['company_id'];

		$query = "SELECT *,(select company_name from ".COMPANY." where cid=".PASSENGERS_LOG.".company_id) as company_name,".PASSENGERS_LOG.".passengers_log_id as pass_logid,(select name from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_name,(select name from ".PEOPLE." where ".PEOPLE.".id=".PASSENGERS_LOG.".driver_id) as driver_name,(select phone from ".PEOPLE." where ".PEOPLE.".id=".PASSENGERS_LOG.".driver_id) as driver_phone,(select phone from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_phone FROM ".PASSENGERS_LOG." where bookby='2' AND ".PASSENGERS_LOG.".company_id='$company_id' AND ( pickup_time between '$currentdate' and  '$enddate' ) order by passengers_log_id desc  LIMIT ".$offset.",".$val."";


		$result = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();			

		return $result;
	}

	public function count_bookingsearch_list($search_txt ='',$filter_date ='',$to_date ='',$sort_type='',$sort_by='',$booking_filter='')
	{

	$search_txt = str_replace("%","!%",$search_txt);
	$search_txt = str_replace("_","!_",$search_txt);
	$pickupdrop_where = '';
	$date_where = '';
	$sort_query ='';
	$status_query = '';
	
	$currentdate = $filter_date.' 00:00:00';
	$today_enddate = $filter_date.' 23:59:59';
	$enddate = $to_date.' 23:59:59';
	$company_id = $_SESSION['company_id'];

	$date_where = '';		
         if($search_txt !=''){
		$pickupdrop_where  = " AND (current_location LIKE  '%$search_txt%' ";
		$pickupdrop_where .= " or drop_location LIKE '%$search_txt%' escape '!'  ";
		$pickupdrop_where .= " or passenger_name LIKE '%$search_txt%'  ";
		$pickupdrop_where .= " or passengers_log_id LIKE '%$search_txt%' ) ";

	 }

	if($filter_date !='' && $to_date !='')	
	{	
		$date_where = " AND ( pickup_time between '$currentdate' and  '$enddate' ) ";
	}
	else
	{
		$date_where = " AND ( pickup_time between '$currentdate' and '$today_enddate' ) ";
	}

	if($booking_filter !='')
	{
		$status_query = " AND ( travel_status = '$booking_filter' ) ";
	}

		$query = "SELECT *,(select company_name from ".COMPANY." where cid=".PASSENGERS_LOG.".company_id) as company_name,".PASSENGERS_LOG.".passengers_log_id as pass_logid,(select name from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_name,(select name from ".PEOPLE." where ".PEOPLE.".id=".PASSENGERS_LOG.".driver_id) as driver_name,(select phone from ".PEOPLE." where ".PEOPLE.".id=".PASSENGERS_LOG.".driver_id) as driver_phone,(select phone from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_phone FROM ".PASSENGERS_LOG."  having bookby='2' AND ".PASSENGERS_LOG.".company_id='$company_id' $pickupdrop_where $date_where $status_query ";



		$result = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();			

		return count($result);
	}
	
	public function all_bookingsearch_list($search_txt ='',$filter_date ='',$to_date ='',$sort_type='',$sort_by='',$booking_filter='',$offset, $val)
	{
	$search_txt = str_replace("%","!%",$search_txt);
	$search_txt = str_replace("_","!_",$search_txt);
	$pickupdrop_where = '';
	$date_where = '';	
	$sort_query ='';
	$status_query = '';

	$currentdate = $filter_date.' 00:00:00';
	$today_enddate = $filter_date.' 23:59:59';
	$enddate = $to_date.' 23:59:59';
	$company_id = $_SESSION['company_id'];

	$date_where = '';		
	if($search_txt !=''){
		$pickupdrop_where  = " AND (current_location LIKE  '%$search_txt%' ";
		$pickupdrop_where .= " or drop_location LIKE '%$search_txt%' escape '!'  ";
		$pickupdrop_where .= " or passenger_name LIKE '%$search_txt%' ";
		$pickupdrop_where .= " or passengers_log_id LIKE '%$search_txt%' ) ";
	 }

	if($filter_date !='' && $to_date !='')	
	{	
		$date_where = " AND ( pickup_time between '$currentdate' and  '$enddate' ) ";
	}
	else
	{
		$date_where = " AND ( pickup_time between '$currentdate' and '$today_enddate' ) ";
	}

	if($booking_filter !='')
	{
		$status_query = " AND ( travel_status = '$booking_filter' ) ";
	}

	if($sort_type !=''){
		if($sort_type == 1)
		{
			$sort_query  = " order by pickup_time $sort_by";
		}
		elseif($sort_type == 2)
		{
			$sort_query  = " order by passenger_name $sort_by";
		}		
		elseif($sort_type == 3)
		{
			$sort_query  = " order by approx_duration $sort_by";
		}		
		elseif($sort_type == 4)
		{
			$sort_query  = " order by approx_distance $sort_by";
		}		
		elseif($sort_type == 5)
		{
			$sort_query  = " order by bookingtype $sort_by";
		}		
		else
		{
			$sort_query  = " order by passengers_log_id $sort_by";
		}		
	 }
	 else
	 {
		$sort_query  = " order by passengers_log_id $sort_by";
	 }	
		
		$query = "SELECT *,(select company_name from ".COMPANY." where cid=".PASSENGERS_LOG.".company_id) as company_name,".PASSENGERS_LOG.".passengers_log_id as pass_logid,(select name from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_name,(select name from ".PEOPLE." where ".PEOPLE.".id=".PASSENGERS_LOG.".driver_id) as driver_name,(select phone from ".PEOPLE." where ".PEOPLE.".id=".PASSENGERS_LOG.".driver_id) as driver_phone,(select phone from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_phone FROM ".PASSENGERS_LOG."  having bookby='2' AND ".PASSENGERS_LOG.".company_id='$company_id' $pickupdrop_where $date_where $status_query $sort_query limit $offset,$val";


		$result = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();			

		return $result;
	}

	public function edit_bookingdetails($pass_logid='')
	{ 

		$company_id = $_SESSION['company_id'];

		if(FARE_SETTINGS == 2 && !empty($company_id))
		{

		$query = "SELECT *,(select company_name from ".COMPANY." where cid=".PASSENGERS_LOG.".company_id) as company_name,".PASSENGERS_LOG.".passengers_log_id as pass_logid,(select name from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_name,(select email from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_email,(select phone from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_phone FROM ".PASSENGERS_LOG." where ".PASSENGERS_LOG.".passengers_log_id ='$pass_logid' AND ".PASSENGERS_LOG.".company_id='$company_id'  ";
		}
		else
		{

		$query = "SELECT *,(select company_name from ".COMPANY." where cid=".PASSENGERS_LOG.".company_id) as company_name,".PASSENGERS_LOG.".passengers_log_id as pass_logid,(select name from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_name,(select email from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_email,(select phone from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_phone,(select min_fare from ".MOTORMODEL." where ".MOTORMODEL.".model_id=".PASSENGERS_LOG.".taxi_modelid) as min_fare FROM ".PASSENGERS_LOG." where ".PASSENGERS_LOG.".passengers_log_id ='$pass_logid' AND ".PASSENGERS_LOG.".company_id='$company_id'  ";

		}

		//echo $query;

		$result = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();			

		return $result;
	}

	public function get_bookingdetails($pass_logid)
	{

		$company_id = $_SESSION['company_id'];

		$query = "SELECT *,(select company_name from ".COMPANY." where cid=".PASSENGERS_LOG.".company_id) as company_name,".PASSENGERS_LOG.".passengers_log_id as pass_logid,(select name from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_name,(select email from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_email,(select phone from ".PASSENGERS." where ".PASSENGERS.".id=".PASSENGERS_LOG.".passengers_id) as passenger_phone,(select min_fare from ".MOTORMODEL." where ".MOTORMODEL.".model_id=".PASSENGERS_LOG.".taxi_modelid) as min_fare FROM ".PASSENGERS_LOG."  where ".PASSENGERS_LOG.".passengers_log_id ='$pass_logid' AND ".PASSENGERS_LOG.".company_id='$company_id'";


		$result = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();			

		return $result;
	}

	public function update_bookingrating($post)
	{

		/*$company_id = $_SESSION['company_id'];

		 $updatequery = " UPDATE ".PASSENGERS_LOG." SET controller_rating='".$post['rating']."' wHERE passengers_log_id = '".$post['pass_logid']."' AND ".PASSENGERS_LOG.".company_id='$company_id'";	

 		$updateresult = Db::query(Database::UPDATE, $updatequery)
		 ->execute();*/

		$updateresult = "";
		return $updateresult;
	}

	public function update_bookingcomments($post)
	{
		/*$company_id = $_SESSION['company_id'];

		 $updatequery = " UPDATE ".PASSENGERS_LOG." SET controller_comments='".$post['feedback_comments']."' wHERE passengers_log_id = '".$post['pass_logid']."'  AND ".PASSENGERS_LOG.".company_id='$company_id'";	

 		$updateresult = Db::query(Database::UPDATE, $updatequery)
		 ->execute(); */
		 $updateresult = "";


		return $updateresult;
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
		$where_cond = '';			

		//print_r($request);
		/*$additional_fields = $this->taxi_additionalfields();		
		$field_count = count($additional_fields);
		

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

	public function search_driver_location($lat,$long,$distance = NULL,$no_passengers,$request,$taxi_fare_km,$taxi_model,$taxi_type,$maximum_luggage,$city_name,$sub_log_id,$company_id,$search_driver)
	{
		$unit = UNIT;
		$distance = "";
		$unit_conversion = "";
		$remove_driver_list = array();


		$assigned_driver = $this->free_availabletaxisearch_list_web($no_passengers,$request,$company_id);

		//$additional_fields = $this->taxi_additionalfields();
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
		} */
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
		if($add_field != "")
		{
			$additional_field_join = "JOIN ".ADDFIELD." as adds ON tmap.`mapping_taxiid`=adds.`taxi_id`";
		}

		$driver_like = '';
		if($search_driver)
		{
			$driver_like = "  and name LIKE  '%$search_driver%' ";
		}

		$common_model = Model::factory('commonmodel');
		$current_datetime = $common_model->company_timezone($company_id);

		$current_time = convert_timezone('now',$current_datetime);
		$current_date = explode(' ',$current_time);
		$start_time = $current_date[0].' 00:00:01';
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
						
	
		$query =" select  list.name as name,list.driver_id as driver_id,list.phone as phone,list.profile_picture as d_photo,list.id as id,list.latitude as latitude,list.longitude as longitude,list.status as status,list.distance as distance,list.distance as distance_miles,comp.company_name as company_name,taxi.taxi_no as taxi_no,taxi.taxi_image as taxi_image,taxi.taxi_capacity as taxi_capacity,taxi.taxi_id as taxi_id from ( SELECT people.name,people.profile_picture,people.phone,driver.*,(((acos(sin((".$lat."*pi()/180)) * sin((driver.latitude*pi()/180))+cos((".$lat."*pi()/180)) *  cos((driver.latitude*pi()/180)) * cos(((".$long."- driver.longitude)* pi()/180))))*180/pi())*60*1.1515$unit_conversion) AS distance,(TIME_TO_SEC(TIMEDIFF('$current_time',driver.update_date))) AS updatetime_difference FROM ".DRIVER." AS driver JOIN ".PEOPLE." AS people ON driver.driver_id=people.id  where people.login_status='S' $distance_query AND driver.shift_status='IN' AND driver.status='F' and driver_id IN ($driver_list) order by distance ) as list JOIN ".TAXIMAPPING." as tmap ON list.`driver_id`=tmap.`mapping_driverid` JOIN ".TAXI." as taxi ON tmap.`mapping_taxiid`=taxi.`taxi_id` JOIN ".MOTORMODEL." as model ON model.`model_id`=taxi.`taxi_model` JOIN ".COMPANY." AS comp ON tmap.`mapping_companyid`=comp.`cid` $additional_field_join where tmap.mapping_startdate <='$current_time' AND updatetime_difference  <= '".LOCATIONUPDATESECONDS."' AND tmap.mapping_enddate >='$current_time'  AND tmap.`mapping_status`='A' ".$where.$add_field.$driver_like." group by list.driver_id order by distance DESC ";
		//echo $query;exit;
		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();

			
		return $result;
		
	}	

	public function updatebooking_logid($data)
	{
			$company_id = $_SESSION['company_id'];
			$common_model = Model::factory('commonmodel');

			//$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);	
			$current_datetime =	date('Y-m-d H:i:s');	
			$duration ='+1 minutes';
			$current_datetime = date('Y-m-d H:i:s', strtotime($duration, strtotime($current_datetime)));

			$updatequery = " UPDATE ".PASSENGERS_LOG." SET driver_id='".$data['driver_id']."',taxi_id='".$data['taxi_id']."',travel_status='7',driver_reply='',msg_status='U',comments='',dispatch_time='$current_datetime' wHERE passengers_log_id ='". $data['pass_logid']."'";	
					
			$updateresult = Db::query(Database::UPDATE, $updatequery)
   			 ->execute();

			return 1;
	}

	public function get_driver_profile_details($id="")
	{
		$sql = "SELECT * FROM ".PEOPLE." WHERE id = '$id' ";                      
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}

	public function cancelbooking_logid($data)
	{
			$updatequery = " UPDATE ".PASSENGERS_LOG." SET travel_status='8' wHERE passengers_log_id ='". $data['pass_logid']."'";						
			$updateresult = Db::query(Database::UPDATE, $updatequery)
   			 ->execute();

			return 1;
	}

	public function completebooking_logid($data)
	{


			$passenger_log_id = $data['pass_logid'];
			$complete_distance = $data['complete_distance'];
			//$complete_waitingtime = $data['complete_waitingtime'];
			//$complete_waitingcost = $data['complete_waitingcost'];
			//$complete_nightcharge = $data['complete_nightcharge'];
			$complete_waitingtime = '';
			$complete_waitingcost = 0;
			$complete_nightcharge = 0;
			$complete_total = $data['complete_total'];
			$complete_remark = $data['complete_remark'];

			$company_id = $_SESSION['company_id'];

			$check_transquery = "select * from ".TRANS." where passengers_log_id='".$data['pass_logid']."'";

			$check_transres = Db::query(Database::SELECT, $check_transquery)
   			 		->execute()
					->as_array();	


			if(count($check_transres) > 0)
			{
				$updatequery = " UPDATE ".PASSENGERS_LOG." SET travel_status='1' wHERE passengers_log_id ='". $data['pass_logid']."'";	

				$updateresult = Db::query(Database::UPDATE, $updatequery)
	   			 ->execute();
			}
			else
			{

				$common_model = Model::factory('commonmodel');

				$company_tax = $common_model->company_tax($company_id);	

				$passengerlog_sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE passengers_log_id = '$passenger_log_id'";

				$passengerlog_result = Db::query(Database::SELECT, $passengerlog_sql)
							->execute()
							->as_array();	

				$first_query = "select * from " . PACKAGE_REPORT . " join ".PACKAGE." on ".PACKAGE.".package_id =".PACKAGE_REPORT.".upgrade_packageid  where ".PACKAGE_REPORT.".upgrade_companyid = ".$company_id."  order by upgrade_id desc limit 0,1";

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

				$siteinfo_query = "SELECT admin_commission  FROM ".SITEINFO;
				$siteinfo_details = Db::query(Database::SELECT, $siteinfo_query)
						    ->execute()
						    ->as_array(); 

				$update_commission = $common_model->update_commission($passenger_log_id,$complete_total,$siteinfo_details[0]['admin_commission']);


				$faretype = $passengerlog_result[0]['faretype'];

				$result = DB::insert(TRANS, array('passengers_log_id','distance','actual_distance','tripfare','fare','waiting_cost','waiting_time','company_tax','remarks',
				'payment_type','amt','trans_packtype'))->values(array($passenger_log_id,$complete_distance,$complete_distance,$complete_total,$complete_total,$complete_waitingcost,$complete_waitingtime,$company_tax,$complete_remark,$faretype,$complete_total,$check_package_type))->execute();


				$updatequery = " UPDATE ".PASSENGERS_LOG." SET travel_status='1' wHERE passengers_log_id ='". $data['pass_logid']."'";	

				$updateresult = Db::query(Database::UPDATE, $updatequery)
	   			 ->execute();

			}
					

			return 1;
	}

	public function count_frequent_location()
	{
		$user_createdby = $_SESSION['userid'];
		 $rs = DB::select()->from(FREQUENT_LOCATION)
					->where('fuserid','=',$user_createdby)
					->order_by('fid','ASC')
					->execute()
					->as_array();
		 return count($rs);
	}

	public function all_frequent_location($offset, $val)
	{
		$user_createdby = $_SESSION['userid'];
		$result = DB::select()->from(FREQUENT_LOCATION)
			->where('fuserid','=',$user_createdby)
			->order_by('fid','ASC')
			->limit($val)->offset($offset)
			->execute()
			->as_array();
		  return $result;
		   
	}

        public function frequent_location()
        {

		$user_createdby = $_SESSION['userid'];
                 $result=DB::select()->from(FREQUENT_LOCATION)->where('fuserid','=',$user_createdby)
			     ->order_by('fid')
			     ->execute()
			     ->as_array();
        
                return  $result;

        }  

	public function validate_addlocation($arr) 
	{
		return Validation::factory($arr)       
		
			->rule('location_name', 'not_empty')
			->rule('location_name', 'min_length', array(':value', '3'))
			->rule('location_name', 'max_length', array(':value', '150'))
			->rule('location_name', 'Model_Tdispatch::checklocationname', array(':value',$arr['location_name']))
			
			//->rule('type', 'not_empty')

			//->rule('keywords', 'not_empty')
			//->rule('keywords', 'min_length', array(':value', '4'))
			//->rule('keywords', 'max_length', array(':value', '150'))
			
			->rule('location', 'not_empty');

	}

	public static function checklocationname($locationname)
	{
		// Check if the username already exists in the database
		$user_createdby = $_SESSION['userid'];
		$result = DB::select('location_name')->from(FREQUENT_LOCATION)->where('location_name','=',$locationname)->where('fuserid','=',$user_createdby)
			->execute()
			->as_array();	
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}


	public static function addlocation($post)
	{
		$user_createdby = $_SESSION['userid'];
		/*
		$result = DB::insert(FREQUENT_LOCATION, array('fuserid','location_name','type','keywords','location'))->values(array($user_createdby,$post['location_name'],$post['type'],$post['keywords'],$post['location']))->execute();
		*/

		$result = DB::insert(FREQUENT_LOCATION, array('fuserid','location_name','location'))->values(array($user_createdby,$post['location_name'],$post['location']))->execute();

		if($result)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public static function location_details($fid)
	{
		$user_createdby = $_SESSION['userid'];
		$result = DB::select('*')->from(FREQUENT_LOCATION)->where('fid','=',$fid)->where('fuserid','=',$user_createdby)
			->execute()
			->as_array();	
		
		return $result;
	}

	public function validate_editlocation($arr,$fid) 
	{
		return Validation::factory($arr)       
		
			->rule('location_name', 'not_empty')
			->rule('location_name', 'min_length', array(':value', '3'))
			->rule('location_name', 'max_length', array(':value', '150'))
			->rule('location_name', 'Model_Tdispatch::edit_checklocationname', array(':value',$fid))
			
			//->rule('type', 'not_empty')

			//->rule('keywords', 'not_empty')
			//->rule('keywords', 'min_length', array(':value', '4'))
			//->rule('keywords', 'max_length', array(':value', '150'))
			
			->rule('location', 'not_empty');

	}

	public static function edit_checklocationname($locationname,$fid)
	{
		$user_createdby = $_SESSION['userid'];	
		$result = DB::select('location_name')->from(FREQUENT_LOCATION)->where('location_name','=',$locationname)->where('fuserid','=',$user_createdby)->where('fid','!=',$fid)
			->execute()
			->as_array();
				
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}

	public static function editlocation($fid,$post)
	{
		$user_createdby = $_SESSION['userid'];	
		/*
		$result=DB::update(FREQUENT_LOCATION)->set(array('fuserid'=>$user_createdby,'location_name'=>$post['location_name'],'type'=>$post['type'],'keywords'=>$post['keywords'],'location'=>$post['location']))->where('fid','=',$fid)->execute();	
		*/

		$result=DB::update(FREQUENT_LOCATION)->set(array('fuserid'=>$user_createdby,'location_name'=>$post['location_name'],'location'=>$post['location']))->where('fid','=',$fid)->execute();	

		if($result)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function delete_frequentlocation($fid) {
			$user_createdby = $_SESSION['userid'];	
			$result = DB::delete(FREQUENT_LOCATION)
				-> where('fid', '=',$fid)
				->where('fuserid','=',$user_createdby)
				-> execute();
			return $result;
	}


	public function count_frequent_journey()
	{
		$user_createdby = $_SESSION['userid'];	
		 $rs = DB::select()->from(FREQUENT_JOURNEY)->where('fjuserid','=',$user_createdby)
					->order_by('fjid','ASC')
					->execute()
					->as_array();
		 return count($rs);
	}

	public function all_frequent_journey($offset, $val)
	{
		$user_createdby = $_SESSION['userid'];	
		$result = DB::select()->from(FREQUENT_JOURNEY)->where('fjuserid','=',$user_createdby)
			->order_by('fjid','ASC')
			->limit($val)->offset($offset)
			->execute()
			->as_array();
		  return $result;
		   
	}

        public function frequent_journey()
        {
		$user_createdby = $_SESSION['userid'];	
                 $result=DB::select()->from(FREQUENT_JOURNEY)->where('fjuserid','=',$user_createdby)
			     ->order_by('fjid')
			     ->execute()
			     ->as_array();
        
                return  $result;

        }  

	public function validate_addjourney($arr) 
	{
		return Validation::factory($arr)       
		
			->rule('journey_name', 'not_empty')
			->rule('journey_name', 'min_length', array(':value', '3'))
			->rule('journey_name', 'max_length', array(':value', '150'))
			->rule('journey_name', 'Model_Tdispatch::checkjourneyname', array(':value',$arr['journey_name']))
			
			->rule('from_location', 'not_empty')

			->rule('to_location', 'not_empty');

	}

	public static function checkjourneyname($journeyname)
	{
		// Check if the username already exists in the database
		
		$user_createdby = $_SESSION['userid'];

		$result = DB::select('journey_name')->from(FREQUENT_JOURNEY)->where('journey_name','=',$journeyname)->where('fjuserid','=',$user_createdby)
			->execute()
			->as_array();	
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}


	public static function addjourney($post)
	{
		$user_createdby = $_SESSION['userid'];

		$result = DB::insert(FREQUENT_JOURNEY, array('fjuserid','journey_name','from_location','to_location'))
					->values(array($user_createdby,$post['journey_name'],$post['from_location'],$post['to_location']))
					->execute();

		if($result)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public static function journey_details($fjid)
	{
		$user_createdby = $_SESSION['userid'];
		$result = DB::select('*')->from(FREQUENT_JOURNEY)->where('fjid','=',$fjid)->where('fjuserid','=',$user_createdby)
			->execute()
			->as_array();	
		
		return $result;
	}

	public function validate_editjourney($arr,$fjid) 
	{
		return Validation::factory($arr)       
		
			->rule('journey_name', 'not_empty')
			->rule('journey_name', 'min_length', array(':value', '3'))
			->rule('journey_name', 'max_length', array(':value', '150'))
			->rule('journey_name', 'Model_Tdispatch::edit_checkjourneyname', array(':value',$fjid))
			
			->rule('from_location', 'not_empty')
			->rule('to_location', 'not_empty');

	}

	public static function edit_checkjourneyname($journeyname,$fjid)
	{
		$user_createdby = $_SESSION['userid'];
		$result = DB::select('journey_name')->from(FREQUENT_JOURNEY)->where('journey_name','=',$journeyname)->where('fjuserid','=',$user_createdby)->where('fjid','!=',$fjid)
			->execute()
			->as_array();
				
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}

	public static function editjourney($fjid,$post)
	{
		$user_createdby = $_SESSION['userid'];
		$result=DB::update(FREQUENT_JOURNEY)->set(array('fjuserid'=>$user_createdby,'journey_name'=>$post['journey_name'],'from_location'=>$post['from_location'],'to_location'=>$post['to_location']))
				->where('fjid','=',$fjid)
				->execute();	

		if($result)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function delete_frequentjourney($fjid) {
			$user_createdby = $_SESSION['userid'];
			$result = DB::delete(FREQUENT_JOURNEY)
				-> where('fjid', '=',$fjid)
				->where('fjuserid','=',$user_createdby)
				-> execute();
			return $result;
	}


	public static function get_suggestedjourney()
	{

		$user_createdby = $_SESSION['userid'];
		$query = "SELECT * FROM ".FREQUENT_JOURNEY." where fjuserid='".$user_createdby."'";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;
	}

	public static function get_suggestedlocation()
	{
		$user_createdby = $_SESSION['userid'];
		$query = "SELECT * FROM ".FREQUENT_LOCATION." where fuserid='".$user_createdby."'";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;
	}

	public static function load_location($like)	
	{
		$user_createdby = $_SESSION['userid'];
		$query = "SELECT * FROM ".FREQUENT_LOCATION." where fjuserid='".$user_createdby."' and location_name LIKE '%$like%' ORDER BY `location_name` ASC";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;
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

	public static function booking_logdetails($pass_logid)	
	{
		$query = "SELECT * FROM ".LOGS." where log_booking !='' and booking_logid ='$pass_logid' order by logid desc";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;
	}

	public static function load_logcontent()	
	{
		//$user_createdby = $_SESSION['userid'];
		$user_createdby=$_SESSION['company_id'];
		$query = "SELECT * FROM ".LOGS." where log_userid='".$user_createdby."' ORDER BY `logid` desc limit 0,10";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();	
		return $results;
	}

	public function count_accounts()
	{
		$user_createdby = $_SESSION['userid'];
		 $rs = DB::select()->from(TBLGROUPACCOUNT)
					->where('acc_created_by','=',$user_createdby)
					->order_by('account_name','ASC')
					->execute()
					->as_array();
		 return count($rs);
	}


	public function all_accounts($offset, $val)
	{
		$user_createdby = $_SESSION['userid'];
		$result = DB::select()->from(TBLGROUPACCOUNT)
			->where('acc_created_by','=',$user_createdby)
			->order_by('aid','DESC')
			->limit($val)->offset($offset)
			->execute()
			->as_array();
		  return $result;
		   
	}

	public function validate_addaccounts($arr) 
	{
		return Validation::factory($arr)       
		
			->rule('account_name', 'not_empty')
			->rule('account_name', 'min_length', array(':value', '4'))
			->rule('account_name', 'max_length', array(':value', '150'))
			->rule('account_name', 'Model_Tdispatch::checkaccountname', array(':value',$arr['account_name']))
			->rule('account_limit', 'numeric')
			->rule('account_discount', 'not_empty')
			->rule('account_discount', 'numeric')
			->rule('firstname', 'not_empty')
			->rule('firstname', 'min_length', array(':value', '4'))
			//->rule('firstname', 'max_length', array(':value', '32'))

			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('phone', 'not_empty');
	}

	public static function checkaccountname($accountname)
	{
		// Check if the username already exists in the database
		$user_createdby = $_SESSION['userid'];
		$result = DB::select('account_name')->from(TBLGROUPACCOUNT)->where('account_name','=',$accountname)->where('acc_created_by','=',$user_createdby)
			->execute()
			->as_array();	
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}


	public static function addaccounts($post,$baserandom_key)
	{
		$random_key = $baserandom_key;
		
		$password_key = $baserandom_key;
		$password = Html::chars(md5($password_key));

		$cdate = date('Y-m-d').' 00:00:00';
		$company_id = $_SESSION['company_id'];
		$passenger_id = $post['passenger_id'];
		$send_mail = 'N';
		if($passenger_id == '')
		{
			$presult = DB::insert(PASSENGERS, array('name','email','password','org_password','phone','activation_key','created_date','login_status','user_status','passenger_cid'))
			->values(array($post['firstname'],$post['email'],$password,$password_key,$post['phone'],$random_key,$cdate,'D', ACTIVE,$company_id))
			->execute();
			$last_insert_id = $presult[0];
			$send_mail = 'S';
		}
		else
		{
			$last_insert_id = $passenger_id;
		}
		
		$user_createdby = $_SESSION['userid'];
		$company_id = $_SESSION['company_id'];
		$result = DB::insert(TBLGROUPACCOUNT, array('acc_created_by','acc_company_id','account_name','passid','limit','discount'))
					->values(array($user_createdby,$company_id,$post['account_name'],$last_insert_id,$post['account_limit'],$post['account_discount']))
					->execute();

		if($result)
		{
			return $send_mail;
		}
		else
		{
			return $send_mail;
		}
	}

	public static function account_details($aid)
	{
		$user_createdby = $_SESSION['userid'];
		$query = "SELECT *,grp_account_tbl.discount as account_discont FROM `grp_account_tbl` LEFT JOIN `passengers` ON (`grp_account_tbl`.`passid` = `passengers`.`id`) WHERE `aid` = ".$aid." AND `acc_created_by` = ".$user_createdby."";
		$result = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		
		return $result;
	}

	public function validate_editaccounts($arr,$aid) 
	{
		return Validation::factory($arr)       
		
			->rule('account_name', 'not_empty')
			->rule('account_name', 'min_length', array(':value', '4'))
			->rule('account_name', 'max_length', array(':value', '150'))
			->rule('account_name', 'Model_Tdispatch::edit_checklocationname', array(':value',$aid))
			->rule('account_limit', 'not_empty')
			->rule('account_limit', 'numeric')
			->rule('account_discount', 'not_empty')
			->rule('account_discount', 'numeric')
			->rule('firstname', 'not_empty')
			->rule('firstname', 'min_length', array(':value', '4'))
			//->rule('firstname', 'max_length', array(':value', '32'))

			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('phone', 'not_empty');


	}

	public static function edit_checkaccountname($accountname,$aid)
	{
		$user_createdby = $_SESSION['userid'];	
		$result = DB::select('account_name')->from(TBLGROUPACCOUNT)->where('account_name','=',$accountname)->where('acc_created_by','=',$user_createdby)->where('aid','!=',$aid)
			->execute()
			->as_array();
				
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}

	public static function editaccount($aid,$post,$base_random_key)
	{
		$user_createdby = $_SESSION['userid'];	
		$passenger_id = $post['passenger_id'];
		$send_mail ='N';

		$random_key = $base_random_key;
		$password = Html::chars(md5($random_key));
		$random_key1 = $base_random_key;
		$cdate = date('Y-m-d').' 00:00:00';
		$company_id = $_SESSION['company_id'];
		$passenger_id = $post['passenger_id'];		
		if($passenger_id == '')
		{
			$presult = DB::insert(PASSENGERS, array('name','email','password','org_password','phone','activation_key','created_date','login_status','user_status','passenger_cid'))
			->values(array($post['firstname'],$post['email'],$password,$random_key1,$post['phone'],$random_key,$cdate,'D', ACTIVE,$company_id))
			->execute();
			$last_insert_id = $presult[0];

			$send_mail ='S';

			$user_createdby = $_SESSION['userid'];
			$company_id = $_SESSION['company_id'];

			$result =DB::update(TBLGROUPACCOUNT)->set(array('acc_created_by'=>$user_createdby,'passid'=>$last_insert_id,'account_name'=>$post['account_name'],'limit'=>$post['account_limit'],'discount'=>$post['account_discount']))->where('aid','=',$aid)
				->where('aid','=',$aid)
				->execute();

		}
		else
		{
			$last_insert_id = $passenger_id;

			$result =DB::update(TBLGROUPACCOUNT)->set(array('acc_created_by'=>$user_createdby,'passid'=>$passenger_id,'account_name'=>$post['account_name'],'limit'=>$post['account_limit'],'discount'=>$post['account_discount']))
				->where('aid','=',$aid)
				->execute();
		}

		if($result)
		{
			$req_result['send_mail'] = $send_mail;	
			$req_result['status'] = 1;
			return $req_result;
		}
		else
		{
			$req_result['send_mail'] = $send_mail;	
			$req_result['status'] = 0;
			return $req_result;
		}
	}
	
	public function block_account_request($blockids)
	{		
		$arr_chk = " aid in ('" . implode("','",$blockids) . "') ";
		$query = " UPDATE ". TBLGROUPACCOUNT ." SET  status = '0' WHERE $arr_chk ";
		$result = Db::query(Database::UPDATE, $query)
		->execute();
		return count($result);
	}
	
	public function active_account_request($activeids)
	{
		$arr_chk = " aid in ('" . implode("','",$activeids) . "') ";
		$query = " UPDATE ". TBLGROUPACCOUNT ." SET  status = '1' WHERE $arr_chk "; 
		$result = Db::query(Database::UPDATE, $query)
		->execute();
		return count($result);
	}

	public function delete_accounts($acc_id) {
			$user_createdby = $_SESSION['userid'];	
			$result = DB::delete(TBLGROUPACCOUNT)
				-> where('aid', '=',$acc_id)
				->where('acc_created_by','=',$user_createdby)
				-> execute();
			return $result;
	}

	public function account_groupdetails($aid)
	{
		$user_createdby = $_SESSION['userid'];
		$result = DB::select()->from(TBLGROUP)
			->where('aid','=',$aid)
			->order_by('department','ASC')
			->execute()
			->as_array();
		  return $result;
		   
	}

	public function change_groups_status($data)
	{
		if($data['status'] == 1)
		{
			$status = 0;
		}
		else
		{
			$status = 1;
		}	
		
		$query = " UPDATE ". TBLGROUP ." SET  status = '$status' WHERE gid ='".$data['group_id']."'"; 
		$result = Db::query(Database::UPDATE, $query)
		->execute();
		return count($result);
	}

	public function account_userdetails($aid)
	{
		$user_createdby = $_SESSION['userid'];

		$account_query = " select * from " .TBLGROUPACCOUNT. " where  aid ='".$aid."'";

 		$account_result = Db::query(Database::SELECT, $account_query)
   			 ->execute()
			 ->as_array();

		$group_query = " select * from " .TBLGROUP. " where  aid ='".$aid."'";

 		$group_result = Db::query(Database::SELECT, $group_query)
   			 ->execute()
			 ->as_array();

		$admin_result = '';
		$user_result = '';
		$details = array();
		if(count($account_result) > 0)
		{
			$admin_id = $account_result[0]['passid'];
			$admin_query = " select * from " . PASSENGERS . " where id= '$admin_id' order by name asc ";
	 		$admin_result = Db::query(Database::SELECT, $admin_query)
	   			 ->execute()
				 ->as_array();
		}


		if(count($group_result) > 0)
		{
			$passenger_id = '';
			

			foreach($group_result as $value)
			{
				$passenger_id = $value['passenger_id'];
				$gid = $value['gid'];
				$department = $value['department'];
			
			$user_query = " select * from " . PASSENGERS . " where FIND_IN_SET (id,'$passenger_id') order by name asc ";

	 		$user_result = Db::query(Database::SELECT, $user_query)
	   			 ->execute()
				 ->as_array();

				foreach($user_result as $value)
				{
					$details[$aid.'_'.$gid.'_'.$value['id']]['name'] = $value['name'];
					$details[$aid.'_'.$gid.'_'.$value['id']]['userid'] = $value['id'];
					$details[$aid.'_'.$gid.'_'.$value['id']]['email'] = $value['email'];
					$details[$aid.'_'.$gid.'_'.$value['id']]['phone'] = $value['phone'];	
					$details[$aid.'_'.$gid.'_'.$value['id']]['department'] = $department;	
				}


			}

		}

		return ($details);
		   
	}
	
	public function validate_addgroups($arr) 
	{
		return Validation::factory($arr)       
		
			->rule('group_name', 'not_empty')
			->rule('group_name', 'min_length', array(':value', '4'))
			->rule('group_name', 'max_length', array(':value', '150'))
			->rule('group_name', 'Model_Tdispatch::checkgroupname', array(':value',$arr['acc_id']))
			->rule('limit', 'numeric')
			->rule('limit', 'Model_Tdispatch::checkgrouplimit', array(':value',$arr['acc_id']))
			->rule('acc_id', 'not_empty');
	}
	
	public function validate_editgroups($arr,$groupid) 
	{		
		return Validation::factory($arr)       
		
			->rule('group_name', 'not_empty')
			->rule('group_name', 'min_length', array(':value', '4'))
			->rule('group_name', 'max_length', array(':value', '150'))
			->rule('group_name', 'Model_Tdispatch::edit_checkgroupname', array(':value',$arr['acc_id']))
			->rule('limit', 'numeric')
			->rule('limit', 'Model_Tdispatch::checkeditgrouplimit', array(':value',$arr['acc_id'],$groupid))
			->rule('acc_id', 'not_empty');
	}
	
	public static function checkeditgrouplimit($limit,$acc_id,$groupid)
	{
		// Check if the limit already exists in the database		
		$result = DB::select('limit')->from(TBLGROUPACCOUNT)->where('aid','=',$acc_id)
			->execute()
			->as_array();
			
		$query = "SELECT sum(`limit`) as totallimit FROM ".TBLGROUP." where aid=".$acc_id." and gid !=".$groupid."";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		
		foreach($results as $vals)
		{
			$totalusedlimit = $vals['totallimit'];
		}
		foreach($result as $val)
		{
			$acc_limit = $val['limit'];
		}
		if($totalusedlimit > 0) 
		{
			$acc_limit = $acc_limit - $totalusedlimit;
		}
		else
		{
			$acc_limit = $acc_limit;
		}
		/*echo $limit;
		echo '<br>';
		echo $acc_limit; exit; */
		if($limit > $acc_limit)
		{
			return false;
		}
		else
		{			
			return true;		
		}
	}

	public static function edit_checkgroupname($group_name,$acc_id)
	{
		$company_id = $_SESSION['company_id'];
		// Check if the username already exists in the database		
		$result = DB::select('department')->from(TBLGROUP)->where('department','=',$group_name)->where('gcompany_id','=',$company_id)->where('aid','!=',$acc_id)
			->execute()
			->as_array();
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}
	
	public static function checkgroupname($group_name,$acc_id)
	{
		$company_id = $_SESSION['company_id'];
		// Check if the username already exists in the database		
		$result = DB::select('department')->from(TBLGROUP)->where('department','=',$group_name)->where('gcompany_id','=',$company_id)->where('aid','=',$acc_id)
			->execute()
			->as_array();	
			
			if(count($result) > 0)
			{
				return false;
			}
			else
			{
				return true;		
			}
	}
	
	public static function checkgrouplimit($limit,$acc_id)
	{
		// Check if the limit already exists in the database		
		$result = DB::select('limit')->from(TBLGROUPACCOUNT)->where('aid','=',$acc_id)
			->execute()
			->as_array();
			
		$query = "SELECT sum(`limit`) as totallimit FROM ".TBLGROUP." where aid=".$acc_id."";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		
		foreach($results as $vals)
		{
			$totalusedlimit = $vals['totallimit'];
		}
		foreach($result as $val)
		{
			$acc_limit = $val['limit'];
		}
		if($totalusedlimit > 0) 
		{
			$acc_limit = $acc_limit - $totalusedlimit;
		}
		else
		{
			$acc_limit = $acc_limit;
		}
		if($limit > $acc_limit)
		{
			return false;
		}
		else
		{			
			return true;		
		}
	}
	
	public static function checkgroupreachlimit($acc_id)
	{
		// Check if the limit already exists in the database		
		$result = DB::select('limit')->from(TBLGROUPACCOUNT)->where('aid','=',$acc_id)
			->execute()
			->as_array();
			
		$query = "SELECT sum(`limit`) as totallimit FROM ".TBLGROUP." where aid=".$acc_id."";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		
		foreach($results as $vals)
		{
			$totalusedlimit = $vals['totallimit'];
		}
		foreach($result as $val)
		{
			$acc_limit = $val['limit'];
		}
		
		if($totalusedlimit == $acc_limit)
		{
			return 0;
		}
		else
		{			
			return 1;		
		}
	}
	
	public static function get_account_limit($acc_id)
	{
		// Check if the limit already exists in the database
		$result = DB::select('limit')->from(TBLGROUPACCOUNT)->where('aid','=',$acc_id)
			->execute()
			->as_array();
			return $result;
	}
	
	public static function addgroups($post)
	{
		$companyid = $_SESSION['company_id'];
		$result = DB::insert(TBLGROUP, array('aid','gcompany_id','limit','department'))
					->values(array($post['acc_id'],$companyid,$post['limit'],$post['group_name']))
					->execute();

		if($result)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	public function groupdetails($gid)
	{
		$user_createdby = $_SESSION['userid'];
		$result = DB::select()->from(TBLGROUP)
			->where('gid','=',$gid)
			->order_by('department','ASC')
			->execute()
			->as_array();
		  return $result;
		   
	}
	
	public static function editgroup($gid,$post)
	{
		$status = isset($post['status'])?$post['status']:'0';
		$user_createdby = $_SESSION['userid'];	
		$company_id = $_SESSION['company_id'];	

		$result=DB::update(TBLGROUP)->set(array('department'=>$post['group_name'],'gcompany_id'=>$company_id,'limit'=>$post['limit'],'status'=>$status))
				->where('gid','=',$gid)
				->execute();	

		if($result)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	public function delete_groups($group_id) {
			$user_createdby = $_SESSION['userid'];	
			$result = DB::delete(TBLGROUP)
				-> where('gid', '=',$group_id)
				-> execute();
			return $result;
	}
	
	public static function getgroup_details_for_user($accountid)	
	{
		$query = "SELECT * FROM ".TBLGROUP." where aid='$accountid' ORDER BY `department` ASC";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return $results;
	}
	
	public function validate_addusers($arr) 
	{
		return Validation::factory($arr)       
			->rule('firstname', 'not_empty')
			->rule('firstname', 'min_length', array(':value', '4'))
			//->rule('firstname', 'max_length', array(':value', '32'))
			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('phone', 'not_empty')
			->rule('group_name', 'not_empty');
	}
	
	public function validate_dispatchsetting($arr) 
	{

		return Validation::factory($arr)       
		
		->rule('labelname', 'not_empty');
	}
	
	public static function addusers($post,$random_key)
	{
		$password_key = text::random($type = 'alnum', $length = 7);
		$password = Html::chars(md5($password_key));

		$cdate = date('Y-m-d').' 00:00:00';

		$company_id = $_SESSION['company_id'];
		$send_mail = 'N';
		$result = '';		
		if($post['passenger_id'] == '')
		{
			$presult = DB::insert(PASSENGERS, array('name','email','password','org_password','phone','activation_key','created_date','login_status','user_status','passenger_cid'))
		->values(array($post['firstname'],$post['email'],$password,$password_key,$post['phone'],$random_key,$cdate,'D', ACTIVE,$company_id))
		->execute();
			$last_insert_id = $presult[0];
			$send_mail = 'S';
		}
		else
		{
			$last_insert_id = $post['passenger_id'];
		}
		
			
		$groupid = $post['group_name'];
		$query = "SELECT * FROM ".TBLGROUP." where gid='$groupid'"; 
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		foreach($results as $passval)
		{
			$passenger_id = $passval['passenger_id'];
		}
		
		if($passenger_id == 0 )
		{
			$check_passid = $post['passenger_id'];
			$user_query = " select * from " . TBLGROUP . " where FIND_IN_SET ('$check_passid',passenger_id) and gid='$groupid'";
			$user_result = Db::query(Database::SELECT, $user_query)
					->execute()
					->as_array();

			if(count($user_result) == 0)
			{
				$result=DB::update(TBLGROUP)->set(array('passenger_id'=>$last_insert_id))
				->where('gid','=',$groupid)
				->execute();
			}
		}
		else
		{
			$check_passid = $post['passenger_id'];
			$user_query = " select * from " . TBLGROUP . " where FIND_IN_SET ('$check_passid',passenger_id) and gid='$groupid'";
			$user_result = Db::query(Database::SELECT, $user_query)
					->execute()
					->as_array();
		
			if(count($user_result) == 0)
			{
				$last_insert_id = $passenger_id.','.$last_insert_id;
				$result=DB::update(TBLGROUP)->set(array('passenger_id'=>$last_insert_id))
				->where('gid','=',$groupid)
				->execute();
			}
		}

		$req_result['send_mail'] = $send_mail;
		$req_result['result'] = $result;

		return $req_result;
	}


	public static function editusers($post,$random_key,$userid='')
	{
		$password_key = text::random($type = 'alnum', $length = 7);
		$password = Html::chars(md5($password_key));

		$cdate = date('Y-m-d').' 00:00:00';

		$company_id = $_SESSION['company_id'];
		$send_mail = 'N';
		$result = '';	

		if($userid)
		{

			$groupid = $post['group_name'];

			$find_query = " select * from " . TBLGROUP . " where gid='$groupid'";
			$find_result = Db::query(Database::SELECT, $find_query)
					->execute()
					->as_array();

			$find_passengerid = $find_result[0]['passenger_id'];
			
			if($find_passengerid !='')
			{
				$user_list = explode(',',$find_passengerid);

				if(count($user_list) > 1)
				{
					$user_array = array();
					$user_array[] = $userid;

					$diff_array = array_diff($user_list,$user_array);

					$insert_array = implode(',',$diff_array);

					$result=DB::update(TBLGROUP)->set(array('passenger_id'=>$insert_array))
						->where('gid','=',$groupid)
						->execute();
				}
			}

		}



		if($post['passenger_id'] == '')
		{
			$presult = DB::insert(PASSENGERS, array('name','email','password','org_password','phone','activation_key','created_date','login_status','user_status','passenger_cid'))
		->values(array($post['firstname'],$post['email'],$password,$password_key,$post['phone'],$random_key,$cdate,'D', ACTIVE,$company_id))
		->execute();
			$last_insert_id = $presult[0];
			$send_mail = 'S';
		}
		else
		{
			$last_insert_id = $post['passenger_id'];
		}
		
			
		$groupid = $post['group_name'];
		$query = "SELECT * FROM ".TBLGROUP." where gid='$groupid'"; 
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		foreach($results as $passval)
		{
			$passenger_id = $passval['passenger_id'];
		}
		
		if($passenger_id == 0)
		{
			$check_passid = $post['passenger_id'];
			$user_query = " select * from " . TBLGROUP . " where FIND_IN_SET ('$check_passid',passenger_id) and gid='$groupid'";
			$user_result = Db::query(Database::SELECT, $user_query)
					->execute()
					->as_array();

			if(count($user_result) == 0)
			{
				$result=DB::update(TBLGROUP)->set(array('passenger_id'=>$last_insert_id))
				->where('gid','=',$groupid)
				->execute();
			}
		}
		else
		{
			$check_passid = $post['passenger_id'];
			$user_query = " select * from " . TBLGROUP . " where FIND_IN_SET ('$check_passid',passenger_id) and gid='$groupid'";
			$user_result = Db::query(Database::SELECT, $user_query)
					->execute()
					->as_array();
		
			if(count($user_result) == 0)
			{
				$last_insert_id = $passenger_id.','.$last_insert_id;
				$result=DB::update(TBLGROUP)->set(array('passenger_id'=>$last_insert_id))
				->where('gid','=',$groupid)
				->execute();
			}
		}

		$req_result['send_mail'] = $send_mail;
		$req_result['result'] = $result;

		return $req_result;
	}

	/** Get Passengers booked and cancelled logs **/
	public function get_booking_log_details($userid="",$status="",$driver_reply="",$createdate="",$start=null,$limit=null,$company_id)
	{
		if($company_id == '')
		{
				$current_time =	date('Y-m-d H:i:s');
				$start_time = date('Y-m-d').' 00:00:01';
				$end_time = date('Y-m-d').' 23:59:59';
		}	
		else
		{
			$timezone_base_query = "select time_zone from  company where cid='$company_id' "; 
			$timezone_fetch = Db::query(Database::SELECT, $timezone_base_query)
					->execute()
					->as_array();			

			if($timezone_fetch[0]['time_zone'] != '')
			{
				$current_time = convert_timezone('now',$timezone_fetch[0]['time_zone']);
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
		$company_condition="";
		if($company_id != ""){
			$company_condition = " AND ".PASSENGERS_LOG.".company_id = '$company_id'";
		}
				
		$condition="";
		if($createdate == 0){ $condition = "AND ".PASSENGERS_LOG.".createdate >='".$start_time."'"; }
		$sql = "SELECT *,(select concat(name,' ',lastname) from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as drivername,(select phone from ".PEOPLE." where id=".PASSENGERS_LOG.".driver_id) as driverphone,(select count(*) from ".PASSENGERS_LOG." WHERE passengers_id = '$userid' AND travel_status = '$status' AND (driver_reply = '' or driver_reply = 'C' or driver_reply = 'R')  $condition) as total_count  FROM ".PASSENGERS_LOG." WHERE passengers_id = '$userid' AND travel_status = '$status' AND (driver_reply = '' or driver_reply = 'C' or driver_reply = 'R')  $condition $company_condition  order by passengers_log_id desc LIMIT $start,$limit";     
		//echo $sql;
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();		
	}



	public static function update_dispatchsetting($post)
	{
		$user_createdby = $_SESSION['userid'];
		//echo '<br>';
		$company_id = $_SESSION['company_id'];
		$hide_customer = isset($post['hide_customer'])?$post['hide_customer']:'';
		$hide_droplocation = isset($post['hide_droplocation'])?$post['hide_droplocation']:'';
		$labelname = isset($post['labelname']) ? implode(',',$post['labelname']) : '';
		
		$result = DB::select()->from(TBLALGORITHM)
									->where('alg_company_id','=',$company_id)
									->order_by('aid','desc')
									->limit(1)
									->execute()
									->as_array();

//echo count($result);
		if(count($result) > 0)
		{
			$result=DB::update(TBLALGORITHM)->set(array('alg_created_by'=>$user_createdby,'alg_company_id'=>$company_id,'labelname'=>$labelname,'hide_customer'=>$hide_customer,'hide_droplocation'=>$hide_droplocation))->where('alg_company_id', '=',$company_id)->execute();	
			
		}
		else
		{
			$result = DB::insert(TBLALGORITHM, array('alg_created_by','alg_company_id','labelname','hide_customer','hide_droplocation'))->values(array($user_createdby,$company_id,$labelname,$post['hide_customer'],$post['hide_droplocation']))->execute();
		}
		//print_r($result);
		//exit;

		if($result)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function tdispatch_settings()
	{
		$user_createdby = $_SESSION['userid'];
		$company_id = $_SESSION['company_id'];
		$result = DB::select()->from(TBLALGORITHM)
									->where('alg_company_id','=',$company_id)
									->order_by('aid','desc')
									->limit(1)
									->execute()
									->as_array();
		  return $result;
	}

	public function getgroupuser_details($userid)
	{
		$user_createdby = $_SESSION['userid'];
		$result = DB::select()->from(PASSENGERS)
			->where('id','=',$userid)
			->execute()
			->as_array();
		  return $result;
	}

	public function	getgrouplist($userid)
	{
		$user_createdby = $_SESSION['userid'];
		$companyid = $_SESSION['company_id'];

		$group_query = " select ".TBLGROUP.".gid,".TBLGROUPACCOUNT.".aid,".TBLGROUP.".department from ".TBLGROUP." left join ".TBLGROUPACCOUNT." on ".TBLGROUPACCOUNT.".aid = ".TBLGROUP.".aid  left join ".PASSENGERS." on ".TBLGROUPACCOUNT.".passid = ".PASSENGERS.".id  where FIND_IN_SET ('$userid',passenger_id) and gcompany_id='$companyid' and ".TBLGROUPACCOUNT.".status='1' and ".TBLGROUP.".status='1' and ".PASSENGERS.".user_status='A' ";

		$group_result = Db::query(Database::SELECT, $group_query)
				->execute()
				->as_array();


		  return $group_result;
	}

	public function delete_groupsusers($groupid,$userid)
	{
		$user_createdby = $_SESSION['userid'];
		$companyid = $_SESSION['company_id'];
		$user_array = array();
		$user_array[0] = $userid;

		$group_query = " select * from " . TBLGROUP . " where gcompany_id='$companyid' and gid='$groupid'";

		$group_result = Db::query(Database::SELECT, $group_query)
				->execute()
				->as_array();

		if(count($group_result) > 0)
		{
			$group_usersid = $group_result[0]['passenger_id'];

			$user_list = explode(',',$group_usersid);

			if(in_array($userid,$user_list))
			{
				$new_array = array_diff($user_list,$user_array);	
				$new_set = implode(',',$new_array);

				$result=DB::update(TBLGROUP)->set(array('passenger_id'=>$new_set))
				->where('gid','=',$groupid)
				->execute();

			}


			return 1;
		}
	}

	public static function getgroup_details_bycompany($company_id)	
	{
		$query = "SELECT * FROM ".TBLGROUP." where gcompany_id='$company_id' ORDER BY `department` ASC";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return $results;
	}

	public static function get_bookingrecurrentdetails($recurrent_id)	
	{
		$query = "SELECT * FROM ".PASSENGERS_LOG." where recurrent_id='$reurrent_id' and pickup_time = CURDATE() ORDER BY `passengers_log_id` desc limit 0,1";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return $results;
	}


	public static function get_autodispatch($pass_logid)	
	{

			$company_id = $_SESSION['company_id'];
			$common_model = Model::factory('commonmodel');

			$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);

			$startdate_query = "SELECT '$current_datetime'-interval 1 hour as startdate,'$current_datetime'+interval 2 hour as enddate";
			$startdate_result = Db::query(Database::SELECT, $startdate_query)->execute()->as_array();			

			$start_datetime = $startdate_result[0]['startdate']; 
			$end_datetime = $startdate_result[0]['enddate']; 


			//Get the Passenger Log details to assign the driver //
			//(".PASSENGERS_LOG.".pickup_time between '$start_datetime' and '$end_datetime') and 

			$booking_sql = "SELECT * from ".PASSENGERS_LOG." left join ".TBLALGORITHM."  on  ".TBLALGORITHM.".alg_company_id = ".PASSENGERS_LOG.".company_id  where driver_id=0  and passengers_log_id='$pass_logid'";


			$results = Db::query(Database::SELECT, $booking_sql)
			->execute()
			->as_array();

			if(count($results) > 0)
			{
				return $results[0]['passengers_log_id'];
			}
			else
			{	
				return ;	
			}
	}



	public function count_recurrent_booking()
	{
		$user_createdby = $_SESSION['userid'];
		$company_id = $_SESSION['company_id'];

		 $rs = DB::select()->from(RECURR_BOOKING)
					->where('companyid','=',$company_id)
					->order_by('reid','ASC')
					->execute()
					->as_array();
		 return count($rs);
	}

	public function all_recurrent_booking($offset, $val)
	{
		$user_createdby = $_SESSION['userid'];
		$company_id = $_SESSION['company_id'];

		$result = DB::select()->from(RECURR_BOOKING)
			->where('companyid','=',$company_id)
			->order_by('reid','ASC')
			->limit($val)->offset($offset)
			->execute()
			->as_array();
		  return $result;
		   
	}

	public function recurrent_bookingdetails($reid)
	{
		$user_createdby = $_SESSION['userid'];
		$company_id = $_SESSION['company_id'];

		$query = "SELECT *,(select name from ".PASSENGERS." where ".PASSENGERS.".id=".RECURR_BOOKING.".recurrent_passengerid) as passenger_name,(select email from ".PASSENGERS." where ".PASSENGERS.".id=".RECURR_BOOKING.".recurrent_passengerid) as passenger_email,(select phone from ".PASSENGERS." where ".PASSENGERS.".id=".RECURR_BOOKING.".recurrent_passengerid) as passenger_phone,(select min_fare from ".MOTORMODEL." where ".MOTORMODEL.".model_id=".RECURR_BOOKING.".recurrent_modelid) as min_fare FROM ".RECURR_BOOKING." where ".RECURR_BOOKING.".reid = ".$reid." ";


		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return $results;

	}


	public function edit_recurrentbooking($post,$random_key,$reid,$password)
	{	

			$firstname = Html::chars($post['firstname']);
			$pass_logid = '';
			$recurrent_id = $reid;
			$account_id = '0';
			$group_id = '0';
			$startDate = $post['frmdate'];
			$endDate = $post['todate'];

			$send_mail = 'N';	
			$city_id = $post['city_id'];
			$search_city = $post['cityname'];

			if($search_city!='')
			{
				$cityid_query = "select city_id from ".CITY." where ".CITY.".city_name like '%".$search_city."%' limit 0,1"; 
				$cityid_fetch = Db::query(Database::SELECT, $cityid_query)
						->execute()
						->as_array();

			}		
			else
			{
				$cityid_query = "select city_id from ".CITY." where ".CITY.".default=1"; 
				$cityid_fetch = Db::query(Database::SELECT, $cityid_query)
						->execute()
						->as_array();
			}


			if(count($cityid_fetch) == 0)
			{
				$cityid_query = "select city_id from ".CITY." where ".CITY.".default=1"; 
				$cityid_fetch = Db::query(Database::SELECT, $cityid_query)
						->execute()
						->as_array();
			}


			$city_id = $cityid_fetch[0]['city_id'];

			$passenger_id = $post['passenger_id'];
			$company_id = $_SESSION['company_id'];
			$common_model = Model::factory('commonmodel');

			$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);
			$current_datesplit = explode(' ',$current_datetime);

			$pickup_date = $post['pickup_date'];
			$pickup_time = $post['pickup_time'];			

			$additional_fields = isset($post['addition_fields'])?serialize($post['addition_fields']):'';
			if($pickup_date =='' || $pickup_date =='Today')
			{
				$pickup_date = $current_datesplit[0];
			}

			if($pickup_time =='' || $pickup_time =='Now')
			{
				$pickup_time = $current_datesplit[1];
			}
			
			$pickup_datetime = $pickup_date.' '.$pickup_time;
			
			$userid = $_SESSION['userid'];

			if(isset($post['dispatch']))
			{
				$booktype = '1';
			}
			else
			{
				$booktype = '2';
			}

			if(isset($post['group_id']))
			{
				$group_id = $post['group_id'];
				$company_dispatch = DB::select()->from(TBLGROUP)
					->where('gid','=',$group_id)
					->execute()
					->as_array();

				if(count($company_dispatch) > 0)
				{
					$account_id = $company_dispatch[0]['aid'];
				}
				else
				{
					$account_id = '0';
				}
			}

			if($post['passenger_id'] =='')
			{
			$insert_passenger = DB::insert(PASSENGERS, array('name','email','phone','password','org_password','created_date','activation_key','user_status','passenger_cid','activation_status'))
			->values(array($firstname,$post['email'],$post['phone'],md5($password),$password,$current_datetime,$random_key,ACTIVE,$company_id,1))
			->execute();

			$send_mail = 'S';			
		 	$passenger_id = $insert_passenger[0];

			$user_createdby = $_SESSION['userid'];
			$company_id = $_SESSION['company_id'];

				/*if(!isset($post['group_id'])) {

				$check_accountexist = DB::select('account_name')->from(TBLGROUPACCOUNT)->where('account_name','=',$firstname)->where('acc_created_by','=',$user_createdby)->execute()->as_array();	
			
				if(count($check_accountexist) == 0)
				{
					/** Insert Account **
					$insert_account = DB::insert(TBLGROUPACCOUNT, array('acc_created_by','acc_company_id','account_name','passid'))->values(array($user_createdby,$company_id,$firstname,$passenger_id))->execute();
					$account_id = $insert_account['0'];
					/** Insert Account **
				}
				else
				{
					/** Insert Account **
					$insert_account = DB::insert(TBLGROUPACCOUNT, array('acc_created_by','acc_company_id','account_name','passid'))->values(array($user_createdby,$company_id,$firstname.'_'.$passenger_id,$passenger_id))->execute();
					$account_id = $insert_account['0'];
					/** Insert Account **

				}	


				$check_groupexist = DB::select('department')->from(TBLGROUP)->where('department','=',$firstname)->where('gcompany_id','=',$company_id)->execute()->as_array();	
			
				if(count($check_groupexist) == 0)
				{
					/** Insert Group **
					$insert_group = DB::insert(TBLGROUP, array('aid','gcompany_id','passenger_id','department'))->values(array($insert_account[0],$company_id,$passenger_id,$firstname))->execute();
					$group_id = $insert_group['0'];
					/** Insert Group **
				}
				else
				{
					/** Insert Group **
					$insert_group = DB::insert(TBLGROUP, array('aid','gcompany_id','passenger_id','department'))->values(array($insert_account[0],$company_id,$passenger_id,$firstname.'_'.$passenger_id))->execute();
					$group_id = $insert_group['0'];
					/** Insert Group **

				}


				} */

			}

				
				/** Insert Recurrent Table **/

				if(isset($post['daysofweek'])) { 
					$days = serialize($post['daysofweek']);
				}
				else
				{
					$days = '';
				}

				$exclude_dates = '';


				if(isset($post['datepicker_status']) && ($post['datepicker_status'] == 1))
				{
					if($post['all_dates'] !='')
					{
						$specific_dates = explode(',',$post['all_dates']);
						$specific_dates = !empty($specific_dates)?serialize($specific_dates):'';
					}
					else
					{
						$specific_dates = '';
					}	
				}
				else
				{
					if($post['define_date'] !='')
					{
						$specific_dates = explode(',',$post['define_date']);
						$specific_dates = !empty($specific_dates)?serialize($specific_dates):'';
					}
					else
					{
						$specific_dates = '';
					}
				}


				/** update query  for Recurrent Booking **/
				$updatequery = " UPDATE ".RECURR_BOOKING." SET labelname ='".$post['labelname']."',frmdate='".$post['frmdate']."',todate='".$post['todate']."',days='".$days."',excludedates='".$exclude_dates."',specific_dates='".$specific_dates."',companyid='".$company_id."',recurrent_passengerid='$passenger_id',recurrent_pickuplocation='".$post['current_location']."',recurrent_pickuplatitude='".$post['pickup_lat']."',recurrent_pickuplongitude='".$post['pickup_lng']."',recurrent_droplocation='".$post['drop_location']."',recurrent_droplatitude='".$post['drop_lat']."',recurrent_droplongitude='".$post['drop_lng']."',recurrent_noofpassengers='".$post['no_passengers']."',recurrent_approxdistance='".$post['distance_km']."',recurrent_approxduration='".$post['total_duration']."',recurrent_approxfare='".$post['total_fare']."',recurrent_city='".$city_id."',recurrent_pickuptime='".$pickup_time."',recurrent_fixedprice='".$post['fixedprice']."',recurrent_faretype='".$post['payment_type']."',recurrent_luggage='".$post['luggage']."',recurrent_operatorid='".$userid."',recurrent_additionalfields='".$additional_fields."',recurrent_modelid='".$post['taxi_model']."',recurrent_accountid='".$account_id."',recurrent_groupid='".$group_id."'    WHERE reid ='".$reid."'";						
				$updateresult = Db::query(Database::UPDATE, $updatequery)
				->execute();

								

				/** update query  for Recurrent Booking **/
				/*
				// Delete all booking which is greater than 24 hrs //
		
				$delete_query = "Delete from ".PASSENGERS_LOG." WHERE pickup_time > NOW() + INTERVAL 24 HOUR and recurrent_id ='".$recurrent_id."'";
				$delete_result = Db::query(Database::DELETE, $delete_query)->execute();

				$date_query = "SELECT now()+interval 24 hour as check_datetime";
				$date_result = Db::query(Database::SELECT, $date_query)->execute();
				$check_datetime = $date_result[0]['check_datetime'];

				// Delete all booking which is greater than 24 hrs //

			// Change startDate //
			if(strtotime($endDate) > strtotime($check_datetime))
			{

				// Days wise entry in Booking Log Table //

				if(isset($post['daysofweek']))
				{	
					//$startDate = $post['frmdate'];
					$startDate = $check_datetime;

					$endDate = $post['todate'];


					$endDate = strtotime($endDate);
					$range_of_dates = array();
					$remove_date = array();

					function toDate($x){return date('Y-m-d', $x);}


					if(isset($post['exclus_start']) && isset($post['exclus_end']))
					{
						$combine_dates = array_combine($post['exclus_start'],$post['exclus_end']);
						$i = 0;
						foreach($combine_dates as $key => $value)
						{
							$st_date = $key;
							$ed_date = $value;
							$dates = range(strtotime($st_date), strtotime($ed_date),86400);
							$range_of_dates[$i] = array_map("toDate", $dates);
							$i++;
						}

						// Multi dimensional array to single Array //	
						foreach ($range_of_dates as $val)
						 {
						    foreach($val as $val2)
						     {
							$remove_date[] = $val2;
						     }
						 }
						// Multi dimensional array to single Array //

					}


					foreach($post['daysofweek'] as $key => $value)
					{
						
						for($i = strtotime($value, strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i))
						{
						    //echo date('l Y-m-d', $i);
						    $booking_date = date('Y-m-d', $i);
						    $check_date = date('d', $i);	
						    $check_month = date('m', $i);	
						    $check_year = date('Y', $i);		
						    // check valid date or not and check exclus date //	



						    if(checkdate($check_month,$check_date,$check_year) && !in_array($booking_date,$remove_date))		
						    {	
								$insert_booking = 'S';
									
								$pickup_datetime = $booking_date.' '.$pickup_time;
								$account_id = isset($account_id)?$account_id:'0';
								$group_id = isset($group_id)?$group_id:'0';

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

								$result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude',
'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','faretype','fixedprice','bookingtype','luggage','bookby',
'operator_id','additional_fields','travel_status','taxi_modelid','recurrent_type','recurrent_id','company_tax','account_id','accgroup_id'))->values(array($booking_key,$passenger_id,$company_id,$post['current_location'],$post['pickup_lat'],$post['pickup_lng'],$post['drop_location'],$post['drop_lat'],$post['drop_lng'],$pickup_datetime,$post['no_passengers'],$post['distance_km'],$post['total_duration'],$post['total_fare'],$post['payment_type'],$post['fixedprice'],$booktype,$post['luggage'],'2',$userid,$additional_fields,'0',$post['model_id'],'2',$recurrent_id,$post['company_tax'],$account_id,$group_id))
								->execute();								
								
						    } 							
						    // check valid date or not and check exclus date //	
						}
					}
				}

				// Days wise entry in Booking Log Table //

			}
			// Change startDate //


			// Change startDate //
			if(strtotime($endDate) > strtotime($check_datetime))
			{

			if($insert_booking == 'N')
			{
				// Date wise entry in Booking Log Table //

				if(isset($post['monthDays']))
				{

					//$startDate = $post['frmdate'];
					$startDate = $check_datetime;
					$endDate = $post['todate'];

					$start_split = explode('-',$startDate);
					$split_startyear = $start_split['0'];
					$split_startmonth = $start_split['1'];
					$split_startdate= $start_split['2'];

					$end_split = explode('-',$endDate);
					$split_endyear = $end_split['0'];
					$split_endmonth = $end_split['1'];
					$split_enddate= $end_split['2'];

					$booking_date = array();

					$i = 0;
					foreach($post['monthDays'] as $key => $value)
					{
						for($j = $split_startmonth; $j <= $split_endmonth; $j++ )
						{	
							$mn = strlen($j) > 1 ? $j : "0".$j;	

							for($z=$split_startyear; $z <= $split_endyear; $z++)
							{
								$valid_date = $z.'-'.$mn.'-'.$value;
									
								if(strtotime($valid_date) > strtotime(date('Y-m-d')) )
								{
									if(checkdate($mn,$value,$z))
									{	
										$booking_date[] = $value.'-'.$mn.'-'.$z;
									}	
								}
							}


						} 	
						$i++;						
					}

			
					$range_of_dates = array();
					$remove_date = array();

					function toDate($x){return date('Y-m-d', $x);}

					if(isset($post['exclus_start']) && isset($post['exclus_end']))
					{
						$combine_dates = array_combine($post['exclus_start'],$post['exclus_end']);
						$i = 0;
						foreach($combine_dates as $key => $value)
						{
							$st_date = $key;
							$ed_date = $value;
							$dates = range(strtotime($st_date), strtotime($ed_date),86400);
							$range_of_dates[$i] = array_map("toDate", $dates);
							$i++;
						}

						// Multi dimensional array to single Array //	
						foreach ($range_of_dates as $val)
						 {
						    foreach($val as $val2)
						     {
							$remove_date[] = $val2;
						     }
						 }
						// Multi dimensional array to single Array //

					}


					foreach($booking_date as $key => $value)
					{
						
					           // check valid date or not and check exclus date //	

						    if(!in_array($value,$remove_date))		
						    {
								$insert_booking = 'S';
								$pickup_datetime = $value.' '.$pickup_time;
								$account_id = isset($account_id)?$account_id:'0';
								$group_id = isset($group_id)?$group_id:'0';


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

								$result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude',
'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','faretype','fixedprice','bookingtype','luggage','bookby',
'operator_id','additional_fields','travel_status','taxi_modelid','recurrent_type','recurrent_id','company_tax','account_id','accgroup_id'))->values(array($booking_key,$passenger_id,$company_id,$post['current_location'],$post['pickup_lat'],$post['pickup_lng'],$post['drop_location'],$post['drop_lat'],$post['drop_lng'],$pickup_datetime,$post['no_passengers'],$post['distance_km'],$post['total_duration'],$post['total_fare'],$post['payment_type'],$post['fixedprice'],$booktype,$post['luggage'],'2',$userid,$additional_fields,'0',$post['model_id'],'2',$recurrent_id,$post['company_tax'],$account_id,$group_id))
								->execute();								
								
						    } 							
						    // check valid date or not and check exclus date //	
						
					}
				}

				// Date wise entry in Booking Log Table //

			}

			}


			// Change startDate //
			if(strtotime($endDate) > strtotime($check_datetime))
			{

				if($insert_booking == 'N')
				{
				// Days wise entry in Booking Log Table //
				$daysofweek = array();
				$daysofweek[0] = 'MON';
				$daysofweek[1] = 'TUE';
				$daysofweek[2] = 'WED';
				$daysofweek[3] = 'THU';
				$daysofweek[4] = 'FRI';
				$daysofweek[5] = 'SAT';
				$daysofweek[6] = 'SUN';


				if(isset($daysofweek))
				{
					//$startDate = $post['frmdate'];
					$endDate = $post['todate'];
					$startDate = $check_datetime;
					$endDate = strtotime($endDate);
					$range_of_dates = array();
					$remove_date = array();

					function toDate($x){return date('Y-m-d', $x);}


					if(isset($post['exclus_start']) && isset($post['exclus_end']))
					{
						$combine_dates = array_combine($post['exclus_start'],$post['exclus_end']);
						$i = 0;
						foreach($combine_dates as $key => $value)
						{
							$st_date = $key;
							$ed_date = $value;
							$dates = range(strtotime($st_date), strtotime($ed_date),86400);
							$range_of_dates[$i] = array_map("toDate", $dates);
							$i++;
						}

						// Multi dimensional array to single Array //	
						foreach ($range_of_dates as $val)
						 {
						    foreach($val as $val2)
						     {
							$remove_date[] = $val2;
						     }
						 }
						// Multi dimensional array to single Array //

					}


					foreach($daysofweek as $key => $value)
					{
						
						for($i = strtotime($value, strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i))
						{
						    //echo date('l Y-m-d', $i);
						    $booking_date = date('Y-m-d', $i);
						    $check_date = date('d', $i);	
						    $check_month = date('m', $i);	
						    $check_year = date('Y', $i);		
						    // check valid date or not and check exclus date //	

						    if(checkdate($check_month,$check_date,$check_year) && !in_array($booking_date,$remove_date))		
						    {	
								$insert_booking = 'S';
									
								$pickup_datetime = $booking_date.' '.$pickup_time;
								$account_id = isset($account_id)?$account_id:'0';
								$group_id = isset($group_id)?$group_id:'0';


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

								$result = DB::insert(PASSENGERS_LOG, array('booking_key','passengers_id','company_id','current_location','pickup_latitude','pickup_longitude','drop_location','drop_latitude','drop_longitude',
'pickup_time','no_passengers','approx_distance','approx_duration','approx_fare','faretype','fixedprice','bookingtype','luggage','bookby',
'operator_id','additional_fields','travel_status','taxi_modelid','recurrent_type','recurrent_id','company_tax','account_id','accgroup_id'))->values(array($booking_key,$passenger_id,$company_id,$post['current_location'],$post['pickup_lat'],$post['pickup_lng'],$post['drop_location'],$post['drop_lat'],$post['drop_lng'],$pickup_datetime,$post['no_passengers'],$post['distance_km'],$post['total_duration'],$post['total_fare'],$post['payment_type'],$post['fixedprice'],$booktype,$post['luggage'],'2',$userid,$additional_fields,'0',$post['model_id'],'2',$recurrent_id,$post['company_tax'],$account_id,$group_id))
								->execute();								
								
						    } 							
						    // check valid date or not and check exclus date //	
						}
					}
				}

				// Days wise entry in Booking Log Table //
				}

			}
			// Change startDate //

			
			if(isset($post['dispatch']))
			{
				$company_id = $_SESSION['company_id'];
				$company_dispatch = DB::select()->from(TBLALGORITHM)
					->where('alg_company_id','=',$company_id)
					->execute()
					->as_array();

				if(count($company_dispatch) > 0)	
				{
					$tdispatch_type = $company_dispatch[0]['labelname'];
					$match_vehicletype = $company_dispatch[0]['match_vehicletype'];
					$hide_customer = $company_dispatch[0]['hide_customer'];
					$hide_droplocation = $company_dispatch[0]['hide_droplocation'];
					$hide_fare = $company_dispatch[0]['hide_fare'];
					// Auto Dispatch //
					
					if(isset($result[0]))					
					{
						$pass_logid = $result[0];	
					}
					else
					{
						$pass_logid = $this->get_bookingrecurrentdetails($insert_recurr[0]);
					}
	
					if($tdispatch_type == 2 && $pass_logid !='')
					{


						$booking_details = $this->get_bookingdetails($pass_logid);

						$latitude = $booking_details[0]["pickup_latitude"];
						$longitude = $booking_details[0]["pickup_longitude"];
						$miles = '';
						$no_passengers = $booking_details[0]["no_passengers"];
						$taxi_fare_km = $booking_details[0]["min_fare"];
						if($match_vehicletype == 1)
						{
						$taxi_model = $booking_details[0]["taxi_modelid"];
						}
						else
						{
						$taxi_model = '';	
						}
						$taxi_type = ''; 
						$maximum_luggage = $booking_details[0]["luggage"];
						$company_id = $booking_details[0]["company_id"];
						$cityname = '';			
						$search_driver = '';		

						$driver_details = $this->search_driver_location($latitude,$longitude,$miles,$no_passengers,$_REQUEST,$taxi_fare_km,$taxi_model,$taxi_type,$maximum_luggage,$cityname,$pass_logid,$company_id,$search_driver);

						if(count($driver_details) > 0)
						{
							$driver_id = $driver_details[0]['driver_id'];
							$taxi_id = $driver_details[0]['id'];

							$updatequery = " UPDATE ".PASSENGERS_LOG." SET driver_id='".$driver_id."',taxi_id='".$taxi_id."',travel_status='7',driver_reply='',msg_status='U' wHERE passengers_log_id ='". $pass_logid."'";						
							$updateresult = Db::query(Database::UPDATE, $updatequery)
				   			 ->execute();

							

						$passenger_logid = $pass_logid;

						$passenger_details = $this->get_bookingdetails($pass_logid);
						$driver_details = $this->get_driver_profile_details($driver_id);
						
						// Create Log //	
						$company_id = $_SESSION['company_id'];			
						$user_createdby = $_SESSION['userid'];			
						$log_message = __('log_message_recurrent_added');
						$log_message = str_replace("RECURRENT_ID",$recurrent_id,$log_message); 
						$log_status = $this->create_logs($recurrent_id,$company_id,$user_createdby,$log_message);
	
						?>
						<script type="text/javascript">load_logcontent();</script>
						<?php 
						// Create Log //

						/*
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

						$device_id = $driver_details[0]['device_id'];
						$device_token = $driver_details[0]['device_token'];								
						$device_type = $driver_details[0]['device_type'];

						$booking_details = array(
						"pickup"=>$passenger_details[0]['current_location'],
						"drop"=>$passenger_details[0]['drop_location'],
						"no_of_passengers"=>$passenger_details[0]['no_passengers'],
						"pickup_time"=>$passenger_details[0]['pickup_time'],
						"driver_id"=>$passenger_details[0]['driver_id'],
						"passenger_id"=>'',
						"roundtrip"=>'',
						"passenger_phone"=>$passenger_details[0]['passenger_phone']
						);
						// Hide Customer //
						if($hide_customer == 1)
						{
							$booking_details = array(
							"pickup"=>$passenger_details[0]['current_location'],
							"drop"=>$passenger_details[0]['drop_location'],
							"no_of_passengers"=>$passenger_details[0]['no_passengers'],
							"pickup_time"=>$passenger_details[0]['pickup_time'],
							"driver_id"=>$passenger_details[0]['driver_id'],
							"passenger_id"=>'',
							"roundtrip"=>''
							);


						}
						// Hide Droplocation //
						if($hide_droplocation == 1)
						{
							$booking_details = array(
							"pickup"=>$passenger_details[0]['current_location'],
							"no_of_passengers"=>$passenger_details[0]['no_passengers'],
							"pickup_time"=>$passenger_details[0]['pickup_time'],
							"driver_id"=>$passenger_details[0]['driver_id'],
							"passenger_id"=>'',
							"roundtrip"=>'',
							"passenger_phone"=>$passenger_details[0]['passenger_phone']
							);

						}


						$msg = array("message" => 'You have new trip request.Kindly response the request',"status" => 1,"Passenger_logid"=>$pass_logid,"booking_details" =>$booking_details,"notification_time"=>$notification_time);

						//Message::success(__('save_booking_success'));

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
					}
	
					
					}
					// Auto Dispatch //

				}
				
			}
			*/
			$req_result['send_mail'] = $send_mail;	
			$req_result['recurrent_id'] = $recurrent_id;

			return $req_result;
	}


	public static function recurrent_details($reid)
	{
		$user_createdby = $_SESSION['userid'];
		$company_id = $_SESSION['company_id'];

		$result = DB::select('*')->from(RECURR_BOOKING)->where('reid','=',$reid)->where('companyid','=',$company_id)
			->execute()
			->as_array();	
		
		return $result;
	}

	public function delete_recurrentbooking($reid) {

			$user_createdby = $_SESSION['userid'];	
			$company_id = $_SESSION['company_id'];

			$result = DB::delete(RECURR_BOOKING)->where('reid', '=',$reid)->where('companyid','=',$company_id)-> execute();
			return $result;
	}

	public function check_grouplimit($group_id,$limit)
	{
		$user_createdby = $_SESSION['userid'];
		$company_id = $_SESSION['company_id'];
		$total_used_limit = '';
		$result = DB::select('*')->from(TBLGROUP)->where('gid','=',$group_id)->where('status','=','1')->where('gcompany_id','=',$company_id)->execute()->as_array();	
		if(count($result) > 0)
		{

			$passenger_id_array = explode(',',$result[0]['passenger_id']);
			$group_limit = $result[0]['limit'];
			foreach($passenger_id_array as $passenger_id)
			{
				$bal_account_limit = $this->check_groupused_limit($passenger_id,$group_id);
				if(count($bal_account_limit)>0)
				{
					if($bal_account_limit[0]['total_used_limit'] >0)
					{
						$total_used_limit += $bal_account_limit[0]['total_used_limit'];
					}
				}	
			}

			$bal_limit = $group_limit - $total_used_limit;	

			if($limit > $bal_limit)
			{
				return 'N';		
			}
			else
			{
				return 'S';
			}
		}
		else
		{
			return 'N';
		}		

	}


	public function check_groupused_limit($passengers_id,$group_id)
	{
		$sql = "SELECT sum(".TRANS.".credits_used) as total_used_limit FROM ".PASSENGERS_LOG." JOIN ".PASSENGERS." ON ( ".PASSENGERS_LOG.".`passengers_id` = ".PASSENGERS.".`id` ) JOIN ".TRANS." ON ( ".TRANS.".`passengers_log_id` = ".PASSENGERS_LOG.".`passengers_log_id` ) WHERE ".PASSENGERS_LOG.".`passengers_id` = '$passengers_id' and accgroup_id='$group_id'";

		$user_result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
		return $user_result;	
	}

	public function get_citymodel_fare_details($model_id="",$city_name="",$city_id='',$company_id='')
	{		
		if($city_name!='')
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".city_name like '%".$city_name."%' limit 0,1"; 
		}		
		elseif($city_id!='')
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".city_id='$city_id'"; 
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
	

		if(FARE_SETTINGS == 2 && !empty($company_id))
		{
			$sql = "SELECT 
			(SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,
			(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,
			(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,
			(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,
			(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km,
			company_model_fare.night_charge,company_model_fare.night_timing_from,company_model_fare.night_timing_to,
			(SUM(company_model_fare.night_fare)*($city_model_fare)/100) + company_model_fare.night_fare as night_fare,
			(SUM(company_model_fare.minutes_fare)*($city_model_fare)/100) + company_model_fare.minutes_fare as minutes_fare,
company_model_fare.waiting_time,company_model_fare.min_km,company_model_fare.below_above_km FROM  ".COMPANY_MODEL_FARE." as company_model_fare WHERE company_model_fare.company_cid='$company_id' and company_model_fare.`model_id` = '$model_id'";
		}
		else
		{
			$sql = "SELECT (SUM(model.base_fare)*($city_model_fare)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*($city_model_fare)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*($city_model_fare)/100) + model.below_km as below_km,(SUM(model.above_km)*($city_model_fare)/100) + model.above_km as above_km,model.night_charge,model.night_timing_from,model.night_timing_to,
			(SUM(model.night_fare)*($city_model_fare)/100) + model.night_fare as night_fare,
			(SUM(model.minutes_fare)*($city_model_fare)/100) + model.minutes_fare as minutes_fare,
			model.waiting_time,model.min_km,model.below_above_km  FROM  ".MOTORMODEL." as model WHERE  model.`model_id` = '$model_id'";
		}
		//echo $sql;
				$result = Db::query(Database::SELECT, $sql)
					->as_object()					
					->execute();						

				return $result;		
	}	

	public static function model_details()
	{
		$company_id = $_SESSION['company_id'];
		if(FARE_SETTINGS == 2 && !empty($company_id))
		{
			 $model_base_query = "select distinct ".MOTORMODEL.".model_id,".COMPANY_MODEL_FARE.".model_name from ".COMPANY_MODEL_FARE." left join ".MOTORMODEL." on ".MOTORMODEL.".model_id=".COMPANY_MODEL_FARE.".model_id where ".COMPANY_MODEL_FARE.".company_cid='$company_id'"; 

			$result = Db::query(Database::SELECT, $model_base_query)
				->execute()
				->as_array();	

			return $result;
		}
		else
		{
			$result = DB::select('model_id','model_name')->from(MOTORMODEL)->where('model_status','=','A')->order_by('model_name','ASC')
				->execute()
				->as_array();

			return $result;
		}	


	}


	public function update_dispatch_status($id,$msg_status,$driver_reply,$travel_status)
	{
		$sql_query = array(
					'msg_status' =>$msg_status,
					'driver_reply' => $driver_reply,
					'travel_status' => $travel_status					
				);			


				//print_r($sql_query);

		$result = DB::update(PASSENGERS_LOG)->set($sql_query)
				->where('passengers_log_id', '=' ,$id)
				->execute();

	}

	public function complete_payment($data)
	{
	
		$passenger_log_id = $data['pass_logid'];
		$complete_distance = $data['complete_distance'];
		//$complete_waitingtime = $data['complete_waitingtime'];
		//$complete_waitingcost = $data['complete_waitingcost'];
		//$complete_nightcharge = $data['complete_nightcharge'];
		$complete_waitingtime = '';
		$complete_waitingcost = 0;
		$complete_nightcharge = 0;
		$complete_total = $data['complete_total'];
		$complete_remark = $data['complete_remark'];

		$company_id = $_SESSION['company_id'];	
	

		$common_model = Model::factory('commonmodel');	
		$company_tax = $common_model->company_tax($company_id);		 

		$trans_sql = "SELECT * FROM ".TRANS." WHERE passengers_log_id = '$passenger_log_id'";

		$trans_result = Db::query(Database::SELECT, $trans_sql)
			->execute()
			->as_array();	

		$passengerlog_sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE passengers_log_id = '$passenger_log_id'";

		$passengerlog_result = Db::query(Database::SELECT, $passengerlog_sql)
			->execute()
			->as_array();	

		$siteinfo_query = "SELECT admin_commission  FROM ".SITEINFO;
		$siteinfo_details = Db::query(Database::SELECT, $siteinfo_query)
				    ->execute()
				    ->as_array(); 

		$company_paypal_query = "SELECT * FROM ".COMPANYINFO." WHERE company_cid = '$company_id'";

		$company_paypal_details =  Db::query(Database::SELECT, $company_paypal_query)
			->execute()
			->as_array(); 

		// Check Valid Trip or Not//	
		
		if(count($passengerlog_result) == 0)
		{
			return -1;
		}
		else
		{
			// Check Travel Status //
			if( (count($trans_result) > 0) || $passengerlog_result[0]['travel_status'] == 1)
			{
				return -2;
			}	
			else
			{

				$first_query = "select * from " . PACKAGE_REPORT . " join ".PACKAGE." on ".PACKAGE.".package_id =".PACKAGE_REPORT.".upgrade_packageid  where ".PACKAGE_REPORT.".upgrade_companyid = ".$company_id."  order by upgrade_id desc limit 0,1";

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
					

				// Check Totalfare is zero //					
				if($complete_total == 0)
				{
					$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
					$journey = $this->update_dispatch_status($passenger_log_id,$msg_status,$driver_reply,$journey_status);

					$faretype = $passengerlog_result[0]['faretype'];

					$result = DB::insert(TRANS, array('passengers_log_id','distance','actual_distance','tripfare','fare','amt','waiting_cost','waiting_time','company_tax','remarks','payment_type','amt','trans_packtype'))->values(array($passenger_log_id,$complete_distance,$complete_distance,$complete_total,$complete_total,$complete_total,$complete_waitingcost,$complete_waitingtime,$company_tax,$complete_remark,$faretype,$total_fare,$check_package_type))
					->execute();
					
					return 1;
				}
				else
				{
					$faretype = $passengerlog_result[0]['faretype'];

					if($faretype == 3)
					{

						$msg_status = 'R';$driver_reply='A';$journey_status=1;
						$journey = $this->update_dispatch_status($passenger_log_id,$msg_status,$driver_reply,$journey_status);

						$update_commission = $common_model->update_commission($passenger_log_id,$complete_total,$siteinfo_details[0]['admin_commission']);


						$faretype = $passengerlog_result[0]['faretype'];

						$result = DB::insert(TRANS, array('passengers_log_id','distance','actual_distance','tripfare','fare','waiting_cost','waiting_time','company_tax','remarks',
'payment_type','amt','trans_packtype'))->values(array($passenger_log_id,$complete_distance,$complete_distance,$complete_total,$complete_total,$complete_waitingcost,$complete_waitingtime,$company_tax,$complete_remark,$faretype,$complete_total,$check_package_type))
						->execute();
			
						return 1;

					}
					elseif($faretype == 2)
					{

						$passenger_id = $passengerlog_result[0]['passengers_id'];
						
						$get_passenger_query = "select * from ".PASSENGERS." where id='$passenger_id'";
			                        $get_passenger_details = Db::query(Database::SELECT, $get_passenger_query)
					                        ->execute()
					                        ->as_array();
						if($get_passenger_details[0]['creditcard_no'] =='')
						{
							return -3;	
						}
						else
						{

							$creditcard_no = encrypt_decrypt('decrypt',$get_passenger_details[0]['creditcard_no']);
							$expdatemonth = $get_passenger_details[0]['expdatemonth'];
							$expdateyear = $get_passenger_details[0]['expdateyear'];
							$creditcard_cvv = $get_passenger_details[0]['creditcard_cvv'];	
							$passenger_discount = $get_passenger_details[0]['discount'];	
                                                        $email = $get_passenger_details[0]['email'];	
                                                        $name = $get_passenger_details[0]['name'];	
                                                        $phone = $get_passenger_details[0]['phone'];	

							$discount_amt = 0;
							$nightcharge = 0;
							$total_amount = $complete_total;

							if($passenger_discount > 0)
							{
								$discount_amt = ($complete_total * $passenger_discount)/100; 
								$total_amount = $complete_total - $discount_amt;
							}


							$product_title = Html::chars('Complete Trip');
							//$payment_type = 'Authorization';
							$payment_action='sale';

							$request  = 'METHOD=DoDirectPayment';
							$request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
							$request .= '&USER=' . urlencode($company_paypal_details[0]['company_paypal_username']);
							$request .= '&PWD=' . urlencode($company_paypal_details[0]['company_paypal_password']);
							$request .= '&SIGNATURE=' . urlencode($company_paypal_details[0]['company_paypal_signature']);

							$request .= '&CUSTREF=' . (int)$passenger_log_id;
							$request .= '&PAYMENTACTION=' . $payment_action; 
							$request .= '&AMT=' . urlencode($total_amount); 

							$request .= '&ACCT=' . urlencode(str_replace(' ', '',$creditcard_no ));
							$request .= '&EXPDATE=' . urlencode($expdatemonth.$expdateyear);
							$request .= '&CVV2=' . urlencode($creditcard_cvv);
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
							 
							if(isset($nvpArray) && !empty($nvpArray) )
				 			{
								 $ack = strtoupper($nvpArray["ACK"]);		                

								if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
								{

									if($complete_nightcharge > 0)
									{
										$nightcharge = 1;
									}

									if($check_package_type != 'N')
									{
										$admin_amt = ($total_amount * $siteinfo_details[0]['admin_commission'] )/100; //payable to admin
										$admin_amt = round($admin_amt, 2);
										$total_balance = round($total_amount,2);				
	
										//Set Commission to Admin	
										$updatequery = " UPDATE ". PEOPLE ." SET account_balance=account_balance+$total_balance WHERE user_type = 'A'";	

										$updateresult = Db::query(Database::UPDATE, $updatequery)
												->execute();
									}
									else
									{
										$admin_amt = 0;
									}

									$company_amt = $total_amount - $admin_amt; 	
									$company_amt = round($company_amt, 2);	

								     	//Set Commission to Admin	
									$updatequery = " UPDATE ". PEOPLE ." SET account_balance=account_balance+$company_amt WHERE user_type = 'C' and company_id=".$company_id;	

									$updateresult = Db::query(Database::UPDATE, $updatequery)
									->execute();

		
									$current_time = date('Y-m-d H:i:s');

						                        $faretype = $passengerlog_result[0]['faretype'];

			
									$result = DB::insert(TRANS, array('passengers_log_id','distance','actual_distance','tripfare','fare','waiting_cost','waiting_time','company_tax','passenger_discount'
,'remarks','correlation_id','ack','transaction_id','payment_type','order_time','amt','currency_code','payment_status',
'captured','admin_amount','company_amount','trans_packtype','nightfare'))->values(array($passenger_log_id,$complete_distance,$complete_distance,$complete_total,$total_amount,$complete_waitingcost,$complete_waitingtime,$company_tax,$discount_amt,$complete_remark,$nvpArray['CORRELATIONID'],$nvpArray['ACK'],$nvpArray['TRANSACTIONID'],$faretype,$current_time,$total_amount,$nvpArray['CURRENCYCODE'],$nvpArray['ACK'],'1',$admin_amt,$company_amt,$check_package_type,$complete_nightcharge))->execute();

							$msg_status = 'R';$driver_reply='A';$journey_status=1;
							$journey = $this->update_dispatch_status($passenger_log_id,$msg_status,$driver_reply,$journey_status);


				                $orderlist='';   
				                $orderlist='<p><b>'.__('complete_from').'</b></p><p style="padding-left:5px;">'.__('complete_from1').''.$passengerlog_result[0]['current_location'].' </p><p style="padding-left:5px;"> To : '.$passengerlog_result[0]['drop_location'].'</p>'.'<p>'.__('total_cost').':'. CURRENCY.' '.$complete_total.'</p>'.'<p>'.__('transactionid_label').': '.$nvpArray['TRANSACTIONID'].'</p>';

                                                $to = "";                                                        
                      	                    	$mail="";
						$this->emailtemplate = Model::factory('emailtemplate');
		
			                        $replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>SITE_NAME,REPLACE_USERNAME=>$name,REPLACE_EMAIL=>$email,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>SITE_EMAIL_CONTACT,REPLACE_SITEURL=>URL_BASE,REPLACE_ORDERID=>$nvpArray['TRANSACTIONID'],REPLACE_ORDERLIST=>$orderlist,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);

					                        $message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcomplete-mail.html',$replace_variables);


				                                $from = SITE_EMAIL_CONTACT;
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

				                                //free sms url with the arguments
				                                if(SMS == 1)
				                                {
				                                $common_model = Model::factory('commonmodel');	
				                                $message_details = $common_model->sms_message('5');
				                                $to = $phone;
				                                $message = $message_details[0]['sms_description'];
			                                        $message = str_replace("##booking_key##",$passenger_log_id,$message);
		
				                                //$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
				                                }



                                                                return 2;

								}
								else
								{
                                                                        return __('payment_failed_due').' '.$nvpArray['L_LONGMESSAGE0'];

								}	

							}
							
						}	


					}	
                                        else if($faretype == 1)
                                        {

						$passenger_id = $passengerlog_result[0]['passengers_id'];
						$account_id = $passengerlog_result[0]['account_id'];
						$accgroup_id = $passengerlog_result[0]['accgroup_id'];

						if($account_id != 0)
						{
							$account_details = $this->get_dispatchaccount_discount($account_id);
							//print_r($get_passenger_profile);
							//print_r($account_discount);
							if(count($account_details)>0)
							{
							$account_holder_id = $account_details[0]['passid'];

							$get_passenger_profile = $this->get_passenger_profile($account_holder_id,$company_id);						
	
							$account_holder_status = $get_passenger_profile[0]['user_status'];
							if($account_holder_status == 'A')
							{
								$creditcard_no = $get_passenger_profile[0]['creditcard_no'];
                                                                $expdatemonth = $get_passenger_profile[0]['expdatemonth'];
                                                                $expdateyear = $get_passenger_profile[0]['expdateyear'];
                                                                $creditcard_cvv = $get_passenger_profile[0]['creditcard_cvv'];	

								//echo $creditcard_no;exit;
								if($creditcard_no !="")
								{									
									$account_limit=$grp_limit="";//exit;
									$total_used_limit=$bal_limit=0;
                                                                        $account_limit = $account_details[0]['limit'];															
                                                                        $grouplimit = $this->get_tdipatch_groupdetails($accgroup_id);
										if(count($grouplimit) > 0)
										{
											$grp_limit = $grouplimit[0]['limit'];
											if($grp_limit > 0)
											{
												$passenger_id_array = explode(',',$grouplimit[0]['passenger_id']);
												
												foreach($passenger_id_array as $passenger_id)
												{
													$bal_account_limit = $this->check_account_usedlimit($passenger_id);
													if(count($bal_account_limit)>0)
													{
														if($bal_account_limit[0]['total_used_limit'] >0)
														{
															$total_used_limit += $bal_account_limit[0]['total_used_limit'];
														}
													}	
												}	
												//echo $grp_limit;
												$bal_limit = $grp_limit - $total_used_limit;
												//echo $bal_limit;
												//echo $complete_total;
												//exit;
												if($bal_limit > $complete_total)
												{


                                                                                                if($creditcard_no =='')
                                                                                                {
                                                                                                return -3;	
                                                                                                }
                                                                                                else
                                                                                                {

                                                                                                $creditcard_no = encrypt_decrypt('decrypt',$creditcard_no);
                                                                                                
                                                                                                $discount_amt = 0;
                                                                                                $nightcharge = 0;

                                                                                                $passenger_id = 
                                                                                                $get_passenger_query = "select * from ".PASSENGERS." where id='$passenger_id'";
		                                                                                $get_passenger_details = Db::query(Database::SELECT, $get_passenger_query)
				                                                                                ->execute()
				                                                                                ->as_array();

							                                        $passenger_discount = $get_passenger_details[0]['discount'];	
                                                                                                $email = $get_passenger_details[0]['email'];
                                                                                                $name = $get_passenger_details[0]['name'];	
                                                                                                $phone = $get_passenger_details[0]['phone'];	

							                                        $discount_amt = 0;
							                                        $nightcharge = 0;
                                                                                                $total_amount = $complete_total;

                                                                                                if($passenger_discount > 0)
                                                                                                {
                                                                                                $discount_amt = ($complete_total * $passenger_discount)/100; 
                                                                                                $total_amount = $complete_total - $discount_amt;
                                                                                                }


                                                                                                $product_title = Html::chars('Complete Trip');
                                                                                                //$payment_type = 'Authorization';
                                                                                                $payment_action='sale';

                                                                                                $request  = 'METHOD=DoDirectPayment';
                                                                                                $request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
                                                                                                $request .= '&USER=' . urlencode($company_paypal_details[0]['company_paypal_username']);
                                                                                                $request .= '&PWD=' . urlencode($company_paypal_details[0]['company_paypal_password']);
                                                                                                $request .= '&SIGNATURE=' . urlencode($company_paypal_details[0]['company_paypal_signature']);

                                                                                                $request .= '&CUSTREF=' . (int)$passenger_log_id;
                                                                                                $request .= '&PAYMENTACTION=' . $payment_action; 
                                                                                                $request .= '&AMT=' . urlencode($total_amount); 

                                                                                                $request .= '&ACCT=' . urlencode(str_replace(' ', '',$creditcard_no ));
                                                                                                $request .= '&EXPDATE=' . urlencode($expdatemonth.$expdateyear);
                                                                                                $request .= '&CVV2=' . urlencode($creditcard_cvv);
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

                                                                                                if(isset($nvpArray) && !empty($nvpArray) )
                                                                                                {
                                                                                                $ack = strtoupper($nvpArray["ACK"]);		                

                                                                                                if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
                                                                                                {

                                                                                                if($complete_nightcharge > 0)
                                                                                                {
                                                                                                $nightcharge = 1;
                                                                                                }

                                                                                                if($check_package_type != 'N')
                                                                                                {
                                                                                                $admin_amt = ($total_amount * $siteinfo_details[0]['admin_commission'] )/100; //payable to admin
                                                                                                $admin_amt = round($admin_amt, 2);
                                                                                                $total_balance = round($total_amount,2);				

                                                                                                //Set Commission to Admin	
                                                                                                $updatequery = " UPDATE ". PEOPLE ." SET account_balance=account_balance+$total_balance WHERE user_type = 'A'";	

                                                                                                $updateresult = Db::query(Database::UPDATE, $updatequery)
                                                                                                ->execute();
                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                $admin_amt = 0;
                                                                                                }

                                                                                                $company_amt = $total_amount - $admin_amt; 	
                                                                                                $company_amt = round($company_amt, 2);	

                                                                                                //Set Commission to Admin	
                                                                                                $updatequery = " UPDATE ". PEOPLE ." SET account_balance=account_balance+$company_amt WHERE user_type = 'C' and company_id=".$company_id;	

                                                                                                $updateresult = Db::query(Database::UPDATE, $updatequery)
                                                                                                ->execute();


                                                                                                $current_time = date('Y-m-d H:i:s');

                                                                                                $faretype = $passengerlog_result[0]['faretype'];

							

                                                                                                $result = DB::insert(TRANS, array('passengers_log_id','distance','actual_distance','tripfare','fare','waiting_cost','waiting_time','company_tax','passenger_discount',
'account_discount','remarks','correlation_id','ack','transaction_id','payment_type','order_time','amt','currency_code','payment_status',                                                                             'captured','admin_amount','company_amount','trans_packtype','nightfare','credits_used'))->values(array($passenger_log_id,$complete_distance,$complete_distance,$complete_total,$total_amount,$complete_waitingcost,$complete_waitingtime,$company_tax,$discount_amt,$total_amount,$complete_remark,$nvpArray['CORRELATIONID'],$nvpArray['ACK'],$nvpArray['TRANSACTIONID'],$faretype,$current_time,$total_amount,$nvpArray['CURRENCYCODE'],$nvpArray['ACK'],'1',$admin_amt,$company_amt,$check_package_type,$complete_nightcharge,$total_amount))->execute();

                                                                                                $msg_status = 'R';$driver_reply='A';$journey_status=1;
                                                                                                $journey = $this->update_dispatch_status($passenger_log_id,$msg_status,$driver_reply,$journey_status);





				                                                        $orderlist='';   
				                                                        $orderlist='<p><b>'.__('complete_from').'</b></p><p style="padding-left:5px;">'.__('complete_from1').''.$passengerlog_result[0]['current_location'].' </p><p style="padding-left:5px;"> To : '.$passengerlog_result[0]['drop_location'].'</p>'.'<p>'.__('total_cost').':'. CURRENCY.' '.$complete_total.'</p>'.'<p>'.__('transactionid_label').': '.$nvpArray['TRANSACTIONID'].'</p>';

                                                                                        $to=""; 
                                                                                        $mail="";
						                                        $this->emailtemplate = Model::factory('emailtemplate');
		
			                                                                $replace_variables=array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>SITE_NAME,REPLACE_USERNAME=>$name,REPLACE_EMAIL=>$email,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>SITE_EMAIL_CONTACT,REPLACE_SITEURL=>URL_BASE,REPLACE_ORDERID=>$nvpArray['TRANSACTIONID'],REPLACE_ORDERLIST=>$orderlist,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);

					                                                                $message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcomplete-mail.html',$replace_variables);


				                                                                        $from = SITE_EMAIL_CONTACT;
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

				                                                                        //free sms url with the arguments
				                                                                        if(SMS == 1)
				                                                                        {
				                                                                        $common_model = Model::factory('commonmodel');	
				                                                                        $message_details = $common_model->sms_message('5');
				                                                                        $to = $phone;
				                                                                        $message = $message_details[0]['sms_description'];
			                                                                                $message = str_replace("##booking_key##",$passenger_log_id,$message);
		
				                                                                        //$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
				                                                                        }




                                                                                                return 2;

                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                return __('payment_failed_due').' '.$nvpArray['L_LONGMESSAGE0'];

                                                                                                }	

                                                                                                }

                                                                                                }	


												}
												else
												{

	                                                                                Message::error(__('no_sufficient_credits'));
	                                                                                echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;
												}
											}
											else
											{

                                                                                Message::error(__('no_credits_in_account'));
                                                                                echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;

											}
										}
										else
										{

                                                                                Message::error(__('group_account_deactive'));
                                                                                echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;

										}
								}
								else
								{

                                                                                Message::error(__('account_holder_nocreditcard'));
                                                                                echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;

								}
							}
							else
							{
                                                                Message::error(__('account_holder_deactive'));
                                                                echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;

							}
						}
						else
						{
                                                        Message::error(__('account_deactive'));
                                                        echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;

						}
					}
					else
					{
                                                Message::error(__('no_account'));
                                                echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;
	
					}
					
                                        }


				}
				// Check Totalfare is zero //						


			}
			// Check Travel Status //


		}

		// Check Valid Trip or Not//			

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


	public function get_dispatchaccount_discount($aid)
	{

		$sql = " SELECT ".TBLGROUPACCOUNT.".limit,".TBLGROUPACCOUNT.".discount,".TBLGROUPACCOUNT.".passid FROM ".TBLGROUPACCOUNT." LEFT JOIN ".PASSENGERS." ON (".TBLGROUPACCOUNT.".passid = ".PASSENGERS.".id) WHERE `aid` = '$aid' and ".TBLGROUPACCOUNT.".status=1";
		
		$result =  Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
		
		//print_r($result);
		return $result;
	}

	public function get_passenger_profile($userid,$company_id) 
	{		
		if($company_id != '')
		{
			$query= "SELECT ".PASSENGERS.".name,".PASSENGERS.".email,".PASSENGERS.".profile_image,".PASSENGERS.".phone,".PASSENGERS.".address,".PASSENGERS.".discount,
".PASSENGERS.".creditcard_no,".PASSENGERS.".creditcard_cvv,".PASSENGERS.".expdatemonth,".PASSENGERS.".expdateyear,".PASSENGERS.".device_token,
".PASSENGERS.".device_type,".PASSENGERS.".user_status FROM ".PASSENGERS." WHERE id = '$userid'";
		}
		else
		{
			$query= "SELECT ".PASSENGERS.".name,".PASSENGERS.".id as passengers_id,".PASSENGERS.".email,".PASSENGERS.".profile_image,".PASSENGERS.".phone,".PASSENGERS.".address,".PASSENGERS.".discount,
".PASSENGERS.".creditcard_no,".PASSENGERS.".creditcard_cvv,".PASSENGERS.".expdatemonth,".PASSENGERS.".expdateyear,".PASSENGERS.".device_token,
".PASSENGERS.".device_type,".PASSENGERS.".user_status FROM ".PASSENGERS." WHERE id = '$userid'";
		}
		//echo $query;
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $result;	
	}

	public function get_tdipatch_groupdetails($groupid)
	{
			///$user_query = " select * from " . TBLGROUP . " where FIND_IN_SET ('$check_passid',passenger_id) and gid='$groupid'";
			$user_query = " select ".TBLGROUP.".limit,passenger_id from ".TBLGROUP." where gid='$groupid' and status = '1'";
			$user_result = Db::query(Database::SELECT, $user_query)
					->execute()
					->as_array();
			return $user_result;
	}

	public function check_account_usedlimit($passengers_id)
	{
			$sql = "SELECT sum(".TRANS.".credits_used) as total_used_limit FROM  ".PASSENGERS_LOG." JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
			JOIN  ".TRANS." ON (  ".TRANS.".`passengers_log_id` =  ".PASSENGERS_LOG.".`passengers_log_id` ) 
			WHERE  ".PASSENGERS_LOG.".`passengers_id` =  '$passengers_id'";
			$user_result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			return $user_result;		
	}

	public function check_dates($data)
	{

		$days = explode(',',$data['days']);
		$frmdate = $data['frmdate'];
		$todate = $data['todate'];

		$exist = '0';
		foreach($days as $key => $value)
		{
			for($i = strtotime($value, strtotime($frmdate)); $i <= strtotime($todate); $i = strtotime('+1 week', $i))
			{
				$exist = '1';
			}
		}	

		return $exist;
	}

	public function get_gatewaydetails()
	{
		$query2 = "SELECT * FROM payment_modules where pay_mod_active='1' and pay_mod_name!='uncard' order by pay_mod_name asc";

		$result = Db::query(Database::SELECT, $query2)
		->execute()
		->as_array();
		return $result;
	}
	


}
?>
