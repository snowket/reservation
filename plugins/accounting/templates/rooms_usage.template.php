<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
<script type='text/javascript'>
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);


    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['', '<?=$last_year?>', '<?=$current_year?>'],
           <? foreach ($usedRoomsCount as $k=>$v) {
            $last_year_day=str_replace($current_year, $last_year, $k);
            $chart_1.="['".date('d',strtotime($k))."',  ".$usedRoomsCount_last_year[$last_year_day]['percent'].", ".$v['percent']."],";
        }
        $chart_1.="]);

        var options = {
            chart: {

            },
            vAxis: {
                minValue: 0,
                maxValue: 100,
                format:'percent'
            }
        };


        var chart = new google.charts.Bar(document.getElementById('curve_chart'));
        chart.draw(data, options);

    }";



echo $chart_1;
?>
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
</script>
<div style='background-color:#FFFFFF; border:solid #3A82CC 1px; margin-right: 10px'>
    <div class='caps_bold dashboard_table_header'><?=$TEXT['rooms_usage_report']['title']?></div>
    <div id='curve_chart' style=' height: 200px;  margin: 0px 10px 10px 0px; padding: 10px;'></div>
</div>

<div style="padding:10px 10px 0px 0px">
    <table class="table-table" cellpadding="2" cellspacing="0" >
        <tr>
            <td class="table-th">
                <?= $TEXT['rooms_usage_report']['date'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['rooms_usage_report']['used_rooms_count'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['rooms_usage_report']['total_rooms_count'] ?>
            </td>
            <td class="table-th">
                <?= $TEXT['rooms_usage_report']['percent'] ?>
            </td>
        </tr>
        <? foreach ($usedRoomsCount as $k=>$v) {
            $total_used_rooms_count+=$v['used_rooms_count'];
            $total_rooms_count+=$v['total_rooms_count'];
            ?>
            <tr>
                <td class="table-td"><?=$k?></td>
                <td class="table-td"><?=$v['used_rooms_count']?></td>
                <td class="table-td"><?=$v['total_rooms_count']?></td>
                <td class="table-td"><?=$v['percent']?>%</td>
            </tr>
        <?}?>
        <tr>
            <td class="" colspan="3">
            </td>
            <td class="table-td" style="background-color: #db4437; color:#ffffff"><?=number_format(($total_used_rooms_count/($total_rooms_count/100)),2)."%"?></td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td class="table-td"> <div id="excel_downloader" class="download-excel" style="float:right; margin-right: 10px">
    Download
</div></td>
        </tr>
    </table>
</div>
<div style="margin-top: 10px;">
    <div class="but" onclick="window.location='index.php?m=accounting&tab=rooms_usage&year=<?=$minus_1_year?>&month=<?=$minus_1_month?>';">
        << <?=($TEXT['months'][$minus_1_month]." ".$minus_1_year)?>
    </div>


        <div class="but" onclick="window.location='index.php?m=accounting&tab=rooms_usage&year=<?=($plus_1_year)?>&month=<?=$plus_1_month?>';">
            <?=($TEXT['months'][$plus_1_month]." ".$plus_1_year)?> >>
        </div>

</div>
<form method="post" target="_blank" id="excel_download_form">
    <input type="hidden" name="action" value="get_excel">
</form>
<script type="text/javascript">
    $( document ).ready(function() {
        $('#excel_downloader').click(function () {
            $('#excel_download_form').submit();
        });
    });
</script>