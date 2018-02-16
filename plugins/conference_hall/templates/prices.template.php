<script src="./js/context_menu/jquery.ui.position.js" type="text/javascript"></script>
<script src="./js/context_menu/jquery.contextMenu.js" type="text/javascript"></script>
<link href="./js/context_menu/sass/jquery.contextMenu.css" rel="stylesheet" type="text/css" />

<script src="./js/notify.min.js" type="text/javascript"></script>
<script src="./js/notify-metro.js" type="text/javascript"></script>
<link href="./css/notify-metro.css" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.1/jquery.cookie.js" type="text/javascript"></script>
<script src="./js/sumoselect/jquery.sumoselect.min.js" type="text/javascript"></script>
<link href="./js/sumoselect/sumoselect.css" rel="stylesheet" type="text/css" />


<link href="./js/build/jquery.datetimepicker.min.css" rel="stylesheet" type="text/css" />
<script src="./js/build/jquery.datetimepicker.full.min.js"></script>

<style>
    .fl{
        float: left;
        display: block;
        width: 60px;
    }
    .form_class{

    }
    .ensoR{
        color:red;
    }
    .ensoL{
        color:blue;
    }
</style>
<div id="column_width" style="width:100%; height:1px"> </div>
<table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
    <tr>
        <td style="width:200px;"><b><?=$TEXT['filter']?></b></td>
        <td style="">
            <form action="" method="get" id="form">
                <input type="hidden" name="m" value="<?=$_GET['m']?>" />
                <input type="hidden" name="tab" value="<?=$_GET['tab']?>" />

                <select onchange="this.form.submit()" name="room_id" class="formField1" style="width:30%;">
                    <option value="0">აირჩიეთ დარბაზი</option>
                    <? foreach($rooms as $room)  { ?>
                        <option value="<?=$room['id']?>" <?=($_GET['room_id']==$room['id'])?"selected":""?>><?=$room['name']?> </option>
                    <? } ?>
                </select>
                <label for="from_calendar">ფასის საწყისი დრო</label>
                <input type="text" class="form_class" name="from_call" id="from_calendar">

                <label for="to_calendar">ფასის საბოლოო დრო</label>
                <input type="text" class="form_class" name="to_call" id="to_calendar">

            </form>
        </td>
    </tr>
</table>

<table width="100%" border="1" cellspacing="5" cellpadding="10" class="" style="margin-top:20px; background:#ddd; color:#000;">
    <tr>
        <form action="index.php?m=conference_hall&tab=prices&room_id=<?=$_GET['room_id']?>" method="POST">
            <input type="hidden" name="action" value="add_price">
            <input type="hidden" name="start_date" id="start_date_x" value="<?=date('H')?>">
            <input type="hidden" name="end_date" id="end_date_x" value="<?=date('H',strtotime('+1 hour'))?>">
            <input type="hidden" name="room_id" value="<?=$_GET['room_id']?>">
        <?foreach ($weekDays as $k => $v) {
            ${$v['name']}=$v['id'];
            $bg=$k>4?'red':'green';
            ?>
            <td  style="color:<?=$bg?> !important;" class="add_<?=${$v['name']}?>">
                <?=$v['name']?>
            <div class="price_cc clone_<?=${$v['name']}?>">

                <input type="number" min="0"  name="callender[<?=$v['name']?>][price]" id="price" style="width:100%;">
            </div>
            </td>


        <?}?>
    </tr>
    <button>შენახვა</button>
    </form>
</table>
<table width="100%" border="1" cellspacing="5" cellpadding="10" class="" style="margin-top:20px; background:#ddd; color:#000;">
<? foreach($price_items as $items){?>
    <tr>
    <td>
        <p class="ensoL"><?=$prices["Mon"][$items]['from_time']?></p>
        to
        <p class="ensoR"><?=$prices["Mon"][$items]['to_time']?></p>
    </td>
        <?foreach ($weekDays as $k => $v) {
            ${$v['name']}=$v['id'];
            $bg=$k>4?'red':'green';
            ?>
            <td  style="color:<?=$bg?> !important;" class="add_<?=${$v['name']}?>">
                <?=$v['name']?>
                <div class="price_cc clone_">
                    <input type="number" value="<?=$prices[$v['name']][$items]['price']?>" id="price" style="width:100%;">
                </div>

            </td>
        <?}?>
    <td>
        <form action="index.php?m=conference_hall&tab=prices&room_id=<?=$_GET['room_id']?>" method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="room_id" value="<?=$_GET['room_id']?>">
            <input type="hidden" name="start_date" value="<?=$prices["Mon"][$items]['from_time']?>">
            <button> Delete </button>
        </form>
    </td>
    </tr>
<? } ?>

</table>
<script>
    jQuery(function(){
        jQuery('#from_calendar').datetimepicker({
            datepicker:false,
            format:'H:i',
            onSelectTime:function(ct){
                var d=new Date(jQuery('#from_calendar').datetimepicker('getValue'));
                $('#start_date_x').val(d.getHours())
            }
        });

        jQuery('#to_calendar').datetimepicker({
            datepicker:false,
            format:'H:i',
            onSelectTime:function(ct) {
                var d=new Date(jQuery('#to_calendar').datetimepicker('getValue'));
                $('#end_date_x').val(d.getHours())
            }
        });

    });

</script>