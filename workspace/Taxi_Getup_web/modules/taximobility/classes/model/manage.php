<?php defined('SYSPATH') OR die('No Direct Script Access');

/******************************************

* Contains Users module details

* @Package: ConnectTaxi

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************/

Class Model_Manage extends Model
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
		$this->Commonmodel = Model::factory('Commonmodel');
		$managemodel = Model::factory('managemodel');
		$this->app_name= SITENAME;
		$this->siteemail= SITE_EMAILID;
		$this->emailtemplate=Model::factory('emailtemplate');
	}
	
	public function company_list()
	{
	
		$result = DB::select()->from(COMPANY)
			->join(PEOPLE, 'LEFT')->on(PEOPLE.'.id', '=', COMPANY.'.userid')
			->where(PEOPLE.'.user_type', '=', 'C')
			->where(PEOPLE.'.status', '=', 'A')
			->order_by('created_date','desc')
			->execute()
			->as_array();
	 	return $result;
	}
	
	public function count_company_list()
	{
		$result = DB::select()->from(COMPANY)
			->join(PEOPLE, 'LEFT')->on(PEOPLE.'.id', '=', COMPANY.'.userid')
			->where(PEOPLE.'.user_type', '=', 'C')
			->where(PEOPLE.'.status', '=', 'A')
			->order_by('created_date','desc')
			->execute()
			->as_array();
		return count($result);
	}
	
	public function all_company_list($offset, $val)
	{
		
		$result = DB::select()->from(COMPANY)
				->join(PEOPLE, 'LEFT')->on(PEOPLE.'.id', '=', COMPANY.'.userid')
				->where(PEOPLE.'.user_type', '=', 'C')
				->where(PEOPLE.'.status', '=', 'A')
				->order_by('created_date','desc')->limit($val)->offset($offset)
				->execute()
				->as_array();
		
		$details = array();
		foreach($result as $key => $res)		
		{
			$details[$key]['no_of_taxi'] = $this->taxicount($res['cid']);
			$details[$key]['no_of_driver'] = $this->drivercount($res['cid']);
			$details[$key]['no_of_manager'] = $this->managercount($res['cid']);
			$details[$key]['no_of_package'] = $this->packagecount($res['cid']);
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
	
	public function packagecount($cid)
	{
		$result = DB::select()->from(PACKAGE_REPORT)->where(PACKAGE_REPORT.'.upgrade_companyid', '=', $cid)
			->execute()
			->as_array();
			
			return count($result);
	}

	public function get_front_company_request($activeids)
	{
	   $res_com = DB::select(PEOPLE.'.*')->from(PEOPLE)
	   ->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
	   ->where( COMPANY.'.company_status','=','D')->where(PEOPLE.'.login_from','=','WD')->where(PEOPLE.'.user_type', '=', 'C')->where(PEOPLE.'.id', 'IN', $activeids)
			->execute()
			->as_array();		
			return $res_com;
	}

	
	public function active_company_request($activeids)
	{
		    //check whether id is exist in checkbox or single active request
		    //==================================================================   
		    	       
		$result = DB::update(PEOPLE)->set(array('status' => 'A'))->where('id', 'IN', $activeids)->where('user_type', '=', 'C')
			->execute();
			
			             
		$result = DB::update(COMPANY)->set(array('company_status' => 'A'))->where('userid', 'IN', $activeids)
			->execute();

			$result_company = DB::select('company_id')->from(PEOPLE)
				->where('id', 'IN', $activeids)
				->where('user_type','=','C')
				->execute()
				->as_array();
					
					if(count($result_company) >0){
						$company_id=array();
						foreach($result_company as $k){
							$company_id[]=$k['company_id'];
						 }
						 if(count($company_id) >0){
							 // updating below users of company
							$result = DB::update(PEOPLE)->set(array('status' => 'A'))->where('company_id', 'IN', $company_id)
							->execute();
							$result = DB::update(TAXI)->set(array('taxi_status' => 'A'))->where('taxi_company', 'IN', $company_id)
							->execute();

						 }
					}
			
		return count($result);
	}
	
	public function block_company_request($activeids)
	{
		    //check whether id is exist in checkbox or single active request
		    //==================================================================	       
		$result = DB::update(PEOPLE)->set(array('status' => 'D'))->where('id', 'IN', $activeids)->where('user_type', '=', 'C')
				->execute();
				
				
		$result = DB::update(COMPANY)->set(array('company_status' => 'D'))->where('userid', 'IN', $activeids)
			->execute();

			$result_company = DB::select('company_id')->from(PEOPLE)
				->where('id', 'IN', $activeids)
				->where('user_type','=','C')
				->execute()
				->as_array();
					
					if(count($result_company) >0){
						$company_id=array();
						foreach($result_company as $k){
							$company_id[]=$k['company_id'];
						 }
						 if(count($company_id) >0){
							 // updating below users of company
							$result = DB::update(PEOPLE)->set(array('status' => 'D'))->where('company_id', 'IN', $company_id)
							->execute();
							$result = DB::update(TAXI)->set(array('taxi_status' => 'D'))->where('taxi_company', 'IN', $company_id)
							->execute();

						 }
					}
			
		return count($result);
	 
	}

	public function driver_list()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];

	   	
	   	if($usertype =='M')
	   	{
		$rs = DB::select()->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->where('user_type','=','D')
				->where('status','!=','T')
				->where('login_country','=',$country_id)
				->where('login_state','=',$state_id)
				->where('login_city','=',$city_id)
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else if($usertype =='C')
		{
		
		$rs = DB::select()->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->where('user_type','=','D')
				->where('status','!=','T')
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else
		{
			$rs = DB::select()->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')			
				->where('user_type','=','D')
				->where('status','!=','T')
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
		
		}	
	}
	
	public function count_driver_list()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
		$rs = DB::select()->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')		
				->where('user_type','=','D')
				->where('status','!=','T')
				->where('login_country','=',$country_id)
				->where('login_state','=',$state_id)
				->where('login_city','=',$city_id)
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else if($usertype =='C')
		{
		
		$rs = DB::select()->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')			
				->where('user_type','=','D')
				->where('status','!=','T')
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else
		{
			$rs = DB::select('id')->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')			
				->where('user_type','=','D')
				->where('status','!=','T')
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
		
		}	
	}
	
	public function all_driver_list($offset, $val)
	{		
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		$company_id = $_SESSION['company_id'];	
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	$peoples = array();
	   	$people_list = DB::select('id','name')->from(PEOPLE)->execute()->as_array();
	   	if(!empty($people_list)){
			foreach($people_list as $p){
				$peoples[$p['id']] = $p['name'];
			}
		}
	   	
	   	if($usertype =='M')
	   	{
			$result = DB::select('account_balance','user_createdby','name','username','email','address','availability_status','company_name',PEOPLE.'.id','driver_license_id','shift_status','phone','country_name','city_name','state_name','userid','profile_picture',array(PEOPLE.'.status','driver_status'),DRIVER.'.update_date')->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
				->join(DRIVER, 'LEFT')->on(PEOPLE.'.id', '=', DRIVER.'.driver_id')
				->where('user_type','=','D')
				->where(PEOPLE.'.status','!=','T')
				->where('login_country','=',$country_id)
				->where('login_state','=',$state_id)
				->where('login_city','=',$city_id)
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')->limit($val)->offset($offset)
				->execute()
				->as_array();
	
			$details = array();
			foreach($result as $key => $res)		
			{
				//$details[$key]['created_by'] = $this->userNamebyId($res['user_createdby']);
				$details[$key]['created_by'] = (array_key_exists($res['user_createdby'],$peoples)) ? $peoples[$res['user_createdby']] : '';
				$details[$key]['name'] = $res['name'];
				$details[$key]['username'] = $res['username'];
				$details[$key]['email'] = $res['email'];
				$details[$key]['company_name'] = $res['company_name'];
				$details[$key]['address'] = $res['address'];
				$details[$key]['availability_status'] = $res['availability_status'];
				$details[$key]['status'] = $res['driver_status'];
				$details[$key]['id'] = $res['id'];
				$details[$key]['driver_license_id'] = $res['driver_license_id'];
				$details[$key]['shift_status'] = $res['shift_status'];
				$details[$key]['phone'] = $res['phone'];
				$details[$key]['country_name'] = $res['country_name'];
				$details[$key]['city_name'] = $res['city_name'];
				$details[$key]['state_name'] = $res['state_name'];
				$details[$key]['cid'] = $res['userid'];
				$details[$key]['photo'] = $res['profile_picture'];
				$details[$key]['driver_status'] = $res['driver_status'];
				$details[$key]['update_date'] = $res['update_date'];
				$details[$key]['account_balance'] = $res['account_balance'];
			}
			return $details;
		}
		else if($usertype =='C')
		{		
			$result = DB::select('account_balance','user_createdby','name','username','email','address','availability_status','company_name',PEOPLE.'.id','driver_license_id','shift_status','phone','country_name','city_name','state_name','userid','profile_picture',array(PEOPLE.'.status','driver_status'),DRIVER.'.update_date')->from(PEOPLE)
					->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
					->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
					->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
					->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
					->join(DRIVER, 'LEFT')->on(PEOPLE.'.id', '=', DRIVER.'.driver_id')
					->where('user_type','=','D')
					->where(PEOPLE.'.status','!=','T')
					->where('company_id','=',$company_id)
					->order_by('created_date','desc')->limit($val)->offset($offset)
					->execute()
					->as_array();

			$details = array();
			foreach($result as $key => $res)		
			{
				//$details[$key]['created_by'] = $this->userNamebyId($res['user_createdby']);
				$details[$key]['created_by'] = (array_key_exists($res['user_createdby'],$peoples)) ? $peoples[$res['user_createdby']] : '';
				$details[$key]['name'] = $res['name'];
				$details[$key]['username'] = $res['username'];
				$details[$key]['email'] = $res['email'];
				$details[$key]['address'] = $res['address'];	
				$details[$key]['availability_status'] = $res['availability_status'];			
				$details[$key]['company_name'] = $res['company_name'];
				$details[$key]['status'] = $res['driver_status'];
				$details[$key]['id'] = $res['id'];
				$details[$key]['driver_license_id'] = $res['driver_license_id'];
				$details[$key]['shift_status'] = $res['shift_status'];
				$details[$key]['phone'] = $res['phone'];
				$details[$key]['country_name'] = $res['country_name'];
				$details[$key]['city_name'] = $res['city_name'];
				$details[$key]['state_name'] = $res['state_name'];
				$details[$key]['cid'] = $res['userid'];
				$details[$key]['photo'] = $res['profile_picture'];
				$details[$key]['driver_status'] = $res['driver_status'];
				$details[$key]['update_date'] = $res['update_date'];
				$details[$key]['account_balance'] = $res['account_balance'];
					
			}
				return $details;
		}
		else
		{	
			$result = DB::select('account_balance','user_createdby','name','username','email','address','availability_status','company_name',PEOPLE.'.id','driver_license_id','shift_status','phone','country_name','city_name','state_name','userid','profile_picture',array(PEOPLE.'.status','driver_status'),DRIVER.'.update_date')->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
				->join(DRIVER, 'LEFT')->on(PEOPLE.'.id', '=', DRIVER.'.driver_id')
				->where(PEOPLE.'.user_type', '=', 'D')
				->where(PEOPLE.'.status','!=','T')
				->order_by('created_date','desc')->limit($val)->offset($offset)
				->execute()
				->as_array();

				$details = array();
				foreach($result as $key => $res)		
				{					
					//$details[$key]['created_by'] = $this->userNamebyId($res['user_createdby']);
					
					$details[$key]['created_by'] = (array_key_exists($res['user_createdby'],$peoples)) ? $peoples[$res['user_createdby']] : '';
					$details[$key]['name'] = $res['name'];
					$details[$key]['username'] = $res['username'];
					$details[$key]['email'] = $res['email'];
					$details[$key]['address'] = $res['address'];	
					$details[$key]['availability_status'] = $res['availability_status'];			
					$details[$key]['company_name'] = $res['company_name'];
					$details[$key]['status'] = $res['driver_status'];
					$details[$key]['id'] = $res['id'];
					$details[$key]['driver_license_id'] = $res['driver_license_id'];
					$details[$key]['shift_status'] = $res['shift_status'];
					$details[$key]['phone'] = $res['phone'];
					$details[$key]['country_name'] = $res['country_name'];
					$details[$key]['city_name'] = $res['city_name'];
					$details[$key]['state_name'] = $res['state_name'];
					$details[$key]['cid'] = $res['userid'];
					$details[$key]['photo'] = $res['profile_picture'];
					$details[$key]['driver_status'] = $res['driver_status'];
					$details[$key]['update_date'] = $res['update_date'];
					$details[$key]['account_balance'] = $res['account_balance'];
				}
				//echo '<pre>';print_r($details);exit;
				return $details;		
		}
		
	}



	public function active_driver_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       
             //$arr_chk = " userid in ('" . implode("','",$activeids) . "') ";	
             
             $result = DB::update(PEOPLE)->set(array('status' => 'A'))->where('id', 'IN', $activeids)
			->execute();
			
		        	  
			 return count($result);
	}
	
	public function block_driver_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       

             //$arr_chk = " userid in ('" . implode("','",$activeids) . "') ";	
             
                    $result = DB::update(PEOPLE)->set(array('status' => 'D'))->where('id', 'IN', $activeids)
			->execute();
		        	  
			 return count($result);
	}
		
	public function motor_list()
	{
	   $rs = DB::select()->from(MOTORCOMPANY)
				->where('motor_status','!=','T')
				->order_by('motor_name','ASC')
				->execute()
				->as_array();
	   return $rs;
	}
	
	public function count_motor_list()
	{
		 $rs = DB::select()->from(MOTORCOMPANY)
					->where('motor_status','!=','T')
					->order_by('motor_name','ASC')
					->execute()
					->as_array();
		 return count($rs);
	}
	
	public function all_motor_list($offset, $val)
	{
		$result = DB::select()->from(MOTORCOMPANY)->where('motor_status','!=','T')->order_by('motor_name','ASC')->limit($val)->offset($offset)
			->execute()
			->as_array();
		  return $result;
		   
	}
	
	public function active_motor_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================

	            //$arr_chk = " motor_id in ('" . implode("','",$activeids) . "') ";	
           
	            $result = DB::update(MOTORCOMPANY)->set(array('motor_status' => 'A'))->where('motor_id', 'IN', $activeids)
			->execute();
  
			 return count($result);
	}
	
	public function block_motor_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       

            // $arr_chk = " motor_id in ('" . implode("','",$activeids) . "') ";	
            
       	            $result = DB::update(MOTORCOMPANY)->set(array('motor_status' => 'D'))->where('motor_id', 'IN', $activeids)
			->execute();
	        	  
			 return count($result);
	}

	public function model_list()
	{
	
			$result = DB::select()->from(MOTORMODEL)->join(MOTORCOMPANY, 'LEFT')->on(MOTORMODEL.'.motor_mid', '=', MOTORCOMPANY.'.motor_id')->where('model_status','!=','T')->order_by('model_name','ASC')
				->execute()
				->as_array();
			
	   return $result;
	}
	
	public function count_model_list()
	{
		$result = DB::select(array(DB::expr("COUNT('model_id')"), 'count'))->from(MOTORMODEL)
						->join(MOTORCOMPANY, 'LEFT')->on(MOTORMODEL.'.motor_mid', '=', MOTORCOMPANY.'.motor_id')
						->where('model_status','!=','T')->order_by('model_name','ASC')
					->execute()
					->as_array();
		 return !empty($result) ? $result[0]['count'] : 0;
	}
	
	public function all_model_list($offset, $val)
	{
		$result =DB::select('model_id','model_status','model_name','motor_name')->from(MOTORMODEL)->join(MOTORCOMPANY, 'LEFT')->on(MOTORMODEL.'.motor_mid', '=', MOTORCOMPANY.'.motor_id')->where('model_status','!=','T')->order_by('model_name','ASC')->limit($val)->offset($offset)
			->execute()
			->as_array();
		  return $result;
		   
	}
	
	public function count_fare_list($company_id)
	{		
		$query = "select * from ".COMPANY_MODEL_FARE." left join ".MOTORMODEL." on ".MOTORMODEL.".model_id = ".COMPANY_MODEL_FARE.".model_id where ".COMPANY_MODEL_FARE.".company_cid = $company_id and `model_status` != 'T' ORDER BY ".COMPANY_MODEL_FARE.".`model_name` ASC";

	 	$result = Db::query(Database::SELECT, $query)
	 	->execute()			
		->as_array();

		 return count($result);
	}
	
	public function all_fare_list($company_id,$offset, $val)
	{
		$query = "select * from ".COMPANY_MODEL_FARE." where ".COMPANY_MODEL_FARE.".company_cid = $company_id  ORDER BY ".COMPANY_MODEL_FARE.".`model_name` ASC limit  $offset,$val";
//left join ".MOTORMODEL." on ".MOTORMODEL.".model_id = ".COMPANY_MODEL_FARE.".model_id and `model_status` != 'T'
	 	$result = Db::query(Database::SELECT, $query)
	 	->execute()			
		->as_array();
		 return $result;
	}
	
	public function count_banner_list($company_id)
	{		
		$query = "select * from ".COMPANY_CMS." where company_id = $company_id and type= '2' ORDER BY `id` ASC";

	 	$result = Db::query(Database::SELECT, $query)
	 	->execute()			
		->as_array();

		 return count($result);
	}
	
	public function all_banner_list($company_id,$offset, $val)
	{
		$query = "select * from ".COMPANY_CMS." where company_id = $company_id and type= '2' ORDER BY `id` ASC limit  $offset,$val";

	 	$result = Db::query(Database::SELECT, $query)
	 	->execute()			
		->as_array();

		 return $result;
	}
	
	public function count_company_searchbanner_list($keyword = "", $status = "")
	{
		$company_id = $_SESSION['company_id'];
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where="";
		if($status!= ''){
			$staus_where= " AND status = '$status'";
		}

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (image_tag LIKE  '%$keyword%' or alt_tags LIKE  '%$keyword%')"; 

		}
			
			$query = "select * from ".COMPANY_CMS." where company_id = $company_id and type= '2'$staus_where $name_where  ORDER BY `id` ASC";
		//echo $query; exit;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return count($results);
	}
	
	public function get_company_all_banner_searchlist($keyword = "", $status = "",$offset ="",$val ="")
	{
		
		$company_id = $_SESSION['company_id'];
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);
		
		$staus_where="";
		if($status!= ''){
			$staus_where= " AND status = '$status'";
		}

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (image_tag LIKE  '%$keyword%' or alt_tags LIKE  '%$keyword%')"; 

		}
			
			$query = "select * from ".COMPANY_CMS." where company_id = $company_id and type= '2'$staus_where $name_where ORDER BY `id` ASC limit  $offset,$val";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;

	}
	
	public function block_banner_request($activeids)
	{           
        $result = DB::update(COMPANY_CMS)->set(array('status' => '0'))->where('id', 'IN', $activeids)
		->execute();
	       	  
		 return count($result);
	}
	
	public function active_banner_request($activeids)
	{
	    $result = DB::update(COMPANY_CMS)->set(array('status' => '1'))->where('id', 'IN', $activeids)
		->execute();
  		 return count($result);
	}
	
	public function count_company_searchmodel_list($keyword = "", $status = "")
	{
		$company_id = $_SESSION['company_id'];
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND ".COMPANY_MODEL_FARE.".fare_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (".COMPANY_MODEL_FARE.".model_name LIKE  '%$keyword%')"; 

		}
			$query = "select * from ".COMPANY_MODEL_FARE." left join ".MOTORMODEL." on ".MOTORMODEL.".model_id = ".COMPANY_MODEL_FARE.".model_id where ".COMPANY_MODEL_FARE.".company_cid = $company_id and ".MOTORMODEL.".model_status != 'T' $staus_where $name_where ORDER BY ".COMPANY_MODEL_FARE.".model_name ASC";
		//echo $query; exit;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return count($results);
	}

	public function get_all_company_list()
	{
		$query = "select cid,company_name from ".COMPANY." join ".PACKAGE_REPORT." on ".PACKAGE_REPORT.".upgrade_companyid = ".COMPANY.".cid where ".COMPANY.".company_status = 'A' group by ".COMPANY.".cid ORDER BY ".COMPANY.".company_name ASC ";
		//echo $query; exit;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;
	}

	public function get_rating_company()
	{
		$query = "select cid,company_name from ".COMPANY." where company_status = 'A' ORDER BY company_name ASC ";
		//echo $query; exit;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;

	
	}
	
	public function get_company_all_model_searchlist($keyword = "", $status = "",$offset ="",$val ="")
	{
		$company_id = $_SESSION['company_id'];
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND ".COMPANY_MODEL_FARE.".fare_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (".COMPANY_MODEL_FARE.".model_name LIKE  '%$keyword%')"; 

		}
			
			$query = "select * from ".COMPANY_MODEL_FARE." left join ".MOTORMODEL." on ".MOTORMODEL.".model_id = ".COMPANY_MODEL_FARE.".model_id where ".COMPANY_MODEL_FARE.".company_cid = $company_id and ".MOTORMODEL.".model_status != 'T' $staus_where $name_where ORDER BY ".COMPANY_MODEL_FARE.".model_name ASC limit  $offset,$val";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;

	}
	
	public function active_model_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================

	            //$arr_chk = " motor_id in ('" . implode("','",$activeids) . "') ";	
           
	            $result = DB::update(MOTORMODEL)->set(array('model_status' => 'A'))->where('model_id', 'IN', $activeids)
			->execute();
  
			 return count($result);
	}
	
	public function active_fare_request($activeids)
	{
	    $result = DB::update(COMPANY_MODEL_FARE)->set(array('fare_status' => 'A'))->where('company_model_fare_id', 'IN', $activeids)
		->execute();
  		 return count($result);
	}
	
	public function block_model_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       

            // $arr_chk = " motor_id in ('" . implode("','",$activeids) . "') ";	
            
       	            $result = DB::update(MOTORMODEL)->set(array('model_status' => 'D'))->where('model_id', 'IN', $activeids)
			->execute();
	        	  
			 return count($result);
	}
	
	public function block_fare_request($activeids)
	{           
        $result = DB::update(COMPANY_MODEL_FARE)->set(array('fare_status' => 'D'))->where('company_model_fare_id', 'IN', $activeids)
		->execute();
	       	  
		 return count($result);
	}

	public function field_list()
	{
	   $rs = DB::select()->from(MANAGEFIELD)
				->order_by('field_order','ASC')
				->execute()

				->as_array();
	   return $rs;
	}
	
	public function count_field_list()
	{
		 $rs = DB::select()->from(MANAGEFIELD)
					->order_by('field_order','ASC')
					->execute()
					->as_array();
		 return count($rs);
	}
	
	public function all_field_list($offset, $val)
	{
		$result = DB::select()->from(MANAGEFIELD)
				->where('field_status','=','A')
				->or_where('field_status','=','D')
				->order_by('field_order','ASC')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
		  return $result;
		   
	}

	public function fieldsearch_list($keyword = "", $status = "")
	{

			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);


		$staus_where= ($status) ? " AND field_status = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (field_labelname LIKE  '%$keyword%' ";
			$name_where .= " or field_name LIKE '%$keyword%' escape '!' ) ";
       		 }


			








			
			$query = " select * from " . MANAGEFIELD . " where 1=1 $staus_where $name_where order by field_order ASC ";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				return $results;

   	}
   	
	public function count_fieldsearch_list($keyword = "", $status = "")
	{

			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);


		$staus_where= ($status) ? " AND field_status = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (field_labelname LIKE  '%$keyword%' ";
			$name_where .= " or field_name LIKE '%$keyword%' escape '!' ) ";
       		 }


			
			
			$query = " select * from " . MANAGEFIELD . " where 1=1 $staus_where $name_where order by field_order ASC ";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				return count($results);

   	}
   	
	public function get_all_field_searchlist($keyword = "", $status = "",$offset = "", $val = "")
	{

			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);


		$staus_where= ($status) ? " AND field_status = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (field_labelname LIKE  '%$keyword%' ";
			$name_where .= " or field_name LIKE '%$keyword%' escape '!' ) ";
       		 }


			
			
			$query = " select * from " . MANAGEFIELD . " where 1=1 $staus_where $name_where order by field_order ASC limit $val offset $offset";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				return $results;

   	}


	
	public function active_field_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================

	            //$arr_chk = " motor_id in ('" . implode("','",$activeids) . "') ";	
           
	            $result = DB::update(MANAGEFIELD)->set(array('field_status' => 'A'))->where('field_id', 'IN', $activeids)
			->execute();
  
			 return count($result);
	}
	
	public function block_field_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       

            // $arr_chk = " motor_id in ('" . implode("','",$activeids) . "') ";	
            
       	            $result = DB::update(MANAGEFIELD)->set(array('field_status' => 'D'))->where('field_id', 'IN', $activeids)
			->execute();
	        	  
			 return count($result);
	}
	public function change_order_request($activeids)
	{
		$split_val = explode('_',$activeids);
		
		$field_id = $split_val[0];
		$field_order = $split_val[1];
		$order_id = $split_val[2];

	        //check whether id is exist in checkbox or single active request
		    //==================================================================
		$rs = DB::select()->from(MANAGEFIELD)->where('field_order','=',$field_order)
					->execute()
					->as_array();

		

		if($field_order < $order_id)
		{	
			$set_order = $field_order;
			
			for($i=$field_order; $i<$order_id;$i++)
			{
			$set_order++;   		    
			/* echo '<br/>'.$set_order.'=>'.$i;
			echo '<br/>'."update manage_field set field_order = ".$i." field_order = ".$set_order; */
			$result = DB::update(MANAGEFIELD)->set(array('field_order' => $i))->where('field_order', '=', $set_order)
				->execute();


			}
			/* echo '<br/>'."update manage_field set field_order = ".$order_id." field_id = ".$field_id; */
			$result = DB::update(MANAGEFIELD)->set(array('field_order' => $order_id))->where('field_id', '=', $field_id)
				->execute();
			   		  
		}
		else
		{
			
			$set_value = $set_order = $field_order;
			
			for($i=$order_id; $i<$field_order;$i++)
			{
			$set_value --;   		    
			
			/* echo '<br/>'.$set_order.'=>'.$i;
			echo '<br/>'."update manage_field set field_order = ".$set_order." field_order = ".$set_value; */
			$result = DB::update(MANAGEFIELD)->set(array('field_order' => $set_order))->where('field_order', '=', $set_value)
				->execute();
			$set_order--;

			}
			/* echo '<br/>'."update manage_field set field_order = ".$order_id." field_id = ".$field_id; */
	       	        $result = DB::update(MANAGEFIELD)->set(array('field_order' => $order_id))->where('field_id', '=', $field_id)
				   ->execute();			
		
		}
//exit;
			 return count($result);
	}


	public function taxi_list()
	{
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{

	   	
		$rs = DB::select()->from(TAXI)->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')->join(MOTORCOMPANY, 'LEFT')->on(TAXI.'.taxi_type', '=', MOTORCOMPANY.'.motor_id')->join(MOTORMODEL, 'LEFT')->on(TAXI.'.taxi_model', '=', MOTORMODEL.'.model_id')
				->where('taxi_country','=',$country_id)
				->where('taxi_state','=',$state_id)
				->where('taxi_city','=',$city_id)
				->where('taxi_company','=',$company_id)
				->where('taxi_status','!=','T')
				->order_by('taxi_id','desc')
				->execute()
				->as_array();
				
				return $rs;
				
				
		}
		else if($usertype =='C')
		{
		
		$rs = DB::select()->from(TAXI)->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')->join(MOTORCOMPANY, 'LEFT')->on(TAXI.'.taxi_type', '=', MOTORCOMPANY.'.motor_id')->join(MOTORMODEL, 'LEFT')->on(TAXI.'.taxi_model', '=', MOTORMODEL.'.model_id')
				->where('taxi_company','=',$company_id)
				->where('taxi_status','!=','T')
				->order_by('taxi_id','desc')
				->execute()
				->as_array();
				
				return $rs;
				
				
				
		}
		else
		{
			$rs = DB::select()->from(TAXI)->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')->join(MOTORCOMPANY, 'LEFT')->on(TAXI.'.taxi_type', '=', MOTORCOMPANY.'.motor_id')->join(MOTORMODEL, 'LEFT')->on(TAXI.'.taxi_model', '=', MOTORMODEL.'.model_id')
				->where('taxi_status','!=','T')
				->order_by('taxi_id','desc')
				->execute()
				->as_array();
				
				return $rs;
				
				
				
		
		}
	}
	
	public function count_taxi_list()
	{
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];	
	   	
	   	if($usertype =='M')
	   	{
			$rs = DB::select('taxi_id')->from(TAXI)->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')->join(MOTORCOMPANY, 'LEFT')->on(TAXI.'.taxi_type', '=', MOTORCOMPANY.'.motor_id')->join(MOTORMODEL, 'LEFT')->on(TAXI.'.taxi_model', '=', MOTORMODEL.'.model_id')
				->where('taxi_country','=',$country_id)
				->where('taxi_state','=',$state_id)
				->where('taxi_city','=',$city_id)
				->where('taxi_company','=',$company_id)
				->where('taxi_status','!=','T')
				->order_by('taxi_id','desc')
				->execute()

				->as_array();
				return $rs;
		}
		else if($usertype =='C')
		{
		
			$rs = DB::select('taxi_id')->from(TAXI)->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')->join(MOTORCOMPANY, 'LEFT')->on(TAXI.'.taxi_type', '=', MOTORCOMPANY.'.motor_id')->join(MOTORMODEL, 'LEFT')->on(TAXI.'.taxi_model', '=', MOTORMODEL.'.model_id')
				->where('taxi_company','=',$company_id)
				->where('taxi_status','!=','T')
				->order_by('taxi_id','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else
		{
			$rs = DB::select('taxi_id')->from(TAXI)->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')->join(MOTORCOMPANY, 'LEFT')->on(TAXI.'.taxi_type', '=', MOTORCOMPANY.'.motor_id')->join(MOTORMODEL, 'LEFT')->on(TAXI.'.taxi_model', '=', MOTORMODEL.'.model_id')
					->where('taxi_status','!=','T')
					->order_by('taxi_id','desc')
					->execute()
					->as_array();
			//echo '<pre>';print_r($rs);exit;
			return $rs;		
		}

	}
	
	public function all_taxi_list($offset, $val,$count = false)
	{
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];	
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	$details = array();
	   	
	   	$result = DB::select('taxi_id','taxi_availability','taxi_status','taxi_no','company_name','cid','motor_name','model_name','taxi_createdby','taxi_capacity','taxi_fare_km','userid',PEOPLE.'.name')->from(TAXI)
				->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')
				->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')
				->join(MOTORCOMPANY, 'LEFT')->on(TAXI.'.taxi_type', '=', MOTORCOMPANY.'.motor_id')
				->join(MOTORMODEL, 'LEFT')->on(TAXI.'.taxi_model', '=', MOTORMODEL.'.model_id')
				->join(PEOPLE, 'LEFT')->on(TAXI.'.taxi_createdby', '=', PEOPLE.'.id')
				->where('taxi_status','!=','T');
		if($usertype =='C'){
			
			$result = $result->where('taxi_company','=',$company_id);
		}
		
		if($usertype =='M'){
			
			$result = $result->where('taxi_company','=',$company_id)
							->where('taxi_country','=',$country_id)
							->where('taxi_state','=',$state_id)
							->where('taxi_city','=',$city_id);
		}
		if($count == true){
			
			$result = $result->execute()->as_array();
			return count($result);
		}else{
			
			$result = $result->order_by('taxi_id','desc')->limit($val)->offset($offset)
				->execute()
				->as_array();
			foreach($result as $key => $res)		
			{
				//$details[$key]['created_by'] = $this->userNamebyId($res['taxi_createdby']);
				$details[$key]['created_by'] = $res['name'];
				$details[$key]['taxi_id'] = $res['taxi_id'];
				$details[$key]['taxi_availability'] = $res['taxi_availability'];
				$details[$key]['taxi_status'] = $res['taxi_status'];
				$details[$key]['taxi_no'] = $res['taxi_no'];
				$details[$key]['company_name'] = $res['company_name'];	
				$details[$key]['motor_name'] = $res['motor_name'];
				$details[$key]['model_name'] = $res['model_name'];
				$details[$key]['taxi_capacity'] = $res['taxi_capacity'];
				$details[$key]['taxi_fare_km'] = $res['taxi_fare_km'];
				$details[$key]['cid'] = $res['userid'];
			}
			//echo "<pre>"; print_r($details); exit;
			return $details;			
		}
		

		
		
		
		/*if($usertype =='M')
	   	{
			$result = DB::select('taxi_id','taxi_createdby','taxi_availability','taxi_status','taxi_no','company_name','motor_name','model_name','taxi_capacity','taxi_fare_km','userid')->from(TAXI)
				->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')
				->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')
				->join(MOTORCOMPANY, 'LEFT')->on(TAXI.'.taxi_type', '=', MOTORCOMPANY.'.motor_id')
				->join(MOTORMODEL, 'LEFT')->on(TAXI.'.taxi_model', '=', MOTORMODEL.'.model_id')
				->where('taxi_status','!=','T')
				->where('taxi_company','=',$company_id)
				->where('taxi_country','=',$country_id)
				->where('taxi_state','=',$state_id)
				->where('taxi_city','=',$city_id)				
				->order_by('taxi_id','desc')->limit($val)->offset($offset)
				->execute()
				->as_array();

		  //print_r($results);exit;
			$details = array();
			foreach($result as $key => $res)		
			{
					//$details[$key]['created_by'] = $this->userNamebyId($res['taxi_createdby']);
					$details[$key]['created_by'] = (array_key_exists($res['taxi_createdby'],$peoples)) ? $peoples[$res['taxi_createdby']] : '';
					$details[$key]['taxi_id'] = $res['taxi_id'];
					$details[$key]['taxi_availability'] = $res['taxi_availability'];
					$details[$key]['taxi_status'] = $res['taxi_status'];
					$details[$key]['taxi_no'] = $res['taxi_no'];
					$details[$key]['company_name'] = $res['company_name'];	
					$details[$key]['motor_name'] = $res['motor_name'];
					$details[$key]['model_name'] = $res['model_name'];
					$details[$key]['taxi_capacity'] = $res['taxi_capacity'];
					$details[$key]['taxi_fare_km'] = $res['taxi_fare_km'];
					$details[$key]['cid'] = $res['userid'];
	
			}
				
			 return $details;	
		  
		}
		else if($usertype =='C')
		{
		
		$result = DB::select()->from(TAXI)
				->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')
				->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')
				->join(MOTORCOMPANY, 'LEFT')->on(TAXI.'.taxi_type', '=', MOTORCOMPANY.'.motor_id')
				->join(MOTORMODEL, 'LEFT')->on(TAXI.'.taxi_model', '=', MOTORMODEL.'.model_id')
				->where('taxi_status','!=','T')
				->where('taxi_company','=',$company_id)
				->order_by('taxi_id','desc')->limit($val)->offset($offset)
				->execute()
				->as_array();
		 
		  
		  		$details = array();
				foreach($result as $key => $res)		
				{
					$details[$key]['created_by'] = $this->userNamebyId($res['taxi_createdby']);
					$details[$key]['taxi_id'] = $res['taxi_id'];
					$details[$key]['taxi_availability'] = $res['taxi_availability'];
					$details[$key]['taxi_status'] = $res['taxi_status'];
					$details[$key]['taxi_no'] = $res['taxi_no'];
					$details[$key]['company_name'] = $res['company_name'];	
					$details[$key]['motor_name'] = $res['motor_name'];
					$details[$key]['model_name'] = $res['model_name'];
					$details[$key]['taxi_capacity'] = $res['taxi_capacity'];
					$details[$key]['taxi_fare_km'] = $res['taxi_fare_km'];
					$details[$key]['cid'] = $res['userid'];
	
				}
			 return $details;	
		}
		else
		{
			$result = DB::select('taxi_id','taxi_availability','taxi_status','taxi_no','company_name','cid','motor_name','model_name','taxi_createdby','taxi_capacity','taxi_fare_km','userid',PEOPLE.'.name')->from(TAXI)
				->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')
				->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')
				->join(MOTORCOMPANY, 'LEFT')->on(TAXI.'.taxi_type', '=', MOTORCOMPANY.'.motor_id')
				->join(MOTORMODEL, 'LEFT')->on(TAXI.'.taxi_model', '=', MOTORMODEL.'.model_id')
				->join(PEOPLE, 'LEFT')->on(TAXI.'.taxi_createdby', '=', PEOPLE.'.id')
				->where('taxi_status','!=','T')
				->order_by('taxi_id','desc')->limit($val)->offset($offset)
				->execute()
				->as_array();

		  		$details = array();
				foreach($result as $key => $res)		
				{
					//$details[$key]['created_by'] = $this->userNamebyId($res['taxi_createdby']);
					$details[$key]['created_by'] = $res['name'];
					$details[$key]['taxi_id'] = $res['taxi_id'];
					$details[$key]['taxi_availability'] = $res['taxi_availability'];
					$details[$key]['taxi_status'] = $res['taxi_status'];
					$details[$key]['taxi_no'] = $res['taxi_no'];
					$details[$key]['company_name'] = $res['company_name'];	
					$details[$key]['motor_name'] = $res['motor_name'];
					$details[$key]['model_name'] = $res['model_name'];
					$details[$key]['taxi_capacity'] = $res['taxi_capacity'];
					$details[$key]['taxi_fare_km'] = $res['taxi_fare_km'];
					$details[$key]['cid'] = $res['userid'];
	
				}
			
			 return $details;			 
		}
		*/		   
	}
	
	
	public function active_taxi_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================

	            //$arr_chk = " motor_id in ('" . implode("','",$activeids) . "') ";	
           
	            $result = DB::update(TAXI)->set(array('taxi_status' => 'A'))->where('taxi_id', 'IN', $activeids)

			->execute();
  
			 return count($result);
	}
	
	public function block_taxi_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       

            // $arr_chk = " motor_id in ('" . implode("','",$activeids) . "') ";	
            
       	            $result = DB::update(TAXI)->set(array('taxi_status' => 'D'))->where('taxi_id', 'IN', $activeids)
			->execute();
	        	  
			 return count($result);
	}

	public function motorsearch_list($keyword = "", $status = "")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND motor_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (motor_name LIKE  '%$keyword%' )";
		}


		$query = " select * from " . MOTORCOMPANY . " where 1=1 $staus_where $name_where order by motor_name ASC";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;

   }
   
   	public function count_motorsearch_list($keyword = "", $status = "")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND motor_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (motor_name LIKE  '%$keyword%' )";
		}


		$query = " select * from " . MOTORCOMPANY . " where 1=1 $staus_where $name_where order by motor_name ASC";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return count($results);

   }
   	
	public function get_all_motor_searchlist($keyword = "", $status = "", $offset ="", $val="")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND motor_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (motor_name LIKE  '%$keyword%' )";
		}


		$query = " select * from " . MOTORCOMPANY . " where 1=1 $staus_where $name_where order by motor_name ASC limit $val offset $offset";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;

   }

  
	public function package_list()
	{
	   $rs = DB::select()->from(PACKAGE)
				->order_by('package_name','ASC')
				->execute()
				->as_array();
	   return $rs;
	}
	
	public function count_package_list()
	{
		 $rs = DB::select()->from(PACKAGE)
					->execute()
					->as_array();
		 return count($rs);
	}
	
	public function all_package_list($offset, $val)
	{
		
		$result = DB::select()->from(PACKAGE)->order_by('package_name','asc')->limit($val)->offset($offset)
			->execute()
			->as_array();
			
		  return $result;
	}
	
	public function active_package_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       
             //$arr_chk = " userid in ('" . implode("','",$activeids) . "') ";	
             
             $result = DB::update(PACKAGE)->set(array('package_status' => 'A'))->where('package_id', 'IN', $activeids)
			->execute();
			
		        	  
			 return count($result);
	}
	
	public function block_package_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       

             //$arr_chk = " userid in ('" . implode("','",$activeids) . "') ";	
             
                    $result = DB::update(PACKAGE)->set(array('package_status' => 'D'))->where('package_id', 'IN', $activeids)
			->execute();
		        	  
			 return count($result);
	}

	public function packagesearch_list($keyword = "", $status = "")
	{

			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);


		$staus_where= ($status) ? " AND package_status = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (package_name LIKE  '%$keyword%')";
       		 }
		
			$query = " select * from " . PACKAGE . " where 1=1 $staus_where $name_where order by package_name ASC ";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				return $results;

   }
   
   	public function count_packagesearch_list($keyword = "", $status = "")
	{

			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);


		$staus_where= ($status) ? " AND package_status = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (package_name LIKE  '%$keyword%')";
       		 }
		
			$query = " select * from " . PACKAGE . " where 1=1 $staus_where $name_where order by package_name ASC ";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				return count($results);

   }
	public function get_all_package_searchlist($keyword = "", $status = "",$offset = "", $val = "")
	{

			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);


		$staus_where= ($status) ? " AND package_status = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (package_name LIKE  '%$keyword%')";
       		 }
		
			$query = " select * from " . PACKAGE . " where 1=1 $staus_where $name_where order by package_name ASC limit $val offset $offset ";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				return $results;

   }

	public function country_list()
	{
	   $rs = DB::select()->from(COUNTRY)
				->where('country_status','!=','T')
				->order_by('country_name','ASC')
				->execute()
				->as_array();
	   return $rs;
	}
	
	public function count_country_list()
	{
		 $rs = DB::select('country_id')->from(COUNTRY)
					//->where('country_status','!=','T')
					->order_by('country_name','ASC')
					->execute()
					->as_array();
		 return count($rs);
	}
	
	public function all_country_list($offset, $val)
	{
		$result = DB::select('country_id','country_status','country_name','iso_country_code','telephone_code','currency_code','currency_symbol','default')->from(COUNTRY)
			//->where('country_status','!=','T')
			->order_by('country_name','ASC')
			->limit($val)->offset($offset)
			->execute()
			->as_array();
		  return $result;
		   
	}
	
	/** update default country status **/
	public function update_default_country($id)
	{
	
			$country = DB::select()->from(COUNTRY)
				->where('country_id', '=' ,$id)
				->execute();

			if($id == DEFAULT_COUNTRY)
			{
				return -2;
			}
		
			if($country[0]['country_status'] == 'A')
			{
				$res = DB::update(SITEINFO)->set(array('site_country' => $id))->where('id', '=', '1')
					->execute();

				$result = DB::update(COUNTRY)->set(array('default' => '1'))
						->where('country_id', '=', $id)
						->execute();
								
				if($result==1){
					$result1 = DB::update(COUNTRY)->set(array('default' => '0'))->where('country_id', '!=', $id)->execute();
					DB::update(PEOPLE)->set(array('login_country' => $id))->where('id', '=', $_SESSION['userid'])->execute();
				}
				
				return $result;
			}
			else
			{
				return -1;
			}
			 
			 
	}
	
	/** update default state status **/
	public function update_default_state($id)
	{
		$state = DB::select()->from(STATE)
				->where('state_countryid', '=' ,DEFAULT_COUNTRY)
				->where('state_id' ,'=',$id)
				->execute();
		$state_country = $state[0]['state_countryid'];
		
		if($id == DEFAULT_STATE)
		{
			return -2;
		}
		if($state_country == DEFAULT_COUNTRY){
			
			if($state[0]['state_status'] == 'A')
			{		
				$result = DB::update(STATE)->set(array('default' => '1'))->where('state_id', '=', $id)
					->execute();
			
				$res = DB::update(SITEINFO)->set(array('site_state' => $id))->where('id', '=', '1')
					->execute();
				if($result==1){
					$result1 = DB::update(STATE)->set(array('default' => '0'))->where('state_id', '!=', $id)->execute();
					DB::update(PEOPLE)->set(array('login_state' => $id))->where('id', '=', $_SESSION['userid'])->execute();
				}
				
				return $result;
			}
			else
			{
				return -1;
			}
		}
		else{
			return 0;
		}
			 
			 
	}
	
	/** update default city status **/
	public function update_default_city($id)
	{
		$city = DB::select()->from(CITY)
				->where('city_countryid', '=' ,DEFAULT_COUNTRY)
				->where('city_stateid' ,'=',DEFAULT_STATE)
				->where('city_id' ,'=',$id)
				->execute();
				
		$city_country = $city[0]['city_countryid'];
		$city_state = $city[0]['city_stateid'];
		
		if($id == DEFAULT_CITY)
		{
			return -2;
		}
		
		if($city_country == DEFAULT_COUNTRY && $city_state == DEFAULT_STATE){
			
			if($city[0]['city_status'] =='A')
			{
				$result = DB::update(CITY)->set(array('default' => '1'))->where('city_id', '=', $id)
				->execute();

				$res = DB::update(SITEINFO)->set(array('site_city' => $id))->where('id', '=', '1')
				->execute();
				
				if($result==1){
					$result1 = DB::update(CITY)->set(array('default' => '0'))->where('city_id', '!=', $id)->execute();
					DB::update(PEOPLE)->set(array('login_city' => $id))->where('id', '=', $_SESSION['userid'])->execute();
				}
				 return $result;
			}
			else
			{
				return -1;
			} 
			 
		}else{
			return 0;
		}
	}
	
	public function active_country_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================

	            //$arr_chk = " motor_id in ('" . implode("','",$activeids) . "') ";	

           
	            $result = DB::update(COUNTRY)->set(array('country_status' => 'A'))->where('country_id', 'IN', $activeids)
			->execute();
  
			 return count($result);
	}
	
	public function block_country_request($activeids)
	{
			$result = DB::select('id')->from(PEOPLE)->where('login_country', 'IN', $activeids)->order_by('id','ASC')
					->execute()
					->as_array();
			
	   		$people_count = count($result);
	   		

			$result = DB::select('taxi_id')->from(TAXI)->where('taxi_country', 'IN', $activeids)->order_by('taxi_id','ASC')
					->execute()
					->as_array();
					
	   		$taxi_count = count($result);	   		
	   		
			if($people_count == 0 && $taxi_count ==0)
			{				
				$result = DB::update(COUNTRY)->set(array('country_status' => 'D'))->where('country_id', 'IN', $activeids)
				->execute();		

				return 1;
			}
			else
			{
			
				return 0;
			}
			
			 return count($result);
	}


	public function block_gateway($activeids)
	{
			$company_id = $_SESSION['company_id'];
			if($activeids)
			{	
				$result = DB::update(PAYMENT_GATEWAYS)->set(array('payment_status' => 'D'))
				->where('id', 'IN', $activeids)
				->where('company_id', '=', $company_id)
				->execute();		
				return 1;
			}
			else
			{
				return 0;
			}
			
			 
	}

	public function trash_gateway($activeids)
	{
		$company_id = $_SESSION['company_id'];
		
			if($activeids)
			{	
				$result = DB::update(PAYMENT_GATEWAYS)->set(array('payment_status' => 'T'))
				->where('id', 'IN', $activeids)
				->where('company_id', '=', $company_id)
				->execute();		
				return 1;
			}
			else
			{
				return 0;
			}
			
							
	}

	public function active_gateway($activeids)
	{
		
		    $company_id = $_SESSION['company_id'];
		
			if($activeids)
			{	
				$result = DB::update(PAYMENT_GATEWAYS)->set(array('payment_status' => 'A'))
				->where('id', 'IN', $activeids)
				->where('company_id', '=', $company_id)
				->execute();		
				return 1;
			}
			else
			{
				return 0;
			}
	}
	public function get_all_country_countlist($keyword = "", $status = "")
	{

			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		//condition for status
		//====================== 

		$staus_where= ($status) ? " AND country_status = '$status'" : "";
	
		//search result export
        //=====================
		$name_where="";	

		if($keyword){
			$name_where  = " AND (country_name LIKE  '%$keyword%')";
       		 }


			
			
			$query = " select count(country_id) as total from " . COUNTRY . " where 1=1 $staus_where $name_where order by country_name ASC ";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->get('total');
			 
				return $results;

	}

	public function get_all_country_searchlist($keyword = "", $status = "",$offset = "", $val = "")
	{

			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		//condition for status
		//====================== 

		$staus_where= ($status) ? " AND country_status = '$status'" : "";
	
		//search result export
        //=====================
		$name_where="";	

		if($keyword){
			$name_where  = " AND (country_name LIKE  '%$keyword%')";
       		 }


			
			
			$query = " select * from " . COUNTRY . " where 1=1 $staus_where $name_where order by country_name ASC limit $val offset $offset ";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				return $results;

	}
	public function trash_country_request($activeids)
	{
			$result = DB::select('id')->from(PEOPLE)->where('login_country', 'IN', $activeids)->order_by('id','ASC')
					->execute()
					->as_array();
			
	   		$people_count = count($result);
	   		

			$result = DB::select('taxi_id')->from(TAXI)->where('taxi_country', 'IN', $activeids)->order_by('taxi_id','ASC')
					->execute()
					->as_array();
					
	   		$taxi_count = count($result);	   		
	   		
			if($people_count == 0 && $taxi_count ==0)
			{				
				$result = DB::update(COUNTRY)->set(array('country_status' => 'T'))->where('country_id', 'IN', $activeids)
				->execute();		

				return 1;
			}
			else
			{
			
				return 0;
			}	
							
	}

	public function city_list()
	{
			$result = DB::select()->from(CITY)->join(STATE, 'LEFT')->on(CITY.'.city_stateid', '=', STATE.'.state_id')->join(COUNTRY, 'LEFT')->on(CITY.'.city_countryid', '=', COUNTRY.'.country_id')->where('city_status' ,'!=', 'T')->order_by('city_name','ASC')
				->execute()
				->as_array();
			
	   		return $result;
	}
	
	public function count_city_list()
	{
			$result = DB::select('city_id')->from(CITY)->join(STATE, 'LEFT')->on(CITY.'.city_stateid', '=', STATE.'.state_id')->join(COUNTRY, 'LEFT')->on(CITY.'.city_countryid', '=', COUNTRY.'.country_id')->order_by('city_name','ASC')
					->execute()
					->as_array(); //->where('city_status' ,'!=' ,'T')
		 return count($result);
	}
	
	public function all_city_list($offset, $val)
	{
		$result =DB::select(COUNTRY.'.country_name',STATE.'.state_name',CITY.'.city_id',CITY.'.city_name',CITY.'.city_status',CITY.'.city_model_fare',array(CITY.'.default','city_default'))->from(CITY)->join(STATE, 'LEFT')->on(CITY.'.city_stateid', '=', STATE.'.state_id')->join(COUNTRY, 'LEFT')->on(CITY.'.city_countryid', '=', COUNTRY.'.country_id')->order_by('city_name','ASC')->limit($val)->offset($offset)
			->execute()
			->as_array(); //->where('city_status', '!=', 'T')
		  return $result;
		   
	}
	
	public function active_city_request($activeids)
	{

	            $result = DB::update(CITY)->set(array('city_status' => 'A'))->where('city_id', 'IN', $activeids)
			->execute();
  
			 return count($result);
	}
	
	public function block_city_request($activeids)
	{
		$result = DB::select()->from(SITEINFO)->where('site_city', 'IN', $activeids)->order_by('id','ASC')
				->execute()
				->as_array();
				
		$site_city_count = count($result);
		if($site_city_count == 0)
		{
     	     $result = DB::update(CITY)->set(array('city_status' => 'D'))->where('city_id', 'IN', $activeids)
			->execute();
			return 1;
		}
		else
		{
			return 0;
		}
		 return count($result);
	}


	
	public function trash_city_request($activeids)
	{
		$result = DB::select()->from(SITEINFO)->where('site_city', 'IN', $activeids)->order_by('id','ASC')
				->execute()
				->as_array();
				
		$site_city_count = count($result);
		if($site_city_count == 0)
		{
			$result = DB::update(CITY)->set(array('city_status' => 'T'))->where('city_id', 'IN', $activeids)
			->execute();
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	public function state_list()
	{
			$result = DB::select()->from(STATE)->join(COUNTRY, 'LEFT')->on(STATE.'.state_countryid', '=', COUNTRY.'.country_id')->where('state_status' ,'!=','T')->order_by('state_name','ASC')
				->execute()
				->as_array();
			
	   return $result;
	}
	
	public function count_state_list()
	{
			$result = DB::select('state_id')->from(STATE)->join(COUNTRY, 'LEFT')->on(STATE.'.state_countryid', '=', COUNTRY.'.country_id')->order_by('state_name','ASC')
					->execute()
					->as_array(); //->where('state_status' ,'!=','T')
		 return count($result);
	}
	
	public function all_state_list($offset, $val)
	{
		$result = DB::select(COUNTRY.'.country_name',STATE.'.state_id',STATE.'.state_status',STATE.'.state_name',array(STATE.'.default', 'state_default'))->from(STATE)->join(COUNTRY, 'LEFT')->on(STATE.'.state_countryid', '=', COUNTRY.'.country_id')->order_by('state_name','ASC')->limit($val)->offset($offset)
			->execute()
			->as_array();//->where('state_status' ,'!=','T')
		  return $result;
	}
	
	public function active_state_request($activeids)
	{
		$result = DB::update(STATE)->set(array('state_status' => 'A'))->where('state_id', 'IN', $activeids)
			->execute();  
			return count($result);
	}
	
	public function block_state_request($activeids)
	{

		$result = DB::select()->from(SITEINFO)->where('site_state', 'IN', $activeids)->order_by('id','ASC')
				->execute()
				->as_array();
				
		$state_count = count($result);
		
		if($state_count == 0)
		{
			$result = DB::update(STATE)->set(array('state_status' => 'D'))->where('state_id', 'IN', $activeids)
			->execute();
		 return 1;
		}
		else
		{
		
			return 0;
		}
		return count($result);
	}

	public function searchstate_list($keyword = "", $status = "")
	{
	
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND state_status = '$status'" : "";
		//search result export
		//=====================
		$name_where="";	
		if($keyword){
		$name_where  = " AND (state_name LIKE  '%$keyword%'"; 
		$name_where .= " or country_name LIKE  '%$keyword%' )";
		}
		$query = " select * from " . STATE . " left join " . COUNTRY . " on ". STATE .".state_countryid =".COUNTRY.".country_id  where 1=1 $staus_where $name_where order by state_name ASC";
		
		$results = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return $results;
		
	}
	
	public function count_searchstate_list($keyword = "", $status = "")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);		
		$staus_where= ($status) ? " AND state_status = '$status'" : "";
		//search result export
		//=====================
		$name_where="";
		if($keyword){
			$name_where  = " AND (state_name LIKE  '%$keyword%'";
			$name_where .= " or country_name LIKE  '%$keyword%' )";
		}
		$query = " select state_id from " . STATE . " left join " . COUNTRY . " on ". STATE .".state_countryid =".COUNTRY.".country_id  where 1=1 $staus_where $name_where order by state_name ASC";
		$results = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return count($results);
	}
	
  	public function get_all_state_searchlist($keyword = "", $status = "",$offset ="",$val ="")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND state_status = '$status'" : "";
		//search result export
		//=====================
		$name_where="";
		if($keyword){
			$name_where  = " AND (state_name LIKE  '%$keyword%'"; 
			$name_where .= " or country_name LIKE  '%$keyword%' )";
		}
		$query = " select ".COUNTRY.".country_name,".STATE.".state_id,".STATE.".state_status,".STATE.".state_name,".STATE.".default as state_default from " . STATE . " left join " . COUNTRY . " on ". STATE .".state_countryid =".COUNTRY.".country_id  where 1=1 $staus_where $name_where order by state_name ASC limit $val offset  $offset";
		$results = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return $results;
	}
	
	public function searchcity_list($keyword = "", $status = "")
	{
	
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND city_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (city_name LIKE  '%$keyword%'"; 
		$name_where  .= " or state_name LIKE  '%$keyword%'"; 
		$name_where .= " or country_name LIKE  '%$keyword%' )";

		}
		
	
		$query = " select * from " . CITY . " left join ".STATE." on ".CITY.".city_stateid =".STATE.".state_id left join " . COUNTRY . " on ". CITY .".city_countryid =".COUNTRY.".country_id  where 1=1 $staus_where $name_where order by city_name ASC";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return $results;
		
	}
	
	public function count_searchcity_list($keyword = "", $status = "")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND city_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";

		if($keyword){
		$name_where  = " AND (city_name LIKE  '%$keyword%'"; 
		$name_where  .= " or state_name LIKE  '%$keyword%'"; 
		$name_where .= " or country_name LIKE  '%$keyword%' )";

		}
		
	
		$query = " select city_id from " . CITY . " left join ".STATE." on ".CITY.".city_stateid =".STATE.".state_id left join " . COUNTRY . " on ". CITY .".city_countryid =".COUNTRY.".country_id  where 1=1 $staus_where $name_where order by city_name ASC";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return count($results);
	}
	
  	public function get_all_city_searchlist($keyword = "", $status = "",$offset ="",$val ="")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND city_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (city_name LIKE  '%$keyword%'";
		$name_where .= " or state_name LIKE  '%$keyword%'";
		$name_where .= " or country_name LIKE  '%$keyword%' )";

		}
		
		$query = " select ".COUNTRY.".country_name,".STATE.".state_name,".CITY.".city_id,".CITY.".city_name,".CITY.".city_status,".CITY.".city_model_fare,".CITY.".default as city_default from " . CITY . " left join ".STATE." on ".CITY.".city_stateid =".STATE.".state_id left join " . COUNTRY . " on ". CITY .".city_countryid =".COUNTRY.".country_id  where 1=1 $staus_where $name_where order by city_name ASC limit $val offset  $offset";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();


		return $results;
	}

	public function searchmanager_list($keyword = "", $status = "", $company = "")
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];	
	   	
	   	if($usertype =='M')
	   	{
			$user_created_where = " AND user_createdby = $user_createdby AND company_id = $company_id ";
		}
		else if($usertype =='C')
	   	{
			$user_created_where = " AND company_id = $company_id ";
		}
		else
		{
			$user_created_where = "";
		}
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		$company_where= ($company) ? " AND cid = '$company'" : "";
		$staus_where= ($status) ? " AND status = '$status'" : "";

		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%' ";			
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
		 }			
			
			$query = " select * from " . PEOPLE . " left join ".COMPANY." on ".PEOPLE.".company_id = ".COMPANY.".cid  left join ".COUNTRY." on ".PEOPLE.".login_country = ".COUNTRY.".country_id   left join ".STATE." on ".PEOPLE.".login_state = ".STATE.".state_id    left join ".CITY." on ".PEOPLE.".login_city = ".CITY.".city_id  where ".PEOPLE.".user_type = 'M' $company_where $staus_where $name_where $user_created_where order by created_date DESC";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
			return $results;
		
	}
	
	public function count_searchmanager_list($keyword = "", $status = "",$company = "")
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];	
	   	
	   	if($usertype =='M')
	   	{
			$user_created_where = " AND user_createdby = $user_createdby AND company_id = $company_id ";
		}
		else if($usertype =='C')
	   	{
			$user_created_where = " AND company_id = $company_id ";
		}
		else
		{
			$user_created_where = "";
		}
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		$company_where= ($company) ? " AND cid = '$company'" : "";		
		$staus_where= ($status) ? " AND status = '$status'" : "";
		
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
       		 }


			$query = " select ".PEOPLE.".name,".COMPANY.".company_name,".PEOPLE.".email,".PEOPLE.".address,".COUNTRY.".country_name,".STATE.".state_name,".CITY.".city_name,".PEOPLE.".status from " . PEOPLE . " left join ".COMPANY." on ".PEOPLE.".company_id = ".COMPANY.".cid  left join ".COUNTRY." on ".PEOPLE.".login_country = ".COUNTRY.".country_id   left join ".STATE." on ".PEOPLE.".login_state = ".STATE.".state_id    left join ".CITY." on ".PEOPLE.".login_city = ".CITY.".city_id  where ".PEOPLE.".user_type = 'M' $company_where $staus_where $name_where $user_created_where order by created_date DESC";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			return $results;
	}
	
  	public function all_manager_searchlist($keyword = "", $status = "",$company = "", $offset ="",$val ="")
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];	
	   	
	   	if($usertype =='M')
	   	{
			$user_created_where = " AND user_createdby = $user_createdby AND company_id = $company_id ";
		}
		else if($usertype =='C')
	   	{
			$user_created_where = " AND company_id = $company_id ";
		}
		else
		{
			$user_created_where = "";
		}
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		$company_where= ($company) ? " AND cid = '$company'" : "";		
		$staus_where= ($status) ? " AND status = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
       		 }


			$query = " select id,status,name,company_name,userid,email,address,country_name,state_name,city_name from " . PEOPLE . " left join ".COMPANY." on ".PEOPLE.".company_id = ".COMPANY.".cid  left join ".COUNTRY." on ".PEOPLE.".login_country = ".COUNTRY.".country_id   left join ".STATE." on ".PEOPLE.".login_state = ".STATE.".state_id    left join ".CITY." on ".PEOPLE.".login_city = ".CITY.".city_id  where ".PEOPLE.".user_type = 'M' $company_where $staus_where $name_where $user_created_where order by created_date DESC limit $val offset  $offset ";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
			return $results;

	}


	public function count_searchadmin_list($keyword = "", $status = "")
	{

			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

	
		$staus_where= ($status) ? " AND status = '$status'" : "";
		
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
       		 }


			$query = " select id from " . PEOPLE . " left join ".COUNTRY." on ".PEOPLE.".login_country = ".COUNTRY.".country_id   left join ".STATE." on ".PEOPLE.".login_state = ".STATE.".state_id    left join ".CITY." on ".PEOPLE.".login_city = ".CITY.".city_id  where (".PEOPLE.".user_type = 'S' OR ".PEOPLE.".user_type = 'DA')  $staus_where $name_where order by created_date DESC";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 

		return $results;
	}
	
	
  	public function all_admin_searchlist($keyword = "", $status = "", $offset ="",$val ="")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);
		$staus_where= ($status) ? " AND status = '$status'" : "";
		$name_where="";
		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
       	}
		if($offset!="" && $val!=""){
			$query = " select ".PEOPLE.".id,".PEOPLE.".status,".PEOPLE.".name,".PEOPLE.".email,".PEOPLE.".address,".COUNTRY.".country_name,".STATE.".state_name,".CITY.".city_name from " . PEOPLE . " left join ".COUNTRY." on ".PEOPLE.".login_country = ".COUNTRY.".country_id   left join ".STATE." on ".PEOPLE.".login_state = ".STATE.".state_id    left join ".CITY." on ".PEOPLE.".login_city = ".CITY.".city_id  where (".PEOPLE.".user_type = 'S' OR ".PEOPLE.".user_type = 'DA') $staus_where $name_where order by created_date DESC limit $val offset  $offset ";
		}else{
			$query = " select ".PEOPLE.".id,".PEOPLE.".status,".PEOPLE.".name,".PEOPLE.".email,".PEOPLE.".address,".COUNTRY.".country_name,".STATE.".state_name,".CITY.".city_name from " . PEOPLE . " left join ".COUNTRY." on ".PEOPLE.".login_country = ".COUNTRY.".country_id   left join ".STATE." on ".PEOPLE.".login_state = ".STATE.".state_id left join ".CITY." on ".PEOPLE.".login_city = ".CITY.".city_id  where (".PEOPLE.".user_type = 'S' OR ".PEOPLE.".user_type = 'DA') $staus_where $name_where order by created_date DESC";
		}
	 	$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $results;
	}	


	public function searchcompany_list($keyword = "", $status = "",$company = '')
	{
	
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);
		
		$company_where= ($company) ? " AND cid = '$company'" : "";
		
		$staus_where= ($status) ? " AND company_status = '$status'" : "";
		
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
       		 }
			$query = " select * from " . COMPANY . " left join " .PEOPLE. " on ". PEOPLE.".id =".COMPANY.".userid  where 1=1 $company_where $staus_where $name_where order by created_date DESC";


	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
			return $results;
		
	}
	
	public function count_searchcompany_list($keyword = "", $status = "",$company = "")
	{
		$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		$company_where= ($company) ? " AND cid = '$company'" : "";	
		$staus_where= ($status) ? " AND company_status = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
       		 }
			$query = " select cid,name,username,email,company_name,company_address,company_status,id from " . COMPANY . " left join " .PEOPLE. " on ". PEOPLE.".id =".COMPANY.".userid  where user_type='C' $company_where $staus_where $name_where order by created_date DESC";


	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			$details = array();
			foreach($results as $key => $res)		
			{
				$details[$key]['no_of_taxi'] = $this->taxicount($res['cid']);
				$details[$key]['no_of_driver'] = $this->drivercount($res['cid']);
				$details[$key]['no_of_manager'] = $this->managercount($res['cid']);
				$details[$key]['no_of_package'] = $this->packagecount($res['cid']);
				$details[$key]['name'] = $res['name'];
				$details[$key]['username'] = $res['username'];
				$details[$key]['email'] = $res['email'];
				$details[$key]['company_name'] = $res['company_name'];
				$details[$key]['company_address'] = $res['company_address'];
				$details[$key]['company_status'] = $res['company_status'];
				$details[$key]['cid'] = $res['cid'];	
				$details[$key]['id'] = $res['id'];	
				
			}
		
		return $details;
	}
	
	public function all_company_searchlist($keyword = "", $status = "",$company = "",$offset ="",$val ="")
	{

		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$company_where= ($company) ? " AND cid = '$company'" : "";	
		$staus_where= ($status) ? " AND company_status = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
       		 }
			$query = " select name,username,email,company_name,company_address,address,company_status,cid,id from " . COMPANY . " left join " .PEOPLE. " on ". PEOPLE.".id =".COMPANY.".userid  where user_type='C' $company_where $staus_where $name_where order by created_date DESC limit $val offset  $offset";


	 		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			
			$details = $taxi_count = $driver_count = $manager_count = $package_count = array();			
			 # taxi count
			$taxi_result = DB::select('taxi_company',array(DB::expr("COUNT('taxi_id')"), 'taxi_count'))->from(TAXI)
						->group_by('taxi_company')
						->execute()
						->as_array();
			
			if(!empty($taxi_result)){
				foreach($taxi_result as $t){
					$taxi_count[$t['taxi_company']] = $t['taxi_count'];
				}
			}
			
			# driver& manager count
			$driver_result = DB::select('company_id','user_type',array(DB::expr("COUNT('id')"), 'count'))->from(PEOPLE)
						->group_by('company_id')
						->group_by('user_type')
						->order_by('company_id')
						->execute()
						->as_array();
						//echo '<pre>';print_r($driver_result);exit;
			if(!empty($driver_result)){
				foreach($driver_result as $d){
					($d['user_type'] =='D') ? $driver_count[$d['company_id']] = $d['count'] :'';
					($d['user_type'] =='M') ? $manager_count[$d['company_id']] = $d['count'] :'';
				}
			}
			
			/*			
			# package count
			$package_result = DB::select('upgrade_companyid',array(DB::expr("COUNT('upgrade_id')"), 'package_count'))->from(PACKAGE_REPORT)
					->group_by('upgrade_companyid')
					->execute()
					->as_array();
			if(!empty($package_result)){
				foreach($package_result as $p){
					$package_count[$p['upgrade_companyid']] = $p['package_count'];
				}
			}*/
			 
			foreach($result as $key => $res)		
			{
				/*$details[$key]['no_of_taxi'] = $this->taxicount($res['cid']);
				$details[$key]['no_of_driver'] = $this->drivercount($res['cid']);
				$details[$key]['no_of_manager'] = $this->managercount($res['cid']);
				$details[$key]['no_of_package'] = $this->packagecount($res['cid']);*/
				$details[$key]['no_of_taxi'] = (array_key_exists($res['cid'],$taxi_count)) ? $taxi_count[$res['cid']] : 0;
				$details[$key]['no_of_driver'] = (array_key_exists($res['cid'],$driver_count)) ? $driver_count[$res['cid']] : 0;
				$details[$key]['no_of_manager'] = (array_key_exists($res['cid'],$manager_count)) ? $manager_count[$res['cid']] : 0;
				$details[$key]['no_of_package'] = (array_key_exists($res['cid'],$package_count)) ? $package_count[$res['cid']] : 0;
				$details[$key]['name'] = $res['name'];
				$details[$key]['username'] = $res['username'];
				$details[$key]['email'] = $res['email'];
				$details[$key]['company_name'] = $res['company_name'];
				$details[$key]['company_address'] = $res['company_address'];
				$details[$key]['address'] = $res['address'];
				$details[$key]['company_status'] = $res['company_status'];
				$details[$key]['cid'] = $res['cid'];	
				$details[$key]['id'] = $res['id'];	
				
			}			 
			//echo '<pre>';print_r($details);exit;
			return $details;
   	}
	
	public function searchdriver_list($keyword = "", $status = "",$company = "")
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
			$user_created_where = " AND login_country = $country_id AND login_state = $state_id AND login_city = $city_id AND company_id = $company_id ";
		}
		else if($usertype =='C')
		{
			$user_created_where = " AND company_id = $company_id ";
		}
		else
		{
			$user_created_where = "";		
		}
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		$company_where= ($company) ? " AND cid = '$company'" : "";	
		$staus_where= ($status) ? " AND status = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
       		 }

			
			$query = " select * from " . PEOPLE . " left join ".COMPANY." on ".PEOPLE.".company_id = ".COMPANY.".cid  left join ".COUNTRY." on ".PEOPLE.".login_country = ".COUNTRY.".country_id   left join ".STATE." on ".PEOPLE.".login_state = ".STATE.".state_id    left join ".CITY." on ".PEOPLE.".login_city = ".CITY.".city_id  where ".PEOPLE.".user_type = 'D' $company_where $staus_where $name_where $user_created_where order by created_date DESC";

	 		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();


		return $result;
		
	}
	
	public function count_searchdriver_list($keyword = "", $status = "",$company = "",$shift_status = "",$peoples = "",$driver_list = "")
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = ($company) ? $company : $_SESSION['company_id'];
		$country_id = $_SESSION['country_id'];
		$state_id = $_SESSION['state_id'];
		$city_id = $_SESSION['city_id'];

		if($usertype =='M') {
			$user_created_where = " AND login_country = $country_id AND login_state = $state_id AND login_city = $city_id AND company_id = $company_id ";
		} else if($usertype =='C') {
			$user_created_where = " AND ".PEOPLE.".company_id = $company_id ";
		} else {
			$user_created_where = "";
		}

		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where = $query_where = $driver_list_in = $company_where = "";
		if($company != "") {
			$company_where = " AND cid = '$company'";
		}
		if($status != "" && $status == "U") {
			if($company != "") {
				$company_where = " and cid = '$company'";
			}
			$driver_list_in = " and ".PEOPLE.".id NOT IN ($driver_list)";
		} else if($status != "" && $status != "U") {
			$staus_where = " AND ".PEOPLE.".status = '$status'";
		}
		$trash_staus = "";
		if(isset($status) && $status !="T")
		{
			$trash_staus = " AND ".PEOPLE.".status != 'T'";
		} 
		
		//$company_where= ($company) ? " AND cid = '$company'" : "";
		//$staus_where= ($status) ? " AND ".PEOPLE.".status = '$status'" : "";
		$name_where = "";
		if($keyword) {
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
		}

			$driver_location_query  = "";
			$driver_location_where = "";
			$driver_status_where = "";

		if($shift_status!='')
		{

			if($shift_status == 'IN')
			{
			$datetime = date('Y-m-d H:i:s');
				$driver_location_query  = " , (TIME_TO_SEC( TIMEDIFF(  '" . $datetime . "', driver.update_date ) )) AS updatetime_difference";
				$driver_location_where = " having updatetime_difference <=  '" . LOCATIONUPDATESECONDS . "'  ";
				$driver_status_where = " AND ".DRIVER.".shift_status = '$shift_status'";
			}
			elseif($shift_status == 'OUT')
			{

				$datetime = date('Y-m-d H:i:s');
				$driver_location_query  = " , (TIME_TO_SEC( TIMEDIFF(  '" . $datetime . "', driver.update_date ) )) AS updatetime_difference";
				$driver_location_where = " having updatetime_difference >=  '" . LOCATIONUPDATESECONDS . "'  ";
			}
			
			
			
		}


		$query = " select account_balance,user_createdby, name, username, email, address, availability_status, company_name,".PEOPLE.".id, driver_license_id, shift_status, phone, country_name, city_name, state_name, userid, profile_picture,".PEOPLE.".id,".PEOPLE.".status as driver_status,".DRIVER.".update_date $driver_location_query from " . PEOPLE . " left join ".COMPANY." on ".PEOPLE.".company_id = ".COMPANY.".cid  left join ".COUNTRY." on ".PEOPLE.".login_country = ".COUNTRY.".country_id   left join ".STATE." on ".PEOPLE.".login_state = ".STATE.".state_id    left join ".CITY." on ".PEOPLE.".login_city = ".CITY.".city_id left join ".DRIVER." on ".PEOPLE.".id = ".DRIVER.".driver_id where ".PEOPLE.".user_type = 'D' $company_where $staus_where $trash_staus $name_where $user_created_where $driver_list_in $driver_status_where $driver_location_where order by created_date DESC";

		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		$details = array();
		foreach($result as $key => $res)
		{
			//$details[$key]['created_by'] = $this->userNamebyId($res['user_createdby']);
			$details[$key]['created_by'] = (array_key_exists($res['user_createdby'],$peoples)) ? $peoples[$res['user_createdby']] : '';
			$details[$key]['name'] = $res['name'];
			$details[$key]['username'] = $res['username'];
			$details[$key]['email'] = $res['email'];
			$details[$key]['address'] = $res['address'];
			$details[$key]['company_name'] = $res['company_name'];	
			$details[$key]['availability_status'] = $res['availability_status'];
			$details[$key]['status'] = $res['driver_status'];
			$details[$key]['id'] = $res['id'];
			$details[$key]['driver_license_id'] = $res['driver_license_id'];
			$details[$key]['account_balance'] = CURRENCY.round($res['account_balance'],2);
			$details[$key]['shift_status'] = $res['shift_status'];
			$details[$key]['phone'] = $res['phone'];
			$details[$key]['country_name'] = $res['country_name'];
			$details[$key]['city_name'] = $res['city_name'];
			$details[$key]['state_name'] = $res['state_name'];
			$details[$key]['cid'] = $res['userid'];
			$details[$key]['photo'] = $res['profile_picture'];
			$details[$key]['driver_status'] = $res['driver_status'];
			$details[$key]['update_date'] = $res['update_date'];
		}
		return $details;
	}

	public function get_all_driver_searchlist($keyword = "", $status = "",$company ="",$shift_status = "",$offset ="",$val ="", $peoples = "", $driver_list = "")
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = ($company) ? $company : $_SESSION['company_id'];
		$country_id = $_SESSION['country_id'];
		$state_id = $_SESSION['state_id'];
		$city_id = $_SESSION['city_id'];

		if($usertype =='M')
		{
			$user_created_where = " AND login_country = $country_id AND login_state = $state_id AND login_city = $city_id AND company_id = $company_id ";
		}
		else if($usertype =='C')
		{
			$user_created_where = " AND company_id = $company_id ";
		}
		else
		{
			$user_created_where = "";
		}
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where = $query_where = $driver_list_in = $company_where = "";
		if($company != "") {
			$company_where = " AND cid = '$company'";
		}
		if($status != "" && $status == "U") {
			if($company != "") {
				$company_where = " and cid = '$company'";
			}
			$driver_list_in = " and ".PEOPLE.".id NOT IN ($driver_list)";
		} else if($status != "" && $status != "U") {
			$staus_where = " AND ".PEOPLE.".status = '$status'";
		}
		
		$trash_staus = "";
		if(isset($status) && $status !="T")
		{
			$trash_staus = " AND ".PEOPLE.".status != 'T'";
		} 
		//$company_where= ($company) ? " AND cid = '$company'" : "";
		//$staus_where= ($status) ? " AND ".PEOPLE.".status = '$status'" : "";
		$name_where = "";
		if($keyword) {
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or phone LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
		}

			$driver_location_query  = "";
			$driver_location_where = "";
			$driver_status_where = "";

		if($shift_status!='')
		{

			if($shift_status == 'IN')
			{
				$datetime = date('Y-m-d H:i:s');
				$driver_location_query  = " , (TIME_TO_SEC( TIMEDIFF(  '" . $datetime . "', driver.update_date ) )) AS updatetime_difference";
				$driver_location_where = " having updatetime_difference <=  '" . LOCATIONUPDATESECONDS . "'  ";
				$driver_status_where = " AND ".DRIVER.".shift_status = '$shift_status'";
			}
			elseif($shift_status == 'OUT')
			{

				$datetime = date('Y-m-d H:i:s');
				$driver_location_query  = " , (TIME_TO_SEC( TIMEDIFF(  '" . $datetime . "', driver.update_date ) )) AS updatetime_difference";
				$driver_location_where = " having updatetime_difference >=  '" . LOCATIONUPDATESECONDS . "'  ";
			}

			
		}


		$query = " select account_balance,user_createdby, name, username, email, address, availability_status, company_name,".PEOPLE.".id,driver_license_id,shift_status,phone,country_name,city_name,state_name,userid,profile_picture,".PEOPLE.".id,".PEOPLE.".status as driver_status,".DRIVER.".update_date $driver_location_query  from " . PEOPLE . " left join ".COMPANY." on ".PEOPLE.".company_id = ".COMPANY.".cid  left join ".COUNTRY." on ".PEOPLE.".login_country = ".COUNTRY.".country_id   left join ".STATE." on ".PEOPLE.".login_state = ".STATE.".state_id left join ".CITY." on ".PEOPLE.".login_city = ".CITY.".city_id left join ".DRIVER." on ".PEOPLE.".id = ".DRIVER.".driver_id where ".PEOPLE.".user_type = 'D' $company_where $staus_where $trash_staus $name_where $user_created_where $driver_list_in $driver_status_where $driver_location_where order by created_date DESC limit $val offset  $offset";

		$result = Db::query(Database::SELECT, $query)->execute()->as_array();

		$details = array();
		foreach($result as $key => $res)
		{
			//$details[$key]['created_by'] = $this->userNamebyId($res['user_createdby']);
			$details[$key]['created_by'] = (array_key_exists($res['user_createdby'],$peoples)) ? $peoples[$res['user_createdby']] : '';
			$details[$key]['name'] = $res['name'];
			$details[$key]['username'] = $res['username'];
			$details[$key]['email'] = $res['email'];
			$details[$key]['address'] = $res['address'];
			$details[$key]['company_name'] = $res['company_name'];	
			$details[$key]['availability_status'] = $res['availability_status'];
			$details[$key]['status'] = $res['driver_status'];
			$details[$key]['id'] = $res['id'];
			$details[$key]['driver_license_id'] = $res['driver_license_id'];
			$details[$key]['shift_status'] = $res['shift_status'];
			$details[$key]['phone'] = $res['phone'];
			$details[$key]['country_name'] = $res['country_name'];
			$details[$key]['city_name'] = $res['city_name'];
			$details[$key]['state_name'] = $res['state_name'];
			$details[$key]['cid'] = $res['userid'];
			$details[$key]['photo'] = $res['profile_picture'];
			$details[$key]['driver_status'] = $res['driver_status'];
			$details[$key]['update_date'] = $res['update_date'];
			$details[$key]['account_balance'] = $res['account_balance'];
		}
		return $details;
	}


  	public function get_all_undriver_searchlist($keyword = "", $status = "",$company ="",$offset ="",$val ="")
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
			$user_created_where = " AND login_country = $country_id AND login_state = $state_id AND login_city = $city_id AND company_id = $company_id ";
		}
		else if($usertype =='C')
		{
			$user_created_where = " AND company_id = $company_id ";
		}
		else
		{
			$user_created_where = "";		
		}
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		$company_where= ($company) ? " AND cid = '$company'" : "";	
		$staus_where= ($status) ? " AND availability_status = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
       		 }

			
			$query = " select *, ".PEOPLE.".status as driver_status from " . PEOPLE . " left join ".COMPANY." on ".PEOPLE.".company_id = ".COMPANY.".cid  left join ".COUNTRY." on ".PEOPLE.".login_country = ".COUNTRY.".country_id   left join ".STATE." on ".PEOPLE.".login_state = ".STATE.".state_id    left join ".CITY." on ".PEOPLE.".login_city = ".CITY.".city_id  where ".PEOPLE.".user_type = 'D' $company_where $staus_where $name_where $user_created_where order by created_date DESC limit $val offset  $offset";

	 		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
						 
			$details = array();
			foreach($result as $key => $res)		
			{
				$details[$key]['created_by'] = $this->userNamebyId($res['user_createdby']);
				$details[$key]['name'] = $res['name'];
				$details[$key]['username'] = $res['username'];
				$details[$key]['email'] = $res['email'];
				$details[$key]['address'] = $res['address'];
				$details[$key]['company_name'] = $res['company_name'];	
				$details[$key]['availability_status'] = $res['availability_status'];
				$details[$key]['status'] = $res['status'];
				$details[$key]['id'] = $res['id'];
				$details[$key]['driver_license_id'] = $res['driver_license_id'];
				$details[$key]['phone'] = $res['phone'];
				$details[$key]['country_name'] = $res['country_name'];
				$details[$key]['city_name'] = $res['city_name'];
				$details[$key]['state_name'] = $res['state_name'];
				$details[$key]['cid'] = $res['userid'];
				$details[$key]['driver_status'] = $res['driver_status'];
				
			}
						
				return $details;

	}


	public function searchtaxi_list($keyword = "", $status = "",$company = "")
	{
	
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
			$createdby_where = " AND taxi_country=$country_id AND taxi_state=$state_id AND taxi_city=$city_id AND taxi_company=$company_id ";
		}
		else if($usertype =='C')
		{
			$createdby_where = " AND taxi_company = $company_id ";
		}
		else
		{
			$createdby_where = "";		
		}
		
		
	
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		$company_where = ($company) ? " AND cid = '$company'" : "";
		$staus_where = ($status) ? " AND taxi_status = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (taxi_no LIKE  '%$keyword%' ";
			$name_where  .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or taxi_type LIKE '%$keyword%' escape '!' ) ";
       		 }


			$query = " select * from " . TAXI . " left join ".COUNTRY."  on  ".TAXI.".taxi_country =".COUNTRY.".country_id left join ".STATE." on ".TAXI.".taxi_state =".STATE.".state_id left join ".CITY." on ".TAXI.".taxi_city =".CITY.".city_id   left join ".COMPANY." on ".TAXI.".taxi_company = ".COMPANY.".cid  left join ".MOTORCOMPANY." on ".TAXI.".taxi_type =".MOTORCOMPANY.".motor_id left join ".MOTORMODEL." on ".TAXI.".taxi_model = ".MOTORMODEL.".model_id where 1=1 $company_where $staus_where $name_where $createdby_where order by taxi_id DESC";

	 		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();

			 return $result;
		
	}
	
	public function count_searchtaxi_list($keyword = "", $status = "",$company = "",$taxi_list = "")
	{
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = ($company) ? $company : $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
			$createdby_where = " AND taxi_country=$country_id AND taxi_state=$state_id AND taxi_city=$city_id AND taxi_company=$company_id ";
		}
		else if($usertype =='C')
		{
			$createdby_where = " AND taxi_company = $company_id ";
		}
		else
		{
			$createdby_where = "";
		}

		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where = $query_where = $taxi_list_in = $company_where = "";
		if($company != "") {
			$company_where = " AND cid = '$company'";
		}
		if($status != "" && $status == "U") {
			$staus_where = " AND taxi_status = 'A' AND taxi_availability = 'A'";
			if($company != "") {
				$company_where = " and taxi_company='$company_id'";
			}
			$taxi_list_in = " and ".TAXI.".taxi_id NOT IN ($taxi_list)";
		} else if($status != "" && $status != "U") {
			$staus_where = " AND taxi_status = '$status'";
		}
		//$company_where= ($company) ? " AND cid = '$company'" : "";
		//$staus_where= ($status) ? " AND taxi_status = '$status'" : "";
		$name_where="";	

		if($keyword) {
			$name_where  = " AND (taxi_no LIKE  '%$keyword%' ";
			$name_where  .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or taxi_type LIKE '%$keyword%' escape '!' ) ";
		}

		$query = " select taxi_no,company_name,motor_name,model_name,taxi_status,mapping_enddate from " . TAXI . " left join ".COUNTRY."  on  ".TAXI.".taxi_country =".COUNTRY.".country_id left join ".STATE." on ".TAXI.".taxi_state =".STATE.".state_id left join ".CITY." on ".TAXI.".taxi_city =".CITY.".city_id   left join ".COMPANY." on ".TAXI.".taxi_company = ".COMPANY.".cid  left join ".MOTORCOMPANY." on ".TAXI.".taxi_type =".MOTORCOMPANY.".motor_id left join ".MOTORMODEL." on ".TAXI.".taxi_model = ".MOTORMODEL.".model_id left JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid where 1=1 $company_where $staus_where $name_where $query_where $createdby_where $taxi_list_in order by taxi_id DESC";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}

  	public function get_all_taxi_searchlist($keyword = "", $status = "",$company = "",$offset ="",$val ="",$taxi_list = "")
	{
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = ($company) ? $company : $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
			$createdby_where = " AND taxi_country=$country_id AND taxi_state=$state_id AND taxi_city=$city_id AND taxi_company=$company_id ";
		}
		else if($usertype =='C')
		{
			$createdby_where = " AND taxi_company = $company_id ";
		}
		else
		{
			$createdby_where = "";
		}

		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where = $query_where = $taxi_list_in = $company_where = "";
		if($company != "") {
			$company_where = " AND cid = '$company'";
		}
		if($status != "" && $status == "U") {
			$staus_where = " AND taxi_status = 'A' AND taxi_availability = 'A'";
			if($company != "") {
				$company_where = " and taxi_company='$company_id'";
			}
			$taxi_list_in = " and ".TAXI.".taxi_id NOT IN ($taxi_list)";
		} else if($status != "" && $status != "U") {
			$staus_where = " AND taxi_status = '$status'";
		}
		//$company_where= ($company) ? " AND cid = '$company'" : "";
		//$staus_where= ($status) ? " AND taxi_status = '$status'" : "";
		$name_where = "";
		if($keyword) {
			$name_where  = " AND (taxi_no LIKE  '%$keyword%' ";
			$name_where  .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or taxi_type LIKE '%$keyword%' escape '!' ) ";
		}

		$query = " select taxi_id,taxi_availability,taxi_status,taxi_no,company_name,motor_name,model_name,taxi_capacity,taxi_fare_km,userid, name,mapping_startdate,mapping_enddate from " . TAXI . " left join ".COUNTRY."  on  ".TAXI.".taxi_country =".COUNTRY.".country_id left join ".STATE." on ".TAXI.".taxi_state =".STATE.".state_id left join ".CITY." on ".TAXI.".taxi_city =".CITY.".city_id   left join ".COMPANY." on ".TAXI.".taxi_company = ".COMPANY.".cid  left join ".MOTORCOMPANY." on ".TAXI.".taxi_type =".MOTORCOMPANY.".motor_id left join ".MOTORMODEL." on ".TAXI.".taxi_model = ".MOTORMODEL.".model_id left join ".PEOPLE." on ".TAXI.".taxi_createdby = ".PEOPLE.".id left JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid where 1=1 $company_where $staus_where $name_where $createdby_where $taxi_list_in order by taxi_id DESC limit $val offset  $offset";

		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		$details = array();
		foreach($result as $key => $res)
		{
			//$details[$key]['created_by'] = $this->userNamebyId($res['taxi_createdby']);
			$details[$key]['created_by'] = $res['name'];
			$details[$key]['taxi_id'] = $res['taxi_id'];
			$details[$key]['taxi_availability'] = $res['taxi_availability'];
			$details[$key]['taxi_status'] = $res['taxi_status'];
			$details[$key]['taxi_no'] = $res['taxi_no'];
			$details[$key]['company_name'] = $res['company_name'];	
			$details[$key]['motor_name'] = $res['motor_name'];
			$details[$key]['model_name'] = $res['model_name'];
			$details[$key]['taxi_capacity'] = $res['taxi_capacity'];
			$details[$key]['taxi_fare_km'] = $res['taxi_fare_km'];
			$details[$key]['cid'] = $res['userid'];
		}
		//echo '<pre>';print_r($details);exit;
		return $details;
	}

	
  	public function get_all_untaxi_searchlist($keyword = "", $status = "",$company = "",$offset ="",$val ="")
	{
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
			$createdby_where = " AND taxi_country=$country_id AND taxi_state=$state_id AND taxi_city=$city_id AND taxi_company=$company_id ";
		}
		else if($usertype =='C')
		{
			$createdby_where = " AND taxi_company = $company_id ";
		}
		else
		{
			$createdby_where = "";		
		}
		
		
	
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		$company_where= ($company) ? " AND cid = '$company'" : "";
		$staus_where= ($status) ? " AND taxi_availability = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (taxi_no LIKE  '%$keyword%' ";
			$name_where  .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or taxi_type LIKE '%$keyword%' escape '!' ) ";
       		 }


			$query = " select * from " . TAXI . " left join ".COUNTRY."  on  ".TAXI.".taxi_country =".COUNTRY.".country_id left join ".STATE." on ".TAXI.".taxi_state =".STATE.".state_id left join ".CITY." on ".TAXI.".taxi_city =".CITY.".city_id   left join ".COMPANY." on ".TAXI.".taxi_company = ".COMPANY.".cid  left join ".MOTORCOMPANY." on ".TAXI.".taxi_type =".MOTORCOMPANY.".motor_id left join ".MOTORMODEL." on ".TAXI.".taxi_model = ".MOTORMODEL.".model_id where 1=1 $company_where $staus_where $name_where $createdby_where order by taxi_id DESC limit $val offset  $offset";


	 		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
		  		$details = array();
				foreach($result as $key => $res)		
				{
					$details[$key]['created_by'] = $this->userNamebyId($res['taxi_createdby']);
					$details[$key]['taxi_id'] = $res['taxi_id'];
					$details[$key]['taxi_availability'] = $res['taxi_availability'];
					$details[$key]['taxi_status'] = $res['taxi_status'];
					$details[$key]['taxi_no'] = $res['taxi_no'];
					$details[$key]['company_name'] = $res['company_name'];	
					$details[$key]['motor_name'] = $res['motor_name'];
					$details[$key]['model_name'] = $res['model_name'];
					$details[$key]['taxi_capacity'] = $res['taxi_capacity'];
					$details[$key]['taxi_fare_km'] = $res['taxi_fare_km'];
					$details[$key]['cid'] = $res['userid'];
	
				}
			 return $details;
	}


	public function searchmodel_list($keyword = "", $status = "")
	{
	
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND model_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (model_name LIKE  '%$keyword%'"; 
		$name_where .= " or motor_name LIKE  '%$keyword%' )";

		}


			$query = " select * from " . MOTORMODEL . " left join " .MOTORCOMPANY. " on ". MOTORMODEL.".motor_mid =".MOTORCOMPANY.".motor_id  where 1=1 $staus_where $name_where order by model_name ASC";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;
		
	}
	
	public function count_searchmodel_list($keyword = "", $status = "")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND model_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (model_name LIKE  '%$keyword%'"; 
		$name_where .= " or motor_name LIKE  '%$keyword%' )";

		}


		$query = " select count(model_id) as count from " . MOTORMODEL . " left join " .MOTORCOMPANY. " on ". MOTORMODEL.".motor_mid =".MOTORCOMPANY.".motor_id  where 1=1 $staus_where $name_where order by model_name ASC";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return !empty($results) ? $results[0]['count'] : 0;
		//return count($results);
	}
	
  	public function get_all_model_searchlist($keyword = "", $status = "",$offset ="",$val ="")
	{
	
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND model_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (model_name LIKE  '%$keyword%'"; 
		$name_where .= " or motor_name LIKE  '%$keyword%' )";

		}


		$query = " select model_id,model_status,model_name,motor_name from " . MOTORMODEL . " left join " .MOTORCOMPANY. " on ". MOTORMODEL.".motor_mid =".MOTORCOMPANY.".motor_id  where 1=1 $staus_where $name_where order by model_name ASC  limit $val offset  $offset";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;

	}

	
	public function trash_state_request($activeids)
	{
		$result = DB::select()->from(SITEINFO)->where('site_state', 'IN', $activeids)->order_by('id','ASC')
				->execute()
				->as_array();
				
		$state_count = count($result);
		if($state_count == 0)
		{
			$result = DB::update(STATE)->set(array('state_status' => 'T'))->where('state_id', 'IN', $activeids)
			->execute();
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function manager_list()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		$company_id = $_SESSION['company_id'];
	   	
	   	if($usertype =='M')
	   	{
		$rs = DB::select()->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->where('user_type','=','M')
				->where('user_createdby','=',$user_createdby)
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else if($usertype =='C')
	   	{
		$rs = DB::select()->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->where('user_type','=','M')
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else
		{
		$rs = DB::select()->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->where('user_type','=','M')
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
		
		}	
	}

	public function count_admin_list()
	{
		$rs = DB::select('id')->from(PEOPLE)
				->where_open()
				->where('user_type','=','S')
				->or_where('user_type','=','DA')
				->where_close()
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
	}
	
	
	public function count_manager_list()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		$company_id = $_SESSION['company_id'];	
	   	if($usertype =='M')
	   	{
			$rs = DB::select()->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->where('user_type','=','M')
				->where('status','!=','T')
				->where('user_createdby','=',$user_createdby)
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else if($usertype =='C')
	   	{
			$rs = DB::select()->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->where('user_type','=','M')
				->where('status','!=','T')
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else
		{
			$rs = DB::select(array(DB::expr("COUNT('id')"), 'count'))->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')						
				->where('user_type','=','M')
				->where('status','!=','T')
				->order_by('created_date','desc')
			->execute()
			->as_array();
				
				return !empty($rs) ? $rs[0]['count'] : 0;
		
		}	
	}
	
	public function all_manager_list($offset, $val)
	{
		
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = $_SESSION['company_id'];	
	   	
	   	if($usertype =='M')
	   	{
			$result = DB::select()->from(PEOPLE)
					->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
					->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
					->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
					->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
					->where('user_type','=','M')
					->where('status','!=','T')
					->where('user_createdby','=',$user_createdby)
					->where('company_id','=',$company_id)
					->order_by('created_date','desc')->limit($val)->offset($offset)
					->execute()

					->as_array();
					
			return $result;
		}
		else if($usertype =='C')
		{
			$result = DB::select()->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
				->where('user_type','=','M')
				->where('status','!=','T')
				->where('company_id','=',$company_id)
				->order_by('created_date','desc')->limit($val)->offset($offset)
				->execute()

				->as_array();
				
			return $result;
		}
		else
		{
		
		
			$result = DB::select('id','status','name','company_name','userid','email','address','country_name','state_name','city_name')->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
				->where(PEOPLE.'.user_type', '=', 'M')
				->where('status','!=','T')
				->order_by('created_date','desc')->limit($val)->offset($offset)
			->execute()
			->as_array();
		//echo '<pre>';print_r($result);exit;
				
				return $result;
		
		}
		
	}


	public function all_admin_list($offset, $val)
	{
			$result = DB::select(PEOPLE.'.id',PEOPLE.'.status',PEOPLE.'.name',PEOPLE.'.email',PEOPLE.'.address',COUNTRY.'.country_name',STATE.'.state_name',CITY.'.city_name')->from(PEOPLE)
				->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
				->where_open()
				->where('user_type','=','S')
				->or_where('user_type','=','DA')
				->where_close()
				->where('status','!=','T')
				->order_by('created_date','desc')->limit($val)->offset($offset)
				->execute()

				->as_array();
				
		return $result;
		
	}

  	
	public function active_manager_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       
             //$arr_chk = " userid in ('" . implode("','",$activeids) . "') ";	
             
             $result = DB::update(PEOPLE)->set(array('status' => 'A'))->where('id', 'IN', $activeids)
			->execute();
			
		        	  
			 return count($result);
	}

	public function active_admin_request($activeids)
	{
             $result = DB::update(PEOPLE)->set(array('status' => 'A'))->where('id', 'IN', $activeids)
			->execute();
			
		        	  
			 return count($result);
	}
	
	public function block_manager_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       

             //$arr_chk = " userid in ('" . implode("','",$activeids) . "') ";	
             
                    $result = DB::update(PEOPLE)->set(array('status' => 'D'))->where('id', 'IN', $activeids)
			->execute();
		        	  
			 return count($result);
	}

	public function block_admin_request($activeids)
	{
		
                    $result = DB::update(PEOPLE)->set(array('status' => 'D'))->where('id', 'IN', $activeids)
			->execute();
		        	  
			 return count($result);
	}
   
 	public function assigntaxi_list()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = $_SESSION['company_id'];
	   	
	   	if($usertype =='M')
	   	{
		$rs = DB::select()->from(TAXIMAPPING)
				->join(COMPANY, 'LEFT')->on(TAXIMAPPING.'.mapping_companyid', '=', COMPANY.'.cid')
				->join(TAXI, 'LEFT')->on(TAXIMAPPING.'.mapping_taxiid', '=', TAXI.'.taxi_id')
				->join(COUNTRY, 'LEFT')->on(TAXIMAPPING.'.mapping_countryid', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXIMAPPING.'.mapping_stateid', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXIMAPPING.'.mapping_cityid', '=', CITY.'.city_id')
				->join(PEOPLE, 'LEFT')->on(TAXIMAPPING.'.mapping_driverid', '=', PEOPLE.'.id')
				->where(COMPANY.'.cid','=',$company_id)
				->order_by('mapping_startdate','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else if($usertype =='C')
	   	{
		$rs = DB::select()->from(TAXIMAPPING)
				->join(COMPANY, 'LEFT')->on(TAXIMAPPING.'.mapping_companyid', '=', COMPANY.'.cid')
				->join(TAXI, 'LEFT')->on(TAXIMAPPING.'.mapping_taxiid', '=', TAXI.'.taxi_id')
				->join(COUNTRY, 'LEFT')->on(TAXIMAPPING.'.mapping_countryid', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXIMAPPING.'.mapping_stateid', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXIMAPPING.'.mapping_cityid', '=', CITY.'.city_id')
				->join(PEOPLE, 'LEFT')->on(TAXIMAPPING.'.mapping_driverid', '=', PEOPLE.'.id')				
				->where(COMPANY.'.cid','=',$company_id)
				->order_by('mapping_startdate','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else
		{
		$rs = DB::select()->from(TAXIMAPPING)
				->join(COMPANY, 'LEFT')->on(TAXIMAPPING.'.mapping_companyid', '=', COMPANY.'.cid')
				->join(TAXI, 'LEFT')->on(TAXIMAPPING.'.mapping_taxiid', '=', TAXI.'.taxi_id')
				->join(COUNTRY, 'LEFT')->on(TAXIMAPPING.'.mapping_countryid', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXIMAPPING.'.mapping_stateid', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXIMAPPING.'.mapping_cityid', '=', CITY.'.city_id')
				->join(PEOPLE, 'LEFT')->on(TAXIMAPPING.'.mapping_driverid', '=', PEOPLE.'.id')				
				->order_by('mapping_startdate','desc')
				->execute()
				->as_array();
				
				return $rs;
		
		}	
	}
	
	public function count_assigntaxi_list()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		$company_id = $_SESSION['company_id'];
		$country_id = $_SESSION['country_id'];
		$state_id = $_SESSION['state_id'];
		$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
			$rs = DB::select()->from(TAXIMAPPING)
				->join(COMPANY, 'LEFT')->on(TAXIMAPPING.'.mapping_companyid', '=', COMPANY.'.cid')
				->join(TAXI, 'LEFT')->on(TAXIMAPPING.'.mapping_taxiid', '=', TAXI.'.taxi_id')
				->join(COUNTRY, 'LEFT')->on(TAXIMAPPING.'.mapping_countryid', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXIMAPPING.'.mapping_stateid', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXIMAPPING.'.mapping_cityid', '=', CITY.'.city_id')
				->join(PEOPLE, 'LEFT')->on(TAXIMAPPING.'.mapping_driverid', '=', PEOPLE.'.id')				
				->where(COMPANY.'.cid','=',$company_id)
				->where(TAXIMAPPING.'.mapping_status','!=','T')
				->where(TAXIMAPPING.'.mapping_countryid','=',$country_id)
				->where(TAXIMAPPING.'.mapping_stateid','=',$state_id)
				->where(TAXIMAPPING.'.mapping_cityid','=',$city_id)
				->order_by('mapping_startdate','desc')
				->execute()
				->as_array();
				return $rs;
		}
		else if($usertype =='C')
	   	{
		$rs = DB::select()->from(TAXIMAPPING)
				->join(COMPANY, 'LEFT')->on(TAXIMAPPING.'.mapping_companyid', '=', COMPANY.'.cid')
				->join(TAXI, 'LEFT')->on(TAXIMAPPING.'.mapping_taxiid', '=', TAXI.'.taxi_id')
				->join(COUNTRY, 'LEFT')->on(TAXIMAPPING.'.mapping_countryid', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXIMAPPING.'.mapping_stateid', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXIMAPPING.'.mapping_cityid', '=', CITY.'.city_id')
				->join(PEOPLE, 'LEFT')->on(TAXIMAPPING.'.mapping_driverid', '=', PEOPLE.'.id')				
				->where(COMPANY.'.cid','=',$company_id)
				->where(TAXIMAPPING.'.mapping_status','!=','T')
				->order_by('mapping_startdate','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else
		{
		$rs = DB::select()->from(TAXIMAPPING)
				->join(COMPANY, 'LEFT')->on(TAXIMAPPING.'.mapping_companyid', '=', COMPANY.'.cid')
				->join(TAXI, 'LEFT')->on(TAXIMAPPING.'.mapping_taxiid', '=', TAXI.'.taxi_id')
				->join(COUNTRY, 'LEFT')->on(TAXIMAPPING.'.mapping_countryid', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXIMAPPING.'.mapping_stateid', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXIMAPPING.'.mapping_cityid', '=', CITY.'.city_id')
				->join(PEOPLE, 'LEFT')->on(TAXIMAPPING.'.mapping_driverid', '=', PEOPLE.'.id')
				->where(TAXIMAPPING.'.mapping_status','!=','T')				
				->order_by('mapping_startdate','desc')
				->execute()
				->as_array();
				
				return $rs;
		
		}	
	}
	
	public function all_assigntaxi_list($offset, $val,$peoples)
	{
		
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = $_SESSION['company_id'];	
	   	$country_id = $_SESSION['country_id'];
		$state_id = $_SESSION['state_id'];
		$city_id = $_SESSION['city_id'];
		
	   	if($usertype =='M')
	   	{
		$result = DB::select('user_createdby','mapping_id','mapping_status','name','company_name','taxi_no','country_name','state_name','city_name','mapping_startdate','mapping_enddate','id','userid','taxi_id','mapping_companyid')->from(TAXIMAPPING)
				->join(COMPANY, 'LEFT')->on(TAXIMAPPING.'.mapping_companyid', '=', COMPANY.'.cid')
				->join(TAXI, 'LEFT')->on(TAXIMAPPING.'.mapping_taxiid', '=', TAXI.'.taxi_id')
				->join(COUNTRY, 'LEFT')->on(TAXIMAPPING.'.mapping_countryid', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXIMAPPING.'.mapping_stateid', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXIMAPPING.'.mapping_cityid', '=', CITY.'.city_id')
				->join(PEOPLE, 'LEFT')->on(TAXIMAPPING.'.mapping_driverid', '=', PEOPLE.'.id')				
				->where(COMPANY.'.cid','=',$company_id)
				->where(TAXIMAPPING.'.mapping_status','!=','T')
				->where(TAXIMAPPING.'.mapping_countryid','=',$country_id)
				->where(TAXIMAPPING.'.mapping_stateid','=',$state_id)
				->where(TAXIMAPPING.'.mapping_cityid','=',$city_id)
				->order_by('mapping_startdate','desc')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
				
				$details = array();
				foreach($result as $key => $res)
				{
					$details[$key]['created_by'] = (array_key_exists($res['user_createdby'],$peoples)) ? $peoples[$res['user_createdby']] : '';
					//$details[$key]['created_by'] = $this->userNamebyId($res['mapping_createdby']);
					$details[$key]['mapping_id'] = $res['mapping_id'];
					$details[$key]['mapping_status'] = $res['mapping_status'];
					$details[$key]['name'] = $res['name'];
					$details[$key]['company_name'] = $res['company_name'];	
					$details[$key]['taxi_no'] = $res['taxi_no'];	
					$details[$key]['country_name'] = $res['country_name'];
					$details[$key]['state_name'] = $res['state_name'];	
					$details[$key]['city_name'] = $res['city_name'];
					$details[$key]['mapping_startdate'] = $res['mapping_startdate'];
					$details[$key]['mapping_enddate'] = $res['mapping_enddate'];
					$details[$key]['id'] = $res['id'];
					$details[$key]['cid'] = $res['userid'];
					$details[$key]['taxi_id'] = $res['taxi_id'];
					$details[$key]['mapping_companyid'] = $res['mapping_companyid'];
	
				}
				
			 return $details;

		}
		else if($usertype =='C')
		{
		$result = DB::select()->from(TAXIMAPPING)
				->join(COMPANY, 'LEFT')->on(TAXIMAPPING.'.mapping_companyid', '=', COMPANY.'.cid')
				->join(TAXI, 'LEFT')->on(TAXIMAPPING.'.mapping_taxiid', '=', TAXI.'.taxi_id')
				->join(COUNTRY, 'LEFT')->on(TAXIMAPPING.'.mapping_countryid', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXIMAPPING.'.mapping_stateid', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXIMAPPING.'.mapping_cityid', '=', CITY.'.city_id')
				->join(PEOPLE, 'LEFT')->on(TAXIMAPPING.'.mapping_driverid', '=', PEOPLE.'.id')				
				->where(COMPANY.'.cid','=',$company_id)
				->where(TAXIMAPPING.'.mapping_status','!=','T')
				->order_by('mapping_startdate','desc')
				->limit($val)->offset($offset)
				->execute()
				->as_array();

				$details = array();
				foreach($result as $key => $res)		
				{
					$details[$key]['created_by'] = (array_key_exists($res['user_createdby'],$peoples)) ? $peoples[$res['user_createdby']] : '';
					//$details[$key]['created_by'] = $this->userNamebyId($res['mapping_createdby']);
					$details[$key]['mapping_id'] = $res['mapping_id'];
					$details[$key]['mapping_status'] = $res['mapping_status'];
					$details[$key]['name'] = $res['name'];
					$details[$key]['company_name'] = $res['company_name'];	
					$details[$key]['taxi_no'] = $res['taxi_no'];	
					$details[$key]['country_name'] = $res['country_name'];
					$details[$key]['state_name'] = $res['state_name'];	
					$details[$key]['city_name'] = $res['city_name'];
					$details[$key]['mapping_startdate'] = $res['mapping_startdate'];
					$details[$key]['mapping_enddate'] = $res['mapping_enddate'];
					$details[$key]['id'] = $res['id'];
					$details[$key]['cid'] = $res['userid'];
					$details[$key]['taxi_id'] = $res['taxi_id'];
					$details[$key]['mapping_companyid'] = $res['mapping_companyid'];
	
				}
				
			 return $details;
			 	
		}
		else
		{
		$result = DB::select()->from(TAXIMAPPING)
				->join(COMPANY, 'LEFT')->on(TAXIMAPPING.'.mapping_companyid', '=', COMPANY.'.cid')
				->join(TAXI, 'LEFT')->on(TAXIMAPPING.'.mapping_taxiid', '=', TAXI.'.taxi_id')
				->join(COUNTRY, 'LEFT')->on(TAXIMAPPING.'.mapping_countryid', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXIMAPPING.'.mapping_stateid', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXIMAPPING.'.mapping_cityid', '=', CITY.'.city_id')
				->join(PEOPLE, 'LEFT')->on(TAXIMAPPING.'.mapping_driverid', '=', PEOPLE.'.id')	
				->where(TAXIMAPPING.'.mapping_status','!=','T')			
				->order_by('mapping_startdate','desc')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
				

				$details = array();
				foreach($result as $key => $res)		
				{
					$details[$key]['created_by'] = (array_key_exists($res['user_createdby'],$peoples)) ? $peoples[$res['user_createdby']] : '';
					//$details[$key]['created_by'] = $this->userNamebyId($res['mapping_createdby']);
					$details[$key]['mapping_id'] = $res['mapping_id'];
					$details[$key]['mapping_status'] = $res['mapping_status'];
					$details[$key]['name'] = $res['name'];
					$details[$key]['company_name'] = $res['company_name'];	
					$details[$key]['taxi_no'] = $res['taxi_no'];	
					$details[$key]['country_name'] = $res['country_name'];
					$details[$key]['state_name'] = $res['state_name'];	
					$details[$key]['city_name'] = $res['city_name'];
					$details[$key]['mapping_startdate'] = $res['mapping_startdate'];
					$details[$key]['mapping_enddate'] = $res['mapping_enddate'];
					$details[$key]['id'] = $res['id'];
					$details[$key]['cid'] = $res['userid'];
					$details[$key]['taxi_id'] = $res['taxi_id'];
					$details[$key]['mapping_companyid'] = $res['mapping_companyid'];
	
				}
				
			 return $details;
		
		}
		
	}



	public function assigntaxisearch_list($keyword = "", $status = "",$company ="")
	{

		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];	
	   	
	   	if($usertype =='M')
	   	{
			$user_created_where = " AND mapping_companyid = $company_id ";
		}
		else if($usertype =='C')
	   	{
			$user_created_where = " AND mapping_companyid = $company_id ";
		}
		else
		{
			$user_created_where = "";		
		}
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);


		$company_where= ($company) ? " AND cid = '$company'" : "";
		$status_where= ($status) ? " AND mapping_status = '$status'" : "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
       		 }

			
		$query = " select * from " . TAXIMAPPING . " left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".TAXI." on ".TAXIMAPPING.".mapping_taxiid = ".TAXI.".taxi_id  left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id where 1=1 $company_where $user_created_where $status_where $name_where order by mapping_startdate DESC ";
		

	 		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
			 return $result;

   }
   
   	public function count_assigntaxisearch_list($keyword = "", $status = "",$company = "")
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = $_SESSION['company_id'];	
		$cuurentdate = $this->Commonmodel->getcompany_all_currenttimestamp($company_id);
		if($usertype =='M')
		{
			$user_created_where = " AND mapping_companyid = $company_id ";
		}
		else if($usertype =='C')
		{
			$user_created_where = " AND mapping_companyid = $company_id ";
		}
		else
		{
			$user_created_where = "";
		}
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$company_where= ($company) ? " AND cid = '$company'" : "";
		//$status_where= ($status) ? " AND mapping_status = '$status'" : "";
		$status_where = "";
		if($status != "" && $status == "U") {
			$status_where = " and ".TAXIMAPPING.".mapping_enddate <= '$cuurentdate' and mapping_status = 'A'";
			$status_where = " AND mapping_status = '$status'";
		} else if($status != "") {
			$status_where = " AND mapping_status = '$status'";
		}

		$name_where = "";
		if($keyword) {
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
		}

		$query = " select * from " . TAXIMAPPING . " left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".TAXI." on ".TAXIMAPPING.".mapping_taxiid = ".TAXI.".taxi_id  left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id where 1=1 $company_where $user_created_where $status_where $name_where order by mapping_startdate DESC ";
		//echo $query; exit;

		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;

   }
   
	public function get_all_assigntaxi_searchlist($keyword = "", $status = "",$company = "",$offset ="",$val ="",$peoples)
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = $_SESSION['company_id'];	
		$cuurentdate = $this->Commonmodel->getcompany_all_currenttimestamp($company_id);
		if($usertype =='M')
		{
			$user_created_where = " AND mapping_companyid = $company_id ";
		}
		else if($usertype =='C')
		{
			$user_created_where = " AND mapping_companyid = $company_id ";
		}
		else
		{
			$user_created_where = "";
		}
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$company_where= ($company) ? " AND cid = '$company'" : "";
		//$status_where= ($status) ? " AND mapping_status = '$status'" : "";
		$status_where = "";
		if($status != "" && $status == "U") {
			$status_where = " and ".TAXIMAPPING.".mapping_enddate <= '$cuurentdate' and mapping_status = 'A'";
			$status_where = " AND mapping_status = '$status'";
		} else if($status != "") {
			$status_where = " AND mapping_status = '$status'";
		}
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
       		 }

			
		$query = " select * from " . TAXIMAPPING . " left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".TAXI." on ".TAXIMAPPING.".mapping_taxiid = ".TAXI.".taxi_id  left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id where 1=1 $company_where $user_created_where $status_where $name_where order by mapping_startdate DESC limit $val offset  $offset ";

	 		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				$details = array();
				foreach($result as $key => $res)		
				{
					$details[$key]['created_by'] = (array_key_exists($res['user_createdby'],$peoples)) ? $peoples[$res['user_createdby']] : '';
					$details[$key]['mapping_id'] = $res['mapping_id'];
					$details[$key]['mapping_status'] = $res['mapping_status'];
					$details[$key]['name'] = $res['name'];
					$details[$key]['company_name'] = $res['company_name'];	
					$details[$key]['taxi_no'] = $res['taxi_no'];	
					$details[$key]['country_name'] = $res['country_name'];
					$details[$key]['state_name'] = $res['state_name'];
					$details[$key]['city_name'] = $res['city_name'];
					$details[$key]['mapping_startdate'] = $res['mapping_startdate'];
					$details[$key]['mapping_enddate'] = $res['mapping_enddate'];
					$details[$key]['id'] = $res['id'];
					$details[$key]['cid'] = $res['userid'];
					$details[$key]['taxi_id'] = $res['taxi_id'];
					$details[$key]['mapping_companyid'] = $res['mapping_companyid'];
	
				}
			 return $details;

   }
   	
	public function active_assigntaxi_request($activeids)
	{

		    //check whether id is exist in checkbox or single active request
		    //==================================================================
             
             $result = DB::update(TAXIMAPPING)->set(array('mapping_status' => 'A'))->where('mapping_id', 'IN', $activeids)
			->execute();
			
		        	  
			 return count($result);
	}
	
	public function block_assigntaxi_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       
                    $result = DB::update(TAXIMAPPING)->set(array('mapping_status' => 'D'))->where('mapping_id', 'IN', $activeids)
			->execute();
		        	  
			 return count($result);
	}
	
	/**
	 * Unassign Taxi
	 * return int count
	 **/

	public function unassign_taxi_request($activeids,$date)
	{
		//check whether id is exist in checkbox or single active request
		//==================================================================

		$sql = "SELECT ".TAXIMAPPING.".mapping_driverid,".TAXIMAPPING.".mapping_companyid FROM ".TAXIMAPPING." WHERE  ".TAXIMAPPING.".mapping_id IN (".implode(",",$activeids).")";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		if(count($result) > 0) {
			$driver_array['unassign_driver_list'] = $driver_array['trip_driver_list'] = array();
			foreach($result as $a) {
				$company_id = $a['mapping_companyid'];
				$driver_id = $a['mapping_driverid'];
				if($company_id == '' || $company_id == 0)
				{
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
				}
				else
				{
					$model_base_query = "select time_zone from  company where cid='$company_id' "; 
					$model_fetch = Db::query(Database::SELECT, $model_base_query)->execute()->as_array();
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
				$company_condition = "";
				if($company_id != ""){
					$company_condition = " AND ".PASSENGERS_LOG.".company_id = '$company_id'";
				}

				$sql = "SELECT ".PASSENGERS_LOG.".passengers_log_id,".PASSENGERS_LOG.".travel_status FROM  ".PASSENGERS_LOG." WHERE  ".PASSENGERS_LOG.".`driver_id` =  '$driver_id' and ".PASSENGERS_LOG.".pickup_time >='".$start_time."' $company_condition and (travel_status = '9' OR travel_status = '5' OR travel_status='3' OR travel_status='2') and driver_reply = 'A' ORDER BY ".PASSENGERS_LOG.".passengers_log_id DESC LIMIT 0 , 1 ";
				$unAsignedresult = Db::query(Database::SELECT, $sql)->as_object()->execute();
				if(count($unAsignedresult) == 0) {
					DB::update(TAXIMAPPING)->set(array('mapping_enddate' => $date,'mapping_status' => 'U'))->where('mapping_id', 'IN', $activeids)->execute();
					$driver_array['unassign_driver_list'][] = $driver_id;
				} else {
					$driver_array['trip_driver_list'][] = $driver_id;
				}
			}
		} else {
			return array();
		}
		if(count($driver_array['trip_driver_list']) > 0) {
			$user_sql = "SELECT GROUP_CONCAT(name) as driver_name FROM ".PEOPLE." WHERE ".PEOPLE.".id IN (".implode(",",$driver_array['trip_driver_list']).")";
			$result = Db::query(Database::SELECT, $user_sql)->execute()->as_array();
			(isset($result[0]['driver_name'])) ? $this->session->set('driverNames', $result[0]['driver_name']) : "";
		}
		return (isset($driver_array['unassign_driver_list']) && count($driver_array['unassign_driver_list']) > 0) ? $driver_array['unassign_driver_list'] : array();
		/* $result = DB::update(TAXIMAPPING)->set(array('mapping_enddate' => $date,'mapping_status' => 'U'))->where('mapping_id', 'IN', $activeids)->execute();
		return $result; */
	}
	
	public function trash_assigntaxi_request($activeids)
	{		
		//check whether id is exist in checkbox or single active request
		//==================================================================
		$result = DB::update(TAXIMAPPING)->set(array('mapping_status' => 'T'))->where('mapping_id', 'IN', $activeids)
			->execute();
				
				return count($result);
	}
         	
	public function rating_drivers_list()
	{
		/*$rs = DB::select('*',array('SUM("rating_points")', 'total_posts'))->from(RATING)
				->join(PEOPLE, 'INNER')->on(RATING.'.rating_driverid', '=', PEOPLE.'.id')
				->group_by(RATING.'.rating_driverid')
				->execute()
				->as_array();*/
				
		$query = "SELECT log.*,sum(log.rating) as total_posts,count(log.passengers_log_id) as co_nt,p.name as name FROM `".PASSENGERS_LOG."` as log Join `".PEOPLE."` as p ON log.driver_id=p.id group by log.driver_id";
			$rs = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $rs;
	}
	
	public function rating_companies_list()
	{
		$rs = DB::select('*',array('SUM("rating_points")', 'total_posts'))->from(RATING)
				->join(COMPANY, 'INNER')->on(RATING.'.rating_companyid', '=', COMPANY.'.cid')
				->join(PEOPLE, 'INNER')->on(RATING.'.rating_userid', '=', PEOPLE.'.id')
				->group_by(RATING.'.rating_companyid')
				->execute()
				->as_array();
				return $rs;
	}
	
	public function count_rating_companies_list()
	{
		$rs = DB::select('*',array('SUM("rating_points")', 'total_posts'))->from(RATING)
				->join(COMPANY, 'INNER')->on(RATING.'.rating_companyid', '=', COMPANY.'.cid')
				->join(PEOPLE, 'INNER')->on(RATING.'.rating_userid', '=', PEOPLE.'.id')
				->group_by(RATING.'.rating_companyid')
				->execute()
				->as_array();
		return count($rs);
	}

	public function count_rating_drivers_list($keyword = "", $comp_id="")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);
		$usertype = $_SESSION['user_type'];	
		$driver_createdby = $_SESSION['userid'];
		$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
		if($usertype =='C')
		{ 
			$condition="";
			//$joins="LEFT JOIN `state` as s ON p.`login_state` = s.`state_id` LEFT JOIN `city` as c ON p.`login_city` = c.`city_id` ";
			$condition = " p.`company_id`='".$company_id."'";
			$name_where="";	
			if($keyword){
				$condition .= " AND (p.name LIKE  '%$keyword%')";
			}
				$query = "SELECT r.passengers_log_id FROM ".PASSENGERS_LOG." as r JOIN ".PEOPLE." as p ON r.`driver_id`=p.`id`  WHERE $condition AND r.travel_status='1' and r.rating!=0 and p.user_type='D' GROUP BY r.driver_id";

			//echo $query;
			$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();

		 return count($results);
		  
		}
		else if($usertype =='M')
		{
		
			$condition="";
			$joins="LEFT JOIN `state` as s ON p.`login_state` = s.`state_id` LEFT JOIN `city` as c ON p.`login_city` = c.`city_id` ";
			$condition = " p.`login_state` = '".$state_id."' AND p.`login_city` = '".$city_id."' AND s.`state_status` = 'A' and c.`city_status` = 'A'";
			$name_where="";	
			if($keyword){
				$condition .= " AND (p.name LIKE  '%$keyword%')";
			}

			$query = "SELECT r.passengers_log_id FROM ".PASSENGERS_LOG." as r JOIN ".PEOPLE." as p ON r.`driver_id`=p.`id` $joins WHERE $condition AND r.travel_status='1' and r.rating!=0 and p.user_type='D' and p.company_id='$company_id' GROUP BY r.driver_id";
						
			//echo $query;
			$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();

		  return count($results);
		}
		else if($usertype =='D')
		{
			/*$result = DB::select(array('SUM("rating_points")', 'total_posts'))->from(RATING)
				->join(PEOPLE, 'INNER')->on(RATING.'.rating_driverid', '=', PEOPLE.'.id')
				->where(PEOPLE.'.id','=',$driver_createdby)
				->group_by(RATING.'.rating_driverid')
				->execute()
				->as_array();
			return count($result);*/
		
			$query = "SELECT r.passengers_log_id FROM ".PASSENGERS_LOG." as r JOIN ".PEOPLE." as p ON r.`driver_id`=p.`id`  WHERE p.id='$driver_createdby' and r.travel_status='1' and r.rating!=0 group by r.driver_id";
			$results = Db::query(Database::SELECT, $query)->execute()->as_array();
			return count($results);
		}
		else
		{
		$name_where="";	
		if($keyword){
			$name_where  .= " AND (p.name LIKE  '%$keyword%')";
       	}
			if($comp_id){
			$name_where  .= " AND p.`company_id`='".$comp_id."'";

			}
		//$query = " select *,SUM(rating_points) as total_posts from " . RATING . " INNER JOIN " . PEOPLE . " ON " . RATING . " .rating_driverid = " . PEOPLE . " .id where 1=1 $name_where group by rating_driverid order by rating_id ASC";
		$query = "SELECT log.passengers_log_id FROM `".PASSENGERS_LOG."` as log Join `".PEOPLE."` as p ON log.driver_id=p.id where 1=1 AND log.travel_status='1' and log.rating!=0 $name_where group by log.driver_id";
		//echo $query;
			$rs = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return count($rs);
		}
	}
	
	public function all_rating_companies($offset, $val)
	{
		$com_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
		if($usertype =='C')
		{

				$result = DB::select('*',array('SUM("rating_points")', 'total_posts'))->from(RATING)
				->join(COMPANY, 'INNER')->on(RATING.'.rating_companyid', '=', COMPANY.'.cid')
				->join(PEOPLE, 'INNER')->on(RATING.'.rating_userid', '=', PEOPLE.'.id')
				->where(RATING.'.rating_companyid','=',$company_id)
				->where(COMPANY.'.cid','=',$company_id)
				->where(COMPANY.'.userid','=',$com_createdby)
				->group_by(RATING.'.rating_companyid')
				->execute()
				->as_array();
				return $result;
		}
		else
		{
			$result = DB::select('*',array('SUM("rating_points")', 'total_posts'))->from(RATING)
				->join(COMPANY, 'INNER')->on(RATING.'.rating_companyid', '=', COMPANY.'.cid')
				->join(PEOPLE, 'INNER')->on(RATING.'.rating_userid', '=', PEOPLE.'.id')
				->group_by(RATING.'.rating_companyid')
				->execute()
				->as_array();
				return $result;
		}
	}

	public function countall_rating_drivers()
	{
		$driver_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	if($usertype =='C')
		{
			$condition = " p.`company_id`='".$company_id."'";
			$query = "SELECT r.passengers_log_id FROM ".PASSENGERS_LOG." as r JOIN ".PEOPLE." as p ON r.`driver_id`=p.`id`  WHERE $condition AND r.travel_status='1' and r.rating!=0 and p.user_type='D' GROUP BY r.driver_id";
			$results = Db::query(Database::SELECT, $query)->execute()->as_array();
			return count($results);
		}
		else if($usertype =='M')
		{
			$joins="LEFT JOIN `state` as s ON p.`login_state` = s.`state_id` LEFT JOIN `city` as c ON p.`login_city` = c.`city_id` ";
			$condition = " p.`login_state` = '".$state_id."' AND p.`login_city` = '".$city_id."' AND s.`state_status` = 'A' and c.`city_status` = 'A'";
			$query = "SELECT r.passengers_log_id FROM ".PASSENGERS_LOG." as r JOIN ".PEOPLE." as p ON r.`driver_id`=p.`id` $joins WHERE $condition AND r.travel_status='1' and r.rating!=0 and p.user_type='D' and p.company_id='$company_id' GROUP BY r.driver_id";
			$results = Db::query(Database::SELECT, $query)->execute()->as_array();
			return count($results);
		}
		else if($usertype =='D')
		{
			$query = "SELECT r.passengers_log_id FROM ".PASSENGERS_LOG." as r JOIN ".PEOPLE." as p ON r.`driver_id`=p.`id`  WHERE p.id='$driver_createdby' and r.travel_status='1' and r.rating!=0 group by r.driver_id";
			$results = Db::query(Database::SELECT, $query)->execute()->as_array();
			return count($results);
		}
		else
		{
			$query = "SELECT log.passengers_log_id FROM `".PASSENGERS_LOG."` as log Join `".PEOPLE."` as p ON log.driver_id=p.id WHERE log.travel_status='1' and log.rating!=0 group by log.driver_id";
			$results = Db::query(Database::SELECT, $query)->execute()->as_array();
			return count($results);
		}
	}


	public function all_rating_drivers($offset, $val)
	{
		$driver_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	if($usertype =='C')
		{
			//$joins="LEFT JOIN `state` as s ON p.`login_state` = s.`state_id` LEFT JOIN `city` as c ON p.`login_city` = c.`city_id` ";
			$condition = " p.`company_id`='".$company_id."'";
			$query = "SELECT r.driver_id,sum(r.rating) as total_posts,count(r.passengers_log_id) as co_nt,p.name as name FROM ".PASSENGERS_LOG." as r JOIN ".PEOPLE." as p ON r.`driver_id`=p.`id`  WHERE $condition AND r.travel_status='1' and r.rating!=0 and p.user_type='D' GROUP BY r.driver_id limit $val offset  $offset";
			//echo $query;
			$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();

		  return $results;
		}
		else if($usertype =='M')
		{

			$joins="LEFT JOIN `state` as s ON p.`login_state` = s.`state_id` LEFT JOIN `city` as c ON p.`login_city` = c.`city_id` ";
			$condition = " p.`login_state` = '".$state_id."' AND p.`login_city` = '".$city_id."' AND s.`state_status` = 'A' and c.`city_status` = 'A'";

			$query = "SELECT r.driver_id,sum(r.rating) as total_posts,count(r.passengers_log_id) as co_nt,p.name as name FROM ".PASSENGERS_LOG." as r JOIN ".PEOPLE." as p ON r.`driver_id`=p.`id` $joins WHERE $condition AND r.travel_status='1' and r.rating!=0 and p.user_type='D' and p.company_id='$company_id' GROUP BY r.driver_id limit $val offset  $offset";

			$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();

		  return $results;
		}
		else if($usertype =='D')
		{
			/*$result = DB::select('*',array('SUM("rating_points")', 'total_posts'))->from(RATING)
				->join(PEOPLE, 'INNER')->on(RATING.'.rating_driverid', '=', PEOPLE.'.id')
				->where(PEOPLE.'.id','=',$driver_createdby)
				->group_by(RATING.'.rating_driverid')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
			return $result;*/
			$query = "SELECT r.driver_id,sum(r.rating) as total_posts,count(r.passengers_log_id) as co_nt,p.name as name FROM ".PASSENGERS_LOG." as r JOIN ".PEOPLE." as p ON r.`driver_id`=p.`id`  WHERE p.id='$driver_createdby' and r.travel_status='1' and r.rating!=0 group by r.driver_id LIMIT ".$offset.",".$val;
			$result = Db::query(Database::SELECT, $query)->execute()->as_array();
			return $results;
		}
		else
		{
			$query = "SELECT log.driver_id,sum(log.rating) as total_posts,count(log.passengers_log_id) as co_nt,p.name as name FROM `".PASSENGERS_LOG."` as log Join `".PEOPLE."` as p ON log.driver_id=p.id WHERE log.travel_status='1' and log.rating!=0 group by log.driver_id LIMIT ".$offset.",".$val."";			
			//echo $query;
			$result = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();

		  return $result;
		}
	}
	public function delete_ratingdrivers($id) {
		$user = DB::delete(RATING)
			-> where('rating_id', '=',$id)
			-> execute();
		return $user;
	}
	public function delete_ratingcompanies($id) {
		$user = DB::delete(RATING)
			-> where('rating_id', '=',$id)
			-> execute();
		return $user;
	}
	
	public function get_all_ratingdrivers_searchlist($keyword = "",$comp_id="", $offset,$val)
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);
		$usertype = $_SESSION['user_type'];	
		$driver_createdby = $_SESSION['userid'];
		$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
		if($usertype =='C')
		{ 
			$condition="";
			//$joins="LEFT JOIN `state` as s ON p.`login_state` = s.`state_id` LEFT JOIN `city` as c ON p.`login_city` = c.`city_id` ";
			$condition = " p.`company_id`='".$company_id."'";
			$name_where="";	
			if($keyword){
				$condition .= " AND (p.name LIKE  '%$keyword%')";
			}
				$query = "SELECT r.driver_id,sum(r.rating) as total_posts,count(r.passengers_log_id) as co_nt,p.name as name FROM ".PASSENGERS_LOG." as r JOIN ".PEOPLE." as p ON r.`driver_id`=p.`id`  WHERE $condition AND r.travel_status='1' and r.rating!=0 and p.user_type='D' GROUP BY r.driver_id LIMIT ".$offset.",".$val;

			//echo $query;
			$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();

		  return $results;
		  
		}
		else if($usertype =='M')
		{
		
			$condition="";
			$joins="LEFT JOIN `state` as s ON p.`login_state` = s.`state_id` LEFT JOIN `city` as c ON p.`login_city` = c.`city_id` ";
			$condition = " p.`login_state` = '".$state_id."' AND p.`login_city` = '".$city_id."' AND s.`state_status` = 'A' and c.`city_status` = 'A'";
			$name_where="";	
			if($keyword){
				$condition .= " AND (p.name LIKE  '%$keyword%')";
			}

			$query = "SELECT r.driver_id,sum(r.rating) as total_posts,count(r.passengers_log_id) as co_nt,p.name as name FROM ".PASSENGERS_LOG." as r JOIN ".PEOPLE." as p ON r.`driver_id`=p.`id` $joins WHERE $condition AND r.travel_status='1' and r.rating!=0 and p.user_type='D' and p.company_id='$company_id' GROUP BY r.driver_id LIMIT ".$offset.",".$val;
						
			//echo $query;
			$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();

		  return $results;
		}
		else if($usertype =='D')
		{
			/*$result = DB::select('*',array('SUM("rating_points")', 'total_posts'))->from(RATING)
				->join(PEOPLE, 'INNER')->on(RATING.'.rating_driverid', '=', PEOPLE.'.id')
				->where(PEOPLE.'.id','=',$driver_createdby)
				->group_by(RATING.'.rating_driverid')
				->execute()
				->as_array();
			return $result; */
			
			$query = "SELECT r.driver_id,sum(r.rating) as total_posts,count(r.passengers_log_id) as co_nt,p.name as name FROM ".PASSENGERS_LOG." as r JOIN ".PEOPLE." as p ON r.`driver_id`=p.`id`  WHERE p.id='$driver_createdby' and r.travel_status='1' and r.rating!=0 group by r.driver_id LIMIT ".$offset.",".$val;
			$result = Db::query(Database::SELECT, $query)->execute()->as_array();
			return $results;
		}
		else
		{
		$name_where="";	
		if($keyword){
			$name_where  .= " AND (p.name LIKE  '%$keyword%')";
       	}
		if($comp_id){
			$name_where  .= " AND p.`company_id`='".$comp_id."'";

			}
		//$query = " select *,SUM(rating_points) as total_posts from " . RATING . " INNER JOIN " . PEOPLE . " ON " . RATING . " .rating_driverid = " . PEOPLE . " .id where 1=1 $name_where group by rating_driverid order by rating_id ASC";
		$query = "SELECT log.driver_id,sum(log.rating) as total_posts,count(log.passengers_log_id) as co_nt,p.name as name FROM `".PASSENGERS_LOG."` as log Join `".PEOPLE."` as p ON log.driver_id=p.id where 1=1 AND log.travel_status='1' and log.rating!=0 $name_where group by log.driver_id LIMIT ".$offset.",".$val;
		//echo $query;
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();

				return $results;
			}
	}

	public function get_all_ratingcompanies_searchlist($keyword = "")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$name_where="";	
		if($keyword){
			$name_where  = " AND (".COMPANY.".company_name LIKE  '%$keyword%')";
       		 }
		$query = " select *,SUM(rating_points) as total_posts from " . RATING . " INNER JOIN " . COMPANY . " ON " . RATING . " .rating_companyid = " . COMPANY . " .cid INNER JOIN " . PEOPLE . " ON " . RATING . " .rating_userid = " . PEOPLE . " .id where 1=1 $name_where group by rating_companyid order by rating_id ASC";
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return $results;
	}
	
	
	public function userNamebyId($id)
	{
				$result = DB::select('name')->from(PEOPLE)
				->where('id','=',$id)
				->execute()
				->as_array();
				if(count($result) > 0)
				{
					return $result[0]['name'];
				}
				else
				{
					return ''; 
				}	
		
	}

	public function userNamebyEmail($id)
	{
				$result = DB::select('email')->from(PEOPLE)
				->where('id','=',$id)
				->execute()
				->as_array();

				if(count($result) > 0)
				{
					return $result[0]['email'];
				}
				else
				{
					return ''; 
				}
				
		
	}
		
 	public function packagereport_list()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = $_SESSION['company_id'];
	   	
		if($usertype =='C')
	   	{
		$rs = DB::select()->from(PACKAGE_REPORT)
				->where('upgrade_userid','=',$company_id)
				->order_by('upgrade_id','desc')
				->execute()
				->as_array();
				
				return $rs;
		}
		else
		{
		$rs = DB::select()->from(PACKAGE_REPORT)
				->order_by('upgrade_id','desc')
				->execute()
				->as_array();
				
				return $rs;
		
		}	
	}
	
	public function count_packagereport_list($comp_id)
	{ 
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		$company_id = $_SESSION['company_id'];	
	   	
		if($usertype =='C')
	   	{
		$rs = DB::select()->from(PACKAGE_REPORT)
				->where('upgrade_companyid','=',$company_id)
				->order_by('upgrade_id','desc')
				->execute()
				->as_array();
				
				return count($rs);
		}
		else
		{
		$name_where="";
		if($comp_id!=""){ $name_where .="and upgrade_companyid=$comp_id "; }
		if($comp_id=="All"){$name_where="";}
		/*$rs = DB::select()->from(PACKAGE_REPORT)
				$name_where
				->order_by('upgrade_id','desc')
				->execute()
				->as_array(); */

			$query = " select * from ". PACKAGE_REPORT ." where 1=1 $name_where order by upgrade_id DESC";
			//echo $query;exit;
	 		$rs = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
				
				return count($rs);
	
		}	
	}
	
	public function all_packagereport_list($comp_id="",$offset, $val)
	{
		
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = $_SESSION['company_id'];	
	   	
		if($usertype =='C')
	   	{
		$rs = DB::select()->from(PACKAGE_REPORT)
				->where('upgrade_companyid','=',$company_id)
				->order_by('upgrade_id','desc')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
			 
				$details = array();
				foreach($rs as $key => $res)		
				{
					$details[$key]['name'] = $this->userNamebyCompanyId($res['upgrade_companyid']);
					$details[$key]['email'] = $this->userNamebyCompanyEmail($res['upgrade_companyid']);
					$details[$key]['package_name'] = $res['upgrade_packagename'];
					$details[$key]['package_type'] = $res['check_package_type'];
					$details[$key]['no_taxi'] = $res['upgrade_no_taxi'];
					$details[$key]['no_driver'] = $res['upgrade_no_driver'];	
					$details[$key]['package_price'] = $res['upgrade_amount'];	
					$details[$key]['credit_date'] = $res['upgrade_date'];
					$details[$key]['expiry_date'] = $res['upgrade_expirydate'];

	
				}
				
			 return $details;
		}
		else

		{
		$name_where="";
		if($comp_id!=""){ $name_where .=" and upgrade_companyid=$comp_id "; }
		if($comp_id=="All"){$name_where="";}
		
		/*$rs = DB::select()->from(PACKAGE_REPORT)
				$name_where
				->order_by('upgrade_id','desc')
				->limit($val)->offset($offset)
				->execute()
				->as_array(); */

			$query = " select * from " . PACKAGE_REPORT ." where 1=1 $name_where order by upgrade_id DESC limit $offset,$val";
	 		$rs = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();

				$details = array();
				foreach($rs as $key => $res)		
				{
					$details[$key]['name'] = $this->userNamebyCompanyId($res['upgrade_companyid']);
					$details[$key]['email'] = $this->userNamebyCompanyEmail($res['upgrade_companyid']);
					$details[$key]['package_name'] = $res['upgrade_packagename'];
					$details[$key]['package_type'] = $res['check_package_type'];
					$details[$key]['no_taxi'] = $res['upgrade_no_taxi'];
					$details[$key]['no_driver'] = $res['upgrade_no_driver'];	
					$details[$key]['package_price'] = $res['upgrade_amount'];	
					$details[$key]['credit_date'] = $res['upgrade_date'];
					$details[$key]['expiry_date'] = $res['upgrade_expirydate'];

	
				}
				
			 return $details;

	
		}
		
	}


	public function userNamebyCompanyId($id)
	{
				$result = DB::select('name')->from(PEOPLE)
				->where('company_id','=',$id)
				->where('user_type','=','C')
				->execute()
				->as_array();
				if(count($result) > 0)
				{
					return $result[0]['name'];
				}
				else
				{
					return ''; 
				}	
		
	}

	public function userNamebyCompanyEmail($id)
	{
				$result = DB::select('email')->from(PEOPLE)
				->where('company_id','=',$id)
				->where('user_type','=','C')
				->execute()
				->as_array();

				if(count($result) > 0)
				{
					return $result[0]['email'];
				}
				else
				{
					return ''; 
				}
				
		
	}
	
	public function get_all_packagereport_searchlist($keyword = "", $status = "")
	{

		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];	

	   	
	   	if($usertype =='M')
	   	{
			$user_created_where = " AND mapping_companyid = $company_id ";
		}
		else if($usertype =='C')
	   	{
			$user_created_where = " AND mapping_companyid = $company_id ";
		}
		else
		{
			$user_created_where = "";		
		}

			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		//condition for status
		//====================== 
		
		$status_where= ($status) ? " AND mapping_status = '$status'" : "";
	
		//search result export
        //=====================
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
       		 }


			
		$query = " select * from " . TAXIMAPPING . " left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".TAXI." on ".TAXIMAPPING.".mapping_taxiid = ".TAXI.".taxi_id  left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id  left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id  left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id where 1=1 $user_created_where $status_where $name_where order by mapping_startdate DESC ";
		

	 		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				$details = array();
				foreach($result as $key => $res)		
				{
					$details[$key]['created_by'] = $this->userNamebyId($res['mapping_createdby']);
					$details[$key]['mapping_id'] = $res['mapping_id'];
					$details[$key]['mapping_status'] = $res['mapping_status'];
					$details[$key]['name'] = $res['name'];
					$details[$key]['company_name'] = $res['company_name'];	
					$details[$key]['taxi_no'] = $res['taxi_no'];	
					$details[$key]['country_name'] = $res['country_name'];
					$details[$key]['state_name'] = $res['state_name'];
					$details[$key]['city_name'] = $res['city_name'];
					$details[$key]['mapping_startdate'] = $res['mapping_startdate'];
					$details[$key]['mapping_enddate'] = $res['mapping_enddate'];
	
				}
				
			 return $details;

   }
   	
	public function active_packagereport_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       
             //$arr_chk = " userid in ('" . implode("','",$activeids) . "') ";	
             
             $result = DB::update(TAXIMAPPING)->set(array('mapping_status' => 'A'))->where('mapping_id', 'IN', $activeids)
			->execute();
			
		        	  
			 return count($result);
	}
	
	public function block_packagereport_request($activeids)
	{
			$result = DB::update(TAXIMAPPING)->set(array('mapping_status' => 'D'))->where('mapping_id', 'IN', $activeids)
			->execute();
			
			 return count($result);
	}
	
	public function trash_motor_request($activeids)
	{
		$result = DB::update(MOTORCOMPANY)->set(array('motor_status' => 'T'))->where('motor_id', 'IN', $activeids)
			->execute();		
		
			return $result;
	}

	public function trash_company_request($activeids)
	{
		$result = DB::update(COMPANY)->set(array('company_status' => 'T'))
				->where('userid', 'IN', $activeids)
				->execute();
		
		$result = DB::update(PEOPLE)->set(array('status' => 'T'))
				->where('id', 'IN', $activeids)
				->where('user_type', '=', 'C')
				->execute();
			
				
				$result_company = DB::select('company_id')->from(PEOPLE)
				->where('id', 'IN', $activeids)
				->where('user_type','=','C')
				->execute()
				->as_array();
					
					if(count($result_company) >0){
						$company_id=array();
						foreach($result_company as $k){
							$company_id[]=$k['company_id'];
						 }
						 if(count($company_id) >0){
							 // updating below users of company
							$result = DB::update(PEOPLE)->set(array('status' => 'T'))->where('company_id', 'IN', $company_id)
							->execute();
							$result = DB::update(TAXI)->set(array('taxi_status' => 'T'))->where('taxi_company', 'IN', $company_id)
							->execute();

						 }
					}
				
		
		return $result;	
	}
	
	public function trash_field_request($activeids)
	{
		$result = DB::update(MANAGEFIELD)->set(array('field_status' => 'T'))
				->where('field_id', 'IN', $activeids)
				->execute();
		return $result;	
	}
	
	public function trash_package_request($activeids)
	{
		$result = DB::update(PACKAGE)->set(array('package_status' => 'T'))
				->where('package_id', 'IN', $activeids)
				->execute();
		return $result;	
	}
	
	public function trash_model_request($activeids)
	{
		$result = DB::update(MOTORMODEL)->set(array('model_status' => 'T'))->where('model_id', 'IN', $activeids)
			->execute();		
		
			return $result;
	}

	public function trash_manager_request($activeids)
	{
	
		$result = DB::update(PEOPLE)->set(array('status' => 'T'))->where('id', 'IN', $activeids)


			->execute();		
		
			return $result;
	}

	public function trash_admin_request($activeids)
	{
	
		$result = DB::update(PEOPLE)->set(array('status' => 'T'))->where('id', 'IN', $activeids)


			->execute();		
		
			return $result;
	}

	public function trash_taxi_request($activeids)
	{
	
		$result = DB::update(TAXI)->set(array('taxi_status' => 'T'))->where('taxi_id', 'IN', $activeids)
			->execute();
		
			return $result;
	}
	
	public function trash_driver_request($activeids)
	{
		$result = DB::update(PEOPLE)->set(array('status' => 'T'))->where('id', 'IN', $activeids)
			->execute();
		
		return $result;
	}


	public function get_all_contacts_searchlist_count($keyword = "",$cid='')
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$name_where="";	
		if($keyword){
			$name_where  = " AND (".CONTACTS.".first_name LIKE  '%$keyword%' OR ".CONTACTS.".subject LIKE  '%$keyword%')";
       		 }
       	$company_cid="";	 
        if($cid!='')
        $company_cid=" AND contact_cid=$cid";		 
		$query = " select cid from " . CONTACTS . " where 1=1 $name_where $company_cid order by sent_date DESC";
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return count($results);
	}
	
	public function get_all_contacts_searchlist($keyword = "",$cid='')
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$name_where="";	
		if($keyword){
			$name_where  = " AND (".CONTACTS.".first_name LIKE  '%$keyword%' OR ".CONTACTS.".subject LIKE  '%$keyword%')";
       		 }
       	$company_cid="";	 
        if($cid!='')
        $company_cid=" AND contact_cid=$cid";		 
		$query = " select cid,email,first_name,subject,message,sent_date from " . CONTACTS . " where 1=1 $name_where $company_cid order by sent_date DESC";
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return $results;
	}
	
	public function get_all_free_quotes_searchlist_count($keyword = "")
	{
		$keyword = str_replace("%","!%",$keyword);
		$name_where="";	
		if($keyword){
			$name_where  = " AND (".GET_FREE_QUOTES.".name LIKE  '%$keyword%' OR ".GET_FREE_QUOTES.".email LIKE  '%$keyword%')";
       		 }	 
		$query = " select * from " . GET_FREE_QUOTES . " where 1=1 $name_where order by id DESC";
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return count($results);
	}
	
	public function get_all_free_quotes_searchlist($keyword = "")
	{
		$keyword = str_replace("%","!%",$keyword);

		$name_where="";	
		if($keyword){
			$name_where  = " AND (".GET_FREE_QUOTES.".name LIKE  '%$keyword%' OR ".GET_FREE_QUOTES.".email LIKE  '%$keyword%')";
       		 }	 	 
		$query = " select * from " . GET_FREE_QUOTES . " where 1=1 $name_where  order by id DESC";
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return $results;
	}
	
	public function delete_free_quotes($id) {
			$user = DB::delete(GET_FREE_QUOTES)
				-> where('id', '=',$id)
				-> execute();
			return $user;
	}
	
	public function delete_contacts($id) {
			$user = DB::delete(CONTACTS)
				-> where('cid', '=',$id)
				-> execute();
			return $user;
	}
	
	public function contacts_list()
	{
		$rs = DB::select()->from(CONTACTS)
				->execute()
				->as_array();
				return $rs;
	}	
	
	public function count_contacts_list($cid='')
	{
		if($cid=='')
		{
		$rs = DB::select('cid')->from(CONTACTS)
				->execute();
		}
		else
		{
		$rs = DB::select('cid')->from(CONTACTS)->where('contact_cid','=',$cid)
				->execute();			
		}
		return count($rs);
	}
	
	public function all_contacts_list($offset, $val,$cid='')
	{
		if($cid=='')
		{
			$rs = DB::select('cid','email','first_name','subject','message','sent_date')->from(CONTACTS)
					->order_by('sent_date','desc')->limit($val)->offset($offset)
					->execute()
					->as_array();
		}
		else
		{
			$rs = DB::select('cid','email','first_name','subject','message','sent_date')->from(CONTACTS)->where('contact_cid','=',$cid)
					->order_by('sent_date','desc')->limit($val)->offset($offset)
					->execute()
					->as_array();			
		}
				return $rs;
	}
	
	
	public function count_free_quotes_list($cid='')
	{
		if($cid=='')
		{
		$rs = DB::select()->from(GET_FREE_QUOTES)
				->execute();
		}
		
		return count($rs);
	}
	
	public function all_free_quotes_list($offset, $val,$cid='')
	{
		if($cid=='')
		{
			$rs = DB::select()->from(GET_FREE_QUOTES)
					->order_by('id','desc')->limit($val)->offset($offset)
					->execute()
					->as_array();
		}
		return $rs;
	}
	
	public function content_list()
	{
		$rs = DB::select()->from(CMS)
				->join(MENU, 'LEFT')->on(CMS.'.menu_id', '=', MENU.'.menu_id')
				->where(CMS.'.type', '=','1')
				->where(CMS.'.status', '=','1')
				->execute()
				->as_array();
				return $rs;
	}
	
	public function count_content_list()
	{
		$rs = DB::select()->from(CMS)
				->join(MENU)->on(CMS.'.menu_id', '=', MENU.'.menu_id')
				->where(CMS.'.type', '=','1')
				->where(CMS.'.status', '=','1')
				->execute();
		return count($rs);
	}
	public function count_company_content_list($cid)
	{
		$rs = DB::select()->from(COMPANY_CMS)
				->where('type', '=','1')
				->where('company_id', '=',$cid)
				->execute();
		return count($rs);
	}	
	public function all_content_list($offset='',$val='')
	{
		
		$rs = DB::select()->from(CMS)
				->join(MENU)->on(CMS.'.menu_id', '=', MENU.'.menu_id')
				->where(CMS.'.type', '=','1')
				->where(CMS.'.status', '=','1')
				->order_by(CMS.'.menu','asc')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
				return $rs;
	}

	public function all_company_content_list($offset='',$val='',$cid='')
	{
		
		$rs = DB::select()->from(COMPANY_CMS)
				->where('type', '=','1')
				->where('company_id', '=',$cid)
				->order_by('id','asc')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
				return $rs;
	}
	public function company_content_request_change($activeids,$status)
	{

		$result = DB::update(COMPANY_CMS)->set(array('status' => $status))->where('id', 'IN', $activeids)
			->execute();
			
		return count($result);
	}		
	public function contacts_list_view($id)
	{
		$user = DB::select(CONTACTS.'.email',CONTACTS.'.first_name',CONTACTS.'.subject',CONTACTS.'.message',CONTACTS.'.sent_date',CONTACTS.'.phone',COMPANY.'.company_name')->from(CONTACTS)->join(COMPANY,'LEFT')->on(CONTACTS.'.contact_cid','=',COMPANY.'.cid')
				->where(CONTACTS.'.cid', '=',$id)-> execute()->as_array();
		return $user;
	}
	
	
	public function free_quotes_list_view($id)
	{
		$user = DB::select()->from(GET_FREE_QUOTES)
				-> where(GET_FREE_QUOTES.'.id', '=',$id)
				-> execute()
				->as_array();
			return $user;
	}
	
	
	public function taxicount($cid)
	{
		$query = " select count(taxi_id) as taxi_count from " . TAXI . " where  taxi_company='$cid' order by taxi_id ASC";
	 	$result = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();


		return $result[0]['taxi_count'];
	}

	public function drivercount($cid)
	{
		$query = " select count(id) as driver_count from " . PEOPLE . " where  company_id='$cid' and user_type='D' order by id ASC";
	 	$result = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();

		return $result[0]['driver_count'];
	}
	
	public function managercount($cid)
	{
		$query = " select count(id) as driver_count from " . PEOPLE . " where  company_id='$cid' and user_type='M' order by id ASC";
	 	$result = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();

		return $result[0]['driver_count'];
	}
	
	public function details_taxiinfo($id)
	{
	
		$result = DB::select('taxi_no','taxi_image','taxi_sliderimage','taxi_serializeimage','taxi_id','motor_name',MOTORMODEL.'.taxi_speed','max_luggage','taxi_company')->from(TAXI)->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')->join(MOTORCOMPANY, 'LEFT')->on(TAXI.'.taxi_type', '=', MOTORCOMPANY.'.motor_id')->join(MOTORMODEL, 'LEFT')->on(TAXI.'.taxi_model', '=', MOTORMODEL.'.model_id')
			->where(TAXI.'.taxi_id', '=', $id)
			->execute()
			->as_array();//->join(ADDFIELD, 'LEFT')->on(TAXI.'.taxi_id', '=', ADDFIELD.'.taxi_id')
		//echo '<pre>';print_r($result);exit;
		return $result;
	}

	public static function taxi_additionalfields()
	{

		$result = DB::select('field_name','field_labelname')->from(MANAGEFIELD)->where('field_status','=','A')->order_by('field_order','asc')
			->execute()
			->as_array();
		  return $result;
	}
		
	public function details_userinfo($id,$driver = 0)
	{
		if($driver == 1) {
			$result = DB::select(COMPANY.'.company_name',COMPANY.'.company_address','user_createdby','id','user_type','company_id','login_country','login_state','login_city','name','username','lastname','email','phone','address','user_type','driver_license_id','dob','account_balance','booking_limit','login_status','gender',DRIVER_INFO.'.driver_license_expire_date',DRIVER_INFO.'.driver_pco_license_number',DRIVER_INFO.'.driver_pco_license_expire_date',DRIVER_INFO.'.driver_insurance_number',DRIVER_INFO.'.driver_insurance_expire_date',DRIVER_INFO.'.driver_national_insurance_number',DRIVER_INFO.'.driver_national_insurance_expire_date',DRIVER_REF_DETAILS.'.registered_driver_code',COUNTRY.'.country_name',STATE.'.state_name',CITY.'.city_name','login_country','login_state','login_city')
			->from(PEOPLE)
			->join(COMPANY,'LEFT')->on(PEOPLE.'.company_id','=',COMPANY.'.cid')
			->join(DRIVER_INFO,'LEFT')->on(DRIVER_INFO.'.driver_id','=',PEOPLE.'.id')
			->join(DRIVER_REF_DETAILS,'LEFT')->on(DRIVER_REF_DETAILS.'.registered_driver_id','=',PEOPLE.'.id')
			->join(COUNTRY,'LEFT')->on(PEOPLE.'.login_country','=',COUNTRY.'.country_id')
			->join(STATE,'LEFT')->on(PEOPLE.'.login_state','=',STATE.'.state_id')
			->join(CITY,'LEFT')->on(PEOPLE.'.login_city','=',CITY.'.city_id')
			->where('id','=',$id)->where('user_type', '=', 'D')
			->execute()
			->as_array();		
		} else {
			$result = DB::select('id','user_createdby','user_type','company_id','login_country','login_state','login_city','name','username','lastname','email','phone','address','user_type','driver_license_id','dob','account_balance','booking_limit','login_status',COMPANY.'.company_name',COMPANY.'.company_address',COUNTRY.'.country_name',STATE.'.state_name',CITY.'.city_name','login_country','login_state','login_city')->from(PEOPLE)
				->join(COMPANY,'LEFT')->on(PEOPLE.'.company_id','=',COMPANY.'.cid')
				->join(COUNTRY,'LEFT')->on(PEOPLE.'.login_country','=',COUNTRY.'.country_id')
				->join(STATE,'LEFT')->on(PEOPLE.'.login_state','=',STATE.'.state_id')
				->join(CITY,'LEFT')->on(PEOPLE.'.login_city','=',CITY.'.city_id')
				->where('id','=',$id)
				->execute()
				->as_array();
		}		
		//echo '<pre>';print_r($result); exit;
		$details = array();		
		foreach($result as $key => $res)
		{
			$details[$key]['created_by'] = $this->userNamebyId($res['user_createdby']);
			if(($res['user_type'] != 'N') && ($res['company_id'] !='') && ($res['user_type'] !='S'))
			{
				$details[$key]['company_id'] = isset($res['company_id']) ? $res['company_id'] : '';
				$details[$key]['company_name'] = isset($res['company_name']) ?  $res['company_name'] :'';
				$details[$key]['company_address'] = isset($res['company_address']) ?  $res['company_address'] :'';
				/*$company_details = $this->companydetails($res['company_id']);
				if(count($company_details) >0 && $company_details !=""){
					$details[$key]['company_id'] = $res['company_id'];
					$details[$key]['company_name'] = $company_details[0]['company_name'];
					$details[$key]['company_address'] = $company_details[0]['company_address'];
			    }else{
					$details[$key]['company_id'] = '';
					$details[$key]['company_name'] = '';
					$details[$key]['company_address'] = '';
				}
				$details[$key]['base_fare'] = $company_details[0]['base_fare'];
				$details[$key]['min_fare'] = $company_details[0]['min_fare'];
				$details[$key]['cancellation_fare'] = $company_details[0]['cancellation_fare'];
				$details[$key]['below_km'] = $company_details[0]['below_km'];
				$details[$key]['above_km'] = $company_details[0]['above_km'];
				$details[$key]['night_charge'] = $company_details[0]['night_charge'];
				$details[$key]['night_timing_from'] = $company_details[0]['night_timing_from'];
				$details[$key]['night_timing_to'] = $company_details[0]['night_timing_to'];
				$details[$key]['night_fare'] = $company_details[0]['night_fare'];
				$details[$key]['waiting_time'] = $company_details[0]['waiting_time'];*/
				//$details[$key]['account_balance'] = $company_details[0]['account_balance'];
			}			
			/*if($res['login_country'] != '')
			{
				$details[$key]['country_name'] = $this->countrydetails($res['login_country']);
			}
			if($res['login_state'] != '')
			{
				$details[$key]['state_name'] = $this->statedetails($res['login_state']);
			}
			if($res['login_city'] != '')
			{
				$details[$key]['city_name'] = $this->citydetails($res['login_city']);
			}*/
			$details[$key]['country_name'] = isset($res['country_name']) ?  $res['country_name'] :'';
			$details[$key]['state_name'] = isset($res['state_name']) ?  $res['state_name'] :'';
			$details[$key]['city_name'] = isset($res['city_name']) ?  $res['city_name'] :'';
			$details[$key]['name'] = $res['name'];
			$details[$key]['username'] = $res['username'];
			$details[$key]['lastname'] = $res['lastname'];
			$details[$key]['email'] = $res['email'];
			$details[$key]['phone'] = $res['phone'];
			$details[$key]['address'] = $res['address'];
			$details[$key]['user_type'] = $res['user_type'];
			$details[$key]['driver_license_id'] = $res['driver_license_id'];
			$details[$key]['dob'] = $res['dob'];
			$details[$key]['id'] = $res['id'];
			$details[$key]['account_balance'] = $res['account_balance'];
			$details[$key]['booking_limit'] = $res['booking_limit'];
			$details[$key]['login_status'] = $res['login_status'];
			$details[$key]['login_country'] = $res['login_country'];
			$details[$key]['login_state'] = $res['login_state'];
			$details[$key]['login_city'] = $res['login_city'];
			if($driver == 1) {
				$details[$key]['gender'] = $res['gender'];
				$details[$key]['driver_license_expire_date'] = $res['driver_license_expire_date'];
				$details[$key]['driver_pco_license_number'] = $res['driver_pco_license_number'];
				$details[$key]['driver_pco_license_expire_date'] = $res['driver_pco_license_expire_date'];
				$details[$key]['driver_insurance_number'] = $res['driver_insurance_number'];
				$details[$key]['driver_insurance_expire_date'] = $res['driver_insurance_expire_date'];
				$details[$key]['driver_national_insurance_number'] = $res['driver_national_insurance_number'];
				$details[$key]['driver_national_insurance_expire_date'] = $res['driver_national_insurance_expire_date'];
				$details[$key]['registered_driver_code'] = $res['registered_driver_code'];
			}
		}		
	 	return $details;		 		
	}
	
	/** passenger info **/
	public function details_passengerinfo($id)
	{
		$result = DB::select('id','name','email','phone','country_code','address','user_status','discount','referral_code','referral_code_amount','wallet_amount')->from(PASSENGERS)->where('id','=',$id)->execute()->as_array();
		$details = array();
		foreach($result as $key => $res)
		{
			//function to get referred by passenger name
			$referred_by = DB::select(PASSENGERS.'.name')->from(PASSENGERS)->join(PASSENGER_REFERRAL,'left')->on(PASSENGER_REFERRAL.'.referred_by','=',PASSENGERS.'.id')->where(PASSENGER_REFERRAL.'.passenger_id','=',$res['id'])->execute()->get('name'); 
			$details[$key]['referred_by'] = $referred_by;
			$details[$key]['name'] = $res['name'];
			$details[$key]['email'] = $res['email'];
			$details[$key]['country_code'] = $res['country_code'];
			$details[$key]['phone'] = $res['phone'];
			$details[$key]['address'] = $res['address'];
			$details[$key]['user_status'] = $res['user_status'];
			$details[$key]['discount'] = $res['discount'];
			$details[$key]['referral_code'] = $res['referral_code'];
			$details[$key]['referral_code_amount'] = $res['referral_code_amount'];
			$details[$key]['wallet_amount'] = $res['wallet_amount'];
			$details[$key]['id'] = $res['id'];
		}
		
	 	return $details;

	}
	
	/** passenger group info **/
	public function details_passenger_group_info($id)
	{
		$query = "SELECT *  FROM ".TBLGROUP." WHERE FIND_IN_SET(".$id.",passenger_id)";
		$result = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();

		return $result;
	}
	
	/** passenger group info **/
	public function details_passenger_account_info($id)
	{
		$query = "SELECT *  FROM ".TBLGROUPACCOUNT." WHERE aid=".$id."";
		$result = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
		return $result;
	}
	
	/** comapny details **/
	public function companydetails($id)
	{ 
		$result = DB::select('company_name','company_address')->from(COMPANY)
				->where('cid','=',$id)
				->execute()
				->as_array();
				
				if(count($result) > 0)
				{
					return $result;
				}
				else
				{
					return ''; 
				}	
	}

	public function countrydetails($id)
	{
				$result = DB::select('country_name')->from(COUNTRY)
				->where('country_id','=',$id)
				->execute()
				->as_array();
				if(count($result) > 0)
				{
					return $result[0]['country_name'];
				}
				else
				{
					return ''; 
				}	
		
	}

	public function statedetails($id)
	{
				$result = DB::select('state_name')->from(STATE)
				->where('state_id','=',$id)
				->execute()
				->as_array();
				if(count($result) > 0)
				{
					return $result[0]['state_name'];
				}
				else
				{
					return ''; 
				}	
		
	}
	
	public function citydetails($id)
	{
				$result = DB::select('city_name')->from(CITY)
				->where('city_id','=',$id)
				->execute()
				->as_array();
				if(count($result) > 0)
				{
					return $result[0]['city_name'];
				}
				else
				{
					return ''; 
				}	
		
	}
	
	//For select the view for rating company
	
	public function ratingcompanies_viewlist($id='',$val,$offset)
	{
		$query = " select * from " . RATING . " INNER JOIN " . COMPANY . " ON " . RATING . " .rating_companyid = " . COMPANY . " .cid INNER JOIN " . PEOPLE . " ON " . RATING . " .rating_userid = " . PEOPLE . " .id  where rating_companyid='$id' order by rating.rating_id ASC limit ".$val." , ".$offset."";
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return $results;
	}
	
	//For select the view for rating drivers
	
	public function ratingdrivers_viewlist($id='',$val, $offset)
	{
		$query = " select * from " . RATING . " INNER JOIN " . PEOPLE . " ON " . RATING . " .rating_driverid = " . PEOPLE . " .id where rating_driverid=".$id." order by rating_id ASC limit ".$val." , ".$offset."";
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return $results;
	}
	
	//For count the view for rating drivers
	
	public function ratingdrivers_countlist()
	{
		$query = " select * from " . RATING . " INNER JOIN " . PEOPLE . " ON " . RATING . " .rating_driverid = " . PEOPLE . " .id where 1=1 order by rating_id ASC";
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return count($results);
	}
	
	//For count the view for rating companies
	
	public function ratingcompanies_countlist()
	{
		$query = " select * from " . RATING . " INNER JOIN " . COMPANY . " ON " . RATING . " .rating_companyid = " . COMPANY . " .cid INNER JOIN " . PEOPLE . " ON " . RATING . " .rating_userid = " . PEOPLE . " .id where 1=1 order by rating_id ASC";
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return count($results);
	}
	
	//For select the view for rating company
	
	public function ratingcompanies_listview()
	{
		$query = " select * from " . RATING . " INNER JOIN " . COMPANY . " ON " . RATING . " .rating_companyid = " . COMPANY . " .cid INNER JOIN " . PEOPLE . " ON " . RATING . " .rating_userid = " . PEOPLE . " .id where 1=1 order by rating.rating_id ASC ";
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return $results;
	}
	
	//For select the view for rating drivers
	
	public function ratingdrivers_listview()
	{
		
		$query = " select * from " . RATING . " INNER JOIN " . PEOPLE . " ON " . RATING . " .rating_driverid = " . PEOPLE . " .id where 1=1  order by rating_id ASC ";
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return $results;
	}
	
	//For search the view for rating drivers
	
	public function get_all_ratingcompanies_searchlist_view($keyword='')
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$name_where="";	
		if($keyword){
			$name_where  = " AND (".PEOPLE.".name LIKE  '%$keyword%')";
       		 }
		$query = " select * from " . RATING . " INNER JOIN " . COMPANY . " ON " . RATING . " .rating_companyid = " . COMPANY . " .cid INNER JOIN " . PEOPLE . " ON " . RATING . " .rating_userid = " . PEOPLE . " .id where 1=1 $name_where group by rating_companyid order by rating_id ASC";
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return $results;
	}
	
	//For search the view for rating drivers
	
	public function get_all_ratingdrivers_searchlist_view($keyword='')
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$name_where="";	
		if($keyword){
			$name_where  = " AND (".PEOPLE.".name LIKE  '%$keyword%')";
       		 }
		$query = " select * from " . RATING . " INNER JOIN " . PEOPLE . " ON " . RATING . " .rating_driverid = " . PEOPLE . " .id where 1=1 $name_where group by rating_driverid order by rating_id ASC";
	 	$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return $results;
	}
	
	//for manage content view 
	public function content_list_view($id='')
	{
		
		$query = " select * from " . CMS . " LEFT join " . MENU . " on " . MENU . ".menu_id = ". CMS. ".menu_id where id='$id' and status='1' and type='1'";
		$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return $results;
	}
	
	//for manage content view 
	public function company_content_list_view($id='')
	{
		
		$query = " select * from " . COMPANY_CMS . " where id='$id' and type='1'";
		$results = Db::query(Database::SELECT, $query)
			   		->execute()
					->as_array();
				return $results;
	}
		
	//for deleting contents
	public function delete_content($id) {
			$user = DB::delete(CMS)
				->where('id', '=',$id)
				->execute();
			return $user;
	}
	
		/** Validating for edit view content **/
	public function validate_editview($arr) 
	{
		
		return Validation::factory($arr)
		->rule('meta_title', 'not_empty')
		->rule('meta_keyword', 'not_empty')
		->rule('meta_description', 'not_empty')
		->rule('menu_name', 'not_empty');		

	}

		/** Validating for edit view content **/
	public function validate_companyeditview($arr,$cid,$id) 
	{
		
		return Validation::factory($arr)
		->rule('page_title', 'not_empty')
		->rule('page_title', 'max_length', array(':value', '50'))
		->rule('menu_name', 'not_empty')
		->rule('menu_name', 'max_length', array(':value', '20'))
		->rule('page_url', 'not_empty')
		->rule('page_url', 'max_length', array(':value', '20'))
		//->rule('page_url', 'alpha_numeric', array(':value','/^[0-9]{1,}/'))
		->rule('page_url', 'Model_Manage::checkpageurl', array(':value',$cid,$id));		

	}
	public static function checkpageurl($pageurl,$cid,$id)
	{
		// Check if the username already exists in the database
		
		$result = DB::select('page_url')->from(COMPANY_CMS)->where('page_url','=',$pageurl)->where('company_id','=',$cid)->where('id','!=',$id)
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
	/** Updating content view while editing **/
	public function update_editview_content($post,$id)
	{
		$result =DB::select()->from(MENU)
			->where(MENU.'.menu_id','=',$post['menu_name'])
			->execute()
			->as_array();		
		if(count($result)>0)
		{
			$menu_name = $result[0]['menu_name'];
		}	
		else
		{
			$menu_name = "";
		}
		$result = DB::update(CMS)->set(array('menu_id' => $post['menu_name'],'menu'=>$menu_name,'meta_title' => $post['meta_title'],'meta_keyword' => $post['meta_keyword'],'meta_description' => $post['meta_description'],'content' => $post['content']))->where('id', '=', $id)
			->execute();
		return $result;
	}

	/** Updating content view while editing **/
	public function update_edit_company_content($post,$id)
	{
		$result = DB::update(COMPANY_CMS)->set(array('menu_name' => $post['menu_name'],'title' => $post['page_title'],'page_url' => $post['page_url'],'meta_title' => $post['meta_title'],'meta_keyword' => $post['meta_keyword'],'meta_description' => $post['meta_description'],'content' => $post['content']))->where('id', '=', $id)
			->execute();
		return $result;
	}	
	//Check the menu already exists
	public function menu_name_exits($post,$id)
	{
		  $result =DB::select()->from(CMS)
			->where(CMS.'.menu_id','!=',$post['menu_name'])
			->where(CMS.'.id','=',$id)
			->execute()
			->as_array();
			
		  if($result)
		  {
		  	return 1;
		  }

	}
	
	public function getcompanymanagerlist($id)
	{
	
			$result = DB::select('id')->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
				->where(COMPANY.'.cid','=',$id)
				->where('user_type','=','M')
				->order_by('id','asc')
				->execute()
				->as_array();
				
				return count($result);
		
	}
	
	//selected manus 
	public function get_menus()
	{
		$result =DB::select()->from(MENU)
		->order_by('order_status','ASC')
		->execute()
		->as_array();

	  return $result;
		   
	}	

	public function get_companymanagerlist($id,$offset='',$val='')
	{
			$result = DB::select('id','status','name','company_name','country_name','state_name','city_name')->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
				->where(COMPANY.'.cid','=',$id)
				->where('user_type','=','M')
				->order_by('id','asc')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
				
				return $result;
		
	}	
	
	public function getcompanydriverlist($id)
	{
	
			$result = DB::select('id')->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
				->where(COMPANY.'.cid','=',$id)
				->where('user_type','=','D')
				->order_by('id','asc')
				->execute()
				->as_array();
				
				return count($result);
		
	}	

	public function get_companydriverlist($id,$offset='',$val='')
	{
			$result = DB::select('id','name','status','company_name','country_name','state_name','city_name')->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
				->where(COMPANY.'.cid','=',$id)
				->where('user_type','=','D')
				->order_by('id','asc')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
				
				return $result;
		
	}	
	
	public function getcompanytaxilist($id)
	{
	
			$result = DB::select('taxi_id')->from(TAXI)
				->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')
				->where(COMPANY.'.cid','=',$id)
				->order_by('taxi_id','asc')
				->execute()
				->as_array();
				
				return count($result);
		
	}	

	public function get_companytaxilist($id,$offset='',$val='')
	{
			$result = DB::select('taxi_status','taxi_id','taxi_no','company_name','country_name','state_name','city_name')->from(TAXI)
				->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')
				->where(COMPANY.'.cid','=',$id)
				->order_by('taxi_id','asc')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
				
				return $result;
		
	}
	
	/** getting driver rating given by users**/
	public function getdriverratinglist($id)
	{
		$result = DB::select(array(DB::expr("COUNT('passengers_log_id')"),'count'))->from(PASSENGERS_LOG)
				->join(PASSENGERS,'left')->on(PASSENGERS_LOG.'.passengers_id', '=', PASSENGERS.'.id')
				->where(PASSENGERS_LOG.'.driver_id','=',$id)
				->order_by('passengers_log_id','asc')
				->execute()
				->as_array();
				
		return !empty($result) ? $result[0]['count'] : 0;		
	}
	
	/** getting driver rating given by users**/
	public function get_driverratinglist($id,$offset='',$val='')
	{
		$result = DB::select('rating','comments','name','current_location','drop_location','no_passengers','pickup_time','waitingtime')
				->from(PASSENGERS_LOG)
				->join(PASSENGERS,'left')->on(PASSENGERS_LOG.'.passengers_id', '=', PASSENGERS.'.id')
				->where(PASSENGERS_LOG.'.driver_id','=',$id)
				->order_by('passengers_log_id','asc')
				->limit($val)->offset($offset)
				->execute()
				->as_array();				
			return $result;		
	}
	
	/** getting user rating given to drivers**/
	public function getuserratinglist($id)
	{
		$result = DB::select()->from(PASSENGERS_LOG)
				->join(PEOPLE,'left')->on(PASSENGERS_LOG.'.driver_id', '=', PEOPLE.'.id')
				->where(PASSENGERS_LOG.'.passengers_id','=',$id)
				->order_by('passengers_log_id','asc')
				->execute()
				->as_array();
				
			return count($result);
		
	}
	
	/** getting user rating given to drivers**/
	public function get_userratinglist($id,$offset='',$val='')
	{
		$result = DB::select()->from(PASSENGERS_LOG)
				->join(PEOPLE,'left')->on(PASSENGERS_LOG.'.driver_id', '=', PEOPLE.'.id')
				->where(PASSENGERS_LOG.'.passengers_id','=',$id)
				->order_by('passengers_log_id','asc')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
				
			return $result;
		
	}
	
	/** getting data for manager driver list **/
	public function getmanagerdriverlist($id)
	{
		$rs = DB::select()->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->where('user_type','=','M')
				->where('id','=',$id)
				->execute()
				->as_array();

			$company_id = $rs[0]['company_id'];
			$login_city = $rs[0]['login_city'];
			$login_country = $rs[0]['login_country'];
			$login_state = $rs[0]['login_state'];

		$result = DB::select()->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
				->where('user_type','=','D')
				->where('login_city','=',$login_city)
				->where('login_country','=',$login_country)
				->where('login_state','=',$login_state)
				->where('company_id','=',$company_id)
				->execute()
				->as_array();

			return count($result);
		
	}	
	
	/** getting data for manager driver list **/
	public function get_managerdriverlist($id,$offset='',$val='')
	{
		$rs = DB::select('company_id','login_city','login_country','login_state')->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->where('user_type','=','M')
				->where('id','=',$id)
				->execute()
				->as_array();

			$company_id = $rs[0]['company_id'];
			$login_city = $rs[0]['login_city'];
			$login_country = $rs[0]['login_country'];
			$login_state = $rs[0]['login_state'];

		$result = DB::select('cid','status','id','name','company_name','country_name','state_name','city_name')->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(PEOPLE.'.login_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(PEOPLE.'.login_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(PEOPLE.'.login_city', '=', CITY.'.city_id')
				->where('user_type','=','D')
				->where('login_city','=',$login_city)
				->where('login_country','=',$login_country)
				->where('login_state','=',$login_state)
				->where('company_id','=',$company_id)
				->order_by('id','asc')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
				
				return $result;
		
	}	
	
	/** getting data for manager taxi list **/
	public function getmanagertaxilist($id)
	{
		$rs = DB::select('cid','login_city','login_country','login_state')->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->where('user_type','=','M')
				->where('id','=',$id)
				->execute()
				->as_array();
		
			$company_id = $rs[0]['cid'];
			$login_city = $rs[0]['login_city'];
			$login_country = $rs[0]['login_country'];
			$login_state = $rs[0]['login_state'];
			
		$result = DB::select()->from(TAXI)
				->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')
				->where('taxi_country','=',$login_country)
				->where('taxi_state','=',$login_state)
				->where('taxi_city','=',$login_city)
				->where('cid','=',$company_id)
				->execute()
				->as_array();
				
				return count($result);
		
	}	
	
	/** getting data for manager taxi list **/
	public function get_managertaxilist($id,$offset='',$val='')
	{
		$rs = DB::select('company_id','login_city','login_country','login_state')->from(PEOPLE)
				->join(COMPANY, 'LEFT')->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->where('user_type','=','M')
				->where('id','=',$id)
				->execute()
				->as_array();

			$company_id = $rs[0]['company_id'];
			$login_city = $rs[0]['login_city'];
			$login_country = $rs[0]['login_country'];
			$login_state = $rs[0]['login_state'];
			
		$result = DB::select('cid','taxi_id','taxi_no','company_name','country_name','state_name','city_name','taxi_status')->from(TAXI)
				->join(COMPANY, 'LEFT')->on(TAXI.'.taxi_company', '=', COMPANY.'.cid')
				->join(COUNTRY, 'LEFT')->on(TAXI.'.taxi_country', '=', COUNTRY.'.country_id')
				->join(STATE, 'LEFT')->on(TAXI.'.taxi_state', '=', STATE.'.state_id')
				->join(CITY, 'LEFT')->on(TAXI.'.taxi_city', '=', CITY.'.city_id')
				->where('taxi_country','=',$login_country)
				->where('taxi_state','=',$login_state)

				->where('taxi_city','=',$login_city)
				->where('cid','=',$company_id)
				->order_by('taxi_id','asc')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
		return $result;		
	}

	public static function get_allcompany($status="")
	{		
		$result = DB::select('cid','company_name')->from(COMPANY);
		if($status != "") {
			$result->where('company_status','=',$status);
		}
		return $result->order_by('company_name','asc')
		->execute()
		->as_array();
	}

	public static function get_allcompany_tranaction()
	{
		
		$result = DB::select()->from(COMPANY)->order_by('company_name','asc')
			->execute()
			->as_array();

		  return $result;
	}
		
	public function rating_drivers($uid)
	{
		$query = "SELECT log.*,p.name as name FROM `".PASSENGERS_LOG."` as log Join `".PEOPLE."` as p ON log.driver_id=p.id WHERE log.driver_id=$uid";
			
		//echo $query;
		$rs = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return $rs;
	}
	
	public function count_rating_for_a_driver($uid)
	{
		$query = "SELECT count(`passengers_log_id`) as co_nt FROM `".PASSENGERS_LOG."` as log Join `".PEOPLE."` as p ON log.driver_id=p.id WHERE log.driver_id=$uid";
			
		//echo $query;
		$rs = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return $rs;
	}

	public function driver_unavailable($id='')
	{
		$query = "SELECT * FROM `".UNAVAILABILITY."` as unable Join `".PEOPLE."` as p ON unable.u_driverid=p.id Join `".COMPANY."` as c ON unable.u_companyid=c.cid WHERE unable.u_driverid=$id";
			
		//echo $query;
		$rs = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return $rs;	
	}

	public function validate_unavailabledriver($arr) 
	{
		return Validation::factory($arr)       

			->rule('reason', 'not_empty')
			->rule('startdate', 'not_empty')
			->rule('enddate', 'not_empty')
			->rule('enddate',  'Model_Manage::date_diff', array('value', $arr['startdate']))
			->rule('enddate', 'Model_Manage::checkunavailable', array(':value',$arr));
	}	

	public static function date_diff($enddate,$startdate)
	{
		if($startdate > $enddate)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	
	}
		
	public static function checkunavailable($enddate,$post)
	{
		$driver_id = $post['driver_id'];
		$reason = $post['reason'];
		$startdate = $post['startdate']; 
		$enddate = $post['enddate']; 
		$driver_where ='';
		$startdate_where = '';
		$date_where = '';
		$enddate_where = '';
		
		if($startdate && $enddate)
		{
				$date_where = " AND ( ( '$startdate' between u_startdate and  u_enddate ) or ( '$enddate' between u_startdate and  u_enddate) )";		
		}
		else
		{
			if($startdate)
			{
				$startdate_where = " AND '$startdate'  between u_startdate and  u_enddate ";	
			}
			if($enddate)
			{
				$enddate_where = " AND '$enddate'  between u_startdate  and  u_enddate ";	
			}
			
			$date_where = $startdate_where.$enddate_where;
		}

		

		
		$query = " select * from " . UNAVAILABILITY . " where 1=1  and u_driverid='$driver_id' $date_where order by u_startdate DESC ";


 		$result = Db::query(Database::SELECT, $query)
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

	public function add_unavailabledriver($post)
	{
			$createdby = $_SESSION['userid'];
			
			$driver_id = $post['driver_id'];
			
			$company_id = $this->get_companyid($driver_id);

			
			$result = DB::insert(UNAVAILABILITY, array('u_driverid','u_companyid','u_reason','u_startdate','u_enddate','u_createdby','u_status'))
					->values(array($post['driver_id'],$company_id,$post['reason'],$post['startdate'],$post['enddate'],$createdby,ACTIVE))
					->execute();
					
			return $result;		
					
	}

	public function get_companyid($id)
	{
				$result = DB::select('company_id')->from(PEOPLE)
				->where('id','=',$id)
				->execute()
				->as_array();
				if(count($result) > 0)
				{
					return $result[0]['company_id'];
				}
				else
				{
					return ''; 
				}	
		
	}

	public function check_peoplecompanyid($id)
	{
		$result = DB::select('company_id','user_type')->from(PEOPLE)
			->where('id','=',$id)
			->execute()
			->as_array();
		return !empty($result) ? $result : array();
	}
	
	public function check_taxicompanyid($id)
	{
				$result = DB::select('taxi_company','taxi_state','taxi_city','taxi_country')->from(TAXI)
				->where('taxi_id','=',$id)
				->execute()
				->as_array();
				
				if(count($result) > 0)
				{
					return $result;
				}
				else
				{
					return 0; 
				}	
		
	}

	public function check_companyid($id)
	{
				$result = DB::select('cid')->from(COMPANY)
				->where('userid','=',$id)
				->execute()
				->as_array();
				if(count($result) > 0)
				{
					return $result;
				}
				else
				{
					return 0; 
				}	
		
	}
		
	public function getunavailabledriverlist($driver_id='')
	{
		$query = "SELECT * FROM `".UNAVAILABILITY."` as unable Join `".PEOPLE."` as p ON unable.u_driverid=p.id  Join `".COMPANY."` as c ON unable.u_companyid=c.cid WHERE unable.u_driverid=$driver_id";
			
		$rs = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return count($rs);		
	}

	public function get_unavailabledriverlist($driver_id='',$offset='',$val='')
	{
		$query = "SELECT * FROM `".UNAVAILABILITY."` as unable Join `".PEOPLE."` as p ON unable.u_driverid=p.id  Join `".COMPANY."` as c ON unable.u_companyid=c.cid WHERE unable.u_driverid=$driver_id limit $val offset $offset";
			
		$rs = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return $rs;		
	}
	
	public function count_unavailability_list()
	{
		$query = "SELECT * FROM `".UNAVAILABILITY."` as unable Join `".PEOPLE."` as p ON unable.u_driverid=p.id Join `".COMPANY."` as c ON unable.u_companyid=c.cid";
			
		$rs = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return count($rs);			
	}

	public function unavailability_details($offset='',$val='')
	{
		$query = "SELECT * FROM `".UNAVAILABILITY."` as unable Join `".PEOPLE."` as p ON unable.u_driverid=p.id Join `".COMPANY."` as c ON unable.u_companyid=c.cid limit $val offset $offset";
			
		$rs = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return $rs;				
	}		

   	public function count_unavailabilitysearch_list($keyword = "", $status = "")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND u_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE '%$keyword%' escape '!' ) ";
					
		}


		$query = " SELECT * FROM `".UNAVAILABILITY."` as unable Join `".PEOPLE."` as p ON unable.u_driverid=p.id Join `".COMPANY."` as c ON unable.u_companyid=c.cid  where 1=1 $staus_where $name_where order by u_id ASC";

		$results = Db::query(Database::SELECT, $query)

		->execute()
		->as_array();

		return count($results);

   }

   	public function get_unavailabilitysearch_list($keyword = "", $status = "",$offset='',$val='')
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND u_status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE '%$keyword%' escape '!' ) ";
					
		}


		$query = " SELECT * FROM `".UNAVAILABILITY."` as unable Join `".PEOPLE."` as p ON unable.u_driverid=p.id Join `".COMPANY."` as c ON unable.u_companyid=c.cid  where 1=1 $staus_where $name_where order by u_id ASC limit $val offset $offset";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;

   }

	   public function count_transaction_list($company,$startdate,$enddate)
	   {
		    $condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";
		    if($company !="") { $condition .= "and pl.company_id =  '$company'"; }
		    if($startdate !="") { $condition .= "and pl.pickup_time >=  '$startdate' and pl.pickup_time <=  '$enddate' "; }
	   		$query = " SELECT * , pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition order by pl.passengers_log_id desc";
		//echo $query;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return count($results);
	   
	   }	
   	
   	   public function transaction_details($company,$startdate,$enddate,$offset='',$val='')
   	   {
		$totalfare = "select sum(fare) from `transacation`";
		$condition = "WHERE pl.travel_status = '1' and pl.driver_reply = 'A' ";
		if($company !="") { $condition .= "and pl.company_id =  '$company'"; }
		if($startdate !="") { $condition .= "and pl.pickup_time >=  '$startdate' and pl.pickup_time <=  '$enddate' "; }		    
		$query = " SELECT * ,(".$totalfare.") AS totalfare, pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id $condition order by pl.passengers_log_id desc limit $val offset $offset";
		//echo $query;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;   	   
		
   	   
   	   }
   	   
	public function active_availabilitytaxi_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================

	            //$arr_chk = " motor_id in ('" . implode("','",$activeids) . "') ";	
           
	            $result = DB::update(TAXI)->set(array('taxi_availability' => 'A'))->where('taxi_id', 'IN', $activeids)

			->execute();
  
			 return count($result);
	}
	
	public function block_availabilitytaxi_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       

            // $arr_chk = " motor_id in ('" . implode("','",$activeids) . "') ";	
            
       	            $result = DB::update(TAXI)->set(array('taxi_availability' => 'D'))->where('taxi_id', 'IN', $activeids)
			->execute();
	        	  
			 return count($result);
	}

	public function active_availabilitydriver_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
             
             $result = DB::update(PEOPLE)->set(array('availability_status' => 'A'))->where('id', 'IN', $activeids)
			->execute();
			
		        	  
			 return count($result);
	}
	
	public function block_availabilitydriver_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
             
                    $result = DB::update(PEOPLE)->set(array('availability_status' => 'D'))->where('id', 'IN', $activeids)
			->execute();
		        	  
			 return count($result);
	}
	
	// get menu list	
	public function count_menu_list()
	{
		$rs = DB::select()->from(MENU)
				->execute();
		return count($rs);
	}	
	
	public function menu_list()
	{
		$rs = DB::select()->from(MENU)
				->execute()
				->as_array();
				return $rs;
	}
		
	public function all_menu_list($offset, $val)
	{
		$rs = DB::select()->from(MENU)
				->limit($val)->offset($offset)
				->execute()
				->as_array();
				return $rs;
	} // end here
	
	//For deleting menu
	public function delete_menu($id) {
			$result = DB::delete(MENU)
				->where('menu_id', '=',$id)
				->execute();
				
			$result1 = DB::delete(CMS)
				->where('menu_id', '=',$id)
				->execute();
				
			return $result;
	}

	public function details_taxi_driver($id){
	
		$now = date('Y-m-d H:i:s');
		$query = "SELECT m.mapping_driverid as driverid FROM ".TAXIMAPPING." as m WHERE m.mapping_taxiid='".$id."' AND m.mapping_startdate<='".$now."' AND m.mapping_enddate>='".$now."'";
			
		//echo $query;
		$rs = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		return $rs;
	}
	
	// get mile list	
	public function count_mile_list()
	{
		$rs = DB::select()->from(MILES)
				->execute();
		return count($rs);
	}
			
	public function all_mile_list($offset='', $val='')
	{
		$rs = DB::select()->from(MILES)
				->order_by('mile_name','ASC')
				->limit($val)->offset($offset)
				->execute()
				->as_array();
				return $rs;
	} // end
	
	//Change the miles status  
	public function active_mile_request($activeids)
	{

	            $result = DB::update(MILES)->set(array('mile_status' => 'A'))->where('id', 'IN', $activeids)
			->execute();
  
			 return count($result);
	}
	
	public function block_mile_request($blockids)
	{
		
     	            $result = DB::update(MILES)->set(array('mile_status' => 'D'))->where('id', 'IN', $blockids)
			->execute();
	        	  
			 return count($result);
	}


	
	public function trash_mile_request($trashids)
	{
		$result = DB::update(MILES)->set(array('mile_status' => 'T'))->where('id', 'IN', $trashids)
			->execute();	
		
		return $result;	
				
	} // End 
	
	//For deleting mile
	public function delete_mile($id) {
			$result = DB::delete(MILES)
				->where('id', '=',$id)
				->execute();				
			return $result;
	}
	
	
	public function update_comments($passengers_log_id)
	{
	       
		$result = DB::update(PASSENGERS_LOG)->set(array('comments' => ''))->where('passengers_log_id', '=', $passengers_log_id)
			->execute();			             
		return $result;
	}
	
	
		public function count_unassign_searchtaxi_list($keyword = "")
	{
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
			$createdby_where = " AND taxi_country=$country_id AND taxi_state=$state_id AND taxi_city=$city_id AND taxi_company=$company_id ";
		}
		else if($usertype =='C')
		{
			$createdby_where = " AND taxi_company = $company_id ";
		}
		else
		{
			$createdby_where = "";		
		}
		
		
	
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		//$company_where= ($company) ? " AND cid = '$company'" : "";
		//$staus_where= ($status) ? " AND taxi_status = '$status'" : "";
		
		$company_where= "";
		$staus_where= "";	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (taxi_no LIKE  '%$keyword%' ";
			$name_where  .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or taxi_type LIKE '%$keyword%' escape '!' ) ";
       		 }
       		 
       		 
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

			$sql = "select * from ".TAXI." JOIN ".COMPANY." ON ".TAXI.".taxi_company = company.cid where ".TAXI.".taxi_status='A' and  ".TAXI.".taxi_availability='A'  and ".TAXI.".taxi_id NOT IN ($taxi_list) $name_where $createdby_where order by ".TAXI.".taxi_id asc";

			//$query = " select * from " . TAXI . " left join ".COUNTRY."  on  ".TAXI.".taxi_country =".COUNTRY.".country_id left join ".STATE." on ".TAXI.".taxi_state =".STATE.".state_id left join ".CITY." on ".TAXI.".taxi_city =".CITY.".city_id   left join ".COMPANY." on ".TAXI.".taxi_company = ".COMPANY.".cid  left join ".MOTORCOMPANY." on ".TAXI.".taxi_type =".MOTORCOMPANY.".motor_id left join ".MOTORMODEL." on ".TAXI.".taxi_model = ".MOTORMODEL.".model_id where 1=1 $company_where $staus_where $name_where $createdby_where order by taxi_id DESC";

	 		$result = Db::query(Database::SELECT, $sql)
			   			 ->execute()
						 ->as_array();

			 return count($result);
	}
	
	  	public function get_unassign_taxi_searchlist($keyword = "",$offset ="",$val ="")
	{
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
			$createdby_where = " AND taxi_country=$country_id AND taxi_state=$state_id AND taxi_city=$city_id AND taxi_company=$company_id ";
		}
		else if($usertype =='C')
		{
			$createdby_where = " AND taxi_company = $company_id ";
		}
		else
		{
			$createdby_where = "";		
		}
		
		
	
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		//$company_where= ($company) ? " AND cid = '$company'" : "";
		//$staus_where= ($status) ? " AND taxi_status = '$status'" : "";
	
		$company_where= "";
		$staus_where= "";
		$name_where="";	

		if($keyword){
			$name_where  = " AND (taxi_no LIKE  '%$keyword%' ";
			$name_where  .= " or company_name LIKE  '%$keyword%' ";
			$name_where .= " or taxi_type LIKE '%$keyword%' escape '!' ) ";
       		 }


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

			$sql = "select * from ".TAXI." JOIN ".COMPANY." ON ".TAXI.".taxi_company = company.cid where ".TAXI.".taxi_status='A' and  ".TAXI.".taxi_availability='A'  and ".TAXI.".taxi_id NOT IN ($taxi_list) $name_where $createdby_where order by ".TAXI.".taxi_id asc limit $offset,$val";

	 		$result = Db::query(Database::SELECT, $sql)
			   			 ->execute()
						 ->as_array();
			 
		  		$details = array();
				foreach($result as $key => $res)		
				{
					$details[$key]['created_by'] = $this->userNamebyId($res['taxi_createdby']);
					$details[$key]['taxi_id'] = $res['taxi_id'];
					$details[$key]['taxi_availability'] = $res['taxi_availability'];
					$details[$key]['taxi_status'] = $res['taxi_status'];
					$details[$key]['taxi_no'] = $res['taxi_no'];
					$details[$key]['company_name'] = $res['company_name'];	
					
					$details[$key]['taxi_capacity'] = $res['taxi_capacity'];
					$details[$key]['taxi_fare_km'] = $res['taxi_fare_km'];
					$details[$key]['cid'] = $res['userid'];
	
				}
			 return $details;
	}
	
	public function free_availabletaxi_list()
	{
		$cuurentdate = date('Y-m-d H:i:s');
		$enddate = date('Y-m-d').' 23:59:59';

		$query_where = " AND ( ( '$cuurentdate' between taximapping.mapping_startdate and  taximapping.mapping_enddate ) or ( '$enddate' between taximapping.mapping_startdate and  taximapping.mapping_enddate) )";	

		$sql ="SELECT people.id,taxi.taxi_id  
			FROM ".TAXI." as taxi
			JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid
			JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid
			JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid
			WHERE people.status = 'A' 
			AND taximapping.mapping_status = 'A' $query_where limit 0,10";

		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}
	
	public function count_freetaxi_list($cid=0)
	{
	
		$currentdate = date('Y-m-d H:i:s');
		$enddate = date('Y-m-d').' 23:59:59';
			
		$query_where = " AND ( ( '$currentdate' between mapping_startdate and  mapping_enddate ) or ( '$enddate' between mapping_startdate and  mapping_enddate) )";	
		
		$companyCond = "";
		if(!empty($cid)) {
			$companyCond = " and ".TAXI.".taxi_company = '$cid'";
		}			

		$query = " select * from " . TAXIMAPPING . " left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id left join ".DRIVER." on ".TAXIMAPPING.".mapping_driverid =".DRIVER.".driver_id where ".TAXIMAPPING.".mapping_status = 'A'  and ".DRIVER.".status='F' and ".COMPANY.".company_status='A' and ".COUNTRY.".country_status='A' and ".STATE.".state_status='A' and ".CITY.".city_status='A' and ".TAXI.".taxi_status='A' and ".TAXI.".taxi_availability='A' and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and people.user_type='D' $companyCond $query_where order by mapping_startdate ASC ";



	
		$results = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
			return count($results);
	}
	
	public function all_freetaxi_list($offset,$val,$cid=0)
	{
	
		$currentdate = date('Y-m-d H:i:s');
		$enddate = date('Y-m-d').' 23:59:59';
			
		$query_where = " AND ( ( '$currentdate' between mapping_startdate and  mapping_enddate ) or ( '$enddate' between mapping_startdate and  mapping_enddate) )";	
						
		$companyCond = "";
		if(!empty($cid)) {
			$companyCond = " and ".TAXI.".taxi_company = '$cid'";
		}
		
		$query = " select * from " . TAXIMAPPING . " left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id left join ".DRIVER." on ".TAXIMAPPING.".mapping_driverid =".DRIVER.".driver_id where ".TAXIMAPPING.".mapping_status = 'A'  and ".DRIVER.".status='F' and ".COMPANY.".company_status='A' and ".COUNTRY.".country_status='A' and ".STATE.".state_status='A' and ".CITY.".city_status='A' and ".TAXI.".taxi_status='A' and ".TAXI.".taxi_availability='A' and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and people.user_type='D' $companyCond $query_where order by mapping_startdate ASC limit $offset,$val ";

		$results = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
			return $results;
	}
	
	
	   	public function count_freetaxisearch_list($keyword = "")
	{

		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];	
	   	
	   	if($usertype =='M')
	   	{
			$user_created_where = " AND mapping_companyid = $company_id ";
		}
		else if($usertype =='C')
	   	{
			$user_created_where = " AND mapping_companyid = $company_id ";
		}
		else
		{
			$user_created_where = "";		
		}
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);


		//$company_where= ($company) ? " AND cid = '$company'" : "";
		//$status_where= ($status) ? " AND mapping_status = '$status'" : "";
		
		$company_where= "";
		$status_where=  "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%' ) ";
       		 }

			
		//$query = " select * from " . TAXIMAPPING . " left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".TAXI." on ".TAXIMAPPING.".mapping_taxiid = ".TAXI.".taxi_id  left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id where 1=1 $company_where $user_created_where $status_where $name_where order by mapping_startdate DESC ";
		
		$currentdate = date('Y-m-d H:i:s');
		$enddate = date('Y-m-d').' 23:59:59';
			
		$query_where = " AND ( ( '$currentdate' between mapping_startdate and  mapping_enddate ) or ( '$enddate' between mapping_startdate and  mapping_enddate) )";	
						

		$query = " select * from " . TAXIMAPPING . " left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id left join ".DRIVER." on ".TAXIMAPPING.".mapping_driverid =".DRIVER.".driver_id where ".TAXIMAPPING.".mapping_status = 'A'  and ".DRIVER.".status='F' and ".COMPANY.".company_status='A' and ".COUNTRY.".country_status='A' and ".STATE.".state_status='A' and ".CITY.".city_status='A' and ".TAXI.".taxi_status='A' and ".TAXI.".taxi_availability='A' and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and people.user_type='D' $name_where $query_where $user_created_where order by mapping_startdate ASC ";




	 		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();

			 return count($result);

   }
   
   
   	public function get_all_freetaxi_searchlist($keyword = "", $offset ="",$val ="")
	{

		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];	
	   	
	   	if($usertype =='M')
	   	{
			$user_created_where = " AND mapping_companyid = $company_id ";
		}
		else if($usertype =='C')
	   	{
			$user_created_where = " AND mapping_companyid = $company_id ";
		}
		else
		{
			$user_created_where = "";		
		}
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		//$company_where= ($company) ? " AND cid = '$company'" : "";
		//$status_where= ($status) ? " AND mapping_status = '$status'" : "";
		$company_where= "";
		$status_where= "";
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%' ) ";
       		 }

			
		//$query = " select * from " . TAXIMAPPING . " left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".TAXI." on ".TAXIMAPPING.".mapping_taxiid = ".TAXI.".taxi_id  left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id where 1=1 $company_where $user_created_where $status_where $name_where order by mapping_startdate DESC limit $val offset  $offset ";


		$currentdate = date('Y-m-d H:i:s');
		$enddate = date('Y-m-d').' 23:59:59';
			
		$query_where = " AND ( ( '$currentdate' between mapping_startdate and  mapping_enddate ) or ( '$enddate' between mapping_startdate and  mapping_enddate) )";	
						

		$query = " select * from " . TAXIMAPPING . " left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id left join ".DRIVER." on ".TAXIMAPPING.".mapping_driverid =".DRIVER.".driver_id where ".TAXIMAPPING.".mapping_status = 'A'  and ".DRIVER.".status='F' and ".COMPANY.".company_status='A' and ".COUNTRY.".country_status='A' and ".STATE.".state_status='A' and ".CITY.".city_status='A' and ".TAXI.".taxi_status='A' and ".TAXI.".taxi_availability='A' and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and people.user_type='D' $name_where $query_where $user_created_where order by mapping_startdate ASC limit $offset,$val ";



	 		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				$details = array();
				foreach($result as $key => $res)		
				{
					$details[$key]['created_by'] = $this->userNamebyId($res['mapping_createdby']);
					$details[$key]['mapping_id'] = $res['mapping_id'];
					$details[$key]['mapping_status'] = $res['mapping_status'];
					$details[$key]['name'] = $res['name'];
					$details[$key]['company_name'] = $res['company_name'];	
					$details[$key]['taxi_no'] = $res['taxi_no'];	
					$details[$key]['country_name'] = $res['country_name'];
					$details[$key]['state_name'] = $res['state_name'];
					$details[$key]['city_name'] = $res['city_name'];
					$details[$key]['mapping_startdate'] = $res['mapping_startdate'];
					$details[$key]['phone'] = $res['phone'];
					$details[$key]['mapping_enddate'] = $res['mapping_enddate'];
					$details[$key]['id'] = $res['id'];
					$details[$key]['cid'] = $res['userid'];
					$details[$key]['taxi_id'] = $res['taxi_id'];
	
				}
				
			 return $details;

   }
   
   
   public function free_driver_list_count($cid=0)
	{
		$usertype = $_SESSION['user_type'];	
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
		$assigned_driver = $this->free_availabletaxi_list();

		$driver_list = '';
		
		if(count($assigned_driver) > 0)
		{
			foreach($assigned_driver as $key => $value)
			{
				$driver_list .= "'".$value['id']."',";
			}
			$driver_list = rtrim($driver_list,',');
		}
		
		$companyCond = "";
		if(!empty($cid)) {
			$companyCond = " and ".PEOPLE.".company_id = '$cid'";
		}
		
		if($usertype == 'M') {
			$companyCond .= " and ".PEOPLE.".login_country='$country_id' and ".PEOPLE.".login_state='$state_id' and ".PEOPLE.".login_city='$city_id'";
		}

		$sql = "select * from ".PEOPLE." JOIN ".COMPANY." ON ".PEOPLE.".company_id = company.cid where ".PEOPLE.".user_type='D'  and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and ".PEOPLE.".id NOT IN ($driver_list) $companyCond order by ".PEOPLE.".id asc ";

		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return count($result);
					
	}
	
	
	public function all_free_driver_list($offset, $val, $cid=0)
	{
		$usertype = $_SESSION['user_type'];	
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
		$assigned_driver = $this->free_availabletaxi_list();

		$driver_list = '';
		
		if(count($assigned_driver) > 0)
		{
			foreach($assigned_driver as $key => $value)
			{
				$driver_list .= "'".$value['id']."',";
			}
			$driver_list = rtrim($driver_list,',');
		}
		
		$companyCond = "";
		if(!empty($cid)) {
			$companyCond = " and ".PEOPLE.".company_id = '$cid'";
		}
		
		if($usertype == 'M') {
			$companyCond .= " and ".PEOPLE.".login_country='$country_id' and ".PEOPLE.".login_state='$state_id' and ".PEOPLE.".login_city='$city_id'";
		}

		$sql = "select * from ".PEOPLE." JOIN ".COMPANY." ON ".PEOPLE.".company_id = company.cid where ".PEOPLE.".user_type='D'  and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and ".PEOPLE.".id NOT IN ($driver_list) $companyCond order by ".PEOPLE.".id asc limit $offset,$val";

		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();


		$details = array();
		foreach($result as $key => $res)		
		{
			$details[$key]['created_by'] = $this->userNamebyId($res['user_createdby']);
			$details[$key]['name'] = $res['name'];
			$details[$key]['username'] = $res['username'];
			$details[$key]['email'] = $res['email'];
			$details[$key]['address'] = $res['address'];	
			$details[$key]['availability_status'] = $res['availability_status'];			
			$details[$key]['company_name'] = $res['company_name'];
			$details[$key]['status'] = $res['status'];
			$details[$key]['id'] = $res['id'];
			$details[$key]['driver_license_id'] = $res['driver_license_id'];
			$details[$key]['phone'] = $res['phone'];
			
			$details[$key]['cid'] = $res['userid'];
		}
						
				return $details;
		
		
		
	}
	
	
	/*public function free_driver_list_count()
	{
	
		$assigned_driver = $this->free_availabletaxi_list();

		$driver_list = '';
		
		if(count($assigned_driver) > 0)
		{
			foreach($assigned_driver as $key => $value)
			{
				$driver_list .= "'".$value['id']."',";
			}
			$driver_list = rtrim($driver_list,',');
		}

		$sql = "select * from ".PEOPLE." JOIN ".COMPANY." ON ".PEOPLE.".company_id = company.cid where ".PEOPLE.".user_type='D'  and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and ".PEOPLE.".id NOT IN ($driver_list) order by ".PEOPLE.".id asc ";

		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return count($result);
					
	}*/
	
	
	public function count_unassign_searchdriver_list($keyword = "")
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
			$user_created_where = " AND login_country = $country_id AND login_state = $state_id AND login_city = $city_id AND company_id = $company_id ";
		}
		else if($usertype =='C')
		{
			$user_created_where = " AND company_id = $company_id ";
		}
		else
		{
			$user_created_where = "";		
		}
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		//$company_where= ($company) ? " AND cid = '$company'" : "";	
		//$staus_where= ($status) ? " AND status = '$status'" : "";
	
		$company_where= "";	
		$staus_where= "";
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%' ) ";
       		 }

			
			//$query = " select * from " . PEOPLE . " left join ".COMPANY." on ".PEOPLE.".company_id = ".COMPANY.".cid  left join ".COUNTRY." on ".PEOPLE.".login_country = ".COUNTRY.".country_id   left join ".STATE." on ".PEOPLE.".login_state = ".STATE.".state_id    left join ".CITY." on ".PEOPLE.".login_city = ".CITY.".city_id where ".PEOPLE.".user_type = 'D' $company_where $staus_where $name_where $user_created_where order by created_date DESC";
			$assigned_driver = $this->free_availabletaxi_list();

		$driver_list = '';
		
		if(count($assigned_driver) > 0)
		{
			foreach($assigned_driver as $key => $value)
			{
				$driver_list .= "'".$value['id']."',";
			}
			$driver_list = rtrim($driver_list,',');
		}

		$query = "select * from ".PEOPLE." JOIN ".COMPANY." ON ".PEOPLE.".company_id = company.cid where ".PEOPLE.".user_type='D'  and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and ".PEOPLE.".id NOT IN ($driver_list) $name_where $user_created_where order by ".PEOPLE.".id asc ";


	 		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
						 
		return count($result);
	}
	
	
	public function get_unassign_driver_searchlist($keyword = "", $offset ="",$val ="")
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
			$user_created_where = " AND login_country = $country_id AND login_state = $state_id AND login_city = $city_id AND company_id = $company_id ";
		}
		else if($usertype =='C')
		{
			$user_created_where = " AND company_id = $company_id ";
		}
		else
		{
			$user_created_where = "";		
		}
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		/*$company_where= ($company) ? " AND cid = '$company'" : "";	
		$staus_where= ($status) ? " AND status = '$status'" : "";*/
		
		$company_where= "";	
		$staus_where= "";
	
		$name_where="";	

		if($keyword){
			$name_where  = " AND (name LIKE  '%$keyword%' ";
			$name_where .= " or company_name LIKE  '%$keyword%') ";
       		 }

			
			//$query = " select * from " . PEOPLE . " left join ".COMPANY." on ".PEOPLE.".company_id = ".COMPANY.".cid  left join ".COUNTRY." on ".PEOPLE.".login_country = ".COUNTRY.".country_id   left join ".STATE." on ".PEOPLE.".login_state = ".STATE.".state_id    left join ".CITY." on ".PEOPLE.".login_city = ".CITY.".city_id  where ".PEOPLE.".user_type = 'D' $company_where $staus_where $name_where $user_created_where order by created_date DESC limit $val offset  $offset";
			
			$assigned_driver = $this->free_availabletaxi_list();

		$driver_list = '';
		
		if(count($assigned_driver) > 0)
		{
			foreach($assigned_driver as $key => $value)
			{
				$driver_list .= "'".$value['id']."',";
			}
			$driver_list = rtrim($driver_list,',');
		}

