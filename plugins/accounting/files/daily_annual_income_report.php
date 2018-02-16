<?

$POST = $VALIDATOR->ConvertSpecialChars($_POST);
$selected_year=(isset($_GET['year']))?$_GET['year']:date('Y');
$selected_month=(isset($_GET['month']))?$_GET['month']:date('m');


$reports=getDailyAnnualIncomeReport($selected_year, $selected_month);
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
    $sheet->setCellValue('A4', $TEXT['tab']['reports']['sub'][$_GET['tab']]);

    $sheet->setCellValue("A5", $TEXT['annual_income_report']['day']);
    $sheet->getStyle('A5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('A')->setAutoSize(true);

    $sheet->setCellValue("B5", $TEXT['annual_income_report']['accommodation_in']);
    $sheet->getStyle('B5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('B5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('B5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('B')->setAutoSize(true);

    $sheet->setCellValue("C5", $TEXT['annual_income_report']['services_in']);
    $sheet->getStyle('C5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('C5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('C5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('C')->setAutoSize(true);

    $sheet->setCellValue("D5", $TEXT['annual_income_report']['out']);
    $sheet->getStyle('D5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('D5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('D5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('D')->setAutoSize(true);

    $sheet->setCellValue("E5", $TEXT['annual_income_report']['balance']);
    $sheet->getStyle('E5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('E5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('E5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('E')->setAutoSize(true);

    $sheet->getStyle('A5:E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getColumnDimension('A:E')->setAutoSize(true);
    $counter=4;
    foreach ($reports[$selected_year.'-'.$selected_month] AS $m=>$report) {
        $sheet->setCellValueByColumnAndRow(0, $counter +2, date('d-m-Y',strtotime($report['day'])));
        $sheet->setCellValueByColumnAndRow(1, $counter +2, $report['accommodation_in']);
        $sheet->setCellValueByColumnAndRow(2, $counter +2, $report['services_in']);
        $sheet->setCellValueByColumnAndRow(3, $counter +2, $report['out']);
        $sheet->setCellValueByColumnAndRow(4, $counter +2, $report['balance']);
        $counter++;
    }
    header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
    header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
    header ( "Cache-Control: no-cache, must-revalidate" );
    header ( "Pragma: no-cache" );
    header ( "Content-type: application/vnd.ms-excel" );
    header ( "Content-Disposition: attachment; filename=annual_income_report_".$selected_year."_".$selected_month.".xls" );

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');
    exit;
}

$TMPL->addVar('TMPL_year', $selected_year);
$TMPL->addVar('TMPL_month', $selected_month);
$TMPL->addVar('TMPL_reports', $reports);
$TMPL->ParseIntoVar($_CENTER, "daily_annual_income_report");
