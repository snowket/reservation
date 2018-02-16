
<?if(!defined('ALLOW_ACCESS')) exit;?>

<tr onMouseOver	="javascript:if (this.style.backgroundColor !=  Color_click) {this.style.backgroundColor=Color_over;}"
	onMouseOut	="javascript:if (this.style.backgroundColor != Color_click) {this.style.backgroundColor=Color_out;}"
	onMouseDown	="setClickColor(this);">
  <td width="11">
    <?if($TMPL_state){?>
     <a href="javascript:void(0)" class="title1" onclick="expand('sitemenu<?=$TMPL_div_id?>')">
     	 <img id="imgsitemenu<?=$TMPL_div_id?>" status="off" src="images/plus.gif" width="11" height="11" border="0"></a>	 	    
    <?}else{?>
     <img src="images/spacer.gif" width="11" height="11" border="0">	 
    <?}?>     	  
  </td>
  <td width="70%" nowrap>
  	 <?=$TMPL_row['spacer']?><a href="<?=$SELF?>&tab=<?=$TMPL_tab?>&action=browse&cid=<?=$TMPL_row['cat_id']?>" class="basic"><?=$TMPL_row['name']?></a>   	  
  </td>  	  
  <td width="60" style="font-size:11px;color:#444;padding-top:7px;" nowrap>?m=<?=$TMPL_row['cat_id']?></td>
  <td width="40" nowrap><a href="javascript:void(0);" class="basic" onclick="window.clipboardData.setData('Text', '?m=<?=$TMPL_row['cat_id']?>');">[copy]</a></td>
  	  
  <td width="16" style="padding-left:5px;">
    <a href="<?=$SELF?>&tab=<?=$TMPL_tab?>&action=up&cid=<?=$TMPL_row['cat_id']?>"><img src="./images/icos16/up.gif" width="16" height="16" border="0" alt="Move up"></a>
  </td>
  <td width="16" style="padding-left:5px;">    
    <a href="<?=$SELF?>&tab=<?=$TMPL_tab?>&action=down&cid=<?=$TMPL_row['cat_id']?>"><img src="./images/icos16/down.gif" width="16" height="16" border="0" alt="Move down"></a>
  </td>
  <td width="16" style="padding-left:5px;">
	<? if ($TMPL_row['cat_level']<5) { ?>
    	<a href="<?=$SELF?>&tab=<?=$TMPL_tab?>&action=add&cid=<?=$TMPL_row['cat_id']?>"><img src="images/icos16/add.gif" width="16" height="16" border="0" alt="Make submenu"></a>
	<? } else { ?>
		<img src="/images/blank.gif" width="16" height="16" border="0" alt="Make submenu">
	<? } ?>
  </td>
  <td width="16" style="padding-left:5px;">    
    <a href="<?=$SELF?>&tab=<?=$TMPL_tab?>&action=edit&cid=<?=$TMPL_row['cat_id']?>"><img src="images/icos16/edit.gif" width="16" height="16" border="0" alt="Edit submenu"></a>
  </td>
  <td width="16" style="padding-left:5px;">    
    <a href="<?=$SELF?>&tab=<?=$TMPL_tab?>&action=change_status&cid=<?=$TMPL_row['cat_id']?>"><img src="./images/icos16/<?=($TMPL_row['publish']==1?'protect-green':'protect-red')?>.gif" width="16" height="16" border="0" alt="Switch visibility"></a>
    <? if($TMPL_row['blocked']==0){ ?>
  </td>
  <td width="16" style="padding-left:5px;">          
    <a href="<?=$SELF?>&tab=<?=$TMPL_tab?>&action=delete&cid=<?=$TMPL_row['cat_id']?>">
      <img src="./images/icos16/delete.gif" width="16" height="16" border="0" alt="Delete"></a><? }else{?></td>
  <td width="16" style="padding-left:5px;">     	
      <img src="./images/icos16/blocked.gif" width="16" height="16" border="0" alt="Blocked"><?}?></td> 	  
</tr>
<?
if($TMPL_code)
{
?>
<tr>
  <td colspan="10">
    <?=$TMPL_code?>
  </td>
</tr>
<?
}	  	  
?>