<?if(!defined('ALLOW_ACCESS')) exit;?>
<div style="width:100%;text-align:right;">
  <img src="./images/icos16/add.gif" width="16" height="16" border="0" align="middle">&nbsp;
  <a href="<?=$_SERVER['PHP_SELF']?>?m=users&action=new_group" class="basic">
    <?=$TEXT['users']['new_group']?>
  </a>
</div>
<table width="100%" cellpadding="2" cellspacing="0" border="0" class="text1" style="margin-bottom:20px">
  <tr>
    <td class="border_gray1" height="20" valign="top">
      <img src="./plugins/users/images/user_group.gif" width="16" height="16" align="left" border="0">
      <span class="text_black"><?=$TEXT['users']['group_title']?></span>
    </td>
    <td class="border_gray1" valign="top">
      <img src="./plugins/users/images/user_blue.gif" width="16" height="16" align="left" border="0">
      <span class="text_black"><?=$TEXT['users']['members']?></span>
    </td>
    <td class="border_gray1" valign="top">&nbsp;</td>
  </tr> 
 <? for($i=0;$i<count($TMPL_groups);$i++){ ?>
   <tr>
     <td style="padding-left:8px">
       <a href="<?=$_SERVER['PHP_SELF']?>?m=users&action=show_users&gid=<?=$TMPL_groups[$i]['id']?>" class="basic">
          <b><?=$TMPL_groups[$i]['title']?></b>
       </a>
     </td>
     <td style="padding-left:16px"><?=$TMPL_groups[$i]['num']?></td>
     <td align="right">
      <? if($TMPL_groups[$i]['id']>1 && IS_SUPER_ADMIN){?>
        <a href="<?=$_SERVER['PHP_SELF']?>?m=users&action=edit_group&gid=<?=$TMPL_groups[$i]['id']?>">
          <img src="./images/icos16/edit.gif" width="16" height="16" border="0" align="middle" alt="edit"></a>
        &nbsp;&nbsp;
        <a href="<?=$_SERVER['PHP_SELF']?>?m=users&action=del_group&gid=<?=$TMPL_groups[$i]['id']?>">
          <img src="./images/icos16/delete.gif" width="16" height="16" border="0" align="middle" alt="delete"></a>
         
      <? }else{ ?> 
        <img src="./images/icos16/blocked.gif" width="16" height="16" border="0">
      <? } ?> 
     </td>
   </tr>
 <?}?>
</table> 
  