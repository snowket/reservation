
<div style="background:#FFF; border:solid #3A82CC 1px; width:100%;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <b><?= $TEXT['booking_list']['filter_modal']['title'] ?></b>
    </div>
    <form action="" method="get" id="filter_form">
        <input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
        <input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td align="right">
                    <label for="guest_id"><?= $TEXT['booking_list']['filter_modal']['booking_id'] ?></label>
                </td>
                <td>
                    <input type="text" id="booking_id" name="booking_id" value="<?=$_GET['booking_id']?>"/>
                </td>
                <td align="right">
                     <label for="guest_id"><?= $TEXT['booking_list']['filter_modal']['invoice_id'] ?></label>
                 </td>
                 <td>
                     <input type="text" id="invoice_id" name="invoice_id" value="<?=$_GET['invoice_id']?>"/>
                 </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="guest_id"><?= $TEXT['booking_list']['filter_modal']['guest_id'] ?></label>
                </td>
                <td>
                    <input type="text" id="guest_id" name="guest_id" value="<?=$_GET['guest_id']?>"/>
                </td>
                <td align="right">
                    <label for="in_start_date"><?= $TEXT['booking_list']['filter_modal']['check_in_date'] ?></label>
                </td>
                <td >
                    <input type="text" class="calendar-icon" id="in_start_date" name="in_start_date" value="<?=(isset($_GET['in_start_date'])&&$_GET['in_start_date']!='')?$_GET['in_start_date']:''?>"  placeholder="<?=$TEXT['booking_list']['filter_modal']['from']?>"  autocomplete="off"/>
                    <input type="text" class="calendar-icon" id="in_end_date" name="in_end_date" value="<?=(isset($_GET['in_end_date'])&&$_GET['in_end_date']!='')?$_GET['in_end_date']:''?>"  placeholder="<?=$TEXT['booking_list']['filter_modal']['to']?>"  autocomplete="off"/>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="guest_name"><?= $TEXT['booking_list']['filter_modal']['guest_name'] ?></label>
                </td>
                <td>
                    <input type="text" id="guest_name" name="guest_name" value="<?=$_GET['guest_name']?>"/>
                </td>
                <td align="right">
                    <label for="out_start_date"><?= $TEXT['booking_list']['filter_modal']['check_out_date'] ?></label>
                </td>
                <td>
                    <input type="text" class="calendar-icon" id="out_start_date" name="out_start_date" value="<?=(isset($_GET['out_start_date'])&&$_GET['out_start_date']!='')?$_GET['out_start_date']:''?>" placeholder="<?=$TEXT['booking_list']['filter_modal']['from']?>"  autocomplete="off"/>
                    <input type="text" class="calendar-icon" id="out_end_date" name="out_end_date" value="<?=(isset($_GET['out_end_date'])&&$_GET['out_end_date']!='')?$_GET['out_end_date']:''?>" placeholder="<?=$TEXT['booking_list']['filter_modal']['to']?>"  autocomplete="off"/>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label for="guest_type"><?= $TEXT['booking_list']['filter_modal']['guest_type'] ?></label>
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
                    <label for="destination"><?= $TEXT['booking_list']['filter_modal']['status'] ?></label>
                </td>
                <td>
                    <select id="status_id" name="status_id">
                        <option value="" <? if(!isset($_GET['status_id'])){echo "selected";}?>><?=$TEXT['booking_list']['filter_modal']['all']?></option>
                        <? foreach($TMPL_booking_statuses AS $status){
                            if($status['id']==2)continue;?>
                            <option value="<?=$status['id']?>" <? if(isset($_GET['status_id'])&&(int)$_GET['status_id']==$status['id']){echo "selected";}?>>
                                <?=$status['title']?>
                            </option>
                        <?}?>
                    </select>
                </td>
            </tr>
            <tr>
             <td align="right">
             <label for="method"><?= $TEXT['booking_list']['filter_modal']['booking_method'] ?></label>
             </td>
             <td>
             <select id="method" name="method">
                 <option value="" <? if(!isset($_GET['method'])){echo "selected";}?>>
                    <?=$TEXT['booking_list']['filter_modal']['method']['all']?>
                 </option>
                 <? foreach($TEXT['booking_list']['filter_modal']['method'] AS $method_value=>$method_title){
                     if($method_value=='all')continue;?>
                     <option value="<?=$method_value?>" <? if(isset($_GET['method'])&&$_GET['method']==$method_value){echo "selected";}?>>
                         <?=$method_title?>
                     </option>
                 <?}?>
             </select>
             </td>


                   <td align="right">
                       <label for="floor"><?=$TEXT['booking_board']['floor']?></label>
                   </td>
                   <td>

                       <select name="floor" id="floor">
                           <option value="0">
                               <?=$TEXT['booking_list']['filter_modal']['method']['all']?>
                           </option>
                           <? for($i=1;$i<=$TMPL_max_floor['floor'];$i++){?>
                               <option value="<?=$i?>" <?=((isset($_GET['floor']) && $_GET['floor']==$i)?'selected':'')?>><?=$i?></option>
                           <? } ?>
                       </select>


                    <label for="active"> <?=$TEXT['booking_list']['filter_modal']['status']?></label>



                       <select name="active" id="active">
                           <option value="0">
                               <?=$TEXT['booking_list']['filter_modal']['method']['all']?>
                           </option>
                           <option value="3"  <?=(isset($_GET['active']) && $_GET['active']==3)?'selected':''?> >Canceled</option>
                       </select>

                </td>

            </tr>
            <tr>
                <td align="right" colspan="4">
                    <div id="today_checkin" class="formButton2" style="cursor: pointer; display: inline-block; padding: 0px 4px 0px 4px"><?= $TEXT['booking_list']['filter_modal']['today_checkins'] ?></div>
                    <div id="today_checkout" class="formButton2" style="cursor: pointer; display: inline-block; padding: 0px 4px 0px 4px"><?= $TEXT['booking_list']['filter_modal']['today_checkouts'] ?></div>
                    <input class="formButton2" type="submit" value="<?= $TEXT['booking_list']['filter_modal']['submit_filter'] ?>" style="cursor: pointer">
                </td>
            </tr>
        </table>
    </form>
