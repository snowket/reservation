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


		<?for($i=0;$i<count($TMPL_lang);$i++){?>
		<tr>
			<td><b><?=$TEXT['block']?>:</b> (<?=$TMPL_lang[$i]?>)</td>
			<td nowrap><input type="text" name="title[<?=$TMPL_lang[$i]?>]" value="<?=$TMPL_title[$TMPL_lang[$i]]?>" class="formField1" style="width:320px"></td>
		</tr>
		<?}?>
			<tr>
				<td><b><?=$TEXT['floor_number']?>:</b></td>
				<td nowrap><input type="text" name="floors" value="<?=$TMPL_floors?>" class="formField1" style="width:320px"></td>
			</tr>



		<tr>
			<td>&nbsp;</td>
			<td align="right"><br><input type="submit" id="addcat_button"  value="<?=$_GET['action']?$_GET['action']:'add'?>" class="formButton2"></td>
		</tr>
	</table>
	<input type="hidden" name="action" id="action" value="<?=$_GET['action']?$_GET['action']:'add'?>">
</form>

<?if ($_GET['action']=='edit') {?>
<h1 class="text1"><?=$TEXT['rooms_floor']?></h1>
<form name="name" action="index.php?m=room_management&tab=blocks" method="post" enctype="multipart/form-data">
<table style="margin: 20px; margin-left:<? echo intval($SETTINGS['th_width'])+20 ?>" border="0" cellspacing="0" cellpadding="2" class="text1">
	<?foreach ($blocks as $k=>$v) { ?>
	<tr>
		<td><?=$TEXT['floor']?> <?=$v['floor']?></td>
		<td>
			<input type="text" name="rooms[<?=$v['id']?>]" value="<?=$v['rooms']?>" class="formField1" style="width:50px">
		</td>
	</tr>
	<?}?>
	<tr>
		<td>&nbsp;</td>
		<td align="right"><br><input type="submit" id="addcat_button"  value="save" class="formButton2"></td>
	</tr>
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="block_id" value="<?=$_GET['pid']?>">
</table>
</form>
<?}?>
