

<script language="javascript" src="./js/calendar/calendar.js"></script>
<script language="javascript" src="./js/calendar/calendar-ge.js"></script>
<script language="javascript" src="./js/calendar/calendar-setup.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="./js/calendar/calendar-blue.css">
<div id="column_width" style="width:100%; height:1px"> </div>
<table width="100%" border="0" cellspacing="0" cellpadding="3" class="tdrow1 text1">
	<tr>
		<td style="width:200px;"><b><?=$TEXT['filter']?></b></td>
		<td style="">
			<form action="" method="get" id="form">
				<input type="hidden" name="m" value="<?=$_GET['m']?>" />
				<input type="hidden" name="tab" value="<?=$_GET['tab']?>" />
				<select onchange="this.form.submit()" name="block_id" class="formField1" style="width:30%;">
					<option value="0"><?=$TEXT['select_block']?></option>
					<? foreach ($TMPL_blocks as $block)  {
						$selected = ($_GET['block_id'] == $block['id'])?'selected':'';
						$style = ($block['level'] == 1)?'style="font-weight:bold;"':'';
						echo '<option value="'.$block['id'].'" '.$style.' '.$selected.' >'.$block['title'].'</option>';
					?>
					<? } ?>
				</select>
				<select onchange="this.form.submit()" name="type_id" class="formField1" style="width:30%;">
					<option value="0"><?=$TEXT['select_type']?></option>
					<? foreach($TMPL_categories as $category)  {
						$selected = ($_GET['type_id'] == $category['id'])?'selected':'';
						$style = ($category['level'] == 1)?'style="font-weight:bold;"':'';
						echo '<option value="'.$category['id'].'" '.$style.' '.$selected.' >'.$category['title'].'</option>';
					?>
					<? } ?>
				</select>
			</form>
		</td>
	</tr>
</table>
<?if ($TMPL_data){?>
<h2 style="padding-top:50px; margin-bottom:20px; border-bottom:1px solid #cecece;">Enter Prices</h2>
<?if ($TMPL_errors) {?>
	<h3 class="err"><center><?=$TMPL_errors?></center></h3>
<?}?>
<form action="" method="post">
<input type="hidden" name="action" value="add" />	
<input type="hidden" name="type_id" value="<?=$_GET['type_id']?>" />
<input type="hidden" name="block_id" value="<?=$_GET['block_id']?>" />
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

<table width="100%" border="1" cellspacing="5" cellpadding="10" class="" style="margin-top:20px; background:#ddd; color:#000;">
	<tr>
		<td>&nbsp;</td>
		<?foreach ($weekDays as $k => $v) {
			$bg=$k>4?'red':'green';
		?>
			<td  style="color:<?=$bg?> !important;"><?=$v?></td>
		<?}?>
	</tr>
	<?foreach ($TMPL_data as $key => $value) {
		$rooms[$value['id']]=array('title'=>$TMPL_capacity[$value['capacity_id']],'capacity_id'=>$value['capacity_id']);
	?>
		<input type="hidden" name="roomArr[]" value="<?=$value['id']?>" />
		<input type="hidden" name="capacityArr[]" value="<?=$value['capacity_id']?>" />
		<tr>
			<td ><?=$TMPL_capacity[$value['capacity_id']]?></td>
			<?foreach ($weekDays as $k => $v) {?>
				<td><input type="text" name="day[<?=$value['id']?>][<?=$v?>]" style="width:80px;" /><?=$TMPL_CONF['system_currency']?></td>
			<?}?>
		</tr>
	<?}?>
	<tr>
		<td colspan="8" align="right">
			<input type="submit" value="Add Prices" class="formButton2" />
		</td>
	</tr>	
</table>
</form>
<?}?>

