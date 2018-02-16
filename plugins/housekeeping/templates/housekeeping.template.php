<style>
.vac{
vertical-align: middle;
}
</style>


<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['housekeeping']['filter_modal']['title'] ?></b>
    </div>
    <form action="" method="get" id="filter_form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td align="right">
                    <label for="block"><?= $TEXT['housekeeping']['filter_modal']['block'] ?></label>
                </td>
                <td>
                    <select id="block" name="block">
                        <option value=""><?=$TEXT['housekeeping']['filter_modal']['all']?></option>
                        <? foreach ($TMPL_blocks as $block) { ?>
                            <option
                                value="<?= $block['id'] ?>" <? if ((int)$_GET['block'] == $block['id']) {
                                echo "selected";
                            } ?>><?= $block['title']?></option>
                        <? } ?>
                    </select>
                </td>
                <td align="right">
                    <label for="floor"><?= $TEXT['housekeeping']['filter_modal']['floor'] ?></label>
                </td>
                <td>
                    <select id="floor" name="floor">
                        <option value=""><?=$TEXT['housekeeping']['filter_modal']['all']?></option>
                        <? for ($i=1;$i<=$TMPL_max_floors; $i++) { ?>
                            <option
                                value="<?= $i ?>" <? if ((int)$_GET['floor'] == $i) {
                                echo "selected";
                            } ?>><?= $i?></option>
                        <? } ?>
                    </select>
                </td>

            </tr>
            <tr>
                <td align="right">
                    <label for="room_title"><?= $TEXT['housekeeping']['filter_modal']['room_title'] ?></label>
                </td>
                <td>
                    <input type="text" id="room_title" name="room_title" value="<?= $_GET['room_title'] ?>"/>
                </td>
                <td align="right">
                    <label for="amount_from"><?= $TEXT['housekeeping']['filter_modal']['status'] ?></label>
                </td>
                <td>
                    <select id="status" name="status">
                        <option value=""><?=$TEXT['housekeeping']['filter_modal']['all']?></option>
                        <? foreach ($TEXT['housekeeping_statuses'] as $k=>$v) { ?>
                            <option
                                value="<?= $k ?>" <? if ($_GET['status'] == $k) {
                                echo "selected";
                            } ?>><?= $v?></option>
                        <? } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="room_type"><?= $TEXT['housekeeping']['filter_modal']['room_type'] ?></label>
                </td>
                <td>
                <select id="room_type" name="room_type">
                    <option value=""><?=$TEXT['housekeeping']['all']?></option>
                    <? foreach ($TMPL_room_types as $k=>$v) { ?>
                       <option
                           value="<?= $k ?>" <? if ($_GET['room_type'] == $k) {
                           echo "selected";
                       } ?>><?= $v['title']?></option>
                   <? } ?> </select>
                </td>
                <td align="right">
                    <label for="availability"><?= $TEXT['housekeeping']['filter_modal']['availability'] ?></label>
                </td>
                <td>
                     <select id="availability" name="availability">
                        <option value=""><?=$TEXT['housekeeping']['all']?></option>
                        <option value="free"  <? if ($_GET['availability'] == 'free') {echo "selected";} ?>><?=$TEXT['housekeeping']['availability_types']['free']?></option>
                        <option value="check_in"  <? if ($_GET['availability'] == 'check_in') {echo "selected";} ?>><?=$TEXT['housekeeping']['availability_types']['check_in']?></option>
                        <option value="check_out"  <? if ($_GET['availability'] == 'check_out') {echo "selected";} ?>><?=$TEXT['housekeeping']['availability_types']['check_out']?></option>
                        <option value="in_use"  <? if ($_GET['availability'] == 'in_use') {echo "selected";} ?>><?=$TEXT['housekeeping']['availability_types']['in_use']?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right" colspan="4">
                    <br>
                    <input class="formButton2" type="submit" value="<?= $TEXT['housekeeping']['filter_modal']['submit_filter'] ?>" style="cursor: pointer">
                </td>
            </tr>
        </table>
    </form>
</div>

<div style="height: 10px;">

</div>

