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
<table style="width:100%;">
  <tr>

        <td style="width:50%;">
      <table  border="1" style="width:100%;float:left; border-collapse: collapse;" cellpadding="2" cellspacing="0">
          <tr>
              <td bgcolor="#ccc" style="color:#00000; " colspan="2"><?= $TEXT['invoice']['com_info']?></td>
          </tr>
      	<tr>
      		<td class="tdrow1"><?= $TEXT['invoice']['guest']?></td>
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
      </td>

    <?php if (($TMPL_guest[type] != 'non-corporate') || $TMPL_guest['id'] != $TMPL_Rguest['id'] ): ?>
      <td>
        <table  border="1" style="width:100%;float:left; border-collapse: collapse;" cellpadding="2" cellspacing="0">
            <tr>
                <td bgcolor="#ccc" style="color:#00000; " colspan="2"><?= $TEXT['invoice']['guest_info']?></td>
            </tr>
        	<tr>
        		<td class="tdrow1"><?= $TEXT['invoice']['guest']?></td>
        		<td class="tdrow2"><span class="text_black"><?=$TMPL_Rguest['first_name']?> <?=$TMPL_Rguest['last_name']?></span></td>
        	</tr>
            <? if($TMPL_Rguest['id_number']!=''){?>
            <tr>
                <td class="tdrow1">ID</td>
                <td class="tdrow2"><span class="text_black"><?=$TMPL_Rguest['id_number']?></span></td>
            </tr>
            <? } ?>
            <tr>
                <td class="tdrow1"><?= $TEXT['invoice']['address']?></td>
                <td class="tdrow2"><span class="text_black"><?=$TMPL_Rguest['address']?></span></td>
            </tr>
            <tr>
                <td class="tdrow1"><?= $TEXT['invoice']['email']?></td>
                <td class="tdrow2"><span class="text_black"><?=$TMPL_Rguest['email']?></span></td>
            </tr>
            <tr>
                <td class="tdrow1"><?= $TEXT['invoice']['phone']?></td>
                <td class="tdrow2"><span class="text_black"><?=$TMPL_Rguest['telephone']?></span></td>
            </tr>
            <tr>
                <td class="tdrow1">TAX</td>
                <td class="tdrow2"><span class="text_black"><?=($TMPL_Rguest['tax']==0)?"TAX FREE":"TAX INCLUDED"?></span></td>
            </tr>
        </table>
      </td>
    <?php endif; ?>
  </tr>
</table>

<table style="width:100%;">
  <tr>
    <td style="width:50%;">
      <table border="1" style="width:100%; border:solid #000 1px;  border-collapse: collapse;    height: 160px; " cellpadding="2" cellspacing="0">
          <tr>
              <td bgcolor="#ccc" style="color:#000; " colspan="2"><?= $TEXT['invoice']['booking_info']?></td>
          </tr>
          <tr>
              <td class="tdrow1"><?= $TEXT['booking_list']['booking_id']?> </td>
              <td class="tdrow2"><span class="text_black"><?=$TMPL_booking['id']?> </span></td>
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
              <td class="tdrow1"><?= $TEXT['invoice']['food_type']?></td>
              <td class="tdrow2"><span class="text_black"><?=$TMPL_food?></span></td>
          </tr>
          <tr>
              <td class="tdrow1"><?= $TEXT['invoice']['comment']?></td>
              <td class="tdrow2"><span class="text_black"><?=$TMPL_booking['comment']?></span></td>
          </tr>
      </table>
    </td>
    <?php if (!empty($TMPL_Fguest)): ?>
      <td style="width:50%;">
        <table border="1" style="width:100%; border:solid #000 1px;  border-collapse: collapse;    height: 160px; " cellpadding="2" cellspacing="0">
            <tr>
                <td bgcolor="#ccc" style="color:#000; " colspan="2"><?= $TEXT['invoice']['guests_info']?></td>
            </tr>
            <tr>
                <td class="tdrow1">სახელი </td>
                <td class="tdrow2"><span class="text_black"><?=$TMPL_Fguest[0]['first_name']." ".$TMPL_Fguest[0]['last_name']?> </span></td>
            </tr>
            <?php $count=array(1,2,3,4,5); foreach ($count as $value): ?>
              <tr>
                  <td class="tdrow1"> </td>
                  <td class="tdrow2"><span class="text_black">
                    <?php if (isset($TMPL_Fguest[$value])){ ?>
                        <?=$TMPL_Fguest[$value]['first_name']." ".$TMPL_Fguest[$value]['last_name']?>
                    <?php }else{
                        echo "-";
                    }?>

                  </span></td>
              </tr>
            <?php endforeach; ?>
        </table>
      </td>
    <?php endif; ?>
  </tr>
</table>

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
                    $daily_general_price=$TMPL_booking_days[$i]['price'];
                    $total_accommodation_price+=$daily_general_price;
                    $total_price+=$daily_general_price;
                    ?>
                    <td nowrap align="right"  width="30px" class="dslr"><?=$daily_general_price?></td>
                </tr>
                <? for($j=0; $j<count($TMPL_booking_daily_services); $j++){
                    if($TMPL_booking_daily_services[$j]['booking_daily_id']==$TMPL_booking_days[$i]['id']){
                        if($TMPL_booking_daily_services[$j]['service_type_id']==2||$TMPL_booking_daily_services[$j]['service_type_id']==9){
                        $total_price+=$TMPL_booking_daily_services[$j]['service_price'];
                        $total_daily_services_price+=$TMPL_booking_daily_services[$j]['service_price'];
                        ?>
                        <tr>
                            <td nowrap><?=$TMPL_booking_daily_services[$j]['service_title']?></td>
                            <!--td><div style="height:16px; overflow: hidden;">------------------------</div></td-->
                            <td nowrap align="right" width="30px" class="dslr"><?=$TMPL_booking_daily_services[$j]['service_price']?></td>
                        </tr>
                    <?}}}?>
            </table>
		</td>
	</tr>
<?}?>
</table>
<br>
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
        <td bgcolor="#ccc" style="color:#000;" colspan="6"><?= $TEXT['invoice']['payment_method']?></td>
    </tr>
    <tr>
        <td align="center"><?= $TEXT['invoice']['by_cash']?></td>
        <td align="center"><?= $TEXT['invoice']['transfer']?></td>
        <td align="center"><?= $TEXT['invoice']['cr_card']?></td>
        <td align="center"><?= $TEXT['invoice']['exchange']?></td>
        <td align="center"><?= $TEXT['invoice']['balance']?></td>
        <td align="center"><?= $TEXT['invoice']['pos']?></td>
    </tr>
    <tr>
        <td align="center"><?=($tr[1])?$tr[1]:'-'?></td>
        <td align="center"><?=($tr[2])?$tr[2]:'-'?></td>
        <td align="center"><?=($tr[3])?$tr[3]:'-'?></td>
        <td align="center"><?=($tr[4])?$tr[4]:'-'?></td>
        <td align="center"><?=($tr[5])?$tr[5]:'-'?></td>
        <td align="center"><?=($tr[6])?$tr[6]:'-'?></td>
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
