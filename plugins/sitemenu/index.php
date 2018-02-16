<?php
//****************************************************************//
//****************************************************************//
//******************** SITEMENU  PCMS PLUGIN *********************//
//****************************************************************//
//****************************************************************//

if(!defined('ALLOW_ACCESS')) exit;
require_once("./classes/dbtree/dbtree.class.php");

$ROOT = dirname(__FILE__);
$SELF = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];

require_once($ROOT."/config.php");
require_once($ROOT."/lang/".LANG.".php");
$TMPL->setRoot($ROOT);
$TMPL->addVar("SELF", $SELF);

$dbtree	 = new dbtree($_CONF['db']['prefix'].'_sitemenu', $CONN);

$tab	 = isset($_GET['tab'])&&isset($_CONF['menutypes'][$_GET['tab']])?$_GET['tab']:key($_CONF['menutypes']);
$TMPL->addVar("TMPL_tab",$tab);

//****************************************************************//
//******** Adding new category / Editing- saving changes *********//
if(isset($_POST['action'])) {
	$POST = $VALIDATOR->ConvertSpecialChars($_POST); 
	$img  = $POST['image'];

	foreach($_CONF['siteblocks'][$POST['siteblock']?$POST['siteblock']:'main'] as $k=>$v) {
		$pageblocks[$k] = $POST['block_'.$k];
	}
	
    //*** we must check if fields "Name" are filled ***//
    for($i=0;$i<count($_CONF['langs_all']);$i++) {
        $VALIDATOR->ValidateLength($POST['name'][$_CONF['langs_all'][$i]],"{$TEXT['sitemenu']['mtitle']}({$_CONF['langs_all'][$i]})",3);
    }
    $errors =$VALIDATOR->PassErrors();

    //********************* header image upload **********************//
    if(!$errors) {
        if($_FILES['newimage']['name']&&$_FILES['newimage']['size']) {
            $IMG = new ImageGD($_CONF['path']['script_upload']."sitemenu");
            $img = $IMG->uploadImage("newimage",1048576,false);
            $errors =$IMG->passErrors();
        }
    }

    //********************* if errors are found **********************//
    if($errors)
        $TMPL->addVar("TMPL_errors",$errors);
    else {
        $parent_id=(isset($_GET['cid']))?$VALIDATOR->ToNatural($_GET['cid']):"1";
        $structure = serialize($pageblocks);

        //**************** Adding new record in database *****************//
        if($_POST['action']=="add") {
            $data=array(
                      'name'		=> serialize($POST['name']),
                      'title'		=> serialize($POST['title']),
                      'redirect'	=> $POST['redirect'],
                      'type'		=> $POST['type_compare'],
                      'viewtype'	=> $POST['viewtype'],
                      'structure'	=> $structure,
                      'img'			=> $img,
                      'bgimg'		=> $POST['bgimage'],
                      'design'		=> $POST['design'],
                      'publish'		=> '1',
                      'groups'		=> serialize($POST['groups']),
                      'groups_view'	=> serialize($POST['groups_view']),
                  );
            $id = $dbtree->Insert($parent_id, array(''=>array('')), $data);
            if(!empty($dbtree->ERRORS_MES)) $FUNC->ServerError(__FILE__,__LINE__,implode("",$dbtree->ERRORS_MES));
            $FUNC->Redirect($SELF."&tab=".$tab);
        }

        //******************* Editing existing record ********************//
        elseif($_POST['action']=="edit") {
            $cat_id = $VALIDATOR->toNatural($_POST['cid']);
            $POST['type'] = $POST['type']?$POST['type']:$POST['type_compare'];
            $query = "UPDATE {$_CONF['db']['prefix']}_sitemenu SET
            			name		='".serialize($POST['name'])."',
                     	title		='".serialize($POST['title'])."', 
                     	redirect	='{$POST['redirect']}',
                     	type		='{$POST['type']}',
                     	viewtype	='{$POST['viewtype']}',
                     	structure	='".serialize($pageblocks)."',
                     	img			='{$img}',
                     	bgimg		='{$POST['bgimage']}',
                     	design		='".$POST['design']."',
                     	groups		='".serialize($POST['groups'])."',
                     	groups_view ='".serialize($POST['groups_view'])."'
                      WHERE  cat_id='{$cat_id}'";
            $res = $CONN->_query($query)  or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
            
            
            if($POST['type'] && ($POST['type'] != $POST['type_compare'])){
               $query = "SELECT cat_left,cat_right from {$_CONF['db']['prefix']}_sitemenu WHERE cat_id='{$cat_id}'";
               $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());	

               $query = "UPDATE {$_CONF['db']['prefix']}_sitemenu SET type='{$POST['type']}' 
                        WHERE cat_left>='{$result->fields['cat_left']}'  AND  cat_right<='{$result->fields['cat_right']}'";
               $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());	         
            }
            
            $FUNC->Redirect($SELF."&tab=".$POST['type']."&action=edit&cid={$cat_id}");
        }
    }

}

