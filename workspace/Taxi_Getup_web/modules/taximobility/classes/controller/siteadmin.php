<?php defined('SYSPATH') or die('No direct script access.');
/****************************************************************

* Contains SITE ADMIN details

* @Package: ConnectTaxi

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************************************/
 
abstract class Controller_Siteadmin extends Controller_Template {
	
	//Default variables
	public $template="admin/template";	
	public $selected_page_title;
	public $page_title;
	public $filter;
	public $status;
	public $footer_contents;
	
	/**
	****__construct()****
	*/
	public function __construct(Request $request, Response $response)
	{	
		$controller = $request->controller();
		$action = $request->action();

        if($controller=="admin"&&$action=="login")
        {
			//Message::error(__('Admin access not allowed for this domain'));
			//$request->redirect('company/login');
		}
		//Session instance
		$this->session = Session::instance();
		$this->urlredirect=Request::current();
		$companyId = $this->session->get('company_id');
		
		$password_verify = $this->session->get('password_verify');
		
		if($password_verify =="" || $password_verify == null || $password_verify !=1)
		{
			$this->all_logout();
		}
		
	/*	if(!isset($this->session) || (!$this->session->get('userid'))  )
		{
			Message::error(__('login_access'));
			$this->request->redirect("/admin/login/");
		}*/
		
		//Include defined Constants files
		//require Kohana::find_file('classes','table_config');
		//require Kohana::find_file('classes','common_config');
		$this->commonmodel = $commonmodel = Model::factory('commonmodel');
		if ( file_exists( DOCROOT . 'application/classes/common_config.php' ) ) {
            require Kohana::find_file( 'classes', 'common_config' );
        }
        
		//exit();
		//Models declaration
		$this->authorize=Model::factory('authorize');

		//$this->siteusers=Model::factory('siteusers');
		//$this->commonmodel=Model::factory('commonmodel');
		$this->managemodel=Model::factory('managemodel');
		$add_model = Model::factory('add');

		$this->site=Model::factory('site');
		$this->emailtemplate=Model::factory('emailtemplate');
		
		$this->lang =$this->session->get('lang');
		if($this->lang !=""){
			$lang=$this->lang;
		}else{
			$lang="en";
		}
		$this->currlang=I18n::lang($lang);

		$this->javascript_language=json_encode(I18n::load($this->currlang));
		View::bind_global('js_language',$this->javascript_language);

		/** get location **/
		if($this->session->get('userid') != "") {
			$location= $this->commonmodel->company_location($companyId);
			//print_r($location);exit;
			$country_company = $state_company = $city_company = 0;
			if(count($location) > 0) {
				$country_company = $location[0]['login_country'];
				$state_company = $location[0]['login_state'];
				$city_company = $location[0]['login_city'];
			}
			View::bind_global('country_company', $country_company);
			View::bind_global('state_company', $state_company);
			View::bind_global('city_company', $city_company);
		}
		$this->usertype=$this->session->get('user_type');
		$this->username=$this->session->get('username');
		$this->firstname=$this->session->get('first_name');
		$this->adminid=$this->session->get('userid');
		/* if(!empty($this->usertype) && ($this->usertype == 'M' || $this->usertype == 'C')) {
			$cid = $this->session->get('company_id');
			$check_result = $add_model->check_package_expiry($cid);
			if($check_result == 0) {
				if($this->usertype == 'C') {
					$returnUrl = "company/login?type=expire";
				} else {
					$returnUrl = "manager/login?type=expire";
				}
				$this->session->destroy();
				Cookie::delete('userid');
				$this->urlredirect->redirect($returnUrl);
			}
		} */
		//Filter type
		View::bind_global('filter', $this->allfilter);
		//Get cookie values if cookie is set and apply to session variables
		Cookie::$salt='userid';
		$cookie=Cookie::get('userid');
		if($cookie)
		{
			$this->session->set("userid",$cookie);
			$user_details=$this->authorize->select_user_details_by_id($cookie);
			if(count($user_details)>0){
				$this->session->set("user_type",$user_details[0]['user_type']);
				$this->session->set("email",$user_details[0]['email']);
			}
		}
	
		if(isset($_SESSION) && array_key_exists('usertype', $_SESSION) && $_SESSION['usertype'] != ADMIN){

			//if not admin redirect to home page (front end)
			$this->urlredirect->redirect('/');		
		}
		
		$this->userid=$this->session->get("userid");
		$this->currenttimestamp=$this->currenttimestamp();
		
		//Css & Script include for admin
		/**To Define path for selected theme**/
		define("ADMINIMGPATH",URL_BASE.'public/admin/images/');
		define("CSSADMIN",URL_BASE.'public/admin/');
		define("ADMINCSSPATH",CSSADMIN.'css/');		
		
		$adminstyles = array(ADMINCSSPATH.'admin_style.css' =>'screen',ADMINCSSPATH.'jquery-ui-1.8.11.custom.css'=>'screen');
		$adminscripts = array(SCRIPTPATH.'jquery-1.4.3.min.js');	

	
 		View::bind_global('adminstyles', $adminstyles);
		View::bind_global('adminscripts', $adminscripts);

		
		
		//Users Themes
		define("THEME","default/");
		define("USERVIEW","themes/".THEME);		
		
		define("CSSPATH","public/".THEME."css/");
		//DEFINE("EMAILTEMPLATELOGO",URL_BASE.PUBLIC_FOLDER_IMGPATH.'/logo.png'); //IMAGESPATH
		
    
		
		$userstyles = array(CSSPATH.'layout.css' =>'screen',
							); //CSSPATH.'mobile_slider/skin.css' =>'screen'
							
		$userscripts = array(SCRIPTPATH.'jquery.jcarousel.pack.js',
							SCRIPTPATH.'jquery-1.4.2.min.js');	
		
				
		View::bind_global('styles', $userstyles);
		View::bind_global('scripts', $userscripts);		
	 			

		//For Filter Defining
		//=====================
		$this->allfilter=array("C"=>"Company Owner","D" =>"Taxi Driver","M" => "Company Manager","S"=>"Moderator");
		$this->paymentModeArr = array("L"=>"Live","T" =>"Test");
	
		//For status Defining
		//=====================
		
		$this->allstatus=array("A"=>"Active","D"=>"Block","T"=>"Trash");
								
		//$this->app_name=$this->commonmodel->select_site_settings('app_name',SITEINFO);
		//$this->currencysymbol=$this->commonmodel->select_site_settings('currency_symbol',SITEINFO);
		//$this->siteemail=$this->commonmodel->select_site_settings('email_id',SITEINFO);
		//print_r($this->siteemail);
		
		if($this->session->get('userid') != "") {
			$getcountry_currecny_details = $this->commonmodel->getcountry_currecny_details();
			if(count($getcountry_currecny_details) > 0)
			{
				$curr_codes = $curr_symbol = array();
				foreach($getcountry_currecny_details as $ccd) {
					$crcode = $ccd['currency_code'];
					$crsymbol = $ccd['currency_symbol'];
					$curr_codes[] = $crcode;
					$curr_symbol[] = $crsymbol;
				}
			}
			else
			{
				$curr_codes[] = CURRENCY_FORMAT;
				$curr_symbol[] = CURRENCY;
			}
			$currencycoderesult = array_combine($curr_codes, $curr_codes);
			$this->all_currency_code = $currencycoderesult;
			$currencysymbolresult = array_combine($curr_symbol, $curr_symbol);
			$this->currencysymbol = $currencysymbolresult;
		}

		//DEFINE("SITENAME",$this->app_name);

		//binding variables to views
		$this->app_name = preg_replace("/#?[a-z0-9]+;/i","",SITENAME);
		$this->siteemail = SITE_EMAILID;
		View::bind_global('app_name',$this->app_name);
		View::bind_global('currencysymbol',$this->currencysymbol);
		View::bind_global('siteemail',$this->siteemail);

		View::bind_global('usertype',$this->usertype);
		View::bind_global('username',$this->username);
		View::bind_global('first_name',$this->firstname);
		View::bind_global('adminid',$this->adminid);	


		View::bind_global('currency_code',$this->all_currency_code); 	                                                                         
		//View::bind_global('currency_symbol',$this->currency_symbol);            

		View::bind_global('action', $action);
		View::bind_global('controller', $controller);
		View::bind_global('selected_page_title', $this->selected_page_title);
		View::bind_global('paymentModeArr', $this->paymentModeArr);

		//status to all views
		View::bind_global('status', $this->allstatus);


		$this->meta_description=''; //
		$this->meta_keywords=''; //
		$this->title=''; //

		View::bind_global('meta_description',$this->meta_description);
		View::bind_global('page_title',$this->page_title);
		View::bind_global('meta_keywords',$this->meta_keywords);
		
		$siteusers = Model::factory('siteusers');
		/** for footer info **/
		//$footer_contents = $siteusers->footer_contents();
		//print_r($footer_contents); exit;
		$footer_contents['site_favicon'] = SITE_FAVICON;
		$footer_contents['site_copyrights'] = SITE_COPYRIGHT;
		View::bind_global('footer_contents',$footer_contents);		
		
		$userid = $this->session->get('userid');
		$usrid = $userid; //isset($userid)?$userid:$id;

		//$tag_len = $this->siteusers->get_tag_count($usrid);     
		//View::bind_global('tag_len_count', $tag_len[0]['smart_tags']);


		//Assign the request to the controller
		$this->request = $request;

		//Assign a response to the controller
		$this->response = $response;	
		
	}//End of construct method

