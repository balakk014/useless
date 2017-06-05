<?php defined('SYSPATH') OR die("No direct access allowed."); 


//For search values
//=================
$user_type_val = isset($srch["user_type"]) ? $srch["user_type"] :''; 
$status_val = isset($srch["status"]) ? $srch["status"] :''; 							
$keyword = isset($srch["keyword"]) ? $srch["keyword"] :''; 

//For CSS class deefine in the table if the data's available
//===========================================================
$total_fundrequest=count($all_fundrequest_list);

$table_css=$export_excel_button="";
if($total_fundrequest>0)
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
<?php if($total_fundrequest > 0){ ?>
<div class= "overflow-block">
<?php } ?>		
<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive">
<?php if($total_fundrequest > 0){ ?>
<thead>
	<tr>
		<td align="left" width="5%"><?php echo __('sno_label'); ?></td>
		<td align="left" width="15%"><?php echo __('requeston'); ?></td>
		<td align="left" width="10%"><?php echo __('amount'); ?>(<?php echo CURRENCY;?>)</td>
		<td align="left" width="10%" ><?php echo __('status'); ?></td>
		<td align="left" width="10%" ><?php echo __('paymentstatus'); ?></td>


	</tr>
</thead>
<tbody>	
		<?php

         $sno=$Offset; /* For Serial No */

		 foreach($all_fundrequest_list as $listings) {

		 //S.No Increment
		 //==============
		 $sno++;
        
         //For Odd / Even Rows
         //===================
         $trcolor=($sno%2==0) ? 'oddtr' : 'eventr';  
		 
        ?>     

        <tr class="<?php echo $trcolor; ?>">
			<td align="center"><?php echo $sno; ?></td>
			<td align="center"><?php echo $listings['requested_date']; ?></td>
			<td align="center"><?php echo $listings['amount']; ?></td>
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
	toggle(26);
});

</script>



