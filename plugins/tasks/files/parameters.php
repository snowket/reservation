<?
//**************************************************************//
//**************************************************************//
//             SITE_SETTINGS PCMS PLUGIN                        //
//**************************************************************//
//**************************************************************//
if(!defined('ALLOW_ACCESS')) exit;

$ROOT   = dirname(__FILE__);
$SELF   = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];
$imgDIR = $_CONF['path']['script_upload'].$LOADED_PLUGIN['plugin'];




//**************************************************************//
//****** Sending a mail list ***********************************//
if(isset($_POST['action'])&&$_POST['action']=="submit"){
	ignore_user_abort(true); 
	set_time_limit(0);

	unset($_POST['update']);
	unset($_POST['action']);

	$_POST = $VALIDATOR->ConvertSpecialChars($_POST);
	#p($_POST);

	foreach ($_POST as $k => $v) {
		$query  = "UPDATE {$_CONF['db']['prefix']}_settings SET value = '$v' WHERE input_name = '$k'";
		$res = $CONN->_Query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	}
	$FUNC->Redirect($SELF."&tab=parameters");
}

//**************************************************************//
//****** Default output  ***************************************//
else{
	$query	= "SELECT * FROM {$_CONF['db']['prefix']}_settings WHERE publish='1' AND pluginid='".$LOADED_PLUGIN['id']."' ORDER BY orderid,id";
	$result	= $CONN->Execute($query)or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data	= $result->GetRows();
	
    $TMPL->addVar("data",$data);
    $TMPL->ParseIntoVar($_CENTER,'parameters');
}
?>