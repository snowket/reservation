<? session_start();  ?>
<script src="./js/context_menu/jquery.ui.position.js" type="text/javascript"></script>
<script src="./js/context_menu/jquery.contextMenu.js" type="text/javascript"></script>
<link href="./js/context_menu/sass/jquery.contextMenu.css" rel="stylesheet" type="text/css" />

<script src="./js/notify.min.js" type="text/javascript"></script>
<script src="./js/notify-metro.js" type="text/javascript"></script>
<link href="./css/notify-metro.css" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.1/jquery.cookie.js" type="text/javascript"></script>
<script src="./js/sumoselect/jquery.sumoselect.min.js" type="text/javascript"></script>
<link href="./js/sumoselect/sumoselect.css" rel="stylesheet" type="text/css" />
<style>
    .ui-resizable-w {
        cursor: w-resize;
        width: 7px;
        left: 2px;
        top: 0;
        height: 100%;
    }
    #db_booking_tool{
        background: #3a82cc;
        display: none;
    }
    #db_booking_tool a{
        padding:0 10px;
        color:red;
    }

</style>


<form action="" method="get" id="form">
<input type="hidden" name="m" value="<?= $_GET['m'] ?>"/>
<input type="hidden" name="tab" value="<?= $_GET['tab'] ?>"/>

<div style="float:left; background:#FFF; border:solid #3A82CC 1px; width:540px;">
    <div style="padding:2px; background:#3A82CC; color:#FFF">
        <?= $TEXT['filter_modal']['title'] ?></b>
    </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
            <tr>
                <td >
                    <label for="block_id"><?= $TEXT['filter_modal']['select_block'] ?></label>
                </td>
                <td style="">
                    <label for="type_id"><?= $TEXT['filter_modal']['select_type'] ?></label>
                </td>
                <td style="">

                </td>
            </tr>
            <tr>
                <td style="">
                    <select multiple="multiple" id="block_id" name="block_id[]" class="formField1" style="width:200px;">
                        <?
                        $sumo_total_rooms=0;
                        for ($i = 0; $i < count($TMPL_blocks); $i++) {
                            $selected = in_array($TMPL_blocks[$i]['id'],$_GET['block_id']) ? 'selected' : '';
                            $style = ($TMPL_blocks[$i]['level'] == 1) ? 'style="font-weight:bold;"' : '';
                            $rooms_count=0;
                            foreach($TMPL_rooms_count[$TMPL_blocks[$i]['id']] AS $k=>$v){
                                $rooms_count+=$v;
                            }
                            $sumo_total_rooms+=$rooms_count;
                            ?>
                            <option value="<?=$TMPL_blocks[$i]['id']?>" <?=$style?> <?=$selected?> ><?=strip_tags(html_entity_decode($TMPL_blocks[$i]['title']))?> (<?=$rooms_count?>)</option>;
                        <? } ?>
                    </select>
                </td>

                <td>
                    <select multiple="multiple" id="type_id" name="type_id[]" class="formField1" style="width:200px;">
                        <?
                        for ($i = 0; $i < count($TMPL_categories); $i++) {
                            $selected = in_array($TMPL_categories[$i]['id'],$_GET['type_id']) ? 'selected' : '';

                            $style = ($TMPL_categories[$i]['level'] == 1) ? 'style="font-weight:bold;"' : '';
                            $sumo_rooms_count=0;
                            if($_GET['block_id']==0){
                                foreach($TMPL_rooms_count AS $TMPL_rooms_count_item){
                                    foreach($TMPL_rooms_count_item AS $k=>$v){
                                        if($k==$TMPL_categories[$i]['id']){
                                            $sumo_rooms_count+=(int)$v;
                                        }
                                    }
                                }
                            }else{
                                $sumo_rooms_count=0;
                                foreach($_GET['block_id'] as $t_block_id){
                                    $sumo_rooms_count+=(int)$TMPL_rooms_count[$t_block_id][$TMPL_categories[$i]['id']];
                                }

                            }
                            ?>
                            <option value="<?=$TMPL_categories[$i]['id']?>" <?=$style?> <?=$selected?>>
                                <?=strip_tags(html_entity_decode($TMPL_categories[$i]['title'])) ?>(<?=$sumo_rooms_count?>)
                            </option>
                        <? } ?>
                    </select>
                </td>
                <td>
                    <input class="formButton2" type="submit" style="cursor: pointer; height:28px" value="<?= $TEXT['filter_modal']['submit'] ?>">

                </td>
            </tr>
        </table>