### Change category status
elseif(isset($_GET['action'])&&$_GET['action']=="change_status" && isset($_GET['cid'])) {
    $cat_id = $VALIDATOR->ToNatural($_GET['cid']);
    $query="UPDATE {$_CONF['db']['prefix']}_sitemenu SET publish=if(publish=1,0,1) WHERE cat_id=".$cat_id;
    $res=$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    $FUNC->Redirect($SELF."&tab=".$tab);
}

### Delete category
elseif(isset($_GET['action'])&&$_GET['action']=="delete" && isset($_GET['cid'])) {
    $cat_id = $VALIDATOR->toNatural($_GET['cid']);
    $dbtree->DeleteAll($cat_id,array('and' => array("blocked = '0'")));
    if(!empty($dbtree->ERRORS_MES)) $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    $FUNC->Redirect($SELF."&tab=".$tab);
}

### Printing form for adding or editing categories
if(isset($_GET['action'])&&($_GET['action']=="add"||$_GET['action']=="edit")) {
	$cid=(isset($_GET['cid']))?$VALIDATOR->ToNatural($_GET['cid']):"1";
	
    //********************** header images list **********************//
    $FM = new FileManager();
    $images = $FM->ReadFiles($_CONF['path']['script_upload']."sitemenu");
    $TMPL->addVar("TMPL_imagedir",$_CONF['path']['script_upload']."sitemenu/");
    
	//$designs = $FM->ReadFiles($_CONF['path']['script_upload']."design");
	$TMPL->addVar("TMPL_designdir",$_CONF['path']['script_upload']."design/");

	//*********************** presetted blocks ***********************//
	$query  = "SELECT name,title FROM {$_CONF['db']['prefix']}_sitemenu_blocks WHERE publish=1 ORDER BY title";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	while($o = $result->FetchNextObject())
		$presetted[$o->NAME] = $o->TITLE;

	//************************ content blocks ************************//
	$query  = "SELECT id,title FROM {$_CONF['db']['prefix']}_content_title ORDER BY title";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	while($o = $result->FetchNextObject()) {
		$content[$o->ID] = $o->TITLE;
	}

	$TMPL->addVar('TMPL_pblocks',$INTERFACE->drawOptions($presetted,'',_ASSOC_));
	$TMPL->addVar('TMPL_cblocks',$INTERFACE->drawOptions($content,'',_ASSOC_));
	$TMPL->addVar('TMPL_lang',$_CONF['langs_all']);
	#$TMPL->addVar('TMPL_blocks',$_CONF['siteblocks']); -- MOVED TO (this.line+27) Line

    //********************** Editing a category **********************//
    if($_GET['action']=="edit") {
        $query="SELECT * FROM {$_CONF['db']['prefix']}_sitemenu WHERE cat_id='$cid'";
        $res=$CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
        if(!$data  = $res->fields)
            $FUNC->Redirect($SELF."&tab=".$tab);
        
        $TMPL->addVar("TMPL_name",$FUNC->unpackData($data['name']));
        $TMPL->addVar("TMPL_title",$FUNC->unpackData($data['title']));
        $TMPL->addVar("TMPL_groups_selected",unserialize($data['groups']));
        $TMPL->addVar("TMPL_groups_view_selected",unserialize($data['groups_view']));
        if ($data['cat_level'] == 1) {
        	$TMPL->addVar("TMPL_types",$INTERFACE->drawOptions($_CONF['menutypes'],$data['type'],_ASSOC_));
        }
        $TMPL->addVar("TMPL_viewtypes",$INTERFACE->drawOptions($_CONF['viewtypes'],$data['viewtype'],_ASSOC_));
        
        $TMPL->addVar("TMPL_type",$data['type']);
        $TMPL->addVar("TMPL_redirect",$data['redirect']);
        $TMPL->addVar("TMPL_allimages",$INTERFACE->drawOptions($images,$data['img'],_LINEAR_));
		$TMPL->addVar("TMPL_alldesigns",$INTERFACE->drawOptions(array_keys($_CONF['siteblocks']),$data['design'],_LINEAR_));
        
        
        if(!empty($data['img']))
            $TMPL->addVar("TMPL_image",$data['img']);
		
		if(!empty($data['design']))
			$TMPL->addVar("TMPL_design",$data['design']);
		
        $structure = $FUNC->unpackData($data['structure']);
        $TMPL->addVar("TMPL_structure",$structure);
		foreach($_CONF['siteblocks'][$data['design']] as $k=>$v) {
			$options[$k] = getOptionList($structure[$k]);
		}
		
		$TMPL->addVar("TMPL_blocks",$_CONF['siteblocks'][$data['design']]);
		$TMPL->addVar("TMPL_siteblock",$data['design']);
		$TMPL->addVar("TMPL_bgimage",$data['bgimg']);
        $TMPL->addVar("TMPL_options",$options);
        $TMPL->addVar("TMPL_cid",$cid);
        $TMPL->addVar("TMPL_action","edit");
    }
    else {
		$TMPL->addVar("TMPL_blocks",$_CONF['siteblocks']['main']);
		/*	if ($cid == 1)
			$TMPL->addVar("TMPL_types",$INTERFACE->drawOptions($_CONF['menutypes'],'',_ASSOC_));*/
        $TMPL->addVar("TMPL_viewtypes",$INTERFACE->drawOptions($_CONF['viewtypes'],$data['viewtype'],_ASSOC_));
        
        $TMPL->addVar("TMPL_type",$tab);
        $TMPL->addVar("TMPL_allimages",$INTERFACE->drawOptions($images,'',_LINEAR_));
        $TMPL->addVar("TMPL_alldesigns",$INTERFACE->drawOptions(array_keys($_CONF['siteblocks']),'',_LINEAR_));
        $TMPL->addVar("TMPL_action","add");
    }
    
    
	$query	= "SELECT * FROM {$_CONF['db']['prefix']}_groups";
	$result	= $CONN->Execute($query) OR $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$groups	= $result->GetRows();
	array_unshift($groups, array('id'=>'guest','title'=>'Guest'));
	$TMPL->addVar('TMPL_groups', $groups);
	
	
	$TMPL->ParseIntoVar($_CENTER,"addtitle");
}

