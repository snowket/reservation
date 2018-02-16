<?if(!defined('ALLOW_ACCESS')) exit;?>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="text1" style="margin-top:20px">
 <tr>
   <td colspan="2" class="border_gray1" style="padding-bottom:10px;">
     <a href="<?=$SELF?>" title="back"><img src="./images/icos16/back.gif" width="16" height="16" border="0" align="absmiddle"></a>
     <b><?=$TMPL_menutitle?></b>
   </td>
 </tr>
 <? for($i=0;$i<count($TMPL_item);$i++){ ?>
     <tr onmouseover="this.style.background='#F2F2F2'" onmouseout="this.style.background='#F8F8F8'">
       <td nowrap style="padding-left:20px"><?=$TMPL_item[$i]['title']?></td>
       <td nowrap width="200" align="right">
         <? for($j=0;$j<count($_CONF['langs_all']);$j++){ ?>
             <a href="<?=$SERVER['PHP_SELF']?>?m=content&action=edit&cid=<?=$TMPL_item[$i]['id']?>&l=<?=$_CONF['langs_all'][$j]?>">[<?=$_CONF['langs_all'][$j]?>]</a>&nbsp;
         <? } ?>
       </td>
     </tr>
 <? } ?>
</table>