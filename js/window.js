function JCFloatDiv(){
	var _this = this;
	this.floatDiv = null;
	this.x = this.y = 0;

	this.Show = function(div, left, top, restrictDrag){	
		var zIndex = parseInt(div.style.zIndex);
		if(zIndex <= 0 || isNaN(zIndex))
			zIndex = 100;

		div.style.zIndex = zIndex;

		if (left < 0)
			left = 0;

		if (top < 0)
			top = 0;

		div.style.left = parseInt(left) + "px";
		div.style.top = parseInt(top) + "px";

		div.restrictDrag = restrictDrag || false;
	}

	this.Close = function(div){
		return;
	}

	this.Move = function(div, x, y){
		if(!div){
			return;
	    }		

		var left = parseInt(div.style.left)+x;
		var top = parseInt(div.style.top)+y;

		if (div.restrictDrag)
		{
			//Left side
			if (left < 0)
				left = 0;

			//Right side
			if ((document.compatMode && document.compatMode == "CSS1Compat")){
				windowWidth = document.documentElement.scrollWidth; 
		    }		
			else{
				if (document.body.scrollWidth > document.body.offsetWidth ||
					(document.compatMode && document.compatMode == "BackCompat") ||
					(document.documentElement && !document.documentElement.clientWidth)
				){
					windowWidth = document.body.scrollWidth;
			    }		
				else{
					windowWidth = document.body.offsetWidth;
				}	
			}

			var floatWidth = div.offsetWidth; 
			if (left > (windowWidth - floatWidth)){
				left = windowWidth - floatWidth;
			}	

			//Top side
			if (top < 0)
				top = 0;
		}

		div.style.left = left+'px';
		div.style.top = top+'px';

	}



	this.StartDrag = function(e, div){
        try{
           if(window.event){
              window.event.cancelBubble = true;
              window.event.returnValue = false;
           }
           else{
              e.preventDefault();
           }
        }
        catch(excp){}

		if(!e){
           e = window.event;
	    }		
		this.x = e.clientX + document.body.scrollLeft;
		this.y = e.clientY + document.body.scrollTop;
		this.floatDiv = div;

	    Event.observe(document, 'mousemove', _this.MoveDrag);
		Event.observe(document, 'mouseup',   _this.StopDrag);
	}

	this.StopDrag = function(){
		Event.stopObserving(document,'mousemove',_this.MoveDrag);
		Event.stopObserving(document,'mouseup',_this.StopDrag);
		this.floatDiv = null;
	}

	this.MoveDrag = function(e){
        try{
           if(window.event){
              window.event.cancelBubble = true;
              window.event.returnValue = false;
           }
           else{
           	  e.preventDefault();
           }
        }
        catch(e){}

		var x = e.clientX + document.body.scrollLeft;
		var y = e.clientY + document.body.scrollTop; 

		if(_this.x == x && _this.y == y)
			return;

		_this.Move(_this.floatDiv, (x - _this.x), (y - _this.y));
		_this.x = x;
		_this.y = y;
	}
}
var jsFloatDiv = new JCFloatDiv();



function popupWindow(id,options){
   options = options||{};
   this.instance = null;
   this.id      = id;
   this.win_id  = '__window_'+id; 
   this.width   = options.width||400;
   this.height  = options.height||400;
   this.title   = options.title||''; 
   this.overlay = options.overlay||false;
   this.overlay = options.resize||true;
   this.url     = options.url||'';
   
   this.x = 0;
   this.y = 0;

}


popupWindow.prototype.open = function(type,source){
  var _this = this;
  try{
  
       if($(this.win_id)){
          $(this.win_id).close();
       }
       
	   if(this.overlay){
	      Overlay.show();
	   }    
	          
    
       var instance = document.createElement('DIV');
       instance.id = this.win_id;
       instance.style.width  = this.width+20+'px';
       instance.style.height = this.height+40+'px';
       instance.style.position = 'absolute';
       instance.style.zIndex   = 1030;
      
       var h = this.height+20;
 
       html  = '<div class="popup_window_header">';
       html += '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
       html += '<tr>';
       html += '<td width="6"><img src="./images/win_corn1.gif" width="6" height="21" border="0"></td>';
       html += '<td onmousedown = "$(\''+this.win_id+'\').drag(arguments[0])">&nbsp;'+this.title+'</td>';
       html += '<td width="20" valign="top"><a href="javascript:$(\''+this.win_id+'\').close()" class="popup_window_close"><img src="./images/1x1.gif" width="16" height="16" border="0"></a></td>';       
       html += '<td width="6"><img src="./images/win_corn2.gif" width="6" height="21" border="0"></td>';
       html += '</tr>';       
       html += '</table>';
       html += '</div>';
       html += '<div class="popup_window_body" style="height:'+h+'px">'; 
       if(type == 'url'){
          html += '<iframe src="javascript:void(0)" class="popup_window_iframe" frameborder="0" style="width:'+this.width+'px;height:'+this.height+'px"></iframe>';       
       }
       else{
          html += '<div id="'+this.id+'" class="popup_window_content" style="width:'+this.width+'px;height:'+this.height+'px">';
          html += '&nbsp;';
          html += '</div>';       
       }       
       html += '<div style="width:100%"><div class="popup_window_resizer">&nbsp;</div></div>';
       html += '</div>';
       
       if(type == 'ajax'){
          ajaxUpdate(this.id,source);
       }
       else if(type == 'url'){
          $(this.id).src = source;
       }
       else if(type == 'div'){
          $(this.id).innerHTML = $(source).innerHTML; 
       }
       else if(type == 'inline'){
          $(this.id).innerHTML = source;
       }
       
       instance.innerHTML = html;
       
       this.instance = document.body.appendChild(instance);
       
        var windowSize   = browser.WindowInnerSize();
	    var windowScroll = browser.WindowScrollPos();
        
	    var left = parseInt(windowScroll.scrollLeft + windowSize.innerWidth / 2 - this.instance.offsetWidth / 2);
	    var top = parseInt(windowScroll.scrollTop + windowSize.innerHeight / 2 - this.instance.offsetHeight / 2);   
       
       jsFloatDiv.Show(this.instance, left, top, true);
       
	   
	   $(this.win_id).close = function(){  _this.close(_this)} 
	   $(this.win_id).drag  = function(e){ 
	                             var floatDiv = new JCFloatDiv();
	                             floatDiv.StartDrag(e, $(_this.win_id)); 
	                          }
		       
  }
  catch(e){
      alert(e.message);
  }
} 



popupWindow.prototype.close = function(t){   
    t = t||this;
    
	if($(t.win_id)==null){
		return false;
    }

	$(t.win_id).parentNode.removeChild($(t.win_id));
	t.instance = null;
	
	if(t.overlay){
	   Overlay.hide();
	}   	
}



