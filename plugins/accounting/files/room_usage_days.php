<?php
$TABLE_BOOKING="cms_booking";
$error=array();
if($_GET['start_date']!=''){
    $start_date=$_GET['start_date'];
}
else{
    $start_date=date('Y-m-d');
    $error['start_date']='Not Valid Start Date';
}
if($_GET['end_date']!=''){
    $end_date=$_GET['end_date'];
}
else{
    $end_date=date('Y-m-d');
    $error['end_date']='Not Valid End Date';
}

$query="SELECT R.id,R.name,date,B.accommodation_price,R.common_id,BD.price as sub_price FROM cms_booking_daily BD
LEFT JOIN cms_booking B on BD.booking_id=B.id
LEFT JOIN cms_rooms R on R.id=B.room_id
WHERE B.active=1 AND (BD.date BETWEEN '{$start_date}' AND '{$end_date}')
AND (type='check_in' OR type='in_use')
ORDER BY BD.date ASC";
$result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
$days_bookings = $result->GetRows();
//p($days_bookings);exit;



$day=array();
foreach($days_bookings as $booking){
    $day[$booking['id']]['count']+=1;
    $day[$booking['id']]['name']=$booking['name'];
    $day[$booking['id']]['sum']+=$booking['sub_price'];
}

usort($day, function($a, $b) {
    return $a['name'] - $b['name'];
});

$dates=array();
foreach($days_bookings as $booking){
    $dates[$booking['date']]=$booking['date'];
}

if ($_POST['action'] == 'get_excel') {
    require_once('classes/PHPExcel/PHPExcel.php');
    require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Daily Annual Income Report');
    $settings = getSettings();
    $sheet->mergeCells('A1:D1');
    $sheet->getStyle('A1')->getAlignment()->setWrapText(true);
    $sheet->getStyle('A1:D1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue('A1',$settings['ltd']['value']);

    $sheet->mergeCells('A2:D2');
    $sheet->getStyle('A2')->getAlignment()->setWrapText(true);
    $sheet->getStyle('A2:D2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue('A2',$settings['address']['value']);

    $sheet->mergeCells('A3:D3');
    $sheet->getStyle('A3')->getAlignment()->setWrapText(true);
    $sheet->getStyle('A3:D3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue('A3',$settings['tel']['value']);

    $sheet->mergeCells('A4:D4');
    $sheet->getStyle('A4')->getAlignment()->setWrapText(true);
    $sheet->getStyle('A4:D4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue('A4', $TEXT['tab']['statistics']['sub'][$_GET['tab']]);


    $sheet->setCellValue("A5", $TEXT['rooms_usage_day_report']['used_rooms_count']);
    $sheet->getStyle('A5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('A')->setAutoSize(true);

    $sheet->setCellValue("B5",  $TEXT['rooms_usage_day_report']['total_rooms_count']);
    $sheet->getStyle('B5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('B5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('B5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('B')->setAutoSize(true);

    $sheet->setCellValue("C5", 'SUM' );
    $sheet->getStyle('C5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('C5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('C5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('C')->setAutoSize(true);

    $sheet->getStyle('A5:C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getColumnDimension('A:C')->setAutoSize(true);
    $counter=4;
    foreach ($day AS $m=>$report) {
        $sheet->setCellValueByColumnAndRow(0, $counter +2, $report['name']);
        $sheet->setCellValueByColumnAndRow(1, $counter +2, $report['count']);
        $sheet->setCellValueByColumnAndRow(2, $counter +2, $report['sum']);
        $counter++;
    }
    header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
    header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
    header ( "Cache-Control: no-cache, must-revalidate" );
    header ( "Pragma: no-cache" );
    header ( "Content-type: application/vnd.ms-excel" );
    header ( "Content-Disposition: attachment; filename=".$start_date."_".$end_date." day_usage_report.xls" );

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');
    exit;
}

$TMPL->addVar("days", $day);
$TMPL->addVar("dates", $dates);
$TMPL->ParseIntoVar($_CENTER, "room_usage_days");
