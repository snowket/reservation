<? if (!defined('ALLOW_ACCESS')) exit; ?>

<style>
    #sticky .stick_item {
        float: left;
        padding: 4px 4px 0px 4px;
        border-right: solid #FFFFFF 1px;
        height: 20px;
    }
</style>

<div id="sticky-anchor"></div>
<div id="sticky" style="background-color: #3a82cc; color:#FFFFFF; display: inline-block">
    <div style="width:151px;" class="stick_item"><?= $TEXT['all_rooms']['block'] ?></div>
    <div style="width:158px;" class="stick_item"><?= $TEXT['all_rooms']['room_type'] ?></div>
    <div style="width:153px;" class="stick_item"><?= $TEXT['all_rooms']['room_capacity'] ?></div>
    <div style="width:66px;" class="stick_item"><?= $TEXT['all_rooms']['floor'] ?></div>
    <div style="width:80px;" class="stick_item"><?= $TEXT['all_rooms']['name'] ?></div>
    <div style="width:103px;" class="stick_item"><?= $TEXT['all_rooms']['description'] ?></div>
    <div style="width:39px;" class="stick_item"><?= $TEXT['all_rooms']['online'] ?></div>
    <div style="width:39px;" class="stick_item"><?= $TEXT['all_rooms']['local'] ?></div>
    <div style="width:71px;" class="stick_item"><?= $TEXT['all_rooms']['action'] ?></div>
</div>
<table class="table-table" cellpadding="2" cellspacing="0">
    <? foreach ($TMPL_blocks as $block) { ?>
        <tr>
            <td class="table-td" style="width:150px;"><?= $block['title'] ?></td>
            <td class="table-td">
                <table>
                    <? foreach ($TMPL_pricePlans as $pricePlan_id => $pricePlan) {
                        if ($pricePlan['block_id'] == $block['id']) { ?>
                            <tr>
                                <td class="table-td"
                                    style="width:150px;"><?= $TMPL_roomTypes[$pricePlan['type_id']]['title'] ?></td>
                                <td class="table-td" style="width:150px;">
                                    <?= $TMPL_roomCapacities[$pricePlan['capacity_id']]['title'] ?>
                                </td>
                                <td class="table-td">
                                    <table id="common_<?= $pricePlan_id ?>">
                                        <? foreach ($TMPL_rooms as $room) {
                                            if ($pricePlan_id == $room['common_id']) { ?>
                                                <tr id="room_tr_<?= $room['id'] ?>" class="table-tr">
                                                    <td class="table-td" style="width:56px;"
                                                        id="room_floor_<?= $room['id'] ?>"><?= $room['floor'] ?></td>
                                                    <td class="table-td" style="width:50px;"
                                                        id="room_name_<?= $room['id'] ?>">
                                                        <div
                                                            style="width:75px; overflow: hidden; white-space: nowrap;"><?= $room['name'] ?></div>
                                                    </td>
                                                    <td class="table-td">
                                                        <div id="room_description_<?= $room['id'] ?>"
                                                             style="width:100px; overflow: hidden; "
                                                             title="<?= $room['description'] ?>"><?= $room['description'] ?></div>
                                                    </td>
                                                    <td class="table-td" align="center" style="width:36px;">
                                                        <div style="cursor:pointer" class="room_restriction_trigger"
                                                             type="dnr_online"
                                                             id="dnr_online_<?= $room['id'] ?>"
                                                             room_id="<?= $room['id'] ?>"
                                                             for_web="<?= $room['for_web'] ?>"
                                                             title="<?= $TEXT['all_rooms']['online_booking'] ?>">
                                                            <img src="./images/icos16/link_<?=$room['for_web']?>.png">
                                                        </div>
                                                    </td>
                                                    <td class="table-td" align="center" style="width:36px;">
                                                        <div style="cursor:pointer" class="room_restriction_trigger"
                                                             type="dnr_local"
                                                             id="dnr_local_<?= $room['id'] ?>"
                                                             room_id="<?= $room['id'] ?>"
                                                             for_local="<?= $room['for_local'] ?>"
                                                             title="<?= $TEXT['all_rooms']['local_booking'] ?>">
                                                            <img src="./images/icos16/locked_<?=$room['for_local']?>.png">
                                                        </div>
                                                    </td>
                                                    <td class="table-td" align="center" style="width:24px;">
                                                        <div style="cursor:pointer" id="edit_room_<?= $room['id'] ?>"
                                                             class="room_edit_trigger" common_id="<?= $pricePlan_id ?>"
                                                             room_id="<?= $room['id'] ?>" floor="<?= $room['floor'] ?>"
                                                             room_title="<?= $room['name'] ?>"
                                                             title="<?= $TEXT['all_rooms']['edit'] ?>">
                                                            <img src="./images/icos16/edit.gif">
                                                        </div>
                                                    </td>
                                                    <td class="table-td" align="center" style="width:24px;">
                                                        <div style="cursor:pointer" class="room_del_trigger"
                                                             room_id="<?= $room['id'] ?>"
                                                             title="<?= $TEXT['all_rooms']['delete'] ?>">
                                                            <img src="./images/icos16/delete.gif">
                                                        </div>
                                                    </td>
                                                </tr>
                                            <? }
                                        } ?>
                                    </table>

                                    <table>
                                        <tr>
                                            <td class="table-td">
                                                <input id="add_floor_<?= $pricePlan_id ?>" type="number" value="1" style="width:56px;">
                                            </td>
                                            <td class="table-td">
                                                <input id="add_name_<?= $pricePlan_id ?>" type="text" style="width:75px;" placeholder="<?= $TEXT['edit_room_modal']['name'] ?>">
                                            </td>
                                            <td class="table-td">
                                                <input id="add_description_<?= $pricePlan_id ?>" type="text" style="width:100px;" placeholder="<?= $TEXT['edit_room_modal']['description'] ?>">
                                            </td>
                                            <td class="table-td" align="center">
                                                <div style="cursor:pointer; width:36px; padding-top: 3px"
                                                     class="add_room_for_web" id="add_room_for_web_<?= $pricePlan_id ?>"
                                                     for_web="0" title="<?= $TEXT['all_rooms']['online_booking'] ?>">
                                                    <img src="./images/icos16/disabled.png">
                                                </div>
                                            </td>
                                            <td class="table-td" align="center">
                                                <div style="cursor:pointer; width:36px; padding-top: 3px"
                                                     class="add_room_for_local" id="add_room_for_local_<?= $pricePlan_id ?>"
                                                     for_local="1" title="<?= $TEXT['all_rooms']['local_booking'] ?>">
                                                    <img src="./images/icos16/locked_1.png">
                                                </div>
                                            </td>
                                            <td class="table-td" align="center">
                                                <div class="add_room_but" common_id="<?= $pricePlan_id ?>"
                                                     style="cursor:pointer; padding: 2px; width: 56px"
                                                     title="<?= $TEXT['all_rooms']['add'] ?>">
                                                    <img src="./images/icos16/add.png">
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        <? }
                    } ?>
                </table>
            </td>
        </tr>
    <? } ?>
