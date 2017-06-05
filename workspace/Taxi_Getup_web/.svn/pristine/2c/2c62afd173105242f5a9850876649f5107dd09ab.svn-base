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

<?php $back_action = URL_BASE.'manage/packagereport';  ?>
<script type="text/javascript">
	$(function(){
		$(".wmd-view-topscroll").scroll(function(){
			$(".wmd-view")
				.scrollLeft($(".wmd-view-topscroll").scrollLeft());
		});
		$(".wmd-view").scroll(function(){
			$(".wmd-view-topscroll")
				.scrollLeft($(".wmd-view").scrollLeft());
		});
	});
</script>
	<div class="cont_container mt15 mt10">
		<div class="content_middle"> 
        <form method="get" class="form" name="managedriver" id="managedriver" action="">
		<table class="list_table1" border="0" width="35%" cellspacing="0" cellpadding="5" >
				<tr>				
					<td valign="middlle"><label><?php echo __('company'); ?></label></td>
					<td valign="top">
						<div class="selector" id="uniform-user_type">
							<select class="select2" name="filter_company" id="filter_company" >
								
									<option value="All"><?php echo __('all_label'); ?></option>    
								<?php 
									foreach ($rcompany_list as $comapany_list) {
									
								?>  
									<option value="<?php echo $comapany_list['cid']; ?>" <?php if(isset($srch['filter_company'])){if($srch['filter_company']==$comapany_list['cid']){echo "selected";} } ?>><?php echo ucfirst($comapany_list['company_name']); ?></option>
									<?php }  ?>
							</select>
						</div>
						<div id="filter_company_error" class="error"></div>
					</td>  
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
</div></div>

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
		<div class="wmd-view-topscroll">
    <div class="scroll-div1">
    </div>
</div>
<div class="wmd-view">
    <div class="scroll-div2">
<div class= "overflow-block">
<?php } ?>		
<table cellspacing="1" cellpadding="10" width="100%" align="center" style="border-top:1px solid #cdcdcd;" class="sTable responsive">
<?php if($total_company > 0){ ?>
<thead>
	<tr>
		<td align="left" width="5%"><?php echo __('sno_label'); ?></td>
		<td align="left" style="text-align:left;" width="10%"><?php echo ucfirst(__('name')); ?></td>
		<td align="left" style="text-align:left;" width="10%"><?php echo __('package_name'); ?></td>
		<td align="left" style="text-align:left;" width="10%"><?php echo __('email_label'); ?></td>		
		<td align="left" style="text-align:left;" width="13%"><?php echo __('package_type'); ?></td>
		<td align="left" width="10%" ><?php echo __('no_of_taxi'); ?></td>
		<td align="left" width="10%" ><?php echo __('no_of_driver'); ?></td>
		<td align="left" width="10%" ><?php echo __('package_price'); ?></td>
		<td align="left" style="text-align:left;" width="11%"><?php echo __('create_date'); ?></td>
		<td align="left" style="text-align:left;" width="13%"><?php echo __('expiry_date'); ?></td>
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
			<td style="text-align:center;"><?php echo $sno; ?></td>
			<td><?php echo wordwrap($listings['name'],25,'<br />',1); ?></td>
			<td><?php echo wordwrap($listings['package_name'],25,'<br />',1); ?></td>
			<td><?php echo wordwrap($listings['email'],25,'<br />',1); ?></td>						
			<td><?php if($listings['package_type'] == 'T' ) { echo __('transaction_based_commission'); } else if($listings['package_type'] == 'P' ) { echo __('package_based_commission'); } else if($listings['package_type'] == 'N' ) { echo __('package_based_no_commission'); } ?></td>	
			<td style="text-align:center;"><?php echo wordwrap($listings['no_taxi'],25,'<br />',1); ?></td>		
			<td style="text-align:center;"><?php echo wordwrap($listings['no_driver'],25,'<br />',1); ?></td>
			<td style="text-align:center;"><?php echo wordwrap($listings['package_price'],25,'<br />',1); ?></td>
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
</div>
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

<script type="text/javascript" language="javascript">
$(document).ready(function(){

});

</script>



