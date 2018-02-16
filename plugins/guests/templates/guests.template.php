
<div style="clear: both"></div>

<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['guests']['filter_modal']['title'] ?></b>
    </div>
    <form action="" method="get" id="filter_form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td align="right">
                    <label for="filter_guest_id_number"><?= $TEXT['guests']['filter_modal']['guest_id'] ?></label>
                </td>
                <td>
                    <input type="text" id="filter_guest_id_number" name="filter_guest_id_number" value="<?=$_GET['guest_id']?>"/>
                </td>
                <td align="right">
                    <label for="start_date"><?= $TEXT['guests']['filter_modal']['date'] ?></label>
                </td>
                <td >
                    <input type="text" class="calendar-icon" id="start_date" name="start_date" value="" placeholder="<?=$TEXT['guests']['filter_modal']['from']?>"/>
                    <input type="text" class="calendar-icon" id="end_date" name="end_date" value="" placeholder="<?=$TEXT['guests']['filter_modal']['to']?>"/>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="guest_name"><?= $TEXT['guests']['filter_modal']['guest_name'] ?></label>
                </td>
                <td>
                    <input type="text" id="guest_name" name="guest_name" value="<?=$_GET['guest_name']?>"/>
                </td>
                <td align="right">
                    <label for="publish"><?= $TEXT['guests']['filter_modal']['publish'] ?></label>
                </td>
                <td>
                    <select id="publish" name="publish">
                        <option value="" <? if(!isset($_GET['guest_status'])||$_GET['guest_status']==''){echo "selected";}?>><?=$TEXT['guest_status']['all']?></option>
                        <option value="0" <? if(isset($_GET['guest_status'])&&$_GET['guest_status']==0){echo "selected";}?>><?=$TEXT['guest_status']['0']?></option>
                        <option value="1" <? if(isset($_GET['guest_status'])&&$_GET['guest_status']==1){echo "selected";}?>><?=$TEXT['guest_status']['1']?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="guest_type"><?= $TEXT['guests']['filter_modal']['guest_type'] ?></label>
                </td>
                <td>
                    <select id="guest_type" name="guest_type">
                        <option value="" <? if(!isset($_GET['guest_type'])){echo "selected";}?>><?=$TEXT['guest_type']['all']?></option>
                        <option value="non-corporate" <? if(isset($_GET['guest_type'])&&$_GET['guest_type']=='non-corporate'){echo "selected";}?>><?=$TEXT['guest_type']['non-corporate']?></option>
                        <option value="company" <? if(isset($_GET['guest_type'])&&$_GET['guest_type']=='company'){echo "selected";}?>><?=$TEXT['guest_type']['company']?></option>
                        <option value="tour-company" <? if(isset($_GET['guest_type'])&&$_GET['guest_type']=='tour-company'){echo "selected";}?>><?=$TEXT['guest_type']['tour-company']?></option>
                    </select>
                </td>
                <td align="right">
                    <label for="tax"><?= $TEXT['guests']['filter_modal']['tax'] ?></label>
                </td>
                <td>
                    <select id="tax" name="tax">
                        <option value="2" <? if(isset($_GET['tax'])&&(int)$_GET['tax']==2){echo "selected";}?>><?=$TEXT['guests']['filter_modal']['all']?></option>
                        <option value="1" <? if(isset($_GET['tax'])&&(int)$_GET['tax']==1){echo "selected";}?>>TAX INCLUDED</option>
                        <option value="0" <? if(isset($_GET['tax'])&&(int)$_GET['tax']==0){echo "selected";}?>>TAX FREE</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right" colspan="4">
                    <br>
                    <input class="formButton2" type="submit" value="<?= $TEXT['guests']['filter_modal']['submit_filter'] ?>" style="cursor: pointer">
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="add_guest_modal_trigger" class="add_edit_modal_trigger formButton2" action="add" style="margin-top:10px; margin-bottom:10px; padding:2px; cursor: pointer; text-align: center; width: 160px; float:right" action="add">
    <b><?=$TEXT['add_guest']?></b>
