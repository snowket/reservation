browser = { 
  agent: navigator.userAgent.toLowerCase(),
  isIe:    (navigator.userAgent.indexOf('msie')!=-1)&&(!this.is_opera)&&(!this.is_safari)&&(!this.is_webtv),
  isOpera: (navigator.userAgent.indexOf('opera')!=-1),       
  isMoz:   (navigator.product=='Gecko'),
  isNs:    ( (navigator.userAgent.indexOf('compatible') == -1) && (navigator.userAgent.indexOf('mozilla') != -1) && (!is_opera) && (!is_webtv) && (!is_safari) )
}
	
	
function pcmsInterface(){ }
// LENA CHECK ALL
pcmsInterface.prototype.CheckAll2 = function (formname,switcher,groupname) {
	for(i=0;i<document.forms[formname].elements.length;i++)
		if(document.forms[formname].elements[i].name == groupname+"[]")
			document.forms[formname].elements[i].checked = document.forms[formname].elements[switcher].checked;
}

pcmsInterface.prototype.CheckAll = function (formname,switcher,groupname) {
	for(i=0;i<document.forms[formname].elements.length;i++) {
		if(document.forms[formname].elements[i].name == groupname+"[]") {
			document.forms[formname].elements[i].checked = document.forms[formname].elements[i].checked?false:true;
		}
    }
}

// params - hash
pcmsInterface.prototype.drawIframe = function(param){
  var id     = param.id||"pcmsIfr";
  var text   = param.text||"";
  var width  = param.width||"100%";
  var height = param.height||"350px";
  var css    = param.css||null; 
  var doc    = null;
  document.write("<iframe width=\""+width+"\" height=\""+height+"\" id=\""+id+"\" style=\"border:1px solid #CACACA;\" frameborder=0></iframe>");
  if(doc=this._getIframeDoc(id)){
      // doc.innerHTML = text;
       //doc.write(text);
       document.getElementById(id).contentWindow.document.write(text);
       document.getElementById(id).contentWindow.document.close();
    }
  if(css)
   {
     try{ doc.createStyleSheet(css); }
     catch(e){}
   }   
}


pcmsInterface.prototype._getIframeDoc = function(id){
	var iframe = document.getElementById(id);
	if(iframe.document)
		return iframe.document;
	else if(iframe.contentWindow.document)
		return iframe.contentWindow.document;
	else if(iframe.contentDocument)
		return iframe.contentDocument;
	else return false;
}


function writeCookie(cookieName, cookieContent, cookieExpireTime){
	var cookiePath = '/';
	if(cookieExpireTime>0){
		var expDate=new Date()
		expDate.setTime(expDate.getTime()+cookieExpireTime*1000*60*60)
		var expires=expDate.toGMTString()
		document.cookie=cookieName+"="+escape(cookieContent)+";path="+escape(cookiePath)+";expires="+expires+";";
	}
    else
		document.cookie=cookieName+"="+escape(cookieContent)+";path="+escape(cookiePath)+";";
}

function readCookie(cookieName){
	var ourCookie=document.cookie;
	if(!ourCookie || ourCookie=="")return ""
	ourCookie=ourCookie.split(";")
	var i=0;
	var Cookie;
	while(i<ourCookie.length){
		Cookie=ourCookie[i].split("=")[0];
		if(Cookie.charAt(0)==" ")
		Cookie=Cookie.substring(1);
		if(Cookie==cookieName){
			return unescape(ourCookie[i].split("=")[1])
		}
		i++;
	}
	return "";
}

function deleteCookie(cookieName){
	var cookiePath = '/';
	document.cookie=cookieName+"="+readCookie(cookieName)+";path="+escape(cookiePath)+";expires=Thu, 01-Jan-1970 00:00:01 GMT;";
}





//////////////////////////////////////////////////////////////
// From ALEX
function getMenuArray() {
	var ids = readCookie('pcms_menu_id');	
	var ids_arr = new Array();
	if(ids.length!=0)
	{
		ids_arr = ids.split(",");
	}
	else
	{
		ids_arr = new Array();
	}
	return ids_arr;
}

ViewPCMSMenu = function() {
	var ids_arr = getMenuArray();
	for(var i=0;i<ids_arr.length;i++)
	{
		SwapMenuImg('img'+ids_arr[i]);
		if((element = document.getElementById(ids_arr[i]))!=null)
		{
			element.style.display  = "block";
		}
	}
}


