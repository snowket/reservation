<?php

/**
 * Created by PhpStorm.
 * User: nino
 * Date: 6/13/2016
 * Time: 12:20 PM
 */
class Booking Extends Model
{
    public function getLastOnlineBookings($count = 0)
    {
        if ($count != 0) {
            $limit = "LIMIT " . $count;
        }
        $query = "SELECT B.*,G.first_name, G.last_name
                  FROM {$this->_CONF['db']['prefix']}_booking AS B
                    LEFT JOIN {$this->_CONF['db']['prefix']}_guests AS G
                    ON B.guest_id=G.id
                  WHERE B.method='online' AND B.online_is_paid=1
                  ORDER BY B.id DESC
                  {$limit}";
        $result = $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
        return $this->mapResults($guests = $result->GetRows());
    }

    public function add($data)
    {

        $check_in = $data['check_in'];
        $check_out = $data['check_out'];
        $affiliate_id = (int)$data['affiliate_id'] > 0 ? 1 : 0;

        if ($check_in < date('Y-m-d') || $check_in == '') {
            $errors[] = "Invalid Check_in";
        }
        if ($check_out <= date('Y-m-d') || $check_out == '') {
            $errors[] = "Invalid Check_out";
        }
        $guest_id = (int)$data['guest_id'];
        $payment_method = $data['payment_method'];
        if ($payment_method != 'pay_later' && $payment_method != 'pay_now') {
            $errors[] = "Invalid Payment Method";
        }
        $bookings = $data['bookings'];


        $selected_currency = $data['cur'];



        $system_currency = $this->_CONF['system_currency'];
        $systemCurrencyCode=$this->_CONF['system_currency_code'];
        $transCurrencyCode = $systemCurrencyCode;


        $lang = $data['lng'];
        if ($lang == 'geo') {
            $transLanguage = 'ge';
        } elseif ($lang == 'eng') {
            $transLanguage = 'en';
        } elseif ($lang == 'rus') {
            $transLanguage = 'ru';
        } else {
            $lang = 'geo';
            $transLanguage = 'ge';
        }


        $query = "select * from {$this->_CONF['db']['prefix']}_room_services";
        $result = $this->CONN->Execute($query);
        $services = $result->GetRows();

        foreach ($services as $service) {
            $tmp['id'] = $service['id'];
            $tmp['price'] = $service['price'];
            $tmp['type_id'] = $service['type_id'];
            $tmp['title'] = $this->FUNC->unpackData($service['title'], 'geo');
            $unpacked_services[$service['id']] = $tmp;
        }

        if (count($bookings) == 0) {
            $errors[] = "booking count=0";
        }

        $added_bookings = array();
        $added_bookings_total_price = 0;
        $added_bookings_total_first_day_price = 0;
        $total_reservation_guarantee_amount = 0;
        if (!$errors) {
            foreach ($bookings AS $booking) {

                $common_id = (int)$booking['common_id'];
                $food_id = (int)$booking['food_id'];
                $child_num = (int)$booking['child_count'];
                $adult_num = (int)$booking['adults_count'];
                $booking_comment = $booking['comment'];
                $daily_ind_discount = 0;
                $services_ids = ($booking['services'] != '') ? explode(',', $booking['services']) : array();

                $log = serialize($POST);
                $responsive_guest_id = $guest_id;
                $status_id = 1;
                $guest_obj = $this->CONN->GetRow("select * from {$this->_CONF['db']['prefix']}_guests where id={$guest_id}");
                $guest_ind_discount = $guest_obj['ind_discount'];
                $guest_tax = (int)$guest_obj['tax'];

                $affiliate_obj = $this->CONN->GetRow("select * from {$this->_CONF['db']['prefix']}_guests where id={$affiliate_id}");
                $affiliate_ind_discount = $affiliate_obj['ind_discount'];


                $query = "SELECT * FROM {$this->_CONF['db']['prefix']}_rooms_manager WHERE id={$common_id}";
                $room_type_obj = $this->CONN->GetRow($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());

                $food_obj = $this->CONN->GetRow("select * from {$this->_CONF['db']['prefix']}_room_services where id={$food_id}");

                //START Tavisufali otaxis dzebna

                $query = "select * from {$this->_CONF['db']['prefix']}_booking
                WHERE
                (check_in>='" . $check_in . "' AND check_in<'" . $check_out . "')
                OR
                (check_out>'" . $check_in . "' AND check_out<='" . $check_out . "')
                OR
                (check_in<='" . $check_in . "' AND check_out>='" . $check_out . "')";
                $result = $this->CONN->Execute($query);
                $conflict_bookings = $result->GetRows();
                $used_rooms_ids = array(0);
                foreach ($conflict_bookings AS $conflict_booking) {
                    if (!in_array($conflict_booking['room_id'], $used_rooms_ids)) {
                        $used_rooms_ids[] = $conflict_booking['room_id'];
                    }
                }
                $tmp_arr = implode(", ", $used_rooms_ids);
                $query = "select * from {$this->_CONF['db']['prefix']}_rooms
                where common_id={$common_id} AND id NOT IN ({$tmp_arr})";
                $result = $this->CONN->Execute($query);
                $free_rooms = $result->GetRows();

                if (count($free_rooms) == 0) {
                    $errors[] = "No Rooms are aviable!";
                } else {
                    $room_id = (int)$free_rooms[0]['id'];
                }
                //END TAVISUFALI OTAXIS DZEBNA

                //START archeuli otaxis fasebi
                $query = "SELECT *
			  FROM {$this->_CONF['db']['prefix']}_room_prices
		  	  WHERE common_id={$common_id} AND date>='{$check_in}' AND date<='$check_out'
		  	  ";
                $result = $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
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
                //END archeuli otaxis fasebi

                $query = "SELECT * FROM {$this->_CONF['db']['prefix']}_room_capacity WHERE id={$room_type_obj['capacity_id']}";
                $room_capacity_obj = $this->CONN->GetRow($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
                if ($adult_num > (int)$room_capacity_obj['capacity']) {
                    $adult_num = (int)$room_capacity_obj['capacity'];
                }

                if (!$errors) {
                    $query = "INSERT INTO {$BOOKING_TABLE} SET
								  room_id='{$room_id}',
								  guest_id={$guest_id},
								  responsive_guest_id={$responsive_guest_id},
								  affiliate_id={$affiliate_id},
								  administrator_id=0,
								  first_day_price=0,
								  daily_ind_discount={$daily_ind_discount},
								  check_in='{$check_in}',
								  check_out='{$check_out}',
								  adult_num='{$adult_num}',
								  child_num='{$child_num}',
								  food_id='{$food_id}',
								  method='online',
								  online_payment_type='{$payment_method}',
								  currency_coef={$currency_coef[$selected_currency]},
								  currency_type='{$selected_currency}',
								  currency_rates='" . serialize($currency_rates) . "',
								  status_id={$status_id},
								  created_at=NOW(),
								  comment='{$booking_comment}',
								  log='{$log}'";
                    $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
                    $booking_id = (int)$this->CONN->Insert_ID();

                    $startDate = strtotime($check_in);
                    $endDate = strtotime($check_out);
                    $guest_net_price = 0;
                    $guest_total_price = 0;
                    $affiliate_net_price = 0;
                    $services_total_price = 0;

                    //START LESS PERSON DISCOUNT [06.07.2016]
                    $lpd = 0;
                    if ($adult_num < (int)$room_capacity_obj['capacity']) {
                        $all_lpds = (array)json_decode($room_type_obj['lpd'], true);
                        if (is_null($all_lpds) || empty($all_lpds)) {
                            $lpd = 0;
                        } else {
                            $lpd = (int)$all_lpds[$adult_num];
                        }
                    }
                    //END LESS PERSON DISCOUNT

                    //START PAY NOW DISCOUNT
                    if ($payment_method == 'pay_now' && $room_type_obj['payments_method'] == 0) {
                        $pay_now_discount = $room_type_obj['pay_now_discount'];
                    } else {
                        $pay_now_discount = 0;
                    }
                    //END PAY NOW DISCOUNT

                    $cashBack = 0;
                    while ($startDate <= $endDate) {
                        $guest_daily_net_price = 0;
                        $affiliate_daily_net_price = 0;
                        if ($startDate < $endDate) {
                            $a = (double)$room_prices_by_date[date('Y-m-d', $startDate)]['price'];
                            $a += $child_num * (double)$room_type_obj['child_price'];
                            $food_price = (double)$food_obj['price'] * ($adult_num + $child_num);
                            $b = (double)$room_prices_by_date[date('Y-m-d', $startDate)]['discount'];
                            $c = $lpd;
                            $d = $pay_now_discount;//pay now
                            $e = $daily_ind_discount;
                            $e2 = 0;
                            $f = $guest_ind_discount;
                            $f2 = $affiliate_ind_discount;
                            $g = ($guest_tax == 0) ? 18 : 0;
                            $g = 0;//garedan yvela daregistrirdeba taxfreet
                            $g2 = 0;//affiliates tax free ar moqmedebs
                            $guest_daily_net_price = calculateNetPrice($a, $food_price, $b, $c, $d, $f, $e, $g);
                            $affiliate_daily_net_price = calculateNetPrice($a, $food_price, $b, $c, $d, $f2, $e2, $g2);
                            $guest_net_price += $guest_daily_net_price;
                            $affiliate_net_price += $affiliate_daily_net_price;
                        }
                        if ($startDate == strtotime($check_in)) {
                            $type = "check_in";
                            $first_day_price = $guest_daily_net_price;
                        } else if ($startDate == $endDate) {
                            $type = "check_out";
                        } else {
                            $type = "in_use";
                        }
                        $daily_comment = "a=" . $a . ", b=" . $b . ", food_price=" . $food_price . ", c=" . $c . ", d=" . $d . ", $f=" . $f . ", e=" . $e . ", g=" . $g;

                        $booking_day_id = addNewBookingDay($booking_id, date('Y-m-d', $startDate), $type, $guest_daily_net_price, $daily_comment);

                        foreach ($services_ids AS $service_id) {
                            if ($unpacked_services[$service_id]['type_id'] == 2 || $unpacked_services[$service_id]['type_id'] == 3) {
                                //2 ყოველდღიური სერვისები
                                //3 ყოველდღიური Extra სერვისები
                                if ($type != 'check_out') {
                                    $services_total_price += $unpacked_services[$service_id]['price'];
                                    addNewDailyService($booking_day_id, $unpacked_services[$service_id]['id'], $unpacked_services[$service_id]['type_id'], $unpacked_services[$service_id]['title'], $unpacked_services[$service_id]['price'], 'no_comment');
                                }
                            } elseif ($unpacked_services[$service_id]['type_id'] == 9) {
                                //9 დახვედრის სერვისები
                                if ($type == 'check_in') {
                                    $services_total_price += $unpacked_services[$service_id]['price'];
                                    $first_day_price += $unpacked_services[$service_id]['price'];
                                    addNewDailyService($booking_day_id, $unpacked_services[$service_id]['id'], $unpacked_services[$service_id]['type_id'], $unpacked_services[$service_id]['title'], $unpacked_services[$service_id]['price'], 'no_comment');
                                }
                            }
                        }
                        $startDate = strtotime('+1 day', $startDate);
                    }
                    $guest_total_price = $guest_net_price + $services_total_price;
                    $added_bookings[] = $booking_id;
                    $added_bookings_total_price += $guest_total_price;
                    $added_bookings_total_first_day_price += $first_day_price;

                    if ($payment_method == 'pay_now') {
                        $reservation_guarantee = $guest_total_price;
                    } else {
                        if ($hotelSettings['pay_later_guarantee_amount'] == -1) {
                            $reservation_guarantee = $first_day_price;
                        } elseif ($hotelSettings['pay_later_guarantee_amount'] == 0) {
                            $reservation_guarantee = 0;
                        } else {
                            $reservation_guarantee = $hotelSettings['pay_later_guarantee_amount'];
                        }
                    }

                    $query = "UPDATE {$this->_CONF['db']['prefix']}_booking SET
                                 first_day_price={$first_day_price},
                                 reservation_guarantee={$reservation_guarantee}
                                 WHERE id={$booking_id}";
                    $this->CONN->Execute($query) or $this->FUNC->ServerError(__FILE__, __LINE__, $this->CONN->ErrorMsg());
                    $total_reservation_guarantee_amount += $reservation_guarantee;
                    if ($affiliate_id != 0) {
                        $cashBack = $guest_net_price - $affiliate_net_price;
                        if ($cashBack > 0) {

                        } else {
                            $cashBack = 0;
                        }
                        addNewCashBack('affiliate', $guest_id, $affiliate_id, $booking_id, $cashBack, $check_out, $guest_net_price . "-" . $affiliate_net_price);
                    }
                    updateBookingFinances($booking_id);
                } else {
                    $tpl->addVar('booking', 'errors', $errors);
                    $tpl->parseIntoTemplate($DESIGN, $GLOBALS['SIDE'], 'booking');
                    exit;
                }
            }
            //-----------------------------------------------------
            $added_booking_ids = implode(",", $added_bookings);
            //$transAmount = $total_reservation_guarantee_amount * $currency_coef[$selected_currency];
            $transAmount = $total_reservation_guarantee_amount * $currency_coef['GEL'];
            $transAmount = round($transAmount, 2);
            //echo $total_reservation_guarantee_amount."*100*".round($currency_coef[$selected_currency],2)."=".$transAmount;
            if ($payment_method == 'pay_later' && $hotelSettings['pay_later_guarantee_amount'] == 0) {
                //unda daregistrirdes  regularuli gadaxda
                $result = $tbcPayment->registerRP($transCurrencyCode, $_SERVER['REMOTE_ADDR'], $_CONF['tbc']['destination'], $transLanguage, '', '1299');
                if (isset($result['TRANSACTION_ID']) && $result['TRANSACTION_ID'] != '') {
                    addBookingTransaction($guest_id, $added_booking_ids, $result['TRANSACTION_ID'], 0, 'rp_registration', $transAmount, $selected_currency, $currency_coef[$selected_currency], serialize($currency_rates));
                    echo $tbcPayment->getRedirectHtmlSource($result['TRANSACTION_ID']);
                } else {
                    foreach ($added_bookings as $added_booking) {
                        deleteBooking($added_booking);
                    }
                    $tpl->addVar('booking', 'message', 'Payment Server Error:');
                }
                $tpl->addVar('booking', 'message', "payment_method=" . $payment_method . "  pay_later_guarantee_amount" . $hotelSettings['pay_later_guarantee_amount']);
            } else {
                $tpl->addVar('booking', 'message', "payment_method=" . $payment_method . "  pay_later_guarantee_amount" . $hotelSettings['pay_later_guarantee_amount']);

                if ($tbcPayment->sendTransData($transAmount * 100, $transCurrencyCode, $_SERVER['REMOTE_ADDR'], $_CONF['tbc']['destination'], $transLanguage, $added_booking_ids)) {
                    $trans_id = $tbcPayment->TRANSACTION_ID;
                    $participates_in_accounting = 0;
                    if (count($added_bookings) > 1) {
                        $destination = 'online_multi_booking';
                    } else {
                        $destination = 'online_single_booking';
                    }
                    addBookingTransaction($guest_id, $added_booking_ids, $trans_id, $participates_in_accounting, $destination, $transAmount, $selected_currency, $currency_coef[$selected_currency], serialize($currency_rates));
                    $tpl->addVar('booking', 'message', $tbcPayment->getRedirectHtmlSource($trans_id));
                } else {
                    foreach ($added_bookings as $added_booking) {
                        deleteBooking($added_booking);
                    }

                    $tpl->addVar('booking', 'message', 'Payment Server Error');
                }
            }
        } else {
            $tpl->addVar('booking', 'errors', $errors);
        }

    }

} 