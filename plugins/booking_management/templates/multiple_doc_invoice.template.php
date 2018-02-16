<table border="0" width="100%">
    <tr>
        <td>
            <?=$TMPL_settings['ltd']['value']?><br>
            <?=$TMPL_settings['address']['value']?><br>
            <?=$TMPL_settings['identification_code']['value']?><br>
            <?=$TMPL_settings['bank']['value']?><br>
            <?=$TMPL_settings['code_of_bank']['value']?><br>
            <?=$TMPL_settings['account']['value']?><br>
        </td>
        <td width="198" ><img src="../uploads/<?=$TMPL_hotel_settings['logo']?>"></td>
        <td align="right" valign="top">
            <H2>INVOICE N: <? foreach($TMPL_invoices AS $k=>$invoice){echo $k.", ";}?></H2>
            <H3><?=date("d-m-Y")?></H3>
        </td>
    </tr>
</table>
<br>
<table  border="1" style="width:100%; border-collapse: collapse;" cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="#ccc" style="color:#00000; " colspan="2"><?= $TEXT['invoice']['guest_info']?></td>
    </tr>
	<tr>
		<td class="tdrow1"><?= $TEXT['invoice']['guest_info']?></td>
		<td class="tdrow2"><span class="text_black"><?=$TMPL_guest['first_name']?> <?=$TMPL_guest['last_name']?></span></td>
	</tr>
    <tr>
        <td class="tdrow1">ID</td>
        <td class="tdrow2"><span class="text_black"><?=$TMPL_guest['id_number']?></span></td>
    </tr>
    <tr>
        <td class="tdrow1"><?= $TEXT['invoice']['address']?></td>
        <td class="tdrow2"><span class="text_black"><?=$TMPL_guest['address']?></span></td>
    </tr>
    <tr>
        <td class="tdrow1"><?= $TEXT['invoice']['email']?></td>
        <td class="tdrow2"><span class="text_black"><?=$TMPL_guest['email']?></span></td>
    </tr>
    <tr>
        <td class="tdrow1"><?= $TEXT['invoice']['phone']?></td>
        <td class="tdrow2"><span class="text_black"><?=$TMPL_guest['telephone']?></span></td>
    </tr>
    <tr>
        <td class="tdrow1">TAX</td>
        <td class="tdrow2"><span class="text_black"><?=($TMPL_guest['tax']==0)?"TAX FREE":"TAX INCLUDED"?></span></td>
    </tr>
