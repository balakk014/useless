console.log(ACTION);

// JavaScript Document

if(ACTION == 'managebooking')
{
	
/************************* Manage Booking - Start *****************************/

$(document).ready(function(){

//$("#keyword").focus(); 
//toggle(31);


$("#sign_up").lightbox_me({centered: true, onLoad: function() {
        $("#sign_up").find("input:first").focus();
    }});


$('.oddtr').bind('click', function(){
var isrdata = this.id;
var findid = isrdata.split('_').pop();

$('table#changetr tr#edit').remove();
$("<tr id='edit'><td colspan=10 id='append_result'></td></tr>").insertAfter( $(this).closest('tr') );
$('#append_result').html('<img src="'+IMGPATH+'/loader.gif" align="middle"> loading...');

	var dataS = "passenger_logid="+trim(findid);		
	$.ajax
	({ 			
		type: "GET",
		url: URL_BASE+"/tdispatch/edit_booking", 
		data: dataS, 
		cache: false, 
		dataType: 'html',
		success: function(response) 
		{ 	
			$('#append_result').html('');
			$('#append_result').html(response);		
		} 
		 
	});	

});

$('.eventr').bind('click', function(){
var isrdata = this.id;
var findid = isrdata.split('_').pop();

	$('table#changetr tr#edit').remove();
	$("<tr id='edit'><td colspan=10 id='append_result'></td></tr>").insertAfter( $(this).closest('tr') );

	$('#append_result').html('<img src="'+IMGPATH+'/loader.gif" align="middle"> loading...');

	var dataS = "passenger_logid="+trim(findid);		
	$.ajax
	({ 			
		type: "GET",
		url: URL_BASE+"tdispatch/edit_booking", 
		data: dataS, 
		cache: false, 
		dataType: 'html',
		success: function(response) 
		{ 	
			$('#append_result').html('');
			$('#append_result').html(response);		
		} 
		 
	});	
	


});


	$("#filter_date").datepicker( {
	showTimepicker:true,
	showSecond: true,
	dateFormat: 'yy-mm-dd',
	stepHour: 1,
	stepMinute: 1,
	minDateTime : new Date(current_date),
	stepSecond: 1
	});
	
	$("#to_date").datepicker( {
	showTimepicker:true,
	showSecond: true,
	dateFormat: 'yy-mm-dd',
	stepHour: 1,
	stepMinute: 1,
	minDateTime : new Date(current_date),
	stepSecond: 1
	});


} );



$('#sort_type').live('change',function(){
  changetableresult();
});

$('#sort_by').live('change',function(){
  changetableresult();
});

$('#booking_filter').live('change',function(){
  changetableresult();
});


function changetableresult()
{
	
var search_txt = $('#search_txt').val();
var filter_date = $('#filter_date').val();
var to_date = $('#to_date').val();
var sort_type = $('#sort_type').val();
var sort_by = $('#sort_by').val();
var booking_filter = $('#booking_filter').val();

var dataS = "search_txt="+trim(search_txt)+"&filter_date="+filter_date+"&to_date="+to_date+"&sort_type="+trim(sort_type)+"&sort_by="+sort_by+"&booking_filter="+booking_filter+"&page=1";	
$('#change_result').html('<img alt="ajax-loader" src="'+URL_BASE+'"/public/css/img/ajax-loaders/ajax-loader-1.gif" />');
	$.ajax
	({ 			
		type: "GET",
		url: URL_BASE+"tdispatch/managebookingsearch", 
		data: dataS, 
		cache: false, 
		dataType: 'html',
		success: function(response) 
		{ 	
			$('#hide_pagi').hide();
			$('#change_result').html('');			
			$('#change_result').html(response);			
		} 
		 
	});	
}

function pagin_info(page_no)
{

		var search_txt = $('#search_txt').val();
		var filter_date = $('#filter_date').val();
		var to_date = $('#to_date').val();
		var sort_type = $('#sort_type').val();
		var sort_by = $('#sort_by').val();
		var booking_filter = $('#booking_filter').val();

		var dataS = "search_txt="+trim(search_txt)+"&filter_date="+filter_date+"&to_date="+to_date+"&sort_type="+trim(sort_type)+"&sort_by="+sort_by+"&booking_filter="+booking_filter+"&page="+page_no;		

		  $.ajax({
			url: URL_BASE+"tdispatch/managebookingsearch", 
			type:"GET",
			data:dataS,
			success:function(response){
			$('#hide_pagi').hide();
			$('#change_result').html();
			$('#change_result').html(response);
			},
			error:function(data)
			{
				//alert(cid);
			}
		});	
    
}

    function driver_details()
    { 	
	var pass_logid = $('#passenger_log_id').val();	
	var search_driver = $('#search_driver').val();
	var dataS = "pass_logid="+pass_logid+"&search_driver="+search_driver;		

	$.ajax
	({ 			
		type: "GET",
		url: URL_BASE+"tdispatch/search_driver_location", 
		data: dataS, 
		cache: false, 
		dataType: 'html',
		success: function(response) 
		{ 	
			$('#driver_details').html(response);			
		} 
		 
	});	
      }	
      


/************************* Manage Booking - End *****************************/




/************************* Edit Booking - End *****************************/

}


