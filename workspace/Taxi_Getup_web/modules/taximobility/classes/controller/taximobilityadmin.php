<?php defined('SYSPATH') or die('No direct script access.');
/****************************************************************
* Contains SITE ADMIN details
* @Package: ConnectTaxi
* @Author: NDOT Team
* @URL : http://www.ndot.in
********************************************************************/
class Controller_TaximobilityAdmin extends Controller_Siteadmin {
	/**
     ****__construct()****
     * Common Function in this controller
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
		
		
		$password_verify = $this->session->get('password_verify');
		
		if($password_verify =="" || $password_verify == null || $password_verify !=1)
		{
			$this->all_logout();
		}
		//Models Installation
		$this->model_settings = Model::factory('admin');
    }
	
	
	
	public function action_index()
	{
		$this->urlredirect->redirect('admin/login');	
		//$view=View::factory(ADMINVIEW.'authorize/json');
		//$this->template->content=$view;
		//$this->template->title="Admin Login";		
	}	


	/**
	* ****action_login()****
	* @return admin login page
	*/
	public function action_login()
	{	

		if($this->userid){ 
			$this->urlredirect->redirect('admin/dashboard');
		}
		
		$userid="";	
		$success_msg="";
		$error_msg="";	
		

			
		$submit=$this->request->post('admin_login');
		$form_values=Arr::extract($_REQUEST,array('email','password'));
		$validate=$this->authorize->login_validate($form_values);

		if(isset($submit))
		{ 
			if($validate->check())
			{

				//$fetch_value = $this->authorize->adminlogin_details($form_values['email'],md5($form_values['password']),TRUE,"");
				$select_result = $this->authorize->adminlogin_details($form_values['email'],md5($form_values['password']),FALSE);
				if(count($select_result) > 0) 
				{  
					$user_time_zone = $this->commonmodel->select_site_settings('user_time_zone',SITEINFO);
					$userid = $select_result[0]['id'];					
					$this->session->set("userid",$select_result[0]['id']);
					$this->session->set("user_type",$select_result[0]['user_type']);
					$this->session->set("name",$select_result[0]['name']);
					$this->session->set("username",$select_result[0]['username']);					
					$this->session->set("email",$select_result[0]['email']);	
					$this->session->set("company_id",$select_result[0]['company_id']);
					$this->session->set("city_id",$select_result[0]['login_city']);	
					$this->session->set("state_id",$select_result[0]['login_state']);	
					$this->session->set("country_id",$select_result[0]['login_country']);				
					$this->session->set("timezone",$user_time_zone);
					$this->session->set("password_verify",1);				
			
					$usrid = $this->session->get('userid');$id =$usrid; 
					//Message::success(__('succesful_login_flash').SITENAME);
					Message::success(__('succesful_login_flash_front').SITENAME);
					if($select_result[0]['user_type']=='S')
					{
						$this->urlredirect->redirect('add/moderator');
						
					}

                   	$this->urlredirect->redirect('admin/login');
					
				}
				else
				{
					Message::error(__('login_failure').'of '.SITENAME); 
				}
			}
			else
			{
				$errors = $validate->errors('errors');
			}
	    }
	    
			$this->template->page_title= __('page_login_title');

	    		$view=View::factory(ADMINVIEW.'login')
				->bind('validate',$validate)
				->bind('form_values',$form_values)
				->bind('errors',$errors);
				
		$this->template->content=$view;
		
	}
	
	/**
	*****action_changepassword()****
	* @return admin change password
	*/
	 public function action_changepassword()
	 {
		$this->is_login();                       

		$errors=array();
		
		$changepassword =arr::get($_REQUEST,'submit_changepassword');	
		
		/**To get current logged user id from session**/
		$usrid = $this->session->get('userid');

		$id = $usrid; //$id = isset($userid)?$userid:$usrid;
				
		if(isset($changepassword) && Validation::factory($_POST) )
		{
			$postvalue = $_POST;
			
			$post = Arr::map('trim', $this->request->post());
		
			$validator = $this->authorize->changepassword_validate(arr::extract($post,array('oldpassword','password','repassword')),$id);


			if($validator->check())
			{

					$update=$this->authorize->changepassword($post['password'],$this->userid);

					$mail="";			

					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>ucfirst($update[0]['name']),REPLACE_EMAIL=>$update[0]['email'],REPLACE_PASSWORD=>$post['password'],REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'changepassword.html',$replace_variables);
				
					$to = $update[0]['email'];
					$from = $this->siteemail;
					$subject = __('reset_password_label')." - ".$this->app_name."admin";	
					$redirect = "admin/changepassword";	
					$mail_model = Model::factory('add');
					$smtp_result = $mail_model->smtp_settings();
					if(!empty($smtp_result) && ($smtp_result[0]['smtp'] == 1))
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

			//$mail=array("to" => $update[0]['email'],"from"=>$this->siteemail,"subject"=>__("reset_password_label")." - ".$this->app_name."admin","message"=>$message);			
					
						
			//		$emailstatus = $this->email_send($mail,'smtp');

					Message::success(__('sucessful_change_password'));

					$this->request->redirect("admin/changepassword");
				


			} 
			else{
				$errors = $validator->errors('errors');
			}
		}
		

		
		$view=View::factory(ADMINVIEW.'authorize/changepassword')
				->bind('errors',$errors)
				->bind('postvalue',$postvalue);
				
		$this->template->content=$view;
       
