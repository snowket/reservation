
<div id="add_edit_service_modal" style="display: none">
    <form action="" id="add_edit_form" method="post" enctype="multipart/form-data">
        <table style="margin: 20px; margin-left:<? echo intval($SETTINGS['th_width'])+20 ?>" border="0" cellspacing="0" cellpadding="2" class="text1">
            <tr>
                <td colspan="2" align="center">
                    <table border="0" cellspacing="0" cellpadding="2" class="text1"><tr><td class="err">
                    <?=$TMPL_errors?>
                    </td></tr></table>
                </td>
            </tr>
            <!-- ERRORS //-->
            <? if(count($TMPL_services_types)>0){?>
            <tr>
                <td><?=$TEXT['extra_services_type']?></td>
                <td nowrap>
                    <select name="service_type" id="service_type">
                        <option value="0">აირჩიეთ სერვისის ტიპი</option>
                        <? foreach($TMPL_services_types AS $services_type){ ?>
                            <option value="<?=$services_type['id']?>" <?=($services_type['id']==$TMPL_service['type_id'])?'selected':''?>><?=$services_type['title'] ?></option>
                        <?}?>
                    </select>
                </td>
            </tr>
            <?}?>
            <? for($i=0;$i<count($TMPL_lang);$i++){ ?>
            <tr>
                <td><b><?=$TEXT['extra_services']?></b> (<?=$TMPL_lang[$i]?>)</td>
                <td nowrap><input type="text" name="title[<?=$TMPL_lang[$i]?>]" value="<?=$TMPL_service['title'][$TMPL_lang[$i]]?>" class="formField1"></td>
            </tr>
            <?}?>
            <tr>
                <td><b><?=$TEXT['price']?> <?=$TEXT['cur']?>:</b></td>
                <td nowrap><input type="number" name="price" value="0" class="formField1" ></td>
            </tr>
        </table>
        <input type="hidden" name="action" id="action" value="<?=$_GET['action']?$_GET['action']:'add'?>">
        <input type="hidden" name="service_id" id="service_id" value="0">
    </form>
</div>

<div style="background:#FFF; border:solid #3A82CC 1px; width: 420px;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['services']['filter_modal']['title'] ?></b>
    </div>
    <form action="" method="get" id="filter_form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <? if(count($TMPL_services_types)>0){?>
            <tr>
                <td><?=$TEXT['extra_services_type']?></td>
                <td nowrap>
                    <select name="type_id" onchange="this.form.submit()">
                        <option value="0">ყველა</option>
                        <? foreach($TMPL_services_types AS $services_type){ ?>
                            <option value="<?=$services_type['id']?>" <?=($services_type['id']==$_GET['type_id'])?"selected":""?>><?=$services_type['title'] ?></option>
                        <?}?>
                    </select>
                </td>
            </tr>
            <?}?>
        </table>
    </form>
</div>

<div id="add_service_modal_trigger" action="add" class="add_edit_modal_trigger formButton2"  style="margin-top:10px; padding:2px;   cursor: pointer; text-align: centerl; width: 160px; float:right">
    <b>+ სერვისის დამატება</b>
</div>
<div style="clear:both"></div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".add_edit_modal_trigger").click(function () {
            var act_but_title="დამატება";
            if($(this).attr('action')=="add"){
                $("#action").val('add');
                $("input[name='price']").val(0);
                $("input[name='service_id']").val(0);
                $("#service_type").val(0);
                $("#add_edit_form input[type=text]").each(function() {
                    $(this).val("");
                });
                act_but_title="დამატება";
            }else{
                act_but_title="რედაქტირება";
                var request = $.ajax({
                    url: "index_ajax.php?cmd=get_service_info",
                    method: "POST",
                    data: {service_id: $(this).attr('service_id')},
                    dataType: "json"
                });

                request.done(function (msg) {
                    $("input[name='price']").val(parseFloat(msg.price));
                    $("input[name='service_id']").val(msg.id);
                    $("#service_type").val(msg.type_id);
                    $.each(msg.title, function(k, v) {
                        $("input[name='title["+k+"]']").val(v);
                        console.log(k,v);
                    });

                });

                request.fail(function (jqXHR, textStatus) {
                    alert("Request failed: " + textStatus);
                });
                $("#action").val('edit');
            }

            var btns={};
            btns[act_but_title]= function () {

                    if ($("#service_type").val() == 0) {
                            alert("Select Service Type");
                            return;
                    }else{
                        $(this).dialog("close");
                        $("#add_edit_form").submit();
                    }
                };
            btns["გაუქმება"]= function () {
                $(this).dialog("close");
            };

            $("#add_edit_service_modal").dialog({
                resizable: false,
                width: 400,
                modal: true,
                buttons: btns
            });
        });
    });
</script>

