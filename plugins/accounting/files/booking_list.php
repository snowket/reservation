<?

if (!defined('ALLOW_ACCESS')) exit;
//ini_set("error_reporting", E_ALL & ~E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE);
$POST = $VALIDATOR->ConvertSpecialChars($_POST);
$GET = $VALIDATOR->ConvertSpecialChars($_GET);
if($_GET['download_word']==1){
    $stringJSON = get_magic_quotes_gpc()?stripslashes($_POST['json']) : $_POST['json'];
    $_POST=json_decode($stringJSON,true);
}


$settings = getSettings();

$where_clause = "";
(isset($_GET['in_start_date'])&&!empty($_GET['in_start_date'])) ? $where_clause .= " AND check_in>='" . $_GET['in_start_date'] . "'" : $where_clause .= "";
(isset($_GET['in_end_date'])&&!empty($_GET['in_end_date'])) ? $where_clause .= " AND check_in<='" . $_GET['in_end_date'] . "'" : $where_clause .= "";

(isset($_GET['out_start_date'])&&!empty($_GET['out_start_date'])) ? $where_clause .= " AND check_out>='" . $_GET['out_start_date'] . "'" : $where_clause .= "";
(isset($_GET['out_end_date'])&&!empty($_GET['out_end_date'])) ? $where_clause .= " AND check_out<='" . $_GET['out_end_date'] . "'" : $where_clause .= "";

(isset($_GET['method'])&&!empty($_GET['method'])) ? $where_clause .= " AND method='" . $_GET['method'] . "'" : $where_clause .= "";

(isset($_GET['active']) && $_GET['active']==3) ? $where_clause .= " AND B.active='0'" : $where_clause .= " AND B.active='1'" ;
(isset($_GET['status_id']) && $_GET['status_id'] != '') ? $where_clause .= " AND status_id=" . $_GET['status_id'] : $where_clause .= "";
(isset($_GET['room__type_id']) && $_GET['room__type_id'] != '') ? $where_clause .= " AND RT.id=" . $_GET['room__type_id'] : $where_clause .= "";

(isset($_GET['guest_id']) && $_GET['guest_id'] != '') ? $where_clause .= " AND G.id_number LIKE '%" . $_GET['guest_id'] . "%'" : $where_clause .= '';
(isset($_GET['guest_name']) && $_GET['guest_name'] != '') ? $where_clause .= " AND CONCAT(G.first_name, ' ', G.last_name ) LIKE '%" . $_GET['guest_name'] . "%'" : $where_clause .= '';
(isset($_GET['guest_type']) && $_GET['guest_type'] != '') ? $where_clause .= " AND G.type='" . $_GET['guest_type'] . "'" : $where_clause .= "";
(isset($_GET['room_id']) && $_GET['room_id'] != '') ? $where_clause .= " AND B.room_id='" . $_GET['room_id'] . "'" : $where_clause .= "";


(isset($_GET['booking_id'])&&!empty($_GET['booking_id'])) ? $where_clause = " AND B.id='" . $_GET['booking_id'] . "'" : $where_clause .= "";
if(isset($_GET['invoice_id'])&&!empty($_GET['invoice_id'])){
    $where_clause = " AND B.id IN(0";
    $invoice=getInvoiceByID((int)$_GET['invoice_id']);
    if($invoice){
        $parts=explode('|',$invoice['uniq_identifier']);
        for($i=0; $i<count($parts); $i++){
            if($parts[$i]==''){
                continue;
            }else{
                $parts2=explode(':',$parts[$i]);
                $invoice_details[$parts2[0]][$parts2[1]]=1;
                $where_clause .=','.$parts2[0];
            }
        }
    }else{
        //ar moidzebna
    }
    $where_clause .=')';
}else{
    $where_clause .= "";
}


