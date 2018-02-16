<?php if(!defined('ALLOW_ACCESS')) exit; ?>

<style>
	#filter {}
	#filter table.t1 {/*width:100%;*/ border-spacing:1px; margin:0 0 20px 0;}
	#filter table.t1 th {padding:3px;}
	#filter table.t1 td {padding:3px;}
	#filter select {font-size:12px; font-family:calibri;}
	
	#listTemplate {}
	#listTemplate .navbar {margin:0 0 20px 0;}
	#listTemplate div.error {text-align: center; font-weight: bold; color: #ff0000;}
	#listTemplate table.t1 {width:100%; /*border-collapse:collapse;*/ border-spacing:1px; margin:0 0 20px 0;}
	#listTemplate table.t1 th {padding:5px;}
	#listTemplate table.t1 td {padding:5px;}
	#listTemplate table.t1 td.num {text-align:right; color:#387FC8;}
	#listTemplate table.t1 td.act {text-align:center;}
	#listTemplate table.t1 td.act a {text-decoration:underline;}
	#listTemplate table.t1 td.act a:hover {text-decoration:none;}
	#listTemplate table.t1 td.paid_0 {background:#ff0000; color:#fff; font-weight:bold;}
	#listTemplate table.t1 td.paid_1 {background:green; color:#fff; font-weight:bold;}
</style>

