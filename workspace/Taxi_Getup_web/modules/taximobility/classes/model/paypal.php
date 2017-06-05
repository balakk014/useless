<?php defined('SYSPATH') or die('No direct script access.');

/******************************************

* Contains Payment Module details

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************/

class Model_Paypal extends Model
{
	
	/**
	 * ****__construct()****
	 *
	 * setting up session variables
	 */
        public function __construct()
        {	
                $this->session = Session::instance();	
                $this->username = $this->session->get("username");
		$this->currentdate = Commonfunction::getCurrentTimeStamp();

	          

        }        
        
        	
        public function getpaypalconfig()
        {
                $result= DB::select()->from(PAYMENT_GATEWAYS)
		             ->where('payment_gatway','=', "Paypals")
			     ->execute()	
			     ->as_array();
                       
		return $result;
                
        }
  
        public function get_user_paypalac(array $params)
        {
                               
		$rs = DB::select(PEOPLE.'.id',PEOPLE.'.paypal_account',REQUEST_FUND.'.requested_id',REQUEST_FUND.'.amount')
				->from(PEOPLE)
				->join(REQUEST_FUND,'left')
				->on(REQUEST_FUND.'.company_ownerid','=',PEOPLE.'.id')				
	    		        ->where(REQUEST_FUND.'.requested_id', 'IN', $params)
	    		         ->where(REQUEST_FUND.'.status', '=', '1')
	    		       // ->group_by(PEOPLE.'.id')    		       
				->execute()
				->as_array();
//print_r($rs);exit;
	        return $rs;
        }

        public function updatewithdraw_details($params,$status,$company_ownerid,$amount)
        {

                $mdate = $this->currentdate;
                $sql_query = array('pay_status'=>$status,'status' => '2','updated_date'=>$mdate);

		$result =  DB::update(REQUEST_FUND)->set($sql_query)
			->where('requested_id', 'IN' , $params )
			->execute();  

		$query = "update ".PEOPLE." set account_balance=account_balance-'$amount' where user_type='A' "; 

		$result = Db::query(Database::UPDATE, $query)
   			 ->execute();


		$query = "update ".PEOPLE." set account_balance=account_balance-'$amount' where id='$company_ownerid'"; 

		$result = Db::query(Database::UPDATE, $query)
   			 ->execute();

                if($result>0)
                {

                $value= DB::select(PEOPLE.'.name',PEOPLE.'.email',REQUEST_FUND.'.requested_id',REQUEST_FUND.'.amount')
                             ->from(PEOPLE)
                             ->join(REQUEST_FUND,'left')
                             ->on(REQUEST_FUND.'.company_ownerid','=',PEOPLE.'.id')
			     ->where(REQUEST_FUND.'.requested_id', 'IN', $params)
			     ->execute()	
			     ->as_array();

             
                 return  $value;
                
              }
      
        }

        public function addwithdraw_transaction_details($transactionfields)
        {
                $transactionkeys=array();
                $transactionvalues=array();
                foreach($transactionfields as $key => $value){
                        array_push($transactionkeys,$key);     
                        array_push($transactionvalues,$value);
                }
                
                $result = DB::insert(WITHDRAW_TRANSACTION_DETAILS, $transactionkeys)
			->values($transactionvalues)
			->execute();
		return $result; 
        }
        

