<style media="screen">
.hidden{
  display: none;
}
</style>

<? if(!empty($TMPL_errors)){?>
    <div style="border:solid red 2px;">
        <? foreach($TMPL_errors as $error){?>
            <div><?=$error?></div>
        <?}?>
    </div>
<?}?>
<? $total_price=0; ?>
<div style="width: 100%;">

</div>
<table width="100%">
    <tr>
        <td width="50%">
            <div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
                <div style="padding:2px; background:#3A82CC; color:#FFF">
                    <b><?=$TEXT['view']['booking_info']?></b>
                </div>
                <table border="0" style="width:100%; " cellpadding="1" cellspacing="0" class="text1">
                    <tr>
                        <td width="50%">
                            <table border="0" style="width:100%; " cellpadding="1" cellspacing="0" class="text1">
                                <tr>
                                    <td class="tdrow1"><?=$TEXT['view']['booking_id']?></td>
                                    <td class="tdrow2"><span class="text_black"><?=$TMPL_booking['id']?></span></td>
                                </tr>
                                <tr>
                                    <td class="tdrow1"><?=$TEXT['view']['room']?></td>
                                    <td class="tdrow2">
                                        <div class="<?=($TMPL_booking['check_out']<date('Y-m-d'))?'':'change_room_modal_trigger'?>" style="display: inline; padding:2px; border:solid #868686 1px; cursor: pointer; text-align: center">
                                            <?=$TMPL_room['name']?> floor(<?=$TMPL_room['floor']?>)
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tdrow1"><?=$TEXT['view']['affiliate']?></td>
                                    <td class="tdrow2">
                                        <?=$TMPL_all_guests[$TMPL_booking['affiliate_id']]['first_name']." ".$TMPL_all_guests[$TMPL_booking['affiliate_id']]['last_name']?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tdrow1"><?=$TEXT['view']['guests_count']?></td>
                                    <td class="tdrow2">
                                      <div class="change_guest_count_modal_trigger">
                                          <?=$TMPL_booking['adult_num']?>/<?=$TMPL_booking['child_num']?>
                                      </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tdrow1"><?=$TEXT['view']['food_type']?></td>
                                    <td class="tdrow2">
                                      <div class="change_food_modal_trigger">
                                          <?=$TMPL_food?>
                                      </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tdrow1"><?=$TEXT['view']['checkin_checkout']?></td>
                                    <td class="tdrow2">
                                        <div class="<?=($TMPL_booking['check_out']<date('Y-m-d'))?'':'change_checkin_checkout_modal_trigger'?>" style="display: inline; padding:2px; border:solid #868686 1px; cursor: pointer; text-align: center">
                                            <?=$TMPL_booking['check_in']?> - <?=$TMPL_booking['check_out']?>
                                        </div>
                                    </td>
                                </tr>
                                <?if($TMPL_booking['parent_id']>0){?>
                                <tr>
                                    <td class="tdrow1" style="color:red"><?=$TEXT['view']['has_parent']?></td>
                                    <td class="tdrow2">
                                        <div >
                                            <a href="index.php?m=booking_management&tab=booking_list&action=view&booking_id=<?=$TMPL_booking['parent_id']?>">
                                                <?=$TMPL_booking['parent_id']?>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?}?>
                                <?if($TMPL_booking['child_id']>0){?>
                                    <tr>
                                        <td class="tdrow1" style="color:red"><?=$TEXT['view']['has_child']?></td>
                                        <td class="tdrow2">
                                            <div >
                                                <a href="index.php?m=booking_management&tab=booking_list&action=view&booking_id=<?=$TMPL_booking['child_id']?>">
                                                    <?=$TMPL_booking['child_id']?>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?}?>
                                <tr>
                                    <td class="tdrow1"><?=$TEXT['view']['comment']?></td>
                                    <td class="tdrow2">
                                        <form method="post">
                                        <input type="hidden" name="action" value="update_booking_comment">
                                        <table width="100%">
                                            <tr>
                                                <td>
                                                    <textarea name="booking_comment" style="width:100%" class="formField3" id="booking_comment"><?=$TMPL_booking['comment']?></textarea>
                                                </td>
                                            </tr>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="submit" value="<?=$TEXT['view']['save']?>" class="formButton2" style="margin-top:6px; cursor:pointer; padding-left:4px; float: right; width:100px">
                                                </td>
                                            </tr>
                                        </table>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td width="50%">
        <div style="background:#FFF; border:solid #3A82CC 1px; width:100%; display: block; padding-bottom:28px">
            <div style="padding:2px; background:#3A82CC; color:#FFF">
                <b><?=$TEXT['foreign_guest']['title']?></b>
            </div>
            <table width="100%">
                <tr>
                    <td class="table-th"><?=$TEXT['foreign_guest']['id']?></td>
                    <td class="table-th"><?=$TEXT['foreign_guest']['id_number']?></td>
                    <td class="table-th"><?=$TEXT['foreign_guest']['first_name']?></td>
                    <td class="table-th"><?=$TEXT['foreign_guest']['last_name']?></td>
                    <td class="table-th"><?=$TEXT['foreign_guest']['comment']?></td>
                    <td class="table-th"><?=$TEXT['foreign_guest']['action']?></td>
                </tr>
                <?
                $foreign_guest_ids=explode('|',$TMPL_booking['foreign_guest_ids']);
                for($i=0; $i<count($foreign_guest_ids);$i++){
                if((int)$foreign_guest_ids[$i]==0){continue;}?>
                <tr>
                    <td class="tdrow2"><?=$TMPL_all_guests[$foreign_guest_ids[$i]]['id']?></td>
                    <td class="tdrow2"><?=$TMPL_all_guests[$foreign_guest_ids[$i]]['id_number']?></td>
                    <td class="tdrow2"><?=$TMPL_all_guests[$foreign_guest_ids[$i]]['first_name']?></td>
                    <td class="tdrow2"><?=$TMPL_all_guests[$foreign_guest_ids[$i]]['last_name']?></td>
                    <td class="tdrow2"><?=$TMPL_all_guests[$foreign_guest_ids[$i]]['comment']?></td>
                    <td class="tdrow2">
                        <a href="index.php?m=guests&tab=guests&filter_guest_id_number=<?=$TMPL_all_guests[$foreign_guest_ids[$i]]['id_number']?>"><img width="16" height="16" border="0" align="middle" src="./images/icos16/edit.gif" alt="edit"></a>
                        <a href="#" class="remove_foreign_guest_trigger" guest_id="<?=$foreign_guest_ids[$i]?>">
                            <img width="16" height="16" border="0" align="middle" src="./images/icos16/delete.gif" alt="delete">
                        </a>
                    </td>
                </tr>
                <?}?>
            </table>
            <div class="formButton2 guest_modal_trigger" data-action="editForiegnGuest" style="margin-top:6px; cursor:pointer; padding-left:4px; float: right; width:100px" ><?=$TEXT['foreign_guest']['add_guest']?></div>
        </div>
        </td>
    </tr>
</table>
<br>