</table>

<div id="del_room_modal" style="display: none; width:400px;" title="<?= $TEXT['del_room_modal']['title'] ?>">
    <div id="del_room_modal_message"></div>
    <div><?= $TEXT['del_room_modal']['message'] ?></div>
</div>

<div id="edit_room_modal" style="display:none; " title="<?= $TEXT['edit_room_modal']['title'] ?>">
    <div id="edit_room_modal_message"></div>
    <table width="100%">
        <tr>
            <td><?= $TEXT['edit_room_modal']['name'] ?></td>
            <td><input type="text" name="edit_title" id="edit_title" value="" style="width: 100%"></td>
        </tr>
        <tr>
            <td><?= $TEXT['edit_room_modal']['floor'] ?></td>
            <td><input type="number" name="edit_floor" id="edit_floor" min="0" value="1"  style="width: 100%"></td>
        </tr>
        <tr>
            <td><?= $TEXT['edit_room_modal']['type'] ?></td>
            <td>
                <select name="edit_common_id" id="edit_common_id" style="width: 100%">
                    <? foreach ($TMPL_pricePlans as $pricePlan_id => $pricePlan) { ?>
                        <option
                            value="<?= $pricePlan_id ?>"><?= $TMPL_blocks[$pricePlan['block_id']]['title'] . " | " . $TMPL_roomTypes[$pricePlan['type_id']]['title'] . " | " . $TMPL_roomCapacities[$pricePlan['capacity_id']]['title'] ?></option>
                    <? } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?= $TEXT['edit_room_modal']['description'] ?></td>
            <td>
                <textarea name="edit_description" id="edit_description"  style="width: 100%"></textarea>
            </td>
        </tr>
    </table>

</div>


