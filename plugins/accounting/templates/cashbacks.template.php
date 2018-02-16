<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['cashbacks']['filter_modal']['title'] ?></b>
    </div>
    <form action="" method="get" id="filter_form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td align="right">
                    <label for="guest_id"><?= $TEXT['cashbacks']['filter_modal']['guest_id'] ?></label>
                </td>
                <td>
                    <input type="text" id="guest_id" name="guest_id" value=""/>
                </td>
                <td align="right">
                    <label for="start_date"><?= $TEXT['cashbacks']['filter_modal']['date'] ?></label>
                </td>
                <td >
                    <input type="text" class="calendar-icon" id="start_date" name="start_date" value=""/>
                    <input type="text" class="calendar-icon" id="end_date" name="end_date" value=""/>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="guest_name"><?= $TEXT['cashbacks']['filter_modal']['guest_name'] ?></label>
                </td>
                <td>
                    <input type="text" id="guest_name" name="guest_name" value=""/>
                </td>
                <td align="right">
                    <label for="amount_from"><?= $TEXT['cashbacks']['filter_modal']['amount'] ?></label>
                </td>
                <td>
                    <input type="number" id="amount_from" name="amount_from" min="0" step="0.01" value="<?=(double)$_GET['amount_from']?>"/>
                    <input type="number" id="amount_to" name="amount_to" min="0" step="0.01" value="<?=(double)$_GET['amount_to']?>"/>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="guest_name"><?= $TEXT['cashbacks']['filter_modal']['status'] ?></label>
                </td>
                <td>
                    <select id="cashback_type" style="" name="cashback_type">
                        <option value="0"><?= $TEXT['cashbacks']['filter_modal']['type'][0] ?></option>
                        <option value="1"><?= $TEXT['cashbacks']['filter_modal']['type'][1] ?></option>
                        <option value="2"><?= $TEXT['cashbacks']['filter_modal']['type'][2] ?></option>
                    </select>
                </td>
                <td align="right">
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td align="right" colspan="4">
                    <br>
                    <input class="formButton2" type="submit" value="<?= $TEXT['cashbacks']['filter_modal']['submit_filter'] ?>" style="cursor: pointer">
                </td>
            </tr>
        </table>
    </form>
</div>

</br>
<table id="cashbacks_table" style="width:100%">
        <tr>
            <td class="table-th">&#8470; </td>
            <td class="table-th">
                <?=$TEXT['cashbacks']['partner']?>
            </td>
            <td class="table-th">
                <?=$TEXT['cashbacks']['date']?>
            </td>
            <td class="table-th">
                <?=$TEXT['cashbacks']['amount']?>
            </td>
            <td class="table-th">
                <?=$TEXT['cashbacks']['amount_paid']?>
            </td>
            <td class="table-th">
                <?=$TEXT['cashbacks']['amount_due']?>
            </td>
            <td class="table-th"><?=$TEXT['cashbacks']['action']?></td>
        </tr>
    <? for($i=0;$i<count($TMPL_cashbacks);$i++){ ?>
        <tr>
            <td class="table-td">
                <b><?=(((int)($_GET['p'])*50)+($i+1)); ?></b>
            </td>
            <td class="table-td">
                <b>
                    <?=$TMPL_guests[$TMPL_cashbacks[$i]['affiliate_id']]['first_name']?>
                    <?=$TMPL_guests[$TMPL_cashbacks[$i]['affiliate_id']]['last_name']?>
                </b>
            </td>
            <td class="table-td">
                <?=$TMPL_cashbacks[$i]['date']?>
            </td>
            <td class="table-td">
                <?= $TMPL_cashbacks[$i]['total_cashback_amount']?>
            </td>
            <td class="table-td">
                <?= $TMPL_cashbacks[$i]['paid_cashback_amount']?>
            </td>
            <td class="table-td">
                <?= ($TMPL_cashbacks[$i]['total_cashback_amount']-$TMPL_cashbacks[$i]['paid_cashback_amount'])?>
            </td>
            <td class="table-td">
                <div class="pay_cashback_trigger" cashback_id="<?=$TMPL_cashbacks[$i]['id']?>" amount="<?=($TMPL_cashbacks[$i]['total_cashback_amount']-$TMPL_cashbacks[$i]['paid_cashback_amount'])?>" style="cursor:pointer; float: left" title="<?=$TEXT['cashbacks']['modal']['title']?>">
                    <img src="./images/icos16/money-add.png" width="16" height="16" border="0" align="middle" alt="Fill Balance">
                </div>
                <div class="action-icon goto_booking_view" title="<?=$TEXT['cashbacks']['view_booking']?>">
                    <a href="index.php?m=booking_management&tab=booking_list&action=view&booking_id=<?=$TMPL_cashbacks[$i]['booking_id']?>">
                        <img src="./images/icos16/booking_bell.png">
                    </a>
                </div>
            </td>
        </tr>
    <?}?>
