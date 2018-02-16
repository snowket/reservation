<?php {$record = is_array($record) ? $record : array();} ?>

<style>
	#addEditForm {}
	#addEditForm table.main {border-spacing:1px;}
	#addEditForm table.main th {padding:5px; text-align:right; vertical-align:middle;}
	#addEditForm table.main td {padding:2px; vertical-align:middle;}
	#addEditForm table.main td input[type=text] {width:400px;}
	#addEditForm table.main td.numeric input {width:75px; text-align:right;}
	#addEditForm table.main .currency,
	#addEditForm table.main .weight_unit {text-transform:uppercase; font-style:italic; color:#ccc; font-weight:bold;}
	#addEditForm table.main td.paid_0 {background:#ff0000; color:#fff; font-weight:bold;}
	#addEditForm table.main td.paid_1 {background:green; color:#fff; font-weight:bold;}
	
	
	#errorMessages {margin:10px 0;}
	#errorMessages .row {/*float:left;*/ padding:5px; border-bottom:0px solid #ff0000; background:#FDBABA;}
	#errorMessages .row .title {color:#ff0000; font-weight:bold;}
	#errorMessages .row .msg {/*color:#ff0000;*/}
</style>

<?php
	if ($error) {
		echo '<div id="errorMessages">';
		
		foreach ($errorMessages AS $errorMessage) {
			echo '<div class="row"><b class="title">Error:</b> <span class="msg">'.$errorMessage.'</span></div><div class="clear"></div>';
		}
		
		echo '</div>'; 
	}
?>

<form id="addEditForm" name="addEditForm" action="" method="post" enctype="multipart/form-data">
	<?php if (!$readonly) { ?>
	<input type="hidden" name="action" value="<?php echo $action ?>" />
	<?php } ?>
	
	<table class="main">
		<?php if (@$record['id']) { ?>
		<tr>
			<th class="tdrow2">Status:</th>
			<td class="tdrow2" title="<?php echo $record['status_delivery']; ?>">
				<?php echo CORE::$MODULES->PARCEL->parcelStatuses[$record['status_delivery']]; ?>
			</td>
		</tr>
		<tr>
			<th class="tdrow2">Payment Status:</th>
			<td class="tdrow2 paid_<?php echo $record['status_paid']; ?>" title="<?php echo $record['status_paid']; ?>">
				<?php echo $TEXT['parcel']['parcel_paid_' . $record['status_paid']]; ?>
			</td>
		</tr>
		<tr>
			<th class="tdrow2" colspan="2">&nbsp;</th>
		</tr>
		<?php } ?>
		<tr>
			<th class="tdrow2">Tracking Code:</th>
			<td class="tdrow2"><input type="text" name="o[trackid]" value="<?php echo @$record['trackid'] ?>" /></td>
		</tr>
		<tr>
			<th class="tdrow2">Owner:</th>
			<td class="tdrow2">
				<select name="o[userid]">
					<option value="0">&nbsp;</option>
					<?php echo pcmsInterface::drawOptions($usersList, $record['userid'], '_ASSOC_'); ?>
				</select>
			</td>
		</tr>
		<tr>
			<th class="tdrow2">Sender:</th>
			<td class="tdrow2"><input type="text" name="o[sender]" value="<?php echo @$record['sender'] ?>" /></td>
		</tr>
		<tr>
			<th class="tdrow2">Weight:</th>
			<td class="tdrow2 numeric"><input type="text" name="o[weight]" value="<?php echo @$record['weight'] ?>" /> <span class="weight_unit"><?php echo CORE::$MODULES->PARCEL->weight_unit ?></span></td>
		</tr>
		<tr>
			<th class="tdrow2">Cutdown:</th>
			<td class="tdrow2">
				<select name="o[status_cutdown]">
					<?php echo pcmsInterface::drawOptions(CORE::$MODULES->PARCEL->parcelCutdownStatuses, (isset($record['status_cutdown']) ? $record['status_cutdown'] : key(CORE::$MODULES->PARCEL->parcelCutdownStatuses)), '_ASSOC_'); ?>
				</select>
			</td>
		</tr>
		<tr>
			<th class="tdrow2">Flight:</th>
			<td class="tdrow2 numeric"><input type="text" name="o[flight]" value="<?php echo @$record['flight'] ?>" /></td>
		</tr>
		<tr>
			<th class="tdrow2">Customs:</th>
			<td class="tdrow2 numeric"><input type="text" name="o[custom_cost]" value="<?php echo @$record['custom_cost'] ?>" /> <span class="currency"><?php echo CORE::$MODULES->USER->defaultCurrency ?></span></td>
		</tr>
		<tr>
			<th class="tdrow2">Delivery Cost:</th>
			<td class="tdrow2 numeric"><input type="text" name="o[delivery_cost]" value="<?php echo @$record['delivery_cost'] ?>" /> <span class="currency"><?php echo CORE::$MODULES->USER->defaultCurrency ?></span></td>
		</tr>
		<tr>
			<th class="tdrow2" colspan="2">&nbsp;</th>
		</tr>
		<?php if ($record['creatorid']) { ?>
		<tr>
			<th class="tdrow2">Creator:</th>
			<td class="tdrow2"><?php echo $usersList[$record['creatorid']]; ?></td>
		</tr>
		<?php } ?>
		<?php if ($record['created']) { ?>
		<tr>
			<th class="tdrow2">Created:</th>
			<td class="tdrow2"><?php echo $record['created']; ?></td>
		</tr>
		<?php } ?>
		<?php if ($record['updated']) { ?>
		<tr>
			<th class="tdrow2">Updated:</th>
			<td class="tdrow2"><?php echo $record['updated']; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th class="tdrow2">Visible:</th>
			<td class="tdrow2">
				<select name="o[visible]">
					<?php echo pcmsInterface::drawOptions(array(1 => 'yes', 0 => 'no'), $record['visible'], '_ASSOC_'); ?>
				</select>
			</td>
		</tr>
		
		<?php if (!$readonly) { ?>
		<tr>
			<th class="tdrow2" colspan="2"><input type="submit" name="" value="<?php echo @$action ?>" class="formButton2" /></th>
		</tr>
		<?php } ?>
	</table>
</form>

<?php #p($record); p($action); ?>

<script>$('td:empty').html('&nbsp;');</script>
