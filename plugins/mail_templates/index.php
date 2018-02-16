<?php
//**************************************************************//
//**************************************************************//
//                PRODUCTS  PCMS PLUGIN                         // 
//**************************************************************//
//**************************************************************//
if(!defined('ALLOW_ACCESS')) exit;

$ROOT   = dirname(__FILE__);
$SELF   = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];
$imgDIR = $_CONF['path']['script_upload'].$LOADED_PLUGIN['plugin'];

require_once($ROOT."/lang/".LANG.".php");
$TMPL->addVar("SELF", $SELF);
$TMPL->addVar("PLUGIN", $LOADED_PLUGIN['plugin']);
$TMPL->setRoot($ROOT);


switch($_GET['tab']){
	case 'guest_templates':		$tab = 'guest_templates';     require_once($ROOT."/files/guests_templates.php");    break;
	default:				    $tab = 'guest_templates';	  require_once($ROOT."/files/guests_templates.php");    break;
}

### DRAW TABS
if(!$LOADED_PLUGIN['restricted']){
	$tabs = array(
					'guest_templates'	=>	$TEXT['tab']['guest_templates'],
				);
	
	$_CENTER = pcmsInterface::drawTabs("{$SELF}&tab=", $tabs, $tab) . $_CENTER;
}
