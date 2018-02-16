<style>
  td.tdrow1 {
    width: 662px;
  }
</style>
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
		<td class="tdrow2">
            <span class="text_black"><?=$TMPL_guest['first_name']?> <?=$TMPL_guest['last_name']?></span>
        </td>
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
  <?php
  $total_accommodation_price=0;
  $total_daily_services_price=0;
  $tax_free_discount=0;
   foreach ($TMPL_booking as $key => $book): ?>
  <table border="1" style="width:100%; border:solid #00000 1px;  border-collapse: collapse; " cellpadding="2" cellspacing="0">
      <tr>
          <td bgcolor="#ccc" style="color:#000; " colspan="2"><?= $TEXT['invoice']['booking_info']?></td>
      </tr>
      <tr>
          <td class="tdrow1"><?= $TEXT['invoice']['check_in']?> / <?= $TEXT['invoice']['check_out']?></td>
          <td class="tdrow2"><span class="text_black"><?=$book['check_in']?> / <?=$book['check_out']?></span></td>
      </tr>
      <tr>
          <td class="tdrow1"><?= $TEXT['invoice']['room']?></td>
          <td class="tdrow2">
          <span class="text_black"><?=($TMPL_settings['rni']['value']==1)?$TMPL_room_type[$book['room_id']][$book['room_id']]['name']." ".$TMPL_room_type[$book['room_id']][$book['room_id']]['title']:
                  $TMPL_room_type[$book['room_id']][$book['room_id']]['title']?></span>
          </td>
      </tr>
      <tr>
          <td class="tdrow1"><?= $TEXT['invoice']['food_type']?></td>
          <td class="tdrow2"><span class="text_black"><?=$TMPL_food[$book['dbl_res_id']]['title']?></span></td>
      </tr>
      <tr>
          <td class="tdrow1">ნომრის ღირებულება პერიოდზე</td>
           <?
              $total_tmp=0;
              for($i=0; $i<count($TMPL_booking_days[$book['id']]); $i++){
                $daily_general_price=$TMPL_booking_days[$book['id']][$i]['price'];
                $total_tmp+=$daily_general_price;
                $total_accommodation_price+=$daily_general_price;
                $total_price+=$daily_general_price;
               for($j=0; $j<count($TMPL_booking_daily_services[$book['id']]); $j++){
                if($TMPL_booking_daily_services[$book['id']][$j]['booking_daily_id']==$TMPL_booking_days[$book['id']][$i]['id']){
                    if($TMPL_booking_daily_services[$book['id']][$j]['service_type_id']==2||$TMPL_booking_daily_services[$book['id']][$j]['service_type_id']==9){
                    $total_price+=$TMPL_booking_daily_services[$book['id']][$j]['service_price'];
                    $total_tmp+=$TMPL_booking_daily_services[$book['id']][$j]['service_price'];
                    $total_daily_services_price+=$TMPL_booking_daily_services[$book['id']][$j]['service_price'];
               }
             }
           }
          }?>
          <td class="tdrow2"><span class="text_black dslr" ><?=$total_tmp?></span><span class="pp_changed"></span></td>
      </tr>
      <tr>
          <td class="tdrow1">კომენტარი</td>
          <td class="tdrow2"><span class="text_black"><?=$book['comment']?></span> </td>
      </tr>
        
  </table>
<?php endforeach; ?>
<table width="100%" border="1" style="width:100%; border-collapse: collapse;" cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="#ccc" style="color:#000; "><?= $TEXT['invoice']['price_calculation']?></td>
        <td bgcolor="#ccc" style="color:#000" class="pp_changed" align="right"><?=$TEXT['booking_modal']['price']?></td>
    </tr>
    <tr>
        <td><?= $TEXT['invoice']['total_accommodation_price']?></td>
        <td align="right" class="dslr"><?=$total_accommodation_price?></td>
    </tr>
    <? if($TMPL_hotel_settings['is_taxable']=='yes'){?>
        <? if($TMPL_guest['tax']==1){?>
        <tr>
            <td><?= $TEXT['invoice']['tax_price']?>  </td>
            <td align="right" class="dslr"><?=number_format((($total_accommodation_price/118)*18),2,",",".")?></td>
        </tr>
        <?}else{?>
            <tr>
                <td><?= $TEXT['invoice']['tax_price']?> </td>
                <td align="right">0</td>
            </tr>
        <?}?>
    <?}?>
    <tr>
        <td><?= $TEXT['invoice']['accommodation_services_total_price']?></td>
        <td align="right" class="dslr"><?=$total_daily_services_price?></td>
    </tr>
    <tr>
        <td style="border-top:dashed black 1px"><?= $TEXT['invoice']['total_price']?></td>
        <? $total_price-=$TMPL_booking['ind_discount'];
        $total_price-=$tax_free_discount;
        ?>
        <td style="border-top:dashed black 1px" align="right" class="dslr"><?=$total_price?></td>
    </tr>
    <tr>
        <td><?= $TEXT['invoice']['the_amount_paid']?></td>
        <td align="right" class="dslr"><?=$TMPL_booking['paid_amount']?></td>
    </tr>
    <tr >
        <td style="border-top:dashed black 1px" class="pp_changed_exim"><b><?= $TEXT['invoice']['amount_due']?></b></td>
        <td  style="border-top:dashed black 1px" align="right">
            <div id="total_amount_due" class="dslr" style="font-weight: bold"><?=($total_price-$TMPL_booking['paid_amount'])?></div>
        </td>
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
</div>
