<?php
error_reporting(E_ALL && ~E_NOTICE);
set_time_limit(0);
ignore_user_abort(true);
//mail('sergi@proservice.ge','HMS - currency_parser.php','cronjob started at: '.date("Y-m-d H:i:s"));
$parts=explode('/hms/',__DIR__);
$hmsDir = $parts[0]."/hms";
$reservationDir = $parts[0];
//*******************************************************//
//*** Authorization  ************************************//
header('Content-Type: text/html; charset=utf-8');
//*******************************************************//
//*** Including base classes ****************************//
require_once($hmsDir."/config.php");
require_once($hmsDir."/common.php");
$FUNC = new CommonFunc();

//*******************************************************//
//*** Setting site language  ****************************//
DEFINE('LANG', $FUNC->SetLang("langs_pcms"));
require_once($hmsDir."/lang/" . LANG . ".php");
require_once($hmsDir."/classes/adodb/adodb.inc.php");
require_once($hmsDir."/classes/datavalidator/validator.class.php");


//*******************************************************//
//*** Establishing connection with database *************//
$CONN = &ADONewCONNection($_CONF['db']['type']);
$CONN->connect($_CONF['db']['host'], $_CONF['db']['user'], $_CONF['db']['pass'], $_CONF['db']['name']);
$CONN->debug = 0;
$CONN->setFetchMode(ADODB_FETCH_ASSOC);

$query = "SET NAMES utf8 COLLATE utf8_general_ci";
$CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

$query = "SELECT * FROM {$_CONF['db']['prefix']}_currency_rates WHERE created_at='".date("Y-m-d")."'";

$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
$already_exists=$result->GetRows();

if($already_exists){
    //mail('sergi@proservice.ge','HMS - currency_parser.php','already_exists');
}else{
    $system_currency='USD';
    $myFile = "http://currency.boom.ge/all_currency.dat";
    $lines = file($myFile);
    for($i=0; $i<count($lines);$i++){
        $cur_item=explode(';',$lines[$i]);
        $currency_rates[$cur_item[0]]=array('name'=>$cur_item[0],'rate'=>$cur_item[1],'change'=>$cur_item[2],'count'=>$cur_item[3]);
    }
    $query = "INSERT INTO {$_CONF['db']['prefix']}_currency_rates SET
								  currency_rates='" . serialize($currency_rates). "',
                                  created_at='" . date("Y-m-d") . "'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
}

