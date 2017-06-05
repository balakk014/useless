<?php defined('SYSPATH') OR die('No Direct Script Access');

/******************************************

* Contains Users module details

* @Package: ConnectTaxi

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************/

Class Model_Siteusers extends Model
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
	public function validate_signup($arr) 
	{
		return Validation::factory($arr)       
		
			->rule('name', 'not_empty')
			->rule('name', 'min_length', array(':value', '4'))
			->rule('name', 'max_length', array(':value', '32'))

			->rule('lastname', 'not_empty')
			->rule('lastname', 'min_length', array(':value', '1'))
			->rule('lastname', 'max_length', array(':value', '32'))

			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('password', 'not_empty')
			->rule('password', 'min_length', array(':value', '5'))
			->rule('password', 'max_length', array(':value', '50'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))

			/*->rule('repassword', 'not_empty')
			->rule('repassword', 'min_length', array(':value', '5'))
			->rule('repassword', 'max_length', array(':value', '50'))
			->rule('repassword',  'matches', array(':validation', 'password', 'repassword'))*/;
	}
	
	public function validate_twittersignup($arr) 
	{
		return Validation::factory($arr)       
		
			->rule('name', 'not_empty')
			->rule('name', 'min_length', array(':value', '4'))
			->rule('name', 'max_length', array(':value', '32'))

            ->rule('lastname', 'not_empty')
			->rule('lastname', 'min_length', array(':value', '1'))
			->rule('lastname', 'max_length', array(':value', '32'))

			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('password', 'not_empty')
			->rule('password', 'min_length', array(':value', '5'))
			->rule('password', 'max_length', array(':value', '50'))
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('account_type', 'not_empty')
			;
	}
	
	
    
	/**User Signup**/
	public function signup($sign,$val,$img_name,$location_details,$account_type,$userurl=null,$random_key=null, $verifylink) 
	{
        $accounttype=($account_type=="job")?2:1; // job means : careseeker login

		$username = Html::chars($sign['name']);
		$password = Html::chars(md5($sign['password']));


		$result = DB::insert(PEOPLE, array('name','lastname','email','user_url','user_uniq_urlid','password','created_date','user_type',/*'photo'*/'status','account_type', 'verify_link'))
					->values(array($sign['name'],$sign['lastname'],$sign['email'],$userurl,$random_key,$password,$this->currentdate,NORMALUSER,/*$img_name*/ACTIVE,$accounttype, $verifylink))
					->execute();

		if($result){								
		
				$email = DB::select()->from(PEOPLE)
						       ->where('email', '=', $val['email'])
						 		->execute()
					           ->as_array(); 
		          
				$this->session->set("user_name",$email["0"]["name"].$email["0"]["lastname"]);
				$this->session->set("name",$sign['name']);
				$this->session->set("id",$email["0"]["id"]);
				$this->session->set("usertype",$email["0"]["user_type"]);
				$this->session->set("user_email",$email["0"]["email"]);

				return 1;
			
		}else{
				return 0;
		}
	}
	/** get site information**/
	public function get_site_info()
	{
		// Check if the username already exists in the database
		$sql = "SELECT app_description,site_tagline,site_copyrights,site_logo FROM ".SITEINFO." WHERE id='1'";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

		return $result;
	}
	
	/** get site information**/
	public function get_sitecms_info()
	{
		// Check if the username already exists in the database
		$sql = "SELECT content,alt_tags FROM ".CMS." WHERE TYPE =  '3' ORDER BY  `order` ASC LIMIT 0 , 5";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

		return $result;
	}
	
	/** get banner images **/
	public function get_banner_images()
	{
		// Check if the username already exists in the database
		$sql = "SELECT banner_image1,banner_image2,banner_image3,banner_image4,banner_image5 FROM ".CMS." WHERE TYPE =  '2' ";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

		return $result;
	}
	public function get_companycms_details($cid)
	{
			 $result=DB::select('id','company_id','menu_name','page_url','banner_image','type')->from(COMPANY_CMS)
			 ->where('company_id', '=', $cid)
			 ->where('status', '=', 1)
			 ->order_by('id', 'ASC')
			 ->execute()
			 ->as_array();        
			return  $result;
	} 
	public function get_company_images($cid)
	{
			 $result=DB::select('banner_image')->from(COMPANY_CMS)
			 ->where('company_id', '=', $cid)
			 ->where('type', '=', 2)
			 ->order_by('id', 'ASC')	
			 ->execute()
			 ->as_array();        
			return  $result;
	} 
	public function get_company_cms_page($cid)
	{
			 $result=DB::select('page_url')->from(COMPANY_CMS)
			 ->where('company_id', '=', $cid)
			 ->execute()
			 ->as_array();        
			return  $result;
	}
	
	public function get_company_cms($cid)
	{
			 $result=DB::select()->from(COMPANYINFO)
			 ->where('company_cid', '=', $cid)
			 ->limit(1)
			 ->execute()
			 ->as_array();        
			return  $result;
	}

	
    public function update_currency_code($uid,$currency)
    {
			
		$currency_arr = array('currency' => $currency);
		$user_update=DB::update(PEOPLE)->set($currency_arr)
						->where('id','=',$uid)
						->execute();
		$session = Session::instance();	
		
		$session->set('currency_select',$currency);
	}
	
	// To Check User Name is Already Available or Not
	public static function unique_username($name)
	{
		// Check if the username already exists in the database
		$sql = "SELECT name FROM ".PEOPLE." WHERE name='$name'";   
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
	// To Check UserName is Already Available while Edit User Details
	public static function unique_username_update($name,$id)
	{
		
		// Check if the username already exists in the database
		$sql = "SELECT name FROM ".PEOPLE." WHERE name='$name' AND id !='$id' ";   
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

	/**User Login**/
	public function login($log,$location_details) 
	{

		$email = Html::chars($log['email']);
		$password = Html::chars(md5($log['password']));
		$query= "SELECT * FROM ".PEOPLE." WHERE email = '$email' AND password='$password'";
		$result =  Db::query(Database::SELECT, $query)
			->execute()
			->as_array();

			if(count($result) == 1 && $result[0]['status'] == 'A')
			{

                //Whenever user logged into the application, Add their IP and other details..
				$login_time = $this->currentdate;
				
				$sql_query = array('last_login'=>$login_time); 

                $lat=$this->lat;
                $lng=$this->lon;			

                
				$result_login = DB::update(PEOPLE)
								->set($sql_query)
								->where('email', '=', $email)
								->execute();
				
				if(($result["0"]["user_type"] == ADMIN)){
			                
			                return 0;
									
					}else{
				
					        $this->session->set("user_name",$result["0"]["username"]);
					        $this->session->set("username",$result[0]["name"]);
					        $this->session->set("id",$result["0"]["id"]);
					        $this->session->set("usertype",$result["0"]["user_type"]);
					        $this->session->set("user_email",$result["0"]["email"]);
					        $this->session->set("lat",$lat);
					        $this->session->set("long",$lng);

					        $this->session->set("account_type",$result["0"]["account_type"]);

				            return 1;
				}
			}
			elseif(count($result) == 1 && $result[0]['status'] == 'I')
			{
				return -1;
			}
			else
			{
				return 0;
			}
	}
	
	// Validating User Details while Updating User Details

	public function validate_user_settings($arr,$files_value_array) 
	{
		return Validation::factory($arr,$files_value_array)       
			->rule('file', 'Upload::type', array($files_value_array['photo'], array('jpg','jpeg', 'png', 'gif')))
			->rule('file', 'Upload::size', array($files_value_array['photo'],'2M'));						
	}	

    // Validating User Details while Updating User Details
	public function validate_carepicture_settings($arr,$files_value_array) 
	{
		return Validation::factory($arr,$files_value_array)       
			->rule('file', 'Upload::type', array($files_value_array['image'], array('jpg','jpeg', 'png', 'gif')))
			->rule('file', 'Upload::size', array($files_value_array['image'],'2M'));						
	}	



	public function validate_user_profilesettings($arr) 
	{
		return Validation::factory($arr)       
			
		    ->rule('name', 'not_empty')
            ->rule('name','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
			->rule('name', 'min_length', array(':value', '4'))
			->rule('name', 'max_length', array(':value', '32'))

            ->rule('lastname', 'not_empty')
            ->rule('lastname','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
			->rule('lastname', 'min_length', array(':value', '1'))
			->rule('lastname', 'max_length', array(':value', '32'))


                    ->rule('email','not_empty')
					->rule('email','max_length',array(':value','50'))
					
					->rule('email','email_domain')
					    

	        ->rule('description', 'not_empty')		
            ->rule('description','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
			->rule('description', 'min_length', array(':value', '5'))


                    ->rule('school', 'not_empty')   
                    ->rule('education','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))

                    ->rule('education', 'not_empty') 
                    ->rule('education','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
            ;						
	}
	public function validate_user_profilesettings_optional($arr)
	{
		return Validation::factory($arr)       
			
                    ->rule('phone', 'phone')
                    ->rule('dob', 'date')

                   
                    ->rule('organisation', 'alpha_space')  
                    ->rule('organisation','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
                    ->rule('organisation', 'not_numeric')  


                    ->rule('work', 'alpha_space')  
                    ->rule('work','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
                    ->rule('work', 'not_numeric')  


                    ->rule('website', 'url')

					        ->rule('user_paypal_account','max_length',array(':value','60'))					     
    					    ->rule('user_paypal_account','email')
					        ->rule('user_paypal_account','Model_Authorize::unique_email')

                   ->rule('account_balance_amt', 'numeric')  


                    ->rule('group', 'alpha_space')  
                    ->rule('group','illegal_chars',array(':value','/^[\p{L}-.,_; \'0-9]*$/u'))
                    ->rule('group', 'not_numeric')  
            ;								
	}	

     public function update_user_settings_optional($array_data,$id)
     {
			if(isset($array_data['time_zone']) && $array_data['time_zone'] !=""){
						$name = $array_data['time_zone'];
						$sql_query[ 'timezone' ]=$name ;
					}
			  if(isset($array_data['phone']) && $array_data['phone'] !=""){
						$name = $array_data['phone'];
						$sql_query[ 'phone' ]=$name ;
					}
			  if(isset($array_data['gender']) && $array_data['gender'] !=""){
						$name = $array_data['gender'];
						$sql_query[ 'gender' ]=$name ;
					}
			  if(isset($array_data['dob']) && $array_data['dob'] !=""){
						$name = $array_data['dob'];
						$sql_query[ 'dob' ]=$name ;
					}
				
			  if(isset($array_data['known_language']) && $array_data['known_language'] !=""){
						$name = $array_data['known_language'];
						
						$sql_query[ 'known_language' ]=implode(',',$array_data['known_language']) ;
					}
			if(isset($array_data['group']) && $array_data['group'] !=""){
						$name = $array_data['group'];
						$sql_query[ 'group' ]=$name ;
					}					
			  if(isset($array_data['work']) && $array_data['work'] !=""){
						$name = $array_data['work'];
						$sql_query[ 'work' ]=$name ;
					}
			  if(isset($array_data['website']) && $array_data['website'] !=""){
						$name = $array_data['website'];
						$sql_query[ 'website' ]=$name ;
					}
			 
            $optional_result =  DB::update(PEOPLE)->set($sql_query)
					    ->where('id', '=' ,$id)
					    ->execute();
	 }
// Updating User Details

	public function update_user_settings($array_data,$post_value_array,$userid,$photo)
	{
		$mdate = $this->currentdate; 
		
		// Update user records in the database
		$sql_query = (array('updated_date' => $mdate));	 //'location' =>$array_data['location'] ,'industry' =>$array_data['industry'],'smart_tags' => $array_data['smart_tags'],
	
		if(isset($array_data['name']) && $array_data['name'] !=""){
			$name = $array_data['name'];
			$sql_query[ 'name' ]=$name ;
		}

        if(isset($array_data['lastname']) && $array_data['lastname'] !=""){


			$name = $array_data['lastname'];
			$sql_query[ 'lastname' ]=$name ;
		}

        if(isset($array_data['description']) && $array_data['description'] !=""){
			$name = $array_data['description'];
			$sql_query[ 'description' ]=$name ;
		}



        if(isset($array_data['education']) && $array_data['education'] !=""){
				$name = $array_data['education'];
				$sql_query[ 'education' ]=$name ;
			}
	    if(isset($array_data['school']) && $array_data['school'] !=""){
				$name = $array_data['school'];
				$sql_query[ 'school' ]=$name ;
			}
  

		if($photo != ""){		
			 $sql_query[ 'photo' ]=$photo ;		
		}
		
		$result =  DB::update(PEOPLE)->set($sql_query)
					->where('id', '=' ,$userid)
					->execute();

        /* optional record update */


            $mdate = $this->currentdate;  		
            $known_language="";
            if(isset($post_value_array['known_language']))
            {
                foreach($post_value_array['known_language'] as $lang)
                {
                  $known_language=$known_language.$lang.",";
                }            
			    $sql_query[ 'known_language' ]=$known_language ;
            }

            $sql_query[ 'updated_date' ]=$mdate ;

			$sql_query[ 'lati' ]=isset($post_value_array['lati'])?$post_value_array['lati']:'' ;
			$sql_query[ 'lng' ]=isset($post_value_array['lng'])?$post_value_array['lng']:'' ;


		
		    $optional_result =  DB::update(PEOPLE)->set($sql_query)
					    ->where('id', '=' ,$userid)
					    ->execute();          
		
		return ($result)?1:0;
	}

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

	// Check Whether Email is Already Exist or Not
	public function check_email($email="")
	{

		$sql = "SELECT email FROM ".PEOPLE." WHERE email='$email' ";   
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


	
	// Check Email Exist or Not while Updating User Details
	public function check_email_update($email="",$id="")
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


	// Check Image Exist or Not while Updating User Details

	public function check_photo($userid="")
	{
		//$sql = "SELECT photo FROM ".PEOPLE." WHERE id ='$userid'";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();
		
			if(count($result) > 0)
			{ 
				return $result[0]['photo'];
			}
	}


	// Reset User Password if User Forgot Password 
	public function forgot_password($array_data,$post_value_array,$random_key)
	{
		$mdate = $this->currentdate;
		$pass= md5($random_key);
		// Create a new user record in the database
		$result = DB::update(PEOPLE)->set(array('password' => $pass,'updated_date' => $mdate ))->where('email', '=', $array_data['email'])
			->execute();
		if($result){
			 $rs = DB::select('name','username','email','password')->from(PEOPLE)
			 			->where('email','=', $post_value_array['email'])
			 			->where('status','=',ACTIVE)
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
		$mdate = $this->currentdate;
		$pass=md5($array_data['confirm_password']);
		// Create a new user record in the database
		$result = DB::update(PEOPLE)->set(array('password' => $pass ,'updated_date' => $mdate ))->where('id', '=', $userid)
			->execute();
			
		if(count($result) == SUCESS)
		{
			$rs = DB::select('username','name','password','email')->from(PEOPLE)
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
			->rule('new_password', 'min_length', array(':value', '6'))
			->rule('old_password', 'max_length', array(':value', '24'))
			->rule('new_password', 'not_empty')
			->rule('new_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('new_password', 'min_length', array(':value', '6'))
			->rule('new_password', 'max_length', array(':value', '24'))
			->rule('confirm_password', 'not_empty')
			->rule('confirm_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('confirm_password',  'matches', array(':validation', 'new_password', 'confirm_password'))
			->rule('confirm_password', 'min_length', array(':value', '6'))
			->rule('confirm_password', 'max_length', array(':value', '24'));
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

	//update user photo null 
	public function update_user_photo($userid)
	{
		$sql_query = array('photo' => "");
		//print_r($sql_query);exit;
		$result =  DB::update(PEOPLE)->set($sql_query)
			->where('id', '=' ,$userid)
			->execute();

		return 1;
	}

	//set user status by following activation url	
	public function set_description_active($usr_id,$key,$settings)
	{

		if($settings[0]['admin_activation_reg'] == YES){
			$sql_query = array('activation_code_status' => ACTIVATION_CODE_STATUS);
		}else{ 

			$sql_query = array('status' => ACTIVE,'activation_code_status' => ACTIVATION_CODE_STATUS);
		}
			$result =  DB::update(USERS)->set($sql_query)
					->where('id', '=' ,$usr_id)
					->where('activation_code', '=' ,$key)
					->where('status','=',IN_ACTIVE)
					->execute();
					
      if($result == 1){
			$rs = DB::select('activation_code_status','status')->from(USERS)
						->where('id', '=', $usr_id)
						->execute()
						->as_array(); 
						
			return $rs;     	
		}
    }

	public function check_userdetails_exist($id,$key)
	{
		$result = DB::select('id','activation_code_status')->from(USERS)
			->where('id', '=', $id)
			->where('activation_code','=',$key)
			->execute()
			->as_array();		
			
			return $result;	

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
    public function get_uname($uid="")
	{

		$sql = "SELECT name,lastname FROM ".PEOPLE." WHERE id='$uid' ";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 
				return $result["0"]["name"]."".$result["0"]["lastname"];
			}
			else
			{
				return "";		
			}
	}

	public function get_people_details($id){
	
		$result=DB::select()->from(PEOPLE)	
				->where('id','=',$id)
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
/**For Facebook user signup insertion**/

	public function register_facebook_user($profile_data = array(),$fb_access_token,$arr,$location_details,$userurl=null,$random_key=null,$pwd='', $verify_code)
	{

        $lat=$this->lat;
        $lng=$this->lon;


        $accounttype=($this->session->get('account_type')=="job")?2:1;  // job means : careseeker login


		$result=DB::select()->from(PEOPLE)
			->where('email', '=', $profile_data->email)
			->execute()
			->as_array();

	    if(empty($result))
		{  
			$username = Html::chars($profile_data->first_name);	
			$password = md5($pwd); //  md5(Commonfunction::randomkey_generator());

			$insert_result = DB::insert(PEOPLE, array('username','name', 'email', 'password','user_url','user_uniq_urlid','photo','login_type','user_type','status',
										'created_date','access_key','secret_key','facebook_session_key','last_login',
										'account_type', 'verify_link'))
							->values(array(strtolower($username),$profile_data->name,$profile_data->email,
										$password,$userurl,$random_key,$arr['picture'],FACEBOOK,NORMALUSER,ACTIVE,
										$this->currentdate,$fb_access_token,FB_APP_SECRET,'',$this->currentdate,$accounttype, $verify_code))
							->execute();

                //'latitude','langitude','login_city','login_state','login_country','login_country_code','login_ip',
                //,$lat,$lng,$city,$state,$country,$country_code,$ip

			$this->session->set("id" ,$insert_result[0]);
			$this->session->set("username", $profile_data->first_name);
			$this->session->set("email", $profile_data->email);
			$this->session->set("fb_access_token" ,$fb_access_token);

            unset($_SESSION["account_type"]);


            $rslt=array(); 
            $rslt=array("name"=>$profile_data->first_name,"email"=>$profile_data->email,"password"=>$password);
            return $rslt;
		}
		else
		{
				$login_time = $this->currentdate;                
				$result_login = DB::update(PEOPLE)
								->set(array('last_login'=> $login_time))
								->execute();
				
				if(($result["0"]["user_type"] == ADMIN)){
				        
						$this->session->set("user_email",$result[0]["email"]);
						$this->session->set("name",$result[0]["name"]);
						$this->session->set("username",$result[0]["username"]);
						$this->session->set("userid", $result[0]["id"]);
						$this->session->set("user_type", $result[0]["user_type"]);
									
					}				
					$this->session->set("user_name",$result["0"]["username"]);
				    $this->session->set("name",$result[0]["name"]);
					$this->session->set("id",$result["0"]["id"]);
					$this->session->set("usertype",$result["0"]["user_type"]);
					$this->session->set("email",$result["0"]["email"]);

            return 1;		
		}
		
	}
	


	/**For Facebook user signup insertion**/
	public function register_facebook_user1($profile_data = array(),$fb_access_token,$arr,$location_details,$userurl=null,$random_key=null, $verify_code)
	{
	
        $lat=$this->lat;
        $lng=$this->lon;


        $accounttype=($this->session->get('account_type')=="job")?2:1;  // job means : careseeker login


		$result=DB::select()->from(PEOPLE)
			->where('email', '=', $profile_data->email)
			->execute()
			->as_array();

	    if(empty($result))
		{  
			$username = Html::chars($profile_data->first_name);	
			$password = md5(Commonfunction::randomkey_generator());

			$insert_result = DB::insert(PEOPLE, array('username','name', 'email', 'password','user_url','user_uniq_urlid','photo','login_type','user_type','status',
										'created_date','access_key','secret_key','facebook_session_key','last_login',
										'account_type', 'verify_link'))
							->values(array(strtolower($username),$profile_data->name,$profile_data->email,
										$password,$userurl,$random_key,$arr['picture'],FACEBOOK,NORMALUSER,INACTIVE,
										$this->currentdate,$fb_access_token,FB_APP_SECRET,'',$this->currentdate,$accounttype, $verify_code))
							->execute();

                //'latitude','langitude','login_city','login_state','login_country','login_country_code','login_ip',
                //,$lat,$lng,$city,$state,$country,$country_code,$ip

			$this->session->set("fb_id" ,$insert_result[0]);
            unset($_SESSION["account_type"]);


            $rslt=array(); 
            $rslt=array("name"=>$profile_data->first_name,"email"=>$profile_data->email,"password"=>$password);
            return $rslt;
		}
		else
		{
				$login_time = $this->currentdate;                
				$result_login = DB::update(PEOPLE)
								->set(array('last_login'=> $login_time))
								->execute();
				
				if(($result["0"]["user_type"] == ADMIN)){
				        
						$this->session->set("user_email",$result[0]["email"]);
						$this->session->set("name",$result[0]["name"]);
						$this->session->set("username",$result[0]["username"]);
						$this->session->set("userid", $result[0]["id"]);
						$this->session->set("user_type", $result[0]["user_type"]);
									
					}				
					$this->session->set("user_name",$result["0"]["username"]);
				    $this->session->set("name",$result[0]["name"]);
					$this->session->set("id",$result["0"]["id"]);

					$this->session->set("usertype",$result["0"]["user_type"]);
					$this->session->set("email",$result["0"]["email"]);


            return 1;		
		}
		
	}
	
		public function logged_user_status()
	{
		$query="SELECT status FROM ".PEOPLE."  where id = '".$this->session->get("id")."'  and user_type!='".ADMIN."' ";
		$result =  Db::query(Database::SELECT, $query)
			->execute()
			->as_array();
			

			if(count($result) == 1 && $result[0]['status'] == 'A')
			{		
			   return 1;
				
			}
			else if(count($result) == 1 && $result[0]['status'] == 'I')
			{
				return -1;
			}
			else
			{
				return 0;
			}
	}
	// user balance get
	public function get_user_accountbalance($uid)
	{
		$sql = "SELECT account_balance_amt as balance  FROM ".PEOPLE." as ppl  WHERE ppl.id='$uid' and ppl.status='A' "; 		
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();		
			return 	$result;
	}
	


	public function get_user_email()
	{      	
        $query="SELECT email FROM ".PEOPLE."  where id != '".$this->session->get("id")."' ";
           
        $result=Db::query(Database::SELECT, $query)
		            ->execute()
		            ->as_array();

        return $result;
    }
	public function get_verify_userid($verify_code)
    {
         $sql = "SELECT id FROM ".PEOPLE." WHERE verify_link='$verify_code'"; 
         $result=Db::query(Database::SELECT, $sql)
            ->execute()
            ->as_array();

        return ($result[0]["id"])?$result[0]["id"]:'';
    }
    
   public function get_verify( $verifycode ) {
	   $query = DB::select( array( 'verify_link_status', 'status' ) )
					->from( PEOPLE )
					->where( 'verify_link', '=', $verifycode )
					->execute()
					->get( 'status' );
					
		if( $query == 1 ) {
			return 0;
		} else{
			DB::update( PEOPLE )
				->set( array( 'verify_link_status' => '1' ) )
				->where( 'verify_link', '=', $verifycode )
				->execute();
			return 1;
		}
   }
   
    public function get_userallinfo($userid)
	{
         $result =  DB::select()->from(PEOPLE)
                                     
                                     ->where('id', '=',$userid)
									 ->execute()
									 ->as_array();		 
		  return $result;			
	}
    
    
    public function dash_canview($id)
    {
    $can_view = 0;
    $sql = "select account_type from ".PEOPLE ." where id='$id' and status='A' ";
    $account_type= Db::query(Database::SELECT, $sql)
                        ->execute()

                        ->as_array(); 
                        
                        
                       // print_r($account_type);

        if(count($account_type)>0)
        {
                  if($account_type[0]['account_type']==2)
                  {
                  	$can_view = 1;
                  }
                  if($account_type[0]['account_type']==1)
                  {
                  
                  	$can_view = 0;
                  }
        }
        return $can_view;    	
    }
    
	public function get_usernames($userid)
	{
         $result =  DB::select('name')->from(PEOPLE)
                                     
                                     ->where('id', '=',$userid)
									 ->execute()
									 ->as_array();		 
		  return $result;			
	}
		public function get_smtpdetails()
	{
          $result =  DB::select()->from(SMTP_SETTINGS)
                                     ->limit(1)
									 ->execute()
									 ->as_array();		 
		  return $result;		
	}
	public function get_email_settinguser($user_id,$cols="")
	{
          $result =  DB::select($cols)->from(USER_EMAIL_SETTINGS)
                                     ->where('userid', '=', $user_id)
                                     ->limit(1)
									 ->execute()
									 ->as_array();		 
		  return $result;		
		
	}
	 public function get_currentuser($userid)
	 {
		 $query="select * from ".PEOPLE." where id=$userid and status='A' " ;
		$result=Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
	     return $result;			 
	 }
	 public function update_useremail_settings($arr,$userid)
	 {
	       $result =  DB::select()->from(USER_EMAIL_SETTINGS)
									 ->where('userid', '=', $userid)
									 ->execute()
									 ->as_array();
			if(count($result)>0)
			{
				
 $set_arr=array('gen_1'=>isset($arr['gen_1'])?$arr['gen_1']:'0','gen_2'=>isset($arr['gen_2'])?$arr['gen_2']:'0',
 'mycon_1'=>isset($arr['mycon_1'])?$arr['mycon_1']:'0','mycon_2'=>isset($arr['mycon_2'])?$arr['mycon_2']:'0','mycon_3'=>isset($arr['mycon_3'])?$arr['mycon_3']:'0',
 'offer_lis_1'=>isset($arr['offer_lis_1'])?$arr['offer_lis_1']:'0','offer_lis_2'=>isset($arr['offer_lis_2'])?$arr['offer_lis_2']:'0','offer_lis_3'=>isset($arr['offer_lis_3'])?$arr['offer_lis_3']:'0','offer_lis_4'=>isset($arr['offer_lis_4'])?$arr['offer_lis_4']:'0','offer_lis_5'=>isset($arr['offer_lis_5'])?$arr['offer_lis_5']:'0','offer_lis_6'=>isset($arr['offer_lis_6'])?$arr['offer_lis_6']:'0','offer_lis_7'=>isset($arr['offer_lis_7'])?$arr['offer_lis_7']:'0','offer_lis_8'=>isset($arr['offer_lis_8'])?$arr['offer_lis_8']:'0','offer_lis_9'=>isset($arr['offer_lis_9'])?$arr['offer_lis_9']:'0','offer_lis_10'=>isset($arr['offer_lis_10'])?$arr['offer_lis_10']:'0','offer_lis_11'=>isset($arr['offer_lis_11'])?$arr['offer_lis_11']:'0',
 'book_lis_1'=>isset($arr['book_lis_1'])?$arr['book_lis_1']:'0','book_lis_2'=>isset($arr['book_lis_2'])?$arr['book_lis_2']:'0','book_lis_3'=>isset($arr['book_lis_3'])?$arr['book_lis_3']:'0');				
					$rs=DB::update(USER_EMAIL_SETTINGS)->set($set_arr)
														->where('userid','=',$userid)
														->execute();				
			}	
			else
			{
				$cols=array('userid','gen_1','gen_2','mycon_1','mycon_2','mycon_3','offer_lis_1','offer_lis_2','offer_lis_3','offer_lis_4','offer_lis_5','offer_lis_6','offer_lis_7','offer_lis_8','offer_lis_9','offer_lis_10','offer_lis_11','book_lis_1','book_lis_2','book_lis_3');				
				$vals=array($userid,isset($arr['gen_1'])?$arr['gen_1']:'0',isset($arr['gen_2'])?$arr['gen_2']:'0',isset($arr['mycon_1'])?$arr['mycon_1']:'0',isset($arr['mycon_2'])?$arr['mycon_2']:'0',isset($arr['mycon_3'])?$arr['mycon_3']:'0',isset($arr['offer_lis_1'])?$arr['offer_lis_1']:'0',isset($arr['offer_lis_2'])?$arr['offer_lis_2']:'0',isset($arr['offer_lis_3'])?$arr['offer_lis_3']:'0',isset($arr['offer_lis_4'])?$arr['offer_lis_4']:'0',isset($arr['offer_lis_5'])?$arr['offer_lis_5']:'0',isset($arr['offer_lis_6'])?$arr['offer_lis_6']:'0',isset($arr['offer_lis_7'])?$arr['offer_lis_7']:'0',isset($arr['offer_lis_8'])?$arr['offer_lis_8']:'0',isset($arr['offer_lis_9'])?$arr['offer_lis_9']:'0',isset($arr['offer_lis_10'])?$arr['offer_lis_10']:'0',isset($arr['offer_lis_11'])?$arr['offer_lis_11']:'0',isset($arr['book_lis_1'])?$arr['book_lis_1']:'0',isset($arr['book_lis_2'])?$arr['book_lis_2']:'0',isset($arr['book_lis_3'])?$arr['book_lis_3']:'0');				
                    $result = DB::insert(USER_EMAIL_SETTINGS, $cols)
                              ->values($vals)
                              ->execute();					
			}	 
	 }
	 
	 public function get_user_email_setting($userid)
	 {
          $result =  DB::select()->from(USER_EMAIL_SETTINGS)
									 ->where('userid', '=', $userid)
									 ->execute()
									 ->as_array();		 
		  return $result;
	 }
	 
	public function usermapupdate($id,$locat,$lati,$lng)
	{
			$query = array("location"=>$locat,"lati" => $lati,"lng"=>$lng);
			$result =  DB::update('people')->set($query)
					->where('id','=',$id)
					->execute();		
			return $result;		
	}	
	public function getusermap_details($id)
	{
   		$result=DB::select('location','lati','lng')->from('people')	
					->where("id","=",$id)
					->execute()->as_array();	
		return $result;	
	}
	public function page_details_terms()
	{
        $sql = 'SELECT * FROM cms where type=7 order by id desc'; 
        $footer_result= Db::query(Database::SELECT, $sql)
                            ->execute()
                            ->as_array();  	
        return $footer_result;			
	}
	public function page_details_privacy()
	{
        $sql = 'SELECT * FROM cms where type=8 order by id desc'; 
        $footer_result= Db::query(Database::SELECT, $sql)
                            ->execute()
                            ->as_array();  	
        return $footer_result;			
	}
	public function page_details_help()
	{
        $sql = 'SELECT * FROM cms where type=4 order by id desc'; 
        $footer_result= Db::query(Database::SELECT, $sql)
                            ->execute()
                            ->as_array();  	
        return $footer_result;			
	}
	public function page_details_press()
	{
        $sql = 'SELECT * FROM cms where type=3 order by id desc'; 
        $footer_result= Db::query(Database::SELECT, $sql)
                            ->execute()
                            ->as_array();  	
        return $footer_result;			
	}	
	public function page_details_contact()
	{
        $sql = 'SELECT * FROM cms where type=9 order by id desc'; 
        $footer_result= Db::query(Database::SELECT, $sql)
                            ->execute()
                            ->as_array();  	
        return $footer_result;			
	}
	

	public function validate_contact($arr) 
	{
		$arr['email'] = trim($arr['email']);
	
		return Validation::factory($arr)      
			->rule('name1','not_empty')
			->rule('name1','not_numeric')
			->rule('name1','min_length', array(':value', '3'))
			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('phone','numeric') //num
			->rule('type','not_empty')
			->rule('subject','not_empty')
			->rule('message','not_empty');
	}	
	public function savecontact($arr)
	{
    		$result = DB::insert('contact', array('name','email','phone','type','subject','message'))
					->values(array($arr['name1'],$arr['email'],$arr['phone'],$arr['type'],$arr['subject'],$arr['message']))
					->execute();
	
			return $result;			
	}
	public function page_details_aboutus()
	{
        $sql = 'SELECT * FROM cms where type=2 order by id desc'; 
        $footer_result= Db::query(Database::SELECT, $sql)
                            ->execute()
                            ->as_array();  	
        return $footer_result;			
	}
	public function page_details_jobs()
	{
        $sql = 'SELECT * FROM cms where type=6 order by id desc'; 
        $footer_result= Db::query(Database::SELECT, $sql)
                            ->execute()
                            ->as_array();  	
        return $footer_result;			
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
   
   public function check_password_exist($userid){
   
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
	
		$sql = "SELECT * FROM ".PEOPLE." WHERE id = '$id' ";                      
		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 	
	}
	
	/**Validating User conatct details**/

	public function validate_contact_form($arr) 
	{
		$arr['email1'] = trim($arr['email1']);
	
		return Validation::factory($arr)      
			->rule('name','not_empty')
			->rule('name','not_numeric')
			//->rule('name','alpha')  
			->rule('name','min_length', array(':value', '3'))
			->rule('email1', 'not_empty')
			->rule('email1', 'email')     
			->rule('email1', 'max_length', array(':value', '50'))
			->rule('message','not_empty')
			->rule('message', 'min_length', array(':value', '10'));
	}
	
	public function add_contact_details($data)
	{
	
		$rs   = DB::insert(QUICK_CONTACTS)
				->columns(array('name','email','message','contact_date'))
				->values(array($data['name'],$data['email1'],$data['message'],$this->currentdate))
				->execute();	
	
		return $rs;
	}
	
	/**Validating company Signup details**/
	public function validate_company_signup($arr) 
	{

		return Validation::factory($arr)
			->rule('firstname', 'not_empty')
			->rule('firstname', 'min_length', array(':value', '4'))
			->rule('firstname', 'max_length', array(':value', '32'))
			
			->rule('lastname', 'not_empty')
			->rule('lastname', 'max_length', array(':value', '32'))
			
			->rule('email', 'not_empty')
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '100'))
			
			->rule('companyname', 'not_empty')
			->rule('companyname', 'min_length', array(':value', '4'))
			->rule('companyname', 'max_length', array(':value', '30'))
			->rule('company_name', 'Model_Siteusers::checkcompany', array(':value',$arr['country'],$arr['state'],$arr['city']))

			->rule('paypal_account', 'not_empty')
			->rule('paypal_account', 'email')     
			->rule('paypal_account', 'max_length', array(':value', '150'))
			
			->rule('country','not_empty')
			
			->rule('city','not_empty')
			
			->rule('state','not_empty')
			
			->rule('address','not_empty')
			
			->rule('companyaddress','not_empty')
			
			->rule('mobile','not_empty')
			->rule('mobile', 'phone')
			->rule('mobile', 'min_length', array(':value', '4'))
			->rule('mobile', 'max_length', array(':value', '36'))
			->rule('mobile', 'Model_Siteusers::checkphone', array(':value'))
			
			
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('password', 'not_empty')
			->rule('password', 'min_length', array(':value', '4'))
			->rule('password', 'max_length', array(':value', '36'))
			
			->rule('confirm_password', 'not_empty')
			->rule('confirm_password', 'min_length', array(':value', '4'))
			->rule('confirm_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('confirm_password',  'matches', array(':validation', 'password', 'confirm_password'))
			->rule('confirm_password', 'max_length', array(':value', '36'))
			
			->rule('domain_name', 'not_empty')
			->rule('domain_name', 'min_length', array(':value', '4'))
			->rule('domain_name', 'max_length', array(':value', '10'))
			->rule('domain_name', 'alpha_numeric', array(':value','/^[0-9]{1,}/'))
			->rule('domain_name', 'Model_Add::checkdomain', array(':value'))
			->rule('time_zone', 'not_empty');
	}

	public static function checkdomain($domainname)
	{
		// Check if the username already exists in the database
		
		$result = DB::select('company_domain')->from(COMPANYINFO)->where('company_domain','=',$domainname)
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
		// Check Whether Email is Already Exist or Not
	public static function checkphone($phone="")
	{


		$result = DB::select('phone')->from(PEOPLE)->where('phone','=',$phone)
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
	
		
	/**User Signup**/
	public function company_signup($sign,$val) 
	{
		//$company_username = Html::chars($sign['company_username']);
		$org_password =  Html::chars($sign['password']);
		$password = Html::chars(md5($sign['confirm_password']));
		$result = DB::insert(PEOPLE, array('name','lastname','email','address','paypal_account','password','org_password','created_date','login_country','login_city','login_state','phone','login_type','user_type','account_type','status','login_from'))
					->values(array($sign['firstname'],$sign['lastname'],$sign['email'],$sign['address'],$sign['paypal_account'],$password,$org_password,
					$this->currentdate,$sign['country'],$sign['city'],$sign['state'],$sign['mobile'],'1','C','0','A','WD'))
					->execute();
		$last_insert_id = $result[0];
		
		$cresult = DB::insert(COMPANY, array('company_name','company_address','company_country','company_state','company_city','userid','company_status','time_zone'))
					->values(array($sign['companyname'],$sign['companyaddress'],$sign['country'],$sign['state'],$sign['city'],$last_insert_id,'D',$sign['time_zone']))
					->execute();
		$last_insert_id1 = $cresult[0];
		
				
		$update_people = DB::update(PEOPLE)->set(array('company_id' => $last_insert_id1))->where('id', '=', $last_insert_id)
			->execute();

		$insert_tdispatchalogrithm = DB::insert(TBLALGORITHM, array('labelname','alg_created_by','alg_company_id','active','hide_customer','hide_droplocation','hide_fare'))->values(array(1,$last_insert_id,$last_insert_id1,1,0,0,0))->execute();
			
					   $key="";
					   $charset = "abcdefghijklmnopqrstuvwxyz"; 
					   $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
					   $charset .= "0123456789"; 
					   $length = mt_rand (30, 35); 
					   for ($i=0; $i<$length; $i++) 
					   $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];					
					
		 DB::insert(COMPANYINFO, array('company_cid','company_domain','company_app_name','company_currency','company_notification_settings','company_api_key','header_bgcolor','menu_color','mouseover_color'))
					->values(array($last_insert_id1,$sign['domain_name'],$sign['companyname'],'$','60',$key,'#FFFFFF','#000000','#FFD800'))
					->execute();	


				 $cms=DB::insert(COMPANY_CMS, array('company_id','menu_name','title','content','page_url','type'));
				 $banner_image=DB::insert(COMPANY_CMS, array('company_id','image_tag','alt_tags','banner_image','type'));
				 for($i=1;$i<=5;$i++)
				 {
					$cms->values(array($last_insert_id1,"page$i","page$i","page$i","page$i",1));
					$banner_image->values(array($last_insert_id1,"banner$i","banner$i","",2));
			     }
				 $cms->execute();	
				 $banner_image->execute();			
			
		
		if($result && $cresult && $update_people)
		return $result[0];
		else
		return 0;
	}
	
	
	// To Check Company Name is Already Available or Not
	public static function checkcompany($companyname,$country,$state,$city)
	{
		// Check if the username already exists in the database
		
		$result = DB::select('company_name')->from(COMPANY)
				->where('company_name','=',$companyname)
				->where('company_country','=',$country)
				->where('company_state','=',$state)
				->where('company_city','=',$city)
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
	
	/**get country details**/
	public function country_details()
	{
		//~ $result = DB::select()->from(COUNTRY)->where('country_status','=','A')->order_by('country_name','asc')
			//~ ->execute()
			//~ ->as_array();
		  //~ return $result;
	}
	/**get city details**/
	public function city_details()
	{
		//~ $result = DB::select()->from(CITY)->where('city_status','=','A')->order_by('city_name','asc')
			//~ ->execute()
			//~ ->as_array();
		  //~ return $result;
	}
	/**get state details**/
	public function state_details()
	{
		//~ $result = DB::select()->from(STATE)->where('state_status','=','A')->order_by('state_name','asc')
			//~ ->execute()
			//~ ->as_array();
		  //~ return $result;
	}
	
	/** for getting city details **/
	public static function getcity_details($country_id,$state_id)
	{
			$result = DB::select()->from(CITY)->join(STATE, 'LEFT')->on(CITY.'.city_stateid', '=', STATE.'.state_id')->join(COUNTRY, 'LEFT')->on(CITY.'.city_countryid', '=', COUNTRY.'.country_id')->where('city_countryid','=',$country_id)->where('city_stateid','=',$state_id)->order_by('city_name','ASC')
				->execute()
				->as_array();
	
			return $result;
	}
	
	/** for getting state details **/
	public function getstate_details($country_id)
	{
			$result = DB::select()->from(STATE)->join(COUNTRY, 'LEFT')->on(STATE.'.state_countryid', '=', COUNTRY.'.country_id')->where('state_countryid','=',$country_id)->order_by('state_name','ASC')
				->execute()
				->as_array();
			
			return $result;
	}
	
	/** for vlaidate company login details **/
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
	
	/** for getting package status **/
	public function package_details()
	{
		$result = DB::select()->from(PACKAGE)->where('package_status','=','A')->order_by('package_name','asc')
			->execute()
			->as_array();
			return $result;
	}
	
	/** for getting package details **/
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
	
	/** menu listing in header pages **/ 
	public function menu_listingorder()
	{
		$sql = "select menu_name,menu_link from ".MENU." where status_post='P' order by order_status ASC";
		$menu_result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $menu_result;
	}
	
	/** menu listing in header pages **/ 
	public function footer_contents()
	{
		$sql = "select * from ".SITEINFO." where id=1";
		$footer_result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $footer_result;
	}
	
	/** contact us validation**/ 
	public function validate_contactus($arr="")
	{
		return Validation::factory($arr)
			->rule('first_name', 'not_empty')
			->rule('first_name', 'Model_Siteusers::checkurlgiven', array(':value'))//to avoid injection
			->rule('first_name', 'max_length', array(':value', '100'))
			//->rule('last_name', 'Model_Siteusers::checkurlgiven', array(':value'))//to avoid injection
			->rule('first_name', 'max_length', array(':value', '100'))
			->rule('email', 'not_empty')
			->rule('email', 'email')
			->rule('email', 'max_length', array(':value', '100'))
			->rule('email', 'Model_Siteusers::checkurlgiven', array(':value'))//to avoid injection
			->rule('phone', 'not_empty')
			->rule('phone','phone' ,array(':value'))
			->rule('phone', 'Model_Siteusers::checkurlgiven', array(':value'))//to avoid injection
			//->rule('subject', 'not_empty')
			//->rule('subject', 'Model_Siteusers::checkurlgiven', array(':value'))//to avoid injection
			//->rule('security_code', 'not_empty')
			//->rule('security_code', 'Model_Siteusers::checkurlgiven', array(':value'))//to avoid injection
			->rule('message', 'not_empty')
			//->rule('country', 'not_empty')
			//->rule('industry', 'not_empty')
			//->rule('no_of_employees', 'not_empty')
			//->rule('budget', 'not_empty')
			//->rule('revenue', 'not_empty')
			->rule('product', 'not_empty');
	}
	
	//Attachment file upload validation
    public function attachment_file_validation() 
	{
		 return Validation::factory($_FILES)
			->rules('attachments', array(
				array('Upload::type', array(':value', array('doc', 'pdf', 'docx','ppt','pptx','odt', 'ods', 'odp', 'odg','html','htm','jpg','jpeg','png', 'gif'))),
				array('Upload::size', array(':value', '1M'))));
	}
	
	/** inserting a contacus info in table **/
	public function contactus_add($sign, $cid, $file_name, $file, $country_name="")
	{
		//$budget=isset($sign['budget'])?$sign['budget']:'-';
		//$message="Budget: ".$budget."   -    Message: ".$sign['message'];
		$message=ucfirst($sign['message']);
		//$contact_message=" (CONTACT REQUEST)  " .ucfirst($sign['message']);
		/*$result = DB::insert(CONTACTS, array('contact_cid','name','email','subject','message','phone','sent_date'))
					->values(array($cid,$sign['name'],$sign['email'],$sign['subject'],$message,$sign['phone'],$this->currentdate))
					->execute();
		$result1 = DB::insert(LOGS, array('log_userid','log_message','log_booking','log_createdate'))
					->values(array($cid,$contact_message,$contact_message,$this->currentdate))
					->execute();*/
					
		/*$result = DB::select()
						->from( CONTACTS )
						->order_by('cid', 'DESC')
						->limit(1)
						->execute()
						->as_array();
			
		if(count($result) > 0)
			$contid = $result[0]['cid'] + 1;
		else
			$contid = 1;	*/	
		
		if(COMPANY_CID == 0)
		{			
			$result = DB::insert(CONTACTS, array('cid', 'contact_cid','first_name','last_name','email','message','phone','country','industry','no_of_employees','budget','product','revenue', 'attachment_file', 'sent_date'))
					->values(array('', $cid, $sign['first_name'], '',$sign['email'],$message,$sign['phone'], $country_name, '', $sign['no_of_employees'],'',$sign['product'], $sign['revenue'], $file_name, $this->currentdate))
					->execute();//$sign['last_name'],$sign['industry'],$sign['budget'],$sign['country']
			
			$contid = $result[0];		
			if($file_name != "" )
			{
				$random_key = 'ATTACHMENT_'.$contid;
				$extension_arr = explode( '.', $file_name);                                                                
				$extension = end($extension_arr );
				$file_name = $random_key.".".$extension;
				$extension_arr = explode( '.', $file_name);                                                                
				$extension = end($extension_arr );				
				$file_name = $random_key.".".$extension;				
				if($file != '')
				{ 
					$file_path = $_SERVER['DOCUMENT_ROOT'].'/public/'.UPLOADS.'/attachments/';
					$dirname = 'attachments';
					echo 'file_path = '.$file_path;
					if (!file_exists($file_path))
					{
						mkdir($file_path, 0777);
						chmod($file_path,0777);
					}
					$value = Upload::save($file,$file_name,$file_path);
					chmod($file_path.$file_name,0777);
				}
			}
			return $contid;		
			
		}
		else
		{
			/* Create Log */
			$ins_logid = 0;
			$company_id = $cid;			
			$user_createdby ="";
			$log_message = __('You have enquiry from ').",".__('name_label').":".$sign['name'].",".__('message').":".$sign['message'].",".__('phone_number').":".$sign['phone'].",".__('Current_Location').":".$sign['clocation'].",".__('Drop_Location').":".$sign['droplocation'];
			$log_booking =  __('You have enquiry from ').",".__('name_label').":".$sign['name'].",".__('message').":".$sign['message'].",".__('phone_number').":".$sign['phone'].",".__('Current_Location').":".$sign['clocation'].",".__('Drop_Location').":".$sign['droplocation'];
			$log_status = $this->create_logs($ins_logid,COMPANY_CID,$user_createdby,$log_message,$log_booking);

			return $log_status;
			/* Create Log */
		}
	}
	//===============================================================================================================		
	
	public static function create_logs($booking_logid='',$company_id='',$log_userid='',$log_message='',$log_booking='')	
	{
		$Commonmodel = Model::factory('Commonmodel');			
		//$user_createdby = $_SESSION['userid'];
		$current_time = $Commonmodel->getcompany_all_currenttimestamp($company_id);


		$result = DB::insert(LOGS, array('booking_logid','log_userid','log_message','log_booking','log_createdate'))
			->values(array($booking_logid,$company_id,$log_message,$log_booking,$current_time))
			->execute();

		return $result;
	}		

	public static function country_citylist($country_id)
	{

			$result = DB::select()->from(CITY)->join(STATE, 'LEFT')->on(CITY.'.city_stateid', '=', STATE.'.state_id')->join(COUNTRY, 'LEFT')->on(CITY.'.city_countryid', '=', COUNTRY.'.country_id')->where('city_countryid','=',$country_id)->where('state_status','=','A')->where('city_status','=','A')->order_by('city_name','ASC')
				->execute()
				->as_array();
	
			return $result;

	}

	public static function get_company_taxi_image($company_id)
	{
		$result = DB::select('taxi_image')->from(TAXI)
			->join(TAXIMAPPING, 'LEFT')->on(TAXIMAPPING.'.mapping_taxiid', '=', TAXI.'.taxi_id')
			->where('taxi_company','=',$company_id)
			->execute()
			->as_array();

		return $result;
	}

	public static function get_company_info($company_id)
	{
		$result = DB::select('company_phone_number','company_address','company_tagline','company_name','header_bgcolor','menu_color','mouseover_color')->from(PEOPLE)
			->join(COMPANYINFO, 'LEFT')->on(COMPANYINFO.'.company_cid', '=', PEOPLE.'.company_id')
			->join(COMPANY, 'LEFT')->on(COMPANY.'.cid', '=', PEOPLE.'.company_id')
			->where('user_type','=','C')
			->where('company_id','=',$company_id)
			->execute()
			->as_array();

		return $result;
	}

	public static function get_company_type($company_id)
	{
		//~ $sql = "SELECT upgrade_packageid FROM package_report WHERE upgrade_companyid=$company_id ORDER BY  `upgrade_id` desc LIMIT 0 , 1";   
		//~ $result=Db::query(Database::SELECT, $sql)
			//~ ->execute()
			//~ ->as_array();
		//~ return $result;
		

	}

	public function all_driver_map_list($company_id)
	{ 
			$result = DB::select('latitude','longitude','name','profile_picture','shift_status',array(DRIVER.'.status','driver_status'))->from(PEOPLE)
					->join(DRIVER)->on(DRIVER.'.driver_id', '=', PEOPLE.'.id')
					->where(PEOPLE.'.user_type','=','D')
					->where(DRIVER.'.status','=','F')
					->where(DRIVER.'.shift_status','=','IN')
					->where(PEOPLE.'.status','=','A')
					->where(PEOPLE.'.login_status','=','S')
					->where('company_id','=',$company_id)
					//->order_by('created_date','desc')->limit($val)->offset($offset)
					->execute()
					->as_array();
					return $result;

	}
	
	public static function checkurlgiven($value)
	{
		if(preg_match("/http/i",$value)){
			return false;
		} else {
			return true;
		}
	}
	
	//Get country details
	public function get_country_details()
	{
		return DB::select('country_id', 'country_name', 'iso_code')->from(COUNTRY_NEW)->where( 'country_status', '=', 1 )->execute()->as_array();
	}	
	
	//Get revenue details
	public function get_revenue_details()
	{
		return DB::select('revenue_id', 'revenue_name')->from(REVENUE)->where( 'revenue_status', '=', 1 )->execute()->as_array();
	}	
	
	//Get industry details
	public function get_industry_details()
	{
		return DB::select('industry_status_id', 'industry_status_name')->from(INDUSTRY)->where( 'industry_status', '=', 1 )->execute()->as_array();
	}	
	
	//Get company status details
	public function get_company_status_details()
	{
		return DB::select('company_status_id', 'company_status_name')->from(COMPANY_STATUS)->where( 'company_status', '=', 1 )->execute()->as_array();
	}

	/** Subscribe validation**/ 
	public function validate_subscribe($arr = "")
	{
		return Validation::factory($arr)
			->rule('subscribe_name', 'not_empty')
			->rule('subscribe_email', 'not_empty')
			->rule('subscribe_email', 'email')
			->rule('subscribe_email', 'max_length', array(':value', '100'))
			->rule('subscribe_email', 'Model_Siteusers::checkurlgiven', array(':value'));//to avoid injection;
	}

	/** inserting subscribe users **/

	public function add_subscribe($post)
	{
		$result = DB::insert(SUBSCRIBE, array('name','email'))
					->values(array($post["subscribe_name"],$post["subscribe_email"]))
					->execute();
		return $result[0];
	}
	
	
	/** get user details by number**/
	public function get_user_detail($phone)
	{
		// Check if the username already exists in the database
		$sql = "SELECT country_code,name,email,phone  FROM ".PASSENGERS." WHERE phone = '".$phone."'";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();
		if(count($result)==0){
			return 0;
		}else{
			return trim($result[0]['country_code'])."**".$result[0]['phone']."**".$result[0]['name']."**".$result[0]['email'];
		}
		return $result;
	}
	
	/**
	 * Get passenger wallet amount
	 * return amount (int)
	 **/

	public function get_wallet_amount($userID)
	{
		$wallet_amount = 0;
		//$userID = $this->admin_userid;
		$result = DB::select('wallet_amount')
					->from(PASSENGERS)
					->where( 'id', '=', $userID )
					->execute()
					->as_array();
		if(count($result) > 0 && $result[0]['wallet_amount']) {
			$wallet_amount = $result[0]['wallet_amount'];
		}
		return $wallet_amount;
	}
	
	/** 
	 * Check Promocode
	 * return true or false (int)
	 **/

	public function checkwalletpromocode($promo_code="",$passenger_id="")
	{
		$promo_query = "SELECT promocode,promo_discount,promo_used,start_date,expire_date,promo_limit FROM  ".PASSENGER_PROMO." WHERE  promocode = '$promo_code' and FIND_IN_SET('$passenger_id',passenger_id)";
		$promo_fetch = Db::query(Database::SELECT, $promo_query)
					->execute()
					->as_array();
		if(count($promo_fetch) > 0) {
			$promocode = $promo_fetch[0]['promocode'];
			$promo_discount = $promo_fetch[0]['promo_discount'];
			$promo_used = $promo_fetch[0]['promo_used'];
			$promo_start = $promo_fetch[0]['start_date'];
			$promo_expire = $promo_fetch[0]['expire_date'];
			$promo_limit = $promo_fetch[0]['promo_limit'];
			$current_time = convert_timezone('now',TIMEZONE);

			if(strtotime($promo_start) > strtotime($current_time)) {
				return 3;
			} else if(strtotime($promo_expire) < strtotime($current_time)) {
				return 4;
			} else {
				$promo_use_query = "SELECT COUNT(passenger_wallet_logid) as promo_count  FROM  ".PASSENGER_WALLET_LOG." WHERE  promocode = '$promo_code' and  `passenger_id` ='$passenger_id'"; 
				$promo_user_count = Db::query(Database::SELECT, $promo_use_query)
										->execute()
										->as_array();
				if(count($promo_user_count) > 0 && $promo_user_count[0]['promo_count'] >= $promo_limit) {
					return 2;
				} else {
					return 1;
				}
			}
		} else {
			return 0;
		}
	}

	/**
	 * Get passenger details
	 **/
	
	public function get_passenger_wallet_amount($passenger_id)
	{
		$sql = "SELECT wallet_amount,name,lastname,email,phone,referral_code_amount,referral_code FROM ".PASSENGERS." WHERE id='$passenger_id'";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}

	/** 
	 * Get Payment gateway details by payment type
	 **/
	public function payment_gateway_bytype($paymentType = "")
	{
		$sql = "SELECT payment_gateway_id as payment_type,paypal_api_username as payment_gateway_username,paypal_api_password as payment_gateway_password,paypal_api_signature as payment_gateway_key,currency_code as gateway_currency_format,payment_method as payment_method FROM ".PAYMENT_GATEWAYS."  WHERE company_id = '0' and payment_gateway_id = '$paymentType'";

		$result =  Db::query(Database::SELECT, $sql)->execute()->as_array(); 
		return $result;
	}

	/**
	 * Insert wallet log
	 **/

	public function add_wallet_log($fieldname_array, $values_array)
	{
		return DB::insert(PASSENGER_WALLET_LOG, $fieldname_array)->values($values_array)->execute();
	}

	/**
	 * Insert credit card details if savecard is 1
	 **/
	public function add_credit_card_details($fieldname_array, $values_array)
	{
		return DB::insert(PASSENGERS_CARD_DETAILS, $fieldname_array)->values($values_array)->execute();
	}
	
	public function getpromodetails($promo_code="",$passenger_id="")
   {
		$promo_query = "SELECT promocode,promo_discount,promo_used,promo_limit FROM  ".PASSENGER_PROMO." WHERE  promocode = '$promo_code' and  `passenger_id` ='$passenger_id'"; 
		$promo_fetch = Db::query(Database::SELECT, $promo_query)
					->execute()
					->as_array();
		if(count($promo_fetch) > 0) {
			$promocode = $promo_fetch[0]['promocode'];
			$promo_discount = $promo_fetch[0]['promo_discount'];
			$promo_used = $promo_fetch[0]['promo_used'];
			$promo_limit = $promo_fetch[0]['promo_limit'];
			$promo_use_query = "SELECT COUNT(passengers_log_id) as promo_count  FROM  ".PASSENGERS_LOG." WHERE  promocode = '$promo_code' and  `passengers_id` ='$passenger_id' and  travel_status='1' and driver_reply='A'"; 
			$promo_user_count = Db::query(Database::SELECT, $promo_use_query)
						->execute()
						->as_array();
			if(count($promo_user_count) > 0 && $promo_user_count[0]['promo_count'] >= $promo_limit) {
				return -1;
			} else {
				return $promo_discount;
			}
		} else {
			return 0;
		}
	}
	
	/**
	 * Check Whether the Eneterd Password is Correct While passenger Change Password
	 * return (int) true or false
	 **/

	public function check_change_password($pass="",$userid="")
	{
		//$userid = (isset($_SESSION['id']) ? $_SESSION['id'] : "");
		$result = DB::select()->from(PASSENGERS)
					->where('id', '=', $userid)
					->execute()
					->as_array();
		$pass = md5($pass);
		$password = $result["0"]["password"];
		return ($password == $pass) ? 1 : 0;
	}
	
	/**
	 * Change passenger password
	 * return (int) query
	 **/
	public function passenger_change_password($array_data,$post_value_array,$userid)
	{
		$result = DB::select('name','password','email')->from(PASSENGERS)
					->where('id', '=', $userid)
					->execute()
					->as_array();
		$password = $result[0]["password"];
		if(md5($array_data['old_password']) == $password){
			
			$mdate = $this->currentdate;
			$password = md5($array_data['confirm_password']);
			$update = DB::update(PASSENGERS)->set(array('password' => $password, 'updated_date' => $mdate))->where('id', '=', $userid)->execute();
			return $result;
		}else{
			return -1;
		}
	}
	
	/**
	 * Check Image Exist or Not while Updating passenger Details
	 * return (int) query
	 **/ 

	public function check_passenger_photo($userid = "")
	{
		$sql = "SELECT photo FROM ".PASSENGERS." WHERE id ='$userid'";   
		$result = Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();
		if(count($result) > 0) { 
			return $result[0]['photo'];
		}
	}
	
	/**
	 * Get User Details at passenger Profile Page
	 **/

	public function get_passenger_details($userid = null)
	{
		$query = "SELECT name,lastname,email,phone,address,id,country_code FROM ".PASSENGERS."  where id = '".$userid."' ";
		$result = Db::query(Database::SELECT, $query)
					->execute()
					->as_array();
		return $result;
	}
	
	/**
	 * Validate passenger form data
	 **/

	public function validate_passenger_profile($posted_value,$userid) 
	{
		return Validation::factory($posted_value)
		->rule('name', 'not_empty')
		->rule('name', 'min_length', array(':value', '4'))
		->rule('name', 'max_length', array(':value', '32'))
		->rule('lastname', 'not_empty')
		->rule('lastname', 'min_length', array(':value', '1'))
		->rule('lastname', 'max_length', array(':value', '32'))
		->rule('email','not_empty')
		->rule('email','max_length',array(':value','50'))
		->rule('email','email_domain')
		->rule('email', 'Model_Siteusers::check_passenger_email', array(':value',$userid))
		->rule('country_code', 'not_empty')
		->rule('country_code', 'min_length', array(':value', '2'))
		->rule('country_code', 'max_length', array(':value', '5'))
		->rule('phone', 'not_empty')
		->rule('phone', 'Model_Siteusers::check_passenger_phone', array(':value',$userid,$posted_value['country_code']))
		->rule('profile_picture', 'Upload::type', array(':value', array('jpg','png','gif')));;
	}

	/**
	 * Check email exist or not while updating passenger details
	 **/

	public static function check_passenger_email($email = "", $userid = "")
	{
		$sql = "SELECT email FROM ".PASSENGERS." WHERE email='$email' AND id !='$userid' ";   
		$result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
		if(count($result) > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Check phone exist or not while updating passenger details
	 **/

	public static function check_passenger_phone($phone = "", $userid = "",$country_code="")
	{
		$sql = "SELECT phone FROM ".PASSENGERS." WHERE phone='$phone' AND country_code='$country_code' AND id !='$userid' ";   
		$result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
		if(count($result) > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Update passenger details
	 **/
	 
	public function update_passenger_details($post,$userid,$photo)
	{
		$mdate = $this->currentdate; 

		$sql_query = (array('updated_date' => $mdate));

		$result = DB::update(PASSENGERS)->set(array('name'=> $post['name'],'lastname'=> $post['lastname'],'email'=> $post['email'],'country_code'=> $post['country_code'],'phone'=> $post['phone'],'address'=> $post['address']))
					->where('id', '=' ,$userid)
					->execute();
		$this->session->set("passenger_name",$post["name"]);
		$this->session->set("passenger_email",$post["email"]);
		$this->session->set("passenger_phone",$post["phone"]);
		$this->session->set("passenger_phone_code",$post["country_code"]);
		return ($result) ? 1 : 0;
	}
	
	/**
	 * Get passenger Cancelled Trips
	 * return array()
	 **/

	public function get_passenger_cancelled_trips($condition = "",$userid = "",$offset = "",$record = "")
	{
		$limit = ($condition) ? "limit $record offset $offset" : "";
		$query = "SELECT ".PASSENGERS_LOG.".passengers_log_id,used_wallet_amount,pickup_time,actual_pickup_time,driver_id,".TRANS.".distance,people.name,people.lastname,".TRANS.".payment_type,".TRANS.".fare,".MOTORMODEL.".model_name,".TRANS.".distance_unit FROM ".PASSENGERS_LOG." left join ".PEOPLE." on ".PEOPLE.".id = ".PASSENGERS_LOG.".driver_id left join ".TAXI." on ".TAXI.".taxi_id = ".PASSENGERS_LOG.".taxi_id left join ".MOTORMODEL." on ".MOTORMODEL.".model_id = ".TAXI.".taxi_model left join ".TRANS." on ".TRANS.".passengers_log_id = ".PASSENGERS_LOG.".passengers_log_id where passengers_id = '".$userid."' and (travel_status = 4 or (travel_status = 9 and driver_reply = 'C')) order by passengers_log_id desc $limit";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return ($condition) ? $result : count($result);
	}
	
	/**
	 * Get passenger completed Trips
	 * return array()
	 **/

	public function get_passenger_completed_trips($condition = "",$userid = "",$offset = "",$record = "")
	{
		$limit = ($condition) ? "limit $record offset $offset" : "";
		$query = "SELECT ".PASSENGERS_LOG.".passengers_log_id,used_wallet_amount,pickup_time,actual_pickup_time,driver_id,".TRANS.".distance,people.name,people.lastname,transacation.payment_type,".TRANS.".fare,".MOTORMODEL.".model_name,".TRANS.".distance_unit FROM ".PASSENGERS_LOG." left join ".PEOPLE." on ".PEOPLE.".id = ".PASSENGERS_LOG.".driver_id left join ".TAXI." on ".TAXI.".taxi_id = ".PASSENGERS_LOG.".taxi_id left join ".MOTORMODEL." on ".MOTORMODEL.".model_id = ".TAXI.".taxi_model left join ".TRANS." on ".TRANS.".passengers_log_id = ".PASSENGERS_LOG.".passengers_log_id where passengers_id = '".$userid."' and travel_status = '1' order by passengers_log_id desc $limit";
		$result = Db::query(Database::SELECT, $query)->execute()->as_array();
		return ($condition) ? $result : count($result);
	}

	/**
	 * Get passenger transaction details
	 * return array()
	 **/

	public function viewtransaction_details($log_id = "",$userid = "")
	{
		$query = " SELECT pl.drop_latitude,pl.drop_longitude,pl.pickup_latitude,pl.pickup_longitude,pl.actual_pickup_time,pl.passengers_log_id,pl.driver_id,pl.current_location,pl.drop_location,t.distance,pl.drop_time,t.tripfare,t.minutes_fare,t.company_tax,ps.used_wallet_amount,t.fare,pe.profile_picture,pe.name AS driver_name,pe.phone AS driver_phone,pa.name AS passenger_name,pl.company_tax AS org_tax,pa.email AS passenger_email,pa.phone AS passenger_phone,t.waiting_time as taxi_waiting_time,t.waiting_cost as taxi_waiting_cost,model.model_id,model.model_name,ta.taxi_no,t.payment_type,t.actual_distance,t.trip_minutes,t.waiting_time,t.distance_unit,t.current_date,t.nightfare_applicable,t.nightfare,t.eveningfare_applicable,t.eveningfare,t.passenger_discount FROM ".PASSENGERS_LOG." as pl left join ".TRANS." as t ON pl.passengers_log_id=t.passengers_log_id left join ".P_SPLIT_FARE." as ps ON ps.trip_id = pl.passengers_log_id Join ".COMPANY." as c ON pl.company_id=c.cid Join ".PEOPLE." as pe ON pe.id=pl.driver_id left join ".TAXI." as ta on ta.taxi_id = pl.taxi_id left join ".MOTORMODEL." as model on model.model_id = ta.taxi_model Join ".PASSENGERS." as pa ON pl.passengers_id=pa.id where ps.friends_p_id = '$userid' and pl.passengers_log_id = '$log_id'";
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $results;
	}
	
	public function get_location_details($trip_id)
	{
		$result = DB::select("active_record","drop_latitude","drop_longitude","pickup_latitude","pickup_longitude","current_location","drop_location")->from(PASSENGERS_LOG)
							->join(DRIVER_LOCATION_HISTORY)->on(PASSENGERS_LOG.'.passengers_log_id','=',DRIVER_LOCATION_HISTORY.'.trip_id')
							->where(PASSENGERS_LOG.'.passengers_log_id','=',$trip_id)
							->execute()
							->as_array();
		return $result;
	}

	// PHP strtotime compatible strings

	public function dateDiff($time1, $time2, $precision = 6)
	{
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
				/* if ($value != 1) {
					$interval .= "s";
				} */
				// Add value and interval to times array
				//$times[] = $value . " " . $interval;
				$times[] = $value;
				$count++;
			}
		}
		if(count($times) == 1) {
			$data = implode(":", $times);
			$times = explode(":","00:".$data);
		}
		if(count($times) == 2) {
			$data = implode(":", $times);
			$times = explode(":","00:".$data);
		}
		if(count($times) == 3) {
			$hours = (isset($times[0]) && strlen($times[0]) == 1) ? "0".$times[0] : $times[0];
			$minutes = (isset($times[1]) && strlen($times[1]) == 1) ? "0".$times[1] : $times[1];
			$seconds = (isset($times[2]) && strlen($times[2]) == 1) ? "0".$times[2] : $times[2];
			$times = explode(":",$hours.":".$minutes.":".$seconds);
		}

		return implode(":", $times);
	}

	public function viewdriver_tracking($trip_id)
	{ 
		$sql = "SELECT * FROM driver_location_history WHERE trip_id = '$trip_id'";
		$trip_check =  Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
		if(count($trip_check) > 0){ return $trip_check;  }else{ return 0;} 
	}
	
	public function getaddress($lat,$lng)
	{ 
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false&key='.GOOGLE_GEO_API_KEY;	
		$json = @file_get_contents($url);
		$data=json_decode($json);
		$status = isset($data->status) ? $data->status : "";
		if($status=="OK")
		return $data->results[0]->formatted_address;
		else
		return false;
	}
	
	/**
	 * Get recent trip details
	 * return array()
	 **/

	public function get_recent_trips($userid = "")
	{
		$query = " SELECT passengers_log_id,current_location,drop_location,pickup_time,name,lastname,country_code,phone,email,driver_id,profile_picture FROM ".PASSENGERS_LOG." left join ".PEOPLE." on ".PEOPLE.".id = ".PASSENGERS_LOG.".driver_id where travel_status = 1 and passengers_id = '".$userid."' order by passengers_log_id desc limit 3";
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		return $results;
	}
	
	/**
	 * Get upcomming trip details
	 * return array()
	 **/

	public function get_upcomming_trips($condition = "",$userid = "")
	{
		$alert = $alert_condition = ""; $array = array(); $passengers_log_id = 0; $alert_condition = "";
		if(!$condition) {
			$alert = "alert_notification,";
			$alert_condition = " and alert_notification = '0'";
		}
		/**
		 *Edited By Logeswaran 02-12-2016
		 *Ride Later List also show here
		 * added this condition travel_status=0 and now_after=1
		 */
		
		//$query = " SELECT $alert passengers_log_id,travel_status,driver_reply,current_location,drop_location,pickup_time,name,lastname,country_code,phone,email,driver_id,profile_picture,count(".PASSENGERS_CARD_DETAILS.".passenger_cardid) as creditcardCnt,".COMPANYINFO.".cancellation_fare FROM ".PASSENGERS_LOG." left join ".PEOPLE." on ".PEOPLE.".id = ".PASSENGERS_LOG.".driver_id left join ".PASSENGERS_CARD_DETAILS." on ".PASSENGERS_CARD_DETAILS.".passenger_id = ".PASSENGERS_LOG.".passengers_id left join ".COMPANYINFO." on ".COMPANYINFO.".company_cid = ".PASSENGERS_LOG.".company_id where (travel_status = 9 or travel_status = 3 or travel_status = 2 or travel_status = 5) and driver_reply = 'A' and passengers_id = '".$userid."' $alert_condition group by passengers_log_id order by passengers_log_id desc limit 1";
		$query = " SELECT $alert passengers_log_id,travel_status,driver_reply,current_location,drop_location,pickup_time,name,lastname,country_code,phone,email,driver_id,profile_picture,count(".PASSENGERS_CARD_DETAILS.".passenger_cardid) as creditcardCnt,".COMPANYINFO.".cancellation_fare FROM ".PASSENGERS_LOG." left join ".PEOPLE." on ".PEOPLE.".id = ".PASSENGERS_LOG.".driver_id left join ".PASSENGERS_CARD_DETAILS." on ".PASSENGERS_CARD_DETAILS.".passenger_id = ".PASSENGERS_LOG.".passengers_id left join ".COMPANYINFO." on ".COMPANYINFO.".company_cid = ".PASSENGERS_LOG.".company_id where (((travel_status = 9 or travel_status = 3 or travel_status = 2 or travel_status = 5) and driver_reply = 'A') OR (travel_status='0' and now_after='1')) and passengers_id = '".$userid."' $alert_condition group by passengers_log_id order by passengers_log_id desc";
		
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		if(!$condition) {
			$passengers_log_id = (isset($results[0]["passengers_log_id"])) ? $results[0]["passengers_log_id"] : 0;
			$array = array("count" => count($results),"trip_id" => $passengers_log_id);
		}
		return ($condition) ? $results : json_encode($array);
	}

	/**
	 * Get Passenger credit card details
	 * return array()
	 **/

	public function get_all_saved_card_details($passenger_id = "")
	{
		$sql = "select passenger_cardid,passenger_id,card_type,expdatemonth,default_card,expdateyear,creditcard_no,creditcard_cvv from ".PASSENGERS_CARD_DETAILS." where passenger_id='$passenger_id'";
		$result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
		return $result;
	}
	
	/**
	 * Get Passenger credit card details
	 * return array()
	 **/

	public function get_single_saved_card_details($card_id = "",$userid = "")
	{
		$sql = "select passenger_cardid,passenger_id,card_type,expdatemonth,default_card,expdateyear,creditcard_no,creditcard_cvv from ".PASSENGERS_CARD_DETAILS." where passenger_id='$userid' and passenger_cardid = '$card_id' limit 1";
		$result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
		return $result;
	}
	
	/**
	 * Validate card
	 * return array()
	 **/

	function isVAlidCreditCard($ccnum,$type="",$returnobj=false)
	{
		$creditcard = array(
						"visa"=>"/^4\d{3}-?\d{4}-?\d{4}-?\d{4}$/",
						"mastercard"=>"/^5[1-5]\d{2}-?\d{4}-?\d{4}-?\d{4}$/",
						"discover"=>"/^6011-?\d{4}-?\d{4}-?\d{4}$/",
						"amex"=>"/^3[4,7]\d{13}$/",
						"diners"=>"/^3[0,6,8]\d{12}$/",
						"bankcard"=>"/^5610-?\d{4}-?\d{4}-?\d{4}$/",
						"jcb"=>"/^[3088|3096|3112|3158|3337|3528|3530]\d{12}$/",
						"enroute"=>"/^[2014|2149]\d{11}$/",
						"switch"=>"/^[4903|4911|4936|5641|6333|6759|6334|6767]\d{12}$/"
					);
		if(empty($type)) {
			$match = false;
			foreach($creditcard as $type=>$pattern) {
				if(preg_match($pattern,$ccnum) == 1) {
					$match = true;
					break;
				}
			}
			if(!$match) { 
				return 0; 
			} else {
				if($returnobj) {
					$return=new stdclass;
					$return->valid=$this->_checkSum($ccnum);
					$return->ccnum=$ccnum;
					$return->type=$type;
					return 1;
				} else {
					return 0;
				}
			}
		} else {
			if(@preg_match($creditcard[strtolower(trim($type))],$ccnum)==0) {
				return false;
			} else {
				if($returnobj) {
					$return = new stdclass;
					$return->valid = $this->_checkSum($ccnum);
					$return->ccnum = $ccnum;
					$return->type = $type;
					return 1;
				} else {
					return 1;
				}
			}
		}
	}
	
	/**
	 * Function Used for validate credit cards 
	 **/

	function _checkSum($ccnum)
	{
		$checksum = 0;
		for ($i=(2-(strlen($ccnum) % 2)); $i<=strlen($ccnum); $i+=2) {
			$checksum += (int)($ccnum{$i-1});
		}
		// Analyze odd digits in even length strings or even digits in odd length strings.
		for ($i=(strlen($ccnum)% 2) + 1; $i<strlen($ccnum); $i+=2) {
			$digit = (int)($ccnum{$i-1}) * 2;
			if ($digit < 10) { 
				$checksum += $digit;
			} else {
				$checksum += ($digit-9);
			}
		}
		if (($checksum % 10) == 0) 
			return true; 
		else 
			return false;
	}
	
	/**
	 * Check card exist for passenger
	 **/

	public function check_card_exist($creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$passenger_id)
	{
		$creditcard_no = encrypt_decrypt('encrypt',$creditcard_no);
		$sql = "SELECT passenger_cardid FROM ".PASSENGERS_CARD_DETAILS." WHERE passenger_id='$passenger_id' and creditcard_no = '$creditcard_no'";
		$result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
		if(count($result) > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	
	/**
	 * Check card exist for passenger
	 **/

	public function edit_check_card_exist($passenger_cardid,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$passenger_id,$default)
	{
		$creditcard_no = encrypt_decrypt('encrypt',$creditcard_no);
		$sql = "SELECT passenger_cardid,default_card FROM ".PASSENGERS_CARD_DETAILS." WHERE passenger_id='$passenger_id' and creditcard_no = '$creditcard_no' and passenger_cardid != '$passenger_cardid' ";
		$result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
		if(count($result) > 0) { 
			$default_card = $result[0]['default_card'];
			if($default_card == $default) {
				return 2;
			} else {
				return 1;
			}
		} else {
			return 0;
		}
	}
	
	/**
	 * Edit Passenger Card Data
	 **/

	public function edit_passenger_carddata($passenger_cardid,$creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$passenger_id,$default,$card_type)
	{
		try {
			$creditcard_no = encrypt_decrypt('encrypt',$creditcard_no);
			if($default == 1) {
				$update_array = array("card_type" => $card_type,
					"creditcard_no" => $creditcard_no,
					"expdatemonth" => $expdatemonth,
					"creditcard_cvv" => $creditcard_cvv,
					"expdateyear" => $expdateyear,
					"default_card" => '1'
				);
				$array = array("default_card" => '0');
				$result = DB::update(PASSENGERS_CARD_DETAILS)
							->set($array)
							->where('passenger_id', '=', $passenger_id)
							->execute(); 
				$udate_result = DB::update(PASSENGERS_CARD_DETAILS)
								->set($update_array)
								->where('passenger_cardid', '=', $passenger_cardid)
								->execute();
			} else {
				$update_array = array("card_type" => $card_type,
						"creditcard_no" => $creditcard_no,
						"expdatemonth" => $expdatemonth,
						"creditcard_cvv" => $creditcard_cvv,
						"expdateyear" => $expdateyear
				);
				$udate_result = DB::update(PASSENGERS_CARD_DETAILS)
									->set($update_array)
									->where('passenger_cardid', '=', $passenger_cardid)
									->execute();
			}
			return 0;
		} catch (Kohana_Exception $e) {
			return 1;
		}
	}
	
	/** 
	 * Credit card delete function 
	 **/
	public function delete_card_details($passenger_cardid,$passenger_id)
	{
		$result=DB::delete(PASSENGERS_CARD_DETAILS)->where('passenger_cardid','=',$passenger_cardid)->where('passenger_id','=',$passenger_id)->execute();
		return $result;
	}
	
	/**
	 * Add Passenger New Card Data
	 **/

	public function add_passenger_carddata($creditcard_no,$creditcard_cvv,$expdatemonth,$expdateyear,$passenger_id,$default,$card_type,$email)
	{
		try {
			$email = urldecode($email);
			$creditcard_no = encrypt_decrypt('encrypt',$creditcard_no);
			//$creditcard_cvv = "";
			if($default == 1) {
				$array = array("default_card" => '0');
				$result = DB::update(PASSENGERS_CARD_DETAILS)
							->set($array)
							->where('passenger_id', '=', $passenger_id)
							->execute(); 
				$card_result = DB::insert(PASSENGERS_CARD_DETAILS, array('passenger_id','passenger_email','card_type','creditcard_no','expdatemonth','expdateyear','default_card','creditcard_cvv'))
								->values(array($passenger_id,$email,$card_type,$creditcard_no,$expdatemonth,$expdateyear,"1",$creditcard_cvv))
								->execute();
			} else {
				$card_result = DB::insert(PASSENGERS_CARD_DETAILS, array('passenger_id','passenger_email','card_type','creditcard_no','expdatemonth','expdateyear','default_card','creditcard_cvv'))
							->values(array($passenger_id,$email,$card_type,$creditcard_no,$expdatemonth,$expdateyear,"0",$creditcard_cvv))
							->execute();
			}
			return 0;
		} catch (Kohana_Exception $e) {
			return 1;
		}
	}
	
	/**
	 * Validate signin form fields
	 **/
	
	public function validate_signin_form($post) 
	{
		return Validation::factory($post)
			->rule('country_code', 'not_empty')
			->rule('country_code', 'min_length', array(':value', '2'))
			->rule('country_code', 'max_length', array(':value', '5'))
			->rule('mobile_number', 'not_empty')
			->rule('mobile_number', 'min_length', array(':value', '7'))
			->rule('mobile_number', 'max_length', array(':value', '15'))
			->rule('password', 'not_empty')
			->rule('password', 'min_length', array(':value', '6')) 
			->rule('password', 'max_length', array(':value', '24'));
	}
	
	/**
	 * Validate passenger details
	 **/

	public function validate_passenger_details($post) 
	{
		$country_code = $post['country_code'];
		$phone = Html::chars($post['mobile_number']);
		$password = Html::chars(md5($post['password']));
		$fb_id = isset($post['fb_id']) ? $post['fb_id'] : "";
		$fb_access_token = isset($post['fb_access_token']) ? $post['fb_access_token'] : "";
		$password = Html::chars(md5($post['password']));
		$query = "SELECT id,name,email,user_status,phone,country_code FROM ".PASSENGERS." WHERE country_code = '$country_code' and phone = '$phone' AND password='$password'";
		$result =  Db::query(Database::SELECT, $query)
					->execute()
					->as_array();
		if(count($result) == 1 && $result[0]['user_status'] == 'A') {
			//Whenever user logged into the application, Add their IP and other details..
			$login_time = $this->currentdate;
			$sql_query = array('last_login' => $login_time,'fb_user_id' => $fb_id,'fb_access_token' => $fb_access_token);
			$result_login = DB::update(PASSENGERS)
							->set($sql_query)
							->where('phone', '=', $phone)
							->execute();
			$this->session->set("passenger_name",$result[0]["name"]);
			$this->session->set("id",$result["0"]["id"]);
			$this->session->set("passenger_email",$result["0"]["email"]);
			$this->session->set("passenger_phone",$result["0"]["phone"]);
			$this->session->set("passenger_phone_code",$result[0]["country_code"]);

			$this->session->delete("fb_name");
			$this->session->delete("fb_email");
			$this->session->delete("fb_id");
			$this->session->delete("fb_access_token");

			if(isset($post['remember_me'])) {
				$this->session->set("remember_me",1);
				$this->session->set("passenger_password",$post['password']);
			}
			return 1;
		} elseif(count($result) == 1 && $result[0]['user_status'] == 'I') {
			return -1;
		} else {
			return 0;
		}
	}
	
	/**
	 * Validate forgot password form fields
	 **/
	
	public function validate_forgot_password_form($post) 
	{
		return Validation::factory($post)
			->rule('country_code', 'not_empty')
			->rule('country_code', 'min_length', array(':value', '2'))
			->rule('country_code', 'max_length', array(':value', '5'))
			->rule('mobile_number', 'not_empty')
			->rule('mobile_number', 'min_length', array(':value', '7'))
			->rule('mobile_number', 'max_length', array(':value', '20'));
	}
	
	/**
	 * Update forgot password to passenger
	 **/

	public function validate_forgot_password_details($post,$password) 
	{
		$country_code = $post['country_code'];
		$phone = Html::chars($post['mobile_number']);
		$query = "SELECT id,name,email FROM ".PASSENGERS." WHERE country_code = '$country_code' and phone = '$phone'";
		$result =  Db::query(Database::SELECT, $query)
					->execute()
					->as_array();
		if(count($result) == 1) {
			$sql_query = array('password' => md5($password));
			$result_login = DB::update(PASSENGERS)
							->set($sql_query)
							->where('phone', '=', $phone)
							->execute();
			return $result;
		} else {
			return array();
		}
	}
	
	public function get_driver_rating($driver_id = "")
	{
		$rating = 0;
		$query = " SELECT count(rating) as count, sum(rating) as rating FROM ".PASSENGERS_LOG." where rating > '0' and driver_id = '$driver_id'";
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		if(count($results) > 0) {
			if(isset($results[0]["rating"]) && $results[0]["rating"] > 0 && isset($results[0]["count"]) && $results[0]["count"] > 0) {
				$rating = round($results[0]["rating"] / $results[0]["count"]);
			}
		}
		return $rating;
	}
	
	/**
	 * Update Passenger Card Data default
	 **/

	public function change_default_card($passenger_id,$passenger_cardid)
	{
		$array = array("default_card" => '0');
		$result = DB::update(PASSENGERS_CARD_DETAILS)
					->set($array)
					->where('passenger_id', '=', $passenger_id)
					->execute();
		$result = DB::update(PASSENGERS_CARD_DETAILS)
					->set(array("default_card" => '1'))
					->where('passenger_id', '=', $passenger_id)
					->where('passenger_cardid', '=', $passenger_cardid)
					->execute();
		return 1;
	}
	
	/**
	 * Update upcoming alert count
	 **/

	public function change_upcoming_alert_count($passenger_id,$trip_id)
	{
		$result = DB::update(PASSENGERS_LOG)
					->set(array("alert_notification" => '1'))
					->where('passengers_id', '=', $passenger_id)
					->where('passengers_log_id', '=', $trip_id)
					->execute();
		return 1;
	}
	
	/**
	 * Check credit card exists
	 **/

	public function check_creditcard_exist($passenger_id,$creditcard_number)
	{
		$card = preg_replace('/\s+/', '', $creditcard_number);
		$creditcard_number = encrypt_decrypt('encrypt',$card);
		$sql = "SELECT passenger_cardid FROM ".PASSENGERS_CARD_DETAILS." WHERE passenger_id='$passenger_id' and creditcard_no = '$creditcard_number'";
		$result = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
		if(count($result) > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	
	/**
	 * Get upcoming alert below 30 min trip
	 **/
	
	public function get_upcoming_trips_alert($passenger_id,$time)
	{
		$query = " SELECT pickup_time FROM ".PASSENGERS_LOG." where travel_status = '9' and driver_reply = 'A' and passengers_id = '".$passenger_id."' and pickup_time >= '$time' order by passengers_log_id desc limit 1";
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		if(count($results) > 0) {
			$db_date = $results[0]["pickup_time"];
			$to_time = strtotime($db_date);
			$from_time = strtotime($time);
			return round(abs($to_time - $from_time) / 60,2);
		}
		return 0;
	}
	
	/** 
	 * Check user email already exists 
	 **/

	public function check_user_exists($email = "",$fb_user_id,$fb_access_token)
	{
		if($email != "") {
			$sql = "select id,user_status,name,email,phone,country_code from ".PASSENGERS." where email = '$email'";
			$result =  Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(count($result) == 1 && $result[0]['user_status'] == "A") {
				if($fb_user_id != "" && $fb_access_token != "") {
					$sql_query = array('fb_user_id' => $fb_user_id,"fb_access_token" => $fb_access_token);
					$update = DB::update(PASSENGERS)
								->set($sql_query)
								->where('email', '=', $email)
								->execute();
				}
				$this->session->set("passenger_name",$result[0]["name"]);
				$this->session->set("id",$result["0"]["id"]);
				$this->session->set("passenger_email",$result["0"]["email"]);
				$this->session->set("passenger_phone",$result["0"]["phone"]);
				$this->session->set("passenger_phone_code",$result[0]["country_code"]);
				return 1;
			} elseif(count($result) == 1 && $result[0]['user_status'] == 'D') {
				return -1;
			} else {
				return 0;
			}
		}
		return 0;
	}
	
	/**
	 * Validate passenger wallet amount
	 **/

	public function validate_wallet_amount($posted_value) 
	{
		return Validation::factory($posted_value)
		->rule('wallet_amount', 'not_empty')
		->rule('wallet_amount', 'Model_Siteusers::validate_wallet_amount_btn', array(':value'));
	}
	
	public static function validate_wallet_amount_btn($wallAmount)
	{
		if(isset($wallAmount) && $wallAmount != "")
		{
			if(($wallAmount < WALLET_AMOUNT_1) || ($wallAmount > WALLET_AMOUNT_3)) {
				return false;
			}
			return true;
		}
		return false;
	}
	
	public function getLaterRequestData()
    {
		$sql = "SELECT passengers_log_id,company_id,pickup_time,auto_send_request,trip_timezone FROM " . PASSENGERS_LOG . " WHERE (travel_status = 0 or travel_status = 7) and driver_reply = '' and bookingtype = 2 order by passengers_log_id asc";
		$result = Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
    }
    
    public function get_alldriver_rating()
	{
		$rating = 0;
		$query = " SELECT driver_id, count(rating) as count, sum(rating) as rating FROM ".PASSENGERS_LOG." where rating > '0' group by driver_id";
		$results = Db::query(Database::SELECT, $query)->execute()->as_array();
		$driver_ratings = array();
		if(count($results) > 0) {
			foreach($results as $r){
				if(isset($r["rating"]) && $r["rating"] > 0 && isset($r["count"]) && $r["count"] > 0) {
					$rating = round($r["rating"] / $r["count"]);
				}
				$driver_ratings[$r["driver_id"]] = $rating;
			}
		}
		return $driver_ratings;
	}
	
	public function smtp_settings(){
		
		$smtp_result = DB::select('smtp')
						->from(SMTP_SETTINGS)
						->where('id', '=', '1')
						->execute()
						->as_array(); 
		return !empty($smtp_result) ? $smtp_result : array();
	}
	
	public function validate_company_form($arr="")
	{
		return Validation::factory($arr)
			->rule('name', 'not_empty')
			->rule('email', 'not_empty')
			->rule('message', 'not_empty');
	}
	
	public function add_contact_us($post,$subject)
	{
		$result = DB::insert(CONTACTS, array('first_name','email','subject','message','phone', 'sent_date'))
				->values(array($post['name'],$post['email'],$subject,$post['message'],$post['phone'], $this->currentdate))
				->execute();
		return isset($result[0]) ? $result[0] : 0;
	}
	
	public function check_passenger_login_status($id)
	{
		$query= "SELECT user_status FROM ".PASSENGERS." WHERE id = '$id' AND user_status !='A'";

		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $result;
	}
	
	public function passenger_recharge_api_validation($array)
	{
		return Validation::factory($array)->rule('passenger_id','not_empty')->rule('recharge_code','not_empty');
	}
	
	public function check_passenger_recharge_code($array)
	{
		if(TIMEZONE) { 
			$current_time = convert_timezone('now',TIMEZONE);
			$current_date = explode(' ',$current_time);
			$start_time = $current_date[0].' 00:00:01';
			$end_time = $current_date[0].' 23:59:59';
		}else{
			$current_time =	date('Y-m-d H:i:s');
			$start_time = date('Y-m-d').' 00:00:01';
			$end_time = date('Y-m-d').' 23:59:59';
		}
		$driver_id=isset($array['passenger_id'])?$array['passenger_id']:'';
		$coupon_code=isset($array['recharge_code'])?$array['recharge_code']:'';
		
		$sql = "SELECT coupon_id FROM ".PASSENGERS_COUPON." where BINARY coupon_code='$coupon_code' ";  
		$result=Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
		if(count($result)>0)
		{
			 $sql2 = "SELECT coupon_id FROM ".PASSENGERS_COUPON." where BINARY coupon_code='$coupon_code' and coupon_status ='A'  ";  
				$result2=Db::query(Database::SELECT, $sql2)
				->execute() ->as_array();
			//echo count($result2);exit;
			if(count($result2)>0)
			{
					
					$sql3 = "SELECT coupon_id FROM ".PASSENGERS_COUPON." where BINARY coupon_code='$coupon_code' and coupon_used ='0'  ";  
					
					$result3=Db::query(Database::SELECT, $sql3)
					->execute()
					->as_array();
	
				if(count($result3)>0){
 
					$sql4 = "SELECT * FROM ".PASSENGERS_COUPON." where BINARY coupon_code='$coupon_code' and coupon_used ='0' and start_date <=  '$end_time' and expiry_date >= '$current_time' ";  
					$result4=Db::query(Database::SELECT, $sql4)
					->execute()
					->as_array();
					return $result4;
				}
				
				return -3;//Invalid already used  
			}
			return -2;//Blocked recharge code
		}
		return -1;//Invalid recharge code 
		
	}
	
	/***Update a recharge **/
	public function update_passenger_recharge_status($array,$recharge_id,$recharge_amount)
	{
		$passenger_id=isset($array['passenger_id'])?$array['passenger_id']:'';
		$recharge_code=isset($array['recharge_code'])?$array['recharge_code']:'';
		$passenger_balance=$this->get_passenger_acc_details($passenger_id);
		$account_balance=isset($passenger_balance[0]['wallet_amount'])?$passenger_balance[0]['wallet_amount']:'0';
		$driver_company_id=0;
		$total_balance=$account_balance+$recharge_amount;
		
		if(TIMEZONE)
		{
			
			$current_time = convert_timezone('now',TIMEZONE);
		}
		else
		{
			$current_time =	date('Y-m-d H:i:s');
		}
		
		
		$datas['receiver_id']=$array['passenger_id'];
	    $datas['receiver_pervious_balance']=$account_balance;
	    $datas['receiver_after_balance']=$total_balance;
	    $datas['transfer_amount']=$recharge_amount;
	    $datas['transfer_type']=1;
	    $datas['comments']="Coupon Recharge";
	    $datas['trip_id']=0;
	    
	    $Commonmodel = Model::factory('Commonmodel');			
	   
	    $add_logs = $Commonmodel->set_passenger_cash_transfer($datas);
		
		
		
		
		$update_array  = array("coupon_used"=>'1',"passenger_id"=>$passenger_id,"used_date"=>$current_time);
		$recharge_status_update = $this->update_table(PASSENGERS_COUPON,$update_array,'coupon_id',$recharge_id);
			
			/***Udpate total account balance to corresponding driver account***/
		$passenger_update_array  = array("wallet_amount"=>round($total_balance,2));
		$update_driver_amount = $this->update_table(PASSENGERS,$passenger_update_array,'id',$passenger_id);
			
		
		
	}
	
	public function get_passenger_recharge_history_count($passenger_id,$start=null,$limit=null){
		if(TIMEZONE)
		{
			$current_time = convert_timezone('now',TIMEZONE);
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
		$previous_date=date('Y-m-d H:i:s', strtotime($current_time. ' -30 days'));
		//and used_date >= '$previous_date'
		$sql = "select * from ((SELECT coupon_name,".PASSENGERS_COUPON." .amount,'Voucher code' AS recharage_by,CONCAT(SUBSTR(coupon_code, 1,3),REPEAT('X', CHAR_LENGTH(coupon_code) - 5),RIGHT(coupon_code,3)) as coupon_code,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date FROM ".PASSENGERS_COUPON."  where ".PASSENGERS_COUPON." .passenger_id='$passenger_id'  AND used_date <='$current_time' and coupon_status='A' and coupon_used='1' order by used_date desc ) 
                       UNION (SELECT '' AS coupon_name,passenger_prepaid_amt_details.amount,'CashU' AS recharage_by,'' AS coupon_code,IFNULL(DATE_FORMAT(".PASSENGER_PREPAID_AMT.".updated_date,'%e-%b-%Y %r'), '') AS used_date FROM ".PASSENGER_PREPAID_AMT." where passenger_id='$passenger_id' and updated_date >= '$previous_date' AND updated_date <='$current_time' ORDER BY updated_date DESC )) as temp_table ORDER BY used_date desc";
/*
		$sql = "SELECT coupon_name,amount,coupon_status,CONCAT(SUBSTR(coupon_code, 1,3),REPEAT('X', CHAR_LENGTH(coupon_code) - 2)) AS coupon_code,created_date,start_date,expiry_date AS end_date,coupon_used AS used_status,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date  FROM ".DRIVERS_COUPON." 
    	where ".DRIVERS_COUPON.".driver_id='$driver_id' and used_date >= '$previous_date' AND used_date <='$current_time' and coupon_status='A' and coupon_used='1'  order by used_date desc limit 0,30 ";  
*/		
		$result=Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
		return $result;
		
	}
	
	public function get_passenger_recharge_history($passenger_id,$start=null,$limit=null){
		if(TIMEZONE)
		{
			$current_time = convert_timezone('now',TIMEZONE);
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
		$previous_date=date('Y-m-d H:i:s', strtotime($current_time. ' -30 days'));
		//and used_date >= '$previous_date'
		$sql = "select * from ((SELECT added_by,coupon_name,".PASSENGERS_COUPON." .amount,'Voucher code' AS recharage_by,CONCAT(SUBSTR(coupon_code, 1,2),REPEAT('X', CHAR_LENGTH(coupon_code) - 6),RIGHT(coupon_code,2)) as coupon_code,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date FROM ".PASSENGERS_COUPON."  where ".PASSENGERS_COUPON." .passenger_id='$passenger_id'  AND used_date <='$current_time' and coupon_status='A' and coupon_used='1' order by used_date desc ) 
                       UNION (SELECT '' AS added_by,'' AS coupon_name,passenger_prepaid_amt_details.amount,'CashU' AS recharage_by,'' AS coupon_code,IFNULL(DATE_FORMAT(".PASSENGER_PREPAID_AMT.".updated_date,'%e-%b-%Y %r'), '') AS used_date FROM ".PASSENGER_PREPAID_AMT." where passenger_id='$passenger_id' and updated_date >= '$previous_date' AND updated_date <='$current_time' ORDER BY updated_date DESC )) as temp_table ORDER BY used_date desc limit $start,$limit";
/*
		$sql = "SELECT coupon_name,amount,coupon_status,CONCAT(SUBSTR(coupon_code, 1,3),REPEAT('X', CHAR_LENGTH(coupon_code) - 2)) AS coupon_code,created_date,start_date,expiry_date AS end_date,coupon_used AS used_status,IFNULL(DATE_FORMAT(used_date,'%e-%b-%Y %r'), '') AS used_date  FROM ".DRIVERS_COUPON." 
    	where ".DRIVERS_COUPON.".driver_id='$driver_id' and used_date >= '$previous_date' AND used_date <='$current_time' and coupon_status='A' and coupon_used='1'  order by used_date desc limit 0,30 ";  
*/		


		$result=Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
		//print_r($result); exit;
		return $result;
		
	}
	

	
	/***Update  Blocked voucher card used driver details **/
	public function update_passenger_blockedvoucherdetails($array)
	{
		$driver_id=isset($array['passenger_id'])?$array['passenger_id']:'';
		$recharge_code=isset($array['recharge_code'])?$array['recharge_code']:'';
		if(TIMEZONE){
			$current_time = convert_timezone('now',TIMEZONE);
		}else{
			$current_time =	date('Y-m-d H:i:s');
		}
		/*****Check if already blocked or not *******/
		$check_already_blocked=$this->check_passenger_already_blocked($driver_id,$recharge_code);
		if(count($check_already_blocked)>0)
		{
			return 1;
			
		}else{
		
		/***Blocked voucher card details ***/
			$history_result = DB::insert(PASSENGERS_RECHARGE_HISTORY, array('passenger_id','coupon_code','used_date'))
							->values(array($passenger_id,$recharge_code,$current_time))
							->execute();
			return $history_result;
		/***End Blocked voucher card details ***/
		}
			
			
	}
	
	public function check_passenger_already_blocked($passenger_id,$recharge_code)
	{
		$sql = "SELECT  passenger_id FROM ".PASSENGERS_RECHARGE_HISTORY." where passenger_id ='$passenger_id' AND coupon_code ='$recharge_code'  ";   
		$result=Db::query(Database::SELECT, $sql)
		->execute()->as_array();
		return $result;
	}
	
	public function get_passenger_acc_details($userid) 
	{		
		$query= "SELECT wallet_amount FROM ".PASSENGERS."  WHERE id = '$userid'";	
		$result =  Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
			return $result;
	}
	
	//Common Function for updation
	public function update_table($table,$arr,$cond1,$cond2)
	{
			$result=DB::update($table)->set($arr)->where($cond1,"=",$cond2)->execute();
			return $result;
	}
	
	//Passenger Profile
	public function passenger_profile($userid,$status='') 
	{		
		$stsCond = (!empty($status)) ? "AND user_status=:status" : "";
		$sql= "SELECT ".PASSENGERS.".wallet_amount,".PASSENGERS.".name,".PASSENGERS.".lastname,".PASSENGERS.".org_password,".PASSENGERS.".password,".PASSENGERS.".salutation,".PASSENGERS.".email,".PASSENGERS.".referral_code,".PASSENGERS.".profile_image,".PASSENGERS.".country_code,".PASSENGERS.".phone,".PASSENGERS.".discount,".PASSENGERS.".user_status,".PASSENGERS.".login_from FROM ".PASSENGERS." WHERE id =:id $stsCond";
		
		//echo $query;
		$query =  Db::query(Database::SELECT, $sql);
		$query->parameters(array(':id' => $userid,':status' => $status));
		$result=$query->execute()
				->as_array();
				
		return $result;	
	}
}

?>
