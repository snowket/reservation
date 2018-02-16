<?

$POST = $VALIDATOR->ConvertSpecialChars($_POST);
$where_clause = "";
(isset($_GET['guest_id_number']) && $_GET['guest_id_number'] != '') ? $where_clause .= " AND G.id_number LIKE '%" . $_GET['guest_id_number'] . "%'" : $where_clause .= '';
(isset($_GET['guest_name']) && $_GET['guest_name'] != '') ? $where_clause .= " AND CONCAT(G.first_name, ' ', G.last_name ) LIKE '%" . $_GET['guest_name'] . "%'" : $where_clause .= '';
(isset($_GET['guest_type']) && $_GET['guest_type'] != '') ? $where_clause .= " AND G.type='" . $_GET['guest_type'] . "'" : $where_clause .= "";

(isset($_GET['guest_balance']) && (int)$_GET['guest_balance'] != 0) ? $where_clause .= " AND G.balance>0" : $where_clause .= "";
$guest_tax = (int)$_GET['tax'];
(isset($_GET['tax']) && $guest_tax != 2) ? $where_clause .= " AND G.tax=" . $guest_tax : $where_clause .= '';

/*
$query = "SELECT G.id, G.id_number AS guest_id_number,G.type,G.tax, G.first_name, G.last_name,G.balance, B.guest_id AS guest_id,B.paid_amount as debit,B.accommodation_price as credit
          FROM cms_guests AS G
            LEFT JOIN cms_booking AS B
              ON G.id=B.guest_id
          WHERE 1=1 AND CONCAT(G.first_name, ' ', G.last_name ) LIKE '%შუქურა %'";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, mysql_error());
$results = $result->getRows();
p($results);
$query = "
          SELECT G.id, G.id_number AS guest_id_number,G.type,G.tax, G.first_name, G.last_name,G.balance, B.responsive_guest_id AS guest_id,B.services_paid_amount as debit,B.services_price as credit
          FROM cms_guests AS G
            LEFT JOIN cms_booking AS B
              ON G.id=B.responsive_guest_id
          WHERE 1=1 AND CONCAT(G.first_name, ' ', G.last_name ) LIKE '%შუქურა %'";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, mysql_error());
$results = $result->getRows();
p($results);
$query = "
          SELECT G.id, G.id_number AS guest_id_number,G.type,G.tax, G.first_name, G.last_name,G.balance, G.id AS guest_id,0 as debit,0 as credit
          FROM cms_guests AS G
          WHERE 1=1 AND CONCAT(G.first_name, ' ', G.last_name ) LIKE '%შუქურა %'";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, mysql_error());
$results = $result->getRows();
p($results);
*/
$guestsBookingsSummaryState = getGuestsBookingsSummaryState($where_clause,(int)$_GET['total_unpaid']);

$paymentMethods = getAllPaymentMethods();
$guests=getAllGuests();
if ($_POST['action'] == 'fill_guest_balance') {
    $guest_id=(int)$_POST['guest_id'];
    $amount=(float)$_POST['amount'];
    if($guest_id!=0&&$amount>0){
        $guest=getGuestByID($guest_id);
        $new_balance=(float)$guest['balance']+(float)$amount;
        updateGuestBalance($guest_id,$new_balance);
        $FUNC->Redirect($SELF);
    }
}

if ($_POST['action'] == 'get_excel') {

    require_once('classes/PHPExcel/PHPExcel.php');
    require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
    $xls = new PHPExcel();

    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Transactions');

    $sheet->setCellValue("A1", $TEXT['guests_financial_state']['guest']);
    $sheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('A')->setAutoSize(true);

    $sheet->setCellValue("B1", $TEXT['guests_financial_state']['id_number']);
    $sheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('B1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('B1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getStyle('B')->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $sheet->getColumnDimension('B')->setAutoSize(true);

    $sheet->setCellValue("C1", $TEXT['guests_financial_state']['type']);
    $sheet->getStyle('C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('C1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('C1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('C')->setAutoSize(true);

    $sheet->setCellValue("D1", $TEXT['guests_financial_state']['tax']);
    $sheet->getStyle('D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('D1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('D1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('D')->setAutoSize(true);

    $sheet->setCellValue("E1", $TEXT['guests_financial_state']['total_paid']);
    $sheet->getStyle('E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('E1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('E1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('E')->setAutoSize(true);

    $sheet->setCellValue("F1", $TEXT['guests_financial_state']['total_debts']);
    $sheet->getStyle('F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('F1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('F1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('F')->setAutoSize(true);

    $sheet->setCellValue("G1", $TEXT['guests_financial_state']['total_unpaid']);
    $sheet->getStyle('G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('G1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('G1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('G')->setAutoSize(true);

    $sheet->setCellValue("H1", $TEXT['guests_financial_state']['balance']);
    $sheet->getStyle('H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('H1')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('H1')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('H')->setAutoSize(true);

    $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getColumnDimension('A:H')->setAutoSize(true);
$i=0;
    foreach ($guestsBookingsSummaryState AS $v) {
        $firstname = $v['first_name'];
        $lastname = $v['last_name'];
        $guest = ($lastname == "") ? $firstname : $firstname . " " . $lastname;
        $id_number = "".$v['guest_id_number'];
        $type = $v['type'];
        $tax = ($v['tax'] == 0) ? 'TAX FREE' : 'TAX INCLUDED';
        $debit = $v['debit'];
        $credit = $v['credit'];

        $sheet->setCellValueByColumnAndRow(0, $i + 2, $guest);
        //$sheet->setCellValueByColumnAndRow(1, $i + 2, $id_number);
        $sheet->setCellValueExplicit('B'.($i + 2), $id_number, PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->setCellValueByColumnAndRow(2, $i + 2, $type);
        $sheet->setCellValueByColumnAndRow(3, $i + 2, $tax);
        $sheet->setCellValueByColumnAndRow(4, $i + 2, $debit);
        $sheet->setCellValueByColumnAndRow(5, $i + 2, $credit);
        $sheet->setCellValueByColumnAndRow(6, $i + 2, $credit-$debit);
        $sheet->setCellValueByColumnAndRow(7, $i + 2, $v['balance']);
        $i++;
    }
    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=guests_financial_state.xls");

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');


}


$TMPL->addVar('TMPL_guests', $guests);
$TMPL->addVar('TMPL_guestsBookingsSummaryState', $guestsBookingsSummaryState);
$TMPL->addVar("TMPL_navbar", $pageBar);
$TMPL->addVar("TMPL_settings",array('page_num'=>50));
$TMPL->ParseIntoVar($_CENTER, "guests_financial_state");


