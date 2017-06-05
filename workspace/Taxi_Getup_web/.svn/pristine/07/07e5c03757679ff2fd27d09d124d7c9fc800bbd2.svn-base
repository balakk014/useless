<?php
defined('SYSPATH') OR die("No direct access allowed.");

$CURRENCY = isset($_SESSION['currency']) ? $_SESSION['currency'] : CURRENCY;

$user_type_val = isset($srch["user_type"]) ? $srch["user_type"] : '';
$startdate = isset($srch["startdate"]) ? $srch["startdate"] : '';
$enddate = isset($srch["enddate"]) ? $srch["enddate"] : '';
$status_val = isset($srch["status"]) ? $srch["status"] : '';
$couponcode_used = isset($srch["coupon_code_status"]) ? $srch["coupon_code_status"] : '';
$form_action = URL_BASE . 'manage/passenger_coupon/';
$back_action = URL_BASE . 'manage/passenger_coupon/';
?>
<link rel="stylesheet" href="<?php echo URL_BASE; ?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
<!--<script src="<?php echo URL_BASE; ?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-1.5.1.min.js"></script>-->
<script src="<?php echo URL_BASE; ?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="<?php echo URL_BASE; ?>public/js/datetimehrspicker/jquery-ui-timepicker-addon.js"></script>

