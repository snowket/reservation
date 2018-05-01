<?php
if(!defined('ALLOW_ACCESS')) exit;
error_reporting(E_ALL);
$ROOT = dirname(__FILE__);
$SELF = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];
$SELF_TABS = $_SERVER['PHP_SELF'] . "?m=" . $LOADED_PLUGIN['plugin'];
$imgDIR = $_CONF['path']['script_upload'].$LOADED_PLUGIN['plugin'];
$TABLE_ITEMS='cms_ch_items';
$TABLE_SERVICES='cms_ch_services';

require_once($ROOT."/lang/".LANG.".php");
$TMPL->setRoot($ROOT);
$TMPL->addVar("SELF", $SELF);

if (isset($_GET['excel'])) {
	require_once($ROOT."/download.php");
}

if (isset($_GET['tab'])) {
    $tab = $_GET['tab'];
    require_once($ROOT . "/files/" . $tab . ".php");
} else {
    reset($TEXT['tab']);         //Moves array pointer to first record
    $tab = key($TEXT['tab']);     //Returns current key
   header("Location: index.php?m=channel_management&tab=" . $tab);
}

if (!$LOADED_PLUGIN['restricted']) {
    //$_CENTER = pcmsInterface::drawTabs("{$SELF_TABS}&tab=",$tabs,$tab).$_CENTER;
    $_CENTER = pcmsInterface::drawModernTabs("{$SELF_TABS}&tab=", $TEXT['tab'], $tab) . $_CENTER;
}


# RESTRICTED AREA