</div>
<?if(!empty($TMPL_errors)){?>
<div style="margin-top:40px;margin-bottom:10px; border:solid red 2px; color:red; padding: 4px">
    <?foreach($TMPL_errors as $error){?>
        <div><?=$error?></div>
    <?}?>
</div>
<?}?>
<table id="guests_table"  border="0" style="width:100%;" cellpadding="0" cellspacing="0" class="table-table">
	<tr>
		<td class="table-th">
		<b>&#8470;&nbsp;</b>
		</td>
		<td class="table-th">
			<?=$TEXT['users']['members']?>
		</td>
        <td class="table-th">
            <?=$TEXT['guest_modal']['id_scan']?>
        </td>
        <td class="table-th">
            <?=$TEXT['guest_modal']['extra_doc']?>
        </td>
		<td class="table-th">
		    <?=$TEXT['users']['email']?>
		</td>
		<td class="table-th">
			<?=$TEXT['users']['birth_day']?>
		</td>
		<td class="table-th">
			<?=$TEXT['users']['joined']?>
		</td>
		<td  class="table-th">
			<?=$TEXT['users']['publish']?>
		</td>
		<td class="table-th">&nbsp;<?=$TEXT['users']['action']?></td>
	</tr>
	<? for($i=0;$i<count($TMPL_users);$i++){ ?>
	<tr>
		<td class="tdrow2"><? $p = intval($_GET['p'])?intval($_GET['p']):1;  echo ($p-1)*50+$i+1; ?></td>
		<td class="tdrow2">
				<a href="<?=$_SERVER['PHP_SELF']?>?m=<?=$TMPL_plugin?>&action=edit_user&uid=<?=$TMPL_users[$i]['id']?>" class="basic"><b><?=$TMPL_users[$i]['first_name']?> <?=$TMPL_users[$i]['last_name']?></b></a>
		</td>
        <td class="tdrow2">
            <?if($TMPL_users[$i]['id_scan']!=''){?>
                <a href="../uploads_script/guests/<?=$TMPL_users[$i]['id_scan']?>" target="_blank">
                    <img src="../uploads_script/guests/<?=$TMPL_users[$i]['id_scan']?>" height="30" />
                </a>
            <?}else{?>

            <?}?>
        </td>
        <td class="tdrow2">
            <?if($TMPL_users[$i]['extra_doc']!=''){?>
                <a href="../uploads_script/guests/<?=$TMPL_users[$i]['extra_doc']?>" target="_blank">
                    <img src="../uploads_script/guests/<?=$TMPL_users[$i]['extra_doc']?>" height="30" />
                </a>
            <?}else{?>

            <?}?>
        </td>
		<td class="tdrow2">
			<a href="mailto:<?=$TMPL_users[$i]['email']?>" class="text1" style="color:black; font-family:Arial; font-size:12px;"><? echo $TMPL_users[$i]['email']?></a>
		</td>
		<td class="tdrow2"><?=date('Y-m-d',strtotime($TMPL_users[$i]['birth_day']))?></td>
		<td class="tdrow2"><?=date('Y-m-d',strtotime($TMPL_users[$i]['created_at']))?></td>
		<td class="tdrow2"><?=$TMPL_users[$i]['publish']?></td>
		<td class="tdrow2">
			<? if($TMPL_users[$i]['id']>0){?>
				<a href="<?=$_SERVER['PHP_SELF']?>?m=<?=$TMPL_plugin?>&action=change_status&uid=<?=$TMPL_users[$i]['id']?>">
				<img src="./images/icos16/protect-<?=$TMPL_users[$i]['publish']==1?'green':'red'?>.gif" width="16" height="16" border="0" align="middle" alt="Block/Unblock"></a>
					&nbsp;&nbsp;
				<a href="#" class="add_edit_modal_trigger" guest_id="<?=$TMPL_users[$i]['id']?>">
				<img src="./images/icos16/edit.gif" width="16" height="16" border="0" align="middle" alt="edit"></a>
			<? }else{ ?>
				<img src="./images/icos16/blocked.gif" width="16" height="16" border="0">
			<? } ?>
		</td>
	</tr>
<?}?>
</table>

<br>
<div align="center" style=""><?=$TMPL_navbar?></div>
<br>

