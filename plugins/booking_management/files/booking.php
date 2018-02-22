<?

$BOOKING_TABLE = $_CONF['db']['prefix'] . '_booking';
$POST = $VALIDATOR->ConvertSpecialChars($_POST);
if ($POST['action'] == 'change_board_period') {
    $_SESSION['days_before'] = $POST['days_before'];
    $_SESSION['days_after'] = $POST['days_after'];
}else {

}

if(isset($_POST['lastScroll'])){
    $TMPL->addVar('lastScroll', $_POST['lastScroll']);
}
if(isset($_GET['lastScroll'])){
    $TMPL->addVar('lastScroll', $_GET['lastScroll']);
}
switch ($_SESSION['days_before']) {
    case "-1w":
        $days_before = date('Y-m-d', strtotime('-7 days'));
        break;
    case "-1m":
        $days_before = date('Y-m-d', strtotime('-1 month'));
        break;
    case "-6m":
        $days_before = date('Y-m-d', strtotime('-6 month'));
        break;
    case "-1y":
        $days_before = date('Y-m-d', strtotime('-1 year'));
        break;
    default:
        $_SESSION['days_before'] = '-1w';
        $days_before = date('Y-m-d', strtotime('-7 days'));
}

switch ($_SESSION['days_after']) {
    case "+1w":
        $days_after = date('Y-m-d', strtotime('+7 days'));
        break;
    case "+1m":
        $days_after = date('Y-m-d', strtotime('+1 month'));
        break;
    case "+6m":
        $days_after = date('Y-m-d', strtotime('+6 month'));
        break;
    case "+1y":
        $days_after = date('Y-m-d', strtotime('+1 year'));
        break;
    default:
        $_SESSION['days_after'] = '+1m';
        $days_after = date('Y-m-d', strtotime('+1 month'));
}

