<?
require_once(dirname(__FILE__).'/header.inc.php');

$TMPL['errors'] = "";
if(isset($_POST["action"])&&$_POST["action"]=="submit") { 
	$POST = $VALIDATOR->ConvertSpecialChars($_POST);
	$query = "SELECT users.*,groups.permitions FROM {$_CONF['db']['prefix']}_users AS users, 
	     {$_CONF['db']['prefix']}_groups AS groups WHERE 
	     users.login='".mysql_real_escape_string($POST['login'])."' AND  users.group_id=groups.id limit 1";
	$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,__FILE__, __LINE__, $CONN->ErrorMsg());
	$data   = $result->GetRows();
	if(count($data)==0||$FUNC->CompiledPass($POST['password'],$data[0]['passhash'])!=$data[0]['password'])
		$TMPL['errors'] = $TEXT['global']['no_user'];
	elseif(empty($data[0]['permitions'])&&$data[0]['group']>1)	
		$TMPL['errors'] = $TEXT['global']['no_user'];
	elseif($data[0]['publish']!='1')
		$TMPL['errors'] = $TEXT['global']['no_user'];
	else { 
		$_SESSION['pcms_user_id']    = $data[0]['id'];
		$_SESSION['pcms_user_group'] = $data[0]['group_id'];
		//$_SESSION['lang']  = $data[0]['lang'];
		session_write_close(); 
		
		$FUNC->Redirect('index.php');
	} 
}  


require_once("./templates/auth.template.php");

?>