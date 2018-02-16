<?
if (!defined('ALLOW_ACCESS')) exit;

$mons = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
$ROOT = dirname(__FILE__);
$g_tab = $_GET['tab'] ? '&tab=' . $_GET['tab'] : '';
$SELF = $_SERVER['PHP_SELF'] . "?m=" . $LOADED_PLUGIN['plugin'] . $g_tab;
$SELF_TABS = $_SERVER['PHP_SELF'] . "?m=" . $LOADED_PLUGIN['plugin'];
$imgDIR = $_CONF['path']['script_upload'] . $LOADED_PLUGIN['plugin'];

$SELF_FILTERED = $_SERVER[REQUEST_URI];
$parts = parse_url($SELF_FILTERED);
$queryParams = array();
parse_str($parts['query'], $queryParams);
unset($queryParams['p']);
$queryString = http_build_query($queryParams);
$SELF_FILTERED = $parts['path'] . '?' . $queryString;
$hotel_settings = getHotelSettings();

require_once($ROOT . "/lang/" . LANG . ".php");

$TMPL->addVar("SELF", $SELF);
$TMPL->addVar("TMPL_hotel_settings", $hotel_settings);
$TMPL->addVar("PLUGIN", $LOADED_PLUGIN['plugin']);
$TMPL->setRoot($ROOT);

$weekDays =
    array(
        'Mon',
        'Tue',
        'Wed',
        'Thu',
        'Fri',
        'Sat',
        'Sun',
    );

$query = "SELECT type_id, count(*) AS num FROM {$_CONF['db']['prefix']}_rooms_manager
          GROUP BY type_id";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
while ($row = $result->FetchRow()) {
    $counts[$row['type_id']] = $row['num'];
}
$room_types = GetRoomTypes();
$hotel_bloks = GetBlocks();

//tu jer araa sheqmnili blokebi an otaxebis tipebi gamoachinos mesiji
if (count($room_types) == 0 || count($hotel_bloks) == 0) {
    $is_ready = 0;
    require_once($ROOT . "/files/first_step.php");
} else {
    // $is_ready = 1;
    // switch ($_GET['tab']) {
    //     case 'booking':
    //         $tab = 'booking';
    //         require_once($ROOT . "/files/booking.php");
    //         break;
    //     case 'booking_list':
    //         $tab = 'booking_list';
    //         require_once($ROOT . "/files/booking_list.php");
    //         break;
    //     case 'booking_dbl':
    //         $tab = 'booking_dbl';
    //         require_once($ROOT . "/files/booking_dbl.php");
    //         break;
    //     case 'feeding':
    //         $tab = 'feeding';
    //         require_once($ROOT . "/files/feeding.php");
    //         break;
    //     case 'nightreport':
    //         $tab = 'nightreport';
    //         require_once($ROOT . "/files/nightreport.php");
    //         break;
    //     default:
    //         $tab = 'booking';
    //         require_once($ROOT . "/files/booking.php");
    //         break;
    // }
    if (isset($_GET['tab'])) {
    $tab = $_GET['tab'];
    require_once($ROOT . "/files/" . $tab . ".php");
} else {
    reset($TEXT['tab']);         //Moves array pointer to first record
    $tab = key($TEXT['tab']);     //Returns current key
    header("Location: index.php?m=booking_management&tab=" . $tab);
}

    //*** Right side - Categories Tree  *******************//
    //$TMPL->ParseIntoVar($_RIGHT,'search');
    //$TMPL->addVar('TMPL_counts',$counts);
    //$TMPL->addVar('TMPL_cats',$room_types);
    //$TMPL->ParseIntoVar($_RIGHT,'categories_right');
}
if (!$LOADED_PLUGIN['restricted']) {
    //$_CENTER = pcmsInterface::drawTabs("{$SELF_TABS}&tab=",$tabs,$tab).$_CENTER;
    $_CENTER = pcmsInterface::drawModernTabs("{$SELF_TABS}&tab=", $TEXT['tab'], $tab) . $_CENTER;
}
//*****************************************************//
//*** DRAW TABS ***************************************//
// if (!$LOADED_PLUGIN['restricted'] && $is_ready) {
//     $tabs = array();
//     $tabs['booking'] = $TEXT['tab']['booking'];
//     $tabs['booking_list'] = $TEXT['tab']['booking_list'];
//     $tabs['booking_dbl'] = $TEXT['tab']['booking_dbl'];
//     $tabs['feeding'] = $TEXT['tab']['feeding'];
//     $tabs['nightreport'] = $TEXT['tab']['nightreport'];
//     $_CENTER = pcmsInterface::drawTabs("{$SELF_TABS}&tab=", $tabs, $tab) . $_CENTER;
// }