<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
	<div class="content_middle">
	    <form method="get" class="form" name="manage_coupon" id="manage_coupon" action="<?php $form_action ?>">
		<table class="list_table1" border="0" width="100%" cellpadding="5" cellspacing="0">
		    <tr>
			<td valign="top" style="width:200px"><label><?php echo __('coupon_code'); ?></label></td>
                        <td width="32%">
                            <div class="ser_input_field"  >
                                <input type="text" name="keyword"  maxlength="256" id="keyword" value="<?php echo isset($srch['keyword']) ? trim($srch['keyword']) : ''; ?>" />
                            </div>
                        </td>
		    
			<td valign="top" style="width:200px"><label><?php echo __('serial_number'); ?></label></td>
			 <td>
                            <div class="ser_input_field" >
				<input type="text" name="serial_number"  maxlength="256"  id="serial_number" value="<?php echo isset($srch['serial_number']) ? trim($srch['serial_number']) : ''; ?>" />
			    </div>
			</td>	
		    </tr>
		  
		    <tr> 		
                <td >		
					 <?php echo __('start_date'); ?> - 	
			   <label><?php echo __('from'); ?></label>
			   </td>
			   <td>
			    <div class="ser_input_field" >
				<input type="text" readonly  title="<?php echo __('select_datetime'); ?>" id="startdate" name="startdate" value="<?php echo isset($srch['startdate']) ? trim($srch['startdate']) : ''; ?>"  />
				<span id="startdate_error" class="error"></span>		 
			    </div>						
                        </td>   
                      <td><?php echo __('start_date'); ?> - 
			    <label><?php echo __('to'); ?></label>
			    </td>
			    <td>
			    <div class="ser_input_field" >
				<input type="text"  readonly  title="<?php echo __('select_datetime'); ?>" id="enddate" name="enddate" value="<?php echo isset($srch['enddate']) ? trim($srch['enddate']) : ''; ?>"  />
				<span id="enddate_error" class="error"></span>							
			    </div>
                        </td> 	   			
			</tr>
			
			<tr>
			<td> <?php echo __('expire_date'); ?> - 
			    <label ><?php echo __('from'); ?></label>
			      </td>
			    <td>
			    <div class="ser_input_field" >
				<input type="text" readonly  title="<?php echo __('select_datetime'); ?>" id="e_startdate" name="e_startdate" value="<?php echo isset($srch['e_startdate']) ? trim($srch['e_startdate']) : ''; ?>"  />
				<span id="startdate_error" class="error"></span>		 
			    </div>				
			</td>       
			
			<td><?php echo __('expire_date'); ?> -
			    <label><?php echo __('to'); ?></label>
			      </td>
			    <td>
			    <div class="ser_input_field">
				<input type="text" readonly  title="<?php echo __('select_datetime'); ?>" id="e_enddate" name="e_enddate" value="<?php echo isset($srch['e_enddate']) ? trim($srch['e_enddate']) : ''; ?>"  />
				<span id="enddate_error" class="error"></span>
			    </div>
			</td>   
		    </tr>
		      
		    <tr>
			<?php
			if ($_SESSION['user_type'] == 'A') {
			    ?>  
    			<td valign="top"><label><?php echo __('status_label'); ?></label></td>
    			<td valign="top">
    			    <input type="hidden" name="company" id="company" value="" >
    			    <div class="formRight">
    				<div class="selector" id="uniform-user_type" style="width:240px;">
    				    <span><?php echo __('status_label'); ?></span>
    				    <select class="select2" name="status" id="status">
    					<option value=""><?php echo __('select_label'); ?></option>    
					    <?php
					    $allstatus = array('A' => 'Active', 'B' => 'Block', 'T' => 'Trash');
					    foreach ($allstatus as $status_key => $allstatus) {
						$selected_status = ($status_val == $status_key) ? ' selected="selected" ' : " ";
						?>  
						<option value="<?php echo $status_key; ?>"  <?php echo $selected_status; ?> ><?php echo ucfirst($allstatus); ?></option>
						<?php
					    }
					    ?>
    				    </select>
    				</div>
    			    </div>    
    			</td>
			    <?php
			} else {
			    ?>
    			<td></td><td></td>
			    <?php
			}
			?>
			<td valign="top" ><label><?php echo __('coupon_code_status'); ?></label></td>
			<td valign="top">
			    <div class="formRight">
				<div class="selector" id="uniform-user_type" style="width:240px;">
				    <span><?php echo __('coupon_code_status'); ?></span>
				    <select class="select2" name="coupon_code_status" id="coupon_code_status">
					<option value=""><?php echo __('select_label'); ?></option>    
					<option value="0" <?php
			if ($couponcode_used == '0') {
			    echo 'selected=selected';
			}
			?>><?php echo __('notused'); ?></option>    
					<option value="1" <?php
					if ($couponcode_used == '1') {
					    echo 'selected=selected';
					}
			?>><?php echo __('used'); ?></option>    

				    </select>
				</div>
			    </div>    
			</td>
		    </tr>

		    <tr style="display:none">
			<td valign="middle" ><label><?php echo __('serial_number_from'); ?></label></td>
			<td>
			    <div class="new_input_field" style="width:240px">
				<input type="text" name="serialno_from"  maxlength="20"  id="serialno_from" value="<?php echo isset($srch['serialno_from']) ? trim($srch['serialno_from']) : ''; ?>" />
				<span id="serialno_from_error" class="error"></span>		 
			    </div>				
			</td>       
			<td valign="middle" ><label><?php echo __('serial_number_to'); ?></label></td>
			<td>
			    <div class="new_input_field" style="width:240px">
				<input type="text" name="serialno_to"  maxlength="20"  id="serialno_to" value="<?php echo isset($srch['serialno_to']) ? trim($srch['serialno_to']) : ''; ?>" />
				<span id="serialno_to_error" class="error"></span>
			    </div>
			</td>  
		    </tr>

		    <tr>
			<td valign="top"><label>&nbsp;</label></td>
			<td>
			    <div class="new_button">
				<input type="submit" value="<?php echo __('button_search'); ?>" name="search_user" title="<?php echo __('button_search'); ?>" />
			    </div>
			    <div class="new_button">
				<input type="button" value="<?php echo __('button_cancel'); ?>" title="<?php echo __('button_cancel'); ?>" onclick="location.href = '<?php echo $back_action; ?>'" />
			    </div>
			</td>
		    </tr>
		</table>

		<?php
		$table_css = $export_pdf_button = $export_excel_button = "";
		if ($total_users > 0) {
		    $table_css = 'class="table_border"';
		    $startdate_export = isset($srch["startdate"]) ? $srch["startdate"] : date('Y-m-01 00:00:00');
		    $enddate_export = isset($srch["enddate"]) ? $srch["enddate"] : date('Y-m-d H:i:s');
		    $user_type_val = isset($srch["user_type"]) ? $srch["user_type"] : '';
		    $startdate = isset($srch["startdate"]) ? $srch["startdate"] : '';
		    $enddate = isset($srch["enddate"]) ? $srch["enddate"] : '';
		    $e_startdate = isset($srch["e_startdate"]) ? $srch["e_startdate"] : '';
		    $e_enddate = isset($srch["e_enddate"]) ? $srch["e_enddate"] : '';
		    $status_val = isset($srch["status"]) ? $srch["status"] : '';
		    $couponcode_used = isset($srch["coupon_code_status"]) ? $srch["coupon_code_status"] : '';
		    $coupon_code = isset($srch["coupon_code"]) ? $srch["coupon_code"] : '';
		    $keyword = isset($srch["keyword"]) ? $srch["keyword"] : '';
		    $serial_number = isset($srch["serial_number"]) ? $srch["serial_number"] : '';
		    $coupon_code_status = isset($srch["coupon_code_status"]) ? $srch["coupon_code_status"] : '';
		    $serialno_from = isset($srch["serialno_from"]) ? $srch["serialno_from"] : '';
		    $serialno_to = isset($srch["serialno_to"]) ? $srch["serialno_to"] : '';
		    $export_pdf_button = '<input type="button"  title="' . __('button_pdf') . '" class="button" value="' . __('button_pdf') . '" 
        				style="margin-left:20px;" onclick="location.href=\'' . URL_BASE . 'manage/coupon_export_pdf/&startdate=' . $startdate . '&enddate=' . $enddate . '&e_startdate=' . $e_startdate . '&e_enddate=' . $e_enddate . '&status=' . $status_val . '&couponcode_used=' . $couponcode_used . '&coupon_code=' . $coupon_code . '&keyword=' . $keyword . '&serial_number=' . $serial_number . '&coupon_code_status=' . $coupon_code_status . '&serialno_from=' . $serialno_from . '&serialno_to=' . $serialno_to . '\'" />
    				';
		    $export_excel_button = '<input type="button"  title="' . __('button_export') . '" class="button" value="' . __('button_export') . '" 
        				style="margin-left:20px;" onclick="location.href=\'' . URL_BASE . 'manage/coupon_export/&startdate=' . $startdate . '&enddate=' . $enddate . '&e_startdate=' . $e_startdate . '&e_enddate=' . $e_enddate . '&status=' . $status_val . '&couponcode_used=' . $couponcode_used . '&coupon_code=' . $coupon_code . '&keyword=' . $keyword . '&serial_number=' . $serial_number . '&coupon_code_status=' . $coupon_code_status . '&serialno_from=' . $serialno_from . '&serialno_to=' . $serialno_to . '\'" />
    				';
		}
		?>

		<div class="widget">
		    <div class="title"><img src="<?php echo IMGPATH; ?>icons/dark/frames.png" alt="" class="titleIcon" /><h6><?php echo $page_title; ?></h6>
			<div style="width:auto; float:right; margin: -5px 3px;">
			    <div class="new_button"> <?php // echo $export_excel_button; ?></div>                       
			    <div class="new_button"> <?php //echo $export_pdf_button; ?></div>                       
			</div>
		    </div>        
			<?php
			if ($total_users > 0) {
			    ?>
    		    <div class= "overflow-block">
				<?php
			    }
			    ?>
			<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive">
