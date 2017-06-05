<?php defined('SYSPATH') or die('No direct script access.');

/****************************************************************

* Contains User Management(Users)details

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************************************/

class Controller_TaximobilityAdd extends Controller_Siteadmin 
{

	/**
	****__construct()****
	*/		
	
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
		$this->is_login();		
	}

	public function is_login()
	{ 
		
		$session = Session::instance();

		//get current url and set it into session
		//========================================
		$this->session->set('requested_url', Request::detect_uri());
			
	        /**To check Whether the user is logged in or not**/
		if(!isset($this->session) || (!$this->session->get('userid')) && !$this->session->get('id') )		
		{			
			Message::error(__('login_access'));
			$this->request->redirect("/admin/login/");
		}
		return;
	}

	public function action_motor()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype !='A' && $usertype !='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$add_model = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addmotor'); 
		$errors = array();
		$post_values = array();
		
		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			
			$post = Arr::map('trim', $this->request->post());
			
			$validator = $add_model->validate_addmotor(arr::extract($post,array('companyname')));
			
			if($validator->check())
			{
			   $signup_id=$add_model->addmotor($post);
			   
				if($signup_id == 1) 
				{ 
					Message::success(__('sucessfull_added_motor_company'));

					$this->request->redirect("add/motor");

				}	
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
		
		$view= View::factory('admin/add_motor')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_motor_company');
		$this->template->page_title= __('add_motor_company'); 
		$this->template->content = $view;
	}	
	
	public function action_model()
	{
		$this->request->redirect("manage/model");
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype !='A' && $usertype !='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$add_model = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addmodel'); 
		$errors = array();
		$post_values = array();
		
		$motor_details = $add_model->motor_details();
		
		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			$post = Arr::map('trim', $this->request->post());
			
			$validator = $add_model->validate_addmodel(arr::extract($post,array('companyname','model_name','model_size','waiting_time','base_fare','min_km','min_fare','cancellation_fare','below_and_above_km','below_km','above_km','minutes_fare','night_charge','night_timing_from','night_timing_to','night_fare','evening_charge','evening_timing_from','evening_timing_to','evening_fare')));
			
			if($validator->check())
			{				
			   $signup_id=$add_model->addmodel($post);
			   
				if($signup_id == 1) 
				{ 
					Message::success(__('sucessfull_added_model_company'));

					$this->request->redirect("manage/model");

				}	
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
		
		$view= View::factory('admin/add_model')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values)
				                ->bind('motor_details',$motor_details);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_model');
		$this->template->page_title= __('add_model'); 
		$this->template->content = $view;
	}	
	
	public function action_fare()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype !='C')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$add_fare = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addfare');
		$errors = array();
		$post_values = array();
		
		$motor_details = $add_fare->motor_details();
		$model_details = $add_fare->model_details();


		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
			$exist_models = $add_fare->exist_models($post_values['model_name']);
			if(count($exist_models) == 1)
			{
				Message::error(__('fare_added_already'));
				$this->request->redirect("manage/fare");
			}

			$validator = $add_fare->validate_addfare(arr::extract($post_values,array('model_name','model_size','waiting_time','base_fare','min_fare','cancellation_fare','below_km','above_km','night_charge','night_timing_from','night_timing_to','night_fare','min_km','below_and_above_km','minutes_fare','evening_charge','evening_timing_from','evening_timing_to','evening_fare')));
			
			if($validator->check())
			{

					$signup_id=$add_fare->addfare($post_values);
			   
					if($signup_id == 1) 
					{ 
						Message::success(__('sucessfull_added_fare_company'));

						$this->request->redirect("manage/fare");

					}	

			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
		
		$view= View::factory('admin/add_fare')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values)
				                ->bind('motor_details',$motor_details)
				                ->bind('model_details',$model_details);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_fare');
		$this->template->page_title= __('add_fare'); 
		$this->template->content = $view;
	}
	
	public function action_checkmodel()
	{
		$add_model = Model::factory('add');
		$exist_models = $add_model->exist_models($_REQUEST['modelId']);
		$message = '';
		if(count($exist_models) > 0) {
			$message =  __('fare_added_already');
		}
		echo $message;exit;
	}
	
	public function action_checkdomain()
	{		
		$add_model = Model::factory('add');
		$check_domain_exist = $add_model->checkcompanydomain($_REQUEST["type"]);
		if($check_domain_exist == 0)
		{
			echo '<span style="color:green;">'.__('company_domain_is_avaliable').'</span>'; exit;
		}
		else
		{
			echo '<span style="color:red;">'.__('company_domain_is_exist').'</span>'; exit;
		}
	}
	
	public function action_company()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype !='A' && $usertype !='S' && $usertype !='DA')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$add_model = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addcompany'); 
		
		$country_details = $add_model->country_details();
		$city_details = $add_model->city_details();
		$state_details = $add_model->state_details();
		//$package_details = $add_model->package_details();
		$package_details = array();
		$currencysymbol = $this->currencysymbol;
        $currencycode = $this->all_currency_code;

		$errors = array();
		$post_values = array();
		
		if ($signup_submit && Validation::factory($_POST,$_FILES) ) 
		{
			$post_values = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
		
			$validator = $add_model->validate_addcompany(arr::extract($post_values,array('firstname','lastname','email','password','repassword','phone','address','company_name','domain_name','company_address','country','state','city','currency_code','currency_symbol','time_zone')),$_FILES);
//'paypal_api_username','paypal_api_password','paypal_api_signature','payment_method',
			/*if(!isset($_POST['paymodstatus']))
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
			$check_default = $add_model->check_array($_POST['default']); */
			
			if($validator->check()) //&& ($check_paystatus == 1)
			{
				//print_r($post); exit;
							
				$signup_id = $add_model->addcompany($post_values,$_FILES);
			   
				if($signup_id == 1) 
				{ 
					$mail="";
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_DOMAINNAME=>$post_values['domain_name'],REPLACE_USERNAME=>$post_values['firstname'],REPLACE_EMAIL=>$post_values['email'],REPLACE_PASSWORD=>$post_values['password'],REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'registertemp.html',$replace_variables);
					$to = $_POST['email'];
					$from = CONTACT_EMAIL;
					$subject = __('registration_success');	
					$redirect = "manage/company";	
					$smtp_result = $add_model->smtp_settings();
					
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

					
					//$mail=array("to" => $_POST['email'],"from"=>CONTACT_EMAIL,"subject"=>__('registration_success'),"message"=>$message);				
					
					//$emailstatus=$this->email_send($mail,'smtp');

					Message::success(__('sucessfull_added_company'));
					$this->request->redirect("manage/company");
				}
				else if($signup_id == 2)
				{
					Message::success(__('invalid_image_uploaded'));
					$this->request->redirect("add/company");
				}
			     
			}
			else
			{
				$errors = $validator->errors('errors');
/*
				if($check_paystatus == 0)
				{
					$errors['paymodstatus'] = 'Please select any one of the gateway';
				}
				else if($check_paystatus == 2)	
				{
					$errors['paymodstatus'] = 'Please select the default gateway';
				}*/
			//echo $errors['paymodstatus'];exit;
			}
		}
		//print_r($errors);exit;
				$view= View::factory('admin/add_company')
						->bind('validator', $validator)
						->bind('errors', $errors)
						->bind('country_details',$country_details)
						->bind('city_details',$city_details)
						->bind('state_details',$state_details)
						->bind('package_details',$package_details)
						->bind('postvalue',$post_values)
						->bind('currency_symbol',$currencysymbol)
						->bind('currency_code',$currencycode);
		$this->template->content = $view;
		                
		$this->template->title= SITENAME." | ".__('add_company');
		$this->template->page_title= __('add_company'); 
		$this->template->content = $view;
	}


	public function action_moderator()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype !='A' && $usertype !='S' && $usertype !='DA')
	   	{
	   		$this->request->redirect("admin/login");
	   	}

		$add_model = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addcompany'); 
		$errors = array();
		$post_values = array();
		if ($signup_submit && Validation::factory($_POST,$_FILES) ) 
		{
			$post_values = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
			$validator = $add_model->validate_addmoderator(arr::extract($post_values,array('name','email','sales_person_email','phone','company_name','domain_name','message','time_zone','no_of_taxi')));

			
			if($validator->check())
			{

			$company = Model::factory('company');
			if($post_values['time_zone'])
			{
				//This will convert the time to requested TIMEZONE
				$current_time = convert_timezone('now',$post_values['time_zone']);
			}
			else
			{
				$current_time = date('Y-m-d H:i:s');
			}
			
			$post_values['createdate']=$current_time;
			$companyemail = $post_values['email'];
			// Check whether the company email exist or not. We need to pass email to this function
			$check_email_exist = $company->checkemail($companyemail);
			if($check_email_exist == 0)
			{
				Message::error(__('email_exists'));
				$this->request->redirect("add/moderator");
			}
		// Check whether the company domain exist or not. We need to pass domain name to this function. Domain name used as a Subdomain in the URL
			$add_model = Model::factory('add');
			$check_domain_exist = $add_model->checkcompanydomain($post_values["domain_name"]);
			if($check_domain_exist == 1)
			{
				Message::error(__('company_domain_is_exist'));
				$this->request->redirect("add/moderator");
			}
			/**********************************************/
//exit;
			
// Pass all values to model to save the free trial data. Once company created then all other information will be related to company will be created

			$budget=isset($post_values['budget'])?$post_values['budget']:'-';
			$ip=$_SERVER['REMOTE_ADDR'];
			// Get city and country details
                      $url = "http://api.ipinfodb.com/v3/ip-country/?key=".IPINFOAPI_KEY."&ip=$ip";
               $data = @file_get_contents($url);
               $dat = explode(";",$data);
              
			$city_name = isset($dat[2])?$dat[2]:"";//$company->get_city_name($post['city']);
			$country_name = isset($dat[3])?$dat[3]:"";//$company->get_country_name($post['country']);
			
			$post_values['city'] = isset($dat[2])?$dat[2]:"";
			$post_values['country'] = isset($dat[3])?$dat[3]:"";
			$message1=" (FREE TRAIL REQUEST)  Company : ".$post_values['company_name']."  |  Message : ".$post_values['message']."   |   No. of Taxi : ".$post_values['no_of_taxi']."   |   Country : ".$country_name."   |   IP Address : ".$ip;

			$save_free_trial = $company->save_moderator_trial($post_values);
			if(count($save_free_trial) > 0)
			{
					$mail="";
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_EMAIL=>$post_values['email'],REPLACE_NAME=>$post_values['name'],REPLACE_PHONE=>$post_values['phone'],REPLACE_COMPANY=>$post_values['company_name'],REPLACE_NOOFTAXI=>$post_values['no_of_taxi'],REPLACE_COUNTRY=>$country_name,REPLACE_CITY=>$city_name,MESSAGE=>$message1,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE,REPLACE_SALES_PERSON_EMAIL=>$_POST['sales_person_email'],REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'get_free_quotes.html',$replace_variables);
					$to = 'mahes@taximobility.com,sales@taximobility.com';
					//$to = 'pandiarajan.v@ndot.in';
					//$to = 'durairaj.s@ndot.in';
					$from = CONTACT_EMAIL;
					$subject = __('get_free_quotes_details')." - ".APPLICATION_NAME;	
					$redirect ='add/moderator' ;	
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
				/********** Auto company creation ***************/

				$signup_id=$company->addcompanydetails($post_values);
			   
				if($signup_id != 0) 
				{ 
					$mail="";
					$domain = trim($post_values['domain_name']);
					$mobile = $signup_id.date('ymd');
					$passengerdriver_details="";
					$passengerdriver_details.= '<p style="font:bold 15px/22px arial;margin:0;color:#333;padding: 10px 0;">Passenger Details</p>';
					$passengerdriver_details.='<table cellspacing="0" cellpadding="10" width="100%" style="border:1px solid #dddddd;font-size: 14px; line-height: 20px; font-family: "Lucida Grande",Arial,sans-serif;font-color: #000; border="1" border-style="solid" border-color="#dddddd;"  ">
					<tr>
						<td style="background:#efefef;"><b style="font:bold 12px arial;color:#333333; ">#</b></td>
						<td style="background:#efefef;"><b style="font:bold 12px arial;color:#333333; ">Username(Mobile Number)</b></td>
						<td style="background:#efefef;"><b style="font:bold 12px arial;color:#333333; ">Password</b></td>
					</tr>';
					// Dynmaic Passenger login details 
					$length=($post_values['no_of_taxi'])?$post_values['no_of_taxi']:3;
					for($i=1;$i<=$length;$i++)
					{
					$passengerdriver_details.= '<tr>
						<td style="font:normal 12px arial;color:#333333; ">'.$i.'</td>
						<td style="font:normal 12px arial;color:#333333; ">'.$mobile.$i.'</td>
						<td style="font:normal 12px arial;color:#333333; ">qwerty</td>
					</tr>';
					}
					$passengerdriver_details.= '</table>';
					//$passengerdriver_details.='<img src="##SITEURL##public/images/email_temp_spacer-header.jpg" width="520px" height="20px" alt="##SITENAME##"/>';
					$passengerdriver_details.= '<p style="font:bold 15px/22px arial;margin:0;color:#333;padding: 10px 0;">Driver Details</p>';
					$passengerdriver_details.= '<table  cellspacing="0" cellpadding="10" width="100%" style="border:1px solid #dddddd;font-size: 14px; line-height: 20px; font-family: "Lucida Grande",Arial,sans-serif;font-color: #000; border="1" border-style="solid" border-color="#dddddd;" ">
					<tr>
						<td style="background:#efefef;"><b style="font:bold 12px arial;color:#333333; ">#</b></td>
						<td style="background:#efefef;"><b style="font:bold 12px arial;color:#333333; ">Username(Mobile Number)</b></td>
						<td style="background:#efefef;"><b style="font:bold 12px arial;color:#333333; ">Password</b></td>
					</tr>';
					// Dynmaic driver login details 
					for($i=1;$i<=$length;$i++)
					{
					$passengerdriver_details.= '<tr>
						<td style="font:normal 12px arial;color:#333333; ">'.$i.'</td>
						<td style="font:normal 12px arial;color:#333333; ">'.$mobile.$i.'</td>
						<td style="font:normal 12px arial;color:#333333; ">qwerty</td>
					</tr>';
					}
					$passengerdriver_details.= '</table>';
					//$passengerdriver_details.='<img src="##SITEURL##public/images/email_temp_spacer-header.jpg" width="520px" height="20px" alt="##SITENAME##"/>';
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_USERNAME=>$post_values['name'],REPLACE_EMAIL=>$post_values['email'],REPLACE_PASSWORD=>'qwerty',REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,MESSAGE=>$passengerdriver_details,REPLACE_SITEURL=>URL_BASE,REPLACE_COMPANYDOMAIN=>$domain,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					// Place the content to Email templete 
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'autotrail_company_registration.html',$replace_variables);
					//$to = $_POST['email'];
					$to = $post_values['sales_person_email'];
					$from = CONTACT_EMAIL;
					$subject = __('registration_success');	//Language string for internationalization
					$redirect = 'no';	

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

					Message::success(__('sucessfull_added_company'));
					$this->request->redirect('add/moderator');

					//echo '1';exit;
				}

			
			}

			}
			else
			{
				$errors = $validator->errors('errors');
				
			}

		}
		$view= View::factory('admin/add_moderator')
						->bind('validator', $validator)
						->bind('errors', $errors)
						->bind('postvalue',$post_values);
		$this->template->content = $view;
	
		$this->template->title= SITENAME." | ".__('add_company');
		$this->template->page_title= __('add_company'); 
		$this->template->content = $view;

	}	
	
	public function action_create_login()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		$add_model = Model::factory('add');
		$cid = $this->request->param('id');
		
		if($cid !='')
		{
			$check_cid = $add_model->check_companyid($cid);
		
			if($check_cid == 0)
			{
				Message::success(__('invalid_companyid'));
				$this->request->redirect("manage/company");
			}
		}
		$usertype = $_SESSION['user_type'];
		if($cid == '')
		{
			$cid = $_SESSION['company_id'];
		}
		if($usertype !='A')
		{
			$check_result = $add_model->validate_packagedriver($cid);
			if($check_result < 0)
			{ 
				if($usertype =='C')
				{
					$this->request->redirect("manage/availabilitydriver");
				}
				if($usertype =='M')
				{
					$this->request->redirect("manage/availabilitydriver");
				}			
			}

			if($check_result == 0)
			{
				if($usertype =='C')
				{
					Message::success(__('please_upgrade_package'));
					$this->request->redirect("company/dashboard");
				}
				if($usertype =='M')
				{
					Message::success(__('check_company_owner'));
					$this->request->redirect("manager/dashboard");
				}
			}
		}
		
		$add_model = Model::factory('add');
		$taxicompany_details = $add_model->taxicompany_details();
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addcompany'); 
		$errors = array();
		$post_values = array();
		
		if ($signup_submit && Validation::factory($_POST) ) 
		{ 
			$post_values = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
			
			$validator = $add_model->validate_createlogin(arr::extract($post_values,array('firstname','lastname','phone','no_of_login','company_id')));
			
			if($validator->check())
			{				
			   $signup_id=$add_model->create_login($post_values);
			   $driver_details=$add_model->view_login($post_values);
			   $passenger_details=$add_model->view_passengerlogin($post_values);
			
				if($signup_id == 1) 
				{ 
					Message::success(__('sucessfull_login_created'));
					

					$this->request->redirect("add/create_login");

				}	
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
			
		}
	
		$view= View::factory('admin/create_login')
						->bind('validator', $validator)
						->bind('errors', $errors)
						->bind('errors', $errors)
						->bind('driver_details', $driver_details)
						->bind('passenger_details', $passenger_details)
						->bind('taxicompany_details', $taxicompany_details)
						->bind('postvalue',$post_values);
		$this->template->content = $view;
		$this->template->title= SITENAME." | ".__('create_login');
		$this->template->page_title= __('create_login'); 
		$this->template->content = $view;
		
	}


	public function action_taxi()
	{
		$add_model = Model::factory('add');
		$cid = $this->request->param('id');
		if($cid !='')
		{
			$check_cid = $add_model->check_companyid($cid);
			if($check_cid == 0)
			{
				Message::success(__('invalid_companyid'));
				$this->request->redirect("manage/company");
			}
		}
		
		$taxi_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];

		if($cid == '')
		{
			$cid = $_SESSION['company_id'];
		}

		if($usertype =='M')
		{
			$this->request->redirect("manager/dashboard");
		}

		/* if($usertype !='A')
		{
			$check_result = $add_model->validate_packagetaxi($cid);
			 if($check_result < 0)
			{ 
				if($usertype =='C')
				{
					$this->request->redirect("manage/availabilitytaxi");
				}
				if($usertype =='M')
				{
					$this->request->redirect("manage/availabilitytaxi");
				}			
			}
			if($check_result == 0)
			{
				if($usertype =='C')
				{
					Message::success(__('please_upgrade_package'));
					$this->request->redirect("company/dashboard");
				}
				if($usertype =='M')
				{
					Message::success(__('check_company_owner'));
					$this->request->redirect("manager/dashboard");
				}
			}

			$check_result = $add_model->validate_package_assigntaxi($cid);

			if($check_result == 0)
			{
				if($usertype =='C')
				{
					Message::success(__('please_upgrade_package'));
					$this->request->redirect("add/upgradepackage");
				}
				if($usertype =='M')
				{
					Message::success(__('check_company_owner'));
					$this->request->redirect("manager/dashboard");
				}
			}
		} */
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addtaxi'); 
		$errors = array();
		$post_values = array();
		$form_array = '';
		//$motor_details = $add_model->motor_details();
		//$model_details = $add_model->model_details();
		$model_details_new = $add_model->model_details_new();
		$country_details = $add_model->country_details();
		$state_details = $add_model->state_details();
		$city_details = $add_model->city_details();
		
		$taxicompany_details = $add_model->taxicompany_details();
		$additional_fields = $add_model->taxi_additionalfields();
		
		$array_count = count($additional_fields);

		if($array_count > 0)
		{
			for($i=0; $i<$array_count; $i++)
			{
				if($i > 0)
				{
					$form_array .= ",".$additional_fields[$i]['field_name'];
				}
				else
				{				
					$form_array .= $additional_fields[$i]['field_name'];
				}
			}
			$form_array = explode(',',$form_array);
		}
		else
		{
			$form_array = array();
		}

		$post_values = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
		//echo "<pre>"; print_r($post_values); exit;

		$values=Arr::merge($post_values,$form_array);
		
		if ($signup_submit && Validation::factory($values,$_FILES) ) 
		{
			$validator = $add_model->validate_addtaxi($values,$form_array,$_FILES,$array_count);

			if($validator->check())
			{
				$comny_id = $post_values['company_name'];
				$modelid = $post_values['taxi_model'];
				//get company brand type for the selected company
				$brandType = $add_model->getCompanyBrand($comny_id);
				$check_fare_exist = 1;
				if($brandType == "M"){
					$check_fare_exist = $add_model->check_fare_exist($comny_id,$modelid);
				}
			   
				if($check_fare_exist == 0) 
				{ 
					Message::error(__('fare_not_avaliable'));
					if($usertype =='C')
					{
						$this->request->redirect("manage/fare");
					}
					else
					{
						$this->request->redirect("manage/model");
					}
				}
				else
				{
					if(!empty($_FILES['taxi_image']['name'])){
						/* image */
						$image_name = uniqid().$_FILES['taxi_image']['name']; 
						$image_type=explode('.',$image_name);
						$image_type=end($image_type);
						
						//$image_name=url::title($image_name).'.'.$image_type;
						$filename = Upload::save($_FILES['taxi_image'],$image_name,DOCROOT.TAXI_IMG_IMGPATH);
						//chmod($filename,'0777');
						//Image resize and crop for thumb image
						$logo_image = Image::factory($filename);
						$path1=DOCROOT.TAXI_IMG_IMGPATH;
						$path=$image_name;
						
						Commonfunction::taxiimageresize($logo_image,TAXI_IMG_WIDTH, TAXI_IMG_HEIGHT,$path1,$image_name,90);
						/**** Taxi APP THU100 ***/
						$tmb100_image_name = 'tmb100_'.$image_name;				
						Commonfunction::taxiimageresize($logo_image,TAXI_APP_THMB100_IMG_WIDTH, TAXI_APP_THMB100_IMG_HEIGHT,$path1,$tmb100_image_name,90);
						/**** TAxi APP THUM50 ***/
						$tmb32_image_name = 'tmb32_'.$image_name;				
						Commonfunction::taxiimageresize($logo_image,TAXI_APP_THMB32_IMG_WIDTH, TAXI_APP_THMB32_IMG_HEIGHT,$path1,$tmb32_image_name,90);	

						
						if ($image_type == 'jpeg' || $image_type == 'jpg' )
						{
							$base_image = imagecreatefromjpeg($path1.$tmb32_image_name);

							$width = 32;
							$height = 12;

							$top_image = imagecreatefrompng(URL_BASE."public/images/view.png");
							$merged_image = $path1.$tmb32_image_name;


							imagesavealpha($top_image, true);
							imagealphablending($top_image, true);

							imagecopy($base_image, $top_image, 0, 23, 0, 0, $width, $height);
							imagejpeg($base_image, $merged_image);

						}
						if ($image_type == 'png')
						{
							$base_image = imagecreatefrompng($path1.$tmb32_image_name);

							$width = 32;
							$height = 12;

							$top_image = imagecreatefrompng(URL_BASE."public/images/view.png");
							$merged_image = $path1.$tmb32_image_name;


							imagesavealpha($top_image, true);
							imagealphablending($top_image, true);

							imagecopy($base_image, $top_image, 0, 23, 0, 0, $width, $height);
							imagepng($base_image, $merged_image);

						}

						$signup_id=$add_model->addtaxi($post_values,$form_array,$path,$_FILES,$array_count);
						
						if($signup_id == 1) 
						{ 
							$mail="";
							Message::success(__('sucessfull_added_taxi'));
							$this->request->redirect("manage/taxi");
						}
					}
				}
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
		$view = View::factory('admin/add_taxi')
				->bind('validator', $validator)
				->bind('errors', $errors)
				->bind('additional_fields',$additional_fields)
				->bind('country_details',$country_details)
				->bind('city_details',$city_details)
				->bind('state_details',$state_details)
				//->bind('motor_details',$motor_details)
				//->bind('model_details',$model_details)
				->bind('model_details_new',$model_details_new)
				->bind('taxicompany_details',$taxicompany_details)
				->bind('cid',$cid)
				->bind('postvalue',$post_values);
		$this->template->content = $view;
		$this->template->title= SITENAME." | ".__('add_taxi');
		$this->template->page_title= __('add_taxi'); 
		$this->template->content = $view;
	}	

	public function action_driver()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		$add_model = Model::factory('add');
		$cid = $this->request->param('id');
		
		if($cid !='')
		{
			$check_cid = $add_model->check_companyid($cid);
		
			if($check_cid == 0)
			{
				Message::success(__('invalid_companyid'));
				$this->request->redirect("manage/company");
			}
		}
				
		$usertype = $_SESSION['user_type'];

		if($cid == '')
		{
			$cid = $_SESSION['company_id'];
		}
		/* if($usertype !='A')
		{
			$check_result = $add_model->validate_packagedriver($cid);

			if($check_result < 0)
			{ 
				if($usertype =='C')
				{
					$this->request->redirect("manage/availabilitydriver");
				}
				if($usertype =='M')
				{
					$this->request->redirect("manage/availabilitydriver");
				}			
			}

			if($check_result == 0)
			{
				if($usertype =='C')
				{
					Message::success(__('please_upgrade_package'));
					$this->request->redirect("company/dashboard");
				}
				if($usertype =='M')
				{
					Message::success(__('check_company_owner'));
					$this->request->redirect("manager/dashboard");
				}
			}

		} */
		
					
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_driver');
		$country_details = $add_model->country_details();
		$state_details = $add_model->state_details();
		$city_details = $add_model->city_details();
		$taxicompany_details = $add_model->taxicompany_details();
		$errors = array();
		$post_values = array();

		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
			$driver = Model::factory('driver');

			$form_values=Arr::extract($post_values,array('firstname','lastname','dob','email','password','repassword','driver_license_id','driver_license_expire_date','driver_pco_license_number','driver_pco_license_expire_date','driver_insurance_number','driver_insurance_expire_date','driver_national_insurance_number','driver_national_insurance_expire_date','phone','address','country','state','city','company_name','booking_limit','brand_type'));

			$file_values = Arr::extract($_FILES,array('photo'));
			$values = Arr::merge($form_values,$file_values);
			$validator = $add_model->validate_adddriver($values);

			if($validator->check())
			{
				$image_name = uniqid().$_FILES['photo']['name'];
				$thumb_image_name = 'thumb_'.$image_name;
				$image_type=explode('.',$image_name);
				$image_type=end($image_type);
				//$image_name=url::title($image_name).'.'.$image_type;
				$filename = Upload::save($_FILES['photo'],$image_name,DOCROOT.SITE_DRIVER_IMGPATH);	
				$logo_image = Image::factory($filename);

				$path11=DOCROOT.SITE_DRIVER_IMGPATH;
				$path1=$image_name;
				Commonfunction::imageresize($logo_image,PASS_IMG_WIDTH, PASS_IMG_HEIGHT,$path11,$image_name,90);
				$path12=$thumb_image_name;
				Commonfunction::imageresize($logo_image,PASS_THUMBIMG_WIDTH, PASS_THUMBIMG_HEIGHT,$path11,$thumb_image_name,90);
				$signup_id=$add_model->add_driver($_POST,$filename);
				$status = $driver->update_driverimage($path1,$signup_id);

				if($signup_id !=0) {
					$signup_id=1;
				} else {
					$signup_id=0;
				}
				//$signup_id=$add_model->add_driver($_POST);

				if($signup_id ==1 ) 
				{ 
					$mail="";
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_USERNAME=>$post_values['firstname'],REPLACE_MOBILE=>$post_values['phone'],REPLACE_PASSWORD=>$post_values['password'],REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'driver-register.html',$replace_variables);
					
					$to = $post_values['email'];
					$from = CONTACT_EMAIL;
					$subject = __('driver_registration_success');
					$redirect = "manage/driver";
					$smtp_result = $add_model->smtp_settings();
					
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
					$message_details = $common_model->sms_message('1');
					$to = $_POST['phone'];
					$message = $message_details[0]['sms_description'];
					$message = str_replace("##SITE_NAME##",SITE_NAME,$message);
			
					//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");
					//print_r($result);exit;					
					}
					
					Message::success(__('sucessfull_added_driver'));
					$this->request->redirect("manage/driver");
				}
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

				$view= View::factory('admin/add_driver')
					->bind('validator', $validator)
					->bind('errors', $errors)
					->bind('country_details',$country_details)
					->bind('state_details',$state_details)
					->bind('city_details',$city_details)
					->bind('taxicompany_details',$taxicompany_details)
					->bind('cid',$cid)
					->bind('postvalue',$post_values);
			$this->template->content = $view;
		                
		$this->template->title= SITENAME." | ".__('add_driver');
		$this->template->page_title= __('add_driver'); 
		$this->template->content = $view;
	}	


	public function action_field()
	{

		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype !='A' && $usertype !='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   		
		$add_model = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addfield'); 
		
		$field_type =arr::get($_REQUEST,'field_type'); 
		$errors = array();
		$post_values = array();

		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			
			$post = Arr::map('trim', $this->request->post());

			$values=Arr::extract($post,array('field_labelname','field_name','field_type'));

			if($field_type != 'Textbox')
			{
			   $field_values=Arr::extract($post,array('field_value'));
			   $values=Arr::merge($values,$field_values);
			}
			
			$validator = $add_model->validate_addfield($values);

			if($validator->check())
			{
	 	
			   $signup_id=$add_model->addfield($post);
			   
				if($signup_id == 1) 
				{ 
					Message::success(__('sucessfull_added_field'));

					$this->request->redirect("manage/field");

				}	
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view= View::factory('admin/add_field')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_field');
		$this->template->page_title= __('add_field'); 
		$this->template->content = $view;
	}

	public function action_package()
	{
		
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype !='A' && $usertype !='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   		
		$add_model = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addpackage'); 
		$errors = array();
		$post_values = array();

		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			
			$post = Arr::map('trim', $this->request->post());
			
			$form_values=Arr::extract($post,array('package_name','package_description','no_of_taxi','no_of_driver','package_price','days_expire'));
			
			$validator = $add_model->validate_addpackage($form_values);

			if($validator->check())
			{
			
			  $signup_id=$add_model->add_package($post);

				Message::success(__('sucessfull_added_package'));
				$this->request->redirect("manage/package");

			     
			}
			else
			{
				$errors = $validator->errors('errors');
				//print_r($errors);exit;
			}
		}

		$view= View::factory('admin/add_package')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_package');
		$this->template->page_title= __('add_package'); 
		$this->template->content = $view;
	}	

	public function action_country()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype !='A' && $usertype !='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$add_model = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addcountry'); 
		$errors = array();
		$post_values = array();
		
		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));

			$validator = $add_model->validate_addcountry(arr::extract($post,array('country_name','iso_country_code','telephone_code','currency_code','currency_symbol')));

			if($validator->check())
			{
				$signup_id=$add_model->addcountry($post);
				if($signup_id == 1) 
				{
					$mail="";
					Message::success(__('sucessfull_added_country'));
					$this->request->redirect("manage/country");
				}
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view= View::factory('admin/add_country')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_country');
		$this->template->page_title= __('add_country'); 
		$this->template->content = $view;
	}
	public function action_city()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype !='A' && $usertype !='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$add_model = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addcity'); 
		$errors = array();
		$post_values = array();
		
		//$motor_details = $add_model->country_details();
		$motor_details = $add_model->country_details_new();
		
		$state_details = $add_model->get_city_state_details($countryid = '');

		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
			if($post_values['country_name'])
			{
				$state_details = $add_model->get_city_state_details($post_values['country_name']);
			}
			
			$validator = $add_model->validate_addcity(arr::extract($post_values,array('country_name','state_name','city_name','zipcode','city_model_fare')));
			
			if($validator->check())
			{
			   $signup_id=$add_model->addcity($post_values);
			   
				if($signup_id == 1) 
				{ 
					Message::success(__('sucessfull_added_city'));

					$this->request->redirect("manage/city");

				}
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
		
		$view= View::factory('admin/add_city')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values)
				                ->bind('state_details',$state_details)
				                ->bind('motor_details',$motor_details);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_city');
		$this->template->page_title= __('add_city'); 
		$this->template->content = $view;
	}
	public function action_state()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype !='A' && $usertype !='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$add_model = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addstate'); 
		$errors = array();
		$post_values = array();
		
		$state_details = $add_model->country_details();
		
		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));

			$validator = $add_model->validate_addstate(arr::extract($post,array('country_name','state_name')));
			
			if($validator->check())
			{
			   $signup_id=$add_model->addstate($post);
			   
				if($signup_id == 1) 
				{ 
					Message::success(__('sucessfull_added_state'));
					$this->request->redirect("manage/state");
				}
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
		
		$view= View::factory('admin/add_state')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post)
				                ->bind('state_details',$state_details);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_state');
		$this->template->page_title= __('add_state'); 
		$this->template->content = $view;
	}

	public function action_getmodellist()
	{
		$add_model = Model::factory('add');
		$output ='';
		$motorid =arr::get($_REQUEST,'motor_id'); 
		$modelid =arr::get($_REQUEST,'model_id'); 

		$getmodel_details = $add_model->getmodel_details($motorid);

		if(isset($motorid))
		{
				
			$count=count($getmodel_details);
			if($count>0)
			{
				$output .='<select name="taxi_model" id="taxi_model" class="required" title="'.__('select_the_taximodel').'" >
					   <option value="">--Select--</option>';

					foreach($getmodel_details as $modellist) { 
					$output .='<option value="'.$modellist["model_id"].'"';
					if($modelid == $modellist["model_id"])
					{
						$output .='selected=selected';
					}
					$output .='>'.$modellist["model_name"].'</option>';
					}
	
				$output .='</select>';
				
			}
			else
			{
				$output .='<select name="taxi_model" id="taxi_model" class="required" title="'.__('select_the_taximodel').'">
				<option value="">--Select--</option></select>';
			}	   

		}
			echo $output;exit;
			
	}

	public function action_getcitylist()
	{
		$add_model = Model::factory('add');
		$output ='';
		$country_id =arr::get($_REQUEST,'country_id'); 
		$state_id =arr::get($_REQUEST,'state_id'); 
		$city_id =arr::get($_REQUEST,'city_id');

		$getmodel_details = $add_model->getcity_details($country_id,$state_id);

		if(isset($country_id))
		{
				
			$count=count($getmodel_details);
			if($count>0)
			{
	
				$output .='<select name="city" id="city" --onchange="change_company();" class="required" title="'.__('select_the_city').'" >
					   <option value="">--Select--</option>';
				
					foreach($getmodel_details as $modellist) { 
					$output .='<option value="'.$modellist["city_id"].'"';
					if($city_id == $modellist["city_id"])
					{
						$output .='selected=selected';
					}
					$output .='>'.$modellist["city_name"].'</option>';
					}

				$output .='</select>';
		
			}
			else
			{
				$output .='<select name="city" id="city" class="required" title="'.__('select_the_city').'">
				   <option value="">--Select--</option></select>';
					   
			}

		}
			echo $output;exit;
			
	}

	public function action_getlist_state()
	{
		$add_model = Model::factory('add');
		$output ='';
		$country_id =arr::get($_REQUEST,'country_id'); 
		$state_id =arr::get($_REQUEST,'state_id'); 

		$getmodel_details = $add_model->getstate_details($country_id);

		if(isset($country_id))
		{
				
			$count=count($getmodel_details);
			if($count>0)
			{
	
				$output .='<select name="state" id="state" onchange=change_city_drop("","","") class="required" title="'.__('select_the_state').'">
					   <option value="">--Select--</option>';
				
				foreach($getmodel_details as $modellist) { 
					$output .='<option value="'.$modellist["state_id"].'"';
					if($state_id == $modellist["state_id"])
					{
						$output .='selected=selected';
					}
					$output .='>'.$modellist["state_name"].'</option>';
				}
					   
				$output .='</select>';
		
			}
			else
			{
				$output .='<select name="state" id="state" class="required" title="'.__('select_the_state').'" >
				   <option value="">--Select--</option></select>';
					   
			}

		}
			echo $output;exit;
			
	}
	public function action_getstatelist()
	{
		$add_model = Model::factory('add');
		$output ='';
		$country_id =arr::get($_REQUEST,'country_id'); 
		$state_id =arr::get($_REQUEST,'state_id'); 

		$getmodel_details = $add_model->getstate_details($country_id);

		if(isset($country_id))
		{
				
			$count=count($getmodel_details);
			if($count>0)
			{
	
				$output .='<select name="state_name" id="state_name" >';
				
				foreach($getmodel_details as $modellist) { 
					$output .='<option value="'.$modellist["state_id"].'"';
					if($state_id == $modellist["state_id"])
					{
						$output .='selected=selected';
					}
					$output .='>'.$modellist["state_name"].'</option>';
				}
					   
				$output .='</select>';
		
			}
			else
			{
				$output .='<select name="state_name" id="state_name">
				   <option value="">--Select--</option></select>';
					   
			}

		}
			echo $output;exit;
			
	}

	public function action_getassigntaxilist()
	{
		$add_model = Model::factory('add');
		$output ='';
		$country_id =arr::get($_REQUEST,'country_id'); 
		$state_id =arr::get($_REQUEST,'state_id'); 
		$city_id =arr::get($_REQUEST,'city_id'); 
		$assigntaxi =arr::get($_REQUEST,'assigntaxi'); 

		$getmodel_details = $add_model->getcity_details($country_id,$state_id);

		if(isset($country_id))
		{
				
			$count=count($getmodel_details);
			if($count>0)
			{
				if(isset($assigntaxi) && $assigntaxi == 1) {
					$output .='<select name="city" id="city" class="required" title=" '.__('select_the_city').'" onchange="change_info(\'\',\'\',\'\',\'\');">
					   <option value="">--Select--</option>';
				} else {
					$output .='<select name="city" id="city" class="required" title=" '.__('select_the_city').'">
					   <option value="">--Select--</option>';
				}
					   
				foreach($getmodel_details as $modellist) { 
				$output .='<option value="'.$modellist["city_id"].'"';
					if($city_id == $modellist["city_id"])
					{
						$output .='selected=selected';
					}
					$output .='>'.$modellist["city_name"].'</option>';
				}
				$output .='</select>';
		
			}
			else
			{
				$output .='<select name="city" id="city" title=" '.__('select_the_city').'" class="required">
				   <option value="">--Select--</option></select>';
					   
			}

		}
			echo $output;exit;
			
	}
	
	public function action_getassignstatelist()
	{
		$add_model = Model::factory('add');
		$output ='';
		$country_id =arr::get($_REQUEST,'country_id'); 
		$state_id =arr::get($_REQUEST,'state_id'); 

		$getmodel_details = $add_model->getstate_details($country_id);

		if(isset($country_id))
		{
				
			$count=count($getmodel_details);
			if($count>0)
			{
	
				$output .='<select name="state" id="state" onchange="change_city_drop(\'\',\'\',\'\'); change_info(\'\',\'\',\'\',\'\');" class="required" title="'.__('select_the_state').'">
					   <option value="">--Select--</option>';
				
				foreach($getmodel_details as $modellist) { 
					$output .='<option value="'.$modellist["state_id"].'"';
					if($state_id == $modellist["state_id"])
					{
						$output .='selected=selected';
					}
					$output .='>'.$modellist["state_name"].'</option>';
				}
					   
				$output .='</select>';
		
			}
			else
			{
				$output .='<select name="state" id="state" onchange="change_city_drop(); change_info();" class="required" title="'.__('select_the_state').'" >
				   <option value="">--Select--</option></select>';
					   
			}

		}
			echo $output;exit;
			
	}
		
	public function action_manager()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype !='A' && $usertype !='C'  && $usertype !='S' && $usertype !='DA' )
		{
			$this->request->redirect("admin/login");
		}
		$add_model = Model::factory('add');
		$company_model = Model::factory('company');
		$cid = $this->request->param('id');
		if($cid !='')
		{
			$check_cid = $add_model->check_companyid($cid);
			if($check_cid == 0)
			{
				Message::success(__('invalid_companyid'));
				$this->request->redirect("manage/company");
			}
		}
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addmanager'); 
		$country_details = $add_model->country_details();
		$city_details = $add_model->city_details();
		$taxicompany_details = $add_model->taxicompany_details();
		$state_details = $add_model->state_details();
		
		$errors = array();
		$post_values = array();
		
		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
			
			$validator = $add_model->validate_addmanager(arr::extract($post_values,array('firstname','lastname','email','password','repassword','phone','address','country','state','city','company_name')));

			if($validator->check())
			{
			   $signup_id=$add_model->addmanager($post_values);
			  
			   if($signup_id == 1) 
				{ 
					if(isset($post_values['add_as_executive']) && $post_values['add_as_executive'] ==1){
						$admins_model = Model::factory('admin');
						$insert_executive = $admins_model->add_executive($post_values);
					}
					$mail="";
					//function to get company domain name
					$company_dets = $company_model->get_company_info($post_values['company_name']);
					//if there is no domain name comes go to product url
					$companyDomain = (count($company_dets) > 0) ? $company_dets[0]['company_domain'] : 'www';
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_DOMAINNAME=>$companyDomain,REPLACE_USERNAME=>$post_values['firstname'],REPLACE_EMAIL=>$post_values['email'],REPLACE_PASSWORD=>$post_values['password'],REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'register_dispatcher.html',$replace_variables);

					$to = $_POST['email'];
					$from = CONTACT_EMAIL;
					$subject = __('registration_success');
					$redirect = "manage/manager";
					$smtp_result = $add_model->smtp_settings();			
							
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

					//$mail=array("to" => $_POST['email'],"from"=>CONTACT_EMAIL,"subject"=>"Registration success","message"=>$message);
					//$emailstatus=$this->email_send($mail,'smtp');

					Message::success(__('sucessfull_added_manager'));
					$this->request->redirect("manage/manager");
				}
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view = View::factory('admin/add_manager')
				->bind('validator', $validator)
				->bind('errors', $errors)
				->bind('country_details',$country_details)
				->bind('city_details',$city_details)
				->bind('taxicompany_details',$taxicompany_details)
				->bind('state_details',$state_details)
				->bind('cid',$cid)
				->bind('postvalue',$post_values);

		$this->template->content = $view;
		$this->template->title= SITENAME." | ".__('add_manager');
		$this->template->page_title= __('add_manager'); 
		$this->template->content = $view;
	}


	public function action_getcompanylist()
	{
		$add_model = Model::factory('add');
		$output ='';
		$country_id =arr::get($_REQUEST,'country_id'); 
		$state_id =arr::get($_REQUEST,'state_id'); 
		$city_id =arr::get($_REQUEST,'city_id'); 
		$company_name =arr::get($_REQUEST,'company_name'); 
		$user_type =  $_SESSION['user_type'];
		$company_id = $_SESSION['company_id'];

		$getmodel_details = $add_model->getcompany_details($country_id,$state_id,$city_id);

		if(isset($country_id))
		{
				
			$count=count($getmodel_details);
			if($count>0)
			{
	
				$output .='<select name="company_name" id="company_name" class="required">
					   <option value="">--Select--</option>';
					   
				foreach($getmodel_details as $modellist) { 
				$output .='<option value="'.$modellist["cid"].'"';
					if($company_name == $modellist["cid"])
					{
						$output .='selected=selected';
					}
					$output .='>'.$modellist["company_name"].'</option>';
				}
				$output .='</select>';
		
			}
			else
			{
				$output .='<select name="company_name" id="company_name">
				   <option value="">--Select--</option></select>';
					   
			}

		}
			echo $output;exit;
			
	}

	public function action_assigntaxi()
	{
	
		$user_createdby = $_SESSION['userid'];
	
		$usertype = $_SESSION['user_type'];	
   	
		$add_model = Model::factory('add');
		
		$usertype = $_SESSION['user_type'];
		
		$company_id = $_SESSION['company_id'];
		
		$cid = $_SESSION['company_id'];

		/* if($usertype !='A')
		{
			$check_result = $add_model->validate_packagetaxi($cid);

			if($check_result < 0)
			{ 
				if($usertype =='C')
				{
					$this->request->redirect("manage/availabilitytaxi");
				}
				if($usertype =='M')
				{
					$this->request->redirect("manage/availabilitytaxi");
				}			
			}

			$check_result = $add_model->validate_packagedriver($cid);

			if($check_result < 0)
			{ 
				if($usertype =='C')
				{
					Message::success(__('limited_driver'));
					$this->request->redirect("manage/availabilitydriver");
				}
				if($usertype =='M')
				{
					Message::success(__('limited_driver'));
					$this->request->redirect("manage/availabilitydriver");
				}			
			}
			
			

			$check_result = $add_model->validate_package_assigntaxi($cid);

			if($check_result == 0)
			{
				if($usertype =='C')
				{
					Message::success(__('please_upgrade_package'));
					$this->request->redirect("company/dashboard");
				}
				if($usertype =='M')
				{
					Message::success(__('check_company_owner'));
					$this->request->redirect("manager/dashboard");
				}
			}

		} */
		
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addassigntaxi'); 
		if($usertype != "M") {
			$country_details = $add_model->country_details();
			$state_details = $add_model->state_details();
			$city_details = $add_model->city_details();
			$taxicompany_details = $add_model->taxicompany_details();
		}
		
		$driver_details = $add_model->driver_details();
		$taxi_details = $add_model->taxi_details();
		
		$errors = array();
		$post_values = array();
		
		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			
			$post = Arr::map('trim', $this->request->post());
			
			$validator = $add_model->validate_addassigntaxi(arr::extract($post,array('company_name','country','state','city','driver','taxi','startdate','enddate')));
			
			if($validator->check())
			{
			   $update = $add_model->addassigntaxi($post);
				if(is_array($update) && count($update) > 0 ) {
					$mail="";
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_USERNAME=>$update[0]['name'],REPLACE_TAXINO=>$update[0]['taxi_no'],REPLACE_STARTDATE=>$post['startdate'],REPLACE_ENDDATE=>$post['enddate'],REPLACE_TAXIMODEL=>$update[0]['model_name'],REPLACE_TAXISPEED=>$update[0]['taxi_speed'],REPLACE_MAXIMUMLUGGAGE=>$update[0]['max_luggage'],REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'assign_taxi.html',$replace_variables);

					$to = $update[0]['email'];
					$from = CONTACT_EMAIL;
					$subject = __('taxi_assigned_you');	
					$redirect = "manage/assigntaxi";
					$smtp_result = $add_model->smtp_settings();
					
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

				}
				Message::success(__('sucessfull_assign_taxi'));
				$this->request->redirect("manage/assigntaxi");
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
		
		$view= View::factory('admin/add_assigntaxi')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
      				                ->bind('country_details',$country_details)
      				                ->bind('state_details',$state_details)
				                ->bind('city_details',$city_details)
				                ->bind('taxicompany_details',$taxicompany_details)
				                ->bind('driver_details',$driver_details)
				                ->bind('taxi_details',$taxi_details)
				                ->bind('postvalue',$post_values);
				                
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('assign_taxi');
		$this->template->page_title= __('assign_taxi'); 
		$this->template->content = $view;
	}	

	public function action_getassignedlist()
	{

		$add_model = Model::factory('add');
		$output ='';
		$country_id =arr::get($_REQUEST,'country_id'); 
		$state_id =arr::get($_REQUEST,'state_id'); 
		$city_id =arr::get($_REQUEST,'city_id'); 
		$company_name =arr::get($_REQUEST,'company_name'); 
		$driver_id =arr::get($_REQUEST,'driver_id'); 
		$taxi_id =arr::get($_REQUEST,'taxi_no'); 
		$startdate =arr::get($_REQUEST,'startdate'); 
		$enddate =arr::get($_REQUEST,'enddate'); 
		$user_type =  $_SESSION['user_type'];
		$company_id = $_SESSION['company_id'];
		$page_title = __('assign_taxi');
		$page_no = arr::get($_REQUEST,'page'); 			
		$count_details = $add_model->getassignedcount($country_id,$state_id,$city_id,$company_name,$driver_id,$taxi_id,$startdate,$enddate);

		if($page_no)
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
		'current_page'   => array('source' => 'query_string','key' => 'page'),
		  'items_per_page' => REC_PER_PAGE,
		'total_items'    => $count_details,
		'view' => 'pagination/punajax',			  
		));
		
		$getmodel_details = $add_model->getassignedlist($country_id,$state_id,$city_id,$company_name,$driver_id,$taxi_id,$startdate,$enddate,$offset, REC_PER_PAGE);
			
			$count=count($getmodel_details);

	
				$output .='<div class="widget">
				<div class="title"><h6>'.$page_title.'</h6>
				<div style="width:auto; float:right; margin: 4px 3px;">
				<div class="button greyishB"></div>                       
				</div>
				</div>';
				if($count > 0){ 
				$output .='<div class= "overflow-block">';
				}
				$output .='<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive">';
				if($count > 0){ 
				$output .='<thead>
				<tr>
				<td align="left" width="5%" style="min-width: 22px !important;" >Status</td>
				<td align="left" width="5%">'. __('sno_label').'</td>
				<td align="left" style="text-align:left;" width="8%">'.ucfirst(__('driver_name')).'</td>
				<td align="left" style="text-align:left;" width="10%">'.__('taxi_no').'</td>
				<td align="left" style="text-align:left;" width="8%">'.__('companyname').'</td>
				<td align="left" style="text-align:left;" width="10%">'.__('country_label').'</td>
				<td align="left" style="text-align:left;" width="8%">'.__('city_label').'</td>
				<td align="left" style="text-align:left;" width="10%">'.__('from_date').'</td>
				<td align="left" style="text-align:left;" width="10%">'.__('end_date').'</td>
				</tr>
				</thead>
				<tbody>	';

				$sno=$offset; /* For Serial No */

				foreach($getmodel_details as $listings) {

				//S.No Increment
				//==============
				$sno++;

				//For Odd / Even Rows
				//===================
				$trcolor=($sno%2==0) ? 'oddtr' : 'eventr';  

				$output .='<tr class="'.$trcolor.'">
				<td align="center">'; 


                             if($listings['mapping_status']=='A')
                             {  $txt = "Active"; $class ="unsuspendicon";    }
                             elseif($listings['mapping_status']=='T')
				{$txt = "Trash"; $class ="trashicon";}
                             else{  $txt = "Deactive"; $class ="blockicon";      }


				$output .='<a href="javascript:void(0);" title ='.$txt.' class='.$class.'></a>' ;  

				$output .='</td> 
				<td align="center">'.$sno.'</td>
				<td align="left">'.wordwrap(ucfirst($listings['name']),30,'<br/>',1).'</td>
				<td>'.wordwrap(ucfirst($listings['taxi_no']),30,'<br/>',1).'</td>
				<td align="left">'.wordwrap(ucfirst($listings['company_name']),30,'<br/>',1).'</td>
				<td align="left">'.wordwrap($listings['country_name'],25,'<br />',1).'</td>						
				<td>'.wordwrap($listings['city_name'],25,'<br />',1).'</td>
				<td>'.wordwrap(Commonfunction::getDateTimeFormat($listings['mapping_startdate'],1),25,'<br />',1).'</td>
				<td>'.wordwrap(Commonfunction::getDateTimeFormat($listings['mapping_enddate'],1),25,'<br />',1).'</td>
				</tr>';
				 } 
				} 

				//For No Records
				//==============
				else{ 
				$output .='<tr>
				<td class="nodata">'.__('no_data').'</td>
				</tr>';
				 } 
				$output .='</tbody>
				</table>';
				 if ($count > 0) { 
				$output .='</div>';
				 } 
				$output .='</div>';
				$output .='<div class="bottom_contenttot"><div class="pagination">';
				if($count > 0) { 
				$output .= ''.$pag_data->render().'';
				}
  				$output .='</div></div>';
  
			echo $output;exit;
			
	}

	public function action_getdriverlist()
	{
		$add_model = Model::factory('add');
		$output ='';
		$country_id =arr::get($_REQUEST,'country_id');
		$state_id =arr::get($_REQUEST,'state_id'); 
		$city_id =arr::get($_REQUEST,'city_id'); 
		$company_id =arr::get($_REQUEST,'company_name'); 
		$user_type =  $_SESSION['user_type'];
		$driver_id =arr::get($_REQUEST,'driver_id');
		$type =arr::get($_REQUEST,'type');
		$getmodel_details = $add_model->getdriverdetails($company_id,$country_id,$state_id,$city_id,$user_type);
		$commMdl = Model::factory('commonmodel');
		$date = $commMdl->getcompany_all_currenttimestamp($company_id);

		if(isset($country_id))
		{
			$count = count($getmodel_details);
			if($count > 0)
			{
				$output .='<select name="driver" id="driver" onchange="change_info("","","");" size=5>
						<option value="">--Select--</option>';
				foreach($getmodel_details as $modellist) {
					if($type == 1) {
						if($date < $modellist['mapping_startdate'] || $date > $modellist['mapping_enddate'] || empty($modellist['mapping_enddate'])) {
							$output .='<option value="'.$modellist["id"].'"';
							if($driver_id == $modellist["id"])
							{
								$output .='selected=selected class="selected_active"';
							}
							$output .='>'.ucfirst($modellist["name"]).'</option>';
						}
					} else {
						$output .='<option value="'.$modellist["id"].'"';
						if($driver_id == $modellist["id"])
						{
							$output .='selected=selected class="selected_active"';
						}
						$output .='>'.ucfirst($modellist["name"]).'</option>';
					}
				}
				$output .='</select>';
			}
			else
			{
				$output .='<select name="driver" id="driver" onchange="change_info("","","");" size=5>
					<option value="">--Select--</option></select>';
			}
		}
		echo $output;exit;
	}

	public function action_gettaxilist()
	{
		$add_model = Model::factory('add');
		$output ='';
		$country_id =arr::get($_REQUEST,'country_id'); 
		$state_id =arr::get($_REQUEST,'state_id'); 
		$city_id =arr::get($_REQUEST,'city_id'); 
		$company_id =arr::get($_REQUEST,'company_name'); 
		$user_type =  $_SESSION['user_type'];
		$taxi_id =arr::get($_REQUEST,'taxi_id'); 
		$type =arr::get($_REQUEST,'type');
		$commMdl = Model::factory('commonmodel');
		$date = $commMdl->getcompany_all_currenttimestamp($company_id);

		$getmodel_details = $add_model->gettaxidetails($company_id,$country_id,$state_id,$city_id,$user_type);

		if(isset($country_id))
		{
			$count = count($getmodel_details);
			if($count > 0)
			{
				$output .='<select name="taxi" id="taxi" onchange="change_info("","","");" size=5>
						<option value="">--Select--</option>';
				foreach($getmodel_details as $modellist) {
					if($type == 1) {
						if($date < $modellist['mapping_startdate'] || $date > $modellist['mapping_enddate'] || empty($modellist['mapping_enddate'])) {
							$output .='<option value="'.$modellist["taxi_id"].'"';
							if($taxi_id == $modellist["taxi_id"])
							{
								$output .='selected=selected class="selected_active"';
							}
							$output .='>'.$modellist["taxi_no"].'</option>';
						}
					} else {
						$output .='<option value="'.$modellist["taxi_id"].'"';
						if($taxi_id == $modellist["taxi_id"])
						{
							$output .='selected=selected class="selected_active"';
						}
						$output .='>'.$modellist["taxi_no"].'</option>';
					}
				}
				$output .='</select>';
			}
			else
			{
				$output .='<select name="taxi" id="taxi" onchange="change_info("","","");" size=5>
						<option value="">--Select--</option></select>';
			}

		}
			echo $output;exit;
			
	}		

	public function action_upgradepackage()
	{
	
		$user_createdby = $_SESSION['userid'];
		$company_id = $_SESSION['company_id'];
	
		$usertype = $_SESSION['user_type'];        
		if($usertype =='A')
		{
			$this->request->redirect("admin/login");
		}
		
		if($usertype =='M')
		{
			$this->request->redirect("manager/login");
		}
   	
		$add_model = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_upgradepackage'); 
		$package_details = $add_model->package_details();
		$field_count = count($package_details);
		
		if($field_count == 0)
		{
			$this->request->redirect("admin/upgradereports");
		}
		
		$errors = array();
		$post_values = array();
		
		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			$signup_id=$add_model->packageupgrade($_POST,$company_id);

			Message::success(__('sucessfull_upgrade_package'));

			$this->request->redirect("add/upgradepackage");
		}

		
		$package_count = $add_model->packagecount($company_id);
		
		$view= View::factory('admin/upgrade_package')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('package_count',$package_count)
   				                ->bind('package_details',$package_details)
				                ->bind('postvalue',$post_values);
				                
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('package_upgrade');
		$this->template->page_title= __('package_upgrade'); 
		$this->template->content = $view;
	}
	
	public function action_packageupgrade()
	{
	
		$user_createdby = $_SESSION['userid'];
	
		$usertype = $_SESSION['user_type'];	
		
   		$company_id = $this->request->param('id');
   		
		$add_model = Model::factory('add');
		
   		$current_packageid = $add_model->current_package($company_id);
		   		
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_upgradepackage'); 
		$package_details = $add_model->package_details();
		$field_count = count($package_details);

		if($field_count == 0)
		{
			$this->request->redirect("admin/upgradereports");
		}
		
		$errors = array();
		$post_values = array();
		
		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			
			
			   $signup_id=$add_model->packageupgrade($_POST,$company_id);
			   
				if($signup_id == 1) 
				{ 
					$mail="";			
						
					/*$signup_cont=$this->emailtemplate->get_template_content(USER_CHANGE_PASSWORD);
					$subject=$signup_cont[0]['email_subject'];
					$content=$signup_cont[0]['email_content'];
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_USERNAME=>$_POST['name'],REPLACE_EMAIL=>$_POST['email'],REPLACE_PASSWORD=>$_POST['password'],REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'registertemp.html',$replace_variables);
					$mail=array("to" => $_POST['email'],"from"=>CONTACT_EMAIL,"subject"=>"Registration success","message"=>$message);									
					$emailstatus=$this->email_send($mail,'smtp');								
					*/
					Message::success(__('sucessfull_upgrade_package'));

					$this->request->redirect("manage/company");

				}	
			     
		}
		
		$package_count = $add_model->packagecount($company_id);

		$view= View::factory('admin/upgradepackage')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('current_packageid',$current_packageid)
   				                ->bind('package_details',$package_details)
   				                ->bind('package_count',$package_count)
				                ->bind('postvalue',$post_values);
				                
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('package_upgrade');
		$this->template->page_title= __('package_upgrade'); 
		$this->template->content = $view;
	}
	
	/** for adding contents **/
	public function action_contents()
	{	
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
	   	
		$add_model = Model::factory('add');		
		/** Select menus **/
		$menu_details = $add_model->get_menus();
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addmanager');
		
		$errors = array();
		$post_values = array();
		
		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = Arr::map('trim', $this->request->post());
			
			$validator = $add_model->validate_addcontents(arr::extract($post_values,array('menu_name','meta_title','meta_keyword','meta_description')));
			
			if($validator->check())
			{
				$menu_name_exits = $add_model->menu_content_exits($post_values);
				if($menu_name_exits == 1)
				{
					Message::error(__('content_already_exits'));
					$this->request->redirect("manage/contents");
				} 
				
			  	$signup_id=$add_model->addcontents($post_values);
			   
				if($signup_id == 1) 
				{ 
					Message::success(__('sucessfull_added_contents'));
					$this->request->redirect("manage/contents");
				}     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
		
		$view= View::factory('admin/add_contents')
				->bind('validator', $validator)
				->bind('errors', $errors)
				->bind('postvalue',$post_values)
				->bind('menu_details',$menu_details);
		                
		$this->template->title= SITENAME." | ".__('add_content');
		$this->template->page_title= __('add_content'); 
		$this->template->content = $view;
	}

	public function action_getdriverassignedlist()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];

		$add_model = Model::factory('add');
		$output ='';
		$driver_id =arr::get($_REQUEST,'driver_id'); 
		$startdate =arr::get($_REQUEST,'startdate'); 
		$enddate =arr::get($_REQUEST,'enddate'); 
		$page_title = __('assign_taxi');
		$page_no = arr::get($_REQUEST,'page'); 			
		$count_details = $add_model->countdriverassignedlist($driver_id,$startdate,$enddate);

		if($page_no)
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
		'current_page'   => array('source' => 'query_string','key' => 'page'),
		  'items_per_page' => REC_PER_PAGE,
		'total_items'    => $count_details,
		'view' => 'pagination/punmad',			  
		));
		
		$getmodel_details = $add_model->getdriverassignedlist($driver_id,$startdate,$enddate,$offset, REC_PER_PAGE);
			
			$count=count($getmodel_details);

	
				$output .='<div class="widget">
				<div class="title"><img src="'.IMGPATH.'icons/dark/frames.png" alt="" class="titleIcon" /><h6>'.$page_title.'</h6>
				<div style="width:auto; float:right; margin: 4px 3px;">
				<div class="button greyishB"></div>                       
				</div>
				</div>';
				if($count > 0){ 
				$output .='<div class= "overflow-block">';
				}
				$output .='<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive">';
				if($count > 0){ 
				$output .='<thead>
				<tr>
				<td align="left" width="5%" style="min-width: 22px !important;" >Status</td>
				<td align="left" width="5%">'. __('sno_label').'</td>
				<td align="left" width="10%">'.ucfirst(__('driver_name')).'</td>
				<td align="left" width="10%">'.__('taxi_no').'</td>
				<td align="left" width="10%">'.__('companyname').'</td>
				<td align="left" width="10%">'.__('country_label').'</td>
				<td align="left" width="10%">'.__('city_label').'</td>
				<td align="left" width="10%">'.__('from_date').'</td>
				<td align="left" width="10%">'.__('end_date').'</td>
				</tr>
				</thead>
				<tbody>	';

				$sno=$offset; /* For Serial No */

				foreach($getmodel_details as $listings) {

				//S.No Increment
				//==============
				$sno++;

				//For Odd / Even Rows
				//===================
				$trcolor=($sno%2==0) ? 'oddtr' : 'eventr';  

				$output .='<tr class="'.$trcolor.'">
				<td>'; 

				if($listings['mapping_status']=='A')
				{  $txt = "Deactivate"; $class ="unsuspendicon";    }

				else {  $txt = "Activate"; $class ="blockicon";      }


				$output .='<a href="javascript:void(0);" title ='.$txt.' class='.$class.'></a>' ;  

				$output .='</td> 
				<td>'.$sno.'</td>
				<td>'.wordwrap(ucfirst($listings['name']),30,'<br/>',1).'<a href="javascript:;" id="change_driver"> Change </a></td>
				<td>'.wordwrap(ucfirst($listings['taxi_no']),30,'<br/>',1).'</td>
				<td>'.wordwrap(ucfirst($listings['company_name']),30,'<br/>',1).'</td>
				<td>'.wordwrap($listings['country_name'],25,'<br />',1).'</td>						
				<td>'.wordwrap($listings['city_name'],25,'<br />',1).'</td>
				<td>'.wordwrap($listings['mapping_startdate'],25,'<br />',1).'</td>
				<td>'.wordwrap($listings['mapping_enddate'],25,'<br />',1).'</td>
				</tr>';
				 } 
				} 

				//For No Records
				//==============
				else{ 
				$output .='<tr>
				<td class="nodata">'.__('no_data').'</td>
				</tr>';
				 } 
				$output .='</tbody>
				</table>';
				 if ($count > 0) { 
				$output .='</div>';
				 } 
				$output .='</div><div class="clr">&nbsp;</div>';
				$output .='<div class="pagination">';
				if($count > 0) { 
				$output .= '<p>'.$pag_data->render().'</p>';
				}
  				$output .='</div><div class="clr">&nbsp;</div>';
  
			echo $output;exit;
			
	}

	
	public function action_delete_image()
	{
		$file_path = $_REQUEST['sPath'];
		$filepath = $_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/taxi_image/';
		$taxi_id = $_REQUEST['taxi_id'];
		$imageid = $_REQUEST['image_id'];
		if(file_exists($file_path))
		{
			unlink($file_path);
		}
		$add_model = Model::factory('add');
		$count = $add_model->change_imagecount($taxi_id,$imageid);
		$output ='';
		$j= 0;

	if(is_array($count))
	{
		foreach($count as $value)
		{
			if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/'.UPLOADS.'/taxi_image/'.$taxi_id.'_'.$value.'.png'))
			{ 
			$output .='<tr>
			<td width="20%"></td>
		
			<td valign="top" width="20%">  
			
				<input type="file" class="text" name="updateimage['.$value.']" id="cpicture'.$value.'" value="" ><br>
				<span id="error'.$value.'" class="err_count" style="display:none;color:red;font-size:11px;">*Only jpeg, jpg or png images</span>
			</td>
			<td valign="top">

				<img style="margin-left:10px;" width="75" height="75" src="'.URL_BASE.'public/'.UPLOADS.'/taxi_image/'.$taxi_id.'_'.$value.'.png"  width="300" alt="Slider Image"/>
			<br />
			
				<a href="javascript:;" onclick="remove_image(\''.$filepath.$taxi_id.'_'.$value.'.png\',\''.$value.'\')" class="ml10" title="Delete">Delete</a>

			</td>
			</tr>';
		
			}
			else
			{
			$output .='<tr style="display:none;">
			<td width="20%"></td>
			<td valign="top" width="20%"><br>
			<span id="error<?php echo $value; ?>" class="err_count" style="display:none;color:red;font-size:11px;">*Only jpeg, jpg or png images</span>
			</td>
			</tr>';			
			
			 }						
		
		}
		
	}
	
		
		/*for($i=0;$j<$count;$i++)
		{ 
			if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/uploads/taxi_image/'.$taxi_id.'_'.$i.'.png'))
			 { 
			 $j++;
			$output .='<tr><td width="20%"></td>'; 
			
			$output .='<td valign="top" width="20%">  
			<input type="file" class="text" name="updateimage[]" id="cpicture'.$i.'" value="" ><br>
			<span id="error'.$i.'" class="err_count" style="display:none;color:red;font-size:11px;">*Only jpeg, jpg or png images</span>
			</td><td valign="top">';
		 
			$output .='<img style="margin-left:10px;" width="75" height="75" src="'.URL_BASE.'public/uploads/taxi_image/'.$taxi_id.'_'.$i.'.png"  width="300" /><br /><a href="javascript:;" onclick="remove_image(\''.$filepath.$taxi_id.'_'.$i.'.png\')" class="ml10" title="Delete">Delete</a>';
			
			$output .='</td></tr>';
			}
			else
			{ 
			$output .='<tr style="display:none">
			<td width="20%"></td>
			<td valign="top" width="20%">  <input type="file" class="text" name="updateimage[]" id="cpicture'.$i.'" value="" ><br>
			<span id="error'.$i.'" class="err_count" style="display:none;color:red;font-size:11px;">*Only jpeg, jpg or png images</span>
			</td>
			</tr>';
			}
		}                  
		*/
		
		echo $output;exit;
		
	}
	
	// Add menu function
	public function action_menu()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype !='A' && $usertype !='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$add_model = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_menu'); 
		$errors = array();
		$post_values = array();

		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));

			$validator = $add_model->validate_addmenu(arr::extract($post_values,array('menu_name','slug')));

			if($validator->check())
			{
				//$str_convertoUrl_val = $add_model->string_convertoUrl($post['menu_name']);

				$menu_name_exits = $add_model->menu_name_exits($post_values);
				if($menu_name_exits == 1)
				{
					Message::error(__('menu_name_exits'));
					$this->request->redirect("manage/menu");
				} 
				
			   	//$status =$add_model->addmenu($post,$str_convertoUrl_val);
			   	
			   	$status =$add_model->addmenu($post_values);
			   	
				if($status == 1) 
				{ 					
					Message::success(__('sucessfull_added_menu'));

					$this->request->redirect("manage/menu");

				}	
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view= View::factory('admin/add_menu')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_menu');
		$this->template->page_title= __('add_menu'); 
		$this->template->content = $view;
	}
	
	// Add mile function
	public function action_mile()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		//echo $usertype;exit;
		if(($usertype !='A') && ($usertype !='S'))
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$add_model = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_mile'); 
		$errors = array();
		$post_values = array();

		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			$post = Arr::map('trim', $this->request->post());

			$validator = $add_model->validate_addmile(arr::extract($post,array('mile')));

			if($validator->check())
			{
								
				$mile_name_exits = $add_model->mile_name_exits($_POST);
				if($mile_name_exits == 1)
				{
					Message::error(__('mile_name_exits'));
					$this->request->redirect("manage/mile");				
				} 
				
			   	$status =$add_model->addmile($post);
				if($status == 1) 
				{ 					
					Message::success(__('sucessfull_added_mile'));

					$this->request->redirect("manage/mile");

				}	
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view= View::factory('admin/add_mile')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_mile');
		$this->template->page_title= __('add_mile'); 
		$this->template->content = $view;
	}	

	public function action_admin()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype !='A')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$add_model = Model::factory('add');

		$cid = $this->request->param('id');
		
		if($cid !='')
		{
			$check_cid = $add_model->check_companyid($cid);
		
			if($check_cid == 0)
			{
				Message::success(__('invalid_companyid'));
				$this->request->redirect("manage/company");		
			}
		}
				
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addadmin'); 
		$country_details = $add_model->country_details();
		$city_details = $add_model->city_details();
		$state_details = $add_model->state_details();
		
		$errors = array();
		$post_values = array();
		
		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
			
			$validator = $add_model->validate_addadmin(arr::extract($post_values,array('firstname','lastname','email','password','repassword','phone','address','country','company_name')));

			if($validator->check())
			{
			   $signup_id=$add_model->addadmin($post_values);
			   
				if($signup_id == 1) 
				{ 
					$mail="";			

					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>COMPANY_SITENAME,REPLACE_USERNAME=>$post_values['firstname'],REPLACE_EMAIL=>$post_values['email'],REPLACE_PASSWORD=>$post_values['password'],REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>CONTACT_EMAIL,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'moderator_registertemp.html',$replace_variables);


					$to = $_POST['email'];
					$from = CONTACT_EMAIL;
					$subject = COMPANY_SITENAME.'-'.__('MODERATOR_CREATION_NOTIFICATION');	
					$redirect = "manage/admin";	
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

					Message::success(__('sucessfull_added_superadmin'));

					$this->request->redirect("manage/admin");

				}	
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view= View::factory('admin/add_admin')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
      				                ->bind('country_details',$country_details)
				                ->bind('city_details',$city_details)
				                ->bind('state_details',$state_details)
				                ->bind('cid',$cid)
				                ->bind('postvalue',$post_values);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_superadmin');
		$this->template->page_title= __('add_superadmin'); 
		$this->template->content = $view;
	}
	
	 /** Add Banner **/
	public function action_banner()
	{
		$this->is_login(); 
		$usertype = $_SESSION['user_type'];
		if($usertype !='C'){
			 $this->request->redirect("company/login");
		}
	
		$cid=$_SESSION['company_id'];
		
		$add = Model::factory('add');
		$errors = array();
		$banner_submit =arr::get($_REQUEST,'submit_banner'); 
		$errors = array();
		$post_values = array();
		if ($banner_submit && Validation::factory($_POST,$_FILES) ) 
		{
			$post_values = $_POST;
			
			$post = Arr::map('trim', $this->request->post());
			
			$validator = $add->validate_addbanner(arr::extract($post,array('banner_image','tags','image_tag')),$_FILES);
			
			if($validator->check())
			{
				$image_updated_status = '';
				
				$image_id = $cid;
				if(!empty($_FILES['banner_image']['name'])){
				/* image1 */
				$image_name1 = uniqid().$_FILES['banner_image']['name']; 
				$image_type=explode('.',$image_name1);
				$image_type=end($image_type);
				
				//$image_name=url::title($image_name).'.'.$image_type;
				$filename = Upload::save($_FILES['banner_image'],$image_name1,DOCROOT.BANNER_IMGPATH);
				
				//Image resize and crop for thumb image
				$logo_image1 = Image::factory($filename);
				$path11=DOCROOT.BANNER_IMGPATH;
				$path1=$image_name1;
				
				Commonfunction::imageresize($logo_image1,BANNER_SLIDER_WIDTH, BANNER_SLIDER_HEIGHT,$path11,$image_name1,90);
				}
				$tags=$_POST['tags'];
				$image_tag=$_POST['image_tag'];
				$image_updated_status = $add->update_banner($tags,$image_tag,$path1,$image_id);
		
				Message::success(__('banner_added_successfully'));				
				$this->request->redirect("manage/banner");
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$this->selected_page_title = __("add_banner");
		$view= View::factory('admin/add_banner')
		->bind('validator', $validator)
		->bind('errors', $errors)
		->bind('postvalue',$post_values)
		->bind('site_settings', $site_settings);               

		$this->template->title= SITENAME." | ".__('add_banner');
		$this->template->page_title= __('add_banner');
		$this->template->content = $view;
	}

	public function action_faq()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype !='A' && $usertype !='S')
		{
			$this->request->redirect("admin/login");
		}
		$add_model = Model::factory('add');
		/**To get the form submit button name**/
		$signup_submit =arr::get($_REQUEST,'submit_addmodel'); 
		$errors = array();
		$post_values = array();

		if ($signup_submit && Validation::factory($_POST) ) 
		{
			$post_values = Securityvalid::sanitize_inputs(Arr::map('trim', $this->request->post()));
			$validator = $add_model->validate_addfaq(arr::extract($post_values,array('faq_title','faq_details')));
			if($validator->check())
			{
				$signup_id=$add_model->addfaq($post_values);
				if($signup_id == 1) 
				{ 
					Message::success(__('sucessfull_added_faq'));
					$this->request->redirect("manage/faq");
				}
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
		$view = View::factory('admin/add_faq')
				->bind('validator', $validator)
				->bind('errors', $errors)
				->bind('postvalue',$post_values);

		$this->template->content = $view;
		$this->template->title= SITENAME." | ".__('add_faq');
		$this->template->page_title= __('add_faq'); 
		$this->template->content = $view;
	}	
	
	public function action_getcountry()
	{
		$output ='';
		$company_id =arr::get($_REQUEST,'company_id');
		$state_id = '';
		$city_id = '';
		if(isset($company_id))
		{
			$location = $this->commonmodel->get_country_details($company_id);
			if(count($location) > 0)
			{
				$output .='<option value="'.$location[0]["login_country"].'">'.$location[0]["country_name"].'</option>';
				$state_id = $location[0]["login_state"];
				$city_id = $location[0]["login_city"];
			}
			else
			{
				$output .='<option value="">--Select--</option>';
					   
			}

		}
			echo $output."~".$state_id."~".$city_id;exit;
			
	}
	
	public function action_getTelephoneCode()
	{
		$post = $this->request->post();
		$telephone_code = '';
		if($post['country_id'] != '') {
			$telephone_code = $this->commonmodel->getCountryTblFlds('telephone_code',$post['country_id']);
		}
		echo $telephone_code;exit;
	}
	/** Function to get taxi min speed and speed from the selected model **/
	public function action_getTaxiSpeed()
	{
		$post = $this->request->post();
		$resArr = array();
		if(!empty($post['modelId'])){
			$resArr = $this->commonmodel->getTaxiSpeed($post['modelId']);
		}
		echo json_encode($resArr);exit;
	}
	
	public function action_coupon()
	{
		$this->is_secondary_login();
		$admin_secret_key = "abcd";
		
		//Page Title
		$this->page_title =  __('generate_coupon');
		$this->selected_page_title = __('generate_coupon');
		
		$usertype = $_SESSION['user_type'];
		if($usertype == 'M')
			$this->request->redirect("manager/login");
		
		//company id from session
		$company_id = $this->session->get('company_id');
		$add_model = Model::factory('add');
		$manage = Model::factory('manage');
		$get_coupon_code = $add_model->getcouponcode();
		$errors = array();
		$post_values = array();
		$generate_coupon =arr::get($_REQUEST,'generate_coupon'); 
		$print_generate_coupon =arr::get($_REQUEST,'print_generate_coupon'); 
		//if ($_POST) 
		if ( ($generate_coupon || $print_generate_coupon) && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			$values = Arr::map('trim', $this->request->post());			
			$values['coupon_count'] = filter_var( $values['coupon_count'], FILTER_SANITIZE_STRIPPED );
			$values['coupon_amt'] = filter_var( $values['coupon_amt'], FILTER_SANITIZE_STRIPPED );
			$values['coupon_name'] = filter_var( $values['coupon_name'], FILTER_SANITIZE_STRIPPED );
			$values['start_date'] = filter_var( $values['start_date'], FILTER_SANITIZE_STRIPPED );
			$values['expire_date'] = filter_var( $values['expire_date'], FILTER_SANITIZE_STRIPPED );
			
			$validate = $add_model->generate_coupon_validate($values);
			
			if( $validate->check() ) 
			{					
				$driver_coupons_list = $add_model->add_driver_coupons($values);
				
				if(count($driver_coupons_list)>0)
				{
					// $xls_output1="";
					
					// $startdate=$values['start_date'];$enddate=$values['expire_date'];
					// $coupon_count = $values['coupon_count'];

					if($print_generate_coupon){

						// 	if($startdate != ""){
						// 	$headlable = __('coupon_code_report').' '.__('from').' '.$startdate.' '.__('to').' '.$enddate;
						// 	}else{	
						// 		$headlable = __('coupon_code_report');
						// 	}
						// $xls_output1 = '<style>
						// 			h1 {color: navy;font-family: "Times New Roman", Times, serif;font-size: 18pt;}	
						// 			td {color:#000000;font-family: "Times New Roman", Times, serif;}
						// 			table, th, td {border: 1px solid #eee;}
						// 			table {border-collapse: collapse;width: 100%;}
						// 			th, td {text-align: left;padding: 8px;}
						// 			tr:nth-child(even){background-color: #f2f2f2}
						// 			th {background-color: #4CAF50;color: white;}
						// 			.head_border {text-align:center;}
						// 			</style>';
						// $xls_output1 .=	'<table border="0" cellpadding="2" cellspacing="2">';
						// $xls_output1 .='<tr> <td style="text-align:center;">'.$headlable.'</td></tr>';
						// $xls_output1 .='<tr><td class="" style="text-align:center;">'.__('create_date')." - ".date("F j, Y").'</td></tr>';
						
						// $xls_output1 .='</table>';
						// $xls_output1 .= '<table border="0" cellpadding="2" cellspacing="2" >'; 
						
						// $xls_output1 .= '<tr border="" style="background-color: #4CAF50;color: #FFF;"><th class="head_border">'.__('sno').'</th>';
						// $xls_output1 .= '<th class="head_border">'.__('coupon_code').'</th>';
						// $xls_output1 .= '<th class="head_border" style="">'.__('amount').'</th>';
						// $xls_output1 .= '<th class="head_border">'.__('serial_number').'</th>';
						// $xls_output1 .= '<th class="head_border">'.__('expire_date').'</th>';
						// $xls_output1 .= '</tr>'; 
						// $sno=0;
						// foreach($driver_coupons_list as $result)
						// {
						// 	$sno=$sno+1;
						// 	$xls_output1 .= '<tr><td class="head_border">'.$sno.'</td>'; 
						// 	$xls_output1 .= '<td class="head_border">'.$result['coupon_code'].'</td>'; 
						// 	$xls_output1 .= '<td class="head_border" style="">'.$result['coupon_amt'].'</td>'; 
						// 	$xls_output1 .= '<td class="head_border" style="">'.$result['serial_number'].'</td>'; 
						// 	$xls_output1 .= '<td class="head_border">'.$enddate.'</td></tr>'; 
						// }
						// $xls_output1 .= "</table>";						
						// $file = 'Export';
						// $filename = $file."_".date("Y-m-d_H-i",time());
						// $html = preg_replace("<tbody>"," ",$xls_output1);
						// $html = preg_replace("</tbody>"," ",$html);
						
						// ob_clean();			
						// $generate_pdf = $manage->generate_pdf($html,$filename);
						
						Session::instance()->set('drivers_coupons', $driver_coupons_list);
						
						Message::success( __( 'Driver coupon added successfully' ) );
						//$this->request->redirect('print/coupon');
						$data = array();
						$data['status'] = 1;
						$data['redirect_url'] = URL_BASE.'print/coupon';
						echo json_encode($data);
						exit;
						
					}else{
						Message::success( __( 'Driver coupon added successfully' ) );
						$this->request->redirect('manage/coupon');
					}
					
				}
				else
				{
					
					Message::error( __( 'Error in add driver coupon' ) );
				}
			} 
			else 
			{
				Message::error( __( 'Invalid driver coupon details ...' ) );	
				$errors = $validate-> errors('errors');
			}
		}

		if(TIMEZONE)
		{
			$current_time = convert_timezone('now',TIMEZONE);
			$timestamp = strtotime($current_time);
			$current_time_str = date('YmdHis', $timestamp);
		}
		else
		{
			$current_time_str = date('YmdHis');
		}

		$generate_id = $current_time_str;
		$view = View::factory('admin/generate_coupon')
				->bind('errors', $errors)
				->bind('postvalue',$post_values)
				->bind('title',$title)
				->bind('couponcode',$get_coupon_code)
				->bind('company_id',$company_id)
				->bind('generate_id',$generate_id);

		$this->template->title = SITENAME.' '.__('generate_coupon');
		$this->template->page_title=__('generate_coupon');
		$this->template->content = $view;
	}
	
	public function action_passenger_coupon()
	{
		//Page Title
		$this->is_secondary_login();
		$this->page_title =  __('generate_coupon');
		$this->selected_page_title = __('generate_coupon');
		
		$usertype = $_SESSION['user_type'];
		if($usertype == 'M')
			$this->request->redirect("manager/login");
		
		//company id from session
		$company_id = $this->session->get('company_id');
		$add_model = Model::factory('add');
		$manage = Model::factory('manage');
		$get_coupon_code = $add_model->passsenger_getcouponcode();
		$errors = array();
		$post_values = array();
		$generate_coupon =arr::get($_REQUEST,'generate_coupon'); 
		$print_generate_coupon =arr::get($_REQUEST,'print_generate_coupon'); 
		//if ($_POST) 
		if ( ($generate_coupon || $print_generate_coupon) && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			$values = Arr::map('trim', $this->request->post());			
			$values['coupon_count'] = filter_var( $values['coupon_count'], FILTER_SANITIZE_STRIPPED );
			$values['coupon_amt'] = filter_var( $values['coupon_amt'], FILTER_SANITIZE_STRIPPED );
			$values['coupon_name'] = filter_var( $values['coupon_name'], FILTER_SANITIZE_STRIPPED );
			$values['start_date'] = filter_var( $values['start_date'], FILTER_SANITIZE_STRIPPED );
			$values['expire_date'] = filter_var( $values['expire_date'], FILTER_SANITIZE_STRIPPED );
			
			$validate = $add_model->passenger_generate_coupon_validate($values);
			
			if( $validate->check() ) 
			{					
				$driver_coupons_list = $add_model->passenger_add_driver_coupons($values);
				
				if(count($driver_coupons_list)>0)
				{
					// $xls_output1="";
					
					// $startdate=$values['start_date'];$enddate=$values['expire_date'];
					// $coupon_count = $values['coupon_count'];

					if($print_generate_coupon){

						// 	if($startdate != ""){
						// 	$headlable = __('coupon_code_report').' '.__('from').' '.$startdate.' '.__('to').' '.$enddate;
						// 	}else{	
						// 		$headlable = __('coupon_code_report');
						// 	}
						// $xls_output1 = '<style>
						// 			h1 {color: navy;font-family: "Times New Roman", Times, serif;font-size: 18pt;}	
						// 			td {color:#000000;font-family: "Times New Roman", Times, serif;}
						// 			table, th, td {border: 1px solid #eee;}
						// 			table {border-collapse: collapse;width: 100%;}
						// 			th, td {text-align: left;padding: 8px;}
						// 			tr:nth-child(even){background-color: #f2f2f2}
						// 			th {background-color: #4CAF50;color: white;}
						// 			.head_border {text-align:center;}
						// 			</style>';
						// $xls_output1 .=	'<table border="0" cellpadding="2" cellspacing="2">';
						// $xls_output1 .='<tr> <td style="text-align:center;">'.$headlable.'</td></tr>';
						// $xls_output1 .='<tr><td class="" style="text-align:center;">'.__('create_date')." - ".date("F j, Y").'</td></tr>';
						
						// $xls_output1 .='</table>';
						// $xls_output1 .= '<table border="0" cellpadding="2" cellspacing="2" >'; 
						
						// $xls_output1 .= '<tr border="" style="background-color: #4CAF50;color: #FFF;"><th class="head_border">'.__('sno').'</th>';
						// $xls_output1 .= '<th class="head_border">'.__('coupon_code').'</th>';
						// $xls_output1 .= '<th class="head_border" style="">'.__('amount').'</th>';
						// $xls_output1 .= '<th class="head_border">'.__('serial_number').'</th>';
						// $xls_output1 .= '<th class="head_border">'.__('expire_date').'</th>';
						// $xls_output1 .= '</tr>'; 
						// $sno=0;
						// foreach($driver_coupons_list as $result)
						// {
						// 	$sno=$sno+1;
						// 	$xls_output1 .= '<tr><td class="head_border">'.$sno.'</td>'; 
						// 	$xls_output1 .= '<td class="head_border">'.$result['coupon_code'].'</td>'; 
						// 	$xls_output1 .= '<td class="head_border" style="">'.$result['coupon_amt'].'</td>'; 
						// 	$xls_output1 .= '<td class="head_border" style="">'.$result['serial_number'].'</td>'; 
						// 	$xls_output1 .= '<td class="head_border">'.$enddate.'</td></tr>'; 
						// }
						// $xls_output1 .= "</table>";						
						// $file = 'Export';
						// $filename = $file."_".date("Y-m-d_H-i",time());
						// $html = preg_replace("<tbody>"," ",$xls_output1);
						// $html = preg_replace("</tbody>"," ",$html);
						
						// ob_clean();			
						// $generate_pdf = $manage->generate_pdf($html,$filename);
						
						Session::instance()->set('drivers_coupons', $driver_coupons_list);
						
						Message::success( __( 'Passenger coupon added successfully' ) );
						//$this->request->redirect('print/coupon');
						$data = array();
						$data['status'] = 1;
						$data['redirect_url'] = URL_BASE.'print/coupon';
						echo json_encode($data);
						exit;
						
					}else{
						Message::success( __( 'Passenger coupon added successfully' ) );
						$this->request->redirect('manage/passenger_coupon');
					}
					
				}
				else
				{
					
					Message::error( __( 'Error in add passenger coupon' ) );
				}
			} 
			else 
			{
				Message::error( __( 'Invalid passenger coupon details ...' ) );	
				$errors = $validate-> errors('errors');
			}
		}

		if(TIMEZONE)
		{
			$current_time = convert_timezone('now',TIMEZONE);
			$timestamp = strtotime($current_time);
			$current_time_str = date('YmdHis', $timestamp);
		}
		else
		{
			$current_time_str = date('YmdHis');
		}

		$generate_id = $current_time_str;
		$view = View::factory('admin/passenger_generate_coupon')
				->bind('errors', $errors)
				->bind('postvalue',$post_values)
				->bind('title',$title)
				->bind('couponcode',$get_coupon_code)
				->bind('company_id',$company_id)
				->bind('generate_id',$generate_id);

		$this->template->title = SITENAME.' '.__('generate_coupon');
		$this->template->page_title=__('generate_coupon');
		$this->template->content = $view;
	}
	
} // End Welcome
?>
