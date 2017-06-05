<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Tdispatch extends Controller_Siteadmin 
{

	/**
	****__construct()****
	* 
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
			$this->request->redirect("/company/login/");
		}
		return;
	}

	//Admin Transactions without Search action 
	public function action_bookings()
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
		$manage_transaction = Model::factory('transaction');
		$common_model = Model::factory('commonmodel');
		$tdispatch_model = Model::factory('tdispatch');
		$add_model = Model::factory('add');

		if ( $list == 'all' ) {
			$page_title = __("all_bookings");
		} elseif ( $list == 'success' ) {
			$page_title = __("success_bookings");
		} elseif ( $list == 'cancelled' ) {
			$page_title = __("cancelled_bookings");
		} elseif ( $list =='rejected') {
			$page_title = __("rejected_bookings");
		} else	{
			$page_title = __("all_bookings");
			$list = 'all';
		}

		$get_allcompany = $manage_transaction->get_allcompany_tranaction($usertype);
		$taxilist = $manage_transaction->gettaxidetails('','');
		$passengerlist = $manage_transaction->getpassengerdetails('','');
		$driverlist = $manage_transaction->getdriverdetails('','');
		$managerlist = $manage_transaction->getmanagerdetails('');
		$count_transaction_list = $tdispatch_model->count_bookings_list($list,'All','All','All','All','All','','','','');
		$grpahdata = $manage_transaction->getgraphvalues($list,'All','All','All','All','','','','','');
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
   
		$all_transaction_list = $tdispatch_model->bookings_details($list,'All','All','All','All','All','','',$offset, REC_PER_PAGE,'','');
		//****pagination ends here***//

		//send data to view file 
		
		$view = View::factory('admin/tdispatch/bookings_report')
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


	public function action_admintransaction_list()
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

		if ( $list == 'all' ) {
			$page_title = __("all_bookings");
		} elseif ( $list == 'success' ) {
			$page_title = __("success_bookings");
		} elseif ( $list == 'cancelled' ) {
			$page_title = __("cancelled_bookings");
		} elseif ( $list =='rejected') {
			$page_title = __("rejected_bookings");
		} else	{
			$page_title = __("all_bookings");
			$list = 'all';
		}

		$company = trim(Html::chars($_REQUEST['filter_company']));
		$startdate = trim(Html::chars($_REQUEST['startdate']));
		$enddate = trim(Html::chars($_REQUEST['enddate']));
		$taxiid = trim(Html::chars($_REQUEST['taxiid']));
		$driver_id = trim(Html::chars($_REQUEST['driver_id']));
		$manager_id = trim(Html::chars($_REQUEST['manager_id']));
		$passengerid = trim(Html::chars($_REQUEST['passengerid']));
		$transaction_id = trim(Html::chars($_REQUEST['transaction_id']));
		$payment_type = trim(Html::chars($_REQUEST['payment_type']));

		$manage_transaction = Model::factory('transaction');
		$common_model = Model::factory('commonmodel');
		$tdispatch_model = Model::factory('tdispatch');

		$get_allcompany = $manage_transaction->get_allcompany_tranaction();
		$taxilist = $manage_transaction->gettaxidetails($company,$manager_id);
		$passengerlist = $manage_transaction->getpassengerdetails($company,'');
		$driverlist = $manage_transaction->getdriverdetails($company,$manager_id);
		$managerlist = $manage_transaction->getmanagerdetails($company);
		$count_transaction_list = $tdispatch_model->count_bookings_list($list,$company,$manager_id,$taxiid,$driver_id,$passengerid,$startdate,$enddate,$transaction_id,$payment_type);

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
   
		$all_transaction_list = $tdispatch_model->bookings_details($list,$company,$manager_id,$taxiid,$driver_id,$passengerid,$startdate,$enddate,$offset, REC_PER_PAGE,$transaction_id,$payment_type);
		$grpahdata = $manage_transaction->getgraphvalues($list,$company,$manager_id,$taxiid,$driver_id,$passengerid,$startdate,$enddate,$transaction_id,$payment_type);
		$gateway_details = $common_model->gateway_details();
		//****pagination ends here***//

		//send data to view file 
		
		$view = View::factory('admin/tdispatch/bookings_report')
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

	public function action_addbooking()
	{
		$user_createdby = $_SESSION['userid'];
	
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$add_model = Model::factory('add');
		$tdispatch_model = Model::factory('tdispatch');
		$common_model = Model::factory('commonmodel');	

		$company_id = $_SESSION['company_id'];
		$company_tax = $common_model->company_tax($company_id);	
		$company_timezone = $common_model->company_timezone($company_id);	

		/**To get the form submit button name**/
		$create_submit =arr::get($_REQUEST,'create'); 
		$dispatch_submit =arr::get($_REQUEST,'dispatch');
		//print_r($dispatch_submit);exit;
		$country_details = $add_model->country_details();
		$city_details = $add_model->city_details();
		$state_details = $add_model->state_details();
		$package_details = $add_model->package_details();
		$model_details = $tdispatch_model->model_details();
		$additional_fields = $add_model->taxi_additionalfields();
		$get_suggestedlocation = $tdispatch_model->get_suggestedlocation();
		$get_suggestedjourney = $tdispatch_model->get_suggestedjourney();
		$get_gatewaydetails = $tdispatch_model->get_gatewaydetails();

		$errors = array();
		$post_values = array();

		if (($create_submit || $dispatch_submit)&& Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			//print_r($post_values);exit;

			$post = Arr::map('trim', $this->request->post());
			//$validator = $tdispatch_model->validate_dispatchbooking(arr::extract($post,array('firstname','email','phone','current_location','pickup_lat','pickup_lng','drop_location','drop_lat','drop_lng','pickup_date','pickup_time','luggage','no_passengers','taxi_model','recurrent','lablename','frmdate','todate','total_fare','notes')));
			$validator = $tdispatch_model->validate_dispatchbooking(arr::extract($post,array('firstname','email','phone','current_location','pickup_lat','pickup_lng','drop_location','drop_lat','drop_lng','pickup_date','pickup_time','taxi_model','recurrent','lablename','frmdate','todate','total_fare','notes')));
			
			if($validator->check())
			{

			$random_key = text::random($type = 'alnum', $length = 10);				
			$password = text::random($type = 'alnum', $length = 6);		
 			   
			   $req_result = $tdispatch_model->addbooking($post,$random_key,$password,$company_tax);
			   
			  //print_r($req_result);exit;

				$send_mail = $req_result['send_mail'];	
				$passenger_logid = $req_result['pass_logid'];
				$recurrent_id = $req_result['recurrent_id'];
				$insert_booking = $req_result['insert_booking'];	

					
					if($passenger_logid != '')		
					{
					/* Create Log */		
					$company_id = $_SESSION['company_id'];			
					$log_message = __('log_message_added');
					$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
					$log_booking = __('log_booking_added');
					$log_booking = str_replace("PASS_LOG_ID",$passenger_logid,$log_booking); 
					$log_status = $tdispatch_model->create_logs($passenger_logid,$company_id,$user_createdby,$log_message,$log_booking);
					/* Create Log */	
					}
					else
					{
					$company_id = $_SESSION['company_id'];			
					$log_message = __('log_message_recurrent_added');
					$log_message = str_replace("RECURRENT_ID",$recurrent_id,$log_message); 
					$log_status = $tdispatch_model->create_logs($recurrent_id,$company_id,$user_createdby,$log_message);
					}
				

				if(($passenger_logid))
				{ 

					if($send_mail == 'S') 
					{
						/** Mail to new User **/
							$mail="";	
	/*		
							$link = URL_BASE.'passengers/activateaccount/?key='.$random_key;					
							$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_NAME=>ucfirst($_POST['firstname']),REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ACTLINK=>$link);
							$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'customer_activation.html',$replace_variables);
	*/

						$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$post['firstname'],REPLACE_MOBILE=>$post['phone'],REPLACE_PASSWORD=>$password,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
						$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'passenger-register.html',$replace_variables);



							$to = $_POST['email'];
							$from = $this->siteemail;
							$subject = __('activation_subject');	
							$redirect = "tdispatch/addbooking";	
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
								$message_details = $common_model->sms_message('1');
								$to = $_POST['phone'];
								$message = $message_details[0]['sms_description'];
								$message = str_replace("##SITE_NAME##",SITE_NAME,$message);

								//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");

							}
						/** Mail to new User **/
					}
						/** Dispatch **/
						$company_id = $_SESSION['company_id'];
						$company_dispatch = DB::select()->from(TBLALGORITHM)
									->where('alg_company_id','=',$company_id)
									->order_by('aid','desc')
									->limit(1)
									->execute()
									->as_array();
						
						$tdispatch_type = '';
						if(count($company_dispatch) > 0)	
						{
							 $tdispatch_type = $company_dispatch[0]['labelname'];
						}

						//exit;
						/*if($insert_booking =='N')
						{
							Message::success(__('booking_added'));
							$this->request->redirect("tdispatch/managebooking#stuff/");
						}
						*/
						//Message::success(__('booking_added'));
						//$this->request->redirect("tdispatch/managebooking#stuff/");
				
						/* Check passsenger log id & tdispatchj submit button clicked */
						//echo $dispatch_submit;
						//echo '<br>';
						//echo $tdispatch_type;
						//echo '<br>';
						//echo $passenger_logid;
						//exit;
						if($dispatch_submit && $tdispatch_type  != 1 && $passenger_logid != '' && $recurrent_id == "")
						{ 
							//echo 'as';exit;?>

							<form action="<?php echo URL_BASE;?>tdispatch/managebooking" method="post" id="form_showpopup">
							  <input type="hidden" name="show_pass_logid" value="<?php echo $passenger_logid; ?>" id="show_pass_logid" />
							  <input type="hidden" name="pickup_latitude" value="<?php echo $post['pickup_lat']; ?>" id="pickup_latitude" />
							  <input type="hidden" name="pickup_longitude" value="<?php echo $post['pickup_lng']; ?>" id="pickup_longitude" />
							  <input type="hidden" name="drop_latitude" value="<?php echo $post['drop_lat']; ?>" id="drop_longitude" />
							  <input type="hidden" name="drop_longitude" value="<?php echo $post['drop_lng']; ?>" id="drop_latitude" />
							  <input type="hidden" name="no_passengers" value="<?php echo $post['no_passengers']; ?>" id="no_passengers" />
							</form>
							<script>	
									document.getElementById('form_showpopup').submit();
							</script>
						<?php
						}
						else
						{
							Message::success(__('booking_added'));
							$this->request->redirect("tdispatch/managebooking");
						}
				}
				else	
				{
					Message::success(__('booking_added'));
					$this->request->redirect("tdispatch/managebooking");
				}
			     
			}
			else
			{
				$errors = $validator->errors('errors');
				print_r($errors);exit;
			}
		}

				$view= View::factory('admin/tdispatch/add_booking')
						->bind('validator', $validator)
						->bind('errors', $errors)
						->bind('company_tax', $company_tax)
						->bind('company_timezone',$company_timezone)
						->bind('country_details',$country_details)
						->bind('city_details',$city_details)
						->bind('state_details',$state_details)
						->bind('package_details',$package_details)
						->bind('model_details',$model_details)
						->bind('additional_fields',$additional_fields)
						->bind('get_suggestedlocation',$get_suggestedlocation)
						->bind('get_suggestedjourney',$get_suggestedjourney)
						->bind('get_gatewaydetails',$get_gatewaydetails)
						->bind('postvalue',$post_values);
		//$this->template->content = $view;
		echo $view;exit;   
		$this->template->title= SITENAME." | ".__('bookings');
		$this->template->page_title= __('bookings'); 
		$this->template->content = $view;
	}


	public function action_booking()
	{
		$user_createdby = $_SESSION['userid'];
	
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$add_model = Model::factory('add');
		$tdispatch_model = Model::factory('tdispatch');
		$common_model = Model::factory('commonmodel');	

		$company_id = $_SESSION['company_id'];
		$company_tax = $common_model->company_tax($company_id);	
		$company_timezone = $common_model->company_timezone($company_id);	

		/**To get the form submit button name**/
		$create_submit =arr::get($_REQUEST,'create'); 
		$dispatch_submit =arr::get($_REQUEST,'dispatch');
		//print_r($dispatch_submit);exit;
		$country_details = $add_model->country_details();
		$city_details = $add_model->city_details();
		$state_details = $add_model->state_details();
		$package_details = $add_model->package_details();
		$model_details = $tdispatch_model->model_details();
		$additional_fields = $add_model->taxi_additionalfields();
		$get_suggestedlocation = $tdispatch_model->get_suggestedlocation();
		$get_suggestedjourney = $tdispatch_model->get_suggestedjourney();
		$get_gatewaydetails = $tdispatch_model->get_gatewaydetails();

		$errors = array();
		$post_values = array();

		if (($create_submit || $dispatch_submit)&& Validation::factory($_POST) ) 
		{
			$post_values = $_POST;


			$post = Arr::map('trim', $this->request->post());
			//$validator = $tdispatch_model->validate_dispatchbooking(arr::extract($post,array('firstname','email','phone','current_location','pickup_lat','pickup_lng','drop_location','drop_lat','drop_lng','pickup_date','pickup_time','luggage','no_passengers','taxi_model','recurrent','lablename','frmdate','todate','total_fare','notes')));
			$validator = $tdispatch_model->validate_dispatchbooking(arr::extract($post,array('firstname','email','phone','current_location','pickup_lat','pickup_lng','drop_location','drop_lat','drop_lng','pickup_date','pickup_time','taxi_model','recurrent','lablename','frmdate','todate','total_fare','notes')));
			
			if($validator->check())
			{

			$random_key = text::random($type = 'alnum', $length = 10);				
			$password = text::random($type = 'alnum', $length = 6);		
 			   
			   $req_result = $tdispatch_model->addbooking($post,$random_key,$password,$company_tax);
			   
			  //print_r($req_result);exit;

				$send_mail = $req_result['send_mail'];	
				$passenger_logid = $req_result['pass_logid'];
				$recurrent_id = $req_result['recurrent_id'];
				$insert_booking = $req_result['insert_booking'];	

					
					if($passenger_logid != '')		
					{
					/* Create Log */		
					$company_id = $_SESSION['company_id'];			
					$log_message = __('log_message_added');
					$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
					$log_booking = __('log_booking_added');
					$log_booking = str_replace("PASS_LOG_ID",$passenger_logid,$log_booking); 
					$log_status = $tdispatch_model->create_logs($passenger_logid,$company_id,$user_createdby,$log_message,$log_booking);
					/* Create Log */	
					}
					else
					{
					$company_id = $_SESSION['company_id'];			
					$log_message = __('log_message_recurrent_added');
					$log_message = str_replace("RECURRENT_ID",$recurrent_id,$log_message); 
					$log_status = $tdispatch_model->create_logs($recurrent_id,$company_id,$user_createdby,$log_message);
					}
				

				if(($passenger_logid))
				{ 

					if($send_mail == 'S') 
					{
						/** Mail to new User **/
							$mail="";	
	/*		
							$link = URL_BASE.'passengers/activateaccount/?key='.$random_key;					
							$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_NAME=>ucfirst($_POST['firstname']),REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ACTLINK=>$link);
							$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'customer_activation.html',$replace_variables);
	*/

						$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$post['firstname'],REPLACE_MOBILE=>$post['phone'],REPLACE_PASSWORD=>$password,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
						$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'passenger-register.html',$replace_variables);



							$to = $_POST['email'];
							$from = $this->siteemail;
							$subject = __('activation_subject');	
							$redirect = "tdispatch/addbooking";	
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
								$message_details = $common_model->sms_message('1');
								$to = $_POST['phone'];
								$message = $message_details[0]['sms_description'];
								$message = str_replace("##SITE_NAME##",SITE_NAME,$message);

								//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");

							}
						/** Mail to new User **/
					}
						/** Dispatch **/
						$company_id = $_SESSION['company_id'];
						$company_dispatch = DB::select()->from(TBLALGORITHM)
									->where('alg_company_id','=',$company_id)
									->order_by('aid','desc')
									->limit(1)
									->execute()
									->as_array();
						
						$tdispatch_type = '';
						if(count($company_dispatch) > 0)	
						{
							 $tdispatch_type = $company_dispatch[0]['labelname'];
						}

						//exit;
						/*if($insert_booking =='N')
						{
							Message::success(__('booking_added'));
							$this->request->redirect("tdispatch/managebooking#stuff/");
						}
						*/
						//Message::success(__('booking_added'));
						//$this->request->redirect("tdispatch/managebooking#stuff/");
				
						/* Check passsenger log id & tdispatchj submit button clicked */
						//echo $dispatch_submit;
						//echo '<br>';
						//echo $tdispatch_type;
						//echo '<br>';
						//echo $passenger_logid;
						//exit;
						if($dispatch_submit && $tdispatch_type  != 1 && $passenger_logid != '' && $recurrent_id == "")
						{ 
							//echo 'as';exit;?>

							<form action="<?php echo URL_BASE;?>tdispatch/managebooking" method="post" id="form_showpopup">
							  <input type="hidden" name="show_pass_logid" value="<?php echo $passenger_logid; ?>" id="show_pass_logid" />
							  <input type="hidden" name="pickup_latitude" value="<?php echo $post['pickup_lat']; ?>" id="pickup_latitude" />
							  <input type="hidden" name="pickup_longitude" value="<?php echo $post['pickup_lng']; ?>" id="pickup_longitude" />
							  <input type="hidden" name="drop_latitude" value="<?php echo $post['drop_lat']; ?>" id="drop_longitude" />
							  <input type="hidden" name="drop_longitude" value="<?php echo $post['drop_lng']; ?>" id="drop_latitude" />
							  <input type="hidden" name="no_passengers" value="<?php echo $post['no_passengers']; ?>" id="no_passengers" />
							</form>
							<script>	
									document.getElementById('form_showpopup').submit();
							</script>
						<?php
						}
						else
						{
							Message::success(__('booking_added'));
							$this->request->redirect("tdispatch/managebooking");
						}
				}
				else	
				{
					Message::success(__('booking_added'));
					$this->request->redirect("tdispatch/managebooking");
				}
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

				$view= View::factory('admin/tdispatch/booking')
						->bind('validator', $validator)
						->bind('errors', $errors)
						->bind('company_tax', $company_tax)
						->bind('company_timezone',$company_timezone)
						->bind('country_details',$country_details)
						->bind('city_details',$city_details)
						->bind('state_details',$state_details)
						->bind('package_details',$package_details)
						->bind('model_details',$model_details)
						->bind('additional_fields',$additional_fields)
						->bind('get_suggestedlocation',$get_suggestedlocation)
						->bind('get_suggestedjourney',$get_suggestedjourney)
						->bind('get_gatewaydetails',$get_gatewaydetails)
						->bind('postvalue',$post_values);
		$this->template->title= SITENAME." | ".__('bookings');
		$this->template->page_title= __('bookings'); 
		$this->template->content = $view;
	}	
	


	public function action_getDistanceandtime()
	{

		$output = '';
		$current_location =arr::get($_REQUEST,'current_location'); 
		$drop_location =arr::get($_REQUEST,'drop_location'); 
		$current_location = urlencode($current_location);
		$drop_location = urlencode($drop_location);

		$json = file_get_contents('https://maps.googleapis.com/maps/api/directions/json?origin='.$current_location.'&destination='.$drop_location.'&waypoints=&sensor=false&key='.GOOGLE_GEO_API_KEY);

		$details = json_decode($json, TRUE);
			
		$distance = $details['routes'][0]['legs']['0']['distance']['text'];
		$duration = $details['routes'][0]['legs']['0']['duration']['text'];
		$output = $distance.','.$duration;
		echo $output;exit;
	}
	
	public function action_group_load()
	{
		$url = $_SERVER['REQUEST_URI'];
		$acid = explode('=', $url);
		$antid = explode('&', $acid[1]);
		$accountid = $antid[0]; 
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';
		$like_q =arr::get($_REQUEST,'q'); 
		$like_q = urlencode($like_q);
		echo $cname = "select\n";
		$getmodel_details = $tdispatch_model->getgroup_details($like_q,$accountid);

		foreach($getmodel_details as $details) {
			$groupname = $details['department'];
			echo "$groupname\n";

		}
		exit;
	}

	public function action_firstname_load()
	{ 
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';
		$like_q =arr::get($_REQUEST,'q'); 
		$like_q = urlencode($like_q);
		$getmodel_details = $tdispatch_model->getuser_details($like_q,'1');
		foreach($getmodel_details as $details) {
			$cname = $details['name'].' - ('.$details['phone'].')';
			echo "$cname\n";
		}
		exit;
	}

	public function action_firstname_load_new()
	{ 
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';
		$like_q =arr::get($_REQUEST,'q'); 
		$like_q = urlencode($like_q);
		$getmodel_details = $tdispatch_model->getuser_details($like_q,'1');
		
		foreach($getmodel_details as $details) {
			$name[] = $details['name'].' - ('.$details['phone'].')';
		}
		echo json_encode($name);exit;
	}

	public function action_email_load_new()
	{
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';
		$like_q =arr::get($_REQUEST,'q'); 

		$like_q = urlencode($like_q);
		$getmodel_details = $tdispatch_model->getuser_details($like_q,'2');

		foreach($getmodel_details as $details) {
			$name[] = $details['email'];
		}
		echo json_encode($name);exit;
	}

	public function action_email_load()
	{
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';
		$like_q =arr::get($_REQUEST,'q'); 

		$like_q = urlencode($like_q);
		$getmodel_details = $tdispatch_model->getuser_details($like_q,'2');

		foreach($getmodel_details as $details) {
			$cname = $details['email'];
			echo "$cname\n";

		}
		exit;
	}

	public function action_phone_load()
	{
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';
		$like_q =arr::get($_REQUEST,'q'); 

		$like_q = urlencode($like_q);
		$getmodel_details = $tdispatch_model->getuser_details($like_q,'3');

		foreach($getmodel_details as $details) {
			$cname = $details['phone'];
			echo "$cname\n";

		}
		exit;
	}

	public function action_phone_load_new()
	{
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';
		$like_q =arr::get($_REQUEST,'q'); 

		$like_q = urlencode($like_q);
		$getmodel_details = $tdispatch_model->getuser_details($like_q,'3');

		foreach($getmodel_details as $details) {
			$name[] = $details['phone'];
		}
		echo json_encode($name);exit;
	}
	
	public function action_get_passengerDetails()
	{
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';
		$name =arr::get($_REQUEST,'field_name'); 

		$getmodel_details = $tdispatch_model->getpassenger_Detailinfo($_REQUEST);

		echo $getmodel_details[0]['id'].','.$getmodel_details[0]['name'].','.$getmodel_details[0]['email'].','.$getmodel_details[0]['phone'];exit;
	}

	public function action_get_passengerDetails_new()
	{
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';
		$name =arr::get($_REQUEST,'field_name'); 

		$getmodel_details = $tdispatch_model->getpassenger_Detailinfo_new($_REQUEST);

		echo $getmodel_details[0]['id'].','.$getmodel_details[0]['name'].','.$getmodel_details[0]['email'].','.$getmodel_details[0]['phone'];exit;
	}

	public function action_get_modelminfare()
	{
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';
		$model_id =arr::get($_REQUEST,'model_id'); 

		$getmodel_details = $tdispatch_model->get_modelminfare($model_id);

		echo $getmodel_details[0]['min_fare'];exit;
	}

	public function action_checkpassenger_email_phone()
	{
		$tdispatch_model = Model::factory('tdispatch');
		$post = $_REQUEST; 
		
		$email_response = $tdispatch_model->check_emailuserdetails($post);		
		$phone_response = $tdispatch_model->check_phoneuserdetails($post);
		
		$result = $email_response.'~'.$phone_response;
		echo $result;		
		exit;
	}


	public function action_checkphoneuserdetails()
	{
		echo $response;exit;	
	}


	public function action_managebooking()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/dashboard");
	   	}
	   	
		//Page Title
		$this->page_title =  __('manage_bookings');
		$this->selected_page_title = __('manage_bookings');
		
		$tdispatch_model = Model::factory('tdispatch');
		$dashboard = Model::factory('admin');

		$count_booking_list = $tdispatch_model->count_booking_list('','','','');
		$update_submit =arr::get($_REQUEST,'update'); 
		$dispatch_submit =arr::get($_REQUEST,'dispatch_button');

		if (($update_submit || $dispatch_submit)&& Validation::factory($_POST) ) 
		{
			$post_values = $_POST;



			$post = Arr::map('trim', $this->request->post());
			$validator = $tdispatch_model->validate_dispatchbooking(arr::extract($post,array('firstname','email','phone','current_location','pickup_lat','pickup_lng','drop_location','drop_lat','drop_lng','pickup_date','pickup_time',
'luggage','no_passengers','taxi_model','recurrent','lablename','frmdate','todate','total_fare')));
			
			if($validator->check())
			{

			   $random_key = text::random($type = 'alnum', $length = 10);	
			   $password = text::random($type = 'alnum', $length = 6);					
			   $req_result = $tdispatch_model->updatebooking($post,$random_key,$password);
		   
			   $send_mail = $req_result['send_mail'];	
			   $passenger_logid = $req_result['pass_logid'];

				/* Create Log */		
				$company_id = $_SESSION['company_id'];			
				$log_message = __('log_message_updated');
				$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
				$log_booking = __('log_booking_updated');
				$log_booking = str_replace("PASS_LOG_ID",$passenger_logid,$log_booking); 
				$log_status = $tdispatch_model->create_logs($passenger_logid,$company_id,$user_createdby,$log_message,$log_booking);
				/* Create Log */

				if($passenger_logid)
				{


					if($send_mail == 'S')
					{

					/** Mail to new User **/
					$mail="";
					/*			
					$link = URL_BASE.'passengers/activateaccount/?key='.$random_key;					
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_NAME=>ucfirst($_POST['firstname']),REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ACTLINK=>$link);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'passenger-register.html',$replace_variables);
					*/

					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$post['firstname'],REPLACE_MOBILE=>$post['phone'],REPLACE_PASSWORD=>$password,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'passenger-register.html',$replace_variables);

					$to = $_POST['email'];
					$from = $this->siteemail;
					$subject = __('activation_subject');	
					$redirect = "tdispatch/addbooking";	
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
					$message_details = $common_model->sms_message('1');
					$to = $_POST['phone'];
					$message = $message_details[0]['sms_description'];
					$message = str_replace("##SITE_NAME##",SITE_NAME,$message);

					//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");

					}
					/** Mail to new User **/
					}
	

					Message::success(__('booking_updated'));
					$this->request->redirect("tdispatch/managebooking");
				}
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}


		//pagination loads here
		//-------------------------
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
		'current_page'   => array('source' => 'query_string','key' => 'page'),
		  'items_per_page' => REC_PER_PAGE,
		'total_items'    => $count_booking_list,
		'view' => 'pagination/punbb',
		));
   
		$all_booking_list = $tdispatch_model->all_booking_list($offset, REC_PER_PAGE);
		$all_company_map_list = $dashboard->all_driver_map_list();
		//****pagination ends here***//
		
		$details ='';
		
		//send data to view file 
		$view = View::factory('admin/tdispatch/manage_booking')
				->bind('all_booking_list',$all_booking_list)
				->bind('pag_data',$pag_data)
				->bind('srch',$_REQUEST)
				->bind('show_popup',$_REQUEST)
				->bind('Offset',$offset)
				->bind('all_company_map_list',$all_company_map_list);


		$this->template->title= SITENAME." | ".__('manage_bookings');
		$this->template->page_title= __('manage_bookings'); 
		$this->template->content = $view;
	}

	public function action_dispatchbooking()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/dashboard");
	   	}
	   	
		
		$tdispatch_model = Model::factory('tdispatch');

		if($_REQUEST) 
		{
			$post_values = $_REQUEST;

				
			$post = Arr::map('trim', $this->request->post());
			
			$validator = $tdispatch_model->validate_dispatchbooking(arr::extract($post,array('firstname','email','phone','current_location','pickup_lat','pickup_lng','drop_location','drop_lat','drop_lng','pickup_date','pickup_time',
'luggage','no_passengers','taxi_model','recurrent','lablename','frmdate','todate','total_fare','notes')));

			if($validator->check())
			{

			   $random_key = text::random($type = 'alnum', $length = 10);	
			   $password = text::random($type = 'alnum', $length = 6);					
			   $req_result = $tdispatch_model->updatebooking($post,$random_key,$password);

			   $send_mail = $req_result['send_mail'];	
			   $passenger_logid = $req_result['pass_logid'];

				/* Create Log */		
				$company_id = $_SESSION['company_id'];			
				$log_message = __('log_message_updated');
				$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
				$log_booking = __('log_booking_updated');
				$log_booking = str_replace("PASS_LOG_ID",$passenger_logid,$log_booking); 
				$log_status = $tdispatch_model->create_logs($passenger_logid,$company_id,$user_createdby,$log_message,$log_booking);
				/* Create Log */

				if($passenger_logid)
				{


					if($send_mail == 'S')
					{

					/** Mail to new User **/
					$mail="";
					/*			
					$link = URL_BASE.'passengers/activateaccount/?key='.$random_key;					
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_NAME=>ucfirst($_POST['firstname']),REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ACTLINK=>$link);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'passenger-register.html',$replace_variables);
					*/

					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$post['firstname'],REPLACE_MOBILE=>$post['phone'],REPLACE_PASSWORD=>$password,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'passenger-register.html',$replace_variables);

					$to = $_POST['email'];
					$from = $this->siteemail;
					$subject = __('activation_subject');	
					$redirect = "tdispatch/addbooking";	
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
					$message_details = $common_model->sms_message('1');
					$to = $_POST['phone'];
					$message = $message_details[0]['sms_description'];
					$message = str_replace("##SITE_NAME##",SITE_NAME,$message);

					//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");

					}
					/** Mail to new User **/
					}
	
					
				}
					echo 'success';exit;
			     
			}

		}
			echo 'content';exit;

	}


	public function action_managebookingsearch()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/dashboard");
	   	}
	   	
		//Page Title
		$this->page_title =  __('manage_bookings');
		$this->selected_page_title = __('manage_bookings');
		
		$tdispatch_model = Model::factory('tdispatch');
		$page_no = arr::get($_REQUEST,'page'); 			
		if(isset($_REQUEST['filter_date']))
		{
			$post_values = $_REQUEST;

			$count_booking_list = $tdispatch_model->count_bookingsearch_list($post_values['search_txt'],$post_values['filter_date'],$post_values['to_date'],$post_values['sort_type'],$post_values['sort_by'],$post_values['booking_filter']);
		}
		else
		{
			$count_booking_list = $tdispatch_model->count_bookingsearch_list('','','','','','');
		}	
		
		//pagination loads here
		//-------------------------

		if($page_no)
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
		'current_page'   => array('source' => 'query_string','key' => 'page'),
		  'items_per_page' => REC_PER_PAGE,
		'total_items'    => $count_booking_list,
		'view' => 'pagination/punajax',			  
		));

   
		if(isset($_REQUEST['filter_date']))
		{
			$post_values = $_REQUEST;

			$all_booking_list = $tdispatch_model->all_bookingsearch_list($post_values['search_txt'],$post_values['filter_date'],$post_values['to_date'],$post_values['sort_type'],$post_values['sort_by'],$post_values['booking_filter'],$offset, REC_PER_PAGE);
		}
		else
		{
			$all_booking_list = $tdispatch_model->all_bookingsearch_list('','','','','','',$offset, REC_PER_PAGE);
		}

		$count = count($all_booking_list);

		//****pagination ends here***//
		
		$page_title = __('manage_bookings');

		$output ='';

			$output .='<div class="widget">
			<div class="title"><h6>'.$page_title.'<span style="color:red;"> ('.__('byclicking_booking').') </span></h6>
			<div style="width:auto; float:right; margin:0;">
			<div class="button greyishB"></div>                       
			</div>
			</div>';
			if($count > 0){ 
			$output .='<div class= "overflow-block">';
			}
			$output .='<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive" id="changetr">';
			if($count > 0){ 
			$output .='<thead>
			<tr>
			<td align="left" width="10%">'.__('companyname').'</td>
			<td align="left" width="10%">'.__('time').'</td>
			<td align="left" width="30%" >'.__('pickuploc_droploc').'</td>
			<td align="left" width="10%" >'.__('fare').'</td>
			<td align="left" width="10%" >'.__('payment').'</td>
			<td align="left" width="10%" >'.__('passenger&phone').'</td>
			<td align="left" width="15%" >'.__('driver').'</td>
			<td align="left" width="10%" >'.__('status').'</td>
			</tr>
			</thead>
			<tbody>	';

			$sno=$offset; /* For Serial No */

			foreach($all_booking_list as $listings) {

			//S.No Increment
			//==============
			$sno++;

			//For Odd / Even Rows
			//===================
			$trcolor=($sno%2==0) ? 'oddtr' : 'eventr';  
			$row_solor = "";
			if($listings['travel_status'] == 0) { $status = __('assign'); $row_solor = 'style="color:red;"';  } elseif($listings['travel_status'] == 1) { $status = __('completed'); $row_solor = 'style="color:green;"'; }  elseif($listings['travel_status'] == 2) { $status = __('inprogress'); $row_solor = 'style="color:orange;"'; }  if($listings['travel_status'] == 3) { $status = __('start_to_pickup'); $row_solor = 'style="color:#909090;"'; } elseif($listings['travel_status'] == 4) { $status = __('cancel_by_passenger'); $row_solor = 'style="color:#303030;"';} elseif($listings['travel_status'] == 5) { $status = __('waiting_payment'); $row_solor = 'style="color:#A52A2A;"';} elseif($listings['travel_status'] == 6) { $status = __('reassign'); $row_solor = 'style="color:blue;"';} elseif($listings['travel_status'] == 7) { $status = __('dispatched'); $row_solor = 'style="color:#B8860B;"'; }  elseif($listings['travel_status'] == 8) { $status = __('cancelled'); $row_solor = 'style="color:#9932CC;"';} elseif($listings['travel_status'] == 9) { $status = __('confirmed'); $row_solor = 'style="color:#2F4F4F;"';} elseif($listings['travel_status'] == 10) { $status = __('reassign'); $row_solor = 'style="color:#FFD700"';} 									

			$output .='<tr class="'.$trcolor.'" id="addtr_'.$listings['pass_logid'].'"><td>'.$listings['company_name'].'</td><td>'.$listings['pickup_time'].'</td><td><span '.$row_solor.'>'.$listings['current_location'].'</span></br><span>'.$listings['drop_location'].'</span></br><span style="color:red;">'.__('booking_id').' : '.$listings['pass_logid'].'</span></td><td><span>'.$listings['approx_distance'].' '.UNIT_NAME.'</span></br><span>'.$listings['approx_duration'].'</span></td><td>';
			if($listings['fixedprice'] != 0)
			{ 
			 $output .='<span>'.findcompany_currency($_SESSION['company_id']).' '.$listings['fixedprice'].'</span>';
			 }
			else
			{ 
			 $output .= '<span>'.findcompany_currency($_SESSION['company_id']).' '.$listings['approx_fare'].'</span>';
			} 
		    $output .='</td><td><span>'.$listings['passenger_name'].'</span></br><span>'.$listings['passenger_phone'].'</span></td>';		

		   $output .='<td>';
				if($listings['driver_name'] !='') { 
					$output .='<span>'.$listings['driver_name'].' ('.$listings['driver_phone'].')</span></br>';
				}

				if($listings['travel_status'] == 6) { $output .='<span style="color:red;">'.__('missed').'</span>'; }
				elseif($listings['travel_status'] == 7) { $output .='<span style="color:#B8860B;">'.__('dispatched').'</span>'; }
				elseif($listings['travel_status'] == 8) { $output .='<span style="color:#9932CC;">'.__('cancelled').'</span>';  } 
				elseif($listings['travel_status'] == 9) { $output .='<span style="color:#2F4F4F;">'.__('confirmed').'</span>'; }
				elseif($listings['travel_status'] == 10) { $output .='<span style="color:#FFD700;">'.__('driver_cancelled').'</span>'; } 
		 $output .='</td>';

			$output .= '<td '.$row_solor.'>'.$status.'</td></tr>';

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
			?>
			<script>
			$('.oddtr').bind('click', function(){
			var isrdata = this.id;
			var findid = isrdata.split('_').pop();

			$('table#changetr tr#edit').remove();
			$("<tr id='edit'><td colspan=10 id='append_result'></td></tr>").insertAfter( $(this).closest('tr') );
			$('#append_result').html('<img src="<?php echo IMGPATH; ?>loader.gif" align="middle"> loading...');

				var dataS = "passenger_logid="+trim(findid);		
				$.ajax
				({ 			
					type: "GET",
					url: "<?php echo URL_BASE;?>tdispatch/edit_booking", 
					data: dataS, 
					cache: false, 
					dataType: 'html',
					success: function(response) 
					{ 	
						$('#append_result').html('');
						$('#append_result').html(response);		
					} 
					 
				});	

			});

			$('.eventr').bind('click', function(){
			var isrdata = this.id;
			var findid = isrdata.split('_').pop();

				$('table#changetr tr#edit').remove();
				$("<tr id='edit'><td colspan=10 id='append_result'></td></tr>").insertAfter( $(this).closest('tr') );

				$('#append_result').html('<img src="<?php echo IMGPATH; ?>loader.gif" align="middle"> loading...');

				var dataS = "passenger_logid="+trim(findid);		
				$.ajax
				({ 			
					type: "GET",
					url: "<?php echo URL_BASE;?>tdispatch/edit_booking", 
					data: dataS, 
					cache: false, 
					dataType: 'html',
					success: function(response) 
					{ 	
						$('#append_result').html('');
						$('#append_result').html(response);		
					} 
					 
				});	
	


			});
			</script>
				
			<?php
			echo $output;exit;
		

	}

/*
	public function action_editbooking()
	{

		$pass_log_id = $_REQUEST['passenger_logid'];
		$add_model = Model::factory('add');
		$tdispatch_model = Model::factory('tdispatch');
		$common_model = Model::factory('commonmodel');	

		$company_id = $_SESSION['company_id'];
		$company_tax = $common_model->company_tax($company_id);		 

		$model_details = $tdispatch_model->model_details();
		$additional_fields = $add_model->taxi_additionalfields();
		$field_count = count($additional_fields);
		$edit_bookingdetails = $tdispatch_model->edit_bookingdetails($pass_log_id);
		$log_details = $tdispatch_model->booking_logdetails($pass_log_id);
		$getgroup_details = $tdispatch_model->getgroup_details_bycompany($company_id);
		$get_suggestedlocation = $tdispatch_model->get_suggestedlocation();
		$get_suggestedjourney = $tdispatch_model->get_suggestedjourney();


		$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);

		//$current_time = $date->format('H:i');
		//$current_date = $date->format('Y-m-d');
	
		$pickup_datetime = explode (' ',$edit_bookingdetails[0]['pickup_time']);		
		$pickup_date = $pickup_datetime[0];
		$pickup_time = $pickup_datetime[1];
		
		$specific_additional_fields = unserialize($edit_bookingdetails[0]['additional_fields']);

		function get_val($key_to_check, $array){
		    if(isset($array[$key_to_check])) {
			return $array[$key_to_check];
		    }
		}

		?>

		<style>
		.alert-message {
		    font-family: sans-serif;
		    margin: 20px;
		    font-weight: bold;
		    
		    -moz-border-radius: 3px;
		    -webkit-border-radius: 3px;
		    border-radius: 3px;
		    
		    padding: 1px;
		    position: relative;
		    font-size: 12px;
		    width:335px;
		}

		.alert-message .close {
		    color: #745050;
		    text-decoration: none;
		    float: right;
		    margin: 7px 7px 0 0;
		    font-weight: bold;
		    font-size: 16px;
		}

		.alert-message p {
		    display: block;
		    margin: 0;
		    padding: 8px 20px 7px 10px;
		    
		    -moz-border-radius: 3px;
		    -webkit-border-radius: 3px;
		    border-radius: 3px;
		}

		.errorp {
		    background-color: #d29191;
		    
		    -webkit-box-shadow: 0px 0px 6px rgba(244,187,187,0.7);
		    -moz-box-shadow: 0px 0px 6px rgba(244,187,187,0.7);
		    box-shadow: 0px 0px 6px rgba(244,187,187,0.7);
		}

		.errorp p {
		    background-color: #f4baba;
		    
		    background-image: -webkit-gradient(linear, left top, left bottom, from(#f4baba), to(#db7f7f));
		    background-image: -webkit-linear-gradient(top, #f4baba, #db7f7f);
		    background-image: -moz-linear-gradient(top, #f4baba, #db7f7f);
		    background-image: -ms-linear-gradient(top, #f4baba, #db7f7f);
		    background-image: -o-linear-gradient(top, #f4baba, #db7f7f);
		    background-image: linear-gradient(top, #f4baba, #db7f7f);
		    filter:progid:DXImageTransform.Microsoft.gradient(startColorStr='#f4baba', EndColorStr='#db7f7f');
		    
		    -webkit-box-shadow: inset 0px 1px 0px #f7d0d0;
		    -moz-box-shadow: inset 0px 1px 0px #f7d0d0;
		    box-shadow: inset 0px 1px 0px #f7d0d0;
		    color: #745050;
		    text-shadow: 1px 1px 0px #eaadad;
		    font-size:13px;
		}
		</style>


		<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>

		<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/site.js"></script>
		<!-- time picker start-->
		<link rel="stylesheet" href="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
		<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-1.5.1.min.js"></script>
		<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
		<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript" src="<?php echo URL_BASE; ?>public/js/jquery.autocomplete.js"></script>
		<!-- time picker start-->
		<link rel="stylesheet" type="text/css" href="<?php echo URL_BASE;?>public/css/jquery.autocomplete.css" />
		<link href="<?php echo URL_BASE;?>public/css/starrating.css" rel="stylesheet" type="text/css" media="screen" />
		<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>
		<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/jquery.lightbox_me.js"></script>
		<link rel="stylesheet" href="<?php echo URL_BASE;?>public/css/dispatch_popup.css" />

		<?php


	$output = '';

	$output .='<div class="errorp alert-message"><a  class="close"></a><p>'.__('note').' : '.__('create_user_not_exist').'</p></div>';

	$output .='<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle">   
	<div class="first_left">	
		<ul>	
		<li class=""><a href="javascript:;" id="edit_booking">Edit</a></li>
		<li class=""><a href="javascript:;" id="log_booking">Log</a></li>
		<li class=""><a href="javascript:;" id="feedback_booking">Feedback</a></li>
		<li class=""><a href="javascript:;" id="share_booking">share</a></li>
		</ul>
	</div> 
         <form name="editbooking_form" class="form" id="editbooking_form" action="" method="post" enctype="multipart/form-data" onSubmit="check_passengerexit()">
<div id="edit_details">
	<div class="first_fieldset">	
	<fieldset class="booking-form-fieldset">
	<legend>'.strtoupper(__('passengers_information')).'</legend>
	   <div class="new_booking_field ">
              <input type="text" title="'.__('enterfirstname_msg').'" class="required" name="firstname" id="firstname" value="'.$edit_bookingdetails[0]['passenger_name'].' - ('.$edit_bookingdetails[0]['passenger_phone'].')"  minlength="4" placeholder="'.__('firstname').'"  autocomplete="off" />
		<input name="passenger_id" id="passenger_id" type="hidden" value="'.$edit_bookingdetails[0]['passengers_id'].'" >';

              if(isset($errors) && array_key_exists('firstname',$errors)){ $output .="<span class='error'>".ucfirst($errors['firstname'])."</span>"; }
		  $output .='<span id="unameavilable" class="error"> </span></div>
		   <div class="new_booking_field mt10">
              <input type="text" title="'.__('enteremailaddress').'" class="required email" name="email" id="email" value="'.$edit_bookingdetails[0]['passenger_email'].'"  maxlength="50" placeholder="'.__('email').'" autocomplete="off"  />';

              if(isset($errors) && array_key_exists('email',$errors)){ $output .="<span class='error'>".ucfirst($errors['email'])."</span>"; }
		$output .='<span id="uemailavilable" class="error"> </span></div>';

		$output .='<div class="new_booking_field mt10"><input type="text" title="'.__('entermobileno').'" name="phone" id="phone" class="required" value="'.$edit_bookingdetails[0]['passenger_phone'].'" minlength="6" maxlength="20" placeholder="'.__('mobile').'" autocomplete="off" />';
              if(isset($errors) && array_key_exists('phone',$errors)){ $output .="<span class='error'>".ucfirst($errors['phone'])."</span>";}

		$output .='<span id="uphoneavilable" class="error"> </span></div>';


		$output .='<div id="show_group" style="display:none"><div class="formRight mt10">
		<div class="selector" id="usergroup_list"></div></div>
		<label for="group_id" generated="true" class="errorvalid" style="display:none">'.__('select_group').'</label>
		</div><div class="new_booking_field mt10 mb50"></div></fieldset>';

		$output .='<fieldset class="booking-form-fieldset">
		<legend>'.strtoupper(__('booking')).'</legend><div class="new_booking_field">';

		$output .='<input type="text" title="'.__('enter_currentlocation').'" class="required " name="current_location" id="current_location" value="'.$edit_bookingdetails[0]['current_location'].'"  autocomplete="off"  placeholder="'.__('enter_currentlocation').'" --onchange="GeocodeFromAddress()"/>';
		if(isset($errors) && array_key_exists('current_location',$errors)){ $output .="<span class='error'>".ucfirst($errors['current_location'])."</span>";}

		$output .='<input type="hidden" name="pickup_lat" id="pickup_lat" value="'.$edit_bookingdetails[0]['pickup_latitude'].'"><input type="hidden" name="pickup_lng" id="pickup_lng" value="'.$edit_bookingdetails[0]['pickup_longitude'].'"></div>';

		 $output .='<div class="new_booking_field mt10"><input type="text" title="'.__('enter_droplocation').'" name="drop_location" id="drop_location" value="'.$edit_bookingdetails[0]['drop_location'].'"  autocomplete="off" placeholder="'.__('enter_droplocation').'" --onchange="GeocodeFromAddress()"/>';
              if(isset($errors) && array_key_exists('drop_location',$errors)){ $output .="<span class='error'>".ucfirst($errors['drop_location'])."</span>";} 
		$output .='<input type="hidden" name="drop_lat" id="drop_lat" value="'.$edit_bookingdetails[0]['drop_latitude'].'"><input type="hidden" name="drop_lng" id="drop_lng" value="'.$edit_bookingdetails[0]['drop_longitude'].'">
		   </div>';
		 $output .='<div class="new_booking_field mt10">
		      <input type="text" title="'.__('note_driver').'" name="notes" id="notes" value="'.$edit_bookingdetails[0]['notes_driver'].'"  autocomplete="off"  placeholder="'.__('note_driver').'" />		     	
		   </div>';
  	      $output .='<div class="new_booking_field mt10"><span class="first"><input type="text" title="'.__('pickup_date').'" class="" name="pickup_date" id="pickup_date" value="'.$pickup_date.'"  autocomplete="off" placeholder="'.__('today').'" readonly />';
		if(isset($errors) && array_key_exists('pickup_date',$errors)){ $output .="<span class='error'>".ucfirst($errors['pickup_date'])."</span>";}
			$output .='</span><span class="second">
		      <input type="text" title="'.__('pickup_time').'" class="" name="pickup_time" id="pickup_time" value="'.$pickup_time.'"  autocomplete="off" placeholder="'.__('now').'" readonly />';
		if(isset($errors) && array_key_exists('pickup_time',$errors)){ $output .="<span class='error'>".ucfirst($errors['pickup_time'])."</span>";}
			$output .='</span></div><div class="new_booking_field mt10"><span class="first mt10 ">	
		      <input type="text" title="'.__('no_luggage').'" class="required onlynumbers" name="luggage" id="luggage" value="'.$edit_bookingdetails[0]['luggage'].'"  autocomplete="off" placeholder="'.__('luggage').'" />';
		 if(isset($errors) && array_key_exists('luggage',$errors)){ $output .="<span class='error'>".ucfirst($errors['luggage'])."</span>";}
			$output .='</span><span class="second mt10"><input type="text" title="'.__('no_passengers').'" class="required number" name="no_passengers" id="no_passengers" value="'.$edit_bookingdetails[0]['no_passengers'].'"  autocomplete="off" placeholder="'.__('passengers').'" />';
		  if(isset($errors) && array_key_exists('no_passengers',$errors)){ $output .="<span class='error'>".ucfirst($errors['no_passengers'])."</span>"; }
			$output .='</span></div><div class="new_booking_field mb20 clr"></div></fieldset></div>';


	$output .='<div class="second_fieldset third_fieldset fl ml20"><div id="show_suggestion"><div id="show_suggested">'.__('show_suggested').'</div><div id="locations_journeys" class="locations"><h2>'.__('favorite_journey').'</h2><ul id="load_journey">';
		if(count($get_suggestedjourney) > 0)
		{
			foreach($get_suggestedjourney as $details)
			{


				$output .="<li title=\"".__('from').' '.$details['from_location'].' '.__('to').' '.$details['to_location']."\"><a href=\"javascript:;\" onclick=\"change_fromtolocation('".urlencode($details['from_location'])."','".urlencode($details['to_location'])."');\"><span class=\"journey-from\">".__('from').":</span><span class='journey-name'>".$details['from_location']."</span><span class='journey-to'>".__('to').":</span><span class='journey-name'>".$details['to_location']."</span></a></li>";

			}
		}
		else
		{
				$output .='<li>'.__('no_data').'</li>';
		}

		$output .='</ul></div><div id="locations_locations" class="locations mt20 mb20"><h2>'.__('favorite_locations').'</h2><ul id="load_location">';
		if(count($get_suggestedlocation) > 0)
		{
			foreach($get_suggestedlocation as $details)
			{
				$output .='<li class="" id="selectclass_'.$details['fid'].'"><a href="javascript:void(0)" onclick="popup_location('.$details['fid'].');" id="fid_'.$details['fid'].'">'.$details['location'].'</a></li>';
			}
		}
		else
		{
			$output .='<li>'.__('no_data').'</li>';
		}
		$output .='</ul></div></div>';	



			$output .='<fieldset class="booking-form-fieldset"><legend>'.strtoupper(__('vehicle')).'</legend><div class="formRight"><div class="selector" id="uniform-user_type">';

		      $field_type = $edit_bookingdetails[0]['taxi_modelid'];
	
		      $output .='<select name="taxi_model" id="taxi_model" class="required" title="'.__('select_the_taximodel').'" OnChange="change_minfare(this.value);"><option value="">'.__('select_label').'</option>';
			foreach($model_details as $list) { 
		      $output .='<option value="'.$list['model_id'].'"' ;
			 if($field_type == $list['model_id']) { 
		      $output .='selected=selected'; } 
		      $output .= ' >  '.ucfirst($list['model_name']).'</option>';
		       } 
		      $output .='</select></div></div><label for="taxi_model" generated="true" class="errorvalid" style="display:none;width:250px;">'.__('select_the_taximodel').'</label><div class="new_booking_field mt20 mb50"></div></fieldset>';

            if($field_count > 0)
            {
				
			$output .='<fieldset class="booking-form-fieldset"><legend>'.strtoupper(__('extra')).'</legend>';

		    for($i=0; $i<$field_count; $i++)
		    { 
		    	$field_name = $additional_fields[$i]['field_name']; 
			$output.='<tr><td valign="top" width="20%"><div class="new_input_field"><label>'.$additional_fields[$i]['field_labelname'].'</label></div></td><td><div class="new_input_field">';
		
			if($additional_fields[$i]['field_type'] == 'Textbox')
			{  
				$field_val = get_val($field_name,$specific_additional_fields);

	                	$output .='<input type="text" title="'.__('enter_the').$additional_fields[$i]['field_labelname'].'" class="" id="'.$field_name.'" name="addition_fields['.$field_name.']" value="'.$field_val.'" maxlength="20" />';
 			}
	                else if($additional_fields[$i]['field_type'] == 'Checkbox')
	                { 
		                $field_val = get_val($field_name,$specific_additional_fields);
	                        $field_chkvalue = ''; 
	            	 	if(isset($postvalue) && array_key_exists($field_name,$postvalue)){ $field_chkvalue =  $postvalue[$field_name]; }
	            	 	
	                 	$field_value = explode(',',$additional_fields[$i]['field_value']);
	                 	
	                 	foreach($field_value as $key => $value)
	                 	{ 
	                $output .='<input type="checkbox" title="'.__('enter_the').'"  name="addition_fields['.$field_name.']" value="'.$value.'"';
			if(trim($field_val) == trim($value)) { $output .= 'checked'; } $output .=' />'.$value;                	
	                 	
	                 	}
			}
		        else if($additional_fields[$i]['field_type'] == 'Radio')
	                {  
				$field_val = get_val($field_name,$specific_additional_fields);
	            	 
	                 	$field_value = explode(',',$additional_fields[$i]['field_value']);
	                 	
	                 	foreach($field_value as $key => $value)
	                 	{ 
	                $output .='<input type="radio" title="'.__('select_the').$field_name.'" class="" name="addition_fields['.$field_name.']" id="'.$field_name.'" value="'.$value.'"';
				if(trim($field_val) == trim($value)) { $output .=' checked'; } $output .=' />'.$value;
	                 	
	                 	}
	               }
	                else if($additional_fields[$i]['field_type'] == 'Select')
	                { 
			 $field_val = get_val($field_name,$specific_additional_fields);
	            	 $field_selvalue =''; 

	            	 if(isset($postvalue) && array_key_exists($field_name,$postvalue)){ $field_selvalue =  $postvalue[$field_name]; }

	                 	$field_value = explode(',',$additional_fields[$i]['field_value']);

				$output .='<div class="formRight"><div class="selector" id="uniform-user_type"><span>'.__('select_label').'</span><select name="addition_fields['.$field_name.']" id="'.$field_name.'" class="" title="'.__('select_the').$field_name.'"><option value="">--Select--</option>';

	                 	foreach($field_value as $key => $value)
	                 	{ 
				$output .='<option value="'.$value.'"';

				if(trim($field_val) == trim($value)) { $output  .='selected=selected'; } $output .=' >'.ucfirst($value).'</option>';
	                 	}
	                 	$output .='</select></div></div>';
			}

	                $output .='<label for="'.$field_name.'" generated="true" style="display:none" class="errorvalid">'.__('select_the').$field_name.'</label>';              	 
		       if(isset($errors) && array_key_exists($field_name,$errors)){ $output .="<span class='error'>".ucfirst($errors[$field_name])."</span>"; }

	                $output .='</div></td></tr>';
		      }
          

		  $output .='<div class="new_booking_field mt20 mb50"></div></fieldset>';
	  }
		  $output .= '</div>';

	$output .='<div class="second_fieldset  fl ml50" id="payment_sec" --style="display:none;"><fieldset class="booking-form-fieldset"><legend>'.strtoupper(__('payment')).'</legend><div class="new_booking_field ">';
	
	$query2 = "SELECT * FROM payment_modules where pay_mod_active='1'  and pay_mod_name!='uncard' order by pay_mod_name asc";

				$result = Db::query(Database::SELECT, $query2)
						->execute()
						->as_array();

				foreach($result as $resultset) { 
					$output .='<span class="second1"><input type="radio" class="required " name="payment_type" value="'.$resultset['pay_mod_id'].'"';
					if($edit_bookingdetails[0]['faretype'] == $resultset['pay_mod_id']) { $output .=' checked'; } else { $output .=''; }$output .=' />'.$resultset['pay_mod_name'].'</span>';
				}
	$output .='<span id="payment_error" class="error" style="display:none;"></span></div>';

	$output .='<div class="new_booking_field mt10"><ul id="acc"><li><label>'.__('account').' :</label><span></span></li><li><label>'.__('journey').' :</label><span id="find_duration">'.$edit_bookingdetails[0]['approx_duration'].'</span></li><li><label>'.__('distance').' :</label><span id="find_km">'.$edit_bookingdetails[0]['approx_distance'].' km</span></li>';

	$output .='<li><label>'.__('tax').' :</label><span>%</span><span id="vat_tax">'.$company_tax.'</span></li>';
	$output .='<li><label>'.__('fare').' :</label><span id="min_fare" class="">'.$edit_bookingdetails[0]['approx_fare'].'</span><span>'.findcompany_currency($_SESSION['company_id']).'</span></li></ul></div>';	

$output .='<div class="new_booking_field "><label>'.__('fixedprice').' :</label><span style="width:50%;float:right !important;"><input type="text" title="'.__('enter_override_price').'" class="onlynumbers" name="fixedprice" id="fixedprice" value="'.$edit_bookingdetails[0]['fixedprice'].'"  maxlength="6" placeholder="'.__('override_price').'" autocomplete="off" /></span>';
	if(isset($errors) && array_key_exists('fixedprice',$errors)){ $output .="<span class='error'>".ucfirst($errors['fixedprice'])."</span>"; }
	$output .='</div><div class="new_booking_field mt10 clr"><ul id="acc"><li><label></label><span id="total_price">'.$edit_bookingdetails[0]['approx_fare'].'</span><span>'.__('total').' : '.findcompany_currency($_SESSION['company_id']).'</span></li></ul></div>';	


	$output .='<div class="new_booking_field mt10"><ul id="acc" style="display:none;"><li><label>'.__('description').' :</label><span id="desc">'.__('rate_kilometer').' '.$edit_bookingdetails[0]['approx_distance'].'</span></li>
	<li><label>'.__('value').' :</label><span>'.findcompany_currency($_SESSION['company_id']).'</span><span id="min_value">'.$edit_bookingdetails[0]['min_fare'].'</span></li><li><label>'.__('subtotal').' :</label><span>'.findcompany_currency($_SESSION['company_id']).'</span><span id="sub_total">'.$edit_bookingdetails[0]['approx_fare'].'</span></li></ul><input type="hidden" name="model_minfare" id="model_minfare" value="'.$edit_bookingdetails[0]['min_fare'].'" ><input type="hidden" name="model_id" id="model_id" value="'.$edit_bookingdetails[0]['taxi_modelid'].'" ><input type="hidden" name="distance_km" id="distance_km" value="'.$edit_bookingdetails[0]['approx_distance'].'" ><input type="hidden" name="total_fare" id="total_fare" value="'.$edit_bookingdetails[0]['approx_fare'].'" ><input type="hidden" name="total_duration" id="total_duration" value="'.$edit_bookingdetails[0]['approx_duration'].'" ><input type="hidden" name="city_id" id="city_id" value="'.$edit_bookingdetails[0]['search_city'].'" ><input type="hidden" name="cityname" id="cityname" value="" > <input type="hidden" name="company_tax" id="company_tax" value="'.$company_tax.'" ><input type="hidden" name="pass_logid" id="pass_logid" value="'.$edit_bookingdetails[0]['pass_logid'].'" >';

	$div = 0;
	if($edit_bookingdetails[0]['travel_status'] !=1)
	{

	$output .='<div class="mt10 button greenB  mb20" ><input type="submit" name="update" value="'.__('update').'" ></div>';

	if($edit_bookingdetails[0]['travel_status'] !=9 && $edit_bookingdetails[0]['travel_status'] !=8)
	{
		$output .='<div class="mt10 button greenB fr" ><input type="submit" name="dispatch_button" id="dispatch_button" value="'.__('dispatch').'" ></div>';

	}

	if($edit_bookingdetails[0]['travel_status'] ==9 || $edit_bookingdetails[0]['travel_status'] ==2 || $edit_bookingdetails[0]['travel_status'] ==5 )
	{
	$div = 1;
	$output .='</div><div class="mt10 button blackB  mb20" ><input type="submit" name="complete_button" id="complete_button" value="'.__('complete').'" ></div>';
	}

	if($edit_bookingdetails[0]['travel_status'] !=2 )
	{
		if($div == 1)
		{
			$output .='<div class="mt10 button greenB fr " style="margin-right: 25px;" ><input type="submit" name="cancel_button" id="cancel_button" value="'.__('cancel').'" ></div>';
		}	
		else
		{
			$output .='</div><div class="mt10 button greenB fr mb20" style="margin-right: 25px;" ><input type="submit" name="cancel_button" id="cancel_button" value="'.__('cancel').'" ></div>';
		}
	}

	}
	$output .='<div class="new_booking_field mt20 "></div></fieldset>';

	$output .='<fieldset class="booking-form-fieldset"><legend>'.strtoupper(__('map')).'</legend>';

	if(SHOW_MAP ==1 ) { 
		$output .='<div id="map" style="display:none;"></div>';//<div>Show Suggesstion Route</div>
	}
	else
	{ 
		$output .='<div id="map" ></div>';
	} 	


	$output .='<div id="directions"></div><div style="display:none;"><table><tr><td>Start altitude:</td><td id="start"></td></tr><tr><td>End altitude:</td><td id="end"></td></tr><tr><td>Maximum altitude:</td><td id="max"></td></tr><tr><td>Minimum altitude:</td><td id="min"></td></tr><tr><td>Distance:</td><td id="distance"></td></tr><tr><td>Total ascent:</td><td id="ascent"></td></tr><tr><td>Total descent:</td>	<td id="descent"></td></tr></table></div>';

	$output .='<div class="new_booking_field mb20" ></div></fieldset></div></div></form>';
	$output .='</div>';

	$output .='<div id="log_details" style="display:none;">';

		foreach($log_details as $log_data)
		{
			$output .= '<div style="margin-bottom:4px;">'.$log_data['log_createdate'].'</div>';
			$output .= '<div class="log_message">'.$log_data['log_booking'].'</div>';
		}

	$output .='</div>';	

	$output .='<div id="feedback_details" style="display:none;">';
	$output .='<form name="sharing_form" class="form" id="sharing_form" action="" method="post" enctype="multipart/form-data">
           <table border="0" cellpadding="5" cellspacing="0" width="100%" style="border:none;float:left;">
           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"><label>'.__('customer').'</label></td>        
		   <td style="border:none;">'.__('customer_not_feedback').'</td>
	   </tr>
           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"><label>'.__('controllers').'</label></td>        
		<td style="border:none;"><label>'.__('give_your_rating').'</label></td>
	   </tr>

           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"></td>        
		<td style="border:none;">
			<ul class="star-rating">
			<li class="current-rating" id="current-rating" style="width: '.(($edit_bookingdetails[0]['controller_rating'] * 26) - 8).'px; background: url('.URL_BASE.'public/images/alt_star.gif) left center!important; left: 0px!important; margin: 0px!important"></li>
			<span id="ratelinks"><li><a href="javascript:rateProduct(1,'.$edit_bookingdetails[0]['pass_logid'].')" title="'.__('star1').'" class="one-star">1</a></li>
			<li><a href="javascript:rateProduct(2,'.$edit_bookingdetails[0]['pass_logid'].')" title="'.__('star2').'" class="two-stars">2</a></li>
			<li><a href="javascript:rateProduct(3,'.$edit_bookingdetails[0]['pass_logid'].')" title="'.__('star3').'" class="three-stars">3</a></li>
			<li><a href="javascript:rateProduct(4,'.$edit_bookingdetails[0]['pass_logid'].')" title="'.__('star4').'" class="four-stars">4</a></li>
			<li><a href="javascript:rateProduct(5,'.$edit_bookingdetails[0]['pass_logid'].')" title="'.__('star5').'" class="five-stars">5</a></li></span>
			</ul>
			<p style="margin-top: 15px;"><span id="ratingtext"></span></p>
		</td>
	   </tr>

           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"></td>        
		<td style="border:none;"><label>'.__('write_your_comment').'</label></td>
	   </tr>

           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"></td>        
		<td style="border:none;">
			<div class="new_input_field">
			<textarea type="text" title="'.__('please_enter_comment').'" name="feedback_comment" id="feedback_comment" cols="5" rows="5" ></textarea>
			</div>
			<span class="error" id="feedback_commenterror"></span>
	</td>
	   </tr>

	   <tr style="border:none;"> <td style="border:none;"></td>
	   <td style="border:none;"><div class="button greenB">  <input type="button" id="rating_button" value="'.__('submit' ).'" name="submit_ratingbutton" title="'.__('submit' ).'" /></div>
                            </td>	
	   </tr>
	   <tr style="border:none;"> <td style="border:none;"></td>
	   <td style="border:none;"><div id="feedback_success"></div>
                            </td>	
	   </tr>		
	</table></form>';
	$output .='</div>';	

	$output .='<div id="share_details" style="display:none;">';
	$output .='<form name="sharing_form" class="form" id="sharing_form" action="" method="post" enctype="multipart/form-data">
           <table border="0" cellpadding="5" cellspacing="0" width="100%" style="border:none;float:left;">
           <tr style="border:none;">
           <td style="border:none;"><h4>'.__('manual_sharing').'</h4></td>        
	   <td style="border:none;"></td>
	   </tr>
           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"><label>'.__('email_label').'</label><span class="star">*</span></td>        
		   <td style="border:none;">
			<div class="new_input_field">
			<input type="text" title="'.__('please_provide_email').'" name="share_email" id="share_email" value="" placeholder="'.__('emaillabel').'"/>
			</div>
			<span class="error" id="share_emailerror"></span>
		   </td>
	   </tr>
           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"><label>'.__('note').'</label><span class="star">*</span></td>        
		<td style="border:none;">
			<div class="new_input_field">
			<textarea type="text" title="'.__('please_enter_note').'" name="share_note" id="share_note" cols="5" rows="5" ></textarea>
			</div>
			<span class="error" id="share_noteerror"></span>
		</td>
	   </tr>
	   <tr style="border:none;"> <td style="border:none;"></td>
	   <td style="border:none;"><div class="button greenB">  <input type="button" id="sharing_button" value="'.__('submit' ).'" name="submit_sharebooking" title="'.__('submit' ).'" /></div>
                            </td>	
	   </tr>
	   <tr style="border:none;"> <td style="border:none;"></td>
	   <td style="border:none;"><div id="shared_success"></div>
           </td>	
	   </tr>		
	</table></form>';
	$output .='</div>';	


	$output .='</div><div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt"></div></div>';

	$output .='</div></div></div>';	

	 if(SHOW_MAP == 1 ) { 

		$output .='<div id="dispatch_popup" style="display:none;width:25% !important;"><div class="header_part"><span>'.__('choose_driver_from_list').'</span></div><div class="login_total"><div class="login_lft"><div class="login_form"><ul><li><div class="controls"><div class="new_input_field"><span class="add-on"><i class=""></i></span><input type="text" name="search_driver" id="search_driver" value="" onKeyUp="driver_details()"></div><input type="hidden" name="passenger_log_id" id="passenger_log_id" value="'.$edit_bookingdetails[0]['pass_logid'].'"></div></li><div id="show_process"><div id="driver_details"></div></ul></div></div><div class="login_rgt"><div id="dispatch_css" ></div></div></div><a id="close_x" class="close sprited" href="'.URL_BASE.'tdispatch/managebooking"></a></div>';

	}
	else
	{

		$output .='<div id="dispatch_popup" style="display:none;"><div class="header_part"><span>'.__('choose_driver_from_list').'</span></div><div class="login_total"><div class="login_lft"><div class="login_form"><ul><li><div class="controls"><div class="new_input_field"><span class="add-on"><i class=""></i></span><input type="text" name="search_driver" id="search_driver" value="" onKeyUp="driver_details()"></div><input type="hidden" name="passenger_log_id" id="passenger_log_id" value="'.$edit_bookingdetails[0]['pass_logid'].'"></div></li><div id="show_process"><div id="driver_details"></div></ul></div></div><div class="login_rgt"><div id="dispatch_css" ><div id="dispatch_map"></div></div></div></div><a id="close_x" class="close sprited" href="'.URL_BASE.'tdispatch/managebooking"></a></div>';

	}

	$total_fare = ($edit_bookingdetails[0]['fixedprice']!='')?$edit_bookingdetails[0]['fixedprice']:$edit_bookingdetails[0]['approx_fare'];
	$output .='<div id="complete_popup" style="display:none;"><div class="header_part"><span>'.__('complete_trip_by_opearotor').'</span></div><div class="login_total"><div class="complete_lft"><div class="complete_form"><ul><li><div class="controls"><div class="mt10 mb10"><span class="add-on"><i class="">'.__("distance_km").'</i></span><input type="text" name="complete_distance" id="complete_distance" value="'.$edit_bookingdetails[0]['approx_distance'].'" ></div><div class="mt10 mb10"><span class="add-on"><i class="">'.__('waiting_time').'</i></span><input type="text" name="complete_waitingtime" id="complete_waitingtime" value="0"></div><div class="mt10 mb10"><span class="add-on"><i class="">'.__('waiting_time_cost').'</i></span><input type="text" name="complete_waitingcost" id="complete_waitingcost" value="0"></div><div class="mt10 mb10"><span class="add-on"><i class="">'.__('night_charge').'</i></span><input type="text" name="complete_nightcharge" id="complete_nightcharge" value="0"></div><div class="mt10 mb10"><span class="add-on"><i class="">'.__('total_amount').'</i></span><input type="text" name="complete_total" id="complete_total" value="'.$total_fare.'"></div><div class="mt10 mb10"><span class="add-on"><i class="">'.__('any_remarks').'</i></span><textarea cols="4" rows="7" name="complete_remark" id="complete_remark" ></textarea></div><div class="mt10 mb10 fr" style="width:260px !important;"><div class="button greenB"><input type="button" id="complete_payment" value="'.__('payment' ).'" name="complete_payment" title="'.__('payment' ).'" /></div><div class="button dredB ml20"><input type="button" id="complete_update" value="'.__('change_status' ).'" name="complete_update" title="'.__('change_status' ).'" /></div></div><div id="complete_loading"></div>
</li></ul></div></div></div><a id="close_x" class="close sprited" href="'.URL_BASE.'tdispatch/managebooking"></a></div>';

?>
<script type="text/javascript">
$(document).ready(function(){

toggle(31);

GeocodeFromAddress();

$("#firstname").autocomplete("<?php echo URL_BASE; ?>tdispatch/firstname_load", {
});


$("#email").autocomplete("<?php echo URL_BASE; ?>tdispatch/email_load", {
});

$("#phone").autocomplete("<?php echo URL_BASE; ?>tdispatch/phone_load", {
});

var date = new Date();
var currentMonth = date.getMonth(); // current month
var currentDate = date.getDate(); // current date
var currentYear = date.getFullYear(); //this year
$("#pickup_date").datepicker( {
showTimepicker:true,
showSecond: true,
dateFormat: 'yy-mm-dd',
stepHour: 1,
stepMinute: 1,
minDate: new Date(currentYear, currentMonth, currentDate),
stepSecond: 1
} );

$("#pickup_time").timepicker( {
showTimepicker:true,
showSecond: false,
timeFormat: 'hh:mm:ss',
stepHour: 1,
stepMinute: 1,
minDateTime : new Date(),
stepSecond: 1
} );


$("#frmdate").datepicker( {
showTimepicker:true,
showSecond: true,
dateFormat: 'yy-mm-dd',
stepHour: 1,
stepMinute: 1,
minDateTime : new Date(),
stepSecond: 1
} );

$("#todate").datepicker( {
showTimepicker:true,
showSecond: true,
dateFormat: 'yy-mm-dd',
stepHour: 1,
stepMinute: 1,
minDateTime : new Date(),
stepSecond: 1
} );

$("#firstname").focus();	

toggle(31);

$("#editbooking_form").validate();

$('#fixedprice').blur(function(){
var price = $(this).val();

if(price == '')
{
	$('#min_fare').removeClass('strike');
	var tot_price = $('#total_fare').val();	
	$('#total_price').html(tot_price);
	
}
else
{	
	$('#total_price').html(price);
	$('#min_fare').addClass('strike');
}

});

$('#firstname').change(function(){
	$('#passenger_id').val('');
	$('#email').removeAttr('readonly');
	$('#phone').removeAttr('readonly');
	$('#show_group').hide();
	$('#usergroup_list').html('');
	//$('#load_location').html('');
	//$('#load_journey').html('');
	//$('#show_suggestion').hide();
});

$('#email').change(function(){
	$('#passenger_id').val('');
	$('#firstname').removeAttr('readonly');
	$('#phone').removeAttr('readonly');
	$('#show_group').hide();
	$('#usergroup_list').html('');
	//$('#load_location').html('');
	//$('#load_journey').html('');
	//$('#show_suggestion').hide();
});

$('#phone').change(function(){
	$('#passenger_id').val('');
	$('#email').removeAttr('readonly');
	$('#firstname').removeAttr('readonly');
	$('#show_group').hide();
	$('#usergroup_list').html('');
	//$('#load_location').html('');
	//$('#load_journey').html('');
	//$('#show_suggestion').hide();
});


});


get_editgroupdetails('<?php echo $edit_bookingdetails[0]["passengers_id"];?>','<?php echo $edit_bookingdetails[0]["accgroup_id"];?>');

function get_editgroupdetails(userid,groupid)
{

	var url= SrcPath+"tdispatch/geteditgrouplist/?userid="+userid+"&groupid="+groupid;

	$.post(url, {
	}, function(response){	
		if(trim(response) != '')
		{
			$('#show_group').show();			
			$('#usergroup_list').html('');
			$('#usergroup_list').html(response);
		}
		else
		{
			$('#show_group').hide();	
			$('#usergroup_list').html('');

		}	
	});

}


function change_fromtolocation(fromlocation,tolocation)
{
 $('#current_location').val(urldecode(fromlocation));
 $('#drop_location').val(urldecode(tolocation));
GeocodeFromAddress();
}

function urldecode(str) {
   return decodeURIComponent((str+'').replace(/\+/g, '%20'));
}


function popup_location(fid)
{
	$('#add_pick_drop').remove();
	$('#load_location li').removeClass('location_selected');
	var p = $("#fid_"+fid);
	var position = p.position();
	var pheight = (position.top)-20;

	$("<div id='add_pick_drop' style='top:"+pheight+"px; display: block;'><div id='close_location' style='color:black;float:right;padding:5px 5px 5px 5px ! important;'>X</div><div class='button dredB add_pic_but'><input type='button' value='Add To Pick Up' title='Add To Pick Up' id='add_to_pickup' onClick='add_booking_pickup("+fid+");'></div><div class='button greenB add_pic_but'><input type='button' value='Add To Drop off' title='Add To Drop off'  id='add_to_dropoff' onClick='add_booking_dropoff("+fid+");'></div></div>").insertAfter('#fid_'+fid);
	$('#selectclass_'+fid).addClass('location_selected');

$('#close_location').click(function(){
	$('#add_pick_drop').remove();
});


}

function add_booking_pickup(fid)
{

	 var pickuplocation = $('#fid_'+fid).text();
	 $('#current_location').val(urldecode(pickuplocation));
	$('#add_pick_drop').remove();
	GeocodeFromAddress();
	$('#add_pick_drop').remove();
}

function add_booking_dropoff(fid)
{
	 var droplocation = $('#fid_'+fid).text();
	 $('#drop_location').val(urldecode(droplocation));
	$('#add_pick_drop').remove();
	GeocodeFromAddress();
}


function check_passengerexit()
{

	var passenger_id = $('#passenger_id').val();
	var email = $('#email').val();
	var phone = $('#phone').val();
	var fixedprice = $('#fixedprice').val();
	var payment_valid = true;

	var url= SrcPath+"tdispatch/checkemailuserdetails/";
	var dataS = "email="+email+"&passenger_id="+passenger_id;		

	$.ajax
	({ 			
		type: "POST",
		url: url, 
		data: dataS, 
		async:false,
		cache: false, 
		dataType: 'html',
		success: function(response) 
		{ 	
			if(trim(response) != 'N')
			{		
				$('#uemailavilable').html('');
				$('#uemailavilable').html(trim(response));
			}
			else
			{
				$('#uemailavilable').html('');
			}	
		} 
		 
	});



	var url= SrcPath+"tdispatch/checkphoneuserdetails/";
	var dataS = "phone="+phone+"&passenger_id="+passenger_id;	
	
	$.ajax
	({ 			
		type: "POST",
		url: url, 
		data: dataS, 
		cache: false, 
		async:false,
		dataType: 'html',
		success: function(response) 
		{ 	
			if(trim(response) != 'N')
			{		
				$('#uphoneavilable').html('');
				$('#uphoneavilable').html(trim(response));
			}
			else
			{
				$('#uphoneavilable').html('');
			}	
		} 
		 
	});


	if($('#group_id'))
	{
		var group_id = $('#group_id').val();
		var total_fare = $('#total_fare').val();
		var payment_type = $('[name="payment_type"]:checked').val(); 
		//alert(payment_type);
		if(payment_type ==4 && group_id !='')
		{	
			var url= SrcPath+"tdispatch/checkgrouplimit/";
			var dataS = "group_id="+group_id+"&total_fare="+total_fare;	

			$.ajax
			({ 			
				type: "POST",
				url: url, 
				data: dataS, 
				cache: false, 
				async:false,
				dataType: 'html',
				success: function(response) 
				{ 	
					if(trim(response) == 'N')
					{	
						$("#payment_error").html("<?php echo __('account_no_sufficient'); ?>");	
						$('#payment_error').show();
						payment_valid = false;
					}
					else
					{
						$("#payment_error").html("");
						$('#payment_error').hide();
						payment_valid = true;
					}	
				} 
				 
			});
		}

	}	
	else
	{
		if(passenger_id !='')
		{
			var recurrent = $('[name="payment_type"]:checked').val(); 
			if(payment_type ==4)
			{
				$("#payment_error").html("<?php echo __('donot_have_account'); ?>");
				$('#payment_error').show();
				payment_valid = false;
			}
			else
			{
				$("#payment_error").html("");
				$('#payment_error').hide();
				payment_valid = true;
			}
		}

	}


	var uemailavilable = $('#uemailavilable').html();
	var uphoneavilable = $('#uphoneavilable').html();

	if(uphoneavilable == "" && uemailavilable == "" && payment_valid == true)
	{
		return true;
	}	
	else
	{
		event.preventDefault();	
		return false;
	}


}



    function driver_details()
    { 		
	var search_driver = $('#search_driver').val();
	var pass_logid = $('#passenger_log_id').val();
	var dataS = "pass_logid="+pass_logid+"&search_driver="+search_driver;		
	$.ajax
	({ 			
		type: "GET",
		url: "<?php echo URL_BASE; ?>tdispatch/search_driver_location", 
		data: dataS, 
		cache: false, 
		dataType: 'html',
		success: function(response) 
		{ 	
			$('#driver_details').html(response);			
		} 
		 
	});	
      }	

$(function(){ 


	$('#add_exclusion').click(function(){  
	var newRow = $("#sub_add tr").length+1; 
	 $("#sub_add").append('<tr id="row_'+newRow+'"><td><input type="text" placeholder="Exclus.start" name="exclus_start[]" id="exclus_start'+newRow+'"  class="required mt10" style="width:120px;"  title=""><br><span id="error'+newRow+'" style="display:none;color:red;font-size:11px;"></span><td><input type="text" placeholder="Exclus.end" name="exclus_end[]" id="exclus_end'+newRow+'"  class="required mt10 " style="width:120px;margin-left:12px;" title=""></td><td><button type="button" class="remove_icon" onClick="return removetr_contact('+newRow+');"></td></tr>');     

	$("#exclus_start"+newRow).datepicker( {
	showTimepicker:true,
	showSecond: true,
	dateFormat: 'yy-mm-dd',
	stepHour: 1,
	stepMinute: 1,
	minDateTime : new Date(),
	stepSecond: 1
	});


	$("#exclus_end"+newRow).datepicker( {
	showTimepicker:true,
	showSecond: true,
	dateFormat: 'yy-mm-dd',
	stepHour: 1,
	stepMinute: 1,
	minDateTime : new Date(),
	stepSecond: 1
	});


	return false;	
	});

});  

  function removetr_contact(rowid) {
   var r1 = "row_"+rowid;
   $("#sub_add tr").each(function () {    
    if(r1==$(this).attr('id')) {
     $(this).remove();
    }   
   });
   return false;
  }
   function getid(rowid) {
   var r2 = "row_"+rowid;
   }     

$('.cal_label').click(function (e) {
var id = $(this).text();

 if ($('#monthDays_'+id).is(':checked'))
 {	
       $('#monthDays_'+id).attr('checked',false);
       $('#calactive_'+id).removeClass('green_active');	
 }
 else
 {	
       $('#monthDays_'+id).attr('checked',true);
       $('#calactive_'+id).addClass('green_active');	
 }			

});

$('.cale_label').click(function (e) {
var id = $(this).text();

 if ($('#daysofweek_'+id).is(':checked'))
 {	
       $('#daysofweek_'+id).attr('checked',false);
       $('#calactive_'+id).removeClass('green_active');	
 }
 else
 {	
       $('#daysofweek_'+id).attr('checked',true);
       $('#calactive_'+id).addClass('green_active');	
 }			

});


$('#edit_booking').click(function() {
	$('#log_details').hide();
	$('#feedback_details').hide();
	$('#share_details').hide();
	$('#edit_details').show();
});

$('#log_booking').click(function() {
	$('#edit_details').hide();
	$('#feedback_details').hide();
	$('#share_details').hide();
	$('#log_details').show();

});

$('#feedback_booking').click(function() {
	$('#edit_details').hide();
	$('#log_details').hide();
	$('#share_details').hide();
	$('#feedback_details').show();
});

$('#share_booking').click(function() {
	$('#edit_details').hide();
	$('#log_details').hide();
	$('#feedback_details').hide();
	$('#share_details').show();
});


$('#rating_button').click(function() {

	var feedback_comment = $('#feedback_comment').val();

	var form_valid = 1; 

	if(trim(feedback_comment).length<=50)
	{ 
		$('#feedback_commenterror').html("<?php echo __('please_enter_comment'); ?>");
		form_valid = 0;
	}	
	else
	{
		$('#feedback_commenterror').html("");
	}
	if(form_valid == 1)
	{ 
		$("#feedback_success").html('<img src="<?php echo IMGPATH; ?>loader.gif">');

		var pass_logid = $('#pass_logid').val();
		var url= "<?php echo URL_BASE;?>tdispatch/update_controllercomments/?feedback_comments="+feedback_comment+'&pass_logid='+pass_logid;

		$.post(url, {
		}, function(response){
			$("#feedback_success").html("");
			//$('#feedback_success').html("<?php echo __('feedback_updated_success'); ?>");
			$('#feedback_comment').val("");
			location.href = "<?php echo URL_BASE;?>/tdispatch/managebooking/";
			
			if($('#feedback_success')){
			  $('#feedback_success').fadeIn('fast');
			  $('#feedback_success').animate({opacity: 1.0}, 2000)
			  $('#feedback_success').fadeOut('slow');
			}

		});
	}

});

$('#sharing_button').click(function() {

	var share_email = $('#share_email').val();
	var share_note = $('#share_note').val();
	var filter = /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i;
	var form_valid = 1; 
        if (!filter.test(share_email)) {
		$('#share_emailerror').html("<?php echo __('please_provide_email'); ?>");
		email.focus;
		form_valid = 0;	
	}
	else
	{
		$('#share_emailerror').html("");
	}

	if(trim(share_note).length<=50)
	{ 
		$('#share_noteerror').html("<?php echo __('please_enter_note'); ?>");
		form_valid = 0;
	}	
	else
	{
		$('#share_noteerror').html("");
	}
	if(form_valid == 1)
	{ 
		$("#shared_success").html('<img src="<?php echo IMGPATH; ?>loader.gif">');

		var pass_logid = $('#pass_logid').val();
		var url= "<?php echo URL_BASE;?>tdispatch/sharing_bookdetails/?share_email="+share_email+'&share_note='+share_note+'&pass_logid='+pass_logid;

		$.post(url, {
		}, function(response){
			$("#shared_success").html("");
			$('#shared_success').html("<?php echo __('booking_shared_success'); ?>");

			if($('#shared_success')){
			  $('#shared_success').fadeIn('fast');
			  $('#shared_success').animate({opacity: 1.0}, 2000)
			  $('#shared_success').fadeOut('slow');
			}

		});
	}

});


$('#cancel_button').click(function() {


	var cancel_Submit = confirm("<?php echo __('sure_want_cancel'); ?>");				
	if(cancel_Submit == true)
	{
		var pass_logid = $('#pass_logid').val();
		var url= "<?php echo URL_BASE;?>tdispatch/cancel_booking/?pass_logid="+pass_logid;

		$.post(url, {
		}, function(response){
			document.location.href="<?php echo URL_BASE; ?>tdispatch/managebooking";
		});
	}
	

});

$('#complete_payment').click(function() {

$('#complete_loading').html('<img src="<?php echo IMGPATH; ?>loader.gif" align="middle"> loading...');

	var pass_logid = $('#pass_logid').val();
	var complete_distance = $('#complete_distance').val();
	var complete_waitingtime = $('#complete_waitingtime').val();
	var complete_waitingcost = $('#complete_waitingcost').val();
	var complete_nightcharge = $('#complete_nightcharge').val();
	var complete_total = $('#complete_total').val();
	var complete_remark = $('#complete_remark').val();

	var url= SrcPath+"tdispatch/complete_payment/?pass_logid="+pass_logid+"&complete_distance="+complete_distance+"&complete_waitingtime="+complete_waitingtime+"&complete_waitingcost="+complete_waitingcost+"&complete_nightcharge="+complete_nightcharge+"&complete_total="+complete_total+"&complete_remark="+complete_remark;	

	$.post(url, {
	}, function(response){
		document.location.href="<?php echo URL_BASE; ?>tdispatch/managebooking";
	});


});


$('#complete_update').click(function() {

$('#complete_loading').html('<img src="<?php echo IMGPATH; ?>loader.gif" align="middle"> loading...');

	var pass_logid = $('#pass_logid').val();
	var complete_distance = $('#complete_distance').val();
	var complete_waitingtime = $('#complete_waitingtime').val();
	var complete_waitingcost = $('#complete_waitingcost').val();
	var complete_nightcharge = $('#complete_nightcharge').val();
	var complete_total = $('#complete_total').val();
	var complete_remark = $('#complete_remark').val();

	var cancel_Submit = confirm("<?php echo __('sure_want_complete'); ?>");				
	if(cancel_Submit == true)
	{
		var pass_logid = $('#pass_logid').val();
		var url= "<?php echo URL_BASE;?>tdispatch/complete_booking/?pass_logid="+pass_logid+"&complete_distance="+complete_distance+"&complete_waitingtime="+complete_waitingtime+"&complete_waitingcost="+complete_waitingcost+"&complete_nightcharge="+complete_nightcharge+"&complete_total="+complete_total+"&complete_remark="+complete_remark;

		$.post(url, {
		}, function(response){
			document.location.href="<?php echo URL_BASE; ?>tdispatch/managebooking";
		});
	}
	else
	{
		document.location.href="<?php echo URL_BASE; ?>tdispatch/managebooking";
	}

});


$('#complete_button').click(function(event) {

$("#complete_popup").lightbox_me({centered: true, onLoad: function() {
        $("#complete_popup").find("input:first").focus();
        	google.maps.event.trigger(tdmap, "resize");
            tdmap.setCenter(currCenter);	
    }});
					
event.preventDefault();
});

var tdmap,currCenter;
$('#dispatch_button').click(function(event) {

var check_valid1 = $("#editbooking_form").valid();
addbookingvar check_valid2 = check_passengerexit();
 var show_map = "<?php echo SHOW_MAP; ?>";
if(check_valid1 == true && check_valid2 == true)
{
	var url= SrcPath+"tdispatch/dispatchbooking/";
	var dataS = $('#editbooking_form').serialize();	

	$.ajax
	({ 			
		type: "POST",
		url: url, 
		data: dataS, 
		cache: false, 
		async:false,
		dataType: 'html',
		success: function(response) 
		{ 	
			$("#dispatch_popup").lightbox_me({centered: true, onLoad: function() {
				$("#dispatch_popup").find("input:first").focus();
				    if(show_map != 1)
				    {
                       			tdmap.refresh();	
				     }
			    }});

			driver_details();	

			event.preventDefault();
		} 
		 
	});

}



  
   
    if(show_map != 1)
    {		
	

	$('#close_x').click(function() {
	window.location.href = "<?php echo URL_BASE;?>tdispatch/managebooking";
	});

	$('.js_lb_overlay').click(function() { 
	window.location.href = "<?php echo URL_BASE;?>tdispatch/managebooking";
	});

	driver_details();

	var pickup_lat = $('#pickup_lat').val();
	var pickup_lng = $('#pickup_lng').val();

	var drop_lat = $('#drop_lat').val();
	var drop_lng = $('#drop_lng').val();
    //var currCenter;
   
   /*
   var latlng = new google.maps.LatLng(pickup_lat,pickup_lng);
      var options = {
        zoom: 6,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      
      var tdmap = new google.maps.Map(document.getElementById("dispatch_map"), options);
    */
    
    //alert(pickup_lat+pickup_lng);
	/*tdmap = new GMaps({
	el: '#dispatch_map',
	lat: pickup_lat,
	lng: pickup_lng,
	}); 
    currCenter = tdmap.getCenter();
   //alert(currCenter);
	tdmap.addMarker({
	lat: pickup_lat,
	lng: pickup_lng,
	title: "<?php echo __('Current_Location'); ?>",
	/* details: {
	  database_id: 42,
	  author: 'HPNeo'
	},
	click: function(e){
	  if(console.log)
	    console.log(e);
	  alert('You clicked in this marker');
	},
	mouseover: function(e){
	  if(console.log)
	    console.log(e);
	}*/
	//});
	/*console.log(drop_lat);
	console.log(drop_lng);
	
	if(drop_lat != "" && drop_lng != "")
	{
		tdmap.addMarker({
		lat: drop_lat,
		lng: drop_lng,
		title: "<?php echo __('Drop_Location'); ?>",
		/*infoWindow: {
		  content: '<p>HTML Content</p>'
		}*/
		//});
	//}
	//tdmap.refresh();

   //}	
 

//});


/* Map */
/*
	function updateStatus(status) {
		$("#info").html(status);

	}

	function clearMarker() {
		if (marker != null)
			marker.setMap(null);
	}
		
			var marker = null;
			var working = false;
      var marker;	
      var flag = false;

      var latlng = new google.maps.LatLng(54.559322, -4.174804);
      var options = {
        zoom: 6,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      
      var map = new google.maps.Map(document.getElementById("map"), options);

			//map.controls[google.maps.ControlPosition.TOP_RIGHT].push(new FullScreenControl(map));

      // set up directions renderer
      var rendererOptions = { draggable: false};
      var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
      directionsDisplay.setMap(map);
			directionsDisplay.setPanel(document.getElementById("directions"));
      google.maps.event.addListener(directionsDisplay, 'directions_changed', function() {
				updateStatus("Route changed");
				if (!working) {
					working = true;
					GetElevation(directionsDisplay.directions.routes[0]);
				}
      });
			google.maps.event.addListener(directionsDisplay, 'routeindex_changed', function() {
				updateStatus("Route index changed");
				/*if (!working) {
					working = true;*/
					//GetElevation(directionsDisplay.directions.routes[directionsDisplay.getRouteIndex()]);
				/*}*/
			//});

			// autocomplete
			/*var autocomplete = new google.maps.places.Autocomplete(document.getElementById('current_location'), {});
			var toAutocomplete = new google.maps.places.Autocomplete(document.getElementById('drop_location'), {});


			google.maps.event.addListener(autocomplete, 'place_changed', function() {
				var place = autocomplete.getPlace();
				GeocodeFromAddress();
				GotoLocation(place.geometry.location,1);
			});

			google.maps.event.addListener(toAutocomplete, 'place_changed', function() {
				var place = toAutocomplete.getPlace();
				GeocodeFromAddress();
				GotoLocation(place.geometry.location,2);
			});


			function GotoLocation(location,field) {
				GetLocationInfo(location,field);
				map.setCenter(location);
			}


		      function GetLocationInfo(latlng,field)
		      {
			if (latlng != null)
			{
			  ShowLatLong(latlng,field);
			}
		      }

		      function ShowLatLong(latLong,field)
		      {
			// show the lat/long
			if (marker != null) {
			  marker.setMap(null);
			}
			/*marker = new google.maps.Marker({
			  position: latLong,
			  map: map});
			*/	
			/*if(field == 1)
			{
				$('#pickup_lat').val(latLong.lat());
				$('#pickup_lng').val(latLong.lng());
				$('#payment_sec').show();	
			}
			else
			{
				$('#drop_lat').val(latLong.lat());
				$('#drop_lng').val(latLong.lng());
				$('#payment_sec').show();	
			}

		      }

			
      			function GeocodeFromAddress() {
	
				working = true;
				// clear all fields
				$("#info").html("");
				$("#distance").html("");
				$("#start").html("");
				$("#end").html("");
				$("#min").html("");
				$("#max").html("");
				$("#ascent").html("");
				$("#descent").html("");
				clearMarker();
			
        // geocode from address
        updateStatus("Locating from address...");
        var geocoder = new google.maps.Geocoder();
        var from = $("#current_location").val();
        geocoder.geocode({ 'address': from },
          function(results, status) {
            if (results[0]) {
              var result = results[0];
              var fromLatLng = result.geometry.location;
		$('#pickup_lat').val(result.geometry.location.lat());
		$('#pickup_lng').val(result.geometry.location.lng());
              GeocodeToAddress(fromLatLng);
            }
            else {
              updateStatus("From address not found");
		working = false;
            }
          }
        );
      }

      function GeocodeToAddress(fromLatLng) {
        // geocode to address
        updateStatus("Locating to address...");
        var geocoder = new google.maps.Geocoder();
        var to = $("#drop_location").val();
        geocoder.geocode({ 'address': to },
          function(results, status) {
            if (results[0]) {
              var result = results[0];
              var toLatLng = result.geometry.location;
		$('#drop_lat').val(result.geometry.location.lat());
		$('#drop_lng').val(result.geometry.location.lng());
              CalculateRoute(fromLatLng, toLatLng);
            }
            else {
              updateStatus("To address not found");
		working = false;
            }
          }
        );
      }

      function CalculateRoute(fromLatLng, toLatLng) {
        // calculate the route
        updateStatus("Calculating route...");
        var directions = new google.maps.DirectionsService();

				var routeType = $('#routeType').val();
				var travelMode = google.maps.DirectionsTravelMode.DRIVING;
				if (routeType == "Walking")
					travelMode = google.maps.DirectionsTravelMode.WALKING;
				else if (routeType == "Public transport")
					travelMode = google.maps.DirectionsTravelMode.TRANSIT;
				else if (routeType == "Cycling")
					travelMode = google.maps.DirectionsTravelMode.BICYCLING;
				
        var request = {
          origin: fromLatLng,
          destination: toLatLng,
          travelMode: travelMode,
	  provideRouteAlternatives: true
        };
        directions.route(request, function(result, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(result);

            GetElevation(result.routes[0]);
          }
          else {
            var statusText = getDirectionStatusText(status);
            updateStatus("An error occurred calculating the route - " + statusText);
						working = false;
          }
        });
      }

			var locations;
      function GetElevation(route) {
				clearMarker();
        // show distance
				var distance = 0;
				var time = 0;
				for (var i=0; i<route.legs.length; i++) {
					var theLeg = route.legs[i];
					distance += theLeg.distance.value;
					time += theLeg.duration.value;
				}


				hm_hours = ('0'+Math.round(time/3600) % 24).slice(-2)+' hours';
				hm_secs = ('0'+Math.round(time/60)%60).slice(-2)+' mins';
				
				if(hm_hours != '00 hours')
				{
					show_time = hm_hours+hm_secs;
				}
				else
				{
					show_time = hm_secs;					
				}


				$("#distance").html(showDistance(distance));
				
				/*if(flag == true)
				{*/
				/*	var km = ((distance/100) / 10).toFixed(1);
					$('#find_km').html(km+" km");
					$('#distance_km').val(km);
					$('#desc').html('Rate Kilometer '+km); 
	
					$('#find_duration').html(show_time);	
					$('#total_duration').val(show_time);	
					var model_minfare = $('#model_minfare').val();
					$('#min_value').html(model_minfare);

				var model_id = $('#taxi_model').val();
				var pickup_location = $('#current_location').val();

				var pickup_lat = $('#pickup_lat').val();
				var pickup_lng = $('#pickup_lng').val();

				var pickup_latlng = pickup_lat+','+pickup_lng;

				var city = "";
				var state = "";

				geocoder = new google.maps.Geocoder();
				var latlng = new google.maps.LatLng(pickup_lat,pickup_lng);
				geocoder.geocode({'latLng': latlng}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						//Check result 0
						var result = results[0];
						//look for locality tag and administrative_area_level_1

						for(var i=0, len=result.address_components.length; i<len; i++) {
							var ac = result.address_components[i];
							if(ac.types.indexOf("locality") >= 0) city = ac.long_name;
							if(ac.types.indexOf("administrative_area_level_1") >= 0) state = ac.long_name;
						}
						//only report if we got Good Stuff
						if(city != '') {
							//alert("Hello to you out there in "+city);
							$('#cityname').val(city);
							var city_id = $('#city_id').val();
							calculate_totalfare(km,model_id,city,city_id);

						}
					} 
				});
	
					/*
					var total_fare = Math.round(parseFloat(km)*parseFloat(model_minfare));
					var vat_tax = $('#vat_tax').html();
					var tot_tax =  (total_fare*vat_tax/100).toFixed(2);
					var tot_amt = parseFloat(total_fare)+parseFloat(tot_tax);
					$("#min_fare").html(total_fare);
					$('#sub_total').html(total_fare);
					$('#total_price').html(total_fare);
					$('#total_fare').val(total_fare);
					$('#min_fare').removeClass('strike');

					var price = $('#fixedprice').val();
					if(price == '')
					{
					//$('#show_suggestion').hide();	
					$('#min_fare').removeClass('strike');
					var tot_price = $('#total_fare').val();	
					$('#total_price').html(tot_price);

					}
					else
					{	//$('#show_suggestion').hide();
					$('#total_price').html(price);
					$('#min_fare').addClass('strike');
					}
					*/
				/*}
				else
				{

					var price = $('#fixedprice').val();
					if(price == '')
					{
					//$('#show_suggestion').hide();	
					$('#min_fare').removeClass('strike');
					var tot_price = $('#total_fare').val();	
					$('#total_price').html(tot_price);

					}
					else
					{	//$('#show_suggestion').hide();
					$('#total_price').html(price);
					$('#min_fare').addClass('strike');
					}

					flag = true;					
				}
				*/

				
        // get all the lat/longs
       /* locations = [];
        for (var i=0; i<route.legs.length; i++) {
          var thisLeg = route.legs[i];
          for (var j=0; j<thisLeg.steps.length; j++) {
            var thisStep = thisLeg.steps[j];
            for (var k=0; k<thisStep.lat_lngs.length; k++) {
              locations.push(thisStep.lat_lngs[k]);
            }
          }
        }
				
				updateStatus("Calculating elevation for " + locations.length + " locations...");
				elevations = [];
				currentPos = 0;
				getElevation();
			}
			
			function showResults(results) {
				// display the results
				var ascent = 0;
				var descent = 0;
				ShowElevation("#start", results[0]);
				ShowElevation("#end", results[results.length-1]);
				var minElevation = results[0];
				var maxElevation = results[0];
				var chartData = [];
				var distance = 0;
				
				for (var i=0; i<results.length; i++) {
					minElevation = Math.min(results[i], minElevation);
					maxElevation = Math.max(results[i], maxElevation);
					
					// calculate distance
					if (i > 0) {
						var d = google.maps.geometry.spherical.computeDistanceBetween (locations[i-1], locations[i]);
						distance += d;
					}
					
					chartData.push([distance/1000, results[i]]);
					
					if (i>0) {
						var thisAscent = results[i] - results[i-1];
						if (thisAscent > 0)
							ascent += thisAscent;
						else
							descent -= thisAscent;
					}
				}
				ShowElevation("#ascent", ascent);
				ShowElevation("#descent", descent);
				ShowElevation("#min", minElevation);
				ShowElevation("#max", maxElevation);
				// chart
				
				
				function showTooltip(x, y, contents) {
					$('<div id="tooltip">' + contents + '</div>').css( {
							position: 'absolute',
							display: 'none',
							top: y,
							left: x + 20,
							border: '1px solid #fdd',
							padding: '2px',
							'background-color': '#fee',
							opacity: 0.80
					}).appendTo("body").fadeIn(200);
				}

				var previousPoint = null;
			}
			
			var currentPos = 0;
			var partLength = 100;
			var elevations = [];
			function getElevation() {
        // calculate the elevation of the route
				var locationsPart = [];
				var end = Math.min(locations.length, currentPos+100);
				for (i=currentPos; i<end; i++) {
					locationsPart.push(locations[i]);
				}
        updateStatus("Calculating elevation for " + currentPos + " to " + end + " (of " + locations.length + ")...");
				
				var positionalRequest = {
          'locations': locationsPart
        };

        var elevator = new google.maps.ElevationService();
        // Initiate the location request
        elevator.getElevationForLocations(positionalRequest,
          function(results, status) {
            if (status == google.maps.ElevationStatus.OK) {
							for (var i=0; i<results.length; i++) {
								elevations.push(results[i].elevation);
							}
							currentPos += partLength;
							if (currentPos > locations.length) {
								showResults(elevations);
								updateStatus("Elevation calculated using " + locations.length + " locations");
								working = false;
							}
							else {
								getElevation();
							}
            }
            else {
							if (status == google.maps.ElevationStatus.OVER_QUERY_LIMIT) {
								updateStatus("Over query limit calculating the elevation for " + currentPos + " to " + (currentPos + partLength) + " (of " + locations.length + "), waiting 1 second before retrying");
								setTimeout("getElevation()", 1000);		
							} 
							else {
								updateStatus("An error occurred calculating the elevation - " + elevationStatusDescription(status));
								working = false;
							}
            }
          }
        );
      }

			function elevationStatusDescription(status) {
				switch (status) {
					case "OVER_QUERY_LIMIT": return "Over query limit";
					case "UNKNOWN_ERROR": return "Unknown error";
					default: return status;
				}
			}
			
      function ShowElevation(selector, elevation) {
        $(selector).html(Math.round(elevation) + " metres (" + Math.round(elevation*3.2808399) + " feet)");
      }

/* Map */

/*</script>


<style>
#dispatch_map{
  display: block;
  width: 400px !important;
  height: 380px !important;
  margin-top:20px ;	
  margin-left:-20px;
  position:absolute !important;
  -moz-box-shadow: 0px 5px 20px #ccc !important;
  -webkit-box-shadow: 0px 5px 20px #ccc !important;
  box-shadow: 0px 5px 20px #ccc !important;
}
#dispatch_map.large{
  height:380px !important;
}

#map{
  display: block;
  width: 100%;
  height: 350px;
  margin-top:20px;	
  margin: 0 auto;
  -moz-box-shadow: 0px 5px 20px #ccc;
  -webkit-box-shadow: 0px 5px 20px #ccc;
  box-shadow: 0px 5px 20px #ccc;
}
#map.large{
  height:350px;
}

.implementation{
	border:dashed 2px #333333;
	background-color:#CCCCCC;
	color:#000000;
	width:50%;
}

.spacer{
clear:both;
height:0px;
}
.left{
	float:left;
	width:250px;
}

</style>

<?php

		echo $output;exit;
	}

*/

	public function action_sharing_bookdetails()
	{
		$user_createdby = $_SESSION['userid'];	
		$tdispatch_model = Model::factory('tdispatch');
		$post = $_REQUEST; 

		$booking_details = $tdispatch_model->get_bookingdetails($post['pass_logid']);

		$passenger_logid = $post['pass_logid'];

		/* Create Log */		
		$company_id = $_SESSION['company_id'];			
		$log_message = __('log_message_share');
		$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
		$log_message = str_replace("EMAILID",$post['share_email'],$log_message); 
		$log_booking = __('log_booking_share');
		$log_booking = str_replace("PASS_LOG_ID",$passenger_logid,$log_booking); 
		$log_booking = str_replace("EMAILID",$post['share_email'],$log_booking); 
		$log_booking = str_replace("NOTE",$post['share_note'],$log_booking); 
		$log_status = $tdispatch_model->create_logs($passenger_logid,$company_id,$user_createdby,$log_message,$log_booking);
		?>
		<script type="text/javascript">load_logcontent();</script>
		<?php 
		/* Create Log */


		if($booking_details)
		 {

				$trip_details='';   
				$trip_details =' <p>'.__('create_date').' : '.$booking_details[0]['createdate'].'</p>'.
				'<p>'.__('pictup_datetime').' : '.$booking_details[0]['pickup_time'].'</p>'.
				'<p>'.__('Current_Location').' : '.$booking_details[0]['current_location'].'</p>'.
				'<p>'.__('Drop_Location').' : '.$booking_details[0]['drop_location'].'</p>'.                                      	
				'<p>'.__('total_cost').' : '.$booking_details[0]['approx_fare'].'</p>'.
				'<p>'.__('duration').' : '.$booking_details[0]['approx_duration'].'</p>'.
				'<p>'.__('distance').' : '.$booking_details[0]['approx_distance'].'</p>'.
				'<p>'.__('No_Passengers').' : '.$booking_details[0]['no_passengers'].'</p>'.
				'<p>'.__('luggage').' : '.$booking_details[0]['luggage'].'</p>';


				$mail="";								
				$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_SHAREDUSER=>ucfirst($_SESSION['name']),REPLACE_CONTROLLERNAME=>ucfirst($_SESSION['name']),REPLACE_MESSAGE=>$post['share_note'],REPLACE_USERNAME=>ucfirst($booking_details[0]['passenger_name']),REPLACE_EMAIL=>$booking_details[0]['passenger_email'],REPLACE_PHONE=>$booking_details[0]['passenger_phone'],REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_TRIPDETAILS=>$trip_details,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);

				       $message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'sharebooking.html',$replace_variables);

					$to = $post['share_email'];
					$from = $this->siteemail;
					$subject = __('share_booking_details');	
					$redirect = "tdispatch/managebooking";	
					if(SMTP == 1)
					{
						include($_SERVER['DOCUMENT_ROOT']."/modules/SMTP/smtp.php");
						echo 'Success'; exit;
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

						echo 'Success'; exit;
					}
					
		}
	
	}

	public function action_update_controllerrating()
	{
		$user_createdby = $_SESSION['userid'];
		$tdispatch_model = Model::factory('tdispatch');
		$post = $_REQUEST; 
		$booking_details = $tdispatch_model->update_bookingrating($post);
		$passenger_logid = $post['pass_logid'];

		/* Create Log */		
		$company_id = $_SESSION['company_id'];			
		$log_message = __('log_message_rated');
		$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
		$log_booking = __('log_booking_rated');
		$log_booking = str_replace("PASS_LOG_ID",$passenger_logid,$log_booking); 
		$log_booking = str_replace("STAR",$post['rating'],$log_booking); 
		$log_status = $tdispatch_model->create_logs($passenger_logid,$company_id,$user_createdby,$log_message,$log_booking);
		?>
		<script type="text/javascript">load_logcontent();</script>
		<?php 
		/* Create Log */

		echo $booking_details;exit;
	}

	public function action_update_controllercomments()
	{
		$user_createdby = $_SESSION['userid'];
		$tdispatch_model = Model::factory('tdispatch');
		$post = $_REQUEST; 
		$booking_details = $tdispatch_model->update_bookingcomments($post);
		$passenger_logid = $post['pass_logid'];

		/* Create Log */		
		$company_id = $_SESSION['company_id'];			
		$log_message = __('log_message_feedback');
		$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
		$log_booking = __('log_booking_feedback');
		$log_booking = str_replace("PASS_LOG_ID",$passenger_logid,$log_booking); 
		$log_booking = str_replace("FEEDBACK",$post['feedback_comments'],$log_booking); 
		$log_status = $tdispatch_model->create_logs($passenger_logid,$company_id,$user_createdby,$log_message,$log_booking);
		
		 Message::success(__('feedback_updated_success'));
		?>
		<script type="text/javascript">load_logcontent();</script>
		<?php 
		/* Create Log */

		echo $booking_details;exit;
	}


	public function action_search_driver_location()
	{

		if(isset($_REQUEST["pass_logid"]))
		{
			$tdispatch_model = Model::factory('tdispatch');
			$booking_details = $tdispatch_model->get_bookingdetails($_REQUEST['pass_logid']);

			$latitude = $booking_details[0]["pickup_latitude"];
			$longitude = $booking_details[0]["pickup_longitude"];
			$miles = '';
			$no_passengers = $booking_details[0]["no_passengers"];
			$taxi_fare_km = $booking_details[0]["min_fare"];
			//$taxi_min_fare = $_POST["taxi_min_fare"]; 
			$taxi_model = $booking_details[0]["taxi_modelid"];
			$taxi_type = ''; 
			$maximum_luggage = $booking_details[0]["luggage"];
			$pass_logid = $_REQUEST["pass_logid"];
			$company_id = $booking_details[0]["company_id"];

			if(isset($_SESSION['search_city']))
			{
				$cityname = $_SESSION['search_city'];			
			}
			else
			{
				$cityname = '';			
			}

			$search_driver = $_REQUEST['search_driver'];
			$driver_details = $tdispatch_model->search_driver_location($latitude,$longitude,$miles,$no_passengers,$_REQUEST,$taxi_fare_km,$taxi_model,$taxi_type,$maximum_luggage,$cityname,$pass_logid,$company_id,$search_driver);
			//print_r($driver_details);
			$count=count($driver_details);
	
				$output ='';
			        $sno = 0;
				if($count > 0){ 
					foreach($driver_details as $listings) {
						$sno++;						
						$miles_to_km = round(($listings['distance_miles'] * 1.609344),2);
						$distance_miles = (ceil($miles_to_km*100)/100);
						$color=($sno%2==0) ? 'whitecp' : 'colorcp';  
						$output .= '<p class='.$color.' id="'.$listings['driver_id'].'_'.$listings['taxi_id'].'_'.round($distance_miles,2).'"><a  href="javascript:;">'.$listings['name'].' ('.__('miles').' '.$distance_miles.')</a></p>';
						
					}
				}
				else
				{
				  $output .= __('no_driver_available');
				}
?>
      <script>	
      $('#driver_details p').click(function() {
		var detailsid = this.id;
		var findimg = detailsid.split('_');

		var pass_logid = $('#passenger_log_id').val();	
		var dataS = "pass_logid="+pass_logid+"&driver_id="+findimg[0]+"&taxi_id="+findimg[1]+"&driver_away_in_km="+findimg[2];	

		$("#show_process").html('<img src="<?php echo IMGPATH; ?>loader.gif">');
		$.ajax
		({ 			
			type: "GET",
			url: "<?php echo URL_BASE;?>tdispatch/updatebooking", 
			data: dataS, 
			cache: false, 
			dataType: 'html',
			success: function(response) 
			{ 		
				$("#show_process").html('');
				console.log(response);
				//document.location.href="<?php echo URL_BASE;?>tdispatch/managebooking/#stuff";
				 window.location="<?php echo URL_BASE;?>tdispatch/managebooking";
			} 
			 
		});	
      });	

</script>		
<?php
			echo $output;exit;

			exit;
		}
	}

	public function action_updatebooking()
	{
		$user_createdby = $_SESSION['userid'];
		$company_id = $_SESSION['company_id'];
		$tdispatch_model = Model::factory('tdispatch');
		$api = Model::factory('api');
		$commonmodel = Model::factory('commonmodel');
		$company_all_currenttimestamp = $commonmodel->getcompany_all_currenttimestamp($company_id);
		if(isset($_REQUEST['driver_id']))
		{
			$update_booking = $tdispatch_model->updatebooking_logid($_REQUEST);
		}
		else
		{
			Message::error(__('no_driver_available'));
			$this->request->redirect("tdispatch/managebooking");
		}

		$passenger_logid = $_REQUEST['pass_logid'];

		$passenger_details = $tdispatch_model->get_bookingdetails($_REQUEST['pass_logid']);
		$driver_details = $tdispatch_model->get_driver_profile_details($_REQUEST['driver_id']);				
		//print_r($bookinglog_details);

		/* Create Log */		
		$company_id = $_SESSION['company_id'];			
		$log_message = __('log_message_dispatched');
		$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
		$log_booking = __('log_booking_dispatched');
		$log_booking = str_replace("DRIVERNAME",$driver_details[0]['name'],$log_booking); 
		$log_status = $tdispatch_model->create_logs($passenger_logid,$company_id,$user_createdby,$log_message,$log_booking);
		?>
		<script type="text/javascript">load_logcontent();</script>
		<?php 
		/* Create Log */

		if($update_booking) {

			/** Dispatch algorithm **/			
			$company_dispatch = DB::select()->from(TBLALGORITHM)
									->where('alg_company_id','=',$company_id)
									->order_by('aid','desc')
									->limit(1)
									->execute()
									->as_array();

			$tdispatch_type = '';
			//$match_vehicletype = '';
			$hide_customer = '';
			$hide_droplocation = '';
			//$hide_fare = '';

			if(count($company_dispatch) > 0)	
			{
				$tdispatch_type = $company_dispatch[0]['labelname'];
				//$match_vehicletype = $company_dispatch[0]['match_vehicletype'];
				$hide_customer = $company_dispatch[0]['hide_customer'];
				$hide_droplocation = $company_dispatch[0]['hide_droplocation'];
				//$hide_fare = $company_dispatch[0]['hide_fare'];
			}

			/** Dispatch algorithm **/
								
								$cityname = "";
								$driver_away_in_km = $_REQUEST['driver_away_in_km'];
								
								//$taxi_speed=$api->get_taxi_speed($bookinglog_details[0]['taxi_id']);
								//$estimated_time = $api->estimated_time($driver_away_in_km,$taxi_speed);
								
								/***** Insert the druiver details to driver request table ************/
								$insert_array = array(
														"trip_id" => $passenger_logid,
														"available_drivers" 			=> $_REQUEST['driver_id'],
														"status" 	=> '0',
														"rejected_timeout_drivers"		=> "",
														"createdate"		=> $company_all_currenttimestamp,
													);								
								//Inserting to Transaction Table 
								$transaction = $commonmodel->insert(DRIVER_REQUEST_DETAILS,$insert_array);	
								$detail = array("passenger_tripid"=>$passenger_logid,"notification_time"=>"");
								$msg = array("message" => __('api_request_confirmed_passenger'),"status" => 1,"detail"=>$detail);
								exit;
           }
	}	



	public function action_cancel_booking()
	{
		$user_createdby = $_SESSION['userid'];
		$tdispatch_model = Model::factory('tdispatch');
		$update_booking = $tdispatch_model->cancelbooking_logid($_REQUEST);
		$passenger_logid = $_REQUEST['pass_logid'];
		/* Create Log */		
		$company_id = $_SESSION['company_id'];			
		$log_message = __('log_message_cancelled');
		$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
		$log_booking = __('log_booking_cancelled');
		$log_booking = str_replace("PASS_LOG_ID",$passenger_logid,$log_booking); 
		$log_status = $tdispatch_model->create_logs($passenger_logid,$company_id,$user_createdby,$log_message,$log_booking);
		?>
		<script type="text/javascript">load_logcontent();</script>
		<?php 
		/* Create Log */


		if($update_booking)
		{
			Message::success(__('successfully_cancel_booking'));
			echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;
		}
	}

	public function action_complete_booking()
	{
		$user_createdby = $_SESSION['userid'];
		$tdispatch_model = Model::factory('tdispatch');
		$update_booking = $tdispatch_model->completebooking_logid($_REQUEST);
		$passenger_logid = $_REQUEST['pass_logid'];
		/* Create Log */		
		$company_id = $_SESSION['company_id'];			
		$log_message = __('log_message_completed');
		$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
		$log_booking = __('log_booking_completed');
		$log_booking = str_replace("PASS_LOG_ID",$passenger_logid,$log_booking); 
		$log_status = $tdispatch_model->create_logs($passenger_logid,$company_id,$user_createdby,$log_message,$log_booking);
		?>
		<script type="text/javascript">load_logcontent();</script>
		<?php 
		/* Create Log */


		if($update_booking)
		{
			Message::success(__('booking_completed_successfully'));
			echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;
		}
	}


	public function action_complete_payment()
	{
		$user_createdby = $_SESSION['userid'];
		$tdispatch_model = Model::factory('tdispatch');
		$log_status = $tdispatch_model->complete_payment($_REQUEST);

		if($log_status == -1)
		{
			Message::error(__('invalid_trip'));
			echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;
		}
		elseif($log_status == -2)
		{
			Message::error(__('trip_already_updated'));
			echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;
		}
		elseif($log_status == -3)
		{
			Message::error(__('user_donot_have_creditcard'));
			echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;
		}
		elseif($log_status == 1)
		{
			$passenger_logid = $_REQUEST['pass_logid'];
			/* Create Log */		
			$company_id = $_SESSION['company_id'];			
			$log_message = __('log_message_completed');
			$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
			$log_booking = __('log_booking_completed');
			$log_booking = str_replace("PASS_LOG_ID",$passenger_logid,$log_booking); 
			$log_status = $tdispatch_model->create_logs($passenger_logid,$company_id,$user_createdby,$log_message,$log_booking);
			?>
			<script type="text/javascript">load_logcontent();</script>
			<?php
			Message::success(__('tripfare_updated_successfully'));
			echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;
		}
		elseif($log_status == 2)
		{

			$passenger_logid = $_REQUEST['pass_logid'];
			/* Create Log */		
			$company_id = $_SESSION['company_id'];			
			$log_message = __('log_message_completed');
			$log_message = str_replace("PASS_LOG_ID",$passenger_logid,$log_message); 
			$log_booking = __('log_booking_completed');
			$log_booking = str_replace("PASS_LOG_ID",$passenger_logid,$log_booking); 
			$log_status = $tdispatch_model->create_logs($passenger_logid,$company_id,$user_createdby,$log_message,$log_booking);
			?>
			<script type="text/javascript">load_logcontent();</script>
			<?php
			Message::success(__('payment_made_successfully'));
			echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;
		}
		else
		{
			Message::success(__($log_status));
			echo "<script> document.location.href='".URL_BASE."tdispatch/managebooking';</script>";exit;
		}


		if($update_booking)
		{
			echo 'Success';exit;
		}
	}



	public function action_frequent_location()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/dashboard");
	   	}
	
		//Page Title
		$this->page_title =  __('frequent_location');
		$this->selected_page_title = __('frequent_location');
		
		$tdispatch_model = Model::factory('tdispatch');

		$count_frequent_location = $tdispatch_model->count_frequent_location();
		
			//pagination loads here
		//-------------------------
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
			'current_page'   => array('source' => 'query_string','key' => 'page'),
			'items_per_page' => REC_PER_PAGE,
			'total_items'    => $count_frequent_location,
			'view' => 'pagination/punbb',
		));
   
		$all_frequent_location = $tdispatch_model->all_frequent_location($offset, REC_PER_PAGE);
		//****pagination ends here***//
		
	
		//send data to view file 
		$view= View::factory('admin/tdispatch/manage_frequent_location')
				->bind('all_frequent_location',$all_frequent_location)
				->bind('pag_data',$pag_data)
				->bind('Offset',$offset);


		$this->template->title= SITENAME." | ".__('frequent_location');
		$this->template->page_title= __('frequent_location'); 
		$this->template->content = $view;				
	}

	public function action_add_frequentlocation()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$tdispatch_model = Model::factory('tdispatch');
		/**To get the form submit button name**/
		$addlocation_submit =arr::get($_REQUEST,'submit_addlocation'); 
		$errors = array();
		$post_values = array();
		
		if ($addlocation_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			$post = Arr::map('trim', $this->request->post());

			$validator = $tdispatch_model->validate_addlocation(arr::extract($post,array('location_name','location')));

			if($validator->check())
			{
			   $added=$tdispatch_model->addlocation($post);

			/* Create Log */		
			$company_id = $_SESSION['company_id'];			
			$log_message = __('log_message_location_added');
			$log_message = str_replace("LOCATIONNAME",$post['location_name'],$log_message); 
			$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
			?>
			<script type="text/javascript">load_logcontent();</script>
			<?php 
			/* Create Log */	

			   
				if($added == 1) 
				{ 
					$mail="";			
						
					Message::success(__('sucessfull_added_frequent_location'));

					$this->request->redirect("tdispatch/frequent_location");

				}	
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view= View::factory('admin/tdispatch/add_frequent_location')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_frequent_location');
		$this->template->page_title= __('add_frequent_location'); 
		$this->template->content = $view;
	}

	public function action_edit_frequentlocation()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$tdispatch_model = Model::factory('tdispatch');
		/**To get the form submit button name**/
		$editlocation_submit =arr::get($_REQUEST,'submit_editlocation'); 
		$fid = $this->request->param('id');
		$location_details = $tdispatch_model->location_details($fid);
		if(count($location_details) == 0)
		{
			Message::success(__('invalid_frequent_location'));
			$this->request->redirect("tdispatch/frequent_location");
		}

		$errors = array();
		$post_values = array();
		
		if ($editlocation_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			$post = Arr::map('trim', $this->request->post());

			$validator = $tdispatch_model->validate_editlocation(arr::extract($post,array('location_name','location')),$fid);

			if($validator->check())
			{
			   $status=$tdispatch_model->editlocation($fid,$post);

			/* Create Log */		
			$company_id = $_SESSION['company_id'];			
			$log_message = __('log_message_location_updated');
			$log_message = str_replace("LOCATIONNAME",$post['location_name'],$log_message); 
			$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
			?>
			<script type="text/javascript">load_logcontent();</script>
			<?php 
			/* Create Log */
			   
				if($status == 1) 
				{ 
					Message::success(__('sucessfull_update_frequent_location'));
					$this->request->redirect("tdispatch/frequent_location");
				}
				else
				{
					Message::error(__('not_updated'));
					$this->request->redirect("tdispatch/frequent_location");
				}
	
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view= View::factory('admin/tdispatch/edit_frequent_location')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values)
						->bind('location_details',$location_details);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('edit_frequent_location');
		$this->template->page_title= __('edit_frequent_location'); 
		$this->template->content = $view;

	}

	public function action_delete_frequentlocation()
	{
		$user_createdby = $_SESSION['userid'];
		$fid = $_REQUEST['fid'];
		$tdispatch_model = Model::factory('tdispatch');
		$location_details = $tdispatch_model->location_details($fid);
		$status = $tdispatch_model->delete_frequentlocation($fid);

		/* Create Log */		
		$company_id = $_SESSION['company_id'];			
		$log_message = __('log_message_location_deleted');
		$log_message = str_replace("LOCATIONNAME",$location_details[0]['location_name'],$log_message); 
		$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
		?>
		<script type="text/javascript">load_logcontent();</script>
		<?php 
		/* Create Log */

		if($status){
		Message::success(__('sucessfull_delete_frequent_location'));
		$this->request->redirect("tdispatch/frequent_location");
		}
	}

	public function action_frequent_journey()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/dashboard");
	   	}
	
		//Page Title
		$this->page_title =  __('frequent_journey');
		$this->selected_page_title = __('frequent_journey');
		
		$tdispatch_model = Model::factory('tdispatch');

		$count_frequent_location = $tdispatch_model->count_frequent_journey();
		
			//pagination loads here
		//-------------------------
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
			'current_page'   => array('source' => 'query_string','key' => 'page'),
			'items_per_page' => REC_PER_PAGE,
			'total_items'    => $count_frequent_location,
			'view' => 'pagination/punbb',
		));
   
		$all_frequent_journey = $tdispatch_model->all_frequent_journey($offset, REC_PER_PAGE);
		//****pagination ends here***//
		
	
		//send data to view file 
		$view= View::factory('admin/tdispatch/manage_frequent_journey')
				->bind('all_frequent_journey',$all_frequent_journey)
				->bind('pag_data',$pag_data)
				->bind('Offset',$offset);


		$this->template->title= SITENAME." | ".__('frequent_journey');
		$this->template->page_title= __('frequent_journey'); 
		$this->template->content = $view;				
	}

	public function action_add_frequentjourney()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$tdispatch_model = Model::factory('tdispatch');
		/**To get the form submit button name**/
		$addlocation_submit =arr::get($_REQUEST,'submit_addjourney'); 
		$errors = array();
		$post_values = array();
		
		if ($addlocation_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			$post = Arr::map('trim', $this->request->post());

			$validator = $tdispatch_model->validate_addjourney(arr::extract($post,array('journey_name','from_location','to_location')));

			if($validator->check())
			{
			   $added=$tdispatch_model->addjourney($post);

			/* Create Log */		
			$company_id = $_SESSION['company_id'];			
			$log_message = __('log_message_journey_added');
			$log_message = str_replace("JOURNEYNAME",$post['journey_name'],$log_message); 
			$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
			?>
			<script type="text/javascript">load_logcontent();</script>
			<?php 
			/* Create Log */	
			   
				if($added == 1) 
				{ 
					$mail="";			
						
					Message::success(__('sucessfull_added_frequent_journey'));

					$this->request->redirect("tdispatch/frequent_journey");

				}	
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view= View::factory('admin/tdispatch/add_frequent_journey')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_frequent_journey');
		$this->template->page_title= __('add_frequent_journey'); 
		$this->template->content = $view;
	}

	public function action_edit_frequentjourney()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$tdispatch_model = Model::factory('tdispatch');
		/**To get the form submit button name**/
		$editlocation_submit =arr::get($_REQUEST,'submit_editjourney'); 
		$fjid = $this->request->param('id');
		$journey_details = $tdispatch_model->journey_details($fjid);
		if(count($journey_details) == 0)
		{
			Message::success(__('invalid_frequent_journey'));
			$this->request->redirect("tdispatch/frequent_journey");
		}

		$errors = array();
		$post_values = array();
		
		if ($editlocation_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			$post = Arr::map('trim', $this->request->post());

			$validator = $tdispatch_model->validate_editjourney(arr::extract($post,array('journey_name','from_location','to_location')),$fjid);

			if($validator->check())
			{
			   $status=$tdispatch_model->editjourney($fjid,$post);

			/* Create Log */		
			$company_id = $_SESSION['company_id'];			
			$log_message = __('log_message_journey_updated');
			$log_message = str_replace("JOURNEYNAME",$post['journey_name'],$log_message); 
			$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
			?>
			<script type="text/javascript">load_logcontent();</script>
			<?php 
			/* Create Log */
			   
				if($status == 1) 
				{ 
					Message::success(__('sucessfull_update_frequent_journey'));
					$this->request->redirect("tdispatch/frequent_journey");
				}
				else
				{
					Message::error(__('not_updated'));
					$this->request->redirect("tdispatch/frequent_journey");
				}
	
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view= View::factory('admin/tdispatch/edit_frequent_journey')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values)
						->bind('journey_details',$journey_details);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('edit_frequent_journey');
		$this->template->page_title= __('edit_frequent_journey'); 
		$this->template->content = $view;

	}

	public function action_delete_frequentjourney()
	{
		$user_createdby = $_SESSION['userid'];
		$fjid = $_REQUEST['fjid'];
		$tdispatch_model = Model::factory('tdispatch');
		$journey_details = $tdispatch_model->journey_details($fjid);
		$status = $tdispatch_model->delete_frequentjourney($fjid);

		/* Create Log */		
		$company_id = $_SESSION['company_id'];			
		$log_message = __('log_message_journey_deleted');
		$log_message = str_replace("JOURNEYNAME",$journey_details[0]['journey_name'],$log_message); 
		$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
		?>
		<script type="text/javascript">load_logcontent();</script>
		<?php 

		/* Create Log */

		if($status){
		Message::success(__('sucessfull_delete_frequent_journey'));
		$this->request->redirect("tdispatch/frequent_journey");
		}
	}

	public function action_get_suggestedlocation()
	{
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';
		$get_suggestedlocation = $tdispatch_model->get_suggestedlocation();

		if(count($get_suggestedlocation) > 0)
		{
			foreach($get_suggestedlocation as $details)
			{
				$output .='<li class="" id="selectclass_'.$details['fid'].'"><a href="javascript:void(0)" onclick="popup_location('.$details['fid'].');" id="fid_'.$details['fid'].'">'.$details['location'].'</a></li>';
			}
		}
		else
		{
			$output .='<li>'.__('no_data').'</li>';
		}
	
		echo $output;exit;
	}

	public function action_get_suggestedjourney()
	{
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';

		$get_suggestedjourney = $tdispatch_model->get_suggestedjourney();
		if(count($get_suggestedjourney) > 0)
		{
			foreach($get_suggestedjourney as $details)
			{


				$output .= "<li title=\"".__('from').' '.$details['from_location'].' '.__('to').' '.$details['to_location']."\"><a href=\"javascript:;\" onclick=\"change_fromtolocation('".urlencode($details['from_location'])."','".urlencode($details['to_location'])."');\"><span class=\"journey-from\">".__('from').":</span><span class='journey-name'>".$details['from_location']."</span><span class='journey-to'>".__('to').":</span><span class='journey-name'>".$details['to_location']."</span></a></li>";

			}
		}
		else
		{
			$output .='<li>'.__('no_data').'</li>';
		}
	
		echo $output;exit;	
	}

	public function action_load_location()
	{
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';
		$like_q =arr::get($_REQUEST,'q'); 

		$like_q = urlencode($like_q);
		echo $cname = "select\n";
		$getmodel_details = $tdispatch_model->load_location($like_q);

		foreach($getmodel_details as $details) {
			$cname = $details['location_name'];
			echo "$cname\n";

		}
		exit;
	}

	public function action_get_log_content()
	{
	?>
		

		<?php
		$tdispatch_model = Model::factory('tdispatch');
		$output = '';
		$get_logcontent = $tdispatch_model->load_logcontent();
		$usertype = $_SESSION['user_type'];
		$i=0;
		if(count($get_logcontent) > 0)
		{
			foreach($get_logcontent as $details)
			{
				$i++;
				if($details['booking_logid'] == 0)
				{
					$class = 'user_info_icon';
				}		
				else
				{
					$class = 'booking_icon';
				}
				if($usertype  == 'A')
				{
					$output .='<li class="show_logs_li"><div class="show_tag"><div class="show_timer"><time class="age" datetime="'.$details['log_createdate'].'"></time></div><div class='.$class.'></div><div class="show_logcontent">'.$details['log_message'].'</div></div></li>';
				}else{
					if($i%2){
						$class="show_logs_li_two";
					}else{
						$class="show_logs_li_one";
					}
					$output .='<li class="show_logs_li '.$class.'"><div class="show_tag"><div class="show_timer"><time class="age" datetime="'.$details['log_createdate'].'"></time></div><div class='.$class.'></div><div class="show_logcontent">'.$details['log_message'].'</div></div></li>';
				}

			}
		}
		else
		{
			$output .='<li class="show_logs_li"><div class="show_timer"></div><div class="show_logcontent">'.__('no_data').'</div></div></li>';
		}
	
		echo $output;exit;
	}


	public function action_accounts()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/dashboard");
	   	}
	
		//Page Title
		$this->page_title =  __('accounts');
		$this->selected_page_title = __('accounts');
		
		$tdispatch_model = Model::factory('tdispatch');

		$count_accounts = $tdispatch_model->count_accounts();
		
			//pagination loads here
		//-------------------------
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
			'current_page'   => array('source' => 'query_string','key' => 'page'),
			'items_per_page' => REC_PER_PAGE,
			'total_items'    => $count_accounts,
			'view' => 'pagination/punbb',
		));
   
		$all_accounts = $tdispatch_model->all_accounts($offset, REC_PER_PAGE);
		//****pagination ends here***//
		
	
		//send data to view file 
		$view= View::factory('admin/tdispatch/manage_accounts')
				->bind('all_accounts',$all_accounts)
				->bind('pag_data',$pag_data)
				->bind('Offset',$offset);


		$this->template->title= SITENAME." | ".__('accounts');
		$this->template->page_title= __('accounts'); 
		$this->template->content = $view;				
	}

	public function action_add_accounts()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$tdispatch_model = Model::factory('tdispatch');
		/**To get the form submit button name**/
		$addaccounts_submit =arr::get($_REQUEST,'submit_addaccounts'); 
		$errors = array();
		$post_values = array();
		
		if ($addaccounts_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			$post = Arr::map('trim', $this->request->post());

			$validator = $tdispatch_model->validate_addaccounts(arr::extract($post,array('account_name','account_limit','account_discount','firstname','email','phone')));

			if($validator->check())
			{
				$app_name = $this->app_name;
				$siteemail = $this->siteemail;
				$random_key = text::random($type = 'alnum', $length = 10);
				$send_mail=$tdispatch_model->addaccounts($post,$random_key);

				if($send_mail == 'S')
				{
					/** Mail to new user **/
					$mail="";			
					$link = URL_BASE.'passengers/activateaccount/?key='.$random_key;					
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$app_name,REPLACE_NAME=>ucfirst($_POST['firstname']),REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ACTLINK=>$link,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'customer_activation.html',$replace_variables);

					$to = $_POST['email'];
					$from = $this->siteemail;
					$subject = __('activation_subject');	
					$redirect = "tdispatch/accounts";	
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
					$message_details = $common_model->sms_message('1');
					$to = $_POST['phone'];
					$message = $message_details[0]['sms_description'];
					$message = str_replace("##SITE_NAME##",SITE_NAME,$message);

					//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");

					}
					/** Mail to new user **/
				}
			/* Create Log */		
			$company_id = $_SESSION['company_id'];			
			$log_message = __('log_message_account_added');
			$log_message = str_replace("ACCOUNTNAME",$post['account_name'],$log_message); 
			$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
			?>
			<script type="text/javascript">load_logcontent();</script>
			<?php 
			/* Create Log */	

				Message::success(__('sucessfull_added_account_location'));
				$this->request->redirect("tdispatch/accounts");

			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view= View::factory('admin/tdispatch/add_accounts')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_accounts');
		$this->template->page_title= __('add_accounts'); 
		$this->template->content = $view;
	}

	public function action_edit_accounts()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$tdispatch_model = Model::factory('tdispatch');
		/**To get the form submit button name**/
		$editaccount_submit =arr::get($_REQUEST,'submit_editaccount'); 
		$aid = $this->request->param('id');
		$account_details = $tdispatch_model->account_details($aid);
		$account_groupdetails = $tdispatch_model->account_groupdetails($aid);
		$account_userdetails = $tdispatch_model->account_userdetails($aid);
	
		if(count($account_details) == 0)
		{
			Message::success(__('invalid_accounts'));
			$this->request->redirect("tdispatch/accounts");
		}

		$errors = array();
		$post_values = array();
		
		if ($editaccount_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			$post = Arr::map('trim', $this->request->post());

			$validator = $tdispatch_model->validate_editaccounts(arr::extract($post,array('account_name','account_limit','account_discount','firstname','email','phone')),$aid);

			if($validator->check())
			{
			if($account_details[0]['limit'] > $post['account_limit'])
			{
				Message::error(__('account_limit_not_updated'));
				$this->request->redirect("tdispatch/accounts");
			}
			else
			{
				$app_name = $this->app_name;
				$siteemail = $this->siteemail;
				$random_key = text::random($type = 'alnum', $length = 10);
				$req_result = $tdispatch_model->editaccount($aid,$post,$random_key);

				if($req_result['send_mail'] == 'S')
				{
					/** Mail to new user **/
					$mail="";			
					$link = URL_BASE.'passengers/activateaccount/?key='.$random_key;					
					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$app_name,REPLACE_NAME=>ucfirst($_POST['firstname']),REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ACTLINK=>$link,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'customer_activation.html',$replace_variables);


					$to = $_POST['email'];
					$from = $this->siteemail;
					$subject = __('activation_subject');	
					$redirect = "tdispatch/accounts";	
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
					$message_details = $common_model->sms_message('1');
					$to = $_POST['phone'];
					$message = $message_details[0]['sms_description'];
					$message = str_replace("##SITE_NAME##",SITE_NAME,$message);

					//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");

					}
					/** Mail to new user **/
				}  
				/* Create Log */		
				$company_id = $_SESSION['company_id'];			
				$log_message = __('log_message_account_updated');
				$log_message = str_replace("ACCOUNTNAME",$post['account_name'],$log_message); 
				$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
				?>
				<script type="text/javascript">load_logcontent();</script>
				<?php 
				/* Create Log */
				   
					if($req_result['status'] == 1) 
					{
						Message::success(__('sucessfull_update_account_location'));
						$this->request->redirect("tdispatch/accounts");
					}
					else
					{
						Message::error(__('not_updated'));
						$this->request->redirect("tdispatch/accounts");
					}
					
	
				} 
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view= View::factory('admin/tdispatch/edit_accounts')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values)
						->bind('account_details',$account_details)
						->bind('account_groupdetails',$account_groupdetails)
						->bind('account_userdetails',$account_userdetails);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('edit_accounts');
		$this->template->page_title= __('edit_accounts'); 
		$this->template->content = $view;

	}
	
	/** block account list**/
	public function action_block_account_request()
	{
		$this->is_login();
		$tdispatch_model = Model::factory('tdispatch');
		$status=$tdispatch_model->block_account_request($_REQUEST['uniqueId']);

		$pagedata=explode("/",$_SERVER["REQUEST_URI"]);
		$page=isset($pagedata[3])?$pagedata[3]:'';
		
		//Flash message for Reject
		//==========================
		Message::success(__('Checked requests have been changed to blocked status.'));
		
		//redirects to job_feedback details page after deletion
		$this->request->redirect("tdispatch/accounts");	 //transaction/index

	}
	
	/** actvie account list**/
	public function action_active_account_request()
	{
		$this->is_login(); 
		$tdispatch_model = Model::factory('tdispatch');
		$status = $tdispatch_model->active_account_request($_REQUEST['uniqueId']);
		
		$pagedata=explode("/",$_SERVER["REQUEST_URI"]);
		$page=isset($pagedata[3])?$pagedata[3]:'';
    
		//Flash message for Reject
		//==========================
		Message::success(__('Checked requests have been changed to activated status.'));

		
		//redirects to job_feedback details page after deletion
		$this->request->redirect("tdispatch/accounts");	 //transaction/index

	}

	public function action_delete_accounts()
	{
		$user_createdby = $_SESSION['userid'];
		$aid = $this->request->param('id');
		$tdispatch_model = Model::factory('tdispatch');
		$account_details = $tdispatch_model->account_details($aid);
		$status = $tdispatch_model->delete_accounts($aid);

		/* Create Log */		
		$company_id = $_SESSION['company_id'];			
		$log_message = __('log_message_account_deleted');
		$log_message = str_replace("ACCOUNTNAME",$account_details[0]['account_name'],$log_message); 
		$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
		?>
		<script type="text/javascript">load_logcontent();</script>
		<?php 
		/* Create Log */

		if($status){
		Message::success(__('sucessfull_delete_account_location'));
		$this->request->redirect("tdispatch/accounts");
		}
	}
	
	/* Add Group */
	public function action_add_groups()
	{
		$acc_id = $this->request->param('id');
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
		$tdispatch_model = Model::factory('tdispatch');
		$limits = $tdispatch_model->checkgroupreachlimit($acc_id);
		$get_account_limit = $tdispatch_model->get_account_limit($acc_id);
		$account_limit = $get_account_limit[0]['limit'];
	   	if($get_account_limit[0]['limit'] != 0 && $limits == 0)
	   	{
			Message::error(__('account_limit_reached'));
			$this->request->redirect("tdispatch/edit_accounts/".$acc_id);
	   	}
		/**To get the form submit button name**/
		$addaccounts_submit =arr::get($_REQUEST,'submit_addgroups'); 
		$errors = array();
		$post_values = array();
		
		if ($addaccounts_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			$post = Arr::map('trim', $this->request->post());

			$validator = $tdispatch_model->validate_addgroups(arr::extract($post,array('acc_id','group_name','limit')));

			if($validator->check())
			{
			   $added=$tdispatch_model->addgroups($post);
			/* Create Log */		
			$company_id = $_SESSION['company_id'];			
			$log_message = __('log_message_group_added');
			$log_message = str_replace("GROUPNAME",$post['group_name'],$log_message); 
			$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
			?>
			<script type="text/javascript">load_logcontent();</script>
			<?php 
			/* Create Log */	

			   
				if($added == 1) 
				{ 
					Message::success(__('sucessfull_added_group_location'));
					$this->request->redirect("tdispatch/edit_accounts/".$acc_id);
				}	
			     
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view= View::factory('admin/tdispatch/add_groups')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('account_id',$acc_id)
				                ->bind('account_limit',$account_limit)
				                ->bind('postvalue',$post_values);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_group');
		$this->template->page_title= __('add_group'); 
		$this->template->content = $view;
	}
	
	public function action_edit_groups()
	{
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$tdispatch_model = Model::factory('tdispatch');
		/**To get the form submit button name**/
		$editaccount_submit =arr::get($_REQUEST,'submit_editgroups'); 
		$groupid = $this->request->param('id');		
		$groupdetails = $tdispatch_model->groupdetails($groupid);
		$gr_account_id = $groupdetails[0]['aid'];
		$get_account_limit = $tdispatch_model->get_account_limit($gr_account_id);
		if(count($groupdetails) == 0)
		{
			Message::success(__('invalid_accounts'));
			$this->request->redirect("tdispatch/accounts");
		}

		$errors = array();
		$post_values = array();
		
		if ($editaccount_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;
			$accountid = $_POST['acc_id'];
			$post = Arr::map('trim', $this->request->post());

			$validator = $tdispatch_model->validate_editgroups(arr::extract($post,array('acc_id','group_name','limit')),$groupid);

			if($validator->check())
			{
				if($post['limit'] < $groupdetails[0]['limit'])
				{
					Message::error(__('group_limit_not_updated'));
					$this->request->redirect("tdispatch/edit_accounts/".$accountid);
				}
				else
				{
					$status=$tdispatch_model->editgroup($groupid,$post);

					/* Create Log */		
					$company_id = $_SESSION['company_id'];			
					$log_message = __('log_message_group_updated');
					$log_message = str_replace("GROUPNAME",$post['group_name'],$log_message); 
					$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
					?>
					<script type="text/javascript">load_logcontent();</script>
					<?php 
					/* Create Log */
					   
						if($status == 1) 
						{ 
							Message::success(__('sucessfull_update_group_location'));
							$this->request->redirect("tdispatch/edit_accounts/".$accountid);
						}
						else
						{
							Message::error(__('not_updated'));
							$this->request->redirect("tdispatch/edit_accounts/".$accountid);
						}
				}
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}

		$view= View::factory('admin/tdispatch/edit_groups')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('postvalue',$post_values)
				                ->bind('get_account_limit',$get_account_limit)
						->bind('groupdetails',$groupdetails);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('edit_group');
		$this->template->page_title= __('edit_group'); 
		$this->template->content = $view;
	}
	
	public function action_delete_groups()
	{
		$user_createdby = $_SESSION['userid'];
		$gid = $this->request->param('id');
		$tdispatch_model = Model::factory('tdispatch');
		$group_details = $tdispatch_model->groupdetails($gid);
		$accnt_id = $group_details[0]['aid'];
		$status = $tdispatch_model->delete_groups($gid);

		/* Create Log */		
		$company_id = $_SESSION['company_id'];			
		$log_message = __('log_message_group_deleted');
		$log_message = str_replace("GROUPNAME",$group_details[0]['department'],$log_message); 
		$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
		?>
		<script type="text/javascript">load_logcontent();</script>
		<?php 
		/* Create Log */

		if($status){
		Message::success(__('sucessfull_delete_group_location'));
		$this->request->redirect("tdispatch/edit_accounts/".$accnt_id);
		}
	}
	
	// Add user account */ 
	public function action_add_users()
	{
		$accountid = $this->request->param('id');
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		$tdispatch_model = Model::factory('tdispatch');
		$getgroup_details = $tdispatch_model->getgroup_details_for_user($accountid);
		/**To get the form submit button name**/
		$addaccounts_submit =arr::get($_REQUEST,'submit_addusers'); 
		$errors = array();
		$post_values = array();
		
		if ($addaccounts_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			$post = Arr::map('trim', $this->request->post());

			$validator = $tdispatch_model->validate_addusers(arr::extract($post,array('firstname','email','phone','group_name')));

			if($validator->check())
			{

			   $random_key = text::random($type = 'alnum', $length = 10);
			   $req_result = $tdispatch_model->addusers($post,$random_key);
			   $added = $req_result['result'];	
			   $send_mail = $req_result['send_mail'];


			/* Create Log */		
			$company_id = $_SESSION['company_id'];			
			$log_message = __('log_message_user_added');
			$log_message = str_replace("USER",$post['firstname'],$log_message); 
			$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
			?>
			<script type="text/javascript">load_logcontent();</script>
			<?php 
			/* Create Log */


					if($send_mail == 'S') {
					/** Mail to new User **/
						$mail="";			
						$link = URL_BASE.'passengers/activateaccount/?key='.$random_key;					
						$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_NAME=>ucfirst($_POST['firstname']),REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ACTLINK=>$link,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
						$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'customer_activation.html',$replace_variables);


						$to = $_POST['email'];
						$from = $this->siteemail;
						$subject = __('activation_subject');	
						$redirect = "tdispatch/accounts";	
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
						$message_details = $common_model->sms_message('1');
						$to = $_POST['phone'];
						$message = $message_details[0]['sms_description'];
						$message = str_replace("##SITE_NAME##",SITE_NAME,$message);

						//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");

						}
						/** Mail to new User **/
					}
	
				if($added == 1) 
				{ 
					Message::success(__('sucessfull_added_user_location'));

					$this->request->redirect("tdispatch/edit_accounts/".$accountid);
				}
				else
				{
					Message::error(__('not_updated'));
					$this->request->redirect("tdispatch/edit_accounts/".$accountid);
				}
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
		$view= View::factory('admin/tdispatch/add_users')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('accountid',$accountid)
				                ->bind('getgroup_details',$getgroup_details)
				                ->bind('postvalue',$post_values);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_users');
		$this->template->page_title= __('add_users'); 
		$this->template->content = $view;
	}

	/** Change Driver status **/
	public function action_changedriver_status()
	{

		if(isset($_REQUEST["value"]))
		{
			$passengers_model = Model::factory('passengers');					
			$common_model = Model::factory('commonmodel');
			$tdispatch_model = Model::factory('tdispatch');					

			$driver_status = $passengers_model->get_driver_request($_REQUEST["value"]);

			$driver_name = $common_model->get_passengerlogdetails($_REQUEST["value"]);

			$driver_reply = $driver_status[0]['driver_reply'];
			if($driver_reply == 'A')
			{

				$change_driver_status = $passengers_model->change_driver_status($_REQUEST["value"],'A');

				?>
				<script type="text/javascript">load_logcontent();</script>
				<?php

				echo __("confirmed");
				exit;
			}
			else if($driver_reply == 'R')
			{

				$change_driver_status = $passengers_model->change_driver_status($_REQUEST["value"],'R');
				?>
				<script type="text/javascript">load_logcontent();</script>
				<?php

				echo __("incoming");
				exit;
			}
			else
			{

				/* Create Log */		
				$company_id = $_SESSION['company_id'];	
				$user_createdby = $_SESSION['userid'];				
				
				$log_message = __('log_message_driver_missed');
				$log_message = str_replace("TRIPID",$_REQUEST["value"],$log_message); 
				$log_message = str_replace("DRIVERNAME",$driver_name,$log_message); 
				$log_booking = __('log_booking_driver_missed');
				$log_booking = str_replace("DRIVERNAME",$driver_name,$log_booking);

				$log_status = $tdispatch_model->create_logs($_REQUEST["value"],$company_id,$user_createdby,$log_message,$log_booking);
				/* Create Log */


				$change_driver_status = $passengers_model->change_driver_status($_REQUEST["value"],'C');
				?>
				<script type="text/javascript">load_logcontent();</script>			
				<?php
				echo __("missed");
				exit;
			}
		}
	}


	public function action_getdriver_status()
	{
		$timeout = 55; // timeout in seconds
		$now = time(); // start timeexit;

		if(isset($_POST["value"]))
		{
			$passengers_model = Model::factory('passengers');


				while((time() - $now) < $timeout)
				{
					$driver_status = $passengers_model->get_driver_request($_POST["value"]);
					$driver_reply = $driver_status[0]['driver_reply'];
					$time_to_reach = $driver_status[0]['time_to_reach_passen'];
					if(!empty($driver_reply))
					{
						if($driver_reply == 'A')
						{
							$result = __("confirmed");							
						}
						else if($driver_reply == 'R')
						{
							$result = __("incoming");
						}
						echo $result;
						exit;
						break;
					}
					usleep(50000);

				}
		}
	}

	
	/** Manage Tdispatch settings **/
        public function action_tdispatch_settings()
        {
		$this->is_login(); 
		$usertype = $_SESSION['user_type'];
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
        $settings = Model::factory('tdispatch');
        $errors = array();
		$submit_settings =arr::get($_REQUEST,'submit_settings'); 
		$errors = array();
		$post_values = array();
		
		if ($submit_settings && Validation::factory($this->request->post()) ) 
		{
			//$post_values = $_POST;
			$post = Arr::map('trim', $this->request->post());
			
			$validator = $settings->validate_dispatchsetting(arr::extract($post,array('labelname')));
			if($validator->check())
			{
				
			   $status = $settings->update_dispatchsetting($post);
				//echo $status;exit;
				if($status == 1)
				{
					Message::success(__('sucessful_settings_update'));
				}
				else
				{
					Message::error(__('not_updated'));
				}
				$this->request->redirect("tdispatch/tdispatch_settings");
			}
			else
			{
				$errors = $validator->errors('errors');
				//print_r($errors); exit;
			}
		}

                $tdispatch_settings = $settings->tdispatch_settings();
                $this->selected_page_title = __("site_settings");
                $view= View::factory('admin/tdispatch/manage_tdispatch_settings')
                ->bind('postvalue',$post_values)
                ->bind('errors', $errors)
                ->bind('tdispatch_settings', $tdispatch_settings);
                
    
		$this->template->title= SITENAME." | ".__('tdispatch_setting');
		$this->template->page_title= __('tdispatch_setting'); 	             
		$this->template->content = $view;

        }

	// Edit user account */ 
	public function action_edit_users()
	{
		$split_id = $this->request->param('id');
		$split_list = explode('_',$split_id);
		$accountid = isset($split_list[0])?$split_list[0]:'';
		$groupid = isset($split_list[1])?$split_list[1]:'';
		$userid = isset($split_list[2])?$split_list[2]:'';

		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];	
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/login");
	   	}
	   	
		if($accountid =='' && $groupid =='' && $userid =='')	
		{
			$this->request->redirect("tdispatch/accounts");
		}

		$tdispatch_model = Model::factory('tdispatch');
		$getgroup_details = $tdispatch_model->getgroup_details_for_user($accountid);
		$user_details = $tdispatch_model->getgroupuser_details($userid);

		if(count($getgroup_details) == 0)	
		{
			$this->request->redirect("tdispatch/accounts");
		}


		/**To get the form submit button name**/
		$addaccounts_submit =arr::get($_REQUEST,'submit_addusers'); 
		$errors = array();
		$post_values = array();
		
		if ($addaccounts_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			$post = Arr::map('trim', $this->request->post());

			$validator = $tdispatch_model->validate_addusers(arr::extract($post,array('firstname','email','phone','group_name')));

			if($validator->check())
			{
			   $random_key = text::random($type = 'alnum', $length = 10);
			   $req_result = $tdispatch_model->editusers($post,$random_key,$userid);
			   $added = $req_result['result'];	
			   $send_mail = $req_result['send_mail'];	


			/* Create Log */		
			$company_id = $_SESSION['company_id'];			
			$log_message = __('log_message_user_added');
			$log_message = str_replace("USER",$post['firstname'],$log_message); 
			$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
			?>
			<script type="text/javascript">load_logcontent();</script>
			<?php 
			/* Create Log */	


					if($send_mail == 'S') {
					/** Mail to new User **/
						$mail="";			
						$link = URL_BASE.'passengers/activateaccount/?key='.$random_key;					
						$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_NAME=>ucfirst($_POST['firstname']),REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ACTLINK=>$link,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
						$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'customer_activation.html',$replace_variables);


						$to = $_POST['email'];
						$from = $this->siteemail;
						$subject = __('activation_subject');	
						$redirect = "tdispatch/accounts";	
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
						$message_details = $common_model->sms_message('1');
						$to = $_POST['phone'];
						$message = $message_details[0]['sms_description'];
						$message = str_replace("##SITE_NAME##",SITE_NAME,$message);

						//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");

						}
						/** Mail to new User **/
					}


				if($added == 1) 
				{ 
					Message::success(__('sucessfull_added_user_location'));

					$this->request->redirect("tdispatch/edit_accounts/".$accountid);
				}
				else
				{
					Message::error(__('not_updated'));
					$this->request->redirect("tdispatch/edit_accounts/".$accountid);
				}
			}
			else
			{
				$errors = $validator->errors('errors');
			}
		}
		$view= View::factory('admin/tdispatch/edit_users')
				                ->bind('validator', $validator)
				                ->bind('errors', $errors)
				                ->bind('accountid',$accountid)
				                ->bind('getgroup_details',$getgroup_details)
						->bind('user_details',$user_details)
				                ->bind('postvalue',$post_values);
		                $this->template->content = $view;	
		                
		$this->template->title= SITENAME." | ".__('add_users');
		$this->template->page_title= __('add_users'); 
		$this->template->content = $view;
	}

	/** Select Group ID **/
	public function action_getgrouplist()
	{
		$tdispatch_model = Model::factory('tdispatch');
		$output ='';
		$userid =arr::get($_REQUEST,'userid'); 

		$getgroup_details = $tdispatch_model->getgrouplist($userid);

		if(isset($userid))
		{
				
			$count=count($getgroup_details);
			if($count>0)
			{
				$output .='<select name="group_id" id="group_id" title="'.__('select_group').'" >
					   <option value="">'.__('select_group').'</option>';

					foreach($getgroup_details as $grouplist) { 
						$output .='<option value="'.$grouplist["gid"].'">'.$grouplist["department"].'</option>';
					}
	
				$output .='</select>';
				
			}
   

		}
			echo $output;exit;
			
	}

	public function action_geteditgrouplist()
	{
		$tdispatch_model = Model::factory('tdispatch');
		$output ='';
		$userid =arr::get($_REQUEST,'userid'); 
		$groupid =arr::get($_REQUEST,'groupid'); 

		$getgroup_details = $tdispatch_model->getgrouplist($userid);

		if(isset($userid))
		{
				
			$count=count($getgroup_details);
			if($count>0)
			{
				$output .='<select name="group_id" id="group_id" class="required" title="'.__('select_group').'" >
					   <option value="">'.__('select_group').'</option>';

					foreach($getgroup_details as $grouplist) { 
						$output .='<option value="'.$grouplist["gid"].'"';
						 if($groupid == $grouplist["gid"]) { $output .='selected=selected'; }  $output .='>'.$grouplist["department"].'</option>';
					}
	
				$output .='</select>';
				
			}
   

		}
			echo $output;exit;
			
	}


	public function action_delete_groupuser()
	{
		$user_createdby = $_SESSION['userid'];

		$split_id = $this->request->param('id');
		$split_list = explode('_',$split_id);

		$accountid = isset($split_list[0])?$split_list[0]:'';
		$groupid = isset($split_list[1])?$split_list[1]:'';
		$userid = isset($split_list[2])?$split_list[2]:'';

		$tdispatch_model = Model::factory('tdispatch');
		$getgroupuser_details = $tdispatch_model->getgroupuser_details($userid);
		$getgroup_details = groupdetails($groupid);
		$status = $tdispatch_model->delete_groupsusers($groupid,$userid);


		/* Create Log */		
		$company_id = $_SESSION['company_id'];			
		$log_message = __('log_message_groupuser_deleted');
		$log_message = str_replace("GROUPUSERNAME",$getgroupuser_details[0]['name'],$log_message); 
		$log_message = str_replace("GROUPNAME",$getgroup_details[0]['department'],$log_message); 

		$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
		?>
		<script type="text/javascript">load_logcontent();</script>
		<?php 
		/* Create Log */

		if($status){
		Message::success(__('sucessfull_delete_group_user'));
		$this->request->redirect("tdispatch/edit_accounts/".$accountid);
		}
	}

	public function action_recurrentbooking()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/dashboard");
	   	}
	
		//Page Title
		$this->page_title =  __('recurrent_booking');
		$this->selected_page_title = __('recurrent_booking');
		
		$tdispatch_model = Model::factory('tdispatch');

		$count_recurrent_booking = $tdispatch_model->count_recurrent_booking();
		
			//pagination loads here
		//-------------------------
		$page_no= isset($_GET['page'])?$_GET['page']:0;

		if($page_no==0 || $page_no=='index')
		$page_no = PAGE_NO;
		$offset = REC_PER_PAGE*($page_no-1);

		$pag_data = Pagination::factory(array (
			'current_page'   => array('source' => 'query_string','key' => 'page'),
			'items_per_page' => REC_PER_PAGE,
			'total_items'    => $count_recurrent_booking,
			'view' => 'pagination/punbb',
		));
   
		$all_recurrent_booking = $tdispatch_model->all_recurrent_booking($offset, REC_PER_PAGE);
		//****pagination ends here***//
		
	
		//send data to view file 
		$view= View::factory('admin/tdispatch/manage_recurrent_booking')
				->bind('all_recurrent_booking',$all_recurrent_booking)
				->bind('pag_data',$pag_data)
				->bind('Offset',$offset);


		$this->template->title= SITENAME." | ".__('recurrent_booking');
		$this->template->page_title= __('recurrent_booking'); 
		$this->template->content = $view;				
	}


	public function action_edit_recurrent_booking()
	{
	
		$user_createdby = $_SESSION['userid'];
		$usertype = $_SESSION['user_type'];
		if($usertype =='A' && $usertype =='S')
	   	{
	   		$this->request->redirect("admin/dashboard");
	   	}
	
		//Page Title
		$post_values = array();
		$errors = array();
	
		$this->page_title =  __('recurrent_booking');
		$this->selected_page_title = __('recurrent_booking');
		
		$add_model = Model::factory('add');
		$tdispatch_model = Model::factory('tdispatch');
		$common_model = Model::factory('commonmodel');	

		$company_id = $_SESSION['company_id'];
		$reid = $this->request->param('id');

		$company_tax = $common_model->company_tax($company_id);		 
		$company_timezone = $common_model->company_timezone($company_id);	

		$count_recurrent_booking = $tdispatch_model->count_recurrent_booking();
		$model_details = $tdispatch_model->model_details();
		$additional_fields = $add_model->taxi_additionalfields();
		$getgroup_details = $tdispatch_model->getgroup_details_bycompany($company_id);
		$get_suggestedlocation = $tdispatch_model->get_suggestedlocation();
		$get_suggestedjourney = $tdispatch_model->get_suggestedjourney();
		$get_gatewaydetails = $tdispatch_model->get_gatewaydetails();

		$get_recurrent_booking = $tdispatch_model->recurrent_bookingdetails($reid);
		$update_submit =arr::get($_REQUEST,'update'); 
		$delete_submit =arr::get($_REQUEST,'delete'); 

		if(count($get_recurrent_booking) == 0)
		{
			Message::success(__('invalid_data_information'));	
			$this->request->redirect("tdispatch/recurrentbooking");
		}

		if ($update_submit && Validation::factory($_POST) ) 
		{
			$post_values = $_POST;

			$random_key = text::random($type = 'alnum', $length = 10);	
			$password = text::random($type = 'alnum', $length = 6);		

			$update_recurrentbooking = $tdispatch_model->edit_recurrentbooking($_POST,$random_key,$reid,$password);

			$send_mail = $update_recurrentbooking['send_mail'];

					if($send_mail == 'S') {
					/** Mail to new User **/
						$mail="";
/*			
				$link = URL_BASE.'passengers/activateaccount/?key='.$random_key;					
				$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_NAME=>ucfirst($_POST['firstname']),REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ACTLINK=>$link);
				$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'customer_activation.html',$replace_variables);
*/

					$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>$post_values['firstname'],REPLACE_MOBILE=>$post_values['phone'],REPLACE_PASSWORD=>$password,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
					$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'passenger-register.html',$replace_variables);

						$to = $_POST['email'];
						$from = $this->siteemail;
						$subject = __('activation_subject');	
						$redirect = "tdispatch/addbooking";	
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
						$message_details = $common_model->sms_message('1');
						$to = $_POST['phone'];
						$message = $message_details[0]['sms_description'];
						$message = str_replace("##SITE_NAME##",SITE_NAME,$message);

						//$result = file_get_contents("http://s1.freesmsapi.com/messages/send?skey=b5cedd7a407366c4b4459d3509d4cebf&message=".urlencode($message)."&senderid=NAJIK&recipient=$to");

						}
						/** Mail to new User **/
					}

			// Create Log //	
			$company_id = $_SESSION['company_id'];			
			$user_createdby = $_SESSION['userid'];			
			$log_message = __('log_message_recurrent_updated');
			$log_message = str_replace("RECURRENT_ID",$reid,$log_message); 
			$log_status = $tdispatch_model->create_logs($reid,$company_id,$user_createdby,$log_message);

			?>
			<script type="text/javascript">load_logcontent();</script>
			<?php 
			// Create Log //

			Message::success(__('recurrent_booking_update'));	
			$this->request->redirect("tdispatch/recurrentbooking");					


		}		

		//send data to view file 
		$view= View::factory('admin/tdispatch/edit_recurrent_booking')
				->bind('get_recurrent_booking',$get_recurrent_booking)
				->bind('pag_data',$pag_data)
				->bind('model_details',$model_details)
				->bind('company_tax', $company_tax)
				->bind('company_timezone', $company_timezone)
				->bind('additional_fields',$additional_fields)
				->bind('get_recurrent_booking',$get_recurrent_booking)
				->bind('Offset',$offset)
				->bind('getgroup_details',$getgroup_details)
				->bind('get_suggestedlocation',$get_suggestedlocation)
				->bind('get_suggestedjourney',$get_suggestedjourney)
				->bind('get_gatewaydetails',$get_gatewaydetails)
				->bind('postvalue',$post_values);


		$this->template->title= SITENAME." | ".__('recurrent_booking');
		$this->template->page_title= __('recurrent_booking'); 
		$this->template->content = $view;				
	}


	public function action_delete_recurrentbooking()
	{
		$user_createdby = $_SESSION['userid'];
		$reid = $_REQUEST['reid'];
		$tdispatch_model = Model::factory('tdispatch');
		$location_details = $tdispatch_model->recurrent_details($reid);
		$status = $tdispatch_model->delete_recurrentbooking($reid);
		/*
		// Create Log //		
		$company_id = $_SESSION['company_id'];			
		$log_message = __('log_message_location_deleted');
		$log_message = str_replace("LOCATIONNAME",$location_details[0]['location_name'],$log_message); 
		$log_status = $tdispatch_model->create_logs('',$company_id,$user_createdby,$log_message);
		?>
		<script type="text/javascript">load_logcontent();</script>
		
		// Create Log //
		*/

		if($status){
		Message::success(__('sucessfull_delete_recurrent_booking'));
		$this->request->redirect("tdispatch/recurrentbooking");
		}
	}

	
	public function action_checkgrouplimit()
	{
		$tdispatch_model = Model::factory('tdispatch');

		$group_id = $_REQUEST['group_id'];
		$total_fare = $_REQUEST['total_fare'];

		$status = $tdispatch_model->check_grouplimit($group_id,$total_fare);
		
		echo $status;exit;
	}

	public function action_get_citymodel_fare_details()
	{
		$company_id = $_SESSION['company_id'];
		$tdispatch_model = Model::factory('tdispatch');
		$common_model = Model::factory('commonmodel');
		$total_min = $_REQUEST['total_min'];
		$taxi_fare_details = $tdispatch_model->get_citymodel_fare_details($_REQUEST['model_id'],$_REQUEST['city_name'],$_REQUEST['city_id'],$company_id);
		//echo $taxi_fare_details;exit;
		$company_tax = $common_model->company_tax($company_id);	
	 
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
			$minutes_fare = $taxi_fare_details[0]->minutes_fare;
			// Waiting Time Charge for an company					
			$total_fare = $distance = $total = 0;

		
			$distance = $_REQUEST['distance_km'];
			//echo $total_min;exit;
			//echo $base_fare."-->".$distance."-->".$min_km_range;exit;
			// Fare calculation type  - //1 => Distance, 2 => Time, 3=> Distance / Time
			$total_fare = $base_fare;
			if(FARE_CALCULATION_TYPE==1 || FARE_CALCULATION_TYPE==3)
			{
				if($distance < $min_km_range)
				{
					$total_fare = $min_fare;
				}
				else if($distance <= $below_above_km_range)
				{
					$fare = $distance * $below_km;
					//echo $distance."*".$below_km."-->".$fare."-->".$base_fare;
					$total_fare  = 	$fare + $base_fare ;
				}
				else if($distance > $below_above_km_range)
				{
					$fare = $distance * $above_km;
					$total_fare  = 	$fare + $base_fare ;
				}
			}
			if(FARE_CALCULATION_TYPE==2 || FARE_CALCULATION_TYPE==3)
			{
				/********** Minutes fare calculation ************/		
				//$total_min = str_replace('mins','',$total_min);
				$minutes = round($total_min/60);	
				//echo "-->".$minutes."-->";
				if($minutes_fare > 0)
				{
					$minutes_cost = $minutes * $minutes_fare;
					//echo $total_fare."-->".$minutes_cost;exit;
					$total_fare  = $total_fare + $minutes_cost;
				}
				/************************************************/
			}
			
			

			/*
			if($company_tax > 0)
			{
					$tax_amount = ($company_tax/100)*$total_fare;//night_charge%100;
			
					$total_fare  = 	$total_fare+$tax_amount;
			}
			*/
			/** Edited By Logeswaran
			 *  TAX Added Here Removed in script.js
			 */
			$total_fare = number_format((($total_fare * TAX / 100) + $total_fare), 2, '.', ' ');
			echo $total_fare;exit;
	}

	public function action_change_groups_status()
	{
		$this->is_login(); 
		$tdispatch_model = Model::factory('tdispatch');
		
		$status = $tdispatch_model->change_groups_status($_REQUEST);
		
		$pagedata=explode("/",$_SERVER["REQUEST_URI"]);
		$page=isset($pagedata[3])?$pagedata[3]:'';
    
		//Flash message for Reject
		//==========================
		Message::success(__('Group Status has been changed successfully !!.'));

		
		//redirects to job_feedback details page after deletion
		$this->request->redirect("tdispatch/edit_accounts/".$_REQUEST['account_id']);	 //transaction/index

	}


	public function action_check_dates()
	{
		$this->is_login(); 
		$tdispatch_model = Model::factory('tdispatch');
		
		$status = $tdispatch_model->check_dates($_REQUEST);
		
		echo $status;exit;    
	}
	public function action_edit_booking()
	{
		//echo 'as';exit;
		$pass_log_id = $_REQUEST['passenger_logid'];
		//$pass_log_id = 335;
		$add_model = Model::factory('add');
		$tdispatch_model = Model::factory('tdispatch');
		$common_model = Model::factory('commonmodel');	

		$company_id = $_SESSION['company_id'];
		$company_tax = $common_model->company_tax($company_id);		 

		$model_details = $tdispatch_model->model_details();
		$additional_fields = "";//$add_model->taxi_additionalfields();
		$field_count = "";//count($additional_fields);
		$edit_bookingdetails = $tdispatch_model->edit_bookingdetails($pass_log_id);
		$log_details = $tdispatch_model->booking_logdetails($pass_log_id);
		$getgroup_details = $tdispatch_model->getgroup_details_bycompany($company_id);
		$get_suggestedlocation = $tdispatch_model->get_suggestedlocation();
		$get_suggestedjourney = $tdispatch_model->get_suggestedjourney();


		$current_datetime = $common_model->getcompany_all_currenttimestamp($company_id);

		//$current_time = $date->format('H:i');
		//$current_date = $date->format('Y-m-d');
	
		$pickup_datetime = explode (' ',$edit_bookingdetails[0]['pickup_time']);		
		$pickup_date = $pickup_datetime[0];
		$pickup_time = $pickup_datetime[1];
		
		$specific_additional_fields = unserialize($edit_bookingdetails[0]['additional_fields']);

		/*function get_val($key_to_check, $array){
		    if(isset($array[$key_to_check])) {
			return $array[$key_to_check];
		    }
		}*/
		$view= View::factory('admin/tdispatch/edit_booking')
		      ->bind('company_tax',$company_tax)
		      ->bind('company_id',$company_id)
		      ->bind('model_details',$model_details)
		      ->bind('additional_fields',$additional_fields)
		      ->bind('edit_bookingdetails',$edit_bookingdetails)
		      ->bind('field_count',$field_count)
		      ->bind('log_details',$log_details)
		      ->bind('getgroup_details',$getgroup_details)
		      ->bind('get_suggestedlocation',$get_suggestedlocation)
		      ->bind('get_suggestedjourney',$get_suggestedjourney)
		      ->bind('current_datetime',$current_datetime)
		      ->bind('pickup_date',$pickup_date)
		      ->bind('pickup_time',$pickup_time)
		      ->bind('specific_additional_fields',$specific_additional_fields);
		$this->template->title= SITENAME." | ".__('edit_booking');
		$this->template->page_title= __('edit_booking'); 
		$this->template->content = $view;	
					
		
		echo $view;exit;
	}
}
?>
