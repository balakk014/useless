<?php defined('SYSPATH') or die('No direct access allowed.');

/*
 * You should have all the module's models inside the module folder
 * so the module can be functional just by copying the module folder.
 *
 * It's recomended to name you Module's Models as Model_Modulename_<Model>
 * to avoid any conflics with any application model
 */

class Model_Commonfunction extends Model {
 
	/**
	*****add_fb_details()****
	*@param 
	*@add fb acc details
	*/        
	
	/* 
	 *
 	public function add_fb_details($arr)
	{	
		$date = date('Y-m-d H:i:s', time());

		$email = isset($arr['email'])?$arr['email']:0;
		//$acc_usr_id = isset($arr['id'])?$arr['id']:0;
		$id = isset($_SESSION['userid'])?$_SESSION['userid']:$_SESSION['id'];
		$acc_token = isset($arr['access_token'])?$arr['access_token']:0;
		$id = isset($_SESSION)?$_SESSION['id']:'';
		$result=  DB::insert(SOCIAL_MEDIA_ACCOUNTS,array('user_id','fb_user_id','social_account_name','profile_image','profile_url','access_key','secret_key',
		'facebook_session_key','account_type','created_date'))
					->values(array($id,$arr['id'],($arr['first_name'].$arr['last_name']),$arr['picture'],$arr['link'],$acc_token,$acc_token,$acc_token,FACEBOOK,$date))
					->execute();
		return $result;
	}
	public function add_googleplus_details($uid,$arr)
	{		
		$date = date('Y-m-d H:i:s', time());
		//$id = isset($_SESSION)?$_SESSION['id']:'';

		$result=  DB::insert(SOCIAL_MEDIA_ACCOUNTS,array('user_id','social_account_name','profile_image','profile_url','account_type','created_date'))
					->values(array($uid,($arr['firstname'].$arr['lastname']),$arr['picture'],$arr['link'],GOOGLEPLUS,$date))
					->execute();
   
		return $result;
	}

 
 	public function add_twitter_details($usrid)
	{
	
		//intialize twitter auth
		//=======================
		$this->twitter=Auth::instance();
		$twit_tokens=arr::extract($_GET,array('oauth_token','oauth_verifier','denied'));

		if(isset($twit_tokens['oauth_token']) && isset($twit_tokens['oauth_verifier']))	 
		{	
			$this->twitter->logged_in();		
			$this->user = $this->twitter->get_user();

		}	

		$name = $this->user->response['screen_name'];
		$img_url = $this->user->response['profile_image_url'];
		//form profile url by screen name
		$profile_url = "https://twitter.com/#!/".$name;

		$date = date('Y-m-d H:i:s', time());
		
		//getting oauth token from session
		//================================
	    if(isset($_SESSION) && ($_SESSION['oauth_token'] != "")){ $oauth_token = $_SESSION['oauth_token']; }
		if(isset($_SESSION) && ($_SESSION['oauth_token_secret'] !="")){ $oauth_secret = $_SESSION['oauth_token_secret'];}
		
		$result=  DB::insert(SOCIAL_MEDIA_ACCOUNTS,array('user_id','social_account_name','profile_image','profile_url','oauth_token','oauth_token_secret',
					'account_type','created_date'))
		->values(array($usrid,$name,$img_url,$profile_url,$oauth_token,$oauth_secret,TWITTER,$date))
					->execute();

		return $result;
	}
	 
 	public function add_linkedin_details($id="",$arr="")
	{
		
		$uname = isset($arr['username'])?$arr['username']:"";
		$date = date('Y-m-d H:i:s', time());
		$img_url = $profile_url = "";
		
		//getting oauth token from session
		//================================
	    if(isset($_SESSION) && ($_SESSION['linkdin_details']['access_token'] != "")){ $oauth_token = $_SESSION['linkdin_details']['access_token']; }
		if(isset($_SESSION) && ($_SESSION['linkdin_details']['secret_key'] !="")){ $oauth_secret = $_SESSION['linkdin_details']['secret_key'];}
		
		//get linkedin username and profile url form session
		//==================================================
		if(isset($_SESSION) && ($_SESSION['linkdin_details']['username'])) { $uname = $_SESSION['linkdin_details']['username'];}
		if(isset($_SESSION) && ($_SESSION['linkdin_details']['profileimage'])) { $img_url = $_SESSION['linkdin_details']['profileimage'];}
		if(isset($_SESSION) && ($_SESSION['linkdin_details']['pubprofile'])) { $profile_url = $_SESSION['linkdin_details']['pubprofile'];}
		
		
		$result=  DB::insert(SOCIAL_MEDIA_ACCOUNTS,
					array('user_id','social_account_name','profile_image','profile_url','oauth_token',
					'oauth_token_secret','facebook_session_key','account_type','created_date'))
					->values(array($id,$uname,$img_url,$profile_url,$oauth_token,$oauth_secret,'',LINKDIN,$date))
					->execute();
		return $result;
	}
	*/

} // End User Model