//****************************************************************//
//***************** Moving category up one level *****************//
elseif(isset($_GET['action'])&&$_GET['action']=="up" && isset($_GET['cid'])){
    $cat_id = $VALIDATOR->ToNatural($_GET['cid']);
    $dbtree->Move($cat_id,'before',array(''=>array()));
    if(!empty($dbtree->ERRORS)) $FUNC->ServerError(__FILE__,__LINE__,$dbtree->ErrorMsg());
    $FUNC->Redirect($SELF."&tab=".$tab);
}

//****************************************************************//
//**************** Moving category down one level ****************//
elseif (isset($_GET['action'])&&$_GET['action']=="down" && isset($_GET['cid'])){
    $cat_id = $VALIDATOR->ToNatural($_GET['cid']);
    $dbtree->Move($cat_id,'after',array('and'=>array("type='{$tab}'")));
    if(!empty($dbtree->ERRORS)) $FUNC->ServerError(__FILE__,__LINE__,$dbtree->ErrorMsg());
    $FUNC->Redirect($SELF."&tab=".$tab);
}

//****************************************************************//
//**************** Contents of selected menu item ****************//
elseif(isset($_GET['action'])&&$_GET['action']=="browse")
{
    $cat_id = $VALIDATOR->toNatural($_GET['cid']);
    $query = "select name,structure from {$_CONF['db']['prefix']}_sitemenu where cat_id=".$cat_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    $data = $result->GetRows();
    $content = array();
    if(count($data)==1)
    {
        $structure = $FUNC->unpackData($data['0']['structure']);
        foreach($structure as $value)
        {
            for($i=0;$i<count($value);$i++)
            {
                if(is_numeric($value[$i]))
                    $content[] = $value[$i];
            }

        }
    }
    if(count($content)>0)
    {
        $query = "select id, title from {$_CONF['db']['prefix']}_content_title
                 where id in(".implode(',',$content).")";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
        $items = $result->GetRows();
        $TMPL->importVars("_CONF");
        $TMPL->addVar("TMPL_item",$items);
    }
    $TMPL->addVar("TMPL_menutitle",$FUNC->unpackData($data[0]['name'],$FUNC->validLang(LANG,'langs')));
    $TMPL->ParseIntoVar($_CENTER,"contentlist");
}