if ($POST['action'] == 'add') {
    //p($POST); exit;
 if($POST['booking_type_ens']=='single'){
    $check_in = $POST['check_in'];
    $check_out = $POST['check_out'];
    $days_count = getDiffBetweenTwoDates($check_in, $check_out);
    $room_ids = $POST['room_id'];
    if($POST['custom_price']>0){
        $custom_price = (double)$POST['custom_price'];
        //$custom_price_for_booking=$custom_price/count($room_ids);
        //CPPD= Custom Price Per Day
        $cppd=$custom_price/(count($room_ids)*$days_count);
        $cppd=round($cppd*100)/100;
    }else{
        $cppd=0;
    }
    $daily_ind_discount = (double)$POST['daily_ind_discount'];
    $first_day_services_array = $_POST['service'];
    $booking_comment = $POST['booking_comment'];
    $sum_fixed_discount = (double)$POST['fixed_discount'];
    $food_id = (int)$_POST['food_id'];

    $one_booking_fixed_discount = round($sum_fixed_discount / count($room_ids), 2);
    //p($one_booking_fixed_discount); exit;
    (int)$POST['adult_num'] < 1 ? $errors[] = "adults count<1" : $adult_num = (int)$POST['adult_num'];
    $child_num = (int)$POST['child_num'];

    if ($check_in < date('Y-m-d',strtotime('-1 day',strtotime(CURRENT_DATE))) || $check_in == '') {
        $errors[] = "araswori check_in";
    }
    if ($check_out <= date('Y-m-d',strtotime('-1 day',strtotime(CURRENT_DATE))) || $check_out == '') {
        $errors[] = "araswori check_out";
    }
    $guest_id = (int)$POST['b_guest_id'];
    $responsive_guest_id = ((int)$POST['b_responsive_guest_id'] == 0) ? $guest_id : (int)$POST['b_responsive_guest_id'];
    $affiliate_id = (int)$POST['b_affiliate_id'];
    $status_id = (int)$POST['status_id'];

    $guest_obj = $CONN->GetRow("select * from {$_CONF['db']['prefix']}_guests where id={$guest_id}");
    $guest_ind_discount = $guest_obj['ind_discount'];
    $guest_tax = (int)$guest_obj['tax'];
    if ($affiliate_id > 0) {
        $affiliate_obj = $CONN->GetRow("select * from {$_CONF['db']['prefix']}_guests where id={$affiliate_id}");
        $affiliate_ind_discount = (int)$affiliate_obj['ind_discount'];
    }
    $food_obj = $CONN->GetRow("select * from {$_CONF['db']['prefix']}_room_services where id={$food_id}");
  //---------------------------------------------START CHECKING ON AVAILABILITY
    $query = "SELECT id,room_id,check_in,check_out FROM {$_CONF['db']['prefix']}_booking
                WHERE active=1 AND
                (
                    (check_in>='" . $check_in . "' AND check_in<'" . $check_out . "')
                    OR
                    (check_out>'" . $check_in . "' AND check_out<='" . $check_out . "')
                    OR
                    (check_in<='" . $check_in . "' AND check_out>='" . $check_out . "')
                ) AND room_id IN(".implode(',',$room_ids).")";

    $result = $CONN->Execute($query);
    $conflict_bookings = $result->GetRows();


    if(!empty($conflict_bookings)){
        $_SESSION['errors']['booking_id']="მოცემლ პერიოდში ჯავშანი უკვე არსებობს";
        $FUNC->Redirect($SELF_FILTERED);
        exit;
    };
 //---------------------------------------------END CHECKING ON AVAILABILITY -

 //---------------------------------------------START MULTIPLE BOOKING FOR
    foreach ($room_ids as $room_id) {
        $room_obj = $CONN->GetRow("select * from {$_CONF['db']['prefix']}_rooms where id={$room_id}");
        $common_id = $room_obj['common_id'];

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms_manager WHERE id={$common_id}";
        $room_type_obj = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_capacity WHERE id={$room_type_obj['capacity_id']}";
        $room_capacity_obj = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());


        $lpd = array();
        $lpd = json_decode($room_type_obj['lpd'], true);

        $old_discount = 0;
        for ($i = ($room_capacity_obj['capacity'] - 1); $i > 0; $i--) {
            $lpd[$i] = ((int)$lpd[$i] == 0) ? $old_discount : (int)$lpd[$i];
            $old_discount = $lpd[$i];
        }
        $less_person_discount = $lpd[$adult_num];


        $pay_now_discount = $room_type_obj['pay_now_discount'];

        //archeuli otaxis fasebi
        $query = "SELECT *
			  FROM {$_CONF['db']['prefix']}_room_prices
		  	  WHERE common_id={$common_id} AND date>='{$check_in}' AND date<='$check_out'
		  	  ";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $room_prices = $result->GetRows();

        if (!empty($room_prices)) {
            foreach ($room_prices as $room_price) {
                if ($room_price['price'] == '') {
                    $errors[] = "fasi ar aris mititebuli";
                }
                $room_prices_by_date[$room_price['date']]['price'] = $room_price['price'];
                $room_prices_by_date[$room_price['date']]['discount'] = $room_price['discount'];
            }
        } else {
            $errors[] = "fasi ar aris mititebuli";
        }

        if (!$errors) {
            $query = "INSERT INTO {$BOOKING_TABLE} SET
                          room_id='{$room_id}',
                          guest_id={$guest_id},
                          responsive_guest_id={$responsive_guest_id},
                          affiliate_id={$affiliate_id},
                          administrator_id={$_SESSION['pcms_user_id']},
                          first_day_price=100,
                          daily_ind_discount={$daily_ind_discount},
                          fixed_discount={$one_booking_fixed_discount},
                          check_in='{$check_in}',
                          check_out='{$check_out}',
                          adult_num='{$adult_num}',
                          child_num='{$child_num}',
                          food_id='{$food_id}',
                          status_id={$status_id},
                          created_at=NOW(),
                          dbl_res=0,
                          dbl_res_id=0,
                          comment='{$booking_comment}',
                          log='" . serialize(array("POST" => $_POST, "GET" => $_GET)) . "'";

            $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $booking_id = (int)$CONN->Insert_ID();

            $startDate = strtotime($check_in);
            $endDate = strtotime($check_out);
            $guest_net_price = 0;
            $guest_total_price = 0;
            $affiliate_net_price = 0;
            $services_total_price = 0;
            $cashBack = 0;

            while ($startDate <= $endDate) {

                $guest_daily_net_price = 0;
                $affiliate_daily_net_price = 0;
                if ($startDate < $endDate) {
                    $a = (double)$room_prices_by_date[date('Y-m-d', $startDate)]['price'];
                    $food_price = (double)$food_obj['price'] * ($child_num + $adult_num);
                    $b = (double)$room_prices_by_date[date('Y-m-d', $startDate)]['discount'];
                    $c = $less_person_discount;
                    $d = 0;//pay now
                    $e = $daily_ind_discount;
                    if ($one_booking_fixed_discount != 0) {
                        //$e += round($one_booking_fixed_discount / $days_count, 2);
                    }
                    $e2 = 0;
                    $f = $guest_ind_discount;
                    $f2 = $affiliate_ind_discount;
                    $g = ($guest_tax == 0) ? 18 : 0;
                    $g2 = 0;//affiliates tax free ar moqmedebs
                    if($cppd>0){
                        $guest_daily_net_price = $cppd;
                        $affiliate_daily_net_price = $cppd;
                    }else{
                        $guest_daily_net_price = calculateNetPrice($a, $food_price, $b, $c, $d, $f, $e, $g);
                        $affiliate_daily_net_price = calculateNetPrice($a, $food_price, $b, $c, $d, $f2, $e2, $g2);
                    }

                    $guest_net_price += $guest_daily_net_price;
                    $affiliate_net_price += $affiliate_daily_net_price;

                }
                if ($startDate == strtotime($check_in)) {
                    $type = "check_in";
                } else if ($startDate == $endDate) {
                    $type = "check_out";
                } else {
                    $type = "in_use";
                }
                $daily_comment = "a=" . $a . ", b=" . $b . ", food_price=" . $food_price . ", c=" . $c . ", d=" . $d . ", $f=" . $f . ", e=" . $e . ", g=" . $g;

                $booking_day_id = addNewBookingDay($booking_id, date('Y-m-d', $startDate), $type, $guest_daily_net_price, $daily_comment);
                for ($i = 0; $i < count($first_day_services_array); $i++) {
                    if ($first_day_services_array[$i]['type_id'] == 2 || $first_day_services_array[$i]['type_id'] == 3) {
                        if ($type != 'check_out') {
                            $services_total_price += $first_day_services_array[$i]['price'];
                            addNewDailyService($booking_day_id, $first_day_services_array[$i]['id'], $first_day_services_array[$i]['type_id'], $first_day_services_array[$i]['title'], $first_day_services_array[$i]['price'], 'no_comment');
                        }
                    } else {
                        if ($type == 'check_in') {
                            $services_total_price += $first_day_services_array[$i]['price'];
                            addNewDailyService($booking_day_id, $first_day_services_array[$i]['id'], $first_day_services_array[$i]['type_id'], $first_day_services_array[$i]['title'], $first_day_services_array[$i]['price'], 'no_comment');
                        }
                    }
                }
                $startDate = strtotime('+1 day', $startDate);
            }
            $guest_total_price = $guest_net_price + $services_total_price;
            if ($affiliate_id != 0) {
                $cashBack = $guest_net_price - $affiliate_net_price;
                if ($cashBack > 0) {
                    addNewCashBack('affiliate', $guest_id, $affiliate_id, $booking_id, $cashBack, $check_out, 'no_comment');
                } else {
                    $cashBack = 0;
                }
            }
           $rows=updateBookingFinances($booking_id);

            $ALOG->addActivityLog(
                "Add New Booking",
                "Administrator Added New Booking[{$booking_id}] Guest[{$guest_id}] room[{$room_id}] check_in[{$check_in}] check_out[{$check_out}]",
                $_SESSION['pcms_user_id'],
                serialize($POST)
            );
        } else {
            var_dump($errors);
            exit;
        }
    }
 //----------------------------------------END MULTIPLEBOOKING FOR

    $FUNC->Redirect($SELF_FILTERED);
  }else{
        //ჯგუფური ჯავშანი

    $check_in = $POST['check_in'];
    $check_out = $POST['check_out'];
    $days_count = getDiffBetweenTwoDates($check_in, $check_out);
    $room_ids = $POST['room_id'];
    if($POST['custom_price']>0){
        $custom_price = (double)$POST['custom_price'];
        //$custom_price_for_booking=$custom_price/count($room_ids);
        //CPPD= Custom Price Per Day
        $cppd=$custom_price/(count($room_ids)*$days_count);
        $cppd=round($cppd*100)/100;
    }else{
        $cppd=0;
    }
    $daily_ind_discount = (double)$POST['daily_ind_discount'];
    $first_day_services_array = $_POST['service'];
    $booking_comment = $POST['booking_comment'];
    $sum_fixed_discount = (double)$POST['fixed_discount'];
    $food_id = (int)$_POST['food_id'];

    $one_booking_fixed_discount = round($sum_fixed_discount / count($room_ids), 2);
    //p($one_booking_fixed_discount); exit;
    (int)$POST['adult_num'] < 1 ? $errors[] = "adults count<1" : $adult_num = (int)$POST['adult_num'];
    $child_num = (int)$POST['child_num'];

    if ($check_in < CURRENT_DATE || $check_in == '') {
        $errors[] = "araswori check_in";
    }
    if ($check_out <= CURRENT_DATE || $check_out == '') {
        $errors[] = "araswori check_out";
    }
    $guest_id = (int)$POST['b_guest_id'];
    $responsive_guest_id = ((int)$POST['b_responsive_guest_id'] == 0) ? $guest_id : (int)$POST['b_responsive_guest_id'];
    $affiliate_id = (int)$POST['b_affiliate_id'];
    $status_id = (int)$POST['status_id'];

    $guest_obj = $CONN->GetRow("select * from {$_CONF['db']['prefix']}_guests where id={$guest_id}");
    $guest_ind_discount = $guest_obj['ind_discount'];
    $guest_tax = (int)$guest_obj['tax'];

    if ($affiliate_id > 0) {
        $affiliate_obj = $CONN->GetRow("select * from {$_CONF['db']['prefix']}_guests where id={$affiliate_id}");
        $affiliate_ind_discount = (int)$affiliate_obj['ind_discount'];
    }
    $food_obj = $CONN->GetRow("select * from {$_CONF['db']['prefix']}_room_services where id={$food_id}");
    //---------------------------------------------START CHECKING ON AVAILABILITY
    $query = "SELECT id,room_id,check_in,check_out FROM {$_CONF['db']['prefix']}_booking
                WHERE active=1 AND
                (
                    (check_in>='" . $check_in . "' AND check_in<'" . $check_out . "')
                    OR
                    (check_out>'" . $check_in . "' AND check_out<='" . $check_out . "')
                    OR
                    (check_in<='" . $check_in . "' AND check_out>='" . $check_out . "')
                ) AND room_id IN(".implode(',',$room_ids).")";

    $result = $CONN->Execute($query);
    $conflict_bookings = $result->GetRows();
    if(!empty($conflict_bookings)){
        $_SESSION['errors']['booking_id']="მოცემლ პერიოდში ჯავშანი უკვე არსებობს";
        $FUNC->Redirect($SELF_FILTERED);
        exit;
    };
    //---------------------------------------------END CHECKING ON AVAILABILITY -

    //---------------------------------------------START MULTIPLE BOOKING FOR
    $db_status=1;
    foreach ($room_ids as $room_id) {

        $room_obj = $CONN->GetRow("select * from {$_CONF['db']['prefix']}_rooms where id={$room_id}");
        $common_id = $room_obj['common_id'];

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms_manager WHERE id={$common_id}";
        $room_type_obj = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_capacity WHERE id={$room_type_obj['capacity_id']}";
        $room_capacity_obj = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $lpd = array();
        $lpd = json_decode($room_type_obj['lpd'], true);

        $old_discount = 0;
        for ($i = ($room_capacity_obj['capacity'] - 1); $i > 0; $i--) {
            $lpd[$i] = ((int)$lpd[$i] == 0) ? $old_discount : (int)$lpd[$i];
            $old_discount = $lpd[$i];
        }
        $less_person_discount = $lpd[$adult_num];


        $pay_now_discount = $room_type_obj['pay_now_discount'];

        //archeuli otaxis fasebi
        $query = "SELECT *
              FROM {$_CONF['db']['prefix']}_room_prices
              WHERE common_id={$common_id} AND date>='{$check_in}' AND date<='$check_out'
              ";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $room_prices = $result->GetRows();

        if (!empty($room_prices)) {
            foreach ($room_prices as $room_price) {
                if ($room_price['price'] == '') {
                    $errors[] = "fasi ar aris mititebuli";
                }
                $room_prices_by_date[$room_price['date']]['price'] = $room_price['price'];
                $room_prices_by_date[$room_price['date']]['discount'] = $room_price['discount'];
            }
        } else {
            $errors[] = "fasi ar aris mititebuli";
        }
        if (!$errors) {
            $query = "INSERT INTO {$BOOKING_TABLE} SET
                          room_id='{$room_id}',
                          guest_id={$guest_id},
                          responsive_guest_id={$responsive_guest_id},
                          affiliate_id={$affiliate_id},
                          administrator_id={$_SESSION['pcms_user_id']},
                          first_day_price=100,
                          daily_ind_discount={$daily_ind_discount},
                          fixed_discount={$one_booking_fixed_discount},
                          check_in='{$check_in}',
                          check_out='{$check_out}',
                          adult_num='{$adult_num}',
                          child_num='{$child_num}',
                          food_id='{$food_id}',
                          status_id={$status_id},
                          created_at=NOW(),
                          dbl_res=1,
                          dbl_res_id=".$db_status.",
                          comment='{$booking_comment}',
                          log='" . serialize(array("POST" => $_POST, "GET" => $_GET)) . "'";

            $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $booking_id = (int)$CONN->Insert_ID();
            ($db_status==1)?$db_status=$booking_id:'';
            $startDate = strtotime($check_in);
            $endDate = strtotime($check_out);
            $guest_net_price = 0;
            $guest_total_price = 0;
            $affiliate_net_price = 0;
            $services_total_price = 0;
            $cashBack = 0;

            while ($startDate <= $endDate) {
                $guest_daily_net_price = 0;
                $affiliate_daily_net_price = 0;
                if ($startDate < $endDate) {
                    $a = (double)$room_prices_by_date[date('Y-m-d', $startDate)]['price'];
                    $food_price = (double)$food_obj['price'] * ($child_num + $adult_num);
                    $b = (double)$room_prices_by_date[date('Y-m-d', $startDate)]['discount'];
                    $c = $less_person_discount;
                    $d = 0;//pay now
                    $e = $daily_ind_discount;
                    if ($one_booking_fixed_discount != 0) {
                        //$e += round($one_booking_fixed_discount / $days_count, 2);
                    }
                    $e2 = 0;
                    $f = $guest_ind_discount;
                    $f2 = $affiliate_ind_discount;
                    $g = ($guest_tax == 0) ? 18 : 0;
                    $g2 = 0;//affiliates tax free ar moqmedebs
                    if($cppd>0){
                        $guest_daily_net_price = $cppd;
                        $affiliate_daily_net_price = $cppd;
                    }else{
                        $guest_daily_net_price = calculateNetPrice($a, $food_price, $b, $c, $d, $f, $e, $g);
                        $affiliate_daily_net_price = calculateNetPrice($a, $food_price, $b, $c, $d, $f2, $e2, $g2);
                    }

                    $guest_net_price += $guest_daily_net_price;
                    $affiliate_net_price += $affiliate_daily_net_price;

                }
                if ($startDate == strtotime($check_in)) {
                    $type = "check_in";
                } else if ($startDate == $endDate) {
                    $type = "check_out";
                } else {
                    $type = "in_use";
                }
                $daily_comment = "a=" . $a . ", b=" . $b . ", food_price=" . $food_price . ", c=" . $c . ", d=" . $d . ", $f=" . $f . ", e=" . $e . ", g=" . $g;

                $booking_day_id = addNewBookingDay($booking_id, date('Y-m-d', $startDate), $type, $guest_daily_net_price, $daily_comment);
                for ($i = 0; $i < count($first_day_services_array); $i++) {
                    if ($first_day_services_array[$i]['type_id'] == 2 || $first_day_services_array[$i]['type_id'] == 3) {
                        if ($type != 'check_out') {
                            $services_total_price += $first_day_services_array[$i]['price'];
                            addNewDailyService($booking_day_id, $first_day_services_array[$i]['id'], $first_day_services_array[$i]['type_id'], $first_day_services_array[$i]['title'], $first_day_services_array[$i]['price'], 'no_comment');
                        }
                    } else {
                        if ($type == 'check_in') {
                            $services_total_price += $first_day_services_array[$i]['price'];
                            addNewDailyService($booking_day_id, $first_day_services_array[$i]['id'], $first_day_services_array[$i]['type_id'], $first_day_services_array[$i]['title'], $first_day_services_array[$i]['price'], 'no_comment');
                        }
                    }
                }
                $startDate = strtotime('+1 day', $startDate);
            }
            $guest_total_price = $guest_net_price + $services_total_price;
            if ($affiliate_id != 0) {
                $cashBack = $guest_net_price - $affiliate_net_price;
                if ($cashBack > 0) {
                    addNewCashBack('affiliate', $guest_id, $affiliate_id, $booking_id, $cashBack, $check_out, 'no_comment');
                } else {
                    $cashBack = 0;
                }
            }
            updateBookingFinances($booking_id);

            $ALOG->addActivityLog(
                "Add New Booking",
                "Administrator Added New Booking[{$booking_id}] Guest[{$guest_id}] room[{$room_id}] check_in[{$check_in}] check_out[{$check_out}]",
                $_SESSION['pcms_user_id'],
                serialize($POST)
            );
        } else {
            p($errors);
            exit;
        }
    }
 //----------------------------------------END MULTIPLEBOOKING FOR

    $FUNC->Redirect($SELF_FILTERED);
    }
} elseif ($POST['action'] == 'update') {

} elseif ($POST['action'] == "del_booking") {

    if ($_SESSION['pcms_user_group'] <=2) {

        $booking_id = (int)$POST['booking_id'];
        $booking = getBookingById($booking_id);
        if($booking) {
            $total_price = (float)$booking['accommodation_price'] + (float)$booking['services_price'];
            $total_paid = (float)$booking['paid_amount'] + (float)$booking['services_paid_amount'];
            $refund_amount = (float)$POST['refund_amount'];
            $payment_method_id = (int)$POST['refund_method_id'];

            $guest = getGuestByID($booking['guest_id']);

            if ($refund_amount > 0 && $refund_amount <= $total_paid) {
                if ($payment_method_id == 5) {
                    $new_balance = (float)$guest['balance'] + $refund_amount;
                    updateGuestBalance($guest['id'], $new_balance);
                    addBookingTransaction('local', $booking['guest_id'], $booking_id, $_SESSION['pcms_user_id'], -$refund_amount, $payment_method_id, 'refund_to_balance');
                } else {
                    //
                    addBookingTransaction('global', $booking['guest_id'], $booking_id, $_SESSION['pcms_user_id'], -$refund_amount, $payment_method_id, 'refund');
                }
                if (($total_paid - $refund_amount) > 0) {
                    addHotelBalance($guest['id'], $booking['id'], $_SESSION['pcms_user_id'], ($total_paid - $refund_amount), 'Income from canceled booking');
                }
            } else {

            }

            if($_POST['rf_action']=='cabcel'){
                archiveBooking($booking['id'],0,false,$_POST['dl_coment']);
            }else{
                deleteBooking($booking['id']);
            }

        }
        header("Location: ".$SELF_FILTERED);
    } else {
        die("You do not have permission");
    }
}
/**LIST**/
    $time_start = microtime(true);

    if (isset($_GET['type_id']) && isset($_GET['block_id'])) {

    $type_ids = $_GET['type_id'];
    $block_ids =$_GET['block_id'];
    $period_start = $_GET['period_start'];
    $period_end = $_GET['period_end'];
    $rooms_status = $_GET['rooms_status'];

    session_start();
    if(isset($_GET['resetFilter']) && $_GET['resetFilter']=='resetFilter'){
        unset($_SESSION['booking']['filter']);
    }else{
        $_SESSION['booking']['filter']['start_date']=($period_start!='')?$period_start:$_SESSION['booking']['filter']['start_date'];
        $_SESSION['booking']['filter']['end_date']=($period_end!='')?$period_end: $_SESSION['booking']['filter']['end_date'];
    }

    $TMPL->addVar('TMPL_sessions',$_SESSION['booking']['filter']);

    $period_start=$_SESSION['booking']['filter']['start_date'];
    $period_end=$_SESSION['booking']['filter']['end_date'];


    $rooms_manager_query = "WHERE 1=1 ";
    ((count($type_ids)==1&& $type_ids[0]!=0)||(count($type_ids)>1)) ? $rooms_manager_query .= " AND type_id IN (".implode(',',$type_ids).")" : "";
    ((count($block_ids)==1&& $block_ids[0]!=0) || count($block_ids)>1) ? $rooms_manager_query .= " AND block_id IN (".implode(',',$block_ids).")" : "";

    //moaqvs shesabamisi PRICE PLAN-bi
    $query = "SELECT *
			FROM {$_CONF['db']['prefix']}_rooms_manager
		  	{$rooms_manager_query}";
    #dd($query);

    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();
       # dd($query);

    if (count($data) == 0) {
        //die("No Price Plans");
    }

    ///moaqvs shesabamisi PRICE PLAN-bi

    //moaqvs yvela stumari
    $query = "SELECT *
			FROM {$_CONF['db']['prefix']}_guests
		  	WHERE publish=1";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $guests_all = $result->GetRows();
    foreach ($guests_all AS $guest) {
        $guests_assoc["'" . $guest['id'] . "'"] = $guest['first_name'] . " " . $guest['last_name'];
    }
    ///moaqvs yvela stumari

    //moaqvs shesabamisi otaxebi
    if (!empty($data)) {
        $common_ids = array(0);
        foreach ($data AS $common) {
            $common_ids[] = $common['id'];
            $rooms_manager_arr[$common['id']] = $common;
        }
        $tmp_arr = implode(",", $common_ids);
        $query = "SELECT *
			FROM {$_CONF['db']['prefix']}_rooms
		  	WHERE common_id IN ($tmp_arr)
		  	AND publish=1 AND (for_web<>0 OR for_local<>0)
		  	ORDER BY floor ASC, name ASC";

        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

        $all_rooms = $result->GetRows();

        if ($period_start != '' && $period_end != '') {
            $query = "SELECT *
			FROM {$_CONF['db']['prefix']}_booking_daily
		  	WHERE active=1 AND date >='{$period_start}' AND date<='{$period_end}' ";
            $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $period_booking_days = $result->GetRows();

            foreach ($period_booking_days AS $period_booking_day) {
                if (!in_array($period_booking_day['booking_id'], $period_booking_ids)) {
                    $period_booking_ids[] = $period_booking_day['booking_id'];
                }
            }
            if (count($period_booking_ids) > 0) {
                $tmp_arr = implode(", ", $period_booking_ids);
                $where_clause = "WHERE active=1 AND id IN ($tmp_arr)";
            } else {
                $where_clause = "";
            }

            $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking " . $where_clause;

            $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $period_bookings = $result->GetRows();
            $period_room_ids = array();
            foreach ($period_bookings AS $period_booking) {
                if (!in_array($period_booking['room_id'], $period_room_ids)) {
                    $period_room_ids[] = $period_booking['room_id'];
                }
            }
            if ($rooms_status == 'free') {
                foreach ($all_rooms AS $all_room) {
                    if (!in_array($all_room['id'], $period_room_ids)) {
                        $tmp_rooms_list[] = $all_room;
                    }
                }
                $all_rooms = $tmp_rooms_list;
            } else if ($rooms_status == 'in_use') {
                foreach ($all_rooms AS $all_room) {
                    if (in_array($all_room['id'], $period_room_ids)) {
                        $tmp_rooms_list[] = $all_room;
                    }

                }

                $all_rooms = $tmp_rooms_list;
            } else {
            }
        }
        foreach ($all_rooms AS $room) {
            $rooms[$room['floor']][] = $room;
        }

    }

    ///moaqvs shesabamisi otaxebi

    //moaqvs shesabamisi BOOKING
    if (!empty($all_rooms)) {
        foreach ($all_rooms AS $room) {
            $rooms_ids[] = $room['id'];
        }
        $tmp_arr = implode(", ", $rooms_ids);
        $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_booking
			  	WHERE active=1 AND room_id IN ($tmp_arr)";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $bookings = $result->GetRows();

#---------------start restricted rooms
        $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_restrictions
              WHERE '".$days_before."'<=to_date AND '".$days_after."'>=from_date";

        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $matched_restrictions = $result->getRows();
        foreach ($matched_restrictions AS $matched_restriction) {
            $restricted_days=getPeriodArray($matched_restriction['from_date'],$matched_restriction['to_date']);
            foreach($restricted_days as $restricted_day){
                $rooms_restrictions[$matched_restriction['room_id']][$restricted_day][$matched_restriction['type']]=1;
            }
        }
#---------------end restricted rooms

        foreach ($bookings AS $booking) {
            $tmp = $booking;
            if($booking['dbl_res']!=1){
                $tmp['guest_name'] = $guests_assoc["'" . $tmp['guest_id'] . "'"];
            }else{
                $tmp['guest_name'] = getDblname($booking['dbl_res_id']);
            }
            $bookings2[] = $tmp;

            $check_in = new DateTime($booking['check_in'], new DateTimeZone("Europe/London"));
            $check_out = new DateTime($booking['check_out'], new DateTimeZone("Europe/London"));
            $tmp_date = new DateTime($booking['check_in'], new DateTimeZone("Europe/London"));

            do {
                if ($check_in == $tmp_date) {
                    $tmp_bookings[$booking['room_id']][$tmp_date->format("Y-m-d")] = 'check_in';
                } else if ($check_out == $tmp_date) {
                    $tmp_bookings[$booking['room_id']][$tmp_date->format("Y-m-d")] = 'check_out';
                } else {
                    $tmp_bookings[$booking['room_id']][$tmp_date->format("Y-m-d")] = 'in_use';
                }
                $tmp_date->modify("+1 day");
            } while ($tmp_date <= $check_out);
        }
        $bookings = $tmp_bookings;
           #dd($bookings2);

        ///moaqvs shesabamisi BOOKING

        //moaqvs shesabamisi fasebi
        $imploded_common_ids = implode(", ", $common_ids);
        $query = "SELECT *,date_format(date,'%d/%M/%Y') as _date
			  FROM {$_CONF['db']['prefix']}_room_prices
		  	  WHERE common_id IN(" . $imploded_common_ids . ")";
        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $room_prices = $result->GetRows();
        ///moaqvs shesabamisi fasebi

        if (!empty($room_prices)) {
            foreach ($room_prices as $key => $value) {
                $common_prices_and_discounts[$value['common_id']][$value['date']] = array('price' => $value['price'], 'discount' => $value['discount']);
                $roomPriceArr[$value['rec_id']][$value['common_id']][$value['day']] = $value['price'];
                $roomPriceDaily[$value['id']] = array('price' => $value['price'], 'discount' => $value['discount']);
                $dateArr[$value['rec_id']][] = $value['_date'];
                $date_range[$value['rec_id']][] = $value['date'];
                $dateArr2[$value['rec_id']][$value['date']][$value['capacity_id']] = $value['id'];
                $rec_id[$value['rec_id']] = $value['rec_id'];
            }
        }

        $roomCapacity = GetRoomCapacity();
        foreach ($roomCapacity as $key => $value) {
            $rcArr[$value['id']] = $value;
        }
        $TMPL->addVar('TMPL_capacity', $rcArr);
        foreach ($dateArr2 as $key => $value) {
            foreach ($value as $k => $v) {
                $date = explode('-', $k);
                $y = $date[0];
                $m = $date[1];
                $d = $date[2];
                $dates[$key][$y][$m][$d] = $v;
            }
        }
    }
    if ($POST['action'] == 'update_price') {
        foreach ($POST['price'] as $key => $value) {
            if ($value != $roomPriceDaily[$key]['price'] || $POST['discount'][$key] != $roomPriceDaily[$key]['discount']) {
                $price = (double)$value;
                $discount = (double)$POST['discount'][$key];
                $id = (int)$key;
                $query = "UPDATE {$_CONF['db']['prefix']}_room_prices SET
			 	 price={$price}, discount={$discount}
			 	 WHERE id={$id}";
                $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            }
        }

        $FUNC->Redirect($SELF . '&type_id=' . (int)$_GET['type_id'] . '&block_id=' . (int)$_GET['block_id']);
    }
} else {

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms_manager";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_hotel_blocks";
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $blocks = $result->GetRows();

    if (count($blocks) == 0) {
        die('NO BlOCKS');
    } else if (count($blocks) == 1) {
        $FUNC->Redirect($SELF . '&tab=booking&type_id=0&block_id[]=' . $blocks[0]['id']);
    } else {
        $FUNC->Redirect($SELF . '&tab=booking&type_id=0&block_id=0');
    }

    //$FUNC->Redirect($SELF . '&tab=booking&type_id=' . $data[0]['type_id'] . '&block_id=' . $data[0]['block_id']);
}
$allStatuses = getAllStatuses();
 #dd($all_rooms);