</div>
<? if (count($TMPL_all_rooms) > 0) { ?>

<div style="margin-left: 4px; float:left; background:#FFF; border:solid #3A82CC 1px; width:517px;">
<div style="padding:2px; background:#3A82CC; color:#FFF">
    <b><?= $TEXT['filter_modal']['rooms_filter'] ?></b>
</div>
    <table>
        <tr>
            <td>
                <input type="text" id="period_start" name="period_start" value="<?=$_SESSION['booking']['filter']['start_date']?>" class="calendar-icon" autocomplete="off" placeholder="<?= $TEXT['filter_modal']['from'] ?>"/>
            </td>
            <td>
                <input type="text" id="period_end" name="period_end" value="<?=$_SESSION['booking']['filter']['end_date']?>" class="calendar-icon" autocomplete="off" placeholder="<?= $TEXT['filter_modal']['to'] ?>"/>
            </td>
            <td>

                <select id="rooms_status" name="rooms_status" class="formField1">
                    <option value="free" <?= ($_GET['rooms_status'] =='' || $_GET['rooms_status'] =='free') ? 'selected' : '' ?>><?= $TEXT['filter_modal']['free'] ?></option>
                    <option value="in_use" <?= ($_GET['rooms_status'] =='in_use') ? 'selected' : '' ?>><?= $TEXT['filter_modal']['in_use'] ?></option>
                    <option value="all" <?= ($_GET['rooms_status'] =='all') ? 'selected' : '' ?>><?= $TEXT['filter_modal']['all'] ?></option>
                </select>
            </td>
            <td>
                <input class="formButton2" type="submit" style="cursor: pointer" value="<?= $TEXT['filter_modal']['submit'] ?>">

            </td>
            <td>
              <button class="formButton2" type="submit" name="resetFilter" value="resetFilter">გასუფთავება</button>
            </td>
        </tr>
        </table>
</div>
</form>

<?if($_SESSION['pcms_user_group']<=2){?>
    <div id="delete_booking_modal" style="display: none" title="<?= $TEXT['del_booking_modal']['delete_booking'] ?>">
        <form  method="post" id="delete_booking_form" >
            <input type="hidden" name="action" value="del_booking" />
            <textarea name="dl_coment" id="" cols="60" rows="4" placeholder="კომენტარი"></textarea>
            <input type="hidden" name="rf_action">
            <input type="hidden" name="booking_id" value="0" />
            <div id="form_message">დარწმუნებული ხართ, რომ გინდათ წაშლა?</div>
            <table id="form_table">
                <tr>
                    <td><b><?= $TEXT['del_booking_modal']['paid_amount'] ?></b></td>
                    <td><input type="text" name="paid_amount" value="0" readonly></td>
                </tr>
                <tr>
                    <td><b><?= $TEXT['del_booking_modal']['refund_amount'] ?></b></td>
                    <td><input type="number" name="refund_amount" value="0" min="0" max="0" step="1"></td>
                </tr>
                <tr>
                    <td><b><?= $TEXT['del_booking_modal']['refund_type'] ?></b></td>
                    <td>
                        <select name="refund_method_id" id="refund_method_id" class="formField1" style="">
                            <? foreach($TMPL_payment_methods as $payment_method){
                                echo '<option value="'.$payment_method['id'].'" >'.$payment_method['title'].'</option>';
                                if($payment_method['id']==5){
                                    //balansi
                                }
                            }?>
                        </select>
                    </td>
                </tr>
            </table>
        </form>
    </div>
<?}?>

<div style="clear: both"></div>

<!-- -->
<? foreach ($TMPL_all_days as $day) {
    $year = date('Y', strtotime($day));
    $month = date('m', strtotime($day));
    $day = date('d', strtotime($day));
    $date_asoc_array[$year][$month][$day] = $day;
}
?>


    <!--CUSTOM BOOKING START-->
    <br>
    <form id="booking_form" action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <input type="hidden" id="b_guest_id" name="b_guest_id" value="0">
        <input type="hidden" id="b_responsive_guest_id" name="b_responsive_guest_id" value="0">
        <input type="hidden" id="b_affiliate_id" name="b_affiliate_id" value="0">
        <input type="hidden" id="booking_type_ens" name="booking_type_ens" value="single">

        <div style="background:#FFF; border:solid #3A82CC 1px;">
            <div style="padding:2px; background:#3A82CC; color:#FFF">
                <div style="float:left;">
                    <label><?= $TEXT['booking_modal']['title'] ?></label>
                </div>
                <div id="discount_display" style="padding-left:10px; float:left;"></div>
                <div style="float:right;">
                    <label for="general_price"><?= $TEXT['booking_modal']['price'] ?></label>
                    <input type="text" id="general_price" name="general_price" value="0" readonly style="width:80px;"/>
                    <label for="custom_price"><?= $TEXT['booking_modal']['custom_price'] ?></label>
                    <input type="text" id="custom_price" name="custom_price" value="0" style="width:80px;"/>
                </div>
                <div style="clear:both;"></div>
            </div>
            <table cellpadding="2" style="width:100%; border:1px solid #d4d4d4; border-collapse: collapse; border-spacing: 0px;">
                <tr>
                    <td style="width:33%; border:1px solid #d4d4d4;">
                        <table>
                            <tr>
                                <td><label for="check_in"><?= $TEXT['booking_modal']['check_in'] ?></label></td>
                                <td><input type="text" id="check_in" name="check_in" value="" class="calendar-icon" autocomplete="off" placeholder="<?= $TEXT['booking_modal']['from'] ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="check_out"><?= $TEXT['booking_modal']['check_out'] ?></label></td>
                                <td><input type="text" id="check_out" name="check_out" value="" class="calendar-icon" autocomplete="off" placeholder="<?= $TEXT['booking_modal']['to'] ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="room_id"><?= $TEXT['booking_modal']['room'] ?></label></td>
                                <td>
                                    <select name="room_id[]" multiple id="room_id" class="formField1" style="width:160px">
                                        <option value='0' selected><?= $TEXT['select_room'] ?></option>
                                        <? foreach ($TMPL_all_rooms as $room) {
                                            echo '<option value="' . $room['id'] . '" >' . $room['name'] . '</option>';
                                        } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="status_id"><?= $TEXT['booking_modal']['status'] ?></label></td>
                                <td>
                                    <select name="status_id" id="status_id" class="formField1" style="width:160px">
                                        <? foreach ($TMPL_all_statuses as $status) {
                                        if($status['id']==1 || $status['id']==3 ||$status['id']==4 ||$status['id']==5 || $status['id']==6 || $status['id']==7){
                                            echo '<option value="' . $status['id'] . '" >' . $status['title'] . '</option>';
                                        }
                                        } ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:33%; border:1px solid #d4d4d4;">
                        <table style="border-collapse: collapse;">
                            <tr>
                                <td><label for="adult_num"><?= $TEXT['booking_modal']['adults'] ?></label></td>
                                <td>
                                    <input type="number" id="adult_num" name="adult_num" min="1" step="1" value="1"
                                           style="width:160px"/>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="child_num"><?= $TEXT['booking_modal']['children'] ?></label></td>
                                <td>
                                    <input type="number" id="child_num" name="child_num" min="0" step="1" value="0"
                                           style="width:160px"/>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="daily_ind_discount"><?= $TEXT['booking_modal']['daily_discount'] ?></label></td>
                                <td valign="middle" style="vertical-align: middle">
                                    <input type="number" id="daily_ind_discount" name="daily_ind_discount" value="0" step="any" min="0"
                                           style="width:75px"/> /
                                    <input type="number" id="fixed_discount" name="fixed_discount" value="0" step="any" min="0"
                                                                                      style="width:76px"/>
                                </td>
                            </tr>
                            <tr>
                                <td><?= $TEXT['booking_modal']['guest'] ?></td>
                                <td>
                                    <div id="guest_selector" class="guest_modal_trigger" def="<?= $TEXT['booking_modal']['select_guest'] ?>" dest="guest" style="padding:2px; border:solid #868686 1px; cursor: pointer; text-align: center">
                                        <?= $TEXT['booking_modal']['select_guest'] ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="affiliate_selector"><?= $TEXT['booking_modal']['affiliate'] ?></label></td>
                                <td>
                                    <div id="affiliate_selector" class="guest_modal_trigger" def="<?= $TEXT['booking_modal']['select_affiliate'] ?>" dest="affiliate" style="padding:2px; border:solid #868686 1px; cursor: pointer; text-align: center">
                                        <?= $TEXT['booking_modal']['select_affiliate'] ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td align="left" style="width:33%; border:1px solid #d4d4d4;">
                        <table width="100%">
                            <tr>
                                <td><?= $TEXT['booking_modal']['responsive_guest'] ?></td>
                                <td>
                                    <div id="responsive_guest_selector" class="guest_modal_trigger" def="<?= $TEXT['booking_modal']['select_responsive_guest'] ?>" dest="responsive" style="padding:2px; border:solid #868686 1px; cursor: pointer; text-align: center">
                                        <?= $TEXT['booking_modal']['select_responsive_guest'] ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?= $TEXT['booking_modal']['info'] ?></td>
                                <td>
                                    <textarea id="booking_comment" class="formField3" style="width:100%"  name="booking_comment"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><?= $TEXT['booking_modal']['services'] ?></td>
                                <td align="left">
                                    <table id="services_table"  style="margin-bottom:4px; border-collapse: collapse; border-spacing: 1px; border:solid #868686 1px;">
                                    </table>
                                    <div id="services_modal_trigger"  style="padding:2px; border:solid #868686 1px;  cursor: pointer; text-align: center">
                                        <b><?= $TEXT['booking_modal']['add_service'] ?></b>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?= $TEXT['booking_modal']['food'] ?></td>
                                <td align="left">
                                    <select name="food_id" id="food_id" class="formField1" style="width:100%">
                                        <? foreach ($TMPL_all_food as $food) {
                                            echo '<option value="' . $food['id'] . '"  price="'.$food['price'].'">[' .$food['price']."] ".$food['title'] . '</option>';
                                        } ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                  <td align="left" colspan="1">
                      <br>
                      <a  href="index.php?m=booking_management&amp;tab=booking_dbl" class="formButton2 booking_button" style="cursor: pointer;padding: 3px;text-decoration:none;">ჯგუფური ჯავშანი</a>
                  </td>
                    <td align="right" colspan="2">
                        <br>
                        <input class="formButton2 booking_button" type="submit" value="<?= $TEXT['booking_modal']['submit_booking'] ?>" data-id='single' style="cursor: pointer">
                    </td>
                </tr>
            </table>
        </div>
    </form>
    <!--CUSTOM BOOKING END-->
     <div id="dbl_booking_modal" style="display:none" >

    </div>
    <!--SERVICE MODAL START-->
    <div id="booking_drag_modal" style="display:none" title="<?= $TEXT['booking_drag_modal']['title'] ?>">
        <?= $TEXT['booking_drag_modal']['message'] ?>
    </div>
    <div id="booking_resize_modal" style="display:none" title="<?= $TEXT['booking_resize_modal']['title'] ?>">
        <?= $TEXT['booking_resize_modal']['message'] ?>
    </div>

    <!--SERVICE MODAL START-->
    <div id="booking_service_modal" style="display:none" title="<?= $TEXT['service_modal']['title'] ?>">
        <table>
            <tr>
                <td><label for="service_type"><?= $TEXT['service_modal']['type'] ?></label></td>
                <td>
                    <select name="service_type" id="service_type" style="width:160px">
                        <option value="0"><?= $TEXT['service_modal']['select_service_type'] ?></option>
                        <? foreach ($TMPL_services_types as $services_type) {
                            if($services_type['id']==4||$services_type['id']==6||$services_type['id']==8)continue;
                            echo '<option value="' . $services_type['id'] . '" >' . $services_type['title'] . '</option>';
                        } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="service_selector"><?= $TEXT['service_modal']['service'] ?></label></td>
                <td>
                    <select name="service_selector" id="service_selector" style="width:160px">
                        <option value="0"><?= $TEXT['service_modal']['add_new_service'] ?></option>
                        <? foreach ($TMPL_all_extra_services as $extra_service) {
                            echo '<option price="' . $extra_service['price'] . '" title="' . $extra_service['title'] . '" value="' . $extra_service['id'] . '" >' . $extra_service['title'] . '</option>';
                        } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="service_title"><?= $TEXT['service_modal']['name'] ?></label></td>
                <td>
                    <input type="text" name="service_title" id="service_title" value="">
                </td>
            </tr>
            <tr>
                <td><label for="services_count"><?= $TEXT['service_modal']['services_count'] ?></label></td>
                <td>
                    <select id="services_count" name="services_count">
                        <?for($i=1; $i<11; $i++){?>
                            <option value="<?=$i?>"><?=$i?></option>
                        <?}?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="service_price"><?= $TEXT['service_modal']['price'] ?></label></td>
                <td><input type="number" id="service_price" name="service_price" min="0" value="0" step="any" style="width:160px"/></td>
            </tr>
        </table>
    </div>
    <!--SERVICE MODAL END-->
    <!--GUEST MODAL START-->
    <div id="booking_guest_modal" style="display: none; width:600px" title="<?= $TEXT['guest_modal']['title'] ?>">
        <div id="guest_modal_message"></div>
        <form name="guest_modal_form" id="guest_modal_form" enctype="multipart/form-data">
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
            <tr >
                <td  style="min-width: 130px">
                    <?= $TEXT['guest_modal']['guest_type'] ?>
                </td>
                <td  style="border:solid gray 1px;">
                    <input type="radio" name="guest_type" value="non-corporate" id="non_corporate" checked >
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
                    <Input id="tax_1" type = 'Radio' Name ='tax' value= '1'  checked><?= $TEXT['guest_modal']['tax_included'] ?>
                    <Input id="tax_0" type = 'Radio' Name ='tax' value= '0' ><?= $TEXT['guest_modal']['tax_free'] ?>
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
                <td><input type="text" name="first_name" id="first_name" value="" ></td>
            </tr>
            <tr  id="guest_lname_tr">
                <td><label for="last_name"><?= $TEXT['guest_modal']['guest_last_name'] ?></label></td>
                <td><input type="text" name="last_name" id="last_name" value=""></td>
            </tr>
              <tr  id="company_co_tr" style="display: none;">
                <td><label for="company_co">საკონტაქტო პირი</label></td>
                <td><input type="text" name="company_co" id="company_co" ></td>
            </tr>
            <tr  id="birth_day_tr">
                <td><label for="birth_day"><?= $TEXT['guest_modal']['birth_day'] ?></label></td>
                <td><input type="text" name="birth_day" id="birth_day" value="" placeholder="1987-12-31"></td>
            </tr>
            <tr  id="guest_ind_discount_tr">
                <td><label for="guest_ind_discount"><?= $TEXT['guest_modal']['ind_discount'] ?></label></td>
                <td><input type="number" name="guest_ind_discount" id="guest_ind_discount" value="0" min="0" max="100" step="1"></td>
            </tr>
            <tr>
                <td><a href="#" id="id_scan_link"><?= $TEXT['guest_modal']['id_scan'] ?></a></td>
                <td align="right"><input class="" type="file" style="width:100%" name="id_scan" ></td>
            </tr>
            <tr>
                <td><label for="country"><?= $TEXT['guest_modal']['country'] ?></label></td>
                <td>
                    <select name="country" id="country" style="width:160px" >
                        <? foreach ($TMPL_countries as $country) {
                            echo '<option value="' . $country['id'] . '" >' . $country[LANG] . '</option>';
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
                    <textarea name="comment" id="comment" style="width:100%"
                              class="formField3"><?= $TMPL_data['comment'] ?></textarea>
                </td>
            </tr>
        </table>
        </form>
    </div>
    <!--GUEST MODAL END-->

    <br>
    <!-- BOOKING TABLE START -->
    <div id="booking_board_cont" style="position:relative; width:100%; border:solid #3A82CC 1px;">
        <div style="padding:2px; background:#3A82CC; color:#FFF">
            <div style="float:left;">
                <?= $TEXT['booking_board']['title'] ?>
            </div>
            <form id="board-period-form" action="" method="post">
                <input type="hidden" name="action" value="change_board_period">
                <input type="hidden" id="days_before" name="days_before" value="<?=$_SESSION['days_before']?>">
                <input type="hidden" id="days_after"  name="days_after" value="<?=$_SESSION['days_after']?>">
            </form>
            <div class="div-table" style="float:right;">
                   <div class="div-tr">
                       <div val="-1y" class="div-td board-period-selector <?=($_SESSION['days_before']=='-1y')?'active-period':'before'?>">-Y</div>
                       <div val="-6m" class="div-td board-period-selector <?=($_SESSION['days_before']=='-6m')?'active-period':'before'?>">-6M</div>
                       <div val="-1m" class="div-td board-period-selector <?=($_SESSION['days_before']=='-1m')?'active-period':'before'?>">-M</div>
                       <div val="-1w" class="div-td board-period-selector <?=($_SESSION['days_before']=='-1w')?'active-period':'before'?>">-W</div>
                       <div class="div-td board-period-selector now">< now ></div>
                       <div val="+1w" class="div-td board-period-selector <?=($_SESSION['days_after']=='+1w')?'active-period':'after'?>">+W</div>
                       <div val="+1m" class="div-td board-period-selector <?=($_SESSION['days_after']=='+1m')?'active-period':'after'?>">+M</div>
                       <div val="+6m" class="div-td board-period-selector <?=($_SESSION['days_after']=='+6m')?'active-period':'after'?>">+6M</div>
                       <div val="+1y" class="div-td board-period-selector <?=($_SESSION['days_after']=='+1y')?'active-period':'after'?>">+Y</div>
                   </div>
            </div>
            <div style="clear: both"></div>
        </div>
        <div id="booking_board_left" style="width:160px; float:left;">
            <table width="100%" class="booking-table">
                <tr>
                    <td rowspan="4" >

                        <div style="overflow: hidden; ">
                        <img src="images/floor_geo.jpg">
                        </div>
                    </td>
                    <td><div style="height: 15px; overflow: hidden"><?= $TEXT['booking_board']['year'] ?></div></td>
                </tr>
                <tr>
                    <td><div style="height: 15px; overflow: hidden"><?= $TEXT['booking_board']['month'] ?></div></td>
                </tr>
                <tr>
                    <td><div style="height: 15px; overflow: hidden"><?= $TEXT['booking_board']['week_day'] ?></div></td>
                </tr>
                <tr>
                    <td><div style="height: 15px; overflow: hidden"><?= $TEXT['booking_board']['day'] ?></div></td>
                </tr>
                <?
                $tmp_floor=-1;

                foreach ($TMPL_all_rooms as $room) {
                    if($room['floor']!=$tmp_floor){
                        $tmp_floor=$room['floor'];
                        $starter='<td style="text-align:center; vertical-align:middle" rowspan="'.count($TMPL_rooms_by_floor[$room['floor']]).'">'.romanic_number($room['floor']).'</td>
                        <td>';
                    }else{
                        $starter='<td>';
                    }
                    ?>
                    <tr>
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
                        <?=$starter?>
                            <div status="<?=$TEXT['housekeeping_statuses'][$room['housekeeping_status']]?>" class="room_selector" style="height: 20px"  id="room_<?= $room['id'] ?>" floor="<?= $room['floor'] ?>" common_id="<?= $room['common_id'] ?>" max_adults="<?= $TMPL_capacity[$TMPL_rooms_manager_arr[$room['common_id']]['capacity_id']]['capacity'] ?>" max_children="<?= $TMPL_capacity[$TMPL_rooms_manager_arr[$room['common_id']]['capacity_id']]['childrens'] ?>" old_bgcolor="" description="<?=$room['description']?>" one_person_discount="<?= $TMPL_rooms_manager_arr[$room['common_id']]['one_person_discount'] ?>" pay_now_discount="<?= $TMPL_rooms_manager_arr[$room['common_id']]['pay_now_discount'] ?>" room_id="<?= $room['id'] ?>" room_name="<?= $room['name'] ?>">
                            <div style="border-radius: 50%; width:10px;height: 10px;  background-color: <?=$color?>; float:right; margin-top:3px; margin-right: 2px; border:solid #FFF 2px;"></div>
                            <?= $room['name'] ?>
                            </div>
                        </td>
                    </tr>
                <? } ?>
            </table>
        </div>
        <div id="booking_board" style="position:relative; width:1px; overflow-x:scroll; margin:0px 0px 0px 160px;" class="booking_board">

            <table  class="booking-table">
                <? foreach ($date_asoc_array as $k => $v) {
                    $days_in_year = 0;
                    foreach ($v as $kk => $vv) {
                        $months_tr .= '<td colspan="' . count($vv) . '">' . $TEXT['months'][date('m', mktime(0, 0, 0, $kk, 10))] . '</td>';
                        foreach ($vv as $kkk => $vvv) {
                            $date_tmp = $k . '-' . $kk . '-' . $kkk;
                            $dayofweek = date('w', strtotime($date_tmp));
                            $check_d=CURRENT_DATE;
                            if ($date_tmp < date('Y-m-d',strtotime('-1 day',strtotime(CURRENT_DATE)))) {
                                $color = '#A8A8A8';
                            } else if ($date_tmp == $check_d) {
                                $color = '#29A4F9';
                                $cd_start = '<div id="current_day">';
                                $cd_end = '</div>';
                            } else {
                                $color = '#FDE44E';
                                $cd_start = '<div id="day_'.$date_tmp.'">';
                                $cd_end = '</div>';
                            }
                            if($dayofweek==6||$dayofweek==0){
                                $week_day_color="#FFAFAF";
                            }else{
                                $week_day_color="#b3ffaf";
                            }
                            if($color == '#29A4F9'){
                                //current day
                                $week_day_color=$color;
                            }
                            $week_days_tr.= '<td bgcolor="' . $week_day_color . '"><div id="week_day_'.$date_tmp.'" class="week-day">'. $TEXT['week_days'][$dayofweek] .'</div></td>';
                            $days_tr .= '<td bgcolor="' . $color . '">' . $cd_start . '' . $kkk . '' . $cd_end . '</td>';
                            $days_in_year++;
                        }
                    }
                    $year_tr .= '<td colspan="' . $days_in_year . '">' . $k . '</td>';
                }
                ?>
                <tr>
                    <?= $year_tr ?>
                </tr>
                <tr>
                    <?= $months_tr ?>
                </tr>
                <tr>
                    <?= $week_days_tr ?>
                </tr>
                <tr>
                    <?= $days_tr ?>
                </tr>
                <? foreach ($TMPL_all_rooms as $room) { ?>
                    <tr>
                        <?  foreach ($TMPL_all_days as $day) {
                            if ($TMPL_common_prices_and_discounts[$room['common_id']][$day]['price'] == '') {
                                $css_class = 'no_price';
                            } else {

                                if ($day < date('Y-m-d',strtotime('-1 day',strtotime(CURRENT_DATE)))) {
                                    $css_class = 'past_date';
                                } else {
                                    $css_class = 'day_selector';
                                }
                            }
                        ?>
                        <?
                            $price = $TMPL_common_prices_and_discounts[$room['common_id']][$day]['price'];
                            $discount = $TMPL_common_prices_and_discounts[$room['common_id']][$day]['discount'];
                            $generated_price = $price - $price / 100 * $discount;

                        ?>
                        <? if ($TMPL_bookings[$room['id']][$day] == "") {
                            $status = "free";
                            $color = "#FFF";
                            if ($day < date('Y-m-d',strtotime('-1 day',strtotime(CURRENT_DATE))) || $price == '') {
                                $color = "#ebebeb";
                            } else {
                                $color = "#FFFFFF";
                            }
                        } else {
                            $status = $TMPL_bookings[$room['id']][$day];
                            if ($status == 'check_in') {

                            } else if ($status == 'check_out') {

                            } else if ($status == 'in_use') {
                                $css_class = 'in_use';
                            }

                            if ($day < CURRENT_DATE || $price == '') {
                                $color = "#ebebeb";
                            } else {
                                $color = "#FFFFFF";
                            }

                        } ?>
                            <?

                            if($room['for_local']==0 || $TMPL_rooms_restrictions[$room['id']][$day]['dnr_local']==1){
                                $color = "#ADFF2F";
                                $css_class = 'in_use dnr_local';
                                $status="in_use";
                            }

                            ?>
<td bgcolor="<?= $color ?>"><div class="btd <?= $css_class ?>" id="<?= $room['id'] ?>-<?= $day ?>" status="<?= $status ?>" date="<?= $day ?>"><?= $price == "" ? ' ' : $generated_price; ?></div></td>
                        <? } ?>

                    </tr>
                <? } ?>
            </table>

            <div id="day_tooltip" style="position: absolute; top:200px; left:240px; display: none">
                <div style="position: relative;" >
                    <div id="day_tooltip_content" style="position:absolute; margin-left: auto; bottom: 0px; padding:4px; background-color: #FFFFFF;  border: 2px solid #3a82cc;">

                    </div>
                    <div class="day-tooltip-arrow" >
                    </div>
                </div>
            </div>
        </div>
   <div style="clear:both"></div>
    </div>
    <!-- BOOKING TABLE END -->

<?if($_SESSION['pcms_user_group']<=2){?>
<form id="del_booking_form" method="post" onsubmit="return confirm('Are you sure?');">
    <input type="hidden" name="action" value="del_booking" />

    <input id="del_booking_id" type="hidden" name="del_booking_id" value="0" />
</form>
<?}?>


<div id="plate_tooltip" style="position: absolute; top:200px; left:240px; display: none">
<div style="position: relative; width:280px;" >
    <div style="position:absolute; margin-left: auto; bottom: 0px; width:280px;  padding:4px; background-color: #FFFFFF;  border: 2px solid #3a82cc;">
    <div id="db_booking_tool" class="hidden">
        ჯგუფური ჯავშნები :
    </div>
    <table>
    <tr>
        <td align="right">ID:</td>
        <td><div id="tt_id"></div></td>
    </tr>
    <tr>
        <td align="right"><?=$TEXT['plate_tooltip']['checkin_checkout']?></td>
        <td><div id="tt_checkin_checkout"></div></td>
    </tr>
    <tr>
        <td align="right"><?=$TEXT['plate_tooltip']['guest']?></td>
        <td><div id="tt_guest"></div></td>
    </tr>
    <tr>
        <td align="right"><?=$TEXT['plate_tooltip']['responsive_guest']?></td>
        <td><div id="tt_responsive_guest"></div></td>
    </tr>
    <tr>
        <td align="right"><?=$TEXT['plate_tooltip']['affiliate']?></td>
        <td><div id="tt_affiliate"></div></td>
    </tr>
    <tr>
        <td align="right"><?=$TEXT['plate_tooltip']['guests_count']?></td>
        <td><div id="tt_guests_count"></div></td>
    </tr>
    <tr>
        <td align="right"><?=$TEXT['plate_tooltip']['food']?></td>
        <td><div id="tt_food"></div></td>
    </tr>
    <tr>
        <td align="right"><?=$TEXT['plate_tooltip']['status']?></td>
        <td><div id="tt_status"></div></td>
    </tr>
    <tr>
        <td align="right"><?=$TEXT['plate_tooltip']['paid_amount']?></td>
        <td><div id="tt_paid_amount"></div></td>
    </tr>
    <tr>
        <td align="right"><?=$TEXT['plate_tooltip']['finances']?></td>
        <td><div id="tt_finances"></div></td>
    </tr>
    <tr>
        <td align="right"><?=$TEXT['plate_tooltip']['services']?></td>
        <td><div id="tt_services"></div></td>
    </tr>
    <tr>
        <td colspan="2"><div id="tt_comment"></div></td>
    </tr>
    </table>
    </div>

    <div class="tooltip-arrow" >
    </div>
</div>
</div>



<style>
#plate_tooltip table{
    color:#FFFFFF;
    background-color: #3a82cc;
    width:100%;
    border-collapse: collapse;
    border-spacing: 0;
}

#plate_tooltip td{
    border: 1px solid #b9b9b9;
    padding: 2px;;
}

