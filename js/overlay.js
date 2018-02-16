var browser = {

	WindowInnerSize: function(pDoc){
		var width, height;
		if(!pDoc){
			pDoc = document;
	    }		

		if (self.innerHeight){
			width = self.innerWidth;
			height = self.innerHeight;
		}
		else if (pDoc.documentElement && pDoc.documentElement.clientHeight){ 
			width = pDoc.documentElement.clientWidth;
			height = pDoc.documentElement.clientHeight;
		}
		else if (pDoc.body){ 		
			width = pDoc.body.clientWidth;
			height = pDoc.body.clientHeight;
		}
		return {innerWidth : width, innerHeight : height};
	},

	WindowScrollPos: function(pDoc){
		var left, top;
		if (!pDoc)
			pDoc = document;

		if(self.pageYOffset){
			left = self.pageXOffset;
			top = self.pageYOffset;
		}
		else if (pDoc.documentElement && pDoc.documentElement.scrollTop){
			left = document.documentElement.scrollLeft;
			top = document.documentElement.scrollTop;
		}
		else if(pDoc.body){ 
			left = pDoc.body.scrollLeft;
			top = pDoc.body.scrollTop;
		}
		return {scrollLeft : left, scrollTop : top};
	},

	WindowScrollSize: function(pDoc){
		var width, height;
		if (!pDoc)
			pDoc = document;

		if((pDoc.compatMode && pDoc.compatMode == "CSS1Compat")){
			width = pDoc.documentElement.scrollWidth;
			height = pDoc.documentElement.scrollHeight;
		}
		else{
			if(pDoc.body.scrollHeight > pDoc.body.offsetHeight)
				height = pDoc.body.scrollHeight;
			else
				height = pDoc.body.offsetHeight;

			if(pDoc.body.scrollWidth > pDoc.body.offsetWidth ||
				(pDoc.compatMode && pDoc.compatMode == "BackCompat") ||
				(pDoc.documentElement && !pDoc.documentElement.clientWidth)
			)
				width = pDoc.body.scrollWidth;
			else
				width = pDoc.body.offsetWidth;
		}
		return {scrollWidth : width, scrollHeight : height};
	},

	WindowSize: function(){
		var innerSize = this.WindowInnerSize();
		var scrollPos = this.WindowScrollPos();
		var scrollSize = this.WindowScrollSize();

		return{
			innerWidth :  innerSize.innerWidth, innerHeight : innerSize.innerHeight,
			scrollLeft :  scrollPos.scrollLeft, scrollTop : scrollPos.scrollTop,
			scrollWidth : scrollSize.scrollWidth, scrollHeight : scrollSize.scrollHeight
		};
	}
}


Overlay =  new function(){

var t = this; 

t.instance = this;
t.overlay  = null;
t.id = '__overlay';
t.zindex = 1020;
t.selects = [];

t.show = function(){
    if($(t.id)!=null){
       return false;
    }
   
   
    this.hideSelects();
   
    div = document.createElement("DIV");
    div.id = t.id;
    div.zIndex = t.zindex;
    div.className = 'popup-overlay';       
    t.overlay = document.body.appendChild(div); 
    
    document.body.style.overflow= 'hidden';
    
    t.resize();

     Event.observe(window, 'resize', t.resize);
    return true;
}
         
         
t.hide = function(){
   if($(t.id)==null){
     return false;
   }

   this.overlay.parentNode.removeChild(t.overlay);
   document.body.style.overflow= 'auto';
   
   Event.stopObserving(window,'resize',t.resize);
   this.overlay = null;
   this.obs  = null;  
   this.showSelects();
   return true;
}   


t.resize = function(){
    var windowSize = browser.WindowScrollSize();
	$(t.id).style.width  = windowSize.scrollWidth + "px";
	$(t.id).style.height = windowSize.scrollHeight + "px";
}
           

t.hideSelects = function(){
   var s = document.getElementsByTagName('select');
   var len = s.length;
   var style;
   for(var i = 0; i < len; i++){
 	  style = s[i].style.display;
 	  this.selects[i] = [s[i],style];
 	  s[i].style.display = 'none';
   }
}
 
t.showSelects = function(){
   var len = t.selects.length; 
   for(var i=0;i<len;i++){
      t.selects[i][0].style.display = t.selects[i][1];
   }
} 
  
  
}  
 


  


