 <!--GUEST MODAL START-->
<div id="booking_guest_modal" style="display: none; width:600px" title="<?= $TEXT['guest_modal']['title'] ?>">
    <div id="guest_modal_message"></div>
    <form name="guest_modal_form" id="guest_modal_form">
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
            <tr>
                <td style="min-width: 130px">
                    <?= $TEXT['guest_modal']['guest_type'] ?>
                </td>
                <td style="border:solid gray 1px;">
                    <input type="radio" name="guest_type" value="non-corporate" id="non_corporate" checked>
                    <label for="non_corporate"><?= $TEXT['guest_modal']['non_corporate'] ?></label>
                    <input type="radio" name="guest_type" value="company" id="company">
                    <label for="company"><?= $TEXT['guest_modal']['company'] ?></label>
                    <input type="radio" name="guest_type" value="tour-company" id="tour-company">
                    <label for="tour-company"><?= $TEXT['guest_modal']['tour_company'] ?></label>
                </td>
            </tr>
            <tr>
                <td><label for="tax"><?= $TEXT['guest_modal']['tax'] ?></label></td>
                <td style="border:solid gray 1px;">
                    <Input id="tax_1" type='Radio' Name='tax' value='1'
                           checked><?= $TEXT['guest_modal']['tax_included'] ?>
                    <Input id="tax_0" type='Radio' Name='tax' value='0'><?= $TEXT['guest_modal']['tax_free'] ?>
                </td>
            </tr>
            <tr>
                <td><label for="id_number" ncorp="<?= $TEXT['guest_modal']['guest_id'] ?>"
                           corp="<?= $TEXT['guest_modal']['company_id'] ?>"><?= $TEXT['guest_modal']['guest_id'] ?></label>
                </td>
                <td><input type="text" name="id_number" id="id_number" value=""></td>
            </tr>
            <tr>
                <td>
                    <label for="first_name" ncorp="<?= $TEXT['guest_modal']['guest_first_name'] ?>"
                           corp="<?= $TEXT['guest_modal']['company_name'] ?>">
                        <?= $TEXT['guest_modal']['guest_first_name'] ?>
                    </label>
                </td>
                <td><input type="text" name="first_name" id="first_name" value=""></td>
            </tr>
            <tr id="guest_lname_tr">
                <td><label for="last_name"><?= $TEXT['guest_modal']['guest_last_name'] ?></label></td>
                <td><input type="text" name="last_name" id="last_name" value=""></td>
            </tr>
            <tr  id="birth_day_tr">
                <td><label for="birth_day"><?= $TEXT['guest_modal']['birth_day'] ?></label></td>
                <td><input type="text" name="birth_day" id="birth_day" value="" placeholder="1987-12-31"></td>
            </tr>
            <tr id="guest_ind_discount_tr">
                <td><label for="guest_ind_discount"><?= $TEXT['guest_modal']['ind_discount'] ?></label></td>
                <td><input type="number" name="guest_ind_discount" id="guest_ind_discount" value="0" min="0" max="100"
                           step="1"></td>
            </tr>
            <tr>
                <td><a href="#" id="id_scan_link"><?= $TEXT['guest_modal']['id_scan'] ?></a></td>
                <td align="right"><input class="" type="file" style="width:100%" name="id_scan"></td>
            </tr>
            <tr>
                <td><label for="country"><?= $TEXT['guest_modal']['country'] ?></label></td>
                <td>
                    <select name="country" id="country" style="width:160px">
                        <? foreach ($TMPL_countries as $country) {
                            echo '<option value="' . $country['id'] . '" >' . $country[LANG] . '</option>';
                        } ?>
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