<div id="restrictions_tooltip" style="position: absolute; display: none;">
    <div id="restrictions_tooltip_title" style="position: relative; width:338px; background-color: #3a82cc; color:#FFFFFF; padding: 4px;">
    </div>
    <div id="close_restrictions_tooltip" style="position: absolute; right: 6px; top:6px; cursor: pointer"><img src="./images/icos16/cancel.png"></div>
    <div style="position: relative; width:334px;">
        <div
            style=" margin-left: auto; top: 0px; width:334px;  padding:4px; background-color: #FFFFFF;  border: 2px solid #3a82cc;">
            <table>
                <tr>
                    <td colspan="3"><?=$TEXT['all_rooms']['restriction_rule']?></td>
                </tr>
                <tr>
                    <td align="left" colspan="3">
                        <form id="restriction_form" method="post">
                            <input type="hidden" name="action" value="restriction_online_booking">
                            <input type="hidden" name="restriction_type" value="">
                            <input type="hidden" name="room_id" value="0">
                            <input type="text" id="period_start" name="period_start" style="float:left"
                                   value="<?= CURRENT_DATE ?>" class="calendar-icon" autocomplete="off"/>

                            <input type="text" id="period_end" name="period_end" style="float:left"
                                   value="<?= date('Y-m-d', strtotime('+1 day', strtotime(CURRENT_DATE))) ?>"
                                   class="calendar-icon" autocomplete="off"/>
                            <div id="add_restriction_trigger" style="position:relative; border:solid #3a82cc 1px; width:80px; cursor: pointer; float:left; padding: 2px; margin-left: 2px;">
                                <img src="./images/icos16/add.png">
                                <div style="position: absolute; left: 22px; top:2px"><?=$TEXT['all_rooms']['add_restriction']?></div>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <div id="tt_comment">

                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="tooltip-arrow">
        </div>
    </div>
</div>


<style>
    #restrictions_tooltip {

    }

    #restrictions_tooltip table {
        color: #3a82cc;
        background-color: #FFFFFF;
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
    }

    #restrictions_tooltip td {
        border: 1px solid #b9b9b9;
        padding: 2px;
    }

    .tooltip-arrow {
        position: absolute;
        right: -17px;
        width: 0px;
        height: 0px;
        top: -2px;
        border-style: solid;
        border-width: 5px 0px 5px 5px;
        border-color: transparent transparent transparent #007bff;
    }
</style>


