<?

$POST = $VALIDATOR->ConvertSpecialChars($_POST);
$selected_year=(isset($_GET['year']))?$_GET['year']:date('Y');

$reports=getAccrualBasedIncomeReport($selected_year);
if ($_POST['action'] == 'get_excel') {
    require_once('classes/PHPExcel/PHPExcel.php');
    require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Accrual Based Income');
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

    $sheet->setCellValue("A5", $TEXT['accrual_based_income']['month']);
    $sheet->getStyle('A5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('A')->setAutoSize(true);

    $sheet->setCellValue("B5", $TEXT['accrual_based_income']['acc_income_tax_included']);
    $sheet->getStyle('B5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('B5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('B5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('B')->setAutoSize(true);

    $sheet->setCellValue("C5", $TEXT['accrual_based_income']['acc_income_tax_free']);
    $sheet->getStyle('C5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('C5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('C5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('C')->setAutoSize(true);

    $sheet->setCellValue("D5", $TEXT['accrual_based_income']['services_income']);
    $sheet->getStyle('D5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('D5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('D5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('D')->setAutoSize(true);

    $sheet->setCellValue("E5", $TEXT['accrual_based_income']['income']);
    $sheet->getStyle('E5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('E5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('E5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('E')->setAutoSize(true);

    $sheet->setCellValue("F5", $TEXT['accrual_based_income']['tax']);
    $sheet->getStyle('F5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('F5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('F5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('F')->setAutoSize(true);

    $sheet->setCellValue("G5", $TEXT['accrual_based_income']['last_52_month_income']);
    $sheet->getStyle('G5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('G5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('G5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('G')->setAutoSize(true);


    $sheet->getStyle('A5:G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getColumnDimension('A:G')->setAutoSize(true);
    $counter=4;
    foreach ($reports[$selected_year] AS $m=>$report) {
        $sheet->setCellValueByColumnAndRow(0, $counter +2, $report['month']." ".$selected_year);
        $sheet->setCellValueByColumnAndRow(1, $counter +2, $report['acc_income_tax_included']);
        $sheet->setCellValueByColumnAndRow(2, $counter +2, $report['acc_income_tax_free']);
        $sheet->setCellValueByColumnAndRow(3, $counter +2, $report['services_income']);
        $sheet->setCellValueByColumnAndRow(4, $counter +2, $report['income']);
        $sheet->setCellValueByColumnAndRow(5, $counter +2, $report['tax']);
        $sheet->setCellValueByColumnAndRow(6, $counter +2, $report['last_12_month_income']);
        $sheet->getStyle('A' . ($counter + 2) . ':G' . ($counter + 2))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
        $counter++;
    }
    header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
    header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
    header ( "Cache-Control: no-cache, must-revalidate" );
    header ( "Pragma: no-cache" );
    header ( "Content-type: application/vnd.ms-excel" );
    header ( "Content-Disposition: attachment; filename=annual_income_report_".$selected_year.".xls" );

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');
    exit;
}

$TMPL->addVar('TMPL_year', $selected_year);
$TMPL->addVar('TMPL_reports', $reports);
$TMPL->ParseIntoVar($_CENTER, "accrual_based_income");
