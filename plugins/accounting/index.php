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

require_once($ROOT . "/lang/" . LANG . ".php");

$TMPL->addVar("SELF", $SELF);
$TMPL->addVar("PLUGIN", $LOADED_PLUGIN['plugin']);
$TMPL->setRoot($ROOT);

$weekDays = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');


if (isset($_GET['tab'])) {
    $tab = $_GET['tab'];
    require_once($ROOT . "/files/" . $tab . ".php");
} else {
    reset($TEXT['tab']);         //Moves array pointer to first record
    $tab = key($TEXT['tab']);     //Returns current key
    header("Location: index.php?m=accounting&tab=" . $tab);
}


//*****************************************************//
//*** DRAW TABS ***************************************//
if (!$LOADED_PLUGIN['restricted']) {
    //$_CENTER = pcmsInterface::drawTabs("{$SELF_TABS}&tab=",$tabs,$tab).$_CENTER;
    $_CENTER = pcmsInterface::drawModernTabs("{$SELF_TABS}&tab=", $TEXT['tab'], $tab) . $_CENTER;
}

function in_out_bokings($fulldate,$day,$month,$year){
    return array($day=>in_out_bokings_day($fulldate),$month=>in_out_bokings_month($year,$month),$year=>in_out_bokings_year($year));
}
function in_out_bokings_day($fulldate)
{
    global $_CONF, $CONN, $FUNC;
    $query="SELECT count(*) as count,BD.type FROM cms_booking_daily BD where BD.date='".$fulldate."' AND  BD.active=1
    AND (BD.type='check_in' || BD.type='check_out') GROUP BY BD.type";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->getRows();
    foreach($data as $k => $v){
        $data[$v['type']]=$v['count'];
        unset($data[$k]);
    }

    return $data;
}
function in_out_bokings_month($year,$month)
{
    global $_CONF, $CONN, $FUNC;
    $query="SELECT count(*) as count,BD.type FROM cms_booking_daily BD where BD.date like '".$year."-".$month."%' AND  BD.active=1
    AND (BD.type='check_in' || BD.type='check_out') GROUP BY BD.type";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->getRows();
    foreach($data as $k => $v){
        $data[$v['type']]=$v['count'];
        unset($data[$k]);
    }
    return $data;
}
function in_out_bokings_year($year)
{
    global $_CONF, $CONN, $FUNC;
    $query="SELECT count(*) as count,BD.type FROM cms_booking_daily BD where BD.date like '".$year."-%' AND  BD.active=1
    AND (BD.type='check_in' || BD.type='check_out') GROUP BY BD.type";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->getRows();
    foreach($data as $k => $v){
        $data[$v['type']]=$v['count'];
        unset($data[$k]);
    }
    return $data;
}

function calc_in_out($fulldate,$day,$month,$year){
    return array($day=>calc_in_out_day($fulldate),$month=>calc_in_out_month($year,$month),$year=>calc_in_out_year($year));
}
function calc_in_out_day($fulldate){
    global $_CONF, $CONN, $FUNC;
    // Accomodation price
    $query="SELECT SUM(BD.price) as price FROM cms_booking_daily BD where BD.date='".$fulldate."' AND  BD.active=1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->fields;
    // Accomodation service_price
    $service="SELECT SUM(BDS.service_price) as service_price FROM cms_booking_daily_services BDS
    LEFT JOIN cms_booking_daily BD ON BD.id=BDS.booking_daily_id
    WHERE BD.active=1 AND BDS.active=1 AND BDS.service_type_id=2 AND BD.date like '".$fulldate."'";
    $service = $CONN->Execute($service) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $service = $service->fields;

    // Service Price
    $query_service="SELECT SUM(BDS.service_price) as service_price FROM cms_booking_daily_services BDS
    LEFT JOIN cms_booking_daily BD ON BD.id=BDS.booking_daily_id
    WHERE BD.active=1 AND BDS.active=1 AND BDS.service_type_id<>2 AND BD.date like '".$fulldate."'";
    $query_service = $CONN->Execute($query_service) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $query_service = $query_service->fields;

$send_data=$data['price']+$service['service_price'];
    return array('price'=>$send_data,'service'=>$query_service['service_price']);
}
function calc_in_out_month($year,$month){
    global $_CONF, $CONN, $FUNC;
    $query="SELECT SUM(BD.price) as price FROM cms_booking_daily BD where BD.date like '".$year."-".$month."%' AND  BD.active=1 ";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->fields;

    $query_service="SELECT SUM(BDS.service_price) as service_price FROM cms_booking_daily_services BDS
    LEFT JOIN cms_booking_daily BD ON BD.id=BDS.booking_daily_id
    WHERE BD.active=1 AND BDS.active=1 AND BDS.service_type_id=2 AND BD.date like '".$year."-".$month."%'";
    $query_service = $CONN->Execute($query_service) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $query_service = $query_service->fields;

    $service="SELECT SUM(BDS.service_price) as service_price FROM cms_booking_daily_services BDS
    LEFT JOIN cms_booking_daily BD ON BD.id=BDS.booking_daily_id
    WHERE BD.active=1 AND BDS.active=1 AND BDS.service_type_id<>2 AND BD.date like '".$year."-".$month."%'";
    $service = $CONN->Execute($service) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $service = $service->fields;


    $send_data=$data['price']+$query_service['service_price'];
    return array('price'=>$send_data,'service'=>$service['service_price']);
}
function calc_in_out_year($year){
    global $_CONF, $CONN, $FUNC;
    $query="SELECT SUM(BD.price) as price FROM cms_booking_daily BD where BD.date like '".$year."-%' AND  BD.active=1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->fields;

    $query_service="SELECT SUM(BDS.service_price) as service_price FROM cms_booking_daily_services BDS
    LEFT JOIN cms_booking_daily BD ON BD.id=BDS.booking_daily_id
    WHERE BD.active=1 AND BDS.active=1 AND BDS.service_type_id=2 AND BD.date like '".$year."-%'";
    $query_service = $CONN->Execute($query_service) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $query_service = $query_service->fields;

    $service="SELECT SUM(BDS.service_price) as service_price FROM cms_booking_daily_services BDS
    LEFT JOIN cms_booking_daily BD ON BD.id=BDS.booking_daily_id
    WHERE BD.active=1 AND BDS.active=1 AND BDS.service_type_id<>2 AND BD.date like '".$year."-%'";
    $service = $CONN->Execute($service) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $service = $service->fields;



    $send_data=$data['price']+$query_service['service_price'];
    return array('price'=>$send_data,'service'=>$service['service_price']);
}


