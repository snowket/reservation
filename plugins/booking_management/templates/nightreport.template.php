
<script src="./js/context_menu/jquery.ui.position.js" type="text/javascript"></script>
<script src="./js/context_menu/jquery.contextMenu.js" type="text/javascript"></script>
<link href="./js/context_menu/sass/jquery.contextMenu.css" rel="stylesheet" type="text/css" />

<script src="./js/notify.min.js" type="text/javascript"></script>
<script src="./js/notify-metro.js" type="text/javascript"></script>
<link href="./css/notify-metro.css" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.1/jquery.cookie.js" type="text/javascript"></script>
<script src="./js/sumoselect/jquery.sumoselect.min.js" type="text/javascript"></script>
<link href="./js/sumoselect/sumoselect.css" rel="stylesheet" type="text/css" />

<form action="" method="get" id="form">
    <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
    <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
    <div style="margin-left: 4px; float:left; background:#FFF; border:solid #3A82CC 1px; width:517px;">
        <div style="padding:2px; background:#3A82CC; color:#FFF">
            <b>Adult Child Report Filter</b>
        </div>
        <table>
            <tr>
                <td>
                    <input type="text" id="period_start" name="period_start" value="<?=$_SESSION['booking']['filter']['start_date']?>" class="calendar-icon" autocomplete="off"/>
                </td>

                <td>

                </td>
                <td>
                    <input class="formButton2" type="submit" style="cursor: pointer" value="<?= $TEXT['filter_modal']['submit'] ?>">

                </td>

            </tr>
        </table>
    </div>
</form>
<script>
    $( "#period_start" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        //minDate: 'today',
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',

    });

</script>

<div style="float:left;width:100%;">
    <div style="margin-left: 4px; float:left;width:800px;padding-top: 20px;">

        <table class="table-table">
            <tbody>
            <tr>
                <td class="table-th"><?=$TEXT['room_num']?></td>
                <td class="table-th"><?=$TEXT['guest']?></td>
                <td class="table-th"><?=$TEXT['day_price']?></td>
                <td class="table-th"><?=$TEXT['guests']?></td>
                <td class="table-th"><?=$TEXT['sauzme']?></td>
                <td class="table-th"><?=$TEXT['sadili']?></td>
                <td class="table-th"><?=$TEXT['vaxshami']?></td>
                <td class="table-th" ><?=$TEXT['food']?></td>
            </tr>
            <? foreach($data as $key=>$value){
                $v=$value['child_num'] + $value['adult_num'];?>
                <tr>
                    <td class="table-td"><?=$value['name']?></td>
                    <td class="table-td"><?=$value['person_name']?></td>
                    <td class="table-td"><?=$value['price']?></td>
                    <td class="table-td"><?=$value['child_num']+$value['adult_num']?></td>
                    <?php
                        switch($value['food_count']) {
                            case 1:
                                echo "<td class='table-td'>" . $v . "</td><td class='table-td'>0</td><td class='table-td'>0</td>";
                                break;
                            case 2:
                                echo "<td class='table-td'>" . $v . "</td><td class='table-td'>0</td><td class='table-td'>" .$v . "</td>";
                                break;
                            case 3:
                                echo "<td class='table-td'>" . $v . "</td><td class='table-td'>" . $v . "</td><td class='table-td'>" . $v . "</td>";
                                break;
                            case 0:
                                echo "<td class='table-td'>0</td><td class='table-td'>0</td><td class='table-td'>0</td>";
                                break;

                        }
                    ?>
                    <td class="table-td"><?=$value['title']?></td>
                </tr>

            <? } ?>
            </tbody>
        </table>
        <form method="post" target="_blank" id="excel_download_form">
            <input type="hidden" name="action" value="get_excel">
            <input type="submit" value="<?=$TEXT['booking_list']['download']?>" class="download-excel" style="float:right; border:solid 0px;">
        </form>
    </div></div>
