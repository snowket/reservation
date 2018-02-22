<?
if (!defined('ALLOW_ACCESS')) exit;

$rate=(isset($_GET['rate']) && $_GET['rate']!='')?$_GET['rate']:1;
$count=(isset($_GET['count']) && $_GET['count']!='')?$_GET['count']:1;
$opt=(isset($_GET['opt']) && $_GET['opt']!='')?$_GET['opt']:'GEL';
function convertGelTo($gel, $rate,$count){
    return number_format($gel/$rate*$count,4,'.','');
}
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection(array('pageSizeH'=>18000,'marginTop'=>500));
$height=40;
$styleTable = array('width' => 100*50, 'unit' => 'pct','borderSize' => 2, 'borderColor' => '006699', 'cellMargin' => 80);
$styleFirstRow = array('borderBottomSize' => 10, 'borderBottomColor' => '0000FF', 'bgColor' => 'FFFFFF');
$styleCell = array('valign' => 'center');
$styleCellBTLR = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
$fontStyle = array('name'=>'sylfaen','size'=>9,'bold' => true, 'align' => 'center');
$phpWord->addTableStyle('ft', $styleTable, $styleFirstRow);
$paragraphOptions = array('space' => array('line' => 250));
$rowStyle=array("exactHeight" => true);


$table = $section->addTable(array('width' => 100*50, 'unit' => 'pct','borderSize' => 2, 'borderColor' => '006699', 'cellMargin' => 80));
$table->addRow();
$table->addCell(4000, $styleCell)->addText($settings['ltd']['value'].' '.$settings['address']['value'].' '.$settings['identification_code']['value'].' '.$settings['bank']['value'].' '.$settings['code_of_bank']['value'].' '.$settings['account']['value'], $fontStyle);
$table->addCell(1000, $styleCell)->addImage('../uploads/'.$hotel_settings['logo'],array('width'=>'200'));

$table->addCell(2000, array('width' => 2000, 'unit' => 'pct',))->addText('INVOICE N:'.$invoice_number.' Date: '.date("d-m-Y"), $fontStyle);


$section->addTextBreak(1,array('size'=>'2'));