function GetServices($type = 0)
{
    global $_CONF, $CONN, $FUNC;
    if ($type != 0) {
        $where_clause = " AND type_id='" . $type . "'";
    }
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services
				WHERE publish=1" . $where_clause . " ORDER BY id ASC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();
    foreach ($data as $service) {
        $services[$service['id']] = $service;
        $services[$service['id']]['title'] = $FUNC->unpackData($service['title'], LANG);
    }
    return $services;
}


function GetRoomCapacity()
{
    global $_CONF, $CONN, $FUNC;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_capacity
				WHERE publish=1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();

    foreach ($data as $key => $value) {
        $data[$key]['title'] = $FUNC->unpackData($value['title'], LANG);
    }
    return $data;
}


function GetBlocks($id = '')
{
    global $_CONF, $CONN, $FUNC;
    if ($id) {
        $q_add = ' AND id=' . (int)$id;
    }
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_hotel_blocks
				WHERE publish=1 {$q_add}";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();
    foreach ($data as $key => $value) {
        $data[$key]['title'] = $FUNC->unpackData($value['title'], LANG);
    }
    return $data;
}

function getSettings()
{
    global $_CONF, $CONN, $FUNC;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_hotel_settings WHERE publish='1' AND pluginid='' ORDER BY orderid,id";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();
    foreach ($data AS $setting) {
        $tmp['title'] = $setting['title'];
        $tmp['value'] = $setting['value'];
        $settings[$setting['input_name']] = $tmp;
    }
    return $settings;
}

function GetRooms($id = '')
{
    global $_CONF, $CONN, $FUNC;
    if ($id) {
        $q_add = ' AND common_id=' . (int)$id;
    }
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms
				WHERE publish=1 {$q_add}";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();
    return $data;
}

function GetAllRooms()
{
    global $_CONF, $CONN, $FUNC;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms
				WHERE publish=1";
    $result = $CONN->GetAssoc($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    //$data=$result->GetRows();
    return $result;
}

function GetRoomTypes($id = '')
{
    global $_CONF, $CONN, $FUNC;
    if ($id) {
        $q_add = ' AND id=' . (int)$id;
    }
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_types
				WHERE publish=1 {$q_add}";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();
    foreach ($data as $key => $value) {
        $data[$key]['title'] = $FUNC->unpackData($value['title'], LANG);
    }
    return $data;
}

function getAllStatuses()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_statuses ORDER BY id ASC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $statuses = $result->GetRows();
    foreach ($statuses as $status) {
        $tmp['id'] = $status['id'];
        $tmp['color'] = $status['color'];
        $tmp['title'] = $FUNC->unpackData($status['title'], LANG);
        $unpaked_statsuses[$tmp['id']] = $tmp;
    }
    return $unpaked_statsuses;
}

function getServicesTypes()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services_types ORDER BY id ASC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $types = $result->GetRows();
    foreach ($types as $type) {
        $tmp['id'] = $type['id'];
        $tmp['title'] = $type['title_'.LANG];
        $unpaked_types[$tmp['id']] = $tmp;
    }
    return $unpaked_types;
}

function getBookingById($booking_id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking WHERE active=1 AND id=" . $booking_id;
    $booking = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $booking;
}

//updateAllBookingFinances();
function updateAllBookingFinances()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT *
              FROM {$_CONF['db']['prefix']}_booking";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $bookings = $result->GetRows();
    foreach ($bookings AS $booking) {
        updateBookingFinances($booking['id']);
    }
}

