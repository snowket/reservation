
function httpAjaxRequest(){
  this.id =0;
  this.httpAjax   = null;
  this.sendParam  = null;
  this.charset    = 'UTF-8'; 
  this.Data       = null;  //data to send
  this._iframe    = null;
  this.timeout    = 10000;
  this.onreadystatechange = null;
  return this._init();
}

(function() {
id    = 0;        //transaction id
STACK = Array();  //array to store all transactions results

//*********************************************************//
//*** Creates request object to server ********************//
httpAjaxRequest.prototype.open = function(method,url,async,user,passwd){
  this.sendParam = {
        'method' :  method,
        'url'    :  url,
        'async'  :  async,
        'user'   :  user,
        'passwd' :  passwd
    };

}

//*********************************************************//
//*** Sends a request to server ***************************//
httpAjaxRequest.prototype.send = function(){
   if(!this.httpAjax)
    {
      try     {this._init();}
      catch(e){return false;}
    }
   //*** sending through XmlHTTP ********************//
   if(this.httpAjax)
      this._sendXmlHTTP(query);
   //*** sending through Iframe object **************//  
   else if(this._iframe){
       try{
            //*** sending via GET method  ***********// 
            if(this.sendParam['method']=="GET")
               {
                  var query = this.Data.type=='form'?this._formToString():this._hashToString();
                  this._iframe.src = this._addToUrl(query);                    
                }
            //*** sending via POST method  **********//     
            else{
                  if(this.Data.type=='form')
                    { try{
						var newForm = this._iframe.document.createElement("input");
                          //  var newForm = doc.createElement(this.Data.value);
                //           setTimeout(function(){this._iframe.contentWindow.document.body.appendChild(newForm)}, 0);               
                 
                      }catch(e){alert(e.message);}

                    //alert(this.iframe.forms[0].elements.length);
                    } 
             }    
         }
         catch(e){return false;}

     }  
   
}

//*********************************************************//
//*** Obtains server answer *******************************//
httpAjaxRequest.prototype.onreadystatechange2 = function(){
  if(this.httpAjax)
    {  
    	var _this = this; 
        tout = window.setInterval(
              function()
                {

                  if(_this.httpAjax.readyState == 4)
				    {
						_this._connAbort(tout);
				    }
                }, 
			  1); 
          setTimeout(function(){_this._connAbort(tout)},_this.timeout);  
   }
  else if(this._iframe){
	   
    } 
}


httpAjaxRequest.prototype.getData = function(){
 var _this = this; 
 tout = window.setInterval(
   function()
	{
	  if(_this.httpAjax)
		{ 
		  if(_this.httpAjax.readyState == 4)
			  return _this._connAbort(tout);
		} 
	  else if(this._iframe)
	    {
	      if(this._iframe.document.body.innerHTML!="")
		    {
		      if(iframe.document.body.load = true)
			    return _this._connAbort(tout); 
		    }
		  
        } 	
	},	
  10); 
  setTimeout(function(){ return _this._connAbort(tout)},_this.timeout);  

}


//*********************************************************//
//*** Aborts connection and clears timeout ****************//
httpAjaxRequest.prototype._connAbort = function(tout){
   window.clearInterval(tout); 
   if(this.httpAjax)
	 { 
       STACK[id] = {}; 
	   STACK[id].httpStatus = this.httpAjax.status;
	   if(this.httpAjax.status>=200&&this.httpAjax.status<300)
	     { 
		   if(this.httpAjax.responseText)
		     STACK[id].responseText = this.httpAjax.responseText; 
		   if(this.httpAjax.responseXML)
			 this._processXML(this.httpAjax.responseXML); 
	     }
	   else{
	         STACK[id].responseText = null;
			 STACK[id].responseXML  = null;
             switch(this.httpAjax.status)
 	          {
			    case(400): STACK[id].httpResponse = "BAD REQUEST";          break;
				case(401): STACK[id].httpResponse = "NOT AUTHORIZED";       break;
	            case(403): STACK[id].httpResponse = "ACCESS FORBIDDEN";     break;
	            case(404): STACK[id].httpResponse = "DOCUMENT NOT FOUND";   break;
	            case(408): STACK[id].httpResponse = "REQUEST TIMEOUT";      break;
				case(414): STACK[id].httpResponse = "REQUEST-URI TOO LONG"; break;
	            case(502): STACK[id].httpResponse = "BAD GATEWAY";          break;
	            case(503): STACK[id].httpResponse = "SERVICE UNAVAILABLE";  break;
	            case(504): STACK[id].httpResponse = "GATEWAY TIMEOUT";      break;				
	          }
		 } 	
     } 
  try{ this.onreadystatechange = this.onreadystatechange();}
  catch(e){}   
 
}

httpAjaxRequest.prototype.getResult = function(){
  return STACK[id];
}


httpAjaxRequest.prototype._processXML = function(xmldata){
   for(var i=0;i<xmldata.childNodes.length;i++)
     { 
	   if(xmldata.childNodes[i].nodeType==1){
	     this._processXML(xmldata.childNodes[i]);
		 
		 }
	    else {
		     STACK[id][xmldata.nodeName] = xmldata.childNodes[i].nodeValue;
         }
	 }
}


//*********************************************************//
//***  Method handles data from a form ********************//
httpAjaxRequest.prototype.prepareForm = function(formid){
  if(typeof formid == 'string')
     var f = (document.getElementById(formid)|| document.forms[formid]);
  else if(typeof formid == 'object')  
     var f = formid;
  else return; 
  this.Data ={'type' : 'form', 'value' : f};
}
 
//*********************************************************//
//***  Method handles data from hash object ***************//
httpAjaxRequest.prototype.prepareHash = function(hash){

  if(typeof hash == 'object')
    this.Data ={'type' : 'hash', 'value' : hash};
} 


//*****************************************************************//
//*** Private methods *********************************************//

//*********************************************************//
//*** Initializes new XMLHttpRequest object ***************//
httpAjaxRequest.prototype._init = function(){
  // microsoft ie browser - first way  //
 try{
     this.httpAjax = new ActiveXObject("Msxml2.XMLHTTP");
     return true;
    } 
    catch(e){
      // microsoft ie browser - alternative way //
      try
        {
          this.httpAjax = new ActiveXObject("Microsoft.XMLHTTP");
          return true;
        }
        catch(e){
            //*** non-microsoft browser ***//  
            try{ 
                 this.httpAjax = new XMLHttpRequest();
                 return true;
              }
              catch(e){
                 //*** emulating XMLHttpRequest by iframe ***//
                 /*
				 try{
                      this._iframe = document.createElement("IFRAME");
                      this._iframe.style.width   = "400";
                      this._iframe.style.height  = "400";
                      this._iframe.style.border  = "2px solid #000";
                     iframe.style.display = 'none';
					  this._iframe.document = this._iframe.contentWindow.document?this._iframe.contentWindow.document:this._iframe.document;
                      document.body.appendChild(this._iframe); 
                      return true;
                    }
                   catch(e)
                    {
                      alert("Your browser does not support Ajax");    
                      return false;  
                    }
                    */
                 return false;            
              } 
        }         
    }
   
}

//*********************************************************//
//*** Sends data through XmlHTTP **************************//
httpAjaxRequest.prototype._sendXmlHTTP = function(){
  var data = this.Data.type=='form'?this._formToString():this._hashToString();
  //*** sending via GET method  ***********// 
  if(this.sendParam['method']=="GET")
   {
     var url = this._addToUrl(data);
     this.httpAjax.open(this.sendParam['method'],url, this.sendParam['async'], this.sendParam['user'],this.sendParam['passwd']);   
     this.httpAjax.send(null); 
   }
  //*** sending via POST method  **********// 
  else
    {
     
       this.httpAjax.open(this.sendParam['method'],this.sendParam['url'], this.sendParam['async'], this.sendParam['user'],this.sendParam['passwd']);
       this.httpAjax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset='+this.charset);
       this.httpAjax.send(data);
    }  

}


httpAjaxRequest.prototype._sendIframe = function(data,charset){

}



//*********************************************************//
//*** Adds data to url for GET method *********************//
 httpAjaxRequest.prototype._addToUrl =function(data){
   return this.sendParam.url + (this.sendParam.url.indexOf('?')>=0? '&' : '?')+ data; 
 }




//*********************************************************//
//*** Converts a hash to valid request query **************//
httpAjaxRequest.prototype._hashToString = function(){
  var parts = [];
  var data = this.Data.value;
  for(var i in data)
     parts[parts.length] = i+"="+escape(data[i]);   
  return parts.join('&'); 
}


//*********************************************************//
//*** Converts form data to valid request query ***********//
httpAjaxRequest.prototype._formToString= function(){
   var parts = [];
   var f = this.Data.value;
   for(i=0;i<f.elements.length;i++)
     {   
       if(f.elements[i].name !="")
         parts[parts.length] = f.elements[i].name+"="+escape(f.elements[i].value);
     }
       
   return parts.join('&'); 
}

})();