<?
session_start();
$get=$_GET;
$post=$_POST;

if($post['action']=='save_booking'){
	$dds=saveBooking_double($post);
	if($dds['status']=='OK'){
		$_SESSION['dbl_save'] = 'OK';
		$_SESSION['dbl_save_data'] = $dds['data'];
		header('location:index.php?m=booking_management&tab=booking_dbl_list&action=view&booking_id='.$dds['data']);
		exit;
	}else{
		$_SESSION['dbl_save'] ='NO';
		$_SESSION['dbl_save_data'] = $dds['data'];
		header('location:index.php?m=booking_management&tab=booking_dbl&type_id=0&block_id=0');
		exit;
	}

}elseif($post['action']=='edit_booking'){

	$dds=editBooking_double($post);
	if($dds['status']=='OK'){
		$_SESSION['dbl_save'] = 'OK';
		$_SESSION['dbl_save_data'] = $dds['data'];
		header('location:index.php?m=booking_management&tab=booking_dbl_list&action=view&booking_id='.$dds['data']);
		exit;

	}else{
		$_SESSION['dbl_save'] ='NO';
		$_SESSION['dbl_save_data'] = $dds['data'];
		header('location:index.php?m=booking_management&tab=booking_dbl_list&action=view&booking_id='.$dds['data']);
		exit;

	}

}
if($get['action']=='view'){

}else{
	if(!isset($_GET['type_id']) && !isset($_GET['block_id'])){
		 $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms_manager";
	    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
	    $data = $result->GetRows();

	    $query = "SELECT * FROM {$_CONF['db']['prefix']}_hotel_blocks";
	    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
	    $blocks = $result->GetRows();

	    if (count($blocks) == 0) {
	        die('NO BlOCKS');
	    } else if (count($blocks) == 1) {
	        $FUNC->Redirect($SELF . '&type_id=0&block_id[]=' . $blocks[0]['id']);
	    } else {
	        $FUNC->Redirect($SELF . '&type_id=0&block_id=0');
	    }
	}
	$type_ids = (array)$_GET['type_id'];
    $block_ids =$_GET['block_id'];
	  $rooms_manager_query = "WHERE 1=1 ";
    ((count($type_ids)==1&& $type_ids[0]!=0)||(count($type_ids)>1)) ? $rooms_manager_query .= " AND type_id IN (".implode(',',$type_ids).")" : "";
    ((count($block_ids)==1&& $block_ids[0]!=0) || count($block_ids)>1) ? $rooms_manager_query .= " AND block_id IN (".implode(',',$block_ids).")" : "";

    //moaqvs shesabamisi PRICE PLAN-bi
    $query = "SELECT *
			FROM {$_CONF['db']['prefix']}_rooms_manager
		  	{$rooms_manager_query}";


    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $data = $result->GetRows();
    if (!empty($data)) {
        foreach ($data AS $common) {
            $rooms_manager_arr[$common['id']] = $common;
        }
    }
          $roomCapacity = GetRoomCapacity();
        foreach ($roomCapacity as $key => $value) {
            $rcArr[$value['id']] = $value;
        }
        $TMPL->addVar('TMPL_capacity', $rcArr);
        $TMPL->addVar('TMPL_countries', getAllCountries());
	$TMPL->addVar('TMPL_all_food', GetServices(6));
	$TMPL->addVar('TMPL_rooms_manager_arr', $rooms_manager_arr);
	$html = $TMPL->parseIntoString("booking_dbl");
	$_CENTER = slib_compress_html($html);
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
function addNewGuestDbl($corp,$tax,$idnumb,$name){
	global $CONN,$_CONF,$ALOG,$FUNC;
	$pieces = explode(" ", $name);
	if(count($pieces)==2){
		$sname=$pieces[0];
		$lname=$pieces[1];
	}else{
		$lname=array_pop($pieces);
		$sname=implode('', $pieces);
	}
	$query="INSERT INTO cms_guests SET type='{$corp}',publish=1,tax={$tax},id_number={$idnumb},first_name='{$sname}',last_name='{$lname}'";
	$CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
	$user_id = (int)$CONN->Insert_ID();
	return $user_id;
}

function reflectBooking($book,$vars,$i){
			global $CONN,$_CONF,$ALOG,$FUNC;
			$check_in = date('Y-m-d',strtotime($vars['check_in_x'][$i]));
			$check_out = date('Y-m-d',strtotime($vars['check_out_x'][$i]));
			$booking_id = $book;
	    $room_id = $vars['room_id'][$i];
	    $food_id = $vars['food_id'][$i];
	    $person = $vars['person_count'][$i];
	    $child = $vars['child_count'][$i];

	    $recalculate_price = false;
		   $old_booking = getBookingById($booking_id);
			 if ($old_booking['food_id'] == $food_id) {
						//nothing to do
				} else {
						//fasis sheucvlelad gadayavs sxva otaxshi
						$query = "UPDATE {$_CONF['db']['prefix']}_booking SET
													food_id = {$food_id},
								updated_at=NOW()
								WHERE id={$booking_id}";
						$result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
						$recalculate_price = true;
						$msg['success'][] = "booking room changed successfully!";
				}
				if ($old_booking['child_num'] == $child) {
						 //nothing to do
				 } else {
						 //fasis sheucvlelad gadayavs sxva otaxshi
						 $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
													 child_num = {$child},
								 updated_at=NOW()
								 WHERE id={$booking_id}";
						 $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
						 $recalculate_price = true;
						 $msg['success'][] = "booking room changed successfully!";
				 }
				 if ($old_booking['adult_num'] == $person) {
							//nothing to do
					} else {
							//fasis sheucvlelad gadayavs sxva otaxshi
							$query = "UPDATE {$_CONF['db']['prefix']}_booking SET
														adult_num = {$person},
									updated_at=NOW()
									WHERE id={$booking_id}";
							$result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
							$recalculate_price = true;
							$msg['success'][] = "booking room changed successfully!";
					}
	    if ($old_booking['room_id'] == $room_id) {
	        //nothing to do
	    } else {
	        //fasis sheucvlelad gadayavs sxva otaxshi
	        $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
	                      room_id = {$room_id},
						  updated_at=NOW()
						  WHERE id={$booking_id}";
	        $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
					$recalculate_price = true;
	        $msg['success'][] = "booking room changed successfully!";
	    }
			if ($old_booking['guest_id'] == $vars['b_guest_id']) {
	        //nothing to do
	    } else {
	        //fasis sheucvlelad gadayavs sxva otaxshi
	        $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
	                      guest_id = {$vars['b_guest_id']},
						  updated_at=NOW()
						  WHERE id={$booking_id}";
	        $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
					$recalculate_price = true;
	        $msg['success'][] = "booking Guest changed successfully!";
	    }

	    if ($old_booking['check_in'] != $check_in) {
	        $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily
				  WHERE active=1 AND booking_id=" . $booking_id;
	        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
	        $old_booking_days = $result->GetRows();

	        $old_check_in_mk = mktime(1, 0, 0, substr($old_booking['check_in'], 5, 2), substr($old_booking['check_in'], 8, 2), substr($old_booking['check_in'], 0, 4));
	        $new_check_in_mk = mktime(1, 0, 0, substr($check_in, 5, 2), substr($check_in, 8, 2), substr($check_in, 0, 4));

	        $diff = $new_check_in_mk - $old_check_in_mk;
	        foreach ($old_booking_days as $old_booking_day) {
	            $old_booking_day_check_in_mk = mktime(1, 0, 0, substr($old_booking_day['date'], 5, 2), substr($old_booking_day['date'], 8, 2), substr($old_booking_day['date'], 0, 4));
	            //$old_booking_day_check_in_mk =$old_booking_day['mk_time'];
	            $query = "UPDATE {$_CONF['db']['prefix']}_booking_daily SET
	                                 date='" . date('Y-m-d', ($old_booking_day_check_in_mk + $diff)) . "'
	                                 WHERE id=" . $old_booking_day['id'];
	            $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
	        }

	        $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
	                                 check_in='" . $check_in . "',
	                                 check_out='" . $check_out . "'
	                                 WHERE id=" . $booking_id;
	        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
	        $recalculate_price = true;
	        $msg['success'][] = "booking dates changed successfully!";
	    }
	    if ($recalculate_price) {
	        $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily
				  WHERE active=1 AND booking_id=" . $booking_id;
	        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
	        $booking_days = $result->GetRows();

	        $query = "SELECT * FROM {$_CONF['db']['prefix']}_rooms
				  WHERE id=" . $room_id;
	        $room = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

	        $query = "SELECT * FROM {$_CONF['db']['prefix']}_room_prices
				  WHERE common_id=" . $room['common_id'] . " AND date>='" . $check_in . "' AND date<='" . $check_out . "'";

	        $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
	        $room_prices = $result->GetRows();
	        $room_prices = mapArrayByProp($room_prices, 'date');


	        $common_obj = $CONN->GetRow("SELECT * FROM {$_CONF['db']['prefix']}_rooms_manager WHERE id=" . $room['common_id']) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
	        $guest_obj = $CONN->GetRow("SELECT * FROM {$_CONF['db']['prefix']}_guests WHERE id=" . $old_booking['guest_id']) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

	        $food_id = $old_booking['food_id'];
	        $food_obj = $CONN->GetRow("select * from {$_CONF['db']['prefix']}_room_services where id={$food_id}");


	        $daily_discount = $old_booking['daily_ind_discount'];
	        $adult_num = (int)$old_booking['adult_num'];
	        $child_num = (int)$old_booking['child_num'];
	        $food_price = (double)$food_obj['price'] * ($adult_num + $child_num);
	        $one_person_discount = 0;
	        if ($adult_num == 1) {
	            $one_person_discount = $common_obj['one_person_discount'];
	        }
	        $guest_ind_discount = $guest_obj['ind_discount'];
	        if ($guest_obj['tax'] == 0) {
	            $guest_tax_discount = 18;
	        }

	        foreach ($booking_days as $booking_day) {

	            $a = $room_prices[$booking_day['date']]['price'];
	            $b = $room_prices[$booking_day['date']]['discount'];
	            $pay_now_discount = 0;
	            if ($booking_day['type'] != 'check_out') {
	                $new_price = calculateNetPrice($a, $food_price, $b, $one_person_discount, $pay_now_discount, $guest_ind_discount, $daily_discount, $guest_tax_discount);
	                $msg['progress'][] = $booking_day['date'] . "->" . $a;
	                $query = "UPDATE {$_CONF['db']['prefix']}_booking_daily SET
	                                 price=" . $new_price . "
	                                 WHERE id=" . $booking_day['id'];
	                $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
	            }
	        }
	        updateBookingFinances($booking_id);
	    }

}
function editBooking_double($vars){
	global $CONN,$_CONF,$ALOG,$FUNC;
	$db_status=$vars['master_id'];
	$errors=array();

	$booking_array=explode(',', $vars['booked_array']);
	foreach($vars['room_id'] as $i=>$value){
		if(isset($vars['booking_ids'][$i])){
				reflectBooking($vars['booking_ids'][$i],$vars,$i);
				continue;
		}

		$conflict_bookings=array();
		$check_in = date('Y-m-d',strtotime($vars['check_in_x'][$i]));
		$check_out = date('Y-m-d',strtotime($vars['check_out_x'][$i]));
		$days_count = getDiffBetweenTwoDates($check_in, $check_out);
		$room_ids = (array)$vars['room_id'][$i];

		$daily_ind_discount = (double)$vars['day_out'];
		$booking_comment = $vars[$i]['booking_comment'];
		$sum_fixed_discount = (double)$vars['all_out'];
		$food_id = (int)$vars['food_id'][$i];

		$one_booking_fixed_discount = round($sum_fixed_discount / count($vars['room_id']), 2);
		//p($one_booking_fixed_discount); exit;
		(int)$vars['person_count'][$i] < 1 ? $errors[] = "adults count<1" : $adult_num = (int)$vars['person_count'][$i];
		$child_num = (int)$vars['child_num'][$i];

		if ($check_in < CURRENT_DATE || $check_in == '') {
				$errors[$i][] = "araswori check_in";
		}
		if ($check_out <= CURRENT_DATE || $check_out == '') {
				$errors[$i][] = "araswori check_out";
		}
		$guest_id = (int)$vars['b_guest_id'];
		if($guest_id==0){
			$guest_id=addNewGuestDbl($vars['group1'],$vars['group2'],$vars['co_number'],$vars['co_name']);
		}
		#dd($errors);
		$responsive_guest_id = $guest_id;
		$affiliate_id = (int)$vars[$i]['b_affiliate_id'];
		$status_id = 1;

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
								) AND room_id IN(".implode(',',$room_ids).") ";
		$result = $CONN->Execute($query);
		$conflict_bookings = $result->GetRows();

		if(!empty($conflict_bookings)){
			$errors[$i][]="მოცემლ პერიოდში ჯავშანი უკვე არსებობს";
		};

		if (!empty($errors[$i])) {
			continue;
		}

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
				for ($k = ($room_capacity_obj['capacity'] - 1); $k > 0; $k--) {
						$lpd[$k] = ((int)$lpd[$k] == 0) ? $old_discount : (int)$lpd[$k];
						$old_discount = $lpd[$k];
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
										$errors[$i][] = "fasi ar aris mititebuli";
								}
								$room_prices_by_date[$room_price['date']]['price'] = $room_price['price'];
								$room_prices_by_date[$room_price['date']]['discount'] = $room_price['discount'];
						}
				} else {
						$errors{$i}[] = "fasi ar aris mititebuli";
				}

				if (empty($errors[$i])) {
						$query = "INSERT INTO cms_booking SET
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
													dbl_res_id={$db_status},
													comment='{$booking_comment}',
													log='" . serialize(array("POST" => $_POST, "GET" => $_GET)) . "'";
						$CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
						$booking_id = (int)$CONN->Insert_ID();
						if($db_status==0){
							$db_status=$booking_id;
							$sq="UPDATE cms_booking SET dbl_res_id=".$db_status." WHERE id=".$db_status;
							$CONN->Execute($sq) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

						}
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
												$e += round($one_booking_fixed_discount / $days_count, 2);
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
								"ჯგუფური ჯავშანი",
								"Administrator Added New Booking[{$booking_id}] Guest[{$guest_id}] room[{$room_id}] check_in[{$check_in}] check_out[{$check_out}]",
								$_SESSION['pcms_user_id'],
								serialize($vars)
						);
					}
			}
	}
	if(!empty($errors)){
		return array('status'=>'NO','data'=>$errors);
	}else{
		saveDblBookingInfoS($vars,$db_status);
		return array('status'=>'OK','data'=>$db_status);
	}
}
function saveBooking_double($vars){
	global $CONN,$_CONF,$ALOG,$FUNC;
	$db_status=0;
	for ($i=0; $i < count($vars['room_id']) ; $i++) {
		$check_in = date('Y-m-d',strtotime($vars['check_in_x'][$i]));
		$check_out = date('Y-m-d',strtotime($vars['check_out_x'][$i]));
		$days_count = getDiffBetweenTwoDates($check_in, $check_out);
		$room_ids = (array)$vars['room_id'][$i];

		$daily_ind_discount = 0;
		$booking_comment = $vars[$i]['booking_comment'];
		$sum_fixed_discount = (double)$vars['all_out'];
		$food_id = (int)$vars['food_id'][$i];

		$one_booking_fixed_discount = round($sum_fixed_discount / count($vars['room_id']), 2);
		(int)$vars['person_count'][$i] < 1 ? $errors[] = "adults count<1" : $adult_num = (int)$vars['person_count'][$i];
		$child_num = (int)$vars['child_num'][$i];

		if ($check_in < CURRENT_DATE || $check_in == '') {
				$errors[$i][] = "araswori check_in";
		}
		if ($check_out <= CURRENT_DATE || $check_out == '') {
				$errors[$i][] = "araswori check_out";
		}
		$guest_id = (int)$vars['b_guest_id'];
		if($guest_id==0){
			$guest_id=addNewGuestDbl($vars['group1'],$vars['group2'],$vars['co_number'],$vars['co_name']);
		}
		#dd($errors);
		$responsive_guest_id = $guest_id;
		$affiliate_id = (int)$vars[$i]['b_affiliate_id'];
		$status_id = 1;

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
		$result = $CONN->Execute($query)?$CONN->Execute($query):array();
		$conflict_bookings = $result->GetRows();

		if(!empty($conflict_bookings)){

			$errors[$i][]="მოცემლ პერიოდში ჯავშანი უკვე არსებობს";
		};

		if (!empty($errors[$i])) {
			return array('status'=>'NO','data'=>$errors);
		}
		//---------------------------------------------END CHECKING ON AVAILABILITY -
		//---------------------------------------------START MULTIPLE BONOING FOR

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
				for ($k = ($room_capacity_obj['capacity'] - 1); $k > 0; $k--) {
						$lpd[$k] = ((int)$lpd[$k] == 0) ? $old_discount : (int)$lpd[$k];
						$old_discount = $lpd[$k];
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
						$query = "INSERT INTO cms_booking SET
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
													dbl_res_id={$db_status},
													comment='{$booking_comment}',
													log='" . serialize(array("POST" => $_POST, "GET" => $_GET)) . "'";
						$CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
						$booking_id = (int)$CONN->Insert_ID();
						if($db_status==0){
							$db_status=$booking_id;
							$sq="UPDATE cms_booking SET dbl_res_id=".$db_status." WHERE id=".$db_status;
							$CONN->Execute($sq) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

						}
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
												$e += round($one_booking_fixed_discount / $days_count, 2);
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
								"ჯგუფური ჯავშანი",
								"Administrator Added New Booking[{$booking_id}] Guest[{$guest_id}] room[{$room_id}] check_in[{$check_in}] check_out[{$check_out}]",
								$_SESSION['pcms_user_id'],
								serialize($vars)
						);
					}
			}
	}
	if(!empty($errors)){
		return array('status'=>'NO','data'=>$errors);
	}else{
		saveDblBookingInfoS($vars,$db_status);
		return array('status'=>'OK','data'=>$db_status);
	}

}

