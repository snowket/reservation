<?php
if(!defined('ALLOW_ACCESS')) exit;
$ROOT = dirname(__FILE__);
$SELF = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];
$imgDIR = $_CONF['path']['script_upload'].$LOADED_PLUGIN['plugin'];


require_once($ROOT."/lang/".LANG.".php");
$TMPL->setRoot($ROOT);
$TMPL->addVar("SELF", $SELF);

if (isset($_GET['excel'])) {
	require_once($ROOT."/download.php");
}

switch($_GET['tab']){
    case 'tasks':	    $tab = 'tasks';    require_once($ROOT."/files/tasks.php");	break;
	default:			$tab = 'tasks';   	require_once($ROOT."/files/tasks.php");	break;
}

$tabs['tasks']		= 'Tasks';


$_CENTER= pcmsInterface::drawTabs("{$SELF}&tab=",$tabs,$tab).$_CENTER;

//$TMPL->ParseIntoVar($_RIGHT,'search');

