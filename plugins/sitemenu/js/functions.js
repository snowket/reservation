function menuBuilder(menublocks){
  this.menuBlocks = menublocks;
  this.active = this.menuBlocks[0]; 
  this._win = null;
} 


//****/

menuBuilder.prototype.activeForm = function(){
   return 'block_'+this.active;
}


//****/

menuBuilder.prototype.addItems = function(){
   var from = $('mod_all');
   var to   = $(this.activeForm());
   
   for(i=0;i<from.options.length;i++){
       if(from.options[i].selected){
          to.appendChild(from.options[i].cloneNode(true));
       }   
   }    
}


//****/

menuBuilder.prototype.removeItems = function(){
	var form = $(this.activeForm());
	for(i=form.options.length-1;i>=0;i--){
		if(form.options[i].selected){
			form.removeChild(form.options[i]);
	    }		
	} 
}


//*** direction- 'up'|'down'  *//
menuBuilder.prototype.moveItem = function(direction){
 var form = $(this.activeForm());
 var selected = form.selectedIndex;
 if(direction=='up'){
    if(selected>0&&selected<form.options.length)
      form.insertBefore(form.options[selected],form.options[selected-1]);
   }
 else if(direction=='down'){
     if(selected>=0&&selected<(form.options.length-1))
        form.insertBefore(form.options[selected+1],form.options[selected]);
   }    

}

//****/

menuBuilder.prototype.selectItems = function(){
  var form;
  for(i=0;i<this.menuBlocks.length;i++){
      form = $('block_'+this.menuBlocks[i]);
      for(j=0;j<form.options.length;j++){
        form.options[j].selected=true;
      }  
   } 
}

//****/

menuBuilder.prototype.switchTabs = function(tab_id){
 this.active = tab_id;

 var sel_tab   = document.getElementById('tab_'+tab_id);
 var sel_block = document.getElementById('div_'+tab_id);
 for(i=0;i<this.menuBlocks.length;i++){
     if(this.menuBlocks[i]!=tab_id){
        var tab = document.getElementById('tab_'+this.menuBlocks[i]);
        tab.style.borderBottom = "1px solid #E68B2C"; 
        tab.style.borderTop    = "1px solid #CBCBCB";
        document.getElementById('div_'+this.menuBlocks[i]).style.display = 'none';  
     }
  }  
     
  sel_tab.style.borderBottom = "0px";
  sel_tab.style.borderTop = "2px solid #E68B2C";
  sel_block.style.display = 'block';
}

//****/

menuBuilder.prototype.showPreview = function(form,imagedir,divEl,posfix,toolTip){
	posfix  = posfix||'';
	if (toolTip) {
		toolTip = " onMouseOver=\\\'toolTip2(designImageName());\\\' onMouseOut=\\\'toolTip();\\\' ";
	}
	else 
		toolTip = '';
	
	var img = form.options[form.selectedIndex].value;
	var div =  document.getElementById(divEl);
	if(img != "") {
       img = imagedir+img+posfix;
       div.innerHTML = "<div style=\"margin:20px;font-size:18px;color:#CBCBCB\">Loading...</div>";
       div.innerHTML += "<div id=\"tempDiv\" style=\"display:none\"><img src=\""+img+"\" width=200 onload=\"javascript:document.getElementById('"+divEl+"').innerHTML='<img "+ toolTip +" src=\\\'"+img+"\\\' width=200 >'\"></div>";
     }
   else
    document.getElementById(divEl).innerHTML = "";
}

//****/

menuBuilder.prototype.showPreview_bgImage = function(src,dest,toolTip){
	if (toolTip) {
		toolTip = ' onMouseOver="toolTip2(\''+ src +'\');" onMouseOut="toolTip();" ';
	}
	else
		toolTip = '';
	
	var elm = document.getElementById(dest);
	if (src == "")
		elm.innerHTML = "";
	else
		elm.innerHTML = '<img ' + toolTip +' id="previewImg" src="' + src + '" border="0" />';
}

//****/

menuBuilder.prototype.switchTabs_img = function(tab_id1,tab_id2,tab_id3){
	document.getElementById('tab_'+tab_id1).style.borderBottom = "0px"; 
	document.getElementById('tab_'+tab_id1).style.borderTop    = "2px solid #E68B2C";
	document.getElementById(tab_id1).style.display = 'block';
			
	document.getElementById('tab_'+tab_id2).style.borderBottom = "1px solid #E68B2C"; 
	document.getElementById('tab_'+tab_id2).style.borderTop    = "1px solid #CBCBCB";
	document.getElementById(tab_id2).style.display = 'none';
	
	document.getElementById('tab_'+tab_id3).style.borderBottom = "1px solid #E68B2C"; 
	document.getElementById('tab_'+tab_id3).style.borderTop    = "1px solid #CBCBCB";
	document.getElementById(tab_id3).style.display = 'none';
}

