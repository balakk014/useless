<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Twilio extends Controller_Siteadmin 
{

	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);		
	}	
	
	public function action_twilioCall()		
	{
		require_once(DOCROOT.'application/vendor/smsgateway/Services/Twilio.php');
		$api = Model::factory('twilio');
		if(isset($_REQUEST['From']))
		{
			$from_number=$_REQUEST['From'];		
			$to_passenger_number=$api->getPassengerNumber($from_number);
			$to_driver_number=$api->getDriverNumber($from_number);
			if(count($to_passenger_number)>0)
			{
				$to=$to_passenger_number[0]["passenger_phone"];
				$driverid=$to_passenger_number[0]["driver_id"];
				$twiliocall=$api->getDriverInfo($driverid);
				$twilio_no=$twiliocall[0]["twilio_number"];
			}else if(count($to_driver_number)>0)
			{
				$to=$to_driver_number[0]["driver_phone"];
				$driverid=$to_driver_number[0]["driver_id"];
				$twiliocall=$api->getDriverInfo($driverid);
				$twilio_no=$twiliocall[0]["twilio_number"];
			}
			else
			{
				header('Content-type: text/xml');
				echo '<Response>
				<Say voice="woman" language="en">sorry number not valid</Say>
				</Response>';
				exit;
			}
		}else{
			$message="Call from mobile number";
			echo json_encode($message);
			exit;
		}
		header('Content-type: text/xml');					
		echo '<Response>
		<Dial callerId="'.$twilio_no.'">'.$to.'</Dial>
		</Response>';							
		exit;
	}
		
}
?>
