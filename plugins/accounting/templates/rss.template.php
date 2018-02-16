<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.1/datepicker.css" />
<script src="./js/new_datepicker.js"></script>
<style media="screen">

  .rev_th{
    border-right:1px solid #000;
    background: #3a82cc;
    color:white;
    height: 53px;
    width: 115px;
  }

  .rev_td{
    text-align: center;
    vertical-align: middle;
  }
  .rev_td_th{
    padding:0 10px;
    color:#3a82cc;
  }
  .th_rev_th{
    width: 33%;
    color:white;
    border-right: 1px solid #000000;
  }
  .bd_td{
    border-left: 1px solid red;
    border-right: 1px solid red;
    width:60px;
  }
  .add_budget_trigger{
    background:#3a82cc;
    color:#fff;
    border:none;
    padding:5px 10px;
  }
  .rev_th_child table tr td{
    border-right: 1px solid rgba(0, 0, 0, 0.3);
    width:75px;
  }
  .rev_th_child table{
        border-collapse: collapse;
        height: 100%;
  }
  
  th.th_rev_th:last-of-type {
    border: none;
}
</style>

<h2  style="cursor: default; user-select: none;margin: 10px 0 20px 0; -webkit-font-smoothing: antialiased; font-family: Verdana,Arial; font-size: 14px;color:#454545" >
<?php
      echo $TEXT['tab']['reports']['sub'][$_GET['tab']]." (".$date->format('d-m-Y').")";
 ?>