<?php
if ($total_users > 0) {
    ?>
    			    <thead>
    				<tr >
    					<!--<td align="left" width="5%"></td>-->
    				    <td align="left" width="5%"><?php echo __('Select'); ?></td>
    				    <td align="left" width="5%" ><?php echo 'Status'; ?></td>
    				    <td align="left" width="5%"><?php echo __('sno_label'); ?></td>
    				    <td align="left" width="10%"><?php echo ucfirst(__('coupon_code')); ?></td>
    				    <td align="left" width="10%"><?php echo ucfirst(__('coupon_name')); ?></td>
    				    <td align="left" width="8%"><?php echo ucfirst(__('voucher_serial_number')); ?></td>
    				    <td align="left" width="10%"><?php echo ucfirst(__('coupon_amt')) . ' (' . $CURRENCY . ')'; ?></td>
    				    <td align="left" style="text-align:left;" width="15%"><?php echo __('start_date'); ?></td>
    				    <td align="left" style="text-align:left;" width="15%"><?php echo __('expiry_date'); ?></td>
    				    <td align="left" style="text-align:left;" width="7%"><?php echo __('coupon_used'); ?></td>
    				    <td align="left" width="10%"><?php echo __('action_label'); ?></td>
    				</tr>
    			    </thead>
    			    <tbody>
				    <?php
				    $sno = $Offset; /* For Serial No */
				    //print_r($all_user_list);
				    foreach ($couponcode_list as $couponcode_list) {
					//S.No Increment
					//==============
					$sno++;

					//For Odd / Even Rows
					//===================
					$trcolor = ($sno % 2 == 0) ? 'oddtr' : 'eventr';
					?>     

					<tr class="<?php echo $trcolor; ?>">
						<!--<td><input type="checkbox" name="uniqueId[]" id="trxn_chk<?php echo $couponcode_list['coupon_id']; ?>" value="<?php echo $couponcode_list['coupon_id']; ?>" /></td>-->
					    <td align="center"><input type="checkbox" name="uniqueId[]" id="trxn_chk<?php echo $couponcode_list['coupon_id']; ?>" value="<?php echo $couponcode_list['coupon_id']; ?>" />
					    </td>
					    <td align="center"> 
						<?php
						if ($couponcode_list['coupon_status'] == 'A') {
						    $txt = "Active";
						    $class = "unsuspendicon";
						} elseif ($couponcode_list['coupon_status'] == 'T') {
						    $txt = "Trash";
						    $class = "trashicon";
						} else {
						    $txt = "Deactive";
						    $class = "blockicon";
						}
						echo '<a  title =' . $txt . ' class=' . $class . '></a>';
						?>                               
					    </td>
					    <td align="center"><?php echo $sno; ?></td>
					    <td align="center"><?php echo $couponcode_list['coupon_code']; ?></td>
					    <td align="center"><?php echo ucfirst($couponcode_list['coupon_name']); ?></td>
					    <td align="center"><?php echo $couponcode_list['serial_number']; ?></td>
					    <td align="center"><?php echo $couponcode_list['amount']; ?></td>
	<?php if ($_SESSION['user_type'] == 'A') { /* ?>
	  <td align="center"><?php echo ($couponcode_list['company_name'] != '') ? $couponcode_list['company_name'] : 'All'; ?></td>
	  <?php */
	}
	?>
					    <td style="text-align:left;" align="center"><?php echo $couponcode_list['start_date']; ?></td>
					    <td style="text-align:left;" align="center"><?php echo $couponcode_list['expiry_date']; ?></td>
					    <?php /* <td align="center"><?php echo $couponcode_list['passenger_count']; ?></td> */ ?>

								<!--<td> 
					    <?php /* if($listings['user_status']=='A')
					      {  $txt = "Activate"; $class ="unsuspendicon";    }
					      elseif($listings['user_status']=='T')
					      {$txt = "Trash"; $class ="trashicon";}
					      else{  $txt = "Deactivate"; $class ="blockicon";      }


					      echo '<a href="javascript:void(0);" title ='.$txt.' class='.$class.'></a>' ;
					     */ ?>
								</td> -->		
					    <?php
					    if ($couponcode_list['coupon_used'] == 0) {
						?>
	    				    <td align="center">No</td>
						<?php
					    } else {
						?>
	    				    <td align="center">
	    					Yes <br/>(<?php echo $couponcode_list['name'] . ', ' . $couponcode_list['phone'] ?>)
	    				    </td>
						<?php
					    }
					    if ($couponcode_list['coupon_used'] == 0) {
						?>
	    				    <td align="center" width="20" colspan='3' ><?php echo '<a href=' . URL_BASE . 'edit/passenger_coupon/' . $couponcode_list['coupon_id'] . ' " title ="Edit" class="editicon"></a>'; ?></td>
						<?php
					    } else {
						?>
	    				    <td align="center" width="20" colspan='3' > - </td>
					    <?php
					}
					?>
					</tr>
					<?php
				    }
				}

				//For No Records
				//==============
				else {
				    ?>
    				<tr>
    				    <td class="nodata"><?php echo __('no_data'); ?></td>
    				</tr>
    <?php
}
?>
			    </tbody>	
			</table>
		    </div>

