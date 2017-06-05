<?php defined('SYSPATH') or die('No direct script access.');
/******************************************

* Contains Cron details

* @Package: Taximobility

* @Author: taxi Team

* @URL : taximobility.com

********************************************/
class Controller_Cronbooking extends Controller_Siteadmin 
{

	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
		$this->cronbooking_model = Model::factory('cronbooking');
	}

/**Function which is used for run the cron daily basis. 
Scope of the function is check the every company recurent booking history in recurent booking tabel and create the new trip before some of time **/
	public function action_cron_recurrentbooking()
	{				
		$status = $this->cronbooking_model->cron_recurrentbooking();		
	}
/**Function which is used for run the cron daily basis. 
Scope of the function is check the every company recurent booking history in recurent booking tabel and dispatch the trip automatically on the time
Search the driver based on current update of the every driver **/
	public function action_cron_autodispatch()
	{		
		$status = $this->cronbooking_model->cron_autodispatch();
	}


	public function action_cron_autodispatch_trips()
	{		
		$status = $this->cronbooking_model->cron_autodispatch_trips();
	}

/*
 * This function is used to delete the expired temporary logs in driver_request_details
 * We have to delete the records in basis of status
 * 2 - Time out request, 4 - Passenger cancelled the request,5 - Driver Arrived,7 - Trip Completed,8 - Trip fare updated, 9 - Driver Cancelled
 * */	
	public function action_delete_expired_records()
	{
		$delete_expired_request = $this->cronbooking_model->delete_driver_request_details();
	}

	public function action_password_reset()
	{ 
		$cronbooking_model = Model::factory('cronbooking');
		$status = $cronbooking_model->cron_pass_reset();

	}
	/************** Later Booking Auto Dispatch()***************/
	public function action_cron_laterbooking_autodispatch()
	{
		$status = $this->cronbooking_model->cron_laterbooking_autodispatch($this->app_name, $this->siteemail);
	}
}
?>
