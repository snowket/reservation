<script type="text/javascript" src="./js/tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript" src="./js/tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<link rel="stylesheet" href="./js/tablesorter/addons/pager/jquery.tablesorter.pager.css">
<link rel="stylesheet" href="./js/tablesorter/themes/blue/style.css">


<div style="clear: both"></div>

<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['guests']['filter_modal']['title'] ?></b>
    </div>
    <form action="" method="get" id="filter_form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td align="right">
                    <label for="guest_id_number"><?= $TEXT['guests']['filter_modal']['guest_id'] ?></label>
                </td>
                <td>
                    <input type="text" id="guest_id_number" name="guest_id_number" value="<?=$_GET['guest_id_number']?>"/>
                </td>
                <td align="right">
                    <label for="guest_type"><?= $TEXT['guests']['filter_modal']['guest_type'] ?></label>
                </td>
                <td>
                    <select id="guest_type" name="guest_type">
                        <option value="" <? if(!isset($_GET['guest_type'])){echo "selected";}?>><?=$TEXT['guest_type']['all']?></option>
                        <option value="non-corporate" <? if(isset($_GET['guest_type'])&&$_GET['guest_type']=='non-corporate'){echo "selected";}?>><?=$TEXT['guest_type']['non-corporate']?></option>
                        <option value="company" <? if(isset($_GET['guest_type'])&&$_GET['guest_type']=='company'){echo "selected";}?>><?=$TEXT['guest_type']['company']?></option>
                        <option value="tour-company" <? if(isset($_GET['guest_type'])&&$_GET['guest_type']=='tour-company'){echo "selected";}?>><?=$TEXT['guest_type']['tour-company']?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="guest_name"><?= $TEXT['guests']['filter_modal']['guest_name'] ?></label>
                </td>
                <td>
                    <input type="text" id="guest_name" name="guest_name" value="<?=$_GET['guest_name']?>"/>
                </td>
                <td align="right">
                    <label for="tax"><?= $TEXT['guests']['filter_modal']['tax'] ?></label>
                </td>
                <td>
                    <select id="tax" name="tax">
                        <option value="2" <? if(isset($_GET['tax'])&&(int)$_GET['tax']==2){echo "selected";}?>><?= $TEXT['guests_financial_state']['all'] ?></option>
                        <option value="1" <? if(isset($_GET['tax'])&&(int)$_GET['tax']==1){echo "selected";}?>>TAX INCLUDED</option>
                        <option value="0" <? if(isset($_GET['tax'])&&(int)$_GET['tax']==0){echo "selected";}?>>TAX FREE</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="total_unpaid"><?= $TEXT['guests_financial_state']['total_unpaid'] ?></label>
                </td>
                <td>
                    <select id="total_unpaid" name="total_unpaid">
                        <option value="0" <? if(isset($_GET['total_unpaid'])&&(int)$_GET['total_unpaid']==0){echo "selected";}?>><?= $TEXT['guests_financial_state']['all'] ?></option>
                        <option value="1" <? if(isset($_GET['total_unpaid'])&&(int)$_GET['total_unpaid']==1){echo "selected";}?>> >0 </option>
                        <option value="2" <? if(isset($_GET['total_unpaid'])&&(int)$_GET['total_unpaid']==2){echo "selected";}?>> =0 </option>
                        <option value="3" <? if(isset($_GET['total_unpaid'])&&(int)$_GET['total_unpaid']==3){echo "selected";}?>> <0 </option>
                    </select>
                </td>
                <td align="right">
                    <label for="guest_balance">
                        <?= $TEXT['guests_financial_state']['balance'] ?>
                    </label>
                </td>
                <td>
                    <select id="guest_balance" name="guest_balance">
                        <option value="0" <? if(isset($_GET['guest_balance'])&&(int)$_GET['guest_balance']==0){echo "selected";}?>><?= $TEXT['guests_financial_state']['all'] ?></option>
                        <option value="1" <? if(isset($_GET['guest_balance'])&&(int)$_GET['guest_balance']==1){echo "selected";}?>> >0 </option>
                    </select>
                </td>
            </tr>

            <tr>
                <td align="right" colspan="4">
                    <br>
                    <input class="formButton2" type="submit" value="<?= $TEXT['guests']['filter_modal']['submit_filter'] ?>" style="cursor: pointer">
                </td>
            </tr>
        </table>
    </form>
</div>

<div style="height:10px">

