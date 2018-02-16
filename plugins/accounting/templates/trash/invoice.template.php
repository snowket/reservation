<style>


</style>
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
        <td width="198" ><img src="images/logo.png"></td>
        <td align="right" valign="top">
            <H2>INVOICE N: <?=$TMPL_booking['id']?></H2>
            <H3><?=date("d-m-Y")?></H3>
        </td>
    </tr>
</table>
<br>
<? $total_price=0; ?>
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
<table border="1" style="width:100%; border:solid #00000 1px;  border-collapse: collapse; " cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="#ccc" style="color:#000; " colspan="2"><?= $TEXT['invoice']['booking_info']?></td>
    </tr>
    <tr>
        <td class="tdrow1"><?= $TEXT['invoice']['check_in']?> / <?= $TEXT['invoice']['check_out']?></td>
        <td class="tdrow2"><span class="text_black"><?=$TMPL_booking['check_in']?> / <?=$TMPL_booking['check_out']?></span></td>
    </tr>
    <tr>
        <td class="tdrow1"><?= $TEXT['invoice']['guests']?></td>
        <td class="tdrow2"><span class="text_black"><?=$TMPL_booking['comment']?></span></td>
    </tr>
</table>
<br>

<table border="1" style="width:100%; border-collapse: collapse;" cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="#ccc" style="color:#000; " colspan="2"><?= $TEXT['invoice']['booking_deily_info']?></td>
    </tr>
<?
$total_accommodation_price=0;
$total_daily_services_price=0;
$tax_free_discount=0;
for($i=0; $i<count($TMPL_booking_days); $i++){?>
	<tr>
		<td width="100px" height="20" valign="top" align="center">
			<span><b><?=$TMPL_booking_days[$i]['date']?></b></span>
		</td>
		<td  valign="top">
            <table width="100%" border="1" cellspacing="0" cellpadding="2" style=" border-collapse: collapse;">
                <tr>
                    <td nowrap><b><?= $TEXT['invoice']['daily_accomodation_price']?></b></td>
                    <?
                    $price=$TMPL_booking_days[$i]['price'];
                    $discount=$TMPL_booking_days[$i]['discount'];
                    $daily_general_price=$price-$price/100*$discount;
                    $total_accommodation_price+=$daily_general_price;
                    $total_price+=$daily_general_price;
                    ?>
                    <!--td>
                        <div style="height:16px; overflow: hidden; border:solid red 1px">------------------------</div>
                    </td-->
                    <td nowrap align="right"  width="30px"><?=$daily_general_price?></td>
                </tr>
                <? for($j=0; $j<count($TMPL_booking_daily_services); $j++){
                    if($TMPL_booking_daily_services[$j]['booking_daily_id']==$TMPL_booking_days[$i]['id']){
                        $total_price+=$TMPL_booking_daily_services[$j]['service_price'];
                        $total_daily_services_price+=$TMPL_booking_daily_services[$j]['service_price'];
                        ?>
                        <tr>
                            <td nowrap><?=$TMPL_booking_daily_services[$j]['service_title']?></td>
                            <!--td><div style="height:16px; overflow: hidden;">------------------------</div></td-->
                            <td nowrap align="right" width="30px"><?=$TMPL_booking_daily_services[$j]['service_price']?></td>
                        </tr>
                    <?}}?>

            </table>
		</td>
	</tr>
<?}?>
</table>
<br>
<table width="100%" border="1" style="width:100%; border-collapse: collapse;" cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="#ccc" style="color:#000; " colspan="2"><?= $TEXT['invoice']['price_calculation']?></td>
    </tr>
    <tr>
        <td><?= $TEXT['invoice']['total_accommodation_price']?></td>
        <td align="right"><?=$total_accommodation_price?></td>
    </tr>
    <tr>
        <td><?= $TEXT['invoice']['services_total_price']?></td>
        <td align="right"><?=$total_daily_services_price?></td>
    </tr>
    <?if($TMPL_guest['tax']==0){       ?>
        <tr>
            <td nowrap><?= $TEXT['invoice']['tax_free_discount']?></td>
            <?
            $tax_free_discount=number_format($total_accommodation_price/118*18, 2);
            ?>
            <td nowrap align="right" width="30px">-<?=$tax_free_discount?>
            </td>
        </tr>
    <? }?>
    <tr>
        <td><?= $TEXT['invoice']['individual_discount']?></td>
        <td align="right">-<?=$TMPL_booking['ind_discount']?></td>
    </tr>
    <tr>
        <td style="border-top:dashed black 1px"><?= $TEXT['invoice']['total_price']?></td>
        <? $total_price-=$TMPL_booking['ind_discount'];
        $total_price-=$tax_free_discount;
        ?>
        <td style="border-top:dashed black 1px" align="right"><?=$total_price?></td>
    </tr>
    <tr>
        <td><?= $TEXT['invoice']['the_amount_paid']?></td>
        <td align="right"><?=$TMPL_booking['paid_amount']?></td>
    </tr>
    <tr >
        <td style="border-top:dashed black 1px"><b><?= $TEXT['invoice']['amount_due']?></b></td>
        <td  style="border-top:dashed black 1px" align="right"><b><?=($total_price-$TMPL_booking['paid_amount'])?></b></td>
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
        <td align="center">X</td>
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
        <td align="center" height="100px"></td>
        <td align="center" height="100px"></td>
        <td align="center" height="100px"></td>
    </tr>
</table>