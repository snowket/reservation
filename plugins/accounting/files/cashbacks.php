<?
$POST = $VALIDATOR->ConvertSpecialChars($_POST);

$whereClause = "WHERE 1 = 1";
(!empty($_GET['start_date']) && isset($_GET['start_date']))?$whereClause.=" AND date>='".$_GET['start_date']."'":$whereClause.='';
(!empty($_GET['end_date']) && isset($_GET['end_date']))?$whereClause.=" AND date<='".$_GET['end_date']."'":$whereClause.='';
(isset($_GET['guest_id']) && $_GET['guest_id']!='')?$whereClause.=" AND guest_id LIKE '%".$_GET['guest_id']."%'":$whereClause.='';
(isset($_GET['guest_name']) && $_GET['guest_name']!='')?$whereClause.=" AND guest_name LIKE '%".$_GET['guest_name']."%'":$whereClause.='';
(isset($_GET['amount_from']) && (float)$_GET['amount_from']>0)?$whereClause.=" AND total_cashback_amount>=".(float)$_GET['amount_from']:$whereClause.='';
(isset($_GET['amount_to']) && (float)$_GET['amount_to']>0)?$whereClause.=" AND total_cashback_amount<=".(float)$_GET['amount_to']:$whereClause.='';
$tmp_arr = implode(", ", $_GET['tax']);
(isset($_GET['amount_to']) && !empty($_GET['tax']))?$whereClause.=" AND guest_tax IN(".$tmp_arr.")":$whereClause.='';
$tmp_arr = implode(", ", $_GET['payment_method_id']);
(isset($_GET['payment_method_id']) && !empty($_GET['payment_method_id']))?$whereClause.=" AND payment_method_id IN(".$tmp_arr.")":$whereClause.='';


if ($_POST['action'] == 'get_excel') {
    $cashbacks=getAllBookingCashBacks($whereClause,true);
    $guests=getAllGuests();
    require_once('classes/PHPExcel/PHPExcel.php');
    require_once('classes/PHPExcel/PHPExcel/Writer/Excel5.php');
    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('CashBacks');
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

    $sheet->setCellValue("A5", $TEXT['cashbacks']['partner']);
    $sheet->getStyle('A5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('A5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('A')->setAutoSize(true);

    $sheet->setCellValue("B5", $TEXT['cashbacks']['date']);
    $sheet->getStyle('B5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('B5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('B5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('B')->setAutoSize(true);

    $sheet->setCellValue("C5", $TEXT['cashbacks']['amount']);
    $sheet->getStyle('C5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('C5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('C5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('C')->setAutoSize(true);

    $sheet->setCellValue("D5", $TEXT['cashbacks']['amount_paid']);
    $sheet->getStyle('D5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('D5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('D5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('D')->setAutoSize(true);

    $sheet->setCellValue("E5", $TEXT['cashbacks']['amount_due']);
    $sheet->getStyle('E5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('E5')->getFill()->getStartColor()->setRGB('3a82cc');
    $sheet->getStyle('E5')->getFont()->getColor()->applyFromArray(array('rgb' => 'FFFFFF'));
    $sheet->getColumnDimension('E')->setAutoSize(true);

    $sheet->getStyle('A5:E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getColumnDimension('A:E')->setAutoSize(true);

    for ($i = 0; $i < count($cashbacks); $i++) {
        $firstname = $guests[$cashbacks[$i]['affiliate_id']]['first_name'];
        $lastname = $guests[$cashbacks[$i]['affiliate_id']]['last_name'];
        $guest=($lastname=="")?$firstname:$firstname." ".$lastname;
        $amount=$joinedTransactions[$i]['amount'];
        $debit = ($amount>=0)?$amount:0;
        $credit = ($amount<0)?$amount:0;

        $sheet->setCellValueByColumnAndRow(0, $i+6, $guest);
        $sheet->setCellValueByColumnAndRow(1, $i+6, $cashbacks[$i]['date']);
        $sheet->setCellValueByColumnAndRow(2, $i+6, $cashbacks[$i]['total_cashback_amount']);
        $sheet->setCellValueByColumnAndRow(3, $i+6, $cashbacks[$i]['paid_cashback_amount']);
        $sheet->setCellValueByColumnAndRow(4, $i+6, ($cashbacks[$i]['total_cashback_amount']- $cashbacks[$i]['paid_cashback_amount']));
    }
    header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
    header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
    header ( "Cache-Control: no-cache, must-revalidate" );
    header ( "Pragma: no-cache" );
    header ( "Content-type: application/vnd.ms-excel" );
    header ( "Content-Disposition: attachment; filename=cashbacks.xls" );

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');

}else if($POST['action']=='pay_cashback'){
    $cashback_id=(int)$POST['cashback_id'];
    $amount=(double)$POST['amount'];

    $query = "SELECT * FROM {$_CONF['db']['prefix']}_booking_cashbacks
              WHERE id=".$cashback_id;
    $old_cashback = $CONN->GetRow($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());
    $new_paid_amount=$old_cashback['paid_cashback_amount']+$amount;
    if($new_paid_amount>$old_cashback['total_cashback_amount'] || $amount<=0){
        die($new_paid_amount.'>'.$old_cashback['total_cashback_amount']);
    }

    addCashBackTransaction($old_cashback['guest_id'], $old_cashback['booking_id'],-$amount, 'cashback');
    $query = "UPDATE {$_CONF['db']['prefix']}_booking_cashbacks SET
    			 	 paid_cashback_amount={$new_paid_amount}
    			 	 WHERE id={$cashback_id}";
    $CONN->Execute($query) or $FUNC->ServerError(__FILE__, __LINE__, $CONN->ErrorMsg());

    $FUNC->Redirect($SELF_FILTERED);
}


$TMPL->addVar('TMPL_guests',getAllGuests());
$TMPL->addVar("TMPL_payment_methods", getAllPaymentMethods());
//$whereClause='';
$TMPL->addVar('TMPL_cashbacks',getAllBookingCashBacks($whereClause));

$TMPL->addVar("TMPL_navbar", $navbar);
$TMPL->ParseIntoVar($_CENTER,"cashbacks");