.tooltip-arrow{
    position:absolute;
    bottom:-5px;
    margin-left: auto;
    margin-right: auto;
    left:0px;
    right:0px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 5px 5px 0 5px;
    border-color: #007bff transparent transparent transparent;
}
.arrow{
    border-top: 10px solid rgba(255, 255, 255, 0);
    border-bottom: 10px solid rgba(255, 255, 255, 0);
    border-right: 10px solid rgb(224, 24, 24);
    height: 0;
    background: #ffffff;
    position: absolute;
    right: 0;
    transform: rotate(180deg);
}
.day-tooltip-arrow{
    position:absolute;
    bottom:-5px;
    margin-left: auto;
    margin-right: auto;
    width: 0;
    height: 0;
    left:32px;
    border-style: solid;
    border-width: 5px 5px 0 5px;
    border-color: #007bff transparent transparent transparent;
}
</style>
<script type="text/javascript">
<?

   $counter=0;
   echo "var bookingPlates=[";
   foreach($TMPL_bookings2 AS $booking){
   if($counter!=0){ echo ","; }
        echo "{id:".$booking['id'].",guest_id:".$booking['guest_id'].", room_id:".$booking['room_id'].", status_id:".$booking['status_id'].", check_in:'".$booking['check_in']."', check_out:'".$booking['check_out']."', guest_name:'".$booking['guest_name']."', paid_amount:'".((float)$booking['paid_amount'] + (float)$booking['services_paid_amount'])."', parent_id:'".$booking['parent_id']."', child_id:'".$booking['child_id']."', booking_id:".$booking['id']."}";
        $counter++;
    }
    echo "];";

    $counter=0;
   echo "\nvar allServices=[";
   foreach($TMPL_all_services AS $service){
   if($counter!=0){ echo ","; }
        echo "{id:".$service['id'].", type_id:".$service['type_id'].", title:'".$service['title']."', price:".$service['price']."}";
        $counter++;
    }
    echo "];";
?>



function resetFilter(){
    $('#form').get(0).reset();
    $('#form').submit();
}

var notify_errors= {
    no_price: 'არჩეულ დღეზე ფასი არაა შეყვანილი',
    past_date: 'არჩეულია წარსული თარიღი',
    in_use: 'არჩეულია დაკავებული დღე',
    invalid_date: 'არჩეულია არასწორი პერიოდი',
    dnr_local:'ოთახი რემონტზეა',
    cant_deleted:'ჯავშანზე ირიცხება გადახდა, გთხოვთ გაანულოთ რათა მოხდეს ჯავშნის წაშლა'

};

var language_pack={
        room_selector:{
            max_adult:'<?=$TEXT['js']['max_adult']?>',
            max_child:'<?=$TEXT['js']['max_child']?>',
            opd:'<?=$TEXT['js']['one_person_discount']?>',
            description:'<?=$TEXT['js']['description']?>'
        },
        system_currency:'<?=$_CONF['system_currency']?>'

};

var managers={
<?
$count=0;
foreach($TMPL_rooms_manager_arr AS $manager){
    $count++;
   $max_adults=(int)$TMPL_capacity[$manager['capacity_id']]['capacity'];
   if($manager['lpd']!=''){
     $lpd=json_decode($manager['lpd'],true);
   }else{
     $lpd=array();
   }
   echo $manager['id'].':{';
   $old_discount=0;
   for($i = ($max_adults-1); $i>0;$i--){
        $discount=((int)$lpd[$i]==0)?$old_discount:(int)$lpd[$i];
        echo $i.':'.$discount;
        $old_discount=$discount;
        if($i!=1){
            echo ',';
        }
   }
    echo '}';
    if($count!=count($TMPL_rooms_manager_arr)){
        echo ',';
    }
}
?>
};


