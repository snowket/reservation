<?
$selected_year=(isset($_GET['year']))?$_GET['year']:date('Y');
$selected_month=(isset($_GET['month']))?$_GET['month']:date('m');
$POST = $VALIDATOR->ConvertSpecialChars($_POST);
require_once(HMS_ROOT."/models/Statistic.model.php");
$statisticModel=new Statistic();

$date = new DateTime($selected_year.'-'.$selected_month.'-01');
$date->modify('last day of this month');


$usedRoomsCount=$statisticModel->getUsedRoomsCount($selected_year.'-'.$selected_month.'-01',$date->format('Y-m-d'));
$current_year=$selected_year;
$last_year=$current_year-1;
#dd($usedRoomsCount);
$datex = new DateTime($last_year.'-'.$selected_month.'-01');
$datex->modify('last day of this month');


$usedRoomsCount_last_year=$statisticModel->getUsedRoomsCount($last_year.'-'.$selected_month.'-01',$datex->format('Y-m-d'));
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


    $sheet->setCellValue("A6", $TEXT['rooms_usage_report']['date']);
    $sheet->getStyle('A6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A6')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A6')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('A')->setAutoSize(true);

    $sheet->setCellValue("B6",  $TEXT['rooms_usage_report']['used_rooms_count']);
    $sheet->getStyle('B6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('B6')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('B6')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('B')->setAutoSize(true);

    $sheet->setCellValue("C6", $TEXT['rooms_usage_report']['total_rooms_count'] );
    $sheet->getStyle('C6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('C6')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('C6')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('C')->setAutoSize(true);
     $sheet->setCellValue("D6", $TEXT['rooms_usage_report']['percent'] );
    $sheet->getStyle('D6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('D6')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('D6')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('D')->setAutoSize(true);

    $sheet->getStyle('A6:D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getColumnDimension('A:D')->setAutoSize(true);
    $counter=2;


    $total_used_rooms_count=0;
    $total_rooms_count=0;
    #dd($usedRoomsCount);
    foreach ($usedRoomsCount AS $m=>$report) {
    	$total_used_rooms_count+=$report['used_rooms_count'];
        $total_rooms_count+=$report['total_rooms_count'];
        $sheet->setCellValueByColumnAndRow(0, $counter +5, $m);
        $sheet->setCellValueByColumnAndRow(1, $counter +5, $report['used_rooms_count']);
        $sheet->setCellValueByColumnAndRow(2, $counter +5, $report['total_rooms_count']);
        $sheet->setCellValueByColumnAndRow(3, $counter +5, $report['percent']."%");
        $counter++;
    }
     $sheet->setCellValueByColumnAndRow(3, $counter +5, number_format(($total_used_rooms_count/($total_rooms_count/100)),2)."%");
    header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
    header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
    header ( "Cache-Control: no-cache, must-revalidate" );
    header ( "Pragma: no-cache" );
    header ( "Content-type: application/vnd.ms-excel" );
    header ( "Content-Disposition: attachment; filename=".$selected_year."_".$selected_month." day_usage_report.xls" );

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');
    exit;
}

$TMPL->addVar('TMPL_year', $selected_year);
$TMPL->addVar('TMPL_month', $selected_month);
$TMPL->addVar("current_year", $current_year);
$TMPL->addVar("last_year", $last_year);
$TMPL->addVar("usedRoomsCount", $usedRoomsCount);
$TMPL->addVar("usedRoomsCount_last_year", $usedRoomsCount_last_year);
$TMPL->ParseIntoVar($_CENTER, "rooms_usage");
