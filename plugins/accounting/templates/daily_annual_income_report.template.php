<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<?
$minus_2=date('Y-m', strtotime('-2 month',strtotime($TMPL_year.'-'.$TMPL_month.'-01')));
$minus_2_year=date('Y', strtotime('-2 month',strtotime($TMPL_year.'-'.$TMPL_month.'-01')));
$minus_2_month=date('m', strtotime('-2 month',strtotime($TMPL_year.'-'.$TMPL_month.'-01')));

$minus_1=date('Y-m', strtotime('-1 month',strtotime($TMPL_year.'-'.$TMPL_month.'-01')));
$minus_1_year=date('Y', strtotime('-1 month',strtotime($TMPL_year.'-'.$TMPL_month.'-01')));
$minus_1_month=date('m', strtotime('-1 month',strtotime($TMPL_year.'-'.$TMPL_month.'-01')));

$selected_month=$TMPL_year.'-'.$TMPL_month;

$plus_1_year=date('Y', strtotime('+1 month',strtotime($TMPL_year.'-'.$TMPL_month.'-01')));
$plus_1_month=date('m', strtotime('+1 month',strtotime($TMPL_year.'-'.$TMPL_month.'-01')));
?>

<div id="chart_div" style="border: 1px solid #d1dceb; margin: 0px 10px 10px 0px; padding: 10px; background-color: #ffffff"></div>
<div style="padding-right: 10px">
    <table class="table-table" cellpadding="2" cellspacing="0" >
        <tr>
            <td class="table-th">
                <?= $TEXT['daily_annual_income_report']['day'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['daily_annual_income_report']['accommodation_in'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['daily_annual_income_report']['services_in'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['daily_annual_income_report']['out'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['daily_annual_income_report']['balance'] ?>
            </td>
        </tr>
    <? foreach ($TMPL_reports[$TMPL_year.'-'.$TMPL_month] AS $m=>$report) { $total_balance+=$report['balance']; ?>
        <tr>
            <td class="table-td"><?=date('d-m-Y',strtotime($report['day']))?></td>
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
    <div class="but" onclick="window.location='index.php?m=accounting&tab=daily_annual_income_report&year=<?=$minus_1_year?>&month=<?=$minus_1_month?>';">
        << <?=($TEXT['months'][$minus_1_month]." ".$minus_1_year)?>
    </div>

    <?if(($TMPL_year.'-'.$TMPL_month)<date('Y-m')){?>
        <div class="but" onclick="window.location='index.php?m=accounting&tab=daily_annual_income_report&year=<?=($plus_1_year)?>&month=<?=$plus_1_month?>';">
            <?=($TEXT['months'][$plus_1_month]." ".$plus_1_year)?> >>
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
          ['', '<?=$TEXT['months'][$minus_2_month].' ('.$minus_2_year.')'?>', '<?=$TEXT['months'][$minus_1_month].' ('.$minus_1_year.')'?>', '<?=$TEXT['months'][$TMPL_month].' ('.$TMPL_year.')'?>'],
          <?foreach ($TMPL_reports[$selected_month] AS $k => $v) {?>
            ['<?=date('d', strtotime($v['day']))?>', <?=$TMPL_reports[$minus_2][$k]['balance']?>, <?=$TMPL_reports[$minus_1][$k]['balance']?>, <?=$TMPL_reports[$selected_month][$k]['balance']?>],
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
                        title: '<?=$TEXT['daily_annual_income_report']['chart_title']." ".$TEXT['months'][$TMPL_month].' ('.$TMPL_year.')'?>',
                        subtitle: '<?=$TEXT['daily_annual_income_report']['chart_subtitle'].": ".$TEXT['months'][$minus_2_month]." (".$minus_2_year."), ".$TEXT['months'][$minus_1_month]." (".$minus_1_year."), ".$TEXT['months'][$TMPL_month]." (".$TMPL_year.")"?>'
                      },
            vAxis:{format:'decimal'}


        };
        var chart = new google.charts.Bar(document.getElementById('chart_div'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
</script>