$('.btd').droppable({
    greedy: true,
    tolerance: 'touch',
    drop: function( event, ui ) {
        var room_id=ui.draggable.attr('room_id');
        var check_in=ui.draggable.attr('check_in');
        var check_out=ui.draggable.attr('check_out');


        var spl=$(this).attr('id').split("-");
        // Helper was dropped (first drag)
        movingDays.push($(this));
        if ($(this).hasClass('no_price')) {

            plateMovingErrors['no_price']=notify_errors.no_price;
            legalPlateMoving=false;
        }
        if ($(this).hasClass('past_date')) {
            plateMovingErrors['past_date']=notify_errors.past_date;
            legalPlateMoving=false;
        }
        if(spl[0]==room_id && $(this).attr('date')>=check_in && $(this).attr('date')<=check_out){

        }else{
            if ($(this).hasClass('in_use')) {
                plateMovingErrors.in_use=notify_errors.in_use;
                legalPlateMoving=false;
            }
        }

    }
});

function drawBookingPlate(booking) {
    var plate='<div id="booking_plate_' + booking['id'] + '" >' +
        '<div class="l-pl"></div>' +
        '<div class="c-pl"></div>' +
        '<div class="r-pl"></div>' +
        '<div class="arrow" ></div></div>';


    var l;
    var t;
    var w;

    if(!$('#' + booking['room_id'] + '-' + booking['check_in']).length && !$('#' + booking['room_id'] + '-' + booking['check_out']).length){
        console.log('not in check_in|check_out range')
        return;
    }
    $('#booking_board').append(plate);
    if($('#' + booking['room_id'] + '-' + booking['check_in']).length){
        l = $('#' + booking['room_id'] + '-' + booking['check_in']).position().left + $('#' + booking['room_id'] + '-' + booking['check_in']).width() / 2;
        t=$('#' + booking['room_id'] + '-' + booking['check_in']).position().top;

    }else{
        l=0;
        t=$('#' + booking['room_id'] + '-' + booking['check_out']).position().top;
    }

    if($('#' + booking['room_id'] + '-' + booking['check_out']).length){
        w = $('#' + booking['room_id'] + '-' + booking['check_out']).position().left - l + $('#' + booking['room_id'] + '-' + booking['check_out']).width() / 2;
    }else{
        w = $('#booking_board').width()-l-5;
    }
    var target = $('#booking_plate_' + booking['id']);
    target.attr('check_in', booking['check_in']);
    target.attr('check_out', booking['check_out']);
    target.attr('room_id', booking['room_id']);
    target.attr('paid_amount', booking['paid_amount']);
    target.attr('booking_id', booking['id']);
    target.attr('guest_id', booking['guest_id']);
    target.css('width', w);
    target.css('left', l);
    var bg_color='';
    var color='';
    if (booking['status_id'] == 1) {
        //წინასწარი ჯავშანი
        bg_color='#808000';
        color="#ffffff";
    } else if (booking['status_id'] == 2) {

        bg_color='#8826fc';
    } else if (booking['status_id'] == 3) {
        //დადასტურებულია გადასახდელია
        bg_color='#fde44e';
    } else if (booking['status_id'] == 4) {
        //დადასტურებულია გადახდილია
        bg_color='#fc26ec';
    } else if (booking['status_id'] == 5) {
        //სტუმარი განთავსდა
        bg_color='#29a4f9';
    } else if (booking['status_id'] == 6){
        //დასრულებულია გადასახდელია
        bg_color='#fc4426';
    } else if (booking['status_id'] == 7){
        //დასრულებულია
        bg_color='#2ffb10';
    }else{
        //console.log("undefined status id",booking['status_id']);
    }
    target.addClass("booking-plate");
    target.addClass("context-menu-one");
    //
    target.css('top', t);
    target.addClass("lr-spl");
    target.attr('title', booking['guest_name']);
    target.attr('status_id', booking['status_id']);
    target.find( ".c-pl" ).append(booking['guest_name']);
    target.find( ".c-pl" ).css({'background-color':bg_color,'color':color});
    //console.log(booking['parent_id']);
    if(booking['parent_id']>0){
        target.find( ".l-pl" ).css('background',"url('images/l_spl_"+booking['status_id']+".png') no-repeat left top");
    }else{
        target.find( ".l-pl" ).css('background-color',bg_color);
    }
    if(booking['child_id']>0){
        target.find( ".r-pl" ).css('background',"url('images/r_spl_"+booking['status_id']+".png') no-repeat left top");
    }else{
        target.find( ".r-pl" ).css('background-color',bg_color);
    }
    target.find( ".c-pl" ).css('width',w-10);
    target.find( ".c-pl" ).addClass('caps');
    target.click(function(){
        info_toggle(this);
    });
    target.dblclick(function () {
       window.location = 'index.php?m=booking_management&tab=booking_list&action=view&booking_id=' + booking['id'];
       // return false;
    });
    if('<?=CURRENT_DATE?>'<=booking['check_in']) {
        target.draggable({
            delay: 0,
            //cursor: "crosshair",
            containment: [$('#booking_board').offset().left, $('#booking_board').offset().top + 80, $('#booking_board').offset().left + $('#booking_board').width(), $('#booking_board').offset().top + $('#booking_board').height() - 40],
            revertDuration: 100,
            revert: function () {
                return !legalPlateMoving;
            },
            start: function () {
                var oldPosition={'left':$(this).css('left'),'top':$(this).css('top')};
                $(this).data('oldPosition',oldPosition);
                movingPlate=$(this);
                movingDays = [];
                legalPlateMoving = true;
                plateMovingErrors = [];
            },
            drag: function () {
                //console.log('dragging');
            },
            stop: function () {
                // $(this).draggable('option','revert','invalid');
                if(legalPlateMoving){
                    booking_drag_confirm.dialog("open");
                    var $target=booking_drag_confirm.dialog().parent();
                    $target.css('top',(window.innerHeight-$target.height())/2);
                    $target.css('left',(window.innerWidth-$target.width())/2);
                    $target.css('position','fixed');
                }else{

                    for (var plateMovingError in plateMovingErrors) {
                        if( plateMovingErrors.hasOwnProperty(plateMovingError) ) {
                            $.notify({title: 'ERROR', text:plateMovingErrors[plateMovingError]}, {className:'error',style: 'metro'});
                        }

                    }
                }
            },
            grid: [33, 21]
        });
    }
    if('<?=CURRENT_DATE?>'<=booking['check_out']) {
        target.resizable({
            grid: 33,
            handles: 'e, w',
            start: function (event, ui) {
                legalPlateResizing=true;
                plateResizingErrors=[];
                resizingPlate=$(this);
                var oldPosition={'left':$(this).css('left'),'top':$(this).css('top'),'width':$(this).css('width')};

                $(this).data('oldPosition',oldPosition);
            },
            resize: function(event, ui){
                //console.log($(this).css('width'));
                $(this).css('top',$(this).data('oldPosition').top);
                $(this).find('.c-pl').css('width',$(this).width()-10);
                $(this).css('height',20);
            },
            stop: function (event, ui) {
                resizing_new_checkin=$(this).attr('check_in');
                resizing_new_checkout=$(this).attr('check_out');
                var difdays=(parseInt($(this).css('width'))-parseInt($(this).data('oldPosition').width))/33;
                if($(this).data('oldPosition').left==$(this).css('left')&&$(this).data('oldPosition').width!=$(this).css('width')){                               var old_checkout=new Date($(this).attr('check_out'));
                    var new_checkout=new Date(old_checkout.getFullYear(), old_checkout.getMonth(), old_checkout.getDate()+difdays);
                    resizing_new_checkout=dateToString(new_checkout);
                    if(difdays>0) {
                        //gazrda marjvnidan
                        var currentDate = old_checkout;
                        var counter = 0;
                        while (dateToString(currentDate) <= dateToString(new_checkout)) {
                            var dateString = dateToString(currentDate);
                            var day = $("#" + $(this).attr('room_id') + "-" + dateString);
                            if (day.attr('status') == 'in_use') {
                                plateResizingErrors.in_use = notify_errors.in_use;
                                legalPlateResizing = false;
                            }
                            if (day.hasClass('no_price')) {
                                plateResizingErrors.no_price = notify_errors.no_price;
                                legalPlateResizing = false;
                            }
                            currentDate.setDate(currentDate.getDate() + 1);
                            counter++;
                        }
                    }else{
                        //shemcireba marjvnidan
                        if ('<?=CURRENT_DATE?>'>dateToString(new_checkout)) {
                            plateResizingErrors.past_date = notify_errors.past_date;
                            legalPlateResizing = false;
                        }
                        if (dateToString(new_checkout)<=$(this).attr('check_in')) {
                            plateResizingErrors.invalid_date = notify_errors.invalid_date;
                            legalPlateResizing = false;
                        }
                    }
                    //console.log('right resize',old_checkout,new_checkout);
                }else if($(this).data('oldPosition').left!=$(this).css('left')&&$(this).data('oldPosition').width!=$(this).css('width')){
                    var old_checkin=new Date($(this).attr('check_in'));
                    var new_checkin=new Date(old_checkin.getFullYear(), old_checkin.getMonth(), old_checkin.getDate()-difdays);
                    resizing_new_checkin=dateToString(new_checkin);
                    if(difdays>0) {
                        //momateba marcxnidan
                        var currentDate = new_checkin;
                        var counter = 0;
                        while (dateToString(currentDate) < dateToString(old_checkin)) {
                            var dateString = dateToString(currentDate);
                            var day = $("#" + $(this).attr('room_id') + "-" + dateString);
                            if (day.attr('status') == 'check_in' || day.attr('status') == 'in_use') {

                                plateResizingErrors.in_use = notify_errors.in_use;
                                legalPlateResizing = false;
                            }
                            if (day.hasClass('no_price')) {
                                plateResizingErrors.no_price = notify_errors.no_price;
                                legalPlateResizing = false;
                            }
                            if ('<?=CURRENT_DATE?>'>dateToString(new_checkin)) {
                                plateResizingErrors.past_date = notify_errors.past_date;
                                legalPlateResizing = false;
                            }
                            currentDate.setDate(currentDate.getDate() + 1);
                            counter++;
                        }
                    }else{
                        //dakleba marcxnidan
                        if (dateToString(new_checkin)>=$(this).attr('check_out')) {
                            plateResizingErrors.invalid_date = notify_errors.invalid_date;
                            legalPlateResizing = false;
                        }

                    }
                    //console.log('left resize',difdays+' days');
                }
                if(legalPlateResizing){
                    booking_resize_confirm.dialog("open");
                }else{
                    for (var plateResizingError in plateResizingErrors) {
                        if( plateResizingErrors.hasOwnProperty(plateResizingError) ) {
                            $.notify({title: 'ERROR', text:plateResizingErrors[plateResizingError]},{className:'error',style: 'metro'});
                        }
                    }
                    $(this).css('left',$(this).data('oldPosition').left);
                    $(this).css('width',$(this).data('oldPosition').width);
                    $(this).find('.c-pl').css('width',$(this).width()-10);
                }
            }
        });
    }
}

function info_toggle(elem){
        var thisel=elem;
        var booking_id=$(thisel).attr('booking_id');
            if(typeof booking_id == typeof undefined || booking_id == false){
                return;
            }
           var offset=$(thisel).offset();
           $('#plate_tooltip').css('top',offset.top-6);
           $('#plate_tooltip').css('left',offset.left-$('#plate_tooltip').width()/2+$(thisel).width()/2);
           $('#plate_tooltip').toggle();
           $('#db_booking_tool a').remove();
            $('#db_booking_tool').css('display','none');
           var request = $.ajax({
                       url: "index_ajax.php?cmd=get_booking_tooltip",
                       method: "POST",
                       data: {
                           booking_id: $(thisel).attr('booking_id')
                       },
                       dataType: "json"
                   });

           request.done(function (msg) {
            console.log(msg);
            if(msg.dbl_res==1){
                $('#db_booking_tool').css('display','block');
                $.each(msg.dbl_res_id_ens,function(key , value){
                        $('#db_booking_tool').append('<a href="index.php?m=booking_management&tab=booking_list&action=view&booking_id='+value.id+'" class="tooltip_a">'+value.id+'</a>');
                });
            }
               $('#tt_id').text(msg.id);
               $('#tt_guest').html(msg.guest.name);
               $('#tt_checkin_checkout').html(msg.check_in+' / '+msg.check_out);
               $('#tt_affiliate').html(msg.affiliate.name);
               $('#tt_guests_count').html(msg.adult_num+'/'+msg.child_num);
               $('#tt_paid_amount').html(msg.paid_amount+' '+language_pack.system_currency);
               $('#tt_responsive_guest').html(msg.responsive_guest.name);
               $('#tt_status').html(msg.status.title);
               $('#tt_food').html(msg.food);
               //$('#tt_finances').html(msg.price+'-'+msg.paid_amount+'='+(msg.price-msg.paid_amount).toFixed(2));
               $('#tt_finances').html('<b>'+(msg.price-msg.paid_amount).toFixed(2)+' '+language_pack.system_currency+'</b>');
               var services='';
               for(var i=0; i<msg.services.length;i++){
                services+=msg.services[i].service_title+'<br>';
               }
               $('#tt_services').html(services);

               $('#tt_comment').html(msg.comment);
           });

           request.fail(function (jqXHR, textStatus) {
               //console.log('error');
           });


    }


