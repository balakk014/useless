<?php defined('SYSPATH') OR die("No direct access allowed."); ?>

<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL_BASE;?>public/css/jquery.autocomplete.css" />
<script type="text/javascript" src="<?php echo URL_BASE; ?>public/js/jquery.autocomplete.js"></script>

<div class="container_content fl clr" style="min-height:170px;">
    <div class="cont_container mt15 mt10">
       <div class="content_middle">
         <form name="editaccounts_form" class="form" id="editaccounts_form" action="" method="post" enctype="multipart/form-data">
           <table border="0" cellpadding="5" cellspacing="0" width="100%">
           <tr>
           <td valign="top" width="20%"><label><?php echo __('account_name'); ?></label><span class="star">*</span></td>        
			   <td>

				   <div class="new_input_field">
					  <input type="text" title="<?php echo __('enter_account_name'); ?>" name="account_name" id="account_name" class="required" value="<?php echo isset($account_details[0]['account_name']) &&!array_key_exists('account_name',$postvalue)? trim($account_details[0]['account_name']):$postvalue['account_name']; ?>"  maxlength="150" minlength="4" />
					  <?php if(isset($errors) && array_key_exists('account_name',$errors)){ echo "<span class='error'>".__('enter_account_name')."</span>";}?>
					<input name="passenger_id" id="passenger_id" type="hidden" value="<?php echo $account_details[0]['id']; ?>" >
				   </div>
				</td>
		  </tr>
         
         <tr>
           <td valign="top" width="20%"><label><?php 
           $company_currency = findcompany_currency($_SESSION['company_id']);
           echo __('limit').'('.$company_currency.')'; ?></label></td>        
			   <td>

				   <div class="new_input_field">
					  <input type="text" title="<?php echo __('enter_account_limit'); ?>" name="account_limit" id="account_limit" value="<?php echo isset($account_details[0]['limit']) &&!array_key_exists('limit',$postvalue)? trim($account_details[0]['limit']):$postvalue['account_limit']; ?>"  maxlength="7" autocomplete="off" />
					  <?php if(isset($errors) && array_key_exists('limit',$errors)){ echo "<span class='error'>".__('enter_account_limit')."</span>";}?>
				   </div>
				</td>
		  </tr>
		  <tr>
           <td valign="top" width="20%"><label><?php echo __('discount_passenger_amount'); ?></label><span class="star">*</span></td>        
			   <td>
				   <div class="new_input_field">
					  <input type="text" title="<?php echo __('enter_discounts_passenger'); ?>" name="account_discount" id="account_discount" class="required" value="<?php echo isset($account_details[0]['account_discont']) &&!array_key_exists('discount',$postvalue)? trim($account_details[0]['account_discont']):$postvalue['account_discount']; ?>"  maxlength="3" autocomplete="off" />
					  <?php if(isset($errors) && array_key_exists('discount',$errors)){ echo "<span class='error'>".__('enter_discounts_passenger')."</span>";}?>
				   </div>
				</td>
		  </tr>


			<tr>
				<td>
					<div class="title">
						<h6><?php echo __('admin_user'); ?></h6>
					</div>
				</td>
			</tr>
			<tr>
           <td valign="top" width="20%"><label><?php echo __('passenger_name'); ?></label><span class="star">*</span></td>        
			   <td>

				   <div class="new_input_field">
					  <input type="text" title="<?php echo __('enter_passenger_name'); ?>" name="firstname" id="account_firstname" class="required" value="<?php echo isset($account_details[0]['name']) &&!array_key_exists('firstname',$postvalue)? trim($account_details[0]['name'].' - ('.$account_details[0]['phone'].')'):$postvalue['firstname']; ?>"  minlength="4" autocomplete="off" />
					  <?php if(isset($errors) && array_key_exists('name',$errors)){ echo "<span class='error'>".__('enter_passenger_name')."</span>";}?>
				   </div>
				</td>
		  </tr>
		  <tr>
           <td valign="top" width="20%"><label><?php echo __('passenger_email'); ?></label><span class="star">*</span></td>        
			   <td>

				   <div class="new_input_field">
					  <input type="text" title="<?php echo __('enteremailaddress'); ?>" name="email" id="account_email" class="required email" value="<?php echo isset($account_details[0]['email']) &&!array_key_exists('email',$postvalue)? trim($account_details[0]['email']):$postvalue['email']; ?>"  maxlength="50" minlength="4" autocomplete="off" />
					  <?php if(isset($errors) && array_key_exists('email',$errors)){ echo "<span class='error'>".__('enteremailaddress')."</span>";}?>
				   </div>
				</td>
		  </tr>
		  <tr>
           <td valign="top" width="20%"><label><?php echo __('passenger_phone'); ?></label><span class="star">*</span></td>        
			   <td>

				   <div class="new_input_field">
					  <input type="text" title="<?php echo __('entermobileno'); ?>" name="phone" id="account_phone" class="required number" value="<?php echo isset($account_details[0]['phone']) &&!array_key_exists('phone',$postvalue)? trim($account_details[0]['phone']):$postvalue['phone']; ?>"  maxlength="15" minlength="4" autocomplete="off" />
					  <?php if(isset($errors) && array_key_exists('phone',$errors)){ echo "<span class='error'>".__('entermobileno')."</span>";}?>
				   </div>
				</td>
		  </tr>


	<tr>
	<td>&nbsp;</td>
	<td colspan="" class="star">*<?php echo __('required_label'); ?></td>
	</tr>                         
                    <tr>
			<td>&nbsp;</td>
                        <td colspan="">
                            <br />
                    
                            <div class="button blackB">     <input type="button" value="<?php echo __('button_back'); ?>" onclick="window.history.go(-1)" /></div>
                            <div class="button dredB">   <input type="reset" value="<?php echo __('button_reset'); ?>" title="<?php echo __('button_reset'); ?>" /></div>
                            <div class="button greenB">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_editaccount" title="<?php echo __('submit' );?>" /></div>
                            <div class="clr">&nbsp;</div>
                        </td>
                    </tr> 

                </table>
                

        </form>
        </div>
        <div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt"></div></div>
    </div>
