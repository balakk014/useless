<?php defined('SYSPATH') OR die("No direct access allowed.");
//$tagcount = count($site_info_settings);
?>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>
<script type='text/javascript'>
        function addFields(){
            // Number of inputs to create
            var number = document.getElementById("member").value;
            // Container <div> where dynamic content will be placed
            var container = document.getElementById("inputfields");
            // Clear previous contents of the container
            while (container.hasChildNodes()) {
                container.removeChild(container.lastChild);
            }
            if(number<=5){
		    for (i=0;i<number;i++){
			// Append a node with a random text
			container.appendChild(document.createTextNode("Enter site tags description " + (i+1)));
			// Create an <input> element, set its type and name attributes
			var input = document.createElement("input");
			input.type = "text";
			input.name = "member" + i;
			container.appendChild(input);
			// Append a line break 
			container.appendChild(document.createElement("br"));
		    }
		}else
		{
			alert('The total no of tags not greater than 5');
		}
        }
</script>
<style type="text/css">
.inputfields .form input[type="text"]
{
width:350px;
}
.errorvalid{padding:0px;}
</style>
<div class="container_content fl clr">
	<div class="cont_container mt15 mt10">
		<div class="content_middle"> 
			<form method="post" enctype="multipart/form-data" class="form" name="banner_form" id="banner_form" action="" >
					<table border="0" cellpadding="5" cellspacing="0" width="100%">
					
					<tr>
						<td valign="top" width="20%">
							<label><?php echo __('image_tag'); ?></label><span class="star">*</span>
						</td>  
						<td>
						<div class="new_input_field">
						<input type="text" class="required" id="image_tag" name="image_tag" value="<?php if(isset($postvalue) && array_key_exists('image_tag',$postvalue)){ echo $postvalue['image_tag']; }?>" maxlength="100" title="<?php echo __('enter_image_tag'); ?>" >
						 <?php if(isset($errors) && array_key_exists('image_tag',$errors)){ echo "<span class='error'>".ucfirst($errors['image_tag'])."</span>";}?>
						</div>						
						</td>      					
					</tr>

					<tr>
						<td valign="top" width="20%">
							<label><?php echo __('alt_tags'); ?></label><span class="star">*</span>
						</td>  
						<td>
						<div class="new_input_field">
						<input type="text" class="required" id="tags" name="tags" value="<?php if(isset($postvalue) && array_key_exists('tags',$postvalue)){ echo $postvalue['tags']; }?>"  maxlength="100"  title="<?php echo __('enter_alt_tags'); ?> " >
						<?php if(isset($errors) && array_key_exists('tags',$errors)){ echo "<span class='error'>".ucfirst($errors['tags'])."</span>";}?>
						</div>						
						</td>      					
					</tr>	
					<tr>
						<td valign="top" width="20%">
							<label><?php echo __('banner_image'); ?></label><span class="star">*</span>
						</td>        
						<td>
							<div class="new_input_field">
							<input type="file" class=" imageonly" name="banner_image" title="<?php echo __('select_taxi_image'); ?>" value="<?php if(isset($postvalue) && array_key_exists('banner_image',$postvalue)){ echo $postvalue['banner_image']; }?>">
							<?php if(isset($errors) && array_key_exists('banner_image',$errors)){ echo "<span class='error'>".ucfirst($errors['banner_image'])."</span>";}?>
							</div>
							<br/><p><?php echo __('banner_image_width_height'); ?></p>
						</td>
					</tr>
					</table>
					<div id="inputfields" class="new_input_field"></div>
					<div class="button dredB" style="margin-left:210px;">   <input type="reset" value="<?php echo __('button_reset'); ?>" title="<?php echo __('button_reset'); ?>" /></div>
					<div class="button greenB">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_banner" title="<?php echo __('submit' );?>" />
					
					</div>
					<p>&nbsp;</p>
				
				
			</form>
		</div>
	</div>
</div>
<div class="clr">&nbsp;</div>
<script type="text/javascript">
 $(document).ready(function(){

var field_val = $("#image_tag").val();
$("#image_tag").focus().val("").val(field_val);

  	
	 jQuery("#banner_form").validate();
	 
	toggle(34);
	$.validator.addMethod( "imageonly", function(value,element){
var pathLength = value.length; var lastDot = value.lastIndexOf( "."); var fileType = value.substring(lastDot,pathLength).toLowerCase(); return this.optional(element) || fileType.match(/(?:.jpg|.jpeg|.png)$/) }, "Please upload image(jpg,jpeg,png) files only");

});   

</script>
