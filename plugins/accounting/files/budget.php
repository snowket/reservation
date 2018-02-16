<?
    use Carbon\Carbon;
    #dd($_POST);
    $min_year=getMinYearSelect();
    $TMPL->addVar("min_year",$min_year);
    $select=($_GET['date'])?$_GET['date']:date('Y-m-d');
    $date = Carbon::parse($select);
    $last_year=Carbon::parse($date->copy()->subYear()->format('Y-m-d'));
    $pre_last_year=Carbon::parse($last_year->copy()->subYear()->format('Y-m-d'));


    $start_date=($_GET['start_date'])?$_GET['start_date']:date('Y');
    $end_date=($_GET['end_date'])?$_GET['end_date']:date('Y');

    $year_ar=array($start_date,$end_date);
    sort($year_ar);
    $TMPL->addVar("year_ar",$year_ar);


    $POST = $VALIDATOR->ConvertSpecialChars($_POST);
    if($POST['action'] == 'save_budget'){
      #dd($POST);
      createBudget($POST['st_year'],$POST['month_price']);
      $FUNC->redirect($SELF."&start_date=".$start_date."&end_date=".$end_date);
    }elseif($POST['action'] == 'edit_budget'){
      editBudget($POST['year'],$POST['sm_budget'][$POST['year']]);
      $FUNC->redirect($SELF."&start_date=".$start_date."&end_date=".$end_date);
    }

    $selected_year=(isset($_GET['year']))?$_GET['year']:date('Y');
    $selected_month=(isset($_GET['month']))?$_GET['month']:date('m');

    $ref_year_array=array();
    while ($start_date<=$end_date) {
      $ref_year_array[]=$start_date;
      $start_date++;
    }
    foreach ($ref_year_array as $value) {
      $tmp=getAnnualIncomeReport($value);
      $report[$value]=$tmp[$value];
    }
    $budgets=getBudgets($ref_year_array);
    #dd($budgets);

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

      if($year_ar[0]==$year_ar[1]){
          $sheet->setCellValue('A4', $year_ar[0]." ".$TEXT['b_year']." ".$TEXT['check_budget']);
      }else{
          $sheet->setCellValue('A4', $year_ar[0]." - ".$year_ar[1]." ".$TEXT['b_years']." ".$TEXT['check_budgets']);
      }

      $sheet->setCellValue("A5", $TEXT['start_year']);
      $sheet->getStyle('A5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('A5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('A5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('A')->setAutoSize(true);

      $sheet->setCellValue("B5", $TEXT['transactions']['status']);
      $sheet->getStyle('B5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('B5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('B5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('B')->setAutoSize(true);

      $sheet->setCellValue("C5", $TEXT['months_budget'][1]);
      $sheet->getStyle('C5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('C5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('C5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('C')->setAutoSize(true);

      $sheet->setCellValue("D5", $TEXT['months_budget'][2]);
      $sheet->getStyle('D5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('D5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('D5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('D')->setAutoSize(true);

      $sheet->setCellValue("E5", $TEXT['months_budget'][3]);
      $sheet->getStyle('E5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('E5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('E5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('E')->setAutoSize(true);

      $sheet->setCellValue("F5", $TEXT['months_budget'][4]);
      $sheet->getStyle('F5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('F5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('F5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('F')->setAutoSize(true);

      $sheet->setCellValue("G5", $TEXT['months_budget'][5]);
      $sheet->getStyle('G5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('G5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('G5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('G')->setAutoSize(true);

      $sheet->setCellValue("H5", $TEXT['months_budget'][6]);
      $sheet->getStyle('H5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('H5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('H5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('H')->setAutoSize(true);

      $sheet->setCellValue("I5", $TEXT['months_budget'][7]);
      $sheet->getStyle('I5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('I5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('I5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('I')->setAutoSize(true);

      $sheet->setCellValue("J5", $TEXT['months_budget'][8]);
      $sheet->getStyle('J5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('J5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('J5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('J')->setAutoSize(true);

      $sheet->setCellValue("K5", $TEXT['months_budget'][9]);
      $sheet->getStyle('K5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('K5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('K5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('K')->setAutoSize(true);

      $sheet->setCellValue("L5", $TEXT['months_budget'][10]);
      $sheet->getStyle('L5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('L5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('L5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('L')->setAutoSize(true);

      $sheet->setCellValue("M5", $TEXT['months_budget'][11]);
      $sheet->getStyle('M5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('M5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('M5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('M')->setAutoSize(true);

      $sheet->setCellValue("N5", $TEXT['months_budget'][12]);
      $sheet->getStyle('N5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $sheet->getStyle('N5')->getFill()->getStartColor()->setRGB('3a82cc');
      $sheet->getStyle('N5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
      $sheet->getColumnDimension('N')->setAutoSize(true);

      $sheet->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $sheet->getColumnDimension('A:N')->setAutoSize(true);
      $counter=1;
      foreach ($budgets AS $m=>$v) {
          $sheet->setCellValueByColumnAndRow(0, $counter +5, $m);
          $tmp=1;
          $sheet->setCellValueByColumnAndRow($tmp, $counter +5, $TEXT['rss']['status'][1]);
          $sheet->setCellValueByColumnAndRow($tmp, $counter +6, $TEXT['rss']['status'][2]);
          $sheet->setCellValueByColumnAndRow($tmp, $counter +7, $TEXT['rss']['status'][3]);
          foreach ($v['prices'] as $key => $value) {
            $sheet->setCellValueByColumnAndRow($tmp+1, $counter +5, $value['st_budget']);
            $vg=$key+1;
            $qs=$report[$m][str_pad($vg, 2, '0', STR_PAD_LEFT)]['accommodation_in']+$report[$m][str_pad($vg, 2, '0', STR_PAD_LEFT)]['services'];
            $qs = $qs <= 0 ? $qs : '+'.$qs ;
            $sheet->setCellValueByColumnAndRow($tmp+1, $counter +6, $qs);
            $r=$m['st_budget']-$qs;
            $r = $r <= 0 ? '+'.abs($r) : -$r ;
            $sheet->setCellValueByColumnAndRow($tmp+1, $counter +7, $r);
            $tmp++;
          }
          $sheet->getStyle('A' . ($counter + 5) . ':N' . ($counter + 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
          $sheet->getStyle('A' . ($counter + 6) . ':N' . ($counter + 6))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
          $sheet->getStyle('A' . ($counter + 7) . ':N' . ($counter + 7))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('000000');
          $counter+=4;
      }
      header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
      header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
      header ( "Cache-Control: no-cache, must-revalidate" );
      header ( "Pragma: no-cache" );
      header ( "Content-type: application/vnd.ms-excel" );
      if($year_ar[0]!=$year_ar[1]){
          header ( "Content-Disposition: attachment; filename=budget_compare_from_".$year_ar[0]."_to_".$year_ar[1].".xls" );
      }else{
          header ( "Content-Disposition: attachment; filename=budget_compare_to_".$year_ar[0].".xls" );
      }


      $objWriter = new PHPExcel_Writer_Excel5($xls);
      $objWriter->save('php://output');
      exit;
    }
    $TMPL->addVar("budgets",$budgets);
    $TMPL->addVar("date",$date);

    $TMPL->addVar("report",$report);
    $TMPL->addVar("days",count($days));

    $TMPL->ParseIntoVar($_CENTER,"budget");


    function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = array();
        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }




    function checkBudget($index){
      global $CONN,$FUNC;
      $qs="SELECT * FROM cms_budget WHERE st_year=".$index;
      $result = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
      return count($result->getRows())?true:false;
    }
    function createBudget($index,$array){
      global $CONN,$FUNC;
      foreach ($array as $key => $value) {
        $qs="INSERT INTO cms_budget SET st_year=".$index.",st_month=".$key.",st_budget=".$value;
    	  $result = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        }
    }
    function editBudget($index,$array){
      global $CONN,$FUNC;
      if(checkBudget($index)){
        foreach ($array as $key => $value) {
          $qs="UPDATE cms_budget SET st_budget=".$value." WHERE st_year=".$index." AND st_month=".$key;
          #dd($qs);
      	  $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
          }
      }else{
        createBudget($index,$array);
      }
      // foreach ($array as $key => $value) {
      //   $qs="INSERT INTO cms_budget SET st_year=".$index.",st_month=".$key.",st_budget=".$value;
    	//   $result = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
      //   }
      return true;
    }
    function getBudgetYearArray($start=null,$end=null){
      global $CONN,$FUNC;
      if(!is_null($start) && !is_null($end)){
        $qs="SELECT DISTINCT(st_year) FROM cms_budget  WHERE st_year >= '".$start."' AND st_year <= '".$end."' ORDER BY st_year ASC";
      }
      else{
        $qs="SELECT DISTINCT(st_year) FROM cms_budget  ORDER BY st_year ASC";
      }
      $result = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
      $result=$result->getRows();
      return $result;
    }


    function getBudgets($years=null){
      global $CONN,$FUNC;
      $rs=array();
      #$years_array=getBudgetYearArray($years[0],$years[1]);
      foreach ($years as  $year ) {
        $rs[$year]['price_sum']=0;
        $rs[$year]['prices']=array();
        $rs[$year]['prices']=array_pad($rs[$year]['prices'],12,array('st_budget'=>0));
        $qs="SELECT * FROM cms_budget WHERE st_year=".$year."  ORDER BY st_year DESC,st_month ASC";
        #dd($qs);
        $result = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
        $result=$result->getRows();
        foreach ($result as $key => $value) {
          $rs[$year]['prices'][$value['st_month']]=$value;
          $rs[$year]['price_sum']=getSumBudget($year);
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
    function getMinYearSelect(){
      global $CONN,$FUNC;
      $qs="SELECT MIN(`date`) as min_year FROM cms_room_prices";
      $result = $CONN->Execute($qs) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
      $result=$result->fields;
      return $result['min_year'];
    }
