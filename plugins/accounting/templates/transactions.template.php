<style>
input, textarea, select {
    width: 120px;
}
</style>

<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['transactions']['filter_modal']['title'] ?></b>
    </div>
    <form action="" method="get" id="filter_form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td align="right">
                    <label for="guest_id"><?= $TEXT['transactions']['filter_modal']['guest_id'] ?></label>
                </td>
                <td>
                    <input type="text" id="guest_id" name="guest_id" value="<?= $_GET['guest_id'] ?>"/>
                </td>
                <td align="right">
                    <label for="start_date"><?= $TEXT['transactions']['filter_modal']['date'] ?></label>
                </td>
                <td>
                    <input type="text" class="calendar-icon" id="start_date" name="start_date" value=""/>
                    <input type="text" class="calendar-icon" id="end_date" name="end_date" value=""/>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="guest_name"><?= $TEXT['transactions']['filter_modal']['guest_name'] ?></label>
                </td>
                <td>
                    <input type="text" id="guest_name" name="guest_name" value="<?= $_GET['guest_name'] ?>"/>
                </td>
                <td align="right">
                    <label for="amount_from"><?= $TEXT['transactions']['filter_modal']['amount'] ?></label>
                </td>
                <td>
                    <input type="number" id="amount_from" name="amount_from" step="0.01" placeholder="<?=$TEXT['transactions']['filter_modal']['from']?>"
                           value="<?= ((float)$_GET['amount_from']!=0)?(float)$_GET['amount_from']:'' ?>"/>
                    <input type="number" id="amount_to" name="amount_to"  step="0.01" placeholder="<?=$TEXT['transactions']['filter_modal']['to']?>"
                           value="<?= ((float)$_GET['amount_to']!=0)?(float)$_GET['amount_to']:'' ?>"/>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="payment_method_id"><?= $TEXT['transactions']['filter_modal']['payment_method'] ?></label>
                </td>
                <td>
                    <select id="payment_method_id" name="payment_method_id">
                        <option value="0"><?=$TEXT['transactions']['all']?></option>
                        <? foreach ($TMPL_payment_methods as $payment_method) { ?>
                            <option
                                value="<?= $payment_method['id'] ?>" <? if ((int)$_GET['payment_method_id'] == $payment_method['id']) {
                                echo "selected";
                            } ?>><?= $payment_method['title'] ?></option>
                        <? } ?>
                    </select>
                </td>
                <td align="right">
                    <label for="tax"><?= $TEXT['transactions']['filter_modal']['tax'] ?></label>
                </td>
                <td>
                    <select id="tax" name="tax">
                        <option value="2" <? if (isset($_GET['tax']) && (int)$_GET['tax'] == 2) {
                            echo "selected";
                        } ?>><?=$TEXT['all']?>
                        </option>
                        <option value="1" <? if (isset($_GET['tax']) && (int)$_GET['tax'] == 1) {
                            echo "selected";
                        } ?>>TAX INCLUDED
                        </option>
                        <option value="0" <? if (isset($_GET['tax']) && (int)$_GET['tax'] == 0) {
                            echo "selected";
                        } ?>>TAX FREE
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="guest_type"><?= $TEXT['transactions']['filter_modal']['guest_type'] ?></label>
                </td>
                <td>
                    <select id="guest_type" name="guest_type">
                        <option value="" <? if (!isset($_GET['guest_type'])) {
                            echo "selected";
                        } ?>><?= $TEXT['guest_type']['all'] ?></option>
                        <option
                            value="non-corporate" <? if (isset($_GET['guest_type']) && $_GET['guest_type'] == 'non-corporate') {
                            echo "selected";
                        } ?>><?= $TEXT['guest_type']['non-corporate'] ?></option>
                        <option value="company" <? if (isset($_GET['guest_type']) && $_GET['guest_type'] == 'company') {
                            echo "selected";
                        } ?>><?= $TEXT['guest_type']['company'] ?></option>
                        <option
                            value="tour-company" <? if (isset($_GET['guest_type']) && $_GET['guest_type'] == 'tour-company') {
                            echo "selected";
                        } ?>><?= $TEXT['guest_type']['tour-company'] ?></option>
                    </select>
                </td>
                <td align="right">
                    <label for="destination"><?= $TEXT['transactions']['filter_modal']['destination'] ?></label>
                </td>
                <td>
                    <select id="destination" name="destination">
                        <option value="" <? if (!isset($_GET['destination'])) {
                            echo "selected";
                        } ?>><?= $TEXT['destination']['all'] ?></option>
                        <option
                            value="accommodation" <? if (isset($_GET['destination']) && $_GET['destination'] == 'accommodation') {
                            echo "selected";
                        } ?>><?= $TEXT['destination']['accommodation'] ?></option>
                        <option
                            value="extra-service" <? if (isset($_GET['destination']) && $_GET['destination'] == 'extra-service') {
                            echo "selected";
                        } ?>><?= $TEXT['destination']['extra-service'] ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">
                Status
                </td>
                <td>
                    <select name="active">
                        <option value="0">ყველა</option>
                        <option value="3"   <?=(isset($_GET['active']) && $_GET['active']==3)?'selected':''?> >Canceled</option>
                    </select>
                </td>
                <td align="right">
                    <label for="destination"><?= $TEXT['transactions']['filter_modal']['tr_type'] ?></label>
                </td>
                <td>
                    <select id="tr_type" name="tr_type">
                        <option value="" <? if (!isset($_GET['tr_type'])) {
                            echo "selected";
                        } ?>><?= $TEXT['tr_type']['all'] ?></option>
                        <option
                            value="debit" <? if (isset($_GET['tr_type']) && $_GET['tr_type'] == 'debit') {
                            echo "selected";
                        } ?>><?= $TEXT['tr_type']['debit'] ?></option>
                        <option
                            value="credit" <? if (isset($_GET['tr_type']) && $_GET['tr_type'] == 'credit') {
                            echo "selected";
                        } ?>><?= $TEXT['tr_type']['credit'] ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right" colspan="4">
                    <br>
                    <input class="formButton2" type="submit"
                           value="<?= $TEXT['transactions']['filter_modal']['submit_filter'] ?>" style="cursor: pointer">
                </td>
            </tr>
        </table>
    </form>
