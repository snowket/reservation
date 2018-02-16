<?
  error_reporting(E_ALL);
  ini_set('display_error',on);
    use Carbon\Carbon;
    $select=($_GET['date'])?$_GET['date']:date('Y-m-d');
    $date = Carbon::parse($select);
    $last_year=Carbon::parse($date->copy()->subYear()->format('Y-m-d'));
    $pre_last_year=Carbon::parse($last_year->copy()->subYear()->format('Y-m-d'));


    $POST = $VALIDATOR->ConvertSpecialChars($_POST);
    if($POST['action']=='save_budget'){
      createBudget($POST['ts_year'],$POST['month_price']);
      $FUNC->redirect($SELF);
    }

    $selected_year=(isset($_GET['year']))?$_GET['year']:date('Y');
    $selected_month=(isset($_GET['month']))?$_GET['month']:date('m');
    $reports_full=getBudgetIncomeReport($selected_year);
    $reports_daily=getDailyAnnualIncomeReport($selected_year, $selected_month);
    $budgets=getBudgets();

    $getAvailableRoomsCount=getAvailableRoomsCount($date->format('Y-m-d'));
    $restrictedRooms=restrictedRooms($date->format('Y-m-d'));
    $sold_rooms=GetSoldRooms($date->format('Y-m-d'));
    $reportings['today']=array(
      'getAvailableRoomsCount'=>$getAvailableRoomsCount,
      'sold_rooms'=>$sold_rooms,
      'restrictedRooms'=>$restrictedRooms,
      'day_report'=>$reports_daily[$date->format('Y-m')][$date->format('d')],
    );
    $reportings['last_year']=array(
      'getAvailableRoomsCount'=>getAvailableRoomsCount($last_year->format('Y-m-d')),
      'sold_rooms'=>GetSoldRooms($last_year->format('Y-m-d')),
      'restrictedRooms'=>restrictedRooms($last_year->format('Y-m-d')),
      'day_report'=>$reports_daily[$last_year->format('Y-m')][$last_year->format('d')],
    );



    $days=generateDateRange($date->copy()->startOfMonth(),$date);
    $days=count($days);
    $mtd_soldRooms=mtd_GetSoldRooms($date);

    $Mtdreportings['today']=array(
      'sold_rooms'=>$mtd_soldRooms,
      'restrictedRooms'=>mtd_restrictedRooms($date),
      'getAvailableRoomsCount'=>mtd_getAvailableRoomsCount($date),
      'day_report'=>mtd_dayReport($date),
    );
    $Mtdreportings['last_year']=array(
      'sold_rooms'=>mtd_GetSoldRooms($last_year),
      'restrictedRooms'=>mtd_restrictedRooms($last_year),
      'getAvailableRoomsCount'=>mtd_getAvailableRoomsCount($last_year),
      'day_report'=>mtd_dayReport($last_year),
    );
    if($POST['action'] == 'get_excel'){
      require_once('classes/PHPExcel/PHPExcel.php');
      require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
      $settings = getSettings();
      $xls = new PHPExcel();
      $xls->setActiveSheetIndex(0);
      $sheet = $xls->getActiveSheet();
      $sheet->setTitle('Budget');

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
      $sheet->setCellValue('A4', $TEXT['tab']['reports']['sub'][$_GET['tab']]." (".$date->format('d-m-Y').")");

      $sheet->setCellValue("A5", $TEXT['rss']['daily_rev']);
      $sheet->getStyle('A5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('A5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('A5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('A')->setAutoSize(true);

      $sheet->setCellValue("B5", $TEXT['rss']['sel_day']);
      $sheet->getStyle('B5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('B5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('B5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('B')->setAutoSize(true);

      $sheet->setCellValue("C5", $TEXT['rss']['budget']);
      $sheet->getStyle('C5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('C5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('C5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('C')->setAutoSize(true);

      $sheet->setCellValue("D5", $TEXT['rss']['diff']);
      $sheet->getStyle('D5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('D5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('D5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('D')->setAutoSize(true);

      $sheet->setCellValue("E5", $TEXT['rss']['diff']);
      $sheet->getStyle('E5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('E5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('E5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('E')->setAutoSize(true);

      $sheet->setCellValue("F5", $TEXT['rss']['l_year']);
      $sheet->getStyle('F5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('F5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('F5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('F')->setAutoSize(true);

      $sheet->setCellValue("G5", $TEXT['rss']['budget']);
      $sheet->getStyle('G5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('G5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('G5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('G')->setAutoSize(true);

      $sheet->setCellValue("H5", $TEXT['rss']['diff']);
      $sheet->getStyle('H5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('H5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('H5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('H')->setAutoSize(true);

      $sheet->setCellValue("I5", $TEXT['rss']['diff']);
      $sheet->getStyle('I5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('I5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('I5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('I')->setAutoSize(true);

      $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $sheet->getColumnDimension('A:I')->setAutoSize(true);
      $counter=1;

      $ACTD=($reportings['today']['day_report']['accommodation_in']+$reportings['today']['day_report']['services_in']);
      $BGD=($budgets[$date->format('Y')]['prices'][$date->format('m')-1]['st_budget'])/$date->format('t');
      $charge=$ACTD-$BGD;
      $chargeP=$charge/$BGD;
      $chargeP=$chargeP*100;
      $tmp=0;
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['income']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, $ACTD);
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, number_format($BGD,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, number_format($charge,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, number_format($chargeP,2,'.','')." %");
      $ACTD=($reportings['last_year']['day_report']['accommodation_in']+$reportings['last_year']['day_report']['services_in']);
      $BGD=($budgets[$last_year->format('Y')]['prices'][$last_year->format('m')-1]['st_budget'])/$last_year->format('t');
      $charge=$ACTD-$BGD;
      $chargeP=$charge/$BGD;
      $chargeP=$chargeP*100;
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5, $ACTD);
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5, number_format($BGD,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5, number_format($charge,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5, number_format($chargeP,2,'.','')." %");
      $counter++;
      $tmp=0;
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['a_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, count($reportings['today']['getAvailableRoomsCount']));
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, count($reportings['today']['getAvailableRoomsCount']));
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, count($reportings['today']['getAvailableRoomsCount']));
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, count($reportings['today']['getAvailableRoomsCount']));
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5, count($reportings['last_year']['getAvailableRoomsCount']));
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5, count($reportings['last_year']['getAvailableRoomsCount']));
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5, count($reportings['last_year']['getAvailableRoomsCount']));
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5, count($reportings['last_year']['getAvailableRoomsCount']));
      $counter++;
      $tmp=0;
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['o_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, count($reportings['today']['restrictedRooms']));
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, count($reportings['today']['restrictedRooms']));
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, count($reportings['today']['restrictedRooms']));
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, count($reportings['today']['restrictedRooms']));
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5, count($reportings['last_year']['restrictedRooms']));
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5, count($reportings['last_year']['restrictedRooms']));
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5, count($reportings['last_year']['restrictedRooms']));
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5, count($reportings['last_year']['restrictedRooms']));
      $counter++;
      $tmp=0;
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['r_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, $reportings['today']['sold_rooms']['count']);
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, $reportings['today']['sold_rooms']['count']);
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, $reportings['today']['sold_rooms']['count']);
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, $reportings['today']['sold_rooms']['count']);
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5, $reportings['last_year']['sold_rooms']['count']);
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5, $reportings['last_year']['sold_rooms']['count']);
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5, $reportings['last_year']['sold_rooms']['count']);
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5, $reportings['last_year']['sold_rooms']['count']);
      $counter++;
      $OC=$reportings['today']['sold_rooms']['count']/count($reportings['today']['getAvailableRoomsCount']);
      $tmp=0;
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['oc_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, number_format(($OC)*100,2,'.','')." %");
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, number_format(($OC)*100,2,'.','')." %");
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, number_format(($OC)*100,2,'.','')." %");
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, number_format(($OC)*100,2,'.','')." %");
      $OC=$reportings['last_year']['sold_rooms']['count']/count($reportings['last_year']['getAvailableRoomsCount']);
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5,  number_format(($OC)*100,2,'.','')." %");
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5,  number_format(($OC)*100,2,'.','')." %");
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5,  number_format(($OC)*100,2,'.','')." %");
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5,  number_format(($OC)*100,2,'.','')." %");
      $counter++;
      $tmp=0;
      $ADR=(($reportings['today']['day_report']['accommodation_in']+$reportings['today']['day_report']['services_in'])/$reportings['today']['sold_rooms']['count']);
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['adr']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, number_format($ADR,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, number_format($ADR,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, number_format($ADR,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, number_format($ADR,2,'.',''));
      $ADR=(($reportings['last_year']['day_report']['accommodation_in']+$reportings['last_year']['day_report']['services_in'])/$reportings['today']['sold_rooms']['count']);
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5,  number_format($ADR,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5,  number_format($ADR,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5,  number_format($ADR,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5,  number_format($ADR,2,'.',''));
      $counter++;
      $tmp=0;
      $Rev=(($reportings['today']['day_report']['accommodation_in']+$reportings['today']['day_report']['services_in'])/count($reportings['today']['getAvailableRoomsCount']));
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['rev_par']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, number_format($Rev,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, number_format($Rev,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, number_format($Rev,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, number_format($Rev,2,'.',''));
      $Rev=(($reportings['last_year']['day_report']['accommodation_in']+$reportings['last_year']['day_report']['services_in'])/count($reportings['today']['getAvailableRoomsCount']));
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5,  number_format($Rev,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5,  number_format($Rev,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5,  number_format($Rev,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5,  number_format($Rev,2,'.',''));
      $sheet->getStyle('A5:I12')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');

      $counter+=5;
      $count=19;
      $sheet->mergeCells('A'.($count-4).':D'.($count-4));
      $sheet->getStyle('A'.($count-4))->getAlignment()->setWrapText(true);
      $sheet->getStyle('A'.($count-4).':D'.($count-4))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
      $sheet->setCellValue('A'.($count-4), $date->copy()->startOfMonth()->format('d-m-Y')." / " .$date->format('d-m-Y')." ". $TEXT['tab']['reports']['sub'][$_GET['tab']] ." ".$TEXT['mdt_charge']);


      $sheet->setCellValue("A".($count-3), $TEXT['rss']['daily_rev']);
      $sheet->getStyle('A'.($count-3))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('A'.($count-3))->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('A'.($count-3))->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('A')->setAutoSize(true);

      $sheet->setCellValue("B".($count-3), $TEXT['rss']['sel_day']);
      $sheet->getStyle('B'.($count-3))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('B'.($count-3))->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('B'.($count-3))->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('B')->setAutoSize(true);

      $sheet->setCellValue("C".($count-3), $TEXT['rss']['budget']);
      $sheet->getStyle('C'.($count-3))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('C'.($count-3))->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('C'.($count-3))->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('C')->setAutoSize(true);

      $sheet->setCellValue("D".($count-3), $TEXT['rss']['diff']);
      $sheet->getStyle('D'.($count-3))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('D'.($count-3))->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('D'.($count-3))->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('D')->setAutoSize(true);

      $sheet->setCellValue("E".($count-3), $TEXT['rss']['diff']);
      $sheet->getStyle('E'.($count-3))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('E'.($count-3))->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('E'.($count-3))->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('E')->setAutoSize(true);

      $sheet->setCellValue("F".($count-3), $TEXT['rss']['l_year']);
      $sheet->getStyle('F'.($count-3))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('F'.($count-3))->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('F'.($count-3))->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('F')->setAutoSize(true);

      $sheet->setCellValue("G".($count-3), $TEXT['rss']['budget']);
      $sheet->getStyle('G'.($count-3))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('G'.($count-3))->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('G'.($count-3))->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('G')->setAutoSize(true);

      $sheet->setCellValue("H".($count-3), $TEXT['rss']['diff']);
      $sheet->getStyle('H'.($count-3))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('H'.($count-3))->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('H'.($count-3))->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('H')->setAutoSize(true);

      $sheet->setCellValue("I".($count-3), $TEXT['rss']['diff']);
      $sheet->getStyle('I'.($count-3))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('I'.($count-3))->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('I'.($count-3))->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('I')->setAutoSize(true);

      $sheet->getStyle('A'.($count-3).':I'.($count-3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $sheet->getColumnDimension('A:I')->setAutoSize(true);

      $ACTD=$ACTD=($Mtdreportings['today']['day_report']);
      $BGD=($budgets[$date->format('Y')]['prices'][$date->format('m')-1]['st_budget'])/$date->format('t'); $BGD=$BGD*$days;
      $charge=$ACTD-$BGD;
      $chargeP=$charge/$BGD;
      $chargeP=$chargeP*100;
      $tmp=0;
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['income']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, $ACTD);
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, number_format($BGD,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, number_format($charge,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, number_format($chargeP,2,'.','')." %");
      $ACTD=$ACTD=($Mtdreportings['last_year']['day_report']);
      $BGD=($budgets[$last_year->format('Y')]['prices'][$last_year->format('m')-1]['st_budget'])/$last_year->format('t'); $BGD=$BGD*$days;
      $charge=$ACTD-$BGD;
      $chargeP=$charge/$BGD;
      $chargeP=$chargeP*100;
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5, $ACTD);
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5, number_format($BGD,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5, number_format($charge,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5, number_format($chargeP,2,'.','')." %");
      $counter++;
      $tmp=0;
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['a_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, $Mtdreportings['today']['getAvailableRoomsCount']);
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, $Mtdreportings['today']['getAvailableRoomsCount']);
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, $Mtdreportings['today']['getAvailableRoomsCount']);
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, $Mtdreportings['today']['getAvailableRoomsCount']);
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5, $Mtdreportings['last_year']['getAvailableRoomsCount']);
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5, $Mtdreportings['last_year']['getAvailableRoomsCount']);
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5, $Mtdreportings['last_year']['getAvailableRoomsCount']);
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5, $Mtdreportings['last_year']['getAvailableRoomsCount']);
      $counter++;
      $tmp=0;
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['o_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, $Mtdreportings['today']['restrictedRooms']);
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, $Mtdreportings['today']['restrictedRooms']);
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, $Mtdreportings['today']['restrictedRooms']);
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, $Mtdreportings['today']['restrictedRooms']);
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5, $Mtdreportings['last_year']['restrictedRooms']);
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5, $Mtdreportings['last_year']['restrictedRooms']);
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5, $Mtdreportings['last_year']['restrictedRooms']);
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5, $Mtdreportings['last_year']['restrictedRooms']);
      $counter++;
      $tmp=0;
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['r_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, $Mtdreportings['today']['sold_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, $Mtdreportings['today']['sold_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, $Mtdreportings['today']['sold_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, $Mtdreportings['today']['sold_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5, $Mtdreportings['last_year']['sold_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5, $Mtdreportings['last_year']['sold_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5, $Mtdreportings['last_year']['sold_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5, $Mtdreportings['last_year']['sold_rooms']);
      $counter++;
      $OC=$Mtdreportings['today']['sold_rooms']/$Mtdreportings['today']['getAvailableRoomsCount'];
      $tmp=0;
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['oc_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, number_format(($OC)*100,2,'.','')." %");
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, number_format(($OC)*100,2,'.','')." %");
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, number_format(($OC)*100,2,'.','')." %");
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, number_format(($OC)*100,2,'.','')." %");
      $OC=$Mtdreportings['last_year']['sold_rooms']/$Mtdreportings['last_year']['getAvailableRoomsCount'];
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5,  number_format(($OC)*100,2,'.','')." %");
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5,  number_format(($OC)*100,2,'.','')." %");
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5,  number_format(($OC)*100,2,'.','')." %");
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5,  number_format(($OC)*100,2,'.','')." %");
      $counter++;
      $tmp=0;
      $ADR=(($Mtdreportings['today']['day_report'])/$Mtdreportings['today']['sold_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['adr']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, number_format($ADR,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, number_format($ADR,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, number_format($ADR,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, number_format($ADR,2,'.',''));
     $ADR=(($Mtdreportings['last_year']['day_report'])/$Mtdreportings['last_year']['sold_rooms']);
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5,  number_format($ADR,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5,  number_format($ADR,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5,  number_format($ADR,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5,  number_format($ADR,2,'.',''));
      $counter++;
      $tmp=0;
      $Rev=(($Mtdreportings['today']['day_report'])/$Mtdreportings['today']['getAvailableRoomsCount']);
      $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['rev_par']);
      $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, number_format($Rev,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+2, $counter +5, number_format($Rev,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+3, $counter +5, number_format($Rev,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+4, $counter +5, number_format($Rev,2,'.',''));
      $Rev=(($Mtdreportings['last_year']['day_report'])/$Mtdreportings['last_year']['getAvailableRoomsCount']);
      $sheet->setCellValueByColumnAndRow($tmp+5, $counter +5,  number_format($Rev,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+6, $counter +5,  number_format($Rev,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+7, $counter +5,  number_format($Rev,2,'.',''));
      $sheet->setCellValueByColumnAndRow($tmp+8, $counter +5,  number_format($Rev,2,'.',''));
      $sheet->getStyle('A17:I23')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
      $counter++;

      header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
      header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
      header ( "Cache-Control: no-cache, must-revalidate" );
      header ( "Pragma: no-cache" );
      header ( "Content-type: application/vnd.ms-excel" );
      header ( "Content-Disposition: attachment; filename=Daily_fainancial_report.xls" );


      $objWriter = new PHPExcel_Writer_Excel5($xls);
      $objWriter->save('php://output');
      exit;
    }
   #dd($Mtdreportings);
    $TMPL->addVar("budgets",$budgets);
    $TMPL->addVar("date",$date);
    $TMPL->addVar("days",$days);
    $TMPL->addVar("last_year",$last_year);
    $TMPL->addVar("reportings",$reportings);
    $TMPL->addVar("Mtdreportings",$Mtdreportings);
    $TMPL->ParseIntoVar($_CENTER,"rss");


    function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = array();
        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }
    function mtd_GetSoldRooms($day){
      global $CONN,$FUNC;
      $days=generateDateRange($day->copy()->startOfMonth(),$day);
      $returnValue=0;
      foreach ($days as $key => $x) {
        $qs="SELECT count(*) as count FROM cms_booking WHERE  date(check_in)<='".$x."' AND date(check_out)>'".$x."'";
        $result = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $result=$result->fields;
        $returnValue+=$result['count'];
      }
      return  $returnValue;
    }
    function mtd_getAvailableRoomsCount($day)
    {
        global $CONN,$FUNC,$_CONF;
        $days=generateDateRange($day->copy()->startOfMonth(),$day);
        $info=0;
        foreach ($days as $key => $d) {
          $tmp=getAvailableRoomsCount($d);
          $info+=count($tmp);
          $tmp=array();
        }
        return $info;
    }
    function mtd_restrictedRooms($day)
    {
        global $CONN,$FUNC,$_CONF;
        $days=generateDateRange($day->copy()->startOfMonth(),$day);
        $info=0;
        foreach ($days as $key => $d) {
          $tmp=restrictedRooms($d);
          $info+=count($tmp);
          $tmp=array();
        }
        return $info;
    }
    function mtd_dayReport($day)
    {
        global $CONN,$FUNC,$_CONF;
        $info=0;
          $selected_year=$day->format('Y');
          $selected_month=$day->format('m');
          $tmp=getDailyAnnualIncomeReport($selected_year, $selected_month);

          foreach ($tmp[$day->format('Y-m')] as $m => $value) {
            $vs=Carbon::parse($value['day']);
            if($vs->lte($day)){
              $info+=$value['accommodation_in'];
              $info+=$value['services_in'];
            }
          }
          $tmp=array();
        return $info;
    }



    function createBudget($index,$array){
      global $CONN,$FUNC;
      foreach ($array as $key => $value) {
        $qs="INSERT INTO cms_budget SET st_year=".$index.",st_month=".$key.",st_budget=".$value;
    	  $result = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        }
    }
    function getBudgetYearArray(){
      global $CONN,$FUNC;
      $qs="SELECT DISTINCT(st_year) FROM cms_budget ORDER BY st_year ASC";
      $result = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
      $result=$result->getRows();
      return $result;
    }

    function GetSoldRooms($day){
      global $CONN,$FUNC;
      $qs="SELECT count(*) as count FROM cms_booking WHERE  date(check_in)<='".$day."' AND date(check_out)>'".$day."'";
      $result = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
      $result=$result->fields;
      return  $result;
    }
    function getBudgets(){
      global $CONN,$FUNC;
      $rs=array();
      $years_array=getBudgetYearArray();
      foreach ($years_array as $key=> $year ) {
        $qs="SELECT * FROM cms_budget WHERE st_year=".$year['st_year']." ORDER BY st_month ASC";
        $result = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $result=$result->getRows();
        foreach ($result as $key => $value) {
          $rs[$year['st_year']]['prices'][$value['st_month']]=$value;
          $rs[$year['st_year']]['price_sum']=getSumBudget($year['st_year']);
        }
      }
    return  $rs;
    }
    function getSumBudget($year){
      global $CONN,$FUNC;
      $qs="SELECT SUM(st_budget) as sum FROM cms_budget WHERE st_year=".$year;
  	  $result = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
      $result=$result->fields;
      return  $result;
    }
    function getRevPar($day){
      global $CONN,$FUNC;
    }
