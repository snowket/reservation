<script language="javascript" src="./tiny_mce/tiny_mce.js"></script>
<script language="javascript" src="./js/mce_init.js"></script>


<div style="width:100%;" class="tdrow1">
<form action="" method="post" enctype="multipart/form-data">
  <table border="0" cellspacing="0" cellpadding="2" class="text1" style="width:90%; margin:20px;">

	<?if($TMPL_errors){?>
	<tr>
	    <td colspan="2">
		    <table border="0" align="center">
                <tr>
                    <td class="err">
                        <?=$TMPL_errors?>
                    </td>
                </tr>
            </table>
	    </td>
    </tr>
	<?}?>
	<tr>
		<td><b><?=$TEXT['block_id']?></b></td>
		<td>
			<select name="block_id" class="formField1" style="width:30%;">
				<? foreach ($TMPL_blocks as $block)  {
					$selected='';
					$selected=$TMPL_item['block_id']==$TMPL_rc[$i]['id']?"selected":"";
					echo '<option value="'.$block['id'].'"  '.$selected.'>'.$block['title'].'</option>';
				?>
				<? } ?>
			</select><span class="redB">!</span>
		</td>
	</tr>
	<tr>
		<td><b><?=$TEXT['type_id']?></b></td>
		<td>
			<select name="type_id" class="formField1" style="width:30%;">
				<? foreach ($TMPL_types as $TMPL_type)  {
					$selected='';
					$selected=$TMPL_item['type_id']==$TMPL_type['id']?"selected":"";
					echo '<option value="'.$TMPL_type['id'].'"  '.$selected.'>'.$TMPL_type['title'].'</option>';
				?>
				<? } ?>
			</select><span class="redB">!</span>
		</td>
	</tr> 
				
    <tr>
		<td><b><?=$TEXT['capacity_id']?></b></td>
		<td>
			<select name="capacity_id" class="formField1" style="width:30%;">
				<? foreach ($TMPL_rc as $rc)  {
					$selected='';
					$selected=$TMPL_item['capacity_id']==$rc['id']?"selected":"";
					echo '<option value="'.$rc['id'].'"  '.$selected.'>'.$rc['title'].'</option>';
				?>
				<? } ?>
			</select><span class="redB">!</span>
		</td>
	</tr> 
	<tr>
		<td width="150" ><b><?=$TEXT['quantity']?></b></td>
		<td>
			<input class="formField1" type="number" name="quantity" style="width:80px" min="1" value="<?=($TMPL_item['quantity'])?$TMPL_item['quantity']:1?>"><span class="redB">!</span>
		</td>
	</tr>
	<tr>
		<td><b><?=$TEXT['default_services']?></b></td>
		<td>
			<select name="default_services[]" class="formField1" style="width:320px; height:100px;" multiple>
				<? for ($i=0;$i<count($TMPL_ds);$i++){
                    if($TMPL_ds[$i]['type_id']!=8){continue;}
					$selected=in_array($TMPL_ds[$i]['id'],explode(',',$TMPL_item['default_services']))?"selected":"";
					echo '<option value="'.$TMPL_ds[$i]['id'].'" '.$selected.'>'.$TMPL_ds[$i]['title'].'</option>';
				?>
				<? } ?>
			</select>
		</td>
	</tr>
	<tr>
        <td><b><?=$TEXT['food_services']?></b></td>
        <td>
            <select name="food_services[]" class="formField1" style="width:320px; height:100px;" multiple>
                <? for ($i=0;$i<count($TMPL_ds);$i++){
                    if($TMPL_ds[$i]['type_id']!=6){continue;}
                    $selected=in_array($TMPL_ds[$i]['id'],explode(',',$TMPL_item['food_services']))?"selected":"";
                    echo '<option value="'.$TMPL_ds[$i]['id'].'" '.$selected.'>'.$TMPL_ds[$i]['title'].'</option>';
                ?>
                <? } ?>
            </select>
        </td>
    </tr>

	<? for($i=0;$i<count($TMPL_lang);$i++){ ?> 
	<tr>
		<td width="200"><b><?=$TEXT['intro']?></b> (<?=$TMPL_lang[$i]?>)</td>
		<td nowrap><textarea type="text" name="intro[<?=$TMPL_lang[$i]?>]"  class="formField1 mceEditor" style="width:98%; height:100px;"><?=$TMPL_intro[$TMPL_lang[$i]]?></textarea></td>
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