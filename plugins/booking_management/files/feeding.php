<?php
    $BOOKING_DAILY = $_CONF['db']['prefix'] . "_booking_daily";
    $BOOKING = $_CONF['db']['prefix'] . "_booking";
    $BOOKING_ROOM_SERVICES = $_CONF['db']['prefix'] . "_room_services";
    $BOOKING_DAILY_SERVICES = $_CONF['db']['prefix'] . "_booking_daily_services";
    if (isset($_GET['period_start']) && $_GET['period_start'] != '') {
        $period_start = "AND BD.date>='" . $_GET['period_start'] . "'";
        $check_in = $_GET['period_start'];
    } else {
        $period_start = "AND BD.date>='" . date('Y-m-d') . "'";

    }
    if (isset($_GET['period_end']) && $_GET['period_end'] != '') {
        $check_out = $_GET['period_end'];
        $period_end = "AND BD.date<='" . $_GET['period_end'] . "'";
    } else {
        $period_end = "AND BD.date<='" . date('Y-m-d') . "'";
    }
    $checks = 'SELECT * FROM cms_hotel_settings WHERE input_name="check_in" OR input_name="check_out"';
    $result = $CONN->Execute($checks);
    $result = $result->getRows();
    $check_in_filter = $result[0]['value'];
    $check_out_filter = $result[1]['value'];

    $HH = date('H:m');
    if ($check_in_filter > $HH) {
        $check_in_type = "AND BD.type<>'check_in'";
    }
    if ($check_out_filter < $HH) {
        $check_out_type = "AND BD.type<>'check_out'";
    }

    $food = "SELECT count(*) as count FROM {$BOOKING_ROOM_SERVICES} WHERE publish=1";
    $food = $CONN->Execute($food);
    $food = $food->fields;


    $daily_query = "SELECT * FROM {$BOOKING_DAILY} BD
LEFT JOIN {$BOOKING_DAILY_SERVICES} BDC on BD.id=BDC.booking_daily_id
LEFT JOIN {$BOOKING} B on BD.booking_id=B.id
LEFT JOIN {$BOOKING_ROOM_SERVICES} BRS on BRS.id=B.food_id
WHERE BD.active=1 {$period_start} {$period_end} {$check_in_type} {$check_out_type} ORDER BY BD.date ASC";


    $daily_query = $CONN->Execute($daily_query);
    $daily_query = $daily_query->GetRows();
    $items = array();

    foreach ($daily_query as $key => $booking) {

        $items[$booking['date']]['adult_num'] += $booking['adult_num'];
        $items[$booking['date']]['child_num'] += $booking['child_num'];
        $items[$booking['date']]['count'] += 1;
        $items[$booking['date']]['food'][$booking['food_id']]['count'] += $booking['adult_num'];
        $items[$booking['date']]['food'][$booking['food_id']]['count'] += $booking['child_num'];
        $items[$booking['date']]['food'][$booking['food_id']]['title'] = $FUNC->unpackData($booking['title'], LANG);
    }
    $days_count = getDiffBetweenTwoDates($check_in, $check_out);
    $TMPL->addVar('items', $items);
    $TMPL->addVar('days_count', $days_count);
    $TMPL->addVar('rooms_count', $food);
    $TMPL->ParseIntoVar($_CENTER, "feed");

if($_POST['action'] == 'get_excel') {

    $hotelSettings = getHotelSettings();

    require_once('classes/PHPExcel/PHPExcel.php');
    require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Food');
    $row_num = 1;


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
    $sheet->setCellValue("A" . $row_num, $TEXT['usage_by_citizenship']['excel']['ddate']);
    $sheet->setCellValue("C" . $row_num, date('Y-m-d'));


    $sheet->setCellValue("A8", 'თარიღი');
    $sheet->setCellValue("B8", 'Adult');
    $sheet->setCellValue("C8", 'Child');
    $sheet->setCellValue("D8", 'Rooms Count');
    $sheet->setCellValue("E8", 'Fooding');



    $sheet->getStyle('A8:E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A8:E8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->getStyle('A8:E8')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A8:E8')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A8:E8')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));

    foreach (range('A', 'Z') as $columnID) {
        $sheet->getColumnDimension($columnID)
            ->setAutoSize(true);
    }

    for ($i = 0; $i < count($items); $i++) {

        $date=array_keys($items);
        $sheet->setCellValueByColumnAndRow(0, $i + 9, $date[$i]);
        $sheet->setCellValueByColumnAndRow(1, $i + 9, $items[$date[$i]]['adult_num']);
        $sheet->setCellValueByColumnAndRow(2, $i + 9,  $items[$date[$i]]['child_num']);
        $sheet->setCellValueByColumnAndRow(3, $i + 9, $items[$date[$i]]['count']);
//        $sheet->setCellValueByColumnAndRow(4, $i + 2, $check_out);
        $k=0;
       foreach($items[$date[$i]]['food'] as $food){
           $sheet->setCellValueByColumnAndRow(4+$k, $i + 9, $food['title']." (".$food['count'].")");
           $k++;
       }
        $cell_name = $columnNames[$columnNumber] . $rowNumber;

        $sheet->getStyle('A' . ($i + 9) . ':E' . ($i + 9))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');


    }
    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=food.xls");

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');

}