function GetServices($type = 0)
{
    global $_CONF, $CONN, $FUNC;
    $where_clause = ($type != 0) ? "WHERE type_id=" . $type : "";
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services
				 " . $where_clause;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();
    $tmp_data = array();
    foreach ($data as $key => $value) {
        $value['title'] = $FUNC->unpackData($value['title'], LANG);
        $tmp_data[$value['id']] = $value;
    }
    return $tmp_data;
}

function GetMappedSpendingMaterials($start_date, $end_date)
{
    global $_CONF, $CONN, $FUNC;

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_spending_materials
              WHERE date>='" . $start_date . "' AND date<='" . $end_date . "'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $spending_materials = $result->GetRows();
    //p($spending_materials);
    foreach ($spending_materials as $spending_material) {
        $mappedSpendingMaterials[$spending_material['service_type_id']][$spending_material['service_id']][$spending_material['date']][$spending_material['room_id']][] = $spending_material;
    }
    return $mappedSpendingMaterials;
}

function getPeriodArray($from, $to)
{
    $range = array();
    $start = strtotime($from);
    $end = strtotime($to);

    do {
        $range[] = date('Y-m-d', $start);
        $start = strtotime("+ 1 day", $start);
    } while ($start <= $end);

    return $range;
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

function getGuestByID($guest_id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_guests
			  	WHERE id={$guest_id}";
    $guest = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $guest;
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
        $tmp['title'] = $FUNC->unpackData($status['title'], LANG);
        $unpaked_statsuses[$status['id']] = $tmp;
    }
    return $unpaked_statsuses;
}

function getBookingById($booking_id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking WHERE id=" . $booking_id;
    $booking = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $booking;
}

function getGuestsBookingsSummaryState2($filter = '')
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT T.*, G.id_number AS guest_id_number,G.type, G.first_name, G.last_name, G.balance
              FROM {$_CONF['db']['prefix']}_booking AS T,

              {$_CONF['db']['prefix']}_guests AS G
              WHERE  T.guest_id=G.id" . $filter;
    p($query);
    //$query = "SELECT * FROM {$_CONF['db']['prefix']}_booking WHERE 1".$filter;

    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $bookings = $result->GetRows();
    $guests_finances = array();
    $all_guests = getAllGuests();
    foreach ($all_guests AS $guest) {
        $guests_finances[$guest['id']]['debit'] = 0;
        $guests_finances[$guest['id']]['credit'] = 0;
    }
    foreach ($bookings AS $booking) {
        $guests_finances[$booking['responsive_guest_id']]['debit'] = (float)$guests_finances[$booking['responsive_guest_id']]['debit'] + (float)$booking['services_paid_amount'];
        $guests_finances[$booking['responsive_guest_id']]['credit'] = (float)$guests_finances[$booking['responsive_guest_id']]['credit'] + (float)$booking['services_price'];
        $guests_finances[$booking['guest_id']]['debit'] = (float)$guests_finances[$booking['guest_id']]['debit'] + (float)$booking['paid_amount'];
        $guests_finances[$booking['guest_id']]['credit'] = (float)$guests_finances[$booking['guest_id']]['credit'] + (float)$booking['accommodation_price'];
    }
    return $guests_finances;
}