function getRoomTypeByID($room_id = 0)
{
    global $CONN, $FUNC, $_CONF;
    if ($room_id > 0) {
        $where_clause = "WHERE R.id=" . $room_id;
    } else {
        $where_clause = "";
    }
    $lang=(isset($_GET['lang'])?$_GET['lang']:LANG);
    $query = "SELECT R.id,R.name,RM.type_id,RM.introimg,RT.title
              FROM {$_CONF['db']['prefix']}_rooms AS R
              LEFT JOIN {$_CONF['db']['prefix']}_rooms_manager AS RM
              ON R.common_id=RM.id
              LEFT JOIN {$_CONF['db']['prefix']}_room_types AS RT
              ON RM.type_id=RT.id " . $where_clause;
    $results = $CONN->GetAssoc($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $rooms = array();
    foreach ($results AS $id => $room) {
        $rooms[$id] = $room;
        $rooms[$id]['title'] = $FUNC->unpackData($room['title'], $lang);
    }
    return $rooms;
}
function GetMaxFloor(){
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT MAX(floor) as floor
              FROM {$_CONF['db']['prefix']}_rooms";
    $results = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $out = $results->fields;
    return $out;
}

function archiveBooking($booking_id,$setoption=0,$booking_type=false,$comment=''){
    global $CONN, $FUNC, $_CONF;
    if($setoption==0){
        $query="UPDATE {$_CONF['db']['prefix']}_booking SET active=0,status_id=13,dl_coment='".$comment."' WHERE id=".$booking_id;
        $archive = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $booking_daily="SELECT id FROM {$_CONF['db']['prefix']}_booking_daily
                    WHERE booking_id=".$booking_id;
        $booking_daily_id = $CONN->Execute($booking_daily) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $booking_daily_id=$booking_daily_id->getRows();
        $booking_daily_id = array_map(function($n) {
            return $n['id'];
        }, $booking_daily_id);
        $query="UPDATE {$_CONF['db']['prefix']}_booking_daily SET active=0 WHERE booking_id=".$booking_id;
        $archive = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $query="UPDATE {$_CONF['db']['prefix']}_booking_daily_services SET active=0 WHERE booking_daily_id IN (".implode(',',$booking_daily_id).")";
        $archive = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }
    else{
        if($booking_type){

        }else{

        }
    }
}


function getJoinedBookings($where_clause = '',$order_clause = '')
{
    global $CONN, $FUNC, $_CONF, $pageBar, $SELF_FILTERED;
    $query = "SELECT B.*,R.floor, G.id_number AS guest_id_number,G.type, G.first_name, G.last_name
              FROM {$_CONF['db']['prefix']}_booking AS B
              LEFT JOIN {$_CONF['db']['prefix']}_guests AS G
              ON B.guest_id=G.id
              LEFT JOIN {$_CONF['db']['prefix']}_rooms AS R
              ON B.room_id=R.id
              WHERE 1=1 AND dbl_res!=1 " . $where_clause." ".$order_clause;
    if ($_POST['action'] == 'get_excel') {
        $bookings = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    } else {
        $bookings = $CONN->PageExecute($query, 50, $_GET['p']) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $pageBar = $FUNC->DrawPageBar_($SELF_FILTERED . "&p=", $bookings);
    }

    return $bookings->getRows();
}
function getDblJoinedBookings($where_clause = '',$order_clause = '')
{
    global $CONN, $FUNC, $_CONF, $pageBar, $SELF_FILTERED;
    $query = "SELECT B.*,R.floor, G.id_number AS guest_id_number,G.type, G.first_name, G.last_name
              FROM {$_CONF['db']['prefix']}_booking AS B
              LEFT JOIN {$_CONF['db']['prefix']}_guests AS G
              ON B.guest_id=G.id
              LEFT JOIN {$_CONF['db']['prefix']}_rooms AS R
              ON B.room_id=R.id
              WHERE 1=1 AND dbl_res=1 " . $where_clause." ".$order_clause;
    if ($_POST['action'] == 'get_excel') {
        $bookings = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    } else {
        $bookings = $CONN->PageExecute($query, 50, $_GET['p']) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $pageBar = $FUNC->DrawPageBar_($SELF_FILTERED . "&p=", $bookings);
    }
    return $bookings->getRows();
}

function getInvoiceByID($invoice_id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT *
              FROM {$_CONF['db']['prefix']}_invoices
              WHERE id=" . $invoice_id;
    $results = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $invoices = $results->getRows();

    if (count($invoices) == 1) {
        return $invoices[0];
    } else {
        return false;
    }
}

function getRoomsCount()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT C.id, C.type_id, C.block_id, COUNT(R.id) AS count
              FROM {$_CONF['db']['prefix']}_rooms_manager AS C
              LEFT JOIN {$_CONF['db']['prefix']}_rooms AS R
              ON C.id=R.common_id
              GROUP BY C.type_id";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $tmp_data = $result->GetRows();
    $return_array = array();
    foreach ($tmp_data AS $tmp) {
        $return_array[$tmp['block_id']][$tmp['type_id']] = (int)$return_array[$tmp['block_id']][$tmp['type_id']] + (int)$tmp['count'];
    }

    return $return_array;
}

function changeStatus($booking_id, $status_id)
{
    global $CONN, $FUNC, $_CONF,$ALOG;
    $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
			 	 status_id={$status_id}
			 	 WHERE id={$booking_id}";
    $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $ALOG->addActivityLog("Booking Status Change", "Administrator Changed Booking[".$booking_id."] Status to Status[".$status_id."]", $_SESSION['pcms_user_id'], mysql_real_escape_string($query));
}