</div>
<table class="table-table" cellpadding="2" cellspacing="0">
    <tr>
        <td class="table-th">&#8470;</td>
        <td class="table-th">
            <?= $TEXT['guests_financial_state']['guest'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['guests_financial_state']['id_number'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['guests_financial_state']['type'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['guests_financial_state']['tax'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['guests_financial_state']['total_debts'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['guests_financial_state']['total_paid'] ?>
        </td>
        <td class="table-th"><?= $TEXT['guests_financial_state']['total_unpaid'] ?></td>
        <td class="table-th"><?= $TEXT['guests_financial_state']['balance'] ?></td>
        <td class="table-th"><?= $TEXT['guests_financial_state']['action'] ?></td>
    </tr>
    <?
    $counter=0;
    foreach($TMPL_guestsBookingsSummaryState AS $guest_state) {$counter++;
        ?>
        <tr class="table-tr">
            <td class="table-td">
                <b><? $p = intval($_GET['p']) ? intval($_GET['p']) : 1;
                    echo ($p - 1) * intval($TMPL_settings['page_num']) + $counter; ?></b>
            </td>
            <td class="table-td">
                <a class="guest-filter basic" href="#">
                    <b><?= $guest_state['first_name'] ?> <?= $guest_state['last_name'] ?></b>
                </a>
            </td>
            <td class="table-td">
                <a class="guest-idnumber-filter basic" href="#" guest_id_number="<?= $guest_state['guest_id_number'] ?>"><?= $guest_state['guest_id_number'] ?></a>
            </td>

            <td class="table-td">
                <a class="basic" href="#"><?= $guest_state['type'] ?></a>
            </td>
            <td class="table-td">
                <a class="basic"
                   tax="<?= $guest_state['tax'] ?>" href="#">
                    <?= ($guest_state['tax'] == 0) ? 'TAX FREE' : 'TAX INCLUDED' ?>
                </a>
            </td>
            <td class="table-td">
                <?=(float)$guest_state['credit'];?>
            </td>
            <td class="table-td">
                <?=(float)$guest_state['debit'];?>
            </td>
            <td class="table-td">
                <span style="color: red; font-weight: bold"><?=($guest_state['credit']-$guest_state['debit']);?></span>
            </td>
            <td class="table-td">
                <?=$guest_state['balance']?>
            </td>
            <td class="table-td">
                <div class="fill_guest_balance_trigger" guest_id="<?=$guest_state['id']?>" style="cursor:pointer; float: left" title="<?=$TEXT['guests_financial_state']['add_balance']?>">
                    <img src="./images/icos16/money-add.png" width="16" height="16" border="0" align="middle" alt="Fill Balance">
                </div>
                <a href="index.php?m=booking_management&tab=booking_list&appro=1&guest_id=<?=$guest_state['guest_id_number']?>&guest_name=<?=$guest_state['first_name']?><?=($guest_state['last_name'])?' '.$guest_state['last_name']:''?>" style="float:left; margin-left: 10px;" title="<?=$TEXT['guests_financial_state']['show_all_bookings']?>">
                    <img src="./images/icos16/booking_bell.gif" width="16" height="16" border="0" align="middle" alt="show all bookings">
                </a>
            </td>
        </tr>
    <? } ?>
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

<div id="fill_guest_balance_modal" style="display:none" title="<?=$TEXT['guests_financial_state']['fill_balance_modal']['title']?>">
    <form id="fill_guest_balance_form" method="post">
        <input type="hidden" name="action" value="fill_guest_balance">
        <input type="hidden" name="guest_id" value="">
        <table>
            <tr>
                <td><label for="amount"><?=$TEXT['guests_financial_state']['fill_balance_modal']['amount']?></label></td>
                <td>
                    <input type="number" min="0" step="0.01" name="amount" id="amount" value="0" style="width:160px">
                </td>
            </tr>
        </table>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.fill_guest_balance_trigger').click(function () {
            $("#fill_guest_balance_form input[name=guest_id]").val($(this).attr('guest_id'));
            $("#fill_guest_balance_modal").dialog({
                resizable: false,
                width: 250,
                modal: true,
                buttons: {
                    "<?=$TEXT['guests_financial_state']['fill_balance_modal']['add']?>": function () {
                        $("#fill_guest_balance_form").submit();
                        $(this).dialog("close");

                    },
                    "<?=$TEXT['guests_financial_state']['fill_balance_modal']['cancel']?>": function () {
                        $(this).dialog("close");
                    }
                }
            });

        });

        $('#excel_downloader').click(function () {
            $('#excel_download_form').submit();
        });

        $('.guest-filter').click(function () {
            $("#guest_name").val($(this).find('b').text());
            $("#filter_form").submit();
        });
        $('.guest-idnumber-filter').click(function () {
                    $("#guest_id_number").val($(this).attr("guest_id_number"));
                    $("#filter_form").submit();
                });

        $('.date-filter').click(function () {
            $("#start_date").val($(this).attr("date"));
            $("#end_date").val($(this).attr("date"));
            $("#filter_form").submit();
        });
        $('.payment_method-filter').click(function () {
            $("#payment_method_id").val($(this).attr("payment_method_id"));
            $("#filter_form").submit();
        });
        $('.tax-filter').click(function () {
            $("#tax").val($(this).attr("tax"));
            $("#filter_form").submit();
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
    });
</script>
