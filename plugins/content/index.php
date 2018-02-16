<?
if(!defined('ALLOW_ACCESS')) exit;

$ROOT = dirname(__FILE__);
$SELF = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];

require_once($ROOT."/lang/".LANG.".php");
require_once(dirname(__FILE__).'/common.inc.php');



$TMPL->setRoot($ROOT);
$TMPL->addVar("SELF", $SELF);


if(isset($_POST['action'])){

 //*******************************************************//
 //*** Creating new content item *************************//
 if($_POST['action']=="add"){
    $title = $VALIDATOR->ConvertSpecialChars($_POST['title']);
    $VALIDATOR->validateLength($title,$TEXT['global']['title'],3,80);

    if(!$errors = $VALIDATOR->PassErrors()){ 
        $rec_id = createContent($title,httpPost('search'));      
        $get_lang=$_GET['l']?$_GET['l']:$_CONF['langs_default'];
        header("Location: {$SELF}&action=edit&id={$rec_id}&lang=".$get_lang);
    }
    else{
       $TMPL->addVar("TMPL_errors",$errors);
    }
 }
  
 //*******************************************************//
 //*** Editing existing content item *********************//  
 elseif($_POST['action']=="save"){ 
     $cid = StringConvertor::toNatural($_POST['cid']);
     if($LOADED_PLUGIN['restricted']){
         $query = "select creator from {$_CONF['db']['prefix']}_content_title where id='{$cid}'";
         $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg()); 
         $data = $result->GetRows();
         if($data[0]['creator']!=$_SESSION['pcms_user_id'])
           $FUNC->Redirect($SELF);
     }
     
     saveContent($cid, $_POST['l'], $_POST['content']); 
     $FUNC->Redirect($SELF."&action=edit&cid={$cid}&l={$_POST['l']}");      
  } 
}

if(isset($_GET['action'])){
  $cid  = StringConvertor::toNatural($_GET['cid']);
  
 //*******************************************************//
 //*** Form for editing selected content item ************//
 if($_GET['action']=="edit"){        
     $data = getContent(httpGet('cid'),httpGet('l'));
     if(!$data){ 
       $FUNC->Redirect($SELF);
     }  
    
     $TMPL->addVar("TMPL_text",$data['content']);
     $TMPL->AddVar("TMPL_cid",$data['id']);
     $TMPL->AddVar("TMPL_lang",$_CONF['langs_all']); 
     $TMPL->AddVar("TMPL_l",$data['lang']);
     if($LOADED_PLUGIN['restricted']&&($data['creator']!=$_SESSION['pcms_user_id'])){
       $TMPL->ParseIntoVar($_CENTER,"content_restricted");
     }  
     else{ 
       $TMPL->ParseIntoVar($_CENTER,"content_full");
     }  
     $query = "select firstname,email from {$_CONF['db']['prefix']}_users where id='{$data['creator']}'";
     $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());  
     $data = $result->fields;       
     $TMPL->AddVar("TMPL_creator",$data);
     $TMPL->ParseIntoVar($_RIGHT,"creator_info");
 } 
   
 //*******************************************************//
 //*** Deleting a content item ***************************// 
 elseif($_GET['action']=="delete")  
   {
      $strict_query = "";
      if($LOADED_PLUGIN['restricted'])
         $strict_query = " and {$_CONF['db']['prefix']}_content_title.creator='{$_SESSION['pcms_user_id']}'";
      $query = "delete from {$_CONF['db']['prefix']}_content_title,{$_CONF['db']['prefix']}_content 
              using {$_CONF['db']['prefix']}_content_title,{$_CONF['db']['prefix']}_content 
              where {$_CONF['db']['prefix']}_content_title.id ='{$cid}' and 
              {$_CONF['db']['prefix']}_content.rec_id ='{$cid}'".$strict_query;
      $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg()); 
      $FUNC->Redirect($SELF);
   }
}

//*******************************************************//
//*** Default Output - list of existing content items ***//
else{
	$query  = "select* from {$_CONF['db']['prefix']}_content_title order by title";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$items = $result->GetRows();

	if($LOADED_PLUGIN['restricted']) {
		for($i=0; $i<count($items); $i++)
			$items[$i]['block'] =($items[$i]['creator']==$_SESSION['pcms_user_id'])?false:true;
	}
	
	$blocked_ids = array(1,2,3,4,222);
	for($i=0; $i<count($items); $i++) {
		if (in_array($items[$i]['id'],$blocked_ids))
			$items[$i]['block'] = true;
	}
	

	$TMPL->importVars("_CONF");
	$TMPL->addVar("TMPL_userid",$_SESSION);
	$TMPL->addVar("TMPL_item",$items);
	$TMPL->ParseIntoVar($_CENTER,"contentlist");
}

?>