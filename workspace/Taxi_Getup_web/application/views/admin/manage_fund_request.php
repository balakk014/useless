<?php defined('SYSPATH') OR die("No direct access allowed."); 

//For search values
//=================
$user_type_val = isset($srch["user_type"]) ? $srch["user_type"] :''; 
$status_val = isset($srch["status"]) ? $srch["status"] :''; 							
$keyword = isset($srch["keyword"]) ? $srch["keyword"] :''; 
$company_val = isset($srch["filter_company"]) ? $srch["filter_company"] :''; 

$split = explode('/',$_SERVER['REQUEST_URI']);
$list = $split[3]; 

$form_action = URL_BASE.'admin/manage_fund_request_list/'.$list.'/';
$back_action = URL_BASE.'admin/manage_fund_request/'.$list.'/';

//For CSS class deefine in the table if the data's available
//===========================================================
$total_fundrequest=count($all_fundrequest_list);

$table_css=$export_excel_button="";
if($total_fundrequest>0)
{ 
	$table_css='class="table_border"'; 

	$export_excel_button='
        				<input type="button"  title="'.__('print_fund_request').'" class="button" value="'.__('print_fund_request').'" 
        				onclick="location.href=\''.URL_BASE.'admin/printdetails/'.$list.'/?filter_company='.$company_val.'\'" />';
}?>

<div class="container_content fl clr">
	<div class="cont_container mt15 mt10">
		<div class="content_middle"> 
        <form method="get" class="form" name="search_frmwithdraw" id="search_frmwithdraw" action="<?php echo $form_action; ?>" >
		<table class="list_table1" border="0" width="100%" cellpadding="5" cellspacing="0">
				<tr>
				<?php if($_SESSION['user_type'] == 'A') { ?>
					<td valign="top"><label><?php echo __('company'); ?></label></td>
					<td valign="top">
						<div class="selector" id="uniform-user_type">
							<select class="select2" name="filter_company" id="filter_company" --onchange="this.form.submit()">
								<option value=""><?php echo __('select_label'); ?></option>    
								<?php 
									foreach ($get_allcompany as $comapany_list) {
									$selected_status = ($company_val == $comapany_list['cid']) ? ' selected="selected" ' : " ";
								?>  
									<option value="<?php echo $comapany_list['cid']; ?>"  <?php echo $selected_status; ?> ><?php echo ucfirst($comapany_list['company_name']); ?></option>
									<?php }  ?>
							</select>
						</div>
						<span id="filter_company_error" class="error"></span>

					</td>     
				<?php } ?>
				
				  


                 </tr>
                 <tr>
                        <td valign="top"><label>&nbsp;</label></td>
                        <td>
                            <div class="button brownB">
                                <input type="submit" value="<?php echo __('button_search'); ?>" name="search_user" title="<?php echo __('button_search'); ?>" />
                            </div>
                            <div class="button blueB">
                                <input type="button" value="<?php echo __('button_cancel'); ?>" title="<?php echo __('button_cancel'); ?>" onclick="location.href = '<?php echo $back_action; ?>'" />
                            </div>
                        </td>
                 </tr>
                </table>

	</form>

	<form method="post" class="form" name="frmwithdraw" id="frmwithdraw" >
                		<div class="widget">
		<div class="title"><img src="<?php echo IMGPATH; ?>icons/dark/frames.png" alt="" class="titleIcon" /><h6><?php echo $page_title; ?></h6>
		<div style="width:auto; float:right; margin: 4px 3px;">
		<div class="button greyishB"> <?php if($company_val !='') { echo $export_excel_button; } ?></div>                       

		</div>
		</div>
