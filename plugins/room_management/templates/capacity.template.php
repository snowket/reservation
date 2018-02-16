<form action="" id="addcat_form" method="post" enctype="multipart/form-data" onsubmit="document.getElementById('addcat_button').disabled=true">
    <table width="600" style="margin: 20px; margin-left:<? echo intval($SETTINGS['th_width'])+20 ?>" border="0" cellspacing="0" cellpadding="2" class="text1">

		<!-- ERRORS //-->
		<tr>
			<td colspan="2" align="center">
				<table border="0" cellspacing="0" cellpadding="2" class="text1"><tr><td class="err">
				<?=$TMPL_errors?>
				</td></tr></table>
			</td>
		</tr>
		<!-- ERRORS //-->
		<tr>
		  <th>
		    <b>სახელი ოთახის ტიპის მიხედვით</b>
		  </th>
      <td>
        <select name="" id="fill_names" class="select" style="width:320px;">
          <?php foreach ($caps_name as $key => $value): ?>
              <option value="<?=$value['id']?>"><?=$value['title']?></option>
          <?php endforeach; ?>
        </select>
      </td>
		</tr>

		<?for($i=0;$i<count($TMPL_lang);$i++){?>
		<tr>
			<td><b><?=$TEXT['title']?></b> (<?=$TMPL_lang[$i]?>)</td>
			<td nowrap><input type="text" id="title-<?=$TMPL_lang[$i]?>" name="title[<?=$TMPL_lang[$i]?>]" value="<?=$TMPL_title[$TMPL_lang[$i]]?>" class="formField1" style="width:320px"></td>
		</tr>
		<?}?>
		<tr>
			<td><b><?=$TEXT['number_of_adult']?>:</b></td>
			<td nowrap><input type="text"  name="capacity" value="<?=$TMPL_capacity?>" class="formField1" style="width:320px"></td>
		</tr>
		<tr>
			<td><b><?=$TEXT['number_of_childrens']?>:</b></td>
			<td nowrap><input type="text"  name="childrens" value="<?=$TMPL_childrens?>" class="formField1" style="width:320px"></td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td align="right"><br><input type="submit" id="addcat_button"  value="  <?=$TEXT['global']['add']?>  " class="formButton2"></td>
		</tr>
	</table>
	<input type="hidden" name="action" id="action" value="<?=$_GET['action']?$_GET['action']:'add'?>">
</form>
<script>
  $('#fill_names').change(function(){
    var id=$(this).val();
    $.post( "index_ajax.php?cmd=get_room_types", { id: id},{async:false})
    .done(function( data ) {
      var info=jQuery.parseJSON(data);
      console.log(info.title);
      $.each(info.title,function(index,value){
        console.log(index,value);
        $('#title-'+index).val(value);
      })
    });
    console.log();
  })
</script>
