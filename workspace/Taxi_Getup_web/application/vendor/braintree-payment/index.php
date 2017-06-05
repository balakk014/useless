<?php //include("braintree.php"); 
 //$clientToken = Braintree_ClientToken::generate();

?>
<html>
  <head>
	 <script src="https://js.braintreegateway.com/v1/braintree.js"></script>
	 	 <!--<script src="jquery-1.7.2.min.js"></script>
	 	 <script>
			 $(document).ready(function()
			 {
				 //alert('dd');
				 $('#button').click(function(e)
				 {
					 //alert('button clicked');
					 
					 var firstName=$('#firstName').val();
					 var lastName=$('#lastName').val();
					 var lastName=$('#lastName').val();
					 var amount=$().val('#amount').val();
					 var lastName=$('#lastName').val();
					 var lastName=$('#lastName').val();
					 var amount=$().val('#amount').val();
					 //alert(firstName);
					 $.ajax({
								type: "POST",
								url: "braintree.php",
								data: 'firstName='+firstName+ '&lastName=' + lastName+'&amount=' + amount,
								success: function(msg) {
									alert(msg);
									
									
								}
								
								
								
								
							});
												 
					 
					 
					 
					 
					 
				 });
			 });
	 	 </script>!-->
<script type="text/javascript">
function numbersonly(e){
var unicode=e.charCode? e.charCode : e.keyCode
if (unicode!=8){ //if the key isn't the backspace key (which we should allow)
if (unicode<48||unicode>57) //if not a number
return false //disable key press
}
}
</script>
<style>
.notice{ color:#eee}
</style>

  </head>
  <body>
    <h1>Braintree Credit Card Transaction Form</h1>
   <div id="paypal-container">
 
		    <form action="braintree.php" method="POST" id="braintree-payment-form">
        
       <table>
		   
	       <tr><td><label>First Name</label></td>
          <td><input type="text" name="firstName" value="" maxlength="20" id="firstName" required="required" /></td></tr>
           <tr><td><label>Last Name</label></td>
          <td><input type="text" name="lastName" value="" maxlength="20" id="lastName" required="required" /></td></tr>
          
          <tr><td><label>Company Name</label></td>
          <td><input type="text" name="company" value="" maxlength="50" required="required" /></td></tr>
          <tr><td><label>Amount</label></td>
          <td><input type="text" name="amount" value="" maxlength="20" id="amount" required="required" onkeypress="return numbersonly(event)" /></td></tr>
          <tr><td><label>Email</label></td>
          <td><input type="text" name="email" value="" maxlength="50" required="required" id="email"/></td></tr>
           <tr><td><label>Phone</label></td>
          <td><input type="text" name="phone" value="" maxlength="12" required="required" onkeypress="return numbersonly(event)" /></td></tr>
          
          <tr><td><label>Card Number</label></td>
          <td><input type="text" size="20" autocomplete="off" name="creditCard" data-encrypted-name="creditCard" maxlength="20" required="required" placeholder="4111111111111111" onkeypress="return numbersonly(event)" /><label><span class='notice'>Default:4111111111111111</span></label></td></tr>
      
       
          <tr><td><label>CVV</label></td>
          <td><input type="text" size="4" maxlength="4" autocomplete="off"  name="cvv"  data-encrypted-name="cvv"  required="required" placeholder="111"/></td></tr>
              
          <tr><td><label>Expiration (MM/YYYY)</label></td><td>
          <input type="text" size="2" name="month"  maxlength="2" required="required" placeholder="05"/> / <input type="text" size="4" maxlength="4"  name="year" required="required" placeholder="2015"/></td></tr>
                </table>
        <input type="submit" id="submit" name="submit" value="submit" />
  
      </form>
      </table>
    </div>
   <script>
      var braintree = Braintree.create("YourClientSideEncryptionKey");
      braintree.onSubmitEncryptForm('braintree-payment-form');
    </script>
  </body>
</html>


<?php
/*$result = Braintree_Customer::create(array(
    'firstName' => $_POST['firstName'],
    'lastName' => $_POST['lastName'],
    'company' => $_POST['company'],
    'email' =>$_POST['email'],
    'phone' => $_POST['phone']
    
));
/*if(isset($_POST['submit']))
{
	echo "ddd";
	
	 $result = Braintree_Customer::create(array(
    'firstName' => $_POST['firstName'],
    'lastName' => $_POST['lastName'],
    'company' => $_POST['company'],
    'email' =>$_POST['email'],
    'phone' => $_POST['phone']
));


}*/

?>
