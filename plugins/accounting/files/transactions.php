<?

$POST = $VALIDATOR->ConvertSpecialChars($_POST);
$where_clause = " AND T.type='global'";
(!empty($_GET['start_date']) && isset($_GET['start_date'])) ? $where_clause .= " AND end_date>='" . $_GET['start_date'] . " 00:00:00'" : $where_clause .= " AND end_date>='" . date('Y-m-01') . " 00:00:00'";
(!empty($_GET['end_date']) && isset($_GET['end_date'])) ? $where_clause .= " AND end_date<='" . $_GET['end_date'] . " 23:59:59'" : $where_clause .= " AND end_date<='" . date('Y-m-d') . "23:59:59'";

(isset($_GET['guest_id']) && $_GET['guest_id'] != '') ? $where_clause .= " AND G.id_number LIKE '%" . $_GET['guest_id'] . "%'" : $where_clause .= '';
(isset($_GET['guest_name']) && $_GET['guest_name'] != '') ? $where_clause .= " AND CONCAT(G.first_name, ' ', G.last_name ) LIKE '%" . $_GET['guest_name'] . "%'" : $where_clause .= '';
(isset($_GET['guest_type']) && $_GET['guest_type'] != '') ? $where_clause .= " AND G.type='" . $_GET['guest_type'] . "'" : $where_clause .= "";
(isset($_GET['active']) && $_GET['active']==3) ? $where_clause .= " AND B.active='0'" : $where_clause .= "" ;
((float)$_GET['amount_from'] != 0) ? $where_clause .= " AND amount>=" . (float)$_GET['amount_from'] : $where_clause .= '';
((float)$_GET['amount_to'] != 0) ? $where_clause .= " AND amount<=" . (float)$_GET['amount_to'] : $where_clause .= '';
$guest_tax = (int)$_GET['tax'];
(isset($_GET['tax']) && $guest_tax != 2) ? $where_clause .= " AND guest_tax=" . $guest_tax : $where_clause .= '';
$payment_method_id = (INT)$_GET['payment_method_id'];
($payment_method_id != 0) ? $where_clause .= " AND payment_method_id=" . $payment_method_id : $where_clause .= '';
(isset($_GET['destination']) && $_GET['destination'] != '') ? $where_clause .= " AND destination='" . $_GET['destination'] . "'" : $where_clause .= "";
($_GET['tr_type'] == 'debit') ? $where_clause .= " AND amount>=0" : $where_clause .= '';
($_GET['tr_type'] == 'credit') ? $where_clause .= " AND amount<0" : $where_clause .= '';
$joinedTransactions=getJoinedAllBookingTransactions($where_clause);

$paymentMethods=getAllPaymentMethods();
$administrators=getAllUsers(array('id', 'login', 'firstname', 'lastname'));
foreach ($joinedTransactions as $key => $value) {
  $joinedTransactions[$key]['bb']=getBookingById($value['booking_id']);
}
if ($_POST['action'] == 'get_excel') {
    require_once('classes/PHPExcel/PHPExcel.php');
    require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Transactions');

    $sheet->setCellValue("A1", $TEXT['transactions']['guest']);
    $sheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('A')->setAutoSize(true);

    $sheet->setCellValue("B1", $TEXT['transactions']['administrator']);
    $sheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('B1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('B1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('B')->setAutoSize(true);

    $sheet->setCellValue("C1", $TEXT['transactions']['date']);
    $sheet->getStyle('C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('C1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('C1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('C')->setAutoSize(true);

    $sheet->setCellValue("D1",$TEXT['booking_list']['check_in']);
    $sheet->getStyle('D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('D1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('D1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('D')->setAutoSize(true);

    $sheet->setCellValue("E1", $TEXT['booking_list']['check_out']);
    $sheet->getStyle('E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('E1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('E1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('E')->setAutoSize(true);

    $sheet->setCellValue("F1", $TEXT['transactions']['room']);
    $sheet->getStyle('F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('F1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('F1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('F')->setAutoSize(true);

    $sheet->setCellValue("G1", $TEXT['transactions']['method']);
    $sheet->getStyle('G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('G1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('G1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('G')->setAutoSize(true);

    $sheet->setCellValue("H1", $TEXT['transactions']['tax']);
    $sheet->getStyle('H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('H1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('H1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('H')->setAutoSize(true);

    $sheet->setCellValue("I1", $TEXT['transactions']['debit']);
    $sheet->getStyle('I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('I1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('I1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('I')->setAutoSize(true);

    $sheet->setCellValue("J1", $TEXT['transactions']['credit']);
    $sheet->getStyle('J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('J1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('J1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('J')->setAutoSize(true);

    $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getColumnDimension('A:J')->setAutoSize(true);

    for ($i = 0; $i < count($joinedTransactions); $i++) {
        $firstname = $joinedTransactions[$i]['first_name'];
        $lastname = $joinedTransactions[$i]['last_name'];
        $guest=($lastname=="")?$firstname:$firstname." ".$lastname;
        $administrator=$administrators[$joinedTransactions[$i]['administrator_id']]['firstname']." ".$administrators[$joinedTransactions[$i]['administrator_id']]['lastname'];
        if($administrator==''){
            $administrator="SYSTEM";
        }
        $date = date('Y-m-d',strtotime($joinedTransactions[$i]['end_date']));
        $payment_method = $paymentMethods[$joinedTransactions[$i]['payment_method_id']]['title'];
        $tax = ($joinedTransactions[$i]['guest_tax'] == 0) ? 'TAX FREE' : 'TAX INCLUDED';
        $amount=$joinedTransactions[$i]['amount'];
        $debit = ($amount>=0)?$amount:0;
        $credit = ($amount<0)?$amount:0;
        $R_name = $joinedTransactions[$i]['R_name'];
        $floor = $joinedTransactions[$i]['floor'];
        $sheet->setCellValueByColumnAndRow(0, $i +2, $guest);
        $sheet->setCellValueByColumnAndRow(1, $i +2, $administrator);
        $sheet->setCellValueByColumnAndRow(2, $i +2, $date);
        $sheet->setCellValueByColumnAndRow(3, $i +2, date('Y-m-d', strtotime($joinedTransactions[$i]['bb']['check_in'])) );
        $sheet->setCellValueByColumnAndRow(4, $i +2, date('Y-m-d', strtotime($joinedTransactions[$i]['bb']['check_out'])) );
        $sheet->setCellValueByColumnAndRow(5, $i +2, $R_name." Floor(".$floor.")");
        $sheet->setCellValueByColumnAndRow(6, $i +2, $payment_method);
        $sheet->setCellValueByColumnAndRow(7, $i +2, $tax);
        $sheet->setCellValueByColumnAndRow(8, $i +2, $debit);
        $sheet->setCellValueByColumnAndRow(9, $i +2, $credit);
    }
    header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
    header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
    header ( "Cache-Control: no-cache, must-revalidate" );
    header ( "Pragma: no-cache" );
    header ( "Content-type: application/vnd.ms-excel" );
    header ( "Content-Disposition: attachment; filename=transactions.xls" );

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');

}

$TMPL->addVar('TMPL_guests', getAllGuests());
$TMPL->addVar("TMPL_payment_methods", $paymentMethods);
$TMPL->addVar("TMPL_administrators", $administrators);
$TMPL->addVar('TMPL_transactions', $joinedTransactions);
// $TMPL->addVar("TMPL_navbar", $FUNC->DrawPageBar($SELF."&p=",$result));
$TMPL->ParseIntoVar($_CENTER, "transactions");
