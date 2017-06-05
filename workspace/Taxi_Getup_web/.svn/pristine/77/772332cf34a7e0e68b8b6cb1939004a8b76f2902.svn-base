<?php defined('SYSPATH') OR die("No direct access allowed."); ?>
<?php $select_pack_id =''; 
$text_credit_card     = 'Credit Card';
$text_paypal='Paypal Express checkout';

$months = array();
	
for ($i = 1; $i <= 12; $i++) {
	$months[] = array(
		'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)), 
		'value' => sprintf('%02d', $i)
	);
}

$today = getdate();

$year_valid = array();

for ($i = $today['year'] - 10; $i < $today['year'] + 1; $i++) {	
	$year_valid[] = array(
		'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)), 
		'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
	);
}

$year_expire = array();

for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
	$year_expire[] = array(
		'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
		'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
	);
}
	
?>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>

<!--- Package Information -->
<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle"> 
<form name="purchase_form" id="purchase_form" action="" class="form" method="post" --onsubmit="chk_paytype();" >
			<p><input type="hidden" name="upgrade_type" value='1' checked></p>
			<!--<p><input type="radio" name="upgrade_type" value='2'><?php echo __('renew'); ?></p>-->
                        <p style="margin-left:20px;margin-top:20px;"><?php echo __('select_package'); ?></p>
			<?php 
			$i = 1;
			$package_count = count($package_details);
			foreach($package_details as $package_list) { ?>

                                <p>
                                <input type="radio" name="pack" id="pack_<?php echo $package_list['package_id']; ?>" value="<?php echo $package_list['package_id']; ?>" <?php if($select_pack_id){ echo 'CHECKED'; } elseif ($i == 1) { echo 'CHECKED'; }?>/>
                                <strong><?php echo wordwrap(ucfirst($package_list['package_name']),30,'<br/>',1); ?></strong>
                                <a href="javascript:;" id="view_<?php echo $package_list['package_id']; ?>" onclick="show_detail('<?php echo $i; ?>','<?php echo $package_count; ?>')" title="<?php echo __('details'); ?>"><?php echo '('.__('details').')'; ?></a>
                                </p>
                                <div class="clr mt10" id="pack_det_<?php echo $i; ?>" style="display:none;padding:20px;">
                                <table border="0" cellpadding="5" cellspacing="0" width="100%">      
				<tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('package_name').' :'.'</h1>'; ?></td>
                                <td><?php echo wordwrap(ucfirst($package_list['package_name']),30,'<br/>',1); ?></td>
                                </tr>
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('package_description').' :'.'</h1>'; ?></td>
                                <td><?php echo wordwrap(ucfirst($package_list['package_description']),30,'<br/>',1); ?></td>
                                </tr>
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('package_price').' :'.'</h1>'; ?></td>
                                <td><p id="price_pack_<?php echo $package_list['package_id']; ?>"><?php echo $package_list["package_price"];?></p></td>
                                </tr>
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('no_of_taxi').' :'.'</h1>'; ?></td>
                                <td><?php echo $package_list["no_of_taxi"];?></td>
                                </tr>
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('no_of_driver').' :'.'</h1>'; ?></td>
                                <td><?php echo $package_list["no_of_driver"];?></td>
                                </tr> 
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('no_of_days_expire').' :'.'</h1>'; ?></td>
                                <td><?php echo $package_list["days_expire"];?></td>
                                </tr>
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('package_type').' :'.'</h1>'; ?></td>
                                <td><?php if($package_list['package_type'] == 'T' ) { echo __('transaction_based_commission'); } else if($package_list['package_type'] == 'P' ) { echo __('package_based_commission'); } else if($package_list['package_type'] == 'N' ) { echo __('package_based_no_commission'); } ?></td>
                                </tr>
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('driver_tracking').' :'.'</h1>'; ?></td>
                                <td><?php if($package_list['driver_tracking'] == 'N' ) { echo __('no'); } else { echo __('yes'); } ?></td>
                                </tr>
                                </table>
                                </div>
                               <?php 
                               $i++;
                               } ?> 

     
		<div id="hide_gateway">
                  <div class="fl clr mt30">
                          <p><?php echo __('select_gateway'); ?></p>
			    <?php
				$query2 = "SELECT * FROM payment_modules where pay_mod_active='1' and pay_mod_id!='1' and pay_mod_id!='3' order by pay_mod_name asc";

				$result = Db::query(Database::SELECT, $query2)
						->execute()
						->as_array();

				foreach($result as $resultset) { 
				?>

                                <p> <span><input type="radio" name="payment_type" id="payment_type" <?php if($resultset['pay_mod_default']=='1'){ echo 'checked="checked"'; } ?> <?php if($resultset['pay_mod_name'] == 'Credit Card Using Paypal' ) { ?> onclick="chk_paytype('creditcard');" <?php } if($resultset['pay_mod_name'] == 'Paypal' ) { ?> onclick="chk_paytype('paypal');" <?php } ?>  /> <strong><?php echo $resultset['pay_mod_name']; ?></strong></span> </p>
			
				<?php if($resultset['pay_mod_default']== 1) { ?> 

					<input type="hidden" name="default_gateway" id="default_gateway" value="<?php if($resultset['pay_mod_name'] == 'Credit Card Using Paypal' ) {  echo 'creditcard'; } else if($resultset['pay_mod_name'] == 'Paypal' ) { echo 'paypal'; }?>" > <?php } ?>

				<?php } ?>
                  </div>              


		<div class="fl clr">			
			<div id="creditcard_details" class="fl clr mt30">
	
                              <table width="100%" cellpadding="5" cellspacing="0" border="0" class="fl clr" style="margin-left:15px;">
				<tr> <th colspan="2" align="left"><h2><?php echo __('card_details'); ?></h2></th></tr>
				<tr><td>
                                        <label class="fl" style="width: 100px;text-align: right;"><?php echo __('card_no'); ?> :</label>
				</td><td>
				<div class="new_input_field">
				<input type="text" size="19" maxlength="19" id="creditCardNumber" name="creditCardNumber" class="required digits fl" title="<?php echo __('card_no_req'); ?>">
				</div>
			</td></tr>
			<tr><td>
			<label class="fl" style="width: 100px;text-align: right;"><?php echo __('expiration_date'); ?> :</label></td>
			<td>
			<select name="cc_expire_date_month">
			<?php foreach ($months as $month) { ?>
			<option value="<?php echo $month['value']; ?>" <?php if(date("n",time()) == $month['value']){echo "Selected";}?> > <?php echo $month['value']; ?></option>
			<?php } ?>
			</select>
			<select name="cc_expire_date_year">
			<?php foreach ($year_expire as $year) { ?>
			<option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
			<?php } ?>
			</select>                                        
			</td></tr>
			<tr><td>
			<label class="fl" style="width: 100px;text-align: right;"><?php echo __('card_verification_no'); ?> :</label></td>
			<td>
			<div class="new_input_field">
	            <input type="text" size="3" maxlength="4" id="cvv2Number" name="cvv2Number" value="" class="width50 required digits fl" title="<?php echo __('card_vno_req'); ?>">    
			</div>
 
			</td></tr>
			</table>
	</div>
	</div>
	</div>
	 <table width="100%" cellpadding="5" cellspacing="0" border="0" class="fl clr" style="margin-left:15px;">		
			 
	   <tr>
			<td>&nbsp;</td>
                        <td colspan="">
                            <br />
                    
                           <div class="button greenB">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_upgradepackage" title="<?php echo __('submit' );?>" /></div>
                            <div class="clr">&nbsp;</div>
                        </td>
                    </tr> 			
			
			</table>    
                    </div>                				
			</div>

        </form>