$pageBar;
function getGuestsBookingsSummaryState($where_clause = '', $total_unpaid)
{
    global $CONN, $FUNC, $_CONF, $pageBar, $SELF_FILTERED;

    if ($total_unpaid == 1) {
        $having_clause = "HAVING (credit-debit)>0";
    } elseif ($total_unpaid == 2) {
        $having_clause = "HAVING (credit-debit)=0";
    } elseif ($total_unpaid == 3) {
        $having_clause = "HAVING (credit-debit)<0";
    } else {
        $having_clause = "";
    }


    $query = "
        SELECT T.id,T.guest_id,T.guest_id_number, T.first_name, T.last_name, T.type,T.tax, SUM(T.debit) AS debit, SUM(T.credit) AS credit,T.balance FROM
                     (SELECT G.id, G.id_number AS guest_id_number,G.type,G.tax, G.first_name, G.last_name,G.balance, B.guest_id AS guest_id,B.paid_amount as debit,B.accommodation_price as credit
                      FROM {$_CONF['db']['prefix']}_guests AS G
                        LEFT JOIN {$_CONF['db']['prefix']}_booking AS B
                          ON G.id=B.guest_id
                      WHERE B.active=1" . $where_clause . "
                      UNION ALL
                      SELECT G.id, G.id_number AS guest_id_number,G.type,G.tax, G.first_name, G.last_name,G.balance, B.responsive_guest_id AS guest_id,B.services_paid_amount as debit,B.services_price as credit
                      FROM {$_CONF['db']['prefix']}_guests AS G
                        LEFT JOIN {$_CONF['db']['prefix']}_booking AS B
                          ON G.id=B.responsive_guest_id
                      WHERE B.active=1" . $where_clause . "
                      UNION ALL
                      SELECT G.id, G.id_number AS guest_id_number,G.type,G.tax, G.first_name, G.last_name,G.balance, G.id AS guest_id,0 as debit,0 as credit
                      FROM {$_CONF['db']['prefix']}_guests AS G
                      WHERE 1=1" . $where_clause . ") AS T
        GROUP BY T.id  " . $having_clause." ORDER BY T.id DESC";
    if ($_POST['action'] == 'get_excel') {
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $records = $result->getRows();
    } else {
        //$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $result = $CONN->PageExecute($query, 50, $_GET['p']) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $records = $result->getRows();

        $pageBar = $FUNC->DrawPageBar_($SELF_FILTERED . "&p=", $result);
    }
    return $records;
}


function changeStatus($booking_id, $status_id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
			 	 status_id={$status_id}
			 	 WHERE id={$booking_id}";
    $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
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

function getAllGuests()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT id, id_number, first_name,last_name,type,country, balance FROM {$_CONF['db']['prefix']}_guests";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $guests = $result->GetRows();
    foreach ($guests as $guest) {
        $tmp['id'] = $guest['id'];
        $tmp['id_number'] = $guest['id_number'];
        $tmp['first_name'] = $guest['first_name'];
        $tmp['last_name'] = $guest['last_name'];
        $tmp['balance'] = $guest['balance'];
        $tmp['type'] = $guest['type'];
        $tmp['country'] = $guest['country'];
        $unpaked_guests[$tmp['id']] = $tmp;
    }
    return $unpaked_guests;
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

function updateGuestBalance($guest_id, $amount)
{
    global $CONN, $FUNC, $_CONF;
    $query = "UPDATE {$_CONF['db']['prefix']}_guests SET
			 	 balance={$amount}
			 	 WHERE id={$guest_id}";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $result;
}

function getAllExtraServices()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services WHERE publish =1 AND type in('extra','fridge') AND in_use=1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $extra_services = $result->GetRows();
    foreach ($extra_services as $extra_service) {
        $tmp['id'] = $extra_service['id'];
        $tmp['price'] = $extra_service['price'];
        $tmp['title'] = $FUNC->unpackData($extra_service['title'], LANG);
        $unpaked_extra_services[] = $tmp;
    }
    return $unpaked_extra_services;
}

function getAllBookingTransactions($filter = '')
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT *
              FROM {$_CONF['db']['prefix']}_booking_transactions $filter";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $transactions = $result->GetRows();
    return $transactions;
}

