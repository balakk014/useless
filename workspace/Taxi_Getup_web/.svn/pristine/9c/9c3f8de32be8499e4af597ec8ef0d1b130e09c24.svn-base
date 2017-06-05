<?php defined('SYSPATH') OR die("No direct access allowed."); 


//For search values
//=================
$user_type_val = isset($srch["user_type"]) ? $srch["user_type"] :''; 
$status_val = isset($srch["status"]) ? $srch["status"] :''; 							
$keyword = isset($srch["keyword"]) ? $srch["keyword"] :''; 

//For CSS class deefine in the table if the data's available
//===========================================================
$total_company=count($all_banner_list);


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
<form method="get" class="form" name="manage_banner" id="manage_banner" action="bannersearch">
<table class="list_table1" border="0" width="65%" cellpadding="5" cellspacing="0">
 <tr>
                        <td valign="top"><label><?php echo __('keyword_label'); ?></label></td>
                        <td >
                            <div class="new_input_field">
                                <input type="text" name="keyword"  maxlength="256" id="keyword" value="<?php echo isset($srch['keyword']) ? trim($srch['keyword']) : ''; ?>" />
                            </div>
                            <span class="search_info_label"><?php echo __('search_by_banner'); ?></span>
                        </td>

                        <td valign="top"><label><?php echo __('status_label'); ?></label></td>
                        <td valign="top">
			<div class="formRight">
			<div class="selector" id="uniform-user_type">
			<span><?php echo __('status_label'); ?></span>
                            <select class="select2" name="status" id="status" onchange="this.form.submit()">
                                <option value=""><?php echo __('status_label'); ?></option>
                                <option value="1" <?php if($status_val == '1') { echo 'selected=selected'; } ?> >Published</option>
                                <option value="0" <?php if($status_val == '0') { echo 'selected=selected'; } ?> >Unpublished</option>
                            </select>
			</div>
			</div>    
                        </td>
			</tr>
			<tr>
                        <td valign="top"><label>&nbsp;</label></td>
                        <td>
                            <!--[if IE]>
                            <input type="text" style="display: none;" disabled="disabled" size="1" />
                            <![endif]-->
                            <div class="button brownB">
                                <input type="submit" value="<?php echo __('button_search'); ?>" name="search_user" title="<?php echo __('button_search'); ?>" />
                            </div>
                            <div class="button blueB">
                                <input type="button" value="<?php echo __('button_cancel'); ?>" title="<?php echo __('button_cancel'); ?>" onclick="location.href = '<?php echo URL_BASE; ?>manage/banner'" />
                            </div>
                        </td>
                    </tr>
                </table>

     		<div class="widget">
		<div class="title"><img src="<?php echo IMGPATH; ?>icons/dark/frames.png" alt="" class="titleIcon" /><h6><?php echo $page_title; ?></h6>
		<div style="width:auto; float:right; margin: 4px 3px;">
		<div class="button greyishB"> <?php //echo $export_excel_button; ?></div>                       
		</div>
		</div>
		

<?php if($total_company > 0){ ?>
<div class= "overflow-block">
<?php } ?>
<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive">
<?php if($total_company > 0){ ?>
<thead>

	<tr>
		<td align="left" width="10%"><?php echo __('select'); ?></td>
		<td align="left" width="10%"><?php echo __('sno'); ?></td>
		<td align="left" width="15%"><?php echo __('Status'); ?></td>
		<td align="left" style="text-align:left;" width="20%"><?php echo __('image_tag'); ?></td>
		<td align="left" style="text-align:left;" width="20%"><?php echo __('alt_tags'); ?></td>		
		<td align="left" width="15%"><?php echo __('image'); ?></td>
		<td align="left" width="20%" ><?php echo __('action_label'); ?></td>
	</tr>
	</thead>
	<tbody>
		<?php

         $sno=$Offset; /* For Serial No */

		 foreach($all_banner_list as $listings) {
		 
		 //S.No Increment
		 //==============
		 $sno++;
        
         //For Odd / Even Rows
         //===================
         $trcolor=($sno%2==0) ? 'oddtr' : 'eventr';  
		 
        ?>     

        <tr class="<?php echo $trcolor; ?>">
                    <td align="center" width="10%"><input type="checkbox" name="uniqueId[]" id="trxn_chk<?php echo $listings['id'];?>" value="<?php echo $listings['id'];?>" />
                    </td>
                    <td align="center" width="10%"><?php echo $sno; ?></td>
                    <td align="center" width="15%"> 
                         <?php 
                             if($listings['status']=='1')
                             {  $txt = "Published"; $class ="unsuspendicon";    }
				elseif($listings['status']=='T')
				{$txt = "Trash"; $class ="trashicon";}
                             else{  $txt = "Unpublished"; $class ="blockicon";      }


                             echo '<a  title ='.$txt.' class='.$class.'></a>' ;  
                         ?>  
                    </td> 
			
			<td align="left" width="15%"><?php echo ucfirst($listings['image_tag']); ?></td>
			<td align="left" width="20%"><?php echo ucfirst($listings['alt_tags']); ?></td>
			<td align="center" width="10%">
				<?php if(!empty($listings['banner_image'])&&file_exists(DOCROOT.BANNER_IMGPATH.$listings['banner_image'])){ ?>
								<img height="75px" width="75px" src="<?php echo URL_BASE.BANNER_IMGPATH.$listings['banner_image'];?>">
							<?php  } ?>
			</td>
			<td align="center" colspan='3' ><?php echo '<a href='.URL_BASE.'edit/banner/'.$listings['id'].' " title ="Edit" class="editicon"></a>' ; ?></td>
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
</form>
</div>
</div>
</form>
<div class="bottom_contenttot">
 <!--** Multiple select starts Here ** -->
<?php if(count($all_banner_list) > 0)
       { ?>
          <div class="select_all">
              <ul><li><b><a href="javascript:selectToggle(true, 'manage_banner');"><?php echo __('all_label');?></a></b></li>
                  <li><span class="pr2 pl2">|</span></li><li><b><a href="javascript:selectToggle(false, 'manage_banner');"><?php echo __('select_none');?></a></b></li></ul>

                <span class="more_selection">
                    <select name="more_action" id="more_action">
                        <option value=""><?php echo __('Change Status'); ?></option>
                        <option value="block_banner_request" >Unpublish</option>
                        <option value="active_banner_request" >Publish</option>
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
</div>

<script type="text/javascript">
$(document).ready(function(){
 $("#keyword").focus(); 
	toggle(34);
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

					case "block_banner_request":
					var confirm_msg =  "<?php echo __('Are you sure want to block Request(s)?');?>";
	
					//Find checkbox whether selected or not and do more action
					//============================================================
					if($('input[type="checkbox"]').is(':checked'))
					{
				   		 var ans = confirm(confirm_msg)
				   		 if(ans){
							 document.manage_banner.action="<?php echo URL_BASE;?>manage/block_banner_request/index";
							 document.manage_banner.submit();
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

					case "active_banner_request":
					var confirm_msg =  "<?php echo __('Are you sure want to Activate Request(s)?');?>";


						//Find checkbox whether selected or not and do more action
						//============================================================
						if($('input[type="checkbox"]').is(':checked'))
						{
					   		 var ans = confirm(confirm_msg)
					   		 if(ans){
								 document.manage_banner.action="<?php echo URL_BASE;?>manage/active_banner_request/index";
								 document.manage_banner.submit();
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
