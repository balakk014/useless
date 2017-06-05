/************************Edit Booking - Start *****************************/

	
$(document).ready(function(){

toggle(31);

GeocodeFromAddress();

$("#firstname").autocomplete(URL_BASE+"tdispatch/firstname_load", {
});


$("#email").autocomplete(URL_BASE+"tdispatch/email_load", {
});

$("#phone").autocomplete(URL_BASE+"tdispatch/phone_load", {
});

//$('#map12').html('');
$('.widget.addbooking_widget').hide();

var date = new Date();
var currentMonth = date.getMonth(); // current month
var currentDate = date.getDate(); // current date
var currentYear = date.getFullYear(); //this year
$("#pickup_date").datepicker( {
showTimepicker:true,
showSecond: true,
dateFormat: 'yy-mm-dd',
stepHour: 1,
stepMinute: 1,
minDate: new Date(currentYear, currentMonth, currentDate),
stepSecond: 1
} );

$("#pickup_time").timepicker( {
showTimepicker:true,
showSecond: false,
timeFormat: 'hh:mm:ss',
stepHour: 1,
stepMinute: 1,
minDateTime : new Date(),
stepSecond: 1
} );


$("#frmdate").datepicker( {
showTimepicker:true,
showSecond: true,
dateFormat: 'yy-mm-dd',
stepHour: 1,
stepMinute: 1,
minDateTime : new Date(),
stepSecond: 1
} );

$("#todate").datepicker( {
showTimepicker:true,
showSecond: true,
dateFormat: 'yy-mm-dd',
stepHour: 1,
stepMinute: 1,
minDateTime : new Date(),
stepSecond: 1
} );

$("#firstname").focus();	

toggle(31);

$("#editbooking_form").validate();

$('#fixedprice').blur(function(){
var price = $(this).val();

if(price == '')
{
	$('#min_fare').removeClass('strike');
	var tot_price = $('#edit_total_fare').val();	
	$('#total_price').html(tot_price);
	
}
else
{	
	$('#total_price').html(price);
	$('#min_fare').addClass('strike');
}

});

$('#firstname').change(function(){
	$('#passenger_id').val('');
	$('#email').removeAttr('readonly');
	$('#phone').removeAttr('readonly');
	$('#show_group').hide();
	$('#usergroup_list').html('');
	//$('#load_location').html('');
	//$('#load_journey').html('');
	//$('#show_suggestion').hide();
});

$('#email').change(function(){
	$('#passenger_id').val('');
	$('#firstname').removeAttr('readonly');
	$('#phone').removeAttr('readonly');
	$('#show_group').hide();
	$('#usergroup_list').html('');
	//$('#load_location').html('');
	//$('#load_journey').html('');
	//$('#show_suggestion').hide();
});

$('#phone').change(function(){
	$('#passenger_id').val('');
	$('#email').removeAttr('readonly');
	$('#firstname').removeAttr('readonly');
	$('#show_group').hide();
	$('#usergroup_list').html('');
	//$('#load_location').html('');
	//$('#load_journey').html('');
	//$('#show_suggestion').hide();
});


});


function get_editgroupdetails(userid,groupid)
{

	var url= SrcPath+"tdispatch/geteditgrouplist/?userid="+userid+"&groupid="+groupid;

	$.post(url, {
	}, function(response){	
		if(trim(response) != '')
		{
			$('#show_group').show();			
			$('#usergroup_list').html('');
			$('#usergroup_list').html(response);
		}
		else
		{
			$('#show_group').hide();	
			$('#usergroup_list').html('');

		}	
	});

}


function change_fromtolocation(fromlocation,tolocation)
{
 $('#edit_current_location').val(urldecode(fromlocation));
 $('#edit_drop_location').val(urldecode(tolocation));
GeocodeFromAddress();
}

function urldecode(str) {
   return decodeURIComponent((str+'').replace(/\+/g, '%20'));
}


