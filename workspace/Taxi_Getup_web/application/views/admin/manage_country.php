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

	$export_excel_button='
        				<input type="button"  title="'.__('button_export').'" class="button" value="'.__('button_export').'" 
        				onclick="location.href=\''.URL_BASE.'manage/export?keyword='.$keyword.'&status='.$status_val.'&type='.$user_type_val.'\'" />
    				';
}?>

<div class="container_content fl clr">
	<div class="cont_container mt15 mt10">
		<div class="content_middle">
			<form method="get" class="form" name="manage_country1" id="manage_country1" action="countrysearch">
				<table class="list_table1" border="0" width="65%" cellpadding="5" cellspacing="0">
					<tr>
						<td valign="top"><label><?php echo __('keyword_label'); ?></label></td>
						<td >
						    <div class="ser_input_field">
							<input type="text" name="keyword"  maxlength="256" id="keyword" value="<?php echo isset($srch['keyword']) ? trim($srch['keyword']) : ''; ?>" />
						    </div>
						    <span class="search_info_label"><?php echo __('search_by_countryname'); ?></span>
						</td>
						<td valign="top"><label><?php echo __('status_label'); ?></label></td>
						<td valign="top">
						<div class="formRight">
						 <div class="selector ser_input_field" id="uniform-user_type">
						 <span><?php echo __('status_label'); ?></span>
						    <select class="select2" name="status" id="status" onchange="this.form.submit()">
							<option value=""><?php echo __('status_label'); ?></option>    
							<?php
							foreach ($status as $status_key => $allstatus) {

							    $selected_status = ($status_val == $status_key) ? ' selected="selected" ' : " ";
							    ?>  
							    <option value="<?php echo $status_key; ?>"  <?php echo $selected_status; ?> ><?php echo $allstatus; ?></option>
							<?php }  ?>
						    </select>
						</div>    
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
							<input type="button" value="<?php echo __('button_cancel'); ?>" title="<?php echo __('button_cancel'); ?>" onclick="location.href = '<?php echo URL_BASE; ?>manage/country'" />
						    </div>
						</td>
					</tr>
				</table>
				</form>
              <div class="over_all">  
			<div class="widget">
				<div class="title"><h6><?php echo $page_title; ?></h6>
				<div style="width:auto; float:right; margin: 4px 3px;">
				<div class="button greyishB"> <?php //echo $export_excel_button; ?></div>                       

			</div>
		</div>

