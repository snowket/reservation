<?

if(isset($_POST['action']) && $_POST['action']=='add'){

       $id=addBooking($_POST['b_guest_id'],$_POST['general_price'],$_POST['custom_price'],$_POST['check_in'],$_POST['check_out'],$_POST['room_id'],$_POST['from_calendar'],$_POST['to_calendar'],$_POST['adult_num'],$_POST['booking_comment'],$_POST['services']);
	header('location:index.php?m=conference_hall&tab=conference_hall_list&action=view&booking_id='.$id);
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
	case "+1y":
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

$rooms=getCH_room();
$services=getCH_service();
$bookings=getAllBookingsSorted($days_before,$days_after);
	$TMPL->addVar('TMPL_countries', getAllCountries());
	$TMPL->addVar('TMPL_all_days', getPeriodArray($days_before, $days_after));
	$TMPL->addVar("TMPL_users", $result->GetRows());
	$TMPL->addVar("TMPL_all_rooms", $rooms);
	$TMPL->addVar("TMPL_bookings", $bookings);
	$TMPL->addVar("TMPL_services", $services);

	$TMPL->ParseIntoVar($_CENTER,"conference_hall");


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
