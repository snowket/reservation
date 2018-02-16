<?
//*******************************************************************//
//*******************************************************************//
//                  GALLERY PCMS PLUGIN                              // 
//*******************************************************************//
//*******************************************************************//
if(!defined('ALLOW_ACCESS')) exit;

$ROOT   = dirname(__FILE__);
$SELF   = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];
$imgDIR = $_CONF['path']['script_upload'].$LOADED_PLUGIN['plugin'];

require_once($ROOT."/lang/".LANG.".php");

$TMPL->setRoot($ROOT);
$TMPL->addVar("SELF", $SELF);
$TMPL->addVar("SETTINGS", $SETTINGS);


if($_GET['tab']=='categories'&&$SETTINGS['with_cats']==1){
	if(!$LOADED_PLUGIN['restricted'])
		require_once("./kernel/categories.php");
} 
else{

$l = $FUNC->validLang(LANG,'langs');

### ADD PICTURE
if($_POST['action']=="add"){
	$POST = $VALIDATOR->ConvertSpecialChars($_POST); 
/*	if($SETTINGS['with_cats']==1) {
		$VALIDATOR->validateString($_POST['cid'][0],'DIGIT',$TEXT['global']['category'],1);
		$cid = isset($_POST['cid']) ? serialize($_POST['cid']) : "";
		$query_cid = "cid = '{$cid}',";
	}
	*/
	if (!$_POST['mid'][0]) $VALIDATOR->validateString($_POST['idiot'],'TEXT','Menu ID',1);
	$mid = isset($_POST['mid']) ? serialize($_POST['mid']) : "";
	$query_mid = "mid = '{$mid}',";
	
	$errors = $VALIDATOR->passErrors();
	
	/*if($_FILES['image']['name'] && $_FILES['image']['size']) {
		if(!$errors) {
			$IMG = new imageGD($imgDIR);
			if($img = $IMG->uploadImage("image")) {
				### Make thumbnail
				if($SETTINGS['th_method']=='c')		$IMG->cropImage($img,$SETTINGS['th_width'],$SETTINGS['th_height'],85,$img);
				else								$IMG->resizeImage($img,$SETTINGS['th_width'],$SETTINGS['th_height'],85,false,$img);
                        
				### Resize picture 
			#	if($SETTINGS['img_method']=='c')	$IMG->cropImage($img,$SETTINGS['img_width'],$SETTINGS['img_height']);
			#	else								$IMG->resizeImage($img,$SETTINGS['img_width'],$SETTINGS['img_height']);
			}
			$errors =$IMG->passErrors();         
		}
	}*/
	if($_POST['image'])
	{
		$img=$_POST['image'];
	}
	else $errors .= $TEXT['gallery']['select_img'];      

	### Image UPLOADED? - ADD TO DB
	if(!$errors) {
		$query = "INSERT INTO {$_CONF['db']['prefix']}_banner_carusel SET {$query_mid}
				  title='".serialize($POST['title'])."', url='".($VALIDATOR->qstr($POST['url']))."', sort_id='".$POST['sort_id']."',
				  img='{$img}', creator='{$_SESSION['id']}'";
		$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$FUNC->Redirect($SELF);
	}  
       
	### If ERRORS
	else{
		$TMPL->addVar('TMPL_errors',$errors);
		$TMPL->addVar('TMPL_title',$POST['title']);
		$TMPL->addVar('TMPL_img',$POST['image']);
		$TMPL->addVar('TMPL_sort_id',$POST['sort_id']);
		$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
		$TMPL->addVar('TMPL_catopts',formCategories($POST['cid']));
		$TMPL->addVar('TMPL_menuopts',menu_formCategories($POST['mid']));
		$TMPL->ParseIntoVar($_CENTER,"addform");
	}
}
### EDIT PICTURE
elseif($_POST['action']=="edit") {
	$POST = $VALIDATOR->ConvertSpecialChars($_POST);
	if($SETTINGS['with_cats']==1) {
		$VALIDATOR->validateString($_POST['cid'][0],'DIGIT',$TEXT['global']['category'],1);
		$cid = isset($_POST['cid']) ? serialize($_POST['cid']) : "";
		$query_cid = "cid = '{$cid}',";
	}
	
	#if (!$_POST['mid'][0])
		#$VALIDATOR->validateString($_POST['idiot'],'TEXT','Menu ID',1);
	$mid = isset($_POST['mid']) ? serialize($_POST['mid']) : "";
	$query_mid = "mid = '{$mid}',";
	
	$errors = $VALIDATOR->passErrors();
	
	### No errors - save changes
	if(!$errors) {
		if($LOADED_PLUGIN['restricted'])
			$strict_query =" and creator='{$_SESSION['id']}'";
		$query = "UPDATE {$_CONF['db']['prefix']}_banner_carusel SET url='".($VALIDATOR->qstr($POST['url']))."',
				 {$query_mid}
				  title ='".serialize($POST['title'])."', sort_id='".$POST['sort_id']."' WHERE id='{$POST['pid']}' {$strict_query}";
		$CONN->_query($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());   
		$FUNC->Redirect($SELF);      
	}  
	### If ERRORS  
	else {
		$strict_query = '';
		if(isset($LOADED_PLUGIN['restricted'])&&!empty($LOADED_PLUGIN['restricted'])) {
			$strict_query =" and creator='{$_SESSION['id']}'"; 
		}
		$query = "SELECT img FROM {$_CONF['db']['prefix']}_banner_carusel WHERE id='{$POST['pid']}' {$strict_query}";
		$result = $CONN->Execute($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$data = $result->fields;
		$TMPL->addVar('TMPL_errors',$errors);
		$TMPL->addVar('TMPL_id',$POST['pid']);
		$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
		$TMPL->addVar('TMPL_sort_id',$POST['sort_id']);
		$TMPL->addVar('TMPL_menuopts',menu_formCategories($POST['mid']));
		$TMPL->addVar('TMPL_catopts',formCategories($POST['cid']));
		$TMPL->addVar('TMPL_img', $data['img']);
		$TMPL->addVar('TMPL_title',$POST['title']);
		$TMPL->ParseIntoVar($_CENTER,"editform_full");
    }

}
### Delete PICTURE
elseif($_GET['action']=='delete'){
	$pid   = StringConvertor::toNatural($_GET['pid']);
	if($LOADED_PLUGIN['restricted'])
		$strict_query =" and creator='{$_SESSION['id']}'"; 
	$query = "SELECT img from {$_CONF['db']['prefix']}_banner_carusel WHERE id='{$pid}' {$strict_query}";
	$result = $CONN->Execute($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data = $result->fields;
	if(count($data)==0)
		$FUNC->Redirect($SELF);
	@unlink($imgDIR."/".$data['img']);
	@unlink($imgDIR."/".$data['img']);  
	$query = "DELETE FROM {$_CONF['db']['prefix']}_banner_carusel WHERE id='{$pid}'";
	$CONN->_query($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$FUNC->Redirect($SELF);
}
### Edit picture
elseif($_GET['action']=='edit') {
	$pid   = StringConvertor::toNatural($_GET['pid']);
	$query = "SELECT * FROM {$_CONF['db']['prefix']}_banner_carusel WHERE id='{$pid}'"; 
	$result = $CONN->Execute($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data = $result->fields;
	$TMPL->addVar('TMPL_id',$data['id']);
	$TMPL->addVar('TMPL_sort_id',$data['sort_id']);
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
	$TMPL->addVar('TMPL_title',$FUNC->unpackData($data['title']));
	$TMPL->addVar('TMPL_url', $data['url']);
	$TMPL->addVar('TMPL_img', $data['img']);
	if($LOADED_PLUGIN['restricted']&&$_SESSION['id']!=$data['creator'])
		$TMPL->ParseIntoVar($_CENTER,"editform_restricted");
	else {
		$TMPL->addVar('TMPL_catopts',formCategories(unserialize($data['cid'])));
		$TMPL->addVar('TMPL_menuopts',menu_formCategories(unserialize($data['mid'])));
		$TMPL->addVar('TMPL_menuopts_fs',menu_formCategories_for_select(intval($_GET['mid'])));
		$TMPL->ParseIntoVar($_CENTER,"editform_full");     
	}
}
### DEFAULT
else {
	$TMPL->addVar('TMPL_catopts', formCategories());
	$TMPL->addVar('TMPL_menuopts',menu_formCategories());
	$TMPL->addVar('TMPL_menuopts_fs',menu_formCategories_for_select(intval($_GET['mid'])));
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
	$TMPL->ParseIntoVar($_CENTER,"addform");
}


### List of added pictures
$and_query = '';
if (isset($_GET['mid']) && intval($_GET['mid']))
	$and_query = "WHERE mid like '%\"".intval($_GET['mid'])."\"%'";
$query = "SELECT * FROM {$_CONF['db']['prefix']}_banner_carusel {$and_query} ORDER BY sort_id DESC";
//$result = $CONN->Execute($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg()); 

$result = $CONN->PageExecute($query,$SETTINGS['page_num'],$_GET['p']) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());   
   
   
 while($o = $result->FetchNextObject()){
     $TMPL_images[]  = array(
             'id'      =>  $o->ID,
             'img'     =>  $o->IMG,
             'title'   =>  $FUNC->unpackData($o->TITLE,$l),
             'blocked' =>  ($LOADED_PLUGIN['restricted']&&$_SESSION['id']!=$o->CREATOR)?true:false
       );
  
  }
 
 $TMPL->addVar('TMPL_images',$TMPL_images);
 $TMPL->addVar("TMPL_navbar", $FUNC->DrawPageBar($SELF."&p=",$result));
 $TMPL->ParseIntoVar($_CENTER,"list"); 
}

//*************************************************************************//
//*** Adding/Editing pictures *********************************************//
if($SETTINGS['with_cats']==1&&!$LOADED_PLUGIN['restricted']){
	switch($_GET['tab']){
		case 'categories':   $tab = 'categories';   break;
		default:             $tab = 'items';        break;
	} 

     $tabs = array(
          'items'       =>   $TEXT['global']['mng_items'],
          'categories'  =>  $TEXT['global']['mng_cats']
       );
	$_CENTER = pcmsInterface::drawTabs("{$SELF}&tab=",$tabs,$tab).$_CENTER;
}


//*************************************************************************//
//*******    Start  functions description   *******************************//
//*************************************************************************//

function formCategories($selected=""){
	global $FUNC,$CONN,$INTERFACE;
	global $_CONF,$SETTINGS,$LOADED_PLUGIN,$l;
	$categories =""; 
	if($SETTINGS['with_cats']==1) {
		$query  = "SELECT * FROM {$_CONF['db']['prefix']}_categories WHERE pid='{$LOADED_PLUGIN['id']}' ORDER BY cat_left";
		$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());  
		while($o = $result->FetchNextObject()) {
			$category[$o->CAT_ID] = str_repeat('&nbsp;',($o->CAT_LEVEL-1)*4).$FUNC->unpackData($o->NAME,$l); 
		}  
		$categories  = "<option></option>";
		$categories .= pcmsInterface::drawOptions($category,$selected,_ASSOC_);     
	} 
	return $categories;   
}

function menu_formCategories($selected) {
	global $_CONF, $CONN, $FUNC, $INTERFACE,$l;
	// Menu categories
	#$query_m  = "SELECT * FROM {$_CONF['db']['prefix']}_sitemenu WHERE cat_id > 1 ORDER BY cat_left";
	$query_m  = 'SELECT * FROM '.$_CONF['db']['prefix'].'_sitemenu WHERE cat_id > 1  ORDER BY cat_left';
	$result_m = $CONN->Execute($query_m) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());  
	while($o_m = $result_m->FetchNextObject()){
		$category_m[$o_m->CAT_ID] = str_repeat('&nbsp;',($o_m->CAT_LEVEL-1)*4).$FUNC->unpackData($o_m->NAME,$l);
	}
	$categories_m  = "<option></option>";
	$categories_m .= $INTERFACE->drawOptions($category_m,$selected,_ASSOC_);
	
	return $categories_m;
}

function menu_formCategories_for_select($selected) {
	global $_CONF, $CONN, $FUNC, $INTERFACE,$l;
	// Menu categories
	$query_m  = "SELECT * FROM {$_CONF['db']['prefix']}_sitemenu WHERE cat_id > 1 ORDER BY cat_left";
	$result_m = $CONN->Execute($query_m) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());  
	while($o_m = $result_m->FetchNextObject()){
		$category_m[$o_m->CAT_ID] = str_repeat('&nbsp;',($o_m->CAT_LEVEL-1)*4).$FUNC->unpackData($o_m->NAME,$l);
	}
	$categories_m  = "<option></option>";
	$categories_m .= $INTERFACE->drawOptions($category_m,$selected,_ASSOC_);
	
	return $categories_m;
}
?>