<form method="get" name="manage_country" id="manage_country" action="country">
<?php if($total_company > 0){ ?>
<div class= "overflow-block">
<?php } ?>
<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive">
<?php if($total_company > 0){ ?>
<thead>
	<tr>
	<td align="center" width="5%"><?php echo __('Select'); ?></td>
	<td align="center" width="5%" ><?php echo __('status_label'); ?></td>
	<td align="center" width="5%"><?php echo __('sno_label'); ?></td>
	<td align="left"  width="15%"><?php echo __('country_label').' '.__('name_label'); ?></td>
	<td align="left" width="15%"><?php echo __('iso_code'); ?></td>
	<td align="left" width="11%"><?php echo __('telephone_code'); ?></td>
	<td align="left" width="10%"><?php echo __('currency_code'); ?></td>
	<td align="left" width="12%"><?php echo __('currency_symbol'); ?></td>
	<td align="center" width="10%" ><?php echo __('action_label'); ?></td>
	<td align="left" width="20%" ><?php echo __('default_country'); ?></td>
	</tr>
	</thead>
	<tbody>
		
		<?php
		/* For Serial No */
		$sno=$Offset;
		foreach($all_company_list as $listings) { 
		 //S.No Increment
		 //==============
		 $sno++;        
		//For Odd / Even Rows
		//===================
		$trcolor=($sno%2==0) ? 'oddtr' : 'eventr';  
		 
		?>     

		<tr class="<?php echo $trcolor; ?>">
                    <td align="center">
                        <input type="checkbox" name="uniqueId[]" id="trxn_chk<?php echo $listings['country_id'];?>" value="<?php echo $listings['country_id'];?>" />
                    </td>
                    <td align="center"> 
                         <?php 
                             if($listings['country_status']=='A')
                             {  $txt = "Active"; $class ="unsuspendicon";    }
				elseif($listings['country_status']=='T')
				{$txt = "Trash"; $class ="trashicon";}
                             else{  $txt = "Deactive"; $class ="blockicon";      }


                             echo '<a  title ='.$txt.' class='.$class.'></a>' ;  
                         ?>                    
			</td> 
			<td align="center"><?php echo $sno; ?></td>
			<td align="left"><?php echo $listings['country_name']; ?></td>
			<td align="left"><?php echo $listings['iso_country_code']; ?></td>
			<td align="left"><?php echo $listings['telephone_code']; ?></td>
			<td align="left"><?php echo $listings['currency_code']; ?></td>
			<td align="center"><?php echo $listings['currency_symbol']; ?></td>
			<td align="center"><?php echo '<a href='.URL_BASE.'edit/country/'.$listings['country_id'].' " title ="Edit" class="editicon"></a>' ; ?></td>
			<td align="center"><input type="radio" name="default_country" <?php if($listings['default'] == 1){?>checked = "checked"<?php }?> value="<?php echo $listings['country_id']; ?>"/></td>
			
			
		</tr>
		<?php }
		if($sno >=1){
		?>
			<tr>
				
				<td colspan="10" align="right"><div class="new_button"><input type="submit" name="update" value="<?php echo __('update');?>" /></div></td>
			</tr>   
		<?php }else { ?>
			<tr>
				<td colspan="7" align="center"><?php echo __('no_data');?></td>
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
	
		</tbody>
	</table>

<?php if ($total_company > 0) { ?>
</div>
<?php } ?>
</form>
</div>
</div>
</div>
 <div class="bottom_contenttot">

<!--** Multiple select starts Here ** -->
<?php if(count($all_company_list) > 0)
       { ?>
          <div class="select_all manage_fag">
                <ul><li><b><a id="select-all"><?php echo __('all_label');?></a></b></li>
				
				<li><span class="pr5 pl5">|</span></li><li><b><a id="select-none"><?php echo __('select_none');?></a></b></li>
				</ul>

                <span class="more_selection">
                    <select name="more_action" id="more_action">
                        <option value=""><?php echo __('Change Status'); ?></option>
                        <option value="block_country_request" ><?php echo __('Block'); ?></option>
                        <option value="active_country_request" ><?php echo __('Active'); ?></option>
                        <option value="trash_country_request" ><?php echo __('Trash'); ?></option>
                    </select>
                 </span>
	        </div>
        <?php
        } ?>
<!--** Multiple select ends Here ** -->
<div class="pagination">
		<?php if($total_company > 0): ?>
		 <?php echo $pag_data->render(); ?>
		<?php endif; ?> 
  </div>
</div>
</div>
</div>
<script type="text/javascript">

$(document).ready(function(){

 $("#keyword").focus(); 
});

$('#select-all').click(function(event) {   
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
});

$('#select-none').click(function(event) {   
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = false;                        
        });
});

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

					case "block_country_request":
					var confirm_msg =  "<?php echo __('Are you sure want to block Request(s)?');?>";
	
					//Find checkbox whether selected or not and do more action
					//============================================================
					if($('input[type="checkbox"]').is(':checked'))
					{
				   		 var ans = confirm(confirm_msg)
				   		 if(ans){
							 document.manage_country.action="<?php echo URL_BASE;?>manage/block_country_request";
							 document.manage_country.submit();
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

					case "active_country_request":
					var confirm_msg =  "<?php echo __('Are you sure want to Activate Request(s)?');?>";


						//Find checkbox whether selected or not and do more action
						//============================================================
						if($('input[type="checkbox"]').is(':checked'))
						{
					   		 var ans = confirm(confirm_msg)
					   		 if(ans){
								 document.manage_country.action="<?php echo URL_BASE;?>manage/active_country_request";
								 document.manage_country.submit();
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

					case "trash_country_request":
					var confirm_msg =  "<?php echo __('Are you sure want to move Request(s) to Trash ?');?>";


						//Find checkbox whether selected or not and do more action
						//============================================================
						if($('input[type="checkbox"]').is(':checked'))
						{
					   		 var ans = confirm(confirm_msg)
					   		 if(ans){
								 document.manage_country.action="<?php echo URL_BASE;?>manage/trash_country_request";
								 document.manage_country.submit();
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
