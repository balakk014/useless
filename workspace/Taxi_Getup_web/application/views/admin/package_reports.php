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
        <form method="post" class="form" name="managedriver" id="managedriver" action="packagereportsearch">

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
		<td align="left" width="5%"><?php echo __('sno_label'); ?></td>
		<td align="left" width="15%"><?php echo ucfirst(__('name')); ?></td>
		<td align="left" width="10%"><?php echo __('email_label'); ?></td>
		<td align="left" width="10%" ><?php echo __('package_name'); ?></td>
		<td align="left" width="10%" ><?php echo __('no_of_taxi'); ?></td>
		<td align="left" width="10%" ><?php echo __('no_of_driver'); ?></td>
		<td align="left" width="10%" ><?php echo __('package_price'); ?></td>
		<td align="left" width="10%" ><?php echo __('create_date'); ?></td>
		<td align="left" width="10%" ><?php echo __('expiry_date'); ?></td>
	</tr>
</thead>
<tbody>	
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
			<td><?php echo $sno; ?></td>
			<td><?php echo wordwrap($listings['name'],25,'<br />',1); ?></td>
			<td><?php echo wordwrap($listings['email'],25,'<br />',1); ?></td>						
			<td><?php echo wordwrap($listings['package_name'],25,'<br />',1); ?></td>	
			<td><?php echo wordwrap($listings['no_taxi'],25,'<br />',1); ?></td>					
			<td><?php echo wordwrap($listings['no_driver'],25,'<br />',1); ?></td>
			<td><?php echo wordwrap($listings['package_price'],25,'<br />',1); ?></td>
			<td><?php echo wordwrap($listings['credit_date'],25,'<br />',1); ?></td>
			<td><?php echo wordwrap($listings['expiry_date'],25,'<br />',1); ?></td>

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
<div class="clr">&nbsp;</div>
<div class="pagination">
		<?php if($total_company > 0): ?>
		 <p><?php echo $pag_data->render(); ?></p>  
		<?php endif; ?> 
  </div>
  <div class="clr">&nbsp;</div>

</div>
</div>