function getDailyAnnualIncomeReport($year, $month)
{
    global $CONN, $FUNC, $_CONF, $TEXT;


    $months[0] = date('Y-m-d', strtotime('-2 month', strtotime($year . '-' . $month . '-01')));
    $months[1] = date('Y-m-d', strtotime('-1 month', strtotime($year . '-' . $month . '-01')));
    $months[2] = $year . '-' . $month . '-01';
    $months[3] = date('Y-m-d', strtotime('+1 month', strtotime($year . '-' . $month . '-01')));

    $query = "SELECT T.id,T.destination,T.amount,T.end_date
              FROM {$_CONF['db']['prefix']}_booking_transactions T,
          {$_CONF['db']['prefix']}_booking B
              WHERE B.id = T.booking_id AND result='OK' AND type='global' AND amount<>0 AND DATE(end_date)>='" . $months[0] . "' AND DATE(end_date)<='" . $months[3] . "'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $transactions = $result->GetRows();
    $report = array();
    for ($i = 0; $i < 3; $i++) {
        $pices = explode('-', $months[$i]);
        $days_count = cal_days_in_month(CAL_GREGORIAN, sprintf('%01d', $pices[1]), $pices[0]);
        for ($d = 1; $d <= $days_count; $d++) {
            $day = ($d < 10) ? '0' . $d : $d;
            $report[$pices[0] . '-' . $pices[1]][$day] = array(
                'day' => $pices[0] . '-' . $pices[1] . '-' . $day,
                'accommodation_in' => 0,
                'services_in' => 0,
                'out' => 0,
                'balance' => 0
            );
        }
    }
    foreach ($transactions AS $transaction) {
        $year = date("Y", strtotime($transaction['end_date']));
        $month = date("m", strtotime($transaction['end_date']));
        $day = date("d", strtotime($transaction['end_date']));
        if ($transaction['amount'] > 0) {
            if ($transaction['destination'] == 'extra-service') {
                $report[$year . '-' . $month][$day]['services_in'] += $transaction['amount'];
            } else {
                $report[$year . '-' . $month][$day]['accommodation_in'] += $transaction['amount'];
            }
        } else {
            $report[$year . '-' . $month][$day]['out'] += $transaction['amount'];
        }
        $report[$year . '-' . $month][$day]['balance'] += $transaction['amount'];
    }
    return $report;
}