		$this->template->meta_description=SITENAME." | Admin ";	
		$this->template->meta_keywords=SITENAME." | Admin ";  
		$this->template->title=SITENAME." | ".__('changepassword_label');
		$this->template->page_title= __('changepassword_label');
	 } 
	
	/**
	*****action_editprofile()****
	* @return admin edit profile
	*/
	 public function action_editprofile()
	 {
	
		$this->is_login();
		$usertype = $_SESSION['user_type'];    
		
		if($usertype =='C')
		{
			$this->request->redirect("company/login");
		}
		
		if($usertype =='M')
		{
			$this->request->redirect("manager/login");
		}
		//get current page segment id 
		$usrid = $this->request->param('userid');
		$id = $this->request->param('id');
		$userid = isset($usrid)?$usrid:$id;
		if($_SESSION['userid'] != $userid) {
			Message::error(__('invalid_access'));
			$this->request->redirect("admin/dashboard");
		}
		//check current action
		$action = $this->request->action();
		$action .= "/".$userid;
		$postvalue = array();

		$add_company = Model::factory('add');

		$country_details = $add_company->country_details();
		$city_details = $add_company->city_details();
		$state_details = $add_company->state_details();
		
		//getting request for form submit
		$editprofile =arr::get($_REQUEST,'submit_editprofile');		

		$errors = array();	

		if(isset($editprofile) && Validation::factory($_POST) )
		{
			$post_values = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));

			$validator = $this->authorize->editprofile_validate(arr::extract($post_values,array('firstname','lastname','email','phone','address','country','state','city')),$userid);

			if($validator->check())
			{
				/* $image_name = "";
				if($_FILES['photo']['name']!=''){
					$image_name = uniqid().$_FILES['photo']['name']; 
					$filename =Upload::save($_FILES['photo'],$image_name,DOCROOT.USER_IMGPATH, '0777');
					$image = Image::factory($filename);
					$image2 = Image::factory($filename);
					$path=DOCROOT.USER_IMGPATH_THUMB;
					Commonfunction::imageresize($image,USER_SMALL_THUMB_WIDTH, USER_SMALL_THUMB_HEIGHT,$path,$image_name,90);
					$path2=DOCROOT.USER_IMGPATH_PROFILE;
					Commonfunction::imageresize($image2,USER_SMALL_IMAGE_WIDTH, USER_SMALL_IMAGE_HEIGHT,$path2,$image_name,90);
				} */
				$status = $this->authorize->edit_people($userid,$post_values);
				if($status == 1)
				{
					Message::success(__('profile_updated_successfully'));
				}
				else
				{
					Message::error(__('not_updated'));
				}
				$this->request->redirect("admin/editprofile/".$userid);
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$login_details=$this->authorize->login_details_byid($userid);
		$email=$_SESSION['email'];
		$view=View::factory(ADMINVIEW.'authorize/editprofile')
				->bind('errors',$errors)
				->bind('action',$action)
				->bind('validate',$validate)
				->bind('user_exists',$user_exists)
				->bind('postvalue',$postvalue)
				->bind('country_details',$country_details)
				->bind('city_details',$city_details)
				->bind('state_details',$state_details)
				->bind('login_detail',$login_details)
				->bind('email',$email);
			
			$this->template->content=$view;

			$this->template->meta_description=SITENAME." | Admin ";	
			$this->template->meta_keywords=SITENAME."  | Admin ";  


			$this->template->title="Edit Profile";
			$this->template->page_title="Edit Profile";

	 }


	 public function action_edituserprofile()
	 {
	
		$this->is_login();
		$usertype = $_SESSION['user_type'];        
		if($usertype =='C')
		{
			$this->request->redirect("company/login");
		}
		
		if($usertype =='M')
		{
			$this->request->redirect("manager/login");
		}
		//get current page segment id 
		$usrid = $this->request->param('userid');
		$id = $this->request->param('id');
		$userid = isset($usrid)?$usrid:$id;
		//check current action
		$action = $this->request->action();
		$action .= "/".$userid;
		$postvalue = array();

		$add_company = Model::factory('add');

		$country_details = $add_company->country_details();
		$city_details = $add_company->city_details();
		$state_details = $add_company->state_details();
		
		//getting request for form submit
		$editprofile =arr::get($_REQUEST,'submit_editprofile');		

		$errors = array();	

		if(isset($editprofile) && Validation::factory($_POST) )
		{
			$postvalue = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
		
			$validator = $this->authorize->editprofile_validate(arr::extract($postvalue,array('firstname','lastname','email','phone','address','country','state','city')),$userid);


				if($validator->check())
				{
				/*

				$image_name = "";

				if($_FILES['photo']['name']!=''){

				$image_name = uniqid().$_FILES['photo']['name']; 

				$filename =Upload::save($_FILES['photo'],$image_name,DOCROOT.USER_IMGPATH, '0777');

				$image = Image::factory($filename);

				$image2 = Image::factory($filename);

				$path=DOCROOT.USER_IMGPATH_THUMB;

				Commonfunction::imageresize($image,USER_SMALL_THUMB_WIDTH, USER_SMALL_THUMB_HEIGHT,$path,$image_name,90);

				$path2=DOCROOT.USER_IMGPATH_PROFILE;

				Commonfunction::imageresize($image2,USER_SMALL_IMAGE_WIDTH, USER_SMALL_IMAGE_HEIGHT,$path2,$image_name,90);

				}	



				*/
					$status = $this->authorize->edit_people($userid,$postvalue);

					if($status == 1)
					{
						Message::success(__('profile_updated_successfully'));
					}
					else
					{
						Message::error(__('not_updated'));
					}
					$this->request->redirect("manageusers/index");
				}
				else
				{					
					$errors = $validator->errors('errors');
				}
		}

		$login_details=$this->authorize->login_details_byid($userid);
		$view=View::factory(ADMINVIEW.'authorize/edituserprofile')
				->bind('errors',$errors)
				->bind('action',$action)
				->bind('validate',$validate)
				->bind('user_exists',$user_exists)
				->bind('postvalue',$postvalue)
				->bind('country_details',$country_details)
				->bind('city_details',$city_details)
				->bind('state_details',$state_details)
				->bind('login_detail',$login_details);
			
			$this->template->content=$view;

			$this->template->meta_description=SITENAME." | Admin ";	
			$this->template->meta_keywords=SITENAME."  | Admin ";  


			$this->template->title=SITENAME." | Edit Profile";
			$this->template->page_title= "Edit Profile";

	 }
	 
	 /**
	*****action_editpassenger()****
	* @return admin edit passenger
	*/
	 public function action_editpassenger()
	 {
		$add_model = Model::factory('add');
		$this->is_login();
		$usertype = $_SESSION['user_type'];        
		if($usertype =='C')
		{
			$this->request->redirect("company/login");
		}
		
		if($usertype =='M')
		{
			$this->request->redirect("manager/login");
		}
		//get current page segment id 
		$usrid = $this->request->param('userid');
		$id = $this->request->param('id');
		$userid = isset($usrid)?$usrid:$id;
		//check current action
		$action = $this->request->action();
		$action .= "/".$userid;
		$postvalue = array();
		
		$login_details=$this->authorize->login_details_by_passengerid($userid);
		if(count($login_details) == 0) {
			$this->request->redirect("manageusers/passengers");
		}
		//getting request for form submit
		$editprofile =arr::get($_REQUEST,'submit_editprofile');

		$errors = array();

		if(isset($editprofile) && Validation::factory($_POST) )
		{
			$postvalue = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
		
			$validator = $this->authorize->editpassenger_validate(arr::extract($postvalue,array('name','email','phone','address','discount')),$userid);


				if($validator->check())
				{
					$status = $this->authorize->edit_passenger($userid,$postvalue);

					if($status == 1)
					{
						Message::success(__('profile_updated_successfully'));
					}
					else
					{
						Message::error(__('not_updated'));
					}
					$this->request->redirect("manageusers/passengers");
				}
				else
				{					
					$errors = $validator->errors('errors');
				}
		}

			
			$taxicompany_details = $add_model->taxicompany_details();
			$view=View::factory(ADMINVIEW.'authorize/editpassenger')
				->bind('errors',$errors)
				->bind('action',$action)
				->bind('validate',$validate)
				->bind('user_exists',$user_exists)
				->bind('postvalue',$postvalue)
				->bind('taxicompany_details',$taxicompany_details)
				->bind('login_detail',$login_details);
			
			$this->template->content=$view;

			$this->template->meta_description=SITENAME." | Passenger ";
			$this->template->meta_keywords=SITENAME."  | Passenger ";  


			$this->template->title=SITENAME." | ".__('editprofile_label');
			$this->template->page_title= __('editprofile_label');
	 }


	/**
	*****action_logout()****
	* @return admin logout from site
	*/
	public function action_logout()
	{
	  $this->session->destroy();
	  Cookie::delete('userid');	
	  Cookie::delete('login_user');	
	  Cookie::delete('user_type_openvbx');	
	  //$this->urlredirect->redirect("admin/login");
	$this->request->redirect("/admin/login");
	}
	
	public function all_logout()
	{
	  foreach($_SESSION as $key => $val)
		{
			if ($key !='password_verify')
			{
				unset($_SESSION[$key]);
			}

		}
	  Cookie::delete('userid');	
	  Cookie::delete('login_user');	
	  Cookie::delete('user_type_openvbx');	
	}
		  
	
	/**
	*****action_forgotpassword()****
	* @return admin forgot password
	*/
	public function action_forgot_password()
	{
		$errors=array();
		
		$forgotpassword =arr::get($_REQUEST,'submit_forgot_password_admin');	
			
		if(isset($forgotpassword) && Validation::factory($_POST) )
		{
			$postvalue = $_POST;

			$post = Arr::map('trim', $this->request->post());
		
			$validator = $this->authorize->forgotpassword_validate(arr::extract($post,array('email')));

			if($validator->check())
			{

				$user_detail=$this->authorize->select_users_byemail($post['email'],"");

				$password=Text::random();   
				$this->authorize->changepassword($password,$user_detail[0]['id']);
				
				$mail="";			

				$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>ucfirst($user_detail[0]['name']),REPLACE_EMAIL=>$post['email'],REPLACE_PASSWORD=>$password,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
				$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'forgotpassword.html',$replace_variables);

					$to = $post['email'];
					$from = $this->siteemail;
					$subject = __('forgot_password_subject')." - ".$this->app_name;	
					$redirect = "admin/login";	
					$mail_model = Model::factory('add');
					$smtp_result = $mail_model->smtp_settings();
					if(!empty($smtp_result) && ($smtp_result[0]['smtp'] == 1))
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


				//$mail=array("to" => $post['email'],"from"=>$this->siteemail,"subject"=>__("forgot_password_label")." - ".$this->app_name."admin","message"=>$message);			
				
					
				//$emailstatus = $this->email_send($mail,'smtp');

				Message::success(__('sucessful_forgot_password'));

				$this->request->redirect("admin/login");


			} 
			else{
				$errors = $validator->errors('errors');
			}
		}
		
		
		$view=View::factory(ADMINVIEW.'forgot_password')
				->bind('errors',$errors)
				->bind('postvalue',$postvalue);
				
		$this->template->content=$view;
       
		$this->template->meta_description=SITENAME." | Admin ";	
		$this->template->meta_keywords=SITENAME." | Admin ";  
		$this->template->title=SITENAME." | ".__('forgot_password');
		$this->template->page_title= __('forgot_password');

	}

	/**
	*****action_delete_userphoto()****
	* @return admin delete photo
	*/
	public function action_delete_userphoto()
	{
		
		$this->is_login();                       
		//get current page segment id 
		$userid = $this->request->param('id');
		
		$user_delete= $this->authorize->check_userphoto($userid); 

		if(file_exists(DOCROOT.USER_IMGPATH.$user_delete) && $user_delete != '')
	    {				
			unlink(DOCROOT.USER_IMGPATH.$user_delete);
	    }	
		if(file_exists(DOCROOT.USER_IMGPATH_THUMB.$user_delete) && $user_delete != '')
	    {				
			unlink(DOCROOT.USER_IMGPATH_THUMB.$user_delete);
	    }	
	  
        $status = $this->authorize->update_user_photo($userid);
			
		//send data to view file 
		$login_details=$this->authorize->login_details_byid($userid,FALSE,$this->usertype);

		//Flash message 
		Message::success(__('delete_userphoto_flash'));	
				
		$this->request->redirect("admin/editprofile/".$userid);
		
	}
	
	public function action_delete()
	{	
		$this->is_login();                       
		//get current page segment id 
		$userid = $this->request->param('id');

		//user image delete and unlink that image
		$user_delete= $this->authorize->check_userphoto($userid);

		if(file_exists(DOCROOT.USER_IMGPATH.$user_delete) && $user_delete != '')
		{				
			unlink(DOCROOT.USER_IMGPATH.$user_delete);
		}
		if(file_exists(DOCROOT.USER_IMGPATH_THUMB.$user_delete) && $user_delete != '')
		{				
			unlink(DOCROOT.USER_IMGPATH_THUMB.$user_delete);
		}
		
		//perform delete action 
		
		$status = $this->authorize->delete_people($userid);
	    
		//Flash message 
		Message::success(__('user_delete_flash'));
		
		//redirects to index page after deletion
		$this->request->redirect("manageusers/index");
	}
	
	/**  delete the passengers **/
	/*public function action_delete_passenger()
	{	
		$this->is_login();                       
		//get current page segment id 
		$userid = $this->request->param('id');

		/*
		 //user image delete and unlink that image
		$user_delete= $this->authorize->check_userphoto($userid);

		if(file_exists(DOCROOT.USER_IMGPATH.$user_delete) && $user_delete != '')
		{				
			unlink(DOCROOT.USER_IMGPATH.$user_delete);
		}
		if(file_exists(DOCROOT.USER_IMGPATH_THUMB.$user_delete) && $user_delete != '')
		{				
			unlink(DOCROOT.USER_IMGPATH_THUMB.$user_delete);
		} */
		
		//perform delete action 
	/*	
		$status = $this->authorize->delete_passenger($userid);
	    
		//Flash message 
		Message::success(__('user_delete_flash'));
		
		//redirects to index page after deletion
		$this->request->redirect("manageusers/passengers");
	} */
	
	/**
	 * ****action_is_login()****
	 * @return check user logged or not
	 */
	public function is_login()
	{ 		
		$session = Session::instance();

		//get current url and set it into session
		//========================================
		$this->session->set('requested_url', Request::detect_uri());
			
	        /**To check Whether the user is logged in or not**/
		if(!isset($this->session) || (!$this->session->get('userid')) ) // && !$this->session->get('id')		
		{
			Message::error(__('login_access'));
			$this->request->redirect("/admin/login/");
		}
		return;
	}

	/**
	*****action_home()****
	* @return admin home
	*/
	 public function action_home()
	 {
		$this->is_login(); 
		$usertype = $_SESSION['user_type'];
		if($usertype !='A'){
		$this->request->redirect("admin/login");
		}
		$this->is_login();

		$usrid = $this->session->get('userid'); 
		$userid =$usrid; 
		$login_details=$this->authorize->login_details_byid($userid);	


		$site = Model::factory('site');

		$total_transactions_amount='0';
		$caregivers_pay_amount='0';
		$transactions_commision_amount='0';
		$withdraw_commision_amount='0';
		$admin_total_commision='0';

		$total_transactions_amount=0; //$site->total_transactions_amount();	


		$this->template->meta_description=SITENAME."  | Admin ";	
		$this->template->meta_keywords=SITENAME."  | Admin ";  
		$this->template->title=SITENAME." |  Home";
		$this->template->page_title=SITENAME." |  Home";


		$view=View::factory(ADMINVIEW.'authorize/home')

			->bind('total_transactions_amount',$total_transactions_amount)
			->bind('caregivers_pay_amount',$caregivers_pay_amount)
			->bind('transactions_commision_amount',$transactions_commision_amount)
			->bind('withdraw_commision_amount',$withdraw_commision_amount)
			->bind('admin_total_commision',$admin_total_commision)

			->bind('login_detail',$login_details);	

		$this->template->content=$view;
	 }
       
        
    // Block or Unblock user
    //===============================
    public function action_blkunblk()
	{
		$this->is_login();                     

		$userid = $this->request->param('id');
		$updatestatus = $this->request->param('sid');

	    $status = $this->authorize->activate_deactivate_people($userid,$updatestatus);
	    
		//Flash message 
		Message::success('User status has been changed successfully');
		
		//redirects to index page after deletion
		$this->request->redirect("manageusers/index");
	}
	
	// Block or Unblock passenger
	//===============================
	public function action_blkunblk_passenger()
	{
		$this->is_login();
		$userid = $this->request->param('id');
		$updatestatus = $this->request->param('sid');
		$status = $this->authorize->activate_deactivate_passenger($userid,$updatestatus);
		//Flash message 
		Message::success(__('passenger_bulk_block'));
		//redirects to index page after deletion
		$this->request->redirect("manageusers/passengers");
	}


        /** Manage Site Settings **/
        public function action_manage_site()
        {
			$this->is_login();
			$usertype = $_SESSION['user_type'];
			if($usertype !='A'){
				$this->request->redirect("admin/login");
			}
			$errors = array();
			$signup_submit =arr::get($_REQUEST,'editsettings_submit');
			$post_values = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
			$content_fields = Arr::extract($this->request->post(),array('banner_content','app_content','about_us_content','contact_us_content'));
			$post_values = array_merge($post_values,$content_fields);
			if ($signup_submit && Validation::factory($post_values,$_FILES) ) 
			{
				$favicon_old = $post_values['favicon_old'];
				$banner_img_old = $post_values['banner_img_old'];
				$mobile_header_logo = $post_values['mobile_header_logo'];
				$flash_screen_logo = $post_values['flash_screen_logo'];

				$validator = $this->model_settings->validate_updatesiteinfo(arr::extract($post_values,array('app_name','app_description','contact_email','phone_number','meta_keyword','meta_description','site_tagline','site_copyrights',
	'site_logo','site_email_logo','notification_settings','admin_commission','sms_enable','default_unit','skip_credit_card', 'cancellation_fare','tax','fare_calculation','price_settings','show_map','pagination_settings','tell_to_friend_message','continuous_request_time', 'referral_settings','referral_amount','wallet_amount1','wallet_amount2','wallet_amount3','wallet_amount_range','driver_referral_setting', 'driver_referral_amount','ios_google_map_key','ios_google_geo_key','web_google_map_key','web_google_geo_key','google_timezone_api_key', 'android_google_api_key','default_miles','date_time_format','user_time_zone','banner_image','banner_content','app_content', 'app_android_store_link','app_ios_store_link','app_bg_color','about_us_content','about_bg_color','footer_bg_color','contact_us_content', 'facebook_follow_link','google_follow_link','twitter_follow_link','passenger_app_android_store_link','passenger_app_ios_store_link','passenger_book_notify','driver_trip_amount','driver_minimum_wallet_request','driver_maximum_wallet_request','wallet_request_per_day')),$_FILES);
				//'currency_code','site_currency',
				if($validator->check())
				{
					//to get previous referral amount to check whether new referral amount 
					$siteInfo = $this->siteinfo;
					$referralAmount = $siteInfo[0]['referral_amount'];
					
					$status = $this->model_settings->updatesiteinfo($post_values);
					//set changed timezone settings to session
					$this->session->set("timezone",$post_values['user_time_zone']);
					if($referralAmount != $post_values['referral_amount']) {
						$allPassList = $this->model_settings->passenger_list_referralcode();
						$passArr = array();
						if(count($allPassList) > 0) {
							foreach($allPassList as $passengers) {
								$passArr[] = $passengers['id'];
							}
							$this->model_settings->update_wallet($passArr,$post_values['referral_amount']);
						}
					}
					if(!empty($_FILES['site_logo']['name'])){					
						$image_name = 'logo.png';
						$image_type=explode('.',$image_name);
						$image_type=end($image_type);
						//$image_name=url::title($image_name).'.'.$image_type;
						$filename = Upload::save($_FILES['site_logo'],$image_name,DOCROOT.SITE_LOGO_IMGPATH);
						//Image resize and crop for thumb image
						$logo_image = Image::factory($filename);
						$path11=DOCROOT.SITE_LOGO_IMGPATH;
						$path1=$image_name;
						Commonfunction::imageresize($logo_image,180, 40,$path11,$image_name,90);
						
						$status = $this->model_settings->updatesiteinfo_image($path1);
						$status = 1;
					}
					
					if(!empty($_FILES['email_site_logo']['name'])){					
						$image_name = 'site_email_logo.png'; 
						$image_type=explode('.',$image_name);
						$image_type=end($image_type);
						//$image_name=url::title($image_name).'.'.$image_type;
						$filename = Upload::save($_FILES['email_site_logo'],$image_name,DOCROOT.SITE_LOGO_IMGPATH);
						//Image resize and crop for thumb image
						$logo_image = Image::factory($filename);
						$path11=DOCROOT.SITE_LOGO_IMGPATH;
						$path1=$image_name;
						Commonfunction::imageresize($logo_image,175, 35,$path11,$image_name,90);
						
						$status = $this->model_settings->updatesite_email_einfo_image($path1);
						$status = 1;
					}
					
					if(!empty($_FILES['site_favicon']['name'])){
						if (file_exists(DOCROOT.SITE_FAVICON_IMGPATH.$favicon_old))
						{
							unlink(DOCROOT.SITE_FAVICON_IMGPATH.$favicon_old);
						}
						$image_name = uniqid().$_FILES['site_favicon']['name']; 
						$image_type=explode('.',$image_name);
						$image_type=end($image_type);
						//$image_name=url::title($image_name).'.'.$image_type;
						$filename = Upload::save($_FILES['site_favicon'],$image_name,DOCROOT.SITE_FAVICON_IMGPATH);
						//Image resize and crop for thumb image
						$logo_image = Image::factory($filename);
						$path11=DOCROOT.SITE_FAVICON_IMGPATH;
						$path1=$image_name;
						Commonfunction::imageresize($logo_image,FAVICON_WIDTH, FAVICON_HEIGHT,$path11,$image_name,90);
						
						$status = $this->model_settings->updatesiteinfo_faviconimage($path1);
					}
					
					if(!empty($_FILES['banner_image']['name'])){
						if ($banner_img_old!="" && file_exists(DOCROOT.PUBLIC_UPLOADS_LANDING_FOLDER.$banner_img_old))
						{
							unlink(DOCROOT.PUBLIC_UPLOADS_LANDING_FOLDER.$banner_img_old);
						}
						$image_name = uniqid().$_FILES['banner_image']['name'];
						$image_type=explode('.',$image_name);
						$image_type=end($image_type);
						//$image_name=url::title($image_name).'.'.$image_type;
						$filename = Upload::save($_FILES['banner_image'],$image_name,DOCROOT.PUBLIC_UPLOADS_LANDING_FOLDER);
						//Image resize and crop for thumb image
						$logo_image = Image::factory($filename);
						$path11=DOCROOT.PUBLIC_UPLOADS_LANDING_FOLDER;
						$path1=$image_name;
						Commonfunction::imageresize($logo_image,SITE_BANNER_WIDTH, SITE_BANNER_HEIGHT,$path11,$image_name,90);
						$status = $this->model_settings->updatesiteinfo_bannerimage($path1);
					}
					
					if(!empty($_FILES['mobile_header_logo']['name'])){
						if ($mobile_header_logo != "" && file_exists(DOCROOT.MOBILE_LOGO_PATH.$mobile_header_logo))
						{
							unlink(DOCROOT.MOBILE_LOGO_PATH.$mobile_header_logo);
						}
						//$image_name = uniqid().$_FILES['mobile_header_logo']['name'];
						$image_name = 'signInLogo.png';
						$image_type=explode('.',$image_name);
						$image_type=end($image_type);
						$filename = Upload::save($_FILES['mobile_header_logo'],$image_name,DOCROOT.MOBILE_LOGO_PATH);
						//Image resize and crop for thumb image
						$logo_image = Image::factory($filename);
						$path11 = DOCROOT.MOBILE_LOGO_PATH;
						Commonfunction::imageresize($logo_image,786, 132,$path11,$image_name,90);
						$status = $this->model_settings->update_mobile_logo_image('mobile_header_logo',$image_name);
					}
					if(!empty($_FILES['flash_screen_logo']['name'])){
						if ($flash_screen_logo != "" && file_exists(DOCROOT.MOBILE_LOGO_PATH.$flash_screen_logo))
						{
							unlink(DOCROOT.MOBILE_LOGO_PATH.$flash_screen_logo);
						}
						//$image_name = uniqid().$_FILES['flash_screen_logo']['name'];
						$image_name = 'headerLogo.png';
						$image_type=explode('.',$image_name);
						$image_type=end($image_type);
						$filename = Upload::save($_FILES['flash_screen_logo'],$image_name,DOCROOT.MOBILE_LOGO_PATH);
						//Image resize and crop for thumb image
						$logo_image = Image::factory($filename);
						$path11 = DOCROOT.MOBILE_LOGO_PATH;
						Commonfunction::imageresize($logo_image,210, 102,$path11,$image_name,90);
						$status = $this->model_settings->update_mobile_logo_image('flash_screen_logo',$image_name);
					}
					
					if($status == 1)
					{
						//to clear browser cache while uploading image
						header('Cache-Control: no-cache');
						header('Pragma: no-cache');
						Message::success(__('sucessful_settings_update'));
					}
					else
					{
						Message::error(__('not_updated'));
					}

				
				$this->request->redirect("admin/manage_site");

				}
				else
				{
					$errors = $validator->errors('errors');
				}
			}
			//echo "<pre>"; print_r($this->siteinfo); exit;
			$currencysymbol = $this->currencysymbol;
			$id = $this->request->param('id');
			$email=$_SESSION['email'];
			$site_settings = $this->siteinfo;
			$company_timezone = (isset($site_settings[0]['user_time_zone']) && $site_settings[0]['user_time_zone'] != "") ? $site_settings[0]['user_time_zone'] : TIMEZONE;
			/*$site_country = $this->model_settings->site_country();
			$site_city = $this->model_settings->site_city();*/
			$this->selected_page_title = __("site_settings");
			$view = View::factory('admin/add_settings_site')
					->bind('validator', $validator)
					->bind('errors', $errors)
					->bind('postvalue',$post_values)
					->bind('site_settings', $site_settings)
					->bind('email', $email)
					//->bind('site_country',$site_country)
					//->bind('site_city',$site_city)
					->bind('company_timezone',$company_timezone)
					->bind('currency_symbol',$currencysymbol);

			$this->template->title= SITENAME." | ".__('site_settings');
			$this->template->page_title= __('site_settings'); 	
			$this->template->content = $view;

        }
        
        /** Manage menu Settings **/
        public function action_menu_settings()
        {
		$this->is_login(); 
		$usertype = $_SESSION['user_type'];
		if($usertype !='A'){
			$this->request->redirect("admin/login");
		}
                $settings = Model::factory('admin');
                $errors = array();
		$signup_submit =arr::get($_REQUEST,'editsettings_submit'); 
		$errors = array();
		$post_values = array();
		
		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			
			$post = Arr::map('trim', $this->request->post());
			
			$validator = $settings->validate_update_menusettings(arr::extract($post,array('menu_name','menu_link')));
			$validator1 = $settings->validate_update_menusettings1(arr::extract($post,array('menu_name1','menu_link1')));
			
			if($validator->check() || $validator1->check())
			{
				if(!empty($post['cnt_contact'])){
				$status = $settings->insert_menusettings($post);
				}
				if(!empty($post['cnt_contact1'])){
				$status = $settings->update_menusettings($post);
				}
				if($status == 1)
				{
					Message::success(__('sucessful_settings_update'));
				}
				else
				{
					Message::error(__('not_updated'));
				}

			
			$this->request->redirect("admin/menu_settings");

			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
		
		$site_menu_settings = $settings->get_menusettings();
                $this->selected_page_title = __("menu_settings");
                $view= View::factory('admin/admin_menu_settings')
                ->bind('validator', $validator)
                ->bind('errors', $errors)
                ->bind('site_menu_settings', $site_menu_settings)
                ->bind('postvalue',$post_values);
                

		$this->template->title= SITENAME." | ".__('menu_settings');
		$this->template->page_title= __('menu_settings');
		$this->template->content = $view;

        }
        
        //delete the menus
        public function action_delete_menus()
	{
		$this->is_login();	
		$id = $this->request->param( 'id' );
		$delete_menus = Model::factory('admin');
		$delete_menu = $delete_menus->delete_menus($id);
		if($delete_menu){
		Message::success(__('Menu was deleted.'));
		$this->request->redirect("admin/menu_settings");
		}
	}
        
        /** Manage module Settings **/
        public function action_module_settings()
		{
			$this->is_login(); 
			$usertype = $_SESSION['user_type'];
			if($usertype !='A'){
				$this->request->redirect("admin/login");
			}
			
			$settings = Model::factory('admin');
			$errors = array();
			$signup_submit =arr::get($_REQUEST,'submit_modules'); 
			$errors = array();
			$post_values = array();
			
			
			if ($signup_submit && Validation::factory($_POST,$_FILES) ) 
			{
				$post_values = $_POST;
				
				$post = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
				//print_r($post);exit;
				$validator = $settings->validate_update_module(arr::extract($post,array('banner_image1','banner_image2','banner_image3','banner_image4','banner_image5')),$_FILES);
				
				if($validator->check())
				{

					$count = $post['member'];
					$image_id = $post['image_id'];
					
					$image_updated_status = '';
					if(!empty($_FILES['banner_image1']['name'])){
					/* image1 */

					$image_name1 = uniqid().$_FILES['banner_image1']['name']; 
					$image_type=explode('.',$image_name1);
					$image_type=end($image_type);
					
					//$image_name=url::title($image_name).'.'.$image_type;
					$filename = Upload::save($_FILES['banner_image1'],$image_name1,DOCROOT.BANNER_IMGPATH);
					//chmod($filename,'0777');
					//Image resize and crop for thumb image
					$logo_image1 = Image::factory($filename);
					$path11=DOCROOT.BANNER_IMGPATH;
					$path1=$image_name1;
					
					Commonfunction::imageresize($logo_image1,BANNER_SLIDER_WIDTH, BANNER_SLIDER_HEIGHT,$path11,$image_name1,90);
					$image_updated_status = $settings->update_module_settings_images1($path1,$image_id);
					}
					if(!empty($_FILES['banner_image2']['name'])){

					/* image2 */
					$image_name2 = uniqid().$_FILES['banner_image2']['name']; 
					$image_type=explode('.',$image_name2);
					$image_type=end($image_type);
					
					//$image_name=url::title($image_name).'.'.$image_type;
					$filename = Upload::save($_FILES['banner_image2'],$image_name2,DOCROOT.BANNER_IMGPATH);
					//chmod($filename,'0777');
					//Image resize and crop for thumb image
					$logo_image2 = Image::factory($filename);
					$path22=DOCROOT.BANNER_IMGPATH;
					$path2=$image_name2;
					
					Commonfunction::imageresize($logo_image2,BANNER_SLIDER_WIDTH, BANNER_SLIDER_HEIGHT,$path22,$image_name2,90);
					
					$image_updated_status = $settings->update_module_settings_images2($path2,$image_id);
					}
					if(!empty($_FILES['banner_image3']['name'])){

					/* image3 */
					$image_name3 = uniqid().$_FILES['banner_image3']['name']; 
					$image_type=explode('.',$image_name3);
					$image_type=end($image_type);
					
					//$image_name=url::title($image_name).'.'.$image_type;
					$filename = Upload::save($_FILES['banner_image3'],$image_name3,DOCROOT.BANNER_IMGPATH);
					//chmod($filename,'0777');
					//Image resize and crop for thumb image
					$logo_image3 = Image::factory($filename);
					$path33=DOCROOT.BANNER_IMGPATH;
					$path3=$image_name3;
					
					Commonfunction::imageresize($logo_image3,BANNER_SLIDER_WIDTH, BANNER_SLIDER_HEIGHT,$path33,$image_name3,90);
					
					$image_updated_status = $settings->update_module_settings_images3($path3,$image_id);
					}
					if(!empty($_FILES['banner_image4']['name'])){

					/* image4 */
					$image_name4 = uniqid().$_FILES['banner_image4']['name']; 
					$image_type=explode('.',$image_name4);
					$image_type=end($image_type);
					
					//$image_name=url::title($image_name).'.'.$image_type;
					$filename = Upload::save($_FILES['banner_image4'],$image_name4,DOCROOT.BANNER_IMGPATH);
					//chmod($filename,'0777');
					//Image resize and crop for thumb image
					$logo_image4 = Image::factory($filename);
					$path44=DOCROOT.BANNER_IMGPATH;
					$path4=$image_name4;
					
					Commonfunction::imageresize($logo_image4,BANNER_SLIDER_WIDTH, BANNER_SLIDER_HEIGHT,$path44,$image_name4,90);
					
					$image_updated_status = $settings->update_module_settings_images4($path4,$image_id);
					}
					if(!empty($_FILES['banner_image5']['name'])){

					/* image5 */
					$image_name5 = uniqid().$_FILES['banner_image5']['name']; 
					$image_type=explode('.',$image_name5);
					$image_type=end($image_type);
					
					//$image_name=url::title($image_name).'.'.$image_type;
					$filename = Upload::save($_FILES['banner_image5'],$image_name5,DOCROOT.BANNER_IMGPATH);
					//chmod($filename,'0777');
					//Image resize and crop for thumb image
					$logo_image5 = Image::factory($filename);
					$path55=DOCROOT.BANNER_IMGPATH;
					$path5=$image_name5;
					
					Commonfunction::imageresize($logo_image5,BANNER_SLIDER_WIDTH, BANNER_SLIDER_HEIGHT,$path55,$image_name5,90);
					
					$image_updated_status = $settings->update_module_settings_images5($path5,$image_id);
					
					}
					
					/*if(!empty($_FILES['banner_image1']['name']) || !empty($_FILES['banner_image2']['name']) || !empty($_FILES['banner_image3']['name']) || !empty($_FILES['banner_image4']['name']) || !empty($_FILES['banner_image5']['name'])){
						$status = $settings->update_module_settings_images($path1,$path2,$path3,$path4,$path5,$image_id);
					}*/
					
					$status = $settings->update_module_settings($post,$count);

					if($status == 1)
					{
						$status = $status;
					}
					else
					{
						$status = $image_updated_status;
					}
					
					if($status != 0)
					{
						Message::success(__('sucessful_settings_update'));
					}
					else
					{
						Message::error(__('not_updated'));
					}
				
				$this->request->redirect("admin/module_settings");

				}
				else
				{
					$errors = $validator->errors('errors');
				}
			}
			
			$site_settings = $settings->site_module_settings();
			$site_info_settings = $settings->site_info_settings();
			
					
					$this->selected_page_title = __("module_setting");
					$view= View::factory('admin/site_module_settings')
				->bind('validator', $validator)
				->bind('errors', $errors)
				->bind('postvalue',$post_values)
				->bind('site_info_settings',$site_info_settings)
				->bind('site_settings', $site_settings);               

			$this->template->title= SITENAME." | ".__('module_setting');
			$this->template->page_title= __('module_setting');
			$this->template->content = $view;

        }
        
	/** Manage Site social settings **/
        public function action_social_network()
        {
			
		$this->is_login(); 
		$usertype = $_SESSION['user_type'];
		if($usertype !='A'){
			$this->request->redirect("admin/login");
		}
                $settings = Model::factory('admin');
                $errors = array();
		$socialsettings_submit =arr::get($_REQUEST,'editsocialsettings_submit'); 
		$errors = array();
		$post_values = array();
		if ($socialsettings_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			$post = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));

			$validator = $settings->validate_update_socialinfo(arr::extract($post,array('facebook_key','facebook_secretkey','facebook_share','twitter_share','google_share','linkedin_share')));
			//'site_city';
			if($validator->check())
			{
			   $status = $settings->update_socialinfo($post);

				if($status == 1)
				{
					Message::success(__('sucessful_settings_update'));
				}
				else
				{
					Message::error(__('not_updated'));
				}
				
				$this->request->redirect("admin/social_network");
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
                $id = $this->request->param('id');
                // $id=1;
                $socialsettings = $this->siteinfo;
                $this->selected_page_title = __("site_settings");
                $view= View::factory('admin/socialnetwork_settings')
                ->bind('validator', $validator)
                ->bind('errors', $errors)
                ->bind('postvalue',$post_values)
                ->bind('socialsettings', $socialsettings);
    
		$this->template->title= SITENAME." | ".__('social_network_setting');
		$this->template->page_title= __('social_network_setting'); 	             
		$this->template->content = $view;

        }
        
       
        public function action_payment_gateways()
        { 
		$this->is_login(); 
		$usertype = $_SESSION['user_type'];
		if($usertype !='A'){
			$this->request->redirect("admin/login");
		}
                $settings = Model::factory('admin');
                $errors = array();
		$payment_settings_submit =arr::get($_REQUEST,'editpaymentsettings_submit'); 
		$errors = array();
		$post_values = array();
		
		if ($payment_settings_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			//$validator = $settings->validate_update_payment_submit(arr::extract($_POST,array('payment_gatway_name','description','currency_code','currency_symbol','payment_method','paypal_api_username','paypal_api_password','paypal_api_signature')));
			//'site_city';
			
			if(!isset($_POST['paymodstatus']))
			{
				$check_paystatus = 0;
			}
			else
			{
				if(in_array($_POST['default'][0],$_POST['paymodstatus']))
				{ 
					$check_paystatus = 1;
				}	
				else
				{ 
					$check_paystatus = 2;
				}
			}


			$check_default = $settings->check_array($_POST['default']);

			
			if($check_paystatus == 1)
			{
			        $status = $settings->update_payment_submit($_POST);
			   
			   	if($status == 1)
				{
					Message::success(__('sucessful_settings_update'));
				}
				else
				{
					Message::error(__('not_updated'));
				}
				
				$this->request->redirect("admin/payment_gateways");
			}
			else
			{
				//$errors = $validator->errors('errors');

				if($check_paystatus == 0)
				{
					$errors['paymodstatus'] = 'Please select any one of the gateway';
				}
				else if($check_paystatus == 2)	
				{
					$errors['paymodstatus'] = 'Please select the default gateway';
				}

			}
		}
		$id = $this->request->param('id');
		$gatway_list = $settings->get_payment_gateway_list();

		$this->selected_page_title = __("site_settings");
		$view = View::factory('admin/payment_gateway_settings')
				->bind('validator', $validator)
				->bind('errors', $errors)
				->bind('postvalue',$post_values)
				->bind('gatway_list',$gatway_list);
		$this->template->title= SITENAME." | ".__('payment_gateway_module');
		$this->template->page_title= __('payment_gateway_module'); 	             
		$this->template->content = $view;

        }

	 public function action_payment_gateway_module()
     { 
		$this->is_login(); 
		$usertype = $_SESSION['user_type'];
		if($usertype !='A' ){
			$this->request->redirect("admin/login");
		}
                $settings = Model::factory('admin');
		
             	$update_post = arr::get($_REQUEST,'update');
		$post = array();
		if($update_post){
			$post = $_REQUEST;
			if(isset($post['default_payment']))
			{
				$id = $post['default_payment'];
				$update_default_country = $settings->update_default_payment($id);
			
				if($update_default_country == 1){
					Message::success(__('changed_default_payment'));
					$this->request->redirect("admin/payment_gateway_module");
				}
				else if($update_default_country == '-1'){
					Message::error(__('select_the_activepayment'));
					$this->request->redirect("admin/payment_gateway_module");
				}
				else{
					Message::error(__('select_the_defaultpayment'));
					$this->request->redirect("admin/payment_gateway_module");
				}
			}
			else
			{
					Message::error(__('not_updated'));
					$this->request->redirect("admin/payment_gateway_module");			
			}

		}
		
                //$id = $this->request->param('id');
                //$currencysymbol = $this->currencysymbol;
                //$currencycode = $this->all_currency_code;

		$count_company_list = $settings->count_paymentgateway_list();

			//pagination loads here
		//-------------------------
		$page_no= isset($_GET['page'])?$_GET['page']:0;
		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
			'current_page'   => array('source' => 'query_string','key' => 'page'),
			'items_per_page' => REC_PER_PAGE,
			'total_items'    => $count_company_list,
			'view' => 'pagination/punbb',
		));

                $payment_settings = $settings->get_payment_gateways($offset, REC_PER_PAGE);
                $this->selected_page_title = __("site_settings");
                $view= View::factory('admin/manage_payment_module')
                ->bind('validator', $validator)
                ->bind('errors', $errors)
                ->bind('postvalue',$post_values)
		->bind('pag_data',$pag_data)
                ->bind('payment_settings', $payment_settings)
                ->bind('Offset',$offset);

		$this->template->title= SITENAME." | ".__('payment_gateway_setting');
		$this->template->page_title= __('payment_gateway_setting'); 	             
		$this->template->content = $view;

       		 }
	

	public function action_edit_admin_gateways()
        { 
		$this->is_login(); 
		$usertype = $_SESSION['user_type'];
		if($usertype !='A'){
			$this->request->redirect("admin/login");
		}
                $settings = Model::factory('admin');
                $errors = array();
		$payment_settings_submit =arr::get($_REQUEST,'editadminpayment'); 
		$errors = array();
		$post_values = array();
		$id = $this->request->param('id');
		if ($payment_settings_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			$validator = $settings->validate_update_payment_submit(arr::extract($_POST,array('payment_gatway_name','description','currency_code','currency_symbol','payment_method','paypal_api_username','paypal_api_password','paypal_api_signature')));
			//'site_city';
			
			
			//$check_default = $settings->check_array($_POST['default']);

			
			if($validator->check())
			{
				
			        $status = $settings->admin_payment_submit($_POST,$id);
			   	if($status == 1)
				{
					Message::success(__('sucessful_settings_update'));
				}
				else
				{
					Message::error(__('not_updated'));
				}
				
				$this->request->redirect("admin/payment_gateway_module");
			}
			else
			{
				$errors = $validator->errors('errors');

				/*if($check_paystatus == 0)
				{
					$errors['paymodstatus'] = 'Please select any one of the gateway';
				}
				else if($check_paystatus == 2)	
				{
					$errors['paymodstatus'] = 'Please select the default gateway';
				} */

			}
		}
                
                $currencysymbol = $this->currencysymbol;
                $currencycode = $this->all_currency_code;
                $payment_settings = $settings->get_payment_gateway_detail($id);
                $this->selected_page_title = __("site_settings");
                $view= View::factory('admin/edit_gateway_settings')
                ->bind('validator', $validator)
                ->bind('errors', $errors)
                ->bind('postvalue',$post_values)
                ->bind('payment_settings', $payment_settings)
                ->bind('currency_symbol',$currencysymbol)
                ->bind('currency_code',$currencycode);

		$this->template->title= SITENAME." | ".__('payment_gateway_setting');
		$this->template->page_title= __('payment_gateway_setting'); 	             
		$this->template->content = $view;

        }



	public function action_mail_settings()
	{
			$this->is_login(); 
			$usertype = $_SESSION['user_type'];
			if($usertype !='A'){
				$this->request->redirect("admin/login");
			}
					$settings = Model::factory('admin');
					$errors = array();
			$signup_submit =arr::get($_REQUEST,'submit_editmailsetings'); 
			$errors = array();
			$post_values = array();
			$id = $this->request->param('id');
			if ($signup_submit && Validation::factory($_POST) ) 
			{
				$post_values = $_POST;
				$post = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
				$validator = $settings->validate_mailsettings(arr::extract($post,array('smtp_host','smtp_port','smtp_username','smtp_password','transport_layer_security','smtp')));
				
				if($validator->check())
				{
					$status = $settings->updatemailsetting($post,$id);
					if($status == 1)
					{
						Message::success(__('sucessful_settings_update'));
					}
					else
					{
						Message::error(__('not_updated'));
					}
	
				$this->request->redirect("admin/mail_settings");
	
				}
				else
				{
					$errors = $validator->errors('errors');
				}
			}
			$mail_settings = $settings->mail_settings($id);
			//$mail_settings = $this->mail_settings;
			$this->selected_page_title = __("site_settings");
			$view= View::factory('admin/manage_mail_settings')
					->bind('validator', $validator)
					->bind('errors', $errors)
					->bind('postvalue',$post_values)
					->bind('mail_settings', $mail_settings);
			$this->template->title= SITENAME." | ".__('menu_mail_settings');
			$this->template->page_title= __('menu_mail_settings');
			$this->template->content = $view;
	}

	public function action_sms_template()
	{
		$this->is_login(); 
		$usertype = $_SESSION['user_type'];
		if($usertype !='A'){
			$this->request->redirect("admin/login");
		}
                $settings = Model::factory('admin');
                $errors = array();
		$signup_submit =arr::get($_REQUEST,'submit_smstemplate'); 
		$errors = array();
		$post_values = array();
		$id = $this->request->param('id');
		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			$validator = $settings->validate_mailsettings(arr::extract($_POST,array('smtp_host','smtp_port','smtp_username','smtp_password','transport_layer_security','smtp')));
			
			if($validator->check())
			{
			   $status = $settings->updatemailsetting($_POST,$id);

				if($status == 1)
				{
					Message::success(__('sucessful_settings_update'));
				}
				else
				{
					Message::error(__('not_updated'));
				}
				

			$this->request->redirect("admin/mail_settings");

			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

                $sms_template = $settings->sms_template($id);

                $this->selected_page_title = __("sms_template");
                $view= View::factory('admin/manage_sms_template')
                ->bind('validator', $validator)
                ->bind('errors', $errors)
                ->bind('postvalue',$post_values)
                ->bind('sms_template', $sms_template);
                
		$this->template->title= SITENAME." | ".__('sms_template');
		$this->template->page_title= __('sms_template'); 	                
                $this->template->content = $view;

	}

	public function action_activeusers_list()
	{
        	$this->is_login(); 
        	$dashboard = Model::factory('admin');
        	$activeusers_list = $dashboard->get_activeusers_list();
        	$output = '';

        	$output.='<thead>
        	<tr>
		        <td width="80">'.__('name_label').'</td>
		        <td width="80">'.__('last_login').'</td>
		        <td width="80">'.__('phone_label').'</td>
		        <td width="80">'.__('address_label').'</td>
                </tr>
		</thead>
		<tbody>';
		if (isset($activeusers_list) && count($activeusers_list) > 0) { 
		foreach($activeusers_list as $activeuserslist){         
              $output.='<tr>
                <td align="center"><a href="'.URL_BASE.'manage/passengerinfo/'.$activeuserslist['id'].'" title="'.$activeuserslist['name'].'" >'.$activeuserslist['name'].'</a></td>
                <td><span>'.$activeuserslist['last_login'].'</span></td>
                <td><span >'.$activeuserslist['phone'].'</span></td>
                <td><span >'.$activeuserslist['address'].'</span></td>
            </tr>';
        	} 
        }
        else
        { 
			$output.='<tr><td colspan="4" align="center">'.__('no_data').'</td> </tr>';
        }
        $output.='</tbody>';
		echo $output;exit;        	
	}
        
       
	public function action_dashboardold()
	{
		$this->is_login();
		if(isset($_SESSION['user_type']))
		{
			$usertype = $_SESSION['user_type'];
			
			if($usertype =='M')
			{ 
				$this->urlredirect->redirect('manager/dashboard');
			}
			if($usertype =='C')
			{ 
				$this->urlredirect->redirect('company/dashboard');
			}
		}
		else
		{
			$this->urlredirect->redirect('admin/login');
		}
				
		$dashboard = Model::factory('admin');
		$dash = Model::factory('dashboard');
		$taxidispatch = Model::factory('taxidispatch');
		
		//open vbx code
		$cookie_name='login_user';
		$cookie_value=$this->session->get('email');
		//$cookie_value='naveen.n@ndot.in';
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
		setcookie('user_type_openvbx', $this->session->get('user_type'), time() + (86400 * 30), "/");
		echo"<iframe src='".URL_BASE."/callcenter/thirdparty/login' height='0px' width='0px'></iframe>";
		$database_exits = $dashboard->get_database_exists('openvbx',$cookie_value);
		//echo $database_exits;exit; 
		$this->session->set('vbx_show',$database_exits);
		//$this->session->set('vbx_show',0);
		
		//echo $_SESSION['vbx_show'];exit;
		//open vbx code

		$post_values = array();
		$post_values = $_REQUEST;
		
		
		$admin_dashboard_data = $dashboard->get_admin_dashboard_data();
		
			//$activeusers_list = $dashboard->get_activeusers_list();
			$activeusers_list_count = $dashboard->get_activeusers_list_count(); 
			
			//$availabletaxi_list = $dashboard->get_availabletaxi_list();
			$availabletaxi_list_count = $dashboard->get_availabletaxi_list_count();

			//$freedriver_list = $dashboard->free_driver_list();
			$freedriver_list_count = $dashboard->free_driver_list_count();
			
			//$freetaxi_list = $dashboard->free_taxi_list();
			$freetaxi_list_count = $dashboard->free_taxi_list_count();
			
			//$comapny_countlist = $dashboard->get_comapny_countlist();
			//$passenger_countlist = $dashboard->count_passenger_list_history();
			//$drivers_countlist = $dashboard->get_drivers_countlist();
			//$availabletaxi_countlist = $dashboard->get_taxi_countlist();

		$get_company_details = $dashboard->get_company_details();
		
		if(isset($_REQUEST['userstartdate'])  && isset($_REQUEST['userenddate']))
		{	if(($_REQUEST['userstartdate'] !='') && ($_REQUEST['userenddate'] !=''))
			{
				$getUserbyCompany = $dashboard->changegetUserbyCompany($_REQUEST['userstartdate'],$_REQUEST['userenddate']);
			}
			else
			{
				$getUserbyCompany = $dashboard->getUserbyCompany();
			}
		}
		else
		{
			$getUserbyCompany = $dashboard->getUserbyCompany();
		}
		
		if(isset($_REQUEST['transtartdate'])  && isset($_REQUEST['tranenddate']))
		{	
			if(($_REQUEST['transtartdate'] !='')  && ($_REQUEST['tranenddate'] !=''))
			{
				$transactionbyCompany = $dashboard->changetransactionbyCompany($_REQUEST['transtartdate'],$_REQUEST['tranenddate']);
			}
			else
			{
				$transactionbyCompany = $dashboard->transactionbyCompany();
			}
		}
		else
		{
			$transactionbyCompany = $dashboard->transactionbyCompany();
		}
		//$all_company_map_list = $dashboard->all_driver_map_list();
		$val = array();
		$all_company_map_list = $taxidispatch->driver_status_details($val);
		//print_r($all_company_map_list);exit;

		$activePassengers = $dashboard->logged_in_passengers();
		$company_accountbalance = $dashboard->company_accountbalance();

		$this->selected_page_title = __("dashboard");
		$view= View::factory('admin/dashboard')
			->bind('postvalue',$post_values)
			//->bind('activeusers_list', $activeusers_list)
			->bind('activeusers_list_count', $activeusers_list_count)
			//->bind('availabletaxi_list', $availabletaxi_list)
			->bind('availabletaxi_list_count', $availabletaxi_list_count)
			/*->bind('comapny_countlist', $comapny_countlist)
			->bind('passenger_countlist', $passenger_countlist)
			->bind('drivers_countlist', $drivers_countlist)
			->bind('availabletaxi_countlist', $availabletaxi_countlist) */
			//->bind('freetaxi_list', $freetaxi_list)
			->bind('freetaxi_list_count', $freetaxi_list_count)
			->bind('admin_dashboard_data', $admin_dashboard_data)
			->bind('getUserbyCompany', $getUserbyCompany)
			->bind('transactionbyCompany', $transactionbyCompany)
			->bind('company_accountbalance',$company_accountbalance)
			->bind('freedriver_list_count',$freedriver_list_count)
			//->bind('freedriver_list', $freedriver_list)
			->bind('get_company_trip_count', $get_company_trip_count)
			->bind('get_company_details', $get_company_details)
			->bind('activePassengers', $activePassengers)
			->bind('all_company_map_list', $all_company_map_list);
			
		$this->template->title= SITENAME." | ".__('dashboard');
		$this->template->page_title= __('dashboard');
		$this->template->content = $view;
		
	}
	
	public function action_dashboard()
	{
		
		if(isset($_SESSION['user_type'])) {
			$usertype = $_SESSION['user_type'];
			if($usertype =='M') {
				$this->urlredirect->redirect('manager/dashboard');
			}
			if($usertype =='C') {
				$this->urlredirect->redirect('company/dashboard');
			}
		} else {
			$this->urlredirect->redirect('admin/login');
		}
		$admin = Model::factory('admin');
		$get_all_company = $admin->get_single_multy_company();
		$driver_list = $admin->get_driver_list(COMPANY_CID);

		/* open vbx code*
		$cookie_name = 'login_user';
		$cookie_value = $this->session->get('email');
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
		setcookie('user_type_openvbx', $this->session->get('user_type'), time() + (86400 * 30), "/");
		echo"<iframe src='".URL_BASE."/callcenter/thirdparty/login' height='0px' width='0px'></iframe>";
		$database_exits = $admin->get_database_exists('openvbx',$cookie_value);
		$this->session->set('vbx_show',$database_exits);*/
		$this->selected_page_title = __("dashboard");
		$view = View::factory('admin/dashboard_new')
				->bind("driver_list",$driver_list)
				->bind("getAllCompany",$get_all_company);
		$this->template->title = SITENAME." | ".__('dashboard');
		$this->template->page_title = __('dashboard');
		$this->template->content = $view;
	}
	
	
	public function action_driver_dashboard()
	{
		
		if(isset($_SESSION['user_type'])) {
			$usertype = $_SESSION['user_type'];
			if($usertype =='M') {
				$this->urlredirect->redirect('manager/dashboard');
			}
			if($usertype =='C') {
				$this->urlredirect->redirect('company/dashboard');
			}
		} else {
			$this->urlredirect->redirect('admin/login');
		}
		$admin = Model::factory('admin');
		$driver_list = $admin->get_driver_list(0);
		/* open vbx code*
		$cookie_name = 'login_user';
		$cookie_value = $this->session->get('email');
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
		setcookie('user_type_openvbx', $this->session->get('user_type'), time() + (86400 * 30), "/");
		echo"<iframe src='".URL_BASE."/callcenter/thirdparty/login' height='0px' width='0px'></iframe>";
		$database_exits = $admin->get_database_exists('openvbx',$cookie_value);
		$this->session->set('vbx_show',$database_exits);*/
		$this->selected_page_title = __("dashboard");
		$view = View::factory('admin/driver_dashboard')
				->bind("driver_list",$driver_list);
		$this->template->title = SITENAME." | ".__('dashboard');
		$this->template->page_title = __('dashboard');
		$this->template->content = $view;
	}

	public function action_get_admin_notifications()
	{
		$this->is_login();
		if(isset($_POST["value"]))
		{
			$driver_model = Model::factory('admin');		
			$driver_id =$this->session->get('id');	
			$driver_id = '7';
			$driver_logs = $driver_model->get_freetaxi_ajax();
			//$noty_array = array();
			$noty_array="";		
			if(count($driver_logs) > 0)
			{
				

				foreach ($driver_logs as $driver) 
				{
					$noty_array.= '<div class="alert alert-block alert-info fade in" id="notification_id">						
						<h4 class="alert-heading">'.__('new_user_notification').'<strong> - '.ucfirst($driver->name).'</strong></h4>
						<dl class="dl-horizontal"> 
						  <dt>'.__("Current_Location").' </dt>
						  <dd>'.$driver->current_location.'</dd>
						  <dt>'.__("Drop_Location").' </dt>
						  <dd>'.$driver->drop_location.'</dd>
						  <dt>'.__("pick_up_time").' </dt>
						  <dd>'.date('H:i:s',strtotime($driver->pickup_time)).'</dd>
						  <dt>'.__("No_Passengers").' </dt>
						  <dd>'.$driver->no_passengers.'</dd>
						  <dt>'.__("Contact Number").' </dt>
						  <dd>'.$driver->phone.'</dd>											 
						</dl>
						<p>
						</p>
					</div>';
					//array_push($noty_array, $html);
				}			
			}
			if(count($driver_logs) == 0)
				$noty_array ='<h4>'.__("no_notifications").'</h4>';	
			
			echo $noty_array;
			exit;
		}
	}

	/** Manage Fund Request **/
        public function action_manage_fund_request()
        {
		
		$find_url = explode('/',$_SERVER['REQUEST_URI']);  
		$split = explode('?',$find_url[3]);  	

		$list = $split[0];

		$this->is_login(); 
		$usertype = $_SESSION['user_type'];

		if($usertype !='A'){
			$this->request->redirect("admin/login");
		}

        $admin_model = Model::factory('admin');
		$transaction_model = Model::factory('transaction');

		$socialsettings_submit =arr::get($_REQUEST,'editsocialsettings_submit'); 
		$errors = array();
		$post_values = array();

		if ( $list == 'all' ) {
			$page_title = __("transaction_fr_all");
		}
		elseif ( $list == 'approved' ) {
			$page_title = __("approvedfundrequest");
		} elseif ( $list == 'rejected' ) {
			$page_title = __("rejectfundreq");
		} elseif ( $list == 'success' ) {
			$page_title = __("successfundreq");
		} elseif ( $list =='failed') {
			$page_title = __("failedfundreq");
		} elseif ( $list =='pending' ) {
			$page_title = __("pendingfundreq");
		}



		$count_fundrequest_list = $admin_model->count_fundrequest_list($list);
		$get_allcompany = $transaction_model->get_allcompany_tranaction();

		//pagination loads here
		//-------------------------
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
		'current_page'   => array('source' => 'query_string','key' => 'page'),
		'items_per_page' => REC_PER_PAGE,
		'total_items'    => $count_fundrequest_list,
		'view' => 'pagination/punbb',			  
		));
   
		$all_fundrequest_list = $admin_model->all_fundreuest_list($list,$offset, REC_PER_PAGE);


                $this->selected_page_title = $page_title;
                $view= View::factory('admin/manage_fund_request')
                ->bind('validator', $validator)
                ->bind('errors', $errors)
                ->bind('postvalue',$post_values)
		->bind('pag_data',$pag_data)
		->bind('get_allcompany',$get_allcompany)
                ->bind('all_fundrequest_list',$all_fundrequest_list)
		->bind('Offset',$offset);
    
		$this->template->title= SITENAME." | ".$page_title;
		$this->template->page_title= $page_title; 	             
		$this->template->content = $view;

        }

	/** Manage Fund Request **/
        public function action_manage_fund_request_list()
        {
		$find_url = explode('/',$_SERVER['REQUEST_URI']);  
		$split = explode('?',$find_url[3]);  	
		$list = $split[0];

		$this->is_login(); 
		$usertype = $_SESSION['user_type'];

		if($usertype !='A'){
			$this->request->redirect("admin/login");
		}

                $admin_model = Model::factory('admin');
		$transaction_model = Model::factory('transaction');

		$company_id = trim(Html::chars($_REQUEST['filter_company']));

		$socialsettings_submit =arr::get($_REQUEST,'editsocialsettings_submit'); 
		$errors = array();
		$post_values = array();

		if ( $list == 'all' ) {
			$page_title = __("transaction_fr_all");
		}
		elseif ( $list == 'approved' ) {
			$page_title = __("approvedfundrequest");
		} elseif ( $list == 'rejected' ) {
			$page_title = __("rejectfundreq");
		} elseif ( $list == 'success' ) {
			$page_title = __("successfundreq");
		} elseif ( $list =='failed') {
			$page_title = __("failedfundreq");
		} elseif ( $list =='pending' ) {
			$page_title = __("pendingfundreq");
		}



		$count_fundrequest_list = $admin_model->count_search_fundrequest_list($list,$company_id);
		$get_allcompany = $transaction_model->get_allcompany_tranaction();

		//pagination loads here
		//-------------------------
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
		'current_page'   => array('source' => 'query_string','key' => 'page'),
		'items_per_page' => REC_PER_PAGE,
		'total_items'    => $count_fundrequest_list,
		'view' => 'pagination/punbb',			  
		));
   
		$all_fundrequest_list = $admin_model->all_search_fundreuest_list($list,$offset, REC_PER_PAGE,$company_id);


                $this->selected_page_title = $page_title;
                $view= View::factory('admin/manage_fund_request')
                ->bind('validator', $validator)
                ->bind('errors', $errors)
                ->bind('postvalue',$post_values)
		->bind('pag_data',$pag_data)
		->bind('get_allcompany',$get_allcompany)
                ->bind('all_fundrequest_list',$all_fundrequest_list)
		->bind('srch',$_REQUEST)
		->bind('Offset',$offset);
    
		$this->template->title= $page_title." | ".SITENAME;
		$this->template->page_title= $page_title; 	             
		$this->template->content = $view;

        }


	
	public function action_payment_module(){
		$this->is_login();
		$find_url = explode('/',$_SERVER['REQUEST_URI']);  
		$split = explode('?',$find_url[3]);  	
		$pay_mod_id = $split[0];


		$admin_model = Model::factory('admin');

		$result = $admin_model->delete_module($pay_mod_id);

		if($result == 1)
		{
			Message::success(__('changesmodified'));
		}
		$this->request->redirect("admin/payment_gateways");

	}


	//Admin Transactions without Search action 
	public function action_account_report()
	{
		$this->is_login();
		$find_url = explode('/',$_SERVER['REQUEST_URI']);
		$split = explode('?',$find_url[2]);
		$list = $split[0];
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype =='C')
		{
			$this->request->redirect("company/login");
		}
		
		if($usertype =='M')
		{
			$this->request->redirect("manager/login");
		}
		$manage_transaction = Model::factory('transaction');
		$admin_model = Model::factory('admin');
		$common_model = Model::factory('commonmodel');

		$page_title = __("account_report");
		$list = 'all';

		$get_allcompany = $manage_transaction->get_allcompany_tranaction($usertype);
		//$taxilist = $manage_transaction->gettaxidetails('','');
		//$passengerlist = $manage_transaction->getpassengerdetails('','');
		//$driverlist = $manage_transaction->getdriverdetails('','');
		//$managerlist = $manage_transaction->getmanagerdetails('');
		$startdate = date('Y-m-01 00:00:00');
		$enddate  = date('Y-m-t 12:59:59');
		//$count_transaction_list = $manage_transaction->count_accountreport_list($list,'All','','','');
		$grpahdata = $manage_transaction->getaccountreportvalues($list,'All',$startdate,$enddate,'');

		$gateway_details = $common_model->gateway_details();

		$package_details = $common_model->package_details();

		//pagination loads here
		/*$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
		'current_page'   => array('source' => 'query_string','key' => 'page'),
		'items_per_page' => REC_PER_PAGE,
		'total_items'    => $count_transaction_list,
		'view' => 'pagination/punbb', 
		)); */
   
		$all_transaction_list = $manage_transaction->accountreport_details($list,'All',$startdate,$enddate,'');
		//****pagination ends here***//

		//send data to view file 
		
		$view = View::factory('admin/account_report')
				->bind('Offset',$offset)
				->bind('action',$action)
				->bind('srch',$_REQUEST)
				->bind('pag_data',$pag_data)
				->bind('all_transaction_list',$all_transaction_list)
				//->bind('taxilist',$taxilist)
				//->bind('driverlist',$driverlist)
				//->bind('managerlist',$managerlist)
				//->bind('passengerlist',$passengerlist)
				->bind('get_allcompany',$get_allcompany)
				->bind('gateway_details',$gateway_details)
				->bind('package_details',$package_details)
				->bind('grpahdata',$grpahdata)
				->bind('id',$id);
		
		$this->page_title = $page_title;
		$this->template->title= $page_title." | ".SITENAME;
		$this->template->page_title= $page_title; 
		$this->template->content = $view;
	}


	public function action_account_report_list()
	{
		$this->is_login();
		$find_url = explode('/',$_SERVER['REQUEST_URI']);  
		$split = explode('?',$find_url[2]);  	
		$list = $split[0];	


		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype =='C')
		{
			$this->request->redirect("company/login");
		}
		
		if($usertype =='M')
		{
			$this->request->redirect("manager/login");
		}		

		$page_title = __("account_report");
		$list = 'all';


		$company = trim(Html::chars($_REQUEST['filter_company']));
		$startdate = Commonfunction::ensureDatabaseFormat(trim(Html::chars($_REQUEST['startdate'])),1);
		$enddate = Commonfunction::ensureDatabaseFormat(trim(Html::chars($_REQUEST['enddate'])),2);
		//$taxiid = trim(Html::chars($_REQUEST['taxiid']));
		//$driver_id = trim(Html::chars($_REQUEST['driver_id']));
		//$manager_id = trim(Html::chars($_REQUEST['manager_id']));
		//$passengerid = trim(Html::chars($_REQUEST['passengerid']));
		//$transaction_id = trim(Html::chars($_REQUEST['transaction_id']));
		$payment_type = trim(Html::chars($_REQUEST['payment_type']));

		$manage_transaction = Model::factory('transaction');
		$common_model = Model::factory('commonmodel');

		$get_allcompany = $manage_transaction->get_allcompany_tranaction();
		//$taxilist = $manage_transaction->gettaxidetails($company,$manager_id);
		//$passengerlist = $manage_transaction->getpassengerdetails($company,$manager_id);
		//$driverlist = $manage_transaction->getdriverdetails($company,$manager_id);
		$managerlist = $manage_transaction->getmanagerdetails($company);
		/*$count_transaction_list = $manage_transaction->count_accountreport_list($list,$company,$startdate,$enddate,$payment_type);
//$manager_id,$taxiid,$driver_id,$passengerid,$transaction_id,
		//pagination loads here
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
		'current_page'   => array('source' => 'query_string','key' => 'page'),
		'items_per_page' => REC_PER_PAGE,
		'total_items'    => $count_transaction_list,
		'view' => 'pagination/punbb', 
		)); */
   
		$all_transaction_list = $manage_transaction->accountreport_details($list,$company,$startdate,$enddate,$payment_type);//$manager_id,$taxiid,$driver_id,$passengerid,$transaction_id,
		$grpahdata = $manage_transaction->getaccountreportvalues($list,$company,$startdate,$enddate,$payment_type);