if ($_POST['action'] == 'get_excel') {

    $query = "SELECT id,first_name,last_name,type,id_number FROM {$_CONF['db']['prefix']}_guests";
    $guests_result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    foreach ($guests_result AS $guest) {
        $guests[$guest['id']] = $guest;
    }
    $pageBar="";
    $bookings = getJoinedBookings($where_clause);
    $food=getAllServices(6);
    $statuses=getAllStatuses();
    $guests=getAllGuest();
    $rooms=GetAllRooms();

    require_once('classes/PHPExcel/PHPExcel.php');
    require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Transactions');

    $sheet->setCellValue("A1", $TEXT['booking_list']['guest']);
    $sheet->setCellValue("B1", $TEXT['booking_list']['affiliate']);
    $sheet->setCellValue("C1", $TEXT['booking_list']['room_id']);
    $sheet->setCellValue("D1", $TEXT['booking_list']['check_in']);
    $sheet->setCellValue("E1", $TEXT['booking_list']['check_out']);
    $sheet->setCellValue("F1", $TEXT['booking_list']['method']);
    $sheet->setCellValue("G1", $TEXT['booking_list']['status']);
    $sheet->setCellValue("H1", $TEXT['booking_list']['food']);
    $sheet->setCellValue("I1", $TEXT['booking_list']['debt']);
    $sheet->setCellValue("J1", $TEXT['booking_list']['paid']);


    $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1:J1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A1:J1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A1:J1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));

    foreach(range('A','J') as $columnID) {
        $sheet->getColumnDimension($columnID)
            ->setAutoSize(true);
    }

    for ($i = 0; $i < count($bookings); $i++) {
        $firstname = $bookings[$i]['first_name'];
        $lastname = $bookings[$i]['last_name'];
        $guest=($lastname=="")?$firstname:$firstname." ".$lastname;

        $affiliate_firstname = $guests[$bookings[$i]['affiliate_id']]['first_name'];
        $affiliate_lastname = $guests[$bookings[$i]['affiliate_id']]['last_name'];
        $affiliate=($affiliate_lastname=="")?$affiliate_firstname:$affiliate_firstname." ".$affiliate_lastname;

        $room=$rooms[$bookings[$i]['room_id']]['name'];
        $check_in=$bookings[$i]['check_in'];
        $check_out=$bookings[$i]['check_out'];
        $method=$TEXT['booking_list']['filter_modal']['method'][$bookings[$i]['method']];
        $status=$statuses[$bookings[$i]['status_id']]['title'];
        $food_type=$food[$bookings[$i]['food_id']]['title'];
        $debt=(int)($bookings[$i]['accommodation_price']*100);
        $debt+=(int)($bookings[$i]['services_price']*100);
        $debt-=(int)($bookings[$i]['paid_amount']*100);
        $debt-=(int)($bookings[$i]['services_paid_amount']*100);
        $debt=$debt/100;

        $sheet->setCellValueByColumnAndRow(0, $i +2, $guest);
        $sheet->setCellValueByColumnAndRow(1, $i +2, $affiliate);
        $sheet->setCellValueByColumnAndRow(2, $i +2, $room);
        $sheet->setCellValueByColumnAndRow(3, $i +2, $check_in);
        $sheet->setCellValueByColumnAndRow(4, $i +2, $check_out);
        $sheet->setCellValueByColumnAndRow(5, $i +2, $method);
        $sheet->setCellValueByColumnAndRow(6, $i +2, $status);
        $sheet->setCellValueByColumnAndRow(7, $i +2, $food_type);
        $sheet->setCellValueByColumnAndRow(8, $i +2, $debt);
        $sheet->setCellValueByColumnAndRow(9, $i +2, $bookings[$i]['paid_amount']);

        $cell_name = $columnNames[$columnNumber] . $rowNumber;

        $sheet->getStyle('A'.($i +2).':J'.($i +2))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');


    }
    header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
    header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
    header ( "Cache-Control: no-cache, must-revalidate" );
    header ( "Pragma: no-cache" );
    header ( "Content-type: application/vnd.ms-excel" );
    header ( "Content-Disposition: attachment; filename=booking_list.xls" );

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');

}else if ($POST['action'] == "add_service") {
   // addNewDailyService((int)$POST['booking_daily_id'], (int)$POST['service_selector'], (int)$POST['service_type'], $POST['service_title'], $POST['service_price'], '');
    //header("Location: index.php?m=booking_management&tab=booking_list&action=view&booking_id=".(int)$GET['booking_id']);
} elseif ($POST['action'] == 'add_amount_paid') {
    $booking_id = $_GET['booking_id'];
    $booking = getBookingById($booking_id);
    $destination = $POST['destination'];
    if($destination=='extra-service'&&(int)$booking['responsive_guest_id']!=0){
        $guest=getGuestByID($booking['responsive_guest_id']);
    }else{
        $guest=getGuestByID($booking['guest_id']);
    }
    $current_balance=(float)$guest['balance'];
    $amount = $POST['amount_pay'];
    $payment_method_id = $POST['payment_method_id'];

    if($payment_method_id==5){
        if($amount<=$current_balance){
            $new_balance=$current_balance-$amount;
            updateGuestBalance($guest['id'],$new_balance);
            addBookingTransaction('local',$booking['guest_id'], $booking_id, $_SESSION['pcms_user_id'], $amount, $payment_method_id, $destination);
            header("Location:".$SELF.'&action=view&booking_id='.$booking_id);
        }else{
            if($current_balance>0){
                $new_balance=0;
                updateGuestBalance($guest['id'],0);
                addBookingTransaction('local',$booking['guest_id'], $booking_id, $_SESSION['pcms_user_id'], $current_balance, $payment_method_id, $destination);
                header("Location:".$SELF.'&action=view&booking_id='.$booking_id);
            }else{
                die('guest_balance=0');
            }
        }
    }elseif($payment_method_id==3) {
        require_once('../ipn/tbc/TbcPayment.class.php');
        require_once('../ipn/tbc/error_codes.php');
        $tbcPayment = new TbcPayment(
            $_CONF['tbc']['MerchantHandler'],
            $_CONF['tbc']['ClientHandler'],
            $_CONF['tbc']['cert_pass'],
            $_CONF['tbc']['p12_file']
        );
        $errors = $tbcPayment->GetErrors();
        $system_currency = $_CONF['system_currency'];
        if ($system_currency == 'GEL') {
            $transCurrencyCode = 981;
        } elseif ($system_currency == 'EUR') {
            $transCurrencyCode = 978;
        } elseif ($system_currency = 'USD') {
            $transCurrencyCode = 840;
        } else {
            $errors[] = "invalid selected currency";
        }
        if (!empty($errors)) {
            p($tbcPayment->GetErrors());
            exit;
        }
        $result=$tbcPayment->makeRP($POST['recc_pmnt_id'], $amount*100, $transCurrencyCode, $_SERVER['REMOTE_ADDR'], $_CONF['tbc']['destination'], 'ge');
        if($result['RESULT']=='OK'){
            $new_transaction_id=$result['TRANSACTION_ID'];
            addFullBookingTransaction($booking['guest_id'], $booking_id, 1, $destination, $new_transaction_id, '', $amount, $system_currency, '', '', $result['RESULT'], $postback_message, 'by administrator makeRP');
            updateBookingFinances($booking['id']);
        }else{
            $errors[]=$error_codes[$result['RESULT_CODE']]['full'];
        }
    }else{
        addBookingTransaction('global',$booking['guest_id'], $booking_id, $_SESSION['pcms_user_id'], $amount, $payment_method_id, $destination);
        header("Location:".$SELF.'&action=view&booking_id='.$booking_id);
    }
}elseif ($POST['action'] == 'withdraw_or_add_to_balance') {
    $booking_id = $_GET['booking_id'];
    $booking = getBookingById($booking_id);
    $destination = $POST['destination'];
    if($destination=='extra-service'&&(int)$booking['responsive_guest_id']!=0){
        $guest=getGuestByID($booking['responsive_guest_id']);
    }else{
        $guest=getGuestByID($booking['guest_id']);
    }
    $current_balance=(float)$guest['balance'];
    $amount = $POST['amount_pay'];
    $payment_method_id = $POST['payment_method_id'];

    if($payment_method_id==5){
        $new_balance=$current_balance+$amount;
        updateGuestBalance($guest['id'],$new_balance);
        addBookingTransaction('local',$booking['guest_id'], $booking_id, $_SESSION['pcms_user_id'], -$amount, $payment_method_id, $destination);
        header("Location:".$SELF.'&action=view&booking_id='.$booking_id);
    }elseif($payment_method_id==1){
        addBookingTransaction('global',$booking['guest_id'], $booking_id, $_SESSION['pcms_user_id'], -$amount, $payment_method_id, $destination);
        header("Location:".$SELF.'&action=view&booking_id='.$booking_id);
    }else{
        die('tanxis gatanis araswori metodi!');
    }
} elseif ($POST['action'] == 'change_status') {
    changeStatus((int)$_GET['booking_id'], (int)$POST['status_id']);
}elseif ($POST['action'] == "del_booking") {
    if($_SESSION['pcms_user_group']==1){
        //p($GET);p($POST);exit;

        $booking_id = (int)$GET['booking_id'];
        $total_price=$POST['total_price'];
        $total_paid=$POST['total_paid'];
        $refund_amount=(float)$POST['refund_amount'];
        $payment_method_id=(int)$POST['refund_method_id'];
        $booking = getBookingById($booking_id);
        $guest=getGuestByID($booking['guest_id']);

        if($refund_amount>0&&$refund_amount<=$total_paid){
            if($payment_method_id==5){
                $new_balance=(float)$guest['balance']+$refund_amount;
                updateGuestBalance($guest['id'],$new_balance);
                addBookingTransaction('local',$booking['guest_id'], $booking_id, $_SESSION['pcms_user_id'], -$refund_amount, $payment_method_id, 'refund_to_balance');
            }else{
                //
                addBookingTransaction('global',$booking['guest_id'], $booking_id, $_SESSION['pcms_user_id'], -$refund_amount, $payment_method_id, 'refund');
            }
            if(($total_paid-$refund_amount)>0){
                addHotelBalance($guest['id'],$booking['id'],$_SESSION['pcms_user_id'],($total_paid-$refund_amount),'Income from canceled booking');
            }
        }else{

        }
        deleteBooking((int)$_GET['booking_id']);
        header("Location: index.php?m=booking_management&tab=booking_list");
    }else{
       die("You do not have permission");
    }
}elseif ($POST['action'] == 'change_room') {
    $booking_id=(int)$_GET['booking_id'];
    $room_id=(int)$_POST['room_id'];
    $query = "UPDATE {$_CONF['db']['prefix']}_booking SET
                      room_id = '{$room_id}',
					  updated_at=NOW()
					  WHERE id={$booking_id}";
    $result  = $CONN->_query($query) or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());


} elseif ($POST['action'] == 'change_checkin_checkout') {
    changeCheckinCheckout((int)$_GET['booking_id'],$_POST['new_checkin'],$_POST['new_checkout'])?p("TRUE"):p("FALSE");
}