$table = $section->addTable(array('width' => 100*50, 'unit' => 'pct','borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80));
$table->addRow(320,$rowStyle);
$table->addCell(null, $styleCell)->addText($TEXT['invoice']['guest_info'], $fontStyle,$paragraphOptions);
$cell=$table->addCell(null, $styleCell)->addText($guest['first_name'].' '.$guest['last_name'], $fontStyle,$paragraphOptions);
$table->addRow(320,$rowStyle);
$table->addCell(null, $styleCell)->addText('ID', $fontStyle,$paragraphOptions);
$table->addCell(null, $styleCell)->addText($guest['id_number'], $fontStyle,$paragraphOptions);
$table->addRow(320,$rowStyle);
$table->addCell(null, $styleCell)->addText($TEXT['invoice']['address'], $fontStyle,$paragraphOptions);
$table->addCell(null, $styleCell)->addText($guest['address'], $fontStyle,$paragraphOptions);
$table->addRow(320,$rowStyle);
$table->addCell(null, $styleCell)->addText($TEXT['invoice']['email'], $fontStyle,$paragraphOptions);
$table->addCell(null, $styleCell)->addText($guest['email'], $fontStyle,$paragraphOptions);
$table->addRow(320,$rowStyle);
$table->addCell(null, $styleCell)->addText($TEXT['invoice']['phone'], $fontStyle,$paragraphOptions);
$table->addCell(null, $styleCell)->addText($guest['telephone'], $fontStyle,$paragraphOptions);
$table->addRow(320,$rowStyle);
$table->addCell(null, $styleCell)->addText('TAX', $fontStyle,$paragraphOptions);
$table->addCell(null, $styleCell)->addText(($guest['tax']==0)?"TAX FREE":"TAX INCLUDED", $fontStyle,$paragraphOptions);



$total_price=0;
$breack=0;
#dd($bookings);
foreach($bookings AS $booking) {
    $booking['accommodation_price']=convertGelTo($booking['accommodation_price'],$rate,$count);
    $booking['paid_amount']=convertGelTo($booking['paid_amount'],$rate,$count);
    $booking['services_price']=convertGelTo($booking['services_price'],$rate,$count);
    $booking['services_paid_amount']=convertGelTo($booking['services_paid_amount'],$rate,$count);

    $breack++;
    $booking_total_price = 0;
    $booking_total_paid = 0;
    $section->addTextBreak(1,array('size'=>'2'));
    $table = $section->addTable(array('width' => 100*50, 'unit' => 'pct','borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80,'exactHeight'));
    $table->addRow(320,$rowStyle);
    $table->addCell(null, array('gridSpan'=>2,'bgColor'=>'999999'))->addText($TEXT['invoice']['booking_info'], $fontStyle);
    $table->addRow(320,$rowStyle);
    $table->addCell(null, $styleCell)->addText('ID', $fontStyle);
    $table->addCell(null, array('align'=>'right'))->addText($booking['id'], $fontStyle);
    $table->addRow(320,$rowStyle);
    $table->addCell(null, $styleCell)->addText($TEXT['invoice']['check_in'].' / '.$TEXT['invoice']['check_out'], $fontStyle);
    $table->addCell(null, array('align'=>'right'))->addText($booking['check_in'].' / '.$booking['check_out'], $fontStyle);
    $table->addRow(320,$rowStyle);
    $table->addCell(null, $styleCell)->addText($TEXT['invoice']['room'], $fontStyle);
    $table->addCell(null, array('align'=>'right'))->addText($roomTypesByID[$booking['room_id']]['title'], $fontStyle);
    $table->addRow(320,$rowStyle);
    $table->addCell(null, $styleCell)->addText($TEXT['invoice']['food_type'], $fontStyle);
    $table->addCell(null, array('align'=>'right'))->addText($booking['food_type'], $fontStyle);
    $table->addRow(320,$rowStyle);
    $table->addCell(null, $styleCell)->addText($TEXT['invoice']['guests'], $fontStyle);
    $table->addCell(null, array('align'=>'right'))->addText($booking['comment'], $fontStyle);
    $section->addTextBreak(1,array('size'=>'2'));
    $table = $section->addTable(array('width' => 100*50, 'unit' => 'pct','borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80,'exactHeight'));
    $table->addRow(320,$rowStyle);
    $table->addCell(null, array('bgColor'=>'999999'))->addText($TEXT['invoice']['price_calculation'], $fontStyle);
    $table->addCell(null, array('bgColor'=>'999999'))->addText("ფასი (".$opt.")", $fontStyle);



    if(isset($invoices[$booking['id']]['a'])&&$invoices[$booking['id']]['a']=="on") {
        $booking_total_price = floatval($booking['accommodation_price']);
        $booking_total_paid = floatval($booking['paid_amount']);
        $table->addRow(320,$rowStyle);
        $table->addCell(null, $styleCell)->addText($TEXT['invoice']['total_accommodation_price'], $fontStyle);
        $table->addCell(null, array('align'=>'right'))->addText($booking['accommodation_price'], $fontStyle);
        if($guest['tax']==1){
            $table->addRow(320,$rowStyle);
            $table->addCell(null, $styleCell)->addText($TEXT['invoice']['tax_price'], $fontStyle);
            $table->addCell(null, array('align'=>'right'))->addText(number_format((((floatval($booking['accommodation_price']))/118)*18),2,"."," "), $fontStyle);
        }else{
            $table->addRow(320,$rowStyle);
            $table->addCell(null, $styleCell)->addText($TEXT['invoice']['tax_price'], $fontStyle);
            $table->addCell(null, array('align'=>'right'))->addText('0', $fontStyle);
        }
    }
    if(isset($invoices[$booking['id']]['s'])&&$invoices[$booking['id']]['s']=="on") {
        $booking_total_price += floatval($booking['services_price']);
        $booking_total_paid += floatval($booking['services_paid_amount']);
        $table->addRow(320,$rowStyle);
        $table->addCell(null, $styleCell)->addText($TEXT['invoice']['services_total_price'], $fontStyle);
        $table->addCell(null, array('align'=>'right'))->addText($booking['services_price'], $fontStyle);

    }
    if(isset($invoices[$booking['id']]['a']) && $invoices[$booking['id']]['a']=="on" && isset($invoices[$booking['id']]['s']) && $invoices[$booking['id']]['s']=="on"){
        $table->addRow(320,$rowStyle);
        $table->addCell(null, $styleCell)->addText($TEXT['invoice']['total_price'], $fontStyle);
        $table->addCell(null, array('align'=>'right'))->addText($booking_total_price, $fontStyle);
    }
    $table->addRow(320,$rowStyle);
    $table->addCell(null, $styleCell)->addText($TEXT['invoice']['the_amount_paid'], $fontStyle);
    $table->addCell(null, array('align'=>'right'))->addText($booking_total_paid, $fontStyle);
    $table->addRow(320,$rowStyle);
    $table->addCell(null, $styleCell)->addText($TEXT['invoice']['amount_due'], $fontStyle);
    $table->addCell(null, array('align'=>'right'))->addText(($booking_total_price-$booking_total_paid), $fontStyle);

    $total_price+=($booking_total_price-$booking_total_paid);
}

$section->addTextBreak(1,array('size'=>'2'));
$table = $section->addTable(array('width' => 100*50, 'unit' => 'pct','borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80));
$table->addRow(320,$rowStyle);
$table->addCell(null, array('gridSpan'=>4,'bgColor'=>'999999'))->addText($TEXT['invoice']['payment_method'], $fontStyle);
$table->addRow(320,$rowStyle);
$table->addCell(null, $styleCell)->addText($TEXT['invoice']['by_cash'], $fontStyle);
$table->addCell(null, $styleCell)->addText($TEXT['invoice']['transfer'], $fontStyle);
$table->addCell(null, $styleCell)->addText($TEXT['invoice']['cr_card'], $fontStyle);
$table->addCell(null, $styleCell)->addText($TEXT['invoice']['exchange'], $fontStyle);
$table->addRow(250,$rowStyle);
$table->addCell(null, $styleCell)->addText('-', $fontStyle);
$table->addCell(null, $styleCell)->addText('-', $fontStyle);
$table->addCell(null, $styleCell)->addText('-', $fontStyle);
$table->addCell(null, $styleCell)->addText('-', $fontStyle);
$section->addTextBreak(1,array('size'=>'2'));


$table = $section->addTable(array('width' => 100*50, 'unit' => 'pct','borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80));
$table->addRow(320,$rowStyle);
$table->addCell(null, array('gridSpan'=>3,'bgColor'=>'999999'))->addText($TEXT['invoice']['signatures'], $fontStyle);
$table->addRow(320,$rowStyle);
$table->addCell(null, $styleCell)->addText($TEXT['invoice']['handed'], $fontStyle);
$table->addCell(null, $styleCell)->addText($TEXT['invoice']['st'], $fontStyle);
$table->addCell(null, $styleCell)->addText($TEXT['invoice']['received'], $fontStyle);
$table->addRow();
$table->addCell(1700, $styleCell)->addImage('../uploads/'.$hotel_settings['signature'],$fontStyle);
$table->addCell(1000, $styleCell)->addImage('../uploads/'.$hotel_settings['stamp'],$fontStyle);
$table->addCell(2300, $styleCell)->addText('', $fontStyle);



write($phpWord, $invoice_number, $writers);
