<?php if(!defined('ALLOW_ACCESS')) exit;?>

<script language="javascript" src="./tiny_mce/tiny_mce.js"></script>
<script language="javascript" src="./js/mce_init.js"></script>

<style>
	#filterForm {margin: 0 0 30px 0;}
	#filterForm table.t1 {width: 100%;}
	#filterForm th {width: 105px; vertical-align: top; text-align: right; white-space: nowrap; padding: 5px;}
	#filterForm td {vertical-align: top;}
	
	#mainForm {}
	#mainForm table.t1 {width: 100%;}
	#mainForm th {width: 105px; vertical-align: top; text-align: right; white-space: nowrap; padding: 5px;}
	#mainForm td {vertical-align: top;}
	#mainForm input[type=text] {width: 70%; padding: 2px 5px;}
	#mainForm textarea {width: 99%; height: 400px;}
	#mainForm .shortcuts {margin: 10px 0 0 0;}
	#mainForm .shortcuts .list * {font-weight: normal;}
	#mainForm .shortcuts .list a {display: block; text-decoration: none; font-style: italic;}
	#mainForm .shortcuts .list a:hover {text-decoration: underline;}
</style>

<form id="filterForm" name="filterForm" action="" method="get">
	<input type="hidden" name="m" value="<?php echo $_GET['m']; ?>" /> <input
		type="hidden" name="tab" value="<?php echo $_GET['tab']; ?>" />
	<table class="t1">
		<tr>
			<th><?php echo $TEXT['mail_template']['template'];?>:</th>
			<td>
                <select name="typeid" onchange="$('#filterForm').submit()" style="width: 300px">
                <? foreach($mailTemplates AS $k=>$v){?>
                    <option value="<?=$k?>" <?=((int)$_GET['typeid']==$k)?'selected':''?>><?=$v?></option>
                <?}?>
                </select>
			</td>
		</tr>
	</table>
</form>

<table width="100%">
<tr>
	<td colspan="2" class="border_gray1"><?=$TMPL_langSwitchers?></td>
</tr>
</table>
<form id="mainForm" name="mainForm" action="" method="post"
	enctype="multipart/form-data">
	<input type="hidden" name="action" value="update"> <input type="hidden"
		name="o[id]" value="<?php echo $data['id']; ?>">
	<table class="t1">

	</tr>
		<tr>
			<th><?php echo $TEXT['mail_template']['subject']?>: </th>
			<td><input type="text" name="o[subject]"
				value="<?php echo $data['subject']; ?>" /></td>
		</tr>

		<tr>
			<th>
				<?php echo $TEXT['mail_template']['message']?>:
				<br><br><br><br>
				<div class="shortcuts">
					<div class="title">Shortcuts:</div>
					<div class="list">

						<a href="javascript:void(0);"
                            onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{hotel_checkin_time}');"
                            title="Insert Hotel's Checkin time">{hotel_checkin_time}</a>
                        <a
                            href="javascript:void(0)"
                            onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{hotel_checkout_time}');"
                            title="Insert Hotel's Checkout time">{hotel_checkout_time}</a>
						<hr>
						<a href="javascript:void(0);"
							onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{firstname}');"
							title="Insert client's firstname">{firstname}</a>
						<a
							href="javascript:void(0)"
							onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{lastname}');"
							title="Insert client's lastname">{lastname}</a>
						<a href="javascript:void(0);"
                            onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{email}');"
                            title="Insert client's firstname">{email}</a>
                        <a
                            href="javascript:void(0)"
                            onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{password}');"
                            title="Insert client's lastname">{password}</a>
                        <a
                             href="javascript:void(0)"
                             onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{pass_recovery_link}');"
                             title="Insert client's lastname">{pass_recovery_link}</a>
                        <hr>
						<a
							href="javascript:void(0)"
							onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{booking_number}');"
							title="Insert booking number ">{booking_number}</a>
						<a
                            href="javascript:void(0)"
                            onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{booking_checkin}');"
                            title="Insert Check-in date ">{booking_checkin}</a>
                        <a
                            href="javascript:void(0)"
                            onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{booking_checkout}');"
                            title="Insert Check-out date">{booking_checkout}</a>
                        <a
                            href="javascript:void(0)"
                            onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{night_stay}');"
                            title="Insert night_stay">{night_stay}</a>
                        <a
                            href="javascript:void(0)"
                            onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{rooms_count}');"
                            title="Insert rooms_count">{rooms_count}</a>
                        <a
                             href="javascript:void(0)"
                             onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{total_accommodation_price}');"
                             title="Insert total_accommodation_price">{total_accommodation_price}</a>
                        <a
                             href="javascript:void(0)"
                             onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{total_services_price}');"
                             title="Insert total_services_price">{total_services_price}</a>
                        <a
                             href="javascript:void(0)"
                             onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{services}');"
                             title="Insert services">{services}</a>
                        <a
                             href="javascript:void(0)"
                             onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{comment}');"
                             title="Insert comment">{comment}</a>
                        <a
                             href="javascript:void(0)"
                             onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{booking_vat}');"
                             title="Insert booking_vat">{booking_vat}</a>
                        <a
                             href="javascript:void(0)"
                             onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{booking_total_price}');"
                             title="Insert booking_total_price">{booking_total_price}</a>
                        <hr>
                        <a
                             href="javascript:void(0)"
                             onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{cancellation_date}');"
                             title="Insert cancellation_date">{cancellation_date}</a>
                        <hr>
                        <a
                            href="javascript:void(0)"
                            onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{hotel_name}');"
                            title="Insert hotel_name from settings">{hotel_name}</a>
                        <a
                            href="javascript:void(0)"
                            onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{hotel_address}');"
                            title="Insert hotel_address from settings">{hotel_address}</a>
                        <a
                            href="javascript:void(0)"
                            onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{hotel_phone}');"
                            title="Insert hotel_phone from settings">{hotel_phone}</a>






                        <hr>
						<a href="javascript:void(0)"
							onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{invoice_id}');"
							title="Insert invoice id / parcel id - if message is about payment">{invoice_id}</a>

						<a href="javascript:void(0)"
							onclick="tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{amount}');"
							title="Insert amount value - if message is about payment/transaction">{amount}</a>

					</div>
				</div>
			</th>
			<td><textarea name="o[message]" class="mceEditor"><?php echo $data['message']; ?></textarea></td>
		</tr>
		<tr>
			<th colspan="2"><input type="submit"
				value=" <?=$TEXT['global']['edit']?> " class="formButton2" /></th>
		</tr>
	</table>
</form>




