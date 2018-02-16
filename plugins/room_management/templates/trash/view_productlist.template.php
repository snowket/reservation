<form action="" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="0" cellpadding="2" class="text_black border_gray2" style="display:none">
	<tr>
		<td valign="top" class="n_text">DataBase File:</td>
		<td><input type="file" name="dbf" style="border:1px Solid Silver; font-size:11px;"></td>
	</tr>
	<tr>
		<td valign="top" class="n_text">Related Columns names:&nbsp;</td>
		<td>
			<input type="text" name="column_name1" value="KOD_MED"   style="border:1px Solid Silver; font-size:11px; width:100px; text-align:center;">
			&nbsp;<-- AND -->&nbsp;
			<input type="text" name="column_name2" value="KOD_MANUF" style="border:1px Solid Silver; font-size:11px; width:100px; text-align:center;">
		</td>
	</tr>
	<tr>
		<td valign="top" class="n_text">Price Column name:&nbsp;</td>
		<td><input type="text" name="column_name3" value="PRICE_USD"   style="border:1px Solid Silver; font-size:11px; width:100px; text-align:center;">
		</td>
	</tr>
	<tr>
		<td valign="top" class="n_text">Multiply coefficient:&nbsp;</td>
		<td><input type="text" name="coefficient" value="1"   style="border:1px Solid Silver; font-size:11px; width:100px; text-align:center;">
		</td>
	</tr>
	<tr>
		<td valign="top" class="n_text">&nbsp;</td>
		<td>
				<input type="submit" name="submit" value="Update prices" style="border:1px Solid Silver;">
				<input type="checkbox" name="update_price_old" value="1" title="Update old price to">
		</td>
			<input type="hidden" name="action" value="update_prices">
	</tr>
</table>
</form>



<form action="" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="0" cellpadding="2" class="text_black border_gray2" style="">
	<tr>
		<td valign="top" class="n_text">DataBase File:</td>
		<td><input type="file" name="price_file" style="border:1px Solid Silver; font-size:11px;"></td>
		<td>
			<input type="submit" name="submit" value="Update prices" style="border:1px Solid Silver;">
			<input type="checkbox" name="update_price_old" value="1" title="Update old price to">
		</td>
			<input type="hidden" name="action" value="update_prices">
	</tr>
</table>
</form>






<table border="0" width="100%" cellspacing="0" cellpadding="5" class="text_black border_gray2"><tr><td>
	<?
		for($i=0;$i<10;$i++)
			echo '<a href="'.$SELF.'&tab=view&kw='.$i.'&param=title">&nbsp;'.$i.'&nbsp;</a>&nbsp;';
		echo '<br>';
		for($i=0,$n=count($TMPL_conf['letters_eng']); $i<$n; $i++)
			echo '<a href="'.$SELF.'&tab=view&kw='.$TMPL_conf['letters_eng'][$i].'&param=title">&nbsp;'.$TMPL_conf['letters_eng'][$i].'&nbsp;</a>&nbsp;';
		echo '<br>';
		for($i=0,$n=count($TMPL_conf['letters_geo']); $i<$n; $i++)
			echo '<a href="'.$SELF.'&tab=view&kw='.urlencode($TMPL_conf['letters_geo'][$i]).'&param=title">&nbsp;'.$TMPL_conf['letters_geo'][$i].'&nbsp;</a>&nbsp;';
		
	?>
</td></tr></table>

