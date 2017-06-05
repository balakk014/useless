<?php defined('SYSPATH') OR die("No direct access allowed."); ?>
<link rel="stylesheet" href="<?php echo URL_BASE; ?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
<script src="<?php echo URL_BASE; ?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="<?php echo URL_BASE; ?>public/js/datetimehrspicker/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE; ?>public/js/validation/jquery.validate.js"></script>
<?php
$file_path = $_SERVER["DOCUMENT_ROOT"] . '/public/' . UPLOADS . '/taxi_image/';
$field_count = count($additional_fields);
?>
<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
        <div class="content_middle"> 
            <form name="edittaxi_form" class="form" id="edittaxi_form" action="" method="post" enctype="multipart/form-data">
                <table border="0" cellpadding="5" cellspacing="0" width="100%">                             
                    <tr>
                        <td><h2 class="tab_sub_tit"><?php echo ucfirst(__('taxi_inform')); ?></h2></td>
                        <td></td>	          
                    </tr>
					<?php 
						if(isset($company_details[0]['taxi_no'])){
							$taxi_no = $company_details[0]['taxi_no'];
						}else{
							if(isset($postvalue['taxi_no'])){
								$taxi_no = $postvalue['taxi_no'];
							}else{
								$taxi_no = "";
							}
						}
						?>
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('taxi_no'); ?></label><span class="star">*</span></td>        
                        <td>
                            <div class="new_input_field">
                                <input type="text"  maxlength="30" minlength="4" class="required" title="<?php echo __('enter_taxi_no'); ?>" id="taxi_no" name="taxi_no" value="<?php echo $taxi_no; ?>" />
<?php if (isset($errors) && array_key_exists('taxi_no', $errors)) {
    echo "<span class='error'>" . ucfirst($errors['taxi_no']) . "</span>";
} ?>
                            </div>
                        </td>   	
                    </tr>

                    <?php /* <tr>
                      <?php $field_type =''; $field_type =  isset($company_details[0]['taxi_type']) &&!array_key_exists('taxi_type',$postvalue)? trim($company_details[0]['taxi_type']):$postvalue['taxi_type']; ?>
                      <td valign="top" width="20%"><label><?php echo __('taxi_type'); ?></label><span class="star">*</span></td>
                      <td>
                      <div class="formRight">
                      <div class="selector" id="uniform-user_type">
                      <span><?php echo __('select_label'); ?></span>
                      <select name="taxi_type" id="taxi_type" class="required" title="<?php echo __('select_the_taxitype'); ?>">
                      <option value="">--Select--</option>
                      <?php foreach($motor_details as $listings) { ?>
                      <option value="<?php echo $listings['motor_id']; ?>" <?php if($field_type == $listings['motor_id']) { echo 'selected=selected'; } ?>><?php echo ucfirst($listings['motor_name']); ?></option>
                      <?php } ?>
                      </select>
                      </div>
                      </div>
                      <?php if(isset($errors) && array_key_exists('taxi_type',$errors)){ echo "<span class='error'>".ucfirst($errors['taxi_type'])."</span>";}?>

                      </td>
                      </tr> */ ?>

                        <?php if ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'DA') {
                            ?>	
                            <?php 
		if(isset($company_details[0]['company_name'])){
			$company_name = $company_details[0]['company_name'];
		}else{
			if(isset($postvalue['company_name'])){
				$company_name = $postvalue['company_name'];
			}else{
				$company_name = "";
			}
		}
		?>
                        <tr>
    <?php $field_type =  isset($company_details[0]['taxi_company']) &&!array_key_exists('company_name',$postvalue)? trim($company_details[0]['taxi_company']):$postvalue['company_name']; ?>
                            <td valign="top" width="20%"><label><?php echo __('taxicompany'); ?></label><span class="star">*</span></td><td>
                                <div class="formRight">
                                    <div class="selector" id="uniform-user_type">
                                        <span><?php echo __('select_label'); ?></span>
                                        <div id="taxicompany_list">
                                            <select name="company_name" id="company_name" class="required" title="<?php echo __('select_the_company'); ?>" onchange="getcountry(this.value,'','')">
                                                <option value="">--Select--</option>
    <?php foreach ($taxicompany_details as $company_list) {
			$companyName = (isset($company_list['company_brand_type']) && $company_list['company_brand_type'] == 'S') ? ucfirst($company_list["company_name"]).' - Admin' : ucfirst($company_list["company_name"]);
		 ?>
                                                    <option value="<?php echo $company_list['cid']; ?>" <?php if ($field_type == $company_list['cid']) {
            echo 'selected=selected';
        } ?> ><?php echo $companyName; ?></option>
                                <?php } ?>
                                            </select>
                                        </div>	
                                    </div></div>
                                <label for="company_name" generated="true" style="display:none" class="errorvalid"><?php echo __('select_the_company'); ?></label>	
    <?php if (isset($errors) && array_key_exists('company_name', $errors)) {
        echo "<span class='error'>" . ucfirst($errors['company_name']) . "</span>";
    } ?>
                            </td>      
                        </tr>	
                                <?php } else {
                                    ?> 
                        <tr>
                            <td valign="top" width="20%"></td>
                            <td>
                                <div class="new_input_field">		
                                    <input type="hidden" name="company_name" id="company_name" value="<?php echo $_SESSION['company_id']; ?>">
                            <?php if (isset($errors) && array_key_exists('company_name', $errors)) {
                                echo "<span class='error'>" . ucfirst($errors['company_name']) . "</span>";
                            } ?>
                                </div>
                            </td>
                        </tr>	
<?php }
?>

                    <tr>
                                            <?php $field_type =''; $field_type =  isset($company_details[0]['taxi_model']) &&!array_key_exists('taxi_model',$postvalue)? trim($company_details[0]['taxi_model']):$postvalue['taxi_model']; ?>
                        <td valign="top" width="20%"><label><?php echo __('taxi_model'); ?></label><span class="star">*</span></td>        
                        <td>
                            <div class="formRight">
                                <div class="selector" id="uniform-user_type">
                                    <span><?php echo __('select_label'); ?></span>
                                    <div --id="model_list">
                                        <select name="taxi_model" id="taxi_model" onchange="getTaxiSpeed(this.value)" class="required" title="<?php echo __('select_the_taximodel'); ?>">
                                            <option value="">--Select--</option>
