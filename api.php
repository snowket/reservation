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
DEFINE('LANG', $_GET['lng']);
require_once("lang/" . LANG . ".php");

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

if ($_GET['cmd'] == 'get_free_rooms') {
    $POST = $VALIDATOR->ConvertSpecialChars($_POST);
    $check_in = $POST['check_in'];
    $check_out = $POST['check_out'];
    $guests_count = $POST['guests_count'];
    $days_count = getDiffBetweenTwoDates($check_in, $check_out);

    if ($check_in < date('Y-m-d') || $check_in == '') {
        echo "invalid chekin day";
        exit;
    }

    if ($check_out <= $check_in || $check_out == '') {
        echo "invalid checkout day";
        exit;
    }

    if ($guests_count < 1) {
        echo "invalid guests count";
        exit;
    }


    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_services
                    WHERE type_id=6 AND publish=1
                    ORDER BY price ASC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $food_prices = $result->getRows();

    foreach ($food_prices AS $food_price) {
        $food['id'] = $food_price['id'];
        $food['title'] = $FUNC->unpackData($food_price['title'], LANG);
        $food['total_price'] = $food_price['price'];
        $mapped_food_prices[$food_price['id']] = $food;
    }

    $query = "SELECT common_id, price FROM {$_CONF['db']['prefix']}_room_prices
                    WHERE date='" . $check_in . "'
                    GROUP BY common_id";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $first_day_prices = $result->getRows();

    foreach ($first_day_prices AS $first_day_price) {
        $mapped_common_fd_prices[$first_day_price['common_id']] = $first_day_price;
    }

//    $query = "SELECT common_id, SUM(price) AS total_price,price as sc_price,discount, COUNT(*) AS priced_days_count FROM {$_CONF['db']['prefix']}_room_prices
//                    WHERE date>='" . $check_in . "' AND date<'" . $check_out . "'
//                    GROUP BY common_id";
//    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
//    $prices = $result->getRows();
//
//
//
//    foreach ($prices AS $price) {
//        $mapped_common_prices[$price['common_id']] = $price;
//    }


    $query = "SELECT common_id,price,discount,date FROM {$_CONF['db']['prefix']}_room_prices
                    WHERE date>='" . $check_in . "' AND date<'" . $check_out . "'
                    ORDER BY date ASC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $prices = $result->getRows();
    $mapArrayPriceSum=array();
    foreach($prices as $price){
        $tmp=$price;
        $tmp_day_price=$price['price'];
        $mapArrayPriceSum[$price['common_id']]['total_price']+=(($price['discount']!=0)?($price['price']-($price['price']*$price['discount']/100)):($price['price']));
        $mapped_common_prices[$price['common_id']] = $price;
        $mapped_common_prices[$price['common_id']]['total_price'] = $mapArrayPriceSum[$price['common_id']]['total_price'];
        $mapped_common_prices[$price['common_id']]['priced_days_count'] =$days_count;
    }

    #---------------start used rooms
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking
                WHERE active=1 AND (
                          (check_in>='" . $check_in . "' AND check_in<'" . $check_out . "') OR
                          (check_out>'" . $check_in . "' AND check_out<='" . $check_out . "') OR
                          (check_in<='" . $check_in . "' AND check_out>='" . $check_out . "')
                      )";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $matched_bookings = $result->getRows();

    $used_rooms_list = array();
    foreach ($matched_bookings AS $matched_booking) {
        if (!in_array($matched_booking['room_id'], $used_rooms_list)) {
            $used_rooms_list[] = $matched_booking['room_id'];
        }
    }
    #---------------end used rooms

    #---------------start restricted rooms
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_restrictions
              WHERE '".$check_in."'<=to_date AND '".$check_out."'>from_date";

    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $matched_restrictions = $result->getRows();

    $restricted_rooms_list = array();
    foreach ($matched_restrictions AS $matched_restriction) {
        if (!in_array($matched_restriction['room_id'], $restricted_rooms_list)) {
            $restricted_rooms_list[] = $matched_restriction['room_id'];
        }
    }
    #---------------end restricted rooms


    if (count($used_rooms_list) > 0) {
        $where_clause = " AND id NOT IN (" . implode(",", $used_rooms_list) . ")";
    } else {
        $where_clause = "";
    }

    if (count($restricted_rooms_list) > 0) {
        $where_clause .= " AND id NOT IN (" . implode(",", $restricted_rooms_list) . ")";
    } else {
        $where_clause .= "";
    }

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms
              WHERE for_web=1 AND for_local=1 AND publish=1" . $where_clause . "
              ORDER BY floor ASC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $free_rooms_list = $result->getRows();

    $free_rooms_common_ids = array();
    foreach ($free_rooms_list AS $free_room) {
        if (!in_array($free_room['common_id'], $free_rooms_common_ids)) {
            $free_rooms_common_ids[] = $free_room['common_id'];
        }
        $free_rooms[$free_room['id']] = array('id' => $free_room['id'], 'common_id' => $free_room['common_id'], 'name' => $free_room['name'], 'floor' => $free_room['floor']);
    }

    if ($common_id != 0) {
        $where_clause = " AND id=" . $common_id;
    } else {
        if (count($free_rooms_common_ids) > 0) {
            $where_clause = " AND id IN (" . implode(",", $free_rooms_common_ids) . ")";
        } else {
            $where_clause = "";
        }
    }

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms_manager WHERE publish=1" . $where_clause;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $free_commons = $result->getRows();

    foreach ($free_commons AS $free_common) {
        $free_commons_list[$free_common['id']] = $free_common;
    }

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_capacity ORDER BY capacity DESC";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $capacities = $result->getRows();
    $first_capacities = array();
    $second_capacities = array();
    foreach ($capacities AS $capacity) {
        if ($guests_count == $capacity['capacity']) {
            $mapped_capacities[$capacity['id']] = $capacity;
        }
    }
    foreach ($capacities AS $capacity) {
        if ($guests_count != $capacity['capacity']) {
            $mapped_capacities[$capacity['id']] = $capacity;
        }
    }
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms_gal";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $rooms_gal = $result->getRows();
    foreach ($rooms_gal AS $room_gal) {
        $mapped_rooms_gal[$room_gal['rec_id']][] = $room_gal['img'];
    }

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms_info";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $rooms_info = $result->getRows();

    foreach ($rooms_info AS $room_info) {
        $mapped_rooms_info[$room_info['common_id']][$room_info['lang']] = $room_info['intro'];
    }
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_types WHERE publish=1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $room_types = $result->getRows();
    foreach ($room_types AS $room_type) {
        $room_types_list[$room_type['id']] = array('id' => $room_type['id'], 'title' => $FUNC->unpackData($room_type['title'], LANG));
    }

    if ($block_id != 0) {
        $all_blocks[$block_id] = getBlockByID($block_id);
    } else {
        $all_blocks = getAllBlocks();
    }

    foreach ($free_rooms AS $free_room) {

        $r_common_id = $free_room['common_id'];
        //p($mapped_common_prices[$r_common_id]['priced_days_count']."!=".$days_count);
        if ($mapped_common_prices[$r_common_id]['priced_days_count'] != $days_count) {
            continue;
        }
        $r_floor = $free_room['floor'];
        $r_block_id = $free_commons_list[$r_common_id]['block_id'];
        $block_title = $all_blocks[$r_block_id]['title'];
        $room_type_title = $room_types_list[$free_commons_list[$r_common_id]['type_id']]['title'];
        $room_title = $free_room['name'];
        $room_capacity_title = $FUNC->unpackData($mapped_capacities[$free_commons_list[$r_common_id]['capacity_id']]['title'], LANG);

        if ($list[$room_type_title][$room_capacity_title]['free_rooms_count']) {
            $list[$room_type_title][$room_capacity_title]['free_rooms_count'] += 1;
        } else {
            $list[$room_type_title][$room_capacity_title] = $free_commons_list[$r_common_id];
            $list[$room_type_title][$room_capacity_title]['desc'] = $mapped_rooms_info[$r_common_id][LANG];
            $list[$room_type_title][$room_capacity_title]['first_night_price'] = $mapped_common_fd_prices[$r_common_id]['price'];
            $list[$room_type_title][$room_capacity_title]['price'] = $mapped_common_prices[$r_common_id]['total_price'];
            $list[$room_type_title][$room_capacity_title]['capacity'] = $mapped_capacities[$free_commons_list[$r_common_id]['capacity_id']];
            $list[$room_type_title][$room_capacity_title]['capacity']['title'] = $FUNC->unpackData($list[$room_type_title]['capacity']['title'], LANG);
            $list[$room_type_title][$room_capacity_title]['gal'] = $mapped_rooms_gal[$r_common_id];
            $list[$room_type_title][$room_capacity_title]['free_rooms_count'] = 1;
            $lpd = (array)json_decode($list[$room_type_title][$room_capacity_title]['lpd'], true);
            $list[$room_type_title][$room_capacity_title]['lpd'] = $lpd;
            $list[$room_type_title][$room_capacity_title]['one_person_discount'] = (int)($lpd[$list[$room_type_title][$room_capacity_title]['capacity']['capacity'] - 1]);
        }
        //$list[$block_title][$room_type_title][]=$free_room;
    }
    $return_array['system_currency'] = $_CONF['system_currency'];
    $myFile = "http://currency.boom.ge/all_currency.dat";
    $lines = file($myFile);
    for ($i = 0; $i < count($lines); $i++) {
        $cur_item = explode(';', $lines[$i]);
        if ($cur_item[0] == 'USD') {
            $usd = $cur_item[1];
        } elseif ($cur_item[0] == 'EUR') {
            $eur = $cur_item[1];
        }
    }

    if ($return_array['system_currency'] == 'GEL') {
        $currency_coef["GEL"] = 1;
        $currency_coef["USD"] = 1 / $usd;
        $currency_coef["EUR"] = 1 / $eur;
    } elseif ($return_array['system_currency'] == 'USD') {
        $currency_coef["GEL"] = $usd;
        $currency_coef["USD"] = 1;
        $currency_coef["EUR"] = $usd / $eur;
    } elseif ($return_array['system_currency'] == 'EUR') {
        $currency_coef["GEL"] = $eur;
        $currency_coef["USD"] = $eur / $usd;
        $currency_coef["EUR"] = 1;
    }
    $return_array['pay_now_limit'] = $hotelSettings['pay_now_limit'];
    $return_array['free_cancelation_time'] = $hotelSettings['free_cancelation_time'];
    $return_array['pay_later_guarantee_amount'] = $hotelSettings['pay_later_guarantee_amount'];
    //$return_array['currency']=$currency_coef;
    $return_array['currency'] = getCurrencyRates();
    $return_array['free_rooms'] = $list;
    $return_array['food_prices'] = $mapped_food_prices;
    $return_array['tbc_payments_method'] = $_CONF['tbc_payments_method'];
    echo json_encode($return_array);
} elseif ($_GET['cmd'] == 'get_all_rooms') {
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms_manager WHERE publish=1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $commons = $result->getRows();
    foreach ($commons AS $common) {
        $commons_list[$common['id']] = $common;
    }

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_capacity";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $capacities = $result->getRows();
    foreach ($capacities AS $capacity) {
        $mapped_capacities[$capacity['id']] = $capacity;
    }
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms_gal";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $rooms_gal = $result->getRows();
    foreach ($rooms_gal AS $room_gal) {
        $mapped_rooms_gal[$room_gal['rec_id']][] = $room_gal['img'];
    }

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms_info";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $rooms_info = $result->getRows();
    foreach ($rooms_info AS $room_info) {
        $mapped_rooms_info[$room_info['common_id']][$room_info['lang']] = $room_info['intro'];
    }
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_types WHERE publish=1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $room_types = $result->getRows();
    foreach ($room_types AS $room_type) {
        $room_types_list[$room_type['id']] = array('id' => $room_type['id'], 'title' => $FUNC->unpackData($room_type['title'], LANG));
    }

    foreach ($commons_list AS $common_item) {
        $r_common_id = $common_item['id'];
        $room_type_title = $room_types_list[$common_item['type_id']]['title'];
        $room_title = $free_room['name'];
        $room_capacity_title = $FUNC->unpackData($mapped_capacities[$common_item['capacity_id']]['title'], LANG);

        $list[$room_type_title][$room_capacity_title] = $common_item;
        $list[$room_type_title][$room_capacity_title]['desc'] = $mapped_rooms_info[$r_common_id][LANG];
        $list[$room_type_title][$room_capacity_title]['capacity'] = $mapped_capacities[$common_item['capacity_id']];
        $list[$room_type_title][$room_capacity_title]['capacity']['title'] = $FUNC->unpackData($mapped_capacities[$common_item['capacity_id']]['title'], LANG);
        $list[$room_type_title][$room_capacity_title]['gal'] = $mapped_rooms_gal[$r_common_id];
    }
    echo json_encode($list);

} else {
    p('EXIT');
}

