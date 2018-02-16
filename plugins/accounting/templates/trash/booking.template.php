<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<script language="javascript" src="./js/calendar/calendar.js"></script>
<script language="javascript" src="./js/calendar/calendar-ge.js"></script>
<script language="javascript" src="./js/calendar/calendar-setup.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="./js/calendar/calendar-blue.css">

<link href="./css/select2.min.css" rel="stylesheet"/>
<script src="./js/select2.min.js"></script>
<!-- -->

<div style="background:#FFF; border:solid #3A82CC 1px; width:480px;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['filter'] ?></b>
    </div>
    <form action="" method="get" id="form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td style="">
                    <label for="block_id">Select Block</label>
                </td>
                <td style="">
                    <select onchange="this.form.submit()" name="block_id" class="formField1" style="width:100%;">

                        <? for ($i = 0; $i < count($TMPL_blocks); $i++) {
                            $selected = ($_GET['block_id'] == $TMPL_blocks[$i]['id']) ? 'selected' : '';
                            $style = ($TMPL_blocks[$i]['level'] == 1) ? 'style="font-weight:bold;"' : '';
                            echo '<option value="' . $TMPL_blocks[$i]['id'] . '" ' . $style . ' ' . $selected . ' >' . $TMPL_blocks[$i]['title'] . '</option>';
                            ?>
                        <? } ?>
                        <? if (count($TMPL_blocks) > 1) { ?>
                            <option
                                value="0" <?= ($_GET['block_id'] == 0) ? 'selected' : '' ?>><?= $TEXT['all'] ?></option>
                        <? } ?>
                    </select>
                </td>
                <td style="">
                    <label for="type_id">Select type</label>
                </td>
                <td>
                    <select onchange="this.form.submit()" name="type_id" class="formField1" style="width:100%;">
                        <? for ($i = 0; $i < count($TMPL_categories); $i++) {
                            $selected = ($_GET['type_id'] == $TMPL_categories[$i]['id']) ? 'selected' : '';
                            $style = ($TMPL_categories[$i]['level'] == 1) ? 'style="font-weight:bold;"' : '';
                            echo '<option value="' . $TMPL_categories[$i]['id'] . '" ' . $style . ' ' . $selected . ' >' . $TMPL_categories[$i]['title'] . '</option>';
                            ?>
                        <? } ?>
                        <? if (count($TMPL_blocks) > 1) { ?>
                            <option
                                value="0" <?= ($_GET['type_id'] == 0) ? 'selected' : '' ?> ><?= $TEXT['all'] ?></option>
                        <? } ?>
                    </select>
                </td>
            </tr>
        </table>
    </form>
</div>
<!-- -->
<? foreach ($TMPL_all_days as $day) {
    $year = date('Y', strtotime($day));
    $month = date('m', strtotime($day));
    $day = date('d', strtotime($day));
    $date_asoc_array[$year][$month][$day] = $day;
}
?>
<br>

