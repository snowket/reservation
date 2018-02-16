<?
require_once("./classes/dbtree/dbtree.class.php");
$dbtree = new dbtree($_CONF['db']['prefix'].'_categories', $CONN);
$TMPL->setRoot('.');

//***************************************************************//
//*** Adding/Editing news categories ****************************//
if($_POST['action']=="addcat"||$_POST['action']=="editcat"){ 
	$cid    = StringConvertor::toNatural($_POST['cid']);
	$POST = $VALIDATOR->ConvertSpecialChars($_POST);
	for($i=0;$i<count($_CONF['langs_all']);$i++)
		$VALIDATOR->ValidateLength($POST['name'][$_CONF['langs_all'][$i]],"{$TEXT['sitemenu']['mtitle']}({$_CONF['langs_all'][$i]})",3);
	$errors =$VALIDATOR->PassErrors(); 
	if(empty($errors)) {
		$name   = serialize($POST['name']);
		//*** Adding new category  **********************// 
		if($_POST['action']=="addcat") {
			$data = array(
						'pid'		=> $LOADED_PLUGIN['id'],
						'mid'		=> isset($_POST['mid']) ? serialize($_POST['mid']) : "",
						'name'		=> $name,
						'image'		=> $POST['image'],
						'image2'	=> $POST['image2'],
						'creator'	=> $_SESSION['pcms_user_id']
				);
			$parent_id = StringConvertor::toNatural($_POST['cid']);
			$id = $dbtree->Insert($parent_id,  array(''=>array('')), $data);
			if(!empty($dbtree->ERRORS_MES)) $FUNC->ServerError(__FILE__,__LINE__,implode("",$dbtree->ERRORS_MES));
		}
		//*** Editing existing category *****************//
		elseif($_POST['action']=="editcat") {
			$query = "UPDATE {$_CONF['db']['prefix']}_categories SET 
							name='{$name}', 
							image='{$POST['image']}',
							". (isset($_POST['mid'])?"mid = '".serialize($_POST['mid'])."', ":"")."
							image2='{$POST['image2']}'
					  WHERE cat_id='{$cid}' AND pid='{$LOADED_PLUGIN['id']}'";
			$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		}
		$FUNC->Redirect($SELF."&tab=categories");
	}
	//*****************************************************// 
	//*** If errors are found *****************************//
	else{
		$TMPL->addVar("TMPL_errors",$errors);
		$TMPL->addVar("TMPL_name",$POST['name']);
		$TMPL->addVar("TMPL_cid",$cid);
		$TMPL->addVar("TMPL_button",$_POST['action']=="edit"?$TEXT['global']['edit']:$TEXT['global']['add']);
		$TMPL->addVar("TMPL_action",$_POST['action']);       
		$TMPL->addVar("TMPL_display","block");
	}  
}

//**************************************************************//
//*** Deleting a category  *************************************//
elseif($_GET['action']=="delete" && isset($_GET['cid'])) {
   $cat_id = StringConvertor::toNatural($_GET['cid']);
   $dbtree->DeleteAll($cat_id,array('and' => array("pid = '{$LOADED_PLUGIN['id']}'")));
   if(!empty($dbtree->ERRORS_MES)) $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());  
   $FUNC->Redirect($SELF."&tab=categories");
}
//**************************************************************//
//*** Moving category up one level  ****************************//
elseif($_GET['action']=="up" && isset($_GET['cid'])) {
	$cat_id = StringConvertor::toNatural($_GET['cid']);
	$dbtree->Move($cat_id,'before',array("and"=>array("pid='{$LOADED_PLUGIN['id']}'")));
	if(!empty($dbtree->ERRORS)) $FUNC->ServerError(__FILE__,__LINE__,$dbtree->ErrorMsg());                      
	$FUNC->Redirect($SELF."&tab=categories");
}

//******************************************************************//
//*** Moving category down one level  ******************************//
elseif ($_GET['action']=="down" && isset($_GET['cid'])) {
	$cat_id = StringConvertor::toNatural($_GET['cid']);
	$dbtree->Move($cat_id,'after',array("and"=>array("pid='{$LOADED_PLUGIN['id']}'"))); 
	if(!empty($dbtree->ERRORS)) $FUNC->ServerError(__FILE__,__LINE__,$dbtree->ErrorMsg());                   
	$FUNC->Redirect($SELF."&tab=categories");         
}
//*******************************************************************//
//*** Changing category status  *************************************//
elseif($_GET['action']=="change_status"&&isset($_GET['cid'])){
	$cat_id = StringConvertor::toNatural($_GET['cid']);
	$query  = "update {$_CONF['db']['prefix']}_categories set publish=if(publish=1,0,1) 
			   where cat_id=".$cat_id;
	$res=$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());  
	$FUNC->Redirect($SELF."&tab=categories");
}
//*******************************************************************//
//*** Editing a category- printing form for editing *****************// 
//*** This code is used only if stupid browser doesn't support AJAX *//
elseif($_GET['action']=="editcat"){
	$cid   = StringConvertor::toNatural($_GET['cid']);
	$query  = "select* from {$_CONF['db']['prefix']}_categories where cat_id='$cid'";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data = $result->fields;
	$TMPL->addVar("TMPL_name",$FUNC->unpackData($data['name']));
	$TMPL->addVar('TMPL_button',$TEXT['global']['edit']);
	$TMPL->addVar('TMPL_cid',$cid);
	$TMPL->addVar('TMPL_action','editcat'); 
	$TMPL->addVar('TMPL_display','block'); 
}
else{
	$TMPL->addVar("TMPL_display","none");
}   

//******************************************************************//
//*** Default output ***********************************************//   
$dbtree->Full(array("cat_id","cat_level","name","publish","creator"),array("and"=>array("pid='{$LOADED_PLUGIN['id']}'")));
if(!empty($dbtree->ERRORS_MES)) $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg()); 
$l = $FUNC->validLang(LANG,'langs');
while($item = $dbtree->NextRow()){ 

  $item['name']   = $FUNC->unpackData($item['name'],$l);
  $item['spacer'] = str_repeat('&nbsp;', 6 *$item['cat_level']); 
  if($item['cat_level']==1)
    $item['name']   = "<b>".$item['name']."</b>";
  $items[] = $item; 
} 

$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
$TMPL->addVar('TMPL_cat',$items);
$TMPL->ParseIntoVar($_CENTER,"categories");  

?>