<div id="guest_modal"  style="display: none; width:600px" title="<?= $TEXT['guest_modal']['title'] ?>">
    <form id="guest_form" method="POST" enctype="multipart/form-data">
        <input type="hidden" id="action" name="action" value="add">
        <input type="hidden" id="guest_id" name="guest_id" value="0">
    <table>
        <tr >
            <td  style="min-width: 130px">
                <?= $TEXT['guest_modal']['guest_type'] ?>
            </td>
            <td  style="border:solid gray 1px;">
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
            <td  style="border:solid gray 1px;">
                <Input id="tax_1" type = 'Radio' Name ='tax' value= '1' checked><?= $TEXT['guest_modal']['tax_included'] ?>
                <Input id="tax_0" type = 'Radio' Name ='tax' value= '0'><?= $TEXT['guest_modal']['tax_free'] ?>
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
            <td><input type="text" name="first_name" id="first_name" value=""></td>
        </tr>
        <tr  id="guest_lname_tr">
            <td><label for="last_name"><?= $TEXT['guest_modal']['guest_last_name'] ?></label></td>
            <td><input type="text" name="last_name" id="last_name" value=""></td>
        </tr>
        <tr  id="birth_day_tr">
            <td><label for="birth_day"><?= $TEXT['guest_modal']['birth_day'] ?></label></td>
            <td><input type="text" name="birth_day" id="birth_day" value="" placeholder="1987-12-31"></td>
        </tr>
        <tr  id="guest_ind_discount_tr">
            <td><label for="guest_ind_discount"><?= $TEXT['guest_modal']['ind_discount'] ?></label></td>
            <td><input type="number" name="guest_ind_discount" id="guest_ind_discount" value="0"  min="0" max="100" step="1"></td>
        </tr>
        <tr>
            <td><a href="#" id="id_scan_link"><?= $TEXT['guest_modal']['id_scan'] ?></a></td>
            <td align="right">
                <input class="" type="file" style="width:100%" name="id_scan">
                <input type="hidden" name="id_scan_old" id="id_scan_old" value="">
            </td>
        </tr>
        <tr>
            <td><a href="#" id="extra_doc_link"><?= $TEXT['guest_modal']['extra_doc'] ?></a></td>
            <td align="right">
                <input class="" type="file" style="width:100%" name="extra_doc">
                <input type="hidden" name="extra_doc_old" id="extra_doc_old" value="">
            </td>
        </tr>
        <tr>
            <td><label for="country"><?= $TEXT['guest_modal']['country'] ?></label></td>
            <td>
                <select name="country" id="country" >
                    <!--option value="0"><?= $TEXT['select_country'] ?></option-->
                    <? foreach ($TMPL_countries as $country) {
                        echo '<option value="' . $country['id'] . '" >' . $country['geo'] . '</option>';
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
                <textarea name="comment" id="comment" style="width:100%" class="formField3"><?= $TMPL_data['comment'] ?></textarea>
            </td>
        </tr>
    </table>
    </form>
</div>


<form method="post" target="_blank">
    <input type="hidden" name="action" value="get_excel">
    <input class="download-excel" type="submit" style="float:right; border:solid 0px;" value="<?=$TEXT['users']['download_excel']?>">
</form>


<script type="text/javascript">
$(document).ready(function () {
    console.log("document ready");



    $(".add_edit_modal_trigger").click(function () {
        var act_but_title="<?=$TEXT['guest_modal']['but_add']?>";
        if($(this).attr('action')=="add"){
            $("#action").val('add');
            $("#guest_id").val(0);
            $("#id_number").val('');
            $("#first_name").val('');
            $("#last_name").val('');
            $("#birth_day").val('');
            $("#guest_ind_discount").val(0);
            $("#id_scan_link").attr('href', '#');
            $("#id_scan_link").attr('target', '');
            $("#extra_doc_link").attr('href', '#');
            $("#extra_doc_link").attr('target', '');
            $("#country").val(273);
            $("#address").val('');
            $("#telephone").val('');
            $("#email").val('');
            $("#comment").val('');
            $('input:radio[name=guest_type]')[0].checked = true;
            //$('#guest_ind_discount_tr').hide();
            $('label[for="id_number"]').text($('label[for="id_number"]').attr('ncorp'));
            $('label[for="first_name"]').text($('label[for="first_name"]').attr('ncorp'));
            $('#guest_lname_tr').show();
            $("#tax_1").prop('checked', true);
            $("#tax_0").prop('checked', false);
            act_but_title="<?=$TEXT['guest_modal']['but_add']?>";
        }else {
            $("#action").val('edit');
            act_but_title = "<?=$TEXT['guest_modal']['but_edit']?>";
        }

        if($(this).attr('action')=="add"){

        }else{
            act_but_title="<?=$TEXT['guest_modal']['but_edit']?>";
            var request = $.ajax({
                url: "index_ajax.php?cmd=get_guest_info",
                method: "POST",
                data: {guest_id: $(this).attr('guest_id')},
                dataType: "json"
            });

            request.done(function (msg) {
                $("#guest_id").val(msg.id);
                $("#id_number").val(msg.id_number);
                $("#first_name").val(msg.first_name);
                $("#last_name").val(msg.last_name);
                $("#birth_day").val(msg.birth_day);
                $("#guest_ind_discount").val(msg.ind_discount);
                if(msg.id_scan!="") {
                    $("#id_scan_link").attr('href', '../uploads_script/guests/' + msg.id_scan);
                    $("#id_scan_link").attr('target', '_blank');
                }
                if(msg.extra_doc!="") {
                    $("#extra_doc_link").attr('href', '../uploads_script/guests/' + msg.extra_doc);
                    $("#extra_doc_link").attr('target', '_blank');
                }
                $("#country").val(msg.country);
                $("#address").val(msg.address);
                $("#telephone").val(msg.telephone);
                $("#email").val(msg.email);
                $("#comment").val(msg.comment);
                $("#extra_doc_old").val(msg.extra_doc);
                $("#id_scan_old").val(msg.id_scan);
                if(msg.type=='company'){
                    $('input:radio[name=guest_type]')[1].checked = true;
                    //$('#guest_ind_discount_tr').hide();
                    $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
                    $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
                    $('#guest_lname_tr').hide();
                    $('#birth_day_tr').hide();
                }else if(msg.type=='tour-company'){
                    $('input:radio[name=guest_type]')[2].checked = true;
                    $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
                    $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
                    $("#guest_ind_discount").val(msg.ind_discount);
                    //$('#guest_ind_discount_tr').show();
                    $('#guest_lname_tr').hide();
                    $('#birth_day_tr').hide();
                }else{
                    $('input:radio[name=guest_type]')[0].checked = true;
                    //$('#guest_ind_discount_tr').hide();
                    $('label[for="id_number"]').text($('label[for="id_number"]').attr('ncorp'));
                    $('label[for="first_name"]').text($('label[for="first_name"]').attr('ncorp'));
                    $('#guest_lname_tr').show();
                    $('#birth_day_tr').show();
                }
                if(msg.tax==1){
                    $("#tax_1").prop('checked', true);
                    $("#tax_0").prop('checked', false);
                }else{
                    $("#tax_1").prop('checked', false);
                    $("#tax_0").prop('checked', true);
                }

            });

            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
            $("#action").val('edit');
        }

        var btns={};
        btns[act_but_title]= function () {
            $('#guest_form').submit();
        };
        btns['<?=$TEXT['guest_modal']['but_cancel']?>']= function () {
            $(this).dialog("close");
        };

        $("#guest_modal").dialog({
            resizable: false,
            width: 540,
            modal: true,
            buttons: btns
        });
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
    $("#guest_form").submit(function (event) {
        if (!validateSubmit()) {
            event.preventDefault();
        }
    });

    function validateSubmit() {
        if ($("#guest_id").val() == 0) {
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
        }
        return true;
    }
    $("#birth_day").datepicker({
        //defaultDate: firstDayOfCurrentMonth,
        maxDate: new Date,
        changeMonth: true,
        changeYear: true,
        yearRange:'1900 : 2030',
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
    });
    $("#start_date").datepicker({
        //defaultDate: firstDayOfCurrentMonth,
        maxDate: new Date,
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        buttonImageOnly: true,
        buttonText: "Select date",
        onSelect: function (selectedDate) {
            var date = $(this).datepicker('getDate');
            //date.setTime(date.getTime() + (1000 * 60 * 60 * 24));
            $("#end_date").datepicker("option", "minDate", date);
        }
    });
    $("#end_date").datepicker({
        dateFormat: 'yy-mm-dd',
        defaultDate: new Date(),
        maxDate: new Date,
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2,
        buttonImageOnly: true,
        buttonText: "Select date",
        onSelect: function (selectedDate) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() - (1000 * 60 * 60 * 24));
            $("#start_date").datepicker("option", "maxDate", date);
        }
    });
    var curr_date = new Date(), y = curr_date.getFullYear(), m = curr_date.getMonth();
    var firstDayOfCurrentMonth = new Date(y, m, 1);
    var lastDayOfCurrentMonth = new Date(y, m + 1, 0);
    //$("#start_date").datepicker("setDate", <?=(isset($_GET['start_date'])&&$_GET['start_date']!="")?"'".$_GET['start_date']."'":"firstDayOfCurrentMonth" ?>);
    $("#end_date").datepicker("setDate",<?=(isset($_GET['end_date'])&&$_GET['end_date']!="")?"'".$_GET['end_date']."'":"new Date()" ?>);
});
</script>