	/**
	 *****encode()****
	 * @return encoded API Result
	 */	
	public function encode($result)
	{
		return json_encode($result);
	}

	/**
	 *****decode()****
	 * @return decoded API Result
	 */	
	public function decode($result)
	{
		$result=json_decode($result);
		$version=explode(".",phpversion());
		if($version[0]>5)
		{
			switch(json_last_error())
			{
				case JSON_ERROR_NONE:
					$error="";
					break;
				case JSON_ERROR_DEPTH:
					$error="Maximum stack depth exceeded";
					break;
				case JSON_ERROR_STATE_MISMATCH:
					$error="Underflow or the modes mismatch";
					break;
				case JSON_ERROR_CTRL_CHAR:
					$error="Unexpected control character found";
					break;
				case JSON_ERROR_SYNTAX:
					$error="Syntax error, malformed JSON";
					break;
				case JSON_ERROR_UTF8:
					$error="Malformed UTF-8 characters, possibly incorrectly encoded";
					break;
				default:
					$error="";
					break;
			}
			if(!empty($error))
			{
				throw new exception("JSON Error: ".$error);			
			}
		}
		return $result;
	}

	/**
	 * ****action_currenttimestamp()****
	 * @return time format
	 */
	public function currenttimestamp()
	{
		return date("Y:m:d H:i:s",time());		
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
		if(!isset($this->session) || (!$this->session->get('userid'))  )		//&& !$this->session->get('id')
		{
			
			Message::error(__('login_access'));
			$this->request->redirect("/admin/login/");
		}
		return;
	}
	
