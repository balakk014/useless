<?php defined('SYSPATH') OR die("No direct access allowed."); ?>
<link rel="stylesheet" href="<?php echo URL_BASE; ?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
<?php /* <script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-1.5.1.min.js"></script> */ ?>
<script src="<?php echo URL_BASE; ?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="<?php echo URL_BASE; ?>public/js/datetimehrspicker/jquery-ui-timepicker-addon.js"></script>
<div class="new_inner_page_outer">
    <div class="widget">
	<div class="title">
	    <h6>Withdraw Request</h6>
            <div style="float: right;margin: 0;"> 
		<?php if ($count_data > 0) {
		    $export_table_count = $count_data;
		    //include_once(APPPATH . 'views/admin/export_menu.php');
		} ?>
	    </div>
        </div>
    </div>
    <div class="content_middle_out">
    <div class="content_middle_new">
	    <?php if ($company_id > 0) { ?>
        <div class="new_button" style="float: right;margin-top: 10px;margin-right: 0;"> 
        <input class="sendRequest" type="button" value="<?php echo __("withdraw_request"); ?>" />
    </div>
<?php } ?>
	
	 <form method="get" class="form" name="managerequests" id="manage_model" action="<?php echo URL_BASE . 'manage/trip_balance_report'; ?>" autocomplete="off">
	<div class="withdraw_seach">
	   
		<ul>
		    <li style="display:none">
			<input type="text" placeholder="<?php echo __('Search by phone number'); ?>" name="keyword" maxlength="256" value="<?php echo isset($srch['keyword']) ? trim($srch['keyword']) : ''; ?>" />
		    </li>
		    <li style="display:none"> 
			<div class="withdraw_select">
			<select name="brand_type">
			    <option value=""><?php echo __('brand_type'); ?></option>
			    <option <?php if (isset($srch['brand_type']) && $srch['brand_type'] == 1) { ?>selected<?php } ?> value="1"><?php echo __('Multy'); ?></option>
			    <option <?php if (isset($srch['brand_type']) && $srch['brand_type'] == 2) { ?>selected<?php } ?> value="2"><?php echo __('Single'); ?></option>
			</select>
			</div>
		    </li>
		    <li>
			<div class="withdraw_select" style="display:none">
			<select name="status">
			    <option value=""><?php echo __('status'); ?></option>
			    <option <?php if (isset($srch['status']) && $srch['status'] != '' && $srch['status'] == 0) { ?>selected<?php } ?> value="0"><?php echo __('pending'); ?></option>
			    <option <?php if (isset($srch['status']) && $srch['status'] == 1) { ?>selected<?php } ?> value="1"><?php echo __('paid'); ?></option>
			    <option <?php if (isset($srch['status']) && $srch['status'] == 2) { ?>selected<?php } ?> value="2"><?php echo __('Cancelled by admin'); ?></option>
                <option <?php if (isset($srch['status']) && $srch['status'] == 3) { ?>selected<?php } ?> value="3"><?php echo __('Cancelled by driver'); ?></option>
			</select>
			</div>
		    </li>
		    <li class="date_picker_icon">
			<input type="text" placeholder="<?php echo __('from_date_label'); ?>" readonly  title="<?php echo __('select_datetime'); ?>" id="startdate" name="startdate" value="<?php echo isset($srch['startdate']) ? trim($srch['startdate']) : date('Y-m-d 00:00:00', strtotime('-7 days')); ?>" />
			<span id="st_startdate_error" class="error"></span>
		    </li>
		    <li class="date_picker_icon">
			<input type="text" placeholder="<?php echo __('end_date_label'); ?>" readonly  title="<?php echo __('select_datetime'); ?>" id="enddate" name="enddate" value="<?php echo isset($srch['enddate']) ? trim($srch['enddate']) : convert_timezone('now',$_SESSION['timezone']); ?>"/>
			<span id="st_enddate_error" class="error"></span>	
		    </li>
		    <li class="search_btn" style="width:7% !important">
                        <div class="new_button"><input type="submit" value="<?php echo __('button_search'); ?>" id="search_user_btn" name="search_user" title="<?php echo __('button_search'); ?>" /></div>

			<!--			<div class="button blueB">
						    <input type="button" value="<?php //echo __('button_cancel');  ?>" title="<?php //echo __('button_cancel');  ?>" onclick="location.href = '<?php //echo URL_BASE . 'manage/withdrawrequest';  ?>'" />
						</div>-->
		    </li>
		     <li><div class="new_button"><input type="button" value="<?php echo __('button_cancel');  ?>" title="<?php echo __('button_cancel');  ?>" onclick="location.href = '<?php echo URL_BASE . 'manage/trip_balance_report';  ?>'" /></div>
		</ul>
	 		    
	</div>
	</form>
	<div class="withdraw_count_box">
	    <div id="quick-actions">
    		<ul>
    		    <li class="color_code_with1">
    			<div class="dash_active_left">
    			    <img class="image" src="<?php echo IMGPATH; ?>dashboard_icons/w1.png"  />
    			</div>
    			<div class="withdraw_detail_right">
    			    <a href="javascript:;" title="<?php echo __('no_of_withdraw_resquest'); ?>" class="blue-square"></a>
    			    <h2><?php echo __('no_of_withdraw_resquest'); ?></h2>    							
    			    <p><?php echo $dashboard_data[0]["total_trip"]; ?></p>
    			</div>
    		    </li>
    		    <li class="color_code_with2">
    			<div class="dash_active_left">
    			    <img class="image" src="<?php echo IMGPATH; ?>dashboard_icons/w2.png"  />
    			</div>
    			<div class="withdraw_detail_right">
    			    <a href="javascript:;" title="<?php echo __('no_of_resquest_approved'); ?>" class="red-square"></a>
    			    <h2><?php echo __('no_of_resquest_approved'); ?></h2>    							
    			    <p><?php echo ($dashboard_data[0]["approver_trip"] > 0) ? $dashboard_data[0]["approver_trip"] : 0; ?></p>
    			</div>
    		    </li>
    		    <li class="color_code_with3">
    			<div class="dash_active_left">
    			    <img class="image" src="<?php echo IMGPATH; ?>dashboard_icons/w3.png"  />
    			</div>
    			<div class="withdraw_detail_right">
    			    <a href="javascript:;" title="<?php echo __('No of Request Cancelled'); ?>" class="blue-square"></a>
    			    <h2><?php echo __('No of Request Cancelled'); ?></h2>    							
    			    <p><?php echo ($dashboard_data[0]["cancelled_trip"] > 0) ? $dashboard_data[0]["cancelled_trip"] : 0; ?></p>
    			</div>
    		    </li>
    		    
    		    <li class="color_code8">
    			<div class="dash_active_left">
    			    <img class="image" src="<?php echo IMGPATH; ?>dashboard_icons/8.png"  />
    			</div>
    			<div class="withdraw_detail_right">
    			    <a href="javascript:;" title="<?php echo __('No of Request Pending'); ?>" class="red-square"></a>
    			    <h2><?php echo __('No of Request Pending'); ?></h2>    							
    			    <p><?php echo ($dashboard_data[0]["pending_trip"]); ?></p>
    			</div>
    		    </li>
    		    
    		    
    		    <li class="color_code_with4">
    			<div class="dash_active_left">
    			    <img class="image" src="<?php echo IMGPATH; ?>dashboard_icons/w4.png"  />
    			</div>
    			<div class="withdraw_detail_right">
    			    <a href="javascript:;" title="<?php echo __('payment_transcaction'); ?>" class="sea-square"></a>
    			    <h2><?php echo __('payment_transcaction'); ?></h2>    							
    			    <p><?php echo ($dashboard_data[0]["paid_amount"] != '') ? CURRENCY . round($dashboard_data[0]["paid_amount"],2) : CURRENCY . "0"; ?></p>
    			</div>
    		    </li>
    		    
    		    <li class="color_code_with7">
    			<div class="dash_active_left">
    			    <img class="image" src="<?php echo IMGPATH; ?>dashboard_icons/w7.png"  />
    			</div>
    			<div class="withdraw_detail_right">
    			    <a href="javascript:;" title="<?php echo __('Payment Cancelled'); ?>" class="green-square"></a>
    			    <h2><?php echo __('Payment Cancelled'); ?></h2>    							
    			    <p><?php echo ($dashboard_data[0]["cancelled_amount"] != '') ? CURRENCY . round($dashboard_data[0]["cancelled_amount"],2) : CURRENCY . "0"; ?></p>
    			</div>
    		    </li>
    		    <li class="color_code_with8">
    			<div class="dash_active_left">
    			    <img class="image" src="<?php echo IMGPATH; ?>dashboard_icons/w8.png"  />
    			</div>
    			<div class="withdraw_detail_right">
    			    <a href="javascript:;" title="<?php echo __('payment_request_pending'); ?>" class="red-square"></a>
    			    <h2><?php echo __('payment_request_pending'); ?></h2>    							
    			    <p><?php echo ($dashboard_data[0]["pending_amount"] != '') ? CURRENCY . round($dashboard_data[0]["pending_amount"],2) : CURRENCY . "0"; ?></p>
    			</div>
    		    </li>
    		   
    		</ul>
	    </div>
	</div>
	<form method="get" class="form" name="trip_balance" id="trip_balance" action="<?php echo URL_BASE . 'manage/trip_balance_report'; ?>" autocomplete="off">
	<div class="withdraw_seach">
	   
		<ul>
		    <li>
			<input type="text" placeholder="Search by Phone number" name="trip_keyword" maxlength="256" value="<?php echo isset($srch['trip_keyword']) ? trim($srch['trip_keyword']) : ''; ?>" />
		    </li>
		    <li>
			<div class="withdraw_select">
			<select name="trip_payment_status">
			    <option value=""><?php echo __('status'); ?></option>
			    <option <?php if (isset($srch['trip_payment_status']) && $srch['trip_payment_status'] != '' && $srch['trip_payment_status'] == 0) { ?>selected<?php } ?> value="0"><?php echo __('pending'); ?></option>
			    <option <?php if (isset($srch['trip_payment_status']) && $srch['trip_payment_status'] == 1) { ?>selected<?php } ?> value="1"><?php echo __('paid'); ?></option>
			    <option <?php if (isset($srch['trip_payment_status']) && $srch['trip_payment_status'] == 2) { ?>selected<?php } ?> value="2"><?php echo __('Cancelled by admin'); ?></option>
			</select>
			</div>
		    </li>
		    <li class="date_picker_icon">
			<input type="text" placeholder="<?php echo __('from_date_label'); ?>" readonly  title="<?php echo __('select_datetime'); ?>" id="trip_startdate" name="trip_startdate" value="<?php echo isset($srch['trip_startdate']) ? trim($srch['trip_startdate']) : date('Y-m-d 00:00:00', strtotime('-7 days')); ?>" />
			<span id="st_startdate_error" class="error"></span>
		    </li>
		    <li class="date_picker_icon">
			<input type="text" placeholder="<?php echo __('end_date_label'); ?>" readonly  title="<?php echo __('select_datetime'); ?>" id="trip_enddate" name="trip_enddate" value="<?php echo isset($srch['trip_enddate']) ? trim($srch['trip_enddate']) : convert_timezone('now',$_SESSION['timezone']); ?>"/>
			<span id="st_enddate_error" class="error"></span>	
		    </li>
		    <li style="width:7% !important"> <div class="new_button" ><input type="submit" value="<?php echo __('button_search'); ?>" id="trip_search_user_btn" name="trip_search_user" title="<?php echo __('button_search'); ?>" /></div>		
		    </li>
		    <li><div class="new_button"><input type="button" value="<?php echo __('button_cancel');  ?>" title="<?php echo __('button_cancel');  ?>" onclick="location.href = '<?php echo URL_BASE . 'manage/trip_balance_report';  ?>'" /></div>
		    </li>
		</ul>
	 		    
	</div>
	
	<div class="withdraw_table">
		<?php if(count($data) >0 && isset($_GET['trip_keyword']) && $_GET['trip_keyword'] !="") { $d_name="";$d_amt=0; foreach ($data as $req_list) { 
			$d_name =$req_list['name'];
			if($req_list['transaction_status'] == 0)
				$d_amt +=$req_list['fare'];
		 } ?>
		<h1><b>Driver : <?php echo $d_name; ?> &nbsp; &nbsp; &nbsp;    Driver Trip Balance: <?php echo CURRENCY.round($d_amt,2); ?> </b></h1> <?php  } ?>
            <div class="responsive_table">
	    <table cellspacing="0" cellpadding="10" width="100%" align="center">
		<thead>
		    <tr>
				<th align="left" width="5%"><?php echo __('Select'); ?></th>
				<th align="left" width="5%"><?php echo __('sno_label'); ?></th>
				<th align="left" width="15%"><?php echo __('trip_id'); ?></th>
				<th align="left" width="15%"><?php echo __('driver'); ?></th>
				<th align="left" width="15%"><?php echo __('trip_amount'); ?></th>
				<th align="left" width="15%"><?php echo __('status'); ?></th>
				<th align="left" width="15%"><?php echo __('completed_date'); ?></th>
		    </tr>
		</thead>
		<tbody>
		    <?php
		   
		    if ($count_data > 0) {
			$sno = $Offset;
			foreach ($data as $req_list) {
			    $sno++;
			    $trcolor = ($sno % 2 == 0) ? 'oddtr' : 'eventr';
			    ?>
			    <tr class="<?php echo $trcolor; ?>">
			    <td align="center">
					<?php if ($req_list['transaction_status'] != 1) { ?>
					    <input data-request-id="<?php echo '#' . $req_list['trip_trans_id']; ?>" type="checkbox" name="uniqueId[]" id="trxn_chk<?php echo $req_list['trip_trans_id']; ?>" value="<?php echo $req_list['trip_trans_id']; ?>" />
				    <?php
				    } else {
					echo '-';
				    }
				    ?>
	    		</td>
				   
				<td align="center"><?php echo $sno; ?></td>
				<td><a href="<?php echo URL_BASE.'transaction/transaction_details/'.$req_list['trip_id'];?>" target="_blank"><?php echo $req_list['trip_id']; ?></a></td>
				<td><?php echo $req_list['name']."-".$req_list['mobile_number']; ?></td>
				<td><?php echo CURRENCY.$req_list['fare']; ?></td>
				
				
				
				<td>
				    <?php
				    $status = "Pending";
				    $color = "#F92E12";
				    if ($req_list['transaction_status'] == 1) {
					$status = "Paid";
					$color = "#089910";
				    } else if ($req_list['transaction_status'] == 2) {
					$status = "Cancelled by Admin";
					$color = "#F92E12";
				    }
				    ?>
				    <span style="color: <?php echo $color; ?>;"><?php echo $status; ?></span>
				</td>
				<td><?php echo Commonfunction::getDateTimeFormat($req_list['trans_current_date'], 1); ?></td> 
			    </tr>
    <?php }
} else {
    ?>
    		    <tr><td colspan="9" class="" align="center"><?php echo __('no_data'); ?></td></tr>
	<?php } ?>
		</tbody>
	    </table>
            </div>
	</div>
    </div>
    </form>
	<div class="table_bottom_control">