<form id="filter" class="filter" method="get">
	<input type="hidden" name="m" value="<?php echo $_GET['m']; ?>" />
	<input type="hidden" name="tab" value="<?php echo $_GET['tab']; ?>" />
	<input type="hidden" name="action" value="parcel/filter" />
	
	<table class="t1">
		<tr>
			<th class="tdrow2">User (owner)</th>
			<td class="tdrow2">
				<select id="s_userid" name="s[userid]" style="width:400px;">
					<option value="0" attrb="null">&nbsp;</option>
					<?php #echo pcmsInterface::drawOptions($usersList, $_REQUEST['s']['userid'], '_ASSOC_'); ?>
					
					<?php
						foreach ($usersList AS $id => $s) {
							#$s = in_array($id, array(1,3)) ? '$$\''.$s : $s;
							$selected = $id == $_REQUEST['s']['userid'] ? 'selected' : '';
							echo '<option value="'.$id.'" '.$selected.' attrb="'. htmlspecialchars($id.' - '.preg_replace('/\.+\d+\.\d+$/', '', strtolower($s)), ENT_QUOTES) .'">'.$id.' - '.$s.'</option>';
						}
					?>
				</select>
				<input type="text" id="s_userid_filt" name="" value="" />
				<input type="button" name="" value="filter" onclick="FilterUsers('#s_userid_filt', '#s_userid')" style="cursor:pointer;" />
				<input type="button" name="" value="clear" onclick="FilterUsers('#s_userid_filt', '#s_userid', 'clear')" style="cursor:pointer;" />
			</td>
		</tr>
		<tr>
			<th class="tdrow2">User (creator)</th>
			<td class="tdrow2">
				<select id="s_creatorid" name="s[creatorid]" style="width:400px;">
					<option value="0">&nbsp;</option>
					<?php #echo pcmsInterface::drawOptions($usersList, $_REQUEST['s']['creatorid'], '_ASSOC_'); ?>
					
					<?php
						foreach ($usersList AS $id => $s) {
							$selected = $id == $_REQUEST['s']['creatorid'] ? 'selected' : '';
							echo '<option value="'.$id.'" '.$selected.' attrb="'. htmlspecialchars($id.' - '.preg_replace('/\.+\d+\.\d+$/', '', strtolower($s)), ENT_QUOTES) .'">'.$id.' - '.$s.'</option>';
						}
					?>
				</select>
				<input type="text" id="s_creatorid_filt" name="" value="" />
				<input type="button" name="" value="filter" onclick="FilterUsers('#s_creatorid_filt', '#s_creatorid')" style="cursor:pointer;" />
				<input type="button" name="" value="clear" onclick="FilterUsers('#s_creatorid_filt', '#s_creatorid', 'clear')" style="cursor:pointer;" />
			</td>
		</tr>
		<tr>
			<th class="tdrow2">Tracking Code</th>
			<td class="tdrow2">
				<select id="s_trackid_opt" name="s[trackid_opt]">
					<?php $_REQUEST['s']['trackid_opt'] = !isset($_REQUEST['s']['trackid_opt']) ? 0 : $_REQUEST['s']['trackid_opt'];?>
					<?php echo pcmsInterface::drawOptions(CORE::$MODULES->PARCEL->pcmsSearchFiltrationLike, $_REQUEST['s']['trackid_opt'], '_ASSOC_'); ?>
				</select>
				<input type="text" name="s[trackid]" value="<?php echo htmlspecialchars($_REQUEST['s']['trackid']); ?>" />
			</td>
		</tr>
		<tr>
			<th class="tdrow2">Flight</th>
			<td class="tdrow2">
				<select id="s_flight_opt" name="s[flight_opt]">
					<?php $_REQUEST['s']['flight_opt'] = !isset($_REQUEST['s']['flight_opt']) ? 1 : $_REQUEST['s']['flight_opt'];?>
					<?php echo pcmsInterface::drawOptions(CORE::$MODULES->PARCEL->pcmsSearchFiltrationLike, $_REQUEST['s']['flight_opt'], '_ASSOC_'); ?>
				</select>
				<input type="text" name="s[flight]" value="<?php echo htmlspecialchars($_REQUEST['s']['flight']); ?>" />
			</td>
		</tr>
		<tr>
			<th class="tdrow2">Sender</th>
			<td class="tdrow2">
				<select id="s_sender_opt" name="s[sender_opt]">
					<?php $_REQUEST['s']['sender_opt'] = !isset($_REQUEST['s']['sender_opt']) ? 1 : $_REQUEST['s']['sender_opt'];?>
					<?php echo pcmsInterface::drawOptions(CORE::$MODULES->PARCEL->pcmsSearchFiltrationLike, $_REQUEST['s']['sender_opt'], '_ASSOC_'); ?>
				</select>
				<input type="text" name="s[sender]" value="<?php echo htmlspecialchars($_REQUEST['s']['sender']); ?>" />
			</td>
		</tr>
		<tr>
			<th class="tdrow2">Status</th>
			<td class="tdrow2">
				<select id="s_sd" name="s[sd][]" size="5" multiple="1">
					<?php echo pcmsInterface::drawOptions(CORE::$MODULES->PARCEL->parcelStatuses, $_REQUEST['s']['sd'], '_ASSOC_'); ?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="tdrow2" colspan="2" style="text-align:right;">
				<input type="button" name="" value="reset" class="formButton2" onclick="ResetSubmitForm()" />
				<input type="submit" name="" value="search" class="formButton2" />
			</td>
		</tr>
	</table>
</form>

<div class="addButtonContainer" style="text-align:right; margin:0 0 10px 0;">
	<a href="<?php echo $SELF?>&action=parcel/edit" target="_blank" title="<?php echo $TEXT['global']['add']?>"><img src="../images/_fromusaus/icons/plus.png" alt="<?php echo $TEXT['global']['add']?>" title="<?php echo $TEXT['global']['add']?>" style="width:22px;" /></a>
</div>

