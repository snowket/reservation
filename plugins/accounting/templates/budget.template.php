<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.1/datepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.1/datepicker.js"></script>
<style media="screen">

  .rev_th{
    border:1px solid #000;
    background: #3a82cc;
    color:white;
    height: 53px;
    padding:10px 0;
    min-width: 115px;
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
    margin-bottom: 10px;
    float:right;
  }
  .mmt{
    width: 93px;
  }
</style>
<h2  style="cursor: default; user-select: none;margin: 10px 0 20px 0; -webkit-font-smoothing: antialiased; font-family: Verdana,Arial; font-size: 14px;color:#454545" >
<?php
  if($year_ar[0]==$year_ar[1]){
      echo $year_ar[0]." ".$TEXT['b_year']." ".$TEXT['tab']['reports']['sub'][$_GET['tab']];
  }else{
    echo $year_ar[0]." - ".$year_ar[1]." ".$TEXT['b_years']." ".$TEXT['tab']['reports']['sub'][$_GET['tab']];
  }


 ?>
</h2>
<div  style="border: 1px solid #d1dceb; margin: 0px 10px 10px 0px; padding: 10px; background-color: #ffffff">
  <div style="float: left;
    width: 100%;">
    <form  method="get" id="date_change_form" style="float:left;">
      <input type="hidden" name="m" value="<?=$_GET['m']?>">
      <input type="hidden" name="tab" value="<?=$_GET['tab']?>">
      <label for="date_change"><b><?=$TEXT['b_s_years']?> </b></label>
      <input type="text" name="start_date" id="date_changex" class="calendar-icon hasDatepicker" value="<?=$year_ar[0]?>">
      <input type="text" name="end_date" id="date_changey" class="calendar-icon hasDatepicker" value="<?=$year_ar[1]?>">
    </form>
    <script>
    "use strict";
    var datepicker = $.fn.datepicker.noConflict();
    $.fn.bootstrapDP = datepicker;
      $(document).ready(function(){
        console.log('date change ready');
        $( "#date_changex" ).bootstrapDP({
              format:'yyyy',
              changeMonth: true,
              numberOfMonths: 2,
              showOn: "both",
              defaultDate: null,
              autoHide: true,
              showButtonPanel: true,
        }).on("change", function () {
              $('#date_change_form').submit();
          });
          $( "#date_changey" ).bootstrapDP({
                format:'yyyy',
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
    <button type="button" name="button" class="add_budget_trigger"><?=$TEXT['add_budget']?></button>
  </div>



<table style="width:100%;color:#3a82cc;    border-collapse: collapse;" border="1" >
  <tr>
    <th>
      <?=$TEXT['start_year']?>
    </th>
    <?php foreach ($TEXT['months_budget'] as $key => $value): ?>
      <th style="width:86px;">
        <?=$value?>
      </th>
    <?php endforeach; ?>
    <th style="width:86px;">

    </th>
  </tr>
  <?php foreach ($budgets as $key => $value): ?>
    <form class="" method="post">
      <input type="hidden" name="m" value="<?=$_GET['m']?>">
      <input type="hidden" name="tab" value="<?=$_GET['tab']?>">
      <input type="hidden" name="year" value="<?=$key?>">
      <input type="hidden" name="action" value="edit_budget">
      <tr>
        <th>
          <?=$key?>
        </th>
        <td colspan="12" style="    vertical-align: middle;">
          <table style="width:100%;border-collapse: collapse;color:#3a82cc;">
            <tr>
              <?php foreach ($value['prices'] as $k => $m): ?>
                <td class="bd_td" >
                  <table  style="width:100%;color:#3a82cc;">

                    <tr>
                        <td style="text-align:center;color:#000;" >
                            <input type="number" name="sm_budget[<?=$key?>][<?=$m['st_month']?>]" value="<?=$m['st_budget']?>" style="width:75px;">
                        </td>
                      </tr>
                  </table>
                </td>
              <?php endforeach; ?>
            </tr>
          </table>
        </td>
        <td >
          <button type="submit" onclick="return confirm('დარწმუნებული ხართ რომ გსურთ (<?=$key?>) ბიუჯეტის რედაქტირება?')" name="button" class="budget_edit_trigger" style="width: 100%;"><?=$TEXT['edit']?></button>
        </td>
      </tr>
    </form>
  <?php endforeach; ?>
</table>
</div>
<h2  style="cursor: default; user-select: none;margin: 10px 0 20px 0; -webkit-font-smoothing: antialiased; font-family: Verdana,Arial; font-size: 14px;color:#454545" >
<?php
  if($year_ar[0]==$year_ar[1]){
      echo $year_ar[0]." ".$TEXT['b_year']." ".$TEXT['check_budget'];
  }else{
      echo $year_ar[0]." - ".$year_ar[1]." ".$TEXT['b_years']." ".$TEXT['check_budgets'];
  }


 ?>
</h2>
<div  style="border: 1px solid #d1dceb; margin: 0px 10px 10px 0px; padding: 10px; background-color: #ffffff">
  <table style="width:100%;color:#3a82cc;    border-collapse: collapse;" border="1">
    <tr>
      <th>
        <?=$TEXT['start_year']?>
      </th>
      <?php foreach ($TEXT['months_budget'] as $key => $value): ?>
        <th class="mmt">
          <?=$value?>
        </th>
      <?php endforeach; ?>
    </tr>
    <?php foreach ($budgets as $key => $value): ?>
        <tr>
          <th>
            <?=$key?>
          </th>
          <td colspan="12" style="    vertical-align: middle;">
            <table style="width:100%;border-collapse: collapse;color:#3a82cc;">
              <tr>
                <?php foreach ($value['prices'] as $k => $m): ?>
                  <td class="bd_td" >
                    <table  style="width:100%;color:#3a82cc;border-collapse: collapse;">
                      <tr>
                          <td title="დაგეგმილი ბიუჯეტი" style="text-align:center;color:#000;padding: 5px 0;    border-bottom: 1px solid rgba(0, 0, 0, 0.3);" >
                              <?=$m['st_budget']?>
                          </td>
                        </tr>
                        <tr>
                          <?php $vg=$k+1;
                          $qs=$report[$key][str_pad($vg, 2, '0', STR_PAD_LEFT)]['income'];
                          if($qs<=0){
                            $f='blue';
                          }else{
                            $f='green';
                          }
                          $qs = $qs <= 0 ? $qs : '+'.$qs ;
                          ?>
                            <td title="შემოსავალი" style="text-align:center;color:<?=$f?>;  padding: 5px 0;   border-bottom: 1px solid rgba(0, 0, 0, 0.3);" >
                              <?=$qs?>
                            </td>
                          </tr>
                          <tr>
                            <?php
                            $r=$m['st_budget']-$qs;
                            if($r>0){
                              $c='red';
                            }else{
                              $c='green';
                            }
                            $r = $r <= 0 ? '+'.abs($r) : -$r ;
                            ?>
                              <td title="სხვაობა" style="text-align:center;color:<?=$c?>; padding: 5px 0;    border-bottom: 1px solid #000;" >
                                <?=$r?>
                              </td>
                            </tr>
                    </table>
                  </td>
                <?php endforeach; ?>
              </tr>
            </table>
          </td>
        </tr>
    <?php endforeach; ?>
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
<script>
$('.add_budget_trigger').click(function(){
  $("#add_budget_modal").dialog({
      resizable: false,
      width: 400,
      height: 500,
      modal: true,
      buttons: {
          "Save": function () {
              $("#add_budget_form").submit();
              $(this).dialog("close");

          },
          "Close": function () {
              $(this).dialog("close");
          }
      },
      create: function (event, ui) {
          $(event.target).parent().css('position', 'fixed');
      },
      position: { my: 'top', at: 'top+200' },
  });
  $( ".focus_class" )[0].focus();
})
</script>

<!-- MODALS  -->
    <div id="add_budget_modal" style="display: none" title="ბიუჯეტის დამატება">
        <form  method="post" id="add_budget_form" >
          <input type="hidden" name="action" value="save_budget">
            <table id="form_table">
              <tr>
                  <th>წელი</th>
                  <td>
                    <input type="text" name="st_year" id="trax" value="<?=date('Y')?>" class="calendar-icon hasDatepicker">
                    <script>
                    "use strict";

                    $(document).ready(function(){
                      $( "#trax" ).bootstrapDP({
                            autoShow:false,
                            format:'yyyy',
                            startDate:'<?=date('Y',strtotime($min_year))?>',
                            autoHide: true,
                            showButtonPanel: true,
                      })


                    })
                    </script>
                  </td>
              </tr>
              <?php foreach ($TEXT['months'] as $key => $value): ?>
                <tr>
                  <th>
                    <?=$value?>
                  </th>
                  <td>
                    <input type="text" name="month_price[]" class="focus_class" value="0">
                  </td>
                </tr>
              <?php endforeach; ?>
            </table>
        </form>
    </div>
