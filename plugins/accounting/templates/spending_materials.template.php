<script src="./js/notify.min.js" type="text/javascript"></script>
<script src="./js/notify-metro.js" type="text/javascript"></script>
<link href="./css/notify-metro.css" rel="stylesheet" type="text/css" />


<script src="plugins/accounting/js/spending_materials.js"></script>

<style>
.vac{
vertical-align: middle;
}

.h18{
height: 18px;
}
.content{
display: none;
}
</style >

<? foreach($ALL_DAYS AS $day){
    $year = date('Y', strtotime($day));
    $month = date('m', strtotime($day));
    $day = date('d', strtotime($day));
    $date_assoc_array[$year][$month][$day] = $day;
    }

?>
<div style="margin-bottom: 10px; background:#FFF; border:solid #3A82CC 1px; width:480px;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?=$TEXT['sp_materials']['filter']['title']?></b>
    </div>
    <form id="form" method="get" action="">
        <input type="hidden" value="<?= $_GET['m'] ?>" name="m">
        <input type="hidden" value="<?= $_GET['tab'] ?>" name="tab">
        <table>
        <tbody>
            <tr>
            <td>
                <input type="text" placeholder="<?=$TEXT['sp_materials']['filter']['from']?>" autocomplete="off" class="calendar-icon" value="<?=$period_start?>" name="period_start" id="period_start">
            </td>
            <td>
                <input type="text" placeholder="<?=$TEXT['sp_materials']['filter']['to']?>" autocomplete="off" class="calendar-icon" value="<?=$period_end?>" name="period_end" id="period_end">
            </td>
            <td>
                <input type="submit" value="<?=$TEXT['sp_materials']['filter']['submit_filter']?>" style="cursor: pointer" class="formButton2">
            </td>
            </tr>
        </tbody>
        </table>
    </form>
</div>


<div id="sp_board" style="background:#FFF; border:solid #3A82CC 1px; ">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?=$TEXT['sp_materials']['board']['title']?></b>
    </div>

<div id="sp_left" style="float:left">
<table border="1"  style="border-collapse:collapse">
<tr><td rowspan="4"></td><td><div class="h18"><?=$TEXT['sp_materials']['board']['year']?></div></td></tr>
<tr><td><div class="h18"><?=$TEXT['sp_materials']['board']['month']?></div></td></tr>
<tr><td><div class="h18"><?=$TEXT['sp_materials']['board']['day_of_week']?></div></td></tr>
<tr ><td><div class="h18"><?=$TEXT['sp_materials']['board']['day']?></div></td></tr>
<?
//SPENDING MATERIALS
$counter=0;
foreach($SERVICES as $service){
    if($service['type_id']==10 ){
        $counter++;
        if($counter==1){
            $s10.="<td><div style='height: 20px'>".$service['title']."</div></td></tr>";
        }else{
            $s10.="<tr><td><div style='height: 20px'>".$service['title']."</div></td></tr>";
        }
    }
}
echo "<tr><td rowspan='".$counter."' bgcolor='#FDE44E'>10</td>".$s10;
//MINI BAR
$counter=0;
foreach($SERVICES as $service){
    if($service['type_id']==4 ){
        $counter++;
        if($counter==1){
            $s4.="<td><div style='height: 20px'>".$service['title']."</div></td></tr>";
        }else{
            $s4.="<tr><td><div style='height: 20px'>".$service['title']."</div></td></tr>";
        }
    }
}
echo "<tr><td rowspan='".$counter."' bgcolor='#74C3FA'>4</td>".$s4;
?>
</table>
</div>

<div id="sp_center" style="width:800px; overflow-x: scroll; float: left">
<table border="1" style="border-collapse:collapse">
<?
$years=''; $months=''; $days='';
foreach($date_assoc_array AS $y=>$year){
    $years.='<td colspan="'.count($year).'"><div class="h18">'.$y.'</div></td>';
    foreach($year AS $m=>$month){
    $months.='<td colspan="'.count($month).'"><div class="h18">'.$TEXT['months'][$m].'</div></td>';
        foreach($month AS $day){
            $dayofweek = date('w', strtotime($y."-".$m."-".$day));
            if($dayofweek==6||$dayofweek==0){
                $week_day_color="#FFAFAF";
            }else{
                $week_day_color="#b3ffaf";
            }
            $wds.='<td bgcolor="'.$week_day_color.'"><div class="h18">'.$TEXT['week_days'][$dayofweek].'</div></td>';
            $days.='<td bgcolor="#A8A8A8"><div class="h18">'.$day.'</div></td>';
            foreach($SERVICES as $service){
                if($service['type_id']==10 || $service['type_id']==4){
                    $day_array=$DATA[$service['type_id']][$service['id']][$y."-".$m."-".$day];
                    //echo "DATA[".$service['type_id']."][".$service['id']."][".$y."-".$m."-".$day."]";
                    if($service['type_id']==10){
                           $css_a='sptda';
                           $css_d='sptdd';
                    }else{
                            $css_a='sptda2';
                            $css_d='sptdd2';
                    }
                    if(count($day_array)>0){
                        $total=0;
                        $content='';
                        foreach($day_array as $r=>$c){
                            $total+=count($c);
                            if(count($c)>0){
                                $sp_total[$service['id']]+=(int)count($c);
                                $content.=$r.": ".count($c)."<br>";
                            }

                        }
                        $tds[$service['type_id']][$service['id']].="<td><div class='".$css_a."'>".$total."<div class='content'>".$content."</div></div></td>";
                    }else{
                        $tds[$service['type_id']][$service['id']].="<td><div class='".$css_d."'>0</div></td>";
                    }
                }
            }
        }
    }
}?>
<tr><?=$years?></tr>
<tr><?=$months?></tr>
<tr><?=$wds?></tr>
<tr><?=$days?></tr>
<? foreach($tds['10'] as $tr){?>
    <tr><?=$tr?></tr>
<?}?>
<? foreach($tds['4'] as $tr){?>
    <tr><?=$tr?></tr>
<?}?>
</table>
</div>

<div id="sp_right" style="float:left; width: 100px; ">
    <table border="1" width="100%" style="border-collapse:collapse">
    <tr>
        <td height="84" style="vertical-align: middle"><div style=" text-align: center">TOTAL</div></td>
    </tr>
    <?
    //SPENDING MATERIALS
    $counter=0;
    foreach($SERVICES as $service){
        if($service['type_id']==10 ){
            $t10.="<tr><td><div class='sptdtotal'>".(int)$sp_total[$service['id']]."</div></td></tr>";
        }
    }
    echo $t10;
    //MINI BAR
    $counter=0;
    foreach($SERVICES as $service){
        if($service['type_id']==4 ){
            $t4.="<tr><td><div class='sptdtotal'>".(int)$sp_total[$service['id']]."</div></td></tr>";
        }
    }
    echo $t4;
    ?>
    </table>
</div>
<div style="clear:both"></div>
</div>

<form method="post" target="_blank" id="excel_download_form">
    <input type="hidden" name="action" value="get_excel">
</form>

<div id="excel_downloader" class="download-excel" style="float:right">
Download
</div>
