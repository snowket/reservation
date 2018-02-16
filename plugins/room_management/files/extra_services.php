<?

### ADD PICTURE
if($_POST['action']=="add"){
	$POST = $VALIDATOR->ConvertSpecialChars($_POST);
    $type=(isset($POST['service_type']))?$POST['service_type']:'default';
	if(!$errors) {
		$query = "INSERT INTO {$_CONF['db']['prefix']}_room_services SET
				  title='".serialize($POST['title'])."',
				  price='".$POST['price']."',
				  type='".$type."'
				  ";
		$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$FUNC->Redirect($SELF);
	}  
       
	### If ERRORS
	else{
		$TMPL->addVar('TMPL_errors',$errors);
		$TMPL->addVar('TMPL_title',$POST['title']);
		$TMPL->addVar('TMPL_services',$POST['services']);
		$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
		$TMPL->ParseIntoVar($_CENTER,"services");
	}
}
### EDIT PICTURE
elseif($_POST['action']=="edit"){
	$POST = $VALIDATOR->ConvertSpecialChars($_POST);
	
	### No errors - save changes
	if(!$errors) {
		if($LOADED_PLUGIN['restricted'])
			$strict_query =" and creator='{$_SESSION['id']}'";
		
		$POST['pid']=(int)$_GET['pid'];
		$query = "UPDATE {$_CONF['db']['prefix']}_room_services SET 
				  title ='".serialize($POST['title'])."',
				  price='".$POST['price']."' 
				  WHERE id='{$POST['pid']}' {$strict_query}";
		$CONN->_query($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());   
		$FUNC->Redirect($SELF);      
	}  
	### If ERRORS  
	else {
		$TMPL->addVar('TMPL_errors',$errors);
		$TMPL->addVar('TMPL_id',$POST['pid']);
		$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
		$TMPL->addVar('TMPL_title',$POST['title']);
		$TMPL->addVar('TMPL_price',$POST['price']);
		$TMPL->ParseIntoVar($_CENTER,"services");
    }

}
### Delete PICTURE
elseif($_GET['action']=='delete'){
	$pid   = StringConvertor::toNatural($_GET['pid']);
	  
	$query = "DELETE FROM {$_CONF['db']['prefix']}_room_services WHERE id='{$pid}'";
	$CONN->_query($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$FUNC->Redirect($SELF);
}
elseif($_GET['action']=='edit') {
	$pid   = StringConvertor::toNatural($_GET['pid']);
	$query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services WHERE id='{$pid}'";
	$result = $CONN->Execute($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data = $result->fields;
	
	$TMPL->addVar('TMPL_id',$data['id']);
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
	$TMPL->addVar('TMPL_title',$FUNC->unpackData($data['title']));
	$TMPL->addVar('TMPL_price',$data['price']);
	if($LOADED_PLUGIN['restricted']&&$_SESSION['id']!=$data['creator'])
		$TMPL->ParseIntoVar($_CENTER,"services");
	else {
		$TMPL->ParseIntoVar($_CENTER,"services");     
	}
}
elseif($_GET['action']=="change_status" && isset($_GET['pid'])) {
	$pid=(int)$_GET['pid'];
    $query="UPDATE {$_CONF['db']['prefix']}_room_services SET publish=if(publish=1,0,1) WHERE id=".$pid;
    $res=$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	    
    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
}elseif($_GET['action']=="change_visibility" && isset($_GET['pid'])) {
	$pid=(int)$_GET['pid'];
    $query="UPDATE {$_CONF['db']['prefix']}_room_services SET in_use=if(in_use=1,0,1) WHERE id=".$pid;
    $res=$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	    
    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
### DEFAULT
else {

    $TMPL->addVar('TMPL_service_types',$TEXT['extra_service_types']);
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
	$TMPL->ParseIntoVar($_CENTER,"services");
}


foreach ($TEXT['extra_service_types'] as $k => $v) {
    $extra_services_arr[]= "'".$k."'";
}
$extra_services_arr=implode(", ",$extra_services_arr);
### List of added pictures
$query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services
		 WHERE type IN(".$extra_services_arr.") ORDER BY id DESC";

$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());   
   
   
 while($o = $result->FetchNextObject()){
     $TMPL_data[]  = array(
             'id'      	  =>  $o->ID,
             'title'   	  =>  $FUNC->unpackData($o->TITLE,'eng'),
         	 'price'   	  =>  $o->PRICE,
         	 'publish'    =>  $o->PUBLISH,
         	 'in_use'     =>  $o->IN_USE,
             'blocked' 	  =>  ($LOADED_PLUGIN['restricted']&&$_SESSION['id']!=$o->CREATOR)?true:false
       );
  
 }
 
 $TMPL->addVar('TMPL_data',$TMPL_data);
 $TMPL->addVar("TMPL_navbar", $FUNC->DrawPageBar($SELF."&p=",$result));
 $TMPL->ParseIntoVar($_CENTER,"services_list"); 