<?php foreach ($model_details as $list) { ?>
                                                <option value="<?php echo $list['model_id']; ?>" <?php if ($field_type == $list['model_id']) {
        echo 'selected=selected';
    } ?>><?php echo ucfirst($list['model_name']); ?></option>
<?php } ?>
                                        </select>
                                    </div>      
                                </div>
                            </div>
                            <label for="taxi_model" generated="true" style="display:none" class="errorvalid"><?php echo __('select_the_taximodel'); ?></label>
<?php if (isset($errors) && array_key_exists('taxi_model', $errors)) {
    echo "<span class='error'>" . ucfirst($errors['taxi_model']) . "</span>";
} ?>
                        </td>   	
                    </tr>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('taxi_owner_name'); ?></label><span class="star">*</span></td>        
                        <td>
                            <div class="new_input_field">
                                <input type="text" title="<?php echo __('enter_taxi_owner_name'); ?>" class="required" id="taxi_owner_name" name="taxi_owner_name" value="<?php echo isset($company_details[0]['taxi_owner_name']) &&!array_key_exists('taxi_owner_name',$postvalue)? trim($company_details[0]['taxi_owner_name']):$postvalue['taxi_owner_name']; ?>" />
<?php if (isset($errors) && array_key_exists('taxi_owner_name', $errors)) {
    echo "<span class='error'>" . ucfirst($errors['taxi_owner_name']) . "</span>";
} ?>
                            </div>
                        </td>   	
                    </tr>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('taxi_manufacturer'); ?></label><span class="star">*</span></td>        
                        <td>
                            <div class="new_input_field">
                                <input type="text" title="<?php echo __('enter_taxi_manufacturer'); ?>" class="required" id="taxi_manufacturer" name="taxi_manufacturer" value="<?php echo isset($company_details[0]['taxi_manufacturer']) && !array_key_exists('taxi_manufacturer', $postvalue) ? trim($company_details[0]['taxi_manufacturer']) : $postvalue['taxi_manufacturer']; ?>" />
<?php if (isset($errors) && array_key_exists('taxi_manufacturer', $errors)) {
    echo "<span class='error'>" . ucfirst($errors['taxi_manufacturer']) . "</span>";
} ?>
                            </div>
                        </td>   	
                    </tr>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('taxi_colour'); ?></label><span class="star">*</span></td>        
                        <td>
                            <div class="new_input_field">
                                <input type="text" title="<?php echo __('enter_taxi_colour'); ?>" class="required" id="taxi_colour" name="taxi_colour" value="<?php echo isset($company_details[0]['taxi_colour']) && !array_key_exists('taxi_colour', $postvalue) ? trim($company_details[0]['taxi_colour']) : $postvalue['taxi_colour']; ?>" />
<?php if (isset($errors) && array_key_exists('taxi_colour', $errors)) {
    echo "<span class='error'>" . ucfirst($errors['taxi_colour']) . "</span>";
} ?>
                            </div>
                        </td>   	
                    </tr>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('taxi_motor_expire_date'); ?></label><span class="star">*</span></td>        
                        <td>
                            <div class="new_input_field">
                                <input type="text" title="<?php echo __('enter_taxi_motor_expire_date'); ?>" class="required" readonly id="taxi_motor_expire_date" name="taxi_motor_expire_date" value="<?php echo isset($company_details[0]['taxi_motor_expire_date']) && !array_key_exists('taxi_motor_expire_date', $postvalue) ? trim($company_details[0]['taxi_motor_expire_date']) : $postvalue['taxi_motor_expire_date']; ?>" />
                                <?php if (isset($errors) && array_key_exists('taxi_motor_expire_date', $errors)) {
                                    echo "<span class='error'>" . ucfirst($errors['taxi_motor_expire_date']) . "</span>";
                                } ?>
                            </div>
                        </td>   	
                    </tr>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('taxi_insurance_number'); ?></label><span class="star">*</span></td>        
                        <td>
                            <div class="new_input_field">
                                <input type="text" title="<?php echo __('enter_taxi_insurance_number'); ?>" maxlength="30" class="required" id="taxi_insurance_number" name="taxi_insurance_number" value="<?php echo isset($company_details[0]['taxi_insurance_number']) && !array_key_exists('taxi_insurance_number', $postvalue) ? trim($company_details[0]['taxi_insurance_number']) : $postvalue['taxi_insurance_number']; ?>" />
