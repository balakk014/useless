<?php 
		if(is_array($mail))
		{
			$this->siteusers=Model::factory('siteusers');
			if($this->array_keys_exists($mail,array('to','from','subject','message')))
			{
				$to=$mail['to'];
				$from=$mail['from'];
				$subject=$mail['subject'];
				$message=$mail['message'];
				$headers = 'MIME-Version: 1.0'. "\r\n";
				$headers .= 'Content-type:text/html;charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '. $from . "\r\n";
				switch($type)
				{		
					 case "smtp":
					 $this->siteusers=Model::factory('siteusers');
					 $smtp_detail=$this->siteusers->get_smtpdetails();
					 $smtp_config="";
					 if(isset($smtp_detail[0]))
					 {
						$host= $smtp_detail[0]['smtp_host'];
						$username=$smtp_detail[0]['smtp_username'];
						$password=$smtp_detail[0]['smtp_password'];
						$port=$smtp_detail[0]['smtp_port'];
						$smtp_config = array('driver' => 'smtp','options' => array('hostname'=>$host,
									'username'=>$username,'password' =>$password,
									'port' => $port,'encryption' => 'ssl')); 	
					 }		

					 $smtp_config1 = array('driver' => 'smtp','options' => array('hostname'=>'smtp.gmail.com',
								  'username'=>'johnjoeshep@gmail.com','password' =>'test@123',
									'port' => '465','encryption' => 'ssl'));
														
					/* $smtp_config = array('driver' => 'smtp','options' => array('hostname'=>'smtp.gmail.com',
								'username'=>'johnjoeshep@gmail.com','password' =>'test@123',
								'port' => '465','encryption' => 'ssl')); */ 						
					
						//mail sending option here
						//print_r($mail);
						//exit;
						try{
							if(Email::connect($smtp_config1))
							{                         	                      
								if(Email::send($to,$from,$subject,$message,$html = $htmlneed)==0)
								{                                  		                
									return 1;
								}
								return 0;
							}
						}
						catch(Throwable $e)
						{
							try{
								if(mail($to, $subject,$message,$headers))
								{                               
									return 1;
								}
							}
							catch(Throwable $e)
							{
								return 0;
							}
						}						
						break;
					default:
						if(mail($to, $subject,$message,$headers))
						{                               
							return 1;
						}
						break;
				}
			}
			else
			{
				return 2;
			}
		}
?>