<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
<table border="0" width="100%" cellpadding="1" cellspacing="0" >
    <tr>
        <td class="tdrow2"  style="background-color:#3a82cc; color:FFF; width:100px">&nbsp;&nbsp;&nbsp;</td>
        <td class="tdrow2"  style="background-color:#3a82cc; color:FFF; min-width: 45%"><?=$TEXT['view']['guest']?></td>
        <td class="tdrow2" >&nbsp;&nbsp;&nbsp;</td>
        <td class="tdrow2" colspan="2" style="background-color:#3a82cc; color:FFF; min-width: 45%"><?=$TEXT['view']['responsive_guest']?></td>
    </tr>
    <tr>
        <td class="tdrow2" >&nbsp;&nbsp;&nbsp;</td>
        <td class="tdrow2">
            <table border="0" style="width:100%; " cellpadding="1" cellspacing="0" class="text1">
                <tr>
                    <td class="tdrow1"><?=$TEXT['view']['guest']?></td>
                    <td class="tdrow2"><span class="text_black guest_modal_trigger" dest="guest" data-id="<?=$TMPL_guest['id']?>" data-action="editGuest"><?=$TMPL_guest['first_name']?> <?=$TMPL_guest['last_name']?></span></td>
                </tr>
                <tr>
                    <td class="tdrow1"><?=$TEXT['view']['tax']?></td>
                    <td class="tdrow2"><span class="text_black"><?=($TMPL_guest['tax']==0)?"TAX FREE":"TAX INCLUDED"?></span></td>
                </tr>
                <tr>
                    <td class="tdrow1"><?=$TEXT['view']['balance']?></td>
                    <td class="tdrow2"><span class="text_black"><?=$TMPL_guest['balance']?></span></td>
                </tr>
            </table>
        </td>
        <td class="tdrow2" >&nbsp;&nbsp;&nbsp;</td>
        <td class="tdrow2" colspan="2">
            <table border="0" style="width:100%; " cellpadding="1" cellspacing="0" class="text1">
                <tr>
                    <td class="tdrow1"><?=$TEXT['view']['responsive_guest']?></td>
                    <td class="tdrow2"><span class="text_black guest_modal_trigger" dest="guest" data-id="<?=$TMPL_responsive_guest['id']?>" data-action="changeResponsiveGuest">
                            <?=$TMPL_responsive_guest['first_name']?> <?=$TMPL_responsive_guest['last_name']?></span></td>
                </tr>
                <tr>
                    <td class="tdrow1"><?=$TEXT['view']['tax']?></td>
                    <td class="tdrow2"><span class="text_black"><?=($TMPL_responsive_guest['tax']==0)?"TAX FREE":"TAX INCLUDED"?></span></td>
                </tr>
                <tr>
                    <td class="tdrow1"><?=$TEXT['view']['balance']?></td>
                    <td class="tdrow2"><span class="text_black"><?=$TMPL_responsive_guest['balance']?></span></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="tdrow2" style="background-color:#3a82cc; color:FFF;"><?=$TEXT['view']['day']?></td>
        <td class="tdrow2" style="background-color:#3a82cc; color:FFF;"><?=$TEXT['view']['accommodation']?></td>
        <td class="tdrow2" >&nbsp;&nbsp;&nbsp;</td>
        <td class="tdrow2" style="background-color:#3a82cc; color:#FFFFFF;"><?=$TEXT['view']['extra_services']?></td>
        <td class="tdrow2" style="background-color:#3a82cc; color:FFF;"><?=$TEXT['view']['action']?></td>
    </tr>