</div>    

<!-- Groups -->

<?php 	$link_button='<input type="button"  title="'.__('add_new').'" class="button" value="'.__('add_new').'" onclick="location.href=\''.URL_BASE.'tdispatch/add_groups'.'/'.$account_details[0]['aid'].'\'" />';
?>

    <div class="cont_container mt15 mt10">
       <div class="content_middle">  
			<div class="widget">
				<div class="title"><img src="<?php echo IMGPATH; ?>icons/dark/frames.png" alt="" class="titleIcon" /><h6><?php echo __('groups'); ?></h6>
				<div style="width:auto; float:right; margin:0 3px;">
				<div class="button greyishB"><?php echo $link_button; ?></div>                       

			</div>   
			</div>    
<div class= "overflow-block">			
<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive" id="changetr">
<?php if(count($account_groupdetails) > 0){ ?>
<thead>	
<tr>
	<td align="center" ><?php echo __('sno_label'); ?></td>
	<td align="center" ><?php echo __('group_name'); ?></td>
	<td align="center" ><?php $company_currency = findcompany_currency($_SESSION['company_id']); echo __('limit').'('.$company_currency.')'; ?></td>
	<td align="center" ><?php echo __('status'); ?></td>
	<td align="center" ><?php echo __('action'); ?></td>
</tr>
</thead>
<tbody>
           <?php $i=1; 
		 foreach($account_groupdetails as $result) { 
		$trcolor=($i%2==0) ? 'oddtr' : 'eventr';  			 
			 ?>	
				<tr class="<?php echo $trcolor; ?>" id="addtr_<?php echo $result['aid']; ?>">
					<td align="center"><?php echo $i; ?></td>
					<td align="center"><?php echo $result['department']; ?></td>
					<td align="center"><?php echo $result['limit']; ?></td>
					<td align="center"><?php if($result['status']=='1')	{  $txt = "Activate"; $class ="unsuspendicon";    }
					else{  $txt = "Deactivate"; $class ="blockicon"; } echo '<a href="javascript:void(0);" title ='.$txt.' class='.$class.'></a>' ;  ?></td>
					<td align="center" width="30%"><a href="<?php echo URL_BASE.'tdispatch/edit_groups/'.$result['gid'];?>" title ="<?php echo __('edit'); ?>" class="editicon" >

				<a href="javascript:;" title ="<?php echo __('status'); ?>" class="<?php echo $class; ?>" onclick="change_groups_status('<?php echo $result['aid']; ?>','<?php echo $result['gid']; ?>','<?php echo $result['status']; ?>')"></a>
				<?php /*
				<a href="javascript:;" title ="<?php echo __('delete'); ?>" class="deleteicon" onclick="delete_groups('<?php echo $result['gid']; ?>')"></a>
				*/ ?>
				</td>
				</tr>
	<?php $i++; } ?>

	<?php }else { ?>
			<tr>
				<td colspan="3" align="center"><?php echo __('no_data');?></td>
			</tr>
	<?php } ?>
</tbody>	
</table>	
        </div>
	</div>


<!-- Groups -->

<!-- Users -->

<?php 	$link_button='<input type="button"  title="'.__('add_new').'" class="button" value="'.__('add_new').'" onclick="location.href=\''.URL_BASE.'tdispatch/add_users'.'/'.$account_details[0]['aid'].'\'" />';
?>
<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle">  
			<div class="widget">
				<div class="title"><img src="<?php echo IMGPATH; ?>icons/dark/frames.png" alt="" class="titleIcon" /><h6><?php echo __('users'); ?></h6>
				<div style="width:auto; float:right; margin:0 3px;">
				<div class="button greyishB"><?php echo $link_button; ?></div>                       

			</div>   
			</div>    
