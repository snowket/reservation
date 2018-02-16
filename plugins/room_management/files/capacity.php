<?

$query = "SELECT * FROM {$_CONF['db']['prefix']}_room_types ORDER BY id DESC";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());


 while($o = $result->FetchNextObject()){
     $caps_name[]  = array(
             'id'      	  =>  $o->ID,
             'title'   	  =>  $FUNC->unpackData($o->TITLE,'eng'),
         	 'publish'    =>  $o->PUBLISH,
             'blocked' 	  =>  ($LOADED_PLUGIN['restricted']&&$_SESSION['id']!=$o->CREATOR)?true:false
       );

 }
$TMPL->addVar('caps_name',$caps_name);
### ADD PICTURE
if($_POST['action']=="add"){
	$POST = $VALIDATOR->ConvertSpecialChars($_POST);
	### Image UPLOADED? - ADD TO DB
	if(!$errors) {
		$query = "INSERT INTO {$_CONF['db']['prefix']}_room_capacity SET
				  title='".serialize($POST['title'])."',
				  childrens='".$POST['childrens']."',
				  capacity='".$POST['capacity']."'
				  ";
		$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$FUNC->Redirect($SELF);
	}

	### If ERRORS
	else{
		$TMPL->addVar('TMPL_errors',$errors);
		$TMPL->addVar('TMPL_title',$POST['title']);
		$TMPL->addVar('TMPL_capacity',$POST['capacity']);
		$TMPL->addVar('TMPL_childrens',$POST['childrens']);
		$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
		$TMPL->ParseIntoVar($_CENTER,"capacity");
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
		$query = "UPDATE {$_CONF['db']['prefix']}_room_capacity SET
				  title ='".serialize($POST['title'])."',
				  	  childrens='".$POST['childrens']."',
				  capacity='".$POST['capacity']."'
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
		$TMPL->addVar('TMPL_capacity',$POST['capacity']);
		$TMPL->addVar('TMPL_childrens',$POST['childrens']);
		$TMPL->ParseIntoVar($_CENTER,"capacity");
    }

}
### Delete PICTURE
elseif($_GET['action']=='delete'){
	$pid   = StringConvertor::toNatural($_GET['pid']);

	$query = "DELETE FROM {$_CONF['db']['prefix']}_room_capacity WHERE id='{$pid}'";
	$CONN->_query($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$FUNC->Redirect($SELF);
}
elseif($_GET['action']=='edit') {
	$pid   = StringConvertor::toNatural($_GET['pid']);
	$query = "SELECT * FROM {$_CONF['db']['prefix']}_room_capacity WHERE id='{$pid}'";
	$result = $CONN->Execute($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data = $result->fields;

	$TMPL->addVar('TMPL_id',$data['id']);
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
	$TMPL->addVar('TMPL_title',$FUNC->unpackData($data['title']));
	$TMPL->addVar('TMPL_capacity',$data['capacity']);
	$TMPL->addVar('TMPL_childrens',$data['childrens']);
	if($LOADED_PLUGIN['restricted']&&$_SESSION['id']!=$data['creator'])
		$TMPL->ParseIntoVar($_CENTER,"capacity");
	else {
		$TMPL->ParseIntoVar($_CENTER,"capacity");
	}
}
elseif($_GET['action']=="change_status" && isset($_GET['pid'])) {
	$pid=(int)$_GET['pid'];
    $query="UPDATE {$_CONF['db']['prefix']}_room_capacity SET publish=if(publish=1,0,1) WHERE id=".$pid;
    $res=$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());

    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
### DEFAULT
else {


	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
	$TMPL->ParseIntoVar($_CENTER,"capacity");
}


### List of added pictures
$query = "SELECT * FROM {$_CONF['db']['prefix']}_room_capacity ORDER BY id DESC";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());


 while($o = $result->FetchNextObject()){
     $TMPL_data[]  = array(
             'id'      	  =>  $o->ID,
             'title'   	  =>  $FUNC->unpackData($o->TITLE,'eng'),
         	 'capacity'   =>  $o->CAPACITY,
             'childrens'  =>  $o->CHILDRENS,
         	 'publish'    =>  $o->PUBLISH,
             'blocked' 	  =>  ($LOADED_PLUGIN['restricted']&&$_SESSION['id']!=$o->CREATOR)?true:false
       );

 }

 $TMPL->addVar('TMPL_data',$TMPL_data);
 $TMPL->addVar("TMPL_navbar", $FUNC->DrawPageBar($SELF."&p=",$result));
 $TMPL->ParseIntoVar($_CENTER,"capacity_list");