if((ACTION == 'add_frequentjourney')||(ACTION == 'edit_frequentjourney'))
{
/**************************** Frequent Journey -  Start *********************************/

$(document).ready(function(){
/*
$("#from_location").autocomplete("<?php echo URL_BASE; ?>tdispatch/load_location/", {
	width: 268,
	//matchContains: true,
	//mustMatch: true,
	//minChars: 0,
	//multiple: true,
	//highlight: false,
	//multipleSeparator: ",",
	selectFirst: false

});


$("#to_location").autocomplete("<?php echo URL_BASE; ?>tdispatch/load_location/", {
	width: 268,
	//matchContains: true,
	//mustMatch: true,
	//minChars: 0,
	//multiple: true,
	//highlight: false,
	//multipleSeparator: ",",
	selectFirst: false

});
*/
 $("#journey_name").focus(); 
 
 toggle(31);

$("#addjourney_form").validate();


jQuery.validator.addMethod('notEqual', function(value) {
var from_location=document.getElementById('from_location').value;
return (value !=  from_location); }, 'From Location and To Location should be different.');


function initialize() {
	
	var input = document.getElementById('from_location');
	var input1 = document.getElementById('to_location');
	var autocomplete = new google.maps.places.Autocomplete(input);
	var autocomplete1 = new google.maps.places.Autocomplete(input1);

	google.maps.event.addListener(autocomplete, 'place_changed', function() {		
		var place = autocomplete.getPlace();

		if (!place.geometry) {
		  // Inform the user that the place was not found and return.
		  input.className = 'notfound';
		  return;
		}
		
		//Assinging the Locations While Auto Suggestions			
		
	});

	google.maps.event.addListener(autocomplete1, 'place_changed', function() {		
		var place = autocomplete1.getPlace();

		if (!place.geometry) {
		  // Inform the user that the place was not found and return.
		  input.className = 'notfound';
		  return;
		}
		
		//Assinging the Locations While Auto Suggestions			
		
	});
}

google.maps.event.addDomListener(window, 'load', initialize);

});

/*
$("#from_location").change(function(){
$("#from_location").val('');
});

$("#to_location").change(function(){
$("#to_location").val('');
});
*/

/**************************** Frequent Journey -  End **********************************/
}

if((ACTION == 'add_frequentlocation')||(ACTION == 'edit_frequentlocation'))
{
/**************************** Frequent Location -  Start *******************************/

$(document).ready(function(){

 $("#location_name").focus(); 
 
 toggle(31);

$("#addlocation_form").validate();

jQuery.validator.addMethod('check_location', function(value) {

return check_location(); }, '');

});

function location_initialize() {
	var input = (document.getElementById('location'));
	var autocomplete = new google.maps.places.Autocomplete(input);

	google.maps.event.addListener(autocomplete, 'place_changed', function() {	
	
		var place = autocomplete.getPlace();
		if (!place.geometry) {
		  // Inform the user that the place was not found and return.
		  input.className = 'notfound';
		  return;
		}
		
		//Assinging the Locations While Auto Suggestions			
		
	});


}

google.maps.event.addDomListener(window, 'load', location_initialize);

/*
function check_location(){

    var elocation =  $("#location").val(); 
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({"address":elocation }, function(results, status) {

    if (status == google.maps.GeocoderStatus.OK) {

	return true;
      }
      else
      {  
	return false;
     }				
    });

}
*/
}
