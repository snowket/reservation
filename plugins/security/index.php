<?
if(!defined('ALLOW_ACCESS')) exit;

$ROOT   = dirname(__FILE__);
$g_tab=$_GET['tab']?'&tab='.$_GET['tab']:'';
$SELF   = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'].$g_tab;
$SELF_TABS   = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];
$uploadDIR = $_CONF['path']['script_upload'].$LOADED_PLUGIN['plugin'];
require_once($ROOT."/lang/".LANG.".php");

$TMPL->addVar("SELF", $SELF);
$TMPL->addVar("PLUGIN", $LOADED_PLUGIN['plugin']);
$TMPL->setRoot($ROOT);


//*** Central side   ***********************************//
// $tab = isset($_GET['tab'])?$_GET['tab']:'';

switch($_GET['tab']){
    case 'ips':		  	        $tab = 'ips';		            require_once($ROOT."/files/ips.php");break;
    case 'db_backup':           $tab = 'db_backup';             require_once($ROOT."/files/db_backup.php");break;
    default:			  	    $tab = 'ips';		            require_once($ROOT."/files/ips.php");break;
}

//*****************************************************//
//*** DRAW TABS ***************************************//
if(!$LOADED_PLUGIN['restricted']){
    $tabs['ips']	    = $TEXT['tab']['ips'];
    $tabs['db_backup']	= $TEXT['tab']['db_backup'];
    $_CENTER = pcmsInterface::drawTabs("{$SELF_TABS}&tab=",$tabs,$tab).$_CENTER;
}

