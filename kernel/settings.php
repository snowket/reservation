<?
if(!defined('ALLOW_ACCESS')) exit;

$ROOT = dirname(__FILE__);
$SELF = $_SERVER['PHP_SELF']."?settings";

$TMPL->setRoot($ROOT);
$TMPL->addVar("SELF", $SELF);

//**********************************************************//
//*** Editing selected plugin settings- saving settings ****//
if($_POST['action']=='save'){ 
  $pid  = StringConvertor::toNatural($_POST['pid']);
  $POST = $VALIDATOR->ConvertSpecialChars($_POST);
  if(!$plugin = $FUNC->arraySearch($PLUGINS,'id',$pid))
     $FUNC->Redirect($SELF);
     
  unset($POST['pid']);
  unset($POST['action']);
  if(file_exists("./plugins/".$plugin['plugin']."/settings.php"))
    require_once("./plugins/".$plugin['plugin']."/settings.php");  
  $query = "update {$_CONF['db']['prefix']}_pcms_plugins set 
           settings='".serialize($POST)."' where id='{$pid}'";
  $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
  $FUNC->Redirect($SELF."&pid=".$pid);         
 
}
//**********************************************************//
//*** Printing form for editing selected plugin settings ***//
elseif(isset($_GET['pid'])){
   $pid = StringConvertor::toNatural($_GET['pid']);
   if(!$plugin = $FUNC->arraySearch($PLUGINS,'id',$pid))
      $FUNC->Redirect($SELF);    
   require_once("./plugins/".$plugin['plugin']."/lang/".LANG.".php");   
   $TMPL->setRoot("./plugins/".$plugin['plugin']);
   $TMPL->addVar("TMPL_plugin",$plugin['title']);
   $TMPL->addVar("TMPL_setts",$FUNC->unpackData($plugin['settings']));
   $TMPL->addVar("TMPL_pid",$plugin['id']);
   $TMPL->ParseIntoVar($_CENTER,"settings");  
}

//**********************************************************//
//*** Printing form for editing selected plugin settings ***//
else{
 
  $_CENTER = "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\">";
  for($i=0;$i<count($PLUGINS);$i++)
    {
      if(!empty($PLUGINS[$i]['settings']))
        {
          $_CENTER .="<tr>
                        <td width=\"30\"><img src=\"./images/icos24/gear_view.gif\" width=\"24\" height=\"24\" border=\"0\"></td>
                        <td><a href=\"{$SELF}&pid={$PLUGINS[$i]['id']}\" class=\"basic\" style=\"font-size:14px\"><b>".$PLUGINS[$i]['title']."</b></td>
                   </tr>";
        }   
    }
  $_CENTER .= "</table>";  

}