$TMPL->addVar('TMPL_all_food', GetServices(6));
$TMPL->addVar('TMPL_rooms_manager_arr', $rooms_manager_arr);
$TMPL->addVar('TMPL_services_types', getServicesTypes());
$TMPL->addVar('TMPL_all_services', getAllServices());
$TMPL->addVar('TMPL_all_statuses', $allStatuses);
$TMPL->addVar('TMPL_countries', getAllCountries());
$TMPL->addVar('TMPL_all_guests', $guests_all);


if ($period_start != '' && $period_end != '') {
    $TMPL->addVar('TMPL_all_days', getPeriodArray($period_start, $period_end));
} else {
    $TMPL->addVar('TMPL_all_days', getPeriodArray($days_before, $days_after));
}

$TMPL->addVar("TMPL_payment_methods", getAllPaymentMethods());
$TMPL->addVar('TMPL_all_rooms', $all_rooms);
$TMPL->addVar('TMPL_rooms_restrictions', $rooms_restrictions);
$TMPL->addVar('TMPL_rooms_by_floor', $rooms);
$TMPL->addVar('TMPL_bookings', $bookings);
$TMPL->addVar('TMPL_bookings2', $bookings2);
$TMPL->addVar('TMPL_common_prices_and_discounts', $common_prices_and_discounts);
$TMPL->addVar('TMPL_blocks', GetBlocks());
$TMPL->addVar('TMPL_categories', GetRoomTypes());
$TMPL->addVar('TMPL_rooms_count', getRoomsCount());
$TMPL->addVar('TMPL_data', $data);
$TMPL->addVar('TMPL_rid', $rec_id);
$TMPL->addVar('TMPL_prices', $roomPriceArr);
$TMPL->addVar('daily_price', $roomPriceDaily);
$TMPL->addVar('TMPL_dr', $dateArr);
$TMPL->addVar('TMPL_dates', $dates);
$TMPL->addVar('date_range', $date_range);
$TMPL->addVar('weekDays', $weekDays);
$TMPL->addVar("TMPL_navbar", $FUNC->DrawPageBar($SELF . "&p=", $result));
$html = $TMPL->parseIntoString("booking");
$_CENTER = slib_compress_html($html);


