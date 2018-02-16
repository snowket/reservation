<?

### ADD PICTURE
if($_POST['action']=="add"){
	$POST = $VALIDATOR->ConvertSpecialChars($_POST); 

	### Image UPLOADED? - ADD TO DB
	if(!$errors) {
		$query = "INSERT INTO {$_CONF['db']['prefix']}_room_services SET
				  title='".serialize($POST['title'])."',
				  price='".$POST['price']."',
				  type_id=".$POST['service_type'];
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
				  price='".$POST['price']."',
                  type_id='".$POST['service_type']."'
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
	$data = $result->GetRows();
    $data[0]['title']=$FUNC->unpackData($data[0]['title']);
	//$data[0]['title']=$FUNC->unpackData($TMPL_service['title'],'eng');
	$TMPL->addVar('TMPL_service',$data[0]);
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
    $TMPL->addVar('TMPL_services_types',GetServicesTypes());
    $TMPL->ParseIntoVar($_CENTER,"services");
}
elseif($_GET['action']=="change_status" && isset($_GET['pid'])) {
	$pid=(int)$_GET['pid'];
    $query="UPDATE {$_CONF['db']['prefix']}_room_services SET publish=if(publish=1,0,1) WHERE id=".$pid;
    $res=$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	    
    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
elseif($_GET['action']=="change_visibility" && isset($_GET['pid'])) {
	$pid=(int)$_GET['pid'];
    $query="UPDATE {$_CONF['db']['prefix']}_room_services SET in_use=if(in_use=1,0,1) WHERE id=".$pid;
    $res=$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	    
    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
### DEFAULT
else {

    $TMPL->addVar('TMPL_services_types',GetServicesTypes());
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
	$TMPL->ParseIntoVar($_CENTER,"services");
}

$TMPL->addVar('TMPL_services_types',GetServicesTypes());
 $TMPL->addVar('TMPL_data',GetServices());
 $TMPL->addVar("TMPL_navbar", $FUNC->DrawPageBar($SELF."&p=",$result));
 $TMPL->ParseIntoVar($_CENTER,"services_list"); 