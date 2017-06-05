<?php defined('SYSPATH') or die('No direct script access.');

/******************************************

* Contains Payment Module details

* @Author: NDOT Team

* @URL : http://www.ndot.in

********************************************/

class Controller_Payment extends Controller_Website {

        public function __construct(Request $request, Response $response)
	    {
	        
		     parent::__construct($request, $response);
		     $this->paypal_db = Model::factory('paypal');	


		     $this->paypalconfig = $this->paypal_db->getpaypalconfig(); 
		     $this->paypaldbconfig = count($this->paypalconfig) > 0?array("username"=>$this->paypalconfig[0]['paypal_api_username'],
		     "password"=>$this->paypalconfig[0]['paypal_api_password'], "signature"=>$this->paypalconfig[0]['paypal_api_signature'], 
		     "environment"=>($this->paypalconfig[0]['payment_method'] =="L")?"live":"sandbox"):"";
		
		     // Load default configuration
		     $this->paypalconfigfile = Kohana::$config->load('paypal');		
		     $this->paypal_account = count($this->paypalconfig) > 0?$this->paypalconfig[0]['paypal_api_username']:$this->paypalconfigfile->username;
		     $this->paypal_currencycode = count($this->paypalconfig) > 0?$this->paypalconfig[0]['currency_code']:'USD';
		
		
            $this->adminId =''; 
            $this->session = Session::instance();
            $this->isadminlogin = ( strlen($this->session->get('userid')) > 0 && $this->session->get('userid') == $this->adminId)?1:0;
            
            $this->siteurl = URL::base('http', TRUE);  

	    $this->email= $this->session->get('user_email');
	    $this->currentdate = Commonfunction::getCurrentTimeStamp();

	}
	
    /**
	 * ****action_is_login()****
	 * @return check user logged or not
	 */
	public function is_login()
	{ 
		
		$session = Session::instance();

		//get current url and set it into session
		//========================================
		$this->session->set('requested_url', Request::detect_uri());
			
	        /**To check Whether the user is logged in or not**/
		if(!isset($this->session) || !$this->session->get('userid') )
		{
			Message::error(__('login_access'));
			$this->request->redirect("/admin/login/");
		}
		return;
	}
	public function action_index(){
	
	       //no direct access allow,redirect to home page        
	       $this->request->redirect('add/upgradepackage');
	}
	
