<?if(!defined('ALLOW_ACCESS')) exit;?>
<form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="action" value="save" /> <input type="hidden"
		name="pid" value="<?=$TMPL_pid?>" />

	<table width="500" border="0" cellspacing="0" cellpadding="5"
		class="text1" align="center">
		<tr>
			<td colspan="2" class="border_gray1"><b><?=$TMPL_plugin?></b></td>
		</tr>

		<!-- Records Per Page //-->
		<tr>
			<td>Records Per Page</td>
			<td><input type="text" name="per_page"
				value="<?=$TMPL_setts['per_page']?>" class="formField1"
				style="width: 40px;" /></td>
		</tr>
		<!-- / -->

		<!-- Default selected mid's //-->
		<tr>
			<td class="border_gray1" colspan="2"><input type="submit"
				value="<?=$TEXT['global']['save']?>" class="formButton2" /></td>
		</tr>
	</table>
</form>