function saveDblBookingInfoS($vars,$id){
	global $CONN,$_CONF,$ALOG,$FUNC;
		$booking_name=$vars['gr_name'];
	$booking_user=$vars['co_name'];
	$booking_user_id=$vars['b_guest_id'];
	$booking_co_person=$vars['co_io_name'];
	$booking_co_street=$vars['co_street'];
	$booking_co_tel=$vars['co_tel'];
	$booking_co_mail=$vars['co_mail'];
	$booking_check_in=date('Y-m-d',strtotime($vars['check_in']));
	$booking_check_out=date('Y-m-d',strtotime($vars['check_out']));
	$query="INSERT INTO cms_booking_dbl SET booking_name='{$booking_name}',booking_user='{$booking_user}',booking_user_id={$booking_user_id},booking_co_person='{$booking_co_person}',booking_co_street='{$booking_co_street}',booking_co_tel='{$booking_co_tel}',booking_co_mail='{$booking_co_mail}',booking_check_in='{$booking_check_in}',booking_check_out='{$booking_check_out}',booking_master_id={$id}";
	$CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
}
function updateDblBookingInfoS($vars,$id){
	global $CONN,$_CONF,$ALOG,$FUNC;
	$booking_name=$vars['gr_name']?$vars['gr_name']:'';
	$booking_user=$vars['co_name']?$vars['co_name']:'';
	$booking_user_id=$vars['b_guest_id']?$vars['b_guest_id']:'';
	$booking_co_person=$vars['co_io_name']?$vars['co_io_name']:'';
	$booking_co_street=$vars['co_street']?$vars['co_street']:'';
	$booking_co_tel=$vars['co_tel']?$vars['co_tel']:'';
	$booking_co_mail=$vars['co_mail']?$vars['co_mail']:'';
	$booking_check_in=date('Y-m-d',strtotime($vars['check_in']));
	$booking_check_out=date('Y-m-d',strtotime($vars['check_out']));
	$query="UPDATE cms_booking_dbl SET booking_name='{$booking_name}',booking_user='{$booking_user}',booking_user_id={$booking_user_id},booking_co_person='{$booking_co_person}',booking_co_street='{$booking_co_street}',booking_co_tel='{$booking_co_tel}',booking_co_mail='{$booking_co_mail}',booking_check_in='{$booking_check_in}',booking_check_out='{$booking_check_out}' WHERE booking_master_id={$id}";
	$CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
}
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
