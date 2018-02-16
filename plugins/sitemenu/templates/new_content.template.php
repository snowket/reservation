
<? if(!defined('ALLOW_ACCESS'))  exit; ?>
<?p($TEXT);?>
<div id ="">
   <form action="" method="post" name="content_editform" id="content_editform">
     <table width="400" border="0" cellspacing="0" cellpadding="5" style="margin:20px">
       <tr>
          <td width="100"><?=$TEXT['title']?></td>
          <td><input type="text" name="title" id="content_title" value="" class="input_text" style="width:100%"></td>
       </tr>  
       <tr>
          <td>&nbsp;</td>
          <td>
             <input type="button" class="applyButton" value="<?=$TEXT['global']['edit']?>"  onclick="this.disabled=true; saveIblock();">&nbsp;
             <input type="button" class="cancelButton" value="<?=$TEXT['global']['cancel']?>" onclick="w2.close()">
          </td>
       </tr>
     </table>
     <textarea name="text" style="width:100%;height:400px"  id="content_text"><?=$TPL_item['title']?></textarea>
     <input type="hidden" name="rec_id" id="content_id" value="">
     <input type="hidden" name="lang" id="content_lang" value="">
     <input type="hidden" name="action" id="content_action" value="">
     <input type="hidden" name="callback" value="editIblock">
   </form>
</div>

<script type="text/javascript">
 tinymceLoad('content_text');
</script> 
