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
                <input type="text" id="period_start" name="period_start" value="<?=$_SESSION['booking']['filter']['start_date']?>" class="calendar-icon" autocomplete="off" placeholder="<?= $TEXT['filter_modal']['from'] ?>"/>
            </td>
            <td>
                <input type="text" id="period_end" name="period_end" value="<?=$_SESSION['booking']['filter']['end_date']?>" class="calendar-icon" autocomplete="off" placeholder="<?= $TEXT['filter_modal']['to'] ?>"/>
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
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() + (1000*60*60*24));
            $( "#period_end" ).datepicker( "option", "minDate",date );
            //scrollBoard("day_"+selectedDate);
        }
    });
    $( "#period_end" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        //minDate: 'today',
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() - (1000*60*60*24));
            $( "#period_start" ).datepicker( "option", "maxDate", date );
        }
    });
</script>
<div style="float:left;width:100%;">
<div style="margin-left: 4px; float:left;width:800px;padding-top: 20px;">

<table class="table-table">
    <tbody>
    <tr>
        <td class="table-th">Date</td>
        <td class="table-th">Adult</td>
        <td class="table-th">Child</td>
        <td class="table-th">Rooms Count</td>
        <td class="table-th" colspan="<?=$rooms_count['count']?>"> <?=$TEXT['food']?></td>
    </tr>
    <? foreach($items as $key=>$value){?>
    <tr>
        <td class="table-td"><?=$key?></td>
        <td class="table-td"><?=$value['adult_num']?></td>
        <td class="table-td"><?=$value['child_num']?></td>
        <td class="table-td"><?=$value['count']?></td>
        <? foreach($value['food'] as $food){?>
            <td class="table-td"><?=$food['title'].'('.$food['count'].')'?></td>
        <? } ?>
    </tr>

    <? } ?>
    </tbody>
</table>
    <form method="post" target="_blank" id="excel_download_form">
        <input type="hidden" name="action" value="get_excel">
        <input type="submit" value="<?=$TEXT['booking_list']['download']?>" class="download-excel" style="float:right; border:solid 0px;">
    </form>
</div></div>