<div id="listTemplate">
	<?php
	
	if (! is_array ( $records ) || ! count ( $records )) {
		echo '<div class="error">No records found</div>';
	} else {
		/*echo '
			<div class="navbar">' . $TMPL_navbar . '</div>
			<div class="clear"></div>
		';*/
		
		echo '
			<table class="t1">
				<tr>
					<th class="tdrow2">#</th>
					<th class="tdrow2">ID</th>
					<th class="tdrow2">User</th>
					<th class="tdrow2">Tracking Code</th>
					<th class="tdrow2">Sender</th>
					<th class="tdrow2">Items Count</th>
					<th class="tdrow2">Items Cost</th>
					<th class="tdrow2">Flight</th>
					<th class="tdrow2">Weight</th>
					<th class="tdrow2">Customs</th>
					<th class="tdrow2">Delivery Cost</th>
					<th class="tdrow2">Paid</th>
					<th class="tdrow2">Status</th>
					<th class="tdrow2">Creator</th>
					<th class="tdrow2">Manage</th>
				</tr>
		';
		foreach ( $records as $i => $v ) {
			$senderShort = strlen($v['sender']) > 20 ? substr($v['sender'], 0, 20).'..' : $v['sender'];
			
			echo '
				<tr id="tr_'.$v['id'].'">
					<td class="tdrow3">'.($i + ((intval($_GET['p']) ? intval($_GET['p']) : 1) - 1) * $SETTINGS['per_page'] + 1).'</td>
					<td class="tdrow3">'.$v['id'].'</td>
					<td class="tdrow3"><a href="?m=users_registered&action=edit_user&uid='.$v['userid'].'" target="_blank" title="'.$v['user_name'].' &lt;'.$v['user_email'].'&gt;">'.$v['user_login'].'</a></td>
					<td class="tdrow3">'.$v['trackid'].'</td>
					<td class="tdrow3 sender" title="'.$v['sender'].'" titleShort="'.$senderShort.'"><a href="'.(!preg_match('/^http:\/\//i', $v['sender']) ? 'http://' : '').$v['sender'].'" target="_blank">'.($senderShort).'</a></td>
					<td class="tdrow3 num">'.$v['items_count'].'</td>
					<td class="tdrow3 num">'.$v['items_cost'].'</td>
					<td class="tdrow3">'.$v['flight'].'</td>
					<td class="tdrow3 num">'.$v['weight'].'</td>
					<td class="tdrow3 num">'.$v['custom_cost'].'</td>
					<td class="tdrow3 num">'.$v['delivery_cost'].'</td>
					<td class="tdrow3 paid_'.$v['status_paid'].'">'.$TEXT['parcel']['parcel_paid_' . $v['status_paid']].'</td>
					<td class="tdrow3">'.CORE::$MODULES->PARCEL->parcelStatuses[$v['status_delivery']].'</td>
					<td class="tdrow3"><a href="?m=users_registered&action=edit_user&uid='.$v['userid'].'" target="_blank" title="'.$v['creator_name'].' &lt;'.$v['creator_email'].'&gt;">'.$v['creator_login'].'</a></td>
					<td class="tdrow3 act">
						<a href="'.$SELF.'&action=parcel/edit&id='.$v['id'].'" target="_blank">edit</a>
						<a href="'.$SELF.'&action=parcel/edit/items&id='.$v['id'].'" target="_blank">items</a>
						<a href="'.$SELF.'&action=parcel/edit/statuses&id='.$v['id'].'" target="_blank">status</a>
					</td>
				</tr>
			';
		}
		echo '</table>';
		
		echo '
			<div class="clear"></div>
			<div class="navbar">' . $TMPL_navbar . '</div>
			<div class="clear"></div>
		';
	}
	
	#p2($records[0]);
	?>
</div>

<script>$('td:empty').html('&nbsp;');</script>

<script>
	function ResetSubmitForm() {
		console.log('ss');

		$('#filter select, #filter input[type=text]').val('');
	}

	function FilterUsers(inputEl, filterEl, cmd) {
		var cmd = cmd || 'cearch';
		var stringLike = $(inputEl).val().trim().toLowerCase();

		if (stringLike.length == 0 || cmd == 'clear') {
			$(filterEl + ' option').show();
		}
		else {
			$(filterEl + ' option').hide();
			$(filterEl + ' option[attrb*="' + stringLike + '"]').show();
			$(filterEl + ' option[attrb="null"]').show();
			$(filterEl).val('');
		}
	}
</script>

<?php #p($_REQUEST); ?>