<?php if (isset($errors) && array_key_exists('taxi_insurance_number', $errors)) {
    echo "<span class='error'>" . ucfirst($errors['taxi_insurance_number']) . "</span>";
} ?>
                            </div>
                        </td>   	
                    </tr>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('taxi_insurance_expire_date'); ?></label><span class="star">*</span></td>        
                        <td>
                            <div class="new_input_field">
                                <input type="text" title="<?php echo __('enter_taxi_insurance_expire_date'); ?>" class="required" readonly id="taxi_insurance_expire_date" name="taxi_insurance_expire_date" value="<?php echo isset($company_details[0]['taxi_insurance_expire_date_time']) && !array_key_exists('taxi_insurance_expire_date', $postvalue) ? trim($company_details[0]['taxi_insurance_expire_date_time']) : $postvalue['taxi_insurance_expire_date']; ?>" />
                    <?php if (isset($errors) && array_key_exists('taxi_insurance_expire_date', $errors)) {
                        echo "<span class='error'>" . ucfirst($errors['taxi_insurance_expire_date']) . "</span>";
                    } ?>
                            </div>
                        </td>   	
                    </tr>
<?php /*
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('taxi_pco_licence_number'); ?></label><span class="star">*</span></td>        
                        <td>
                            <div class="new_input_field">
                                <input type="text" title="<?php echo __('enter_taxi_pco_licence_number'); ?>" maxlength="30" class="required" id="taxi_pco_licence_number" name="taxi_pco_licence_number" value="<?php echo isset($company_details[0]['taxi_pco_licence_number']) && !array_key_exists('taxi_pco_licence_number', $postvalue) ? trim($company_details[0]['taxi_pco_licence_number']) : $postvalue['taxi_pco_licence_number']; ?>" />
<?php if (isset($errors) && array_key_exists('taxi_pco_licence_number', $errors)) {
    echo "<span class='error'>" . ucfirst($errors['taxi_pco_licence_number']) . "</span>";
} ?>
                            </div>
                        </td>   	
                    </tr>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('taxi_pco_licence_expire_date'); ?></label><span class="star">*</span></td>        
                        <td>
                            <div class="new_input_field">
                                <input type="text" title="<?php echo __('enter_taxi_pco_licence_expire_date'); ?>" class="required" readonly id="taxi_pco_licence_expire_date" name="taxi_pco_licence_expire_date" value="<?php echo isset($company_details[0]['taxi_pco_licence_expire_date']) && !array_key_exists('taxi_pco_licence_expire_date', $postvalue) ? trim($company_details[0]['taxi_pco_licence_expire_date']) : $postvalue['taxi_pco_licence_expire_date']; ?>" />
<?php if (isset($errors) && array_key_exists('taxi_pco_licence_expire_date', $errors)) {
    echo "<span class='error'>" . ucfirst($errors['taxi_pco_licence_expire_date']) . "</span>";
} ?>
                            </div>
                        </td>   	
                    </tr>
*/ ?>

<?php /* <tr>
  <td valign="top" width="20%"><label><?php echo __('taxi_capacity').'('.__('total_passenger').')'; ?></label><span class="star">*</span></td>
  <td>
  <div class="new_input_field">
  <input type="text" title="<?php echo __('enter_taxi_capacity'); ?>" class="required digits" id="taxi_capacity" name="taxi_capacity" value="<?php echo isset($company_details[0]['taxi_capacity']) &&!array_key_exists('taxi_capacity',$postvalue)? trim($company_details[0]['taxi_capacity']):$postvalue['taxi_capacity']; ?>" minlength="1" maxlength="20" />
  <?php if(isset($errors) && array_key_exists('taxi_capacity',$errors)){ echo "<span class='error'>".ucfirst($errors['taxi_capacity'])."</span>";}?>
  </div>
  </td>
  </tr> */ ?>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('taxi_speed'); ?></label><span class="star"></span></td>        
                        <td>
                            <div class="new_input_field">
                                <input type="text" title="<?php echo __('enter_taxi_speed'); ?>" class="numbersdots" id="taxi_speed" name="taxi_speed" value="<?php echo isset($company_details[0]['taxi_speed']) && !array_key_exists('taxi_speed', $postvalue) ? trim($company_details[0]['taxi_speed']) : $postvalue['taxi_speed']; ?>" minlength="1" maxlength="20" />
                    <?php if (isset($errors) && array_key_exists('taxi_speed', $errors)) {
                        echo "<span class='error'>" . ucfirst($errors['taxi_speed']) . "</span>";
                    } ?>
                            </div>
                        </td>   	
                    </tr>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('taxi_min_speed'); ?></label><span class="star"></span></td>        
                        <td>			   
                            <div class="new_input_field">
                                <input type="text" title="<?php echo __('entertaxi_min_speed'); ?>" class="onlynumbers" name="taxi_min_speed" id="taxi_min_speed" value="<?php echo isset($company_details[0]['taxi_min_speed']) && !array_key_exists('taxi_min_speed', $postvalue) ? trim($company_details[0]['taxi_min_speed']) : $postvalue['taxi_min_speed']; ?>"  minlength="1" maxlength="7"  />

                    <?php if (isset($errors) && array_key_exists('taxi_min_speed', $errors)) {
                        echo "<span class='error'>" . ucfirst($errors['taxi_min_speed']) . "</span>";
                    } ?>
                            </div>
                        </td>   	
                    </tr> 

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('maximum_luggage'); ?></label><span class="star"></span></td>        
                        <td>
                            <div class="new_input_field">
                                <input type="text" title="<?php echo __('enter_minimum_luggage'); ?>" class="onlynumbers" name="minimum_luggage" id="minimum_luggage" value="<?php echo isset($company_details[0]['max_luggage']) && !array_key_exists('max_luggage', $postvalue) ? trim($company_details[0]['max_luggage']) : $postvalue['minimum_luggage']; ?>" maxlength="4"  />
