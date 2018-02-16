

<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['activity_log']['filter_modal']['title'] ?></b>
    </div>
    <form action="" method="get" id="filter_form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td align="right">
                    <label for="keyword"><?= $TEXT['activity_log']['filter_modal']['keyword'] ?></label>
                </td>
                <td>
                    <input type="text"  id="keyword" name="keyword" value="<?=$_GET['keyword']?>"/>
                </td>
                <td align="right">
                    <label for="start_date"><?= $TEXT['activity_log']['filter_modal']['date'] ?></label>
                </td>
                <td>
                    <input type="text" class="calendar-icon" id="start_date" name="start_date" value=""/>
                    <input type="text" class="calendar-icon" id="end_date" name="end_date" value=""/>
                </td>



                <td align="right" >
                    <input class="formButton2" type="submit"
                           value="<?= $TEXT['activity_log']['filter_modal']['submit_filter'] ?>" style="cursor: pointer">
                </td>
            </tr>
        </table>
    </form>
</div>
<div class="center" style="width:100%;padding-top: 20px;">
    <table>
        <tr>
            <td class="table-th">
                #
            </td>
            <th class="table-th">სტუმრის სახელი</th>
            <th class="table-th">გადახდილი თანხა</th>
            <th class="table-th">გადახდის მეთოდი</th>
            <th class="table-th">ტრანზაქციის ID</th>
            <th class="table-th">სტატუსი</th>
            <th class="table-th">დანიშნულება</th>
            <th class="table-th">სტუმრის IP</th>
            <th class="table-th">Postback ინფო</th>
        </tr>
        <? $count=1; foreach($TMPL_logs as $log){ ?>
            <tr>

                <td class="table-td"><?=$count?></td>
                <td class="table-td"><?=$log['first_name']." ".$log['last_name']?></td>
                <td class="table-td"><?=$log['amount']?></td>
                <td class="table-td"><?=$log['method']?></td>
                <td class="table-td"><?=$log['transaction_id']?></td>
                <td class="table-td"><?=$log['tr_status']?></td>
                <td class="table-td"><?=$log['destination']?></td>
                <td class="table-td"><?=$log['user_ip']?></td>
                <td class="table-td"><?
                    $rr=$log['postback_message']; echo $rr['3DSECURE'];?></td>
            </tr>
        <? $count++; } ?>
    </table>
</div>
<div id="excel_downloader" class="download-excel" style="float:right">
    Download
</div>


<script type="text/javascript">
    $(document).ready(function () {

        $("#start_date").datepicker({
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

        $("#start_date").datepicker("setDate", '<?=(isset($_GET['start_date'])&&$_GET['start_date']!="")?$_GET['start_date']:date("Y-m-01") ?>');
        $("#end_date").datepicker("setDate", '<?=(isset($_GET['end_date'])&&$_GET['end_date']!="")?$_GET['end_date']:date("Y-m-d") ?>');

        $('#excel_downloader').click(function () {
            $('#excel_download_form').submit();
        });
    });

   </script>