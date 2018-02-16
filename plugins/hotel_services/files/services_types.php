<?

### ADD PICTURE
if($_POST['action']=="add"){
	$POST = $VALIDATOR->ConvertSpecialChars($_POST);
    $type=(isset($POST['service_type']))?$POST['service_type']:'default';
	if(!$errors) {
		$query = "INSERT INTO {$_CONF['db']['prefix']}_room_services_types SET
				  title='".serialize($POST['title'])."'";
		$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$FUNC->Redirect($SELF);
	}  
       
	### If ERRORS
	else{
		$TMPL->addVar('TMPL_errors',$errors);
		$TMPL->addVar('TMPL_title',$POST['title']);
		$TMPL->addVar('TMPL_services',$POST['services']);
		$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
		$TMPL->ParseIntoVar($_CENTER,"services_types");
	}
}
### EDIT PICTURE
elseif($_POST['action']=="edit"){
	$POST = $VALIDATOR->ConvertSpecialChars($_POST);
	
	### No errors - save changes
	if(!$errors) {
		$POST['pid']=(int)$_GET['pid'];
		$query = "UPDATE {$_CONF['db']['prefix']}_room_services_types SET
				  title ='".serialize($POST['title'])."'
				  WHERE id='{$POST['pid']}'";
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
		$TMPL->ParseIntoVar($_CENTER,"services_types");
    }

}
### Delete PICTURE
elseif($_GET['action']=='delete'){
	$pid   = StringConvertor::toNatural($_GET['pid']);
	$query = "DELETE FROM {$_CONF['db']['prefix']}_room_services_types WHERE id='{$pid}'";
	$CONN->_query($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$FUNC->Redirect($SELF);
}
elseif($_GET['action']=='edit') {
	$pid   = StringConvertor::toNatural($_GET['pid']);
	$query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services_types WHERE id='{$pid}'";
	$result = $CONN->Execute($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data = $result->fields;
	
	$TMPL->addVar('TMPL_id',$data['id']);
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
	$TMPL->addVar('TMPL_title',$FUNC->unpackData($data['title']));
	$TMPL->addVar('TMPL_price',$data['price']);
	if($LOADED_PLUGIN['restricted']&&$_SESSION['id']!=$data['creator'])
		$TMPL->ParseIntoVar($_CENTER,"services_types");
	else {
		$TMPL->ParseIntoVar($_CENTER,"services_types");
	}
}
elseif($_GET['action']=="change_status" && isset($_GET['pid'])) {
	$pid=(int)$_GET['pid'];
    $query="UPDATE {$_CONF['db']['prefix']}_room_services_types SET publish=if(publish=1,0,1) WHERE id=".$pid;
    $res=$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	    
    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
}elseif($_GET['action']=="change_visibility" && isset($_GET['pid'])) {
	$pid=(int)$_GET['pid'];
    $query="UPDATE {$_CONF['db']['prefix']}_room_services_types SET in_use=if(in_use=1,0,1) WHERE id=".$pid;
    $res=$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	    
    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
### DEFAULT
else {

    $TMPL->addVar('TMPL_service_types',GetServicesTypes());
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
	$TMPL->ParseIntoVar($_CENTER,"services_types");
}

### List of added pictures

 $TMPL->addVar('TMPL_data',GetServicesTypes());
 $TMPL->addVar("TMPL_navbar", $FUNC->DrawPageBar($SELF."&p=",$result));
 $TMPL->ParseIntoVar($_CENTER,"services_types_list");