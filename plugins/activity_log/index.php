<?
if (!defined('ALLOW_ACCESS')) exit;
$ROOT = dirname(__FILE__);
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
$g_tab = $_GET['tab'] ? '&tab=' . $_GET['tab'] : '';
$SELF = $_SERVER['PHP_SELF'] . "?m=" . $LOADED_PLUGIN['plugin'] . $g_tab;
$SELF_TABS = $_SERVER['PHP_SELF'] . "?m=" . $LOADED_PLUGIN['plugin'];

$SELF_FILTERED = $_SERVER['REQUEST_URI'];
$parts = parse_url($SELF_FILTERED);
$queryParams = array();
parse_str($parts['query'], $queryParams);
unset($queryParams['p']);
$queryString = http_build_query($queryParams);
$SELF_FILTERED = $parts['path'] . '?' . $queryString;


require_once($ROOT . "/lang/" . LANG . ".php");

$TMPL->addVar("SELF", $SELF);
$TMPL->addVar("PLUGIN", $LOADED_PLUGIN['plugin']);
$TMPL->setRoot($ROOT);

switch ($_GET['tab']) {
    case 'activity_log':
        $tab = 'activity_log';
        require_once($ROOT . "/files/activity_log.php");
        break;
    case 'transaction_log':
        $tab = 'transaction_log';
        require_once($ROOT . "/files/transaction_log.php");
        break;
    case 'guests_activity_log':
        $tab = 'guests_activity_log';
        require_once($ROOT . "/files/guests_activity_log.php");
        break;
    default:
        $tab = 'activity_log';
        require_once($ROOT . "/files/activity_log.php");
        break;
}


if (!$LOADED_PLUGIN['restricted']) {
    $_CENTER = pcmsInterface::drawTabs("{$SELF_TABS}&tab=", $TEXT['tabs'], $tab) . $_CENTER;
}

$navbar;
function getTransactionLogs($where=''){
    global $CONN, $FUNC, $_CONF;
    $query="SELECT BT.type,BT.destination,BT.transaction_id,BT.method,BT.amount,BT.postback_message,G.first_name,G.last_name,G.email,BT.user_ip,BT.start_date,BT.end_date,BT.tr_status FROM cms_booking_transactions BT
    LEFT JOIN cms_guests G on BT.guest_id=G.id {$where}
    ORDER BY BT.id DESC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $result=$result->getRows();
    foreach($result as $key=>$value){
        $tmp=unserialize($value['postback_message']);
        if(is_array($tmp)){
            $result[$key]['postback_message']=$tmp;
        }
        else{
            $result[$key]['postback_message']=unserialize($tmp);
        }
    }
//    p($result);exit;
    return $result;
}
function getActivityLogs($where_clause,$excel=false)
{
    global $CONN, $FUNC, $_CONF,$navbar,$SELF_FILTERED;

    $groups=getGroups();
    $groups[0]['title']='CRON';
    $users=getUsers();
    $users[0]['firstname']='CRON';
    $users[0]['group_id']=0;


    $query = "SELECT al.id,al.action,al.description,al.administrator_id,al.ip,al.date,u.firstname,u.lastname,g.title
              FROM {$_CONF['db']['prefix']}_activity_log AS al
                LEFT JOIN {$_CONF['db']['prefix']}_users AS u
                   ON al.administrator_id=u.id
                      LEFT JOIN {$_CONF['db']['prefix']}_groups AS g
                        ON u.group_id=g.id
              WHERE ".$where_clause."
              ORDER BY al.id DESC";
    //p($query);
    if ($excel) {
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    } else {
        $result = $CONN->PageExecute($query, 50, $_GET['p']) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $navbar = $FUNC->DrawPageBar_($SELF_FILTERED . "&p=", $result);
    }

    $logs = $result->GetRows();

    foreach ($logs as $log) {
        $return[$log['id']]=$log;
        $return[$log['id']]['group']=($log['administrator_id']==0)?'CRON':$groups[$users[$log['administrator_id']]['group_id']]['title'];
        $return[$log['id']]['user']=($log['administrator_id']==0)?'CRON':$users[$log['administrator_id']]['firstname']." ".$users[$log['administrator_id']]['lastname'];
    }

    return $return;
}

function getGroups()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT *
              FROM {$_CONF['db']['prefix']}_groups";
    $groups = $CONN->GetAssoc($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $groups;
}

function getUsers()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT id, login, firstname,lastname,email,group_id
              FROM {$_CONF['db']['prefix']}_users";
    $users = $CONN->GetAssoc($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $users;
}

function getGuestsActivityLogs($where_clause,$excel=false)
{
    global $CONN, $FUNC, $_CONF,$navbar,$SELF_FILTERED;

    $query = "SELECT al.id,al.action,al.description,al.guest_id,al.ip,al.date,g.first_name,g.last_name
              FROM {$_CONF['db']['prefix']}_guests_activity_log AS al
                LEFT JOIN {$_CONF['db']['prefix']}_guests AS g
                   ON al.guest_id=g.id
              WHERE ".$where_clause."
              ORDER BY al.id DESC";
    //p($query);
    if ($excel) {
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    } else {
        $result = $CONN->PageExecute($query, 50, $_GET['p']) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $navbar = $FUNC->DrawPageBar_($SELF_FILTERED . "&p=", $result);
    }

    $logs = $result->GetRows();

    foreach ($logs as $log) {
        $return[$log['id']]=$log;
        //$return[$log['id']]['user']=($log['administrator_id']==0)?'CRON':$users[$log['administrator_id']]['firstname']." ".$users[$log['administrator_id']]['lastname'];
    }

    return $return;
}

function getGuests()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT id, login, firstname,lastname,email,group_id
              FROM {$_CONF['db']['prefix']}_guests";
    $users = $CONN->GetAssoc($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $users;
}