function getAllPaymentMethods()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_payment_methods WHERE publish =1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $extra_services = $result->GetRows();
    foreach ($extra_services as $extra_service) {
        $tmp['id'] = $extra_service['id'];
        $tmp['title'] = $FUNC->unpackData($extra_service['title'], LANG);
        $unpaked_extra_services[$tmp['id']] = $tmp;
    }
    return $unpaked_extra_services;
}
function getDblBookings(){
     global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_payment_methods WHERE publish =1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $extra_services = $result->GetRows();
    foreach ($extra_services as $extra_service) {
        $tmp['id'] = $extra_service['id'];
        $tmp['title'] = $FUNC->unpackData($extra_service['title'], LANG);
        $unpaked_extra_services[$tmp['id']] = $tmp;
    }
    return $unpaked_extra_services;
}
function getAllServices($typeIds = '')
{
    global $CONN, $FUNC, $_CONF;
    if ($typeIds != '') {
        $where_clause = " AND type_id IN (" . $typeIds . ")";
    }
    $lang=(isset($_GET['lang'])?$_GET['lang']:LANG);

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services WHERE 1 =1 " . $where_clause." ORDER BY price ASC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $services = $result->GetRows();


    foreach ($services as $service) {
        $tmp['id'] = $service['id'];
        $tmp['price'] = $service['price'];
        $tmp['type_id'] = $service['type_id'];
        $tmp['title'] = $FUNC->unpackData($service['title'], $lang);
        $unpaked_services[$tmp['id']] = $tmp;
    }
    return $unpaked_services;
}

function getBookingTransactions($booking_id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT id,administrator_id,amount, start_date,destination,payment_method_id
              FROM {$_CONF['db']['prefix']}_booking_transactions
              WHERE booking_id =" . $booking_id . " AND pia=1 AND result='OK'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $transactions = $result->GetRows();
    return $transactions;
}

function getGuestTransactions($guest_id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT *
              FROM {$_CONF['db']['prefix']}_booking_transactions
              WHERE guest_id =" . $guest_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $transactions = $result->GetRows();
    return $transactions;
}

function addBookingTransaction($type, $guest_id, $booking_id, $administrator_id, $amount = 0, $payment_method_id, $destination)
{
    global $CONN, $FUNC, $_CONF, $ALOG;
    $query = "INSERT INTO {$_CONF['db']['prefix']}_booking_transactions
                SET type='" . $type . "' ,
                    guest_id='" . $guest_id . "' ,
                    booking_id=" . $booking_id . ",
                    administrator_id=" . $administrator_id . ",
                    transaction_id='',
                    destination='" . $destination . "',
                    payment_method_id=" . $payment_method_id . ",
                    method='administrator',
                    amount='" . $amount . "',
                    tr_status='FINISHED',
                    start_date=NOW(),
                    end_date=NOW(),
                    result='OK',
                    postback_message='',
                    user_ip='" . $_SERVER['REMOTE_ADDR'] . "'";

    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $transaction_id=(int)$CONN->Insert_ID();
    updateBookingFinances($booking_id);
    $ALOG->addActivityLog("Add Booking Transaction", "Administrator Added Transaction[".$transaction_id."] toBooking[".$booking_id."]", $_SESSION['pcms_user_id'], mysql_real_escape_string($query));
    return $transaction_id;
}

function addFullBookingTransaction($guest_id, $booking_id, $participates_in_accounting, $destination, $transaction_id, $reason_tr_id = '', $amount = 0, $currency_type, $currency_coef, $currency_rates, $tr_result, $postback_message, $comment)
{
    global $CONN, $FUNC, $_CONF,$ALOG;
    $query = "INSERT INTO {$_CONF['db']['prefix']}_booking_transactions
                SET type='global' ,
                    guest_id='" . $guest_id . "' ,
                    booking_id='" . $booking_id . "',
                    pia=" . $participates_in_accounting . ",
                    destination='" . $destination . "',
                    transaction_id='" . $transaction_id . "',
                    reason_tr_id='" . $reason_tr_id . "',
                    payment_method_id=3,
                    method='tbc',
                    amount='" . $amount . "',
                    currency_type='" . $currency_type . "',
                    currency_coef='" . $currency_coef . "',
                    currency_rates='" . $currency_rates . "',
                    tr_status='FINISHED',
                    start_date=NOW(),
                    end_date=NOW(),
                    result='" . $tr_result . "',
                    postback_message='" . $postback_message . "',
                    user_ip='" . $_SERVER['REMOTE_ADDR'] . "',
                    comment='" . $comment . "'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $transaction_id=(int)$CONN->Insert_ID();

    $ALOG->addActivityLog("Add Booking Transaction (Full)", "Administrator Added Transaction[".$transaction_id."] toBooking[".$booking_id."]", $_SESSION['pcms_user_id'], mysql_real_escape_string($query));
    return $transaction_id;
}

