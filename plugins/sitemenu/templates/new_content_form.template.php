<? if(!defined('ALLOW_ACCESS'))  exit; ?>
<div style="margin:20px;width:460px">
 <form action="" method="post" id="content_form" onsubmit="mBuilder.createNewContent();return false;">
   <table width="100%" border="0" cellspacing="0" cellpadding="5">
     <tr>
        <td width="100"><?=$TEXT['global']['title']?></td>
        <td><input type="text" name="title" value="" class="input_text" style="width:99%"></td>
     </tr>  
     <tr>
        <td>&nbsp;</td>
        <td>
           <input type="button" class="cancelButton" value="<?=$TEXT['global']['cancel']?>" onclick="mBuilder.closeContentWin()">&nbsp;&nbsp;&nbsp;
           <input type="button" class="saveButton" value="   <?=$TEXT['global']['add']?>   "  onclick="this.disabled=true; mBuilder.createNewContent();">
        </td>
     </tr>
   </table>
   <input type="hidden" name="lang" value="<?=$TPL_lang?>">
   <input type="hidden" name="action" value="add">
 </form>
</div> 