        public function addtransaction_details(array $transactionfields)
        {
        	$add_company = Model::factory('add');

		

               // $transactionfields['SERVICEAMT'] = isset($transactionfields['SERVICEAMT'])?$transactionfields['SERVICEAMT']:"0.00";

                $transactionfields['PAYERID'] = isset($transactionfields['PAYERID'])?$transactionfields['PAYERID']:"";
                $transactionfields['PAYERSTATUS'] = isset($transactionfields['PAYERSTATUS'])?$transactionfields['PAYERSTATUS']:"";
                $transactionfields['FIRSTNAME'] = isset($transactionfields['FIRSTNAME'])?$transactionfields['FIRSTNAME']:"";
                $transactionfields['LASTNAME'] = isset($transactionfields['LASTNAME'])?$transactionfields['LASTNAME']:"";
                $transactionfields['EMAIL'] = isset($transactionfields['EMAIL'])?$transactionfields['EMAIL']:"";

                $transactionfields['COUNTRYCODE'] = isset($transactionfields['COUNTRYCODE'])?$transactionfields['COUNTRYCODE']:"";

                $transactionfields['USERID'] = isset($transactionfields['USERID'])?$transactionfields['USERID']:"";
                $transactionfields['CORRELATIONID'] = isset($transactionfields['CORRELATIONID'])?$transactionfields['CORRELATIONID']:"";
                $transactionfields['ACK'] = isset($transactionfields['ACK'])?$transactionfields['ACK']:"";
                $transactionfields['TRANSACTIONID'] = isset($transactionfields['TRANSACTIONID'])?$transactionfields['TRANSACTIONID']:"";
                $transactionfields['PAYERSTATUS'] = isset($transactionfields['PAYERSTATUS'])?$transactionfields['PAYERSTATUS']:"";
                $transactionfields['TRANSACTIONTYPE'] = isset($transactionfields['TRANSACTIONTYPE'])?$transactionfields['TRANSACTIONTYPE']:"";
                $transactionfields['PAYMENTTYPE'] = isset($transactionfields['PAYMENTTYPE'])?$transactionfields['PAYMENTTYPE']:"";
                $transactionfields['ORDERTIME'] = isset($transactionfields['ORDERTIME'])?$transactionfields['ORDERTIME']:"";


                $transactionfields['AMT'] = isset($transactionfields['AMT'])?$transactionfields['AMT']:"";
                $transactionfields['CURRENCYCODE'] = isset($transactionfields['CURRENCYCODE'])?$transactionfields['CURRENCYCODE']:"";
                $transactionfields['PAYMENTSTATUS'] = isset($transactionfields['PAYMENTSTATUS'])?$transactionfields['PAYMENTSTATUS']:"";
                $transactionfields['PENDINGREASON'] = isset($transactionfields['PENDINGREASON'])?$transactionfields['PENDINGREASON']:"";
                $transactionfields['REASONCODE'] = isset($transactionfields['REASONCODE'])?$transactionfields['REASONCODE']:"";
                $transactionfields['INVOICEID'] = isset($transactionfields['INVOICEID'])?$transactionfields['INVOICEID']:"";

                $transactionfields['LOGIN_ID'] = isset($transactionfields['LOGIN_ID'])?$transactionfields['LOGIN_ID']:"";
                $transactionfields['USER_AGENT'] = isset($transactionfields['USER_AGENT'])?$transactionfields['USER_AGENT']:"";

		$transactionfields['COMPANY_ID'] = isset($transactionfields['COMPANY_ID'])?$transactionfields['COMPANY_ID']:"";
		$pack_id = $transactionfields['PACKAGE_ID'] = isset($transactionfields['PACKAGE_ID'])?$transactionfields['PACKAGE_ID']:"";
		$transactionfields['EXPIRY_DATE'] = isset($transactionfields['EXPIRY_DATE'])?$transactionfields['EXPIRY_DATE']:"";
		$transactionfields['LAST_DATE'] = isset($transactionfields['LAST_DATE'])?$transactionfields['LAST_DATE']:"";
		$transactionfields['UPGRADE_TYPE'] = isset($transactionfields['UPGRADE_TYPE'])?$transactionfields['UPGRADE_TYPE']:"";
		
		$get_packagedetails = $add_company->payment_packagedetails($pack_id);
		$package_name = $get_packagedetails[0]['package_name']; 
		$no_of_taxi = $get_packagedetails[0]['no_of_taxi']; 
		$no_of_driver = $get_packagedetails[0]['no_of_driver']; 
		$check_package_type = $get_packagedetails[0]['package_type']; 
		
		//$current_datetime =  date('Y-m-d H:i:s');
		$current_datetime = convert_timezone('now',TIMEZONE);
			
		if($transactionfields['UPGRADE_TYPE'] == 1)
		{
			
			$result = DB::insert(PACKAGE_REPORT, array('upgrade_companyid','upgrade_packageid','upgrade_packagename','upgrade_no_taxi','upgrade_no_driver','upgrade_startdate','upgrade_expirydate','upgrade_invoiceid','upgrade_transactionid','upgrade_correlationid','upgrade_ack','upgrade_pendingreason','upgrade_reasoncode',
			'upgrade_currencycode','upgrade_capture','upgrade_amount','upgrade_type','upgrade_countrycode','upgrade_by','check_package_type'))
			->values(array($transactionfields['COMPANY_ID'],$transactionfields['PACKAGE_ID'],$package_name,$no_of_taxi,$no_of_driver,$current_datetime,$transactionfields['EXPIRY_DATE'],$transactionfields['INVOICEID'],$transactionfields['TRANSACTIONID'],$transactionfields['CORRELATIONID'],$transactionfields['ACK'],$transactionfields['PENDINGREASON'],$transactionfields['REASONCODE'],$transactionfields['CURRENCYCODE'],'1',$transactionfields['AMT'],'1',$transactionfields['COUNTRYCODE'],$transactionfields['USERID'],$check_package_type))
			->execute();

		}
		else
		{
			
			$result = DB::insert(PACKAGE_REPORT, array('upgrade_companyid','upgrade_packageid','upgrade_packagename','upgrade_no_taxi','upgrade_no_driver','upgrade_startdate','upgrade_expirydate','upgrade_invoiceid','upgrade_transactionid','upgrade_correlationid','upgrade_ack','upgrade_pendingreason','upgrade_reasoncode',
			'upgrade_currencycode','upgrade_capture','upgrade_amount','upgrade_type','upgrade_countrycode','upgrade_by','check_package_type'))
			->values(array($transactionfields['COMPANY_ID'],$transactionfields['PACKAGE_ID'],$package_name,$no_of_taxi,$no_of_driver,$transactionfields['LAST_DATE'],$transactionfields['EXPIRY_DATE'],$transactionfields['INVOICEID'],$transactionfields['TRANSACTIONID'],$transactionfields['CORRELATIONID'],$transactionfields['ACK'],$transactionfields['PENDINGREASON'],$transactionfields['REASONCODE'],$transactionfields['CURRENCYCODE'],'1',$transactionfields['AMT'],'1',$transactionfields['COUNTRYCODE'],$transactionfields['USERID'],$check_package_type))
			->execute();
							
		}	
		
		$amount = $transactionfields['AMT'];
		//Set Commission to Admin	
		$updatequery = " UPDATE ". PEOPLE ." SET account_balance=account_balance+$amount wHERE user_type = 'A'";	

		$updateresult = Db::query(Database::UPDATE, $updatequery)
				->execute();

		        return $result;  
        }
 
}
?>
