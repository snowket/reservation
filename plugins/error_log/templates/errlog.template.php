<?if(!defined('ALLOW_ACCESS')) exit;?>
<div style="width:95%;height:350px;padding:10px; border:1px solid #E5E6EE;background:#fff;overflow:auto;" class="text1">
  <?=$TMPL_errors?>
</div>
<form action="" method="post">
 <div style="width:95%; padding:5px; text-align:right;">
   <input type="submit" value="<?=$TEXT['errlog']['clean_log']?>"  class="formButton2">
 </div>  
 <input type="hidden" name="action" value="clearlog">
</form>
