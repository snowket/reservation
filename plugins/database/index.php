<?
// ###################################################################### //
// ########################### NEWS PCMS PLUGIN  ######################## //
// ###################################################################### //
if(!defined('ALLOW_ACCESS')) exit;

$ROOT   = dirname(__FILE__);
$SELF   = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];
$imgDIR = $_CONF['path']['script_upload'].$LOADED_PLUGIN['plugin'];

require_once($ROOT."/lang/".LANG.".php");
$TMPL->addVar("SELF", $SELF);
$TMPL->addVar("TMPL_settings", $SETTINGS);

/*
if($SETTINGS['with_cats']==1&&!$LOADED_PLUGIN['restricted']){
	switch($_GET['tab']){
		case 'categories':   $tab = 'categories';   break;
		default:             $tab = 'items';        break;
	} 

     $tabs = array(
          'items'       =>  $TEXT['global']['mng_items'],
          'categories'  =>  $TEXT['global']['mng_cats']
       );
	$_CENTER = $INTERFACE->drawTabs("{$SELF}&tab=",$tabs,$tab).$_CENTER;
}
*/
$TMPL->setRoot($ROOT);

require_once('database.php');



?>