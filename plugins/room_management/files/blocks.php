<?

### ADD PICTURE
if($_POST['action']=="add")
{
	$POST = $VALIDATOR->ConvertSpecialChars($_POST);


	### Image UPLOADED? - ADD TO DB
	if(!$errors) {
		$query = "INSERT INTO {$_CONF['db']['prefix']}_hotel_blocks SET
				  title='".serialize($POST['title'])."',
				  floors='".$POST['floors']."'
				  ";
		$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$id = $CONN->Insert_ID();


		for ($i=1; $i < $POST['floors']+1; $i++) {
			$query = "INSERT INTO {$_CONF['db']['prefix']}_block_manager SET
				  block_id='".$id."',
				  floor='".$i."'
				  ";
			$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		}

		$FUNC->Redirect($SELF.'&action=edit&pid='.$id);
	}

	### If ERRORS
	else{
		$TMPL->addVar('TMPL_errors',$errors);
		$TMPL->addVar('TMPL_title',$POST['title']);
		$TMPL->addVar('TMPL_floor',$POST['floor']);
		$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
		$TMPL->ParseIntoVar($_CENTER,"blocks");
	}
}
### EDIT PICTURE
elseif($_POST['action']=="edit")
{
	$POST = $VALIDATOR->ConvertSpecialChars($_POST);

	### No errors - save changes
	if(!$errors) {
		if($LOADED_PLUGIN['restricted'])
			$strict_query =" and creator='{$_SESSION['id']}'";

		$POST['pid']=(int)$_GET['pid'];
		$qs="SELECT * FROM {$_CONF['db']['prefix']}_hotel_blocks WHERE id=".$POST['pid'];
		$res=$CONN->Execute($qs) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$res=$res->fields;

		$query = "UPDATE {$_CONF['db']['prefix']}_hotel_blocks SET
				  title ='".serialize($POST['title'])."',floors='".$POST['floors']."'
				  WHERE id='{$POST['pid']}' {$strict_query}";
		$CONN->_query($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		if($res['floors']<$POST['floors']){
			for ($i=$POST['floors']; $i > $res['floors']; $i--) {
				$query = "INSERT INTO {$_CONF['db']['prefix']}_block_manager SET
						block_id='".$res['id']."',rooms=0,
						floor='".$i."'
						";
				$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
			}
		}
		$FUNC->Redirect($SELF);
	}
	### If ERRORS
	else {
		$TMPL->addVar('TMPL_errors',$errors);
		$TMPL->addVar('TMPL_id',$POST['pid']);
		$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
		$TMPL->addVar('TMPL_title',$POST['title']);
		$TMPL->addVar('TMPL_floors',$POST['floors']);
		$TMPL->ParseIntoVar($_CENTER,"blocks");
    }

}
### Delete PICTURE
elseif($_GET['action']=='delete')
{
	die('Contact Support Team To delete Block');
	$pid   = StringConvertor::toNatural($_GET['pid']);

	$query = "DELETE FROM {$_CONF['db']['prefix']}_hotel_blocks WHERE id='{$pid}'";
	$CONN->_query($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$FUNC->Redirect($SELF);
}
elseif($_GET['action']=='edit')
{
	$pid   = StringConvertor::toNatural($_GET['pid']);
	$query = "SELECT * FROM {$_CONF['db']['prefix']}_hotel_blocks WHERE id='{$pid}'";
	$result = $CONN->Execute($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data = $result->fields;

	$query = "SELECT * FROM {$_CONF['db']['prefix']}_block_manager
			  WHERE block_id='{$pid}' order by floor DESC";
	$result = $CONN->Execute($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$blocks = $result->GetRows();

	$TMPL->addVar('TMPL_item',$data);
	$TMPL->addVar('blocks',$blocks);
	$TMPL->addVar('TMPL_id',$data['id']);
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
	$TMPL->addVar('TMPL_title',$FUNC->unpackData($data['title']));
	$TMPL->addVar('TMPL_floors',$data['floors']);
	if($LOADED_PLUGIN['restricted']&&$_SESSION['id']!=$data['creator'])
		$TMPL->ParseIntoVar($_CENTER,"blocks");
	else {
		$TMPL->ParseIntoVar($_CENTER,"blocks");
	}
}
elseif($_POST['action']=="save")
{
	foreach ($_POST['rooms'] as $key => $value) {
		$rooms=(int)$value;
		if ($rooms==0)
		{
			continue;
		}
		$query = "UPDATE {$_CONF['db']['prefix']}_block_manager SET
				  rooms ='".$rooms."'
				  WHERE id=$key";
		$CONN->_query($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());

	}
	$FUNC->Redirect($SELF.'&action=edit&pid='.$_POST['block_id']);
}
elseif($_GET['action']=="change_status" && isset($_GET['pid'])) {
	$pid=(int)$_GET['pid'];
    $query="UPDATE {$_CONF['db']['prefix']}_hotel_blocks SET publish=if(publish=1,0,1) WHERE id=".$pid;
    $res=$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());

    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
### DEFAULT
else
{
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
	$TMPL->ParseIntoVar($_CENTER,"blocks");
}


### List of added pictures
$query = "SELECT * FROM {$_CONF['db']['prefix']}_hotel_blocks ORDER BY id DESC";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());

 while($o = $result->FetchNextObject()){
     $TMPL_data[]  = array(
             'id'      	  =>  $o->ID,
             'title'   	  =>  $FUNC->unpackData($o->TITLE,LANG),
         	 'floors'     =>  $o->FLOORS,
         	 'publish'    =>  $o->PUBLISH,
             'blocked' 	  =>  ($LOADED_PLUGIN['restricted']&&$_SESSION['id']!=$o->CREATOR)?true:false
       );

 }

 $TMPL->addVar('TMPL_data',$TMPL_data);
 $TMPL->addVar("TMPL_navbar", $FUNC->DrawPageBar($SELF."&p=",$result));
 $TMPL->ParseIntoVar($_CENTER,"blocks_list");
