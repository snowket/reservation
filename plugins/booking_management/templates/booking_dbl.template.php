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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.1/datepicker.css" />
<script src="./js/new_datepicker.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
<style>
input[type='radio']:after {
        width: 10px;
        height: 10px;
        border-radius: 10px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #fff;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }
    .calendar-icon_x {
	    width: 172px;
	    background: url('images/calendar.gif'),white;
	    background-repeat: no-repeat;
	    background-attachment: scroll;
	    background-position: 154px 2px;
	    padding-left: 2px;
	}
	.calendar-icon_d {
	    width: 172px;
	    background: url('images/calendar.gif'),white;
	    background-repeat: no-repeat;
	    background-attachment: scroll;
	    background-position: 157px 2px;
	    padding-left: 2px;
	}

    input[type='radio']:checked:after {
        width: 10px;
        height: 10px;
        border-radius: 10px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #000;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }
	.master{
		width: 1200px;;
		border-collapse: collapse;
    	border-spacing: 0;
	}
	.master_room{
		width: 1200px;
		border-collapse:collapse;
    	background-color: #e5ecf4;
	}

	.master >tbody > tr{
		height: 30px;
	}
	.master >tbody > tr:nth-of-type(2n+1){
		background-color: #e5ecf4;
	}
	.master > tbody > tr:nth-of-type(2n+2){
		background-color: #ccd2d9;
	}
	span.text{
		color:black;
		padding:0 10px;
		float: left;
	}
	label.radio_text{
		color:black;
	}
	fieldset span.text{
		margin: 3px 3px 0px 0;

	}
	.informer{
		width: 100%;
	}
	.informer  tbody  tr  td:nth-of-type(2n+1) {
   		width: 200px;
	}
	.divider{
		width: 100%;
		height: 20px;
	}
	select.select{
		width: 86%;
	}

	#master_tr td .informer{
		height: 65px;
		padding:5px 0;
	}
	table.row_former{
		padding: 5px 0 8px 0;
	}
	.delete_button_room_tmp{
		background-color: #ff6c00;
		border:0;
		color:white;
	}

  .addrowroomtmp{
		background-color: #ff6c00;
		border:0;
		color:white;
		padding:5px 10px;
		margin:3px 14px;
		cursor: pointer;
    font-family: 'nino',Arial,Verdana;
	}
	.book_it{
		background-color: #ff6c00;
    border: 0;
    color: white;
    padding: 5px 10px;
    margin: 3px 5px;
    float:left;
    font-family: 'nino',Arial,Verdana;
	}
  .delete_it{
		background-color: #ff6c00;
    border: 0;
    color: white;
    padding: 5px 10px;
    margin: 3px 5px;
    float:right;
    font-family: 'nino',Arial,Verdana;
	}
	table.master input{
		width: 172px !important;
	}
	table.master select{
		width:172px;
	}
</style>

<form method="post" action="index.php?m=booking_management&amp;tab=booking_dbl" id="booking_form">
<input type="hidden" name="action" value="save_booking">

<table class="master">
	<tr>
		<td >
			<table class="informer">
				<tr>
					<td>
						<span class="text">ჯგუფის სახელწოდება</span>
					</td>
					<td>
						<input type="text" name="gr_name" required=""  value="<?=$booking_master['booking_name']?>">

					</td>
				</tr>
			</table>
		</td>
		<td >
			<table class="informer">
				<tr>
					<td>
						<span class="text">დამკვეთი</span>
					</td>
					<td>
						<select name="guest_t" id="guest_type_select" class="select ">
							            <option value="tour-company">ტურ ოპერატორი</option>
										<option value="non-corporate">ფიზიკური პირი</option>
										<option value="company">კომპანია</option>

						</select>
					</td>
					<td>
						<div id="guest_selector" class="guest_modal_trigger" def="<?= $TEXT['booking_modal']['select_guest'] ?>" dest="guest" style="padding:2px; border:solid #868686 1px; cursor: pointer; text-align: center">
                                        აირჩიეთ დამკვეთი
                                    </div>

					</td>
				</tr>
			</table>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>
			<table class="informer">
				<tr>
					<td>
						<table>
							<tr>
								<td>
									<span class="text">კომპანიის ID </span>
								</td>
								<td>
									<input type="number" name="co_number" class=""  value="<?=$guest['id_number']?>">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
			<td>
			<table class="informer">
				<tr>
					<td>
						<table>
							<tr>
								<td>
									<span class="text">კომპანიის დასახელება</span>
								</td>
								<td>
									<input type="text" name="co_name" class="search_by_id" required="" value="<?=$guest['first_name']?>">
									<input type="hidden" name="guest_ind_discount" class="guest_ind_discount">
									<input type="hidden" name="b_guest_id" class="b_guest_id" value="<?=$booking_master['booking_user_id']?>">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
			<td>
			<table class="informer">
				<tr>
					<td>
						<table>
							<tr>
								<td>
									<span class="text">საკონტაქტო პირი </span>
								</td>
								<td>
									<input type="text" name="co_io_name" class="" required="" value="<?=$booking_master['booking_co_person']?>">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="informer">
				<tr>
					<td>
						<table>
							<tr>
								<td>
									<span class="text">მისამართი </span>
								</td>
								<td>
									<input type="text" name="co_street" class="co_street" required="" value="<?=$booking_master['booking_co_street']?>">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
			<td>
			<table class="informer">
				<tr>
					<td>
						<table>
							<tr>
								<td>
									<span class="text">ტელეფონი </span>
								</td>
								<td>
									<input type="text" name="co_tel" class="" required="" value="<?=$booking_master['booking_co_tel']?>">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
			<td>
			<table class="informer">
				<tr>
					<td>
						<table>
							<tr>
								<td>
									<span class="text">ელ. ფოსტა</span>
								</td>
								<td>
									<input type="text" name="co_mail" class="" required="" value="<?=$booking_master['booking_co_mail']?>">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="informer">
				<tr>
					<td>
						<table>
							<tr>
								<td>
									<span class="text">შესვლა </span>
								</td>
								<td>
									<input type="text" id="check_in" name="check_in" class="calendar-icon_x hasDatepicker" required="" value="">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
			<td>
			<table class="informer">
				<tr>
					<td>
						<table>
							<tr>
								<td>
									<span class="text">გამოსვლა</span>
								</td>
								<td>
									<input type="text" id="check_out" name="check_out" class="calendar-icon_x hasDatepicker" required="" value="">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
			<td>
			<table class="informer">
				<tr>
					<td>
						<table>
							<tr>
								<td>
									<span class="text">კვება</span>
								</td>
								<td>
									<select name="food" id="food">
										<option >აირჩიეთ საერთო კვება</option>
										<?php foreach ($TMPL_all_food as $key => $value): ?>
											<option value="<?=$value['id']?>" <?=$value['id']==$TMPL_bookings[0]['food_id']?'selected':''?> price="<?=$value['price']?>"><?=$value['title']?></option>
										<?php endforeach ?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>


<div class="divider"></div>
<table class="master_room">

		<tr id="master_tr" class="master_id_0 levok">
		<td style="width: 410px;" >
			<table class="informer">
				<tr>
					<td>
						<span class="text">ოთახის არჩევა</span>
					</td>
					<td>
						<select name="room_id[]" id="room_id" class="select select_room" date-idl="0">
							<option>ოთახები</option>
						</select>

					</td>
				</tr>
					<tr>
					<td>
						<span class="text">კვება</span>
					</td>
					<td>
						<select name="food_id[]" id="food" class="select select_food">
										<option >აირჩიე კვება</option>
										<?php foreach ($TMPL_all_food as $k => $food): ?>
											<option value="<?=$food['id']?>" price="<?=$food['price']?>"><?=$food['title']?></option>
										<?php endforeach ?>
						</select>
					</td>
				</tr>
			</table>
		</td>
			<td>
			<table class="informer">
				<tr>
					<td>
						<span class="text">ზრდასრული</span>
					</td>
					<td>
						<input type="number" name="person_count[]" id="adult_id" value="1">
					</td>
				</tr>
				<tr>
					<td>
						<span class="text">შესვლა</span>
					</td>
					<td>
						<input type="text" id="check_in_x" name="check_in_x[]" class="calendar-icon_d hasDatepicker check_in_x" value="">

					</td>
				</tr>
			</table>
		</td>
		<td>
			<table class="informer">
				<tr>
					<td>
						<span class="text">ბავშვი</span>
					</td>
					<td>
						<input type="number" name="child_count[]" id="child_id" value="0">

					</td>
				</tr>
					<tr>
					<td>
						<span class="text">გამოსვლა</span>
					</td>
					<td>
						<input type="text" id="check_out_x" name="check_out_x[]" class="calendar-icon_d hasDatepicker check_out_x" value="">

					</td>
				</tr>
			</table>
		</td>
		<td  style="height: 60px;">
			<input type="hidden" name="room_price[]" class="room_price" value=''>
			<table class="row_former" style="height: 100%;">
				<tr>
				<td >
					<button class="delete_button_room_tmp" style="height:100%;" onclick="deleteRoomtmp(this)" data-id="0"><i class="fa fa-times"></i></button>
				</td>
				</tr>

			</table>

		</td>
	</tr>
	<tr id="divider_tr">
		<td class="div" colspan="4" ><div style="background: white;height: 1px;margin:0 14px;"></div></td>
	</tr>
	<tr id="button_tr">
		<td colspan="4">
			<a onclick="AddRowRoomTmp(this); " data-id="0"  class="addrowroomtmp">ოთახის დამატება</a>
		</td>
	</tr>
</table>
<div class="divider"></div>
<table class="master">
	<tbody>
		<tr>
			<td colspan="2">
				<table class="informer">
					<tr>
						<td><span class="text">ჯამური ფასდაკლება</span></td>
						<td>
              <input type="number" name="all_out" class="all_out" placeholder="ჯამური" value="0">
            </td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="informer">
					<tr>
					<td>
						<span class="text">სულ სტუმრები</span></td>
					<td>
						<input type="number" disabled name="all_guests" class="all_guests" value="0">
					</td>
					</tr>
				</table>
			</td>
			<td>
				<table class="informer">
					<tr>
						<td>
							<span class="text">ზრდასრული / ბავშვი</span></td>
						<td>
						<input type="number" disabled  class="person" value="0">
						<input type="number" disabled  class="child" value="0">
					</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<table class="informer">
					<tr>
						<td colspan="1">
							<div class="person_price_toggle">

							<span class="text"><label for="person_price">განთავსების ღირებულება 1 პერსონაზე</label>
								<input type="checkbox" name="checkbox" id="person_price" value="value" style="width: 40px !important;"> </span>
							</div>
						</td>
						<td>
							<input type="text" name="person_price" class="person_price" style="display: none;">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="informer">
					<tr>
						<td>
							<span class="text">განთავსების ღირებულება</span>
						</td>
						<td>
							<input type="number" disabled name='accomodation_price' class="acc_price" value="0">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="informer">
					<tr>
						<td>
							<span class="text">კვება</span>
						</td>
						<td>
							<input type="number" disabled name='food_price' class="food_price" value="0">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="informer">
					<tr>
						<td>
							<span class="text">საერთო ღირებულება</span>
						</td>
						<td>
							<input type="number" disabled name='sum_price' class="sum_price" value="0">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>

			<td colspan="2">
				<button type="submit" class="book_it">დაჯავშნვა</button>
			</td>
		</tr>
	</tbody>
</table>
</form>

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
             <tr  id="company_co_tr">
                <td><label for="company_co">საკონტაქტო პირი</label></td>
                <td><input type="text" name="company_co" id="company_co" ></td>
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
<script>
   "use strict";

	var free_rooms=[];
	var free_rooms_tmp=[];
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
	var bookings_array = ['<?php echo implode("','", $bookings_array); ?>'];
	$('.day_out').change(function(){
		regenPrice();
	});
	$('.all_out').change(function(){
		regenPrice();
	});
	$("#person_price").change(function() {
	       $('.person_price').toggle();
	       $('.person_price').val(0);
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

    var guest_modal_dest="affiliate";
    $(".guest_modal_trigger").click(function () {
    	guest_modal_dest=$('#guest_type_select').val();
        if(guest_modal_dest=='non-corporate'){
            $( "#guest_search_field" ).autocomplete('option', 'source', "index_ajax.php?cmd=get_guest_suggestions");
            if(!$.isEmptyObject(guest_obj)){
                fillGuestModal(guest_obj['id']);
            }else{
                fillGuestModal(0);
            }
        }else if(guest_modal_dest=='company'){
            $( "#guest_search_field" ).autocomplete('option', 'source', "index_ajax.php?cmd=get_guest_suggestions");

            if(!$.isEmptyObject(responsive_guest_obj)){
                fillGuestModal(responsive_guest_obj['id']);
            }else{
                fillGuestModal(0);
            }
        }else if(guest_modal_dest=='tour-company'){
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
                            	 fillGuest(msg['guest']['id']);
                                $('#guest_id').val(msg['guest']['id']);
                                $("#booking_guest_modal").dialog("close");
                                $('#first_name').val(msg.guest.first_name);
                                if(guest_modal_dest=='guest'){
                                    $('#guest_selector').html(msg.guest.first_name+ ' ' + msg.guest.last_name);
                                    guest_obj=msg['guest'];
                                }else if(guest_modal_dest=='responsive'){
                                    $('#responsive_guest_selector').html(msg.guest.first_name+ ' ' + msg.guest.last_name);
                                    responsive_guest_obj=msg['guest'];
                                }else if(guest_modalbooking_dest=='affiliate'){
                                    $('#affiliate_selector').html(msg.guest.first_name+ ' ' + msg.guest.last_name);
                                    affiliate_obj=msg['guest'];
                                }
                                regenPrice();
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
                        }
                        fillGuest(last_fetched_guest_obj['id']);
                        regenPrice();
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
                        regenPrice();
                        $(this).dialog("close");
                    }
                }
        });
        var $target=$('#booking_guest_modal').dialog().parent();
        $target.css('top',(window.innerHeight-$target.height())/2);
        $target.css('left',(window.innerWidth-$target.width())/2);
        $target.css('position','fixed');

    });
	$(function() {
		var price=0;
		$('.levok').live('change',function(){
		var classList = $(this).attr('class').split(/\s+/);
		var id=classList[0].split('_id_');
		id=id.pop();
			var priceR=updatePrice(this);
			$(this).find('.room_price').val(priceR);
	   	sumPrices();
		updateFoodPrice();
		updateAccprice();
		});
	});
	function regenPrice(){
		var levok=$('.levok');
		$.each(levok,function(key,item){
			var classList = $(item).attr('class').split(/\s+/);
			var id=classList[0].split('_id_');
			id=id.pop();
			var priceR=updatePrice(item);
			$(item).find('.room_price').val(priceR);

		});
		sumPrices();
		updateFoodPrice();
		updateAccprice();

	}
	 $("input[type='radio'][name='guest_type']").change(function(event) {
	        if($(this).val()=='non-corporate'){
	            $('label[for="id_number"]').text($('label[for="id_number"]').attr('ncorp'));
	            $('label[for="first_name"]').text($('label[for="first_name"]').attr('ncorp'));
	            $('#guest_lname_tr').show();
	            //$('#guest_ind_discount_tr').hide();
	        }else if($(this).val()=='company'){
	            $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
	            $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
	            $('#guest_lname_tr').hide();
	            //$('#guest_ind_discount_tr').hide();
	        }else if($(this).val()=='tour-company'){
	            $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
	            $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
	            $('#guest_lname_tr').hide();
	            //$('#guest_ind_discount_tr').show();
	        }
	        $("input[type='hidden'][name='guest_type']").val($(this).val());
	    });
	 $(function() {
	 		var check_out=$('#check_out').val();
	 		var check_in=$('#check_in').val();
	 		var cx=$('.check_out_x');
			$.each(cx,function(index,item){
				$(item).datepicker({
					    format:'dd-mm-yyyy',
					    changeMonth: true,
					    numberOfMonths: 2,

					    Date:check_out,
					    showOn: "both",
					    defaultDate: null,
					    autoHide: true,
					    showButtonPanel: true,
					    });

			});
			var rx=$('.check_in_x');
			$.each(rx,function(index,item){
				$(item).datepicker({
					    format:'dd-mm-yyyy',
					    changeMonth: true,
					    changeMonth: true,

					    showOn: "both",
					    defaultDate: null,
					    autoHide: true,
					    showButtonPanel: true,
					    });
			});
	 });
	$(function() {

	    var dateFormat = "mm/dd/yyyy",
	    from = $("#check_in").datepicker({
	    format:'dd-mm-yyyy',
	    changeMonth: true,
	    numberOfMonths: 2,
	    showOn: "both",
	    defaultDate: null,
	    autoHide: true,
	    showButtonPanel: true,
	    })
	    .on("change", function () {
          from_x.datepicker('setDate',$(this).datepicker('getDate'));
	    }),
	    to = $( "#check_out" ).datepicker({
	        format:'dd-mm-yyyy',
	        changeMonth: true,
	        numberOfMonths: 2,
	        showOn: "both",
	        autoHide: true,
	        showButtonPanel: true,
	         defaultDate: null,
	      })
	    .on("change", function () {
          to_x.datepicker('setDate',$(this).datepicker('getDate'));
	    }),
	    from_x = $("#check_in_x").datepicker({
	      format:'dd-mm-yyyy',
	      changeMonth: true,
	      numberOfMonths: 2,
	      showOn: "both",
	      autoHide: true,
	      showButtonPanel: true,
	       defaultDate: null,
	    })
	    .on("change", function () {

	    }),
	    to_x = $( "#check_out_x" ).datepicker({
	      format:'dd-mm-yyyy',
	      changeMonth: true,
	      numberOfMonths: 2,
	      showOn: "both",
	      autoHide: true,
	      showButtonPanel: true,
	      defaultDate: null,
	      })
	    .on("change", function () {
	    });

	    function getDate (element) {
	      var date;
	      try {
	        date = $.datepicker.parseDate( dateFormat, element.value );
	      } catch (error) {
	        date = null;
	      }
	      return date;
	    }

	});

		var selectedDaysArray = [];
	    var selectedRooms=[];
	    var price = 0;


		var delete_button_room_tmp=$('.delete_button_room_tmp');
		$(delete_button_room_tmp[0]).css('display','none');


	  $('#food').on('change', function() {
		  	$('.select_food').val(this.value);
		  	regenPrice();
		});
    var s_rooms=[];

     $('.check_in_x , .check_out_x').live('change', function (e) {
    	    	var mmo=$(this).closest('.levok');
    	    	var checkin=$(mmo).find('.check_in_x').val();
    			  var checkout=$(mmo).find('.check_out_x').val();
            var elo=$('#check_in').val();
            var eno=$('#check_out').val();
            if(checkin=='' || checkout==''){
              return true;
            }
            if((elo==checkin && eno==checkout) && free_rooms.length != 0){
              console.log('stop here');
              selectedRooms.forEach(function(i){
                  i=parseInt(i);
              })
              if(selectedRooms.length){
                  selectedRooms.forEach(function(id){
                    free_rooms = $.grep(free_rooms, function(e){
                         return e.id != id;
                      });
                  })
                  free_rooms=free_rooms.filter(function(n){ return n.id != undefined });
              }

              var classList = $(mmo).attr('class').split(/\s+/);
              fillFreeRooms(classList[0],free_rooms);
              return true;
            }
    			$.ajax({
    				  type: "POST",
    				  url:"index_ajax.php?cmd=get_free_rooms_dbl",
    				  data: {'check_in':checkin,'check_out':checkout,'b_id':bookings_array},
    				  dataType: 'json',
    				  async:true,
    				  success: function(data) {
    				  	 	    free_rooms=[];
        					  	free_rooms_tmp=[];
        					  		$.each(data,function(i,x){
        					  			$.each(x,function(y,m){
        					  				$.each(m,function(l,f){
        					  					free_rooms.push(f);
        					  				});
        					  			});
        					  		});

                  selectedRooms.forEach(function(i){
                    i=parseInt(i);
                  })
                  if(selectedRooms.length){
                      selectedRooms.forEach(function(id){
                        free_rooms = $.grep(free_rooms, function(e){
                             return e.id != id;
                          });
                      })
                      free_rooms=free_rooms.filter(function(n){ return n.id != undefined });
                  }
        					var classList = $(mmo).attr('class').split(/\s+/);
        					fillFreeRooms(classList[0],free_rooms);
    				  }
    			});
    });

	    $('select.select_room').live('change', function (e) {
	    	var element=$(this);
        $(element).css('border-color','grey');
		    var optionSelected = $("option:selected", this);
		    var valueSelected = this.value;
		   	free_rooms=jQuery.grep(free_rooms, function(value) {
			      return (value.id != valueSelected);
	    		});
		   	selectedRooms.push(valueSelected);
        	free_rooms=free_rooms.filter(function(n){ return n.id != undefined });
		   	if($(element).data('pre')!=='undefined'){
  		   		selectedRooms=jQuery.grep(selectedRooms, function(value) {
  				  return value != $(element).data('pre');
  				});
  				free_rooms.push({
  					'id':$(element).data('pre'),
  					'common_id':$(element).data('com_id'),
  					'name':$(element).data('text'),
  				});
		   	}
		   	$(element).data('pre',valueSelected);
		   	$(element).data('com_id',optionSelected.attr('common_id'));
		   	$(element).data('text',optionSelected.html());
        regenPrice();
		});
	  function deleteRoomtmp(element){
		var strconfirm = confirm("გსურთ ოთახის წაშლა ? ");
	    if (strconfirm == true) {
	    	var id=$(element).data('id');
	    	var room_selec=$('.master_id_'+id).find('.select_room');
	    	free_rooms.push({
					'id':$(room_selec).data('pre'),
					'common_id':$(room_selec).data('com_id'),
					'name':$(room_selec).data('text'),
				});

			$('.master_id_'+id).remove();
	    }
	    sumPrices();
	    CalcPersons();
  		updateFoodPrice();
  		updateAccprice();
	}
	    var serialized_form_obj={};
	    var guest_obj={};
	    var affiliate_obj={};
	    var responsive_guest_obj={};
      var last_fetched_guest_obj={};

	    function fillGuestModal (g_id) {
	        if (g_id == 0) {
	            $('#guest_id').val(0);
	            $("#booking_guest_modal :input").attr("disabled", false);
	            $("#id_number").val('');
	            $("#first_name").val('');
	            $("#last_name").val('');
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
	              if(guest_modal_dest=='company'){
	                $('input:radio[name=guest_type]')[1].checked = true;
	                $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
	                $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
	                $('#guest_lname_tr').hide();
	            }else if(guest_modal_dest=='tour-company'){
	                $('input:radio[name=guest_type]')[2].checked = true;
	                $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
	                $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
	                $('#guest_lname_tr').hide();
	            }else{
	                $('input:radio[name=guest_type]')[0].checked = true;
	                $('label[for="id_number"]').text($('label[for="id_number"]').attr('ncorp'));
	                $('label[for="first_name"]').text($('label[for="first_name"]').attr('ncorp'));
	                $('#guest_lname_tr').show();
	                 $('#company_co_tr').hide();
	            }

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
	            }else if(msg.type=='tour-company'){
	                $('input:radio[name=guest_type]')[2].checked = true;
	                $('label[for="id_number"]').text($('label[for="id_number"]').attr('corp'));
	                $('label[for="first_name"]').text($('label[for="first_name"]').attr('corp'));
	                //$('#guest_ind_discount_tr').show();
	                $('#guest_lname_tr').hide();

	            }else{
	                $('input:radio[name=guest_type]')[0].checked = true;
	                //$('#guest_ind_discount_tr').hide();
	                $('label[for="id_number"]').text($('label[for="id_number"]').attr('ncorp'));
	                $('label[for="first_name"]').text($('label[for="first_name"]').attr('ncorp'));
	                $('#guest_lname_tr').show();
	                $('#company_co_tr').hide();
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
	function CalcPersons(){
		var count=0;
		var person=0;
		var child=0;
		$( "input[name*='person_count[]']" ).each(function() {  // first pass, create name mapping
	     	if($(this).val()!=''){
	     		  	count=parseInt(count)+parseInt($(this).val());
	     	}
	    });
	    person=count;
	    $( "input[name*='child_count[]']" ).each(function() {  // first pass, create name mapping
	     	if($(this).val()!=''){
	     		  	count=parseInt(count)+parseInt($(this).val());
	     	}
	    });
	    child=count-person;

		$('.person').val(person);
		$('.child').val(child);
		$('.all_guests').val(count);

	}

	function fillFreeRooms(element,f_rooms){
		var h='<option> ოთახები</option>';
		$.each(f_rooms,function(index,value){
			h=h+'<option value="'+value['id']+'" common_id="'+value['common_id']+'">'+value['name']+'</option>';
		});
		$('.'+element+' #room_id').html(h)
	}
	function display_check_in_check_out(element){
		var xin=$('.'+element).find('#check_in_x');
		var xon=$('.'+element).find('#check_out_x');
		$(xin).datepicker({
	      format:'dd-mm-yyyy',
	      changeMonth: true,
	      numberOfMonths: 2,
	      showOn: "both",
	      autoHide: true,
	      showButtonPanel: true,
	    });
      $(xin).datepicker('setDate',$('#check_in').datepicker('getDate',true));
		$(xon).datepicker({
	      format:'dd-mm-yyyy',
	      changeMonth: true,
	      numberOfMonths: 2,
	      showOn: "both",
	      autoHide: true,
	      showButtonPanel: true,
	    });
        $(xon).datepicker('setDate',$('#check_out').datepicker('getDate',true));
        return new Promise(function(resolve,reject){
          resolve();
        })
    }


	 function sumPrices(){
	    var prices=$('.room_price');
	    var master_price=0;
	    $.each(prices,function(index,value){
	      master_price=master_price+parseFloat($(value).val());
	    });
	    $('.sum_price').val(master_price);
	  }
	function AddRowRoomTmp(element){
		//window.event.preventDefault();
		element=$(element).last()[0];
		var id=parseInt(element.dataset.id)+1;
		var html_tmp=$('#master_tr').last().html();
		var divider=$('#divider_tr').html();
		divider='<tr id="divider_tr" class="master_id_'+(id)+'"</tr>'+divider+'</tr>';
		html_tmp=html_tmp.replace(/data-id="(\d+)"/g,'data-id="'+(id)+'"');
		$('<tr id="master_tr" class="master_id_'+(id)+' levok">'+html_tmp+'</tr>'+divider).insertAfter($('.master_room tbody #divider_tr').last());
		element.dataset.id=id;
		display_button_remove();
		CalcPersons();
		food_selection('master_id_'+id);
		display_check_in_check_out('master_id_'+id).then(function(){
      console.log('displayed rooms');
    });
  }
	function food_selection(element){
		$('.'+element).find('.select_food').val($('#food').val());
	}
	function display_button_remove(){
		$('.delete_button_room_tmp').css('display','block');
	}
	function fillGuest(id){

		  $.ajax({
			  type: "POST",
			  url:"index_ajax.php?cmd=get_guest_info",
			  data: {'guest_id':id},
			  dataType: 'json',
			  success: function(data) {

			  	var dat=jQuery.parseJSON(JSON.stringify(data));
				  	$('input[name=co_number').val(dat['id_number']);
				  	$('input[name=co_io_name').val(dat['company_co']);
				  	$('input[name=co_mail').val(dat['email']);
				  	$('input[name=co_street').val(dat['address']);
				  	$('input[name=co_name').val(dat['first_name'] + dat['last_name']);
				  	$('input[name=co_tel').val(dat['telephone']);
				  	$(".guest_ind_discount").val(dat['ind_discount']);
				  	$(".b_guest_id").val(dat['id']);
	            }
			});
	}
	function stringToDate(YYYYMMDD) {
	    var arr = YYYYMMDD.split("-");
	    return new Date(arr[2],arr[1]-1,arr[0]);
	}
	function dateToString(date){
	   return date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+ '-' + ("0" + date.getDate()).slice(-2);

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
	function getAllDateFood(from, to){
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

	    function getRoomPrice(room_id,day,callback){
        $.ajax({
	         type: "GET",
	         url:"index_ajax.php",
	         data: {'cmd':'get_room_price','day':day,'room_id':room_id},
	         dataType: 'json',
	         async: false,
	         success: function(message){
             localStorage.setItem("key", "value");
	            callback(message);
	        }
	       });

	    }

	    var guest_net_price = 0;
	    var guest_total_price=0;
	    var affiliate_net_price=0;

	    function updateFoodPrice(){
	    	var ea=$('.levok');
	    	var dprice=0;
	    	$.each(ea,function (index,value){
	    	   var $option_food=$(value).find('#food option:selected');
		       var $option_room=$(value).find('#room_id option:selected');
		       var $option_adult=$(value).find('#adult_id');
		       var $option_child=$(value).find('#child_id');
		       var $check_in_x=$(value).find('#check_in_x');
		       var $check_out_x=$(value).find('#check_out_x');
		       var ss=getAllDateFood($check_in_x.val(),$check_out_x.val());
	          for (var i = 0; i < ss.length - 1; i++) {

	               	 dprice+= parseFloat($option_food.attr('price'))*(parseFloat($option_adult.val())+parseFloat($option_child.val()));
	            }
	    	});
	    	$('.food_price').val(dprice);
	    }
	    function updateAccprice(){
	    	var sum=$('.sum_price').val();
	    	var food=$('.food_price').val();
	    	var acc=sum-food;
	    	if(acc>0){
	    		$('.acc_price').val(acc);
	    	}

	    }
	    function updatePrice(id) {
	        guest_net_price = 0;
	        guest_total_price=0;//net price+services
	        affiliate_net_price=0;
	        var one_person_discount=0;
          $(id).find('#food').css('border-color','grey');
	       var $option_food=$(id).find('#food option:selected');
	       var $option_room=$(id).find('#room_id option:selected');
	       var $option_adult=$(id).find('#adult_id');
	       var $option_child=$(id).find('#child_id');
	       var $check_in_x=$(id).find('#check_in_x');
	       var $person_price=$('.person_price');

	       var $check_out_x=$(id).find('#check_out_x');
	       if($check_in_x.val()!='' && $check_out_x.val()!=''){
	       	var datecheckin=$check_in_x.datepicker('getDate', true);
	       	var datecheckout=$check_out_x.datepicker('getDate', true);
	        var selectedDaysArray=getAllDateFromTo(datecheckin,datecheckout);
	       }else{
	         //no check_in/check_out room_price cant be calculated
	         console.log('no check_in/check_out room_price cant be calculated for : ' + id);
	         return guest_total_price;
	       }
	       if(!$option_room.attr('common_id')){
	         return guest_total_price;
	       }
	       if($option_food.attr('price')==undefined){
	         return guest_total_price;
	       }

	        var guest_ind_discount= 0;
	        var guest_tax_free_discount=0;
	        var affiliate_discount=0;
	        var affiliate_tax=1;
	        var acc=0;
	        if(!$.isEmptyObject(guest_obj)){
	            guest_ind_discount= guest_obj['ind_discount'];
	            guest_tax_free_discount=(guest_obj['tax']=='0')?18:0;
	        }



	        var daily_discount=0;
	        var fixed_discount_tmp=parseFloat($('.all_out').val());
          var levok_count=$('.levok').length;
          var fixed_discount=parseFloat(fixed_discount_tmp/levok_count);

	            var selected_room_id=$option_room.val();
	            if(parseInt($option_adult.val())>=0){
	                one_person_discount=parseInt(managers[$option_room.attr('common_id')][$option_adult.val()]);
	                if(isNaN(one_person_discount)){
	                    one_person_discount=0;
	                }
	            }
	            for (var i = 0; i < selectedDaysArray.length - 1; i++) {
	               if($person_price.val()>0){
	               		acc=parseFloat($person_price.val())*parseFloat($option_adult.val());
	               }else{
	               	 getRoomPrice($option_room.attr('common_id'),selectedDaysArray[i],function(data){
	                 acc=parseInt(data.price);
	                });
	               }

	  				if(acc=='0'){
	                	console.log('price acc = 0');
	                	return false;
	                }
		               	var food_price = parseFloat($option_food.attr('price'))*(parseFloat($option_adult.val())+parseFloat($option_child.val()));
		                var guest_daily_net_price = parseFloat(calculateNetPrice(acc, food_price, one_person_discount, 0, guest_ind_discount, daily_discount, guest_tax_free_discount));
		                guest_net_price += parseFloat(guest_daily_net_price);

				        var cashBack=(guest_net_price).toFixed(2);
				        if(cashBack<0){
				            cashBack=0;
				        }
				        guest_total_price=guest_net_price;

	            }
              guest_total_price=parseFloat(guest_total_price)-parseFloat(fixed_discount);
			CalcPersons();
	        return guest_total_price;
	    }

	    function calculateNetPrice(a,food_price,c,d,f,e,g){
	        //console.log('calculateNetPrice('+a+','+food_price+','+c+','+d+','+f+','+e+','+g+');');
	        var daily_price=parseFloat(a);
	        //daily_price=daily_price-daily_price/100*b;
	        daily_price=daily_price-daily_price/100*c;
	        daily_price=daily_price-daily_price/100*d;
	        daily_price=daily_price-daily_price/100*f;
	        daily_price=daily_price-e;
	        daily_price=daily_price-daily_price/(100+g)*g;
	        daily_price=daily_price+food_price;
	        return daily_price.toFixed(2);
	    }
      $('.book_it').click(function(e){
        if(!$('#booking_form')[0].checkValidity()){
          e.preventDefault();
        }
        var levok=$('.levok');
        var error_room=0;
        var error_food=0;
        $.each(levok,function(index,item){
          var room_id=$(item).find('#room_id');
          var parsed_room_id=parseInt($(room_id).val());
          if(isNaN(parsed_room_id)){
            error_room=1;
            $(room_id).css('border','1px solid red');
          }
          var food_id=$(item).find('#food');
          var parsed_food_id=parseInt($(food_id).val());
          if(isNaN(parsed_food_id)){
            error_food=1;
            $(food_id).css('border','1px solid red');
          }
        })
        if(error_room || error_food){
          return false;
        }else{
          $('<input type="submit">').hide().appendTo('#booking_form').click().remove();
        }

      })
</script>