var legalPlateMoving=true;
var plateMovingErrors=[];
var movingDays=[];
var movingPlate;

var legalPlateResizing=true;
var plateResizingErrors=[];
var resizingPlate;
var resizing_new_checkin;
var resizing_new_checkout;



function confirmBookingDrag(booking_plate,recalculate_price){
    //console.log("confirmBookingDrag("+booking_plate.attr('id')+", "+recalculate_price+");");
    if (legalPlateMoving) {
        var splitedID = booking_plate.attr('id').split("_");
        var new_checkin=movingDays[0].attr('date');
        var new_checkout=movingDays[0].attr('date');
        var splID=movingDays[0].attr('id').split("-");
        var new_roomid=splID[0];

        for(var i=0;i<movingDays.length;i++){
            if(new_checkin>movingDays[i].attr('date')){
                new_checkin=movingDays[i].attr('date');
            }
            if(new_checkout<movingDays[i].attr('date')){
                new_checkout=movingDays[i].attr('date');
            }
        }
        var request = $.ajax({
            url: "index_ajax.php?cmd=drag_booking",
            method: "POST",
            data: {
                booking_id: splitedID[2],
                check_in: new_checkin,
                check_out:new_checkout,
                room_id:new_roomid,
                recalculate_price:recalculate_price
            },
            dataType: "json"
        });

        request.done(function (msg) {
            var currentDate = new Date(booking_plate.attr('check_in'));
            var end = new Date(booking_plate.attr('check_out'));
            var counter = 0;
            while (currentDate <= end) {
                var dateString = dateToString(currentDate);
                var day = $("#" + booking_plate.attr('room_id') + "-" + dateString);
                if (day.attr('status') == 'check_in') {
                    if(counter==0) {
                        var pervDay=new Date();
                        pervDay.setDate(currentDate.getDate() - 1);
                        if ($("#" + booking_plate.attr('room_id') + "-" + dateToString(pervDay)).attr('status') != 'in_use') {
                            day.parent().attr('bgcolor', '#FFF');
                            day.removeClass('in_use');
                            day.addClass('day_selector');
                            day.attr('status', 'free')
                        }else{
                            day.attr('status', 'check_out')
                        }
                    }
                }else {
                    day.parent().attr('bgcolor', '#FFF');
                    day.removeClass('in_use');
                    day.addClass('day_selector');
                    day.attr('status', 'free')
                }

                currentDate.setDate(currentDate.getDate() + 1);
                counter++;
            }
            //axlebi
            for (var i = 0; i < movingDays.length; i++) {
                if (i == 0) {
                    var dsplit = movingDays[i].attr('id').split("-");
                    booking_plate.attr('room_id', dsplit[0]);
                    booking_plate.attr('check_in', movingDays[i].attr('date'));
                    movingDays[i].attr('status', 'check_in');
                } else if (i == (movingDays.length - 1)) {
                    booking_plate.attr('check_out', movingDays[i].attr('date'));
                    if (movingDays[i].attr('status') != 'check_in') {
                        movingDays[i].attr('status', 'check_out');
                    }
                } else {
                    movingDays[i].attr('status', 'in_use');
                }
                movingDays[i].parent().attr('bgcolor', '#FFAFAF');
            }
            $.notify({title: 'SUCCESS', text:msg.success},{className:'success',style: 'metro'});
        });

        request.fail(function (jqXHR, textStatus) {
            $.notify({title: 'ERROR', text: "Request failed: " + textStatus},{className:'error',style: 'metro'});
        });

    } else {
        //console.log(plateMovingErrors);

    }
}


function confirmBookingResize(booking_plate,recalculate_price){
    //console.log("confirmBookingResize("+booking_plate.attr('id')+", "+recalculate_price+");");
    if (legalPlateMoving) {
        var splitedID = booking_plate.attr('id').split("_");
        var new_checkin=resizing_new_checkin;
        var new_checkout=resizing_new_checkout
        var request = $.ajax({
            url: "index_ajax.php?cmd=resize_booking",
            method: "POST",
            data: {
                booking_id: splitedID[2],
                check_in: new_checkin,
                check_out:new_checkout,
                recalculate_price:recalculate_price
            },
            dataType: "json"
        });

        request.done(function (msg) {
            var currentDate = new Date(booking_plate.attr('check_in'));
            var end = new Date(booking_plate.attr('check_out'));
            var counter = 0;
            while (currentDate <= end) {
                var dateString = dateToString(currentDate);
                var day = $("#" + booking_plate.attr('room_id') + "-" + dateString);
                if (day.attr('status') == 'check_in') {
                    if(counter==0) {
                        var pervDay=new Date();
                        pervDay.setDate(currentDate.getDate() - 1);
                        if ($("#" + booking_plate.attr('room_id') + "-" + dateToString(pervDay)).attr('status') != 'in_use') {
                            day.parent().attr('bgcolor', '#FFF');
                            day.removeClass('in_use');
                            day.addClass('day_selector');
                            day.attr('status', 'free')
                        }else{
                            day.attr('status', 'check_out')
                        }
                    }
                }else {
                    day.parent().attr('bgcolor', '#FFF');
                    day.removeClass('in_use');
                    day.addClass('day_selector');
                    day.attr('status', 'free')
                }
                currentDate.setDate(currentDate.getDate() + 1);
                counter++;
            }
            //axlebi

            booking_plate.attr('check_in',new_checkin);
            booking_plate.attr('check_out', new_checkout);

             currentDate = new Date(booking_plate.attr('check_in'));
             end = new Date(booking_plate.attr('check_out'));
             counter = 0;
            while (currentDate <= end) {
                var dateString = dateToString(currentDate);
                var day = $("#" + booking_plate.attr('room_id') + "-" + dateString);
                    if(counter==0) {
                        day.attr('status', 'check_in');
                        day.parent().attr('bgcolor', '#FFAFAF');
                    }else{
                         if(currentDate.getTime() === end.getTime()) {
                            if(day.attr('status')!='check_in'){
                                day.attr('status', 'check_out');
                                day.parent().attr('bgcolor', '#FFAFAF');
                            }
                         }else{
                            day.attr('status', 'in_use');
                            day.parent().attr('bgcolor', '#FFAFAF');
                         }
                    }
                currentDate.setDate(currentDate.getDate() + 1);
                counter++;
            }
        });

        request.fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });

    } else {
        booking_plate.css('left',booking_plate.data('oldPosition').left);
        booking_plate.css('width',booking_plate.data('oldPosition').width);
    }
}

function dateToString(date){
   return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+ '-' + ("0" + date.getDate()).slice(-2);

}

