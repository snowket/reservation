function showEditImgForm(cid){
	var ajax = new httpAjaxRequest();
	//*** Try to use AJAX first  ***********************//
  if(ajax){
        document.getElementById('addimg_form').reset();
        document.getElementById('addimg_err').innerHTML ="";
        document.getElementById('addimg_button').value = 'Edit';
        document.getElementById('cid_image').value = cid;
        
        ajax.prepareHash({id:cid});
        ajax.open("GET", "index_ajax.php?m=products&tab=categories&action=editcat&cid="+cid);
        ajax.send();
        ajax.getData();
        ajax.onreadystatechange = function(){
            data =ajax.getResult();
            if (data.image) { 
                  document.getElementById('image').value = data.image;
                  showPreviewImage_cat(data.image,'imagePreview');
			}
			else {
				showPreviewImage_cat('','imagePreview');
			}
			
            if (data.image2) { 
                  document.getElementById('image2').value = data.image2;
                  showPreviewImage_cat(data.image2,'imagePreview2');
			}
			else {
				showPreviewImage_cat('','imagePreview2');
			}
		}  
        
		// changes GR
        //document.getElementById('addcat_div').style.display='block';
        dd.elements.addimg_div.show();
     }
     
   //*** If stupid browser does not support AJAX :( ***//  
   else {  
        window.location.href = "index.php?m="+this.plugin+"&categories&action=editcat&cid="+cid; 
     }          
}

function showPreviewImage_cat(src, dest) {
	var elm = document.getElementById(dest);

	if (src == "")
		elm.innerHTML = "";
	else
		elm.innerHTML = '<img id="previewImg" src="' + src + '" border="0" />'
}

function UpdateCatImage(){
	var ajax = new httpAjaxRequest();
	
	//*** Try to use AJAX first  ***********************//
	if(ajax){
		cid		= document.getElementById('cid_image').value;
		image	= document.getElementById('image').value;
		image2	= document.getElementById('image2').value;
        
        ajax.prepareHash({cid:cid,image:image,image2:image2,action:'edit_image'});
        ajax.open("POST", "index_ajax.php?m=products");
        ajax.send();
        ajax.getData();
        ajax.onreadystatechange = function(){
             data =ajax.getResult();
             if (data.reply == '1') {
             	 alert('Saved');
             }
        }
     }
	//*** If stupid browser does not support AJAX :( ***//  
	else {
		document.getElementById('addimg_form').submit();
	}          
}


/////////////////////////////////////////////////
// OPTIONS
/////////////////////////////////////////////////
function showEditOptForm(cid){
	var ajax = new httpAjaxRequest();
	//*** Try to use AJAX first  ***********************//
  if(ajax){
        document.getElementById('addopt_form').reset();
        document.getElementById('addopt_err').innerHTML ="";
        document.getElementById('cid_options').value = cid;
        
        ajax.prepareHash({id:cid});
        ajax.open("GET", "index_ajax.php?m=products&tab=categories&action=editcat&cid="+cid);
        ajax.send();
        ajax.getData();
        ajax.onreadystatechange = function(){
             data =ajax.getResult();  
             if (data.option) {
             	  form = document.getElementById('options_id');
             	  for(i=0;i<form.options.length;i++){
             	      if(form.options[i].value == data.option){
             	      	form.options[i].selected = true;
             	      	break; 	
             	      }
             	    }
                  
			}
		}  
        
		// changes GR
        //document.getElementById('addcat_div').style.display='block';
        dd.elements.addopt_div.show();
     }
     
   //*** If stupid browser does not support AJAX :( ***//  
   else {  
        window.location.href = "index.php?m="+this.plugin+"&categories&action=editcat&cid="+cid; 
     }          
}

function UpdateOpt(){
	var ajax = new httpAjaxRequest();
	
	//*** Try to use AJAX first  ***********************//
	if(ajax){
		cid			= document.getElementById('cid_options').value;
		options_id	= document.getElementById('options_id').value;
        
        ajax.prepareHash({cid:cid,options:options_id,action:'edit_options'});
        ajax.open("POST", "index_ajax.php?m=products");
        ajax.send();
        ajax.getData();
        ajax.onreadystatechange = function(){
             data =ajax.getResult();
             if (data.reply == '1') {
             	 alert('Saved');
             }
        }
     }
	//*** If stupid browser does not support AJAX :( ***//  
	else {
		document.getElementById('addopt_form').submit();
	}          
}