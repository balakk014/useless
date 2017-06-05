<?php defined('SYSPATH') or die('No direct script access.');
/****************************************************************

* Contains driver details

* @Package: ConnectTaxi

* @Author:  NDOT Team

* @URL : http://www.ndot.in

********************************************************************/
Class Controller_Driver extends Controller_Website
{
	/**
	****__construct()****
	* Common Function in this controller
	*/
	public function __construct(Request $request,Response $response)
	{
		parent::__construct($request,$response);
		//$this->currentdate=Commonfunction::getCurrentTimeStamp();	
		$Commonmodel = Model::factory('Commonmodel');
		$this->currentdate = $Commonmodel->getcompany_all_currenttimestamp(COMPANY_CID);		
		/**To Set Errors Null to avoid error if not set in view**/              
		$this->session = Session::instance();
		$user_error=0;			
		
		$managemodel = Model::factory('managemodel');
		//Currency Settings from admin End
		$siteinfo=$managemodel->select_site_settings();
		View::bind_global('siteinfo', $siteinfo);
			
        $this->session->set('siteinfo',$siteinfo);
			
	/*			
        $this->paypal_db = Model::factory('paypal');	
        $this->paypalconfig = $this->paypal_db->getpaypalconfig(); 
        $this->paypal_currencysymbol = count($this->paypalconfig) > 0?$this->paypalconfig[0]['currency_symbol']:'';
        $this->paypal_currencycode = count($this->paypalconfig) > 0?$this->paypalconfig[0]['currency_code']:'';
	*/		
			
			
        View::bind_global('balance',$bal);			
			
		$driver = Model::factory('siteusers');				
		$this->template=USERVIEW."template";		
	}
	
 
	public function action_index()
	{
		$id =$this->session->get('id'); 
		//$userid = $this->session->get('userid');
		$usrid = $id; //isset($userid)?$userid:$id;
	    $radius = Arr::get($_REQUEST,"rad");
		$driver = Model::factory('siteusers');		
		$get_logged_user_details = $driver->get_logged_user_details($usrid);		
	   	
		if(!isset($usrid)){
				
			$view=View::factory(USERVIEW.'home');		
	        $this->template->meta_desc = $this->meta_description;
            $this->template->meta_keywords = $this->meta_keywords;
            $this->template->title =$this->title;

	        $this->template->content=$view;	     
	
		}else{
	  	         $this->request->redirect('users/profile');
		}
	}

	/**
	 *****action_login()****
	 *@ User Login
	 */	      		
	public function action_login()
	{	
			$id =$this->session->get('id');
			//$userid = $this->session->get('userid');
			$usrid =$id; // isset($userid)?$userid:$id;

			/**To Set Errors Null to avoid error if not set in view**/              
			$this->session = Session::instance();

			$driver = Model::factory('driver');

			/**Check if session set or not and if set it should not show login page**/
			if(!isset($usrid))		
			{	
			    $errors = array();			

			    /**To get the form submit button name**/
			    $login_submit =arr::get($_REQUEST,'submit_login'); 
			
				$phone = mysql_real_escape_string($_GET['phone']);
				$phone_exist = $this->check_phone_exist($phone);
				$password = mysql_real_escape_string($_GET['password']);
				$remember = mysql_real_escape_string($_GET['remember']);
			/*	if(!is_numeric($phone)){
					echo '3';
					exit;
				}else{*/
					if($phone_exist == 1)
					{
							$result=$driver->login($phone,$password,$remember);
							if($result==1){
								Message::success(__('succesful_login_flash').COMPANY_SITENAME);
								echo $result;
								exit;
							}
							else
							{
								echo $result;
								exit;
							}						
					}
					else
					{
						echo '2';
						exit;
					}
				//}

			}else{
				$this->request->redirect('driver/dashboard');
			}
			$this->template->meta_desc = $this->meta_description;
			$this->template->meta_keywords = $this->meta_keywords;
			$this->template->title =$this->title;
		}
/* Pop up Email Check */
	public function check_email_exist()
	{
		//$_GET['email'];
		$drivers = Model::factory('driver');
		$email_exist = $drivers->check_email_driver($_GET['email']);
		return  $email_exist;
	}

	public function check_phone_exist()
	{
		$siteusers = Model::factory('driver');
		$phone_exist = $siteusers->check_phone_passengers($_GET['phone']);
		return  $phone_exist;
	
	}
		
	//DashoBoard Settings Function
	public function action_dashboard()
	{
		$errors = array();	
		$this->is_login(); $this->is_login_status(); 		
		//$this->is_login(); 	
		/*** Dashboard Style & Scripts ***/
		$dashstyles = array(CSSPATH.'dashboard/bootstrap-responsive.css' =>'screen',
							CSSPATH.'dashboard/charisma-app.css' =>'screen',
							CSSPATH.'dashboard/jquery-ui-1.8.21.custom.css' =>'screen',
							CSSPATH.'dashboard/chosen.css' =>'screen',
							CSSPATH.'dashboard/uniform.default.css' =>'screen',
							CSSPATH.'dashboard/jquery.noty.css' =>'screen',
							CSSPATH.'dashboard/noty_theme_default.css' =>'screen',
							CSSPATH.'dashboard/jquery.iphone.toggle.css' =>'screen',
							CSSPATH.'dashboard/opa-icons.css' =>'screen',
							CSSPATH.'datepicker.css'=>'screen');
							
		$dashscripts = array(SCRIPTPATH.'dashboard/jquery-ui-1.8.21.custom.min.js',
							SCRIPTPATH.'dashboard/bootstrap-transition.js',
							SCRIPTPATH.'dashboard/bootstrap-alert.js',
							SCRIPTPATH.'dashboard/bootstrap-modal.js',
							SCRIPTPATH.'dashboard/bootstrap-dropdown.js',
							SCRIPTPATH.'dashboard/bootstrap-scrollspy.js',
							SCRIPTPATH.'dashboard/bootstrap-tab.js',
							SCRIPTPATH.'dashboard/bootstrap-tooltip.js',
							SCRIPTPATH.'dashboard/bootstrap-popover.js',
							SCRIPTPATH.'dashboard/bootstrap-button.js',
							SCRIPTPATH.'dashboard/bootstrap-collapse.js',
							SCRIPTPATH.'dashboard/bootstrap-tour.js',
							SCRIPTPATH.'dashboard/jquery.cookie.js',
							SCRIPTPATH.'dashboard/jquery.dataTables.min.js',
							SCRIPTPATH.'dashboard/jquery.chosen.min.js',
							SCRIPTPATH.'dashboard/jquery.noty.js',
							SCRIPTPATH.'dashboard/jquery.iphone.toggle.js',
							SCRIPTPATH.'dashboard/jquery.history.js',
							SCRIPTPATH.'dashboard/charisma.js',
							SCRIPTPATH.'highcharts.js',
							SCRIPTPATH.'bootstrap-datepicker.js',
							SCRIPTPATH.'kkcountdown.js',
							SCRIPTPATH.'jquery-countdown.js',
									);		
		$driver_model = Model::factory('driver');			
		$Commonmodel = Model::factory('Commonmodel');			
		$driver_id =$this->session->get('id');
		$id =$driver_id;
		//echo COMPANY_CID;
		$assignedtaxi_list = $driver_model->get_assignedtaxi_alllist($driver_id,COMPANY_CID);
		$driver_logs = "";//$driver_model->get_driver_logs($driver_id,'R','A','1',COMPANY_CID,REC_PER_PAGE);
		//exit;
		//Getting Unread Count
		$driver_logs_new = $driver_model->get_driver_logs($driver_id,'U','','0',COMPANY_CID);

		$today_driver_logs_completed = $driver_model->get_driver_logs($driver_id,'R','A','1',COMPANY_CID);
				
		//Getting In Progress Data
		$driver_logs_progress = $driver_model->get_driver_logs($driver_id,'R','A','2',COMPANY_CID);		
		//print_r($driver_logs_progress);
		//Getting Up Coming Data
		$driver_logs_upcoming = $driver_model->get_driver_logs($driver_id,'R','A','9',COMPANY_CID);		
		// print_r($driver_logs_upcoming);
		
		//Getting Today Rejected Count
		$driver_logs_today_rejected = count($driver_model->get_driver_logs($driver_id,'R','R','0',COMPANY_CID));
		//Getting all Completed driver logs
		$driver_logs_completed = $driver_model->get_driver_logs_completed($driver_id,'R','A','1');		
		//Getting all Completed driver logs
		$driver_logs_completed_transaction = $driver_model->get_driver_logs_completed_transaction($driver_id,'R','A','1',REC_PER_PAGE,'0');		
		//Getting all rejected driver logs
		$driver_logs_rejected = $driver_model->get_driver_logs_completed($driver_id,'R','R','0');		
		//Get Drivers comments
		$driver_comments = $driver_model->get_driver_comments($driver_id,1,COMPANY_CID);		
		$get_transaction = $driver_model->get_trans_of_driver($driver_id,REC_PER_PAGE);	
		
		$driver_earnings = $driver_model->get_driver_earnings($driver_id);
		
		$driver_shift_status = "";//$Commonmodel->get_driver_current_shift_status($driver_id);		
	
        $name =$this->session->get('name');
        
   		$view= View::factory(USERVIEW.'/driver/dashboard')
					        ->bind('name', $name)
					        ->bind('driver_logs', $driver_logs)
					        ->bind('assignedtaxi_list', $assignedtaxi_list)
					        ->bind('driver_logs_new', $driver_logs_new)
					        ->bind('today_driver_logs_completed',$today_driver_logs_completed)
					        ->bind('driver_logs_rejected',$driver_logs_rejected)
					        ->bind('dashstyles',$dashstyles)
					        ->bind('dashscripts',$dashscripts)
					        ->bind('driver_logs_progress',$driver_logs_progress)
					        ->bind('driver_comments',$driver_comments)
					        ->bind('get_driver_logs_completed',$driver_logs_completed)
					        ->bind('driver_logs_completed_transaction',$driver_logs_completed_transaction)
					        ->bind('get_transaction',$get_transaction)
					        ->bind('driver_logs_upcoming',$driver_logs_upcoming)
					        ->bind('availablity',$availablity)
					        ->bind('driver_earnings',$driver_earnings)
					        ->bind('driver_shift_status',$driver_shift_status)
					        ->bind('id', $driver_id);
	    $this->template->content = $view;
	    $this->template->meta_desc = $this->meta_description;
        $this->template->meta_keywords = $this->meta_keywords;
        $this->template->title =$this->title;
	}

     
	/** Dashboard View Rating ***/
	public function action_viewrate()
	{
		$rating = mysql_real_escape_string($_GET['rating']);
		$output="";
		for($k=1;$k<=$rating;$k++)
				{
					$output .= '<div class="rating_enb" id="'.$k.'">&nbsp;</div>';
				}
				$output .= '<div class="rating_value">'.$rating.' / 5</div>';
				echo $output;
				exit;
	}
	//DashoBoard Settings Function for Ajax Load
	public function action_dashboard_ajax()
	{
		if(isset($_POST['limit']))
		{
						
			$driver_model = Model::factory('driver');		
			$driver_id =$this->session->get('id');			
			
			$driver_logs = $driver_model->get_driver_logs($driver_id,'R','A',REC_PER_PAGE,$_POST['limit']);	
			
			$html1 = '';
			
			foreach ($driver_logs as $driver) 
			{ 
				$comments = ($driver->comments == null)?"No Comments":$driver->comments;
				$status = ($driver->travel_status == 1)?"Completed":"Not Completed";
				$html1 = '<div class="bs-docs-example">					
							<div class="media">
								<a class="pull-left" href="javascript:" title="'.ucfirst($driver->name).'">
									<img class="media-object" data-src="holder.js/64x64" alt="64x64" style="width: 64px; height: 64px;" src='.PUBLIC_IMGPATH."/user.png".'>
								</a>
								<div class="media-body">
									<h4 class="media-heading">'.__("Passenger").' : '.ucfirst($driver->name).' - ' . __("Comments").'</h4>
										'.$comments.'
										<br/>
										<span class="rating">'.__("Rating").' : ' . $driver->rating .'</span>
									<div class="media">
										<a class="pull-left" href="javascript:" title="'.ucfirst($driver->name).'">
										<img class="media-object" data-src="holder.js/64x64" alt="64x64" style="width: 64px; height: 64px;" src='.PUBLIC_IMGPATH."/user.png".'>
										</a>
										<div class="media-body">
											<h4 class="media-heading"><?php  ?></h4>
												<dl class="dl-horizontal">
												  <dt>'.__("Current_Location").'</dt>
												  <dd>'.$driver->current_location.'</dd>
												  <dt>'.__("Drop_Location").'</dt>
												  <dd>'.$driver->drop_location.'</dd>
												  <dt>'.__("pick_up_time").'</dt>
												  <dd>'.date('H:i:s',strtotime($driver->pickup_time)).'</dd>
												  <dt>'.__("status_label").'</dt>
												  <dd>'.$status.'</dd>													 
												</dl>
												
										</div>
									</div>
								</div>
							</div>
						</div> '; 

			
			}				
			
			echo $html1;
			exit;
		}
		
	}
	
	
	/**
	 * ****action_logout()****
	 * @return auth logout action
	 */	 
	public function action_logout() 
	{
		$Commonmodel = Model::factory('Commonmodel');
		$driver_model = Model::factory('driver');
		$update_id = $this->session->get('id');
		$update_array  = array("login_status"=>"N","login_from"=>"");									
		$login_status_update = $Commonmodel->update(PEOPLE,$update_array,'id',$update_id);		//destroy session while logout
		/*** Update in Driver table **/
		$driver_reply = $driver_model->update_driver_shift_status($update_id,'0');
		/** Update in driver shift history table **/
		$update_arrary  = array("shift_end" => $this->currentdate);
		$inserid = $this->session->get("shiftstatus_id");		
		if($inserid)
		{
		$transaction = $Commonmodel->update(DRIVERSHIFTSERVICE,$update_arrary,'driver_shift_id',$inserid);
		}
		$this->session->set("shiftstatus_id","");
		$this->session->destroy();
		// Sign out the user and redirects to login page
		//Auth::instance()->logout();
		
		$this->session = Session::instance();
		Message::success(__('succesful_logout_flash').COMPANY_SITENAME);		
		$this->request->redirect("/");
		
	}

	/**
	 * ****action_forgot_password()****
	 * @return forgot_password action
	 */	
	 	
	public function action_forgotpassword()
	{

		    $driver = Model::factory('driver');
		     /**To Set Errors Null to avoid error if not set in view**/              
		    $errors = array();
		    $view=View::factory(USERVIEW.'driver/forgot_password')
				    ->bind('validator', $validator)
				    ->bind('errors', $errors)
				    ->bind('success',$success)
				    ->bind('email_error',$email_error);
		
		    /**To generate random key if user enter email at forgot password**/
		    $random_key = text::random($type = 'alnum', $length = 7);

		     /**To get the form submit button name**/
		    $change_pwd_submit =arr::get($_REQUEST,'submit_forgot_password'); 

		    if ($change_pwd_submit && Validation::factory($_POST) ) 
		    {
			    $validator = $driver->validate_forgotpwd(arr::extract($_POST,array('email')));
			    if ($validator->check()) 
			    {
				    $email_exist = $driver->check_email_driver($_POST['email']);
				    if($email_exist == 1)
				    { 				
					       $result=$driver->forgot_password($validator,$_POST,$random_key);
						    if($result)
						    { 						   						
                                $mail="";		
								$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_USERNAME=>ucfirst($result[0]['name']),REPLACE_MOBILE=>$result[0]['phone'],REPLACE_PASSWORD=>$random_key,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE,SITE_DESCRIPTION=>$this->app_description,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
						    $message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'user-forgotpassword.html',$replace_variables);


					$to = $_POST['email'];
					$from = CONTACT_EMAIL;
					$subject = __('forgot_password_subject')." - ".COMPANY_SITENAME;	
					$redirect = "";	
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


					//free sms url with the arguments
					if(SMS == 1)
					{
					$common_model = Model::factory('commonmodel');	
					$message_details = $common_model->sms_message('2');
					$to = $result[0]['phone'];
					$message = $message_details[0]['sms_description'];
				        $message = str_replace("##PASSWORD##",$random_key,$message);
			
					//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
					}

							    Message::success(__('sucessful_forgot_password'));
							    $this->request->redirect("/");
							
						    }	
						    else
						    {
							     Message::error(__('Try Again Later'));
							    $this->request->redirect("/");
						    }
				    }
				    else
				    {
					    $email_error=__("email_not_exist");	
				    }
			    $validator = null;
			    }
			    else 
			    {
				    //validation failed, get errors
				    $errors = $validator->errors('errors');
			    }
		    }			
	        $this->template->meta_desc = $this->meta_description;
            $this->template->meta_keywords = $this->meta_keywords;
            $this->template->title =$this->title;

			$this->template->content = $view;
    }			
		
	/**
	*****action_editprofile()****
	* @driver edit profile
	*/
	 
	public function action_editprofile()
	{ 
		$driver = Model::factory('driver');
       	/**To Set Errors Null to avoid error if not set in view**/              
		$errors = array();
		
	     /**Check Whether the user is logged in**/
		$this->is_login(); $this->is_login_status(); 		

		
		/**To get current logged user id from session**/
		$userid =$this->session->get('id'); 
		//$usrid = $this->session->get('userid');

		$id =$userid; // isset($userid)?$userid:$usrid;

		//$submit_profile =arr::get($_REQUEST,'submit_user_profile');
		$submit_profile_form2 =arr::get($_REQUEST,'signup');
		
		$_POST= Arr::map('trim', $this->request->post());    
		if ($submit_profile_form2 && Validation::factory($_POST,$_FILES) ) 
		{	
               /**Send entered values to model for validation**/      
				$validator = $driver->validate_driver_profilesettings(arr::extract($_POST,array('name','phone','address')));
							if(!empty($_FILES['profile_picture']['name'])){
								$image_name = uniqid().$_FILES['profile_picture']['name'];
								$thumb_image_name = 'thumb_'.$image_name;
								$image_type=explode('.',$image_name);
								$image_type=end($image_type);
								//$image_name=url::title($image_name).'.'.$image_type;
								$filename = Upload::save($_FILES['profile_picture'],$image_name,DOCROOT.SITE_DRIVER_IMGPATH);								
								//Image resize and crop for thumb image
								
								$logo_image = Image::factory($filename);
								$path11=DOCROOT.SITE_DRIVER_IMGPATH;
								$path1=$image_name;
								Commonfunction::imageresize($logo_image,PASS_IMG_WIDTH, PASS_IMG_HEIGHT,$path11,$image_name,90);								
								
								$path12=$thumb_image_name;
								Commonfunction::imageresize($logo_image,PASS_THUMBIMG_WIDTH, PASS_THUMBIMG_HEIGHT,$path11,$thumb_image_name,90);
								
								$status = $driver->update_driverimage($path1,$id);
							}
			   $phone_exist = $driver->check_driver_phone_update($_POST['phone'],$id);
				/**If email exists show error message**/
				if($phone_exist > 0)			
				{	
					$phone_exist= __("phone_exists");
				}
				else
				{
					if ($validator->check()) 
					{      
						
							$result=$driver->update_user_settings($validator,$_POST,$id);
							
							Message::success(__('user_success_update'));
							$this->request->redirect('/driver/dashboard');

					}
					else 
					{
						//validation failed, get errors					
						$errors = $validator->errors('errors');					
					}
				}
			
		}    
		$driver = $driver->get_driver_profile_details($id);
		$view= View::factory(USERVIEW.'driver/editprofile')
					->bind('validator', $validator)
					->bind('errors', $errors)
					->bind('user', $driver)
					->bind('phone_exist', $phone_exist)
					->bind('data',$_POST);
		//print_r($user);exit;
	    $this->template->meta_desc = $this->meta_description;
        $this->template->meta_keywords = $this->meta_keywords;
        $this->template->title =$this->title;
	    $this->template->content = $view;
	}

	/**
	*****action_change_password()****
	*@driver change password
	*/
	public function action_changepassword()
	{
		$driver = Model::factory('driver');
		/*To set errors in array if errors not set*/
		$errors = array();
		/*checks if user logged or not*/
		$this->is_login();$this->is_login_status(); 		
		//$this->session = Session::instance();
		$userid =$this->session->get('id'); 

	    $id =$userid; 
        $usrid=$id=$userid; 

		$submit_change_pass =arr::get($_REQUEST,'submit_change_pass');

			if ($submit_change_pass && Validation::factory($_POST) ) 
			{
				$userid1 =$this->session->get('id');
				//$userid2 =$this->session->get('userid');
				$userid = isset($userid1)?$userid1:'';
				$validator_changepass = $driver->validate_changepwd(arr::extract($_POST,array('old_password','new_password','confirm_password')));
				//print_r($validator_changepass);exit;
					if ($validator_changepass->check()) 
					{						
							$oldpass_check= $driver->check_pass($_POST['old_password'],$userid);
							if($_POST['old_password'] != $_POST['new_password'])
							{
									if($oldpass_check == 1)
									{
									 $result = $driver->change_password($validator_changepass,$_POST,$userid);
										if($result)
										{
										   		$mail="";
	
												//$signup_cont=$this->emailtemplate->get_template_content(USER_CHANGE_PASSWORD);
												//$subject=$signup_cont[0]['email_subject'];
												//$content=$signup_cont[0]['email_content'];	
												$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_USERNAME=>ucfirst($result[0]['name']),REPLACE_MOBILE=>$result[0]['phone'],REPLACE_PASSWORD=>$_POST['confirm_password'],REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE,SITE_DESCRIPTION=>$this->app_description,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
												$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'user-changepassword.html',$replace_variables);

							$to = $result[0]['email'];
							$from = CONTACT_EMAIL;
							$subject = __('change_password_subject').' - '.COMPANY_SITENAME;	
							$redirect = "driver/changepassword";	
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

												//$mail=array("to" => $result[0]['email'],"from"=>CONTACT_EMAIL,"subject"=>__('change_password_subject').' - '.COMPANY_SITENAME,"message"=>$message);
												//$emailstatus=$this->email_send($mail,'smtp');						
												Message::success(__('sucessful_change_password'));			
												$this->request->redirect("driver/changepassword");
										}	
										else
										{
											echo __("fail_change_password");
										}
										$validator_changepass = null;
										$email_exists="";
										$user_exists="";
							}
							else
							{
								$oldpass_error=__('oldpassword_error');	
							}
						}else{
						
							$same_pw =__('samepw_error');	
						}
						
					}
					
					else 
					{
					//validation failed, get errors
					$errors = $validator_changepass->errors('errors');
					}
			}

        $user = $driver->get_driver_profile_details($userid);
       // print_r($user);
		$view=View::factory(USERVIEW.'driver/changepassword')
				->bind('validator', $_POST)
				->bind('validator_changepass', $validator_changepass)
				->bind('oldpass_error',$oldpass_error)
				->bind('same_pw',$same_pw)
				->bind('errors', $errors)
                ->bind('usrid',$userid)
				->bind('user', $user);        
		
		$this->template->content = $view;		
	    $this->template->meta_desc = $this->meta_description;
        $this->template->meta_keywords = $this->meta_keywords;
        $this->template->title =$this->title;
	}

	
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
		if(!isset($this->session) ||  !$this->session->get('id') ) // (!$this->session->get('userid')) && 		 
		{
			Message::error(__('login_access'));
			$this->request->redirect("/users/login/");
		}
		return;
	}

	/*****Complete Trip *****/	
	public function action_complete_trip()
	{		
		/*
		$device_token = 'APA91bH1c4Lm0-BKHgjJVqOoI6JmF2RAf_mGhdU5vHBuGRASz37Lh1a8xJX-CgDQkUPE12pRPtQDcLmssS2Y1pHpk_QfztAxyjwdRyX50wg786MKv73kaw7TMYZ9sdx42tn9GEs8trxk'; $device_type = 1; $customer_android_key = 'AIzaSyA7H3B1xvR9S9VD25al1Q13bYpwnOfXXhQ';
		$trip_id = $_REQUEST['trip_id'];
		$user = 'Admin';
		$push_msg_string = "Trip was successfully completed by {$user}"; 
		$push_message = array("message" => $push_msg_string,"status" => 19);

		$send_pushnotification = $this->send_driver_mobile_pushnotification($device_token,$device_type,$push_message,$customer_android_key);
		exit;
		*/
		$commonmodel = Model::factory('commonmodel');
		$driver_model = Model::factory('driver');
		//print_r($_REQUEST['trip_id']);exit;
		$trip_id = $_REQUEST['trip_id'];
		$cash_payment = $_REQUEST['cash_payment'];
		$comments = isset($_REQUEST['comments']) ? $_REQUEST['comments'] : "";
		$get_pass_log_details = $driver_model->get_passenger_log($trip_id);
		//print_r($get_pass_log_details);exit;
		if(count($get_pass_log_details) > 0)
		{
			$total_distance = $get_pass_log_details[0]['distance'];
			$passenger_id = $get_pass_log_details[0]['passengers_id'];
			$driver_id = $get_pass_log_details[0]['driver_id'];
			$taxi_id = $get_pass_log_details[0]['taxi_id'];
			$company_id = $get_pass_log_details[0]['company_id'];
			$waiting_time = $get_pass_log_details[0]['waitingtime'];
			$passenger_discount = $get_pass_log_details[0]['passenger_discount'];
			$travel_status = $get_pass_log_details[0]['travel_status'];
			$company_tax = $get_pass_log_details[0]['company_tax'];
			$pickuptime = date('H:i:s', strtotime($get_pass_log_details[0]['pickup_time']));
			$fixedprice = $get_pass_log_details[0]['fixedprice'];
			$promocode = $get_pass_log_details[0]['promocode'];
			$pickupdrop = $get_pass_log_details[0]['pickupdrop'];
			$actual_pickup_time = $get_pass_log_details[0]['actual_pickup_time'];
			$tax = $company_tax;//(FARE_SETTINGS != 2) ? TAX : $company_tax;

			$taxi_model_id = $driver_model->get_taxi_modelid_by_taxiid($taxi_id);
			
			if($travel_status == 2 || $travel_status == 3 || $travel_status == 5)
			{
				//$taxi_details = $driver_model->get_taxi_model_details1($taxi_id);
				//$taxi_model_id = $taxi_details[0]['taxi_model'];
				$taxi_fare_details = $driver_model->get_model_fare_details1($company_id,$taxi_model_id,$get_pass_log_details[0]['search_city']);
				
				$base_fare = '0';
				$min_km_range = '0';
				$min_fare = '0';
				$cancellation_fare = '0';
				$below_above_km_range = '0';
				$below_km = '0';
				$above_km = '0';
				$night_charge = '0';
				$night_timing_from = '0';
				$night_timing_to ='0';
				$night_fare = '0';
				$evening_charge = '0';
				$evening_timing_from = '0';
				$evening_timing_to ='0';
				$evening_fare = '0';
				$waiting_per_hour = '0';
				$minutes_cost= '0';
				$nightfare='0';
				$waiting_cost='0';
				if(count($taxi_fare_details) > 0)
				{
					$base_fare = $taxi_fare_details[0]['base_fare'];
					$min_km_range = $taxi_fare_details[0]['min_km'];
					$min_fare = $taxi_fare_details[0]['min_fare'];
					$cancellation_fare = $taxi_fare_details[0]['cancellation_fare'];
					$below_above_km_range = $taxi_fare_details[0]['below_above_km'];
					$below_km = $taxi_fare_details[0]['below_km'];
					$above_km = $taxi_fare_details[0]['above_km'];
					$night_charge = $taxi_fare_details[0]['night_charge'];
					$night_timing_from = $taxi_fare_details[0]['night_timing_from'];
					$night_timing_to = $taxi_fare_details[0]['night_timing_to'];
					$night_fare = $taxi_fare_details[0]['night_fare'];
					$evening_charge = $taxi_fare_details[0]['evening_charge'];
					$evening_timing_from = $taxi_fare_details[0]['evening_timing_from'];
					$evening_timing_to = $taxi_fare_details[0]['evening_timing_to'];
					$evening_fare = $taxi_fare_details[0]['evening_fare'];
					$waiting_per_hour = $taxi_fare_details[0]['waiting_time'];
					$minutes_fare = $taxi_fare_details[0]['minutes_fare'];
				}
								
				if($travel_status != 5) {
					$drop_time = $commonmodel->getcompany_all_currenttimestamp($company_id);
				} else {
					$drop_time = $get_pass_log_details[0]['drop_time'];
				}
				
				$roundtrip="No";
				if($pickupdrop == 1)
				{
					$roundtrip = "Yes";
					//$total_distance = $total_distance * 2;
				}
				
				$interval  = abs(strtotime($drop_time) - strtotime($actual_pickup_time));
				$minutes   = round($interval / 60); 

				$baseFare = $base_fare;
				$total_fare = $base_fare;
								
				/*****End Find  Minimum distance****/
				if(FARE_CALCULATION_TYPE==1 || FARE_CALCULATION_TYPE==3)
				{
					$baseFare = $base_fare;
					if($total_distance < $min_km_range)
					{
						$baseFare = $min_fare;
						$total_fare = $min_fare;
					}
					else if($total_distance <= $below_above_km_range)
					{       
						$fare = $total_distance * $below_km;
						$total_fare  = 	$fare + $base_fare ;
					}
					else if($total_distance > $below_above_km_range)
					{
						$fare = $total_distance * $above_km;
						$total_fare  = 	$fare + $base_fare ;
					}
				}
				if(FARE_CALCULATION_TYPE==2 || FARE_CALCULATION_TYPE==3)
				{
					/********** Minutes fare calculation ************/
					//$date_difference = abs($drop_time - $actual_pickup_time);
					//$minutes = floor(((($date_difference % 604800) % 86400) % 3600) / 60);
					if($minutes_fare > 0)
					{
						$minutes_cost = $minutes * $minutes_fare;
						$total_fare  = $total_fare + $minutes_cost;
					}
					/************************************************/
				}
				$trip_fare = $total_fare;

				// Waiting Time calculation
				$waiting_cost = $waiting_per_hour * $waiting_time;
				$total_fare = $waiting_cost + $total_fare;		
								
				
				
				$parsed = date_parse($actual_pickup_time);
				$pickup_seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
				/*******Night fare Calculation part************/
				$parsed = date_parse($night_timing_from);
				$night_from_seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
				$parsed = date_parse($night_timing_to);
				$night_to_seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
				//Night Fare Calculation
				$nightfare_applicable=$date_difference=0;
				if ($night_charge != 0) 
				{
				if( ($pickup_seconds >= $night_from_seconds && $pickup_seconds <= 86399) || ($pickup_seconds >= 0 && $pickup_seconds <= $night_to_seconds) )

					{        
						$nightfare_applicable = 1;
						$nightfare = ($night_fare/100)*$total_fare;//night_charge%100;
						//$total_fare  = $nightfare + $total_fare;
					}
				}
				/*******Night fare Calculation part end ************/
				
				/***Evening fare calculation start *****/
				$parsed_evg = date_parse($evening_timing_from);
				$evg_from_seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
				$parsed_evg = date_parse($evening_timing_to);
				$evg_to_seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];

				//Evening Fare Calculation
				$eveningfare = 0;//echo $pickuptime;exit;
				$evefare_applicable=$date_difference=0;
				if ($evening_charge != 0)
				{
					//if( $pickuptime >= $evening_timing_from &&  $pickuptime <= $evening_timing_to)
					if( ($pickup_seconds >= $evg_from_seconds && $pickup_seconds <= 86399) || ($pickup_seconds >= 0 && $pickup_seconds <= $evg_to_seconds) )
					{
						$evefare_applicable = 1;
						$eveningfare = ($evening_fare/100)*$total_fare;//night_charge%100;
						//$total_fare  = $eveningfare + $total_fare;
					}
				}
				/***Evening fare calculation End *****/
				$night_evg_minfare=0;$minimum_night_evening = 0;
				/**minimum_night_evening 1-Evg,2-Night Flag for finf which one is apply****/
				if($evening_charge !=0 ||  $night_charge !=0)
				{
					if($nightfare>=$eveningfare)
					{
						$evefare_applicable = 0;
						$minimum_night_evening = 2;//Flag for finf which one is apply
						$total_fare  = $nightfare + $total_fare; //Night fare 
					}
					else
					{	$minimum_night_evening = 1;$nightfare_applicable = 0;
						$total_fare  = $eveningfare + $total_fare;// //Evening fare 
					}
					
				}

				// Passenger individual Discount Calculation				
				$discount_fare="0.00";
				if($passenger_discount!='0')
				{
					$discount_fare = ($passenger_discount/100)*$total_fare;				
					$total_fare = $total_fare - $discount_fare;
				}
				
				$promo_discount = $promodiscount_amount = $promo_discount_type = 0;

				if($promocode != "")
				{
					$promodetails = $commonmodel->getpromodetails($promocode,$passenger_id);
					//print_r($promodetails);
					if(is_array($promodetails) && count($promodetails) > 0)
					{
						$promo_discount = $promodetails['promo_discount'];
						$promo_discount_type = $promodetails['discount_type'];
						// %
						if($promo_discount_type == 1)
						{
							$calculate_amt = ($promo_discount/100)*$total_fare;
							$promodiscount_amount = round($calculate_amt,2);
						}
						//Flat rate
						elseif($promo_discount_type == 2)
						{
							$calculate_amt = $promo_discount;
							$promodiscount_amount = round($calculate_amt,2);
						}
						$promodiscount_amount = round($calculate_amt,2);
						$total_fare = $total_fare-$promodiscount_amount;
						$update_discount_arrary = array("promo_discount_amount" => round($promodiscount_amount,2));
						$result = $commonmodel->update_table(PASSENGERS_LOG,$update_discount_arrary,'passengers_log_id',$trip_id);
						
						if($total_fare < 0)
							$total_fare = 0;
						//if($promodiscount_amount > 0)
						//$update_promo_discount = $api->update_promo_discount($passengers_id,$promocode,$promodiscount_amount);
					}
					else 
					{
						$promo_discount = 0;
						$promodiscount_amount = 0;
					}					
				}
				
				
				$tax_amount = "";
				if($tax > 0)//company_tax
				{
						$tax_amount = ($tax/100)*$total_fare;//night_charge%100;
						$total_fare =  $total_fare+$tax_amount;
				}

			
				
				$actual_trip_fare = $total_fare;
                                                           
				$total_fare = ($fixedprice != 0) ? $fixedprice : $total_fare;

				$trip_fare = round($trip_fare,2);
				$total_fare = round($total_fare,2);
				$referdiscount = 0;//round($referdiscount,2);
				$discount_fare = round($discount_fare,2);
				$tax_amount = round($tax_amount,2);
				$nightfare = round($nightfare,2);
				
				//updating approximate fare
				$update_array  = array("approx_fare" => $total_fare);
				$commonmodel->update(PASSENGERS_LOG,$update_array,'passengers_log_id',$trip_id);
				
				$update_commission = $commonmodel->update_commission($trip_id,$total_fare,ADMIN_COMMISSON);
								
				$evefare_applicable = 0;
				$insert_array = array(
									"passengers_log_id" => $trip_id,
									"distance" 			=> $total_distance,
									"actual_distance" 	=> $total_distance,
									"tripfare"			=> $trip_fare,
									"fare" 				=> $total_fare,
									"actual_trip_fare" 	=> $actual_trip_fare,
									"tips" 				=> 0,
									"waiting_cost"		=> $waiting_cost,
									"passenger_discount"=> $promodiscount_amount,
									"promo_discount_fare"=> $promo_discount,
									"company_tax"		=> $tax_amount,
									"waiting_time"		=> 0,
									"trip_minutes"		=> 0,
									"minutes_fare"		=> 0,
									"remarks"			=> '',
									"payment_type"		=> !empty($paymode) ? $paymode : 1,
									"amt"				=> $total_fare,
									"nightfare_applicable" => $nightfare_applicable,
									"nightfare" 		=> $nightfare,
									"eveningfare_applicable" => $evefare_applicable,
									"eveningfare" 		=> $evening_fare,
									"admin_amount"		=> $update_commission['admin_commission'],
									"company_amount"	=> $update_commission['company_commission'],
									"trans_packtype"	=> $update_commission['trans_packtype']
								);

				$check_trans_already_exist = $driver_model->checktrans_details($trip_id);
				if(count($check_trans_already_exist) > 0)
				{	
					$tranaction_id = $check_trans_already_exist[0]['id'];
					$update_transaction = $driver_model->update_table(TRANS,$insert_array,'id',$tranaction_id);
					$jobreferral = $tranaction_id;
					
					/*$update_pass_log_arrary = array("dispatcher_update"=>1);
				$result = $driver_model->update_table(PASSENGERS_LOG, $update_pass_log_arrary, 'passengers_log_id',$trip_id);*/
				}
				else
				{
					$transaction = $commonmodel->insert(TRANS,$insert_array);		
					$jobreferral = $transaction[0];

				}								
												
				/********** Update Travel Status after complete trip *****************/
				//$update_pass_log_arrary = array("travel_status" => 1,"dispatcher_update"=>1);
				$update_pass_log_arrary = array("travel_status" => 1);
				$result = $driver_model->update_table(PASSENGERS_LOG, $update_pass_log_arrary, 'passengers_log_id',$trip_id);	
				
				/********** Update Driver Status after complete Payments *****************/
				$drivers_id = $driver_id;//$get_pass_log_details[0]->driver_id;
				$update_driver_arrary = array("status" => 'F');
				$result = $driver_model->update_table(DRIVER,$update_driver_arrary,'driver_id',$drivers_id);	
				
				/************Update Driver Status ***************************************/				
				$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
				$journey = $driver_model->update_journey_status($trip_id,$msg_status,$driver_reply,$journey_status);
				/*************** Update in driver request table ******************/
				$update_trip_array  = array("status"=>'8');
				$result = $driver_model->update_table(DRIVER_REQUEST_DETAILS,$update_trip_array,'trip_id',$trip_id);		
				/*************************************************************************/
				if(!empty($comments))
					$update_array = array('comments'=>$comments,'trip_completed_by' => 2);
				else
					$update_array = array('trip_completed_by' => 2);

				$update_transaction = $driver_model->update_table(PASSENGERS_LOG,$update_array,'passengers_log_id',$trip_id);

				$device_id = isset($get_pass_log_details[0]['driver_device_id']) ? $get_pass_log_details[0]['driver_device_id'] : '';
				$device_token = isset($get_pass_log_details[0]['driver_device_token']) ? $get_pass_log_details[0]['driver_device_token'] : '';
				$device_type = isset($get_pass_log_details[0]['driver_device_type']) ? $get_pass_log_details[0]['driver_device_type'] : '';

				$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '0';

				if(!empty($userid))
				{
					$user = $driver_model->get_user_name_by_id($userid);
				}	
				else
				{
					$user = 'Admin';
				}

				$customer_android_key = $commonmodel->select_site_settings('driver_android_key',SITEINFO);

				if(!empty($device_token) && !empty($device_type))
				{	
					$push_msg_string = "Trip was successfully completed by {$user}"; 
					$push_message = array("message" => $push_msg_string,"status" => 19);
					$send_pushnotification = $this->send_driver_mobile_pushnotification($device_token,$device_type,$push_message,$customer_android_key);
					
				}
			}
			$send_mail_status = $this->send_mail_passenger($trip_id,1);
			echo "1";exit;
			
		}
		else
			echo "0";exit;
	}

	
	public function is_login_status()
	{ 
		$session = Session::instance();

		//get current url and set it into session
		//========================================
		$this->session->set('requested_url', Request::detect_uri());
				
		if(isset($this->session) || $this->session->get('id') )	
		{	
			$driver = Model::factory('driver');
			/**Check user is blocked or not  **/
			$result=$driver->logged_user_status();
			
			
			/**If user exists ans status is active redirect to home**/
			if( ($result == 1) || ($result== 0) )
			{
				return;	
			}
			/**If user status is not active**/
			elseif($result== -1)
			{ 
				Message::success(__('user_blocked')); 
				$this->request->redirect("/users/logout/");
			}
			/**If user name or password is wrong**/
			else
			{	
				Message::success(__('invalid_credentials')); 	  
				$this->request->redirect("/users/logout/");
			}		
			//return;
		}else { return; }		
	}
	
	
	//Get the Driver Push Notifications
	public function action_get_driver_notifications()
	{
		if(isset($_POST["value"]))
		{
			$driver_model = Model::factory('driver');		
			$driver_id =$this->session->get('id');				
			$driver_logs = $driver_model->get_driver_logs_ajax($driver_id,'U','');
			//$noty_array = array();
			$noty_array="";		
			if(count($driver_logs) > 0)
			{
				$noty_array = '<input type="hidden" id="driver_logs" name="driver_logs" value="'.count($driver_logs).'">';
				$i=1;
				foreach ($driver_logs as $driver) 
				{
					if($driver->pickupdrop == 1) { $drop = 'Yes'; } else { $drop = 'No';}
					$timers ="<script>$('.kkcount-down-1').html('timers');</script>";
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
						  <dt>'.__("Drop").' </dt>
						  <dd>'.$drop.'</dd>
						  <dt>'.__("Time to left").' </dt>
						  <dd><span data-seconds="60" class="kkcount-down-1">This notification will expire with in 60 seconds.</span></dd>
						</dl>
						  <a class="btn btn-success" href="#driver_response" onclick=action_request("'.$driver->passengers_log_id.'","A")>'.__("Confirm").'</a> <a class="btn btn-danger"  href="#driver_response"  onclick=action_request("'.$driver->passengers_log_id.'","R")>'.__("Reject").'</a>
						<p>
						</p>

					</div>';
					//array_push($noty_array, $html);
					$i=$i+1;
				}			
			}
			if(count($driver_logs) == 0)
				$noty_array ='<h4>'.__("no_notifications").'</h4>';	
			
			echo $noty_array;
			exit;
		}
	}
	
	public function action_update_driver_status()
	//Get appropriate data for Updating the Driver Status
	{
		if(isset($_POST['pass_logid']))
		{
			$driver_model = Model::factory('driver');
			//$current_trip_details = $driver_model->get_passenger_log_details($_POST['pass_logid']);
			//$pickup_time = date('h:i:s A',strtotime($current_trip_details[0]->pickup_time));
			//$current_time  = date('h:i:s A');
			//if ( $pickup_time > $current_time ) 
			//{
				if($_POST['status'] == 'A')
				$html = '<div class="alert alert-block alert-success fade in">														
							<label for="time_to_travel"><strong>'.__("time_to_reach").'</strong></label>
							<input class="required" type="number" min="1" max="999" maxlength="3" id="time_to_reach" name="time_to_reach" placeholder="'.__("time_to_reach").'" value="">
							<button class="btn btn-primary" onclick=javascript:validate_span("time_to_reach","'.$_POST['pass_logid'].'","'.$_POST['status'].'","'.$_POST['flag'].'") id="required_btn">Submit</button>
							<br/>
							<span>'.__('time_hint').'</span>
							<span class="danger" id="span_time_to_travel"></span><br/>
							
						</div>';
				else
					$html = '<div class="alert alert-block alert-danger fade in">														
								<label for="time_to_travel"><strong>'.__("comments_reject").'</strong></label>
								<textarea class="required" type="text" id="comments_reject" name="comments_reject" placeholder="'.__("comments_reject").'" value=""></textarea>
								<button class="btn btn-primary" onclick=javascript:validate_span("comments_reject","'.$_POST['pass_logid'].'","'.$_POST['status'].'","'.$_POST['flag'].'") id="required_btn">Submit</button>
								<br/>
							</div>';
                $html .= '<script>$("#time_to_reach").keypress(function(event) { return checkisNumber(event) });</script>';											
				echo $html;
				exit;
			/*}
			else
			{
				$driver_reply = $driver_model->update_driver_status($_POST['pass_logid'],'C','Due to Timeout','1');
				Message::success(__('driver_reply_timeout'));
				echo "<script> document.location.href='".URL_BASE."driver/dashboard';</script>";
				exit;				
			} */
		
		}
	}
	
	//Sending the Mail to the appropriate Users
	public function action_set_driver_status()
	{
		
		if(isset($_REQUEST['pass_logid']))
		{
			$driver_model = Model::factory('driver');

			$tdispatch_model = Model::factory('tdispatch');	


			$driver_id =$this->session->get('id');
			$passenger_details = $driver_model->get_passenger_log_details($_REQUEST['pass_logid']);

				foreach($passenger_details as $pvalues)
				{
					 $name = $pvalues->name;
					 $passenger_email=$pvalues->passenger_email;
					 $current_location=$pvalues->current_location;
					 $drop_location=$pvalues->drop_location;
					 $pickup_time=$pvalues->pickup_time;
					 $driver_name = ucfirst($pvalues->driver_name);
					 $passenger_name = ucfirst($pvalues->passenger_name);
					 $passenger_phone = $pvalues->passenger_phone;
					 $driver_phone = $pvalues->driver_phone;
					 $bookby = $pvalues->bookby;
					 $company_id = $pvalues->get_companyid;
				 }			

			$company_owner = $driver_model->get_company_ownerid($company_id);

			$company_ownerid = $company_owner[0]['id'];

			$current_datetime = convert_timezone('now',TIMEZONE);
			$current_time = date('h:i:s A',strtotime($current_datetime));

			$pickup = date('h:i:s A',strtotime($pickup_time));


			if($_REQUEST['status'] == 'A' && $bookby ==0)
			{



				if ( $current_time > $pickup ) 
				{

						if($bookby == 2)
						{	
						// Create Log //		
						
						$log_message = __('log_message_driver_missed');
						$log_message = str_replace("TRIPID",$_REQUEST['pass_logid'],$log_message); 
						$log_message = str_replace("DRIVERNAME",$driver_name,$log_message); 
						$log_booking = __('log_booking_driver_missed');
						$log_booking = str_replace("DRIVERNAME",$driver_name,$log_booking); 

						$log_status = $tdispatch_model->create_logs($_REQUEST['pass_logid'],$company_id,$company_ownerid,$log_message,$log_booking);
						// Create Log //

						}

						$driver_reply = $driver_model->update_driver_status($_REQUEST['pass_logid'],'C','Due to Timeout','1');
						Message::success(__('driver_reply_timeout'));
						echo "<script> document.location.href='".URL_BASE."driver/dashboard';</script>";
						exit;				
				}
			}
				$my_profile = $driver_model->get_driver_profile_details($driver_id);				
				$manager_profile = $driver_model->get_manager_details($my_profile[0]['login_city'],$my_profile[0]['login_state'],$my_profile[0]['login_country'],$my_profile[0]['company_id']);
				//print_r($manager_profile);
				foreach($manager_profile as $mvalues)
				{
					 $manager_name = $mvalues->name;
					 $manager_email=$mvalues->email;
				 }
			$driver_reply = $driver_model->update_driver_status($_REQUEST['pass_logid'],$_REQUEST['status'],$_REQUEST['field'],$_REQUEST['flag']);

			if($driver_reply == 1)
			{
				if($bookby == 2)
				{
				// Create Log //		

			
				$log_message = __('log_message_driver_confirmed');
				$log_message = str_replace("TRIPID",$_REQUEST['pass_logid'],$log_message); 
				$log_message = str_replace("DRIVERNAME",$driver_name,$log_message); 
				$log_booking = __('log_booking_driver_confirmed');
				$log_booking = str_replace("DRIVERNAME",$driver_name,$log_booking); 

				$log_status = $tdispatch_model->create_logs($_REQUEST['pass_logid'],$company_id,$company_ownerid,$log_message,$log_booking);
				// Create Log //
				}
				
				
				$html = '<div class="alert fade in danger">		
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<strong>'.__("request_confirmed").'</strong>	
							<a href="javascript:location.reload();">Click Here to Reload the Page</a>						
						</div>';

				$html_passenger = '<table>
							<tr><b>'.__('Current_Location').'</b><td> - </td><td>'.$current_location.'</td></tr>							
							<tr><b>'.__('Drop_Location').'</b><td> - </td><td>'.$drop_location.'</td></tr>							
							<tr><b>'.__('pick_up_time').'</b><td> - </td><td>'.date('H:i:s',strtotime($pickup_time)).'</td></tr>																			 
							<tr><b>'.__('driver_name').'</b><td> - </td><td>'.$driver_name.'</td></tr>							
							<tr><b>'.__('driver').' '.__('phone_number').'</b><td> - </td><td>'.$driver_phone.'</td></tr>		
								</table>';
				$html_manager = '<table>
							<tr><b>'.__('Current_Location').' </b><td> - </td><td>'.$current_location.'</td></tr>							
							<tr><b>'.__('Drop_Location').'</b><td> - </td><td>'.$drop_location.'</td></tr>							
							<tr><b>'.__('pick_up_time').' </b><td> - </td><td>'.date('H:i:s',strtotime($pickup_time)).'</td></tr>																			 
							<tr><b>'.__('Passenger').' </b><td> - </td><td>'.$passenger_name.'</td></tr>		
							<tr><b>'.__('Passenger').' '.__('phone_number').'</b><td> - </td><td>'.$passenger_phone.'</td></tr>
							<tr><b>'.__('driver_name').' </b><td> - </td><td>'.$driver_name.'</td></tr>							
						</table>';
				//print_r($passenger_details);
				/** Send Confirm Mail to Passenger **/
			/*	$pass_replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_NAME=>$passenger_name,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE,REPLACE_REQMESSAGE=>$html_passenger);
				$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'request_confirm_passenger.html',$pass_replace_variables);

					$to = $passenger_email;
					$from = CONTACT_EMAIL;
					$subject = __('request_confirmation_passenger_subject');	
					$redirect = "no";	
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
					}*/

	

						
				/*** Send Mail to Manager ***/
				
			/*	$manager_replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_NAME=>$manager_name,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE,REPLACE_REQMESSAGE=>$html_manager);
				$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'request_confirm_manager.html',$manager_replace_variables);


					$to = $manager_email;
					$from = CONTACT_EMAIL;
					$subject = __('request_confirmation_manager_subject')." - ".COMPANY_SITENAME;	
					$redirect = "no";	
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
				*/

				echo $html;
				//echo "<script> document.location.href='".URL_BASE."driver/dashboard';</script>";
				exit;
			}
			else if($driver_reply == 2)
			{
				if($bookby == 2)
				{
				// Create Log //		

				$log_message = __('log_message_driver_cancelled');
				$log_message = str_replace("TRIPID",$_REQUEST['pass_logid'],$log_message); 
				$log_message = str_replace("DRIVERNAME",$driver_name,$log_message); 
				$log_booking = __('log_booking_driver_cancelled');
				$log_booking = str_replace("DRIVERNAME",$driver_name,$log_booking); 

				$log_status = $tdispatch_model->create_logs($_REQUEST['pass_logid'],$company_id,$company_ownerid,$log_message,$log_booking);
				// Create Log //

				}
				
			/*	$html = '<div class="alert fade in danger">							
							<strong>'.__("request_rejected").'</strong>.
						</div>';				
				$html_manager = '<table>
							<tr><b>'.__('Current_Location').' </b><td> - </td><td>'.$current_location.'</td></tr>							
							<tr><b>'.__('Drop_Location').'</b><td> - </td><td>'.$drop_location.'</td></tr>							
							<tr><b>'.__('pick_up_time').'</b><td> - </td><td>'.date('H:i:s',strtotime($pickup_time)).'</td></tr>																			 
							<tr><b>'.__('Passenger').'</b><td> - </td><td>'.$passenger_name.'</td></tr>		
							<tr><b>'.__('Passenger').' '.__('phone_number').'</b><td> - </td><td>'.$passenger_phone.'</td></tr>
							<tr><b>'.__('driver_name').'  </b><td> - </td><td>'.$driver_name.'</td></tr>	
							<tr><b>'.__('driver_comments').' </b><td> - </td><td>'.$_POST['field'].'</td></tr>							
						</table>';				
				$manager_replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_NAME=>$manager_name,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE,REPLACE_REQMESSAGE=>$html_manager);
				$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'request_reject_manager.html',$manager_replace_variables);

				$to = $manager_email;
				$from = CONTACT_EMAIL;
				$subject = __('request_reject_manager_subject')." - ".COMPANY_SITENAME;	
				$redirect = "no";	
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
				}*/


				Message::success(__('request_rejected'));
				echo "<script> document.location.href='".URL_BASE."driver/dashboard';</script>";
				exit;
			}
			else
			{
				if($bookby == 2)
				{
				// Create Log //		

				$log_message = __('log_message_driver_missed');
				$log_message = str_replace("TRIPID",$_REQUEST['pass_logid'],$log_message); 
				$log_message = str_replace("DRIVERNAME",$driver_name,$log_message); 
				$log_booking = __('log_booking_driver_missed');
				$log_booking = str_replace("DRIVERNAME",$driver_name,$log_booking); 

				$log_status = $tdispatch_model->create_logs($_REQUEST['pass_logid'],$company_id,$company_ownerid,$log_message,$log_booking);
				// Create Log //
				}
				Message::success(__('driver_reply_timeout'));
				echo "<script> document.location.href='".URL_BASE."driver/dashboard';</script>";
				exit;				
			}
		}
		
	}
	
	/*Journey Cancel*/
	public function action_cancelTrip()
	{
		if(isset($_POST['pass_logid']))
		{
			if($_POST['status'] == 'R')
				$html = '<div class="alert alert-block alert-danger fade in">														
							<label for="time_to_travel"><strong>'.__("comments_reject").'</strong></label>
							<textarea class="required" type="text" id="comments_reject" name="comments_reject" placeholder="'.__("comments_reject").'" value=""></textarea>
							<button class="btn btn-primary" onclick=javascript:validate_span("comments_reject","'.$_POST['pass_logid'].'","'.$_POST['status'].'") id="required_btn">Submit</button>
							<br/>
						</div>';
			echo $html;
			exit;
		}
	}

	/*Journey Function*/
	public function action_journey_completed()
	{
		if(isset($_REQUEST['pass_logid']))
		{

			$driver_model = Model::factory('driver');
			$api = Model::factory('api');					
			$start = $this->getLatLong($_POST['from']);			
			$finish = $this->getLatLong($_POST['to']);		
			$get_passenger_log_details = $driver_model->get_passenger_log_details($_POST['pass_logid']);
			//echo count($get_passenger_log_details);
			$passenger_discount = $get_passenger_log_details[0]->passenger_discount;
			$pickupdrop = $get_passenger_log_details[0]->pickupdrop;
			$pickuptime = date('h:i:s a', strtotime($get_passenger_log_details[0]->pickup_time));
			$taxi_id = $get_passenger_log_details[0]->taxi_id;
			$driver_id = $get_passenger_log_details[0]->driver_id;
			$company_id = $get_passenger_log_details[0]->company_id;
			$get_taxi_fare_waiting_charge = $driver_model->get_taxi_fare_waiting_charge($taxi_id);

			$taxi_details = $driver_model->get_taxi_model_details($taxi_id);
			$taxi_model_id = $taxi_details[0]->taxi_model;	
			$taxi_fare_details = $driver_model->get_citymodel_fare_details($taxi_model_id,$get_passenger_log_details[0]->search_city,$company_id);
			//Make Driver Status Free
			/****** TDispatch **********************/
			$faretype = $get_passenger_log_details[0]->faretype;
			//echo $_POST['pass_logid'];
			//exit;

			$bookby = $get_passenger_log_details[0]->bookby;
			$account_id = $get_passenger_log_details[0]->account_id;
			$accgroup_id = $get_passenger_log_details[0]->accgroup_id;
			$company_tax = $get_passenger_log_details[0]->company_tax;
			$account_details = $api->get_account_discount($account_id);

			$account_limit=$account_discount=$grp_limit="";//exit;
				if(count($account_details)>0)
				{
					$account_limit = $account_details[0]['limit'];
					$account_discount = $account_details[0]['discount'];
				}

			$update_driver_arrary  = array("status" => 'F');	
            		$result = $api->update_table(DRIVER,$update_driver_arrary,'driver_id',$driver_id);			
			//Converting the Waiting Time 
			$waiting_time = $_POST['waiting_time'];
			$split_time = explode(":",$waiting_time);
			if(count($split_time) == 3)
			{
				//Converting to Hours
				$waiting_hours = $split_time[0]/60;
			}
			else
			{
				//Converting to Hours
				$waiting_hours = $split_time[0];
				$waiting_hours += $split_time[1]/60;
			}

			$fixedprice = $get_passenger_log_details[0]->fixedprice;			
		
			$base_fare = $taxi_fare_details[0]->base_fare;
			$min_km_range = $taxi_fare_details[0]->min_km;
			$min_fare = $taxi_fare_details[0]->min_fare;
			$cancellation_fare = $taxi_fare_details[0]->cancellation_fare;
			$below_above_km_range = $taxi_fare_details[0]->below_above_km;
			$below_km = $taxi_fare_details[0]->below_km;
			$above_km = $taxi_fare_details[0]->above_km;
			$night_charge = $taxi_fare_details[0]->night_charge;
			$night_timing_from = $taxi_fare_details[0]->night_timing_from;
			$night_timing_to = $taxi_fare_details[0]->night_timing_to;
			$night_fare = $taxi_fare_details[0]->night_fare;
			$waiting__per_hour = $taxi_fare_details[0]->waiting_time;
			// Waiting Time Charge for an company					
			$total_fare = $distance = $total = 0;

			$waiting_cost = $waiting__per_hour * $waiting_hours;

			if($bookby == 2)
			{
				$distance = $get_passenger_log_details[0]->approx_distance;
				$roundtrip = "No";								
			}
			else
			{


				$distance = $this->Haversine($start, $finish);
				//print_r($taxi_fare_details);
				$roundtrip = "No";							
				if($pickupdrop == 1)
				{
					$roundtrip = "Yes";
					//$total_fare = $total_fare * 2;
					$distance = $distance * 2;
				}
				//exit;
				//Converting to Km
				$distance = $distance * 1.609344;		
			}

			if($distance <= $min_km_range)
			{
				$total_fare  = 	$min_fare;
			}
			else if($distance <= $below_above_km_range)
			{
				$fare = $below_km * $distance;
				$total_fare  = 	$fare + $base_fare ;
			}
			elseif($distance > $below_above_km_range)
			{
				$fare = $above_km * $distance;
				$total_fare  = 	$fare + $base_fare;
			}


			if ($night_charge != 0) 
			{
				if( $pickuptime >= $night_timing_from || $pickuptime <= $night_timing_from)
				{
					$nightfare = ($night_fare/100)*$total_fare;//night_charge%100;					
					$total_fare  = 	$nightfare + $total_fare;
				}
			}			
			$total_journey_fare = $total_fare;
			$tax_data = "";
				//	echo $total_fare;
				//echo '<br>';		

			

			$total_fare  = 	$total_fare + $waiting_cost;
			
			$tripfare  = 	$total_fare + $waiting_cost;			

			
			if($company_tax > 0)
			{
					$tax_amount = ($company_tax/100)*$total_fare;//night_charge%100;
			
					$total_fare  = 	$total_fare+$tax_amount;

					$tax_data = "<dt>".__('tax_amount')."(".$company_tax."%)"."</dt>
							<dd>
								<div class='input-prepend'><span class='add-on'>".CURRENCY."</span>
									<input class='span2' id='company_tax' disabled type='text' value=".$tax_amount." placeholder='".__('tax_amount')."'>
								</div>
							</dd>";
			}
			else
			{
					$tax_data = "<input class='span2' id='company_tax' disabled type='hidden' value=''>";
			}
			$subtotal  = 	$total_fare;
			$total = $total_fare;

			//echo $total;
					//exit;			
			$passenger_discount_data = "<input class='span2' id='passenger_discount' disabled type='hidden' value=''>";
			$account_discount_data = "<input class='span2' id='account_discount' disabled type='hidden' value=''>";
			if($faretype != 1)
			{
				$discount_fare="";
				if($passenger_discount!='0')
				{
				$discount_fare = ($passenger_discount/100)*$total_fare;//night_charge%100;					
				$total  = 	$total_fare - $discount_fare;
				}
			
				if($discount_fare=="")
				{
					$discount_fare = 0;
				}		
				else
				{
					$discount_fare = $discount_fare;
				}
				$passenger_discount_data = "<dt>".__('discount_passenger_amount')."(".$passenger_discount.")"."</dt>
							<dd>
								<div class='input-prepend'><span class='add-on'>".CURRENCY."</span>
									<input class='span2' id='passenger_discount' disabled type='text' value=".$discount_fare." placeholder='".__('discount_passenger_amount')."'>
								</div>
							</dd>";
			}
			else
			{
				//$account_discount
				$acc_discount_fare="";
				if($account_discount!='0')
				{
				$acc_discount_fare = ($account_discount/100)*$total_fare;//night_charge%100;					
				$total  = 	$total_fare - $acc_discount_fare;
				}
			
				if($acc_discount_fare=="")
				{
					$acc_discount_fare = 0;
				}		
				else
				{
					$acc_discount_fare = $acc_discount_fare;
				}
				$account_discount_data = "<dt>".__('discount_account_amount')."(".$account_discount.")"."</dt>
							<dd>
								<div class='input-prepend'><span class='add-on'>".CURRENCY."</span>
									<input class='span2' id='account_discount' disabled type='text' value=".$acc_discount_fare." placeholder='".__('discount_account_amount')."'>
								</div>
							</dd>";						
			}
			/*				<dt>".__('fare_per_hour')."</dt>
							<dd>
								<div class='input-prepend'><span class='add-on'>".CURRENCY."</span>
									<input class='span2' id='fare_per_hour' disabled type='text' value=".$fare_per_hour." placeholder='".__('fare_per_hour')."'>
								</div>
							</dd>
							* 		*/
			
           $roundtrip_data = "<dt>".__('pickup_drop_lable')."</dt>
							<dd>
								<div class='input'>
									<input class='span2' id='roundtrip' disabled type='text' value=".$roundtrip.">
								</div>
							</dd>";
															
			$html="";
			$html = "<div class='completed_cost'>
						<dl class='dl-horizontal'>

							<dt>".__('Total Distance')."</dt>
							<dd>
								<div class='input-append'>
									<input class='span2' id='distance' disabled type='text' value=".$distance." placeholder='".__('Total_cost')."'>
									<span class='add-on'>km</span>
								</div>
							</dd>
							<dt>".__('Actual Distance')."</dt>
							<dd>
								<div class='input-append'>
									<input class='span2' id='actual_distance' type='text' value=".$distance." placeholder='".__('Actual Distance')."'>
									<span class='add-on'>km</span>
								</div>
							</dd>
							<dt>".__('Total_cost')."</dt>
							<dd>
								<div class='input-prepend'><span class='add-on'>".CURRENCY."</span>
									<input class='span2' id='total_fare' disabled type='text' value=".$total_journey_fare." placeholder='".__('Total_cost')."'>
								</div>
							</dd>
							<dt>".__('waiting_time')."</dt>
							<dd>
								<div class='input-append'>
									<input class='span2' name='waiting_hours' id='waiting_hours' disabled type='text' value=".$waiting_hours." placeholder='".__('waiting_time')."'>
									<span class='add-on'>Hr</span>
								</div>
							</dd>
							<dt>".__('waiting_time_cost')."</dt>
							<dd>
								<div class='input-prepend'><span class='add-on'>".CURRENCY."</span>
									<input class='span2' id='waiting_cost' disabled type='text' value=".$waiting_cost." placeholder='".__('waiting_time_cost')."'>
									<input type='hidden' id='tripfare' value=".$tripfare.">
								</div>
							</dd>			
							$roundtrip_data				
							$tax_data
							 <dt>".__('subtotal')."</dt>
							<dd>
								<div class='input-prepend'><span class='add-on'>".CURRENCY."</span>
									<input class='span2' id='subtotal' disabled type='text' value=".$subtotal." placeholder='".__('subtotal')."'>
								</div>
							</dd>							
							$passenger_discount_data
							$account_discount_data
							<dt>".__('total')."</dt>
							<dd>
								<div class='input-prepend'><span class='add-on'>".CURRENCY."</span>
									<input class='span2' id='total' disabled type='text' value=".$total." placeholder='".__('TOTAL')."'>
								</div>
							</dd>";
							if($fixedprice!='') {
								$html .="<dt>".__('fixedprice')."</dt>

									<dd>
										<div class='input-prepend'><span class='add-on'>".CURRENCY."</span>
											<input class='span2' id='actual_amount' type='text' placeholder='".__('actual_amount')."' value=".$fixedprice." readonly>
										</div>
									</dd>";
							}
							else
							{
								$html .="<dt>".__('actual_amount')."</dt>
									<dd>
										<div class='input-prepend'><span class='add-on'>".CURRENCY."</span>
											<input class='span2' id='actual_amount' type='text' placeholder='".__('actual_amount')."' value=".round($total)." >
										</div>
									</dd>";
							}
			
							$html .="<dt>".__('any_remarks')."</dt>
							<dd>
								<textarea id='remarks'></textarea>
							</dd>
							<dt> </dt>
							<dd>
								<button class='btn btn-info' id='submit_travel' onclick=javascript:insert_transaction('".$_POST['pass_logid']."')>Submit</button>
							</dd>
						</dl>
					</div>";
					$html .= '<script>$("#actual_distance").keypress(function(event) { return checkisNumber(event) });
		   $("#actual_amount").keypress(function(event) { return checkisNumber(event) });</script>';
			$msg_status = 'R';$driver_reply='A';$journey_status=1;
			$journey = $driver_model->update_journey_status($_POST['pass_logid'],$msg_status,$driver_reply,$journey_status);

			
			echo $html;		
			
			//Inserting to Transaction Table 
			//$transaction = $driver_model->insert_transaction($_POST['pass_logid'],$distance,$total);
						
			exit;
		}
	}
	
	function action_insert_transaction()
	{
		if(isset($_REQUEST['pass_logid']))
		{
			if($_REQUEST['actual_distance'] == "")
				$distance = $_REQUEST['distance'];
			else
				$distance = $_REQUEST['actual_distance'];
				
			$actual_amount = $_REQUEST['actual_amount'];
			$waiting_cost = $_REQUEST['waiting_cost'];
			$waiting_hours = $_REQUEST['waiting_hours'];
			$remarks = $_REQUEST['remarks'];
			$tripfare = $_REQUEST['tripfare'];
			$total = $_REQUEST['total'];
			
			$passenger_discount = $_REQUEST['passenger_discount'];
			$account_discount = $_REQUEST['account_discount'];
			$company_tax = $_REQUEST['company_tax'];

			if($actual_amount != null)
				$total_fare = $actual_amount;
			else
				$total_fare = $total;			
			
			$Commonmodel = Model::factory('Commonmodel');	
			$drivermodel = Model::factory('driver');	
			
			$transcommission = $drivermodel->set_trans_commissiondetails($_REQUEST['pass_logid'],$total_fare);
	
			$insert_array = array(
								"passengers_log_id"     => $_REQUEST['pass_logid'],
								"distance" 		=> $_REQUEST['distance'],
								"actual_distance" 	=> $_REQUEST['actual_distance'],
								"tripfare"			=> $tripfare,
								"fare" 			=> $total_fare,
								"waiting_cost"		=> $waiting_cost,
								"company_tax"		=> $company_tax,
								"passenger_discount"=> $passenger_discount,
								"account_discount" => $account_discount,
								"waiting_time"		=> $waiting_hours,
								"remarks"		=> $remarks,
								"amt" 			=> $total_fare,
								"admin_amount"		=> $transcommission['admin_commission'],
								"company_amount"	=> $transcommission['company_commission'],
								"trans_packtype"          => $transcommission['package_type'],
								"payment_type"          => '3'  
							);
			//print_r($insert_array);
			//exit;				
			//Inserting to Transaction Table 
			$transaction = $Commonmodel->insert(TRANS,$insert_array);

			//Update 
			exit;
		}
	}
	
	//Getting the latitude and Longitude
	
	function getLatLong($address) 
	{		
		$address = str_replace(' ', '+', $address);
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&sensor=false&key='.GOOGLE_GEO_API_KEY;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$geoloc = curl_exec($ch);
		
		$json = json_decode($geoloc);	
        if($json->status == 'OK')
		{				
		return array($json->results[0]->geometry->location->lat, $json->results[0]->geometry->location->lng);
		}
		else
		{
		return array(11.621354, 76.14253698);
		}
	
		
	}
	
	//Applying the Haversine Function to get the Distance
	
	function Haversine($start, $finish) 
	{	
		$theta = $start[1] - $finish[1]; 
		$distance = (sin(deg2rad($start[0])) * sin(deg2rad($finish[0]))) + (cos(deg2rad($start[0])) * cos(deg2rad($finish[0])) * cos(deg2rad($theta))); 
		$distance = acos($distance); 
		$distance = rad2deg($distance); 
		$distance = $distance * 60 * 1.1515; 
		
		return round($distance, 2);
	}
	
	
		
	//Function for Getting the Driver Status every 60 seconds
	public function action_push_notification()
	{
		if(isset($_POST["driver_id"]))
		{
			$driverid = $_POST["driver_id"];
			$current_datetime = convert_timezone('now',TIMEZONE);
			$Commonmodel = Model::factory('Commonmodel');
			if(isset($_POST["datetime"]))
			{ 
				$getcurrenttime = $_POST["datetime"];
				$availablity = $Commonmodel->get_driver_availability($driverid,COMPANY_CID,$getcurrenttime);
			}
			else
			{
				$availablity = $Commonmodel->get_driver_availability($driverid,COMPANY_CID,$current_datetime);
			}
			
			$html ="";
			//print_r($availablity);
			//exit;
			//Driver Status Need to Change as Active
			if(count($availablity) > 0)
			{
				if($availablity[0]['driver_reply'] == 'A'){
				//Check The Travel Status if it is 0 - Not Completed
				if($availablity[0]['travel_status'] == 9)
				{
					
					$driver_model = Model::factory('driver');
					$current_location = $availablity[0]['current_location'];				
					$drop_location = $availablity[0]['drop_location'];				
					$passlog_id = $availablity[0]['passengers_log_id'];				
					$msg_status = $availablity[0]['msg_status'];				
					$driver_reply = $availablity[0]['driver_reply'];				
					$travel_status = 2;		
					
					echo "|";
						echo "<br><input type='hidden' id='passlog_id' value='".$passlog_id."'><input type='hidden' id='fromText1' value='".$current_location."'><input type='hidden' id='toText1' value='".$drop_location."'>";
					echo "|";	
					
					//Change The Travel Status of the Journey as In Progress - 2
					$driver_model->update_journey_status($passlog_id,$msg_status,$driver_reply,$travel_status);
					echo "<span class='btn btn-mini btn-danger'>I AM HIRED</span>";		
					echo "<br/>From : ".$availablity[0]['current_location'];
					echo "<br/>To : ".$availablity[0]['drop_location'];
					
				}
				
				if($availablity[0]['travel_status'] == 2)
				{
					
					
					$driver_model = Model::factory('driver');
					$current_location = $availablity[0]['current_location'];				
					$drop_location = $availablity[0]['drop_location'];				
					$passlog_id = $availablity[0]['passengers_log_id'];				
					$msg_status = $availablity[0]['msg_status'];				
					$driver_reply = $availablity[0]['driver_reply'];	
					$travel_status = 2;				

					
					/*echo "|";
						echo "<br><input type='hidden' id='passlog_id' value='".$passlog_id."'><input type='hidden' id='fromText1' value='".$current_location."'><input type='hidden' id='toText1' value='".$drop_location."'><div class='btn-group'><ul><li><button class='btn btn-mini btn-primary' id='journey_started' disabled>Journey Started</button></li>";
						echo "<li>
						
						<br><button class='btn btn-mini btn-success' onclick='Example1.Timer.toggle();' id='waiting_btn'>Start/Stop - Waiting Time</button></li><br>
						
						<button class='btn btn-mini btn-info' onclick=javascript:journey_completed('".$passlog_id."','".$current_location."','".$drop_location."'); >Completed</button></li>
						
						</ul>
						</div>";
					echo "|";*/
					echo "<span class='btn btn-mini btn-danger'>I AM HIRED</span>";
					echo "<br/>From : ".$availablity[0]['current_location'];
					echo "<br/>To : ".$availablity[0]['drop_location'];
				}	
				
				if($availablity[0]['travel_status'] == 4)
				{										
					$driver_model = Model::factory('driver');
					$current_location = $availablity[0]['current_location'];				
					$drop_location = $availablity[0]['drop_location'];				
					$passlog_id = $availablity[0]['passengers_log_id'];				
					$msg_status = $availablity[0]['msg_status'];				
					$driver_reply = $availablity[0]['driver_reply'];	
					$travel_status = 4;				
				echo "<span class='btn btn-mini btn-primary'>I AM FREE</span><br><br><div id='breakstatus'>
						<span class='btn btn-mini btn-warning' onclick='driverbreak(1)'>BREAK IN</span></div>";					
					
				}
					
					
					
					
					$lat = 11;
					$long = 76.9613;
					$radius = 5;
					$status = 'A';
					$driver_status = $Commonmodel->set_status_driver($lat,$long,$radius,$driverid,$status);
					$dat_arr = explode("$",$driver_status);	
					echo "<input type='hidden' id='latitude' value=".$dat_arr[0].">";
					echo "<input type='hidden' id='longitude' value=".$dat_arr[1].">";
				}
				
				
				
				
			}	
			else
			{
				$userid =$this->session->get('id'); 
				$driver_model = Model::factory('driver');
				$driver_current_status = $driver_model->get_driver_current_status($userid);
				//print_r($driver_current_status);
				if(isset($userid))
				{
					if(count($driver_current_status) > 0)
					{
						if($driver_current_status[0]->status == 'B')
						{
							echo "<span class='btn btn-mini btn-primary'>I AM IN BREAK</span><br><br><div id='breakstatus'>
							<span class='btn btn-mini btn-warning' onclick='driverbreak(0)'>BREAK OUT</span><br>
							</div>";
							echo "<input type='hidden' id='latitude' value=".$driver_current_status[0]->latitude.">";
							echo "<input type='hidden' id='longitude' value=".$driver_current_status[0]->longitude.">";	
						}
						else if($driver_current_status[0]->status == 'S')
						{
							echo "<span class='btn btn-mini btn-primary'>I AM IN SERVICE</span><br><br><div id='breakstatus'>
							<span class='btn btn-mini btn-warning' onclick='driverbreak(0)'>SERVICE OUT</span><br>
							</div>";
							echo "<input type='hidden' id='latitude' value=".$driver_current_status[0]->latitude.">";
							echo "<input type='hidden' id='longitude' value=".$driver_current_status[0]->longitude.">";	
						}
						else				
						{
							echo "<span class='btn btn-mini btn-primary'>I AM FREE</span><br><br><div id='breakstatus'>
							<span class='btn btn-mini btn-warning' onclick='driverbreak(1)'>BREAK IN</span></div>";
							$lat = 11;
							$long = 76.9613;
							$radius = 5;
							$status = 'F';
							$driver_status = $Commonmodel->set_status_driver($lat,$long,$radius,$driverid,$status);
							$dat_arr = explode("$",$driver_status);	

							echo "<input type='hidden' id='latitude' value=".$dat_arr[0].">";
							echo "<input type='hidden' id='longitude' value=".$dat_arr[1].">";
							
						}	
					}
					else
					{
						echo "<span class='btn btn-mini btn-primary'>I AM FREE</span><br><br><div id='breakstatus'>
						<span class='btn btn-mini btn-warning' onclick='driverbreak(1)'>BREAK IN</span></div>";
						$lat = 11;
						$long = 76.9613;
						$radius = 5;
						$status = 'F';
						$driver_status = $Commonmodel->set_status_driver($lat,$long,$radius,$driverid,$status);
						$dat_arr = explode("$",$driver_status);	
							echo "<input type='hidden' id='latitude' value=".$dat_arr[0].">";
							echo "<input type='hidden' id='longitude' value=".$dat_arr[1].">";
					}	
				}else{
					//For Admin,Company and Manger will See the driver Status
					$driver_current_status = $driver_model->get_driver_current_status($driverid);
					if($driver_current_status[0]->status == 'B')
					{
						echo "<span class='btn btn-mini btn-primary'>I AM IN BREAK</span><br><br><div id='breakstatus'>
						<span class='btn btn-mini btn-warning' >BREAK IN</span></div>";
						echo "<input type='hidden' id='latitude' value=".$driver_current_status[0]->latitude.">";
						echo "<input type='hidden' id='longitude' value=".$driver_current_status[0]->longitude.">";	
					}
					else if($driver_current_status[0]->status == 'S')
					{
						echo "<span class='btn btn-mini btn-primary'>I AM IN SERVICE</span><br><br><div id='breakstatus'>
						<span class='btn btn-mini btn-warning' onclick='driverbreak(0)'>SERVICE IN</span><br>
						</div>";
						echo "<input type='hidden' id='latitude' value=".$driver_current_status[0]->latitude.">";
						echo "<input type='hidden' id='longitude' value=".$driver_current_status[0]->longitude.">";	
					}
					else					
					{
						echo "<span class='btn btn-mini btn-warning'>I AM FREE</span>";
						$lat = 11;
						$long = 76.9613;
						$radius = 5;
						$status = 'F';
						$driver_status = $Commonmodel->set_status_driver($lat,$long,$radius,$driverid,$status);
						$dat_arr = explode("$",$driver_status);	
				
						echo "<input type='hidden' id='latitude' value=".$dat_arr[0].">";
						echo "<input type='hidden' id='longitude' value=".$dat_arr[1].">";
						
					}	
				}
			}		
			
		}
		exit;
	}
	//Admin Push Notification for Tracking Driver		
	public function action_adminpush_notification()
	{
		if(isset($_POST["driver_id"]))
		{
			$driverid = $_POST["driver_id"];
			
			$Commonmodel = Model::factory('Commonmodel');
			$driver_model = Model::factory('driver');
			if(isset($_POST["datetime"]))
			{ 
				$getcurrenttime = $_POST["datetime"];
				$availablity = $driver_model->check_driver_travel_availability($driverid,$getcurrenttime);
				
			}
			else
			{
				$availablity = $driver_model->check_driver_travel_availability($driverid,date('Y-m-d H:i:s'));
				
			}
			$get_current_trip_logs = $driver_model->get_current_trip_logs($driverid);
			
			$html ="";
			$val="";
			//echo $ch;exit;
			if(isset($_POST["type"]))
			{
				$dri = $Commonmodel->get_driver_currentstatus($driverid);
				if(!empty($dri))
				{
					if($dri=="IN")
					{
						//$val=' -- '.__('shift_in');
						$val=' -- '.__('online');
					}
					elseif($dri=="OUT")
					{
						//$val=' -- '.__('shift_out');
						$val=' -- '.__('offline');
					}
				}
		    }
		    $driver_current_status = $driver_model->get_driver_current_status($driverid);		  
			$update_time=isset($driver_current_status[0]->update_date) ? "  Last Updated : ".Commonfunction::getDateTimeFormat($driver_current_status[0]->update_date,1):"";
			//print_r($availablity);//exit;
			//Driver Status Need to Change as Active
			if(count($availablity) > 0)
			{
						
							$current_location = $availablity[0]['current_location'];				
							$drop_location = $availablity[0]['drop_location'];				
							$passlog_id = $availablity[0]['passengers_log_id'];				
							$msg_status = $availablity[0]['msg_status'];				
							$driver_reply = $availablity[0]['driver_reply'];				
							//$travel_status = 2;		
							
							//echo "|";
								//echo "<br><input type='hidden' id='passlog_id' value='".$passlog_id."'><input type='hidden' id='fromText1' value='".$current_location."'><input type='hidden' id='toText1' value='".$drop_location."'>";
							//echo "|";	
							
							//Change The Travel Status of the Journey as In Progress - 2
							//$driver_model->update_journey_status($passlog_id,$msg_status,$driver_reply,$travel_status);
							echo "<span class='btn btn-mini btn-danger'>I AM HIRED $val</span>$update_time";
							
							if(count($get_current_trip_logs)>0) { 
								$distance = $get_current_trip_logs[0]['distance'];
							echo "<a id='complete_button' class='btn btn-mini ' style='margin-left:15px;background:#3C85BA !important;color:white;cursor:default !important;' onclick='show_compopup();'>COMPLETE TRIP</a>";	
							echo "<span class='btn btn-mini btn-primary'>Distance so far - $distance</span>";		
							}
							
							echo "<br/>From : ".$availablity[0]['current_location'];
							echo "<br/>To : ".$availablity[0]['drop_location'];
							$driver_current_status = $driver_model->get_driver_current_status($driverid);
							echo "<input type='hidden' id='latitude' value=".$driver_current_status[0]->latitude.">";
							echo "<input type='hidden' id='longitude' value=".$driver_current_status[0]->longitude.">";	

			}	
			else
			{
					$driver_current_status = $driver_model->get_driver_current_status($driverid);
					if($driver_current_status[0]->status == 'B')
					{
						echo "<span class='btn btn-mini btn-primary'>".__('i_am_busy').$val."</span>$update_time<br><br><div id='breakstatus'>
						<span class='btn btn-mini btn-warning' >".__('break_in')."</span></div>";
						echo "<input type='hidden' id='latitude' value=".$driver_current_status[0]->latitude.">";
						echo "<input type='hidden' id='longitude' value=".$driver_current_status[0]->longitude.">";	
					}else if($driver_current_status[0]->status == 'A')
					{
						echo "<span class='btn btn-mini btn-primary'>".__('i_am_active').$val."</span><br><br>";
						echo "<input type='hidden' id='latitude' value=".$driver_current_status[0]->latitude.">";
						echo "<input type='hidden' id='longitude' value=".$driver_current_status[0]->longitude.">";	
					}
					else if($driver_current_status[0]->status == 'S')
					{
						echo "<span class='btn btn-mini btn-primary'>".__('i_am_service').$val."</span>$update_time<br><br><div id='breakstatus'>
						<span class='btn btn-mini btn-warning' onclick='driverbreak(0)'>SERVICE IN</span><br>
						</div>";
						echo "<input type='hidden' id='latitude' value=".$driver_current_status[0]->latitude.">";
						echo "<input type='hidden' id='longitude' value=".$driver_current_status[0]->longitude.">";	
					}
					else					
					{
						echo "<span class='btn btn-mini btn-warning'>".__('i_am_free').$val."</span>$update_time";					
						echo "<input type='hidden' id='latitude' value=".$driver_current_status[0]->latitude.">";
						echo "<input type='hidden' id='longitude' value=".$driver_current_status[0]->longitude.">";	
						
					}	
			}		
			
		}
		exit;
	}
	public function action_show_progress_driver()
	{
		if(isset($_POST["driver_id"]))
		{
			$driverid = $_POST["driver_id"];
			$current_datetime = convert_timezone('now',TIMEZONE);
			$Commonmodel = Model::factory('Commonmodel');
			$availablity = $Commonmodel->get_driver_availability($driverid,COMPANY_CID,$current_datetime);
						
			if(count($availablity) > 0)
			{				
				if($availablity[0]['travel_status'] == 2)
				{										
					$driver_model = Model::factory('driver');
					$current_location = $availablity[0]['current_location'];				
					$drop_location = $availablity[0]['drop_location'];				
					$passlog_id = $availablity[0]['passengers_log_id'];				
					$msg_status = $availablity[0]['msg_status'];				
					$driver_reply = $availablity[0]['driver_reply'];				
					$travel_status = 2;		
					
					echo "|";
						echo "<br><input type='hidden' id='passlog_id' value='".$passlog_id."'><input type='hidden' id='fromText1' value='".$current_location."'><input type='hidden' id='toText1' value='".$drop_location."'>";
					echo "|";	
					
				}			
			
			}
		}
		exit;
	}
	
	//Change the Latitude of the Driver
	public function action_set_status_driver()	
	{
		if(isset($_POST["latitude"]))
		{
			$find_model = Model::factory('find');
			$latitude = $_POST["latitude"];
			$longitude = $_POST["longitude"];
			$miles = $_POST["miles"];
			$id = $_POST["id"];
			
			$find_model->set_status_driver($latitude,$longitude,$miles,$id);
			
			
			exit;
		}
	}
	
	
	public function action_transactionlog()
	{
		$errors = array();	
		$this->is_login(); 
		$this->is_login_status(); 	
		$userid =$this->session->get('id'); 
		
		$dashstyles = array(CSSPATH.'dashboard/bootstrap-responsive.css' =>'screen',
							CSSPATH.'dashboard/charisma-app.css' =>'screen',
							CSSPATH.'dashboard/jquery-ui-1.8.21.custom.css' =>'screen',
							CSSPATH.'dashboard/chosen.css' =>'screen',
							CSSPATH.'dashboard/uniform.default.css' =>'screen',
							CSSPATH.'dashboard/jquery.noty.css' =>'screen',
							CSSPATH.'dashboard/noty_theme_default.css' =>'screen',
							CSSPATH.'dashboard/jquery.iphone.toggle.css' =>'screen',
							CSSPATH.'dashboard/opa-icons.css' =>'screen',
							CSSPATH.'datepicker.css'=>'screen');
							
		$dashscripts = array(SCRIPTPATH.'dashboard/jquery-ui-1.8.21.custom.min.js',
							SCRIPTPATH.'dashboard/bootstrap-transition.js',
							SCRIPTPATH.'dashboard/bootstrap-alert.js',
							SCRIPTPATH.'dashboard/bootstrap-modal.js',
							SCRIPTPATH.'dashboard/bootstrap-dropdown.js',
							SCRIPTPATH.'dashboard/bootstrap-scrollspy.js',
							SCRIPTPATH.'dashboard/bootstrap-tab.js',
							SCRIPTPATH.'dashboard/bootstrap-tooltip.js',
							SCRIPTPATH.'dashboard/bootstrap-popover.js',
							SCRIPTPATH.'dashboard/bootstrap-button.js',
							SCRIPTPATH.'dashboard/bootstrap-collapse.js',
							SCRIPTPATH.'dashboard/bootstrap-tour.js',
							SCRIPTPATH.'dashboard/jquery.cookie.js',
							SCRIPTPATH.'dashboard/jquery.dataTables.min.js',
							SCRIPTPATH.'dashboard/jquery.chosen.min.js',
							SCRIPTPATH.'dashboard/jquery.noty.js',
							SCRIPTPATH.'dashboard/jquery.iphone.toggle.js',
							SCRIPTPATH.'dashboard/jquery.history.js',
							SCRIPTPATH.'dashboard/charisma.js',
							SCRIPTPATH.'highcharts.js',
							SCRIPTPATH.'bootstrap-datepicker.js',
							SCRIPTPATH.'kkcountdown.js',
							SCRIPTPATH.'jquery-countdown.js',
									);	

		$manage_transaction = Model::factory('transaction');
		
		//pagination loads here
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		
		$all_transaction_list = $manage_transaction->front_driver_transaction_details('','',$userid,'','',$offset, REC_PER_PAGE);

		$view=View::factory(USERVIEW.'driver/transactionlog')
				->bind('dashstyles',$dashstyles)
				->bind('dashscripts',$dashscripts)
				->bind('pag_data',$pag_data)
				->bind('all_transaction_list',$all_transaction_list)
				->bind('errors', $errors);
		
		$this->template->content = $view;		
	    $this->template->meta_desc = $this->meta_description;
        $this->template->meta_keywords = $this->meta_keywords;
        $this->template->title =$this->title;
	}
	
	//Function for Update Driver Break Status
	public function action_update_break_status()
	//Get appropriate data for Updating the Driver Status
	{
	
		$userid =$this->session->get('id'); 
		$driver_model = Model::factory('driver');
		$Commonmodel = Model::factory('Commonmodel');	
		$getTaxiforDriver = $driver_model->getTaxiforDriver($userid);
		$driver_reply = $driver_model->update_driver_break_status($userid,$_POST['breakstatus']);
			if($_POST['breakstatus'] == '1')
			{
				$reason = $_POST['reason'];
				$interval_type = $_POST['interval_type'];
				$driver_reply = $driver_model->update_driver_break_status($userid,$_POST['breakstatus'],$interval_type);
				$insert_array = array(
									"driver_id" => $userid,
									"taxi_id" 			=> $getTaxiforDriver[0]['mapping_taxiid'],
									"interval_type" 	=> $interval_type,
									"interval_start" 	=> $this->currentdate,
									"interval_end"		=> "",
									"reason"		=> $reason,
									"createdate"		=> $this->currentdate,
								);
								
				//Inserting to Transaction Table 
				$transaction = $Commonmodel->insert(DRIVERBREAKSERVICE,$insert_array);
				$inserid = $transaction[0];
				$this->session->set("breakstatus_id",$inserid);
				if($_POST['interval_type'] == 'B')
				{
				$html = "<span class='btn btn-mini btn-warning' onclick='driverbreak(0)'>BREAK OUT</span>";
				}
				else
				{
				$html = "<span class='btn btn-mini btn-warning' onclick='driverbreak(0)'>SERVICE OUT</span>";
				}
			}
			else
			{
				$update_arrary  = array("interval_end" => $this->currentdate);
				$inserid = $this->session->get("breakstatus_id");
				$this->session->set("breakstatus_id","");
				$transaction = $Commonmodel->update(DRIVERBREAKSERVICE,$update_arrary,'driver_break_service_id',$inserid);
				$get_current_status = $Commonmodel->get_driver_current_break_status($inserid);
				if($get_current_status[0]['interval_type'] == 'S')
				{
				$html = "<span class='btn btn-mini btn-warning' onclick='driverbreak(1)'>SERVICE IN</span>";
				}
				else
				{
				$html = "<span class='btn btn-mini btn-warning' onclick='driverbreak(1)'>BREAK IN</span>";
				}
			}
			echo $html;
			exit;
	}

	public function action_get_current_driverstatus()
	{
		$userid =$this->session->get('id'); 
		$driver_model = Model::factory('driver');

		$driver_current_status = $driver_model->get_driver_current_status($userid);

		if(count($driver_current_status) > 0)
		{					
			echo $driver_current_status[0]->shift_status;exit;
		}
		
	}	
	//Function for Update Driver Break Status
	public function action_update_shift_status()
	//Get appropriate data for Updating the Driver Status
	{	
		$userid =$this->session->get('id'); 
		$driver_model = Model::factory('driver');
		$Commonmodel = Model::factory('Commonmodel');	//
		$getTaxiassignedforDriver = $driver_model->get_assignedtaxi_list($userid);
		$current_time = convert_timezone('now',TIMEZONE);

		$company_status = $driver_model->driver_companystatus($userid);	

		if(($company_status == 'D' ) || ($company_status == 'T')){
			echo $html = __('login_deactive');
			//return;
			exit;
		}

		
	if($_POST['shiftstatus'] == '1')
	{
		if(count($getTaxiassignedforDriver)>0)
		{
		$getTaxiforDriver = $driver_model->getTaxiforDriver($userid);		
		//print_r($getTaxiforDriver);exit;
			if($_POST['shiftstatus'] == '1')
			{
				$reason = "";//$_POST['reason'];	
				 if(count($getTaxiforDriver)>0)
				{ 	
					//echo "sfs";	
				
	
				$driver_reply = $driver_model->update_driver_shift_status($userid,$_POST['shiftstatus']);
				$insert_array = array(
									"driver_id" => $userid,
									"taxi_id" 			=> $getTaxiforDriver[0]['mapping_taxiid'],								
									"shift_start" 	=> $current_time,
									"shift_end"		=> "",
									"reason"		=> $reason,
									"createdate"		=> $current_time,
								);								
				//Inserting to Transaction Table 
				$transaction = $Commonmodel->insert(DRIVERSHIFTSERVICE,$insert_array);
				$inserid = $transaction[0];
				$this->session->set("shiftstatus_id",$inserid);
				if($_POST['shiftstatus'] == '1')
				{
				$html = "<span class='btn btn-mini btn-success' onclick='drivershift(0)'>".__('shift_out')."</span>
				<input name='shift_current_status' id='shift_current_status' type='hidden' value='IN'>";
				}
				else
				{
				$html = "<span class='btn btn-mini btn-danger' onclick='drivershift(0)'>".__('shift_in')."</span>
				<input name='shift_current_status' id='shift_current_status' type='hidden' value='OUT'>";
				}
			   }
			   else
			   {
					echo $html = __('taxi_not_assigned');
					//return;
					exit;				   
			   } 
				
			}
			else
			{
			$driver_current_status = $driver_model->get_driver_current_status($userid);
				//print_r($driver_current_status);
				//exit;
				if(count($driver_current_status) > 0)
				{						
					if($driver_current_status[0]->status !='A')
					{
						$update_arrary  = array("shift_end" => $current_time);
						$inserid = $this->session->get("shiftstatus_id");
						$this->session->set("shiftstatus_id","");
						$transaction = $Commonmodel->update(DRIVERSHIFTSERVICE,$update_arrary,'driver_shift_id',$inserid);
						$driver_reply = $driver_model->update_driver_shift_status($userid,'0');
						//$get_current_status = $Commonmodel->get_driver_current_shift_status($inserid);
						$html = "<span class='btn btn-mini btn-danger' onclick='drivershift(1)'>".__('shift_in')."</span>
						<input name='shift_current_status' id='shift_current_status' type='hidden' value='OUT'>";
					}
					else
					{
						echo $html = "<span class='btn btn-mini btn-success' onclick='drivershift(0)'>".__('shift_out')."</span>
				<input name='shift_current_status' id='shift_current_status' type='hidden' value='IN'><br>".__('driver_in_trip');
						exit;
					}
				}
			}
			echo $html;
			exit;
		}
		else
		{
			echo $html = __('taxi_not_assigned');
			exit;
		}

		}
		else
		{

				$driver_current_status = $driver_model->get_driver_current_status($userid);
					//print_r($driver_current_status);
					//exit;
					if(count($driver_current_status) > 0)
					{						
						if($driver_current_status[0]->status !='A')
						{
							$update_arrary  = array("shift_end" => $current_time);
							$inserid = $this->session->get("shiftstatus_id");
							$this->session->set("shiftstatus_id","");
							$transaction = $Commonmodel->update(DRIVERSHIFTSERVICE,$update_arrary,'driver_shift_id',$inserid);
							$driver_reply = $driver_model->update_driver_shift_status($userid,'0');
							//$get_current_status = $Commonmodel->get_driver_current_shift_status($inserid);
							$html = "<span class='btn btn-mini btn-danger' onclick='drivershift(1)'>".__('shift_in')."</span>
							<input name='shift_current_status' id='shift_current_status' type='hidden' value='OUT'>";
						}
						else
						{
							echo $html = "<span class='btn btn-mini btn-success' onclick='drivershift(0)'>".__('shift_out')."</span>

					<input name='shift_current_status' id='shift_current_status' type='hidden' value='IN'><br>".__('driver_in_trip');
							exit;
						}
					}

				echo $html;
				exit;
		}
	
	}
		
	//DashoBoard Settings Function
	public function action_completedtrips()
	{
		$errors = array();	
		$this->is_login(); $this->is_login_status(); 		
		//$this->is_login(); 	
		/*** Dashboard Style & Scripts ***/
		$dashstyles = array(CSSPATH.'dashboard/bootstrap-responsive.css' =>'screen',
							CSSPATH.'dashboard/charisma-app.css' =>'screen',
							CSSPATH.'dashboard/jquery-ui-1.8.21.custom.css' =>'screen',
							CSSPATH.'dashboard/chosen.css' =>'screen',
							CSSPATH.'dashboard/uniform.default.css' =>'screen',
							CSSPATH.'dashboard/jquery.noty.css' =>'screen',
							CSSPATH.'dashboard/noty_theme_default.css' =>'screen',
							CSSPATH.'dashboard/jquery.iphone.toggle.css' =>'screen',
							CSSPATH.'dashboard/opa-icons.css' =>'screen',
							CSSPATH.'datepicker.css'=>'screen');
							
		$dashscripts = array(SCRIPTPATH.'dashboard/jquery-ui-1.8.21.custom.min.js',
							SCRIPTPATH.'dashboard/bootstrap-transition.js',
							SCRIPTPATH.'dashboard/bootstrap-alert.js',
							SCRIPTPATH.'dashboard/bootstrap-modal.js',
							SCRIPTPATH.'dashboard/bootstrap-dropdown.js',
							SCRIPTPATH.'dashboard/bootstrap-scrollspy.js',
							SCRIPTPATH.'dashboard/bootstrap-tab.js',
							SCRIPTPATH.'dashboard/bootstrap-tooltip.js',
							SCRIPTPATH.'dashboard/bootstrap-popover.js',
							SCRIPTPATH.'dashboard/bootstrap-button.js',
							SCRIPTPATH.'dashboard/bootstrap-collapse.js',
							SCRIPTPATH.'dashboard/bootstrap-tour.js',
							SCRIPTPATH.'dashboard/jquery.cookie.js',
							SCRIPTPATH.'dashboard/jquery.dataTables.min.js',
							SCRIPTPATH.'dashboard/jquery.chosen.min.js',
							SCRIPTPATH.'dashboard/jquery.noty.js',
							SCRIPTPATH.'dashboard/jquery.iphone.toggle.js',
							SCRIPTPATH.'dashboard/jquery.history.js',
							SCRIPTPATH.'dashboard/charisma.js',
							SCRIPTPATH.'highcharts.js',
							SCRIPTPATH.'bootstrap-datepicker.js',
							SCRIPTPATH.'kkcountdown.js',
							SCRIPTPATH.'jquery-countdown.js',
									);		
		$driver_model = Model::factory('driver');			
		$driver_id =$this->session->get('id');
		$id =$driver_id;

		//Getting all Completed driver logs total
		$get_driver_total_logs_completed_transaction = $driver_model->get_driver_total_logs_completed_transaction($driver_id,'R','A','1');	

		//pagination loads here
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);
		
		$pag_data = Pagination::factory(array (
		'current_page'   => array('source' => 'query_string','key' => 'page'),
		  'items_per_page' => REC_PER_PAGE,
		'total_items'    => count($get_driver_total_logs_completed_transaction),
		'view' => 'pagination/punbb',
		));

		//Getting all Completed driver logs
		$driver_logs_completed_transaction = $driver_model->get_driver_logs_completed_transaction($driver_id,'R','A','1',REC_PER_PAGE,$offset);			

		//print_r($driver_logs_completed_transaction);
   		$view= View::factory(USERVIEW.'driver/completedtrips')
					        ->bind('dashstyles',$dashstyles)
					        ->bind('dashscripts',$dashscripts)
					        ->bind('driver_logs_completed_transaction',$driver_logs_completed_transaction)
					        ->bind('get_driver_total_logs_completed_transaction',$get_driver_total_logs_completed_transaction)
					        ->bind('pag_data',$pag_data)
					        ->bind('offset',$offset );

	    $this->template->content = $view;
	    $this->template->meta_desc = $this->meta_description;
        $this->template->meta_keywords = $this->meta_keywords;
        $this->template->title =$this->title;
	}

	public function action_resetpassword()
	{

		    $driver = Model::factory('driver');
		     /**To Set Errors Null to avoid error if not set in view**/              
		    $errors = array();

		$siteusers = Model::factory('siteusers');
		/**To get the form submit button name**/

		$country_details = $siteusers->country_details();
		$city_details = $siteusers->city_details();
		$state_details = $siteusers->state_details();

	   	$site_info = $siteusers->get_site_info();
	   	$sitecms_info = $siteusers->get_sitecms_info();
	   	$banner_images = $siteusers->get_banner_images();

		$view=View::factory(USERVIEW.'home')
			->bind('sitecms_info',$sitecms_info)
			->bind('banner_images',$banner_images)
			->bind('country_details',$country_details)
			->bind('city_details',$city_details)
			->bind('state_details',$state_details)
			->bind('site_info',$site_info);
		
		    /**To generate random key if user enter email at forgot password**/
		    $random_key = text::random($type = 'alnum', $length = 7);


		    if (isset($_REQUEST)) 
		    {

				    $email_exist = $driver->check_phone_passengers($_REQUEST['phone_no']);
				    if($email_exist == 1)
				    { 		
						$validator = '';			
					       $result=$driver->forgot_password_phone($validator,$_REQUEST,$random_key);
						    if($result)
						    { 
						
   						
                                   		$mail="";		
									    $replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_USERNAME=>ucfirst($result[0]['name']),REPLACE_MOBILE=>$result[0]['phone'],REPLACE_PASSWORD=>$random_key,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE,SITE_DESCRIPTION=>$this->app_description,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
						    $message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'user-forgotpassword.html',$replace_variables);


					$to = $result[0]['email'];
					$from = CONTACT_EMAIL;
					$subject = __('forgot_password_subject')." - ".COMPANY_SITENAME;	
					$redirect = "";	
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
					$message_details = $common_model->sms_message('2');
					$to = $result[0]['phone'];
					$message = $message_details[0]['sms_description'];
				        $message = str_replace("##PASSWORD##",$random_key,$message);
			
					//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
					}

							    Message::success(__('sucessful_forgot_password'));
							    $this->request->redirect("/");
							
						    }	
						    else
						    {
							     Message::error(__('Try Again Later'));
							    $this->request->redirect("/");
						    }
				    }
				    else
				    {
					    $email_error=__("email_not_exist");	
				    }
			    $validator = null;

		    }			
	        $this->template->meta_desc = $this->meta_description;
            $this->template->meta_keywords = $this->meta_keywords;
            $this->template->title =$this->title;

			$this->template->content = $view;
    }			

	public function send_mail_passenger($log_id='',$travel_status='')
    {
		/**************************** Mail send to Passenger ***************/   
		$driver_model = Model::factory('driver');
		$passenger_log_details = $driver_model->passenger_transdetails($log_id);
		//print_r($passenger_log_details);exit;
		if(count($passenger_log_details)>0)
		{

				$to = $passenger_log_details[0]['passenger_email'];
				$name = $passenger_log_details[0]['passenger_name'];

			
			$job_referral = $passenger_log_details[0]['job_referral'];
			$location_data = $driver_model->get_location_details($log_id);
			if($location_data)
			{
				$pickup = $location_data[0]['current_location'];
				$drop = $location_data[0]['drop_location'];
				$path = $location_data[0]['active_record'];
				$path=str_replace('],[', '|', $path);
				$path=str_replace(']', '', $path);
				$path=str_replace('[', '', $path);
				$path =explode('|',$path);$path=array_unique($path);
				include_once MODPATH."/email/vendor/polyline_encoder/encoder.php";
				$polylineEncoder = new PolylineEncoder();
				if(count($path) > 0)
				{
					foreach ($path as $values)
					{
						$values = explode(',',$values);
						$polylineEncoder->addPoint($values[0],$values[1]);
						$polylineEncoder->encodedString();
					}
				}
				$encodedString = $polylineEncoder->encodedString();
				$marker_end=$location_data[0]['drop_latitude'].','.$location_data[0]['drop_longitude'];
				$marker_start=$location_data[0]['pickup_latitude'].','.$location_data[0]['pickup_longitude'];
				//$url= "http://maps.google.com/maps/api/staticmap?center=$center&zoom=11&markers=$markers&size=500x300&sensor=TRUE_OR_FALSE&path=color:0x0000ff|weight:5|$path";echo $url;exit;
				$mapurl = "http://maps.googleapis.com/maps/api/staticmap?size=250x250&markers=color:red%7C$marker_start&markers=color:green%7C$marker_end&path=weight:3%7Ccolor:red%7Cenc:$encodedString";
			}
			else
			{
				$mapurl ="";
				$pickup ="";
				$drop="";
			}
			$subtotal='';
			$orderlist='';   
			//$orderlist='<table cellspacing="8" cellpadding="5">';   
			if($travel_status == 4)
			{
				//$orderlist.='<tr style="color:#808080"><td colspan="2" style="color: #161616; font-size: 15px; font-weight: bold"><b>'.__('cancel_trip').'</b></td><td></td></tr>';
			}
			else
			{
				//$orderlist.='<tr style="color:#808080"><td colspan="2" style="color: #161616; font-size: 15px; font-weight: bold"><b>'.__('complete_from').'</b></td><td></td></tr>';
			}
			$used_wallet_amount = (isset($passenger_log_details[0]['used_wallet_amount'])) ? $passenger_log_details[0]['used_wallet_amount'] : 0;	
			$distance_fare = $passenger_log_details[0]['tripfare'] - $passenger_log_details[0]['minutes_fare'];
			//$subtotal= $passenger_log_details[0]['fare'];
			$subtotal= $passenger_log_details[0]['fare']+$used_wallet_amount;
			$payment_mode_value=$passenger_log_details[0]['payment_type'];
			switch($payment_mode_value)
			{
				case 1:
				$payment_mode = __('cash');
				break;
				case 2:
				$payment_mode =__('credit_card');
				break;
				case 3:
				$payment_mode =__('uncard');
				break;
				default:
				$payment_mode =__('account');
			}
			$distance_km=($passenger_log_details[0]['distance']!='')?$passenger_log_details[0]['distance']:'0';
			$trip_minutes=($passenger_log_details[0]['trip_minutes']!='')?$passenger_log_details[0]['trip_minutes']:'0';

			$tips=($passenger_log_details[0]['tips']!='')?$passenger_log_details[0]['tips']:'0';
			
			$distance_fare_row = '';
			if($distance_fare != 0) {
				$distance_fare_row = '<tr><td width="50%"> <p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('distance_fare').'</p></td><td width="50%"><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($distance_fare,2).'</p></td></tr>';
			}
			$minutes_fare_row = '';
			if($passenger_log_details[0]['minutes_fare'] != 0) {
				$minutes_fare_row = '<tr><td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('minutes_fare').'</td><td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($passenger_log_details[0]['minutes_fare'],2).'</p></td></tr>';
			}
			$wallet_row = '';
			if($used_wallet_amount != 0) {
				$wallet_row = '<tr><td><p  style="font:bold 15px/18px arial;margin:0 ;color:#333">'.__('wallet_amount_paid').'</td><td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($used_wallet_amount,2).'</p></td></tr>';
			}
			$evening_fare = '';
			if($passenger_log_details[0]['eveningfare'] != 0) {
				$evening_fare = '<tr><td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('eveningfare').'</td><td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($passenger_log_details[0]['eveningfare'],2).'</p></td></tr>';
			}
			$night_fare = '';
			if($passenger_log_details[0]['nightfare'] != 0) {
				$night_fare = '<tr><td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('nightfare').'</td><td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($passenger_log_details[0]['nightfare'],2).'</p></td></tr>';
			}
			
			$orderlist = '<tr>
		<td  align="left" colspan="2" width="100%" style="padding: 20px 15px 0px 15px;">
		    <table width="100%" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #ddd;">
			<tr>
			    <td valign="top" style="padding: 0;" width="305px;">
				<img src="##MAPURL##" align="top" style="float: left;" />
			    </td>
			    <td align="center" valign="middle">
				 <table width="100%" align="center" cellpadding="0" cellspacing="0">
				     <tr>
					 <td colspan="2" valign="middle" align="center" height="180">
					     <p style="font:normal 14px/18px arial;margin:0 ;color:#333;text-transform: uppercase;">'.__('total_fare').'</p>
				    <p style="font:normal 50px/58px arial;margin:0 ;color:#333;text-transform: uppercase;">'.COMPANY_CURRENCY.' '.round($passenger_log_details[0]['fare'],2).'</p>
				     <p  style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.__('payment_mode').': '.$payment_mode.'</p>
					 </td>
				     </tr>
				     <tr>
					 <td valign="middle" align="center">
					     <p style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.__('distance').'</p>
				    <p style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.$distance_km.'	'.__('km').'</p></td>
					 <td valign="middle" align="center">
					       <p style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.__('trip_minutes').'</p>
				    <p style="font:normal 14px/28px arial;margin:0 ;color:#333;text-transform: uppercase;">'.$trip_minutes.'</p>
					 </td>
				     </tr>
				 </table>
				
			    </td>
			</tr>
		    </table>
		</td>
	    </tr>
	    <tr>
		<td align="left" colspan="2" style="padding: 10px 15px;">
		    <p style="font:normal 18px arial;margin:0;color:#333;border-bottom:1px solid #ddd;padding: 10px 0;text-transform: uppercase">'.__('fare_details').'</p>
		</td>
	    </tr>
	    <tr>
		<td colspan="2" style="padding: 0px 5px;">
		    <table width="100%" align="center" cellpadding="8" cellspacing="0">
			'.$distance_fare_row.$minutes_fare_row.'
			<tr>
			    <td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('waiting_time_cost').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($passenger_log_details[0]['waiting_cost'],2).'</p></td>
			</tr>
			<tr>
			    <td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('waiting_time_hours').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.$passenger_log_details[0]['waiting_time'].'</p></td>
			</tr>'.$evening_fare.$night_fare.'

			<tr>
			    <td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('Tips').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($tips,2).'</p></td>
			</tr>

			
			<tr>
			    <td><p  style="font:normal 15px/18px arial;margin:0 ;color:#333">'.__('tax').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($passenger_log_details[0]['tax_amount'],2).'</p></td>
			</tr>
			
			<tr>
			    <td><p  style="font:bold 15px/18px arial;margin:0 ;color:#333">'.__('sub_total').'</td>
			    <td><p style="font:normal 14px/18px arial;margin:0 ;color:#000">'.COMPANY_CURRENCY.' '.round($subtotal,2).'</p></td>
			</tr>'.$wallet_row.'
			
		    </table>
		</td>
	    </tr>
	    <tr>
	    
		<td align="left" colspan="2" style="padding: 10px 15px;">
		    <p style="font:normal 18px arial;margin:0;color:#333;border-bottom:1px solid #ddd;padding: 10px 0;text-transform: uppercase">'.__('booking_details').'</p>
		</td>
	    </tr>
	    <tr>
		<td style="background: #fff;padding: 10px 15px;" valign="top">
		    <b  style="font:bold 14px/20px arial;margin:0;color:#000">'.__('Current_Location').'</b>
		    <p  style="font:normal 13px/18px arial;margin:3px 0 0 0 ;color:#000">'.$passenger_log_details[0]['current_location'].'</p>
		</td>
		<td style="background: #fff;padding:15px;" valign="top">
		    <b  style="font:bold 14px/20px arial;margin:0;color:#000">'.__('Drop_Location').'</b>
		    <p  style="font:normal 13px/18px arial;margin:3px 0 0 0 ;color:#000">'.$passenger_log_details[0]['drop_location'].'</p>
		</td>
	    </tr>';

			$mail="";								
			$replace_variables=array(
				REPLACE_LOGO=>EMAILTEMPLATELOGO,
				REPLACE_SITENAME=>COMPANY_SITENAME,
				REPLACE_USERNAME=>$name,
				REPLACE_EMAIL=>$to,
				REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',
				REPLACE_SITEEMAIL=>CONTACT_EMAIL,
				REPLACE_SITEURL=>URL_BASE,
				REPLACE_ORDERID=>$log_id,
				REPLACE_ORDERLIST=>$orderlist,
				REPLACE_MAPURl=>$mapurl,
				REPLACE_COMPANYDOMAIN=>'',
				REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,
				REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR
			);

			/* Added for language email template */
			if($this->lang!='en'){
			if(file_exists(DOCROOT.TEMPLATEPATH.$this->lang.'/tripcomplete-mail-'.$this->lang.'.html')){
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.$this->lang.'/tripcomplete-mail-'.$this->lang.'.html',$replace_variables);
			}else{
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcomplete-mail.html',$replace_variables);
			}
			}else{
			$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'tripcomplete-mail.html',$replace_variables);
			}
			/* Added for language email template */

			//echo $message;exit;

			$from = $this->siteemail;
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
			
			$msg_status = 'R';$driver_reply='A';$journey_status=1; // Waiting for Payment
			$journey = $driver_model->update_journey_status($log_id,$msg_status,$driver_reply,$journey_status);
		}
		/**************************** Mail send to Passenger ***************/
    }


	/*****Cancel Trip *****/	
	public function action_driver_cancel_trip()
	{		
		$commonmodel = Model::factory('commonmodel');
		$driver_model = Model::factory('driver');
		$emailtemplate = Model::factory('emailtemplate');

		$trip_id = $_REQUEST['trip_id'];
		$comments = isset($_REQUEST['comments']) ? $_REQUEST['comments'] : "";
		$get_pass_log_details = $driver_model->get_passenger_log($trip_id);

		//print_r($get_pass_log_details);exit;
		if(count($get_pass_log_details) > 0)
		{
			$passenger_id = $get_pass_log_details[0]['passengers_id'];
			$driver_id = $get_pass_log_details[0]['driver_id'];
			$taxi_id = $get_pass_log_details[0]['taxi_id'];
			$company_id = $get_pass_log_details[0]['company_id'];
			$waiting_time = $get_pass_log_details[0]['waitingtime'];
			$travel_status = $get_pass_log_details[0]['travel_status'];
			
			if($travel_status == 2 || $travel_status == 5)
			{
				echo "2";exit;
			}
			//cancel the trip by driver
			elseif($travel_status == 9 || $travel_status == 3)	
			{	
				$customer_android_key = $commonmodel->select_site_settings('customer_android_key',SITEINFO);
				$driver_android_key = $commonmodel->select_site_settings('driver_android_key',SITEINFO);

				$driver_reply = 'C';
				if(TIMEZONE)
				{
					$current_time = convert_timezone('now',TIMEZONE);
				}
				else
				{
					$current_time =	date('Y-m-d H:i:s');
				}

				/******Update cancelled date*****/
				if(!empty($comments))
					$update_array  = array("driver_reply"=>$driver_reply,"cancelled_date"=>$current_time,'trip_completed_by' => 2);
				else
					$update_array  = array("driver_reply"=>$driver_reply,"cancelled_date"=>$current_time,'comments'=>$comments,'trip_completed_by' => 2);

				$cancelled_date_result = $driver_model->update_table(PASSENGERS_LOG,$update_array,'passengers_log_id',$trip_id);
				/*****End Cancelled date update***/

				//send passenger push notification
				$passenger_device_id = isset($get_pass_log_details[0]['passenger_device_id']) ? $get_pass_log_details[0]['passenger_device_id'] : '';
				$passenger_device_token = isset($get_pass_log_details[0]['passenger_device_token']) ? $get_pass_log_details[0]['passenger_device_token'] : '';
				$passenger_device_type = isset($get_pass_log_details[0]['passenger_device_type']) ? $get_pass_log_details[0]['passenger_device_type'] : '';

				if(!empty($passenger_device_type) && !empty($passenger_device_token))
				{
					$push_message = array("message"=>__('trip_cancelled_driver'),"trip_id"=>$trip_id,"status"=>9);
					$p_send_notification = $this->send_passenger_mobile_pushnotification($passenger_device_token,$passenger_device_type,$push_message,$customer_android_key);
				}
				//send passenger push notification

				//send driver push notification
				$driver_device_id = isset($get_pass_log_details[0]['driver_device_id']) ? $get_pass_log_details[0]['driver_device_id'] : '';
				$driver_device_token = isset($get_pass_log_details[0]['driver_device_token']) ? $get_pass_log_details[0]['driver_device_token'] : '';
				$driver_device_type = isset($get_pass_log_details[0]['driver_device_type']) ? $get_pass_log_details[0]['driver_device_type'] : '';

				$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '0';

				if(!empty($userid))
				{
					$user = $driver_model->get_user_name_by_id($userid);
				}	
				else
				{
					$user = 'Admin';
				}

				if(!empty($driver_device_type) && !empty($driver_device_token))
				{	
					$push_msg_string = "Trip {$trip_id} was cancelled by {$user}"; 
					$push_message = array("message" => $push_msg_string,"status" => 19);
					$send_pushnotification = $this->send_driver_mobile_pushnotification($driver_device_token,$driver_device_type,$push_message,$driver_android_key);
					
				}
				//send driver push notification

				// Email to passenger
				$passenger_name = $get_pass_log_details[0]['passenger_name'];
				$driver_name = $get_pass_log_details[0]['driver_name'];

				$replace_variables = array(REPLACE_LOGO=>URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png',REPLACE_SITENAME=>SITE_NAME,REPLACE_COPYRIGHTS=>COMPANY_COPYRIGHT,REPLACE_PHONE=>ADMIN_PHONE_NUMBER,REPLACE_DRIVERNAME=>$driver_name,REPLACE_SITEEMAIL=>SITE_EMAIL_CONTACT,REPLACE_SITEURL=>URL_BASE,REPLACE_USERNAME=>$passenger_name);
				$message = $emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver_cancel_trip.html',$replace_variables);
				
				$to = $get_pass_log_details[0]['passenger_email'];
				$from = SITE_EMAIL_CONTACT;
				$subject = __('Your booking has been cancelled')." - ".SITE_NAME;
				$redirect = "no";
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
			}
			
			echo "1";exit;
			
		}
		else
			echo "0";exit;
	}
		
    public function send_driver_mobile_pushnotification($d_device_token="",$device_type="",$pushmessage=null,$android_api="",$book_request="")
    {                          
				//---------------------------------- ANDROID ----------------------------------//                            
                           if($device_type == 1)
                           {						  
                            $apiKey = $android_api;
                            //echo $d_device_token;
                            $registrationIDs = array($d_device_token);
                            // Message to be sent                                    
                            if(!empty($registrationIDs))
                            {
                                // Set POST variables
                                $url = 'https://android.googleapis.com/gcm/send';
                                //print_r($registrationIDs);exit;
                                $fields = array(
                                                'registration_ids'  => $registrationIDs,
                                                'data'              => array( "message" => $pushmessage ),
                                                );

                                $headers = array( 
                                                'Authorization: key=' . $apiKey,
                                                'Content-Type: application/json'
                                                );
                               // print_r($pushmessage);exit;
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
                               //echo $result;                            
                            }
                            //exit;  
                         }
                         else if($device_type == 2)
                         {                          
                            //---------------------------------- IPHONE ----------------------------------//  
                            //print_r($contact_iphone);exit;
					$deviceToken = trim($d_device_token);                                                      
					if(!empty($deviceToken))
					{
						
						$passphrase = '';
						// Put your alert message here:
						//$message = $message = "A new business ".$business_name." is added in Yiper";
						//$message = $deal_id.".".ucfirst($merchant_name)." has a new deal for you. View now...";                                    
						$badge = 0;
						

							$root = $_SERVER['DOCUMENT_ROOT'].'/application/classes/controller/driver_deploy.pem' ;
							// echo  $root;
							$ctx = stream_context_create();
							stream_context_set_option($ctx, 'ssl', 'local_cert',$root );
							stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
							// Open a connection to the APNS server
							$fp = stream_socket_client(
								'ssl://gateway.sandbox.push.apple.com:2195', $err,
								$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

						if (!$fp)
							exit("Failed to connect: $err $errstr" . PHP_EOL);
						//echo 'Connected to APNS' . PHP_EOL;
						// Create the payload body
					

						$status = '1';


						/*$message = 'Driver from '.$distance.' distance. TripID : '.$trip_id;*/
						/*$message = 'Pickup :'.$pickup_address.' , Drop :'.$drop_address;*/


						$subject = $pushmessage;  
						$message = $pushmessage;

						if($status == 1)
						{
						
							$body['aps'] = array(
								'alert' => $message,
								'title' => $subject,	
								'status' => 19,
								'sound' => 'default',
								);
						}


						//print_r($body);
						// exit;
						// Encode the payload as JSON
						$payload = json_encode($body);
						// Build the binary notification
						$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
						// Send it to the server
						$result = fwrite($fp, $msg, strlen($msg));
						/*if (!$result)
						    echo 'Message not delivered' . PHP_EOL;
						else
						echo 'Message successfully delivered' . PHP_EOL;exit;*/
						// Close the connection to the server
						fclose($fp);  
					} 

                        }			
                        else
                        {
							
			}		
		}

	
} 