<?php if (isset($errors) && array_key_exists('minimum_luggage', $errors)) {
    echo "<span class='error'>" . ucfirst($errors['minimum_luggage']) . "</span>";
} ?>                            
                            </div>
                        </td>   	
                    </tr> 

<!--<tr>
<td valign="top" width="20%"><label><?php //echo str_replace('%currency%',CURRENCY,__('taxi_fare_km')); ?></label><span class="star">*</span></td>        
<td>
<div class="new_input_field">
      <input type="text" title="<?php //echo __('enter_taxi_fare_km'); ?>" class="required digits" id="taxi_fare_km" name="taxi_fare_km" value="<?php //echo isset($company_details[0]['taxi_fare_km']) &&!array_key_exists('taxi_fare_km',$postvalue)? trim($company_details[0]['taxi_fare_km']):$postvalue['taxi_fare_km']; ?>"  minlength="1" maxlength="20" />
                                <?php //if(isset($errors) && array_key_exists('taxi_fare_km',$errors)){ echo "<span class='error'>".ucfirst($errors['taxi_fare_km'])."</span>";}?>
</div>
</td>   	
</tr>-->
                    <input type="hidden" title="<?php echo __('enter_taxi_fare_km'); ?>" id="taxi_fare_km" name="taxi_fare_km" value="<?php echo isset($company_details[0]['taxi_fare_km']) && !array_key_exists('taxi_fare_km', $postvalue) ? trim($company_details[0]['taxi_fare_km']) : $postvalue['taxi_fare_km']; ?>"  minlength="1" maxlength="20" />
                                <?php /* 				
                                  <tr>
                                  <td valign="top" width="20%"><label><?php echo __('taxi_image'); ?></label><span class="star">*</span></td>
                                  <td>
                                  <div class="new_input_field">
                                  <input type="file" title="<?php echo __('upload_taxi_image'); ?>" id="taxi_image" name="taxi_image" value="<?php if(isset($postvalue) && array_key_exists('taxi_image',$postvalue)){ echo $postvalue['taxi_image']; }?>" maxlength="50" />
                                  <?php if(isset($errors) && array_key_exists('taxi_image',$errors)){ echo "<span class='error'>".ucfirst($errors['taxi_image'])."</span>";}?>
                                  </div>
                                  </td>
                                  </tr>
                                 */ ?>	

                                <?php
                                if ($field_count > 0) {
                                    for ($i = 0; $i < $field_count; $i++) {
                                        $field_name = $additional_fields[$i]['field_name'];
                                        ?>
                            <tr>
                                <td valign="top" width="20%"><label><?php echo $additional_fields[$i]['field_labelname']; ?></label><span class="star">*</span></td>        
                                <td>
                                    <div class="new_input_field">
                                        <?php
                                        if ($additional_fields[$i]['field_type'] == 'Textbox') {
                                            $field_chkvalue = isset($company_details[0][$field_name]) && !array_key_exists($field_name, $postvalue) ? trim($company_details[0][$field_name]) : $postvalue[$field_name];
                                            ?>	                        
                                            <input type="text" title="<?php echo __('enter_the') . $field_name; ?>" class="required"  id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" value="<?php echo $field_chkvalue; ?>" maxlength="20" />
                                        <?php
                                        } else if ($additional_fields[$i]['field_type'] == 'Checkbox') {

                                            $field_chkvalue = '';
                                            $field_chkvalue = isset($company_details[0][$field_name]) && !array_key_exists($field_name, $postvalue) ? trim($company_details[0][$field_name]) : $postvalue[$field_name];

                                            $field_value = explode(',', $additional_fields[$i]['field_value']);

                                            foreach ($field_value as $key => $value) {
                                                ?>
                                                <input type="checkbox" title="<?php echo __('enter_the'); ?>"  name="<?php echo $field_name; ?>" value="<?php echo $value; ?>" <?php if ($field_chkvalue == $value) {
                                                echo 'checked';
                                            } ?> /><?php echo $value; ?>	                 	

                                                        <?php }
                                                        ?>

        <?php
        } else if ($additional_fields[$i]['field_type'] == 'Radio') {
            $field_radvalue = '';
            $field_radvalue = isset($company_details[0][$field_name]) && !array_key_exists($field_name, $postvalue) ? trim($company_details[0][$field_name]) : $postvalue[$field_name];

            $field_value = explode(',', $additional_fields[$i]['field_value']);

            foreach ($field_value as $key => $value) {
                ?>
                                                <input type="radio" title="<?php echo __('select_the') . $field_name; ?>" class="required" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" value="<?php echo $value; ?>" <?php if ($field_radvalue == $value) {
                                    echo 'checked';
                                } ?> /><?php echo $value; ?>	                 	

                                <?php }
                                ?>

                            <?php
                            } else if ($additional_fields[$i]['field_type'] == 'Select') {
                                $field_selvalue = '';

                                $field_selvalue = isset($company_details[0][$field_name]) && !array_key_exists($field_name, $postvalue) ? trim($company_details[0][$field_name]) : $postvalue[$field_name];

                                $field_value = explode(',', $additional_fields[$i]['field_value']);
                                ?>
                                            <div class="formRight">
                                                <div class="selector" id="uniform-user_type">
                                                    <span><?php echo __('select_label'); ?></span>
                                                    <select name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" class="required" title="<?php echo __('select_the') . $field_name; ?>">
                                                        <option value="">--Select--</option>
                                                    <?php
                                                    foreach ($field_value as $key => $value) {
                                                        ?>
                                                            <option value="<?php echo $value; ?>" <?php if ($field_selvalue == $value) {
                                                            echo 'selected=selected';
                                                        } ?> ><?php echo ucfirst($value); ?></option>
                                        <?php }
                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                    <?php
                                }
                                ?>
                                        <label for="<?php echo $field_name; ?>" generated="true" style="display:none" class="errorvalid"><?php echo __('select_the') . $field_name; ?></label>	
        <?php if (isset($errors) && array_key_exists($field_name, $errors)) {
            echo "<span class='error'>" . ucfirst($errors[$field_name]) . "</span>";
        } ?>
                                    </div>
                                </td>
                            </tr>
                                                <?php
                                                }
                                            }
                                            ?>    

