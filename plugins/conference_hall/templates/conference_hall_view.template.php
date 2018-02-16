<link href="./js/build/jquery.datetimepicker.min.css" rel="stylesheet" type="text/css" />
<script src="./js/build/jquery.datetimepicker.full.min.js"></script>
<script src="./js/JS_serialize.js"></script>
<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>

<table width="100%">
    <tr>
        <td width="50%">
            <div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
                <div style="padding:2px; background:#3A82CC; color:#FFF">
                    <b><?=$TEXT['view']['booking_info']?></b>
                </div>
                <table border="0" style="width:100%; " cellpadding="1" cellspacing="0" class="text1">
                    <tr>
                        <td width="50%">
                            <table border="0" style="width:100%; " cellpadding="1" cellspacing="0" class="text1">
                                <tr>
                                    <td class="tdrow1"><?=$TEXT['view']['booking_id']?></td>
                                    <td class="tdrow2"><span class="text_black booking_id"><?=$TMPL_booking['id']?></span></td>
                                </tr>

                                <tr>
                                    <td class="tdrow1"><?=$TEXT['view']['affiliate']?></td>
                                    <td class="tdrow2">
                                      <?=$TMPL_booking['guest']['name']?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tdrow1"><?=$TEXT['view']['guests_count']?></td>
                                    <td class="tdrow2">
                                      <div class="change_guest_count_modal_trigger">
                                          <?=$TMPL_booking['adult_num']?>
                                      </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="tdrow1"><?=$TEXT['view']['checkin_checkout']?></td>
                                    <td class="tdrow2">
                                        <div class="<?=($TMPL_booking['check_out']<date('Y-m-d'))?'':'change_checkin_checkout_modal_trigger'?>" style="display: inline; padding:2px; border:solid #868686 1px; cursor: pointer; text-align: center">
                                            <?=$TMPL_booking['check_in']?> - <?=$TMPL_booking['check_out']?>
                                        </div>
                                    </td>
                                </tr>
                                <?if($TMPL_booking['parent_id']>0){?>
                                <tr>
                                    <td class="tdrow1" style="color:red"><?=$TEXT['view']['has_parent']?></td>
                                    <td class="tdrow2">
                                        <div >
                                            <a href="index.php?m=booking_management&tab=booking_list&action=view&amp;booking_id=<?=$TMPL_booking['parent_id']?>">
                                                <?=$TMPL_booking['parent_id']?>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?}?>
                                <?if($TMPL_booking['child_id']>0){?>
                                    <tr>
                                        <td class="tdrow1" style="color:red"><?=$TEXT['view']['has_child']?></td>
                                        <td class="tdrow2">
                                            <div >
                                                <a href="index.php?m=booking_management&tab=booking_list&action=view&amp;booking_id=<?=$TMPL_booking['child_id']?>">
                                                    <?=$TMPL_booking['child_id']?>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td class="tdrow1"><?=$TEXT['view']['comment']?></td>
                                    <td class="tdrow2">
                                        <form method="post">
                                        <input type="hidden" name="action" value="update_booking_comment">
                                        <table width="100%">
                                            <tr>
                                                <td>
                                                    <textarea name="booking_comment" style="width:100%" class="formField3" id="booking_comment"><?=$TMPL_booking['comment']?></textarea>
                                                </td>
                                            </tr>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="submit" value="<?=$TEXT['view']['save']?>" class="formButton2" style="margin-top:6px;cursor:pointer;padding-left:4px;float:right;width:100px;">
                                                </td>
                                            </tr>
                                        </table>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
<table width="100%">
    <tr>
        <td width="50%">
            <div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
                <div style="padding:2px; background:#3A82CC; color:#FFF">
                    <b>ოთახები </b>
                </div>
            </div>
        </td>
    </tr>
    <tr>
     <td>
         <table border="0" style="width:100%; " cellpadding="1" cellspacing="0" class="text1">
             <tr>
                 <td width="80%">
                     <table border="0" style="width:100%; " cellpadding="1" cellspacing="0" class="text1">
                         <? foreach($TMPL_booking['rooms'] as $room){ ?>
                             <tr>
                                 <td class="tdrow1"><?=$TEXT['view']['room']?></td>
                                 <td class="tdrow2">
                                     <div class="change_guest_count_modal_trigger">
                                         <?=$room['name']?> <b> ღირებულება <?=$TMPL_booking['info']['price']?>
                                     </div>
                                 </td>
                             </tr>
                             <tr>
                                 <td class="tdrow1">სერვისები</td>
                                 <td class="tdrow2">
                                    <div class="services_list">
                                        <?php $tmps=0; foreach ($TMPL_booking['service_info'] as $key => $value): $tmps+=$value['count'][1];?>
                                            <div style="width:100%;float:left;">
                                              <span><?=$value['info'][0]['name']?></span>
                                              <span class="float-right">(<?=$value['count'][0]?> პერსონაზე) (ღირებულება <?=$value['count'][1]?>) </span>
                                              <a class="service_delete_trigger" href="#" style="float:right;padding:0 10px;"  service_id="<?=$key?>">
                                                <img src="./images/icos16/delete.gif" width="16" height="16" alt="edit" border="0">
                                              </a>
                                              <a class="add_edit_modal_trigger" href="#" style="float:right;padding:0 10px;" count="<?=$value['count'][0]?>" service_id="<?=$key?>">
                                                  <img src="./images/icos16/edit.gif" width="16" height="16" alt="edit" border="0">
                                              </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div id="services_modal_trigger"  style="float:left;width:50%;;padding:2px; border:solid #868686 1px;  cursor: pointer; text-align: center">
                                        <b><?= $TEXT['booking_modal']['add_service'] ?></b>
                                    </div>
                                 </td>
                             </tr>
                             <tr>

                                 <td class="tdrow1">დალაგების ტიპი</td>
                                 <td class="tdrow2">
                                     <select name="type_id" id="type_ind_<?=$room['room_id']?>" style="width: 50%">
                                         <? foreach($room['types'] as $type){?>
                                             <option value="<?=$type['id']?>" data-cap="<?=$type['capacity']?>" ><?=$type['name']." (".$type['capacity'].")"?></option>
                                         <? } ?>
                                     </select>
                                 </td>
                                 </td>
                             </tr>
                             <tr>

                                 <td class="tdrow1">სტუმრების რაოდენობა</td>
                                 <td class="tdrow2">
                                     <input type="number" id="person_cap_<?=$room['room_id']?>" min="0" onKeyUp="getMaxvalue(<?=$room['room_id']?>,this)" value="<?=$room['person_cap']?>" name="peroson_cap">
                                 </td>
                                 </td>
                             </tr>
                             <tr>

                                 <td class="tdrow1">სტუმრების რაოდენობა</td>
                                 <td class="tdrow2">
                                     <input type="number" id="person_cap_<?=$room['room_id']?>" min="0" onKeyUp="getMaxvalue(<?=$room['room_id']?>,this)" value="<?=$room['person_cap']?>" name="peroson_cap">
                                 </td>
                                 </td>
                             </tr>
                             <tr>

                                 <td class="tdrow1">საერთო ღირებულება </td>
                                 <td class="tdrow2">
                                   <?=($TMPL_booking['info']['price']+$tmps)?> ₾
                                 </td>
                                 </td>
                             </tr>
                               <tr>

                                 <td class="tdrow1"></td>
                                 <td class="tdrow2">
                                     <a href="index.php?m=conference_hall&tab=conference_hall_list&action=delete&id=<?=$_GET['booking_id']?>&amp;room_id=<?=$room['room_id']?>">წაშლა</a>
                                 </td>
                                 </td>
                             </tr>
                         <? } ?>
                     </table>
                 </td>
                 <td width="20%"></td>
             </tr>
         </table>
         <table>
             <tr>
             <td>

             </td>
                 <td>
                     <a target="blank" href="<?="index.php?m=conference_hall&tab=conference_hall_list&action=get_invoice&booking_id=".$TMPL_booking['id']?>">ინვოისის ნახვა</a>
                 </td>
             </tr>
         </table>
     </td>
 </tr>

</table>

<div id="add_edit_service_modal" style="display:none" title="სერვისის რედაქტირება">
    <table>
        <tr>
            <td><label for="service_title"><?= $TEXT['service_modal']['name'] ?></label></td>
            <td>
              <select name="services[]" id="services" class="service_price_gen"  style="width:100%;">
                  <? foreach($TMPL_services as $service){ ?>
                      <option value="<?=$service['id']?>" ><?=$service['name']?></option>
                  <?  } ?>
              </select>
            </td>
        </tr>
        <tr>
            <td><label for="services_count"><?= $TEXT['service_modal']['services_count'] ?></label></td>
            <td>
                <input id="services_count" name="services_count"  style="width:100%;">
            </td>
        </tr>
        <tr>
            <td><label for="service_price"><?= $TEXT['service_modal']['price'] ?></label></td>
            <td><input type="number" id="service_price" name="service_price" min="0" value="0" step="any" style="width:160px"/></td>
        </tr>
    </table>
</div>
<script>

  $(document).ready(function(){
    $(".add_edit_modal_trigger").click(function () {
          var count=$(this).attr('count');
            act_but_title="რედაქტირება";
            var request = $.ajax({
                url: "index_ajax.php?cmd=get_ch_service_info",
                method: "POST",
                data: {service_id: $(this).attr('service_id')},
                dataType: "json"
            });

            request.done(function (msg) {
                $("#add_edit_service_modal #services").val(msg.id);
                $("#add_edit_service_modal #service_price").val(parseInt(msg.price)*parseInt(count));
                $("#add_edit_service_modal #services_count").val(count);
            });

            request.fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
            $("#action").val('edit');

        var btns={};
        btns[act_but_title]= function () {

          updateService();
          $(this).dialog("close");
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
    $("#services_modal_trigger").click(function () {
      $("#add_edit_service_modal #services_count").val(1);
        $("#add_edit_service_modal").dialog({
            resizable: false,
            width: 400,
            modal: true,
            buttons: {
                "<?= $TEXT['service_modal']['but_add'] ?>": function () {
                    saveService();
                    $(this).dialog("close");
                },
        "<?= $TEXT['service_modal']['but_cancel'] ?>": function () {
                    $(this).dialog("close");
                }
            }
        });
        var $target=$('#booking_service_modal').dialog().parent();
        $target.css('top',(window.innerHeight-$target.height())/2);
        $target.css('left',(window.innerWidth-$target.width())/2);
        $target.css('position','fixed');
    });
    $('#services_count').change(function(){
      var index=$(this).val();
      var price_R=$('#service_price').val();
      var price=parseInt(price_R)*parseInt(index);
      $('#service_price').val(price);
    })
    $('.service_price_gen').change(function(){
      var id=$(this).val();
      var cmd="get_ch_service_price";
      var request = $.ajax({
          url: "index_ajax.php?cmd=" + cmd,
          method: "POST",
          data: {id:id},
      });

      request.done(function (msg) {
          msg=JSON.parse (msg);
          var price_R=msg['price'];
          var price=parseInt(price_R)*parseInt($('#services_count').val());
          $('#service_price').val(price);
      });
    })
    $('#service_count').change(function(){
      var id=$(this).val();
      var cmd="get_ch_service_price";
      var request = $.ajax({
          url: "index_ajax.php?cmd=" + cmd,
          method: "POST",
          data: {id:id},
      });

      request.done(function (msg) {
          msg=JSON.parse (msg);
          var price_R=msg['price'];
          var price=parseInt(price_R)*parseInt($('#services_count').val());
          $('#service_price').val(price);
      });
    })
  })
  function saveService(){
    var cmd='add_ch_service_booking';
    var booking_id=parseInt($('.booking_id').text());
    var service_id=$('.service_price_gen').val();
    var count=$('#services_count').val();
    var price=$('#add_edit_service_modal #service_price').val();
    console.log(price);
    var request = $.ajax({
        url: "index_ajax.php?cmd=" + cmd,
        method: "POST",
        data: {booking_id:booking_id,service:service_id,count:count,price:price},
    });
    request.done(function (msg) {
      msg=$.parseJSON(msg);
      if(msg['status'] == 'OK'){
        location.reload();
      }
        console.log(msg);
    });
  }
  function updateService(){
    var cmd='add_ch_service_booking';
    var booking_id=parseInt($('.booking_id').text());
    var service_id=$('#add_edit_service_modal #services').val();
    var count=$('#add_edit_service_modal #services_count').val();
    var price=$('#add_edit_service_modal #service_price').val();
    var request = $.ajax({
        url: "index_ajax.php?cmd=" + cmd,
        method: "POST",
        data: {booking_id:booking_id,service:service_id,count:count,price:price},
    });
    request.done(function (msg) {
      msg=$.parseJSON(msg);
      if(msg['status'] == 'OK'){
        location.reload();
      }
      console.log(msg);
    });
  }
    function getMaxvalue(id,tr){
        var idr=$('#type_ind_'+id+' :selected').data('cap');
        if($(tr).val()>idr){
            $(tr).val(idr);
        }

    }
        function saveRoom(booking ,id){
            event.preventDefault();
            var cmd='booking_room_info_save';
            var services=$('#services_'+id).val();
            var room_type=$('#type_ind_'+id).val();
            var person_cap=$('#person_cap_'+id).val();

            var formDate= {
                booking_id: booking,
                services: services,
                room_type: room_type,
                person_cap: person_cap,
                room_id: id,
            };

            var request = $.ajax({
                url: "index_ajax.php?cmd=" + cmd,
                method: "POST",
                data: formDate,

            });

            request.done(function (msg) {
                msg=JSON.parse (msg);
                if(msg.error==0){
                    var type='success';
                }else{
                    var type='warning'
                }
                swal({
                    title: msg.text,
                    text: "I will close in 2 seconds.",
                    timer: 2000,
                    showConfirmButton: false,
                    type: type
                });
            });
        }

</script>
