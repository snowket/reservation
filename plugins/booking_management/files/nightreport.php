<?php
$hotelSettings = getHotelSettings();
$da=date('H:m');


if(isset($_GET['period_start']) && $_GET['period_start']!=''){
    $query="SELECT BD.date,BD.type,B.adult_num,B.child_num,R.name,RS.title,RS.food_count,BD.price,CONCAT(G.first_name,' ',G.last_name) as person_name FROM cms_booking_daily BD
    LEFT JOIN cms_booking B on B.id=BD.booking_id
    LEFT JOIN cms_rooms R on R.id=B.room_id
    LEFT JOIN cms_guests G on B.guest_id=G.id
    LEFT JOIN cms_room_services RS on B.food_id=RS.id
    WHERE BD.date='". $_GET['period_start']."' ORDER BY R.name ASC";
}else{
    $query="SELECT BD.type,BD.date,B.adult_num,B.child_num,R.name,RS.title,RS.food_count,BD.price,CONCAT(G.first_name,' ',G.last_name) as person_name FROM cms_booking_daily BD
    LEFT JOIN cms_booking B on B.id=BD.booking_id
    LEFT JOIN cms_guests G on B.guest_id=G.id
    LEFT JOIN cms_rooms R on R.id=B.room_id
    LEFT JOIN cms_room_services RS on B.food_id=RS.id
    WHERE BD.date='".date('Y-m-d')."' ORDER BY R.name ASC";
}
    $result = $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $items=$result->getRows();
    foreach($items as $key=>$value){
        $items[$key]['title']=$FUNC->unpackData($value['title'],LANG);
    }

    $TMPL->addVar('data', $items);
    $TMPL->ParseIntoVar($_CENTER, "nightreport");

if($_POST['action'] == 'get_excel') {

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
    $sheet->setCellValue("C" . $row_num, $_SESSION['username']);
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


    $sheet->setCellValue("A11", 'ოთახი');
    $sheet->setCellValue("B11", 'სტუმრების რაოდენობა');
    $sheet->setCellValue("C11", 'სტუმრების რაოდენობა');
    $sheet->setCellValue("D11", 'საუზმე');
    $sheet->setCellValue("E11", 'Note');
    $sheet->setCellValue("F11", 'სადილი');
    $sheet->setCellValue("G11", 'Note');
    $sheet->setCellValue("H11", 'ვახშამი');
    $sheet->setCellValue("I11", 'Note');
    $sheet->setCellValue("J11", 'კვების ტიპი');



    $sheet->getStyle('A11:J11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A11:J11')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->getStyle('A11:J11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A11:J11')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A11:J11')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));

    foreach (range('A', 'J') as $columnID) {
        $sheet->getColumnDimension($columnID)
            ->setAutoSize(true);
    }

    for ($i = 0; $i < count($items); $i++) {
        $sum=$items[$i]['child_num']+$items[$i]['adult_num'];
        $date=array_keys($items);
        $sheet->setCellValueByColumnAndRow(0, $i +12, $items[$i]['name']);
        $sheet->setCellValueByColumnAndRow(1, $i + 12, $items[$i]['person_name']);
        $sheet->setCellValueByColumnAndRow(2, $i + 12, $sum);
        $sheet->setCellValueByColumnAndRow(3, $i + 12,  $sum);
        $sheet->setCellValueByColumnAndRow(5, $i + 12, $sum);
        $sheet->setCellValueByColumnAndRow(7, $i +12, $sum);
        $sheet->setCellValueByColumnAndRow(9, $i + 12, $items[$i]['title']);

        $cell_name = $columnNames[$columnNumber] . $rowNumber;

        $sheet->getStyle('A' . ($i + 12) . ':J' . ($i + 12))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');


    }
    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=report_".date('Y-m-d HH:mm:ss').".xls");

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');

}
