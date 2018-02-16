 var send = function(data){
 var dataToSend =document.getElementById('calendar_y').value+"."+document.getElementById('calendar_m').value;
 var ajax = new httpAjaxRequest(); 
 if(ajax)
  {  
     ajax.prepareHash({date:dataToSend}); 
     ajax.open("GET", "index_ajax.php?m=news");
     ajax.send();
     ajax.getData();
     ajax.onreadystatechange = function()
       { 
           data =ajax.getResult();
           document.getElementById("calendar_div").innerHTML =data.calendar; 
       }  
  }
}