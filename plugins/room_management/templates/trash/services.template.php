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
        <? if(count($TMPL_services_types)>0){?>
        <tr>
            <td><?=$TEXT['extra_services_type']?></td>
            <td nowrap>
                <select name="service_type">
                    <? foreach($TMPL_services_types AS $services_type){ ?>
                        <option value="<?=$services_type['id']?>" <?=($services_type['id']==$TMPL_service['type_id'])?'selected':''?>><?=$services_type['title'] ?></option>
                    <?}?>
                </select>
            </td>
        </tr>
        <?}?>
		<? for($i=0;$i<count($TMPL_lang);$i++){ ?>
		<tr>
			<td><b><?=$TEXT['extra_services']?></b> (<?=$TMPL_lang[$i]?>)</td>
			<td nowrap><input type="text" name="title[<?=$TMPL_lang[$i]?>]" value="<?=$TMPL_service['title'][$TMPL_lang[$i]]?>" class="formField1" style="width:320px"></td>
		</tr>
		<?}?>
		<tr>
			<td><b><?=$TEXT['price']?> <?=$TEXT['cur']?>:</b></td>
			<td nowrap><input type="text" name="price" value="<?=$TMPL_service['price']?>" class="formField1" style="width:320px"></td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td align="right"><br><input type="submit" id="addcat_button"  value="  <?=$TEXT['global']['add']?>  " class="formButton2"></td>
		</tr>
	</table>
	<input type="hidden" name="action" id="action" value="<?=$_GET['action']?$_GET['action']:'add'?>">
</form>