function expand(id) {
	var ids_arr = getMenuArray();
	if((element = document.getElementById(id))!=null) {
		if(element.style.display=="block") { //collapse subtree
			SwapMenuImg('img'+id);
			element.style.display = "none";
			//excuding element width 'id' from array 'ids_arr' and store result in 'new_ids_arr'
			var new_ids_arr = new Array();
			
			for(i=0, t=0;i<ids_arr.length;i++) {
			    if(ids_arr[i]!=id&&ids_arr[i].length!=0) {
			        new_ids_arr[t++] = ids_arr[i];
			    }  
			}

			//write 'new_ids_arr' in cookie if at least subtree is exploded
	        if(new_ids_arr.length>0) {	       
		        writeCookie('pcms_menu_id',new_ids_arr.join(','), 100000);
	        }
	        else {
	        	deleteCookie('pcms_menu_id');
	        }		
			
		}
		else { //explode subtree
			SwapMenuImg('img'+id);
			element.style.display = "block";
	        var in_cookie = false;//set flag 

	        for(i=0;i<ids_arr.length;i++)   {
	            if(ids_arr[i]==id) { //if there is coincidence
	                in_cookie = true;
	                break;
	            }
	        }
	        if(!in_cookie) {
	            ids_arr.push(id);
	            writeCookie('pcms_menu_id',ids_arr.join(','), 100000);
	        }		
		}
	}
}

function SwapMenuImg(id) {
	if(document.getElementById(id)!=null) {
		if(document.getElementById(id).attributes['status'].value == "off")	{
			document.getElementById(id).src="images/minus.gif";
			document.getElementById(id).attributes['status'].value = "on";
		}
		else {
			document.getElementById(id).src="images/plus.gif";
			document.getElementById(id).attributes['status'].value = "off";
		}
	}
}











//////////////////////////////////////////////////////////////
// From GR
function showHide(elmID,param) {
	var obj = document.getElementById(elmID);
	if (param == 'show' || param == 'block') {
		obj.style.display = 'block';
	}
	else if (param == 'hide' || param == 'none') {
		obj.style.display = 'none';
	}
	else {
		obj.style.display = (obj.style.display == 'none')?'block':'none';
	}
}

function update_wh(elmID,H) {
	var obj = document.getElementById(elmID);
	//obj.style.width  = parseInt(obj.style.width) + W;
	setTimeout (function() {obj.style.height = parseInt(obj.style.height) + H; writeCookie(elmID,obj.style.height,31104000);},0);
}

function CheckIsIE() {
	if (navigator.appName.toUpperCase() == 'MICROSOFT INTERNET EXPLORER') 
		return true;
	else
		return false;
}

// For ws_dragdrop.js
// http://www.quirksmode.org/viewport/compatibility.html
function active_winXY() {
	var x,y;
	var xy = Array;
	if (self.pageYOffset) // all except Explorer
	{
		x = self.pageXOffset;
		y = self.pageYOffset;
	}
	else if (document.documentElement && document.documentElement.scrollTop)
		// Explorer 6 Strict
	{
		x = document.documentElement.scrollLeft;
		y = document.documentElement.scrollTop;
	}
	else if (document.body) // all other Explorers
	{
		x = document.body.scrollLeft;
		y = document.body.scrollTop;
	}
	
	xy['x'] = x;
	xy['y'] = y;
	
	return xy;
}



function ajaxUpdate(container,query){
   var url;
   url = /index_ajax\.php/.test(query) ? query : './index_ajax.php?'+query;
   
   var ajax = new Ajax.Updater(container,url, {
          method:  'get',
          evalScripts:  true,
          onException: function(){
                alert('Failed to perform request. Check if ajax is supported');
          },
          onSuccess: function() {
        
          }
      }); 
}


function ajaxSubmit(plugin,container,formname){

  var ajax = new Ajax.Updater(container,'./index_ajax.php?m='+plugin,{
                method: 'post', 
                parameters:   Form.serialize(formname), 
                evalScripts:  true,
                onException:  function() { 
                    alert('Failed to perform request. Check if ajax is supported');
                },
                onSuccess: function() {
                  hideLoader();
                }
            });  
}


function AjaxSubmit_noform(url,div_parseInto) { 
	var myAjax = new Ajax.Updater (div_parseInto,url,{method: "GET", evalScripts: true, onComplete: showResult}); //alert(myAjax.Responders);
}
function showResult(originalRequest) {
    //put returned XML in the textarea
    params=(originalRequest.responseText);
    //alert(originalRequest.responseText);
    //$('main').innerHTML=params;
    $(div_parseInto).innerHTML=params;
    String.evalScripts(originalRequest.responseText);
}


