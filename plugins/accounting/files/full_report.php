<?php


$hotelsettings=getHotelSettings();
$day=date('d');
$month=date('m');
$year=date('Y');
//შემოსავალი ჯავშნებიდან (ABI)
$dates=calc_in_out(date('Y-m-d'),$day,$month,$year);
$dates_back=calc_in_out(date('Y-m-d',strtotime('-1 year')),$day,$month,$year-1);
$user=getUserName();
foreach($dates as $key=> $date){
    $dates[$key]['sum']=$date['price']+$date['service'];
}
foreach($dates_back as $key=> $date){
    $dates_back[$key]['sum']=$date['price']+$date['service'];
}
//ჯავშანი შემოდის/გადის
$in_out_bokings=in_out_bokings(date('Y-m-d'),$day,$month,$year);
$in_out_bokings_back=in_out_bokings(date('Y-m-d',strtotime('-1 year')),$day,$month,$year-1);
$income=getDailyAnnualIncomeReport($year,$month);
foreach($income as $key=> $inc){
    if(is_array($inc)){
        foreach($inc as $i){
            $income[$key]['sum_acc']+=$i['accommodation_in'];
            $income[$key]['sum_serv']+=$i['services_in'];
        }
        $income['master_sum']+=$income[$key]['sum_acc'];
        $income['master_serv']+=$income[$key]['sum_serv'];
    }
}
$income_back=getDailyAnnualIncomeReport($year-1,$month);
foreach($income_back as $key=> $inc){
    if(is_array($inc)){
        foreach($inc as $i){
            $income_back[$key]['sum_acc']+=$i['accommodation_in'];
            $income_back[$key]['sum_serv']+=$i['services_in'];
        }
        $income_back['master_sum']+=$income_back[$key]['sum_acc'];
        $income_back['master_serv']+=$income_back[$key]['sum_serv'];
    }
}
// ოთახების რეპორტი ( სულ / თავისუფალი / დაკავებული %
$rooms=GetAllRooms();

$selected_year=(isset($_GET['year']))?$_GET['year']:date('Y');
$selected_month=(isset($_GET['month']))?$_GET['month']:date('m');
$POST = $VALIDATOR->ConvertSpecialChars($_POST);
require_once(HMS_ROOT."/models/Statistic.model.php");
$statisticModel=new Statistic();
$usedRoomsCount=$statisticModel->getFullUsedRoomsCount($selected_year.'-01-01',date($selected_year.'-'.$selected_month.'-t'));
foreach($usedRoomsCount as $key=>$value){
   foreach($value as $keys=>$string){
       $usedRoomsCount[$key]['sum']+=$string['used_rooms_count'];
       $usedRoomsCount[$key]['sum_all']+=$string['total_rooms_count'];
   }
    $usedRoomsCount['master_sum']+=$usedRoomsCount[$key]['sum'];
    $usedRoomsCount['master_sum_all']+=$usedRoomsCount[$key]['sum_all'];
}

