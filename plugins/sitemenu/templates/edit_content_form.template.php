
<? if(!defined('ALLOW_ACCESS'))  exit; ?>
<div style="padding:10px;display:block;width:880px" id="edit_content_div">
   <div style="text-align:right;padding:5px" id="content_langs">
      <?foreach($TMPL_langs as $lang){?>
          &nbsp;&nbsp;<a href="javascript: mBuilder.getContent('<?=$TMPL_id?>', '<?=$lang?>');" class="basic" id="content_lang_<?=$lang?>"><?=$lang?></a>
      <?}?> 
   </div>
   <form action="" method="post" name="content_editform" id="content_editform">
     <div>
        <textarea name="text" style="width:100%;height:400px"  id="content_text"><?=$TPL_item['title']?></textarea>
     </div> 
     <div style="text-align:right;margin-top:10px">
        <input type="button" class="cancelButton" value="<?=$TEXT['global']['cancel']?>" onclick="mBuilder.closeContentWin()">&nbsp;&nbsp; 
        <input type="button" class="applyButton"  value="<?=$TEXT['global']['edit']?>"  onclick="mBuilder.saveContent();">         			 
     </div>			 			 
     <input type="hidden" name="id" id="content_id" value="">
     <input type="hidden" name="lang" id="content_lang" value="">
     <input type="hidden" name="action" value="save">
   </form>
</div>

<script type="text/javascript">
 mBuilder.getContent("<?=$TMPL_id?>", "<?=$TMPL_lang?>");
</script> 
