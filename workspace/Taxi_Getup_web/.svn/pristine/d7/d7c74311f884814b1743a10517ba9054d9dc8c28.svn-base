<?php defined('SYSPATH') OR die("No direct access allowed."); 


//For search values
//=================
$user_type_val = isset($srch["user_type"]) ? $srch["user_type"] :''; 
$status_val = isset($srch["status"]) ? $srch["status"] :''; 							
$keyword = isset($srch["keyword"]) ? $srch["keyword"] :''; 

//For CSS class deefine in the table if the data's available
//===========================================================
$total_company=count($all_company_list);

$table_css=$export_excel_button="";
if($total_company>0)
{ 
	$table_css='class="table_border"'; 

	$export_excel_button='<span>
        				<input type="button"  title="'.__('button_export').'" class="button" value="'.__('button_export').'" 
        				onclick="location.href=\''.URL_BASE.'manage/export?keyword='.$keyword.'&status='.$status_val.'&type='.$user_type_val.'\'" />
    				</span>';
}?>

<div class="bread_crumb" >
	<!-- common config  home link -->
	<?php 
	$atag_start='<a href="'.URL_BASE.'admin/login" title="Home">'; 
	$atag_end='</a>';?>	
	<?php echo $atag_start.__('home_breadcrumb').$atag_end;?>
	<span class="fwn"><img src="<?php echo URL_BASE;?>public/admin/images/list_arrow_medium.png" width="14px" height="14px" _class="mt5"/></span>
	
	<div style="float: left;"><?php echo __('manage_driver'); ?></div>
	</div>

<div class="container_content fl clr">
<div class="cont_container mt15 mt10">

    <div class="content_top"><div class="top_left"></div><div class="top_center"></div><div class="top_rgt"></div></div>
    <div class="content_middle">
    
 
        <form method="post" class="admin_form" name="frmcompany" id="frmcompany" action="search">



<div class="clr">&nbsp;</div>
<div style="float: right;">

</div> 
<div class="clr">&nbsp;</div>


<table cellspacing="0" cellpadding="10" width="100%" align="center" <?php echo $table_css; ?>>
<?php if($total_company > 0){ ?>


	<tr class="rowhead">


<!--** Multiple select Starts Here ** -->
 <th align="center"><?php echo __('Select'); ?></th>
<th align="center" style="min-width: 22px !important;" ><?php echo 'Status'; ?></th>
<!--** Multiple select ends Here ** -->


		<th align="center" width="5%"><?php echo __('sno_label'); ?></th>
		<th align="center" width="15%"><?php echo __('username_list_label'); ?></th>
		<th align="center" width="15%"><?php echo __('companyname'); ?></th>
		<th align="center" width="15%"><?php echo __('companyaddress'); ?></th>
		<th align="center" width="10%"><?php echo __('email_label'); ?></th>
		<th align="center" width="10%" ><?php echo __('action_label'); ?></th>

	</tr>
		<?php

         $sno=$Offset; /* For Serial No */

		 foreach($all_company_list as $listings) {
		 
		 //S.No Increment
		 //==============
		 $sno++;
        
         //For Odd / Even Rows
         //===================
         $trcolor=($sno%2==0) ? 'oddtr' : 'eventr';  
		 
        ?>     

        <tr class="<?php echo $trcolor; ?>">


<!--** Multiple select Starts Here ** -->

                    <td align="center"> <?php //echo $job_category['serviceid'];?>
                        <input type="checkbox" name="uniqueId[]" id="trxn_chk<?php echo $listings['id'];?>" value="<?php echo $listings['id'];?>" />
                    </td>

                    <td align="center"> 
                            <?php 
                             if($listings['company_status']=='A')
                             {  $txt = "Activate"; $class ="unsuspendicon";    }
				elseif($listings['company_status']=='T')
				{$txt = "Trash"; $class ="trashicon";}
                             else{  $txt = "Deactivate"; $class ="blockicon";      }


                             echo '<a title ='.$txt.' class='.$class.'></a>' ;  
                         ?>

                    </td> 

<!--** Multiple select ends Here ** -->

			<td align="center"><?php echo $sno; ?></td>
			<td align="center">

                <?php echo wordwrap(ucfirst($listings['name']),30,'<br/>',1); ?>

			</td>

			<td align="center"><?php echo wordwrap($listings['company_name'],25,'<br />',1); ?></td>
			
			<td align="center"><?php echo $listings['company_address']; ?></td>
			
			<td align="center"><?php echo wordwrap($listings['email'],25,'<br />',1); ?></td>

			<td align="center" width="20" colspan='3' ><?php echo '<a href='.URL_BASE.'edit/company/'.$listings['id'].' " title ="Edit" class="editicon"></a>' ; ?></td>


		</tr>
		<?php } 
 		 } 
		 
		//For No Records
		//==============
	     else{ ?>
       	<tr>
        	<td class="nodata"><?php echo __('no_data'); ?></td>
        </tr>
		<?php } ?>
