<? session_start(); ?>
<link href="./js/build/jquery.datetimepicker.min.css" rel="stylesheet" type="text/css" />
<script src="./js/build/jquery.datetimepicker.full.min.js"></script>
<script src="./js/JS_serialize.js"></script>
<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<br>
<? foreach ($TMPL_all_days as $day) {
    $year = date('Y', strtotime($day));
    $month = date('m', strtotime($day));
    $day = date('d', strtotime($day));
    $date_asoc_array[$year][$month][$day] = $day;
}

?>
<style>
    .hidden{
        display: none;
    }
</style>
<form id="booking_form" action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add">
    <input type="hidden" id="b_guest_id" name="b_guest_id" value="0">
    <input type="hidden" id="b_responsive_guest_id" name="b_responsive_guest_id" value="0">
    <input type="hidden" id="b_affiliate_id" name="b_affiliate_id" value="0">

    <div style="background:#FFF; border:solid #3A82CC 1px;">
        <div style="padding:2px; background:#3A82CC; color:#FFF">
            <div style="float:left;">
                <label><?= $TEXT['booking_modal']['title'] ?></label>
            </div>
            <div id="discount_display" style="padding-left:10px; float:left;"></div>
            <div style="float:right;">
                <label for="general_price"><?= $TEXT['booking_modal']['price'] ?></label>
                <input type="text" id="general_price" name="general_price" value="0" readonly style="width:80px;"/>
                <label for="custom_price"><?= $TEXT['booking_modal']['custom_price'] ?></label>
                <input type="text" id="custom_price" name="custom_price" value="0" style="width:80px;"/>
            </div>
            <div style="clear:both;"></div>
        </div>
        <table cellpadding="2" style="width:100%; border:1px solid #d4d4d4; border-collapse: collapse; border-spacing: 0px;">
            <tr>
                <td style="width:37%; border:1px solid #d4d4d4;">
                    <table>
                        <tr>
                            <td><label for="check_in"><?= $TEXT['booking_modal']['check_in'] ?></label></td>
                            <td><input type="text" id="check_in" name="check_in" value="" class="calendar-icon form_change_event_claas" autocomplete="off" placeholder="<?= $TEXT['booking_modal']['from'] ?>"/>
                            </td>
                            <td><label for="from_calendar"><?= $TEXT['booking_modal']['check_in'] ?></label></td>
                            <td><input type="text" id="from_calendar" name="from_calendar" value="" class="calendar-icon form_change_event_claas" autocomplete="off" placeholder="<?= $TEXT['booking_modal']['from'] ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="check_out"><?= $TEXT['booking_modal']['check_out'] ?></label></td>
                            <td><input type="text" id="check_out" name="check_out" value="" class="calendar-icon form_change_event_claas" autocomplete="off" placeholder="<?= $TEXT['booking_modal']['to'] ?>"/>
                            </td>
                            <td><label for="to_calendar"><?= $TEXT['booking_modal']['check_out'] ?></label></td>
                            <td><input type="text" id="to_calendar" name="to_calendar" value="" class="calendar-icon form_change_event_claas" autocomplete="off" placeholder="<?= $TEXT['booking_modal']['to'] ?>"/>
                            </td>
                        </tr>


                    </table>
                    <table style="width: 100%;">
                        <tr>
                            <td><label for="room_id"><?= $TEXT['booking_modal']['room'] ?></label></td>
                            <td>
                                <select name="room_id[]" multiple id="room_id" class="formField1 form_change_event_claas" style="width:100%;">
                                    <? foreach ($TMPL_all_rooms as $room) {
                                        echo '<option value="' . $room['id'] . '" >' . $room['name'] . '</option>';
                                    } ?>
                                </select>
                            </td>
                        </tr>
                    </table>

                </td>
                <td style="width:33%; border:1px solid #d4d4d4;">
                    <table style="border-collapse: collapse;">
                        <tr>
                            <td><label for="adult_num"><?= $TEXT['booking_modal']['adults'] ?></label></td>
                            <td>
                                <input type="number" id="adult_num" class="form_change_event_claas" name="adult_num" min="1" step="1" value="1"
                                       style="width:160px"/>
                            </td>
                        </tr>


                        <tr>
                            <td><?= $TEXT['booking_modal']['guest'] ?></td>
                            <td>
                                <div id="guest_selector" class="guest_modal_trigger" def="<?= $TEXT['booking_modal']['select_guest'] ?>" dest="guest" style="padding:2px; border:solid #868686 1px; cursor: pointer; text-align: center">
                                    <?= $TEXT['booking_modal']['select_guest'] ?>
                                </div>
                            </td>
                        </tr>

                    </table>
                </td>
                <td align="left" style="width:33%; border:1px solid #d4d4d4;">
                    <table width="100%">

                        <tr>
                            <td><?= $TEXT['booking_modal']['info'] ?></td>
                            <td>
                                <textarea id="booking_comment" class="formField3" style="width:100%"  name="booking_comment"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><?= $TEXT['booking_modal']['services'] ?></td>
                            <td align="left">
                                <select name="services[]" id="services" multiple="multiple" class="form_change_event_claas" style="width: 100%">
                                    <? foreach($TMPL_services as $service){ ?>
                                        <option value="<?=$service['id']?>"><?=$service['name']?></option>
                               <?      } ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="right" colspan="3">
                    <br>
                    <input class="formButton2" type="submit" value="<?= $TEXT['booking_modal']['submit_booking'] ?>" style="cursor: pointer">
                </td>
            </tr>
        </table>
    </div>