<form action="" method="post" enctype="multipart/form-data">
<table border="0" width="100%" cellspacing="0" cellpadding="5" class="text_black border_gray2">
  <? for($i=0;$i<count($TMPL_items);$i++){?>
	<tr
		<? if($TMPL_items[$i]['promo']) { ?>
			onmouseover="this.style.background='#FFA4BD'"  onmouseout="this.style.background='#FFDBE5'" style="cursor:pointer; background:#FFDBE5;"
		<? } else {?>
			 onmouseover="this.style.background='#ECECEC'" onmouseout="this.style.background='#F8F8F8'" style="cursor:pointer;"
		<? }?>
	>
		<td width="100" height="85" valign="top" class="border_gray1" style="padding: 0px 5px 0px 5px;">
		<?if($TMPL_items[$i]['img']){?>
			<div style="width:100px; height:90px; overflow:auto;"><img src="<?=$TMPL_imgdir?>/thumb_<?=$TMPL_items[$i]['img']?>" border="0" vspace="4"></div>
		<?}else{?>
			<img src="./plugins/products/images/nopic2.jpg" width="70" style="border:1px solid #ccc;">
		<?}?>
		</td>
		<td valign="top" style="padding-right:20px;" class="border_gray1">
			<a class="basic" href="<?=$SELF?>&action=edit&id=<?=$TMPL_items[$i]['id']?>">
				<b><?=$TMPL_items[$i]['title']?></b>
				<? if($TMPL_items[$i]['promo']) { ?><span style="color:red"><b> - PROMO PRODUCT </b><? } ?>
			</a><br>
			<table border="0" cellspacing="0" cellpadding="2">
				<tr><td>Price:&nbsp;</td><td><input type="text" name="price_id[<?=$TMPL_items[$i]['id']?>]" value="<?=$TMPL_items[$i]['price']?>" title="Price" maxlength="10" style="width:55px; font-size:11px; border:1px Solid Silver;"></td></tr>
				<tr><td>Product CODE: </td><td><input type="text" name="code[<?=$TMPL_items[$i]['id']?>]" value="<?=$TMPL_items[$i]['code']?>" class="formField1" style="width:100px; font-size:11px; border:1px Solid Silver;"></td></tr>
				<!-- <tr><td>Manufacturer CODE: </td><td><input type="text" name="mcode[<?=$TMPL_items[$i]['id']?>]" value="<?=$TMPL_items[$i]['mcode']?>" class="formField1" style="width:55px; font-size:11px; border:1px Solid Silver;"></td></tr> //-->
			</table>
		</td>
		<td align="right" valign="top" style="width:1%;" class="border_gray1" nowrap>
			<? /*
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td>Sort: &nbsp;</td>
					<td><input type="text" name="sort_id[<?=$TMPL_items[$i]['id']?>]" value="<?=$TMPL_items[$i]['sort_id']?>" title="Sort ID" maxlength="10" style="width:55px; margin-bottom:5px; font-size:11px; border:1px Solid Silver;"></td>
				</tr>
			</table>
			*/ ?>
			
			<a href="<?=$SELF?>&tab=view&action=change_status2&id=<?=$TMPL_items[$i]['id']?>"><img src="./images/<?=($TMPL_items[$i]['publish_basket']==1?'cart_green':'cart_red')?>.gif" width="16" height="16" border="0" align="top" title="Publish basket"></a>
			<a href="<?=$SELF?>&tab=view&action=change_status&id=<?=$TMPL_items[$i]['id']?>"><img src="./images/icos16/<?=($TMPL_items[$i]['publish']==1?'protect-green':'protect-red')?>.gif" width="16" height="16" border="0" align="top" title="Enable/Disable Product"></a>
			
			<a href="<?=$SELF?>&action=edit&id=<?=$TMPL_items[$i]['id']?>"><img src="./images/icos16/edit.gif" width="16" height="16" border="0" align="top" title="Edit Product"></a>
			<?if($TMPL_items[$i]['blocked']){?>
				<img src="./images/icos16/blocked.gif" width="16" height="16" border="0" align="top">
			<? }else{ ?>
				<a href="<?=$SELF?>&tab=view&action=delete&id=<?=$TMPL_items[$i]['id']?>"><img src="./images/icos16/delete.gif" width="16" height="16" border="0" align="top" title="Delete Product"></a>
			<?}?>
		</td>
	</tr>
  <?}?>
  
  <tr>
  	  <td align="right" colspan="3">
		<input type="submit" name="submit" value="update" class="formButton2">
		<input type="hidden" name="action" value="update_sort_id">
  	  </td>
  </tr>
</table>

</form>
<div style="width:100%;margin-top:20px;">
  <center><?=$TMPL_pagebar?></center>
</div>