</div>

<br>
<form  action="index.php?m=booking_management&tab=booking_dbl_list&action=get_invoice&invoice_type=multiple" target="_blank" method="post" id="send-multiple-invoices" >
    <input type="hidden" name="action" value="send_multiple_invoices">
    <table border="0" style="width:100%;" cellpadding="0" cellspacing="0" class="table-table">
        <tr>
            <td class="table-th" height="20" valign="top" align="center">
            &#8470;
            </td>
            <td class="table-th" height="20" valign="top" align="center">
                ჯავშნის დასახელება
            </td>
            <td class="table-th" height="20" valign="top" align="center">
                დამკვეთი
            </td>
            <td class="table-th" height="20" valign="top" align="center">
                 საკონტაქტო პირი
            </td>

            <td class="table-th" height="20" valign="top" align="center">
                 ტელეფონი
            </td>
            <td class="table-th" height="20" valign="top" align="center">
                მეილი
            </td>
            <td class="table-th" height="20" valign="top" align="center">
                ღირებულება
            </td>
            <td class="table-th" height="20" valign="top" align="center">
                შესვლა
            </td>
            <td class="table-th" height="20" valign="top" align="center">
                გამოსვლა
            </td>
            <td class="table-th" height="20" valign="top" align="center">
                ინვოისი
            </td>
            <td class="table-th" height="20" valign="top" align="center">
                ცვლილება
            </td>

        </tr>
        <? $i=0; foreach($TMPL_booking as $booking){ ?>
        <tr class="master_tr_class">
            <td class="table-td" align="center">
                <a href="#" class="basic"><?=$booking['info']['booking_master_id']?></a>
            </td>
            <td class="table-td collapse_tr" valign="top" style="text-align: center;"><?=$booking['info']['booking_name']?></td>
            <td class="table-td" valign="top" style="text-align: center;"><?=$booking['info']['booking_user']?></td>
            <td class="table-td" valign="top" style="text-align: center;"><?=$booking['info']['booking_co_person']?></td>
            <td class="table-td" valign="top" style="text-align: center;"><?=$booking['info']['booking_co_tel']?></td>
            <td class="table-td" valign="top" style="text-align: center;"><?=$booking['info']['booking_co_mail']?></td>
            <td class="table-td" valign="top" style="text-align: center;"><?=$booking['info']['price']?></td>
            <td class="table-td" valign="top" style="text-align: center;"><?=$booking['info']['booking_check_in']?></td>
            <td class="table-td" valign="top" style="text-align: center;"><?=$booking['info']['booking_check_out']?></td>
            <td class="table-td" valign="top" style="text-align: center;">
            <a target="_blank" href="index.php?m=booking_management&tab=booking_dbl_list&action=get_invoice&invoice_type=accommodation_dbl&booking_id=<?=$booking['info']['booking_master_id']?>">
                <img src="./images/icos16/a.png" width="16" height="16" border="0" align="middle" alt="Full Info"  title="განთავსების ინვოისი">
            </a>
            <a target="_blank" href="index.php?m=booking_management&tab=booking_dbl_list&action=get_invoice&invoice_type=services_dbl&booking_id=<?=$booking['info']['booking_master_id']?>">
                <img src="./images/icos16/s.png" width="16" height="16" border="0" align="middle" alt="Full Info"  title="სერვისების ინვოისი">
            </a>
              <a target="_blank" href="index.php?m=booking_management&tab=booking_dbl_list&action=get_invoice&invoice_type=full_dbl&booking_id=<?=$booking['info']['booking_master_id']?>">
                <img src="./images/icos16/f.png" width="16" height="16" border="0" align="middle" alt="Full Info"  title="სრული ინვოისი">
            </a>
            </td>
            <td class="table-td" valign="top" style="text-align: center;">
                 <a target="_blank" href="index.php?m=booking_management&tab=booking_dbl_list&action=view&booking_id=<?=$booking['info']['booking_master_id']?>">რედაქტურება</a>
            </td>
        </tr>
        <tr style="display: none;">
            <td colspan="11">
                <table border="1" style="width:100%;" cellpadding="0" cellspacing="0" class="table-table">
                    <tr>
                        <td class="table-th" height="20" valign="top" align="center">
                             <?=$TEXT['booking_list']['booking_id']?>
                        </td>
                        <td class="table-th" height="20" valign="top">
                             <?=$TEXT['booking_list']['guest']?>
                        </td>
                        <td class="table-th" height="20" valign="top">
                            <?=$TEXT['booking_list']['affiliate']?>
                        </td>
                        <td class="table-th" valign="top" style="border-bottom:1px solid #E5E6EE">
                            <?=$TEXT['booking_list']['room_id']?>
                        </td>
                        <td class="table-th" valign="top" style="border-bottom:1px solid #E5E6EE">
                            <?=$TEXT['booking_list']['floor']?>Floor
                        </td>

                        <td class="table-th" valign="top">
                            <?=$TEXT['booking_list']['check_in']?>
                        </td>
                        <td class="table-th" valign="top">
                            <?=$TEXT['booking_list']['check_out']?>
                        </td>
                        <td class="table-th" valign="top">
                            <?=$TEXT['booking_list']['filter_modal']['booking_method']?>
                        </td>
                        <td class="table-th" valign="top" nowrap>
                            <?=$TEXT['booking_list']['status']?>
                        </td>
                        <td class="table-th" valign="top" nowrap>
                            <?=$TEXT['booking_list']['food']?>
                        </td>
                        <td class="table-th" valign="top" nowrap>
                            <?=$TEXT['booking_list']['debt']?>
                        </td>
                        <td class="table-th" valign="top" style="width: 94px;" align="center">
                            <a  id="select-all-invoices" title="<?=$TEXT['booking_list']['select_all']?>" style="cursor:pointer; text-decoration:none;"><?=$TEXT['booking_list']['invoice']?></a>
                            <br>
                            <a  id="select-a-invoices" title="<?=$TEXT['booking_list']['accommodation_invoice']?>" style="cursor:pointer; text-decoration:none;">&nbsp;A</a>

                            <a  id="select-s-invoices" title="<?=$TEXT['booking_list']['services_invoice']?>" style="cursor:pointer; text-decoration:none;">&nbsp;&nbsp;&nbsp;&nbsp;S</a>
                        </td>
                        <td class="table-th" valign="top">
                            <?=$TEXT['booking_list']['action']?>
                        </td>
                    </tr>
                    <?php $k=0; foreach ($booking['array'] as $key => $value): ?>
                            <tr>

                            <td class="table-td" align="center">
                                <a href="#" title="<?=$value['dl_coment']?>" class="basic <?=($value['active']==0)?'cenceled':''?>"><?=$value['id'] ?></a>
                            </td>
                            <td class="table-td " style="padding-left:8px;">
                                <a href="#" title="<?=$value['guest_id_number']?>" class="guests-all-bookings basic" guest_id_number="<?=$value['guest_id_number']?>" ><?=$value['first_name']?> <?=$value['last_name']?></a>
                            </td>
                            <td class="table-td basic" style="padding-left:8px;">
                                <a href="#"  class="basic"><?=$TMPL_guests[$value['affiliate_id']]['first_name']?> <?=$TMPL_guests[$value['affiliate_id']]['last_name']?></a>
                            </td>
                            <td class="table-td basic" style="padding-left:8px;">
                                <a href="#"  class="basic"><?=$TMPL_rooms[$value['room_id']]['name']?></a>
                            </td>
                            <td class="table-td basic" style="padding-left:8px;">
                                <a href="#"  class="basic"><?=$TMPL_rooms[$value['room_id']]['floor']?></a>
                            </td>
                            <td class="table-td" style="padding-left:8px;">
                                <a href="#" title="" class="bookings-by-check-in basic" date="<?=date('Y-m-d',strtotime($value['check_in']))?>" >
                                    <?=date('Y-m-d',strtotime($value['check_in']))?>
                                </a>
                            </td>
                            <td class="table-td" style="padding-left:8px;">
                                <a href="#" title="" class="bookings-by-check-out basic" date="<?=date('Y-m-d',strtotime($value['check_out']))?>" >
                                    <?=date('Y-m-d',strtotime($value['check_out']))?>
                                </a>
                            </td>
                            <td class="table-td" style="padding-left:8px;" nowrap>
                                            <a href="#" title="" class="bookings-by-method basic" method="<?=$value['method']?>" >
                                                <?=$TEXT['booking_list']['filter_modal']['method'][$value['method']]?>
                                            </a>
                                        </td>
                            <td class="table-td" style="padding-left:8px;" nowrap>
                                <a href="#" title="" class="bookings-by-status basic" status_id="<?=$value['status_id']?>" >
                                    <?=$TMPL_booking_statuses[$value['status_id']]['title']?>
                                </a>
                            </td>
                            <td class="table-td" style="padding-left:8px;" nowrap>
                                <a href="#" title="" class="bookings-by-food basic" food_id="<?=$value['food_id']?>" >
                                    <?=$TMPL_food[$value['food_id']]['title']?>
                                </a>
                            </td>
                            <td class="table-td" style="padding-left:8px;" nowrap>
                            <?
                            $debt=(int)($value['accommodation_price']*100);
                            $debt+=(int)($value['services_price']*100);
                            $debt-=(int)($value['paid_amount']*100);
                            $debt-=(int)($value['services_paid_amount']*100);

                            $debt=$debt/100;
                            ?>
                                    <a href="#"  class="basic"><?=$debt?></a>
                            </td>
                            <td class="table-td" style="padding-right:8px;" nowrap align="right">
                            <?
                            if($TMPL_invoice_details[$value['id']]['a']==1){
                                $a='checked';
                            }else{
                                $a='';
                            }
                            if($TMPL_invoice_details[$value['id']]['s']==1){
                                $s='checked';
                            }else{
                                $s='';
                            }
                            ?>
                                <input class="a-invoice" type="checkbox" name="invoices[<?=$value['id']?>][a]" title="<?=$TEXT['booking_list']['accommodation_invoice']?>" <?=$a?>> <input class="s-invoice" type="checkbox" name="invoices[<?=$value['id']?>][s]" title="<?=$TEXT['booking_list']['services_invoice']?>" <?=$s?>>
                            </td>
                            <td class="table-td" align="center" valign="middle" nowrap>
                                <a href="<?=$_SERVER['PHP_SELF']?>?m=<?=$TMPL_plugin?>&tab=booking_list&action=view&booking_id=<?=$value['id']?>">
                                    <img src="./images/icos16/about.gif" width="16" height="16" border="0" align="middle" alt="Full Info"  title="<?=$TEXT['booking_list']['view_booking']?>">
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </td>
        </tr>
        <? $i++; }?>

    </table>