<div class= "overflow-block">			
<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive" id="changetr">
<?php if(count($account_userdetails) > 0){ ?>
<thead>	
<tr>
	<td align="center" ><?php echo __('sno_label'); ?></td>
	<td align="center" ><?php echo __('name'); ?></td>
	<td align="center" ><?php echo __('email'); ?></td>
	<td align="center" ><?php echo __('phone'); ?></td>
	<td align="center" ><?php echo __('group_name'); ?></td>
	<td align="center" ><?php echo __('action'); ?></td>
</tr>
</thead>
<tbody>
           <?php $i=1; 

		 foreach($account_userdetails as $key => $result) { 
		$trcolor=($i%2==0) ? 'oddtr' : 'eventr';  			 
			 ?>	
				<tr class="<?php echo $trcolor; ?>" id="addtr_<?php echo isset($key)?$key:''; ?>">
					<td align="center"><?php echo $i; ?></td>
					<td align="center"><?php echo $result['name']; ?></td>
					<td align="center"><?php echo $result['email']; ?></td>
					<td align="center"><?php echo $result['phone']; ?></td>
					<td align="center"><?php echo $result['department']; ?></td>
					<td align="center" width="30%"><a href="<?php echo URL_BASE.'tdispatch/edit_users/';?><?php echo isset($key) ? $key : ''; ?>" title ="<?php echo __('edit'); ?>" class="editicon" ><a href="javascript:;" title ="<?php echo __('delete'); ?>" class="deleteicon" onclick="delete_groupuser('<?php echo isset($key) ? $key : ''; ?>')"></a></td>
				</tr>
	<?php $i++; } ?>

	<?php }else { ?>
			<tr>
				<td colspan="3" align="center"><?php echo __('no_data');?></td>
			</tr>
	<?php } ?>
</tbody>	
</table>	
        </div>
	</div>
</div>
</div>
</div>
</div>
<!-- Users -->
<script type="text/javascript">
$(document).ready(function(){

$("#location_name").focus(); 
$("#editaccounts_form").validate();
});

$("#account_firstname").autocomplete("<?php echo URL_BASE; ?>tdispatch/firstname_load", {
});

$("#account_email").autocomplete("<?php echo URL_BASE; ?>tdispatch/email_load", {
});

$("#account_phone").autocomplete("<?php echo URL_BASE; ?>tdispatch/phone_load", {
});

$('#account_firstname').change(function(){
	$('#passenger_id').val('');
	$('#account_email').removeAttr('readonly');
	$('#account_phone').removeAttr('readonly');
});

$('#account_email').change(function(){
	$('#passenger_id').val('');
	$('#account_firstname').removeAttr('readonly');
	$('#account_phone').removeAttr('readonly');
});

$('#account_phone').change(function(){
	$('#passenger_id').val('');
	$('#account_email').removeAttr('readonly');
	$('#account_firstname').removeAttr('readonly');
});

function delete_groups(group_id)
{

	var cancel_Submit = confirm("<?php echo __('delete_alert_adminjob'); ?>");				
	if(cancel_Submit == true)
	{
		document.location.href="<?php echo URL_BASE; ?>tdispatch/delete_groups/"+group_id;
	}

}

function change_groups_status(account_id,group_id,status)
{

	var cancel_Submit = confirm("<?php echo __('want_change_status'); ?>");				
	if(cancel_Submit == true)
	{
		document.location.href="<?php echo URL_BASE; ?>tdispatch/change_groups_status?group_id="+group_id+"&status="+status+"&account_id="+account_id;
	}

}


function delete_groupuser(key_id)
{

	var cancel_Submit = confirm("<?php echo __('delete_alert_adminjob'); ?>");				
	if(cancel_Submit == true)
	{
		document.location.href="<?php echo URL_BASE; ?>tdispatch/delete_groupuser/"+key_id;
	}

}

function check_passengerexit()
{

	var passenger_id = $('#passenger_id').val();
	var email = $('#account_email').val();
	var phone = $('#account_phone').val();

	var SrcPath = '<?php echo URL_BASE; ?>';
	var url= SrcPath+"tdispatch/checkemailuserdetails/";
	var dataS = "email="+email+"&passenger_id="+passenger_id;		

	$.ajax
	({ 			
		type: "POST",
		url: url, 
		data: dataS, 
		async:false,
		cache: false, 
		dataType: 'html',
		success: function(response) 
		{ 	
			if(trim(response) != 'N')
			{		
				$('#uemailavilable').html('');
				$('#uemailavilable').html(trim(response));
			}
			else
			{
				$('#uemailavilable').html('');
			}	
		} 
		 
	});



	var url= SrcPath+"tdispatch/checkphoneuserdetails/";
	var dataS = "phone="+phone+"&passenger_id="+passenger_id;	
	
	$.ajax
	({ 			
		type: "POST",
		url: url, 
		data: dataS, 
		cache: false, 
		async:false,
		dataType: 'html',
		success: function(response) 
		{ 	
			if(trim(response) != 'N')
			{		
				$('#uphoneavilable').html('');
				$('#uphoneavilable').html(trim(response));
			}
			else
			{
				$('#uphoneavilable').html('');
			}	
		} 
		 
	});



	
	var uemailavilable = $('#uemailavilable').html();
	var uphoneavilable = $('#uphoneavilable').html();

	if(uphoneavilable == "" && uemailavilable == "")
	{
		return true;
	}	
	else
	{
		event.preventDefault();	
		return false;
	}


}

</script>
