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
                    <td>სერვისის ტიპი</td>
                    <td nowrap>
                        <select name="service_type" id="service_type">
                            <option value="0">აირჩიეთ სერვისის ტიპი</option>
                            <? foreach($TMPL_services_types AS $services_type){ ?>
                                <option value="<?=$services_type['id']?>" <?=($services_type['id']==$TMPL_service['type_id'])?'selected':''?>><?=$services_type['name'] ?></option>
                            <?}?>
                        </select>
                    </td>
                </tr>
            <?}?>
            <? for($i=0;$i<count($TMPL_lang);$i++){ ?>
                <tr>
                    <td> დასახელება (<?=$TMPL_lang[$i]?>)</td>
                    <td nowrap><input type="text" name="name[<?=$TMPL_lang[$i]?>]" value="<?=$TMPL_service['title'][$TMPL_lang[$i]]?>" class="formField1"></td>
                </tr>
            <?}?>
            <tr>
                <td><b>ფასი:</b></td>
                <td nowrap><input type="number" name="price" value="0" class="formField1" ></td>
            </tr>
        </table>
        <input type="hidden" name="action" id="action" value="<?=$_GET['action']?$_GET['action']:'add'?>">
        <input type="hidden" name="service_id" id="service_id" value="0">
    </form>
</div>
<div id="add_service_modal_trigger" action="add" class="add_edit_modal_trigger formButton2"  style="margin-top:10px; padding:2px;   cursor: pointer; text-align: centerl; width: 160px; float:right">
    <b>+ სერვისის დამატება</b>
</div>
<div style="clear:both"></div>


<div style="float:left;width:100%;">

    <div style="margin-left: 4px; float:left;width:800px;padding-top: 20px;">

        <table class="table-table">
            <tbody>
            <tr>
                <td class="table-th">ID</td>
                <td class="table-th">Name</td>
                <td class="table-th">Type</td>
                <td class="table-th">price</td>
                <td class="table-th">Edit</td>
                <td class="table-th">Delete</td>

            </tr>
            <? foreach($services as $key=>$value){?>
                <tr>
                    <td class="table-td"><?=$value['id']?></td>
                    <td class="table-td"><?=$value['name']?></td>
                    <td class="table-td"><?=$value['c_name']?></td>
                    <td class="table-td"><?=$value['price']?></td>
                    <td class="table-td">
                        <a class="add_edit_modal_trigger" href="#" service_id="<?=$value['id']?>">
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
                    url: "index_ajax.php?cmd=get_ch_service_info",
                    method: "POST",
                    data: {service_id: $(this).attr('service_id')},
                    dataType: "json"
                });

                request.done(function (msg) {
                    $("input[name='price']").val(parseFloat(msg.price));
                    $("input[name='service_id']").val(msg.id);
                    $("#service_type").val(msg.type_id);
                    $.each(msg.name, function(k, v) {
                        $("input[name='name["+k+"]']").val(v);
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