	public function is_secondary_login()
	{ 
		$siteInfo = $this->siteinfo;
		$admin_secret_key = $siteInfo[0]['admin_secret_key'];
		$session = Session::instance();
		$session_timestamp = (isset($_SESSION['session_timestamp']) && $_SESSION['session_timestamp'] !="")?$_SESSION['session_timestamp']:"";
		$current_time = convert_timezone('now',TIMEZONE);
		$current_timestamp = strtotime($current_time);
		$seconds_diff = $current_timestamp - $session_timestamp;                          
		$time = ($seconds_diff/3600);
		$url = Request::detect_uri();
		if(isset($_SESSION['session_timestamp']) &&  $time >= 0.5)
		{
			Message::error(__('Please enter your verification password to access the page'));
			$this->request->redirect("/admin/secondary_login/?redirect=".$url);
		}
		if((!$this->session->get('admin_secret_key')) || !($this->session->get('session_timestamp')) )
		{
			Message::error(__('Please enter your verification password to access the page'));
			$this->request->redirect("/admin/secondary_login/?redirect=".$url);
		}
		
		
		$this->session->set('requested_url', Request::detect_uri());
		
		return;
	}

	/**
	 * ****action_email_send()****
	 * @E-Mail function calls from here
	 */	
	public function email_send(array $mail,$type='smtp',$htmlneed=true)
	{

		if(is_array($mail))
		{
			if($this->array_keys_exists($mail,array('to','from','subject','message')))
			{
				$to=$mail['to'];
				$from=$mail['from'];
				$subject=$mail['subject'];
				$message=$mail['message'];
				$headers = 'MIME-Version: 1.0'. "\r\n";
				$headers .= 'Content-type:text/html;charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '. $from . "\r\n";
				switch($type)
				{		
					case "smtp":
					
					 $this->siteusers=Model::factory('siteusers');
					 $smtp_detail=$this->siteusers->get_smtpdetails();
					 $smtp_config="";
					 if(isset($smtp_detail[0]))
					 {
						$host= $smtp_detail[0]['smtp_host'];
						$username=$smtp_detail[0]['smtp_username'];
						$password=$smtp_detail[0]['smtp_password'];
						$port=$smtp_detail[0]['smtp_port'];
						$smtp_config = array('driver' => 'smtp','options' => array('hostname'=>$host,
									'username'=>$username,'password' =>$password,
									'port' => $port,'encryption' => 'ssl')); 	
					 }
					 
					 $smtp_config1 = array('driver' => 'smtp','options' => array('hostname'=>'smtp.gmail.com',
								  'username'=>'johnjoeshep@gmail.com','password' =>'test@123',
									'port' => '465','encryption' => 'ssl'));
											
					
						//mail sending option here
						try{
							if(Email::connect($smtp_config1))
							{                         	                      
								if(Email::send($to,$from,$subject,$message,$html = $htmlneed)==0)
								{                                  		                
									return 1;
								}
								return 0;
							}
						}
						catch(Exception $e)
						{
							try{
								if(mail($to, $subject,$message,$headers))
								{                               
									return 1;
								}
							}
							catch(Exception $e)
							{
								return 0;
							}
						}						
						break;
					default:
						if(mail($to, $subject,$message,$headers))
						{                               
							return 1;
						}
						break;
				}
			}
			else
			{
				return 2;
			}
		}
	}

