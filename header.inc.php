<?
error_reporting(E_ALL && ~E_NOTICE);
session_start();
define("ALLOW_ACCESS",true);
//----------------------------------------------------------START SECURITY PLUGIN
require_once("./includes/allowed_server_ip_range.php");

require_once("./includes/allow_deny_ips.php");
if($djc9d8g8gd8d8d90==0){
    define("ALLOW_ACCESS",false);
    die('Unexpected Server IP!');
}
$remote_ip=$_SERVER['REMOTE_ADDR'];


if(count($allowed_ips)==0 && count($denied_ips)>0){
    foreach($denied_ips AS $k=>$v){
        if($remote_ip==$k){
            header('Location: ../restricted.php');
        }
    }
}
if(count($denied_ips)==0 && count($allowed_ips)>0){
    $page_access=false;
    foreach($allowed_ips AS $k=>$v){
        if($remote_ip==$k){
            $page_access=true;
        }
    }
    if(!$page_access){
        header('Location: ../restricted.php');
    }
}
//----------------------------------------------------------END SECURITY PLUGIN

//*******************************************************//
//*** Including base classes ****************************//
require_once("./config.php");
require_once("./common.php");
require 'classes/autoload.php';
$FUNC      = new CommonFunc();
require 'classes/PHPMailer/PHPMailerAutoload.php';

$mail_smtp = new PHPMailer;

$mail_smtp->SMTPDebug = 0;                               // Enable verbose debug output

$mail_smtp->isSMTP();                                    // Set mailer to use SMTP
$mail_smtp->Host = $_CONF['SMTP']['Host'];              // Specify main and backup SMTP servers
$mail_smtp->SMTPAuth = true;                              // Enable SMTP authentication
$mail_smtp->Username = $_CONF['SMTP']['Username'];        // SMTP username
$mail_smtp->Password = $_CONF['SMTP']['Password'];                           // SMTP password
$mail_smtp->SMTPSecure = 'smtp';                          // Enable TLS encryption, `ssl` also accepted
$mail_smtp->Port =$_CONF['SMTP']['Port'];
$mail_smtp->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => false
    )
);
$mail_smtp->CharSet = 'UTF-8';

//*******************************************************//
//*** Setting site language  ****************************//
//DEFINE('LANG',$_CONF['langs_pcms_def']);
DEFINE('LANG',$FUNC->SetLang("langs_pcms","cms_lng"));
require_once("lang/".LANG.".php");

require_once("./classes/mailer/mailer.class.php");
require_once("./includes/functions.php");

require_once("./classes/adodb/adodb.inc.php");
require_once("./classes/datavalidator/validator.class.php");
require_once("./classes/imageGD/imageGD.class.php");
require_once("./classes/filemanager/filemanager.class.php");
require_once("./classes/pcmsInterface.php");
require_once("./classes/pcmsTemplate/pcmsTemplate.php");


$VALIDATOR = new Validator();
$GET = $VALIDATOR->ConvertSpecialChars($_GET);
$POST = $VALIDATOR->ConvertSpecialChars($_POST);
$INTERFACE = new pcmsInterface();


//*******************************************************//
//*** Establishing connection with database *************//
$CONN = &ADONewCONNection($_CONF['db']['type']);
$CONN->connect($_CONF['db']['host'], $_CONF['db']['user'], $_CONF['db']['pass'], $_CONF['db']['name']);
$CONN->debug = 0;
$CONN->setFetchMode(ADODB_FETCH_ASSOC);

$query  = "SET NAMES utf8 COLLATE utf8_general_ci";
$CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());

require_once("./models/Model.php");
require_once("./models/ActivityLog.model.php");
$ALOG=new ActivityLog();
if($_SESSION['pcms_user_group']==1){
    define("IS_SUPER_ADMIN",true);
}else{
    define("IS_SUPER_ADMIN",false);
}

if($_POST['action']=='change_current_date' && isset($_POST['current_date']) && $_POST['current_date']!=''){
    $_SESSION['server_date']=$_POST['current_date'];
}
if(isset($_SESSION['server_date'])&&$_SESSION['server_date']!=''){
    define("CURRENT_DATE",$_SESSION['server_date']);
}else{
    define("CURRENT_DATE",date('Y-m-d'));
}
