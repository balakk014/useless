<?php defined('SYSPATH') OR die("No direct access allowed."); ?>

<!--Map & Location Autocomplete JS-->
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>
<script src="http://maps.google.com/maps/api/js?key=<?php echo GOOGLE_MAP_API_KEY; ?>&libraries=places,geometry&amp;sensor=false&callback=initialize" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/gmaps.js"></script>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-1.5.1.min.js"></script>
<!--Map & Location Autocomplete JS-->


<?php 	$link_button='<input type="button"  title="'.__('add_frequent_journey').'" class="button" value="'.__('add_frequent_journey').'" onclick="location.href=\''.URL_BASE.'tdispatch/add_frequentjourney/#stuff'.'\'" />';
?>
<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle">
       
			<div class="widget">
				<div class="title"><img src="<?php echo IMGPATH; ?>icons/dark/frames.png" alt="" class="titleIcon" /><h6><?php echo __('frequent_journey'); ?></h6>
				<div style="width:auto; float:right; margin:0 3px;">
				<div class="button greyishB"><?php echo $link_button; ?></div>                       

			</div>   
			</div>    
		
<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive" id="changetr">
<?php if(count($all_frequent_journey) > 0){ ?>
<thead>	
<tr>
	<td align="center" ><?php echo __('sno_label'); ?></td>
	<td align="center" ><?php echo __('journey_name'); ?></td>
	<td align="center" ><?php echo __('action'); ?></td>
</tr>
</thead>
<tbody>
           <?php $i=1; 
		 foreach($all_frequent_journey as $result) { 
		$trcolor=($i%2==0) ? 'oddtr' : 'eventr';  			 
			 ?>	
				<tr class="<?php echo $trcolor; ?>" id="addtr_<?php echo $result['fjid']; ?>">
					<td align="center"><?php echo $i; ?></td>
					<td align="center"><?php echo $result['journey_name']; ?></td>	
					<td align="center" width="30%"><a href="<?php echo URL_BASE.'tdispatch/edit_frequentjourney/'.$result['fjid'].'/#stuff';?>" title ="<?php echo __('edit'); ?>" class="editicon" ><a href="javascript:;" title ="<?php echo __('delete'); ?>" class="deleteicon" onclick="delete_details('<?php echo $result['fjid']; ?>')"></a></td>
				</tr>
	<?php $i++; } ?>

	<?php }else { ?>
			<tr>
				<td colspan="3" align="center"><?php echo __('no_data');?></td>
			</tr>
	<?php } ?>
</tbody>	
</table>	

	</div>
</div>

<div class="clr">&nbsp;</div>
<div class="pagination" id="hide_pagi">
		<?php if(count($all_frequent_journey) > 0): ?>
		 <p><?php echo $pag_data->render(); ?></p>  
		<?php endif; ?> 
  </div>
  <div class="clr">&nbsp;</div>

</div>
</div>

<script language="javascript" type="text/javascript">

 $(document).ready(function(){
	toggle(31);
});

function delete_details(fjid)
{

	var cancel_Submit = confirm("<?php echo __('delete_alert_adminjob'); ?>");				
	if(cancel_Submit == true)
	{
		document.location.href="<?php echo URL_BASE; ?>tdispatch/delete_frequentjourney/?fjid="+fjid;
	}

}

</script>