//,$manager_id,$taxiid,$driver_id,$passengerid,,$transaction_id
		$gateway_details = $common_model->gateway_details();
		//****pagination ends here***//

		//send data to view file 
		
		$view = View::factory('admin/account_report')
				->bind('Offset',$offset)
				->bind('action',$action)
				->bind('srch',$_REQUEST)
			    	->bind('pag_data',$pag_data)
				->bind('all_transaction_list',$all_transaction_list)
				->bind('taxilist',$taxilist)
				->bind('driverlist',$driverlist)
				->bind('managerlist',$managerlist)
				->bind('passengerlist',$passengerlist)
				->bind('get_allcompany',$get_allcompany)
				->bind('grpahdata',$grpahdata)
				->bind('gateway_details',$gateway_details)
				->bind('id',$id);
		
		$this->page_title = $page_title;
		$this->template->title= $page_title." | ".SITENAME;
		$this->template->page_title= $page_title; 
		$this->template->content = $view;
	}

	
	public function action_saas_report()
	{
		$this->is_login();
		$find_url = explode('/',$_SERVER['REQUEST_URI']);  
		$split = explode('?',$find_url[2]);  	
		$list = $split[0];
		
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype =='C')
		{
			$this->request->redirect("company/login");
		}
		
		if($usertype =='M')
		{
			$this->request->redirect("manager/login");
		}

		$admin_model = Model::factory('admin');
		$startdate = date('Y-01-01 00:00:00');
		$enddate  = date('Y-12-t 12:59:59');

		$all_company_list_count = $admin_model->company_details_count($startdate,$enddate);
		//pagination loads here
		//-------------------------
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
			'current_page'   => array('source' => 'query_string','key' => 'page'),
			'items_per_page' => REC_PER_PAGE,
			'total_items'    => $all_company_list_count,
			'view' => 'pagination/punbb',			  
		));
		$all_company_list = $admin_model->company_details($offset,REC_PER_PAGE,$startdate,$enddate);
		$view = View::factory('admin/saas_report')
						->bind('Offset',$offset)
						->bind('pag_data',$pag_data)
						->bind('all_company_list',$all_company_list);
		$this->page_title = __('saas');$page_title=__('saas');
		$this->template->title= $page_title." | ".SITENAME;
		$this->template->page_title= $page_title; 
		$this->template->content = $view;

	}

	public function action_saas_report_list()
	{
		$this->is_login();
		$find_url = explode('/',$_SERVER['REQUEST_URI']);
		$split = explode('?',$find_url[2]);
		$list = $split[0];
		
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype =='C')
		{
			$this->request->redirect("company/login");
		}
		
		if($usertype =='M')
		{
			$this->request->redirect("manager/login");
		}
		
		$admin_model = Model::factory('admin');
		$startdate="";$enddate="";
		//$startdate = trim(Html::chars($_REQUEST['startdate']));
		//$enddate = trim(Html::chars($_REQUEST['enddate']));
		$package=trim(Html::chars($_REQUEST['company_package']));
		$all_company_search_count = $admin_model->company_details_search_count($startdate,$enddate,$package);
		//pagination loads here
		$page_no= isset($_GET['page'])?$_GET['page']:0;
		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);
		$pag_data = Pagination::factory(array (
		'current_page'   => array('source' => 'query_string','key' => 'page'),
		'items_per_page' => REC_PER_PAGE,
		'total_items'    => $all_company_search_count,
		'view' => 'pagination/punbb', 
		));
		$all_company_list = $admin_model->company_details_search($offset,REC_PER_PAGE,$startdate,$enddate,$package);
		$view = View::factory('admin/saas_report')
						->bind('srch',$_REQUEST)
						->bind('Offset',$offset)
						->bind('pag_data',$pag_data)
						->bind('all_company_list',$all_company_list);
		$this->page_title = __('saas');$page_title=__('saas');
		$this->template->title= $page_title." | ".SITENAME;
		$this->template->page_title= $page_title; 
		$this->template->content = $view;

	}

	public function action_saas_excel_report()
	{

		$startdate="";$enddate="";
		//$startdate = trim(Html::chars($_REQUEST['startdate']));
		//$enddate = trim(Html::chars($_REQUEST['enddate']));
		$fname="";
		$package=trim(Html::chars($_REQUEST['company_package']));
		if($package!=""){
		($package==1)?$fname="Trail-":$fname="Paid-";
		}else{ $fname="ALL-"; }
		$admin_model = Model::factory('admin');
		$results = $admin_model->saas_excel_report($startdate,$enddate,$package);
		$xls_output = "<table border='1' cellspacing='0' cellpadding='5'>";
			$xls_output .= "<th>".__('company_name')."</th>";
			$xls_output .= "<th>".__('company_domain')."</th>";
			$xls_output .= "<th>".__('email')."</th>";
			$xls_output .= "<th>".__('password')."</th>";
			$xls_output .= "<th>".__('upgrade_date')."</th>";
			$xls_output .= "<th>".__('upgrade_expirydate')."</th>";
			$file = $fname.'SAAS-Report';

		foreach($results as $result)
		{
			$xls_output .= "<tr>";
			$xls_output .= "<td>".$result['company_name']."</td>"; 
			$xls_output .= "<td>".$result['companydomain']."</td>"; 
			$xls_output .= "<td>".$result['email']."</td>"; 
			$xls_output .= "<td>".$result['password']."</td>"; 
			$xls_output .= "<td>".Commonfunction::getDateTimeFormat($result['upgrade_date'],1)."</td>"; 
			$xls_output .= "<td>".Commonfunction::getDateTimeFormat($result['upgrade_expirydate'],1)."</td>"; 
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


	public function action_printdetails()
	{
		$find_url = explode('/',$_SERVER['REQUEST_URI']);  
		$split = explode('?',$find_url[3]);  	
		$list = $split[0];

		$this->is_login(); 
		$usertype = $_SESSION['user_type'];
		$company_id = trim(Html::chars($_REQUEST['filter_company']));

		if($usertype !='A'){
			$this->request->redirect("admin/login");
		}

                $admin_model = Model::factory('admin');
		$transaction_model = Model::factory('transaction');

		$errors = array();
		$post_values = array();

		$page_title = __("transaction_fr_all");

		$print_fundrequest_list = $admin_model->print_fundreuest_list($company_id,$list);
//print_r($print_fundrequest_list);exit;
		?>

<?php header('Content-type: text/html; charset=utf-8'); ?>
<style>
body{ background:none!important;*background:#fff!important; }
</style>
<script type="text/javascript">
window.onload=window.print();
</script>
<table with="700" border="0">
	<tbody><tr style="font-size:0;">
		<td valign="top" align="left" style="margin:0;padding:0;border-bottom:2px solid #191919;"></td>
	</tr>
</tbody></table>

<table width="705" cellpadding="0" cellspacing="0" border="0" style="margin:auto;border-top:2px solid #191919; margin:auto;border-right:2px solid #191919;border-left:2px solid #191919; border-bottom:2px solid #191919; ">
	<tbody><tr style="font-size:0;">
		<td valign="top" align="left">
			<table width="705" cellpadding="1" cellspacing="0" border="0">
			
				<tbody><tr>
					<td width="3" valign="top" height="55" style="border-bottom: 2px solid #191919;">&nbsp;</td>
					<td valign="top" align="left" width="400" style="border-bottom: 2px solid #191919;">
					<a title="<?php echo SITE_NAME; ?>"><img src="<?php echo URL_BASE; ?>public/images/logo.png" alt="<?php echo SITE_NAME ?>" border="0"></a>
					</td><td valign="top" width="250" style="border-bottom: 2px solid #191919;">&nbsp;</td>
					<td valign="top" width="50" align="left" style="border-bottom: 2px solid #191919;">&nbsp;</td>
					<td valign="top" width="2" style="border-bottom: 2px solid #191919;">&nbsp;</td>
				</tr>
			</tbody></table>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="705" cellpadding="0" cellspacing="0" border="0">
				<tbody><tr height="10">
					<td valign="top"></td>
				</tr>
				<tr>
				<td valign="top" width="15"></td>
				<td valign="top" width="675">
				<table width="675" cellpadding="0" cellspacing="0" border="0" align="left">
				<tbody><tr>
				<td valign="top" align="left" width="350">
				<table width="500" cellpadding="0" cellspacing="0" border="0">
				<tbody><tr>
				<td valign="top" style="font:bold 17px arial;"><?php echo __('companyname').' : '; ?> <?php echo $print_fundrequest_list[0]['company_name']; ?>
				</td>
				</tr>
				<tr height="5">
				<td valign="top"></td>
				</tr>
				</tr>
				</tbody>
				</table>
				<table>
				<tbody><tr>
				<td valign="top" align="left">
				<p style="padding-top:5px;"><span style="color:#000000; font:bold 13px arial;"><?php echo __('company_details').' : '; ?></span>

				</p>

				</td>
				</tr>

				<tr>
				<td valign="top"> 
				<span style="color:#000000; font:bold 13px arial;"><?php echo __('name_label').' : '; ?></span></td>
				<td><span style="color:#940000; font:bold 13px arial;"><?php echo $print_fundrequest_list[0]['name']; ?></span>
				</td>
				</tr>

				<tr>
				<td valign="top"> 
				<span style="color:#000000; font:bold 13px arial;"><?php echo __('email_label').' : '; ?></span></td>
				<td><span style="color:#940000; font:bold 13px arial;"><?php echo $print_fundrequest_list[0]['email']; ?></span>

				</td>
				</tr>
				<tr>
				<td valign="top"> 
				<span style="color:#000000; font:bold 13px arial;"><?php echo __('companyaddress').' : '; ?></span></td><td>
				<span style="color:#940000; font:bold 13px arial;"><?php echo $print_fundrequest_list[0]['address']; ?></span>

				</td>
				</tr>
				<tr>
				<td valign="top">
				<span style="color:#000000; font:bold 13px arial;"><?php echo __('phone_number').' : '; ?></span></td><td>
				<span style="color:#940000; font:bold 13px arial;"><?php echo $print_fundrequest_list[0]['phone']; ?></span>

				</td>
				</tr>

				<tr>
				<td valign="top">
				<span style="color:#000000; font:bold 13px arial;"><?php echo __('paypalaccount').' : '; ?></span></td><td>
				<span style="color:#940000; font:bold 13px arial;"><?php echo $print_fundrequest_list[0]['paypal_account']; ?></span>

				</td>
				</tr>


				</tbody></table>
		</td>
			<td valign="top" width="15">
			</td>
			<td valign="top" width="300">
			<p style="margin:0px;padding:0;overflow:hidden;">
			</p>
			</td>
			</tr>
			</tbody></table>
			</td>
			</tr>
			<tr height="10">
			<td valign="top">
			</td>
			</tr>
			</tbody></table>
		</td>
	</tr>    
	<!-- MAIN DEAL -->
	<!-- INFO DETAILS -->
     <tr>
		<td valign="top">
		<table width="705" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #d1d1d1;">
			<tbody><tr height="10">
				<td>
				</td>
				<td>
				</td>
				<td>
				</td>
				<td>
				</td>
				<td>
				</td>
				<td>
				</td>
			</tr>
			<tr height="66">
			<td valign="left" width="2%"></td>

			<td valign="left" >
			<strong style="display: block;text-align:left;font:bold 13px arial;color: #000000;margin: 0;padding: 0;"><?php echo __('transaction_fr').' : '; ?></strong>
			</td>
			</tr>
			<tr>
			<td valign="top" width="2%"></td>					
			<td valign="top" width="20%">
			<p style="display: block;text-align: left;font:normal 13px arial;color: #333;margin: 0;padding: 0;"><?php echo __('requeston'); ?></p>
			</td>
			<td valign="top" width="20%">
			<p style="display: block;text-align: left;font:normal 13px arial;color: #333;margin: 0;padding: 0;"><?php echo __('amount'); ?></p>
			</td>
			<td valign="top" width="20%">
			<p style="display: block;text-align: left;font:normal 13px arial;color: #333;margin: 0;padding: 0;"><?php echo __('status'); ?></p>
			</td>
			<td valign="top" width="20%">
			<p style="display: block;text-align: left;font:normal 13px arial;color: #333;margin: 0;padding: 0;"><?php echo __('paymentstatus'); ?></p>
			</td>


			</tr>
			<?php foreach($print_fundrequest_list as $details) { ?>
			<tr height="25">
			<td valign="left" width="20"></td>					
			<td width="20%">
			<p style="display: block;text-align: left;font:normal 13px arial;color: #333;margin: 0;padding: 0;"><?php echo $details['requested_date']; ?></p>
			</td>
			<td width="20%">
			<p style="display: block;text-align: left;font:normal 13px arial;color: #333;margin: 0;padding: 0;"> <?php echo CURRENCY; ?> <?php echo $details['amount']; ?></p>
			</td>

			<td width="20%">
			<p style="display: block;text-align: left;font:normal 13px arial;color: #333;margin: 0;padding: 0;">
			<?php if($details["status"] == 1)
			{ 
				echo __('pending');
			}
			else if($details["status"] == 2)
			{ 
				echo __('approved'); 
			}
			else 
			{ 
				echo __('rejected'); 
			} ?>
			</p>
			</td>	
			<td width="20%">
			<p style="display: block;text-align: left;font:normal 13px arial;color: #333;margin: 0;padding: 0;">
			<?php 
			if($details["pay_status"] == 1) 
			{
				echo __('success');
			}
			else if($details["pay_status"] == 2)
			{
				echo __('failed');
			}
			else
			{
			        echo __('fundstatus');
			}
			?>printdetails
			</p>
			</td>

			</tr>
			<?php } ?>

			<tr>
			<td valign="middle" width="10"></td>					
			<td>
			<p style="display: block;text-align: left;font:normal 13px arial;color: #333;margin: 0;padding: 0;"> </p>
			</td>
			<td>
			<p style="display: block;text-align: left;font:normal 13px arial;color: #333;margin: 0;padding: 0;"></p>
			</td>
			<td valign="middle" width="250">
			<table border="0" cellpadding="0" cellspacing="0">
			</table>
			</td>
			<td valign="middle" width="30"></td>
			</tr>
			<tr height="10"><td></td></tr>
			</tbody></table>
		</td>
	</tr>    
	<!-- INFO DETAILS -->
	 

                           <!-- SUPPORT DETAILS-->
                    <tr>
                        <td valign="top"><table width="705" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #d1d1d1;">
                                <tbody><tr height="10"><td colspan="3"></td></tr>
                                <tr><td width="15" valign="top"></td><td width="670" valign="top"><p style="text-align: center;font:normal 10px arial;color: #333;margin: 0;padding: 0;"><b style="font:bold 11px arial;color:#333;text-align:center;"><?php echo SITE_NAME.' '.SITE_COPYRIGHT; ?></p>
                                    </td><td width="15" valign="top"></td></tr>
                                <tr height="10"><td colspan="3"></td></tr>
                            </tbody></table>
                        </td>
                    </tr>
				
                 </tbody></table>

  
<?php     exit;   

	}
	public function action_active_driver_report()
	{
		$find_url = explode('/',$_SERVER['REQUEST_URI']);  
		$split = explode('?',$find_url[2]);  	
		$list = $split[0];
		
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype =='C')
		{
			$this->request->redirect("company/login");
		}
		
		if($usertype =='M')
		{
			$this->request->redirect("manager/login");
		}

		$company = isset($_REQUEST['filter_company'])?trim(Html::chars($_REQUEST['filter_company'])):'';
		$startdate = isset($_REQUEST['startdate'])?trim(Html::chars($_REQUEST['startdate'])):'';
		$enddate = isset($_REQUEST['enddate'])?trim(Html::chars($_REQUEST['enddate'])):'';
		$taxiid = isset($_REQUEST['taxiid'])?trim(Html::chars($_REQUEST['taxiid'])):'';
		$driver_id = isset($_REQUEST['driver_id'])?trim(Html::chars($_REQUEST['driver_id'])):'';
		$manager_id = isset($_REQUEST['manager_id'])?trim(Html::chars($_REQUEST['manager_id'])):'';
		$passengerid = isset($_REQUEST['passengerid'])?trim(Html::chars($_REQUEST['passengerid'])):'';


		$admin_model = Model::factory('admin');
		$transaction_model = Model::factory('transaction');

		$get_allcompany = $transaction_model->get_allcompany();
		$taxilist = $transaction_model->gettaxidetails($company,$manager_id);
		$passengerlist = $transaction_model->getpassengerdetails($company,'');
		$driverlist = $transaction_model->getdriverdetails($company,$manager_id);
		$managerlist = $transaction_model->getmanagerdetails($company);

		$page_title = __("active_driver_report");
		$list = 'all';

		$count_active_driverlist = $admin_model->count_active_driverlist('','','','','','','');

		//pagination loads here
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
		'current_page'   => array('source' => 'query_string','key' => 'page'),
		'items_per_page' => REC_PER_PAGE,
		'total_items'    => $count_active_driverlist,
		'view' => 'pagination/punbb', 
		));
   
		$active_driverlist = $admin_model->active_driverlist_details('','','','','','','',$offset, REC_PER_PAGE);

		//****pagination ends here***//

		//send data to view file 
		
		$view = View::factory('admin/activer_drivers')

				->bind('Offset',$offset)
				->bind('action',$action)
				->bind('srch',$_REQUEST)
				->bind('pag_data',$pag_data)
				->bind('taxilist',$taxilist)
				->bind('driverlist',$driverlist)
				->bind('managerlist',$managerlist)
				->bind('passengerlist',$passengerlist)
				->bind('active_driverlist',$active_driverlist)
				->bind('get_allcompany',$get_allcompany)
				->bind('grpahdata',$grpahdata)
				->bind('id',$id);
		
		$this->page_title = $page_title;
		$this->template->title= $page_title." | ".SITENAME;
		$this->template->page_title= $page_title; 
		$this->template->content = $view;		
	}


	public function action_active_driver_search()
	{
		$find_url = explode('/',$_SERVER['REQUEST_URI']);  
		$split = explode('?',$find_url[2]);  	
		$list = $split[0];
		
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype =='C')
		{
			$this->request->redirect("company/login");
		}
		
		if($usertype =='M')
		{
			$this->request->redirect("manager/login");
		}

		$company = isset($_REQUEST['filter_company'])?trim(Html::chars($_REQUEST['filter_company'])):'';
		$startdate = isset($_REQUEST['startdate'])?trim(Html::chars($_REQUEST['startdate'])):'';
		$enddate = isset($_REQUEST['enddate'])?trim(Html::chars($_REQUEST['enddate'])):'';
		$taxiid = isset($_REQUEST['taxiid'])?trim(Html::chars($_REQUEST['taxiid'])):'';
		$driver_id = isset($_REQUEST['driver_id'])?trim(Html::chars($_REQUEST['driver_id'])):'';
		$manager_id = isset($_REQUEST['manager_id'])?trim(Html::chars($_REQUEST['manager_id'])):'';
		$passengerid = isset($_REQUEST['passengerid'])?trim(Html::chars($_REQUEST['passengerid'])):'';

		$admin_model = Model::factory('admin');
		$transaction_model = Model::factory('transaction');

		$get_allcompany = $transaction_model->get_allcompany();
		$taxilist = $transaction_model->gettaxidetails($company,$manager_id);
		$passengerlist = $transaction_model->getpassengerdetails($company,'');
		$driverlist = $transaction_model->getdriverdetails($company,$manager_id);
		$managerlist = $transaction_model->getmanagerdetails($company);


		$page_title = __("active_driver_report");
		$list = 'all';

		$count_activedriver_search = $admin_model->count_active_driverlist($company,$manager_id,$taxiid,$driver_id,$passengerid,$startdate,$enddate);


		//pagination loads here
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
		'current_page'   => array('source' => 'query_string','key' => 'page'),
		'items_per_page' => REC_PER_PAGE,
		'total_items'    => $count_activedriver_search,
		'view' => 'pagination/punbb', 
		));
   
		$active_driverlist = $admin_model->active_driverlist_details($company,$manager_id,$taxiid,$driver_id,$passengerid,$startdate,$enddate,$offset, REC_PER_PAGE);

		//****pagination ends here***//

		//send data to view file 
		
		$view = View::factory('admin/activer_drivers')

				->bind('Offset',$offset)
				->bind('action',$action)
				->bind('srch',$_REQUEST)
				->bind('pag_data',$pag_data)
				->bind('taxilist',$taxilist)
				->bind('driverlist',$driverlist)
				->bind('managerlist',$managerlist)
				->bind('passengerlist',$passengerlist)
				->bind('active_driverlist',$active_driverlist)
				->bind('get_allcompany',$get_allcompany)
				->bind('grpahdata',$grpahdata)
				->bind('id',$id);
		
		$this->page_title = $page_title;
		$this->template->title= $page_title." | ".SITENAME;
		$this->template->page_title= $page_title; 
		$this->template->content = $view;		
	}
	// Generate PDF *******************/
	//http://192.168.1.88:1010/admin/exportpdf/?filter_company=All&startdate=2014-03-01 00:00:00&enddate=2014-04-05 17:37:40&taxiid=All&driver_id=All&type_export=0
	public function action_exportpdf()
	{

		$manage = Model::factory('manage');
		$driver = Model::factory('driver');
		$passengers = Model::factory('passengers');
		$admin_model = Model::factory('admin');
		
		$startdate = $_GET['startdate'];
		$enddate = $_GET['enddate'];
		$driver_id = $_GET['driver_id'];
		$filter_company = $_GET['filter_company'];
		$type = $_GET['type_export'];
		$taxiid = $_GET['taxiid'];


			$passengers_details = $passengers->get_passenger_profile_details($driver_id);
			//print_r($passengers_details);
			//exit;
			$company_logs_completed_transaction = $admin_model->active_driverlist_details($filter_company,'',$taxiid,$driver_id,'',$startdate,$enddate,0, REC_PER_PAGE);
			//print_r($company_logs_completed_transaction);exit;
		$Middle_html="";
		//echo count($driver_logs_completed_transaction);		
			//require Kohana::find_file('vendor', 'pdf/config/lang/eng.php');//
			//require Kohana::find_file('vendor', 'pdf/tcpdf.php');.
			//$logo = 'http://192.168.1.88:1235/public/uploads/site_logo/redtaxi.png';
			$Endhtml="";
			$Tophtml = '
			<style>
	h1 {
		color: navy;
		font-family: times;
		font-size: 24pt;
	}
	p.first {
		color: #003300;
		font-family: helvetica;
		font-size: 12pt;
	}
	p.first span {
		color: #006600;
		font-style: italic;
	}
	p#second {
		color: rgb(00,63,127);
		font-family: times;
		font-size: 12pt;
		text-align: justify;
	}
	
	p#second > span {
		
	}
	table.first {
		color: #003300;
		font-family: helvetica;
		font-size: 8pt;
		background-color:#FFF; 
		border:10px solid #236B8D;
		
		
	}
	td {
		font-weight:bold;
		font:bold 12pt arial; color:#000000;
		
	}
	.invoice_head{text-align: right;color:#000000;}
	.head_border{border-bottom:1px solid #2c2c2c;}
	.totalstyle{font-weight:bold; font:bold 12pt arial; color:#ffffff; background-color:#2c2c2c; text-align:left; width:auto}
	.taxstyle{font-weight:bold; font:bold 12pt arial; color:#000000;  text-align:left;}
	</style>
	<table border="0" cellpadding="1" cellspacing="1">
 <tr>
   <td></td>
   <td><div class="invoice_head">'.date("F j, Y").'<h1>INVOICE</h1></div></td>
 </tr>
  <tr>';

  $Tophtml .='<td align="left">'.$filter_company.'</td>
  <td align="right">'.$filter_company.'</td>';

$Tophtml .= '</tr>';

 $Tophtml .= '</table>';
            
            $driver_name_lable = __('driver_name');
			$taxi_lable = __('taxi_no');
			$companyname_lable = __('companyname');
			$trip_count_lable = __('trip_count');

			$at_lable = __('at');
											
			$Middle_html .= '<table border="0" cellpadding="1" cellspacing="1" width="700">
						<tr>
							<td class="head_border" width=10>#</td>
							<td class="head_border" width=130><b>'.$taxi_lable.'</b></td>
							<td class="head_border" width=350><b>'.$driver_name_lable.'</b></td>
							<td class="head_border" width=130><b>'.$companyname_lable.'</b></td>
							<td class="head_border" width=30><b>'.$trip_count_lable.'</b></td>		
						
						</tr>';
						$i=1;
						($i%2 == 1)?$class="eventr":$class="oddtr";
							$rowdatas="";
							$tax_total="";
							$fare_total="";
							foreach($company_logs_completed_transaction as $values)
							{
								
								$driver_id = $values['driver_id'];
								$taxi_no = $values['taxi_no'];
								$company_name = $values['company_name'];
								$trip_count = $values['trip_count'];
								$driver_name = $values['driver_name'];
								//$pickup_time = date('d/m/Y',strtotime($values->pickup_time)).' '.$at_lable.'<br>'.date('h:i:s A', strtotime($values->pickup_time));
								
								if($_SESSION['company_id'] != 0)
								{
									$company_currency = findcompany_currencyformat($_SESSION['company_id']);
								}
								else
								{
									$company_currency = findcompany_currencyformat($values['company_id']);
								}
								
							
							$Middle_html .= '<tr>	
								<td width=10>'.$i.'</td>
								<td width=130>'.$taxi_no.'</td>
								<td width=350>'.$driver_name.'</td>
								<td width=130>'.$company_name.'</td>
								<td width=30>'.$trip_count.'</td>
								</tr>';
								$i = $i+1;
					}	
			$Middle_html .= 	'';	
			$Middle_html .=	"</table>";					
			$Endhtml .=	'<table border="0" cellpadding="1" cellspacing="1" width="700"><tr>	
								<td width=10></td>
								<td width=130></td>
								<td width=350></td>
								<td width=130></td>
								<td width=30></td>
								<td width=30>Total</td>
								<td width=10 class="taxstyle" height="25">'.$tax_total.'</td>
								<td width=10 class="totalstyle" height="25">'.CURRENCY.$fare_total.'</td>															
								</tr></table>';
						
			$html = $Tophtml.$Middle_html.$Endhtml;//
			$html = preg_replace("<tbody>"," ",$html);
			$html = preg_replace("</tbody>"," ",$html);
			echo '<pre>';
			echo $html;
			echo '</pre>';
			exit;
			ob_clean();			
			$filename = __('INVOICE').'-'.$driver_name.'-'.date('m-d-y-s');
			if($type == __('gen_pdf'))
			{
			$generate_pdf = $manage->generate_pdf($html,$filename);
			}
			else
			{
			$filepath = $_SERVER['DOCUMENT_ROOT']."/public/".UPLOADS."/driver_invoice/".$filename;
			$generate_pdf = $manage->send_pdf($html,$driver_name,$email,$filepath);
			if($generate_pdf ==1)
			{
                    $mail="";			                  							
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$driver_name,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver_invoice.html',$replace_variables);

					$to = $email;//'senthilkumar.a@ndot.in';//
					$from = $this->siteemail;
					$subject = __('driver_invoice').' '.$this->app_name;						
					$redirect = "manage/driverinfo/".$driver_id;	
					 $attachment = $filepath.'.pdf';
					$mail_model = Model::factory('add');
					$smtp_result = $mail_model->smtp_settings();
					if(!empty($smtp_result) && ($smtp_result[0]['smtp'] == 1))
					{
						include($_SERVER['DOCUMENT_ROOT']."/modules/SMTP/smtp.php");
					}
					else
					{
						$email_from = 'maheswaran.r@ndot.in'; // Who the email is from
						$email_subject = 'driver invoice'; // The Subject of the email
						$email_message = 'driver invoice';

						$email_to = 'senthilkumar.a@ndot.in'; // Who the email is to

						$headers = "From: ".$email_from;

						$semi_rand = md5(time());
						$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

						$headers .= "\nMIME-Version: 1.0\n" .
						"Content-Type: multipart/mixed;\n" .
						" boundary=\"{$mime_boundary}\"";

						$email_message .= "This is a multi-part message in MIME format.\n\n" .
						"--{$mime_boundary}\n" .
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
						"Content-Transfer-Encoding: 7bit\n\n" .
						$email_message .= "\n\n";

						$fileatt = $filename; // Path to the file

						$fileatt_type = "application/pdf"; // File Type
						//$fileatt_name = 'voucher'.$id.".pdf"; // Filename that will be used for the file as the attachment

						$file = fopen($fileatt,'rb');
						$data = fread($file,filesize($fileatt));
						fclose($file);
						$data = chunk_split(base64_encode($data));
						$fileatt_name = 'voucher'.md5(time()).".pdf";

						$email_message .= "--{$mime_boundary}\n" .
						"Content-Type: {$fileatt_type};\n" .
						" name=\"{$fileatt_name}\"\n" .
						//"Content-Disposition: attachment;\n" .
						//" filename=\"{$fileatt_name}\"\n" .
						"Content-Transfer-Encoding: base64\n\n" .
						$data .= "\n\n" ;

						$email_message .= "--{$mime_boundary}\n" .
						$ok = @mail($email_to, $email_subject, $email_message, $headers);	
						// To send HTML mail, the Content-type header must be set      
					}   
						Message::success(__('invoice_send'));
						$this->request->redirect(URL_BASE.$redirect);
				}
				else
				{
					$this->request->redirect("manage/driver/".$driver_id."");
				} 			
			}
         	}
/****************************************************************************/         	
	public function action_calendarwise_report()
	{
		$find_url = explode('/',$_SERVER['REQUEST_URI']);  		

		$split = explode('?',$find_url[3]);  	

		$list = $split[0];

		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype =='C')
		{
			$this->request->redirect("company/login");
		}
		
		if($usertype =='M')
		{
			$this->request->redirect("manager/login");
		}
		$admin_model = Model::factory('admin');
		$manage_transaction = Model::factory('transaction');
		$common_model = Model::factory('commonmodel');

		$list = 'all';

		$page_title = __('calendarwise_report');

		$startdate = isset($_REQUEST['all_dates'])?$_REQUEST['all_dates']:'';
		$get_allcompany = $manage_transaction->get_allcompany_tranaction($usertype);
		$taxilist = $manage_transaction->gettaxidetails('','');
		$passengerlist = $manage_transaction->getpassengerdetails('','');
		$driverlist = $manage_transaction->getdriverdetails('','');
		$managerlist = $manage_transaction->getmanagerdetails('');
		$count_transaction_list = $admin_model->count_calendarwise_translist($list,'All','All','All','All','All',$startdate,'','','');
		$grpahdata = $admin_model->getgraphvalues_calcendarwise($list,'All','All','All','All','',$startdate,'','','');
		$gateway_details = $common_model->gateway_details();

		$package_details = $common_model->package_details();

		//pagination loads here
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
		'current_page'   => array('source' => 'query_string','key' => 'page'),
		'items_per_page' => REC_PER_PAGE,
		'total_items'    => $count_transaction_list,
		'view' => 'pagination/punbb', 
		));
   

		$all_transaction_list = $admin_model->calcendarwise_details($list,'All','All','All','All','All',$startdate,'',$offset, REC_PER_PAGE,'','');
		//****pagination ends here***//

		//send data to view file 
		
		$view = View::factory('admin/calcendarwise_report')
				->bind('Offset',$offset)
				->bind('action',$action)
				->bind('srch',$_REQUEST)
				->bind('pag_data',$pag_data)
				->bind('all_transaction_list',$all_transaction_list)
				->bind('taxilist',$taxilist)
				->bind('driverlist',$driverlist)
				->bind('managerlist',$managerlist)
				->bind('passengerlist',$passengerlist)
				->bind('get_allcompany',$get_allcompany)
				->bind('gateway_details',$gateway_details)
				->bind('package_details',$package_details)
				->bind('grpahdata',$grpahdata)
				->bind('id',$id);
		
		$this->page_title = $page_title;
		$this->template->title= $page_title." | ".SITENAME;
		$this->template->page_title= $page_title; 
		$this->template->content = $view;
	}
	
	public function action_enable_template()
	{
		$admin_model = Model::factory('admin');
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype !='A' && $usertype !='S')
		{
			$this->request->redirect("admin/dashboard");
		}
		$this->is_login(); 
		$manage = Model::factory('manage');
		$status = $admin_model->enable_template($_REQUEST['uniqueId'],$_REQUEST['status']);
		$pagedata = explode("/",$_SERVER["REQUEST_URI"]);
		$page = isset($pagedata[3])?$pagedata[3]:'';

		//Flash message for Reject
		//==========================
		$statusMessage = ($_REQUEST['status'] == 0) ? __("enable") : __("disable");
		if($status == 1)
		{
			Message::success(__('Checked requests have been changed to '.strtolower($statusMessage).' status.'));
		}
		else
		{
			Message::error(__('Problem in update'));
		}
		$this->request->redirect($_SERVER['HTTP_REFERER']);
	}

	public function action_withdraw_payment_mode()
	{ 
		$this->is_login(); 
		$usertype = $_SESSION['user_type'];
		if($usertype !='A' ){
			$this->request->redirect("admin/login");
		}
		$settings = Model::factory('admin');
		$count = $settings->count_withdraw_payment_mode();

		//pagination loads here
		$page_no= isset($_GET['page'])?$_GET['page']:0;
		if($page_no == 0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
			'current_page'   => array('source' => 'query_string','key' => 'page'),
			'items_per_page' => REC_PER_PAGE,
			'total_items'    => $count,
			'view' => 'pagination/punbb',
		));

		$withdraw_payment_mode = $settings->get_withdraw_payment_mode($offset, REC_PER_PAGE);
		$this->selected_page_title = __("site_settings");
		$view = View::factory('admin/manage_withdraw_payment_mode')
				->bind('pag_data',$pag_data)
				->bind('withdraw_payment_mode', $withdraw_payment_mode)
				->bind('count', $count)
				->bind('Offset',$offset);
		$this->template->title= SITENAME." | ".__('Withdraw Payment Mode');
		$this->template->page_title= __('Withdraw Payment Mode');
		$this->template->content = $view;
	}
	
	public function action_setMenuEnable()
	{
		if($_GET) {
			$this->session->set('left_menu_enable',$_GET["data"]);
		}
		exit;
	}
	
	public function action_secondary_login()
	{
		$siteInfo = $this->siteinfo;
		
		$admin_secret_key = $siteInfo[0]['admin_secret_key'];
		$session_timestamp = (isset($_SESSION['session_timestamp']) && $_SESSION['session_timestamp'] !="")?$_SESSION['session_timestamp']:"";
		
		$redirect_url =(isset($_GET['redirect_url']) && $_GET['redirect_url'] !="")?$_GET['redirect_url']:"";
		$current_time = convert_timezone('now',TIMEZONE);
		$current_timestamp = strtotime($current_time);
		$seconds_diff = $current_timestamp - $session_timestamp;                          
		$time = ($seconds_diff/3600);
		$redirect="";
		if(isset($_SESSION['admin_secret_key']) && $_SESSION['admin_secret_key']==$admin_secret_key && $session_timestamp && $time <= 0.5)
		{
			$redirect = (isset($_GET['redirect']) && $_GET['redirect'] !="")?$_GET['redirect']:"";
			if($redirect  !="")
			{
				$this->request->redirect($redirect);
			}
			else
			{
				$this->request->redirect("admin/dashboard");
			}
		}
	
		$settings = Model::factory('admin');
		$signup_submit =arr::get($_REQUEST,'secondary_admin_login');
		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			$validator = $settings->validate_secondary_login(arr::extract($_POST,array('password')));
			
			if($validator->check())
			{
				$redirect = (isset($_GET['redirect']) && $_GET['redirect'] !="")?$_GET['redirect']:"";
				if($redirect !="")
				{
					$url = $redirect;
				}
				else
				{
					$url = "admin/dashboard";
				}

				if($admin_secret_key == $post_values['password'])
				{
					
					$this->session->set("session_timestamp",$current_timestamp);
					$this->session->set("admin_secret_key",$_POST['password']);
					Message::success(__('Success'));
					$this->request->redirect($url);
				}
				else
				{
					Message::error(__('Please enter valid verification password'));
					$this->request->redirect("admin/secondary_login?redirect=".$url); 
				}
				
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
		/*if(isset($_POST['admin_secret_key']) && $_POST['admin_secret_key']!= $admin_secret_key || $time >= 0.5)
		{
			$this->session->set("session_timestamp",$current_timestamp);
			//$this->session->set("admin_secret_key",$_POST['admin_secret_key']);
			
			//print_r(1); exit;
			
		}
		else
		{
			unset($_SESSION['session_timestamp']);
			unset($_SESSION['admin_secret_key']);
			//print_r(2); exit;
		} */
		
		//1494928164
		//1494928100
		//1494924500
		//$current_time =
		
		$view = View::factory('admin/secondary_login')
				->bind('errors', $errors)
				->bind('postvalue',$post_values)
				->bind('title',$title);
				
	    $this->template->title = SITENAME.' '.__('Password Verification');
		$this->template->page_title=__('Password Verification');
		$this->template->content = $view;
		
	}
	
	
} // End siteadmin class