//****************************************************************//
//******************** Deleting header image *********************//
elseif(isset($_GET['action'])&&$_GET['action']=="delpic")
{
    $pic = basename($VALIDATOR->ConvertSpecialChars($_GET['pic']));
    if(@unlink($_CONF['path']['script_upload']."sitemenu/".$pic))
    {
        $query = "update {$_CONF['db']['prefix']}_sitemenu set img='' where img='{$pic}'";
        $res = $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    }
    $FUNC->Redirect($_SERVER['HTTP_REFERER']);
}
//****************************************************************//
//***** Default output- Printing list of existing categories *****//
else
{ 

    $dbtree->Full(array("cat_id","cat_left","cat_right","cat_level","name","publish","blocked","type"),array("and"=>array("type='{$tab}'")));
    if(!empty($dbtree->ERRORS_MES))
    { 
    	$FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    }
    $l = $FUNC->validLang(LANG,'langs');
    
 

	while ($item = $dbtree->NextRow()) 
	{ 
		$item['name'] = $FUNC->unpackData($item['name'],$l);
        if($item['cat_level']==1)
        {
            $item['name'] = "<b>".$item['name']."</b>";
        }	
	
        $item['spacer'] = str_repeat('&nbsp;', 6 *$item['cat_level']); 
        $menu_item[] = $item;
    } 
    
	if(count($menu_item)>0)
  	{     		
  		$tree = ""; 
		for($i=0;$i<count($menu_item);$i++) 
		{
	  		$temp_menu_item = $menu_item[$i];
	  		
	  		if ($menu_item[$i]['cat_left']+1!=$menu_item[$i]['cat_right'])
	  		{	
				$div_id = $i;
	       		$code = "<div id='sitemenu{$div_id}' style=\"display:none;text-valign:top;\">
					     <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-size:12px;\" border=\"0\"><tr><td valign=\"top\">";  
	            
	            while(isset($menu_item[++$i]) && $menu_item[$i]['cat_level']>1) 
	            {
	            	$item['spacer'] = str_repeat('&nbsp;', 6*$menu_item[$i]['cat_level']); 
	                $code.= drawRow($menu_item[$i]);
	                	
	            }
	            $i--;	            	            
	            
	        	$code .= "</td></tr></table></div>";
	        	$tree .= drawRow($temp_menu_item,$code,$div_id,true);//,$state="closed"
  				     			
	  		}
	  		else
	  		{
				$tree .= drawRow($menu_item[$i]);
	  		}		
		} 
	}
    $TMPL->addVar("TMPL_tree",$tree);
    $TMPL->ParseIntoVar($_CENTER,"tree");
}


  $TMPL->prependToVar($_CENTER,$INTERFACE->drawTabs("{$SELF}&tab=",$_CONF['menutypes'],$tab));

//****************************************************************//
//***************** Start Functions Descriptions *****************//
//****************************************************************//
//****************************************************************//
//*** Generates options list for added pageblocks in edit form ***//
function getOptionList($container=array())
{
    global $presetted,$content;
    $out="";
    if(isset($container))
    {
        for($i=0;$i<count($container);$i++)
        {
            $index=$container[$i];
            if(is_numeric($index)&&isset($content[$index]))
            {
                $out .="<option value=\"{$index}\">{$content[$index]}</option>";
            }
            elseif(isset($presetted[$index]))
            {
                $out .="<option value=\"{$index}\">{$presetted[$index]}</option>";
            }
        }
        return $out;
    }
}
function drawRow($menu_item,$code="",$div_id="",$state=false) {
	global $TMPL;
	$TMPL->addVar("TMPL_row",$menu_item);
	$TMPL->addVar("TMPL_div_id",$div_id);
	$TMPL->addVar("TMPL_code",$code);
	$TMPL->addVar("TMPL_state",$state);	        	
	$TMPL->ParseIntoVar($tree,"tree_row");   
	return $tree;
}

?>