<?
$total_accommodation_price=0;
$total_daily_services_price=0;
$tax_free_discount=0;
for($i=0; $i<count($TMPL_booking_days); $i++){
    $acc_price_without_services+=$TMPL_booking_days[$i]['price'];
    $total_accommodation_price+=$TMPL_booking_days[$i]['price'];
    $total_price+=$TMPL_booking_days[$i]['price'];
    ?>
	<tr >
		<td class="tdrow2" width="100px" valign="middle" align="center">
			<div class="text_black">
                <b><?=$TMPL_booking_days[$i]['date']?></b>
            </div>
		</td>
		<td class="tdrow2" height="20" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="encode">
                <tr>
                    <td nowrap><b><?=$TEXT['view']['accommodation']?></b></td>
                    <td><div style="height:16px; overflow: hidden;">----------------------------------------------------</div></td>
                    <td nowrap align="right"  width="30px">
                       <div id="daily_acc_price_<?=$TMPL_booking_days[$i]['id']?>" class="booking_daily_price" type="<?=$TMPL_booking_days[$i]['type']?>" price="<?=$TMPL_booking_days[$i]['price']?>" new_price="<?=$TMPL_booking_days[$i]['price']?>"><?=$TMPL_booking_days[$i]['price']?></div>
                    <td class="tdrow2">
                        <? if($TMPL_booking_days[$i]['date']>=date('Y-m-d')){?>
                            <div class="services_modal_trigger_bedroom" booking_daily_id="<?=$TMPL_booking_days[$i]['id']?>" style="padding:2px; border:solid #868686 1px;  cursor: pointer; text-align: center">
                                <b> + </b>
                            </div>
                        <?}?>
                    </td>
                    </td>
                </tr>
                <? for($j=0; $j<count($TMPL_booking_daily_services); $j++){
                    if($TMPL_booking_daily_services[$j]['booking_daily_id']==$TMPL_booking_days[$i]['id']){
                        //tu yoveldgiuri servisia an daxvedris servisi
                        if($TMPL_booking_daily_services[$j]['service_type_id']==2||$TMPL_booking_daily_services[$j]['service_type_id']==9){
                            //$total_price+=$TMPL_booking_daily_services[$j]['service_price'];
                            $total_accommodation_price+=$TMPL_booking_daily_services[$j]['service_price'];
                            ?>
                        <tr>
                            <td nowrap><?=$TMPL_booking_daily_services[$j]['service_title']?></td>
                            <td><div style="height:16px; overflow: hidden;">----------------------------------------------------</div></td>
                            <td nowrap align="right" width="30px"><?=$TMPL_booking_daily_services[$j]['service_price']?></td>
                            <td align="right">
                                <? if($TMPL_booking_days[$i]['date']>=date('Y-m-d')){?>
                                    <a onclick="return confirm('are you sure?')" href="index.php?m=booking_management&tab=booking_list&action=delete_daily_service&booking_id=<?=$TMPL_booking['id']?>&daily_service_id=<?=$TMPL_booking_daily_services[$j]['id']?>">
                                        <img width="16" height="16" border="0" align="middle" alt="delete" src="./images/icos16/delete.gif">
                                    </a>
                                <?}?>
                            </td>
                        </tr>

                <?}}}?>
			</table>
		</td>
        <td class="tdrow2">&nbsp;</td>
        <td class="tdrow2" height="20" valign="top">
            <table width="100%">
                <? $total_daily_services_price=0; for($j=0; $j<count($TMPL_booking_daily_services); $j++){

                    if($TMPL_booking_daily_services[$j]['booking_daily_id']==$TMPL_booking_days[$i]['id']){
                        if($TMPL_booking_daily_services[$j]['service_type_id']!=2&&$TMPL_booking_daily_services[$j]['service_type_id']!=9){
                            $total_price+=$TMPL_booking_daily_services[$j]['service_price'];
                            $total_daily_services_price+=$TMPL_booking_daily_services[$j]['service_price'];


                            $tmp=$TMPL_booking_daily_services[$j]['service_title'];
                            $price[$TMPL_booking_daily_services[$j]['service_type_id']][$TMPL_booking_days[$i]['date']]['price']+=$TMPL_booking_daily_services[$j]['service_price'];

                         ?>



                        <tr class=" servie_<?=$TMPL_booking_days[$i]['date']?> hidden">
                            <td nowrap><?=$TMPL_booking_daily_services[$j]['service_title']?></td>
                            <td><div style="height:16px; overflow: hidden;">----------------------------------------------------</div></td>
                            <td nowrap align="right" width="30px"><?=$TMPL_booking_daily_services[$j]['service_price']?></td>


                            <td align="right">
                            <? if($TMPL_booking_days[$i]['date']>=$_SESSION['server_date']){?>
                                <a onclick="return confirm('are you sure?')" href="index.php?m=booking_management&tab=booking_list&action=delete_daily_service&booking_id=<?=$TMPL_booking['id']?>&daily_service_id=<?=$TMPL_booking_daily_services[$j]['id']?>">
                                    <img width="16" height="16" border="0" align="middle" alt="delete" src="./images/icos16/delete.gif">
                                </a>
                            <?}?>
                            </td>
                        </tr>

                    <?}
                  }
                }?>
                <tr class="<?=$total_daily_services_price?'':'hidden'?>">
                  <td nowrap><a href="#" dds="<?=$TMPL_booking_days[$i]['date']?>" onclick="javascript:triggerServices(this)"> სერვისები</a></td>
                  <td><div style="height:16px; overflow: hidden;">----------------------------------------------------</div></td>
                  <td nowrap align="right" width="30px"><?=$total_daily_services_price?></td>
                </tr>

            </table>
        </td>

        <td class="tdrow2">
        <? if($TMPL_booking_days[$i]['date']>=date('Y-m-d')){?>
            <div class="services_modal_trigger" booking_daily_id="<?=$TMPL_booking_days[$i]['id']?>" style="padding:2px; border:solid #868686 1px;  cursor: pointer; text-align: center">
                <b> + </b>
            </div>
        <?}?>
        </td>

	</tr>
<?}?>
    <tr>
        <td class="tdrow2"></td>
        <td class="tdrow2">
            <table width="100%">
                <tr>
                    <td><?=$TEXT['view']['total_price']?> <?=($TMPL_guest['tax']==0)?'(TAX FREE)':'(TAX INCLUDED)';?></td>
                    <td align="right">
                        <div id="acc_price_trigger" without_services_price="<?=$acc_price_without_services?>" total_price="<?=$TMPL_booking['accommodation_price']?>" style="cursor:pointer; border:solid red 2px; text-align: center; display: block; height:21px; padding: 2px;">
                            <div id="acc_total_price">
                                <?=$TMPL_booking['accommodation_price']?>
                            </div>
                            <div id="price_editor" style="display: none">
                                <div style="float: left"><input id="price_editor_inp" type="number" value="<?=$TMPL_booking['accommodation_price']?>" min="0" style="width: 60px"></div>
                                <div id="save_changed_price" class="formButton2" style="height:17px; float: left; margin: 0 4px; padding: 0 4px">Save</div>
                                <div id="cancel_changed_price" class="formButton2" style="height:17px; float: left; padding: 0 4px">Cancel</div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><?=$TEXT['view']['amount_paid']?></td>
                    <td align="right">
                        <div id="acc_paid_amount" amount="<?=$TMPL_booking['paid_amount']?>">
                        <?=$TMPL_booking['paid_amount']?>
                        </div>
                    </td>
                </tr>
                <tr >
                    <td style="border-top:dashed black 1px"><?=$TEXT['view']['amount_due']?></td>
                    <td  style="border-top:dashed black 1px" align="right">
                        <? $accommodation_amount_due=($TMPL_booking['accommodation_price']-$TMPL_booking['paid_amount'])?>
                        <div id="acc_amount_due" style="font-weight: bold; color:<?=($accommodation_amount_due<0)?'red':'black'?>;" amount="<?=$accommodation_amount_due ?>">
                            <?=$accommodation_amount_due ?>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
        <td class="tdrow2">&nbsp;</td>
        <td class="tdrow2">
            <table width="100%">
                <tr>
                    <td><?=$TEXT['view']['total_price']?></td>
                    <td align="right"><?=$TMPL_booking['services_price']?></td>
                </tr>
                <tr>
                    <td><?=$TEXT['view']['amount_paid']?></td>
                    <td align="right"><?=$TMPL_booking['services_paid_amount']?></td>
                </tr>
                <tr >
                    <td style="border-top:dashed black 1px"><b><?=$TEXT['view']['amount_due']?></b></td>
                    <? $extra_services_amount_due=($TMPL_booking['services_price']-$TMPL_booking['services_paid_amount'])?>

                    <td  style="border-top:dashed black 1px" align="right">
                        <b style="color:<?=($extra_services_amount_due<0)?'red':'black'; ?>;"><?=$extra_services_amount_due ?></b>
                    </td>
                </tr>
            </table>

            </td>
        <td class="tdrow2"></td>
    </tr>
    <tr>
        <td class="tdrow2">
           <b><?=$TEXT['view']['transactions']?></b>
        </td>
        <td>
            <table border="1" width="100%">
                <? foreach($TMPL_booking_transactions as $transaction){
                    if($transaction['destination']=='accommodation'){?>
                        <tr bgcolor="<?=($transaction['amount']>0)?'#B3FFAF':'#FFAFAF'?>">
                            <td>
                                <?=($transaction['amount']>0)?$TEXT['view']['amount_in']:$TEXT['view']['amount_out']?>
                            </td>
                            <td><?=$TMPL_payment_methods[$transaction['payment_method_id']]['title']?></td>
                            <td><?=$transaction['start_date']?></td>
                            <td align="right"><?=$transaction['amount']?></td>
                        </tr>
                    <?}?>
                <?}?>
            </table>
        </td>
        <td class="tdrow2">&nbsp;</td>
        <td>
            <table border="1" width="100%" >
                <? foreach($TMPL_booking_transactions as $transaction){
                    if($transaction['destination']=='extra-service'){
                        ?>
                        <tr bgcolor="<?=($transaction['amount']>0)?'#B3FFAF':'#FFAFAF'?>">
                            <td>
                                <?=($transaction['amount']>0)?$TEXT['view']['amount_in']:$TEXT['view']['amount_out']?>
                            </td>
                            <td><?=$TMPL_payment_methods[$transaction['payment_method_id']]['title']?></td>
                            <td><?=$transaction['start_date']?></td>
                            <td align="right"><?=$transaction['amount']?></td>
                        </tr>
                    <?}?>
                <?}?>
            </table>
        </td>
        <tr>
        <td class="tdrow2"><?=$TEXT['view']['action']?></td>
        <td class="tdrow2">
            <form id="accommodation_pay_form"  method="post"  onsubmit="return confirm('Are you sure he/she paid '+$('.pay_class').val()+' ?');">
                <input type="hidden" name="destination" value="accommodation" />
                <table border="0" width="100%">
                    <? if($accommodation_amount_due>0){?>
                        <tr>
                            <td><b><?=$TEXT['view']['amount_in']?></b></td>
                            <td>
                                <input type="hidden" name="action" value="add_amount_paid" />
                                <input type="number" class="pay_class" name="amount_pay" value="0.01" min="0.01" step="0.01">
                            </td>
                            <td>
                                <select name="payment_method_id" id="payment_method_id" class="formField1" style="">
                                    <? foreach($TMPL_payment_methods as $payment_method){
                                        if($payment_method['id']==5){
                                            if($TMPL_guest['balance']!=0){
                                                echo '<option value="' . $payment_method['id'] . '" >' . $payment_method['title'] . '(' . $TMPL_guest['balance'] . ')</option>';
                                            }
                                        }elseif($payment_method['id']==3){
                                            if(!empty($TMPL_guestRegularPayments)){
                                                echo '<option value="'.$payment_method['id'].'" >'.$payment_method['title'].'</option>';
                                            }
                                        }else{
                                            echo '<option value="'.$payment_method['id'].'" >'.$payment_method['title'].'</option>';
                                        }
                                    }?>
                                </select>
                            </td>
                            <td>
                                <input type="submit" class="formButton2" value="Pay" >
                            </td>
                        </tr>
                    <?}elseif($accommodation_amount_due<0){?>
                        <tr>
                            <td><b><?=$TEXT['view']['amount_out']?></b></td>
                            <td>
                                <input type="hidden" name="action" value="withdraw_or_add_to_balance" />
                                <input type="number" name="amount_pay" value="<?=-$accommodation_amount_due?>" min="0.01" max="<?=-$accommodation_amount_due?>" step="0.01">
                            </td>
                            <td>
                                <select name="payment_method_id" id="payment_method_id" class="formField1" style="">
                                    <option value="<?=$TMPL_payment_methods[5]['id']?>" ><?=$TMPL_payment_methods[5]['title'] . '(' . $TMPL_guest['balance'] . ')' ?></option>';
                                    <option value="<?=$TMPL_payment_methods[1]['id']?>" ><?=$TMPL_payment_methods[1]['title'] ?></option>';

                                </select>
                            </td>
                            <td>
                                <input class="formButton2" type="submit" value="Pay" >
                            </td>
                        </tr>
                    <?}?>
                </table>
            </form>
        </td>
        <td class="tdrow2"></td>
        <td class="tdrow2">
            <form id="service_pay_form" method="post"  onsubmit="return confirm('Are you sure?');">
                <input type="hidden" name="destination" value="extra-service" />
                <table border="0" width="100%">
                    <? if($extra_services_amount_due>0){?>
                        <tr>
                            <td><b><?=$TEXT['view']['amount_in']?></b></td>
                            <td>
                                <input type="hidden" name="action" value="add_amount_paid" />
                                <input type="number" name="amount_pay" value="0.01" min="0.01" step="0.01">
                            </td>
                            <td>
                                <select name="payment_method_id" id="payment_method_id" class="formField1" style="">
                                    <? foreach($TMPL_payment_methods as $payment_method){
                                        if($payment_method['id']==5){
                                            if($TMPL_guest['balance']!=0){
                                                echo '<option value="' . $payment_method['id'] . '" >' . $payment_method['title'] . '(' . $TMPL_responsive_guest['balance'] . ')</option>';
                                            }
                                        }else{
                                            echo '<option value="'.$payment_method['id'].'" >'.$payment_method['title'].'</option>';
                                        }
                                    }?>
                                </select>
                            </td>
                            <td>
                                <input type="submit" class="formButton2" value="Pay" >
                            </td>
                        </tr>
                    <?}elseif($extra_services_amount_due<0){?>
                        <tr>
                            <td><b><?=$TEXT['view']['amount_out']?></b></td>
                            <td>
                                <input type="hidden" name="action" value="withdraw_or_add_to_balance" />
                                <input type="number" name="amount_pay" value="<?=-$extra_services_amount_due?>" min="0.01" max="<?=-$extra_services_amount_due?>" step="0.01">
                            </td>
                            <td>
                                <select name="payment_method_id" id="payment_method_id" class="formField1" style="">
                                    <option value="<?=$TMPL_payment_methods[5]['id']?>" ><?=$TMPL_payment_methods[5]['title'] . '(' . $TMPL_guest['balance'] . ')' ?></option>';
                                    <option value="<?=$TMPL_payment_methods[1]['id']?>" ><?=$TMPL_payment_methods[1]['title'] ?></option>';

                                </select>
                            </td>
                            <td>
                                <input type="submit" class="formButton2" value="Pay" >
                            </td>
                        </tr>
                    <?}?>
                </table>
            </form>
        </td>
        <td class="tdrow2"></td>
    </tr>
    <tr>
        <td class="tdrow2"></td>
        <td class="tdrow2">
            <a href="index.php?m=booking_management&tab=booking_list&action=get_invoice&invoice_type=accommodation&booking_id=<?=$TMPL_booking['id']?>" target="_blank" ><?=$TEXT['get_accommodation_invoice']?></a>
        </td>
        <td class="tdrow2"></td>
        <td class="tdrow2">
            <a href="index.php?m=booking_management&tab=booking_list&action=get_invoice&invoice_type=services&booking_id=<?=$TMPL_booking['id']?>" target="_blank" ><?=$TEXT['get_service_invoice']?></a>
        </td>
        <td class="tdrow2"></td>
    </tr>
