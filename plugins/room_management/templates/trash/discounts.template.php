<script language="javascript" src="./js/calendar/calendar.js"></script>
<script language="javascript" src="./js/calendar/calendar-ge.js"></script>
<script language="javascript" src="./js/calendar/calendar-setup.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="./js/calendar/calendar-blue.css">
<form action="" method="get" id="form">
<input type="hidden" name="m" value="<?=$_GET['m']?>" />
<input type="hidden" name="tab" value="<?=$_GET['tab']?>" />
<table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
	<tr>
		<td style="width:200px;">&nbsp;</td>
		<td style="">
			<table border="0" cellpadding="7" cellspacing="0">
				<tr>
					<td>
						<select name="block_id" class="formField1" style="width:100%;">
							<option><?=$TEXT['select_block']?></option>
							<? for ($i=0;$i<count($TMPL_blocks);$i++)  {
								$selected = ($_GET['block_id'] == $TMPL_blocks[$i]['id'])?'selected':'';
								$style = ($TMPL_blocks[$i]['level'] == 1)?'style="font-weight:bold;"':'';
								echo '<option value="'.$TMPL_blocks[$i]['id'].'" '.$style.' '.$selected.' >'.$TMPL_blocks[$i]['title'].'</option>';
							?>
							<? } ?>
						</select>
					</td>
					<td>
						<select name="type_id[]" class="formField1" style="width:100%; height:100px;" multiple>
							<option><?=$TEXT['select_type']?></option>
							<? for ($i=0;$i<count($TMPL_categories);$i++)  {
								$selected = ($_GET['type_id'] == $TMPL_categories[$i]['id'])?'selected':'';
								$style = ($TMPL_categories[$i]['level'] == 1)?'style="font-weight:bold;"':'';
								echo '<option value="'.$TMPL_categories[$i]['id'].'" '.$style.' '.$selected.' >'.$TMPL_categories[$i]['title'].'</option>';
							?>
							<? } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
							Start date - <input type="text" id="start_date" name="start_date" value="<?=$_POST['start_date']?>" />
							<img id="trigger_c2" src="./images/icos16/cal.gif" width="14" height="14" border="0" align="absmiddle" style="cursor:pointer; padding-right:20px;">
							<script type="text/javascript">
							Calendar.setup({
							    inputField     :    "start_date",     // id of the input field
							    ifFormat       :    "%Y-%m-%d",      // format of the input field
							    button         :    "trigger_c2",  // trigger for the calendar (button ID)
							    align          :    "Br",           // alignment
								timeFormat     :    "24",
								showsTime      :    true,
							    singleClick    :    true
							});
							</script>
					</td>
					<td>
							End date - <input type="text" id="end_date" name="end_date" value="<?=$_POST['end_date']?>" />
							<img id="trigger_c3" src="./images/icos16/cal.gif" width="14" height="14" border="0" align="absmiddle" style="cursor:pointer;">
							<script type="text/javascript">
							Calendar.setup({
							    inputField     :    "end_date",     // id of the input field
							    ifFormat       :    "%Y-%m-%d",      // format of the input field
							    button         :    "trigger_c3",  // trigger for the calendar (button ID)
							    align          :    "Br",           // alignment
								timeFormat     :    "24",
								showsTime      :    true,
							    singleClick    :    true
							});
							</script>
					</td>
				</tr>
				<tr>
					<td>
						<label for="discount" nowrap><?=$TEXT['discount']?>%</label>
						<input type="text" id="discount" width="60%;" name="discount" value="<?=$_POST['discount']?>" />
					</td>
					<td align="right"><input type="submit" value="Search" class="formButton2" /></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?if (!empty($TMPL_prices)) {?>
<h2 style="padding-top:50px; margin-bottom:20px; border-bottom:1px solid #cecece;">Price List</h2>
	<?
	foreach ($TMPL_rid as $k => $v){ 
		$n=count($TMPL_dr[$v])-1;
	?>
	
		<h3 style="background:#cecece; margin-top:30px;">Date Range : From <?=$TMPL_dr[$v][0]?>  To  <?=$TMPL_dr[$v][$n]?></h3>
		<table width="100%" border="1" cellspacing="5" cellpadding="10" class="" style="margin-top:0px;  background:#ddd; color:#000;">
			<tr>
				<td></td>
				<?foreach($weekDays as $k3 => $v3){
					$bg=$k3>4?'red':'green';?>
					<td style="color:<?=$bg?> !important;"><?=$v3?></td>
				<?}?>
				<td>&nbsp;</td>
			</tr>
			<?foreach ($rooms as $key2 => $value2){?>
				<form action="" method="post">
					<input type="hidden" name="type_id" value="<?=$_GET['type_id']?>" />
					<input type="hidden" name="block_id" value="<?=$_GET['block_id']?>" />
					<input type="hidden" name="action" value="update" />	
					<input type="hidden" name="rec_id" value="<?=$v?>" />	
					<input type="hidden" name="common_id" value="<?=$key2?>" />
					<input type="hidden" name="capacity_id" value="<?=$value2['capacity_id']?>" />
					<tr>
						<td><?=$value2['title']?></td>
						<?foreach ($weekDays as $k2 => $v2) {?>
							<td><input type="text" value="<?=$TMPL_prices[$v][$key2][$v2]?>" name="day[<?=$key2?>][<?=$v2?>]" style="width:60px;" /> Gel</td>
						<?}?>
						<td><input type="submit" value="edit" /></td>
					</tr>
				</form>
			<?}?>	
		</table>
		<form action="" method="post">
			<input type="hidden" name="type_id" value="<?=$_GET['type_id']?>" />
			<input type="hidden" name="action" value="delete" />	
			<input type="hidden" name="rec_id" value="<?=$v?>" />
			<input type="submit" value="delete" />
		</form>
	<?}?>
<?}?>
 
<div align="center"><?=$TMPL_navbar?></div>