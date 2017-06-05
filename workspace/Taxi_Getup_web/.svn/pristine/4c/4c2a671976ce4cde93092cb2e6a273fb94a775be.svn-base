<?php
defined('SYSPATH') OR die("No direct access allowed.");
echo html::script('public/ckeditor/ckeditor.js'); 
$randy = md5( uniqid (rand(), 1) );
//print_r($site_settings);
?>
<?php /* <script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script> */ ?>
<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle"> 
            <form method="POST" enctype="multipart/form-data" class="form" action="" name="settings" id="settings" >
				<?php //print_r($site_settings);?>
                <table class="0" cellpadding="5" cellspacing="0" width="100%">
				<?php /*	<tr>
                        <td valign="top" width="20%"><label><?php echo __('company_url'); ?></label></td>   
                        <td>http://<?php echo $site_settings[0]['company_domain'].'.'.DOMAIN_NAME; ?></td>                                                
                    </tr>
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('company_domain'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field" style="width:400px;"><input type="text" name="company_domain" id="company_domain" title="<?php echo __('enter_company_domain'); ?>" maxlength="250" class="required" value="<?php echo isset($site_settings[0]['company_domain'])?trim($site_settings[0]['company_domain']):''; ?>" readonly="readonly" />
                        <span><?php echo ".".DOMAIN_NAME; ?></span>
                        </div>
                    </tr>
                    <input type="hidden" name="company_api_key" id="company_api_key" value="" />
                    <?php /*<input type="hidden" name="customer_app_url" id="customer_app_url" value="" />
                    <input type="hidden" name="driver_app_url" id="driver_app_url" value="" /> */ ?>
