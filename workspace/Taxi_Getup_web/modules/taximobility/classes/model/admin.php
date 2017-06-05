<?php defined('SYSPATH') or die('No direct script access.');

/**************************************************

* Contains Admin(Login,Logout,Forgot Password,etc,...)details

* @Package: taximobility

* @Author: taximobility Team

* @URL : http://ndot.in/

*****************************************************/

class Model_Admin extends Model
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
		$this->mdate = commonfunction::getCurrentTimeStamp();
		//$this->DisplayDateTimeFormat = commonfunction::DisplayDateTimeFormat();
	}
 
 	public function get_db_language()
 	{
 		$lanuage_updated = DB::select('site_language')->from(SITE)
					->execute()
					->as_array();
		    return $lanuage_updated;
 	}
	
	/**
	 * ****admin_login()****
	 *
	 * @param $email varchar, $password varchar
	 * @return int one or zero
	 */
	public function admin_login($email = "", $password = "")
	{
        $password = md5($post_value_array['password']);
		$resultset = DB::select()->from(PEOPLE)
				->where('email', '=', $email)
				->where('password', '=', $password)
				->where('usertype', '=','A')
				->where('status','=','A') 
			    ->execute()
			    ->as_array();

	
		if(count($resultset) == 1){
		
			$this->session->set("email",$resultset[0]["email"]);
			$this->session->set("username",$resultset[0]["username"]);
			$this->session->set("id", $resultset[0]["id"]);			
			//$this->session->set("usertype", "A");
			$this->session->set("user_type", $resultset[0]["usertype"]);
			
			//Front end login
			$this->session->set("user_name",$resultset["0"]["username"]);
			$this->session->set("userid",$resultset["0"]["id"]);
			$this->session->set("usertype",$resultset["0"]["usertype"]);
			$this->session->set("user_email",$resultset["0"]["email"]);
			//	
			
			return 1;
	 	}
		else{
			$email = DB::select()->from(PEOPLE)
				           ->where('email', '=', $email)
						   ->execute()
			               ->as_array(); 
 
			if(count($email) == 0){
				return 2;
			}return 0;
		}
				
	}

	/**
	 * ****user_list()****
	 *
	 * @return user list array 
	 */
	public function user_list()
	{

	   $rs = DB::select()->from(PEOPLE)
				->order_by('name','ASC')
				->execute()
				->as_array();

	   return $rs;
	}

	/**
	 * ****count_user_list()****
	 *
	 * @return user list count of array 
	 */
	public function count_user_list()
	{
	
	 $rs = DB::select('id')->from(PEOPLE)
				->execute()
				->as_array();	

	 return count($rs);
	}


	/**
	 * ****count_user_login_list()****
	 *
	 * @return user list count of array 
	 */
	public function count_user_login_list()
	{
	
	 $rs = DB::select()->from(USER_LOGIN_DETAILS)
				->execute()
				->as_array();	

	 return count($rs);
	}

	/**
	 * ****all_user_list()****
	 *@param $offset int, $val int
	 *@return alluser list count of array 
	 */
	public function all_user_list($offset, $val)
	{

		$query = "select * from ". PEOPLE . " where status = '".ACTIVE."' or status ='".IN_ACTIVE."' order by created_date desc limit $offset,$val";  
		$result = Db::query(Database::SELECT, $query)
			    ->execute()
			    ->as_array();

	  return $result;
	}

	/**
	 * ****edit_users()****
	 *@param $current_uri int,$post_value_array array
	 *@return alluser list count of array 
	 */  
   public function edit_users($current_uri, $post_value_array,$image_name) 
    {
   
		$random_key = Commonfunction::admin_random_user_password_generator();

		$abt_me = isset($post_value_array['aboutme'])?$post_value_array['aboutme']:"";
		$status = isset($post_value_array['status'])? ACTIVE : IN_ACTIVE;
	
		$query = array('firstname' => $post_value_array['firstname'],
					  'lastname' => $post_value_array['lastname'], 'email' => $post_value_array['email'], 'username' => $post_value_array['username'],
					  'aboutme' => $abt_me,'paypal_account' => $post_value_array['paypal_account'],
					  'status' => $status,'activation_code' => $random_key,'updated_date' => $this->mdate,'country_id'=>$post_value_array['country_id']);

		if($image_name != "")  $query[ 'photo' ]=$image_name ;
		$result =  DB::update(PEOPLE)->set($query)
						->where('id', '=' ,$current_uri)
						->execute();
						
		if(count($result) > 0){
		
			$sql = "SELECT status FROM ".PEOPLE." WHERE id ='$current_uri' ";   
			$result=Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
				
			return $result;
		}
						
		
			
    }

	/**
	 * ****get_users_data()****
	 *@param $current_uri int
	 *@return alluser lists
	 */ 
	public function get_users_data($current_uri = '')
	{		 
	 	$rs   = DB::select()->from(PEOPLE)
				 ->where('id', '=', $current_uri)
			     ->execute()	
			     ->as_array();

		return $rs;
	}
	
	/**To Check User Name is Already Available or Not**/
	public static function unique_username($name)
	{
		// Check if the username already exists in the database
		$sql = "SELECT username FROM ".PEOPLE." WHERE username='$name' AND status!='D' ";   
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
	
	/*To Check UserName is Already Available while Edit User Details*/
	public static function unique_username_update($name,$id)
	{
		// Check if the username already exists in the database
		$sql = "SELECT username FROM ".PEOPLE." WHERE username='$name' AND id !='$id' AND status!='D' ";   
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

	/**Reset User Password if User Forgot Password**/

	public function forgot_password($array_data,$post_value_array,$random_key)
	{


		$pass= md5($random_key);
		// Create a new user record in the database
		$result = DB::update(PEOPLE)->set(array('password' => $pass,'updated_date' => $this->mdate ))->where('email', '=', $array_data['email'])
			->execute();
		if($result){
			 $rs = DB::select('username','email')->from(PEOPLE)
			 			->where('email','=', $post_value_array['email'])
			 			->where('status','=',ACTIVE)
						->execute()
						->as_array();
			 return $rs;
		}else{
			return 0;
			}

	}	
	
	/**Check Email Exist or Not while Updating User Details**/

	public function check_email_update($email="",$id="")
	{
		$sql = "SELECT email FROM ".PEOPLE." WHERE email='$email' AND id !='$id' AND status!='D' ";   
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

	/**
	 * ****add_users()****
	 *@return insert user values in database
	 */ 
	public function add_users($validator,$post_value_array,$image_name,$activation_key)
	{
		$randomkey = Commonfunction::admin_random_user_password_generator();

		$email = $post_value_array['email'];
	    $status = isset($post_value_array['status'])?"A":"I";
		$rs   = DB::insert(PEOPLE)
				->columns(array('firstname', 'lastname', 'email', 'username','paypal_account','photo','status','password','activation_code',
				'created_date','country_id'))
				->values(array($post_value_array['firstname'],$post_value_array['lastname'], 
							   $post_value_array['email'], $post_value_array['username'],$post_value_array['paypal_account'], 
							   $image_name,$status,md5($activation_key),$randomkey,$this->mdate,$post_value_array['country_id']))
				->execute();
		if($rs){
			$email = DB::select()->from(PEOPLE)
				           ->where('email', '=', $email)
						    ->execute()
			               ->as_array(); 
			return $email;
			}
		else{

 
			if(count($email) == 0){
				return 2;
			}
				return 0;
		}
						

	}

	//function for auto login while clicking activation_link	
	public function auto_user_login($activation_code)
	{
			$rs = DB::select('username','id')->from(PEOPLE)
							 ->where('activation_code', '=' ,$activation_code)
							 ->execute()
							 ->as_array();			

			if(count($rs) == 1){
				$this->session->set("UserName",$rs[0]["username"]);
				$this->session->set("UserId",$rs[0]["id"]);
				return 1;
			}
	}
	/**
	 * ****delete_users()****
	 *@param $current_uri int
	 *@delete user items
	 */
	public function delete_users($current_uri)
	{
			//get username and email for sending mail to users
			$username = DB::select('username','email')->from(PEOPLE)
							 ->where('id', '=', $current_uri)
							 ->execute()
							 ->as_array();		

			if($username)
			{
			$sql_query = array('status'=>USER_DELETE);
				//updated user details from database and set status "D"
			$rs   = DB::update(PEOPLE)
					 ->set($sql_query)
					 ->where('id', '=', $current_uri)
					 ->execute();

			}

		return $username;
	}
	
	/**
	 * ****delete_users_login()****
	 *@param $current_uri int
	 *@delete user login ip & browser details
	 */
	public function delete_users_login($user_login_chk)
    {
		$query = DB::delete(USER_LOGIN_DETAILS)
				->where('id', 'IN', $user_login_chk)   
			    ->execute();
	
			return 1;
	}

	/**
	 * ****export_data()****
	 *@export user listings
	 */
	public function export_data($keyword ="",$user_type ="",$status="")
	{
		
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		$xls_output = "<table border='1' cellspacing='0' cellpadding='5'>";
		$xls_output .= "<th>".__('name')."</th>";
		$xls_output .= "<th>".__('lastname')."</th>";
		$xls_output .= "<th>".__('email')."</th>";
		$xls_output .= "<th>".__('username')."</th>";
		$xls_output .= "<th>".__('created_date')."</th>";
		$xls_output .= "<th>".__('usertype_label')."</th>";
		$xls_output .= "<th>".__('status')."</th>";
		$file = 'Export';

		//condition for Usertype
		//====================== 
		$usertype_where= ($user_type) ? " AND usertype = '$user_type'" : "";

		
		//condition for status
		//====================== 

		$staus_where= ($status) ? " AND status = '".ACTIVE."' or status ='".IN_ACTIVE."' " : "";
	
		//search result export
        //=====================
		$name_where="";	

		if($keyword){
			$name_where  = " AND(name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!') ";
        }

			$query = " select distinct name,lastname,username,email,created_date,user_type,status from " . PEOPLE . " where 1=1  $usertype_where $staus_where $name_where order by created_date DESC";
			
	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();


		foreach($results as $result)
		{
			$status = ($result['status'] == "A") ? "Active" : "Inactive";
			$type   = ($result['user_type']== "A") ? "Admin" : "User";
			$xls_output .= "<tr>"; 
			$xls_output .= "<td>".mb_convert_encoding($result['name'],'utf-16','utf-8')."</td>"; 
			$xls_output .= "<td>".mb_convert_encoding($result['lastname'],'utf-16','utf-8')."</td>"; 
			$xls_output .= "<td>".$result['email']."</td>"; 
			$xls_output .= "<td>".mb_convert_encoding($result['username'],'utf-16','utf-8')."</td>"; 
			$xls_output .= "<td>".$result['created_date']."</td>"; 
			$xls_output .= "<td>".$type."</td>"; 
			$xls_output .= "<td>".$status."</td>"; 
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

	/**
	 * ****get_user_status()****
	 *@return user status
	 */
	public function get_user_status()
	{
        
	 	$rs   = DB::select('status')->from(PEOPLE)
				->group_by('status')
			    ->execute()->as_array();  

		return $rs;	
	}

	/**
	 * ****add_user_form()****
	 *@param $arr validation array
	 *@validation check
	 */
	public function validate_user_form($arr) 
	{

		$arr['firstname'] = trim($arr['firstname']);
		$arr['email'] = trim($arr['email']);
		//updated for trim of username while posting and not proper validation
		$arr['username'] = trim($arr['username']);
		return Validation::factory($arr)
			->rule('firstname','not_empty')
			//commented (front end no validation for alpha space and alpha dash)       
			//->rule('firstname','alpha_space')
			//->rule('lastname','alpha_dash')
			->rule('lastname', 'min_length', array(':value', '1'))
			->rule('firstname', 'min_length', array(':value', '4'))
			->rule('firstname', 'max_length', array(':value', '32'))
			->rule('file', 'Upload::type', array($files_value_array['photo'], array('jpg','jpeg', 'png', 'gif')))
			->rule('file', 'Upload::size', array($files_value_array['photo'],'2M'))
			->rule('email','not_empty')
			->rule('email','email')
			->rule('country_id','not_empty')
			->rule('paypal_account','email')
			->rule('username', 'not_empty')
			->rule('username', 'min_length', array(':value', '4'))
			->rule('username', 'max_length', array(':value', '30'));
	}


	/**
	 * ****edit_user_form()****

	 *@param $arr validation array
	 *@validation check
	 */
	public function validate_edit_user_form($arr) 
	{

		$arr['firstname'] = trim($arr['firstname']);
		$arr['email'] = trim($arr['email']);
		//updated for trim of username while posting and not proper validation
		$arr['username'] = trim($arr['username']);
		return Validation::factory($arr)
			->rule('firstname','not_empty')  
			//commented (front end no validation for alpha space and alpha dash)            
			//->rule('firstname','alpha_space')
			//->rule('lastname','alpha_dash')
			->rule('lastname', 'min_length', array(':value', '1'))			
			->rule('firstname', 'min_length', array(':value', '4'))
			->rule('firstname', 'max_length', array(':value', '32'))
			->rule('aboutme','not_empty')
			->rule('aboutme', 'min_length', array(':value', '10'))						
			->rule('file', 'Upload::type', array($files_value_array['photo'], array('jpg','jpeg', 'png', 'gif')))
			->rule('file', 'Upload::size', array($files_value_array['photo'],'2M'))
			->rule('email','not_empty')
			->rule('email','email')
			->rule('country_id','not_empty')
			->rule('paypal_account','email')
			->rule('username', 'not_empty')
			->rule('username', 'min_length', array(':value', '4'))
			->rule('username', 'max_length', array(':value', '30'));
	}

	/**
	 * ****my_info_form****
	 *@param $arr validation array
	 *@validation check
	 */
	public function validate_my_info_form($arr) 
	{

		$arr['firstname'] = trim($arr['firstname']);
		$arr['email'] = trim($arr['email']);
		//updated for trim of username while posting and not proper validation
		$arr['username'] = trim($arr['username']);
		return Validation::factory($arr)
			->rule('firstname','not_empty')  
		    //commented (front end no validation for alpha space and alpha dash)            
			//->rule('firstname','alpha_space')
			//->rule('lastname','alpha_dash')
			->rule('lastname', 'min_length', array(':value', '1'))			
			->rule('firstname', 'min_length', array(':value', '4'))
			->rule('firstname', 'max_length', array(':value', '32'))
			->rule('aboutme','not_empty')
			->rule('aboutme', 'min_length', array(':value', '10'))			
			->rule('file', 'Upload::type', array($files_value_array['photo'], array('jpg','jpeg', 'png', 'gif')))
			->rule('file', 'Upload::size', array($files_value_array['photo'],'2M'))
			->rule('email','not_empty')
			->rule('email','email')
			->rule('paypal_account','email')
			->rule('username', 'not_empty')
			->rule('username', 'min_length', array(':value', '4'))
			->rule('username', 'max_length', array(':value', '30'));
	}

	/**
	 * ****validate_login()****
	 *@param $arr validation array
	 *@validation check
	 */
	public function validate_login($arr)
	{

        //$post->pre_filter('ucfirst', 'email');
              
        return Validation::factory($arr)       
			//->rule('name','not_exists',array(':validation', 'email', ':field', array('password')));
            ->rule('email','not_empty')
			->rule('email','email')
			->rule('password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('password', 'not_empty');

	}

	/**
	 * ****get_all_search_list()****
	 *@param $keyword string, $user_type char, $status char
	 *@return search result string
	 */

	public function usersearch_list($keyword = "", $user_type = "", $status = "")
	{
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		//condition for status
		//====================== 
		$usertype_where= ($user_type) ? " AND user_type = '$user_type'" : "";

		
		//condition for status
		//====================== 

		$staus_where= ($status) ? " AND status = '$status'" : "";
	
		//search result export
        //=====================
		$name_where="";	

		if($keyword){
			$name_where  = " AND(name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
		}

			$query = " select * from " . PEOPLE . " where 1=1   $usertype_where $staus_where $name_where order by created_date DESC ";
			

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				return $results;

	}
   
	public function count_usersearch_list($keyword = "", $user_type = "", $status = "")
	{

			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		//condition for status
		//====================== 
		$usertype_where= ($user_type) ? " AND user_type = '$user_type'" : "";

		
		//condition for status
		//====================== 

		$staus_where= ($status) ? " AND status = '$status'" : "";
	
		//search result export
        //=====================
		$name_where="";	

		if($keyword){
			$name_where  = " AND(name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
		}

			$query = " select id from " . PEOPLE . " where 1=1   $usertype_where $staus_where $name_where order by created_date DESC";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				return count($results);

	}


	public function get_all_search_list($keyword = "", $user_type = "", $status = "",$offset = "", $val ="")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		//condition for status
		//====================== 
		$usertype_where= ($user_type) ? " AND user_type = '$user_type'" : "";

		
		//condition for status
		//======================
		$staus_where= ($status) ? " AND status = '$status'" : "";
	
		//search result export
		//=====================
		$name_where="";

		if($keyword){
			$name_where  = " AND(name LIKE  '%$keyword%' ";
			$name_where .= " or lastname LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ";
			$name_where .= " or username LIKE '%$keyword%' escape '!' ) ";
		}
			if($user_type == 'C'){
				$query = "SELECT ".PEOPLE.".id,".PEOPLE.".name,".PEOPLE.".lastname,".PEOPLE.".email,".PEOPLE.".phone,".PEOPLE.".address,".PEOPLE.".created_date,".PEOPLE.".user_type,".PEOPLE.".status FROM  ".COMPANY." LEFT JOIN  ".PEOPLE." ON ".PEOPLE.".id = ".COMPANY.".userid WHERE 1=1  and user_type !='A' $usertype_where $staus_where $name_where order by created_date DESC limit $val offset $offset ";
			}else{
				$query = " select id,name,lastname,email,phone,address,created_date,user_type,status from " . PEOPLE . " where 1=1 and  user_type !='A' $usertype_where $staus_where $name_where order by created_date DESC limit $val offset $offset";
			}
	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
				return $results;
	}
	
	/**
	 * ****get_all_passenger_search_list()****
	 *@param $keyword string, $user_type char, $status char
	 *@return passenger search result string
	 */

	public function passengersearch_list($keyword = "", $status = "")
	{
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);
		
		//condition for status
		//======================
		$staus_where= ($status) ? " AND user_status = '$status'" : "";
	
		//search result export
		//=====================
		$name_where="";	

		if($keyword){
			$name_where  = " AND(name LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' escape '!' ) ";
		}

			$query = " select * from " . PASSENGERS . " where 1=1  $staus_where $name_where order by created_date DESC ";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				return $results;

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
		$name_where="";	

		if($keyword){
			$name_where  = " AND(name LIKE  '%$keyword%' ";	
			$name_where .= " or email LIKE  '%$keyword%' escape '!' ) ";
		}

			$company_where= ($company) ? " AND passenger_cid = '$company'" : "";	
			$query = " select id from " . PASSENGERS . " where 1=1 $staus_where $name_where $company_where order by created_date DESC";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				return count($results);

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

		if($keyword){
			$name_where  = " AND(name LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%'  escape '!' ) ";
		}

			$company_where= ($company) ? " AND passenger_cid = '$company'" : "";
			if(trim($offset)!="" && trim($val)!=""){
				$query = " select id,phone,name,email,address,referral_code,wallet_amount,created_date,user_status from " . PASSENGERS . " where 1=1 $staus_where $name_where $company_where order by created_date DESC limit $val offset $offset";	
			}else{
				$query = "select id,phone,name,email,address,referral_code,wallet_amount,created_date,user_status from " . PASSENGERS . " where 1=1 $staus_where $name_where $company_where order by created_date DESC";
			}
			

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
				return $results;
	}


	/**
	 * ****get_sendemail_validation()****
	 *@param $arr validation array
	 *@validation check
	 */
	public function get_sendemail_validation($arr)
	{
		$arr['subject'] = trim($arr['subject']);
		$arr['message'] = trim($arr['message']);
		return Validation::factory($arr)       
			->rule('user_status','not_empty')
			->rule('to_user','not_empty')
			->rule('subject','not_empty')
			->rule('subject','alpha_space')
			->rule('subject', 'min_length', array(':value', '10'))
			->rule('subject', 'max_length', array(':value', '512'))			
			->rule('subject','alpha_space')
			->rule('message','not_empty')
			//->rule('message','alpha_space')
			->rule('message', 'min_length', array(':value', '20'))
			->rule('message', 'max_length', array(':value', '1024'));

    }

	public function image_upload($files_value_array)
	{
		
		return Validation::factory($files_value_array)

			->rule('photo','Upload::not_empty')
		    ->rule('photo','Upload::valid')
			->rule('photo','Upload::type',array('Upload::type',array('jpg','png','gif')));

	}

	/**
	 * ****sendemail()****
	 *@email send to too many (bulk) users
	 */
	public function sendemail($details,$headers,$variables,$from) 
	{

		//mail sending option to all users and insert userid in database
		//===============================================================
 		$user_id = "";
		$user_id = count($post_value_array['to_user']);

		for ($i=0; $i<$user_id;$i++) {
 
	 	    $to    = DB::select('email','username','status')->from(PEOPLE)
				     ->where('id','=',$post_value_array['to_user'][$i])
			         ->execute()
					 ->as_array(); 
					 
		//common email template not included so common email variable replace functionality hided
					 
		/*	$username = array(USERNAME => $to[$i]['username'],TO_MAIL => $to[$i]['email']);
		   $replace_variable = array_merge($variables,$username); 
		   
		   //send mail to user by defining value from here               
		   $mail = Commonfunction::get_email_template_details(1,$replace_variable);*/	
			$subject = $details['subject']; 
			$message = $details['message'];		   



         //creating object for model
         //=========================      
         $mail = Model::factory('commonfunction');

         $smtp_settings  = $mail->get_smtp_settings();
			$smtp_config = array('driver' => 'smtp','options' => array('hostname'=>$smtp_settings[0]['smtp_host'],
								'username'=>$smtp_settings[0]['smtp_username'],'password' => $smtp_settings[0]['smtp_password'],
								'port' => $smtp_settings[0]['smtp_port'],'encryption' => 'ssl')); 
										   
		   //send bulk mail to users
		   //======================= 
			if(Email::connect($smtp_config)){                         	

				if(Email::send($to[0]['email'],$from, $subject, $message,$html = true) == 0){                                  		                
					   // return 0;
				 }
				 //return 1;
			}else{
					  
				 if(mail($to[0]['email'], $from,$subject, $message, $headers)){                               
						//return 1;
				 }

			}


		   
			$result = DB::insert(BULKEMAIL)
						  ->columns(array('user_id','user_status','sent_date'))
						  ->values(array($post_value_array['to_user'][$i],$to[0]['status'],$this->mdate))
						  ->execute();
		}

	}

	/**
	 * **** get_user_type_list()****
	 *@param $email varchar
	 *@email send to users
	 */
	public function get_user_type_list($status_val,$validator,$error) 
	{
		//$status_name = isset($status_val["to_user"]) ? $status_val["to_user"] :'';
		$users_validator = array();
		if(count($validator) > 0 ){
		
		//echo $validator[0]['to_user']."test";
		        $users_validator = explode(",",$validator['validator'][0]['to_user']);		        
		}
		
		$status = ($status_val['status'] == 'A')?"A":"I";
	 	$rs     = DB::select('id','username')->from(PEOPLE)
						->where('status', '=',$status)
						->order_by('username','ASC')
			   	 	->execute()
			   	 	->as_array();  
		
		$build_dd="<select name='to_user[]' multiple='multiple' id='users'>";	
		foreach($rs as $result)
		{
			
			$selected=(in_array($result['id'],$users_validator)) ? "selected='selected' " :'';
			$name = ucfirst($result['username']);
			$build_dd .= "<option value='".$result['id']."' $selected >".$name."</option>";
		}
       echo $build_dd .= "</select>";
       if(count($error['errors']) > 0){
               $build_dd = "<span class='error'>";
               $build_dd .=$error['errors'][0]['to_user'];
               $build_dd .="</span>";
               echo $build_dd;
       }
	   exit;

	}

	/**
	 * ****all_user_login_list()****
	 *@param $offset int, $val int
	 *@return alluser list count of array 
	 */
	public function all_user_login_list($offset, $val)
	{

		//Query for listing login listings   
		$query  = "select ".USER_LOGIN_DETAILS.'.last_login'.','.USER_LOGIN_DETAILS.
				'.login_ip'.','.USER_LOGIN_DETAILS.'.user_agent'.','.USER_LOGIN_DETAILS.'.ban_ip'.',
				'.USER_LOGIN_DETAILS.'.id'.','.PEOPLE.'.username'." from ". USER_LOGIN_DETAILS .
				" left join ".PEOPLE. " on ". 	PEOPLE.'.id' .'='. USER_LOGIN_DETAILS.'.userid'." order by last_login DESC limit $offset, $val ";
	 
		$results = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();

	 	return $results;

	}
	
	/**
	 * ****get_all_user_login_search_list()***
	 *@param $keyword string, $user_type char, $status char
	 *@return search result string
	 */
		public function get_all_user_login_search_list($keyword = "")
	{

			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		$name_where = "";
		if($keyword){
			$name_where  = " WHERE 1=1 AND(username LIKE  '%$keyword%' escape '!' ";
			$name_where .= " OR ".USER_LOGIN_DETAILS.".login_ip LIKE  '%$keyword%' escape '!' ) ";
			//$name_where .= " or username LIKE '%$keyword%') ";
        }
		
		//Query for listing login listings   
		$query  = "select ".USER_LOGIN_DETAILS.'.last_login'.','.USER_LOGIN_DETAILS.
				'.login_ip'.','.USER_LOGIN_DETAILS.'.user_agent'.','.USER_LOGIN_DETAILS.'.ban_ip'.',
				'.USER_LOGIN_DETAILS.'.id'.','.PEOPLE.'.username'." from ". USER_LOGIN_DETAILS .
				" left join ".PEOPLE. " on ". 	PEOPLE.'.id' .'='. USER_LOGIN_DETAILS.'.userid'.$name_where." order by last_login DESC ";
	 
		$results = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();

	 	return $results;

   }		
	

	/**Check Image Exist or Not while Updating Job Details**/

	public function check_userphoto($userid="")
	{
		$sql = "SELECT photo FROM ".PEOPLE." WHERE id ='$userid'";   
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

			if(count($result) > 0)
			{ 

				return $result[0]['photo'];
			}
	}
	
	//update user photo null 
	public function update_user_photo($userid)
	{

		$sql_query = array('photo' => "");
		$result =  DB::update(PEOPLE)->set($sql_query)
			->where('id', '=' ,$userid)
			->execute();

		return 1;
	}


	//update site logo null 
	public function update_logo_image($id)
	{

		$sql_query = array('site_logo' => "");
		$result =  DB::update(SITE)->set($sql_query)
			->where('id', '=' ,$id)
			->execute();

		return 1;
	}
	
	
	public function change_password_validation($arr)
	{
		return Validation::factory($arr)       
			//->rule('old_password','alpha_dash')
			->rule('old_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('old_password', 'not_empty')
			->rule('old_password', 'min_length', array(':value', '4'))
			->rule('old_password', 'max_length', array(':value', '16'))
			->rule('new_password', 'not_empty')
			//->rule('new_password','alpha_dash')
			->rule('new_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('confirm_password', 'not_empty')
			//->rule('confirm_password','alpha_dash')
			->rule('confirm_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('confirm_password', 'min_length', array(':value', '4'))
			->rule('confirm_password',  'matches', array(':validation', 'old_password', 'confirm_password'))
			->rule('confirm_password', 'max_length', array(':value', '16'));
			
			

    }
       /**User Change Password**/

	public function change_password($array,$post_value_array,$userid="")
	{
		
		$pass=md5($array['confirm_password']);
		// Create a new user record in the database
		$result = DB::update(USERS)->set(array('password' => $pass ))->where('id', '=', $userid)
			->execute();
			
		if(count($result) == SUCESS)
		{
			$rs = DB::select('username','password','email')->from(PEOPLE)
				->where('id', '=', $userid)
				->execute()
				->as_array();
				
			return $rs;
		}

	}		

	/**Validating Change Password Details**/

	public function validate_changepwd($arr) 
	{ 
		return Validation::factory($arr)       
			->rule('old_password', 'not_empty')
			//->rule('old_password','alpha_dash')
			->rule('old_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('old_password', 'max_length', array(':value', '16'))
			->rule('new_password', 'not_empty')
			//->rule('new_password','alpha_dash')
			->rule('new_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			->rule('new_password', 'max_length', array(':value', '16'))
			->rule('confirm_password', 'not_empty')
			->rule('confirm_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			//->rule('confirm_password','alpha_dash')
			//->rule('confirm_password', 'equals', array(':validation', 'new_password'))
			->rule('confirm_password',  'matches', array(':validation', 'new_password', 'confirm_password'))
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
			->rule('conf_password','valid_password',array(':value','/^[A-Za-z0-9@#$%!^&*(){}?-_<>=+|~`\'".,:;[]+]*$/u'))
			//->rule('conf_password', array(':equals','new_password'))
			//->rule('conf_password', 'matches')
			->rule('conf_password', 'max_length', array(':value', '16'));
	}

	/**Check Whether the Eneterd Password is Correct While User Change Password**/

	public function check_pass($pass="",$userid="")
	{
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

	/**User Reset Password**/

	public function reset_password($array,$post_value_array,$id)
	{
		
		$pass=md5($array['conf_password']);
		// Create a new user record in the database
		$result = DB::update(PEOPLE)->set(array('password' => $pass ))->where('id', '=', $id)
			->execute();
		echo "ok";
		return 1;

	}

	/**
	 * ****count_user_messages_list()****
	 * @return user messages count 
	 */
	public function count_user_messages_list()
	{
	
	 $rs = DB::select()->from(USER_EMAIL)
				->execute()
				->as_array();	

	 return count($rs);
	}	
	
	/**
	 * ****all_user_messages_list()****
	 **param offset int,$val int
	 *@return all job_orders list 
	 */	
	public function all_user_messages_list($offset,$val)
	{

		//query to display all user messages listings	
		$query = " SELECT U.username AS receivername,U1.username AS sendername,U1.id AS usrid,
						UEB.subject,UEB.random_number,U1.usertype as sendertype,U1.status as senderstatus,
						UEB.flag_status,UEB.id,
						UEB.sent_date,UEB.order_no,UEB.id,
						JD.job_title,JD.job_url
						FROM ".USER_EMAIL." AS UEB
						LEFT JOIN ".PEOPLE." AS U ON ( U.id = UEB.receiver_id )
						LEFT JOIN ".PEOPLE." AS U1 ON ( U1.id = UEB.sender_id )
						LEFT JOIN ".JOB_DETAILS." AS JD ON ( JD.id = UEB.job_id )
						ORDER BY sent_date DESC LIMIT $offset,$val ";	
						
  
		$result = Db::query(Database::SELECT, $query)
		    	  ->execute()
		    	  ->as_array();

		return $result;			
		
		}	
		
	/**
	 * ****all_msg_sender_list****
	 *@return all buyers(who order job) list 
	 */		
	public function all_msg_sender_list()
	{
	
		//where condition included for empty entries in drop down (updated on 22/11/2011)
		$query =  " SELECT  DISTINCT U1.username AS buyername,U1.id AS userid1 
						FROM ".USER_EMAIL." AS UEB
						LEFT JOIN ".PEOPLE." AS U1 ON ( U1.id = UEB.sender_id )
						LEFT JOIN ".JOB_DETAILS." AS JD ON ( JD.id = UEB.job_id ) WHERE U1.id !=''
						ORDER BY U1.username ASC ";	

		$result = Db::query(Database::SELECT, $query)
			    	  ->execute()
			    	  ->as_array();
		    	  
		return $result;			
		
		}	
		
	/**
	 * ****all_msg_receiver_list****
	 *@return all seller(who doing job) list 
	 */		
	public function all_msg_receiver_list()
	{
		//where condition included for empty entries in drop down (updated on 22/11/2011)
		$query =  " SELECT  DISTINCT U.username AS sellername,U.id AS userid
						FROM ".USER_EMAIL." AS UEB
						LEFT JOIN ".PEOPLE." AS U ON ( U.id = UEB.receiver_id )
						LEFT JOIN ".JOB_DETAILS." AS JD ON ( JD.id = UEB.job_id ) WHERE U.id !=''
						ORDER BY U.username ASC ";	
  
		$result = Db::query(Database::SELECT, $query)
			    	  ->execute()
			    	  ->as_array();
		    	  
		return $result;			
		
		}			
		
		/**
		 * ****all_job_orders()****
		 */		
		public function all_job_orders()
		{
		   $rs = DB::select('order_no','id')->from(JOB_ORDERS)
					->execute()
					->as_array();
					
			return $rs;
			
			
			}
			
	/**
	 * ****get_all_user_messages_search_list()***
	 *@param $keyword string, $user_type char, $status char
	 *@return search result string
	 */
		public function get_all_user_messages_search_list($sender_search = "", $receiver_search = "", $order_search = "", $job_search ="")
	{

			$order_search = str_replace("%","!%",$order_search);
			$order_search = str_replace("_","!_",$order_search);

		//condition for job search
		//====================== 
		$job_where= ($job_search) ? " AND UE.job_id = '$job_search'" : "";

		//condition for sender name
		//==========================
		$sender_where= ($sender_search) ? " AND  UE.sender_id = '$sender_search'" : "";
		
		//condition for receiver name
		//===========================
		$receiver_where= ($receiver_search) ? " AND  UE.receiver_id = '$receiver_search'" : "";		
	
		//condition for job order search
		//===============================
		$job_order_where= ($order_search) ? " AND  UE.order_no LIKE '%$order_search%' escape '!' " : "";	


        
		$query = "SELECT UE.order_no,UE.subject,
					        U.username AS sendername,
					        U1.username AS receivername,
							  U.id AS usrid,
							  U.status as senderstatus,
							  U.usertype as sendertype,
							  UE.random_number,
							  JD.job_title,
							  JD.job_url,
							  UE.sent_date,
							  UE.id,
							  UE.flag_status
							  
						  FROM ".USER_EMAIL." AS UE
		       		  LEFT JOIN ".JOB_DETAILS." AS JD ON (JD.id = UE.job_id)
				 		  LEFT JOIN ".PEOPLE." AS U ON(U.id = UE.sender_id)
				 		  LEFT JOIN ".PEOPLE." AS U1 ON(U1.id = UE.receiver_id)
						  WHERE 1=1 $job_where $sender_where $receiver_where $job_order_where order by UE.sent_date DESC ";
						  
						  
 
	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
						 
						 
			return $results;

   }	

	//function for displaying job in drop down	
	public function all_job_list()
	{
		$query = " SELECT DISTINCT JD.job_url,JD.job_title,JD.id 
					  FROM ".USER_EMAIL." AS UE
		       	  LEFT JOIN ".JOB_DETAILS." AS JD ON (JD.id = UE.job_id)
		       	  WHERE JD.id = UE.job_id ORDER BY JD.job_title ASC ";
       	  
	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();		       	  

		return $results;		
		
		}

	/**
	 * ****update_flag_status()****
	 *@return update flag status in database
	 */
	public function update_flag_status($msg_id, $flag_status)
	{

		$db_set ="";
		switch ($flag_status){
			case ACT:
			// if status is ACTIVE means  
			//===========================
				$db_set = " flag_status = '".IN_ACTIVE."' ";
			break;	
			case INACT:
				// if status is IN_ACTIVE means
				//==============================
					$db_set = " flag_status = '".ACTIVE."' ";
				break;				
		}
		
		   $query = " UPDATE ". USER_EMAIL ." SET $db_set WHERE 1=1 AND id = '$msg_id' ";	


	      $result = Db::query(Database::UPDATE, $query)
			    ->execute();	
		  		 		    
			return $result;	 		    

	}

	
	/**
	 * ****update_sender_status()****
	 *@return update flag status in database
	 */
	public function update_sender_status($sender_id,$sender_status)
	{

		$db_set ="";
		switch ($sender_status){
			case 1:
			// if status is ACTIVE means  
			//===========================
				$db_set = " status = '".IN_ACTIVE."' ";
			break;	
			case 0:
				// if status is IN_ACTIVE means
				//==============================
					$db_set = " status = '".ACTIVE."' ";
				break;				
		}
		
		   $query = " UPDATE ". PEOPLE ." SET $db_set WHERE 1=1 AND id = '$sender_id' ";	


	      $result = Db::query(Database::UPDATE, $query)
			    ->execute();	
		  		 		    
			return $result;	 		    

	}		

	/**
	 * ****more_user_action()****
	 *@return delete,flag,unflag etc.....
	 */
	public function more_usermsg_action($type,$msg_id)
	{	
		//if action delete means
		//=======================
		if($type == "del"){	
			$query = DB::delete(USER_EMAIL)
					 ->where('id', 'IN', $msg_id)   
					 ->execute();
			return $type;		 
		}
		$db_set = $and_where= "";
		if(($type == "flag") || ($type == "unflag")){	
		
	
				//checking for more action to do using $type
				//===========================================	
				switch($type)
				{	
				
					//if action "flag" selected means
					//==================================		
					case FLAG:
					// if flag is selected set value is "A" 
					//===========================================
						$db_set = " flag_status = '".ACTIVE."' ";
						$and_where = " AND flag_status = '".IN_ACTIVE."' ";
						break;
					//if action "unflag" selected means
					//====================================		    
					case UNFLAG:
					// if unflag is selected set value is "I" 
					//==============================================  
						$db_set = " flag_status = '".IN_ACTIVE."' ";
						$and_where = " AND flag_status = '".ACTIVE."' ";  
				   	break;
				   						   		
		
				} 
				//update database with $msg_id and all other details(delete, flag, unflag)
				//======================================================================================
				$query = " UPDATE ". USER_EMAIL ." SET $db_set WHERE 1=1 AND id IN ('" . implode("','",$msg_id) . "') $and_where ";
				
			
				$result = Db::query(Database::UPDATE, $query)
						  		 ->execute();
		
				return $type;	
				
			}
		$and_where = "";	
		if(($type == "inactive") || ($type == "active")){
			
		//checking for more action to do using $type
		//===========================================	
		switch($type)
		{	
			//if action "INACTIVE_ACTION" selected means
			//=========================================
			case "inactive":
			
			// if inactive is selected set value is "A" 
			//===========================================
				$db_set = "status = '".IN_ACTIVE."' ";
				$and_where = " AND status = '".ACTIVE."' ";
				break;
			//if action "ACTIVE" selected means
			//====================================		    
			case "active":
			
			// if ACTIVE is selected set value is "A" 
			//==============================================  
				$db_set = "status = '".ACTIVE."' ";  
				$and_where = " AND status = '".IN_ACTIVE."' ";
		   	break;

		}
			
			//update database with $msg_id and all other details(delete, flag, unflag)
			//======================================================================================
			$query = " UPDATE ". PEOPLE ." AS U LEFT JOIN ".USER_EMAIL." AS UE ON (UE.sender_id = U.id) SET $db_set   
			WHERE 1=1 AND U.id != '$this->admin_session_id' AND UE.id IN ('" . implode("','",$msg_id) . "') $and_where ";
			
		
			$result = Db::query(Database::UPDATE, $query)
					  		 ->execute();
			return $type;
			
			}
			
			
	
	}
	
	/**
	 * ****count_contact_requests_list()****
	 *
	 * @return contact_details count of array 
	 */
	public function count_contact_requests_list()
	{
	
	 $rs = DB::select()->from(CONTACT_REQUEST)
				->execute()
				->as_array();	

	 return count($rs);
	}
	
	/**
	 * ****all_contact_requests_list()****
	 **param offset int,$val int
	 *@return all contact_request list 
	 */	
	public function all_contact_requests_list($offset,$val)
	{

		//query to display all contact_request listings	
		$query = " SELECT U.username AS username,CR.name AS name,
						CR.subject,CR.message,CS.subject AS subject1,
						CR.email,CR.telephone,CR.id,
						CR.ip,CR.request_date,CR.contact_request_reply
						FROM ".CONTACT_REQUEST." AS CR
						LEFT JOIN ".PEOPLE." AS U ON ( U.id = CR.user_id )
						LEFT JOIN ".CONTACT_SUBJECT." AS CS ON(CS.id = CR.contact_subjectid)
						ORDER BY CR.request_date DESC LIMIT $offset,$val ";	
						
  
		$result = Db::query(Database::SELECT, $query)
		    	  ->execute()
		    	  ->as_array();

		return $result;			
		
		}
		
	/**
	 * ****delete_contact_request()****
	 **param $deleteids array
	 *@return all delete_contact_request list 
	 */	
	public function delete_contact_request($deleteids)
	{
		//check whether id is exist in checkbox or single delete_contact_request
		//======================================================================
	    $deleteids = is_array($deleteids)?implode(",",$deleteids):$deleteids;
		$arr_chk = " id in ( $deleteids ) ";	
	
		$query = " Delete from ". CONTACT_REQUEST . " where $arr_chk ";	
		$result = Db::query(Database::DELETE, $query)
		    	  ->execute();

		return count($result);
	}


	/**
	 * ****get_contact_request_details()****
	 *@param $id int
	 *@return all contact request lists
	 */ 
	public function get_contact_request_details($id)
	{	
	
		//query to display all contact_request listings	
		$query = " SELECT CR.email,
								CR.subject,
							   CR.message,
							   CS.subject
						FROM ".CONTACT_REQUEST." AS CR
						LEFT JOIN ".CONTACT_SUBJECT." AS CS ON (CS.id = CR.contact_subjectid)
						WHERE CR.id = $id ";	 
  
		$result = Db::query(Database::SELECT, $query)
		    	  ->execute()
		    	  ->as_array();

		return $result;
	}

	
	public function validate_auto_reply_contact_form($arr)
	{
		$arr['subject'] = trim($arr['subject']);
		$arr['message'] = trim($arr['message']);
		return Validation::factory($arr)
			->rule('subject', 'not_empty')
			//->rule('subject', 'alpha_space')
			->rule('subject', 'min_length', array(':value', '5'))
			->rule('message','not_empty')	
			//->rule('message','alpha_space')
			->rule('message', 'min_length', array(':value', '5'));
		
		}
	
	/**
	 *****update_auto_reply_status()****
	 *@return update auto_reply_status in database
	 */
	 public function update_auto_reply_status($reply_id)
	 {

		$result =  DB::update(CONTACT_REQUEST)->set(array('contact_request_reply' => SUCESS))
						->where('id', '=' ,$reply_id)
						->execute();
		
		return $result;	 	

	 	}	
		
	/**
	 *****update_banIP_status****
	 *@return update BAN IP status in database
	 */
	public function update_banIP_status($id, $status)
	{

		$db_set ="";
		switch ($status){
			case 0:
			// if status is 0 means set block 
			//================================
				$db_set = " ban_ip = '".BLOCK."' ";
			break;	
		case 1:
			// if status is 1 means set unblock 
			//=================================
				$db_set = " ban_ip = '".UNBLOCK."' ";
			break;				
		}
		
		$query = " UPDATE ". USER_LOGIN_DETAILS ." SET $db_set WHERE 1=1 AND id = '$id' ";	

		
	    $result = Db::query(Database::UPDATE, $query)
			    ->execute();	
			    
		if($result){
			//get selected ip blocked/unblocked means get user email address
			//===============================================================
			$query_email = "select email,username,".USER_LOGIN_DETAILS.".login_ip,".USER_LOGIN_DETAILS.".ban_ip from ".USER_LOGIN_DETAILS.
							" left join ".PEOPLE. " on ".PEOPLE.'.id' .'='.USER_LOGIN_DETAILS.'.userid'.
				 		    " where ".USER_LOGIN_DETAILS.'.id'." = '$id' ";
				 		    
			$email_result = Db::query(Database::SELECT, $query_email)
					  ->execute()
					  ->as_array();
					  		 		    
			return $email_result;	 		    
		 }			     	

		

	}
	
		/**Validating Forgot Password Details**/
	public function validate_forgotpwd($arr) 
	{

		return Validation::factory($arr)       
			->rule('email', 'email')     
			->rule('email', 'max_length', array(':value', '50'))
			->rule('email', 'not_empty');
	}

	/**Check Whether Email is Already Exist or Not**/

	public function check_email_admin($email="")
	{
		$sql = "SELECT email,usertype FROM ".PEOPLE." WHERE email='$email' and usertype='".A."'";   
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

	public function get_curusrinfo($id="")
	{

		$sql = "SELECT login_ip FROM ".USER_LOGIN_DETAILS." WHERE id='$id' ";
		
		$result=Db::query(Database::SELECT, $sql)
			->execute()
			->as_array();

		return $result;
	
	}			
	
	/** Site Settings **/
	public function site_settings($id="")
	{
		$result = DB::select()->from(SITEINFO)
				->where('id', '!=', $id)
				->execute()
				->as_array();
		return  $result;
	}
	
	public function getCompanyTimezone($id="")
	{
		$result = DB::select()->from(SITEINFO)
				->where('id', '!=', $id)
				->execute()
				->as_array();
		return  $result;
	}
    public function site_country(){
		$result=DB::select()->from(COUNTRY)
			     ->where('country_status', '=', 'A')
			     ->execute()
			     ->as_array();
                return  $result;
	}
	public function site_city(){
		$result=DB::select()->from(CITY)
			     ->where('city_status', '=', 'A')
			     ->execute()
			     ->as_array();
			     return  $result;
	}

         /** validating the site info settings **/
        public function validate_updatesiteinfo($arr="",$files_value_array="")
        {
                        $validator = Validation::factory($arr)     
                                                   
                        ->rule('app_name', 'not_empty')
                        ->rule('app_name', 'max_length', array(':value', '250'))
                        
                        ->rule('site_tagline', 'not_empty')
                        ->rule('site_tagline', 'max_length', array(':value', '50'))
                        
                        ->rule('app_description', 'not_empty')
                        
                        ->rule('contact_email','not_empty')
                        ->rule('contact_email', 'max_length', array(':value', '30'))
                        ->rule('contact_email', 'email')     
                        
                        ->rule('phone_number','not_empty')
                        ->rule('phone_number', 'max_length', array(':value', '30'))
                        ->rule('tell_to_friend_message', 'not_empty')
                        ->rule('meta_keyword','not_empty')
                        
                        ->rule('driver_minimum_wallet_request','not_empty')
                        ->rule('driver_minimum_wallet_request','numeric')

                        ->rule('driver_maximum_wallet_request','not_empty')
                        ->rule('driver_maximum_wallet_request','numeric')

                        ->rule('wallet_request_per_day','not_empty')
                        ->rule('wallet_request_per_day','numeric')
                       

                        ->rule('tax','not_empty')
                        ->rule('tax','numeric')
                        ->rule('tax', 'Model_Admin::check_percentage', array(':value'))

                        ->rule('driver_trip_amount','not_empty')
                        ->rule('driver_trip_amount','numeric')
                        //->rule('driver_trip_amount', 'Model_Admin::check_percentage', array(':value'))
                        
                        //->rule('site_country','not_empty')
                        
                        ->rule('notification_settings','not_empty')
                        ->rule('notification_settings','numeric')

                        ->rule('admin_commission','not_empty')
			            ->rule('admin_commission','numeric')                       
			
                        ->rule('continuous_request_time','not_empty')
						->rule('continuous_request_time','numeric')

                        /*->rule('site_city','not_empty')
                        
                        ->rule('file', 'Upload::not_empty',array($files_value_array['site_logo']))
			->rule('file', 'Upload::type', array($files_value_array['site_logo'], array('jpg','jpeg', 'png', 'gif')))
			->rule('file', 'Upload::size', array($files_value_array['site_logo'],'2M'))*/
                        ->rule('site_copyrights','not_empty')
			->rule('fare_calculation','not_empty')
                        /* ->rule('site_currency','not_empty')
                        ->rule('site_currency', 'Model_Admin::checksite_currency', array(':value',$arr['currency_code']))
			->rule('currency_code','not_empty') */
                        ->rule('price_settings','not_empty')
                        ->rule('default_unit','not_empty')
                        ->rule('skip_credit_card','not_empty')
                        ->rule('cancellation_fare','not_empty')
                        ->rule('referral_settings','not_empty')
                        ->rule('referral_amount','not_empty')
                         ->rule('referral_amount','numeric')
                        ->rule('wallet_amount1','not_empty')
                        ->rule('wallet_amount1','numeric')
                        ->rule('wallet_amount2','not_empty')
                        ->rule('wallet_amount2','numeric')
                        ->rule('wallet_amount3','not_empty')
                        ->rule('wallet_amount3','numeric')
                        ->rule('wallet_amount1', 'Model_Admin::compare_wallet_amount1', array(':value',$arr['wallet_amount2'],$arr['wallet_amount3']))
                        ->rule('wallet_amount2', 'Model_Admin::compare_wallet_amount2', array(':value',$arr['wallet_amount3']))
                        ->rule('wallet_amount_range','not_empty')
                        ->rule('wallet_amount_range', 'Model_Admin::check_wallet_amount_range', array(':value',$arr['wallet_amount_range']))
                        ->rule('driver_referral_setting','not_empty')
                        ->rule('driver_referral_amount','not_empty')
                         ->rule('driver_referral_amount','numeric')
						->rule('ios_google_map_key','not_empty')
						->rule('ios_google_geo_key','not_empty')
						->rule('web_google_map_key','not_empty')
						->rule('web_google_geo_key','not_empty')
						->rule('google_timezone_api_key','not_empty')
						->rule('android_google_api_key','not_empty')
                        ->rule('show_map','not_empty')
                        ->rule('pagination_settings','not_empty')
                        ->rule('default_miles','not_empty')
                        ->rule('default_miles','numeric')
                        ->rule('user_time_zone','not_empty')
                        ->rule('date_time_format','not_empty');
						/*if(isset($files_value_array['banner_image']['name']) && $files_value_array['banner_image']['name']!=""){
							$validator = $validator->rule('banner_image','Upload::not_empty',array(":value"))
									->rule('banner_image','Upload::type', array(":value", array('jpg','jpeg', 'png', 'gif')))
									->rule('banner_image','Upload::size', array(":value",'2M'));
						}*/
						
						
				$validator = $validator->rule('banner_content','not_empty')
						->rule('app_content','not_empty')
						->rule('passenger_app_android_store_link','not_empty')
						->rule('passenger_app_ios_store_link','not_empty')
						->rule('app_android_store_link','not_empty')
						->rule('app_ios_store_link','not_empty')
						->rule('app_bg_color','not_empty')
                        ->rule('about_us_content','not_empty')
                        ->rule('about_bg_color','not_empty')
                        ->rule('footer_bg_color','not_empty')
                        ->rule('contact_us_content','not_empty')
                        ->rule('facebook_follow_link','not_empty')
                        ->rule('google_follow_link','not_empty')
						->rule('twitter_follow_link','not_empty');

			return $validator;

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
        /** validating the module settings **/
        public function validate_update_module1($arr="")
        {
                        return Validation::factory($arr)     
                                                   
                        ->rule('member', 'not_empty')
                        ->rule('member', 'max_length', array(':value', '2'));
                        
        }
        /** Updating the banner images **/
        public function update_module_settings_images1($image,$id)
        {
		
		$sql="select banner_image1 from ".CMS." where id='$id'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['banner_image1'])){
				$id1 = DOCROOT.BANNER_IMGPATH.$results[0]['banner_image1'];
				if(file_exists($id1)){
					$id1 = BANNER_IMGPATH.$results[0]['banner_image1'];
					unlink($id1);
				}
			}
			
		if($id > 0){
		if(isset($image))
		{
			$query = array('banner_image1' => $image,'type' =>'2');
		}

		$result =  DB::update(CMS)->set($query)
					->where('id', '=' ,$id)
					->execute();
		}else{
			$result   = DB::insert(CMS)
					->columns(array('banner_image1','type','status'))
					->values(array($image,'2','1'))
					->execute();
			$id = $result[0];
		}
			
		return $id;
        }
        
         public function update_module_settings_images2($image,$id)
        {
		
		$sql="select banner_image2 from ".CMS." where id='$id'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
				if(!empty($results[0]['banner_image2'])){
				$id2 = DOCROOT.BANNER_IMGPATH.$results[0]['banner_image2'];
				if(file_exists($id2)){
					$id2 = BANNER_IMGPATH.$results[0]['banner_image2'];
				unlink($id2);
				}
			}
			
		if($id > 0){
		
		if(isset($image))
		{
			$query = array('banner_image2' => $image,'type' =>'2');
		}

		$result =  DB::update(CMS)->set($query)
					->where('id', '=' ,$id)
					->execute();
		}else{
			$result   = DB::insert(CMS)
					->columns(array('banner_image2','type','status'))
					->values(array($image,'2','1'))
					->execute();
		}
		return $result;
        }
        
         public function update_module_settings_images3($image,$id)
        {
		
		$sql="select banner_image3 from ".CMS." where id='$id'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
					
				if(!empty($results[0]['banner_image3'])){
				$id3 = DOCROOT.BANNER_IMGPATH.$results[0]['banner_image3'];
				if(file_exists($id3)){
					$id3 = BANNER_IMGPATH.$results[0]['banner_image3'];
				unlink($id3);
				}
			}

		if($id > 0){
		
		if(isset($image))
		{
			$query = array('banner_image3' => $image,'type' =>'2');
		}

		$result =  DB::update(CMS)->set($query)
					->where('id', '=' ,$id)
					->execute();
		}else{
			$result   = DB::insert(CMS)
					->columns(array('banner_image3','type','status'))
					->values(array($image,'2','1'))
					->execute();
		}
		return $result;
        }
        
         public function update_module_settings_images4($image,$id)
        {
		
		$sql="select banner_image4 from ".CMS." where id='$id'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['banner_image4'])){
				$id4 = DOCROOT.BANNER_IMGPATH.$results[0]['banner_image4'];
				if(file_exists($id4)){
					$id4 = BANNER_IMGPATH.$results[0]['banner_image4'];
					unlink($id4);
				}
			}
		if($id > 0){
		
		if(isset($image))
		{
			$query = array('banner_image4' =>$image,'type' =>'2');
		}

		$result =  DB::update(CMS)->set($query)
					->where('id', '=' ,$id)
					->execute();
		}else{
			$result   = DB::insert(CMS)
					->columns(array('banner_image4','type','status'))
					->values(array($image,'2','1'))
					->execute();
		}
		return $result;
        }
        
         public function update_module_settings_images5($image,$id)
        {
		
		$sql="select banner_image5 from ".CMS." where id='$id'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['banner_image5'])){
				$id5 = DOCROOT.BANNER_IMGPATH.$results[0]['banner_image5'];
				if(file_exists($id5)){
					$id5 = BANNER_IMGPATH.$results[0]['banner_image5'];
					unlink($id5);
				}
			}
		if($id > 0){
		
		if(isset($image)){
			$query = array('banner_image5'=>$image,'type' =>'2');
		}

		$result =  DB::update(CMS)->set($query)
					->where('id', '=' ,$id)
					->execute();
		}else{
			$result   = DB::insert(CMS)
					->columns(array('banner_image5','type','status'))
					->values(array($image,'2','1'))
					->execute();
		}
		return $result;
        }
        /** Updating the module settings **/
        public function update_module_settings($post,$count)
        {
		if($count<=5)
		{
			$sql ="select * from ".CMS." where `order`!=0";
				$res =Db::query(Database::SELECT, $sql)
					->execute();
				$count1 = count($res);
				
			if(isset($post['member0']))
			{
				if($count1 > 0){
				$query = array('content' => $post['member0'],'alt_tags' =>$post['tags1'], 'type'=>'3','status' => '1','order' => '1');
				$rs   = DB::update(CMS)->set($query)
					->where('order','=','1')
					->execute();
					// Checked the updated query status isset
					if($rs == 1){
						$update_sstatus = $rs;
					}
					
				}else{
					$rs   = DB::insert(CMS)
						->columns(array('content','alt_tags','type','status','order'))
						->values(array($post['member0'],$post['tags1'],'3','1','1'))
						->execute();
					// Checked the updated query status isset
					if($rs == 1){
						$update_sstatus = $rs;
					}
				}
			}
			if(isset($post['member1']))
			{
				if($count1 > 0){
				$query = array('content' => $post['member1'],'alt_tags' =>$post['tags2'],'type'=>'3','status' => '1','order' => '2');
				$rs   = DB::update(CMS)->set($query)
					->where('order','=','2')
					->execute();
					// Checked the updated query status isset
					if($rs == 1){
						$update_sstatus = $rs;
					}
				}else{
					$rs   = DB::insert(CMS)
						->columns(array('content','alt_tags','type','status','order'))
						->values(array($post['member1'],$post['tags2'],'3','1','2'))
						->execute();
						
					// Checked the updated query status isset
					if($rs == 1){
						$update_sstatus = $rs;
					}
				}
			}
			if(isset($post['member2']))
			{
				if($count1 > 0){
				$query = array('content' => $post['member2'],'alt_tags' =>$post['tags3'],'type'=>'3','status' => '1','order' => '3');
				$rs   = DB::update(CMS)->set($query)
					->where('order','=','3')
					->execute();
					// Checked the updated query status isset
					if($rs == 1){
						$update_sstatus = $rs;
					}
					
				}else{
					$rs   = DB::insert(CMS)
						->columns(array('content','alt_tags','type','status','order'))
						->values(array($post['member2'],$post['tags3'],'3','1','3'))
						->execute();
						
					// Checked the updated query status isset
					if($rs == 1){
						$update_sstatus = $rs;
					}
				}
			}
			if(isset($post['member3']))
			{
				if($count1 > 0){
				$query = array('content' => $post['member3'],'alt_tags' =>$post['tags4'],'type'=>'3','status' => '1','order' => '4');
				$rs   = DB::update(CMS)->set($query)
					->where('order','=','4')
					->execute();
					
					// Checked the updated query status isset
					if($rs == 1){
						$update_sstatus = $rs;
					}
					
				}else{
					$rs   = DB::insert(CMS)
						->columns(array('content','alt_tags','type','status','order'))
						->values(array($post['member3'],$post['tags4'],'3','1','4'))
						->execute();
						
					// Checked the updated query status isset
					if($rs == 1){
						$update_sstatus = $rs;
					}
				}
			}
			if(isset($post['member4']))
			{
				if($count1 > 0){
				$query = array('content' => $post['member4'],'alt_tags' =>$post['tags5'],'type'=>'3','status' => '1','order' => '5');
				$rs   = DB::update(CMS)->set($query)
					->where('order','=','5')
					->execute();
					
					// Checked the updated query status isset
					if($rs == 1){
						$update_sstatus = $rs;
					}
					
				}else{
					$rs   = DB::insert(CMS)
						->columns(array('content','alt_tags','type','status','order'))
						->values(array($post['member4'],$post['tags5'],'3','1','5'))
						->execute();
					
					// Checked the updated query status isset
					if($rs == 1){
						$update_sstatus = $rs;
					}
				}
				
			}
			
			if(isset($update_sstatus)){
				return $update_sstatus;
			}else{
				return 0;
			}
		}
		
		
	}
	
	/** site logo **/
	public function updatesiteinfo_image($image)
	{
		$sql="select site_logo from ".SITEINFO." where id='1'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['site_logo'])){
				$id1 = $results[0]['site_logo'];
				if(file_exists($id1)){
				unlink($id1);
				}
			}
			
		$query = array('site_logo' => $image);
		$result =  DB::update(SITEINFO)->set($query)
					->where('id', '=' ,'1')
					->execute();	
			return $result;
	}
	
	/** site email logo **/
	public function updatesite_email_einfo_image($image)
	{
		$sql="select email_site_logo from ".SITEINFO." where id='1'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['email_site_logo'])){
				$id1 = $results[0]['email_site_logo'];
				if(file_exists($id1)){
				unlink($id1);
				}
			}
			
		$query = array('email_site_logo' => $image);
		$result =  DB::update(SITEINFO)->set($query)
					->where('id', '=' ,'1')
					->execute();	
			return $result;
	}
	
	/** site favicon image **/
	/** site logo **/
	public function updatesiteinfo_faviconimage($image)
	{
		$sql="select site_favicon from ".SITEINFO." where id='1'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['site_favicon'])){
				$id1 = $results[0]['site_favicon'];
				if(file_exists($id1)){
				unlink($id1);
				}
			}
			
		$query = array('site_favicon' => $image);
		$result =  DB::update(SITEINFO)->set($query)
					->where('id', '=' ,'1')
					->execute();
					
			return $result;
	}
	
	/** site banner image **/
	public function updatesiteinfo_bannerimage($image)
	{
		$sql="select banner_image from ".SITEINFO." where id='1'";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['banner_image'])){
				$id1 = $results[0]['banner_image'];
				if(file_exists($id1)){
					unlink($id1);
				}
			}
			
		$query = array('banner_image' => $image);
		$result =  DB::update(SITEINFO)->set($query)
					->where('id', '=' ,'1')
					->execute();			
		return $result;
	}
        
	public function updatesiteinfo($post_value_array)
	{
		//$php_format = $post_value_array['date_time_format'];
		$admin_commision_setting = isset($post_value_array['admin_commision_setting']) ? $post_value_array['admin_commision_setting'] : 0;
		$company_commision_setting = isset($post_value_array['company_commision_setting']) ? $post_value_array['company_commision_setting'] : 0;
		$driver_commision_setting = isset($post_value_array['driver_commision_setting']) ? $post_value_array['driver_commision_setting'] : 0;
		/* $SYMBOLS_MATCHING = array(
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
		$date_time_format_script = ($jqueryui_format != "") ? $jqueryui_format : yy-mm-dd hh:mm:ss"; */
		$date_time_format_script = "yy-mm-dd hh:mm:ss";
		$wallet_amount_range = $post_value_array['wallet_amount1'].'-'.$post_value_array['wallet_amount3'];
		$query = array(
			'app_name' => $post_value_array['app_name'],
			'app_description' => $post_value_array['app_description'],
			'email_id' => $post_value_array['contact_email'],
			'phone_number' => $post_value_array['phone_number'],
			'meta_keyword' => $post_value_array['meta_keyword'],
			'meta_description' => $post_value_array['meta_description'],
			'show_map' => $post_value_array['show_map'],
			'site_tagline' => $post_value_array['site_tagline'],
			'site_copyrights' => $post_value_array['site_copyrights'],
			'notification_settings'=>$post_value_array['notification_settings'],
			'pagination_settings'=>$post_value_array['pagination_settings'],
			'tell_to_friend_message'=>$post_value_array['tell_to_friend_message'],
			'admin_commission'=>$post_value_array['admin_commission'],				
			'tax'=>$post_value_array['tax'],
			'sms_enable'=>$post_value_array['sms_enable'],
			'default_unit'=>$post_value_array['default_unit'],
			'skip_credit_card'=>$post_value_array['skip_credit_card'],
			'cancellation_fare_setting'=>$post_value_array['cancellation_fare'],
			'fare_calculation_type'=>$post_value_array['fare_calculation'],
			'price_settings'=>$post_value_array['price_settings'],
			'referral_settings'=>$post_value_array['referral_settings'],
			'referral_amount'=>$post_value_array['referral_amount'],
			'wallet_amount1'=>$post_value_array['wallet_amount1'],
			'wallet_amount2'=>$post_value_array['wallet_amount2'],
			'wallet_amount3'=>$post_value_array['wallet_amount3'],
			'wallet_amount_range'=>$wallet_amount_range,
			'driver_referral_setting'=>$post_value_array['driver_referral_setting'],
			'driver_referral_amount'=>$post_value_array['driver_referral_amount'],
			'ios_google_map_key'=>$post_value_array['ios_google_map_key'],
			'ios_google_geo_key'=>$post_value_array['ios_google_geo_key'],
			'web_google_map_key'=>$post_value_array['web_google_map_key'],
			'web_google_geo_key'=>$post_value_array['web_google_geo_key'],
			'google_timezone_api_key'=>$post_value_array['google_timezone_api_key'],
			'android_google_key'=>$post_value_array['android_google_api_key'],
			'android_google_key'=>$post_value_array['android_google_api_key'],
			'default_miles'=>$post_value_array['default_miles'],
			'date_time_format'=>$post_value_array['date_time_format'],
			'user_time_zone' => $post_value_array['user_time_zone'],
			'date_time_format_script' => $date_time_format_script,
			'admin_commision_setting' => $admin_commision_setting,
			'company_commision_setting' => $company_commision_setting,
			'driver_commision_setting' => $driver_commision_setting,
			'banner_content'=>$post_value_array['banner_content'],
			'app_content'=>$post_value_array['app_content'],
			'passenger_app_android_store_link'=>$post_value_array['passenger_app_android_store_link'],
			'passenger_app_ios_store_link'=>$post_value_array['passenger_app_ios_store_link'],
			'app_android_store_link'=>$post_value_array['app_android_store_link'],
			'app_ios_store_link'=>$post_value_array['app_ios_store_link'],
			'app_bg_color'=>$post_value_array['app_bg_color'],
			'about_us_content'=>$post_value_array['about_us_content'],
			'about_bg_color'=>$post_value_array['about_bg_color'],
			'footer_bg_color'=>$post_value_array['footer_bg_color'],
			'contact_us_content'=>$post_value_array['contact_us_content'],
			'facebook_follow_link' => $post_value_array['facebook_follow_link'],
			'google_follow_link' => $post_value_array['google_follow_link'],
			'twitter_follow_link' => $post_value_array['twitter_follow_link'],
			'passenger_book_notify' => $post_value_array['passenger_book_notify'],
			'driver_trip_amount' => $post_value_array['driver_trip_amount'],
			'driver_minimum_wallet_request' => $post_value_array['driver_minimum_wallet_request'],
			'driver_maximum_wallet_request' => $post_value_array['driver_maximum_wallet_request'],
			'wallet_request_per_day' => $post_value_array['wallet_request_per_day']
		);
		//print_r($query);exit;
		//'site_country' => $post_value_array['site_country'],
		//'site_currency' => $post_value_array['site_currency'],
		//'currency_format'=>$post_value_array['currency_code'],
		//'taxi_charge'=>$post_value_array['taxi_charge'],	
		//'site_city' => $post_value_array['site_city'];
		//echo "site currency:".$post_value_array['site_currency']; echo "<br />";
		$result =  DB::update(SITEINFO)->set($query)->where('id', '=' ,'1')->execute();

		if($result && $post_value_array['prev_referral_amount'] != $post_value_array['referral_amount']) {
			//all passengers referral code amount updated here
			DB::update(PASSENGERS)->set(array("referral_code_amount"=>$post_value_array['referral_amount']))->execute();
		}
		//update referral amount of driver
		if($result && $post_value_array['prev_driver_referral_amount'] != $post_value_array['driver_referral_amount']) {
			//all drivers referral code amount updated here
			DB::update(DRIVER_REF_DETAILS)->set(array("registered_driver_code_amount"=>$post_value_array['driver_referral_amount']))->execute();
		}
		return $result;
	}
        
        public function validate_update_socialinfo($arr="")
        {
                        return Validation::factory($arr)     
                                                   
                        ->rule('facebook_key', 'not_empty')
                        ->rule('facebook_secretkey', 'not_empty')
                        //->rule('twitter_key','not_empty')
                        //->rule('twitter_secretkey','not_empty')
                        ->rule('facebook_share','not_empty')
                        ->rule('twitter_share','not_empty')
                        ->rule('google_share','not_empty')
                        ->rule('linkedin_share','not_empty');

        }
        
        public function update_socialinfo($post_value_array)
        {
        		$query = array('facebook_key' => $post_value_array['facebook_key'],
					'facebook_secretkey' => $post_value_array['facebook_secretkey'],
					'twitter_key' => "",//$post_value_array['twitter_key'],
					'twitter_secretkey' => "",//$post_value_array['twitter_secretkey'],
					'facebook_share' => $post_value_array['facebook_share'],
					'twitter_share' => $post_value_array['twitter_share'],
					'google_share' => $post_value_array['google_share'],
					'linkedin_share' => $post_value_array['linkedin_share'],
					);

		$result =  DB::update(SITEINFO)->set($query)
						->where('id', '=' ,'1')
						->execute();

		return $result;	
        }
        
        public function validate_update_payment_submit($arr="")
        {	
                        return Validation::factory($arr)     
                                                   
                        ->rule('payment_gatway_name', 'not_empty')
                        ->rule('description', 'not_empty')
                        ->rule('currency_code','not_empty')
                        ->rule('currency_code', 'max_length', array(':value', '3'))
                        ->rule('currency_symbol','not_empty')
                        ->rule('currency_symbol', 'max_length', array(':value', '1'))
                        ->rule('payment_method','not_empty')
                        ->rule('paypal_api_username','not_empty')
                        ->rule('paypal_api_password','not_empty')
                        ->rule('paypal_api_signature','not_empty');



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

        public function update_payment_submit($post_value_array)
        {
		
		/*$query = array('payment_gatway' => $post_value_array['payment_gatway_name'],
				'description' => $post_value_array['description'],
				'currency_code' => $post_value_array['currency_code'],
				'currency_symbol' => $post_value_array['currency_symbol'],
				'payment_method' => $post_value_array['payment_method'],
				'paypal_api_username' => $post_value_array['paypal_api_username'],
				'paypal_api_password' => $post_value_array['paypal_api_password'],
				'paypal_api_signature' => $post_value_array['paypal_api_signature']);

		$result =  DB::update(PAYMENT_GATEWAYS)->set($query)
					->where('id', '=' ,'1')
					->execute(); */

		$update = 0;

		foreach($post_value_array['payid'] as $k=>$id){  
		
			if($id == $post_value_array['default'][0]){
				$default = '1';
			}
			else
			{
				$default = '0';
			}

			if(in_array($id,$post_value_array['paymodstatus']))
			{
				$paystatus = '1';
			}
			else
			{
				$paystatus = '';
			}
	
			$update_result =  DB::update(PAYMENT_MODULES)->set(array('pay_mod_active' => $paystatus, 'pay_mod_default' => $default))->where('pay_mod_id', '=' ,$id)->execute();

			if(($update_result == 1) && ($update ==0))	
			{	
				$result = 1;
			}else{
				$result = 0;
			}

		}

					
		return $result;	
        }

	public function admin_payment_submit($post_value_array,$id)
	{
	$query = array('payment_gatway' => $post_value_array['payment_gatway_name'],
				'description' => $post_value_array['description'],
				'currency_code' => $post_value_array['currency_code'],
				'currency_symbol' => $post_value_array['currency_symbol'],
				'payment_method' => $post_value_array['payment_method'],
				'paypal_api_username' => $post_value_array['paypal_api_username'],
				'paypal_api_password' => $post_value_array['paypal_api_password'],
				'paypal_api_signature' => $post_value_array['paypal_api_signature']);

		$result =  DB::update(PAYMENT_GATEWAYS)->set($query)
					->where('id', '=' ,$id)
					->execute();

		return count($result);

	}
        
    public function site_payment_gateways(){
		$result=DB::select('id')->from(PAYMENT_GATEWAYS)
			     ->where('id', '=', '1')
			     ->execute()
			     ->as_array();
			     return  $result;
	}

	public function get_payment_gateways($offset, $val){
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
		
				
		}
		else if($usertype =='C')
		{
	
				
		}
		else
		{
			$result=DB::select('id','payment_status','payment_gatway','default_payment_gateway')->from(PAYMENT_GATEWAYS)
			     ->where('payment_status','!=','T')
			     ->where('company_id','=','0')
			     ->limit($val)->offset($offset)
			     ->execute()
			     ->as_array();
			     return  $result;
			
		
		}
	}

	public function count_paymentgateway_list(){
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
	   	$company_id = $_SESSION['company_id'];
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
	   	
	   	if($usertype =='M')
	   	{
		
				
		}
		else if($usertype =='C')
		{
	
				
		}
		else
		{
			$result=DB::select('id')->from(PAYMENT_GATEWAYS)
			     ->where('payment_status','!=','T')
			     ->execute()
			     ->as_array();
			     return  count($result);
			
		
		}
	}

	public function get_payment_gateway_detail($id){
		$result=DB::select()->from(PAYMENT_GATEWAYS)
			     ->where('id', '=', $id)
			     ->execute()
			     ->as_array();
			     return  $result;
	}

	/** update default country status **/
	public function update_default_payment($id)
	{
	
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
						->where('company_id', '=','0')
					->execute();
				}
				
				return $result;
			}
			else
			{
				return -1;
			}
			 
			 
	}


	
        public function mail_settings($id="")
        {
                 $result=DB::select('smtp_username', 'smtp_password', 'smtp_host', 'transport_layer_security', 'smtp_port', 'smtp')->from(SMTP_SETTINGS)
			     ->where('id', '!=', $id)
			     ->execute()
			     ->as_array();
        
                return  $result;

        }

        public function sms_template($id="")
        {
                 $result=DB::select('sms_id','sms_info','status')->from(SMS_TEMPLATE)
			     ->order_by('sms_id')
			     ->execute()
			     ->as_array();
        
                return  $result;

        }        
        public function validate_mailsettings($arr="")
        {
                        return Validation::factory($arr)     
                                                   
                        ->rule('smtp_host', 'not_empty')
                        ->rule('smtp_host', 'max_length', array(':value', '50'))
                        
                        ->rule('smtp_port', 'not_empty')
                        ->rule('smtp_port', 'max_length', array(':value', '4'))
                        
                        ->rule('smtp_username','not_empty')
                        ->rule('smtp_username', 'max_length', array(':value', '50'))
                        
                        ->rule('smtp_password','not_empty')
                        ->rule('smtp_password', 'max_length', array(':value', '50'))

			->rule('transport_layer_security','not_empty')
			->rule('smtp','not_empty');

                        

        }
        
        public function updatemailsetting($post_value_array)
        {
		$query = array('smtp_host' => $post_value_array['smtp_host'],
				'smtp_port' => $post_value_array['smtp_port'],
				'smtp_username' => $post_value_array['smtp_username'],
				'smtp_password' => $post_value_array['smtp_password'],
				'transport_layer_security' => $post_value_array['transport_layer_security'],
				'smtp' => $post_value_array['smtp'] );

		$result =  DB::update(SMTP_SETTINGS)->set($query)
					->where('id', '=' ,'1')
					->execute();
		return $result;	
	}
        
	public function get_activeusers_list()
	{
		$results = DB::select()->from(PASSENGERS)
				->where('login_status', '=', 'A')
				->order_by('last_login','desc')
				->limit('0,10')
				->execute()
				->as_array();
	 	return $results;
	}
	
	//get all active users list
	public function get_all_activeusers_list($cid=0)
	{
		/*$results = DB::select()->from(PASSENGERS)
				->where('login_status', '=', 'A')
				->order_by('last_login','desc')
				->execute()
				->as_array();*/
		$condition = "";
		if(!empty($cid)) {
			$condition = " and ".PASSENGERS.".passenger_cid='$cid'";
		}
		$sql = "select * from ".PASSENGERS." where ".PASSENGERS.".login_status='A' $condition order by ".PASSENGERS.".last_login desc";
		//echo $sql;exit;
		$results = Db::query(Database::SELECT, $sql)->execute()->as_array();
	 	return $results;
	}
	//dashboard active users count
	public function get_activeusers_list_count($cid=0)
	{
		/*$results = DB::select()->from(PASSENGERS)
				->where('login_status', '=', 'A')
				->order_by('last_login','desc')
				->execute()
				->as_array();*/
		$condition = "";
		if(!empty($cid)) {
			$condition = " and ".PASSENGERS.".passenger_cid='$cid'";
		}
		$sql = "select * from ".PASSENGERS." where ".PASSENGERS.".login_status='A' $condition order by ".PASSENGERS.".last_login desc";
		$results = Db::query(Database::SELECT, $sql)->execute()->as_array();
	 	return count($results);
	}
	
	//get all active user list
	public function all_users_list($offset='', $val='',$cid=0)
	{
		/*$results = DB::select()->from(PASSENGERS)
				->where('login_status', '=', 'A')
				->order_by('last_login','desc')
				->limit($val)
				->offset($offset)
				->execute()
				->as_array();*/
		$condition = "";
		if(!empty($cid)) {
			$condition = " and ".PASSENGERS.".passenger_cid='$cid'";
		}
		$sql = "select * from ".PASSENGERS." where ".PASSENGERS.".login_status='A' $condition order by ".PASSENGERS.".last_login desc limit $offset,$val";
		$results = Db::query(Database::SELECT, $sql)->execute()->as_array();	
		return $results;
	}
	
	public function get_availabletaxi_list()
	{
	
		$currentdate = date('Y-m-d H:i:s');
		$enddate = date('Y-m-d').' 23:59:59';
			
		$query_where = " AND ( ( '$currentdate' between mapping_startdate and  mapping_enddate ) or ( '$enddate' between mapping_startdate and  mapping_enddate) )";	
						

		$query = " select * from " . TAXIMAPPING . " left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id left join ".DRIVER." on ".TAXIMAPPING.".mapping_driverid =".DRIVER.".driver_id where ".TAXIMAPPING.".mapping_status = 'A'  and ".DRIVER.".status='F' and ".COMPANY.".company_status='A' and ".COUNTRY.".country_status='A' and ".STATE.".state_status='A' and ".CITY.".city_status='A' and ".TAXI.".taxi_status='A' and ".TAXI.".taxi_availability='A' and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and people.user_type='D' $query_where order by mapping_startdate ASC limit 0,10 ";

		

	
		$results = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
			return $results;
	}
	
	
	public function get_availabletaxi_list_count()
	{
	
		$currentdate = date('Y-m-d H:i:s');
		$enddate = date('Y-m-d').' 23:59:59';
			
		$query_where = " AND ( ( '$currentdate' between mapping_startdate and  mapping_enddate ) or ( '$enddate' between mapping_startdate and  mapping_enddate) )";	
						

		$query = " select * from " . TAXIMAPPING . " left join " .TAXI. " on ".TAXIMAPPING.".mapping_taxiid =".TAXI.".taxi_id left join " .COMPANY. " on " .TAXIMAPPING.".mapping_companyid = " .COMPANY.".cid left join ".COUNTRY." on ".TAXIMAPPING.".mapping_countryid = ".COUNTRY.".country_id left join ".STATE." on ".TAXIMAPPING.".mapping_stateid = ".STATE.".state_id left join ".CITY." on ".TAXIMAPPING.".mapping_cityid = ".CITY.".city_id  left join ".PEOPLE." on ".TAXIMAPPING.".mapping_driverid =".PEOPLE.".id left join ".DRIVER." on ".TAXIMAPPING.".mapping_driverid =".DRIVER.".driver_id where ".TAXIMAPPING.".mapping_status = 'A'  and ".DRIVER.".status='F' and ".COMPANY.".company_status='A' and ".COUNTRY.".country_status='A' and ".STATE.".state_status='A' and ".CITY.".city_status='A' and ".TAXI.".taxi_status='A' and ".TAXI.".taxi_availability='A' and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' and people.user_type='D' $query_where order by mapping_startdate ASC ";



	
		$results = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
			return count($results);
	}
	
	public function get_comapny_countlist()
	{
		/*echo DB::select()->from(COMPANY)
				->join(PEOPLE)->on(PEOPLE.'.id', '=' ,COMPANY.'.userid')
				//->where(PEOPLE.'.company_id', '=' ,COMPANY.'.cid')
				->where(PEOPLE.'.status', '=', 'A');exit;*/
		$results = DB::select('cid')->from(COMPANY)
				->join(PEOPLE)->on(PEOPLE.'.id', '=' ,COMPANY.'.userid')
				//->where(PEOPLE.'.company_id', '=' ,COMPANY.'.cid')
				->where(PEOPLE.'.status', '=', 'A')
				->execute();
			return count($results);
	}
	
	public function get_passenger_countlist()
	{
		$results = DB::select('id')->from(PEOPLE)
				->where('user_type', '=', 'N')
				->where('account_type', '=', '2')
				->where('status', '=', 'A')
				->execute();
	 	return count($results);
	}
	
	public function count_passenger_list_history()
	{	
		$rs = DB::select('id')->from(PASSENGERS)
				->where('user_status', '=', 'A')
				->order_by('created_date','desc')
				->execute()
				->as_array();
		return count($rs);
	}
	
	public function get_drivers_countlist()
	{
		$results = DB::select('id')->from(PEOPLE)
				//->join(TAXIMAPPING,'LEFT')->on(PEOPLE.'.id', '=' ,TAXIMAPPING.'.mapping_driverid')
				->where(PEOPLE.'.user_type', '=', 'D')
				->where(PEOPLE.'.status', '=', 'A')
				//->where(TAXIMAPPING.'.mapping_status', '=', 'A')
				->where(PEOPLE.'.id', '<>', '')
				->execute();
			return count($results);
	}
	
	public function get_taxi_countlist()
	{
		$results = DB::select('taxi_id')->from(TAXI)
				->execute();
			return count($results);
	}
	
	public function free_taxi_list_count($cid=0)
	{
		$usertype = $_SESSION['user_type'];	
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
		$booked_driver = $this->free_availabletaxi_list();

		$taxi_list = "";
		$condition = "";
		if(count($booked_driver) > 0)
		{		
			foreach($booked_driver as $key => $value)
			{
				$taxi_list .= "'".$value['taxi_id']."',";
			}
			$taxi_list = rtrim($taxi_list,',');
			$condition = "and ".TAXI.".taxi_id NOT IN ($taxi_list)";
		}
		
		$companyCond = "";
		if(!empty($cid)) {
			$companyCond = " and ".TAXI.".taxi_company = '$cid'";
		}
		
		if($usertype == 'M') {
			$companyCond .= " and ".TAXI.".taxi_country='$country_id' and ".TAXI.".taxi_state='$state_id' and ".TAXI.".taxi_city='$city_id'";
		}
		
		$sql = "select ".TAXI.".taxi_id from ".TAXI." JOIN ".COMPANY." ON ".TAXI.".taxi_company = company.cid where ".TAXI.".taxi_status='A' and  ".TAXI.".taxi_availability='A'  $condition $companyCond order by ".TAXI.".taxi_id asc";
		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return count($result);
		
	}
	
		public function free_taxi_list($cid=0)
	{
		$usertype = $_SESSION['user_type'];	
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
		$booked_driver = $this->free_availabletaxi_list();

		$taxi_list = "";
		$condition = "";
		if(count($booked_driver) > 0)
		{		
			foreach($booked_driver as $key => $value)
			{
				$taxi_list .= "'".$value['taxi_id']."',";
			}
			$taxi_list = rtrim($taxi_list,',');
			$condition = "and ".TAXI.".taxi_id NOT IN ($taxi_list)";
		}
		
		$companyCond = "";
		if(!empty($cid)) {
			$companyCond = " and ".TAXI.".taxi_company = '$cid'";
		}
		if($usertype == 'M') {
			$companyCond .= " and ".TAXI.".taxi_country='$country_id' and ".TAXI.".taxi_state='$state_id' and ".TAXI.".taxi_city='$city_id'";
		}
		$sql = "select * from ".TAXI." JOIN ".COMPANY." ON ".TAXI.".taxi_company = company.cid where ".TAXI.".taxi_status='A' and  ".TAXI.".taxi_availability='A' $condition $companyCond order by ".TAXI.".taxi_id asc limit 0,10";
		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return $result;
		
	}
	
	public function free_taxi_list_all()
	{
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
		
		
		$sql = "select * from ".TAXI." JOIN ".COMPANY." ON ".TAXI.".taxi_company = company.cid where ".TAXI.".taxi_status='A' and  ".TAXI.".taxi_availability='A'  and ".TAXI.".taxi_id NOT IN ($taxi_list) order by ".TAXI.".taxi_id asc";
		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return $result;
		
	}
	
	
	public function free_taxi_list_all_pag($offset,$val,$cid=0)
	{
		$usertype = $_SESSION['user_type'];	
	   	$country_id = $_SESSION['country_id'];
	   	$state_id = $_SESSION['state_id'];
	   	$city_id = $_SESSION['city_id'];
		$booked_driver = $this->free_availabletaxi_list();

		$taxi_list = "";
		$taxiCond = "";
		
		if(count($booked_driver) > 0)
		{		
			foreach($booked_driver as $key => $value)
			{
				$taxi_list .= "'".$value['taxi_id']."',";
			}
			$taxi_list = rtrim($taxi_list,',');
			$taxiCond = " and ".TAXI.".taxi_id NOT IN ($taxi_list)";
		}
		
		$companyCond = "";
		if(!empty($cid)) {
			$companyCond = " and ".TAXI.".taxi_company = '$cid'";
		}
		
		if($usertype == 'M') {
			$companyCond .= " and ".TAXI.".taxi_country='$country_id' and ".TAXI.".taxi_state='$state_id' and ".TAXI.".taxi_city='$city_id'";
		}
		
		$sql = "select * from ".TAXI." JOIN ".COMPANY." ON ".TAXI.".taxi_company = company.cid where ".TAXI.".taxi_status='A' and  ".TAXI.".taxi_availability='A' $companyCond $taxiCond order by ".TAXI.".taxi_id asc limit $offset,$val";
		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return $result;
		
	}
	
	public function free_driver_list()
	{
	
		$assigned_driver = $this->free_availabletaxi_list();

		$driver_list = '';
		$condition = "";
		if(count($assigned_driver) > 0)
		{
			foreach($assigned_driver as $key => $value)
			{
				$driver_list .= "'".$value['id']."',";
			}
			$driver_list = rtrim($driver_list,',');
			$condition = "and ".PEOPLE.".id NOT IN ($driver_list)";
		}

		$sql = "select * from ".PEOPLE." JOIN ".COMPANY." ON ".PEOPLE.".company_id = company.cid where ".PEOPLE.".user_type='D'  and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' $condition order by ".PEOPLE.".id asc limit 0,10";

		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return $result;
					
	}
	
	
	public function free_driver_list_count()
	{
	
		$assigned_driver = $this->free_availabletaxi_list();

		$driver_list = '';
		$condition = "";
		if(count($assigned_driver) > 0)
		{
			foreach($assigned_driver as $key => $value)
			{
				$driver_list .= "'".$value['id']."',";
			}
			$driver_list = rtrim($driver_list,',');
			$condition = "and ".PEOPLE.".id NOT IN ($driver_list)";
		}

		$sql = "select ".PEOPLE.".id from ".PEOPLE." JOIN ".COMPANY." ON ".PEOPLE.".company_id = company.cid where ".PEOPLE.".user_type='D'  and ".PEOPLE.".status='A' and ".PEOPLE.".availability_status='A' $condition order by ".PEOPLE.".id asc ";

		$result = Db::query(Database::SELECT, $sql)
		->execute()
		->as_array();
			
		return count($result);
					
	}

	public function free_availabletaxi_list()
	{
	$company_id = $_SESSION['company_id'];
	$companyCond = "";
	if($company_id != 0) {
		$companyCond = " AND taxi.taxi_company = '$company_id'";
	}
		$cuurentdate = date('Y-m-d H:i:s');
		$enddate = date('Y-m-d').' 23:59:59';
			
		$query_where = " AND ( ( '$cuurentdate' between taximapping.mapping_startdate and  taximapping.mapping_enddate ) or ( '$enddate' between taximapping.mapping_startdate and  taximapping.mapping_enddate) )";	
						
				
		$sql ="SELECT people.id,taxi.taxi_id  
			FROM ".TAXI." as taxi
			JOIN ".COMPANY." as company ON taxi.taxi_company = company.cid
			JOIN ".TAXIMAPPING." as taximapping  ON taxi.taxi_id = taximapping.mapping_taxiid
			JOIN ".PEOPLE." as people ON people.id = taximapping.mapping_driverid
			WHERE people.status = 'A' 
			AND taximapping.mapping_status = 'A' $companyCond $query_where limit 0,10";
		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}

	/** selecting the banner image for module settings **/
	public function site_module_settings()
	{
		$sql="select id,banner_image1,banner_image2,banner_image3,banner_image4,banner_image5 from ".CMS." where type='2' and status='1'";
		
		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}
	
	/** selecting the tag descriptions for module settings **/
	public function site_info_settings()
	{
		$sql="select content,alt_tags from ".CMS." where type='3' and status='1' order by `order` ASC";
		
		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}
	
	/** validate the menu settings  **/
	public function validate_update_menusettings($arr="")
	{
		return Validation::factory($arr)     
                                                   
                        ->rule('menu_name', 'not_empty')
                        ->rule('menu_link', 'not_empty');
                        //->rule('status_post','not_empty');
                        
	}
	
	/** validate the menu settings  **/
	public function validate_update_menusettings1($arr="")
	{
		return Validation::factory($arr)     
                                                   
                        ->rule('menu_name1', 'not_empty')
                        ->rule('menu_link1', 'not_empty');
                        //->rule('status_post','not_empty');
                        
	}
	
	/** insert the menu settings  **/
	public function insert_menusettings($post)
	{
		$count = count($post['cnt_contact']);
		if($count > 0){
			
			for($i=0;$i<$count;$i++){
				$status = $post['status_post'.$i];
				if($status=='Publish'){
					$status='P';
				}else if($status=='Unpublish'){
					$status='U';
				}
			$sql="select menu_id from ".MENU." order by menu_id DESC";
			$results = Db::query(Database::SELECT, $sql)
					->execute()
					->as_array();
			if(!empty($results[0]['menu_id'])){
				$id = $results[0]['menu_id'];
			}else{
				$id = 0;
			}
			
			if($id > 0){
			$rs= DB::insert(MENU)
					->columns(array('menu_name', 'menu_link', 'status_post','order_status'))
					->values(array($post['menu_name'][$i],$post['menu_link'][$i],$status,$id+1))
					->execute();
			}else{
				$rs= DB::insert(MENU)
					->columns(array('menu_name', 'menu_link', 'status_post','order_status'))
					->values(array($post['menu_name'][$i],$post['menu_link'][$i],$status,'1'))
					->execute();
			}
			
			}
			return $rs;
		}
		
	}
	
	/**update menu settings already having values **/
	public function update_menusettings($post)
	{
		$count = count($post['cnt_contact1']);

		if($count > 0){
			//$count = $count - 1;
			for($i=$count;$i>0;$i--){
			
				$status = $post['status_posts'.$i];
				if($status=='Publish'){
					$status='P';
				}else if($status=='Unpublish'){
					$status='U';
				}
			
			$query = array('menu_name' => $post['menu_name1'][$i-1],
				'menu_link' => $post['menu_link1'][$i-1],
				'status_post' => $status,
				'order_status' => $i);

			$result =  DB::update(MENU)->set($query)
					->where('menu_id', '=' ,$i)
					->execute();
			
			}
			return $result;
		}
	}
	/** Getting site menu settings **/
	public function get_menusettings()
	{
		$sql="select * from ".MENU." order by order_status ASC";
		$results = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $results;
	}
	
	//delete the menus
	public function delete_menus($id) {
			$user = DB::delete(MENU)
				-> where('menu_id', '=',$id)
				-> execute();
			return $user;
	}
	
	public function get_admin_dashboard_data()
	{	
	
		//$result_query = "SELECT * FROM ".PASSENGERS." where 1=1 and (user_status = 'A' OR user_status = 'D' )";
		$result_query = "SELECT id FROM ".PASSENGERS." where 1=1 and user_status = 'A'";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["general_users"]=count($results);

		//$result_query = "SELECT * FROM ".PEOPLE." where user_type='D' and (status = 'A' OR status = 'D' ) ";
		$result_query = "SELECT id FROM ".PEOPLE." where user_type='D' and status = 'A' ";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["driver"]=count($results);
		
		//$result_query = "SELECT * FROM  ".COMPANY." LEFT JOIN  ".PEOPLE." ON ".PEOPLE.".id = ".COMPANY.".userid WHERE user_type ='C' and (status = 'A' OR  status = 'D' ) ";
		$result_query = "SELECT ".COMPANY.".cid FROM  ".COMPANY." LEFT JOIN  ".PEOPLE." ON ".PEOPLE.".id = ".COMPANY.".userid WHERE user_type ='C' and ".COMPANY.".company_status = 'A' ";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["company"]=count($results);
		
		//$result_query = "SELECT * FROM ".PEOPLE." where user_type='M' and (status = 'A' OR status = 'D' )";
		$result_query = "SELECT id FROM ".PEOPLE." where user_type='M' and status = 'A'";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["manager"]=count($results);
		
		//$result_query = "SELECT * FROM ".COUNTRY." where country_status = 'A' OR country_status = 'D' ";
		$result_query = "SELECT country_id FROM ".COUNTRY." where country_status = 'A'";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["country"]=count($results);
		
		//$result_query = "SELECT * FROM ".STATE." where state_status = 'A' OR state_status = 'D'";
		$result_query = "SELECT state_id FROM ".STATE." where state_status = 'A'";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["state"]=count($results);
		
		//$result_query = "SELECT * FROM ".CITY." where city_status = 'A' OR city_status = 'D'";
		$result_query = "SELECT city_id FROM ".CITY." where city_status = 'A'";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["city"]=count($results);	
		
		$result_query = "SELECT taxi_id FROM ".TAXI." where taxi_status = 'A'";
		$results = Db::query(Database::SELECT, $result_query)
		->execute()
		->as_array();
		$result["taxi"]=count($results);	
					
				
		return $result;
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
	
	public function changegetUserbyCompany($startdate,$enddate)
	{
	
	
		$date_where = "  and (created_date between '$startdate' and '$enddate') ";	
		$query = "SELECT count(`id`) as co_nt,c.company_name FROM `people` as p Join `company` as c ON p.company_id=c.cid WHERE p.user_type='D' $date_where group by p.company_id";
	
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
	
	public function changetransactionbyCompany($startdate,$enddate)
	{
		$date_where = "  and (T.current_date between '$startdate' and '$enddate') ";	
		
		$query = "SELECT sum(`fare`) as co_nt,c.company_name FROM ".COMPANY." as c Join ".PASSENGERS_LOG." as p ON p.company_id=c.cid Join ".TRANS." as T on T.passengers_log_id=p.passengers_log_id  $date_where group by p.company_id";

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
	
		
	public function transactionbyCompany()
	{
		$query = "SELECT sum(`fare`) as co_nt,c.company_name FROM ".COMPANY." as c Join ".PASSENGERS_LOG." as p ON p.company_id=c.cid Join ".TRANS." as T on T.passengers_log_id=p.passengers_log_id group by p.company_id";

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
		
	public function get_freetaxi_ajax()
	{
		$startdate = '';
		$enddate = '';
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

			
	}

	public function count_fundrequest_list($list)
	{
		if($list == 'all')
		{	   	
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid order by requested_id desc ";
		}
		else if($list == 'approved')
		{
				$query = "select account_balance,company_name,username,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id  left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '2' order by requested_id desc ";
		}
		else if($list == 'rejected')
		{
				$query = "select account_balance,company_name,username,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '3' order by requested_id desc ";
		}
		else if($list == 'success')
		{
				$query = "select account_balance,company_name,username,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id  left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.pay_status = '1' order by requested_id desc ";
		}
		else if($list == 'failed')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.pay_status = '2' order by requested_id desc";
		}
		else if($list == 'pending')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '1' order by requested_id desc";
		}
//echo $query;
		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();
				
		return count($result);
	

	}
	public function all_fundreuest_list($list,$offset, $val)
	{
		
		if($list == 'all')
		{  	
			$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid order by requested_id desc limit $val offset $offset";
		}
		else if($list == 'approved')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id  left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '2' order by requested_id desc limit $val offset $offset";
		}
		else if($list == 'rejected')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '3' order by requested_id desc limit $val offset $offset";
		}
		else if($list == 'success')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.pay_status = '1' order by requested_id desc limit $val offset $offset";
		}
		else if($list == 'failed')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.pay_status = '2' order by requested_id desc limit $val offset $offset";
		}
		else if($list == 'pending')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '1' order by requested_id desc limit $val offset $offset";
		}

		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();

		 return $result;
		
	}


	public function count_search_fundrequest_list($list,$company_id)
	{

		if($company_id !='')
		{
			$condition = " and company.cid ='".$company_id."'";
		}
		else
		{
			$condition = '';
		}

		if($list == 'all')
		{	   	
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where 1=1 $condition order by requested_id desc ";
		}
		else if($list == 'approved')
		{
				$query = "select account_balance,company_name,username,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id  left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '2' $condition order by requested_id desc ";
		}
		else if($list == 'rejected')
		{
				$query = "select account_balance,company_name,username,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '3' $condition order by requested_id desc ";
		}
		else if($list == 'success')
		{
				$query = "select account_balance,company_name,username,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id  left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.pay_status = '1' $condition order by requested_id desc ";
		}
		else if($list == 'failed')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.pay_status = '2' $condition order by requested_id desc";
		}
		else if($list == 'pending')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '1' $condition order by requested_id desc";
		}
		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();
				
		return count($result);
	

	}
	public function all_search_fundreuest_list($list,$offset, $val,$company_id)
	{

		if($company_id !='')
		{
			$condition = " and company.cid ='".$company_id."'";
		}
		else
		{
			$condition = '';
		}
		
		if($list == 'all')
		{  	
			$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where 1=1 $condition order by requested_id desc limit $val offset $offset";
		}
		else if($list == 'approved')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id  left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '2' $condition order by requested_id desc limit $val offset $offset";
		}
		else if($list == 'rejected')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '3' $condition order by requested_id desc limit $val offset $offset";
		}
		else if($list == 'success')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.pay_status = '1' $condition order by requested_id desc limit $val offset $offset";
		}
		else if($list == 'failed')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.pay_status = '2' $condition order by requested_id desc limit $val offset $offset";
		}
		else if($list == 'pending')
		{
				$query = "select account_balance,company_name,name,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '1' $condition order by requested_id desc limit $val offset $offset";
		}
