<?
error_reporting(E_ALL && ~E_NOTICE);
define("ALLOW_ACCESS", true);

//header('Content-Type: text/html; charset=utf-8');
header('Content-Type: application/json');
require_once("./config.php");
require_once("./common.php");
$FUNC = new CommonFunc();

//*******************************************************//
//*** Setting site language  ****************************//
require_once("lang/geo.php");

require_once("./classes/adodb/adodb.inc.php");
require_once("./classes/datavalidator/validator.class.php");
$VALIDATOR = new Validator();

//*******************************************************//
//*** Establishing connection with database *************//
$CONN = &ADONewCONNection($_CONF['db']['type']);
$CONN->connect($_CONF['db']['host'], $_CONF['db']['user'], $_CONF['db']['pass'], $_CONF['db']['name']);
$CONN->debug = 0;
$CONN->setFetchMode(ADODB_FETCH_ASSOC);

$query = "SET NAMES utf8 COLLATE utf8_general_ci";
$CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
require_once("./includes/functions.php");

$hotelSettings = getHotelSettings();
$getChanellSettings=getChanellSettings();
if($_POST){
  $desc=serialize($_POST);
  $type="Booking";
  $date_in=date('Y-m-d');
  $query="INSERT INTO cms_channel_logs SET type='Booking',desc='".$desc."',date_in='".$date_in."'";
  $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__,$CONN->ErrorMsg().$query);
}else{
  $query="SELECT * FROM cms_channel_logs ORDER BY created_at";
  $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__,$CONN->ErrorMsg().$query);
  $result=$result->getRows();
dd($result);
}
