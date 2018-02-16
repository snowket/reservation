<form action="" method="post" enctype="multipart/form-data">
<table border="0" width="100%" cellspacing="0" cellpadding="5" class="text_black border_gray2">
  <? for($i=0,$n=count($TMPL_items); $i<$n; $i++){?>
	<tr
		<? if($TMPL_items[$i]['promo']) { ?>
			onmouseover="this.style.background='#FFA4BD'"  onmouseout="this.style.background='#FFDBE5'" style="cursor:pointer; background:#FFDBE5;"
		<? } else {?>
			 onmouseover="this.style.background='#ECECEC'" onmouseout="this.style.background='#F8F8F8'" style="cursor:pointer;"
		<? }?>
	 >
		<td width="100" height="90" valign="top" class="border_gray1">
		<?if($TMPL_items[$i]['img']){?>
			<img src="<?=$TMPL_imgdir?>/thumb_<?=$TMPL_items[$i]['img']?>" border="0" vspace="4" width="90">
		<?}else{?>
		<?}?>
		</td>
		<td valign="top" style="padding-right:20px;" class="border_gray1">
			<a class="basic" href="<?=$SELF?>&action=edit&id=<?=$TMPL_items[$i]['id']?>">
				<b><?=$TMPL_items[$i]['title']?></b>
			</a><br>
			<?=$TMPL_items[$i]['intro']?>
		</td>
		<td align="right" valign="top" style="width:1%;" class="border_gray1" nowrap>
			<table cellspacing="0" cellpadding="0">
			</table>
			
			
			<a href="<?=$SELF?>&action=change_status&id=<?=$TMPL_items[$i]['id']?>"><img src="./images/icos16/<?=($TMPL_items[$i]['publish']==1?'protect-green':'protect-red')?>.gif" width="16" height="16" border="0" align="top" title="Enable/Disable Product"></a>
			
			<a href="<?=$SELF?>&action=edit&id=<?=$TMPL_items[$i]['id']?>"><img src="./images/icos16/edit.gif" width="16" height="16" border="0" align="top" title="Edit Product"></a>
			<?if($TMPL_items[$i]['blocked']){?>
				<img src="./images/icos16/blocked.gif" width="16" height="16" border="0" align="top">
			<? }else{ ?>
				<a onclick="return confirm('are you sure?')" href="<?=$SELF?>&action=delete&id=<?=$TMPL_items[$i]['id']?>"><img src="./images/icos16/delete.gif" width="16" height="16" border="0" align="top" title="Delete Product"></a>
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