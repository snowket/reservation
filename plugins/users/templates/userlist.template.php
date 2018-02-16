<?if(!defined('ALLOW_ACCESS')) exit;?>
<div style="border:solid #347cc8 2px; padding: 4px; color:#3a82cc"><?=$TEXT['groups']['notes']['g'.$_GET['gid']]?></div>
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="margin-bottom:10px">
 <tr>
  <td class="sectionName"><?=$TMPL_grname?></td>
  <td align="right">
    <img src="./images/icos16/add.gif" width="16" height="16" border="0" align="middle">&nbsp;
    <a href="<?=$_SERVER['PHP_SELF']?>?m=users&action=new_member&gid=<?=$TMPL_gid?>" class="basic">
      <?=$TEXT['users']['new_member']?>
    </a>
  </td>
 </tr>
</table>
<table width="100%" cellpadding="2" cellspacing="0" border="0" class="text1" style="margin-bottom:20px">
  <tr>
    <td class="border_gray1" height="20" valign="top">
      <img src="./plugins/users/images/user_group.gif" width="16" height="16" align="left" border="0">
      <span class="text_black"><?=$TEXT['users']['members']?></span>
    </td>
    <td class="border_gray1" valign="top" style="border-bottom:1px solid #E5E6EE">
      <img src="./plugins/users/images/mail.gif" width="14" height="14" align="left" border="0">
      <span class="text_black"><?=$TEXT['users']['email']?></span>
    </td>
    <td class="border_gray1" valign="top">
      <img src="./plugins/users/images/cal.gif" width="14" height="14" align="left" border="0">
      <span class="text_black"><?=$TEXT['users']['joined']?></span>
    </td>    
    <td class="border_gray1" valign="top">&nbsp;</td>
  </tr> 
 <? for($i=0;$i<count($TMPL_users);$i++){ ?>
   <tr>
     <td style="padding-left:8px">
       <a href="<?=$_SERVER['PHP_SELF']?>?m=users&action=edit_user&uid=<?=$TMPL_users[$i]['id']?>" class="basic">
          <b><?=$TMPL_users[$i]['login']?></b>
       </a>
     </td>
     <td style="padding-left:8px">
       <a href="mailto:<?=$TMPL_users[$i]['email']?>" class="text1">
         <?=$TMPL_users[$i]['email']?>
       </a> 
     </td>
     <td style="padding-left:8px">
        <?=$TMPL_users[$i]['joined']?> 
     </td>
     <td align="right">
      <? if($TMPL_users[$i]['id']>1){?>
        <a href="<?=$_SERVER['PHP_SELF']?>?m=users&action=change_status&uid=<?=$TMPL_users[$i]['id']?>">
          <img src="./images/icos16/protect-<?=$TMPL_users[$i]['publish']==1?'green':'red'?>.gif" width="16" height="16" border="0" align="middle" alt="edit"></a>
        &nbsp;&nbsp;  
        <a href="<?=$_SERVER['PHP_SELF']?>?m=users&action=edit_user&uid=<?=$TMPL_users[$i]['id']?>">
          <img src="./images/icos16/edit.gif" width="16" height="16" border="0" align="middle" alt="edit"></a>
        &nbsp;&nbsp;
        <a href="<?=$_SERVER['PHP_SELF']?>?m=users&action=del_user&uid=<?=$TMPL_users[$i]['id']?>&gid=<?=$TMPL_gid?>">
          <img src="./images/icos16/delete.gif" width="16" height="16" border="0" align="middle" alt="delete"></a>
         
      <? }else{ ?> 
        <img src="./images/icos16/blocked.gif" width="16" height="16" border="0">
      <? } ?> 
     </td>
   </tr>
 <?}?>
</table> 
  