	public function action_pay(){   

    	$site = Model::factory('site');
	$add_company = Model::factory('add');

	     /**Check Whether the user is logged in**/
		$this->is_login(); 

		/**To get current logged user id from session**/
		$id =$userid =$this->session->get('userid'); 
		$company_id =$this->session->get('company_id'); 
		$upgrade_type = $_POST["upgrade_type"];
		$pack_id = $_POST["pack"];
		$this->session->set('pack_id',$pack_id);
		$this->session->set('upgrade_type',$upgrade_type);
                //if not post redirect to home page
                if(isset($_POST["pack"]) && ($userid!='') )
                { 
			$pack_id = $_POST["pack"];

			$pack_id = $this->session->get('pack_id');
			$get_packagedetails = $add_company->payment_packagedetails($pack_id);
		
			$amount = $get_packagedetails[0]['package_price'];   
			$amount = number_format($amount, 2, '.', '');

			$currency_code = $this->paypal_currencycode;                         

			$product_title = Html::chars($get_packagedetails[0]['package_name']);

			$payment_gateway  =  $_POST['payment_type'];
			$sellerid = $_SESSION['userid'];

                }else{
                
                        //show msg as do not call direct payment url
                        //redirect to home here
                        $this->request->redirect('add/upgradepackage');
                }
                
                $paypal_type=($this->paypalconfig[0]['payment_method'] =="L")?"live":"sandbox";

               if($paypal_type=='live') { $production = 1;  }
                else {  $production = 0; }


                $urlProtocal = $_SERVER['SERVER_PORT'];
                $accessProtocol = ($urlProtocal == 443)?'https://':'http://';
                $host = $accessProtocol.$_SERVER["HTTP_HOST"];
                
                $notifyurl =  $host.URL_BASE."payment/notify";               
                $this->payment_success_url = url::site('listing/order/purchase-complete', 'http');//cms/page/order-purchase-complete
                $returnurl = url::site('payment/checkout', 'http');               
                $cancelurl = url::site('payment/cancelled', 'http');
                $email = $this->email;



                if($payment_gateway == "Paypal") //ExpressCheckout
                {
                        $paypal = new Paypal;
                        
                        //send control to paypal url usinf process single function
                        $paypal->item_name($product_title)
                                    ->amount($amount)
                                    ->notify_url($notifyurl)
                                    ->return_url($returnurl) //your thank you page, where paypal will be redirected after the transaction
                                    ->cancel_return($cancelurl)
                                    ->custom("custom info")
                                    ->currency_code($currency_code)
                                    ->production($production)
                                    ->process_single();
                        
                }else{ 
                        
                        $invoceno = Commonfunction::randomkey_generator();
                        $paypal = Paypal::instance('ExpressCheckout',$this->paypaldbconfig);



                        $data = array(
                         'AMT' => $amount, 'CURRENCYCODE' => $this->paypal_currencycode, 'RETURNURL' => $returnurl, 'CANCELURL' => $cancelurl , 
                         'PAYMENTACTION' =>'Sale','DESC'=>$product_title,'MAXAMT'=>'',//$this->paypalcashwithdrawconfig['max_amount']
                        'EMAIL'=>$email,'NOSHIPPING'=>1,'L_NAME0'=>$product_title,'L_NUMBER0'=>$pack_id,'L_QTY0'=>1,'L_TAXAMT0'=>0.00,'L_AMT0'=>
                         $amount,'ITEMAMT'=>$amount); //,'FROMDATE'=>$fdate,'TODATE'=>$tdate,'FROMTIME'=>$ftime,'TOTIME'=>$ttime,'TOTAL_HOURS'=>$bookinghours


                        $payment = $paypal->set($data);

                        if (Arr::get($payment, 'ACK') === 'Success') { 
                        
                                // Store token in SESSION
                                $this->session->set('paypal_token_' . $payment['TOKEN'], $amount);
                                $this->session->set('paypal_sellerid_' . $payment['TOKEN'], $sellerid);
                                $this->session->set('paypal_invoiceno_' . $payment['TOKEN'], $invoceno);
                                $this->session->set('paypal_jobtitle_' . $payment['TOKEN'], $product_title);
                                

                                // We now send the user to the Paypal site for them to provide their details
                                $params = $data;
                                $params['token'] = $payment['TOKEN'];
                                unset($params['PAYMENTACTION']);

                                $url = $paypal->redirect_url('express-checkout', $params);
                                $this->request->redirect($url);

                        }else{
				                $this->request->redirect('add/upgradepackage');
			                }
                 }
	}
	