</form>


<div align="center" style=""><?=$TMPL_navbar?></div>
</br>
<form method="post" target="_blank" id="excel_download_form">
    <input type="hidden" name="action" value="get_excel">
    <input type="submit" value="<?=$TEXT['booking_list']['download']?>" class="download-excel" style="float:right; border:solid 0px;">
</form>




<script>
  $('.collapse_tr').click(function(){
    console.log(this);
    $(this).parent().nextUntil('tr.master_tr_class').slideToggle(500);
  });
</script>
<script type="text/javascript">
$(document).ready(function () {
    $( 'a.basic.cenceled' ).tooltip();

    $( "#in_start_date").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            var nextDay=new Date();
            nextDay.setTime(date.getTime() + (1000*60*60*24));
            $( "#in_end_date" ).datepicker( "option", "minDate",date );
            $( "#out_start_date" ).datepicker( "option", "minDate",nextDay );
            $( "#out_end_date" ).datepicker( "option", "minDate",nextDay );
        }
    });

    $( "#in_end_date").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            //date.setTime(date.getTime() - (1000*60*60*24));
            $("#in_start_date" ).datepicker( "option", "maxDate", date );
        }
    });

    $( "#out_start_date").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            //date.setTime(date.getTime() - (1000*60*60*24));
            $("#out_end_date" ).datepicker( "option", "minDate", date );
        }
    });

    $( "#out_end_date").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            $("#out_start_date" ).datepicker( "option", "maxDate", date );
        }
    });
    $("#today_checkin").click(function(){
        resetFilterForm();
        var today=new Date();
        $('#in_start_date').datepicker('setDate', today);
        $('#in_end_date').datepicker('setDate', today);
        $("#filter_form").submit();
    });

    $("#today_checkout").click(function(){
        resetFilterForm();
        var today=new Date();
        $('#out_start_date').datepicker('setDate', today);
        $('#out_end_date').datepicker('setDate', today);
        $("#filter_form").submit();
    });

    $(".guests-all-bookings").click(function () {
        $("#guest_id").val($(this).attr("guest_id_number"));
        $("#guest_name").val($(this).text());
        $("#filter_form").submit();
    });

    $(".bookings-by-check-in").click(function () {
        $("#in_start_date").val($(this).attr("date"));
        $("#in_end_date").val($(this).attr("date"));
        $("#filter_form").submit();
    });

    $(".bookings-by-check-out").click(function () {
        $("#out_start_date").val($(this).attr("date"));
        $("#out_end_date").val($(this).attr("date"));
        $("#filter_form").submit();
    });

    $(".bookings-by-status").click(function () {
        $("#status_id").val($(this).attr("status_id"));
        $("#filter_form").submit();
    });

    $(".bookings-by-method").click(function () {
        $("#method").val($(this).attr("method"));
        $("#filter_form").submit();
    });

    function resetFilterForm(){
        $("#guest_id").val('');
        $("#guest_name").val('');
        $("#guest_type").val('');
        $("#in_start_date").val('');
        $("#in_end_date").val('');
        $("#out_start_date").val('');
        $("#out_end_date").val('');
        $("#status_id").val('');
    }

    var selected_a_invoices=false;
    var selected_s_invoices=false;
    $("#select-all-invoices").click(function () {

        $(".a-invoice").each(function () {
            if(selected_a_invoices&&selected_s_invoices){
                $(this).prop('checked', false);
            }else{
                $(this).prop('checked', true);
            }
        });

        $(".s-invoice").each(function () {
            if(selected_a_invoices&&selected_s_invoices){
                $(this).attr('checked', false);
            }else{
                $(this).attr('checked', true);
            }
        });
        selected_a_invoices=!(selected_a_invoices&&selected_s_invoices);
        selected_s_invoices=selected_a_invoices;
    });

    $("#select-a-invoices").click(function () {
        $(".a-invoice").each(function () {
            if(selected_a_invoices){
                $(this).prop('checked', false);
            }else{
                $(this).prop('checked', true);
            }
        });
        selected_a_invoices=!selected_a_invoices;
    });

    $("#select-s-invoices").click(function () {
        $(".s-invoice").each(function () {
            if(selected_s_invoices){
                $(this).attr('checked', false);
            }else{
                $(this).attr('checked', true);
            }
        });
        selected_s_invoices=!selected_s_invoices;
    });

});
</script>