<?if (!empty($TMPL_prices)) {?>
<h2 style="padding-top:50px; margin-bottom:20px; border-bottom:1px solid #cecece;">Price List</h2>
	<?
    $counter=0;
	foreach ($TMPL_rid as $k => $v){ 
		$n=count($TMPL_dr[$v])-1;
        $counter++;
	?>
	
		<div class="close-but" style="outline:solid #ff8d39 1px; cursor:pointer; background:#ff8d39; margin-top:30px; padding: 4px; color:#FFF;"><b>Date Range :</b> <span style="color: #000"> <b><?=$TMPL_dr[$v][0]?></b> - <b><?=$TMPL_dr[$v][$n]?></b></span></div>
        <div class="content-div" style="<?=($counter!=1)?'display:none;':''; ?>outline:solid #ff8d39 1px; padding-bottom: 4px;">
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
					<input type="hidden" name="start_date" value="<?=$date_range[$v][0]?>" />
					<input type="hidden" name="end_date" value="<?=$date_range[$v][$n]?>" />
					<input type="hidden" name="common_id" value="<?=$key2?>" />
					<input type="hidden" name="capacity_id" value="<?=$value2['capacity_id']?>" />
					<tr>
						<td><?=$value2['title']?></td>
						<?foreach ($weekDays as $k2 => $v2) {?>
							<td><input type="text" value="<?=$TMPL_prices[$v][$key2][$v2]?>" name="day[<?=$key2?>][<?=$v2?>]" style="width:60px;" /> <?=$TMPL_CONF['system_currency']?></td>
						<?}?>
						<td><input type="submit" value="edit" class="formButton2" /></td>
					</tr>
				</form>
			<?}?>	
		</table>
		<form action="" method="post">
			<input type="hidden" name="type_id" value="<?=$_GET['type_id']?>" />
			<input type="hidden" name="action" value="delete" />	
			<input type="hidden" name="rec_id" value="<?=$v?>" />
			<input type="submit" value="delete" class="formButton2" />
		</form>
			
			
		
		
		<?$r=count($rooms);?>
		<form action="" method="post">
		<div style=" float:left; width:120px; ">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
				 	<td colspan="2" style="background:#52bcd6; border-right:1px solid #fff !important; color:#fff; text-align:center; font-size:20px;">Year</td>
				</tr>
				<tr>
					<td colspan="2" style="background:#145261; height:22px; border-right:1px solid #fff !important; color:#fff; text-align:center; font-size:16px;">Month</td>
				</tr>
				<tr>
				 	<td colspan="2" style="background:#52bcd6;  border:1px solid #fff !important; border-bottom:1px solid #145261 !important; text-align:center; color:#fff; font-size:12px;">Day</td>
				</tr>
				<?foreach ($rooms as $k4 => $v4) {?>
					<tr>
						<td rowspan="2" style="vertical-align:middle;"><?=$v4['title']?></td>
						<td style="height:20px;"><?=$TMPL_CONF['system_currency']?></td>
					</tr>
					<tr>
						<td colspan="2" style="height:20px;">%</td>
					</tr>
					<tr><td colspan="2" style="background:#ccc; height:1px;">&nbsp;</td></tr>
				<?}?>
			</table>
		</div>
            <div class="clear"></div>
		<div class="prices-container" style="width:1px; margin:20px 0px 0px 120px; overflow-x: scroll">
			<table border="0" width="100px" cellpadding="0" cellspacing="0">
				<tr>
					<?foreach ($TMPL_dates[$k] as $key_c => $value_c) {?>
						<td>
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
								<tr>
								 	<td colspan="<?=count($value_c)?>" style="background:#52bcd6; border-right:1px solid #fff !important; color:#fff; padding-left:5px; font-size:20px;"><?=$key_c?></td>
								</tr>
								<tr>
								<?foreach ($value_c as $k_c => $v_c) {?>
									<td>
										<table border="0" cellpadding="0" cellspacing="0">
											<tr>
												<td colspan="<?=count($v_c)?>" style="background:#145261; border-right:1px solid #fff !important; padding-left:10px; color:#fff; font-size:16px;"><?=$TEXT['months'][$k_c]?></td>
											</tr>
											<tr>
												<?foreach ($v_c as $k1_c => $v1_c) {
													$date=$key_c.'-'.$k_c.'-'.$k1_c; 
													$startDate = strtotime($date);
													$dw = date( "D", $startDate);
													$bg='52bcd6';
													if (in_array($dw,array('Sat','Sun'))) {
														$bg='ff7f7f';
													}
												?>
													<td>
														<table border="0" cellpadding="0" cellspacing="0">
															<tr>
																<td style="background:#<?=$bg?>;  border:1px solid #fff !important; border-bottom:1px solid #145261 !important; text-align:center; color:#fff; font-size:12px;"><?=$k1_c?></td>
															</tr>
															<tr>
																<td>
																	<table border="0" cellpadding="0" cellspacing="0">
																		<?foreach ($v1_c as $k2_c => $v2_c) {?>
																			<tr>
																				<td nowrap>
																					<input style="width:32px;" type="text" name="price[<?=$v2_c?>]" value="<?=$daily_price[$v2_c]['price']?>" />
																				</td>
																			</tr>
																			<tr>
																				<td nowrap>
																					<input style="width:32px; background:<?=$daily_price[$v2_c]['discount']>0?'#ff7f7f':'#fff'?>;" type="text" name="discount[<?=$v2_c?>]" value="<?=$daily_price[$v2_c]['discount']?>" />
																				</td>
																			</tr>
																			<tr><td colspan="2" style="background:#ccc; height:1px;">&nbsp;</td></tr>
																		<?}?>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												<?}?>
											</tr>
										</table>
									</td>
								<?}?>
								</tr>
							</table>
						</td>
					<?}?>
				</tr>
			</table>
		</div>
        <div style="clear: both;">
            <input type="hidden" name="action" value="update_price" />
            <input  type="submit" value="update_price" class="formButton2" />
        </div>
		</form>






        </div>
	<?}?>

<?}?>

 
<div align="center"><?=$TMPL_navbar?></div>

<script>
    $( document ).ready(function() {
        $(".prices-container").css('width',($('#column_width').width()-120)+'px');
        $(".content-div").css('width',$('#column_width').width()+'px');

        $(".close-but").click(function () {

            $header = $(this);
            //getting the next element
            $content = $header.next();
            //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
            $content.slideToggle(50, function () {
                //execute this after slideToggle is done
                //change text of header based on visibility of content div
                $header.text(function () {
                    //change text based on condition
                    //return $content.is(":visible") ? "Collapse" : "Expand";
                });
            });
        });
    });
</script>