<?php defined('SYSPATH') OR die('No Direct Script Access');
Class Model_Cms extends Model
{
	/** for getting cms page content**/
	public function get_cmscontent()
	{
		$sql = "select menu,content,link,status,type from ".CMS." where type='1' and status='1'";
		$cms_result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $cms_result;
		
	}
	
	/*Get the CMS Content*/
	public function getcmscontent($content,$default_companyid="")
	{
		$default_companyid=COMPANY_CID;
		if($default_companyid != 0)
		{			
			$sql = "select ".COMPANY_CMS.".content,".COMPANY_CMS.".menu_name as menu from ".COMPANY_CMS."  where ".COMPANY_CMS.".type='1' and status='1' AND ".COMPANY_CMS.".page_url= '$content' AND company_id = '".$default_companyid."'";
		}
		else
		{
			$sql = "select ".CMS.".content,".CMS.".meta_keyword,".CMS.".meta_title,".CMS.".meta_description,".CMS.".menu from ".CMS." JOIN  ".MENU." ON ( ".MENU.".`menu_id` =  ".CMS.".`menu_id` ) where ".CMS.".type='1' and status='1' and ".MENU.".menu_link='".$content."'";
		}
		//echo $sql;exit;
		$cms_result = Db::query(Database::SELECT, $sql)
				->execute()
				->as_array();
			return $cms_result;
	}
	/*Get the CMS Content*/
	public function getcompanycontent($pagename,$cid)
	{
			$rs = DB::select()->from(COMPANY_CMS)		
				->where('company_id','=',$cid)
				->where('page_url','=',$pagename)
				->where('status','=',1)
				->execute()
				->as_array();
			return $rs;		
	}	
	public function get_company_addr($cid)
	{
			$rs = DB::select()->from(COMPANY)		
				->where('cid','=',$cid)
				->execute()
				->as_array();
			return $rs;		
	}

	
}
