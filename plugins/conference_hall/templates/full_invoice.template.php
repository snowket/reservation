<div id="invoice" style=" padding: 10px">
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
            <H2>INVOICE N: <?=$TMPL_invoice_number?></H2>
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
     <? if($TMPL_guest['id_number']!=''){?>
    <tr>
        <td class="tdrow1">ID</td>
        <td class="tdrow2"><span class="text_black"><?=$TMPL_guest['id_number']?></span></td>
    </tr>
    <? } ?>
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
        <td class="tdrow1"><?= $TEXT['invoice']['room']?></td>
        <td class="tdrow2">
        <span class="text_black"><?=($TMPL_settings['rni']['value']==1)?$TMPL_room_type[$TMPL_booking['room_id']]['name']." ".$TMPL_room_type[$TMPL_booking['room_id']]['title']:
                $TMPL_room_type[$TMPL_booking['room_id']]['title']?></span>
        </td>
    </tr>
    <tr>
        <td class="tdrow1"><?= $TEXT['invoice']['food_type']?></td>
        <td class="tdrow2"><span class="text_black"><?=$TMPL_food?></span></td>
    </tr>
    <tr>
        <td class="tdrow1"><?= $TEXT['invoice']['comment']?></td>
        <td class="tdrow2"><span class="text_black"><?=$TMPL_booking['comment']?></span></td>
    </tr>
</table>
<br>
<table border="1" style="width:100%; border-collapse: collapse;" cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="#ccc" style="color:#000; " colspan="2"><?= $TEXT['invoice']['extra_services_deily_info']?></td>
    </tr>
    <?php foreach ($founds['service_info'] as $key => $value): ?>
      <tr>
        <td>
          <?=$value['info'][0]['name']?>
        </td>
        <td>
          <?=$value['count'][1]?>
        </td>
      </tr>
    <?php endforeach; ?>
</table>

<table width="100%" border="1" style="width:100%; border-collapse: collapse;" cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="#ccc" style="color:#000; " colspan="1"><?= $TEXT['invoice']['price_calculation']?></td>
        <td bgcolor="#ccc" style="color:#000" class="pp_changed" align="right"><?=$TEXT['booking_modal']['price']?></td>
    </tr>
    <tr>
        <td><?= $TEXT['invoice']['total_accommodation_price']?></td>
        <td align="right" class="dslr"><?=$founds['price']?></td>
    </tr>
    <tr>
        <td><?= $TEXT['invoice']['services_total_price']?></td>
        <td align="right" class="dslr"><?=$founds['service_price']?></td>
    </tr>
    <? if($TMPL_guest['tax']==1){?>
        <tr>
            <td><?= $TEXT['invoice']['tax_price']?>  </td>
            <td align="right" class="dslr"><?=number_format(((($founds['price']+$founds['service_price'])/118)*18),2,".","")?></td>
        </tr>
    <?}else{?>
        <tr>
            <td><?= $TEXT['invoice']['tax_price']?> </td>
            <td align="right" class="dslr">0</td>
        </tr>
    <?}?>
        <td style="border-top:dashed black 1px" class="pp_changed_exim"><b><?= $TEXT['invoice']['amount_due']?></b></td>
        <td  style="border-top:dashed black 1px" align="right">
            <div id="total_amount_due" class="dslr" style="font-weight: bold"><?=($founds['price']+$founds['service_price'])?></div>
        </td>
    </tr>
</table>
<br>
<!--note table width="100%" border="1" style="width:100%; border-collapse: collapse;" cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="#ccc" style="color:#000;" colspan="4"><?= $TEXT['invoice']['note']?></td>
    </tr>
    <tr>
        <td colspan="4">[note]</td>
    </tr>
</table>
<br note-->

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
</div>
