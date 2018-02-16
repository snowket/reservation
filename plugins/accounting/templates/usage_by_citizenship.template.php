<div style="background:#FFF; border:solid #3A82CC 1px; width:800px;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['transactions']['filter_modal']['title'] ?></b>
    </div>
    <form action="" method="get" id="filter_form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td align="right">
                    <label for="start_date"><?= $TEXT['transactions']['filter_modal']['date'] ?></label>
                </td>
                <td>
                    <input type="text" class="calendar-icon" id="start_date" name="start_date" value="<?=$TMPL_start_date?>"/>
                    <input type="text" class="calendar-icon" id="end_date" name="end_date" value="<?=$TMPL_end_date?>"/>
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
</br>

<div style="width:800px; border: 1px solid #d1dceb; display: inline-block">
    <div class="table-th" style="padding: 4px">
        <?= $TEXT['usage_by_citizenship']['all_country_title'] ?>
    </div>
    <div id="piechart" style="float:left; width:500px; background-color: #f8f8f8"></div>
    <div style="float:right; width: 290px; margin-top: 10px;">
        <table class="table-table" cellpadding="2" cellspacing="0" >
            <tr>
                <td class="table-th">
                    <?= $TEXT['usage_by_citizenship']['country'] ?>
                </td>
                <td class="table-th" width="100">
                    <?= $TEXT['usage_by_citizenship']['guests_count'] ?>
                </td>
            </tr>
        <? foreach ($TMPL_reports['guests'] AS $country=>$count) {$total_guests_count+=$count;
         ?>
            <tr>
                <td class="table-td"><?=$country?></td>
                <td class="table-td"><?=$count?></td>
            </tr>
        <?}?>
            <tr>
                <td class=""></td>
                <td class="table-td" style="background-color: #db4437; color:#ffffff"><?=(int)$total_guests_count?></td>
            </tr>
        </table>
    </div>
</div>
<div style="clear: both"></div>
</br>

<div style="width:800px; border: 1px solid #d1dceb; display: inline-block">
    <div class="table-th" style="padding: 4px">
        <?= $TEXT['usage_by_citizenship']['local_forign_title'] ?>
    </div>
    <div id="piechart2" style="float:left; width:500px; background-color: #f8f8f8"></div>
    <div style="float:right; width: 290px; margin-top: 10px;">

    <table class="table-table" cellpadding="2" cellspacing="0" >
        <tr>
            <td class="table-th">
                <?= $TEXT['usage_by_citizenship']['country'] ?>
            </td>
            <td class="table-th" width="100">
                <?= $TEXT['usage_by_citizenship']['guests_count'] ?>
            </td>
            <td class="table-th" width="100">
                <?= $TEXT['usage_by_citizenship']['guests_count_night'] ?>
            </td>
        </tr>
        <tr>
            <td class="table-td">
                <?= $TEXT['usage_by_citizenship']['local'] ?>
            </td>
            <td class="table-td">
                <?= (int)$TMPL_reports["nights"]['local']['guests'] ?>
            </td>
            <td class="table-td">
                <?= (int)$TMPL_reports["nights"]['local']['nights'] ?>
            </td>
        </tr>
        <tr>
            <td class="table-td">
                <?= $TEXT['usage_by_citizenship']['forign'] ?>
            </td>
            <td class="table-td">
                <?= (int)$TMPL_reports["nights"]['global']['guests'] ?>
            </td>
            <td class="table-td">
                <?= (int)$TMPL_reports["nights"]['global']['nights'] ?>
            </td>
        </tr>
        <tr>
            <td class="">
            </td>
            <td class="table-td" style="background-color: #db4437; color:#ffffff">
                <?= (int)($TMPL_reports["nights"]['local']['guests'])+(int)$TMPL_reports["nights"]['global']['guests'] ?>
            </td>
            <td class="table-td" style="background-color: #db4437; color:#ffffff">
                <?= (int)($TMPL_reports["nights"]['local']['nights'])+(int)$TMPL_reports["nights"]['global']['nights'] ?>
            </td>
        </tr>
    </table>
    </div>

</div>
<div class="referenca">
    <?=$TMPL_start_date?> - დან <?=$TMPL_end_date?> -მდე სტუმრების რაოდენობა იყო : <?=$q?>
</div>
<form method="post" target="_blank" id="excel_download_form">
    <input type="hidden" name="action" value="get_excel">
</form>
<div id="excel_downloader" class="download-excel" style="float:right; margin-right: 10px">Download</div>

<script type="text/javascript">
    $( document ).ready(function() {
       $('#excel_downloader').click(function () {
           $('#excel_download_form').submit();
       });
       $("#start_date").datepicker({

                   maxDate:'<?=$TMPL_end_date?>',
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
                   minDate:'<?=$TMPL_start_date?>',
                   dateFormat: 'yy-mm-dd',
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
    });
</script>

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>

<script type="text/javascript">
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var dataTable = new google.visualization.DataTable();
        dataTable.addColumn('string', 'Task');
        dataTable.addColumn('number', 'Hours per Day');
        // A column for custom tooltip content
        dataTable.addColumn({type: 'string', role: 'tooltip'});
        dataTable.addRows([
        <? foreach ($TMPL_reports['guests'] AS $country=>$count) {?>
              ['<?=$country?>',<?=$count?>,'<?=$country?>\n<?=$count?> (<?=number_format($count/($total_guests_count/100),2)?>%)'],
            <?}?>
        ]);

        var options = {
             legend: 'none',
             backgroundColor: '#f8f8f8'
         };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(dataTable, options);

        var dataTable2 = new google.visualization.DataTable();
                dataTable2.addColumn('string', 'Task');
                dataTable2.addColumn('number', 'Hours per Day');
                // A column for custom tooltip content
                dataTable2.addColumn({type: 'string', role: 'tooltip'});
                dataTable2.addRows([
                      ['<?=$TEXT['usage_by_citizenship']['local']?>',<?=(int)$TMPL_reports["nights"]['local']['nights']?>,'<?=$TEXT['usage_by_citizenship']['local']?>\n<?=(int)$TMPL_reports["nights"]['local']['nights']?> (<?=number_format($TMPL_reports["nights"]['local']['nights']/(($TMPL_reports["nights"]['global']['nights']+$TMPL_reports["nights"]['local']['nights'])/100),2)?>%)'],
                      ['<?=$TEXT['usage_by_citizenship']['forign']?>',<?=$TMPL_reports["nights"]['global']['nights']?>,'<?=$TEXT['usage_by_citizenship']['forign']?>\n<?=$TMPL_reports["nights"]['global']['nights']?> (<?=number_format($TMPL_reports["nights"]['global']['nights']/(($TMPL_reports["nights"]['global']['nights']+$TMPL_reports["nights"]['local']['nights'])/100),2)?>%)'],
                ]);

                var options2 = {
                     legend: 'none',
                     backgroundColor: '#f8f8f8'
                 };
                var chart2 = new google.visualization.PieChart(document.getElementById('piechart2'));
                chart2.draw(dataTable2, options2);





      }
</script>