<?php if ($_SESSION['user_type'] != 'M') {
    ?>
                        <tr>
                                <?php $field_type = '';
                                $field_type = isset($company_details[0]['taxi_country']) && !array_key_exists('country', $postvalue) ? trim($company_details[0]['taxi_country']) : $postvalue['country']; ?>
                            <td valign="top" width="20%"><label><?php echo __('country_label'); ?></label><span class="star">*</span></td>        
                            <td>
                                <div class="formRight">
                                    <div class="selector" id="uniform-user_type">
                                        <span><?php echo __('select_label'); ?></span>
                                        <select  title="<?php echo __('select_the_country'); ?>" class="required" <?php if ($_SESSION['user_type'] == 'C' || $_SESSION['user_type'] == 'M') { ?> name="countrys" disabled <?php } else { ?> name="country" id="country" <?php } ?>>
                                            <option value="">--Select--</option>
    <?php foreach ($country_details as $country_list) { ?>
                                                <option value="<?php echo $country_list['country_id']; ?>" <?php if ($field_type == $country_list['country_id']) {
            echo 'selected=selected';
        } ?>><?php echo ucfirst($country_list['country_name']); ?></option>
                                                <?php } ?>
                                        </select>
                                                <?php if ($_SESSION['user_type'] == 'C' || $_SESSION['user_type'] == 'M') { ?> <input type="hidden" name="country" id="country" value="<?php echo $field_type; ?>"> <?php } ?>
                                    </div>
                                </div>
                                <label for="country" generated="true" style="display:none" class="errorvalid"><?php echo __('select_the_country'); ?></label>	
    <?php if (isset($errors) && array_key_exists('country', $errors)) {
        echo "<span class='error'>" . ucfirst($errors['country']) . "</span>";
    } ?>

                            </td>   	
                        </tr>

                        <tr>
                        <?php $field_type = '';
                        $field_type = isset($company_details[0]['taxi_state']) && !array_key_exists('state', $postvalue) ? trim($company_details[0]['taxi_state']) : $postvalue['state']; ?>
                            <td valign="top" width="20%"><label><?php echo __('state_label'); ?></label><span class="star">*</span></td>
                            <td>
                                <div class="formRight">
                                    <div class="selector" id="uniform-user_type">
                                        <span><?php echo __('select_label'); ?></span>
                                        <div id="state_list">
                                            <select name="state" id="state" onchange="change_city_drop();" class="required" title="<?php echo __('select_the_state'); ?>">
                                                <option value="">--Select--</option>
    <?php foreach ($state_details as $state_list) { ?>
                                                    <option value="<?php echo $state_list['state_id']; ?>" <?php if ($field_type == $state_list['state_id']) {
            echo 'selected=selected';
        } ?> ><?php echo ucfirst($state_list["state_name"]); ?></option>
    <?php } ?>
                                            </select>
                                        </div>	
                                    </div></div>
                                <label for="state" generated="true" style="display:none" class="errorvalid"><?php echo __('select_the_state'); ?></label>	
                                    <?php if (isset($errors) && array_key_exists('state', $errors)) {
                                        echo "<span class='error'>" . ucfirst($errors['state']) . "</span>";
                                    } ?>
                            </td>      
                        </tr>

                        <tr>
    <?php $field_type = '';
    $field_type = isset($company_details[0]['taxi_city']) && !array_key_exists('city', $postvalue) ? trim($company_details[0]['taxi_city']) : $postvalue['city']; ?>
                            <td valign="top" width="20%"><label><?php echo __('city_label'); ?></label><span class="star">*</span></td>        
                            <td>
                                <div class="formRight">
                                    <div class="selector" id="uniform-user_type">
                                        <span><?php echo __('select_label'); ?></span>
                                        <div id="city_list">
                                            <select name="city" id="city" --onchange="change_company();" class="required" title="<?php echo __('select_the_city'); ?>">
                                                <option value="">--Select--</option>
                            <?php foreach ($city_details as $city_list) { ?>
                                                    <option value="<?php echo $city_list['city_id']; ?>" <?php if ($field_type == $city_list['city_id']) {
                            echo 'selected=selected';
                        } ?> ><?php echo ucfirst($city_list["city_name"]); ?></option>
                            <?php } ?>
                                            </select>
                                        </div>	
                                    </div>
                                </div>
                                <label for="city" generated="true" style="display:none" class="errorvalid"><?php echo __('select_the_city'); ?></label>	
    <?php if (isset($errors) && array_key_exists('city', $errors)) {
        echo "<span class='error'>" . ucfirst($errors['city']) . "</span>";
    } ?>
                            </td>   	
                        </tr>
<?php } else {
    ?>
                        <input type="hidden" name="country" id="country" value="<?php echo $_SESSION['country_id']; ?>">
                        <input type="hidden" name="state" id="state" value="<?php echo $_SESSION['state_id']; ?>">
                        <input type="hidden" name="city" id="city" value="<?php echo $_SESSION['city_id']; ?>">

                        <?php }
                        ?>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('taxi_image_label'); ?> </label><span class="star">*</span></td>   
                        <td width="30%"> 
                            <div class="new_input_field">
                                <input type="file" class="imageonly" name="taxi_image" id="taxi_image" title="<?php echo __('select_taxi_image'); ?>" value="<?php echo isset($company_details) && !array_key_exists('taxi_image', $postvalue) ? trim($company_details[0]['taxi_image']) : $postvalue['taxi_image']; ?>">

                            </div>
                            <div class="site_logo" >
                                <input type="hidden" name="taxi_old_img" id="taxi_old_img" value="<?php echo $company_details[0]['taxi_image']; ?>" >
                        <?php if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/public/' . UPLOADS . '/taxi_image/' . $company_details[0]['taxi_image']) && !empty($company_details[0]['taxi_image'])) { ?>
                                    <img width="75" height="75" src="<?php echo URL_BASE . TAXI_IMG_IMGPATH . $company_details[0]['taxi_image']; ?>"/>
                        <?php } else { ?>
                                    <img width="75" height="75"  src="<?php echo URL_BASE; ?>public/images/no_image.png"/>
                        <?php } ?>
                            </div>