$(document).ready(function () {
    console.log("document ready");


    $('#block_id').SumoSelect({
        selectAll: true,
        captionFormat: 'არჩეულია {0} შენობა',
        captionFormatAllSelected:'არჩეულია {0} ყველა!',
        locale: ['OK', 'Cancel', '<?= $TEXT['all']?> (<?=$sumo_total_rooms?>)'],
        triggerChangeCombined: false,
        forceCustomRendering: true
    });

    $('#type_id').SumoSelect({
        selectAll: true,
        captionFormat: 'არჩეულია {0} ტიპი',
        captionFormatAllSelected:'არჩეულია {0} ყველა!',
        locale: ['OK', 'Cancel', '<?= $TEXT['all']?>'],
        triggerChangeCombined: false,
        forceCustomRendering: true
    });
    <?if(empty($_GET['block_id']) ||(count($_GET['block_id'])==1 && $_GET['block_id'][0]==0)){?>
    $('#block_id')[0].sumo.selectAll();
    <?}?>
    <?if(empty($_GET['type_id']) ||(count($_GET['type_id'])==1 && $_GET['type_id'][0]==0)){?>
    $('#type_id')[0].sumo.selectAll();
    <?}?>


<?="var other_service='".$TEXT['service_modal']['add_new_service']."';"?>
    $('#service_type').on("change", function (e) {

        if ($(this).val() == 0) {
            $("#service_title").val('');
            $("#service_title").attr("readonly", false);
            $("#service_price").val(0);
            $("#service_price").attr("readonly", false);
        } else {
            $("#service_selector").html('');
            $('#service_selector').append('<option value="0" price="0" >'+other_service+'</option>');
            for(var i=0; i<allServices.length;i++){
                if($(this).val()==allServices[i]['type_id']) {
                    $('#service_selector').append('<option value="'+allServices[i]['id']+'" price="'+allServices[i]["price"]+'" >'+allServices[i]["title"]+'</option>');
                }
            }
        }
        $("#service_title").val('');
        $("#service_title").attr("readonly", false);
        $("#service_price").val(0);
        $("#service_price").attr("readonly", false);
    });

    $('#service_selector').on("change", function (e) {

        if ($(this).val() == 0) {
            $("#service_title").val('');
            $("#service_title").attr("readonly", false);
            $("#service_price").val(0);
            $("#service_price").attr("readonly", false);
        } else {
            $("#service_title").val($('option:selected', this).text());
            $("#service_title").attr("readonly", true);
            $("#service_price").val($('option:selected', this).attr('price'));
            $("#service_price").attr("readonly", true);

        }
    });

    function drowAddedServices() {
        $("#services_table td").parent().remove();
        for (var i = 0; i < addedServices.length; ++i) {
            var service_html = '';
            service_html = '<tr><td><input id="service[' + i + '][id]" type="hidden" value="' + addedServices[i]["id"] + '"  name="service[' + i + '][id]">';
            service_html += '<input id="service[' + i + '][type_id]" type="hidden" value="' + addedServices[i]["type_id"] + '" name="service[' + i + '][type_id]">';
            service_html += '<input id="service[' + i + '][title]" type="text" value="' + addedServices[i]["title"] + '" readonly="" name="service[' + i + '][title]"></td>';
            service_html += '<td><input id="service[' + i + '][price]" type="text" value="' + addedServices[i]["price"] + '" style="width:60px" readonly="" name="service[' + i + '][price]"></td>';
            service_html += '<td><img class="service_remove_button" array_id="' + i + '" width="16" height="16" border="0" align="middle" src="./images/icos16/delete.gif" alt="delete"></td></tr>';
            $('#services_table').append(service_html);
        }
    }

    $('.service_remove_button').live('click', function () {
        var id = $(this).attr('array_id');
        var tmp = [];
        for (var i = 0; i < addedServices.length; ++i) {
            if (i != id) {
                tmp.push(addedServices[i]);

            }
        }
        addedServices = tmp;
        drowAddedServices();
        updatePrice();
    });
    $( "#adult_num, #child_num").change(function() {
        updatePrice();
    });
    $( "#daily_ind_discount").change(function() {
        if(selectedRooms.length>0 && selectedDaysArray.length>1){
            $( "#fixed_discount").val($(this).val()*(selectedDaysArray.length-1));
        }else{
            $( "#fixed_discount").val(0);
            $(this).val(0);
        }
        updatePrice();
    });

    $( "#fixed_discount").change(function() {
        if(selectedRooms.length>0 && selectedDaysArray.length>1){
            var pd=$(this).val()/(selectedDaysArray.length-1);
           $( "#daily_ind_discount").val(pd.toFixed(2));
        }else{
            $( "#daily_ind_discount").val(0);
            $(this).val(0);
        }
        updatePrice();
    });


    //var guest_ind_discount=0;
    //var guest_tax_free_discount=0;
    var guest_net_price = 0;
    var guest_total_price=0;
    var affiliate_net_price=0;

    function updatePrice() {

       // if(selectedRooms.length==0){return;}


        var guest_ind_discount= 0;
        var guest_tax_free_discount=0;
        var affiliate_discount=0;
        var affiliate_tax=1;
        if(!$.isEmptyObject(guest_obj)){
            guest_ind_discount= guest_obj['ind_discount'];
            guest_tax_free_discount=(guest_obj['tax']=='0')?18:0;
        }
        if(!$.isEmptyObject(affiliate_obj)){
            affiliate_discount= affiliate_obj['ind_discount'];
        }
        guest_net_price = 0;
        guest_total_price=0;//net price+services
        affiliate_net_price=0;
        var one_person_discount=0;

        var daily_discount=parseFloat($('#daily_ind_discount').val());
        //var affiliate_tax_discount=(affiliate_tax==0)?18:0;
        //console.log('log','food_id:'+parseFloat($('#food_id option:selected').attr('price'))+ 'adult_num:'+$('#adult_num').val() +'child_num:'+ $('#child_num').val());
        for (var j = 0; j < selectedRooms.length; j++) {
            var selected_room_id=selectedRooms[j];
            if(parseInt($("#adult_num").val())>=0){
                //one_person_discount=parseInt($('#room_'+selected_room_id).attr('one_person_discount'));
                one_person_discount=parseInt(managers[$('#room_'+selected_room_id).attr('common_id')][$("#adult_num").val()]);
                if(isNaN(one_person_discount)){
                    one_person_discount=0;
                }
            }
            //console.log(one_person_discount);
            for (var i = 0; i < selectedDaysArray.length - 1; i++) {
                var acc = parseFloat($('#'+ selected_room_id+'-'+ selectedDaysArray[i]).text());
                var food_price = parseFloat($('#food_id option:selected').attr('price'))*(parseFloat($('#adult_num').val())+parseFloat($('#child_num').val()));
                var guest_daily_net_price = parseFloat(calculateNetPrice(acc, food_price, one_person_discount, 0, guest_ind_discount, daily_discount, guest_tax_free_discount));
                guest_net_price += guest_daily_net_price;
                var affiliate_daily_net_price = parseFloat(calculateNetPrice(acc, food_price, one_person_discount, 0, affiliate_discount, 0, 0));
                affiliate_net_price += affiliate_daily_net_price;
            }
        }
        affiliate_net_price = affiliate_net_price.toFixed(2);
        guest_net_price = guest_net_price.toFixed(2);

        var cashBack=(guest_net_price-affiliate_net_price).toFixed(2);
        if(cashBack<0){
            cashBack=0;
        }
        guest_total_price=guest_net_price;
        for (var i = 0; i < addedServices.length; i++) {
            var type_id=parseInt(addedServices[i]['type_id']);
            if(type_id==2||type_id==3){
                guest_total_price =parseFloat(guest_total_price)+ parseFloat(addedServices[i]['price'])*(selectedDaysArray.length - 1);
            }else{
                guest_total_price =parseFloat(guest_total_price)+ parseFloat(addedServices[i]['price']);
            }
        }
        $("#discount_display").text('guest_net('+guest_net_price+')-aff_net('+affiliate_net_price+')=cashBack('+cashBack+')');
        $("#general_price").val(guest_total_price);
    }

    function calculateNetPrice(a,food_price,c,d,f,e,g){
        //console.log('calculateNetPrice('+a+','+food_price+','+c+','+d+','+f+','+e+','+g+');');
        var daily_price=a;
        //daily_price=daily_price-daily_price/100*b;
        daily_price=daily_price-daily_price/100*c;
        daily_price=daily_price-daily_price/100*d;
        daily_price=daily_price-daily_price/100*f;
        daily_price=daily_price-e;
        daily_price=daily_price-daily_price/(100+g)*g;
        daily_price=daily_price+food_price;
        return daily_price.toFixed(2);
    }

    $('#food_id').on("change", function (e) {
        updatePrice();
    });

    var serialized_form_obj={};
    var guest_obj={};
    var affiliate_obj={};
    var responsive_guest_obj={};


    function fillGuestModal (g_id) {
        //console.log('fillGuestModal('+g_id+');');
        if (g_id == 0) {
            $('#guest_id').val(0);
            $("#booking_guest_modal :input").attr("disabled", false);
            $("#id_number").val('');
            $("#first_name").val('');
            $("#last_name").val('');
            $("#birth_day").val('');
            $("#company_co").val('');
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
            $('#guest_ind_discount').val(0);
            $('#guest_ind_discount_tr').val(0);
            return;
        }else{

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
            $("#company_co").val(msg.company_co);
            $("#guest_ind_discount").val(msg.ind_discount);
            $("#id_scan_link").attr('href', '../uploads_script/guests/' + msg.id_scan);
            $("#id_scan_link").attr('target', '_blank');
            $("#country").val(msg.country);
            $("#address").val(msg.address);
            $("#telephone").val(msg.telephone);
            $("#email").val(msg.email);
            $("#comment").val(msg.comment);
            //$("#comment").attr('readonly', 'readonly');
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
                //$('#guest_ind_discount_tr').show();
                $('#guest_lname_tr').hide();
                  $('#birth_day_tr').hide();
            }else{
                $('input:radio[name=guest_type]')[0].checked = true;
                //$('#guest_ind_discount_tr').hide();
                $('label[for="id_number"]').text($('label[for="id_number"]').attr('ncorp'));
                $('label[for="first_name"]').text($('label[for="first_name"]').attr('ncorp'));
                $('#guest_lname_tr').show();
                $("#company_co_tr").hide();
                $('#birth_day_tr').show();
            }
            if(msg.tax==1){
                $("#tax_1").prop('checked', true);
                $("#tax_0").prop('checked', false);
            }else{
                $("#tax_1").prop('checked', false);
                $("#tax_0").prop('checked', true);
            }

            //$("#booking_guest_modal :input").attr("disabled", true);
            $("#guest_id").attr("disabled", false);
            serialized_form_obj=$('#guest_modal_form').serialize();
            last_fetched_guest_obj=msg;
        });

        request.fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });
    };
    var ctrlMode = false; // if true the ctrl key is down
    ///this works
    $(document).keydown(function(e){
        if(e.ctrlKey){
            ctrlMode = true;
        };
    });
    $(document).keyup(function(e){
        ctrlMode = false;
    });

    var last_fetched_guest_obj={};

    var from = {
        room_id: 0,
        room_name: '',
        date: 0
    };
    var to = {
        room_id: 0,
        room_name: '',
        date: 0
    };
    var selectedDaysArray = [];
    var selectedRooms=[];
    var price = 0;

    function clearColoredDays(){
        //console.log('clearColoredDays()');
        $(".day_selector").each(function () {
            if ($.inArray($(this).attr("id").split('-')[0] ,selectedRooms)!== -1 && $(this).attr("date") >= from.date && $(this).attr("date") <= to.date) {
                if ($(this).attr("status") != 'check_in' && $(this).attr("status") != 'check_out') {
                    $(this).css("background", "#fff");
                }else{
                    $(this).css("background", "none");
                }
            }
        });
        $(".room_selector").each(function () {
            if ($.inArray($(this).attr("room_id") ,selectedRooms)!== -1) {
                $(this).css('background','#fff');
                $(this).attr('is_selected','false');
            }
        });
    }

    function colorSelectedDays(){
        $(".day_selector").each(function () {
            if ($.inArray($(this).attr("id").split('-')[0] ,selectedRooms)!== -1 && $(this).attr("date") >= from.date && $(this).attr("date") <= to.date) {
                $(this).css("background", "#ff7f7f");
            }
        });
        $(".room_selector").each(function () {
            if ($.inArray($(this).attr("room_id") ,selectedRooms)!== -1) {
                $(this).css('background','#ff7f7f');
                $(this).attr('is_selected','true');
            }
        });
    }

    $('.room_selector').mouseover(function () {
        var msg=language_pack.room_selector.opd+'='+$(this).attr('one_person_discount')+'%<br>';
        msg+=language_pack.room_selector.max_child+'='+$(this).attr('max_children')+'<br>';
        msg+=language_pack.room_selector.max_adult+'='+$(this).attr('max_adults')+'<br>';
        if($(this).attr('description')!=''){
         msg+='<div style="border:solid white 1px; padding:4px">'+language_pack.room_selector.description+' : '+$(this).attr('description')+'</div>';
        }
        var room_name=$(this).attr('room_name');
        $(this).css('background','#00a8ec');
        $(this).notify(
                {
                    title: 'ოთახი '+room_name,
                    text: msg
                },
                {
                autoHide: false,
                hideDuration: 0,
                showDuration: 0,
                autoHideDelay: 0,
                position:"right",
                elementPosition: 'right',
                className:'info',
                style: 'metro'
        });
    });

    $('.room_selector').mouseout(function () {
    $(this).css('background','#ffffff');
        colorSelectedDays();
        $(this).parent().find(".notifyjs-hidable").trigger('notify-hide');
    });


    $('.room_selector').click(function () {
        if(selectedRooms.length==0){
            //console.log("return");
            return;
        }
        var tmp_old_rooms=selectedRooms.slice(0);
        clearColoredDays();
        var selected_room_id= $(this).attr('room_id');
        if ($.inArray(selected_room_id ,selectedRooms)!== -1) {
            selectedRooms = $.grep( selectedRooms, function( n, i ) {
                return n != selected_room_id;
            });
        }else{
            selectedRooms.push(selected_room_id);
        }
        if(!validateSelectedDates()){
            selectedRooms=tmp_old_rooms;
            $.notify({title: 'ERROR', text: notify_errors.invalid_date},{className:'error',style: 'metro'});
        }
        if(selectedRooms.length==0){
            $('#room_id').val(0);
            $('#check_in').val('');
            $('#check_out').val('');
        }else if(selectedRooms.length==1){
            $('#room_id').val(selectedRooms[0]);
        }else{
            $('#room_id').val(selectedRooms);
        }
        colorSelectedDays();
        updatePrice();
    });

    $(".dnr_local").click(function () {
        $.notify({title: 'ERROR', text: notify_errors.dnr_local},{className:'error',style: 'metro'});
    });

    $(".day_selector").mouseenter(function () {
        $(this).attr("old-color",$(this).css("background-color"));
        $(this).css("background-color","#29A4F9");
        var date=$(this).attr("date");
        var pos=$(this).position();
        $("#day_tooltip_content").html($("#week_day_"+date).text()+'<br>'+date);
        w_parent=$("#week_day_"+date).parent();

        $("#day_tooltip").css("background-color",w_parent.attr("bgColor"));

        $('#day_tooltip').css('top',pos.top-5);
        $('#day_tooltip').css('min-width',150);
        $('#day_tooltip').css('left',pos.left+$('#booking_board').scrollLeft()-24);
        $('#day_tooltip').css('z-index',1000);
        $('#day_tooltip').show();

    });
    $(".day_selector").mouseleave(function () {
        $(this).css("background-color",$(this).attr("old-color"));
        $('#day_tooltip').hide();

    });
    $(".day_selector").click(function () {

        var selected_room_id=$(this).attr("id").split('-')[0];
        if (from.room_id == 0) {
            clearColoredDays();
            selectedRooms=[];
            from.room_id = selected_room_id;
            from.room_name = $('#room_'+from.room_id).attr("room_name");
            from.date = $(this).attr("date");
            $('#room_id').val(0);
            $('#check_in').val(from.date);
            $('#check_out').val('');
            $(this).css("background", "#ff7f7f");
            updatePrice();
        } else if (from.room_id == selected_room_id) {
            //to daemtxva from-s
            if (from.date == $(this).attr("date")) {
                return;
            }
            ///to daemtxva from-s
            to.room_id = selected_room_id;
            to.room_name = $('#room_'+to.room_id).attr("room_name");
            to.date = $(this).attr("date");
            //tu [to naklebia fromze]
            if (to.date < from.date) {
                var tmp = to;
                to = from;
                from = tmp;
            }
            selectedRooms.push(selected_room_id);

            if(validateSelectedDates()){
                colorSelectedDays();
            }else{
                $.notify({title: 'ERROR', text: notify_errors.invalid_date},{className:'error',style: 'metro'});

                clearColoredDays();
                selectedRooms=[];
            }
             selectedDaysArray=getAllDateFromTo(from.date ,to.date);
            updatePrice();
            $("#check_in").val(from.date);
            $("#check_out").val(to.date);
            $("#room_id").val(to.room_id);
            to.room_id = 0;
            from.room_id = 0;
        } else {
            $("#" + from.room_id + "-" + from.date).css("background", "rgba(255,255,255,0)");
            selectedRooms=[];
            from.room_id = 0;
            $.notify({title: 'ERROR', text: "selected rooms are not the same" },{className:'error',style: 'metro'});
        }
        $(this).attr("old-color",$(this).css("background-color"));
    });

    function stringToDate(YYYYMMDD) {
        var arr = YYYYMMDD.split("-");
        return new Date(arr[0],arr[1]-1,arr[2]);
    }

    function getAllDateFromTo(from, to){
        var start= stringToDate(from),
            end = stringToDate(to),
            currentDate = new Date(start.getTime()),
            between = []
            ;

        while (currentDate <= end) {
            between.push(dateToString(new Date(currentDate)));
            currentDate.setDate(currentDate.getDate() + 1);
        }
        return between;
    }
    function validateSelectedDates(){
        console.log('validateSelectedDates',selectedRooms,from.date+'-->'+to.date);
        var result=true;
        $(".btd").each(function () {
            var this_room_id=$(this).attr("id").split('-')[0];

            if ($.inArray(this_room_id,selectedRooms)!== -1 && $(this).attr("date") >= from.date && $(this).attr("date") <= to.date) {
                if (($(this).attr("status") == 'check_in' && $(this).attr("date") != to.date) || $(this).attr("status") == 'in_use' || ($(this).attr("status") == 'check_out' && $(this).attr("date") != from.date)) {
                    result=false;
                }
            }
        });
        return result;
    }

    function obnuliai() {
        for(var i=0;i<selectedRooms.length;i++){
            $('#room_'+selectedRooms[i]).attr('is_selected','false');
            $('#room_'+selectedRooms[i]).css('background','fff');
        }
        selectedRooms=[];
        $("#" + from.room_id + "-" + from.date).css("background", "rgba(255,255,255,0)");
        $("#" + to.room_id + "-" + to.date).css("background", "rgba(255,255,255,0)");
        to.room_id = 0;
        from.room_id = 0;

        //dzveli monishnulis gasuftaveba
        for (var i = 0; i < selectedDaysArray.length; i++) {
            $('#' + selectedDaysArray[i]).css("background", "rgba(255,255,255,0)");
        }
        selectedDaysArray = [];
        //dzveli monishnulis gasuftaveba
        updatePrice();

    }

