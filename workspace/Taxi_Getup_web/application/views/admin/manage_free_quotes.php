<?php defined('SYSPATH') OR die("No direct access allowed.");

$status_val = isset($srch["status"]) ? $srch["status"] :''; 
$keyword = isset($srch["keyword"]) ? $srch["keyword"] :''; 

$total_contacts=count($all_free_quotes_list);
$total_contactslist=count($ContactsList);

if(isset($all_free_quotes_list)){
$table_css=$export_excel_button="";
if($total_contacts >0)
{ 
        $table_css='class="table_border"'; 
        $export_excel_button='<input type="button"  title="'.__('button_export').'" class="button" value="'.__('button_export').'" onclick="location.href=\''.URL_BASE.'manage/export?keyword='.$keyword.'\'" />';
}?>
<div class="container_content fl clr">
	<div class="cont_container mt15 mt10">
		<div class="content_middle"> 
<form method="post" class="form" name="managerating_form" id="managerating_form" action="free_quotes_search">
<table class="list_table1" border="0" width="65%" cellpadding="5" cellspacing="0">
 <tr>
                        <td valign="top"><label><?php echo __('keyword_label'); ?></label></td>
                        <td >
                            <div class="new_input_field">
                                <input type="text" name="keyword"  maxlength="256" id="keyword" value="<?php echo isset($srch['keyword']) ? trim($srch['keyword']) : ''; ?>" />
                            </div>
                            <span class="search_info_label"><?php echo __('search_by_free_quotes'); ?></span>
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
                                <input type="button" value="<?php echo __('button_cancel'); ?>" title="<?php echo __('button_cancel'); ?>" onclick="location.href = '<?php echo URL_BASE; ?>manage/free_quotes'" />
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
<?php if($total_contacts > 0){ ?>
<div class= "overflow-block">
<?php } ?>
<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive">
<?php if($total_contacts > 0){ ?>
<thead>
	<tr>
		<td align="left" width="5%"><?php echo __('sno_label'); ?></td>
		<td align="left" width="20%"><?php echo __('name_label'); ?></td>
		<td align="left" width="10%"><?php echo __('emaillabel'); ?></td>
		<td align="left" width="10%"><?php echo __('companyname'); ?></td>
		<td align="left" width="10%"><?php echo __('no_of_taxi'); ?></td>
		<td align="left" width="10%"><?php echo __('phone_number'); ?></td>
		<td align="left" width="10%"><?php echo __('message'); ?></td>
		<td align="left" width="10%"><?php echo __('download_date'); ?></td>
		<td align="left" width="10%"><?php echo __('action_label'); ?></td>
	</tr>
</thead>
<tbody>		
        <?php
        $sno=$Offset; /* For Serial No */
        foreach($all_free_quotes_list as $listings) {
        //S.No Increment
        $sno++;
        //For Odd / Even Rows
        //===================
        $trcolor=($sno%2==0) ? 'oddtr' : 'eventr';
        ?>     
        <tr class="<?php echo $trcolor; ?>">
			<td align="center"><?php echo $sno; ?></td>
			<td align="center"><a href="mailto:<?php echo $listings['email'];?>" title="mailto" target="_blank"><?php echo wordwrap(ucfirst($listings['name'])); ?></a></td>
			<td align="center"><?php echo wordwrap($listings['email']); ?></td>
			<td align="center"><?php echo wordwrap($listings['company_name']); ?></td>
			<td align="center"><?php echo wordwrap($listings['no_of_taxi']); ?></td>
			<td align="center"><?php echo wordwrap($listings['mobile_no']); ?></td>
			<td align="center"><?php if(strlen($listings['message']) > 100) { echo substr(wordwrap($listings['message']),0,100).'...';}else{ echo substr(wordwrap($listings['message']),0,50);} ?></td>
			<td align="center"><?php echo date("Y-m-d", strtotime($listings['createdate'])); ?></td>
			<td align="center"><a href="<?php echo URL_BASE.'manage/free_quotes_view/'.$listings['id'];?>" class="viewicon" title="View details"></a><?php echo '<a onclick="delete_free_quotes('.$listings["id"].');" title ="Delete" class="deleteicon"></a>' ; ?></td>

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
<?php if ($total_contacts > 0) { ?>
</div>
<?php } ?>
</form>
</div>
</div>
</div>
<div class="clr">&nbsp;</div>
<div class="pagination">
		<?php if(($action != 'contacts_search') && $total_contacts > 0): ?>
		 <p><?php echo $pag_data->render(); ?></p>  
		<?php endif; ?> 
  </div>
  <div class="clr">&nbsp;</div>

</div>
<?php } ?>
<script type="text/javascript">
 $(document).ready(function(){
  $("#keyword").focus(); 
	toggle(22);
});
var confirm_msg =  "<?php echo __('Do you want to delete this free quotes details');?>";
function delete_free_quotes(id){
	var ans = confirm(confirm_msg);
	if(ans){
		window.location='<?php echo URL_BASE ;?>manage/delete_free_quotes/'+id;
	}
}
</script>