function addHotelBalance($guest_id, $booking_id, $administrator_id, $amount = 0, $comment)
{
    global $CONN, $FUNC, $_CONF, $ALOG;
    $query = "INSERT INTO {$_CONF['db']['prefix']}_hotel_balance
                SET
                    guest_id='" . $guest_id . "' ,
                    booking_id=" . $booking_id . ",
                    administrator_id=" . $administrator_id . ",
                    ip='" . $_SERVER['REMOTE_ADDR'] . "',
                    amount='" . $amount . "',
                    date=NOW(),
                    comment='" . $comment . "'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $balance_id=(int)$CONN->Insert_ID();
    $ALOG->addActivityLog("Add Amount To Hotel Balance", "Administrator Added Amount To Hotel Balance[".$balance_id."]", $_SESSION['pcms_user_id'], mysql_real_escape_string($query));
    return $balance_id;
}

function updateBookingFinances($booking_id)
{
    global $CONN, $FUNC, $_CONF;
    $booking_accommodation_price = 0;
    $booking_services_price = 0;
    $paid_amount = 0;
    $services_paid_amount = 0;

    $query = "SELECT id, price
                FROM {$_CONF['db']['prefix']}_booking_daily
                WHERE booking_id=" . $booking_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $booking_days = $result->GetRows();
    if (count($booking_days) < 2) {
        return false;
    }
    foreach ($booking_days AS $booking_day) {
        $booking_accommodation_price += (float)$booking_day['price'];
        $days_ids[] = $booking_day['id'];
    }
    $days_ids[] = 0;
    $tmp_arr = implode(", ", $days_ids);

    $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_booking_daily_services
			  	WHERE booking_daily_id IN ($tmp_arr)";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $daily_services = $result->GetRows();

    foreach ($daily_services AS $service) {
        if (in_array($service['service_type_id'], array(2, 9))) {
            $booking_accommodation_price += (float)$service['service_price'];
        } else {
            $booking_services_price += (float)$service['service_price'];
        }
    }


    $query = "SELECT *
                FROM {$_CONF['db']['prefix']}_booking_transactions
                WHERE booking_id=" . $booking_id . " AND result='OK'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $transactions = $result->GetRows();

    foreach ($transactions AS $transaction) {
        if ($transaction['destination'] == 'accommodation') {
            $paid_amount += (float)$transaction['amount'];
        } elseif ($transaction['destination'] == 'extra-service') {
            $services_paid_amount += (float)$transaction['amount'];
        } else {

        }
    }

    $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
			 	 paid_amount={$paid_amount},
			 	 services_paid_amount={$services_paid_amount},
			 	 accommodation_price={$booking_accommodation_price},
			 	 services_price={$booking_services_price}
			 	 WHERE id={$booking_id}";
    $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $CONN->Affected_Rows();
}

function addNewCashBack($type, $guest_id, $affiliate_id, $booking_id, $cashBack, $date, $comment)
{
    global $CONN, $FUNC, $_CONF, $ALOG;
    $query = "INSERT INTO {$_CONF['db']['prefix']}_booking_cashbacks
                SET type='" . $type . "' ,
                    guest_id=" . $guest_id . ",
                    affiliate_id=" . $affiliate_id . ",
                    booking_id=" . $booking_id . ",
                    total_cashback_amount=" . $cashBack . ",
                    paid_cashback_amount=0,
                    date='" . $date . "',
                    created_at=NOW(),
                    comment='" . $comment . "'";
    $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $cash_back_id=(int)$CONN->Insert_ID();
    $ALOG->addActivityLog("Add CashBack", "Administrator Added", $_SESSION['pcms_user_id'], mysql_real_escape_string($query));
    return $cash_back_id;
}

function getGuestByID($guest_id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_guests
			  	WHERE id={$guest_id}";
    $guest = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $guest;
}

function getAllGuest()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_guests";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $guests = $result->GetRows();
    foreach ($guests as $guest) {
        $mapped_guests[$guest['id']] = $guest;
    }
    return $mapped_guests;
}

function deleteDailyService($id)
{
    global $CONN, $FUNC, $_CONF,$ALOG;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily_services
			  	WHERE id={$id}";
    $dailyService = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily
			  	WHERE id={$dailyService['booking_daily_id']}";
    $day = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    if ($day['date'] >= $_SESSION['server_date']) {
        $query = "delete from {$_CONF['db']['prefix']}_booking_daily_services where id={$id}";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $ALOG->addActivityLog("Delete Daily Service", "Administrator Deleted Daily Service[".$id."][".$dailyService["service_title"]."] Booking[".$day["booking_id"]."]", $_SESSION['pcms_user_id'], mysql_real_escape_string(serialize($dailyService)));
    }

}