<? if (count($TMPL_all_rooms) > 0) { ?>
    <!--MODAL CUSTOM BOOKING START-->
    <form id="booking_form" action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="guest_type" value="non-corporate">
        <div style="background:#FFF; border:solid #3A82CC 1px;">
            <div style="padding:2px; background:#3A82CC; color:#FFF">
                <div style="float:left;"><label>New Booking Record</label></div>
                <div style="float:right;">
                    <label for="general_price">Price:</label>
                    <input type="text" id="general_price" name="general_price" value="0" readonly
                           style="width:80px;"/>
                </div>
                <div style="clear:both;"></div>
            </div>
            <table style="width:100%; border:1px solid #d4d4d4; border-collapse: collapse; border-spacing: 0px;">
                <tr>
                    <td style="width:33%; border:1px solid #d4d4d4;">
                        <table>
                            <tr>
                                <td><label for="check_in">Check In</label></td>
                                <td><input type="text" id="check_in" name="check_in" value=""/>
                                    <!--img id="trigger_c2" src="./images/icos16/cal.gif" width="14" height="14" border="0"
                                         align="absmiddle" style="cursor:pointer; padding-right:20px;"-->
                                </td>
                            </tr>
                            <tr>
                                <td><label for="check_out">Check Out</label></td>
                                <td><input type="text" id="check_out" name="check_out" value=""/>
                                    <!--img id="trigger_c3" src="./images/icos16/cal.gif" width="14" height="14" border="0"
                                         align="absmiddle" style="cursor:pointer; padding-right:20px;"-->
                                </td>
                            </tr>
                            <tr>
                                <td><label for="room_id">Room</label></td>
                                <td>
                                    <select name="room_id" id="room_id" class="formField1" style="">
                                        <option value='0'><?= $TEXT['select_room'] ?></option>
                                        <? foreach ($TMPL_all_rooms as $room) {
                                            echo '<option value="' . $room['id'] . '" >' . $room['name'] . '</option>';
                                        } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="status_id">Status</label></td>
                                <td>
                                    <select name="status_id" id="status_id" class="formField1" style="">
                                        <? foreach ($TMPL_all_statuses as $status) {
                                            echo '<option value="' . $status['id'] . '" >' . $status['title'] . '</option>';
                                        } ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:33%; border:1px solid #d4d4d4;">
                        <table>
                            <tr>
                                <td><label for="adult_num">Adults</label></td>
                                <td>
                                    <input type="number" id="adult_num" name="adult_num" min="1" step="1" value="1"
                                           style="width:160px"/>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="child_num">Children</label></td>
                                <td>
                                    <input type="number" id="child_num" name="child_num" min="0" step="1" value="0"
                                           style="width:160px"/>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="ind_discount">Ind. Discount</label></td>
                                <td>
                                    <input type="number" id="ind_discount" name="ind_discount" value="0" step="any"
                                           style="width:160px"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Guest</td>
                                <td>
                                    <div id="guest_selector" style="padding:2px; border:solid #868686 1px; cursor: pointer; text-align: center">
                                        <b>Select Guest</b>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td align="left" style="width:33%; border:1px solid #d4d4d4;">
                        <table width="100%">
                            <tr>
                                <td>Info</td>
                                <td>
                                    <textarea id="booking_comment" class="formField3" style="width:100%" form="booking_form" name="booking_comment"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Services</td>
                                <td align="left">
                                    <table id="services_table"  style="margin-bottom:4px; border-collapse: collapse; border-spacing: 1px; border:solid #868686 1px;">
                                    </table>
                                    <div id="services_modal_trigger"  style="padding:2px; border:solid #868686 1px;  cursor: pointer; text-align: center">
                                        <b>+ Add Service</b>
                                    </div>
                                </td>
                            </tr>
                        </table>


                    </td>
                </tr>
                <tr>
                    <td align="right" colspan="3">
                        <br>
                        <input class="formButton2" type="submit" value="Submit Booking" style="cursor: pointer">
                    </td>
                </tr>
            </table>
        </div>
    </form>

    <div id="booking_service_modal" style="display:none" title="Add Service">
        <table>
            <tr>
                <td><label for="service_selector">Service</label></td>
                <td>
                    <select name="service_selector" id="service_selector" style="width:160px">
                        <option value="0"><?= $TEXT['add_new_service'] ?></option>
                        <? foreach ($TMPL_all_extra_services as $extra_service) {
                            echo '<option price="' . $extra_service['price'] . '" title="' . $extra_service['title'] . '" value="' . $extra_service['id'] . '" >' . $extra_service['title'] . '</option>';
                        } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="service_title">Title</label></td>
                <td><input type="text" name="service_title" id="service_title" value=""></td>
            </tr>
            <tr>
                <td><label for="service_price">Price</label></td>
                <td><input type="number" id="service_price" name="service_price" min="0" value="0"
                           step="any" style="width:160px"/></td>
            </tr>
        </table>
    </div>

    <div id="booking_guest_modal" style="display: none" title="Select or add new guest">
        <table>
            <tr >
                <td>
                    <input type="radio" name="guest_type" value="non-corporate" id="non_corporate" checked form="booking_form">
                    <label for="non_corporate">Non-corporate</label>
                </td>
                <td>
                    <input type="radio" name="guest_type" value="company" id="company" form="booking_form">
                    <label for="company">Company</label>
                </td>
            </tr>
            <tr>
                <td><label for="guest_id">Guest</label></td>
                <td>
                    <select name="guest_id" id="guest_id" style="width:160px" form="booking_form">
                        <option value="0"><?= $TEXT['select_guest'] ?></option>
                        <? foreach ($TMPL_all_guests as $guest) {
                            echo '<option value="' . $guest['id'] . '" >' . $guest['first_name'] . ' ' . $guest['last_name'] . '</option>';
                        } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="tax">TAX</label></td>
                <td>
                    <Input id="tax_1" type = 'Radio' Name ='tax' value= '1' form="booking_form" checked>TAX
                    <Input id="tax_0" type = 'Radio' Name ='tax' value= '0' form="booking_form">TAX FREE
                </td>
            </tr>
            <tr>
                <td><label for="id_number">ID Number</label></td>
                <td><input type="text" name="id_number" id="id_number" value=""  form="booking_form"></td>
            </tr>
            <tr>
                <td><label for="first_name">F.Name</label></td>
                <td><input type="text" name="first_name" id="first_name" value="" form="booking_form"></td>
            </tr>
            <tr  id="guest_lname_tr">
                <td><label for="last_name">L.Name</label></td>
                <td><input type="text" name="last_name" id="last_name" value="" form="booking_form"></td>
            </tr>
            <tr>
                <td><a href="#" id="id_scan_link">ID Scan</a></td>
                <td align="right"><input class="" type="file" style="width:100%" name="id_scan" form="booking_form"></td>
            </tr>
            <tr>
                <td><label for="guest_id"><?= $TEXT['select_country'] ?></label></td>
                <td>
                    <select name="country" id="country" style="width:160px" form="booking_form">
                        <!--option value="0"><?= $TEXT['select_country'] ?></option-->
                        <? foreach ($TMPL_countries as $country) {
                            echo '<option value="' . $country['id'] . '" >' . $country['geo'] . '</option>';
                        }?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="address">Address</label></td>
                <td><input type="text" name="address" id="address" value="" form="booking_form"></td>
            </tr>
            <tr>
                <td><label for="telephone">Telephone</label></td>
                <td><input type="text" name="telephone" id="telephone" value="" form="booking_form"></td>
            </tr>
            <tr>
                <td><label for="email">Email</label></td>
                <td><input type="text" name="email" id="email" value="" form="booking_form"></td>
            </tr>
            <tr>
                <td><label for="comment">Comment</label></td>
                <td class="tdrow3">
                    <textarea name="comment" id="comment" form="booking_form" style="width:100%"
                              class="formField3"><?= $TMPL_data['comment'] ?></textarea>
                </td>
            </tr>
        </table>
    </div>
    <!--MODAL CUSTOM BOOKING END-->

    <!--script type="text/javascript">
        Calendar.setup({
            inputField: "check_in",     // id of the input field
            ifFormat: "%Y-%m-%d",      // format of the input field
            button: "trigger_c2",  // trigger for the calendar (button ID)
            align: "Br",           // alignment
            timeFormat: "24",
            showsTime: false,
            singleClick: true
        });
    </script>

    <script type="text/javascript">
        Calendar.setup({
            inputField: "check_out",     // id of the input field
            ifFormat: "%Y-%m-%d",      // format of the input field
            button: "trigger_c3",  // trigger for the calendar (button ID)
            align: "Br",           // alignment
            timeFormat: "24",
            showsTime: false,
            singleClick: true
        });
    </script-->

    <br>
    <!-- BOOKING TABLE START -->
    <div id="booking_board_cont" style="position:relative; width:100%; border:solid #3A82CC 1px;">
        <div style="padding:2px; background:#3A82CC; color:#FFF">
            <div style="float:left;">
                Booking Board
            </div>
            <div style="clear: both"></div>
        </div>
        <div id="booking_board_left" style="width:120px; float:left;">
            <table width="100%" class="booking-table">
                <tr>
                    <td rowspan="3">Room</td>
                    <td>Year</td>
                </tr>
                <tr>
                    <td>Month</td>
                </tr>
                <tr>
                    <td>Day</td>
                </tr>
                <? foreach ($TMPL_all_rooms as $room) { ?>
                    <tr>
                        <td colspan="2"><?= $room['name'] ?></td>
                    </tr>
                <? } ?>
            </table>
        </div>
        <div id="booking_board" style="position:relative; width:1px; overflow-x:scroll; margin:0px 0px 0px 120px;">
            <table width="100%" class="booking-table">

                <? foreach ($date_asoc_array as $k => $v) {
                    $days_in_year = 0;
                    foreach ($v as $kk => $vv) {
                        $months_tr .= '<td colspan="' . count($vv) . '">' . date('F', mktime(0, 0, 0, $kk, 10)) . '</td>';
                        foreach ($vv as $kkk => $vvv) {
                            $date_tmp = $k . '-' . $kk . '-' . $kkk;
                            if ($date_tmp < date('Y-m-d')) {
                                $color = '#A8A8A8';
                            } else if ($date_tmp == date('Y-m-d')) {
                                $color = '#29A4F9';
                                $cd_start = '<div id="current_day">';
                                $cd_end = '</div>';
                            } else {
                                $color = '#FDE44E';
                                $cd_start = '<div id="day_'.$date_tmp.'">';
                                $cd_end = '</div>';
                            }
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
                    <?= $days_tr ?>
                </tr>
                <? foreach ($TMPL_all_rooms as $room) { ?>
                    <tr>
                        <? foreach ($TMPL_all_days as $day) {
                            if ($TMPL_common_prices_and_discounts[$room['common_id']][$day]['price'] == '') {
                                $css_class = 'no_price';
                            } else {
                                if ($day < date('Y-m-d')) {
                                    $css_class = 'past_date';
                                } else {
                                    $css_class = 'day_selector';
                                }
                            }
                            ?>
                            <?
                            $price = $TMPL_common_prices_and_discounts[$room['common_id']][$day]['price'];
                            $discount = $TMPL_common_prices_and_discounts[$room['common_id']][$day]['discount'];
                            $generated_price = $price - $price / 100 * $discount;

                            ?>
                            <? if ($TMPL_bookings[$room[id]][$day] == "") {
                                $status = "free";
                                $color = "#FFF";
                                if ($day < date('Y-m-d') || $price == '') {
                                    $color = "#ebebeb";
                                } else {
                                    $color = "#FFF";
                                }
                            } else {
                                $status = $TMPL_bookings[$room[id]][$day];
                                if ($status == 'check_in') {

                                } else if ($status == 'check_out') {

                                } else if ($status == 'in_use') {
                                    $css_class = 'in_use';
                                }
                                $color = "#FFAFAF";

                            } ?>
                            <td bgcolor="<?= $color ?>">
                                <div class="booking-table-day <?= $css_class ?>" discount="<?= $discount ?>"
                                     price="<?= $generated_price ?>" id="<?= $room['id'] ?>-<?= $day ?>"
                                     status="<?= $status ?>" floor="<?= $room['floor'] ?>"
                                     common_id="<?= $room['common_id'] ?>" date="<?= $day ?>"
                                     room_id="<?= $room['id'] ?>" room_name="<?= $room['name'] ?>">
                                    <?= $price == "" ? '?' : $generated_price; ?>
                                </div>
                            </td>
                        <? } ?>
                    </tr>
                <? } ?>
            </table>
        </div>
    </div>
    <!-- BOOKING TABLE END -->

    <!--?if(count($TMPL_all_rooms)>0){?-->
<? } ?>


<script type="text/javascript">
<?
   $counter=0;
   echo "var bookingPlates=[";
   foreach($TMPL_bookings2 AS $booking){
   if($counter!=0){ echo ","; }
        echo "{id:".$booking['id'].", room_id:".$booking['room_id'].", status_id:".$booking['status_id'].", check_in:'".$booking['check_in']."', check_out:'".$booking['check_out']."', guest_name:'".$booking['guest_name']."', booking_id:".$booking['id']."}";
        $counter++;
    }
    echo "];";
?>

function drawBookingPlate(booking) {
    console.log(booking);
    $('#booking_board').append('<div id="booking_plate_' + booking['id'] + '" ></div>');
    var l = $('#' + booking['room_id'] + '-' + booking['check_in']).position().left + $('#' + booking['room_id'] + '-' + booking['check_in']).width() / 2;
    var w = $('#' + booking['room_id'] + '-' + booking['check_out']).position().left - l + $('#' + booking['room_id'] + '-' + booking['check_out']).width() / 2;
    var target = $('#booking_plate_' + booking['id']);
    target.addClass("booking-plate");
    target.css('width', w - 7);
    target.css('left', l + 3);
    var cimcima = '';
    if (booking['status_id'] == 1) {
        target.css('border', 'solid #29A4F9 2px');
        cimcima = '<div style="float:left; margin-right:2px; margin-top:1px;"><img src="/images/cimcima-green.gif"></div>';
    } else if (booking['status_id'] == 2) {
        target.css('border', 'solid #8826fc 2px');
    } else if (booking['status_id'] == 3) {
        target.css('border', 'solid #fc4426 2px');
        cimcima = '<div style="float:left; margin-right:2px; margin-top:1px;"><img src="/images/cimcima-grey.gif"></div>';
    } else if (booking['status_id'] == 4) {
        target.css('border', 'solid #26fc3f 2px');
        cimcima = '<div style="float:left; margin-right:2px; margin-top:1px;"><img src="/images/cimcima-red.gif"></div>';
    } else if (booking['status_id'] == 5) {
        target.css('border', 'solid #626262 2px');
        cimcima = '<div style="float:left; margin-right:2px; margin-top:1px;"><img src="/images/cimcima-white.gif"></div>';
    } else {
        target.css('border', 'solid #29A4F9 2px');
    }
    target.css('top', $('#' + booking['room_id'] + '-' + booking['check_in']).position().top - 1);
    target.attr('title', booking['guest_name']);
    target.append(cimcima + booking['guest_name']);
    target.click(function () {
        window.location = 'index.php?m=booking_management&tab=booking_list&action=view&booking_id=' + booking['id'];
        return false;
    });
}

$(document).ready(function () {
    console.log("document ready");
    //FIX select2 ar mushaobda
    if ($.ui && $.ui.dialog && $.ui.dialog.prototype._allowInteraction) {
        var ui_dialog_interaction = $.ui.dialog.prototype._allowInteraction;
        $.ui.dialog.prototype._allowInteraction = function (e) {
            if ($(e.target).closest('.select2-dropdown').length) return true;
            return ui_dialog_interaction.apply(this, arguments);
        };
    }
    //FIX select2 ar mushaobda

    $('#service_selector').on("change", function (e) {

        if ($(this).val() == 0) {
            $("#service_title").val('');
            $("#service_title").attr("readonly", false);
            $("#service_price").val(0);
            $("#service_price").attr("readonly", false);
        } else {
            $("#service_title").val($('option:selected', this).attr('title'));
            $("#service_title").attr("readonly", true);
            $("#service_price").val($('option:selected', this).attr('price'));
            $("#service_price").attr("readonly", true);

        }
    });

    function drowAddedServices() {
        $("#services_table td").parent().remove();
        for (var i = 0; i < addedServices.length; ++i) {
            var service_html = '';
            service_html = '<tr><td><input id="service[' + i + '][id]" type="hidden" value="' + addedServices[i]["id"] + '"  name="service[' + i + '][id]">';
            service_html += '<input id="service[' + i + '][title]" type="text" value="' + addedServices[i]["title"] + '" readonly="" name="service[' + i + '][title]"></td>';
            service_html += '<td><input id="service[' + i + '][price]" type="text" value="' + addedServices[i]["price"] + '" style="width:60px" readonly="" name="service[' + i + '][price]"></td>';
            service_html += '<td><img class="service_remove_button" array_id="' + i + '" width="16" height="16" border="0" align="middle" src="./images/icos16/delete.gif" alt="delete"></td></tr>';
            $('#services_table').append(service_html);
        }
    }

    $('.service_remove_button').live('click', function () {
        var id = $(this).attr('array_id');
        var tmp = [];
        for (var i = 0; i < addedServices.length; ++i) {
            if (i != id) {
                tmp.push(addedServices[i]);

            }
        }
        addedServices = tmp;
        drowAddedServices();
        updatePrice();
    });

    var general_price = 0;

    function updatePrice() {
        general_price = 0;
        for (var i = 0; i < selectedDaysArray.length - 1; i++) {
            general_price += Number($('#' + selectedDaysArray[i]).attr('price'));
        }
        for (var i = 0; i < addedServices.length; i++) {
            general_price += Number(addedServices[i]['price']);
        }
        $("#general_price").val(general_price);
    }


    $("#guest_id").select2({
        placeholder: "Select a guest",
        allowClear: false
    });

    $('#guest_id').on("change", function (e) {
        if ($(this).val() == 0) {
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
            $("#tax_1").prop('disabled', false);
            $("#tax_0").prop('checked', false);
            $("#tax_0").prop('disabled', false);
            return;
        }else{

        }
        var request = $.ajax({
            url: "index_ajax.php?cmd=get_user_info",
            method: "POST",
            data: {guest_id: $(this).val()},
            dataType: "json"
        });

        request.done(function (msg) {
            $("#id_number").val(msg.id_number);
            $("#first_name").val(msg.first_name);
            $("#last_name").val(msg.last_name);
            $("#id_scan_link").attr('href', '../uploads_script/guests/' + msg.id_scan);
            $("#id_scan_link").attr('target', '_blank');
            $("#country").val(msg.country);
            $("#address").val(msg.address);
            $("#telephone").val(msg.telephone);
            $("#email").val(msg.email);
            $("#comment").val(msg.comment);
            $("#comment").attr('readonly', 'readonly');
            if(msg.tax==1){
                $("#tax_1").prop('checked', true);
                $("#tax_1").prop('disabled', true);
                $("#tax_0").prop('checked', false);
                $("#tax_0").prop('disabled', true);
            }else{
                $("#tax_1").prop('checked', false);
                $("#tax_1").prop('disabled', true);
                $("#tax_0").prop('checked', true);
                $("#tax_0").prop('disabled', true);
            }

            // console.log( msg );
        });

        request.fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });
    });


    var from = {
        room_id: 0,
        room_name: '',
        date: 0
    };
    var to = {
        room_id: 0,
        room_name: '',
        date: 0
    };
    var selectedDaysArray = [];
    var price = 0;


    $(".day_selector").click(function () {
        if (from.room_id == 0) {
            obnuliai();
            from.room_id = $(this).attr("room_id");
            from.room_name = $(this).attr("room_name");
            from.date = $(this).attr("date");
            $(this).css("background", "#ff7f7f");
        } else if (from.room_id == $(this).attr("room_id")) {
            //to daemtxva from-s
            if (from.date == $(this).attr("date")) {
                console.log("to=from", $(this).attr("date"));
                return;
            }
            ///to daemtxva from-s
            to.room_id = $(this).attr("room_id");
            to.room_name = $(this).attr("room_name");
            to.date = $(this).attr("date");
            //tu [to naklebia fromze]
            if (to.date < from.date) {
                var tmp = to;
                to = from;
                from = tmp;
            }
            //tu [to naklebia fromze]
            //gaaferade monishnili periodi
            price = 0;
            $(".day_selector").each(function () {
                if ($(this).attr("room_id") == from.room_id && $(this).attr("date") >= from.date && $(this).attr("date") <= to.date) {
                    //tu archeul periodshi moxvda erti mainc dajavshnuli dge
                    if (($(this).attr("status") == 'check_in' && $(this).attr("date") != to.date) || ($(this).attr("status") == 'check_out' && $(this).attr("date") != from.date)) {
                        alert("romeligac dge moyva dakavebuli");
                        obnuliai();
                        return;
                    }
                    ///tu archeul periodshi moxvda erti mainc dajavshnuli dge
                    $(this).css("background", "#ff7f7f");
                    selectedDaysArray.push($(this).attr("id"));
                }
            });
            updatePrice();
            //gaaferade monishnili periodi

            $("#check_in").val(from.date);
            $("#check_out").val(to.date);
            $("#room_id").val(to.room_id);

            console.log("room:", to.room_id, "from:", from.date, "to:", to.date);
            to.room_id = 0;
            from.room_id = 0;
        } else {
            console.log("from.room_id", from.room_id);
            $("#" + from.room_id + "-" + from.date).css("background", "rgba(255,255,255,0)");
            from.room_id = 0;
            alert("selected rooms are not the same");

        }
    });
    function obnuliai() {
        $("#" + from.room_id + "-" + from.date).css("background", "rgba(255,255,255,0)");
        $("#" + to.room_id + "-" + to.date).css("background", "rgba(255,255,255,0)");
        to.room_id = 0;
        from.room_id = 0;

        //dzveli monishnulis gasuftaveba
        for (var i = 0; i < selectedDaysArray.length; i++) {
            $('#' + selectedDaysArray[i]).css("background", "rgba(255,255,255,0)");
        }
        selectedDaysArray = [];
        //dzveli monishnulis gasuftaveba
        updatePrice();

    }

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
        if ($("#guest_id").val() == 0) {
            if ($("#id_number").val() == '') {
                alert("Please Enter ID Number");
                return false;
            }
            if ($("#first_name").val() == '') {
                alert("Please Enter First Name");
                return false;
            }
            if ($("#address").val() == '') {
                alert("Please Enter Address");
                return false;
            }
            if ($("#telephone").val() == '') {
                alert("Please Enter Telephone");
                return false;
            }
            if ($("#email").val() == '') {
                alert("Please Enter Email");
                return false;
            }
        }
        return true;
    }

    $("#check_in").change(function () {
        validateBookingData();
    });
    $("#check_out").change(function () {
        validateBookingData();
    });
    $("#room_id").change(function () {
        validateBookingData();
    });

    function validateBookingData() {
        if ($("#check_in").val() != '' && $("#check_out").val() != '' && $("#room_id").val() != 0) {
            alert("val");
        }

    }

    $("#booking_board").scrollLeft(0);

    var addedServices = [];
    $("#services_modal_trigger").click(function () {
        $("#booking_service_modal").dialog({
            resizable: false,
            width: 400,
            modal: true,
            buttons: {
                "Add": function () {
                    $(this).dialog("close");
                    if ($("#service_selector").val() == 0) {
                        if ($("#service_title").val() == '') {
                            alert("Set title to service");
                            return;
                        }
                    }
                    var tmp = {};
                    tmp['id'] = $("#service_selector").val();
                    tmp['title'] = $("#service_title").val();
                    tmp['price'] = $("#service_price").val();
                    addedServices.push(tmp);
                    drowAddedServices();
                    updatePrice();
                },
                Cancel: function () {
                    $(this).dialog("close");
                }
            }
        });
    });

    $("#guest_selector").click(function () {
        $("#booking_guest_modal").dialog({
            resizable: false,
            width: 400,
            modal: true,
            buttons: {
                "Add": function () {
                    $(this).dialog("close");
                    $('#guest_selector').html($('#first_name').val() + ' ' + $('#last_name').val());
                },
                Cancel: function () {
                    $(this).dialog("close");
                }
            }
        });
    });


    $( "#check_in" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        minDate: 'today',
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() + (1000*60*60*24));
            $( "#check_out" ).datepicker( "option", "minDate",date );
            scrollBoard("day_"+selectedDate);
        }
    });
    $( "#check_out" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        minDate: 'today',
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() - (1000*60*60*24));
            $( "#check_in" ).datepicker( "option", "maxDate", date );
        }
    });

    $("input[type='radio'][name='guest_type']").change(function(event) {
        if($(this).val()=='non-corporate'){
            $('label[for="id_number"]').text("Guest ID");
            $('label[for="first_name"]').text("F.Name");
            $('label[for="last_name"]').text("L.Name");
            $('#guest_lname_tr').show();
        }else{
            $('label[for="id_number"]').text("Company ID");
            $('label[for="first_name"]').text("Company Name");
            $('#guest_lname_tr').hide();
        }
        $("input[type='hidden'][name='guest_type']").val($(this).val());
    });

    function scrollBoard(target_id) {
        $("#booking_board").scrollLeft(0);
        var position = $("#"+target_id).position();
        $(target_id).css("background","red");
        $("#booking_board").scrollLeft(position.left - 400);
    }
    var w = $("#booking_board_cont").width() - $("#booking_board_left").width();
    $("#booking_board").width(w);
    for (var i = 0; i < bookingPlates.length; ++i) {
        drawBookingPlate(bookingPlates[i]);
    }
    scrollBoard('current_day');
});
$(window).load(function () {
    console.log("window load");


    /*
    var position = $("#current_day").position();
    $("#booking_board").scrollLeft(position.left - 400);
    console.log(position.left);*/
});

</script>