// For printing iframes
function print(parseFrom,iframeTo,css_src) {
	document.getElementById(iframeTo).contentWindow.document.body.innerHTML = document.getElementById(parseFrom).innerHTML;
	if (CheckIsIE()==true) {
		document.iframe_print.close();
		document.iframe_print.document.createStyleSheet(css_src);
		document.iframe_print.focus();
		document.iframe_print.print();
	}
	else {
		//document.getElementById(iframeTo).close();
		importCSS(get_DOC(document.getElementById('iframe_print')),css_src);
		//document.iframe_print.createStyleSheet('./css/admin.css');
		//document.getElementById(iframeTo).document.createStyleSheet('./css/admin.css');
		document.getElementById(iframeTo).contentWindow.print();
	}
	//document.getElementById(iframeTo).print();
}

function importCSS(doc, css) {
	var css_ary = css.replace(/\s+/, '').split(',');
	var csslen, elm, headArr, x, css_file;

	for (x = 0, csslen = css_ary.length; x<csslen; x++) {
		css_file = css_ary[x];

		if (css_file != null && css_file != 'null' && css_file.length > 0) {
			// Is relative, make absolute
			if (css_file.indexOf('://') == -1 && css_file.charAt(0) != '/')
				css_file = this.documentBasePath + "/" + css_file;

			if (typeof(doc.createStyleSheet) == "undefined") {
				elm = doc.createElement("link");

				elm.rel = "stylesheet";
				elm.href = css_file;

				if ((headArr = doc.getElementsByTagName("head")) != null && headArr.length > 0)
					headArr[0].appendChild(elm);
			} else
				doc.createStyleSheet(css_file);
		}
	}
}

function get_DOC(iframe) {
	if(iframe.contentWindow) 
		return iframe.contentWindow.document;
	else if (iframe.contentDocument) 
		return iframe.contentDocument; 
	else 
		return iframe.document;
}

function selectMultiple(elmID,startFrom) {
	startFrom = startFrom || 0;
	obj = document.getElementById(elmID);
	for (i=obj.length-1;i>=startFrom;i--) {
		obj.options[i].selected = true;
	}
}




// IGNORED
/* -------------------- */
pcmsInterface.prototype.drawFloatDiv = function(id,display){

  var sourse = document.getElementById(id);
  var width   = sourse.style.width||"300px";
  var height  = sourse.style.height||"200px";
  var left    = sourse.style.left||"0px";
  var top     = sourse.style.top||"0px"; 
 
  html = document.getElementById(id).innerHTML;
  setTimeout(function(){sourse.parentNode.removeChild(sourse);},0);
   
  div  = "<div id=\""+id+"\" style=\"width:"+width+"; height:"+(height+21)+"; position:absolute;left:"+left+";top:"+top+";display:"+display+";z-index:100000000\">"; 
  div += "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width:100%;height:21px;text-align:right;\" class=\"win_header\">";
  div += "<tr>";
  div += "<td width=\"6\"><img src=\"./images/win_corn1.gif\" width=\"6\" height=\"21\" border=\"0\"></td>";
  div += "<td style=\"background-image:url(./images/line_grad1.gif)\"><a href=\"javascript:void(0)\" onclick=\"document.getElementById('"+id+"').style.display='none'\" style=\"margin:2px 3px 0px 0px;\">";
  div += "<img src=\"./images/icos16/close.gif\" width=\"16\" height=\"16\" border=\"0\" style=\"border:1px solid #87B7E8\" onmouseover=\"this.style.border='1px outset ButtonHighlight'\" onmouseout=\"this.style.border='1px solid #87B7E8'\" onmousedown=\"this.style.border='1px inset ButtonHighlight'\">";
  div += "</a></td>";
  div += "<td width=\"6\"><img src=\"./images/win_corn2.gif\" width=\"6\" height=\"21\" border=\"0\"></td>";
  div += "</tr>";
  div += "</table>";
  div += "<div style=\"overflow:auto;width:100%; height:"+height+";background:#F3F3F3\" class=\"FLOAT_main\">"; 
  div += html; 
  div += "</div>";
  div += "</div>";
  document.write(div);
}


//Encoding base 64
var Base64 = {


	_keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",


	encode: function(input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = Base64._utf8_encode(input);

		while (i < input.length) {

			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);

			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;

			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}

			output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

		}

		return output;
	},


	decode: function(input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;

		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		while (i < input.length) {

			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));

			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;

			output = output + String.fromCharCode(chr1);

			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}

		}

		output = Base64._utf8_decode(output);

		return output;

	},

	_utf8_encode: function(string) {
		string = string.replace(/\r\n/g, "\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if ((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},

	_utf8_decode: function(utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;

		while (i < utftext.length) {

			c = utftext.charCodeAt(i);

			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if ((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i + 1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i + 1);
				c3 = utftext.charCodeAt(i + 2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}

		return string;
	}

}