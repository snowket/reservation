<?if(!defined('ALLOW_ACCESS')) exit;?>
<form action="" method="post" enctype="multipart/form-data">
 <table width="500" border="0" cellspacing="0" cellpadding="5" class="text1" align="center">
  <tr>
    <td colspan="2" class="border_gray1">
      <b><?=$TMPL_plugin?></b>
    </td>
  </tr>
  <tr>
    <td><?=$TEXT['setts']['th_size']?></td>
    <td>
       <input type="text" name="th_width" value="<?=$TMPL_setts['th_width']?>" class="formField1" style="width:40px"> x <input type="text" name="th_height" value="<?=$TMPL_setts['th_height']?>" class="formField1" style="width:40px"> px
    </td>
  </tr>
  <tr>
    <td>
       <?=$TEXT['setts']['th_method']?>
    </td>
    <td>
       <? $TMPL_setts['th_method']=='c'?$th_checked2="checked":$th_checked1="checked"?>
       <input type="radio" name="th_method" value="r" <?=$th_checked1?> id="check_r1"><label for="check_r1"><?=$TEXT['setts']['resize']?></label><br>
       <input type="radio" name="th_method" value="c" <?=$th_checked2?> id="check_c1"><label for="check_c1"><?=$TEXT['setts']['crop']?></label>
    </td>
  </tr>
  
  <tr>
    <td><?=$TEXT['setts']['img_size']?></td>
    <td>
       <input type="text" name="img_width" value="<?=$TMPL_setts['img_width']?>" class="formField1" style="width:40px"> x <input type="text" name="img_height" value="<?=$TMPL_setts['img_height']?>" class="formField1" style="width:40px"> px
    </td>
  </tr>
  <tr>
    <td><?=$TEXT['setts']['img_method']?></td>
    <td>
       <? $TMPL_setts['img_method']=='c'?$img_checked2="checked":$img_checked1="checked"?>
       <input type="radio" name="img_method" value="r" <?=$img_checked1?> id="check_r2"><label for="check_r2"><?=$TEXT['setts']['resize']?></label><br>
       <input type="radio" name="img_method" value="c" <?=$img_checked2?> id="check_c2"><label for="check_c2"><?=$TEXT['setts']['crop']?></label>
    </td>
  </tr>
  <tr>
    <td><label for="with_cats" style="cursor:pointer;"><?=$TEXT['setts']['use_cats']?></label></td>
    <td>
       <?$checked=$TMPL_setts['with_cats']==1?"checked":""?>
       <input type="checkbox" name="with_cats" id="with_cats" value="1" <?=$checked?>>&nbsp;
       
    </td>
  </tr>
	<tr>
	<td><?=$TEXT['setts']['page_num']?></td>
	<td><input type="text" name="page_num" value="<?=$TMPL_setts['page_num']?>" class="formField1" style="width:50px"></td>
	</tr>
  <tr>
    <td class="border_gray1">&nbsp;</td>
    <td class="border_gray1"><input type="submit" value="<?=$TEXT['global']['save']?>"  class="formButton2"></td>
  </tr>
 </table>
 <input type="hidden" name="action" value="save"> 
 <input type="hidden" name="pid" value="<?=$TMPL_pid?>">
</form> 
