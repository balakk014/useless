<?php defined('SYSPATH') OR die('No Direct Script Access');

/******************************************

* Contains Users Module details

* @Created on July, 2013

* @Updated on July, 2013

* @Package: taxi

* @Author: taxi Team

* @URL : taxiapp.com

********************************************/

Class Model_Company extends Model
{
	public function __construct()
	{	
		$this->session = Session::instance();	
		//$this->username = $this->session->get("user_name");
		$this->currentdate=Commonfunction::getCurrentTimeStamp();

	}
	
	/**Validating User SignUP details**/
	public function validate_company_signup($arr) 
	{

		return Validation::factory($arr)
			/*->rule('company_username', 'not_empty')
			->rule('company_username', 'min_length', array(':value', '4'))
			->rule('company_username', 'max_length', array(':value', '32'))
			->rule('company_username','alpha_dash')*/
			->rule('firstname', 'not_empty')
			->rule('firstname', 'min_length', array(':value', '4'))
			->rule('firstname', 'max_length', array(':value', '32'))
			->rule('lastname', 'not_empty')
			->rule('lastname', 'max_length', array(':value', '32'))
			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '100'))
			->rule('companyname', 'not_empty')
			->rule('companyname','alpha_dash')
			->rule('companyname', 'min_length', array(':value', '4'))
			->rule('companyname', 'max_length', array(':value', '30'))
			->rule('country','not_empty')
			->rule('city','not_empty')
			->rule('state','not_empty')
			->rule('address','not_empty')
			->rule('companyaddress','not_empty')
			->rule('mobile','not_empty')
			->rule('mobile', 'min_length', array(':value', '4'))
			->rule('mobile', 'max_length', array(':value', '36'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('password', 'not_empty')
			->rule('password', 'min_length', array(':value', '4'))
			->rule('password', 'max_length', array(':value', '36'))
			->rule('confirm_password', 'not_empty')
			->rule('confirm_password', 'min_length', array(':value', '4'))
			->rule('confirm_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('confirm_password',  'matches', array(':validation', 'password', 'confirm_password'))
			->rule('confirm_password', 'max_length', array(':value', '36'));
			//->rule('terms', 'not_empty');

	}
	
	/**User Signup**/
	public function company_signup($sign,$val) 
	{
		//$company_username = Html::chars($sign['company_username']);
		$org_password =  Html::chars($sign['password']);
		$password = Html::chars(md5($sign['confirm_password']));
		$result = DB::insert(PEOPLE, array('name','lastname','email','address','password','org_password','created_date','login_country','login_city','login_state','phone','login_type','user_type','account_type','status'))
					->values(array($sign['firstname'],$sign['lastname'],$sign['email'],$sign['address'],$password,$org_password,
					$this->currentdate,$sign['country'],$sign['city'],$sign['state'],$sign['mobile'],'1','C','0','A'))
					->execute();
		$last_insert_id = $result[0];
		
		$update_people = DB::update(PEOPLE)->set(array('company_id' => $result['0']))->where('id', '=', $last_insert_id)
			->execute();
		
		$cresult = DB::insert(COMPANY, array('company_name','company_address','company_country','company_state','company_city','userid','company_status'))
					->values(array($sign['companyname'],$sign['companyaddress'],$sign['country'],$sign['state'],$sign['city'],$last_insert_id,'A'))
					->execute();
					
		$upgrade_package = $sign['upgrade_package'];
		$upgrade_packid = $sign['pack'];
		
		$add_company = Model::factory('company');
		
		if($upgrade_package =='D')
		{
			$get_packagedetails = $add_company->payment_packagedetails($upgrade_packid);
			$package_name = $get_packagedetails[0]['package_name']; 
			$no_of_taxi = $get_packagedetails[0]['no_of_taxi']; 
			$no_of_driver = $get_packagedetails[0]['no_of_driver']; 
			$days = $get_packagedetails[0]['days_expire'];   
			$amount = $get_packagedetails[0]['package_price'];   
			$userid = $last_insert_id;
			$expirydate = Commonfunction::getExpiryTimeStamp($days);
			$packageresult = DB::insert(PACKAGE_REPORT, array('upgrade_userid','upgrade_packageid','upgrade_packagename','upgrade_no_taxi','upgrade_no_driver','upgrade_expirydate','upgrade_ack','upgrade_capture','upgrade_amount','upgrade_type','upgrade_by'))->values(array($result[0],$upgrade_packid,$package_name,$no_of_taxi,$no_of_driver,$expirydate,'Success','1',$amount,'D',$userid))->execute();
		}
		
		if($result && $cresult && $update_people && $packageresult)
		return $result[0];
		else
		return 0;
	}
	
	/**To Check User Name is Already Available or Not**/
	public static function unique_username($name)
	{
		// Check if the username already exists in the database
		$sql = "SELECT username FROM ".PEOPLE." WHERE username='$name'";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{
				return 1;
			}
			else
			{
				return 0;
			}
	}
	
	/**Check Whether Email is Already Exist or Not**/

	public function check_email($email="")
	{
		$sql = "SELECT email FROM ".PEOPLE." WHERE email='$email' AND status!='D' ";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;
			}
	}
	
	/**get country details**/
	public static function country_details()
	{
		$result = DB::select()->from(COUNTRY)->where('country_status','=','A')->order_by('country_name','asc')
			->execute()
			->as_array();
		  return $result;
	}
	/**get city details**/
	public static function city_details()
	{
		$result = DB::select()->from(CITY)->where('city_status','=','A')->order_by('city_name','asc')
			->execute()
			->as_array();
		  return $result;
	}
	/**get state details**/
	public static function state_details()
	{
		$result = DB::select()->from(STATE)->where('state_status','=','A')->order_by('state_name','asc')
			->execute()
			->as_array();
		  return $result;
	}
	
	public static function getcity_details($country_id,$state_id)
	{
			$result = DB::select()->from(CITY)->join(STATE, 'LEFT')->on(CITY.'.city_stateid', '=', STATE.'.state_id')->join(COUNTRY, 'LEFT')->on(CITY.'.city_countryid', '=', COUNTRY.'.country_id')->where('city_countryid','=',$country_id)->where('city_stateid','=',$state_id)->order_by('city_name','ASC')
				->execute()
				->as_array();
	
			return $result;
	}
	
	public static function getstate_details($country_id)
	{
			$result = DB::select()->from(STATE)->join(COUNTRY, 'LEFT')->on(STATE.'.state_countryid', '=', COUNTRY.'.country_id')->where('state_countryid','=',$country_id)->order_by('state_name','ASC')
				->execute()
				->as_array();
			
			return $result;
	}
	
	public function company_login($email="",$password="")
	{
		$password = md5($password);
		$sql = "SELECT * FROM ".PEOPLE." WHERE email='$email' and password='$password' and user_type='C' AND status!='D' ";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;
			}
	}
	
	public function unique_email_not_exists($email="")
	{
		$sql = "SELECT * FROM ".PEOPLE." WHERE email='$email' and user_type='C' AND status!='D' ";
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return 1;
			}
			else
			{
				return 0;
			}
	}
	public function validate_company_login($arr) 
	{

		return Validation::factory($arr)
			->rule('company_email', 'not_empty')
			->rule('company_email', 'email')
			->rule('company_email', 'max_length', array(':value', '100'))
			->rule('company_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('company_password', 'not_empty')
			->rule('company_password', 'min_length', array(':value', '4'))
			->rule('company_password', 'max_length', array(':value', '36'));
	}
	
	public function package_details()
	{
		$result = DB::select()->from(PACKAGE)->where('package_status','=','A')->order_by('package_name','asc')
			->execute()
			->as_array();
			return $result;
	}
	
	public function payment_packagedetails($packid=0)
	{

		$result = DB::select()->from(PACKAGE)
			->where('package_status','=','A')
			->where('package_id','=',$packid)
			->order_by('package_name','asc')
			->execute()
			->as_array();

		  return $result;
	}
	public function get_availabletaxi_list()
	{
	
		$company_id = $_SESSION['company_id'];
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
			AND taximapping.mapping_status = 'A' and taxi.taxi_status='A' and taxi.taxi_availability='A' and people.availability_status='A' and people.user_type='D' $query_where limit 0,10";


		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}
	
	public function get_availabletaxi_list_count()
	{
	
		$company_id = $_SESSION['company_id'];
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
			AND taximapping.mapping_status = 'A' and taxi.taxi_status='A' and taxi.taxi_availability='A' and people.availability_status='A' and people.user_type='D' $query_where";


		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return count($results);
	}

	public function get_manager_list()
	{
		$company_id = $_SESSION['company_id'];
		$result = DB::select()->from(PEOPLE)
			->join(COMPANY, '')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
			->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
			->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
			->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
			->where('user_type','=','M')
			->where('company_id','=', $company_id)
			->order_by('name','asc')
			->execute()
			->as_array();

		  return $result;
	}

	public function free_driver_list()
	{
		$company_id = $_SESSION['company_id'];
		$booked_driver = $this->free_availabletaxi_list();
		$driver_list = "";
		if(count($booked_driver) > 0)
		{
			foreach($booked_driver as $key => $value)
			{
				$driver_list .= "'".$value['id']."',";
			}
			$driver_list = trim($driver_list,',');
		}
		else
		{
			$driver_list ="''";
		}

		
		$sql = "select * from ".PEOPLE." JOIN ".COMPANY." ON ".PEOPLE.".company_id = company.cid where ".PEOPLE.".user_type='D' and  ".PEOPLE.".company_id='$company_id' and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and ".PEOPLE.".id NOT IN ($driver_list) order by ".PEOPLE.".id asc limit 0,10";

		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return $result;
				

	}

	public function free_driver_list_count()
	{
		$company_id = $_SESSION['company_id'];
		$booked_driver = $this->free_availabletaxi_list();
		$driver_list = "";
		if(count($booked_driver) > 0)
		{
			foreach($booked_driver as $key => $value)
			{
				$driver_list .= "'".$value['id']."',";
			}
			$driver_list = trim($driver_list,',');
		}
		else
		{
			$driver_list ="''";
		}

		
		$sql = "select * from ".PEOPLE." JOIN ".COMPANY." ON ".PEOPLE.".company_id = company.cid where ".PEOPLE.".user_type='D' and  ".PEOPLE.".company_id='$company_id' and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and ".PEOPLE.".id NOT IN ($driver_list) order by ".PEOPLE.".id asc";

		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return count($result);
				

	}
	

	public function free_taxi_list()
	{
		$company_id = $_SESSION['company_id'];
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

		$sql = "select * from ".TAXI." JOIN ".COMPANY." ON ".TAXI.".taxi_company = company.cid where ".TAXI.".taxi_status='A' and  ".TAXI.".taxi_availability='A' and ".TAXI.".taxi_company='$company_id' and ".TAXI.".taxi_id NOT IN ($taxi_list) order by ".TAXI.".taxi_id asc limit 0,10";
		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return $result;
				
	}

	public function free_taxi_list_count()
	{
		$company_id = $_SESSION['company_id'];
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

		$sql = "select * from ".TAXI." JOIN ".COMPANY." ON ".TAXI.".taxi_company = company.cid where ".TAXI.".taxi_status='A' and  ".TAXI.".taxi_availability='A' and ".TAXI.".taxi_company='$company_id' and ".TAXI.".taxi_id NOT IN ($taxi_list) order by ".TAXI.".taxi_id asc";
		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return count($result);
				
	}
	
	
	public function free_availabletaxi_list()
	{
	
		$company_id = $_SESSION['company_id'];
		$cuurentdate = date('Y-m-d H:i:s');
		$enddate = date('Y-m-d').' 23:59:59';
			
		$query_where = " AND ( ( '$cuurentdate' between taximapping.mapping_startdate and  taximapping.mapping_enddate ) or ( '$enddate' between taximapping.mapping_startdate and  taximapping.mapping_enddate) )";	
						
				
		/*$sql ="SELECT people.id,taxi.taxi_id  
			FROM ".TAXI." as taxi
			JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid
			JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid
			JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid
			JOIN ".DRIVER." as driver ON driver.driver_id = taximapping.mapping_driverid
			WHERE people.status = 'A' and driver.status='F' and company.cid='$company_id'
			AND taximapping.mapping_status = 'A' $query_where limit 0,10";
			*/
	$sql ="SELECT people.id,taxi.taxi_id  
			FROM ".TAXI." as taxi
			JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid
			JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid
			JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid
			WHERE people.status = 'A' and company.cid='$company_id'
			AND taximapping.mapping_status = 'A' $query_where limit 0,10";

		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}

	public function dashboard_driverdetails($cid = '')
	{
		$query = "SELECT count(`id`) as co_nt FROM ".PEOPLE." as p Join ".COMPANY." as c ON p.company_id=c.cid where user_type='D' and status='A' and cid = '$cid'";
		$queryval = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		$result = "";
		foreach($queryval as $res)
		{
			if($res["co_nt"] > 0)
			{		
				$result .= "['Driver', ".$res["co_nt"].""."],";
			}	
		}
		
		$result = rtrim($result,",");

		return $result;
	}

	public function changedashboard_driverdetails($cid = '',$startdate = '',$enddate = '')
	{
		$date_where = "  and (created_date between '$startdate' and '$enddate') ";	
		$query = "SELECT count(`id`) as co_nt FROM ".PEOPLE." as p Join ".COMPANY." as c ON p.company_id=c.cid where user_type='D' and cid = '$cid' $date_where";
		$queryval = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		$result = "";
		foreach($queryval as $res)
		{
			if($res["co_nt"] > 0)
			{		
				$result .= "['Driver', ".$res["co_nt"].""."],";
			}	
		}
		
		$result = rtrim($result,",");

		return $result;
	}
	
	public function dashboard_managerdetails($cid = '')
	{
		$query = "SELECT count(`id`) as co_nt FROM ".PEOPLE." as p Join ".COMPANY." as c ON p.company_id=c.cid where user_type='M' and status='A' and cid = '$cid'";
		$queryval = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		$result = "";
		foreach($queryval as $res)
		{
			if($res["co_nt"] > 0)
			{			
				$result .= "['Manager', ".$res["co_nt"].""."],";
			}	
		}
		
		$result = rtrim($result,",");

		return $result;
	}	

	public function changedashboard_managerdetails($cid = '',$startdate = '',$enddate = '')
	{
		$date_where = "  and (created_date between '$startdate' and '$enddate') ";	
		$query = "SELECT count(`id`) as co_nt FROM ".PEOPLE." as p Join ".COMPANY." as c ON p.company_id=c.cid where user_type='M' and cid = '$cid' $date_where ";
		$queryval = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		$result = "";
		
		foreach($queryval as $res)
		{
			if($res["co_nt"] > 0)
			{
				$result .= "['Manager', ".$res["co_nt"].""."],";
			}
		}
		
		$result = rtrim($result,",");

		return $result;
	}	
			

	public function dashboard_taxidetails($cid = '')
	{
		$query = "SELECT count(`taxi_id`) as co_nt FROM ".TAXI." where taxi_company='$cid' and taxi_status = 'A' ";
		$queryval = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		$result = "";
		foreach($queryval as $res)
		{
			if($res["co_nt"] > 0)
			{
				$result .= "['Taxi', ".$res["co_nt"].""."],";
			}
		}
		
		$result = rtrim($result,",");

		return $result;
	}	

	public function changedashboard_taxidetails($cid = '',$startdate = '',$enddate = '')
	{
	
		$date_where = "  and (taxi_createdate between '$startdate' and '$enddate') ";	
		$query = "SELECT count(`taxi_id`) as co_nt FROM ".TAXI." where taxi_company='$cid' $date_where";
		$queryval = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		$result = "";
		foreach($queryval as $res)
		{
			if($res["co_nt"] > 0)
			{		
				$result .= "['Taxi', ".$res["co_nt"].""."],";
			}	
		}
		
		$result = rtrim($result,",");

		return $result;
	}	
	

	public function transactionbycompanydriver($cid='')				
	{
		 $query = "SELECT count(`passengers_log_id`) as co_nt,pe.name FROM ".PASSENGERS_LOG." as p Join ".PEOPLE." as pe ON pe.id=p.driver_id Join ".COMPANY." as c ON pe.company_id=c.cid  where pe.user_type='D' and pe.company_id ='$cid' group by p.driver_id";
		$queryval = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		$result = "";
		foreach($queryval as $res)
		{
			$result .= "['".$res["name"]."', ".$res["co_nt"].""."],";
		}
		
		$result = rtrim($result,",");

		return $result;	
	}
	
	/**Validating Fund Request Form**/
	public function validate_fundrequest_amount($arr)
	{

		$total_pending_request = $this->fund_req_calc();
		return Validation::factory($arr)
			->rule('amount', 'not_empty')
			->rule('amount','max_length',array(':value', '3'))
			->rule('amount','numeric')
			->rule('amount','must_greaterthan',array($arr['amount'],MIN_FUND))
			->rule('amount','must_lessertotal',array($arr['amount'],$total_pending_request))
			->rule('amount','must_lesserthan',array($arr['amount'],MAX_FUND));

	}

	public function get_payment_gateways()
	{	
	   	$company_id = $_SESSION['company_id'];
			$result=DB::select()->from(PAYMENT_GATEWAYS)
			     ->where('payment_status','!=','T')
			     ->where('company_id','=',$company_id)
			     ->execute()
			     ->as_array();
			     return  $result;
	}
	
	/** Insert Fund Request Details **/
	public function insert_fundrequest_amount($details)
	{

		$result = DB::insert(REQUEST_FUND, array('company_ownerid','company_id','amount','status'))
					->values(array($_SESSION['userid'],$details['company_id'],$details['amount'],'1'))
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

	public function count_fundrequest_list()
	{
		$company_id = $_SESSION['company_id'];
		$user_id = $_SESSION['userid'];
	   	
		$rs = DB::select()->from(REQUEST_FUND)
				->order_by('requested_id','desc')
				->where('company_ownerid', '=', $user_id)
				->execute()
				->as_array();
				
		return count($rs);
	

	}
	public function all_fundreuest_list($offset, $val)
	{
		
		$company_id = $_SESSION['company_id'];
		$user_id = $_SESSION['userid'];
	   	
		$rs = DB::select()->from(REQUEST_FUND)
				->order_by('requested_id','desc')
				->where('company_ownerid', '=', $user_id)
				->limit($val)->offset($offset)
				->execute()
				->as_array();
				//print_r($rs);

		 return $rs;
		
	}

	//fund request fund calculation
	public function fund_req_calc()
	{
		$login_id = $_SESSION['userid'];

		$query = "SELECT sum(amount) as total_amt from ".REQUEST_FUND." where company_ownerid='$login_id' and status=1";
		$queryval = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();

		if(count($queryval)>0)
		{
			if($queryval[0]["total_amt"] > 0)
			{ 
				$total_pending_request = $queryval[0]["total_amt"];
			}
			else
			{ 
				$total_pending_request = 0;
			}
		}
		else
		{
			$total_pending_request = 0;
		}	
		
		$userbalance_query = "select * from ".PEOPLE." where id=".$login_id;

		$userbalance_result = Db::query(Database::SELECT, $userbalance_query)
				->execute()
				->as_array();
		
		$available_amt = $userbalance_result[0]['account_balance'] - $total_pending_request;

		return $available_amt;	
	}
	
		
	//company total current balance
	public function company_tot_current_bal($user_id)
	{
		$query = "SELECT p.account_balance FROM ".PEOPLE." as p  where user_type='C' and p.id='$user_id'   order by p.id";
		$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $result;
	}
	
	public function admin_tot_current_bal()
	{
		$query = "SELECT pl.account_balance FROM ".PEOPLE." as pl where user_type='A' and status='A' order by pl.id";
		return $result = Db::query(Database::SELECT,$query)
						 ->execute()
						 ->as_array();
	}

	 /** validating the site info settings **/
	public function validate_updatesiteinfo($arr="")
	{
					return Validation::factory($arr)     
					->rule('company_tax', 'not_empty')
					->rule('company_tax', 'numeric')
					->rule('company_tax', 'Model_Company::check_percentage', array(':value'))
					->rule('default_unit','not_empty')
					->rule('skip_credit_card','not_empty')
					->rule('fare_calculation','not_empty')
					->rule('cancellation_fare','not_empty')
					->rule('company_copyrights','not_empty')
					->rule('user_time_zone','not_empty')
					->rule('date_time_format','not_empty')
					//->rule('company_logo', 'Upload::not_empty',array($files_value_array['company_logo']))
					->rule('company_logo', 'Upload::type', array(':value', array('jpeg','jpg','png','gif')))
					//->rule('email_site_logo', 'Upload::not_empty',array($files_value_array['email_site_logo']))
					->rule('email_site_logo', 'Upload::type', array(':value', array('jpeg','jpg','png','gif')))
					//->rule('company_favicon', 'Upload::not_empty',array($files_value_array['company_favicon']))
					->rule('company_favicon', 'Upload::type', array(':value', array('jpeg','jpg','png','gif')))
					->rule('driver_commission','not_empty')
					->rule('driver_commission','numeric');
					/*->rule('customer_app_android', 'not_empty')
					->rule('customer_app_android', 'max_length', array(':value', '450'))
					->rule('customer_app_android', 'url')
					
					->rule('customer_app_ios', 'not_empty')
					->rule('customer_app_ios', 'max_length', array(':value', '450'))
					->rule('customer_app_ios', 'url')
		
					
					
					->rule('driver_app_android', 'not_empty')
					->rule('driver_app_android', 'max_length', array(':value', '450'))
					->rule('driver_app_android', 'url')
					
					->rule('app_name', 'not_empty')
					->rule('app_name', 'max_length', array(':value', '250'))
					
					->rule('company_tagline', 'not_empty')
					->rule('company_tagline', 'max_length', array(':value', '50'))
					
					->rule('app_description', 'not_empty')
					
					->rule('contact_email','not_empty')
					->rule('contact_email', 'max_length', array(':value', '30'))
					->rule('contact_email', 'email')     
					
					->rule('phone_number','not_empty')
					->rule('phone_number', 'max_length', array(':value', '30'))
					
					//->rule('meta_keyword','not_empty')
					
					//->rule('meta_description','not_empty')
					
					//->rule('site_country','not_empty')
					
					->rule('notification_settings','not_empty')
					->rule('notification_settings','numeric')


					/*->rule('site_city','not_empty')
					
					->rule('file', 'Upload::not_empty',array($files_value_array['site_logo']))
		->rule('file', 'Upload::type', array($files_value_array['site_logo'], array('jpg','jpeg', 'png', 'gif')))
		->rule('file', 'Upload::size', array($files_value_array['site_logo'],'2M'))
					
					
					
					//->rule('company_currency','not_empty')
					->rule('sms_enable','not_empty')
					
					->rule('passenger_setting','not_empty')
					->rule('home_page_title','not_empty')
					->rule('home_page_title', 'max_length', array(':value', '80'))
					->rule('home_page_content','not_empty')
					->rule('default_unit','not_empty')
					->rule('fare_calculation','not_empty')
					->rule('skip_credit_card','not_empty');*/

	}

	/** update default Payment status **/
	public function update_default_payment($id)
	{
			$company_id = $_SESSION['company_id'];
			$payment_status = DB::select()->from(PAYMENT_GATEWAYS)
				->where('id', '=' ,$id)
				->execute();
		
			if($payment_status[0]['payment_status'] == 'A')
			{
				$result = DB::update(PAYMENT_GATEWAYS)->set(array('default_payment_gateway' => '1'))
						->where('id', '=', $id)
						->execute();
								
				if($result==1){
				$result1 = DB::update(PAYMENT_GATEWAYS)->set(array('default_payment_gateway' => '0'))
						->where('id', '!=', $id)
						->where('company_id', '=', $company_id)
					->execute();
				}
				
				return $result;
			}
			else
			{
				return -1;
			}
			 
			 
	}
	
	/** site logo **/
	public function updatesiteinfo_image($image,$cid)
	{
		$sql="select company_logo from ".COMPANYINFO." where company_cid=$cid";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['company_logo'])){
				$id1 = $results[0]['company_logo'];
				if(file_exists($id1)){
				unlink($id1);
				}
			}
			
		$query = array('company_logo' => $image);
		$result =  DB::update(COMPANYINFO)->set($query)
					->where('company_cid', '=', $cid)
					->execute();	
			return $result;
	}
	
	/** site favicon image **/
	/** site logo **/
	public function updatesiteinfo_faviconimage($image,$cid)
	{
		$sql="select company_favicon from ".COMPANYINFO." where id=$cid";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['company_favicon'])){
				$id1 = $results[0]['company_favicon'];
				if(file_exists($id1)){
				unlink($id1);
				}
			}
			
		$query = array('company_favicon' => $image);
		$result =  DB::update(COMPANYINFO)->set($query)
					->where('company_cid', '=' ,$cid)
					->execute();
					
			return $result;
	}
        
        public function updatesiteinfo($post_value_array,$cid)
        {
			/* $php_format = $post_value_array['date_time_format'];
			$SYMBOLS_MATCHING = array(
				// Day
				'd' => 'dd',
				'D' => 'D',
				'j' => 'd',
				'l' => 'DD',
				'N' => '',
				'S' => '',
				'w' => '',
				'z' => 'o',
				// Week
				'W' => '',
				// Month
				'F' => 'MM',
				'm' => 'mm',
				'M' => 'M',
				'n' => 'm',
				't' => '',
				// Year
				'L' => '',
				'o' => '',
				'Y' => 'yy',
				'y' => 'y',
				// Time
				'a' => '',
				'A' => '',
				'B' => '',
				'g' => '',
				'G' => '',
				'h' => 'hh',
				'H' => 'hh',
				'i' => 'mm',
				's' => 'ss',
				'u' => ''
			);
			$jqueryui_format = "";
			$escaping = false;
			for($i = 0; $i < strlen($php_format); $i++)
			{
				$char = $php_format[$i];
				if($char === '\\') // PHP date format escaping character
				{
					$i++;
					if($escaping) $jqueryui_format .= $php_format[$i];
					else $jqueryui_format .= '\'' . $php_format[$i];
					$escaping = true;
				}
				else
				{
					if($escaping) { $jqueryui_format .= "'"; $escaping = false; }
					if(isset($SYMBOLS_MATCHING[$char]))
						$jqueryui_format .= $SYMBOLS_MATCHING[$char];
					else
						$jqueryui_format .= $char;
				}
			}
			$date_time_format_script = ($jqueryui_format != "") ? $jqueryui_format : "yy/mm/dd hh:mm:ss"; */
			$date_time_format_script = "yy-mm-dd hh:mm:ss";
			/*$query = array(
				'customer_app_url' => $post_value_array['customer_app_android']."|".$post_value_array['customer_app_ios'],
				'driver_app_url' => $post_value_array['driver_app_android'],			
				'company_app_name' => $post_value_array['app_name'],
				'company_tax' => $post_value_array['company_tax'],
				'company_app_description' => $post_value_array['app_description'],
				'company_email_id' => $post_value_array['contact_email'],
				'company_phone_number' => $post_value_array['phone_number'],
				'company_meta_title' => $post_value_array['meta_title'],
				'company_meta_keyword' => $post_value_array['meta_keyword'],
				'company_meta_description' => $post_value_array['meta_description'],
				//'site_country' => $post_value_array['site_country'],
				//'company_currency' => $post_value_array['company_currency'],
				'company_tagline' => $post_value_array['company_tagline'],
				'company_copyrights' => $post_value_array['company_copyrights'],
				'company_notification_settings'=>$post_value_array['notification_settings'],
				'company_sms_enable'=>$post_value_array['sms_enable'],
				'cancellation_fare'=>$post_value_array['cancellation_fare'],
				'passenger_setting'=>$post_value_array['passenger_setting'],
				'home_page_title'=>$post_value_array['home_page_title'],
				'home_page_content'=>$post_value_array['home_page_content'],,
			); */
			$query = array(
				'company_tax' => $post_value_array['company_tax'],
				'default_unit'=>$post_value_array['default_unit'],
				'fare_calculation_type'=>$post_value_array['fare_calculation'],
				'cancellation_fare'=>$post_value_array['cancellation_fare'],
				'company_copyrights' => $post_value_array['company_copyrights'],
				'skip_credit_card'=>$post_value_array['skip_credit_card']
			);
			$result =  DB::update(COMPANYINFO)->set($query)
					->where('company_cid', '=' ,$cid)
					->execute();

			$time_array = array('date_time_format'=>$post_value_array['date_time_format'],'user_time_zone' => $post_value_array['user_time_zone'],'date_time_format_script' => $date_time_format_script,'driver_commission'=>$post_value_array['driver_commission']);
			$result = DB::update(COMPANY)->set($time_array)->where('cid', '=' ,$cid)->execute();
			return $result;
        }
        public function site_settings($id="")
        {
                 $sql="SELECT *, `company`.`time_zone`, `companyinfo`.`cancellation_fare` as company_cancellation FROM `companyinfo` JOIN `company` ON (`companyinfo`.`company_cid` = `company`.`cid`) WHERE `company_cid` = '$id' ";
                 $results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
					/* $result=DB::select( '*', ''.COMPANY.'.time_zone')->from(COMPANYINFO)
                 ->join(COMPANY)->on(COMPANYINFO.'.company_cid', '=' ,COMPANY.'.cid')
			     ->where('company_cid', '=', $id)
			     ->execute()
			     ->as_array();        */
                return  $results;
        } 

	public function validate_update_socialinfo($arr="")
        {
                        return Validation::factory($arr)     
                                                   
                        ->rule('facebook_key', 'not_empty')
                        ->rule('facebook_secretkey', 'not_empty')
                        ->rule('facebook_share','not_empty')
                        ->rule('twitter_share','not_empty')
                        ->rule('google_share','not_empty')
                        ->rule('linkedin_share','not_empty');

        } 

	 public function update_socialinfo($post_value_array,$cid)
        {
        		$query = array('company_facebook_key' => $post_value_array['facebook_key'],
					'company_facebook_secretkey' => $post_value_array['facebook_secretkey'],
					'company_facebook_share' => $post_value_array['facebook_share'],
					'company_twitter_share' => $post_value_array['twitter_share'],
					'company_google_share' => $post_value_array['google_share'],
					'company_linkedin_share' => $post_value_array['linkedin_share'],
					);

		$result =  DB::update(COMPANYINFO)->set($query)
						->where('company_cid', '=' ,$cid)
						->execute();

		return $result;	
        }  

	public function layout_settings($id="")
        {
                 $sql="SELECT header_bgcolor,menu_color,mouseover_color FROM company  WHERE cid = '$id' ";
                 $results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
                return  $results;
        }  
        public function validate_update_layoutinfo($arr="")
        {
                        return Validation::factory($arr)     
                        ->rule('header_bg', 'not_empty')
                        ->rule('menu_bg', 'not_empty')
                        ->rule('mover_bg','not_empty');

        }



	public function update_layoutinfo($post_value_array,$cid)
        {
        		$query = array('header_bgcolor' => '#'.$post_value_array['header_bg'],
					'menu_color' => '#'.$post_value_array['menu_bg'],
					'mouseover_color' => '#'.$post_value_array['mover_bg'],
					);

		$result =  DB::update(COMPANY)->set($query)
						->where('cid', '=' ,$cid)
						->execute();

		return $result;	
        }  

	public function get_company_type($company_id)
	{
		$sql = "SELECT upgrade_packageid FROM package_report WHERE upgrade_companyid=$company_id ORDER BY  `upgrade_id` desc LIMIT 0 , 1";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();
		return $result;
		

	}


       
                                     /** validating the banners images **/
        public function validate_update_module($arr="",$files_value_array="")
        {
		
                        return Validation::factory($arr)     
                                                   
                        /*->rule('member', 'not_empty')
                        ->rule('member', 'max_length', array(':value', '2'))*/
                        
			
			
			->rule('file', 'Upload::not_empty',array($files_value_array['banner_image1']))
			->rule('file', 'Upload::type', array($files_value_array['banner_image1'], array('jpg','jpeg', 'png', 'gif')))
			->rule('file', 'Upload::size', array($files_value_array['banner_image1'],'2M'))
			
			->rule('file', 'Upload::not_empty',array($files_value_array['banner_image2']))
			->rule('file', 'Upload::type', array($files_value_array['banner_image2'], array('jpg','jpeg', 'png', 'gif')))
			->rule('file', 'Upload::size', array($files_value_array['banner_image2'],'2M'))
			
			->rule('file', 'Upload::not_empty',array($files_value_array['banner_image3']))
			->rule('file', 'Upload::type', array($files_value_array['banner_image3'], array('jpg','jpeg', 'png', 'gif')))
			->rule('file', 'Upload::size', array($files_value_array['banner_image3'],'2M'))
			
			->rule('file', 'Upload::not_empty',array($files_value_array['banner_image4']))
			->rule('file', 'Upload::type', array($files_value_array['banner_image4'], array('jpg','jpeg', 'png', 'gif')))
			->rule('file', 'Upload::size', array($files_value_array['banner_image4'],'2M'))
			
			->rule('file', 'Upload::not_empty',array($files_value_array['banner_image5']))
			->rule('file', 'Upload::type', array($files_value_array['banner_image5'], array('jpg','jpeg', 'png', 'gif')))
			->rule('file', 'Upload::size', array($files_value_array['banner_image5'],'2M'));
                        

        }
        /** Updating the banner images **/
        public function update_module_settings_images($image,$id)
        {
		
		$sql="select banner_image from ".COMPANY_CMS." where id='$id'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['banner_image'])){
				$id1 = DOCROOT.BANNER_IMGPATH.$results[0]['banner_image'];
				if(file_exists($id1)){
					$id1 = BANNER_IMGPATH.$results[0]['banner_image'];
					unlink($id1);
				}
			}
			

		$query = array('banner_image' => $image);
		$result =  DB::update(COMPANY_CMS)->set($query)
					->where('id', '=' ,$id)
					->execute();
		
		return $result;
        }  
        
        /** Updating the banner images **/
        public function update_module_settings_tags($tag,$image_tag,$id)
        {

		$query = array('alt_tags' => $tag,'image_tag'=>$image_tag);
		$result =  DB::update(COMPANY_CMS)->set($query)
					->where('id', '=' ,$id)
					->execute();
		
		return $result;
        }          
              	/** selecting the banner image for module settings **/
	public function site_module_settings($cid)
	{
		$sql="select * from ".COMPANY_CMS." where type='2' and status='1' and company_id='$cid' order by id ASC";
		
		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}
	public function get_company_info($cid)
	{
			$rs = DB::select()->from(COMPANYINFO)
				->where('company_cid','=',$cid)
				->execute()
				->as_array();
			return $rs;
	}	
	// List Company Users
	public function count_passenger_list_history($cid)
	{	
		$rs = DB::select('id','name','email','phone','address','created_date','user_status')->from(PASSENGERS)->where('passenger_cid','=',$cid)->order_by('created_date','desc')->execute()->as_array();
		return $rs;
	}	
	/** passenger list **/
	public function all_passenger_list_history($cid,$offset, $val)
	{
		$query = "select id,name,email,phone,address,created_date,user_status from ". PASSENGERS . " where passenger_cid='".$cid."' order by created_date desc limit $offset,$val"; 
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}