</table>
</div>
<br>
<a href="index.php?m=booking_management&tab=booking_list&action=get_invoice&invoice_type=full&booking_id=<?=$TMPL_booking['id']?>" target="_blank"><?=$TEXT['get_full_invoice']?></a>
<br>
<br>
<?if(count($TMPL_guestRegularPayments)>1){?>
<div style="background:#FFF; border:solid #3A82CC 1px; ">
<div style="padding:2px; background:#3A82CC; color:#FFF"><b>რეგისტრირებული ბარათები</b></div>


<table border="0" cellpadding="1" cellspacing="0" >

<? foreach($TMPL_guestRegularPayments AS $transaction_id=>$guestRegularPayment){
$result=unserialize(unserialize($guestRegularPayment['postback_message']))?>
<tr>
<td><?=$result['CARD_NUMBER']?></td>
<td><?=$result['RECC_PMNT_EXPIRY']?></td>


<?if(in_array($TMPL_booking['id'], explode(',',$guestRegularPayment['booking_id']) )){?>
<td>registered for this booking</td>
<td><input type="radio" name="recc_pmnt_id" value="<?=$transaction_id?>" checked="checked" form="accommodation_pay_form" /></td>

<? }else{ ?>
<td>registered for another booking</td>
<td><input type="radio" name="recc_pmnt_id" value="<?=$transaction_id?>" form="accommodation_pay_form"></td>
<?}?>

</tr>
<?}?>
</table>
</div>
<?}?>


<br>
<form  method="post">
    <input type="hidden" name="action" value="change_status" />
    <table>
        <tr>
            <td><b><?=$TEXT['view']['change_status']?></b></td>
            <td>
                <select name="status_id" id="status_id" class="formField1" style="">
                    <? foreach($TMPL_all_statuses as $status){
                        if($status['id']>=$TMPL_booking['status_id'] || $_SESSION['pcms_user_group']<=2){
                            echo '<option value="'.$status['id'].'" '.($status["id"]==$TMPL_booking["status_id"]?"selected":"").'>'.$status['title'].'</option>';
                        }
                    } ?>
                </select>
            </td>
            <td><input type="submit" value="<?=$TEXT['view']['change']?>" class="formButton2"></td>
        </tr>
    </table>
</form>
<br>