function getAnnualIncomeReport($year)
{
    global $CONN, $FUNC, $_CONF, $TEXT;
    $from = $year - 2;
    $to = $year + 1;
    $query = "SELECT id,type,destination,amount,result,end_date
              FROM {$_CONF['db']['prefix']}_booking_transactions
              WHERE result='OK' AND type='global' AND amount<>0 AND DATE(end_date)>='" . $from . "-01-01' AND DATE(end_date)<'" . $to . "-01-01'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $transactions = $result->GetRows();

    $report = array();
    for ($y = $from; $y < $to; $y++) {
        foreach ($TEXT['months'] AS $k => $v) {
            $report[$y][$k] = array(
                'month' => $v . ' ' . $y,
                'accommodation_in' => 0,
                'services_in' => 0,
                'out' => 0,
                'balance' => 0
            );
        }
    }
    foreach ($transactions AS $transaction) {
        $year = date("Y", strtotime($transaction['end_date']));
        $month = date("m", strtotime($transaction['end_date']));

        if ($transaction['amount'] > 0) {
            if ($transaction['destination'] == 'extra-service') {
                $report[$year][$month]['services_in'] += $transaction['amount'];
            } else {
                $report[$year][$month]['accommodation_in'] += $transaction['amount'];
            }
        } else {
            $report[$year][$month]['out'] += $transaction['amount'];
        }

        $report[$year][$month]['balance'] += $transaction['amount'];
    }
    return $report;
}
function getBudgetIncomeReport($year)
{
  global $CONN, $FUNC, $_CONF, $TEXT;
  $query = "SELECT bd.booking_id,bd.date,bd.price,bd.type,b.guest_id
            FROM {$_CONF['db']['prefix']}_booking_daily AS bd,
                 {$_CONF['db']['prefix']}_booking AS b
            WHERE b.active=1 AND bd.date>='" . ($year - 1) . "-01-01' AND bd.date<'" . ($year + 1) . "-01-01' AND bd.type<>'check_out' AND bd.booking_id=b.id";
  $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
  $booking_days = $result->GetRows();

  $query = "SELECT bd.date, SUM(bds.service_price) AS amount,b.id
            FROM {$_CONF['db']['prefix']}_booking_daily AS bd,
                 {$_CONF['db']['prefix']}_booking_daily_services AS bds,
                  {$_CONF['db']['prefix']}_booking b
            WHERE b.active=1 AND bds.service_type_id<>2 AND bd.active=1 AND bds.active=1 AND bd.date>='" . ($year - 1) . "-01-01' AND bd.date<'" . ($year + 1) . "-01-01' AND bd.id=bds.booking_daily_id AND b.id=bd.booking_id
            GROUP BY bd.date";
  $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
  $services = $result->GetRows();
  $tmp = array();
  foreach ($services as $service) {
      $tmp[$service['date']] = $service['amount'];
  }
  $services = $tmp;

  $query = "SELECT bd.date, SUM(bds.service_price) AS amount,b.id
            FROM {$_CONF['db']['prefix']}_booking_daily AS bd,
                 {$_CONF['db']['prefix']}_booking_daily_services AS bds,
                  {$_CONF['db']['prefix']}_booking b
            WHERE b.active=1 AND bds.service_type_id=2 AND bd.active=1 AND bds.active=1 AND bd.date>='" . ($year - 1) . "-01-01' AND bd.date<'" . ($year + 1) . "-01-01' AND bd.id=bds.booking_daily_id AND b.id=bd.booking_id
            GROUP BY bd.date";
  $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
  $service_acc = $result->GetRows();
  $tmp = array();
  foreach ($service_acc as $service) {
      $tmp[$service['date']] = $service['amount'];
  }
  $service_acc = $tmp;

  $query = "SELECT id,tax
            FROM {$_CONF['db']['prefix']}_guests";
  $guests = $CONN->GetAssoc($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

  $report = array();
  $tmp = array(
      'acc_with_tax' => 0,
      'acc_no_tax' => 0,
      'services' => 0,
      'last_12_month_with_tax' => 0,
      'last_12_month_no_tax' => 0
  );
  foreach ($TEXT['months'] AS $k => $v) {
      $tmp['month'] = $v;
      $report[($year - 1)][$k] = $report[$year][$k] = $tmp;
  }
  foreach ($booking_days AS $booking_day) {
      $t = explode('-', $booking_day['date']);
      if ($guests[$booking_day['guest_id']]['tax'] == 1) {
          $report[$t[0]][$t[1]]['acc_with_tax'] += $booking_day['price'];
          $report[$t[0]][$t[1]]['acc_with_tax'] += (double)$service_acc[$booking_day['date']];
          $service_acc[$booking_day['date']]=0;
      } else {
          $report[$t[0]][$t[1]]['acc_no_tax'] += $booking_day['price'];
          $report[$t[0]][$t[1]]['acc_no_tax']+= (double)$service_acc[$booking_day['date']];
          $service_acc[$booking_day['date']]=0;
      }
      $report[$t[0]][$t[1]]['services'] += (double)$services[$booking_day['date']];
      $services[$booking_day['date']]=0;



  }


  //gasuli wlis totals calke loopshi imito vitvli, imitom rom shevamciro iteraciebis raodenoba
  $last_year_total = 0;
  foreach ($report[($year - 1)] AS $m => $r) {
      $last_year_total += $r['acc_with_tax'];
      $last_year_total += $r['acc_no_tax'];
      $last_year_total += $r['services'];
  }

  foreach ($report[$year] AS $m => $r) {
      $last_year_total -= $report[($year - 1)][$m]['acc_with_tax'];
      $last_year_total -= $report[($year - 1)][$m]['acc_no_tax'];
      $last_year_total -= $report[($year - 1)][$m]['services'];
      $income = $r['acc_with_tax'] + $r['acc_no_tax'] + $r['services'];
      $last_year_total += $income;
      $summary_report[$year][$m]['month'] = $r['month'];
      $summary_report[$year][$m]['acc_income_tax_included'] = number_format($r['acc_with_tax'],2,'.','');
      $summary_report[$year][$m]['acc_income_tax_free'] = $r['acc_no_tax'];
      $summary_report[$year][$m]['services_income'] = $r['services'];
      $summary_report[$year][$m]['income'] = number_format($income,2,'.','');
      $summary_report[$year][$m]['tax'] = round(($r['acc_with_tax'] / 118) * 18, 2);
      $summary_report[$year][$m]['last_12_month_income'] = number_format($last_year_total,2,'.','');
      $summary_report[($year - 1)][$m]['month'] = $report[($year - 1)][$m]['month'];
      $summary_report[($year - 1)][$m]['income'] = number_format(($report[($year - 1)][$m]['acc_with_tax'] + $report[($year - 1)][$m]['acc_with_tax'] + $report[($year - 1)][$m]['services']),2,'.','');
      $summary_report[($year - 1)][$m]['tax'] = 0;
      $summary_report[($year - 1)][$m]['last_12_month_income'] = 0;
  }

  return $summary_report;
}
function getAccrualBasedIncomeReport($year)
{
    global $CONN, $FUNC, $_CONF, $TEXT;
    $query = "SELECT bd.booking_id,bd.date,bd.price,bd.type,b.guest_id
              FROM {$_CONF['db']['prefix']}_booking_daily AS bd,
                   {$_CONF['db']['prefix']}_booking AS b
              WHERE b.active=1 AND bd.date>='" . ($year - 1) . "-01-01' AND bd.date<'" . ($year + 1) . "-01-01' AND bd.type<>'check_out' AND bd.booking_id=b.id";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $booking_days = $result->GetRows();

    $query = "SELECT bd.date, SUM(bds.service_price) AS amount,b.id
              FROM {$_CONF['db']['prefix']}_booking_daily AS bd,
                   {$_CONF['db']['prefix']}_booking_daily_services AS bds,
                    {$_CONF['db']['prefix']}_booking b
              WHERE b.active=1 AND bds.service_type_id<>2 AND bd.active=1 AND bds.active=1 AND bd.date>='" . ($year - 1) . "-01-01' AND bd.date<'" . ($year + 1) . "-01-01' AND bd.id=bds.booking_daily_id AND b.id=bd.booking_id
              GROUP BY bd.date";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $services = $result->GetRows();

    $tmp = array();
    foreach ($services as $service) {
        $tmp[$service['date']] = $service['amount'];
    }
    $services = $tmp;

    $query = "SELECT bd.date, SUM(bds.service_price) AS amount,b.id
              FROM {$_CONF['db']['prefix']}_booking_daily AS bd,
                   {$_CONF['db']['prefix']}_booking_daily_services AS bds,
                    {$_CONF['db']['prefix']}_booking b
              WHERE b.active=1 AND bds.service_type_id=2 AND bd.active=1 AND bds.active=1 AND bd.date>='" . ($year - 1) . "-01-01' AND bd.date<'" . ($year + 1) . "-01-01' AND bd.id=bds.booking_daily_id AND b.id=bd.booking_id
              GROUP BY bd.date";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $service_acc = $result->GetRows();

    $tmp = array();
    foreach ($service_acc as $service) {
        $tmp[$service['date']] = $service['amount'];
    }
    $service_acc = $tmp;

    $query = "SELECT id,tax
              FROM {$_CONF['db']['prefix']}_guests";
    $guests = $CONN->GetAssoc($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $report = array();
    $tmp = array(
        'acc_with_tax' => 0,
        'acc_no_tax' => 0,
        'services' => 0,
        'last_12_month_with_tax' => 0,
        'last_12_month_no_tax' => 0
    );
    foreach ($TEXT['months'] AS $k => $v) {
        $tmp['month'] = $v;
        $report[($year - 1)][$k] = $report[$year][$k] = $tmp;
    }
    foreach ($booking_days AS $booking_day) {
        $t = explode('-', $booking_day['date']);
        if ($guests[$booking_day['guest_id']]['tax'] == 1) {
            $report[$t[0]][$t[1]]['acc_with_tax'] += $booking_day['price'];
            $report[$t[0]][$t[1]]['acc_with_tax'] += (double)$service_acc[$booking_day['date']];
            $service_acc[$booking_day['date']]=0;
        } else {
            $report[$t[0]][$t[1]]['acc_no_tax'] += $booking_day['price'];
            $report[$t[0]][$t[1]]['acc_no_tax']+= (double)$service_acc[$booking_day['date']];
            $service_acc[$booking_day['date']]=0;
        }
        $report[$t[0]][$t[1]]['services'] += (double)$services[$booking_day['date']];
        $services[$booking_day['date']]=0;



    }


    //gasuli wlis totals calke loopshi imito vitvli, imitom rom shevamciro iteraciebis raodenoba
    $last_year_total = 0;
    foreach ($report[($year - 1)] AS $m => $r) {
        $last_year_total += $r['acc_with_tax'];
        $last_year_total += $r['acc_no_tax'];
        $last_year_total += $r['services'];
    }

    foreach ($report[$year] AS $m => $r) {
        $last_year_total -= $report[($year - 1)][$m]['acc_with_tax'];
        $last_year_total -= $report[($year - 1)][$m]['acc_no_tax'];
        $last_year_total -= $report[($year - 1)][$m]['services'];
        $income = $r['acc_with_tax'] + $r['acc_no_tax'] + $r['services'];
        $last_year_total += $income;
        $summary_report[$year][$m]['month'] = $r['month'];
        $summary_report[$year][$m]['acc_income_tax_included'] = number_format($r['acc_with_tax'],2,'.','');
        $summary_report[$year][$m]['acc_income_tax_free'] = $r['acc_no_tax'];
        $summary_report[$year][$m]['services_income'] = $r['services'];
        $summary_report[$year][$m]['income'] = number_format($income,2,'.','');
        $summary_report[$year][$m]['tax'] = round(($r['acc_with_tax'] / 118) * 18, 2);
        $summary_report[$year][$m]['last_12_month_income'] = number_format($last_year_total,2,'.','');
        $summary_report[($year - 1)][$m]['month'] = $report[($year - 1)][$m]['month'];
        $summary_report[($year - 1)][$m]['income'] = number_format(($report[($year - 1)][$m]['acc_with_tax'] + $report[($year - 1)][$m]['acc_with_tax'] + $report[($year - 1)][$m]['services']),2,'.','');
        $summary_report[($year - 1)][$m]['tax'] = 0;
        $summary_report[($year - 1)][$m]['last_12_month_income'] = 0;
    }

    return $summary_report;

}

function getJoinedAllBookingTransactions($where_clause = '')
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT T.*, G.id_number AS guest_id_number,G.type, G.first_name, G.last_name,B.active,R.name as R_name,R.floor
              FROM {$_CONF['db']['prefix']}_booking_transactions AS T,{$_CONF['db']['prefix']}_guests AS G,{$_CONF['db']['prefix']}_booking AS B,{$_CONF['db']['prefix']}_rooms AS R
              WHERE T.result='OK' AND R.id=B.room_id AND B.active=1 AND B.id=T.booking_id AND T.guest_id=G.id" . $where_clause ." ORDER BY T.id DESC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $transactions = $result->GetRows();
    return $transactions;
}

$navbar;
function getAllBookingCashBacks($filter = '', $excel = false)
{
    global $CONN, $FUNC, $_CONF, $SELF_FILTERED, $navbar;
    $query = "SELECT *
              FROM {$_CONF['db']['prefix']}_booking_cashbacks $filter";
    if ($excel) {
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    } else {
        $result = $CONN->PageExecute($query, 50, $_GET['p']) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $navbar = $FUNC->DrawPageBar_($SELF_FILTERED . "&p=", $result);
    }

    $cashbacks = $result->GetRows();
    return $cashbacks;
}

function getBookingTransactions($booking_id)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT id,administrator_id,amount, start_date
              FROM {$_CONF['db']['prefix']}_booking_transactions
              WHERE booking_id =" . $booking_id . " AND result='OK'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $transactions = $result->GetRows();
    return $transactions;
}