<?php if($total_fundrequest > 0){ ?>
<div class= "overflow-block">
<?php } ?>		
<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive">
<?php if($total_fundrequest > 0){ ?>

		<input type="hidden" name="form_type" value="pending" />
<thead>
	<tr>
		<?php if(($split[3]=='failed') || ($split[3]=='pending'))
		{ ?>
		<td align="left" width="5%"><?php echo __('select'); ?></td>
		<?php } ?>
		<td align="left" width="5%"><?php echo __('sno_label'); ?></td>
		<td align="left" width="15%"><?php echo ucfirst(__('name')); ?></td>
		<td align="left" width="15%"><?php echo __('company_name'); ?></td>
		<td align="left" width="15%"><?php echo __('requeston'); ?></td>
		<td align="left" width="10%"><?php echo __('amount'); ?>(<?php echo CURRENCY;?>)</td>
		<td align="left" width="10%"><?php echo __('acc_bal'); ?>(<?php echo CURRENCY;?>)</td>
		<?php if(($split[3]=='failed') || ($split[3]=='success')) { ?>
		<td align="left" width="10%"><?php echo __('correlation_id'); ?></td>
		<?php } ?>
		<td align="left" width="10%" ><?php echo __('status'); ?></td>
		<td align="left" width="10%" ><?php echo __('paymentstatus'); ?></td>
		<?php if(($split[3]=='failed')) { ?>
		<td align="left" width="10%"><?php echo __('error_code'); ?></td>
		<td align="left" width="15%"><?php echo __('responsemessage_label'); ?></td>
		<?php } ?>

	</tr>
</thead>
<tbody>	
		<?php

         $sno=$Offset; /* For Serial No */

		 foreach($all_fundrequest_list as $listings) { 
//echo '<pre>';print_r($listings);
//echo '</pre>';
		 //S.No Increment
		 //==============
		 $sno++;
        
         //For Odd / Even Rows
         //===================
         $trcolor=($sno%2==0) ? 'oddtr' : 'eventr';  
		 
        ?>     

        <tr class="<?php echo $trcolor; ?>">
		<?php if(($split[3]=='failed') || ($split[3]=='pending'))
		{ ?>
			<td align="center" ><input type="checkbox" name="pending_req_list[]" value="<?php echo $listings['requested_id'];?>" /></td>
		<?php } ?>	
			<td align="center"><?php echo $sno; ?></td>
			<td align="center"><?php echo $listings['name']; ?></td>
			<td align="center"><a href="<?php echo URL_BASE.'manage/companydetails/'.$listings['company_ownerid'];?>"><?php echo $listings['company_name']; ?></a></td>
			<td align="center"><?php echo $listings['requested_date']; ?></td>
			<td align="center"><?php echo $listings['amount']; ?></td>
			<td align="center"><?php echo $listings['account_balance']; ?></td>
			<?php if(($split[3]=='failed') || ($split[3]=='success')) { ?>
			<td align="center"><?php echo $listings['correlationid']; ?></td>
			<?php } ?>
			<td align="center">
			<?php if($listings["status"] == 1)
			{ 
				echo __('pending');
			}
			else if($listings["status"] == 2)
			{ 
				echo __('approved'); 
			}
			else 
			{ 
				echo __('rejected_by_admin'); 
			} ?>
			</td>	
			<td align="center">
			<?php 
			if($listings["pay_status"] == 1) 
			{
				echo __('success');
			}
			else if($listings["pay_status"] == 2)
			{
				echo __('failed');
			}
			else if($listings["pay_status"] == 3)
			{
				echo __('rejected'); 
			}
			else			
			{
			    echo __('fundstatus');
			}
			?>
			</td>
			<?php if(($split[3]=='failed')) { ?>
			<td align="center"><?php echo $listings['errorcode']; ?></td>
			<td align="center"><?php echo $listings['long_message']; ?></td>
			<?php } ?>

		</tr>
		<?php } ?>
		<?php if(($split[3]=='failed') || ($split[3]=='pending')) { ?>
		<tr>
		<td colspan="2" align="center">
				<input type="checkbox" value="0" name='checkall'
					onclick="checkUncheckAll(this);" /><label class="ml-10"><?php echo __('all/none'); ?></label>
		</td>
		<td colspan="4">
				<select name="request_action" id="request_action"
					onchange="if
(this.options[this.selectedIndex].value=='1') { approve('pending'); } else if(this.options[this.selectedIndex].value=='2'){ reject('pending'); }">
					<option value="">
						-
						<?php echo __('moreaction'); ?>
						-
					</option>
					<option value="1">
					<?php echo __('approve'); ?>
					</option>
					<option value="2">
					<?php echo __('reject'); ?>
					</option>
				</select>
		</td>
		</tr>
	<?php } ?>
		<?php
 		 } 
		 
		//For No Records
		//==============
	     else{ ?>
       	<tr>
        	<td class="nodata"><?php echo __('no_data'); ?></td>
        </tr>

		<?php } ?>
		</tbody>
