
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">


<? if(!empty($TMPL_errors)){?>
    <div style="border:solid red 2px;">
        <? foreach($TMPL_errors as $error){?>
            <div><?=$error?></div>
        <?}?>
    </div>
<?}?>
<? $total_price=0; ?>
<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?=$TEXT['view']['booking_info']?></b>
    </div>
    <table border="0" style="width:100%; " cellpadding="1" cellspacing="0" class="text1">
        <tr>
            <td class="tdrow1"><?=$TEXT['view']['booking_id']?></td>
            <td class="tdrow2"><span class="text_black"><?=$TMPL_booking['id']?></span></td>
        </tr>
        <tr>
            <td class="tdrow1"><?=$TEXT['view']['room']?></td>
            <td class="tdrow2">
                <div style="display: inline; padding:2px; text-align: center">
                    <?=$TMPL_room['name']?> floor(<?=$TMPL_room['floor']?>)
                </div>
            </td>
        </tr>
        <tr>
            <td class="tdrow1"><?=$TEXT['view']['checkin_checkout']?></td>
            <td class="tdrow2">
                <div class="" style="display: inline; padding:2px; text-align: center">
                    <?=$TMPL_booking['check_in']?> - <?=$TMPL_booking['check_out']?>
                </div>
            </td>
        </tr>

        <tr>
            <td class="tdrow1"><?=$TEXT['view']['comment']?></td>
            <td class="tdrow2"><span class="text_black"><?=$TMPL_booking['comment']?></span></td>
        </tr>
    </table>
</div>
<br>