$usedRooms_back=$statisticModel->getFullUsedRoomsCount(($selected_year-1).'-01-01',date(($selected_year-1).'-'.$selected_month.'-t'));
foreach($usedRooms_back as $key=>$value){
    foreach($value as $keys=>$string){
        $usedRooms_back[$key]['sum']+=$string['used_rooms_count'];
        $usedRooms_back[$key]['sum_all']+=$string['total_rooms_count'];
    }
    $usedRooms_back['master_sum']+=$usedRooms_back[$key]['sum'];
    $usedRooms_back['master_sum_all']+=$usedRooms_back[$key]['sum_all'];
}
if(isset($_POST['action']) && $_POST['action']=='get_excel'){
    require_once('classes/PHPExcel/PHPExcel.php');
    require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
    $rooms=$usedRoomsCount;
    $rooms_back=$usedRooms_back;
    $xls = new PHPExcel();
    $hotelSettings=getHotelSettings();
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
    $sheet->setCellValue("C" . $row_num, date('Y-m-d H:m:s'));
    $row_num++;
    $sheet->mergeCells('A' . $row_num . ':C' . $row_num);
    $row_num=$row_num+2;
    foreach (range('A', 'I') as $columnID) {
        $sheet->getColumnDimension($columnID)
            ->setAutoSize(true);
    }
    $sheet->getStyle('A' . $row_num . ':G' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, 'აღწერა');
    $sheet->setCellValue("B" . $row_num, 'დაკვირვების დღე '.date('d/m/Y'));
    $sheet->setCellValue("C" . $row_num, 'დაკვირვების თვე '.date('m/Y'));
    $sheet->setCellValue("D" . $row_num, 'დაკვირვების წელი '.date('Y'));
    $sheet->setCellValue("E" . $row_num, 'დაკვირვების დღე '.date('d/m/Y'));
    $sheet->setCellValue("F" . $row_num, 'დაკვირვების დღე '.date('d/m/Y'));
    $sheet->setCellValue("G" . $row_num, 'დაკვირვების დღე '.date('d/m/Y'));
    $row_num++;

    $sheet->getStyle('A' . $row_num . ':G' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, 'ჯავშნების შემოსავალი (დარიცხული)');
    $sheet->setCellValue("B" . $row_num, $dates[date('d')]['price']);
    $sheet->setCellValue("C" . $row_num, $dates[date('m')]['price']);
    $sheet->setCellValue("D" . $row_num, $dates[date('Y')]['price']);
    $sheet->setCellValue("E" . $row_num, $dates_back[date('d')]['price']);
    $sheet->setCellValue("F" . $row_num, $dates_back[date('m')]['price']);
    $sheet->setCellValue("G" . $row_num, $dates_back[date('Y',strtotime('-1 Year'))]['price']);
    $row_num++;

    $sheet->getStyle('A' . $row_num . ':G' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, 'სერვისების შემოსავალი (დარიცხული)');
    $sheet->setCellValue("B" . $row_num, $dates[date('d')]['service']);
    $sheet->setCellValue("C" . $row_num, $dates[date('m')]['service']);
    $sheet->setCellValue("D" . $row_num, $dates[date('Y')]['service']);
    $sheet->setCellValue("E" . $row_num, $dates_back[date('d')]['service']);
    $sheet->setCellValue("F" . $row_num, $dates_back[date('m')]['service']);
    $sheet->setCellValue("G" . $row_num, $dates_back[date('Y',strtotime('-1 Year'))]['service']);
    $row_num++;

    $sheet->getStyle('A' . $row_num . ':G' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, 'ჯამური შემოსავალი (დარიცხული)');
    $sheet->setCellValue("B" . $row_num, $dates[date('d')]['sum']);
    $sheet->setCellValue("C" . $row_num, $dates[date('m')]['sum']);
    $sheet->setCellValue("D" . $row_num, $dates[date('Y')]['sum']);
    $sheet->setCellValue("E" . $row_num, $dates_back[date('d')]['sum']);
    $sheet->setCellValue("F" . $row_num, $dates_back[date('m')]['sum']);
    $sheet->setCellValue("G" . $row_num, $dates_back[date('Y',strtotime('-1 Year'))]['sum']);
    $row_num+=2;

    $sheet->getStyle('A' . $row_num . ':G' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, 'ჯავშნების შემოსავალი (საკასო)');
    $sheet->setCellValue("B" . $row_num, $income[date('Y-m')][date('d')]['accommodation_in']);
    $sheet->setCellValue("C" . $row_num, $income[date('Y-m')]['sum_acc']);
    $sheet->setCellValue("D" . $row_num, $income['master_sum']);
    $sheet->setCellValue("E" . $row_num, $income_back[date('Y-m',strtotime('-1 year'))][date('d')]['accommodation_in']);
    $sheet->setCellValue("F" . $row_num, $income_back[date('Y-m',strtotime('-1 year'))]['sum_acc']);
    $sheet->setCellValue("G" . $row_num, $income_back['master_sum']);
    $row_num++;

    $sheet->getStyle('A' . $row_num . ':G' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, 'სერვისების შემოსავალი (საკასო)');
    $sheet->setCellValue("B" . $row_num, $income[date('Y-m')][date('d')]['services_in']);
    $sheet->setCellValue("C" . $row_num, $income[date('Y-m')]['sum_serv']);
    $sheet->setCellValue("D" . $row_num, $income['master_serv']);
    $sheet->setCellValue("E" . $row_num, $income_back[date('Y-m',strtotime('-1 year'))][date('d')]['services_in']);
    $sheet->setCellValue("F" . $row_num, $income_back[date('Y-m',strtotime('-1 year'))]['sum_serv']);
    $sheet->setCellValue("G" . $row_num, $income_back['master_serv']);
    $row_num++;

    $sheet->getStyle('A' . $row_num . ':G' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, 'ჯამური შემოსავალი (საკასო)');
    $sheet->setCellValue("B" . $row_num, ($income[date('Y-m')][date('d')]['accommodation_in'] + $income[date('Y-m')][date('d')]['services_in']) );
    $sheet->setCellValue("C" . $row_num, ($income[date('Y-m')]['sum_acc'] + $income[date('Y-m')]['sum_serv']));
    $sheet->setCellValue("D" . $row_num, ($income['master_sum'] + $income['master_serv']));
    $sheet->setCellValue("E" . $row_num, ($income_back[date('Y-m',strtotime('-1 year'))][date('d')]['accommodation_in'] + $income_back[date('Y-m',strtotime('-1 year'))][date('d')]['services_in']) );
    $sheet->setCellValue("F" . $row_num, ($income_back[date('Y-m',strtotime('-1 year'))]['sum_acc'] + $income_back[date('Y-m',strtotime('-1 year'))]['sum_serv']) );
    $sheet->setCellValue("G" . $row_num, ($income_back['master_sum'] + $income_back['master_serv']) );
    $row_num++;

    $sheet->getStyle('A' . $row_num . ':G' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, 'დღგ (საკასო)');
    $sheet->setCellValue("B" . $row_num, number_format((($income[date('Y-m')][date('d')]['accommodation_in'] + $income[date('Y-m')][date('d')]['services_in'])-($income[date('Y-m')][date('d')]['accommodation_in'] + $income[date('Y-m')][date('d')]['services_in'])/1.18),2,'.',' ') );
    $sheet->setCellValue("C" . $row_num, number_format((($income[date('Y-m')]['sum_acc'] + $income[date('Y-m')]['sum_serv'])-($income[date('Y-m')]['sum_acc'] + $income[date('Y-m')]['sum_serv'])/1.18),2,'.',' '));
    $sheet->setCellValue("D" . $row_num, number_format((($income['master_sum'] + $income['master_serv'])-($income['master_sum'] + $income['master_serv'])/1.18),2,'.',' '));
    $sheet->setCellValue("E" . $row_num, number_format((($income_back[date('Y-m',strtotime('-1 year'))][date('d')]['accommodation_in'] + $income_back[date('Y-m',strtotime('-1 year'))][date('d')]['services_in'])-($income_back[date('Y-m',strtotime('-1 year'))][date('d')]['accommodation_in'] + $income_back[date('Y-m',strtotime('-1 year'))][date('d')]['services_in'])/1.18),2,'.',' ') );
    $sheet->setCellValue("F" . $row_num, number_format((($income_back[date('Y-m',strtotime('-1 year'))]['sum_acc'] + $income_back[date('Y-m',strtotime('-1 year'))]['sum_serv'])-($income_back[date('Y-m',strtotime('-1 year'))]['sum_acc'] + $income_back[date('Y-m',strtotime('-1 year'))]['sum_serv'])/1.18),2,'.',' ') );
    $sheet->setCellValue("G" . $row_num, number_format((($income_back['master_sum'] + $income_back['master_serv'])-($income_back['master_sum'] + $income_back['master_serv'])/1.18),2,'.',' ') );
    $row_num+=2;

    $sheet->getStyle('A' . $row_num . ':G' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, 'სულ ოთახები');
    $sheet->setCellValue("B" . $row_num, $rooms[date('Y-m')][date('d')]['total_rooms_count'] );
    $sheet->setCellValue("C" . $row_num, $rooms[date('Y-m')]['sum_all']);
    $sheet->setCellValue("D" . $row_num, $rooms['master_sum_all']);
    $sheet->setCellValue("E" . $row_num, $rooms_back[date('Y-m',strtotime('-1 year'))][date('d')]['total_rooms_count'] );
    $sheet->setCellValue("F" . $row_num, $rooms_back[date('Y-m',strtotime('-1 year'))]['sum_all'] );
    $sheet->setCellValue("G" . $row_num, $rooms_back['master_sum_all'] );
    $row_num++;

    $sheet->getStyle('A' . $row_num . ':G' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, 'დაკავებული ოთახები');
    $sheet->setCellValue("B" . $row_num, $rooms[date('Y-m')][date('d')]['used_rooms_count'] );
    $sheet->setCellValue("C" . $row_num, $rooms[date('Y-m')]['sum']);
    $sheet->setCellValue("D" . $row_num, $rooms['master_sum']);
    $sheet->setCellValue("E" . $row_num, $rooms_back[date('Y-m',strtotime('-1 year'))][date('d')]['used_rooms_count'] );
    $sheet->setCellValue("F" . $row_num, $rooms_back[date('Y-m',strtotime('-1 year'))]['sum'] );
    $sheet->setCellValue("G" . $row_num, $rooms_back['master_sum'] );
    $row_num++;

    $sheet->getStyle('A' . $row_num . ':G' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, 'დაკავებული ოთახების პროცენტულობა');
    $sheet->setCellValue("B" . $row_num, number_format(($rooms[date('Y-m')][date('d')]['used_rooms_count']/$rooms[date('Y-m')][date('d')]['total_rooms_count'])*100,2,',','.')."%" );
    $sheet->setCellValue("C" . $row_num, number_format(($rooms[date('Y-m')]['sum']/$rooms[date('Y-m')]['sum_all'])*100,2,',','.')."%");
    $sheet->setCellValue("D" . $row_num, number_format(($rooms['master_sum']/$rooms['master_sum_all'])*100,2,',','.')."%");
    $sheet->setCellValue("E" . $row_num, number_format(($rooms_back[date('Y-m',strtotime('-1 year'))][date('d')]['used_rooms_count']/$rooms_back[date('Y-m',strtotime('-1 year'))][date('d')]['total_rooms_count'])*100,2,',','.')."%" );
    $sheet->setCellValue("F" . $row_num, number_format(($rooms_back[date('Y-m',strtotime('-1 year'))]['sum']/$rooms_back[date('Y-m',strtotime('-1 year'))]['sum_all'])*100,2,',','.')."%" );
    $sheet->setCellValue("G" . $row_num, number_format(($rooms_back['master_sum']/$rooms_back['master_sum_all'])*100,2,',','.')."%" );
    $row_num+=2;

    $sheet->getStyle('A' . $row_num . ':G' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, 'დღეს შემოდის');
    $sheet->setCellValue("B" . $row_num, $in_out_bokings[date('d')]['check_in'] );
    $sheet->setCellValue("C" . $row_num, $in_out_bokings[date('m')]['check_in']);
    $sheet->setCellValue("D" . $row_num, $in_out_bokings[date('Y')]['check_in']);
    $sheet->setCellValue("E" . $row_num, $in_out_bokings_back[date('d')]['check_in'] );
    $sheet->setCellValue("F" . $row_num, $in_out_bokings_back[date('m')]['check_in'] );
    $sheet->setCellValue("G" . $row_num, $in_out_bokings_back[date('Y',strtotime('-1 year'))]['check_in'] );
    $row_num++;

    $sheet->getStyle('A' . $row_num . ':G' . $row_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
    $sheet->setCellValue("A" . $row_num, 'დღეს გადის');
    $sheet->setCellValue("B" . $row_num, $in_out_bokings[date('d')]['check_out'] );
    $sheet->setCellValue("C" . $row_num, $in_out_bokings[date('m')]['check_out']);
    $sheet->setCellValue("D" . $row_num, $in_out_bokings[date('Y')]['check_out']);
    $sheet->setCellValue("E" . $row_num, $in_out_bokings_back[date('d')]['check_out'] );
    $sheet->setCellValue("F" . $row_num, $in_out_bokings_back[date('m')]['check_out'] );
    $sheet->setCellValue("G" . $row_num, $in_out_bokings_back[date('Y',strtotime('-1 year'))]['check_out'] );
    $row_num++;

    //END Report 2
    #exit('before download');

    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=audit_" .date('Y-m-d H:m:s').".xls");
    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');
}

$TMPL->addVar('rooms',$usedRoomsCount);
$TMPL->addVar('rooms_back',$usedRooms_back);
$TMPL->addVar("income", $income);
$TMPL->addVar("income_back", $income_back);
$TMPL->addVar("dates", $dates);
$TMPL->addVar("dates_back", $dates_back);
$TMPL->addVar("user", $user);
$TMPL->addVar("hotelsettings", $hotelsettings);
$TMPL->addVar("in_out_bokings", $in_out_bokings);
$TMPL->addVar("in_out_bokings_back", $in_out_bokings_back);

$TMPL->ParseIntoVar($_CENTER,"full_report");
