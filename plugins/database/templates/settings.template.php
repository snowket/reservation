<?if(!defined('ALLOW_ACCESS')) exit;?>
<form action="" method="post" enctype="multipart/form-data">
<table width="500" border="0" cellspacing="0" cellpadding="5" class="text1" align="center">
  <tr>
	<td colspan="2" class="border_gray1"><b><?=$TMPL_plugin?></b></td>
  </tr>
  <!-- Thumbnail size //-->
  <tr>
	<td><?=$TEXT['setts']['thumb_size']?></td>
	<td nowrap><input type="text" name="th_width" value="<?=$TMPL_setts['th_width']?>" class="formField1" style="width:40px"> x <input type="text" name="th_height" value="<?=$TMPL_setts['th_height']?>" class="formField1" style="width:40px"> px</td>
	<!-- Thumbnail method //-->
	<td nowrap>
		<? $TMPL_setts['method']=='c'?$checked2="checked":$checked1="checked"?>
		<input type="radio" name="method" value="r" <?=$checked1?> id="check_r"><label for="check_r"><?=$TEXT['setts']['resize']?></label><br>
		<input type="radio" name="method" value="c" <?=$checked2?> id="check_c"><label for="check_c"><?=$TEXT['setts']['crop']?></label>
	</td>
	<!-- Thumbnail method //-->
  </tr>
  <!-- Thumbnail size //-->
  
  
  <!-- Intro Image Size //-->
  <tr>
    <td><?=$TEXT['setts']['img_size']?></td>
    <td><input type="text" name="img_width" value="<?=$TMPL_setts['img_width']?>" class="formField1" style="width:40px"> x <input type="text" name="img_height" value="<?=$TMPL_setts['img_height']?>" class="formField1" style="width:40px"> px</td>
    <!-- Thumbnail method //-->
    <td>
       <? $TMPL_setts['img_method']=='c'?$img_checked2="checked":$img_checked1="checked"?>
       <input type="radio" name="img_method" value="r" <?=$img_checked1?> id="check_r2"><label for="check_r2"><?=$TEXT['setts']['resize']?></label><br>
       <input type="radio" name="img_method" value="c" <?=$img_checked2?> id="check_c2"><label for="check_c2"><?=$TEXT['setts']['crop']?></label>
    </td>
    <!-- Thumbnail method //-->
  </tr>
  <!-- Intro Image Size //-->
  
  
  <!-- Enable large image //-->
  <tr>
	<td><label for="with_img_large" style="cursor:pointer;">Enable large image</label></td>
	<td>
		<?$checked=$TMPL_setts['with_img_large']==1?"checked":""?>
		<input type="checkbox" name="with_img_large" id="with_img_large" value="1" <?=$checked?>>&nbsp;
	</td>
  </tr>
  <!-- Enable large image //-->
  <!-- Calendar min YEAR //-->
  <tr>
	<td><?=$TEXT['setts']['cal_min']?></td>
	<td><input type="text" name="cal_min" value="<?=$TMPL_setts['cal_min']?>" class="formField1" style="width:40px"></td>
  </tr>
  <!-- Calendar min YEAR //-->
  <!-- Calendar max YEAR //-->
  <tr>
	<td><?=$TEXT['setts']['cal_max']?></td>
	<td><input type="text" name="cal_max" value="<?=$TMPL_setts['cal_max']?>" class="formField1" style="width:40px"></td>
  </tr>
  <!-- Calendar max YEAR //-->
  <!-- Max page_num //-->
  <tr>
	<td>Max page_num</td>
	<td><input type="text" name="page_num" value="<?=$TMPL_setts['page_num']?>" class="formField1" style="width:40px"></td>
  </tr>
  <!-- MAX page_num //-->
  <!-- Enable using categories //-->
  <tr>
	<td><label for="with_cats" style="cursor:pointer;"><?=$TEXT['setts']['use_cats']?></label></td>
	<td>
		<?$checked=$TMPL_setts['with_cats']==1?"checked":""?>
		<input type="checkbox" id="with_cats" name="with_cats" value="1" <?=$checked?>>&nbsp;
	</td>
  </tr>
  <!-- Enable using categories //-->
  <!-- Enable show_in_main //-->
  <tr>
	<td><label for="show_in_main" style="cursor:pointer;">Enable show_in_main</label></td>
	<td>
		<?$checked=$TMPL_setts['show_in_main']==1?"checked":""?>
		<input type="checkbox" id="show_in_main" name="show_in_main" value="1" <?=$checked?>>&nbsp;
	</td>
  </tr>
  <!-- Enable show_in_main //-->
  <!-- Enable sponsored news //-->
  <tr>
	<td><label for="with_sponsored_news" style="cursor:pointer;">Enable sponsored news</label></td>
	<td>
		<?$checked=$TMPL_setts['with_sponsored_news']==1?"checked":""?>
		<input type="checkbox" id="with_sponsored_news" name="with_sponsored_news" value="1" <?=$checked?>>&nbsp;
	</td>
  </tr>
  <!-- Enable sponsored news //-->
  <!-- Enable Print //-->
  <tr>
	<td><label for="with_print" style="cursor:pointer;">Enable <b>Print</b></label></td>
	<td>
		<?$checked=$TMPL_setts['with_print']==1?"checked":""?>
		<input type="checkbox" id="with_print" name="with_print" value="1" <?=$checked?>>&nbsp;
	</td>
  </tr>
  <!-- Enable Print //-->
  <!-- Enable WYSIWYG //-->
  <tr>
	<td><label for="with_wysiwyg" style="cursor:pointer;">Enable <b>wysiwyg</b></label></td>
	<td>
		<?$checked=$TMPL_setts['with_wysiwyg']==1?"checked":""?>
		<input type="checkbox" id="with_wysiwyg" name="with_wysiwyg" value="1" <?=$checked?>>&nbsp;
	</td>
  </tr>
  <!-- Enable WYSIWYG //-->
  <!-- Enable SHAW DATE //-->
  <tr>
	<td><label for="with_show_date" style="cursor:pointer;">Enable <b>show date</b></label></td>
	<td>
		<?$checked=$TMPL_setts['with_show_date']==1?"checked":""?>
		<input type="checkbox" id="with_show_date" name="with_show_date" value="1" <?=$checked?>>&nbsp;
	</td>
  </tr>
  <!-- Enable SHAW DATE //-->
  <!-- Enable SHAW DATE //-->
  <tr>
	<td><label for="with_show_img" style="cursor:pointer;">Enable <b>show img</b></label></td>
	<td>
		<?$checked=$TMPL_setts['with_show_img']==1?"checked":""?>
		<input type="checkbox" id="with_show_img" name="with_show_img" value="1" <?=$checked?>>&nbsp;
	</td>
  </tr>
  <!-- Enable SHAW DATE //-->
  <!-- Default selected mid's //-->
  <tr>
	<td>Default selected mid's <span style="color:red;">(separate with <b>','</b>)</span></td>
	<td><input type="text" name="default_mid" value="<?=$TMPL_setts['default_mid']?>" class="formField1" style="width:120px;"></td>
  </tr>
  <!-- Default selected mid's //-->
  <tr>
	<td class="border_gray1" colspan="2"><input type="submit" value="<?=$TEXT['global']['save']?>"  class="formButton2"></td>
  </tr>
</table>
	<input type="hidden" name="action" value="save"> 
	<input type="hidden" name="pid" value="<?=$TMPL_pid?>" >
</form> 