<?php if ($company_id == 0) { ?>
    	<!--** Multiple select starts Here ** -->
    <?php if ($count_data > 0) { ?>
	<ul class="select_all_part">
		    <li><a href="javascript:selectToggle(true, 'trip_balance');"><?php echo __('all_label'); ?></a></li>
		    <li><a href="javascript:selectToggle(false, 'trip_balance');"><?php echo __('select_none'); ?></a></li>
		    <li><div class="bottom_selection_select">
			<select name="more_action" id="more_action">
			    <option value=""><?php echo __('Change Status'); ?></option>
			    <option value="approved" ><?php echo __('Pay'); ?></option>
			    <option value="deny" ><?php echo __('Cancelled'); ?></option>
			</select>
			</div>
		    </li>
		</ul>
		<?php } ?>
    	<!--** Multiple select ends Here ** -->
<?php } ?>
	
<?php if ($count_data > 0): ?>
    	    <?php echo $pag_data->render(); ?>
<?php endif; ?> 
	
	</div>

    </div>
</div>



<?php if ($company_id == 0) { ?>
    <?php /** List Page Status Change Using Popup Start * */ ?>
    <div id="myModal" class="modal_popup" style="visibility: hidden;">
        <div class="modal-content withdraw_request_change">
    	<div class="withdraw_popup_header">    	    
    	    <h2>Withdraw Status Change</h2>
	    <span class="close">×</span>
    	</div>
    	<div class="withdraw_popup_form">
    	    <form method="post" name="list_status_update" onsubmit="return check_list_validation_form();" enctype="multipart/form-data" autocomplete="off">
		<input type="hidden" name="type" value="0"/>
    		<input type="hidden" name="withdraw_request_id" value=""/>
		<ul>
    		    <li><input type="text" readonly name="request_id" placeholder="Request ID" value=""/>
    			<em class='error' id="requestIdError"></em></li>
    		    <li><select name="status" onchange="changeStatus(this.value);">
    			    <option value=""><?php echo "Status"; ?></option>
    			    <option value="1"><?php echo __('Pay'); ?></option>
    			    <option value="2"><?php echo __('deny'); ?></option>
    			</select>
    			<em class='error' id="statusError"></em></li>
    		    <li id="paymodeModeSection"><select name="payment_mode">
    			    <option value=""><?php echo "Payment Mode"; ?></option>
				
    			</select>
    			<em class='error' id="amountError"></em></li>
    		    <li id="transactionIdSection"><input type="text" name="transaction_id" placeholder="Transaction ID" value=""/>
    			<em class='error' id="transactionidError"></em></li>
    		    <li><textarea id="comments" name="comments" placeholder="Comments"></textarea>
    			<em class='error' id="commentsError"></em></li>
    		    <li><input type="file" name="attachment" value=""/>
    			<em class='error' id="attachmentError"></em></li>
                    <li><div class="new_button"> <input type="submit" value="Submit" name="status_form_submit" title="Submit"></div></li>		   
    		</ul>    		
    	    </form>
    	</div>    	
        </div>
    </div>
    <?php /** List Page Status Change Using Popup End * */ ?>
		    <?php } else { ?>
<div id="requestModal" class="withdrow_requestpop" style="visibility: hidden;">
	<div class="withdrow_reuestpopinner">
        <div class="modal-content withdraw_request_change">
    	<div class="withdraw_popup_header">
    	    <span class="close">×</span>
    	    <h2><?php echo __("withdraw_request"); ?></h2>
    	</div>
    <?php /*<div class="withdraw_popup_form">
    	    <form method="post" name="company_request_amount" id="company_request_amount" onsubmit="return validate_request_form();" autocomplete="off">
		<ul>
		    <li><strong><?php echo __('payment_availed'); ?> :</strong> <?php echo CURRENCY; ?><span id="payment_availed"><?php echo COMPANY_AVAILABLE_AMOUNT; ?></span></li>
		    <?php
				$pending_amt = ($dashboard_data[0]["payment_transaction_pending"] != '') ? $dashboard_data[0]["payment_transaction_pending"] : 0;
		    ?>
		    <li><strong><?php echo __('payment_req_pending'); ?> :</strong> <span><?php echo CURRENCY . $pending_amt; ?></span></li>
		    <li><strong>  <?php echo __('request_amount'); ?> : </strong>
    		    <input type="text" class="numbersOnly" readonly onpaste="return false;" name="company_request_amount" placeholder="Amount" value="<?php echo COMPANY_AVAILABLE_AMOUNT - $pending_amt; ?>"/>
    		    <em class='error' id="requestAmountError"></em></li>
		    <li>
                        <div class="new_button">
			<input type="submit" value="Submit" name="company_request_form" title="Submit">
                        </div>
                        <div class="new_button">
    		    <input type="button" value="Cancel" id="closePopup" title="Cancel">
                        </div>
		    </li>
		  
		</ul>    		
    	    </form>
    	</div>
    	<?php */ ?>
        </div>
    </div>
	</div>

<?php } ?>

