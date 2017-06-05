
<?php defined('SYSPATH') OR die('No Direct Script Access');

class Model_Twilio extends Model
{
	public function getPassengerNumber($fromNumber){
		$sql="SELECT  passenger_phone,driver_id FROM ".TWILIO_CALL." WHERE driver_phone='".$fromNumber."'";
		return Db::query(Database::SELECT, $sql)->execute()->as_array();
		
	}
	public function getDriverNumber($fromNumber){
		$sql="SELECT  driver_phone,driver_id FROM ".TWILIO_CALL." WHERE passenger_phone='".$fromNumber."'";
		return Db::query(Database::SELECT, $sql)->execute()->as_array();
	}
	public function getDriverInfo($driverid){
		$sql="SELECT  driver_twilio_number as twilio_number FROM ".DRIVER_INFO." WHERE driver_id='".$driverid."'";
		return Db::query(Database::SELECT, $sql)->execute()->as_array();
	}

}
?>
