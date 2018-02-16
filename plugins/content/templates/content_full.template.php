<?if(!defined('ALLOW_ACCESS')) exit;?>
<script language="javascript" src="./tiny_mce/tiny_mce.js"></script>
<script language="javascript" src="./js/mce_init.js"></script>
<form action="" method="post">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
     <td><?require_once(dirname(__FILE__)."/langbar.template.php");?></td>
   </tr>
   <tr>
     <td>
       <textarea name="content" style="width:100%;height:350px" class="mceEditor"><?=$TMPL_text?></textarea>
     </td>
   </tr>
   <tr>
     <td align="right">
       <input type="submit" value=" <?=$TEXT['global']['edit']?> " class="formButton2">
     </td>
   </tr>
 </table>
 <input type="hidden" name="cid" value="<?=$TMPL_cid?>">
 <input type="hidden" name="l" value="<?=$TMPL_l?>">
 <input type="hidden" name="action" value="save">
</form>