<div style="background:#FFF; border:solid #3A82CC 1px; width:100%; display: block; padding-bottom:28px">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?=$TEXT['tasks']['title']?></b>
    </div>
    <table width="100%">
        <tr>
            <td class="table-th">&#8470;</td>
            <td class="table-th">
                <?= $TEXT['tasks']['creator'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['tasks']['deadline'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['tasks']['executor'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['tasks']['executed_at'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['tasks']['task'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['tasks']['status'] ?>
            </td>
            <td class="table-th"><?= $TEXT['tasks']['action'] ?></td>
        </tr>
        <?
        foreach ($TMPL_booking_tasks AS $task) { ?>
            <tr class="table-tr">
                <td class="table-td">
                    <b><? $p = ((int)($_GET['p'])!=0) ? (int)($_GET['p']) : 1;
                        echo ($p - 1) * intval($TMPL_settings['page_num']) + $counter + 1;?></b>
                </td>
                <td class="table-td" title="<?=$TMPL_users[$task['creator_id']]['firstname']?> <?=$TMPL_users[$task['creator_id']]['lastname']?> (<?= date('Y-m-d H:i:s', strtotime($task['created_at'])) ?>)">
                   <?=$TMPL_users[$task['creator_id']]['login']?>
                </td>
                <td class="table-td">
                    <div  class="date-filter basic" title="<?= date('Y-m-d', strtotime($task['deadline_at'])) ?>">
                        <?= date('Y-m-d H:i:s', strtotime($task['deadline_at'])) ?>
                    </div>
                </td>
                <td class="table-td">
                    <div title="<?=$TMPL_users[$task['executor_id']]['firstname']?> <?=$TMPL_users[$task['executor_id']]['lastname']?>">
                        <?=($TMPL_users[$task['executor_id']]['login']=='')?$TEXT['tasks']['undefined']:$TMPL_users[$task['executor_id']]['login']?>
                    </div>
                </td>
                <td class="table-td">
                    <div >
                        <?=($task['executed_at']=='')?$TEXT['tasks']['undefined']:date('Y-m-d H:i:s', strtotime($task['executed_at'])) ?>
                    </div>
                </td>
                <td class="table-td">
                    <div  id="task_<?=$task['id']?>"><?= $task['task']?></div>
                </td>
                <td class="table-td" style=" <?=($task['status']==1)?'background-color:#83FA00; color:#000000':'background-color:#FF0000; color:FFFFFF'?>">
                    <div class="change_task_status_trigger" task_id="<?=$task['id']?>" style="cursor: pointer">
                        <?= ($task['status']==1)?$TEXT['tasks']['executed']:$TEXT['tasks']['unexecuted']?>
                    </div>
                </td>
                <td class="table-td">
                    <div class="edit_task" task_id="<?=$task['id']?>" deadline_date="<?=date('Y-m-d', strtotime($task['deadline_at']))?>" deadline_time="<?=date('H:i:s', strtotime($task['deadline_at']))?>" style="float:left; cursor: pointer">
                        <img src="./images/icos16/edit.gif" width="16" height="16" border="0" align="middle" alt="Full Info"  title="<?=$TEXT['tasks']['edit_task']?>">
                    </div>
                    <div class="delete_task" task_id="<?=$task['id']?>" style="float:left; margin-left: 10px; cursor: pointer" >
                        <img src="./images/icos16/delete.gif" width="16" height="16" border="0" align="middle" alt="Full Info"  title="<?=$TEXT['tasks']['delete_task']?>">
                    </div>
                </td>
            </tr>
        <? $counter++;} ?>
    </table>
    <div id="add_new_task_modal_trigger" class="formButton2" style="margin-top:6px; cursor:pointer; padding-left:4px; float: right; width:140px"><?=$TEXT['tasks']['add_new_task']?></div>
</div>



<?
$total_price=$TMPL_booking['accommodation_price']+$TMPL_booking['services_price'];
$total_paid=$TMPL_booking['paid_amount']+$TMPL_booking['services_paid_amount'];
?>

<?if($_SESSION['pcms_user_group']<=2){?>
<div id="delete_booking_modal" style="display: none" title="<?= $TEXT['del_booking_modal']['delete_booking'] ?>">
    <form  method="post" id="delete_booking_form" >
        <input type="hidden" name="action" value="del_booking" />
        <input type="hidden" name="total_price" value="<?=$total_price?>" />
        <input type="hidden" name="total_paid" value="<?=$total_paid?>" />
        <table>
            <tr>
                <td><b><?= $TEXT['del_booking_modal']['refund_amount'] ?></b></td>
                <td><input type="number" name="refund_amount" value="<?=$total_paid?>" min="0.01" max="<?=$total_paid?>"></td>
            </tr>
            <tr>
                <td><b><?= $TEXT['del_booking_modal']['refund_type'] ?></b></td>
                <td>
                    <select name="refund_method_id" id="refund_method_id" class="formField1" style="">
                        <? foreach($TMPL_payment_methods as $payment_method){
                        echo '<option value="'.$payment_method['id'].'" >'.$payment_method['title'].'</option>';
                            if($payment_method['id']==5){
                            //balansi
                            }
                        }?>
                    </select>
                </td>
            </tr>
        </table>
    </form>
</div>
    <div id="cancel_booking_modal" style="display: none" title="<?= $TEXT['del_booking_modal']['delete_booking'] ?>">
        <form  method="post" id="cancel_booking_form" >
            <input type="hidden" name="action" value="del_booking" />
            <input type="hidden" name="total_price" value="<?=$total_price?>" />
            <input type="hidden" name="total_paid" value="<?=$total_paid?>" />
            <textarea name="dl_coment" id="" cols="69" rows="10" placeholder="კომენტარი"></textarea>
        </form>
    </div>
    <div id="cancel_booking_modal_trigger" total_price="<?=$total_price?>" total_paid="<?=$total_paid?>" class="formButton2" style="margin-top:10px; margin-bottom:10px; padding:2px; cursor: pointer; text-align: centerl; width: 160px; float:right">
        <?= $TEXT['del_booking_modal']['delete_booking'] ?>
    </div>

<div id="delete_booking_modal_trigger" total_price="<?=$total_price?>" total_paid="<?=$total_paid?>" class="formButton2" style="margin-top:10px; margin-bottom:10px; padding:2px; cursor: pointer; text-align: centerl; width: 160px; float:right">
ჯავშნის წაშლა
</div>
<?}?>

<form id="add_foreign_guest_form" method="post">
    <input type="hidden" name="action" value="add_foreign_guest">
    <input type="hidden" name="f_guest_id" id="f_guest_id" value="">
</form>
<form id="change_booking_guest" method="post">
    <input type="hidden" name="action" value="edit_booking_guest">
    <input type="hidden" name="l_guest_id" id="l_guest_id" value="">
</form>
<form id="change_resonsive_guest" method="post">
    <input type="hidden" name="action" value="change_resonsive_guest">
    <input type="hidden" name="l_guest_id" id="l_guest_id" value="">
</form>

<form id="remove_foreign_guest_form" method="post">
    <input type="hidden" name="action" value="remove_foreign_guest">
    <input type="hidden" name="f_guest_id" id="f_guest_id" value="0">
</form>

<form id="change_task_status_form" method="post">
    <input type="hidden" name="action" value="change_task_status">
    <input type="hidden" name="task_id" id="task_id" value="0">
</form>

<form id="delete_task_form" method="post">
    <input type="hidden" name="action" value="delete_task">
    <input type="hidden" name="task_id" id="task_id" value="0">
</form>

<div id="booking_service_modal" style="display:none" title="<?= $TEXT['service_modal']['title'] ?>">
    <form id="add_service_form" method="post">
        <input type="hidden" name="action" value="add_service">
        <input type="hidden" name="booking_daily_id" id="booking_daily_id" value="0">
        <table>
            <tr>
                <td><label for="service_type"><?= $TEXT['service_modal']['type'] ?></label></td>
                <td>
                    <select name="service_type" id="service_type" style="width:160px">
                        <option value="0"><?= $TEXT['service_modal']['select_service_type'] ?></option>
                        <? foreach ($TMPL_services_types as $services_type) {
                            if($services_type['id']!=4&&$services_type['id']!=7)continue;
                            echo '<option value="' . $services_type['id'] . '" >' . $services_type['title'] . '</option>';
                        } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="service_selector"><?= $TEXT['service_modal']['service'] ?></label></td>
                <td>
                    <select name="service_selector" id="service_selector" style="width:160px">
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="service_title"><?= $TEXT['service_modal']['name'] ?></label></td>
                <td>
                    <input type="text" name="service_title" id="service_title" value="" style="width: 160px">
                </td>
            </tr>
            <tr>
                <td><label for="services_count"><?= $TEXT['service_modal']['services_count'] ?></label></td>
                <td>
                    <select name="services_count" id="services_count" >
                        <?for($i=1; $i<11; $i++ ){?>
                            <option value="<?=$i?>"><?=$i?></option>
                        <?}?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="service_price"><?= $TEXT['service_modal']['price'] ?></label></td>
                <td><input type="number" id="service_price" name="service_price" min="0" value="0"
                           step="any" style="width:160px"/></td>
            </tr>
        </table>
    </form>
</div>
<div id="booking_service_bedroom_modal" style="display:none" title="<?= $TEXT['service_modal']['title'] ?>">
    <form id="add_service_form_bedroom" method="post">
        <input type="hidden" name="action" value="add_service">
        <input type="hidden" name="booking_daily_id" id="booking_daily_id_bedroom" value="0">
        <table>
            <tr>
                <td><label for="service_type"><?= $TEXT['service_modal']['type'] ?></label></td>
                <td>
                    <select name="service_type" id="service_type_bedroom" style="width:160px">
                        <option value="0"><?= $TEXT['service_modal']['select_service_type'] ?></option>
                        <? foreach ($TMPL_services_types as $services_type) {
                            if($services_type['id']!=2)continue;
                            echo '<option value="' . $services_type['id'] . '" >' . $services_type['title'] . '</option>';
                        } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="service_selector"><?= $TEXT['service_modal']['service'] ?></label></td>
                <td>
                    <select name="service_selector" id="service_selector_bedroom" style="width:160px">
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="service_title"><?= $TEXT['service_modal']['name'] ?></label></td>
                <td>
                    <input type="text" name="service_title" id="service_title_bedroom" value="" style="width: 160px">
                </td>
            </tr>
            <tr>
                <td><label for="services_count"><?= $TEXT['service_modal']['services_count'] ?></label></td>
                <td>
                    <select name="services_count" id="services_count" >
                        <?for($i=1; $i<11; $i++ ){?>
                            <option value="<?=$i?>"><?=$i?></option>
                        <?}?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="service_price"><?= $TEXT['service_modal']['price'] ?></label></td>
                <td><input type="number" id="service_price_bedroom" name="service_price" min="0" value="0"
                           step="any" style="width:160px"/></td>
            </tr>
        </table>
    </form>
</div>

<div id="add_new_task_modal" style="display:none" title="<?= $TEXT['tasks']['add_new_task'] ?>">
  <form id="add_new_task_form" method="post">
      <input type="hidden" name="action" value="add_new_task">
      <input type="hidden" name="task_id" value="0">
      <table width="100%" border="0">
          <tr>
              <td width="50%">
                  <label for="service_price"><?= $TEXT['tasks']['date'] ?>Date: </label>
                    <input type="text" id="deadline_date" name="deadline_date" value="<?=CURRENT_DATE?>" class="calendar-icon" autocomplete="off" />
               </td>
              <td width="50%">
                  <label for="service_price"><?= $TEXT['tasks']['date'] ?>Time: </label>
                   <select id="deadline_time" name="deadline_time">
                   <?for($i=0;$i<24;$i++){?>
                        <option value="<?=($i>9)?$i:'0'.$i?>:00:00"><?=($i>9)?$i:'0'.$i?>:00:00</option>
                        <option value="<?=($i>9)?$i:'0'.$i?>:30:00"><?=($i>9)?$i:'0'.$i?>:30:00</option>
                   <?}?>
                   </select>
              </td>
          <tr>
          </tr>
              <td colspan="2">
                <textarea id="task" class="formField3" style="width:100%" name="task"></textarea>
              </td>
          </tr>
      </table>
  </form>
</div>

<div id="change_room_modal" style="display:none" title="<?= $TEXT['change_room_modal']['title'] ?>">
    <form id="change_room_form" method="post">
        <input type="hidden" name="action" value="change_room">
        <table>
            <tr>
                <td><label for="room_id"><?= $TEXT['service_modal']['select_room'] ?></label></td>
                <td>
                    <select name="room_id" id="room_id" style="width:160px">
                    </select>
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="change_food_modal" style="display:none" title="<?= $TEXT['change_room_modal']['title'] ?>">
    <form id="change_food_form" method="post">
        <input type="hidden" name="action" value="change_food">
        <input type="hidden" name="booking_id" value="<?=$TMPL_booking['id']?>">
        <table>
            <tr>
                <td><label for="food_id"><?= $TEXT['service_modal']['select_room'] ?></label></td>
                <td>
                    <select name="food_id" id="food_id" style="width:160px">
                    </select>
                </td>
            </tr>
        </table>
    </form>
</div>
<div id="change_guest_count_modal" style="display:none" title="<?= $TEXT['change_room_modal']['title'] ?>">
    <form id="change_guest_count_form" method="post">
        <input type="hidden" name="action" value="change_guest_count">
        <input type="hidden" name="booking_id" value="<?=$TMPL_booking['id']?>">
        <table>
            <tr>
                <td>
                    <label for="adult_num"><?= $TEXT['service_modal']['adult_num'] ?></label></td>
                <td>
                    <input type="text" name="adult_num" id="adult_num">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="child_num"><?= $TEXT['service_modal']['child_num'] ?></label></td>
                <td>
                    <input type="text" name="child_num" id="child_num">
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="change_checkin_checkout_modal" style="display: none" title="<?= $TEXT['change_room_modal']['title'] ?>">
    <form id="change_checkin_checkout_form" method="post">
        <input type="hidden" name="action" value="change_checkin_checkout">
        <input type="hidden" name="room_id" value="<?=$TMPL_booking['room_id']?>">
        <table>
            <tr>
                <td><label for="new_checkin"><?= $TEXT['view']['checkin'] ?></label></td>
                <td>
                    <input id="new_checkin" class="calendar-icon" type="text" placeholder="დან" autocomplete="off" value="<?=$TMPL_booking['check_in']?>" name="new_checkin" <?=($TMPL_booking['check_in']<$_SESSION['server_date'])?"readonly":""?> >
                </td>
            </tr>
            <tr>
                <td><label for="new_checkout"><?= $TEXT['view']['checkout'] ?></label></td>
                <td>
                    <input id="new_checkout" class="calendar-icon" type="text" placeholder="მდე" autocomplete="off" value="<?=$TMPL_booking['check_out']?>" name="new_checkout">
                </td>
            </tr>
            <tr>
                <td><?= $TEXT['booking_modal']['food'] ?></td>
                <td align="left">

                  <!-- change_checkin_checkout_form Food form tab -->
                    <select name="food_price" id="food_price" class="formField1" style="width:100%">
                        <? foreach ($TMPL_all_food as $food) { ?>
                          <option value="<?=$food['price']?>" <?=$TMPL_booking['food_id']==$food['id']?'selected':''?> >[<?=$food['price']?>] <?=$food['title']?> </option>;
                        <?} ?>
                    </select>
                </td>
            </tr>
        </table>
    </form>
</div>


<script>

    <?
    $counter=0;
   echo "\nvar allServices=[";
   foreach($TMPL_all_services AS $service){
   if($counter!=0){ echo ","; }
        echo "{id:".$service['id'].", type_id:".$service['type_id'].", title:'".$service['title']."', price:".$service['price']."}";
        $counter++;
    }
    echo "];";
?>
    $( document ).ready(function() {

        $(".close-but").click(function () {
            $header = $(this);
            //getting the next element
            $content = $header.next();
            //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
            $content.slideToggle(50, function () {
                //execute this after slideToggle is done
                //change text of header based on visibility of content div
                $header.text(function () {
                    //change text based on condition
                    //return $content.is(":visible") ? "Collapse" : "Expand";
                });
            });
        });


        $("#delete_booking_modal_trigger").click(function () {
            var total_price=$(this).attr('total_price');
            var total_paid=$(this).attr('total_paid');
            if(total_paid==0){
                $("#delete_booking_form").submit();
            }else{
                $("#delete_booking_modal").dialog({
                        resizable: false,
                        width: 400,
                        modal: true,
                        buttons: {
                            "<?= $TEXT['del_booking_modal']['end_refund'] ?>": function () {
                                $("#delete_booking_form").submit();
                                $(this).dialog("close");

                            },
                            "<?= $TEXT['del_booking_modal']['cancel'] ?>": function () {
                                $(this).dialog("close");
                            }
                        },
                        position: { my: 'top', at: 'top+300' }
                });
            }
        });
        $("#cancel_booking_modal_trigger").click(function () {
            var total_price=$(this).attr('total_price');
            var total_paid=$(this).attr('total_paid');

                $("#cancel_booking_modal").dialog({
                    resizable: false,
                    width: 400,
                    modal: true,
                    buttons: {
                        "Done": function () {
                            $("#cancel_booking_form").submit();
                            $(this).dialog("close");

                        },
                        "<?= $TEXT['del_booking_modal']['cancel'] ?>": function () {
                            $(this).dialog("close");
                        }
                    },
                    position: { my: 'top', at: 'top+300' }
                });

        });

        $(".delete_task").click(function () {
            if(confirm('are you sure?')){
                $('#delete_task_form input[name=task_id]').val($(this).attr('task_id'));
                $('#delete_task_form').submit();
            }
        });

        $(".change_task_status_trigger").click(function () {
            $('#change_task_status_form input[name=task_id]').val($(this).attr('task_id'));
            $('#change_task_status_form').submit();
        });
        $(".remove_foreign_guest_trigger").click(function () {
            $('#remove_foreign_guest_form input[name=f_guest_id]').val($(this).attr('guest_id'));
            $('#remove_foreign_guest_form').submit();
        });



        $("#add_new_task_modal_trigger").click(function () {
            $("#add_new_task_modal").attr('title','<?=$TEXT['tasks']['add_new_task']?>');
            $("#add_new_task_form input[name='action']").val("add_new_task");
            $("#add_new_task_form textarea[name='task']").val("");
            $("#add_new_task_form input[name='deadline_date']").val('<?=date('Y-m-d',mktime())?>');
            $("#add_new_task_form select[name='deadline_time']").val('00:00:00');
            $("#add_new_task_modal").dialog({
                resizable: false,
                width: 400,
                modal: true,
                buttons: {
                    "<?= $TEXT['tasks']['but_add'] ?>": function () {
                        $("#add_new_task_form").submit();
                        $(this).dialog("close");
                    },
                    "<?= $TEXT['tasks']['but_cancel'] ?>": function () {
                        $(this).dialog("close");
                    }
                }
            });
            $("#add_new_task_modal").dialog('option', 'title', '<?=$TEXT['tasks']['add_new_task']?>');
            $("#deadline_date").datepicker('hide');
        });

        $(".edit_task").click(function () {
            $("#add_new_task_modal").attr('title','<?=$TEXT['tasks']['edit_task']?>');
            $("#add_new_task_form input[name='action']").val("edit_task");
            $("#add_new_task_form input[name='task_id']").val($(this).attr('task_id'));
            $("#add_new_task_form textarea[name='task']").val($('#task_'+$(this).attr('task_id')).text());
            $("#add_new_task_form input[name='deadline_date']").val($(this).attr('deadline_date'));
            $("#add_new_task_form select[name='deadline_time']").val($(this).attr('deadline_time'));
            $("#add_new_task_modal").dialog({
                resizable: false,
                width: 400,
                modal: true,
                buttons: {
                    "<?= $TEXT['tasks']['but_edit'] ?>": function () {
                        $("#add_new_task_form").submit();
                        $(this).dialog("close");
                    },
                    "<?= $TEXT['tasks']['but_cancel'] ?>": function () {
                        $(this).dialog("close");
                    }
                }
            });
            $("#add_new_task_modal").dialog('option', 'title', '<?=$TEXT['tasks']['edit_task']?>');
            $("#deadline_date").datepicker('hide');
        });

        $(".services_modal_trigger").click(function () {
            $('#booking_daily_id').val($(this).attr('booking_daily_id'));
            $("#booking_service_modal").dialog({
                resizable: false,
                width: 400,
                modal: true,
                buttons: {
                    "<?= $TEXT['service_modal']['but_add'] ?>": function () {

                        if($('#service_type').val()==0){
                            alert("Please Select Services Type");
                            return;
                        }
                        if ($("#service_selector").val() == 0) {
                            if ($("#service_title").val() == '') {
                                alert("Set title to service");
                                return;
                            }
                        }
                        $("#add_service_form").submit();
                        $(this).dialog("close");

                    },
                    "<?= $TEXT['service_modal']['but_cancel'] ?>": function () {
                        $(this).dialog("close");
                    }
                }
            });
        });
        $(".services_modal_trigger_bedroom").click(function () {
            $('#booking_daily_id_bedroom').val($(this).attr('booking_daily_id'));
            $("#booking_service_bedroom_modal").dialog({
                resizable: false,
                width: 400,
                modal: true,
                buttons: {
                    "<?= $TEXT['service_modal']['but_add'] ?>": function () {

                        if($('#service_type_bedroom').val()==0){
                            alert("Please Select Services Type");
                            return;
                        }
                        if ($("#service_selector_bedroom").val() == 0) {
                            if ($("#service_title_bedroom").val() == '') {
                                alert("Set title to service");
                                return;
                            }
                        }
                        $("#add_service_form_bedroom").submit();
                        $(this).dialog("close");

                    },
                    "<?= $TEXT['service_modal']['but_cancel'] ?>": function () {
                        $(this).dialog("close");
                    }
                }
            });
        });

        <?="var other_service='".$TEXT['service_modal']['add_new_service']."';"?>
        $('#service_type').on("change", function (e) {

            if ($(this).val() == 0) {
                $("#service_selector").html('');
                $("#service_title").val('');
                $("#service_title").attr("readonly", false);
                $("#service_price").val(0);
                $("#service_price").attr("readonly", false);
            } else {
                $("#service_selector").html('');
                $('#service_selector').append('<option value="0" price="0" >'+other_service+'</option>');
                for(var i=0; i<allServices.length;i++){
                    if($(this).val()==allServices[i]['type_id']) {
                        $('#service_selector').append('<option value="'+allServices[i]['id']+'" price="'+allServices[i]["price"]+'" >'+allServices[i]["title"]+'</option>');
                    }
                }
            }
            $("#service_title").val('');
            $("#service_title").attr("readonly", false);
            $("#service_price").val(0);
            $("#service_price").attr("readonly", false);
        });

        $('#service_selector').on("change", function (e) {
            if ($(this).val() == 0) {
                $("#service_title").val('');
                $("#service_title").attr("readonly", false);
                $("#service_price").val(0);
                $("#service_price").attr("readonly", false);
            } else {
                $("#service_title").val($('option:selected', this).text());
                $("#service_title").attr("readonly", true);
                $("#service_price").val($('option:selected', this).attr('price'));
                $("#service_price").attr("readonly", true);

            }
        });

        $('#service_type_bedroom').on("change", function (e) {

            if ($(this).val() == 0) {
                $("#service_selector_bedroom").html('');
                $("#service_title_bedroom").val('');
                $("#service_title_bedroom").attr("readonly", false);
                $("#service_price_bedroom").val(0);
                $("#service_price_bedroom").attr("readonly", false);
            } else {
                $("#service_selector_bedroom").html('');
                $('#service_selector_bedroom').append('<option value="0" price="0" >'+other_service+'</option>');
                for(var i=0; i<allServices.length;i++){
                    if($(this).val()==allServices[i]['type_id']) {
                        $('#service_selector_bedroom').append('<option value="'+allServices[i]['id']+'" price="'+allServices[i]["price"]+'" >'+allServices[i]["title"]+'</option>');
                    }
                }
                console.log('this sets price to 2 0');
            }
            $("#service_title_bedroom").val('');
            $("#service_title_bedroom").attr("readonly", false);
            $("#service_price_bedroom").val(0);
            $("#service_price_bedroom").attr("readonly", false);
        });
        $('#service_selector_bedroom').on("change", function (e) {
            if ($(this).val() == 0) {

                $("#service_title_bedroom").val('');
                $("#service_title_bedroom").prop('readonly', false);
                $("#service_price_bedroom").val(0);
                $("#service_price_bedroom").prop('readonly', false);
            } else {
                $("#service_title_bedroom").val($('option:selected', this).text());
                $("#service_title_bedroom").attr("readonly", true);
                console.log($('option:selected', this).attr('price'))
                $("#service_price_bedroom").val($('option:selected', this).attr('price'));
                $("#service_price_bedroom").attr("readonly", true);

            }
        });


        $("#save_changed_price").click(function () {
            saveChangedAccPrice();
        });


        $("#cancel_changed_price").click(function () {
            var old_price=parseFloat($('#acc_price_trigger').attr('total_price'));
            $("#price_editor_inp").val(old_price);
            previewChangedPrice();
            $('#price_editor').hide();
            $('#acc_total_price').show();
        });

        $("#acc_total_price").click(function () {
            <?if($_SESSION['pcms_user_group']<=2){?>
                $('#price_editor').show();
                $('#acc_total_price').hide();
            <?}?>
        });

        $("#price_editor_inp").change(function() {
            previewChangedPrice();
        });


        function previewChangedPrice(){
            var old_total_price=parseFloat($("#acc_price_trigger").attr('total_price'));
            var without_services_price=parseFloat($("#acc_price_trigger").attr('without_services_price'));
            var services_price=old_total_price-without_services_price;
            var new_total_price=parseFloat($("#price_editor_inp").val())-services_price;
            var old_price=0;
            var new_price=0;
            var days_count=$(".booking_daily_price" ).length;
            $(".booking_daily_price").each(function() {
                // if(without_services_price>0){
                //      old_price=parseFloat($(this).attr('price'));
                //      new_price=((old_price*new_total_price)/without_services_price).toFixed(2);
                // }else{
                    if($(this).attr('type')!='check_out'){
                        new_price=(new_total_price/(days_count-1)).toFixed(2);
                    }else{
                        new_price=0;
                    }
              //  }
                $(this).text(new_price);
                $(this).attr('new_price',new_price);
            });
            $('#acc_amount_due').text((parseFloat($("#price_editor_inp").val())-parseFloat($('#acc_paid_amount').attr('amount'))).toFixed(2));
        }

        function saveChangedAccPrice(){
            var request = $.ajax({
               url: "index_ajax.php?cmd=change_booking_acc_price",
               method: "POST",
               data: {booking_id:'<?=(int)$_GET['booking_id']?>', accomodation_price:$('#price_editor_inp').val()},
               dataType: "json"
            });

            request.done(function (msg) {
//                console.log(msg);
                location.reload();
            });

            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        }

        $("#deadline_date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            minDate: 'today',
            modal: true,
            numberOfMonths: 2,
            dateFormat:'yy-mm-dd',
            onSelect: function( selectedDate ) {
                var date = $(this).datepicker('getDate');
            }
        });

        $(".change_room_modal_trigger").click(function () {
            var request = $.ajax({
                url: "index_ajax.php?cmd=get_free_rooms",
                method: "POST",
                data: {current_room_id:'<?=$TMPL_room['id']?>',check_in: '<?=$TMPL_booking['check_in']?>',check_out:'<?=$TMPL_booking['check_out']?>'},
                dataType: "json"
            });

            request.done(function (msg) {
                $("#room_id").html('');
                $.each( msg, function( block_title, room_types ) {
                    $('#room_id').append('<optgroup label="'+block_title +'">');
                    $.each( room_types, function( room_type_title, rooms ) {
                        $('#room_id').append('<optgroup label="-'+room_type_title +'">');
                        $.each( rooms, function( key, room ) {
                            if(parseInt('<?=$TMPL_room['id']?>')==room.id){
                                $('#room_id').append('<option value="'+room.id+'" selected>--'+room.name+' ('+room.floor+')</option>');
                            }else{
                                $('#room_id').append('<option value="'+room.id+'">--'+room.name+' ('+room.floor+')</option>');
                            }
                        });
                        $('#room_id').append('</optgroup>');
                    });
                    $('#room_id').append('</optgroup>');
                });
            });

            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });

            $("#change_room_modal").dialog({
                resizable: false,
                width: 400,
                modal: true,
                buttons: {
                    "<?= $TEXT['change_room_modal']['edit'] ?>": function () {
                        $('#change_room_form').submit();
                        $(this).dialog("close");
                    },
                    "<?= $TEXT['change_room_modal']['cancel'] ?>": function () {
                        $(this).dialog("close");
                    }
                }
            });
        });
        //Food change Dialog
        $(".change_food_modal_trigger").click(function () {
            var request = $.ajax({
                url: "index_ajax.php?cmd=get_food_ids",
                method: "POST",
                data: {current_room_id:'<?=$TMPL_room['id']?>',check_in: '<?=$TMPL_booking['check_in']?>',check_out:'<?=$TMPL_booking['check_out']?>'},
                dataType: "json"
            });

            request.done(function (msg) {
                console.log(msg);
                $("#food_id").html('');
                $.each( msg, function( price, title ) {
                    $('#food_id').append('<option value="'+title.id+'">'+title.title+' ('+price+')</option>');
                });
            });

            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });

            $("#change_food_modal").dialog({
                resizable: false,
                width: 400,
                modal: true,
                buttons: {
                    "<?= $TEXT['change_room_modal']['edit'] ?>": function () {
                        $('#change_food_form').submit();
                        $(this).dialog("close");
                    },
                    "<?= $TEXT['change_room_modal']['cancel'] ?>": function () {
                        $(this).dialog("close");
                    }
                }
            });
        });

    //Food change Dialog End
    // GUEST COUNT CHANGE
        $(".change_guest_count_modal_trigger").click(function () {
            var request = $.ajax({
                url: "index_ajax.php?cmd=get_guest_counts",
                method: "POST",
                data: {current_room_id:'<?=$TMPL_booking['id']?>'},
                dataType: "json"
            });

            request.done(function (msg) {
                console.log(msg);
                $('#adult_num').val(msg['adult']);
                $('#child_num').val(msg['child']);
            });

            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });

            $("#change_guest_count_modal").dialog({
                resizable: false,
                width: 400,
                modal: true,
                buttons: {
                    "<?= $TEXT['change_room_modal']['edit'] ?>": function () {
                        $('#change_guest_count_form').submit();
                        $(this).dialog("close");
                    },
                    "<?= $TEXT['change_room_modal']['cancel'] ?>": function () {
                        $(this).dialog("close");
                    }
                }
            });
        });
    // GUEST COUNT END
        var disabled_checkin_days=[];
        var disabled_checkout_days=[];
        var request = $.ajax({
            url: "index_ajax.php?cmd=get_room_booking_days",
            method: "POST",
            data: {room_id:'<?=$TMPL_room['id']?>',booking_id:'<?=$TMPL_booking['id']?>', check_in: '<?=$TMPL_booking['check_in']?>',check_out:'<?=$TMPL_booking['check_out']?>'},
            dataType: "json"
        });

        request.done(function (msg) {
            $.each( msg, function( type, days ) {
                $.each( days, function(k, day ) {
                    if(type=='check_in'){
                        disabled_checkin_days.push(day);
                    }else if(type=='in_use'){
                        disabled_checkin_days.push(day);
                        disabled_checkout_days.push(day);
                    }else if(type=='check_out'){
                        disabled_checkout_days.push(day);
                    }
                });
            });
            $("#new_checkin").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                <?=($TMPL_booking['check_in']<$_SESSION['server_date'])?'disabled:true,':'disabled:false,'?>
                minDate: 'today',
                maxDate:'<?= date('Y-m-d', strtotime($TMPL_booking['check_out'] .' -1 day')) ?>',
                numberOfMonths: 2,
                dateFormat:'yy-mm-dd',
                beforeShowDay:function(date){
                    var string = $.datepicker.formatDate('yy-mm-dd', date);
                    return [disabled_checkin_days.indexOf(string) == -1];
                },
                onSelect: function( selectedDate ) {
                    var date = $(this).datepicker('getDate');
                    date.setTime(date.getTime() + (1000*60*60*24));
                    $( "#new_checkout" ).datepicker( "option", "minDate",date );
                }
            });

            $("#new_checkout").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                minDate: '<?=date('Y-m-d', strtotime($TMPL_booking['check_in'] .' +1 day'))?>',
                <?=($TMPL_booking['check_out']<$_SESSION['server_date'])?'disabled:true,':'disabled:false,'?>
                numberOfMonths: 2,
                dateFormat:'yy-mm-dd',
                beforeShowDay:function(date){
                    var string = $.datepicker.formatDate('yy-mm-dd', date);
                    return [disabled_checkout_days.indexOf(string) == -1];
                },
                onSelect: function( selectedDate ) {
                    var date = $(this).datepicker('getDate');
                    date.setTime(date.getTime() - (60*60*24));
                    console.log('new_checkin maxDate ', $.datepicker.formatDate('yy-mm-dd', date));
                    $( "#new_checkin" ).datepicker( "option", "maxDate", $.datepicker.formatDate('yy-mm-dd', date) );

                }
            });
        });

        request.fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });

        //tarigebis shcvlaa
        $(".change_checkin_checkout_modal_trigger").click(function () {
           $("#change_checkin_checkout_modal").dialog({
                resizable: false,
                width: 400,
                modal: true,
                buttons: {
                    "<?= $TEXT['change_room_modal']['edit'] ?>": function () {
                        $('#change_checkin_checkout_form').submit();
                        $(this).dialog("close");
                    },
                    "<?= $TEXT['change_room_modal']['cancel'] ?>": function () {
                        $(this).dialog("close");
                    }
                }
            });
        });


    });
    $('.open_services').click(function(){
        classes = {};
            $($(this).attr('class').split(' '))
        var classNames = $($(this).attr('class').split(' '));
        $.each(classNames, function (i, className) {
            classes[i]=className;
        });
        $('.servie_'+classes[1]).toggle('slow');
    });
    function triggerServices(elem){
      console.log(elem);
      var date=$(elem).attr('dds');
      $('.servie_'+ date).toggle('slow');
    }
</script>

<?
    require_once("add_guest.template.php");
?>
