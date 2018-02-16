<script src="./js/context_menu/jquery.ui.position.js" type="text/javascript"></script>
<script src="./js/context_menu/jquery.contextMenu.js" type="text/javascript"></script>
<link href="./js/context_menu/sass/jquery.contextMenu.css" rel="stylesheet" type="text/css" />

<script src="./js/notify.min.js" type="text/javascript"></script>
<script src="./js/notify-metro.js" type="text/javascript"></script>
<link href="./css/notify-metro.css" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.1/jquery.cookie.js" type="text/javascript"></script>
<script src="./js/sumoselect/jquery.sumoselect.min.js" type="text/javascript"></script>
<link href="./js/sumoselect/sumoselect.css" rel="stylesheet" type="text/css" />
<div id="add_edit_service_type_modal" style="display: none">
    <form action="index.php?m=conference_hall&tab=service_types" id="add_edit_form" method="post" enctype="multipart/form-data">
        <table style="margin: 20px; margin-left:<? echo intval($SETTINGS['th_width'])+20 ?>" border="0" cellspacing="0" cellpadding="2" class="text1">




            <tr>
                <td><b>დასახელება</b></td>
                <td nowrap><input type="text" name="service_type" value="0" class="formField1" ></td>
            </tr>
            <tr>
                <td><b>ფასის გენერირება</b></td>
             <td>
                 <select name="type_of" id="">
                     <option value="0">ფასის გენერირება თითოეულ კაცზე</option>
                     <option value="1">ფასის გენერირება დარბაზზე</option>
                 </select>
             </td>
            </tr>
        </table>
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="service_id" value="0">
        <input type="hidden" name="type" value="service_type">
    </form>
</div>

<div id="add_service_type_modal_trigger" action="add" class="add_edit_modal_trigger formButton2"  style="margin-top:10px; padding:2px;   cursor: pointer; text-align: centerl; width: 160px; float:right">
    <b>+ სერვისის დამატება</b>
</div>
<div style="float:left;width:100%;">
    <div style="margin-left: 4px; float:left;width:800px;padding-top: 20px;">

        <table class="table-table">
            <tbody>
            <tr>
                <td class="table-th">ID</td>
                <td class="table-th">სახელი</td>
                <td class="table-th">ფასის გენერირების ტიპი</td>
                <td class="table-th">შეცვლა</td>
                <td class="table-th">წაშლა</td>

            </tr>
            <? foreach($service_types as $key=>$value){?>
                <tr>
                    <td class="table-td"><?=$value['id']?></td>
                    <td class="table-td"><?=$value['name']?></td>
                    <td class="table-td">
                        <?if($value['type_of']==1){
                            ?>
                            ფასის გენერირება დარბაზზე
                            <?}else{?>
                            ფასის გენერირება თითოეულ კაცზე
                            <? } ?>
                    </td>
                    <td class="table-td">
                        <img class="add_edit_modal_trigger" action="edit" service_id="<?=$value['id']?>"
                             src="./images/icos16/edit.gif" width="16" height="16" alt="edit" border="0">
                    </td>
                    <td class="table-td"></td>

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
                    url: "index_ajax.php?cmd=get_ch_service_type_info",
                    method: "POST",
                    data: {service_id: $(this).attr('service_id')},
                    dataType: "json"
                });

                request.done(function (msg) {
                    $("input[name='service_type']").val(msg.name);
                    $("select[name='type_of']").val(msg.type_of);
                    $("input[name='service_id']").val(msg.id);
                    $("input[name='action']").val('edit');
                });

                request.fail(function (jqXHR, textStatus) {
                    alert("Request failed: " + textStatus);
                });
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

            $("#add_edit_service_type_modal").dialog({
                resizable: false,
                width: 400,
                modal: true,
                buttons: btns
            });
        });
    });
</script>