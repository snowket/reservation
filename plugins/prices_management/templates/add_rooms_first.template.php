<div style="width:100%;" class="tdrow1">
<form action="" method="post" enctype="multipart/form-data">
  <table border="0" cellspacing="0" cellpadding="2" class="text1" style="width:90%; margin:20px; background:url('./plugins/products/images/basket.gif') no-repeat right bottom;">

	<?if($TMPL_errors){?>
	<tr><td colspan="2">
		<table border="0" align="center"><tr><td class="err"><?=$TMPL_errors?></td></tr></table>
	</td></tr>
	<?}?>
	<tr>
		<td width="150" ><b><label for="one_person_discount"><?=$TEXT['one_person_discount']?></label></b></td>
		<td>
			<input class="formField1" id="one_person_discount" type="checkbox" name="one_person_discount" value="1" <?=$TMPL_item['one_person_discount']?'checked':''?>>
		</td>
	</tr>
	<tr>
		<td><b><?=$TEXT['block_id']?></b></td>
		<td>
			<select name="block_id" class="formField1" style="width:30%;">
				<option></option>
				<? for ($i=0;$i<count($TMPL_blocks);$i++)  {
					$selected='';
					$selected=$TMPL_item['block_id']==$TMPL_rc[$i]['id']?"selected":"";
					echo '<option value="'.$TMPL_blocks[$i]['id'].'"  '.$selected.'>'.$TMPL_blocks[$i]['title'].'</option>';
				?>
				<? } ?>
			</select><span class="redB">!</span>
		</td>
	</tr> 	
	<tr>
		<td><b><?=$TEXT['type_id']?></b></td>
		<td>
			<select name="type_id" class="formField1" style="width:30%;">
				<option></option>
				<? for ($i=0;$i<count($TMPL_types);$i++)  {
					$selected='';
					$selected=$TMPL_item['type_id']==$TMPL_types[$i]['id']?"selected":"";
					echo '<option value="'.$TMPL_types[$i]['id'].'"  '.$selected.'>'.$TMPL_types[$i]['title'].'</option>';
				?>
				<? } ?>
			</select><span class="redB">!</span>
		</td>
	</tr> 
				
    <tr>
		<td><b><?=$TEXT['capacity_id']?></b></td>
		<td>
			<select name="capacity_id" class="formField1" style="width:30%;">
				<option></option>
				<? for ($i=0;$i<count($TMPL_rc);$i++)  {
					$selected='';
					$selected=$TMPL_item['capacity_id']==$TMPL_rc[$i]['id']?"selected":"";
					echo '<option value="'.$TMPL_rc[$i]['id'].'"  '.$selected.'>'.$TMPL_rc[$i]['title'].'</option>';
				?>
				<? } ?>
			</select><span class="redB">!</span>
		</td>
	</tr> 
	<tr>
		<td width="150" ><b><?=$TEXT['quantity']?></b></td>
		<td>
			<input class="formField1" type="text" name="quantity" style="width:80%" value="<?=$TMPL_item['quantity']?>"><span class="redB">!</span>
		</td>
	</tr>
	<tr>
		<td><b><?=$TEXT['default_services']?></b></td>
		<td>
			<select name="default_services[]" class="formField1" style="width:320px; height:100px;" multiple>
				<option></option>
				<? for ($i=0;$i<count($TMPL_ds);$i++){
					$selected=in_array($TMPL_ds[$i]['id'],explode(',',$TMPL_item['default_services']))?"selected":"";
					echo '<option value="'.$TMPL_ds[$i]['id'].'" '.$selected.'>'.$TMPL_ds[$i]['title'].'</option>';
				?>
				<? } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td><b><?=$TEXT['extra_services']?></b></td>
		<td>
			<select name="extra_services[]" class="formField1" style="width:320px; height:100px;" multiple>
				<option></option>
				<? for ($i=0;$i<count($TMPL_es);$i++){
					$selected=in_array($TMPL_es[$i]['id'],explode(',',$TMPL_item['extra_services']))?"selected":"";
					echo '<option value="'.$TMPL_es[$i]['id'].'" '.$selected.'>'.$TMPL_es[$i]['title'].'</option>';
				?>
				<? } ?>
			</select>
		</td>
	</tr>	
	
	<? for($i=0;$i<count($TMPL_lang);$i++){ ?> 
	<tr>
		<td width="200"><b><?=$TEXT['intro']?></b> (<?=$TMPL_lang[$i]?>)</td>
		<td nowrap><textarea type="text" name="intro[<?=$TMPL_lang[$i]?>]"class="formField1" style="width:98%; height:100px;"><?=$TMPL_intro[$TMPL_lang[$i]]?></textarea></td>
	</tr>
	<?}?>	

	<tr>  
		<td><b><?=$TEXT['image']?></b></td>
		<td>
			<?if(empty($TMPL_item['img'])){?>
				<input type="file" class="formField1" name="image" style="width:80%">
			<?}else{?>
				<img src="<?=$TMPL_item['img']?>" border="0">&nbsp;
				<a href="<?=$SELF?>&action=delpic&rec_id=<?=$TMPL_item['id']?>"><img src="./images/icos16/delete.gif" width="16" height="16" border="0"></a>
			<?}?>
		</td>
	</tr>
	
	<tr>
		<td style="padding-top:4px;text-align:right">&nbsp;</td>
		<td><input type="submit" value="  <?=$TEXT['global']['add']?>  " class="formButton2"></td>
	</tr>
  </table> 
  <input type="hidden" name="action" value="add">
</form>
</div>