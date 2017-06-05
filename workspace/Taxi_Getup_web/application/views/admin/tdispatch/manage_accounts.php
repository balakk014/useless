<?php defined('SYSPATH') OR die("No direct access allowed."); ?>

<?php 	$link_button='<input type="button"  title="'.__('add_accounts').'" class="button" value="'.__('add_accounts').'" onclick="location.href=\''.URL_BASE.'tdispatch/add_accounts'.'\'" />';
?>
<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle">
		   <form method="get" class="form" name="accounts" id="accounts">
			<div class="widget">
				<div class="title"><img src="<?php echo IMGPATH; ?>icons/dark/frames.png" alt="" class="titleIcon" /><h6><?php echo __('accounts'); ?></h6>
				<div style="width:auto; float:right; margin: 0 3px;">
				<div class="button greyishB"><?php echo $link_button; ?></div>                       

			</div>   
			</div>    
<?php if(count($all_accounts) > 0){ ?> <div class= "overflow-block"> <?php } ?>
<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive" id="changetr">
<?php if(count($all_accounts) > 0){ ?>
<thead>	
<tr>
	<td align="center" width="5%"></td>
	<td align="center" ><?php echo __('sno_label'); ?></td>
	<td align="center" ><?php echo __('account_name'); ?></td>
	<td align="center" ><?php 
	$company_currency = findcompany_currency($_SESSION['company_id']);
	echo __('limit').'('.$company_currency.')'; ?></td>
	<td align="center" ><?php echo __('discount'); ?></td>
	<td align="center" width="10%"><?php echo __('status_label'); ?></td>  
	<td align="center" ><?php echo __('action'); ?></td>
</tr>
</thead>
<tbody>
           <?php $i=1; 
		 foreach($all_accounts as $result) { 
		$trcolor=($i%2==0) ? 'oddtr' : 'eventr';  			 
			 ?>	
				<tr class="<?php echo $trcolor; ?>" id="addtr_<?php echo $result['aid']; ?>">
					<td><input type="checkbox" name="uniqueId[]" id="trxn_chk<?php echo $result['aid'];?>" value="<?php echo $result['aid'];?>" /></td>
					<td align="center"><?php echo $i; ?></td>
					<td align="center"><?php echo $result['account_name']; ?></td>
					<td align="center"><?php echo $result['limit']; ?></td>
					<td align="center"><?php echo $result['discount']; ?></td>
					<td> 
					<?php 
					if($result['status']=='1')
					{  $txt = "Activate"; $class ="unsuspendicon";    }
					else{  $txt = "Deactivate"; $class ="blockicon";}


					echo '<a href="javascript:void(0);" title ='.$txt.' class='.$class.'></a>' ;  
					?>
					</td>
					<td align="center" width="10%"><a href="<?php echo URL_BASE.'tdispatch/edit_accounts/'.$result['aid'];?>" title ="<?php echo __('edit'); ?>" class="editicon" >
					<?php /*
					<a href="javascript:;" title ="<?php echo __('delete'); ?>" class="deleteicon" onclick="delete_accounts('<?php echo $result['aid']; ?>')"></a>
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
</form>
	
        </div>
	</div>


<div class="clr">&nbsp;</div>
<div class="pagination">
		<?php if(count($all_accounts) > 0): ?>
		 <p><?php echo $pag_data->render(); ?></p>  
		<?php endif; ?> 
  </div>
  <div class="clr">&nbsp;</div>

<?php if(count($all_accounts) > 0){ ?>
	 <div class="select_all">
                <b><a href="javascript:selectToggle(true, 'accounts');"><?php echo __('all_label');?></a></b><span class="pr2 pl2">|</span><b><a href="javascript:selectToggle(false, 'accounts');"><?php echo __('select_none');?></a></b>

                <span class="more_selection">
                    <select name="more_action" id="more_action">
                        <option value=""><?php echo __('Change Status'); ?></option>
                        <option value="block_account_request" ><?php echo __('Block'); ?></option>
                        <option value="active_account_request" ><?php echo __('Active'); ?></option>                        
                    </select>
                 </span>
	        </div>
	<?php } ?>


</div>
<script language="javascript" type="text/javascript">

 $(document).ready(function(){

});

// Select all the details by one click
function selectToggle(toggle, form) {
		var myForm = document.forms[form];
		for( var i=0; i < myForm.length; i++ ) { 
		    if(toggle) {
		        myForm.elements[i].checked = "checked";
		    } 
		    else
		    { myForm.elements[i].checked = ""; }
		}
	}
	
	//for More action Drop Down
	//=========================
	$('#more_action').change(function() {

		//select drop down option value
		//======================================
		var selected_val= $('#more_action').val();
		
			//perform more action reject withdraw
			//===================================		
			switch (selected_val){


					//	Current Action "reject"//block 
					//===================================

					case "block_account_request":
					var confirm_msg =  "<?php echo __('Are you sure want to block Request(s)?');?>";
	
					//Find checkbox whether selected or not and do more action
					//============================================================
					if($('input[type="checkbox"]').is(':checked'))
					{						
				   		 var ans = confirm(confirm_msg)
				   		 if(ans){							 
							 document.accounts.action="<?php echo URL_BASE;?>tdispatch/block_account_request/index";
							 document.accounts.submit();
						 }else{
						 	$('#more_action').val('');
						 }
	
					}
					else{
					        //alert for no record select
					        //=============================
						    alert("<?php echo __('Please select atleast one or more Record(s) to do this action');?>")	
						    $('#more_action').val('');
					}					
					break;



					//	Current Action "approve"
					//=========================

					case "active_account_request":
					var confirm_msg =  "<?php echo __('Are you sure want to Activate Request(s)?');?>";


						//Find checkbox whether selected or not and do more action
						//============================================================
						if($('input[type="checkbox"]').is(':checked'))
						{
					   		 var ans = confirm(confirm_msg)
					   		 if(ans){
								 document.accounts.action="<?php echo URL_BASE;?>tdispatch/active_account_request/index";
								 document.accounts.submit();
							 }else{
							 	$('#more_action').val('');
							 }		
						}
						else{
						        //alert for no record select
						        //=============================
							    alert("<?php echo __('Please select atleast one or more Record(s) to do this action');?>")	
							    $('#more_action').val('');
						}						

					break;

				}		
			return false;  
	});

function delete_accounts(acc_id)
{
	var cancel_Submit = confirm("<?php echo __('delete_alert_adminjob'); ?>");				
	if(cancel_Submit == true)
	{
		document.location.href="<?php echo URL_BASE; ?>tdispatch/delete_accounts/"+acc_id;
	}

}
</script>
