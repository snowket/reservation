var ns4 = document.layers;
var ns6 = document.getElementById && !document.all;
var ie4 = document.all;

if (ns6)
	offsetX = -45;
else
	offsetX = 0;
	
	offsetY = 20;

var toolTipSTYLE="";

function initToolTips() {
	if(ns4||ns6||ie4) {
    if(ns4) 	 { toolTipSTYLE = document.toolTipLayer;}
    else if(ns6) { toolTipSTYLE = document.getElementById("toolTipLayer").style;}
    else if(ie4) { toolTipSTYLE = document.all.toolTipLayer.style; }
    if(ns4) 	 { document.captureEvents(Event.MOUSEMOVE); }
    else  		 { toolTipSTYLE.visibility = "visible"; toolTipSTYLE.display = "none";  }
    document.onmousemove = moveToMouseLoc;
  }
}

function toolTip(msg, fg, bg) {
	if(toolTip.arguments.length < 1) { // hide
		if(ns4) toolTipSTYLE.visibility = "hidden";
		else toolTipSTYLE.display = "none";
	}
	else { // show
		if(!fg) fg = "#000000";
		if(!bg) bg = "#FFFFE1";
		var content =
		'<table border="0" cellspacing="0" cellpadding="1" bgcolor="' + fg + '"><td>' +
		'<table border="0" cellspacing="0" cellpadding="1" bgcolor="' + bg + 
		'"><td align="center"><font face="sans-serif" color="' + fg +
		'" size="-2">&nbsp\;' + msg + '&nbsp\;</font></td></table></td></table>';

		if(ns4) {
			toolTipSTYLE.document.write(content);
			toolTipSTYLE.document.close();
			toolTipSTYLE.visibility = "visible";
		}
	
		if(ns6) {
		  document.getElementById("toolTipLayer").innerHTML = content;
		  toolTipSTYLE.display='block'
		}
	
		if(ie4) {
		  document.all("toolTipLayer").innerHTML=content;
		  toolTipSTYLE.display='block'
		}
	}
}

function moveToMouseLoc(e) {
	if(ns4||ns6) {
		x = e.pageX + 58;
		y = e.pageY + 7;
	}
	else {
		x = event.x + document.body.scrollLeft;
		y = event.y + document.body.scrollTop;
	}

	toolTipSTYLE.left = x + offsetX;
	toolTipSTYLE.top = y + offsetY;
	return true;
}