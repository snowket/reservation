<?if(!defined('ALLOW_ACCESS')) exit;?>
<form action="" method="post">
 <span class="sectionName">
  <?=$TEXT['content']['new_content']?>
</span> <br><br>
 <table width="100%" cellspacing="2" cellpadding="2" border="0" class="border_gray1">
   <tr>
     <td width="100" valign="top" class="text1"><b><?=$TEXT['global']['title']?></b></td>
     <td valign="top" class="text1">
       <input class="formField1" type="text" name="title" style="width:100%" value="">
     </td>     
     <td valign="top">
       <input type="submit" value=" <?=$TEXT['global']['add']?> " class="formButton3">
     </td>     
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td valign="top" class="text1" style="padding-bottom:10px">
        <input type="checkbox" name="search" value="1" checked>&nbsp;<?=$TEXT['content']['allow_search']?>
     </td>     
     <td valign="top">&nbsp;</td>     
   </tr>
 </table>
 <input type="hidden" name="action" value="add">
</form> 

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="text1" style="margin-top:20px">
 <? for($i=0;$i<count($TMPL_item);$i++){ ?>
     <tr onmouseover="this.style.background='#F2F2F2'" onmouseout="this.style.background='#F8F8F8'">
       <td nowrap><?=$TMPL_item[$i]['title']?></td>
       <td nowrap width="200" align="right">
         <? for($j=0;$j<count($_CONF['langs_all']);$j++){ ?>
             <a href="<?=$SELF?>&action=edit&cid=<?=$TMPL_item[$i]['id']?>&l=<?=$_CONF['langs_all'][$j]?>">[<?=$_CONF['langs_all'][$j]?>]</a>&nbsp;
         <? } ?>
       </td>
       <td width="20" align="right">
         <? if($TMPL_item[$i]['block']){ ?>
            <img src="./images/icos16/blocked.gif" width="16" height="16" border="0">
         <?}else{?>
           <a href="<?=$SELF?>&action=delete&cid=<?=$TMPL_item[$i]['id']?>" onclick="return confirm('are you shure?')">
             <img src="./images/icos16/delete.gif" width="16" height="16" border="0">
           </a>
         <?}?>
       </td>
     </tr>
 <? } ?>
</table>
