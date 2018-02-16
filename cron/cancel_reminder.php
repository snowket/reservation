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

require_once("./models/Model.php");
require_once("./models/ActivityLog.model.php");
$ALOG=new ActivityLog();

$hotelSettings = getHotelSettings();
if ($hotelSettings['free_cancelation_time'] <= 0) {
    die("Cancellations are not allowed!");
    exit;

}

$query = "SELECT id,guest_id,booking_id,destination,result,end_date,cancel_notified_at
              FROM {$_CONF['db']['prefix']}_booking_transactions
              WHERE result='OK' AND (destination='online_multi_booking' OR destination='online_single_booking' OR destination='rp_registration')";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
$transactions = $result->GetRows();
if (count($transactions) < 1) {
    die("No Transactions!");
    exit;
}

foreach ($transactions AS $transaction) {
    $b_ids = explode(',', $transaction['booking_id']);
    for ($i = 0; $i < count($b_ids); $i++) {
        $mapped_transactions[$b_ids[$i]] = $transaction['id'];
    }
    $tmp_transactions[$transaction['id']] = $transaction;
}
$transactions = $tmp_transactions;

$query = "SELECT id,guest_id,method,online_payment_type,online_is_paid,check_in,check_out
              FROM {$_CONF['db']['prefix']}_booking
              WHERE method='online' AND online_payment_type='pay_later' AND online_is_paid=1";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
$bookings = $result->GetRows();
if (count($bookings) < 1) {
    die("No Booking!");
    exit;
}

$query = "SELECT id, first_name, last_name, email
              FROM {$_CONF['db']['prefix']}_guests
              WHERE publish=1";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
$guests = $result->GetRows();

foreach ($guests AS $guest) {
    $tmp_guests[$guest['id']] = $guest;
}
$guests = $tmp_guests;


foreach ($bookings AS $booking) {
    $transaction_id = $mapped_transactions[$booking['id']];
    $booking['transaction_id'] = $transaction_id;
    $notifications[$transaction_id]['transaction'] = $transactions[$transaction_id];
    $notifications[$transaction_id]['guest'] = $guests[$booking['guest_id']];
    $notifications[$transaction_id]['bookings'][] = $booking;
    $notifications[$transaction_id]['check_in'] = $booking['check_in'];
    $notifications[$transaction_id]['check_out'] = $booking['check_out'];
}
require_once $hmsDir."/models/MailNotification.class.php";
$mn = new MailNotification();

define("SECONDS_PER_HOUR", 60 * 60);

foreach ($notifications AS $notification) {
    if ($notification['transaction']['cancel_notified_at'] != '0000-00-00 00:00:00') {
        //ukve gagzavnilia
        p('ukve gagzavnilia');
        continue;
    }
    $check_in_mk = strtotime($notification['check_in'] . " " . $hotelSettings['check_in'] . ":00");
    $cancellation_last_chance = $check_in_mk - $hotelSettings['free_cancelation_time'] * SECONDS_PER_HOUR;
    $tr_created_at = strtotime($notification['transaction']['end_date']);
    if ($tr_created_at > $cancellation_last_chance) {
        //dajavshnis dros ukve gasuli iyo gauqmebis vada
        p('dajavshnis dros ukve gasuli iyo gauqmebis vada');
        continue;
    }
    if (time() > ($cancellation_last_chance-$hotelSettings['cancel_reminder']*SECONDS_PER_HOUR) && time() < $check_in_mk) {
        $guest = $notification['guest'];
        $notification_data['cancellation_date'] = date('Y-m-d H:i:s', $cancellation_last_chance);
        $notification_data['booking_number'] = $notification['transaction']['booking_id'];
        $notification_data['check_in'] = $notification['check_in'];
        $notification_data['check_out'] = $notification['check_out'];
        $notification_data['night_stay'] = getDiffBetweenTwoDates($notification['check_in'], $notification['check_out']);
        $notification_data['rooms_count'] = count($notification['bookings']);
        $mn->SendCancelReminderToGuest($guest['email'], $guest['first_name'], $guest['last_name'], $notification_data, LANG);
        changeTransactionCancelNotifiedAt($notification['transaction']['id']);
        $log=array(
            "booking_id"=>$notification_data['booking_number'],
            "transaction_id"=>$notification['transaction']['id'],
            "now"=>time(),
            "cancellation_last_chance"=>$cancellation_last_chance
        );
        $ALOG->addActivityLog("Cancel Reminder Sent", "CRON: Cancel Reminder Sent to ".$guest['email'], 0, $log);
    }else{

    }

}

function changeTransactionCancelNotifiedAt($id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "UPDATE {$_CONF['db']['prefix']}_booking_transactions SET
			 	 cancel_notified_at=NOW()
			 	 WHERE id={$id}";
    $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return true;
}

function getDiffBetweenTwoDates($date1, $date2)
{
    $now = strtotime($date1); // or your date as well
    $your_date = strtotime($date2);
    $datediff = $your_date - $now;
    return floor($datediff / (60 * 60 * 24));
}