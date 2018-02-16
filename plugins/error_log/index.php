<?
if(!defined('ALLOW_ACCESS')) exit;

$ROOT = dirname(__FILE__);
require_once($ROOT."/lang/".LANG.".php");
$TMPL->setRoot($ROOT);

//*******************************************************************//
//*** Clearing log file *********************************************//
if(isset($_POST['action'])&&$_POST['action']=="clearlog")
  {
    $fp = @fopen($_CONF['path']['error_log'],"w");
    @fclose($fp);
    $FUNC->Redirect($_SERVER['PHP_SELF']."?m=errlog");
  }

//*******************************************************************//
//*** Displaying log file contents **********************************//    
$fp = nl2br(@file_get_contents($_CONF['path']['error_log'])); 
$TMPL->addVar("TMPL_errors",preg_replace("/(\[\d{2}\.\d{2}\.\d{4}\s\d{2}:\d{2}\])/U","<span class=\"err\"><b>$1</b></span>",$fp));
$TMPL->ParseIntoVar($_CENTER,"errlog");

?>