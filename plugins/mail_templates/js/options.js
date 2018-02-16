function add2form(form){
  if(form.options[form.selectedIndex].value=='2'){
      document.getElementById('add2form').style.display = 'block';
    }
  else{
      document.getElementById('add2form').style.display = 'none';
    }  
}


function warnCatChange(text){
  if(document.getElementById('old_cat_id').value!=0){
     if(document.getElementById('old_cat_id').value!=document.getElementById('cat_id').value)
        return confirm(text);
    }
  return true;
}

function warnCatChange_return(text,active_value){
	if (!confirm(text)) {
		form = document.getElementById('cat_id');
		for(i=0;i<form.options.length;i++){
			if(form.options[i].value == active_value){
				form.options[i].selected = true;
				break;
			}
		}
	}
}