/** for getting passenger listing search count **/
	public function count_passengersearch_list($keyword = "", $status = "",$company = "")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		//condition for status
		//======================
		$staus_where= ($status) ? " AND user_status = '$status'" : "";

		//search result export
		//=====================
		$name_where = "";

		if($keyword){
			$name_where  = " AND(name LIKE  '%$keyword%' ";	
			$name_where .= " or email LIKE  '%$keyword%' escape '!' ) ";
		}

		$company_where= ($company) ? " AND passenger_cid = '$company'" : "";	
		$query = " select id,name,email,phone,address,created_date,user_status from " . PASSENGERS . " where 1=1 $staus_where $name_where $company_where order by created_date DESC";

		$results = Db::query(Database::SELECT, $query)
					->execute()
					->as_array();
		return $results;
	}

	/** for getting passenger listing search **/

	public function get_all_searchpassenger_list($keyword = "", $status = "",$company = "",$offset = "", $val ="")
	{

		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		//condition for status
		//======================
		$staus_where= ($status) ? " AND user_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword) {
			$name_where  = " AND(name LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%'  escape '!' ) ";
		}

		$company_where= ($company) ? " AND passenger_cid = '$company'" : "";
		$query = " select id,name,email,phone,address,created_date,user_status from " . PASSENGERS . " where 1=1 $staus_where $name_where $company_where order by created_date DESC limit $val offset $offset";

		$results = Db::query(Database::SELECT, $query)
					->execute()
					->as_array();
		return $results;
	}

	public function get_country()
	{
		//~ $query = "select * from ".COUNTRY; 
			//~ $result = Db::query(Database::SELECT, $query)
						//~ ->execute()
						//~ ->as_array();
//~ 
				//~ return $result;
	}
	public function get_city($id)
	{
		$query = "select * from ".CITY." where city_countryid = $id"; 
			$result = Db::query(Database::SELECT, $query)
						->execute();

				return $result;
	}
	public function save_free_trial($post)
	{

		//$budget=isset($post['budget'])?$post['budget']:'-';
		//$message="Budget: ".$budget."   -    Message: ".$post['message'];
		$message=" (FREE TRAIL REQUEST)   " .ucfirst($post_value_array['message']);
		$result = DB::insert(GET_FREE_QUOTES, array('name','email','mobile_no','company_name','no_of_taxi','country_name','city_name','message','createdate'))
					->values(array($post['g_name'],$post['g_email'],$post['g_phone'],$post['company_name'],$post['no_of_taxi'],$post['country'],$post['city'],$message,$post['createdate']))
					->execute();
		return $result;
	}

	public function save_moderator_trial($post)
	{

		//$budget=isset($post['budget'])?$post['budget']:'-';
		//$message="Budget: ".$budget."   -    Message: ".$post['message'];
		$message=" (FREE TRAIL REQUEST)   " .ucfirst($post['message']);
		$result = DB::insert(GET_FREE_QUOTES, array('name','email','mobile_no','company_name','no_of_taxi','country_name','city_name','message','createdate'))
					->values(array($post['name'],trim($post['email']),$post['phone'],$post['company_name'],$post['no_of_taxi'],$post['country'],$post['city'],$message,$post['createdate']))
					->execute();
		return $result;
	}
	
	
        public function validate_update_smssettings($arr="")
        {
                        return Validation::factory($arr)     
                                                   
                        ->rule('sms_account_id','not_empty')
                        ->rule('sms_auth_token','not_empty')
                        ->rule('sms_from_number','not_empty');

        }
        
        public function sms_settings($id)
        {
                 $result=DB::select()->from(SMS_SETTINGS)
			     ->where('company_id', '=', $id)
			     ->execute()
			     ->as_array();        
                return  $result;
        }
        
        public function update_sms_settings($post_value_array,$id,$sms_id)
        {
			$query = array(
				'sms_account_id' => $post_value_array['sms_account_id'],
				'sms_auth_token' => $post_value_array['sms_auth_token'],
				'sms_from_number' => $post_value_array['sms_from_number'],
				);
		if($sms_id != '')
		{
		$result =  DB::update(SMS_SETTINGS)->set($query)
						->where('company_id', '=' ,$id)
						->execute();
		}else{
			
		$result =  $result = DB::insert(SMS_SETTINGS, array('sms_account_id','sms_auth_token','sms_from_number','company_id'))
					->values(array($query['sms_account_id'],$query['sms_auth_token'],$query['sms_from_number'],$id))
					->execute();
		}

		return $result;	
        }
	public function get_admin_dashboard_data($company_id)
	{	
	
		//$result_query = "SELECT * FROM ".PASSENGERS." where 1=1 and (user_status = 'A' OR user_status = 'D' )";
		$result_query = "SELECT * FROM ".PASSENGERS." where 1=1 and user_status = 'A'";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["general_users"]=count($results);

		//$result_query = "SELECT * FROM ".PEOPLE." where user_type='D' and (status = 'A' OR status = 'D' ) and company_id=$company_id ";
		$result_query = "SELECT * FROM ".PEOPLE." where user_type='D' and status = 'A' and company_id=$company_id ";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["driver"]=count($results);
		
		/*$result_query = "SELECT * FROM  ".COMPANY." LEFT JOIN  ".PEOPLE." ON ".PEOPLE.".id = ".COMPANY.".userid WHERE user_type ='C' and (status = 'A' OR  status = 'D' ) ";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["company"]=count($results);*/
		
		//$result_query = "SELECT * FROM ".PEOPLE." where user_type='M' and (status = 'A' OR status = 'D' ) and company_id=$company_id ";
		$result_query = "SELECT * FROM ".PEOPLE." where user_type='M' and status = 'A' and company_id=$company_id ";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["manager"]=count($results);
		
		//$result_query = "SELECT * FROM ".COUNTRY." where country_status = 'A' OR country_status = 'D' ";
		$result_query = "SELECT * FROM ".COUNTRY." where country_status = 'A' ";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["country"]=count($results);
		
		//$result_query = "SELECT * FROM ".STATE." where state_status = 'A' OR state_status = 'D'";
		$result_query = "SELECT * FROM ".STATE." where state_status = 'A'";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["state"]=count($results);
		
		//$result_query = "SELECT * FROM ".CITY." where city_status = 'A' OR city_status = 'D'";
		$result_query = "SELECT * FROM ".CITY." where city_status = 'A'";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["city"]=count($results);	
		
		//$result_query = "SELECT * FROM ".TAXI." where taxi_company =".$company_id." AND (taxi_status = 'A' OR taxi_status = 'D' )";
		$result_query = "SELECT * FROM ".TAXI." where taxi_company =".$company_id." AND taxi_status = 'A'";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["taxi"]=count($results);	
					
				
		return $result;
	}
	public function get_activeusers_list($company_id)
	{
		$results = DB::select()->from(PASSENGERS)
				->where('login_status', '=', 'A')
				->where('passenger_cid', '=', $company_id)
				->order_by('last_login','desc')
				->limit('0,10')
				->execute()
				->as_array();
	 	return $results;
	}
		//dashboard active users count
	public function get_activeusers_list_count($company_id)
	{
		$results = DB::select()->from(PASSENGERS)
				->where('login_status', '=', 'A')
				->where('passenger_cid', '=', $company_id)
				->order_by('last_login','desc')
				->execute()
				->as_array();
	 	return count($results);
	}
	
	public static function addcompanydetails($post)
	{ 
			$password = Html::chars(md5('qwerty'));
			
			$upgrade_package = 'N';
			$upgrade_packid ='5';
			$taxi_type = '1';
			$taxi_model=1;
			//print_r($post);
			//exit;
			if($post['time_zone'])
			{
				$current_time = convert_timezone('now',$post['time_zone']);
				$current_date = explode(' ',$current_time);
				$start_time = $current_date[0].' 00:00:01';
				$end_time = $current_date[0].' 23:59:59';
			}
			else
			{
				$current_time = date('Y-m-d H:i:s');
				$start_time = date('Y-m-d').' 00:00:01';
				$end_time = date('Y-m-d').' 23:59:59';				
			}			
			$add_company = Model::factory('add');

			$current_date = convert_timezone('now',$post['time_zone']);
			$country_id = 9;//$post['country'];//DEFAULT_COUNTRY
			$state_id = 5;//$post['state'];//DEFAULT_STATE
			$city_id = 6;//$post['city'];//DEFAULT_CITY
			if($post['type']==2){
			$post['g_name']=($post['name'])?$post['name']:$post['g_name'];
			$post['g_email']=($post['email'])?$post['email']:$post['g_email'];
			$post['g_phone']=($post['phone'])?$post['phone']:$post['g_phone'];
			}

			$result = DB::insert(PEOPLE, array('name','address','lastname','email','paypal_account','phone','password','org_password','login_country','login_state','login_city','created_date','user_type','status'))
						->values(array($post['g_name'],'','',trim($post['g_email']),'',$post['g_phone'],$password,'qwerty',$country_id,$state_id,$city_id,$current_date,'C',ACTIVE))
						->execute();

			$company_userid = $result[0];

			$in_company = DB::insert(COMPANY, array('company_name','company_address','company_country','company_state','company_city','userid','time_zone','header_bgcolor','menu_color','mouseover_color'))
						->values(array($post['company_name'],'',$country_id,$state_id,$city_id,$company_userid,$post['time_zone'],'#FFFFFF','#000000','#FFD800'))
						->execute();
				
			$reg_companyid = $in_company[0];

			$insert_tdispatchalogrithm = DB::insert(TBLALGORITHM, array('labelname','alg_created_by','alg_company_id','active','hide_customer','hide_droplocation','hide_fare'))->values(array(2,$company_userid,$reg_companyid,1,0,0,0))->execute();

						   $key="";
						   $charset = "abcdefghijklmnopqrstuvwxyz"; 
						   $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
						   $charset .= "0123456789"; 
						   $length = mt_rand (30, 35); 
						   for ($i=0; $i<$length; $i++) 
						   $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];					
						
			 DB::insert(COMPANYINFO, array('company_cid','company_domain','company_phone_number','company_app_name','company_currency','company_currency_format','payment_method','company_paypal_username','company_paypal_password','company_paypal_signature','company_notification_settings','company_api_key'))
						->values(array($reg_companyid,trim($post['domain_name']),'9000000000',$post['company_name'],'$','USD','T','nandhu_1337947987_biz_api1.gmail.com','1337948040','A0YqGlJEML24al4qg2LnV2U.g2ThAfXD37NEiWIVcgjl1pxlygg-XaVs','20',$key))
						->execute();	


					$cms=DB::insert(COMPANY_CMS, array('company_id','menu_name','title','content','page_url','type'));
					//$banner_image=DB::insert(COMPANY_CMS, array('company_id','image_tag','alt_tags','banner_image','type'));
					//$pages=array('About us','Features','Terms and Conditions','Tutorials','Contact us');
					//$page_url=array('about-us','features','termsconditions','tutorials','contact-us');
					$pages=array('About us','Privacy policy','Servicing for Excellence','Terms and Conditions','Help');
					$page_url=array('aboutus','privacypolicy','service-area','termsconditions','help');
					 for($i=0;$i<5;$i++)
					 {
						$cms->values(array($reg_companyid,$pages[$i],$pages[$i],$pages[$i],$page_url[$i],1));
						if($i==0)
						{
							$srcfile=DOCROOT.PUBLIC_IMAGES_FOLDER.'header_banner_bg.jpg';
							$dstfile=DOCROOT.PUBLIC_UPLOAD_BANNER_FOLDER.$reg_companyid.'_header_banner_bg.jpg';
							copy($srcfile, $dstfile);
							$image_name=$reg_companyid.'_header_banner_bg.jpg';
							$banner_image=DB::insert(COMPANY_CMS, array('company_id','image_tag','alt_tags','banner_image','type'));
							$banner_image->values(array($reg_companyid,"image1","image1",$image_name,2));
							$banner_image->execute();
						}
					 }
					 $cms->execute();	
					 //$banner_image->execute();
			
				
			$update_people = DB::update(PEOPLE)->set(array('company_id' => $reg_companyid))->where('id', '=', $company_userid)
				->execute();
			if($upgrade_package =='N')
			{
			$get_packagedetails = $add_company->payment_packagedetails($upgrade_packid);
			$package_name = $get_packagedetails[0]['package_name']; 
			$no_of_taxi = $get_packagedetails[0]['no_of_taxi']; 
			$no_of_driver = $get_packagedetails[0]['no_of_driver']; 
			$days = $get_packagedetails[0]['days_expire'];   
			$amount = $get_packagedetails[0]['package_price'];   
			$package_type = $get_packagedetails[0]['package_type'];   
			
			
			if($days > 0)
			{
				$expirydate = Commonfunction::getExpiryTimeStamp($current_time,$days);
			}
			else
			{
				$expirydate = Commonfunction::getExpiryTimeStamp($current_time,TRAILEXPIRY);
			}
			
					$result = DB::insert(PACKAGE_REPORT, array('upgrade_companyid','upgrade_packageid','upgrade_packagename','upgrade_no_taxi','upgrade_no_driver','upgrade_expirydate','upgrade_ack','upgrade_capture','upgrade_amount','upgrade_type','upgrade_by','check_expirydate','check_package_type'))->values(array($in_company[0],$upgrade_packid,$package_name,$no_of_taxi,$no_of_driver,$expirydate,'Success','1',$amount,'D',1,$expirydate,$package_type))->execute();
			}
			
			// Convert Time
			$domain = trim($post['domain_name']);
						
			
			$referral_code="";

				/** Referral key generator  $country_id,$state_id,$city_id **/

			$taxilength=($post['no_of_taxi'])?$post['no_of_taxi']:3;
			
			for($i=1;$i<=$taxilength;$i++)
			{
				/***** Driver login creation ************/
				$driver_email=$domain.$i.'@'.$domain.'.com';
				$driver_name =$domain.$i;
				$driver_mobile =$reg_companyid.date('ymd').$i;
				$fieldname_array = 
				array('name','email','password','org_password','otp','phone','driver_referral_code','user_type','status','login_city','login_state','login_country','created_date','updated_date','company_id','booking_limit');
				$values_array = 
				array($driver_name,$driver_email,md5("qwerty"),'qwerty','',$driver_mobile,$referral_code,'D','A',$city_id,$state_id,$country_id,$current_time,$current_time,$reg_companyid,'100');
				$result = DB::insert(PEOPLE, $fieldname_array)
				->values($values_array)
				->execute();
				$driver_id='';
					if($result){										
					$email = DB::select()->from(PEOPLE)
					->where('email', '=', $driver_email)
					->execute()
					->as_array();
					$driver_id =  $email [0]['id'];
					}
				/***** Passenger login creation ************/
				$p_fieldname_array = array('name','salutation','lastname','email','password','org_password','phone','activation_status','user_status','created_date','updated_date','passenger_cid','skip_credit_card');
				$passenger_values_array = array($driver_name,'Mr',$driver_name,$driver_email,md5("qwerty"),'qwerty',$driver_mobile,'1','A',$current_time,$current_time,$reg_companyid,'1');
				$p_values_array = $passenger_values_array;
				$passenger_result = DB::insert(PASSENGERS, $p_fieldname_array)
					->values($p_values_array)
					->execute();	
				/***** Taxi  creation ************/
				$taxi_image = 'taxi_'.$i.'.png';
				$taxi_result = DB::insert(TAXI, array('taxi_no','taxi_type','taxi_model','taxi_company','taxi_country','taxi_image','taxi_state','taxi_city','taxi_capacity','taxi_speed'))
						->values(array($domain.$i,$taxi_type,'1',$reg_companyid,$country_id,$taxi_image,$state_id,$city_id,'5','100'))
						->execute();
				$taxi_id = $taxi_result[0];
				
				/*$taxi_additional_result = DB::insert(ADDFIELD, array('taxi_id'))
								->values(array($taxi_id))
								->execute();*/
				/***** Driver location creation ************/
				$driver_status_result = DB::insert(DRIVER, array('driver_id','latitude','longitude','status','shift_status'))
				->values(array($driver_id,LOCATION_LATI,LOCATION_LONG,'F','OUT'))
				->execute();
				/** Insert Taxi Mappning Details *******/
					$taxi_mapping_result = DB::insert(TAXIMAPPING, array('mapping_driverid','mapping_taxiid','mapping_companyid','mapping_countryid','mapping_stateid','mapping_cityid','mapping_startdate','mapping_enddate','mapping_createdby'))
							->values(array($driver_id,$taxi_id,$reg_companyid,$country_id,$state_id,$city_id,$start_time,$expirydate,1))
							->execute();
				/** Driver referral entry **/
				$driverReferralCode = text::random($type = 'alnum', $length = 6);
				$driverRef = DB::insert(DRIVER_REF_DETAILS, array('registered_driver_id','registered_driver_code','registered_driver_code_amount'))->values(array($driver_id,$driverReferralCode,DRIVER_REF_AMOUNT))->execute();
			}
										
			/*** Insert Model fare for the company ***********/
				$adminmodeldata = DB::select('*')->from(MOTORMODEL)
				->where(MOTORMODEL.'.model_id','=',$taxi_model)
				->execute()
				->as_array();
					if(count($adminmodeldata)>0)
					{
						foreach($adminmodeldata as $values)
						{
							$model_id = $values['model_id'];
							$model_name = $values['model_name'];
							$motor_mid = $values['motor_mid'];
							$base_fare = $values['base_fare'];
							$min_km = $values['min_km'];
							$min_fare = $values['min_fare'];
							$cancellation_fare = $values['cancellation_fare'];
							$below_above_km = $values['below_above_km'];
							$below_km = $values['below_km'];
							$above_km = $values['above_km'];
							$night_charge = $values['night_charge'];
							$night_timing_from = $values['night_timing_from'];
							$night_timing_to = $values['night_timing_to'];
							$night_fare = $values['night_fare'];
							$waiting_time = $values['waiting_time'];
						}
						$result = DB::insert(COMPANY_MODEL_FARE, array('model_id','company_cid','motor_mid','base_fare','min_fare','cancellation_fare','below_km','above_km','night_charge',
						'night_timing_from','night_timing_to','night_fare','min_km','below_above_km','waiting_time','model_name'))
						->values(array($model_id,$reg_companyid,$motor_mid,$base_fare,$min_fare,$cancellation_fare,$below_km,$above_km,$night_charge,$night_timing_from,$night_timing_to,$night_fare,$min_km,$below_above_km,$waiting_time,$model_name))
						->execute();
					}
			/**********************************************************************************************************************/		
			if($result)
			{
				return $reg_companyid;
			}
			else
			{
				return 0;
			}
	}


	
	
	
		// Check Whether Email is Already Exist or Not
	public static function checkemail($email="")
	{
		$result = DB::select('email')->from(PEOPLE)->where('email','=',$email)
			->execute()
			->as_array();
			
			if(count($result) > 0)
			{ 
				return 0;
			}
			else
			{
				return 1;		
			}
	}
	public static function get_country_name($id="")
	{
		$result = DB::select('country_name')->from(COUNTRY)->where('country_id','=',$id)
			->execute()
			->as_array();
	    return $result[0]['country_name'];
	}
	public static function get_city_name($id="")
	{
		$result = DB::select('city_name')->from(CITY)->where('city_id','=',$id)
			->execute()
			->as_array();
	    return $result[0]['city_name'];
	}
	
	/**************Dashboard Trip Details Chart***************/
	public function get_company_trip_count($month, $year)
	{
		$company_id=COMPANY_CID;
		$query = "SELECT count(c.cid) as count
				FROM ".COMPANY." as c
				Join ".PASSENGERS_LOG." as p ON p.company_id=c.cid
				Join ".TRANS." as T on T.passengers_log_id=p.passengers_log_id
				where month(T.current_date) ='" . $month . "'
				and year(T.current_date)='" . $year . "'
				and p.travel_status = 1
				and c.cid=".$company_id."
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

	public function get_company_trip_revenues($month, $year)
	{
		$company_id=COMPANY_CID;
		$query = "SELECT sum(fare) as revenues
				FROM ".COMPANY." as c
				Join ".PASSENGERS_LOG." as p ON p.company_id=c.cid
				Join ".TRANS." as T on T.passengers_log_id=p.passengers_log_id
				where month(T.current_date) ='" . $month . "'
				and year(T.current_date)='" . $year . "'
				and p.travel_status = 1
				and c.cid=".$company_id."
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

	public function total_trip_details($start='',$end='')
	{
		$company_id=COMPANY_CID;
		 $query = "SELECT round(sum(t.`fare`)) as fare,count(fare) as trips,DATE_FORMAT(log.`pickup_time`,'%d') as date,DATE_FORMAT(log.`pickup_time`,'%M') as month
		 FROM ".PASSENGERS_LOG." as log
		 LEFT JOIN ".TRANS." as t on log.`passengers_log_id`=t.`passengers_log_id`
		 WHERE log.`travel_status` = 1
		 AND log.`pickup_time` BETWEEN '".$start." 00:00:01' AND '".$end." 23:59:59'
		 and log.company_id=".$company_id."
		 group by DATE(log.`pickup_time`)";
		 
		//echo $query;exit;
		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();		

		return $result;
	}
	/**************Dashboard Trip Details Chart***************/

	public function get_company_payment_settings($company_user_id)
	{	//echo $company_user_id;exit;
		$query2 = "SELECT * FROM ".COMPANY_PAYMENT_MODULES." order by pay_mod_id asc";

		$result =DB::select()->from(COMPANY_PAYMENT_MODULES)
				->where('company_user_id','=',$company_user_id)
				->execute()
				->as_array();

		return $result;
	}

	public function validate_payment_settings($arr,$uid)
	{
	return Validation::factory($arr)   
			->rule('paymodstatus', 'not_empty');

	}

	public function update_payment_setting($uid,$post,$company_id)
	{
	/***Company payment settings Update***/
		if(isset($post_value_array['payid'])){
			foreach($post_value_array['payid'] as $k=>$id)
			{
				//print_r($id);exit;
				if($id == $post_value_array['default'][0]){
					$default = '1';
				}else{
					$default = '0';
				}

				if(in_array($id,$post_value_array['paymodstatus'])){ 
					$paystatus = "1";
				}else{
					$paystatus = '';
				}

				$result3=DB::update(COMPANY_PAYMENT_MODULES)->set(array('pay_active'=>$paystatus,'pay_mod_default'=>$default))
					->where('compay_payment_id','=',$id)
					->execute();
			}
		}
		/***Company payment settings Insert***/
		elseif(isset($post_value_array['payid_add'])){
			foreach($post_value_array['payid_add'] as $k=>$id)
			{  
		
				if($id == $post_value_array['default'][0]){
					$default = '1';
				}else{
					$default = '0';
				}

				if(in_array($id,$post_value_array['paymodstatus'])){ 
					$paystatus = "1";
				}else{
					$paystatus = '';
				}
				
				$pay_result =  DB::insert('company_payment_module',array('company_id','company_user_id','pay_mod_id','pay_mod_name','pay_mod_image','pay_active','pay_mod_default'))
					->values(array($company_id,$uid,$post_value_array['payid_add'][$k],$post_value_array['paymodname'][$k],$post_value_array['paymodimage'][$k],$paystatus,$default))
					->execute();
			}
		}

		if(isset($pay_result) || isset($result3) )
		{
			return 1;
		}
		else
		{
			return 0;
		}

	}

	public function check_array($value="")
	{	

			if(!empty($value))
			{ 
				return true;
			}
			else
			{	
				return false;		
			}
	}
	//** Function to check whether the given value is below 100 **//
	public static function check_percentage($percentage)
	{
		if($percentage > 100) {
			return false;
		} else {
			return true;
		}
	}
	
	public function updatesiteinfo_bannerimage($image,$cid)
	{
		$sql="select banner_image from ".COMPANY." where cid='$cid'";
		$results = Db::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($results[0]['banner_image'])){
			$id1 = $results[0]['banner_image'];
			if(file_exists($id1)){
				unlink($id1);
			}
		}
		$query = array('banner_image' => $image);
		$result = DB::update(COMPANY)->set($query)->where('cid', '=' ,'1')->execute();
		return $result;
	}


}
?>