</table>
<br>
<? $total_price=0; ?>
<? foreach($TMPL_bookings AS $booking){
    $booking_total_price=0;
    $booking_total_paid=0?>
<table border="1" style="width:100%; border:solid #00000 1px;  border-collapse: collapse; " cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="#ccc" style="color:#000; " colspan="2"><?= $TEXT['invoice']['booking_info']?></td>
    </tr>
    <tr>
        <td class="tdrow1">ID</td>
        <td class="tdrow2" style="width: 150px;" align="right"><span class="text_black"><?=$booking['id']?></span></td>
    </tr>
    <tr>
        <td class="tdrow1"><?= $TEXT['invoice']['check_in']?> / <?= $TEXT['invoice']['check_out']?></td>
        <td class="tdrow2" align="right"><span class="text_black"><?=$booking['check_in']?> / <?=$booking['check_out']?></span></td>
    </tr>
    <tr>
        <td class="tdrow1" colspan="2"><?= $TEXT['invoice']['guests']?>: <span class="text_black"><?=$booking['comment']?></span></td>
    </tr>
    <tr>
        <td bgcolor="#ccc" style="color:#000; " colspan="2"><?= $TEXT['invoice']['price_calculation']?></td>
    </tr>
    <?if($TMPL_invoices[$booking['id']]['a']=="on"){
        $booking_total_price=floatval($booking['accommodation_price']);
        $booking_total_paid=floatval($booking['paid_amount']); ?>
    <tr>
        <td><?= $TEXT['invoice']['total_accommodation_price']?></td>
        <td align="right"><?=$booking['accommodation_price']?></td>
    </tr>
        <? if($TMPL_hotel_settings['is_taxable']=='yes'){?>
            <? if($TMPL_guest['tax']==1){?>
                <tr>
                    <td><?= $TEXT['invoice']['tax_price']?>  </td>
                    <td align="right"><?=number_format((((floatval($booking['accommodation_price']))/118)*18),2,",",".")?></td>
                </tr>
            <?}else{?>
                <tr>
                    <td><?= $TEXT['invoice']['tax_price']?> </td>
                    <td align="right">0</td>
                </tr>
            <?}?>
        <?}?>
    <?}?>
    <?if($TMPL_invoices[$booking['id']]['s']=="on"){
        $booking_total_price+=floatval($booking['services_price']);
        $booking_total_paid+=floatval($booking['services_paid_amount']);?>
    <tr>
        <td><?= $TEXT['invoice']['services_total_price']?></td>
        <td align="right"><?=$booking['services_price']?></td>
    </tr>
    <?}?>
    <?if($TMPL_invoices[$booking['id']]['a']=="on" && $TMPL_invoices[$booking['id']]['s']=="on"){?>
    <tr>
        <td style="border-top:dashed black 1px"><?= $TEXT['invoice']['total_price']?></td>
        <td style="border-top:dashed black 1px" align="right"><?=$booking_total_price?></td>
    </tr>
    <?}?>
    <tr>
        <td><?= $TEXT['invoice']['the_amount_paid']?></td>
        <td align="right"><?=$booking_total_paid?></td>
    </tr>
    <tr>
        <td style="border-top:dashed black 1px"><b><?= $TEXT['invoice']['amount_due']?></b></td>
        <td  style="border-top:dashed black 1px" align="right">
            <b><?=($booking_total_price-$booking_total_paid)?></b>
        </td>
    </tr>
</table><br>
<?
    $total_price+=($booking_total_price-$booking_total_paid);
}
?>

<table width="100%" border="1" style="width:100%; border-collapse: collapse;" cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="#ccc" style="color:#000;" colspan="2"><?= $TEXT['invoice']['amount_due']?></td>
    </tr>
    <tr>
        <td align="left"><?= $TEXT['invoice']['amount_due']?></td>
        <td align="right" style="width: 150px;"><?=$total_price?></td>
    </tr>
</table>
<br>
<table width="100%" border="1" style="width:100%; border-collapse: collapse;" cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="#ccc" style="color:#000;" colspan="4"><?= $TEXT['invoice']['payment_method']?></td>
    </tr>
    <tr>
        <td align="center"><?= $TEXT['invoice']['by_cash']?></td>
        <td align="center"><?= $TEXT['invoice']['transfer']?></td>
        <td align="center"><?= $TEXT['invoice']['cr_card']?></td>
        <td align="center"><?= $TEXT['invoice']['exchange']?></td>
    </tr>
    <tr>
        <td align="center">-</td>
        <td align="center">-</td>
        <td align="center">-</td>
        <td align="center">-</td>
    </tr>
</table>
<br>
<table width="100%" border="1" style="width:100%; border-collapse: collapse;" cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="#ccc" style="color:#000;" colspan="3"><?= $TEXT['invoice']['signatures']?></td>
    </tr>
    <tr>
        <td align="center" width="33%"><?= $TEXT['invoice']['handed']?></td>
        <td align="center" width="33%"><?= $TEXT['invoice']['st']?></td>
        <td align="center" width="33%"><?= $TEXT['invoice']['received']?></td>
    </tr>
    <tr>
        <td align="center" height="100px"><img src="../uploads/<?=$TMPL_hotel_settings['signature']?>"></td>
        <td align="center" height="100px"><img src="../uploads/<?=$TMPL_hotel_settings['stamp']?>"></td>
        <td align="center" height="100px"></td>
    </tr>
</table>