$('.booking_button').on('click',function(){
    $('#booking_type_ens').val($(this).data('id'));
});
    $("#booking_form").submit(function (event) {
        if (!validateSubmit()) {
            event.preventDefault();
        }
    });

    function validateSubmit() {
        if ($("#check_in").val() == '') {
            alert("Please Select Check in Date");
            return false;
        }
        if ($("#check_out").val() == '') {
            alert("Please Select Check Out Date");
            return false;
        }
        if ($("#room_id").val() == 0) {
            alert("Please Select Room");
            return false;
        }
        if ($.isEmptyObject(guest_obj)) {
                alert("Please Select Guest");
                return false;
        }else{
            $('#b_guest_id').val(guest_obj['id']);
        }
        if (!$.isEmptyObject(affiliate_obj)) {
            $('#b_affiliate_id').val(affiliate_obj['id']);
        }else{
            $('#b_affiliate_id').val(0);
        }
        if (!$.isEmptyObject(responsive_guest_obj)) {
            $('#b_responsive_guest_id').val(responsive_guest_obj['id']);
        }else{
            $('#b_responsive_guest_id').val(0);
        }
        return true;
    }


    $("#room_id").change(function () {
        reflectSelectionOnBoard();
    });

   $("#trigger_dbl_booking_modal").click(function (e) {
        e.preventDefault();
        $("#dbl_booking_modal").dialog({
            resizable: false,
            width: 400,
            modal: true,
            buttons: {
                "<?= $TEXT['service_modal']['but_add'] ?>": function () {


                },
        "<?= $TEXT['service_modal']['but_cancel'] ?>": function () {
                    $(this).dialog("close");
                }
            }
        });
        var $target=$('#dbl_booking_modal').dialog().parent();
        $target.css('top',(window.innerHeight-$target.height())/2);
        $target.css('left',(window.innerWidth-$target.width())/2);
        $target.css('position','fixed');
    });

    $("#booking_board").scrollLeft(0);

    var addedServices = [];
    $("#services_modal_trigger").click(function () {
        $("#booking_service_modal").dialog({
            resizable: false,
            width: 400,
            modal: true,
            buttons: {
                "<?= $TEXT['service_modal']['but_add'] ?>": function () {

                    if ($("#service_type").val() == 0) {
                            alert("Please Select Services Type");
                            return;
                    }
                    if ($("#service_selector").val() == 0) {
                        if ($("#service_title").val() == '') {
                            alert("Set title to service");
                            return;
                        }
                    }
                    var tmp = {};
                    tmp['id'] = $("#service_selector").val();
                    tmp['title'] = $("#service_title").val();
                    tmp['price'] = $("#service_price").val();
                    tmp['type_id'] = $("#service_type").val();
                    var count = parseInt($("#services_count").val(),10);
                    for(var j=0; j<count; j++){
                        addedServices.push(tmp);
                    }

                    drowAddedServices();
                    updatePrice();
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

    $( "#guest_search_field").autocomplete({

        source: "index_ajax.php?cmd=get_guest_suggestions",
        minLength: 0,
        select: function( event, ui ) {
            $('#guest_id').val(ui.item['id']);
            fillGuestModal(ui.item['id']);
        }
    }).autocomplete("option", "appendTo", "#booking_guest_modal");
    $( "#guest_search_field").click(function(){
        $(this).autocomplete( "search", $(this).val() );

    });


    var guest_modal_dest="";

    $(".guest_modal_trigger").click(function () {
        guest_modal_dest=$(this).attr('dest');
        if(guest_modal_dest=='guest'){
            $( "#guest_search_field" ).autocomplete('option', 'source', "index_ajax.php?cmd=get_guest_suggestions");
            if(!$.isEmptyObject(guest_obj)){
                fillGuestModal(guest_obj['id']);
            }else{
                fillGuestModal(0);
            }
        }else if(guest_modal_dest=='responsive'){
            $( "#guest_search_field" ).autocomplete('option', 'source', "index_ajax.php?cmd=get_guest_suggestions");
            //console.log('responsive_guest_obj');
            //console.log(responsive_guest_obj);
            if(!$.isEmptyObject(responsive_guest_obj)){
                fillGuestModal(responsive_guest_obj['id']);
            }else{
                fillGuestModal(0);
            }
        }else if(guest_modal_dest=='affiliate'){
            $( "#guest_search_field" ).autocomplete('option', 'source', "index_ajax.php?cmd=get_affiliate_suggestions");
            if(!$.isEmptyObject(affiliate_obj)){
                fillGuestModal(affiliate_obj['id']);
            }else{
                fillGuestModal(0);
            }
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
                    var cmd="";
                    if($('#guest_id').val()==0){
                        cmd='add_guest';
                    }else{
                        if(serialized_form_obj===$('#guest_modal_form').serialize()){
                            cmd='select_guest';
                        }else{
                            cmd='edit_guest';
                        }
                    }
                    //console.log(cmd);
                    if(cmd=='add_guest' || cmd=='edit_guest') {
                        var datatosend=$('#guest_modal_form').serializeArray();
                        var fileInput = $("[name='id_scan']")[0];
                        var file = fileInput.files[0];
                        var formData = new FormData($('#guest_modal_form')[0]);
                        formData.append('file', file);
                        $.each(datatosend,function(key){
                            formData.append(key['name'], key['value']);
                        });



                        var request = $.ajax({
                            url: "index_ajax.php?cmd=" + cmd,
                            method: "POST",
                            data: formData,
                            dataType: "json",
                            processData: false,
                            contentType: false,
                        });

                        request.done(function (msg) {
                            if(msg['guest']===undefined){
                                var message="<div style='border:solid red 2px'>"
                                for(var i=0; i<msg['errors'].length;i++){
                                    message+="<b>"+msg['errors'][i]+"</b><br>";
                                }
                                message+="</div>";
                                $('#guest_modal_message').html(msg['errors']);
                            }else{
                                $('#guest_id').val(msg['guest']['id']);
                                $("#booking_guest_modal").dialog("close");
                                updatePrice();
                                $('#first_name').val(msg.guest.first_name);
                                if(guest_modal_dest=='guest'){
                                    $('#guest_selector').html(msg.guest.first_name+ ' ' + msg.guest.last_name);

                                    guest_obj=msg['guest'];
                                }else if(guest_modal_dest=='responsive'){
                                    $('#responsive_guest_selector').html(msg.guest.first_name+ ' ' + msg.guest.last_name);
                                    responsive_guest_obj=msg['guest'];
                                }else if(guest_modal_dest=='affiliate'){
                                    $('#affiliate_selector').html(msg.guest.first_name+ ' ' + msg.guest.last_name);
                                    affiliate_obj=msg['guest'];
                                    updatePrice();
                                }
                                //console.log(guest_obj,responsive_guest_obj,affiliate_obj);
                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            $('#guest_modal_message').text("ver daemata!");
                        });

                    }else{
                        if(guest_modal_dest=='guest'){
                            $('#guest_selector').html($('#first_name').val() + ' ' + $('#last_name').val());
                            guest_obj=last_fetched_guest_obj;
                        }else if(guest_modal_dest=='responsive'){
                            $('#responsive_guest_selector').html($('#first_name').val() + ' ' + $('#last_name').val());
                            responsive_guest_obj=last_fetched_guest_obj;
                        }else if(guest_modal_dest=='affiliate'){
                            $('#affiliate_selector').html($('#first_name').val() + ' ' + $('#last_name').val());
                            affiliate_obj=last_fetched_guest_obj;
                            updatePrice();
                        }
                        //console.log(guest_obj,responsive_guest_obj,affiliate_obj);
                        $(this).dialog("close");
                    }
                },
                "<?= $TEXT['guest_modal']['but_cancel'] ?>": function () {
                        if(guest_modal_dest=='guest'){
                            $('#guest_selector').text($('#guest_selector').attr('def'));
                            guest_obj={};
                        }else if(guest_modal_dest=='responsive'){
                            $('#responsive_guest_selector').text($('#responsive_guest_selector').attr('def'));
                            responsive_guest_obj={};
                        }else if(guest_modal_dest=='affiliate'){
                            $('#affiliate_selector').text($('#affiliate_selector').attr('def'));
                            affiliate_obj={};
                        }
                        updatePrice();
                        $(this).dialog("close");
                    }
                }
        });
        var $target=$('#booking_guest_modal').dialog().parent();
        $target.css('top',(window.innerHeight-$target.height())/2);
        $target.css('left',(window.innerWidth-$target.width())/2);
        $target.css('position','fixed');

    });

    function reflectSelectionOnBoard(){
        clearColoredDays();
        selectedRooms=[];
         if($('#check_id').val()!=''&&$('#check_out').val()!=''&&$('#room_id').val()[0]!=0 ){
             from.date=$('#check_in').val();
             to.date=$('#check_out').val();
             selectedRooms=$('#room_id').val();
             if(!validateSelectedDates()){
                 $.notify({title: 'ERROR', text: notify_errors.invalid_date },{className:'error',style: 'metro'});
                 $('#room_id').val(0);
             }
             selectedDaysArray=getAllDateFromTo(from.date ,to.date);
             updatePrice();
         }

        colorSelectedDays();

    }

    $( "#check_in" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        minDate: 'today',
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() + (1000*60*60*24));
            $( "#check_out" ).datepicker( "option", "minDate",date );
            scrollBoard("day_"+selectedDate);
            reflectSelectionOnBoard();
        }
    });
    $( "#check_out" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        minDate: 'today',
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() - (1000*60*60*24));
            $( "#check_in" ).datepicker( "option", "maxDate", date );
            reflectSelectionOnBoard();
        }
    });

    $( "#period_start" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        //minDate: 'today',
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() + (1000*60*60*24));
            $( "#period_end" ).datepicker( "option", "minDate",date );
            //scrollBoard("day_"+selectedDate);
        }
    });
    $( "#period_end" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        //minDate: 'today',
        numberOfMonths: 2,
        dateFormat:'yy-mm-dd',
        onSelect: function( selectedDate ) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() - (1000*60*60*24));
            $( "#period_start" ).datepicker( "option", "maxDate", date );
        }
    });
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
            $('#guest_lname_tr').show();
            $('#company_co_tr').hide();
              $('#birth_day_tr').show();
        }else if($(this).val()=='company'){
            $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
            $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
            $('#guest_lname_tr').hide();
             $("#company_co_tr").show();
               $('#birth_day_tr').hide();
        }else if($(this).val()=='tour-company'){
            $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
            $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
            $('#guest_lname_tr').hide();
             $("#company_co_tr").show();
               $('#birth_day_tr').hide();
        }
        $("input[type='hidden'][name='guest_type']").val($(this).val());
    });

    function scrollBoard(target_id) {
        $("#booking_board").scrollLeft(0);
        var position;
        if($("#"+target_id).length){
            position = $("#"+target_id).position();
        }else{
            return;
        }

        $(target_id).css("background","red");
        $("#booking_board").scrollLeft(position.left - 400);
    }
    var w = $("#booking_board_cont").width() - $("#booking_board_left").width();
    $("#booking_board").width(w-10);

    $( window ).resize(function() {
      var w = $("#booking_board_cont").width() - $("#booking_board_left").width();
      $("#booking_board").width(w-10);
    });
    for (var i = 0; i < bookingPlates.length; i++) {
        drawBookingPlate(bookingPlates[i]);
    }
    scrollBoard('current_day');

    function split_booking_items(bookingPlate){
        var check_in=bookingPlate.attr('check_in');
        var check_out=bookingPlate.attr('check_out');
        var currentDate = stringToDate(check_in);
        currentDate.setDate(currentDate.getDate() + 1);
        var split_booking_items={};
        while (dateToString(currentDate) < check_out) {
            split_booking_items['split_'+dateToString(currentDate)]={'name':dateToString(currentDate),'value':dateToString(currentDate)};
            currentDate.setDate(currentDate.getDate() + 1);
        }
        return split_booking_items;
    }

    function disableDeleteButton(bookingPlate){
        var check_in=bookingPlate.attr('check_in');
        var check_out=bookingPlate.attr('check_out');
        var currentDate = '<?=CURRENT_DATE?>';
         if(currentDate>check_in){
             return true;
         }else{
             return false;
         }
    }

    $.contextMenu({
        selector: '.context-menu-one',
        build:function ($trigger, e){
            return {
                callback: function(key, options) {
                    var url ="";
                    var data={};
                    var booking_id=options.$trigger.attr('booking_id');
                    var guest_id=options.$trigger.attr('guest_id');
                    var paid_amount=options.$trigger.attr('paid_amount');
                    var total_price=options.$trigger.attr('total_price');

                    if(key=='edit'){
                        $(location).attr("href","index.php?m=booking_management&tab=booking_list&action=view&booking_id="+booking_id);
                    }
                    else if(key=='profile') {
                        fillGuestModal(guest_id);
                        $("#booking_guest_modal").dialog({
                            resizable: false,
                            width: 540,
                            modal: true,
                        });

                    }
                    else if(key=='cancel'){
                        if(paid_amount>0){
                            $("#form_message").hide();
                            $("#form_table").show();
                        }else{
                            $("#form_message").show();
                            $("#form_table").hide();
                        }
                        $("#delete_booking_form input[name='booking_id']").val(booking_id);
                        $("#delete_booking_form input[name='rf_action']").val('cabcel');
                        $("#delete_booking_form input[name='paid_amount']").val(paid_amount);
                        $("#delete_booking_form input[name='refund_amount']").attr("max",paid_amount);

                        $("#delete_booking_modal").dialog({
                            resizable: false,
                            width: 400,
                            height: 200,
                            modal: true,
                            buttons: {
                                "<?= $TEXT['del_booking_modal']['end_refund'] ?>": function () {
                                    $("#delete_booking_form").submit();
                                    $(this).dialog("close");

                                },
                                "<?= $TEXT['del_booking_modal']['cancel'] ?>": function () {
                                    $(this).dialog("close");
                                }
                            },
                            create: function (event, ui) {
                                $(event.target).parent().css('position', 'fixed');
                            },
                            position: { my: 'top', at: 'top+300' },
                        });
                    }else if(key=='delete'){
                        if(paid_amount>0){
                            $("#form_message").hide();
                            $("#form_table").show();
                        }else{
                            $("#form_message").show();
                            $("#form_table").hide();
                        }
                        $("#delete_booking_form input[name='booking_id']").val(booking_id);
                        $("#delete_booking_form input[name='paid_amount']").val(paid_amount);
                        $("#delete_booking_form input[name='refund_amount']").attr("max",paid_amount);

                        $("#delete_booking_modal").dialog({
                            resizable: false,
                            width: 400,
                            height: 200,
                            modal: true,
                            buttons: {
                                "<?= $TEXT['del_booking_modal']['end_refund'] ?>": function () {
                                    $("#delete_booking_form").submit();
                                    $(this).dialog("close");

                                },
                                "<?= $TEXT['del_booking_modal']['cancel'] ?>": function () {
                                    $(this).dialog("close");
                                }
                            },
                            create: function (event, ui) {
                                $(event.target).parent().css('position', 'fixed');
                            },
                            position: { my: 'top', at: 'top+300' },
                        });
                    } else{
                        var parts=key.split('_');
                        var action=parts[0];
                        if(action=='status'){
                            url="index_ajax.php?cmd=change_booking_status";
                            data={
                                booking_id: booking_id,
                                status_id:parts[1]
                            };
                        }else if(action=='split'){
                            url="index_ajax.php?cmd=split_booking";
                            data={
                                booking_id: booking_id,
                                date:parts[1]
                            };
                        }
                        var request = $.ajax({
                            url: url,
                            method: "POST",
                            data: data,
                            dataType: "json"
                        });

                        request.done(function (msg) {
                            if(action=='status') {
                                $("#booking_plate_" + msg.booking_id).attr('status_id', msg.status_id);
                                $("#booking_plate_" + msg.booking_id).find('.c-pl').css('background-color', msg.color);
                                $("#booking_plate_" + msg.booking_id).find('.l-pl').css('background-color', msg.color);
                                $("#booking_plate_" + msg.booking_id).find('.r-pl').css('background-color', msg.color);
                            }else if(action=='split'){
                                location.reload();
                            }else{

                            }
                        });

                        request.fail(function (jqXHR, textStatus) {
                            alert("Request failed: " + textStatus);
                        });
                    }
                },
                items: {
                    "edit": {
                        name: "<?=$TEXT['context']['edit']?>",
                        icon: "edit"
                    },
                    "split_booking": {
                        "name": "<?=$TEXT['context']['split']?>",
                        "icon": "cut",
                        "items": split_booking_items($trigger)
                    },
                    "change_status": {
                        "name": "<?=$TEXT['context']['status']?>",
                        "icon": "edit",
                        "items": {
                            <? $counter=0;
                            foreach($TMPL_all_statuses AS $status){
                                $counter++;
                                if($status['id']==2)continue;
                                if($counter==count($TMPL_all_statuses)){
                                    $isLast='';
                                }else{
                                    $isLast=',';
                                }

                                echo '"status_'.$status['id'].'": {name: "'.$status['title'].'",disabled: function(key, opt) {return opt.$trigger.attr("status_id")>='.$status['id'].';}}'.$isLast.'';
                            }?>
                        }
                    },
                    <?if($_SESSION['pcms_user_group']<=2){?>
                    "cancel": {
                        name: "ჯავშნის გაუქმება",
                        icon: "delete",
                        disabled: disableDeleteButton($trigger)},
                    <?}?>
                    <?if($_SESSION['pcms_user_group']<=2){?>
                    "delete": {
                        name: "ჯავშნის წაშლა",
                        icon: "delete",
                        disabled: disableDeleteButton($trigger)},
                    <?}?>
                    "profile": {
                        name: "პროფილი",
                        icon: "edit"
                    },
                }

            }
        }

    });

