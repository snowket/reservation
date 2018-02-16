<?if(!defined('ALLOW_ACCESS')) exit;?>
<table width="100%" border="0" cellpadding="0" cellspacing="2" style="font-size:11px;">
  <tr>
    <td width="100%" class="tab_devider">&nbsp;</td>
      <? for($i=0;$i<count($TMPL_lang);$i++){ ?>
         <td class="<?=($TMPL_lang[$i]==$TMPL_l)?'tab_active':'tab_inactive'?>">
           <a href="<?=$SELF?>&action=edit&cid=<?=$TMPL_cid?>&l=<?=$TMPL_lang[$i]?>" class="text1"><?=$TMPL_lang[$i]?></a>
         </td>  
      <?}?>
  </tr> 
</table>