<div id="fade"></div>

<script type="text/javascript">
    $(document).ready(function() {
	$("#startdate,#enddate").datetimepicker({
	    showTimepicker:DEFAULT_TIME_SHOW,
	    showSecond: true,
	    timeFormat: DEFAULT_TIME_FORMAT_SCRIPT,
	    dateFormat: DEFAULT_DATE_FORMAT_SCRIPT,
	    stepHour: 1,
	    stepMinute: 1,
	    stepSecond: 1
	});
	
	$("#trip_startdate,#trip_enddate").datetimepicker({
	    showTimepicker:DEFAULT_TIME_SHOW,
	    showSecond: true,
	    timeFormat: DEFAULT_TIME_FORMAT_SCRIPT,
	    dateFormat: DEFAULT_DATE_FORMAT_SCRIPT,
	    stepHour: 1,
	    stepMinute: 1,
	    stepSecond: 1
	});

<?php if ($company_id == 0) { ?>
    	// Get the modal
    	var modal = document.getElementById('myModal');
        modal.style.display = "none";

    	// Get the button that opens the modal
    	//var btn = document.getElementById("myBtn");

    	// Get the <span> element that closes the modal
    	var span = document.getElementsByClassName("close")[0];

    	// When the user clicks the button, open the modal
    	/* btn.onclick = function() {
    		modal.style.display = "block";
    	} */

    	// When the user clicks on <span> (x), close the modal
    	span.onclick = function() {
    	    $('#more_action').val('');
    	    $('input:checkbox').removeAttr('checked');
    	    modal.style.display = "none";
            $('#fade').hide();
    	}

    	// When the user clicks anywhere outside of the modal, close it
    	window.onclick = function(event) {
    	    if (event.target == modal) {
    		modal.style.display = "none";
    	    }
    	}

    	//for More action Drop Down
    	//=========================
    	
    	
    	$('#more_action').change(function() {
    	    $("input[name=request_id],input[name=transaction_id],select[name=status],#comments").val('');
    	    $("#statusError, #amountError, #transactionidError, #commentsError").html("");
    	    //select drop down option value
    	    //======================================
    	    var selected_val= $('#more_action').val();
    	    //perform more action reject withdraw
    	    //===================================
    	    switch (selected_val) {
    		//	Current Action "reject"//block 
    		//===================================
    		case "approved":
    		    //var confirm_msg =  "<?php echo __('Are you sure want to approve Request(s)?'); ?>";
    		    var confirm_msg = confirm("<?php echo __('Are you sure want to approve the Request(s)?'); ?>");

    		    //Find checkbox whether selected or not and do more action
    		    //============================================================
    		    if($('input[type="checkbox"]').is(':checked')) {
    			var request_ids = $("input[type=checkbox]:checked").map(function() {
    			    return $(this).data("request-id");
    			}).get().join(",");
    			var withdraw_request_id = $("input[type=checkbox]:checked").map(function() {
    			    return this.value;
    			}).get().join(",");
    			$("input[name=withdraw_request_id]").val(withdraw_request_id);
    			$("input[name=request_id]").val(request_ids);
    			if(confirm_msg == true)
    			{
					$.ajax ({
						type: "POST",
						url: SrcPath+"manage/approveWalletRequest_trip",
						data: {'uniqueId':withdraw_request_id}, 
						cache: false, 
						dataType: 'html',
						success: function(response) 
						{
							location.reload();
						} 
					});
				}
    			
    			//$("select[name=status]").val(1);

                       
    			//modal.style.display = "block";
    		    } else {
    			alert("<?php echo __('Please select atleast one or more Record(s) to do this action'); ?>")	
    			$('#more_action').val('');
    			$('#fade').hide();
    		    }
    		    break;
    		case "deny":
    		    //var confirm_msg =  "<?php echo __('Are you sure want to Activate Request(s)?'); ?>";
    		    
    		    var confirm_msg = confirm("<?php echo __('Are you sure want to cancel the Request(s)?'); ?>");
    		    
    		    //Find checkbox whether selected or not and do more action
    		    //============================================================
    		    if($('input[type="checkbox"]').is(':checked')) {
    			var request_ids = $("input[type=checkbox]:checked").map(function() {
    			    return $(this).data("request-id");
    			}).get().join(",");
    			var withdraw_request_id = $("input[type=checkbox]:checked").map(function() {
    			    return this.value;
    			}).get().join(",");
    			$("input[name=withdraw_request_id]").val(withdraw_request_id);
    			$("input[name=request_id]").val(request_ids);
    			//$("select[name=status]").val(2);
    			$("#comments").attr("placeholder", "Reason");
    			$("#paymodeModeSection,#transactionIdSection").hide();
    			
    			if(confirm_msg == true)
    			{
					$.ajax ({
						type: "POST",
						url: SrcPath+"manage/disapproveWalletRequest_trip",
						data: {'uniqueId':withdraw_request_id}, 
						cache: false, 
						dataType: 'html',
						success: function(response) 
						{
							location.reload();
						} 
					});
				}
    			
    			
    			
    		    } else {
    			//alert for no record select
    			//=============================
    			alert("<?php echo __('Please select atleast one or more Record(s) to do this action'); ?>")	
    		    }
    		    break;
    		}
    		return false;  
    	    });
<?php } else { ?>
    		    // Get the modal
    		    var modal = document.getElementById('requestModal');

    		    // Get the button that opens the modal
    		    //var btn = document.getElementById("myBtn");

    		    // Get the <span> element that closes the modal
    		    var span = document.getElementsByClassName("close")[0];

    		    // When the user clicks the button, open the modal
    		    /* btn.onclick = function() {
    		modal.style.display = "block";
    	} */

    		    // When the user clicks on <span> (x), close the modal
    		    span.onclick = function() {
    			$('#more_action').val('');
    			$('input:checkbox').removeAttr('checked');
    			modal.style.display = "none";
    		    }

    		    // When the user clicks anywhere outside of the modal, close it
    		    window.onclick = function(event) {
    			if (event.target == modal) {
    			    modal.style.display = "none";
                            $('#fade').hide();
    			}
    		    }
    	
    		    jQuery('.numbersOnly').keyup(function () { 
    			this.value = this.value.replace(/[^0-9\.]/g,'');
    		    });
    	
    		    $(".sendRequest").click( function () {
    			$("#requestAmountError").html("");
			modal.style.display = "block";
                        $('#fade').show();
                                        
    		    });
    		    $("#closePopup").click( function () {
    			$("#requestAmountError").html("");
    			modal.style.display = "none";
    			$('#fade').hide();
                        
    		    });
    		    $(".close").click( function () {
					$('#fade').hide();
    		    });
<?php } ?>
		});

