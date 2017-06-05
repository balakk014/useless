
<?php defined('SYSPATH') OR die('No Direct Script Access');

class Model_Cronreminder extends Model
{

	public function cron_reminder()
	{
				
		$apikey_query = "select company_cid,company_api_key,company_email_id,company_name,time_zone as timezone from companyinfo 
					left join company on company.cid=companyinfo.company_cid where company_status='A'";

		$apikey_result =  Db::query(Database::SELECT, $apikey_query)
		->execute()
		->as_array();
		if(count($apikey_result)>0)
		{
			return $apikey_result;
		}
		else
		{
			return 0;
		}
		
	}

	public function get_company_package_expdate($company_id)
	{
		$query = "select upgrade_packageid,upgrade_packagename,upgrade_no_taxi,upgrade_expirydate,name from package_report
				left join people on people.company_id=package_report.upgrade_companyid
				where package_report.upgrade_companyid = '$company_id'
				and people.status NOT IN ('D')
				order by upgrade_id desc limit 0,1";
				
		$query_result =  Db::query(Database::SELECT, $query)->execute()->as_array();
		if(count($query_result)>0)
		{
			return $query_result;
		}
		else
		{
			return 0;
		}		
	}
	
	public function cron_deactive_company($company_id)
	{
		$result1 = DB::update(PEOPLE)->set(array('status' => 'D'))
				->and_where('company_id', '=', $company_id)
				->and_where('user_type', '=', 'C') //Deactive Company
				->execute();
		
		$result2 = DB::update(PEOPLE)->set(array('status' => 'D'))
				->and_where('company_id', '=', $company_id)
				->and_where('user_type', '=', 'M') //Deactive Dispatchers
				->execute();
				
		$result3 = DB::update(PEOPLE)->set(array('status' => 'D'))
				->and_where('company_id', '=', $company_id)
				->and_where('user_type', '=', 'D') //Deactive Drivers
				->execute();
		
		if($result1 || $result2 || $result3){
			return 1;
		}else{
			return 0;
		}
	}

	public function company_current_expiry_date($company_id)
	{
		$query="select max(upgrade_id) as max_id
				from ".PACKAGE_REPORT."
				where upgrade_companyid=".$company_id."";
				
		$result =  Db::query(Database::SELECT, $query)->execute()->as_array();
		
		if(count($result)>0){
			$max_id=$result[0]['max_id'];
			$query="select upgrade_expirydate,company_name,cid
					from ".PACKAGE_REPORT."
					left join ".COMPANY." on company.cid=".PACKAGE_REPORT.".upgrade_companyid
					left join ".PEOPLE." on company_id=".PACKAGE_REPORT.".upgrade_companyid
					where ".PACKAGE_REPORT.".upgrade_id=".$max_id."
					and ".PEOPLE.".status NOT IN ('D')";
			$result1 =  Db::query(Database::SELECT, $query)->execute()->as_array();
			
			if(count($result1)>0){
				return $result1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

		
	

}
?>
