<?php

if(!defined('ALLOW_ACCESS')) exit;

$TABLE = $_CONF['db']['prefix'].'_email_templates';

$SELF   = $_SERVER['PHP_SELF']."?m=".$LOADED_PLUGIN['plugin'];


// record must be initialized first
$templates=$TEXT['templates'];
$content_lang=isset($GET['lang'])?$GET['lang']:LANG;
$type_id=(isset($GET['typeid'])&&(int)$GET['typeid']>0)?$GET['typeid']:1;

$query	= "SELECT * FROM ".$TABLE." WHERE lang='".$content_lang."' AND typeid = ".$type_id." LIMIT 1";

$result	= $CONN->Execute($query) OR $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
$record	= $result->fields;

if(!$record && $templates[$type_id]!='' &&  in_array($content_lang,$_CONF['langs_all'])) {
    $query = "INSERT INTO {$TABLE} SET
                              typeid='{$type_id}',
                              lang='{$GET['lang']}',
                              subject='Subject',
                              message='Message',
							  userid='{$_SESSION['pcms_user_id']}'";
    $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $query	= "SELECT * FROM ".$TABLE." WHERE lang='".$content_lang."' AND typeid = ".$type_id." LIMIT 1";
    $result	= $CONN->Execute($query) OR $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    $record	= $result->fields;
}

if(isset($_POST['action']) && $_POST['action']=="update") {
	if (!$record ){

	}else {
		$id = $record['id'];
        $message=str_replace('../',$_CONF['path']['url']."/",$VALIDATOR->qstr($_POST['o']['message']));
        $query = "UPDATE {$TABLE} SET
					  subject='".$VALIDATOR->qstr($_POST['o']['subject'])."',
					  message='".$message."'
					  WHERE id=".$id;
        $CONN->Execute($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
		$FUNC->Redirect($SELF.'&tab='.$_GET['tab'].'&typeid='.$record['typeid'].'&lang='.$content_lang);
	}
}


foreach ($_CONF['langs_all'] as $k) {
    $langTabs[$k] = $k;
}
$tabRedUrl = $SELF.($_GET['action']?'&action='.$_GET['action']:'').($_GET['typeid']?'&typeid='.$_GET['typeid']:'').'&lang=';

$record['subject'] = $VALIDATOR->ConvertSpecialChars($record['subject']);
$record['message'] = $VALIDATOR->ConvertSpecialChars($record['message']);

$TMPL->addVar("mailTemplates", $templates);
$TMPL->addVar("hotelSettings", getHotelSettings());
$TMPL->addVar("TMPL_langSwitchers",$INTERFACE->drawTabs($tabRedUrl,$langTabs,$FUNC->validLang($content_lang,'langs')));
$TMPL->addVar("data", $record);
$TMPL->ParseIntoVar($_CENTER, "guests_templates");