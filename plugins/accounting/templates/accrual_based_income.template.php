<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div id="chart_div" style="border: 1px solid #d1dceb; margin: 0px 10px 10px 0px; padding: 10px; background-color: #ffffff"></div>
<div style="padding-right: 10px">
    <table class="table-table" cellpadding="2" cellspacing="0" >
        <tr>
            <td class="table-th">
                <?= $TEXT['accrual_based_income']['month'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['accrual_based_income']['acc_income_tax_included'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['accrual_based_income']['acc_income_tax_free'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['accrual_based_income']['services_income'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['accrual_based_income']['income'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['accrual_based_income']['tax'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['accrual_based_income']['last_12_month_income'] ?>
            </td>
        </tr>
    <? foreach ($TMPL_reports[$TMPL_year] AS $m=>$report) { $total_income+=$report['income']; ?>
        <tr>
            <td class="table-td">
                <?if($m==date('m')){
                    echo "<b>".$report['month']." ".$TMPL_year."</b>";
                }else{
                    echo $report['month']." ".$TMPL_year;
                }?>
            </td>
            <td class="table-td"><?=$report['acc_income_tax_included']?></td>
            <td class="table-td"><?=$report['acc_income_tax_free']?></td>
            <td class="table-td"><?=$report['services_income']?></td>
            <td class="table-td"><?=$report['income']?></td>
            <td class="table-td"><?=$report['tax']?></td>
            <td class="table-td"><?=$report['last_12_month_income']?></td>
        </tr>
    <?}?>
        <tr>
            <td class="table-td" colspan="4"><?= $TEXT['accrual_based_income']['summary'] ?></td>
            <td class="table-td" style="background-color: #808080; color:#ffffff"><?=$total_income?></td>
            <td class="" colspan="3"></td>
        </tr>
    </table>
</div>

<div style="margin-top: 10px;">
    <div class="but" onclick="window.location='index.php?m=accounting&tab=accrual_based_income&year=<?=($TMPL_year-1)?>';">
    << <?=($TMPL_year-1)?>
    </div>

    <?if($TMPL_year<date('Y')){?>
    <div class="but" onclick="window.location='index.php?m=accounting&tab=accrual_based_income&year=<?=($TMPL_year+1)?>';">
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
          ['', '<?=($TMPL_year-1)?>', '<?=($TMPL_year)?>'],
          <?foreach ($TEXT['months'] AS $k => $v) {?>
          ['<?=$v?>', <?=$TMPL_reports[($TMPL_year-1)][$k]['income']?>, <?=$TMPL_reports[($TMPL_year)][$k]['income']?>],
          <?}?>
        ]);
        var formatter = new google.visualization.NumberFormat({
            fractionDigits: 2,
            suffix: ' <?=$_CONF['system_currency']?>'
        });
        formatter.format(data, 1);
        formatter.format(data, 2);
        var options = {
          chart: {
            title: '<?=($TEXT['accrual_based_income']['chart_title']." ".$TMPL_year)?>',
            subtitle: '<?=$TEXT['accrual_based_income']['chart_subtitle'].' '.($TMPL_year-1).'-'.$TMPL_year ?>'
          },
          vAxis:{format: 'decimal'}
        };
        var chart = new google.charts.Bar(document.getElementById('chart_div'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
</script>