<?php /*
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('company_api_key'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field" style="width:400px;"><input type="text" name="company_api_key" id="company_api_key" title="<?php echo __('enter_api_key'); ?>" maxlength="250" class="required" value="<?php echo isset($site_settings[0]['company_api_key'])?trim($site_settings[0]['company_api_key']):''; ?>" readonly="readonly" />
                        </div>
                    </tr>
                                        <tr>
                        <td valign="top" width="20%"><label><?php echo __('time_zone'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field" style="width:400px;"><?php echo isset($site_settings[0]['time_zone'])?trim($site_settings[0]['time_zone']):''; ?>
                        </div>
                    </tr> */ ?>
                    <!--Package Details start-->
				  <!--  <tr>
					<td><?php //echo ucfirst(__('current_package_detail')); ?></td>
					<td></td>	          
				   </tr>   -->
				
				   <?php  /* if(count($package_details) > 0) { ?>				

					<tr>
					<td valign="top" width="20%"><label><?php echo __('package_name'); ?></label></td>        
					<td>
						   <div class="new_input_field">
						   <?php if(isset($package_details[0]['package_name'])) { echo $package_details[0]['package_name']; } ?>				
						   </div>
					</td>   	
					</tr>

					<tr>
					<td valign="top" width="20%"><label><?php echo __('package_type'); ?></label></td>        
					<td>
						   <div class="new_input_field">
							<?php if($package_details[0]['package_type'] == 'T' ) { echo __('transaction_based_commission'); } else if($package_details[0]['package_type'] == 'P' ) { echo __('package_based_commission'); } else if($package_details[0]['package_type'] == 'N' ) { echo __('package_based_no_commission'); } ?>

						   </div>
					</td>   	
					</tr>

				  <?php }	 
					else
					{ ?>
					<tr>
				 
					<td>
						   <div class="new_input_field">
						   <?php echo __('no_package_available');  ?>				
						   </div>
					</td>   	
					</tr>
				<?php } */?>	

				  <!--Package Details end-->
                 <?php /*   <tr>
				<?php $customer_app=explode('|',$site_settings[0]['customer_app_url']);
				      $driver_app=explode('|',$site_settings[0]['driver_app_url']);
				      if(!isset($customer_app[0])){$customer_app[0]='';}
				      if(!isset($customer_app[1])){$customer_app[1]='';}
				      if(!isset($driver_app[0])){$driver_app[0]='';} 
				      if(!isset($driver_app[1])){$driver_app[1]='';} ?>

                        <td valign="top" width="20%"><label><?php echo __('customer_app_android_url'); //$company_type['0']['upgrade_packageid']=4; ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field" style="width:400px;"><input type="text" class="required" name="customer_app_android" id="customer_app_url" title="<?php echo __('customer_app_android_url'); ?>" value="<?php if($company_type['0']['upgrade_packageid'] == 5){ echo 'https://play.google.com/store/apps/details?id=com.taximobility';}else{ echo isset($postvalue['customer_app_android'])?(Arr::get($postvalue,'customer_app_android')):trim($customer_app[0]); } ?>" <?php if($company_type['0']['upgrade_packageid'] == 5){ echo "readonly"; } ?> /></div>
                            <span class="error"><?php echo isset($errors['customer_app_android']) ? $errors['customer_app_android'] : ''; ?></span>
                        </td>

                    </tr>  
                                       
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('customer_app_ios_url'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field" style="width:400px;"><input type="text" class="required" name="customer_app_ios" id="driver_app_url" title="<?php echo __('customer_app_ios_url'); ?>" value="<?php if($company_type['0']['upgrade_packageid'] == 5){ echo 'https://itunes.apple.com/us/app/taxi-mobility/id882697557?mt=8';}else{   echo isset($postvalue['customer_app_ios'])?(Arr::get($postvalue,'customer_app_ios')):trim($customer_app[1]); } ?>" <?php if($company_type['0']['upgrade_packageid'] == 5){ echo "readonly"; } ?> /></div>
                            <span class="error"><?php echo isset($errors['customer_app_ios']) ? $errors['customer_app_ios'] : ''; ?></span>
                        </td>

                    </tr> 
		      <tr>
                        <td valign="top" width="20%"><label><?php echo __('driver_app_android_url'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field" style="width:400px;"><input type="text" class="required" name="driver_app_android" id="driver_app_android_url" title="<?php echo __('driver_app_android_url'); ?>" value="<?php if($company_type['0']['upgrade_packageid'] == 5){ echo 'https://play.google.com/store/apps/details?id=com.taximobility.driver';}else{ echo isset($postvalue['driver_app_android'])?(Arr::get($postvalue,'driver_app_android')):trim($driver_app[0]); } ?>" <?php if($company_type['0']['upgrade_packageid'] == 5){ echo "readonly"; } ?> /></div>
                            <span class="error"><?php echo isset($errors['driver_app_android']) ? $errors['driver_app_android'] : ''; ?></span>
                        </td>

                    </tr>  
                    <?php /*<tr>
                        <td valign="top" width="20%"><label><?php echo __('driver_app_ios_url'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field" style="width:400px;"><input type="text" class="required" name="driver_app_ios" id="driver_app_ios_url" title="<?php echo __('driver_app_ios_url'); ?>" value="<?php if($company_type['0']['upgrade_packageid'] == 5){ echo 'https://itunes.apple.com/us/app/taximobility-driver/id911690305?ls=1&mt=8';}else{   echo isset($postvalue['driver_app_url'])?(Arr::get($postvalue,'driver_app_ios')):trim($driver_app[1]); } ?>" <?php if($company_type['0']['upgrade_packageid'] == 5){ echo "readonly"; } ?> /></div>
                            <span class="error"><?php echo isset($errors['driver_app_ios']) ? $errors['driver_app_ios'] : ''; ?></span>
                        </td>

                    </tr>             */?>             
                    
                    
                <?php /*    
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('app_name_label'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field" style="width:400px;"><input type="text" class="required" name="app_name" id="app_name" title="<?php echo __('enter_app_name'); ?>" maxlength="250" value="<?php echo isset($site_settings) &&!array_key_exists('app_name',$postvalue)? trim($site_settings[0]['company_app_name']):$postvalue['app_name']; ?>"></div>
                    <?php if(isset($errors) && array_key_exists('app_name',$errors)){ echo "<span class='error'>".$errors['app_name']."</span>";}?>
                    </tr>                    
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('app_description_label'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field" style="width:400px;"><textarea type="text" class="required" name="app_description" id="app_description" rows="7" cols="35" style="resize:none;" title="<?php echo __('enter_app_description'); ?>" value=""><?php 
                        echo isset($postvalue['app_description'])?(Arr::get($postvalue,'app_description')):trim($site_settings[0]['company_app_description']); ?></textarea></div>
                            <span class="error"><?php echo isset($errors['app_description']) ? $errors['app_description'] : ''; ?></span>
                        </td>

                    </tr> 
                    
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('comapany_tag_line'); ?></label><span class="star">*</span></td>   
                        <td>
				<div class="new_input_field" style="width:400px;">
					<input type="text" class="required" name="company_tagline" id="site_tagline" title="<?php echo __('enter_company_tag_line'); ?>" maxlength="50" value="<?php 
                        echo isset($postvalue['company_tagline'])?(Arr::get($postvalue,'company_tagline')):trim($site_settings[0]['company_tagline']); ?>">
				</div>
				 <?php echo "<span class='error'>".Arr::get($errors,'company_tagline')."</span>";?>
			</td>
                    </tr>                              


                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('contact_email_label'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field" style="width:400px;"><input type="text" class="required" name="contact_email" id="contact_email" title="<?php echo __('enter_contact_email'); ?>" maxlength="30" value="<?php 
                        echo isset($postvalue['contact_email'])?(Arr::get($postvalue,'contact_email')):trim($site_settings[0]['company_email_id']); ?>"></div>
                            <span class="error"><?php echo isset($errors['contact_email']) ? $errors['contact_email'] : ''; ?></span></td>
                    </tr>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('contact_phone_label'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field" style="width:400px;"><input type="text" class="required" name="phone_number" id="phone_number"  title="<?php echo __('enter_phone_number'); ?>" maxlength="30" value="<?php 
                        echo isset($postvalue['phone_number'])?(Arr::get($postvalue,'phone_number')):trim($site_settings[0]['company_phone_number']); ?>"></div>
                            <span class="error"><?php echo isset($errors['phone_number']) ? $errors['phone_number'] : ''; ?></span></td>
                    </tr>
                    <input type="hidden" name="notification_settings" maxlength="3" value="<?php 
                        echo $site_settings[0]['company_notification_settings']; ?>">
                  <?php /*   <tr>
                        <td valign="top" width="20%"><label><?php echo __('notification_settings_label'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field" style="width:400px;"><input type="text" class="required" name="notification_settings" id="notification_settings"  title="<?php echo __('enter_notification_settings'); ?>" maxlength="3" value="<?php 
                        echo isset($postvalue['notification_settings'])?(Arr::get($postvalue,'notification_settings')):trim($site_settings[0]['company_notification_settings']); ?>"></div>
                            <span class="error"><?php echo isset($errors['notification_settings']) ? $errors['notification_settings'] : ''; ?></span>
                            <span class="textclass fl clr"><?php echo __('notification_settings_upto'); ?></span>
                            </td>
                    </tr> */ ?>

                     <tr>
                        <td valign="top" width="20%"><label><?php echo __('tax'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field" style="width:400px;"><input type="text" class="required" name="company_tax" id="company_tax"  title="<?php echo __('enter_tax'); ?>" maxlength="5" value="<?php  echo isset($postvalue['company_tax'])?(Arr::get($postvalue,'company_tax')):trim($site_settings[0]['company_tax']); ?>"></div>
                            <span class="error"><?php echo isset($errors['company_tax']) ? $errors['company_tax'] : ''; ?></span>
                            <span class="textclass fl clr"><?php echo __('note_tax'); ?></span>
                            </td>
                    </tr>
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('default_unit'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field">
                        <?php $checked=isset($site_settings[0]['default_unit'])?$site_settings[0]['default_unit']:"1"; 
						//echo $checked;?>
                        <input type="radio" name="default_unit" id="default_unit" title="<?php echo __('enter_payment_method'); ?>"  value="0" <?php if($checked=='0'){ echo 'checked=checked';}?> ><?php echo 'Kilometer'; ?>
                        
                        <input type="radio" name="default_unit" id="default_unit" title="<?php echo __('enter_payment_method'); ?>"  value="1" <?php if($checked=='1'){ echo 'checked=checked';}?>><?php  echo 'Mile'; ?>
                        </div>
						<?php if(isset($errors) && array_key_exists('default_unit',$errors)){ echo "<span class='error'>".ucfirst($errors['default_unit'])."</span>";}?></td>
                    </tr>
					<tr>
                        <td valign="top" width="20%"><label><?php echo __('skip_credit_card'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field">
                        <?php $checked=isset($site_settings[0]['skip_credit_card'])?$site_settings[0]['skip_credit_card']:"1"; 
						//echo $checked;?>
                        <input type="radio" name="skip_credit_card" id="skip_credit_card" title="<?php echo __('select_skip_credit_card'); ?>"  value="1" <?php if($checked=='1'){ echo 'checked=checked';}?> ><?php echo 'Enable'; ?>
                        
                        <input type="radio" name="skip_credit_card" id="skip_credit_card" title="<?php echo __('select_skip_credit_card'); ?>"  value="0" <?php if($checked=='0'){ echo 'checked=checked';}?>><?php  echo 'Disable'; ?>
                        </div>
						<?php if(isset($errors) && array_key_exists('skip_credit_card',$errors)){ echo "<span class='error'>".ucfirst($errors['skip_credit_card'])."</span>";}?></td>
                    </tr>
                    <tr>
						<td valign="top" width="20%"><label><?php echo __('driver_commission'); ?></label><span class="star">*</span></td>   
						<td>
							<div class="new_input_field" style="width:400px;">
								<input type="text" name="driver_commission" id="driver_commission"  title="<?php echo __('driver_commission'); ?>" max="100" maxlength="5" value="<?php echo isset($site_settings) && (!array_key_exists('driver_commission', $errors)) ? $site_settings[0]['driver_commission'] : $validator['driver_commission']; ?>">
							</div>
							<span class="error"><?php echo isset($errors['driver_commission']) ? ucfirst($errors['driver_commission']) : ''; ?></span>
						</td>
					</tr>
					<tr>
                        <td valign="top" width="20%"><label><?php echo __('fare_calculation'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field">
				<div class="formRight">
					<div class="selector" id="uniform-user_type">
						<select name="fare_calculation" id="fare_calculation" title="<?php echo __('fare_calculation'); ?>" >
							<option value="">-- Select --</option>
							<option value="1" <?php if($site_settings[0]['fare_calculation_type'] == '1') { echo 'selected=selected'; } ?> ><?php echo __('distance');?></option>
							<option value="2" <?php if($site_settings[0]['fare_calculation_type'] == '2') { echo 'selected=selected'; } ?> ><?php echo __('time');?></option>
							<option value="3" <?php if($site_settings[0]['fare_calculation_type'] == '3') { echo 'selected=selected'; } ?> ><?php echo __('distance')." / ".__('time');?></option>
						<?php echo isset($site_settings) && (!array_key_exists('fare_calculation', $errors)) ? $site_settings[0]['fare_calculation_type'] : $validator['fare_calculation']; ?></select>
						</div>
					</div>
				</div>
						    <span class="error"><?php echo isset($errors['fare_calculation']) ? $errors['fare_calculation'] : ''; ?></span></td>
                    </tr>

                     <tr>
                        <td valign="top" width="20%"><label><?php echo __('cancellation_fare'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field">
				<div class="formRight">
					<div class="selector" id="uniform-user_type">
						<?php $cancel_chk=isset($validator['cancellation_fare'])?$validator['cancellation_fare']:$site_settings[0]['company_cancellation']; 
						//echo $cancel_chk;?>
						<select name="cancellation_fare" id="cancellation_fare" class="required" title="<?php echo __('cancellation_fare'); ?>">
							<option value=""><?php echo __('select_label'); ?></option>
							<option value="1" <?php if($cancel_chk == 1) { echo 'selected=selected'; } ?> ><?php echo __('yes');?></option>
							<option value="0" <?php if($cancel_chk == 0) { echo 'selected=selected'; } ?> ><?php echo __('no');?></option>
						</select>
						</div>
					</div>
				</div> 
						<label for="cancellation_fare" generated="true" class="errorvalid" style="display:none"></label>
						<span class="error"><?php echo isset($errors['cancellation_fare']) ? $errors['cancellation_fare'] : ''; ?></span></td>
                    </tr>
                    
				<tr>
					<td valign="top" width="20%"><label><?php echo __('selected_timezone'); ?></label><span class="star">*</span></td>   
					<td>
						<div class="new_input_field">
							<div class="formRight">
								<div class="selector">
									<?php $format = isset($validator['user_time_zone']) ? $validator['user_time_zone'] : $company_timezone; ?>
									<select name="user_time_zone" title="<?php echo __('time_zone'); ?>" >
										<?php
											$timezone = unserialize(SELECT_TIMEZONE);
											foreach($timezone as $key => $value) { ?>
												<option value="<?php echo $value; ?>" <?php if($company_timezone == $value) { echo 'selected=selected'; }else if($value == $site_settings[0]['user_time_zone']) { echo 'selected=selected'; } ?> ><?php echo ucfirst($value); ?></option>
										<?php } ?>
										<?php echo isset($site_settings) && (!array_key_exists('user_time_zone', $errors)) ? $company_timezone : $validator['user_time_zone']; ?>
									</select>
								</div>
							</div>
						</div>
						<span class="error"><?php echo isset($errors['date_time_format']) ? $errors['date_time_format'] : ''; ?></span>
					</td>
				</tr>
				<tr>
					<td valign="top" width="20%"><label><?php echo __('date_time_format'); ?></label><span class="star">*</span></td>   
					<td>
						<div class="new_input_field">
							<div class="formRight">
								<div class="selector">
									<?php $format = isset($validator['date_time_format'])?$validator['date_time_format']:$site_settings[0]['date_time_format']; ?>
									<select name="date_time_format" title="<?php echo __('date_time_format'); ?>" >
										<option value=""><?php echo __('select_label'); ?></option>
										<option value="Y-m-d H:i:s" <?php if($format == "Y-m-d H:i:s") { echo 'selected=selected'; } ?> ><?php echo "Y-m-d H:i:s"; ?></option>
										<option value="Y/m/d H:i:s" <?php if($format == "Y/m/d H:i:s") { echo 'selected=selected'; } ?> ><?php echo "Y/m/d H:i:s"; ?></option>
										<option value="d-m-Y H:i:s" <?php if($format == "d-m-Y H:i:s") { echo 'selected=selected'; } ?> ><?php echo "d-m-Y H:i:s"; ?></option>
										<option value="d/m/Y H:i:s" <?php if($format == "d/m/Y H:i:s") { echo 'selected=selected'; } ?> ><?php echo "d/m/Y H:i:s"; ?></option>
										<option value="D,dM-Y h:i:s A" <?php if($format == "D,dM-Y h:i:s A") { echo 'selected=selected'; } ?> ><?php echo "D,dM-Y h:i:s A"; ?></option> 
										<?php echo isset($site_settings) && (!array_key_exists('date_time_format', $errors)) ? $site_settings[0]['date_time_format'] : $validator['date_time_format']; ?>
									</select>
								</div>
							</div>
						</div>
						<span class="error"><?php echo isset($errors['date_time_format']) ? $errors['date_time_format'] : ''; ?></span>
					</td>
				</tr>
                    
                     <tr>
                        <td valign="top" width="20%"><label><?php echo __('site_copyrights_label'); ?></label><span class="star">*</span></td>   
                        <td>
				<div class="new_input_field" >
					<input type="text" class="required" name="company_copyrights" id="site_copyrights" title="<?php echo __('enter_site_copyrights'); ?>" maxlength="100" value="<?php echo isset($site_settings) &&!array_key_exists('company_copyrights',$postvalue)? trim($site_settings[0]['company_copyrights']):$postvalue['company_copyrights']; ?>">
				</div>
				<?php if(isset($errors) && array_key_exists('company_copyrights',$errors)){ echo "<span class='error'>".$errors['company_copyrights']."</span>";}?>
			</td>
                    </tr>
                    
                    
                <?php /*    <tr>
                        <td valign="top" width="20%"><label><?php echo __('meta_title_label'); ?></label></td>   
                        <td><div class="new_input_field" style="width:400px;">
                        <input type="text" name="meta_title" id="meta_title"  title="<?php echo __('enter_meta_title'); ?>" maxlength="64" value="<?php  echo isset($postvalue['meta_title'])?(Arr::get($postvalue,'meta_title')):trim($site_settings[0]['company_meta_title']); ?>">
                        </div>
                            <span class="error"><?php echo isset($errors['meta_title']) ? $errors['meta_title'] : ''; ?></span></td>
                    </tr>
                    
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('meta_key_label'); ?></label></td>   
                        <td><div class="new_input_field" style="width:400px;">
			<textarea name="meta_keyword" id="meta_keyword" rows="7" cols="35" class="required" title="<?php echo __('enter_meta_keywords'); ?>" style="resize:none;"><?php 
                        echo isset($postvalue['meta_keyword'])?(Arr::get($postvalue,'meta_keyword')):trim($site_settings[0]['company_meta_keyword']); ?></textarea>
			</div>
                            <span class="error"><?php echo isset($errors['meta_keyword']) ? $errors['meta_keyword'] : ''; ?></span></td>
                    </tr>
                    
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('meta_desc_label'); ?></label></td>   
                        <td><div class="new_input_field" style="width:400px;">
                        <textarea name="meta_description" id="meta_description" rows="7" cols="35" class="required" title="<?php echo __('enter_meta_description'); ?>" style="resize:none;"><?php 
                        echo isset($postvalue['meta_description'])?(Arr::get($postvalue,'meta_description')):trim($site_settings[0]['company_meta_description']); ?></textarea>
			</div>
                            <span class="error"><?php echo isset($errors['meta_description']) ? $errors['meta_description'] : ''; ?></span></td>
                    </tr>

                     <tr>
                        <td valign="top" width="20%"><label><?php echo __('sms_enable'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field">
				<div class="formRight">
					<div class="selector" id="uniform-user_type">
						<?php $sms_chk=isset($validator['sms_enable'])?$validator['sms_enable']:$site_settings[0]['company_sms_enable']; ?>
						<select name="sms_enable" id="sms_enable" class="required" title="<?php echo __('sms_enable'); ?>" >
							<option value="">-- Select --</option>
							<option value="1" <?php if($sms_chk == '1') { echo 'selected=selected'; } ?> ><?php echo __('yes');?></option>
							<option value="0" <?php if($sms_chk == '0') { echo 'selected=selected'; } ?> ><?php echo __('no');?></option>
						</select>
						</div>
					</div>
				</div>
					<label for="sms_enable" generated="true" class="errorvalid" style="display:none"></label>
						    <span class="error"><?php echo isset($errors['sms_enable']) ? $errors['sms_enable'] : ''; ?></span></td>
                    </tr>

                    
		<!--
		    <tr>
			<td valign="top" width="20%"><label><?php echo __('passenger_setting');?></label><span class="star">*</span></td>   	
			<td> <?php  
					
				$labelname_type = array( "1" => "Server will select the nearest taxi and dispatch","2" => "Passenger able to select the taxi","3"=>"Passenger able to select the nearest only one taxi"); ?>
				   <div class="selector" id="uniform-user_type">
						<?php $passenger_chk = isset($validator['passenger_setting'])?$validator['passenger_setting']:$site_settings[0]['passenger_setting']; ?>
				   <select name="passenger_setting" id="passenger_setting" class="required" title="<?php echo __('passenger_setting'); ?>">
					<option value=''><?php echo __('select_label'); ?></option>
					<?php foreach($labelname_type as $labelname_key => $labelname_value) { ?>
					<option value="<?php echo $labelname_key; ?>" <?php if($passenger_chk == $labelname_key) echo "selected='selected'"; ?>><?php echo $labelname_value; ?></option>
					<?php } ?>
				    </select>
				    </div>	
				<label for="passenger_setting" generated="true" class="errorvalid" style="display:none"></label>
				<span class="error"><?php echo isset($errors['passenger_setting']) ? $errors['passenger_setting'] : ''; ?></span>

			</td>
		    </tr>
		-->
			<input type="hidden" value="2" name="passenger_setting" id="passenger_setting" > */ ?>
                     <?php /*
                     <tr>
                        <td valign="top" width="20%"><label><?php echo __('site_country'); ?> </label><span class="star">*</span></td>   
                        <td>
				<div class="new_input_field">
					<div class="formRight">
						<div class="selector" id="uniform-user_type">
							<select name="site_country" id="site_country" title="<?php echo __('enter_site_country'); ?>" >
								<option value="">-- select country --</option>
								<?php foreach($site_country as $sitecountry){ ?>
								<option value='<?php echo $sitecountry['country_id'];?>' <?php if($site_settings[0]['site_country'] == $sitecountry['country_id'] ) {  echo 'selected=selected'; } ?> ><?php echo $sitecountry['country_name'];?></option>
								<?php } ?>
							<?php echo isset($site_settings) && (!array_key_exists('site_country', $errors)) ? $site_settings[0]['site_country'] : $validator['site_country']; ?></select>
							</div>
						</div>
					</div>
							<span class="error"><?php echo isset($errors['site_country']) ? $errors['site_country'] : ''; ?></span></td>
                    </tr>
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('site_city'); ?> </label><span class="star">*</span></td>   
                        <td><div class="new_input_field">
                        <select name="site_city" id="site_city" title="<?php echo __('enter_site_city'); ?>" >
				<option>-- select city --</option>
				<?php foreach($site_city as $sitecity){ ?>
				<option value='<?php echo $sitecity['city_id'];?>' <?php if($site_settings[0]['site_city'] == $sitecity['city_id']) { echo 'selected=selected'; } ?> ><?php echo $sitecity['city_name'];?></option>
				<?php } ?>
                        <?php echo isset($site_settings) && (!array_key_exists('site_city', $errors)) ? $site_settings[0]['site_city'] : $validator['site_city']; ?></select>
			</div>
                            <span class="error"><?php echo isset($errors['site_city']) ? $errors['site_city'] : ''; ?></span></td>
                    </tr> */ ?>
                    <?php /* <tr>
                        <td valign="top" width="20%"><label><?php echo __('select_currency'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field">
				<div class="formRight">
					<div class="selector" id="uniform-user_type">
						<?php $com_cur_chk=isset($validator['company_currency'])?$validator['company_currency']:$site_settings[0]['company_currency']; ?>
						<select name="company_currency" id="site_currency" class="required" title="<?php echo __('select_currency'); ?>" >
							<option value=""><?php echo __('select_label'); ?></option>
							<?php foreach($currency_symbol as $key=>$currencysymbol){ ?>
							<option value="<?php echo $currencysymbol;?>" <?php if($com_cur_chk == $currencysymbol) { echo 'selected=selected'; } ?> ><?php echo $currencysymbol;?></option>
							<?php } ?>
                            </select>
						</div>
					</div>
				</div>
						    <span class="error"><?php echo isset($errors['company_currency']) ? $errors['company_currency'] : ''; ?></span></td>
                    </tr> */?>


                 <?php /*  

 
 
            <tr>
                    <td valign="top" width="20%"><label><?php echo __('home_page_title'); ?></label><span class="star">*</span></td>   
                     <td>
				<div class="new_input_field" style="width:400px;">
					<input type="text" class="required" name="home_page_title" id="home_page_title" title="<?php echo __('home_page_content'); ?>" maxlength="100" value="<?php echo isset($site_settings) &&!array_key_exists('home_page_title',$postvalue)? trim($site_settings[0]['home_page_title']):$postvalue['home_page_title']; ?>">
				</div>
				<?php if(isset($errors) && array_key_exists('home_page_title',$errors)){ echo "<span class='error'>".$errors['home_page_title']."</span>";}?>
			</td>
                    </tr>
 
 
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('home_page_content'); ?></label><span class="star">*</span></td>   
                        <td>

					
                        <textarea name="home_page_content" class="ckeditor required" id="home_page_content" rows="7" cols="35" title="<?php echo __('home_page_content'); ?>" style="resize:none;"><?php echo isset($site_settings) &&!array_key_exists('home_page_content',$postvalue)? trim($site_settings[0]['home_page_content']):$postvalue['home_page_content']; ?></textarea>					

				<?php if(isset($errors) && array_key_exists('home_page_content',$errors)){ echo "<span class='error'>".$errors['home_page_content']."</span>";}?>
			</td>
                    </tr>

 			 */ ?>
                    
<tr>
                        <td valign="top" width="20%"><label><?php echo __('company_logo_label'); ?></label><span class="star">*</span></td>   
                        <td>
				<div class="new_input_field">
					<?php if(empty($site_settings[0]['company_logo'])) { $cl="required"; } else{ $cl=""; }  ?>	
					<input type="file" name="company_logo" id="site_logo" class="imageonly <?php echo $cl;?>" title="<?php echo __('select_taxi_image'); ?>" value="<?php echo isset($site_settings) &&!array_key_exists('company_logo',$postvalue)? trim($site_settings[0]['company_logo']):$postvalue['company_logo']; ?>">
					
				</div>
				<?php if(!empty($site_settings[0]['company_logo'])&&file_exists(DOCROOT.SITE_LOGO_IMGPATH.$site_settings[0]['company_logo'])){ ?>
				<div class="site_logo" style="width:160px;">
					<?php   ?>
				<img src="<?php echo URL_BASE.SITE_LOGO_IMGPATH.'/'.$site_settings[0]['company_logo'].'?'.$randy;?>" width="160">
				</div>
				<?php } ?>
				<small class="sub_note"><?php echo __('logo_desc'); ?></small>
				<?php if(isset($errors) && array_key_exists('company_logo',$errors)){ echo "<span class='error'>".$errors['company_logo']."</span>";}?>
				
			</td>
                    </tr>
                    
                  <tr>
                <td valign="top" width="20%"><label><?php echo __('site_email_logo'); ?></label><span class="star">*</span></td>   
                <td>
					<div class="new_input_field">
						<input type="file" name="email_site_logo" id="email_site_logo" class="imageonly" title="<?php echo __('select_taxi_image'); ?>" value="<?php echo isset($site_settings) &&!array_key_exists('email_site_logo',$postvalue)? trim($site_settings[0]['company_logo']):$postvalue['email_site_logo']; ?>">
					</div>
				<?php if(!empty($site_settings[0]['company_logo'])&&file_exists(DOCROOT.SITE_LOGO_IMGPATH.$_SESSION['company_id'].'_email_logo.png')){ ?>
				<div class="site_logo" style="width:160px;">
					<div class="email_site_logo" style="width:160px;">
						<img src="<?php echo URL_BASE.'public/'.UPLOADS.'/site_logo/'.$_SESSION['company_id'].'_email_logo.png';?>" width="160">
					</div>
					<?php } ?>
				<small class="sub_note"><?php echo __('email_logo_desc'); ?></small>
				<?php if(isset($errors) && array_key_exists('email_site_logo',$errors)){ echo "<span class='error'>".ucfirst($errors['email_site_logo'])."</span>";}?>
				
			</td>
                    </tr>
                    
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('company_favicon_label'); ?></label><span class="star">*</span></td>   
                        <td>
				<div class="new_input_field">
					<?php if(empty($site_settings[0]['company_logo'])) { $cl="required"; } else{ $cl=""; }  ?>
					<input type="file" name="company_favicon" id="site_favicon" class="imageonly <?php echo $cl;?>" title="<?php echo __('select_taxi_image'); ?>" value="<?php echo isset($site_settings) &&!array_key_exists('company_favicon',$postvalue)? trim($site_settings[0]['company_favicon']):$postvalue['company_favicon']; ?>">
					
				</div>
				<input type="hidden" name="favicon_old" id="favicon_old" value="<?php echo $site_settings[0]['company_favicon']; ?>" />
				<?php if(!empty($site_settings[0]['company_favicon'])&&file_exists(DOCROOT.SITE_FAVICON_IMGPATH.$site_settings[0]['company_favicon'])){ ?>
				<div class="site_logo" style="width:220px;"> 
				<img src="<?php echo URL_BASE.SITE_FAVICON_IMGPATH.$site_settings[0]['company_favicon'].'?'.$randy;;?>">
				</div>
				<?php } ?>
                                <small class="sub_note"><?php echo __('fav_desc'); ?></small>
				<?php if(isset($errors) && array_key_exists('company_favicon',$errors)){ echo "<span class='error'>".$errors['company_favicon']."</span>";}?>
			</td>
                    </tr>

                    <tr>
			<td class="empt_cel">&nbsp;</td>
                        <td colspan="" class="star">*<?php echo __('required_label'); ?></td>
                    </tr>                                
                    <tr>
                        <td valign="top">&nbsp;</td>
                        <td style="padding-left:0px;">
                            <div class="new_button">  <input type="submit" name="editsettings_submit" title ="<?php echo __('button_update'); ?>" value="<?php echo __('button_update'); ?>"></div>
                            <div class="new_button"> <input type="reset" name="editsettings_reset" title="<?php echo __('button_reset'); ?>" value="<?php echo __('button_reset'); ?>"></div>
                        </td></tr>
                </table>

            </form>
            <br/><br/>

        </div>

        <div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt" ></div></div>
    </div>

</div>
<script src="<?php echo SCRIPTPATH; ?>jscolor.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript">
 /*$(document).ready(function() {

jQuery.validator.addMethod("decimalTwo", function(value, element) {
    return this.optional(element) || /^(\d{1,3})(\.\d{2})$/.test(value);
}, "Must be in US currency format 0.99");

jQuery("#settings").validate();

$.validator.addMethod( "imageonly", function(value,element){
var pathLength = value.length; var lastDot = value.lastIndexOf( "."); var fileType = value.substring(lastDot,pathLength).toLowerCase(); return this.optional(element) || fileType.match(/(?:.jpg|.jpeg|.png)$/) }, "Please upload image file only");

//For Field Focus
//===============
var field_val = $("#app_name").val();
$("#app_name").focus().val("").val(field_val); 

var cityid= $("#site_city").val();

	if(cityid == '')
	{
		//change_city();
	}


     $("#site_country").change(function() {

      		var countryid= $("#site_country").val();

		  $.ajax({
			url:"<?php echo URL_BASE;?>add/getcitylist",
			type:"get",
			data:"country_id="+countryid,
			success:function(data){

			$('#city_list').html();
			$('#city_list').html(data);
			},
			error:function(data)
			{
				//alert(cid);
			}
		});	
    });

});

function change_city()
{

     		var countryid= $("#site_country").val();

		  $.ajax({
			url:"<?php echo URL_BASE;?>add/getcitylist",
			type:"get",
			data:"country_id="+countryid,
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
    
</script>