</div>

<div style="height: 10px;">

</div>


<table class="table-table" cellpadding="2" cellspacing="0">
    <tr>
        <td class="table-th">&#8470;</td>
        <td class="table-th">
            <?= $TEXT['transactions']['guest'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['transactions']['administrator'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['transactions']['date'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['booking_list']['check_in'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['booking_list']['check_out'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['transactions']['room'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['transactions']['method'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['transactions']['tax'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['transactions']['debit'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['transactions']['credit'] ?>
        </td>
        <td class="table-th">
            Status
        </td>
        <td class="table-th"><?= $TEXT['transactions']['action'] ?></td>
    </tr>
    <? $debit = 0;
    $credit = 0;//p($TMPL_transactions);
    //p($TMPL_transactions[0]);
    $counter=0;
    foreach ($TMPL_transactions AS $TR) { ?>
        <tr class="table-tr">
            <td class="table-td">
                <b><? $p = ((int)($_GET['p'])!=0) ? (int)($_GET['p']) : 1;
                    echo ($p - 1) * intval($TMPL_settings['page_num']) + $counter + 1;?></b>
            </td>
            <td class="table-td">
                <div id="tr_name_<?=$TR['id']?>" class="guest-filter basic" guest_id="<?= $TR['guest_id'] ?>" title="<?= $TR['first_name'] ?> <?= $TR['last_name'] ?>" >
                    <b><?= $TR['first_name'] ?> <?= $TR['last_name'] ?></b>
                </div>
            </td>
            <td class="table-td">
                <div title="<?= $TMPL_administrators[$TR['administrator_id']]['login'] ?>" >
                    <b><?= ($TR['administrator_id']==0)?"SYSTEM":$TMPL_administrators[$TR['administrator_id']]['firstname']." ".$TMPL_administrators[$TR['administrator_id']]['lastname'] ?></b>
                </div>
            </td>
            <td class="table-td">
                <div id="tr_date_<?=$TR['id']?>" class="date-filter basic" title="<?= date('Y-m-d', strtotime($TR['end_date'])) ?>">
                    <?= date('Y-m-d', strtotime($TR['end_date'])) ?>
                </div>
            </td>
            <td class="table-td">
                <div id="tr_date_<?=$TR['id']?>" class="date-filter basic" title="<?= date('Y-m-d', strtotime($TR['bb']['check_in'])) ?>">
                    <?= date('Y-m-d', strtotime($TR['bb']['check_in'])) ?>
                </div>
            </td>
            <td class="table-td">
                <div id="tr_date_<?=$TR['id']?>" class="date-filter basic" title="<?= date('Y-m-d', strtotime($TR['bb']['check_out'])) ?>">
                    <?= date('Y-m-d', strtotime($TR['bb']['check_out'])) ?>
                </div>
            </td>

            <td class="table-td">
                <div>
                    <?=$TR['R_name']?> Floor (<?=$TR['floor']?>)
                </div>
            </td>

            <td class="table-td">
                <div id="tr_payment_method_id_<?=$TR['id']?>" class="payment_method-filter basic" title="<?= $TR['payment_method_id'] ?>">
                    <?= $TMPL_payment_methods[$TR['payment_method_id']]['title'] ?>
                </div>
            </td>
            <td class="table-td">
                <div id="tr_tax_<?=$TR['id']?>" class="tax-filter basic" title="<?= $TR['guest_tax'] ?>" >
                    <?= ($TR['guest_tax'] == 0) ? 'TAX FREE' : 'TAX INCLUDED' ?>
                </div>
            </td>
            <td class="table-td">
                <? if($TR['amount'] > 0){$debit += $TR['amount']; $tmp_debit=(float)$TR['amount']; }else{$tmp_debit=(float)0;} ?>
                <div id="tr_debit_<?=$TR['id']?>" title="<?=$tmp_debit?>" >
                <?=$tmp_debit?>
                </div>
            </td>
            <td class="table-td">
                <? if ($TR['amount'] < 0) {$credit += $TR['amount']; $tmp_credit=(float)$TR['amount'];}else{$tmp_credit=(float)0;} ?>
                <div id="tr_credit_<?=$TR['id']?>" title="<?=$tmp_credit?>">
                <?=$tmp_credit?>
                </div>
            </td>
            <td class="table-td">
                  <? if($TR['active']){ ?>
                      <span class="active">Active</span>
                <? } else{ ?>
                      <span class="canceled">Canceled</span>
                <? } ?>
            </td>
            <td class="table-td">
                <div tr_id="<?= $TR['id']?>" booking_id="<?= $TR['booking_id']?>" class="action-icon edit_modal_trigger" data-icon="edit" title="<?=$TEXT['transactions']['edit']?>">
                </div>
                <div tr_id="<?= $TR['id']?>" class="action-icon delete_modal_trigger" data-icon="delete" title="<?=$TEXT['transactions']['delete']?>">
                </div>
                <?if((int)$TR['booking_id']!=0){?>
                <div booking_id="<?= $TR['booking_id']?>" class="action-icon goto_booking_view" data-icon="booking_bell" title="<?=$TEXT['transactions']['booking']?>">
                </div>
                <?}?>
            </td>
        </tr>
    <? $counter++;} ?>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="table-th">total: <?= $debit ?></td>
        <td class="table-th">total: <?= $credit ?></td>
        <td></td>
    </tr>
</table>
<div>
    <center><?= $TMPL_navbar ?></center>
</div>
<form method="post" target="_blank" id="excel_download_form">
    <input type="hidden" name="action" value="get_excel">
</form>
<div id="excel_downloader" class="download-excel" style="float:right">
Download
</div>

<div id="edit_modal" style="display: none" title="<?=$TEXT['transactions']['edit_modal']['title']?>">
    <form id="edit_form" method="post">
        <input type="hidden" name="action" value="edit_transaction">
        <input type="hidden" id="edit_transaction_id" name="edit_transaction_id" value="">
        <table>
            <tr>
                <td align="right">
                    <label for="edit_guest_id"><?=$TEXT['transactions']['edit_modal']['guest_id']?></label>
                </td>
                <td align="left">
                    <input type="text" id="edit_guest_id" name="edit_guest_id" value="" readonly>
                </td>
                <td align="right">
                    <label for="edit_booking_id"><?=$TEXT['transactions']['edit_modal']['booking_id']?></label>
                </td>
                <td align="left">
                    <input id="edit_booking_id" name="edit_booking_id" type="text" readonly>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="edit_date"><?=$TEXT['transactions']['edit_modal']['date']?></label>
                </td>
                <td align="left">
                    <input type="text" id="edit_date" name="edit_date" class="calendar-icon" value="" >
                </td>
                <td align="right">
                    <label for="edit_amount_in"><?=$TEXT['transactions']['edit_modal']['amount_in']?></label>
                </td>
                <td align="left">
                    <input id="edit_amount_in" name="edit_amount_in" type="number" min="0" step="0.01"  value="0">
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="edit_payment_method_id"><?=$TEXT['transactions']['edit_modal']['payment_method']?></label>
                </td>
                <td align="left">
                    <select id="edit_payment_method_id" name="edit_payment_method_id">
                        <? foreach ($TMPL_payment_methods as $payment_method) { ?>
                            <option
                                value="<?= $payment_method['id'] ?>" <? if ((int)$_GET['payment_method_id'] == $payment_method['id']) {
                                echo "selected";
                            } ?>><?= $payment_method['title'] ?></option>
                        <? } ?>
                    </select>
                </td>
                <td align="right">
                    <label for="edit_amount_out"><?=$TEXT['transactions']['edit_modal']['amount_out']?></label>
                </td>
                <td align="left">
                    <input type="number" min="0" step="0.01" name="edit_amount_out" id="edit_amount_out" value="0">
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="edit_tax"><?=$TEXT['transactions']['edit_modal']['tax']?></label>
                </td>
                <td align="left">
                    <select id="edit_tax" name="edit_tax">
                        <option value="1">TAX INCLUDED</option>
                        <option value="0">TAX FREE</option>
                    </select>
                </td>
                <td align="right">

                </td>
                <td align="left">

                </td>
            </tr>
        </table>
    </form>
</div>

<div id="delete_modal" style="display: none" title="<?=$TEXT['transactions']['delete_modal']['title']?>">
<div><?=$TEXT['transactions']['delete_modal']['confirm_message']?></div>
    <form id="delete_form" method="post">
        <input type="hidden" name="action" value="delete_transaction">
        <input type="hidden" id="delete_transaction_id" name="delete_transaction_id" value="0">
    </form>
</div>

<script type="text/javascript">
$(document).ready(function () {
        $('.action-icon').each(function() {
           $(this).css('background-image',"url('./images/icos16/"+$(this).attr('data-icon')+".png')");
        });
        $('.action-icon').mouseover(function() {
           $(this).css('background-image',"url('./images/icos16/"+$(this).attr('data-icon')+"_hover.png')");
        });
        $('.action-icon').mouseout(function() {
           $(this).css('background-image',"url('./images/icos16/"+$(this).attr('data-icon')+".png')");
        });

        $('.goto_booking_view').click(function () {
            window.location.href = "index.php?m=booking_management&tab=booking_list&action=view&booking_id="+$(this).attr('booking_id');
        });
        $('#excel_downloader').click(function () {
            $('#excel_download_form').submit();
        });

        $('.guest-filter').click(function () {
            $("#guest_name").val($(this).find('b').text());
            $("#filter_form").submit();
        });
        $('.date-filter').click(function () {
            $("#start_date").val($(this).attr("title"));
            $("#end_date").val($(this).attr("title"));
            $("#filter_form").submit();
        });
        $('.payment_method-filter').click(function () {
            $("#payment_method_id").val(parseInt($(this).attr("title"),10));
            $("#filter_form").submit();
        });
        $('.tax-filter').click(function () {
            $("#tax").val(parseInt($(this).attr("title"),10));
            $("#filter_form").submit();
        });

        $("#edit_date").datepicker({
            defaultDate: firstDayOfCurrentMonth,
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            dateFormat: 'yy-mm-dd',
            buttonImageOnly: true,
            buttonText: "Select date",
            onSelect: function (selectedDate) {
                var date = $(this).datepicker('getDate');
                date.setTime(date.getTime() + (1000 * 60 * 60 * 24));
                $("#end_date").datepicker("option", "minDate", date);
            }
        });

        $("#start_date").datepicker({
            defaultDate: firstDayOfCurrentMonth,
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            dateFormat: 'yy-mm-dd',
            buttonImageOnly: true,
            buttonText: "Select date",
            onSelect: function (selectedDate) {
                var date = $(this).datepicker('getDate');
                date.setTime(date.getTime() + (1000 * 60 * 60 * 24));
                $("#end_date").datepicker("option", "minDate", date);
            }
        });
        $("#end_date").datepicker({
            dateFormat: 'yy-mm-dd',
            defaultDate: new Date(),
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            buttonImageOnly: true,
            buttonText: "Select date",
            onSelect: function (selectedDate) {
                var date = $(this).datepicker('getDate');
                date.setTime(date.getTime() - (1000 * 60 * 60 * 24));
                $("#start_date").datepicker("option", "maxDate", date);
            }
        });
        var curr_date = new Date(), y = curr_date.getFullYear(), m = curr_date.getMonth();
        var firstDayOfCurrentMonth = new Date(y, m, 1);
        var lastDayOfCurrentMonth = new Date(y, m + 1, 0);
        $("#start_date").datepicker("setDate", <?=(isset($_GET['start_date'])&&$_GET['start_date']!="")?"'".$_GET['start_date']."'":"firstDayOfCurrentMonth" ?>);
        $("#end_date").datepicker("setDate", <?=(isset($_GET['end_date'])&&$_GET['end_date']!="")?"'".$_GET['end_date']."'":"new Date()" ?>);

        $(".edit_modal_trigger").click(function () {
            var tr_id=$(this).attr('tr_id');
            $("#edit_transaction_id").val(tr_id);
            $("#edit_guest_id").val($('#tr_name_'+tr_id).attr('guest_id'));
            $("#edit_booking_id").val($(this).attr('booking_id'));
            $("#edit_date").val($('#tr_date_'+tr_id).attr('title'));
            $("#edit_amount_in").val($('#tr_debit_'+tr_id).attr('title'));
            $("#edit_amount_out").val(parseFloat($('#tr_credit_'+tr_id).attr('title'))*-1);
            $("#edit_payment_method_id").val($('#tr_payment_method_id_'+tr_id).attr('title'));
            $("#edit_tax").val($('#tr_tax_'+tr_id).attr('title'));

            $("#edit_modal").dialog({
                resizable: false,
                width: 540,
                modal: true,
                buttons: {
                    "<?=$TEXT['transactions']['edit_modal']['edit']?>": function () {
                            var request = $.ajax({
                                url: "index_ajax.php?cmd=edit_transaction",
                                method: "POST",
                                data: $('#edit_form').serialize(),
                                dataType: "json"
                            });

                            request.done(function (msg) {
                                if(msg['error']===undefined){
                                    var tr_id=msg.id;

                                    $('#tr_date_'+tr_id).attr('title',msg.end_date);
                                    $('#tr_date_'+tr_id).text(msg.end_date);
                                    if(parseFloat(msg.amount)>=0){
                                        $('#tr_debit_'+tr_id).attr('title',msg.amount);
                                        $('#tr_debit_'+tr_id).text(msg.amount);
                                        $('#tr_credit_'+tr_id).attr('title',0);
                                        $('#tr_credit_'+tr_id).text(0);
                                    }else{
                                        $('#tr_debit_'+tr_id).attr('title',0);
                                        $('#tr_debit_'+tr_id).text(0);
                                        $('#tr_credit_'+tr_id).attr('title',msg.amount);
                                        $('#tr_credit_'+tr_id).text(msg.amount);
                                    }
                                    $('#tr_payment_method_id_'+tr_id).attr('title',msg.payment_method_id);
                                    $('#tr_payment_method_id_'+tr_id).text(msg.payment_method);
                                    $('#tr_tax_'+tr_id).attr('title',msg.guest_tax);
                                    if(parseInt(msg.guest_tax,10)==1){
                                        $('#tr_tax_'+tr_id).text('TAX INCLUDED');
                                    }else{
                                        $('#tr_tax_'+tr_id).text('TAX FREE');
                                    }

                                }else{
                                    alert(msg.error_message);
                                }

                            });

                            request.fail(function (jqXHR, textStatus) {
                                alert('eroor');
                            });
                            $(this).dialog("close");
                    },
                    "<?=$TEXT['transactions']['edit_modal']['cancel']?>": function () {
                            $(this).dialog("close");
                        }
                    }
            });
            var $target=$('#edit_modal').dialog().parent();
            $target.css('top',(window.innerHeight-$target.height())/2);
            $target.css('left',(window.innerWidth-$target.width())/2);
            $target.css('position','fixed');
        });

        $(".delete_modal_trigger").click(function () {
            var tr_id=$(this).attr('tr_id');
            $("#delete_transaction_id").val(tr_id);

            $("#delete_modal").dialog({
                resizable: false,
                width: 300,
                modal: true,
                buttons: {
                    "<?=$TEXT['transactions']['delete_modal']['delete']?>": function () {
                            var request = $.ajax({
                                url: "index_ajax.php?cmd=delete_transaction",
                                method: "POST",
                                data: $('#delete_form').serialize(),
                                dataType: "json"
                            });

                            request.done(function (msg) {
                                if(msg['error']===undefined){
                                    var tr_id=msg.id;
                                    location.reload();
                                }else{
                                    alert(msg.error_message);
                                }
                            });

                            request.fail(function (jqXHR, textStatus) {
                                alert('eroor');
                            });
                            $(this).dialog("close");
                    },
                    "<?=$TEXT['transactions']['delete_modal']['cancel']?>": function () {
                            $(this).dialog("close");
                        }
                    }
            });
            var $target=$('#delete_modal').dialog().parent();
            $target.css('top',(window.innerHeight-$target.height())/2);
            $target.css('left',(window.innerWidth-$target.width())/2);
            $target.css('position','fixed');
        });



    });
</script>
