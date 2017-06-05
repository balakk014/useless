<?php defined('SYSPATH') OR die("No direct access allowed.");

//For search values
//=================
$status_val = isset($srch["status"]) ? $srch["status"] :''; 	
					
//For CSS class deefine in the table if the data's available
//===========================================================
$total_mile=count($all_mile_list);
$total_milelist=count($MileList);

if(isset($all_mile_list)){
$table_css=$export_excel_button="";
?>

<div class="container_content fl clr">
	<div class="cont_container mt15 mt10">
		<div class="content_middle"> 
<form method="post" class="form" name="manage_mile" id="manage_mile" action="contacts_search">
       		<div class="widget">
		<div class="title"><img src="<?php echo IMGPATH; ?>icons/dark/frames.png" alt="" class="titleIcon" /><h6><?php echo $page_title; ?></h6>
		<div style="width:auto; float:right; margin: 4px 3px;">
		<div class="button greyishB"> <?php //echo $export_excel_button; ?></div>                       

		</div>
		</div>
<?php if($total_mile > 0){ 

?>
<div class= "overflow-block">
<?php } ?>
<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive">
<?php if($total_mile > 0){ ?>
<thead>
	<tr>		
		<td align="left" width="5%"><?php echo __('select_miles'); ?></td>
		<td align="left" width="5%"><?php echo __('status_label'); ?></td>
		<td align="left" width="5%"><?php echo __('sno_label'); ?></td>
		<td align="left" width="20%"><?php echo __('miles_label'); ?></td>
		<td align="left" width="10%"><?php echo __('action_label'); ?></td>
	</tr>
</thead>
<tbody>		
		<?php

         $sno=$Offset; /* For Serial No */

		 foreach($all_mile_list as $listings) {
		 //S.No Increment
		 $sno++;
		 //For Odd / Even Rows
		 //===================
		 $trcolor=($sno%2==0) ? 'oddtr' : 'eventr';  
					 
        ?>     

        <tr class="<?php echo $trcolor; ?>">
        
			 <td><input type="checkbox" name="uniqueId[]" id="trxn_chk<?php echo $listings['id'];?>" value="<?php echo $listings['id'];?>" /> </td>
			 <td>
			  <?php 
                             if($listings['mile_status']=='A')
                             {  $txt = "Active"; $class ="unsuspendicon";    }
				elseif($listings['mile_status']=='T')
				{$txt = "Trash"; $class ="trashicon";}
                             else{  $txt = "Deactive"; $class ="blockicon";      }


                             echo '<a  title ='.$txt.' class='.$class.'></a>' ;  
                         ?> 
                         </td>
			<td align="center"><?php echo $sno; ?></td>
			<td align="center"><?php echo ucfirst($listings['mile_name']).' '.__('miles_label'); ?></td>
			<td align="center"><a href="<?php echo URL_BASE.'edit/mile/'.$listings['id'];?>" class="editicon" title="View details"></a><?php echo '<a onclick="delete_mile('.$listings["id"].');" title ="Delete" class="deleteicon"></a>' ; ?></td>

		</tr>
		<?php } 
 		 } 
		 
		//For No Records
	     else{ ?>
       	<tr>
        	<td class="nodata"><?php echo __('no_data'); ?></td>
        </tr>
		<?php } ?>
	</tbody>
</table>
<?php if ($total_mile > 0) { ?>
</div>
<?php } ?>
</form>
</div>
</div>
</div>
<div class="clr">&nbsp;</div>
<div class="pagination">
		<?php if(($action != 'mile_search') && $total_mile > 0): ?>
		 <p><?php echo $pag_data->render(); ?></p>  
		<?php endif; ?> 
  </div>
  <div class="clr">&nbsp;</div>

	<!--** Multiple select starts Here ** -->
	<?php if(count($all_mile_list) > 0)
	       { ?>
		  <div class="select_all">
		        <b><a id="select-all"><?php echo __('all_label');?></a></b><span class="pr2 pl2">|</span><b><a id="select-none"><?php echo __('select_none');?></a></b>

		       <span class="more_selection">
		            <select name="more_action" id="more_action">
		                <option value=""><?php echo __('Change Status'); ?></option>
		                <option value="block_mile_request" ><?php echo __('Block'); ?></option>
		                <option value="active_mile_request" ><?php echo __('Active'); ?></option>
		                <option value="trash_mile_request" ><?php echo __('Trash'); ?></option>
		            </select>
		         </span>
			</div>
	<?php
	} ?>
	<!--** Multiple select ends Here ** -->

</div>
<?php } ?>
<script type="text/javascript">
 $(document).ready(function(){
	toggle(26);
});

var delete_confirm_msg =  "<?php echo __('Do you want to delete this menu');?>";
function delete_mile(id){
	var ans = confirm(delete_confirm_msg);
	if(ans){
		window.location='<?php echo URL_BASE ;?>manage/delete_mile/'+id;
	}
}

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

					case "block_mile_request":
					var confirm_msg =  "<?php echo __('Are you sure want to block Request(s)?');?>";
	
					//Find checkbox whether selected or not and do more action
					//============================================================
					if($('input[type="checkbox"]').is(':checked'))
					{
				   		 var ans = confirm(confirm_msg)
				   		 if(ans){
							 document.manage_mile.action="<?php echo URL_BASE;?>manage/block_mile_request/index";
							 document.manage_mile.submit();
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

					case "active_mile_request":
					var confirm_msg =  "<?php echo __('Are you sure want to Activate Request(s)?');?>";


						//Find checkbox whether selected or not and do more action
						//============================================================
						if($('input[type="checkbox"]').is(':checked'))
						{
					   		 var ans = confirm(confirm_msg)
					   		 if(ans){
								 document.manage_mile.action="<?php echo URL_BASE;?>manage/active_mile_request/index";
								 document.manage_mile.submit();
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

					case "trash_mile_request":
					var confirm_msg =  "<?php echo __('Are you sure want to move Request(s) to Trash ?');?>";


						//Find checkbox whether selected or not and do more action
						//============================================================
						if($('input[type="checkbox"]').is(':checked'))
						{
					   		 var ans = confirm(confirm_msg)
					   		 if(ans){
								 document.manage_mile.action="<?php echo URL_BASE;?>manage/trash_mile_request/index";
								 document.manage_mile.submit();
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