var drag_modal_x=false;

    booking_drag_confirm =$("#booking_drag_modal").dialog({
        autoOpen: false,
        resizable: false,
        width: 480,
        modal: true,
        buttons: {
            "<?= $TEXT['booking_drag_modal']['recalculate_price'] ?>": function () {
                confirmBookingDrag(movingPlate,true);
                drag_modal_x=true;
                $(this).dialog("close");
            },
            "<?= $TEXT['booking_drag_modal']['leave_old_price'] ?>": function () {
                confirmBookingDrag(movingPlate,false);
                drag_modal_x=true;
                $(this).dialog("close");
            },
            "<?= $TEXT['booking_drag_modal']['cancel'] ?>": function () {
                drag_modal_x=false;
                $(this).dialog("close");
            }
        },
        beforeClose: function(event, ui)
        {//console.log('beforeclose');
            if(!drag_modal_x){
                movingPlate.css('left',movingPlate.data('oldPosition').left);
                movingPlate.css('top',movingPlate.data('oldPosition').top);
            }
            drag_modal_x=false;
        }
    });
    $('.board-period-selector').click(function () {
        if($(this).hasClass('before')){
            $( "#days_before" ).val($(this).attr('val'));
        }
        if($(this).hasClass('after')){
            $( "#days_after" ).val($(this).attr('val'));
        }
        $( "#board-period-form" ).submit();
        //Console.log();
    });


    <? if(isset($_SESSION['errors']['booking_id'])){ ?>
    $.notify({title: 'ERROR', text: '<?=$_SESSION['errors']['booking_id']?>'},{className:'error',style: 'metro'});
    <? unset($_SESSION['errors']['booking_id']); } ?>

});

var resize_modal_x=false;
booking_resize_confirm =$("#booking_resize_modal").dialog({
    autoOpen: false,
    resizable: false,
    width: 480,
    modal: true,
    buttons: {
        "<?= $TEXT['booking_resize_modal']['recalculate_price'] ?>": function () {
            confirmBookingResize(resizingPlate,true);
            resize_modal_x=true;
            $(this).dialog("close");
        },
        "<?= $TEXT['booking_resize_modal']['leave_old_price'] ?>": function () {
            confirmBookingResize(resizingPlate,false);
            resize_modal_x=true;
            $(this).dialog("close");
        },
        "<?= $TEXT['booking_resize_modal']['cancel'] ?>": function () {
            resize_modal_x=false;
            $(this).dialog("close");
        }
    },
    beforeClose: function(event, ui)
    {
        if(!resize_modal_x){
            resizingPlate.css('left',resizingPlate.data('oldPosition').left);
            resizingPlate.css('top',resizingPlate.data('oldPosition').top);
            resizingPlate.css('width',resizingPlate.data('oldPosition').width);
            resizingPlate.find('.c-pl').css('width',(resizingPlate.width()-8)+'px');
        }
        resize_modal_x=false;
    }
});

setTimeout(function() {
    var lastScrollLeft = 0;
    if ( $.cookie("scroll") !== null ) {
        var scroll=$.cookie("scroll");

        $("#booking_board").scrollLeft(scroll);

    }
    $("#booking_board").scroll(function() {
        var documentScrollLeft = $("#booking_board").scrollLeft();
        if (lastScrollLeft != documentScrollLeft) {
            var date = new Date();
            var minutes = 30;
            date.setTime(date.getTime() + (minutes * 60 * 1000));
            $.cookie("scroll",documentScrollLeft,{ expires: date } );
            lastScrollLeft = documentScrollLeft;
        }

    });
}, 2500);

$(document).ready(function(){
    $("#booking_board .arrow").each(function(){

        var color = $(this).parent().find('.c-pl').css('background-color');
      $(this).css('border-right','15px solid '+color);
    });
});

</script>

<style>
    .ui-draggable {
        padding-left:2px;
        position:absolute;
        white-space:nowrap;
        cursor:default;
        overflow:hidden;
        color:#000000;
    }
    .ui-resizable {
        padding-left:2px;
        position:absolute;
        white-space:nowrap;
        cursor:default;
        overflow:hidden;
        color:#000000;
    }
    .ui-dialog-titlebar-close {
        //display: none;
    }
</style>

<? }else{?>
    <div style="clear: both"></div>
    <?=$TEXT['no_price_plans']?>
    <a href="index.php?m=room_management&tab=items">
        click
    </a>
<?} ?>