function popup_location(fid)
{
	$('#add_pick_drop').remove();
	$('#load_location li').removeClass('location_selected');
	var p = $("#fid_"+fid);
	var position = p.position();
	var pheight = (position.top)-20;

	$("<div id='add_pick_drop' style='top:"+pheight+"px; display: block;'><div id='close_location' style='color:black;float:right;padding:5px 5px 5px 5px ! important;'>X</div><div class='button dredB add_pic_but'><input type='button' value='Add To Pick Up' title='Add To Pick Up' id='add_to_pickup' onClick='add_booking_pickup("+fid+");'></div><div class='button greenB add_pic_but'><input type='button' value='Add To Drop off' title='Add To Drop off'  id='add_to_dropoff' onClick='add_booking_dropoff("+fid+");'></div></div>").insertAfter('#fid_'+fid);
	$('#selectclass_'+fid).addClass('location_selected');

$('#close_location').click(function(){
	$('#add_pick_drop').remove();
});


}

function add_booking_pickup(fid)
{

	 var pickuplocation = $('#fid_'+fid).text();
	 $('#edit_current_location').val(urldecode(pickuplocation));
	$('#add_pick_drop').remove();
	GeocodeFromAddress();
	$('#add_pick_drop').remove();
}

function add_booking_dropoff(fid)
{
	 var droplocation = $('#fid_'+fid).text();
	 $('#edit_drop_location').val(urldecode(droplocation));
	$('#add_pick_drop').remove();
	GeocodeFromAddress();
}

/*
function check_passengerexit()
{

	var passenger_id = $('#passenger_id').val();
	var email = $('#email').val();
	var phone = $('#phone').val();
	var fixedprice = $('#fixedprice').val();
	var payment_valid = true;

	var url= SrcPath+"tdispatch/checkemailuserdetails/";
	var dataS = "email="+email+"&passenger_id="+passenger_id;		

	$.ajax
	({ 			
		type: "POST",
		url: url, 
		data: dataS, 
		async:false,
		cache: false, 
		dataType: 'html',
		success: function(response) 
		{ 	
			if(trim(response) != 'N')
			{		
				$('#uemailavilable').html('');
				$('#uemailavilable').html(trim(response));
			}
			else
			{
				$('#uemailavilable').html('');
			}	
		} 
		 
	});



	var url= SrcPath+"tdispatch/checkphoneuserdetails/";
	var dataS = "phone="+phone+"&passenger_id="+passenger_id;	
	
	$.ajax
	({ 			
		type: "POST",
		url: url, 
		data: dataS, 
		cache: false, 
		async:false,
		dataType: 'html',
		success: function(response) 
		{ 	
			if(trim(response) != 'N')
			{		
				$('#uphoneavilable').html('');
				$('#uphoneavilable').html(trim(response));
			}
			else
			{
				$('#uphoneavilable').html('');
			}	
		} 
		 
	});


	if($('#group_id'))
	{
		var group_id = $('#group_id').val();
		var total_fare = $('#total_fare').val();
		var payment_type = $('[name="payment_type"]:checked').val(); 
		//alert(payment_type);
		if(payment_type ==4 && group_id !='')
		{	
			var url= SrcPath+"tdispatch/checkgrouplimit/";
			var dataS = "group_id="+group_id+"&total_fare="+total_fare;	

			$.ajax
			({ 			
				type: "POST",
				url: url, 
				data: dataS, 
				cache: false, 
				async:false,
				dataType: 'html',
				success: function(response) 
				{ 	
					if(trim(response) == 'N')
					{	
						$("#payment_error").html("account_no_sufficient");	
						$('#payment_error').show();
						payment_valid = false;
					}
					else
					{
						$("#payment_error").html("");
						$('#payment_error').hide();
						payment_valid = true;
					}	
				} 
				 
			});
		}

	}	
	else
	{
		if(passenger_id !='')
		{
			var recurrent = $('[name="payment_type"]:checked').val(); 
			if(payment_type ==4)
			{
				$("#payment_error").html("donot_have_account");
				$('#payment_error').show();
				payment_valid = false;
			}
			else
			{
				$("#payment_error").html("");
				$('#payment_error').hide();
				payment_valid = true;
			}
		}

	}


	var uemailavilable = $('#uemailavilable').html();
	var uphoneavilable = $('#uphoneavilable').html();

	if(uphoneavilable == "" && uemailavilable == "" && payment_valid == true)
	{
		return true;
	}	
	else
	{
		event.preventDefault();	
		return false;
	}


}
*/
	function check_passengerexit()
	{

		var passenger_id = $('#passenger_id').val();
		var email = $('#email').val();
		var phone = $('#phone').val();
		var fixedprice = $('#fixedprice').val();
		var payment_valid = true;

		var url= SrcPath+"tdispatch/checkpassenger_email_phone/";
		var dataS = "email="+email+"&phone="+phone+"&passenger_id="+passenger_id;		
		console.log(dataS);

		$('#dispatch_loading').html('<div class="media-body"><h4 style="text-align:center" class="media-heading">Loading ......... </h4></div>');
		
		$.ajax
		({ 			
			type: "POST",
			url: url, 
			data: dataS, 
			async:false,
			cache: false, 
			dataType: 'html',
			success: function(response) 
			{ 	
				var chk_result = response.split('~');
				var chk_email=0;
				var chk_phone=0;
				console.log(chk_result[0]);
				if(chk_result[0]== 1)
				{		
					$('#uemailavilable').html('');
					$('#dispatch_loading').html('');
					$('#uemailavilable').html(language.email_exists);
				}
				else
				{	
					$('#uemailavilable').html('');
					$('#dispatch_loading').html('');
					$('#uphoneavilable').html('');				
				}
				
				if(chk_result[1]== 1)
				{
					$('#uphoneavilable').html('');
					$('#uphoneavilable').html(language.phone_exists);
				}	
				else
				{	
					$('#uemailavilable').html('');
					$('#dispatch_loading').html('');
					$('#uphoneavilable').html('');				
				}
			} 
			 
		});

		var uemailavilable = $('#uemailavilable').html();
		var uphoneavilable = $('#uphoneavilable').html();

		if(uphoneavilable == "" && uemailavilable == "" && payment_valid == true)
		{
			return true;
		}	
		else
		{
			event.preventDefault();	
			return false;
		}


	}


    function driver_details()
    { 		
	var search_driver = $('#search_driver').val();
	var pass_logid = $('#passenger_log_id').val();
	var dataS = "pass_logid="+pass_logid+"&search_driver="+search_driver;		
	$.ajax
	({ 			
		type: "GET",
		url: URL_BASE+"/tdispatch/search_driver_location", 
		data: dataS, 
		cache: false, 
		dataType: 'html',
		success: function(response) 
		{ 	
			$('#driver_details').html(response);			
		} 
		 
	});	
      }	