    public function action_checkout()
    {
	
	        $token = Arr::get($_GET, 'token');
		$add_company = Model::factory('add');
            // Check token is valid so you can load details	

            // Load the Paypal object
            $paypal = Paypal::instance('ExpressCheckout',$this->paypaldbconfig);

            // Get the customers details from Paypal
            $customer = $paypal->GetExpressCheckoutDetails(array('TOKEN'=>$token));
           

            if (Arr::get($customer, 'ACK') === 'Success')
            {
                    // Perform any calculations to determine the final charging price

                    $params = array(
                    'TOKEN'     => $token,
                    'PAYERID'   => Arr::get($customer, 'PAYERID'),
                    'AMT'       => $this->session->get('paypal_token_'.$token),
                    'CURRENCYCODE' => $this->paypal_currencycode
                    );

         	 $pack_id = $this->session->get('pack_id');
		$get_packagedetails = $add_company->payment_packagedetails($pack_id);

		$days = $get_packagedetails[0]['days_expire'];   
		$company_id =$this->session->get('company_id'); 

		$upgrade_packagelist = " select * from " . PACKAGE_REPORT . "  where ".PACKAGE_REPORT.".upgrade_companyid = '$company_id' order by upgrade_expirydate  DESC";

		$upgrade_result = Db::query(Database::SELECT, $upgrade_packagelist)
				->execute()
				->as_array();

		if(count($upgrade_result) > 0)
		{
			$last_expirydate = $upgrade_result[0]['upgrade_expirydate'];
		}
		else
		{
			$last_expirydate =  date('Y-m-d H:i:s');
		}

		$last_expirydate = convert_timezone('now',TIMEZONE);				

		$upgrade_type = $this->session->get('upgrade_type');	
		
		if($upgrade_type == 1)
		{
			$current_time = convert_timezone('now',TIMEZONE);
		
			if($days > 0)
			{
				$expirydate = Commonfunction::getExpiryTimeStamp($current_time,$days);
			}
			else
			{
				$expirydate = $current_time;
			}
		}
		else
		{

			if($days > 0)
			{
				$expirydate = Commonfunction::getExpiredTimeStamp($lase_expirydate,$days);
			}
			else
			{
				$expirydate = $current_time;
			}

						
		}

			$current_time = convert_timezone('now',TIMEZONE);
			if($days > 0)
			{
				$expirydate = Commonfunction::getExpiryTimeStamp($current_time,$days);
			}
			else
			{
				$expirydate = $current_time;
			}


                    // Process the payment
                    $paymentresponse = $paypal->DoExpressCheckoutPayment($params); 

                    $this->session->set('paymentresponse',$paymentresponse);// Data store

                    if (Arr::get($paymentresponse, 'ACK') === 'Success')
                    {       

                            $this->session->set('shipping_countrycode',Arr::get($customer, 'COUNTRYCODE'));  
               
                            //transaction
                            $transactionfield = array(
                                                        'PAYERID' => Arr::get($customer, 'PAYERID'),
                                                        'PAYERSTATUS' => Arr::get($customer, 'PAYERSTATUS'),


                                                        'FIRSTNAME' => Arr::get($customer, 'FIRSTNAME'),
                                                        'LASTNAME' => Arr::get($customer, 'LASTNAME'),
                                                        'EMAIL' => Arr::get($customer, 'EMAIL'),

							'PACKAGE_ID' => Arr::get($customer,'L_NUMBER0'),
							'COMPANY_ID' => $this->session->get('company_id'),  
							'UPGRADE_TYPE' => $this->session->get('upgrade_type'),    
							'LAST_DATE' => $last_expirydate,                 
							'EXPIRY_DATE' => $expirydate,                 
                                                        'COUNTRYCODE' => Arr::get($customer, 'COUNTRYCODE'),
                                                        'USERID' => $this->session->get('userid'),                       
                                                        'INVOICEID' => $this->session->get('paypal_invoiceno_' .$token),
                                                        'LOGIN_ID' => Request::$client_ip,
                                                        'USER_AGENT' => Request::$user_agent
                                                        );
                            
                            $transactionfield = $transactionfield + $paymentresponse;           
       
                            $this->session->set('transactionfield',$transactionfield);// Data store
                 
                          
                            //insert transaction status
                            //===================================
                            $transaction_detail=$this->paypal_db->addtransaction_details($transactionfield);

                            $last_transaction_insert_id = $transaction_detail[0];
			     $total_amt = isset($transactionfield['AMT'])?$transactionfield['AMT']:"";	
                            $orderid= $order_no = $paymentresponse['TRANSACTIONID'];

                            $customer_name=$this->session->get('name');
                            $customer_email=$this->session->get('email'); 

                            $email=$this->session->get('email').",".$customer_email; //'rathi.t@ndot.in'.','.
                            $name=$this->session->get('name'); 


				$orderlist='';   
				$orderlist=' <p>'.__('package_service').' : '.$this->session->get('paypal_jobtitle_'.$token).'</p>'.
				'<p>'.__('purchased_date').' : '.$this->currentdate.'</p>'.
				'<p>'.__('total_cost').' : '. CURRENCY.' '.$total_amt.'</p>'.                                      	

				'<p>'.__('paypal_invoice').' : '. $order_no.'</p>';


				$mail="";								
				$replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>ucfirst($name),REPLACE_EMAIL=>$email,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ORDERID=>$orderid,REPLACE_ORDERLIST=>$orderlist,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);

				$message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'order-mail.html',$replace_variables);

					$to = $email;
					$from = $this->siteemail;
					$subject = __('payment_made_successfully');	
					$redirect = "add/upgradepackage";	
					if(SMTP == 1)
					{
						include($_SERVER['DOCUMENT_ROOT']."/modules/SMTP/smtp.php");
					}
					else
					{
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						// Additional headers
						$headers .= 'From: '.$from.'' . "\r\n";
						$headers .= 'Bcc: '.$to.'' . "\r\n";
						mail($to,$subject,$message,$headers);	
					}

				//$mail=array("to" =>$email,"from"=>$this->siteemail,"subject"=>"Payment has been made successfully","message"=>$message);
					

				//$emailstatus=$this->email_send($mail,'smtp');								
					       



                             Message::success(__("payment_completed"));
                            $this->request->redirect('add/upgradepackage');   
                    
                    }else{
                    
                            //redirect to api error page
                            $this->request->redirect('add/upgradepackage');
                    }			
 	                //redirect to api error page
                    $this->request->redirect('add/upgradepackage');
            }
	}
	
	public function action_cancelled(){ 	
			
			//set cancel message
             Message::success(__("payment_cancelled"));
			$this->request->redirect('add/upgradepackage');
			//create cancel page and call 			
	}
	
	public function action_apierror(){ 
			//change order status 
			//
			//create cancel page and call 
			
	}
     public function action_dodirectpayment()
    {   

		$siteusers = Model::factory('siteusers');
		$add_company = Model::factory('add');


	         /**Check Whether the user is logged in**/
		    $this->is_login(); 
		   	
		    /**To get current logged user id from session**/
			$id =$userid =$this->session->get('userid');
			$company_id =$this->session->get('company_id'); 
			$upgrade_type = $_POST["upgrade_type"];
			
			$pack_id = $_POST["pack"];
			
			$this->session->set('pack_id',$pack_id);
			$this->session->set('upgrade_type',$upgrade_type); 
            //if not post redirect to home page
            if(isset($_POST["pack"]) && ($userid!='') )
            { 


			$get_packagedetails = $add_company->payment_packagedetails($_POST['pack']);

			$days = $get_packagedetails[0]['days_expire'];   
						

			$company_id =$this->session->get('company_id'); 

			$upgrade_packagelist = " select * from " . PACKAGE_REPORT . "  where ".PACKAGE_REPORT.".upgrade_companyid = '$company_id' order by upgrade_expirydate  DESC";

			$upgrade_result = Db::query(Database::SELECT, $upgrade_packagelist)
					->execute()
					->as_array();

			if(count($upgrade_result) > 0)
			{
				$last_expirydate = $upgrade_result[0]['upgrade_expirydate'];
			}
			else
			{
				$last_expirydate =  date('Y-m-d H:i:s');
			}
			
			$last_expirydate = convert_timezone('now',TIMEZONE);

			$upgrade_type = $this->session->get('upgrade_type');	
		
			if($upgrade_type == 1)
			{

				$current_time = convert_timezone('now',TIMEZONE);
				if($days > 0)
				{
					$expirydate = Commonfunction::getExpiryTimeStamp($current_time,$days);
				}
				else
				{
					$expirydate = $current_time;
				}
			}
			else
			{
				if($days > 0)
				{
					$expirydate = Commonfunction::getExpiredTimeStamp($current_time,$days);
				}
				else
				{
					$expirydate = $current_time;
				}
						
			}
				
				
				
			$amount = $get_packagedetails[0]['package_price'];   
			$amount = number_format($amount, 2, '.', '');

			$currency_code = $this->paypal_currencycode;                         

			$product_title = Html::chars($get_packagedetails[0]['package_name']);
			$sellerid = $_SESSION['userid'];

			//$payment_type = 'Authorization';
			$payment_action='sale';

			$request  = 'METHOD=DoDirectPayment';
			$request .= '&VERSION=65.1'; //  $this->version='65.1';     51.0  
			$request .= '&USER=' . urlencode($this->paypalconfig[0]['paypal_api_username']);
			$request .= '&PWD=' . urlencode($this->paypalconfig[0]['paypal_api_password']);
			$request .= '&SIGNATURE=' . urlencode($this->paypalconfig[0]['paypal_api_signature']);


			$request .= '&CUSTREF=' . (int)$pack_id;
			$request .= '&PAYMENTACTION=' . $payment_action; //type
			$request .= '&AMT=' . urlencode($amount); //   $amount = urlencode($data['amount']);

			//$request .= '&CREDITCARDTYPE=' . $_POST['cc_type'];
			$request .= '&ACCT=' . urlencode(str_replace(' ', '', $_POST['creditCardNumber']));
			// $request .= '&CARDSTART=' . urlencode($_POST['cc_start_date_month'] . $_POST['cc_start_date_year']);
			$request .= '&EXPDATE=' . urlencode($_POST['cc_expire_date_month'] . $_POST['cc_expire_date_year']);
			$request .= '&CVV2=' . urlencode($_POST['cvv2Number']);


			/*$request .= '&FIRSTNAME=' . urlencode($shipping_first_name);
			$request .= '&LASTNAME=' . urlencode($shipping_last_name);
			$request .= '&EMAIL=' . urlencode($shipping_email);

			$request .= '&IPADDRESS=' . urlencode($_SERVER['REMOTE_ADDR']);
			$request .= '&STREET=' . urlencode($this->session->get('addr'));


			$request .= '&CITY=' . urlencode($city);
			$request .= '&STATE=' . urlencode($state);
			$request .= '&ZIP=' . urlencode($zipcode);


			$request .= '&COUNTRYCODE=' . urlencode($country_code);
			*/
			$request .= '&CURRENCYCODE=' . urlencode($this->paypal_currencycode);


                    $paypal_type=($this->paypalconfig[0]['payment_method'] =="L")?"live":"sandbox";
		            if ($paypal_type=="live") {
			            $curl = curl_init('https://api-3t.paypal.com/nvp');
		            } else {
			            $curl = curl_init('https://api-3t.sandbox.paypal.com/nvp');
		            }
		
		            curl_setopt($curl, CURLOPT_PORT, 443);
		            curl_setopt($curl, CURLOPT_HEADER, 0);
		            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		            curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		            curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		            curl_setopt($curl, CURLOPT_POST, 1);
		            curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

		            $response = curl_exec($curl); 
		            $nvpstr=$response; 		
		            curl_close($curl); 

		            $intial=0;
	             	$nvpArray = array();


		            while(strlen($nvpstr)){
			            //postion of Key
			            $keypos= strpos($nvpstr,'=');
			            //position of value
			            $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

			            /*getting the Key and Value values and storing in a Associative Array*/
			            $keyval=substr($nvpstr,$intial,$keypos);
			            $valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			            //decoding the respose
			            $nvpArray[urldecode($keyval)] =urldecode( $valval);
			            $nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
	               	   }
		            $_SESSION["paymentresponse"]=array();
                    $_SESSION["paymentresponse"]=$nvpArray;  

                    if(isset($_SESSION["paymentresponse"]) && !empty($_SESSION["paymentresponse"]) )
                    {
                        $paymentresponse=array();

                        $ack = strtoupper($_SESSION['paymentresponse']["ACK"]);		                
		                if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING" )
		                {                           


                            $invoceno = commonfunction::randomkey_generator();
   
                            $paymentresponse=$_SESSION['paymentresponse'];

                            $transactionfield = array(

                                            'FIRSTNAME' => $this->session->get('username'),
                                            'LASTNAME' => $this->session->get('username'),
                                            'EMAIL' => $this->email,
					    'PACKAGE_ID' => $this->session->get('pack_id'),
					    'COMPANY_ID' => $this->session->get('company_id'),  
					    'UPGRADE_TYPE' => $this->session->get('upgrade_type'),    
					    'LAST_DATE' => $last_expirydate,    
					    'EXPIRY_DATE' => $expirydate,                 
                                            'COUNTRYCODE' => 'US',
                                            'USERID' => $this->session->get('userid'),                       
                                            'INVOICEID' => $_SESSION['paymentresponse']["TRANSACTIONID"], 
                                            'LOGIN_ID' => Request::$client_ip,
                                            'USER_AGENT' => Request::$user_agent
                                            );                        

                            $transactionfield = $transactionfield + $paymentresponse;   
                            $this->session->set('transactionfield',$transactionfield);// Data store
                 

                            //insert transaction status
                            //===================================
                            $transaction_detail=$this->paypal_db->addtransaction_details($transactionfield);
                            $last_transaction_insert_id = $transaction_detail[0];

			    $total_amt = isset($transactionfield['AMT'])?$transactionfield['AMT']:"";	

                            $orderid=$order_no=$_SESSION['paymentresponse']["TRANSACTIONID"];
        
                            $Transactionfield=array();    
                            $Transactionfield=$this->session->get('transactionfield');


				/* *************************** Mail send to purchased user ends ************** */   

                            $customer_name=$this->session->get('name');
                            $customer_email=$this->session->get('email'); 

                            $email=$this->session->get('email').",".$customer_email; //'rathi.t@ndot.in'.','.
                            $name=$this->session->get('name'); 


                            $orderlist='';  

			     $current_datetime = convert_timezone('now',TIMEZONE);

                             $orderlist=' <p>'.__('payment').' : '.$product_title.'</p>'.
                                         '<p>'.__('purchased_date').' : '.$current_datetime.'</p>'.
					'<p>'.__('total_cost').' : '. CURRENCY.' '.$total_amt.'</p>'.                                      	
                                 
                                        '<p>'.__('paypal_invoice').' : '. $order_no.'</p>';
         
      	                    $mail="";								
					        $replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>ucfirst($name),REPLACE_EMAIL=>$email,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ORDERID=>$orderid,REPLACE_ORDERLIST=>$orderlist,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);

					        $message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'order-mail.html',$replace_variables);


					$to = $email;
					$from = $this->siteemail;
					$subject = __('payment_made_successfully');	
					$redirect = "add/upgradepackage";	
					if(SMTP == 1)
					{
						include($_SERVER['DOCUMENT_ROOT']."/modules/SMTP/smtp.php");
					}
					else
					{
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						// Additional headers
						$headers .= 'From: '.$from.'' . "\r\n";
						$headers .= 'Bcc: '.$to.'' . "\r\n";
						mail($to,$subject,$message,$headers);	
					}

					        //$mail=array("to" =>$email,"from"=>$this->siteemail,"subject"=>"Payment has been made successfully","message"=>$message);		

					        //$emailstatus=$this->email_send($mail,'smtp');								
					       
                            /* *************************** Mail send to purchased user ends ************** */
				          Message::success(__("payment_completed"));
				          $this->request->redirect('add/upgradepackage');   
                         }   
			 else
			 {
			 		  $message = $_SESSION['paymentresponse']['L_LONGMESSAGE0'];
				          Message::error($message);
				          $this->request->redirect('add/upgradepackage');   
			 }	
  

                    } 
                    else
                    {
                    		//set cancel message
                            Message::success(__("payment_cancelled"));
			                $this->request->redirect('add/upgradepackage');
                    } 

            }else{
            
                    //show msg as do not call direct payment url
                    //redirect to home here
                    $this->request->redirect('add/upgradepackage');
            }
	}

	public function action_withdrawrequest(){			
   
	        //Always check logged user as admin to allow withdraw request
	        $this->is_login();

		if($_SESSION['user_type'] !='A') {	        
	                //Redirect to admin withdraw panel after paypal response
	                $this->request->redirect('/admin/login');
	        }

	       
	        if(Arr::get($_POST, 'pending_req_list')){	     
	                $params = array();           
	                $params += Arr::get($_POST, 'pending_req_list');    
	                
	                $user_paypalac = $this->paypal_db->get_user_paypalac($params);        //params = UNIQUEID
	                // print_r($user_paypalac);exit;
	        }
	        

	        $data = array( 'CURRENCYCODE' => $this->paypal_currencycode, 'EMAILSUBJECT' => __('refund_mailsubj'));

	        if(count($user_paypalac) < 1){
	        
	                Message::error(__('withdraw_need_paypal_acc'));
	                //Redirect to admin withdraw panel after paypal response
	                $this->request->redirect('admin/manage_fund_request/pending');
	        }
	        
	        
	        //SET PAYPAL USER Email Ids as MASS PAY field Require
	        foreach($user_paypalac as $key => $value){

			$receiver_emails = array();
			$receiver_emails_count = 0;
			$masspay_reponse = '';
		     if($value['paypal_account'] == '')
		     {
	                Message::error(__('withdraw_need_paypal_acc'));
	                //Redirect to admin withdraw panel after paypal response
	                $this->request->redirect('admin/manage_fund_request/pending');
	    	     }	
					   
	             $receiver_emails['L_AMT'.$receiver_emails_count] = $value['amount'];
	             $receiver_emails['L_EMAIL'.$receiver_emails_count] = $value['paypal_account'];
	             $receiver_emails['L_UNIQUEID'.$receiver_emails_count] = $value['requested_id'];
	             $receiver_emails_count ++;
	                       


	        // Load the Paypal object
                $paypal = Paypal::instance('MassPay',$this->paypaldbconfig);

	        $masspay_reponse = $paypal->DoMassPayPayment($data,$receiver_emails);
	        
	        //Array ( [TIMESTAMP] => 2013-10-14T07:45:35Z [CORRELATIONID] => 4a4003d249abb [ACK] => Success [VERSION] => 65 [BUILD] => 8021845 )


	      if($masspay_reponse['ACK']!="Success"){
	                
	                Message::success(__('withdraw_failed_msg'));
	                $error_count = 0;
	                foreach($user_paypalac as $key => $value){	 
	                         
	                        if(isset($masspay_reponse['L_ERRORCODE'.$error_count])){
	                        $withdraw_transactionfield = array(
	                              'correlationid' => $masspay_reponse['CORRELATIONID'],
	                              'masscapturetime' => $masspay_reponse['TIMESTAMP'],
	                              'version' => $masspay_reponse['VERSION'],
	                              'build' => $masspay_reponse['BUILD'],
	                              'ack' => $masspay_reponse['ACK'],
	                              'currencycode' => $this->paypal_currencycode,
	                              'amount' => $value['amount'],      
	                              'payer_email' =>$this->paypal_account,
	                              'receiver_email' => $value['paypal_account'],
	                              'requested_id' => $value['requested_id'],   
	                              'errorcode' => $masspay_reponse['L_ERRORCODE'.$error_count],
	                              'short_message' => $masspay_reponse['L_SHORTMESSAGE'.$error_count],
	                              'long_message' => $masspay_reponse['L_LONGMESSAGE'.$error_count],
	                              'severitycode' => $masspay_reponse['L_SEVERITYCODE'.$error_count],
	                              'login_id' => Request::$client_ip,
	                              'user_agent' => Request::$user_agent	              
                                ); 
                                $withdraw_status =  '2';
                                $params = array($value['requested_id']);
                                }else{

                                        $withdraw_transactionfield = array(
	                              'correlationid' => $masspay_reponse['CORRELATIONID'],
	                              'masscapturetime' => $masspay_reponse['TIMESTAMP'],
	                              'version' => $masspay_reponse['VERSION'],
	                              'build' => $masspay_reponse['BUILD'],
	                              'ack' => $masspay_reponse['ACK'],
	                              'currencycode' => $this->paypal_currencycode,
	                              'amount' => $value['amount'],      
	                              'payer_email' =>$this->paypal_account,
	                              'receiver_email' => $value['paypal_account'],
	                              'requested_id' => $value['requested_id'], 
	                              'payment_response' => JSON_ENCODE($masspay_reponse),  
	                              'errorcode' => $masspay_reponse['L_ERRORCODE'.$error_count],
	                              'short_message' => $masspay_reponse['L_SHORTMESSAGE'.$error_count],
	                              'long_message' => $masspay_reponse['L_LONGMESSAGE'.$error_count],
	                              'severitycode' => $masspay_reponse['L_SEVERITYCODE'.$error_count],
	                              'login_id' => Request::$client_ip,
	                              'user_agent' => Request::$user_agent	              
                                );  
                                $withdraw_status =  '1';
                                 $params = array($value['requested_id']);
                                }

                                $this->paypal_db->updatewithdraw_details($params , "2",$value['id'],$value['amount'] );
                                $this->paypal_db->addwithdraw_transaction_details($withdraw_transactionfield );
                                $error_count++;
                                }
                                
                       //$this->paypal_db->addtransactionlog_deatils($transactionlogfield);
	               //set Payment Error Msg
	               //Redirect to admin withdraw panel after paypal response
	                $this->request->redirect('admin/manage_fund_request/pending');
                        }else{
             
	              Message::success(__('withdraw_succesfull_msg'));	      
	              $success_count=0;
	              
	                      foreach($user_paypalac as $key => $value){	
	                      //transaction details
	                      $withdraw_transactionfield = array(
	                                              'correlationid' => $masspay_reponse['CORRELATIONID'],
	                                              'masscapturetime' => $masspay_reponse['TIMESTAMP'],
	                                              'version' => $masspay_reponse['VERSION'],
	                                              'build' => $masspay_reponse['BUILD'],
	                                              'ack' => $masspay_reponse['ACK'],
	                                              'currencycode' => $this->paypal_currencycode,
	                                              'amount' => $value['amount'],   
   
	                                              'payer_email' =>$this->paypal_account,
	                                              'receiver_email' => $value['paypal_account'],
	                                              'requested_id' => $value['requested_id'],   
	                                              'payment_response' => JSON_ENCODE($masspay_reponse),                           
	                                              'login_id' => Request::$client_ip,
	                                              'user_agent' => Request::$user_agent	              
                                                );
                                                //print_r( $withdraw_transactionfield['amount']); exit; 
                                                         
                                                $withdraw_status =  '1';
                                                $params = array($value['requested_id']);
                              	                      
	                     	 //$this->paypal_db->addtransaction_deatils($transactionfield);
	                      	$res = $this->paypal_db->updatewithdraw_details($params , '1',$value['id'],$value['amount'] ); 
 
				$to = $email = $res[0]['email'];
				$name = $res[0]['name'];

			    $orderid=$order_no=$masspay_reponse['CORRELATIONID'];
			    $total_amt = $value['amount'];
			    $product_title = __('amount_credited_account');	
			    $subject =  __('amount_credited_account');					
                            $orderlist='';   
                            $orderlist=' <p>'.__('payment').' : '.$product_title.'</p>'.
                                         '<p>'.__('purchased_date').' : '.$this->currentdate.'</p>'.
					'<p>'.__('total_cost').' : '. CURRENCY.' '.$total_amt.'</p>'.                                      	
                                 
                                        '<p>'.__('paypal_invoice').' : '. $order_no.'</p>';
				
					        $replace_variables=array(REPLACE_LOGO=>EMAILTEMPLATELOGO,REPLACE_SITENAME=>$this->app_name,REPLACE_USERNAME=>ucfirst($name),REPLACE_EMAIL=>$email,REPLACE_SITELINK=>URL_BASE.'users/contactinfo/',REPLACE_SITEEMAIL=>$this->siteemail,REPLACE_SITEURL=>URL_BASE,REPLACE_ORDERID=>$orderid,REPLACE_ORDERLIST=>$orderlist,REPLACE_COPYRIGHTS=>SITE_COPYRIGHT,REPLACE_COPYRIGHTYEAR=>COPYRIGHT_YEAR);

					        $message=$this->emailtemplate->emailtemplate(DOCROOT.TEMPLATEPATH.'fund-order-mail.html',$replace_variables);

                            
					$from = $this->siteemail;

					$redirect = "admin/manage_fund_request/success";	
					if(SMTP == 1)
					{
						include($_SERVER['DOCUMENT_ROOT']."/modules/SMTP/smtp.php");
					}
					else
					{
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						// Additional headers
						$headers .= 'From: '.$from.'' . "\r\n";
						$headers .= 'Bcc: '.$to.'' . "\r\n";
						mail($to,$subject,$message,$headers);	
					}



                 //send mail to user by defining common function variables from here               

			     $this->paypal_db->addwithdraw_transaction_details($withdraw_transactionfield );
	                      
	           }
	            
	              $this->request->redirect('admin/manage_fund_request/success');  
	       } 
	
	   } // For Loop
	}

	public function action_reject_withdraw_request()
	{

		$request_list = $_REQUEST['pending_req_list'];
		//print_r($request_list);exit;
		//reject request
		foreach($request_list as $index=>$val)
		{
			$query = "update ".REQUEST_FUND." set status='3',pay_status='3' where requested_id='$val'";

			$result = Db::query(Database::UPDATE, $query)
	   			 ->execute();
		}
		Message::error(__('request_rejected'));	      
		$this->request->redirect("admin/manage_fund_request/pending/");
	}

}
?> 