<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
<table border="0" width="100%" cellpadding="1" cellspacing="0" >
    <tr>
        <td class="tdrow2"  style="background-color:#3a82cc; color:FFF;">&nbsp;&nbsp;&nbsp;</td>
        <td class="tdrow2"  style="background-color:#3a82cc; color:FFF;"><?=$TEXT['view']['guest']?></td>
        <td class="tdrow2" >&nbsp;&nbsp;&nbsp;</td>
        <td class="tdrow2" colspan="2" style="background-color:#3a82cc; color:FFF;"><?=$TEXT['view']['responsive_guest']?></td>
    </tr>
    <tr>
        <td class="tdrow2" >&nbsp;&nbsp;&nbsp;</td>
        <td class="tdrow2">
            <table border="0" style="width:100%; " cellpadding="1" cellspacing="0" class="text1">
                <tr>
                    <td class="tdrow1"><?=$TEXT['view']['guest']?></td>
                    <td class="tdrow2"><span class="text_black"><?=$TMPL_guest['first_name']?> <?=$TMPL_guest['last_name']?></span></td>
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
                    <td class="tdrow2"><span class="text_black"><?=$TMPL_responsive_guest['first_name']?> <?=$TMPL_responsive_guest['last_name']?></span></td>
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
        <td class="tdrow2" style="background-color:#3a82cc; color:FFF;"><?=$TEXT['view']['extra_services']?></td>
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
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td nowrap><b><?=$TEXT['view']['accommodation']?></b></td>
                    <td><div style="height:16px; overflow: hidden;">----------------------------------------------------</div></td>
                    <td nowrap align="right"  width="30px">
                       <div id="daily_acc_price_<?=$TMPL_booking_days[$i]['id']?>" class="booking_daily_price" type="<?=$TMPL_booking_days[$i]['type']?>" price="<?=$TMPL_booking_days[$i]['price']?>" new_price="<?=$TMPL_booking_days[$i]['price']?>"><?=$TMPL_booking_days[$i]['price']?></div>
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
                        </tr>
                <?}}}?>
			</table>
		</td>
        <td class="tdrow2">&nbsp;</td>
        <td class="tdrow2" height="20" valign="top">
            <table width="100%">
                <? for($j=0; $j<count($TMPL_booking_daily_services); $j++){
                    if($TMPL_booking_daily_services[$j]['booking_daily_id']==$TMPL_booking_days[$i]['id']){
                        if($TMPL_booking_daily_services[$j]['service_type_id']!=2&&$TMPL_booking_daily_services[$j]['service_type_id']!=9){
                            $total_price+=$TMPL_booking_daily_services[$j]['service_price'];
                            $total_daily_services_price+=$TMPL_booking_daily_services[$j]['service_price'];
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

        <td class="tdrow2">
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
                                <div style="float: left"><input id="price_editor_inp" type="number" value="<?=$TMPL_booking['accommodation_price']?>" style="width: 60px"></div>
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
                            <td align="right">TTT<?=$transaction['amount']?></td>
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
            <form id="accommodation_pay_form"  method="post"  onsubmit="return confirm('Are you sure?');">
                <input type="hidden" name="destination" value="accommodation" />
                <table border="0" width="100%">
                    <? if($accommodation_amount_due>0){?>
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
            <td><b>Change Status</b></td>
            <td>
                <select name="status_id" id="status_id" class="formField1" style="">
                    <? foreach($TMPL_all_statuses as $status){
                        if($status['id']>=$TMPL_booking['status_id']){
                            echo '<option value="'.$status['id'].'" >'.$status['title'].'</option>';
                        }
                    } ?>
                </select>
            </td>
            <td><input type="submit" value="Update" class="formButton2"></td>
        </tr>
    </table>
</form>
<br>

<?
$total_price=$TMPL_booking['accommodation_price']+$TMPL_booking['services_price'];
$total_paid=$TMPL_booking['paid_amount']+$TMPL_booking['services_paid_amount'];
?>

<div id="acc_price_modal" style="display: none" title="<?= $TEXT['change_room_modal']['title'] ?>">
    <form id="change_checkin_checkout_form" method="post">
        <input type="hidden" name="action" value="change_checkin_checkout">
        <input type="hidden" name="room_id" value="<?=$TMPL_booking['room_id']?>">
        <table>
            <tr>
                <td><label for="new_checkin"><?= $TEXT['view']['checkin'] ?></label></td>
                <td>
                    <input id="new_checkin" class="calendar-icon" type="text" placeholder="დან" autocomplete="off" value="<?=$TMPL_booking['check_in']?>" name="new_checkin" <?=($TMPL_booking['check_in']<date("Y-m-d"))?"readonly":""?> >
                </td>
            </tr>
            <tr>
                <td><label for="new_checkout"><?= $TEXT['view']['checkout'] ?></label></td>
                <td>
                    <input id="new_checkout" class="calendar-icon" type="text" placeholder="მდე" autocomplete="off" value="<?=$TMPL_booking['check_out']?>" name="new_checkout">
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
                        }
                });
            }
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
            $('#price_editor').show();
            $('#acc_total_price').hide();
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
                if(without_services_price>0){
                    old_price=parseFloat($(this).attr('price'));
                    new_price=((old_price*new_total_price)/without_services_price).toFixed(2);
                }else{
                    if($(this).attr('type')!='check_out'){
                        new_price=(new_total_price/(days_count-1)).toFixed(2);
                    }else{
                        new_price=0;
                    }
                }
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

            /*
                $.each(msg.bdz,function( k, v ) {
                   $('#daily_acc_price_'+k).text(v);
                   $('#daily_acc_price_'+k).attr('price',v);
                   $('#daily_acc_price_'+k).attr('new_price',v);
                   console.log('#daily_acc_price_'+k,v);
                });
                $('#acc_total_price').text(msg.new_total_price);
                $('#acc_total_price').attr('total_price',msg.new_total_price);
                //$('#acc_total_price').attr('without_services_price',msg.new_total_price-);

                //$('#acc_amount_due').attr('amount',msg.total_price-);
                $('#acc_price_trigger').attr('total_price',msg.new_total_price);
                $('#price_editor').hide();
                $('#acc_total_price').show();*/
                location.reload();
            });

            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        }

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
            console.log(disabled_checkin_days);
            console.log(disabled_checkout_days);
            console.log('max= ',Math.max.apply(Math, disabled_checkin_days));
            $("#new_checkin").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                disabled:<?=($TMPL_booking['check_in']<date("Y-m-d"))?'true':'false'?>,
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
                disabled:<?=($TMPL_booking['check_out']<date("Y-m-d"))?'true':'false'?>,
                numberOfMonths: 2,
                dateFormat:'yy-mm-dd',
                beforeShowDay:function(date){
                    var string = $.datepicker.formatDate('yy-mm-dd', date);
                    return [disabled_checkout_days.indexOf(string) == -1];
                },
                onSelect: function( selectedDate ) {
                    var date = $(this).datepicker('getDate');
                    date.setTime(date.getTime() - (1000*60*60*24));
                    $( "#new_checkin" ).datepicker( "option", "maxDate",date );

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
</script>