<?php
if ($total_users > 0) {
    ?></div><?php
}
?>
	    </form>
	</div>
    </div>
    <div class="clr">&nbsp;</div>
    <div class="pagination">
<?php if ($total_users > 0): ?>
    	<p><?php echo $pag_data->render(); ?></p>  
    <?php endif; ?> 
    </div>
    <div class="clr">&nbsp;</div>

    <!--** Multiple select starts Here ** -->
<?php
if (count($couponcode_list) > 0) {
    ?>
        <div class="select_all">
    	<b><a id="select-all"><?php echo __('all_label'); ?></a></b><span class="pr2 pl2">|</span><b><a id="select-none"><?php echo __('select_none'); ?></a></b>

    	<span style="padding-left:10px;">
    	    <select name="more_action" id="more_action">
    		<option value=""><?php echo __('Change Status'); ?></option>
    		<option value="block_coupon_request" ><?php echo __('Block'); ?></option>
    		<option value="active_coupon_request" ><?php echo __('Active'); ?></option>
    		<option value="trash_coupon_request" ><?php echo __('Trash'); ?></option>
    	    </select>
    	</span>
        </div>
	<?php
    }
    ?>
    <!--** Multiple select ends Here ** -->

    <!--** Multiple select starts Here ** -->
<?php
if (count($couponcode_list) > 0) {
    ?>
        <!--<div class="select_all">
          <b><a href="javascript:selectToggle(true, 'frmusers');"><?php echo __('all_label'); ?></a></b><span class="pr2 pl2">|</span><b><a href="javascript:selectToggle(false, 'frmusers');"><?php echo __('select_none'); ?></a></b>

          <span style="padding-left:10px;">
    	  <select name="more_action" id="more_action">
    	      <option value=""><?php echo __('Change Status'); ?></option>
    	      <option value="block_passenger_request" ><?php echo __('Block'); ?></option>
    	      <option value="active_passenger_request" ><?php echo __('Active'); ?></option>
    	      <option value="trash_passenger_request" ><?php echo __('Trash'); ?></option>
    	  </select>
           </span>
          </div>
    <?php
}
?>
    <!--** Multiple select ends Here ** -->