	/**
	 * ***action_array_keys_exists()****
	 * ** User Defined Function **
	 * @return check array exist otr not
	 */		
	public function array_keys_exists($array,$keys) 
	{
		foreach($keys as $k) 
		{
			if(!isset($array[$k])) 
			{
				return false;
			}
		}
    return true;
	}
	
	/**
	 * ****DisplayDateTimeFormat()****
	 *
	 * @param $input_date_time string
	 * @param 
	 * @return  time format
	 */
	public function DisplayDateTimeFormat($input_date_time)
	{
		//getting input data from last login db field
		//===========================================
		$input_date_split = explode("-",$input_date_time);

		//splitting year and time in two arrays
		//=====================================
		$input_date_explode = explode(' ',$input_date_split[2]);
		$input_date_explode1 = explode(':',$input_date_explode[1]);

		//getting to display datetime format
		//==================================
		$display_datetime_format = date('j M Y h:i:s A',mktime($input_date_explode1[0], $input_date_explode1[1], $input_date_explode1[2], $input_date_split[1], $input_date_explode[0], $input_date_split[0]));

		return $display_datetime_format;
	}
	
    public function get_geolocation() {        
        //return $rs;
    }

    public function create_the_document($input_table,$export_table_header,$export_table_field_select,$heading){
		$vars=explode('-',$_SESSION['download_set']);
		$start=$vars['0']-1;$end=$vars['1']-1;$type=$vars['2'];
		$this->session->delete('download_set');
		if($type==2)
		{
			$pdffile='<style>h1{color: navy;font-family: times;font-size: 24pt;}
					td {font-weight:bold;font:bold 12pt arial; color:#000000;}
					.tr_border{border-bottom:1px solid #2c2c2c;}
					.invoice_head{text-align: center;color:#000000;}
					.head_border{border-bottom:1px solid #2c2c2c;margin-top:5px;}
					.totalstyle{font-weight:bold; font:bold 12pt arial; color:#ffffff; background-color:#2c2c2c; text-align:right;}
				</style>';
			$pdffile .='<table border="0" cellpadding="1" cellspacing="1">';
			//$pdffile .='<tr><td style="text-align:center;">'.$heading.' from '.$start.' to '.$end.'</td></tr>';
			$pdffile .='<tr><td style="text-align:center;"></td></tr>';
			$pdffile .='</table>';
			$pdffile .='<table border="0" cellpadding="1" cellspacing="1">';
			$pdffile .='<tr>';
			$pdffile .='<td class="head_border">'.__('sno_label').'</td>';
				foreach($export_table_header as $head){
					$pdffile .='<td class="head_border">'.$head.'</td>';
				}
				$pdffile .='</tr>';
				
				for($io=$start;$io<=$end;$io++){
					
					if(isset($input_table[$io])){
						$pdffile .='<tr>';
						$pdffile .='<td class="head_border">'.($io+1).'</td>';
						$apt_array=$input_table[$io];
						foreach($export_table_field_select as $kmr){
							if(is_array($kmr)){
								$pdffile .='<td class="head_border">';
								 $koo=1;foreach($kmr['field'] as $vat){ 
									if(isset($apt_array[$vat]) && $apt_array[$vat] !=""){
										if($koo < count($kmr['field'])){
											$pdffile .=$apt_array[$vat].$kmr['symbol'];
										}else{
											$pdffile .=$apt_array[$vat];
										}
									}else{
										$pdffile .='';
									}
								$koo++;	
								}
								$pdffile .='</td>';
							}elseif(isset($apt_array[$kmr])){
							$pdffile .='<td class="head_border">'.$apt_array[$kmr].'</td>';
							}else{
							$pdffile .='<td class="head_border">---</td>';
							} 
						}
						$pdffile .='</tr>';
					}
					
				} 
				
				$pdffile .='</table>';
				$filename = $heading."_".date("Y-m-d_H-i",time());
				$html = preg_replace("<tbody>"," ",$pdffile);
				$html = preg_replace("</tbody>"," ",$html);
				$html = trim($pdffile);
				ob_clean();
				$manage_model = Model::factory('manage');
				$generate_pdf = $manage_model->generate_pdf($html,$filename);
			}else{
					
				$pdffile ='<table border="0" cellpadding="1" cellspacing="1">';
				$pdffile .='<th>'.__('sno_label').'</th>';
				foreach($export_table_header as $head){
				$pdffile .='<th>'.$head.'</th>';
				}
				
				for($io=$start;$io<=$end;$io++){
					if(isset($input_table[$io])){
						$pdffile .='<tr>';
						$pdffile .='<td>'.($io+1).'</td>';
						$apt_array=$input_table[$io];
						foreach($export_table_field_select as $kmr){
							if(is_array($kmr)){
								$pdffile .='<td>';
								 $koo=1;foreach($kmr['field'] as $vat){ 
									if(isset($apt_array[$vat]) && $apt_array[$vat] !=""){
										if($koo < count($kmr['field'])){
											$pdffile .=$apt_array[$vat].$kmr['symbol'];
										}else{
											$pdffile .=$apt_array[$vat];
										}
									}else{
										$pdffile .='';
									}
								$koo++;	
								}
								$pdffile .='</td>';
							}elseif(isset($apt_array[$kmr])){
							$pdffile .='<td>'.$apt_array[$kmr].'</td>';
							}else{
							$pdffile .='<td>---</td>';
							} 
						}
						$pdffile .='</tr>';
					}
					
				} 
				
				$pdffile .='</table>';

				$filename = $heading."_".date("Y-m-d_H-i",time()).'.xls';
				header("Content-disposition: attachment; filename=".$filename."");
				echo $pdffile; 
				exit;
			}
	}
	
	public function action_create_the_document($input_table,$export_table_header,$export_table_field_select,$heading){
		$vars=explode('-',$_SESSION['download_set']);
		$start=0;$end=$vars['1']-1;$type=$vars['2'];
		//echo "<pre>"; print_r($start); exit('dfdfd');
		$this->session->delete('download_set');
		if($type==2)
		{
			$pdffile='<style>h1{color: navy;font-family: times;font-size: 24pt;}
					td {font-weight:bold;font:bold 12pt arial; color:#000000;}
					.tr_border{border-bottom:1px solid #2c2c2c;}
					.invoice_head{text-align: center;color:#000000;}
					.head_border{border-bottom:1px solid #2c2c2c;margin-top:5px;}
					.totalstyle{font-weight:bold; font:bold 12pt arial; color:#ffffff; background-color:#2c2c2c; text-align:right;}
				</style>';
			$pdffile .='<table border="0" cellpadding="1" cellspacing="1">';
			//$pdffile .='<tr><td style="text-align:center;">'.$heading.' from '.$start.' to '.$end.'</td></tr>';
			$pdffile .='<tr><td style="text-align:center;"></td></tr>';
			$pdffile .='</table>';
			$pdffile .='<table border="0" cellpadding="1" cellspacing="1">';
			$pdffile .='<tr>';
			$pdffile .='<td class="head_border">'.__('sno_label').'</td>';
				foreach($export_table_header as $head){
					$pdffile .='<td class="head_border">'.$head.'</td>';
				}
				$pdffile .='</tr>';
				$k=0;
				for($io=$start;$io<=$end;$io++){
					
					if(isset($input_table[$io])){
						$pdffile .='<tr>';
						$pdffile .='<td class="head_border">'.(++$k).'</td>';
						$apt_array=$input_table[$io];
						foreach($export_table_field_select as $kmr){
							if(is_array($kmr)){
								$pdffile .='<td class="head_border">';
								 $koo=1;foreach($kmr['field'] as $vat){ 
									if(isset($apt_array[$vat]) && $apt_array[$vat] !=""){
										if($koo < count($kmr['field'])){
											$pdffile .=$apt_array[$vat].$kmr['symbol'];
										}else{
											$pdffile .=$apt_array[$vat];
										}
									}else{
										$pdffile .='';
									}
								$koo++;	
								}
								$pdffile .='</td>';
							}elseif(isset($apt_array[$kmr])){
							$pdffile .='<td class="head_border">'.$apt_array[$kmr].'</td>';
							}else{
							$pdffile .='<td class="head_border">---</td>';
							} 
						}
						$pdffile .='</tr>';
					}
					
				} 
				
				$pdffile .='</table>';
				$filename = $heading."_".date("Y-m-d_H-i",time());
				$html = preg_replace("<tbody>"," ",$pdffile);
				$html = preg_replace("</tbody>"," ",$html);
				$html = trim($pdffile);
				//echo $html; exit;
				ob_clean();
				$manage_model = Model::factory('manage');
				$generate_pdf = $manage_model->generate_pdf($html,$filename);
			}else{
					
				/* $pdffile ='<table border="0" cellpadding="1" cellspacing="1">';
				$pdffile .='<th>'.__('sno_label').'</th>';
				foreach($export_table_header as $head){
				$pdffile .='<th>'.$head.'</th>';
				} */
				array_unshift($export_table_header,__('sno_label'));
				$dataArray = array();
				$dataContent = array();
				$dataArray[1] = $export_table_header;
				
				for($io=$start;$io<=$end;$io++){
					if(isset($input_table[$io])){
						//$pdffile .='<tr>';
						//$pdffile .='<td>'.($io+1).'</td>';
						$dataContent[] = $io+1;
						$apt_array=$input_table[$io];
						foreach($export_table_field_select as $kmr){
							if(is_array($kmr)){
								//$pdffile .='<td>';
								 $koo=1;foreach($kmr['field'] as $vat){ 
									if(isset($apt_array[$vat]) && $apt_array[$vat] !=""){
										if($koo < count($kmr['field'])){
											//$pdffile .=$apt_array[$vat].$kmr['symbol'];
											$dataContent[] = $apt_array[$vat].$kmr['symbol'];
										}else{
											//$pdffile .=$apt_array[$vat];
											$dataContent[] = $apt_array[$vat];
										}
									}else{
										//$pdffile .='';
									}
								$koo++;	
								}
								
								//$pdffile .='</td>';
							}elseif(isset($apt_array[$kmr])){
							//$pdffile .='<td>'.$apt_array[$kmr].'</td>';
							$dataContent[] = $apt_array[$kmr];
							}else{
							//$pdffile .='<td>---</td>';
							} 
						}
						//$pdffile .='</tr>';
						$dataArray[] = $dataContent;
						$dataContent = array();
					}
					
				} 
				
				//echo '<pre>'; print_r($dataArray);exit;
				$sheetname = $filename = $heading;
				$XLSX = new Spreadsheet();	
				$data = array($sheetname => $dataArray);
				$XLSX->setData( $data, 1 );
				$XLSX->save(array(),$filename);
				
				//$pdffile .='</table>';
				/* $filename = $heading."_".date("Y-m-d_H-i",time());
				header("Content-Disposition: attachment; filename=".$filename.".xls");
				echo $pdffile;  */
				exit;
			}
	}
	
	
	/** function to send mail or sms to passengers while the admin activate/block/trash him **/
	public function sendMailSmspassengers($passEmails = '', $passMobiles = '', $action = '')
	{
		//Mail
		$subject = __('account').' '.ucfirst($action);
		$txtmessage = __('passenger_account_status_update_msg').ucfirst($action);
		$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SUBJECT=>$subject,REPLACE_MESSAGE=>$txtmessage,REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT);
		$message = $this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'passenger_status_send.html',$replace_variables);
		$to = $passEmails;
		$from = $this->siteemail;
		$redirect = "no";	
		if($to != '') {
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
		}
		//SMS
		if(SMS == 1 && !empty($passMobiles))
		{
			$phnosArr = explode(',', $passMobiles);
			if(count($phnosArr) > 0) {
				foreach($phnosArr as $key=>$phone) {
					$result = $this->commonmodel->send_sms($phone,$txtmessage);
				}
			}
		}
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
	
} // End Welcome