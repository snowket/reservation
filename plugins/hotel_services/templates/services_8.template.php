
<?
$service_type_id=(int)$_GET['tab'];
if($service_type_id==0){
    $service_type_id=2;
}
?>
<div id="add_edit_service_modal" style="display: none"  title="<?=$TEXT['add_edit_service_modal']['title']?>">
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
            <tr>
                <td><img src="../uploads_script/hotel_services/no-image.png" id="service_img"></td>
                <td colspan="2" align="center">
                    <input  type="file" name="image" style="width:100%">
                </td>
            </tr>
            <? if(count($TMPL_services_types)>0){?>
                <tr>
                    <td><?=$TEXT['extra_services_type']?></td>
                    <td nowrap>
                        <select name="service_type" id="service_type">
                            <option value="0"><?=$TEXT['add_edit_service_modal']['type']?></option>
                            <? foreach($TMPL_services_types AS $services_type){ ?>
                                <option value="<?=$services_type['id']?>" <?=($services_type['id']==$service_type_id)?'selected':''?>><?=$services_type['title'] ?></option>
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
                <td nowrap><input type="number" name="price" value="0" class="formField1" readonly ></td>
            </tr>
        </table>
        <input type="hidden" name="action" id="action" value="<?=$_GET['action']?$_GET['action']:'add'?>">
        <input type="hidden" name="service_id" id="service_id" value="0">
    </form>
</div>

<b><?=$TEXT['desc'][$_GET['tab']]?></b>
<div id="add_service_modal_trigger" action="add" class="add_edit_modal_trigger formButton2"  style="margin-top:10px; padding:2px;   cursor: pointer; text-align: centerl; width: 160px; float:right">
    <b><?=$TEXT['list_table']['add_service']?></b>
</div>

<div style="clear:both"></div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".add_edit_modal_trigger").click(function () {
            var act_but_title="<?=$TEXT['add_edit_service_modal']['add']?>";
            if($(this).attr('action')=="add"){
                $("#action").val('add');
                $("input[name='price']").val(0);
                $("input[name='service_id']").val(0);

                $("#service_type").val(<?=$service_type_id?>);
                $("#service_type").attr("disabled", true);
                $("#add_edit_form input[type=text]").each(function() {
                    $(this).val("");
                });
                act_but_title="<?=$TEXT['add_edit_service_modal']['add']?>";
            }else{
                $("#service_type").attr("disabled", false);
                act_but_title="<?=$TEXT['add_edit_service_modal']['edit']?>";
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
                    if(msg.img==''){
                        $("#service_img").attr('src','../uploads_script/hotel_services/no-image.png');
                    }else{
                        $("#service_img").attr('src','../uploads_script/hotel_services/'+msg.img);
                    }


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
                    $("#service_type").attr("disabled", false);
                    $("#add_edit_form").submit();
                }
            };
            btns["<?=$TEXT['add_edit_service_modal']['cancel']?>"]= function () {
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