</table>
</br>
<div><center><?=$TMPL_navbar?></center></div>

<div id="excel_downloader" class="download-excel" style="float:right">
Download
</div>

<div id="pay_cashback_modal" style="display:none" title="<?=$TEXT['cashbacks']['modal']['title']?>">
    <form id="pay_cashback_form" method="post">
        <input type="hidden" name="action" value="pay_cashback">
        <input type="hidden" name="cashback_id" value="0">
        <table>
            <tr>
                <td><label for="amount"><?=$TEXT['cashbacks']['modal']['amount']?></label></td>
                <td>
                    <input type="number" min="0.01" step="0.01" name="amount" id="amount" value="0.01" style="width:160px">
                </td>
            </tr>
        </table>
    </form>
</div>

<form method="post" target="_blank" id="excel_download_form">
    <input type="hidden" name="action" value="get_excel">
</form>

<script type="text/javascript">
$(document).ready(function () {
    var firstDayOfCurrentMonth = '<?=(isset($_GET['start_date'])&& $_GET['start_date']!='')?$_GET['start_date']:date('Y-m-01')?>';
    var currentDayOfCurrentMonth =  '<?=(isset($_GET['end_date'])&& $_GET['end_date']!='')?$_GET['end_date']:date('Y-m-d')?>';

    $("#start_date").datepicker({
        defaultDate: firstDayOfCurrentMonth,
        changeMonth: true,
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
        defaultDate:currentDayOfCurrentMonth,
        changeMonth: true,
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        buttonImageOnly: true,
        buttonText: "Select date",
        onSelect: function (selectedDate) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() - (1000 * 60 * 60 * 24));
            $("#start_date").datepicker("option", "maxDate", date);
        }
    });


    $("#start_date").datepicker("setDate", firstDayOfCurrentMonth);
    $("#end_date").datepicker("setDate",currentDayOfCurrentMonth);

    $('.pay_cashback_trigger').click(function(){
        $("#pay_cashback_form input[name=cashback_id]").val($(this).attr('cashback_id'));
        $("#pay_cashback_form input[name=amount]").attr('max',$(this).attr('amount'));
        $("#pay_cashback_form input[name=amount]").val($(this).attr('amount'));

        $("#pay_cashback_modal").dialog({
            resizable: false,
            width: 250,
            modal: true,
            buttons: {
                "<?=$TEXT['cashbacks']['modal']['add']?>": function () {
                    if($("#pay_cashback_form input[name=amount]").val()>$("#pay_cashback_form input[name=amount]").attr('max')){
                        alert("Incorrect Amount");
                        return false;
                    }
                    $("#pay_cashback_form").submit();
                    $(this).dialog("close");

                },
                "<?=$TEXT['cashbacks']['modal']['cancel']?>": function () {
                    $(this).dialog("close");
                }
            }
        });

    });
    $('#excel_downloader').click(function () {
                $('#excel_download_form').submit();
    });
});
</script>