function updateGuestBalance($guest_id, $amount)
{
    global $CONN, $FUNC, $_CONF, $ALOG;
    $query = "UPDATE {$_CONF['db']['prefix']}_guests SET
			 	 balance={$amount}
			 	 WHERE id={$guest_id}";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $ALOG->addActivityLog("Update Guest Balane", "Administrator Updated Guest[".$guest_id."] Balance=".$amount, $_SESSION['pcms_user_id'], "");
    return $result;
}

function getRoomByID($id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_rooms
			  	WHERE id={$id}";
    $room = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $room;
}

function changeCheckinCheckout($booking_id, $new_checkin, $new_checkout,$food=0)
{
    global $CONN, $FUNC, $_CONF, $ALOG;
    $booking = getBookingById($booking_id);
    if($new_checkin==''){
        $new_checkin=$booking['check_in'];
    }
    if($new_checkout==''){
        $new_checkout=$booking['check_out'];
    }
    if ($booking['check_in'] < date('Y-m-d') && $booking['check_in'] != $new_checkin) {
        p('case 1');
        return false;
    }
    if ($booking['check_out'] < date('Y-m-d') && $booking['check_out'] != $new_checkout) {
        p('case 2');
        return false;
    }
    $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_booking
			  	WHERE room_id={$booking['room_id']} AND id<>" . $booking_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $other_bookings = $result->GetRows();
    foreach ($other_bookings as $other_booking) {
        if ($other_booking['check_in'] == $new_checkin) {
            p('case 3');
            return false;
        }
        if ($other_booking['check_in'] < $new_checkin && $other_booking['check_out'] > $new_checkin) {
            p('case 4');
            return false;
        }
        if ($other_booking['check_out'] == $new_checkout) {
            p('case 5');
            return false;
        }
        if ($other_booking['check_in'] < $new_checkout && $other_booking['check_out'] > $new_checkout) {
            p('case 6');
            return false;
        }
        if ($other_booking['check_in'] > $new_checkin && $other_booking['check_out'] < $new_checkout) {
            p('case 7');
            return false;
        }

    }
    $p_count=$booking['adult_num']+$booking['child_num'];
    $room = getRoomByID($booking['room_id']);
    $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_room_prices
			  	WHERE common_id=" . $room['common_id'] . " AND date>='" . $new_checkin . "' AND date<='" . $new_checkout . "'";

    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $new_days_prices = mapArrayByProp($result->GetRows(), 'date');

    $current_days = createDateRangeArray($booking['check_in'], $booking['check_out']);
    $new_days = createDateRangeArray($new_checkin, $new_checkout);
    // p(count($new_days_prices)."=".count($new_days));
    if (count($new_days_prices) != count($new_days)) {
        //fasebi araa yvela dgeze sheyvanili
        p('case 8');
        return false;
    }
    for ($i = 0; $i < count($current_days); $i++) {
        if (!in_array($current_days[$i], $new_days)) {
            $days_to_remove[] = $current_days[$i];
        }
    }

    $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_booking_daily
			  	WHERE booking_id=" . $booking['id'];
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $booking_current_days = $result->GetRows();
    $com=$booking_current_days[0]['comment'];
    foreach ($booking_current_days AS $booking_current_day) {
        if (in_array($booking_current_day['date'], $days_to_remove)) {
            deleteBookingDayWithServices($booking_current_day['id']);
        }
    }
    $mapped_booking_current_days = mapArrayByProp($booking_current_days, 'date');
    $mapped_booking_current_days_dates = mapArrayByProp($booking_current_days, 'date', true);
    for ($i = 0; $i < count($new_days); $i++) {

        $daily_price = $new_days_prices[$new_days[$i]]['price'] - (($new_days_prices[$new_days[$i]]['price'] / 100) * $new_days_prices[$new_days[$i]]['discount']);
        $daily_price+=$food*$p_count;
        if ($new_days[$i] == $new_checkin) {
            $type = 'check_in';
        } elseif ($new_days[$i] == $new_checkout) {
            $type = 'check_out';
            $daily_price = 0;
        } else {
            $type = 'in_use';
        }

        if (!in_array($new_days[$i], $mapped_booking_current_days_dates)) {
            addNewBookingDay($booking['id'], $new_days[$i], $type, $daily_price, '');
        } else {
            //update
            if ($mapped_booking_current_days[$new_days[$i]]['type'] == 'check_out') {
                $query = "UPDATE {$_CONF['db']['prefix']}_booking_daily SET
			 	 type='" . $type . "',
			 	 price=" . $daily_price . ",
         comment='".$com."'
			 	 WHERE id=" . $mapped_booking_current_days[$new_days[$i]]['id'];
            } else {
                $daily_price = ($type == 'check_out') ? 0 : $mapped_booking_current_days[$new_days[$i]]['price'];
                $query = "UPDATE {$_CONF['db']['prefix']}_booking_daily SET
			 	 type='" . $type . "',
			 	 price=" . $daily_price . "
			 	 WHERE id=" . $mapped_booking_current_days[$new_days[$i]]['id'];
            }
            //p($query);
            $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        }
    }
    $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
			 	 check_in='" . $new_checkin . "',
			 	 check_out='" . $new_checkout . "'
			 	 WHERE id=" . $booking_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    updateBookingFinances($booking_id);
    $ALOG->addActivityLog("Change Booking Checkin Checkout", "Administrator Changed Booking[".$booking_id."] Checkin[".$new_checkin."] Checkout[".$new_checkout."]", $_SESSION['pcms_user_id'], mysql_real_escape_string(serialize($booking)));
    return true;
}

