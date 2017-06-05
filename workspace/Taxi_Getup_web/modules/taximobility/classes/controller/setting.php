<?php defined('SYSPATH') or die('No direct script access.');
/****************************************************************

* Contains SITE ADMIN details


* @Package: ConnectTaxi

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************************************/
class Controller_Setting extends Controller_Siteadmin {

	/**
	****__construct()****
	*/	

	public function action_index()
	{
		exit('in');
		$this->urlredirect->redirect('admin/login');	
		//$view=View::factory(ADMINVIEW.'authorize/json');
		//$this->template->content=$view;
		//$this->template->title="Admin Login";		
	}
	
	public function action_manage_executives()
	{
			
		$view=View::factory(ADMINVIEW.'callcenter/add_executives');
		$this->template->content=$view;
		$this->template->title="Add Executives";		
	}
	
	
	public function action_add_flows()
	{
		$view=View::factory(ADMINVIEW.'callcenter/add_flows');
		$this->template->content=$view;
		$this->template->title="Add Flows";	
	}
	
	
	public function action_add_devices()
	{
		$view=View::factory(ADMINVIEW.'callcenter/add_devices');
		$this->template->content=$view;
		$this->template->title="Add Devices";		
	}	
	
	public function action_add_numbers()
	{
		$view=View::factory(ADMINVIEW.'callcenter/add_numbers');
		$this->template->content=$view;
		$this->template->title="Add Numbers";		
	}
	

} // End siteadmin class