<script type="text/javascript">
$(document).ready(function(){


$("#purchase_form").validate();
$("#creditcard_details").hide();
chk_paytype();

	var pack_check = $("input[type=radio][name='pack']:checked").val();
	
	var pack_value = $('#price_pack_'+pack_check).html();
	if(pack_value == 0)
	{
		$('#hide_gateway').hide();
		$("#creditCardNumber").removeClass("required");
		$("#cvv2Number").removeClass("required");
		document.forms["purchase_form"].action = "<?php echo URL_BASE;?>add/upgradepackage";
	}

});

$("input:radio").click(function(){

var name = $(this).attr("id");

if(name != 'payment_type')
{
    if($("[id="+name+"]:checked").length == 1)
    {
	var package_value = $('#price_'+name).html();
	if(package_value > 0)
	{
		$('#hide_gateway').show();
	}
	else
	{
		$('#hide_gateway').hide();
		$("#creditCardNumber").removeClass("required");
		$("#cvv2Number").removeClass("required");
		document.forms["purchase_form"].action = "<?php echo URL_BASE;?>add/upgradepackage";	
	}
    }    
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
});

function show_detail(id)
{
        $("#pack_det_"+id).slideToggle("show");
} 

function chk_paytype(payment_type)
{ 
	var paypal = $("#paypal").is(":checked");	
	var creditcard = $("#creditcard").is(":checked");
	
	if(payment_type ===undefined)
	{
		payment_type = $('#default_gateway').val();
	}

	var pack_check = $("input[type=radio][name='pack']:checked").val();
	
	var pack_value = $('#price_pack_'+pack_check).html();
	if(pack_value == 0)
	{
		$('#hide_gateway').hide();
		$("#creditCardNumber").removeClass("required");
		$("#cvv2Number").removeClass("required");
		document.forms["purchase_form"].action = "<?php echo URL_BASE;?>add/upgradepackage";
	}

	if(payment_type =='paypal'  && pack_value != 0)
	{	
		$('#creditcard_details').hide('fast');	
		$("#creditCardNumber").removeClass("required");
		$("#cvv2Number").removeClass("required");
		document.forms["purchase_form"].action = "<?php echo URL_BASE;?>payment/pay";
	}
	else if(payment_type == 'creditcard'  && pack_value != 0)
	{	  
		$('#creditcard_details').show('slow');
		$("#creditCardNumber").addClass("required");
		$("#cvv2Number").addClass("required");	  
		document.forms["purchase_form"].action = "<?php echo URL_BASE;?>payment/dodirectpayment";
	}
}

function show_detail(id,tot)
{
	for(var i=1; i <= tot; i++)
	{  
		var isVisible = $("#pack_det_"+i).is(":visible");
		
		if(id==i)
		{
			$("#pack_det_"+i).slideToggle("show");
		}
		else
		{	
			if(isVisible)
			{
				$("#pack_det_"+i).slideToggle("hide");
			}
		}
	}
} 
   
</script>

