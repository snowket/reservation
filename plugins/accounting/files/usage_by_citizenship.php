<?
$POST = $VALIDATOR->ConvertSpecialChars($_POST);
$where_clause = "";

$start_date = (isset($_GET['start_date']) && $_GET['start_date'] != '') ? $_GET['start_date'] : date('Y-m-01');
$end_date = (isset($_GET['end_date']) && $_GET['end_date'] != '') ? $_GET['end_date'] : date('Y-m-d');


$reports = getUsageByCitizenship($start_date, $end_date);
/*
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
*/
$counterit=0;
$q="SELECT DISTINCT(booking_id) FROM cms_booking_daily BD
WHERE BD.date>='".$start_date."' AND BD.date<='".$end_date."' AND BD.type<>'check_out' AND BD.active=1";
$q	= $CONN->Execute($q)or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
$q	= $q->getRows();
$it =  new RecursiveIteratorIterator(new RecursiveArrayIterator($q));
$l = iterator_to_array($it, false);
foreach($l as $i){
    $q="SELECT adult_num,child_num FROM cms_booking
    WHERE active=1 AND id=".$i;
    $q	= $CONN->Execute($q)or $FUNC->ServerError(__FILE__,__LINE__,$CONN->ErrorMsg());
    $q	= $q->fields;
    $counterit+=$q['adult_num']+$q['child_num'];
}

$hotelSettings = GetHotelSettings();

if ($_POST['action'] == 'get_excel') {

    require_once('classes/PHPExcel/PHPExcel.php');
    require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
    $xls = new PHPExcel();

    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->getColumnDimension('A')->setWidth(20);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(40);
    $sheet->setTitle('Usage By Citizenship');

    $row_num = 1;
    $sheet->getRowDimension('1')->setRowHeight(40);
    $sheet->mergeCells('A' . $row_num . ':C' . $row_num);
    $sheet->getStyle('A' . $row_num)->getAlignment()->setWrapText(true);
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');

    $sheet->setCellValue("A" . $row_num, $TEXT['usage_by_citizenship']['excel']['description']);
    $row_num++;
    $sheet->mergeCells('A' . $row_num . ':C' . $row_num);
    $row_num++;
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->mergeCells('A' . $row_num . ':B' . $row_num);
    $sheet->setCellValue("A" . $row_num, $TEXT['usage_by_citizenship']['excel']['object']);
    $sheet->setCellValue("C" . $row_num, $hotelSettings['ltd']);
    $row_num++;
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->mergeCells('A' . $row_num . ':B' . $row_num);
    $sheet->setCellValue("A" . $row_num, $TEXT['usage_by_citizenship']['excel']['address']);
    $sheet->setCellValue("C" . $row_num, $hotelSettings['address']);
    $row_num++;
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->mergeCells('A' . $row_num . ':B' . $row_num);
    $sheet->setCellValue("A" . $row_num, $TEXT['usage_by_citizenship']['excel']['tel']);
    $sheet->setCellValue("C" . $row_num, $hotelSettings['tel']);
    $row_num++;
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->mergeCells('A' . $row_num . ':B' . $row_num);
    $sheet->setCellValue("A" . $row_num, $TEXT['usage_by_citizenship']['excel']['contact_person']);
    $sheet->setCellValue("C" . $row_num, '');
    $row_num++;
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->mergeCells('A' . $row_num . ':B' . $row_num);
    $sheet->setCellValue("A" . $row_num, $TEXT['usage_by_citizenship']['excel']['cell_phone']);
    $sheet->setCellValue("C" . $row_num, $hotelSettings['tel']);
    $row_num++;
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->mergeCells('A' . $row_num . ':B' . $row_num);
    $sheet->setCellValue("A" . $row_num, $TEXT['usage_by_citizenship']['excel']['mail']);
    $sheet->setCellValue("C" . $row_num, $hotelSettings['e_mail']);
    $row_num++;
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->mergeCells('A' . $row_num . ':B' . $row_num);
    $sheet->setCellValue("A" . $row_num, $TEXT['usage_by_citizenship']['excel']['period_range']);
    $sheet->setCellValue("C" . $row_num, $start_date . ' / ' . $end_date);
    $row_num++;
    $sheet->mergeCells('A' . $row_num . ':C' . $row_num);
    $row_num++;


    $total_local_guests_count = $reports['nights']['local']['guests'];
    $total_local_guests_night = $reports['nights']['local']['nights'];

    $total_forign_guests_count = $reports['nights']['global']['guests'];
    $total_forign_guests_night = $reports['nights']['global']['nights'];

    //START Report 1
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, '');
    $sheet->setCellValue("B" . $row_num, $TEXT['usage_by_citizenship']['excel']['guest']);
    $sheet->setCellValue("C" . $row_num, $TEXT['usage_by_citizenship']['excel']['guest.night']);
    $row_num++;
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, $TEXT['usage_by_citizenship']['excel']['local']);
    $sheet->setCellValue("B" . $row_num, $total_local_guests_count);
    $sheet->setCellValue("C" . $row_num, $total_local_guests_night);
    $row_num++;
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, $TEXT['usage_by_citizenship']['excel']['forign']);
    $sheet->setCellValue("B" . $row_num, $total_forign_guests_count);
    $sheet->setCellValue("C" . $row_num, $total_forign_guests_night);
    $row_num++;
    $sheet->mergeCells('A' . $row_num . ':C' . $row_num);
    $row_num++;
    //END Report 1

    //START Report 2
    $sheet->mergeCells('A' . $row_num . ':C' . $row_num);
    $sheet->getStyle('A' . $row_num . ':C' . ($row_num + 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A' . $row_num . ':C' . ($row_num + 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A' . $row_num . ':C' . ($row_num + 1))->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A' . $row_num . ':C' . ($row_num + 1))->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, $TEXT['usage_by_citizenship']['excel']['title_2']);
    $row_num++;
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->mergeCells('A' . $row_num . ':B' . $row_num);
    $sheet->setCellValue("A" . $row_num, $TEXT['usage_by_citizenship']['excel']['country']);
    $sheet->setCellValue("C" . $row_num, $TEXT['usage_by_citizenship']['excel']['guest_count']);
    $row_num++;
    foreach ($reports['guests'] AS $country => $guests_count) {
        $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
        $sheet->mergeCells('A' . $row_num . ':B' . $row_num);
        $sheet->setCellValue("A" . $row_num, $country);
        $sheet->setCellValue("C" . $row_num, $guests_count);
        $row_num++;

    }
    //END Report 2

    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=usage_by_citizenship_" . $start_date . "_" . $end_date . ".xls");

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');


}


$TMPL->addVar("TMPL_reports", $reports);
$TMPL->addVar("q", $counterit);
$TMPL->addVar("TMPL_start_date", $start_date);
$TMPL->addVar("TMPL_end_date", $end_date);
$TMPL->ParseIntoVar($_CENTER, "usage_by_citizenship");


