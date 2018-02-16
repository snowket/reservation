<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['booking_list']['filter_modal']['title'] ?></b>
    </div>
    <form action="" method="get" id="filter_form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <input type="hidden" name="filter" value="filter"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td align="right">
                    <label for="guest_id"><?= $TEXT['booking_list']['filter_modal']['booking_id'] ?></label>
                </td>
                <td>
                    <input type="text" id="booking_id" name="booking_id" value="<?=$_GET['booking_id']?>"/>
                </td>
                <td align="right">
                    <label for="guest_name"><?= $TEXT['booking_list']['filter_modal']['guest_name'] ?></label>
                </td>
                <td>
                    <input type="text" id="guest_name" name="guest_name" value="<?=$_GET['guest_name']?>"/>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="guest_id"><?= $TEXT['booking_list']['filter_modal']['guest_id'] ?></label>
                </td>
                <td>
                    <input type="text" id="guest_id" name="guest_id" value="<?=$_GET['guest_id']?>"/>
                </td>
                <td align="right">
                    <label for="in_start_date"><?= $TEXT['booking_list']['filter_modal']['check_in_date'] ?></label>
                </td>
                <td >
                    <input type="text" class="calendar-icon" id="in_start_date" name="in_start_date" value="<?=(isset($_GET['in_start_date'])&&$_GET['in_start_date']!='')?$_GET['in_start_date']:''?>"  placeholder="<?=$TEXT['booking_list']['filter_modal']['from']?>"  autocomplete="off"/>
                    <input type="text" class="calendar-icon" id="in_end_date" name="in_end_date" value="<?=(isset($_GET['in_end_date'])&&$_GET['in_end_date']!='')?$_GET['in_end_date']:''?>"  placeholder="<?=$TEXT['booking_list']['filter_modal']['to']?>"  autocomplete="off"/>
                </td>
            </tr>


            <tr>
                <td align="right" colspan="4">
                    <input class="formButton2" type="submit" value="<?= $TEXT['booking_list']['filter_modal']['submit_filter'] ?>" style="cursor: pointer">
                </td>
            </tr>
        </table>
    </form>
</div>


<table border="0" style="width:100%;" cellpadding="0" cellspacing="0" class="table-table">
        <tr>
            <td class="table-th" height="20" valign="top" align="center">
                &#8470;
            </td>
            <td class="table-th" height="20" valign="top" align="center">
                <?=$TEXT['booking_list']['booking_id']?>
            </td>
            <td class="table-th" height="20" valign="top">
                <?=$TEXT['booking_list']['guest']?>
            </td>

            <td class="table-th" valign="top" style="border-bottom:1px solid #E5E6EE">
                <?=$TEXT['booking_list']['room_id']?>
            </td>
              <td class="table-th" valign="top" style="border-bottom:1px solid #E5E6EE">
               ფასი
            </td>

            <td class="table-th" valign="top">
                <?=$TEXT['booking_list']['check_in']?>
            </td>
            <td class="table-th" valign="top">
                <?=$TEXT['booking_list']['check_out']?>
            </td>
            <td class="table-th" valign="top">
                <?=$TEXT['booking_list']['action']?>
            </td>
        </tr>

        <? for($i=0;$i<count($TMPL_booking);$i++){ ?>
            <tr>
                <td class="table-td" align="center">
                    <a href="#" class="basic"><? $p = intval($_GET['p'])?intval($_GET['p']):1;  echo ($p-1)*50+$i+1; ?></a>
                </td>
                <td class="table-td" align="center">
                    <a href="#" title="<?=$TMPL_booking[$i]['dl_coment']?>" class="basic <?=($TMPL_booking[$i]['active']==0)?'cenceled':''?>"><?=$TMPL_booking[$i]['id'] ?></a>
                </td>
                <td class="table-td" align="center">
                    <a href="#" class="basic "><?=$TMPL_booking[$i]['guest']['name'] ?></a>
                </td>

                <td class="table-td basic" style="padding-left:8px;">
                    <? foreach($TMPL_booking[$i]['rooms'] as $room){ ?>
                        <a href="#"  class="basic"><?=$room['name']?></a><br>
                <?    }?>

                </td>
                 <td class="table-td basic" style="padding-left:8px;">
                        <a href="#"  class="basic"><?=$TMPL_booking[$i]['price']?></a><br>

                </td>

                <td class="table-td" style="padding-left:8px;" nowrap>
                    <a href="#" title="" class="bookings-by-check-in basic" date="<?=$TMPL_booking[$i]['check_in']?>" >
                        <?=$TMPL_booking[$i]['check_in']?>
                    </a>
                </td>
                <td class="table-td" style="padding-left:8px;" nowrap>
                    <a href="#" title="" class="bookings-by-check-out basic" date="<?=$TMPL_booking[$i]['check_out']?>" >
                        <?=$TMPL_booking[$i]['check_out']?>
                    </a>
                </td>





                <td class="table-td" align="center" valign="middle" nowrap>
                    <a href="<?=$_SERVER['PHP_SELF']?>?m=<?=$TMPL_plugin?>&tab=<?=$_GET['tab']?>&action=view&booking_id=<?=$TMPL_booking[$i]['id']?>">
                        <img src="./images/icos16/about.gif" width="16" height="16" border="0" align="middle" alt="Full Info"  title="<?=$TEXT['booking_list']['view_booking']?>">
                    </a>
                </td>
            </tr>
        <?}?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

        </tr>
    </table>
<script>
    $(document).ready(function(){
        $( "#in_start_date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            dateFormat:'yy-mm-dd',
            onSelect: function( selectedDate ) {
                var date = $(this).datepicker('getDate');
                var nextDay=new Date();
                nextDay.setTime(date.getTime() + (1000*60*60*24));
                $( "#in_end_date" ).datepicker( "option", "minDate",date );
                $( "#out_start_date" ).datepicker( "option", "minDate",nextDay );
                $( "#out_end_date" ).datepicker( "option", "minDate",nextDay );
            }
        });

        $( "#in_end_date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            dateFormat:'yy-mm-dd',
            onSelect: function( selectedDate ) {
                var date = $(this).datepicker('getDate');
                //date.setTime(date.getTime() - (1000*60*60*24));
                $("#in_start_date" ).datepicker( "option", "maxDate", date );
            }
        });

        $( "#out_start_date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            dateFormat:'yy-mm-dd',
            onSelect: function( selectedDate ) {
                var date = $(this).datepicker('getDate');
                //date.setTime(date.getTime() - (1000*60*60*24));
                $("#out_end_date" ).datepicker( "option", "minDate", date );
            }
        });

        $( "#out_end_date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            dateFormat:'yy-mm-dd',
            onSelect: function( selectedDate ) {
                var date = $(this).datepicker('getDate');
                $("#out_start_date" ).datepicker( "option", "maxDate", date );
            }
        });
    })
</script>