</h2>
<div  style="border: 1px solid #d1dceb; margin: 0px 10px 10px 0px; padding: 10px; background-color: #ffffff">
      <div style="padding:10px;">
        <form  method="get" id="date_change_form">
          <input type="hidden" name="m" value="<?=$_GET['m']?>">
          <input type="hidden" name="tab" value="<?=$_GET['tab']?>">
          <label for="date_change"><b> <?=$TEXT['b_s_day']?> </b></label>
          <input type="text" name="date" id="date_change" class="calendar-icon hasDatepicker" value="<?=$date->format('d-m-Y')?>">
        </form>
        <script>
        "use strict";
        var datepicker = $.fn.datepicker.noConflict();
        $.fn.bootstrapDP = datepicker;
          $(document).ready(function(){
            console.log('date change ready');
            $( "#date_change" ).bootstrapDP({
    					    format:'dd-mm-yyyy',
    					    changeMonth: true,
    					    numberOfMonths: 2,
    					    showOn: "both",
    					    defaultDate: null,
    					    autoHide: true,
    					    showButtonPanel: true,
    				}).on("change", function () {
                  $('#date_change_form').submit();
        	    });
          })
        </script>
      </div>
      <table>
        <tr>
          <td>
            <table style="border-collapse: collapse;" border="1" >
              <thead>
                <tr>
                  <th class="rev_th"><?=$TEXT['rss']['daily_rev']?> </th>
                  <th class="rev_th"><?=$TEXT['rss']['sel_day']?></th>
                <th class="rev_th rev_th_child">
                  <table border="0" style="width:100%;height:100%;">
                    <tr>
                      <th class="th_rev_th"><?=$TEXT['rss']['budget']?></th>
                      <th class="th_rev_th"><?=$TEXT['rss']['diff']?> ₾</th>
                      <th class="th_rev_th"><?=$TEXT['rss']['diff']?> %</th>
                    </tr>
                  </table>
                </th>
                <th class="rev_th">
                <?=$TEXT['rss']['l_year']?>
                </th>
                <th class="rev_th rev_th_child">
                  <table border="0" style="width:100%;">
                    <tr>
                      <th class="th_rev_th"><?=$TEXT['rss']['budget']?></th>
                      <th class="th_rev_th"><?=$TEXT['rss']['diff']?> ₾</th>
                      <th class="th_rev_th"><?=$TEXT['rss']['diff']?> %</th>
                    </tr>
                  </table>
                </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="rev_td_th">
                  <?=$TEXT['rss']['income']?>
                  </td>
                  <td class="rev_td">
                    <? $ACTD=($reportings['today']['day_report']['accommodation_in']+$reportings['today']['day_report']['services_in']); ?>
                    <?=$ACTD?>
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                          <? $BGD=($budgets[$date->format('Y')]['prices'][$date->format('m')-1]['st_budget'])/$date->format('t'); ?>
                          <?=number_format($BGD,2,'.','')?>
                        </td>
                        <td class="rev_td">
                          <?
                            $charge=$ACTD-$BGD;
                            if($charge>=0){
                               echo '+ '.number_format($charge,2,'.','');
                            }else{
                               echo number_format($charge,2,'.','');
                            }
                            ?>
                        </td>
                        <td class="rev_td">
                          <?
                          $chargeP=$charge/$BGD;
                          $chargeP=$chargeP*100;
                            if($charge>=0){
                               echo '+ '.number_format($chargeP,2,'.','');
                            }else{
                               echo number_format($chargeP,2,'.','');
                            }
                            ?>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td class="rev_td">
                    <? $ACTD=($reportings['last_year']['day_report']['accommodation_in']+$reportings['last_year']['day_report']['services_in']); ?>
                    <?=$ACTD?>
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                          <? $BGD=($budgets[$last_year->format('Y')]['prices'][$last_year->format('m')-1]['st_budget'])/$last_year->format('t'); ?>
                          <?=number_format($BGD,2,'.','')?>
                        </td>
                        <td class="rev_td">
                          <?
                            $charge=$ACTD-$BGD;
                            if($charge>0){
                               echo '+ '.number_format($charge,2,'.','');
                            }else{
                               echo number_format($charge,2,'.','');
                            }
                            ?>
                        </td>
                        <td class="rev_td">
                          <?
                          $chargeP=$charge/$BGD;
                          $chargeP=$chargeP*100;
                            if($charge>0){
                               echo '+ '.number_format($chargeP,2,'.','');
                            }else{
                               echo number_format($chargeP,2,'.','');
                            }
                            ?>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td class="rev_td_th">
                    <?=$TEXT['rss']['a_rooms']?>
                  </td>
                  <td class="rev_td">
                    <?=count($reportings['today']['getAvailableRoomsCount'])?>
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                            <?=count($reportings['today']['getAvailableRoomsCount'])?>
                        </td>
                        <td class="rev_td">
                          <?=count($reportings['today']['getAvailableRoomsCount'])?>
                        </td>
                        <td class="rev_td">
                            <?=count($reportings['today']['getAvailableRoomsCount'])?>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td class="rev_td">
                    <?=count($reportings['last_year']['getAvailableRoomsCount'])?>
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                            <?=count($reportings['last_year']['getAvailableRoomsCount'])?>
                        </td>
                        <td class="rev_td">
                          <?=count($reportings['last_year']['getAvailableRoomsCount'])?>
                        </td>
                        <td class="rev_td">
                            <?=count($reportings['last_year']['getAvailableRoomsCount'])?>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td class="rev_td_th">
                    <?=$TEXT['rss']['o_rooms']?>
                  </td>
                  <td class="rev_td">
                    <?=count($reportings['today']['restrictedRooms'])?>
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                            <?=count($reportings['today']['restrictedRooms'])?>
                        </td>
                        <td class="rev_td">
                            <?=count($reportings['today']['restrictedRooms'])?>
                        </td>
                        <td class="rev_td">
                            <?=count($reportings['today']['restrictedRooms'])?>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td class="rev_td">
                    <?=count($reportings['last_year']['restrictedRooms'])?>
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                            <?=count($reportings['last_year']['restrictedRooms'])?>
                        </td>
                        <td class="rev_td">
                            <?=count($reportings['last_year']['restrictedRooms'])?>
                        </td>
                        <td class="rev_td">
                            <?=count($reportings['last_year']['restrictedRooms'])?>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td class="rev_td_th">
                    <?=$TEXT['rss']['r_rooms']?>
                  </td>
                  <td class="rev_td">
                      <?=$reportings['today']['sold_rooms']['count']?>
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                            <?=$reportings['today']['sold_rooms']['count']?>
                        </td>
                        <td class="rev_td">
                          <?=$reportings['today']['sold_rooms']['count']?>
                        </td>
                        <td class="rev_td">
                          <?=$reportings['today']['sold_rooms']['count']?>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td class="rev_td">
                      <?=$reportings['last_year']['sold_rooms']['count']?>
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                            <?=$reportings['last_year']['sold_rooms']['count']?>
                        </td>
                        <td class="rev_td">
                          <?=$reportings['last_year']['sold_rooms']['count']?>
                        </td>
                        <td class="rev_td">
                          <?=$reportings['last_year']['sold_rooms']['count']?>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td class="rev_td_th">
                    <?=$TEXT['rss']['oc_rooms']?>
                  </td>
                  <td class="rev_td">
                    <? $OC=$reportings['today']['sold_rooms']['count']/count($reportings['today']['getAvailableRoomsCount']); ?>
                    <?=number_format(($OC)*100,2,'.','')?>%
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                          <?=number_format(($OC)*100,2,'.','')?>%
                        </td>
                        <td class="rev_td">
                          <?=number_format(($OC)*100,2,'.','')?>%
                        </td>
                        <td class="rev_td">
                          <?=number_format(($OC)*100,2,'.','')?>%
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td class="rev_td">
                    <? $OC=$reportings['last_year']['sold_rooms']['count']/count($reportings['last_year']['getAvailableRoomsCount']); ?>
                    <?=number_format(($OC)*100,2,'.','')?>%
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                          <?=number_format(($OC)*100,2,'.','')?>%
                        </td>
                        <td class="rev_td">
                          <?=number_format(($OC)*100,2,'.','')?>%
                        </td>
                        <td class="rev_td">
                          <?=number_format(($OC)*100,2,'.','')?>%
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td class="rev_td_th">
                  <?=$TEXT['rss']['adr']?>
                  </td>
                  <td class="rev_td">
                    <? $ADR=(($reportings['today']['day_report']['accommodation_in']+$reportings['today']['day_report']['services_in'])/$reportings['today']['sold_rooms']['count']); ?>
                      <?=number_format($ADR,2,'.','')?>
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                            <?=number_format($ADR,2,'.','')?>
                        </td>
                        <td class="rev_td">
                            <?=number_format($ADR,2,'.','')?>
                        </td>
                        <td class="rev_td">
                            <?=number_format($ADR,2,'.','')?>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td class="rev_td">
                    <? $ADR=(($reportings['last_year']['day_report']['accommodation_in']+$reportings['last_year']['day_report']['services_in'])/$reportings['lasht_year']['sold_rooms']['count']); ?>
                      <?=number_format($ADR,2,'.','')?>
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                            <?=number_format($ADR,2,'.','')?>
                        </td>
                        <td class="rev_td">
                            <?=number_format($ADR,2,'.','')?>
                        </td>
                        <td class="rev_td">
                            <?=number_format($ADR,2,'.','')?>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td class="rev_td_th">
                    <?=$TEXT['rss']['rev_par']?>
                  </td>
                  <td class="rev_td">
                    <? $Rev=(($reportings['today']['day_report']['accommodation_in']+$reportings['today']['day_report']['services_in'])/count($reportings['today']['getAvailableRoomsCount']));?>
                      <?=number_format($Rev,2,'.','')?>
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                            <?=number_format($Rev,2,'.','')?>
                        </td>
                        <td class="rev_td">
                            <?=number_format($Rev,2,'.','')?>
                        </td>
                        <td class="rev_td">
                            <?=number_format($Rev,2,'.','')?>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td class="rev_td">
                    <? $Rev=(($reportings['last_year']['day_report']['accommodation_in']+$reportings['last_year']['day_report']['services_in'])/count($reportings['last_year']['getAvailableRoomsCount']));?>
                      <?=number_format($Rev,2,'.','')?>
                  </td>
                  <td class="rev_th_child">
                    <table style="width:100%;">
                      <tr>
                        <td class="rev_td">
                            <?=number_format($Rev,2,'.','')?>
                        </td>
                        <td class="rev_td">
                            <?=number_format($Rev,2,'.','')?>
                        </td>
                        <td class="rev_td">
                            <?=number_format($Rev,2,'.','')?>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <h2  style="cursor: default; user-select: none;margin: 10px 0 20px 0; -webkit-font-smoothing: antialiased; font-family: Verdana,Arial; font-size: 14px;color:#454545" >
            <?php
              echo $date->copy()->startOfMonth()->format('d-m-Y')." / " .$date->format('d-m-Y')." ". $TEXT['tab']['reports']['sub'][$_GET['tab']] ." ".$TEXT['mdt_charge'] ;
             ?>
            </h2>
            <table style="border-collapse: collapse;" border="1" >
              <thead>
                <tr>
                  <th class="rev_th"><?=$TEXT['rss']['mtd']?></th>
                  <th class="rev_th"><?=$TEXT['rss']['mtd_day']?></th>
                <th class="rev_th rev_th_child">
                  <table border="0" style="width:100%;">
                    <tr>
                      <th class="th_rev_th"><?=$TEXT['rss']['budget']?></th>
                      <th class="th_rev_th"><?=$TEXT['rss']['diff']?> ₾</th>
                      <th class="th_rev_th"><?=$TEXT['rss']['diff']?> %</th>
                    </tr>
                  </table>
                </th>
                <th class="rev_th">
                  <?=$TEXT['rss']['mtd_l_year']?>
                </th>
                <th class="rev_th rev_th_child">
                  <table border="0" style="width:100%;">
                    <tr>
                      <th class="th_rev_th"><?=$TEXT['rss']['budget']?></th>
                      <th class="th_rev_th"><?=$TEXT['rss']['diff']?> ₾</th>
                      <th class="th_rev_th"><?=$TEXT['rss']['diff']?> %</th>
                    </tr>
                  </table>
                </th>
                </tr>
              </thead>
              <tr>
                <td class="rev_td_th">
                  <?=$TEXT['rss']['income']?>
                </td>
                <td class="rev_td">
                  <? $ACTD=($Mtdreportings['today']['day_report']); ?>
                  <?=$ACTD?>
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;border-collapse:collapse;">
                    <tr>
                      <td class="rev_td" style="">
                        <? $BGD=($budgets[$date->format('Y')]['prices'][$date->format('m')-1]['st_budget'])/$date->format('t'); $BGD=$BGD*$days;?>
                        <?=number_format($BGD,2,'.','')?>
                      </td>
                      <td class="rev_td" style="">
                        <?
                          $charge=$ACTD-$BGD;
                          if($charge>0){
                             echo '+ '.number_format($charge,2,'.','');
                          }else{
                             echo number_format($charge,2,'.','');
                          }
                          ?>
                      </td>
                      <td class="rev_td" style="">
                        <?
                        $chargeP=abs($charge)/$BGD;
                        $chargeP=$chargeP*100;
                          if($charge>=0){
                             echo '+ '.number_format($chargeP,2,'.','');
                          }else{
                             echo number_format($chargeP,2,'.','');
                          }
                          ?>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="rev_td">
                  <? $ACTD=($Mtdreportings['last_year']['day_report']); ?>
                  <?=$ACTD?>
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;">
                    <tr>
                      <td class="rev_td x">
                        <? $BGD=($budgets[$last_year->format('Y')]['prices'][$last_year->format('m')-1]['st_budget'])/$last_year->format('t'); $BGD=$BGD*$days;?>
                        <?=number_format($BGD,2,'.','')?>
                      </td>
                      <td class="rev_td">
                        <?
                          $charge=$ACTD-$BGD;
                          if($charge>=0){
                             echo '+ '.number_format($charge,2,'.','');
                          }else{
                             echo number_format($charge,2,'.','');
                          }
                          ?>
                      </td>
                      <td class="rev_td">
                        <?
                        $chargeP=$charge/$BGD;
                        $chargeP=$chargeP*100;
                          if($charge>=0){
                             echo '+ '.number_format($chargeP,2,'.','');
                          }else{
                             echo number_format($chargeP,2,'.','');
                          }
                          ?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td class="rev_td_th">
                  <?=$TEXT['rss']['a_rooms']?>
                </td>
                <td class="rev_td">
                  <? $aaRooms=$Mtdreportings['today']['getAvailableRoomsCount']; ?>
                  <?=$aaRooms?>
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;">
                    <tr>
                      <td class="rev_td">
                                <?=$aaRooms?>
                      </td>
                      <td class="rev_td">
                            <?=$aaRooms?>
                      </td>
                      <td class="rev_td">
                              <?=$aaRooms?>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="rev_td">
                        <?=$aaRooms?>
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;">
                    <tr>
                      <td class="rev_td">
                              <?=$aaRooms?>
                      </td>
                      <td class="rev_td">
                          <?=$aaRooms?>
                      </td>
                      <td class="rev_td">
                                <?=$aaRooms?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td class="rev_td_th">
                  <?=$TEXT['rss']['o_rooms']?>
                </td>
                <td class="rev_td">
                  <? $ooRooms=$Mtdreportings['today']['restrictedRooms']; ?>
                  <?=$ooRooms?>
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;">
                    <tr>
                      <td class="rev_td">
                        <?=$ooRooms?>
                      </td>
                      <td class="rev_td">
                        <?=$ooRooms?>
                      </td>
                      <td class="rev_td">
                          <?=$ooRooms?>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="rev_td">
                  <? $ooRooms_l=$Mtdreportings['last_year']['restrictedRooms'];?>
                  <?=$ooRooms_l?>
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;">
                    <tr>
                      <td class="rev_td">
                          <?=$ooRooms_l?>
                      </td>
                      <td class="rev_td">
                        <?=$ooRooms_l?>
                      </td>
                      <td class="rev_td">
                          <?=$ooRooms_l?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td class="rev_td_th">
                  <?=$TEXT['rss']['r_rooms']?>
                </td>
                <td class="rev_td">
                  <?=$Mtdreportings['today']['sold_rooms']?>
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;">
                    <tr>
                      <td class="rev_td">
                          <?=$Mtdreportings['today']['sold_rooms']?>
                      </td>
                      <td class="rev_td">
                          <?=$Mtdreportings['today']['sold_rooms']?>
                      </td>
                      <td class="rev_td">
                          <?=$Mtdreportings['today']['sold_rooms']?>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="rev_td sss">
                  <?=$Mtdreportings['last_year']['sold_rooms']?>
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;">
                    <tr>
                      <td class="rev_td">
                          <?=$Mtdreportings['last_year']['sold_rooms']?>
                      </td>
                      <td class="rev_td">
                          <?=$Mtdreportings['last_year']['sold_rooms']?>
                      </td>
                      <td class="rev_td">
                          <?=$Mtdreportings['last_year']['sold_rooms']?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <tr>
                <td class="rev_td_th">
                  <?=$TEXT['rss']['oc_rooms']?>
                </td>
                <td class="rev_td">
                  <? $OC=$Mtdreportings['today']['sold_rooms']/$Mtdreportings['today']['getAvailableRoomsCount']; ?>

                  <?=number_format(($OC)*100,2,'.','')?>%
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;">
                    <tr>
                      <td class="rev_td">
                        <?=number_format(($OC)*100,2,'.','')?>%
                      </td>
                      <td class="rev_td">
                        <?=number_format(($OC)*100,2,'.','')?>%
                      </td>
                      <td class="rev_td">
                        <?=number_format(($OC)*100,2,'.','')?>%
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="rev_td">
                  <? $OC=$Mtdreportings['last_year']['sold_rooms']/$Mtdreportings['last_year']['getAvailableRoomsCount']; ?>
                  <?=number_format(($OC)*100,2,'.','')?>%
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;">
                    <tr>
                      <td class="rev_td">
                        <?=number_format(($OC)*100,2,'.','')?>%
                      </td>
                      <td class="rev_td">
                        <?=number_format(($OC)*100,2,'.','')?>%
                      </td>
                      <td class="rev_td">
                        <?=number_format(($OC)*100,2,'.','')?>%
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td class="rev_td_th">
                <?=$TEXT['rss']['adr']?>
                </td>
                <td class="rev_td">
                  <? $ADR=(($Mtdreportings['today']['day_report'])/$Mtdreportings['today']['sold_rooms']); ?>
                    <?=number_format($ADR,2,'.','')?>
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;">
                    <tr>
                      <td class="rev_td">
                          <?=number_format($ADR,2,'.','')?>
                      </td>
                      <td class="rev_td">
                          <?=number_format($ADR,2,'.','')?>
                      </td>
                      <td class="rev_td">
                          <?=number_format($ADR,2,'.','')?>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="rev_td">
                  <? $ADR=(($Mtdreportings['last_year']['day_report'])/$Mtdreportings['last_year']['sold_rooms']); ?>
                    <?=number_format($ADR,2,'.','')?>
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;">
                    <tr>
                      <td class="rev_td">
                          <?=number_format($ADR,2,'.','')?>
                      </td>
                      <td class="rev_td">
                          <?=number_format($ADR,2,'.','')?>
                      </td>
                      <td class="rev_td">
                          <?=number_format($ADR,2,'.','')?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td class="rev_td_th">
                  <?=$TEXT['rss']['rev_par']?>
                </td>
                <td class="rev_td">
                  <? $Rev=(($Mtdreportings['today']['day_report'])/$Mtdreportings['today']['getAvailableRoomsCount']);?>
                    <?=number_format($Rev,2,'.','')?>
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;">
                    <tr>
                      <td class="rev_td">
                          <?=number_format($Rev,2,'.','')?>
                      </td>
                      <td class="rev_td">
                          <?=number_format($Rev,2,'.','')?>
                      </td>
                      <td class="rev_td">
                          <?=number_format($Rev,2,'.','')?>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="rev_td">
                  <? $Rev=(($Mtdreportings['last_year']['day_report'])/$Mtdreportings['last_year']['getAvailableRoomsCount']);?>
                    <?=number_format($Rev,2,'.','')?>
                </td>
                <td class="rev_th_child">
                  <table style="width:100%;">
                    <tr>
                      <td class="rev_td">
                          <?=number_format($Rev,2,'.','')?>
                      </td>
                      <td class="rev_td">
                          <?=number_format($Rev,2,'.','')?>
                      </td>
                      <td class="rev_td">
                          <?=number_format($Rev,2,'.','')?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
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
