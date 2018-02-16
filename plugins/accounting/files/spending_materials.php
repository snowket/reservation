<?

$POST = $VALIDATOR->ConvertSpecialChars($_POST);
$where_clause = "";


$period_start = (isset($_GET['period_start']) && $_GET['period_start'] != '') ? $_GET['period_start'] : date('Y-m-d', strtotime('-1 month'));
$period_end = (isset($_GET['period_end']) && $_GET['period_end'] != '') ? $_GET['period_end'] : date('Y-m-d', time());


$services = array_merge(GetServices(10), GetServices(4));
$data = GetMappedSpendingMaterials($period_start, $period_end);
$period_days = getPeriodArray($period_start, $period_end);


if ($_POST['action'] == 'get_excel') {
    require_once('classes/PHPExcel/PHPExcel.php');
    require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Spending Materials');

    $letters = range('A', 'Z');
    $columnNames = $letters;
    foreach ($letters as $one) {
        foreach ($letters as $two) {
            $columnNames[] = "$one$two";
        }
    }



    $column = "A1";
    $sheet->setCellValue($column, "date/item");
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getStyle($column)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');

    for ($i = 0; $i < count($period_days); $i++) {
        $column = "A" . ($i + 2);
        $sheet->setCellValue($column, $period_days[$i]);
        $sheet->getStyle($column)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle($column)->getFill()->getStartColor()->setRGB('3a82cc');
        $sheet->getStyle($column)->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
        $sheet->getStyle($column)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }
    $column = "A".(count($period_days)+2);
    $sheet->setCellValue($column, "TOTAL");
    $sheet->getStyle($column)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');


    $columnNumber = 1;
    $total_by_period = array();
    foreach ($services as $service) {
        $column = $columnNames[$columnNumber] . "1";
        $sheet->getColumnDimension($columnNames[$columnNumber])->setAutoSize(true);

        $sheet->setCellValue($column, $service['title']);
        $sheet->getStyle($column)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle($column)->getFill()->getStartColor()->setRGB('3a82cc');
        $sheet->getStyle($column)->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
        $sheet->getStyle($column)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');

        $rowNumber = 2;
        foreach ($period_days as $day) {
            $day_total = 0;
            $services_in_day = $data[$service['type_id']][$service['id']][$day];
            if (count($services_in_day) > 0) {
                foreach ($services_in_day as $by_room) {
                    $day_total += (int)count($by_room);
                }
            } else {
                $day_total = 0;
            }
            $total_by_period[$service['id']] += $day_total;
            $cell_name = $columnNames[$columnNumber] . $rowNumber;
            $sheet->setCellValue($cell_name, $day_total);
            $sheet->getStyle($cell_name)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
            $rowNumber++;
        }
        $column = $columnNames[$columnNumber] . $rowNumber;
        $sheet->setCellValue($column, $total_by_period[$service['id']]);
        $sheet->getStyle($column)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle($column)->getFill()->getStartColor()->setRGB('f9294a');
        $sheet->getStyle($column)->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
        $sheet->getStyle($column)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
        $columnNumber++;
    }

    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=spending_materials.xls");

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');
}
$TMPL->addVar("period_start", $period_start);
$TMPL->addVar("period_end", $period_end);
$TMPL->addVar("ALL_DAYS", $period_days);
$TMPL->addVar("SERVICES", $services);
$TMPL->addVar("DATA", $data);
$TMPL->ParseIntoVar($_CENTER, "spending_materials");