<?php if (isset($errors) && array_key_exists('taxi_image', $errors)) {
    echo "<span class='error'>" . ucfirst($errors['taxi_image']) . "</span>";
} ?>
                        </td>
                        <td>	

                        </td>
                    </tr>
                    <table id="change_multiimage"  >
<?php
$taxi_id = $company_details[0]['taxi_id'];
$j = 0;

$count = $company_details[0]['taxi_sliderimage'];
$serialize_count = unserialize($company_details[0]['taxi_serializeimage']);

if (is_array($serialize_count)) {
    foreach ($serialize_count as $value) {
        if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/public/' . UPLOADS . '/taxi_image/' . $taxi_id . '_' . $value . '.png')) {
            ?>
                                    <tr>
                                        <td width="20%"></td>

                                        <td valign="top" width="20%">  

                                            <input type="file" class="text imageonly" name="updateimage[<?php echo $value; ?>]" id="cpicture<?php echo $value; ?>" value="" title="<?php echo __('select_taxi_image'); ?>"><br>
                                            <span id="error<?php echo $value; ?>" class="err_count" style="display:none;color:red;font-size:11px;">*Only jpeg, jpg or png images</span>
                                        </td>
                                        <td valign="top">

                                            <img style="margin-left:10px;" width="75" height="75" src="<?php echo URL_BASE . 'public/' . UPLOADS . '/taxi_image/' . $taxi_id . '_' . $value . '.png'; ?>"  width="300" alt="Slider Image"/>
                                            <a href="javascript:;" onclick="remove_image('<?php echo $file_path . $taxi_id . '_' . $value . '.png'; ?>','<?php echo $value; ?>')" class="ml10" title="Delete">Delete</a>

                                        </td>
                                    </tr>
            <?php
        } else {
            ?>
                                    <tr style="display:none;">
                                        <td width="20%"></td>
                                        <td valign="top" width="20%"><br>
                                            <span id="error<?php echo $value; ?>" class="err_count" style="display:none;color:red;font-size:11px;">*Only jpeg, jpg or png images</span>
                                        </td>
                                    </tr>			

        <?php
        }
    }
}
?>	
                    </table> 
                    <table border="0" cellpadding="0" cellspacing="0"  class="form" id="sub_add" width="100%">
                        <tr><td valign="top" width="20%"></td><td>&nbsp;&nbsp;&nbsp;<a id="add_moreimage" href="#">Add More Image</a></td></tr>   
                    </table>

                    <table border="0" cellpadding="5" cellspacing="0"  class="form" width="100%">	
                        <tr>
                            <td width="20%" class="empt_cel"> &nbsp;</td>
                            <td colspan="" class="star">*<?php echo __('required_label'); ?></td>
                        </tr>
                        <tr>
                            <td width="20%">&nbsp;</td>
                            <td colspan="2">          
                                <div class="new_button">
                                    <input type="hidden" name="taxi_id" id="taxi_id" value="<?php echo $taxi_id; ?>">  
                                    <input type="hidden" name="add_count" id="add_count" value="<?php echo $count; ?>">  
                                    <input type="button" value="<?php echo __('button_back'); ?>" onclick="window.history.go(-1)" /></div>
                                <div class="new_button">  <input type="submit" value="<?php echo __('submit'); ?>" name="submit_edittaxi" id="submit_edittaxi" title="<?php echo __('submit'); ?>" /></div>
                                <div class="new_button">   <input type="reset" onclick="change_state('<?php echo isset($company_details[0]['taxi_country']) ? $company_details[0]['taxi_country'] : ''; ?>','<?php echo isset($company_details[0]['taxi_state']) ? $company_details[0]['taxi_state'] : ''; ?>');change_city_drop('<?php echo isset($company_details[0]['taxi_country']) ? $company_details[0]['taxi_country'] : ''; ?>','<?php echo isset($company_details[0]['taxi_state']) ? $company_details[0]['taxi_state'] : ''; ?>','<?php echo isset($company_details[0]['taxi_city']) ? $company_details[0]['taxi_city'] : ''; ?>')" value="<?php echo __('button_reset'); ?>" title="<?php echo __('button_reset'); ?>" /></div>
                            </td>
                        </tr>
                    </table>


            </form>
        </div>
        <div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt"></div></div>
    </div>