</div>
</div>
<script type="text/javascript" language="javascript">
    //For Delete the users
    //=====================

    function frmdel_user(userid)
    {
	var answer = confirm("<?php echo __('delete_alert2'); ?>");    
	if (answer){
	    window.location="<?php echo URL_BASE; ?>admin/delete_passenger/"+userid;
	}    
	return false;  
    }  
    function frmblk_user(userid,status)
    {   
	window.location="<?php echo URL_BASE; ?>admin/blkunblk_passenger/"+userid+"/"+status;    
	return false;  
    }
</script>
<script type="text/javascript">
    $(document).ready(function(){
	//toggle(1);
	$("input[type='text']:first", document.forms[0]).focus();
    });
</script>


<script type="text/javascript">
    function selectToggle(toggle, form) 
    {
	var myForm = document.forms[form];
	for( var i=0; i < myForm.length; i++ ) 
	{ 
	    if(toggle) 
		myForm.elements[i].checked = "checked";
	    else
		myForm.elements[i].checked = ""; 
	}
    }

	
    //for More action Drop Down
    //=========================
    $('#more_action').change(function() 
    {
	//select drop down option value
	//======================================
	var selected_val= $('#more_action').val();
		
	//perform more action reject withdraw
	//===================================		
	switch (selected_val)
	{
	    //	Current Action "reject"//block 
	    //===================================

	    case "block_passenger_request":
		var confirm_msg =  "<?php echo __('Are you sure want to block Request(s)?'); ?>";

		//Find checkbox whether selected or not and do more action
		//============================================================
		if($('input[type="checkbox"]').is(':checked'))
		{
		    var ans = confirm(confirm_msg)
		    if(ans)
		    {
			document.frmusers.action="<?php echo URL_BASE; ?>manageusers/block_passenger_request/index";
			document.frmusers.submit();
		    }
		    else
			$('#more_action').val('');

		}
		else
		{
		    //alert for no record select
		    //=============================
		    alert("<?php echo __('Please select atleast one or more Record(s) to do this action'); ?>")	
		    $('#more_action').val('');
		}					
		break;
			
	    //	Current Action "approve"
	//=========================

    case "active_passenger_request":
	var confirm_msg =  "<?php echo __('Are you sure want to Activate Request(s)?'); ?>";

	//Find checkbox whether selected or not and do more action
	//============================================================
	if($('input[type="checkbox"]').is(':checked'))
	{
	    var ans = confirm(confirm_msg)
	    if(ans){
		document.frmusers.action="<?php echo URL_BASE; ?>manageusers/active_passenger_request/index";
		document.frmusers.submit();
	    }
	    else
	    {
		$('#more_action').val('');
	    }		
	}
	else
	{
	    //alert for no record select
	    //=============================
	    alert("<?php echo __('Please select atleast one or more Record(s) to do this action'); ?>")	
	    $('#more_action').val('');
	}
	break;

    //	Current Action "trash"
//==========================

case "trash_passenger_request":
var confirm_msg =  "<?php echo __('Are you sure you want to delete?'); ?>";

//Find checkbox whether selected or not and do more action
//============================================================
if($('input[type="checkbox"]').is(':checked'))
{
    var ans = confirm(confirm_msg)
    if(ans)
    {
	document.frmusers.action="<?php echo URL_BASE; ?>manageusers/trash_passenger_request/index";
	document.frmusers.submit();
    }
    else
	$('#more_action').val('');
}
else
{
    //alert for no record select
    //=============================
    alert("<?php echo __('Please select atleast one or more Record(s) to do this action'); ?>")	
    $('#more_action').val('');
}
break;
}		
return false;  
});
	