if ($_GET['action'] == "view") {
    if (isset($_GET['booking_id'])) {
        $booking_id = (int)$_GET['booking_id'];
        $booking =getBookingById($booking_id );
        if (!empty($booking)) {
            $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily WHERE booking_id={$booking['id']} ORDER BY date ASC ";
            $booking_days = $CONN->GetAll($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            foreach ($booking_days AS $booking_day) {
                $booking_days_ids[] = $booking_day['id'];
            }
            $tmp_arr = implode(", ", $booking_days_ids);
            $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_booking_daily_services
			  	WHERE booking_daily_id IN ({$tmp_arr})";

            $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $booking_daily_services = $result->GetRows();


            $guest=getGuestByID($booking['guest_id']);
            if($booking['responsive_guest_id']==0){
                $responsive_guest=$guest;
            }else{
                $responsive_guest=getGuestByID($booking['responsive_guest_id']);
            }

            $guestTransactions=getGuestTransactions($booking['guest_id']);
            foreach($guestTransactions AS $guestTransaction){
                if($guestTransaction['destination']=='rp_registration' && $guestTransaction['result']=='OK'){
                    $guestRegularPayments[$guestTransaction['transaction_id']]=$guestTransaction;
                }
            }
            $TMPL->addVar("TMPL_errors", $errors);
            $TMPL->addVar("TMPL_guestRegularPayments", $guestRegularPayments);
            $TMPL->addVar("TMPL_booking_transactions", getBookingTransactions($booking_id));
            $TMPL->addVar("TMPL_payment_methods", getAllPaymentMethods());
            $TMPL->addVar("TMPL_booking_days", $booking_days);
            $TMPL->addVar("TMPL_services_types", getServicesTypes());
            $TMPL->addVar("TMPL_all_services", getAllServices());
            $TMPL->addVar("TMPL_booking_daily_services", $booking_daily_services);
            $TMPL->addVar("TMPL_booking", $booking);
            $TMPL->addVar('TMPL_all_statuses', getAllStatuses());
            $TMPL->addVar("TMPL_guest", $guest);
            $TMPL->addVar("TMPL_room", getRoomByID($booking['room_id']));
            $TMPL->addVar("TMPL_responsive_guest", $responsive_guest);
            $TMPL->ParseIntoVar($_CENTER, "booking_view");
        } else {
            p("Booking with this id not exists!");
        }

    }
}elseif ($_GET['action'] == "send_invoice") {
    $feedback_attachment='plugins/booking_management/last_invoice/Invoice.pdf';
    $to=$_POST['email1'];
    $to.=(isset($_POST['email2'])&&!empty($_POST['email2']))?", ".$_POST['email2']:"";
    $result=Send_Email_u($to, 'Booking Invoice',"message",'text/plain','utf-8', htmlspecialchars_decode($settings['ltd']['value']), $settings['e_mail']['value'],$feedback_attachment,true);
    if($result) {
        die("The mail has been sent successfully");
    }else{
        die("Unable to Send Email");
    }
} elseif ($_GET['action'] == "get_invoice") {

    if(isset($_GET['lang'])&&$_GET['lang']!=''){
        require_once($ROOT."/lang/".$_GET['lang'].".php");
    }
    if(isset($_POST['lang'])&&$_POST['lang']!=''){
        require_once($ROOT."/lang/".$_POST['lang'].".php");
        $stringJSON = get_magic_quotes_gpc()?stripslashes($_POST['json']) : $_POST['json'];
        $_POST=json_decode($stringJSON,true);

    }
    include_once("classes/mpdf/mpdf.php");
    $mpdf = new mPDF('', 'A4', '8', '', 15, 15, 20, 20, 5, 5);
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->useSubstitutions = true;
    $mpdf->SetHTMLFooter("<div style='text-align:center; padding-bottom: 4px; background-color: #333333; color:white; width:100%;'>" . $TEXT['invoice']['rule_1'] . "</div><div style='text-align:center; padding-bottom: 4px; background-color: #333333; color:white; width:100%;'>" . $settings['ltd']['value'] . " " . $settings['address']['value'] . " " . $settings['tel']['value'] . " " . $settings['e_mail']['value'] . "</div>");

    if ($_GET['invoice_type'] == 'accommodation' || $_GET['invoice_type'] == 'services' || $_GET['invoice_type'] == 'full') {
        if (isset($_GET['booking_id'])) {
            $booking_id = (int)$_GET['booking_id'];
            $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking WHERE id={$booking_id}";
            $booking = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
            $roomTypesByID=getRoomTypeByID($booking['room_id']);
            if (!empty($booking)) {
                $invoice_identifier="|";
                if($_GET['invoice_type'] == 'accommodation'){
                    $invoice_identifier.=$booking_id.":a|";
                }elseif($_GET['invoice_type'] == 'services'){
                    $invoice_identifier.=$booking_id.":s|";
                }else{
                    $invoice_identifier.=$booking_id.":a|".$booking_id.":s|";
                }
                $invoice_number=addInvoice($invoice_identifier);
                $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily WHERE booking_id={$booking['id']} ORDER BY date ASC ";
                $booking_days = $CONN->GetAll($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
                foreach ($booking_days AS $booking_day) {
                    $booking_days_ids[] = $booking_day['id'];
                }
                $tmp_arr = implode(", ", $booking_days_ids);
                $query = "SELECT *
				FROM {$_CONF['db']['prefix']}_booking_daily_services
			  	WHERE booking_daily_id IN ({$tmp_arr})";

                $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
                $booking_daily_services = $result->GetRows();

                $TMPL->addVar("TMPL_booking_transactions", getBookingTransactions($booking_id));
                $TMPL->addVar("TMPL_room_type", $roomTypesByID);
                $TMPL->addVar("TMPL_payment_methods", getAllPaymentMethods());
                $TMPL->addVar("TMPL_booking_days", $booking_days);
                $TMPL->addVar("TMPL_all_extra_services", getAllServices());
                $TMPL->addVar("TMPL_booking_daily_services", $booking_daily_services);
                $TMPL->addVar("TMPL_booking", $booking);
                $TMPL->addVar('TMPL_all_statuses', getAllStatuses());
                $TMPL->addVar("TMPL_settings", $settings);

                if ($_GET['invoice_type'] == 'accommodation') {
                    $guest= getGuestByID($booking['guest_id']);
                    $template="accommodation_invoice";
                    $invoices[$booking['id']]['a']="on";
                } elseif ($_GET['invoice_type'] == 'services') {
                    if((int)$booking['responsive_guest_id']==0){
                        $guest= getGuestByID($booking['guest_id']);
                    }else{
                        $guest= getGuestByID($booking['responsive_guest_id']);
                    }
                    $template="services_invoice";
                    $invoices[$booking['id']]['s']="on";
                } elseif ($_GET['invoice_type'] == 'full') {
                    $guest= getGuestByID($booking['guest_id']);
                    $template="full_invoice";
                    $invoices[$booking['id']]['a']="on";
                    $invoices[$booking['id']]['s']="on";
                }
                $TMPL->addVar("TMPL_invoice_number", $invoice_number);
                $TMPL->addVar("TMPL_guest", $guest);
                $html = $TMPL->ParseIntoString($template);
                if($_GET['download_word']==1){
                    //p($invoices);exit;
                    $bookings[]=$booking;
                    require_once $ROOT.'/files/PhpWord.inc.php';
                    require_once $ROOT.'/files/GetWordDoc.php';
                    header_remove ();
                    header("Content-Disposition: attachment; filename=".$invoice_number.".docx");
                    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                    readfile($ROOT.'/last_invoice/'.$invoice_number.'.docx'); // or echo file_get_contents($temp_file);
                    unlink($ROOT.'/last_invoice/'.$invoice_number.'.docx');
                    exit;
                }

                $mpdf->WriteHTML($html);
                $mpdf->Output($ROOT.'/last_invoice/Invoice.pdf','F');
                p($html);
                $TMPL->addVar("TMPL_email", $guest['email']);
                $html = $TMPL->ParseIntoString("invoice_sender");
                p($html);
                exit;
            } else {
                die("Booking with this id not exists!");
            }

        }else{
            die("Booking ID not set");
        }
    }else if ($_GET['invoice_type'] == 'multiple') {
        $invoices=$_POST['invoices'];
        if(count($invoices)==0){
            die("0 Invoices Selected");
        }
        $where_clause=" AND B.id in(";
        foreach($invoices AS $k=>$invoice){
            $where_clause.=$k.',';
        }
        $where_clause.="0) ORDER BY B.id ASC";

        $bookings=getJoinedBookings($where_clause);
        $roomTypesByID=getRoomTypeByID();
        $guest_id_checker=$bookings[0]['guest_id'];
        $invoice_identifier="|";

        foreach($bookings AS $booking){
            if($guest_id_checker!=$booking['guest_id']){
                die("Multiple Guests Invoices Selected");
            }
            if($invoices[$booking['id']]['a']=='on'){
                $invoice_identifier.=$booking['id'].":a|";
            }
            if($invoices[$booking['id']]['s']=='on'){
                $invoice_identifier.=$booking['id'].":s|";
            }
        }
        $invoice_number=addInvoice($invoice_identifier);

        $guest=getGuestByID($bookings[0]['guest_id']);
        $TMPL->addVar("TMPL_invoice_number", $invoice_number);
        $TMPL->addVar("TMPL_guest", $guest);
        $TMPL->addVar("TMPL_invoices", $invoices);
        $TMPL->addVar("TMPL_bookings", $bookings);
        $TMPL->addVar("TMPL_room_type", $roomTypesByID);

        if($_GET['download_word']==1){
            require_once $ROOT.'/files/PhpWord.inc.php';
            require_once $ROOT.'/files/GetWordDoc.php';
            header_remove ();
            header("Content-Disposition: attachment; filename=".$invoice_number.".docx");
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            readfile($ROOT.'/last_invoice/'.$invoice_number.'.docx'); // or echo file_get_contents($temp_file);
            unlink($ROOT.'/last_invoice/'.$invoice_number.'.docx');
            exit;
        }
        $html = $TMPL->ParseIntoString("multiple_invoice");
        $mpdf->WriteHTML($html);
        $file=$ROOT.'/last_invoice/Invoice.pdf';

        $mpdf->Output($file,'F');
        p($html);

        $TMPL->addVar("TMPL_email", $guest['email']);
        $html = $TMPL->ParseIntoString("invoice_sender");

        p($html);
        exit;

    } else {
        die("Invalid invoice_type");
    }

}elseif ($_GET['action'] == "delete_daily_service") {

    if(isset($_GET['booking_id'])&&isset($_GET['daily_service_id'])){
        $booking_id=(int)$_GET['booking_id'];
        $daily_service_id=(int)$_GET['daily_service_id'];
        deleteDailyService($daily_service_id);
        updateBookingFinances($booking_id);
        header("Location: index.php?m=booking_management&tab=booking_list&action=view&booking_id=".$booking_id);
    }

} else {

    $query = "SELECT id,first_name,last_name,type,id_number FROM {$_CONF['db']['prefix']}_guests";
    $guests_result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    foreach ($guests_result AS $guest) {
        $guests[$guest['id']] = $guest;
    }

    $pageBar="";
    $bookings = getJoinedBookings($where_clause,(int)$_GET['total_unpaid']);

    foreach ($bookings as $key => $value) {
      $date2=new DateTime($value['check_out']);
      $date1=new DateTime($value['check_in']);
      $bookings[$key]['days_in']=$date2->diff($date1)->format("%a");
    }
    $food=getAllServices(6);
    $statuses=getAllStatuses();
    $rooms=GetAllRooms();
    $room_types = GetRoomTypes();
    $TMPL->addVar("TMPL_navbar", $pageBar);
    $TMPL->addVar("TMPL_invoice_details", $invoice_details);
    $TMPL->addVar("TMPL_plugin", $LOADED_PLUGIN['plugin']);
    $TMPL->addVar("TMPL_settings", $SETTINGS);
    $TMPL->addVar("TMPL_booking", $bookings);
    $TMPL->addVar("TMPL_guests", $guests);
    $TMPL->addVar("TMPL_food", $food);
    $TMPL->addVar("TMPL_room_types", $room_types);
    $TMPL->addVar("TMPL_rooms", $rooms);
    $TMPL->addVar("TMPL_booking_statuses", $statuses);
    $TMPL->ParseIntoVar($_CENTER, "booking_list");
}


function addNewDailyService($booking_daily_id, $service_id, $service_type_id, $service_title, $service_price, $comment)
{
    global $CONN, $FUNC, $_CONF;
    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_daily WHERE id=" . $booking_daily_id;
    $day = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    if($day['date']>=date('Y-m-d')){
        $query = "INSERT INTO {$_CONF['db']['prefix']}_booking_daily_services set
			      booking_daily_id = {$booking_daily_id},
				  service_id = {$service_id},
				  service_type_id = {$service_type_id},
				  service_title='{$service_title}',
				  service_price={$service_price},
				  comment='{$comment}'";
        $result = $CONN->_query($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $return_id = (int)$CONN->Insert_ID();
        updateBookingFinances((int)$_GET['booking_id']);
        return $return_id;
    }else{
        return false;
    }

}
