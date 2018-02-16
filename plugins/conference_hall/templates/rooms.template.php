<script src="./js/context_menu/jquery.ui.position.js" type="text/javascript"></script>
<script src="./js/context_menu/jquery.contextMenu.js" type="text/javascript"></script>
<link href="./js/context_menu/sass/jquery.contextMenu.css" rel="stylesheet" type="text/css" />

<script src="./js/notify.min.js" type="text/javascript"></script>
<script src="./js/notify-metro.js" type="text/javascript"></script>
<link href="./css/notify-metro.css" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.1/jquery.cookie.js" type="text/javascript"></script>
<script src="./js/sumoselect/jquery.sumoselect.min.js" type="text/javascript"></script>
<link href="./js/sumoselect/sumoselect.css" rel="stylesheet" type="text/css" />

<div id="add_edit_service_modal" style="display: none">
    <form action="index.php?m=conference_hall&tab=rooms" id="add_edit_form" method="post" enctype="multipart/form-data">
        <table style="margin: 20px; margin-left:<? echo intval($SETTINGS['th_width'])+20 ?>" border="0" cellspacing="0" cellpadding="2" class="text1">
            <tr>
                <td colspan="2" align="center">
                    <table border="0" cellspacing="0" cellpadding="2" class="text1">
                        <tr>
                            <td class="err">
                                <?=$TMPL_errors?>
                            </td>
                        </tr>
                        <? for($i=0;$i<count($_CONF['langs_publish']);$i++){ ?>
                            <tr>
                                <td> დასახელება (<?=$_CONF['langs_publish'][$i]?>)</td>
                                <td nowrap><input type="text" name="name[<?=$_CONF['langs_publish'][$i]?>]" value="" class="formField1"></td>
                            </tr>
                        <?}?>


                            <tr>
                                <td> დალაგების შესაძლო ტიპები </td>
                                <td nowrap>
                                    <select name="room_types[]" class="formField1 selector" multiple="multiple" style="width: 100%;">
                                        <? foreach($room_types as $types){?>
                                           <option value="<?=$types['id']?>"><?=$types['name']?>(<?=$types['capacity']?>)</option>
                                         <? } ?>
                                    </select>
                                </td>
                            </tr>

                    </table>
                </td>
            </tr>

        </table>
        <input type="hidden" name="action" id="action" value="add">
        <input type="hidden" name="room_id" id="room_id" value="0">
    </form>
</div>
<div id="add_service_modal_trigger" action="add" class="add_edit_modal_trigger formButton2"  style="margin-top:10px; padding:2px;   cursor: pointer; text-align: centerl; width: 160px; float:right">
    <b>+ დარბაზის დამატება</b>
</div>
<div style="clear:both"></div>


<div style="float:left;width:100%;">

    <div style="margin-left: 4px; float:left;width:800px;padding-top: 20px;">

        <table class="table-table">
            <tbody>
            <tr>
                <td class="table-th">ID</td>
                <td class="table-th">სახელი</td>
                <td class="table-th">დალაგების ტიპები</td>
                <td class="table-th">შეცვლა</td>
                <td class="table-th">წაშლა</td>

            </tr>
            <? foreach($room as $key=>$value){?>
                <tr>
                    <td class="table-td"><?=$value['id']?></td>
                    <td class="table-td"><?=$value['name']?></td>
                    <td class="table-td">
                      <? foreach($value['types'] as $type){ ?>
                            <p><?=$type['name']." (".$type['capacity'].")"?></p>
                         <?  } ?>

                    </td>
                    <td class="table-td">
                        <a class="add_edit_modal_trigger" href="#" room_id="<?=$value['id']?>">
                            <img src="./images/icos16/edit.gif" width="16" height="16" alt="edit" border="0">
                        </a>
                    </td>
                    <td class="table-td">Delete</td>

                </tr>

            <? } ?>
            </tbody>
        </table>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $(".add_edit_modal_trigger").click(function () {
            var act_but_title="დამატება";
            if($(this).attr('action')=="add"){
                $("#action").val('add');
                $("input[name='room_id']").val(0);
                $("#service_type").val(0);
                $("#add_edit_form input[type=text]").each(function() {
                    $(this).val("");
                });
                act_but_title="დამატება";
            }else{
                act_but_title="რედაქტირება";
                var request = $.ajax({
                    url: "index_ajax.php?cmd=get_ch_room_info",
                    method: "POST",
                    data: {service_id: $(this).attr('room_id')},
                    dataType: "json"
                });

                request.done(function (msg) {
                    $("input[name='room_id']").val(msg.id);
                    $.each(msg.name, function(k, v) {
                        $("input[name='name["+k+"]']").val(v);
                        console.log(k,v);
                    });
                    $.each(msg.type_id, function(k, v) {
                        $('select.selector option[value="'+v+'"]').attr("selected", "selected");
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