function slib_compress_html($buffer)
{
    $replace = array(
        "#<!--.*?-->#s" => "",      // strip comments
        "#>\s+<#" => ">\n<",  // strip excess whitespace
        "#\n\s+<#" => "\n<"    // strip excess whitespace
    );
    $search = array_keys($replace);
    $html = preg_replace($search, $replace, $buffer);
    return trim($html);
}

$status_info = "<br><table border='0' ><tr><td style='background:#3A82CC; color:#FFF;' align='center'>ჯავშნების სტატუსები</td></tr>";
foreach ($allStatuses AS $status) {
    if ($status['id'] == 2) continue;
    $status_info .= '<tr>
    <td>
    <div class="booking-plate" style="padding:2px; white-space: normal; height: auto; overflow: visible;  position:relative; width:164px; border: 3px solid ' . $status['color'] . ';">' . $status['title'] . '</div>
    </td>
</tr>';
}
$status_info .= "</table>";
$_LEFT .= $status_info;
usleep(100);
$time_end = microtime(true);
$time = $time_end - $time_start;
//p("EXEC TIME = ".$time);


function yearArray($year)
{
    $range = array();
    $start = strtotime($year . '-01-01');
    $end = strtotime($year . '-12-31');
    do {
        $range[] = date('Y-m-d', $start);
        $start = strtotime("+ 1 day", $start);
    } while ($start <= $end);

    return $range;
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

function addNewGuest($id_number, $guest_type, $tax, $guest_ind_discount, $first_name, $last_name, $id_scan, $country, $address, $telephone, $email, $comment)
{
    global $CONN, $VALIDATOR, $FUNC, $_CONF;
    $POST['password'] = $VALIDATOR->RandString("1234567890QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm!@#$%^&*+/-_|", 6);
    $passhash = $VALIDATOR->RandString("!@#$%^&*+/-_|", 5);
    $password = $FUNC->CompiledPass($POST['password'], $passhash);
    $query = "INSERT INTO {$_CONF['db']['prefix']}_guests set
			      id_number = '{$id_number}',
			      type = '{$guest_type}',
			      tax = {$tax},
			      ind_discount = {$guest_ind_discount},
				  first_name = '{$first_name}',
				  last_name='{$last_name}',
				  telephone='{$telephone}',
				  email='{$email}',
				  country={$country},
				  address='{$address}',
				  id_scan='{$id_scan}',
				  password='{$password}',
				  passhash ='{$passhash}',
				  group_id=5,
				  comment ='{$comment}',
				  created_at=NOW(),
				  publish=1";
    $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $CONN->Insert_ID();
}


function saveImage($image)
{
    $imgDIR = "../uploads_script/guests";
    $SET['img_width'] = 480;
    $SET['img_height'] = 640;
    $SET['th_width'] = 340;
    $SET['th_height'] = 260;
    $SET['th_method'] = 'r';
    if ($_FILES[$image]['name'] && $_FILES[$image]['size']) {
        $IMG = new ImageGD($imgDIR);
        if ($img = $IMG->uploadImage($image)) {
            $IMG->resizeImage($img, $SET['img_width'], $SET['img_height'], 100, false, $img);

            if ($SET['th_method'] == 'c') {
                $IMG->cropImage($img, $SET['th_width'], $SET['th_height'], 100, 'thumb_' . $img);
            } else {
                $IMG->resizeImage($img, $SET['th_width'], $SET['th_height'], 100, false, 'thumb_' . $img);
            }
        }
        if ($errors = $IMG->passErrors()) {
            @unlink($imgDIR . '/' . $img);
            @unlink($imgDIR . '/thumb_' . $img);
            @unlink($imgDIR . '/thumb2_' . $img);
        }
    }
    return $img;
}

function calculateNetPrice($a, $food_price, $b, $c, $d, $f, $e, $g)
{
    //A-B(%)-C(%)-D(%)-F(%)-E-G(%)+FOOD
    $daily_price = $a;
    $daily_price = $daily_price - $daily_price / 100 * $b;
    $daily_price = $daily_price - $daily_price / 100 * $c;
    $daily_price = $daily_price - $daily_price / 100 * $d;
    $daily_price = $daily_price - $daily_price / 100 * $f;
    $daily_price = $daily_price - $e;
    $daily_price = $daily_price - $daily_price / (100 + $g) * $g;
    $daily_price += $food_price;
    $daily_price = round($daily_price * 100) / 100;
    return $daily_price;
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
function getDblname($id){
    global $CONN, $FUNC, $_CONF;
    $query="SELECT booking_name FROM cms_booking_dbl WHERE booking_master_id=".$id;
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return $result->fields['booking_name'];
}

function romanic_number($integer, $upcase = true)
{
    $table = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $return = '';
    while ($integer > 0) {
        foreach ($table as $rom => $arb) {
            if ($integer >= $arb) {
                $integer -= $arb;
                $return .= $rom;
                break;
            }
        }
    }
    return $return;
}

function addNewDailyService($booking_daily_id, $service_id, $service_type_id, $service_title, $service_price, $comment)
{
    global $CONN, $FUNC, $_CONF;
    $query = "INSERT INTO {$_CONF['db']['prefix']}_booking_daily_services set
			      booking_daily_id = {$booking_daily_id},
				  service_id = {$service_id},
				  service_type_id = {$service_type_id},
				  service_title='{$service_title}',
				  service_price={$service_price},
				  comment='{$comment}'";
    $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    return (int)$CONN->Insert_ID();
}
