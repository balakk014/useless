<?php defined('SYSPATH') or die('No direct script access.');

/****************************************************************

* Contains User Management(Users)details

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************************************/

class Controller_Print extends Controller
{
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
		$session->set('requested_url', Request::detect_uri());
			
	        /**To check Whether the user is logged in or not**/
		if(!isset($session) || (!$session->get('userid')) && !$session->get('id') )		
		{			
			Message::error(__('login_access'));
			$this->request->redirect("/admin/login/");
		}
		return;
	}

	public function action_coupon()
	{
		DEFINE('PROTOCOL','http');
		$form_url = url::base(PROTOCOL,TRUE);
		$drivers_coupons = Session::instance()->get('drivers_coupons');

		if(isset($drivers_coupons) && !empty($drivers_coupons))
		{
			$view = View::factory('admin/export_coupon')
				        ->bind('drivers_coupons', $drivers_coupons)
				        ->bind('form_url',$form_url);
				 
		    $this->response->body($view);
		}
		else
		{
			$this->request->redirect("admin/dashboard");
		}
	}
}	