function deleteBookingDayWithServices($booking_day_id)
{
    global $CONN, $FUNC, $_CONF,$ALOG;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily_services WHERE booking_daily_id=". $booking_day_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $services = $result->getRows();

    if(count($services)>0){
        $query = "DELETE FROM {$_CONF['db']['prefix']}_booking_daily_services
			  WHERE booking_daily_id=" . $booking_day_id;
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    }

    $query = "DELETE FROM {$_CONF['db']['prefix']}_booking_daily
              WHERE id={$booking_day_id}";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $ALOG->addActivityLog("Delete Booking Day With Services", "Delete Booking Day[".$booking_day_id."] With Services", $_SESSION['pcms_user_id'], mysql_real_escape_string(serialize($services)));

}

function createDateRangeArray($strDateFrom, $strDateTo)
{
    $aryRange = array();
    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom < $iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}

function addNewBookingDay($booking_id, $date, $type, $price, $comment)
{
    global $CONN, $FUNC, $_CONF,$ALOG;
    $query = "INSERT INTO {$_CONF['db']['prefix']}_booking_daily set
			      booking_id = {$booking_id},
				  date = '{$date}',
				  mk_time='" . mktime() . "',
				  type='{$type}',
				  price={$price},
				  comment='{$comment}'";
    $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $booking_day_id=(int)$CONN->Insert_ID();
    //$ALOG->addActivityLog("Add New Booking Day", "Add New Booking Day[".$booking_day_id."]", $_SESSION['pcms_user_id'],"");
    return $booking_day_id;
}



function deleteBooking($booking_id)
{
    global $CONN, $FUNC, $_CONF,$ALOG;
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

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_cashbacks WHERE booking_id=" . $booking['id'];
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, mysql_error());
        $cashbacks = $result->getRows();

        deleteBookingCashback($booking['id']);

        $log['POST'] = $_POST;
        $log['GET'] = $_GET;
        $log['booking'] = $booking;
        $log['booking_days'] = $booking_days;
        $log['daily_services'] = $daily_services;
        $log['cashbacks'] = $cashbacks;


        $ALOG->addActivityLog('Delete Double Booking', 'Administrator Deleted Booking', $_SESSION['pcms_user_id'], serialize($log));
        return true;
    } else {
        return false;
    }
}

function mapArrayByProp($arr, $prop, $only_props = false)
{
    $mappedArray = array();
    foreach ($arr AS $item) {
        if ($only_props) {
            if (!in_array($item[$prop], $mappedArray)) {
                $mappedArray[] = $item[$prop];
            }

        } else {
            $mappedArray[$item[$prop]] = $item;
        }

    }
    return $mappedArray;
}

function addInvoice($invoice_identifier)
{
    global $CONN, $FUNC, $_CONF;

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_invoices
              WHERE uniq_identifier ='" . $invoice_identifier . "'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, mysql_error());
    $invoices = $result->getRows();

    if (count($invoices) == 0) {
        $query = "INSERT INTO {$_CONF['db']['prefix']}_invoices SET
				  uniq_identifier ='" . $invoice_identifier . "'";
        $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $invoice_id = (int)$CONN->Insert_ID();
    } elseif (count($invoices) == 1) {
        $invoice_id = $invoices[0]['id'];
    } else {
        $invoice_id = 0;
        $FUNC->ServerError(__FILE__, __LINE__, 'multiple invoice identifiers (' . $invoice_identifier . ')');
    }
    return $invoice_id;
}

