<?if(!defined('ALLOW_ACCESS')) exit;?>
<table width="550" style="margin: 20px" border="0" cellspacing="0" cellpadding="2">
  <tr>
     <td valign="top" width="150">
        <div style="padding:2px;width:104px;border:1px solid #ccc"><img src="<?=$TMPL_img?>"  width="100" border="0"></div>
     </td>
     <td valign="top">
       <table width="100%" border="0" cellspacing="0" cellpadding="2" class="text1">
         <? for($i=0;$i<count($TMPL_lang);$i++){ ?>
            <tr>
              <td width="40"><?=$TMPL_lang[$i]?>:</td> 
              <td><b><?=$TMPL_title[$TMPL_lang[$i]]?></b></td>
            </tr>
         <?}?> 
       </table>
     </td>
  </tr>
</table>