</form>



<div id="booking_board_cont" style="position:relative; width:1261px; border:solid #3A82CC 1px;overflow-x:scroll;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <div style="float:left;">
            <?= $TEXT['booking_board']['title'] ?>
        </div>
        <form id="board-period-form" action="" method="post">
            <input type="hidden" name="action" value="change_board_period">
            <input type="hidden" id="days_before" name="days_before" value="<?=$_SESSION['days_before']?>">
            <input type="hidden" id="days_after"  name="days_after" value="<?=$_SESSION['days_after']?>">
        </form>
        <div class="div-table" style="float:right;">
            <div class="div-tr">
                <div val="-1y" class="div-td board-period-selector <?=($_SESSION['days_before']=='-1y')?'active-period':'before'?>">-Y</div>
                <div val="-6m" class="div-td board-period-selector <?=($_SESSION['days_before']=='-6m')?'active-period':'before'?>">-6M</div>
                <div val="-1m" class="div-td board-period-selector <?=($_SESSION['days_before']=='-1m')?'active-period':'before'?>">-M</div>
                <div val="-1w" class="div-td board-period-selector <?=($_SESSION['days_before']=='-1w')?'active-period':'before'?>">-W</div>
                <div class="div-td board-period-selector now">< now ></div>
                <div val="+1w" class="div-td board-period-selector <?=($_SESSION['days_after']=='+1w')?'active-period':'after'?>">+W</div>
                <div val="+1m" class="div-td board-period-selector <?=($_SESSION['days_after']=='+1m')?'active-period':'after'?>">+M</div>
                <div val="+6m" class="div-td board-period-selector <?=($_SESSION['days_after']=='+6m')?'active-period':'after'?>">+6M</div>
                <div val="+1y" class="div-td board-period-selector <?=($_SESSION['days_after']=='+1y')?'active-period':'after'?>">+Y</div>
            </div>
        </div>
        <div style="clear: both"></div>
    </div>
    <div id="booking_board_left" style="width:160px; float:left;">
        <table width="100%" class="booking-table">
            <tr>
                <td rowspan="4" >

                    <div style="overflow: hidden; ">
                        <img src="images/floor_geo.jpg">
                    </div>
                </td>
                <td><div style="height: 15px; overflow: hidden"><?= $TEXT['booking_board']['year'] ?></div></td>
            </tr>
            <tr>
                <td><div style="height: 15px; overflow: hidden"><?= $TEXT['booking_board']['month'] ?></div></td>
            </tr>
            <tr>
                <td><div style="height: 15px; overflow: hidden"><?= $TEXT['booking_board']['week_day'] ?></div></td>
            </tr>
            <tr>
                <td><div style="height: 15px; overflow: hidden"><?= $TEXT['booking_board']['day'] ?></div></td>
            </tr>
            <?
            $tmp_floor=-1;

            foreach ($TMPL_all_rooms as $room) {

                    $starter='<td>';

                ?>
                <tr>
                    <?=$starter?>
                    <div status="<?=$TEXT['housekeeping_statuses'][$room['housekeeping_status']]?>" class="room_selector" style="height: 20px"  id="room_<?= $room['id'] ?>" floor="<?= $room['floor'] ?>" common_id="<?= $room['common_id'] ?>" max_adults="<?= $TMPL_capacity[$TMPL_rooms_manager_arr[$room['common_id']]['capacity_id']]['capacity'] ?>" max_children="<?= $TMPL_capacity[$TMPL_rooms_manager_arr[$room['common_id']]['capacity_id']]['childrens'] ?>" old_bgcolor="" description="<?=$room['description']?>" one_person_discount="<?= $TMPL_rooms_manager_arr[$room['common_id']]['one_person_discount'] ?>" pay_now_discount="<?= $TMPL_rooms_manager_arr[$room['common_id']]['pay_now_discount'] ?>" room_id="<?= $room['id'] ?>" room_name="<?= $room['name'] ?>">
                        <div style="border-radius: 50%; width:10px;height: 10px;  background-color: <?=$color?>; float:right; margin-top:3px; margin-right: 2px; border:solid #FFF 2px;"></div>
                        <?= $room['name'] ?>
                    </div>
                    </td>
                </tr>
            <? } ?>
        </table>
    </div>
    <div id="booking_board" style="position:relative; width:1px; overflow-x:scroll; margin:0px 0px 0px 160px; width:87%;" class="booking_board">

        <table  class="booking-table">
            <? foreach ($date_asoc_array as $k => $v) {
                $days_in_year = 0;
                foreach ($v as $kk => $vv) {

                    $months_tr .= '<td colspan="' . count($vv) . '">' . $TEXT['months'][date('m', mktime(0, 0, 0, $kk, 10))] . '</td>';
                    foreach ($vv as $kkk => $vvv) {

                        $date_tmp = $k . '-' . $kk . '-' . $kkk;
                        $dayofweek = date('w', strtotime($date_tmp));
                        if ($date_tmp < CURRENT_DATE) {
                            $color = '#A8A8A8';
                        } else if ($date_tmp == CURRENT_DATE) {
                            $color = '#29A4F9';
                            $cd_start = '<div id="current_day">';
                            $cd_end = '</div>';
                        } else {
                            $color = '#FDE44E';
                            $cd_start = '<div id="day_'.$date_tmp.'">';
                            $cd_end = '</div>';
                        }
                        if($dayofweek==6||$dayofweek==0){
                            $week_day_color="#FFAFAF";
                        }else{
                            $week_day_color="#b3ffaf";
                        }
                        if($color == '#29A4F9'){
                            //current day
                            $week_day_color=$color;
                        }
                        $week_days_tr.= '<td bgcolor="' . $week_day_color . '"><div id="week_day_'.$date_tmp.'" class="week-day">'. $TEXT['week_days'][$dayofweek] .'</div></td>';
                        $days_tr .= '<td bgcolor="' . $color . '">' . $cd_start . '' . $kkk . '' . $cd_end . '</td>';
                        $days_in_year++;
                    }
                }
                $year_tr .= '<td colspan="' . $days_in_year . '">' . $k . '</td>';
            }
            ?>
            <tr>
                <?= $year_tr ?>
            </tr>
            <tr>
                <?= $months_tr ?>
            </tr>
            <tr>
                <?= $week_days_tr ?>
            </tr>
            <tr>
                <?= $days_tr ?>
            </tr>
            <? foreach ($TMPL_all_rooms as $room) { ?>
                <tr>
                    <?  foreach ($TMPL_all_days as $day) {
                        if ($TMPL_bookings[$room['id']][$day] == "") {
                            $status = "free";
                            $color = '#FFFFFF';
                            $filter_button='hidden';
                        } else {
                            $filter_button='active';
                            $status = 'in_use';
                            if ($status == 'check_in') {

                            } else if ($status == 'check_out') {

                            } else if ($status == 'in_use') {
                                $css_class = 'in_use';
                            }
                         $color = "hsla(210, 76%, 46%, 0.89)";
                        } ?>
                        <td bgcolor="<?= $color ?>">
                            <div class="btd <?= $css_class ?>" id="<?= $room['id'] ?>-<?= $day ?>" status="<?= $status ?>" date="<?= $day ?>">
                                <a class="<?=$filter_button?>" date="<?= $day ?>" data-id="<?= $room['id'] ?>" onclick="filterOpen(this)">open</a>
                            </div>
                        </td>
                    <? } ?>
                </tr>
            <? } ?>
        </table>

        <div id="day_tooltip" style="position: absolute; top:200px; left:240px; display: none">
            <div style="position: relative;" >
                <div id="day_tooltip_content" style="position:absolute; margin-left: auto; bottom: 0px; padding:4px; background-color: #FFFFFF;  border: 2px solid #3a82cc;">

                </div>
                <div class="day-tooltip-arrow" >
                </div>
            </div>
        </div>
    </div>
    <div style="clear:both"></div>
