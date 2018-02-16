<?
if(!defined('ALLOW_ACCESS')) exit;
require_once('classes/dbtree/dbtree.class.php');

/*
$res = require_once(dirname(__FILE__).'/common.inc.php');

if(!$res){
  return;
}

*/

$getAct = httpGet('action');


if($_GET['action'] == 'new_content'){ 
   print $TMPL->parseIntoString('new_content_form'); 
   exit;
}


if($getAct == 'edit_content'){

   $id   = StringConvertor::ConvertSpecialChars(httpGet('id'));
   $lang = StringConvertor::ConvertSpecialChars(httpGet('lang'));
  
   $TMPL->addVar('TMPL_id',  $id);  
   $TMPL->addVar('TMPL_lang',$lang);  
   $TMPL->addVar('TMPL_langs',$_CONF['langs_all']);  
    
   print $TMPL->parseIntoString('edit_content_form');
   exit;
}



?>
