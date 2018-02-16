function catManager(){
  var textAdd  = '';
  var textEdit = '';
  var plugin   = ''; 
}


catManager.prototype.showAddForm = function(parent){
	document.getElementById('addcat_form').reset();
	document.getElementById('addcat_err').innerHTML ="";
	document.getElementById('cid').value = parent;
	document.getElementById('addcat_button').value = this.textAdd;
	document.getElementById('action').value = "addcat";
	try { document.getElementById('imagePreview').innerHTML = "";  }
	catch (e) {};
	try { document.getElementById('imagePreview2').innerHTML = ""; }
	catch (e) {};
//	changes GR
//	document.getElementById('addcat_div').style.display='block';
	dd.elements.addcat_div.show();
	document.getElementById('addcat_div').focus();
}

catManager.prototype.hide = function(){
 document.getElementById('addcat_div').style.display='none';
 document.getElementById('addcat_form').reset();
 
}

catManager.prototype.showEditForm = function(cid){
  var ajax = new httpAjaxRequest();
  //*** Try to use AJAX first  ***********************//
  if(ajax){
		document.getElementById('addcat_form').reset();
		document.getElementById('addcat_err').innerHTML ="";
		document.getElementById('cid').value  = cid;
		document.getElementById('addcat_button').value = this.textEdit;
		document.getElementById('action').value = "editcat";

		ajax.prepareHash({id:cid});
		ajax.open("GET", "index_ajax.php?m="+this.plugin+"&action=editcat&cid="+cid);
		ajax.send();
		ajax.getData();
		ajax.onreadystatechange = function(){
			data =ajax.getResult();
            
			for(tag in data) { 
				if(document.getElementById("name["+tag+"]"))
				document.getElementById("name["+tag+"]").value = data[tag];
			} 
		
			if(data.image){
                  document.getElementById('image').value = data.image;
                  document.getElementById('imagePreview').innerHTML = '<img id="previewImg" src="' + data.image + '" border="0" />';
			}
			else{ document.getElementById('imagePreview').innerHTML = ""; }
			
			if(data.image2){
                  document.getElementById('image2').value = data.image2;
                  document.getElementById('imagePreview2').innerHTML = '<img id="previewImg" src="' + data.image2 + '" border="0" />';
                  //this.showPreviewImage_cat(data.image2,'imagePreview2');
			}
			else{ document.getElementById('imagePreview2').innerHTML = ""; }
			
			try { document.getElementById('mid_select').innerHTML = data.mid;
			}
			catch (e) {  }
			
		}  
        
		// changes GR
        //document.getElementById('addcat_div').style.display='block';
        dd.elements.addcat_div.show();
     }
     
   //*** If stupid browser does not support AJAX :( ***//  
   else{  
        window.location.href = "index.php?m="+this.plugin+"&categories&action=editcat&cid="+cid; 
     }          
}

catManager.prototype.showPreviewImage_cat = function(src, dest) {
	var elm = document.getElementById(dest);

	if (src == "")
		elm.innerHTML = "";
	else
		elm.innerHTML = '<img id="previewImg" src="' + src + '" border="0" />';
}


catManager.prototype.action = function(){

  //*** Action is 'add'  ***********************************//
  if(document.getElementById('action').value == 'addcat')
     document.getElementById('addcat_form').submit(); 
  //*** Action is 'edit' ***********************************//    
  else{
        var ajax = new httpAjaxRequest(); 
        //*** Try to use AJAX first  ***********************//
        if(ajax)
          {  
             ajax.prepareForm('addcat_form'); 
             ajax.open("POST", "index_ajax.php?m="+this.plugin+"&categories");
             ajax.send();
             ajax.getData();
             ajax.onreadystatechange = function(){
                   data =ajax.getResult();
                   
                   document.getElementById("result").innerHTML =data.calendar; 
                 }  
          }
        //*** if stupid browser does not support AJAX :( ***//    
        else    
          document.getElementById('addcat_form').submit();
    }
}

/*
// Only in FUTURE
catManager.prototype.UpdateCategory = function(){
	var ajax = new httpAjaxRequest();
	
	### Try to use AJAX first
	if(ajax){
        ajax.prepareForm('addcat_form');
        ajax.open("POST", "index_ajax.php?m="+this.plugin);
        ajax.send();
        ajax.getData();
        ajax.onreadystatechange = function(){
             data =ajax.getResult();
             if (data.reply == '1') {
             	 alert('Saved');
             }
        }
     }
	### If stupid browser does not support AJAX :(  
	else {
		document.getElementById('addimg_form').submit();
	}          
}
*/