<?php if ($company_id == 0) { ?>
		function changeStatus(id)
		{
			if(id == 2) {
				$("#paymodeModeSection,#transactionIdSection").hide();
				$("#comments").attr("placeholder", "Reason");
			} else {
				$("#paymodeModeSection,#transactionIdSection").show();
				$("#comments").attr("placeholder", "Comments");
			}
		}

    	function selectToggle(toggle, form) {
    	    var myForm = document.forms[form];
    	    for( var i=0; i < myForm.length; i++ ) { 
    		if(toggle) {
    		    myForm.elements[i].checked = "checked";
    		} else { 
    		    myForm.elements[i].checked = ""; 
    		}
    	    }
    	}

    	function check_list_validation_form()
    	{
    	    var validate = 1;
    	    var s = document.list_status_update.status.value.trim();
    	    var r = document.list_status_update.request_id.value.trim();
    	    if(s != 2) {
				var p = document.list_status_update.payment_mode.value.trim();
				var t = document.list_status_update.transaction_id.value.trim();
			}
    	    var c = document.list_status_update.comments.value.trim();
    	    $("#requestIdError,#amountError, #statusError, #transactionidError, #commentsError").html("");
    	    if (r == "") { $("#requestIdError").html("*Required"); validate = 0; }
    	    if (s == "") { $("#statusError").html("*Required"); validate = 0; }
    	    if(s != 2) {
				if (p == "") { $("#amountError").html("*Required"); validate = 0; }
				if (t == "") { $("#transactionidError").html("*Required"); validate = 0; }
			}
    	    if (c == "") { $("#commentsError").html("*Required"); validate = 0; }
    	    if(validate) { 
				document.list_status_update.submit(); 
    	    }
    	    return false;
    	}
<?php } else { ?>
    	function validate_request_form()
    	{
    	    var validate = 1;
    	    var amount =  document.company_request_amount.company_request_amount.value.trim();
    	    var payment_availed = $("#payment_availed").text();
    	    $("#requestAmountError").html("");
    	    if (amount == "") { 
    		$("#requestAmountError").html("*Required"); 
    		validate = 0; 
    	    } else if (amount != "" && parseFloat(amount) == 0) {
    		$("#requestAmountError").html("*Amount should be greater than 0"); 
    		validate = 0; 
    	    } else if (amount != "" && parseFloat(amount) > 0 && parseFloat(amount) > parseFloat(payment_availed)) {
    		$("#requestAmountError").html("*Invalid Amount"); 
    		validate = 0; 
    	    }
    	    if(validate) {
    		document.company_request_amount.submit(); 
    		$("#company_request_amount").submit();
    	    }
    	    return false;
    	}
<?php } ?>
</script>