</div>

<br>

<!--GUEST MODAL START-->
<div id="booking_guest_modal" style="display: none; width:600px" title="<?= $TEXT['guest_modal']['title'] ?>">
    <div id="guest_modal_message"></div>
    <form name="guest_modal_form" id="guest_modal_form" enctype="multipart/form-data">
        <input type="hidden" id="guest_id" name="guest_id" value="0">
        <table>
            <tr>
                <td><label for="guest_id"><?= $TEXT['guest_modal']['guest'] ?></label></td>
                <td>
                    <div class="ui-widget">
                        <input id="guest_search_field">
                    </div>
                </td>
            </tr>
            <tr >
                <td  style="min-width: 130px">
                    <?= $TEXT['guest_modal']['guest_type'] ?>
                </td>
                <td  style="border:solid gray 1px;">
                    <input type="radio" name="guest_type" value="non-corporate" id="non_corporate" checked >
                    <label for="non_corporate"><?= $TEXT['guest_modal']['non_corporate'] ?></label>
                    <input type="radio" name="guest_type" value="company" id="company">
                    <label for="company"><?= $TEXT['guest_modal']['company'] ?></label>
                    <input type="radio" name="guest_type" value="tour-company" id="tour-company">
                    <label for="tour-company"><?= $TEXT['guest_modal']['tour_company'] ?></label>
                </td>
            </tr>
            <tr>
                <td><label for="tax"><?= $TEXT['guest_modal']['tax'] ?></label></td>
                <td  style="border:solid gray 1px;">
                    <Input id="tax_1" type = 'Radio' Name ='tax' value= '1'  checked><?= $TEXT['guest_modal']['tax_included'] ?>
                    <Input id="tax_0" type = 'Radio' Name ='tax' value= '0' ><?= $TEXT['guest_modal']['tax_free'] ?>
                </td>
            </tr>
            <tr>
                <td><label for="id_number" ncorp="<?= $TEXT['guest_modal']['guest_id'] ?>" corp="<?= $TEXT['guest_modal']['company_id'] ?>"><?= $TEXT['guest_modal']['guest_id'] ?></label></td>
                <td><input type="text" name="id_number" id="id_number" value="" ></td>
            </tr>
            <tr>
                <td>
                    <label for="first_name" ncorp="<?= $TEXT['guest_modal']['guest_first_name'] ?>" corp="<?= $TEXT['guest_modal']['company_name'] ?>">
                        <?= $TEXT['guest_modal']['guest'] ?>
                    </label>
                </td>
                <td><input type="text" name="first_name" id="first_name" value="" ></td>
            </tr>
            <tr  id="guest_lname_tr">
                <td><label for="last_name"><?= $TEXT['guest_modal']['guest_last_name'] ?></label></td>
                <td><input type="text" name="last_name" id="last_name" value=""></td>
            </tr>
            <tr  id="guest_ind_discount_tr">
                <td><label for="guest_ind_discount"><?= $TEXT['guest_modal']['ind_discount'] ?></label></td>
                <td><input type="number" name="guest_ind_discount" id="guest_ind_discount" value="0" min="0" max="100" step="1"></td>
            </tr>
            <tr>
                <td><a href="#" id="id_scan_link"><?= $TEXT['guest_modal']['id_scan'] ?></a></td>
                <td align="right"><input class="" type="file" style="width:100%" name="id_scan" ></td>
            </tr>
            <tr>
                <td><label for="country"><?= $TEXT['guest_modal']['country'] ?></label></td>
                <td>
                    <select name="country" id="country" style="width:160px" >
                        <? foreach ($TMPL_countries as $country) {
                            echo '<option value="' . $country['id'] . '" >' . $country[LANG] . '</option>';
                        }?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="address"><?= $TEXT['guest_modal']['address'] ?></label></td>
                <td><input type="text" name="address" id="address" value=""></td>
            </tr>
            <tr>
                <td><label for="telephone"><?= $TEXT['guest_modal']['tel'] ?></label></td>
                <td><input type="text" name="telephone" id="telephone" value=""></td>
            </tr>
            <tr>
                <td><label for="email"><?= $TEXT['guest_modal']['email'] ?></label></td>
                <td><input type="text" name="email" id="email" value=""></td>
            </tr>
            <tr>
                <td><label for="comment"><?= $TEXT['guest_modal']['comment'] ?></label></td>
                <td class="tdrow3">
                    <textarea name="comment" id="comment" style="width:100%"
                              class="formField3"><?= $TMPL_data['comment'] ?></textarea>
                </td>
            </tr>
        </table>
    </form>