function addCashBackTransaction($guest_id, $booking_id, $amount, $comment)
{
    global $CONN, $FUNC, $_CONF;
    $query = "INSERT INTO {$_CONF['db']['prefix']}_booking_transactions
                SET type='global' ,
                    guest_id='" . $guest_id . "' ,
                    booking_id='" . $booking_id . "',
                    destination='accommodation',
                    transaction_id='',
                    reason_tr_id='',
                    payment_method_id=2,
                    method='tbc',
                    amount='" . $amount . "',
                    currency_type='" . $_CONF[''] . "',
                    tr_status='FINISHED',
                    start_date=NOW(),
                    end_date=NOW(),
                    result='OK',
                    postback_message='',
                    user_ip='" . $_SERVER['REMOTE_ADDR'] . "',
                    comment='" . $comment . "'";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
}

function addBookingTransaction($type, $guest_id, $booking_id, $administrator_id, $amount = 0, $payment_method_id, $destination)
{
    global $CONN, $FUNC, $_CONF;
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
    updateBookingFinances($booking_id);
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
}

function getUsageByCitizenship($start_date, $end_date)
{
    global $CONN, $FUNC, $_CONF;

    $checked_in_guests = array();
    $nights = array(
        'local' => array(
            'guests'=>0,
            'nights'=>0
        ),
        'global' => array(
            'guests'=>0,
            'nights'=>0
        )
    );

    $query = "SELECT G.id,G.country,C." . LANG . " AS country, C.id AS country_id
              FROM {$_CONF['db']['prefix']}_guests AS G
              LEFT JOIN {$_CONF['db']['prefix']}_country AS C
              ON G.country=C.id";
    $guests = $CONN->GetAssoc($query);
    $query = "SELECT booking_id,COUNT(*) AS count
              FROM {$_CONF['db']['prefix']}_booking_daily
              WHERE active=1 AND date>='" . $start_date . "' AND date<='" . $end_date . "' AND type<>'check_out'
              GROUP BY booking_id";
    $booking_days = $CONN->GetRows2($query, 'booking_id', 'count');
    if (!empty($booking_days)) {
        $query = "SELECT B.id,CONCAT_WS('|', B.guest_id, B.foreign_guest_ids) as foreign_guest_ids,B.check_in,B.check_out
                  FROM {$_CONF['db']['prefix']}_booking B
                  LEFT JOIN cms_guests G on B.guest_id=G.id
                  WHERE B.active=1 AND B.id IN(" . implode(',', array_keys($booking_days)) . ")";
        $bookings = $CONN->getRows2($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        foreach ($bookings as $booking) {
            $booking_guest_ids = explode('|', $booking['foreign_guest_ids']);
            if ($booking['check_in'] >= $start_date && $booking['check_in'] <= $end_date) {
                for ($i = 0; $i < count($booking_guest_ids); $i++) {
                    if ((int)$booking_guest_ids[$i] != 0) {
                        $country = $guests[$booking_guest_ids[$i]]['country'];
                        if ($country == '') {
                            $country = "unknown";
                        }
                        $checked_in_guests[$country] = (int)$checked_in_guests[$country] + 1;
                    }
                }
            }

        }

        foreach ($booking_days as $booking_id=>$booking_days_count) {
            $booking_guest_ids = explode('|', $bookings[$booking_id]['foreign_guest_ids']);
            for ($i = 0; $i < count($booking_guest_ids); $i++) {
                if ((int)$booking_guest_ids[$i] != 0) {
                    if ((int)$guests[$booking_guest_ids[$i]]['country_id'] == 273) {
                        $nights['local']['guests']=$nights['local']['guests']+1;
                        $nights['local']['nights']=$nights['local']['nights']+$booking_days_count;
                    }else{
                        $nights['global']['guests']=$nights['global']['guests']+1;
                        $nights['global']['nights']=$nights['global']['nights']+$booking_days_count;
                    }

                }
            }
        }
    }

    $report = array();
    $report['guests'] = $checked_in_guests;
    $report['nights'] = $nights;
    return $report;
}

function getJoinedBookings($where_clause = '')
{
     global $CONN, $FUNC, $_CONF, $pageBar, $SELF_FILTERED;
    $query = "SELECT B.*,R.floor,RT.title as type_title, G.id_number AS guest_id_number,G.type, G.first_name, G.last_name
              FROM {$_CONF['db']['prefix']}_booking AS B
              LEFT JOIN {$_CONF['db']['prefix']}_guests AS G
              ON B.guest_id=G.id
              LEFT JOIN {$_CONF['db']['prefix']}_rooms AS R
              ON B.room_id=R.id
              LEFT JOIN {$_CONF['db']['prefix']}_rooms_manager AS RM
              ON RM.id=R.common_id
              LEFT JOIN {$_CONF['db']['prefix']}_room_types AS RT
              ON RM.type_id=RT.id
              WHERE 1=1 AND dbl_res!=1 " . $where_clause." ".$order_clause;
    if ($_POST['action'] == 'get_excel') {
        $bookings = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    } else {
        $bookings = $CONN->PageExecute($query, 50, $_GET['p']) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $pageBar = $FUNC->DrawPageBar_($SELF_FILTERED . "&p=", $bookings);
    }

    return $bookings->getRows();
}

function getAllServices($typeIds = '')
{
    global $CONN, $FUNC, $_CONF;
    if ($typeIds != '') {
        $where_clause = " AND type_id IN (" . $typeIds . ")";
    }
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services WHERE publish =1 " . $where_clause;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $services = $result->GetRows();
    foreach ($services as $service) {
        $tmp['id'] = $service['id'];
        $tmp['price'] = $service['price'];
        $tmp['type_id'] = $service['type_id'];
        $tmp['title'] = $FUNC->unpackData($service['title'], LANG);
        $unpaked_services[$tmp['id']] = $tmp;
    }
    return $unpaked_services;
}
function GetMaxFloor(){
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT MAX(floor) as floor
              FROM {$_CONF['db']['prefix']}_rooms";
    $results = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $out = $results->fields;
    return $out;
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
function GetAllRooms()
{
    global $_CONF, $CONN, $FUNC;
    $query = "SELECT *,id as ind FROM {$_CONF['db']['prefix']}_rooms
				WHERE publish=1";
    $result = $CONN->GetAssoc($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    //$data=$result->GetRows();
    return $result;
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

function getServicesTypes()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services_types ORDER BY id ASC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $types = $result->GetRows();
    foreach ($types as $type) {
        $tmp['id'] = $type['id'];
        $tmp['title'] = $FUNC->unpackData($type['title'], LANG);
        $unpaked_types[$tmp['id']] = $tmp;
    }
    return $unpaked_types;
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
function restrictedRooms($day){
  global $CONN,$FUNC,$_CONF;
  $qs="SELECT room_id as id FROM cms_room_restrictions WHERE date(from_date)<='".$day."' AND date(to_date)>='".$day."'";
  $qs = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
  $qs=$qs->getRows();
  return $qs;
}
function getAvailableRoomsCount($day)
{
    global $CONN,$FUNC,$_CONF;
    $qs=restrictedRooms($day);
    $id_array=array();
    if(!empty($qs)){
      foreach($qs as $q){
      array_push($id_array,$q['id']);
      }
      $idsn=implode("','",$id_array);
      $query = "SELECT * FROM cms_rooms R WHERE publish=1 AND id NOT IN ('".$idsn."')";
    }else{
      $query = "SELECT * FROM cms_rooms R WHERE publish=1";
    }
    $result = $CONN->GetRows2($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $result;
}
function getAllUsers($fields_array=array())
{
    global $CONN,$FUNC,$_CONF;
    if(empty($fields_array)){
        $fields="*";
    }else{
        $fields=implode(',',$fields_array);
    }
    $query = "SELECT ".$fields."
                  FROM {$_CONF['db']['prefix']}_users
                  ORDER BY id DESC";
    $result = $CONN->GetRows2($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $result;
}