<script>
    $(document).ready(function () {
        $("#guest_search_field").autocomplete({
            source: "index_ajax.php?cmd=get_guest_suggestions",
            minLength: 0,
            select: function (event, ui) {
                $('#guest_id').val(ui.item['id']);
                fillGuestModal(ui.item['id']);
            }
        }).autocomplete("option", "appendTo", "#booking_guest_modal");

        $("#guest_search_field").click(function () {
            $(this).autocomplete("search", $(this).val());
        });
        var guest_modal_dest = "";

        $(".guest_modal_trigger").click(function () {
            var triger=$(this);

            guest_modal_dest = $(triger).attr('dest');
            if (guest_modal_dest == 'guest') {
                $("#guest_search_field").autocomplete('option', 'source', "index_ajax.php?cmd=get_guest_suggestions");
               fillGuestModal($(this).data('id'));
            } else if (guest_modal_dest == 'responsive') {
                $("#guest_search_field").autocomplete('option', 'source', "index_ajax.php?cmd=get_guest_suggestions");
                if (!$.isEmptyObject(responsive_guest_obj)) {
                    fillGuestModal(responsive_guest_obj['id']);
                } else {
                    fillGuestModal(0);
                }
            } else if (guest_modal_dest == 'affiliate') {
                $("#guest_search_field").autocomplete('option', 'source', "index_ajax.php?cmd=get_affiliate_suggestions");
                console.log('autofill affiliate');
                console.log('autofill affiliate_obj');
                if (!$.isEmptyObject(affiliate_obj)) {
                    fillGuestModal(affiliate_obj['id']);
                } else {
                    fillGuestModal(0);
                }
            }
            else{

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
                        var cmd = "";
                        if ($('#guest_id').val() == 0) {
                            cmd = 'add_guest';
                        } else {
                            if (serialized_form_obj === $('#guest_modal_form').serialize()) {
                                cmd = 'select_guest';
                            } else {
                                cmd = 'edit_guest';
                            }
                        }
                        console.log(cmd);

                        if (cmd == 'add_guest' || cmd == 'edit_guest') {


                                var request = $.ajax({
                                    url: "index_ajax.php?cmd=" + cmd,
                                    method: "POST",
                                    data: $('#guest_modal_form').serialize(),
                                    dataType: "json"
                                });

                                request.done(function (msg) {
                                    console.log(msg);
                                    if (msg['guest'] === undefined) {
                                        var message = "<div style='border:solid red 2px'>"
                                        for (var i = 0; i < msg['errors'].length; i++) {
                                            message += "<b>" + msg['errors'][i] + "</b><br>";
                                        }
                                        message += "</div>";
                                        $('#guest_modal_message').html(msg['errors']);
                                    } else {
                                        if(triger.data('action')=='editGuest'){
                                            $("#change_booking_guest input[name=l_guest_id]").val(msg['guest']['id']);
                                            $("#change_booking_guest").submit();
                                        }else if(triger.data('action')=='editForiegnGuest') {
                                            $("#add_foreign_guest_form input[name=f_guest_id]").val(msg['guest']['id']);
                                            $("#add_foreign_guest_form").submit();
                                        }
                                        else if(triger.data('action')=='changeResponsiveGuest'){
                                            $("#change_resonsive_guest input[name=l_guest_id]").val(msg['guest']['id']);
                                            $("#change_resonsive_guest").submit();
                                        }
                                    }
                                });

                                request.fail(function (jqXHR, textStatus) {
                                    $('#guest_modal_message').text("ver daemata!");
                                });
                        } else {
                            if(triger.data('action')=='editGuest'){
                                $("#change_booking_guest input[name=l_guest_id]").val(last_fetched_guest_obj.id);
                                $("#change_booking_guest").submit();
                                $(this).dialog("close");
                            }else if(triger.data('action')=='editForiegnGuest') {
                                $("#add_foreign_guest_form input[name=f_guest_id]").val(last_fetched_guest_obj.id);
                                $("#add_foreign_guest_form").submit();
                                $(this).dialog("close");
                            }
                            else if(triger.data('action')=='changeResponsiveGuest'){
                                $("#change_resonsive_guest input[name=l_guest_id]").val(last_fetched_guest_obj.id);
                                $("#change_resonsive_guest").submit();
                                $(this).dialog("close");
                            }

                        }
                    },
                    "<?= $TEXT['guest_modal']['but_cancel'] ?>": function () {
                        if (guest_modal_dest == 'guest') {
                            $('#guest_selector').text($('#guest_selector').attr('def'));
                            guest_obj = {};
                        } else if (guest_modal_dest == 'responsive') {
                            $('#responsive_guest_selector').text($('#responsive_guest_selector').attr('def'));
                            responsive_guest_obj = {};
                        } else if (guest_modal_dest == 'affiliate') {
                            $('#affiliate_selector').text($('#affiliate_selector').attr('def'));
                            affiliate_obj = {};
                        }
                        $(this).dialog("close");
                    }
                }
            });
            var $target = $('#booking_guest_modal').dialog().parent();
            $target.css('top', (window.innerHeight - $target.height()) / 2);
            $target.css('left', (window.innerWidth - $target.width()) / 2);
            $target.css('position', 'fixed');

        });

        var serialized_form_obj = {};
        var guest_obj = {};
        var affiliate_obj = {};
        var responsive_guest_obj = {};

        function fillGuestModal(g_id) {
            console.log('fillGuestModal(' + g_id + ');');
            if (g_id == 0) {
                $("#booking_guest_modal :input").attr("disabled", false);
                $("#id_number").val('');
                $("#first_name").val('');
                $("#last_name").val('');
                $("#birth_day").val('');
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
                $('#birth_day_tr').show();
                $('#guest_ind_discount').val(0);
                $('#guest_ind_discount_tr').val(0);
                return;
            } else {

            }
            var request = $.ajax({
                url: "index_ajax.php?cmd=get_guest_info",
                method: "POST",
                data: {guest_id: g_id},
                dataType: "json"
            });

            request.done(function (msg) {
                $("#id_number").val(msg.id_number);
                $("#first_name").val(msg.first_name);
                $("#last_name").val(msg.last_name);
                $("#birth_day").val(msg.birth_day);
                $('#guest_id').val(msg.id);
                $('#guest_search_field').val(msg.first_name+' '+msg.last_name);
                $("#guest_ind_discount").val(msg.ind_discount);
                $("#id_scan_link").attr('href', '../uploads_script/guests/' + msg.id_scan);
                $("#id_scan_link").attr('target', '_blank');
                $("#country").val(msg.country);
                $("#address").val(msg.address);
                $("#telephone").val(msg.telephone);
                $("#email").val(msg.email);
                $("#comment").val(msg.comment);
                //$("#comment").attr('readonly', 'readonly');
                if (msg.type == 'company') {
                    $('input:radio[name=guest_type]')[1].checked = true;
                    //$('#guest_ind_discount_tr').hide();
                    $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
                    $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
                    $('#guest_lname_tr').hide();
                    $('#birth_day_tr').hide();
                } else if (msg.type == 'tour-company') {
                    $('input:radio[name=guest_type]')[2].checked = true;
                    $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
                    $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
                    //$('#guest_ind_discount_tr').show();
                    $('#guest_lname_tr').hide();
                      $('#birth_day_tr').hide();
                } else {
                    $('input:radio[name=guest_type]')[0].checked = true;
                    //$('#guest_ind_discount_tr').hide();
                    $('label[for="id_number"]').text($('label[for="id_number"]').attr('ncorp'));
                    $('label[for="first_name"]').text($('label[for="first_name"]').attr('ncorp'));
                    $('#guest_lname_tr').show();
                      $('#birth_day_tr').show();
                }
                if (msg.tax == 1) {
                    $("#tax_1").prop('checked', true);
                    $("#tax_0").prop('checked', false);
                } else {
                    $("#tax_1").prop('checked', false);
                    $("#tax_0").prop('checked', true);
                }

                //$("#booking_guest_modal :input").attr("disabled", true);
                $("#guest_id").attr("disabled", false);
                serialized_form_obj = $('#guest_modal_form').serialize();
                last_fetched_guest_obj = msg;
            });

            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        };
        $( "#birth_day" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            dateFormat:'yy-mm-dd',
            yearRange: '1900:2030',
        });
        $("input[type='radio'][name='guest_type']").change(function(event) {
            if($(this).val()=='non-corporate'){
                $('label[for="id_number"]').text($('label[for="id_number"]').attr('ncorp'));
                $('label[for="first_name"]').text($('label[for="first_name"]').attr('ncorp'));
                $('#birth_day_tr').show();
                $('#guest_lname_tr').show();
                $('#guest_ind_discount').val(0);
                //$('#guest_ind_discount_tr').hide();
            }else if($(this).val()=='company'){
                $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
                $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
                $('#birth_day_tr').hide();
                $('#last_name').val('');
                $('#guest_lname_tr').hide();
                $('#guest_ind_discount').val(0);
                //$('#guest_ind_discount_tr').hide();
            }else if($(this).val()=='tour-company'){
                $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
                $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
                $('#birth_day_tr').hide();
                $('#last_name').val('');
                $('#guest_lname_tr').hide();
                //$('#guest_ind_discount_tr').show();
                $('#guest_ind_discount').val(0);
            }
            $("input[type='hidden'][name='guest_type']").val($(this).val());
        });
    });
</script>
