<style>
    .master{
        width: 100%;
        display: block;
        position: relative;
    }
    .heading{
        position: relative;
        margin:0 auto;
        width:50%;
        text-align: center;
    }
    .h3port {
        font-weight: 700;
        padding:20px 0 0 0;
    }
    .br{
        padding:10px 0 2px 0;
    }
    td.table{
        border:1px solid #000;
    }
    table {
        border-collapse: collapse;
    }
    .report{
        padding:10px 0;
    }
</style>
<div class="master">
    <div class="heading">
        <h3><?=$hotelsettings['ltd']?></h3>
    </div>
    <div class="h3port">
        <?=$TEXT['date']?> : <?=date('Y-m-d H:m:s')?> <br>
        <?=$TEXT['user']?> : <?=$user['firstname']." ".$user['lastname']?>
    </div>



    <div class="report">
        <table class="table-table">
         <tr>
             <td class="table-th"><?=$TEXT['acounting']?></td>
                       <td class="table-th"> <?=$TEXT['start_day']?> <?=date('d/m/Y')?></td>
                       <td class="table-th"> <?=$TEXT['start_month']?> <?=date('m/Y')?></td>
                       <td class="table-th"> <?=$TEXT['start_year']?><?=date('Y')?></td>


                   <td class="table-th"><?=$TEXT['last_day']?> <?=date('d/m/Y',strtotime('-1 year'))?></td>
                   <td class="table-th"><?=$TEXT['last_month']?><?=date('m/Y',strtotime('-1 year'))?></td>
                   <td class="table-th"><?=$TEXT['last_year']?><?=date('Y',strtotime('-1 year'))?></td>

         </tr>
        <tr>
            <td class="table-td"><?=$TEXT['income']?></td>
            <td class="table-td"><?=$dates[date('d')]['price']?></td>
            <td class="table-td"><?=$dates[date('m')]['price']?></td>
            <td class="table-td"><?=$dates[date('Y')]['price']?></td>

            <td class="table-td"><?=$dates_back[date('d')]['price']?></td>
            <td class="table-td"><?=$dates_back[date('m')]['price']?></td>
            <td class="table-td"><?=$dates_back[date('Y',strtotime('-1 Year'))]['price']?></td>

        </tr>
            <tr>
                <td class="table-td"><?=$TEXT['income_service']?></td>
                <td class="table-td"><?=$dates[date('d')]['service']?></td>
                <td class="table-td"><?=$dates[date('m')]['service']?></td>
                <td class="table-td"><?=$dates[date('Y')]['service']?></td>

                <td class="table-td"><?=$dates_back[date('d')]['service']?></td>
                <td class="table-td"><?=$dates_back[date('m')]['service']?></td>
                <td class="table-td"><?=$dates_back[date('Y',strtotime('-1 year'))]['service']?></td>
            </tr>
            <tr>
                <td class="table-td"><?=$TEXT['income_sum']?></td>


                <td class="table-td"><?=$dates[date('d')]['sum']?></td>
                <td class="table-td"><?=$dates[date('m')]['sum']?></td>
                <td class="table-td"><?=$dates[date('Y')]['sum']?></td>

                <td class="table-td"><?=$dates_back[date('d')]['sum']?></td>
                <td class="table-td"><?=$dates_back[date('m')]['sum']?></td>
                <td class="table-td"><?=$dates_back[date('Y',strtotime('-1 Year'))]['sum']?></td>
            </tr>
            <tr> <td colspan="4" class="br"></td></tr>
            <tr>
                <td class="table-td"><?=$TEXT['income_inc']?></td>
                <td class="table-td"><?=$income[date('Y-m')][date('d')]['accommodation_in']?></td>
                <td class="table-td"><?=$income[date('Y-m')]['sum_acc']?></td>
                <td class="table-td"><?=$income['master_sum']?></td>

                <td class="table-td"><?=$income_back[date('Y-m',strtotime('-1 year'))][date('d')]['accommodation_in']?></td>
                <td class="table-td"><?=$income_back[date('Y-m',strtotime('-1 year'))]['sum_acc']?></td>
                <td class="table-td"><?=$income_back['master_sum']?></td>
            </tr>
            <tr>
                <td class="table-td"><?=$TEXT['income_inc_service']?></td>
                <td class="table-td"><?=$income[date('Y-m')][date('d')]['services_in']?></td>
                <td class="table-td"><?=$income[date('Y-m')]['sum_serv']?></td>
                <td class="table-td"><?=$income['master_serv']?></td>

                <td class="table-td"><?=$income_back[date('Y-m',strtotime('-1 year'))][date('d')]['services_in']?></td>
                <td class="table-td"><?=$income_back[date('Y-m',strtotime('-1 year'))]['sum_serv']?></td>
                <td class="table-td"><?=$income_back['master_serv']?></td>
            </tr>
            <tr>
                <td class="table-td"><?=$TEXT['income_inc_sum']?></td>
                <td class="table-td"><?=($income[date('Y-m')][date('d')]['accommodation_in'] + $income[date('Y-m')][date('d')]['services_in']) ?></td>
                <td class="table-td"><?=($income[date('Y-m')]['sum_acc'] + $income[date('Y-m')]['sum_serv']) ?></td>
                <td class="table-td"><?=($income['master_sum'] + $income['master_serv']) ?></td>

                <td class="table-td"><?=($income_back[date('Y-m',strtotime('-1 year'))][date('d')]['accommodation_in'] + $income_back[date('Y-m',strtotime('-1 year'))][date('d')]['services_in']) ?></td>
                <td class="table-td"><?=($income_back[date('Y-m',strtotime('-1 year'))]['sum_acc'] + $income_back[date('Y-m',strtotime('-1 year'))]['sum_serv']) ?></td>
                <td class="table-td"><?=($income_back['master_sum'] + $income_back['master_serv']) ?></td>
            </tr>
            <tr>
                <td class="table-td"><?=$TEXT['wat']?></td>
                <td class="table-td">
                    <?=number_format((($income[date('Y-m')][date('d')]['accommodation_in'] + $income[date('Y-m')][date('d')]['services_in'])-($income[date('Y-m')][date('d')]['accommodation_in'] + $income[date('Y-m')][date('d')]['services_in'])/1.18),2,',','.') ?></td>
                <td class="table-td">
                    <?=number_format((($income[date('Y-m')]['sum_acc'] + $income[date('Y-m')]['sum_serv'])-($income[date('Y-m')]['sum_acc'] + $income[date('Y-m')]['sum_serv'])/1.18),2,',','.') ?></td>
                <td class="table-td">
                    <?=number_format((($income['master_sum'] + $income['master_serv'])-($income['master_sum'] + $income['master_serv'])/1.18),2,',','.') ?></td>


                <td class="table-td">
                    <?=number_format((($income_back[date('Y-m',strtotime('-1 year'))][date('d')]['accommodation_in'] + $income_back[date('Y-m',strtotime('-1 year'))][date('d')]['services_in'])-($income_back[date('Y-m',strtotime('-1 year'))][date('d')]['accommodation_in'] + $income_back[date('Y-m',strtotime('-1 year'))][date('d')]['services_in'])/1.18),2,',','.') ?></td>
                <td class="table-td">
                    <?=number_format((($income_back[date('Y-m',strtotime('-1 year'))]['sum_acc'] + $income_back[date('Y-m',strtotime('-1 year'))]['sum_serv'])-($income_back[date('Y-m',strtotime('-1 year'))]['sum_acc'] + $income_back[date('Y-m',strtotime('-1 year'))]['sum_serv'])/1.18),2,',','.') ?></td>
                <td class="table-td">
                    <?=number_format((($income_back['master_sum'] + $income_back['master_serv'])-($income_back['master_sum'] + $income_back['master_serv'])/1.18),2,',','.') ?></td>
            </tr>
            <tr> <td colspan="4" class="br"></td></tr>

            <tr>
                <td class="table-td"><?=$TEXT['all_rooms']?></td>
                <td class="table-td"><?=$rooms[date('Y-m')][date('d')]['total_rooms_count']?></td>
                <td class="table-td"><?=$rooms[date('Y-m')]['sum_all']?></td>
                <td class="table-td"><?=$rooms['master_sum_all']?></td>
                <td class="table-td"><?=$rooms_back[date('Y-m',strtotime('-1 year'))][date('d')]['total_rooms_count']?></td>
                <td class="table-td"><?=$rooms_back[date('Y-m',strtotime('-1 year'))]['sum_all']?></td>
                <td class="table-td"><?=$rooms_back['master_sum_all']?></td>
            </tr>
            <tr>
                <td class="table-td"><?=$TEXT['used_rooms']?></td>
                <td class="table-td"><?=$rooms[date('Y-m')][date('d')]['used_rooms_count']?></td>
                <td class="table-td"><?=$rooms[date('Y-m')]['sum']?></td>
                <td class="table-td"><?=$rooms['master_sum']?></td>
                <td class="table-td"><?=$rooms_back[date('Y-m',strtotime('-1 year'))][date('d')]['used_rooms_count']?></td>
                <td class="table-td"><?=$rooms_back[date('Y-m',strtotime('-1 year'))]['sum']?></td>
                <td class="table-td"><?=$rooms_back['master_sum']?></td>
            </tr>
            <tr>
                <td class="table-td"><?=$TEXT['used_rooms_per']?></td>
                <td class="table-td"><?=number_format(($rooms[date('Y-m')][date('d')]['used_rooms_count']/$rooms[date('Y-m')][date('d')]['total_rooms_count'])*100,2,',','.')?> %</td>
                <td class="table-td"><?=number_format(($rooms[date('Y-m')]['sum']/$rooms[date('Y-m')]['sum_all'])*100,2,',','.')?> %</td>
                <td class="table-td"><?=number_format(($rooms['master_sum']/$rooms['master_sum_all'])*100,2,',','.')?> %</td>

                <td class="table-td"><?=number_format(($rooms_back[date('Y-m',strtotime('-1 year'))][date('d')]['used_rooms_count']/$rooms_back[date('Y-m',strtotime('-1 year'))][date('d')]['total_rooms_count'])*100,2,',','.')?> %</td>
                <td class="table-td"><?=number_format(($rooms_back[date('Y-m',strtotime('-1 year'))]['sum']/$rooms_back[date('Y-m',strtotime('-1 year'))]['sum_all'])*100,2,',','.')?> %</td>
                <td class="table-td"><?=number_format(($rooms_back['master_sum']/$rooms_back['master_sum_all'])*100,2,',','.')?> %</td>
            </tr>
            <tr> <td colspan="4" class="br"></td></tr>
            <tr>
                <td class="table-td"><?=$TEXT['check_in']?></td>
                <td class="table-td"><?=$in_out_bokings[date('d')]['check_in']?></td>
                <td class="table-td"><?=$in_out_bokings[date('m')]['check_in']?></td>
                <td class="table-td"><?=$in_out_bokings[date('Y')]['check_in']?></td>

                <td class="table-td"><?=$in_out_bokings_back[date('d')]['check_in']?></td>
                <td class="table-td"><?=$in_out_bokings_back[date('m')]['check_in']?></td>
                <td class="table-td"><?=$in_out_bokings_back[date('Y',strtotime('-1 year'))]['check_in']?></td>
              </tr>
            <tr>
                <td class="table-td"><?=$TEXT['check_out']?> </td>
                <td class="table-td"><?=$in_out_bokings[date('d')]['check_out']?></td>
                <td class="table-td"><?=$in_out_bokings[date('m')]['check_out']?></td>
                <td class="table-td"><?=$in_out_bokings[date('Y')]['check_out']?></td>

                <td class="table-td"><?=$in_out_bokings_back[date('d')]['check_out']?></td>
                <td class="table-td"><?=$in_out_bokings_back[date('m')]['check_out']?></td>
                <td class="table-td"><?=$in_out_bokings_back[date('Y',strtotime('-1 year'))]['check_out']?></td>
            </tr>
        </table>
    </div>
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

    });
    </script>