function getAllBlocks()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_hotel_blocks WHERE publish=1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $bloks = $result->getRows();
    foreach ($bloks AS $block) {
        $bloks_list[$block['id']] = array('id' => $block['id'], 'title' => $FUNC->unpackData($block['title'], LANG), 'floors' => $block['floors']);
    }
    return $bloks_list;
}

function getDiffBetweenTwoDates2($datetime1, $datetime2)
{
    $datetime1 = date_create($datetime1);
    $datetime2 = date_create($datetime2);
    $interval = date_diff($datetime1, $datetime2);
    return $interval->days;
}

function getDiffBetweenTwoDates3($date1, $date2)
{
    $current = $date1;
    $datetime2 = date_create($date2);
    $count = 0;
    while (date_create($current) < $datetime2) {
        $current = gmdate("Y-m-d", strtotime("+1 day", strtotime($current)));
        $count++;
    }
    return $count;
}

function getDiffBetweenTwoDates($date1, $date2)
{
    $now = strtotime($date1); // or your date as well
    $your_date = strtotime($date2);
    $datediff = $your_date - $now;
    return floor($datediff / (60 * 60 * 24));
}

function getCurrencyRates()
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_currency_rates
              ORDER BY updated_at DESC
              LIMIT 1";
    $result = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $rates = unserialize($result['currency_rates']);
    $return['GEL'] = 1;
    foreach ($rates AS $rate) {
        $return[$rate['name']] = round(1 / ($rate['rate'] / $rate['count']), 3);
    }
    return $return;
}