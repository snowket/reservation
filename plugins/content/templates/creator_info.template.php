<?if(!defined('ALLOW_ACCESS')) exit;?>
<div class="blockHeader">
  <span class="text1"><?=$TEXT['global']['creator_info']?></span>
</div>  
<table width="100%" border="0" cellspacing="0" cellpadding="3" class="text1">
 <tr>
   <td width="20"><img src="./images/icos16/user_blue.gif" width="16" height="16"></td>
   <td><?=$TMPL_creator['name']?$TMPL_creator['name']:$TEXT['global']['unknown']?></td>
 </tr>
 <tr>
   <td width="20"><img src="./images/icos16/mail.gif" border="0" width="16" height="16"></td>
   <td><?=$TMPL_creator['email']?"<a href=\"mailto: {$TMPL_creator['email']}\">".$TMPL_creator['email']."</>":$TEXT['global']['unknown']?></td>
 </tr>
</table>