</div>
<?php ?>
<script type="text/javascript">
    $(document).ready(function(){
	
<?php if (isset($company_details[0]['taxi_company'])) { ?>	
                    getcountry('<?php echo $company_details[0]['taxi_company']; ?>','<?php echo $company_details[0]['taxi_state']; ?>','<?php echo $company_details[0]['taxi_city']; ?>');
<?php } ?>

        var field_val = $("#taxi_no").val();
        $("#taxi_no").focus().val("").val(field_val);
        change_type();	
        change_state('','');	
        change_city_drop('','','');
        var modelId = $("#taxi_model").val();
        getTaxiSpeed(modelId);
        //date time picker
        $("#taxi_insurance_expire_date").datetimepicker( {
            showTimepicker:true,
            showSecond: true,
            timeFormat: 'hh:mm:ss',
            dateFormat: 'yy-mm-dd',
            stepHour: 1,
            stepMinute: 1,
            minDateTime : new Date(),
            stepSecond: 1
        } );

        $('#taxi_motor_expire_date, #taxi_pco_licence_expire_date').datepicker({dateFormat: 'yy-mm-dd',changeMonth: true, changeYear: true, yearRange: new Date().getFullYear()+':+100',minDate: 0,maxDate: new Date(2100, 1,18) });

    });

    jQuery(document).ready(function () {
        jQuery('#edittaxi_form').submit(function(){

            //var validator=jQuery("#addtaxi_form").validate({});
        });

        $.validator.addMethod( "imageonly", function(value,element){
            var pathLength = value.length; var lastDot = value.lastIndexOf( "."); var fileType = value.substring(lastDot,pathLength).toLowerCase(); return this.optional(element) || fileType.match(/(?:.jpg|.jpeg|.png)$/) }, "Please upload image file only");


        $("#submit_edittaxi").click(function(){
            var flag = true;
            var validator=jQuery("#edittaxi_form").valid({});

            $("input:file").each(function(){ 
                var fld=$(this).val();
                var get_id= $(this).attr('id').replace('size','');
                if(fld!=''){
                    if(!/(\.png|\.jpg|\.jpeg)$/i.test(fld)) {
                        $("#error"+get_id).show();
                       
                        flag=false;
                    }else{
                        $("#error"+get_id).hide();
		 
                        flag=true;
                    } 
                }
            });

            return flag;   
        });
    });

    function remove_image(sPath,imageid)
    {
        var taxi_id = $('#taxi_id').val();
        $.ajax({
            url:"<?php echo URL_BASE; ?>add/delete_image",
            type:"get",
            data:"sPath="+sPath+"&taxi_id="+taxi_id+"&image_id="+imageid,
            success:function(data){
                $('#change_multiimage').html();
                $('#change_multiimage').html(data);
            },
            error:function(data)
            {
                //alert(cid);
            }
        });

    }

    $("#country").change(function() {

        var countryid= $("#country").val();
        var stateid= $("#state").val();

        $.ajax({
            url:"<?php echo URL_BASE; ?>add/getlist_state",
            type:"get",
            data:"country_id="+countryid+"&state_id="+stateid,
            success:function(data){

                $('#state_list').html();
                $('#state_list').html(data);
                change_city_drop();
            },
            error:function(data)
            {
                //alert(cid);
            }
        });	
    });

    function change_city_drop(country_id,state_id,city_id){
		
        var countryid= $("#country").val();
        var stateid= $("#state").val();
        var cityid= $("#city").val();
        if(country_id != '' && state_id != '' && city_id != '') {
            countryid = country_id;
            stateid = state_id;
            cityid = city_id;
        }
        $.ajax({
            url:"<?php echo URL_BASE; ?>add/getassigntaxilist",
            type:"get",
            data:"country_id="+countryid+"&state_id="+stateid+"&city_id="+cityid,
            success:function(data){

                $('#city_list').html();
                $('#city_list').html(data);
                //change_driverinfo();
                //change_taxiinfo();
            },
            error:function(data)
            {
                //alert(cid);
            }
        });	
    } 

    function change_state(country_id,state_id)
    {

        var countryid= $("#country").val();
        var stateid= $("#state").val();
        if(country_id != '' && state_id != '') {
            countryid = country_id;
            stateid= state_id;
        }

        $.ajax({
            url:"<?php echo URL_BASE; ?>add/getlist_state",
            type:"get",
            data:"country_id="+countryid+"&state_id="+stateid,
            success:function(data){

                $('#state_list').html();
                $('#state_list').html(data);
            },
            error:function(data)
            {
                //alert(cid);
            }
        });	
    
    }
    

    /*function change_city()
{

                var stateid= $("#state").val();
                var countryid= $("#country").val();
                var cityid= $("#city").val();
		
                  $.ajax({
                        url:"<?php echo URL_BASE; ?>add/getcitylist",
                        type:"get",
                        data:"country_id="+countryid+"&state_id="+stateid+"&city_id="+cityid,
                        success:function(data){

                        $('#city_list').html();
                        $('#city_list').html(data);
                        },
                        error:function(data)
                        {
                                //alert(cid);
                        }
                });	
    
} */

    function change_company()
    {

        var countryid= $("#country").val();
        var stateid= $("#state").val();
        var city_id= $("#city").val();
        var company_name = $("#company_name").val();

        $.ajax({
            url:"<?php echo URL_BASE; ?>add/getcompanylist",
            type:"get",
            data:"country_id="+countryid+"&state_id="+stateid+"&city_id="+city_id+"&company_name="+company_name,
            success:function(data){

                $('#taxicompany_list').html();
                $('#taxicompany_list').html(data);
            },
            error:function(data)
            {
                //alert(cid);
            }	
        });
    }

    function change_type()
    {

        var motorid= $("#taxi_type").val();
        var modelid= $("#taxi_model").val();
      		
        if(modelid ==null)
        {
            modelid="";
        }

        $.ajax({
            url:"<?php echo URL_BASE; ?>add/getmodellist",
            type:"get",
            data:"motor_id="+motorid+"&model_id="+modelid,
            success:function(data){
                $('#model_list').html();
                $('#model_list').html(data);
            },
            error:function(data)
            {
                //alert(cid);
            }
        });	
    
    }

    $("#taxi_type").change(function() {

        var motorid= $("#taxi_type").val();
        var modelid= $("#model_id").val();

        $.ajax({
            url:"<?php echo URL_BASE; ?>add/getmodellist",
            type:"get",
            data:"motor_id="+motorid+"&model_id="+modelid,
            success:function(data){
                $('#model_list').html();
                $('#model_list').html(data);
            },
            error:function(data)
            {
                //alert(cid);
            }
        });	
    });
    
    $(function(){ 
        $('#add_moreimage').click(function(){  
            var newRow = $("#sub_add tr").length+1; 
            var totimgRow = newRow + ($("#change_multiimage tr").length);
            if(totimgRow <= 11) {
                $("#sub_add").append('<tr id="row_'+newRow+'"><td width="20%"></td><td width="20%"><input type="file" style="margin-bottom:5px;" name="size[]" id="size'+newRow+'"  class="required" title="Select the image"><br><span id="error'+newRow+'" style="display:none;color:red;font-size:11px;">*Only jpeg, jpg or png images</span><td><a href="javascript:;" onClick="return removetr_contact('+newRow+');">Delete</a></td></tr>'); 
            }    
            return false;	
        });
   
    
    
    });  

    function removetr_contact(rowid) {
        var r1 = "row_"+rowid;
        $("#sub_add tr").each(function () {    
            if(r1==$(this).attr('id')) {
                $(this).remove();
            }   
        });
        return false;
    }
    function getid(rowid) {
        var r2 = "row_"+rowid;
    }   
  
    /** Function to get taxi speed and minimum speed details **/
    function getTaxiSpeed(modelId)
    {
<?php 
/*
        $.ajax({
            url:"<?php echo URL_BASE; ?>add/getTaxiSpeed",
            type:"post",
            data:"modelId="+modelId,
            success:function(data){
                var res = $.parseJSON(data);
                $.each(res, function(i, resval) {
                    $('#taxi_speed').val(resval.taxi_speed);
                    $('#taxi_speed').attr('readonly','readonly');
                    $('#taxi_min_speed').val(resval.taxi_min_speed);
                    $('#taxi_min_speed').attr('readonly','readonly');
                });
			
            },
            error:function(data)
            {
                //alert(cid);
            }
        });	
*/ ?>
    }

    function getcountry(company_id,stateid,cityid)
    {
        $.ajax({
            url:"<?php echo URL_BASE; ?>add/getcountry",
            type:"get",
            data:"company_id="+company_id,
            success:function(data){
                var res = data.split("~");
                $('#country').html(res[0]);
                if(stateid != '' && cityid != '') {
                    change_country(stateid,cityid);
                } else {
                    change_country(res[1],res[2]);
                }
            },
            error:function(data)
            {
                //alert(cid);
            }
        });	
    }

    function change_country(state_id,city_id)
    {
        var countryid= $("#country").val();

        $.ajax({
            url:"<?php echo URL_BASE; ?>add/getlist_state",
            type:"get",
            data:"country_id="+countryid+"&state_id="+state_id,
            success:function(data){
                $('#state_list').html(data);
                change_city(countryid,state_id,city_id);
            },
            error:function(data)
            {
                //alert(cid);
            }
        });
    }

    function change_city(country_id,state_id,city_id)
    {

        var countryid= $("#country").val();
        var stateid= $("#state").val();
        var cityid= $("#city").val();
        if(country_id != '' && state_id != '' && city_id != '') {
            countryid = country_id;
            stateid = state_id;
            cityid = city_id;
        }
        $.ajax({
            url:"<?php echo URL_BASE; ?>add/getassigntaxilist",
            type:"get",
            data:"country_id="+countryid+"&state_id="+stateid+"&city_id="+cityid,
            success:function(data){
                $('#city_list').html();
                $('#city_list').html(data);
            },
            error:function(data)
            {
                //alert(cid);
            }
        });	
    
    }
   
</script>