<script type="text/javascript">
    $(document).ready(function () {
        $("#period_start").datepicker({
            defaultDate: "today",
            changeMonth: true,
            minDate: 'today',
            numberOfMonths: 1,
            dateFormat: 'yy-mm-dd',
            onSelect: function (selectedDate) {
                var date = $(this).datepicker('getDate');
                date.setTime(date.getTime() + (1000 * 60 * 60 * 24));
                $("#period_end").datepicker("option", "minDate", date);
                //scrollBoard("day_"+selectedDate);
            }
        });

        $("#period_end").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            minDate: 'tomorrow',
            numberOfMonths: 1,
            dateFormat: 'yy-mm-dd',
            onSelect: function (selectedDate) {
                var date = $(this).datepicker('getDate');
                date.setTime(date.getTime() - (1000 * 60 * 60 * 24));
                $("#period_start").datepicker("option", "maxDate", date);
            }
        });

        $('.add_room_for_web').live("click", function () {
            if ($(this).attr('for_web') == 1) {
                $(this).html('<img src="./images/icos16/link_0.png">');
                $(this).attr('for_web', 0);
            } else {
                $(this).html('<img src="./images/icos16/link_1.png">');
                $(this).attr('for_web', 1);
            }
        });
        $('.add_room_for_local').live("click", function () {
            if ($(this).attr('for_local') == 1) {
                $(this).html('<img src="./images/icos16/locked_0.png">');
                $(this).attr('for_local', 0);
            } else {
                $(this).html('<img src="./images/icos16/locked_1.png">');
                $(this).attr('for_local', 1);
            }
        });


        $('.add_room_but').live("click", function () {
            var common_id = $(this).attr('common_id');
            var request = $.ajax({
                url: "index_ajax.php?cmd=add_room",
                method: "POST",
                data: {
                    floor: $("#add_floor_" + common_id).val(),
                    name: $("#add_name_" + common_id).val(),
                    description: $("#add_description_" + common_id).val(),
                    common_id: common_id,
                    for_web: $("#add_room_for_web_" + common_id).attr('for_web'),
                    for_local: $("#add_room_for_local_" + common_id).attr('for_local')
                },
                dataType: "json"
            });

            request.done(function (msg) {
                if (msg != null && msg['error'] == 1) {
                    console.log(msg['error_message']);
                } else {
                    var tr = '<tr class="table-tr" id="room_tr_' + msg.room_id + '">';
                    tr += '<td id="room_floor_' + msg.room_id + '" style="width:56px;" class="table-td">' + msg.floor + '</td>';
                    tr += '<td id="room_name_' + msg.room_id + '" style="width:50px;" class="table-td">';
                    tr += '<div style="width:50px; overflow: hidden; white-space: nowrap;">' + msg.name + '</div>';
                    tr += '</td>';
                    tr += '<td class="table-td">';
                    tr += '<div title="' + msg.description + '" style="width:100px; overflow: hidden; " id="room_description_' + msg.room_id + '">' + msg.description + '</div>';
                    tr += '</td>';

                    tr += '<td align="center" style="width:36px;" class="table-td">';
                    tr += '<div type="dnr_online" for_web="' + msg.for_web + '" room_id="' + msg.room_id + '" id="dnr_online_' + msg.room_id + '" class="room_restriction_trigger" style="cursor:pointer">';
                    tr += '<img src="./images/icos16/link_'+msg.for_web+'.png">';
                    tr += '</div>';
                    tr += '</td>';

                    tr += '<td align="center" style="width:36px;" class="table-td">';
                    tr += '<div type="dnr_local" for_local="' + msg.for_local + '" room_id="' + msg.room_id + '" id="dnr_local_' + msg.room_id + '" class="room_restriction_trigger" style="cursor:pointer">';
                    tr += '<img src="./images/icos16/locked_'+msg.for_local+'.png">';
                    tr += '</div>';
                    tr += '</td>';

                    tr += '<td align="center" style="width:24px;" class="table-td">';
                    tr += '<div room_title="' + msg.name + '" floor="' + msg.floor + '" room_id="' + msg.room_id + '" common_id="' + msg.common_id + '" class="room_edit_trigger" id="edit_room_' + msg.room_id + '" style="cursor:pointer">';
                    tr += '<img src="./images/icos16/edit.gif">';
                    tr += '</div>';
                    tr += '</td>';
                    tr += '<td align="center" style="width:24px;" class="table-td">';
                    tr += '<div room_id="' + msg.room_id + '" class="room_del_trigger" style="cursor:pointer">';
                    tr += '<img src="./images/icos16/delete.gif">';
                    tr += '</div>';
                    tr += '</td>';
                    tr += '</tr>';
                    $('#common_' + msg.common_id + ' tr:last').after(tr);
                }
            });

            request.fail(function (jqXHR, textStatus) {
                alert(textStatus);
            });
        });

        $('.room_restriction_trigger').live("dblclick", function () {
            var request = $.ajax({
                url: "index_ajax.php?cmd=change_room_restriction_status",
                method: "POST",
                data: {
                    room_id: $(this).attr('room_id'),
                    type: $(this).attr('type'),

                },
                dataType: "json"
            });

            request.done(function (msg) {
                if (msg.error == 0) {
                    if(msg.type=="dnr_online"){
                        $("#dnr_online_" + msg.room_id + " img").attr("src", "./images/icos16/link_"+msg.value+".png");
                        $("#dnr_online_" + msg.room_id).attr("for_online", msg.value);
                    }else{
                        $("#dnr_local_" + msg.room_id + " img").attr("src", "./images/icos16/locked_"+msg.value+".png");
                        $("#dnr_local_" + msg.room_id).attr("for_local", msg.value);
                    }

                } else {
                    alert(msg.message);
                }
            });

            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);

            });
        });


        var restriction_popup_opened = 0;
        $('.room_restriction_trigger').live({
            click: function () {
                if (restriction_popup_opened == 1) {
                    $('#restrictions_tooltip').hide();
                }
                restriction_popup_opened = 1;
                var room_id = $(this).attr('room_id');
                var type = $(this).attr('type');
                if(type=="dnr_online"){
                    $('#restrictions_tooltip_title').html("<?=$TEXT['all_rooms']['dnr_online_title']?>");
                }else{
                    $('#restrictions_tooltip_title').html("<?=$TEXT['all_rooms']['dnr_local_title']?>");
                }
                $("#restriction_form input[name=room_id]").val(room_id);
                $("#restriction_form input[name=restriction_type]").val(type);

                var offset = $(this).offset();
                $('#restrictions_tooltip').css('top', offset.top - 20);
                $('#restrictions_tooltip').css('left', offset.left - $('#restrictions_tooltip').width() - 10);
                $('#period_start').val('<?=CURRENT_DATE?>');
                $('#period_end').val('<?= date('Y-m-d', strtotime('+1 day', strtotime(CURRENT_DATE))) ?>');
                updateRestrictionTable($(this).attr('room_id'), type);
                $('#restrictions_tooltip').fadeIn("slow");

            },
            mouseleave: function () {
                //$('#restrictions_tooltip').hide();
            },
            mouseenter: function () {
                /*  var offset = $(this).offset();
                 $('#restrictions_tooltip').css('top', offset.top - 6);
                 $('#restrictions_tooltip').css('left', offset.left - $('#restrictions_tooltip').width() / 2 + $(this).width() / 2);
                 $('#restrictions_tooltip').show();*/
            },
            dblclick: function () {

            }

        });

        $(document).mouseup(function (e) {
            if (restriction_popup_opened == 1 && !$.contains($("#restrictions_tooltip").get(0), e.target) && !$.contains($("#ui-datepicker-div").get(0), e.target)) {
                $("#restrictions_tooltip").fadeOut();
            }
        });


        $("#close_restrictions_tooltip").click(function(){
            $("#restrictions_tooltip").fadeOut();
        });

        function updateRestrictionTable(room_id, restriction_type) {
            var request = $.ajax({
                url: "index_ajax.php?cmd=get_room_restrictions",
                method: "POST",
                data: {
                    room_id: room_id,
                    type: restriction_type
                },
                dataType: "json"
            });

            request.done(function (msg) {
                var restrictions = msg.restrictions;
                var html = "<table>";
                if (restrictions.length == 0) {
                    html += "<tr>";
                    html += "<td align='center'><div><?=$TEXT['all_rooms']['no_restriction']?></div></td>";
                    html += "</tr>";
                } else {
                    for (var i = 0; i < restrictions.length; i++) {
                        html += "<tr>";
                        html += "<td width='115'>" + restrictions[i]['from_date'] + "</td>";
                        html += "<td width='115'>" + restrictions[i]['to_date'] + "</td>";
                        html += "<td align='center'><div class='delete-restriction-trigger' restriction_id='" + restrictions[i].id + "' style='cursor: pointer'><img src=\"./images/icos16/delete.gif\"></div></td>";
                        html += "</tr>";
                    }
                }

                html += "</table>";
                $('#tt_comment').html(html);
            });

            request.fail(function (jqXHR, textStatus) {
                var html = "<table>";
                html += "<tr>";
                html += "<td><div>Server Error</div></td>";
                html += "</tr>";
                html += "</table>";
                $('#tt_comment').html(html);
            });
        }

        $("#add_restriction_trigger").live("click", function () {
            var room_id=$('#restriction_form input[name=room_id]').val();
            var restriction_type=$('#restriction_form input[name=restriction_type]').val();
            var from_date=$('#restriction_form input[name=period_start]').val();
            var to_date=$('#restriction_form input[name=period_end]').val();
            var request = $.ajax({
                url: "index_ajax.php?cmd=add_room_restriction",
                method: "POST",
                data: {
                    room_id: room_id,
                    type: restriction_type,
                    from_date: from_date,
                    to_date: to_date
                },
                dataType: "json"
            });

            request.done(function (json) {
                if(json.error==0){
                    updateRestrictionTable(json.room_id, json.type);
                }else{
                    var html = "<table>";
                    html += "<tr>";
                    html += "<td><div>"+json.message+"</div></td>";
                    html += "</tr>";
                    html += "</table>";
                    $('#tt_comment').html(html);
                }

            });

            request.fail(function (jqXHR, textStatus) {
                var html = "<table>";
                html += "<tr>";
                html += "<td><div>"+textStatus+"</div></td>";
                html += "</tr>";
                html += "</table>";
                $('#tt_comment').html(html);
            });


        });

        $(".delete-restriction-trigger").live("click", function () {
            var restriction_id=$(this).attr('restriction_id');
            var request = $.ajax({
                url: "index_ajax.php?cmd=delete_room_restriction",
                method: "POST",
                data: {
                    restriction_id: restriction_id
                },
                dataType: "json"
            });

            request.done(function (json) {
                if(json.error==0){
                    updateRestrictionTable(json.room_id, json.type);
                }else{
                    var html = "<table>";
                    html += "<tr>";
                    html += "<td><div>"+json.message+"</div></td>";
                    html += "</tr>";
                    html += "</table>";
                    $('#tt_comment').html(html);
                }

            });

            request.fail(function (jqXHR, textStatus) {
                var html = "<table>";
                html += "<tr>";
                html += "<td><div>"+textStatus+"</div></td>";
                html += "</tr>";
                html += "</table>";
                $('#tt_comment').html(html);
            });
        });

        $(".room_edit_trigger").live("click", function () {
            $("#edit_title").val($(this).attr("room_title"));
            $("#edit_floor").val($(this).attr("floor"));
            $("#edit_common_id").val($(this).attr("common_id"));
            var room_id = $(this).attr("room_id");
            $("#edit_description").val($("#room_description_" + room_id).attr('title'));

            $('#edit_room_modal').dialog({
                modal: true,
                draggable: false,
                resizable: false,
                buttons: {
                    '<?=$TEXT['edit_room_modal']['yes']?>': function () {
                        var request = $.ajax({
                            url: "index_ajax.php?cmd=edit_room",
                            method: "POST",
                            data: {
                                room_id: room_id,
                                floor: $("#edit_floor").val(),
                                name: $("#edit_title").val(),
                                description: $("#edit_description").val(),
                                common_id: $("#edit_common_id").val()
                            },
                            dataType: "json"
                        });

                        request.done(function (msg) {
                            if (msg != null && msg['error'] == 1) {
                                $('#edit_room_modal_message').text(msg['error_message']);
                            } else {
                                $("#room_floor_" + msg.room_id).text(msg.floor);
                                $("#room_name_" + msg.room_id).text(msg.name);
                                $("#room_description_" + msg.room_id).text(msg.description);
                                $("#edit_room_" + msg.room_id).attr('room_title', msg.name);
                                $("#edit_room_" + msg.room_id).attr('floor', msg.floor);
                                if ($("#edit_room_" + msg.room_id).attr('common_id') != msg.common_id) {
                                    var tr = $("#room_tr_" + msg.room_id);
                                    $("#room_tr_" + msg.room_id).remove();
                                    $('#common_' + msg.common_id + ' tr:last').after(tr);
                                }
                                $("#edit_room_" + msg.room_id).attr('common_id', msg.common_id);
                                $("#edit_room_modal").dialog("close");
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            $('#edit_room_modal_message').text('დაფიქსირდა შეცდომა!');
                        });
                    },
                    '<?=$TEXT['edit_room_modal']['no']?>': function () {
                        $(this).dialog('close');
                    }
                }
            });

            $('#edit_room_modal').dialog('open');
            var $target = $('#edit_room_modal').dialog().parent();
            $target.css('top', (window.innerHeight - $target.height()) / 2);
            $target.css('left', (window.innerWidth - $target.width()) / 2);
            $target.css('position', 'fixed');

        });


        var del_room_modal_invoker;
        $(".room_del_trigger").live("click", function () {
            del_room_modal_invoker = $(this);
            $('#del_room_modal').dialog({
                resizable: false,
                width: 240,
                modal: true,
                buttons: {
                    '<?=$TEXT['del_room_modal']['yes']?>': function () {
                        var request = $.ajax({
                            url: "index_ajax.php?cmd=delete_room",
                            method: "POST",
                            data: {
                                room_id: del_room_modal_invoker.attr('room_id')
                            },
                            dataType: "json"
                        });

                        request.done(function (msg) {
                            if (msg != null && msg['error'] == 1) {
                                $('#del_room_modal_message').text(msg['error_message']);
                            } else {
                                $("#room_tr_" + msg.room_id).remove();
                                $("#del_room_modal").dialog("close");
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            $('#del_room_modal_message').text('დაფიქსირდა შეცდომა!');
                        });
                    },
                    '<?=$TEXT['del_room_modal']['no']?>': function () {
                        $(this).dialog('close');
                    }
                }
            });
            var $target = $('#del_room_modal').dialog().parent();
            $target.css('top', (window.innerHeight - $target.height()) / 2);
            $target.css('left', (window.innerWidth - $target.width()) / 2);
            $target.css('position', 'fixed');
        });


    })
    ;

    $(function () {
        $(window).scroll(sticky_relocate);
        sticky_relocate();
    });

    function sticky_relocate() {
        var window_top = $(window).scrollTop();
        var div_top = $('#sticky-anchor').offset().top;
        if (window_top > div_top) {
            $('#sticky').addClass('stick');
        } else {
            $('#sticky').removeClass('stick');
        }
    }

</script>