<script src="./js/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="./css/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

<div style="width: 100%;" class="tdrow1">
	<form name="thisform" action="" method="post"
		enctype="multipart/form-data">
		<input type="hidden" name="action" value="add">
		<table style="width: 100%;" align="center" cellspacing="0"
			cellpadding="5" border="0">
			<tr>
				<td valign="middle" class="tdrow2" nowrap style="width: 200px;">Package
					type</td>
				<td valign="middle" class="tdrow3" nowrap><select name="type">
						<option value="alia">Alia</option>
						<option value="qronika">Qronika</option>
						<option value="both">Both</option>
				</select></td>
			</tr>
			<tr>
				<td valign="top" class="tdrow2" nowrap>Months</td>
				<td valign="middle" class="tdrow3" nowrap>
				<?php $newspapers_packages_periods = explode(',',$settings['newspapers_packages_periods']);/*foreach ()*/ ?>
				<select name="months">
					<?php
					foreach ( $newspapers_packages_periods as $npp ) {
						echo '<option value="' . $npp . '">' . $npp . '</option>';
					}
					?>
				</select>
				</td>
			</tr>
			<tr>
				<td valign="top" class="tdrow2" nowrap>Alia numbers count</td>
				<td valign="middle" class="tdrow3" nowrap><input type="text"
					class="formField1" name="number_alia" value="" style="width: 75px;"
					maxlength="5"></td>
			</tr>
			<tr>
				<td valign="top" class="tdrow2" nowrap>Qronika numbers count</td>
				<td valign="middle" class="tdrow3" nowrap><input type="text"
					class="formField1" name="number_qronika" value=""
					style="width: 75px;" maxlength="5"></td>
			</tr>
			<tr>
				<td valign="top" class="tdrow2" nowrap>Cost</td>
				<td valign="middle" class="tdrow3" nowrap><input type="text"
					class="formField1" name="cost" value="" style="width: 75px;"
					maxlength="7"></td>
			</tr>
			<tr>
				<td valign="top" colspan="2" align="right"><input
					class="formButton2" type="submit" name="update" value="   add   "></td>
		
		</table>
	</form>

	<hr noshade>



	<!-- ITEMS //-->
	<style>
#items {
	
}

#items .td_type {
	
}

#items .td_type select {
	width: 100%;
}

#items .td_months {
	
}

#items .td_months select {
	width: 100%;
}

#items .td_number_alia {
	width: 75px;
}

#items .td_number_alia input {
	width: 100%;
}

#items .td_number_qronika {
	width: 75px;
}

#items .td_number_qronika input {
	width: 100%;
}

#items .td_cost {
	width: 125px;
}

#items .td_cost input {
	width: 100%;
}

#items .td_submit {
	width: 55px;
	text-align: center;
}

#items .td_actions {
	width: 55px;
	text-align: center;
}
</style>
<?php if ( ($n=count($items))>0 ) { /*p($data[0]);*/ ?>
	<div id="items">
	
	<?php foreach ($items AS $v) { ?>
	<form action="" method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="edit"> <input type="hidden"
				name="id" value="<?=$v['id']?>">

			<table style="width: 100%;" align="center" cellspacing="0"
				cellpadding="5" border="0">
				<tr>
					<td class="td_type tdrow2"><select name="type">
							<option value="alia" <?=($v['type']=='alia'?'selected':'')?>>Alia</option>
							<option value="qronika"
								<?=($v['type']=='qronika'?'selected':'')?>>Qronika</option>
							<option value="both" <?=($v['type']=='both'?'selected':'')?>>Both</option>
					</select></td>
					<td class="td_months tdrow2"><select name="months">
				<?php
		foreach ( $newspapers_packages_periods as $npp ) {
			$selected = $v ['months'] == $npp ? 'selected' : '';
			echo '<option value="' . $npp . '" ' . $selected . '>' . $npp . '</option>';
		}
		?>
			</select></td>
					<td class="td_number_alia tdrow2"><input type="text"
						class="formField1" name="number_alia"
						value="<?=$v['number_alia']?>" maxlength="5"></td>
					</td>
					<td class="td_number_qronika tdrow2"><input type="text"
						class="formField1" name="number_qronika"
						value="<?=$v['number_qronika']?>" maxlength="5"></td>
					</td>
					<td class="td_cost tdrow2"><input type="text" class="formField1"
						name="cost" value="<?=$v['cost']?>" maxlength="7"></td>
					</td>
					<td class="td_submit tdrow2"><input class="formButton2"
						type="submit" name="update" value="Edit"></td>
					<td class="td_actions tdrow2" nowrap><a
						href="<?=$SELF?>&action=delete&rec_id=<?=$data[$i]['id']?>"><img
							src="./images/icos16/delete.gif" width="16" height="16"
							border="0" alt="Delete" align="absbottom" vspace="3"></a> <a
						href="<?=$SELF?>&action=change_status&rec_id=<?=$data[$i]['id']?>"><img
							src="./images/icos16/<?=($data[$i]['publish']==1?'protect-green':'protect-red')?>.gif"
							width="16" height="16" border="0" alt="Switch visibility"
							vspace="5"></a></td>
				</tr>
			</table>
		</form>
	<?php }?>
	</div>
<?php }?>



</div>