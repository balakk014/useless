<?php defined('SYSPATH') OR die('No Direct Script Access');

/******************************************

* Contains Driver module details

* @Package: Driver

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************/

Class Model_Driver extends Model
{
	/**
	****__construct()**
	*** Common Function in this model
	*/	
	public function __construct()
	{	
		$this->session = Session::instance();	
		$this->name = $this->session->get("name");
		$this->admin_userid = $this->session->get("passenger_id");
		$this->admin_email = $this->session->get("email");
		$this->user_admin_type = $this->session->get("user_type");
		$this->Commonmodel = $Commonmodel = Model::factory('Commonmodel');
		
		//$this->currentdate=Commonfunction::getCurrentTimeStamp();

        $this->lat='';
        $this->lon='';
        if( isset($_SESSION['id']) && ($_SESSION['id']!='') )
        { 
                $this->lat=isset($_SESSION['ip_lati'])?$_SESSION['ip_lati']:LOCATION_LATI;  
                $this->lon=isset($_SESSION['ip_lng'])?$_SESSION['ip_lng']:LOCATION_LONG;                 
        }
        else{ 
                $this->lat=isset($_COOKIE['c_lati'])?$_COOKIE['c_lati']:LOCATION_LATI;    
                $this->lon=isset($_COOKIE['c_lng'])?$_COOKIE['c_lng']:LOCATION_LONG;    
         }
	}
	/**Validating User SignUP details**/

	
	// Check Email Exist or Not while Updating User Details
	public function check_passenger_email_update($email="",$id="")
	{
		$sql = "SELECT email FROM ".PEOPLE." WHERE email='$email' AND id !='$id' ";   
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
 
	/**Validating Login Datas**/
	public function validate_login($arr) 
	{
		
		if($arr['password'] == 'Password'){ $arr['password'] = "";}

		return Validation::factory($arr)       
            ->rule('email','not_empty')
			->rule('email','email')
			->rule('password', 'not_empty')
			->rule('password','min_length',array(':value','5'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'));

	}
	/** Validate Profile Settings **/
	public function validate_driver_profilesettings($arr) 
	{
		return Validation::factory($arr)       
			
		    ->rule('name', 'not_empty')
            //->rule('name','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
			->rule('name', 'min_length', array(':value', '4'))
			->rule('name', 'max_length', array(':value', '32'))
		    ->rule('phone', 'not_empty')
			->rule('phone','numeric') //num
	        ->rule('address', 'not_empty');		
            //->rule('description','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'));						
	}
	/**User Login**/
	public function login($phone,$pwd,$remember) 
	{
		$password = Html::chars(md5($pwd));
		if(COMPANY_CID != 0)
		{
		$query= "SELECT * FROM ".PEOPLE." WHERE phone = '$phone' AND password='$password' AND user_type='D' AND company_id=".COMPANY_CID;
	}else{
		 $query= "SELECT * FROM ".PEOPLE." WHERE phone = '$phone' AND password='$password' AND user_type='D'";
	}
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
			if(count($result) == 1 && ($result[0]['status'] == 'A' || $result[0]['status'] == 'M'))
			{
				//Whenever user logged into the application, Add their IP and other details..
				$currentdate = $this->Commonmodel->getcompany_all_currenttimestamp(COMPANY_CID);
				$login_time = $currentdate;			
				$sql_query = array('last_login'=>$login_time,"login_status"=>"S","login_from"=>"W"); 		                
				$result_login = DB::update(PEOPLE)
								->set($sql_query)
								->where('phone', '=', $phone)
								->execute();
					        $this->session->set("id",$result["0"]["id"]);
					        $this->session->set("email",$result["0"]["email"]);
					        $this->session->set("phone",$result["0"]["phone"]);
					        $this->session->set("name",$result["0"]["name"]);
					        $this->session->set("usertype","driver");
					        if($remember == "yes")
						{
								 setcookie("driver_phone",$phone, time()+3600*24,'/');
								 setcookie("driver_password",$pwd, time()+3600*24,'/');
						}
				
				            return 1;
			}
			elseif(count($result) == 1 && $result[0]['status'] == 'D')
			{
				return -1;
			}
			elseif(count($result) == 1 && $result[0]['status'] == 'T')
			{
				return -3;
			}
			else
			{
				return 0;
			}
	}
	// Updating User Details
	public function update_driverimage($image,$userid)
	{
		$sql="select profile_picture from ".PEOPLE." where id='$userid'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['profile_picture'])){
				$id1 = SITE_DRIVER_IMGPATH.$results[0]['profile_picture'];
				$id2 = SITE_DRIVER_IMGPATH.'thumb_'.$results[0]['profile_picture'];
				if(file_exists($id1) && file_exists($id2)){
					unlink($id1);
					unlink($id2); 
				}
				
			}
		$mdate = $this->Commonmodel->getcompany_all_currenttimestamp(COMPANY_CID);	
		
		$sql_query = (array('updated_date' => $mdate));
	
		if(isset($image)){
			$sql_query[ 'profile_picture' ]=$image ;
		}
		
		$result =  DB::update(PEOPLE)->set($sql_query)
					->where('id', '=' ,$userid)
					->execute();
		return $result;
	}
	
	public function update_user_settings($array_data,$post_value_array,$userid)
	{
		$mdate = $this->Commonmodel->getcompany_all_currenttimestamp(COMPANY_CID);
		
		// Update user records in the database
		$sql_query = (array('updated_date' => $mdate));	 //'location' =>$array_data['location'] ,'industry' =>$array_data['industry'],'smart_tags' => $array_data['smart_tags'],
	
		if(isset($array_data['name']) && $array_data['name'] !=""){
			$name = $array_data['name'];
			$sql_query[ 'name' ]=$name ;
		}

		if(isset($array_data['phone']) && $array_data['phone'] !=""){
			$name = $array_data['phone'];
			$sql_query[ 'phone' ]=$name ;
		}

		if(isset($array_data['address']) && $array_data['address'] !=""){
			$name = $array_data['address'];
			$sql_query[ 'address' ]=$name ;
		}
		
		$result =  DB::update(PEOPLE)->set($sql_query)
					->where('id', '=' ,$userid)
					->execute();

		$sql_query[ 'updated_date' ]=$mdate ;
		
		//echo $sql_query;
		//exit;
         
		$this->session->set("name",$array_data['name']);
		return ($result)?1:0; 
	}

	public function get_driver_profile_details($id="")
	{
		$sql = "SELECT * FROM ".PEOPLE." WHERE id = '$id' ";                      
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}
	// Validating User Details while Updating User Details

	/**Get User Details at User Profile Page**/
	public function get_user_details($userid=null,$location_details=null) //,$offset=0,$rec=0
	{    
        $lat=$this->lat; 
        $lon=$this->lon;

        $rad='';

        $result=array();
        if($userid!=null)
        {
	        if($rad == ""){	

                if( ($lat!=null) && ($lon!=null) )
                {
		            $query="SELECT *,(((acos(sin((".$lat."*pi()/180)) * 
                        sin((`latitude`*pi()/180))+cos((".$lat."*pi()/180)) * 
                        cos((`latitude`*pi()/180)) * cos(((".$lon."- `Langitude`)*
                        pi()/180))))*180/pi())*60*1.1515) as distance FROM ".PEOPLE."  where id = '".$userid."' 
                         ORDER BY `distance` ASC ";	  		 //limit $offset,$rec	
                }else 
                {
                    $query="SELECT * FROM ".PEOPLE."  where id = '".$userid."' ";
                }
	        }
            $result=Db::query(Database::SELECT, $query)
			            ->execute()
			            ->as_array();

            return $result;

        }else { return $result; }
	}
	
	
	// Validating Forgot Password Details
	public function validate_forgotpwd($arr) 
	{
		return Validation::factory($arr)       
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '100'))
			->rule('email', 'not_empty');
	}

	// Check Whether Passenger Email is Already Exist or Not
	public function check_email_driver($email="")
	{

		$sql = "SELECT email FROM ".PEOPLE." WHERE email='$email' and company_id=".COMPANY_CID;   
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
	
	public function check_phone_passengers($phone="")
	{
		if(COMPANY_CID != 0)
		{
			$sql = "SELECT phone FROM ".PEOPLE." WHERE phone='$phone' and company_id=".COMPANY_CID;   
		}
		else
		{
			$sql = "SELECT phone FROM ".PEOPLE." WHERE phone='$phone'";   
		}
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
	
	public function check_driver_phone_update($email="",$id="")
	{
		$sql = "SELECT phone FROM ".PEOPLE." WHERE phone='$email' AND id !='$id' AND company_id=".COMPANY_CID;   
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
	// Reset User Password if User Forgot Password 
	public function forgot_password($array_data,$post_value_array,$random_key)
	{
		$mdate = $this->Commonmodel->getcompany_all_currenttimestamp(COMPANY_CID);
		$pass= md5($random_key);
		// Create a new user record in the database
		$result = DB::update(PEOPLE)->set(array('password' => $pass,'org_password'=>$random_key,'updated_date' => $mdate ))->where('email', '=', $array_data['email'])
		->where('status','=','A')
		->where('company_id','=',COMPANY_CID)
			->execute();
		if($result){
			 $rs = DB::select('name','username','email','password','phone')->from(PEOPLE)
			 			->where('email','=', $post_value_array['email'])
			 			->where('status','=','A')
			 			->where('company_id','=',COMPANY_CID)
						->execute()
						->as_array();
			 return $rs;
		}else{
			return 0;
			}
	}	


	public function forgot_password_phone($array_data,$value,$random_key)
	{
		$mdate = $this->Commonmodel->getcompany_all_currenttimestamp(COMPANY_CID);
		$pass= md5($random_key);
		// Create a new user record in the database
		$result = DB::update(PEOPLE)->set(array('password' => $pass,'org_password'=>$random_key,'updated_date' => $mdate ))->where('phone', '=', $value['phone_no'])
			->execute();
		if($result){
			 $rs = DB::select('name','username','email','password','phone')->from(PEOPLE)
			 			->where('phone','=', $value['phone_no'])
			 			->where('status','=','A')
						->execute()
						->as_array();
			 return $rs;
		}else{
			return 0;
			}
	}	

	// User Change Password
	public function change_password($array_data,$post_value_array,$userid="")
	{
		$userid = (isset($_SESSION['id'])?$_SESSION['id']:"");	
		$mdate = $this->Commonmodel->getcompany_all_currenttimestamp(COMPANY_CID);
		
		$pass=md5($array_data['confirm_password']);
		// Create a new user record in the database
		$result = DB::update(PEOPLE)->set(array('password' => $pass ,'org_password'=>$array_data['confirm_password'],'updated_date' => $mdate ))->where('id', '=', $userid)
			->execute();
			
		if(count($result) == SUCESS)
		{
			$rs = DB::select('name','password','email','phone')->from(PEOPLE)
				->where('id', '=', $userid)
				->execute()
				->as_array();
				
			return $rs;
		}
	}		

	// Validating Change Password Details
	public function validate_changepwd($arr) 
	{ 
		return Validation::factory($arr)       
			->rule('old_password', 'not_empty')
			->rule('old_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('old_password', 'max_length', array(':value', '16'))
			->rule('new_password', 'not_empty')
			->rule('new_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('new_password', 'min_length', array(':value', '5'))
			->rule('new_password', 'max_length', array(':value', '16'))
			->rule('confirm_password', 'not_empty')
			->rule('confirm_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('confirm_password',  'matches', array(':validation', 'new_password', 'confirm_password'))
			->rule('confirm_password', 'min_length', array(':value', '5'))
			->rule('confirm_password', 'max_length', array(':value', '16'));
	}

	/**Validating Reset Password Details **/

	public function validate_resetpwd($arr) 
	{
		return Validation::factory($arr)       
			->rule('new_password', 'not_empty')
			//->rule('new_password','alpha_dash')
			->rule('new_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('new_password', 'max_length', array(':value', '16'))
			->rule('conf_password', 'not_empty')
			//->rule('conf_password','alpha_dash')
			//->rule('conf_password', array(':equals','new_password'))
			->rule('conf_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('conf_password', 'max_length', array(':value', '16'));
	}

	// Check Whether the Eneterd Password is Correct While User Change Password
	public function check_pass($pass="",$userid="")
	{
		$userid = (isset($_SESSION['id'])?$_SESSION['id']:"");	
		$result = DB::select()->from(PEOPLE)
			->where('id', '=', $userid)
			->execute()
			->as_array();
		$pass=md5($pass);
		$password=$result["0"]["password"];
			if($password == $pass)
			{ 
				return 1;
			}
			else
			{
				return 0;		
			}
	}


	
public function get_email($uid="")
	{

		$sql = "SELECT email FROM ".PEOPLE." WHERE id='$uid' ";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return $result["0"]["email"];
			}
			else
			{
				return "";		
			}
	}


	public function get_passenger_details($key){
	
		$result=DB::select()->from(PEOPLE)	
				->where('activation_key','=',$key)
				->execute()
				->as_array();				
					
		return $result;		
	}	
	
	/**
	* Validation rule for fields in email
	*/
	public function validate_email($arr)
	{
		return Validation::factory($arr)
					->rule('email','not_empty')
					->rule('email','max_length',array(':value','50'))
					->rule('email', 'Model_Authorize::check_label_not_empty',array(":value",__('enter_email')))
					->rule('email','email_domain')

					->rule('email','Model_Authorize::unique_email');
	}

	public function logged_user_status()
	{
		$query="SELECT status FROM ".PEOPLE."  where id = '".$this->session->get("id")."' ";
		$result =  Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
			

			if(count($result) == 1 && $result[0]['status'] == 'A')
			{		
			   return 1;
				
			}
			else if(count($result) == 1 && $result[0]['status'] == 'D')
			{
				return -1;
			}
			else
			{
				return 0;
			}
	}

	public function get_smtpdetails()
	{
          $result =  DB::select()->from(SMTP_SETTINGS)
                                     ->limit(1)
									 ->execute()
									 ->as_array();		 
		  return $result;		
	}

	public function get_logged_user_details($userid)
	{
   
   
   		$result=DB::select()->from(PEOPLE)	
					->where("id","=",$userid)
					->and_where("user_type","=","N") //NORMAL

					->execute()
					->as_array(); // print_r($result);exit;   				
		return $result;   
	}
   
	public function check_password_exist($userid)
	{
   
			$query=DB::select('email','name','password')->from(PEOPLE)
								->where('id','=',$userid)
								->execute()->as_array();
								
			if(count($query) > 0){
			return 1;
			}else{
			return 0;
			}	
			
	}
	public function get_my_profile_details($id)
	{
		$sql = "SELECT name,notification_setting,company_id FROM ".PEOPLE." WHERE id = '$id' ";                      
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}

	public function get_my_trips($id)
	{
	
		$sql = "SELECT ps.passengers_log_id,drloc.active_record,ps.current_location,ps.drop_location FROM passengers_log as ps join driver_location_history as drloc on drloc.driver_id = ps.driver_id WHERE ps.driver_id = '$id' and drloc.status='A' and ps.passengers_log_id=drloc.trip_id and ps.travel_status=1 order by drloc.location_hid desc LIMIT 0 , 3"; 
		//echo  $sql;exit;                    
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
		
	}

	public function get_taxi_trips($id)
	{
	
		$sql = "SELECT ps.passengers_log_id,drloc.active_record,ps.current_location,ps.drop_location FROM passengers_log as ps join driver_location_history as drloc on drloc.driver_id = ps.driver_id WHERE ps.taxi_id = '$id' and drloc.status='A' and ps.passengers_log_id=drloc.trip_id and ps.travel_status=1 LIMIT 0 , 3"; 
		//echo  $sql;exit;                    
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
		
	}

	
	
	//Function used to get the get_driver_logs $driver_id,'R','A','1',COMPANY_CID);
	public function get_driver_logs($id,$msg_status,$driver_reply=null,$travel_status=null,$company_id,$start=null,$limit=null)
	{
		if($company_id == '')
		{
				$current_time =	date('Y-m-d H:i:s');
				$start_time = date('Y-m-d').' 00:00:01';
				$end_time = date('Y-m-d').' 23:59:59';
		}	
		else
		{
			$timezone_base_query = "select time_zone from  company where cid='$company_id'"; 
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
		$result = DB::select('name','current_location','drop_location','no_passengers','pickup_time')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
					->limit($start)->offset($limit)
					//->order_by(PASSENGERS.'.id', 'ASC')					
					->order_by(PASSENGERS_LOG.'.passengers_log_id', 'DESC')		
					->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
					->where(PASSENGERS_LOG.'.pickup_time','>=',$start_time)
					->as_object()		
					->execute();									
		             
		return $result;				
	}
	
	//Function used to find the completed logs
	public function get_driver_logs_completed($id,$msg_status,$driver_reply=null,$travel_status=null,$start=null,$limit=null)
	{
		$result = DB::select('*')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					//->join(PEOPLE)->on(PASSENGERS_LOG.'.driver_id','=',PEOPLE.'.id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
					->limit($start)->offset($limit)
					->order_by(PASSENGERS_LOG.'.passengers_log_id', 'desc')					
					->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
					->as_object()		
					->execute();									
		    //print_r($result);           
		return $result;	
	}
	//Function used to getting upcoming trips with first in fisrt
	//Function used to get all driver logs with transactions
	public function get_driver_logs_completed_transaction($id,$msg_status,$driver_reply=null,$travel_status=null,$start=null,$limit=null)
	{
		$result = DB::select(PASSENGERS_LOG.'.passengers_id',PASSENGERS_LOG.'.driver_id',PASSENGERS_LOG.'.taxi_id',PASSENGERS_LOG.'.company_id',PASSENGERS_LOG.'.current_location',PASSENGERS_LOG.'.pickup_latitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.drop_location',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.pickup_longitude',PASSENGERS_LOG.'.drop_latitude',PASSENGERS_LOG.'.drop_longitude',PASSENGERS_LOG.'.approx_distance',PASSENGERS_LOG.'.approx_duration',PASSENGERS_LOG.'.approx_fare',PASSENGERS_LOG.'.pickup_time',PASSENGERS_LOG.'.travel_status',PASSENGERS_LOG.'.driver_reply',PASSENGERS_LOG.'.comments',PASSENGERS_LOG.'.rating',PASSENGERS_LOG.'.driver_comments',PASSENGERS_LOG.'.fixedprice',PASSENGERS_LOG.'.company_tax',PASSENGERS_LOG.'.faretype',PASSENGERS_LOG.'.bookingtype',PASSENGERS_LOG.'.luggage',PASSENGERS_LOG.'.bookby',PASSENGERS_LOG.'.operator_id',TRANS.'.distance',TRANS.'.actual_distance',TRANS.'.fare',TRANS.'.remarks',TRANS.'.payment_type',TRANS.'.amt',TRANS.'.distance',TRANS.'.payment_status',PASSENGERS.'.name')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->join(TRANS,'LEFT')->on(PASSENGERS_LOG.'.passengers_log_id','=',TRANS.'.passengers_log_id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
					->limit($start)->offset($limit)
					->order_by(PASSENGERS_LOG.'.passengers_log_id', 'desc')					
					->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
					->as_object()		
					->execute();									
		   //print_r($result);           
		return $result;	
	}

	//Function used to get all driver logs with transactions
	public function get_taxi_logs_completed_transaction($id,$msg_status,$driver_reply=null,$travel_status=null,$start=null,$limit=null)
	{
		$result = DB::select(PASSENGERS_LOG.'.distance','distance_unit','fare','name','current_location','drop_location','no_passengers','pickup_time', PASSENGERS_LOG.'.passengers_log_id')
					->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->join(TRANS,'LEFT')->on(PASSENGERS_LOG.'.passengers_log_id','=',TRANS.'.passengers_log_id')
					->where(PASSENGERS_LOG.'.taxi_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
					->limit($start)->offset($limit)
					->order_by(PASSENGERS_LOG.'.passengers_log_id', 'desc')					
					->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
					->as_object()		
					->execute();									
		   //print_r($result);           
		return $result;	
	}
	
		
	//Function used to get all driver logs with transactions
	public function get_driver_total_logs_completed_transaction($id,$msg_status,$driver_reply=null,$travel_status=null,$start=null,$limit=null)
	{
		$result = DB::select('*')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->join(TRANS,'LEFT')->on(PASSENGERS_LOG.'.passengers_log_id','=',TRANS.'.passengers_log_id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
					->limit($start)->offset($limit)
					->order_by(PASSENGERS_LOG.'.passengers_log_id', 'desc')					
					->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
					->as_object()		
					->execute();									
		    //print_r($result);           
		return $result;	
	}
	
	//Function used to get the get_driver_logs
	public function get_driver_logs1($id,$msg_status,$driver_reply=null,$travel_status=null,$start=null,$limit=null,$find_count=false)
	{
		if($find_count==TRUE){
			$result = DB::select('id')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
					->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
					->where(PASSENGERS_LOG.'.rating','!=','0')
					->order_by(PASSENGERS_LOG.'.passengers_log_id', 'DESC')
					->as_object()		
					->execute();									
			return $result;
		}else{
			$result = DB::select('id','rating','comments','passengers_log_id','profile_image','name','createdate')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
					->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
					->where(PASSENGERS_LOG.'.rating','!=','0')
					->order_by(PASSENGERS_LOG.'.passengers_log_id', 'DESC')			
					->limit($start)->offset($limit)
					->as_object()		
					->execute();									
			return $result;		
		}			
	}
	// Function used to get Drivers comments Today and Totally
	public function get_driver_comments($id,$today = null,$company_id='')
	{
		if($company_id == '')
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


		if($today == 1)	
			// It will take the today transaction too
			$result = DB::select('rating','fare')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->join(TRANS)->on(PASSENGERS_LOG.'.passengers_log_id','=',TRANS.'.passengers_log_id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					//->where(PASSENGERS_LOG.'.comments','!=','')
					->where(PASSENGERS_LOG.'.travel_status','=',1)
					->where(PASSENGERS_LOG.'.pickup_time','LIKE',$date)
					->order_by(PASSENGERS.'.id', 'desc')					
					
					->as_object()
					->execute();
			
		else	
			$result = DB::select('name','rating','comments')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					//->where(PASSENGERS_LOG.'.comments','!=','')
					->where(PASSENGERS_LOG.'.travel_status','=',1)
					->order_by('id', 'desc')										
					->as_object()
					->execute();
		      // print_r($result);        
		return $result;	
	}
	/*** Get Passenger Profile details using passenger log id ***/
	public function get_passenger_log_details($passengerlog_id="")
	{
				$sql = "SELECT * , ".COMPANY.".cid as get_companyid, ".PEOPLE.".name AS driver_name,".PEOPLE.".phone AS driver_phone, ".PASSENGERS.".discount AS passenger_discount, ".PASSENGERS.".name AS passenger_name,".PASSENGERS.".email AS passenger_email,".PASSENGERS.".creditcard_no AS creditcard_no,".PASSENGERS.".phone AS passenger_phone FROM  ".PASSENGERS_LOG." JOIN  ".COMPANY." ON (  ".PASSENGERS_LOG.".`company_id` =  ".COMPANY.".`cid` ) JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$passengerlog_id'";
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}
	/*** Get Passenger Profile details with tranaction using passenger log id ***/
	public function get_passenger_log_tranaction_details($passengerlog_id="") 
	{
				$sql = "SELECT ".PASSENGERS_LOG.".current_location,".PASSENGERS_LOG.".drop_location,".PASSENGERS_LOG.".no_passengers,".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".rating,  ".PEOPLE.".name AS driver_name,".PEOPLE.".phone AS driver_phone,  ".PASSENGERS.".name AS passenger_name,".PASSENGERS.".email AS passenger_email,".PASSENGERS.".phone AS passenger_phone,".TRANS.".distance,".TRANS.".actual_distance,".TRANS.".fare,".TRANS.".waiting_time,".TRANS.".waiting_cost,".TRANS.".remarks FROM  ".PASSENGERS_LOG." JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) JOIN  ".TRANS." ON (  ".TRANS.".`passengers_log_id` =  ".PASSENGERS_LOG.".`passengers_log_id` ) 
WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$passengerlog_id'";
//echo $sql;
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}	
	/*** Get Taxi fare per KM & Waiting charge of the company Based on Taxi***/
	public function get_taxi_fare_waiting_charge($taxi_id="")
	{
				$sql = "SELECT * FROM  ".TAXI." JOIN  ".COMPANY." ON (  ".COMPANY.".`cid` =  ".TAXI.".`taxi_company` ) WHERE  ".TAXI.".`taxi_id` =  '$taxi_id'";
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}
	/*** Get Taxi fare per KM & Waiting charge of the company based Company***/
	public function get_taxi_fare_details($company_id="")
	{
				$sql = "SELECT * FROM  ".COMPANY."  WHERE  `cid` =  '$company_id'";
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}	

	/*** Get Taxi Model***/
	public function get_taxi_model_details($taxi_id="")
	{
				$sql = "SELECT * FROM  ".TAXI."  WHERE  `taxi_id` =  '$taxi_id'";
				$result = Db::query(Database::SELECT, $sql)					
					->as_object()		
					->execute();											               
				return $result;		
	}

	/*** Get Taxi fare per KM & Waiting charge of the company based Company***/
	public function get_model_fare_details($model_id="",$search_city="",$company_id='')
	{
		if($search_city!='')
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".city_name like '%".$search_city."%' limit 0,1"; 
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

		if(FARE_SETTINGS == 2)
		{
			$sql = "SELECT (SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km,company_model_fare.night_charge,company_model_fare.night_timing_from,company_model_fare.night_timing_to,company_model_fare.night_fare,
company_model_fare.waiting_time,company_model_fare.min_km,company_model_fare.below_above_km FROM  ".COMPANY_MODEL_FARE." as company_model_fare WHERE company_model_fare.company_cid='$company_id' and company_model_fare.`model_id` = '$model_id'";
		}
		else
		{
			$sql = "SELECT (SUM(model.base_fare)*($city_model_fare)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*($city_model_fare)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*($city_model_fare)/100) + model.below_km as below_km,(SUM(model.above_km)*($city_model_fare)/100) + model.above_km as above_km,model.night_charge,model.night_timing_from,model.night_timing_to,model.night_fare,model.waiting_time,model.min_km,model.below_above_km FROM  ".MOTORMODEL." as model WHERE  model.`model_id` = '$model_id'";
		}
		
				$result = Db::query(Database::SELECT, $sql)
					->as_object()					
					->execute();						

				return $result;		
	}	

	public function get_citymodel_fare_details($model_id="",$search_city="",$company_id='')
	{

		if($search_city!='')
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".city_id ='".$search_city."' limit 0,1"; 
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

		if(FARE_SETTINGS == 2)
		{
			$sql = "SELECT (SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km,company_model_fare.night_charge,company_model_fare.night_timing_from,company_model_fare.night_timing_to,(SUM(company_model_fare.night_fare)*($city_model_fare)/100) + company_model_fare.night_fare as night_fare,
company_model_fare.waiting_time,company_model_fare.min_km,company_model_fare.below_above_km FROM  ".COMPANY_MODEL_FARE." as company_model_fare WHERE company_model_fare.company_cid='$company_id' and company_model_fare.`model_id` = '$model_id'";
		}
		else
		{
			$sql = "SELECT (SUM(model.base_fare)*($city_model_fare)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*($city_model_fare)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*($city_model_fare)/100) + model.below_km as below_km,(SUM(model.above_km)*($city_model_fare)/100) + model.above_km as above_km,model.night_charge,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*($city_model_fare)/100) + model.night_fare as night_fare,model.waiting_time,model.min_km,model.below_above_km  FROM  ".MOTORMODEL." as model WHERE  model.`model_id` = '$model_id'";
		}
		//echo $sql;
				$result = Db::query(Database::SELECT, $sql)
					->as_object()					
					->execute();						

				return $result;		
	}	


	//Function used to get the get_driver_logs
	public function get_driver_logs_ajax($id,$msg_status,$driver_reply=null)
	{
		$current_date = date('Y-m-d '.'00:00:01');
		$result = DB::select('*')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)					
					->order_by('id', 'ASC')					
					->where(PASSENGERS_LOG.'.travel_status','!=',8)
					->where(PASSENGERS_LOG.'.pickup_time','>=',$current_date)
					->as_object()		
					->execute();									
         
		return $result;	
			
	}
	//Get Driver Current Status if he is break,Avtive,Free
	public function get_driver_current_status($id)
	{
		$result = DB::select('update_date','latitude','longitude','status')->from(DRIVER)
					->where(DRIVER.'.driver_id','=',$id)				
					->order_by('id', 'ASC')					
					->as_object()		
					->execute();									
		//print_r($result);              
		return $result;	
			
	}
	
	public function get_shift_status($id)
	{
		$result = DB::select('*')->from(DRIVERSHIFTSERVICE)
					->where(DRIVERSHIFTSERVICE.'.driver_id','=',$id)				
					->order_by('driver_shift_id', 'ASC')
					 ->limit(1)
					->execute()
					->as_array();					
									
		return $result;	

	}
	/** Driver Current Travel Availability **/
	public function check_driver_travel_availability($driver_id,$pickup_time)
	{
		/*$sql = "SELECT * FROM ".PASSENGERS_LOG." WHERE `pickup_time` < '".$pickup_time."'  and `driver_id` = '".$driver_id."' and `driver_reply` = 'A' and `travel_status` != 1 order by passengers_log_id desc limit 1 "; */
				
		$condition = "AND ".PASSENGERS_LOG.".pickup_time >='".date('Y-m-d 00:00:01')."'";
		
		$sql = "SELECT current_location,drop_location,passengers_log_id,msg_status,driver_reply,current_location,drop_location FROM ".PASSENGERS_LOG." WHERE `pickup_time` < '".$pickup_time."'  and `driver_id` = '".$driver_id."' and `driver_reply` = 'A' and (`travel_status` = 2)  $condition order by passengers_log_id desc limit 1 ";
	//	echo $sql;
		$availablity = Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 
			
		return $availablity	;
	}
	//Update Driver Break Status
	public function update_driver_break_status($id,$status,$stat = null)
	{
		if($status == 1)
		{
			$break_status = $stat;
		}
		else
		{
			$break_status = 'F';
		}
		$sql_query = array(
					'status' => $break_status,
				);
		DB::update(DRIVER)->set($sql_query)
				->where('driver_id', '=' ,$id)
				->execute();
	}	
	
	//Update Driver Shift Status
	public function update_driver_shift_status($id,$status,$stat = null)
	{
		if($status == 1)
		{
			$shift_status = 'IN';
			$notification = 1;
		}
		else
		{
			$shift_status = 'OUT';
			$notification = 0;
		}
		$sql_query = array(
					'shift_status' => $shift_status,
				);
        $notification_query = array(
					'notification_setting' => $notification,
				);					
		DB::update(DRIVER)->set($sql_query)
				->where('driver_id', '=' ,$id)
				->execute();
		DB::update(PEOPLE)->set($notification_query)
				->where('id', '=' ,$id)
				->execute();
	}

	//Update Driver Status
	public function update_driver_status($id,$status,$field,$flag)
	{
		$data = DB::select('*')->from(PASSENGERS_LOG)
					->where(PASSENGERS_LOG.'.passengers_log_id','=',$id)
					->as_object()		
					->execute();		
		foreach($data as $values)
		{
			$driver_reply = $values->driver_reply;
		}
		//Acceptred Status
		if($status == 'A')
		{						
			$sql_query = array(
					'driver_reply' => $status,
					'time_to_reach_passen' => $field,
					'travel_status' => '9',
					'msg_status' =>'R',
					
				);					
		}
		//Rejected Status and Adding the Driver Comments 
		else
		{
			$sql_query = array(
					'driver_reply' => $status,
					'driver_comments' => $field,
					'travel_status' => '10',
					'msg_status' =>'R'
				);			
		}
 
		if($driver_reply=='')
		{
			DB::update(PASSENGERS_LOG)->set($sql_query)
				->where('passengers_log_id', '=' ,$id)
				->execute();
				
			if($status == 'A')
				return 1;
			else if($status == 'R')
				return 2;
			else if($status == 'C')		
				return 3;
		}
		else
		{	
			// Driver cancel the drip when pick up
			if($flag == 1)
			{
					$sql_query = array(
					'travel_status' => '10',
					'driver_reply' => $status,
					'driver_comments' => $field,
					'msg_status' =>'R'
				);	
				DB::update(PASSENGERS_LOG)->set($sql_query)
				->where('passengers_log_id', '=' ,$id)
				->execute();		
				if($status == 'R')
				return 2;
			else if($status == 'C')		
				return 3;
			}
			else
			{
				return 0;
			}
		}
			
	}
	
	//Function used to get the Manager Details
	public function get_manager_details($login_city,$login_state,$login_country,$company_id)
	{
		$data = DB::select('*')->from(PEOPLE)
					->where(PEOPLE.'.login_city','=',$login_city)
					->where(PEOPLE.'.login_state','=',$login_state)
					->where(PEOPLE.'.login_country','=',$login_country)
					->where(PEOPLE.'.company_id','=',$company_id)
					->where(PEOPLE.'.user_type','=','M')
					->as_object()		
					->execute();
		if(count($data)>0)
		{
			$result = $data;	
		}
		else
		{
		$result = DB::select('*')->from(PEOPLE)
					->join(COMPANY)->on(COMPANY.'.userid','=',PEOPLE.'.id')
					->where(COMPANY.'.cid','=',$company_id)
					->as_object()		
					->execute();				
		}
		return $result;
	}
	
	public function get_assignedtaxi_list($driver_id='')
	{

		$current_time = convert_timezone('now',TIMEZONE);
		$current_date = explode(' ',$current_time);
		$start_time = $current_date[0].' 00:00:01';
		$end_time = $current_date[0].' 23:59:59';
		
		$query = " select * from " . TAXIMAPPING . " left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id where mapping_driverid='$driver_id' AND mapping_startdate <=  '".$end_time."'
AND mapping_enddate >= '".$start_time."' and mapping_enddate >= '$current_time' order by mapping_startdate ASC"; 


 		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();		

		return $result;
	}
	
	
	public function get_assignedtaxi_alllist($driver_id='',$company_id)
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
			$company_condition = " AND mapping_companyid = '$company_id'";
		}
		
		$query = " select * from " . TAXIMAPPING . " left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id where mapping_driverid='$driver_id' and mapping_enddate >= '".$current_time."' $company_condition order by mapping_startdate ASC"; 
///echo $query;
 		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();		

		return $result;
	}
	

	
	//Update the Journey Status
	public function update_journey_status($id,$msg_status,$driver_reply,$travel_status)
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
	
	public function get_trans_of_driver($id,$limit,$days_ago='',$cur_day='')
	{
		$d_day_ago=date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 7, date("Y")));
		$d_cur_day=(date('Y-m-d'));
		if(($days_ago == '')&&($cur_day == ''))
		{
			$start = $d_day_ago;
			$end = $d_cur_day;
		}else
		{
			$start = $days_ago;
			$end = $cur_day;
		}
		 $query = "SELECT sum(t.`fare`) as fare,DATE_FORMAT(log.`pickup_time`,'%d') as date,DATE_FORMAT(log.`pickup_time`,'%M') as month  FROM ".PASSENGERS_LOG." as log LEFT JOIN ".TRANS." as t on log.`passengers_log_id`=t.`passengers_log_id` WHERE log.`driver_id` = ".$id." AND log.`travel_status` = 1 AND log.`pickup_time` BETWEEN '".$start." 00:00:01' AND '".$end." 23:59:59' group by DATE(log.`pickup_time`) limit 0,$limit ";
		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();		

		return $result;
	}

	public function get_trans_of_taxi($id,$limit,$days_ago='',$cur_day='')
	{     
		$d_day_ago=date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 7, date("Y")));
		$d_cur_day=(date('Y-m-d'));
		if(($days_ago == '')&&($cur_day == '')){
			$start = $d_day_ago;
			$end = $d_cur_day;
		}else
		{
			$start = $days_ago;
			$end = $cur_day;
		}
		$query = "SELECT round(sum(t.`fare`)) as fare,count(t.`fare`) as trips,DATE_FORMAT(log.`pickup_time`,'%d') as date,DATE_FORMAT(log.`pickup_time`,'%M') as month  FROM ".PASSENGERS_LOG." as log LEFT JOIN ".TRANS." as t on log.`passengers_log_id`=t.`passengers_log_id` WHERE log.`taxi_id` = ".$id." AND log.`travel_status` = 1 AND log.`pickup_time` BETWEEN '".$start."' AND '".$end."' group by DATE(log.`pickup_time`) limit 0,$limit ";
		//echo $query;exit;
		$result = Db::query(Database::SELECT, $query)
			 ->execute()
			 ->as_array();		

		return $result;
	}


	//Function used to get all driver break and service logs 
	public function count_get_driver_logs_service($id)
	{
	
		$query = " select * from " . DRIVERBREAKSERVICE . " join " .TAXI. " on ".DRIVERBREAKSERVICE.".taxi_id =".TAXI.".taxi_id where driver_id='$id' order by driver_break_service_id DESC"; 
		//echo $query;
		$result = Db::query(Database::SELECT, $query)
   			  ->as_object()
   			  ->execute();	

		return count($result);	
	}	
					
	public function get_driver_logs_service($id,$start,$limit)
	{
	
			$query = " select * from " . DRIVERBREAKSERVICE . " join " .TAXI. " on ".DRIVERBREAKSERVICE.".taxi_id =".TAXI.".taxi_id where driver_id='$id' order by driver_break_service_id DESC limit $start offset $limit"; 

		$result = Db::query(Database::SELECT, $query)
   			  ->as_object()
   			  ->execute();	
							
		    //print_r($result);           
		return $result;	
	}	

	//Function used to get all Shift logs
	public function count_get_driver_shift_logs($id)
	{
	
		$query = " SELECT count('driver_shift_id') as count FROM ".DRIVERSHIFTSERVICE." JOIN ".PEOPLE." ON ".PEOPLE.".id = ".DRIVERSHIFTSERVICE.".driver_id WHERE driver_id =  '".$id."' "; 
		//echo $query;
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();			
		return !empty($result) ? $result[0]['count'] : 0;	
	}
		
	public function get_driver_shift_logs($id,$start,$limit)
	{
	
		$query = "SELECT driver_id,reason,shift_start,shift_end,".TAXI.".taxi_no,".TAXI.".taxi_id FROM ".DRIVERSHIFTSERVICE." join " .TAXI. " on ".DRIVERSHIFTSERVICE.".taxi_id =".TAXI.".taxi_id WHERE driver_id =  '".$id."' ORDER BY driver_shift_id DESC LIMIT $start offset $limit";		
		//echo $query;
		$result = Db::query(Database::SELECT, $query)
   			  ->as_object()
   			  ->execute();	
							
		    //print_r($result);           
		return $result;	
	}
	
	//Function used to get all taxi logs with transactions
	
	public function count_get_taxi_logs_service($id)
	{
	
		$query = " select count(driver_break_service_id) as count from " . DRIVERBREAKSERVICE . " join " .PEOPLE. " on ".DRIVERBREAKSERVICE.".driver_id =".PEOPLE.".id where ".DRIVERBREAKSERVICE.".taxi_id='$id' and ".PEOPLE.".user_type='D' order by driver_break_service_id DESC"; 

		$result = Db::query(Database::SELECT, $query)
				  ->execute()
				  ->as_array();	
		return !empty($result) ? $result[0]['count'] : 0;				
		//return count($result);	
	}
		
	public function get_taxi_logs_service($id,$start,$offset)
	{	
		$query = " select name,interval_type,interval_start,interval_end,reason from " . DRIVERBREAKSERVICE . " join " .PEOPLE. " on ".DRIVERBREAKSERVICE.".driver_id =".PEOPLE.".id where ".DRIVERBREAKSERVICE.".taxi_id='$id' and ".PEOPLE.".user_type='D' order by driver_break_service_id DESC limit $start offset $offset"; 

		$result = Db::query(Database::SELECT, $query)
   			  ->as_object()
   			  ->execute();								
		return $result;	
	}
	
	
	public function getTaxiforDriver($id)
	{
		$current_time = convert_timezone('now',TIMEZONE);

		$query = "select mapping_taxiid from ".TAXIMAPPING." where mapping_status='A' and mapping_driverid='".$id."' AND '$current_time'  between mapping_startdate  and  mapping_enddate order by mapping_startdate DESC"; 
		//echo $query;exit;
 		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();		

		return $result;
	}
	
	public function get_driver_earnings($driver_id)
	{
		$query = "select *, sum(fare) as total_amount from ".PASSENGERS_LOG." join 
		".TRANS." on ".PASSENGERS_LOG.".passengers_log_id = ".TRANS.".passengers_log_id 
		where ".PASSENGERS_LOG.".driver_id='$driver_id' and ".PASSENGERS_LOG.".travel_status='1'";
			$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();		

		return $result;
	}

	public function set_trans_commissiondetails($passenger_logid,$total_fare)
	{

		$passengerlog_query = "select * from " . PASSENGERS_LOG . " where passengers_log_id='$passenger_logid'";

		$passengerlog_results = Db::query(Database::SELECT, $passengerlog_query)
				->execute()
				->as_array();

		$company_id = $passengerlog_results[0]['company_id'];
		$trans_commission = array();

			/** Commission Part **/		
	
			$first_query = "select * from " . PACKAGE_REPORT . " join ".PACKAGE." on ".PACKAGE.".package_id =".PACKAGE_REPORT.".upgrade_packageid  where ".PACKAGE_REPORT.".upgrade_companyid = ".$company_id."  order by upgrade_id desc limit 0,1";

		$first_results = Db::query(Database::SELECT, $first_query)
				->execute()
				->as_array();

			if(count($first_results) > 0)
			{
				$check_package_type = $first_results[0]['check_package_type'];
				$package_id = $first_results[0]['upgrade_packageid'];
			}
			else
			{
				$check_package_type = 'T';
				$package_id = '';

			}

			if($check_package_type != 'N')
			{
				$admin_amt = ($total_fare * ADMIN_COMMISSON)/100; //payable to admin
				$admin_amt = round($admin_amt, 2);
				$total_balance = round($total_fare,2);				

				//Set Commission to Admin	
				$updatequery = " UPDATE ". PEOPLE ." SET account_balance=account_balance+$admin_amt wHERE user_type = 'A'";	

				$updateresult = Db::query(Database::UPDATE, $updatequery)
						->execute();
			
			}
			else
			{
				$admin_amt = 0;
			}

			//$company_amt = $total_fare - $admin_amt; 	
			$company_amt = $total_fare;
			$company_amt = round($company_amt, 2);	

			$trans_commission['admin_commission'] = $admin_amt;
			$trans_commission['company_commission'] = $company_amt;
			$trans_commission['package_type'] = $check_package_type;

		     	//Set Commission to Admin	
			$updatequery = " UPDATE ". PEOPLE ." SET account_balance=account_balance+$company_amt WHERE user_type = 'C' and company_id=".$company_id;	

			$updateresult = Db::query(Database::UPDATE, $updatequery)
			->execute();

			/** Commission Part **/		

			return $trans_commission;
	}


	public function driver_companystatus($user_id)
	{		
		$query= "SELECT * FROM ".PEOPLE." WHERE id='$user_id'";
		$result =  Db::query(Database::SELECT, $query)
			->execute()
			->as_array();


		if(count($result) > 0)
		{
			$company_id = $result[0]['company_id'];

			$query= "SELECT company_status FROM ".COMPANY." WHERE cid='$company_id'";
			$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();

			if(count($result) > 0)
			{
				return $result[0]['company_status'];	
			}
			else
			{
				$result[0]['company_status'] = 'A';
				return $result[0]['company_status'];
			}

		}
		else
		{
			$result[0]['company_status'] = 'A';

			return $result[0]['company_status'];
		}	

	}

	public function get_company_ownerid($company_id)
	{
			$query= "SELECT id FROM ".PEOPLE." WHERE company_id='$company_id' and user_type='C'";
			$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();

			return $result;
	}
	
	public function get_trip_statitics($driver_id)
	{
		$start_date=date('Y-m-d', mktime(0, 0, 0, date("m") , date("d")-7, date("Y")));
		$end_date=(date('Y-m-d'));
		$rejected_query= "SELECT `createdate` , count(`createdate`) as rejected_count FROM ".DRIVER_REJECTION." where driver_id = $driver_id and `createdate` between '$start_date 00:00:01' and '$end_date 23:59:59'  group by DATE(`createdate`)";
		$rejected_trips =  Db::query(Database::SELECT, $rejected_query)
			->execute()
			->as_array();
			
		/*$cancelled_query= "SELECT `createdate` , count(`createdate`) as cancelled_count FROM ".PASSENGERS_LOG." where `driver_reply`='C' and `driver_id` = '$driver_id' and `createdate` between '$start_date 00:00:01' and '$end_date 23:59:59'  group by DATE(`createdate`)";
		$cancelled_trips =  Db::query(Database::SELECT, $cancelled_query)
			->execute()
			->as_array();
			
		$completed_query= "SELECT `createdate` , count(`createdate`) as completed_count FROM ".PASSENGERS_LOG." where `driver_reply`='A' and `driver_id` = '$driver_id' and `travel_status` = '1' and `createdate` between '$start_date 00:00:01' and '$end_date 23:59:59'  group by DATE(`createdate`)";
		$completed_trips =  Db::query(Database::SELECT, $completed_query)
			->execute()
			->as_array();*/
		$completed_trips = $cancelled_trips = $cum_arr = array();
		$trips = "SELECT DATE(createdate) as date, travel_status, createdate, driver_reply, count(createdate) as count FROM ".PASSENGERS_LOG." where driver_id = '$driver_id' and createdate between '$start_date 00:00:01' and '$end_date 23:59:59'  group by driver_reply, travel_status, DATE(createdate)";
		
		$trips_query =  Db::query(Database::SELECT, $trips)
							->execute()
							->as_array();
		
		foreach($trips_query as $t){			
			if($t['driver_reply'] == 'C'){				
				$cancelled_trips[] = array('createdate' => $t['createdate'], 'cancelled_count' => $t['count']);
			}
			if($t['driver_reply'] == 'A' && $t['travel_status'] == 1){
				$completed_trips[] = array('createdate' => $t['createdate'], 'completed_count' => $t['count']);
			}
		}		
		$result=array('completed_trips'=>$completed_trips,'rejected_trips'=>$rejected_trips,'cancelled_trips'=>$cancelled_trips);
		return $result;
	}

	public function get_current_trip_logs($id)
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
		
		$query= "SELECT approx_distance,approx_fare,passengers_log_id,distance,".PASSENGERS_LOG.".company_id,current_location,drop_location
				FROM ".PASSENGERS_LOG."
				LEFT JOIN ".PEOPLE." on ".PEOPLE.".id=".PASSENGERS_LOG.".driver_id
				WHERE ".PASSENGERS_LOG.".driver_id='$id'
				and `pickup_time` >= '".$start_time."'
				and `pickup_time` <= '".$end_time."'
				and  driver_reply='A'
				and (travel_status = 2 or travel_status = 5)
				order by ".PASSENGERS_LOG.".passengers_log_id desc
				limit 1";

		$result =  Db::query(Database::SELECT, $query)
			->execute()
			->as_array();

		return $result;
	}
	//** function to get total transaction count of a taxi **//
	public function get_total_trans_taxi($id)
	{
		 $query = "SELECT count(log.`passengers_log_id`) as total  FROM ".PASSENGERS_LOG." as log LEFT JOIN ".TRANS." as t on log.`passengers_log_id`=t.`passengers_log_id` WHERE log.`taxi_id` = ".$id." AND log.`travel_status` = 1 group by DATE(log.`pickup_time`)";
		//echo $query;exit;
		$result = Db::query(Database::SELECT, $query)->execute()->get('total');		

		return $result;
	}
	//** function to get total transaction count of a driver **//
	public function get_total_trans_driver($id)
	{
		 $query = "SELECT count(log.`passengers_log_id`) as total  FROM ".PASSENGERS_LOG." as log LEFT JOIN ".TRANS." as t on log.`passengers_log_id`=t.`passengers_log_id` WHERE log.`driver_id` = ".$id." AND log.`travel_status` = 1 group by DATE(log.`pickup_time`)";
		//echo $query;
		$result = Db::query(Database::SELECT, $query)->execute()->get('total');
		return $result;
	}
	//** function to get total ratings of a driver **//
	public function get_total_ratings_driver($id)
	{
		 $query = "SELECT sum(log.`rating`) as total_ratings, count(log.`passengers_log_id`) as trip_cnt FROM ".PASSENGERS_LOG." as log LEFT JOIN ".TRANS." as t on log.`passengers_log_id`=t.`passengers_log_id` WHERE log.`driver_id` = ".$id." AND log.`travel_status` = 1 and log.`rating`!=0 group by DATE(log.`pickup_time`)";
		//echo $query;
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $result;
	}
	//** function to get overall trip statistics count **//
	public function getoverall_trip_statitics_count($driver_id)
	{
		$rejected_query= "SELECT count(`createdate`) as rejected_count FROM ".DRIVER_REJECTION." where driver_id = $driver_id group by DATE(`createdate`)";
		$rejected_trips =  Db::query(Database::SELECT, $rejected_query)->execute()->get('rejected_count');
			
		/*$cancelled_query= "SELECT count(`createdate`) as cancelled_count FROM `passengers_log` where `driver_reply`='C' and `driver_id` = '$driver_id' group by DATE(`createdate`)";
		$cancelled_trips =  Db::query(Database::SELECT, $cancelled_query)->execute()->get('cancelled_count');
			
		$completed_query= "SELECT count(`createdate`) as completed_count FROM `passengers_log` where `driver_reply`='A' and `driver_id` = '$driver_id' and `travel_status` = '1' group by DATE(`createdate`)";
		$completed_trips =  Db::query(Database::SELECT, $completed_query)->execute()->get('completed_count');*/
		
		$trips= "SELECT DATE(createdate) as date, travel_status, driver_reply, count(createdate) as count FROM ".PASSENGERS_LOG." where driver_id = '$driver_id' group by travel_status, driver_reply, DATE(createdate) order by DATE(createdate) ASC";
		$trips_query =  Db::query(Database::SELECT, $trips)->execute()->as_array();

		$cancelled_trips = $completed_trips =0;
		foreach($trips_query as $t){			
			if($t['driver_reply'] == 'C'){				
				$cancelled_trips++;
			}
			if($t['driver_reply'] == 'A' && $t['travel_status'] == 1){
				$completed_trips++;
			}
		}
		
		if($rejected_trips == 0 && $cancelled_trips == 0 && $completed_trips == 0) {
			return 0;
		} else {
			return 1;
		}
	}
	
	/** 
	 * Driver Referral List
	 **/
	
	public function get_driver_referral_list($id,$start,$record,$con)
	{
		$limit = "";
		if($con) {
			$limit .= "limit $start offset $record";
		}
		$query = " SELECT name,registered_driver_code,referred_driver_id,registered_driver_id,registered_driver_wallet,referral_status,createdate FROM ".DRIVER_REF_DETAILS." JOIN ".PEOPLE." ON ".PEOPLE.".id = ".DRIVER_REF_DETAILS.".registered_driver_id WHERE referred_driver_id =  '".$id."' ORDER BY createdate DESC $limit";
		
		$result = Db::query(Database::SELECT, $query)
					->as_object()
					->execute();
		return $result;
	}
	
	public function driver_logs_progress_upcoming($id,$msg_status,$driver_reply=null,$company_id)
	{	
		if($company_id == '')
		{
				$current_time =	date('Y-m-d H:i:s');
				$start_time = date('Y-m-d').' 00:00:01';
				$end_time = date('Y-m-d').' 23:59:59';
		}	
		else
		{
			$timezone_base_query = "select time_zone from  company where cid='$company_id'"; 
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
		$result = DB::select(PASSENGERS_LOG.'.travel_status','name','current_location','drop_location','no_passengers','pickup_time','passengers_log_id')->from(PASSENGERS_LOG)
					->join(PASSENGERS)->on(PASSENGERS_LOG.'.passengers_id','=',PASSENGERS.'.id')
					->where(PASSENGERS_LOG.'.driver_id','=',$id)
					->where(PASSENGERS_LOG.'.msg_status','=',$msg_status)
					->where(PASSENGERS_LOG.'.driver_reply','=',$driver_reply)
					//->order_by(PASSENGERS.'.id', 'ASC')					
					->order_by(PASSENGERS_LOG.'.passengers_log_id', 'DESC')		
					//->where(PASSENGERS_LOG.'.travel_status','=',$travel_status)
					->where(PASSENGERS_LOG.'.pickup_time','>=',$start_time)
					->group_by(PASSENGERS_LOG.'.travel_status')
					->as_object()
					->execute();									
		
		return !empty($result) ? $result : array();
	}

	/*** Get Taxi Model***/
	public function get_taxi_model_info($taxi_id="")
	{
				$sql = "SELECT taxi_model FROM  ".TAXI."  WHERE  `taxi_id` =  '$taxi_id'";
				$result = Db::query(Database::SELECT, $sql)					
					->execute()			
					->as_array(); 								               
				return $result;		
	}

	//get trip details
	public function get_passenger_log($trip_id)
	{
		$sql = "SELECT ".PEOPLE.".phone,".PASSENGERS_LOG.".booking_key,".PASSENGERS_LOG.".passengers_id,".PASSENGERS_LOG.".driver_id,".PASSENGERS_LOG.".taxi_id,
		".PASSENGERS_LOG.".company_id,".PASSENGERS_LOG.".current_location,".PASSENGERS_LOG.".pickup_latitude, ".PASSENGERS_LOG.".pickup_longitude,".PASSENGERS_LOG.".now_after, IFNULL(".PASSENGERS_LOG.".drop_location,0) as drop_location,".PASSENGERS_LOG.".drop_latitude,".PASSENGERS_LOG.".drop_longitude,".PASSENGERS_LOG.".no_passengers,".PASSENGERS_LOG.".taxi_modelid, 
		".PASSENGERS_LOG.".approx_distance,".PASSENGERS_LOG.".approx_duration,".PASSENGERS_LOG.".approx_fare,".PASSENGERS_LOG.".fixedprice,".PASSENGERS_LOG.".promocode,".PASSENGERS_LOG.".time_to_reach_passen,
		".PASSENGERS_LOG.".pickup_time,".PASSENGERS_LOG.".actual_pickup_time,".PASSENGERS_LOG.".drop_time,".PASSENGERS_LOG.".account_id,".PASSENGERS_LOG.".accgroup_id,".PASSENGERS_LOG.".pickupdrop,
		".PASSENGERS_LOG.".rating,".PASSENGERS_LOG.".comments,".PASSENGERS_LOG.".travel_status,".PASSENGERS_LOG.".driver_reply,
		".PASSENGERS_LOG.".msg_status,".PASSENGERS_LOG.".createdate,".PASSENGERS_LOG.".booking_from,".PASSENGERS_LOG.".search_city,".PASSENGERS_LOG.".used_wallet_amount,
		".PASSENGERS_LOG.".sub_logid,".PASSENGERS_LOG.".bookby,".PASSENGERS_LOG.".booking_from_cid,".PASSENGERS_LOG.".distance,".PASSENGERS_LOG.".notes_driver,".PASSENGERS_LOG.".waitingtime,
		".PEOPLE.".name AS driver_name,".PASSENGERS_LOG.".promocode,".PASSENGERS.".discount AS passenger_discount,".PEOPLE.".phone AS driver_phone,".PEOPLE.".profile_picture AS driver_photo,".PEOPLE.".device_id AS driver_device_id,".PEOPLE.".device_token AS driver_device_token,".PEOPLE.".device_type AS driver_device_type,".PASSENGERS.".discount AS passenger_discount,".PASSENGERS.".device_id AS passenger_device_id,".PASSENGERS.".device_token AS passenger_device_token,".PASSENGERS.".referred_by AS referred_by,".PASSENGERS.".referrer_earned AS referrer_earned,".PASSENGERS.".device_type AS passenger_device_type, ".PASSENGERS.".salutation AS passenger_salutation,".PASSENGERS.".name AS passenger_name,".PASSENGERS.".lastname AS passenger_lastname,".PASSENGERS.".email AS passenger_email,".PASSENGERS.".phone AS passenger_phone,IFNULL(".PASSENGERS.".wallet_amount,0) AS wallet_amount,".COMPANYINFO.".cancellation_fare as cancellation_nfree,".COMPANYINFO.".company_tax as company_tax
		FROM  ".PASSENGERS_LOG." 
		JOIN  ".COMPANY." ON (  ".PASSENGERS_LOG.".`company_id` =  ".COMPANY.".`cid` ) 
		LEFT JOIN  ".PASSENGERS." ON (  ".PASSENGERS_LOG.".`passengers_id` =  ".PASSENGERS.".`id` ) 
		JOIN  ".COMPANYINFO." ON (  ".PASSENGERS_LOG.".`company_id` =  ".COMPANYINFO.".`company_cid` ) 
		JOIN  ".PEOPLE." ON (  ".PEOPLE.".`id` =  ".PASSENGERS_LOG.".`driver_id` ) 
		WHERE  ".PASSENGERS_LOG.".`passengers_log_id` =  '$trip_id'";
		
		$result =  Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

		return $result;
		
	}


	/*** Get Taxi fare per KM & Waiting charge of the company based Company***/
	public function get_model_fare_details1($company_id,$model_id="",$search_city="")
	{

		if($search_city!='')
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".city_id ='".$search_city."'"; 
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
		if(FARE_SETTINGS == 2)
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
				$result = Db::query(Database::SELECT, $sql)					
				->execute()
				->as_array();											               
				return $result;		
	}

	/*** Get Taxi fare per KM & Waiting charge of the company it's for client requirement***/
	public function get_model_fare_details1_new($company_id,$model_id="",$search_city="")
	{
		//echo $company_id.'-'.$model_id.'-'.''.$search_city;exit;
		$company_moter_model_status=0;

		if($search_city!='')
		{
			$model_base_query = "select city_model_fare from ".CITY." where ".CITY.".city_id ='".$search_city."'"; 
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
		if($company_id !="" && !empty($model_id))
		{
			$check_company_moter_model=$this->check_company_moter_model($company_id,$model_id);
			$company_moter_model_status=count($check_company_moter_model);
			
		}
		//echo $company_moter_model_status=count($check_company_moter_model);exit;
		
		if($company_moter_model_status !=0)
		{
			$sql = "SELECT (SUM(company_model_fare.base_fare)*($city_model_fare)/100) + company_model_fare.base_fare as base_fare,(SUM(company_model_fare.min_fare)*($city_model_fare)/100) + company_model_fare.min_fare as min_fare,(SUM(company_model_fare.cancellation_fare)*($city_model_fare)/100) + company_model_fare.cancellation_fare as cancellation_fare,(SUM(company_model_fare.below_km)*($city_model_fare)/100) + company_model_fare.below_km as below_km,(SUM(company_model_fare.above_km)*($city_model_fare)/100) + company_model_fare.above_km as above_km,(SUM(company_model_fare.minutes_fare)*($city_model_fare)/100) + company_model_fare.minutes_fare as minutes_fare,company_model_fare.night_charge,company_model_fare.night_timing_from,company_model_fare.night_timing_to,(SUM(company_model_fare.night_fare)*($city_model_fare)/100) + company_model_fare.night_fare as night_fare,company_model_fare.evening_charge,company_model_fare.evening_timing_from,company_model_fare.evening_timing_to,(SUM(company_model_fare.evening_fare)*($city_model_fare)/100) + company_model_fare.evening_fare as evening_fare,
company_model_fare.waiting_time,company_model_fare.min_km,company_model_fare.below_above_km,    company_model_fare.cents_above_counts
FROM  ".COMPANY_MODEL_FARE." as company_model_fare WHERE  ".COMPANY_MODEL_FARE.".`model_id` = '$model_id' and ".COMPANY_MODEL_FARE.".`company_cid`= '$company_id'";
		}
		else
		{
			$sql = "SELECT (SUM(model.base_fare)*($city_model_fare)/100) + model.base_fare as base_fare,(SUM(model.min_fare)*($city_model_fare)/100) + model.min_fare as min_fare,(SUM(model.cancellation_fare)*($city_model_fare)/100) + model.cancellation_fare as cancellation_fare,(SUM(model.below_km)*($city_model_fare)/100) + model.below_km as below_km,(SUM(model.above_km)*($city_model_fare)/100) + model.above_km as above_km,(SUM(model.minutes_fare)*($city_model_fare)/100) + model.minutes_fare as minutes_fare,model.night_charge,model.night_timing_from,model.night_timing_from,model.night_timing_to,(SUM(model.night_fare)*($city_model_fare)/100) + model.night_fare as night_fare,model.evening_charge,model.evening_timing_from,model.evening_timing_to,(SUM(model.evening_fare)*($city_model_fare)/100) + model.evening_fare as evening_fare,model.waiting_time,model.min_km,model.below_above_km,    cents_above_counts
			FROM  ".MOTORMODEL." as model WHERE  model.`model_id` = '$model_id'";			
		}
//echo $sql;
				$result = Db::query(Database::SELECT, $sql)					
				->execute()
				->as_array();											               
				return $result;		
	}

	public function siteinfo_details()	
	{
	
		$sql = "SELECT admin_commission,referral_discount,currency_format,referral_amount,referral_settings,wallet_amount1,wallet_amount2,wallet_amount3,wallet_amount_range FROM ".SITEINFO;

		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

	}

	/******** reg_passenger_first_trip ***********/
	public function reg_passenger_first_trip($passengers_id)
	{
		$sql = "select ".PASSENGERS_LOG.".passengers_log_id from ".PASSENGERS_LOG." RIGHT JOIN ".TRANS." as t ON ".PASSENGERS_LOG.".passengers_log_id = t.passengers_log_id where passengers_id = '$passengers_id' and travel_status = '1' and  driver_reply = 'A' and msg_status='R'";
		 $result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();					             
		if(count($result) == 0)
		{
			return 1;
		}
		else
		{
			return 0;
		}				
	}

	//to check the passenger have referral amount to use
	public function check_passenger_referral_amount($passenger_id)
	{
		$sql = "SELECT referral_amount,referral_code FROM ".PASSENGER_REFERRAL." WHERE passenger_id='$passenger_id' and referral_amount_used='0'";
		$result=Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}

	//to check the passenger have wallet amount to use
	public function get_passenger_wallet_amount($passenger_id)
	{
		$sql = "SELECT wallet_amount,name,lastname,email,phone,referral_code_amount,referral_code FROM ".PASSENGERS." WHERE id='$passenger_id'";
		$result=Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}

	//function to get passenger details by referral code
	public function passenger_detailsbyreferralcode($referral_code)
	{
		$sql = "SELECT id,wallet_amount FROM ".PASSENGERS." WHERE referral_code='$referral_code'";
		$result=Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}

	public function get_passenger_phone_by_id($id) 
	{

		$query= "SELECT phone FROM ".PASSENGERS." WHERE id = '$id'";

		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return ($result[0]['phone'])?$result[0]['phone']:'';	
	}	

	/****Update driver commission and fare details*****/
	public function update_fare_details($trip_id,$driver_id,$driver_commision,$driver_balance,$company_id,$flag)
	{
		
		if(TIMEZONE){
			$current_time = convert_timezone('now',TIMEZONE);
			
		}else{
			$current_time =	date('Y-m-d H:i:s');
			
		}
		$fare_settings=1;
			
		if($flag==2)
		{ 
			$get_model_id=$this->get_model_id_details($trip_id);
			//$booked_from=$get_model_id[0]['booked_from'];
			//$now_after=$get_model_id[0]['now_after'];
			$taxi_model=$get_model_id[0]['taxi_model'];
			$check_company_moter_model=$this->check_company_moter_model($company_id,$taxi_model);
			$company_moter_model_status=count($check_company_moter_model);
			if(count($company_moter_model_status)>0){
				$fare_settings=2;
			}
			
		}
		
		$driver_percentage=$driver_commision;
		$commission_settings=ADMIN_COMMISSON;//array("admin_commission"=>ADMIN_COMMISSON);
		$driver_balance=!empty($driver_balance)?$driver_balance:'0';
		$additional_trip_details=$this->get_additional_trip_details($trip_id);
		//$driver_profile=$this->driver_profile($driver_id);
		//$account_balance=isset($driver_profile[0]['account_balance'])?$driver_profile[0]['account_balance']: 0;
		//$driver_balance=$account_balance-$driver_share;
		//$update_driver_tripcommission=$this->update_driver_tripcommission($driver_id,$driver_balance);
		//$driver_details=array("before_balance"=>'',"deducted_balance"=>$drivercommision,"current_balance"=>'');
		$driver_details='';
		if(count($additional_trip_details)=='0'){
			$fieldname_array = array('passengers_log_id','fare_calculation_type','fare_settings','commission_settings','driver_amount','driver_commission_percentage','driver_balance','updated_date','driver_payment_status');
					$values_array = array($trip_id,FARE_CALCULATION_TYPE,$fare_settings,ADMIN_COMMISSON,$driver_balance,$driver_percentage,json_encode($driver_details),$current_time,0);
					$result = DB::insert(PASSENGERS_LOG_ADDITIONAL, $fieldname_array)
						->values($values_array)
						->execute();
		}else{
			
			$update_array  = array("fare_settings"=>$fare_settings,"fare_calculation_type"=>FARE_CALCULATION_TYPE,"commission_settings"=>ADMIN_COMMISSON,"driver_commission_percentage"=>$driver_percentage,"updated_date"=>$current_time,"driver_balance"=>json_encode($driver_details),"driver_payment_status"=>0,"driver_amount"=>$driver_balance);
				$update_driver_amount = $this->update_table(PASSENGERS_LOG_ADDITIONAL,$update_array,'passengers_log_id',$trip_id);
			
		}
			
	}

	public function get_model_id_details($trip_id)
	{
		$result = DB::select(TAXI.'.taxi_model',PASSENGERS_LOG.'.booked_from',PASSENGERS_LOG.'.now_after')
					->from(PASSENGERS_LOG)
					->join(TAXI)->on(TAXI.'.taxi_id', '=', PASSENGERS_LOG.'.taxi_id')
					->where(PASSENGERS_LOG.'.passengers_log_id', '=', $trip_id)
					->execute()
					->as_array(); return $result;
		
	}

	public function get_additional_trip_details($trip_id)
	{

		$sql = "SELECT fare_calculation_type,fare_settings,commission_settings,driver_commission_percentage,final_totalfare FROM ".PASSENGERS_LOG_ADDITIONAL." WHERE `passengers_log_id` = '$trip_id' ";
			$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();
			return $result;
		
	}

	public function get_booking_fee_status($trip_id)
	{
		$booking_fee_status = 1;
		


		$result = DB::select(PASSENGERS_LOG.'.street_pickup')->from(PASSENGERS_LOG)->where(PASSENGERS_LOG.'.passengers_log_id', '=', $trip_id)->where(PASSENGERS_LOG.'.street_pickup', '=', '1')->execute()->as_array();


		if(count($result) > 0)
		{

			$booking_fee_status = 3;
		}
		else
		{

			$result = DB::select(PASSENGERS_LOG.'.operator_id',PEOPLE.'.user_type')
						->from(PASSENGERS_LOG)
						->join(PEOPLE)->on(PEOPLE.'.id', '=', PASSENGERS_LOG.'.operator_id')
						->where(PASSENGERS_LOG.'.passengers_log_id', '=', $trip_id)
						->execute()
						->as_array();
		
			if(count($result) > 0)
			{
				if($result[0]['user_type'] == 'M' || $result[0]['user_type'] == 'C')
				{
					$booking_fee_status = 2;
				}
			}

		}
		
		return $booking_fee_status;
	}

	public function get_user_name_by_id($userid)
	{
		$user = DB::select('name')->from(PEOPLE)->where('id', '=', $userid)->execute()->get('name');
		return $user;
	}

	public function passenger_transdetails($log_id)	
	{
	
		$sql = "SELECT pl.passengers_log_id,pl.booking_key,pl.passengers_id,pl.driver_id,pl.taxi_id,pl.company_id,pl.current_location,pl.pickup_latitude,pl.pickup_longitude,pl.drop_location,pl.drop_latitude,pl.drop_longitude,pl.no_passengers,pl.approx_distance,pl.approx_duration,pl.approx_fare,pl.time_to_reach_passen,pl.pickup_time,pl.dispatch_time,pl.pickupdrop,pl.rating,pl.comments,pl.travel_status,pl.driver_reply,pl.createdate,pl.booking_from,pl.company_tax,pl.faretype,pl.bookingtype,pl.driver_comments,(pl.drop_time - pl.actual_pickup_time) as travel_time,
				
				t.id as job_referral,t.distance,t.actual_distance,t.tripfare,t.fare,t.tips,t.waiting_time,t.waiting_cost,t.company_tax as tax_amount,t.amt,t.passenger_discount,t.account_discount,t.credits_used,t.transaction_id,t.payment_type,t.payment_status,t.admin_amount,t.company_amount,t.nightfare_applicable,t.nightfare,t.eveningfare_applicable,t.eveningfare,t.trans_packtype,t.waiting_time,t.minutes_fare,t.trip_minutes,	pl.used_wallet_amount, p.name as passenger_name,p.email as 
				passenger_email,pe.name as driver_name,pe.email as driver_email,t.tips 
				FROM ".PASSENGERS_LOG." as pl left join ".PASSENGERS." as p on 
				p.id=pl.passengers_id left join ".TRANS." as t on 
				t.passengers_log_id=pl.passengers_log_id left join ".PEOPLE." as pe on pl.driver_id=pe.id WHERE t.passengers_log_id = '$log_id'";

		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

	}

	public function get_location_details($trip_id)
	{
		$result =DB::select('current_location','drop_location','active_record','drop_latitude','drop_longitude','pickup_latitude',
'pickup_longitude')->from(PASSENGERS_LOG)
		                     ->join(DRIVER_LOCATION_HISTORY)->on(PASSENGERS_LOG.'.passengers_log_id','=',DRIVER_LOCATION_HISTORY.'.trip_id')
		                     ->where(PASSENGERS_LOG.'.passengers_log_id','=',$trip_id)
		                     ->execute()
		                     ->as_array();
		return $result;
		                   
	}

	//Common Function for updation
	public function update_table($table,$arr,$cond1,$cond2)
	{
		$result = DB::update($table)->set($arr)->where($cond1,"=",$cond2)->execute();
		return $result;
	}

	//check transaction details 
	public function checktrans_details($log_id)
	{
		$sql = "SELECT id FROM ".TRANS." WHERE passengers_log_id = '$log_id'";
		$result =  Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

		return $result;
	}

	public function get_taxi_modelid_by_taxiid($taxi_id)
	{
		$result = DB::select(MOTORMODEL.'.model_id')
					->from(MOTORMODEL)
					->join(TAXI)->on(TAXI.'.taxi_model', '=', MOTORMODEL.'.model_id')
					->where(TAXI.'.taxi_id', '=', $taxi_id)
					->execute()
					->as_array();
		
		return $result[0]['model_id'];					
	}
	

}

?>
