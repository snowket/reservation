<?

header('Content-type: text/html; charset=utf-8');
require_once(dirname(__FILE__).'/header.inc.php');
define("HMS_TITLE",'ProService - Hotel Management System (HMS)');
define("HMS_VERSION",'4.0');
define("HMS_ROOT",dirname(__FILE__));
define("RESERVATION_ROOT",dirname(HMS_ROOT));

//*** Authorization  ************************************//
if(!isset($_SESSION['pcms_user_id'])||!isset($_SESSION['pcms_user_group'])){
    $FUNC->Redirect("auth.php");
}

$TMPL = new pcmsTemplate();
$TMPL->importVars("TEXT");
$TMPL->importVars('_CONF');


$server_date=date('Y-m-d', strtotime('-1 month'));
$TMPL->addVar('server_date',$server_date);
//******************************************************//
//*** Reading available plugins ************************//
$restriction = " WHERE publish=1 ";
if($_SESSION['pcms_user_group']>1){
	$query	= "SELECT permitions, restricted from {$_CONF['db']['prefix']}_groups WHERE id='{$_SESSION['pcms_user_group']}'";
	$result	= $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
	$data	= $result->GetRows();
	if($data[0]['permitions']==''){
		$data[0]['permitions']=0;
	}
	$restriction = " WHERE id IN({$data[0]['permitions']})";
}
else {
	#$restriction .= "";
}




$query   = "SELECT * FROM {$_CONF['db']['prefix']}_pcms_plugins {$restriction} ORDER BY sort_id ASC";
$result  = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
$PLUGINS = $result->GetRows();

$query   = "SELECT * FROM {$_CONF['db']['prefix']}_currency_rates
            ORDER BY created_at DESC
            LIMIT 1";
$currency_rates  = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());


unset($data);
function getUserName(){
    global $CONN, $FUNC;
    if(isset($_SESSION['pcms_user_id']) && isset($_SESSION['pcms_user_group'])){
        $query = "SELECT * FROM cms_users WHERE id=".$_SESSION['pcms_user_id'];
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $result = $result->fields;
        return $result;
    }else{
        return false;
    }
}


$user=getUserName();
$TMPL->addVar('user',$user);
//*****************************************************//
//*** Selecting active module *************************//
if(isset($_GET['m'])){
	if($LOADED_PLUGIN = $FUNC->arraySearch($PLUGINS,'plugin',$_GET['m'])) {
		if(!empty($data[0]['restricted']))
			$LOADED_PLUGIN['restricted'] = (in_array($LOADED_PLUGIN['id'],explode(",",$data[0]['restricted'])))?true:false;
		if(!empty($LOADED_PLUGIN['settings']))
			$SETTINGS =$FUNC->unpackData($LOADED_PLUGIN['settings']);
		require_once("./plugins/{$LOADED_PLUGIN['plugin']}/index.php");
	}
}
//******************************************************//
//*** Some functions, available for superadmin only ****//
elseif($_SESSION['pcms_user_id']==1){
	if(isset($_GET['settings']))
		require_once("./kernel/settings.php");
}

if(!isset($_GET['m']) && !isset($_GET['settings'])) {
	require_once("index_design.php");
}
require 'classes/chennels.php';
/*
if($_SESSION['pcms_user_id']==1) {
	if(!empty($LOADED_PLUGIN['settings']))
        $go_to_plugin ="&pid=".$LOADED_PLUGIN['id'];
	    $_LEFT .="<div ".OMO." style=\"width:100%; padding: 5px 0px 5px 0px; border:1px Solid #F8F8F8; text-align:center;\"><a class=\"basic\" href=\"{$SERVER['PHP_SELF']}?settings{$go_to_plugin}\"><u>{$TEXT['global']['settings']}</u></a></div>";
	    $_LEFT .= '<br><a href="'.$_CONF['path']['url'].'/buildCache.php" class="text1" style="margin-left:25px;">'.$TEXT['global']['clear_cache'].'</a>';
}
*/

$query = "SELECT * FROM {$_CONF['db']['prefix']}_backups WHERE downloaded=0";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, mysql_error());
$backups = $result->getRows();


require_once("templates/index.template.php");
$CONN->_close();