function addBlobToInvoice($invoice_id,$blob)
{
    global $CONN, $FUNC, $_CONF;
    $query = "UPDATE {$_CONF['db']['prefix']}_invoices SET
              data ='" . $blob . "'
              WHERE id=".$invoice_id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return true;
}

function addNoteToInvoice($invoice_id,$note, $email_1, $email_2='')
{
    global $CONN, $FUNC, $_CONF,$VALIDATOR,$ALOG;
    $query = "UPDATE {$_CONF['db']['prefix']}_invoices SET
    			 	 note='{$VALIDATOR->qstr($note)}',
    			 	 email_1='{$email_1}',
    			 	 email_2='{$email_2}'
              WHERE id=".$invoice_id;
    $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $ALOG->addActivityLog('Add Note To Invoice', 'Add Note To Invoice['.$invoice_id.']', $_SESSION['pcms_user_id'], mysql_real_escape_string($note));
    return true;
}

function addForeignGuestToBooking($booking_id, $foreign_guest_id)
{
    global $CONN, $FUNC, $_CONF, $ALOG;

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking
              WHERE id ='" . $booking_id . "'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, mysql_error());
    $bookings = $result->getRows();

    if (count($bookings) == 1) {
        $booking = $bookings[0];
        $foreign_guest_ids = explode('|', $booking['foreign_guest_ids']);
        $new_foreign_guest_ids = '|';
        if (!in_array($foreign_guest_id, $foreign_guest_ids)) {
            for ($i = 0; $i < count($foreign_guest_ids); $i++) {
                if ($foreign_guest_ids[$i] != '') {
                    $new_foreign_guest_ids .= $foreign_guest_ids[$i] . '|';
                }
            }
            $new_foreign_guest_ids .= $foreign_guest_id . '|';
            $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
        			 	 foreign_guest_ids='{$new_foreign_guest_ids}'
        			 	 WHERE id={$booking_id}";
            //p($query);
            $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $ALOG->addActivityLog('Add Guest To Booking', 'Administrator Added Guest['.$foreign_guest_id.'] to Booking['.$booking_id.']', $_SESSION['pcms_user_id'], '');
            return true;
        }
    } else {
        $FUNC->ServerError(__FILE__, __LINE__, 'No booking Exists with id (' . $booking_id . ')');
        return false;
    }

}

function removeForeignGuestFromBooking($booking_id, $foreign_guest_id)
{
    global $CONN, $FUNC, $_CONF, $ALOG;

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking
              WHERE id ='" . $booking_id . "'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, mysql_error());
    $bookings = $result->getRows();

    if (count($bookings) == 1) {
        $booking = $bookings[0];
        $foreign_guest_ids = explode('|', $booking['foreign_guest_ids']);
        $new_foreign_guest_ids = '|';
        if (in_array($foreign_guest_id, $foreign_guest_ids)) {
            for ($i = 0; $i < count($foreign_guest_ids); $i++) {
                if ($foreign_guest_ids[$i] != '' && $foreign_guest_ids[$i] != $foreign_guest_id) {
                    $new_foreign_guest_ids .= $foreign_guest_ids[$i] . '|';
                }
            }
            $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
        			 	 foreign_guest_ids='{$new_foreign_guest_ids}'
        			 	 WHERE id={$booking_id}";

            $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $ALOG->addActivityLog('Remove Guest from Booking', 'Administrator Removed Guest['.$foreign_guest_id.'] from Booking['.$booking_id.']', $_SESSION['pcms_user_id'], '');
            return true;
        }else{
            $FUNC->ServerError(__FILE__, __LINE__, "Foreign Guest with ID=".$foreign_guest_id." Not exists in Booking With ID=".$booking_id);
            return false;
        }
    } else {
        $FUNC->ServerError(__FILE__, __LINE__, 'No booking Exists with id (' . $booking_id . ')');
        return false;
    }
}
function getDiffBetweenTwoDates($date1, $date2)
{
    $now = strtotime($date1); // or your date as well
    $your_date = strtotime($date2);
    $datediff = $your_date - $now;
    return floor($datediff / (60 * 60 * 24));
}

function deleteBookingCashback($booking_id)
{
    global $CONN, $FUNC, $_CONF, $ALOG;
    $query = "DELETE FROM {$_CONF['db']['prefix']}_booking_cashbacks
                  WHERE booking_id={$booking_id}";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $ALOG->addActivityLog('Remove Cashback', 'Administrator removed Cashback from Booking['.$booking_id.']', $_SESSION['pcms_user_id'], '');
    return true;
}


function getAllCountries()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_country WHERE publish =1 ORDER BY sort_id DESC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $result->GetRows();
}