</table>

</form>
</div>
<div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt"></div></div>
 
  <div class="people_pagination">
		<?php if(($action != 'search') && $total_company > 0): ?>
		 <p><?php echo $pag_data->render(); ?></p>  
		<?php endif; ?> 
  </div>
  <div class="clr">&nbsp;</div>

<!--** Multiple select starts Here ** -->
<?php if(count($all_company_list) > 0)
       { ?>
          <div class="select_all">
                <b><a href="javascript:selectToggle(true, 'frmcompany');"><?php echo __('all_label');?></a></b><span class="pr2 pl2">|</span><b><a href="javascript:selectToggle(false, 'frmcompany');"><?php echo __('select_none');?></a></b>

                 <span class="more_selection">
                    <select name="more_action" id="more_action">
                        <option value=""><?php echo __('Change Status'); ?></option>
                        <option value="block_company_request" ><?php echo __('Block'); ?></option>
                        <option value="active_company_request" ><?php echo __('Active'); ?></option>
                        <option value="trash_company_request" ><?php echo __('Trash'); ?></option>
                    </select>
                 </span>
	        </div>
        <?php
        } ?>
<!--** Multiple select ends Here ** -->



</div>
</div>

<script type="text/javascript" language="javascript">
//For Delete the users
//=====================
function frmdel_user(userid)
{
   var answer = confirm("<?php echo __('delete_alert2');?>");
    
	if (answer){
        window.location="<?php echo URL_BASE;?>admin/delete/"+userid;
    }
    
    return false;  
}  
function frmblk_user(userid,status)
{   
    window.location="<?php echo URL_BASE;?>admin/blkunblk/"+userid+"/"+status;    
    return false;  
}  

</script>
<script type="text/javascript">
$(document).ready(function(){
	toggle(5);$("input[type='text']:first", document.forms[0]).focus();
});
</script>


<script type="text/javascript">

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

					case "block_company_request":
					var confirm_msg =  "<?php echo __('Are you sure want to block Request(s)?');?>";
	
					//Find checkbox whether selected or not and do more action
					//============================================================
					if($('input[type="checkbox"]').is(':checked'))
					{
				   		 var ans = confirm(confirm_msg)
				   		 if(ans){
							 document.frmcompany.action="<?php echo URL_BASE;?>manage/block_company_request/index";
							 document.frmcompany.submit();
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

					case "active_company_request":
					var confirm_msg =  "<?php echo __('Are you sure want to Activate Request(s)?');?>";


						//Find checkbox whether selected or not and do more action
						//============================================================
						if($('input[type="checkbox"]').is(':checked'))
						{
					   		 var ans = confirm(confirm_msg)
					   		 if(ans){
								 document.frmcompany.action="<?php echo URL_BASE;?>manage/active_company_request/index";
								 document.frmcompany.submit();
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


                	//	Current Action "trash"
					//==========================

					case "trash_users_request":
					var confirm_msg =  "<?php echo __('Are you sure want to move Request(s) to Trash ?');?>";


						//Find checkbox whether selected or not and do more action
						//============================================================
						if($('input[type="checkbox"]').is(':checked'))
						{
					   		 var ans = confirm(confirm_msg)
					   		 if(ans){
								 document.frmcompany.action="<?php echo URL_BASE;?>manageusers/trash_users_request/index";
								 document.frmcompany.submit();
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
</script>