$query = "select * from ".PEOPLE." JOIN ".COMPANY." ON ".PEOPLE.".company_id = company.cid where ".PEOPLE.".user_type='D'  and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and ".PEOPLE.".id NOT IN ($driver_list) $name_where $user_created_where order by ".PEOPLE.".id asc limit $val offset  $offset ";


	 		$result = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
						 
			$details = array();
			foreach($result as $key => $res)		
			{
				$details[$key]['created_by'] = $this->userNamebyId($res['user_createdby']);
				$details[$key]['name'] = $res['name'];
				$details[$key]['username'] = $res['username'];
				$details[$key]['email'] = $res['email'];
				$details[$key]['address'] = $res['address'];
				$details[$key]['company_name'] = $res['company_name'];	
				$details[$key]['availability_status'] = $res['availability_status'];
				$details[$key]['status'] = $res['status'];
				$details[$key]['id'] = $res['id'];
				$details[$key]['driver_license_id'] = $res['driver_license_id'];
				$details[$key]['phone'] = $res['phone'];				
				$details[$key]['cid'] = $res['userid'];
				
			}
						
				return $details;

	}

	public function current_package_details($cid)
	{
		
		$query = "SELECT people.id, package_id, upgrade_packageid, check_package_type, upgrade_expirydate, upgrade_no_taxi as total_taxi,upgrade_no_driver as total_driver, package_name, package_type, upgrade_no_taxi as total_taxi, upgrade_no_driver as total_driver,company_domain,company_currency FROM ".PEOPLE." left join ".COMPANYINFO." on people.company_id = companyinfo.company_cid left join ".PACKAGE_REPORT." on people.company_id = package_report.upgrade_companyid left join ".PACKAGE." on package_report.upgrade_packageid = package.package_id  WHERE people.user_type='C' and people.company_id ='$cid' and ( check_package_type = 'T' or upgrade_expirydate >=now() ) ";
		$result = Db::query(Database::SELECT, $query)
			 ->execute()
			 ->as_array();
		
		//echo '<pre>';print_r($result);exit;
		return !empty($result) ? $result : array();		
		
		/*$query = "SELECT people.id ,(select upgrade_packageid from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as upgrade_packageid,(select check_package_type from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as check_package_type,(select upgrade_expirydate from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as upgrade_expirydate FROM people WHERE user_type='C' and company_id ='$cid' group by people.id Having ( check_package_type = 'T' or upgrade_expirydate >=now() )";
		
		$result = Db::query(Database::SELECT, $query)
			 ->execute()
			 ->as_array();		
		
		if(count($result) > 0)
		{
			$package_query = "select *,(select upgrade_expirydate from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as upgrade_expirydate,(select upgrade_no_taxi from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as total_taxi,(select upgrade_no_driver from package_report where package_report.upgrade_companyid = '$cid' order by upgrade_id desc limit 0,1 ) as total_driver from package where package_id = ".$result[0]['upgrade_packageid'];

			$package_result = Db::query(Database::SELECT, $package_query)
				 ->execute()
				 ->as_array();		
			//echo '<pre>';print_r($package_result);exit;
			return $package_result;

		}	
		else
		{
			return $array;
		}*/
	}	
	
	public function mute_driver_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       

             //$arr_chk = " userid in ('" . implode("','",$activeids) . "') ";	
             
                    $result = DB::update(PEOPLE)->set(array('status' => 'M'))->where('id', 'IN', $activeids)
			->execute();
		        	  
			 return count($result);
	}
	
	public function company_info($cid)
	{
		$rs = DB::select('company_domain','company_currency')->from(COMPANYINFO)->where('company_cid','=',$cid)
				->execute()->as_array();
		return $rs;
	}	
	
	/*********************************************************************************************/
	//Function used to get all driver logs with transactions
	public function get_driver_completed_transaction($id,$msg_status,$driver_reply=null,$travel_status=null,$start=null,$limit=null,$current_date,$fromdate,$todate)
	{
		if($current_date == 1)
		{
			$start_time = date('Y-m-d').' 00:00:01';
			$end_time = date('Y-m-d').' 23:59:59';
		}
		else
		{
			$start_time = $fromdate;
			$end_time = $todate;		
		}
		$result = DB::select(PASSENGERS_LOG.'.passengers_log_id',PASSENGERS_LOG.'.passengers_id',PASSENGERS_LOG.'.driver_id',PASSENGERS_LOG.'.taxi_id',PASSENGERS_LOG.'.company_id',PASSENGERS_LOG.'.current_location',PASSENGERS_LOG.'.pickup_latitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.drop_location',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.drop_latitude',PASSENGERS_LOG.'.drop_longitude',PASSENGERS_LOG.'.distance',PASSENGERS_LOG.'.approx_duration',PASSENGERS_LOG.'.approx_fare',PASSENGERS_LOG.'.pickup_time',PASSENGERS_LOG.'.travel_status',PASSENGERS_LOG.'.driver_reply',PASSENGERS_LOG.'.driver_comments',PASSENGERS_LOG.'.fixedprice',PASSENGERS_LOG.'.company_tax',PASSENGERS_LOG.'.faretype',PASSENGERS_LOG.'.bookingtype',PASSENGERS_LOG.'.luggage',PASSENGERS_LOG.'.bookby',PASSENGERS_LOG.'.operator_id',TRANS.'.distance',TRANS.'.actual_distance',TRANS.'.fare',TRANS.'.remarks',TRANS.'.payment_type',TRANS.'.amt',TRANS.'.distance_unit',TRANS.'.payment_status',array(TRANS.'.company_tax','Taxamt'),PASSENGERS.'.name')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->join(TRANS,'LEFT')->on(PASSENGERS_LOG.'.passengers_log_id','=',TRANS.'.passengers_log_id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
					->limit($start)->offset($limit)
					->order_by(PASSENGERS_LOG.'.passengers_log_id', 'desc')					
					->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
					->where(PASSENGERS_LOG.'.pickup_time','>=',$start_time)
					->where(PASSENGERS_LOG.'.pickup_time','<=',$end_time)
					->as_object()		
					->execute();
					//print_r($result);									 
		return $result;	
	}	
	/*********************************************************************************************/
	//Function used to Passenegr completed logs with transactions
	public function get_passenger_completed_transaction($id,$msg_status,$driver_reply=null,$travel_status=null,$start=null,$limit=null,$current_date,$fromdate,$todate)
	{
		if($current_date == 1)
		{
			$start_time = date('Y-m-d').' 00:00:01';
			$end_time = date('Y-m-d').' 23:59:59';
		}
		else
		{
			$start_time = $fromdate;
			$end_time = $todate;		
		}
		$result = DB::select(PASSENGERS_LOG.'.passengers_log_id',PASSENGERS_LOG.'.passengers_id',PASSENGERS_LOG.'.driver_id',PASSENGERS_LOG.'.taxi_id',PASSENGERS_LOG.'.company_id',PASSENGERS_LOG.'.current_location',PASSENGERS_LOG.'.pickup_latitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.drop_location',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.drop_latitude',PASSENGERS_LOG.'.drop_longitude',PASSENGERS_LOG.'.approx_distance',PASSENGERS_LOG.'.approx_duration',PASSENGERS_LOG.'.approx_fare',PASSENGERS_LOG.'.pickup_time',PASSENGERS_LOG.'.travel_status',PASSENGERS_LOG.'.driver_reply',PASSENGERS_LOG.'.driver_comments',PASSENGERS_LOG.'.fixedprice',PASSENGERS_LOG.'.company_tax',PASSENGERS_LOG.'.faretype',PASSENGERS_LOG.'.bookingtype',PASSENGERS_LOG.'.luggage',PASSENGERS_LOG.'.bookby',PASSENGERS_LOG.'.operator_id',TRANS.'.distance',TRANS.'.actual_distance',TRANS.'.fare',TRANS.'.remarks',TRANS.'.payment_type',TRANS.'.amt',TRANS.'.distance',TRANS.'.distance_unit',TRANS.'.payment_status',array(TRANS.'.company_tax','Taxamt'),PASSENGERS.'.name')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->join(TRANS,'LEFT')->on(PASSENGERS_LOG.'.passengers_log_id','=',TRANS.'.passengers_log_id')
					->where(PASSENGERS_LOG.'.passengers_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
					->limit($start)->offset($limit)
					->order_by(PASSENGERS_LOG.'.passengers_log_id', 'desc')					
					->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
					->where(PASSENGERS_LOG.'.pickup_time','>=',$start_time)
					->where(PASSENGERS_LOG.'.pickup_time','<=',$end_time)
					->as_object()		
					->execute();
		//echo '<pre>';print_r($result);exit;
		return $result;	
	}	
   	   
   	/*** Common Function for generating PDF *************/
   	/* public function generate_pdf($html,$filename)
   	{					
		require_once(APPPATH.'vendor/pdf/config/lang/eng.php');
            require_once(APPPATH.'vendor/pdf/tcpdf.php');

	           // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);            
			
            // set document information
            $pdf->SetCreator(PDF_CREATOR);

            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			//to remove top border in the document
			$pdf->setPrintHeader(false);
            //set margins
            //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            //set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            //set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            //set some language-dependent strings
            $pdf->setLanguageArray($l);

            // ---------------------------------------------------------
            // set font
            $pdf->SetFont('helvetica', '', 10);
			$pdf->AddPage();

			$pdf->SetFont('helvetica', '', 8);

			$pdf->writeHTML($html, true, false, false, false, ''); 

           // add a page
            // output the HTML content
            // reset pointer to the last page
            //Close and output PDF document
            ob_end_clean();
            $pdf->Output($filename.'.pdf', 'D');           
            exit;
	} */
	public function generate_pdf($html,$filename)
   	{
			require_once(APPPATH.'vendor/pdf/config/lang/eng.php');
            require_once(APPPATH.'vendor/pdf/tcpdf.php');
            
	           // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);

            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            //to remove top border in the document
			$pdf->setPrintHeader(false);
            //set margins
            //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            //set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            //set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            //set some language-dependent strings
            $pdf->setLanguageArray($l);

            // ---------------------------------------------------------
            // set font
            $pdf->SetFont('ufontscom_aealarabiya', '', 9);
			$pdf->AddPage();

			$pdf->SetFont('ufontscom_aealarabiya', '', 9);
			//$pdf->writeHTML($html, true, false, false, false, '');
			//$html = '<span color="#0000ff">This is Arabic "العربية" Example With TCPDF.</span>';

			$pdf->WriteHTML($html, true, 0, true, 0);

           // add a page
            // output the HTML content
            // reset pointer to the last page
            //Close and output PDF document
            ob_end_clean();
            $pdf->Output($filename.'.pdf', 'D');
            exit;
	}
  	/*** Common Function for Send PDF *************/
   	public function send_pdf($html,$driver_name,$driver_email,$filepath)
   	{					
			require_once(APPPATH.'vendor/pdf/config/lang/eng.php');
            require_once(APPPATH.'vendor/pdf/tcpdf.php');

	           // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);            
			
            // set document information
            $pdf->SetCreator(PDF_CREATOR);

            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            //set margins
            //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            //set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            //set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            //set some language-dependent strings
            $pdf->setLanguageArray($l);

            // ---------------------------------------------------------

            // set font
            $pdf->SetFont('helvetica', '', 10);
			$pdf->AddPage();

			$pdf->SetFont('helvetica', '', 8);

			$pdf->writeHTML($html, true, false, false, false, '');
						
           // add a page
            // output the HTML content
            // reset pointer to the last page
            //Close and output PDF document
            ob_end_clean();
            $pdf->Output($filepath.'.pdf', 'F');          
            if(file_exists($filepath.'.pdf'))
            {
				return 1;
			}
			else
			{
				return 0;
			}

	}	
	
	public function model_faredetails($uid)
	{
		$company_id = $_SESSION['company_id'];	
		$query = "select * from ".COMPANY_MODEL_FARE." where ".COMPANY_MODEL_FARE.".company_cid = '$company_id' and ".COMPANY_MODEL_FARE.".model_id= '$uid' ORDER BY `company_model_fare_id` ASC";
		
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	
	public function count_faq_list()
	{
			$result = DB::select('faq_id')->from(PASSENGERS_FAQ)->where('status','!=','N')->order_by('faq_id','ASC')->execute()->as_array();
		 return count($result);
	}
	
	public function all_faq_list($offset, $val)
	{
		$result =DB::select('faq_id','status','faq_title','faq_details')->from(PASSENGERS_FAQ)->where('status','!=','N')->order_by('faq_id','ASC')->limit($val)->offset($offset)->execute()->as_array();
		return $result;
		   
	}
	
	public function block_faq_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================
	       

            // $arr_chk = " motor_id in ('" . implode("','",$activeids) . "') ";	
            
       	            $result = DB::update(PASSENGERS_FAQ)->set(array('status' => 'D'))->where('faq_id', 'IN', $activeids)
			->execute();
	        	  
			 return count($result);
	}
	
	public function active_faq_request($activeids)
	{
		
		    //check whether id is exist in checkbox or single active request
		    //==================================================================

	            //$arr_chk = " motor_id in ('" . implode("','",$activeids) . "') ";	
           
	            $result = DB::update(PASSENGERS_FAQ)->set(array('status' => 'A'))->where('faq_id', 'IN', $activeids)
			->execute();
  
			 return count($result);
	}
	
	public function trash_faq_request($activeids)
	{
		$result = DB::update(PASSENGERS_FAQ)->set(array('status' => 'T'))->where('faq_id', 'IN', $activeids)
			->execute();		
		
			return $result;
	}
	
	public function count_searchfaq_list($keyword = "", $status = "")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (faq_title LIKE  '%$keyword%'"; 
		$name_where .= " or faq_title LIKE  '%$keyword%' )";

		}


			$query = " select * from " . PASSENGERS_FAQ . "  where 1=1 $staus_where $name_where order by faq_title ASC";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return count($results);
	}
	
	public function get_all_faq_searchlist($keyword = "", $status = "",$offset ="",$val ="")
	{
	
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		$staus_where= ($status) ? " AND status = '$status'" : "";

		//search result export
		//=====================
		$name_where="";	

		if($keyword){
		$name_where  = " AND (faq_title LIKE  '%$keyword%'"; 
		$name_where .= " or faq_title LIKE  '%$keyword%' )";

		}


			$query = " select * from " . PASSENGERS_FAQ . "  where 1=1 $staus_where $name_where order by faq_title ASC  limit $val offset  $offset";

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;

	}

	/************* Dashboard All Driver status***************/
	public function all_driver_map_list()
	{
		
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		$company_id = $_SESSION['company_id'];	
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
		if($usertype  == 'C')
		{
			$result = DB::select("*",array(DRIVER.'.status','driver_status'))->from(PEOPLE)
					->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
					->where('user_type','=','D')
					->where(PEOPLE.'.status','=','A')
					//->where(PEOPLE.'.login_status','=','S')
					->where('company_id','=',$company_id)
					//->order_by('created_date','desc')->limit($val)->offset($offset)
					->execute()
					->as_array();
					return $result;
		}else if($usertype  == 'M')
		{
					$result = DB::select("*",array(DRIVER.'.status','driver_status'))->from(PEOPLE)
					->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
					->where('user_type','=','D')
					->where(PEOPLE.'.status','=','A')
					//->where(PEOPLE.'.login_status','=','S')
					->where('company_id','=',$company_id)
					//->where(PEOPLE.'.user_createdby','=',$user_createdby)
					//->order_by('created_date','desc')->limit($val)->offset($offset)
					->execute()
					->as_array();
					return $result;
		}
		else 
		{
					$result = DB::select("*",array(DRIVER.'.status','driver_status'))->from(PEOPLE)
					->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
					->where('user_type','=','D')
					->where(PEOPLE.'.status','=','A')
					//->where(PEOPLE.'.login_status','=','S')
					//->order_by('created_date','desc')->limit($val)->offset($offset)
					->execute()
					->as_array();
					return $result;
		}

	}

	public function all_driver_map_list_company($company)
	{
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
			$result = DB::select("*",array(DRIVER.'.status','driver_status'))->from(PEOPLE)
					->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
					->where('user_type','=','D')
					->where(PEOPLE.'.status','=','A')
					//->where(PEOPLE.'.login_status','=','S')
					->where('company_id','=',$company_id)
					//->order_by('created_date','desc')->limit($val)->offset($offset)
					->execute()
					->as_array();
					return $result;
		}else if($usertype  == 'M')
		{
					$result = DB::select("*",array(DRIVER.'.status','driver_status'))->from(PEOPLE)
					->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
					->where('user_type','=','D')
					->where(PEOPLE.'.status','=','A')
					//->where(PEOPLE.'.login_status','=','S')
					->where('company_id','=',$company_id)
					//->where(PEOPLE.'.user_createdby','=',$user_createdby)
					//->order_by('created_date','desc')->limit($val)->offset($offset)
					->execute()
					->as_array();
					return $result;
		}
		else 
		{
				$query = "SELECT *,D.status AS driver_status FROM ".PEOPLE." AS PP
					 JOIN ".DRIVER." AS D ON PP.`id` = D.`driver_id` 
					 WHERE PP.user_type =  'D'
					 AND PP.status =  'A'
					 $company_cond";

					//echo $query;exit;
					$result = Db::query(Database::SELECT, $query)
						 ->execute()
						 ->as_array();
				return $result;
					
					/*$result = DB::select("*",array(DRIVER.'.status','driver_status'))->from(PEOPLE)
					->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
					->where('user_type','=','D')
					->where(PEOPLE.'.status','=','A')
					//->where(PEOPLE.'.login_status','=','S')
					//->order_by('created_date','desc')->limit($val)->offset($offset)
					->execute()
					->as_array();
					return $result;*/
		}

	}

	
	public function getpromocode()
	{
		$promo_code="";
		//$promocode_query = "select concat(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1)) as promocode from passengers_promo Having NOT EXISTS (select promocode from passengers_promo having promocode=promocode) limit 1";
		$promocode_query = "select concat(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1)) as promocode from passengers_promo Having NOT EXISTS (select promocode from passengers_promo having promocode=promocode) limit 1";
		$promocode_result = Db::query(Database::SELECT, $promocode_query)->execute()->as_array();
		if(count($promocode_result) > 0)
		{
			$promo_code = $promocode_result[0]['promocode'];
		}
		else
		{
			//$promocode_query = "select concat(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand()*36+1, 1)) as promocode";
			$promocode_query = "select concat(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1),substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand()*36+1, 1)) as promocode";
			$promocode_result = Db::query(Database::SELECT, $promocode_query)->execute()->as_array();
			$promo_code = $promocode_result[0]['promocode'];
		}		
		return $promo_code;
	}

	public function getuserslist($company_id = 0)
	{
		$demo_company=""; //1-Demo. For testing purpose only
		$demo_cond="";
		if($demo_company!=""){
			$demo_cond="and passenger_cid=".$demo_company."";
		}
		//condition to get passengers who are related to the company in company login. for admin login all users listed
		if($company_id != 0) {
			$demo_cond="and passenger_cid=".$company_id."";
		}
		$query = "select id,name,salutation,email from ". PASSENGERS . "
			where user_status = 'A'
			and activation_status = '1'
			$demo_cond
			order by created_date desc"; 
		$result = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		$list = "";
		if(count($result)>0)
		{
			$emails="";
			foreach($result as $value)
			{
				$id = $value['id'];
				$name = $value['name'];
				$salutation = empty($value['salutation'])?'':$value['salutation'].' ';				
				$email = $value['email'];	
				$pname = $salutation.$name;
				$emails .= 	'<option value="'.$id.'~'.$email.'~'.$pname.'">'.$email.'('.$pname.')'.'</option>'	;
			}
			$list = '<div class="new_input_field"><select name="to_user[]" id="to_user" class="required" multiple>
						'.$emails.'
				  </select></div> ';
		}
		else
		{
			$list =  __('No Passenger').'<br><input type="hidden" id="to_user" class="promo_send_user" name="to_user" value="">';
		}
		return $list;
	}

	public function getnewuserslist($company_id = 0)
	{
		$demo_company=""; //1-Demo. For testing purpose only
		$demo_cond="";
		if($demo_company!=""){
			$demo_cond="and passenger_cid=".$demo_company."";
		}
		//condition to get passengers who are related to the company in company login. for admin login all users listed
		if($company_id != 0) {
			$demo_cond="and passenger_cid=".$company_id."";
		}
		
		$query = "select ". PASSENGERS . ".id,name,salutation,email from ". PASSENGERS . " LEFT JOIN ". PASSENGERS_LOG . " ON ". PASSENGERS . ".id = ". PASSENGERS_LOG . ".passengers_id LEFT JOIN " .TRANS. " ON ". PASSENGERS_LOG . ".passengers_log_id = " .TRANS. ".passengers_log_id  where ". PASSENGERS . ".user_status = 'A' and ". PASSENGERS . ".activation_status = '1' and ". TRANS . ".fare IS NULL $demo_cond GROUP BY " .PASSENGERS. ".id order by created_date desc"; 
		$result = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		$list = "";
		if(count($result)>0)
		{
			$emails="";
			foreach($result as $value)
			{
				$id = $value['id'];
				$name = $value['name'];
				$salutation = empty($value['salutation'])?'':$value['salutation'].' ';				
				$email = $value['email'];	
				$pname = $salutation.$name;
				$emails .= 	'<option value="'.$id.'~'.$email.'~'.$pname.'">'.$email.'('.$pname.')'.'</option>'	;
			}
			$list = '<div class="new_input_field"><select name="to_user[]" id="to_user" class="required" multiple>
						'.$emails.'
				  </select></div> ';
		}
		else
		{
			$list =  __('No Passenger').'<br><input type="hidden" id="to_user" class="promo_send_user" name="to_user" value="">';
		}
		return $list;
	}
	
	public function getpassengerscount($company_id = 0)
	{
		$demo_company=""; //1-Demo. For testing purpose only

		$demo_cond="";
		if($demo_company!=""){
			$demo_cond="and passenger_cid= '".$demo_company."' ";
		}
		//condition to get passengers who are related to the company in company login. for admin login all users listed
		if($company_id != 0) {
			$demo_cond="and passenger_cid= '".$company_id."' ";
		}
		$query = "select count(id) as total from ". PASSENGERS . " where user_status = 'A' and activation_status = '1' $demo_cond
			order by created_date desc"; 
		$result = Db::query(Database::SELECT, $query)->execute()->get('total');
		return $result;
	}

	public function check_promo_exit($promo_code="", $company_id = 0)
    {
		$cond = "";
		if($company_id != 0) {
			$cond = "and company_id=".$company_id."";
		}
		$promo_query = "SELECT promocode,promo_discount,promo_used FROM  ".PASSENGER_PROMO." WHERE  promocode = '$promo_code' $cond"; 
		$promo_fetch = Db::query(Database::SELECT, $promo_query)
					->execute()
					->as_array();	
		 if(count($promo_fetch)>0)
		 {
			 return 1;
		 }
		 else
		 {
			 return 0;
		 }
	}

	public function count_promocode_list($search, $company_id = 0)
	{
		$condition='';		
		if(!empty($search))
		{
			if($search['keyword']!='')
			{
				$condition .=" AND promocode like '%".$search['keyword']."%'";
			}
			
			if(!empty($search['startdate']))
			{
				$condition .= " AND start_date >= '".$search['startdate']."'";
			}
			
			if(!empty($search['enddate']))
			{
				$condition .= " AND start_date <= '".$search['enddate']."'";
			}			
			
			if(!empty($search['e_startdate']))
			{
				$condition .= " AND expire_date >= '".$search['e_startdate']."'"; 
			}			
			
			if(!empty($search['e_enddate']))
			{
				$condition .= " AND expire_date <= '".$search['e_enddate']."'"; 
			}			
			if(!empty($search['company']))
			{
				$condition .= " AND company_id = '".$search['company']."'"; 
			}
			if(!empty($search['promocode_type']))
			{
				$condition .= " AND promocode_type = '".$search['promocode_type']."'"; 
			}
			
		}
		
		if($company_id != 0) {
			$condition .= " AND ".COMPANY.".company_status = 'A' AND company_id = '".$company_id."'"; 
		}
		$query="SELECT passenger_id FROM ".PASSENGER_PROMO." left join ".COMPANY." ON ".COMPANY.".cid = ".PASSENGER_PROMO.".company_id where 1=1 $condition group by `promocode`";
		//echo $query; exit;
		$result =  Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}

	public function promocode_list($offset, $val,$search, $company_id = 0)
	{
		$condition='';
		if(!empty($search))
		{
			if($search['keyword'] !='')
			{
				$condition .=" AND promocode like '%".$search['keyword']."%'";
			}
			
			if(!empty($search['startdate']))
			{
				$condition .= " AND start_date >= '".$search['startdate']."'"; 
			}
			
			if(!empty($search['enddate']))
			{
				$condition .= " AND start_date <= '".$search['enddate']."'"; 
			}
			
			
			if(!empty($search['e_startdate']))
			{
				$condition .= " AND expire_date >= '".$search['e_startdate']."'";
			}			
			
			if(!empty($search['e_enddate']))
			{
				$condition .= " AND expire_date <= '".$search['e_enddate']."'";
			}	
			if(!empty($search['company']))
			{
				$condition .= " AND company_id = '".$search['company']."'";
			}
			if(!empty($search['promocode_type']))
			{
				$condition .= " AND promocode_type = '".$search['promocode_type']."'"; 
			}
		}
		if($company_id != 0) {
			$condition .= " AND ".COMPANY.".company_status = 'A' AND company_id = '".$company_id."'"; 
		}
		$query="SELECT passenger_id,passenger_promoid,promocode,promo_discount,start_date,expire_date,promo_limit,promocode_type FROM ".PASSENGER_PROMO." left join ".COMPANY." ON ".COMPANY.".cid = ".PASSENGER_PROMO.".company_id where 1=1 $condition group by `promocode` order by passenger_promoid desc limit $offset,$val ";
		$result =  Db::query(Database::SELECT, $query)->execute()->as_array();
		//echo '<pre>';print_r($result);exit;						 
		return $result;	
	}

	public function getactive_users($companyId="")
	{
		$company_cond = "";
		if(!empty($companyId)) {
			$company_cond = "and passenger_cid=".$companyId."";
		}
					
		$query = "select id,salutation,name,email from ". PASSENGERS . "
			where user_status = 'A'
			and activation_status = '1'
			$company_cond
			order by created_date desc"; 
		$result = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
			
		$all_plist = array();	
		foreach($result as $value)
		{
			$id = $value['id'];
			$name = $value['name'];
			$salutation = empty($value['salutation'])?'':$value['salutation'].' ';
			$email = $value['email'];	
			$pname = $salutation.$name;
			$list = $id.'~'.$email.'~'.$pname;
			$all_plist[] = $list;
		}
		return $all_plist;
	}
	/**function to get company name **/
	public function getcompanydomainName($cid)
	{
		$query = "select company_domain from ". COMPANYINFO . " where company_cid = $cid"; 
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result[0];
	}
	/** Function to get driver's current status **/
	public function get_driver_current_status($driver_id,$company_id='')
	{
		if($company_id == '' || $company_id ==0)
		{
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
		$company_condition = "";
		if($company_id != ""){
			$company_condition = " AND ".PASSENGERS_LOG.".company_id = '$company_id'";
		}

				 $sql = "SELECT ".PASSENGERS_LOG.".passengers_log_id,".PASSENGERS_LOG.".travel_status FROM  ".PASSENGERS_LOG." WHERE  ".PASSENGERS_LOG.".`driver_id` =  '$driver_id' and ".PASSENGERS_LOG.".pickup_time >='".$start_time."' $company_condition and (travel_status = '9' OR travel_status = '5' OR travel_status='3' OR travel_status='2') and driver_reply = 'A' ORDER BY ".PASSENGERS_LOG.".passengers_log_id DESC LIMIT 0 , 1 ";
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
			
	}
	
	//** to check whether the taxi is assigned **//
	public function istaxiassigned($taxiIds)
	{
		$rs = DB::select(TAXI.'.taxi_no')->from(TAXIMAPPING)->join(TAXI, 'LEFT')->on(TAXIMAPPING.'.mapping_taxiid', '=', TAXI.'.taxi_id')->where(TAXIMAPPING.'.mapping_taxiid', 'IN',$taxiIds)->where(TAXIMAPPING.'.mapping_status','=','A')->execute()->as_array();
		return $rs;
	}
	//** to check whether the taxi is assigned **//
	public function isdriverassigned($driverIds)
	{
		$rs = DB::select(PEOPLE.'.name')->from(TAXIMAPPING)->join(PEOPLE, 'LEFT')->on(TAXIMAPPING.'.mapping_driverid', '=', PEOPLE.'.id')->where(TAXIMAPPING.'.mapping_driverid', 'IN',$driverIds)->where(TAXIMAPPING.'.mapping_status','=','A')->execute()->as_array();
		return $rs;
	}
	//** to get the assigned taxi details **//
	public static function get_assigned_details($assignId)
	{
		$result = DB::select('mapping_driverid','mapping_taxiid','mapping_startdate','mapping_enddate')->from(TAXIMAPPING)->where(TAXIMAPPING.'.mapping_id','=',$assignId)->execute()->as_array();
		return $result;
	}
	public static function check_already_assigned($driver_id,$taxi_id,$startdate,$enddate)
	{
		$query = " select count(*) as total from " . TAXIMAPPING . " left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id where 1=1 and mapping_status='A' AND (mapping_driverid ='$driver_id' or mapping_taxiid ='$taxi_id')  AND ( ( '$startdate' between mapping_startdate and  mapping_enddate ) or ( '$enddate' between mapping_startdate and  mapping_enddate) ) order by mapping_startdate DESC ";
 		$result = Db::query(Database::SELECT, $query)->execute()->get('total');
 		return $result;
	}
	public static function check_driver_have_taxi($mapId,$driver_id,$startdate,$enddate)
	{
		$query = " select count(*) as total from " . TAXIMAPPING . " left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id where mapping_id != '$mapId' and (mapping_driverid ='$driver_id')  AND ( ( '$startdate' between mapping_startdate and  mapping_enddate ) or ( '$enddate' between mapping_startdate and  mapping_enddate) ) order by mapping_startdate DESC ";
		$result = Db::query(Database::SELECT, $query)->execute()->get('total');
 		return $result;
	}
	/** get wallet requested list **/
	public function getWalletAmtRequests($searchArr, $companyId, $getCnt = 0, $offset = '', $limit = '')
	{
		$keyword = (isset($searchArr['keyword'])) ? $searchArr['keyword'] : '';
		$company = (isset($searchArr['company'])) ? $searchArr['company'] : $companyId;
		$startdate = (isset($searchArr['startdate']) && !empty($searchArr['startdate'])) ? Commonfunction::ensureDatabaseFormat($searchArr['startdate'],1) : '';
		$enddate = (isset($searchArr['enddate']) && !empty($searchArr['startdate'])) ? Commonfunction::ensureDatabaseFormat($searchArr['enddate'],2) : '';
		$limitCnd = "";
		if($getCnt == 0){
			$limitCnd = " limit $offset,$limit";
		}
		
		$whereCnd = "";
		if(!empty($keyword)){
			$whereCnd .= " AND (".PEOPLE.".name LIKE  '%$keyword%' ";
			$whereCnd .= " or ".PEOPLE.".lastname LIKE  '%$keyword%' ";
			$whereCnd .= " or ".PEOPLE.".phone LIKE '%$keyword%' escape '!' ) ";
		}
		
		if(!empty($company)){
			$whereCnd .= " AND ".PEOPLE.".company_id = '$company'";
		}
		
		if(!empty($startdate) && !empty($enddate)) {
			$whereCnd .= " AND ".DRIVER_WALLET_REQUESTS.".wallet_request_date between '$startdate' AND '$enddate'";
		} else if(!empty($startdate)){
			$whereCnd .= " AND ".DRIVER_WALLET_REQUESTS.".wallet_request_date >= '$startdate'";
		} else if(!empty($enddate)) {
			$whereCnd .= " AND ".DRIVER_WALLET_REQUESTS.".wallet_request_date <= '$enddate'";
		}
		//print_r($whereCnd); exit;
		$query = "SELECT wallet_request_id,wallet_request_amount, wallet_request_status as request_status, (CASE WHEN wallet_request_status = '2' THEN 'Approved' WHEN wallet_request_status = '3' THEN 'Reject' ELSE 'Pending' END) as reqstatuslbl,wallet_request_date,wallet_request_approved_date as approved_date,".PEOPLE.".name as driverName, wallet_request_driver as driver_id, CONCAT(".PEOPLE.".country_code,".PEOPLE.".phone) as driverPhone,".COMPANY.".company_name,wallet_request_status FROM ".DRIVER_WALLET_REQUESTS." LEFT JOIN ".PEOPLE." ON ".PEOPLE.".id = ".DRIVER_WALLET_REQUESTS.".wallet_request_driver LEFT JOIN ".COMPANY." ON ".COMPANY.".cid = ".PEOPLE.".company_id WHERE 1=1 $whereCnd order by wallet_request_id desc $limitCnd";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		
		return $result;
	}
	/** update the wallet request status **/
	public function updateRequestStatus($activeids,$reqSts,$crntTime,$userId)
	{
		if($reqSts == 3) {
			$result = DB::update(DRIVER_WALLET_REQUESTS)->set(array('wallet_request_status' => $reqSts))->where('wallet_request_id', 'IN', $activeids)->execute();
			$result = DB::select('wallet_request_driver','wallet_request_amount')->from(DRIVER_WALLET_REQUESTS)->where('wallet_request_id', 'IN', $activeids)->execute()->as_array();
			if(count($result) > 0) {
				foreach($result as $r) {
					$wallet_request_amount = $r['wallet_request_amount'];
					$wallet_request_driver = $r['wallet_request_driver'];
					$query = "update ".DRIVER_REF_DETAILS." set registered_driver_wallet = registered_driver_wallet + $wallet_request_amount where registered_driver_id = '$wallet_request_driver'";
					Db::query(Database::UPDATE, $query)->execute();
				}
			}
		} else {
			$result = DB::update(DRIVER_WALLET_REQUESTS)->set(array('wallet_request_status' => $reqSts, 'wallet_request_approved_date' => $crntTime, 'wallet_request_approved_by' => $userId))->where('wallet_request_id', 'IN', $activeids)->execute();
		}
		return $result;
	}
	/** get wallet requests for a particular driver **/
	public function getDriverWithdrawRequests($driverId)
	{
		$query = "SELECT wallet_request_id,wallet_request_amount,(CASE WHEN wallet_request_status = '2' THEN 'Approved' WHEN wallet_request_status = '3' THEN 'Reject' ELSE 'Pending' END) as statuslbl,wallet_request_date as request_date,wallet_request_approved_date as approved_date, wallet_request_status as request_status FROM ".DRIVER_WALLET_REQUESTS." WHERE wallet_request_driver = :driverId";
		$result = Db::query(Database::SELECT, $query)->parameters(array(':driverId'=>$driverId))->execute()->as_array();
		
		return $result;
	}
	/** function to get passenger wallet logs **/
	public function passengerWalletLogs($searchArr, $setPagination=0 , $offset = '', $limit = '')
	{
		$keyword = (isset($searchArr['keyword'])) ? trim(Html::chars($searchArr['keyword'])) : '';
		$startdate = (isset($searchArr['startdate'])) ? Commonfunction::ensureDatabaseFormat($searchArr['startdate'],1) : '';
		$enddate = (isset($searchArr['enddate'])) ? Commonfunction::ensureDatabaseFormat($searchArr['enddate'],2) : '';
		$limitCnd = "";
		$keyword       = str_replace("%", "!%", $keyword);
        $keyword       = str_replace("_", "!_", $keyword);
		//Database::instance()->escape($keyword);
		if($setPagination == 1){
			$limitCnd = " limit $offset,$limit";
		}
		
		$whereCnd = "";
		if(!empty($keyword)){
			$whereCnd .= " AND (p.name LIKE  '%$keyword%' ";
			$whereCnd .= " or p.lastname LIKE  '%$keyword%' ";
			$whereCnd .= " or CONCAT(p.country_code,p.phone) LIKE '%$keyword%' escape '!' ) ";
		}
		
		if(!empty($company)){
			$whereCnd .= " AND ".PEOPLE.".company_id = '$company'";
		}
		
		if(!empty($startdate) && !empty($enddate)) {
			$whereCnd .= " AND wlogs.createdate between '$startdate' AND '$enddate'";
		} else if(!empty($startdate)){
			$whereCnd .= " AND wlogs.createdate >= '$startdate'";
		} else if(!empty($enddate)) {
			$whereCnd .= " AND wlogs.createdate <= '$enddate'";
		}
		
		$query = "SELECT wlogs.creditcard_no,wlogs.card_holder_name,wlogs.amount,IF(wlogs.payment_type = 1, 'Paypal', 'Braintree') as payment_type,wlogs.transaction_id,wlogs.promocode,wlogs.promocode_amount,wlogs.createdate,p.name,CONCAT(p.country_code,p.phone) as mobile_number FROM ".PASSENGER_WALLET_LOG." as wlogs JOIN ".PASSENGERS." as p ON p.id = wlogs.passenger_id where 1=1 $whereCnd order by wlogs.passenger_wallet_logid desc $limitCnd";
		//echo $query;exit;
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	/************* Dashboard All Driver status***************/
	
	public function details_promoinfo($id, $company_id = 0)
	{
		$condition = "";
		if($company_id != 0) {
			$condition .= " AND ".COMPANY.".company_status = 'A' AND company_id = '".$company_id."'"; 
		}
		$query="SELECT promo_used_details,promocode,promo_discount,start_date,expire_date,promo_limit,passenger_id,count(passenger_id) as passenger_count,GROUP_CONCAT(name) as user_name,promocode_type FROM ".PASSENGER_PROMO." left join ".COMPANY." ON ".COMPANY.".cid = ".PASSENGER_PROMO.".company_id left join ".PASSENGERS." on find_in_set(".PASSENGERS.".id,".PASSENGER_PROMO.".passenger_id) where passenger_promoid = '$id' $condition group by promocode order by passenger_promoid desc";
		$result =  Db::query(Database::SELECT, $query)
					->execute()
					->as_array();
		//echo '<pre>';print_r($result);exit;
		return $result;
	}
	
	/** 
	 * Get Withdraw Requested list 
	 * return array
	 **/

	public function getWithdrawRequests($searchArr, $companyId, $getCnt = 0, $driver_type, $offset = '', $limit = '')
	{
		$company_id = $_SESSION['company_id'];
		$user_type = $_SESSION['user_type'];
		$keyword = (isset($searchArr['keyword'])) ? $searchArr['keyword'] : '';
		$company = (isset($searchArr['company'])) ? $searchArr['company'] : '';
		$brand_type = (isset($searchArr['brand_type'])) ? $searchArr['brand_type'] : '';
		$status = (isset($searchArr['status'])) ? $searchArr['status'] : '';
		$startdate = (isset($searchArr['startdate']) && $searchArr['startdate'] != "") ? Commonfunction::ensureDatabaseFormat($searchArr['startdate'],1) : date('Y-m-d 00:00:00', strtotime('-7 days'));
		$enddate = (isset($searchArr['enddate']) && $searchArr['enddate'] != "") ? Commonfunction::ensureDatabaseFormat($searchArr['enddate'],2) : convert_timezone('now',$_SESSION['timezone']);
		$whereCnd = $limitCnd = "";
		if($getCnt == 0){
			$limitCnd = " limit $offset,$limit";
		}

		$whereCnd .= ($driver_type == 1) ? " and type = '1'" : " and type = '0'";
		$whereCnd .= ($user_type == 'C') ? " and ".WITHDRAW_REQUEST.".company_id = '$company_id'" : "";

		if(!empty($keyword)){
			$whereCnd .= " AND (p.name LIKE  '%$keyword%' ";
			$whereCnd .= " or p.lastname LIKE  '%$keyword%' ";
			$whereCnd .= " or p.phone LIKE '%$keyword%' escape '!'";
			$whereCnd .= " or ".WITHDRAW_REQUEST.".request_id LIKE '%$keyword%')";
		}

		if($status != ""){
			$whereCnd .= " and ".WITHDRAW_REQUEST.".request_status = '$status'";
		}

		if($brand_type != ""){
			$whereCnd .= " and ".WITHDRAW_REQUEST.".brand_type = '$brand_type'";
		}

		if(!empty($startdate) && !empty($enddate)) {
			$whereCnd .= " AND ".WITHDRAW_REQUEST.".request_date between '$startdate' AND '$enddate'";
		} else if(!empty($startdate)){
			$whereCnd .= " AND ".WITHDRAW_REQUEST.".request_date >= '$startdate'";
		} else if(!empty($enddate)) {
			$whereCnd .= " AND ".WITHDRAW_REQUEST.".request_date <= '$enddate'";
		}

		$query = "SELECT withdraw_request_id,request_id,brand_type,withdraw_amount,request_date,request_status,name,lastname from ".WITHDRAW_REQUEST." left join ".PEOPLE." as p on p.id = ".WITHDRAW_REQUEST.".requester_id where 1=1 $whereCnd order by withdraw_request_id desc $limitCnd";
		//echo $query;exit;
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	
	/** 
	 * Get Withdraw Requested Dashboard Data's 
	 * return array
	 **/

	public function getDashboardWithdrawRequests($companyId,$driver_type)
	{
		$condition = "";
		if($companyId > 0) {
			$condition .= " and ".WITHDRAW_REQUEST.".company_id = '$companyId'";
		}
		$condition .= ($driver_type == 1) ? " and type = '1'" : " and type = '0'";
		$query = "SELECT count(withdraw_request_id) as total_withdraw_request_count,sum(case when (request_status = '1') then 1 else 0 end) as approved_withdraw_request_count,sum(case when (request_status = '2' or request_status = '3') then 1 else 0 end) as deny_withdraw_request_count,sum(withdraw_amount) as payment_transaction,sum(case when (brand_type = '2') then withdraw_amount else NULL end) as payment_transaction_single,sum(case when (brand_type = '1') then withdraw_amount else NULL end) as payment_transaction_multy,sum(case when (request_status = '2' or request_status = '3') then withdraw_amount else NULL end) as payment_transaction_deneid,sum(case when (request_status = '0') then withdraw_amount else NULL end) as payment_transaction_pending from ".WITHDRAW_REQUEST." where 1=1 $condition";
		
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		
		return $result;
	}
	
	/** 
	 * Get Withdraw Requested for Detail Page 
	 * return array
	 **/

	public function get_withdraw_deatil($id)
	{
		$query = "SELECT withdraw_request_id,request_id,brand_type,withdraw_amount,request_date,request_status,type,name,lastname from ".WITHDRAW_REQUEST." left join ".PEOPLE." as p on p.id = ".WITHDRAW_REQUEST.".requester_id where withdraw_request_id = '$id'";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	
	/** 
	 * Get Withdraw Requested Log
	 * return array
	 **/

	public function get_withdraw_log($withdraw_request_id)
	{
		$query = "SELECT log_id,payment_mode,transaction_id,comments,status,file_name,created_date,payment_mode_name from ".WITHDRAW_REQUEST_LOG." left join ".WITHDRAW_PAYMENT_MODE." as p on p.withdraw_payment_mode_id = ".WITHDRAW_REQUEST_LOG.".payment_mode where withdraw_request_id = '$withdraw_request_id' order by log_id desc";
		//echo $query;exit;
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	
	/** 
	 * Insert New Log for Withdraw Request
	 * return last insert ID
	 **/

	public function insert_withdraw_status_log($post,$log_id)
	{
		$date = commonfunction::getCurrentTimeStamp();
		$transaction_id = $post['transaction_id'];
		$payment_mode = $post['payment_mode'];
		if($post["status"] == 2) {
			$transaction_id = "";
			$payment_mode = 0;
		}
		$result = DB::insert(WITHDRAW_REQUEST_LOG, array('status','payment_mode','transaction_id','comments','withdraw_request_id','created_date'))->values(array($post['status'],$payment_mode,$transaction_id,$post['comments'],$log_id,$date))->execute();
		DB::update(WITHDRAW_REQUEST)->set(array('request_status' => $post["status"]))->where('withdraw_request_id', '=', $log_id)->execute();
		if($post['status'] == 1) {
			$querys = "SELECT withdraw_amount,requester_id from ".WITHDRAW_REQUEST." where withdraw_request_id = '$log_id'";
			$amount_result = Db::query(Database::SELECT, $querys)->execute()->as_array();
			if(count($amount_result) > 0) {
				$company_request_amount = $amount_result[0]["withdraw_amount"];
				$requester_id = $amount_result[0]["requester_id"];
				//$update_query = "update ".PEOPLE." set trip_wallet = trip_wallet - '$company_request_amount' where id = '$requester_id'";
				//Db::query(Database::UPDATE, $update_query)->execute();
				//exit;
			}
		} else if($post['status'] == 2) {
			$company_request_amount = $requester_id = 0;
			$querys = "SELECT withdraw_amount,requester_id from ".WITHDRAW_REQUEST." where withdraw_request_id = '$log_id'";
			$amount_result = Db::query(Database::SELECT, $querys)->execute()->as_array();
			if(count($amount_result) > 0) {
				$company_request_amount = $amount_result[0]["withdraw_amount"];
				$requester_id = $amount_result[0]["requester_id"];
			}
			if($requester_id > 0) {
				if($post['type'] == 0) {
					$update_query = "update ".PEOPLE." set account_balance = account_balance + '$company_request_amount' where id = '$requester_id' and user_type = 'C'";
				} else {
					$update_query = "update ".PEOPLE." set trip_wallet = trip_wallet + '$company_request_amount' where id = '$requester_id' and user_type = 'D'";
				}
				Db::query(Database::UPDATE, $update_query)->execute();
			}
		}
		return $result[0];
	}

	/** 
	 * Update Withdraw Request Attachment Name
	 **/

	public function update_withdraw_file_name($log_ids,$attachment_name)
	{
		DB::update(WITHDRAW_REQUEST_LOG)->set(array('file_name' => $attachment_name))->where('log_id', 'IN', $log_ids)->execute();
	}

	/** 
	 * Get Withdraw Request Payment Mode
	 * return array
	 **/

	public function get_withdraw_payment_mode()
	{
		$query = "SELECT withdraw_payment_mode_id,payment_mode_name from ".WITHDRAW_PAYMENT_MODE." where payment_mode_status = '0' order by withdraw_payment_mode_id desc";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}

	/** 
	 * Block & Unblock Withdraw Request Payment Mode
	 * return int true
	 **/

	public function block_unblock_withdraw_payment($activeids,$status)
	{
		$result = DB::update(WITHDRAW_PAYMENT_MODE)
					->set(array('payment_mode_status' => $status))
					->where('withdraw_payment_mode_id', 'IN', $activeids)
					->execute();
		return 1;
	}
	
	/** 
	 * Insert New Withdraw Request From Company
	 * return last insert ID
	 **/
	
	public function insert_company_withdraw_request($post,$company_id,$user_id)
	{
		$uniqueId = commonfunction::checkRequestID();
		$date = commonfunction::getCurrentTimeStamp();
		$company_request_amount = $post['company_request_amount'];
		$result = DB::insert(WITHDRAW_REQUEST, array('company_id','request_id','brand_type','requester_id','withdraw_amount','request_date'))->values(array($company_id,$uniqueId,1,$user_id,$company_request_amount,$date))->execute();
		/* if($result[0] > 0) {
			$update_query = "update ".PEOPLE." set account_balance = account_balance - '$company_request_amount' where id = '$user_id'";
			Db::query(Database::UPDATE, $update_query)->execute();
		} */
		return $result[0];
	}
	
	public function mapping_driver_details($id){
	
		$now = date('Y-m-d H:i:s');
		//$query = "SELECT m.mapping_driverid as driverid FROM ".TAXIMAPPING." m WHERE m.mapping_taxiid=".$id;
		$query = "SELECT m.mapping_driverid as driverid, p.id FROM ".TAXIMAPPING." m join ".PEOPLE." p on m.mapping_driverid = p.id  WHERE m.mapping_taxiid='".$id."' AND m.mapping_startdate<='".$now."' AND m.mapping_enddate>='".$now."'";
			
		//echo $query;exit;
		$rs = Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
		//print_r($rs);exit;
		return $rs;
	}
	
	public function get_people_list(){
		
		$peoples =array();
		$people_list = DB::select('id','name')->from(PEOPLE)->execute()->as_array();
		if(!empty($people_list)){
			foreach($people_list as $p){
				$peoples[$p['id']] = $p['name'];
			}
		}
		return $peoples;
	}
	
	public function unassign_taxi_list($company = '')
	{
		$cuurentdate = $this->Commonmodel->getcompany_all_currenttimestamp($company);
		$date = explode(" ",$cuurentdate);
		$enddate = $date[0]." 23:59:59";

		$query_where = " AND ( ( '$cuurentdate' between taximapping.mapping_startdate and  taximapping.mapping_enddate ) or ( '$enddate' between taximapping.mapping_startdate and  taximapping.mapping_enddate) )";

		$sql = "SELECT people.id,taxi.taxi_id
			FROM ".TAXI." as taxi
			JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid
			JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid
			JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid
			WHERE people.status = 'A'
			AND taximapping.mapping_status = 'A' $query_where";
		//echo $sql;exit;
		$results = Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $results;
	}
	
	public function findcompany_currency($company_cid)
	{
		$currency_arr[] = array('currency_code' =>CURRENCY_FORMAT, 'currency_symbol' =>CURRENCY);
		$rs = DB::select('currency_code','currency_symbol')->from(PAYMENT_GATEWAYS)
			->where('default_payment_gateway', '=', '1')
			->where('company_id', '=',$company_cid)
			->execute()
			->as_array();
		
		if(count($rs)>0)
			return $rs;
		else
			return $currency_arr;
	}
	/** function to get driver device token and device type **/
	public function getDriverDeviceToken($deviceId)
	{
		$result = DB::select('device_type','device_token')->from(PEOPLE)->where('id','=',$deviceId)->execute()->as_array();
		return $result;
	}
	public function signoutDriver($driverId, $driver_shift_id, $currentDate)
	{
		//check whether id is exist in checkbox or single active request
		//==================================================================
		$result = DB::update(PEOPLE)->set(array("login_from"=>"","login_status"=>"N","device_id" => "","device_token" => "","device_type" => "","notification_setting"=>"0"))->where('id', '=', $driverId)->execute();
		if($result){
			//update shit status in driver table
			DB::update(DRIVER)->set(array('shift_status' => 'OUT'))->where('driver_id', '=', $driverId)->execute();
			//update driver shit end time in shift service table
			DB::update(DRIVERSHIFTSERVICE)->set(array("shift_end" => $currentDate))->where('driver_shift_id', '=', $driver_shift_id)->execute();
		}
		return $result;
	}
	
	public function company_login_update($id = "")
	{
		return DB::update(PEOPLE)->set(array('login_from' => 'W'))->where('id', '=', $id)->where('user_type', '=', 'C')->execute();
	}
	
	public function update_assign_date($ids,$start_date,$end_date)
	{
		//print_r($start_date); print_r($end_date); exit;
		$result = DB::update(TAXIMAPPING)->set(array('mapping_startdate' => $start_date,'mapping_enddate' => $end_date))->where('mapping_id', 'IN', $ids)->execute();
			
		return $result;
	}
	
	public function couponcode_list_count($search, $company_id = 0)
	{
		$condition = '';
		if(!empty($search))
		{
			if($search['keyword'] !='')
			{
				$keyword = '';
				$keyword = trim(Html::chars($search['keyword']));
				$condition .=" AND coupon_code like '%".$keyword."%'";
			}
			if($search['serial_number'] !='')
			{
				$serial_number = '';
				$serial_number = trim(Html::chars($search['serial_number']));
				$condition .=" AND serial_number = '".$serial_number."'";
			}
			if($search['status'] !='')
			{
				$condition .=" AND coupon_status = '".$search['status']."'";
			}
			if($search['coupon_code_status'] !='')
			{
				$condition .=" AND coupon_used = '".$search['coupon_code_status']."'";
			}
			if(!empty($search['startdate']))
			{
				$condition .= " AND start_date >= '".$search['startdate']."'"; 
			}
			
			if(!empty($search['enddate']))
			{
				$condition .= " AND start_date <= '".$search['enddate']."'"; 
			}			
			
			
			if(!empty($search['e_startdate']))
			{
				$condition .= " AND expiry_date >= '".$search['e_startdate']."'"; 
			}			
			
			if(!empty($search['e_enddate']))
			{
				$condition .= " AND expiry_date <= '".$search['e_enddate']."'"; 
			}	
			if(!empty($search['company']))
			{
				$condition .= " AND company_id = '0'"; 
			}
			if(!empty($search['serialno_from']) || !empty($search['serialno_to']) )
			{
				
				if($search['serialno_from'] != '' && $search['serialno_to'] != '')
				{
					$serialno_from = '';
					$serialno_to = '';
					$serialno_from = trim(Html::chars($search['serialno_from']));
					$serialno_to = trim(Html::chars($search['serialno_to']));
					$condition .= " AND ( serial_number >= '".$serialno_from."' and serial_number <= '".$serialno_to."' )"; 
				}
				elseif($search['serialno_from'] != '')
				{
					$serialno_from = '';
					$serialno_from = trim(Html::chars($search['serialno_from']));
					$condition .= " AND serial_number >= '".$serialno_from."'"; 
				}
				elseif($search['serialno_to'] != '')
				{
					$serialno_to = '';
					$serialno_to = trim(Html::chars($search['serialno_to']));
					$condition .= " AND serial_number <= '".$serialno_to."'"; 
				}				
					
			}		
			
		}
		if($company_id != 0) {
			$condition .= " AND company_id = '".$company_id."'"; 
		}
		
		//$condition .= " AND coupon_used = '0'"; 
			$query = "SELECT * FROM ".DRIVERS_COUPON."  group by `coupon_code` having 1=1 $condition AND added_by=0 order by coupon_id desc";
		//echo $query;
		$result =  Db::query(Database::SELECT, $query)
						->execute()
						->as_array();						 
		return $result;	
	}
	public function couponcode_list($offset, $val,$search, $company_id = 0)
	{
		$condition = '';
		if(!empty($search))
		{
			if($search['keyword'] !='')
			{
				$keyword = '';
				$keyword = trim(Html::chars($search['keyword']));
				$condition .=" AND coupon_code like '%".$keyword."%'";
			}
			if($search['serial_number'] !='')
			{
				$serial_number = '';
				$serial_number = trim(Html::chars($search['serial_number']));
				$condition .=" AND serial_number = '".$serial_number."'";
			}
			if($search['status'] !='')
			{
				$condition .=" AND coupon_status = '".$search['status']."'";
			}
			if($search['coupon_code_status'] !='')
			{
				$condition .=" AND coupon_used = '".$search['coupon_code_status']."'";
			}
			if(!empty($search['startdate']))
			{
				$condition .= " AND start_date >= '".$search['startdate']."'"; 
			}
			
			if(!empty($search['enddate']))
			{
				$condition .= " AND start_date <= '".$search['enddate']."'"; 
			}			
			
			
			if(!empty($search['e_startdate']))
			{
				$condition .= " AND expiry_date >= '".$search['e_startdate']."'"; 
			}			
			
			if(!empty($search['e_enddate']))
			{
				$condition .= " AND expiry_date <= '".$search['e_enddate']."'"; 
			}	
			if(!empty($search['company']))
			{
				$condition .= " AND company_id = '0'"; 
			}
			if(!empty($search['serialno_from']) || !empty($search['serialno_to']) )
			{
				
				if($search['serialno_from'] != '' && $search['serialno_to'] != '')
				{
					$serialno_from = '';
					$serialno_to = '';
					$serialno_from = trim(Html::chars($search['serialno_from']));
					$serialno_to = trim(Html::chars($search['serialno_to']));
					$condition .= " AND ( serial_number >= '".$serialno_from."' and serial_number <= '".$serialno_to."' )"; 
				}
				elseif($search['serialno_from'] != '')
				{
					$serialno_from = '';
					$serialno_from = trim(Html::chars($search['serialno_from']));
					$condition .= " AND serial_number >= '".$serialno_from."'"; 
				}
				elseif($search['serialno_to'] != '')
				{
					$serialno_to = '';
					$serialno_to = trim(Html::chars($search['serialno_to']));
					$condition .= " AND serial_number <= '".$serialno_to."'"; 
				}				
					
			}
		
			
		}
		if($company_id != 0) {
			$condition .= " AND company_id = '".$company_id."'"; 
		}
		
		//$condition .= " AND coupon_used = '0'"; 
		//SELECT * FROM ".DRIVERS_COUPON." as dc LEFT JOIN ".PEOPLE." as p on dc.driver_id = p.id WHERE p.user_type = 'C' GROUP BY `coupon_code` HAVING 1=1 $condition ORDER BY coupon_id DESC LIMIT $offset,$val 

		if( $val != '' )
			$query = "SELECT * FROM ".DRIVERS_COUPON." as dc LEFT JOIN ".PEOPLE." as p on dc.driver_id = p.id GROUP BY `coupon_code` HAVING 1=1 $condition AND added_by=0 ORDER BY coupon_id DESC LIMIT $offset,$val";
		else
			$query = "SELECT * FROM ".DRIVERS_COUPON." as dc LEFT JOIN ".PEOPLE." as p on dc.driver_id = p.id GROUP BY `coupon_code` HAVING 1=1 $condition AND added_by=0 ORDER BY coupon_id DESC";
		//echo $query;exit;
		$result =  Db::query(Database::SELECT, $query)
						->execute()
						->as_array();						 
		return $result;	
	}
	
	public function coupon_generate_id_list_count($search)
	{
		$condition = '';
		if(!empty($search))
		{
			if($search['generate_id'] !='')
			{
				$condition .=" AND dc.generate_id like '%".$search['generate_id']."%'";
			}
			if(!empty($search['startdate']))
			{
				$condition .= " AND dc.created_date >= '".$search['startdate']."'"; 
			}
			if(!empty($search['enddate']))
			{
				$condition .= " AND dc.created_date <= '".$search['enddate']."'"; 
			}			
			
		}
		
		$query = "SELECT *, (SELECT COUNT(coupon_code) 
					          FROM ".DRIVERS_COUPON." dc1 
					         WHERE dc1.generate_id = dc.generate_id) AS total_coupons,
					         (SELECT COUNT(coupon_code) 
					          FROM ".DRIVERS_COUPON." dc1 
					         WHERE dc1.generate_id = dc.generate_id AND dc1.coupon_used = 1) AS used_coupons  
					         FROM ".DRIVERS_COUPON." AS dc 
					  WHERE generate_id != '' $condition 
					  GROUP BY generate_id  
					  ORDER BY created_date DESC";
		
		$result =  Db::query(Database::SELECT, $query)
						->execute()
						->as_array();						 
		return count($result);	
	}
	
	public function get_coupon_generate_id_list($offset,$limit,$search)
	{
		$condition = '';
		if(!empty($search))
		{
			if($search['generate_id'] !='')
			{
				$condition .=" AND dc.generate_id like '%".$search['generate_id']."%'";
			}
			if(!empty($search['startdate']))
			{
				$condition .= " AND dc.created_date >= '".$search['startdate']."'"; 
			}
			if(!empty($search['enddate']))
			{
				$condition .= " AND dc.created_date <= '".$search['enddate']."'"; 
			}			
			
		}
		
		$query = "SELECT *, (SELECT COUNT(coupon_code) 
					          FROM ".DRIVERS_COUPON." dc1 
					         WHERE dc1.generate_id = dc.generate_id) AS total_coupons,
					         (SELECT COUNT(coupon_code) 
					          FROM ".DRIVERS_COUPON." dc1 
					         WHERE dc1.generate_id = dc.generate_id AND dc1.coupon_used = 1) AS used_coupons  
					         FROM ".DRIVERS_COUPON." AS dc 
					  WHERE generate_id != '' $condition 
					  GROUP BY generate_id  
					  ORDER BY created_date DESC 
					  LIMIT $limit OFFSET $offset";

	
		$result =  Db::query(Database::SELECT, $query)
						->execute()
						->as_array();						 
		return $result;	
	}
	
	public function get_generate_id_coupons($generate_id)
	{
		$coupon_list = array();

		$site_info = DB::select('site_currency', 'email_id', 'phone_number')->from(SITEINFO)
		               ->limit(1)->execute()->as_array();

		$drivers_coupons = DB::select()->from(DRIVERS_COUPON)
		                     ->where('generate_id', '=', $generate_id)
		                     ->where('coupon_used', '=', 0)
		                     ->execute()->as_array();

		if(isset($drivers_coupons) && count($drivers_coupons) > 0)
		{
			foreach ($drivers_coupons as $coupon) {
				
				$coupon_list[] = array("coupon_code" => $coupon['coupon_code'],
									   "coupon_amt" => $coupon['amount'],
									   "serial_number" => $coupon['serial_number'],
									   "site_currency" => $site_info[0]['site_currency'],
									   "email_id" => $site_info[0]['email_id'],
									   "phone_number" => $site_info[0]['phone_number'],
								 );
			}
		} 

		return $coupon_list;                   
	}
	
	
	public function passenger_couponcode_list_count($search, $company_id = 0)
	{
		$condition = '';
		if(!empty($search))
		{
			if($search['keyword'] !='')
			{
				$keyword = '';
				$keyword = trim(Html::chars($search['keyword']));
				$condition .=" AND coupon_code like '%".$keyword."%'";
			}
			if($search['serial_number'] !='')
			{
				$serial_number = '';
				$serial_number = trim(Html::chars($search['serial_number']));
				$condition .=" AND serial_number = '".$serial_number."'";
			}
			if($search['status'] !='')
			{
				$condition .=" AND coupon_status = '".$search['status']."'";
			}
			if($search['coupon_code_status'] !='')
			{
				$condition .=" AND coupon_used = '".$search['coupon_code_status']."'";
			}
			if(!empty($search['startdate']))
			{
				$condition .= " AND start_date >= '".$search['startdate']."'"; 
			}
			
			if(!empty($search['enddate']))
			{
				$condition .= " AND start_date <= '".$search['enddate']."'"; 
			}			
			
			
			if(!empty($search['e_startdate']))
			{
				$condition .= " AND expiry_date >= '".$search['e_startdate']."'"; 
			}			
			
			if(!empty($search['e_enddate']))
			{
				$condition .= " AND expiry_date <= '".$search['e_enddate']."'"; 
			}	
			if(!empty($search['company']))
			{
				$condition .= " AND company_id = '0'"; 
			}
			if(!empty($search['serialno_from']) || !empty($search['serialno_to']) )
			{
				
				if($search['serialno_from'] != '' && $search['serialno_to'] != '')
				{
					$serialno_from = '';
					$serialno_to = '';
					$serialno_from = trim(Html::chars($search['serialno_from']));
					$serialno_to = trim(Html::chars($search['serialno_to']));
					$condition .= " AND ( serial_number >= '".$serialno_from."' and serial_number <= '".$serialno_to."' )"; 
				}
				elseif($search['serialno_from'] != '')
				{
					$serialno_from = '';
					$serialno_from = trim(Html::chars($search['serialno_from']));
					$condition .= " AND serial_number >= '".$serialno_from."'"; 
				}
				elseif($search['serialno_to'] != '')
				{
					$serialno_to = '';
					$serialno_to = trim(Html::chars($search['serialno_to']));
					$condition .= " AND serial_number <= '".$serialno_to."'"; 
				}				
					
			}		
			
		}
		if($company_id != 0) {
			//$condition .= " AND company_id = '".$company_id."'"; 
		}
		
		//$condition .= " AND coupon_used = '0'"; 
			$query = "SELECT * FROM ".PASSENGERS_COUPON."  group by `coupon_code` having 1=1 $condition AND added_by=0 order by coupon_id desc";
		//echo $query;
		$result =  Db::query(Database::SELECT, $query)
						->execute()
						->as_array();						 
		return $result;	
	}
	public function passenger_couponcode_list($offset, $val,$search, $company_id = 0)
	{
		$condition = '';
		if(!empty($search))
		{
			if($search['keyword'] !='')
			{
				$keyword = '';
				$keyword = trim(Html::chars($search['keyword']));
				$condition .=" AND coupon_code like '%".$keyword."%'";
			}
			if($search['serial_number'] !='')
			{
				$serial_number = '';
				$serial_number = trim(Html::chars($search['serial_number']));
				$condition .=" AND serial_number = '".$serial_number."'";
			}
			if($search['status'] !='')
			{
				$condition .=" AND coupon_status = '".$search['status']."'";
			}
			if($search['coupon_code_status'] !='')
			{
				$condition .=" AND coupon_used = '".$search['coupon_code_status']."'";
			}
			if(!empty($search['startdate']))
			{
				$condition .= " AND start_date >= '".$search['startdate']."'"; 
			}
			
			if(!empty($search['enddate']))
			{
				$condition .= " AND start_date <= '".$search['enddate']."'"; 
			}			
			
			
			if(!empty($search['e_startdate']))
			{
				$condition .= " AND expiry_date >= '".$search['e_startdate']."'"; 
			}			
			
			if(!empty($search['e_enddate']))
			{
				$condition .= " AND expiry_date <= '".$search['e_enddate']."'"; 
			}	
			if(!empty($search['company']))
			{
				$condition .= " AND company_id = '0'"; 
			}
			if(!empty($search['serialno_from']) || !empty($search['serialno_to']) )
			{
				
				if($search['serialno_from'] != '' && $search['serialno_to'] != '')
				{
					$serialno_from = '';
					$serialno_to = '';
					$serialno_from = trim(Html::chars($search['serialno_from']));
					$serialno_to = trim(Html::chars($search['serialno_to']));
					$condition .= " AND ( serial_number >= '".$serialno_from."' and serial_number <= '".$serialno_to."' )"; 
				}
				elseif($search['serialno_from'] != '')
				{
					$serialno_from = '';
					$serialno_from = trim(Html::chars($search['serialno_from']));
					$condition .= " AND serial_number >= '".$serialno_from."'"; 
				}
				elseif($search['serialno_to'] != '')
				{
					$serialno_to = '';
					$serialno_to = trim(Html::chars($search['serialno_to']));
					$condition .= " AND serial_number <= '".$serialno_to."'"; 
				}				
					
			}
		
			
		}
		if($company_id != 0) {
			//$condition .= " AND company_id = '".$company_id."'"; 
		}
		
		//$condition .= " AND coupon_used = '0'"; 
		//SELECT * FROM ".DRIVERS_COUPON." as dc LEFT JOIN ".PEOPLE." as p on dc.driver_id = p.id WHERE p.user_type = 'C' GROUP BY `coupon_code` HAVING 1=1 $condition ORDER BY coupon_id DESC LIMIT $offset,$val 

		if( $val != '' )
			$query = "SELECT * FROM ".PASSENGERS_COUPON." as dc LEFT JOIN ".PASSENGERS." as p on dc.passenger_id = p.id GROUP BY `coupon_code` HAVING 1=1 $condition AND added_by=0 ORDER BY coupon_id DESC LIMIT $offset,$val";
		else
			$query = "SELECT * FROM ".PASSENGERS_COUPON." as dc LEFT JOIN ".PASSENGERS." as p on dc.passenger_id = p.id GROUP BY `coupon_code` HAVING 1=1 $condition AND added_by=0 ORDER BY coupon_id DESC";
		//echo $query;exit;
		$result =  Db::query(Database::SELECT, $query)
						->execute()
						->as_array();						 
		return $result;	
	}
	
	
	public function coupon_generate_id_list_count_passenger($search)
	{
		$condition = '';
		if(!empty($search))
		{
			if($search['generate_id'] !='')
			{
				$condition .=" AND dc.generate_id like '%".$search['generate_id']."%'";
			}
			if(!empty($search['startdate']))
			{
				$condition .= " AND dc.created_date >= '".$search['startdate']."'"; 
			}
			if(!empty($search['enddate']))
			{
				$condition .= " AND dc.created_date <= '".$search['enddate']."'"; 
			}			
			
		}
		
		$query = "SELECT *, (SELECT COUNT(coupon_code) 
					          FROM ".PASSENGERS_COUPON." dc1 
					         WHERE dc1.generate_id = dc.generate_id) AS total_coupons,
					         (SELECT COUNT(coupon_code) 
					          FROM ".PASSENGERS_COUPON." dc1 
					         WHERE dc1.generate_id = dc.generate_id AND dc1.coupon_used = 1) AS used_coupons  
					         FROM ".PASSENGERS_COUPON." AS dc 
					  WHERE generate_id != '' $condition 
					  GROUP BY generate_id  
					  ORDER BY created_date DESC";
		
		$result =  Db::query(Database::SELECT, $query)
						->execute()
						->as_array();						 
		return count($result);	
	}
	
	public function get_coupon_generate_id_list_passenger($offset,$limit,$search)
	{
		$condition = '';
		if(!empty($search))
		{
			if($search['generate_id'] !='')
			{
				$condition .=" AND dc.generate_id like '%".$search['generate_id']."%'";
			}
			if(!empty($search['startdate']))
			{
				$condition .= " AND dc.created_date >= '".$search['startdate']."'"; 
			}
			if(!empty($search['enddate']))
			{
				$condition .= " AND dc.created_date <= '".$search['enddate']."'"; 
			}			
			
		}
		
		$query = "SELECT *, (SELECT COUNT(coupon_code) 
					          FROM ".PASSENGERS_COUPON." dc1 
					         WHERE dc1.generate_id = dc.generate_id) AS total_coupons,
					         (SELECT COUNT(coupon_code) 
					          FROM ".PASSENGERS_COUPON." dc1 
					         WHERE dc1.generate_id = dc.generate_id AND dc1.coupon_used = 1) AS used_coupons  
					         FROM ".PASSENGERS_COUPON." AS dc 
					  WHERE generate_id != '' $condition 
					  GROUP BY generate_id  
					  ORDER BY created_date DESC 
					  LIMIT $limit OFFSET $offset";

	
		$result =  Db::query(Database::SELECT, $query)
						->execute()
						->as_array();						 
		return $result;	
	}
	
	public function get_generate_id_coupons_passengers($generate_id)
	{
		$coupon_list = array();

		$site_info = DB::select('site_currency', 'email_id', 'phone_number')->from(SITEINFO)
		               ->limit(1)->execute()->as_array();

		$drivers_coupons = DB::select()->from(PASSENGERS_COUPON)
		                     ->where('generate_id', '=', $generate_id)
		                     ->where('coupon_used', '=', 0)
		                     ->execute()->as_array();

		if(isset($drivers_coupons) && count($drivers_coupons) > 0)
		{
			foreach ($drivers_coupons as $coupon) {
				
				$coupon_list[] = array("coupon_code" => $coupon['coupon_code'],
									   "coupon_amt" => $coupon['amount'],
									   "serial_number" => $coupon['serial_number'],
									   "site_currency" => $site_info[0]['site_currency'],
									   "email_id" => $site_info[0]['email_id'],
									   "phone_number" => $site_info[0]['phone_number'],
								 );
			}
		} 

		return $coupon_list;                   
	}
	
	public function get_passengers_list()
	{
		$result = DB::select('id','name','country_code', 'phone', 'wallet_amount')->from(PASSENGERS)
				->where('user_status','=','A')
				->order_by('name','asc')
				->execute()
				->as_array();
				
		return $result;
		
	}
	
	public function get_passengers_data($id)
	{
		$result = DB::select('wallet_amount','name','email','phone','country_code')->from(PASSENGERS)
				->where('id','=',$id)
				->execute()
				->current();
		return $result;
		
	}
	
	/**Validating for Add Motor**/
	public function validate_passenger_credits($arr) 
	{
		return Validation::factory($arr)       
		
			->rule('passenger_id', 'not_empty')
			->rule('pay_type', 'not_empty')
			->rule('amount', 'not_empty')
			->rule('amount', 'numeric')
			->rule( 'amount', 'regex', array( ':value', "/^[1-9]\d*(\.\d+)?$/" ) )
			->rule('comments', 'not_empty');
	}
	
	public function add_passenger_amount($post)
	{
		//print_r($post); exit;
		$serial_number = null;
		$last_insert_id = DB::select(array(DB::expr('coupon_id' ), 'last_insert_id'))
			                    ->from(PASSENGERS_COUPON)
			                    ->order_by('coupon_id', 'DESC')
			                    ->limit(1)
			                    ->execute()
			                    ->get('last_insert_id');

		$serial_number = $last_insert_id + 1;
		
		
		$currentdate = date('Y-m-d H:i:s');
		$result = DB::insert(PASSENGERS_COUPON, array('passenger_id','amount','coupon_used','coupon_status','coupon_count','comments','added_by','start_date','expiry_date','used_date','created_date','pay_type','serial_number'))
						->values(array($post['passenger_id'],$post['amount'],1,'A',1,$post['comments'],1,$currentdate,$currentdate,$currentdate,$currentdate,$post['pay_type'],$serial_number))
						->execute();
		$get_balance = $this->get_passengers_data($post['passenger_id']);
		$old_balance = (isset($get_balance['wallet_amount']) && $get_balance['wallet_amount'] !="")?$get_balance['wallet_amount']:0;
		
		if($post['pay_type'] == 1)
		{
			$total_balance = $old_balance + $post['amount'];
		}
		else
		{
			$total_balance = $old_balance - $post['amount'];	
		}
		
		$datas['receiver_id']=$post['passenger_id'];
	    $datas['receiver_pervious_balance']=$old_balance;
	    $datas['receiver_after_balance']=$total_balance;
	    $datas['transfer_amount']=$post['amount'];
	    $datas['transfer_type']=$post['pay_type'];
	    $datas['comments']=($post['pay_type'] ==1)?"Admin credit":"Admin Debit";
	    $datas['trip_id']=0;
	   
	    $add_logs = $this->Commonmodel->set_passenger_cash_transfer($datas);
		
		$passenger_update_array  = array("wallet_amount"=>round($total_balance,2));
		$update_driver_amount = $this->update_table(PASSENGERS,$passenger_update_array,'id',$post['passenger_id']);
		
		
		return $result;
	}
	
	//Common Function for updation
	public function update_table($table,$arr,$cond1,$cond2)
	{
			$result=DB::update($table)->set($arr)->where($cond1,"=",$cond2)->execute();
			return $result;
	}
	
	/** function to get passenger credit logs **/
	public function passengerCreditLogs($searchArr, $setPagination=0 , $offset = '', $limit = '')
	{
		$keyword = (isset($searchArr['keyword'])) ? trim(Html::chars($searchArr['keyword'])) : '';
		$startdate = (isset($searchArr['startdate']) && $searchArr['startdate']!="" ) ? Commonfunction::ensureDatabaseFormat($searchArr['startdate'],1) : '';
		$enddate = (isset($searchArr['enddate']) && $searchArr['enddate']!="") ? Commonfunction::ensureDatabaseFormat($searchArr['enddate'],2) : '';
		$limitCnd = "";
		$keyword       = str_replace("%", "!%", $keyword);
        $keyword       = str_replace("_", "!_", $keyword);
		//Database::instance()->escape($keyword);
		if($setPagination == 1){
			$limitCnd = " limit $offset,$limit";
		}
		
		$whereCnd = "";
		if(!empty($keyword)){
			$whereCnd .= " AND (p.name LIKE  '%$keyword%' ";
			$whereCnd .= " or p.lastname LIKE  '%$keyword%' ";
			$whereCnd .= " or CONCAT(p.country_code,p.phone) LIKE '%$keyword%' escape '!' ) ";
		}
		
		if(!empty($company)){
			//$whereCnd .= " AND ".PEOPLE.".company_id = '$company'";
		}
		
		if(!empty($startdate) && !empty($enddate)) {
			$whereCnd .= " AND wlogs.transfer_date between '$startdate' AND '$enddate'";
		} else if(!empty($startdate)){
			$whereCnd .= " AND wlogs.transfer_date >= '$startdate'";
		} else if(!empty($enddate)) {
			$whereCnd .= " AND wlogs.transfer_date <= '$enddate'";
		}

		$query = "SELECT wlogs.trip_id,wlogs.receiver_previous_balance as old_balance,wlogs.receiver_after_balance as new_balance,wlogs.comments,p.name,IF(wlogs.transfer_type = 1, 'Credit', 'Debit') as payment_type,wlogs.transfer_amount as amount,wlogs.transfer_date as created_date,p.name,CONCAT(p.country_code,p.phone) as mobile_number FROM ".PASSENGER_CASH_TRANSFER." as wlogs JOIN ".PASSENGERS." as p ON p.id = wlogs.receiver_id where 1=1 $whereCnd order by wlogs.id desc $limitCnd";
			
		//echo $query;exit;
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	
	/**Validating for Add Motor**/
	public function validate_driver_credits($arr) 
	{
		return Validation::factory($arr)       
		
			->rule('driver_id', 'not_empty')
			->rule('pay_type', 'not_empty')
			->rule('amount', 'not_empty')
			->rule('amount', 'numeric')
			//->rule( 'amount', 'regex', array( ':value', "/^[1-9]\d*(\.\d+)?$/" ) )
			->rule('comments', 'not_empty');
	}
	
	public function add_driver_amount($post)
	{
		//print_r($post); exit;
		$serial_number = null;
		$last_insert_id = DB::select(array(DB::expr('coupon_id' ), 'last_insert_id'))
			                    ->from(DRIVERS_COUPON)
			                    ->order_by('coupon_id', 'DESC')
			                    ->limit(1)
			                    ->execute()
			                    ->get('last_insert_id');

		$serial_number = $last_insert_id + 1;
		
		
		$currentdate = date('Y-m-d H:i:s');
		$result = DB::insert(DRIVERS_COUPON, array('driver_id','amount','coupon_used','coupon_status','coupon_count','comments','added_by','start_date','expiry_date','used_date','created_date','pay_type','serial_number'))
						->values(array($post['driver_id'],$post['amount'],1,'A',1,$post['comments'],1,$currentdate,$currentdate,$currentdate,$currentdate,$post['pay_type'],$serial_number))
						->execute();
		$get_balance = $this->get_drivers_data($post['driver_id']);
		$old_balance = (isset($get_balance['account_balance']) && $get_balance['account_balance'] !="")?$get_balance['account_balance']:0;
		if($post['pay_type'] == 1)
		{
			$total_balance = $old_balance + $post['amount'];
		}
		else
		{
			$total_balance = $old_balance - $post['amount'];	
		}
		
		$datas['receiver_id']=$post['driver_id'];
	    $datas['receiver_pervious_balance']=$old_balance;
	    $datas['receiver_after_balance']=$total_balance;
	    $datas['transfer_amount']=$post['amount'];
	    $datas['transfer_type']=$post['pay_type'];
	    $datas['comments']=($post['pay_type'] ==1)?"Admin credit":"Admin Debit";
	    $datas['trip_id']=0;
	   
	    $add_logs = $this->Commonmodel->set_driver_cash_transfer($datas);
		
		$passenger_update_array  = array("account_balance"=>round($total_balance,2));
		$update_driver_amount = $this->update_table(PEOPLE,$passenger_update_array,'id',$post['driver_id']);
		
		
		return $result;
	}
	
	public function get_drivers_data($id)
	{
		$result = DB::select('account_balance','name','email','phone','country_code')->from(PEOPLE)
				->where('id','=',$id)
				->execute()
				->current();
		return $result;
		
	}
	
	public function get_drivers_list()
	{
		$result = DB::select('id','name','country_code', 'phone', 'account_balance')->from(PEOPLE)
				->where('user_type','=','D')
				->where('status','=','A')
				->order_by('name','asc')
				->execute()
				->as_array();
				
		return $result;
		
	}
	
	/** function to get passenger wallet logs **/
	public function driverCreditLogs($searchArr, $setPagination=0 , $offset = '', $limit = '')
	{
		$keyword = (isset($searchArr['keyword'])) ? trim(Html::chars($searchArr['keyword'])) : '';
		$startdate = (isset($searchArr['startdate']) && $searchArr['startdate']!="" ) ? Commonfunction::ensureDatabaseFormat($searchArr['startdate'],1) : '';
		$enddate = (isset($searchArr['enddate']) && $searchArr['enddate']!="") ? Commonfunction::ensureDatabaseFormat($searchArr['enddate'],2) : '';
		$limitCnd = "";
		$keyword       = str_replace("%", "!%", $keyword);
        $keyword       = str_replace("_", "!_", $keyword);
		//Database::instance()->escape($keyword);
		if($setPagination == 1){
			$limitCnd = " limit $offset,$limit";
		}
		
		$whereCnd = "";
		if(!empty($keyword)){
			$whereCnd .= " AND (p.name LIKE  '%$keyword%' ";
			$whereCnd .= " or p.lastname LIKE  '%$keyword%' ";
			$whereCnd .= " or CONCAT(p.country_code,p.phone) LIKE '%$keyword%' escape '!' ) ";
		}
		
		if(!empty($company)){
			//$whereCnd .= " AND ".PEOPLE.".company_id = '$company'";
		}
		
		if(!empty($startdate) && !empty($enddate)) {
			$whereCnd .= " AND wlogs.transfer_date between '$startdate' AND '$enddate'";
		} else if(!empty($startdate)){
			$whereCnd .= " AND wlogs.transfer_date >= '$startdate'";
		} else if(!empty($enddate)) {
			$whereCnd .= " AND wlogs.transfer_date <= '$enddate'";
		}
		
		$query = "SELECT wlogs.trip_id,wlogs.receiver_previous_balance as old_balance,wlogs.receiver_after_balance as new_balance,wlogs.comments,p.name,IF(wlogs.transfer_type = 1, 'Credit', 'Debit') as payment_type,wlogs.transfer_amount as amount,wlogs.transfer_date as created_date,p.name,CONCAT(p.country_code,p.phone) as mobile_number FROM ".DRIVER_CASH_TRANSFER." as wlogs JOIN ".PEOPLE." as p ON p.id = wlogs.receiver_id where 1=1 $whereCnd order by wlogs.id desc $limitCnd";
		//echo $query;exit;
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	
	public function count_admintransaction_list($search,$company_id)
	{
		$condition = '';
		
		if(isset($company_id) && $company_id != 0)
		{
			$condition .= " AND pe.company_id = '".$company_id."'"; 
		}
		
		if(isset($search['trip_search_user']) && $search['trip_search_user']=="new")
		{
			if($search['trip_keyword'] !='')
				{
					$condition .= " AND pe.phone = '".$search['trip_keyword']."'"; 
				}
		}
		else
		{
			if(!empty($search))
			{
				if($search['trip_keyword'] !='')
				{
					$condition .= " AND pe.phone = '".$search['trip_keyword']."'"; 
				}
				if(!empty($search['trip_startdate']))
				{
					$condition .= " AND dw.date >= '".$search['trip_startdate']."'"; 
				}
				if(!empty($search['trip_enddate']))
				{
					$condition .= " AND dw.date <= '".$search['trip_enddate']."'"; 
				}
				
				if($search['trip_payment_status'] !="" )
				{
					$condition .= " AND dw.status = '".$search['trip_payment_status']."'"; 
				}
				
				
			}
		}
		$query = " SELECT count(*) as count FROM `".PASSENGERS_LOG."` as pl left join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id left Join `".PEOPLE."` as pe ON pe.id=pl.driver_id left join `".DRIVER_WALLET_TRANS."` as dw ON pl.passengers_log_id=dw.trip_id WHERE pl.travel_status = '1' and pl.driver_reply = 'A' and t.payment_type = 5 and t.fare >0 and dw.type = 2 $condition  order by pl.passengers_log_id desc";
		
		//
		$results = Db::query(Database::SELECT, $query)->execute()->get('count');
		return $results;
	   
	}
	
	public function admintransaction_list($search,$offset,$limit,$company_id)
	{
		$condition = '';
		if(isset($company_id) && $company_id != 0)
		{
			$condition .= " AND pe.company_id = '".$company_id."'"; 
		}
		if(isset($search['trip_search_user']) && $search['trip_search_user']=="new")
		{
			if($search['trip_keyword'] !='')
				{
					$condition .= " AND pe.phone = '".$search['trip_keyword']."'"; 
				}
		}
		else
		{
			
			if(!empty($search))
			{
				if($search['trip_keyword'] !='')
				{
					$condition .= " AND pe.phone = '".$search['trip_keyword']."'"; 
				}
				if(!empty($search['trip_startdate']))
				{
					$condition .= " AND dw.date >= '".$search['trip_startdate']."'"; 
				}
				if(!empty($search['trip_enddate']))
				{
					$condition .= " AND dw.date <= '".$search['trip_enddate']."'"; 
				}
			
				if($search['trip_payment_status'] !="" )
				{
					$condition .= " AND dw.status = '".$search['trip_payment_status']."'"; 
				}		
				
			}
		}
		
		$query = " SELECT dw.id as trip_trans_id,dw.status as transaction_status,t.fare,dw.date as trans_current_date,t.passengers_log_id as trip_id,pe.name,CONCAT('',pe.phone) as mobile_number  FROM `".PASSENGERS_LOG."` as pl left join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id left Join `".PEOPLE."` as pe ON pe.id=pl.driver_id left join `".DRIVER_WALLET_TRANS."` as dw ON pl.passengers_log_id=dw.trip_id  WHERE pl.travel_status = '1' and pl.driver_reply = 'A' and t.payment_type = 5 and t.fare >0 and dw.type = 2 $condition  order by pl.passengers_log_id desc limit $limit offset $offset";
		
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		
		return $results;
	   
	}
	
	
	/** update the wallet request status **/
	public function updateRequestStatus_trip($activeids,$reqSts,$crntTime,$userId)
	{

		if($reqSts == 1) {
			$explode = explode(",",$activeids);
			$result = DB::update(DRIVER_WALLET_TRANS)->set(array('status' => 1, 'update_time' => $crntTime))->where('id', 'IN', $explode)->execute();
			$result = DB::select('amount','driver_id')->from(DRIVER_WALLET_TRANS)->where('id', 'IN', $explode)->execute()->as_array();
			if(count($result) > 0) {
				foreach($result as $r) {
					$wallet_request_amount = $r['amount'];
					$wallet_request_driver = $r['driver_id'];
					$query = "update ".PEOPLE." set trip_wallet = trip_wallet - $wallet_request_amount where id = '$wallet_request_driver'";
					Db::query(Database::UPDATE, $query)->execute();
				}
			}
		} else {
			$explode = explode(",",$activeids);
			$result = DB::update(DRIVER_WALLET_TRANS)->set(array('status' => 2, 'update_time' => $crntTime))->where('id', 'IN', $explode)->execute();
		}
		return $result;
	}
	
	public function get_dashboard_data($search,$company_id)
	{
		$condition="";
		if(isset($company_id) && $company_id != 0)
		{
			$condition .= " AND pe.company_id = '".$company_id."'"; 
		}
		if(!empty($search))
		{
			if(isset($search['keyword']) && $search['keyword'] !='')
			{
				$condition .= " AND pe.phone = '".$search['keyword']."'"; 
			}
			if(!empty($search['startdate']))
			{
				$condition .= " AND dw.date >= '".$search['startdate']."'"; 
			}
			if(!empty($search['enddate']))
			{
				$condition .= " AND dw.date <= '".$search['enddate']."'"; 
			}
		
			if(isset($search['trip_payment_status']) && $search['trip_payment_status'] !="" )
			{
				//$condition .= " AND dw.status = '".$search['trip_payment_status']."'"; 
			}		
			
		}
		//$condition .= " AND pe.phone =1000000003";
		/*
		 	$query = "SELECT if(status != '1' ,sum(amount),0) as tot_amount,if(status = '1' ,sum(amount),0) as paid_amount,if(status = '1' ,count(id),0) as approver_trip,if(status = '2' ,count(id),0) as cancelled_trip,,if(status != '5' ,count(id),0) as total_trip FROM ".DRIVER_WALLET_TRANS." as dw left Join `".PEOPLE."` as pe ON dw.driver_id=pe.id where 1=1 $condition"; */
		
	
		//	print_r($condition); exit;
		
		$query = "SELECT sum(if(dw.status = '2' ,dw.amount,NULL)) as cancelled_amount,sum(if(dw.status = '0' ,dw.amount,NULL)) as pending_amount,sum(if(dw.status = '1' ,dw.amount,NULL)) as paid_amount,count(if(dw.status = 1 ,dw.status,NULL)) as approver_trip,count(if(dw.status = 2 ,dw.status,NULL)) as cancelled_trip,count(if(dw.status = 0 ,dw.status,NULL)) as pending_trip,count(if(dw.status != 5 ,dw.status,NULL)) as total_trip FROM ".DRIVER_WALLET_TRANS." as dw left Join `".PEOPLE."` as pe ON dw.driver_id=pe.id left join `".TRANS."` as t ON dw.trip_id=t.passengers_log_id where t.payment_type = 5 and t.fare >0 and dw.type = 2 $condition";
		
//print_r($query); exit;
		$result =  Db::query(Database::SELECT, $query)
						->execute()
						->as_array();
						
						//print_r($result); exit;		
									 
		return $result;	
	}
	
	
}
