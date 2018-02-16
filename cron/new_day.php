<?php
error_reporting(E_ALL && ~E_NOTICE);
set_time_limit(0);
ignore_user_abort(true);
//mail('sergi@proservice.ge','HMS - new_day.php','cronjob started at: '.date("Y-m-d H:i:s"));
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

$query = "SELECT *
              FROM {$_CONF['db']['prefix']}_rooms";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
$rooms = $result->GetRows();

$rooms_states_yesterday = getRoomsStateByDate(date("Y-m-d", time() - 60 * 60 * 24));
$rooms_states_today = getRoomsStateByDate(date("Y-m-d", time()));


foreach ($rooms AS $room) {
    $status = '';
    if (in_array('check_in', $rooms_states_yesterday[$room['id']]) || in_array('in_use', $rooms_states_yesterday[$room['id']])) {
        $status = 'touchup';
    }
    if (in_array('check_out', $rooms_states_today[$room['id']])) {
        $status = 'dirty';
    }
    if ($status == '') {
        $update[$room['housekeeping_status']][] = $room['id'];
    } else {
        $update[$status][] = $room['id'];
    }

}

foreach ($update AS $k => $v) {
    if (count($v) > 0) {
        $query = "UPDATE {$_CONF['db']['prefix']}_rooms SET
                                 housekeeping_status='" . $k . "',
                                 hs_updated_at='" . date("Y-m-d H:i:s") . "'
                                 WHERE id IN (" . implode(',', $v) . ")";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }
}

//mail('sergi@proservice.ge','HMS - new_day.php','cronjob ended at: '.date("Y-m-d H:i:s"));

function getRoomsStateByDate($date)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT B.id,B.room_id,D.booking_id, D.date,D.type
              FROM {$_CONF['db']['prefix']}_booking AS B
              LEFT JOIN {$_CONF['db']['prefix']}_booking_daily AS D
              ON B.id=D.booking_id
              WHERE D.date='" . $date . "'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $states = $result->GetRows();
    foreach ($states AS $state) {
        $roomStates[$state['room_id']][] = $state['type'];
    }
    return $roomStates;
}