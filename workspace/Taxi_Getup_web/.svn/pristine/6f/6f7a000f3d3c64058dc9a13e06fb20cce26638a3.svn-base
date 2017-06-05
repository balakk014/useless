/* request channel */
function xhr() {};

xhr.prototype.init = function() {
	try {
		this._xh = new XMLHttpRequest();
	} catch (e) {
		var _ie = new Array(
		'MSXML2.XMLHTTP.5.0',
		'MSXML2.XMLHTTP.4.0',
		'MSXML2.XMLHTTP.3.0',
		'MSXML2.XMLHTTP',
		'Microsoft.XMLHTTP'
		);
		var success = false;
		for (var i=0;i < _ie.length && !success; i++) {
			try {
				this._xh = new ActiveXObject(_ie[i]);
				success = true;
			} catch (e) {
				
			}
		}
		if ( !success ) {
			return false;
		}
		return true;
	}
}

xhr.prototype.wait = function() {
	state = this._xh.readyState;
	return (state && (state < 4));
}

xhr.prototype.process = function() {
	if (this._xh.readyState == 4 && this._xh.status == 200) {
		this.processed = true;
	}
}

xhr.prototype.send = function(urlget,data) {
	if (!this._xh) {
		this.init();
	}
	if (!this.wait()) {
		this._xh.open("GET",urlget,false);
		this._xh.send(data);
		if (this._xh.readyState == 4 && this._xh.status == 200) {
			return this._xh.responseText;
		}
	}
	return false;
}

function addView(productID) {
		var remotos = new xhr, nt;
		nt = remotos.send('update.php?view=1&productID='+productID);
}

function addComment(productID) {
		var text = document.getElementById('commenttext').value, 
		remotos = new xhr,
		nt = remotos.send('update.php?comment='+text+'&productID='+productID);
		document.getElementById('newcomment').innerHTML = nt; 
		document.getElementById('commentform').style.display  = 'none'; 
}

function rateProduct(rating,pass_logid)  {
		var remotos = new xhr,
		nt = remotos.send(this.hostname+'/tdispatch/update_controllerrating/?rating='+rating+'&pass_logid='+pass_logid),
		rating = (rating * 26) - 8;
		document.getElementById('current-rating').style.width = rating+'px';
		//document.getElementById('ratelinks').style.display = 'none';
		document.getElementById('ratingtext').innerHTML = 'Rating Updated !!';

		if($('#ratingtext')){
		  $('#ratingtext').fadeIn('fast');
		  $('#ratingtext').animate({opacity: 1.0}, 2000)
		  $('#ratingtext').fadeOut('slow');
		}
}

var offsetfromcursorX=12 //Customize x offset of tooltip
var offsetfromcursorY=10 //Customize y offset of tooltip

var offsetdivfrompointerX=10 //Customize x offset of tooltip DIV relative to pointer image
var offsetdivfrompointerY=14 //Customize y offset of tooltip DIV relative to pointer image. Tip: Set it to (height_of_pointer_image-1).

document.write('<div id="dhtmltooltip" ></div>') //write out tooltip DIV
document.write('<img id="dhtmlpointer" style="visibility: hidden" src="'+this.hostname+'/public/images/arrow2.gif">') //write out pointer image

var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

var pointerobj=document.all? document.all["dhtmlpointer"] : document.getElementById? document.getElementById("dhtmlpointer") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}


function sTD(term){
  if (ns6||ie){
    if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
    if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
    var remotos = new xhr,
    nt = remotos.send('update.php?term='+term);
    tipobj.innerHTML="<strong>"+term+"</strong>: "+nt;
    enabletip=true
    return false
  }
}

function positiontip(e){
if (enabletip){
var nondefaultpos=false
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var winwidth=ie&&!window.opera? ietruebody().clientWidth : window.innerWidth-20
var winheight=ie&&!window.opera? ietruebody().clientHeight : window.innerHeight-20

var rightedge=ie&&!window.opera? winwidth-event.clientX-offsetfromcursorX : winwidth-e.clientX-offsetfromcursorX
var bottomedge=ie&&!window.opera? winheight-event.clientY-offsetfromcursorY : winheight-e.clientY-offsetfromcursorY

var leftedge=(offsetfromcursorX<0)? offsetfromcursorX*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth){
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=curX-tipobj.offsetWidth+"px"
nondefaultpos=true
}
else if (curX<leftedge)
tipobj.style.left="5px"
else{
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetfromcursorX-offsetdivfrompointerX+"px"
pointerobj.style.left=curX+offsetfromcursorX+"px"
}

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight){
tipobj.style.top=curY-tipobj.offsetHeight-offsetfromcursorY+"px"
nondefaultpos=true
}
else{
tipobj.style.top=curY+offsetfromcursorY+offsetdivfrompointerY+"px"
pointerobj.style.top=curY+offsetfromcursorY+"px"
}
tipobj.style.visibility="visible"
if (!nondefaultpos)
pointerobj.style.visibility="visible"
else
pointerobj.style.visibility="hidden"
}
}

function hTD(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
pointerobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}

document.onmousemove=positiontip