$(document).ready(function()
{
//toggle(25);

$("#startdate").datetimepicker( 
{
showTimepicker:true,
showSecond: true,
timeFormat: 'hh:mm:ss',
dateFormat: 'yy-mm-dd',
stepHour: 1,
stepMinute: 1,
stepSecond: 1,
onSelect: function (selected) 
{
    var dt = new Date(selected);
    dt.setDate(dt.getDate() + 1);
    $("#enddate").datepicker("option", "minDate", dt);
}
});

$("#enddate").datetimepicker( 
{
showTimepicker:true,
showSecond: true,
timeFormat: 'hh:mm:ss',
dateFormat: 'yy-mm-dd',
stepHour: 1,
stepMinute: 1,
stepSecond: 1,
onSelect: function (selected) 
{
    var dt = new Date(selected);
    dt.setDate(dt.getDate() - 1);
    $("#startdate").datepicker("option", "maxDate", dt);
}
});

$("#e_startdate").datetimepicker( 
{
showTimepicker:true,
showSecond: true,
timeFormat: 'hh:mm:ss',
dateFormat: 'yy-mm-dd',
stepHour: 1,
stepMinute: 1,
stepSecond: 1,
onSelect: function (selected) 
{
    var dt = new Date(selected);
    dt.setDate(dt.getDate() - 1);
    $("#e_enddate").datepicker("option", "minDate", dt);
}
});

$("#e_enddate").datetimepicker( {
showTimepicker:true,
showSecond: true,
timeFormat: 'hh:mm:ss',
dateFormat: 'yy-mm-dd',
stepHour: 1,
stepMinute: 1,
stepSecond: 1,
onSelect: function (selected) {
    var dt = new Date(selected);
    dt.setDate(dt.getDate() - 1);
    $("#e_startdate").datepicker("option", "maxDate", dt);
}
} );

//toggle(1);

$('#select-all').click(function(event) 
{   
// Iterate each checkbox
$(':checkbox').each(function() {
    this.checked = true;                        
});
});

$('#select-none').click(function(event) 
{   
// Iterate each checkbox
$(':checkbox').each(function() {
    this.checked = false;                        
});
});

//for More action Drop Down
//=========================
$('#more_action').change(function() 
{
//select drop down option value
//======================================
var selected_val= $('#more_action').val();
		
//perform more action reject withdraw
//===================================		
switch (selected_val)
{
    //	Current Action "reject"//block 
    //===================================

    case "block_coupon_request":
	var confirm_msg =  "<?php echo __('Are you sure want to block Request(s)?'); ?>";

	//Find checkbox whether selected or not and do more action
	//============================================================
	if($('input[type="checkbox"]').is(':checked'))
	{
	    var ans = confirm(confirm_msg)
	    if(ans)
	    {
		document.manage_coupon.action="<?php echo URL_BASE; ?>manage/block_coupon_request/index";
		document.manage_coupon.submit();
	    }
	    else
	    {
		$('#more_action').val('');
	    }
	}
	else
	{
	    //alert for no record select
	    //=============================
	    alert("<?php echo __('Please select atleast one or more Record(s) to do this action'); ?>")	
	    $('#more_action').val('');
	}					
	break;

    //	Current Action "approve"
//=========================

case "active_coupon_request":
var confirm_msg =  "<?php echo __('Are you sure want to Activate Request(s)?'); ?>";

//Find checkbox whether selected or not and do more action
//============================================================
if($('input[type="checkbox"]').is(':checked'))
{
    var ans = confirm(confirm_msg)
    if(ans)
    {
	document.manage_coupon.action="<?php echo URL_BASE; ?>manage/active_coupon_request/index";
	document.manage_coupon.submit();
    }
    else
	$('#more_action').val('');
}
else
{
    //alert for no record select
    //=============================
    alert("<?php echo __('Please select atleast one or more Record(s) to do this action'); ?>")	
    $('#more_action').val('');
}
break;
//	Current Action "trash"
//==========================

case "trash_coupon_request":
var confirm_msg =  "<?php echo __('Are you sure you want to delete?'); ?>";

//Find checkbox whether selected or not and do more action
//============================================================
if($('input[type="checkbox"]').is(':checked'))
{
var ans = confirm(confirm_msg)
if(ans)
{
document.manage_coupon.action="<?php echo URL_BASE; ?>manage/trash_coupon_request/index";
document.manage_coupon.submit();
}
else
{
$('#more_action').val('');
}		
}
else
{
//alert for no record select
//=============================
alert("<?php echo __('Please select atleast one or more Record(s) to do this action'); ?>")	
$('#more_action').val('');
}						

break;
}		
return false;  
});
} );
</script>