</table>
<?php if ($total_fundrequest > 0) { ?>
</div>
<?php } ?>
</form>
</div>
</div>
<div class="clr">&nbsp;</div>
<div class="pagination">
		<?php if($total_fundrequest > 0): ?>
		 <p><?php echo $pag_data->render(); ?></p>  
		<?php endif; ?> 
  </div>
  <div class="clr">&nbsp;</div>

</div>
</div>

<script type="text/javascript" language="javascript">
$(document).ready(function(){
	toggle(27);
});

function checkUncheckAll(theElement) 
{
	 var theForm = theElement.form, z = 0;
	 for(z=0; z<theForm.length;z++)
	 {
		  if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall')
		  {
			theForm[z].checked = theElement.checked;
		  }
	 }
}


		//approve
		function approve(rtype)
		{
			if(rtype == "pending")
			{
				var chks = document.getElementsByName('pending_req_list[]');
				var hasChecked = false;												
				// Get the checkbox array length and iterate it to see if any of them is selected
				for (var i = 0; i < chks.length; i++)
				{
					if (chks[i].checked)
					{
						hasChecked = true;
						break;
					}
				
				}
				
				// if ishasChecked is false then throw the error message
				if (!hasChecked)
				{
					alert("<?php echo __('please_select_at_least_one_record'); ?>");
					chks[0].focus();
					$('#request_action').val('');
					return false;
				}
				
				// if one or more checkbox value is selected then submit the form
				var booSubmit = confirm("<?php echo __('are_you_sure_want_to_pay'); ?>");				
				if(booSubmit == true)
				{
					document.frmwithdraw.action="<?php echo URL_BASE;?>payment/withdrawrequest/";
					document.frmwithdraw.submit();
				}

			}
			else if(rtype == "failed")
			{
				var chks = document.getElementsByName('failed_list[]');
				var hasChecked = false;												
				// Get the checkbox array length and iterate it to see if any of them is selected
				for (var i = 0; i < chks.length; i++)
				{
					if (chks[i].checked)
					{
						hasChecked = true;
						break;
					}
				
				}
				
				// if ishasChecked is false then throw the error message
				if (!hasChecked)
				{
					alert("<?php echo __('please_select_at_least_one_record'); ?>");
					chks[0].focus();
					$('#request_action').val('');
					return false;
				}
				
				// if one or more checkbox value is selected then submit the form
				var booSubmit = confirm("<?php echo __('are_you_sure_want_to_pay'); ?>");				
				if(booSubmit == true)
				{
					document.frmwithdraw.submit();
				}

			}
		}
	
		
		//approve request					
		function reject(rtype)
		{
			if(rtype == "pending")
			{
				var chks = document.getElementsByName('pending_req_list[]');
				var hasChecked = false;												
				// Get the checkbox array length and iterate it to see if any of them is selected
				for (var i = 0; i < chks.length; i++)
				{
					if (chks[i].checked)
					{
						hasChecked = true;
						break;
					}
				
				}
				
				// if ishasChecked is false then throw the error message
				if (!hasChecked)
				{
					alert("<?php echo __('please_select_at_least_one_record'); ?>");
					chks[0].focus();
					$('#request_action').val('');
					return false;
				}
				
				// if one or more checkbox value is selected then submit the form
				var booSubmit = confirm("<?php echo __('are_you_sure_want_to_reject'); ?>");				
				if(booSubmit == true)
				{
							 document.frmwithdraw.action="<?php echo URL_BASE;?>payment/reject_withdraw_request";
							 document.frmwithdraw.submit();
				}

			}
			else if(rtype == "failed")
			{
				var chks = document.getElementsByName('failed_list[]');
				var hasChecked = false;												
				// Get the checkbox array length and iterate it to see if any of them is selected
				for (var i = 0; i < chks.length; i++)
				{
					if (chks[i].checked)
					{
						hasChecked = true;
						break;
					}
				
				}
				
				// if ishasChecked is false then throw the error message
				if (!hasChecked)
				{
					alert("<?php echo __('please_select_at_least_one_record'); ?>");
					chks[0].focus();
					$('#request_action').val('');
					return false;
				}
				
				// if one or more checkbox value is selected then submit the form
				var booSubmit = confirm("<?php echo __('are_you_sure_want_to_reject'); ?>");				
				if(booSubmit == true)
				{
					document.failed_transaction.submit();
				}

			}
		}

</script>



