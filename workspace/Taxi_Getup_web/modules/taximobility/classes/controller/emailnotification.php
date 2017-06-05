<?php defined('SYSPATH') or die('No direct script access.');


Class Controller_Emailnotification extends Controller_Website
{

	
	
		public function __construct(Request $request, Response $response)  
    	{  
			

		//$data = new Controller_User();
		
		//$this->session = Session::instance();
		parent::__construct($request, $response);
		$this->announcement=Model::factory('announcement');
		$this->emailtemplate=Model::factory('emailtemplate');
		
		
   	    }  
		public function action_birthdaynotify()
		{
			

					$mail="";	
					$service_detail=$siteusers->get_birthdaynotify();
					$usr_set=$siteusers->get_email_settinguser($service_detail[0]['userid'],'mycon_2');	
					if(isset($usr_set[0]['mycon_2'])&&$usr_set[0]['mycon_2']==1)
					{
						$user_detail=$siteusers->get_usernames($user_id);
						$follow_cont=$this->emailtemplate->get_template_content(WISHLIST_SERVICE);
						$subject=$follow_cont[0]['email_subject'];
						$content=$follow_cont[0]['email_content'];
						$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,USERNAME=>isset($service_detail[0]['name'])?$service_detail[0]['name']:'',SERVICE_NAME=>isset($service_detail[0]['title'])?$service_detail[0]['title']:'',WISH_USERNAME=>isset($user_detail[0]['name'])?$user_detail[0]['name']:'',REPLACE_SITENAME=>$this->app_name,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,SITE_DESCRIPTION=>$this->app_description,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
						$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'mail_template.html',$replace_variables,$content);
						$mail=array("to" => isset($service_detail[0]['email'])?$service_detail[0]['email']:'',"from"=>$this->siteemail,"subject"=>$subject,"message"=>$message);									
						$emailstatus=$this->email_send($mail,'smtp');	  
				    }               
		   exit;
		
		}
	
		public function action_addphoto()
		{
			

					$mail="";	
					$service_detail=$siteusers->get_birthdaynotify();
					$usr_set=$siteusers->get_email_settinguser($service_detail[0]['userid'],'mycon_2');	
					if(isset($usr_set[0]['mycon_2'])&&$usr_set[0]['mycon_2']==1)
					{
						$user_detail=$siteusers->get_usernames($user_id);
						$follow_cont=$this->emailtemplate->get_template_content(WISHLIST_SERVICE);
						$subject=$follow_cont[0]['email_subject'];
						$content=$follow_cont[0]['email_content'];
						$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,USERNAME=>isset($service_detail[0]['name'])?$service_detail[0]['name']:'',SERVICE_NAME=>isset($service_detail[0]['title'])?$service_detail[0]['title']:'',WISH_USERNAME=>isset($user_detail[0]['name'])?$user_detail[0]['name']:'',REPLACE_SITENAME=>$this->app_name,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,SITE_DESCRIPTION=>$this->app_description,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
						$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'mail_template.html',$replace_variables,$content);
						$mail=array("to" => isset($service_detail[0]['email'])?$service_detail[0]['email']:'',"from"=>$this->siteemail,"subject"=>$subject,"message"=>$message);									
						$emailstatus=$this->email_send($mail,'smtp');	  
				    }               
		   exit;
		
		}	
		
		public function action_publishlisting()
		{
			

					$mail="";	
					$service_detail=$siteusers->get_birthdaynotify();
					$usr_set=$siteusers->get_email_settinguser($service_detail[0]['userid'],'mycon_2');	
					if(isset($usr_set[0]['mycon_2'])&&$usr_set[0]['mycon_2']==1)
					{
						$user_detail=$siteusers->get_usernames($user_id);
						$follow_cont=$this->emailtemplate->get_template_content(WISHLIST_SERVICE);
						$subject=$follow_cont[0]['email_subject'];
						$content=$follow_cont[0]['email_content'];
						$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,USERNAME=>isset($service_detail[0]['name'])?$service_detail[0]['name']:'',SERVICE_NAME=>isset($service_detail[0]['title'])?$service_detail[0]['title']:'',WISH_USERNAME=>isset($user_detail[0]['name'])?$user_detail[0]['name']:'',REPLACE_SITENAME=>$this->app_name,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,SITE_DESCRIPTION=>$this->app_description,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
						$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'mail_template.html',$replace_variables,$content);
						$mail=array("to" => isset($service_detail[0]['email'])?$service_detail[0]['email']:'',"from"=>$this->siteemail,"subject"=>$subject,"message"=>$message);									
						$emailstatus=$this->email_send($mail,'smtp');	  
				    }               
		   exit;
		
		}	
		
		public function action_completeyouraccount()
		{
			

					$mail="";	
					$service_detail=$siteusers->get_birthdaynotify();
					$usr_set=$siteusers->get_email_settinguser($service_detail[0]['userid'],'mycon_2');	
					if(isset($usr_set[0]['mycon_2'])&&$usr_set[0]['mycon_2']==1)
					{
						$user_detail=$siteusers->get_usernames($user_id);
						$follow_cont=$this->emailtemplate->get_template_content(WISHLIST_SERVICE);
						$subject=$follow_cont[0]['email_subject'];
						$content=$follow_cont[0]['email_content'];
						$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,USERNAME=>isset($service_detail[0]['name'])?$service_detail[0]['name']:'',SERVICE_NAME=>isset($service_detail[0]['title'])?$service_detail[0]['title']:'',WISH_USERNAME=>isset($user_detail[0]['name'])?$user_detail[0]['name']:'',REPLACE_SITENAME=>$this->app_name,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,SITE_DESCRIPTION=>$this->app_description,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);
						$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'mail_template.html',$replace_variables,$content);
						$mail=array("to" => isset($service_detail[0]['email'])?$service_detail[0]['email']:'',"from"=>$this->siteemail,"subject"=>$subject,"message"=>$message);									
						$emailstatus=$this->email_send($mail,'smtp');	  
				    }               
		   exit;
		
		}	
		
		
	
		
			
}//Controller

?>
