<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>



<div id="chart_div" style="border: 1px solid #d1dceb; margin: 0px 10px 10px 0px; padding: 10px; background-color: #ffffff"></div>
<div style="padding-right: 10px">
    <table class="table-table" cellpadding="2" cellspacing="0" >
        <tr>
            <td class="table-th">
                <?= $TEXT['annual_income_report']['month'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['annual_income_report']['accommodation_in'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['annual_income_report']['services_in'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['annual_income_report']['out'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['annual_income_report']['balance'] ?>
            </td>
        </tr>
    <? foreach ($TMPL_reports[$TMPL_year] AS $m=>$report) { $total_balance+=$report['balance']; ?>
        <tr>
            <td class="table-td"><?=$report['month']?></td>
            <td class="table-td"><?=$report['accommodation_in']?></td>
            <td class="table-td"><?=$report['services_in']?></td>
            <td class="table-td"><?=$report['out']?></td>
            <td class="table-td"><?=$report['balance']?></td>
        </tr>
    <?}?>
        <tr>
            <td class="" colspan="4">
            </td>
            <td class="table-td" style="background-color: #db4437; color:#ffffff"><?=$total_balance?></td>
        </tr>
    </table>
</div>

<div style="margin-top: 10px;">
    <div class="but" onclick="window.location='index.php?m=accounting&tab=annual_income_report&year=<?=($TMPL_year-1)?>';">
    << <?=($TMPL_year-1)?>
    </div>

    <?if($TMPL_year<date('Y')){?>
    <div class="but" onclick="window.location='index.php?m=accounting&tab=annual_income_report&year=<?=($TMPL_year+1)?>';">
    <?=($TMPL_year+1)?> >>
    </div>
    <?}?>
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

<script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['', '<?=($TMPL_year-2)?>', '<?=($TMPL_year-1)?>', '<?=$TMPL_year?>'],
          <?foreach ($TEXT['months'] AS $k => $v) {?>
          ['<?=$v?>', <?=$TMPL_reports[($TMPL_year-2)][$k]['balance']?>, <?=$TMPL_reports[($TMPL_year-1)][$k]['balance']?>, <?=$TMPL_reports[($TMPL_year)][$k]['balance']?>],
          <?}?>
        ]);
        var formatter = new google.visualization.NumberFormat({
            fractionDigits: 2,
            suffix: ' <?=$_CONF['system_currency']?>'
        });
        formatter.format(data, 1);
        formatter.format(data, 2);
        formatter.format(data, 3);
        var options = {
          chart: {
            title: '<?=($TEXT['annual_income_report']['chart_title']." ".$TMPL_year)?>',
            subtitle: '<?=$TEXT['annual_income_report']['chart_subtitle'].' '.($TMPL_year-2).'-'.$TMPL_year ?>'
          },
          bars: 'vertical',
          vAxis:{format: 'decimal'}
        };
        var chart = new google.charts.Bar(document.getElementById('chart_div'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
</script>



