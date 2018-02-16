<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td style="width:64%;" class="tdrow1">
	<form name="prodForm" action="<?=$SELF?>" method="post" enctype="multipart/form-data">
	<table border="0" width="100%" cellspacing="0" cellpadding="5">
		<tr>
			<td width="200" valign="top">
			<?if($TMPL_item['introimg']){?>
			 <div style="120px;text-align:center;">
				  <img src="<?=$TMPL_item['introimg']?>" <? $dim = @getImageSize($TMPL_item['introimg']); echo $dim[3]; ?> border="0" vspace="4"><br>
				  <div style="margin-top:5px"> 
				     <a href="<?=$SELF?>&action=delimg&id=<?=$TMPL_item['id']?>" class="basic">[<?=$TEXT['global']['delete']?>]</a>
				  </div>  
			 </div>
			<?}else{?>
				<input type="file" name="image" class="formField1" style="width:120px">
			<?}?>  
				<div>
					<a href="<?=$SELF?>&tab=gallery&rec_id=<?=$TMPL_item['id']?>" class="basic"><b><?=$TEXT['gall']?></b></a>
				</div>
			</td>
			<td align="right" valign ="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="2" class="text1">
				<?if($TMPL_errors){?>
				<tr>
					<td>&nbsp;</td>
					<td class="err"><?=$TMPL_errors?></td>
				</tr>
				<?}?>
				<tr>
					<td><b><?=$TEXT['block_id']?></b></td>
					<td>
						<select name="block_id" class="formField1" style="width:90%;">
							<? foreach ($TMPL_blocks as $block)  {
								$selected='';
								$selected=$TMPL_item['block_id']==$block['id']?"selected":"";
								echo '<option value="'.$block['id'].'"  '.$selected.'>'.$block['title'].'</option>';
							?>
							<? } ?>
						</select><span class="redB">!</span>
					</td>
				</tr> 	
				<tr>
					<td><b><?=$TEXT['type_id']?></b></td>
					<td>
						<select name="type_id" class="formField1" style="width:90%;">
							<option>1</option>
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
						<select name="capacity_id" class="formField1" style="width:90%;">
							<option>1</option>
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
					<td><b><?=$TEXT['default_services']?></b></td>
					<td>
						<select name="default_services[]" class="formField1" style="width:320px; height:100px;" multiple>
							<?
                            for ($i=0;$i<count($TMPL_es);$i++){
                                if($TMPL_es[$i]['type_id']!=8){continue;}
								$selected=in_array($TMPL_es[$i]['id'],explode(',',$TMPL_item['default_services']))?"selected":"";
								echo '<option value="'.$TMPL_es[$i]['id'].'" '.$selected.'>'.$TMPL_es[$i]['title'].'</option>';
                            } ?>
						</select>
					</td>
				</tr>
				<tr>
                    <td><b><?=$TEXT['food_services']?></b></td>
                    <td>
                        <select name="food_services[]" class="formField1" style="width:320px; height:100px;" multiple>
                            <? for ($i=0;$i<count($TMPL_es);$i++){
                                if($TMPL_es[$i]['type_id']!=6){continue;}
                                $selected=in_array($TMPL_es[$i]['id'],explode(',',$TMPL_item['food_services']))?"selected":"";
                                echo '<option value="'.$TMPL_es[$i]['id'].'" '.$selected.'>'.$TMPL_es[$i]['title'].'</option>';
                            ?>
                            <? } ?>
                        </select>
                    </td>
                </tr>

				<!--tr>
					<td><b><?=$TEXT['extra_services']?></b></td>
					<td>
						<select name="extra_services[]" class="formField1" style="width:300px; height:100px;" multiple>
							<? for ($i=0;$i<count($TMPL_es);$i++){
                                if($TMPL_es[$i]['type_id']==8 || $TMPL_es[$i]['type_id']==6){continue;}
								$selected=in_array($TMPL_es[$i]['id'],explode(',',$TMPL_item['extra_services']))?"selected":"";
								echo '<option value="'.$TMPL_es[$i]['id'].'" '.$selected.'>'.$TMPL_es[$i]['title'].'</option>';
							?>
							<? } ?>
						</select>
					</td>
				</tr-->
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value=" <?=$TEXT['global']['edit']?> " class="formButton2"></td>
				</tr>       
			</table>   
			<input type="hidden" name="lang" value="<?=$TMPL_item['lang']?>">
			<input type="hidden" name="id" value="<?=$TMPL_item['id']?>">
			<input type="hidden" name="action" value="edit">
			</td>
		</tr>
	</table>
	</form>
</td>
</tr>
</table>