//****/

menuBuilder.prototype.contentAddWindow = function(){
  this._win = new popupWindow('content_win',{width:500,height: 100})
  this._win.open('ajax','m=sitemenu&action=new_content');
}

//****/

menuBuilder.prototype.contentEditWindow = function(){
  var form = $(this.activeForm()); 
  var value = form.value;

  if(value==''){
     alert('No item selected');
  }
  else if(!/^\d+$/.test(value)){
    alert('Selected item is not a content page. You can\'t edit it');
  }
  else{
     this._win = new popupWindow('content_win',{width:900,height: 500});
     this._win.open('ajax','m=sitemenu&action=edit_content&id='+value);
  }
}

//****/

menuBuilder.prototype.getContent = function(id, lang){ 
   var url = './index_ajax.php?m=content&action=get&id='+id+'&lang='+lang; 
   var response = '';
   var ajax= new Ajax.Request(url, {
          method:  'get',
          evalScripts:  true,
          onException: function(t,e){ 
                alert(e.message);
                alert('Failed to perform request. Check if ajax is supported');
          },
          onSuccess: function(request) {
              var data = eval("(" + request.responseText + ")");
              if(empty(data)){
                alert('Invalid request');
              }   
              else{

                 if(tinyMCE.get('content_text')) {
                   try{
                      tinyMCE.execCommand('mceRemoveControl', false,  'content_text');
                   }
                   catch(e){}
                   tinyMCE.editors['content_text'] = null; 
                 }
                 
                 $$('#content_langs a').each(function(el){el.style.fontWeight='normal'});
                 $('content_lang_'+data.lang).style.fontWeight='bold'; 

                 $('content_id').value   = data.id;
                 $('content_lang').value = data.lang;  
                 $('content_text').value = data.text;
                 
                 $('edit_content_div').show();
            
                 tinyMCE.execCommand('mceAddControl', false, 'content_text');               
              } 
         }
      }); 
}

//****/

menuBuilder.prototype.createNewContent = function(){
   var url  = './index_ajax.php?m=content'; 
   var response = '';
   var _this = this;
      
   var ajax= new Ajax.Request(url, {
          method:  'post',
          parameters:   Form.serialize('content_form'), 
          evalScripts:  true,
          onException: function(){ 
                alert('Failed to perform request. Check if ajax is supported');
          },
          onSuccess: function(request) { 
               var answer = eval("(" + request.responseText + ")"); 

               if(answer.result==0){
                  alert(answer.errors);
               }
               else{
                   var data = answer.data;
                  _this.addOption(_this.activeForm(),data.rec_id,data.title);
                  _this.addOption('group_content',data.rec_id, data.title);
                  _this._win.close(); 
               }
          }
          
      }); 
}

/***/

menuBuilder.prototype.saveContent = function(){
   var url  = './index_ajax.php?m=content'; 
   var response = '';
   var _this = this; 
   
   if(tinyMCE.get('content_text')){
     var ed = tinyMCE.editors['content_text'];
     $('content_text').value = ed.getContent(); 
   }
   
   var ajax= new Ajax.Request(url, {
          method:  'post',
          parameters:  Form.serialize('content_editform'), 
          evalScripts: true,
          onException: function(){ 
                alert('Failed to perform request. Check if ajax is supported');
          },
          onSuccess: function(request) { 
               var answer = eval("(" + request.responseText + ")"); 

               if(answer.result==0){
                  alert("Failed to save data");
               }
               else{
                  alert("Data successfully saved"); 
               }
          }  
      });
}


/****/

menuBuilder.prototype.addOption = function(box_id,value,text){
   var o = document.createElement('option');
   o.setAttribute('value',value);
   o.appendChild(document.createTextNode(text));   
   $(box_id).appendChild(o); 
}

//****/

menuBuilder.prototype.closeContentWin = function(){
   try{
     this._win.close();
   }
   catch(e){
     return; 
   }
}


function tinymceLoad(wysiwyg_id){
   if(tinyMCE.get(wysiwyg_id)) {
     tinyMCE.editors[wysiwyg_id] = null;
   }
   tinyMCE.execCommand('mceAddControl', false, wysiwyg_id);    
}

function empty(obj){ 
  for (var i in obj) { 
      return false;
  }
  return true;
}