<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['transactions']['filter_modal']['title'] ?></b>
    </div>
    <form action="" method="get" id="filter_form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td align="center">
                    <label for="start_date"><?= $TEXT['transactions']['filter_modal']['date'] ?></label>

                    <input type="text" class="calendar-icon" id="start_date" name="start_date" value=""/>
                    <input type="text" class="calendar-icon" id="end_date" name="end_date" value=""/>

                    <input class="formButton2" type="submit"
                           value="<?= $TEXT['transactions']['filter_modal']['submit_filter'] ?>" style="cursor: pointer">
                </td>
            </tr>
        </table>
    </form>
</div>
<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;padding-top: 20px;">
    <div style="padding:2px;  color:#FFF">
        <table class="table-table" cellpadding="2" cellspacing="0" >
            <tr>


                <td class="table-th">
                    <?= $TEXT['rooms_usage_day_report']['used_rooms_count'] ?>
                </td>
                <td class="table-th">
                    <?= $TEXT['rooms_usage_day_report']['total_rooms_count'] ?>
                </td>
                <td class="table-th">
                    SUM
                </td>

            </tr>
            <?php foreach($days as $day){ ?>
            <tr>

                    <td class="table-td">
                        <?=$day['name']?>
                    </td>
                    <td class="table-td">
                        <?=$day['count']?>
                    </td>
                <td class="table-td">
                    <?=$day['sum']?>
                </td>
            </tr>

            <? $i++; } ?>

        </table>
    </div>
</div>
<form method="post" target="_blank" id="excel_download_form">
    <input type="hidden" name="action" value="get_excel">
</form>
<div id="excel_downloader" class="download-excel" style="float:right; margin-right: 10px">
    Download
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        $('#excel_downloader').click(function () {
            $('#excel_download_form').submit();
        });
    });
</script>
<script>
    $(document).ready(function () {
        console.log('doc_ready');
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

    });
</script>