<form action="" method="POST">
<input type="hidden" name="action" value="multi_change_status">
<table class="table-table" cellpadding="2" cellspacing="0">
    <tr>
        <td class="table-th">
            <input type="checkbox" id="check_all">
        </td>
        <td class="table-th">&#8470;</td>
        <td class="table-th">
            <?= $TEXT['housekeeping']['room_name'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['housekeeping']['floor'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['housekeeping']['room_type'] ?>
        </td>
        <td class="table-th" width="150">
            <?= $TEXT['housekeeping']['status'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['housekeeping']['availability'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['housekeeping']['spending_materials'] ?>
        </td>
        <td class="table-th">
            <?= $TEXT['housekeeping']['mini_bar'] ?>
        </td>
    </tr>
    <? $debit = 0;
    $credit = 0;//p($TMPL_room_types);
    $rooms_counter=0;
    foreach ($TMPL_rooms as $k=> $room) {$rooms_counter++;
        ?>
        <tr class="table-tr">
            <td class="table-td vac">
                <input type="checkbox" class="room_checker" value="<?=$room['id']?>" room_id="<?=$room['id']?>" name="selected_rooms[]">
            </td>
            <td class="table-td vac">
                <b><?=$rooms_counter ?></b>
            </td>
            <td class="table-td vac">
                <?=$room['name']?>
            </td>
            <td class="table-td vac">
                <?=$room['floor']?>
            </td>
            <td class="table-td vac">
                <?=$TMPL_room_types[$room['type_id']]['title']?>
            </td>
            <td class="table-td vac" align="center">
            <?
            if($room['housekeeping_status']=='clean'){
                $color="#83FA00";
                $tcolor="#000";
            }elseif($room['housekeeping_status']=='touchup'){
                $color="#FF8F00";
                $tcolor="#000";
            }elseif($room['housekeeping_status']=='dirty'){
                $color="#FF0000";
                $tcolor="#FFF";
            }elseif($room['housekeeping_status']=='dnr'){
                $color="#D90D7D";
                $tcolor="#FFF";
            }elseif($room['housekeeping_status']=='inspect'){
                $color="#00C8F2";
                $tcolor="#000";
            }
            ?>
            <div id="room_status_<?=$room['id']?>" style="padding:2px; border:solid gray 1px; background-color: <?=$color?>">
                <div id="status_change_trigger_<?=$room['id']?>" class="status_change_trigger text1"  style="cursor:pointer; text-decoration: none; color:<?=$tcolor?>"
                status="<?=$room['housekeeping_status']?>" room_id="<?=$room['id']?>">
                    <?=$TEXT['housekeeping_statuses'][$room['housekeeping_status']]?>
                </div>
            </div>
            </td>
            <td class="table-td vac">
               <?
                   $statuses=$TMPL_rooms_states[$room['id']];
                   if(count($statuses)==0){
                       echo $TEXT['housekeeping']['availability_types']['free'];
                   }else{
                        for($k=0;$k<count($statuses);$k++){
                            echo $TEXT['housekeeping']['availability_types'][$statuses[$k]];
                        }
                   }
               ?>
            </td>
            <td class="table-td" width="200" style="vertical-align: bottom">
                <table id="sp_table_<?=$room['id']?>" width="100%" style="border-collapse: collapse; color: #3a82cc; background-color: #FFFFFF" >
                <?if(count($room['spending_materials'])>0){ $sp_counter=0;?>

                     <?foreach($room['spending_materials'] AS $k=>$spending_material){ $sp_counter++;?>
                       <tr>
                           <td width="20" style=" border:solid #d1dceb 1px;"><?=$sp_counter ?></td>
                           <td style=" border:solid #d1dceb 1px;"><?=$k?></td>
                           <td width="30" style=" border:solid #d1dceb 1px;"><?=count($spending_material)?></td>
                           <td width="18" style=" border:solid #d1dceb 1px;">
                                <div room_id="<?=$room['id']?>" service_id="<?=$spending_material[0]['service_id']?>" service_type_id="<?=$spending_material[0]['service_type_id']?>" class="del_sp_trigger" style="cursor: pointer">
                                    <img width="16" height="16" border="0" alt="delete" src="./images/icos16/delete.gif">
                                </div
                           </td>
                       </tr>
                     <?}?>
                <?}else{?>
                <!--tr>
                    <td style=" border:solid #d1dceb 1px;" valign="center" align="center">No Items</td>
                </tr-->
                <?}?>
                </table>
                <div class="add_modal_trigger orange-but" sp_type_id="10" room_id="<?=$room['id']?>">
                    <?=$TEXT['spending_materials_modal']['trigger']?>
                </div>
            </td>
            <td class="table-td" width="200" style="vertical-align: bottom">
                <table id="mb_table_<?=$room['id']?>" width="100%" style="border-collapse: collapse; color: #3a82cc; background-color: #FFF">
                <?if(count($room['minibar_items'])>0){ $mb_counter=0;?>

                     <?foreach($room['minibar_items'] AS $k=>$minibar_item){ $mb_counter++;?>
                       <tr>
                           <td width="20" style=" border:solid #d1dceb 1px;"><?=$mb_counter ?></td>
                           <td style=" border:solid #d1dceb 1px;"><?=$k?></td>
                           <td width="30" style=" border:solid #d1dceb 1px;"><?=count($minibar_item)?></td>
                           <td width="18" style=" border:solid #d1dceb 1px;">
                                <div room_id="<?=$room['id']?>" service_id="<?=$minibar_item[0]['service_id']?>" service_type_id="<?=$minibar_item[0]['service_type_id']?>" class="del_sp_trigger" style="cursor: pointer; width:16px">
                                    <img width="16" height="16" border="0" alt="delete" src="./images/icos16/delete.gif">
                                </div
                           </td>
                       </tr>
                     <?}?>
                <?}else{?>
                    <!--tr>
                        <td style=" border:solid #d1dceb 1px;" valign="center" align="center">No Items</td>
                    </tr-->
                <?}?>
                </table>
                <div class="add_modal_trigger orange-but" sp_type_id="4" room_id="<?=$room['id']?>" >
                    <?=$TEXT['mb_modal']['trigger']?>
                </div>
            </td>
        </tr>
    <? } ?>
</table>


<div id="list_table" style="display:none">
   <table  class="table-table" cellpadding="2" cellspacing="0" border="1" width="100%">
       <tr>
           <td class="table-th" width="20">&#8470;</td>
           <td class="table-th" width="50">
               <?= $TEXT['housekeeping']['room_name'] ?>
           </td>
           <td class="table-th" width="60">
               <?= $TEXT['housekeeping']['floor'] ?>
           </td>
           <td class="table-th" width="160">
               <?= $TEXT['housekeeping']['room_type'] ?>
           </td>
           <td class="table-th" width="120">
               <?= $TEXT['housekeeping']['status'] ?>
           </td>
           <td class="table-th" width="120">
               <?= $TEXT['housekeeping']['availability'] ?>
           </td>
           <td class="table-th">
               <?= $TEXT['housekeeping']['comment'] ?>
           </td>
       </tr>
       <? $debit = 0;
       $credit = 0;//p($TMPL_room_types);
       $rooms_counter=0;
       foreach ($TMPL_rooms as $k=> $room) {$rooms_counter++;
           ?>
           <tr class="table-tr">
               <td class="table-td vac">
                   <b><?=$rooms_counter ?></b>
               </td>
               <td class="table-td vac">
                   <?=$room['name']?>
               </td>
               <td class="table-td vac">
                   <?=$room['floor']?>
               </td>
               <td class="table-td vac">
                   <?=$TMPL_room_types[$room['type_id']]['title']?>
               </td>
               <td class="table-td vac" align="center">
                  <?=$TEXT['housekeeping_statuses'][$room['housekeeping_status']]?>
               </td>
               <td class="table-td vac">
                   <?
                   $statuses=$TMPL_rooms_states[$room['id']];
                   if(count($statuses)==0){
                       echo $TEXT['housekeeping']['availability_types']['free'];
                   }else{
                       for($k=0;$k<count($statuses);$k++){
                           echo $TEXT['housekeeping']['availability_types'][$statuses[$k]];
                       }
                   }
                   ?>
               </td>
               <td class="table-td vac" align="center">
               </td>

           </tr>
       <? } ?>
   </table>
</div>


<div id="print_button" class="orange-but">PRINT</div>

<div id="multi_selector_div" style="display: none; margin-top: 8px; width:300px;">
    <select name="status"  class="multi_statuses_selector">
     <?foreach($TEXT['housekeeping_statuses'] AS $k=>$v){?>
      <option value="<?=$k?>"><?=$v?></option>
     <?}?>
    </select>
    <input class="formButton2" type="submit" style="cursor: pointer" value="Change">
</div>
</form>
<div>
    <center><?= $TMPL_navbar ?></center>
</div>
<form method="post" target="_blank" id="excel_download_form">
    <input type="hidden" name="action" value="get_excel">
</form>
<div id="statuses_selector_div" style="display: none">
    <select  class="statuses_selector" room_id="0">
     <?foreach($TEXT['housekeeping_statuses'] AS $k=>$v){?>
      <option value="<?=$k?>"><?=$v?></option>
     <?}?>
    </select>
</div>

<div id="del_sp_modal" style="display: none; width:400px;" title="<?=$TEXT['del_sp_modal']['title'] ?>" >
    <div id="del_sp_modal_message"></div>
    <div><?=$TEXT['del_sp_modal']['message'] ?></div>
</div>


<div id="add_sp_modal" style="display: none; width:400px;" title="<?=$TEXT['spending_materials_modal']['title'] ?>" >
    <div id="add_sp_modal_message"></div>
    <form name="add_sp_modal_form" id="add_sp_modal_form">
        <input type="hidden" name="room_id" value="0">
        <input type="hidden" name="service_type_id" value="0">
        <table>
            <tr>
                <td>
                    <label for="service_id"><?=$TEXT['spending_materials_modal']['sp']?></label>
                </td>
                <td>
                    <label for="count"><?=$TEXT['spending_materials_modal']['count']?></label>
                </td>
            </tr>
            <tr>
                <td>
                    <select name="service_id">
                        <?foreach($TMPL_spending_materials AS $spending_material){?>
                            <option value="<?=$spending_material['id']?>"><?=$spending_material['title']?></option>
                        <?}?>
                    </select>
                </td>
                <td><input name="count" type="number" value="1" min="1" step="1" style="width: 40px"></td>
            </tr>
        </table>
    </form>
</div>


<div id="add_mb_modal" style="display: none; width:400px;" title="<?=$TEXT['mb_modal']['title'] ?>" >
    <div id="add_mb_modal_message"></div>
    <form name="add_mb_modal_form" id="add_mb_modal_form">
        <input type="hidden" name="room_id" value="0">
        <input type="hidden" name="service_type_id" value="0">
        <table>
            <tr>
                <td>
                    <label for="service_id"><?=$TEXT['mb_modal']['mb']?></label>
                </td>
                <td>
                    <label for="count"><?=$TEXT['mb_modal']['count']?></label>
                </td>
            </tr>
            <tr>
                <td>
                    <select name="service_id">
                        <?foreach($TMPL_mb_items AS $TMPL_mb_item){?>
                            <option value="<?=$TMPL_mb_item['id']?>"><?=$TMPL_mb_item['title']?></option>
                        <?}?>
                    </select>
                </td>
                <td><input name="count" type="number" value="1" min="1" step="1" style="width: 40px"></td>
            </tr>
        </table>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        var statuses=[];
        statuses['clean']={ title : 'clean', color: '#83FA00', tcolor : '#000'};
        statuses['touchup']={ title : 'touchup', color: '#FF8F00', tcolor : '#000'};
        statuses['dirty']={ title : 'dirty', color: '#FF0000', tcolor : '#FFF'};
        statuses['dnr']={ title : 'dnr', color: '#D90D7D', tcolor : '#FFF'};
        statuses['inspect']={ title : 'inspect', color: '#00C8F2', tcolor : '#000'};

        var oldHtml='';
        var old_room_id=0;
        var old_status='';

        $('.status_change_trigger').live( "click", function() {
           var room_id=$(this).attr('room_id');
           var status=$(this).attr('status');
           if(old_room_id!=0){
            $('#room_status_'+old_room_id).html(oldHtml);
           }

           old_room_id=room_id;
           old_status=status;
           oldHtml=$('#room_status_'+room_id).html();

           //var selector='<select  id="stauses_selector_'+room_id+'" room_id="'+room_id+'">';
           $('#statuses_selector_div .statuses_selector').attr('id','statuses_selector_'+room_id);
           $('#statuses_selector_div .statuses_selector').attr('room_id',room_id);

           $('#room_status_'+room_id).html($('#statuses_selector_div').html());
           $('#room_status_'+room_id+' .statuses_selector').val(status);
           $('#room_status_'+room_id+' .statuses_selector').focus();
        });

        $('.statuses_selector').live( "change", function() {
            change_room_housekeeping_status($(this).attr('room_id'),$(this).val(),$("#"+$(this).attr('id')+' option:selected').text());
        });
        $('.statuses_selector').live( "focusout", function() {
           if(old_room_id!=0){
              $('#room_status_'+old_room_id).html(oldHtml);
              old_room_id=0;
           }

        });


        function change_room_housekeeping_status(room_id,status,status_title){
            var request = $.ajax({
                               url: "index_ajax.php?cmd=change_room_housekeeping_status",
                               method: "POST",
                               data: {
                                   room_id: room_id,
                                   status: status,
                                   status_title:status_title
                               },
                               dataType: "json"
                           });

           request.done(function (msg) {
                $('#room_status_'+msg.room_id).html(oldHtml);
                $('#room_status_'+msg.room_id).css('background-color',statuses[msg.status].color);
                $('#status_change_trigger_'+msg.room_id).css('color',statuses[msg.status].tcolor);
                $('#status_change_trigger_'+msg.room_id).attr('status', msg.status);
                $('#status_change_trigger_'+msg.room_id).text(msg['status_title']);
                old_room_id=0;
                old_status='';
                oldHtml='';
           });

           request.fail(function (jqXHR, textStatus) {
               $('#room_status_'+old_room_id).html(oldHtml);
               old_room_id=0;
               old_status='';
               oldHtml='';
               alert("Request failed: " + textStatus);

           });
        }
        $('#check_all').change(function () {
            $(".room_checker").each(function () {
                if($('#check_all').attr('checked')=='checked'){
                    $(this).prop('checked', true);
                }else{
                    $(this).prop('checked', false);
                }
            });
            if($(this).attr('checked')=='checked'){
                $('#multi_selector_div').show();
            }else{
                $('#multi_selector_div').hide();
            }
        });

        $(".room_checker").change(function () {
            var selected_rooms_ids=[];
            $(".room_checker").each(function () {
                if($(this).attr('checked')=='checked'){
                    selected_rooms_ids.push($(this).attr('room_id'));
                }
            });
            if(selected_rooms_ids.length<2){
              $('#multi_selector_div').hide();
            }else{
             $('#multi_selector_div').show();
            }
        });

        var active_form_id;
        var active_modal_id;
        $(".add_modal_trigger").click(function () {
            var sp_type_id=$(this).attr('sp_type_id');
            var modal;
            if(sp_type_id==10){
                modal=$("#add_sp_modal");
                active_form_id="add_sp_modal_form";
                active_modal_id="add_sp_modal";
            }else if(sp_type_id==4){
                modal=$("#add_mb_modal");
                active_form_id="add_mb_modal_form";
                active_modal_id="add_mb_modal";
            }else{
                return;
            }
             $("#"+active_form_id+" input[name=service_type_id]").val($(this).attr('sp_type_id'));
             $("#"+active_form_id+" input[name=room_id]").val($(this).attr('room_id'));

            modal.dialog({
                resizable: false,
                width: 240,
                modal: true,
                buttons: {
                    "<?=$TEXT['spending_materials_modal']['add']?>": function () {

                        var sp = {};
                        sp.room_id = $("#"+active_form_id+" input[name=room_id]").val();
                        sp.sp_count=$("#"+active_form_id+" input[name=count]").val();
                        sp.service_id=$("#"+active_form_id+" select[name=service_id]").val();
                        sp.service_type_id=$("#"+active_form_id+" input[name=service_type_id]").val();
                        var request = $.ajax({
                            url: "index_ajax.php?cmd=add_sp",
                            method: "POST",
                            data: sp,
                            dataType: "json"
                        });

                        request.done(function (msg) {
                        if($("#"+active_form_id+" input[name=service_type_id]").val()==4){
                            fillSpTables(msg,'mb');
                        }else if($("#"+active_form_id+" input[name=service_type_id]").val()==10){
                            fillSpTables(msg,'sp');
                        }else{
                            console.log("error")
                        }

                            console.log(msg);
                            $("#"+active_modal_id).dialog("close");
                        });

                        request.fail(function (jqXHR, textStatus) {
                            $('#'+active_modal_id+'_message').text("ver daemata!");
                        });
                    },
                    "<?=$TEXT['spending_materials_modal']['cancel']?>": function () {
                        $(this).dialog("close");
                    }
                }
            });
            var $target=modal.dialog().parent();
            $target.css('top',(window.innerHeight-$target.height())/2);
            $target.css('left',(window.innerWidth-$target.width())/2);
            $target.css('position','fixed');
        });


        var del_sp_modal_invoker;
        $(".del_sp_trigger").live( "click", function() {
            del_sp_modal_invoker=$(this);
            $('#del_sp_modal').dialog({
                resizable: false,
                width: 240,
                modal: true,
                buttons: {
                    '<?=$TEXT['del_sp_modal']['yes'] ?>': function () {
                        var sp = {};
                            sp.room_id = del_sp_modal_invoker.attr('room_id');
                            sp.service_id = del_sp_modal_invoker.attr('service_id');
                            sp.service_type_id=del_sp_modal_invoker.attr('service_type_id');
                            var request = $.ajax({
                                url: "index_ajax.php?cmd=del_sp",
                                method: "POST",
                                data: sp,
                                dataType: "json"
                            });

                            request.done(function (msg) {
                                if(msg!=null && msg['error']==1){
                                    $('#del_sp_modal_message').text(msg['error_message']);
                                }else{
                                if(del_sp_modal_invoker.attr('service_type_id')==10){
                                    fillSpTables(msg,'sp');
                                }else if(del_sp_modal_invoker.attr('service_type_id')==4){
                                    fillSpTables(msg,'mb');
                                }
                                $("#del_sp_modal").dialog("close");
                                }
                            });

                            request.fail(function (jqXHR, textStatus) {
                                $('#del_sp_modal_message').text('<?=$TEXT['del_sp_modal']['error_message'] ?>');
                            });
                    },
                    '<?=$TEXT['del_sp_modal']['no'] ?>': function () {
                        $(this).dialog('close');
                    }
                }
            });
            var $target=$('#del_sp_modal').dialog().parent();
            $target.css('top',(window.innerHeight-$target.height())/2);
            $target.css('left',(window.innerWidth-$target.width())/2);
            $target.css('position','fixed');
        });

        function fillSpTables(sp,table_preffix){
            var table;
            var out='';
            var counter=0;
            var service_id=0;
            var service_type_id=0;
            for (var room_id in sp) {
                  if (sp.hasOwnProperty(room_id)) {
                        table=$("#"+table_preffix+"_table_"+room_id);
                        table.find('tr').remove();
                        counter=0;
                        for (var title in sp[room_id]) {
                            if (sp[room_id].hasOwnProperty(title)) {
                                counter++;
                                service_id=sp[room_id][title][0].service_id;
                                service_type_id=sp[room_id][title][0].service_type_id;
                                out+='<tr>';
                                    out+='<td width="20" style=" border:solid #d1dceb 1px;">'+counter+'</td>';
                                    out+='<td style="border:solid #d1dceb 1px;">'+title+'</td>';
                                    out+='<td width="30" style=" border:solid #d1dceb 1px;">'+sp[room_id][title].length+'</td>';
                                    out+='<td width="18" style=" border:solid #d1dceb 1px;">';
                                        out+='<div room_id="'+room_id+'" service_id="'+service_id+'" service_type_id="'+service_type_id+'" class="del_sp_trigger"  style="cursor: pointer">';
                                            out+='<img width="16" height="16" border="0" alt="delete" src="./images/icos16/delete.gif">';
                                        out+='</div>';
                                    out+='</td>';
                                out+='</tr>';
                            }
                        }
                        table.append(out);
                  }
            }
        }

        function printData(target_id)
        {
            var divToPrint=document.getElementById(target_id);
            newWin= window.open("");
            divToPrint.style.display = "block";
            newWin.document.write(divToPrint.outerHTML);
            divToPrint.style.display = "none";
            newWin.print();
            newWin.close();
        }
        $('#print_button').on('click',function(){
            printData('list_table');
        })
    });
</script>

<style>
    .orange-but{
        background-color: #ff8d39;
        border: 1px solid gray;
        color: #ffffff;
        cursor: pointer;
        float: right;
        margin: 2px auto;
        padding: 2px;
        text-align: center;
        width: 60px;
    }
</style>