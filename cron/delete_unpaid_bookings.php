<?php
error_reporting(E_ALL && ~E_NOTICE);
set_time_limit(0);
ignore_user_abort(true);
define("ALLOW_ACCESS", true);
//mail('sergi@proservice.ge','HMS - new_day.php','cronjob started at: '.date("Y-m-d H:i:s"));
$parts=explode('/hms/',__DIR__);
$hmsDir = $parts[0]."/hms";
$reservationDir = $parts[0];

//*******************************************************//
//*** Authorization  ************************************//
header('Content-Type: text/html; charset=utf-8');
//*******************************************************//
//*** Including base classes ****************************//
require_once($hmsDir . "/config.php");
require_once($hmsDir . "/common.php");
$FUNC = new CommonFunc();

//*******************************************************//
//*** Setting site language  ****************************//
DEFINE('LANG', $FUNC->SetLang("langs_pcms"));
require_once($hmsDir . "/lang/" . LANG . ".php");
require_once($hmsDir . "/classes/adodb/adodb.inc.php");
require_once($hmsDir . "/classes/datavalidator/validator.class.php");
require_once($reservationDir . "/includes/functions.php");


//*******************************************************//
//*** Establishing connection with database *************//
$CONN = &ADONewCONNection($_CONF['db']['type']);
$CONN->connect($_CONF['db']['host'], $_CONF['db']['user'], $_CONF['db']['pass'], $_CONF['db']['name']);
$CONN->debug = 0;
$CONN->setFetchMode(ADODB_FETCH_ASSOC);

$query = "SET NAMES utf8 COLLATE utf8_general_ci";
$CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

require_once($hmsDir ."./models/Model.php");
require_once($hmsDir ."./models/ActivityLog.model.php");
$ALOG=new ActivityLog();


$hotelSettings = getHotelSettings();
require_once($reservationDir . '/ipn/tbc/TbcPayment.class.php');
$tbcPayment = new TbcPayment(
    $_CONF['tbc']['MerchantHandler'],
    $_CONF['tbc']['ClientHandler'],
    $_CONF['tbc']['cert_pass'],
    $_CONF['tbc']['p12_file']
);


$query = "SELECT *
              FROM {$_CONF['db']['prefix']}_booking_transactions
              WHERE destination='online_multi_booking' OR destination='online_single_booking' OR destination='rp_registration'";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
$transactions = $result->GetRows();
foreach($transactions AS $transaction){
    $b_ids=explode(',',$transaction['booking_id']);
    for($i=0; $i<count($b_ids); $i++){
        $mapped_transactions[$b_ids[$i]]=$transaction['id'];
    }
}

$query = "SELECT *
              FROM {$_CONF['db']['prefix']}_booking
              WHERE method='online' AND online_is_paid=0";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
$bookings = $result->GetRows();


foreach ($bookings AS $booking) {
    define("SECONDS_PER_HOUR", 60 * 60);
    $seconds = time() - strtotime($booking['created_at']);
    if ($seconds > (int)$hotelSettings['unpaid_bookings_lifetime'] * 60) {
        deleteBooking($booking['id']);
        changeTransactionResult($mapped_transactions[$booking['id']],'TIMEOUT');
    } else {
       // p($booking['id'].' =>'.$mapped_transactions[$booking['id']]);
    }
}

function changeTransactionResult($id,$result='TIMEOUT')
{
    global $CONN, $FUNC, $_CONF;
    $query = "UPDATE {$_CONF['db']['prefix']}_booking_transactions SET
			 	 result='{$result}',
			 	 comment='BY CRON: delete_unpaid_bookings',
			 	 end_date=NOW()
			 	 WHERE id={$id}";
    $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return true;
}
function deleteBooking($booking_id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking WHERE id=" . $booking_id;
    $booking = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    if (!empty($booking)) {
        $query = "SELECT *
			  FROM {$_CONF['db']['prefix']}_booking_daily
		  	  WHERE booking_id=" . $booking['id'];
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $booking_days = $result->GetRows();

        //DELETE booking_daily_services
        foreach ($booking_days AS $booking_day) {
            $booking_days_ids[] = $booking_day['id'];
        }

        $query = "SELECT *
			  FROM {$_CONF['db']['prefix']}_booking_daily_services
		  	  WHERE booking_daily_id IN (" . implode(", ", $booking_days_ids) . ")";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $daily_services = $result->GetRows();


        $query = "DELETE
			FROM {$_CONF['db']['prefix']}_booking_daily_services
		  	WHERE booking_daily_id IN (" . implode(", ", $booking_days_ids) . ")";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        ///DELETE booking_daily_services

        //DELETE booking_daily
        $query = "DELETE
			FROM {$_CONF['db']['prefix']}_booking_daily
		  	WHERE booking_id=" . $booking['id'];
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        ///DELETE booking_daily

        //DELETE booking
        $query = "DELETE
			FROM {$_CONF['db']['prefix']}_booking
		  	WHERE id=" . $booking['id'];
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        ///DELETE booking

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_cashbacks WHERE booking_id=".$booking['id'];
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, mysql_error());
        $cashbacks = $result->getRows();

        deleteBookingCashback($booking['id']);
        $log['booking'] = $booking;
        $log['booking_days'] = $booking_days;
        $log['daily_services'] = $daily_services;
        $log['cashbacks']=$cashbacks;
        $ALOG->addActivityLog('Delete Booking', 'Cron Deleted Booking', 0, serialize($log));
        return true;
    } else {
        return false;
    }
}



function deleteBookingCashback($booking_id){
    global $CONN, $FUNC, $_CONF;
    $query = "DELETE FROM {$_CONF['db']['prefix']}_booking_cashbacks
                  WHERE booking_id={$booking_id}";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return true;
}