//echo $query;
		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();

		 return $result;
		
	}
	

	public function delete_module($pay_mod_id)
	{		
		$rows_deleted = DB::delete(PAYMENT_MODULES)->where('pay_mod_id','=',$pay_mod_id)->execute();

		return $rows_deleted;
	}

	public function company_accountbalance()
	{
		$query = "SELECT account_balance,c.company_name FROM ".COMPANY." as c Join ".PEOPLE." as p ON p.company_id=c.cid where user_type='C'   order by p.id";

		$queryval = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		$result = "";
		foreach($queryval as $res)
		{
			$result .= "['".$res["company_name"]."', ".$res["account_balance"].""."],";
		}
		
		$result = rtrim($result,",");

		return $result;
	}

		
	public function print_fundreuest_list($company_id,$list)
	{

		if($company_id !='')
		{
			$condition = " and company.cid ='".$company_id."'";
		}
		else
		{
			$condition = "";
		}
		
		if($list == 'all')
		{	   	
				$query = "select company_name,name,email,address,phone,paypal_account,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where 1=1 $condition order by requested_id desc ";
		}
		else if($list == 'approved')
		{
				$query = "select company_name,name,email,address,phone,paypal_account,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id  left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '2' $condition order by requested_id desc ";
		}
		else if($list == 'rejected')
		{
				$query = "select company_name,name,email,address,phone,paypal_account,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '3' $condition order by requested_id desc ";
		}
		else if($list == 'success')
		{
				$query = "select company_name,name,email,address,phone,paypal_account,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id  left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.pay_status = '1' $condition order by requested_id desc ";
		}
		else if($list == 'failed')
		{
				$query = "select company_name,name,email,address,phone,paypal_account,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.pay_status = '2' $condition order by requested_id desc";
		}
		else if($list == 'pending')
		{
				$query = "select company_name,name,email,address,phone,paypal_account,request_fund.requested_id,company_ownerid,request_fund.amount,DATE_FORMAT(requested_date, '%b %d %Y %H:%i:%s') as requested_date,user_type,request_fund.status,request_fund.pay_status,wtd.correlationid,wtd.masscapturetime,wtd.errorcode,wtd.long_message from ".REQUEST_FUND." as request_fund  left join ".WITHDRAW_TRANSACTION_DETAILS." as wtd on request_fund.requested_id =wtd.requested_id left join ".PEOPLE." as people on request_fund.company_ownerid = people.id left join ".COMPANY." as company on request_fund.company_id = company.cid where request_fund.status = '1' $condition order by requested_id desc";
		}
	
//echo $query;
		$result = Db::query(Database::SELECT, $query)
   			 ->execute()
			 ->as_array();
				
		return $result;
	

	}
	
	//live users search
	public function live_usersearch_list($keyword = "")
	{
		$company_id = $_SESSION['company_id'];
		$user_created_where = "";
		if(!empty($company_id))
		{
			$user_created_where = " AND passenger_cid = $company_id ";
		}
			$keyword = str_replace("%","!%",$keyword);
			$keyword = str_replace("_","!_",$keyword);

		//condition for status
		//====================== 
		//$usertype_where= ($user_type) ? " AND user_type = '$user_type'" : "";

		
		//condition for status
		//====================== 

		//$staus_where= ($status) ? " AND status = '$status'" : "";
	
		//search result export
        //=====================
		$name_where="";	

		if($keyword){
			$name_where  = " AND(name LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%') ";
		}

			$query = " select * from " . PASSENGERS . " where login_status='A' $name_where $user_created_where order by created_date DESC ";
			

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				return $results;

	}
	
	
		public function count_live_usersearch_list($keyword = "")
	{
		$company_id = $_SESSION['company_id'];
		$user_created_where = "";
		if(!empty($company_id))
		{
			$user_created_where = " AND passenger_cid = $company_id ";
		}

		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		//condition for status
		//====================== 
		//$usertype_where= ($user_type) ? " AND user_type = '$user_type'" : "";

		
		//condition for status
		//====================== 

		//$staus_where= ($status) ? " AND status = '$status'" : "";
	
		//search result export
        //=====================
		$name_where="";	

		if($keyword){
			$name_where  = " AND(name LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ) ";
		}

			$query = " select id from " . PASSENGERS . " where login_status='A' $name_where $user_created_where order by created_date DESC";

	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
			 
				return count($results);

	}
	
	
	
		public function get_all_live_search_list($keyword = "",$offset = "", $val ="")
	{
		$company_id = $_SESSION['company_id'];
		$user_created_where = "";
		if(!empty($company_id))
		{
			$user_created_where = " AND passenger_cid = $company_id ";
		}
		$keyword = str_replace("%","!%",$keyword);
		$keyword = str_replace("_","!_",$keyword);

		//condition for status
		//====================== 
		//$usertype_where= ($user_type) ? " AND user_type = '$user_type'" : "";

		
		//condition for status
		//====================== 

		//$staus_where= ($status) ? " AND status = '$status'" : "";
	
		//search result export
		//=====================
		$name_where="";	

		if($keyword){
			$name_where  = " AND(name LIKE  '%$keyword%' ";
			$name_where .= " or email LIKE  '%$keyword%' ) ";
		}
			
	   $query = " select * from " . PASSENGERS . " where login_status='A' $name_where $user_created_where order by created_date DESC limit $val offset $offset";
			
	 		$results = Db::query(Database::SELECT, $query)
			   			 ->execute()
						 ->as_array();
				return $results;
	}
	
	public function get_user_status_block($userid)
	{
        
	 	$rs   = DB::select('id')->from(PEOPLE)
	 	        ->where('id','=',$userid)
				->where('status','=','A')
			    ->execute()->as_array();  
			    

		return count($rs);	
	}
	
	public function get_company_status($companyid)
	{
        
	 	$rs   = DB::select('cid')->from(COMPANY)
	 	        ->where('cid','=',$companyid)
				->where('company_status','=','A')
			    ->execute()->as_array();  
			    

		return count($rs);	
	}	
	public function count_active_driverlist($company='',$manager_id='',$taxiid='',$driverid='',$passengerid='',$startdate='',$enddate='')
	{
			$query_where ='';

			$usertype = $_SESSION['user_type'];

			$condition = '';

			$transaction_model = Model::factory('transaction');

			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{
				$taxilist = $transaction_model->gettaxidetails($company,$manager_id);
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
				$driverlist = $transaction_model->getdriverdetails($company,$manager_id);

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
			$passengerlist = $transaction_model->getpassengerdetails($company,$manager_id);
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




			if($startdate =='' && $enddate =='' )
			{
				$startdate = date('Y-m-d').' 00:00:00';
				$enddate = date('Y-m-d').' 23:59:59';
			}
			
			$condition .= "  and (pl.pickup_time between '$startdate' and '$enddate') ";

			if($company !='')
			{
				$query_where = "  and c.cid='$company' ";
			}

			if($company !='')
			{
				$query_where = "  and c.cid='$company' ";
			}
            /*
             * Before change
	   		$query = " SELECT *,count(tr.passengers_log_id) as trip_count,sum(amt) as trip_amount, pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id  Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id  Join `".TRANS."` as tr ON pl.passengers_log_id=tr.passengers_log_id  where 1=1 $condition group by pl.driver_id  order by pl.passengers_log_id desc";
            */

	   		$query = " SELECT *,count(tr.passengers_log_id) as trip_count,sum(amt) as trip_amount, pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id  Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id  Join `".TRANS."` as tr ON pl.passengers_log_id=tr.passengers_log_id JOIN `".TAXI."` as t ON  pl.taxi_id=t.taxi_id where 1=1 $condition group by pl.taxi_id  order by pl.passengers_log_id desc";
		//echo $query;exit;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return $results;
	}

	public function active_driverlist_details($company,$manager_id,$taxiid,$driverid,$passengerid,$startdate,$enddate,$offset,$val)
	{

			$usertype = $_SESSION['user_type'];

			$condition = '';

			$transaction_model = Model::factory('transaction');

			if((($manager_id !='') && ($manager_id !='All')) || ($usertype == 'M'))
			{
				$taxilist = $transaction_model->gettaxidetails($company,$manager_id);
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
				$driverlist = $transaction_model->getdriverdetails($company,$manager_id);

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
			$passengerlist = $transaction_model->getpassengerdetails($company,$manager_id);
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




			if($startdate =='' && $enddate =='' )
			{
				$startdate = date('Y-m-d').' 00:00:00';
				$enddate = date('Y-m-d').' 23:59:59';
			}
			
			$condition .= "  and (pl.pickup_time between '$startdate' and '$enddate') ";

			if($company !='')
			{
				$query_where = "  and c.cid='$company' ";
			}

			if($company !='')
			{
				$query_where = "  and c.cid='$company' ";
			}
            /*
             * before change
	   		$query = " SELECT *,count(tr.passengers_log_id) as trip_count,sum(amt) as trip_amount, pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id  Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id  Join `".TRANS."` as tr ON pl.passengers_log_id=tr.passengers_log_id  where 1=1 $condition group by pl.driver_id  order by pl.passengers_log_id desc  limit $val offset $offset";
	   		*/
	   		$query = " SELECT pl.*,c.*,pe.*,t.taxi_no,count(tr.passengers_log_id) as trip_count,sum(amt) as trip_amount, pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id  Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id  Join `".TRANS."` as tr ON pl.passengers_log_id=tr.passengers_log_id  JOIN `".TAXI."` as t ON  pl.taxi_id=t.taxi_id where 1=1 $condition group by pl.taxi_id  order by pl.passengers_log_id desc  limit $val offset $offset";
			//echo $query;exit;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();
		return $results;
	}				

	public function count_calendarwise_translist($list,$company,$manager_id,$taxiid,$driverid,$passengerid,$startdate,$enddate,$transaction_id,$payment_type)
	{		
			//echo $driverid;exit;
			$usertype = $_SESSION['user_type'];

			if($startdate !='')
			{
				$fromdate = $startdate.' 00:00:00';
				$enddate = $startdate.' 23:59:59';
			}


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
				$condition = "and pl.travel_status = '1' and pl.driver_reply = 'A' ";
			}
			else if($list =='cancelled')
			{
				$condition = "and ((pl.travel_status = '4' and pl.driver_reply = 'A') or (pl.travel_status = '0' and pl.driver_reply = 'C'))";
			}
			else if($list =='rejected')
			{
				$condition = "and pl.driver_reply = 'R'";
			}

			if($payment_type !='All' && $payment_type !='')
			{
				if($list != 'rejected')
				{
					$condition .= " and payment_type = '$payment_type' ";
				}
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
		  
		    if($startdate !="") { $condition .= " and pl.createdate >=  '$fromdate' and pl.createdate <=  '$enddate' "; }
		
			if($list =='rejected')
			{
		   		$query = " SELECT * , pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id  where 1=1 $condition order by pl.passengers_log_id desc";
			}
			else
			{		   		
		   		$query = " SELECT * , pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id where  1=1 $condition $trans_condition order by pl.passengers_log_id desc";
			}

		//echo '<br/>'.$query; 

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return count($results);
	   
	}	

	/********* Graph ****************/
	public function getgraphvalues_calcendarwise($list,$company,$manager_id,$taxiid,$driver_id,$passengerid,$startdate,$enddate,$transaction_id,$payment_type)
	{

			if($startdate !='')
			{
				$fromdate = $startdate.' 00:00:00';
				$enddate = $startdate.' 23:59:59';
			}
			else
			{

			}


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
			//$trans_condition = " and t.transaction_id like '%".$transaction_id."%'";
			$trans_condition ='';
		}
		else
		{
			$trans_condition ='';
		}

			if($list =='all')
			{
				$condition = "and pl.driver_reply = 'A' ";
			}
			else if($list =='success')
			{
				$condition = "and pl.travel_status = '1' and pl.driver_reply = 'A' ";
			}
			else if($list =='cancelled')
			{
				$condition = "and pl.travel_status = '4' and pl.driver_reply = 'A' ";
			}
			else if($list =='rejected')
			{
				$condition = "and pl.driver_reply != 'A'";
			}

			if($payment_type !='All' && $payment_type !='')
			{

				if($list !='rejected')
				{
					$condition .= " and payment_type ='$payment_type' ";
				}
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
		} 
		
		if($startdate !="") { $condition .= "and pl.createdate >=  '$fromdate' and pl.createdate <=  '$enddate' "; }		    
		$query = " SELECT pl.createdate,round(sum(t.fare)) as amount FROM `".PASSENGERS_LOG."` as pl join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id where 1=1 $condition $trans_condition group by DATE(pl.`createdate`)";
		
		//echo '<br/><br/><br/>'.$query;

		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();	

		return $results;
	}

   	public function calcendarwise_details($list,$company,$manager_id,$taxiid,$driverid,$passengerid,$startdate,$enddate,$offset='',$val='',$transaction_id,$payment_type)
   	{


		if($startdate !='')
		{
			$fromdate = $startdate.' 00:00:00';
			$enddate = $startdate.' 23:59:59';
		}



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
			//$trans_condition = " and t.transaction_id like '%".$transaction_id."%'";
			$trans_condition ='';
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
				$condition = "and pl.travel_status = '1' and pl.driver_reply = 'A' ";
			}
			else if($list =='cancelled')
			{
				$condition = "and ((pl.travel_status = '4' and pl.driver_reply = 'A') or (pl.travel_status = '0' and pl.driver_reply = 'C'))";
			}
			else if($list =='rejected')
			{
				$condition = "and pl.driver_reply = 'R'";
			}

			if($payment_type !='All' && $payment_type !='' )
			{
				if($list !='rejected')
				{
					$condition .= " and payment_type = '$payment_type'";
				}
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
		if($startdate !="") { $condition .= " and pl.createdate >=  '$fromdate' and pl.createdate <=  '$enddate' "; }	

			if($list =='rejected')
			{
			   		$query = " SELECT * , pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id where 1=1 $condition order by pl.passengers_log_id desc  limit $val offset $offset";
			   		//echo 'as';
			}
			else
			{		    
				$query = " SELECT * ,pe.name AS driver_name,pe.phone AS driver_phone,  pa.name AS passenger_name,pa.email AS passenger_email,pa.phone AS passenger_phone FROM `".PASSENGERS_LOG."` as pl join `".TRANS."` as t ON pl.passengers_log_id=t.passengers_log_id Join `".COMPANY."` as c ON pl.company_id=c.cid Join `".PEOPLE."` as pe ON pe.id=pl.driver_id   Join `".PASSENGERS."` as pa ON pl.passengers_id=pa.id where 1=1 $condition $trans_condition order by pl.passengers_log_id desc limit $val offset $offset";
			}
	   //echo '<br/><br/><br/>'.$query;
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();

		return $results;   	     
   	}	//


	/** Get Passengers List **************/
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
         else
         {
			$joins=" LEFT "; 
		 }
		$query = "SELECT ".PASSENGERS.".id,".PASSENGERS.".name,".COMPANY.".company_name FROM ".PASSENGERS." {$joins}JOIN  ".COMPANY." ON (  ".PASSENGERS.".`passenger_cid` =  ".COMPANY.".`cid` ) $condition  ORDER BY `name` ASC";
		//echo $query;exit;
		
		//$query = "select * from ".PASSENGERS." ORDER BY  `passengers`.`name` ASC ";
		$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;	}
	/**
	 * ****export_data()****
	 *@export user listings
	 */
	 
	// To Check Currency code is equal to Currency symbol
	public static function checksite_currency($currencysymbol,$currencycode)
	{
		// To Check Currency code is equal to Currency symbol
		$result = DB::select('country_id')->from(COUNTRY)->where('currency_code','=',$currencycode)->where('currency_symbol','=',$currencysymbol)
			->execute()
			->as_array();
			
			if(count($result) > 0)
			{
				return true;
			}
			else
			{
				return false;		
			}
	}

	public function get_company_details()
	{
		$result = DB::select('company_name','cid')->from(COMPANY)
				->join(PEOPLE)->on(PEOPLE.'.company_id', '=', COMPANY.'.cid')
				->where(PEOPLE.'.user_type','=','C')
				->where(PEOPLE.'.status','=','A')
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

	public function company_details_count($startdate,$enddate)
	{
		
		$condition="";
		$condition .=" f.upgrade_id IN (select max(upgrade_id) from package_report where upgrade_packageid IN ('5','6') group by upgrade_companyid order by upgrade_id asc)";
		if($startdate !="") { /*$condition .= " and c.company_created_date >=  '$startdate' and c.company_created_date <=  '$enddate' ";*/ }
		$query = "SELECT d.company_name, concat(c.`company_domain`, '.getuptaxi.com') as companydomain, p.`name`, p.`email`, p.`org_password` as password,c.`company_created_date`, c.`company_paypal_username`, c.`company_paypal_password`, c.`company_paypal_signature`, c. `company_api_key`, f.`upgrade_date`, f.`upgrade_expirydate`,f.upgrade_id FROM `companyinfo` c join company d on c.company_cid=d.cid join package_report f on f.upgrade_companyid=d.cid join people p on p.id=d.userid where $condition  group by d.`company_name`";
		//echo $query;exit;
	$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return count($results);

	}

	public function company_details($offset,$val,$startdate,$enddate)
	{
		
		$condition="";
		$condition .=" f.upgrade_id IN (select max(upgrade_id) from package_report where upgrade_packageid IN ('5','6') group by upgrade_companyid order by upgrade_id asc)";
		if($startdate !="") { /*$condition .= " and c.company_created_date >=  '$startdate' and c.company_created_date <=  '$enddate' ";*/ }
		$query = "SELECT c.company_cid,d.company_name, concat(c.`company_domain`, '.getuptaxi.com') as companydomain, p.`name`, p.`email`, p.`org_password` as password,c.`company_created_date`, c.`company_paypal_username`, c.`company_paypal_password`, c.`company_paypal_signature`, c. `company_api_key`, f.`upgrade_date`, f.`upgrade_expirydate`,f.upgrade_id FROM `companyinfo` c join company d on c.company_cid=d.cid join package_report f on f.upgrade_companyid=d.cid join people p on p.id=d.userid where $condition
		group by d.`company_name`
		order by f.`upgrade_date` desc
		limit $val offset $offset";
	//echo $query;exit;
	$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;

	}

	public function company_details_search($offset,$val,$startdate,$enddate,$package)
	{	
		$condition="";
		$condition .=" f.upgrade_id IN (select max(upgrade_id) from package_report where upgrade_packageid IN ('5','6') group by upgrade_companyid order by upgrade_id desc)";
		if($startdate !="") { /* $condition .= " and c.company_created_date >=  '$startdate' and c.company_created_date <=  '$enddate' "; */ }
		if($package !="") {
			if($package==1){
				$condition .= " and f.upgrade_packageid = 5";
			}else{
				$condition .= " and f.upgrade_packageid = 6";
			}
		}
		
		$query = "SELECT c.company_cid,d.company_name, concat(c.`company_domain`, '.getuptaxi.com') as companydomain, p.`name`, p.`email`, p.`org_password` as password,c.`company_created_date`, c.`company_paypal_username`, c.`company_paypal_password`, c.`company_paypal_signature`, c. `company_api_key`, f.`upgrade_date`, f.`upgrade_expirydate`,f.upgrade_id
		FROM `companyinfo` c
		join company d on c.company_cid=d.cid
		join package_report f on f.upgrade_companyid=d.cid
		join people p on p.id=d.userid
		where $condition
		group by d.`company_name`
		order by f.`upgrade_date` desc
		limit $val offset $offset";
		//echo $query;exit;
		$results = Db::query(Database::SELECT, $query)
				->execute()
				->as_array();
		return $results;
	}

	public function saas_excel_report($startdate,$enddate,$package)
	{	
		$condition="";
		$condition .=" f.upgrade_id IN (select max(upgrade_id) from package_report where upgrade_packageid IN ('5','6') group by upgrade_companyid order by upgrade_date desc)";
		if($startdate !="") { /* $condition .= " and c.company_created_date >=  '$startdate' and c.company_created_date <=  '$enddate' "; */ }
		if($package !="") { if($package==1){$condition .= " and f.upgrade_packageid = 5"; }else{$condition .= " and f.upgrade_packageid = 6"; } }
		$query = "SELECT d.company_name, concat(c.`company_domain`, '.getuptaxi.com') as companydomain, p.`name`, p.`email`, p.`org_password` as password,c.`company_created_date`, c.`company_paypal_username`, c.`company_paypal_password`, c.`company_paypal_signature`, c. `company_api_key`, f.`upgrade_date`, f.`upgrade_expirydate`,f.upgrade_id FROM `companyinfo` c join company d on c.company_cid=d.cid join package_report f on f.upgrade_companyid=d.cid join people p on p.id=d.userid where $condition  group by d.`company_name` order by upgrade_date desc";

	$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return $results;

	}

	public function company_details_search_count($startdate,$enddate,$package)
	{
		
		$condition="";
		$condition .=" f.upgrade_id IN (select max(upgrade_id) from package_report where upgrade_packageid IN ('5','6') group by upgrade_companyid order by upgrade_id asc)";
		if($startdate !="") { /*$condition .= " and c.company_created_date >=  '$startdate' and c.company_created_date <=  '$enddate' ";*/ }
		if($package !="") { if($package==1){$condition .= " and f.upgrade_packageid = 5"; }else{$condition .= " and f.upgrade_packageid = 6"; } }
		$query = "SELECT d.company_name, concat(c.`company_domain`, '.getuptaxi.com') as companydomain, p.`name`, p.`email`, p.`org_password` as password,c.`company_created_date`, c.`company_paypal_username`, c.`company_paypal_password`, c.`company_paypal_signature`, c. `company_api_key`, f.`upgrade_date`, f.`upgrade_expirydate`,f.upgrade_id FROM `companyinfo` c join company d on c.company_cid=d.cid join package_report f on f.upgrade_companyid=d.cid join people p on p.id=d.userid where $condition  group by d.`company_name`";

	$results = Db::query(Database::SELECT, $query)
		->execute()
		->as_array();				
		return count($results);

	}

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
	
	//function to get passenger list who have referral
	public function passenger_list_referralcode()
	{
		$sql = "SELECT id,wallet_amount FROM ".PASSENGERS." WHERE referral_code != '' and user_status = 'A'";
		$result=Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	//function to update wallet
	public function update_wallet($passengeridArr, $referral_amount)
	{
		$query = array("referral_code_amount"=>$referral_amount);
		return DB::update(PASSENGERS)->set($query)->where('id', 'IN' ,$passengeridArr)->execute();
	}
	
	public function siteinfo_details()	
	{
	
		$sql = "SELECT admin_commission,referral_discount,currency_format,referral_amount,referral_settings  FROM ".SITEINFO;

		return Db::query(Database::SELECT, $sql)
			->execute()
			->as_array(); 

	}
	
	public static function check_wallet_amount_range($base_fare)
	{
		if(preg_match('/^\d+(\-\d+)*$/',$base_fare))
		{		
			return true;
		}
		else
		{
			return false;
		}
	}
	/** Fuction to check wallet amount1 is greater than wallet amount2 or wallet amount3 **/
	public static function compare_wallet_amount1($amount1,$amount2,$amount3)
	{
		if($amount1 > $amount2 || $amount1 > $amount3)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	/** Fuction to check wallet amount1 is greater than wallet amount2 or wallet amount3 **/
	public static function compare_wallet_amount2($amount1,$amount2)
	{
		if($amount1 > $amount2)
		{
			return false;
		}
		else
		{
			return true;
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
	//** Function to get all logged in passengers list **//	
	public function logged_in_passengers()
	{
		$sql = "SELECT id,name,latitude,longitude FROM ".PASSENGERS." WHERE user_status = 'A' and login_status = 'S'";
		$result=Db::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	//** Database exists for open vbx **//	
	public function get_database_exists($text,$cookie_value)
	{
		
		$name = Database::$default;
		$config=Kohana::$config->load('database')->$name;
		//$dbname='openvbx';
		
		$tablename='users';
		$localhost=$config['connection']['hostname'];
		$mysql_user=$config['connection']['username'];
		$mysql_password=$config['connection']['password'];
		$dbname=$config['connection']['callcenterdatabase'];
		$link = mysqli_connect($localhost, $mysql_user,$mysql_password);
		//$link = mysqli_connect('localhost', 'root','ndot');
		if (!mysqli_select_db($link,$dbname)) {
			return 0;
		}else{
			$user=$cookie_name='login_user';
			if(isset($_COOKIE[$cookie_name])) { 
				$user= $_COOKIE[$cookie_name];
			}
			$sql="SELECT * FROM users where email ='$cookie_value'";
			if ($result = mysqli_query($link, $sql)) { 
				
				$out=0;
				if($result->num_rows >0){
					if($text=='text'){
						while($row = $result->fetch_assoc()) {
							return $row['id'];
						}
						
					}
					$out=1;
				}
				return $out;
			}
		}
		
		return 0;
	}
	
	
	//** Insert executives for open vbx **//	
	
	public function add_executive($post)
	{
		
		$name = Database::$default;
		$config=Kohana::$config->load('database')->$name;
		//$dbname='openvbx';
		$tablename='users';
		$localhost=$config['connection']['hostname'];
		$mysql_user=$config['connection']['username'];
		$mysql_password=$config['connection']['password'];
		$dbname=$config['connection']['callcenterdatabase'];
		$link = mysqli_connect($localhost, $mysql_user,$mysql_password);
		//$link = mysqli_connect('localhost', 'root','ndot');
		if (!mysqli_select_db($link,$dbname)) {
			return 0;
		}else{
			
			$name=$post['firstname'];
			$lname=$post['lastname'];
			$email=$post['email'];
			$phone=$post['phone'];
			$sql="SELECT email FROM users where email ='$email'";
			
			if ($result = mysqli_query($link, $sql)) { 
				
				$out=0;
				if($result->num_rows >0){
					$out=1;
				}else{ 
					$mysqli = new mysqli($localhost, $mysql_user,$mysql_password,$dbname);
					$sql = "INSERT INTO `users` (`id`, `is_admin`, `is_active`, `first_name`, `last_name`, `password`, `invite_code`, `email`, `pin`, `notification`, `auth_type`, `voicemail`, `tenant_id`) VALUES (NULL, '1', '1', '".$name."', '".$lname."', NULL, NULL, '".$email."', NULL, NULL, '1', 'Please leave a message after the beep.', '1')";
					//$sql = "INSERT INTO `Taximobility-v1.0.7-oenvbx`.`users` (`id`, `is_admin`, `is_active`, `first_name`, `last_name`, `password`, `invite_code`, `email`, `pin`, `notification`, `auth_type`, `voicemail`, `tenant_id`) VALUES (NULL, '0', '1', 'naveen', 'n', NULL, NULL, 'admins@taximobility.com', NULL, NULL, '1', 'Please leave a message after the beep.', '1')";
					$result=$mysqli->query($sql);					
				}				
				return $out;
			}
		}
		return 0;
	}
	
	
	//** Update executives for open vbx **//	
	
	public function update_executive($post)
	{
		
		$name = Database::$default;
		$config=Kohana::$config->load('database')->$name;
		//$dbname='openvbx';
		$tablename='users';
		$localhost=$config['connection']['hostname'];
		$mysql_user=$config['connection']['username'];
		$mysql_password=$config['connection']['password'];
		$dbname=$config['connection']['callcenterdatabase'];
		$link = mysqli_connect($localhost, $mysql_user,$mysql_password);
		//$link = mysqli_connect('localhost', 'root','ndot');
		if (!mysqli_select_db($link,$dbname)) {
			return 0;
		}else{
			
			$name=$post['firstname'];
			$lname=$post['lastname'];
			$email=$post['email'];
			$phone=$post['phone'];
			$id=$post['callcenter_id'];
			$sql="SELECT * FROM users where email ='$email'";
			
			if ($result = mysqli_query($link, $sql)) { 
				
				$out=0;
				if($result->num_rows >0){
					$out=1;
				}else{ 
					$mysqli = new mysqli($localhost, $mysql_user,$mysql_password,$dbname);
					$sql = "update users set first_name='".$name."',last_name='".$lname."',email='".$email."' where id=$id";
					$result=$mysqli->query($sql);
					
				}
				
				return $out;
			}
		}
		
		return 0;
	}
	
	
	//** Update executives for open vbx **//	
	
	public function delete_executive($id)
	{
		
		$name = Database::$default;
		$config=Kohana::$config->load('database')->$name;
		//$dbname='openvbx';
		$tablename='users';
		$localhost=$config['connection']['hostname'];
		$mysql_user=$config['connection']['username'];
		$mysql_password=$config['connection']['password'];
		$dbname=$config['connection']['callcenterdatabase'];
		$link = mysqli_connect($localhost, $mysql_user,$mysql_password);
		//$link = mysqli_connect('localhost', 'root','ndot');
		if (!mysqli_select_db($link,$dbname)) {
			return 0;
		}else{
			
			$sql="SELECT * FROM users where id =$id";
			
			if ($result = mysqli_query($link, $sql)) { 
				
				$out=0;
				if($result->num_rows >0){
					$mysqli = new mysqli($localhost, $mysql_user,$mysql_password,$dbname);
					$sql = "DELETE FROM users where id= '$id'";
					$result=$mysqli->query($sql);
					$out=1;
				}
				
				return $out;
			}
			
		}
		
		return 0;
	}
	
	public function enable_template($activeids,$status)
	{
		$result = DB::update(SMS_TEMPLATE)->set(array('status' => $status))->where('sms_id', 'IN', $activeids)->execute();
		return count($result);
	}
	
	public function get_driver_list($company_id = 0)
	{
		$condition = "";
		if($company_id > 0) {
			$condition .= " and company_id = '$company_id'";
		}
		$sql = "SELECT id,name,country_code,phone FROM ".PEOPLE." where user_type = 'D' $condition order by name asc";
		return Db::query(Database::SELECT, $sql)
				->execute()
				->as_array(); 
	}
	
	public function count_withdraw_payment_mode()
	{
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = $_SESSION['company_id'];
		$country_id = $_SESSION['country_id'];
		$state_id = $_SESSION['state_id'];
		$city_id = $_SESSION['city_id'];
		if($usertype !='M' || $usertype !='C')
		{
			$result = DB::select('withdraw_payment_mode_id')->from(WITHDRAW_PAYMENT_MODE)
						->execute()
						->as_array();
			return count($result);
		}
		return array();
	}
	
	public function get_withdraw_payment_mode($offset, $val)
	{
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		$company_id = $_SESSION['company_id'];
		$country_id = $_SESSION['country_id'];
		$state_id = $_SESSION['state_id'];
		$city_id = $_SESSION['city_id'];
	   	if($usertype !='M' || $usertype !='C')
		{
			$result = DB::select('withdraw_payment_mode_id','payment_mode_name','payment_mode_status')->from(WITHDRAW_PAYMENT_MODE)
						->limit($val)->offset($offset)
						->execute()
						->as_array();
			return $result;
		}
		return array();
	}
	
	public function get_payment_gateway_list()
	{
		$query2 = "SELECT pay_mod_id,pay_mod_name,pay_mod_image,pay_mod_default,pay_mod_active,pay_mod_cd  FROM ".PAYMENT_MODULES." order by pay_mod_id asc";
		return Db::query(Database::SELECT, $query2)->execute()->as_array();
	}
	
		/** Update mobile image **/
	public function update_mobile_logo_image($field,$image)
	{
		$sql = "select $field from ".SITEINFO." where id='1'";
		$results = Db::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($results[0][$field])){
			$id1 = $results[0][$field];
			if(file_exists($id1)){
				unlink($id1);
			}
		}
		$query = array($field => $image);
		$result =  DB::update(SITEINFO)->set($query)->where('id', '=' ,'1')->execute();
		return $result;
	}
	
	public function get_single_multy_company()
	{
		$result = DB::select('company_name','cid','company_brand_type')->from(COMPANYINFO)
				->join(COMPANY)->on(COMPANY.'.cid', '=', COMPANYINFO.'.company_cid')
				->where(COMPANY.'.company_status','=','A')
				->order_by('company_name','ASC')
				->execute()
				->as_array();
		if(count($result) > 0) {
			return $result;
		} else {
			return 0;
		}
	}
	
	 public function validate_secondary_login($arr="")
	 {
		return Validation::factory($arr)->rule('password', 'not_empty');
	 }
}
?>