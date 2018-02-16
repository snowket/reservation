<?
if(!defined('ALLOW_ACCESS')) exit;

$ROOT   = dirname(__FILE__);
$g_tab=$_GET['tab']?'&tab='.$_GET['tab']:'';
$SELF   = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'].$g_tab;
$SELF_TABS   = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];
$imgDIR = $_CONF['path']['script_upload'].$LOADED_PLUGIN['plugin'];
require_once($ROOT."/lang/".LANG.".php");

$TMPL->addVar("SELF", $SELF);
$TMPL->addVar("PLUGIN", $LOADED_PLUGIN['plugin']);
$TMPL->setRoot($ROOT);


//*** Central side   ***********************************//
// $tab = isset($_GET['tab'])?$_GET['tab']:'';

switch($_GET['tab']){
    case 'settings':		$tab = 'settings';		    require_once($ROOT."/files/settings.php");break;
    case 'theme':           $tab = 'theme';             require_once($ROOT."/files/theme.php");break;
    default:			  	$tab = 'settings';		    require_once($ROOT."/files/settings.php");break;
}

//*****************************************************//
//*** DRAW TABS ***************************************//
if(!$LOADED_PLUGIN['restricted']){
    $tabs['settings']	= $TEXT['tab']['settings'];
    $tabs['theme']	    = $TEXT['tab']['theme'];
    $_CENTER = pcmsInterface::drawTabs("{$SELF_TABS}&tab=",$tabs,$tab).$_CENTER;
}