</div>
<!--GUEST MODAL END-->

<div id="boking_filter_modal"  style="display: none; height:800px">
    <table border="0" style="width:100%;" cellpadding="0" cellspacing="0" class="table-table table_add">
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

            <td class="table-th" valign="top">
                <?=$TEXT['booking_list']['check_in']?>
            </td>
            <td class="table-th" valign="top">
                <?=$TEXT['booking_list']['check_out']?>
            </td>

        </tr>

    </table>
</div>
<script>
    function filterOpen(target){
        var date=$(target).attr('date');
        var id=$(target).data('id');
        var $target=$('#boking_filter_modal').dialog().parent();
        $target.css('top','50');
        $target.css('left','300');
        $target.css('height','500');
        $target.css('width','800');
        $target.css('position','fixed');
        var request = $.ajax({
            url: "index_ajax.php?cmd=get_ch_booking_ajax",
            method: "POST",
            data: {room_id: id,date:date},
            dataType: "json"
        });
        request.done(function (msg) {
            msg.forEach(function(value, key){
                $('.table_add tbody').append('<tr>');
                $('.table_add tbody').append('<td class="table-td" align="center"><a href="#" class="basic">'+value['id']+'</a></td>');
                $('.table_add tbody').append('<td class="table-td" align="center"><a href="index.php?m=conference_hall&tab=conference_hall_list&action=view&booking_id='+value['id']+'" title="" class="basic ">'+value['id']+'</a></td>');
                $('.table_add tbody').append('<td class="table-td" align="center"><a href="#" title="" class="basic ">'+value['first_name']+'</a></td>');
                $('.table_add tbody').append('<td class="table-td" align="center"><a href="#" title="" class="basic ">'+value['room_name']+'</a></td>');
                $('.table_add tbody').append('<td class="table-td" align="center"><a href="#" title="" class="basic ">'+value['check_in']+'</a></td>');
                $('.table_add tbody').append('<td class="table-td" align="center"><a href="#" title="" class="basic ">'+value['check_out']+'</a></td>');
                $('.table_add tbody').append('</tr>');
            });
        });
        request.fail(function (jqXHR, textStatus) {
            console.log("Request failed: " + textStatus);
        });
    }
    $(document).ready(function(){

        var $eventSelect=$('#room_id').select2({
            placeholder: "აირჩიეთ ოთახი/ოთახები",
            allowClear: true
        });

        $("#booking_form").submit(function (event) {
            if (!validateSubmit()) {
                event.preventDefault();
            }
        });
        function updatePrice() {
            console.log($('#room_id').val());

        }
        var serialized_form_obj={};
        var guest_obj={};
        var affiliate_obj={};
        var responsive_guest_obj={};


        var affiliate_obj={};
        var responsive_guest_obj={};
        function fillGuestModal (g_id) {
            console.log('fillGuestModal('+g_id+');');
            if (g_id == 0) {
                $('#guest_id').val(0);
                $("#booking_guest_modal :input").attr("disabled", false);
                $("#id_number").val('');
                $("#first_name").val('');
                $("#last_name").val('');
                $("#id_scan_link").attr('href', '#');
                $("#id_scan_link").attr('target', '');
                $("#country").val(273);
                $("#address").val('');
                $("#telephone").val('');
                $("#email").val('');
                $("#comment").val('');
                $("#comment").removeAttr('readonly');
                $("#tax_1").prop('checked', true);
                $("#tax_0").prop('checked', false);
                $('input:radio[name=guest_type]')[0].checked = true;
                $('label[for="id_number"]').text($('label[for="id_number"]').attr('ncorp'));
                $('label[for="first_name"]').text($('label[for="first_name"]').attr('ncorp'));
                $('#guest_lname_tr').show();
                $('#guest_ind_discount').val(0);
                $('#guest_ind_discount_tr').val(0);
                return;
            }else{

            }
            var request = $.ajax({
                url: "index_ajax.php?cmd=get_guest_info",
                method: "POST",
                data: {guest_id: g_id},
                dataType: "json"
            });

            request.done(function (msg) {
                console.log("Request failed: " + msg);
                $("#id_number").val(msg.id_number);
                $("#first_name").val(msg.first_name);
                $("#last_name").val(msg.last_name);
                $("#guest_ind_discount").val(msg.ind_discount);
                $("#id_scan_link").attr('href', '../uploads_script/guests/' + msg.id_scan);
                $("#id_scan_link").attr('target', '_blank');
                $("#country").val(msg.country);
                $("#address").val(msg.address);
                $("#telephone").val(msg.telephone);
                $("#email").val(msg.email);
                $("#comment").val(msg.comment);
                //$("#comment").attr('readonly', 'readonly');
                if(msg.type=='company'){
                    $('input:radio[name=guest_type]')[1].checked = true;
                    //$('#guest_ind_discount_tr').hide();
                    $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
                    $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
                    $('#guest_lname_tr').hide();
                }else if(msg.type=='tour-company'){
                    $('input:radio[name=guest_type]')[2].checked = true;
                    $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
                    $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
                    //$('#guest_ind_discount_tr').show();
                    $('#guest_lname_tr').hide();
                }else{
                    $('input:radio[name=guest_type]')[0].checked = true;
                    //$('#guest_ind_discount_tr').hide();
                    $('label[for="id_number"]').text($('label[for="id_number"]').attr('ncorp'));
                    $('label[for="first_name"]').text($('label[for="first_name"]').attr('ncorp'));
                    $('#guest_lname_tr').show();
                }
                if(msg.tax==1){
                    $("#tax_1").prop('checked', true);
                    $("#tax_0").prop('checked', false);
                }else{
                    $("#tax_1").prop('checked', false);
                    $("#tax_0").prop('checked', true);
                }

                //$("#booking_guest_modal :input").attr("disabled", true);
                $("#guest_id").attr("disabled", false);
                serialized_form_obj=$('#guest_modal_form').serialize();
                last_fetched_guest_obj=msg;
            });

            request.fail(function (jqXHR, textStatus) {
                console.log("Request failed: " + textStatus);
            });
        };
        $( "#guest_search_field").autocomplete({

            source: "index_ajax.php?cmd=get_guest_suggestions",
            minLength: 0,
            select: function( event, ui ) {
                $('#guest_id').val(ui.item['id']);
                fillGuestModal(ui.item['id']);
            }
        }).autocomplete("option", "appendTo", "#booking_guest_modal");
        $( "#guest_search_field").click(function(){
            $(this).autocomplete( "search", $(this).val() );

        });
        $("#booking_form").submit(function (event) {
            if (!validateSubmit()) {
                event.preventDefault();
            }
        });
        function validateSubmit() {

            if ($("#check_in").val() == '') {
                alert("Please Select Check in Date");
                return false;
            }
            if ($("#check_out").val() == '') {
                alert("Please Select Check Out Date");
                return false;
            }
            if ($("#room_id").val() == 0) {
                alert("Please Select Room");
                return false;
            }
            if ($.isEmptyObject(guest_obj)) {
                alert("Please Select Guest");
                return false;
            }else{
                $('#b_guest_id').val(guest_obj['id']);
            }
            if (!$.isEmptyObject(affiliate_obj)) {
                $('#b_affiliate_id').val(affiliate_obj['id']);
            }else{
                $('#b_affiliate_id').val(0);
            }
            if (!$.isEmptyObject(responsive_guest_obj)) {
                $('#b_responsive_guest_id').val(responsive_guest_obj['id']);
            }else{
                $('#b_responsive_guest_id').val(0);
            }
            return true;
        }

        var guest_modal_dest="";
        $(".guest_modal_trigger").click(function () {
            guest_modal_dest=$(this).attr('dest');
            if(guest_modal_dest=='guest'){
                $( "#guest_search_field" ).autocomplete('option', 'source', "index_ajax.php?cmd=get_guest_suggestions");
                if(!$.isEmptyObject(guest_obj)){
                    fillGuestModal(guest_obj['id']);
                }else{
                    fillGuestModal(0);
                }
            }else if(guest_modal_dest=='responsive'){
                $( "#guest_search_field" ).autocomplete('option', 'source', "index_ajax.php?cmd=get_guest_suggestions");
                //console.log('responsive_guest_obj');
                //console.log(responsive_guest_obj);
                if(!$.isEmptyObject(responsive_guest_obj)){
                    fillGuestModal(responsive_guest_obj['id']);
                }else{
                    fillGuestModal(0);
                }
            }else if(guest_modal_dest=='affiliate'){
                $( "#guest_search_field" ).autocomplete('option', 'source', "index_ajax.php?cmd=get_affiliate_suggestions");
                if(!$.isEmptyObject(affiliate_obj)){
                    fillGuestModal(affiliate_obj['id']);
                }else{
                    fillGuestModal(0);
                }
            }
            $('#guest_search_field').val('');
            $('#guest_modal_message').html('');
            $("#booking_guest_modal").dialog({
                resizable: false,
                width: 540,
                modal: true,
                buttons: {
                    "<?= $TEXT['guest_modal']['but_add'] ?>": function () {
                        var alert_message="";
                        if ($('#id_number').val() == '') {
                            alert_message+="No id_number\n";
                        }
                        if ($('#first_name').val() == '') {
                            alert_message+="No first_name\n";
                        }
                        if ($('#email').val() == '') {
                            alert_message+="No email\n";
                        }
                        if(alert_message!=""){
                            alert_message+="do you want to continue anyway?";
                            if (confirm(alert_message)!= true) {
                                return;
                            }
                        }
                        var cmd="";
                        if($('#guest_id').val()==0){
                            cmd='add_guest';
                        }else{
                            if(serialized_form_obj===$('#guest_modal_form').serialize()){
                                cmd='select_guest';
                            }else{
                                cmd='edit_guest';
                            }
                        }
                        //console.log(cmd);
                        if(cmd=='add_guest' || cmd=='edit_guest') {
                            var datatosend=$('#guest_modal_form').serializeArray();
                            var fileInput = $("[name='id_scan']")[0];
                            var file = fileInput.files[0];
                            var formData = new FormData($('#guest_modal_form')[0]);
                            formData.append('file', file);
                            $.each(datatosend,function(key){
                                formData.append(key['name'], key['value']);
                            });



                            var request = $.ajax({
                                url: "index_ajax.php?cmd=" + cmd,
                                method: "POST",
                                data: formData,
                                dataType: "json",
                                processData: false,
                                contentType: false,
                            });

                            request.done(function (msg) {
                                if(msg['guest']===undefined){
                                    var message="<div style='border:solid red 2px'>"
                                    for(var i=0; i<msg['errors'].length;i++){
                                        message+="<b>"+msg['errors'][i]+"</b><br>";
                                    }
                                    message+="</div>";
                                    $('#guest_modal_message').html(msg['errors']);
                                }else{
                                    $('#guest_id').val(msg['guest']['id']);
                                    $("#booking_guest_modal").dialog("close");
                                    updatePrice();
                                    $('#first_name').val(msg.guest.first_name);
                                    if(guest_modal_dest=='guest'){
                                        $('#guest_selector').html(msg.guest.first_name+ ' ' + msg.guest.last_name);

                                        guest_obj=msg['guest'];
                                    }else if(guest_modal_dest=='responsive'){
                                        $('#responsive_guest_selector').html(msg.guest.first_name+ ' ' + msg.guest.last_name);
                                        responsive_guest_obj=msg['guest'];
                                    }else if(guest_modal_dest=='affiliate'){
                                        $('#affiliate_selector').html(msg.guest.first_name+ ' ' + msg.guest.last_name);
                                        affiliate_obj=msg['guest'];
                                        updatePrice();
                                    }
                                    //console.log(guest_obj,responsive_guest_obj,affiliate_obj);
                                }
                            });

                            request.fail(function (jqXHR, textStatus) {
                                $('#guest_modal_message').text("ver daemata!");
                            });

                        }
                        else{
                            if(guest_modal_dest=='guest'){
                                $('#guest_selector').html($('#first_name').val() + ' ' + $('#last_name').val());
                                guest_obj=last_fetched_guest_obj;
                            }else if(guest_modal_dest=='responsive'){
                                $('#responsive_guest_selector').html($('#first_name').val() + ' ' + $('#last_name').val());
                                responsive_guest_obj=last_fetched_guest_obj;
                            }else if(guest_modal_dest=='affiliate'){
                                $('#affiliate_selector').html($('#first_name').val() + ' ' + $('#last_name').val());
                                affiliate_obj=last_fetched_guest_obj;
                                updatePrice();
                            }
                            //console.log(guest_obj,responsive_guest_obj,affiliate_obj);
                            $(this).dialog("close");
                        }
                    },
                    "<?= $TEXT['guest_modal']['but_cancel'] ?>": function () {
                        if(guest_modal_dest=='guest'){
                            $('#guest_selector').text($('#guest_selector').attr('def'));
                            guest_obj={};
                        }else if(guest_modal_dest=='responsive'){
                            $('#responsive_guest_selector').text($('#responsive_guest_selector').attr('def'));
                            responsive_guest_obj={};
                        }else if(guest_modal_dest=='affiliate'){
                            $('#affiliate_selector').text($('#affiliate_selector').attr('def'));
                            affiliate_obj={};
                        }
                        updatePrice();
                        $(this).dialog("close");
                    }
                }
            });
            var $target=$('#booking_guest_modal').dialog().parent();
            $target.css('top',(window.innerHeight-$target.height())/2);
            $target.css('left',(window.innerWidth-$target.width())/2);
            $target.css('position','fixed');

        });
       var $serviceSelect= $('#services').select2({
            placeholder: "აირჩიეთ სერვისები",
            allowClear: true
        });

        $('.form_change_event_claas').on('change', function (evt) {
            console.log($('#booking_form').serializeObject());
            if($('#room_id').val()==''){
                return false;
            }
            if($('#from_calendar').val()==''){
                return false;
            }
            if($('#to_calendar').val()==''){
                return false;
            }
            if($('#check_in').val()==''){
                return false;
            }

            var formData=$('#booking_form').serializeObject();
            var request = $.ajax({
                url: "index_ajax.php?cmd=validate_ch_room_for_booking",
                method: "POST",
                data: {formData},
                dataType: "json",

            });

            request.done(function (msg) {
                if(msg.error==0){
                    $('#general_price').val(msg.text);
                }else if(msg.error==1){
                    notify(msg.text,msg.bookings);
                }
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });

        });

        var end_date;
        $( "#check_in" ).datetimepicker({
            datetimepicker:false,
            format:'d-m-Y',
            mask:true,
            minDate:<?=CURRENT_DATE?>,
            onSelectDate:function(ct){

                $('#check_out').val($('#check_in').val());
            }

        });
        $( "#check_out" ).datetimepicker({
            datetimepicker:false,
            format:'d-m-Y',
            mask:true,

        });


        $('#from_calendar').datetimepicker({
            datepicker:false,
            format:'H:i',
            step:15,
            onSelectTime:function(ct){
                var d=new Date($('#from_calendar').datetimepicker('getValue'));
                $('#start_date_x').val(d.getHours())
            }
        });

        $('#to_calendar').datetimepicker({
            datepicker:false,
            format:'H:i',
            step:15,

            onSelectTime:function(ct) {
                var d=new Date($('#to_calendar').datetimepicker('getValue'));
                $('#end_date_x').val(d.getHours())
            }
        });

    })
</script>