$(function(){ 


	$('#add_exclusion').click(function(){  
	var newRow = $("#sub_add tr").length+1; 
	 $("#sub_add").append('<tr id="row_'+newRow+'"><td><input type="text" placeholder="Exclus.start" name="exclus_start[]" id="exclus_start'+newRow+'"  class="required mt10" style="width:120px;"  title=""><br><span id="error'+newRow+'" style="display:none;color:red;font-size:11px;"></span><td><input type="text" placeholder="Exclus.end" name="exclus_end[]" id="exclus_end'+newRow+'"  class="required mt10 " style="width:120px;margin-left:12px;" title=""></td><td><button type="button" class="remove_icon" onClick="return removetr_contact('+newRow+');"></td></tr>');     

	$("#exclus_start"+newRow).datepicker( {
	showTimepicker:true,
	showSecond: true,
	dateFormat: 'yy-mm-dd',
	stepHour: 1,
	stepMinute: 1,
	minDateTime : new Date(),
	stepSecond: 1
	});


	$("#exclus_end"+newRow).datepicker( {
	showTimepicker:true,
	showSecond: true,
	dateFormat: 'yy-mm-dd',
	stepHour: 1,
	stepMinute: 1,
	minDateTime : new Date(),
	stepSecond: 1
	});


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

$('.cal_label').click(function (e) {
var id = $(this).text();

 if ($('#monthDays_'+id).is(':checked'))
 {	
       $('#monthDays_'+id).attr('checked',false);
       $('#calactive_'+id).removeClass('green_active');	
 }
 else
 {	
       $('#monthDays_'+id).attr('checked',true);
       $('#calactive_'+id).addClass('green_active');	
 }			

});

$('.cale_label').click(function (e) {
var id = $(this).text();

 if ($('#daysofweek_'+id).is(':checked'))
 {	
       $('#daysofweek_'+id).attr('checked',false);
       $('#calactive_'+id).removeClass('green_active');	
 }
 else
 {	
       $('#daysofweek_'+id).attr('checked',true);
       $('#calactive_'+id).addClass('green_active');	
 }			

});


$('#edit_booking').click(function() {
	$('#log_details').hide();
	$('#feedback_details').hide();
	$('#share_details').hide();
	$('#edit_details').show();
});

$('#log_booking').click(function() {
	$('#edit_details').hide();
	$('#feedback_details').hide();
	$('#share_details').hide();
	$('#log_details').show();

});

$('#feedback_booking').click(function() {
	$('#edit_details').hide();
	$('#log_details').hide();
	$('#share_details').hide();
	$('#feedback_details').show();
});

$('#share_booking').click(function() {
	$('#edit_details').hide();
	$('#log_details').hide();
	$('#feedback_details').hide();
	$('#share_details').show();
});


$('#rating_button').click(function() {

	var feedback_comment = $('#feedback_comment').val();

	var form_valid = 1; 

	if(trim(feedback_comment).length<=50)
	{ 
		$('#feedback_commenterror').html(language.please_enter_comment);
		form_valid = 0;
	}	
	else
	{
		$('#feedback_commenterror').html("");
	}
	if(form_valid == 1)
	{ 
		$("#feedback_success").html('<img src="'+IMGPATH+'/loader.gif">');

		var pass_logid = $('#pass_logid').val();
		var url= URL_BASE+"/tdispatch/update_controllercomments/?feedback_comments="+feedback_comment+'&pass_logid='+pass_logid;

		$.post(url, {
		}, function(response){
			$("#feedback_success").html("");
			//$('#feedback_success').html("<?php echo __('feedback_updated_success'); ?>");
			$('#feedback_comment').val("");
			location.href = URL_BASE+"/tdispatch/managebooking/";
			
			if($('#feedback_success')){
			  $('#feedback_success').fadeIn('fast');
			  $('#feedback_success').animate({opacity: 1.0}, 2000)
			  $('#feedback_success').fadeOut('slow');
			}

		});
	}

});

$('#sharing_button').click(function() {

	var share_email = $('#share_email').val();
	var share_note = $('#share_note').val();
	var filter = /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i;
	var form_valid = 1; 
        if (!filter.test(share_email)) {
		$('#share_emailerror').html(language.please_provide_email);
		email.focus;
		form_valid = 0;	
	}
	else
	{
		$('#share_emailerror').html("");
	}

	if(trim(share_note).length<=50)
	{ 
		$('#share_noteerror').html(language.please_enter_note);
		form_valid = 0;
	}	
	else
	{
		$('#share_noteerror').html("");
	}
	if(form_valid == 1)
	{ 
		$("#shared_success").html('<img src="'+IMGPATH+'/loader.gif">');

		var pass_logid = $('#pass_logid').val();
		var url= URL_BASE+"tdispatch/sharing_bookdetails/?share_email="+share_email+'&share_note='+share_note+'&pass_logid='+pass_logid;

		$.post(url, {
		}, function(response){
			$("#shared_success").html("");
			$('#shared_success').html("booking_shared_success");

			if($('#shared_success')){
			  $('#shared_success').fadeIn('fast');
			  $('#shared_success').animate({opacity: 1.0}, 2000)
			  $('#shared_success').fadeOut('slow');
			}

		});
	}

});


$('#cancel_button').click(function() {


	var cancel_Submit = confirm('sure_want_cancel');				
	if(cancel_Submit == true)
	{
		var pass_logid = $('#pass_logid').val();
		var url= URL_BASE+"tdispatch/cancel_booking/?pass_logid="+pass_logid;

		$.post(url, {
		}, function(response){
			document.location.href=URL_BASE+"tdispatch/managebooking";
		});
	}
	

});

$('#complete_payment').click(function() {

$('#complete_loading').html('<img src=""'+IMGPATH+'/loader.gif" align="middle"> loading...');

	var pass_logid = $('#pass_logid').val();
	var complete_distance = $('#complete_distance').val();
	var complete_waitingtime = $('#complete_waitingtime').val();
	var complete_waitingcost = $('#complete_waitingcost').val();
	var complete_nightcharge = $('#complete_nightcharge').val();
	var complete_total = $('#complete_total').val();
	var complete_remark = $('#complete_remark').val();

	var url= SrcPath+"tdispatch/complete_payment/?pass_logid="+pass_logid+"&complete_distance="+complete_distance+"&complete_waitingtime="+complete_waitingtime+"&complete_waitingcost="+complete_waitingcost+"&complete_nightcharge="+complete_nightcharge+"&complete_total="+complete_total+"&complete_remark="+complete_remark;	

	$.post(url, {
	}, function(response){
		document.location.href=URL_BASE+"tdispatch/managebooking";
	});


});


$('#complete_update').click(function() {

$('#complete_loading').html('<img src=""'+IMGPATH+'/loader.gif" align="middle"> loading...');

	var pass_logid = $('#pass_logid').val();
	var complete_distance = $('#complete_distance').val();
	var complete_waitingtime = $('#complete_waitingtime').val();
	var complete_waitingcost = $('#complete_waitingcost').val();
	var complete_nightcharge = $('#complete_nightcharge').val();
	var complete_total = $('#complete_total').val();
	var complete_remark = $('#complete_remark').val();

	var cancel_Submit = confirm("<?php echo __('sure_want_complete'); ?>");				
	if(cancel_Submit == true)
	{
		var pass_logid = $('#pass_logid').val();
		var url= URL_BASE+"tdispatch/complete_booking/?pass_logid="+pass_logid+"&complete_distance="+complete_distance+"&complete_waitingtime="+complete_waitingtime+"&complete_waitingcost="+complete_waitingcost+"&complete_nightcharge="+complete_nightcharge+"&complete_total="+complete_total+"&complete_remark="+complete_remark;

		$.post(url, {
		}, function(response){
			document.location.href=URL_BASE+"tdispatch/managebooking";
		});
	}
	else
	{
		document.location.href=URL_BASE+"tdispatch/managebooking";
	}

});


$('#complete_button').click(function(event) {

$("#complete_popup").lightbox_me({centered: true, onLoad: function() {
        $("#complete_popup").find("input:first").focus();
        	google.maps.event.trigger(tdmap, "resize");
            tdmap.setCenter(currCenter);	
    }});
					
event.preventDefault();
});

var tdmap,currCenter;
$('#dispatch_button').click(function(event) {

var check_valid1 = $("#editbooking_form").valid();
var check_valid2 = check_passengerexit();
 var show_map = SHOW_MAP;
if(check_valid1 == true && check_valid2 == true)
{
	var url= SrcPath+"tdispatch/dispatchbooking/";
	var dataS = $('#editbooking_form').serialize();	

	$.ajax
	({ 			
		type: "POST",
		url: url, 
		data: dataS, 
		cache: false, 
		async:false,
		dataType: 'html',
		success: function(response) 
		{ 	
			$("#dispatch_popup").lightbox_me({centered: true, onLoad: function() {
				$("#dispatch_popup").find("input:first").focus();
				    if(show_map != 1)
				    {
                       			tdmap.refresh();	
				     }
			    }});

			driver_details();	

			event.preventDefault();
		} 
		 
	});

}



   
    if(show_map != 1)
    {		
	

	$('#close_x').click(function() {
	window.location.href = URL_BASE+"tdispatch/managebooking";
	});

	$('.js_lb_overlay').click(function() { 
	window.location.href = URL_BASE+"tdispatch/managebooking";
	});

	driver_details();

	var pickup_lat = $('#pickup_lat').val();
	var pickup_lng = $('#pickup_lng').val();

	var drop_lat = $('#drop_lat').val();
	var drop_lng = $('#drop_lng').val();
    //var currCenter;
   
   /*
   var latlng = new google.maps.LatLng(pickup_lat,pickup_lng);
      var options = {
        zoom: 6,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      
      var tdmap = new google.maps.Map(document.getElementById("dispatch_map"), options);
    */
    
    
	tdmap = new GMaps({
	el: '#dispatch_map',
	lat: pickup_lat,
	lng: pickup_lng,
	}); 
    currCenter = tdmap.getCenter();
   //alert(currCenter);
	tdmap.addMarker({
	lat: pickup_lat,
	lng: pickup_lng,
	title: "Current_Location",
	/* details: {
	  database_id: 42,
	  author: 'HPNeo'
	},
	click: function(e){
	  if(console.log)
	    console.log(e);
	  alert('You clicked in this marker');
	},
	mouseover: function(e){
	  if(console.log)
	    console.log(e);
	}*/
	});
	tdmap.addMarker({
	lat: drop_lat,
	lng: drop_lng,
	title: "Drop_Location",
	/*infoWindow: {
	  content: '<p>HTML Content</p>'
	}*/
	});
	//tdmap.refresh();

   }	
 

});


/* Map */

	function updateStatus(status) {
		$("#info").html(status);

	}

	function clearMarker() {
		if (marker != null)
			marker.setMap(null);
	}
		
			var marker = null;
			var working = false;
      var marker;	
      var flag = false;

      var latlng = new google.maps.LatLng(54.559322, -4.174804);
      var options = {
        zoom: 6,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      
      var map = new google.maps.Map(document.getElementById("map"), options);

			//map.controls[google.maps.ControlPosition.TOP_RIGHT].push(new FullScreenControl(map));

      // set up directions renderer
      var rendererOptions = { draggable: false};
      var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
      directionsDisplay.setMap(map);
			directionsDisplay.setPanel(document.getElementById("edit_directions"));
      google.maps.event.addListener(directionsDisplay, 'directions_changed', function() {
				updateStatus("Route changed");
				if (!working) {
					working = true;
					GetElevation(directionsDisplay.directions.routes[0]);
				}
      });
			google.maps.event.addListener(directionsDisplay, 'routeindex_changed', function() {
				updateStatus("Route index changed");
				/*if (!working) {
					working = true;*/
					GetElevation(directionsDisplay.directions.routes[directionsDisplay.getRouteIndex()]);
				/*}*/
			});

			// autocomplete
			var autocomplete = new google.maps.places.Autocomplete(document.getElementById('edit_current_location'), {});
			var toAutocomplete = new google.maps.places.Autocomplete(document.getElementById('edit_drop_location'), {});


			google.maps.event.addListener(autocomplete, 'place_changed', function() {
				var place = autocomplete.getPlace();
				GeocodeFromAddress();
				GotoLocation(place.geometry.location,1);
			});

			google.maps.event.addListener(toAutocomplete, 'place_changed', function() {
				var place = toAutocomplete.getPlace();
				GeocodeFromAddress();
				GotoLocation(place.geometry.location,2);
			});


			function GotoLocation(location,field) {
				GetLocationInfo(location,field);
				map.setCenter(location);
			}


		      function GetLocationInfo(latlng,field)
		      {
			if (latlng != null)
			{
			  ShowLatLong(latlng,field);
			}
		      }

		      function ShowLatLong(latLong,field)
		      {
			// show the lat/long
			if (marker != null) {
			  marker.setMap(null);
			}
		marker = new google.maps.Marker({
			  position: latLong,
			  map: map});
				
			if(field == 1)
			{
				$('#pickup_lat').val(latLong.lat());
				$('#pickup_lng').val(latLong.lng());
				$('#payment_sec').show();	
			}
			else
			{
				$('#drop_lat').val(latLong.lat());
				$('#drop_lng').val(latLong.lng());
				$('#payment_sec').show();	
			}

		      }

			
      			function GeocodeFromAddress() {
	
				working = true;
				// clear all fields
				$("#info").html("");
				$("#distance").html("");
				$("#start").html("");
				$("#end").html("");
				$("#min").html("");
				$("#max").html("");
				$("#ascent").html("");
				$("#descent").html("");
				clearMarker();
			
        // geocode from address
        updateStatus("Locating from address...");
        var geocoder = new google.maps.Geocoder();
        var from = $("#edit_current_location").val();
        geocoder.geocode({ 'address': from },
          function(results, status) {
            if (results[0]) {
              var result = results[0];
              var fromLatLng = result.geometry.location;
		$('#pickup_lat').val(result.geometry.location.lat());
		$('#pickup_lng').val(result.geometry.location.lng());
              GeocodeToAddress(fromLatLng);
            }
            else {
              updateStatus("From address not found");
		working = false;
            }
          }
        );
      }

      function GeocodeToAddress(fromLatLng) {
        // geocode to address
        updateStatus("Locating to address...");
        var geocoder = new google.maps.Geocoder();
        var to = $("#edit_drop_location").val();
        geocoder.geocode({ 'address': to },
          function(results, status) {
            if (results[0]) {
              var result = results[0];
              var toLatLng = result.geometry.location;
		$('#drop_lat').val(result.geometry.location.lat());
		$('#drop_lng').val(result.geometry.location.lng());
              CalculateRoute(fromLatLng, toLatLng);
            }
            else {
              updateStatus("To address not found");
		working = false;
            }
          }
        );
      }

      function CalculateRoute(fromLatLng, toLatLng) {
        // calculate the route
        updateStatus("Calculating route...");
        var directions = new google.maps.DirectionsService();

				var routeType = $('#routeType').val();
				var travelMode = google.maps.DirectionsTravelMode.DRIVING;
				if (routeType == "Walking")
					travelMode = google.maps.DirectionsTravelMode.WALKING;
				else if (routeType == "Public transport")
					travelMode = google.maps.DirectionsTravelMode.TRANSIT;
				else if (routeType == "Cycling")
					travelMode = google.maps.DirectionsTravelMode.BICYCLING;
				
        var request = {
          origin: fromLatLng,
          destination: toLatLng,
          travelMode: travelMode,
	  provideRouteAlternatives: true
        };
        directions.route(request, function(result, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(result);

            GetElevation(result.routes[0]);
          }
          else {
            var statusText = getDirectionStatusText(status);
            updateStatus("An error occurred calculating the route - " + statusText);
						working = false;
          }
        });
      }

			var locations;
      function GetElevation(route) {
				clearMarker();
        // show distance
				var distance = 0;
				var time = 0;
				for (var i=0; i<route.legs.length; i++) {
					var theLeg = route.legs[i];
					distance += theLeg.distance.value;
					time += theLeg.duration.value;
				}


				hm_hours = ('0'+Math.round(time/3600) % 24).slice(-2)+' hours';
				hm_secs = ('0'+Math.round(time/60)%60).slice(-2)+' mins';
				
				if(hm_hours != '00 hours')
				{
					show_time = hm_hours+hm_secs;
				}
				else
				{
					show_time = hm_secs;					
				}


				$("#distance").html(showDistance(distance));

				var default_company_unit = $('#default_company_unit').val();
				if(default_company_unit=='MILES'){
					var km =(distance/1609.34).toFixed(1);
				}else if(default_company_unit=='KM'){
					var km =((distance/100) / 10).toFixed(1);
				}else{
					var km =(distance/1609.34).toFixed(1);
				}
				
				/*if(flag == true)
				{*/
					$('#edit_find_km').html(km+" "+default_company_unit);
					$('#edit_distance_km').val(km);
					$('#desc').html('Rate Kilometer '+km); 
	
					$('#edit_find_duration').html(show_time);	
					$('#edit_total_duration').val(show_time);	
					var model_minfare = $('#model_minfare').val();
					$('#min_value').html(model_minfare);

				var model_id = $('#taxi_model').val();
				var pickup_location = $('#edit_current_location').val();

				var pickup_lat = $('#pickup_lat').val();
				var pickup_lng = $('#pickup_lng').val();

				var pickup_latlng = pickup_lat+','+pickup_lng;

				var city = "";
				var state = "";

				geocoder = new google.maps.Geocoder();
				var latlng = new google.maps.LatLng(pickup_lat,pickup_lng);
				geocoder.geocode({'latLng': latlng}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						//Check result 0
						var result = results[0];
						//look for locality tag and administrative_area_level_1

						for(var i=0, len=result.address_components.length; i<len; i++) {
							var ac = result.address_components[i];
							if(ac.types.indexOf("locality") >= 0) city = ac.long_name;
							if(ac.types.indexOf("administrative_area_level_1") >= 0) state = ac.long_name;
						}
						//only report if we got Good Stuff
						if(city != '') {
							//alert("Hello to you out there in "+city);
							$('#cityname').val(city);
							var city_id = $('#city_id').val();
							calculate_totalfare(km,model_id,city,city_id);

						}
					} 
				});
	
					/*
					var total_fare = Math.round(parseFloat(km)*parseFloat(model_minfare));
					var vat_tax = $('#vat_tax').html();
					var tot_tax =  (total_fare*vat_tax/100).toFixed(2);
					var tot_amt = parseFloat(total_fare)+parseFloat(tot_tax);
					$("#min_fare").html(total_fare);
					$('#sub_total').html(total_fare);
					$('#total_price').html(total_fare);
					$('#total_fare').val(total_fare);
					$('#min_fare').removeClass('strike');

					var price = $('#fixedprice').val();
					if(price == '')
					{
					//$('#show_suggestion').hide();	
					$('#min_fare').removeClass('strike');
					var tot_price = $('#total_fare').val();	
					$('#total_price').html(tot_price);

					}
					else
					{	//$('#show_suggestion').hide();
					$('#total_price').html(price);
					$('#min_fare').addClass('strike');
					}
					*/
				/*}
				else
				{

					var price = $('#fixedprice').val();
					if(price == '')
					{
					//$('#show_suggestion').hide();	
					$('#min_fare').removeClass('strike');
					var tot_price = $('#total_fare').val();	
					$('#total_price').html(tot_price);

					}
					else
					{	//$('#show_suggestion').hide();
					$('#total_price').html(price);
					$('#min_fare').addClass('strike');
					}

					flag = true;					
				}
				*/

				
        // get all the lat/longs
        locations = [];
        for (var i=0; i<route.legs.length; i++) {
          var thisLeg = route.legs[i];
          for (var j=0; j<thisLeg.steps.length; j++) {
            var thisStep = thisLeg.steps[j];
            for (var k=0; k<thisStep.lat_lngs.length; k++) {
              locations.push(thisStep.lat_lngs[k]);
            }
          }
        }
				
				updateStatus("Calculating elevation for " + locations.length + " locations...");
				elevations = [];
				currentPos = 0;
				getElevation();
			}
			
			function showResults(results) {
				// display the results
				var ascent = 0;
				var descent = 0;
				ShowElevation("#start", results[0]);
				ShowElevation("#end", results[results.length-1]);
				var minElevation = results[0];
				var maxElevation = results[0];
				var chartData = [];
				var distance = 0;
				
				for (var i=0; i<results.length; i++) {
					minElevation = Math.min(results[i], minElevation);
					maxElevation = Math.max(results[i], maxElevation);
					
					// calculate distance
					if (i > 0) {
						var d = google.maps.geometry.spherical.computeDistanceBetween (locations[i-1], locations[i]);
						distance += d;
					}
					
					chartData.push([distance/1000, results[i]]);
					
					if (i>0) {
						var thisAscent = results[i] - results[i-1];
						if (thisAscent > 0)
							ascent += thisAscent;
						else
							descent -= thisAscent;
					}
				}
				ShowElevation("#ascent", ascent);
				ShowElevation("#descent", descent);
				ShowElevation("#min", minElevation);
				ShowElevation("#max", maxElevation);
				// chart
				
				
				function showTooltip(x, y, contents) {
					$('<div id="tooltip">' + contents + '</div>').css( {
							position: 'absolute',
							display: 'none',
							top: y,
							left: x + 20,
							border: '1px solid #fdd',
							padding: '2px',
							'background-color': '#fee',
							opacity: 0.80
					}).appendTo("body").fadeIn(200);
				}

				var previousPoint = null;
			}
			
			var currentPos = 0;
			var partLength = 100;
			var elevations = [];
			function getElevation() {
        // calculate the elevation of the route
				var locationsPart = [];
				var end = Math.min(locations.length, currentPos+100);
				for (i=currentPos; i<end; i++) {
					locationsPart.push(locations[i]);
				}
        updateStatus("Calculating elevation for " + currentPos + " to " + end + " (of " + locations.length + ")...");
				
				var positionalRequest = {
          'locations': locationsPart
        };

        var elevator = new google.maps.ElevationService();
        // Initiate the location request
        elevator.getElevationForLocations(positionalRequest,
          function(results, status) {
            if (status == google.maps.ElevationStatus.OK) {
							for (var i=0; i<results.length; i++) {
								elevations.push(results[i].elevation);
							}
							currentPos += partLength;
							if (currentPos > locations.length) {
								showResults(elevations);
								updateStatus("Elevation calculated using " + locations.length + " locations");
								working = false;
							}
							else {
								getElevation();
							}
            }
            else {
							if (status == google.maps.ElevationStatus.OVER_QUERY_LIMIT) {
								updateStatus("Over query limit calculating the elevation for " + currentPos + " to " + (currentPos + partLength) + " (of " + locations.length + "), waiting 1 second before retrying");
								setTimeout("getElevation()", 1000);		
							} 
							else {
								updateStatus("An error occurred calculating the elevation - " + elevationStatusDescription(status));
								working = false;
							}
            }
          }
        );
      }

			function elevationStatusDescription(status) {
				switch (status) {
					case "OVER_QUERY_LIMIT": return "Over query limit";
					case "UNKNOWN_ERROR": return "Unknown error";
					default: return status;
				}
			}
			
      function ShowElevation(selector, elevation) {
        $(selector).html(Math.round(elevation) + " metres (" + Math.round(elevation*3.2808399) + " feet)");
      }
	function showDistance(distance) {
		return Math.round(distance/100) / 10 + " km (" +
		Math.round((distance*0.621371192)/100) / 10 + " miles)";
	}

/* Map */
