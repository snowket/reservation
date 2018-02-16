<form action="" method="post" enctype="multipart/form-data">
 <table width="550" border="0" cellspacing="0" cellpadding="10" class="text1" align="center">
  <tr>
    <td colspan="3" class="border_gray1">
      <b><?=$TMPL_plugin?></b>
    </td>
  </tr>
  <tr>
   <td><?=$TEXT['setts']['th_size']?></td>
   <td>
      <input type="text" name="th_width" value="<?=$TMPL_setts['th_width']?>" class="formField1" style="width:40px"> x <input type="text" name="th_height" value="<?=$TMPL_setts['th_height']?>" class="formField1" style="width:40px"> px
   </td>
   <td>
       <? $TMPL_setts['th_method']=='c'?$checked2="checked":$checked1="checked"?>
       <input type="radio" name="th_method" value="r" <?=$checked1?> id="check_r"><label for="check_r"><?=$TEXT['setts']['resize']?></label><br>
       <input type="radio" name="th_method" value="c" <?=$checked2?> id="check_c"><label for="check_c"><?=$TEXT['setts']['crop']?></label>
    </td>
  </tr>
  <tr>
    <td><?=$TEXT['setts']['img_size']?></td>
    <td>
       <input type="text" name="img_width" value="<?=$TMPL_setts['img_width']?>" class="formField1" style="width:40px"> x <input type="text" name="img_height" value="<?=$TMPL_setts['img_height']?>" class="formField1" style="width:40px"> px
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?=$TEXT['setts']['g_th_size']?></td>
    <td>
      <input type="text" name="g_th_width" value="<?=$TMPL_setts['g_th_width']?>" class="formField1" style="width:40px"> x <input type="text" name="g_th_height" value="<?=$TMPL_setts['g_th_height']?>" class="formField1" style="width:40px"> px
    </td>
    <td>
       <? $TMPL_setts['g_method']=='c'?$checked2="checked":$checked1="checked"?>
       <input type="radio" name="g_method" value="r" <?=$checked1?> id="g_check_r"><label for="g_check_r"><?=$TEXT['setts']['resize']?></label><br>
       <input type="radio" name="g_method" value="c" <?=$checked2?> id="g_check_c"><label for="g_check_c"><?=$TEXT['setts']['crop']?></label>
    </td>
  </tr>
  <tr>
    <td><?=$TEXT['setts']['g_size']?></td>
   <td><input type="text" name="g_width" value="<?=$TMPL_setts['g_width']?>" class="formField1" style="width:40px"> x <input type="text" name="g_height" value="<?=$TMPL_setts['g_height']?>" class="formField1" style="width:40px"> px</td>
   <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?=$TEXT['setts']['levels']?></td>
    <td>
       <input type="text" name="levels" value="<?=$TMPL_setts['levels']?>" class="formField1" style="width:40px"> 
    </td>
    <td>&nbsp;</td>
  </tr> 
  
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">
       <?$checked=$TMPL_setts['with_cats']==1?"checked":""?>
       <input type="checkbox" name="with_cats" id="with_cats" value="1" <?=$checked?>>&nbsp;
       <label for="with_cats"><?=$TEXT['setts']['use_cats']?></label>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">
       <?$checked=$TMPL_setts['add_search']==1?"checked":""?>
       <input type="checkbox" name="add_search" id="add_search" value="1" <?=$checked?>>&nbsp;
       <label for="add_search"><?=$TEXT['setts']['add_search']?></label>
    </td>
  </tr>
  <tr>
    <td><label for="wysiwyg" style="cursor:pointer;">Enable <b>WYSIWYG</b>?</label></td>
    <td colspan="2">
       <?$checked=$TMPL_setts['wysiwyg']==1?"checked":""?>
       <input type="checkbox" name="wysiwyg" id="wysiwyg" value="1" <?=$checked?>>&nbsp;
    </td>
  </tr>
  <tr>
    <td class="border_gray1">&nbsp;</td>
    <td class="border_gray1" colspan="2"><input type="submit" value="<?=$TEXT['global']['save']?>"  class="formButton2"></td>
  </tr>
 </table>
 <input type="hidden" name="action" value="save"> 
 <input type="hidden" name="pid" value="<?=$TMPL_pid?>">
</form> 