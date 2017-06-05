<?php


require_once 'lib/Braintree.php';

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('hv6s4t6xqjw2wnqd');//your_merchant_id
Braintree_Configuration::publicKey('q2kmqd9ncttjvwgy');//your_public_key
Braintree_Configuration::privateKey('0d70778ec56f713f86d4b110098041e5');//your_private_key

				/*******************************************DB Connection************************************************/
											$dbusername = 'root';
											$dbpass = 'ndot';
											$dbhost = 'localhost';
											$con=mysql_connect($dbhost,$dbusername,$dbpass)or die('server error');
											$conn=mysql_select_db('kohana_study',$con) or die('db error');
											
				/********************************************************************************************/


 $amount=$_POST['amount'];
 $firstName=$_POST['firstName'];
  $lastName=$_POST['lastName'];
  $company=$_POST['company'];
  $phone=$_POST['phone'];
   $cnumber=$_REQUEST['creditCard'];
  $ccv=$_POST['cvv'];
  $expirationMonth=$_POST['month'];
  $expirationYear=$_POST['year'];
  //$email=$_POST['email'];
   //$email=$_POST['email'];

 
 



/*if($_POST)
{
	   $firstName=$_POST['firstName'];
	    $lastName=$_POST['lastName'];
	   
	   
	    $sql = mysql_query("INSERT INTO brain_tree ( api_id, status) VALUES ( '".$firstName."', '".$lastName."')");
	 
	    
}*/

 
 // $firstName=$_POST['firstName'];
 
 /*$result = Braintree_Customer::create(array(
    'firstName' => 'yuva',
    'lastName' => 'samy',
    'creditCard' => array(
        'number' => '4111111111111111',
        'expirationMonth' =>'08',
        'expirationYear' => '2015',
        'cvv' => '111',
        'billingAddress' => array(
            'postalCode' => '438223'
        )
    )
));*/




$result = Braintree_Transaction::sale(array(

      'amount' => $amount,
            
    'creditCard' => array(
        
        'cardholderName' => $firstName,
        'number' => '4111111111111111',//$_POST['creditCard']
        'expirationMonth' =>'08',//$_POST['month']
        'expirationYear' => '2015',//$_POST['year']
        'cvv' => '111',
    ),
    
    'customer' => array(
    'firstName' => $firstName,
    'lastName' => $lastName,
    'company' => $company,
    'phone' => $phone,
    'fax' => '123456789',
    'website' => 'http://www.mywebsite.com',
    'email' => $email
  ),
    
    'shipping' => array(
    'firstName' => 'yuva',
    'lastName' => 'raj',
    'company' => 'Ndot',
    'streetAddress' => 'Vadavalli',
    'extendedAddress' => '3rd Extension',
    'locality' => 'Chennai',
    'region' => 'IND',
    'postalCode' => '60103',
    'countryCodeAlpha2' => 'IN'
  )
    
    
    
    
    
    
    
    
    
));






 /*$result = Braintree_Customer::create(array(
    'firstName' => $_POST['firstName'],
    'lastName' => $_POST['lastName'],
    'company' => $_POST['company'],
    'email' =>$_POST['email'],
    'phone' => $_POST['phone']
    //'amount' => $amount
    
    
    )
));
print_r($result);*/



/*$result = Braintree_Transaction::sale(array(
    'amount' => $_POST['amount'],
    'creditCard' => array(
        'number' =>  $_POST['creditCard'],
        'cvv'=> $_POST['cvv'],
        'expirationMonth'=>$_POST['month'],
        'expirationYear' => $_POST['year']
    )
));*/




if ($result->success) {
    //print_r( $api=$result->transaction->id);
     //print_r($status=$result->transaction->processorResponseCode);
     echo $api=$result->transaction->id;echo"<br>";
     echo $status=$result->transaction->processorResponseCode;
     
     $sql = mysql_query("INSERT INTO brain_tree ( api_id, status) VALUES ( '".$api."', '".$status."')");
     echo"<br>";
     echo"values inserted success";
     //print_r($sql);
   
    
    } else if ($result->transaction) {
    print_r("Error processing transaction:");
    	
    print_r("\n  code: " . $result->transaction->processorResponseCode);
    print_r("\n  text: " . $result->transaction->processorResponseText);
} else {
	
    print_r("Validation errors: \n");
    print_r($result->errors->deepAll());
    
}

?>
